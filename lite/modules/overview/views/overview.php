<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array|null                     $next
 * @var array                          $timers
 * @var array                          $notifications
 * @var array                          $panels  List of [ 'title' => , 'html' => ] added by other modules.
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\Hud;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;
use DigiFalk\MafiaPBBGEngine\Icons;

defined( 'ABSPATH' ) || exit;

$dfmg_tiles = array(
	array( 'cash', __( 'Cash', 'digifalk-mafia-pbbg-engine' ), Format::money( $c->money ) ),
	array( 'bank', __( 'Bank', 'digifalk-mafia-pbbg-engine' ), Format::money( $c->bank ) ),
	array( 'bullets', __( 'Bullets', 'digifalk-mafia-pbbg-engine' ), Format::number( $c->bullets ) ),
	array( 'power', __( 'Attack / defense', 'digifalk-mafia-pbbg-engine' ), round( $c->attack_power() ) . ' / ' . round( $c->defense_power() ) ),
	array( 'city', __( 'City', 'digifalk-mafia-pbbg-engine' ), $c->location_name() ),
	array( 'wealth', __( 'Wealth', 'digifalk-mafia-pbbg-engine' ), $c->wealth_title() ),
);
$dfmg_ready = 0;
foreach ( $timers as $dfmg_timer ) {
	$dfmg_ready += (int) $dfmg_timer['expires'] <= time() ? 1 : 0;
}
?>
<section class="dfmg-hero">
	<div class="dfmg-hero__who">
		<?php echo wp_kses( Hud::avatar_ring( $c, 72 ), dfmg_allowed_html() ); ?>
		<div>
			<p class="dfmg-hero__eyebrow"><?php echo esc_html( $c->rank_name() ); ?></p>
			<h3 class="dfmg-hero__name"><?php echo esc_html( $c->name ); ?></h3>
			<p class="dfmg-hero__meta"><?php echo wp_kses( Icons::svg( 'calendar', 14 ), dfmg_allowed_html() ); ?> <?php /* translators: %s: date */ echo esc_html( sprintf( __( 'Playing since %s', 'digifalk-mafia-pbbg-engine' ), Format::date( (int) $c->created_at ) ) ); ?></p>
		</div>
	</div>
	<div class="dfmg-hero__bars">
		<div class="dfmg-meter">
			<div class="dfmg-meter__head"><span><?php echo wp_kses( Icons::svg( 'rank', 14 ), dfmg_allowed_html() ); ?> <?php echo esc_html( $next ? sprintf( /* translators: %s: rank */ __( 'Next rank: %s', 'digifalk-mafia-pbbg-engine' ), $next['name'] ) : __( 'Highest rank reached', 'digifalk-mafia-pbbg-engine' ) ); ?></span><strong><?php echo esc_html( $c->rank_progress() . '%' ); ?></strong></div>
			<?php echo wp_kses( UI::bar( $c->rank_progress(), '', true ), dfmg_allowed_html() ); ?>
		</div>
		<div class="dfmg-meter dfmg-meter--health">
			<div class="dfmg-meter__head"><span><?php echo wp_kses( Icons::svg( 'health', 14 ), dfmg_allowed_html() ); ?> <?php esc_html_e( 'Health', 'digifalk-mafia-pbbg-engine' ); ?></span><strong><?php echo esc_html( $c->health_percent() . '%' ); ?></strong></div>
			<?php echo wp_kses( UI::bar( $c->health_percent(), '', true ), dfmg_allowed_html() ); ?>
		</div>
	</div>
</section>

<div class="dfmg-tiles">
	<?php foreach ( $dfmg_tiles as $dfmg_tile ) : ?>
		<div class="dfmg-tile">
			<span class="dfmg-tile__icon"><?php echo wp_kses( Icons::svg( $dfmg_tile[0], 20 ), dfmg_allowed_html() ); ?></span>
			<span class="dfmg-tile__label"><?php echo esc_html( $dfmg_tile[1] ); ?></span>
			<strong class="dfmg-tile__value"><?php echo esc_html( (string) $dfmg_tile[2] ); ?></strong>
		</div>
	<?php endforeach; ?>
</div>

<div class="dfmg-grid dfmg-grid--2">
	<section class="dfmg-card">
		<h3 class="dfmg-card__title"><?php echo wp_kses( Icons::svg( 'timer', 18 ), dfmg_allowed_html() ); ?> <?php esc_html_e( 'Timers', 'digifalk-mafia-pbbg-engine' ); ?>
			<?php if ( $timers ) : ?>
				<?php /* translators: 1: ready timers, 2: all timers */ ?>
				<span class="dfmg-pill"><?php echo esc_html( sprintf( __( '%1$d of %2$d ready', 'digifalk-mafia-pbbg-engine' ), $dfmg_ready, count( $timers ) ) ); ?></span>
			<?php endif; ?>
		</h3>
		<?php if ( ! $timers ) : ?>
			<?php echo wp_kses( UI::empty_state( __( 'No timers.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
		<?php else : ?>
			<ul class="dfmg-timerlist">
				<?php foreach ( $timers as $dfmg_timer ) : ?>
					<li>
						<a href="<?php echo esc_url( $dfmg_timer['url'] ); ?>">
							<span class="dfmg-timerlist__icon"><?php echo wp_kses( Icons::svg( $dfmg_timer['icon'] ?? 'timer', 16 ), dfmg_allowed_html() ); ?></span>
							<span class="dfmg-timerlist__label"><?php echo esc_html( $dfmg_timer['label'] ); ?></span>
							<?php echo wp_kses( Format::countdown( (int) $dfmg_timer['expires'] ), dfmg_allowed_html() ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</section>

	<section class="dfmg-card">
		<h3 class="dfmg-card__title"><?php echo wp_kses( Icons::svg( 'notifications', 18 ), dfmg_allowed_html() ); ?> <?php esc_html_e( 'Latest notifications', 'digifalk-mafia-pbbg-engine' ); ?></h3>
		<?php if ( ! $notifications ) : ?>
			<?php echo wp_kses( UI::empty_state( __( 'No notifications yet.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() ); ?>
		<?php else : ?>
			<ul class="dfmg-feed">
				<?php foreach ( $notifications as $dfmg_n ) : ?>
					<li class="<?php echo $dfmg_n['is_read'] ? '' : 'is-unread'; ?>">
						<div><?php echo wp_kses_post( $dfmg_n['message'] ); ?></div>
						<small><?php echo esc_html( Format::ago( (int) $dfmg_n['created_at'] ) ); ?></small>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</section>

	<?php foreach ( $panels as $dfmg_panel ) : ?>
		<section class="dfmg-card">
			<h3 class="dfmg-card__title"><?php echo esc_html( $dfmg_panel['title'] ); ?></h3>
			<?php echo wp_kses( $dfmg_panel['html'], dfmg_allowed_html() ); ?>
		</section>
	<?php endforeach; ?>
</div>
