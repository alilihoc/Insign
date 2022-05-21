<?php

namespace Drupal\insign_early_access\Controller;


class ExportController {

  public function exportUsersCode() {
    $headers = [
      'Email',
      'code'
    ];

    $content = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties([
          'vid' => 'code_a_usage_unique',
          'field_status' => 1
        ]
      );

    $results = [];
    foreach ($content as $term) {
      $results[] = [
        $term->field_user_email->value,
        $term->name->value
      ];
    }

    \Drupal::service('insign_early_access.service.extract')->extraction($headers, $results);

  }
}
