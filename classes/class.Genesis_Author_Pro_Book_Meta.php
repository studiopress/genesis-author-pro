<?php

/**
 * Genesis_Author_Pro_Book_Meta class.
 */
class Genesis_Author_Pro_Book_Meta {

	/**
	 * The post object of the current book
	 *
	 * @var object
	 * @access public
	 */
	var $post;

	/**
	 * The post ID of the current book
	 *
	 * @var string
	 * @access public
	 */
	var $post_ID;

	/**
	 * The saved book meta values for the current book
	 *
	 * @var array
	 * @access private
	 */
	private $_meta_value;

	/**
	 * Fields to be output in the book editor custom meta fields.
	 *
	 * @var array
	 * @access private
	 */
	private $_fields;

	/**
	 * Prefix used for building the book meta name and IDs
	 *
	 * (default value: '_genesis_author_pro')
	 *
	 * @var string
	 * @access private
	 */
	private $_prefix = '_genesis_author_pro';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct(){

		add_action( 'admin_enqueue_scripts' , array( $this, 'enqueue_scripts'  )    );
		add_action( 'add_meta_boxes'        , array( $this, 'add_meta_boxes'   ), 1 );
		add_filter( 'post_updated_messages' , array( $this, 'updated_messages' )    );

	}

	/**
	 * Enqueues the script and css files for the books.
	 *
	 * @uses wp_enqueue_script
	 * @uses wp_enqueue_style
	 * @access public
	 * @static
	 * @return void
	 */
	static public function enqueue_scripts() {

		wp_enqueue_style( 'genesis_author_pro_admin_styles' , GENESIS_AUTHOR_PRO_RESOURCES_URL . 'css/admin.css', array(), 0.1 );

	}

	/**
	 * Action on the add_meta_boxes hook.
	 * Adds the book details metabox
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static public function add_meta_boxes(){

		global $Genesis_Author_Pro_CPT, $Genesis_Author_Pro_Book_Meta;

		add_meta_box(
			'genesis_author_pro_book_meta',
			__( 'Book Details', 'genesis-author-pro' ),
			array( $Genesis_Author_Pro_Book_Meta, 'meta_box' ),
			$Genesis_Author_Pro_CPT->post_type,
			'normal',
			'high'
		);

	}

	/**
	 * Callback function for the book details meta box.
	 * Builds the book details meta box output.
	 *
	 * @access public
	 * @static
	 * @param object $post
	 * @return void
	 */
	static public function meta_box( $post ){

		global $Genesis_Author_Pro_Book_Meta;

		// Add an nonce field so we can check for it later.
		wp_nonce_field( GENESIS_AUTHOR_PRO_CLASSES_DIR, '_genesis_author_pro_nonce' );

		echo '<table class="form-table"><tbody>';

		$Genesis_Author_Pro_Book_Meta->set_post( $post );
		$Genesis_Author_Pro_Book_Meta->set_meta_value();
		$Genesis_Author_Pro_Book_Meta->set_fields();
		$Genesis_Author_Pro_Book_Meta->meta_rows();

		echo '</tbody></table>';

	}

	/**
	 * Sets the post object and post ID class variables.
	 *
	 * @access public
	 * @param object $post
	 * @return void
	 */
	public function set_post( $post ){

		$this->post    = $post;
		$this->post_id = $post->ID;

	}

	/**
	 * Gets the saved meta value and sets the private $_meta_value object variable.
	 *
	 * @access public
	 * @return void
	 */
	public function set_meta_value(){

		$this->_meta_value = get_post_meta( $this->post_id, $this->_prefix, true );

	}

