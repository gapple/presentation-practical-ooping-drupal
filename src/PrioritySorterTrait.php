<?php

namespace Drupal\ga;

use Drupal\ga\AnalyticsCommand\SettingGroupInterface;
use Drupal\ga\AnalyticsCommand\SettingItemInterface;

/**
 * Trait for classes that need to sort items by priority.
 *
 * @see PrioritizedTrait
 */
trait PrioritySorterTrait {

  /**
   * Sort callback to order by priority.
   *
   * @param SettingItemInterface|SettingGroupInterface $a
   *   First item.
   * @param SettingItemInterface|SettingGroupInterface $b
   *   Second item.
   *
   * @return int
   *   An integer less than, equal to, or greater than zero if the first
   *   argument is considered to be respectively less than, equal to, or greater
   *   than the second.
   */
  public static function sortByPriority($a, $b) {
    return $b->getPriority() - $a->getPriority();
  }

}
