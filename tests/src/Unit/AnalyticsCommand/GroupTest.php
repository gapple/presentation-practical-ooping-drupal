<?php

namespace Drupal\Tests\ga\Unit\Event;

use Drupal\ga\AnalyticsCommand\Group;
use Drupal\Tests\ga\BasicCommand;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\ga\AnalyticsCommand\Group
 * @group ga
 */
class GroupTest extends UnitTestCase {

  /**
   * Test an empty group.
   */
  public function testEmptyGroup() {
    $group = new Group();

    $this->assertEquals([], $group->getCommands());
  }

  /**
   * Test a single command in the collection.
   */
  public function testSingleItem() {
    $group = new Group();

    $commandOne = new BasicCommand('one');
    $group->addCommand($commandOne);

    $this->assertEquals(
      [$commandOne],
      $group->getCommands()
    );

    $this->assertEquals(
      [['one']],
      $group->getSettingCommands()
    );
  }

  /**
   * Test adding a scalar value as a command.
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage Argument passed to Drupal\ga\AnalyticsCommand\Group::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface or Drupal\ga\AnalyticsCommand\SettingGroupInterface, string given
   */
  public function testAddScalar() {
    $group = new Group();
    $group->addCommand('test');
  }

  /**
   * Test adding a stdClass object as a command.
   *
   * @expectedException \InvalidArgumentException
   * @expectedExceptionMessage Argument passed to Drupal\ga\AnalyticsCommand\Group::addCommand must be an instance of Drupal\ga\AnalyticsCommand\SettingItemInterface or Drupal\ga\AnalyticsCommand\SettingGroupInterface, instance of stdClass given
   */
  public function testAddStdClass() {
    $group = new Group();
    $group->addCommand((object) ['test' => 'value']);
  }

  /**
   * Test multiple items in the collection.
   */
  public function testMultipleItems() {
    $group = new Group();

    $commandOne = new BasicCommand('one');
    $group->addCommand($commandOne);
    $commandTwo = new BasicCommand('two');
    $group->addCommand($commandTwo);

    $this->assertEquals(
      [$commandOne, $commandTwo],
      $result = $group->getCommands()
    );
    $this->assertEquals(
      [['one'], ['two']],
      $result = $group->getSettingCommands()
    );
  }

  /**
   * Test multiple items added in an order different to their priority.
   */
  public function testMultipleOrderedItems() {
    $group = new Group();
    $commandTwo = new BasicCommand('two', [], NULL, 10);
    $commandOne = new BasicCommand('one', [], NULL, 50);
    $group->addCommand($commandOne);
    $group->addCommand($commandTwo);

    $this->assertEquals(
      [$commandOne, $commandTwo],
      $result = $group->getCommands()
    );
    $this->assertEquals(
      [['one'], ['two']],
      $result = $group->getSettingCommands()
    );
  }

  /**
   * Test a single group in the collection.
   */
  public function testNestedGroup() {
    $group = new Group();
    $nestedGroup = new Group();

    $commandOne = new BasicCommand('one');
    $commandTwo = new BasicCommand('two');

    $nestedGroup->addCommand($commandOne);
    $nestedGroup->addCommand($commandTwo);

    $group->addCommand($nestedGroup);

    $this->assertEquals(
      [$nestedGroup],
      $result = $group->getCommands()
    );
    $this->assertEquals(
      [['one'], ['two']],
      $result = $group->getSettingCommands()
    );
  }

  /**
   * Test a collection with both items and groups.
   */
  public function testMixed() {
    $group = new Group();
    $nestedGroup = new Group();

    $commandOne = new BasicCommand('one');
    $commandTwo = new BasicCommand('two');
    $commandThree = new BasicCommand('three');
    $commandFour = new BasicCommand('four');

    $nestedGroup->addCommand($commandTwo);
    $nestedGroup->addCommand($commandThree);

    $group->addCommand($commandOne);
    $group->addCommand($nestedGroup);
    $group->addCommand($commandFour);


    $this->assertEquals(
      [$commandOne, $nestedGroup, $commandFour],
      $group->getCommands()
    );
    $this->assertEquals(
      [['one'], ['two'], ['three'], ['four']],
      $group->getSettingCommands()
    );
  }

  /**
   * Test a collection with both items and groups, with priorities.
   */
  public function testMixedOrdered() {
    $group = new Group();
    $nestedGroup = new Group(NULL, 20);

    $commandOne = new BasicCommand('one', [], NULL, 30);
    $commandTwo = new BasicCommand('two', [], NULL, 100);
    $commandThree = new BasicCommand('three', [], NULL, -100);
    $commandFour = new BasicCommand('four', [], NULL, 10);

    $nestedGroup->addCommand($commandThree);
    $nestedGroup->addCommand($commandTwo);

    $group->addCommand($nestedGroup);
    $group->addCommand($commandFour);
    $group->addCommand($commandOne);


    $this->assertEquals(
      [$commandOne, $nestedGroup, $commandFour],
      $group->getCommands()
    );
    $this->assertEquals(
      [['one'], ['two'], ['three'], ['four']],
      $group->getSettingCommands()
    );
  }

}
