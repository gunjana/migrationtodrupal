<?php

namespace Drupal\migration_to_drupal\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MappingForm.
 *
 * Creates a Form to map json data attributes to entity.
 */
class MappingForm extends ConfigFormBase {

  /**
   * Entity field type manager service.
   *
   * @var Drupal\Core\Entity\EntityFieldManager
   */
  protected $fieldManager;

  /**
   * Module Handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct function for this controller.
   */
  public function __construct(EntityFieldManagerInterface $fieldManager, ModuleHandlerInterface $module_handler, EntityTypeManagerInterface $entity_type_manager) {
    $this->fieldManager = $fieldManager;
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_field.manager'),
      $container->get('module_handler'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'migration_to_drupal.mapping',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mapping_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('migration_to_drupal.mapping');

    $entity_fields = $this->fieldManager->getFieldDefinitions('cities_data', 'cities_data');
    unset($entity_fields["id"]);
    unset($entity_fields["uuid"]);
    unset($entity_fields["uid"]);
    unset($entity_fields["metatag"]);
    foreach ($entity_fields as $key => $value) {
      $form[$key] = [
        '#type' => 'textfield',
        '#title' => ($value instanceof BaseFieldDefinition) ? $value->getLabel()->getUntranslatedString() : $value->getLabel(),
        '#default_value' => $config->get($key),
      ];
    }
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Configuration'),
      '#button_type' => 'primary',
    ];
    $form['actions']['process'] = [
      '#type' => 'submit',
      '#value' => $this->t('Process Records'),
      '#button_type' => 'primary',
      '#submit' => [
        [$this, 'processForm'],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $entity_fields = $this->fieldManager->getFieldDefinitions('cities_data', 'cities_data');
    unset($entity_fields["id"]);
    unset($entity_fields["uuid"]);
    unset($entity_fields["uid"]);
    unset($entity_fields["metatag"]);
    foreach ($entity_fields as $key => $value) {
      $this->config('migration_to_drupal.mapping')->set($key, $form_state->getValue($key))->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function processForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('migration_to_drupal.mapping');
    $mapping = $config->getRawData();

    // Get the data from json file.
    $module_path = $this->moduleHandler->getModule('migration_to_drupal')->getPath();
    $data = file_get_contents($module_path . '/json_file/cities.json');
    $cities = json_decode($data);

    // Delete earlier entities.
    $ids = $this->entityTypeManager->getStorage('cities_data')->getQuery('AND')->accessCheck(TRUE)->execute();
    $storage_handler = $this->entityTypeManager->getStorage('cities_data');
    $entities = $storage_handler->loadMultiple($ids);

    $storage_handler->delete($entities);

    $chunks = array_chunk($cities, 5);
    foreach ($chunks as $chunk) {
      $operations[] = [
        '\Drupal\migration_to_drupal\ProcessDataBatch::processData',
        [$chunk, $mapping],
      ];
    }

    // Setting up batch process.
    $batch = [
      'title' => $this->t('Migrating Data from Json To Entity'),
      'operations' => $operations,
      'finished' => '\Drupal\migration_to_drupal\ProcessDataBatch::processFinishedCallback',
    ];
    $batch['progress_message'] = FALSE;
    batch_set($batch);
  }

}
