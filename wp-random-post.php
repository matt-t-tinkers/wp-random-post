<?php

/**
 * Plugin Name: WP Random Post
 * Description: Shortcode to display a random post.
 * Plugin URI: https://github.com/matt-t-tinkers/wp-random-post
 * Author: Matt Taylor
 * Author URI: https://github.com/matt-t-tinkers/
 * Version: 0.1
 * Text Domain: wp-random-post
 * License: GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! class_exists( 'RandomPostShortcode' ) ) {
	/**
	 * Class to provide shortcodes for use with the plugin
	 */
	class RandomPostShortcode {
		/**
		 * Constructor - register all hooks with WordPress API
		 */
		public function __construct() {
			/**
			 * Add the shortcode
			 */
			add_shortcode( 'wp-random-post', array( $this, 'getRandomPost' ) );

		}

		/**
		 * Returns a random post
		 *
		 * @param array  $atts - attribues passed to shortcode.
		 */
		public function getRandomPost($atts) {
			// Use these attributes to filter the query.
            $options = shortcode_atts(array(
				'post_type' => 'post',
                'author'    => '',
			), $atts);
			$output  = '';
			if ( ! empty( $options['post_type'] ) ) {
				$post_query = new WP_Query( array(
					'post_type'     => 'post',
                    'orderby'       => 'rand',
                    'posts_per_page' => 1,
				) );
				if ( $post_query->have_posts() ) {
					while ( $post_query->have_posts() ) {
						$post_query->the_post();
						ob_start();
                        echo ('<h1>I am awake</h1>');
						printf( '<h2>%s</h2>', esc_html( the_title() ) );
						$output .= ob_get_clean();
					}
				} else {
					$output .= '<p>No post could be found.</p>';
				}
				wp_reset_postdata();
			}
			return $output;
		}
    }
    new RandomPostShortcode();
}
