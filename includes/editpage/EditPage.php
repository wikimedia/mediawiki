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

namespace MediaWiki\EditPage;

use BadMethodCallException;
use MediaWiki\Actions\WatchAction;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\Config;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\EditPage\Constraint\AccidentalRecreationConstraint;
use MediaWiki\EditPage\Constraint\AuthorizationConstraint;
use MediaWiki\EditPage\Constraint\ChangeTagsConstraint;
use MediaWiki\EditPage\Constraint\ContentModelChangeConstraint;
use MediaWiki\EditPage\Constraint\DefaultTextConstraint;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\Constraint\EditConstraintRunner;
use MediaWiki\EditPage\Constraint\EditFilterMergedContentHookConstraint;
use MediaWiki\EditPage\Constraint\ExistingSectionEditConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\ImageRedirectConstraint;
use MediaWiki\EditPage\Constraint\MissingCommentConstraint;
use MediaWiki\EditPage\Constraint\NewSectionMissingSubjectConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;
use MediaWiki\EditPage\Constraint\RedirectConstraint;
use MediaWiki\EditPage\Constraint\SpamRegexConstraint;
use MediaWiki\EditPage\Constraint\UnicodeConstraint;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\Exception\MWException;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\CategoryPage;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\WebRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Session\SessionManager;
use MediaWiki\Skin\Skin;
use MediaWiki\Status\Status;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\Title\Title;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\TempUser\CreateStatus;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MessageLocalizer;
use OOUI;
use OOUI\ButtonWidget;
use OOUI\CheckboxInputWidget;
use OOUI\DropdownInputWidget;
use OOUI\FieldLayout;
use RuntimeException;
use stdClass;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * The HTML user interface for page editing.
 *
 * This was originally split from the Article class, with some database and text
 * munging logic still partly there.
 *
 * EditPage cares about two distinct titles:
 * - $this->mContextTitle is the page that forms submit to, links point to,
 *   redirects go to, etc.
 * - $this->mTitle (as well as $mArticle) is the page in the database that is
 *   actually being edited.
 *
 * These are usually the same, but they are now allowed to be different.
 *
 * Surgeon General's Warning: prolonged exposure to this class is known to cause
 * headaches, which may be fatal.
 *
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should be split up into service objects and command objects
 *       in the future (T157658).
 */
#[\AllowDynamicProperties]
class EditPage implements IEditObject {
	use DeprecationHelper;
	use ProtectedHookAccessorTrait;

	/**
	 * Used for Unicode support checks
	 */
	public const UNICODE_CHECK = UnicodeConstraint::VALID_UNICODE;

	/**
	 * HTML id and name for the beginning of the edit form.
	 */
	public const EDITFORM_ID = 'editform';

	/**
	 * Prefix of key for cookie used to pass post-edit state.
	 * The revision id edited is added after this
	 */
	public const POST_EDIT_COOKIE_KEY_PREFIX = 'PostEditRevision';

	/**
	 * Duration of PostEdit cookie, in seconds.
	 * The cookie will be removed on the next page view of this article (Article::view()).
	 *
	 * Otherwise, though, we don't want the cookies to accumulate.
	 * RFC 2109 ( https://www.ietf.org/rfc/rfc2109.txt ) specifies a possible
	 * limit of only 20 cookies per domain. This still applies at least to some
	 * versions of IE without full updates:
	 * https://blogs.msdn.com/b/ieinternals/archive/2009/08/20/wininet-ie-cookie-internals-faq.aspx
	 *
	 * A value of 20 minutes should be enough to take into account slow loads and minor
	 * clock skew while still avoiding cookie accumulation when JavaScript is turned off.
	 *
	 * Some say this is too long (T211233), others say it is too short (T289538).
	 * The same value is used for client-side post-edit storage (in mediawiki.action.view.postEdit).
	 */
	public const POST_EDIT_COOKIE_DURATION = 1200;

	/**
	 * @var Article
	 */
	private $mArticle;

	/** @var WikiPage */
	private $page;

	/**
	 * @var Title
	 */
	private $mTitle;

	/** @var null|Title */
	private $mContextTitle = null;

	/**
	 * @deprecated since 1.38 for public usage; no replacement
	 * @var string
	 */
	private $action = 'submit';

	/** @var bool Whether an edit conflict needs to be resolved. Detected based on whether
	 * $editRevId is different than the latest revision. When a conflict has successfully
	 * been resolved by a 3-way-merge, this field is set to false.
	 */
	public $isConflict = false;

	/** @var bool New page or new section */
	private $isNew = false;

	/** @var bool */
	private $deletedSinceEdit;

	/** @var string */
	public $formtype;

	/** @var bool
	 * True the first time the edit form is rendered, false after re-rendering
	 * with diff, save prompts, etc.
	 */
	public $firsttime;

	/** @var stdClass|null */
	private $lastDelete;

	/** @var bool */
	private $mTokenOk = false;

	/** @var bool */
	private $mTriedSave = false;

	/** @var bool */
	private $incompleteForm = false;

	/** @var bool */
	private $missingComment = false;

	/** @var bool */
	private $missingSummary = false;

	/** @var bool */
	private $allowBlankSummary = false;

	/** @var bool */
	protected $blankArticle = false;

	/** @var bool */
	private $allowBlankArticle = false;

	/** @var ?Title */
	private $problematicRedirectTarget = null;

	/** @var ?Title */
	private $allowedProblematicRedirectTarget = null;

	/** @var bool */
	private $ignoreProblematicRedirects = false;

	/** @var string */
	private $autoSumm = '';

	/** @var string */
	private $hookError = '';

	/** @var ParserOutput|null */
	private $mParserOutput;

	/**
	 * @var RevisionRecord|false|null
	 *
	 * A RevisionRecord corresponding to $this->editRevId or $this->edittime
	 */
	private $mExpectedParentRevision = false;

	/** @var bool */
	public $mShowSummaryField = true;

	# Form values

	/** @var bool */
	public $save = false;

	/** @var bool */
	public $preview = false;

	/** @var bool */
	private $diff = false;

	/** @var bool */
	private $minoredit = false;

	/** @var bool */
	private $watchthis = false;

	/** @var bool Corresponds to $wgWatchlistExpiry */
	private $watchlistExpiryEnabled;

	private WatchedItemStoreInterface $watchedItemStore;

	/** @var string|null The expiry time of the watch item, or null if it is not watched temporarily. */
	private $watchlistExpiry;

	/** @var bool */
	private $recreate = false;

	/** @var string
	 * Page content input field.
	 */
	public $textbox1 = '';

	/**
	 * @deprecated since 1.44
	 * @var string
	 */
	private $textbox2 = '';

	/** @var string */
	public $summary = '';

	/**
	 * @var bool
	 * If true, hide the summary field.
	 */
	private $nosummary = false;

	/** @var string|null
	 * Timestamp of the latest revision of the page when editing was initiated
	 * on the client.
	 */
	public $edittime = '';

	/** @var int|null Revision ID of the latest revision of the page when editing
	 * was initiated on the client.  This is used to detect and resolve edit
	 * conflicts.
	 *
	 * @note 0 if the page did not exist at that time.
	 * @note When starting an edit from an old revision, this still records the current
	 * revision at the time, not the one the edit is based on.
	 *
	 * @see $oldid
	 * @see getExpectedParentRevision()
	 */
	private $editRevId = null;

	/** @var string */
	public $section = '';

	/** @var string|null */
	public $sectiontitle = null;

	/** @var string|null */
	private $newSectionAnchor = null;

	/** @var string|null
	 * Timestamp from the first time the edit form was rendered.
	 */
	public $starttime = '';

	/** @var int Revision ID the edit is based on, or 0 if it's the current revision.
	 * FIXME: This isn't used in conflict resolution--provide a better
	 * justification or merge with parentRevId.
	 * @see $editRevId
	 */
	public $oldid = 0;

	/**
	 * @var int Revision ID the edit is based on, adjusted when an edit conflict is resolved.
	 * @see $editRevId
	 * @see $oldid
	 * @see getparentRevId()
	 */
	private $parentRevId = 0;

	/** @var int|null */
	private $scrolltop = null;

	/** @var bool */
	private $markAsBot = true;

	/** @var string */
	public $contentModel;

	/** @var null|string */
	public $contentFormat = null;

	/** @var null|array */
	private $changeTags = null;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value

	/** @var string Before even the preview */
	public $editFormPageTop = '';
	/** @var string */
	public $editFormTextTop = '';
	/** @var string */
	public $editFormTextBeforeContent = '';
	/** @var string */
	public $editFormTextAfterWarn = '';
	/** @var string */
	public $editFormTextAfterTools = '';
	/** @var string */
	public $editFormTextBottom = '';
	/** @var string */
	public $editFormTextAfterContent = '';
	/** @var string */
	public $previewTextAfterContent = '';

	/** @var bool should be set to true whenever an article was successfully altered. */
	public $didSave = false;
	/** @var int */
	public $undidRev = 0;
	/** @var int */
	private $undoAfter = 0;

	/** @var bool */
	public $suppressIntro = false;

	/** @var bool */
	private $edit;

	/** @var int|false */
	private $contentLength = false;

	/**
	 * @var bool Set in ApiEditPage, based on ContentHandler::allowsDirectApiEditing
	 */
	private $enableApiEditOverride = false;

	/**
	 * @var IContextSource
	 */
	protected $context;

	/**
	 * @var bool Whether an old revision is edited
	 */
	private $isOldRev = false;

	/**
	 * @var string|null What the user submitted in the 'wpUnicodeCheck' field
	 */
	private $unicodeCheck;

	/** @var callable|null */
	private $editConflictHelperFactory = null;
	private ?TextConflictHelper $editConflictHelper = null;

	private IContentHandlerFactory $contentHandlerFactory;
	private PermissionManager $permManager;
	private RevisionStore $revisionStore;
	private WikiPageFactory $wikiPageFactory;
	private WatchlistManager $watchlistManager;
	private UserNameUtils $userNameUtils;
	private RedirectLookup $redirectLookup;
	private UserOptionsLookup $userOptionsLookup;
	private TempUserCreator $tempUserCreator;
	private UserFactory $userFactory;
	private IConnectionProvider $dbProvider;
	private BlockErrorFormatter $blockErrorFormatter;
	private AuthManager $authManager;
	private UserRegistrationLookup $userRegistrationLookup;

	/** @var User|null */
	private $placeholderTempUser;

	/** @var User|null */
	private $unsavedTempUser;

	/** @var User|null */
	private $savedTempUser;

	/** @var bool Whether temp user creation will be attempted */
	private $tempUserCreateActive = false;

	/** @var string|null If a temp user name was acquired, this is the name */
	private $tempUserName;

	/** @var bool Whether temp user creation was successful */
	private $tempUserCreateDone = false;

	/** @var bool Whether temp username acquisition failed (false indicates no failure or not attempted) */
	private $unableToAcquireTempName = false;

	private LinkRenderer $linkRenderer;
	private LinkBatchFactory $linkBatchFactory;
	private RestrictionStore $restrictionStore;
	private CommentStore $commentStore;

	/**
	 * @stable to call
	 * @param Article $article
	 */
	public function __construct( Article $article ) {
		$this->mArticle = $article;
		$this->page = $article->getPage(); // model object
		$this->mTitle = $article->getTitle();

		// Make sure the local context is in sync with other member variables.
		// Particularly make sure everything is using the same WikiPage instance.
		// This should probably be the case in Article as well, but it's
		// particularly important for EditPage, to make use of the in-place caching
		// facility in WikiPage::prepareContentForEdit.
		$this->context = new DerivativeContext( $article->getContext() );
		$this->context->setWikiPage( $this->page );
		$this->context->setTitle( $this->mTitle );

		$this->contentModel = $this->mTitle->getContentModel();

		$services = MediaWikiServices::getInstance();
		$this->contentHandlerFactory = $services->getContentHandlerFactory();
		$this->contentFormat = $this->contentHandlerFactory
			->getContentHandler( $this->contentModel )
			->getDefaultFormat();
		$this->permManager = $services->getPermissionManager();
		$this->revisionStore = $services->getRevisionStore();
		$this->watchlistExpiryEnabled = $this->getContext()->getConfig() instanceof Config
			&& $this->getContext()->getConfig()->get( MainConfigNames::WatchlistExpiry );
		$this->watchedItemStore = $services->getWatchedItemStore();
		$this->wikiPageFactory = $services->getWikiPageFactory();
		$this->watchlistManager = $services->getWatchlistManager();
		$this->userNameUtils = $services->getUserNameUtils();
		$this->redirectLookup = $services->getRedirectLookup();
		$this->userOptionsLookup = $services->getUserOptionsLookup();
		$this->tempUserCreator = $services->getTempUserCreator();
		$this->userFactory = $services->getUserFactory();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->linkBatchFactory = $services->getLinkBatchFactory();
		$this->restrictionStore = $services->getRestrictionStore();
		$this->commentStore = $services->getCommentStore();
		$this->dbProvider = $services->getConnectionProvider();
		$this->blockErrorFormatter = $services->getFormatterFactory()
			->getBlockErrorFormatter( $this->context );
		$this->authManager = $services->getAuthManager();
		$this->userRegistrationLookup = $services->getUserRegistrationLookup();

		$this->deprecatePublicProperty( 'textbox2', '1.44', __CLASS__ );
		$this->deprecatePublicProperty( 'action', '1.38', __CLASS__ );
	}

	/**
	 * @return Article
	 */
	public function getArticle() {
		return $this->mArticle;
	}

	/**
	 * @since 1.28
	 * @return IContextSource
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @since 1.19
	 * @return Title
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * @param Title|null $title
	 */
	public function setContextTitle( $title ) {
		$this->mContextTitle = $title;
	}

	/**
	 * @throws RuntimeException if no context title was set
	 * @return Title
	 */
	public function getContextTitle() {
		if ( $this->mContextTitle === null ) {
			throw new RuntimeException( "EditPage does not have a context title set" );
		} else {
			return $this->mContextTitle;
		}
	}

	/**
	 * Returns if the given content model is editable.
	 *
	 * @param string $modelId The ID of the content model to test. Use CONTENT_MODEL_XXX constants.
	 * @return bool
	 * @throws MWUnknownContentModelException If $modelId has no known handler
	 */
	private function isSupportedContentModel( string $modelId ): bool {
		return $this->enableApiEditOverride === true ||
			$this->contentHandlerFactory->getContentHandler( $modelId )->supportsDirectEditing();
	}

	/**
	 * Allow editing of content that supports API direct editing, but not general
	 * direct editing. Set to false by default.
	 * @internal Must only be used by ApiEditPage
	 *
	 * @param bool $enableOverride
	 */
	public function setApiEditOverride( $enableOverride ) {
		$this->enableApiEditOverride = $enableOverride;
	}

	/**
	 * This is the function that gets called for "action=edit". It
	 * sets up various member variables, then passes execution to
	 * another function, usually showEditForm()
	 *
	 * The edit form is self-submitting, so that when things like
	 * preview and edit conflicts occur, we get the same form back
	 * with the extra stuff added.  Only when the final submission
	 * is made and all is well do we actually save and redirect to
	 * the newly-edited page.
	 */
	public function edit() {
		// Allow extensions to modify/prevent this form or submission
		if ( !$this->getHookRunner()->onAlternateEdit( $this ) ) {
			return;
		}

		wfDebug( __METHOD__ . ": enter" );

		$request = $this->context->getRequest();
		// If they used redlink=1 and the page exists, redirect to the main article
		if ( $request->getBool( 'redlink' ) && $this->mTitle->exists() ) {
			$this->context->getOutput()->redirect( $this->mTitle->getFullURL() );
			return;
		}

		$this->importFormData( $request );
		$this->firsttime = false;

		$readOnlyMode = MediaWikiServices::getInstance()->getReadOnlyMode();
		if ( $this->save && $readOnlyMode->isReadOnly() ) {
			// Force preview
			$this->save = false;
			$this->preview = true;
		}

		if ( $this->save ) {
			$this->formtype = 'save';
		} elseif ( $this->preview ) {
			$this->formtype = 'preview';
		} elseif ( $this->diff ) {
			$this->formtype = 'diff';
		} else { # First time through
			$this->firsttime = true;
			if ( $this->previewOnOpen() ) {
				$this->formtype = 'preview';
			} else {
				$this->formtype = 'initial';
			}
		}

		// Check permissions after possibly creating a placeholder temp user.
		// This allows anonymous users to edit via a temporary account, if the site is
		// configured to (1) disallow anonymous editing and (2) autocreate temporary
		// accounts on edit.
		$this->unableToAcquireTempName = !$this->maybeActivateTempUserCreate( !$this->firsttime )->isOK();

		$status = $this->getEditPermissionStatus(
			$this->save ? PermissionManager::RIGOR_SECURE : PermissionManager::RIGOR_FULL
		);
		if ( !$status->isGood() ) {
			wfDebug( __METHOD__ . ": User can't edit" );

			$user = $this->context->getUser();
			if ( $user->getBlock() && !$readOnlyMode->isReadOnly() ) {
				// Auto-block user's IP if the account was "hard" blocked
				$user->scheduleSpreadBlock();
			}
			$this->displayPermissionStatus( $status );

			return;
		}

		$revRecord = $this->mArticle->fetchRevisionRecord();
		// Disallow editing revisions with content models different from the current one
		// Undo edits being an exception in order to allow reverting content model changes.
		$revContentModel = $revRecord ?
			$revRecord->getMainContentModel() :
			false;
		if ( $revContentModel && $revContentModel !== $this->contentModel ) {
			$prevRevRecord = null;
			$prevContentModel = false;
			if ( $this->undidRev ) {
				$undidRevRecord = $this->revisionStore
					->getRevisionById( $this->undidRev );
				$prevRevRecord = $undidRevRecord ?
					$this->revisionStore->getPreviousRevision( $undidRevRecord ) :
					null;

				$prevContentModel = $prevRevRecord ?
					$prevRevRecord->getMainContentModel() :
					'';
			}

			if ( !$this->undidRev
				|| !$prevRevRecord
				|| $prevContentModel !== $this->contentModel
			) {
				$this->displayViewSourcePage(
					$this->getContentObject(),
					$this->context->msg(
						'contentmodelediterror',
						$revContentModel,
						$this->contentModel
					)->plain()
				);
				return;
			}
		}

		$this->isConflict = false;

		# Attempt submission here.  This will check for edit conflicts,
		# and redundantly check for locked database, blocked IPs, etc.
		# that edit() already checked just in case someone tries to sneak
		# in the back door with a hand-edited submission URL.

		if ( $this->formtype === 'save' ) {
			$resultDetails = null;
			$status = $this->attemptSave( $resultDetails );
			if ( !$this->handleStatus( $status, $resultDetails ) ) {
				return;
			}
		}

		# First time through: get contents, set time for conflict
		# checking, etc.
		if ( $this->formtype === 'initial' || $this->firsttime ) {
			if ( !$this->initialiseForm() ) {
				return;
			}

			if ( $this->mTitle->getArticleID() ) {
				$this->getHookRunner()->onEditFormInitialText( $this );
			}
		}

		// If we're displaying an old revision, and there are differences between it and the
		// current revision outside the main slot, then we can't allow the old revision to be
		// editable, as what would happen to the non-main-slot data if someone saves the old
		// revision is undefined.
		// When this is the case, display a read-only version of the page instead, with a link
		// to a diff page from which the old revision can be restored
		$curRevisionRecord = $this->page->getRevisionRecord();
		if ( $curRevisionRecord
			&& $revRecord
			&& $curRevisionRecord->getId() !== $revRecord->getId()
			&& ( WikiPage::hasDifferencesOutsideMainSlot(
					$revRecord,
					$curRevisionRecord
				) || !$this->isSupportedContentModel(
					$revRecord->getSlot(
						SlotRecord::MAIN,
						RevisionRecord::RAW
					)->getModel()
				) )
		) {
			$restoreLink = $this->mTitle->getFullURL(
				[
					'action' => 'mcrrestore',
					'restore' => $revRecord->getId(),
				]
			);
			$this->displayViewSourcePage(
				$this->getContentObject(),
				$this->context->msg(
					'nonmain-slot-differences-therefore-readonly',
					$restoreLink
				)->plain()
			);
			return;
		}

		$this->showEditForm();
	}

