<?php

namespace Drupal\migration_to_drupal\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Extract city data from database.
 *
 * @MigrateSource(
 *   id = "migrate_mongo"
 * )
 */
class MigrateMongo extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $mb = \Drupal::service('mongodb.tools');
    $collection = $mb->find("drupal", "city_data", "{}");
    return $collection;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields['_id'] = $this->t('Id');
    $fields['city'] = $this->t('city');
    $fields['loc'] = $this->t('loc');
    $fields['state'] = $this->t('state');
    $fields['pop'] = $this->t('pop');
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      '_id' => [
        'type' => 'integer',
        'alias' => 'u',
      ],
    ];
  }

}
