<?php

namespace Drupal\ga\Event;

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
   * @var SettingItemInterface[]
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
   * @param SettingItemInterface $item
   *   An analytics command item or group.
   */
  public function addCommand(SettingItemInterface $item) {
    $this->commands[] = $item;
  }

  /**
   * Format the commands for use in drupalSettings.
   *
   * @return array
   *   An array of commands for use in drupalSettings.
   */
  public function getDrupalSettingCommands() {
    // sortByPriority is provided by PrioritySorterTrait.
    usort($this->commands, 'self::sortByPriority');

    return array_reduce($this->commands, 'self::settingReducer', []);
  }

  /**
   * Callback for array_reduce to transform setting item objects.
   *
   * @param array $carry
   *   The return value or previous iterations.
   * @param SettingItemInterface $item
   *   The value of the current iteration.
   *
   * @return array
   *   An array of commands formatted for JSON encoding.
   */
  private static function settingReducer(array $carry, SettingItemInterface $item) {
    $carry[] = $item->getSettingCommand();
    return $carry;
  }

}