	/**
	 * Check the configuration and current user and enable automatic temporary
	 * user creation if possible.
	 *
	 * @param bool $doAcquire Whether to acquire a name for the temporary account
	 *
	 * @since 1.39
	 * @return Status Will return a fatal status if $doAcquire was true and the acquire failed.
	 */
	public function maybeActivateTempUserCreate( $doAcquire ): Status {
		if ( $this->tempUserCreateActive ) {
			// Already done
			return Status::newGood();
		}
		$user = $this->context->getUser();

		// Log out any user using an expired temporary account, so that we can give them a new temporary account.
		// As described in T389485, we need to do this because the maintenance script to expire temporary accounts
		// may fail to run or not be configured to run.
		if ( $user->isTemp() ) {
			$expiryAfterDays = $this->tempUserCreator->getExpireAfterDays();
			if ( $expiryAfterDays ) {
				$expirationCutoff = (int)ConvertibleTimestamp::now( TS_UNIX ) - ( 86_400 * $expiryAfterDays );

				// If the user was created before the expiration cutoff, then log them out, expire any other existing
				// sessions, and revoke any access to the account that may exist.
				// If no registration is set then do nothing, as if registration date system is broken it would
				// cause a new temporary account for each edit.
				$firstUserRegistration = $this->userRegistrationLookup->getFirstRegistration( $user );
				if (
					$firstUserRegistration &&
					ConvertibleTimestamp::convert( TS_UNIX, $firstUserRegistration ) < $expirationCutoff
				) {
					// Log the user out of the expired temporary account.
					$user->logout();

					// Clear any stashed temporary account name (if any is set), as we want a new name for the user.
					$session = $this->context->getRequest()->getSession();
					$session->set( 'TempUser:name', null );
					$session->save();

					// Invalidate any sessions for the expired temporary account
					SessionManager::singleton()->invalidateSessionsForUser(
						$this->userFactory->newFromUserIdentity( $user )
					);
				}
			}
		}

		if ( $this->tempUserCreator->shouldAutoCreate( $user, 'edit' ) ) {
			if ( $doAcquire ) {
				$name = $this->tempUserCreator->acquireAndStashName(
					$this->context->getRequest()->getSession() );
				if ( $name === null ) {
					$status = Status::newFatal( 'temp-user-unable-to-acquire' );
					$status->value = self::AS_UNABLE_TO_ACQUIRE_TEMP_ACCOUNT;
					return $status;
				}
				$this->unsavedTempUser = $this->userFactory->newUnsavedTempUser( $name );
				$this->tempUserName = $name;
			} else {
				$this->placeholderTempUser = $this->userFactory->newTempPlaceholder();
			}
			$this->tempUserCreateActive = true;
		}
		return Status::newGood();
	}

	/**
	 * If automatic user creation is enabled, create the user.
	 *
	 * This is a helper for internalAttemptSave().
	 *
	 * If the edit is a null edit, the user will not be created.
	 */
	private function createTempUser(): Status {
		if ( !$this->tempUserCreateActive ) {
			return Status::newGood();
		}
		$status = $this->tempUserCreator->create(
			$this->tempUserName,
			$this->context->getRequest()
		);
		if ( $status->isOK() ) {
			$this->placeholderTempUser = null;
			$this->unsavedTempUser = null;
			$this->savedTempUser = $status->getUser();
			$this->authManager->setRequestContextUserFromSessionUser();
			$this->tempUserCreateDone = true;
		}
		return $status;
	}

	/**
	 * Get the authority for permissions purposes.
	 *
	 * On an initial edit page GET request, if automatic temporary user creation
	 * is enabled, this may be a placeholder user with a fixed name. Such users
	 * are unsuitable for anything that uses or exposes the name, like
	 * throttling. The only thing a placeholder user is good for is fooling the
	 * permissions system into allowing edits by anons.
	 */
	private function getAuthority(): Authority {
		return $this->getUserForPermissions();
	}

	/**
	 * Get the user for permissions purposes, with declared type User instead
	 * of Authority for compatibility with PermissionManager.
	 *
	 * @return User
	 */
	private function getUserForPermissions() {
		if ( $this->savedTempUser ) {
			return $this->savedTempUser;
		} elseif ( $this->unsavedTempUser ) {
			return $this->unsavedTempUser;
		} elseif ( $this->placeholderTempUser ) {
			return $this->placeholderTempUser;
		} else {
			return $this->context->getUser();
		}
	}

	/**
	 * Get the user for preview or PST purposes. During the temporary user
	 * creation flow this may be an unsaved temporary user.
	 *
	 * @return User
	 */
	private function getUserForPreview() {
		if ( $this->savedTempUser ) {
			return $this->savedTempUser;
		} elseif ( $this->unsavedTempUser ) {
			return $this->unsavedTempUser;
		} elseif ( $this->firsttime && $this->placeholderTempUser ) {
			// Mostly a GET request and no temp user was aquired,
			// but needed for pst or content transform for preview,
			// fallback to a placeholder for this situation (T330943)
			return $this->placeholderTempUser;
		} elseif ( $this->tempUserCreateActive ) {
			throw new BadMethodCallException(
				"Can't use the request user for preview with IP masking enabled" );
		} else {
			return $this->context->getUser();
		}
	}

	/**
	 * Get the user suitable for permanent attribution in the database. This
	 * asserts that an anonymous user won't be used in IP masking mode.
	 *
	 * @return User
	 */
	private function getUserForSave() {
		if ( $this->savedTempUser ) {
			return $this->savedTempUser;
		} elseif ( $this->tempUserCreateActive ) {
			throw new BadMethodCallException(
				"Can't use the request user for storage with IP masking enabled" );
		} else {
			return $this->context->getUser();
		}
	}

	/**
	 * @param string $rigor PermissionManager::RIGOR_ constant
	 * @return PermissionStatus
	 */
	private function getEditPermissionStatus( string $rigor = PermissionManager::RIGOR_SECURE ): PermissionStatus {
		$user = $this->getUserForPermissions();
		return $this->permManager->getPermissionStatus(
			'edit',
			$user,
			$this->mTitle,
			$rigor
		);
	}

	/**
	 * Display a permissions error page, like OutputPage::showPermissionStatus(),
	 * but with the following differences:
	 * - If redlink=1, the user will be redirected to the page
	 * - If there is content to display or the error occurs while either saving,
	 *   previewing or showing the difference, it will be a
	 *   "View source for ..." page displaying the source code after the error message.
	 *
	 * @param PermissionStatus $status Permissions errors
	 * @throws PermissionsError
	 */
	private function displayPermissionStatus( PermissionStatus $status ): void {
		$out = $this->context->getOutput();
		if ( $this->context->getRequest()->getBool( 'redlink' ) ) {
			// The edit page was reached via a red link.
			// Redirect to the article page and let them click the edit tab if
			// they really want a permission error.
			$out->redirect( $this->mTitle->getFullURL() );
			return;
		}

		$content = $this->getContentObject();

		// Use the normal message if there's nothing to display:
		// page or section does not exist (T249978), and the user isn't in the middle of an edit
		if ( !$content || ( $this->firsttime && !$this->mTitle->exists() && $content->isEmpty() ) ) {
			$action = $this->mTitle->exists() ? 'edit' :
				( $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage' );
			throw new PermissionsError( $action, $status );
		}

		$this->displayViewSourcePage(
			$content,
			$out->formatPermissionStatus( $status, 'edit' )
		);
	}

	/**
	 * Display a read-only View Source page
	 * @param Content $content
	 * @param string $errorMessage additional wikitext error message to display
	 */
	private function displayViewSourcePage( Content $content, string $errorMessage ): void {
		$out = $this->context->getOutput();
		$this->getHookRunner()->onEditPage__showReadOnlyForm_initial( $this, $out );

		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setPageTitleMsg( $this->context->msg(
			'viewsource-title'
		)->plaintextParams(
			$this->getContextTitle()->getPrefixedText()
		) );
		$out->addBacklinkSubtitle( $this->getContextTitle() );
		$out->addHTML( $this->editFormPageTop );
		$out->addHTML( $this->editFormTextTop );

		if ( $errorMessage !== '' ) {
			$out->addWikiTextAsInterface( $errorMessage );
			$out->addHTML( "<hr />\n" );
		}

		# If the user made changes, preserve them when showing the markup
		# (This happens when a user is blocked during edit, for instance)
		if ( !$this->firsttime ) {
			$text = $this->textbox1;
			$out->addWikiMsg( 'viewyourtext' );
		} else {
			try {
				$text = $this->toEditText( $content );
			} catch ( MWException ) {
				# Serialize using the default format if the content model is not supported
				# (e.g. for an old revision with a different model)
				$text = $content->serialize();
			}
			$out->addWikiMsg( 'viewsourcetext' );
		}

		$out->addHTML( $this->editFormTextBeforeContent );
		$this->showTextbox( $text, 'wpTextbox1', [ 'readonly' ] );
		$out->addHTML( $this->editFormTextAfterContent );

		$out->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$out->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		$out->addHTML( $this->editFormTextBottom );
		if ( $this->mTitle->exists() ) {
			$out->returnToMain( null, $this->mTitle );
		}
	}

	/**
	 * Should we show a preview when the edit form is first shown?
	 *
	 * @return bool
	 */
	protected function previewOnOpen() {
		$config = $this->context->getConfig();
		$previewOnOpenNamespaces = $config->get( MainConfigNames::PreviewOnOpenNamespaces );
		$request = $this->context->getRequest();
		if ( $config->get( MainConfigNames::RawHtml ) ) {
			// If raw HTML is enabled, disable preview on open
			// since it has to be posted with a token for
			// security reasons
			return false;
		}
		$preview = $request->getRawVal( 'preview' );
		if ( $preview === 'yes' ) {
			// Explicit override from request
			return true;
		} elseif ( $preview === 'no' ) {
			// Explicit override from request
			return false;
		} elseif ( $this->section === 'new' ) {
			// Nothing *to* preview for new sections
			return false;
		} elseif ( ( $request->getCheck( 'preload' ) || $this->mTitle->exists() )
			&& $this->userOptionsLookup->getOption( $this->context->getUser(), 'previewonfirst' )
		) {
			// Standard preference behavior
			return true;
		} elseif ( !$this->mTitle->exists()
			&& isset( $previewOnOpenNamespaces[$this->mTitle->getNamespace()] )
			&& $previewOnOpenNamespaces[$this->mTitle->getNamespace()]
		) {
			// Categories are special
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Section editing is supported when the page content model allows
	 * section edit and we are editing current revision.
	 *
	 * @return bool True if this edit page supports sections, false otherwise.
	 */
	private function isSectionEditSupported(): bool {
		$currentRev = $this->page->getRevisionRecord();

		// $currentRev is null for non-existing pages, use the page default content model.
		$revContentModel = $currentRev
			? $currentRev->getMainContentModel()
			: $this->page->getContentModel();

		return (
			( $this->mArticle->getRevIdFetched() === $this->page->getLatest() ) &&
			$this->contentHandlerFactory->getContentHandler( $revContentModel )->supportsSections()
		);
	}

	/**
	 * This function collects the form data and uses it to populate various member variables.
	 * @param WebRequest &$request
	 * @throws ErrorPageError
	 */
	public function importFormData( &$request ) {
		# Section edit can come from either the form or a link
		$this->section = $request->getVal( 'wpSection', $request->getVal( 'section', '' ) );

		if ( $this->section !== null && $this->section !== '' && !$this->isSectionEditSupported() ) {
			throw new ErrorPageError( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
		}

		$this->isNew = !$this->mTitle->exists() || $this->section === 'new';

		if ( $request->wasPosted() ) {
			$this->importFormDataPosted( $request );
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( __METHOD__ . ": Not a posted form." );
			$this->textbox1 = '';
			$this->summary = '';
			$this->sectiontitle = null;
			$this->edittime = '';
			$this->editRevId = null;
			$this->starttime = wfTimestampNow();
			$this->edit = false;
			$this->preview = false;
			$this->save = false;
			$this->diff = false;
			$this->minoredit = false;
			// Watch may be overridden by request parameters
			$this->watchthis = $request->getBool( 'watchthis', false );
			if ( $this->watchlistExpiryEnabled ) {
				$this->watchlistExpiry = null;
			}
			$this->recreate = false;

			// When creating a new section, we can preload a section title by passing it as the
			// preloadtitle parameter in the URL (T15100)
			if ( $this->section === 'new' && $request->getCheck( 'preloadtitle' ) ) {
				$this->sectiontitle = $request->getVal( 'preloadtitle' );
				$this->setNewSectionSummary();
			} elseif ( $this->section !== 'new' && $request->getRawVal( 'summary' ) !== '' ) {
				$this->summary = $request->getText( 'summary' );
				if ( $this->summary !== '' ) {
					// If a summary has been preset using &summary= we don't want to prompt for
					// a different summary. Only prompt for a summary if the summary is blanked.
					// (T19416)
					$this->autoSumm = md5( '' );
				}
			}

			if ( $request->getVal( 'minor' ) ) {
				$this->minoredit = true;
			}
		}

		$this->oldid = $request->getInt( 'oldid' );
		$this->parentRevId = $request->getInt( 'parentRevId' );

		$this->markAsBot = $request->getBool( 'bot', true );
		$this->nosummary = $request->getBool( 'nosummary' );

		// May be overridden by revision.
		$this->contentModel = $request->getText( 'model', $this->contentModel );
		// May be overridden by revision.
		$this->contentFormat = $request->getText( 'format', $this->contentFormat );

		try {
			$handler = $this->contentHandlerFactory->getContentHandler( $this->contentModel );
		} catch ( MWUnknownContentModelException ) {
			throw new ErrorPageError(
				'editpage-invalidcontentmodel-title',
				'editpage-invalidcontentmodel-text',
				[ wfEscapeWikiText( $this->contentModel ) ]
			);
		}

		if ( !$handler->isSupportedFormat( $this->contentFormat ) ) {
			throw new ErrorPageError(
				'editpage-notsupportedcontentformat-title',
				'editpage-notsupportedcontentformat-text',
				[
					wfEscapeWikiText( $this->contentFormat ),
					wfEscapeWikiText( ContentHandler::getLocalizedName( $this->contentModel ) )
				]
			);
		}

		// Allow extensions to modify form data
		$this->getHookRunner()->onEditPage__importFormData( $this, $request );
	}

	private function importFormDataPosted( WebRequest $request ): void {
		# These fields need to be checked for encoding.
		# Also remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->textbox1 = rtrim( $request->getText( 'wpTextbox1' ) );
		if ( !$request->getCheck( 'wpTextbox2' ) ) {
			// Skip this if wpTextbox2 has input, it indicates that we came
			// from a conflict page with raw page text, not a custom form
			// modified by subclasses
			$textbox1 = $this->importContentFormData( $request );
			if ( $textbox1 !== null ) {
				$this->textbox1 = $textbox1;
			}
		}

		$this->unicodeCheck = $request->getText( 'wpUnicodeCheck' );

		if ( $this->section === 'new' ) {
			# Allow setting sectiontitle different from the edit summary.
			# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
			# currently doing double duty as both edit summary and section title. Right now this
			# is just to allow API edits to work around this limitation, but this should be
			# incorporated into the actual edit form when EditPage is rewritten (T20654, T28312).
			if ( $request->getCheck( 'wpSectionTitle' ) ) {
				$this->sectiontitle = $request->getText( 'wpSectionTitle' );
				if ( $request->getCheck( 'wpSummary' ) ) {
					$this->summary = $request->getText( 'wpSummary' );
				}
			} else {
				$this->sectiontitle = $request->getText( 'wpSummary' );
			}
		} else {
			$this->sectiontitle = null;
			$this->summary = $request->getText( 'wpSummary' );
		}

		# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
		# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
		# section titles. (T3600)
		# It is weird to modify 'sectiontitle', even when it is provided when using the API, but API
		# users have come to rely on it: https://github.com/wikimedia-gadgets/twinkle/issues/1625
		$this->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->summary );
		if ( $this->sectiontitle !== null ) {
			$this->sectiontitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->sectiontitle );
		}

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $this->section === 'new' ) {
			$this->setNewSectionSummary();
		}

		$this->edittime = $request->getVal( 'wpEdittime' );
		$this->editRevId = $request->getIntOrNull( 'editRevId' );
		$this->starttime = $request->getVal( 'wpStarttime' );

		$undidRev = $request->getInt( 'wpUndidRevision' );
		if ( $undidRev ) {
			$this->undidRev = $undidRev;
		}
		$undoAfter = $request->getInt( 'wpUndoAfter' );
		if ( $undoAfter ) {
			$this->undoAfter = $undoAfter;
		}

		$this->scrolltop = $request->getIntOrNull( 'wpScrolltop' );

		if ( $this->textbox1 === '' && !$request->getCheck( 'wpTextbox1' ) ) {
			// wpTextbox1 field is missing, possibly due to being "too big"
			// according to some filter rules that may have been configured
			// for security reasons.
			$this->incompleteForm = true;
		} else {
			// If we receive the last parameter of the request, we can fairly
			// claim the POST request has not been truncated.
			$this->incompleteForm = !$request->getVal( 'wpUltimateParam' );
		}
		if ( $this->incompleteForm ) {
			# If the form is incomplete, force to preview.
			wfDebug( __METHOD__ . ": Form data appears to be incomplete" );
			wfDebug( "POST DATA: " . var_export( $request->getPostValues(), true ) );
			$this->preview = true;
		} else {
			$this->preview = $request->getCheck( 'wpPreview' );
			$this->diff = $request->getCheck( 'wpDiff' );

			// Remember whether a save was requested, so we can indicate
			// if we forced preview due to session failure.
			$this->mTriedSave = !$this->preview;

			if ( $this->tokenOk( $request ) ) {
				# Some browsers will not report any submit button
				# if the user hits enter in the comment box.
				# The unmarked state will be assumed to be a save,
				# if the form seems otherwise complete.
				wfDebug( __METHOD__ . ": Passed token check." );
			} elseif ( $this->diff ) {
				# Failed token check, but only requested "Show Changes".
				wfDebug( __METHOD__ . ": Failed token check; Show Changes requested." );
			} else {
				# Page might be a hack attempt posted from
				# an external site. Preview instead of saving.
				wfDebug( __METHOD__ . ": Failed token check; forcing preview" );
				$this->preview = true;
			}
		}
		$this->save = !$this->preview && !$this->diff;
		if ( !$this->edittime || !preg_match( '/^\d{14}$/', $this->edittime ) ) {
			$this->edittime = null;
		}

		if ( !$this->starttime || !preg_match( '/^\d{14}$/', $this->starttime ) ) {
			$this->starttime = null;
		}

		$this->recreate = $request->getCheck( 'wpRecreate' );

		$user = $this->context->getUser();

		$this->minoredit = $request->getCheck( 'wpMinoredit' );
		$this->watchthis = $request->getCheck( 'wpWatchthis' );
		$submittedExpiry = $request->getText( 'wpWatchlistExpiry' );
		if ( $this->watchlistExpiryEnabled && $submittedExpiry !== '' ) {
			// This parsing of the user-posted expiry is done for both preview and saving. This
			// is necessary because ApiEditPage uses preview when it saves (yuck!). Note that it
			// only works because the unnormalized value is retrieved again below in
			// getCheckboxesDefinitionForWatchlist().
			$submittedExpiry = ExpiryDef::normalizeExpiry( $submittedExpiry, TS_ISO_8601 );
			if ( $submittedExpiry !== false ) {
				$this->watchlistExpiry = $submittedExpiry;
			}
		}

		# Don't force edit summaries when a user is editing their own user or talk page
		if ( ( $this->mTitle->getNamespace() === NS_USER || $this->mTitle->getNamespace() === NS_USER_TALK )
			&& $this->mTitle->getText() === $user->getName()
		) {
			$this->allowBlankSummary = true;
		} else {
			$this->allowBlankSummary = $request->getBool( 'wpIgnoreBlankSummary' )
				|| !$this->userOptionsLookup->getOption( $user, 'forceeditsummary' );
		}

		$this->autoSumm = $request->getText( 'wpAutoSummary' );

		$this->allowBlankArticle = $request->getBool( 'wpIgnoreBlankArticle' );
		$allowedProblematicRedirectTargetText = $request->getText( 'wpAllowedProblematicRedirectTarget' );
		$this->allowedProblematicRedirectTarget = $allowedProblematicRedirectTargetText === ''
			? null : Title::newFromText( $allowedProblematicRedirectTargetText );
		$this->ignoreProblematicRedirects = $request->getBool( 'wpIgnoreProblematicRedirects' );

		$changeTags = $request->getVal( 'wpChangeTags' );
		$changeTagsAfterPreview = $request->getVal( 'wpChangeTagsAfterPreview' );
		if ( $changeTags === null || $changeTags === '' ) {
			$this->changeTags = [];
		} else {
			$this->changeTags = array_filter(
				array_map(
					'trim',
					explode( ',', $changeTags )
				)
			);
		}
		if ( $changeTagsAfterPreview !== null && $changeTagsAfterPreview !== '' ) {
			$this->changeTags = array_merge( $this->changeTags, array_filter(
				array_map(
					'trim',
					explode( ',', $changeTagsAfterPreview )
				)
			) );
		}
	}

	/**
	 * Subpage overridable method for extracting the page content data from the
	 * posted form to be placed in $this->textbox1, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param WebRequest &$request
	 * @return string|null
	 */
	protected function importContentFormData( &$request ) {
		return null; // Don't do anything, EditPage already extracted wpTextbox1
	}

	/**
	 * Initialise form fields in the object
	 * Called on the first invocation, e.g. when a user clicks an edit link
	 * @return bool If the requested section is valid
	 */
	private function initialiseForm(): bool {
		$this->edittime = $this->page->getTimestamp();
		$this->editRevId = $this->page->getLatest();

		$dummy = $this->contentHandlerFactory
			->getContentHandler( $this->contentModel )
			->makeEmptyContent();
		$content = $this->getContentObject( $dummy ); # TODO: track content object?!
		if ( $content === $dummy ) { // Invalid section
			$this->noSuchSectionPage();
			return false;
		}

		if ( !$content ) {
			$out = $this->context->getOutput();
			$this->editFormPageTop .= Html::errorBox(
				$out->parseAsInterface( $this->context->msg( 'missing-revision-content',
					$this->oldid,
					Message::plaintextParam( $this->mTitle->getPrefixedText() )
				) )
			);
		} elseif ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			$modelMsg = $this->getContext()->msg( 'content-model-' . $content->getModel() );
			$modelName = $modelMsg->exists() ? $modelMsg->text() : $content->getModel();

			$out = $this->context->getOutput();
			$out->showErrorPage(
				'modeleditnotsupported-title',
				'modeleditnotsupported-text',
				[ $modelName ]
			);
			return false;
		}

		$this->textbox1 = $this->toEditText( $content );

		$user = $this->context->getUser();
		// activate checkboxes if user wants them to be always active
		# Sort out the "watch" checkbox
		if ( $this->userOptionsLookup->getOption( $user, 'watchdefault' ) ) {
			# Watch all edits
			$this->watchthis = true;
		} elseif ( $this->userOptionsLookup->getOption( $user, 'watchcreations' ) && !$this->mTitle->exists() ) {
			# Watch creations
			$this->watchthis = true;
		} elseif ( $this->watchlistManager->isWatched( $user, $this->mTitle ) ) {
			# Already watched
			$this->watchthis = true;
		}
		if ( $this->watchthis && $this->watchlistExpiryEnabled ) {
			$watchedItem = $this->watchedItemStore->getWatchedItem( $user, $this->getTitle() );
			$this->watchlistExpiry = $watchedItem ? $watchedItem->getExpiry() : null;
		}
		if ( !$this->isNew && $this->userOptionsLookup->getOption( $user, 'minordefault' ) ) {
			$this->minoredit = true;
		}
		if ( $this->textbox1 === false ) {
			return false;
		}
		return true;
	}

