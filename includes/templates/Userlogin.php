<?php

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
	<p><?php  $this->msg('loginprompt') ?></p>
<?php } ?>
<form name="userlogin" id="userlogin" method="post" action="<?php $this->text('action') ?>">
	<table border='0'>
		<tr>
			<td align='right'><?php $this->msg('yourname') ?>:</td>
			<td align='left'>
				<input tabindex='1' type='text' name="wpName"
					value="<?php $this->text('name') ?>" size='20' />
			</td>
			<td align='left'>
				<input tabindex='3' type='submit' name="wpLoginattempt"
					value="<?php $this->msg('login') ?>" />
			</td>
		</tr>
		<tr>
			<td align='right'><?php $this->msg('yourpassword') ?>:</td>
			<td align='left'>
				<input tabindex='2' type='password' name="wpPassword"
					value="<?php $this->text('password') ?>" size='20' />
			</td>
			<td align='left'>
				<input tabindex='4' type='checkbox' name="wpRemember"
					value="1" id="wpRemember"
					<?php if( $this->data['remember'] ) { ?>checked="checked"<?php } ?>
					/><label for="wpRemember"><?php $this->msg('remembermypassword') ?></label>
			</td>
		</tr>
	<?php if( $this->data['create'] ) { ?>
		<tr>
			<td colspan='3'>&nbsp;</td>
		</tr>
		<tr>
			<td align='right'><?php $this->msg('yourpasswordagain') ?>:</td>
			<td align='left'>
				<input tabindex='5' type='password' name="wpRetype"
					value="<?php $this->text('retype') ?>" 
					size='20' />
			</td>
			<td><?php $this->msg('newusersonly') ?></td>
		</tr>
		<tr>
			<?php if( $this->data['useemail'] ) { ?>
				<td align='right'><?php $this->msg('youremail') ?>:</td>
				<td align='left'>
					<input tabindex='7' type='text' name="wpEmail"
						value="<?php $this->text('email') ?>" size='20' />
				</td>
			<?php } ?>
			<?php if( $this->data['userealname'] ) { ?>
				</tr>
				<tr>
					<td align='right'><?php $this->msg('yourrealname') ?>:</td>
					<td align='left'>
						<input tabindex='8' type='text' name="wpRealName" 
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
	<?php } ?>
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
	<?php } ?>
	</table>
</form>
<?php
		$this->msgHtml( 'loginend' );
	}
}

?>