<?php

namespace Drupal\ga\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\ga\AnalyticsCommand\Create;
use Drupal\ga\AnalyticsCommand\Linker\AutoLink;
use Drupal\ga\AnalyticsCommand\Pageview;
use Drupal\ga\AnalyticsCommand\RequirePlugin;
use Drupal\ga\AnalyticsCommand\Set;
use Drupal\ga\AnalyticsEvents;
use Drupal\ga\Event\CollectEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DefaultCommandSubscriber.
 */
class DefaultCommandSubscriber implements EventSubscriberInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user service.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * User Entity Storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      AnalyticsEvents::COLLECT => [
        ['onCollectDefaultCommands'],
      ],
    ];
  }

  /**
   * DefaultCommandSubscriber constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory service.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The Entity Type Manager Service.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    AccountInterface $currentUser,
    EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->configFactory = $configFactory;
    $this->currentUser = $currentUser;
    $this->userStorage = $entityTypeManager->getStorage('user');
  }

  /**
   * Add default events according to configuration.
   *
   * @param \Drupal\ga\Event\CollectEvent $event
   *   The AnalyticsEvents::COLLECT event.
   */
  public function onCollectDefaultCommands(CollectEvent $event) {

    $config = $this->configFactory->get('ga.settings');

    if ($config->get('add_default_commands')) {

      // Initialize tracker or set tracker options.
      if (($tracking_id = $config->get('tracking_id'))) {
        // Add options which can be provided when initializing the tracker.
        $fieldsObject = [];

        if ($config->get('plugins.linker.enable')) {
          $fieldsObject['allowLinker'] = TRUE;
        }

        if ($config->get('track_user_id') && $this->currentUser->isAuthenticated()) {
          $account = $this->userStorage->load($this->currentUser->id());
          $fieldsObject['userId'] = $account->uuid();
        }

        if ($config->get('anonymize_ip')) {
          $fieldsObject['anonymizeIp'] = TRUE;
        }

        $event->addCommand(new Create($tracking_id, 'auto', NULL, $fieldsObject));
      }
      else {
        // If a trackingId isn't provided for initializing a tracker, these
        // options can be provided via set commands instead.
        if ($config->get('track_user_id') && $this->currentUser->isAuthenticated()) {
          $account = $this->userStorage->load($this->currentUser->id());
          $event->addCommand(new Set('userId', $account->uuid()));
        }

        if ($config->get('anonymize_ip')) {
          $event->addCommand(new Set('anonymizeIp', TRUE));
        }
      }

      if ($config->get('send_pageview')) {
        $event->addCommand(new Pageview());
      }

      // Enable Plugins.
      if ($config->get('plugins.linkid')) {
        $event->addCommand(new RequirePlugin('linkid'));
      }
      if ($config->get('plugins.displayfeatures')) {
        $event->addCommand(new RequirePlugin('displayfeatures'));
      }
      if ($config->get('plugins.linker.enable')) {
        // Note: 'allowLinker' must be set when creating the tracker for this
        // plugin to have an effect.
        $event->addCommand(new RequirePlugin('linker'));
        if (($domains = $config->get('plugins.linker.domains'))) {
          $event->addCommand(new AutoLink($domains));
        }
      }
    }
  }

}
