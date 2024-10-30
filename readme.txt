=== Bug Links ===
Contributors: jdub
Tags: bugzilla, bugs, link, filter
Requires at least: 2.1
Tested up to: 2.3
Stable tag: 1.0

Filters commonly used references to bug numbers into active links, with (currently hardcoded) support for numerous Free Software projects.

== Description ==

Bug Links will filter commonly used references to bug numbers into active links to those bugs. It supports numerous Free Software projects, and adding support for more, despite being a code change at the moment, is very straightforward.

It will filter bug references such as:

* wordpress bug #55555
* wordpress bug 55555
* wordpress #55555

With or without space before the #.

If you would like your project supported in Bug Links, please send me the base bug URL for your tracker and a 16x16 icon (PNG preferred) to represent it.

== Installation ==

WordPress or WordPress MU:

1. Unpack the plugin into `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.

WordPress MU for all users:

1. Unpack the plugin into `/wp-content/mu-plugins/`.
2. Make a symlink to `bug-links.php` in the `mu-plugins` directory.
