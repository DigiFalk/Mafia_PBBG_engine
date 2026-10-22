<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $crimes
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;

if ( $c->timer_active( 'crime' ) ) {
	echo wp_kses( UI::cooldown( __( 'You can commit a crime again in', 'digifalk-mafia-pbbg-engine' ), $c->timer( 'crime' ) ), dfmg_allowed_html() );
}
?>
<?php if ( ! $crimes ) : ?>
	<?php echo wp_kses( UI::empty_state( __( 'There are no crimes available yet.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
<?php else : ?>
	<table class="dfmg-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Crime', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Loot', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Cooldown', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Chance', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $crimes as $dfmg_crime ) : ?>
				<tr>
					<td>
						<strong><?php echo esc_html( $dfmg_crime['name'] ); ?></strong>
						<?php if ( $dfmg_crime['description'] ) : ?>
							<br><small class="dfmg-muted"><?php echo esc_html( $dfmg_crime['description'] ); ?></small>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html( Format::money( $dfmg_crime['min_money'] ) . ' – ' . Format::money( $dfmg_crime['max_money'] ) ); ?></td>
					<td><?php echo esc_html( Format::duration( (int) $dfmg_crime['cooldown'] ) ); ?></td>
					<td class="dfmg-col-bar"><?php echo wp_kses( UI::bar( (float) $dfmg_crime['skill'] ), dfmg_allowed_html() ); ?></td>
					<td><?php echo wp_kses( $this->button( 'commit', __( 'Commit', 'digifalk-mafia-pbbg-engine' ), array( 'crime' => $dfmg_crime['id'] ) ), dfmg_allowed_html() ); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
