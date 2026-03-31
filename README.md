# Hero Random Images

Hero Random Images is a WordPress plugin that adds a Gutenberg hero block powered by an ACF gallery field.

The block selects one random image from the gallery and overlays editable heading, text, and button content using InnerBlocks.

## Features

- Displays a random hero image from an ACF gallery field
- Supports heading, paragraph, and button content in the block editor
- Uses WordPress responsive image functions for `srcset` and attachment metadata
- Includes accessibility improvements for hero labeling and image handling
- Fails gracefully when ACF is missing or the gallery field is misconfigured

## Requirements

- WordPress 6.3 or newer
- PHP 7.4 or newer
- Advanced Custom Fields Pro

## Installation

1. Copy this plugin into `wp-content/plugins/hero-random`.
2. Activate the plugin in WordPress.
3. Make sure Advanced Custom Fields Pro is installed and active.
4. Create or assign an ACF gallery field for the block.
5. The plugin currently checks `hero_random` first and then `gallery`.
6. Add the `Hero (Random ACF Image)` block in the editor.

## Files

- `hero-random.php` plugin bootstrap and block registration
- `inc/hero-random-block.php` block render template
- `inc/hero-random.css` shared front-end and editor styling
- `readme.txt` WordPress-style plugin readme

## Notes

- This plugin is distributed through GitHub and is not currently intended for the WordPress.org plugin directory.
- The block depends on ACF block registration and ACF gallery field data.

## License

GPL-2.0-or-later. See [GNU GPL v2.0](https://www.gnu.org/licenses/gpl-2.0.html).
