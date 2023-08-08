<?php

namespace Drupal\dom_paragraphs_extra\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;

/**
 * Derivative class that provides the menu links for DOM Paragraphs.
 */
class DomParagraphsExtraLink extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs a new DomParagraphsExtraLink object.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info service.
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_type_bundle_info) {
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    foreach ($this->entityTypeBundleInfo->getBundleInfo('paragraph') as $key => $bundle) {
      $links[] = [
        'title' => $bundle['label'],
        'route_name' => 'view.dom_paragraphs.dom_paragraphs_page',
        'route_parameters' => ['arg_0' => $key],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
