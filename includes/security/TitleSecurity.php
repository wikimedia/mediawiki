<?php

/**
 * Encapsulates Title-based security logic
 *
 * @since 1.24
 *
 * TODO: resolve lazy-loading mess
 */
class TitleSecurity {
	/** @var Title title to be protected */
	protected $title;

	/** @var array Array of groups allowed to edit this article */
	protected $mRestrictions = array();

	/** @var bool */
	protected $mOldRestrictions = false;

	/** @var bool Cascade restrictions on this page to included templates and images? */
	protected $mCascadeRestriction;

	/** Caching the results of getCascadeProtectionSources */
	protected $mCascadingRestrictions;

	/** @var array When do the restrictions on this page expire? */
	protected $mRestrictionsExpiry = array();

	/** @var bool Are cascading restrictions in effect on this page? */
	protected $mHasCascadingRestrictions;

	/** @var array Where are the cascading restrictions coming from on this page? */
	protected $mCascadeSources;

	/** @var bool Boolean for initialisation on demand */
	protected $mRestrictionsLoaded = false;

	/** @var mixed Cached value for getTitleProtection (create protection) */
	protected $mTitleProtection = null;

	/**
	 * Prevent construction
	 *
	 * Use the newFromTitle method instead.
	 */
	protected function __construct() {
	}

	public static function newFromTitle( Title $title ) {
		$p = new TitleSecurity();
		$p->title = $title;
		return $p;
	}

