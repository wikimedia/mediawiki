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
		if( $this->data['message'] ) {
?>
	<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<h2><?php $this->msg('loginerror') ?>:</h2>
		<?php } ?>
		<?php $this->html('message') ?>
	</div>
	<div class="visualClear"></div>
<?php } ?>

<div id="userloginForm">
<form name="userlogin" method="post" action="<?php $this->text('action') ?>">
	<h2><?php $this->msg('login') ?></h2>
	<p id="userloginlink"><?php $this->html('link') ?></p>
	<div id="userloginprompt"><?php  $this->msgWiki('loginprompt') ?></div>
	<table>
		<tr>
			<td align='right'><label for='wpName1'><?php $this->msg('yourname') ?>:</label></td>
			<td align='left'>
				<input type='text' class='loginText' name="wpName" id="wpName1"
					value="<?php $this->text('name') ?>" size='20' />
			</td>
		</tr>
		<tr>
			<td align='right'><label for='wpPassword1'><?php $this->msg('yourpassword') ?>:</label></td>
			<td align='left'>
				<input type='password' class='loginPassword' name="wpPassword" id="wpPassword1"
					value="<?php $this->text('password') ?>" size='20' />
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
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>">
					<?php echo $doms ?>
				</select>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<td></td>
			<td align='left'>
				<input type='checkbox' name="wpRemember"
					value="1" id="wpRemember"
					<?php if( $this->data['remember'] ) { ?>checked="checked"<?php } ?>
					/> <label for="wpRemember"><?php $this->msg('remembermypassword') ?></label>
			</td>
		</tr>
		<tr>
			<td></td>
			<td align='left' style="white-space:nowrap">
				<input type='submit' name="wpLoginattempt" id="wpLoginattempt" value="<?php $this->msg('login') ?>" />&nbsp;<?php if( $this->data['useemail'] ) { ?><input type='submit' name="wpMailmypassword" id="wpMailmypassword"
									value="<?php $this->msg('mailmypassword') ?>" />
				<?php } ?>
			</td>
		</tr>
	</table>
</form>
</div>
<div id="loginend"><?php $this->msgWiki( 'loginend' ); ?></div>
<?php

	}
}

class UsercreateTemplate extends QuickTemplate {
	function execute() {
		if( $this->data['message'] ) {
?>
	<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<h2><?php $this->msg('loginerror') ?>:</h2>
		<?php } ?>
		<?php $this->html('message') ?>
	</div>
	<div class="visualClear"></div>
<?php } ?>
<div id="userlogin">

<?php if( $this->data['create'] ) { ?>
<form name="userlogin2" id="userlogin2" method="post" action="<?php $this->text('action') ?>">
	<h2><?php $this->msg('createaccount') ?></h2>
	<p id="userloginlink"><?php $this->html('link') ?></p>
	<table>
		<tr>
			<td align='right'><label for='wpName2'><?php $this->msg('yourname') ?>:</label></td>
			<td align='left'>
				<input type='text' class='loginText' name="wpName" id="wpName2"
					value="<?php $this->text('name') ?>" size='20' />
			</td>
		</tr>
		<tr>
			<td align='right'><label for='wpPassword2'><?php $this->msg('yourpassword') ?>:</label></td>
			<td align='left'>
				<input type='password' class='loginPassword' name="wpPassword" id="wpPassword2"
					value="<?php $this->text('password') ?>" size='20' />
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
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>">
					<?php echo $doms ?>
				</select>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<td align='right'><label for='wpRetype'><?php $this->msg('yourpasswordagain') ?>:</label></td>
			<td align='left'>
				<input type='password' class='loginPassword' name="wpRetype" id="wpRetype"
					value="<?php $this->text('retype') ?>"
					size='20' />
			</td>
		</tr>
		<tr>
			<?php if( $this->data['useemail'] ) { ?>
				<td align='right'><label for='wpEmail'><?php $this->msg('youremail') ?>:</label></td>
				<td align='left'>
					<input type='text' class='loginText' name="wpEmail" id="wpEmail"
						value="<?php $this->text('email') ?>" size='20' />
				</td>
			<?php } ?>
			<?php if( $this->data['userealname'] ) { ?>
				</tr>
				<tr>
					<td align='right'><label for='wpRealName'><?php $this->msg('yourrealname') ?>:</label></td>
					<td align='left'>
						<input type='text' class='loginText' name="wpRealName" id="wpRealName"
							value="<?php $this->text('realname') ?>" size='20' />
					</td>
			<?php } ?>
		</tr>
		<tr>
			<td></td>
			<td align='left'>
				<input type='checkbox' name="wpRemember"
					value="1" id="wpRemember"
					<?php if( $this->data['remember'] ) { ?>checked="checked"<?php } ?>
					/> <label for="wpRemember"><?php $this->msg('remembermypassword') ?></label>
			</td>
		</tr>
		<tr>
			<td></td>
			<td align='left'>
				<input type='submit' name="wpCreateaccount" id="wpCreateaccount"
					value="<?php $this->msg('createaccount') ?>" />
				<?php if( $this->data['createemail'] ) { ?>
				<input type='submit' name="wpCreateaccountMail" id="wpCreateaccountMail"
					value="<?php $this->msg('createaccountmail') ?>" />
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
	</table>
	<?php

		if( $this->data['useemail'] ) {
			echo '<div id="login-emailforlost">';
			$this->msgHtml( 'emailforlost' );
			echo '</div>';
		}

	?>
</form>
</div>
<div id="signupend"><?php $this->msgWiki( 'signupend' ); ?></div>
<?php

	}
}

?>
