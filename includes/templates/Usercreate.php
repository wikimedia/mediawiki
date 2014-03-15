<?php
/**
 * Html form for account creation (since 1.22 with VForm appearance).
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

class UsercreateTemplate extends BaseTemplate {

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
	<?php if ( $this->haveData( 'languages' ) ) { ?>
		<div id="languagelinks">
			<p><?php $this->html( 'languages' ); ?></p>
		</div>
	<?php }
	      if ( !wfMessage( 'signupstart' )->isDisabled() ) { ?>
		<div id="signupstart"><?php $this->msgWiki( 'signupstart' ); ?></div>
	<?php } ?>
	<div id="userloginForm">
		<h2 class="createaccount-join">
			<?php $this->msg( $this->data['loggedin'] ? 'createacct-another-join' : 'createacct-join' ); ?>
		</h2>
		<form name="userlogin2" id="userlogin2" class="mw-ui-vform" method="post" action="<?php $this->text( 'action' ); ?>">
			<section class="mw-form-header">
				<?php $this->html( 'header' ); /* extensions such as ConfirmEdit add form HTML here */ ?>
			</section>
			<!-- This element is used by the mediawiki.special.userlogin.signup.js module. -->
			<div
				id="mw-createacct-status-area"
				<?php if ( $this->data['message'] ) { ?>
					class="<?php echo $this->data['messagetype']; ?>box"
				<?php } else { ?>
					style="display: none;"
				<?php } ?>
			>
			<?php if ( $this->data['message'] ) { ?>
					<?php if ( $this->data['messagetype'] == 'error' ) { ?>
						<strong><?php $this->msg( 'createacct-error' ); ?></strong>
						<br />
					<?php } ?>
					<?php $this->html( 'message' ); ?>
			<?php } ?>
			</div>

			<div>
				<label for='wpName2'>
					<?php $this->msg( 'userlogin-yourname' ); ?>

					<span class="mw-ui-flush-right"><?php echo $this->getMsg( 'createacct-helpusername' )->parse(); ?></span>
				</label>
				<?php
				echo Html::input( 'wpName', $this->data['name'], 'text', array(
					'class' => 'mw-input loginText',
					'id' => 'wpName2',
					'tabindex' => '1',
					'size' => '20',
					'required',
					'placeholder' => $this->getMsg( $this->data['loggedin'] ?
						'createacct-another-username-ph' : 'userlogin-yourname-ph' )->text(),
				) );
				?>
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
				<?php
				echo Html::input( 'wpPassword', null, 'password', array(
					'class' => 'mw-input loginPassword',
					'id' => 'wpPassword2',
					'tabindex' => '3',
					'size' => '20',
					'required',
					'placeholder' => $this->getMsg( 'createacct-yourpassword-ph' )->text()
				) + User::passwordChangeInputAttribs() );
				?>
			</div>

			<?php
			if ( $this->data['usedomain'] ) {
				$select = new XmlSelect( 'wpDomain', false, $this->data['domain'] );
				$select->setAttribute( 'tabindex', 4 );
				foreach ( $this->data['domainnames'] as $dom ) {
					$select->addOption( $dom );
				}
			?>
				<div id="mw-user-domain-section">
					<label for="wpDomain"><?php $this->msg( 'yourdomainname' ); ?></label>
					<div class="mw-input">
						<?php echo $select->getHTML(); ?>
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
							'required' => $this->data['emailrequired'],
							'placeholder' => $this->getMsg( $this->data['loggedin'] ?
								'createacct-another-email-ph' : 'createacct-email-ph' )->text()
						) );
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
						<?php $this->msgWiki( $this->data['loggedin'] ? 'createacct-another-realname-tip' : 'prefs-help-realname' ); ?>
					</div>
				</div>
			<?php } ?>

			<?php if ( $this->data['usereason'] ) { ?>
				<div>
					<label for='wpReason'><?php $this->msg( 'createacct-reason' ); ?></label>
					<?php echo Html::input( 'wpReason', $this->data['reason'], 'text', array(
						'class' => 'mw-input loginText',
						'id' => 'wpReason',
						'tabindex' => '8',
						'size' => '20',
						'placeholder' => $this->getMsg( 'createacct-reason-ph' )->text()
					) ); ?>
				</div>
			<?php } ?>

			<?php
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
								<?php $this->msgHtml( $inputItem['msg'] ); ?>
							</label>
						<?php
						} else {
							// Not a checkbox.
							// TODO (bug 31909) support other input types, e.g. select boxes.
						?>
							<?php if ( !empty( $inputItem['msg'] ) ) { ?>
								<label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>">
									<?php $this->msgWiki( $inputItem['msg'] ); ?>
								</label>
							<?php } ?>
							<input
								type="<?php echo htmlspecialchars( $inputItem['type'] ); ?>"
								class="mw-input"
								name="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
								tabindex="<?php echo $tabIndex++; ?>"
								value="<?php echo htmlspecialchars( $inputItem['value'] ); ?>"
								id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
							/>
						<?php } ?>
						<?php if ( $inputItem['helptext'] !== false ) { ?>
							<div class="prefsectiontip">
								<?php $this->msgWiki( $inputItem['helptext'] ); ?>
							</div>
						<?php } ?>
					</div>
				<?php
				}
			}

			// JS attempts to move the image CAPTCHA below this part of the form,
			// so skip one index.
			$tabIndex++;
			?>
			<div class="mw-submit">
				<?php
				echo Html::input(
					'wpCreateaccount',
					$this->getMsg( $this->data['loggedin'] ? 'createacct-another-submit' : 'createacct-submit' ),
					'submit',
					array(
						'class' => "mw-ui-button mw-ui-big mw-ui-block mw-ui-constructive",
						'id' => 'wpCreateaccount',
						'tabindex' => $tabIndex++
					)
				);
				?>
			</div>
			<?php if ( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
			<?php if ( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpCreateaccountToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
		</form>
		<?php if ( !wfMessage( 'signupend' )->isDisabled() ) { ?>
			<div id="signupend"><?php $this->html( 'signupend' ); ?></div>
		<?php } ?>
	</div>
	<div class="mw-createacct-benefits-container">
		<h2><?php $this->msg( 'createacct-benefit-heading' ); ?></h2>
		<div class="mw-createacct-benefits-list">
			<?php
			for ( $benefitIdx = 1; $benefitIdx <= $this->data['benefitCount']; $benefitIdx++ ) {
				// Pass each benefit's head text (by default a number) as a parameter to the body's message for PLURAL handling.
				$headUnescaped = $this->getMsg( "createacct-benefit-head$benefitIdx" )->text();
			?>
				<div class="mw-number-text <?php $this->msg( "createacct-benefit-icon$benefitIdx" ); ?>">
					<h3><?php $this->msg( "createacct-benefit-head$benefitIdx" ); ?></h3>
					<p><?php echo $this->getMsg( "createacct-benefit-body$benefitIdx" )->params( $headUnescaped )->escaped(); ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php

	}
}
