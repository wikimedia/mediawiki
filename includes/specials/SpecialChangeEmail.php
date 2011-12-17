<?php
/**
 * Implements Special:ChangeEmail
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
 * @ingroup SpecialPage
 */

/**
 * Let users change their email address.
 *
 * @ingroup SpecialPage
 */
class SpecialChangeEmail extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'ChangeEmail' );
	}

	function isListed() {
		global $wgAuth;
		return $wgAuth->allowPropChange( 'emailaddress' );
	}

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgAuth;

		$this->checkReadOnly();

		$this->setHeaders();
		$this->outputHeader();

		if ( !$wgAuth->allowPropChange( 'emailaddress' ) ) {
			$this->error( 'cannotchangeemail' );
			return;
		}

		$user = $this->getUser();
		$request = $this->getRequest();

		if ( !$request->wasPosted() && !$user->isLoggedIn() ) {
			$this->error( 'changeemail-no-info' );
			return;
		}

		if ( $request->wasPosted() && $request->getBool( 'wpCancel' ) ) {
			$this->doReturnTo();
			return;
		}

		$out = $this->getOutput();
		$out->disallowUserJs();
		$out->addModules( 'mediawiki.special.changeemail' );

		$this->mPassword = $request->getVal( 'wpPassword' );
		$this->mNewEmail = $request->getVal( 'wpNewEmail' );

		if ( $request->wasPosted()
			&& $user->matchEditToken( $request->getVal( 'token' ) ) )
		{
			$info = $this->attemptChange( $user, $this->mPassword, $this->mNewEmail );
			if ( $info === true ) {
				$this->doReturnTo();
			} elseif ( $info === 'eauth' ) {
				# Notify user that a confirmation email has been sent...
				$out->wrapWikiMsg( "<div class='error' style='clear: both;'>\n$1\n</div>",
					'eauthentsent', $user->getName() );
				$this->doReturnTo( 'soft' ); // just show the link to go back
				return; // skip form
			}
		}

		$this->showForm();
	}

	protected function doReturnTo( $type = 'hard' ) {
		$titleObj = Title::newFromText( $this->getRequest()->getVal( 'returnto' ) );
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}
		if ( $type == 'hard' ) {
			$this->getOutput()->redirect( $titleObj->getFullURL() );
		} else {
			$this->getOutput()->addReturnTo( $titleObj );
		}
	}

	protected function error( $msg ) {
		$this->getOutput()->wrapWikiMsg( "<p class='error'>\n$1\n</p>", $msg );
	}

	protected function showForm() {
		$user = $this->getUser();

		$oldEmailText = $user->getEmail()
			? $user->getEmail()
			: $this->msg( 'changeemail-none' )->text();

		$this->getOutput()->addHTML(
			Xml::fieldset( $this->msg( 'changeemail-header' )->text() ) .
			Xml::openElement( 'form',
				array(
					'method' => 'post',
					'action' => $this->getTitle()->getLocalUrl(),
					'id' => 'mw-changeemail-form' ) ) . "\n" .
			Html::hidden( 'token', $user->getEditToken() ) . "\n" .
			Html::hidden( 'returnto', $this->getRequest()->getVal( 'returnto' ) ) . "\n" .
			$this->msg( 'changeemail-text' )->parseAsBlock() . "\n" .
			Xml::openElement( 'table', array( 'id' => 'mw-changeemail-table' ) ) . "\n" .
			$this->pretty( array(
				array( 'wpName', 'username', 'text', $user->getName() ),
				array( 'wpOldEmail', 'changeemail-oldemail', 'text', $oldEmailText ),
				array( 'wpNewEmail', 'changeemail-newemail', 'input', $this->mNewEmail ),
				array( 'wpPassword', 'yourpassword', 'password', $this->mPassword ),
			) ) . "\n" .
			"<tr>\n" .
				"<td></td>\n" .
				'<td class="mw-input">' .
					Xml::submitButton( $this->msg( 'changeemail-submit' )->text() ) .
					Xml::submitButton( $this->msg( 'changeemail-cancel' )->text(), array( 'name' => 'wpCancel' ) ) .
				"</td>\n" .
			"</tr>\n" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' ) . "\n"
		);
	}

	protected function pretty( $fields ) {
		$out = '';
		foreach ( $fields as $list ) {
			list( $name, $label, $type, $value ) = $list;
			if( $type == 'text' ) {
				$field = htmlspecialchars( $value );
			} else {
				$attribs = array( 'id' => $name );
				if ( $name == 'wpPassword' ) {
					$attribs[] = 'autofocus';
				}
				$field = Html::input( $name, $value, $type, $attribs );
			}
			$out .= "<tr>\n";
			$out .= "\t<td class='mw-label'>";
			if ( $type != 'text' ) {
				$out .= Xml::label( $this->msg( $label )->text(), $name );
			} else {
				$out .=  $this->msg( $label )->escaped();
			}
			$out .= "</td>\n";
			$out .= "\t<td class='mw-input'>";
			$out .= $field;
			$out .= "</td>\n";
			$out .= "</tr>";
		}
		return $out;
	}

	/**
	 * @return bool|string true or string on success, false on failure
	 */
	protected function attemptChange( User $user, $pass, $newaddr ) {
		if ( $newaddr != '' && !Sanitizer::validateEmail( $newaddr ) ) {
			$this->error( 'invalidemailaddress' );
			return false;
		}

		$throttleCount = LoginForm::incLoginThrottle( $user->getName() );
		if ( $throttleCount === true ) {
			$this->error( 'login-throttled' );
			return false;
		}

		if ( !$user->checkTemporaryPassword( $pass ) && !$user->checkPassword( $pass ) ) {
			$this->error( 'wrongpassword' );
			return false;
		}

		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $user->getName() );
		}

		list( $status, $info ) = Preferences::trySetUserEmail( $user, $newaddr );
		if ( $status !== true ) {
			if ( $status instanceof Status ) {
				$this->getOutput()->addHTML(
					'<p class="error">' .
					$this->getOutput()->parseInline( $status->getWikiText( $info ) ) .
					'</p>' );
			}
			return false;
		}

		$user->saveSettings();
		return $info ? $info : true;
	}
}
