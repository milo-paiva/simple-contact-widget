<?php
/**
 * Simple Contact Widget
 *
 * @link              https://github.com/milo-paiva/simple-contact-widget
 * @since             1.0
 * @package           Simple Contact Widget
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Contact Widget
 * Plugin URI:        https://github.com/milo-paiva/simple-contact-widget
 * Description:       Simple widget to display your contact information with links and icons
 * Version:           1.1
 * Author:            Milo Paiva
 * Author URI:        https://bekiwee.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       scwidget
 * Domain Path:       /languages
 */


if( ! defined( 'ABSPATH') ){
    exit;
}

if ( ! class_exists( 'Simple_Contact_Widget' ) )
{
    class Simple_Contact_Widget extends WP_Widget {

		function __construct() {
			$this->define_scwidget_constants();

            $this->load_scwidget_textdomain();

            parent::__construct(
				'wpcontact_widget',
				__( 'Simple Contact', 'scwidget' ), 
				array( 'description' => __( 'Display your contact information with links and icons', 'scwidget' ), ) // Args
			);

            add_action( 'wp_enqueue_scripts', array( $this, 'scwidget_register_scripts' ), 999 );			
		}

        public function define_scwidget_constants(){
            define( 'SCWIDGET_PATH', plugin_dir_path( __FILE__ ) );
            define( 'SCWIDGET_URL', plugin_dir_url( __FILE__ ) );
            define( 'SCWIDGET_VERSION', '1.0.0' );
        }

        public function load_scwidget_textdomain(){
            load_plugin_textdomain(
                'scwidget',
                false,
                dirname( plugin_basename( __FILE__ ) ) . '/languages/'
            );
        }

        public function scwidget_register_scripts(){
            wp_enqueue_style('font-awesome', SCWIDGET_URL . 'includes/css/font-awesome.css'); 
	        wp_enqueue_style('style', SCWIDGET_URL . 'includes/css/style.css', array(), SCWIDGET_VERSION, 'all' );
        }

        public function widget( $args, $instance ) {
			
			$output = '';
			
			echo $args['before_widget'];
			
			if ( !empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}	
			
			echo '<div>';

			if ( !empty( $instance['address'] ) )
			{
				$output .='
				<div class="scwidget scwidget-address">
					<div class="sciwdget-icon">
						<i class="fa fa-map-o" aria-hidden="true"></i>
					</div>
					<div class="scwidget-address"><a href="'. esc_url($instance['addressLink']) .'" target="_blank">'. esc_attr__($instance['address']) .'</a></div>
				</div>';
			}

			if ( !empty( $instance['phone'] ) )
			{
				$output .='
				<div class="scwidget scwidget-phone">
					<div class="sciwdget-icon">
						<i class="fa fa-phone-square" aria-hidden="true"></i>
					</div>
					<div class="scwidget-phone"><a href="tel:055'. esc_attr(preg_replace('/[^0-9]/', '', $instance['phone'])) .'">'.  esc_attr__($instance['phone']) .'</a></div>
				</div>';
			}

			if ( !empty( $instance['whatsapp'] ) )
			{
				$output .='
				<div class="desk"><div class="scwidget scwidget-whats">
					<div class="sciwdget-icon">
						<i class="fa fa-whatsapp"></i>
					</div>
					<div class="scwidget-phone"><a href="https://web.whatsapp.com/send?phone=55'. esc_attr(preg_replace('/[^0-9]/', '', $instance['whatsapp'])) .'&text=Olá!%20Acessei%20o%20site%20e%20gostaria%20de%20mais%20informações%20sobre" target="_blank">'.  esc_attr__($instance['whatsapp']) .'</a></div>
				</div></div>
				<div class="mob"><div class="scwidget scwidget-whats">
					<div class="sciwdget-icon">
						<i class="fa fa-whatsapp"></i>
					</div>
					<div class="scwidget-phone"><a href="https://api.whatsapp.com/send?phone=55'. esc_attr(preg_replace('/[^0-9]/', '', $instance['whatsapp'])) .'&text=Olá!%20Acessei%20o%20site%20e%20gostaria%20de%20mais%20informações%20sobre" target="_blank">'.  esc_attr__($instance['whatsapp']) .'</a></div>
				</div></div>';
			}

			if ( !empty( $instance['contact_email'] ) )
			{
				$output .='
				<div class="scwidget scwidget-mail">
					<div class="sciwdget-icon">
						<i class="fa fa-envelope-o"></i>
					</div>
					<div class="scwidget-contact_email"><a href="mailto:'. esc_attr($instance['contact_email']) .'">'.  esc_attr__($instance['contact_email']) .'</a></div>
				</div>';	
			}	
			
			if ( !empty( $instance['open_hours'] ) )
			{
				$output .= '
				<div class="scwidget scwidget-mail">
					<div class="sciwdget-icon">
						<i class="fa fa-clock-o"></i>
					</div>
					<div class="scwidget-open_hours">'.  esc_attr__($instance['open_hours']) .'</div>
				</div>';	
			}	
			
			echo $output;	
			
			echo '</div>';
			
		}

		public function form( $instance ) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Contact Information', 'scwidget' );
			$address = ! empty( $instance['address'] ) ? $instance['address'] : __( 'Address', 'scwidget' );
			$addressLink = ! empty( $instance['addressLink'] ) ? $instance['addressLink'] : __( 'Location URL', 'scwidget' );
			$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : __( 'Phone', 'scwidget' );
			$whatsapp = ! empty( $instance['whatsapp'] ) ? $instance['whatsapp'] : __( 'WhatsApp', 'scwidget' );
			$contact_email = ! empty( $instance['contact_email'] ) ? $instance['contact_email'] : __( 'Email', 'scwidget' );
			$open_hours = ! empty( $instance['open_hours'] ) ? $instance['open_hours'] : __( 'Opening Hours', 'scwidget' );
			?>
			<p class="scwidget-row">
				<label for="<?php echo esc_html($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title: ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'title' )); ?>" name="<?php echo esc_html($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p class="scwidget-row">
				<label><?php esc_html_e( 'Address: ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'address' )); ?>" name="<?php echo esc_html($this->get_field_name( 'address' )); ?>" type="text" value="<?php echo esc_attr( $address ); ?>">
			</p>
			<p class="scwidget-row">
				<label><?php esc_html_e( 'Google Maps Link: ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'addressLink' )); ?>" name="<?php echo esc_html($this->get_field_name( 'addressLink' )); ?>" type="text" value="<?php echo esc_attr( $addressLink ); ?>">
			</p>
			<p class="scwidget-row">
				<label for="<?php echo esc_html($this->get_field_id( 'phone' )); ?>"><?php esc_html_e( 'Telephone: ', 'scwidget'); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'phone' )); ?>" name="<?php echo esc_html($this->get_field_name( 'phone' )); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
			</p>
			<p class="scwidget-row">
				<label for="<?php echo esc_html($this->get_field_id( 'phone' )); ?>"><?php esc_html_e( 'WhatsApp: ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'phone' )); ?>" name="<?php echo esc_html($this->get_field_name( 'whatsapp' )); ?>" type="text" value="<?php echo esc_attr( $whatsapp ); ?>">
			</p>
			<p class="scwidget-row">
				<label for="<?php echo esc_html($this->get_field_id( 'phone' )); ?>"><?php esc_html_e( 'Email: ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'phone' )); ?>" name="<?php echo esc_html($this->get_field_name( 'contact_email' )); ?>" type="email" value="<?php echo esc_attr( $contact_email ); ?>">
			</p>
			<p class="scwidget-row">
				<label for="<?php echo esc_html($this->get_field_id( 'open_hours' )); ?>"><?php esc_html_e( 'Opening hours:  ', 'scwidget' ); ?></label> 
				<input class="cswidget-wide" id="<?php echo esc_html($this->get_field_id( 'open_hours' )); ?>" name="<?php echo esc_html($this->get_field_name( 'open_hours' )); ?>" type="text" value="<?php echo esc_attr( $open_hours ); ?>">
			</p>
			<?php 
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
			$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? wp_strip_all_tags( $new_instance['address'] ) : '';
			$instance['addressLink'] = ( ! empty( $new_instance['addressLink'] ) ) ? wp_strip_all_tags( $new_instance['addressLink'] ) : '';
			$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? wp_strip_all_tags( $new_instance['phone'] ) : '';
			$instance['whatsapp'] = ( ! empty( $new_instance['whatsapp'] ) ) ? wp_strip_all_tags( $new_instance['whatsapp'] ) : '';
			$instance['contact_email'] = ( ! empty( $new_instance['contact_email'] ) ) ? wp_strip_all_tags( $new_instance['contact_email'] ) : '';
			$instance['open_hours'] = ( ! empty( $new_instance['open_hours'] ) ) ? wp_strip_all_tags( $new_instance['open_hours'] ) : '';

			return $instance;
		}
    }
}

function register_scwidget() {
    register_widget( 'Simple_Contact_Widget' );
}
add_action( 'widgets_init', 'register_scwidget' );