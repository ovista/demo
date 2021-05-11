<?php

namespace Drupal\demo;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface CardEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Card revision IDs for a specific Card.
   *
   * @param \Drupal\demo\Entity\CardEntityInterface $entity
   *   The Card entity.
   *
   * @return int[]
   *   Card revision IDs (in ascending order).
   */
  public function revisionIds(CardEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Card author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Card revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\demo\Entity\CardEntityInterface $entity
   *   The Card entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(CardEntityInterface $entity);

  /**
   * Unsets the language for all Card with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
