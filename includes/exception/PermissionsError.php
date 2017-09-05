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
 */

/**
 * Show an error when a user tries to do something they do not have the necessary
 * permissions for.
 *
 * @since 1.18
 * @ingroup Exception
 */
class PermissionsError extends ErrorPageError {
	/**
	 * A set of permissions, that are considered to be valid for redirecting the user to
	 * Special:UserLogin when they are not logged in. See PermissionsError::shouldRedirectLogin.
	 */
	private static $redirectToLoginPermissions = [
		'read',
		'edit',
		'createpage',
		'createtalk',
		'upload'
	];

	public $permission, $errors;

	/**
	 * @param string|null $permission A permission name or null if unknown
	 * @param array $errors Error message keys or [key, param...] arrays; must not be empty if
	 *   $permission is null
	 * @throws \InvalidArgumentException
	 */
	public function __construct( $permission, $errors = [] ) {
		global $wgLang;

		if ( $permission === null && !$errors ) {
			throw new \InvalidArgumentException( __METHOD__ .
				': $permission and $errors cannot both be empty' );
		}

		$this->permission = $permission;

		if ( !count( $errors ) ) {
			$groups = [];
			foreach ( User::getGroupsWithPermission( $this->permission ) as $group ) {
				$groups[] = UserGroupMembership::getLink( $group, RequestContext::getMain(), 'wiki' );
			}

			if ( $groups ) {
				$errors[] = [ 'badaccess-groups', $wgLang->commaList( $groups ), count( $groups ) ];
			} else {
				$errors[] = [ 'badaccess-group0' ];
			}
		}

		$this->errors = $errors;

		// Give the parent class something to work with
		parent::__construct( 'permissionserrors', Message::newFromSpecifier( $errors[0] ) );
	}

	/**
	 * For some action (read, edit, create and upload), this permissions error can be solved by
	 * simply logging in. If this method returns true, the PermissionsError will most likely
	 * redirect the user to Special:UserLogin with a warning message, why they have to login to
	 * continue, instead of showing a permissions error page.
	 *
	 * The implementation of PermissionsError::shouldRedirectLogin returns true, if all of these
	 * conditions are met, subclasses may or may not overwrite this method:
	 * 1. the user is not logged in
	 * 2. the only error is insufficient permissions (i.e. no block or something else)
	 * 3. the error can be avoided simply by logging in
	 *
	 * @param string|null $permission A permission name or null if unknown
	 * @param User $user The currently logged in User
	 * @return bool
	 */
	protected function shouldRedirectLogin( $permission, User $user ) {
		return in_array( $permission, self::$redirectToLoginPermissions )
			&& $user->isAnon()
			&& count( $this->errors ) === 1
			&& isset( $this->errors[0][0] )
			&& (
				$this->errors[0][0] === 'badaccess-groups' ||
				$this->errors[0][0] === 'badaccess-group0'
			)
			&& (
				User::groupHasPermission( 'user', $permission ) ||
				User::groupHasPermission( 'autoconfirmed', $permission )
			);
	}

	public function report() {
		$context = RequestContext::getMain();
		$out = $context->getOutput();
		$request = $context->getRequest();

		if ( $this->shouldRedirectLogin( $this->permission, $context->getUser() ) ) {
			# Due to T34276, if a user does not have read permissions,
			# $this->getTitle() will just give Special:Badtitle, which is
			# not especially useful as a returnto parameter. Use the title
			# from the request instead, if there was one.
			$returnTo = Title::newFromText( $request->getVal( 'title', '' ) );
			if ( in_array( $this->msg, LoginHelper::getValidErrorMessages() ) ) {
				$msg = $this->msg;
			} elseif ( $this->permission === 'edit' ) {
				$msg = 'whitelistedittext';
			} elseif ( $this->permission === 'createpage' || $this->permission === 'createtalk' ) {
				$msg = 'create-nologin-text';
			} elseif ( $this->permission === 'upload' ) {
				$msg = 'uploadnologintext';
			} else { # Read
				$msg = 'exception-nologin-text';
			}

			// If an unsupported message is used, don't try redirecting to Special:UserLogin,
			// since the message may not be compatible.
			if ( in_array( $msg, LoginHelper::getValidErrorMessages() ) ) {
				$this->reportUserNotLoggedIn( $msg, $returnTo );
				return;
			}
		}
		$out->showPermissionsErrorPage( $this->errors, $this->permission );
		$out->output();
	}

	/**
	 * Redirect to Special:UserLogin if the specified message is compatible. Otherwise,
	 * show an error page as usual.
	 *
	 * @param string $msg The message key of the message that should be shown on the login page
	 * @param Title $returnTo A title to redirect to after a successful login
	 */
	public function reportUserNotLoggedIn( $msg, $returnTo ) {
		// Message is valid. Redirect to Special:Userlogin
		$context = RequestContext::getMain();

		if ( $returnTo === null ) {
			$returnTo = RequestContext::getMain()->getTitle();
		}

		$output = $context->getOutput();
		$query = $context->getRequest()->getValues();
		// Title will be overridden by returnto
		unset( $query['title'] );
		// Redirect to Special:Userlogin
		$output->redirect( SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
			// Return to this page when the user logs in
			'returnto' => $returnTo->getFullText(),
			'returntoquery' => wfArrayToCgi( $query ),
			'warning' => $msg,
		] ) );

		$output->output();
	}
}
