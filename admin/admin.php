<?
/**
 * WPrequal Admin Class
 *
 * @since 1.0
 */
 
class WPrequal_Admin {
	
	/** constructor */
	public function __construct() {
		
		/**
		 * Amin Menu.
		 *
		 * Adds submenu item to Setting tab in WP admin.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'admin_menu', array( $this, 'wprequal_add_pages' ) );
		
		/**
		 * Admin Scripts.
		 *
		 * Adds scripts and styles to WPrequal settings page.
		 *
		 * @since 1.0
		 */
		 
		add_action( 'admin_enqueue_scripts', array( $this, 'wprequal_enqueue_admin_scripts' ) );
		
	}
	
	/**
	 * Add WPrequal settings page to Settings submenu.
	 *
	 * @since 1.0
	 */
	 
	public function wprequal_add_pages() {
   		// Add a new submenu under Settings:
		add_options_page(
			__('WPrequal','wprequal'), 
			__('WPrequal','wprequal'), 
			'manage_options', 
			'wprequal', 
			array( $this, 'wprequal_settings_page' ) 
		);
	}
	
	/**
	 * WPrequal settings page.
	 *
	 * @since 1.0
	 */
	 
	public function wprequal_settings_page() {

		//must check that the user has the required capability 
		if ( ! current_user_can( 'manage_options' ) ) {
		  wp_die( __( 'You do not have sufficient permissions to access this page.') );
		}
		
		echo "<h2>" . __( 'WPrequal Settings', 'wprequal' ) . "</h2>";
		
		if( isset($_POST[ 'wprequal_start_up' ]) && $_POST[ 'wprequal_start_up' ] == 'Y' ) {
			self :: wprequal_set_token();
			self :: wprequal_set_defaults();
		}
		
		if( isset($_POST[ 'wprequal_submit_hidden' ]) && $_POST[ 'wprequal_submit_hidden' ] == 'Y' ) {
			
			$new_options = array(
				'back_color' 		=> sanitize_text_field( $_POST['back_color'] ),
				'header_font_color' => sanitize_text_field( $_POST['header_font_color'] ),
				'header_font_size' 	=> sanitize_text_field( $_POST['header_font_size'] ),
				'sub_font_color' 	=> sanitize_text_field( $_POST['sub_font_color'] ),
				'sub_font_size' 		=> sanitize_text_field( $_POST['sub_font_size'] ),
				'button_back_color'	=> sanitize_text_field( $_POST['button_back_color'] ),
				'button_font_color' => sanitize_text_field( $_POST['button_font_color'] ),
				'button_padding' 	=> sanitize_text_field( $_POST['button_padding'] )
			);
	
			// Save the posted value in the database
			update_option( 'wprequal_settings', $new_options );
	
			// Put a "settings saved" message on the screen
	
			?>
			<div class="updated"><p><strong><? _e( 'Settings saved.', 'wprequal' ); ?></strong></p></div>
			<?
	
		}
		
		// If WPrequal token or defaults do not exsist add into database
		if ( get_option( 'wprequal_token_code' ) && get_option( 'wprequal_settings' ) ) {
		
			// Read in existing option value from database
			$option = get_option( 'wprequal_settings' );
		
			// Styles for color picker
			?>
			<style>
				.wp-picker-holder{
					position: absolute;
					z-index: 999;
				}
				
				.wprequal_red{
					border-color:#FC0307 !important;	
				}
			</style>
			
			<div class="wrap">
				<?
			
				// settings form
				
				?>
			
				<form id="wprequal_customize" method="post">
					
					<input type="hidden" name="wprequal_submit_hidden" value="Y" />
					
					<table>
					
						<tr>
							<td><? _e( 'Background Color:', 'wprequal' ); ?></td>
							<td><input type="text" name="back_color" value="<? echo $option['back_color']; ?>" class="wprequal_colors" data-default-color="#CCCCCC" /></td>
						</tr>
						
						<tr>
							<td><? _e( 'Header Font Color:', 'wprequal' ); ?></td>
							<td><input type="text" name="header_font_color" value="<? echo $option['header_font_color']; ?>" class="wprequal_colors" data-default-color="000000"/></td>
						</tr>
						
						<tr>
							<td><? _e( 'Header Font Size:', 'wprequal' ); ?></td>
							<td><input type="number" name="header_font_size" value="<? echo $option['header_font_size']; ?>" class="wprequal_admin_input" />px</td>
						</tr>
						
						<tr>
							<td><? _e( 'Subheader Font Color:', 'wprequal' ); ?></td>
							<td><input type="text" name="sub_font_color" value="<? echo $option['sub_font_color']; ?>" class="wprequal_colors" data-default-color="#000000" /></td>
						</tr>
						
						<tr>
							<td><? _e( 'Subheader Font Size:', 'wprequal' ); ?></td>
							<td><input type="number" name="sub_font_size" value="<? echo $option['sub_font_size']; ?>" class="wprequal_admin_input" />px</td>
						</tr>
						
						<tr>
							<td><? _e( 'Button Backgeound Color:', 'wprequal' ); ?></td>
							<td><input type="text" name="button_back_color" value="<? echo $option['button_back_color']; ?>" class="wprequal_colors" data-default-color="#02b760" /></td>
						</tr>
						
						<tr>
							<td><? _e( 'Button Font Color:', 'wprequal' ); ?></td>
							<td><input type="text" name="button_font_color" value="<? echo $option['button_font_color']; ?>" class="wprequal_colors" data-default-color="#000000" /></td>
						</tr>
						
						<tr>
							<td><? _e( 'Button Padding:', 'wprequal' ); ?></td>
							<td><input type="number" name="button_padding" value="<? echo $option['button_padding']; ?>" class="wprequal_admin_input" />px</td>
						</tr>
				
					</table>
					
					<p class="submit">
						<input type="submit" name="Submit" class="button-primary wprequal_customize_button" value="<? esc_attr_e( 'Save Changes' ) ?>" />
					</p>
				</form>
			</div>
			
			<?
		} else {
		
			?>
			<form method="post">
				<input type="hidden" name="wprequal_start_up" value="Y" />
				
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<? esc_attr_e( 'Get Started' ) ?>" />
				</p>
			</form>
			<?
			
		}
	 
	}
	
