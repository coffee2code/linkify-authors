# Changelog

## 2.4 _(2024-08-15)_

### Highlights:

This recommended release features improvements to widget implementation, adds some hardening measures, notes compatibility through WP 6.6+, removes unit tests from release packaging, updates copyright date (2024), and other code improvements and minor changes.

### Details:

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

## 2.3.1 _(2023-08-22)_
* Fix: Fix some typos in documentation
* Change: Note compatibility through WP 6.3+
* Change: Update copyright date (2023)
* New: Add `.gitignore` file
* Unit tests:
    * Allow tests to run against current versions of WordPress
    * New: Add `composer.json` for PHPUnit Polyfill dependency
    * Change: Prevent PHP warnings due to missing core-related generated files

## 2.3 _(2021-10-16)_

### Highlights:

This minor release removes support for the long-deprecated `linkify_authors()`, adds DEVELOPER-DOCS.md, notes compatibility through WP 5.8+, and minor reorganization and tweaks to unit tests.

### Details:

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

## 2.2.6 _(2021-04-26)_
* Change: Add textdomain for lone non-widget string
* Change: Note compatibility through WP 5.7+
* Change: Update copyright date (2021)
* Change: Fix formatting for readme.txt changelog entry for v2.2.5
* Change: Unit tests: Move install script into `phpunit/bin/` where it was originally meant to be
* New: Add a few more possible TODO items

## 2.2.5 _(2020-08-15)_
* New: Add TODO.md and move existing TODO list from top of main plugin file into it
* Change: Restructure unit test file structure
    * New: Create new subdirectory `phpunit/` to house all files related to unit testing
    * Change: Move `bin/` to `phpunit/bin/`
    * Change: Move `tests/bootstrap.php` to `phpunit/`
    * Change: Move `tests/` to `phpunit/tests/`
    * Change: Rename `phpunit.xml` to `phpunit.xml.dist` per best practices
* Change: Note compatibility through WP 5.5+

## 2.2.4 _(2020-05-05)_
* Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests
* Change: Note compatibility through WP 5.4+
* Change: Update links to coffee2code.com to be HTTPS
* Change: Update examples in documentation to use a proper example URL

## 2.2.3 _(2019-11-25)_
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* New: Add optional step to installation instructions to note availability of the widget
* Change: Update unit test install script and bootstrap to use latest WP unit test repo
* Change: Note compatibility through WP 5.3+
* Change: Add link to plugin's page in Plugin Directory to README.md
* Change: Update copyright date (2020)
* Change: Split paragraph in README.md's "Support" section into two

## 2.2.2 _(2019-02-01)_
* New: Add README.md
* Change: Escape text used in markup attributes (hardening)
* Change: Add GitHub link to readme
* Change: Unit tests: Minor whitespace tweaks to bootstrap
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 2.2.1 _(2017-02-24)_
* Fix: Fix unit tests by not declaring test class variable as static
* Change: Update unit test bootstrap
    * Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Enable more error output for unit tests
* Change: Note compatibility through WP 4.7+
* Change: Minor readme.txt content and formatting tweaks
* Change: Update copyright date (2017)

## 2.2 _(2016-03-11)_
* Change: Update widget to 004:
    * Add `register_widget()` and change to calling it when hooking 'admin_init'.
    * Add `version()` to return the widget's version.
    * Reformat config array.
    * Discontinue use of old-style constructor.
    * Add inline docs for class variables.
    * Late-escape attribute values.
    * Reorder some conditional expressions.
* Change: Explicitly declare methods in unit tests as public or protected.
* Change: Fix and simplify unit tests. Add tests for widget.
* New: Add 'Text Domain' to plugin header.
* New: Add LICENSE file.
* New: Add empty index.php to prevent files from being listed if web server has enabled directory listings.
* Change: Use `DIRECTORY_SEPARATOR` in place of '/' when requiring widget file.
* Change: Note compatibility through WP 4.4+.
* Change: Update copyright date (2016).

## 2.1.3 _(2015-08-12)_
* Update: Discontinue use of PHP4-style constructor invocation of WP_Widget to prevent PHP notices in PHP7
* Update: Minor widget header reformatting
* Update: Minor widget file code tweaks (spacing, bracing)
* Update: Minor inline document tweaks (spacing)
* Note compatibility through WP 4.3+

## 2.1.2 _(2015-02-11)_
* Note compatibility through WP 4.1+
* Update copyright date (2015)

## 2.1.1 _(2014-08-26)_
* Minor plugin header reformatting
* Change documentation links to wp.org to be https
* Note compatibility through WP 4.0+
* Add plugin icon

## 2.1 _(2013-12-20)_
* If a slug search for a user by login fails, try by nicename
* Discontinue use of deprecated `get_userdatabylogin()` and use `get_user_by()` instead
* Validate author is either int or string before handling
* Add unit tests
* Minor code tweaks (spacing, bracing)
* Minor documentation tweaks
* Note compatibility through WP 3.8+
* Discontinue compatibility with WP older than 3.3
* Update copyright date (2014)
* Change donate link
* Add banner

## 2.0.4
* Add check to prevent execution of code if file is directly accessed
* Note compatibility through WP 3.5+
* Update copyright date (2013)
* Create repo's WP.org assets directory
* Move screenshot into repo's assets directory

## 2.0.3
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Remove ending PHP close tag
* Note compatibility through WP 3.4+

## 2.0.2
* Note compatibility through WP 3.3+
* Add link to plugin directory page to readme.txt
* Update copyright date (2012)

## 2.0.1
* Note compatibility through WP 3.2+
* Minor code formatting changes (spacing)
* Fix plugin homepage and author links in description in readme.txt

## 2.0
* Add Linkify Authors widget
* Rename `linkify_authors()` to `c2c_linkify_authors()` (but maintain a deprecated version for backwards compatibility)
* Rename `linkify_authors` action to `c2c_linkify_authors` (but maintain support for backwards compatibility)
* Add Template Tag, Screenshots, and Frequently Asked Questions sections to readme.txt
* Add screenshot of widget admin
* Changed Description in readme.txt
* Note compatibility through WP 3.1+
* Update copyright date (2011)

## 1.2
* Use `get_the_author_meta('display_name')` instead of deprecated `get_author_name()`
* Add filter `linkify_authors` to respond to the function of the same name so that users can use the `do_action()` notation for invoking template tags
* Wrap function in `if(!function_exists())` check
* Reverse order of `implode()` arguments
* Fix to prevent PHP notice
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Note compatibility with WP 3.0+
* Minor tweaks to code formatting (spacing)
* Add Filters and Upgrade Notice sections to readme.txt
* Remove trailing whitespace

## 1.1
* Add PHPDoc documentation
* Use `esc_attr()` instead of the deprecated `attribute_escape()`
* Minor formatting tweaks
* Note compatibility with WP 2.9+
* Drop compatibility with WP older than 2.8
* Update copyright date
* Update readme.txt (including adding Changelog)

## 1.0
* Initial release
