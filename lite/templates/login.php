<?php
/**
 * Shown to visitors that are not logged in.
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Frontend\Game;

defined( 'ABSPATH' ) || exit;
?>
<div class="dfmg-welcome dfmg-welcome--login">
	<div class="dfmg-card">
		<span class="dfmg-emblem"><?php echo wp_kses( \DigiFalk\MafiaPBBGEngine\Icons::svg( 'shield', 30 ), dfmg_allowed_html() ); ?></span>
		<h2><?php esc_html_e( 'Welcome to the underworld', 'digifalk-mafia-pbbg-engine' ); ?></h2>
		<p><?php esc_html_e( 'Commit crimes, steal cars, build a family and work your way up to Godfather. Log in to play.', 'digifalk-mafia-pbbg-engine' ); ?></p>
		<?php
		wp_login_form(
			array(
				'redirect' => Game::page_url(),
				'form_id'  => 'dfmg-login',
			)
		);
		?>
		<p class="dfmg-links">
			<?php if ( get_option( 'users_can_register' ) ) : ?>
				<a class="dfmg-button" href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Create account', 'digifalk-mafia-pbbg-engine' ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( wp_lostpassword_url( Game::page_url() ) ); ?>"><?php esc_html_e( 'Lost your password?', 'digifalk-mafia-pbbg-engine' ); ?></a>
		</p>
	</div>
	<?php do_action( 'dfmg_login_page' ); ?>
</div>
