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

use MediaWiki\Collation\CollationFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Handles the backend logic of moving a page from one title
 * to another.
 *
 * @since 1.24
 */
class MovePage {

	/**
	 * @var Title
	 */
	protected $oldTitle;

	/**
	 * @var Title
	 */
	protected $newTitle;

	/**
	 * @var ServiceOptions
	 */
	protected $options;

	/**
	 * @var ILoadBalancer
	 */
	protected $loadBalancer;

	/**
	 * @var NamespaceInfo
	 */
	protected $nsInfo;

	/**
	 * @var WatchedItemStoreInterface
	 */
	protected $watchedItems;

	/**
	 * @var RepoGroup
	 */
	protected $repoGroup;

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var SpamChecker
	 */
	private $spamChecker;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * @var WikiPageFactory
	 */
	private $wikiPageFactory;

	/**
	 * @var UserFactory
	 */
	private $userFactory;

	/** @var UserEditTracker */
	private $userEditTracker;

	/** @var MovePageFactory */
	private $movePageFactory;

	/** @var CollationFactory */
	public $collationFactory;

	/** @var PageUpdaterFactory */
	private $pageUpdaterFactory;

	/** @var RestrictionStore */
	private $restrictionStore;

	/**
	 * @internal For use by PageCommandFactory
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CategoryCollation,
		MainConfigNames::MaximumMovedPages,
	];

	/**
	 * @see MovePageFactory
	 *
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 * @param ServiceOptions $options
	 * @param ILoadBalancer $loadBalancer
	 * @param NamespaceInfo $nsInfo
	 * @param WatchedItemStoreInterface $watchedItems
	 * @param RepoGroup $repoGroup
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param RevisionStore $revisionStore
	 * @param SpamChecker $spamChecker
	 * @param HookContainer $hookContainer
	 * @param WikiPageFactory $wikiPageFactory
	 * @param UserFactory $userFactory
	 * @param UserEditTracker $userEditTracker
	 * @param MovePageFactory $movePageFactory
	 * @param CollationFactory $collationFactory
	 * @param PageUpdaterFactory $pageUpdaterFactory
	 * @param RestrictionStore $restrictionStore
	 */
	public function __construct(
		Title $oldTitle,
		Title $newTitle,
		ServiceOptions $options,
		ILoadBalancer $loadBalancer,
		NamespaceInfo $nsInfo,
		WatchedItemStoreInterface $watchedItems,
		RepoGroup $repoGroup,
		IContentHandlerFactory $contentHandlerFactory,
		RevisionStore $revisionStore,
		SpamChecker $spamChecker,
		HookContainer $hookContainer,
		WikiPageFactory $wikiPageFactory,
		UserFactory $userFactory,
		UserEditTracker $userEditTracker,
		MovePageFactory $movePageFactory,
		CollationFactory $collationFactory,
		PageUpdaterFactory $pageUpdaterFactory,
		RestrictionStore $restrictionStore
	) {
		$this->oldTitle = $oldTitle;
		$this->newTitle = $newTitle;

		$this->options = $options;
		$this->loadBalancer = $loadBalancer;
		$this->nsInfo = $nsInfo;
		$this->watchedItems = $watchedItems;
		$this->repoGroup = $repoGroup;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->revisionStore = $revisionStore;
		$this->spamChecker = $spamChecker;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->wikiPageFactory = $wikiPageFactory;
		$this->userFactory = $userFactory;
		$this->userEditTracker = $userEditTracker;
		$this->movePageFactory = $movePageFactory;
		$this->collationFactory = $collationFactory;
		$this->pageUpdaterFactory = $pageUpdaterFactory;
		$this->restrictionStore = $restrictionStore;
	}

