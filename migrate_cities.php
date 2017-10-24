<?php

/**
 * @file
 * Handles incoming requests to fire off regularly-scheduled tasks (cron jobs).
 */

/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());

include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

print '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';

$regions = file_get_contents("json/regions.json");
$json_decode_regions = json_decode($regions);

$cities = file_get_contents("json/cities.json");
$json_decode_cities = json_decode($cities);
  
$vocab = taxonomy_vocabulary_machine_name_load('city');

foreach ($json_decode_regions as $key => $regions) {
	$terms = new stdClass(); 
	$terms->vid = $vocab->vid; 
	$terms->name = $regions->District_Name;
	taxonomy_term_save($terms);
	
	foreach ($json_decode_cities as $key => $cities) {
		if($regions->District_Id == $cities->District_Id){
			
			$child = new stdClass(); 
			$child->vid = $vocab->vid; 
			$child->name = $cities->Province_City_Name;
			$child->parent = $terms->tid;
	
			taxonomy_term_save($child);
		}
    }
}

?>
