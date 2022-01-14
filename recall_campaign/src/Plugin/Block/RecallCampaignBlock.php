<?php

namespace Drupal\recall_campaign\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\recall_campaign\RecallCampaignStorage;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "recall_campaign_block",
 *   admin_label = @Translation("Recall Campaign Block"),
 * )
 */
class RecallCampaignBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
  }


  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['recall_campaign_block_settings'] = $form_state->getValue('recall_campaign_block_settings');
  }
}