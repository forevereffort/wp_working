<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('sfs_admin') ) :

class sfs_admin {

	var $options = array();
	
	/*
	*  __construct
	*
	*  Initialize filters, action, variables and includes
	*
	*  @type	function
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
	
		// actions
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array( $this, 'page_init' ));
		add_action('admin_enqueue_scripts',	array($this, 'admin_enqueue_scripts'), 0);
	}
	
	/*
	*  admin_menu
	*
	*  This function will add the SFS menu item to the WP admin
	*
	*  @type	action (admin_menu)
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function admin_menu() {
	
		// vars
		$parent_slug = 'skyscanner-flight-search';
		$cap = 'manage_options';
		
		
		// add parent
		add_menu_page(
			__("Skyscanner Flight Search", 'sfs'),
			__("Skyscanner Flight Search", 'sfs'),
			$cap,
			$parent_slug,
			array( $this, 'create_admin_page' ),
			'dashicons-admin-site'
		);

		add_submenu_page(
			$parent_slug,
			__("Settings", 'sfs'),
			__("Settings", 'sfs'),
			$cap, 
			$parent_slug,
			array( $this, 'create_admin_page' )
		);

		add_submenu_page(
			$parent_slug,
			__("Search History", 'sfs'),
			__("Search History", 'sfs'),
			$cap, 
			'skyscanner-flight-search-history',
			array( $this, 'create_params_page' )
		);
		
	}

	function create_params_page(){
		sfs_include('includes/admin/search-params.php');
	}
	
	function create_admin_page(){

		if( isset($_POST['reset']) ){
			update_option(
				'sfs_settings',
				array(
					'api_key' => '',
					'countries' => '',
					'service_available' => 1
				)
			);

			echo '<p style="color:red">SFS settings have been reset.</p>';
		}

		$this->options = get_option( 'sfs_settings' );
?>
        <div class="wrap">
            <h1>Skyscanner Flight Search Settings</h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'sfsPlugin' );
                do_settings_sections( 'sfsPlugin' );
				submit_button();
            ?>
            </form>
			<form method="post" action="">
				<p class="submit">
					<input name="reset" class="button button-secondary" type="submit" value="Clear all fields" >
					<input type="hidden" name="action" value="reset" />
				</p>
			</form>
        </div>
<?php
	}

    public function page_init(){

        register_setting(
            'sfsPlugin',
            'sfs_settings',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'sfs_settings_section',
            __('', 'sfs'),
            array( $this, 'sfs_settings_section_callback' ),
            'sfsPlugin'
		);

        add_settings_field(
            'api_key',
            __("API Key", 'sfs'),
            array( $this, 'api_key_render' ),
            'sfsPlugin',
            'sfs_settings_section'
        );      

        add_settings_field(
            'countries', 
            __("Countries", 'sfs'), 
            array( $this, 'countries_render' ), 
            'sfsPlugin', 
            'sfs_settings_section'
		);
		
		add_settings_field(
            'service_available', 
            __("Service Available", 'sfs'), 
            array( $this, 'service_available_render' ), 
            'sfsPlugin', 
            'sfs_settings_section'
        ); 
    }

    public function sanitize( $input ){
        $new_input = array();
		
        if( isset( $input['api_key'] ) )
			$new_input['api_key'] = sanitize_text_field( $input['api_key'] );
			
		if( isset( $input['countries'] ) )
			$new_input['countries'] = sanitize_text_field( $input['countries'] );
			
		if( isset( $input['service_available'] ) )
            $new_input['service_available'] = absint( $input['service_available'] );

        return $new_input;
    }

    function sfs_settings_section_callback(  ) {
		// echo __( 'This Section Description', 'sfs' );
	}

    public function api_key_render(){
        printf(
            '<input type="text" id="api_key" name="sfs_settings[api_key]" value="%s" style="width: 500px;"/>',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }

    public function countries_render(){
		sfs_include('includes/api/sfs-api.php');

		$sfs_api = new SFS_API($this->options['api_key']);

		$countries = array();

		$res = $sfs_api->get_countries();

		if( $res['success'] ){
			$countries = $res['data'];
		} else {
			echo '<p style="color: red">' . $res['err'] . '</p>';
		}
?>
		<div style="width: 500px;">
			<select id="sfs_countries" name='sfs_settings[countries]'>
				<option value="">Select a country...</option>
				<?php
					foreach($countries as $item){
						$selected = selected( $this->options['countries'], $item->Code );

						echo "<option value=\"{$item->Code}\" {$selected}>{$item->Name}</option>";
					}
				?>
			</select>
			<p>The <strong>market country</strong> your user is in</p>
		</div>
<?php
	}
	
	public function service_available_render(){
?>
    	<input type="checkbox" id="service_available" name="sfs_settings[service_available]" value="1" <?php checked( $this->options['service_available'], 1 ); ?> />
<?php
    }
	
	/*
	*  admin_enqueue_scripts
	*
	*  This function will add the already registered css
	*
	*  @type	function
	*  @date	23/12/2019
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function admin_enqueue_scripts( $hook ) {
		$version = sfs()->get_setting('version');

		if( $hook == 'toplevel_page_skyscanner-flight-search' ){
			wp_enqueue_style( 'selectize', sfs_get_url('assets/vendors/selectize/css/selectize.css') );

			wp_enqueue_script( 'selectize', sfs_get_url('assets/vendors/selectize/js/standalone/selectize.min.js'), array(), null, true);

			wp_enqueue_script( 'sfs-settings', sfs_get_url('assets/js/sfs-settings.js'), array('jquery', 'selectize'), $version, true);
		}
	}
}

// initialize
sfs()->admin = new sfs_admin();

endif; // class_exists check

?>