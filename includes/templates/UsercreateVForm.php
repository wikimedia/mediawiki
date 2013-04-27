<?php
/**
 * Html form for account creation with new VForm appearance.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Templates
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * Html form for create account with new VForm appearance.
 *
 * @since 1.22
 */
class UsercreateTemplateVForm extends BaseTemplate {

	/**
	 * Extensions (AntiSpoof and TitleBlacklist) call this in response to
	 * UserCreateForm hook to add checkboxes to the create account form.
	 */
	function addInputItem( $name, $value, $type, $msg, $helptext = false ) {
		$this->data['extraInput'][] = array(
			'name' => $name,
			'value' => $value,
			'type' => $type,
			'msg' => $msg,
			'helptext' => $helptext,
		);
	}

	function execute() {
		global $wgCookieExpiration;
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
?>
<div class="mw-ui-container">
	<?php
	if ( $this->haveData( 'languages' ) ) {
	?>
		<div id="languagelinks">
			<p><?php $this->html( 'languages' ); ?></p>
		</div>
	<?php
	}
	?>
<div id="userloginForm">
<h2 class="createaccount-join"><?php $this->msg( 'createacct-join' ); ?></h2>
<form name="userlogin2" id="userlogin2" class="mw-ui-vform" method="post" action="<?php $this->text( 'action' ); ?>">
	<section class="mw-form-header">
		<?php $this->html( 'header' ); /* extensions such as ConfirmEdit add form HTML here */ ?>
	</section>
	<?php
	if ( $this->data['message'] ) {
?>
		<div class="<?php $this->text( 'messagetype' ); ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<strong><?php $this->msg( 'createacct-error' ); ?></strong><br />
		<?php } ?>
		<?php $this->html( 'message' ); ?>
		</div>
	<?php } ?>
		<div>
			<label for='wpName2'>
				<?php $this->msg( 'userlogin-yourname' ); ?>