	/**
	 * Can $user perform $action on this page?
	 * This skips potentially expensive cascading permission checks
	 * as well as avoids expensive error formatting
	 *
	 * Suitable for use for nonessential UI controls in common cases, but
	 * _not_ for functional access control.
	 *
	 * May provide false positives, but should never provide a false negative.
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check; $wgUser will be used if not provided.
	 * @return bool
	 */
	public function quickUserCan( $action, User $user ) {
		return $this->userCan( $action, $user, false );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check; $wgUser will be used if not
	 *   provided.
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @return bool
	 */
	public function userCan( $action, User $user, $rigor = 'secure' ) {
		return !count( $this->getUserPermissionsErrorsInternal(
			$action, $user, $rigor, true ) );
	}

	/**
	 * Can $user perform $action on this page?
	 *
	 * @todo FIXME: This *does not* check throttles (User::pingLimiter()).
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param string $rigor One of (quick,full,secure)
	 *   - quick  : does cheap permission checks from slaves (usable for GUI creation)
	 *   - full   : does cheap and expensive checks possibly from a slave
	 *   - secure : does cheap and expensive checks, using the master as needed
	 * @param array $ignoreErrors Array of Strings Set this to a list of message keys
	 *   whose corresponding errors may be ignored.
	 * @return array Array of arguments to wfMessage to explain permissions problems.
	 */
	public function getUserPermissionsErrors( $action, $user, $rigor = 'secure',
		$ignoreErrors = array()
	) {
		$errors = $this->getUserPermissionsErrorsInternal( $action, $user, $rigor );

		// Remove the errors being ignored.
		foreach ( $errors as $index => $error ) {
			$error_key = is_array( $error ) ? $error[0] : $error;

			if ( in_array( $error_key, $ignoreErrors ) ) {
				unset( $errors[$index] );
			}
		}

		return $errors;
	}

	/**
	 * Permissions checks that fail most often, and which are easiest to test.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkQuickPermissions( $action, $user, $errors,
		$rigor, $short
	) {
		if ( !Hooks::run( 'TitleQuickPermissions',
			array( $this->title, $user, $action, &$errors, ( $rigor !== 'quick' ), $short ) )
		) {
			return $errors;
		}

		if ( $action == 'create' ) {
			if (
				( $this->title->isTalkPage() && !$user->isAllowed( 'createtalk' ) ) ||
				( !$this->title->isTalkPage() && !$user->isAllowed( 'createpage' ) )
			) {
				$errors[] = $user->isAnon() ? array( 'nocreatetext' ) : array( 'nocreate-loggedin' );
			}
		} elseif ( $action == 'move' ) {
			if ( !$user->isAllowed( 'move-rootuserpages' )
					&& $this->title->getNamespace() == NS_USER && !$this->title->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-user-page' );
			}

			// Check if user is allowed to move files if it's a file
			if ( $this->title->getNamespace() == NS_FILE && !$user->isAllowed( 'movefile' ) ) {
				$errors[] = array( 'movenotallowedfile' );
			}

			// Check if user is allowed to move category pages if it's a category page
			if ( $this->title->getNamespace() == NS_CATEGORY && !$user->isAllowed( 'move-categorypages' ) ) {
				$errors[] = array( 'cant-move-category-page' );
			}

			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$userCanMove = User::groupHasPermission( 'user', 'move' );
				$autoconfirmedCanMove = User::groupHasPermission( 'autoconfirmed', 'move' );
				if ( $user->isAnon() && ( $userCanMove || $autoconfirmedCanMove ) ) {
					// custom message if logged-in users without any special rights can move
					$errors[] = array( 'movenologintext' );
				} else {
					$errors[] = array( 'movenotallowed' );
				}
			}
		} elseif ( $action == 'move-target' ) {
			if ( !$user->isAllowed( 'move' ) ) {
				// User can't move anything
				$errors[] = array( 'movenotallowed' );
			} elseif ( !$user->isAllowed( 'move-rootuserpages' )
					&& $this->title->getNamespace() == NS_USER && !$this->title->isSubpage() ) {
				// Show user page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-to-user-page' );
			} elseif ( !$user->isAllowed( 'move-categorypages' )
					&& $this->title->getNamespace() == NS_CATEGORY ) {
				// Show category page-specific message only if the user can move other pages
				$errors[] = array( 'cant-move-to-category-page' );
			}
		} elseif ( !$user->isAllowed( $action ) ) {
			$errors[] = $this->missingPermissionError( $action, $short );
		}

		return $errors;
	}

	/**
	 * Add the resulting error code to the errors array
	 *
	 * @param array $errors List of current errors
	 * @param array $result Result of errors
	 *
	 * @return array List of errors
	 */
	protected static function resultToError( $errors, $result ) {
		if ( is_array( $result ) && count( $result ) && !is_array( $result[0] ) ) {
			// A single array representing an error
			$errors[] = $result;
		} elseif ( is_array( $result ) && is_array( $result[0] ) ) {
			// A nested array representing multiple errors
			$errors = array_merge( $errors, $result );
		} elseif ( $result !== '' && is_string( $result ) ) {
			// A string representing a message-id
			$errors[] = array( $result );
		} elseif ( $result === false ) {
			// a generic "We don't want them to do that"
			$errors[] = array( 'badaccess-group0' );
		}
		return $errors;
	}

	/**
	 * Check various permission hooks
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkPermissionHooks( $action, $user, $errors, $rigor, $short ) {
		// Use getUserPermissionsErrors instead
		$result = '';
		if ( !Hooks::run( 'userCan', array( &$this->title, &$user, $action, &$result ) ) ) {
			return $result ? array() : array( array( 'badaccess-group0' ) );
		}
		// Check getUserPermissionsErrors hook
		if ( !Hooks::run( 'getUserPermissionsErrors',
			array( &$this->title, &$user, $action, &$result ) )
		) {
			$errors = self::resultToError( $errors, $result );
		}
		// Check getUserPermissionsErrorsExpensive hook
		if ( $rigor !== 'quick'
			&& !( $short && count( $errors ) > 0 )
			&& !Hooks::run( 'getUserPermissionsErrorsExpensive',
				array( &$this->title, &$user, $action, &$result ) )
		) {
			$errors = self::resultToError( $errors, $result );
		}

		return $errors;
	}

	/**
	 * Check permissions on special pages & namespaces
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkSpecialsAndNSPermissions( $action, $user, $errors,
		$rigor, $short
	) {
		# Only 'createaccount' can be performed on special pages,
		# which don't actually exist in the DB.
		if ( NS_SPECIAL == $this->title->getNamespace() && $action !== 'createaccount' ) {
			$errors[] = array( 'ns-specialprotected' );
		}

		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $user ) ) {
			$ns = $this->title->getNamespace() == NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $this->title->getNsText();
			$errors[] = $this->title->getNamespace() == NS_MEDIAWIKI ?
				array( 'protectedinterface', $action ) : array( 'namespaceprotected', $ns, $action );
		}

		return $errors;
	}

	/**
	 * Check CSS/JS sub-page permissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkCSSandJSPermissions( $action, $user, $errors,
		$rigor, $short
	) {
		# Protect css/js subpages of user pages
		# XXX: this might be better using restrictions
		# XXX: right 'editusercssjs' is deprecated, for backward compatibility only
		if ( $action != 'patrol' && !$user->isAllowed( 'editusercssjs' ) ) {
			if ( preg_match( '/^' . preg_quote( $user->getName(), '/' ) . '\//',
					$this->title->getText() )
			) {
				if ( $this->title->isCssSubpage()
					&& !$user->isAllowedAny( 'editmyusercss', 'editusercss' )
				) {
					$errors[] = array( 'mycustomcssprotected', $action );
				} elseif ( $this->title->isJsSubpage()
					&& !$user->isAllowedAny( 'editmyuserjs', 'edituserjs' )
				) {
					$errors[] = array( 'mycustomjsprotected', $action );
				}
			} else {
				if ( $this->title->isCssSubpage() && !$user->isAllowed( 'editusercss' ) ) {
					$errors[] = array( 'customcssprotected', $action );
				} elseif ( $this->title->isJsSubpage() && !$user->isAllowed( 'edituserjs' ) ) {
					$errors[] = array( 'customjsprotected', $action );
				}
			}
		}

		return $errors;
	}

	/**
	 * Check against page_restrictions table requirements on this
	 * page. The user must possess all required rights for this
	 * action.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkPageRestrictions( $action, $user, $errors, $rigor, $short ) {
		foreach ( $this->getRestrictions( $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> editprotected
			if ( $right == 'sysop' ) {
				$right = 'editprotected';
			}
			// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
			if ( $right == 'autoconfirmed' ) {
				$right = 'editsemiprotected';
			}
			if ( $right == '' ) {
				continue;
			}
			if ( !$user->isAllowed( $right ) ) {
				$errors[] = array( 'protectedpagetext', $right, $action );
			} elseif ( $this->mCascadeRestriction && !$user->isAllowed( 'protect' ) ) {
				$errors[] = array( 'protectedpagetext', 'protect', $action );
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkCascadingSourcesRestrictions( $action, $user, $errors,
		$rigor, $short
	) {
		if ( $rigor !== 'quick' && !$this->title->isCssJsSubpage() ) {
			# We /could/ use the protection level on the source page, but it's
			# fairly ugly as we have to establish a precedence hierarchy for pages
			# included by multiple cascade-protected pages. So just restrict
			# it to people with 'protect' permission, as they could remove the
			# protection anyway.
			list( $cascadingSources, $restrictions ) = $this->getCascadeProtectionSources();
			# Cascading protection depends on more than this page...
			# Several cascading protected pages may include this page...
			# Check each cascading level
			# This is only for protection restrictions, not for all actions
			if ( isset( $restrictions[$action] ) ) {
				foreach ( $restrictions[$action] as $right ) {
					// Backwards compatibility, rewrite sysop -> editprotected
					if ( $right == 'sysop' ) {
						$right = 'editprotected';
					}
					// Backwards compatibility, rewrite autoconfirmed -> editsemiprotected
					if ( $right == 'autoconfirmed' ) {
						$right = 'editsemiprotected';
					}
					if ( $right != '' && !$user->isAllowedAll( 'protect', $right ) ) {
						$pages = '';
						foreach ( $cascadingSources as $page ) {
							$pages .= '* [[:' . $page->getPrefixedText() . "]]\n";
						}
						$errors[] = array( 'cascadeprotected', count( $cascadingSources ), $pages, $action );
					}
				}
			}
		}

		return $errors;
	}

	/**
	 * Check action permissions not already checked in checkQuickPermissions
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkActionPermissions( $action, $user, $errors,
		$rigor, $short
	) {
		global $wgDeleteRevisionsLimit, $wgLang;

		if ( $action == 'protect' ) {
			if ( count( $this->getUserPermissionsErrorsInternal( 'edit',
				$user, $rigor, true ) )
			) {
				// If they can't edit, they shouldn't protect.
				$errors[] = array( 'protect-cantedit' );
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $this->getTitleProtection();
			if ( $title_protection ) {
				if ( $title_protection['permission'] == ''
					|| !$user->isAllowed( $title_protection['permission'] )
				) {
					$errors[] = array(
						'titleprotected',
						User::whoIs( $title_protection['user'] ),
						$title_protection['reason']
					);
				}
			}
		} elseif ( $action == 'move' ) {
			// Check for immobile pages
			if ( !MWNamespace::isMovable( $this->title->getNamespace() ) ) {
				// Specific message for this case
				$errors[] = array( 'immobile-source-namespace', $this->title->getNsText() );
			} elseif ( !$this->title->isMovable() ) {
				// Less specific message for rarer cases
				$errors[] = array( 'immobile-source-page' );
			}
		} elseif ( $action == 'move-target' ) {
			if ( !MWNamespace::isMovable( $this->title->getNamespace() ) ) {
				$errors[] = array( 'immobile-target-namespace', $this->title->getNsText() );
			} elseif ( !$this->title->isMovable() ) {
				$errors[] = array( 'immobile-target-page' );
			}
		} elseif ( $action == 'delete' ) {
			$tempErrors = $this->checkPageRestrictions( 'edit',
				$user, array(), $rigor, true );
			if ( !$tempErrors ) {
				$tempErrors = $this->checkCascadingSourcesRestrictions( 'edit',
					$user, $tempErrors, $rigor, true );
			}
			if ( $tempErrors ) {
				// If protection keeps them from editing, they shouldn't be able to delete.
				$errors[] = array( 'deleteprotected' );
			}
			if ( $rigor !== 'quick' && $wgDeleteRevisionsLimit
				&& !$this->userCan( 'bigdelete', $user ) && $this->title->isBigDeletion()
			) {
				$errors[] = array( 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) );
			}
		}
		return $errors;
	}

	/**
	 * Check that the user isn't blocked from editing.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkUserBlock( $action, $user, $errors, $rigor, $short ) {
		// Account creation blocks handled at userlogin.
		// Unblocking handled in SpecialUnblock
		if ( $rigor === 'quick' || in_array( $action, array( 'createaccount', 'unblock' ) ) ) {
			return $errors;
		}

		global $wgEmailConfirmToEdit;

		if ( $wgEmailConfirmToEdit && !$user->isEmailConfirmed() ) {
			$errors[] = array( 'confirmedittext' );
		}

		$useSlave = ( $rigor !== 'secure' );
		if ( ( $action == 'edit' || $action == 'create' )
			&& !$user->isBlockedFrom( $this->title, $useSlave )
		) {
			// Don't block the user from editing their own talk page unless they've been
			// explicitly blocked from that too.
		} elseif ( $user->isBlocked() && $user->mBlock->prevents( $action ) !== false ) {
			// @todo FIXME: Pass the relevant context into this function.
			$errors[] = $user->getBlock()->getPermissionsError( RequestContext::getMain() );
		}

		return $errors;
	}

	/**
	 * Check that the user is allowed to read this page.
	 *
	 * @param string $action The action to check
	 * @param User $user User to check
	 * @param array $errors List of current errors
	 * @param string $rigor Same format as TitleSecurity::getUserPermissionsErrors()
	 * @param bool $short Short circuit on first error
	 *
	 * @return array List of errors
	 */
	protected function checkReadPermissions( $action, $user, $errors, $rigor, $short ) {
		global $wgWhitelistRead, $wgWhitelistReadRegexp;

		$whitelisted = false;
		if ( User::isEveryoneAllowed( 'read' ) ) {
			# Shortcut for public wikis, allows skipping quite a bit of code
			$whitelisted = true;
		} elseif ( $user->isAllowed( 'read' ) ) {
			# If the user is allowed to read pages, he is allowed to read all pages
			$whitelisted = true;
		} elseif ( $this->title->isSpecial( 'Userlogin' )
			|| $this->title->isSpecial( 'ChangePassword' )
			|| $this->title->isSpecial( 'PasswordReset' )
		) {
			# Always grant access to the login page.
			# Even anons need to be able to log in.
			$whitelisted = true;
		} elseif ( is_array( $wgWhitelistRead ) && count( $wgWhitelistRead ) ) {
			# Time to check the whitelist
			# Only do these checks is there's something to check against
			$name = $this->title->getPrefixedText();
			$dbName = $this->title->getPrefixedDBkey();

			// Check for explicit whitelisting with and without underscores
			if ( in_array( $name, $wgWhitelistRead, true ) || in_array( $dbName, $wgWhitelistRead, true ) ) {
				$whitelisted = true;
			} elseif ( $this->title->getNamespace() == NS_MAIN ) {
				# Old settings might have the title prefixed with
				# a colon for main-namespace pages
				if ( in_array( ':' . $name, $wgWhitelistRead ) ) {
					$whitelisted = true;
				}
			} elseif ( $this->title->isSpecialPage() ) {
				# If it's a special page, ditch the subpage bit and check again
				$name = $this->title->getDBkey();
				list( $name, /* $subpage */ ) = SpecialPageFactory::resolveAlias( $name );
				if ( $name ) {
					$pure = SpecialPage::getTitleFor( $name )->getPrefixedText();
					if ( in_array( $pure, $wgWhitelistRead, true ) ) {
						$whitelisted = true;
					}
				}
			}
		}

		if ( !$whitelisted && is_array( $wgWhitelistReadRegexp ) && !empty( $wgWhitelistReadRegexp ) ) {
			$name = $this->title->getPrefixedText();
			// Check for regex whitelisting
			foreach ( $wgWhitelistReadRegexp as $listItem ) {
				if ( preg_match( $listItem, $name ) ) {
					$whitelisted = true;
					break;
				}
			}
		}

		if ( !$whitelisted ) {
			# If the title is not whitelisted, give extensions a chance to do so...
			Hooks::run( 'TitleReadWhitelist', array( $this->title, $user, &$whitelisted ) );
			if ( !$whitelisted ) {
				$errors[] = $this->missingPermissionError( $action, $short );
			}
		}

		return $errors;
	}

	/**
	 * Get a description array when the user doesn't have the right to perform
	 * $action (i.e. when User::isAllowed() returns false)
	 *
	 * @param string $action The action to check
	 * @param bool $short Short circuit on first error
	 * @return array List of errors
	 */
	protected function missingPermissionError( $action, $short ) {
		// We avoid expensive display logic for quickUserCan's and such
		if ( $short ) {
			return array( 'badaccess-group0' );
		}

		$groups = array_map( array( 'User', 'makeGroupLinkWiki' ),
			User::getGroupsWithPermission( $action ) );

		if ( count( $groups ) ) {
			global $wgLang;
			return array(
				'badaccess-groups',
				$wgLang->commaList( $groups ),
				count( $groups )
			);
		} else {
			return array( 'badaccess-group0' );
		}
	}

	/**
	 * Can $user perform $action on this page? This is an internal function,
	 * which checks ONLY that previously checked by userCan (i.e. it leaves out
	 * checks on wfReadOnly() and blocks)
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @param User $user User to check
	 * @param string $rigor One of (quick,full,secure)
	 *   - quick  : does cheap permission checks from slaves (usable for GUI creation)
	 *   - full   : does cheap and expensive checks possibly from a slave
	 *   - secure : does cheap and expensive checks, using the master as needed
	 * @param bool $short Set this to true to stop after the first permission error.
	 *
	 * @return array Array of arrays of the arguments to wfMessage to explain permissions problems.
	 */
	protected function getUserPermissionsErrorsInternal(
		$action, $user, $rigor = 'secure', $short = false
	) {
		if ( $rigor === true ) {
			$rigor = 'secure'; // b/c
		} elseif ( $rigor === false ) {
			$rigor = 'quick'; // b/c
		} elseif ( !in_array( $rigor, array( 'quick', 'full', 'secure' ) ) ) {
			throw new Exception( "Invalid rigor parameter '$rigor'." );
		}

		# Read has special handling
		if ( $action == 'read' ) {
			$checks = array(
				'checkPermissionHooks',
				'checkReadPermissions',
			);
		# Don't call checkSpecialsAndNSPermissions or checkCSSandJSPermissions
		# here as it will lead to duplicate error messages. This is okay to do
		# since anywhere that checks for create will also check for edit, and
		# those checks are called for edit.
		} elseif ( $action == 'create' ) {
			$checks = array(
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			);
		} else {
			$checks = array(
				'checkQuickPermissions',
				'checkPermissionHooks',
				'checkSpecialsAndNSPermissions',
				'checkCSSandJSPermissions',
				'checkPageRestrictions',
				'checkCascadingSourcesRestrictions',
				'checkActionPermissions',
				'checkUserBlock'
			);
		}

		$errors = array();
		while ( count( $checks ) > 0 &&
				!( $short && count( $errors ) > 0 ) ) {
			$method = array_shift( $checks );
			$errors = $this->$method( $action, $user, $errors, $rigor, $short );
		}

		return $errors;
	}

	/**
	 * Get a filtered list of all restriction types supported by this wiki.
	 * @param bool $exists True to get all restriction types that apply to
	 * titles that do exist, False for all restriction types that apply to
	 * titles that do not exist
	 * @return array
	 */
	public static function getFilteredRestrictionTypes( $exists = true ) {
		global $wgRestrictionTypes;
		$types = $wgRestrictionTypes;
		if ( $exists ) {
			# Remove the create restriction for existing titles
			$types = array_diff( $types, array( 'create' ) );
		} else {
			# Only the create and upload restrictions apply to non-existing titles
			$types = array_intersect( $types, array( 'create', 'upload' ) );
		}
		return $types;
	}

	/**
	 * Returns restriction types for the current Title
	 *
	 * @return array Applicable restriction types
	 */
	public function getRestrictionTypes() {
		if ( $this->title->isSpecialPage() ) {
			return array();
		}

		$types = self::getFilteredRestrictionTypes( $this->title->exists() );

		if ( $this->title->getNamespace() != NS_FILE ) {
			# Remove the upload restriction for non-file titles
			$types = array_diff( $types, array( 'upload' ) );
		}

		Hooks::run( 'TitleGetRestrictionTypes', array( $this->title, &$types ) );

		wfDebug( __METHOD__ . ': applicable restrictions to [[' .
			$this->title->getPrefixedText() . ']] are {' . implode( ',', $types ) . "}\n" );

		return $types;
	}

	/**
	 * Is this title subject to title protection?
	 * Title protection is the one applied against creation of such title.
	 *
	 * @return array|bool An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	public function getTitleProtection() {
		// Can't protect pages in special namespaces
		if ( $this->title->getNamespace() < 0 ) {
			return false;
		}

		// Can't protect pages that exist.
		if ( $this->title->exists() ) {
			return false;
		}

		if ( $this->mTitleProtection === null ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'protected_titles',
				array(
					'user' => 'pt_user',
					'reason' => 'pt_reason',
					'expiry' => 'pt_expiry',
					'permission' => 'pt_create_perm'
				),
				array( 'pt_namespace' => $this->title->getNamespace(), 'pt_title' => $this->title->getDBkey() ),
				__METHOD__
			);

			// fetchRow returns false if there are no rows.
			$row = $dbr->fetchRow( $res );
			if ( $row ) {
				if ( $row['permission'] == 'sysop' ) {
					$row['permission'] = 'editprotected'; // B/C
				}
				if ( $row['permission'] == 'autoconfirmed' ) {
					$row['permission'] = 'editsemiprotected'; // B/C
				}
			}
			$this->mTitleProtection = $row;
		}
		return $this->mTitleProtection;
	}

	/**
	 * Remove any title protection due to page existing
	 */
	public function deleteTitleProtection() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete(
			'protected_titles',
			array( 'pt_namespace' => $this->title->getNamespace(), 'pt_title' => $this->title->getDBkey() ),
			__METHOD__
		);
		$this->mTitleProtection = false;
	}

