<?php
/**
 * @file
 * Contains \Drupal\recall_campaign\AddForm.
 */

namespace Drupal\recall_campaign;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;
use Drupal\recall_campaign\RecallCampaignStorage;

class AddForm extends FormBase {
  protected $id;

  function getFormId() {
    return 'recall_campaign_add';
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $this->id = \Drupal::request()->get('id');
    $recall_campaign = RecallCampaignStorage::get($this->id);
    
    $form['vin'] = array(
      '#type' => 'textfield',
      '#title' => t('VIN'),
      '#required' => TRUE,
      '#default_value' => (!empty($recall_campaign->vin)) ? $recall_campaign->vin : '',
    );
    $form['field_recall_campaign_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Recall Campaign Name'),
      '#required' => FALSE,
      '#default_value' => (!empty($recall_campaign->field_recall_campaign_name)) ? $recall_campaign->field_recall_campaign_name : '',
    );
    $form['field_ref_no'] = array(
      '#type' => 'textfield',
      '#title' => t('Ref. no'),
      '#required' => FALSE,
      '#default_value' => (!empty($recall_campaign->field_ref_no)) ? $recall_campaign->field_ref_no : '',
    );
    $form['field_model'] = array(
      '#type' => 'textfield',
      '#title' => t('Model'),
      '#required' => FALSE,
      '#default_value' => (!empty($recall_campaign->field_model)) ? $recall_campaign->field_model : '',
    );    

    $form['field_error_msg'] = array(
    '#type' => 'textarea',
    '#title' => t('Error Message'),
    '#rows' => 5,
    '#cols' => 2,
    '#required' => FALSE,
    '#default_value' => (!empty($recall_campaign->field_error_msg)) ? $recall_campaign->field_error_msg : '',
    '#format' => 'plain',
    );

    $form['recall_campain_info'] = array(
    '#type' => 'textarea',
    '#title' => t('Recall campaign information'),
    '#rows' => 5,
    '#cols' => 2,
    '#required' => FALSE,
    '#default_value' => (!empty($recall_campaign->recall_campain_info)) ? $recall_campaign->recall_campain_info : '',
    );

    $form['field_situation'] = array(
    '#type' => 'textarea',
    '#title' => t('Situation'),
    '#rows' => 5,
    '#cols' => 2,
    '#required' => FALSE,
    '#default_value' => (!empty($recall_campaign->field_situation)) ? $recall_campaign->field_situation : '',
    );
    $form['field_effect'] = array(
    '#type' => 'textarea',
    '#title' => t('Effect'),
    '#rows' => 5,
    '#cols' => 2,
    '#required' => FALSE,
    '#default_value' => (!empty($recall_campaign->field_effect)) ? $recall_campaign->field_effect : '',
    );
    $form['field_measure'] = array(
    '#type' => 'textarea',
    '#title' => t('Measure'),
    '#rows' => 5,
    '#cols' => 2,
    '#required' => FALSE,
    '#default_value' => (!empty($recall_campaign->field_measure)) ? $recall_campaign->field_measure : '',
    );

    $form['field_vin_recall_status'] = array(
      '#type' => 'textfield',
      '#title' => t('Status'),
      '#required' => TRUE,
      '#default_value' => (!empty($recall_campaign->field_vin_recall_status)) ? $recall_campaign->field_vin_recall_status : '',
    );
    
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => ($recall_campaign) ? t('Update') : t('Add'),
    );

    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => 'Cancel',
      '#attributes' => ['class' => ['button', 'button--primary']],
      '#url' => Url::fromRoute('recall_campaign_list'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
		/*Form validation rules here...*/
  }


  function submitForm(array &$form, FormStateInterface $form_state) {
    // Upload file to upload_directory in the public files dir.
    $fields = [
      'vin' => SafeMarkup::checkPlain($form_state->getValue('vin')),
      'field_recall_campaign_name' => $form_state->getValue('field_recall_campaign_name'),
      'recall_campain_info' => $form_state->getValue('recall_campain_info'),
      'field_ref_no' => $form_state->getValue('field_ref_no'),
      'field_model' => $form_state->getValue('field_model'),
      'field_error_msg' => $form_state->getValue('field_error_msg'),
      'field_situation' => $form_state->getValue('field_situation'),
      'field_effect' => $form_state->getValue('field_effect'),
      'field_measure' => $form_state->getValue('field_measure'),
      'field_vin_recall_status' => $form_state->getValue('field_vin_recall_status')
    ];

    if (!empty($this->id) && RecallCampaignStorage::exists($this->id)) {
      $recall_campaign = RecallCampaignStorage::load($this->id);

      RecallCampaignStorage::edit($this->id, $fields);
      \Drupal::logger('recall_campaign')->notice('@type: deleted %vin.',
          array(
              '@type' => $this->id,
              '%vin' => $this->id,
          ));

      drupal_set_message(t('Recall Campaign has been edited'));
    }
    else {
      $new_recall_campaign_id = RecallCampaignStorage::add($fields);
      \Drupal::logger('recall_campaign')->notice('@type: deleted %vin.',
          array(
              '@type' => $this->id,
              '%vin' => $this->id,
          ));

      drupal_set_message(t('Recall Campaign has been submitted'));
    }
    $form_state->setRedirect('recall_campaign_list');
    return;
  }
}
