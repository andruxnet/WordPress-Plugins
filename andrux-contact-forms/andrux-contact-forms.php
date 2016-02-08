<?php
/*
Plugin Name: Contact Forms by andrux
Version: 1.0
Description: Create contact forms and customize output as you need.
Author: andruxnet
Author URI: http://andrux.net
License: GPLv2
Tags: contact, forms
*/

/* let's define some constants */
define( '__CF_SECTIONS', '__cf-sections' );
define( '__CF_FIELDS', '__cf-fields' );
define( '__CF_EXTRA', '__cf-extra' );
define( '__CF_EMAIL', '__cf-email' );
define( '__CF_ACTION', '__cf-action' );
define( '__CF_NONCE', '__cf-nonce' );
define( '__CF_EMAIL_SETTINGS', '__cf-email-settings' );

/**
 * This class encapsulates the logic for the plugin
 *
 */
class andrux_Contact_Forms
{
	private $plugin_path;
	private $plugin_url;

	public function __construct() {
		/* help variables */
		$this->plugin_path = dirname( __FILE__ );
		$this->plugin_url = plugins_url() . '/' . basename( $this->plugin_path );

		/* action hooks */
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'pre_post_update', array( $this, 'save_postdata' ) );
		add_action( 'init', array( $this, 'register_custom_post_types' ) );
		add_action( 'init', array( $this, 'load_scripts' ) );
		add_action( 'phpmailer_init', array( $this, 'smtp_email' ) );
		add_action( 'manage___cf-contact-form_posts_custom_column', array( $this, 'contact_form_extra_column_content' ), 5, 2 );

		/* filter hooks */
		add_filter( 'get_sample_permalink_html', array( $this, 'remove_permalink_view' ), 10, 2 );
		add_filter( 'pre_get_shortlink', array( $this, 'remove_shortlink_button' ), 10, 2 );
		add_filter( 'manage___cf-contact-form_posts_columns', array( $this, 'contact_form_extra_column_head' ) );
		add_filter( 'manage_edit-__cf-contact-form_columns', array( $this, 'contact_form_list_columns' ) );
		add_filter( 'wp_mail_from', array( $this, 'set_mail_from' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'set_mail_from_name' ) );

		/* ajax hooks */
		//add_action( 'wp_ajax_save_custom_field', array( $this, 'save_custom_field' ) );

