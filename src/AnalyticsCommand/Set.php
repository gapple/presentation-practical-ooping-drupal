<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Class Set.
 */
class Set implements SettingItemInterface {

  use PrioritizedTrait;

  const DEFAULT_PRIORITY = 100;

  /**
   * The setting key.
   *
   * @var string
   */
  protected $settingKey;

  /**
   * The setting value.
   *
   * @var mixed
   */
  protected $settingValue;

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
   * Create constructor.
   *
   * @param string $setting_key
   *   The setting key.
   * @param mixed $setting_value
   *   The setting value.
   * @param array $fields_object
   *   A set of additional options for the command.
   * @param string $tracker_name
   *   The tracker name.
   * @param int $priority
   *   The command priority.
   */
  public function __construct($setting_key, $setting_value, array $fields_object = [], $tracker_name = NULL, $priority = self::DEFAULT_PRIORITY) {
    $this->settingKey = $setting_key;
    $this->settingValue = $setting_value;
    $this->fieldsObject = $fields_object;
    $this->trackerName = $tracker_name;
    $this->priority = $priority;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    $command = [
      ($this->trackerName ? $this->trackerName . '.' : '') . 'set',
      $this->settingKey,
      $this->settingValue,
    ];

    if (!empty($this->fieldsObject)) {
      $command[] = $this->fieldsObject;
    }

    return $command;
  }

}
