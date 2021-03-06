<?php

namespace Drupal\demo;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Card entity.
 *
 * @see \Drupal\demo\Entity\CardEntity.
 */
class CardEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\demo\Entity\CardEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished card entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published card entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit card entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete card entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add card entities');
  }


}
