<?php

namespace Drupal\ga\AnalyticsCommand\Linker;

use Drupal\ga\AnalyticsCommand\PrioritizedTrait;

/**
 * Class AutoLink.
 */
class AutoLink {

  use PrioritizedTrait;

  const DEFAULT_PRIORITY = 0;

  /**
   * A list of domains.
   *
   * @var array
   */
  protected $domains;

  /**
   * Add linker parameter to anchor rather than query.
   *
   * @var bool|null
   */
  protected $useAnchor;

  /**
   * Add linker parameters to form submissions.
   *
   * @var bool|null
   */
  protected $decorateForms;

  /**
   * The name of the tracker for this command.
   *
   * @var string
   */
  protected $trackerName;

  /**
   * Create constructor.
   *
   * @param array $domains
   *   A list of domains.
   * @param bool|null $useAnchor
   *   Add linker parameter to anchor rather than query (optional).
   * @param bool|null $decorateForms
   *   Add linker parameters to form submissions (optional).
   * @param string $tracker_name
   *   The tracker name (optional).
   * @param int $priority
   *   The command priority.
   */
  public function __construct(array $domains, $useAnchor = NULL, $decorateForms = NULL, $tracker_name = NULL, $priority = self::DEFAULT_PRIORITY) {
    $this->domains = $domains;
    $this->useAnchor = $useAnchor;
    $this->decorateForms = $decorateForms;
    $this->trackerName = $tracker_name;
    $this->priority = $priority;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    $command = [
      ($this->trackerName ? $this->trackerName . '.' : '') . 'linker:autoLink',
      $this->domains,
    ];

    if (!is_null($this->useAnchor)) {
      $command[] = $this->useAnchor;
    }
    // Add default value if later option is specified.
    elseif (!is_null($this->decorateForms)) {
      $command[] = FALSE;
    }

    if (!is_null($this->decorateForms)) {
      $command[] = $this->decorateForms;
    }

    return $command;
  }

}
