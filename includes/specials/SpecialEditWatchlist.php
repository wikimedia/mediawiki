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

namespace MediaWiki\Specials;

use EditWatchlistCheckboxSeriesField;
use EditWatchlistNormalHTMLForm;
use LogicException;
use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\OOUIHTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;
use MediaWiki\Title\TitleValue;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * Users can edit their watchlist via this page.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialEditWatchlist extends UnlistedSpecialPage {
	/**
	 * Editing modes. EDIT_CLEAR is no longer used; the "Clear" link scared people
	 * too much. Now it's passed on to the raw editor, from which it's very easy to clear.
	 */
	public const EDIT_CLEAR = 1;
	public const EDIT_RAW = 2;
	public const EDIT_NORMAL = 3;
	public const VIEW = 4;

	/** @var string|null */
	protected $successMessage;

	/** @var TOCData */
	protected $tocData;

	/** @var array[] */
	private $badItems = [];

	private TitleParser $titleParser;
	private WatchedItemStoreInterface $watchedItemStore;
	private GenderCache $genderCache;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $nsInfo;
	private WikiPageFactory $wikiPageFactory;
	private WatchlistManager $watchlistManager;

	/** @var int|false where the value is one of the EDIT_ prefixed constants (e.g. EDIT_NORMAL) */
	private $currentMode;

	public function __construct(
		?WatchedItemStoreInterface $watchedItemStore = null,
		?TitleParser $titleParser = null,
		?GenderCache $genderCache = null,
		?LinkBatchFactory $linkBatchFactory = null,
		?NamespaceInfo $nsInfo = null,
		?WikiPageFactory $wikiPageFactory = null,
		?WatchlistManager $watchlistManager = null
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
	}

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
			'mediawiki.special'
		] );
		$out->addModules( [ 'mediawiki.special.watchlist' ] );

		$mode = self::getMode( $this->getRequest(), $mode, self::EDIT_NORMAL );
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

			case self::EDIT_NORMAL:
			default:
				$this->executeViewEditWatchlist();
				break;
		}
	}

	/**
	 * Renders a subheader on the watchlist page.
	 */
	protected function outputSubtitle() {
		$out = $this->getOutput();
		$skin = $this->getSkin();
		// For legacy skins render the tabs in the subtitle
		$subpageSubtitle = $skin->supportsMenu( 'associated-pages' ) ? '' :
			' ' .
				self::buildTools(
					null,
					$this->getLinkRenderer(),
					$this->currentMode
				);
		$out->addSubtitle(
			Html::element(
				'span',
				[
					'class' => 'mw-watchlist-owner'
				],
				// Previously the watchlistfor2 message took 2 parameters.
				// It now only takes 1 so empty string is passed.
				// Empty string parameter can be removed when all messages
				// are updated to not use $2
				$this->msg( 'watchlistfor2', $this->getUser()->getName(), '' )->text()
			) . $subpageSubtitle
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks() {
		return SpecialWatchlist::WATCHLIST_TAB_PATHS;
	}

	/**
	 * @inheritDoc
	 */
	public function getShortDescription( string $path = '' ): string {
		return SpecialWatchlist::getShortDescriptionHelper( $this, $path );
	}

	/**
	 * Executes an edit mode for the watchlist view, from which you can manage your watchlist
	 */
	protected function executeViewEditWatchlist() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'watchlistedit-normal-title' ) );

		$form = $this->getNormalForm();
		$form->prepareForm();

		$result = $form->tryAuthorizedSubmit();
		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			$out->addHTML( $this->successMessage );
			$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
			return;
		}

		$out->addTOCPlaceholder( $this->tocData );

		$form->displayForm( $result );
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
	 * @return array
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

	public function submitRaw( $data ) {
		$wanted = $this->extractTitles( $data['Titles'] );
		$current = $this->getWatchlist();

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
	public function submitClear( $data ): bool {
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
		$current = $this->getWatchlist();
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
	 * Prepare a list of titles on a user's watchlist (excluding talk pages)
	 * and return an array of (prefixed) strings
	 *
	 * @return array
	 */
	private function getWatchlist() {
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
	 * Get a list of titles on a user's watchlist, excluding talk pages,
	 * and return as a two-dimensional array with namespace and title.
	 *
	 * @return array
	 */
	protected function getWatchlistInfo() {
		$titles = [];
		$options = [ 'sort' => WatchedItemStore::SORT_ASC ];

		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
			$options[ 'sortByExpiry'] = true;
		}

		$watchedItems = $this->watchedItemStore->getWatchedItemsForUser(
			$this->getUser(), $options
		);

		$lb = $this->linkBatchFactory->newLinkBatch();
		$context = $this->getContext();

		foreach ( $watchedItems as $watchedItem ) {
			$namespace = $watchedItem->getTarget()->getNamespace();
			$dbKey = $watchedItem->getTarget()->getDBkey();
			$lb->add( $namespace, $dbKey );
			if ( !$this->nsInfo->isTalk( $namespace ) ) {
				$titles[$namespace][$dbKey] = $watchedItem->getExpiryInDaysText( $context );
			}
		}

		$lb->execute();

		return $titles;
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
	 * @param string[]|LinkTarget[] $targets
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
	 * @param string[]|LinkTarget[] $targets
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
	 * @param string[]|LinkTarget[] $targets
	 */
	private function runWatchUnwatchCompleteHook( string $action, array $targets ): void {
		foreach ( $targets as $target ) {
			$title = $target instanceof LinkTarget ?
				Title::newFromLinkTarget( $target ) :
				Title::newFromText( $target );
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
	 * @param string[]|LinkTarget[] $targets
	 * @return TitleValue[]
	 */
	private function getExpandedTargets( array $targets ) {
		$expandedTargets = [];
		foreach ( $targets as $target ) {
			if ( !$target instanceof LinkTarget ) {
				try {
					$target = $this->titleParser->parseTitle( $target, NS_MAIN );
				} catch ( MalformedTitleException $e ) {
					continue;
				}
			}

			$ns = $target->getNamespace();
			$dbKey = $target->getDBkey();
			$expandedTargets[] =
				new TitleValue( $this->nsInfo->getSubject( $ns ), $dbKey );
			$expandedTargets[] =
				new TitleValue( $this->nsInfo->getTalk( $ns ), $dbKey );
		}
		return $expandedTargets;
	}

	public function submitNormal( $data ) {
		$removed = [];

		foreach ( $data as $titles ) {
			// ignore the 'check all' checkbox, which is a boolean value
			if ( is_array( $titles ) ) {
				$this->unwatchTitles( $titles );
				$removed = array_merge( $removed, $titles );
			}
		}

		if ( count( $removed ) > 0 ) {
			$this->successMessage = $this->msg( 'watchlistedit-normal-done'
			)->numParams( count( $removed ) )->parse();
			$this->showTitles( $removed, $this->successMessage );

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the standard watchlist editing form
	 *
	 * @return HTMLForm
	 */
	protected function getNormalForm() {
		$fields = [];
		$count = 0;

		// Allow subscribers to manipulate the list of watched pages (or use it
		// to preload lots of details at once)
		$watchlistInfo = $this->getWatchlistInfo();
		$this->getHookRunner()->onWatchlistEditorBeforeFormRender( $watchlistInfo );

		foreach ( $watchlistInfo as $namespace => $pages ) {
			$options = [];
			foreach ( $pages as $dbkey => $expiryDaysText ) {
				$title = Title::makeTitleSafe( $namespace, $dbkey );

				if ( $this->checkTitle( $title, $namespace, $dbkey ) ) {
					$text = $this->buildRemoveLine( $title, $expiryDaysText );
					$options[$text] = $title->getPrefixedText();
					$count++;
				}
			}

			// checkTitle can filter some options out, avoid empty sections
			if ( count( $options ) > 0 ) {
				// add a checkbox to select all entries in namespace
				$fields['CheckAllNs' . $namespace] = [
					'cssclass' => 'mw-watchlistedit-checkall',
					'type' => 'check',
					'section' => "ns$namespace",
					'label' => $this->msg( 'watchlistedit-normal-check-all' )->text()
				];

				$fields['TitlesNs' . $namespace] = [
					'cssclass' => 'mw-watchlistedit-check',
					'class' => EditWatchlistCheckboxSeriesField::class,
					'options' => $options,
					'section' => "ns$namespace",
				];
			}
		}
		$this->cleanupWatchlist();

		$this->tocData = new TOCData();
		if ( count( $fields ) > 1 && $count > 30 ) {
			$tocLength = 0;
			$contLang = $this->getContentLanguage();
			foreach ( $fields as $key => $data ) {
				// ignore the 'check all'  field
				if ( str_starts_with( $key, 'CheckAllNs' ) ) {
					continue;
				}
				# strip out the 'ns' prefix from the section name:
				$ns = (int)substr( $data['section'], 2 );
				$nsText = ( $ns === NS_MAIN )
					? $this->msg( 'blanknamespace' )->text()
					: $contLang->getFormattedNsText( $ns );
				$anchor = "editwatchlist-{$data['section']}";
				++$tocLength;
				$this->tocData->addSection( new SectionMetadata(
					1,
					// This is supposed to be the heading level, e.g. 2 for a <h2> tag,
					// but this page uses <legend> tags for the headings, so use a fake value
					99,
					htmlspecialchars( $nsText ),
					$this->getLanguage()->formatNum( $tocLength ),
					(string)$tocLength,
					null,
					null,
					$anchor,
					$anchor
				) );
			}
		}

		$form = new EditWatchlistNormalHTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getPageTitle() ); // Remove subpage
		$form->setSubmitTextMsg( 'watchlistedit-normal-submit' );
		$form->setSubmitDestructive();
		# Used message keys:
		# 'accesskey-watchlistedit-normal-submit', 'tooltip-watchlistedit-normal-submit'
		$form->setSubmitTooltip( 'watchlistedit-normal-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-normal-legend' );
		$form->addHeaderHtml( $this->msg( 'watchlistedit-normal-explain' )->parse() );
		$form->setSubmitCallback( [ $this, 'submitNormal' ] );

		return $form;
	}

	/**
	 * Build the label for a checkbox, with a link to the title, and various additional bits
	 *
	 * @param Title $title
	 * @param string $expiryDaysText message shows the number of days a title has remaining in a user's watchlist.
	 *               If this param is not empty then include a message that states the time remaining in a watchlist.
	 * @return string
	 */
	private function buildRemoveLine( $title, string $expiryDaysText = '' ): string {
		$linkRenderer = $this->getLinkRenderer();
		$link = $linkRenderer->makeLink( $title );

		$tools = [];
		$tools['talk'] = $linkRenderer->makeLink(
			$title->getTalkPage(),
			$this->msg( 'talkpagelinktext' )->text()
		);

		if ( $title->exists() ) {
			$tools['history'] = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'history_small' )->text(),
				[],
				[ 'action' => 'history' ]
			);
		}

		if ( $title->getNamespace() === NS_USER && !$title->isSubpage() ) {
			$tools['contributions'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				$this->msg( 'contribslink' )->text()
			);
		}

		$this->getHookRunner()->onWatchlistEditorBuildRemoveLine(
			$tools, $title, $title->isRedirect(), $this->getSkin(), $link );

		if ( $title->isRedirect() ) {
			// Linker already makes class mw-redirect, so this is redundant
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}

		$watchlistExpiringMessage = '';
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) && $expiryDaysText ) {
			$watchlistExpiringMessage = Html::element(
				'span',
				[ 'class' => 'mw-watchlistexpiry-msg' ],
				$expiryDaysText
			);
		}

		return $link . ' ' . Html::openElement( 'span', [ 'class' => 'mw-changeslist-links' ] ) .
			implode(
				'',
				array_map( static function ( $tool ) {
					return Html::rawElement( 'span', [], $tool );
				}, $tools )
			) .
			Html::closeElement( 'span' ) .
			$watchlistExpiringMessage;
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @return HTMLForm
	 */
	protected function getRawForm() {
		$titles = implode( "\n", $this->getWatchlist() );
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
		$form->setSubmitCallback( [ $this, 'submitRaw' ] );

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
		$form->setSubmitCallback( [ $this, 'submitClear' ] );
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
			case self::EDIT_NORMAL:
				return self::EDIT_NORMAL;
			default:
				return $defaultValue;
		}
	}

	/**
	 * Build a set of links for convenient navigation
	 * between watchlist viewing and editing modes
	 *
	 * @param mixed $unused
	 * @param LinkRenderer|null $linkRenderer
	 * @param int|false $selectedMode result of self::getMode
	 * @return string
	 */
	public static function buildTools( $unused, ?LinkRenderer $linkRenderer = null, $selectedMode = false ) {
		if ( !$linkRenderer ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		}

		$tools = [];
		$modes = [
			'view' => [ 'Watchlist', false, false ],
			'edit' => [ 'EditWatchlist', false, self::EDIT_NORMAL ],
			'raw' => [ 'EditWatchlist', 'raw', self::EDIT_RAW ],
			'clear' => [ 'EditWatchlist', 'clear', self::EDIT_CLEAR ],
		];

		foreach ( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$link = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMessage( "watchlisttools-{$mode}" )->text()
			);
			$isSelected = $selectedMode === $arr[2];
			$classes = [
				'mw-watchlist-toollink',
				'mw-watchlist-toollink-' . $mode,
				$isSelected ? 'mw-watchlist-toollink-active' :
					'mw-watchlist-toollink-inactive'
			];
			$tools[] = Html::rawElement( 'span', [
				'class' => $classes,
			], $link );
		}

		return Html::rawElement(
			'span',
			[ 'class' => [ 'mw-watchlist-toollinks', 'mw-changeslist-links' ] ],
			implode( '', $tools )
		);
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialEditWatchlist::class, 'SpecialEditWatchlist' );
