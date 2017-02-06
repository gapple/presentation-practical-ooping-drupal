<?php

namespace Drupal\Tests\ga\Unit\Event;

use Drupal\ga\AnalyticsCommand\Group;
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
   * @expectedExceptionMessage Argument passed to Drupal\ga\Event\CollectEvent::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface or Drupal\ga\AnalyticsCommand\SettingGroupInterface, string given
   */
  public function testAddScalar() {
    $this->event->addCommand('test');
  }

  /**
   * Test adding a stdClass object as a command.
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage Argument passed to Drupal\ga\Event\CollectEvent::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface or Drupal\ga\AnalyticsCommand\SettingGroupInterface, instance of stdClass given
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

  /**
   * Test a single group in the collection.
   */
  public function testGroup() {
    $group = new Group('test');

    $group->addCommand(new BasicCommand('one'));
    $group->addCommand(new BasicCommand('two'));

    $this->event->addCommand($group);

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one'], ['two']], $result);
  }

  /**
   * Test a collection with both items and groups.
   */
  public function testMixed() {
    $group = new Group('test');

    $group->addCommand(new BasicCommand('two'));
    $group->addCommand(new BasicCommand('three'));

    $this->event->addCommand(new BasicCommand('one'));
    $this->event->addCommand($group);
    $this->event->addCommand(new BasicCommand('four'));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one'], ['two'], ['three'], ['four']], $result);
  }

  /**
   * Test a collection with both items and groups, with priorities.
   */
  public function testMixedOrdered() {
    $group = new Group('test', 20);

    // Priorities within the group should be relative to the group.
    $group->addCommand(new BasicCommand('three', [], NULL, -100));
    $group->addCommand(new BasicCommand('two', [], NULL, 100));

    $this->event->addCommand($group);
    $this->event->addCommand(new BasicCommand('four', [], NULL, 10));
    $this->event->addCommand(new BasicCommand('one', [], NULL, 30));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals([['one'], ['two'], ['three'], ['four']], $result);
  }

  /**
   * Test a collection with a nested group.
   */
  public function testNestedGroup() {
    $innerGroup = new Group('test');
    $innerGroup->addCommand(new BasicCommand('four', [], NULL, -100));
    $innerGroup->addCommand(new BasicCommand('three', [], NULL, 100));

    $outerGroup = new Group('test', 20);
    $outerGroup->addCommand($innerGroup);
    $outerGroup->addCommand(new BasicCommand('five', [], NULL, -100));
    $outerGroup->addCommand(new BasicCommand('two', [], NULL, 100));

    $this->event->addCommand($outerGroup);
    $this->event->addCommand(new BasicCommand('six', [], NULL, 10));
    $this->event->addCommand(new BasicCommand('one', [], NULL, 30));

    $result = $this->event->getDrupalSettingCommands();
    $this->assertEquals(
      [['one'], ['two'], ['three'], ['four'], ['five'], ['six']],
      $result
    );
  }

}
