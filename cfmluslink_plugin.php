<?php
/*
Plugin Name: cfml.us URL Shortener
Plugin URI: http://www.mattgifford.co.uk/
Description: This plugin will create a short URL for posts / pages using the cfml.us URL Shortener
Author: Matt Gifford
Author URI: http://www.mattgifford.co.uk/
Version: 0.0.3
License: New BSD License - http://www.opensource.org/licenses/bsd-license.php
*/

/*
*
* Method to display the shortened link directly 
* within the template pages
*
*/

function show_cfmlus_link() {
  global $post;

  $customvalue = get_post_custom_values('cfmlus_link');
  $cfmlus_link = $customvalue[0];

  if($cfmlus_link === NULL) {
    generate_cfmlus_link($post->ID);
  }
  
  $customvalue = get_post_custom_values('cfmlus_link');
  $cfmlus_link = $customvalue[0];

  echo fetch_cfmlus_link( $atts );

}

/*
*
* Method to be called from the shortcode implementation
* using the [cfmlus_link] shortcode
*
*/

function fetch_cfmlus_link( $atts ) {

  global $post;

  $customvalue = get_post_custom_values('cfmlus_link');
  $cfmlus_link = $customvalue[0];

  if($cfmlus_link === NULL) {
    generate_cfmlus_link($post->ID);
  }
  
  $customvalue = get_post_custom_values('cfmlus_link');
  $cfmlus_link = $customvalue[0];
  
  extract( shortcode_atts( array(
    'title' => 'Short URL: ',
    'class' => '',
  ), $atts ) );

  return $title . '<a href="'.$cfmlus_link.'" class="'.$class.'">'.$cfmlus_link.'</a>';

}

add_shortcode( 'cfmlus_link', 'fetch_cfmlus_link' );


/*
* 
* Method to generate the shortened URL using the service
* 
*/

function generate_cfmlus_link( $post_id ) {

  //verify post is not a revision
  if ( !wp_is_post_revision( $post_id ) ) {

    $post_title = get_the_title( $post_id );
    $post_url = get_permalink( $post_id );

        $response = wp_remote_get( 'http://cfml.us?url=' . $post_url);
        $cfmlusURL = $response['body'];

        update_post_meta($post_id, 'cfmlus_link', $cfmlusURL);
    
  }

}

?>