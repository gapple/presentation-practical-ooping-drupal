<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Class Send.
 */
class Send implements SettingItemInterface {

  use PrioritizedTrait;

  const DEFAULT_PRIORITY = -10;

  /**
   * The event hitType parameter.
   *
   * @var string
   */
  protected $hitType;

  /**
   * A map of values for the command's fieldsObject parameter.
   *
   * @var array
   */
  protected $fieldsObject;

  /**
   * The name of the tracker for this command.
   *
   * @var string
   */
  protected $trackerName;

  /**
   * Send constructor.
   *
   * @param string $hit_type
   *   The event hitType.
   * @param array $fields_object
   *   A map of values for the command's fieldsObject parameter.
   * @param string $tracker_name
   *   The tracker name (optional).
   * @param int $priority
   *   The command priority.
   */
  public function __construct($hit_type, array $fields_object = [], $tracker_name = NULL, $priority = self::DEFAULT_PRIORITY) {

    if (!in_array($hit_type, ["pageview", "event", "social", "timing"])) {
      throw new \InvalidArgumentException("Invalid hit type specified.");
    }

    $this->hitType = $hit_type;
    $this->fieldsObject = $fields_object;
    $this->trackerName = $tracker_name;
    $this->priority = $priority;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    $command = [
      ($this->trackerName ? $this->trackerName . '.' : '') . 'send',
      $this->hitType,
    ];

    if (!empty($this->fieldsObject)) {
      $command[] = $this->fieldsObject;
    }

    return $command;
  }

}