	/**
	 * Is this page "semi-protected" - the *only* protection levels are listed
	 * in $wgSemiprotectedRestrictionLevels?
	 *
	 * @param string $action Action to check (default: edit)
	 * @return bool
	 */
	public function isSemiProtected( $action = 'edit' ) {
		global $wgSemiprotectedRestrictionLevels;

		$restrictions = $this->getRestrictions( $action );
		$semi = $wgSemiprotectedRestrictionLevels;
		if ( !$restrictions || !$semi ) {
			// Not protected, or all protection is full protection
			return false;
		}

		// Remap autoconfirmed to editsemiprotected for BC
		foreach ( array_keys( $semi, 'autoconfirmed' ) as $key ) {
			$semi[$key] = 'editsemiprotected';
		}
		foreach ( array_keys( $restrictions, 'autoconfirmed' ) as $key ) {
			$restrictions[$key] = 'editsemiprotected';
		}

		return !array_diff( $restrictions, $semi );
	}

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param string $action The action the page is protected from,
	 * by default checks all actions.
	 * @return bool
	 */
	public function isProtected( $action = '' ) {
		global $wgRestrictionLevels;

		$restrictionTypes = $this->getRestrictionTypes();

		# Special pages have inherent protection
		if ( $this->title->isSpecialPage() ) {
			return true;
		}

		# Check regular protection levels
		foreach ( $restrictionTypes as $type ) {
			if ( $action == $type || $action == '' ) {
				$r = $this->getRestrictions( $type );
				foreach ( $wgRestrictionLevels as $level ) {
					if ( in_array( $level, $r ) && $level != '' ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Determines if $user is unable to edit this page because it has been protected
	 * by $wgNamespaceProtection.
	 *
	 * @param User $user User object to check permissions
	 * @return bool
	 */
	public function isNamespaceProtected( User $user ) {
		global $wgNamespaceProtection;

		if ( isset( $wgNamespaceProtection[$this->title->getNamespace()] ) ) {
			foreach ( (array)$wgNamespaceProtection[$this->title->getNamespace()] as $right ) {
				if ( $right != '' && !$user->isAllowed( $right ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Cascading protection: Return true if cascading restrictions apply to this page, false if not.
	 *
	 * @return bool If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected() {
		list( $sources, /* $restrictions */ ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Determines whether cascading protection sources have already been loaded from
	 * the database.
	 *
	 * @param bool $getPages True to check if the pages are loaded, or false to check
	 * if the status is loaded.
	 * @return bool Whether or not the specified information has been loaded
	 */
	public function areCascadeProtectionSourcesLoaded( $getPages = true ) {
		return $getPages ? $this->mCascadeSources !== null : $this->mHasCascadingRestrictions !== null;
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param bool $getPages Whether or not to retrieve the actual pages
	 *        that the restrictions have come from and the actual restrictions
	 *        themselves.
	 * @return array Two elements: First is an array of Title objects of the
	 *        pages from which cascading restrictions have come, false for
	 *        none, or true if such restrictions exist but $getPages was not
	 *        set. Second is an array like that returned by
	 *        TitleSecurity::getAllRestrictions(), or an empty array if $getPages is
	 *        false.
	 */
	public function getCascadeProtectionSources( $getPages = true ) {
		global $wgContLang;
		$pagerestrictions = array();

		if ( $this->mCascadeSources !== null && $getPages ) {
			return array( $this->mCascadeSources, $this->mCascadingRestrictions );
		} elseif ( $this->mHasCascadingRestrictions !== null && !$getPages ) {
			return array( $this->mHasCascadingRestrictions, $pagerestrictions );
		}

		$dbr = wfGetDB( DB_SLAVE );

		if ( $this->title->getNamespace() == NS_FILE ) {
			$tables = array( 'imagelinks', 'page_restrictions' );
			$where_clauses = array(
				'il_to' => $this->title->getDBkey(),
				'il_from=pr_page',
				'pr_cascade' => 1
			);
		} else {
			$tables = array( 'templatelinks', 'page_restrictions' );
			$where_clauses = array(
				'tl_namespace' => $this->title->getNamespace(),
				'tl_title' => $this->title->getDBkey(),
				'tl_from=pr_page',
				'pr_cascade' => 1
			);
		}

		if ( $getPages ) {
			$cols = array( 'pr_page', 'page_namespace', 'page_title',
				'pr_expiry', 'pr_type', 'pr_level' );
			$where_clauses[] = 'page_id=pr_page';
			$tables[] = 'page';
		} else {
			$cols = array( 'pr_expiry' );
		}

		$res = $dbr->select( $tables, $cols, $where_clauses, __METHOD__ );

		$sources = $getPages ? array() : false;
		$now = wfTimestampNow();

		foreach ( $res as $row ) {
			$expiry = $wgContLang->formatExpiry( $row->pr_expiry, TS_MW );
			if ( $expiry > $now ) {
				if ( $getPages ) {
					$page_id = $row->pr_page;
					$page_ns = $row->page_namespace;
					$page_title = $row->page_title;
					$sources[$page_id] = Title::makeTitle( $page_ns, $page_title );
					# Add groups needed for each restriction type if its not already there
					# Make sure this restriction type still exists

					if ( !isset( $pagerestrictions[$row->pr_type] ) ) {
						$pagerestrictions[$row->pr_type] = array();
					}

					if (
						isset( $pagerestrictions[$row->pr_type] )
						&& !in_array( $row->pr_level, $pagerestrictions[$row->pr_type] )
					) {
						$pagerestrictions[$row->pr_type][] = $row->pr_level;
					}
				} else {
					$sources = true;
				}
			}
		}

		if ( $getPages ) {
			$this->mCascadeSources = $sources;
			$this->mCascadingRestrictions = $pagerestrictions;
		} else {
			$this->mHasCascadingRestrictions = $sources;
		}

		return array( $sources, $pagerestrictions );
	}

	/**
	 * Accessor for mRestrictionsLoaded
	 *
	 * @return bool Whether or not the page's restrictions have already been
	 * loaded from the database
	 */
	public function areRestrictionsLoaded() {
		return $this->mRestrictionsLoaded;
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param string $action Action that permission needs to be checked for
	 * @return array Restriction levels needed to take the action. All levels
	 *     are required.
	 */
	public function getRestrictions( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictions[$action] )
				? $this->mRestrictions[$action]
				: array();
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @return array Keys are actions, values are arrays as returned by
	 *     TitleSecurity::getRestrictions()
	 */
	public function getAllRestrictions() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return $this->mRestrictions;
	}

	/**
	 * Get the expiry time for the restriction against a given action
	 *
	 * @param string $action
	 * @return string|bool 14-char timestamp, or 'infinity' if the page is protected forever
	 *     or not protected at all, or false if the action is not recognised.
	 */
	public function getRestrictionExpiry( $action ) {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}
		return isset( $this->mRestrictionsExpiry[$action] ) ? $this->mRestrictionsExpiry[$action] : false;
	}

	/**
	 * Returns cascading restrictions for the current article
	 *
	 * @return bool
	 */
	public function areRestrictionsCascading() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}

		return $this->mCascadeRestriction;
	}

	/**
	 * Loads a string into mRestrictions array
	 *
	 * @param ResultWrapper $res Resource restrictions as an SQL result.
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	protected function loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions = null ) {
		$rows = array();

		foreach ( $res as $row ) {
			$rows[] = $row;
		}

		$this->loadRestrictionsFromRows( $rows, $oldFashionedRestrictions );
	}

	/**
	 * Compiles list of active page restrictions from both page table (pre 1.10)
	 * and page_restrictions table for this existing page.
	 * Public for usage by LiquidThreads.
	 *
	 * @param array $rows Array of db result objects
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *   restrictions from page table (pre 1.10)
	 */
	public function loadRestrictionsFromRows( $rows, $oldFashionedRestrictions = null ) {
		global $wgContLang;
		$dbr = wfGetDB( DB_SLAVE );

		$restrictionTypes = $this->getRestrictionTypes();

		foreach ( $restrictionTypes as $type ) {
			$this->mRestrictions[$type] = array();
			$this->mRestrictionsExpiry[$type] = $wgContLang->formatExpiry( '', TS_MW );
		}

		$this->mCascadeRestriction = false;

		# Backwards-compatibility: also load the restrictions from the page record (old format).

		if ( $oldFashionedRestrictions === null ) {
			$oldFashionedRestrictions = $dbr->selectField( 'page', 'page_restrictions',
				array( 'page_id' => $this->title->getArticleID() ), __METHOD__ );
		}

		if ( $oldFashionedRestrictions != '' ) {

			foreach ( explode( ':', trim( $oldFashionedRestrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if ( count( $temp ) == 1 ) {
					// old old format should be treated as edit/move restriction
					$this->mRestrictions['edit'] = explode( ',', trim( $temp[0] ) );
					$this->mRestrictions['move'] = explode( ',', trim( $temp[0] ) );
				} else {
					$restriction = trim( $temp[1] );
					if ( $restriction != '' ) { //some old entries are empty
						$this->mRestrictions[$temp[0]] = explode( ',', $restriction );
					}
				}
			}

			$this->mOldRestrictions = true;

		}

		if ( count( $rows ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();

			# Cycle through all the restrictions.
			foreach ( $rows as $row ) {

				// Don't take care of restrictions types that aren't allowed
				if ( !in_array( $row->pr_type, $restrictionTypes ) ) {
					continue;
				}

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = $wgContLang->formatExpiry( $row->pr_expiry, TS_MW );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction |= $row->pr_cascade;
				}
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param string $oldFashionedRestrictions Comma-separated list of page
	 *   restrictions from page table (pre 1.10)
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null ) {
		global $wgContLang;
		if ( !$this->mRestrictionsLoaded ) {
			if ( $this->title->exists() ) {
				$dbr = wfGetDB( DB_SLAVE );

				$res = $dbr->select(
					'page_restrictions',
					array( 'pr_type', 'pr_expiry', 'pr_level', 'pr_cascade' ),
					array( 'pr_page' => $this->title->getArticleID() ),
					__METHOD__
				);

				$this->loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions );
			} else {
				$title_protection = $this->getTitleProtection();

				if ( $title_protection ) {
					$now = wfTimestampNow();
					$expiry = $wgContLang->formatExpiry( $title_protection['expiry'], TS_MW );

					if ( !$expiry || $expiry > $now ) {
						// Apply the restrictions
						$this->mRestrictionsExpiry['create'] = $expiry;
						$this->mRestrictions['create'] = explode( ',', trim( $title_protection['permission'] ) );
					} else { // Get rid of the old restrictions
						$this->mTitleProtection = false;
					}
				} else {
					$this->mRestrictionsExpiry['create'] = $wgContLang->formatExpiry( '', TS_MW );
				}
				$this->mRestrictionsLoaded = true;
			}
		}
	}

	/**
	 * Flush the protection cache in this object and force reload from the database.
	 * This is used when updating protection from WikiPage::doUpdateRestrictions().
	 */
	public function flushRestrictions() {
		$this->mRestrictionsLoaded = false;
		$this->mTitleProtection = null;
	}

	/**
	 * Purge expired restrictions from the page_restrictions table
	 */
	public static function purgeExpiredRestrictions() {
		if ( wfReadOnly() ) {
			return;
		}

		$method = __METHOD__;
		$dbw = wfGetDB( DB_MASTER );
		$dbw->onTransactionIdle( function () use ( $dbw, $method ) {
			$dbw->delete(
				'page_restrictions',
				array( 'pr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
				$method
			);
			$dbw->delete(
				'protected_titles',
				array( 'pt_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
				$method
			);
		} );
	}
}
