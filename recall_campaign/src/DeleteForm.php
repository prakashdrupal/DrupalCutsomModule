<?php

namespace Drupal\recall_campaign;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DeleteForm extends ConfirmFormBase {
  protected $id;

  function getFormId() {
    return 'recall_campaign_delete';
  }

  function getQuestion() {
    return t('Are you sure you want to delete recall campaign %id ?', array('%id' => $this->id));
  }

  function getConfirmText() {
    return t('Delete');
  }

  function getCancelUrl() {
    return new Url('recall_campaign_list');
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $this->id = \Drupal::request()->get('id');
    return parent::buildForm($form, $form_state);
  }

  function submitForm(array &$form, FormStateInterface $form_state) {
    RecallCampaignStorage::delete($this->id);
    \Drupal::logger('recall_campaign')->notice('@type: deleted %vin.',
        array(
            '@type' => $this->id,
            '%vin' => $this->id,
        ));
    drupal_set_message(t('recall_campaign submission %id has been deleted.', array('%id' => $this->id)));
    $form_state->setRedirect('recall_campaign_list');
  }
}
