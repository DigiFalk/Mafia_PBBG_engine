<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $inmates
 * @var int                            $bail
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;

if ( $c->is_jailed() ) {
	echo wp_kses( UI::cooldown( $c->is_in_supermax() ? __( 'You are in solitary confinement. Free in', 'digifalk-mafia-pbbg-engine' ) : __( 'You are in jail. Free in', 'digifalk-mafia-pbbg-engine' ), $c->timer( 'jail' ) ), dfmg_allowed_html() );
	if ( $bail ) {
		echo wp_kses( '<p>' . $this->button( 'bail', sprintf( /* translators: %s: money */ __( 'Pay bail (%s)', 'digifalk-mafia-pbbg-engine' ), Format::money( $bail ) ) ) . '</p>', dfmg_allowed_html() );
	}
}
?>
<h3>
	<?php
	/* translators: %s: city */
	printf( esc_html__( 'Inmates in %s', 'digifalk-mafia-pbbg-engine' ), esc_html( $c->location_name() ) );
	?>
</h3>
<?php if ( ! $inmates ) : ?>
	<?php echo wp_kses( UI::empty_state( __( 'The cells are empty.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
<?php else : ?>
	<table class="dfmg-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Inmate', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Rank', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Free in', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Chance', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $inmates as $dfmg_row ) : ?>
				<?php $dfmg_inmate = $dfmg_row['character']; ?>
				<tr>
					<td><?php echo wp_kses( $dfmg_inmate->link(), dfmg_allowed_html() ); ?><?php echo $dfmg_row['supermax'] ? ' <em class="dfmg-tag">' . esc_html__( 'solitary', 'digifalk-mafia-pbbg-engine' ) . '</em>' : ''; ?></td>
					<td><?php echo esc_html( $dfmg_inmate->rank_name() ); ?></td>
					<td><?php echo wp_kses( Format::countdown( $dfmg_inmate->timer( 'jail' ) ), dfmg_allowed_html() ); ?></td>
					<td><?php echo esc_html( $dfmg_row['chance'] . '%' ); ?></td>
					<td>
						<?php if ( $dfmg_row['chance'] ) : ?>
							<?php echo wp_kses( $this->button( 'bust', $dfmg_inmate->id() === $c->id() ? __( 'Escape', 'digifalk-mafia-pbbg-engine' ) : __( 'Break out', 'digifalk-mafia-pbbg-engine' ), array( 'target' => $dfmg_inmate->id() ) ), dfmg_allowed_html() ); ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
