<?php
/* Init of advenced funcions of airoborn theme */
require_once (TEMPLATEPATH . '/advance/apollo13.php');

$apollo13 = new Apollo13();
if(!function_exists('lets_play_apollo_game')) {
	function lets_play_apollo_game(){
		global $apollo13;
		$apollo13->start();
	}
}

lets_play_apollo_game();

	
include("functions/theme-likethis.php");
