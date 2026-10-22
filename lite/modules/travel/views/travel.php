<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $destinations
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;

if ( $c->timer_active( 'travel' ) ) {
	echo wp_kses( UI::cooldown( __( 'You can fly again in', 'digifalk-mafia-pbbg-engine' ), $c->timer( 'travel' ) ), dfmg_allowed_html() );
}
?>
<p>
	<?php
	/* translators: %s: city */
	printf( esc_html__( 'You are in %s. Where do you want to go?', 'digifalk-mafia-pbbg-engine' ), '<strong>' . esc_html( $c->location_name() ) . '</strong>' );
	?>
</p>
<table class="dfmg-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Destination', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Ticket', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Cooldown after arrival', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $destinations as $dfmg_dest ) : ?>
			<tr>
				<td><strong><?php echo esc_html( $dfmg_dest['name'] ); ?></strong></td>
				<td><?php echo esc_html( Format::money( $dfmg_dest['travel_cost'] ) ); ?></td>
				<td><?php echo esc_html( Format::duration( (int) $dfmg_dest['travel_time'] ) ); ?></td>
				<td><?php echo wp_kses( $this->button( 'fly', __( 'Fly', 'digifalk-mafia-pbbg-engine' ), array( 'to' => $dfmg_dest['id'] ) ), dfmg_allowed_html() ); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
