<?php
/**
 * Html form for user login.
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

/**
 * @defgroup Templates Templates
 */

/**
 * HTML template for Special:Userlogin form
 * @ingroup Templates
 */
class UserloginTemplate extends QuickTemplate {
	function execute() {
		if( $this->data['message'] ) {
?>
	<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<strong><?php $this->msg( 'loginerror' )?></strong><br />
		<?php } ?>
		<?php $this->html('message') ?>
	</div>
	<div class="visualClear"></div>
<?php } ?>

<div id="loginstart"><?php $this->msgWiki( 'loginstart' ); ?></div>
<div id="userloginForm">
<form name="userlogin" method="post" action="<?php $this->text('action') ?>">
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html('languages' ); ?></p></div><?php } ?>
	<ul class="mw-form-list">
		<li>
			<label for='wpName1' class="pos-above"><?php $this->msg('yourname') ?>
				<a href="#" class="pull-right">
					<img src="resources/mediawiki.special/images/icon-lock.png"/>&nbsp;
					<?php $this->msg( 'signwithsecure' ) ?>
				</a>
			</label>
			<?php
			echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'loginText',
				'id' => 'wpName1',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'yournamehelp' )	// XXX here and elsewhere should I use wfMessage()->text() ?
				# Can't do + array( 'autofocus' ) because + for arrays in PHP
				# only works right for associative arrays!  Thanks, PHP.
			) + ( $this->data['name'] ? array() : array( 'autofocus' => '' ) ) ); ?>

		</li>
		<li>
			<label for='wpPassword1' class="pos-above"><?php $this->msg('yourpassword') ?></label>
			<?php
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword1',
				'tabindex' => '2',
				'size' => '20',
				'placeholder' => wfMessage( 'yourpasswordhelp' )
			) + ( $this->data['name'] ? array( 'autofocus' ) : array() ) ); ?>

		</li>
	<?php if( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
		$doms = "";
		foreach( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<li id="mw-user-domain-section">
			<label for='wpDomain' class="pos-above"><?php $this->msg( 'yourdomain' ) ?></label>
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>"
					tabindex="3">
					<?php echo $doms ?>
				</select>
		</li>
	<?php }

	if( $this->haveData( 'extrafields' ) ) {
		echo $this->data['extrafields'];
	} ?>

		<li class="pull-up">
			<?php if( $this->data['canremember'] ) {
				global $wgCookieExpiration;
				$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
				echo Xml::checkLabel(
					wfMessage( 'remembermypassword' )->numParams( $expirationDays )->text(),
					'wpRemember',
					'wpRemember',
					$this->data['remember'],
					array( 'tabindex' => '8' )
				);
			} ?>
			<label class='pull-right'>
		<?php
		if ( $this->data['useemail'] && $this->data['canreset'] ) {
			if( $this->data['resetlink'] === true ){
				echo '&#160;';
				echo Linker::link(
					SpecialPage::getTitleFor( 'PasswordReset' ),
					wfMessage( 'userlogin-resetlink' )
				);
			} elseif( $this->data['resetlink'] === null ) {
				echo '&#160;';
				echo Html::input(
					'wpMailmypassword',
					wfMessage( 'mailmypassword' )->text(),
					'submit', array(
						'id' => 'wpMailmypassword',
						'tabindex' => '10'
					)
				);
			}
		} ?>
			</label>

		</li>
<?php if( $this->data['cansecurelogin'] ) { ?>
		<li>
			<?php
			echo Xml::checkLabel(
				wfMessage( 'securelogin-stick-https' )->text(),
				'wpStickHTTPS',
				'wpStickHTTPS',
				$this->data['stickHTTPS'],
				array( 'tabindex' => '9' )
			);
			?>
		</li>
<?php } ?>
		<li>
			<?php
		echo Html::input( 'wpLoginAttempt', wfMessage( 'login' )->text(), 'submit', array(
			'id' => 'wpLoginAttempt',
			'tabindex' => '9',
			'class' => 'mw-btn blue login-button'
		) );
		?>
			</td>
		</tr>
	</table>
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
<div id="createaccount-cta">
    <h3><?php $this->msg( 'donthaveaccount' ) ?><a href="#" id="joinwikipedia" class="mw-btn green"><?php $this->msg( 'joinwikipedia' ) ?></a></h3> 
</div>
</div>
<div id="loginend"><?php $this->html( 'loginend' ); ?></div>
<?php

	}
}
