<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Interface for a command group.
 *
 * A group may contain other groups.
 */
interface SettingGroupInterface {

  /**
   * An integer value for sorting by priority.
   *
   * @return int
   *   The priority value.
   */
  public function getPriority();

  /**
   * Add a setting item or group.
   *
   * @param SettingItemInterface|SettingGroupInterface $item
   *   The setting item or group to add.
   */
  public function addCommand($item);

  /**
   * The array of setting items ordered by priority.
   *
   * @return (SettingItemInterface|SettingGroupInterface)[]
   *   The array of setting items.
   */
  public function getCommands();

}
