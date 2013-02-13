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
 * HTML template for Special:Userlogin form using new Agora styling and clean UI.
 * @ingroup Templates
 */
class UserloginTemplateAgora extends QuickTemplate {
function execute() {
?>
<div id="loginstart"><?php $this->msgWiki( 'loginstart' ); ?></div>
<div id="userloginForm">
<form name="userlogin" method="post" action="<?php $this->text('action') ?>">
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html('languages' ); ?></p></div><?php } ?>
	<ul class="mw-form-list">
	<?php
	// TODO is inside the form the right place for this?
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

		<li>
			<label for='wpName1' class="pos-above"><?php $this->msg('userlogin-yourname') ?>
				<!-- FIXME (spage 2013-02-11) proper link and only if there's content. -->
				<a href="#" class="pull-right mw-secure">
					<!-- FIXME (spage 2013-02-11) CSS should align the lock. -->
					&nbsp;&nbsp;&nbsp;&nbsp;<?php $this->msg( 'userlogin-signwithsecure' ) ?>
				</a>
			</label>
			<?php
			echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'loginText',
				'id' => 'wpName1',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'userlogin-yournamehelp' )	// XXX here and elsewhere should I use wfMessage()->text() ?
				# Can't do + array( 'autofocus' ) because + for arrays in PHP
				# only works right for associative arrays!  Thanks, PHP.
			) + ( $this->data['name'] ? array() : array( 'autofocus' => '' ) ) ); ?>

		</li>
		<li>
			<label for='wpPassword1' class="pos-above"><?php $this->msg('userlogin-yourpassword') ?></label>
			<?php
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword1',
				'tabindex' => '2',
				'size' => '20',
				'placeholder' => wfMessage( 'userlogin-yourpasswordhelp' )
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
					wfMessage( 'userlogin-remembermypassword' )->numParams( $expirationDays )->text(),
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
		</li>
		<li class="center-text">
			<!-- TODO (spage 2013-02-11 this should be HTML:element( 'span', with tabindex and class, but don't know how to put parsed wiki link inside it without turning into a nested p tag.
				QuickTemplate implementation of msgWiki in SkinTemplate.php says "An ugly, ugly hack."
				QuickTemplate msgXxx() functions all echo output, none return it.
			-->
			<span class="mw-userlogin-help">
			<?php $this->msgWiki( 'userlogin-helplink' ); ?>
			</span>
		</li>
			</td>
		</tr>
	</table>
	<input type="hidden" name="useAgora" value="1" />
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
<?php if( $this->haveData( 'createOrLoginHref' ) ) { ?>
<div id="createaccount-cta">
    <h3 id="userloginlink"><?php $this->msg( 'donthaveaccount' ) ?><a href="<?php $this->text( 'createOrLoginHref' ) ?>" id="mw-joinproject" class="mw-btn green"><?php $this->msg( 'userlogin-joinproject' ) ?></a></h3>
</div>
</div>
<?php } ?>
<div id="loginend"><?php $this->html( 'loginend' ); ?></div>
<?php

} // end execute()
}
