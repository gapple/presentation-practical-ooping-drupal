<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Trait for storing and exposing priority value of an object.
 *
 * @see SettingItemInterface
 * @see SettingGroupInterface
 */
trait PrioritizedTrait {

  /**
   * Priority integer.
   *
   * @var int
   */
  protected $priority;

  /**
   * An integer value for sorting by priority.
   *
   * @return int
   *   The priority value.
   */
  public function getPriority() {
    return $this->priority;
  }

}
