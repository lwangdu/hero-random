=== Hero Random Images ===
Contributors: Lobsang Wangdu
Tags: block, gutenberg, hero, image, acf
Requires at least: 6.3
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display a random image from an ACF gallery as a responsive, accessible hero block.

== Description ==

Hero Random Images adds a Gutenberg block that displays one random image from an ACF gallery field and overlays editable heading, text, and buttons.

Features:

* Randomly selects an image from an ACF gallery field.
* Supports editable heading, paragraph, and button content via InnerBlocks.
* Uses responsive image markup from WordPress attachment data.
* Includes accessibility improvements for hero labeling and image output.

This plugin requires Advanced Custom Fields Pro because it uses ACF block registration and ACF gallery fields.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`, or install it through WordPress.
2. Activate the plugin.
3. Make sure Advanced Custom Fields Pro is installed and activated.
4. Create or assign an ACF gallery field for the block. The plugin currently looks for `hero_random` first, then `gallery`.
5. Add the `Hero (Random ACF Image)` block in the block editor.

== Frequently Asked Questions ==

= Does this plugin work without ACF Pro? =

No. It depends on Advanced Custom Fields Pro for block registration and gallery field data.

= Which field names does it use? =

The block currently checks `hero_random` first and then `gallery`.

= Does it store visitor data? =

No. This plugin does not track users, store personal data, or send data to external services.

== Screenshots ==

1. The Hero Random block in the editor.
2. The hero block rendered on the front end.

== Changelog ==

= 1.2.5 =

* Improved block accessibility and heading labeling behavior.
* Simplified stylesheet loading so editor and front end stay aligned.
* Added release metadata and readme content for public distribution.
