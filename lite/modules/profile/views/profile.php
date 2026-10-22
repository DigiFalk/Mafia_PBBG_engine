<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var \DigiFalk\MafiaPBBGEngine\Character $target
 * @var bool                           $own
 * @var array                          $fields  Label => HTML.
 * @var array                          $actions HTML buttons.
 * @var string                         $avatar
 * @var bool                           $upload  Can upload an avatar.
 * @var bool                           $has_own Has an uploaded avatar.
 * @var int                            $max_kb
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Format;

defined( 'ABSPATH' ) || exit;
?>
<div class="dfmg-card dfmg-profile">
	<div class="dfmg-profile__head">
		<?php echo wp_kses( $avatar, dfmg_allowed_html() ); ?>
		<h3><?php echo wp_kses( $target->name_html(), dfmg_allowed_html() ); ?></h3>
	</div>
	<table class="dfmg-table dfmg-table--keyvalue">
		<?php foreach ( $fields as $dfmg_label => $dfmg_value ) : ?>
			<tr><th><?php echo esc_html( $dfmg_label ); ?></th><td><?php echo wp_kses_post( (string) $dfmg_value ); ?></td></tr>
		<?php endforeach; ?>
	</table>
	<?php if ( $actions ) : ?>
		<div class="dfmg-actions"><?php echo wp_kses( implode( ' ', $actions ), dfmg_allowed_html() ); ?></div>
	<?php endif; ?>
	<?php if ( $target->bio ) : ?>
		<div class="dfmg-usertext"><?php echo wp_kses( Format::user_text( (string) $target->bio ), dfmg_allowed_html() ); ?></div>
	<?php endif; ?>
</div>
<?php if ( $upload ) : ?>
	<div class="dfmg-card dfmg-avatar-card">
		<h3><?php esc_html_e( 'Avatar', 'digifalk-mafia-pbbg-engine' ); ?></h3>
		<div class="dfmg-avatar-edit">
			<div class="dfmg-avatar-edit__preview"><?php echo wp_kses( get_avatar( (int) $target->user_id, 96 ), dfmg_allowed_html() ); ?></div>
			<div class="dfmg-avatar-edit__form">
				<?php echo wp_kses( $this->form( 'avatar', array(), 'dfmg-form dfmg-form--stacked dfmg-avatar-form', true ), dfmg_allowed_html() ); ?>
					<label><?php esc_html_e( 'New picture', 'digifalk-mafia-pbbg-engine' ); ?>
						<input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" required data-max-kb="<?php echo esc_attr( (string) $max_kb ); ?>">
					</label>
					<p class="dfmg-muted">
						<?php
						/* translators: %s: maximum file size */
						echo esc_html( sprintf( __( 'JPG, PNG, GIF or WebP, at most %s. The picture is cropped to a square and also becomes your profile picture on the rest of the site.', 'digifalk-mafia-pbbg-engine' ), size_format( $max_kb * 1024 ) ) );
						?>
					</p>
					<button type="submit" class="dfmg-button"><?php esc_html_e( 'Upload avatar', 'digifalk-mafia-pbbg-engine' ); ?></button>
				</form>
				<?php if ( $has_own ) : ?>
					<?php echo wp_kses( $this->button( 'avatar_remove', __( 'Remove avatar', 'digifalk-mafia-pbbg-engine' ), array(), 'dfmg-button dfmg-button--ghost dfmg-button--small' ), dfmg_allowed_html() ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php if ( $own ) : ?>
	<div class="dfmg-card">
		<h3><?php esc_html_e( 'Edit profile text', 'digifalk-mafia-pbbg-engine' ); ?></h3>
		<?php echo wp_kses( $this->form( 'bio', array(), 'dfmg-form dfmg-form--stacked' ), dfmg_allowed_html() ); ?>
			<textarea name="bio" rows="6"><?php echo esc_textarea( (string) $target->bio ); ?></textarea>
			<p class="dfmg-muted"><?php esc_html_e( 'Allowed: bold, italic, links and lists.', 'digifalk-mafia-pbbg-engine' ); ?></p>
			<button type="submit" class="dfmg-button"><?php esc_html_e( 'Save', 'digifalk-mafia-pbbg-engine' ); ?></button>
		</form>
	</div>
<?php endif; ?>