	/**
	 * Builds table row output for indicated fields.
	 * Uses the $_fields object variable if no $fields value is provided
	 * optionally echoes the output.
	 *
	 * @access public
	 * @param array $fields (default: array())
	 * @param bool $echo (default: true)
	 * @return string
	 */
	public function meta_rows( $fields = array(), $echo = true ){

		$fields = $fields ? $fields : $this->_fields;

		$rows = '';

		foreach( $fields as $field ){

			$description = empty( $field['description'] ) ? '' : sprintf( '<p class="description">%s</p>', $field['description'] );

			$rows .= sprintf(
				'<tr valign="top"><th scope="row"><label for="%s">%s</label></th><td>%s%s</td></tr>',
				$this->_get_field_id( $field['name'] ),
				$field['label'],
				$this->_get_field( $field ),
				$description
			);

		}

		if( $echo ){
			echo $rows;
		}

		return $rows;

	}

	/**
	 * Sets the $_fields object variable.
	 * The fields are used to build the book details rows/inputs
	 *
	 * @access public
	 * @return void
	 */
	public function set_fields(){

		$this->_fields = array(
			array(
				'name'        => 'featured_text',
				'label'       => __( 'Featured Text', 'genesis-author-pro' ),
				'description' => __( 'This will be displayed as a banner along with the featured image. For best display, the text should be one word or a short two-word phrase.', 'genesis-author-pro' ),
				'type'        => 'text',
			),
			array(
				'name'        => 'price',
				'label'       => __( 'Price', 'genesis-author-pro' ),
				'description' => '',
				'type'        => 'text',
			),
			array(
				'name'        => 'isbn',
				'label'       => __( 'ISBN', 'genesis-author-pro' ),
				'description' => __( 'Will display International Standard Book Number for this book if provided.', 'genesis-author-pro' ),
				'type'        => 'text',
			),
			array(
				'name'        => 'publisher',
				'label'       => __( 'Publisher', 'genesis-author-pro' ),
				'description' => '',
				'type'        => 'text',
			),
			array(
				'name'        => 'editor',
				'label'       => __( 'Editor', 'genesis-author-pro' ),
				'description' => '',
				'type'        => 'text',
			),
			array(
				'name'        => 'edition',
				'label'       => __( 'Edition', 'genesis-author-pro' ),
				'description' => __( 'Edition or version of the book', 'genesis-author-pro' ),
				'type'        => 'text',
			),
			array(
				'name'        => 'publication_date',
				'label'       => __( 'Publication Date', 'genesis-author-pro' ),
				'description' => __( 'Most common date formats will be automatically converted to machine readable format on save. The date will be displayed using the date format in the general settings.', 'genesis-author-pro' ),
				'type'        => 'text',
			),
			array(
				'name'        => 'available',
				'label'       => __( 'Available in', 'genesis-author-pro' ),
				'description' => __( 'A list of formats the book is available in. Example: Hardback, Paperback, PDF, and Kindle', 'genesis-author-pro' ),
				'type'        => 'text',
			),
			array(
				'name'        => 'button_1',
				'label'       => __( 'Button 1', 'genesis-author-pro' ),
				'description' => __( 'In Genesis Child Themes this will create a button on the book page that can be used as a link for purchase, download, etc.<br>In other themes, this will be the URL of the "Book Bottom #" block.', 'genesis-author-pro' ),
				'type'        => 'button',
			),
			array(
				'name'        => 'button_2',
				'label'       => __( 'Button 2', 'genesis-author-pro' ),
				'description' => __( 'In Genesis Child Themes this will create a button on the book page that can be used as a link for purchase, download, etc.<br>In other themes, this will be the URL of the "Book Bottom #" block.', 'genesis-author-pro' ),
				'type'        => 'button',
			),
			array(
				'name'        => 'button_3',
				'label'       => __( 'Button 3', 'genesis-author-pro' ),
				'description' => __( 'In Genesis Child Themes this will create a button on the book page that can be used as a link for purchase, download, etc.<br>In other themes, this will be the URL of the "Book Bottom #" block.', 'genesis-author-pro' ),
				'type'        => 'button',
			),
		);

	}

