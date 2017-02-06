<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Class RequirePlugin.
 *
 * Since 'require' is a php reserved word, the class name needs to be longer.
 */
class RequirePlugin implements SettingItemInterface {

  use PrioritizedTrait;

  const DEFAULT_PRIORITY = 250;

  /**
   * The plugin name.
   *
   * @var string
   */
  protected $pluginName;

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
   * @param string $plugin_name
   *   The plugin name.
   * @param array $fields_object
   *   A map of values for the command's fieldsObject parameter.
   * @param string $tracker_name
   *   The tracker name (optional).
   * @param int $priority
   *   The command priority.
   */
  public function __construct($plugin_name, array $fields_object = [], $tracker_name = NULL, $priority = self::DEFAULT_PRIORITY) {
    $this->pluginName = $plugin_name;
    $this->fieldsObject = $fields_object;
    $this->trackerName = $tracker_name;
    $this->priority = $priority;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    $command = [
      ($this->trackerName ? $this->trackerName . '.' : '') . 'require',
      $this->pluginName,
    ];


    if (!empty($this->fieldsObject)) {
      $command[] = $this->fieldsObject;
    }

    return $command;
  }

}
