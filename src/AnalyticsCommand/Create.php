<?php

namespace Drupal\ga\AnalyticsCommand;

/**
 * Class Create.
 */
class Create implements SettingItemInterface {

  use PrioritizedTrait;

  const DEFAULT_PRIORITY = 300;

  /**
   * The Google Analytics property ID.
   *
   * @var string
   */
  protected $trackingId;

  /**
   * The analytics cookie domain.
   *
   * @var string
   */
  protected $cookieDomain;

  /**
   * The name of the tracker for this command.
   *
   * @var string
   */
  protected $trackerName;

  /**
   * A map of values for the command's fieldsObject parameter.
   *
   * @var array
   */
  protected $fieldsObject;

  /**
   * Create constructor.
   *
   * @param string $tracking_id
   *   A Google Analytics property ID.
   * @param string $cookie_domain
   *   The cookie domain.
   * @param string $tracker_name
   *   The tracker name.
   * @param array $fields_object
   *   A set of additional options for the command.
   * @param int $priority
   *   The command priority.
   */
  public function __construct($tracking_id, $cookie_domain = 'auto', $tracker_name = NULL, array $fields_object = [], $priority = self::DEFAULT_PRIORITY) {
    $this->trackingId = $tracking_id;
    $this->cookieDomain = $cookie_domain;
    $this->trackerName = $tracker_name;
    $this->fieldsObject = $fields_object;
    $this->priority = $priority;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    $command = [
      'create',
      $this->trackingId,
      $this->cookieDomain,
    ];

    if (!empty($this->trackerName)) {
      $command[] = $this->trackerName;
    }
    if (!empty($this->fieldsObject)) {
      $command[] = $this->fieldsObject;
    }

    return $command;
  }

}
