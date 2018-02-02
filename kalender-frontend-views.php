<?php
function add_date_before( $content ) {
		
	$fullcontent = display_my_date() . $content;

	return $fullcontent;
}
add_filter('the_content', 'add_date_before');