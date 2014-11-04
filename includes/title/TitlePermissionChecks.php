<?php
/**
 * Permission checks for a user and title. This does NOT (currently)
 * include all of the Protection checks.
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
 * @license GPL 2+
 * @author CSteipp
 */

class TitlePermissionChecks {

	/**
	 * @var User $mUser
	 */
	protected $mUser;

	/**
	 * @var TitleProperties $mTitleProperties
	 */
	protected $mTitleProperties;

	/**
	 * @var boolean $doExpensiveQueries
	 */
	protected $doExpensiveQueries;

	/**
	 * @param User $user the user to check permissions for
	 * @param bool $doExpensiveQueries do expensive permission checks
	 */
	public function __construct( TitleProperties $title, User $user, $doExpensiveQueries = true ) {
		$this->mTitleProperties = $title;
		$this->mUser = $user;
		$this->doExpensiveQueries = $doExpensiveQueries;
	}

	/**
	 * Check if a user should be allowed to edit this TitleValue
	 * with the specified content model
	 */
	public function userCanEdit() {
		// Reimplement the checks from getUserPermissionsErrorsInternal
		// for $action='edit'

		#'checkQuickPermissions',
		$status = $this->checkQuickPermissions( 'edit' );

		#'checkPermissionHooks',
		// Can we do these?

		#'checkSpecialsAndNSPermissions',
		$status = $this->checkSpecialsAndNSPermissions( 'edit' );

		#'checkCSSandJSPermissions',
		$status = $this->checkSpecialsAndNSPermissions( 'edit' );

		'checkPageRestrictions',
		'checkCascadingSourcesRestrictions',
		'checkActionPermissions',
		'checkUserBlock'
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	public function checkSpecialsAndNSPermissions( $action ) {
		global $wgNamespaceProtection; // a wiki kitten dies..
		$status = Status::newGood();
		$ns = $this->mTitleProperties->getTitleValue()->getNamespace();
		# Only 'createaccount' can be performed on special pages,
		# which don't actually exist in the DB.
		if ( NS_SPECIAL == $ns && $action !== 'createaccount' ) {
			//$errors[] = array( 'ns-specialprotected' );
			$status->fatal( 'cant-move-user-page' );
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $wgNamespaceProtection ) ) {
			if ( $ns == NS_MEDIAWIKI ) {
				$status->fatal( 'protectedinterface', $action );
			} else {
				$msgNs = $ns == NS_MAIN ? wfMessage( 'nstab-main' )->text() : $this->getNsText(); # AHHHHHHH! (TODO)
				$status->fatal( 'namespaceprotected', $msgNs,  $action );
			}
		}

		return $status;
	}

	/**
	 * Check CSS/JS sub-page permissions
	 *
	 * @param string $action The action to check
	 */
	public function checkCSSandJSPermissions( $action ) {
		$errors = Status::newGood();

		# Protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: right 'editusercssjs' is deprecated, for backward compatibility only
		if ( $action != 'patrol' && !$this->mUser->isAllowed( 'editusercssjs' ) ) {
			if ( preg_match( '/^' . preg_quote( $this->mUser->getName(), '/' ) . '\//',
				$this->mTitleProperties->getTitleValue()->getText() )
			) {
				if ( $this->mTitleProperties->isCssSubpage()
					&& !$this->mUser->isAllowedAny( 'editmyusercss', 'editusercss' )
				) {
					$errors->fatal( 'mycustomcssprotected', $action );
				} elseif ( $this->mTitleProperties->isJsSubpage()
					&& !$this->mUser->isAllowedAny( 'editmyuserjs', 'edituserjs' )
				) {
					$errors->fatal( 'mycustomjsprotected', $action );
				}
			} else {
				if ( $this->mTitleProperties->isCssSubpage()
					&& !$this->mUser->isAllowed( 'editusercss' )
				) {
					$errors->fatal( 'customcssprotected', $action );
				} elseif ( $this->mTitleProperties->isJsSubpage()
					&& !$this->mUser->isAllowed( 'edituserjs' )
				) {
					$errors->fatal( 'customjsprotected', $action );
				}
			}
		}

		return $errors;
	}

