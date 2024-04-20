<?php

namespace Drupal\migration_to_drupal;

/**
 * Processes JSON data & imports to Drupal.
 */
class ProcessDataBatch {

  /**
   * Function to import json data to entity.
   */
  public static function processData($records, $mapping, &$context) {
    $message = 'Migrating Data from Json To Entity..';
    $results = [];
    foreach ($records as $record) {
      $dataArray = [];
      foreach ($mapping as $key => $value) {
        if ($value) {
          if ($value === "loc") {
            $dataArray[$key] = [
              'lat' => $record->$value[0],
              'lng' => $record->$value[1],
            ];
          }
          else {
            $dataArray[$key] = $record->$value;
          }
        }
      }
      $storage = \Drupal::entityTypeManager()->getStorage('cities_data');
      $entity = $storage->create($dataArray);
      $results[] = $entity->save();
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * Batch Finished callback.
   */
  public static function processFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'Successfully Migrated Data.', 'Successfully Migrated Data.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addMessage($message);
  }

}
