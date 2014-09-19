<?php
// @codingStandardsIgnoreFile
/**
 * Html form for user login (since 1.22 with VForm appearance).
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

class UserloginTemplate extends BaseTemplate {

	function execute() {
		global $wgCookieExpiration;
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
?>
<div class="mw-ui-container">
	<div id="userloginprompt"><?php $this->msgWiki('loginprompt') ?></div>
	<?php if ( $this->haveData( 'languages' ) ) { ?>
		<div id="languagelinks">
			<p><?php $this->html( 'languages' ); ?></p>
		</div>
	<?php } ?>
	<div id="userloginForm">
		<form name="userlogin" class="mw-ui-vform" method="post" action="<?php $this->text( 'action' ); ?>">
			<?php if ( $this->data['loggedin'] ) { ?>
				<div class="warningbox">
					<?php echo $this->getMsg( 'userlogin-loggedin' )->params( $this->data['loggedinuser'] )->parse(); ?>
				</div>
			<?php } ?>
			<section class="mw-form-header">
				<?php $this->html( 'header' ); /* extensions such as ConfirmEdit add form HTML here */ ?>
			</section>

			<?php if ( $this->data['message'] ) { ?>
				<div class="<?php $this->text( 'messagetype' ); ?>box">
					<?php if ( $this->data['messagetype'] == 'error' ) { ?>
						<strong><?php $this->msg( 'loginerror' ); ?></strong>
						<br />
					<?php } ?>
					<?php $this->html( 'message' ); ?>
				</div>
			<?php } ?>

			<div class="mw-ui-vform-field">
				<label for='wpName1'>
					<?php
					$this->msg( 'userlogin-yourname' );

					if ( $this->data['secureLoginUrl'] ) {
						echo Html::element( 'a', array(
							'href' => $this->data['secureLoginUrl'],
							'class' => 'mw-ui-flush-right mw-secure',
						), $this->getMsg( 'userlogin-signwithsecure' )->text() );
					}
					?>
				</label>
				<?php
				$extraAttrs = array();
				echo Html::input( 'wpName', $this->data['name'], 'text', array(
					'class' => 'loginText mw-ui-input',
					'id' => 'wpName1',
					'tabindex' => '1',
					'size' => '20',
					// 'required' is blacklisted for now in Html.php due to browser issues.
					// Keeping here in case that changes.
					'required' => true,
					// Set focus to this field if it's blank.
					'autofocus' => !$this->data['name'],
					'placeholder' => $this->getMsg( 'userlogin-yourname-ph' )->text()
				) );
				?>
			</div>

			<div class="mw-ui-vform-field">
				<label for='wpPassword1'>
					<?php
					$this->msg( 'userlogin-yourpassword' );

					if ( $this->data['useemail'] && $this->data['canreset'] && $this->data['resetlink'] === true ) {
						echo ' ' . Linker::link(
							SpecialPage::getTitleFor( 'PasswordReset' ),
							$this->getMsg( 'userlogin-resetpassword-link' )->parse(),
							array( 'class' => 'mw-ui-flush-right' )
						);
					}
					?>
				</label>
				<?php
				echo Html::input( 'wpPassword', null, 'password', array(
					'class' => 'loginPassword mw-ui-input',
					'id' => 'wpPassword1',
					'tabindex' => '2',
					'size' => '20',
					// Set focus to this field if username is filled in.
					'autofocus' => (bool)$this->data['name'],
					'placeholder' => $this->getMsg( 'userlogin-yourpassword-ph' )->text()
				) );
				?>
			</div>

			<?php
			if ( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
				$select = new XmlSelect( 'wpDomain', false, $this->data['domain'] );
				$select->setAttribute( 'tabindex', 3 );
				foreach ( $this->data['domainnames'] as $dom ) {
					$select->addOption( $dom );
				}
			?>
				<div class="mw-ui-vform-field" id="mw-user-domain-section">
					<label for='wpDomain'><?php $this->msg( 'yourdomainname' ); ?></label>
					<?php echo $select->getHTML(); ?>
				</div>
			<?php } ?>

			<?php
			if ( $this->haveData( 'extrafields' ) ) {
				echo $this->data['extrafields'];
			}
			?>

			<div class="mw-ui-vform-field">
				<?php if ( $this->data['canremember'] ) { ?>
					<div class="mw-ui-checkbox">
						<input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4"
							<?php if ( $this->data['remember'] ) {
								echo 'checked="checked"';
							} ?>
						><label for="wpRemember">
							<?php echo $this->getMsg( 'userlogin-remembermypassword' )->numParams( $expirationDays )->escaped(); ?></label>
					</div>
				<?php } ?>
			</div>

			<div class="mw-ui-vform-field">
				<?php
				echo Html::input( 'wpLoginAttempt', $this->getMsg( 'pt-login-button' )->text(), 'submit', array(
					'id' => 'wpLoginAttempt',
					'tabindex' => '6',
					'class' => 'mw-ui-button mw-ui-big mw-ui-block mw-ui-constructive'
				) );
				?>
			</div>

			<div class="mw-ui-vform-field" id="mw-userlogin-help">
				<?php
				echo Html::element(
					'a',
					array(
						'href' => Skin::makeInternalOrExternalUrl(
							wfMessage( 'helplogin-url' )->inContentLanguage()->text()
						),
					),
					$this->getMsg( 'userlogin-helplink2' )->text()
				);
				?>
			</div>

			<?php if ( $this->haveData( 'createOrLoginHref' ) ) { ?>
				<?php if ( $this->data['loggedin'] ) { ?>
					<div id="mw-createaccount-another">
						<a href="<?php $this->text( 'createOrLoginHref' ); ?>" id="mw-createaccount-join" tabindex="7"  class="mw-ui-button"><?php $this->msg( 'userlogin-createanother' ); ?></a>
					</div>
				<?php } else { ?>
					<div id="mw-createaccount-cta">
						<?php $this->msg( 'userlogin-noaccount' ); ?><a href="<?php $this->text( 'createOrLoginHref' ); ?>" id="mw-createaccount-join" tabindex="7"  class="mw-ui-button mw-ui-progressive"><?php $this->msg( 'userlogin-joinproject' ); ?></a>
					</div>
				<?php } ?>
			<?php } ?>
			<?php if ( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
			<?php if ( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
			<?php if ( $this->data['cansecurelogin'] ) {?><input type="hidden" name="wpForceHttps" value="<?php $this->text( 'stickhttps' ); ?>" /><?php } ?>
		</form>
	</div>
</div>
<?php

	}
}
