=== Linkify Authors ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: authors, link, linkify, archives, list, widget, template tag, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 2.0.4
Version: 2.0.4

Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.


== Description ==

Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.

The plugin provides a widget called "Linkify Authors" as well as a template tag, `c2c_linkify_authors()`, to easily indicate authors to list and how to list them.  Authors are specified by either ID or slug.  See other parts of the documentation for example usage and capabilities.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/linkify-authors/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/linkify-authors/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `linkify-authors.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the provided widget or the `c2c_linkify_authors()` template tag in one of your templates (be sure to pass it at least the first argument indicating what author IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list).  Other optional arguments are available to customize the output.


== Screenshots ==

1. The plugin's widget configuration.


== Frequently Asked Questions ==

= What happens if I tell it to list something that I have mistyped, haven't created yet, or have deleted? =

If a given ID/slug doesn't match up with an existing author then that item is ignored without error.

= How do I get items to appear as a list (using HTML tags)? =

Whether you use the template tag or the widget, specify the following information for the appropriate fields/arguments:

Before text: `<ul><li>` (or `<ol><li>`)
After text: `</li></ul>` (or `</li></ol>`)
Between authors: `</li><li>`


== Template Tags ==

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

= Functions =

* `<?php c2c_linkify_authors( $authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) ?>`
Displays links to each of any number of authors specified via author IDs/slugs

= Arguments =

* `$authors`
A single author ID/slug, or multiple author IDs/slugs defined via an array, or multiple authors IDs/slugs defined via a comma-separated and/or space-separated string

* `$before`
(optional) To appear before the entire author listing (if authors exist or if 'none' setting is specified)

* `$after`
(optional) To appear after the entire author listing (if authors exist or if 'none' setting is specified)

* `$between`
(optional) To appear between authors

* `$before_last`
(optional) To appear between the second-to-last and last element, if not specified, 'between' value is used

* `$none`
(optional) To appear when no posts have been found.  If blank, then the entire function doesn't display anything

= Examples =

* These are all valid calls:

`<?php c2c_linkify_authors(3); ?>`
`<?php c2c_linkify_authors("3"); ?>`
`<?php c2c_linkify_authors("scott"); ?>`
`<?php c2c_linkify_authors("3 9 10"); ?>`
`<?php c2c_linkify_authors("scott bill alice"); ?>`
`<?php c2c_linkify_authors("scott 9 alice"); ?>`
`<?php c2c_linkify_authors("3,9,10"); ?>`
`<?php c2c_linkify_authors("scott,bill,alice"); ?>`
`<?php c2c_linkify_authors("scott,92,alice"); ?>`
`<?php c2c_linkify_authors("3, 9, 10"); ?>`
`<?php c2c_linkify_authors("scott, bill, alice"); ?>`
`<?php c2c_linkify_authors("scott, 92, alice"); ?>`
`<?php c2c_linkify_authors(array(43,92,102)); ?>`
`<?php c2c_linkify_authors(array("43","92","102")); ?>`
`<?php c2c_linkify_authors(array("scott","bill","alice")); ?>`
`<?php c2c_linkify_authors(array("scott",92,"alice")); ?>`

* `<?php c2c_linkify_authors("3 9"); ?>`

Outputs something like:

`<a href="http://yourblog.com/archives/author/admin">Scott</a>, <a href="http://yourblog.com/archives/author/billm">Bill</a>`

* Assume that you have a custom field with a key of "Related Authors" that happens to have a value of "3, 9" defined (and you're in-the-loop).

Outputs something like:

`Related authors: <a href="http://yourblog.com/archives/author/admin">Scott</a>, <a href="http://yourblog.com/archives/author/billm">Bill</a>`

* `<ul><?php c2c_linkify_authors("3, 9", "<li>", "</li>", "</li><li>"); ?></ul>`

Outputs something like:

`<ul><li><a href="http://yourblog.com/archives/author/admin">Scott</a></li>
<li><a href="http://yourblog.com/archives/author/billm">Bill</a></li></ul>`

* `<?php c2c_linkify_authors(""); // Assume you passed an empty string as the first value ?>`

Displays nothing.

* `<?php c2c_linkify_authors("", "", "", "", "", "No related authors."); // Assume you passed an empty string as the first value ?>`

Outputs:

`No related authors.`


== Filters ==

The plugin exposes one action for hooking.

= c2c_linkify_authors (action) =

The 'c2c_linkify_authors' hook allows you to use an alternative approach to safely invoke `c2c_linkify_authors()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

Arguments:

* same as for `c2c_linkify_authors()`

Example:

Instead of:

`<?php c2c_linkify_authors( '19, 28', 'Authors: ' ); ?>`

Do:

`<?php do_action( 'c2c_linkify_authors', '19, 28', 'Authors: ' ); ?>`


== Changelog ==

= 2.0.4 =
* Add check to prevent execution of code if file is directly accessed
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Create repo's WP.org assets directory
* Move screenshot into repo's assets directory

= 2.0.3 =
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

= 2.0.2 =
* Note compatibility through WP 3.3+
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

= 2.0.1 =
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

= 2.0 =
* Add Linkify Authors widget
* Rename `linkify_authors()` to `c2c_linkify_authors()` (but maintain a deprecated version for backwards compatibility)
* Rename 'linkify_authors' action to 'c2c_linkify_authors' (but maintain support for backwards compatibility)
* Add Template Tag, Screenshots, and Frequently Asked Questions sections to readme.txt
* Add screenshot of widget admin
* Changed Description in readme.txt
* Note compatibility through WP 3.1+
* Update copyright date (2011)

= 1.2 =
* Use get_the_author_meta('display_name') instead of deprecated get_author_name()
* Add filter 'linkify_authors' to respond to the function of the same name so that users can use the do_action() notation for invoking template tags
* Wrap function in if(!function_exists()) check
* Reverse order of implode() arguments
* Fix to prevent PHP notice
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor tweaks to code formatting (spacing)
* Add Filters and Upgrade Notice sections to readme.txt
* Remove trailing whitespace

= 1.1 =
* Add PHPDoc documentation
* Use esc_attr() instead of the deprecated attribute_escape()
* Minor formatting tweaks
* Note compatibility with WP 2.9+
* Drop compatibility with WP older than 2.8
* Update copyright date
* Update readme.txt (including adding Changelog)

= 1.0 =
* Initial release


== Upgrade Notice ==

= 2.0.4 =
Trivial update: noted compatibility through WP 3.5+

= 2.0.3 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license

= 2.0.2 =
Trivial update: noted compatibility through WP 3.3+ and minor readme.txt tweaks

= 2.0.1 =
Trivial update: noted compatibility through WP 3.2+ and minor code formatting changes (spacing)

= 2.0 =
Feature update: added widget, deprecated `linkify_authors()` in favor of `c2c_linkify_authors()`, renamed action from 'linkify_authors' to 'c2c_linkify_authors', noted compatibility with WP 3.1+, added Template Tags and FAQ sections to readme, and updated copyright date (2011).

= 1.2 =
Minor update. Highlights: switched away from use of deprecated function; added filter to allow alternative safe invocation of function; verified WP 3.0 compatibility.
