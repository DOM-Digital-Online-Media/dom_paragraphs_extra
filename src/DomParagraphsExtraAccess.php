<?php

namespace Drupal\dom_paragraphs_extra;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DomParagraphsExtraAccess.
 */
class DomParagraphsExtraAccess extends ControllerBase {

  /**
   * Current route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $currentRouteMatch;

  /**
   * Constructs DomParagraphsExtraAccess controller.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   Current route match service.
   */
  public function __construct(RouteMatchInterface $current_route_match) {
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('current_route_match'));
  }

  /**
   * Provides access check for dom_paragraphs_extra routes.
   */
  public function access(AccountInterface $account) {
    $access = FALSE;

    switch ($this->currentRouteMatch->getRouteName()) {
      case 'view.dom_paragraphs.dom_paragraphs_page':
        $type = $this->currentRouteMatch->getParameter('arg_0');
        $access = $account->hasPermission('bypass paragraphs type content access')
          || $account->hasPermission("view paragraph content {$type}");
        break;

      case 'dom_paragraphs_extra.paragraph.add':
        $type = $this->currentRouteMatch->getParameter('type');
        $access = $account->hasPermission('bypass paragraphs type content access')
          || $account->hasPermission("create paragraph content {$type}");
        break;

      case 'dom_paragraphs_extra.paragraph.edit':
        $paragraph = $this->currentRouteMatch->getParameter('paragraph');
        $access = $account->hasPermission('bypass paragraphs type content access')
          || $account->hasPermission("update paragraph content {$paragraph->bundle()}");
        break;

      case 'dom_paragraphs_extra.paragraph.delete':
        $paragraph = $this->currentRouteMatch->getParameter('paragraph');
        $access = $account->hasPermission('bypass paragraphs type content access')
          || $account->hasPermission("delete paragraph content {$paragraph->bundle()}");
        break;
    }

    return $access ? AccessResult::allowed() : AccessResult::forbidden();
  }

}
