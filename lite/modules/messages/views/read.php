<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $message
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Character;
use DigiFalk\MafiaPBBGEngine\Format;

defined( 'ABSPATH' ) || exit;

$dfmg_incoming = (int) $message['recipient_id'] === $c->id();
echo wp_kses( $this->view( 'tabs', array( 'active' => $dfmg_incoming ? 'inbox' : 'sent' ) ), dfmg_allowed_html() );
?>
<article class="dfmg-card dfmg-message">
	<h3><?php echo esc_html( $message['subject'] ); ?></h3>
	<p class="dfmg-muted">
		<?php
		/* translators: 1: sender, 2: recipient, 3: date */
		echo wp_kses( sprintf( esc_html__( 'From %1$s to %2$s, %3$s', 'digifalk-mafia-pbbg-engine' ), Character::link_by_id( (int) $message['sender_id'] ), Character::link_by_id( (int) $message['recipient_id'] ), esc_html( Format::date( (int) $message['created_at'] ) ) ), dfmg_allowed_html() );
		?>
	</p>
	<div class="dfmg-usertext"><?php echo wp_kses_post( wpautop( esc_html( $message['body'] ) ) ); ?></div>
	<div class="dfmg-actions">
		<?php if ( $dfmg_incoming ) : ?>
			<a class="dfmg-button" href="<?php echo esc_url( $this->url( array( 'view' => 'compose', 'reply' => $message['id'] ) ) ); ?>"><?php esc_html_e( 'Reply', 'digifalk-mafia-pbbg-engine' ); ?></a>
		<?php endif; ?>
		<?php echo wp_kses( $this->button( 'delete', __( 'Delete', 'digifalk-mafia-pbbg-engine' ), array( 'id' => $message['id'], 'sent' => $dfmg_incoming ? 0 : 1 ), 'dfmg-button dfmg-button--ghost' ), dfmg_allowed_html() ); ?>
	</div>
</article>
