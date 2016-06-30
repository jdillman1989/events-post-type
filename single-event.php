<?php
/**
 * The template for displaying single events
 *
 * @package WordPress
 */

get_header();

while ( have_posts() ) : the_post(); 

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

	$title = get_the_title();
	$image = get_field('event_image');
	$content = get_the_content();
	$link = get_the_permalink();
	$excerpt = get_the_excerpt();

	echo '<meta name="twitter:card" content="summary_large_image">';
	echo '<meta name="twitter:site" content="@'.twittertag.'">';
	echo '<meta name="twitter:title" content="'.$title.'">';
	echo '<meta name="twitter:description" content="'.$excerpt.'">';
	echo '<meta name="twitter:image" content="'.$image.'">';

endwhile;

get_footer();