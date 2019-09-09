<?php
/**
 * Take page text out of an XML dump file and perform some operation on it.
 * Used as a base class for CompareParsers and PreprocessDump.
 * We implement below the simple task of searching inside a dump.
 *
 * Copyright © 2011 Platonides
 * https://www.mediawiki.org/
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Base class for interating over a dump.
 *
 * @ingroup Maintenance
 */
abstract class DumpIterator extends Maintenance {
	private $count = 0;
	private $startTime;
	/** @var string|bool|null */
	private $from;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Does something with a dump' );
		$this->addOption( 'file', 'File with text to run.', false, true );
		$this->addOption( 'dump', 'XML dump to execute all revisions.', false, true );
		$this->addOption( 'from', 'Article from XML dump to start from.', false, true );
	}

	public function execute() {
		if ( !( $this->hasOption( 'file' ) ^ $this->hasOption( 'dump' ) ) ) {
			$this->fatalError( "You must provide a file or dump" );
		}

		$this->checkOptions();

		if ( $this->hasOption( 'file' ) ) {
			$revision = new WikiRevision( $this->getConfig() );

			$revision->setText( file_get_contents( $this->getOption( 'file' ) ) );
			$revision->setTitle( Title::newFromText(
				rawurldecode( basename( $this->getOption( 'file' ), '.txt' ) )
			) );
			$this->from = false;
			$this->handleRevision( $revision );

			return;
		}

		$this->startTime = microtime( true );

		if ( $this->getOption( 'dump' ) == '-' ) {
			$source = new ImportStreamSource( $this->getStdin() );
		} else {
			$this->fatalError( "Sorry, I don't support dump filenames yet. "
				. "Use - and provide it on stdin on the meantime." );
		}
		$importer = new WikiImporter( $source, $this->getConfig() );

		$importer->setRevisionCallback(
			[ $this, 'handleRevision' ] );
		$importer->setNoticeCallback( function ( $msg, $params ) {
			echo wfMessage( $msg, $params )->text() . "\n";
		} );

		$this->from = $this->getOption( 'from', null );
		$this->count = 0;
		$importer->doImport();

		$this->conclusions();

		$delta = microtime( true ) - $this->startTime;
		$this->error( "Done {$this->count} revisions in " . round( $delta, 2 ) . " seconds " );
		if ( $delta > 0 ) {
			$this->error( round( $this->count / $delta, 2 ) . " pages/sec" );
		}

		# Perform the memory_get_peak_usage() when all the other data has been
		# output so there's no damage if it dies. It is only available since
		# 5.2.0 (since 5.2.1 if you haven't compiled with --enable-memory-limit)
		$this->error( "Memory peak usage of " . memory_get_peak_usage() . " bytes\n" );
	}

	public function finalSetup() {
		parent::finalSetup();

		if ( $this->getDbType() == Maintenance::DB_NONE ) {
			global $wgUseDatabaseMessages, $wgLocalisationCacheConf, $wgHooks;
			$wgUseDatabaseMessages = false;
			$wgLocalisationCacheConf['storeClass'] = LCStoreNull::class;
			$wgHooks['InterwikiLoadPrefix'][] = 'DumpIterator::disableInterwikis';
		}
	}

	static function disableInterwikis( $prefix, &$data ) {
		# Title::newFromText will check on each namespaced article if it's an interwiki.
		# We always answer that it is not.

		return false;
	}

	/**
	 * Callback function for each revision, child classes should override
	 * processRevision instead.
	 * @param WikiRevision $rev
	 */
	public function handleRevision( $rev ) {
		$title = $rev->getTitle();
		if ( !$title ) {
			$this->error( "Got bogus revision with null title!" );

			return;
		}

		$this->count++;
		if ( $this->from !== false ) {
			if ( $this->from != $title ) {
				return;
			}
			$this->output( "Skipped " . ( $this->count - 1 ) . " pages\n" );

			$this->count = 1;
			$this->from = null;
		}

		$this->processRevision( $rev );
	}

	/* Stub function for processing additional options */
	public function checkOptions() {
	}

	/* Stub function for giving data about what was computed */
	public function conclusions() {
	}

	/* Core function which does whatever the maintenance script is designed to do */
	abstract public function processRevision( $rev );
}

/**
 * Maintenance script that runs a regex in the revisions from a dump.
 *
 * @ingroup Maintenance
 */
class SearchDump extends DumpIterator {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Runs a regex in the revisions from a dump' );
		$this->addOption( 'regex', 'Searching regex', true, true );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	/**
	 * @param Revision $rev
	 */
	public function processRevision( $rev ) {
		if ( preg_match( $this->getOption( 'regex' ), $rev->getContent()->getTextForSearchIndex() ) ) {
			$this->output( $rev->getTitle() . " matches at edit from " . $rev->getTimestamp() . "\n" );
		}
	}
}

$maintClass = SearchDump::class;
require_once RUN_MAINTENANCE_IF_MAIN;
