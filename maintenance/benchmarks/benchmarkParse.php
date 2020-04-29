<?php
/**
 * Benchmark script for parse operations
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
 * @author Tim Starling <tstarling@wikimedia.org>
 * @ingroup Benchmark
 */

require __DIR__ . '/../Maintenance.php';

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * Maintenance script to benchmark how long it takes to parse a given title at an optionally
 * specified timestamp
 *
 * @since 1.23
 */
class BenchmarkParse extends Maintenance {
	/** @var string MediaWiki concatenated string timestamp (YYYYMMDDHHMMSS) */
	private $templateTimestamp = null;

	private $clearLinkCache = false;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/** @var array Cache that maps a Title DB key to revision ID for the requested timestamp */
	private $idCache = [];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark parse operation' );
		$this->addArg( 'title', 'The name of the page to parse' );
		$this->addOption( 'warmup', 'Repeat the parse operation this number of times to warm the cache',
			false, true );
		$this->addOption( 'loops', 'Number of times to repeat parse operation post-warmup',
			false, true );
		$this->addOption( 'page-time',
			'Use the version of the page which was current at the given time',
			false, true );
		$this->addOption( 'tpl-time',
			'Use templates which were current at the given time (except that moves and ' .
			'deletes are not handled properly)',
			false, true );
		$this->addOption( 'reset-linkcache', 'Reset the LinkCache after every parse.',
			false, false );
	}

	public function execute() {
		if ( $this->hasOption( 'tpl-time' ) ) {
			$this->templateTimestamp = wfTimestamp( TS_MW, strtotime( $this->getOption( 'tpl-time' ) ) );
			Hooks::register( 'BeforeParserFetchTemplateAndtitle', [ $this, 'onFetchTemplate' ] );
		}

		$this->clearLinkCache = $this->hasOption( 'reset-linkcache' );
		// Set as a member variable to avoid function calls when we're timing the parse
		$this->linkCache = MediaWikiServices::getInstance()->getLinkCache();

		$title = Title::newFromText( $this->getArg( 0 ) );
		if ( !$title ) {
			$this->error( "Invalid title" );
			exit( 1 );
		}

		$revLookup = MediaWikiServices::getInstance()->getRevisionLookup();
		if ( $this->hasOption( 'page-time' ) ) {
			$pageTimestamp = wfTimestamp( TS_MW, strtotime( $this->getOption( 'page-time' ) ) );
			$id = $this->getRevIdForTime( $title, $pageTimestamp );
			if ( !$id ) {
				$this->error( "The page did not exist at that time" );
				exit( 1 );
			}

			$revision = $revLookup->getRevisionById( $id );
		} else {
			$revision = $revLookup->getRevisionByTitle( $title );
		}

		if ( !$revision ) {
			$this->error( "Unable to load revision, incorrect title?" );
			exit( 1 );
		}

		$warmup = $this->getOption( 'warmup', 1 );
		for ( $i = 0; $i < $warmup; $i++ ) {
			$this->runParser( $revision );
		}

		$loops = $this->getOption( 'loops', 1 );
		if ( $loops < 1 ) {
			$this->fatalError( 'Invalid number of loops specified' );
		}
		$startUsage = getrusage();
		$startTime = microtime( true );
		for ( $i = 0; $i < $loops; $i++ ) {
			$this->runParser( $revision );
		}
		$endUsage = getrusage();
		$endTime = microtime( true );

		printf( "CPU time = %.3f s, wall clock time = %.3f s\n",
			// CPU time
			( $endUsage['ru_utime.tv_sec'] + $endUsage['ru_utime.tv_usec'] * 1e-6
			- $startUsage['ru_utime.tv_sec'] - $startUsage['ru_utime.tv_usec'] * 1e-6 ) / $loops,
			// Wall clock time
			( $endTime - $startTime ) / $loops
		);
	}

	/**
	 * Fetch the ID of the revision of a Title that occurred
	 *
	 * @param Title $title
	 * @param string $timestamp
	 * @return bool|string Revision ID, or false if not found or error
	 */
	private function getRevIdForTime( Title $title, $timestamp ) {
		$dbr = $this->getDB( DB_REPLICA );

		$id = $dbr->selectField(
			[ 'revision', 'page' ],
			'rev_id',
			[
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
				'rev_timestamp <= ' . $dbr->addQuotes( $timestamp )
			],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp DESC', 'LIMIT' => 1 ],
			[ 'revision' => [ 'JOIN', 'rev_page=page_id' ] ]
		);

		return $id;
	}

	/**
	 * Parse the text from a given Revision
	 *
	 * @param RevisionRecord $revision
	 */
	private function runParser( RevisionRecord $revision ) {
		$content = $revision->getContent( SlotRecord::MAIN );
		$title = Title::newFromLinkTarget( $revision->getPageAsLinkTarget() );

		$content->getParserOutput( $title, $revision->getId() );
		if ( $this->clearLinkCache ) {
			$this->linkCache->clear();
		}
	}

	/**
	 * Hook into the parser's revision ID fetcher. Make sure that the parser only
	 * uses revisions around the specified timestamp.
	 *
	 * @param Parser $parser
	 * @param Title $title
	 * @param bool &$skip
	 * @param string|bool &$id
	 * @return bool
	 */
	private function onFetchTemplate( Parser $parser, Title $title, &$skip, &$id ) {
		$pdbk = $title->getPrefixedDBkey();
		if ( !isset( $this->idCache[$pdbk] ) ) {
			$proposedId = $this->getRevIdForTime( $title, $this->templateTimestamp );
			$this->idCache[$pdbk] = $proposedId;
		}
		if ( $this->idCache[$pdbk] !== false ) {
			$id = $this->idCache[$pdbk];
		}

		return true;
	}
}

$maintClass = BenchmarkParse::class;
require RUN_MAINTENANCE_IF_MAIN;
