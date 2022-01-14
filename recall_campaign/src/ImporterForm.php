<?php

namespace Drupal\recall_campaign;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use \Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\recall_campaign\RecallCampaignStorage;
/**
 * Provides the form for adding countries.
 */
class ImporterForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'xlsimport_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['#attributes'] = [
            'enctype' => 'multipart/form-data',
        ];

        $form['xlsfile'] = [
            '#title' => t('Upload XLS File'),
            '#type' => 'file',
            '#description' => ($max_size = file_upload_max_size()) ? t('Due to server restrictions, the <strong>maximum upload file size is @max_size</strong>. Files that exceed this size will be disregarded.', ['@max_size' => format_size($max_size)]) : '',
            '#element_validate' => ['::xlsimport_validate_fileupload'],
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('Start Import'),
        ];

        return $form;
    }

    /**
     * Validate the file upload. It must be a XLS, and we must
     * successfully save it to our import directory.
     */
    public static function xlsimport_validate_fileupload(&$element, FormStateInterface $form_state, &$complete_form) {

        $validators = [
            'file_validate_extensions' => ['xls XLS'],
        ];

        if ($file = file_save_upload('xlsfile', $validators, FALSE, 0, FILE_EXISTS_REPLACE)) {

            // The file was saved using file_save_upload() and was added to the
            // files table as a temporary file. We'll make a copy and let the
            // garbage collector delete the original upload.
            $xls_dir = 'temporary://xlsfile';
            $directory_exists = file_prepare_directory($xls_dir, FILE_CREATE_DIRECTORY);

            if ($directory_exists) {
                $destination = $xls_dir . '/' . $file->getFilename();
                if (file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
                    $form_state->setValue('xlsupload', $destination);
                } else {
                    $form_state->setErrorByName('xlsimport', t('Unable to copy upload file to @dest', ['@dest' => $destination]));
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        if ($xlsupload = $form_state->getValue('xlsupload')) {

            if ($handle = fopen($xlsupload, 'r')) {

                /**
                 * Validate the uploaded XLS here.
                 *
                 * The example XLS happens to have cell A1 ($line[0]) as
                 * below; we validate it only.
                 *
                 * You'll probably want to check several headers, eg:
                 *   if ( $line[0] == 'Index' || $line[1] != 'Supplier' || $line[2] != 'Title' )
                 */
                fclose($handle);
            } else {
                $form_state->setErrorByName('xlsfile', t('Unable to read uploaded file @filepath', ['@filepath' => $xlsupload]));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $batch = [
            'title' => t('Importing XLS ...'),
            'operations' => [],
            'init_message' => t('Commencing'),
            'progress_message' => t('Processed @current out of @total.'),
            'error_message' => t('An error occurred during processing'),
            'finished' => 'xls_import_finished',
            'file' => drupal_get_path('module', 'recall_campaign') . '/import.batch.inc',
        ];

        if ($xlsupload = $form_state->getValue('xlsupload')) {
			error_log("HER 125");
            if ($handle = fopen($xlsupload, 'r')) {
			error_log("HER 127");
                
                $spreadsheet = IOFactory::load($xlsupload);
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);                
                $sheet_left_blank = array_slice($sheetData, 1);
				error_log(print_r($sheetData, true));
                foreach($sheet_left_blank as $array_of_data){

                     $array_of_data = array_filter($array_of_data);
                  //echo'<pre>'; print_r($array_of_data); exit('ss');
                  $batch['operations'][] = [
                  '_xlsimport_import_line',
                  $array_of_data,
                  ]; 
                 error_log(print_r($array_of_data, true));
                 }
                
                fclose($handle);
            } // we caught this in xlsimport_form_validate()
        } // we caught this in xlsimport_form_validate()
        batch_set($batch);
    }

}