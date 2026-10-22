<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character   $c
 * @var array                            $quote
 * @var \DigiFalk\MafiaPBBGEngine\Character[] $patients
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;
?>
<div class="dfmg-card">
	<?php if ( $c->is_hospitalized() ) : ?>
		<?php echo wp_kses( UI::cooldown( __( 'You are being treated. Discharged in', 'digifalk-mafia-pbbg-engine' ), $c->timer( 'hospital' ) ), dfmg_allowed_html() ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Your health:', 'digifalk-mafia-pbbg-engine' ); ?></p>
		<?php echo wp_kses( UI::bar( $c->health_percent() ), dfmg_allowed_html() ); ?>
		<?php if ( $quote['cost'] || $quote['time'] ) : ?>
			<p>
				<?php
				/* translators: 1: cost, 2: duration */
				printf( esc_html__( 'A full recovery costs %1$s and takes %2$s.', 'digifalk-mafia-pbbg-engine' ), esc_html( Format::money( $quote['cost'] ) ), esc_html( Format::duration( $quote['time'] ) ) );
				?>
			</p>
			<?php echo wp_kses( $this->button( 'admit', __( 'Get admitted', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'You have no injuries.', 'digifalk-mafia-pbbg-engine' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>

<h3><?php esc_html_e( 'Patients', 'digifalk-mafia-pbbg-engine' ); ?></h3>
<?php if ( ! $patients ) : ?>
	<?php echo wp_kses( UI::empty_state( __( 'There are no patients.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
<?php else : ?>
	<table class="dfmg-table">
		<?php foreach ( $patients as $dfmg_p ) : ?>
			<tr><td><?php echo wp_kses( $dfmg_p->link(), dfmg_allowed_html() ); ?></td><td><?php echo wp_kses( Format::countdown( $dfmg_p->timer( 'hospital' ) ), dfmg_allowed_html() ); ?></td></tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>
