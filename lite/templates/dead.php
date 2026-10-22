<?php
/**
 * Shown when the character has been murdered.
 *
 * @var \DigiFalk\MafiaPBBGEngine\Character      $character
 * @var \DigiFalk\MafiaPBBGEngine\Character|null $killer
 * @var array                               $messages
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Frontend\Game;

defined( 'ABSPATH' ) || exit;
?>
<div class="dfmg-welcome dfmg-welcome--dead">
	<div class="dfmg-card dfmg-card--dead">
		<span class="dfmg-emblem"><?php echo wp_kses( \DigiFalk\MafiaPBBGEngine\Icons::svg( 'murder', 30 ), dfmg_allowed_html() ); ?></span>
		<?php echo wp_kses( Game::template( 'messages', array( 'messages' => $messages ) ), dfmg_allowed_html() ); ?>
		<h2><?php esc_html_e( 'Rest in peace', 'digifalk-mafia-pbbg-engine' ); ?></h2>
		<p>
			<?php
			if ( $killer ) {
				/* translators: 1: your character, 2: killer */
				echo wp_kses( sprintf( esc_html__( '%1$s was murdered by %2$s.', 'digifalk-mafia-pbbg-engine' ), '<strong>' . esc_html( $character->name ) . '</strong>', $killer->link() ), dfmg_allowed_html() );
			} else {
				/* translators: %s: your character */
				printf( esc_html__( '%s is no longer with us.', 'digifalk-mafia-pbbg-engine' ), '<strong>' . esc_html( $character->name ) . '</strong>' );
			}
			?>
		</p>
		<p><?php esc_html_e( 'Start again with a new character.', 'digifalk-mafia-pbbg-engine' ); ?></p>
		<?php echo wp_kses( Game::form_open( 'core', 'create_character' ), dfmg_allowed_html() ); ?>
			<label for="dfmg-name"><?php esc_html_e( 'New name', 'digifalk-mafia-pbbg-engine' ); ?></label>
			<input type="text" id="dfmg-name" name="name" minlength="3" maxlength="20" pattern="[A-Za-z0-9_\-]{3,20}" required>
			<button type="submit" class="dfmg-button"><?php esc_html_e( 'Start over', 'digifalk-mafia-pbbg-engine' ); ?></button>
		</form>
	</div>
</div>
