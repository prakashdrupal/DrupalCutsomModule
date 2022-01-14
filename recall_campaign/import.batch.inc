<?php

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\recall_campaign\RecallCampaignStorage;

/**
 * Handle batch completion.
 *
 *   Creates a new XLS file containing all failed rows if any.
 */
function xls_import_finished($success, $results, $operations) {

    return t('The XLS import has completed.');
}

/**
 * Process one array at a time.
 */
function _xlsimport_import_line($serial, $vin, $field_recall_campaign_name, $ref_num, $model, $err_msg, $recall_campain_info, $faq_situation, $faq_effect, $faq_measure, $status) {

    /**
     * Convert the line of the XLS file into a new node.
     *
     */

    $node = [
        'vin' => $vin,
        'recall_campain_info' => $recall_campain_info,
        'field_recall_campaign_name' => $field_recall_campaign_name,
        'field_ref_no' => $ref_num,
        'field_model' => $model,
        'field_error_msg' => $err_msg,
        'field_vin_recall_status' => $status,
        'field_effect' => $faq_effect,
        'field_measure' => $faq_measure,
        'field_situation' => $faq_situation,
    ];

    //echo "<pre>";print_r($node);exit;

    \Drupal::database()->insert('recall_campaign')
        ->fields(array(
            'vin' => $vin,
            'recall_campain_info' => $recall_campain_info,
            'field_recall_campaign_name' => $field_recall_campaign_name,
            'field_ref_no' => $ref_num,
            'field_model' => $model,
            'field_error_msg' => $err_msg,
            'field_vin_recall_status' => $status,
            'field_effect' => $faq_effect,
            'field_measure' => $faq_measure,
            'field_situation' => $faq_situation
        ))
        ->execute();

    drupal_set_message('XLS data added to the database');

}