	/**
	 * @param callable $authorizer ( string $action, PageIdentity $target, PermissionStatus $status )
	 * @param Authority $performer
	 * @param string|null $reason
	 * @return PermissionStatus
	 */
	private function authorizeInternal(
		callable $authorizer,
		Authority $performer,
		?string $reason
	): PermissionStatus {
		$status = PermissionStatus::newEmpty();

		$authorizer( 'move', $this->oldTitle, $status );
		$authorizer( 'edit', $this->oldTitle, $status );
		$authorizer( 'move-target', $this->newTitle, $status );
		$authorizer( 'edit', $this->newTitle, $status );

		if ( $reason !== null && $this->spamChecker->checkSummary( $reason ) !== false ) {
			// This is kind of lame, won't display nice
			$status->fatal( 'spamprotectiontext' );
		}

		$tp = $this->newTitle->getTitleProtection();
		if ( $tp !== false && !$performer->isAllowed( $tp['permission'] ) ) {
			$status->fatal( 'cantmove-titleprotected' );
		}

		// TODO: change hook signature to accept Authority and PermissionStatus
		$user = $this->userFactory->newFromAuthority( $performer );
		$status = Status::wrap( $status );
		$this->hookRunner->onMovePageCheckPermissions(
			$this->oldTitle, $this->newTitle, $user, $reason, $status );
		// TODO: remove conversion code after hook signature is changed.
		$permissionStatus = PermissionStatus::newEmpty();
		foreach ( $status->getErrorsArray() as $error ) {
			$permissionStatus->fatal( ...$error );
		}
		return $permissionStatus;
	}

	/**
	 * Check whether $performer can execute the move.
	 *
	 * @note this method does not guarantee full permissions check, so it should
	 * only be used to to decide whether to show a move form. To authorize the move
	 * action use {@link self::authorizeMove} instead.
	 *
	 * @param Authority $performer
	 * @param string|null $reason
	 * @return PermissionStatus
	 */
	public function probablyCanMove( Authority $performer, string $reason = null ): PermissionStatus {
		return $this->authorizeInternal(
			static function ( string $action, PageIdentity $target, PermissionStatus $status ) use ( $performer ) {
				return $performer->probablyCan( $action, $target, $status );
			},
			$performer,
			$reason
		);
	}

	/**
	 * Authorize the move by $performer.
	 *
	 * @note this method should be used right before the actual move is performed.
	 * To check whether a current performer has the potential to move the page,
	 * use {@link self::probablyCanMove} instead.
	 *
	 * @param Authority $performer
	 * @param string|null $reason
	 * @return PermissionStatus
	 */
	public function authorizeMove( Authority $performer, string $reason = null ): PermissionStatus {
		return $this->authorizeInternal(
			static function ( string $action, PageIdentity $target, PermissionStatus $status ) use ( $performer ) {
				return $performer->authorizeWrite( $action, $target, $status );
			},
			$performer,
			$reason
		);
	}

	/**
	 * Check if the user is allowed to perform the move.
	 *
	 * @param Authority $performer
	 * @param string|null $reason To check against summary spam regex. Set to null to skip the check,
	 *   for instance to display errors preemptively before the user has filled in a summary.
	 * @deprecated since 1.36, use ::authorizeMove or ::probablyCanMove instead.
	 * @return Status
	 */
	public function checkPermissions( Authority $performer, $reason ) {
		$permissionStatus = $this->authorizeInternal(
			static function ( string $action, PageIdentity $target, PermissionStatus $status ) use ( $performer ) {
				return $performer->definitelyCan( $action, $target, $status );
			},
			$performer,
			$reason
		);
		return Status::wrap( $permissionStatus );
	}

