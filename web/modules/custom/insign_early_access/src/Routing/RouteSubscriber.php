<?php

namespace Drupal\insign_early_access\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\insign_early_access\Form\VocabularyOverviewTerms;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // register form override
    if ($route = $collection->get('user.register')) {
      $route->setDefault('_form', 'Drupal\insign_early_access\Form\UserRegisterForm');
    }

    // Add status + remove delete and edit button for used codes
    if ($route = $collection->get('entity.taxonomy_vocabulary.overview_form')) {
      $route->setDefault('_form', VocabularyOverviewTerms::class);
      $collection->add('entity.taxonomy_vocabulary.overview_form', $route);
    }
  }
}
