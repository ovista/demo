<?php

namespace Drupal\demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\demo\Entity\CardEntity as Card;

/**
 * Provides a 'CardListingBlock' block.
 *
 * @Block(
 *  id = "card_listing_block",
 *  admin_label = @Translation("Card listing block"),
 * )
 */
class CardListingBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['orientation'] = [
      '#type' => 'select',
      '#options' => [
        'landscape' => 'Landscape',
        'portrait' => 'Portrait',
      ],
      '#title' => $this->t('Orientation'),
      '#description' => $this->t('Portrait or landscape'),
      '#default_value' => $this->configuration['orientation'],
      '#weight' => '10',
    ];
    
    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Heading'),
      '#description' => $this->t('Heading'),
      '#default_value' => $this->configuration['heading'],
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '11',
    ];

    $form['cards'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Cards'),
      '#target_type' => 'card',
      '#tags' => TRUE,
      '#description' => $this->t('Cards'),
      '#default_value' => !empty($this->configuration['cards']) ? Card::loadMultiple($this->configuration['cards']) : NULL,
      '#weight' => '12',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['orientation'] = $form_state->getValue('orientation');
    $this->configuration['heading'] = $form_state->getValue('heading');
    $this->configuration['cards'] = array_map(function ($item) { return $item['target_id']; }, $form_state->getValue('cards'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $view_mode = $this->configuration['orientation'];
    foreach (Card::loadMultiple($this->configuration['cards']) as $card) {
      $elements[] = \Drupal::entityTypeManager()->getViewBuilder('card')->view($card, $view_mode);
    }
     
    return array(
      '#theme' => 'card_listing_block',
      ['#markup' => $this->configuration['heading']],
      $elements,
    );
  }

}