	/**
	 * Does various checks that the move is
	 * valid. Only things based on the two titles
	 * should be checked here.
	 *
	 * @return Status
	 */
	public function isValidMove() {
		$status = new Status();

		if ( $this->oldTitle->equals( $this->newTitle ) ) {
			$status->fatal( 'selfmove' );
		} elseif ( $this->newTitle->getArticleID( Title::READ_LATEST /* T272386 */ )
			&& !$this->isValidMoveTarget()
		) {
			// The move is allowed only if (1) the target doesn't exist, or (2) the target is a
			// redirect to the source, and has no history (so we can undo bad moves right after
			// they're done). If the target is a single revision redirect to a different page,
			// it can be deleted with just `delete-redirect` rights (i.e. without needing
			// `delete`) - see T239277
			$fatal = $this->newTitle->isSingleRevRedirect() ? 'redirectexists' : 'articleexists';
			$status->fatal( $fatal, $this->newTitle->getPrefixedText() );
		}

		// @todo If the old title is invalid, maybe we should check if it somehow exists in the
		// database and allow moving it to a valid name? Why prohibit the move from an empty name
		// without checking in the database?
		if ( $this->oldTitle->getDBkey() == '' ) {
			$status->fatal( 'badarticleerror' );
		} elseif ( $this->oldTitle->isExternal() ) {
			$status->fatal( 'immobile-source-namespace-iw' );
		} elseif ( !$this->oldTitle->isMovable() ) {
			$nsText = $this->oldTitle->getNsText();
			if ( $nsText === '' ) {
				$nsText = wfMessage( 'blanknamespace' )->text();
			}
			$status->fatal( 'immobile-source-namespace', $nsText );
		} elseif ( !$this->oldTitle->exists() ) {
			$status->fatal( 'movepage-source-doesnt-exist', $this->oldTitle->getPrefixedText() );
		}

		if ( $this->newTitle->isExternal() ) {
			$status->fatal( 'immobile-target-namespace-iw' );
		} elseif ( !$this->newTitle->isMovable() ) {
			$nsText = $this->newTitle->getNsText();
			if ( $nsText === '' ) {
				$nsText = wfMessage( 'blanknamespace' )->text();
			}
			$status->fatal( 'immobile-target-namespace', $nsText );
		}
		if ( !$this->newTitle->isValid() ) {
			$status->fatal( 'movepage-invalid-target-title' );
		}

		// Content model checks
		if ( !$this->contentHandlerFactory
			->getContentHandler( $this->oldTitle->getContentModel() )
			->canBeUsedOn( $this->newTitle )
		) {
			$status->fatal(
				'content-not-allowed-here',
				ContentHandler::getLocalizedName( $this->oldTitle->getContentModel() ),
				$this->newTitle->getPrefixedText(),
				SlotRecord::MAIN
			);
		}

		// Image-specific checks
		if ( $this->oldTitle->inNamespace( NS_FILE ) ) {
			$status->merge( $this->isValidFileMove() );
		}

		if ( $this->newTitle->inNamespace( NS_FILE ) && !$this->oldTitle->inNamespace( NS_FILE ) ) {
			$status->fatal( 'nonfile-cannot-move-to-file' );
		}

		// Hook for extensions to say a title can't be moved for technical reasons
		$this->hookRunner->onMovePageIsValidMove( $this->oldTitle, $this->newTitle, $status );

		return $status;
	}

	/**
	 * Checks for when a file is being moved
	 *
	 * @see UploadBase::getTitle
	 * @return Status
	 */
	protected function isValidFileMove() {
		$status = new Status();

		if ( !$this->newTitle->inNamespace( NS_FILE ) ) {
			// No need for further errors about the target filename being wrong
			return $status->fatal( 'imagenocrossnamespace' );
		}

		$file = $this->repoGroup->getLocalRepo()->newFile( $this->oldTitle );
		$file->load( File::READ_LATEST );
		if ( $file->exists() ) {
			if ( $this->newTitle->getText() != wfStripIllegalFilenameChars( $this->newTitle->getText() ) ) {
				$status->fatal( 'imageinvalidfilename' );
			}
			if ( strlen( $this->newTitle->getText() ) > 240 ) {
				$status->fatal( 'filename-toolong' );
			}
			if (
				!$this->repoGroup->getLocalRepo()->backendSupportsUnicodePaths() &&
				!preg_match( '/^[\x0-\x7f]*$/', $this->newTitle->getText() )
			) {
				$status->fatal( 'windows-nonascii-filename' );
			}
			if ( strrpos( $this->newTitle->getText(), '.' ) === 0 ) {
				// Filename cannot only be its extension
				// Will probably fail the next check too.
				$status->fatal( 'filename-tooshort' );
			}
			if ( !File::checkExtensionCompatibility( $file, $this->newTitle->getDBkey() ) ) {
				$status->fatal( 'imagetypemismatch' );
			}
		}

		return $status;
	}

