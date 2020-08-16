=== Linkify Authors ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: authors, link, linkify, archives, list, widget, template tag, coffee2code
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.3
Tested up to: 5.5
Stable tag: 2.2.4

Turn a string, list, or array of author IDs and/or slugs into a list of links to those authors. Provides a widget and template tag.


== Description ==

The plugin provides a widget called "Linkify Authors" as well as a template tag, `c2c_linkify_authors()`, which allow you to easily specify authors to list and how to list them. Authors are specified by either ID or slug. See other parts of the documentation for example usage and capabilities.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/linkify-authors/) | [Plugin Directory Page](https://wordpress.org/plugins/linkify-authors/) | [GitHub](https://github.com/coffee2code/linkify-authors/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `linkify-authors.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use the provided widget or the `c2c_linkify_authors()` template tag in one of your templates (be sure to pass it at least the first argument indicating what author IDs and/or slugs to linkify -- the argument can be an array, a space-separate list, or a comma-separated list). Other optional arguments are available to customize the output.
4. Optional: Use the "Linkify Authors" widget in one of the sidebars provided by your theme.


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

= Does this plugin include unit tests? =

Yes.


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
(optional) To appear when no posts have been found. If blank, then the entire function doesn't display anything

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

`<a href="https://example.com/archives/author/admin">Scott</a>, <a href="https://example.com/archives/author/billm">Bill</a>`

* Assume that you have a custom field with a key of "Related Authors" that happens to have a value of "3, 9" defined (and you're in-the-loop).

Outputs something like:

`Related authors: <a href="https://example.com/archives/author/admin">Scott</a>, <a href="https://example.com/archives/author/billm">Bill</a>`

* `<ul><?php c2c_linkify_authors("3, 9", "<li>", "</li>", "</li><li>"); ?></ul>`

Outputs something like:

`<ul><li><a href="https://example.com/archives/author/admin">Scott</a></li>
<li><a href="https://example.com/archives/author/billm">Bill</a></li></ul>`

* `<?php c2c_linkify_authors(""); // Assume you passed an empty string as the first value ?>`

Displays nothing.

* `<?php c2c_linkify_authors("", "", "", "", "", "No related authors."); // Assume you passed an empty string as the first value ?>`

Outputs:

`No related authors.`


== Hooks ==

The plugin exposes one action for hooking.

**c2c_linkify_authors (action)**

The 'c2c_linkify_authors' hook allows you to use an alternative approach to safely invoke `c2c_linkify_authors()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

Arguments:

* same as for `c2c_linkify_authors()`

Example:

Instead of:

`<?php c2c_linkify_authors( '19, 28', 'Authors: ' ); ?>`

Do:

`<?php do_action( 'c2c_linkify_authors', '19, 28', 'Authors: ' ); ?>`


== Changelog ==

= 2.2.4 (2020-05-05) =
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Update examples in documentation to use a proper example URL

= 2.2.3 (2019-11-25) =
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* New: Add optional step to installation instructions to note availability of the widget
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Note compatibility through WP 5.3+
* Change: Add link to plugin's page in Plugin Directory to README.md
* Change: Update copyright date (2020)
* Change: Split paragraph in README.md's "Support" section into two

= 2.2.2 (2019-02-01) =
* New: Add README.md
* Change: Escape text used in markup attributes (hardening)
* Change: Add GitHub link to readme
* Change: Unit tests: Minor whitespace tweaks to bootstrap
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/linkify-authors/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.2.4 =
Trivial update: Updated a few URLs to be HTTPS and noted compatibility through WP 5.4+.

= 2.2.3 =
Trivial update: modernized unit tests, created CHANGELOG.md to store historical changelog outside of readme.txt, noted compatibility through WP 5.3+, and updated copyright date (2020)

= 2.2.2 =
Trivial update: noted compatibility through WP 5.1+ and updated copyright date (2019)

= 2.2.1 =
Trivial update: fixed some unit tests, noted compatibility through WP 4.7+, updated copyright date (2017)

= 2.2 =
Minor update: minor updates to widget code and unit tests; verified compatibility through WP 4.4; updated copyright date (2016).

= 2.1.3 =
Bugfix update: Prevented PHP notice under PHP7+ for widget; noted compatibility through WP 4.3+

= 2.1.2 =
Trivial update: noted compatibility through WP 4.1+ and updated copyright date

= 2.1.1 =
Trivial update: noted compatibility through WP 4.0+; added plugin icon.

= 2.1 =
Moderate update: fallback failed user_login check to user_nicename; better validate data received; added unit tests; noted compatibility through WP 3.8+

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
