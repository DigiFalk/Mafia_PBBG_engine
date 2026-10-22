<?php
/**
 * Module Name: Profile
 * Description: Public player profiles, your own profile text and an uploaded avatar (saved as WebP and used as the WordPress avatar).
 * Version: 1.1.0
 * Author: DigiFalk
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

namespace DigiFalk\MafiaPBBGEngine\Modules;

use DigiFalk\MafiaPBBGEngine\Avatar;
use DigiFalk\MafiaPBBGEngine\Character;
use DigiFalk\MafiaPBBGEngine\DB;
use DigiFalk\MafiaPBBGEngine\Module\Module;

defined( 'ABSPATH' ) || exit;

final class Profile extends Module {

	public function title(): string {
		return __( 'Profile', 'digifalk-mafia-pbbg-engine' );
	}

	public function settings_fields(): array {
		return array(
			'avatar_upload' => array(
				'label'       => __( 'Players can upload an avatar', 'digifalk-mafia-pbbg-engine' ),
				'type'        => 'checkbox',
				'default'     => 1,
				'description' => __( 'Saved as a square .webp image and used as the WordPress avatar of the user on the whole site.', 'digifalk-mafia-pbbg-engine' ),
			),
			'avatar_max_kb' => array(
				'label'   => __( 'Maximum upload size (KB)', 'digifalk-mafia-pbbg-engine' ),
				'type'    => 'int',
				'default' => 2048,
			),
			'avatar_size'   => array(
				'label'   => __( 'Avatar size (pixels)', 'digifalk-mafia-pbbg-engine' ),
				'type'    => 'int',
				'default' => 256,
			),
		);
	}

	private function uploads_enabled(): bool {
		return (bool) $this->setting( 'avatar_upload' );
	}

	public function allowed_in_jail(): bool {
		return true;
	}

	public function allowed_in_hospital(): bool {
		return true;
	}

	public function menu( Character $c ): array {
		return array(
			array(
				'label' => __( 'My profile', 'digifalk-mafia-pbbg-engine' ),
				'group' => 'general',
				'order' => 30,
			),
		);
	}

	public function render( Character $c, array $query ): string {
		$name   = (string) ( $query['player'] ?? '' );
		$target = '' !== $name ? Character::find_by_name( $name ) : $c;
		if ( ! $target ) {
			$this->error( __( 'This player doesn\'t exist.', 'digifalk-mafia-pbbg-engine' ) );
			$target = $c;
		}
		$fields = array(
			__( 'Rank', 'digifalk-mafia-pbbg-engine' )    => esc_html( $target->rank_name() ),
			__( 'Wealth', 'digifalk-mafia-pbbg-engine' ) => esc_html( $target->wealth_title() ),
			__( 'Status', 'digifalk-mafia-pbbg-engine' )  => $target->is_alive()
				? ( $target->is_online() ? '<span class="dfmg-online">' . esc_html__( 'Online', 'digifalk-mafia-pbbg-engine' ) . '</span>' : esc_html__( 'Alive', 'digifalk-mafia-pbbg-engine' ) )
				: '<span class="dfmg-dead">' . esc_html__( 'Murdered', 'digifalk-mafia-pbbg-engine' ) . '</span>',
			__( 'Murders', 'digifalk-mafia-pbbg-engine' ) => (int) DB::value( "SELECT COUNT(*) FROM {activity} WHERE character_id = %d AND action = 'murder' AND success = 1", $target->id() ),
			__( 'Started', 'digifalk-mafia-pbbg-engine' ) => esc_html( \DigiFalk\MafiaPBBGEngine\Format::date( (int) $target->created_at ) ),
		);
		if ( ! $target->is_alive() && $target->shot_by ) {
			$fields[ __( 'Murdered by', 'digifalk-mafia-pbbg-engine' ) ] = Character::link_by_id( (int) $target->shot_by );
		}
		$own = $target->id() === $c->id();
		return $this->view(
			'profile',
			array(
				'c'       => $c,
				'target'  => $target,
				'own'     => $own,
				'fields'  => apply_filters( 'dfmg_profile_fields', $fields, $target, $c ),
				'actions' => $own
					? apply_filters( 'dfmg_profile_own_actions', array(), $c )
					: ( $target->is_alive() ? apply_filters( 'dfmg_profile_actions', array(), $target, $c ) : array() ),
				'avatar'  => get_avatar( (int) $target->user_id, 96 ),
				'upload'  => $own && $this->uploads_enabled(),
				'has_own' => $own && '' !== Avatar::url( (int) $c->user_id ),
				'max_kb'  => max( 1, (int) $this->setting( 'avatar_max_kb' ) ),
			)
		);
	}

	public function action_bio( Character $c, array $input ): void {
		$c->set( 'bio', wp_kses_post( mb_substr( (string) ( $input['bio'] ?? '' ), 0, 5000 ) ) );
		$this->success( __( 'Profile saved.', 'digifalk-mafia-pbbg-engine' ) );
	}

	public function action_avatar( Character $c, array $input ): void {
		if ( ! $this->uploads_enabled() ) {
			$this->error( __( 'Uploading an avatar is switched off.', 'digifalk-mafia-pbbg-engine' ) );
			return;
		}
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- nonce checked by Game::handle_action.
		$file = array();
		if ( isset( $_FILES['avatar']['tmp_name'], $_FILES['avatar']['name'] ) ) {
			// The file itself is checked by Avatar::save() (real upload, image type, size).
			$file = array(
				'name'     => sanitize_file_name( wp_unslash( $_FILES['avatar']['name'] ) ),
				'type'     => sanitize_mime_type( wp_unslash( $_FILES['avatar']['type'] ?? '' ) ),
				// Not unslashed: on Windows the temporary path contains backslashes.
				'tmp_name' => sanitize_text_field( $_FILES['avatar']['tmp_name'] ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				'error'    => absint( $_FILES['avatar']['error'] ?? 0 ),
				'size'     => absint( $_FILES['avatar']['size'] ?? 0 ),
			);
		}
		// phpcs:enable
		$result = Avatar::save( (int) $c->user_id, $file, max( 32, min( 1024, (int) $this->setting( 'avatar_size' ) ) ), max( 1, (int) $this->setting( 'avatar_max_kb' ) ) );
		if ( is_wp_error( $result ) ) {
			$this->error( $result->get_error_message() );
			return;
		}
		$this->success( __( 'Your new avatar is saved. It is also your profile picture on the rest of the site.', 'digifalk-mafia-pbbg-engine' ) );
	}

	public function action_avatar_remove( Character $c, array $input ): void {
		Avatar::delete( (int) $c->user_id );
		$this->success( __( 'Your avatar was removed.', 'digifalk-mafia-pbbg-engine' ) );
	}
}

return new Profile();
