<?php

namespace Drupal\campaign\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use \Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Serialization\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\field\Entity\FieldConfig;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
use Drupal\Component\Render\FormattableMarkup;
/**
 * Defines CampaignController class.
 */
class CampaignController extends ControllerBase {

	/**
	* Display the markup.
	* @param Request type object
	*
	* @return array
	* Return markup array.
	*/
	public function content(Request $request) {
	    
    $fieldSource = $this->queryProcessed($request);
      
    if ($request->getMethod() == 'POST') {

        $fields = [
          'salutation' => $request->request->get('salutation'),
          'first_name' => $request->request->get('fname'),
          'last_name' => $request->request->get('lname'),
          'email' => $request->request->get('email'),
          'mobile' => $request->request->get('mobile'),
          'created_at' => date("Y-m-d H:i:s")
        ]; 

        echo '<pre>'; print_r($fields); die;
        $errors = $this->validate($fields);
        //print_r($banner_url); die();

            /*
          * This request push code on CRM.  
            */
            $fields['raw_query_params'] = $request->getQueryString();

            /*
          * This request save data on our database. 
            */
            $insertflag = ServiceCalculatorConfigStorage::addlms($fields);
          //   if (!empty($insertflag)) {
          //     $banner_url = $this->getBannerUrlByMid($mid);
          //     $session->set('banner_url', $banner_url);
          //     $session->set('model_name', $request->request->get('model_name'));
          //     return  $this->redirectTo('/success');
          // } else {
          //   $error = t('Oops unexpected server error!');
          //   $errors['unknow'] = $error;
          //     \Drupal::logger('cardetail_requestoffer')->notice($error);
          // }
    } 

    return [];
  }


    /**
    * get Campaign List
    * @param  Request $request
    * @return Array
    */
    public function getCampaignList(Request $request) {

        return [
			'#theme' => 'campaignList',
            '#params' => []
		];
    }

	 /**
    * get Campaign Details
    * @param  Request $request
    * @return Array
    */
    public function getCampaignDetails(Request $request) {

        return [
          '#theme' => 'campaignDetails',
          '#params' => []
        ];
    }

    /**
    * Display the markup.
    * @param Request type object
    *
    * @return array
    * Return processed query params array.
    */
    public function queryProcessed(Request $request) {

      $qs = $request->getQueryString();
      $req = [];
      if (!empty($qs)) {
          $get = Request::normalizeQueryString($qs);
          $get = explode('&', $get);
          $req = [];
          foreach ($get as $v) {
              $v = explode('=', $v);
              $req[$v[0]] = $v[1];
          }
      }

      return $req;
  }


}