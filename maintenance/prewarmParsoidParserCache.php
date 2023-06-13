<?php
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageLookup;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\Rdbms\SelectQueryBuilder;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script for populating parser cache with parsoid output.
 *
 * @since 1.41
 *
 * @license GPL-2.0-or-later
 * @author Richika Rana
 */
class PrewarmParsoidParserCache extends Maintenance {
	private int $forceParse = 0;
	private ParsoidOutputAccess $parsoidOutputAccess;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;

	public function __construct() {
		parent::__construct();

		$this->addDescription(
			'Populate Parser cache with parsoid output. By default, script will run for all pages.'
		);
		$this->addOption(
			'force',
			'This will force parse all pages in the wiki',
			false,
			false
		);
	}

	private function getPageLookup(): PageLookup {
		$this->pageLookup = MediaWikiServices::getInstance()->getPageStore();
		return $this->pageLookup;
	}

	private function getRevisionLookup(): RevisionLookup {
		$this->revisionLookup = MediaWikiServices::getInstance()->getRevisionLookup();
		return $this->revisionLookup;
	}

	private function getParsoidOutputAccess(): ParsoidOutputAccess {
		$this->parsoidOutputAccess = MediaWikiServices::getInstance()->getParsoidOutputAccess();
		return $this->parsoidOutputAccess;
	}

	private function getQuery(): SelectQueryBuilder {
		$dbr = $this->getDB( DB_REPLICA );

		$query = $dbr->newSelectQueryBuilder()
			->select( [ 'page_id' ] )
			->from( 'page' )
			->caller( __METHOD__ )
			->orderBy( 'page_id', SelectQueryBuilder::SORT_ASC );

		return $query;
	}

	/**
	 * Populate parser cache with parsoid output.
	 *
	 * @return bool
	 */
	public function execute() {
		// If --force is supplied, for a parse
		$force = $this->getOption( 'force' );

		if ( $force !== null ) {
			$this->forceParse = ParsoidOutputAccess::OPT_FORCE_PARSE;
		}

		$query = $this->getQuery();
		$result = $query->fetchResultSet();

		// Look through pages by pageId and populate the parserCache
		foreach ( $result as $row ) {
			$page = $this->getPageLookup()->getPageById( $row->page_id );
			if ( $page === null ) {
				$this->output( "Page with ID $row->page_id not found. Skipping this page ID..." );
				continue;
			}

			$parserOpts = ParserOptions::newFromAnon();
			$latestRevision = $page->getLatest();
			$revision = $this->getRevisionLookup()->getRevisionById( $latestRevision );
			$mainSlot = $revision->getSlot( SlotRecord::MAIN );

			// POA will write a dummy output to PC, but we don't want that here. Just skip!
			if ( !$this->getParsoidOutputAccess()->supportsContentModel( $mainSlot->getModel() ) ) {
				$this->output( __METHOD__ .
					': Parsoid does not support content model "' .
					$mainSlot->getModel() .
					"\". Skipping this page with ID: $row->page_id... \n"
				);
				continue;
			}

			$this->output( "\nGenerating and writing output to parser cache for page ID: $row->page_id \n" );
			$status = $this->parsoidOutputAccess->getParserOutput(
				$page,
				$parserOpts,
				$revision,
				$this->forceParse | ParsoidOutputAccess::OPT_LOG_LINT_DATA
			);

			if ( !$status->isOK() ) {
				$this->output( __METHOD__ . ": Error writing to parser cache for page ID: $row->page_id \n" );
				continue;
			}
			$this->output( "Parser cache has been populated with Parsoid output for page ID: $row->page_id \n\n" );
		}
		$this->output( "\nDone populating for all pages...\n" );

		return true;
	}
}

$maintClass = PrewarmParsoidParserCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
