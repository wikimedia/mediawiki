<?php
/**
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


/**
 * Html form for user login with new VForm appearance.
 */
class UserloginTemplateVForm extends BaseTemplate {

	function execute() {
		global $wgCookieExpiration;
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
?>
<div class="mw-ui-container">
	<?php
	if ( $this->haveData( 'languages' ) ) {
	?>
		<div id="languagelinks">
			<p><?php $this->html('languages' ); ?></p>
		</div>
	<?php
	}
	?>
<div id="userloginForm">
<form name="userlogin" class="mw-ui-vform" method="post" action="<?php $this->text( 'action' ); ?>">
	<section class="mw-form-header">
		<?php $this->html( 'header' ); /* extensions such as ConfirmEdit add form HTML here */ ?>
	</section>
	<?php

	if( $this->data['message'] ) {
	?>
		<div class="<?php $this->text('messagetype') ?>box">
		<?php
		if ( $this->data['messagetype'] == 'error' ) {
		?>
			<strong><?php $this->msg( 'loginerror' )?></strong><br />
		<?php
		}
		$this->html('message')
		?>
		</div>
	<?php
	}
	?>
		<div>
			<label for='wpName1'>
				<?php
				$this->msg( 'userlogin-yourname' );
				if ( $this->data['secureLoginUrl'] ) {
					echo Html::element( 'a', array(
							'href' => $this->data['secureLoginUrl'],
							'class' => 'mw-ui-flush-right mw-secure',
						), $this->getMsg( 'userlogin-signwithsecure' )->text() );
				} ?>
			</label>
			<?php
			$extraAttrs = array();
			// Set focus to this field if its blank.
			if ( !$this->data['name'] ) {
				$extraAttrs['autofocus'] = '';
			}
			echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'loginText',
				'id' => 'wpName1',
				'tabindex' => '1',
				'size' => '20',
				// 'required' is blacklisted for now in Html.php due to browser issues.
				// Keeping here in case that changes
				'required',
				'placeholder' => $this->getMsg( 'userlogin-yourname-ph' )->text()
			) + $extraAttrs );
			?>
		</div>
		<div>
			<label for='wpPassword1'>
			<?php
			$this->msg('userlogin-yourpassword');

			if ( $this->data['useemail'] && $this->data['canreset'] && $this->data['resetlink'] === true ) {
				echo Linker::link(
					SpecialPage::getTitleFor( 'PasswordReset' ),
					$this->getMsg( 'userlogin-resetlink' )->parse(),
					array( 'class' => 'mw-ui-flush-right' )
					);
				// TODO: remove the wpMailmypassword code branch from
				// templates/Userlogin.php as well; it is never executed and
				// doesn't work.
			}
			?>
			</label>
			<?php
			$extraAttrs = array();
			// Set focus to this field if username is filled in.
			if ( $this->data['name'] ) {
				$extraAttrs['autofocus'] = '';
			}
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword1',
				'tabindex' => '2',
				'size' => '20',
				'placeholder' => $this->getMsg( 'userlogin-yourpassword-ph' )->text()
			) + $extraAttrs );
			?>
		</div>
	<?php
	if( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
		$doms = "";
		foreach( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<div id="mw-user-domain-section">
			<label for='wpDomain' class="pos-above"><?php $this->msg( 'yourdomain' ) ?></label>
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>"
					tabindex="3">
					<?php echo $doms ?>
				</select>
		</div>
	<?php }

	if( $this->haveData( 'extrafields' ) ) {
		echo $this->data['extrafields'];
	} ?>

		<div>

	<?php if( $this->data['canremember'] ) { ?>
		<label class="mw-ui-checkbox-label">
			<input name="wpRemember" type="checkbox" value="1" id="wpRemember" tabindex="4"
				<?php if ( $this->data['remember'] ) {
					echo 'checked="checked"';
				} ?>
			>
			<?php echo $this->getMsg( 'userlogin-remembermypassword' )->numParams( $expirationDays )->escaped(); ?>
		</label>
	<?php } ?>
		</div>

	<?php if( $this->data['cansecurelogin'] ) { ?>
		<div>
			<label class="mw-ui-checkbox-label">
				<input name="wpStickHTTPS" type="checkbox" value="1" id="wpStickHTTPS" tabindex="5"
					<?php if ( $this->data['stickHTTPS'] ) {
						echo 'checked="checked"';
					} ?>
				>
				<?php $this->msg( 'securelogin-stick-https' ); ?>
			</label>
		</div>
	<?php } ?>
		<div>
			<?php
			echo Html::input( 'wpLoginAttempt', $this->getMsg( 'login' )->text(), 'submit', array(
				'id' => 'wpLoginAttempt',
				'tabindex' => '6',
				'class' => 'mw-ui-button mw-ui-big mw-ui-block mw-ui-primary'
			) );
			?>
		</div>
		<div id="mw-userlogin-help">
			<?php echo $this->getMsg( 'userlogin-helplink' )->parse() ?>
		</div>
		<?php if( $this->haveData( 'createOrLoginHref' ) ) { ?>
			<div id="mw-createaccount-cta">
				<h3 id="mw-userloginlink"><?php $this->msg( 'userlogin-noaccount' ) ?><a href="<?php $this->text( 'createOrLoginHref' ) ?>" id="mw-createaccount-join" tabindex="7"  class="mw-ui-button mw-ui-constructive"><?php $this->msg( 'userlogin-joinproject' ) ?></a></h3>
			</div>
		<?php } ?>
	<input type="hidden" id="mw-useNew" name="useNew" value="1" />
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
</div>
<?php
	}
}
