<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Class SetDimension.
 */
class SetDimension extends Set {

  /**
   * SetDimension constructor.
   *
   * @param int $index
   *   The dimension index.
   * @param mixed $value
   *   The dimension value.
   * @param array $fields_object
   *   A set of additional options for the command.
   * @param string $tracker_name
   *   The tracker name.
   * @param int $priority
   *   The command priority.
   */
  public function __construct($index, $value, array $fields_object = [], $tracker_name = NULL, $priority = self::DEFAULT_PRIORITY) {
    if (!is_int($index)) {
      throw new \InvalidArgumentException("Dimension index must be a positive integer.");
    }

    parent::__construct('dimension' . $index, $value, $fields_object, $tracker_name, $priority);
  }

}
