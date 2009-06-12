=== Linkify Authors ===
Contributors: Scott Reilly
Donate link: http://coffee2code.com/donate
Tags: authors, link, linkify, archives, list, template tag, coffee2code
Requires at least: 2.5
Tested up to: 2.8
Stable tag: 1.0
Version: 1.0

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

1. Unzip `linkify-authors.zip` inside the `/wp-content/plugins/` directory for your site
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the `linkify_authors()` template author in one of your templates (be sure to pass it at least the first argument indicating what author IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list).  Other optional arguments are available to customize the output.

