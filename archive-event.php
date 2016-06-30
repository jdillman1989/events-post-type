<?php
/**
 * The template for viewing all events
 */
get_header(); 

// get posts
$posts = get_posts(array(
	'post_type'			=> 'event',
	'posts_per_page'	=> -1,
	'meta_key'			=> 'event-start-date',
	'orderby'			=> 'meta_value_num',
	'order'				=> 'ASC'
));

if( $posts ):
	
	echo '<ul>';

	foreach( $posts as $post ){
		
		setup_postdata( $post );
		$get_location = get_post_meta( get_the_ID(), 'event-location', true );
		$get_start = get_post_meta( get_the_ID(), 'event-start-date', true );
		$get_end = get_post_meta( get_the_ID(), 'event-end-date', true );

		if ($get_start == $get_end) {
			$display_dates = date('F j, Y', $get_start);
		}
		else{
			$display_start = date('F j, Y', $get_start);
			$display_end = date('F j, Y', $get_end);
			$display_dates = $display_start.' - '.$display_end;
		}

		$today_start = strtotime('today midnight');

		if ($get_end > $today_start) {

			$title = get_the_title();
			$image = get_field('event_image');
			$excerpt = get_the_excerpt();
			$link = get_the_permalink();

		};
	};
	
	echo '</ul>';

	wp_reset_postdata(); 

endif;

get_footer();
