<?php
/**
 * "Game element" widget: shows one game element (cash, timers, menu, …) in any widget area.
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

namespace DigiFalk\MafiaPBBGEngine\Frontend;

defined( 'ABSPATH' ) || exit;

class HudWidget extends \WP_Widget {

	public function __construct() {
		parent::__construct(
			'dfmg_hud',
			__( 'Mafia PBBG Engine: game element', 'digifalk-mafia-pbbg-engine' ),
			array(
				'description'                 => __( 'Show a live piece of the game, such as cash, timers or the game menu.', 'digifalk-mafia-pbbg-engine' ),
				'customize_selective_refresh' => true,
			)
		);
	}

	public function widget( $args, $instance ) {
		$key  = sanitize_key( $instance['element'] ?? 'player' );
		$html = Hud::render( $key, 'stack', 'widget' );
		if ( '' === $html ) {
			return;
		}
		echo wp_kses_post( $args['before_widget'] );
		$title = apply_filters( 'widget_title', $instance['title'] ?? '', $instance, $this->id_base );
		if ( $title ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( $title ) . wp_kses_post( $args['after_title'] );
		}
		echo wp_kses( $html, dfmg_allowed_html() );
		echo wp_kses_post( $args['after_widget'] );
	}

	public function form( $instance ) {
		$title   = (string) ( $instance['title'] ?? '' );
		$element = (string) ( $instance['element'] ?? 'player' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'digifalk-mafia-pbbg-engine' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'element' ) ); ?>"><?php esc_html_e( 'Element:', 'digifalk-mafia-pbbg-engine' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'element' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'element' ) ); ?>">
				<?php foreach ( Hud::labels() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $element, $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
		return '';
	}

	public function update( $new_instance, $old_instance ) {
		$key = sanitize_key( $new_instance['element'] ?? '' );
		return array(
			'title'   => sanitize_text_field( $new_instance['title'] ?? '' ),
			'element' => isset( Hud::labels()[ $key ] ) ? $key : 'player',
		);
	}
}
