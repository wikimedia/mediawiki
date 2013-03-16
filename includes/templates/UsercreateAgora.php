<?php
/**
 * Html form for account creation with new Agora appearance.
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

if( !defined( 'MEDIAWIKI' ) ) die( -1 );

/**
 * @ingroup Templates
 */
class UsercreateTemplateAgora extends AgoraTemplate {
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
?>
<div class="mw-ui-container">
<div id="userloginForm">
<h2 class="createaccount-join"><?php $this->msg('createacct-join') ?></h2>
<form name="userlogin2" id="userlogin2" method="post" action="<?php $this->text('action') ?>">
	<?php $this->html('header'); /* pre-table point for form plugins... */ ?>
	<?php if( $this->haveData( 'languages' ) ) { ?><div id="languagelinks"><p><?php $this->html( 'languages' ); ?></p></div><?php } ?>
	<ul class="mw-ui-formlist">
	<?php
	if( $this->data['message'] ) {
?>
		<li>
			<div class="<?php $this->text('messagetype') ?>box">
			<?php if ( $this->data['messagetype'] == 'error' ) { ?>
				<strong><?php $this->msg( 'loginerror' )?></strong><br />
			<?php } ?>
			<?php $this->html('message') ?>
			</div>
			<div class="visualClear"></div>
		</li>
	<?php } ?>
		<li>
			<label for='wpName2'>
				<?php $this->msg('userlogin-yourname') ?>

				<span class="mw-ui-flush-right"><?php echo wfMessage( 'createacct-helpusername-link' )->parse() ?></span>
			</label>
			<?php echo Html::input( 'wpName', $this->data['name'], 'text', array(
				'class' => 'mw-input loginText',
				'id' => 'wpName2',
				'tabindex' => '1',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'userlogin-yourname-ph' )->text(),
				'autofocus'
			) ); ?>
		</li>
		<li>
		<?php if( $this->data['createemail'] ) {
			echo UserloginTemplateAgora::labelledCheck(
				wfMessage( 'createaccountmail' )->text(),
				'wpCreateaccountMail',
				'wpCreateaccountMail',
				$this->data['createemailset'],
				array( 'tabindex' => '2' )
			);
		} ?>
		</li>
		<li class="mw-row-password">
			<label for='wpPassword2'><?php $this->msg('userlogin-yourpassword') ?></label>
			<?php echo Html::input( 'wpPassword', null, 'password', array(
				'class' => 'mw-input loginPassword',
				'id' => 'wpPassword2',
				'tabindex' => '3',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'createacct-yourpassword-ph' )->text()
			) + User::passwordChangeInputAttribs() ); ?>
		</li>
	<?php if( $this->data['usedomain'] ) {
		$doms = "";
		foreach( $this->data['domainnames'] as $dom ) {
			$doms .= "<option>" . htmlspecialchars( $dom ) . "</option>";
		}
	?>
		<li>
			<!-- TODO (spage 2013-02-13) what replaces td class="mw-label" ? -->
			<label><?php $this->msg( 'yourdomainname' ) ?></label>
			<div class="mw-input">
				<select name="wpDomain" value="<?php $this->text( 'domain' ) ?>"
					tabindex="4">
					<?php echo $doms ?>
				</select>
			</div>
		</li>
	<?php } ?>
		<li class="mw-row-password">
			<label for='wpRetype'><?php $this->msg('createacct-yourpasswordagain') ?></label>
			<?php
			echo Html::input( 'wpRetype', null, 'password', array(
				'class' => 'mw-input loginPassword',
				'id' => 'wpRetype',
				'tabindex' => '5',
				'size' => '20',
				'required',
				'placeholder' => wfMessage( 'createacct-yourpasswordagain-ph' )->text()
				) + User::passwordChangeInputAttribs() );
			?>
		</li>
		<li>
		<?php if( $this->data['useemail'] ) { ?>
			<label for='wpEmail'><?php $this->msg('createacct-emailoptional') // FIXME required/optional message
				// FIXME	$this->data['emailrequired'] ? createacct-emailrequired / optional.
		?></label>
			<?php
				echo Html::input( 'wpEmail', $this->data['email'], 'email', array(
					'class' => 'mw-input loginText',
					'id' => 'wpEmail',
					'tabindex' => '6',
					'size' => '20',
					'placeholder' => wfMessage( 'createacct-email-ph' )->text()
					# Can't do + array( 'autofocus' ) because + for arrays in PHP
					# only works right for associative arrays!  Thanks, PHP.
				) + ( $this->data['emailrequired'] ? array() : array( 'required' => '' ) ) );
			?>
			<?php
			// Agora eliminate the  prefsectiontip div tip:
			// prefs-help-email-required is redundant with the placeholder text
			// Doesn't show the wordy prefs-help-email
			// Doesn't show the wordy prefs-help-email-others
			?>
		<?php } ?>
		</li>
		<?php if( $this->data['userealname'] ) { ?>
			<li>
				<label for='wpRealName'><?php $this->msg('yourrealname') ?></label>
				<input type='text' class='mw-input loginText' name="wpRealName" id="wpRealName"
					tabindex="7"
					value="<?php $this->text('realname') ?>" size='20' />
				<div class="prefsectiontip">
					<?php $this->msgWiki('prefs-help-realname'); ?>
				</div>
			</li>
		<?php } ?>
		<?php if( $this->data['usereason'] ) { ?>
			<li>
				<label for='wpReason'><?php $this->msg('createacct-reason') ?></label>
				<input type='text' class='mw-input loginText' name="wpReason" id="wpReason"
						tabindex="8"
						value="<?php $this->text('reason') ?>" size='20' />
			</li>
		<?php } ?>
		<?php if( $this->data['canremember'] ) { ?>
			<li>
				<?php
				global $wgCookieExpiration;
				$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
				echo UserloginTemplateAgora::labelledCheck(
					wfMessage( 'remembermypassword' )->numParams( $expirationDays )->text(),
					'wpRemember',
					'wpRemember',
					$this->data['remember'],
					array( 'tabindex' => '9' )
				)
				?>
			</li>
		<?php }

		$tabIndex = 10;
		if ( isset( $this->data['extraInput'] ) && is_array( $this->data['extraInput'] ) ) {
			foreach ( $this->data['extraInput'] as $inputItem ) { ?>
			<li>
				<?php
					// Output the message label, unless it's a checkbox.
					if ( !empty( $inputItem['msg'] ) && $inputItem['type'] != 'checkbox' ) {
						?><label for="<?php
						echo htmlspecialchars( $inputItem['name'] ); ?>"><?php
						$this->msgWiki( $inputItem['msg'] ) ?></label><?php
					} else {
						## Lost in table rows here...
						?><!-- do I need to output a label to v-align these optional checkboxes? --><?php
					}
				?>
				<input type="<?php echo htmlspecialchars( $inputItem['type'] ) ?>" class="mw-input" name="<?php
					echo htmlspecialchars( $inputItem['name'] ); ?>"
						tabindex="<?php echo $tabIndex++; ?>"
						value="<?php
					if ( $inputItem['type'] != 'checkbox' ) {
						echo htmlspecialchars( $inputItem['value'] );
					} else {
						echo '1';
					}
					?>" id="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"
						<?php
					if ( $inputItem['type'] == 'checkbox' && !empty( $inputItem['value'] ) )
						echo 'checked="checked"';
						?> /> <?php
						if ( $inputItem['type'] == 'checkbox' && !empty( $inputItem['msg'] ) ) {
							?>
					<label for="<?php echo htmlspecialchars( $inputItem['name'] ); ?>"><?php
						$this->msgHtml( $inputItem['msg'] ) ?></label><?php
						}
					if( $inputItem['helptext'] !== false ) {
					?>
					<div class="prefsectiontip">
						<?php $this->msgWiki( $inputItem['helptext'] ); ?>
					</div>
					<?php } ?>
			</li>
<?php
			}
		}
