<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bbginnovate
  template name: Project
 */

/***** BEGIN PROJECT PAGINATION LOGIC 
There are some nuances to this.  Note that we're not using the paged parameter because we don't have the same number of posts on every page.  Instead we use the offset parameter.  The 'posts_per_page' limits the number displayed on the current page and is used to calculate offset.
http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
****/

$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$numPostsFirstPage=7;
$numPostsSubsequentPages=6;

$postsPerPage=$numPostsFirstPage;
$offset=0;
if ($currentPage > 1) {
	$postsPerPage=$numPostsSubsequentPages;
	$offset=$numPostsFirstPage + ($currentPage-2)*$numPostsSubsequentPages;
}


$categoryID=0;
$hasTeamFilter=false;
if (isset($_GET['category_id'])) {
	$categoryID=$_GET['category_id'];
	/*** this is a filtered team page ***/
	$hasTeamFilter=true;
	$teamCategoryID= $_GET['category_id'];
	$teamCategory=get_category($teamCategoryID);

	$qParams=array(
		'post_type' => array('post')
		,'category__and' => array(get_cat_id('Project'), $_GET['category_id'])
		,'posts_per_page' => $postsPerPage
		,'offset' => $offset
		,'post_status' => array('publish')
	);
} else {
	$qParams=array(
		'post_type' => array('post')
		,'cat' => get_cat_id('Project')
		,'posts_per_page' => $postsPerPage
		,'offset' => $offset
		,'post_status' => array('publish')
	);
}

/*** late in the game we ran into a pagination issue, so we're running a second query here ***/
$custom_query_args= $qParams;
$custom_query = new WP_Query( $custom_query_args );


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
					<h6 class="bbg-label--mobile large">
						<?php if ($hasTeamFilter) {
							echo "" . $teamCategory->cat_name. " projects";
						} else {
							echo 'Projects';
						}

						?>
					</h6>
				</header><!-- .page-header -->
			</div>
			<?php
				/**** SPECIAL CASE: mobile apps landing page gets a little teaser with contact info ***/
				$mobileAppsCategory=get_category_by_slug("mobile-apps");
				if ($categoryID==$mobileAppsCategory->term_id) {
				?>

					<section id="mission" class="usa-section usa-grid">
					<?php
						$qParams=array(
							'post_type' => array('post'),
							'posts_per_page' => 1,
							'cat' => get_cat_id('MobileApps Introduction')
						);
						$the_query = new WP_Query( $qParams );

						$siteIntroContent="";
						if ( $the_query->have_posts() ) :
							while ( $the_query->have_posts() ) : $the_query->the_post();
								$siteIntroTitle=get_the_title();
								echo '<h3 id="site-intro" class="usa-font-lead">';
								/* echo '<h2>' . $siteIntroTitle . '</h2>'; */
								echo get_the_content();
								echo '</h3>';
							endwhile;
						endif;
						wp_reset_postdata();
					?>
					</section><!-- Site introduction -->
			<?php
				/**** SPECIAL CASE: mobile apps landing page gets a little teaser with contact info ***/
				}
			?>
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
					echo '</div><!-- .usa-grid -->';

					echo '<div class="usa-grid">';
					/*
					$args = array(
						'prev_text'          => __( 'Older projects' ),
						'next_text'          => __( 'Newer projects' ),
						'screen_reader_text' => __( 'Project navigation' )
					);*/

					echo '<nav class="navigation posts-navigation" role="navigation">';
					echo '<h2 class="screen-reader-text">Project navigation</h2>';
					echo '<div class="nav-links">';
					echo '<!-- max is '  . $custom_query->max_num_pages . '-->';
					$nextLink=get_next_posts_link('Older Posts', $custom_query->max_num_pages);
					$prevLink=get_previous_posts_link('Newer Posts');
					if ($nextLink != "") {
						echo '<div class="nav-previous">';
						echo $nextLink;
						echo '</div>';
					}

					if ($prevLink != "") {
						echo '<div class="nav-next">';
						echo $prevLink;
						echo '</div>';	
					}
					
					echo '</div>';
					echo '</nav>';
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


