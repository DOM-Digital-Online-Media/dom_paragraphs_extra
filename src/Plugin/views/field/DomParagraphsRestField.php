<?php

namespace Drupal\dom_paragraphs_extra\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\EntityField;
use Drupal\views\ResultRow;

/**
 * Field handler which provides REST export ability to referenced entities.
 *
 * @ViewsField("dom_paragraphs_rest_field")
 */
class DomParagraphsRestField extends EntityField {

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    return parent::defineOptions() + [
      'fields' => [
        'default' => [],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $fields = $this->entityFieldManager->getFieldStorageDefinitions($this->getTargetEntityType());
    $field_options = [];
    foreach ($fields as $field) {
      $field_options[$field->getName()] = $field->getLabel();
    }

    // Allow to choose fields for output.
    $form['fields'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Fields'),
      '#options' => $field_options,
      '#default_value' => $this->options['fields'],
      '#required' => TRUE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function advancedRender(ResultRow $values) {
    $data = [];
    $items = $this->getItems($values);
    $entity_type = $this->getTargetEntityType();
    foreach ($items as $delta => $item) {
      $raw = $item['raw'] ?? NULL;
      $value = $raw ? $raw->getValue() : [];

      if (!empty($value['target_id']) && !empty($entity_type)) {
        $entity = $this->entityTypeManager
          ->getStorage($entity_type)
          ->load($value['target_id']);

        foreach (array_filter($this->options['fields']) as $field) {
          $data[$delta][$field] = $entity->get($field)->getValue();
        }
      }
    }
    return $data;
  }

  /**
   * Returns target entity type for entityreference field.
   *
   * @return string
   *   Entity type id.
   */
  protected function getTargetEntityType() {
    $definition = $this->entityFieldManager
      ->getFieldStorageDefinitions($this->getEntityType())[$this->definition['field_name']];
    return $definition->getSetting('target_type');
  }

}
