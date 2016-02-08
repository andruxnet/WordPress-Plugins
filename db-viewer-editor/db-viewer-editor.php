<?php
/*
Plugin Name: DB Viewer & Editor
Version: 1.0
Description: Create database queries and display the results using shortcodes in posts and pages.
Author: andruxnet
Author URI: http://andrux.net
License: GPLv2
*/

/**
 * This class encapsulates the logic for the plugin
 *
 */
class DB_Viewer_Editor
{
	private $plugin_path;
	private $plugin_url;
	private $options;
	private $roles_template;

	public function __construct() {
		/* help variables */
		$this->plugin_path = dirname( __FILE__ );
		$this->plugin_url = plugins_url() . '/' . basename( $this->plugin_path );

		/* database options with their default values */
		$this->options = array(
			'db-query-queries' => array(),
			'db-query-roles' => array(),
		);

		$this->roles_template = array(
			'Administrator' => array( 'view' => 'yes', 'edit' => 'yes', 'remove' => 'yes' ),
			'Editor' => array( 'view' => 'no', 'edit' => 'no', 'remove' => 'no' ),
			'Author' => array( 'view' => 'no', 'edit' => 'no', 'remove' => 'no' ),
			'Contributor' => array( 'view' => 'no', 'edit' => 'no', 'remove' => 'no' ),
			'Subscriber' => array( 'view' => 'no', 'edit' => 'no', 'remove' => 'no' ),
		);

		/* make sure we have our options created/deleted on activation/deactivation */
		register_activation_hook( __FILE__, array( $this, 'on_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'on_deactivate' ) );

		/* action hooks */
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		/* ajax hooks */
		add_action( 'wp_ajax_settings_get_queries', array( $this, 'settings_get_queries' ) );
		add_action( 'wp_ajax_settings_add_query', array( $this, 'settings_add_query' ) );
		add_action( 'wp_ajax_settings_update_query', array( $this, 'settings_update_query' ) );
		add_action( 'wp_ajax_settings_remove_query', array( $this, 'settings_remove_query' ) );
		add_action( 'wp_ajax_settings_test_query', array( $this, 'settings_test_query' ) );
		add_action( 'wp_ajax_settings_get_roles', array( $this, 'settings_get_roles' ) );
		add_action( 'wp_ajax_settings_update_role', array( $this, 'settings_update_role' ) );
		add_action( 'wp_ajax_shortcode_update_record', array( $this, 'shortcode_update_record' ) );
		add_action( 'wp_ajax_nopriv_shortcode_update_record', array( $this, 'shortcode_update_record' ) );
		add_action( 'wp_ajax_shortcode_delete_record', array( $this, 'shortcode_delete_record' ) );
		add_action( 'wp_ajax_nopriv_shortcode_delete_record', array( $this, 'shortcode_delete_record' ) );

		/* shortcodes */
		add_shortcode( 'DB-Query', array( $this, 'shortcode' ) );
	}

	/**
	 * add database options on plugin activation
	 */
	public function on_activate() {
		foreach ( $this->options as $option => $value ) {
			update_option( $option, $value );
		}
	}

	/**
	 * remove database options on plugin deactivation
	 */
	public function on_deactivate() {
		foreach ( $this->options as $option => $value ) {
			delete_option( $option );
		}
	}

	public function load_easyui() {
		wp_enqueue_style( 'easyui-css', $this->plugin_url . '/css/easyui.css' );
		wp_enqueue_style( 'easyui-icon-css', $this->plugin_url . '/css/icon.css' );
		wp_enqueue_style( 'easyui-dialog-css', $this->plugin_url . '/css/dialog.css' );

		wp_enqueue_script( 'easyui-js', $this->plugin_url . '/js/jquery.easyui.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'edatagrid-js', $this->plugin_url . '/js/jquery.edatagrid.js' );
	}

	/**
	 * load Javascript and CSS
	 */
	public function load_scripts() {
		wp_enqueue_style( 'db-query-styles' , $this->plugin_url . '/css/style.css' );
		wp_enqueue_style( 'db-query-jquery-css', $this->plugin_url . '/css/jquery-ui.css' );

		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://code.jquery.com/jquery-latest.min.js"), false, '');
		wp_enqueue_script('jquery');

		wp_enqueue_script( 'db-query-scripts' , $this->plugin_url . '/js/core.js', array( 'jquery' ) );

		$this->load_easyui();
	}

	/**
	 * add menu tabs to the admin side
	 */
	public function admin_menu() {
		$plugin_page = add_options_page( 'DB Viewer & Editor', 'DB Viewer & Editor', 'activate_plugins', 'db-query', array( $this, 'settings_page' ) );

		/* add scripts and css only on our plugin page */
		add_action( 'load-' . $plugin_page, array( $this, 'load_scripts' ), 1 );
	}

	/**
	 * get role permissions
	 */
	public function settings_get_roles() {
		$options = get_option( 'db-query-queries' );

		/* if the query does not exist just get the roles template */
		$role_options = ( $_GET['query_id'] == '0' ) ? $this->roles_template : array_merge( $this->roles_template, $options[ $_GET['query_id'] ]['permissions'] );

		/* update roles option to prepare work when updating roles */
		update_option( 'db-query-roles', $role_options );

		$rows = array();

		foreach ( $role_options as $role => $permissions ) {
			$row_object = (object) null;

			$row_object->{'role'} = $role;
			$row_object->{'view'} = $permissions['view'];
			$row_object->{'edit'} = $permissions['edit'];
			$row_object->{'remove'} = $permissions['remove'];

			$rows[] = $row_object;
		}

		echo json_encode( $rows );

		die();
	}

	/**
	 * update role permissions
	 */
	public function settings_update_role() {
		$options = get_option( 'db-query-queries' );

		/* get data from roles option for a non existent query */
		if ( $_GET['query_id'] == '0' ) {
			$role_options = get_option( 'db-query-roles' );
		}
		else {
			/* query in the database, get the permissions for it */
			$role_options = $options[ $_GET['query_id'] ]['permissions'];
		}

		$view = $_REQUEST['view'];
		$edit = $_REQUEST['edit'];
		$remove = $_REQUEST['remove'];

		/* update the permissions for this role */
		$role_options[ $_REQUEST['role'] ] = array(
			'view' => $view,
			'edit' => $edit,
			'remove' => $remove,
		);

		/* update roles option when the query does not exist */
		if ( $_GET['query_id'] == '0' ) {
			update_option( 'db-query-roles', $role_options );
		}
		else {
			$options[ $_GET['query_id'] ]['permissions'] = $role_options;
			update_option( 'db-query-queries', $options );
		}

		echo json_encode( $role_options[ $_REQUEST['role'] ] );

		die();
	}

	/**
	 * show settings page
	 */
	public function settings_page() {
		include_once 'settings.php';
	}

	/**
	 * get a list of db queries from the settings page
	 */
	public function settings_get_queries() {
		$options = get_option( 'db-query-queries' );

		$rows = array();
		foreach ( $options as $option => $values ) {
			$row_object = ( object ) null;

			$row_object->id = $option;
			$row_object->description = $values['description'];
			$row_object->query = $values['query'];
			$row_object->maxrows = $values['maxrows'];
			$row_object->shortcode = '[DB-Query id=' . $option . ']';

			$rows[] = $row_object;
		}

		echo json_encode( $rows );

		die();
	}

	/**
	 * add a new db query from the settings page
	 */
	public function settings_add_query() {
		$options = get_option( 'db-query-queries' );
		$role_options = get_option( 'db-query-roles' );

		$query_id = time();

		$options[ $query_id ] = array(
			'description' => $_REQUEST['description'],
			'maxrows' => $_REQUEST['maxrows'],
			'query' => stripslashes( $_REQUEST['query'] ),
			'permissions' => $role_options
		);

		/* clear the roles option to use the template on new queries */
		update_option( 'db-query-roles', $this->roles_template );

		/* update query options */
		update_option( 'db-query-queries', $options );

		echo json_encode( $options[ $query_id ] );

		die();
	}

	/**
	 * update a db query from the settings page
	 */
	public function settings_update_query() {
		$options = get_option( 'db-query-queries' );

		$id = $_REQUEST['id'];

		$options[ $id ]['description'] = $_REQUEST['description'];
		$options[ $id ]['maxrows'] = $_REQUEST['maxrows'];
		$options[ $id ]['query'] = stripslashes( $_REQUEST['query'] );

		update_option( 'db-query-queries', $options );

		echo json_encode( $options[ $id ] );

		die();
	}

	/**
	 * remove a db query from the settings page
	 */
	public function settings_remove_query() {
		$options = get_option( 'db-query-queries' );
		$id = $_REQUEST['id'];

		unset( $options[ $id ] );

		update_option( 'db-query-queries', $options );

		echo json_encode( array( 'success' => true ) );

		die();
	}

	/**
	 * test a db query from the settings page
	 */
	public function settings_test_query() {
		$conn = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		if ( mysqli_connect_errno() )
			die( 'Connection Error: ' . mysqli_connect_error() );

		$query = stripslashes( $_REQUEST['query'] );

		$result = mysqli_query( $conn, $query );

		if ( ! $result )
			die( 'Couldn\'t execute query. ' . mysqli_error( $conn ) );

		echo '<table>';
		echo '<tr>';

		/* load column names from the database */
		while ( $column = mysqli_fetch_field( $result ) ) {
			echo '<th>' . $column->name . '</th>';
		}
		echo '</tr>';

		/* load rows */
		while ( $row = mysqli_fetch_array( $result ) ) {
			echo '<tr>';
			for ( $i = 0; $i < count( $row ); $i++ ) {
				echo '<td>' . $row[ $i ] . '</td>';
			}
			echo '</tr>';
		}

		echo '</table>';

		die();
	}

	/**
	 * output shortcode inside the posts
	 */
	public function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => '0'
		), $atts ) );

