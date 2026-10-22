<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var array                          $rows
 * @var bool                           $sent
 * @var int                            $paged
 * @var int                            $pages
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

use DigiFalk\MafiaPBBGEngine\Character;
use DigiFalk\MafiaPBBGEngine\Format;
use DigiFalk\MafiaPBBGEngine\Frontend\UI;

defined( 'ABSPATH' ) || exit;

echo wp_kses( $this->view( 'tabs', array( 'active' => $sent ? 'sent' : 'inbox' ) ), dfmg_allowed_html() );

if ( ! $rows ) {
	echo wp_kses( UI::empty_state( __( 'No messages.', 'digifalk-mafia-pbbg-engine' ) ), dfmg_allowed_html() );
	return;
}
?>
<?php echo wp_kses( $this->form( 'delete', $sent ? array( 'sent' => 1 ) : array() ), dfmg_allowed_html() ); ?>
	<table class="dfmg-table dfmg-messages">
		<thead>
			<tr>
				<th class="dfmg-col-check"></th>
				<th><?php echo $sent ? esc_html__( 'To', 'digifalk-mafia-pbbg-engine' ) : esc_html__( 'From', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Subject', 'digifalk-mafia-pbbg-engine' ); ?></th>
				<th><?php esc_html_e( 'Date', 'digifalk-mafia-pbbg-engine' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $rows as $dfmg_m ) : ?>
				<tr class="<?php echo ( ! $sent && ! $dfmg_m['is_read'] ) ? 'is-unread' : ''; ?>">
					<td class="dfmg-col-check"><input type="checkbox" name="ids[]" value="<?php echo esc_attr( $dfmg_m['id'] ); ?>"></td>
					<td><?php echo wp_kses( Character::link_by_id( (int) ( $sent ? $dfmg_m['recipient_id'] : $dfmg_m['sender_id'] ) ), dfmg_allowed_html() ); ?></td>
					<td><a href="<?php echo esc_url( $this->url( array( 'view' => 'read', 'id' => $dfmg_m['id'] ) ) ); ?>"><?php echo esc_html( $dfmg_m['subject'] ); ?></a></td>
					<td><small><?php echo esc_html( Format::ago( (int) $dfmg_m['created_at'] ) ); ?></small></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<button type="submit" class="dfmg-button dfmg-button--ghost dfmg-button--small"><?php esc_html_e( 'Delete selected', 'digifalk-mafia-pbbg-engine' ); ?></button>
</form>
<?php
if ( ! $sent ) {
	echo wp_kses( $this->button( 'read_all', __( 'Mark all as read', 'digifalk-mafia-pbbg-engine' ), array(), 'dfmg-button dfmg-button--ghost dfmg-button--small' ), dfmg_allowed_html() );
}
echo wp_kses( UI::pager( $this->id(), $sent ? array( 'view' => 'sent' ) : array(), $paged, $pages ), dfmg_allowed_html() );