	/**
	 * Checks if $this can be moved to a given Title
	 * - Selects for update, so don't call it unless you mean business
	 *
	 * @since 1.25
	 * @return bool
	 */
	protected function isValidMoveTarget() {
		# Is it an existing file?
		if ( $this->newTitle->inNamespace( NS_FILE ) ) {
			$file = $this->repoGroup->getLocalRepo()->newFile( $this->newTitle );
			$file->load( File::READ_LATEST );
			if ( $file->exists() ) {
				wfDebug( __METHOD__ . ": file exists" );
				return false;
			}
		}
		# Is it a redirect with no history?
		if ( !$this->newTitle->isSingleRevRedirect() ) {
			wfDebug( __METHOD__ . ": not a one-rev redirect" );
			return false;
		}
		# Get the article text
		$rev = $this->revisionStore->getRevisionByTitle(
			$this->newTitle,
			0,
			RevisionStore::READ_LATEST
		);
		if ( !is_object( $rev ) ) {
			return false;
		}
		$content = $rev->getContent( SlotRecord::MAIN );
		# Does the redirect point to the source?
		# Or is it a broken self-redirect, usually caused by namespace collisions?
		$redirTitle = $content ? $content->getRedirectTarget() : null;

		if ( $redirTitle ) {
			if ( $redirTitle->getPrefixedDBkey() !== $this->oldTitle->getPrefixedDBkey() &&
				$redirTitle->getPrefixedDBkey() !== $this->newTitle->getPrefixedDBkey() ) {
				wfDebug( __METHOD__ . ": redirect points to other page" );
				return false;
			} else {
				return true;
			}
		} else {
			# Fail safe (not a redirect after all. strange.)
			wfDebug( __METHOD__ . ": failsafe: database says " . $this->newTitle->getPrefixedDBkey() .
				" is a redirect, but it doesn't contain a valid redirect." );
			return false;
		}
	}

	/**
	 * Move a page without taking user permissions into account. Only checks if the move is itself
	 * invalid, e.g., trying to move a special page or trying to move a page onto one that already
	 * exists.
	 *
	 * @param UserIdentity $user
	 * @param string|null $reason
	 * @param bool|null $createRedirect
	 * @param string[] $changeTags Change tags to apply to the entry in the move log
	 * @return Status
	 */
	public function move(
		UserIdentity $user, $reason = null, $createRedirect = true, array $changeTags = []
	) {
		$status = $this->isValidMove();
		if ( !$status->isOK() ) {
			return $status;
		}

		return $this->moveUnsafe( $user, $reason ?? '', $createRedirect, $changeTags );
	}

	/**
	 * Same as move(), but with permissions checks.
	 *
	 * @param Authority $performer
	 * @param string|null $reason
	 * @param bool $createRedirect Ignored if user doesn't have suppressredirect permission
	 * @param string[] $changeTags Change tags to apply to the entry in the move log
	 * @return Status
	 */
	public function moveIfAllowed(
		Authority $performer, $reason = null, $createRedirect = true, array $changeTags = []
	) {
		$status = $this->isValidMove();
		$status->merge( $this->authorizeMove( $performer, $reason ) );
		if ( $changeTags ) {
			$status->merge( ChangeTags::canAddTagsAccompanyingChange( $changeTags, $performer ) );
		}

		if ( !$status->isOK() ) {
			// TODO: wrap block spreading into Authority side-effect?
			$user = $this->userFactory->newFromAuthority( $performer );
			// Auto-block user's IP if the account was "hard" blocked
			$user->spreadAnyEditBlock();
			return $status;
		}

		// Check suppressredirect permission
		if ( !$performer->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = true;
		}

		return $this->moveUnsafe( $performer->getUser(), $reason ?? '', $createRedirect, $changeTags );
	}

	/**
	 * Move the source page's subpages to be subpages of the target page, without checking user
	 * permissions. The caller is responsible for moving the source page itself. We will still not
	 * do moves that are inherently not allowed, nor will we move more than $wgMaximumMovedPages.
	 *
	 * @param UserIdentity $user
	 * @param string|null $reason The reason for the move
	 * @param bool|null $createRedirect Whether to create redirects from the old subpages to
	 *  the new ones
	 * @param string[] $changeTags Applied to entries in the move log and redirect page revision
	 * @return Status Good if no errors occurred. Ok if at least one page succeeded. The "value"
	 *  of the top-level status is an array containing the per-title status for each page. For any
	 *  move that succeeded, the "value" of the per-title status is the new page title.
	 */
	public function moveSubpages(
		UserIdentity $user, $reason = null, $createRedirect = true, array $changeTags = []
	) {
		return $this->moveSubpagesInternal(
			function ( Title $oldSubpage, Title $newSubpage )
			use ( $user, $reason, $createRedirect, $changeTags ) {
				$mp = $this->movePageFactory->newMovePage( $oldSubpage, $newSubpage );
				return $mp->move( $user, $reason, $createRedirect, $changeTags );
			}
		);
	}

