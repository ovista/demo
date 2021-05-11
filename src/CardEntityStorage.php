<?php

namespace Drupal\demo;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\demo\Entity\CardEntityInterface;

/**
 * Defines the storage handler class for Card entities.
 *
 * This extends the base storage class, adding required special handling for
 * Card entities.
 *
 * @ingroup demo
 */
class CardEntityStorage extends SqlContentEntityStorage implements CardEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CardEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {card_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {card_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(CardEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {card_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('card_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
