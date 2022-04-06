<?php

/**
 * Show custom Primary sidebar in Primary Sidebar location.
 *
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/
 */
function sk_change_genesis_primary_sidebar() {

	if( is_active_sidebar( 'news-single-sidebar' ) ) {

		// Remove the Primary Sidebar from the Primary Sidebar area.
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

		add_action( 'genesis_sidebar', 'sk_do_sidebar' );
	}

}

function sk_do_sidebar() {

	dynamic_sidebar( 'news-single-sidebar' );

}

//* To remove empty markup, '<p class="entry-meta"></p>' for entries that have not been assigned to any Genre
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_footer', 'sk_custom_post_meta' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_content', 'custom_entry_content' ); // Add custom loop

function custom_entry_content() {
    //Custom Entry Content
}
?>

<div class="main">
<div class="container">
<div class="single-controller">	
<div class="row">

    <div class="col-md-9">
        <div class="left">
            <!-- sidebar left -->
            <?php if ( has_post_thumbnail() ) { 
            	?>

<div class="featured">
  <?php the_post_thumbnail(); } ?>
  <h2><?php the_title(); ?></h2>
  <p><?php  the_content(); ?></p>
</div>

        </div>
    </div>
    <div class="col-md-3">
        <div class="right">
        	<h2>Latest News</h2>
           <?php dynamic_sidebar( 'news-single-sidebar' );?>
        </div>
    </div>
</div>
</div>
</div>
</div>

<?php
genesis();