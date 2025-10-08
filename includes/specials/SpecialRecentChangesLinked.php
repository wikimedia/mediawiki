<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\MessageParser;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory;
use MediaWiki\RecentChanges\RecentChangeFactory;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use SearchEngineFactory;

/**
 * This is to display changes made to all articles linked in an article.
 *
 * @ingroup RecentChanges
 * @ingroup SpecialPage
 */
class SpecialRecentChangesLinked extends SpecialRecentChanges {
	/** @var bool|Title */
	protected $rclTargetTitle;

	private SearchEngineFactory $searchEngineFactory;

	public function __construct(
		WatchedItemStoreInterface $watchedItemStore,
		MessageParser $messageParser,
		UserOptionsLookup $userOptionsLookup,
		SearchEngineFactory $searchEngineFactory,
		UserIdentityUtils $userIdentityUtils,
		TempUserConfig $tempUserConfig,
		RecentChangeFactory $recentChangeFactory,
		ChangesListQueryFactory $changesListQueryFactory,
	) {
		parent::__construct(
			$watchedItemStore,
			$messageParser,
			$userOptionsLookup,
			$userIdentityUtils,
			$tempUserConfig,
			$recentChangeFactory,
			$changesListQueryFactory,
		);
		$this->mName = 'Recentchangeslinked';
		$this->searchEngineFactory = $searchEngineFactory;
	}

	/** @inheritDoc */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$opts->add( 'target', '' );
		$opts->add( 'showlinkedto', false );

		return $opts;
	}

	/** @inheritDoc */
	public function parseParameters( $par, FormOptions $opts ) {
		$opts['target'] = $par;
	}

	/**
	 * @inheritDoc
	 */
	protected function modifyQuery( ChangesListQuery $query, FormOptions $opts ) {
		$target = $opts['target'];
		$showlinkedto = $opts['showlinkedto'];

		if ( $target === '' ) {
			$query->forceEmptySet();
			return;
		}
		$outputPage = $this->getOutput();
		$title = Title::newFromText( $target );
		if ( !$title || $title->isExternal() ) {
			$outputPage->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
			$outputPage->addHTML(
				Html::errorBox( $this->msg( 'allpagesbadtitle' )->parse(), '', 'mw-recentchangeslinked-errorbox' )
			);
			$query->forceEmptySet();
			return;
		}

		$outputPage->setPageTitleMsg(
			$this->msg( 'recentchangeslinked-title' )->plaintextParams( $title->getPrefixedText() )
		);

		$ns = $title->getNamespace();
		if ( $ns === NS_CATEGORY && !$showlinkedto ) {
			// special handling for categories
			// XXX: should try to make this less kludgy
			$link_tables = [ 'categorylinks' ];
			$showlinkedto = true;
		} else {
			// for now, always join on these tables; really should be configurable as in whatlinkshere
			$link_tables = [ 'pagelinks', 'templatelinks' ];
			// imagelinks only contains links to pages in NS_FILE
			if ( $ns === NS_FILE || !$showlinkedto ) {
				$link_tables[] = 'imagelinks';
			}
		}

		$query->requireLink(
			$showlinkedto ? ChangesListQuery::LINKS_TO : ChangesListQuery::LINKS_FROM,
			$link_tables,
			$title
		);
	}

	public function setTopText( FormOptions $opts ) {
		$target = $this->getTargetTitle();
		if ( $target ) {
			$this->getOutput()->addBacklinkSubtitle( $target );
			$this->getSkin()->setRelevantTitle( $target );
		}
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function getExtraOptions( $opts ) {
		$extraOpts = parent::getExtraOptions( $opts );

		$opts->consumeValues( [ 'showlinkedto', 'target' ] );

		$extraOpts['target'] = [ $this->msg( 'recentchangeslinked-page' )->escaped(),
			Html::input( 'target', str_replace( '_', ' ', $opts['target'] ), 'text', [ 'size' => 40 ] ) . ' ' .
			Html::check( 'showlinkedto', $opts['showlinkedto'], [ 'id' => 'showlinkedto' ] ) . ' ' .
			Html::label( $this->msg( 'recentchangeslinked-to' )->text(), 'showlinkedto' ) ];

		$this->addHelpLink( 'Help:Related changes' );
		return $extraOpts;
	}

	/**
	 * @return Title
	 */
	private function getTargetTitle() {
		if ( $this->rclTargetTitle === null ) {
			$opts = $this->getOptions();
			if ( isset( $opts['target'] ) && $opts['target'] !== '' ) {
				$this->rclTargetTitle = Title::newFromText( $opts['target'] );
			} else {
				$this->rclTargetTitle = false;
			}
		}

		return $this->rclTargetTitle;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function outputNoResults() {
		$targetTitle = $this->getTargetTitle();
		if ( $targetTitle === false ) {
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => [ 'mw-changeslist-empty', 'mw-changeslist-notargetpage' ] ],
					$this->msg( 'recentchanges-notargetpage' )->parse()
				)
			);
		} elseif ( !$targetTitle || $targetTitle->isExternal() ) {
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => [ 'mw-changeslist-empty', 'mw-changeslist-invalidtargetpage' ] ],
					$this->msg( 'allpagesbadtitle' )->parse()
				)
			);
		} else {
			parent::outputNoResults();
		}
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRecentChangesLinked::class, 'SpecialRecentChangesLinked' );