				<span class="mw-ui-flush-right"><?php echo $this->getMsg( 'createacct-helpusername-link' )->parse(); ?></span>
			</label>
			<?php echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'mw-input loginText',
				'id' => 'wpName2',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'placeholder' => $this->getMsg( 'userlogin-yourname-ph' )->text(),
				'autofocus'
			) ); ?>
		</div>
		<div>
		<?php if ( $this->data['createemail'] ) { ?>
			<label class="mw-ui-checkbox-label">
				<input name="wpCreateaccountMail" type="checkbox" value="1" id="wpCreateaccountMail" tabindex="2"
					<?php if ( $this->data['createemailset'] ) {
						echo 'checked="checked"';
					} ?>
				>
				<?php $this->msg( 'createaccountmail' ); ?>
			</label>
		<?php } ?>
		</div>
		<div class="mw-row-password">
			<label for='wpPassword2'><?php $this->msg( 'userlogin-yourpassword' ); ?></label>
			<?php echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'mw-input loginPassword',
				'id' => 'wpPassword2',
				'tabindex' => '3',
				'size' => '20',
				'required',
				'placeholder' => $this->getMsg( 'createacct-yourpassword-ph' )->text()
			) + User::passwordChangeInputAttribs() ); ?>
		</div>
	<?php if ( $this->data['usedomain'] ) {
		$doms = "";
		foreach ( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<div>
			<label><?php $this->msg( 'yourdomainname' ); ?></label>
			<div class="mw-input">
				<select name="wpDomain" value="<?php $this->text( 'domain' ); ?>"
					tabindex="4">
					<?php echo $doms ?>
				</select>
			</div>
		</div>
	<?php } ?>
		<div class="mw-row-password">
			<label for='wpRetype'><?php $this->msg( 'createacct-yourpasswordagain' ); ?></label>
			<?php
			echo Html::input( 'wpRetype', null, 'password', array(
				'class' => 'mw-input loginPassword',
				'id' => 'wpRetype',
				'tabindex' => '5',
				'size' => '20',
				'required',
				'placeholder' => $this->getMsg( 'createacct-yourpasswordagain-ph' )->text()
				) + User::passwordChangeInputAttribs() );
			?>
		</div>
		<div>
		<?php if ( $this->data['useemail'] ) { ?>
			<label for='wpEmail'>
				<?php
					$this->msg( $this->data['emailrequired'] ?
						'createacct-emailrequired' :
						'createacct-emailoptional'
					);
				?>
			</label>
			<?php
				echo Html::input( 'wpEmail', $this->data['email'], 'email', array(
					'class' => 'mw-input loginText',
					'id' => 'wpEmail',
					'tabindex' => '6',
					'size' => '20',
					'placeholder' => $this->getMsg( 'createacct-email-ph' )->text()
				) + ( $this->data['emailrequired'] ? array() : array( 'required' => '' ) ) );
			?>
			<?php
			// VForm eliminates the prefsectiontip div tip:
			// prefs-help-email-required is redundant with the placeholder text
			// Doesn't show the wordy prefs-help-email
			// Doesn't show the wordy prefs-help-email-others
			?>
		<?php } ?>
		</div>
		<?php if ( $this->data['userealname'] ) { ?>
			<div>
				<label for='wpRealName'><?php $this->msg( 'createacct-realname' ); ?></label>
				<input type='text' class='mw-input loginText' name="wpRealName" id="wpRealName"
					tabindex="7"
					value="<?php $this->text( 'realname' ); ?>" size='20' />
				<div class="prefsectiontip">
					<?php $this->msgWiki( 'prefs-help-realname' ); ?>
				</div>
			</div>
		<?php }
		if ( $this->data['usereason'] ) { ?>
			<div>
				<label for='wpReason'><?php $this->msg( 'createacct-reason' ); ?></label>
				<input type='text' class='mw-input loginText' name="wpReason" id="wpReason"
						tabindex="8"
						value="<?php $this->text( 'reason' ); ?>" size='20' />
			</div>
		<?php }
		$tabIndex = 9;
		if ( isset( $this->data['extraInput'] ) && is_array( $this->data['extraInput'] ) ) {
			foreach ( $this->data['extraInput'] as $inputItem ) { ?>
			<div>
				<?php
				// If it's a checkbox, output the whole thing (assume it has a msg).
				if ( $inputItem['type'] == 'checkbox' ) {
				?>
					<label class="mw-ui-checkbox-label">
						<input
							name="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
							id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
							type="checkbox" value="1"
							tabindex="<?php echo $tabIndex++; ?>"
							<?php if ( !empty( $inputItem['value'] ) ) {
								echo 'checked="checked"';
							} ?>
						>
						<?php $this->msg( $inputItem['msg'] ); ?>
					</label>
				<?php
				} else {
					// Not a checkbox.
					if ( !empty( $inputItem['msg'] ) ) {
						// Output the message label
					?>
						<label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>">
							<?php $this->msgWiki( $inputItem['msg'] ); ?>
						</label>
					<?php
					}
					?>
					<input
						type="<?php echo htmlspecialchars( $inputItem['type'] ); ?>"
						class="mw-input"
						name="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
						tabindex="<?php echo $tabIndex++; ?>"
						value="<?php echo htmlspecialchars( $inputItem['value'] ); ?>"
						id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
					/>
				<?php
				}
				if ( $inputItem['helptext'] !== false ) {
				?>
					<div class="prefsectiontip">
						<?php $this->msgWiki( $inputItem['helptext'] ); ?>
					</div>
				<?php
				}
				?>
				</div>
			<?php
			}
		}
		// JS attempts to move the image CAPTCHA below this part of the form,
		// so skip one index.
		$tabIndex++;
		?>
		<div class="mw-submit">
			<input type='submit' class="mw-ui-button mw-ui-big mw-ui-block mw-ui-primary" name="wpCreateaccount" id="wpCreateaccount"
				tabindex="<?php echo $tabIndex++; ?>"
				value="<?php $this->msg( 'createaccount' ); ?>" />
		</div>
	<input type="hidden" id="useNew" name="useNew" value="1" />
<?php if ( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if ( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpCreateaccountToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
<div class="mw-createacct-benefits-container">
	<h2><?php $this->msg( 'createacct-benefit-heading' ); ?></h2>
	<div class="mw-createacct-benefits-list">
		<div>
			<div class="mw-benefits-icon <?php $this->msg( 'createacct-benefit-icon1' ); ?>"></div>
			<div class="mw-number-text">
				<h3><?php $this->msg( 'createacct-benefit-head1' ); ?></h3>
				<p><?php $this->msg( 'createacct-benefit-body1' ); ?></p>
			</div>
		</div>
		<div>
			<div class="mw-benefits-icon <?php $this->msg( 'createacct-benefit-icon2' ); ?>"></div>
			<div class="mw-number-text">
				<h3><?php $this->msg( 'createacct-benefit-head2' ); ?></h3>
				<p><?php $this->msg( 'createacct-benefit-body2' ); ?></p>
			</div>
		</div>
		<div>
			<div class="mw-benefits-icon <?php $this->msg( 'createacct-benefit-icon3' ); ?>"></div>
			<div class="mw-number-text">
				<h3><?php $this->msg( 'createacct-benefit-head3' ); ?></h3>
				<p><?php $this->msg( 'createacct-benefit-body3' ); ?></p>
			</div>
		</div>
	</div>
</div>
</div>
<?php

	}
}
