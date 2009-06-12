<?php
/*
Plugin Name: Linkify Authors
Version: 1.0
Plugin URI: http://coffee2code.com/wp-plugins/linkify-authors
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.

Compatible with WordPress 2.5+, 2.6+, 2.7+, 2.8+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1. Download the file http://coffee2code.com/wp-plugins/linkify-authors.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the linkify_authors() template tag in one of your templates (be sure to pass it at least the first argument
	indicating what author IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a
	comma-separated list).  Other optional arguments are available to customize the output.


Examples:

These are all valid calls:
	<?php linkify_authors(3); ?>
	<?php linkify_authors("3"); ?>
	<?php linkify_authors("scott"); ?>
	<?php linkify_authors("3 9 10"); ?>
	<?php linkify_authors("scott bill alice"); ?>
	<?php linkify_authors("scott 9 alice"); ?>
	<?php linkify_authors("3,9,10"); ?>
	<?php linkify_authors("scott,bill,alice"); ?>
	<?php linkify_authors("scott,92,alice"); ?>
	<?php linkify_authors("3, 9, 10"); ?>
	<?php linkify_authors("scott, bill, alice"); ?>
	<?php linkify_authors("scott, 92, alice"); ?>
	<?php linkify_authors(array(43,92,102)); ?>
	<?php linkify_authors(array("43","92","102")); ?>
	<?php linkify_authors(array("scott","bill","alice")); ?>
	<?php linkify_authors(array("scott",92,"alice")); ?>

<?php linkify_authors("3 9"); ?>
Displays something like:
	<a href="http://yourblog.com/archives/author/admin">Scott</a>, 
	<a href="http://yourblog.com/archives/author/billm">Bill</a>

<ul><?php linkify_authors("3, 9", "<li>", "</li>", "</li><li>"); ?></ul>
Displays something like:
	<ul><li><a href="http://yourblog.com/archives/author/admin">Scott</a></li>
	<li><a href="http://yourblog.com/archives/author/billm">Bill</a></li></ul>

<?php linkify_authors(""); // Assume you passed an empty string as the first value ?>
Displays nothing.

<?php linkify_authors("", "", "", "", "", "No related authors."); // Assume you passed an empty string as the first value ?>
Displays:
	No related authors.

*/

/*
Copyright (c) 2009 by Scott Reilly (aka coffee2code)

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

/*
	Displays links to each of any number of authors specified via author IDs/slugs
	Arguments:
	 $authors: A single author ID/slug, or multiple author IDs/slugs defined via an array, or multiple author IDs/slugs
					defined via a comma-separated and/or space-separated string
	 $before 	: (optional) To appear before the entire author listing (if authors exist or if 'none' setting is specified)
	 $after 	: (optional) To appear after the entire author listing (if authors exist or if 'none' setting is specified)
	 $between	: (optional) To appear between all authors
	 $before_last : (optional) To appear between the second-to-last and last element, if not specified, 'between' value is used	
	 $none		: (optional) To appear when no authors have been found.  If blank, then the entire function doesn't display anything
 */
function linkify_authors($authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '') {
	if ( empty($authors) )
		$authors = array();
	elseif ( !is_array($authors) )
		$authors = explode(',', str_replace(array(', ', ' ', ','), ',', $authors));

	if ( empty($authors) ) $response = '';
	else {
		$links = array();
		foreach ( $authors as $id ) {
			if ( 0 == (int)$id ) {
				$author = get_userdatabylogin($id);
				$id = $author->ID;
			}
			$title = get_author_name($id);
			if ( $title )
				$links[] = sprintf(
					'<a href="%1$s" title="%2$s">%3$s</a>',
					get_author_posts_url($id),
					sprintf(__('Posts by %s'), attribute_escape($title)),
					$title
				);
		}
		if ( empty($before_last) ) $response = implode($links, $between);
		else {
			switch ( $size = sizeof($links) ) {
				case 1:
					$response = $links[0];
					break;
				case 2:
					$response = $links[0] . $before_last . $links[1];
					break;
				default:
					$response = implode(array_slice($links,0,$size-1), $between) . $before_last . $links[$size-1];
			}
		}
	}
	if ( empty($response) ) {
		if ( empty($none) ) return;
		$response = $none;
	}
	echo $before . $response . $after;
}
?>