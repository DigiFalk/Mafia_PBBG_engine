<?php
/**
 * First visit: choose a character name.
 *
 * @var array $messages
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Frontend\Game;

defined( 'ABSPATH' ) || exit;
?>
<div class="dfmg-welcome dfmg-welcome--create-character">
	<div class="dfmg-card">
		<span class="dfmg-emblem"><?php echo wp_kses( \DigiFalk\MafiaPBBGEngine\Icons::svg( 'profile', 30 ), dfmg_allowed_html() ); ?></span>
		<?php echo wp_kses( Game::template( 'messages', array( 'messages' => $messages ) ), dfmg_allowed_html() ); ?>
		<h2><?php esc_html_e( 'Choose your gangster name', 'digifalk-mafia-pbbg-engine' ); ?></h2>
		<p><?php esc_html_e( 'The city will know you by this name. Choose well: it can\'t be changed.', 'digifalk-mafia-pbbg-engine' ); ?></p>
		<?php echo wp_kses( Game::form_open( 'core', 'create_character' ), dfmg_allowed_html() ); ?>
			<label for="dfmg-name"><?php esc_html_e( 'Name', 'digifalk-mafia-pbbg-engine' ); ?></label>
			<input type="text" id="dfmg-name" name="name" minlength="3" maxlength="20" pattern="[A-Za-z0-9_\-]{3,20}" required>
			<button type="submit" class="dfmg-button"><?php esc_html_e( 'Start playing', 'digifalk-mafia-pbbg-engine' ); ?></button>
		</form>
	</div>
</div>
