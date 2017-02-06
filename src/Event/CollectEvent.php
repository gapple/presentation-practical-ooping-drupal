<?php

namespace Drupal\ga\Event;

use Drupal\ga\AnalyticsCommand\SettingGroupInterface;
use Drupal\ga\AnalyticsCommand\SettingItemInterface;
use Drupal\ga\PrioritySorterTrait;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CollectEvent.
 */
class CollectEvent extends Event {

  use PrioritySorterTrait;

  /**
   * The registered analytics commands.
   *
   * @var (SettingItemInterface|SettingGroupInterface)[]
   */
  protected $commands;

  /**
   * CollectEvent constructor.
   */
  public function __construct() {
    $this->commands = [];
  }

  /**
   * Add a command item or group.
   *
   * @param SettingItemInterface|SettingGroupInterface $item
   *   An analytics command item or group.
   */
  public function addCommand($item) {
    if (!is_object($item)) {
      throw new \InvalidArgumentException("Argument passed to " . __METHOD__ . " must be an instance of Drupal\\ga\\AnalyticsCommand\\SettingItemInterface or Drupal\\ga\\AnalyticsCommand\\SettingGroupInterface, " . gettype($item) . " given");
    }
    elseif (
      !($item instanceof SettingItemInterface) &&
      !($item instanceof SettingGroupInterface)
    ) {
      throw new \InvalidArgumentException("Argument passed to " . __METHOD__ . " must be an instance of Drupal\\ga\\AnalyticsCommand\\SettingItemInterface or Drupal\\ga\\AnalyticsCommand\\SettingGroupInterface, instance of " . get_class($item) . " given");
    }

    $this->commands[] = $item;
  }

  /**
   * Format the commands for use in drupalSettings.
   *
   * @return array
   *   An array of commands for use in drupalSettings.
   */
  public function getDrupalSettingCommands() {
    usort($this->commands, 'self::sortByPriority');

    return array_reduce($this->commands, 'self::settingReducer', []);
  }

  /**
   * Callback for array_reduce to transform the various setting objects.
   *
   * @param array $carry
   *   The return value or previous iterations.
   * @param SettingItemInterface|SettingGroupInterface $item
   *   The value of the current iteration.
   *
   * @return array
   *   An array of commands formatted for JSON encoding.
   */
  private static function settingReducer(array $carry, $item) {

    if ($item instanceof SettingItemInterface) {
      $carry[] = $item->getSettingCommand();
    }
    elseif ($item instanceof SettingGroupInterface) {
      // Groups may contain items or nested groups, so process recursively.
      $carry = array_reduce($item->getCommands(), 'self::settingReducer', $carry);
    }
    // An exception is thrown when adding items if they aren't one of these
    // classes, so there isn't a need to handle alternate cases here.

    return $carry;
  }

}
