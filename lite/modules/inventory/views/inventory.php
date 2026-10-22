<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $slots
 * @var array                          $equipped
 * @var array                          $items
 * @var int                            $sell_pct
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;
use DigiFalk\MafiaPBBGEngine\Items;

defined( 'ABSPATH' ) || exit;
?>
<h3><?php esc_html_e( 'Equipment', 'digifalk-mafia-pbbg-engine' ); ?></h3>
<div class="dfmg-grid dfmg-grid--3">
	<?php foreach ( $slots as $dfmg_key => $dfmg_slot ) : ?>
		<?php $dfmg_item = $equipped[ $dfmg_key ] ?? null; ?>
		<div class="dfmg-card dfmg-slot">
			<h4><?php echo esc_html( $dfmg_slot['label'] ); ?></h4>
			<?php if ( $dfmg_item ) : ?>
				<p><strong><?php echo esc_html( $dfmg_item['name'] ); ?></strong></p>
				<ul class="dfmg-effects">
					<?php foreach ( Items::describe_effects( $dfmg_item ) as $dfmg_line ) : ?>
						<li><?php echo esc_html( $dfmg_line ); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php echo wp_kses( $this->button( 'unequip', __( 'Unequip', 'digifalk-mafia-pbbg-engine' ), array( 'slot' => $dfmg_key ), 'dfmg-button dfmg-button--ghost dfmg-button--small' ), dfmg_allowed_html() ); ?>
			<?php else : ?>
				<p class="dfmg-muted"><?php esc_html_e( 'Empty', 'digifalk-mafia-pbbg-engine' ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<p class="dfmg-muted">
	<?php
	/* translators: 1: attack, 2: defense, 3: health */
	printf( esc_html__( 'Attack %1$s · Defense %2$s · Max. health %3$s', 'digifalk-mafia-pbbg-engine' ), esc_html( (string) round( $c->attack_power() ) ), esc_html( (string) round( $c->defense_power() ) ), esc_html( Format::number( $c->max_health() ) ) );
	?>
</p>

<h3><?php esc_html_e( 'Belongings', 'digifalk-mafia-pbbg-engine' ); ?></h3>
<?php if ( ! $items ) : ?>
	<?php echo wp_kses( UI::empty_state( __( 'Your inventory is empty.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
<?php else : ?>
	<table class="dfmg-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Item', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Amount', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Effect', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $items as $dfmg_item ) : ?>
				<?php $dfmg_usage = Items::usage( $dfmg_item ); ?>
				<tr>
					<td><strong><?php echo esc_html( $dfmg_item['name'] ); ?></strong></td>
					<td><?php echo esc_html( Format::number( $dfmg_item['qty'] ) ); ?></td>
					<td><small><?php echo esc_html( implode( ', ', Items::describe_effects( $dfmg_item ) ) ); ?></small></td>
					<td class="dfmg-actions">
						<?php if ( 'equip' === $dfmg_usage ) : ?>
							<?php echo wp_kses( $this->button( 'equip', __( 'Equip', 'digifalk-mafia-pbbg-engine' ), array( 'item' => $dfmg_item['id'] ) ), dfmg_allowed_html() ); ?>
						<?php elseif ( 'use' === $dfmg_usage ) : ?>
							<?php echo wp_kses( $this->button( 'use', __( 'Use', 'digifalk-mafia-pbbg-engine' ), array( 'item' => $dfmg_item['id'] ) ), dfmg_allowed_html() ); ?>
						<?php endif; ?>
						<?php echo wp_kses( $this->button( 'sell', sprintf( /* translators: %s: money */ __( 'Sell (%s)', 'digifalk-mafia-pbbg-engine' ), Format::money( floor( $dfmg_item['price'] * $sell_pct / 100 ) ) ), array( 'item' => $dfmg_item['id'] ), 'dfmg-button dfmg-button--ghost dfmg-button--small' ), dfmg_allowed_html() ); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
