<?php

/**
 * @file
 * Contains \Drupal\campaign\Plugin\Block\CampaignDetailsBlock
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
 *   id = "campaign_details_block",
 *   admin_label = @Translation("Campaign Details Block"),
 * )
 */
class CampaignDetailsBlock extends BlockBase {

	/**
	* {@inheritdoc}
	*/
  	public function build() {

        
	}
}