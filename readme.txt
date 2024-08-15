=== Linkify Authors ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: author, link, linkify, archives, widget
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.3
Tested up to: 6.6
Stable tag: 2.4

Turn a string, list, or array of author IDs and/or slugs into a list of links to the post archive for each author. Provides a widget and template tag.


== Description ==

The plugin provides a widget called "Linkify Authors" as well as a template tag, `c2c_linkify_authors()`, which allow you to easily specify authors to list and how to list them. Authors are specified by either ID or slug. Each link will point to the post archive for each user. See other parts of the documentation for example usage and capabilities.

Links: [Plugin Homepage](https://coffee2code.com/wp-plugins/linkify-authors/) | [Plugin Directory Page](https://wordpress.org/plugins/linkify-authors/) | [GitHub](https://github.com/coffee2code/linkify-authors/) | [Author Homepage](https://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or install the plugin code inside the plugins directory for your site (typically `/wp-content/plugins/`).
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

Yes. The tests are not packaged in the release .zip file or included in plugins.svn.wordpress.org, but can be found in the [plugin's GitHub repository](https://github.com/coffee2code/linkify-authors/).


== Developer Documentation ==

Developer documentation can be found in [DEVELOPER-DOCS.md](https://github.com/coffee2code/linkify-authors/blob/master/DEVELOPER-DOCS.md). That documentation covers the template tag and hook provided by the plugin.

As an overview, this is the template tag provided by the plugin:

* `c2c_linkify_authors()` : Display links to each of any number of authors specified via author IDs/slugs. You can customize text to show before and/or after the entire listing, between each author, and what to display (if anything) when no authors are listed.

This is the hook provided by the plugin:

* `c2c_linkify_authors` : Allows use of an alternative approach to safely invoke `c2c_linkify_authors()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.


== Changelog ==

= 2.4 (2024-08-15) =
Highlights:

This recommended release features improvements to widget implementation, adds some hardening measures, notes compatibility through WP 6.6+, removes unit tests from release packaging, updates copyright date (2024), and other code improvements and minor changes.

Details:

* Widget:
    * New: Extract base widget functionality common amongst my Linkify family of plugins into reusable base class
    * Change: Define a default 'none' message so that something is shown when no authors are specified
    * Change: Improve spacing in block editor around widget input field help text
    * New: Add `get_config()` to retrieve configuration
    * New: Add unit tests
    * Change: Update version to 005
* New: Extract code for creating link to author's post archive into new `__c2c_linkify_authors_get_author_link()`
* Hardening: Escape some variables prior to being output
* Change: Tweak descriptions to clarify that the links are to each author's post archive
* Change: Add default values for optional arguments to inline parameter documentation
* Change: Note compatibility through WP 6.6+
* Change: Prevent unwarranted PHPCS complaints about unescaped output (HTML is allowed)
* Change: Update copyright date (2024)
* Change: Reduce number of 'Tags' from `readme.txt`
* Change: Remove development and testing-related files from release packaging
* Hardening: Unit tests: Prevent direct web access to `bootstrap.php`
* New: Add some potential TODO items

= 2.3.1 (2023-08-22) =
* Fix: Fix some typos in documentation
* Change: Note compatibility through WP 6.3+
* Change: Update copyright date (2023)
* New: Add `.gitignore` file
* Unit tests:
    * Allow tests to run against current versions of WordPress
    * New: Add `composer.json` for PHPUnit Polyfill dependency
    * Change: Prevent PHP warnings due to missing core-related generated files

= 2.3 (2021-10-16) =
Highlights:

This minor release removes support for the long-deprecated `linkify_authors()`, adds DEVELOPER-DOCS.md, notes compatibility through WP 5.8+, and minor reorganization and tweaks to unit tests.

Details:

* Change: Remove long-deprecated function `linkify_authors()`
* New: Add DEVELOPER-DOCS.md and move template tag and hooks documentation into it
* Fix: Fix Markdown formatting for code examples in readme.txt
* Change: Tweak installation instruction
* Change: Note compatibility through WP 5.8+
* Unit tests:
    * Change: Restructure unit test directories
        * Change: Move `phpunit/` into `tests/phpunit/`
        * Change: Move `phpunit/bin/` into `tests/`
    * Change: Remove 'test-' prefix from unit test file
    * Change: In bootstrap, store path to plugin file constant
    * Change: In bootstrap, add backcompat for PHPUnit pre-v6.0

_Full changelog is available in [CHANGELOG.md](https://github.com/coffee2code/linkify-authors/blob/master/CHANGELOG.md)._


== Upgrade Notice ==

= 2.4 =
Recommended update: improved widget implementation, added some hardening measures, noted compatibility through WP 6.6+, removed unit tests from release packaging, updated copyright date (2024), and other code improvements and minor changes.

= 2.3.1 =
Trivial update: noted compatibility through WP 6.3+, updated unit tests to run against latest WordPress, fixed some typos in inline documentation, and updated copyright date (2023)

= 2.3 =
Minor update: removed support for long-deprecated `linkify_authors()`, added DEVELOPER-DOCS.md, noted compatibility through WP 5.8+, and minor reorganization and tweaks to unit tests

= 2.2.6 =
Trivial update: noted compatibility through WP 5.7+ and updated copyright date (2021).

= 2.2.5 =
Trivial update: Restructured unit test file structure, added a TODO.md file, and noted compatibility through WP 5.5+.

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