	/**
	 * Move the source page's subpages to be subpages of the target page, with user permission
	 * checks. The caller is responsible for moving the source page itself.
	 *
	 * @param Authority $performer
	 * @param string|null $reason The reason for the move
	 * @param bool|null $createRedirect Whether to create redirects from the old subpages to
	 *  the new ones. Ignored if the user doesn't have the 'suppressredirect' right.
	 * @param string[] $changeTags Applied to entries in the move log and redirect page revision
	 * @return Status Good if no errors occurred. Ok if at least one page succeeded. The "value"
	 *  of the top-level status is an array containing the per-title status for each page. For any
	 *  move that succeeded, the "value" of the per-title status is the new page title.
	 */
	public function moveSubpagesIfAllowed(
		Authority $performer, $reason = null, $createRedirect = true, array $changeTags = []
	) {
		if ( !$performer->authorizeWrite( 'move-subpages', $this->oldTitle ) ) {
			return Status::newFatal( 'cant-move-subpages' );
		}
		return $this->moveSubpagesInternal(
			function ( Title $oldSubpage, Title $newSubpage )
			use ( $performer, $reason, $createRedirect, $changeTags ) {
				$mp = $this->movePageFactory->newMovePage( $oldSubpage, $newSubpage );
				return $mp->moveIfAllowed( $performer, $reason, $createRedirect, $changeTags );
			}
		);
	}

	/**
	 * @param callable $subpageMoveCallback
	 * @return Status
	 * @throws MWException
	 */
	private function moveSubpagesInternal( callable $subpageMoveCallback ) {
		// Do the source and target namespaces support subpages?
		if ( !$this->nsInfo->hasSubpages( $this->oldTitle->getNamespace() ) ) {
			return Status::newFatal( 'namespace-nosubpages',
				$this->nsInfo->getCanonicalName( $this->oldTitle->getNamespace() ) );
		}
		if ( !$this->nsInfo->hasSubpages( $this->newTitle->getNamespace() ) ) {
			return Status::newFatal( 'namespace-nosubpages',
				$this->nsInfo->getCanonicalName( $this->newTitle->getNamespace() ) );
		}

		// Return a status for the overall result. Its value will be an array with per-title
		// status for each subpage. Merge any errors from the per-title statuses into the
		// top-level status without resetting the overall result.
		$maximumMovedPages = $this->options->get( MainConfigNames::MaximumMovedPages );
		$topStatus = Status::newGood();
		$perTitleStatus = [];
		$subpages = $this->oldTitle->getSubpages( $maximumMovedPages + 1 );
		$count = 0;
		foreach ( $subpages as $oldSubpage ) {
			$count++;
			if ( $count > $maximumMovedPages ) {
				$status = Status::newFatal( 'movepage-max-pages', $maximumMovedPages );
				$perTitleStatus[$oldSubpage->getPrefixedText()] = $status;
				$topStatus->merge( $status );
				$topStatus->setOK( true );
				break;
			}

			// We don't know whether this function was called before or after moving the root page,
			// so check both titles
			if ( $oldSubpage->getArticleID() == $this->oldTitle->getArticleID() ||
				$oldSubpage->getArticleID() == $this->newTitle->getArticleID()
			) {
				// When moving a page to a subpage of itself, don't move it twice
				continue;
			}
			$newPageName = preg_replace(
					'#^' . preg_quote( $this->oldTitle->getDBkey(), '#' ) . '#',
					StringUtils::escapeRegexReplacement( $this->newTitle->getDBkey() ), # T23234
					$oldSubpage->getDBkey() );
			if ( $oldSubpage->isTalkPage() ) {
				$newNs = $this->newTitle->getTalkPage()->getNamespace();
			} else {
				$newNs = $this->newTitle->getSubjectPage()->getNamespace();
			}
			// T16385: we need makeTitleSafe because the new page names may be longer than 255
			// characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );
			$status = $subpageMoveCallback( $oldSubpage, $newSubpage );
			if ( $status->isOK() ) {
				$status->setResult( true, $newSubpage->getPrefixedText() );
			}
			$perTitleStatus[$oldSubpage->getPrefixedText()] = $status;
			$topStatus->merge( $status );
			$topStatus->setOK( true );
		}

		$topStatus->value = $perTitleStatus;
		return $topStatus;
	}

