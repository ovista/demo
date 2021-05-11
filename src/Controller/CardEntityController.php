<?php

namespace Drupal\demo\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\demo\Entity\CardEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CardEntityController.
 *
 *  Returns responses for Card routes.
 */
class CardEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Card revision.
   *
   * @param int $card_revision
   *   The Card revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($card_revision) {
    $card = $this->entityTypeManager()->getStorage('card')
      ->loadRevision($card_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('card');

    return $view_builder->view($card);
  }

  /**
   * Page title callback for a Card revision.
   *
   * @param int $card_revision
   *   The Card revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($card_revision) {
    $card = $this->entityTypeManager()->getStorage('card')
      ->loadRevision($card_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $card->label(),
      '%date' => $this->dateFormatter->format($card->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Card.
   *
   * @param \Drupal\demo\Entity\CardEntityInterface $card
   *   A Card object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(CardEntityInterface $card) {
    $account = $this->currentUser();
    $card_storage = $this->entityTypeManager()->getStorage('card');

    $langcode = $card->language()->getId();
    $langname = $card->language()->getName();
    $languages = $card->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $card->label()]) : $this->t('Revisions for %title', ['%title' => $card->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all card revisions") || $account->hasPermission('administer card entities')));
    $delete_permission = (($account->hasPermission("delete all card revisions") || $account->hasPermission('administer card entities')));

    $rows = [];

    $vids = $card_storage->revisionIds($card);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\demo\CardEntityInterface $revision */
      $revision = $card_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $card->getRevisionId()) {
          $link = $this->l($date, new Url('entity.card.revision', [
            'card' => $card->id(),
            'card_revision' => $vid,
          ]));
        }
        else {
          $link = $card->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.card.translation_revert', [
                'card' => $card->id(),
                'card_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.card.revision_revert', [
                'card' => $card->id(),
                'card_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.card.revision_delete', [
                'card' => $card->id(),
                'card_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['card_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
