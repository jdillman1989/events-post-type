<?php

// Register taxonomies for Directory Listings and Events
function theme_taxonomies() {  
    register_taxonomy(  
        'events_categories',  //The slug of the taxonomy
        'event', //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Event Categories',  //Display name
            'query_var' => true
            // 'rewrite' => array(
            //     'slug' => 'event', // This controls the base slug that will display before each term
            //     'with_front' => false // Don't display the category base before 
            // )
        )  
    );
}  
add_action( 'init', 'theme_taxonomies');

foreach (glob( get_stylesheet_directory() ."/cpt/*.php") as $post_type){
    include_once( $post_type );
}
