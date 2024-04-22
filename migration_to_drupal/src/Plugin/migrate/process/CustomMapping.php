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
    $mapping_config = \Drupal::config('migration_to_drupal.mapping');
    if (!is_null($mapping_config->get($destination_property))) {
      $source_key = $mapping_config->get($destination_property);
      if ($source_key == 'loc') {
        $loc = [
          "lat" => $row->getSource()['location'][0],
          "lng" => $row->getSource()['location'][1],
        ];
        return $loc;
      }
      return $row->getSourceProperty($source_key);
    }
    return NULL;
  }

}
