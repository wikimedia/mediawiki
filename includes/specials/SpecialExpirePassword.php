<?php
/**
 * Implements Special:ExpirePassword
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
 * Special page to expire a users password manually
 *
 * @ingroup SpecialPage
 */
class ExpirePasswordPage extends SpecialPage {
	/** @var string mTarget The target for password expiration */
	protected $mTarget = null;

	public function __construct() {
		parent::__construct( 'ExpirePassword', 'userrights' );
	}

	public function isRestricted() {
		return true;
	}

	protected function getGroupName() {
		return 'users';
	}

	/**
	 * Show the form and handles request after submitting
	 *
	 * @param string|null $par String for Subpage provided or null
	 * @throws UserBlockedError|PermissionsError
	 */
	public function execute( $par ) {
		global $wgPasswordExpireGrace;
		// If the visitor doesn't have permissions to change user permissions, don't show anything

		$user = $this->getUser();

		/*
		 * Give only allowed users the permission to use Special:ExpirePassword
		 */
		if ( $user->isBlocked() && !$user->isAllowed( 'userrights' ) ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		if ( !$user->isAllowed( 'userrights' ) ) {
			$this->displayRestrictionError();
			return;
		}

		$request = $this->getRequest();
		$this->mTarget = $request->getVal( 'user' );

		$this->checkReadOnly();

		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		if (
			$request->wasPosted() &&
			$this->mTarget &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) )
		) {
			// expire Password
			$targetUser = User::newFromName( $this->mTarget );
			if ( !$targetUser || $targetUser->getId() === 0 ) {
				$out->addWikiMsg( 'nosuchusershort', $this->mTarget );

				$out->addReturnTo( $this->getPageTitle() );
				return;
			}

			// We want to check later, if the expire was successful. For this we need to alter
			// the time of expiration a little bit, so "soft" is recognized as successful
			$offset = 5;

			// want to expire hard?
			$expireTime = ( $request->getCheck( 'expire-hard' )
				? time() - ( $wgPasswordExpireGrace + $offset )
				: time() - $offset
			);

			// expire the password of user
			$targetUser->expirePassword( $expireTime );

			// password expired?
			$expiredPassword = $targetUser->getPasswordExpired();

			if ( $expiredPassword !== false ) {
				$out->addWikiMsg( 'expirepassword-success', $targetUser->getName() );
			} else {
				$out->addWikiMsg( 'expirepassword-fatal', $targetUser->getName() );
			}
			$out->addReturnTo( $this->getPageTitle() );
		} else {
			// show Form for input of username
			$this->searchForm();
		}
	}

	/**
	 * Output a form to allow searching for a user
	 */
	function searchForm() {
		$this->getOutput()->addHTML(
			Xml::openElement(
				'form',
				array(
					'method' => 'post',
					'action' => $this->getPageTitle()->getLocalUrl(),
					'name' => 'uluser',
					'id' => 'mw-expirepass-form'
				)
			) .
			//Xml::hidden( 'title', $this->getPageTitle()->getPrefixedText() ) .
			Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() ) .
			Xml::fieldset( $this->msg( 'expirepassword-searchuser' )->text() ) .
			Xml::inputLabel(
				$this->msg( 'expirepassword-entername' )->text(),
				'user',
				'username',
				30,
				str_replace( '_', ' ', $this->mTarget ),
				array( 'autofocus' => true )
			) .
			'<br />' .
			Xml::checkLabel(
				$this->msg( 'expirepassword-hard' )->text(),
				'expire-hard',
				'hard'
			) .
			'<br />' .
			Xml::submitButton( $this->msg( 'expirepassword-submit' )->text() ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}
}
