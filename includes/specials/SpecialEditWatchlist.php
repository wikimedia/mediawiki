<?php
/**
 * @defgroup Watchlist Users watchlist handling
 */

/**
 * Implements Special:EditWatchlist
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
 * @ingroup Watchlist
 */

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * Provides the UI through which users can perform editing
 * operations on their watchlist
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
	const EDIT_CLEAR = 1;
	const EDIT_RAW = 2;
	const EDIT_NORMAL = 3;

	protected $successMessage;

	protected $toc;

	private $badItems = [];

	/**
	 * @var TitleParser
	 */
	private $titleParser;

	public function __construct() {
		parent::__construct( 'EditWatchlist', 'editmywatchlist' );
	}

	/**
	 * Initialize any services we'll need (unless it has already been provided via a setter).
	 * This allows for dependency injection even though we don't control object creation.
	 */
	private function initServices() {
		if ( !$this->titleParser ) {
			$this->titleParser = MediaWikiServices::getInstance()->getTitleParser();
		}
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Main execution point
	 *
	 * @param int $mode
	 */
	public function execute( $mode ) {
		$this->initServices();
		$this->setHeaders();

		# Anons don't get a watchlist
		$this->requireLogin( 'watchlistanontext' );

		$out = $this->getOutput();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->outputHeader();
		$this->outputSubtitle();
		$out->addModuleStyles( 'mediawiki.special' );

		# B/C: $mode used to be waaay down the parameter list, and the first parameter
		# was $wgUser
		if ( $mode instanceof User ) {
			$args = func_get_args();
			if ( count( $args ) >= 4 ) {
				$mode = $args[3];
			}
		}
		$mode = self::getMode( $this->getRequest(), $mode );

		switch ( $mode ) {
			case self::EDIT_RAW:
				$out->setPageTitle( $this->msg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm();
				if ( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
				}
				break;
			case self::EDIT_CLEAR:
				$out->setPageTitle( $this->msg( 'watchlistedit-clear-title' ) );
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
		$out->addSubtitle( $this->msg( 'watchlistfor2', $this->getUser()->getName() )
			->rawParams(
				self::buildTools(
					$this->getLanguage(),
					$this->getLinkRenderer()
				)
			)
		);
	}

	/**
	 * Executes an edit mode for the watchlist view, from which you can manage your watchlist
	 */
	protected function executeViewEditWatchlist() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'watchlistedit-normal-title' ) );
		$form = $this->getNormalForm();
		if ( $form->show() ) {
			$out->addHTML( $this->successMessage );
			$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
		} elseif ( $this->toc !== false ) {
			$out->prependHTML( $this->toc );
			$out->addModules( 'mediawiki.toc' );
			$out->addModuleStyles( 'mediawiki.toc.styles' );
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
	 * @return array
	 */
	private function extractTitles( $list ) {
		$list = explode( "\n", trim( $list ) );
		if ( !is_array( $list ) ) {
			return [];
		}

		$titles = [];

		foreach ( $list as $text ) {
			$text = trim( $text );
			if ( strlen( $text ) > 0 ) {
				$title = Title::newFromText( $text );
				if ( $title instanceof Title && $title->isWatchable() ) {
					$titles[] = $title;
				}
			}
		}

		MediaWikiServices::getInstance()->getGenderCache()->doTitlesArray( $titles );

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
			$this->watchTitles( $toWatch );
			$this->unwatchTitles( $toUnwatch );
			$this->getUser()->invalidateCache();

			if ( count( $toWatch ) > 0 || count( $toUnwatch ) > 0 ) {
				$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			if ( count( $toWatch ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-added' )
					->numParams( count( $toWatch ) )->parse();
				$this->showTitles( $toWatch, $this->successMessage );
			}

			if ( count( $toUnwatch ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed' )
					->numParams( count( $toUnwatch ) )->parse();
				$this->showTitles( $toUnwatch, $this->successMessage );
			}
		} else {

			if ( count( $current ) === 0 ) {
				return false;
			}

			$this->clearUserWatchedItems( $current, 'raw' );
			$this->showTitles( $current, $this->successMessage );
		}

		return true;
	}

	public function submitClear( $data ) {
		$current = $this->getWatchlist();
		$this->clearUserWatchedItems( $current, 'clear' );
		$this->showTitles( $current, $this->successMessage );
		return true;
	}

	/**
	 * @param array $current
	 * @param string $messageFor 'raw' or 'clear'
	 */
	private function clearUserWatchedItems( $current, $messageFor ) {
		$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		if ( $watchedItemStore->clearUserWatchedItems( $this->getUser() ) ) {
			$this->successMessage = $this->msg( 'watchlistedit-' . $messageFor . '-done' )->parse();
			$this->successMessage .= ' ' . $this->msg( 'watchlistedit-' . $messageFor . '-removed' )
					->numParams( count( $current ) )->parse();
			$this->getUser()->invalidateCache();
		} else {
			$watchedItemStore->clearUserWatchedItemsUsingJobQueue( $this->getUser() );
			$this->successMessage = $this->msg( 'watchlistedit-clear-jobqueue' )->parse();
		}
	}

	/**
	 * Print out a list of linked titles
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 * @param string $output
	 */
	private function showTitles( $titles, &$output ) {
		$talk = $this->msg( 'talkpagelinktext' )->text();
		// Do a batch existence check
		$batch = new LinkBatch();
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

		$watchedItems = MediaWikiServices::getInstance()->getWatchedItemStore()->getWatchedItemsForUser(
			$this->getUser(),
			[ 'forWrite' => $this->getRequest()->wasPosted() ]
		);

		if ( $watchedItems ) {
			/** @var Title[] $titles */
			$titles = [];
			foreach ( $watchedItems as $watchedItem ) {
				$namespace = $watchedItem->getLinkTarget()->getNamespace();
				$dbKey = $watchedItem->getLinkTarget()->getDBkey();
				$title = Title::makeTitleSafe( $namespace, $dbKey );

				if ( $this->checkTitle( $title, $namespace, $dbKey )
					&& !$title->isTalkPage()
				) {
					$titles[] = $title;
				}
			}

			MediaWikiServices::getInstance()->getGenderCache()->doTitlesArray( $titles );

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

		$watchedItems = MediaWikiServices::getInstance()->getWatchedItemStore()
			->getWatchedItemsForUser( $this->getUser(), [ 'sort' => WatchedItemStore::SORT_ASC ] );

		$lb = new LinkBatch();

		foreach ( $watchedItems as $watchedItem ) {
			$namespace = $watchedItem->getLinkTarget()->getNamespace();
			$dbKey = $watchedItem->getLinkTarget()->getDBkey();
			$lb->add( $namespace, $dbKey );
			if ( !MWNamespace::isTalk( $namespace ) ) {
				$titles[$namespace][$dbKey] = 1;
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
		if ( !count( $this->badItems ) ) {
			return; // nothing to do
		}

		$user = $this->getUser();
		$badItems = $this->badItems;
		DeferredUpdates::addCallableUpdate( function () use ( $user, $badItems ) {
			$store = MediaWikiServices::getInstance()->getWatchedItemStore();
			foreach ( $badItems as $row ) {
				list( $title, $namespace, $dbKey ) = $row;
				$action = $title ? 'cleaning up' : 'deleting';
				wfDebug( "User {$user->getName()} has broken watchlist item " .
					"ns($namespace):$dbKey, $action.\n" );

				$store->removeWatch( $user, new TitleValue( (int)$namespace, $dbKey ) );
				// Can't just do an UPDATE instead of DELETE/INSERT due to unique index
				if ( $title ) {
					$user->addWatch( $title );
				}
			}
		} );
	}

	/**
	 * Add a list of targets to a user's watchlist
	 *
	 * @param string[]|LinkTarget[] $targets
	 */
	private function watchTitles( $targets ) {
		$expandedTargets = [];
		foreach ( $targets as $target ) {
			if ( !$target instanceof LinkTarget ) {
				try {
					$target = $this->titleParser->parseTitle( $target, NS_MAIN );
				}
				catch ( MalformedTitleException $e ) {
					continue;
				}
			}

			$ns = $target->getNamespace();
			$dbKey = $target->getDBkey();
			$expandedTargets[] = new TitleValue( MWNamespace::getSubject( $ns ), $dbKey );
			$expandedTargets[] = new TitleValue( MWNamespace::getTalk( $ns ), $dbKey );
		}

		MediaWikiServices::getInstance()->getWatchedItemStore()->addWatchBatchForUser(
			$this->getUser(),
			$expandedTargets
		);
	}

	/**
	 * Remove a list of titles from a user's watchlist
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 */
	private function unwatchTitles( $titles ) {
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();

		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$store->removeWatch( $this->getUser(), $title->getSubjectPage() );
				$store->removeWatch( $this->getUser(), $title->getTalkPage() );

				$page = WikiPage::factory( $title );
				Hooks::run( 'UnwatchArticleComplete', [ $this->getUser(), &$page ] );
			}
		}
	}

	public function submitNormal( $data ) {
		$removed = [];

		foreach ( $data as $titles ) {
			$this->unwatchTitles( $titles );
			$removed = array_merge( $removed, $titles );
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
		Hooks::run(
			'WatchlistEditorBeforeFormRender',
			[ &$watchlistInfo ]
		);

		foreach ( $watchlistInfo as $namespace => $pages ) {
			$options = [];

			foreach ( array_keys( $pages ) as $dbkey ) {
				$title = Title::makeTitleSafe( $namespace, $dbkey );

				if ( $this->checkTitle( $title, $namespace, $dbkey ) ) {
					$text = $this->buildRemoveLine( $title );
					$options[$text] = $title->getPrefixedText();
					$count++;
				}
			}

			// checkTitle can filter some options out, avoid empty sections
			if ( count( $options ) > 0 ) {
				$fields['TitlesNs' . $namespace] = [
					'class' => EditWatchlistCheckboxSeriesField::class,
					'options' => $options,
					'section' => "ns$namespace",
				];
			}
		}
		$this->cleanupWatchlist();

		if ( count( $fields ) > 1 && $count > 30 ) {
			$this->toc = Linker::tocIndent();
			$tocLength = 0;
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();

			foreach ( $fields as $data ) {
				# strip out the 'ns' prefix from the section name:
				$ns = substr( $data['section'], 2 );

				$nsText = ( $ns == NS_MAIN )
					? $this->msg( 'blanknamespace' )->escaped()
					: htmlspecialchars( $contLang->getFormattedNsText( $ns ) );
				$this->toc .= Linker::tocLine( "editwatchlist-{$data['section']}", $nsText,
					$this->getLanguage()->formatNum( ++$tocLength ), 1 ) . Linker::tocLineEnd();
			}

			$this->toc = Linker::tocList( $this->toc );
		} else {
			$this->toc = false;
		}

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = new EditWatchlistNormalHTMLForm( $fields, $context );
		$form->setSubmitTextMsg( 'watchlistedit-normal-submit' );
		$form->setSubmitDestructive();
		# Used message keys:
		# 'accesskey-watchlistedit-normal-submit', 'tooltip-watchlistedit-normal-submit'
		$form->setSubmitTooltip( 'watchlistedit-normal-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-normal-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-normal-explain' )->parse() );
		$form->setSubmitCallback( [ $this, 'submitNormal' ] );

		return $form;
	}

	/**
	 * Build the label for a checkbox, with a link to the title, and various additional bits
	 *
	 * @param Title $title
	 * @return string
	 */
	private function buildRemoveLine( $title ) {
		$linkRenderer = $this->getLinkRenderer();
		$link = $linkRenderer->makeLink( $title );

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

		if ( $title->getNamespace() == NS_USER && !$title->isSubpage() ) {
			$tools['contributions'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				$this->msg( 'contribslink' )->text()
			);
		}

		Hooks::run(
			'WatchlistEditorBuildRemoveLine',
			[ &$tools, $title, $title->isRedirect(), $this->getSkin(), &$link ]
		);

		if ( $title->isRedirect() ) {
			// Linker already makes class mw-redirect, so this is redundant
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}

		return $link . ' ' .
			$this->msg( 'parentheses' )->rawParams( $this->getLanguage()->pipeList( $tools ) )->escaped();
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
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'raw' ) ); // Reset subpage
		$form = new OOUIHTMLForm( $fields, $context );
		$form->setSubmitTextMsg( 'watchlistedit-raw-submit' );
		# Used message keys: 'accesskey-watchlistedit-raw-submit', 'tooltip-watchlistedit-raw-submit'
		$form->setSubmitTooltip( 'watchlistedit-raw-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-raw-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-raw-explain' )->parse() );
		$form->setSubmitCallback( [ $this, 'submitRaw' ] );

		return $form;
	}

	/**
	 * Get a form for clearing the watchlist
	 *
	 * @return HTMLForm
	 */
	protected function getClearForm() {
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'clear' ) ); // Reset subpage
		$form = new OOUIHTMLForm( [], $context );
		$form->setSubmitTextMsg( 'watchlistedit-clear-submit' );
		# Used message keys: 'accesskey-watchlistedit-clear-submit', 'tooltip-watchlistedit-clear-submit'
		$form->setSubmitTooltip( 'watchlistedit-clear-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-clear-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-clear-explain' )->parse() );
		$form->setSubmitCallback( [ $this, 'submitClear' ] );
		$form->setSubmitDestructive();

		return $form;
	}

	/**
	 * Determine whether we are editing the watchlist, and if so, what
	 * kind of editing operation
	 *
	 * @param WebRequest $request
	 * @param string $par
	 * @return int
	 */
	public static function getMode( $request, $par ) {
		$mode = strtolower( $request->getVal( 'action', $par ) );

		switch ( $mode ) {
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
				return false;
		}
	}

	/**
	 * Build a set of links for convenient navigation
	 * between watchlist viewing and editing modes
	 *
	 * @param Language $lang
	 * @param LinkRenderer|null $linkRenderer
	 * @return string
	 */
	public static function buildTools( $lang, LinkRenderer $linkRenderer = null ) {
		if ( !$lang instanceof Language ) {
			// back-compat where the first parameter was $unused
			global $wgLang;
			$lang = $wgLang;
		}
		if ( !$linkRenderer ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		}

		$tools = [];
		$modes = [
			'view' => [ 'Watchlist', false ],
			'edit' => [ 'EditWatchlist', false ],
			'raw' => [ 'EditWatchlist', 'raw' ],
			'clear' => [ 'EditWatchlist', 'clear' ],
		];

		foreach ( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$tools[] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMessage( "watchlisttools-{$mode}" )->text()
			);
		}

		return Html::rawElement(
			'span',
			[ 'class' => 'mw-watchlist-toollinks' ],
			wfMessage( 'parentheses' )->rawParams( $lang->pipeList( $tools ) )->escaped()
		);
	}
}
