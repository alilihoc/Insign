<?php

namespace Drupal\insign_early_access\Form;

use Drupal\taxonomy\Form\OverviewTerms;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\VocabularyInterface;

class VocabularyOverviewTerms extends OverviewTerms {

  public function buildForm(array $form, FormStateInterface $form_state, VocabularyInterface $taxonomy_vocabulary = NULL) {
    $form = parent::buildForm( $form, $form_state, $taxonomy_vocabulary);
    if($taxonomy_vocabulary && $taxonomy_vocabulary->id() !== 'code_a_usage_unique') {
      return $form;
    }

    $form['terms']['#header'] = array_merge(array_slice($form['terms']['#header'], 0, 1, TRUE),
      [t('Status')],
      [t("Nom d'utilisateur")],
      array_slice($form['terms']['#header'], 1, NULL, TRUE));

    foreach ($form['terms'] as &$term) {
      if (is_array($term) && !empty($term['#term'])) {
        $status['status'] = [
          '#markup' => ($term['#term']->field_status->value) ? t('Utilisé') : t('Non utilisé'),
          '#type' => 'item',
        ];

        $userEmail['user_email'] = [
          '#markup' => ($term['#term']->field_user_email->value) ? $term['#term']->field_user_email->value : NULL,
          '#type' => 'item',
        ];

        if ($term['#term']->field_status->value) {
          unset($term['operations']['#links']);
        }

        $term = array_slice($term, 0, 1, TRUE) +
          $status +
          $userEmail +
          array_slice($term, 1, NULL, TRUE);
      }
    }

    return $form;
  }

}
