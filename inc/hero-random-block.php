<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Renders the Random Hero Images block.
 * Vars: $block, $content (InnerBlocks), $is_preview
 */

// 1) Wrapper attributes (add our class)
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => 'hero-random-wrapper'
]);

// 2) Pull images from ACF (gallery). Adjust field names if needed.
$field_keys = ['hero_random', 'gallery'];
$images     = null;

foreach ( $field_keys as $key ) {
    $maybe = get_field( $key );
    if ( $maybe ) { $images = $maybe; break; }
}

// Editor fallback if no images
if ( empty( $images ) || ! is_array( $images ) ) {
    echo '<div ' . $wrapper_attrs . '>';
    echo '<div class="hero-overlay"><div class="hero-content"><p><em>' . esc_html__( 'Add some images to your ACF gallery field to see the hero.', 'hero-random' ) . '</em></p></div></div>';
    echo '</div>';
    return;
}

// 3) Pick a random image and compute responsive attributes
$choice = $images[ array_rand( $images ) ];

if ( is_array( $choice ) && isset( $choice['ID'] ) ) {
    $img_id = (int) $choice['ID'];
} elseif ( is_numeric( $choice ) ) {
    $img_id = (int) $choice;
} elseif ( is_string( $choice ) && '' !== $choice ) {
    $img_id = (int) attachment_url_to_postid( $choice );
} else {
    $img_id = 0;
}

if ( ! $img_id ) {
    echo '<div ' . $wrapper_attrs . '>';
    echo '<div class="hero-overlay"><div class="hero-content"><p><em>' . esc_html__( 'The selected gallery image could not be resolved. Check the ACF gallery return format.', 'hero-random' ) . '</em></p></div></div>';
    echo '</div>';
    return;
}

if ( ! wp_attachment_is_image( $img_id ) ) {
    echo '<div ' . $wrapper_attrs . '>';
    echo '<div class="hero-overlay"><div class="hero-content"><p><em>' . esc_html__( 'The selected gallery item is not a valid image attachment.', 'hero-random' ) . '</em></p></div></div>';
    echo '</div>';
    return;
}

$alt  = trim( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) );
$alt  = $alt !== '' ? $alt : ''; // If decorative, keep empty alt
$meta = wp_get_attachment_metadata( $img_id );

$width  = isset( $meta['width'] )  ? (int) $meta['width']  : null;
$height = isset( $meta['height'] ) ? (int) $meta['height'] : null;

// Build src/srcset/sizes using core sizes; you can swap for custom image sizes as needed.
$src_full = wp_get_attachment_image_src( $img_id, 'full' );
$src_large = wp_get_attachment_image_src( $img_id, 'large' );
$src = $src_large ? $src_large[0] : ($src_full ? $src_full[0] : '');

if ( '' === $src ) {
    echo '<div ' . $wrapper_attrs . '>';
    echo '<div class="hero-overlay"><div class="hero-content"><p><em>' . esc_html__( 'The selected image could not be rendered. Check the attachment image sizes.', 'hero-random' ) . '</em></p></div></div>';
    echo '</div>';
    return;
}

$srcset = wp_get_attachment_image_srcset( $img_id, 'full' );
$sizes  = '(max-width: 600px) 100vw, (max-width: 1200px) 100vw, 100vw';

// Title/heading IDs for a11y
$section_id   = isset( $block['anchor'] ) ? $block['anchor'] : 'hero-' . $block['id'];
$title_id     = 'hero-title-' . $block['id'];
$title_ref_id = $title_id;
$content_with_id = $content;
$has_labeled_heading = false;

if ( ! empty( trim( $content ) ) ) {
    $content_with_id = preg_replace_callback(
        '/<h([1-6])\b([^>]*)>/i',
        static function ( $matches ) use ( $title_id, &$title_ref_id, &$has_labeled_heading ) {
            $has_labeled_heading = true;

            if ( preg_match( '/\sid\s*=\s*([\'"])(.*?)\1/i', $matches[2], $id_match ) ) {
                $title_ref_id = $id_match[2];
                return $matches[0];
            }

            return sprintf( '<h%s%s id="%s">', $matches[1], $matches[2], esc_attr( $title_id ) );
        },
        $content,
        1
    );
}

// 4) Decide fetch priority (hero is typically above-the-fold)
$attrs = [
    'class'         => 'hero-image',
    'alt'           => $alt,
    'loading'       => 'eager',
    'decoding'      => 'async',
    'fetchpriority' => 'high',
];

// Ensure dimensions to prevent layout shift when available
if ( $width && $height ) {
    $attrs['width'] = (string) $width;
    $attrs['height'] = (string) $height;
}

// 5) InnerBlocks template: heading + subheading + buttons
$template = [
    [
        'core/heading',
        [
            'level'       => 1,
            'placeholder' => __( 'Hero Title', 'hero-random' ),
        ],
    ],
    [
        'core/paragraph',
        [
            'placeholder' => __( 'Hero subheading text goes here', 'hero-random' ),
            'className'   => 'hero-subheading',
        ],
    ],
    [
        'core/buttons',
        [],
        [
            [
                'core/button',
                [
                    'placeholder' => __( 'Learn more', 'hero-random' ),
                ],
            ],
        ],
    ],
];

?>
<section id="<?php echo esc_attr( $section_id ); ?>"
         <?php echo $wrapper_attrs; ?>
         <?php if ( $has_labeled_heading ) : ?>
         aria-labelledby="<?php echo esc_attr( $title_ref_id ); ?>"
         <?php endif; ?>>

  <figure class="hero-media">
    <picture>
      <?php
      // Optional: if you have WebP variants via plugins, add <source> elements here.
      // Example (commented): <source type="image/webp" srcset="...">
      ?>
      <img
        src="<?php echo esc_url( $src ); ?>"
        srcset="<?php echo esc_attr( $srcset ); ?>"
        sizes="<?php echo esc_attr( $sizes ); ?>"
        <?php foreach ( $attrs as $k => $v ) { printf( '%s="%s" ', esc_attr( $k ), esc_attr( $v ) ); } ?>
        style="object-fit: cover;"
      />
    </picture>
  </figure>

    <div class="hero-overlay" aria-live="polite">
    <?php if ( $is_preview ) : ?>
      <div class="hero-content">
        <InnerBlocks
          template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>"
          allowedBlocks="<?php echo esc_attr( wp_json_encode( [
    'core/heading',
    'core/paragraph',
    'core/buttons',
] ) ); ?>" />
      </div>
    <?php else : ?>
      <?php if ( ! empty( trim( $content ) ) ) : ?>
        <div class="hero-content">
          <?php echo $content_with_id; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>

</section>
