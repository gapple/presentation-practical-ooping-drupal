<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Interface for a single command.
 */
interface SettingItemInterface {

  /**
   * An integer value for sorting by priority.
   *
   * @return int
   *   The priority value.
   */
  public function getPriority();

  /**
   * A command to be sent to Google Analytics.
   *
   * @return array
   *   An array of command values.
   */
  public function getSettingCommand();

}
