<?
/**
 * WPrequal Widget.
 *
 * Displays WPrequal widget in widget areas.
 *
 * @since 1.0
 */
 
class WPrequal_Widget extends WP_Widget {
	
	/** constructor */
	public function __construct() {
		parent::__construct(
			'wprequal',
			__( 'WPrequal', 'wprequal' ),
			array( 'description' => __( 'Add a mortgage prequal form', 'wprequal' ), )
		);
	}
	
	/** @see WP_Widget::widget */
	public function widget( $args, $instance ) {
		
		echo $args['before_widget'];
		?>
		<div class="wprequal">
		
			<div class="wprequal_wrap" id="wprequal_wrap">
				<? $response = json_decode( WPrequal :: wprequal_get( 'form/1.0/?key=' ) );
				if ( ! empty ( $response ) ) echo $response;
				else WPrequal :: wprequal_error(); ?>
			</div>
	
			<input type="hidden" id="wprequal_client_email" class="wprequal_data" value="<? echo $instance['email']; ?>">
			
		</div>
		<?
		echo $args['after_widget'];
	}
	
	/** @see WP_Widget::form */
	public function form( $instance ) {
		$email = ! empty( $instance['email'] ) ? $instance['email'] : __( 'Your email here', 'wprequal' );
		?>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php _e( esc_attr( 'Email my leads to:' ) ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<?php 
	}
	
	/** @see WP_Widget::update */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';

		return $instance;
	}
}
?>