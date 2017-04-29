<?php
/**
 * Template Name: Page with no sidebar
 * Template Post Type: post, page
 *
 * The template for displaying pages without the sidebar
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post(); 
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( !isset( $theme_options[ 'remove_entry_header' ] ) || $theme_options[ 'remove_entry_header' ] != 'yes' ) { ?>
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
					
					<?php twentyseventeen_edit_link( get_the_ID() ); ?>
					
				</header><!-- .entry-header -->
				<?php } ?>
				<div class="entry-content">
					<?php
						the_content();

						wp_link_pages( array(
							'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
							'after'  => '</div>',
						) );
					?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->			

			<?php 
			// Get each of our panels and show the post data.
			if( is_front_page() ) {
				if ( 0 !== twentyseventeen_panel_count() || is_customize_preview() ) : // If we have pages to show.

				/**
				 * Filter number of front page sections in Twenty Seventeen.
				 *
				 * @since Twenty Seventeen 1.0
				 *
				 * @param $num_sections integer
				 */
				$num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );
				global $twentyseventeencounter;

				// Create a setting and control for each of the sections available in the theme.
				for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
					$twentyseventeencounter = $i;
					twentyseventeen_front_page_section( null, $i );
				}

				endif; // The if ( 0 !== twentyseventeen_panel_count() ) ends here.
				
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

						
			}
			
			endwhile; // End of the loop.	
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>