	/**
	 * Gets the individual field output.
	 * Uses a switch to check the type and return the appropriately formated output.
	 *
	 * @access private
	 * @param array $field
	 * @return string
	 */
	private function _get_field( $field ){

		switch( $field['type'] ){

		case 'button' :
			return $this->_get_button_field( $field );
			break;

		case 'text':
			return $this->_get_text_field( $field );
			break;

		}

	}

	/**
	 * Builds the button field output.
	 * Calls the object meta_rows() method to build the text field output.
	 *
	 * @access private
	 * @param array $field
	 * @return sring
	 */
	private function _get_button_field( $field ){

		$rows = array(
			array(
				'name'        => sprintf( '%s_uri', $field['name'] ),
				'label'       => sprintf( __( '%s URI', 'genesis-author-pro' ), $field['label'] ),
				'type'        => 'text',
			),
			array(
				'name'        => sprintf( '%s_text', $field['name'] ),
				'label'       => sprintf( __( '%s Text', 'genesis-author-pro' ), $field['label'] ),
				'type'        => 'text',
			),
		);

		return sprintf( '<table class="form-table"><tbody>%s</tbody></table>', $this->meta_rows( $rows, false ) );

	}

	/**
	 * Builds basic text field output.
	 *
	 * @access private
	 * @param array $field
	 * @return string
	 */
	private function _get_text_field( $field ){

		$name  = $this->_get_field_name(  $field['name'] );
		$id    = $this->_get_field_id(    $field['name'] );
		$value = $this->_get_field_value( $field['name'] );

		return sprintf( '<input type="text" class="widefat" name="%s" id="%s" value="%s" />', $name, $id, $value );

	}

	/**
	 * Builds the field name attribute using the object $_prefix variable.
	 *
	 * @access private
	 * @param string $name
	 * @return string
	 */
	private function _get_field_name( $name ){

		return sprintf( '%s[%s]', $this->_prefix, $name );

	}

	/**
	 * Builds the field ID attribute using the object $_prefix variable.
	 *
	 * @access private
	 * @param string $name
	 * @return string
	 */
	private function _get_field_id( $name ){

		return sprintf( '%s_%s', $this->_prefix, $name );

	}

	/**
	 * Gets the current value for the field.
	 *
	 * @access private
	 * @param string $name
	 * @return string
	 */
	private function _get_field_value( $name ){

		$value = empty( $this->_meta_value[$name] ) ? '' : $this->_meta_value[$name];

		if( 'publication_date' === $name && $value ){
			$value = date_i18n( get_option( 'date_format' ), $value );
		}

		return $value;

	}

	/**
	 * Creates custom updated messages for books.
	 *
	 * @access public
	 * @static
	 * @param mixed $messages
	 * @return void
	 */
	static public function updated_messages ( $messages ) {

		global $post, $post_ID, $Genesis_Author_Pro_CPT;

		$messages[$Genesis_Author_Pro_CPT->post_type] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Book updated.' , 'genesis-author-pro' ),
			2  => __( 'Custom field updated.' , 'genesis-author-pro' ),
			3  => __( 'Custom field deleted.' , 'genesis-author-pro' ),
			4  => __( 'Book updated.' , 'genesis-author-pro' ),
			/* translators: %s: date and time of the revision */
			5  => isset($_GET['revision']) ? sprintf( __( 'Book restored to revision from %s.' , 'genesis-author-pro' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Book published.', 'genesis-author-pro' ),
			7  => __( 'Book saved.'    , 'genesis-author-pro' ),
			8  => __( 'Book submitted.', 'genesis-author-pro' ),
			9  => sprintf( __( 'Book scheduled for: %1$s.', 'genesis-author-pro' ), '<strong>' . date_i18n( get_option('date_format') . ' ' . get_option('time_format'), strtotime( $post->post_date ) ) . '</strong>' ),
			10 => __( 'Book draft updated.' , 'genesis-author-pro' ),
		);

		return $messages;

	}

}
