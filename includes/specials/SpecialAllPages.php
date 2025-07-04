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

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageStore;
use MediaWiki\SpecialPage\IncludableSpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use SearchEngineFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Implements Special:Allpages
 *
 * @ingroup SpecialPage
 * @todo Rewrite using IndexPager
 */
class SpecialAllPages extends IncludableSpecialPage {

	/**
	 * Maximum number of pages to show on single subpage.
	 *
	 * @var int
	 */
	protected $maxPerPage = 345;

	/**
	 * Determines, which message describes the input field 'nsfrom'.
	 *
	 * @var string
	 */
	protected $nsfromMsg = 'allpagesfrom';

	private IConnectionProvider $dbProvider;
	private SearchEngineFactory $searchEngineFactory;
	private PageStore $pageStore;

	public function __construct(
		?IConnectionProvider $dbProvider = null,
		?SearchEngineFactory $searchEngineFactory = null,
		?PageStore $pageStore = null
	) {
		parent::__construct( 'Allpages' );
		// This class is extended and therefore falls back to global state - T265309
		$services = MediaWikiServices::getInstance();
		$this->dbProvider = $dbProvider ?? $services->getConnectionProvider();
		$this->searchEngineFactory = $searchEngineFactory ?? $services->getSearchEngineFactory();
		$this->pageStore = $pageStore ?? $services->getPageStore();
	}

	/**
	 * Entry point : initialise variables and call subfunctions.
	 *
	 * @param string|null $par Becomes "FOO" when called like Special:Allpages/FOO
	 */
	public function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();
		$out->getMetadata()->setPreventClickjacking( false );

		# GET values
		$from = $request->getVal( 'from', null );
		$to = $request->getVal( 'to', null );
		$namespace = $request->getInt( 'namespace' );

		$miserMode = (bool)$this->getConfig()->get( MainConfigNames::MiserMode );

		// Redirects filter is disabled in MiserMode
		$hideredirects = $request->getBool( 'hideredirects', false ) && !$miserMode;

		$namespaces = $this->getLanguage()->getNamespaces();

		$out->setPageTitleMsg(
			( $namespace > 0 && array_key_exists( $namespace, $namespaces ) ) ?
				$this->msg( 'allinnamespace' )->plaintextParams( str_replace( '_', ' ', $namespaces[$namespace] ) ) :
				$this->msg( 'allarticles' )
		);
		$out->addModuleStyles( 'mediawiki.special' );

