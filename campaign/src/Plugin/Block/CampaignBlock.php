<?php

/**
 * @file
 * Contains \Drupal\campaign\Plugin\Block\CampaignBlock
 */
namespace Drupal\campaign\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term; 
#use Drupal\campaign\Controller\CampaignController;
#use Drupal\testdrive\Helper\LmsFormHelper;

/**
 * Provides a 'campaign' block.
 *
 * @Block(
 *   id = "campaign_block",
 *   admin_label = @Translation("Campaign Steps Block"),
 * )
 */
class campaignBlock extends BlockBase {

	/**
	* {@inheritdoc}
	*/
  	public function build() {

        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('campaign');
        $collections = [];

		foreach($terms as $term) {
			$exturl = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid)->get('field_campaign_video_url')->getValue();
			$exturl = isset($exturl[0]['uri']) ? $exturl[0]['uri'] : '';

			$tabimage = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid)->get('field_tab_category_image')->getValue();
	      	$tab_image = '';
      		if(!empty($tabimage)){
		        $tabimage = $tabimage[0]['target_id'];
		       	$slidefile = \Drupal\file\Entity\File::load($tabimage);
		       	$tab_image = $slidefile->url();
	      	}

	      	$campaign_images = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid)->get('field_campaign_image')->getValue();
	      	$acce_image = [];
	      	foreach ($campaign_images as $img) {
	      		$accimage = $img['target_id'];
		       	$slidefile = \Drupal\file\Entity\File::load($accimage);
		       	$acce_image[] = [
		       		'txt' => $img['alt'],
		       		'img' => $slidefile->url()
		       	];
	      	}
;
	      	$brochure = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid)->get('field_campaign_brochure')->getValue();
	      	$brochure_file = '';
      		if(!empty($brochure)){
		        $brochure = $brochure[0]['target_id'];
		       	$brochure = \Drupal\file\Entity\File::load($brochure);
		       	$brochure_file = $brochure->url();
	      	}

	  		$dealerurl = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($term->tid)->get('field_dealer_url')->getValue();
			$dealerurl = isset($dealerurl[0]['uri']) ? $dealerurl[0]['uri'] : '#';

			$collections[] = [
		      	'id' => $term->tid,
		      	'name' => $term->name,
		      	'youtube_url' => $exturl,
		      	'tab_image' => $tab_image,
		      	'brochure' => $brochure_file,
		      	'dealerurl' => $dealerurl,
		      	'acce_image' => $acce_image
		    ];
		}

		\Drupal::service('page_cache_kill_switch')->trigger();

		return [
			'#theme' => 'campaign',
			'#campaign' => $collections,
			'#attached' => [
		        'library' => [
		          'campaign/campaign-style'
		        ],
		    ]
		];
	}
}