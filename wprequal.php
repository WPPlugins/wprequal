<?
/*
Plugin Name: WPrequal
Plugin URI:  https://wprequal.com
Description: Provide mortgage prequalifications from your WordPress website (USA only)
Version:     1.0
Author:      WPrequal
Author URI:  https://wprequal.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wprequal

WPrequal is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WPrequal is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WPrequal. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * WPrequal Class
 *
 * @since 1.0
 */
 
class WPrequal {
	
	/** constructor */
	public function __construct() {
		
		/**
		 * Require WPrequal Widget Class.
 		 */
		 
		require_once( plugin_dir_path( __FILE__ ) . 'widget.php' );
		
		/**
		 * Enqueue Scripts.
		 *
		 * Enqueue and localize scripts for WPrequal widget.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'widgets_init', function(){
			register_widget( 'WPrequal_Widget' );
		});
		
		/**
		 * Enqueue Scripts.
		 *
		 * Enqueue and localize scripts for WPrequal widget.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'wp_enqueue_scripts', array( $this, 'wprequal_enqueue_scripts' ) );
		
		/**
		 * WPrequal Custom Styles.
		 *
		 * Adds user selected custom inline styles for the WPrequal widget.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'wp_enqueue_scripts', array( $this, 'wprequal_custom_styles' ), 9999 );
		
		/**
		 * Add ajax.
		 *
		 * Init privileged and non privileged users ajax from WPrequal widget.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'wp_ajax_wprequal_ajax_process', array( $this, 'wprequal_ajax_process' ) );
		add_action( 'wp_ajax_nopriv_wprequal_ajax_process', array( $this, 'wprequal_ajax_process' ) );
		
		/**
		 * Require WPrequal Admin Class.
 		 */
		 
		require_once( plugin_dir_path( __FILE__ ) . 'admin/admin.php' );
		
	}
	
	/**
	 * Enqueue Scripts and Styles.
	 *
	 * Enqueue scriptsa and styles. Localize scripts for WPrequal widget ajax.
	 *
	 * @since 1.0
	 */
	 
	public function wprequal_enqueue_scripts() {
		wp_enqueue_style( 'wprequal_css', plugin_dir_url( __FILE__ ) . 'css/style.css' );
		wp_enqueue_script( 'wprequal_js', plugins_url( 'js/wprequal.js', __FILE__ ) , array ( 'jquery' ), NULL, true );
		wp_localize_script( 'wprequal_js', 'wprequal_url', array(
			'ajax_url' 		=> admin_url( 'admin-ajax.php' ),
			'nonce' 			=> wp_create_nonce( 'wprequal_secure_me' ), )
		);
	}
	
	/**
	 * Process Ajax Requests.
	 *
	 * Data received form WPrequal widget via ajax is passed to the WPreQal api.
	 *
	 * @since 1.0
	 *
	 * @param string $_REQUEST Data received from WPrequal ajax POST.
	 */
	
	public function wprequal_ajax_process() {
		check_ajax_referer( 'wprequal_secure_me', 'wprequal_ajax_nonce' );
		self :: wprequal_get( 'process/1.0/?' . $_REQUEST['string'] . '&key=' );
		exit();
	}
	
	/**
	 * Get and Recieve data.
	 *
	 * Get and Recieve data from WPrequal api.
	 *
	 * @since 1.0
	 *
	 * @param string $string Get string request for WPrequal api.
	 */
	 
	public static function wprequal_get( $string ) {
		$token = get_option( 'wprequal_token_code' );
		return wp_remote_retrieve_body( wp_remote_get( 'https://api.wprequal.com/' . $string . $token ) );
	}
	
	/**
	 * Error Message.
	 *
	 * Display error message if data not received from WPreQal api.
	 *
	 * @since 1.0
	 */
	 
	public static function wprequal_error() {
		?>
		<div class="wprequal_red">
			An error has occured. Please refresh your browser. If this issue persists. Please contact the website administrator.
		</div>
		<?
	}
	
	/**
	 * Add Custom Styles.
	 *
	 * Add user selected custon styles for WPrequal widget.
	 *
	 * @since 1.0
	 */
	
	public function wprequal_custom_styles() {
		wp_enqueue_style(
			'wprequal_custom_styles',
			plugin_dir_url( __FILE__ ) . '/css/style.css'
		);
		
		// Read in existing option value from database
		$option = get_option( 'wprequal_settings' );
		
		$custom_css = "
			.wprequal {
				background: {$option['back_color']};
			}
			
			.wprequal_header {
				color: {$option['header_font_color']} !important;
				font-size: {$option['header_font_size']}px;
			}
			
			.wprequal_content {
				color: {$option['sub_font_color']};
				font-size: {$option['sub_font_size']}px;
			}
			
			.wprequal button {
				color: {$option['button_font_color']};
				background: {$option['button_back_color']};
				padding-top: {$option['button_padding']}px;
				padding-bottom: {$option['button_padding']}px;
			}
			
			
		";
		wp_add_inline_style( 'wprequal_custom_styles', $custom_css );
	}

}

$WPrequal = new WPrequal();
?>