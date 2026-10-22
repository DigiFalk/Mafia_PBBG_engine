<?php
/**
 * Game layout. The zones are filled with game elements chosen in
 * Appearance → Customize → Game layout (see Frontend\Layout and Frontend\Hud).
 *
 * @var \DigiFalk\MafiaPBBGEngine\Character     $character
 * @var \DigiFalk\MafiaPBBGEngine\Module\Module $module
 * @var array                              $menu
 * @var array                              $messages
 * @var string                             $content
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Frontend\Game;
use DigiFalk\MafiaPBBGEngine\Frontend\Layout;

defined( 'ABSPATH' ) || exit;

$dfmg_z = array();
foreach ( array_keys( Layout::zones() ) as $dfmg_zone ) {
	$dfmg_z[ $dfmg_zone ] = Layout::zone( $dfmg_zone );
}
$dfmg_header  = $dfmg_z['header-left'] . $dfmg_z['header-center'] . $dfmg_z['header-right'];
$dfmg_footer  = $dfmg_z['footer-left'] . $dfmg_z['footer-center'] . $dfmg_z['footer-right'];
$dfmg_sidebar = '' !== $dfmg_z['sidebar'];
$dfmg_credit  = \DigiFalk\MafiaPBBGEngine\Frontend\Credit::html();

$dfmg_zone_html = static function ( string $zone ) use ( $dfmg_z ): string {
	return '' === $dfmg_z[ $zone ] ? '' : '<div class="dfmg-zone dfmg-zone--' . esc_attr( $zone ) . '">' . $dfmg_z[ $zone ] . '</div>';
};
?>
<div class="dfmg-shell">
	<?php if ( '' !== $dfmg_header || $dfmg_sidebar ) : ?>
		<header class="dfmg-header<?php echo '' === $dfmg_header ? ' dfmg-header--toggle-only' : ''; ?>">
			<?php
			echo wp_kses( $dfmg_zone_html( 'header-left' ) . $dfmg_zone_html( 'header-center' ) . $dfmg_zone_html( 'header-right' ), dfmg_allowed_html() );
			?>
			<?php if ( $dfmg_sidebar ) : ?>
				<button type="button" class="dfmg-menu-toggle" aria-controls="dfmg-nav" aria-expanded="false"><span class="dfmg-menu-toggle__icon" aria-hidden="true"></span><span><?php esc_html_e( 'Menu', 'digifalk-mafia-pbbg-engine' ); ?></span></button>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="dfmg-body<?php echo $dfmg_sidebar ? '' : ' dfmg-body--full'; ?>">
		<?php if ( $dfmg_sidebar ) : ?>
			<aside class="dfmg-sidebar" id="dfmg-nav">
				<?php echo wp_kses( $dfmg_z['sidebar'], dfmg_allowed_html() ); ?>
			</aside>
		<?php endif; ?>

		<main class="dfmg-main">
			<?php echo wp_kses( $dfmg_zone_html( 'top' ), dfmg_allowed_html() ); ?>
			<div class="dfmg-page-head">
				<span class="dfmg-page-head__icon"><?php echo wp_kses( \DigiFalk\MafiaPBBGEngine\Icons::svg( \DigiFalk\MafiaPBBGEngine\Icons::has( $module->id() ) ? $module->id() : 'dot', 22 ), dfmg_allowed_html() ); ?></span>
				<h2 class="dfmg-title"><?php echo esc_html( $module->title() ); ?></h2>
			</div>
			<?php echo wp_kses( Game::template( 'messages', array( 'messages' => $messages ) ), dfmg_allowed_html() ); ?>
			<?php echo wp_kses( $content, dfmg_allowed_html() ); ?>
			<?php echo wp_kses( $dfmg_zone_html( 'bottom' ), dfmg_allowed_html() ); ?>
		</main>
	</div>
	<?php if ( '' !== $dfmg_footer || '' !== $dfmg_credit ) : ?>
		<footer class="dfmg-footer">
			<?php echo wp_kses( $dfmg_zone_html( 'footer-left' ) . $dfmg_zone_html( 'footer-center' ) . $dfmg_zone_html( 'footer-right' ) . $dfmg_credit, dfmg_allowed_html() ); ?>
		</footer>
	<?php endif; ?>
</div>
