<?php

namespace Drupal\demo\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Card entities.
 *
 * @ingroup demo
 */
interface CardEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Card name.
   *
   * @return string
   *   Name of the Card.
   */
  public function getName();

  /**
   * Sets the Card name.
   *
   * @param string $name
   *   The Card name.
   *
   * @return \Drupal\demo\Entity\CardEntityInterface
   *   The called Card entity.
   */
  public function setName($name);

  /**
   * Gets the Card creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Card.
   */
  public function getCreatedTime();

  /**
   * Sets the Card creation timestamp.
   *
   * @param int $timestamp
   *   The Card creation timestamp.
   *
   * @return \Drupal\demo\Entity\CardEntityInterface
   *   The called Card entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Card revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Card revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\demo\Entity\CardEntityInterface
   *   The called Card entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Card revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Card revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\demo\Entity\CardEntityInterface
   *   The called Card entity.
   */
  public function setRevisionUserId($uid);

}