	/**
	 * @param Content|null $defaultContent The default value to return
	 * @return Content|false|null Content on success, $defaultContent for invalid sections
	 * @since 1.21
	 */
	protected function getContentObject( $defaultContent = null ) {
		$services = MediaWikiServices::getInstance();
		$request = $this->context->getRequest();

		$content = false;

		// For non-existent articles and new sections, use preload text if any.
		if ( !$this->mTitle->exists() || $this->section === 'new' ) {
			$content = $services->getPreloadedContentBuilder()->getPreloadedContent(
				$this->mTitle->toPageIdentity(),
				$this->context->getUser(),
				$request->getVal( 'preload' ),
				$request->getArray( 'preloadparams', [] ),
				$request->getVal( 'section' )
			);
		// For existing pages, get text based on "undo" or section parameters.
		} elseif ( $this->section !== '' ) {
			// Get section edit text (returns $def_text for invalid sections)
			$orig = $this->getOriginalContent( $this->getAuthority() );
			$content = $orig ? $orig->getSection( $this->section ) : null;

			if ( !$content ) {
				$content = $defaultContent;
			}
		} else {
			$undoafter = $request->getInt( 'undoafter' );
			$undo = $request->getInt( 'undo' );

			if ( $undo > 0 && $undoafter > 0 ) {
				// The use of getRevisionByTitle() is intentional, as allowing access to
				// arbitrary revisions on arbitrary pages bypass partial visibility restrictions (T297322).
				$undorev = $this->revisionStore->getRevisionByTitle( $this->mTitle, $undo );
				$oldrev = $this->revisionStore->getRevisionByTitle( $this->mTitle, $undoafter );
				$undoMsg = null;

				# Make sure it's the right page,
				# the revisions exist and they were not deleted.
				# Otherwise, $content will be left as-is.
				if ( $undorev !== null && $oldrev !== null &&
					!$undorev->isDeleted( RevisionRecord::DELETED_TEXT ) &&
					!$oldrev->isDeleted( RevisionRecord::DELETED_TEXT )
				) {
					if ( WikiPage::hasDifferencesOutsideMainSlot( $undorev, $oldrev )
						|| !$this->isSupportedContentModel(
							$oldrev->getMainContentModel()
						)
					) {
						// Hack for undo while EditPage can't handle multi-slot editing
						$this->context->getOutput()->redirect( $this->mTitle->getFullURL( [
							'action' => 'mcrundo',
							'undo' => $undo,
							'undoafter' => $undoafter,
						] ) );
						return false;
					} else {
						$content = $this->getUndoContent( $undorev, $oldrev, $undoMsg );
					}

					if ( $undoMsg === null ) {
						$oldContent = $this->page->getContent( RevisionRecord::RAW );
						$parserOptions = ParserOptions::newFromUserAndLang(
							$this->getUserForPreview(),
							$services->getContentLanguage()
						);
						$contentTransformer = $services->getContentTransformer();
						$newContent = $contentTransformer->preSaveTransform(
							$content, $this->mTitle, $this->getUserForPreview(), $parserOptions
						);

						if ( $newContent->getModel() !== $oldContent->getModel() ) {
							// The undo may change content
							// model if its reverting the top
							// edit. This can result in
							// mismatched content model/format.
							$this->contentModel = $newContent->getModel();
							$oldMainSlot = $oldrev->getSlot(
								SlotRecord::MAIN,
								RevisionRecord::RAW
							);
							$this->contentFormat = $oldMainSlot->getFormat();
							if ( $this->contentFormat === null ) {
								$this->contentFormat = $this->contentHandlerFactory
									->getContentHandler( $oldMainSlot->getModel() )
									->getDefaultFormat();
							}
						}

						if ( $newContent->equals( $oldContent ) ) {
							# Tell the user that the undo results in no change,
							# i.e. the revisions were already undone.
							$undoMsg = 'nochange';
							$content = false;
						} else {
							# Inform the user of our success and set an automatic edit summary
							$undoMsg = 'success';
							$this->generateUndoEditSummary( $oldrev, $undo, $undorev, $services );
							$this->undidRev = $undo;
							$this->undoAfter = $undoafter;
							$this->formtype = 'diff';
						}
					}
				} else {
					// Failed basic checks.
					// Older revisions may have been removed since the link
					// was created, or we may simply have got bogus input.
					$undoMsg = 'norev';
				}

				$out = $this->context->getOutput();
				// Messages: undo-success, undo-failure, undo-main-slot-only, undo-norev,
				// undo-nochange.
				$class = "mw-undo-{$undoMsg}";
				$html = $this->context->msg( 'undo-' . $undoMsg )->parse();
				if ( $undoMsg !== 'success' ) {
					$html = Html::errorBox( $html );
				}
				$this->editFormPageTop .= Html::rawElement(
					'div',
					[ 'class' => $class ],
					$html
				);
			}

			if ( $content === false ) {
				$content = $this->getOriginalContent( $this->getAuthority() );
			}
		}

		return $content;
	}

	/**
	 * When using the "undo" action, generate a default edit summary and save it
	 * to $this->summary
	 *
	 * @param RevisionRecord|null $oldrev The revision in the URI "undoafter" field
	 * @param int $undo The integer in the URI "undo" field
	 * @param RevisionRecord|null $undorev The revision in the URI "undo" field
	 * @param MediaWikiServices $services Service container
	 * @return void
	 */
	private function generateUndoEditSummary( ?RevisionRecord $oldrev, int $undo,
		?RevisionRecord $undorev, MediaWikiServices $services
	) {
		// If we just undid one rev, use an autosummary
		$firstrev = $this->revisionStore->getNextRevision( $oldrev );
		if ( $firstrev && $firstrev->getId() == $undo ) {
			$userText = $undorev->getUser() ?
				$undorev->getUser()->getName() :
				'';
			if ( $userText === '' ) {
				$undoSummary = $this->context->msg(
					'undo-summary-username-hidden',
					$undo
				)->inContentLanguage()->text();
			// Handle external users (imported revisions)
			} elseif ( ExternalUserNames::isExternal( $userText ) ) {
				$userLinkTitle = ExternalUserNames::getUserLinkTitle( $userText );
				if ( $userLinkTitle ) {
					$userLink = $userLinkTitle->getPrefixedText();
					$undoSummary = $this->context->msg(
						'undo-summary-import',
						$undo,
						$userLink,
						$userText
					)->inContentLanguage()->text();
				} else {
					$undoSummary = $this->context->msg(
						'undo-summary-import2',
						$undo,
						$userText
					)->inContentLanguage()->text();
				}
			} else {
				$undoIsAnon =
					!$undorev->getUser() ||
					!$undorev->getUser()->isRegistered();
				$disableAnonTalk = $services->getMainConfig()->get( MainConfigNames::DisableAnonTalk );
				$undoMessage = ( $undoIsAnon && $disableAnonTalk ) ?
					'undo-summary-anon' :
					'undo-summary';
				$undoSummary = $this->context->msg(
					$undoMessage,
					$undo,
					$userText
				)->inContentLanguage()->text();
			}
			if ( $this->summary === '' ) {
				$this->summary = $undoSummary;
			} else {
				$this->summary = $undoSummary . $this->context->msg( 'colon-separator' )
					->inContentLanguage()->text() . $this->summary;
			}
		}
	}

	/**
	 * Returns the result of a three-way merge when undoing changes.
	 *
	 * @param RevisionRecord $undoRev Newest revision being undone. Corresponds to `undo`
	 *        URL parameter.
	 * @param RevisionRecord $oldRev Revision that is being restored. Corresponds to
	 *        `undoafter` URL parameter.
	 * @param ?string &$error If false is returned, this will be set to "norev"
	 *   if the revision failed to load, or "failure" if the content handler
	 *   failed to merge the required changes.
	 *
	 * @return Content|false
	 */
	private function getUndoContent( RevisionRecord $undoRev, RevisionRecord $oldRev, &$error ) {
		$handler = $this->contentHandlerFactory
			->getContentHandler( $undoRev->getSlot(
				SlotRecord::MAIN,
				RevisionRecord::RAW
			)->getModel() );
		$currentContent = $this->page->getRevisionRecord()
			->getContent( SlotRecord::MAIN );
		$undoContent = $undoRev->getContent( SlotRecord::MAIN );
		$undoAfterContent = $oldRev->getContent( SlotRecord::MAIN );
		$undoIsLatest = $this->page->getRevisionRecord()->getId() === $undoRev->getId();
		if ( $currentContent === null
			|| $undoContent === null
			|| $undoAfterContent === null
		) {
			$error = 'norev';
			return false;
		}

		$content = $handler->getUndoContent(
			$currentContent,
			$undoContent,
			$undoAfterContent,
			$undoIsLatest
		);
		if ( $content === false ) {
			$error = 'failure';
		}
		return $content;
	}

