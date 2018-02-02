<?php
/* 
 * Plugin Name: "Alter Bahnhof" - Veranstaltungsplugin
 * Descrtiption: Fügt die Veranstaltungskalenderfunktion hinzu. Es wird Advanced Custom Fields Pro benötigt.
 * Version: 1.0.0
 * Author: woodpresser
 */

/**
 * Register and include styles for frontend
 */
function load_kalender_frontend_styles() {
	wp_register_style( 'kalender-frontend-styles', plugins_url( 'kalender-frontend-styles.css', __FILE__ ) );
	
	wp_enqueue_style( 'kalender-frontend-styles' );
}
add_action('wp_enqueue_scripts', 'load_kalender_frontend_styles');

/**
 * Register acf options page.
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page( 
		array(
			'page_title' 	=> 'Veranstaltungen',
			'menu_title'	=> 'Kalender',
			'menu_slug' 	=> 'kalender',
			'capability'	=> 'edit_posts',
			'position'		=> 6,
			'icon_url'		=> 'dashicons-calendar-alt',
			'redirect'		=> false
		)
	);
}

/*
 * Shortcode für Kalender
 */
function the_albh_kalender_content() {
	// get grouped_fields
	$info_group = get_sub_field('informationen');
	//get parent_post
	$pID = $info_group['beitrag_verlinken']->ID;
	
	?>
	<tr>
		<td class="albh_datum">
			<?php if( !empty( $pID ) ) { ?>	
				<h5><a href="<?php the_permalink( $pID ) ?>"><?php echo $info_group['datum']; ?></a></h5>
			<?php } else { ?>
				<h5><?php echo $info_group['datum']; ?></h5>
			<?php } ?>			
		</td>
		<td class="albh_meta">
			<?php if( !empty( $pID ) ) { ?>	
				<h5><a href="<?php the_permalink( $pID ) ?>"><?php echo $info_group['bezeichnung']; ?></a></h5>
			<?php } else { ?>
				<h5><?php echo $info_group['bezeichnung']; ?></h5>
			<?php } ?>
			
			<p><?php the_sub_field('kurzbeschreibung'); ?></p>

		</td>
	</tr>
	<?php
}

function albh_kalender_overview_shortcode() {
	// check if the repeater field has rows of data
	if( have_rows('kalender_repeat', 'option') ) {
		echo '<table id="albh_kalender">';
		// loop through the rows of data
			while ( have_rows('kalender_repeat', 'option') ) : the_row();
				// Display kalender content
				the_albh_kalender_content();			
			endwhile;
		echo '</table>';
	} else {
		echo 'Keine Veranstaltungen gefunden.';
	}
}
add_shortcode('albh_kalender', 'albh_kalender_overview_shortcode');