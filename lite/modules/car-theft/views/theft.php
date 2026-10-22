<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $spots
 * @var int                            $cooldown
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;

if ( $c->timer_active( 'theft' ) ) {
	echo wp_kses( UI::cooldown( __( 'You can steal a car again in', 'digifalk-mafia-pbbg-engine' ), $c->timer( 'theft' ) ), dfmg_allowed_html() );
}
?>
<p class="dfmg-muted">
	<?php
	/* translators: %s: duration */
	printf( esc_html__( 'After every attempt you have to wait %s.', 'digifalk-mafia-pbbg-engine' ), esc_html( Format::duration( $cooldown ) ) );
	?>
</p>
<table class="dfmg-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Spot', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Cars up to', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Chance', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $spots as $dfmg_spot ) : ?>
			<tr>
				<td><strong><?php echo esc_html( $dfmg_spot['name'] ); ?></strong></td>
				<td><?php echo esc_html( Format::money( $dfmg_spot['max_value'] ) ); ?></td>
				<td class="dfmg-col-bar"><?php echo wp_kses( UI::bar( (float) min( 100, (int) $dfmg_spot['chance'] ) ), dfmg_allowed_html() ); ?></td>
				<td><?php echo wp_kses( $this->button( 'steal', __( 'Steal', 'digifalk-mafia-pbbg-engine' ), array( 'spot' => $dfmg_spot['id'] ) ), dfmg_allowed_html() ); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
