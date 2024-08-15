<?php
/**
 * Plugin Name: Linkify Authors
 * Version:     2.4
 * Plugin URI:  https://coffee2code.com/wp-plugins/linkify-authors/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: linkify-authors
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Turn a string, list, or array of author IDs and/or slugs into a list of links to the post archive for each author. Includes widget and template tag.
 *
 * Compatible with WordPress 3.3 through 6.6+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/linkify-authors/
 *
 * @package Linkify_Authors
 * @author  Scott Reilly
 * @version 2.4
 */

/*
	Copyright (c) 2009-2024 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'linkify-authors.widget.php' );

if ( ! function_exists( 'c2c_linkify_authors' ) ) :
/**
 * Displays links to each of any number of authors specified via author IDs/slugs
 *
 * @since 2.0
 *
 * @param int|string|array $authors     A single author ID/slug, or multiple author IDs/slugs defined via an array, or multiple author IDs/slugs defined
 *                                      via a comma-separated and/or space-separated string
 * @param string           $before      Optional. Text to appear before the entire author listing (if authors exist or if 'none' setting is specified). Default empty string.
 * @param string           $after       Optional. Text to appear after the entire author listing (if authors exist or if 'none' setting is specified). Default empty string.
 * @param string           $between     Optional. Text to appear between all authors. Default ', '.
 * @param string           $before_last Optional. Text to appear between the second-to-last and last element, if not specified, 'between' value is used. Default empty string.
 * @param string           $none        Optional. Text to appear when no authors have been found.  If blank, then the entire function doesn't display anything. Default empty string.
 */
function c2c_linkify_authors( $authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) {
	if ( empty( $authors ) ) {
		$authors = array();
	} elseif ( ! is_array( $authors ) ) {
		$authors = explode( ',', str_replace( array( ', ', ' ', ',' ), ',', $authors ) );
	}

	if ( empty( $authors ) ) {
		$response = '';
	} else {
		$links = array();
		foreach ( $authors as $id ) {
			if ( 0 == (int) $id ) {
				if ( ! is_string( $id ) ) {
					continue;
				}
				$author = get_user_by( 'login', $id );
				if ( ! $author ) {
					$author = get_user_by( 'slug', $id );
				}
				if ( $author ) {
					$id = $author->ID;
				}
			}
			if ( ! $id ) {
				continue;
			}

			$link = __c2c_linkify_authors_get_author_link( $id );
			if ( $link ) {
				$links[] = $link;
			}
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
					$response = implode( $between, array_slice( $links, 0, $size-1 ) ) . $before_last . $links[ $size-1 ];
			}
		}
	}

	if ( empty( $response ) ) {
		if ( empty( $none ) ) {
			return;
		}
		$response = $none;
	}
	// Output authors (which is permitted to include markup).
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $before . $response . $after;
}
add_action( 'c2c_linkify_authors', 'c2c_linkify_authors', 10, 6 );
endif;

/**
 * Returns the post author archive link for a user.
 *
 * @access private
 *
 * @param int $user_id The user ID.
 * @return string
 */
function __c2c_linkify_authors_get_author_link( $user_id ) {
	$title = get_the_author_meta( 'display_name', $user_id );

	if ( ! $title ) {
		return '';
	}

	return sprintf(
		'<a href="%1$s" title="%2$s">%3$s</a>',
		esc_url( get_author_posts_url( $user_id ) ),
		/* translators: %s: Author's display name */
		esc_attr( sprintf( __( 'Posts by %s', 'linkify-authors' ), $title ) ),
		esc_attr( $title )
	);
}