	/**
	 * Moves *without* any sort of safety or other checks. Hooks can still fail the move, however.
	 *
	 * @param UserIdentity $user
	 * @param string $reason
	 * @param bool $createRedirect
	 * @param string[] $changeTags Change tags to apply to the entry in the move log
	 * @return Status
	 */
	private function moveUnsafe( UserIdentity $user, $reason, $createRedirect, array $changeTags ) {
		$status = Status::newGood();

		// TODO: make hooks accept UserIdentity
		$userObj = $this->userFactory->newFromUserIdentity( $user );
		$this->hookRunner->onTitleMove( $this->oldTitle, $this->newTitle, $userObj, $reason, $status );
		if ( !$status->isOK() ) {
			// Move was aborted by the hook
			return $status;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );

		$this->hookRunner->onTitleMoveStarting( $this->oldTitle, $this->newTitle, $userObj );

		$pageid = $this->oldTitle->getArticleID( Title::READ_LATEST );
		$protected = $this->restrictionStore->isProtected( $this->oldTitle );

		// Attempt the actual move
		$moveAttemptResult = $this->moveToInternal( $user, $this->newTitle, $reason, $createRedirect,
			$changeTags );

		if ( !$moveAttemptResult->isGood() ) {
			// T265779: Attempt to delete target page failed
			$dbw->cancelAtomic( __METHOD__ );
			return $moveAttemptResult;
		} else {
			$nullRevision = $moveAttemptResult->getValue()['nullRevision'];
			'@phan-var RevisionRecord $nullRevision';
		}

		$redirid = $this->oldTitle->getArticleID();

		if ( $protected ) {
			# Protect the redirect title as the title used to be...
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'pr_type', 'pr_level', 'pr_cascade', 'pr_expiry' ] )
				->from( 'page_restrictions' )
				->where( [ 'pr_page' => $pageid ] )
				->forUpdate()
				->caller( __METHOD__ )
				->fetchResultSet();
			$rowsInsert = [];
			foreach ( $res as $row ) {
				$rowsInsert[] = [
					'pr_page' => $redirid,
					'pr_type' => $row->pr_type,
					'pr_level' => $row->pr_level,
					'pr_cascade' => $row->pr_cascade,
					'pr_expiry' => $row->pr_expiry
				];
			}
			$dbw->insert( 'page_restrictions', $rowsInsert, __METHOD__, [ 'IGNORE' ] );

			// Build comment for log
			$comment = wfMessage(
				'prot_1movedto2',
				$this->oldTitle->getPrefixedText(),
				$this->newTitle->getPrefixedText()
			)->inContentLanguage()->text();
			if ( $reason ) {
				$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
			}

			// reread inserted pr_ids for log relation
			$logRelationsValues = $dbw->selectFieldValues(
				'page_restrictions',
				'pr_id',
				[ 'pr_page' => $redirid ],
				__METHOD__
			);

