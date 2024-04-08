<?php
// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\RevisionRecord;

require_once __DIR__ . '/Maintenance.php';

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
		$services = MediaWikiServices::getInstance();
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
		$dbr = $this->getDB( DB_REPLICA );
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
				$prefixCond = [ 'page_namespace' => $ns ];
				if ( $prefixDBkey !== '' ) {
					$prefixCond[] = 'page_title ' . $dbr->buildLike( $prefixDBkey, $dbr->anyString() );
				}
				$orConds[] = $dbr->makeList( $prefixCond, LIST_AND );
			}
		}

		$conds = $orConds ? $dbr->makeList( $orConds, LIST_OR ) : [];
		$pageQuery = WikiPage::getQueryInfo();

		$res = $dbr->newSelectQueryBuilder()
			->queryInfo( $pageQuery )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			yield $this->wikiPageFactory->newFromTitle( $title );
		}
	}
}

$maintClass = GrepPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
