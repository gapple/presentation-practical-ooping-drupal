<?php

namespace Drupal\Tests\ga\Unit\AnalyticsCommand;

use Drupal\ga\AnalyticsCommand\Timing;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\ga\AnalyticsCommand\Timing
 * @group ga
 */
class TimingTest extends UnitTestCase {

  /**
   * Test the default priority.
   */
  public function testDefaultPriority() {
    $command = new Timing('category', 'var', 10);

    $this->assertEquals(-10, $command->getPriority());
  }

  /**
   * Test the command array for a basic command without additional options.
   */
  public function testBasic() {
    $command = new Timing('category', 'var', 10);

    $this->assertEquals(
      ['send', 'timing', 'category', 'var', 10],
      $command->getSettingCommand()
    );
  }

  /**
   * Test the command array when label is provided.
   */
  public function testWithLabel() {
    $command = new Timing('category', 'var', 10, 'label');

    $this->assertEquals(
      ['send', 'timing', 'category', 'var', 10, 'label'],
      $command->getSettingCommand()
    );
  }

  /**
   * Test the command array when label and a zero value are provided.
   */
  public function testWithLabelAndZeroValue() {
    $command = new Timing('category', 'var', 0, 'label');

    $this->assertEquals(
      ['send', 'timing', 'category', 'var', 0, 'label'],
      $command->getSettingCommand()
    );
  }

  /**
   * Test with a float value.
   *
   * @expectedException \InvalidArgumentException
   *
   * @expectedExceptionMessage Timing value must be an integer
   */
  public function testWithFloatValue() {
    new Timing('category', 'var', NULL, 1.5);
  }

  /**
   * Test with a negative integer value.
   *
   * Negative values are not proscribed according to the documentation.
   * https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#timingValue
   */
  public function testWithNegativeIntegerValue() {
    $command = new Timing('category', 'var', -10);

    $this->assertEquals(
      ['send', 'timing', 'category', 'var', -10],
      $command->getSettingCommand()
    );
  }

  /**
   * Test with a string event value.
   *
   * @expectedException \InvalidArgumentException
   *
   * @expectedExceptionMessage Timing value must be an integer
   */
  public function testWithStringValue() {
    new Timing('category', 'var', '10');
  }

  /**
   * Test the command array when values are provided in fieldsObject.
   */
  public function testWithFieldsObject() {
    $command = new Timing('category', 'var', 10, NULL, ['field1' => 'value1']);

    $this->assertEquals(
      ['send', 'timing', 'category', 'var', 10, ['field1' => 'value1']],
      $command->getSettingCommand()
    );
  }

  /**
   * Test the command when a tracker name is provided.
   */
  public function testWithTrackerName() {
    $command = new Timing('category', 'var', 10, NULL, [], 'tracker');

    $this->assertEquals(
      ['tracker.send', 'timing', 'category', 'var', 10],
      $command->getSettingCommand()
    );
  }

}