		/* we need to return our data instead of printing, or it would show always before any content */
		ob_start();

		/* get this user's role permissions */
		$role_can = DB_Viewer_Editor::get_role_permissions( $atts['id'] );

		//if ( $role_can->view ):
?>
		<script type="text/javascript">
			/* this piece is needed to make the iframe the same size of the datagrid inside shortcode-html.php */
			function resizeIframe( obj ) {
				obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
			}
		</script>
		<iframe src="<?php echo $this->plugin_url; ?>/shortcode-html.php?id=<?php echo $atts['id']; ?>&can_edit=<?php echo $role_can->edit; ?>&can_remove=<?php echo $role_can->remove; ?>"
				width="100%" scrolling="no" onload="javascript:resizeIframe( this );"></iframe>
<?php //else: ?>
			<!--<img src="<?php echo $this->plugin_url . '/access-denied.jpg'; ?>" title="You're not authorized to view this page!" />-->
<?php //endif;

		return ob_get_clean();
	}

	/**
	 * update a record from the shortcode datagrid
	 */
	public function shortcode_update_record() {
		/* remove the action parameter from the post data */
		$post_data = array_slice( $_POST, 0, -1 );

		/* get the table name */
		$table = $post_data['shortcode_table'];

		/* remove the table name from the received data array */
		unset( $post_data['shortcode_table'] );

		/* values to be passed to the execute method as bind parameters */
		$column_values = array();

		/* build the SET part of the query in the form of column = ? */
		$set_values = array();

		foreach ( $post_data as $field => $value ) {
			$column_values[] = $value;
			$set_values[] = "$field = ?";
		}

		/* get both the WHERE and SET parts as strings */
		$query_where = array_shift( $set_values );
		$query_set = implode( ',', $set_values );

		/* move the first value to the end, because that's the id we're going to update */
		$id = array_shift( $column_values );
		array_push( $column_values, $id );

		/* connect to the database, build the query, prepare it and finally executing */
		$conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD );
		$query = "UPDATE $table SET $query_set WHERE $query_where";
		$q = $conn->prepare( $query );
		$q->execute( $column_values );

		die();
	}

	/**
	 * delete a record from the shortcode datagrid
	 */
	public function shortcode_delete_record() {
		/* get the row parameter from the post data */
		$post_data = $_POST['row'];

		/* get the table name */
		$table = $post_data['shortcode_table'];

		/* remove the table name from the received data array */
		unset( $post_data['shortcode_table'] );

		/* values to be passed to the execute method as bind parameters */
		$column_values = array();

		/* build the SET part of the query in the form of column = ? */
		$set_values = array();

		foreach ( $post_data as $field => $value ) {
			$column_values[] = $value;
			$set_values[] = "$field = ?";
		}

		/* get both the WHERE and SET parts as strings */
		$query_where = array_shift( $set_values );

		/* grab the first column value because that's the id we're going to update */
		$id = array_shift( $column_values );

		/* connect to the database, build the query, prepare it and finally executing */
		$conn = new PDO( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD );
		$query = "DELETE FROM $table WHERE $query_where";
		$q = $conn->prepare( $query );
		$q->execute( array( $id ) );

		echo json_encode( array( 'success' => true ) );

		die();
	}

	/**
	 * find out if the current user is allowed to view, edit and/or remove records in the shortcode query table
	 */
	public static function get_role_permissions( $query_id ) {
		/* get all roles this user belongs to */
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;

		/* get the permissions for the query */
		$options = get_option( 'db-query-queries' );
		$role_perm = $options[ $query_id ]['permissions'];

		/* store all permissions here */
		$permissions = ( object ) null;

		foreach ( $roles as $role ) {
			$permissions->view = $role_perm[ ucfirst( $role ) ]['view'] == 'yes';
			$permissions->edit = $role_perm[ ucfirst( $role ) ]['edit'] == 'yes';
			$permissions->remove = $role_perm[ ucfirst( $role ) ]['remove'] == 'yes';
		}

		return $permissions;
	}
}

/* initialize our object */
return new DB_Viewer_Editor();