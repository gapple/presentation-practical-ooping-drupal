<?php

namespace Drupal\Tests\ga\Unit\Event;

use Drupal\ga\Event\CollectEvent;
use Drupal\Tests\ga\BasicCommand;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\ga\Event\CollectEvent
 * @group ga
 */
class CollectEventTest extends UnitTestCase {

  /**
   * Collect Event.
   *
   * @var CollectEvent
   */
  private $event;

  /**
   * Setup an event object for tests to use.
   */
  public function setUp() {
    parent::setUp();

    $this->event = new CollectEvent();
  }

  /**
   * Test an empty event collection.
   */
  public function testEmptyCollection() {
    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([], $result);
  }

  /**
   * Test a single command in the collection.
   */
  public function testSingleItem() {
    $this->event->addCommand(new BasicCommand('one'));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one']], $result);
  }

  /**
   * Test adding a scalar value as a command.
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage Argument passed to Drupal\ga\Event\CollectEvent::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface, string given
   */
  public function testAddScalar() {
    $this->event->addCommand('test');
  }

  /**
   * Test adding a stdClass object as a command.
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage Argument passed to Drupal\ga\Event\CollectEvent::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface, instance of stdClass given
   */
  public function testAddStdClass() {
    $this->event->addCommand((object) ['test' => 'value']);
  }

  /**
   * Test multiple items in the collection.
   */
  public function testMultipleItems() {
    $this->event->addCommand(new BasicCommand('one'));
    $this->event->addCommand(new BasicCommand('two'));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one'], ['two']], $result);
  }

  /**
   * Test multiple items added in an order different to their priority.
   */
  public function testMultipleOrderedItems() {
    $this->event->addCommand(new BasicCommand('two', [], NULL, 10));
    $this->event->addCommand(new BasicCommand('one', [], NULL, 50));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one'], ['two']], $result);
  }

}