	/**
	 * Determines if $user is unable to edit this page because it has been protected
	 * by an array of namespace protections, such as $wgNamespaceProtection.
	 *
	 * @param array $namespaceProtection associative array of namespaces, and the right
	 *	or array of sufficient rights to allow editing.
	 * @return bool
	 */
	public function isNamespaceProtected( $namespaceProtection ) {
		$ns = $this->mTitleProperties->getTitleValue()->getNamespace();
		if ( isset( $namespaceProtection[$ns] ) ) {
			foreach ( (array)$namespaceProtection[$ns] as $right ) {
				if ( $right != '' && !$this->mUser->isAllowed( $right ) ) {
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Permissions checks that fail most often, and which are easiest to test.
	 * This does NOT include permissions enforced by the TitleQuickPermissions
	 * hook.
	 * @param string $action The action to check
	 * @return array List of errors
	 */
	public function checkQuickPermissions( $action ) {
		$status = Status::newGood();
		$ns = $this->mTitleProperties->getTitleValue()->getNamespace();
		if ( $action == 'create' ) {
			if (
				( $this->mTitleProperties->isTalkPage() && !$this->mUser->isAllowed( 'createtalk' ) ) ||
				( !$this->mTitleProperties->isTalkPage() && !$this->mUser->isAllowed( 'createpage' ) )
			) {
				$status->fatal( $this->mUser->isAnon() ? 'nocreatetext' : 'nocreate-loggedin' );
				//$errors[] = $user->isAnon() ? array( 'nocreatetext' ) : array( 'nocreate-loggedin' );
			}
		} elseif ( $action == 'move' ) {
			if ( !$this->mUser->isAllowed( 'move-rootuserpages' )
					&& $ns == NS_USER && !$this->mTitleProperties->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-user-page' );
				//$errors[] = array( 'cant-move-user-page' );
			}

			// Check if user is allowed to move files if it's a file
			if ( $ns == NS_FILE && !$this->mUser->isAllowed( 'movefile' ) ) {
				$status->fatal( 'movenotallowedfile' );
				//$errors[] = array( 'movenotallowedfile' );
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $ns == NS_CATEGORY && !$this->mUser->isAllowed( 'move-categorypages' ) ) {
				$status->fatal( 'cant-move-category-page' );
				//$errors[] = array( 'cant-move-category-page' );
			}

			if ( !$this->mUser->isAllowed( 'move' ) ) {
				// User can't move anything
				$userCanMove = User::groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = User::groupHasPermission( 'autoconfirmed', 'move' );
				if ( $this->mUser->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$status->fatal( 'movenologintext' );
					//$errors[] = array( 'movenologintext' );
				} else {
					$status->fatal( 'movenotallowed' );
					//$errors[] = array( 'movenotallowed' );
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$this->mUser->isAllowed( 'move' ) ) {
				// User can't move anything
				$status->fatal( 'movenotallowed' );
				//$errors[] = array( 'movenotallowed' );
			} elseif ( !$this->mUser->isAllowed( 'move-rootuserpages' )
					&& $ns == NS_USER && !$this->mTitleProperties->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-to-user-page' );
				//$errors[] = array( 'cant-move-to-user-page' );
			} elseif ( !$this->mUser->isAllowed( 'move-categorypages' )
					&& $ns == NS_CATEGORY ) {
				// Show category page-specific message only if the user can move other pages
				$status->fatal( 'cant-move-to-category-page' );
				//$errors[] = array( 'cant-move-to-category-page' );
			}
		} elseif ( !$this->mUser->isAllowed( $action ) ) {
			$status->fatal( 'tpc-user-not-allowed' );
			//$errors[] = $this->missingPermissionError( $action, $short );
		}

		return $status;
	}


}
