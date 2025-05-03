<?php
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\PageLookup;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as ParsoidSiteConfig;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
	private ParserOutputAccess $parserOutputAccess;
	private PageLookup $pageLookup;
	private RevisionLookup $revisionLookup;
	private ParsoidSiteConfig $parsoidSiteConfig;

	public function __construct() {
		parent::__construct();

		$this->addDescription(
			'Populate parser cache with parsoid output. By default, script attempt to run' .
			'for supported content model pages (in a specified batch if provided)'
		);
		$this->addOption(
			'force',
			'Re-parse pages even if the cached entry seems up to date',
			false,
			false
		);
		$this->addOption( 'start-from', 'Start from this page ID', false, true );
		$this->addOption( 'namespace', 'Filter pages in this namespace', false, true );
		$this->setBatchSize( 100 );
	}

	private function getPageLookup(): PageLookup {
		$this->pageLookup ??= $this->getServiceContainer()->getPageStore();
		return $this->pageLookup;
	}

	private function getRevisionLookup(): RevisionLookup {
		$this->revisionLookup ??= $this->getServiceContainer()->getRevisionLookup();
		return $this->revisionLookup;
	}

	private function getParserOutputAccess(): ParserOutputAccess {
		$this->parserOutputAccess ??= $this->getServiceContainer()->getParserOutputAccess();
		return $this->parserOutputAccess;
	}

	private function getParsoidSiteConfig(): ParsoidSiteConfig {
		$this->parsoidSiteConfig ??= $this->getServiceContainer()->getParsoidSiteConfig();
		return $this->parsoidSiteConfig;
	}

	private function getQueryBuilder(): SelectQueryBuilder {
		$dbr = $this->getReplicaDB();

		return $dbr->newSelectQueryBuilder()
			->select( [ 'page_id' ] )
			->from( 'page' )
			->caller( __METHOD__ )
			->orderBy( 'page_id', SelectQueryBuilder::SORT_ASC );
	}

	private function parse(
		PageRecord $page,
		RevisionRecord $revision
	): Status {
		$popts = ParserOptions::newFromAnon();
		$popts->setUseParsoid();
		try {
			return $this->getParserOutputAccess()->getParserOutput(
				$page,
				$popts,
				$revision,
				$this->forceParse
			);
		} catch ( ClientError $e ) {
			return Status::newFatal( 'parsoid-client-error', $e->getMessage() );
		} catch ( ResourceLimitExceededException $e ) {
			return Status::newFatal( 'parsoid-resource-limit-exceeded', $e->getMessage() );
		}
	}

	/**
	 * NamespaceInfo::getCanonicalIndex() requires the namespace to be in lowercase,
	 * so let's do some normalization and return its canonical index.
	 *
	 * @param string $namespace The namespace string from the command line
	 * @return int The canonical index of the namespace
	 */
	private function normalizeNamespace( string $namespace ): int {
		return $this->getServiceContainer()->getNamespaceInfo()
			->getCanonicalIndex( strtolower( $namespace ) );
	}

	/**
	 * Populate parser cache with parsoid output.
	 *
	 * @return bool
	 */
	public function execute() {
		$force = $this->getOption( 'force' );
		$startFrom = $this->getOption( 'start-from' );

		// We need the namespace index instead of the name to perform the query
		// on, because that's what the page table stores (in the page_namespace field).
		$namespaceIndex = null;
		$namespace = $this->getOption( 'namespace' );
		if ( $namespace !== null ) {
			$namespaceIndex = $this->normalizeNamespace( $namespace );
		}

		if ( $force !== null ) {
			// If --force is supplied, for a parse for supported pages or supported
			// pages in the specified batch.
			$this->forceParse = ParserOutputAccess::OPT_FORCE_PARSE;
		}

		$startFrom = (int)$startFrom;

		$this->output( "\nWarming parsoid parser cache with Parsoid output...\n\n" );
		while ( true ) {
			$query = $this->getQueryBuilder();
			if ( $namespaceIndex !== null ) {
				$query = $query->where( [ 'page_namespace' => $namespaceIndex ] );
			}
			$query = $query->where( $this->getReplicaDB()->expr( 'page_id', '>=', $startFrom ) )
				->limit( $this->getBatchSize() );

			$result = $query->fetchResultSet();

			if ( !$result->numRows() ) {
				break;
			}

			$currentBatch = $startFrom + ( $this->getBatchSize() - 1 );
			$this->output( "\n\nBatch: $startFrom - $currentBatch\n----\n" );

			// Look through pages by pageId and populate the parserCache
			foreach ( $result as $row ) {
				$page = $this->getPageLookup()->getPageById( $row->page_id );
				$startFrom = ( (int)$row->page_id + 1 );

				if ( $page === null ) {
					$this->output( "\n[Skipped] Page ID: $row->page_id not found.\n" );
					continue;
				}

				$latestRevision = $page->getLatest();
				$revision = $this->getRevisionLookup()->getRevisionById( $latestRevision );
				$mainSlot = $revision->getSlot( SlotRecord::MAIN );

				// POA will write a dummy output to PC, but we don't want that here. Just skip!
				if ( !$this->getParsoidSiteConfig()->supportsContentModel( $mainSlot->getModel() ) ) {
					$this->output(
						'[Skipped] Content model "' .
						$mainSlot->getModel() .
						"\" not supported for page ID: $row->page_id.\n"
					);
					continue;
				}

				$status = $this->parse( $page, $revision );
				if ( !$status->isOK() ) {
					$this->output(
						__METHOD__ .
						": Error parsing page ID: $row->page_id or writing to parser cache\n"
					);
					continue;
				}

				$this->output( "[Done] Page ID: $row->page_id ✔️\n" );
			}
			$this->waitForReplication();
		}

		$this->output( "\nDone pre-warming parsoid parser cache...\n" );

		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = PrewarmParsoidParserCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
