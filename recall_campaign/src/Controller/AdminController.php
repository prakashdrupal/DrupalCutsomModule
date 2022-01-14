<?php
/**
 * @file
 * Contains \Drupal\recall_campaign\Controller\AdminController.
 */
namespace Drupal\recall_campaign\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\recall_campaign\RecallCampaignStorage;
use Drupal\file\Entity\File;
use Drupal\Component\Render\FormattableMarkup; 

class AdminController extends ControllerBase {

function contentOriginal() {
  $url = Url::fromRoute('recall_campaign_add');
  //$add_link = ;
  $add_link = '<p>' . \Drupal::l(t('New Recall Campaign'), $url) . '</p>';

  // Table header
  $header = array( 'id' => t('Id'), 'vin' => t('VIN'), 'field_recall_campaign_name' => t('Recall Campaign Name'),'field_ref_no' => t('Ref. no'), 'field_model' => t('Model'), 'field_vin_recall_status' => t('Status'), 'operations' => t('Delete'), );

  $rows = array();
  foreach(RecallCampaignStorage::getAll() as $id=>$content) {
    // Row with attributes on the row and some of its cells.
    $rows[] = array( 'data' => array($id, $content->vin, $content->field_recall_campaign_name, $content->field_ref_no,$content->video_url, $content->field_model, $content->field_vin_recall_status, l('Delete', "admin/content/recall_campaign/delete/$id")) );
   }

   $table = array( '#type' => 'table', '#header' => $header, '#rows' => $rows, '#attributes' => array( 'id' => 'recall_campaign-table', ), );
   return $add_link . drupal_render($table);
 }

  /*public function content1() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello World'),
    );
  }*/

  function content() {

    $url = Url::fromRoute('recall_campaign_add')->setOption('attributes', ['class' => 'button button-action button--primary button--small', 'style' => 'margin-bottom:10px']);
    $link = Link::fromTextAndUrl(t('Add New Recall Campaign'), $url)->toRenderable();

    // Table header.
    $header = array(
      'id' => t('Id'),
      'vin' => t('VIN'), 
      'field_recall_campaign_name' => t('Recall Campaign Name'),
      'field_ref_no' => t('Ref.no'),
      'field_model' => t('Model'), 
      'field_vin_recall_status' => t('Status'), 
      'opt' =>$this->t('Operations'),
    );
    $rows = array();
  
    $imgurl='';
    
    foreach (RecallCampaignStorage::getAll() as $id => $content) {
      // Row with attributes on the row and some of its cells.
      if ($content->id != 0) {
        $editUrl = Url::fromRoute('recall_campaign_edit', array('id' => $content->id));
        $deleteUrl = Url::fromRoute('recall_campaign_delete', array('id' => $content->id));
       

        $edit_link = \Drupal::l('edit', $editUrl);
        $delete_link = \Drupal::l('delete',  $deleteUrl);
        $mainLink = t('@linkApprove  @linkReject', array('@linkApprove' => $edit_link, '@linkReject' => $delete_link));

        $rows[] = array(
          'data' => array(
            $content->id,
            $content->vin, $content->field_recall_campaign_name, $content->field_ref_no, $content->field_model, $content->field_vin_recall_status,
             $mainLink,
          ),
        );
      }
      
    }

    $render = [];
    $render[] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => array('id' => 'recall_campaign-table'),
      '#empty' => $this->t('No records found'),
    );
    $render[] = ['#type' => 'pager'];
    return array(
      $link,
      $render,
    );
  }
}
