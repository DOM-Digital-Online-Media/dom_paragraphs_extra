<?php

namespace Drupal\dom_paragraphs_extra;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Class DomParagraphsExtraAccess.
 */
class DomParagraphsExtraAccess {

  /**
   * Provides access check for dom_paragraphs_extra routes.
   */
  public function access(AccountInterface $account) {
    $access = FALSE;

    $route = \Drupal::routeMatch();
    switch ($route->getRouteName()) {
      case 'view.dom_paragraphs.dom_paragraphs_page':
        $type = $route->getParameter('arg_0');
        $access = $account->hasPermission('bypass paragraphs type content access') || $account->hasPermission("view paragraph content {$type}");
        break;

      case 'dom_paragraphs_extra.paragraph.add':
        $type = $route->getParameter('type');
        $access = $account->hasPermission('bypass paragraphs type content access') || $account->hasPermission("create paragraph content {$type}");
        break;

      case 'dom_paragraphs_extra.paragraph.edit':
        $paragraph = $route->getParameter('paragraph');
        $access = $account->hasPermission('bypass paragraphs type content access') || $account->hasPermission("update paragraph content {$paragraph->bundle()}");
        break;

      case 'dom_paragraphs_extra.paragraph.delete':
        $paragraph = $route->getParameter('paragraph');
        $access = $account->hasPermission('bypass paragraphs type content access') || $account->hasPermission("delete paragraph content {$paragraph->bundle()}");
        break;
    }

    return $access ? AccessResult::allowed() : AccessResult::forbidden();
  }

}