		if ( $par !== null ) {
			$this->showChunk( $namespace, $par, $to, $hideredirects );
		} elseif ( $from !== null && $to === null ) {
			$this->showChunk( $namespace, $from, $to, $hideredirects );
		} else {
			$this->showToplevel( $namespace, $from, $to, $hideredirects );
		}
	}

	/**
	 * Outputs the HTMLForm used on this page
	 *
	 * @param int $namespace A namespace constant (default NS_MAIN).
	 * @param string $from DbKey we are starting listing at.
	 * @param string $to DbKey we are ending listing at.
	 * @param bool $hideRedirects Don't show redirects  (default false)
	 */
	protected function outputHTMLForm( $namespace = NS_MAIN,
		$from = '', $to = '', $hideRedirects = false
	) {
		$miserMode = (bool)$this->getConfig()->get( MainConfigNames::MiserMode );
		$formDescriptor = [
			'from' => [
				'type' => 'text',
				'name' => 'from',
				'id' => 'nsfrom',
				'size' => 30,
				'label-message' => 'allpagesfrom',
				'default' => str_replace( '_', ' ', $from ),
			],
			'to' => [
				'type' => 'text',
				'name' => 'to',
				'id' => 'nsto',
				'size' => 30,
				'label-message' => 'allpagesto',
				'default' => str_replace( '_', ' ', $to ),
			],
			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'id' => 'namespace',
				'label-message' => 'namespace',
				'all' => null,
				'default' => $namespace,
			],
			'hideredirects' => [
				'type' => 'check',
				'name' => 'hideredirects',
				'id' => 'hidredirects',
				'label-message' => 'allpages-hide-redirects',
				'value' => $hideRedirects,
			],
		];

		if ( $miserMode ) {
			unset( $formDescriptor['hideredirects'] );
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->setWrapperLegendMsg( 'allpages' )
			->setSubmitTextMsg( 'allpagessubmit' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * @param int $namespace (default NS_MAIN)
	 * @param string|null $from List all pages from this name
	 * @param string|null $to List all pages to this name
	 * @param bool $hideredirects Don't show redirects (default false)
	 */
	private function showToplevel(
		$namespace = NS_MAIN, $from = null, $to = null, $hideredirects = false
	) {
		$from = $from ? Title::makeTitleSafe( $namespace, $from ) : null;
		$to = $to ? Title::makeTitleSafe( $namespace, $to ) : null;
		$from = ( $from && $from->isLocal() ) ? $from->getDBkey() : null;
		$to = ( $to && $to->isLocal() ) ? $to->getDBkey() : null;

		$this->showChunk( $namespace, $from, $to, $hideredirects );
	}

	/**
	 * @param int $namespace Namespace (Default NS_MAIN)
	 * @param string|null $from List all pages from this name (default null)
	 * @param string|null $to List all pages to this name (default null)
	 * @param bool $hideredirects Don't show redirects (default false)
	 */
	private function showChunk(
		$namespace = NS_MAIN, $from = null, $to = null, $hideredirects = false
	) {
		$output = $this->getOutput();

		$fromList = $this->getNamespaceKeyAndText( $namespace, $from );
		$toList = $this->getNamespaceKeyAndText( $namespace, $to );
		$namespaces = $this->getContext()->getLanguage()->getNamespaces();
		$n = 0;
		$prevTitle = null;

		if ( !$fromList || !$toList ) {
			$out = $this->msg( 'allpagesbadtitle' )->parseAsBlock();
		} elseif ( !array_key_exists( $namespace, $namespaces ) ) {
			// Show errormessage and reset to NS_MAIN
			$out = $this->msg( 'allpages-bad-ns', $namespace )->parse();
			$namespace = NS_MAIN;
		} else {
			[ $namespace, $fromKey, $from ] = $fromList;
			[ , $toKey, $to ] = $toList;

			$dbr = $this->dbProvider->getReplicaDatabase();
			$filterConds = [ 'page_namespace' => $namespace ];
			if ( $hideredirects ) {
				$filterConds['page_is_redirect'] = 0;
			}

			$conds = $filterConds;
			$conds[] = $dbr->expr( 'page_title', '>=', $fromKey );
			if ( $toKey !== "" ) {
				$conds[] = $dbr->expr( 'page_title', '<=', $toKey );
			}

			$res = $this->pageStore->newSelectQueryBuilder()
				->where( $conds )
				->caller( __METHOD__ )
				->orderBy( 'page_title' )
				->limit( $this->maxPerPage + 1 )
				->useIndex( 'page_name_title' )
				->fetchPageRecords();

			// Eagerly fetch the set of pages to be displayed and warm up LinkCache (T328174).
			// Note that we can't use fetchPageRecordArray() here as that returns an array keyed
			// by page IDs; we need a simple sequence.
			/** @var ExistingPageRecord[] $pages */
			$pages = iterator_to_array( $res );

			$linkRenderer = $this->getLinkRenderer();
			if ( count( $pages ) > 0 ) {
				$out = Html::openElement( 'ul', [ 'class' => 'mw-allpages-chunk' ] );

				while ( $n < $this->maxPerPage && $n < count( $pages ) ) {
					$page = $pages[$n];
					$attributes = $page->isRedirect() ? [ 'class' => 'allpagesredirect' ] : [];

					$out .= Html::rawElement( 'li', $attributes, $linkRenderer->makeKnownLink( $page ) ) . "\n";
					$n++;
				}
				$out .= Html::closeElement( 'ul' );

				if ( count( $pages ) > 2 ) {
					// Only apply CSS column styles if there's more than 2 entries.
					// Otherwise, rendering is broken as "mw-allpages-body"'s CSS column count is 3.
					$out = Html::rawElement( 'div', [ 'class' => 'mw-allpages-body' ], $out );
				}
			} else {
				$out = '';
			}

			if ( $fromKey !== '' && !$this->including() ) {
				# Get the first title from previous chunk
				$prevConds = $filterConds;
				$prevConds[] = $dbr->expr( 'page_title', '<', $fromKey );
				$prevKey = $dbr->newSelectQueryBuilder()
					->select( 'page_title' )
					->from( 'page' )
					->where( $prevConds )
					->orderBy( 'page_title', SelectQueryBuilder::SORT_DESC )
					->offset( $this->maxPerPage - 1 )
					->caller( __METHOD__ )->fetchField();

				if ( $prevKey === false ) {
					# The previous chunk is not complete, need to link to the very first title
					# available in the database
					$prevKey = $dbr->newSelectQueryBuilder()
						->select( 'page_title' )
						->from( 'page' )
						->where( $prevConds )
						->orderBy( 'page_title' )
						->caller( __METHOD__ )->fetchField();
				}

				if ( $prevKey !== false ) {
					$prevTitle = Title::makeTitle( $namespace, $prevKey );
				}
			}
		}

		if ( $this->including() ) {
			$output->addHTML( $out );
			return;
		}

		$navLinks = [];
		$self = $this->getPageTitle();

		$linkRenderer = $this->getLinkRenderer();
		// Generate a "previous page" link if needed
		if ( $prevTitle ) {
			$query = [ 'from' => $prevTitle->getText() ];

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$navLinks[] = $linkRenderer->makeKnownLink(
				$self,
				$this->msg( 'prevpage', $prevTitle->getText() )->text(),
				[],
				$query
			);

		}

		// Generate a "next page" link if needed
		if ( $n === $this->maxPerPage && isset( $pages[$n] ) ) {
			# $t is the first link of the next chunk
			$t = TitleValue::newFromPage( $pages[$n] );
			$query = [ 'from' => $t->getText() ];

			if ( $namespace ) {
				$query['namespace'] = $namespace;
			}

			if ( $hideredirects ) {
				$query['hideredirects'] = $hideredirects;
			}

			$navLinks[] = $linkRenderer->makeKnownLink(
				$self,
				$this->msg( 'nextpage', $t->getText() )->text(),
				[],
				$query
			);
		}

		$this->outputHTMLForm( $namespace, $from ?? '', $to ?? '', $hideredirects );

		if ( count( $navLinks ) ) {
			// Add pagination links
			$pagination = Html::rawElement( 'div',
				[ 'class' => 'mw-allpages-nav' ],
				$this->getLanguage()->pipeList( $navLinks )
			);

			$output->addHTML( $pagination );
			$out .= Html::element( 'hr' ) . $pagination; // Footer
		}

		$output->addHTML( $out );
	}

	/**
	 * @param int $ns The namespace of the article
	 * @param string $text The name of the article
	 * @return array|null [ int namespace, string dbkey, string pagename ] or null on error
	 */
	protected function getNamespaceKeyAndText( $ns, $text ) {
		if ( $text == '' ) {
			# shortcut for common case
			return [ $ns, '', '' ];
		}

		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return [ $t->getNamespace(), $t->getDBkey(), $t->getText() ];
		} elseif ( $t ) {
			return null;
		}

		# try again, in case the problem was an empty pagename
		$text = preg_replace( '/(#|$)/', 'X$1', $text );
		$t = Title::makeTitleSafe( $ns, $text );
		if ( $t && $t->isLocal() ) {
			return [ $t->getNamespace(), '', '' ];
		} else {
			return null;
		}
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pages';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialAllPages::class, 'SpecialAllPages' );
