<?php

class Security {
	function showEditRestrictions( EditPage $page ) {
		if ( $this->mTitle->getNamespace() != NS_MEDIAWIKI && $this->mTitle->isProtected( 'edit' ) ) {
			# Is the title semi-protected?
			if ( $this->mTitle->isSemiProtected() ) {
				$noticeMsg = 'semiprotectedpagewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagewarning';
			}
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle, '',
				array( 'lim' => 1, 'msgKey' => array( $noticeMsg ) ) );
		}
		if ( $this->mTitle->isCascadeProtected() ) {
			# Is this page under cascading protection from some source pages?
			list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();
			$notice = "<div class='mw-cascadeprotectedwarning'>\n$1\n";
			$cascadeSourcesCount = count( $cascadeSources );
			if ( $cascadeSourcesCount > 0 ) {
				# Explain, and list the titles responsible
				foreach ( $cascadeSources as $page ) {
					$notice .= '* [[:' . $page->getPrefixedText() . "]]\n";
				}
			}
			$notice .= '</div>';
			$wgOut->wrapWikiMsg( $notice, array( 'cascadeprotectedwarning', $cascadeSourcesCount ) );
		}
		if ( !$this->mTitle->exists() && $this->mTitle->getRestrictions( 'create' ) ) {
			LogEventsList::showLogExtract( $wgOut, 'protect', $this->mTitle, '',
				array( 'lim' => 1,
					'showIfEmpty' => false,
					'msgKey' => array( 'titleprotectedwarning' ),
					'wrap' => "<div class=\"mw-titleprotectedwarning\">\n$1</div>" ) );
		}
	}
	static function protectionlevel( $parser, $type = '', $title = '' ) {
	Security::loadPageRestrictions( $this, $data->page_restrictions );
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param array $limit set of restriction keys
	 * @param $reason String
	 * @param &$cascade Integer. Set to false if cascading protection isn't allowed.
	 * @param array $expiry per restriction type expiration
	 * @param $user User The user updating the restrictions
	 * @return Status
	 */
	public function doUpdateRestrictions( array $limit, array $expiry, &$cascade, $reason, User $user ) {
		global $wgContLang;

		if ( wfReadOnly() ) {
			return Status::newFatal( 'readonlytext', wfReadOnlyReason() );
		}

		$restrictionTypes = $this->mTitle->getRestrictionTypes();

		$id = $this->getId();

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		// @todo FIXME: Same limitations as described in ProtectionForm.php (line 37);
		// we expect a single selection, but the schema allows otherwise.
		$isProtected = false;
		$protect = false;
		$changed = false;

		$dbw = wfGetDB( DB_MASTER );

		foreach ( $restrictionTypes as $action ) {
			if ( !isset( $expiry[$action] ) ) {
				$expiry[$action] = $dbw->getInfinity();
			}
			if ( !isset( $limit[$action] ) ) {
				$limit[$action] = '';
			} elseif ( $limit[$action] != '' ) {
				$protect = true;
			}

			// Get current restrictions on $action
			$current = implode( '', $this->mTitle->getRestrictions( $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				// Only check expiry change if the action is actually being
				// protected, since expiry does nothing on an not-protected
				// action.
				if ( $this->mTitle->getRestrictionExpiry( $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $this->mTitle->areRestrictionsCascading() != $cascade ) {
			$changed = true;
		}

		// If nothing has changed, do nothing
		if ( !$changed ) {
			return Status::newGood();
		}

		if ( !$protect ) { // No protection at all means unprotection
			$revCommentMsg = 'unprotectedarticle';
			$logAction = 'unprotect';
		} elseif ( $isProtected ) {
			$revCommentMsg = 'modifiedarticleprotection';
			$logAction = 'modify';
		} else {
			$revCommentMsg = 'protectedarticle';
			$logAction = 'protect';
		}

		$encodedExpiry = array();
		$protectDescription = '';
		# Some bots may parse IRC lines, which are generated from log entries which contain plain
		# protect description text. Keep them in old format to avoid breaking compatibility.
		# TODO: Fix protection log to store structured description and format it on-the-fly.
		$protectDescriptionLog = '';
		foreach ( $limit as $action => $restrictions ) {
			$encodedExpiry[$action] = $dbw->encodeExpiry( $expiry[$action] );
			if ( $restrictions != '' ) {
				$protectDescriptionLog .= $wgContLang->getDirMark() . "[$action=$restrictions] (";
				# $action is one of $wgRestrictionTypes = array( 'create', 'edit', 'move', 'upload' ).
				# All possible message keys are listed here for easier grepping:
				# * restriction-create
				# * restriction-edit
				# * restriction-move
				# * restriction-upload
				$actionText = wfMessage( 'restriction-' . $action )->inContentLanguage()->text();
				# $restrictions is one of $wgRestrictionLevels = array( '', 'autoconfirmed', 'sysop' ),
				# with '' filtered out. All possible message keys are listed below:
				# * protect-level-autoconfirmed
				# * protect-level-sysop
				$restrictionsText = wfMessage( 'protect-level-' . $restrictions )->inContentLanguage()->text();
				if ( $encodedExpiry[$action] != 'infinity' ) {
					$expiryText = wfMessage(
						'protect-expiring',
						$wgContLang->timeanddate( $expiry[$action], false, false ),
						$wgContLang->date( $expiry[$action], false, false ),
						$wgContLang->time( $expiry[$action], false, false )
					)->inContentLanguage()->text();
				} else {
					$expiryText = wfMessage( 'protect-expiry-indefinite' )
						->inContentLanguage()->text();
				}

				if ( $protectDescription !== '' ) {
					$protectDescription .= wfMessage( 'word-separator' )->inContentLanguage()->text();
				}
				$protectDescription .= wfMessage( 'protect-summary-desc' )
					->params( $actionText, $restrictionsText, $expiryText )
					->inContentLanguage()->text();
				$protectDescriptionLog .= $expiryText . ') ';
			}
		}
		$protectDescriptionLog = trim( $protectDescriptionLog );

		if ( $id ) { // Protection of existing page
			if ( !wfRunHooks( 'ArticleProtect', array( &$this, &$user, $limit, $reason ) ) ) {
				return Status::newGood();
			}

			// Only restrictions with the 'protect' right can cascade...
			// Otherwise, people who cannot normally protect can "protect" pages via transclusion
			$editrestriction = isset( $limit['edit'] ) ? array( $limit['edit'] ) : $this->mTitle->getRestrictions( 'edit' );

			// The schema allows multiple restrictions
			if ( !in_array( 'protect', $editrestriction ) && !in_array( 'sysop', $editrestriction ) ) {
				$cascade = false;
			}

			// Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
				if ( $restrictions != '' ) {
					$dbw->replace( 'page_restrictions', array( array( 'pr_page', 'pr_type' ) ),
						array( 'pr_page' => $id,
							'pr_type' => $action,
							'pr_level' => $restrictions,
							'pr_cascade' => ( $cascade && $action == 'edit' ) ? 1 : 0,
							'pr_expiry' => $encodedExpiry[$action]
						),
						__METHOD__
					);
				} else {
					$dbw->delete( 'page_restrictions', array( 'pr_page' => $id,
						'pr_type' => $action ), __METHOD__ );
				}
			}

			// Prepare a null revision to be added to the history
			$editComment = $wgContLang->ucfirst(
				wfMessage(
					$revCommentMsg,
					$this->mTitle->getPrefixedText()
				)->inContentLanguage()->text()
			);
			if ( $reason ) {
				$editComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
			}
			if ( $protectDescription ) {
				$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
				$editComment .= wfMessage( 'parentheses' )->params( $protectDescription )->inContentLanguage()->text();
			}
			if ( $cascade ) {
				$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
				$editComment .= wfMessage( 'brackets' )->params(
					wfMessage( 'protect-summary-cascade' )->inContentLanguage()->text()
				)->inContentLanguage()->text();
			}

			// Insert a null revision
			$nullRevision = Revision::newNullRevision( $dbw, $id, $editComment, true );
			$nullRevId = $nullRevision->insertOn( $dbw );

			$latest = $this->getLatest();
			// Update page record
			$dbw->update( 'page',
				array( /* SET */
					'page_touched' => $dbw->timestamp(),
					'page_restrictions' => '',
					'page_latest' => $nullRevId
				), array( /* WHERE */
					'page_id' => $id
				), __METHOD__
			);

			wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $nullRevision, $latest, $user ) );
			wfRunHooks( 'ArticleProtectComplete', array( &$this, &$user, $limit, $reason ) );
		} else { // Protection of non-existing page (also known as "title protection")
			// Cascade protection is meaningless in this case
			$cascade = false;

			if ( $limit['create'] != '' ) {
				$dbw->replace( 'protected_titles',
					array( array( 'pt_namespace', 'pt_title' ) ),
					array(
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey(),
						'pt_create_perm' => $limit['create'],
						'pt_timestamp' => $dbw->encodeExpiry( wfTimestampNow() ),
						'pt_expiry' => $encodedExpiry['create'],
						'pt_user' => $user->getId(),
						'pt_reason' => $reason,
					), __METHOD__
				);
			} else {
				$dbw->delete( 'protected_titles',
					array(
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey()
					), __METHOD__
				);
			}
		}

		$this->mTitle->flushRestrictions();

		if ( $logAction == 'unprotect' ) {
			$logParams = array();
		} else {
			$logParams = array( $protectDescriptionLog, $cascade ? 'cascade' : '' );
		}

		// Update the protection log
		$log = new LogPage( 'protect' );
		$log->addEntry( $logAction, $this->mTitle, trim( $reason ), $logParams, $user );

		return Status::newGood();
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 * @param $limit Array
	 * @throws MWException
	 * @return String
	 */
	protected static function flattenRestrictions( $limit ) {
		if ( !is_array( $limit ) ) {
			throw new MWException( 'WikiPage::flattenRestrictions given non-array restriction set' );
		}

		$bits = array();
		ksort( $limit );

		foreach ( $limit as $action => $restrictions ) {
			if ( $restrictions != '' ) {
				$bits[] = "$action=$restrictions";
			}
		}

		return implode( ':', $bits );
	}
<<<<<<< HEAD
	 * Updates cascading protections
	 *
	 * @param $parserOutput ParserOutput object for the current version
	 */
	public function doCascadeProtectionUpdates( ParserOutput $parserOutput ) {
		if ( wfReadOnly() || !$this->mTitle->areRestrictionsCascading() ) {
			return;
		}

		// templatelinks table may have become out of sync,
		// especially if using variable-based transclusions.
		// For paranoia, check if things have changed and if
		// so apply updates to the database. This will ensure
		// that cascaded protections apply as soon as the changes
		// are visible.

		// Get templates from templatelinks
		$id = $this->getId();

		$tlTemplates = array();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'templatelinks' ),
			array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $id ),
			__METHOD__
		);

		foreach ( $res as $row ) {
			$tlTemplates["{$row->tl_namespace}:{$row->tl_title}"] = true;
		}

		// Get templates from parser output.
		$poTemplates = array();
		foreach ( $parserOutput->getTemplates() as $ns => $templates ) {
			foreach ( $templates as $dbk => $id ) {
				$poTemplates["$ns:$dbk"] = true;
			}
		}

		// Get the diff
		$templates_diff = array_diff_key( $poTemplates, $tlTemplates );

		if ( count( $templates_diff ) > 0 ) {
			// Whee, link updates time.
			// Note: we are only interested in links here. We don't need to get other DataUpdate items from the parser output.
			$u = new LinksUpdate( $this->mTitle, $parserOutput, false );
			$u->doUpdate();
		}
	}

	/**
<<<<<<< HEAD
	 * Update the article's restriction field, and leave a log entry.
	 *
	 * @deprecated since 1.19
	 * @param array $limit set of restriction keys
	 * @param $reason String
	 * @param &$cascade Integer. Set to false if cascading protection isn't allowed.
	 * @param array $expiry per restriction type expiration
	 * @param $user User The user updating the restrictions
	 * @return bool true on success
	 */
	public function updateRestrictions(
		$limit = array(), $reason = '', &$cascade = 0, $expiry = array(), User $user = null
	) {
		global $wgUser;

		$user = is_null( $user ) ? $wgUser : $user;

		return $this->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $user )->isOK();
	}

	function checkEditProtection( $this, $user, $errors ) {
		# Check $wgNamespaceProtection for restricted namespaces
		if ( $this->isNamespaceProtected( $user ) ) {
			$ns = $this->mNamespace == NS_MAIN ?
				wfMessage( 'nstab-main' )->text() : $this->getNsText();
			$errors[] = $this->mNamespace == NS_MEDIAWIKI ?
				array( 'protectedinterface' ) : array( 'namespaceprotected', $ns );
		}

<<<<<<< HEAD
	 * Check against page_restrictions table requirements on this
	 * page. The user must possess all required rights for this
	 * action.
	 *
	 * @param string $action the action to check
	 * @param $user User user to check
	 * @param array $errors list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkPageRestrictions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		foreach ( $this->getRestrictions( $action ) as $right ) {
			// Backwards compatibility, rewrite sysop -> protect
			if ( $right == 'sysop' ) {
				$right = 'protect';
			}
			if ( $right != '' && !$user->isAllowed( $right ) ) {
				// Users with 'editprotected' permission can edit protected pages
				// without cascading option turned on.
				if ( $action != 'edit' || !$user->isAllowed( 'editprotected' )
					|| $this->mCascadeRestriction )
				{
					$errors[] = array( 'protectedpagetext', $right );
				}
			}
		}

		return $errors;
	}

	/**
	 * Check restrictions on cascading pages.
	 *
	 * @param string $action the action to check
	 * @param $user User to check
	 * @param array $errors list of current errors
	 * @param $doExpensiveQueries Boolean whether or not to perform expensive queries
	 * @param $short Boolean short circuit on first error
	 *
	 * @return Array list of errors
	 */
	private function checkCascadingSourcesRestrictions( $action, $user, $errors, $doExpensiveQueries, $short ) {
		if ( $doExpensiveQueries && !$this->isCssJsSubpage() ) {
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
					$right = ( $right == 'sysop' ) ? 'protect' : $right;
					if ( $right != '' && !$user->isAllowed( $right ) ) {
						$pages = '';
						foreach ( $cascadingSources as $page )
							$pages .= '* [[:' . $page->getPrefixedText() . "]]\n";
						$errors[] = array( 'cascadeprotected', count( $cascadingSources ), $pages );
					}
				}
			}
		}

		return $errors;
	}

	function checkActionPermissions( $this, $action, $user, $errors ) {
		if ( $action == 'protect' ) {
			if ( count( $this->getUserPermissionsErrorsInternal( 'edit', $user, $doExpensiveQueries, true ) ) ) {
				// If they can't edit, they shouldn't protect.
				$errors[] = array( 'protect-cantedit' );
			}
		} elseif ( $action == 'create' ) {
			$title_protection = $this->getTitleProtection();
			if( $title_protection ) {
				if( $title_protection['pt_create_perm'] == 'sysop' ) {
					$title_protection['pt_create_perm'] = 'protect'; // B/C
				}
				if( $title_protection['pt_create_perm'] == '' ||
					!$user->isAllowed( $title_protection['pt_create_perm'] ) )
				{
					$errors[] = array( 'titleprotected', User::whoIs( $title_protection['pt_user'] ), $title_protection['pt_reason'] );
				}
			}
		} elseif ( $action == 'move' ) {
<<<<<<< HEAD
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
	 * @return array applicable restriction types
	 */
	public function getRestrictionTypes() {
		if ( $this->isSpecialPage() ) {
			return array();
		}

		$types = self::getFilteredRestrictionTypes( $this->exists() );

		if ( $this->getNamespace() != NS_FILE ) {
			# Remove the upload restriction for non-file titles
			$types = array_diff( $types, array( 'upload' ) );
		}

		wfRunHooks( 'TitleGetRestrictionTypes', array( $this, &$types ) );

		wfDebug( __METHOD__ . ': applicable restrictions to [[' .
			$this->getPrefixedText() . ']] are {' . implode( ',', $types ) . "}\n" );

		return $types;
	}

	/**
	 * Is this title subject to title protection?
	 * Title protection is the one applied against creation of such title.
	 *
	 * @return Mixed An associative array representing any existent title
	 *   protection, or false if there's none.
	 */
	private function getTitleProtection() {
		// Can't protect pages in special namespaces
		if ( $this->getNamespace() < 0 ) {
			return false;
		}

		// Can't protect pages that exist.
		if ( $this->exists() ) {
			return false;
		}

		if ( !isset( $this->mTitleProtection ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'protected_titles',
				array( 'pt_user', 'pt_reason', 'pt_expiry', 'pt_create_perm' ),
				array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ),
				__METHOD__
			);

			// fetchRow returns false if there are no rows.
			$this->mTitleProtection = $dbr->fetchRow( $res );
		}
		return $this->mTitleProtection;
	}

	/**
	 * Update the title protection status
	 *
	 * @deprecated in 1.19; will be removed in 1.20. Use WikiPage::doUpdateRestrictions() instead.
	 * @param $create_perm String Permission required for creation
	 * @param string $reason Reason for protection
	 * @param string $expiry Expiry timestamp
	 * @return boolean true
	 */
	public function updateTitleProtection( $create_perm, $reason, $expiry ) {
		wfDeprecated( __METHOD__, '1.19' );

		global $wgUser;

		$limit = array( 'create' => $create_perm );
		$expiry = array( 'create' => $expiry );

		$page = WikiPage::factory( $this );
		$cascade = false;
		$status = $page->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $wgUser );

		return $status->isOK();
	}

	/**
	 * Remove any title protection due to page existing
	 */
	public function deleteTitleProtection() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->delete(
			'protected_titles',
			array( 'pt_namespace' => $this->getNamespace(), 'pt_title' => $this->getDBkey() ),
			__METHOD__
		);
		$this->mTitleProtection = false;
	}

	/**
	 * Is this page "semi-protected" - the *only* protection is autoconfirm?
	 *
	 * @param string $action Action to check (default: edit)
	 * @return Bool
	 */
	public function isSemiProtected( $action = 'edit' ) {
		if ( $this->exists() ) {
			$restrictions = $this->getRestrictions( $action );
			if ( count( $restrictions ) > 0 ) {
				foreach ( $restrictions as $restriction ) {
					if ( strtolower( $restriction ) != 'autoconfirmed' ) {
						return false;
					}
				}
			} else {
				# Not protected
				return false;
			}
			return true;
		} else {
			# If it doesn't exist, it can't be protected
			return false;
		}
	}

	/**
	 * Does the title correspond to a protected article?
	 *
	 * @param string $action the action the page is protected from,
	 * by default checks all actions.
	 * @return Bool
	 */
	public function isProtected( $action = '' ) {
		global $wgRestrictionLevels;

		$restrictionTypes = $this->getRestrictionTypes();

		# Special pages have inherent protection
		if( $this->isSpecialPage() ) {
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
	 * @param $user User object to check permissions
	 * @return Bool
	 */
	public function isNamespaceProtected( User $user ) {
		global $wgNamespaceProtection;

		if ( isset( $wgNamespaceProtection[$this->mNamespace] ) ) {
			foreach ( (array)$wgNamespaceProtection[$this->mNamespace] as $right ) {
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
	 * @return Bool If the page is subject to cascading restrictions.
	 */
	public function isCascadeProtected() {
		list( $sources, /* $restrictions */ ) = $this->getCascadeProtectionSources( false );
		return ( $sources > 0 );
	}

	/**
	 * Cascading protection: Get the source of any cascading restrictions on this page.
	 *
	 * @param bool $getPages Whether or not to retrieve the actual pages
	 *        that the restrictions have come from.
	 * @return Mixed Array of Title objects of the pages from which cascading restrictions
	 *     have come, false for none, or true if such restrictions exist, but $getPages
	 *     was not set.  The restriction array is an array of each type, each of which
	 *     contains a array of unique groups.
	 */
	public function getCascadeProtectionSources( $getPages = true ) {
		global $wgContLang;
		$pagerestrictions = array();

		if ( isset( $this->mCascadeSources ) && $getPages ) {
			return array( $this->mCascadeSources, $this->mCascadingRestrictions );
		} elseif ( isset( $this->mHasCascadingRestrictions ) && !$getPages ) {
			return array( $this->mHasCascadingRestrictions, $pagerestrictions );
		}

		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );

		if ( $this->getNamespace() == NS_FILE ) {
			$tables = array( 'imagelinks', 'page_restrictions' );
			$where_clauses = array(
				'il_to' => $this->getDBkey(),
				'il_from=pr_page',
				'pr_cascade' => 1
			);
		} else {
			$tables = array( 'templatelinks', 'page_restrictions' );
			$where_clauses = array(
				'tl_namespace' => $this->getNamespace(),
				'tl_title' => $this->getDBkey(),
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
		$purgeExpired = false;

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
			} else {
				// Trigger lazy purge of expired restrictions from the db
				$purgeExpired = true;
			}
		}
		if ( $purgeExpired ) {
			Title::purgeExpiredRestrictions();
		}

		if ( $getPages ) {
			$this->mCascadeSources = $sources;
			$this->mCascadingRestrictions = $pagerestrictions;
		} else {
			$this->mHasCascadingRestrictions = $sources;
		}

		wfProfileOut( __METHOD__ );
		return array( $sources, $pagerestrictions );
	}

	/**
	 * Accessor/initialisation for mRestrictions
	 *
	 * @param string $action action that permission needs to be checked for
	 * @return Array of Strings the array of groups allowed to edit this article
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
	 * Get the expiry time for the restriction against a given action
	 *
	 * @param $action
	 * @return String|Bool 14-char timestamp, or 'infinity' if the page is protected forever
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
	 * @return Boolean
	 */
	function areRestrictionsCascading() {
		if ( !$this->mRestrictionsLoaded ) {
			$this->loadRestrictions();
		}

		return $this->mCascadeRestriction;
	}

	/**
	 * Loads a string into mRestrictions array
	 *
	 * @param $res Resource restrictions as an SQL result.
	 * @param string $oldFashionedRestrictions comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	private function loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions = null ) {
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
	 * @param array $rows of db result objects
	 * @param string $oldFashionedRestrictions comma-separated list of page
	 *        restrictions from page table (pre 1.10)
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
				array( 'page_id' => $this->getArticleID() ), __METHOD__ );
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
					if( $restriction != '' ) { //some old entries are empty
						$this->mRestrictions[$temp[0]] = explode( ',', $restriction );
					}
				}
			}

			$this->mOldRestrictions = true;

		}

		if ( count( $rows ) ) {
			# Current system - load second to make them override.
			$now = wfTimestampNow();
			$purgeExpired = false;

			# Cycle through all the restrictions.
			foreach ( $rows as $row ) {

				// Don't take care of restrictions types that aren't allowed
				if ( !in_array( $row->pr_type, $restrictionTypes ) )
					continue;

				// This code should be refactored, now that it's being used more generally,
				// But I don't really see any harm in leaving it in Block for now -werdna
				$expiry = $wgContLang->formatExpiry( $row->pr_expiry, TS_MW );

				// Only apply the restrictions if they haven't expired!
				if ( !$expiry || $expiry > $now ) {
					$this->mRestrictionsExpiry[$row->pr_type] = $expiry;
					$this->mRestrictions[$row->pr_type] = explode( ',', trim( $row->pr_level ) );

					$this->mCascadeRestriction |= $row->pr_cascade;
				} else {
					// Trigger a lazy purge of expired restrictions
					$purgeExpired = true;
				}
			}

			if ( $purgeExpired ) {
				Title::purgeExpiredRestrictions();
			}
		}

		$this->mRestrictionsLoaded = true;
	}

	/**
	 * Load restrictions from the page_restrictions table
	 *
	 * @param string $oldFashionedRestrictions comma-separated list of page
	 *        restrictions from page table (pre 1.10)
	 */
	public function loadRestrictions( $oldFashionedRestrictions = null ) {
		global $wgContLang;
		if ( !$this->mRestrictionsLoaded ) {
			if ( $this->exists() ) {
				$dbr = wfGetDB( DB_SLAVE );

				$res = $dbr->select(
					'page_restrictions',
					array( 'pr_type', 'pr_expiry', 'pr_level', 'pr_cascade' ),
					array( 'pr_page' => $this->getArticleID() ),
					__METHOD__
				);

				$this->loadRestrictionsFromResultWrapper( $res, $oldFashionedRestrictions );
			} else {
				$title_protection = $this->getTitleProtection();

				if ( $title_protection ) {
					$now = wfTimestampNow();
					$expiry = $wgContLang->formatExpiry( $title_protection['pt_expiry'], TS_MW );

					if ( !$expiry || $expiry > $now ) {
						// Apply the restrictions
						$this->mRestrictionsExpiry['create'] = $expiry;
						$this->mRestrictions['create'] = explode( ',', trim( $title_protection['pt_create_perm'] ) );
					} else { // Get rid of the old restrictions
						Title::purgeExpiredRestrictions();
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
	static function purgeExpiredRestrictions() {
		if ( wfReadOnly() ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'page_restrictions',
			array( 'pr_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__
		);

		$dbw->delete(
			'protected_titles',
			array( 'pt_expiry < ' . $dbw->addQuotes( $dbw->timestamp() ) ),
			__METHOD__
		);
	}

	/**
<<<<<<< HEAD
		if ( $protected ) {
			# Protect the redirect title as the title used to be...
			$dbw->insertSelect( 'page_restrictions', 'page_restrictions',
				array(
					'pr_page'    => $redirid,
					'pr_type'    => 'pr_type',
					'pr_level'   => 'pr_level',
					'pr_cascade' => 'pr_cascade',
					'pr_user'    => 'pr_user',
					'pr_expiry'  => 'pr_expiry'
				),
				array( 'pr_page' => $pageid ),
				__METHOD__,
				array( 'IGNORE' )
			);
			# Update the protection log
			$log = new LogPage( 'protect' );
			$comment = wfMessage(
				'prot_1movedto2',
				$this->getPrefixedText(),
				$nt->getPrefixedText()
			)->inContentLanguage()->text();
			if ( $reason ) {
				$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
			}
			// @todo FIXME: $params?
			$log->addEntry( 'move_prot', $nt, $comment, array( $this->getPrefixedText() ) );
		}

<<<<<<< HEAD
				if ( $title->getNamespace() !== NS_MEDIAWIKI && $title->quickUserCan( 'protect', $user ) && $title->getRestrictionTypes() ) {
					$mode = $title->isProtected() ? 'unprotect' : 'protect';
					$content_navigation['actions'][$mode] = array(
						'class' => ( $onPage && $action == $mode ) ? 'selected' : false,
						'text' => wfMessageFallback( "$skname-action-$mode", $mode )->setContext( $this->getContext() )->text(),
						'href' => $title->getLocalURL( "action=$mode" )
					);
				}

<<<<<<< HEAD
	function protectThisPage() {
		global $wgUser, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( ! $diff ) && $wgUser->isAllowed( 'protect' ) && $title->getRestrictionTypes() ) {
			if ( $title->isProtected() ) {
				$text = wfMessage( 'unprotectthispage' )->text();
				$query = array( 'action' => 'unprotect' );
			} else {
				$text = wfMessage( 'protectthispage' )->text();
				$query = array( 'action' => 'protect' );
			}

			$s = Linker::linkKnown(
				$title,
				$text,
				array(),
				$query
			);
		} else {
			$s = '';
		}

		return $s;
	}

	$protected = Security::getTitleRestrictions( $titleObj );
				$r = $titleObj->getRestrictions( 'edit' );
				if ( in_array( 'sysop', $r ) ) {
					$protected = wfMessage( 'template-protected' )->parse();
				} elseif ( in_array( 'autoconfirmed', $r ) ) {
					$protected = wfMessage( 'template-semiprotected' )->parse();
				} else {
					$protected = '';
				}
