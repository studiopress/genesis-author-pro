<?php
/*

Plugin Name: Osom Author Pro
Plugin URI: https://wordpress.org/plugins/genesis-author-pro/
Description: Adds a Book CPT and fields to display theme beatifully.
Version: 2.0
Author: OsomPress
Author URI: https://www.osompress.com/
Text Domain: genesis-author-pro
Domain Path /languages/

 */

if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

/**
 * Action on the plugins_loaded hook.
 * Invokes the load_plugin_textdomain() function to support i18 translation strings.
 *
 * @access public
 * @static
 * @return void
 */
function genesis_author_pro_text_domain() {
	load_plugin_textdomain( 'genesis-author-pro', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'genesis_author_pro_text_domain' );

/**
 * Registered autoload function.
 * Used to load class files automatically if they are in the provided array.
 *
 * @access public
 * @param string $class
 * @return void
 */
function gapro_autoloader( $class ) {

	$classes = array(
		'Genesis_Author_Pro'               => 'class.Genesis_Author_Pro.php',
		'Genesis_Author_Pro_Activation'    => 'class.Genesis_Author_Pro_Activation.php',
		'Genesis_Author_Pro_Book_Meta'     => 'class.Genesis_Author_Pro_Book_Meta.php',
		'Genesis_Author_Pro_CPT'           => 'class.Genesis_Author_Pro_CPT.php',
		'Genesis_Author_Pro_Save'          => 'class.Genesis_Author_Pro_Save.php',
		'Genesis_Author_Pro_Template'      => 'class.Genesis_Author_Pro_Template.php',
		'Genesis_Author_Pro_Widget'        => 'class.Genesis_Author_Pro_Widget.php',
		'Genesis_Author_Pro_Widget_Admin'  => 'class.Genesis_Author_Pro_Widget_Admin.php',
		'Genesis_Author_Pro_Widget_Output' => 'class.Genesis_Author_Pro_Widget_Output.php',
	);

	if ( isset( $classes[$class] ) ) {
		require_once GENESIS_AUTHOR_PRO_CLASSES_DIR . $classes[$class];
	}

}
spl_autoload_register( 'gapro_autoloader' );

register_activation_hook( __FILE__, array( 'Genesis_Author_Pro_Activation', 'activate' ) );

define( 'GENESIS_AUTHOR_PRO_CLASSES_DIR', dirname( __FILE__ ) . '/classes/' );
define( 'GENESIS_AUTHOR_PRO_FUNCTIONS_DIR', dirname( __FILE__ ) . '/functions/' );
define( 'GENESIS_AUTHOR_PRO_TEMPLATES_DIR', dirname( __FILE__ ) . '/templates/' );
define( 'GENESIS_AUTHOR_PRO_RESOURCES_URL', plugin_dir_url( __FILE__ ) . 'resources/' );

add_action( 'init', array( 'Genesis_Author_Pro_CPT', 'init' ), 1 );

add_action( 'init', 'author_pro_init' );
/**
 * Action added on the init hook.
 * @access public
 * @return void
 */
function author_pro_init() {

	add_action( 'load-post.php', array( 'Genesis_Author_Pro', 'maybe_do_book_meta' ) );
	add_action( 'load-post-new.php', array( 'Genesis_Author_Pro', 'maybe_do_book_meta' ) );
	add_action( 'load-edit-tags.php', array( 'Genesis_Author_Pro', 'maybe_enqueue_scripts' ) );
	add_filter( 'bulk_post_updated_messages', array( 'Genesis_Author_Pro', 'bulk_updated_messages' ), 10, 2 );
	add_action( 'save_post', array( 'Genesis_Author_Pro', 'maybe_do_save' ), 10, 2 );

}

add_action( 'genesis_init', 'genesis_author_pro_init' );
/**
 * Action added on the genesis_init hook.
 * All actions except the init and activate hook are loaded through this function.
 * This ensures that Genesis is available for any Genesis functions that will be used.
 *
 * @access public
 * @return void
 */
function genesis_author_pro_init() {

	$archive_page_hook = sprintf( 'load-%1$s_page_genesis-cpt-archive-%1$s', 'books' );
	add_action( 'init', array( 'Genesis_Author_Pro_CPT', 'maybe_remove_genesis_sidebar_form' ), 11 );
	add_action( 'admin_init', array( 'Genesis_Author_Pro_CPT', 'remove_genesis_layout_options' ), 11 );
	add_filter( 'template_include', array( 'Genesis_Author_Pro_Template', 'maybe_include_template' ) );
	add_action( $archive_page_hook, array( 'Genesis_Author_Pro', 'maybe_enqueue_scripts' ) );
	add_action( 'widgets_init', array( 'Genesis_Author_Pro', 'widgets_init' ) );

}

// Add image size
add_action( 'after_setup_theme', array( 'Genesis_Author_Pro_CPT', 'maybe_add_image_size' ) );

// Register block bindings for Author Pro metadata
add_action( 'init', 'osom_author_pro_register_block_bindings' );
function osom_author_pro_register_block_bindings() {
	$bindings = array(
		'osom-author-pro/book-featured-text'    => array(
			'label' => __( 'Featured Text', 'genesis-author-pro' ),
			'key'   => 'featured_text',
		),
		'osom-author-pro/book-price'            => array(
			'label' => __( 'Book Price', 'genesis-author-pro' ),
			'key'   => 'price',
		),
		'osom-author-pro/book-isbn'             => array(
			'label' => __( 'Book ISBN', 'genesis-author-pro' ),
			'key'   => 'isbn',
		),
		'osom-author-pro/book-publisher'        => array(
			'label' => __( 'Book Publisher', 'genesis-author-pro' ),
			'key'   => 'publisher',
		),
		'osom-author-pro/book-editor'           => array(
			'label' => __( 'Book Editor', 'genesis-author-pro' ),
			'key'   => 'editor',
		),
		'osom-author-pro/book-edition'          => array(
			'label' => __( 'Book Edition', 'genesis-author-pro' ),
			'key'   => 'edition',
		),
		'osom-author-pro/book-publication-date' => array(
			'label' => __( 'Book Publication Date', 'genesis-author-pro' ),
			'key'   => 'publication_date',
		),
		'osom-author-pro/book-available-in'     => array(
			'label' => __( 'Book Available In', 'genesis-author-pro' ),
			'key'   => 'available',
		),
		'osom-author-pro/book-button-1-uri'     => array(
			'label' => __( 'Book Button 1', 'genesis-author-pro' ),
			'key'   => 'button_1_uri',
		),
		'osom-author-pro/book-button-2-uri'     => array(
			'label' => __( 'Book Button 2', 'genesis-author-pro' ),
			'key'   => 'button_2_uri',
		),
		'osom-author-pro/book-button-3-uri'     => array(
			'label' => __( 'Book Button 3', 'genesis-author-pro' ),
			'key'   => 'button_3_uri',
		),
	);

	foreach ( $bindings as $block_type => $binding ) {
		register_block_bindings_source( $block_type, array(
			'label'              => $binding['label'],
			'get_value_callback' => function ( $source_args ) use ( $binding ) {
				return osom_author_pro_get_metadata_value( $binding['key'] );
			},
		) );
	}
}

// Retrieve metadata value based on key
function osom_author_pro_get_metadata_value( $key ) {
	// Get the current post ID
	$post_id = get_the_ID();

	// Get the _genesis_author_pro meta value
	$meta_value = get_post_meta( $post_id, '_genesis_author_pro', true );

	// Extract the value based on key
	$value = $meta_value[$key] ?? __( 'Value not set', 'genesis-author-pro' );

	if ( $key == 'publication_date' ) {
		$value = date_i18n( get_option( 'date_format' ), $value );
	}

	return esc_html( $value );
}

// Register block variations for Author Pro
add_filter( 'get_block_type_variations', 'osom_author_pro_block_type_variations', 10, 2 );
function osom_author_pro_block_type_variations( $variations, $block_type ) {

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-featured-text',
			'title'      => 'Featured Text',
			'icon'       => 'book-alt',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'featured' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-featured-text',
							'args'   => array(
								'key' => 'featured_text',
							),
						),
					),
					'name'     => 'Featured Text',
				),
				'placeholder' => __( 'Featured text' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-price',
			'title'      => 'Book Price',
			'icon'       => 'money-alt',
			'category'   => 'author-pro',
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-price',
							'args'   => array(
								'key' => 'price',
							),
						),
					),
					'name'     => 'Price',
				),
				'placeholder' => __( 'Book price' ),
			),
			'keywords'   => array( 'book', 'author', 'price' ),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-isbn',
			'title'      => 'ISBN',
			'icon'       => 'book-alt',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'isbn' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-isbn',
							'args'   => array(
								'key' => 'isbn',
							),
						),
					),
					'name'     => 'ISBN',
				),
				'placeholder' => __( 'ISBN' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-publisher',
			'title'      => 'Book Publisher',
			'icon'       => 'building',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'publisher' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-publisher',
							'args'   => array(
								'key' => 'publisher',
							),
						),
					),
					'name'     => 'Publisher',
				),
				'placeholder' => __( 'Book publisher' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-editor',
			'title'      => 'Book Editor',
			'icon'       => 'edit',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'editor' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-editor',
							'args'   => array(
								'key' => 'editor',
							),
						),
					),
					'name'     => 'Editor',
				),
				'placeholder' => __( 'Book editor' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-edition',
			'title'      => 'Book Edition',
			'icon'       => 'book',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'edition' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-edition',
							'args'   => array(
								'key' => 'edition',
							),
						),
					),
					'name'     => 'Edition',
				),
				'placeholder' => __( 'Book edition.' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-publication-date',
			'title'      => 'Book Publication Date',
			'icon'       => 'calendar-alt',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'publication date', 'date' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-publication-date',
							'args'   => array(
								'key' => 'publication_date',
							),
						),
					),
					'name'     => 'Publication Date',
				),
				'placeholder' => __( 'Publication date' ),
			),
		);
	}

	if ( 'core/paragraph' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-available-in',
			'title'      => 'Book Available In',
			'icon'       => 'admin-site-alt3',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'available' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'content' => array(
							'source' => 'osom-author-pro/book-available-in',
							'args'   => array(
								'key' => 'available',
							),
						),
					),
					'name'     => 'Available In',
				),
				'placeholder' => __( 'Available formats' ),
			),
		);
	}

	if ( 'core/button' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-button-1',
			'title'      => 'Book Button 1',
			'icon'       => 'book',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'button' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'url'  => array(
							'source' => 'osom-author-pro/book-button-1-uri',
							'args'   => array(
								'key' => 'button_1_uri',
							),
						),
					'name'     => 'Button 1',
				),
				'placeholder' => __( 'Button 1' ),
			),
		);
	}

	if ( 'core/button' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-button-2',
			'title'      => 'Book Button 2',
			'icon'       => 'book',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'button' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'url' => array(
							'source' => 'osom-author-pro/book-button-2-uri',
							'args'   => array(
								'key' => 'button_2_uri',
							),
						),
					),
					'name'     => 'Button 2',
				),
				'placeholder' => __( 'Button 2' ),
			),
		);
	}

	if ( 'core/button' === $block_type->name ) {
		$variations[] = array(
			'name'       => 'book-button-3',
			'title'      => 'Book Button 3',
			'icon'       => 'book',
			'category'   => 'author-pro',
			'keywords'   => array( 'book', 'author', 'button' ),
			'attributes' => array(
				'metadata'    => array(
					'bindings' => array(
						'url' => array(
							'source' => 'osom-author-pro/book-button-3-uri',
							'args'   => array(
								'key' => 'button_3_uri',
							),
						),
					),
					'name'     => 'Button 3',
				),
				'placeholder' => __( 'Button 3' ),
			),
		);
	}

	return $variations;
}

