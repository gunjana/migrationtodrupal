<?php

namespace Drupal\migration_to_drupal;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the cities data entity type.
 */
class CitiesDataListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['city'] = $this->t('City Name');
    $header['pop'] = $this->t('Pop');
    $header['state'] = $this->t('State');
    $header['location'] = $this->t('Location');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    $row['city'] = $entity->get('city')->getValue()[0]["value"];
    $row['pop'] = $entity->get('pop')->getValue()[0]["value"];
    $row['state'] = $entity->get('state')->getValue()[0]["value"];
    $row['location'] = $entity->get('location')->getValue()[0]["value"];
    return $row + parent::buildRow($entity);
  }

}