	/**
	 * Get the content of the wanted revision, without section extraction.
	 *
	 * The result of this function can be used to compare user's input with
	 * section replaced in its context (using WikiPage::replaceSectionAtRev())
	 * to the original text of the edit.
	 *
	 * This differs from Article::getContent() that when a missing revision is
	 * encountered the result will be null and not the
	 * 'missing-revision' message.
	 *
	 * @param Authority $performer to get the revision for
	 * @return Content|null
	 */
	private function getOriginalContent( Authority $performer ): ?Content {
		if ( $this->section === 'new' ) {
			return $this->getCurrentContent();
		}
		$revRecord = $this->mArticle->fetchRevisionRecord();
		if ( $revRecord === null ) {
			return $this->contentHandlerFactory
				->getContentHandler( $this->contentModel )
				->makeEmptyContent();
		}
		return $revRecord->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer );
	}

	/**
	 * Get the edit's parent revision ID
	 *
	 * The "parent" revision is the ancestor that should be recorded in this
	 * page's revision history.  It is either the revision ID of the in-memory
	 * article content, or in the case of a 3-way merge in order to rebase
	 * across a recoverable edit conflict, the ID of the newer revision to
	 * which we have rebased this page.
	 *
	 * @return int Revision ID
	 */
	private function getParentRevId() {
		if ( $this->parentRevId ) {
			return $this->parentRevId;
		} else {
			return $this->mArticle->getRevIdFetched();
		}
	}

	/**
	 * Get the current content of the page. This is basically similar to
	 * WikiPage::getContent( RevisionRecord::RAW ) except that when the page doesn't
	 * exist an empty content object is returned instead of null.
	 *
	 * @since 1.21
	 * @return Content
	 */
	protected function getCurrentContent() {
		$revRecord = $this->page->getRevisionRecord();
		$content = $revRecord ? $revRecord->getContent(
			SlotRecord::MAIN,
			RevisionRecord::RAW
		) : null;

		if ( $content === null ) {
			return $this->contentHandlerFactory
				->getContentHandler( $this->contentModel )
				->makeEmptyContent();
		}

		return $content;
	}

	/**
	 * Make sure the form isn't faking a user's credentials.
	 *
	 * @param WebRequest $request
	 * @return bool
	 */
	private function tokenOk( WebRequest $request ): bool {
		$token = $request->getVal( 'wpEditToken' );
		$user = $this->context->getUser();
		$this->mTokenOk = $user->matchEditToken( $token );
		return $this->mTokenOk;
	}

	/**
	 * Sets post-edit cookie indicating the user just saved a particular revision.
	 *
	 * This uses a temporary cookie for each revision ID so separate saves will never
	 * interfere with each other.
	 *
	 * Article::view deletes the cookie on server-side after the redirect and
	 * converts the value to the global JavaScript variable wgPostEdit.
	 *
	 * If the variable were set on the server, it would be cached, which is unwanted
	 * since the post-edit state should only apply to the load right after the save.
	 *
	 * @param int $statusValue The status value (to check for new article status)
	 */
	private function setPostEditCookie( int $statusValue ): void {
		$revisionId = $this->page->getLatest();
		$postEditKey = self::POST_EDIT_COOKIE_KEY_PREFIX . $revisionId;

		$val = 'saved';
		if ( $statusValue === self::AS_SUCCESS_NEW_ARTICLE ) {
			$val = 'created';
		} elseif ( $this->oldid ) {
			$val = 'restored';
		}
		if ( $this->tempUserCreateDone ) {
			$val .= '+tempuser';
		}

		$response = $this->context->getRequest()->response();
		$response->setCookie( $postEditKey, $val, time() + self::POST_EDIT_COOKIE_DURATION );
	}

	/**
	 * Attempt submission
	 * @param array|false &$resultDetails See docs for $result in internalAttemptSave @phan-output-reference
	 * @throws UserBlockedError|ReadOnlyError|ThrottledError|PermissionsError
	 * @return Status
	 */
	public function attemptSave( &$resultDetails = false ) {
		// Allow bots to exempt some edits from bot flagging
		$markAsBot = $this->markAsBot
			&& $this->getAuthority()->isAllowed( 'bot' );

		// Allow trusted users to mark some edits as minor
		$markAsMinor = $this->minoredit && !$this->isNew
			&& $this->getAuthority()->isAllowed( 'minoredit' );

		$status = $this->internalAttemptSave( $resultDetails, $markAsBot, $markAsMinor );

		$this->getHookRunner()->onEditPage__attemptSave_after( $this, $status, $resultDetails );

		return $status;
	}

	/**
	 * Log when a page was successfully saved after the edit conflict view
	 */
	private function incrementResolvedConflicts(): void {
		if ( $this->context->getRequest()->getText( 'mode' ) !== 'conflict' ) {
			return;
		}

		$this->getEditConflictHelper()->incrementResolvedStats( $this->context->getUser() );
	}

	/**
	 * Handle status, such as after attempt save
	 *
	 * @param Status $status
	 * @param array|false $resultDetails
	 *
	 * @throws ErrorPageError
	 * @return bool False, if output is done, true if rest of the form should be displayed
	 */
	private function handleStatus( Status $status, $resultDetails ): bool {
		$statusValue = is_int( $status->value ) ? $status->value : 0;

		/**
		 * @todo FIXME: once the interface for internalAttemptSave() is made
		 *   nicer, this should use the message in $status
		 */
		if ( $statusValue === self::AS_SUCCESS_UPDATE
			|| $statusValue === self::AS_SUCCESS_NEW_ARTICLE
		) {
			$this->incrementResolvedConflicts();

			$this->didSave = true;
			if ( !$resultDetails['nullEdit'] ) {
				$this->setPostEditCookie( $statusValue );
			}
		}

		$out = $this->context->getOutput();

		// "wpExtraQueryRedirect" is a hidden input to modify
		// after save URL and is not used by actual edit form
		$request = $this->context->getRequest();
		$extraQueryRedirect = $request->getVal( 'wpExtraQueryRedirect' );

		switch ( $statusValue ) {
			// Status codes for which the error/warning message is generated somewhere else in this class.
			// They should be refactored to provide their own messages and handled below (T384399).
			case self::AS_HOOK_ERROR_EXPECTED:
			case self::AS_ARTICLE_WAS_DELETED:
			case self::AS_CONFLICT_DETECTED:
			case self::AS_SUMMARY_NEEDED:
			case self::AS_TEXTBOX_EMPTY:
			case self::AS_END:
			case self::AS_BLANK_ARTICLE:
			case self::AS_REVISION_WAS_DELETED:
				return true;

			case self::AS_HOOK_ERROR:
				return false;

			// Status codes that provide their own error/warning messages. Most error scenarios that don't
			// need custom user interface (e.g. edit conflicts) should be handled here, one day (T384399).
			case self::AS_BROKEN_REDIRECT:
			case self::AS_DOUBLE_REDIRECT:
			case self::AS_DOUBLE_REDIRECT_LOOP:
			case self::AS_CONTENT_TOO_BIG:
			case self::AS_MAX_ARTICLE_SIZE_EXCEEDED:
			case self::AS_PARSE_ERROR:
			case self::AS_SELF_REDIRECT:
			case self::AS_UNABLE_TO_ACQUIRE_TEMP_ACCOUNT:
			case self::AS_UNICODE_NOT_SUPPORTED:
				foreach ( $status->getMessages() as $msg ) {
					$out->addHTML( Html::errorBox(
						$this->context->msg( $msg )->parse()
					) );
				}
				return true;

			case self::AS_SUCCESS_NEW_ARTICLE:
				$queryParts = [];
				if ( $resultDetails['redirect'] ) {
					$queryParts[] = 'redirect=no';
				}
				if ( $extraQueryRedirect ) {
					$queryParts[] = $extraQueryRedirect;
				}
				$anchor = $resultDetails['sectionanchor'] ?? '';
				$this->doPostEditRedirect( implode( '&', $queryParts ), $anchor );
				return false;

			case self::AS_SUCCESS_UPDATE:
				$extraQuery = '';
				$sectionanchor = $resultDetails['sectionanchor'];
				// Give extensions a chance to modify URL query on update
				$this->getHookRunner()->onArticleUpdateBeforeRedirect( $this->mArticle,
					$sectionanchor, $extraQuery );

				$queryParts = [];
				if ( $resultDetails['redirect'] ) {
					$queryParts[] = 'redirect=no';
				}
				if ( $extraQuery ) {
					$queryParts[] = $extraQuery;
				}
				if ( $extraQueryRedirect ) {
					$queryParts[] = $extraQueryRedirect;
				}
				$this->doPostEditRedirect( implode( '&', $queryParts ), $sectionanchor );
				return false;

			case self::AS_SPAM_ERROR:
				$this->spamPageWithContent( $resultDetails['spam'] ?? false );
				return false;

			case self::AS_BLOCKED_PAGE_FOR_USER:
				throw new UserBlockedError(
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
					$this->context->getUser()->getBlock(),
					$this->context->getUser(),
					$this->context->getLanguage(),
					$request->getIP()
				);

			case self::AS_IMAGE_REDIRECT_ANON:
			case self::AS_IMAGE_REDIRECT_LOGGED:
				throw new PermissionsError( 'upload' );

			case self::AS_READ_ONLY_PAGE_ANON:
			case self::AS_READ_ONLY_PAGE_LOGGED:
				throw new PermissionsError( 'edit' );

			case self::AS_READ_ONLY_PAGE:
				throw new ReadOnlyError;

			case self::AS_RATE_LIMITED:
				$out->addHTML( Html::errorBox(
					$this->context->msg( 'actionthrottledtext' )->parse()
				) );
				return true;

			case self::AS_NO_CREATE_PERMISSION:
				$permission = $this->mTitle->isTalkPage() ? 'createtalk' : 'createpage';
				throw new PermissionsError( $permission );

			case self::AS_NO_CHANGE_CONTENT_MODEL:
				throw new PermissionsError( 'editcontentmodel' );

			default:
				// We don't recognize $statusValue. The only way that can happen
				// is if an extension hook aborted from inside ArticleSave.
				// Render the status object into $this->hookError
				// FIXME this sucks, we should just use the Status object throughout
				$this->hookError = Html::errorBox(
					"\n" . $status->getWikiText( false, false, $this->context->getLanguage() )
				);
				return true;
		}
	}

	/**
	 * Emit the post-save redirect. The URL is modifiable with a hook.
	 *
	 * @param string $query
	 * @param string $anchor
	 * @return void
	 */
	private function doPostEditRedirect( $query, $anchor ) {
		$out = $this->context->getOutput();
		$url = $this->mTitle->getFullURL( $query ) . $anchor;
		$user = $this->getUserForSave();
		// If the temporary account was created in this request,
		// or if the temporary account has zero edits (implying
		// that the account was created during a failed edit
		// attempt in a previous request), perform the top-level
		// redirect to ensure the account is attached.
		// Note that the temp user could already have performed
		// the top-level redirect if this a first edit on
		// a wiki that is not the user's home wiki.
		$shouldRedirectForTempUser = $this->tempUserCreateDone ||
			( $user->isTemp() && ( $user->getEditCount() === 0 ) );
		if ( $shouldRedirectForTempUser ) {
			$this->getHookRunner()->onTempUserCreatedRedirect(
				$this->context->getRequest()->getSession(),
				$user,
				$this->mTitle->getPrefixedDBkey(),
				$query,
				$anchor,
				$url
			);
		}
		$out->redirect( $url );
	}

	/**
	 * Set the edit summary and link anchor to be used for a new section.
	 */
	private function setNewSectionSummary(): void {
		Assert::precondition( $this->section === 'new', 'This method can only be called for new sections' );
		Assert::precondition( $this->sectiontitle !== null, 'This method can only be called for new sections' );

		$services = MediaWikiServices::getInstance();
		$parser = $services->getParser();
		$textFormatter = $services->getMessageFormatterFactory()->getTextFormatter(
			$services->getContentLanguageCode()->toString()
		);

		if ( $this->sectiontitle !== '' ) {
			$this->newSectionAnchor = $this->guessSectionName( $this->sectiontitle );
			// If no edit summary was specified, create one automatically from the section
			// title and have it link to the new section. Otherwise, respect the summary as
			// passed.
			if ( $this->summary === '' ) {
				$messageValue = MessageValue::new( 'newsectionsummary' )
					->plaintextParams( $parser->stripSectionName( $this->sectiontitle ) );
				$this->summary = $textFormatter->format( $messageValue );
			}
		} else {
			$this->newSectionAnchor = '';
		}
	}

	/**
	 * Attempt submission (no UI)
	 *
	 * @param array &$result Array to add statuses to, currently with the
	 *   possible keys:
	 *   - spam (string): Spam string from content if any spam is detected by
	 *     matchSpamRegex.
	 *   - sectionanchor (string): Section anchor for a section save.
	 *   - nullEdit (bool): Set if doUserEditContent is OK.  True if null edit,
	 *     false otherwise.
	 *   - redirect (bool): Set if doUserEditContent is OK. True if resulting
	 *     revision is a redirect.
	 * @param bool $markAsBot True if edit is being made under the bot right
	 *     and the bot wishes the edit to be marked as such.
	 * @param bool $markAsMinor True if edit should be marked as minor.
	 *
	 * @return Status Status object, possibly with a message, but always with
	 *   one of the AS_* constants in $status->value,
	 *
	 * @todo FIXME: This interface is TERRIBLE, but hard to get rid of due to
	 *   various error display idiosyncrasies. There are also lots of cases
	 *   where error metadata is set in the object and retrieved later instead
	 *   of being returned, e.g. AS_CONTENT_TOO_BIG and
	 *   AS_BLOCKED_PAGE_FOR_USER. All that stuff needs to be cleaned up some
	 * time.
	 */
	private function internalAttemptSave( &$result, $markAsBot = false, $markAsMinor = false ) {
		// If an attempt to acquire a temporary name failed, don't attempt to do anything else.
		if ( $this->unableToAcquireTempName ) {
			$status = Status::newFatal( 'temp-user-unable-to-acquire' );
			$status->value = self::AS_UNABLE_TO_ACQUIRE_TEMP_ACCOUNT;
			return $status;
		}
		// Auto-create the temporary account user, if the feature is enabled.
		// We create the account before any constraint checks or edit hooks fire, to ensure
		// that we have an actor and user account that can be used for any logs generated
		// by the edit attempt, and to ensure continuity in the user experience (if a constraint
		// denies an edit to a logged-out user, that history should be associated with the
		// eventually successful account creation)
		$tempAccountStatus = $this->createTempUser();
		if ( !$tempAccountStatus->isOK() ) {
			return $tempAccountStatus;
		}
		if ( $tempAccountStatus instanceof CreateStatus ) {
			$result['savedTempUser'] = $tempAccountStatus->getUser();
		}

		$useNPPatrol = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseNPPatrol );
		$useRCPatrol = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseRCPatrol );
		if ( !$this->getHookRunner()->onEditPage__attemptSave( $this ) ) {
			wfDebug( "Hook 'EditPage::attemptSave' aborted article saving" );
			$status = Status::newFatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		}

		if ( !$this->getHookRunner()->onEditFilter( $this, $this->textbox1, $this->section,
			$this->hookError, $this->summary )
		) {
			# Error messages etc. could be handled within the hook...
			$status = Status::newFatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR;
			return $status;
		} elseif ( $this->hookError ) {
			# ...or the hook could be expecting us to produce an error
			$status = Status::newFatal( 'hookaborted' );
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return $status;
		}

		try {
			# Construct Content object
			$textbox_content = $this->toEditContent( $this->textbox1 );
		} catch ( MWContentSerializationException $ex ) {
			$status = Status::newFatal(
				'content-failed-to-parse',
				$this->contentModel,
				$this->contentFormat,
				$ex->getMessage()
			);
			$status->value = self::AS_PARSE_ERROR;
			return $status;
		}

		$this->contentLength = strlen( $this->textbox1 );

		$requestUser = $this->context->getUser();
		$authority = $this->getAuthority();
		$pstUser = $this->getUserForPreview();

		$changingContentModel = false;
		if ( $this->contentModel !== $this->mTitle->getContentModel() ) {
			$changingContentModel = true;
			$oldContentModel = $this->mTitle->getContentModel();
		}

		// BEGINNING OF MIGRATION TO EDITCONSTRAINT SYSTEM (see T157658)
		/** @var EditConstraintFactory $constraintFactory */
		$constraintFactory = MediaWikiServices::getInstance()->getService( '_EditConstraintFactory' );
		$constraintRunner = new EditConstraintRunner();

		// UnicodeConstraint: ensure that `$this->unicodeCheck` is the correct unicode
		$constraintRunner->addConstraint(
			new UnicodeConstraint( $this->unicodeCheck )
		);

		// SimpleAntiSpamConstraint: ensure that the context request does not have
		// `wpAntispam` set
		// Use $user since there is no permissions aspect
		$constraintRunner->addConstraint(
			$constraintFactory->newSimpleAntiSpamConstraint(
				$this->context->getRequest()->getText( 'wpAntispam' ),
				$requestUser,
				$this->mTitle
			)
		);

		// SpamRegexConstraint: ensure that the summary and text don't match the spam regex
		$constraintRunner->addConstraint(
			$constraintFactory->newSpamRegexConstraint(
				$this->summary,
				$this->sectiontitle,
				$this->textbox1,
				$this->context->getRequest()->getIP(),
				$this->mTitle
			)
		);
		$constraintRunner->addConstraint(
			new ImageRedirectConstraint(
				$textbox_content,
				$this->mTitle,
				$authority
			)
		);
		$constraintRunner->addConstraint(
			$constraintFactory->newReadOnlyConstraint()
		);

		// Load the page data from the primary DB. If anything changes in the meantime,
		// we detect it by using page_latest like a token in a 1 try compare-and-swap.
		$this->page->loadPageData( IDBAccessObject::READ_LATEST );
		$new = !$this->page->exists();

		$constraintRunner->addConstraint(
			new AuthorizationConstraint(
				$authority,
				$this->mTitle,
				$new
			)
		);
		$constraintRunner->addConstraint(
			new ContentModelChangeConstraint(
				$authority,
				$this->mTitle,
				$this->contentModel
			)
		);
		$constraintRunner->addConstraint(
			$constraintFactory->newLinkPurgeRateLimitConstraint(
				$requestUser->toRateLimitSubject()
			)
		);
		$constraintRunner->addConstraint(
			// Same constraint is used to check size before and after merging the
			// edits, which use different failure codes
			$constraintFactory->newPageSizeConstraint(
				$this->contentLength,
				PageSizeConstraint::BEFORE_MERGE
			)
		);
		$constraintRunner->addConstraint(
			new ChangeTagsConstraint( $authority, $this->changeTags )
		);

		// If the article has been deleted while editing, don't save it without
		// confirmation
		$constraintRunner->addConstraint(
			new AccidentalRecreationConstraint(
				$this->wasDeletedSinceLastEdit(),
				$this->recreate
			)
		);

		// Check the constraints
		if ( !$constraintRunner->checkConstraints() ) {
			$failed = $constraintRunner->getFailedConstraint();

			// Need to check SpamRegexConstraint here, to avoid needing to pass
			// $result by reference again
			if ( $failed instanceof SpamRegexConstraint ) {
				$result['spam'] = $failed->getMatch();
			} else {
				$this->handleFailedConstraint( $failed );
			}

			return Status::wrap( $failed->getLegacyStatus() );
		}
		// END OF MIGRATION TO EDITCONSTRAINT SYSTEM (continued below)

		$flags = EDIT_AUTOSUMMARY |
			( $new ? EDIT_NEW : EDIT_UPDATE ) |
			( $markAsMinor ? EDIT_MINOR : 0 ) |
			( $markAsBot ? EDIT_FORCE_BOT : 0 );

		if ( $new ) {
			$content = $textbox_content;

			$result['sectionanchor'] = '';
			if ( $this->section === 'new' ) {
				if ( $this->sectiontitle !== null ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->sectiontitle );
				}
				$result['sectionanchor'] = $this->newSectionAnchor;
			}

			$pageUpdater = $this->page->newPageUpdater( $pstUser )
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
				->setContent( SlotRecord::MAIN, $content );
			$pageUpdater->prepareUpdate( $flags );

			// BEGINNING OF MIGRATION TO EDITCONSTRAINT SYSTEM (see T157658)
			// Create a new runner to avoid rechecking the prior constraints, use the same factory
			$constraintRunner = new EditConstraintRunner();

			// Don't save a new page if it's blank or if it's a MediaWiki:
			// message with content equivalent to default (allow empty pages
			// in this case to disable messages, see T52124)
			$constraintRunner->addConstraint(
				new DefaultTextConstraint(
					$this->mTitle,
					$this->allowBlankArticle,
					$this->textbox1
				)
			);

			$constraintRunner->addConstraint(
				$constraintFactory->newEditFilterMergedContentHookConstraint(
					$content,
					$this->context,
					$this->summary,
					$markAsMinor,
					$this->context->getLanguage(),
					$pstUser
				)
			);

			// Check the constraints
			if ( !$constraintRunner->checkConstraints() ) {
				$failed = $constraintRunner->getFailedConstraint();
				$this->handleFailedConstraint( $failed );
				return Status::wrap( $failed->getLegacyStatus() );
			}
			// END OF MIGRATION TO EDITCONSTRAINT SYSTEM (continued below)
		} else { # not $new

			# Article exists. Check for edit conflict.

			$timestamp = $this->page->getTimestamp();
			$latest = $this->page->getLatest();

			wfDebug( "timestamp: {$timestamp}, edittime: {$this->edittime}" );
			wfDebug( "revision: {$latest}, editRevId: {$this->editRevId}" );

			$editConflictLogger = LoggerFactory::getInstance( 'EditConflict' );
			// An edit conflict is detected if the current revision is different from the
			// revision that was current when editing was initiated on the client.
			// This is checked based on the timestamp and revision ID.
			// TODO: the timestamp based check can probably go away now.
			if ( ( $this->edittime !== null && $this->edittime != $timestamp )
				|| ( $this->editRevId !== null && $this->editRevId != $latest )
			) {
				$this->isConflict = true;
				if ( $this->section === 'new' ) {
					if ( $this->page->getUserText() === $requestUser->getName() &&
						$this->page->getComment() === $this->summary
					) {
						// Probably a duplicate submission of a new comment.
						// This can happen when CDN resends a request after
						// a timeout but the first one actually went through.
						$editConflictLogger->debug(
							'Duplicate new section submission; trigger edit conflict!'
						);
					} else {
						// New comment; suppress conflict.
						$this->isConflict = false;
						$editConflictLogger->debug( 'Conflict suppressed; new section' );
					}
				} elseif ( $this->section === ''
					&& $this->edittime
					&& $this->revisionStore->userWasLastToEdit(
						$this->dbProvider->getPrimaryDatabase(),
						$this->mTitle->getArticleID(),
						$requestUser->getId(),
						$this->edittime
					)
				) {
					# Suppress edit conflict with self, except for section edits where merging is required.
					$editConflictLogger->debug( 'Suppressing edit conflict, same user.' );
					$this->isConflict = false;
				}
			}

			if ( $this->isConflict ) {
				$editConflictLogger->debug(
					'Conflict! Getting section {section} for time {editTime}'
					. ' (id {editRevId}, article time {timestamp})',
					[
						'section' => $this->section,
						'editTime' => $this->edittime,
						'editRevId' => $this->editRevId,
						'timestamp' => $timestamp,
					]
				);
				// @TODO: replaceSectionAtRev() with base ID (not prior current) for ?oldid=X case
				// ...or disable section editing for non-current revisions (not exposed anyway).
				if ( $this->editRevId !== null ) {
					$content = $this->page->replaceSectionAtRev(
						$this->section,
						$textbox_content,
						$this->sectiontitle,
						$this->editRevId
					);
				} else {
					$content = $this->page->replaceSectionContent(
						$this->section,
						$textbox_content,
						$this->sectiontitle,
						$this->edittime
					);
				}
			} else {
				$editConflictLogger->debug(
					'Getting section {section}',
					[ 'section' => $this->section ]
				);
				$content = $this->page->replaceSectionAtRev(
					$this->section,
					$textbox_content,
					$this->sectiontitle
				);
			}

			if ( $content === null ) {
				$editConflictLogger->debug( 'Activating conflict; section replace failed.' );
				$this->isConflict = true;
				$content = $textbox_content; // do not try to merge here!
			} elseif ( $this->isConflict ) {
				// Attempt merge
				$mergedChange = $this->mergeChangesIntoContent( $content );
				if ( $mergedChange !== false ) {
					// Successful merge! Maybe we should tell the user the good news?
					$content = $mergedChange[0];
					$this->parentRevId = $mergedChange[1];
					$this->isConflict = false;
					$editConflictLogger->debug( 'Suppressing edit conflict, successful merge.' );
				} else {
					$this->section = '';
					$this->textbox1 = ( $content instanceof TextContent ) ? $content->getText() : '';
					$editConflictLogger->debug( 'Keeping edit conflict, failed merge.' );
				}
			}

			if ( $this->isConflict ) {
				return Status::newGood( self::AS_CONFLICT_DETECTED )->setOK( false );
			}

			$pageUpdater = $this->page->newPageUpdater( $pstUser )
				->setContent( SlotRecord::MAIN, $content );
			$pageUpdater->prepareUpdate( $flags );

			// BEGINNING OF MIGRATION TO EDITCONSTRAINT SYSTEM (see T157658)
			// Create a new runner to avoid rechecking the prior constraints, use the same factory
			$constraintRunner = new EditConstraintRunner();
			$constraintRunner->addConstraint(
				$constraintFactory->newEditFilterMergedContentHookConstraint(
					$content,
					$this->context,
					$this->summary,
					$markAsMinor,
					$this->context->getLanguage(),
					$pstUser
				)
			);
			$constraintRunner->addConstraint(
				new NewSectionMissingSubjectConstraint(
					$this->section,
					$this->sectiontitle ?? '',
					$this->allowBlankSummary
				)
			);
			$constraintRunner->addConstraint(
				new MissingCommentConstraint( $this->section, $this->textbox1 )
			);
			$constraintRunner->addConstraint(
				new ExistingSectionEditConstraint(
					$this->section,
					$this->summary,
					$this->autoSumm,
					$this->allowBlankSummary,
					$content,
					$this->getOriginalContent( $authority )
				)
			);
			// Check the constraints
			if ( !$constraintRunner->checkConstraints() ) {
				$failed = $constraintRunner->getFailedConstraint();
				$this->handleFailedConstraint( $failed );
				return Status::wrap( $failed->getLegacyStatus() );
			}
			// END OF MIGRATION TO EDITCONSTRAINT SYSTEM (continued below)

			# All's well
			$sectionAnchor = '';
			if ( $this->section === 'new' ) {
				$sectionAnchor = $this->newSectionAnchor;
			} elseif ( $this->section !== '' ) {
				# Try to get a section anchor from the section source, redirect
				# to edited section if header found.
				# XXX: Might be better to integrate this into WikiPage::replaceSectionAtRev
				# for duplicate heading checking and maybe parsing.
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->textbox1, $matches );
				# We can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this.
				if ( $hasmatch && $matches[2] !== '' ) {
					$sectionAnchor = $this->guessSectionName( $matches[2] );
				}
			}
			$result['sectionanchor'] = $sectionAnchor;

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->textbox1 = $this->toEditText( $content );
			$this->section = '';
		}

		// Check for length errors again now that the section is merged in
		$this->contentLength = strlen( $this->toEditText( $content ) );

		// Message key of the label of the submit button - used by some constraint error messages
		$submitButtonLabel = $this->getSubmitButtonLabel();

		// BEGINNING OF MIGRATION TO EDITCONSTRAINT SYSTEM (see T157658)
		// Create a new runner to avoid rechecking the prior constraints, use the same factory
		$constraintRunner = new EditConstraintRunner();
		if ( !$this->ignoreProblematicRedirects ) {
			$constraintRunner->addConstraint(
				new RedirectConstraint(
					$this->allowedProblematicRedirectTarget,
					$content,
					$this->getCurrentContent(),
					$this->getTitle(),
					$submitButtonLabel,
					$this->contentFormat,
					$this->redirectLookup
				)
			);
		}
		$constraintRunner->addConstraint(
			// Same constraint is used to check size before and after merging the
			// edits, which use different failure codes
			$constraintFactory->newPageSizeConstraint(
				$this->contentLength,
				PageSizeConstraint::AFTER_MERGE
			)
		);
		// Check the constraints
		if ( !$constraintRunner->checkConstraints() ) {
			$failed = $constraintRunner->getFailedConstraint();
			$this->handleFailedConstraint( $failed );
			return Status::wrap( $failed->getLegacyStatus() );
		}
		// END OF MIGRATION TO EDITCONSTRAINT SYSTEM

		if ( $this->undidRev && $this->isUndoClean( $content ) ) {
			// As the user can change the edit's content before saving, we only mark
			// "clean" undos as reverts. This is to avoid abuse by marking irrelevant
			// edits as undos.
			$pageUpdater
				->setOriginalRevisionId( $this->undoAfter ?: false )
				->setCause( PageUpdateCauses::CAUSE_UNDO )
				->markAsRevert(
					EditResult::REVERT_UNDO,
					$this->undidRev,
					$this->undoAfter ?: null
				);
		}

		$needsPatrol = $useRCPatrol || ( $useNPPatrol && !$this->page->exists() );
		if ( $needsPatrol && $authority->authorizeWrite( 'autopatrol', $this->getTitle() ) ) {
			$pageUpdater->setRcPatrolStatus( RecentChange::PRC_AUTOPATROLLED );
		}

		$pageUpdater
			->addTags( $this->changeTags )
			->saveRevision(
				CommentStoreComment::newUnsavedComment( trim( $this->summary ) ),
				$flags
			);
		$doEditStatus = $pageUpdater->getStatus();

		if ( !$doEditStatus->isOK() ) {
			// Failure from doEdit()
			// Show the edit conflict page for certain recognized errors from doEdit(),
			// but don't show it for errors from extension hooks
			if (
				$doEditStatus->failedBecausePageMissing() ||
				$doEditStatus->failedBecausePageExists() ||
				$doEditStatus->failedBecauseOfConflict()
			) {
				$this->isConflict = true;
				// Destroys data doEdit() put in $status->value but who cares
				// @phan-suppress-next-line PhanTypeMismatchPropertyProbablyReal
				$doEditStatus->value = self::AS_END;
			}
			return $doEditStatus;
		}

		$result['nullEdit'] = !$doEditStatus->wasRevisionCreated();
		if ( $result['nullEdit'] ) {
			// We didn't know if it was a null edit until now, so bump the rate limit now
			$limitSubject = $requestUser->toRateLimitSubject();
			MediaWikiServices::getInstance()->getRateLimiter()->limit( $limitSubject, 'linkpurge' );
		}
		$result['redirect'] = $content->isRedirect();

		$this->updateWatchlist();

		// If the content model changed, add a log entry
		if ( $changingContentModel ) {
			$this->addContentModelChangeLogEntry(
				$this->getUserForSave(),
				// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
				// $oldContentModel is set when $changingContentModel is true
				$new ? false : $oldContentModel,
				$this->contentModel,
				$this->summary
			);
		}

		// Instead of carrying the same status object throughout, it is created right
		// when it is returned, either at an earlier point due to an error or here
		// due to a successful edit.
		$statusCode = ( $new ? self::AS_SUCCESS_NEW_ARTICLE : self::AS_SUCCESS_UPDATE );
		return Status::newGood( $statusCode );
	}

	/**
	 * Apply the specific updates needed for the EditPage fields based on which constraint
	 * failed, rather than interspersing this logic throughout internalAttemptSave at
	 * each of the points the constraints are checked. Eventually, this will act on the
	 * result from the backend.
	 */
	private function handleFailedConstraint( IEditConstraint $failed ): void {
		if ( $failed instanceof AuthorizationConstraint ) {
			// Auto-block user's IP if the account was "hard" blocked
			if (
				!MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly()
				&& $failed->getLegacyStatus()->value === self::AS_BLOCKED_PAGE_FOR_USER
			) {
				$this->context->getUser()->spreadAnyEditBlock();
			}
		} elseif ( $failed instanceof DefaultTextConstraint ) {
			$this->blankArticle = true;
		} elseif ( $failed instanceof EditFilterMergedContentHookConstraint ) {
			$this->hookError = $failed->getHookError();
		} elseif (
			// ExistingSectionEditConstraint also checks for revisions deleted
			// since the edit was loaded, which doesn't indicate a missing summary
			(
				$failed instanceof ExistingSectionEditConstraint
				&& $failed->getLegacyStatus()->value === self::AS_SUMMARY_NEEDED
			) ||
			$failed instanceof NewSectionMissingSubjectConstraint
		) {
			$this->missingSummary = true;
		} elseif ( $failed instanceof MissingCommentConstraint ) {
			$this->missingComment = true;
		} elseif ( $failed instanceof RedirectConstraint ) {
			$this->problematicRedirectTarget = $failed->problematicTarget;
		}
	}

	/**
	 * Does checks and compares the automatically generated undo content with the
	 * one that was submitted by the user. If they match, the undo is considered "clean".
	 * Otherwise there is no guarantee if anything was reverted at all, as the user could
	 * even swap out entire content.
	 *
	 * @param Content $content
	 *
	 * @return bool
	 */
	private function isUndoClean( Content $content ): bool {
		// Check whether the undo was "clean", that is the user has not modified
		// the automatically generated content.
		$undoRev = $this->revisionStore->getRevisionById( $this->undidRev );
		if ( $undoRev === null ) {
			return false;
		}

		if ( $this->undoAfter ) {
			$oldRev = $this->revisionStore->getRevisionById( $this->undoAfter );
		} else {
			$oldRev = $this->revisionStore->getPreviousRevision( $undoRev );
		}

		if ( $oldRev === null ||
			$undoRev->isDeleted( RevisionRecord::DELETED_TEXT ) ||
			$oldRev->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			return false;
		}

		$undoContent = $this->getUndoContent( $undoRev, $oldRev, $undoError );
		if ( !$undoContent ) {
			return false;
		}

		// Do a pre-save transform on the retrieved undo content
		$services = MediaWikiServices::getInstance();
		$contentLanguage = $services->getContentLanguage();
		$user = $this->getUserForPreview();
		$parserOptions = ParserOptions::newFromUserAndLang( $user, $contentLanguage );
		$contentTransformer = $services->getContentTransformer();
		$undoContent = $contentTransformer->preSaveTransform( $undoContent, $this->mTitle, $user, $parserOptions );

		if ( $undoContent->equals( $content ) ) {
			return true;
		}
		return false;
	}

	/**
	 * @param UserIdentity $user
	 * @param string|false $oldModel false if the page is being newly created
	 * @param string $newModel
	 * @param string $reason
	 */
	private function addContentModelChangeLogEntry( UserIdentity $user, $oldModel, $newModel, $reason = "" ): void {
		$new = $oldModel === false;
		$log = new ManualLogEntry( 'contentmodel', $new ? 'new' : 'change' );
		$log->setPerformer( $user );
		$log->setTarget( $this->mTitle );
		$log->setComment( is_string( $reason ) ? $reason : "" );
		$log->setParameters( [
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $newModel
		] );
		$logid = $log->insert();
		$log->publish( $logid );
	}

	/**
	 * Register the change of watch status
	 */
	private function updateWatchlist(): void {
		if ( $this->tempUserCreateActive ) {
			return;
		}
		$user = $this->getUserForSave();
		if ( !$user->isNamed() ) {
			return;
		}

		$title = $this->mTitle;
		$watch = $this->watchthis;
		$watchlistExpiry = $this->watchlistExpiry;

		// This can't run as a DeferredUpdate due to a possible race condition
		// when the post-edit redirect happens if the pendingUpdates queue is
		// too large to finish in time (T259564)
		$this->watchlistManager->setWatch( $watch, $user, $title, $watchlistExpiry );

		$this->watchedItemStore->maybeEnqueueWatchlistExpiryJob();
	}

	/**
	 * Attempts to do 3-way merge of edit content with a base revision
	 * and current content, in case of edit conflict, in whichever way appropriate
	 * for the content type.
	 *
	 * @param Content $editContent
	 *
	 * @return array|false either `false` or an array of the new Content and the
	 *   updated parent revision id
	 */
	private function mergeChangesIntoContent( Content $editContent ) {
		// This is the revision that was current at the time editing was initiated on the client,
		// even if the edit was based on an old revision.
		$baseRevRecord = $this->getExpectedParentRevision();
		$baseContent = $baseRevRecord ?
			$baseRevRecord->getContent( SlotRecord::MAIN ) :
			null;

		if ( $baseContent === null ) {
			return false;
		} elseif ( $baseRevRecord->isCurrent() ) {
			// Impossible to have a conflict when the user just edited the latest revision. This can
			// happen e.g. when $wgDiff3 is badly configured.
			return [ $editContent, $baseRevRecord->getId() ];
		}

		// The current state, we want to merge updates into it
		$currentRevisionRecord = $this->revisionStore->getRevisionByTitle(
			$this->mTitle,
			0,
			IDBAccessObject::READ_LATEST
		);
		$currentContent = $currentRevisionRecord
			? $currentRevisionRecord->getContent( SlotRecord::MAIN )
			: null;

		if ( $currentContent === null ) {
			return false;
		}

		$mergedContent = $this->contentHandlerFactory
			->getContentHandler( $baseContent->getModel() )
			->merge3( $baseContent, $editContent, $currentContent );

		if ( $mergedContent ) {
			// Also need to update parentRevId to what we just merged.
			return [ $mergedContent, $currentRevisionRecord->getId() ];
		}

		return false;
	}

	/**
	 * Returns the RevisionRecord corresponding to the revision that was current at the time
	 * editing was initiated on the client even if the edit was based on an old revision
	 *
	 * @since 1.35
	 * @return RevisionRecord|null Current revision when editing was initiated on the client
	 */
	public function getExpectedParentRevision() {
		if ( $this->mExpectedParentRevision === false ) {
			$revRecord = null;
			if ( $this->editRevId ) {
				$revRecord = $this->revisionStore->getRevisionById(
					$this->editRevId,
					IDBAccessObject::READ_LATEST
				);
			} elseif ( $this->edittime ) {
				$revRecord = $this->revisionStore->getRevisionByTimestamp(
					$this->getTitle(),
					$this->edittime,
					IDBAccessObject::READ_LATEST
				);
			}
			$this->mExpectedParentRevision = $revRecord;
		}
		return $this->mExpectedParentRevision;
	}

	public function setHeaders() {
		$out = $this->context->getOutput();

		$out->addModules( 'mediawiki.action.edit' );
		$out->addModuleStyles( [
			'mediawiki.action.edit.styles',
			'mediawiki.codex.messagebox.styles',
			'mediawiki.editfont.styles',
			'mediawiki.interface.helpers.styles',
		] );

		$user = $this->context->getUser();

		if ( $this->userOptionsLookup->getOption( $user, 'uselivepreview' ) ) {
			$out->addModules( 'mediawiki.action.edit.preview' );
		}

		if ( $this->userOptionsLookup->getOption( $user, 'useeditwarning' ) ) {
			$out->addModules( 'mediawiki.action.edit.editWarning' );
		}

		if ( $this->context->getConfig()->get( MainConfigNames::EnableEditRecovery )
			&& $this->userOptionsLookup->getOption( $user, 'editrecovery' )
		) {
			$wasPosted = $this->getContext()->getRequest()->getMethod() === 'POST';
			$out->addJsConfigVars( 'wgEditRecoveryWasPosted', $wasPosted );
			$out->addModules( 'mediawiki.editRecovery.edit' );
		}

		# Enabled article-related sidebar, toplinks, etc.
		$out->setArticleRelated( true );

		$contextTitle = $this->getContextTitle();
		if ( $this->isConflict ) {
			$msg = 'editconflict';
		} elseif ( $contextTitle->exists() && $this->section != '' ) {
			$msg = $this->section === 'new' ? 'editingcomment' : 'editingsection';
		} else {
			$msg = $contextTitle->exists()
				|| ( $contextTitle->getNamespace() === NS_MEDIAWIKI
					&& $contextTitle->getDefaultMessageText() !== false
				)
				? 'editing'
				: 'creating';
		}

		# Use the title defined by DISPLAYTITLE magic word when present
		# NOTE: getDisplayTitle() returns HTML while getPrefixedText() returns plain text.
		#       Escape ::getPrefixedText() so that we have HTML in all cases,
		#       and pass as a "raw" parameter to ::setPageTitleMsg().
		$displayTitle = $this->mParserOutput ? $this->mParserOutput->getDisplayTitle() : false;
		if ( $displayTitle === false ) {
			$displayTitle = htmlspecialchars(
				$contextTitle->getPrefixedText(), ENT_QUOTES, 'UTF-8', false
			);
		} else {
			$out->setDisplayTitle( $displayTitle );
		}

		// Enclose the title with an element. This is used on live preview to update the
		// preview of the display title.
		$displayTitle = Html::rawElement( 'span', [ 'id' => 'firstHeadingTitle' ], $displayTitle );

		$out->setPageTitleMsg( $this->context->msg( $msg )->rawParams( $displayTitle ) );

		$config = $this->context->getConfig();

		# Transmit the name of the message to JavaScript. This was added for live preview.
		# Live preview doesn't use this anymore. The variable is still transmitted because
		# Edit Recovery and user scripts use it.
		$out->addJsConfigVars( [
			'wgEditMessage' => $msg,
		] );

		// Add whether to use 'save' or 'publish' messages to JavaScript for post-edit, other
		// editors, etc.
		$out->addJsConfigVars(
			'wgEditSubmitButtonLabelPublish',
			$config->get( MainConfigNames::EditSubmitButtonLabelPublish )
		);
	}

	/**
	 * Show all applicable editing introductions
	 */
	private function showIntro(): void {
		$services = MediaWikiServices::getInstance();

		// Hardcoded list of notices that are suppressable for historical reasons.
		// This feature was originally added for LiquidThreads, to avoid showing non-essential messages
		// when commenting in a thread, but some messages were included (or excluded) by mistake before
		// its implementation was moved to one place, and this list doesn't make a lot of sense.
		// TODO: Remove the suppressIntro feature from EditPage, and invent a better way for extensions
		// to skip individual intro messages.
		$skip = $this->suppressIntro ? [
			'editintro',
			'code-editing-intro',
			'sharedupload-desc-create',
			'sharedupload-desc-edit',
			'userpage-userdoesnotexist',
			'blocked-notice-logextract',
			'newarticletext',
			'newarticletextanon',
			'recreate-moveddeleted-warn',
		] : [];

		$messages = $services->getIntroMessageBuilder()->getIntroMessages(
			IntroMessageBuilder::MORE_FRAMES,
			$skip,
			$this->context,
			$this->mTitle->toPageIdentity(),
			$this->mArticle->fetchRevisionRecord(),
			$this->context->getUser(),
			$this->context->getRequest()->getVal( 'editintro' ),
			wfArrayToCgi(
				array_diff_key(
					$this->context->getRequest()->getQueryValues(),
					[ 'title' => true, 'returnto' => true, 'returntoquery' => true ]
				)
			),
			!$this->firsttime,
			$this->section !== '' ? $this->section : null
		);

		foreach ( $messages as $message ) {
			$this->context->getOutput()->addHTML( $message );
		}
	}

	/**
	 * Gets an editable textual representation of $content.
	 * The textual representation can be turned by into a Content object by the
	 * toEditContent() method.
	 *
	 * If $content is null or false or a string, $content is returned unchanged.
	 *
	 * If the given Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param Content|null|false|string $content
	 * @return string The editable text form of the content.
	 *
	 * @throws MWException If $content is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	private function toEditText( $content ) {
		if ( $content === null || $content === false ) {
			return '';
		}
		if ( is_string( $content ) ) {
			return $content;
		}

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content->serialize( $this->contentFormat );
	}

	/**
	 * Turns the given text into a Content object by unserializing it.
	 *
	 * If the resulting Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param string|null|false $text Text to unserialize
	 * @return Content|false|null The content object created from $text. If $text was false
	 *   or null, then false or null will be returned instead.
	 *
	 * @throws MWException If unserializing the text results in a Content
	 *   object that is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	protected function toEditContent( $text ) {
		if ( $text === false || $text === null ) {
			return $text;
		}

		$content = ContentHandler::makeContent( $text, $this->getTitle(),
			$this->contentModel, $this->contentFormat );

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content;
	}

	/**
	 * Send the edit form and related headers to OutputPage
	 */
	public function showEditForm() {
		# need to parse the preview early so that we know which templates are used,
		# otherwise users with "show preview after edit box" will get a blank list
		# we parse this near the beginning so that setHeaders can do the title
		# setting work instead of leaving it in getPreviewText
		$previewOutput = '';
		if ( $this->formtype === 'preview' ) {
			$previewOutput = $this->getPreviewText();
		}

		$out = $this->context->getOutput();

		// FlaggedRevs depends on running this hook before adding edit notices in showIntro() (T337637)
		$this->getHookRunner()->onEditPage__showEditForm_initial( $this, $out );

		$this->setHeaders();

		// Show applicable editing introductions
		$this->showIntro();

		if ( !$this->isConflict &&
			$this->section !== '' &&
			!$this->isSectionEditSupported()
		) {
			// We use $this->section to much before this and getVal('wgSection') directly in other places
			// at this point we can't reset $this->section to '' to fallback to non-section editing.
			// Someone is welcome to try refactoring though
			$out->showErrorPage( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
			return;
		}

		$this->showHeader();

		$out->addHTML( $this->editFormPageTop );

		$user = $this->context->getUser();
		if ( $this->userOptionsLookup->getOption( $user, 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, true );
		}

		$out->addHTML( $this->editFormTextTop );

		if ( $this->formtype !== 'save' && $this->wasDeletedSinceLastEdit() ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'deletedwhileediting' )->parse(),
				'',
				'mw-deleted-while-editing'
			) );
		}

		// @todo add EditForm plugin interface and use it here!
		//       search for textarea1 and textarea2, and allow EditForm to override all uses.
		$out->addHTML( Html::openElement(
			'form',
			[
				'class' => 'mw-editform',
				'id' => self::EDITFORM_ID,
				'name' => self::EDITFORM_ID,
				'method' => 'post',
				'action' => $this->getActionURL( $this->getContextTitle() ),
				'enctype' => 'multipart/form-data',
				'data-mw-editform-type' => $this->formtype
			]
		) );

		// Add a check for Unicode support
		$out->addHTML( Html::hidden( 'wpUnicodeCheck', self::UNICODE_CHECK ) );

		// Add an empty field to trip up spambots
		$out->addHTML(
			Html::openElement( 'div', [ 'id' => 'antispam-container', 'style' => 'display: none;' ] )
			. Html::rawElement(
				'label',
				[ 'for' => 'wpAntispam' ],
				$this->context->msg( 'simpleantispam-label' )->parse()
			)
			. Html::element(
				'input',
				[
					'type' => 'text',
					'name' => 'wpAntispam',
					'id' => 'wpAntispam',
					'value' => ''
				]
			)
			. Html::closeElement( 'div' )
		);

		$this->getHookRunner()->onEditPage__showEditForm_fields( $this, $out );

		// Put these up at the top to ensure they aren't lost on early form submission
		$this->showFormBeforeText();

		if ( $this->formtype === 'save' && $this->wasDeletedSinceLastEdit() ) {
			$username = $this->lastDelete->actor_name;
			$comment = $this->commentStore->getComment( 'log_comment', $this->lastDelete )->text;

			// It is better to not parse the comment at all than to have templates expanded in the middle
			// TODO: can the label be moved outside of the div so that wrapWikiMsg could be used?
			$key = $comment === ''
				? 'confirmrecreate-noreason'
				: 'confirmrecreate';
			$out->addHTML( Html::rawElement(
				'div',
				[ 'class' => 'mw-confirm-recreate' ],
				$this->context->msg( $key )
					->params( $username )
					->plaintextParams( $comment )
					->parse() .
					Html::rawElement(
						'div',
						[],
						Html::check(
							'wpRecreate',
							false,
							[ 'title' => Linker::titleAttrib( 'recreate' ), 'tabindex' => 1, 'id' => 'wpRecreate' ]
						)
						. "\u{00A0}" .
						Html::label(
							$this->context->msg( 'recreate' )->text(),
							'wpRecreate',
							[ 'title' => Linker::titleAttrib( 'recreate' ) ]
						)
					)
			) );
		}

		# When the summary is hidden, also hide them on preview/show changes
		if ( $this->nosummary ) {
			$out->addHTML( Html::hidden( 'nosummary', true ) );
		}

		# If a blank edit summary was previously provided, and the appropriate
		# user preference is active, pass a hidden tag as wpIgnoreBlankSummary. This will stop the
		# user being bounced back more than once in the event that a summary
		# is not required.
		# ####
		# For a bit more sophisticated detection of blank summaries, hash the
		# automatic one and pass that in the hidden field wpAutoSummary.
		if (
			$this->missingSummary ||
			// @phan-suppress-next-line PhanSuspiciousValueComparison
			( $this->section === 'new' && $this->nosummary ) ||
			$this->allowBlankSummary
		) {
			$out->addHTML( Html::hidden( 'wpIgnoreBlankSummary', true ) );
		}

		if ( $this->undidRev ) {
			$out->addHTML( Html::hidden( 'wpUndidRevision', $this->undidRev ) );
		}
		if ( $this->undoAfter ) {
			$out->addHTML( Html::hidden( 'wpUndoAfter', $this->undoAfter ) );
		}

		if ( $this->problematicRedirectTarget !== null ) {
			// T395767, T395768: Save the target to a variable so the constraint can fail again if the redirect is
			// still problematic but has changed between two save attempts
			$out->addHTML( Html::hidden(
				'wpAllowedProblematicRedirectTarget',
				$this->problematicRedirectTarget->getFullText()
			) );
		}

		$autosumm = $this->autoSumm !== '' ? $this->autoSumm : md5( $this->summary );
		$out->addHTML( Html::hidden( 'wpAutoSummary', $autosumm ) );

		$out->addHTML( Html::hidden( 'oldid', $this->oldid ) );
		$out->addHTML( Html::hidden( 'parentRevId', $this->getParentRevId() ) );

		$out->addHTML( Html::hidden( 'format', $this->contentFormat ) );
		$out->addHTML( Html::hidden( 'model', $this->contentModel ) );
		if ( $this->changeTags ) {
			$out->addHTML( Html::hidden( 'wpChangeTagsAfterPreview', implode( ',', $this->changeTags ) ) );
		}

		$out->enableOOUI();

		if ( $this->section === 'new' ) {
			$this->showSummaryInput( true );
			$out->addHTML( $this->getSummaryPreview( true ) );
		}

		$out->addHTML( $this->editFormTextBeforeContent );
		if ( $this->isConflict ) {
			$currentText = $this->toEditText( $this->getCurrentContent() );

			$editConflictHelper = $this->getEditConflictHelper();
			$editConflictHelper->setTextboxes( $this->textbox1, $currentText );
			$editConflictHelper->setContentModel( $this->contentModel );
			$editConflictHelper->setContentFormat( $this->contentFormat );
			$out->addHTML( $editConflictHelper->getEditFormHtmlBeforeContent() );

			$this->textbox2 = $this->textbox1;
			$this->textbox1 = $currentText;
		}

		if ( !$this->mTitle->isUserConfigPage() ) {
			$out->addHTML( self::getEditToolbar() );
		}

		if ( $this->blankArticle ) {
			$out->addHTML( Html::hidden( 'wpIgnoreBlankArticle', true ) );
		}

		if ( $this->isConflict ) {
			// In an edit conflict bypass the overridable content form method
			// and fallback to the raw wpTextbox1 since editconflicts can't be
			// resolved between page source edits and custom ui edits using the
			// custom edit ui.
			$conflictTextBoxAttribs = [];
			if ( $this->wasDeletedSinceLastEdit() ) {
				$conflictTextBoxAttribs['style'] = 'display:none;';
			} elseif ( $this->isOldRev ) {
				$conflictTextBoxAttribs['class'] = 'mw-textarea-oldrev';
			}

			// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
			// $editConflictHelper is declard, when isConflict is true
			$out->addHTML( $editConflictHelper->getEditConflictMainTextBox( $conflictTextBoxAttribs ) );
			// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable
			// $editConflictHelper is declard, when isConflict is true
			$out->addHTML( $editConflictHelper->getEditFormHtmlAfterContent() );
		} else {
			$this->showContentForm();
		}

		$out->addHTML( $this->editFormTextAfterContent );

		$this->showStandardInputs();

		$this->showFormAfterText();

		$this->showTosSummary();

		$this->showEditTools();

		$out->addHTML( $this->editFormTextAfterTools . "\n" );

		$out->addHTML( $this->makeTemplatesOnThisPageList( $this->getTemplates() ) );

		$out->addHTML( Html::rawElement( 'div', [ 'class' => 'hiddencats' ],
			Linker::formatHiddenCategories( $this->page->getHiddenCategories() ) ) );

		$out->addHTML( Html::rawElement( 'div', [ 'class' => 'limitreport' ],
			self::getPreviewLimitReport( $this->mParserOutput ) ) );

		$out->addModules( 'mediawiki.action.edit.collapsibleFooter' );

		if ( $this->isConflict ) {
			try {
				$this->showConflict();
			} catch ( MWContentSerializationException $ex ) {
				// this can't really happen, but be nice if it does.
				$out->addHTML( Html::errorBox(
					$this->context->msg(
						'content-failed-to-parse',
						$this->contentModel,
						$this->contentFormat,
						$ex->getMessage()
					)->parse()
				) );
			}
		}

		// Set a hidden field so JS knows what edit form mode we are in
		if ( $this->isConflict ) {
			$mode = 'conflict';
		} elseif ( $this->preview ) {
			$mode = 'preview';
		} elseif ( $this->diff ) {
			$mode = 'diff';
		} else {
			$mode = 'text';
		}
		$out->addHTML( Html::hidden( 'mode', $mode, [ 'id' => 'mw-edit-mode' ] ) );

		// Marker for detecting truncated form data.  This must be the last
		// parameter sent in order to be of use, so do not move me.
		$out->addHTML( Html::hidden( 'wpUltimateParam', true ) );
		$out->addHTML( $this->editFormTextBottom . "\n</form>\n" );

		if ( !$this->userOptionsLookup->getOption( $user, 'previewontop' ) ) {
			$this->displayPreviewArea( $previewOutput, false );
		}
	}

	/**
	 * Wrapper around TemplatesOnThisPageFormatter to make
	 * a "templates on this page" list.
	 *
	 * @param PageIdentity[] $templates
	 * @return string HTML
	 */
	public function makeTemplatesOnThisPageList( array $templates ) {
		$templateListFormatter = new TemplatesOnThisPageFormatter(
			$this->context,
			$this->linkRenderer,
			$this->linkBatchFactory,
			$this->restrictionStore
		);

		// preview if preview, else section if section, else false
		$type = false;
		if ( $this->preview ) {
			$type = 'preview';
		} elseif ( $this->section !== '' ) {
			$type = 'section';
		}

		return Html::rawElement( 'div', [ 'class' => 'templatesUsed' ],
			$templateListFormatter->format( $templates, $type )
		);
	}

	/**
	 * Extract the section title from current section text, if any.
	 *
	 * @param string $text
	 * @return string|false
	 */
	private static function extractSectionTitle( $text ) {
		if ( preg_match( "/^(=+)(.+)\\1\\s*(\n|$)/i", $text, $matches ) ) {
			return MediaWikiServices::getInstance()->getParser()
				->stripSectionName( trim( $matches[2] ) );
		} else {
			return false;
		}
	}

	private function showHeader(): void {
		$out = $this->context->getOutput();
		$user = $this->context->getUser();
		if ( $this->isConflict ) {
			$this->addExplainConflictHeader();
			$this->editRevId = $this->page->getLatest();
		} else {
			if ( $this->section !== '' && $this->section !== 'new' && $this->summary === '' &&
				!$this->preview && !$this->diff
			) {
				$sectionTitle = self::extractSectionTitle( $this->textbox1 ); // FIXME: use Content object
				if ( $sectionTitle !== false ) {
					$this->summary = "/* $sectionTitle */ ";
				}
			}

			$buttonLabel = $this->context->msg( $this->getSubmitButtonLabel() )->text();

			if ( $this->missingComment ) {
				$out->wrapWikiMsg( "<div id='mw-missingcommenttext'>\n$1\n</div>", 'missingcommenttext' );
			}

			if ( $this->missingSummary && $this->section !== 'new' ) {
				$out->wrapWikiMsg(
					"<div id='mw-missingsummary'>\n$1\n</div>",
					[ 'missingsummary', $buttonLabel ]
				);
			}

			if ( $this->missingSummary && $this->section === 'new' ) {
				$out->wrapWikiMsg(
					"<div id='mw-missingcommentheader'>\n$1\n</div>",
					[ 'missingcommentheader', $buttonLabel ]
				);
			}

			if ( $this->blankArticle ) {
				$out->wrapWikiMsg(
					"<div id='mw-blankarticle'>\n$1\n</div>",
					[ 'blankarticle', $buttonLabel ]
				);
			}

			if ( $this->hookError !== '' ) {
				$out->addWikiTextAsInterface( $this->hookError );
			}

			if ( $this->section != 'new' ) {
				$revRecord = $this->mArticle->fetchRevisionRecord();
				if ( $revRecord && $revRecord instanceof RevisionStoreRecord ) {
					// Let sysop know that this will make private content public if saved

					if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $user ) ) {
						$out->addHTML(
							Html::warningBox(
								$out->msg( 'rev-deleted-text-permission', $this->mTitle->getPrefixedDBkey() )->parse(),
								'plainlinks'
							)
						);
					} elseif ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
						$out->addHTML(
							Html::warningBox(
								// title used in wikilinks, should not contain whitespaces
								$out->msg( 'rev-deleted-text-view', $this->mTitle->getPrefixedDBkey() )->parse(),
								'plainlinks'
							)
						);
					}

					if ( !$revRecord->isCurrent() ) {
						$this->mArticle->setOldSubtitle( $revRecord->getId() );
						$this->isOldRev = true;
					}
				} elseif ( $this->mTitle->exists() ) {
					// Something went wrong

					$out->addHTML(
						Html::errorBox(
							$out->msg( 'missing-revision', $this->oldid )->parse()
						)
					);
				}
			}
		}

		$this->addLongPageWarningHeader();
	}

	/**
	 * Helper function for summary input functions, which returns the necessary
	 * attributes for the input.
	 *
	 * @param array $inputAttrs Array of attrs to use on the input
	 * @return array
	 */
	private function getSummaryInputAttributes( array $inputAttrs ): array {
		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		return $inputAttrs + [
			'id' => 'wpSummary',
			'name' => 'wpSummary',
			'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'tabindex' => 1,
			'size' => 60,
			'spellcheck' => 'true',
		];
	}

	/**
	 * Builds a standard summary input with a label.
	 *
	 * @param string $summary The value of the summary input
	 * @param string $labelText The html to place inside the label
	 * @param array $inputAttrs Array of attrs to use on the input
	 *
	 * @return OOUI\FieldLayout OOUI FieldLayout with Label and Input
	 */
	private function getSummaryInputWidget( $summary, string $labelText, array $inputAttrs ): FieldLayout {
		$inputAttrs = OOUI\Element::configFromHtmlAttributes(
			$this->getSummaryInputAttributes( $inputAttrs )
		);
		$inputAttrs += [
			'title' => Linker::titleAttrib( 'summary' ),
			'accessKey' => Linker::accesskey( 'summary' ),
		];

		// For compatibility with old scripts and extensions, we want the legacy 'id' on the `<input>`
		$inputAttrs['inputId'] = $inputAttrs['id'];
		$inputAttrs['id'] = 'wpSummaryWidget';

		return new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'value' => $summary,
				'infusable' => true,
			] + $inputAttrs ),
			[
				'label' => new OOUI\HtmlSnippet( $labelText ),
				'align' => 'top',
				'id' => 'wpSummaryLabel',
				'classes' => [ $this->missingSummary ? 'mw-summarymissed' : 'mw-summary' ],
			]
		);
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 */
	private function showSummaryInput( bool $isSubjectPreview ): void {
		# Add a class if 'missingsummary' is triggered to allow styling of the summary line
		$summaryClass = $this->missingSummary ? 'mw-summarymissed' : 'mw-summary';
		if ( $isSubjectPreview ) {
			if ( $this->nosummary ) {
				return;
			}
		} elseif ( !$this->mShowSummaryField ) {
			return;
		}

		$labelText = $this->context->msg( $isSubjectPreview ? 'subject' : 'summary' )->parse();
		$this->context->getOutput()->addHTML(
			$this->getSummaryInputWidget(
				$isSubjectPreview ? $this->sectiontitle : $this->summary,
				$labelText,
				[ 'class' => $summaryClass ]
			)
		);
	}

	/**
	 * @param bool $isSubjectPreview True if this is the section subject/title
	 *   up top, or false if this is the comment summary
	 *   down below the textarea
	 * @return string
	 */
	private function getSummaryPreview( bool $isSubjectPreview ): string {
		// avoid spaces in preview, gets always trimmed on save
		$summary = trim( $this->summary );
		if ( $summary === '' || ( !$this->preview && !$this->diff ) ) {
			return "";
		}

		$commentFormatter = MediaWikiServices::getInstance()->getCommentFormatter();
		$summary = $this->context->msg( 'summary-preview' )->parse()
			. $commentFormatter->formatBlock( $summary, $this->mTitle, $isSubjectPreview );
		return Html::rawElement( 'div', [ 'class' => 'mw-summary-preview' ], $summary );
	}

	private function showFormBeforeText(): void {
		$out = $this->context->getOutput();
		$out->addHTML( Html::hidden( 'wpSection', $this->section ) );
		$out->addHTML( Html::hidden( 'wpStarttime', $this->starttime ) );
		$out->addHTML( Html::hidden( 'wpEdittime', $this->edittime ) );
		$out->addHTML( Html::hidden( 'editRevId', $this->editRevId ) );
		$out->addHTML( Html::hidden( 'wpScrolltop', $this->scrolltop, [ 'id' => 'wpScrolltop' ] ) );
	}

	protected function showFormAfterText() {
		/**
		 * To make it harder for someone to slip a user a page
		 * which submits an edit form to the wiki without their
		 * knowledge, a random token is associated with the login
		 * session. If it's not passed back with the submission,
		 * we won't save the page, or render user JavaScript and
		 * CSS previews.
		 *
		 * For anon editors, who may not have a session, we just
		 * include the constant suffix to prevent editing from
		 * broken text-mangling proxies.
		 */
		$this->context->getOutput()->addHTML(
			"\n" .
			Html::hidden( "wpEditToken", $this->context->getUser()->getEditToken() ) .
			"\n"
		);
	}

	/**
	 * Subpage overridable method for printing the form for page content editing
	 * By default this simply outputs wpTextbox1
	 * Subclasses can override this to provide a custom UI for editing;
	 * be it a form, or simply wpTextbox1 with a modified content that will be
	 * reverse modified when extracted from the post data.
	 * Note that this is basically the inverse for importContentFormData
	 */
	protected function showContentForm() {
		$this->showTextbox1();
	}

	private function showTextbox1(): void {
		if ( $this->formtype === 'save' && $this->wasDeletedSinceLastEdit() ) {
			$attribs = [ 'style' => 'display:none;' ];
		} else {
			$builder = new TextboxBuilder();
			$classes = $builder->getTextboxProtectionCSSClasses( $this->getTitle() );

			# Is an old revision being edited?
			if ( $this->isOldRev ) {
				$classes[] = 'mw-textarea-oldrev';
			}

			$attribs = [
				'aria-label' => $this->context->msg( 'edit-textarea-aria-label' )->text(),
				'tabindex' => 1,
				'class' => $classes,
			];
		}

		$this->showTextbox(
			$this->textbox1,
			'wpTextbox1',
			$attribs
		);
	}

	protected function showTextbox( string $text, string $name, array $customAttribs = [] ) {
		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			$name,
			$customAttribs,
			$this->context->getUser(),
			$this->mTitle
		);

		$this->context->getOutput()->addHTML(
			Html::textarea( $name, $builder->addNewLineAtEnd( $text ), $attribs )
		);
	}

	private function displayPreviewArea( string $previewOutput, bool $isOnTop ): void {
		$attribs = [ 'id' => 'wikiPreview' ];
		if ( $isOnTop ) {
			$attribs['class'] = 'ontop';
		}
		if ( $this->formtype !== 'preview' ) {
			$attribs['style'] = 'display: none;';
		}

		$out = $this->context->getOutput();
		$out->addHTML( Html::openElement( 'div', $attribs ) );

		if ( $this->formtype === 'preview' ) {
			$this->showPreview( $previewOutput );
		}

		$out->addHTML( '</div>' );

		if ( $this->formtype === 'diff' ) {
			try {
				$this->showDiff();
			} catch ( MWContentSerializationException $ex ) {
				$out->addHTML( Html::errorBox(
					$this->context->msg(
						'content-failed-to-parse',
						$this->contentModel,
						$this->contentFormat,
						$ex->getMessage()
					)->parse()
				) );
			}
		}
	}

	/**
	 * Append preview output to OutputPage.
	 * Includes category rendering if this is a category page.
	 *
	 * @param string $text The HTML to be output for the preview.
	 */
	private function showPreview( string $text ): void {
		if ( $this->mArticle instanceof CategoryPage ) {
			$this->mArticle->openShowCategory();
		}
		# This hook seems slightly odd here, but makes things more
		# consistent for extensions.
		$out = $this->context->getOutput();
		$this->getHookRunner()->onOutputPageBeforeHTML( $out, $text );
		$out->addHTML( $text );
		if ( $this->mArticle instanceof CategoryPage ) {
			$this->mArticle->closeShowCategory();
		}
	}

	/**
	 * Get a diff between the current contents of the edit box and the
	 * version of the page we're editing from.
	 *
	 * If this is a section edit, we'll replace the section as for final
	 * save and then make a comparison.
	 */
	public function showDiff() {
		$oldtitlemsg = 'currentrev';
		# if message does not exist, show diff against the preloaded default
		if ( $this->mTitle->getNamespace() === NS_MEDIAWIKI && !$this->mTitle->exists() ) {
			$oldtext = $this->mTitle->getDefaultMessageText();
			if ( $oldtext !== false ) {
				$oldtitlemsg = 'defaultmessagetext';
				$oldContent = $this->toEditContent( $oldtext );
			} else {
				$oldContent = null;
			}
		} else {
			$oldContent = $this->getCurrentContent();
		}

		$textboxContent = $this->toEditContent( $this->textbox1 );
		if ( $this->editRevId !== null ) {
			$newContent = $this->page->replaceSectionAtRev(
				$this->section, $textboxContent, $this->sectiontitle, $this->editRevId
			);
		} else {
			$newContent = $this->page->replaceSectionContent(
				$this->section, $textboxContent, $this->sectiontitle, $this->edittime
			);
		}

		if ( $newContent ) {
			$this->getHookRunner()->onEditPageGetDiffContent( $this, $newContent );

			$user = $this->getUserForPreview();
			$parserOptions = ParserOptions::newFromUserAndLang( $user,
				MediaWikiServices::getInstance()->getContentLanguage() );
			$services = MediaWikiServices::getInstance();
			$contentTransformer = $services->getContentTransformer();
			$newContent = $contentTransformer->preSaveTransform( $newContent, $this->mTitle, $user, $parserOptions );
		}

		if ( ( $oldContent && !$oldContent->isEmpty() ) || ( $newContent && !$newContent->isEmpty() ) ) {
			$oldtitle = $this->context->msg( $oldtitlemsg )->parse();
			$newtitle = $this->context->msg( 'yourtext' )->parse();

			if ( !$oldContent ) {
				$oldContent = $newContent->getContentHandler()->makeEmptyContent();
			}

			if ( !$newContent ) {
				$newContent = $oldContent->getContentHandler()->makeEmptyContent();
			}

			$de = $oldContent->getContentHandler()->createDifferenceEngine( $this->context );
			$de->setContent( $oldContent, $newContent );

			$difftext = $de->getDiff( $oldtitle, $newtitle );
			$de->showDiffStyle();
		} else {
			$difftext = '';
		}

		$this->context->getOutput()->addHTML( Html::rawElement( 'div', [ 'id' => 'wikiDiff' ], $difftext ) );
	}

	/**
	 * Give a chance for site and per-namespace customizations of
	 * terms of service summary link that might exist separately
	 * from the copyright notice.
	 *
	 * This will display between the save button and the edit tools,
	 * so should remain short!
	 */
	private function showTosSummary(): void {
		$msgKey = 'editpage-tos-summary';
		$this->getHookRunner()->onEditPageTosSummary( $this->mTitle, $msgKey );
		$msg = $this->context->msg( $msgKey );
		if ( !$msg->isDisabled() ) {
			$this->context->getOutput()->addHTML( Html::rawElement(
				'div',
				[ 'class' => 'mw-tos-summary' ],
				$msg->parseAsBlock()
			) );
		}
	}

	/**
	 * Inserts optional text shown below edit and upload forms. Can be used to offer special
	 * characters not present on most keyboards for copying/pasting.
	 */
	private function showEditTools(): void {
		$this->context->getOutput()->addHTML( Html::rawElement(
			'div',
			[ 'class' => 'mw-editTools' ],
			$this->context->msg( 'edittools' )->inContentLanguage()->parse()
		) );
	}

	/**
	 * Get the copyright warning.
	 *
	 * @param PageReference $page
	 * @param string $format Output format, valid values are any function of a Message object
	 *   (e.g. 'parse', 'plain')
	 * @param MessageLocalizer $localizer
	 * @return string
	 */
	public static function getCopyrightWarning( PageReference $page, string $format, MessageLocalizer $localizer ) {
		$services = MediaWikiServices::getInstance();
		$rightsText = $services->getMainConfig()->get( MainConfigNames::RightsText );
		if ( $rightsText ) {
			$copywarnMsg = [ 'copyrightwarning',
				'[[' . $localizer->msg( 'copyrightpage' )->inContentLanguage()->text() . ']]',
				$rightsText ];
		} else {
			$copywarnMsg = [ 'copyrightwarning2',
				'[[' . $localizer->msg( 'copyrightpage' )->inContentLanguage()->text() . ']]' ];
		}
		// Allow for site and per-namespace customization of contribution/copyright notice.
		$title = Title::newFromPageReference( $page );
		( new HookRunner( $services->getHookContainer() ) )->onEditPageCopyrightWarning( $title, $copywarnMsg );
		if ( !$copywarnMsg ) {
			return '';
		}

		$msg = $localizer->msg( ...$copywarnMsg )->page( $page );
		return Html::rawElement( 'div', [ 'id' => 'editpage-copywarn' ], $msg->$format() );
	}

	/**
	 * Get the Limit report for page previews
	 *
	 * @since 1.22
	 * @param ParserOutput|null $output ParserOutput object from the parse
	 * @return string HTML
	 */
	public static function getPreviewLimitReport( ?ParserOutput $output = null ) {
		if ( !$output || !$output->getLimitReportData() ) {
			return '';
		}

		$limitReport = Html::rawElement( 'div', [ 'class' => 'mw-limitReportExplanation' ],
			wfMessage( 'limitreport-title' )->parseAsBlock()
		);

		// Show/hide animation doesn't work correctly on a table, so wrap it in a div.
		$limitReport .= Html::openElement( 'div', [ 'class' => 'preview-limit-report-wrapper' ] );

		$limitReport .= Html::openElement( 'table', [
			'class' => 'preview-limit-report wikitable'
		] ) .
			Html::openElement( 'tbody' );

		$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
		foreach ( $output->getLimitReportData() as $key => $value ) {
			if ( in_array( $key, [
				'cachereport-origin',
				'cachereport-timestamp',
				'cachereport-ttl',
				'cachereport-transientcontent',
				'limitreport-timingprofile',
			] ) ) {
				// These entries have non-numeric parameters, and can't be displayed by this code.
				// They are used by the plaintext limit report (see RenderDebugInfo::debugInfo()).
				// TODO: Display this information in the table somehow.
				continue;
			}

			if ( $hookRunner->onParserLimitReportFormat( $key, $value, $limitReport, true, true ) ) {
				$keyMsg = wfMessage( $key );
				$valueMsg = wfMessage( "$key-value" );
				if ( !$valueMsg->exists() ) {
					// This is formatted raw, not as localized number.
					// If you want the parameter formatted as a number,
					// define the `$key-value` message.
					$valueMsg = ( new RawMessage( '$1' ) )->params( $value );
				} else {
					// If you define the `$key-value` or `$key-value-html`
					// message then the argument *must* be numeric.
					$valueMsg = $valueMsg->numParams( $value );
				}
				if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
					$limitReport .= Html::openElement( 'tr' ) .
						Html::rawElement( 'th', [], $keyMsg->parse() ) .
						Html::rawElement( 'td', [], $valueMsg->parse() ) .
						Html::closeElement( 'tr' );
				}
			}
		}

		$limitReport .= Html::closeElement( 'tbody' ) .
			Html::closeElement( 'table' ) .
			Html::closeElement( 'div' );

		return $limitReport;
	}

	protected function showStandardInputs( int &$tabindex = 2 ) {
		$out = $this->context->getOutput();
		$out->addHTML( "<div class='editOptions'>\n" );

		if ( $this->section !== 'new' ) {
			$this->showSummaryInput( false );
			$out->addHTML( $this->getSummaryPreview( false ) );
		}

		// When previewing, override the selected dropdown option to select whatever was posted
		// (if it's a valid option) rather than the current value for watchlistExpiry.
		// See also above in $this->importFormDataPosted().
		$expiryFromRequest = null;
		if ( $this->preview || $this->diff || $this->isConflict ) {
			$expiryFromRequest = $this->getContext()->getRequest()->getText( 'wpWatchlistExpiry' );
		}

		$checkboxes = $this->getCheckboxesWidget(
			$tabindex,
			[ 'minor' => $this->minoredit, 'watch' => $this->watchthis, 'wpWatchlistExpiry' => $expiryFromRequest ]
		);
		$checkboxesHTML = new OOUI\HorizontalLayout( [ 'items' => array_values( $checkboxes ) ] );

		$out->addHTML( "<div class='editCheckboxes'>" . $checkboxesHTML . "</div>\n" );

		// Show copyright warning.
		$out->addHTML( self::getCopyrightWarning( $this->mTitle, 'parse', $this->context ) );
		$out->addHTML( $this->editFormTextAfterWarn );

		$out->addHTML( "<div class='editButtons'>\n" );
		$out->addHTML( implode( "\n", $this->getEditButtons( $tabindex ) ) . "\n" );

		$cancel = $this->getCancelLink( $tabindex++ );

		$edithelp = $this->getHelpLink() .
			$this->context->msg( 'word-separator' )->escaped() .
			$this->context->msg( 'newwindow' )->parse();

		$out->addHTML( "	<span class='cancelLink'>{$cancel}</span>\n" );
		$out->addHTML( "	<span class='editHelp'>{$edithelp}</span>\n" );
		$out->addHTML( "</div><!-- editButtons -->\n" );

		$this->getHookRunner()->onEditPage__showStandardInputs_options( $this, $out, $tabindex );

		$out->addHTML( "</div><!-- editOptions -->\n" );
	}

	/**
	 * Show an edit conflict. textbox1 is already shown in showEditForm().
	 * If you want to use another entry point to this function, be careful.
	 */
	private function showConflict(): void {
		$out = $this->context->getOutput();
		if ( $this->getHookRunner()->onEditPageBeforeConflictDiff( $this, $out ) ) {
			$this->incrementConflictStats();

			$this->getEditConflictHelper()->showEditFormTextAfterFooters();
		}
	}

	private function incrementConflictStats(): void {
		$this->getEditConflictHelper()->incrementConflictStats( $this->context->getUser() );
	}

	private function getHelpLink(): string {
		$message = $this->context->msg( 'edithelppage' )->inContentLanguage()->text();
		$editHelpUrl = Skin::makeInternalOrExternalUrl( $message );
		return Html::element( 'a', [
			'href' => $editHelpUrl,
			'target' => 'helpwindow'
		], $this->context->msg( 'edithelp' )->text() );
	}

	/**
	 * @param int $tabindex Current tabindex
	 * @return ButtonWidget
	 */
	private function getCancelLink( int $tabindex ): ButtonWidget {
		$cancelParams = [];
		if ( !$this->isConflict && $this->oldid > 0 ) {
			$cancelParams['oldid'] = $this->oldid;
		} elseif ( $this->getContextTitle()->isRedirect() ) {
			$cancelParams['redirect'] = 'no';
		}

		return new OOUI\ButtonWidget( [
			'id' => 'mw-editform-cancel',
			'tabIndex' => $tabindex,
			'href' => $this->getContextTitle()->getLinkURL( $cancelParams ),
			'label' => new OOUI\HtmlSnippet( $this->context->msg( 'cancel' )->parse() ),
			'framed' => false,
			'infusable' => true,
			'flags' => 'destructive',
		] );
	}

	/**
	 * Returns the URL to use in the form's action attribute.
	 * This is used by EditPage subclasses when simply customizing the action
	 * variable in the constructor is not enough. This can be used when the
	 * EditPage lives inside of a Special page rather than a custom page action.
	 *
	 * @param Title $title Title object for which is being edited (where we go to for &action= links)
	 * @return string
	 */
	protected function getActionURL( Title $title ) {
		return $title->getLocalURL( [ 'action' => $this->action ] );
	}

	/**
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 */
	private function wasDeletedSinceLastEdit(): bool {
		if ( $this->deletedSinceEdit !== null ) {
			return $this->deletedSinceEdit;
		}

		$this->deletedSinceEdit = false;

		if ( !$this->mTitle->exists() && $this->mTitle->hasDeletedEdits() ) {
			$this->lastDelete = $this->getLastDelete();
			if ( $this->lastDelete ) {
				$deleteTime = wfTimestamp( TS_MW, $this->lastDelete->log_timestamp );
				if ( $deleteTime > $this->starttime ) {
					$this->deletedSinceEdit = true;
				}
			}
		}

		return $this->deletedSinceEdit;
	}

	/**
	 * Get the last log record of this page being deleted, if ever.  This is
	 * used to detect whether a delete occurred during editing.
	 * @return stdClass|null
	 */
	private function getLastDelete(): ?stdClass {
		$dbr = $this->dbProvider->getReplicaDatabase();
		$commentQuery = $this->commentStore->getJoin( 'log_comment' );
		$data = $dbr->newSelectQueryBuilder()
			->select( [
				'log_type',
				'log_action',
				'log_timestamp',
				'log_namespace',
				'log_title',
				'log_params',
				'log_deleted',
				'actor_name'
			] )
			->from( 'logging' )
			->join( 'actor', null, 'actor_id=log_actor' )
			->where( [
				'log_namespace' => $this->mTitle->getNamespace(),
				'log_title' => $this->mTitle->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
			] )
			->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_DESC )
			->queryInfo( $commentQuery )
			->caller( __METHOD__ )
			->fetchRow();
		// Quick paranoid permission checks...
		if ( $data !== false ) {
			if ( $data->log_deleted & LogPage::DELETED_USER ) {
				$data->actor_name = $this->context->msg( 'rev-deleted-user' )->escaped();
			}

			if ( $data->log_deleted & LogPage::DELETED_COMMENT ) {
				$data->log_comment_text = $this->context->msg( 'rev-deleted-comment' )->escaped();
				$data->log_comment_data = null;
			}
		}

		return $data ?: null;
	}

	/**
	 * Get the rendered text for previewing.
	 * @throws MWException
	 * @return string
	 */
	public function getPreviewText() {
		$out = $this->context->getOutput();
		$config = $this->context->getConfig();

		if ( $config->get( MainConfigNames::RawHtml ) && !$this->mTokenOk ) {
			// Could be an offsite preview attempt. This is very unsafe if
			// HTML is enabled, as it could be an attack.
			$parsedNote = '';
			if ( $this->textbox1 !== '' ) {
				// Do not put big scary notice, if previewing the empty
				// string, which happens when you initially edit
				// a category page, due to automatic preview-on-open.
				$parsedNote = Html::rawElement( 'div', [ 'class' => 'previewnote' ],
					$out->parseAsInterface(
						$this->context->msg( 'session_fail_preview_html' )->plain()
					) );
			}
			$this->incrementEditFailureStats( 'session_loss' );
			return $parsedNote;
		}

		$note = '';

		try {
			$content = $this->toEditContent( $this->textbox1 );

			$previewHTML = '';
			if ( !$this->getHookRunner()->onAlternateEditPreview(
				$this, $content, $previewHTML, $this->mParserOutput )
			) {
				return $previewHTML;
			}

			# provide a anchor link to the editform
			$continueEditing = '<span class="mw-continue-editing">' .
				'[[#' . self::EDITFORM_ID . '|' .
				$this->context->getLanguage()->getArrow() . ' ' .
				$this->context->msg( 'continue-editing' )->text() . ']]</span>';
			if ( $this->mTriedSave && !$this->mTokenOk ) {
				$note = $this->context->msg( 'session_fail_preview' )->plain();
				$this->incrementEditFailureStats( 'session_loss' );
			} elseif ( $this->incompleteForm ) {
				$note = $this->context->msg( 'edit_form_incomplete' )->plain();
				if ( $this->mTriedSave ) {
					$this->incrementEditFailureStats( 'incomplete_form' );
				}
			} else {
				$note = $this->context->msg( 'previewnote' )->plain() . ' ' . $continueEditing;
			}

			# don't parse non-wikitext pages, show message about preview
			if ( $this->mTitle->isUserConfigPage() || $this->mTitle->isSiteConfigPage() ) {
				if ( $this->mTitle->isUserConfigPage() ) {
					$level = 'user';
				} elseif ( $this->mTitle->isSiteConfigPage() ) {
					$level = 'site';
				} else {
					$level = false;
				}

				if ( $content->getModel() === CONTENT_MODEL_CSS ) {
					$format = 'css';
					if ( $level === 'user' && !$config->get( MainConfigNames::AllowUserCss ) ) {
						$format = false;
					}
				} elseif ( $content->getModel() === CONTENT_MODEL_JSON ) {
					$format = 'json';
					if ( $level === 'user' /* No comparable 'AllowUserJson' */ ) {
						$format = false;
					}
				} elseif ( $content->getModel() === CONTENT_MODEL_JAVASCRIPT ) {
					$format = 'js';
					if ( $level === 'user' && !$config->get( MainConfigNames::AllowUserJs ) ) {
						$format = false;
					}
				} elseif ( $content->getModel() === CONTENT_MODEL_VUE ) {
					$format = 'vue';
					if ( $level === 'user' && !$config->get( MainConfigNames::AllowUserJs ) ) {
						$format = false;
					}
				} else {
					$format = false;
				}

				# Used messages to make sure grep find them:
				# Messages: usercsspreview, userjsonpreview, userjspreview,
				#   sitecsspreview, sitejsonpreview, sitejspreview
				if ( $level && $format ) {
					$note = "<div id='mw-{$level}{$format}preview'>" .
						$this->context->msg( "{$level}{$format}preview" )->plain() .
						' ' . $continueEditing . "</div>";
				}
			}

			if ( $this->section === "new" ) {
				$content = $content->addSectionHeader( $this->sectiontitle );
			}

			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this->getHookRunner()->onEditPageGetPreviewContent( $this, $content );

			$parserResult = $this->doPreviewParse( $content );
			$parserOutput = $parserResult['parserOutput'];
			$previewHTML = $parserResult['html'];
			$this->mParserOutput = $parserOutput;
			$out->addParserOutputMetadata( $parserOutput );
			if ( $out->userCanPreview() ) {
				$out->addContentOverride( $this->getTitle(), $content );
			}

			foreach ( $parserOutput->getWarningMsgs() as $mv ) {
				$note .= "\n\n" . $this->context->msg( $mv )->text();
			}

		} catch ( MWContentSerializationException $ex ) {
			$m = $this->context->msg(
				'content-failed-to-parse',
				$this->contentModel,
				$this->contentFormat,
				$ex->getMessage()
			);
			$note .= "\n\n" . $m->plain(); # gets parsed down below
			$previewHTML = '';
		}

		if ( $this->isConflict ) {
			$conflict = Html::warningBox(
				$this->context->msg( 'previewconflict' )->escaped(),
				'mw-previewconflict'
			);
		} else {
			$conflict = '';
		}

		$previewhead = Html::rawElement(
			'div', [ 'class' => 'previewnote' ],
			Html::rawElement(
				'h2', [ 'id' => 'mw-previewheader' ],
				$this->context->msg( 'preview' )->escaped()
			) .
			Html::warningBox(
				$out->parseAsInterface( $note )
			) . $conflict
		);

		return $previewhead . $previewHTML . $this->previewTextAfterContent;
	}

	private function incrementEditFailureStats( string $failureType ): void {
		MediaWikiServices::getInstance()->getStatsFactory()
			->getCounter( 'edit_failure_total' )
			->setLabel( 'cause', $failureType )
			->setLabel( 'namespace', 'n/a' )
			->setLabel( 'user_bucket', 'n/a' )
			->copyToStatsdAt( 'edit.failures.' . $failureType )
			->increment();
	}

	/**
	 * Get parser options for a preview
	 * @return ParserOptions
	 */
	protected function getPreviewParserOptions() {
		$parserOptions = $this->page->makeParserOptions( $this->context );
		$parserOptions->setRenderReason( 'page-preview' );
		$parserOptions->setIsPreview( true );
		$parserOptions->setIsSectionPreview( $this->section !== null && $this->section !== '' );

		// XXX: we could call $parserOptions->setCurrentRevisionRecordCallback here to force the
		// current revision to be null during PST, until setupFakeRevision is called on
		// the ParserOptions. Currently, we rely on Parser::getRevisionRecordObject() to ignore
		// existing revisions in preview mode.

		return $parserOptions;
	}

	/**
	 * Parse the page for a preview. Subclasses may override this class, in order
	 * to parse with different options, or to otherwise modify the preview HTML.
	 *
	 * @param Content $content The page content
	 * @return array with keys:
	 *   - parserOutput: The ParserOutput object
	 *   - html: The HTML to be displayed
	 */
	protected function doPreviewParse( Content $content ) {
		$user = $this->getUserForPreview();
		$parserOptions = $this->getPreviewParserOptions();

		// NOTE: preSaveTransform doesn't have a fake revision to operate on.
		// Parser::getRevisionRecordObject() will return null in preview mode,
		// causing the context user to be used for {{subst:REVISIONUSER}}.
		// XXX: Alternatively, we could also call setupFakeRevision()
		// before PST with $content.
		$services = MediaWikiServices::getInstance();
		$contentTransformer = $services->getContentTransformer();
		$contentRenderer = $services->getContentRenderer();
		$pstContent = $contentTransformer->preSaveTransform( $content, $this->mTitle, $user, $parserOptions );
		$parserOutput = $contentRenderer->getParserOutput( $pstContent, $this->mTitle, null, $parserOptions );
		$out = $this->context->getOutput();
		$skin = $out->getSkin();
		$skinOptions = $skin->getOptions();
		// TODO T371004 move runOutputPipeline out of $parserOutput
		// TODO T371022 ideally we clone here, but for now let's reproduce getText behaviour
		$oldHtml = $parserOutput->getRawText();
		$html = $parserOutput->runOutputPipeline( $parserOptions, [
			'allowClone' => 'false',
			'userLang' => $skin->getLanguage(),
			'injectTOC' => $skinOptions['toc'],
			'enableSectionEditLinks' => false,
			'includeDebugInfo' => true,
		] )->getContentHolderText();
		$parserOutput->setRawText( $oldHtml );
		return [
			'parserOutput' => $parserOutput,
			'html' => $html
		];
	}

	/**
	 * @return Title[]
	 */
	public function getTemplates() {
		if ( $this->preview || $this->section !== '' ) {
			$templates = [];
			if ( !$this->mParserOutput ) {
				return $templates;
			}
			foreach (
				$this->mParserOutput->getLinkList( ParserOutputLinkTypes::TEMPLATE )
				as [ 'link' => $link ]
			) {
				$templates[] = Title::newFromLinkTarget( $link );
			}
			return $templates;
		} else {
			return $this->mTitle->getTemplateLinksFrom();
		}
	}

	/**
	 * Allow extensions to provide a toolbar.
	 *
	 * @return string|null
	 */
	public static function getEditToolbar() {
		$startingToolbar = '<div id="toolbar"></div>';
		$toolbar = $startingToolbar;

		$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
		if ( !$hookRunner->onEditPageBeforeEditToolbar( $toolbar ) ) {
			return null;
		}
		// Don't add a pointless `<div>` to the page unless a hook caller populated it
		return ( $toolbar === $startingToolbar ) ? null : $toolbar;
	}

	/**
	 * Return an array of field definitions. Despite the name, not only checkboxes are supported.
	 *
	 * Array keys correspond to the `<input>` 'name' attribute to use for each field.
	 *
	 * Array values are associative arrays with the following keys:
	 *  - 'label-message' (required): message for label text
	 *  - 'id' (required): 'id' attribute for the `<input>`
	 *  - 'default' (required): default checkedness (true or false)
	 *  - 'title-message' (optional): used to generate 'title' attribute for the `<label>`
	 *  - 'tooltip' (optional): used to generate 'title' and 'accesskey' attributes
	 *    from messages like 'tooltip-foo', 'accesskey-foo'
	 *  - 'label-id' (optional): 'id' attribute for the `<label>`
	 *  - 'legacy-name' (optional): short name for backwards-compatibility
	 *  - 'class' (optional): PHP class name of the OOUI widget to use. Defaults to
	 *    CheckboxInputWidget.
	 *  - 'options' (optional): options to use for DropdownInputWidget,
	 *    ComboBoxInputWidget, etc. following the structure as given in the documentation for those
	 *    classes.
	 *  - 'value-attr' (optional): name of the widget config option for the "current value" of the
	 *    widget. Defaults to 'selected'; for some widget types it should be 'value'.
	 * @param array<string,mixed> $values Map of field names (matching the 'legacy-name') to current field values.
	 *   For checkboxes, the value is a bool that indicates the checked status of the checkbox.
	 * @return array[]
	 */
	public function getCheckboxesDefinition( $values ) {
		$checkboxes = [];

		$user = $this->context->getUser();
		// don't show the minor edit checkbox if it's a new page or section
		if ( !$this->isNew && $this->permManager->userHasRight( $user, 'minoredit' ) ) {
			$checkboxes['wpMinoredit'] = [
				'id' => 'wpMinoredit',
				'label-message' => 'minoredit',
				// Uses messages: tooltip-minoredit, accesskey-minoredit
				'tooltip' => 'minoredit',
				'label-id' => 'mw-editpage-minoredit',
				'legacy-name' => 'minor',
				'default' => $values['minor'],
			];
		}

		if ( $user->isNamed() ) {
			$checkboxes = array_merge(
				$checkboxes,
				$this->getCheckboxesDefinitionForWatchlist( $values['watch'], $values['wpWatchlistExpiry'] ?? null )
			);
		}

		$this->getHookRunner()->onEditPageGetCheckboxesDefinition( $this, $checkboxes );

		return $checkboxes;
	}

	/**
	 * Get the watchthis and watchlistExpiry form field definitions.
	 *
	 * @param bool $watch
	 * @param string $watchexpiry
	 * @return array[]
	 */
	private function getCheckboxesDefinitionForWatchlist( $watch, $watchexpiry ): array {
		$fieldDefs = [
			'wpWatchthis' => [
				'id' => 'wpWatchthis',
				'label-message' => 'watchthis',
				// Uses messages: tooltip-watch, accesskey-watch
				'tooltip' => 'watch',
				'label-id' => 'mw-editpage-watch',
				'legacy-name' => 'watch',
				'default' => $watch,
			]
		];
		if ( $this->watchlistExpiryEnabled ) {
			$watchedItem = $this->watchedItemStore->getWatchedItem( $this->getContext()->getUser(), $this->getTitle() );
			if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() === null ) {
				// Not temporarily watched, so we always default to infinite.
				$userPreferredExpiry = 'infinite';
			} else {
				$userPreferredExpiryOption = !$this->getTitle()->exists()
					? 'watchcreations-expiry'
					: 'watchdefault-expiry';
				$userPreferredExpiry = $this->userOptionsLookup->getOption(
					$this->getContext()->getUser(),
					$userPreferredExpiryOption,
					'infinite'
				);
			}

			$expiryOptions = WatchAction::getExpiryOptions(
				$this->getContext(),
				$watchedItem,
				$userPreferredExpiry
			);

			if ( $watchexpiry && in_array( $watchexpiry, $expiryOptions['options'] ) ) {
				$expiryOptions['default'] = $watchexpiry;
			}
			// When previewing, override the selected dropdown option to select whatever was posted
			// (if it's a valid option) rather than the current value for watchlistExpiry.
			// See also above in $this->importFormDataPosted().
			$expiryFromRequest = $this->getContext()->getRequest()->getText( 'wpWatchlistExpiry' );
			if ( ( $this->preview || $this->diff ) && in_array( $expiryFromRequest, $expiryOptions['options'] ) ) {
				$expiryOptions['default'] = $expiryFromRequest;
			}

			// Reformat the options to match what DropdownInputWidget wants.
			$options = [];
			foreach ( $expiryOptions['options'] as $label => $value ) {
				$options[] = [ 'data' => $value, 'label' => $label ];
			}

			$fieldDefs['wpWatchlistExpiry'] = [
				'id' => 'wpWatchlistExpiry',
				'label-message' => 'confirm-watch-label',
				// Uses messages: tooltip-watchlist-expiry, accesskey-watchlist-expiry
				'tooltip' => 'watchlist-expiry',
				'label-id' => 'mw-editpage-watchlist-expiry',
				'default' => $expiryOptions['default'],
				'value-attr' => 'value',
				'class' => DropdownInputWidget::class,
				'options' => $options,
				'invisibleLabel' => true,
			];
		}
		return $fieldDefs;
	}

	/**
	 * Returns an array of fields for the edit form, including 'minor' and 'watch' checkboxes and
	 * any other added by extensions. Despite the name, not only checkboxes are supported.
	 *
	 * @param int &$tabindex Current tabindex
	 * @param array<string,mixed> $values Map of field names to current field values.
	 *   For checkboxes, the value is a bool that indicates the checked status of the checkbox.
	 * @return \OOUI\Element[] Associative array of string keys to \OOUI\Widget or \OOUI\Layout
	 *  instances
	 */
	public function getCheckboxesWidget( &$tabindex, $values ) {
		$checkboxes = [];
		$checkboxesDef = $this->getCheckboxesDefinition( $values );

		foreach ( $checkboxesDef as $name => $options ) {
			$legacyName = $options['legacy-name'] ?? $name;

			$title = null;
			$accesskey = null;
			if ( isset( $options['tooltip'] ) ) {
				$accesskey = $this->context->msg( "accesskey-{$options['tooltip']}" )->text();
				$title = Linker::titleAttrib( $options['tooltip'] );
			}
			if ( isset( $options['title-message'] ) ) {
				$title = $this->context->msg( $options['title-message'] )->text();
			}
			// Allow checkbox definitions to set their own class and value-attribute names.
			// See $this->getCheckboxesDefinition() for details.
			$className = $options['class'] ?? CheckboxInputWidget::class;
			$valueAttr = $options['value-attr'] ?? 'selected';
			$checkboxes[ $legacyName ] = new FieldLayout(
				new $className( [
					'tabIndex' => ++$tabindex,
					'accessKey' => $accesskey,
					'id' => $options['id'] . 'Widget',
					'inputId' => $options['id'],
					'name' => $name,
					$valueAttr => $options['default'],
					'infusable' => true,
					'options' => $options['options'] ?? null,
				] ),
				[
					'align' => 'inline',
					'label' => new OOUI\HtmlSnippet( $this->context->msg( $options['label-message'] )->parse() ),
					'title' => $title,
					'id' => $options['label-id'] ?? null,
					'invisibleLabel' => $options['invisibleLabel'] ?? null,
				]
			);
		}

		return $checkboxes;
	}

	/**
	 * Get the message key of the label for the button to save the page
	 */
	private function getSubmitButtonLabel(): string {
		$labelAsPublish =
			$this->context->getConfig()->get( MainConfigNames::EditSubmitButtonLabelPublish );

		// Can't use $this->isNew as that's also true if we're adding a new section to an extant page
		$newPage = !$this->mTitle->exists();

		if ( $labelAsPublish ) {
			$buttonLabelKey = $newPage ? 'publishpage' : 'publishchanges';
		} else {
			$buttonLabelKey = $newPage ? 'savearticle' : 'savechanges';
		}

		return $buttonLabelKey;
	}

	/**
	 * Returns an array of html code of the following buttons:
	 * save, diff and preview
	 *
	 * @param int &$tabindex Current tabindex
	 *
	 * @return string[] Strings or objects with a __toString() implementation. Usually an array of
	 *  {@see ButtonInputWidget}, but EditPageBeforeEditButtons hook handlers might inject something
	 *  else.
	 */
	public function getEditButtons( &$tabindex ) {
		$buttons = [];

		$labelAsPublish =
			$this->context->getConfig()->get( MainConfigNames::EditSubmitButtonLabelPublish );

		$buttonLabel = $this->context->msg( $this->getSubmitButtonLabel() )->text();
		$buttonTooltip = $labelAsPublish ? 'publish' : 'save';

		$buttons['save'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpSave',
			'tabIndex' => ++$tabindex,
			'id' => 'wpSaveWidget',
			'inputId' => 'wpSave',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'flags' => [ 'progressive', 'primary' ],
			'label' => $buttonLabel,
			'infusable' => true,
			'type' => 'submit',
			// Messages used: tooltip-save, tooltip-publish
			'title' => Linker::titleAttrib( $buttonTooltip ),
			// Messages used: accesskey-save, accesskey-publish
			'accessKey' => Linker::accesskey( $buttonTooltip ),
		] );

		$buttons['preview'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpPreview',
			'tabIndex' => ++$tabindex,
			'id' => 'wpPreviewWidget',
			'inputId' => 'wpPreview',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'label' => $this->context->msg( 'showpreview' )->text(),
			'infusable' => true,
			'type' => 'submit',
			// Allow previewing even when the form is in invalid state (T343585)
			'formNoValidate' => true,
			// Message used: tooltip-preview
			'title' => Linker::titleAttrib( 'preview' ),
			// Message used: accesskey-preview
			'accessKey' => Linker::accesskey( 'preview' ),
		] );

		$buttons['diff'] = new OOUI\ButtonInputWidget( [
			'name' => 'wpDiff',
			'tabIndex' => ++$tabindex,
			'id' => 'wpDiffWidget',
			'inputId' => 'wpDiff',
			// Support: IE 6 – Use <input>, otherwise it can't distinguish which button was clicked
			'useInputTag' => true,
			'label' => $this->context->msg( 'showdiff' )->text(),
			'infusable' => true,
			'type' => 'submit',
			// Allow previewing even when the form is in invalid state (T343585)
			'formNoValidate' => true,
			// Message used: tooltip-diff
			'title' => Linker::titleAttrib( 'diff' ),
			// Message used: accesskey-diff
			'accessKey' => Linker::accesskey( 'diff' ),
		] );

		$this->getHookRunner()->onEditPageBeforeEditButtons( $this, $buttons, $tabindex );

		return $buttons;
	}

	/**
	 * Creates a basic error page which informs the user that
	 * they have attempted to edit a nonexistent section.
	 */
	private function noSuchSectionPage(): void {
		$out = $this->context->getOutput();
		$out->prepareErrorPage();
		$out->setPageTitleMsg( $this->context->msg( 'nosuchsectiontitle' ) );

		$res = $this->context->msg( 'nosuchsectiontext', $this->section )->parseAsBlock();

		$this->getHookRunner()->onEditPageNoSuchSection( $this, $res );
		$out->addHTML( $res );

		$out->returnToMain( false, $this->mTitle );
	}

	/**
	 * Show "your edit contains spam" page with your diff and text
	 *
	 * @param string|array|false $match Text (or array of texts) which triggered one or more filters
	 */
	public function spamPageWithContent( $match = false ) {
		$this->textbox2 = $this->textbox1;

		$out = $this->context->getOutput();
		$out->prepareErrorPage();
		$out->setPageTitleMsg( $this->context->msg( 'spamprotectiontitle' ) );

		$spamText = $this->context->msg( 'spamprotectiontext' )->parseAsBlock();

		if ( $match ) {
			if ( is_array( $match ) ) {
				$matchText = $this->context->getLanguage()->listToText( array_map( 'wfEscapeWikiText', $match ) );
			} else {
				$matchText = wfEscapeWikiText( $match );
			}

			$spamText .= $this->context->msg( 'spamprotectionmatch' )
				->params( $matchText )
				->parseAsBlock();
		}
		$out->addHTML( Html::rawElement(
			'div',
			[ 'id' => 'spamprotected' ],
			$spamText
		) );

		$out->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );
		$this->showDiff();

		$out->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );
		$this->showTextbox( $this->textbox2, 'wpTextbox2', [ 'tabindex' => 6, 'readonly' ] );

		$out->addReturnTo( $this->getContextTitle(), [ 'action' => 'edit' ] );
	}

	private function addLongPageWarningHeader(): void {
		if ( $this->contentLength === false ) {
			$this->contentLength = strlen( $this->textbox1 );
		}

		$out = $this->context->getOutput();
		$longPageHint = $this->context->msg( 'longpage-hint' );
		if ( !$longPageHint->isDisabled() ) {
			$msgText = trim( $longPageHint->sizeParams( $this->contentLength )
				->params( $this->contentLength ) // Keep this unformatted for math inside message
				->parse() );
			if ( $msgText !== '' && $msgText !== '-' ) {
				$out->addHTML( "<div id='mw-edit-longpage-hint'>\n$msgText\n</div>" );
			}
		}
	}

	private function addExplainConflictHeader(): void {
		$this->context->getOutput()->addHTML(
			$this->getEditConflictHelper()->getExplainHeader()
		);
	}

	/**
	 * Turns section name wikitext into anchors for use in HTTP redirects. Various
	 * versions of Microsoft browsers misinterpret fragment encoding of Location: headers
	 * resulting in mojibake in address bar. Redirect them to legacy section IDs,
	 * if possible. All the other browsers get HTML5 if the wiki is configured for it, to
	 * spread the new style links more efficiently.
	 *
	 * @param string $text
	 * @return string
	 */
	private function guessSectionName( $text ): string {
		// Detect Microsoft browsers
		$userAgent = $this->context->getRequest()->getHeader( 'User-Agent' );
		$parser = MediaWikiServices::getInstance()->getParser();
		if ( $userAgent && preg_match( '/MSIE|Edge/', $userAgent ) ) {
			// ...and redirect them to legacy encoding, if available
			return $parser->guessLegacySectionNameFromWikiText( $text );
		}
		// Meanwhile, real browsers get real anchors
		$name = $parser->guessSectionNameFromWikiText( $text );
		// With one little caveat: per T216029, fragments in HTTP redirects need to be urlencoded,
		// otherwise Chrome double-escapes the rest of the URL.
		return '#' . urlencode( mb_substr( $name, 1 ) );
	}

	/**
	 * @param callable $factory Factory function to create a {@see TextConflictHelper}
	 * @since 1.31
	 */
	public function setEditConflictHelperFactory( callable $factory ) {
		Assert::precondition( !$this->editConflictHelperFactory,
			'Can only have one extension that resolves edit conflicts' );
		$this->editConflictHelperFactory = $factory;
	}

	private function getEditConflictHelper(): TextConflictHelper {
		if ( !$this->editConflictHelper ) {
			$label = $this->getSubmitButtonLabel();
			if ( $this->editConflictHelperFactory ) {
				$this->editConflictHelper = ( $this->editConflictHelperFactory )( $label );
			} else {
				$this->editConflictHelper = new TextConflictHelper(
					$this->getTitle(),
					$this->getContext()->getOutput(),
					MediaWikiServices::getInstance()->getStatsFactory(),
					$label,
					MediaWikiServices::getInstance()->getContentHandlerFactory()
				);
			}
		}
		return $this->editConflictHelper;
	}
}