// Adding Osom Author Pro block category
add_filter( 'block_categories_all', 'osom_author_pro_block_category', 10, 2 );
function osom_author_pro_block_category( $categories, $post ) {

	array_unshift( $categories, array(
		'slug'  => 'author-pro',
		'title' => 'Author Pro',
		'icon'  => 'book',
	) );

	return $categories;
}

// Register block patterns
add_action( 'init', 'osom_author_pro_register_block_patterns' );
function osom_author_pro_register_block_patterns() {
	register_block_pattern(
		'osom-author-pro/two-columns-book-template',
		array(
			'title'       => __( 'Two columm book template', 'genesis-author-pro' ),
			'description' => __( 'Displays book featured image on one column and the book info as list on the other', 'genesis-author-pro' ),
			'content'     => '<!-- wp:columns -->
			<div class="wp-block-columns"><!-- wp:column -->
			<div class="wp-block-column"><!-- wp:post-featured-image {"aspectRatio":"auto","height":"400px"} /--></div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"0px"}}} -->
			<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"align":"center","placeholder":"Featured text","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-featured-text","args":{"key":"featured_text"}}},"name":"Featured Text"},"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"},"margin":{"top":"0","bottom":"0"}},"typography":{"letterSpacing":"1.5px","textTransform":"capitalize","fontStyle":"normal","fontWeight":"700"}},"backgroundColor":"accent","textColor":"contrast","fontSize":"small"} -->
			<p class="has-text-align-center has-contrast-color has-accent-background-color has-text-color has-background has-link-color has-small-font-size" style="margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;font-style:normal;font-weight:700;letter-spacing:1.5px;text-transform:capitalize"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-terms {"term":"book-authors","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}},"typography":{"fontStyle":"normal","fontWeight":"700"}},"className":"is-style-default","fontSize":"medium"} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group"><!-- wp:paragraph -->
			<p><strong>Price</strong>:</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Book price","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-price","args":{"key":"price"}}},"name":"Price"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>Edition</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Book edition.","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-edition","args":{"key":"edition"}}},"name":"Edition"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>ISBN</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"ISBN","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-isbn","args":{"key":"isbn"}}},"name":"ISBN"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>Editor</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Book editor","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-editor","args":{"key":"editor"}}},"name":"Editor"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>Publisher</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Book publisher","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-publisher","args":{"key":"publisher"}}},"name":"Publisher"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>Publication date</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Publication date","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-publication-date","args":{"key":"publication_date"}}},"name":"Publication Date"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph -->
			<p><strong>Available in</strong>: </p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"placeholder":"Available formats","metadata":{"bindings":{"content":{"source":"osom-author-pro/book-available-in","args":{"key":"available"}}},"name":"Available In"}} -->
			<p></p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group --></div>
			<!-- /wp:column --></div>
			<!-- /wp:columns -->',
			'categories'  => array( 'Osom Author Pro' ),
			'keywords'    => array( 'author', 'book', 'osom' ),
		)
	);
	// Register pattern category
	register_block_pattern_category(
		'Osom Author Pro',
		array( 'label' => __( 'Osom Author Pro', 'genesis-author-pro' ) )
	);
}

function is_genesis_theme_2() {
	return ( wp_get_theme()->parent() && 'Genesis' === wp_get_theme()->parent()->get( 'Name' ) );
}

// Hide book button text field on non Genesis themes
add_action( 'admin_head', 'osom_custom_admin_css' );
function osom_custom_admin_css() {
	global $pagenow;
	if ( !is_genesis_theme_2() ) {
		echo '
        <style>
        #genesis_author_pro_book_meta tr:nth-child(2) {
            display: none;
        }
        </style>';
	}
}
