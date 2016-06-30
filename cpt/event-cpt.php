<?php 
function create_post_type_events() {  

    $single_name = 'Event';
    $plural_name = 'Events';

    register_post_type('event',
        array(
            'label' => $plural_name,
            'description' => $plural_name,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'hierarchical' => true,
            'taxonomies' => array('events_categories'),
            'has_archive' => true,
            'rewrite' => array(
                'with_front' => false,
                'slug' => 'event'
            ),
            'query_var' => true,
            'exclude_from_search' => false,
            'menu_position' => 0,
            'supports' => array(
                'title',
                'editor',
                'excerpt'
            ),
            'labels' => array (
                'name' => $plural_name,
                'singular_name' => $single_name,
                'menu_name' => $plural_name,
                'add_new' => 'Add '.$single_name,
                'add_new_item' => 'Add New '.$single_name,
                'edit' => 'Edit',
                'edit_item' => 'Edit '.$single_name,
                'new_item' => 'New '.$single_name,
                'view' => 'View '.$single_name,
                'view_item' => 'View '.$single_name,
                'search_items' => 'Search '.$plural_name,
                'not_found' => 'No '.$plural_name.' Found',
                'not_found_in_trash' => 'No '.$plural_name.' Found in Trash'
            )
        )
    );
}

add_action( 'init', 'create_post_type_events' );

//Add new fields to event post type
function render_event_info_metabox( $post ) {
 
	// generate a nonce field
	wp_nonce_field( basename( __FILE__ ), 'event-info-nonce' );
 
	// get previously saved meta values (if any)
	$event_start_date = get_post_meta( $post->ID, 'event-start-date', true );
	$event_end_date = get_post_meta( $post->ID, 'event-end-date', true );
	$event_location = get_post_meta( $post->ID, 'event-location', true );
 
	?>
		 
		<label for="event-start-date">Event Start Date:</label>
				<input id="event-start-date" type="text" name="event-start-date" placeholder="Format: June 1, 2015" value="<?php echo date( 'F d, Y', $event_start_date ); ?>" />
		 		<br>
		<label for="event-end-date">Event End Date:</label>
				<input id="event-end-date" type="text" name="event-end-date" placeholder="Format: June 1, 2015" value="<?php echo date( 'F d, Y', $event_end_date ); ?>" />
				<br>
		<label for="event-location">Event Location:</label>
				<input id="event-location" type="text" name="event-location" placeholder="eg. Hilton Chicago" value="<?php echo $event_location; ?>" />
				<br>
	<?php 
}

function add_event_info_metabox() {
	add_meta_box(
		'event-info-metabox',
		'Event Info',
		'render_event_info_metabox',
		'event',
		'side',
		'core'
	);
}
add_action( 'add_meta_boxes', 'add_event_info_metabox' );

//save new fields to database
function save_event_info( $post_id ) {

	// checking if the post being saved is an 'event',
	if ( 'event' != $_POST['post_type'] ) {
		return;
	}

	// checking for the 'save' status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	// verify nonce field
	$is_valid_nonce = ( isset( $_POST['event-info-nonce'] ) && ( wp_verify_nonce( $_POST['event-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;
 
	// exit depending on the save status or if the nonce is not valid
	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}
 
	// checking for the values and performing necessary actions
	if ( isset( $_POST['event-start-date'] ) ) {
		update_post_meta( $post_id, 'event-start-date', strtotime( $_POST['event-start-date'] ) );
	}

	if ( isset( $_POST['event-end-date'] ) && strlen( $_POST['event-end-date'] ) > 10 ) {
		update_post_meta( $post_id, 'event-end-date', strtotime( $_POST['event-end-date'] ) );
	}
	else{
		// if no end date is set, assume end date = start date
		update_post_meta( $post_id, 'event-end-date', strtotime( $_POST['event-start-date'] ) );
	}
 
	if ( isset( $_POST['event-location'] ) ) {
		update_post_meta( $post_id, 'event-location', sanitize_text_field( $_POST['event-location'] ) );
	}
 
}
add_action( 'save_post', 'save_event_info' );