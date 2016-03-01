<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Project
 */

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage=7;
$numPostsSubsequentPages=6;

$postsPerPage=$numPostsFirstPage;
if ($paged > 1) {
	$postsPerPage=$numPostsSubsequentPages;
}

$hasTeamFilter=false;
if (get_query_var('cat',false)) {
	$hasTeamFilter=true;
	$teamCategoryID= get_query_var('cat',false);
	$teamCategory=get_category($teamCategoryID);

	$qParams=array(
		'post_type' => array('post')
		,'category__and' => array(get_cat_id('Project'),get_query_var('cat',false))
		,'posts_per_page' => $postsPerPage
		,'paged' => $currentPage
	);
} else {
	$qParams=array(
		'post_type' => array('post')
		,'cat' => get_cat_id('Project')
		,'posts_per_page' => $postsPerPage
		,'paged' => $currentPage
	);
}


query_posts($qParams);


/*** SHARING VARS ****/
/*
$teamCategoryID=$_GET["cat"];
$teamCategory=get_category($teamCategoryID);
$portfolioDescription=$teamCategory->description;
*/
$portfolioDescription="some portfolio description could go here based on the category description?";



get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="usa-grid">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h6 class="bbg-label--mobile large">Projects <?php if ($hasTeamFilter) { echo "(" . $teamCategory->cat_name. ")"; }?></h6>
				</header><!-- .page-header -->
			</div>

			<div class="usa-grid-full">
				<?php
					$counter=0;
					while ( have_posts() )  {
						the_post();
						$counter=$counter+1;
						if ( $counter == 1 && $currentPage==1 ) {
							$includeMetaFeatured = FALSE;
							get_template_part( 'template-parts/content-excerpt-featured', get_post_format() );
							echo '<div class="usa-grid">';
						} elseif ( $counter == 1 && $currentPage != 1 ) {
							echo '<div class="usa-grid">';
							$gridClass = "bbg-grid--1-2-3";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						} else {
							$gridClass = "bbg-grid--1-2-3";
							get_template_part( 'template-parts/content-portfolio', get_post_format() );
						}
					}
					the_posts_navigation();
					echo '</div><!-- .usa-grid -->';
				?>

			<?php else : ?>

				<?php get_template_part( 'template-parts/content', 'none' ); ?>

			<?php endif; ?>
			</div><!-- .usa-grid-full -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>


