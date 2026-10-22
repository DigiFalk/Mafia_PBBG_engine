<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $cars
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;
use DigiFalk\MafiaPBBGEngine\Locations;

defined( 'ABSPATH' ) || exit;

if ( ! $cars ) {
	echo wp_kses( UI::empty_state( __( 'Your garage is empty. Time to steal a car?', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() );
	return;
}
?>
<table class="dfmg-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Car', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Damage', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'Value', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th><?php esc_html_e( 'City', 'digifalk-mafia-pbbg-engine' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $cars as $dfmg_car ) : ?>
			<?php $dfmg_here = (int) $dfmg_car['location_id'] === (int) $c->location_id; ?>
			<tr>
				<td><strong><?php echo esc_html( $dfmg_car['name'] ); ?></strong></td>
				<td><?php echo esc_html( $dfmg_car['damage'] . '%' ); ?></td>
				<td><?php echo esc_html( Format::money( $dfmg_car['worth'] ) ); ?></td>
				<td><?php echo esc_html( Locations::name( (int) $dfmg_car['location_id'] ) ); ?></td>
				<td class="dfmg-actions">
					<?php if ( $dfmg_here ) : ?>
						<?php echo wp_kses( $this->button( 'sell', __( 'Sell', 'digifalk-mafia-pbbg-engine' ), array( 'car' => $dfmg_car['id'] ) ), dfmg_allowed_html() ); ?>
						<?php echo wp_kses( $this->button( 'crush', sprintf( /* translators: %s: bullets */ __( 'Crush (%s bullets)', 'digifalk-mafia-pbbg-engine' ), Format::number( $dfmg_car['bullets'] ) ), array( 'car' => $dfmg_car['id'] ), 'dfmg-button dfmg-button--ghost' ), dfmg_allowed_html() ); ?>
						<?php if ( $dfmg_car['damage'] ) : ?>
							<?php echo wp_kses( $this->button( 'repair', sprintf( /* translators: %s: cost */ __( 'Repair (%s)', 'digifalk-mafia-pbbg-engine' ), Format::money( $dfmg_car['repair_cost'] ) ), array( 'car' => $dfmg_car['id'] ), 'dfmg-button dfmg-button--ghost' ), dfmg_allowed_html() ); ?>
						<?php endif; ?>
					<?php else : ?>
						<?php echo wp_kses( $this->button( 'ship', sprintf( /* translators: %s: cost */ __( 'Ship here (%s)', 'digifalk-mafia-pbbg-engine' ), Format::money( $dfmg_car['ship_cost'] ) ), array( 'car' => $dfmg_car['id'] ), 'dfmg-button dfmg-button--ghost' ), dfmg_allowed_html() ); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
