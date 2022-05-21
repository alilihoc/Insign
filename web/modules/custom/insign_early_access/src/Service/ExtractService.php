<?php

namespace Drupal\insign_early_access\Service;

class ExtractService {

  /**
   * @param array|null $headers
   * @param array|null $content
   * @param string $fileType
   * @param string $delimiter
   * @param string $enclosure
   */
  public function extraction(
    ?array $headers,
    ?array $content,
    string $fileType = 'csv',
    string $delimiter = ',',
    string $enclosure = '"'
  ): void {
    $filename = $this->getFilename($fileType);
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Content-Type: text/csv');

    $fp = fopen("php://output", 'w');
    fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    fputcsv($fp, $headers, $delimiter, $enclosure);
    foreach ($content as $fields) {
      fputcsv($fp, $fields, $delimiter, $enclosure);
    }
    fclose($fp);
    exit;
  }

  /**
   * @param $fileType
   *
   * @return string
   */
  public function getFilename(string $fileType): string {
    return '/tmp/' . time() . '.' . $fileType;
  }
}