		/* shortcodes */
		require_once 'inc/shortcodes/contact-form.php';
	}

	/**
	 * show custom columns when listing contact forms
	 */
	public function contact_form_list_columns( $columns ) {
		//return array_merge($columns, $new_columns);
		return array(
			'title' => 'Form Title',
			'__cf-contact-form-shortcode' => 'Shortcode',
		);
	}

	/**
	 * remove the extra permalink field that displays after we enter the title of our contact form
	 */
	public function remove_permalink_view( $return, $post_id  ) {
		return '__cf-contact-form' === get_post_type( $post_id ) 
			? '<strong>Shortcode:</strong> [andrux-contact-form title="' . get_the_title( $post_id ) . '"]' 
			: $return;
	}

	/**
	 * remove the get shortlink button that displays after we enter the title of our contact form
	 */
	public function remove_shortlink_button(  $return, $post_id  ) {
		return '__cf-contact-form' === get_post_type( $post_id ) ? '' : $return;
	}

	/**
	 * register custom post types and whatever they need
	 */
	public function register_custom_post_types() {
		/* register custom posts */
		$custom_posts =  array(
			'Contact Form' => array(
				'prefix' => '__cf-',
				'icon' => 'dashicons-feedback',
			),
		);
		foreach ( $custom_posts as $custom_post => $post_data ) {
			$lower_post_name = strtolower( $custom_post );
			$custom_post_slug = $post_data['prefix'] . sanitize_title( $custom_post );

			$labels = array(
				'name'               => __( "{$custom_post}s" ),
				'singular_name'      => __( $custom_post ),
				'menu_name'          => __( "{$custom_post}s" ),
				'name_admin_bar'     => __( $custom_post ),
				'add_new'            => __( "Add New" ),
				'add_new_item'       => __( "Add New {$custom_post}" ),
				'new_item'           => __( "New {$custom_post}" ),
				'edit_item'          => __( "Edit {$custom_post}" ),
				'view_item'          => __( "View {$custom_post}" ),
				'all_items'          => __( "All {$custom_post}s" ),
				'search_items'       => __( "Search {$custom_post}s" ),
				'parent_item_colon'  => __( "Parent {$custom_post}s:" ),
				'not_found'          => __( "No {$lower_post_name}s found." ),
				'not_found_in_trash' => __( "No {$lower_post_name}s found in Trash." ),
			);

			register_post_type( $custom_post_slug,
				array(
					'labels'             => $labels,
					'menu_icon'			 => $post_data['icon'],
					'public'             => true,
					'publicly_queryable' => true,
					'show_ui'            => true,
					'show_in_menu'       => true,
					'query_var'          => true,
					'rewrite'            => array( 'slug' => $custom_post_slug ),
					'capability_type'    => 'post',
					'has_archive'        => false,
					'hierarchical'       => false,
					'show_in_nav_menus'	 => false,
					'menu_position'      => null,
					'supports'			 => array( 'title' ),
				)
			);
		}
	}

	/**
	 * hook our settings page in the admin menu and load the needed scripts
	 */
	public function admin_menu() {
		$email_settings_page = add_submenu_page( 'edit.php?post_type=__cf-contact-form', 'Email Settings', 'Email Settings', 'manage_options', '__cf-email-settings', array( $this, 'email_settings_page' ) );

		add_action( 'load-' . $email_settings_page, array( $this, 'load_scripts' ) );
	}

	/**
	 * show email settings page
	 */
	public function email_settings_page() {
		include_once 'email-settings.php';
	}

	/**
	 * load Javascript and CSS
	 */
	public function load_scripts() {
		wp_enqueue_style( 'andrux-contact-form', $this->plugin_url . '/inc/styles.css' );
		wp_enqueue_script( 'andrux-contact-form', $this->plugin_url . '/inc/scripts.js', array( 'jquery' ), '', true );
	}

	/**
	 * add a meta box for custom fields
	 */
	public function add_meta_boxes() {
		add_meta_box( 'contact-form-sections', 'Sections', array( $this, 'contact_form_sections_meta_box' ), '__cf-contact-form', 'normal' );
		add_meta_box( 'contact-form-fields', 'Fields', array( $this, 'contact_form_fields_meta_box' ), '__cf-contact-form', 'normal' );
		add_meta_box( 'contact-form-submit', 'On Submit', array( $this, 'contact_form_submit_meta_box' ), '__cf-contact-form', 'normal' );
		add_meta_box( 'contact-form-extra', 'Extra Settings', array( $this, 'contact_form_extra_settings_meta_box' ), '__cf-contact-form', 'side' );

		$this->load_scripts();
	}

	public function contact_form_sections_meta_box() {
		require_once 'inc/meta-boxes/contact-form-sections.php';
	}

	public function contact_form_fields_meta_box() {
		require_once 'inc/meta-boxes/contact-form-fields.php';
	}

	public function contact_form_extra_settings_meta_box() {
		require_once 'inc/meta-boxes/contact-form-extra.php';
	}

	public function contact_form_submit_meta_box() {
		require_once 'inc/meta-boxes/contact-form-submit.php';
	}

	/**
	 * save our data when updating the post
	 *
	 * @param int $post_id
	 */
	public function save_postdata( $post_id ) {
		/* if on autosave we don't want to process our data */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		/* verify this came from our screen and with proper authorization */
		if ( wp_verify_nonce( $_POST[ __CF_NONCE ], __CF_ACTION ) ) {
			update_post_meta( $post_id, __CF_SECTIONS, $_POST[ __CF_SECTIONS ] );
			update_post_meta( $post_id, __CF_FIELDS, $_POST[ __CF_FIELDS ] );
			update_post_meta( $post_id, __CF_EXTRA, $_POST[ __CF_EXTRA ] );
			update_post_meta( $post_id, __CF_EMAIL, $_POST[ __CF_EMAIL ] );
		}
	}

	/**
	 * save custom fields individually from the settings page
	 */
	public function save_custom_field() {
		$post_id = $_POST['post-id'];
		$field_value = $_POST['field-value'];

		/* add/update meta value */
		update_post_meta( $post_id, PAGEBOSS_CUSTOM_DESCRIPTION, $field_value );

		die();
	}

	/**
	 * add the custom description column to the posts listing screen
	 */
	public function contact_form_extra_column_head( $defaults ) {
		$defaults['__cf-contact-form-shortcode'] = 'Shortcode';

		return $defaults;
	}

	/**
	 * add the shortcode to be used for this contact form
	 */
	public function contact_form_extra_column_content( $column_name, $post_id ) {
		if ( $column_name == '__cf-contact-form-shortcode' ) {
			echo '[andrux-contact-form title="' . get_the_title( $post_id ) . '"]';
		}
	}

	public function set_mail_from( $from ) {
		$email_options = get_option( __CF_EMAIL_SETTINGS );

		return empty( $email_options['From']['value'] ) ? $from : $email_options['From']['value'];
	}

	public function set_mail_from_name( $from ) {
		$email_options = get_option( __CF_EMAIL_SETTINGS );

		return empty( $email_options['From Name']['value'] ) ? $from : $email_options['From Name']['value'];
	}


	/**
	 * set the content type to HTML
	 * 
	 * @return string the email content type
	 */
	public function set_html_content_type() {
		return 'text/html';
	}

	/**
	 * setup smtp mail if user wants to use that instead of regular mail
	 */
	public function smtp_email( $phpmailer ) {
		$smtp_options = get_option( __CF_EMAIL_SETTINGS );

		if ( $smtp_options['Method']['value'] == 'smtp' ) {
			$phpmailer->Mailer = $smtp_options['Method']['value'];
			$phpmailer->ContentType = $smtp_options['Content Type']['value'];
			$phpmailer->From = $smtp_options['From']['value'];
			$phpmailer->FromName = $smtp_options['From Name']['value'];
			$phpmailer->Sender = $phpmailer->From;
			$phpmailer->Host = $smtp_options['Host']['value'];
			$phpmailer->SMTPSecure = $smtp_options['Protocol']['value'];
			$phpmailer->Port = $smtp_options['Port']['value'];
			$phpmailer->SMTPAuth = $smtp_options['Authentication']['value'] == 1;
			$phpmailer->IsHTML = $phpmailer->ContentType == 'text/html';

			/* for attachments (disabled at the moment */
			/*if ( isset( $_POST['attachment'] ) ) {
				$phpmailer->AddAttachment( $_POST['attachment'] );
			}*/

			if ( $phpmailer->SMTPAuth ) {
				$phpmailer->Username = $smtp_options['Email Username']['value'];
				$phpmailer->Password = base64_decode( $smtp_options['Email Password']['value'] );
			}
		}
	}

	/**
	 * recursive version of wp_parse_args taken from https://gist.github.com/boonebgorges/5510970
	 */
	public static function parse_args_r( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$r = $b;
 
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = self::parse_args_r( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}
 
		return $r;
	}
}

/* initialize our object */
return new andrux_Contact_Forms();
