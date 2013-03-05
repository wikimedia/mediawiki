<?php
/**
 * Html form for user login with new Agora appearance.
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

class UserloginTemplateAgora extends QuickTemplate {

	/**
	 * Convenience function to build an HTML checkbox nested inside a label.
	 *
	 * @param $label string text for label
	 * @param $name string form name
	 * @param $id
	 * @param $checked bool
	 * @param $attribs array
	 *
	 * @return string HTML
	 * @see Xml:checkLabel
	 */
	public static function labelledCheck( $label, $name, $id, $checked = false, $attribs = array() ) {
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
				$label
			);
	}

	function execute() {
?>
<div class="mw-ui-container">
<div id="userloginForm">
<form name="userlogin" method="post" action="<?php $this->text('action') ?>">
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html('languages' ); ?></p></div><?php } ?>
	<ul class="mw-ui-formlist">
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
				'placeholder' => wfMessage( 'userlogin-yourname-help' )->text()
				# Can't do + array( 'autofocus' ) because + for arrays in PHP
				# only works right for associative arrays!  Thanks, PHP.
			) + ( $this->data['name'] ? array() : array( 'autofocus' => '' ) ) );
			?>
		</li>
		<li>
			<label for='wpPassword1'>
			<?php
			$this->msg('userlogin-yourpassword');

			if ( $this->data['useemail'] && $this->data['canreset'] ) {
				if( $this->data['resetlink'] === true ){
					echo Linker::link(
						SpecialPage::getTitleFor( 'PasswordReset' ),
						wfMessage( 'userlogin-resetlink' )->parse(),
						array( 'class' => 'mw-ui-flush-right' )
					);
				} elseif( $this->data['resetlink'] === null ) {
					/* TODO (spage 2013-02-26) no idea what class to use for this,
					 * it doesn't belong in the label.
					 */
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
			<?php
			echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'loginPassword',
				'id' => 'wpPassword1',
				'tabindex' => '2',
				'size' => '20',
				'placeholder' => wfMessage( 'userlogin-yourpassword-help' )->text()
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

		<li>
			<?php if( $this->data['canremember'] ) {
				global $wgCookieExpiration;
				$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
				echo self::labelledCheck(
					wfMessage( 'userlogin-remembermypassword' )->numParams( $expirationDays )->text(),
					'wpRemember',
					'wpRemember',
					$this->data['remember']
				);
			} ?>
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
				'class' => 'mw-ui-button big block primary'
			) );
			?>
		</li>
		<li style="text-align: center;">
			<?php
			// Per HTML::rawElement, which says "If you're hardcoding all the
			// attributes, or there are none, you should probably just type out
			// the html element yourself."
			echo wfMessage( 'userlogin-helplink' )->parse();
			?>
		</li>
		<li>
		<?php if( $this->haveData( 'createOrLoginHref' ) ) { ?>
		<div id="createaccount-cta">
			<h3 id="userloginlink"><?php $this->msg( 'donthaveaccount' ) ?><a href="<?php $this->text( 'createOrLoginHref' ) ?>" id="mw-joinproject" class="mw-ui-button constructive" style="width: auto; display: inline-block;"><?php $this->msg( 'userlogin-joinproject' ) ?></a></h3>
		</div>
		<?php } ?>
		</li>
	</ul>
	<input type="hidden" id="useAgora" name="useAgora" value="1" />
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpLoginToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
</div>
<?php

} // end execute()
}