			// Update the protection log
			$logEntry = new ManualLogEntry( 'protect', 'move_prot' );
			$logEntry->setTarget( $this->newTitle );
			$logEntry->setComment( $comment );
			$logEntry->setPerformer( $user );
			$logEntry->setParameters( [
				'4::oldtitle' => $this->oldTitle->getPrefixedText(),
			] );
			$logEntry->setRelations( [ 'pr_id' => $logRelationsValues ] );
			$logEntry->addTags( $changeTags );
			$logId = $logEntry->insert();
			$logEntry->publish( $logId );
		}

		# Update watchlists
		$oldtitle = $this->oldTitle->getDBkey();
		$newtitle = $this->newTitle->getDBkey();
		$oldsnamespace = $this->nsInfo->getSubject( $this->oldTitle->getNamespace() );
		$newsnamespace = $this->nsInfo->getSubject( $this->newTitle->getNamespace() );
		if ( $oldsnamespace != $newsnamespace || $oldtitle != $newtitle ) {
			$this->watchedItems->duplicateAllAssociatedEntries( $this->oldTitle, $this->newTitle );
		}

		// If it is a file then move it last.
		// This is done after all database changes so that file system errors cancel the transaction.
		if ( $this->oldTitle->getNamespace() === NS_FILE ) {
			$status = $this->moveFile( $this->oldTitle, $this->newTitle );
			if ( !$status->isOK() ) {
				$dbw->cancelAtomic( __METHOD__ );
				return $status;
			}
		}

		$this->hookRunner->onPageMoveCompleting(
			$this->oldTitle, $this->newTitle,
			$user, $pageid, $redirid, $reason, $nullRevision
		);

		$dbw->endAtomic( __METHOD__ );

		// Keep each single hook handler atomic
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use ( $user, $pageid, $redirid, $reason, $nullRevision ) {
					$this->hookRunner->onPageMoveComplete(
						$this->oldTitle,
						$this->newTitle,
						$user,
						$pageid,
						$redirid,
						$reason,
						$nullRevision
					);
				}
			)
		);

		return $moveAttemptResult;
	}

	/**
	 * Move a file associated with a page to a new location.
	 * Can also be used to revert after a DB failure.
	 *
	 * @internal
	 * @param Title $oldTitle Old location to move the file from.
	 * @param Title $newTitle New location to move the file to.
	 * @return Status
	 */
	private function moveFile( $oldTitle, $newTitle ) {
		$file = $this->repoGroup->getLocalRepo()->newFile( $oldTitle );
		$file->load( File::READ_LATEST );
		if ( $file->exists() ) {
			$status = $file->move( $newTitle );
		} else {
			$status = Status::newGood();
		}

		// Clear RepoGroup process cache
		$this->repoGroup->clearCache( $oldTitle );
		$this->repoGroup->clearCache( $newTitle ); # clear false negative cache
		return $status;
	}

	/**
	 * Move page to a title which is either a redirect to the
	 * source page or nonexistent
	 *
	 * @todo This was basically directly moved from Title, it should be split into
	 *   smaller functions
	 * @param UserIdentity $user doing the move
	 * @param Title &$nt The page to move to, which should be a redirect or non-existent
	 * @param string $reason The reason for the move
	 * @param bool $createRedirect Whether to leave a redirect at the old title. Does not check
	 *   if the user has the suppressredirect right
	 * @param string[] $changeTags Change tags to apply to the entry in the move log
	 * @return Status Status object with the following value on success:
	 *   [
	 *     'nullRevision' => The ("null") revision created by the move (RevisionRecord)
	 *     'redirectRevision' => The initial revision of the redirect if it was created (RevisionRecord|null)
	 *   ]
	 */
	private function moveToInternal( UserIdentity $user, &$nt, $reason = '', $createRedirect = true,
		array $changeTags = []
	): Status {
		if ( $nt->getArticleId( Title::READ_LATEST ) ) {
			$moveOverRedirect = true;
			$logType = 'move_redir';
		} else {
			$moveOverRedirect = false;
			$logType = 'move';
		}

		if ( $moveOverRedirect ) {
			$overwriteMessage = wfMessage(
					'delete_and_move_reason',
					$this->oldTitle->getPrefixedText()
				)->inContentLanguage()->text();
			$newpage = $this->wikiPageFactory->newFromTitle( $nt );
			$errs = [];
			$status = $newpage->doDeleteArticleReal(
				$overwriteMessage,
				$user,
				/* $suppress */ false,
				/* unused */ null,
				$errs,
				/* unused */ null,
				$changeTags,
				'delete_redir'
			);

			if ( !$status->isGood() ) {
				return $status;
			}

			$nt->resetArticleID( false );
		}

		if ( $createRedirect ) {
			if ( $this->oldTitle->getNamespace() === NS_CATEGORY
				&& !wfMessage( 'category-move-redirect-override' )->inContentLanguage()->isDisabled()
			) {
				$redirectContent = new WikitextContent(
					wfMessage( 'category-move-redirect-override' )
						->params( $nt->getPrefixedText() )->inContentLanguage()->plain() );
			} else {
				$redirectContent = $this->contentHandlerFactory
					->getContentHandler( $this->oldTitle->getContentModel() )
					->makeRedirectContent(
						$nt,
						wfMessage( 'move-redirect-text' )->inContentLanguage()->plain()
					);
			}

			// NOTE: If this page's content model does not support redirects, $redirectContent will be null.
		} else {
			$redirectContent = null;
		}

		// T59084: log_page should be the ID of the *moved* page
		$oldid = $this->oldTitle->getArticleID();
		$logTitle = clone $this->oldTitle;

		$logEntry = new ManualLogEntry( 'move', $logType );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $logTitle );
		$logEntry->setComment( $reason );
		$logEntry->setParameters( [
			'4::target' => $nt->getPrefixedText(),
			'5::noredir' => $redirectContent ? '0' : '1',
		] );

		$formatter = LogFormatter::newFromEntry( $logEntry );
		$formatter->setContext( RequestContext::newExtraneousContext( $this->oldTitle ) );
		$comment = $formatter->getPlainActionText();
		if ( $reason ) {
			$comment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );

		$oldpage = $this->wikiPageFactory->newFromTitle( $this->oldTitle );
		$oldcountable = $oldpage->isCountable();

		$newpage = $this->wikiPageFactory->newFromTitle( $nt );

		# Change the name of the target page:
		$dbw->update( 'page',
			/* SET */ [
				'page_namespace' => $nt->getNamespace(),
				'page_title' => $nt->getDBkey(),
			],
			/* WHERE */ [ 'page_id' => $oldid ],
			__METHOD__
		);

		// Reset $nt before using it to create the null revision (T248789).
		// But not $this->oldTitle yet, see below (T47348).
		$nt->resetArticleID( $oldid );

		$commentObj = CommentStoreComment::newUnsavedComment( $comment );
		# Save a null revision in the page's history notifying of the move
		$nullRevision = $this->revisionStore->newNullRevision(
			$dbw,
			$nt,
			$commentObj,
			true,
			$user
		);
		if ( $nullRevision === null ) {
			$id = $nt->getArticleID( Title::READ_EXCLUSIVE );
			$msg = 'Failed to create null revision while moving page ID ' .
				$oldid . ' to ' . $nt->getPrefixedDBkey() . " (page ID $id)";

			throw new MWException( $msg );
		}

		$nullRevision = $this->revisionStore->insertRevisionOn( $nullRevision, $dbw );
		$logEntry->setAssociatedRevId( $nullRevision->getId() );

		/**
		 * T163966
		 * Increment user_editcount during page moves
		 * Moved from SpecialMovepage.php per T195550
		 */
		$this->userEditTracker->incrementUserEditCount( $user );

		// Get the old redirect state before clean up
		$isRedirect = $this->oldTitle->isRedirect();
		if ( !$redirectContent ) {
			// Clean up the old title *before* reset article id - T47348
			WikiPage::onArticleDelete( $this->oldTitle );
		}

		$this->oldTitle->resetArticleID( 0 ); // 0 == non existing
		$newpage->loadPageData( WikiPage::READ_LOCKING ); // T48397

		$newpage->updateRevisionOn( $dbw, $nullRevision, null, $isRedirect );

		$fakeTags = [];
		$this->hookRunner->onRevisionFromEditComplete(
			$newpage, $nullRevision, $nullRevision->getParentId(), $user, $fakeTags );

		$options = [
			'changed' => false,
			'moved' => true,
			'oldtitle' => $this->oldTitle,
			'oldcountable' => $oldcountable,
			'causeAction' => 'edit-page',
			'causeAgent' => $user->getName(),
		];

		$updater = $this->pageUpdaterFactory->newDerivedPageDataUpdater( $newpage );
		$updater->prepareUpdate( $nullRevision, $options );
		$updater->doUpdates();

		WikiPage::onArticleCreate( $nt );

		# Recreate the redirect, this time in the other direction.
		$redirectRevision = null;
		if ( $redirectContent ) {
			$redirectArticle = $this->wikiPageFactory->newFromTitle( $this->oldTitle );
			$redirectArticle->loadFromRow( false, WikiPage::READ_LOCKING ); // T48397
			$redirectRevision = $redirectArticle->newPageUpdater( $user )
				->setContent( SlotRecord::MAIN, $redirectContent )
				->addTags( $changeTags )
				->addSoftwareTag( 'mw-new-redirect' )
				->setUsePageCreationLog( false )
				->setFlags( EDIT_SUPPRESS_RC )
				->saveRevision( $commentObj );
		}

		# Log the move
		$logid = $logEntry->insert();

		$logEntry->addTags( $changeTags );
		$logEntry->publish( $logid );

		return Status::newGood( [
			'nullRevision' => $nullRevision,
			'redirectRevision' => $redirectRevision,
		] );
	}
}