	/**
	 * Colorpicker 
	 *
	 * Add styles and scripts for wp colorpicker on WPrequal settings page.
	 *
	 * @since 1.0
	 */
	
	public function wprequal_enqueue_admin_scripts() {
   	 	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_script( 'wprequal_colorpicker', plugins_url( 'admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	
	/**
	 * Request and Set api token.
	 *
	 * Sets the api token at WP init if token does not exsist.
	 *
	 * @since 1.0
	 */
	
	private static function wprequal_set_token() {
		if ( ! get_option( 'wprequal_token_code' ) ) {
			$new_token = WPrequal :: wprequal_get( 'activate/1.0/?wprequal_url=' . urlencode( get_site_url() ) );
			update_option( 'wprequal_token_code', sanitize_text_field( $new_token ) );
		}
	}
	
	/**
	 * WPrequal Defaults.
	 *
	 * Sets the WPrequal defaults if they do not exsist.
	 *
	 * @since 1.0
	 */
	
	private static function wprequal_set_defaults() {
		$default_options = array(
			'back_color' 		=> '#CCCCCC',
			'header_font_color' => 'inherit',
			'header_font_size' 	=> 28,
			'sub_font_color' 	=> 'inherit',
			'sub_font_size' 		=> 24,
			'button_back_color'	=> 'inherit',
			'button_font_color' => 'inherit',
			'button_padding' 	=> 4
		);
			
		// Save the default options in the database
		update_option( 'wprequal_settings', $default_options );
	}

}

$WPrequal_Admin = new WPrequal_Admin();
?>