<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use LogicException;
use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\OOUIHTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Pager\EditWatchlistPager;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\Watchlist\WatchlistSpecialPage;

/**
 * Users can edit their watchlist via this page.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialEditWatchlist extends UnlistedSpecialPage {

	use WatchlistSpecialPage;

	/**
	 * Editing modes. EDIT_CLEAR is no longer used; the "Clear" link scared people
	 * too much. Now it's passed on to the raw editor, from which it's very easy to clear.
	 */
	public const EDIT_CLEAR = 1;
	public const EDIT_RAW = 2;
	public const EDIT = 3;
	public const VIEW = 4;

	public const CHECKBOX_NAME = 'wpTitles';

	/** @var string|null */
	protected $successMessage;

	/** @var array[] */
	private $badItems = [];

	private TitleParser $titleParser;
	private WatchedItemStoreInterface $watchedItemStore;
	private GenderCache $genderCache;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $nsInfo;
	private WikiPageFactory $wikiPageFactory;
	private WatchlistManager $watchlistManager;
	protected EditWatchlistPager $pager;

	/** @var int|false where the value is one of the EDIT constants (e.g. EDIT_RAW) */
	private $currentMode;

	public function __construct(
		?WatchedItemStoreInterface $watchedItemStore = null,
		?TitleParser $titleParser = null,
		?GenderCache $genderCache = null,
		?LinkBatchFactory $linkBatchFactory = null,
		?NamespaceInfo $nsInfo = null,
		?WikiPageFactory $wikiPageFactory = null,
		?WatchlistManager $watchlistManager = null,
	) {
		parent::__construct( 'EditWatchlist', 'editmywatchlist' );
		// This class is extended and therefor fallback to global state - T266065
		$services = MediaWikiServices::getInstance();
		$this->watchedItemStore = $watchedItemStore ?? $services->getWatchedItemStore();
		$this->titleParser = $titleParser ?? $services->getTitleParser();
		$this->genderCache = $genderCache ?? $services->getGenderCache();
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->nsInfo = $nsInfo ?? $services->getNamespaceInfo();
		$this->wikiPageFactory = $wikiPageFactory ?? $services->getWikiPageFactory();
		$this->watchlistManager = $watchlistManager ?? $services->getWatchlistManager();
		$this->pager = $this->getDefaultPager(
			(bool)$services->getMainConfig()->get( MainConfigNames::WatchlistExpiry )
		);
	}

	private function getDefaultPager( bool $expiryEnabled ): EditWatchlistPager {
		return new EditWatchlistPager(
			$this->getContext(),
			$this->getPageTitle(),
			$this->watchedItemStore,
			$this->nsInfo,
			$this->linkBatchFactory,
			$this->getHookRunner(),
			$expiryEnabled,
		);
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/**
	 * Main execution point
	 *
	 * @param string|null $mode
	 */
	public function execute( $mode ) {
		$this->setHeaders();

		$user = $this->getUser();
		if ( !$user->isRegistered()
			|| ( $user->isTemp() && !$user->isAllowed( 'editmywatchlist' ) )
		) {
			throw new UserNotLoggedIn( 'watchlistanontext' );
		}

		$out = $this->getOutput();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->outputHeader();
		$out->addModuleStyles( [
			'mediawiki.interface.helpers.styles',
			'mediawiki.special.watchlistedit.styles',
		] );
		$out->addModules( [ 'mediawiki.special.watchlistedit' ] );

		$mode = self::getMode( $this->getRequest(), $mode, self::EDIT );
		$this->currentMode = $mode;
		$this->outputSubtitle();

		switch ( $mode ) {
			case self::VIEW:
				$title = SpecialPage::getTitleFor( 'Watchlist' );
				$out->redirect( $title->getLocalURL() );
				break;
			case self::EDIT_RAW:
				$out->setPageTitleMsg( $this->msg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm();
				if ( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
				}
				break;
			case self::EDIT_CLEAR:
				$out->setPageTitleMsg( $this->msg( 'watchlistedit-clear-title' ) );
				$form = $this->getClearForm();
				if ( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
				}
				break;

			case self::EDIT:
			default:
				$this->executeViewEditWatchlist();
				break;
		}
	}

	/**
	 * Renders a subheader on the watchlist page.
	 */
	protected function outputSubtitle() {
		$subtitle = $this->getWatchlistOwnerHtml();
		if ( !$this->getSkin()->supportsMenu( 'associated-pages' ) ) {
			// For legacy skins render the tabs in the subtitle
			$subtitle .= ' ' . $this->buildTools( $this->currentMode );
		}
		$this->getOutput()->addSubtitle( $subtitle );
	}

	/**
	 * @return HTMLForm
	 */
	private function createNamespaceSelectForm(): HTMLForm {
		$namespaceFormDescriptor = [
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'id' => 'namespace-selector',
				'label-message' => 'namespace',
				'all' => '',
				'default' => '',
				'include' => array_merge( array_values( $this->nsInfo->getSubjectNamespaces() ) ),
			],
		];
		$namespaceSelectForm = HTMLForm::factory( 'codex', $namespaceFormDescriptor, $this->getContext() );
		$namespaceSelectForm
			->setMethod( 'get' )
			->setId( 'namespace-selector-form' )
			->setTitle( $this->getPageTitle() )
			->setSubmitTextMsg( 'allpagessubmit' );
		if ( $this->getRequest()->getInt( 'limit' ) ) {
			$namespaceSelectForm->addHiddenField( 'limit', $this->getRequest()->getInt( 'limit' ) );
		}
		return $namespaceSelectForm->prepareForm();
	}

	/**
	 * Executes an edit mode for the watchlist view, from which you can manage your watchlist
	 */
	protected function executeViewEditWatchlist() {
		$output = $this->getOutput();
		$output->setPageTitleMsg( $this->msg( 'watchlistedit-normal-title' ) );

		if ( $this->getRequest()->wasPosted() ) {
			$this->handleEditWatchlistFormSubmission( $output );
		}

		$this->createNamespaceSelectForm()->displayForm( '' );
		$output->addHTML( $this->pager->getBody() );
	}

	/**
	 * @param OutputPage $output
	 * @return void
	 */
	private function handleEditWatchlistFormSubmission( OutputPage $output ) {
		if ( ( new HTMLForm( [], $this->getContext() ) )->requestIsAuthorized() ) {
			$removed = [];
			$titles = $this->getRequest()->getArray( self::CHECKBOX_NAME );
			if ( is_array( $titles ) ) {
				$this->unwatchTitles( $titles );
				$removed = array_merge( $removed, $titles );
			}
			if ( count( $removed ) > 0 ) {
				$this->successMessage = $this->msg( 'watchlistedit-normal-done' )
					->numParams( count( $removed ) )->parse();
				$this->showTitles( $removed, $this->successMessage );
				$output->addHTML( Html::rawElement(
					'div',
					[ 'class' => 'edit-watchlist-result' ],
					$this->successMessage
				) );
			}
		}
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @see also SpecialWatchlist::getSubpagesForPrefixSearch
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		// SpecialWatchlist uses SpecialEditWatchlist::getMode, so new types should be added
		// here and there - no 'edit' here, because that the default for this page
		return [
			'clear',
			'raw',
		];
	}

	/**
	 * Extract a list of titles from a blob of text, returning
	 * (prefixed) strings; unwatchable titles are ignored
	 *
	 * @param string $list
	 * @return string[]
	 */
	private function extractTitles( $list ) {
		$list = explode( "\n", trim( $list ) );

		$titles = [];

		foreach ( $list as $text ) {
			$text = trim( $text );
			if ( $text !== '' ) {
				$title = Title::newFromText( $text );
				if ( $title instanceof Title && $this->watchlistManager->isWatchable( $title ) ) {
					$titles[] = $title;
				}
			}
		}

		$this->genderCache->doTitlesArray( $titles );

		$list = [];
		/** @var Title $title */
		foreach ( $titles as $title ) {
			$list[] = $title->getPrefixedText();
		}

		return array_unique( $list );
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	private function submitRaw( $data ) {
		$wanted = $this->extractTitles( $data['Titles'] );
		$current = $this->getWatchlistFull();

		if ( count( $wanted ) > 0 ) {
			$toWatch = array_diff( $wanted, $current );
			$toUnwatch = array_diff( $current, $wanted );
			if ( !$toWatch && !$toUnwatch ) {
				return false;
			}

			$this->watchTitles( $toWatch );
			$this->unwatchTitles( $toUnwatch );
			$this->getUser()->invalidateCache();
			$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();

			if ( $toWatch ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-added' )
					->numParams( count( $toWatch ) )->parse();
				$this->showTitles( $toWatch, $this->successMessage );
			}

			if ( $toUnwatch ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed' )
					->numParams( count( $toUnwatch ) )->parse();
				$this->showTitles( $toUnwatch, $this->successMessage );
			}
		} else {
			if ( !$current ) {
				return false;
			}

			$this->clearUserWatchedItems( 'raw' );
			$this->showTitles( $current, $this->successMessage );
		}

		return true;
	}

	/**
	 * Handler for the clear form submission
	 *
	 * @param array $data
	 * @return bool
	 */
	private function submitClear( $data ): bool {
		$this->clearUserWatchedItems( 'clear' );
		return true;
	}

	/**
	 * Makes a decision about using the JobQueue or not for clearing a users watchlist.
	 * Also displays the appropriate messages to the user based on that decision.
	 *
	 * @param string $messageFor 'raw' or 'clear'. Only used when JobQueue is not used.
	 */
	private function clearUserWatchedItems( string $messageFor ): void {
		if ( $this->watchedItemStore->mustClearWatchedItemsUsingJobQueue( $this->getUser() ) ) {
			$this->clearUserWatchedItemsUsingJobQueue();
		} else {
			$this->clearUserWatchedItemsNow( $messageFor );
		}
	}

	/**
	 * You should call clearUserWatchedItems() instead to decide if this should use the JobQueue
	 *
	 * @param string $messageFor 'raw' or 'clear'
	 */
	private function clearUserWatchedItemsNow( string $messageFor ): void {
		$current = $this->getWatchlistFull();
		if ( !$this->watchedItemStore->clearUserWatchedItems( $this->getUser() ) ) {
			throw new LogicException(
				__METHOD__ . ' should only be called when able to clear synchronously'
			);
		}
		// Messages used: watchlistedit-clear-done, watchlistedit-raw-done
		$this->successMessage = $this->msg( 'watchlistedit-' . $messageFor . '-done' )->parse();
		// Messages used: watchlistedit-clear-removed, watchlistedit-raw-removed
		$this->successMessage .= ' ' . $this->msg( 'watchlistedit-' . $messageFor . '-removed' )
				->numParams( count( $current ) )->parse();
		$this->getUser()->invalidateCache();
		$this->showTitles( $current, $this->successMessage );
	}

	/**
	 * You should call clearUserWatchedItems() instead to decide if this should use the JobQueue
	 */
	private function clearUserWatchedItemsUsingJobQueue(): void {
		$this->watchedItemStore->clearUserWatchedItemsUsingJobQueue( $this->getUser() );
		$this->successMessage = $this->msg( 'watchlistedit-clear-jobqueue' )->parse();
	}

	/**
	 * Print out a list of linked titles
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 * @param string &$output
	 */
	private function showTitles( $titles, &$output ) {
		$talk = $this->msg( 'talkpagelinktext' )->text();
		// Do a batch existence check
		$batch = $this->linkBatchFactory->newLinkBatch();
		if ( count( $titles ) >= 100 ) {
			$output = $this->msg( 'watchlistedit-too-many' )->parse();
			return;
		}
		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$batch->addObj( $title );
				$batch->addObj( $title->getTalkPage() );
			}
		}

		$batch->execute();

		// Print out the list
		$output .= "<ul>\n";

		$linkRenderer = $this->getLinkRenderer();
		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$output .= '<li>' .
					$linkRenderer->makeLink( $title ) . ' ' .
					$this->msg( 'parentheses' )->rawParams(
						$linkRenderer->makeLink( $title->getTalkPage(), $talk )
					)->escaped() .
					"</li>\n";
			}
		}

		$output .= "</ul>\n";
	}

	/**
	 * Prepare a list of ALL titles on a user's watchlist (excluding talk pages)
	 * and return an array of (prefixed) strings
	 *
	 * @return array
	 */
	private function getWatchlistFull(): array {
		$list = [];
		$watchedItems = $this->watchedItemStore->getWatchedItemsForUser(
			$this->getUser(),
			[ 'forWrite' => $this->getRequest()->wasPosted() ]
		);
		if ( $watchedItems ) {
			/** @var Title[] $titles */
			$titles = [];
			foreach ( $watchedItems as $watchedItem ) {
				$namespace = $watchedItem->getTarget()->getNamespace();
				$dbKey = $watchedItem->getTarget()->getDBkey();
				$title = Title::makeTitleSafe( $namespace, $dbKey );

				if ( $this->checkTitle( $title, $namespace, $dbKey )
					&& !$title->isTalkPage()
				) {
					$titles[] = $title;
				}
			}
			$this->genderCache->doTitlesArray( $titles );
			foreach ( $titles as $title ) {
				$list[] = $title->getPrefixedText();
			}
		}
		$this->cleanupWatchlist();
		return $list;
	}

	/**
	 * Validates watchlist entry
	 *
	 * @param Title $title
	 * @param int $namespace
	 * @param string $dbKey
	 * @return bool Whether this item is valid
	 */
	private function checkTitle( $title, $namespace, $dbKey ) {
		if ( $title
			&& ( $title->isExternal()
				|| $title->getNamespace() < 0
			)
		) {
			$title = false; // unrecoverable
		}

		if ( !$title
			|| $title->getNamespace() != $namespace
			|| $title->getDBkey() != $dbKey
		) {
			$this->badItems[] = [ $title, $namespace, $dbKey ];
		}

		return (bool)$title;
	}

	/**
	 * Attempts to clean up broken items
	 */
	private function cleanupWatchlist() {
		if ( $this->badItems === [] ) {
			return; // nothing to do
		}

		$user = $this->getUser();
		$badItems = $this->badItems;
		DeferredUpdates::addCallableUpdate( function () use ( $user, $badItems ) {
			foreach ( $badItems as [ $title, $namespace, $dbKey ] ) {
				$action = $title ? 'cleaning up' : 'deleting';
				wfDebug( "User {$user->getName()} has broken watchlist item " .
					"ns($namespace):$dbKey, $action." );

				// NOTE: We *know* that the title is invalid. TitleValue may refuse instantiation.
				// XXX: We may need an InvalidTitleValue class that allows instantiation of
				//      known bad title values.
				$this->watchedItemStore->removeWatch( $user, Title::makeTitle( (int)$namespace, $dbKey ) );
				// Can't just do an UPDATE instead of DELETE/INSERT due to unique index
				if ( $title ) {
					$this->watchlistManager->addWatch( $user, $title );
				}
			}
		} );
	}

	/**
	 * Add a list of targets to a user's watchlist
	 *
	 * @param string[] $targets
	 */
	private function watchTitles( array $targets ): void {
		if ( $targets &&
			$this->watchedItemStore->addWatchBatchForUser(
				$this->getUser(), $this->getExpandedTargets( $targets )
			)
		) {
			$this->runWatchUnwatchCompleteHook( 'Watch', $targets );
		}
	}

	/**
	 * Remove a list of titles from a user's watchlist
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param string[] $targets
	 */
	private function unwatchTitles( array $targets ): void {
		if ( $targets &&
			$this->watchedItemStore->removeWatchBatchForUser(
				$this->getUser(), $this->getExpandedTargets( $targets )
			)
		) {
			$this->runWatchUnwatchCompleteHook( 'Unwatch', $targets );
		}
	}

	/**
	 * @param string $action
	 *   Can be "Watch" or "Unwatch"
	 * @param string[] $targets
	 */
	private function runWatchUnwatchCompleteHook( string $action, array $targets ): void {
		foreach ( $targets as $target ) {
			$title = Title::newFromText( $target );
			$page = $this->wikiPageFactory->newFromTitle( $title );
			$user = $this->getUser();
			if ( $action === 'Watch' ) {
				$this->getHookRunner()->onWatchArticleComplete( $user, $page );
			} else {
				$this->getHookRunner()->onUnwatchArticleComplete( $user, $page );
			}
		}
	}

	/**
	 * @param string[] $targets
	 * @return PageReference[]
	 */
	private function getExpandedTargets( array $targets ) {
		$expandedTargets = [];
		foreach ( $targets as $target ) {
			try {
				$target = $this->titleParser->parseTitle( $target, NS_MAIN );
			} catch ( MalformedTitleException ) {
				continue;
			}

			$ns = $target->getNamespace();
			$dbKey = $target->getDBkey();
			$expandedTargets[] =
				PageReferenceValue::localReference(
					$this->nsInfo->getSubject( $ns ),
					$dbKey
				);
			$expandedTargets[] =
				PageReferenceValue::localReference(
					$this->nsInfo->getTalk( $ns ),
					$dbKey
				);
		}
		return $expandedTargets;
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @return HTMLForm
	 */
	protected function getRawForm() {
		$titles = implode( "\n", $this->getWatchlistFull() );
		$fields = [
			'Titles' => [
				'type' => 'textarea',
				'label-message' => 'watchlistedit-raw-titles',
				'default' => $titles,
			],
		];
		$form = new OOUIHTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getPageTitle( 'raw' ) ); // Reset subpage
		$form->setSubmitTextMsg( 'watchlistedit-raw-submit' );
		# Used message keys: 'accesskey-watchlistedit-raw-submit', 'tooltip-watchlistedit-raw-submit'
		$form->setSubmitTooltip( 'watchlistedit-raw-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-raw-legend' );
		$form->addHeaderHtml( $this->msg( 'watchlistedit-raw-explain' )->parse() );
		$form->setSubmitCallback( $this->submitRaw( ... ) );

		return $form;
	}

	/**
	 * Get a form for clearing the watchlist
	 *
	 * @return HTMLForm
	 */
	protected function getClearForm() {
		$form = new OOUIHTMLForm( [], $this->getContext() );
		$form->setTitle( $this->getPageTitle( 'clear' ) ); // Reset subpage
		$form->setSubmitTextMsg( 'watchlistedit-clear-submit' );
		# Used message keys: 'accesskey-watchlistedit-clear-submit', 'tooltip-watchlistedit-clear-submit'
		$form->setSubmitTooltip( 'watchlistedit-clear-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-clear-legend' );
		$form->addHeaderHtml( $this->msg( 'watchlistedit-clear-explain' )->parse() );
		$form->setSubmitCallback( $this->submitClear( ... ) );
		$form->setSubmitDestructive();

		return $form;
	}

	/**
	 * Determine whether we are editing the watchlist, and if so, what
	 * kind of editing operation
	 *
	 * @param WebRequest $request
	 * @param string|null $par
	 * @param int|false $defaultValue to use if not known.
	 * @return int|false
	 */
	public static function getMode( $request, $par, $defaultValue = false ) {
		$mode = strtolower( $request->getRawVal( 'action' ) ?? $par ?? '' );

		switch ( $mode ) {
			case 'view':
				return self::VIEW;
			case 'clear':
			case self::EDIT_CLEAR:
				return self::EDIT_CLEAR;
			case 'raw':
			case self::EDIT_RAW:
				return self::EDIT_RAW;
			case 'edit':
			case self::EDIT:
				return self::EDIT;
			default:
				return $defaultValue;
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialEditWatchlist::class, 'SpecialEditWatchlist' );
