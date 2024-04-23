<?php

namespace Drupal\migration_to_drupal\Plugin\migrate\source;

use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Provides a 'CustomSource' migrate process plugin.
 *
 * @MigrateSource(
 *   id = "custom_source"
 * )
 */
class CustomSource extends DrupalSqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('city_data', 'c')->fields('c');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'city' => $this->t('City Name'),
      'identifier' => $this->t('ID'),
      'pop' => $this->t('Pop'),
      'state' => $this->t('State'),
      'location' => $this->t('Location'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['identifier']['type'] = 'string';
    return $ids;
  }
}
