<?php
/*
Plugin name: Skyscanner Flight Search
Plugin URI: http://PLUGIN_URI.com/
Description: Skyscanner Flight Search
Author: Jin Che Wang
Author URI: http://AUTHOR_URI.com
Version: 1.0.0
Text Domain: sfs
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SFS') ) :

class SFS {

    /** @var string The plugin version number */
    var $version = '1.0.0';
    
    /** @var array The plugin settings array */
	var $settings = array();

    /*
	*  __construct
	*
	*  A dummy constructor to ensure SFS is only initialized once
	*
	*  @type	function
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
    */
    
    function __construct() {
		
		/* Do nothing here */
		
    }
    
    /*
	*  initialize
	*
	*  The real constructor to initialize SFS
	*
	*  @type	function
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	N/A
	*/
		
	function initialize() {
		
		// vars
		$version = $this->version;
		$basename = plugin_basename( __FILE__ );
		$path = plugin_dir_path( __FILE__ );
		$url = plugin_dir_url( __FILE__ );
		$slug = dirname($basename);
		
		
		// settings
		$this->settings = array(
			
			// basic
			'name'				=> __('Skyscanner Flight Search', 'sfs'),
			'version'			=> $version,
						
			// urls
			'file'				=> __FILE__,
			'basename'			=> $basename,
			'path'				=> $path,
			'url'				=> $url,
			'slug'				=> $slug,
        );

        // constants
        $this->define('SFS_PATH', $path);
        $this->define('SFS_URL', $url);
        
        // api
		include_once( SFS_PATH . 'includes/api/api-helpers.php');

		// admin
		if( is_admin() ) {
			sfs_include('includes/admin/admin.php');
		}

		// actions
		// add_action( 'plugins_loaded', array( $this, 'page_templater') );

		// create a virtual page for permalink : search-flights
		$this->create_virtual_page();

		// actions
		add_action( 'wp_enqueue_scripts', array( $this, 'sfs_scripts') );

		add_action( 'wp_ajax_sfs_browse_routes_ajax_func', array( $this, 'sfs_browse_routes_ajax_func' ) );
		add_action( 'wp_ajax_nopriv_sfs_browse_routes_ajax_func', array( $this, 'sfs_browse_routes_ajax_func' ) );
	}
    
    /*
	*  define
	*
	*  This function will safely define a constant
	*
	*  @type	function
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	$name (string)
	*  @param	$value (mixed)
	*  @return	n/a
	*/
	
	function define( $name, $value = true ) {
		
		if( !defined($name) ) {
			define( $name, $value );
		}
		
	}

	/**
	*  get_setting
	*
	*  Returns a setting.
	*
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	string $name
	*  @return	mixed
	*/
	
	function get_setting( $name ) {
		return isset($this->settings[ $name ]) ? $this->settings[ $name ] : null;
	}

	function page_templater(){
		sfs_include('includes/sfs_template_loader.php');
	}

	function create_virtual_page(){
		add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ){
			$wp_rewrite->rules = array_merge(
				['search-flights/?$' => 'index.php?sfs=1'],
				$wp_rewrite->rules
			);
		} );

		add_filter( 'query_vars', function( $query_vars ){
			$query_vars[] = 'sfs';
			return $query_vars;
		} );

		add_action( 'template_redirect', function(){
			$sfs = intval( get_query_var( 'sfs' ) );
			if ( $sfs ) {
				sfs_include('templates/search-flights-template.php');
				die;
			}
		} );
	}

	function sfs_scripts(){
		global $wp;

		$sfs_settings = get_option( 'sfs_settings' );

		if( isset($wp->request) && $wp->request == 'search-flights' && isset($sfs_settings['service_available']) && $sfs_settings['service_available'] ){
			wp_enqueue_script('sfs-reactjs', sfs_get_url('/assets/public/frontend.bundle.js') , array(), null, true);

			wp_localize_script( 'sfs-reactjs', 'sfs_wp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
		}
	}

	function get_sfs_settings(){
		$sfs_settings = get_option( 'sfs_settings' );

		sfs_include('includes/api/sfs-api.php');
		$sfs_api = new SFS_API();

		return array_merge( $sfs_settings , array(
			'locale' => $sfs_api->get_locale(),
			'currency' => $sfs_api->get_currency(),
		));
	}

	function sfs_browse_routes_ajax_func(){
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "sfs_browse_routes_ajax_nonce")) {
			exit("No naughty business please");
		}

		$sfs_from = $_POST['sfs_from'];
		$sfs_to = $_POST['sfs_to'];
		$sfs_date = $_POST['sfs_date'];

		$this->save_sfs_search_params($sfs_from, $sfs_to, $sfs_date);
		
		$sfs_settings = get_option( 'sfs_settings' );

		sfs_include('includes/api/sfs-api.php');
		$sfs_api = new SFS_API( $sfs_settings['api_key'], $sfs_settings['countries'] );

		$result = $sfs_api->get_browseroutes($sfs_from, $sfs_to, $sfs_date);

		$result = json_encode($result);
		
		echo $result;
	
		die();
	}

	function save_sfs_search_params($sfs_from, $sfs_to, $sfs_date){
		$new_data = array(
			'sfs_from' => $sfs_from,
			'sfs_to' => $sfs_to,
			'sfs_date' => $sfs_date
		);

		$sfs_search_params = get_option( 'sfs_search_params' );

		if( empty($sfs_search_params) ){
			add_option('sfs_search_params', array( $new_data ));
		} else {
			$sfs_search_params[] = $new_data;

			update_option('sfs_search_params', $sfs_search_params);
		}
	}
}

/*
*  sfs
*
*  The main function responsible for returning the one true sfs Instance to functions everywhere.
*  Use this function like you would a global variable, except without needing to declare the global.
*
*  Example: <?php $sfs = sfs(); ?>
*
*  @type	function
*  @date	23/12/2019
*  @since	1.0.0.0
*
*  @param	N/A
*  @return	(object)
*/

function sfs() {

	// globals
	global $sfs;
	
	// initialize
    if( !isset($sfs) ) {
        $sfs = new SFS();
        $sfs->initialize();
	}

	// return
	return $sfs;
}

// initialize
sfs();

endif; // class_exists check

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

register_activation_hook( __FILE__, 'flush_rewrite_rules' );

?>