<?php

namespace Drupal\dom_paragraphs_extra\Plugin\Menu;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

/**
 * Class DomParagraphsExtraDerivative.
 */
class DomParagraphsExtraAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return parent::getCacheContexts() + ['url'];
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    return ['type' => $route_match->getRawParameter('arg_0')];
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(RouteMatchInterface $route_match) {
    $type = $route_match->getRawParameter('arg_0');
    return ['query' => ['destination' => Url::fromRoute('view.dom_paragraphs.dom_paragraphs_page', ['arg_0' => $type])->toString()]];
  }

}
