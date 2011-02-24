<?php
/**
 * @package Linkify_Authors
 * @author Scott Reilly
 * @version 2.0
 */
/*
Plugin Name: Linkify Authors
Version: 2.0
Plugin URI: http://coffee2code.com/wp-plugins/linkify-authors/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.

Compatible with WordPress 2.8+, 2.9+, 3.0+, 3.1+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/linkify-authors/

*/

/*
Copyright (c) 2009-2011 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

require_once( dirname( __FILE__ ) . '/linkify-authors.widget.php' );

if ( !function_exists( 'c2c_linkify_authors' ) ) :
/**
 * Displays links to each of any number of authors specified via author IDs/slugs
 *
 * @since 2.0
 *
 * @param int|string|array $authors A single author ID/slug, or multiple author IDs/slugs defined via an array, or multiple author IDs/slugs defined via a comma-separated and/or space-separated string
 * @param string $before (optional) Text to appear before the entire author listing (if authors exist or if 'none' setting is specified)
 * @param string $after (optional) Text to appear after the entire author listing (if authors exist or if 'none' setting is specified)
 * @param string $between (optional) Text to appear between all authors
 * @param string $before_last (optional) Text to appear between the second-to-last and last element, if not specified, 'between' value is used
 * @param string $none (optional) Text to appear when no authors have been found.  If blank, then the entire function doesn't display anything
 * @return none (Text is echoed; nothing is returned)
 */
function c2c_linkify_authors( $authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) {
	if ( empty( $authors ) )
		$authors = array();
	elseif ( !is_array( $authors ) )
		$authors = explode( ',', str_replace( array( ', ', ' ', ',' ), ',', $authors ) );

	if ( empty( $authors ) ) {
		$response = '';
	} else {
		$links = array();
		foreach ( $authors as $id ) {
			if ( 0 == (int) $id ) {
				$author = get_userdatabylogin( $id );
				if ( $author )
					$id = $author->ID;
			}
			if ( !$id )
				continue;
			$title = get_the_author_meta( 'display_name', $id );
			if ( $title )
				$links[] = sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					get_author_posts_url( $id ),
					esc_attr( sprintf( __( 'Posts by %s' ), $title ) ),
					$title
				);
		}
		if ( empty( $before_last ) ) {
			$response = implode( $between, $links );
		} else {
			switch ( $size = sizeof( $links ) ) {
				case 1:
					$response = $links[0];
					break;
				case 2:
					$response = $links[0] . $before_last . $links[1];
					break;
				default:
					$response = implode( $between, array_slice( $links, 0, $size-1 ) ) . $before_last . $links[$size-1];
			}
		}
	}
	if ( empty( $response ) ) {
		if ( empty( $none ) )
			return;
		$response = $none;
	}
	echo $before . $response . $after;
}
add_action( 'c2c_linkify_authors', 'c2c_linkify_authors', 10, 6 );
endif;

if ( !function_exists( 'linkify_authors' ) ) :
/**
 * Displays links to each of any number of authors specified via author IDs/slugs
 *
 * @since 1.0
 * @deprecated 2.0 Use c2c_linkify_authors() instead
 */
function linkify_authors( $authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) {
	_deprecated_function( 'linkify_authors', '2.0', 'c2c_linkify_authors' );
	return c2c_linkify_authors( $authors, $before, $after, $between, $before_last, $none );
}
add_action( 'linkify_authors', 'linkify_authors', 10, 6 ); // Deprecated
endif;
?>