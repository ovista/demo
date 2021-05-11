<?php

/**
 * @file
 * Contains card.page.inc.
 *
 * Page callback for Card entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Card templates.
 *
 * Default template: card.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_card(array &$variables) {
  // Fetch CardEntity Entity Object.
  $card = $variables['elements']['#card'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
