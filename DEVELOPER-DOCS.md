# Developer Documentation

This plugin provides a [hook](#hook) and a [template tag](#template-tag).

## Template Tag

The plugin provides one template tag for use in your theme templates, functions.php, or plugins.

### Functions

* `<?php c2c_linkify_authors( $authors, $before = '', $after = '', $between = ', ', $before_last = '', $none = '' ) ?>`
Displays links to each of any number of authors specified via author IDs/slugs

### Arguments

* `$authors` _(string|int|array)_
A single author ID/slug, or multiple author IDs/slugs defined via an array, or multiple author IDs/slugs defined via a comma-separated and/or space-separated string

* `$before` _(string)_
Optional. Text to appear before the entire author listing (if authors exist or if 'none' setting is specified). Default is an empty string.

* `$after` _(string)_
Optional. Text to appear after the entire author listing (if authors exist or if 'none' setting is specified). Default is an empty string.

* `$between` _(string)_
Optional. Text to appear between authors. Default is ", ".

* `$before_last` _(string)_
Optional. Text to appear between the second-to-last and last element, if not specified, 'between' value is used. Default is an empty string.

* `$none` _(string)_
Optional. Text to appear when no authors have been found. If blank, then the entire function doesn't display anything. Default is an empty string.

### Examples

These are all valid calls:

```php
<?php c2c_linkify_authors(3); ?>
<?php c2c_linkify_authors("3"); ?>
<?php c2c_linkify_authors("scott"); ?>
<?php c2c_linkify_authors("3 9 10"); ?>
<?php c2c_linkify_authors("scott bill alice"); ?>
<?php c2c_linkify_authors("scott 9 alice"); ?>
<?php c2c_linkify_authors("3,9,10"); ?>
<?php c2c_linkify_authors("scott,bill,alice"); ?>
<?php c2c_linkify_authors("scott,92,alice"); ?>
<?php c2c_linkify_authors("3, 9, 10"); ?>
<?php c2c_linkify_authors("scott, bill, alice"); ?>
<?php c2c_linkify_authors("scott, 92, alice"); ?>
<?php c2c_linkify_authors(array(43,92,102)); ?>
<?php c2c_linkify_authors(array("43","92","102")); ?>
<?php c2c_linkify_authors(array("scott","bill","alice")); ?>
<?php c2c_linkify_authors(array("scott",92,"alice")); ?>
```

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


## Hook

The plugin exposes one action for hooking.

### `c2c_linkify_authors` _(action)_

The `c2c_linkify_authors` hook allows you to use an alternative approach to safely invoke `c2c_linkify_authors()` in such a way that if the plugin were to be deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `c2c_linkify_authors()`

#### Example:

Instead of:

`<?php c2c_linkify_authors( '19, 28', 'Authors: ' ); ?>`

Do:

`<?php do_action( 'c2c_linkify_authors', '19, 28', 'Authors: ' ); ?>`
