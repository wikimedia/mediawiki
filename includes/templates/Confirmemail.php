<?php
/**
 * @package MediaWiki
 * @subpackage Templates
 */
if( !defined( 'MEDIAWIKI' ) ) die();

/** */
require_once( 'includes/SkinTemplate.php' );

/**
 * HTML template for Special:Confirmemail form
 * @package MediaWiki
 * @subpackage Templates
 */
class ConfirmemailTemplate extends QuickTemplate {
	function execute() {
		if( $this->data['error'] ) {
?>
	<div class='error'><?php $this->msgWiki( $this->data['error']) ?></div>
<?php } else { ?>
	<div>
	<?php $this->msgWiki( 'confirmemail_text' ) ?>
	</div>
<?php } ?>
<form name="confirmemail" id="confirmemail" method="post" action="<?php $this->text('action') ?>">
	<input type="hidden" name="action" value="submit" />
	<input type="hidden" name="wpEditToken" value="<?php $this->text('edittoken') ?>" />
	<table border='0'>
		<tr>
			<td></td>
			<td><input type="submit" name="wpConfirm" value="<?php $this->msg('confirmemail_send') ?>" /></td>
		</tr>
	</table>
</form>
<?php
	}
}

?>