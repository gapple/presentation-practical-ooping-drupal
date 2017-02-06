<?php

namespace Drupal\Tests\ga;

use Drupal\ga\AnalyticsCommand\PrioritizedTrait;
use Drupal\ga\AnalyticsCommand\SettingItemInterface;

/**
 * Simple command for testing.
 */
class BasicCommand implements SettingItemInterface {
  use PrioritizedTrait;

  protected $command;

  /**
   * BasicCommand constructor.
   *
   * @param string $command
   *   Command name.
   */
  public function __construct($command) {
    $this->command = $command;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettingCommand() {
    return [$this->command];
  }

}
