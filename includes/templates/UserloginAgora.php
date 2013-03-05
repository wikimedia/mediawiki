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
 * @defgroup Templates Templates
 */

/**
 * New base template for Agora-look templates that provides helper methods for
 * some Agora-specific layouts.
 */
abstract class AgoraTemplate extends QuickTemplate {

	/**
	 * Convenience function to build an Agora HTML checkbox nested inside a
	 * label.  This arguably belongs in class Html, but then Agora clients
	 * would have to apply an Agora class to the label as well as attrs for the
	 * checkbox.
	 *
	 * @param $label string text for label
	 * @param $name string form element name
	 * @param $id
	 * @param $checked bool (default: false)
	 * @param $attribs array additional attributes for the input checkbox
	 *
	 * @return string HTML
	 * @see Xml:checkLabel
	 */
	public function labelledCheck( $label, $name, $id, $checked = false, $attribs = array() ) {
		return Html::rawElement(
				'label',
				array(
					'for' => $id,
					'class' => 'mw-ui-checkbox-label'
				),
				Xml::check(
					$id,
					$checked,
					array( 'id' => $id ) + $attribs
				) .
				// Html:rawElement doesn't escape contents.
				htmlspecialchars( $label )
			);
	}

}

/**
 * Html form for user login with new Agora appearance.
 */

class UserloginTemplateAgora extends AgoraTemplate {

	function execute() {
?>
<div class="mw-ui-container">
<div id="userloginForm">
<form name="userlogin" class="mw-ui-vform" method="post" action="<?php $this->text('action') ?>">
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html('languages' ); ?></p></div><?php } ?>
	<?php
	if( $this->data['message'] ) {
?>
		<div class="<?php $this->text('messagetype') ?>box">
		<?php if ( $this->data['messagetype'] == 'error' ) { ?>
			<strong><?php $this->msg( 'loginerror' )?></strong><br />
		<?php } ?>
		<?php $this->html('message') ?>
		</div>
<?php } ?>

		<div>
			<label for='wpName1'>
				<?php
				$this->msg('userlogin-yourname');
				if ( $this->data['secureLoginUrl'] ) {
					echo Html::element( 'a', array(
							'href' => $this->data['secureLoginUrl'],
							'class' => 'mw-ui-flush-right mw-secure',
						), wfMessage( 'userlogin-signwithsecure' )->text() );
				} ?>
			</label>
			<?php
			echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'loginText',
				'id' => 'wpName1',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'userlogin-yourname-ph' )->text()
				# Can't do + array( 'autofocus' ) because + for arrays in PHP
				# only works right for associative arrays!  Thanks, PHP.
			) + ( $this->data['name'] ? array() : array( 'autofocus' => '' ) ) );
			?>
		</div>
		<div>
			<label for='wpPassword1'>
			<?php
			$this->msg('userlogin-yourpassword');

			if ( $this->data['useemail'] && $this->data['canreset'] && $this->data['resetlink'] === true ) {
				echo Linker::link(
					SpecialPage::getTitleFor( 'PasswordReset' ),
					wfMessage( 'userlogin-resetlink' )->parse(),
					array( 'class' => 'mw-ui-flush-right' )
					);
				// TODO (spage 2013-03-22): remove the wpMailmypassword code
				// branch from templates/Userlogin.php as well; it is never
				// executed and doesn't work.
			} ?>
			</label>
			<?php
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword1',
				'tabindex' => '2',
				'size' => '20',
				'placeholder' => wfMessage( 'userlogin-yourpassword-ph' )->text()
			) + ( $this->data['name'] ? array( 'autofocus' ) : array() ) ); ?>
		</div>
	<?php if( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
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
			<?php if( $this->data['canremember'] ) {
				global $wgCookieExpiration;
				$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
				echo $this->labelledCheck(
					wfMessage( 'userlogin-remembermypassword' )->numParams( $expirationDays )->text(),
					'wpRemember',
					'wpRemember',
					$this->data['remember'],
					array ( 'tabindex' => 4 )
				);
			} ?>
		</div>
<?php if( $this->data['cansecurelogin'] ) { ?>
		<div>
			<?php
			echo $this->labelledCheck(
				wfMessage( 'securelogin-stick-https' )->text(),
				'wpStickHTTPS',
				'wpStickHTTPS',
				$this->data['stickHTTPS'],
				array( 'tabindex' => '5' )
			);
			?>
		</div>
<?php } ?>
		<div>
			<?php
			echo Html::input( 'wpLoginAttempt', wfMessage( 'login' )->text(), 'submit', array(
				'id' => 'wpLoginAttempt',
				'tabindex' => '6',
				'class' => 'mw-ui-button big block primary'
			) );
			?>
		</div>
		<div style="text-align: center;">
			<?php echo wfMessage( 'userlogin-helplink' )->parse() ?>
		</div>
		<?php if( $this->haveData( 'createOrLoginHref' ) ) { ?>
			<div id="createaccount-cta">
				<h3 id="userloginlink"><?php $this->msg( 'userlogin-noaccount' ) ?><a href="<?php $this->text( 'createOrLoginHref' ) ?>" id="mw-joinproject" class="mw-ui-button constructive" style="width: auto; display: inline-block;"><?php $this->msg( 'userlogin-joinproject' ) ?></a></h3>
			</div>
		<?php } ?>
	<input type="hidden" id="useAgora" name="useAgora" value="1" />
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
</div>
<?php

} // end execute()
}
