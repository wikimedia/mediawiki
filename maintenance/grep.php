<?php
// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
use MediaWiki\Content\TextContent;
use MediaWiki\Language\Language;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\StringUtils\StringUtils;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Search pages for a given regex
 *
 * @ingroup Maintenance
 */
class GrepPages extends Maintenance {
	/** @var Language */
	private $contLang;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Search the source text of pages for lines matching ' .
			'a given regex, and print the lines.' );
		$this->addOption( 'prefix',
			'Title prefix. Can be specified more than once. ' .
			'Use e.g. --prefix=Talk: to search an entire namespace.',
			false, true, false, true );
		$this->addOption( 'show-wiki', 'Add the wiki ID to the output' );
		$this->addOption( 'pages-with-matches',
			'Suppress normal output; instead print the title of each page ' .
			'from which output would normally have been printed.',
			false, false, 'l' );
		$this->addArg( 'regex', 'The regex to search for' );
	}

	private function init() {
		$services = $this->getServiceContainer();
		$this->contLang = $services->getContentLanguage();
		$this->wikiPageFactory = $services->getWikiPageFactory();
	}

	public function execute() {
		$this->init();

		$showWiki = $this->getOption( 'show-wiki' );
		$wikiId = WikiMap::getCurrentWikiId();
		$prefix = $this->getOption( 'prefix' );
		$regex = $this->getArg( 0 );
		$titleOnly = $this->hasOption( 'pages-with-matches' );

		if ( ( $regex[0] ?? '' ) === '/' ) {
			$delimRegex = $regex;
		} else {
			$delimRegex = '{' . $regex . '}';
		}

		foreach ( $this->findPages( $prefix ) as $page ) {
			$content = $page->getContent( RevisionRecord::RAW );
			$titleText = $page->getTitle()->getPrefixedDBkey();
			if ( !$content ) {
				$this->error( "Page has no content: $titleText" );
				continue;
			}
			if ( !$content instanceof TextContent ) {
				$this->error( "Page has a non-text content model: $titleText" );
				continue;
			}

			$text = $content->getText();

			if ( $titleOnly ) {
				if ( preg_match( $delimRegex, $text ) ) {
					if ( $showWiki ) {
						echo "$wikiId\t$titleText\n";
					} else {
						echo "$titleText\n";
					}
				}
			} else {
				foreach ( StringUtils::explode( "\n", $text ) as $lineNum => $line ) {
					$lineNum++;
					if ( preg_match( $delimRegex, $line ) ) {
						if ( $showWiki ) {
							echo "$wikiId\t$titleText:$lineNum:$line\n";
						} else {
							echo "$titleText:$lineNum:$line\n";
						}
					}
				}
			}
		}
	}

	public function findPages( $prefixes = null ) {
		$dbr = $this->getReplicaDB();
		$orConds = [];
		if ( $prefixes !== null ) {
			foreach ( $prefixes as $prefix ) {
				$colonPos = strpos( $prefix, ':' );
				if ( $colonPos !== false ) {
					$ns = $this->contLang->getNsIndex( substr( $prefix, 0, $colonPos ) );
					$prefixDBkey = substr( $prefix, $colonPos + 1 );
				} else {
					$ns = NS_MAIN;
					$prefixDBkey = $prefix;
				}
				$prefixExpr = $dbr->expr( 'page_namespace', '=', $ns );
				if ( $prefixDBkey !== '' ) {
					$prefixExpr = $prefixExpr->and(
						'page_title',
						IExpression::LIKE,
						new LikeValue( $prefixDBkey, $dbr->anyString() )
					);
				}
				$orConds[] = $prefixExpr;
			}
		}
		$lastId = 0;
		do {
			$res = $dbr->newSelectQueryBuilder()
				->queryInfo( WikiPage::getQueryInfo() )
				->where( $orConds ? $dbr->orExpr( $orConds ) : [] )
				->andWhere( $dbr->expr( 'page_id', '>', $lastId ) )
				->limit( 200 )
				->caller( __METHOD__ )
				->fetchResultSet();
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				yield $this->wikiPageFactory->newFromTitle( $title );
				$lastId = $row->page_id;
			}
		} while ( $res->numRows() );
	}
}

// @codeCoverageIgnoreStart
$maintClass = GrepPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
