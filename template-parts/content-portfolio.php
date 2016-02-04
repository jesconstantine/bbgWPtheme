<?php
/**
 * Template part for displaying a portfolio excerpt 
 * 3 columns without byline or date
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
 */



global $gridClass;
if ( empty ($gridClass)) {
	$gridClass="bbg-grid--1-2-2";
}
$classNames="bbg-portfolio__excerpt ".$gridClass;
?>


<article id="post-<?php the_ID(); ?>" <?php post_class($classNames); ?>>
	<header class="entry-header bbg-portfolio__excerpt-header">
	<?php 
		echo sprintf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) );
	?>
		<div class="single-post-thumbnail clear bbg__excerpt-header__thumbnail--medium">
			<?php
				/* Set a default thumbnail image in case one isn't set */
				$thumbnail = '<img src="' . get_template_directory_uri() . '/img/portfolio-project-default.png" alt="This is a default image." />';

				if (has_post_thumbnail()) {
					$thumbnail = the_post_thumbnail('medium-thumb');
				}
				echo $thumbnail;
			?>
		</div>

		<?php the_title( sprintf( '<h3 class="entry-title bbg-portfolio__excerpt__title">', esc_url( get_permalink() ) ), '</h3>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta bbg__article-meta">
			<!--<?php bbginnovate_posted_on(); ?>-->
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</a>

	</header><!-- .entry-header -->

	<div class="entry-content bbg-portfolio__excerpt-title">
		<?php the_excerpt(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bbginnovate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .bbg-portfolio__excerpt-title -->

</article><!-- .bbg-portfolio__excerpt -->
