<?php
/**
 * @package MediaWiki
 * @subpackage Templates
 */
if( !defined( 'MEDIAWIKI' ) ) die();

/** */
require_once( 'includes/SkinTemplate.php' );

/**
 * HTML template for Special:Userlogin form
 * @package MediaWiki
 * @subpackage Templates
 */
class UserloginTemplate extends QuickTemplate {
	function execute() {
		if( $this->data['error'] ) {
?>
	<h2><?php $this->msg('loginerror') ?>:</h2>
	<p class='error'><?php $this->html('error') ?></p>
<?php } else { ?>
	<h2><?php $this->msg('login'      ) ?>:</h2>
	<?php  $this->msgWiki('loginprompt') ?>
<?php } ?>
<form name="userlogin" id="userlogin" method="post" action="<?php $this->text('action') ?>">
	<table border='0'>
		<tr>
			<td align='right'><label for='wpName'><?php $this->msg('yourname') ?>:</label></td>
			<td align='left'>
				<input tabindex='1' type='text' name="wpName" id="wpName"
					value="<?php $this->text('name') ?>" size='20' />
			</td>
			<td align='left'>
				<input tabindex='3' type='checkbox' name="wpRemember"
					value="1" id="wpRemember"
					<?php if( $this->data['remember'] ) { ?>checked="checked"<?php } ?>
					/><label for="wpRemember"><?php $this->msg('remembermypassword') ?></label>
			</td>
		</tr>
		<tr>
			<td align='right'><label for='wpPassword'><?php $this->msg('yourpassword') ?>:</label></td>
			<td align='left'>
				<input tabindex='2' type='password' name="wpPassword" id="wpPassword"
					value="<?php $this->text('password') ?>" size='20' />
			</td>
			<td align='left'>
				<input tabindex='4' type='submit' name="wpLoginattempt"
					value="<?php $this->msg('login') ?>" />
			</td>
		</tr>
	<?php if( $this->data['usedomain'] ) {
		$doms = "";
		foreach( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<tr>
			<td align='right'><?php $this->msg( 'yourdomainname' ) ?>:</td>
			<td align='left'>
				<select tabindex='11' name="wpDomain" value="<?php $this->text( 'domain' ) ?>">
					<?php echo $doms ?>
				</select>
			</td>
		</tr>
	<?php } ?>
	<?php if( $this->data['create'] ) { ?>
		<tr>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td align='right'><label for='wpRetype'><?php $this->msg('yourpasswordagain') ?>:</label></td>
			<td align='left'>
				<input tabindex='5' type='password' name="wpRetype" id="wpRetype"
					value="<?php $this->text('retype') ?>" 
					size='20' />
			</td>
			<td><?php $this->msg('newusersonly') ?></td>
		</tr>
		<tr>
			<?php if( $this->data['useemail'] ) { ?>
				<td align='right'><label for='wpEmail'><?php $this->msg('youremail') ?>:</label></td>
				<td align='left'>
					<input tabindex='7' type='text' name="wpEmail" id="wpEmail"
						value="<?php $this->text('email') ?>" size='20' />
				</td>
			<?php } ?>
			<?php if( $this->data['userealname'] ) { ?>
				</tr>
				<tr>
					<td align='right'><label for='wpRealName'><?php $this->msg('yourrealname') ?>:</label></td>
					<td align='left'>
						<input tabindex='8' type='text' name="wpRealName" id="wpRealName"
							value="<?php $this->text('realname') ?>" size='20' />
					</td>
			<?php } ?>
			<td align='left'>
				<input tabindex='9' type='submit' name="wpCreateaccount"
					value="<?php $this->msg('createaccount') ?>" />
				<?php if( $this->data['createemail'] ) { ?>
				<input tabindex='6' type='submit' name="wpCreateaccountMail"
					value="<?php $this->msg('createaccountmail') ?>" />
				<?php } ?>
			</td>
		</tr>
	<?php if( $this->data['useemail'] ) { ?>
		<tr>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='3' align='left'>
				<p>
					<?php $this->msgHtml( 'emailforlost' ) ?><br />
					<input tabindex='10' type='submit' name="wpMailmypassword"
						value="<?php $this->msg('mailmypassword') ?>" />
				</p>
			</td>
		</tr>
	<?php } } ?>
	</table>
</form>
<?php
		$this->msgWiki( 'loginend' );
	}
}

?>