?>
		<li>
			<div class="mw-submit">
				<input type='submit' class="mw-ui-button big block primary" name="wpCreateaccount" id="wpCreateaccount"
					tabindex="<?php echo $tabIndex++; ?>"
					value="<?php $this->msg('createaccount') ?>" />
			</div>
		</li>
	</ul>
	<input type="hidden" id="useAgora" name="useAgora" value="1" />
<?php if( $this->haveData( 'uselang' ) ) { ?><input type="hidden" name="uselang" value="<?php $this->text( 'uselang' ); ?>" /><?php } ?>
<?php if( $this->haveData( 'token' ) ) { ?><input type="hidden" name="wpCreateaccountToken" value="<?php $this->text( 'token' ); ?>" /><?php } ?>
</form>
</div>
<div class="createacct-benefits-container hidden-phone visible-desktop visible-tablet">
	<h2><?php echo wfMessage( 'createacct-benefit-heading' )->text() ?></h2>
	<ul class="createacct-benefits-list">
		<li>
			<div class="benefits-icon <?php echo wfMessage( 'createacct-benefit-icon1' )->text() ?>"></div>
			<div class="number-text">
				<h3><?php echo wfMessage( 'createacct-benefit-head1' )->text() ?></h3>
				<p><?php echo wfMessage( 'createacct-benefit-body1' )->text() ?></p>
			</div>
		</li>
		<li>
			<div class="benefits-icon <?php echo wfMessage( 'createacct-benefit-icon2' )->text() ?>"></div>
			<div class="number-text">
				<h3><?php echo wfMessage( 'createacct-benefit-head2' )->text() ?></h3>
				<p><?php echo wfMessage( 'createacct-benefit-body2' )->text() ?></p>
			</div>
		</li>
		<li>
			<div class="benefits-icon <?php echo wfMessage( 'createacct-benefit-icon3' )->text() ?>"></div>
			<div class="number-text">
				<h3><?php echo wfMessage( 'createacct-benefit-head3' )->text() ?></h3>
				<p><?php echo wfMessage( 'createacct-benefit-body3' )->text() ?></p>
			</div>
		</li>
  </ul>
</div>
</div>
<?php

	}
}
