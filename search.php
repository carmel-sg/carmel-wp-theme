<?php
/**
 * Search template.
 *
 * @package Carmel
 * @license GPL-2.0-or-later
 */

add_action( 'genesis_after_header', 'carmel_search_title' );
/**
 * Echos the search title.
 */
function carmel_search_title() {

	echo '<div class="archive-description"><h1 class="archive-title">Search</h1></div>';
}

add_action( 'genesis_before_loop', 'carmel_search_header' );
/**
 * Echos the title with the search term.
 */
function carmel_search_header() {

	$title = sprintf( '<h1>%s %s</h1>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), get_search_query() );

	echo apply_filters( 'genesis_search_title_output', $title ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}

genesis();

