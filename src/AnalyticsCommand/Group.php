<?php

namespace Drupal\ga\AnalyticsCommand;

use Drupal\ga\PrioritySorterTrait;

/**
 * A group of setting items or groups.
 */
class Group implements SettingGroupInterface {

  use PrioritizedTrait, PrioritySorterTrait;

  const DEFAULT_PRIORITY = 0;

  /**
   * The commands within this group.
   *
   * @var (SettingItemInterface|SettingGroupInterface)[]
   */
  protected $commands;

  /**
   * AnalyticsCommandGroup constructor.
   *
   * @param string $key
   *   The group name.
   * @param int $priority
   *   The group priority.
   */
  public function __construct($priority = self::DEFAULT_PRIORITY) {
    $this->priority = $priority;
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
   * An array of setting items or groups.
   *
   * @return (SettingItemInterface|SettingGroupInterface)[]
   *   An array of setting items or groups.
   */
  public function getCommands() {
    usort($this->commands, 'self::sortByPriority');
    return $this->commands;
  }
}
