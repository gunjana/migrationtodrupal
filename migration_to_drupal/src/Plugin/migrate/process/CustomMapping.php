<?php

namespace Drupal\migration_to_drupal\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Provides a 'CustomMapping' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *   id = "custom_mapping"
 * )
 */
class CustomMapping extends ProcessPluginBase {

  /**
   * Maps Json attributes to custom entity attribute.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $mapping_config = \Drupal::config('migration_to_drupal.mapping')->getRawData();
    dump($mapping_config);
    die();
    if (isset($mapping_config[$destination_property]['source_key'])) {
      $source_key = $mapping_config[$destination_property]['source_key'];
      return $row->getSourceProperty($source_key);
    }
    return NULL;
  }
}
