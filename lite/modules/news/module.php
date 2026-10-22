<?php
/**
 * Module Name: News
 * Description: Game news as a custom post type in WordPress. Shown in the game and on the login page.
 * Version: 1.0.0
 * Author: DigiFalk
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

namespace DigiFalk\MafiaPBBGEngine\Modules;

use DigiFalk\MafiaPBBGEngine\Character;
use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;
use DigiFalk\MafiaPBBGEngine\Module\Module;

defined( 'ABSPATH' ) || exit;

final class News extends Module {

	const POST_TYPE = 'dfmg_news';

	public function title(): string {
		return __( 'News', 'digifalk-mafia-pbbg-engine' );
	}

	public function allowed_in_jail(): bool {
		return true;
	}

	public function allowed_in_hospital(): bool {
		return true;
	}

	public function boot(): void {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'dfmg_login_page', array( $this, 'login_news' ) );
	}

	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => __( 'Game news', 'digifalk-mafia-pbbg-engine' ),
					'singular_name' => __( 'News item', 'digifalk-mafia-pbbg-engine' ),
					'add_new_item'  => __( 'New news item', 'digifalk-mafia-pbbg-engine' ),
					'edit_item'     => __( 'Edit news item', 'digifalk-mafia-pbbg-engine' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => 'dfmg',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'author' ),
				'capability_type' => 'post',
			)
		);
	}

	public function menu( Character $c ): array {
		return array(
			array(
				'label' => __( 'News', 'digifalk-mafia-pbbg-engine' ),
				'group' => 'community',
				'order' => 5,
			),
		);
	}

	private function posts( int $count ): array {
		return get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => $count,
				'post_status'    => 'publish',
			)
		);
	}

	private function markup( array $posts, bool $full ): string {
		if ( ! $posts ) {
			return UI::empty_state( __( 'No news yet.', 'digifalk-mafia-pbbg-engine' ) );
		}
		$html = '';
		foreach ( $posts as $post ) {
			$body  = $full
				? apply_filters( 'the_content', $post->post_content ) // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- the WordPress core filter for post content.
				: wpautop( wp_trim_words( $post->post_content, 40 ) );
			$html .= '<article class="dfmg-card dfmg-news"><h3>' . esc_html( get_the_title( $post ) ) . '</h3>'
				. '<p class="dfmg-muted">' . esc_html( Format::date( (int) get_post_time( 'U', true, $post ) ) ) . '</p>'
				. '<div class="dfmg-usertext">' . wp_kses_post( $body ) . '</div></article>';
		}
		return $html;
	}

	public function login_news(): void {
		echo wp_kses( '<div class="dfmg-login-news"><h3>' . esc_html__( 'Latest news', 'digifalk-mafia-pbbg-engine' ) . '</h3>' . $this->markup( $this->posts( 3 ), false ) . '</div>', dfmg_allowed_html() );
	}

	public function render( Character $c, array $query ): string {
		return $this->markup( $this->posts( 10 ), true );
	}
}

return new News();
