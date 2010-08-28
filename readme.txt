=== Linkify Authors ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: authors, link, linkify, archives, list, template tag, coffee2code
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.2
Version: 1.2

Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.


== Description ==

Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors.

These are all valid calls:

`<?php linkify_authors(3); ?>`
`<?php linkify_authors("3"); ?>`
`<?php linkify_authors("scott"); ?>`
`<?php linkify_authors("3 9 10"); ?>`
`<?php linkify_authors("scott bill alice"); ?>`
`<?php linkify_authors("scott 9 alice"); ?>`
`<?php linkify_authors("3,9,10"); ?>`
`<?php linkify_authors("scott,bill,alice"); ?>`
`<?php linkify_authors("scott,92,alice"); ?>`
`<?php linkify_authors("3, 9, 10"); ?>`
`<?php linkify_authors("scott, bill, alice"); ?>`
`<?php linkify_authors("scott, 92, alice"); ?>`
`<?php linkify_authors(array(43,92,102)); ?>`
`<?php linkify_authors(array("43","92","102")); ?>`
`<?php linkify_authors(array("scott","bill","alice")); ?>`
`<?php linkify_authors(array("scott",92,"alice")); ?>`

`<?php linkify_authors("3 9"); ?>`
Displays something like:
`<a href="http://yourblog.com/archives/author/admin">Scott</a>, <a href="http://yourblog.com/archives/author/billm">Bill</a>`

`<ul><?php linkify_authors("3, 9", "<li>", "</li>", "</li><li>"); ?></ul>`
Displays something like:
`<ul><li><a href="http://yourblog.com/archives/author/admin">Scott</a></li>
<li><a href="http://yourblog.com/archives/author/billm">Bill</a></li></ul>`

`<?php linkify_authors(""); // Assume you passed an empty string as the first value ?>`
Displays nothing.

`<?php linkify_authors("", "", "", "", "", "No related authors."); // Assume you passed an empty string as the first value ?>`
Displays:
No related authors.


== Installation ==

1. Unzip `linkify-authors.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the `linkify_authors()` template author in one of your templates (be sure to pass it at least the first argument indicating what author IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list).  Other optional arguments are available to customize the output.


== Filters ==

The plugin exposes one action for hooking.

= linkify_authors (action) =

The 'linkify_authors' hook allows you to use an alternative approach to safely invoke `linkify_authors()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

Arguments:

* same as for `linkify_authors()`

Example:

Instead of:

`<?php linkify_authors( '19, 28', 'Authors: ' ); ?>`

Do:

`<?php do_action( 'linkify_authors', '19, 28', 'Authors: ' ); ?>`


== Changelog ==

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

= 1.2 =
Minor update. Highlights: switched away from use of deprecated function; added filter to allow alternative safe invocation of function; verified WP 3.0 compatibility.