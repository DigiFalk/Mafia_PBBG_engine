<?php
/**
 * @var \DigiFalk\MafiaPBBGEngine\Character $c
 * @var string                         $to
 * @var string                         $subject
 * @var int                            $reply
 *
 * @package DigiFalk\MafiaPBBGEngine
 */

defined( 'ABSPATH' ) || exit;

echo wp_kses( $this->view( 'tabs', array( 'active' => 'compose' ) ), dfmg_allowed_html() );
?>
<div class="dfmg-card">
	<?php echo wp_kses( $this->form( 'send', array( 'reply' => $reply ), 'dfmg-form dfmg-form--stacked' ), dfmg_allowed_html() ); ?>
		<label><?php esc_html_e( 'To', 'digifalk-mafia-pbbg-engine' ); ?><input type="text" name="to" value="<?php echo esc_attr( $to ); ?>" required></label>
		<label><?php esc_html_e( 'Subject', 'digifalk-mafia-pbbg-engine' ); ?><input type="text" name="subject" maxlength="120" value="<?php echo esc_attr( $subject ); ?>"></label>
		<label><?php esc_html_e( 'Message', 'digifalk-mafia-pbbg-engine' ); ?><textarea name="body" rows="8" required></textarea></label>
		<button type="submit" class="dfmg-button"><?php esc_html_e( 'Send', 'digifalk-mafia-pbbg-engine' ); ?></button>
	</form>
</div>
