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
            $post_query = new WP_Query(array(
                'post_type'      => $options['post_type'],
                'author'         => $options['author'],
                // Pick out a random post.
                'orderby'        => 'rand',
                // Just one post please.
                'posts_per_page' => 1,
            ));
            if ($post_query->have_posts()) {
                while ($post_query->have_posts()) {
                    $post_query->the_post();
                    ob_start();
                    ?>
                    <article>	
                        <?php
                        if (has_post_thumbnail()) {
                            $thumbnail_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
                            if (empty($thumbnail_alt)) {
                                $thumbnail_alt = get_the_title();
                            }
                            ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img src="<?php esc_attr(the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>"></a>
                            <?php
                        }
                        ?>
                        <header>
                            <h2>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo esc_html(the_title('', '', false)); ?></a>
                            </h2>
                        </header>
                        <p><?php echo esc_html(the_excerpt()); ?></p>
                        <?php the_date( 'd/m/Y', '<time>', '</time>' ); ?>
                    </article>
                    <?php
                    $output .= ob_get_clean();
                }
            } else {
                $output .= '<p>No post could be found.</p>';
            }
            // Reset the post query.
            wp_reset_postdata();
			return $output;
		}
    }
    new RandomPostShortcode();
}
