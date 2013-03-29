<?php

function tz_likeThis($post_id,$action = 'get', $isMinus = 0) {

	if(!is_numeric($post_id)) {
		//error_log("Error: Value submitted for post_id was not numeric");
		return;
	} //if

	switch($action) {
	
	case 'get':
		$data = get_post_meta($post_id, '_likes');
		
		if(!is_numeric($data[0])) {
			$data[0] = 0;
			add_post_meta($post_id, '_likes', '0', true);
		} //if
		
		return $data[0];
	break;
	
	
	case 'update':
		
		if(!$isMinus && isset($_COOKIE["like_" + $post_id])) {
			return;
		} //if
		
		if($isMinus) {
			setcookie("like_" + $post_id, '', -86400, '/');
		}
		
		$currentValue = get_post_meta($post_id, '_likes');
		
		if(!is_numeric($currentValue[0])) {
			$currentValue[0] = 0;
			add_post_meta($post_id, '_likes', '1', true);
		} //if
		
		if (!$isMinus){ 
			$currentValue[0]++;
			setcookie("like_" + $post_id, $post_id,time()*20, '/');
		} else {
			--$currentValue[0];
		}
		update_post_meta($post_id, '_likes', $currentValue[0]);
		
		//setcookie("like_" + $post_id + $ip, $post_id, time()*20, '/');
	break;

	} //switch

} //tz_likeThis

function tz_printLikes($post_id) {
	$likes = tz_likeThis($post_id);
	
	$who = ' people like ';
	
	if($likes == 1) {
		$who = ' person likes ';
	} //if
	
	if(isset($_COOKIE["like_" + $post_id])) {

		print '<a href="#" class="likeThis active" id="like-'.$post_id.'"><span class="icon"></span><span class="count">'.$likes.'</span></a>';
		return;
	} //if

	print '<a href="#" class="likeThis" id="like-'.$post_id.'"><span class="icon"></span><span class="count">'.$likes.'</span></a>';
} //tz_printLikes


function setUpPostLikes($post_id) {
	if(!is_numeric($post_id)) {
		error_log("Error: Value submitted for post_id was not numeric");
		return;
	} //if
	
	
	add_post_meta($post_id, '_likes', '0', true);

} //setUpPost


function checkHeaders() {
	if(isset($_POST["likepost"])) {
		$isMinus = $_POST['isminus'];
		tz_likeThis($_POST["likepost"],'update', $isMinus);
	} //if

} //checkHeaders


function jsIncludes() {
	wp_enqueue_script('jquery');

} //jsIncludes

function get_real_ip() {
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif (!empty($_SERVER["REMOTE_ADDR"])) {
		$cip = $_SERVER["REMOTE_ADDR"];
	} else {
		$cip = "0.0.0.0";
	}
	return $cip;
}

add_action ('publish_post', 'setUpPostLikes');
add_action ('init', 'checkHeaders');
add_action ('get_header', 'jsIncludes');
?>