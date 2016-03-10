<?php
/**
 * Script that dumps wiki pages or logging database into an XML interchange
 * wrapper format for export or backup
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
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
 * @ingroup Dump Maintenance
 */

require_once __DIR__ . '/backup.inc';

class DumpBackup extends BackupDumper {
	function __construct( $args = null ) {
		parent::__construct();

		$this->addDescription( <<<TEXT
This script dumps the wiki page or logging database into an
XML interchange wrapper format for export or backup.

XML output is sent to stdout; progress reports are sent to stderr.

WARNING: this is not a full database dump! It is merely for public export
         of your wiki. For full backup, see our online help at:
         https://www.mediawiki.org/wiki/Backup
TEXT
		);
		$this->stderr = fopen( "php://stderr", "wt" );
		// Actions
		$this->addOption( 'full', 'Dump all revisions of every page' );
		$this->addOption( 'current', 'Dump only the latest revision of every page.' );
		$this->addOption( 'logs', 'Dump all log events' );
		$this->addOption( 'stable', 'Dump stable versions of pages' );
		$this->addOption( 'revrange', 'Dump range of revisions specified by revstart and ' .
			'revend parameters' );
		$this->addOption( 'pagelist',
			'Dump only pages included in the file', false, true );
		// Options
		$this->addOption( 'start', 'Start from page_id or log_id', false, true );
		$this->addOption( 'end', 'Stop before page_id or log_id n (exclusive)', false, true );
		$this->addOption( 'revstart', 'Start from rev_id', false, true );
		$this->addOption( 'revend', 'Stop before rev_id n (exclusive)', false, true );
		$this->addOption( 'skip-header', 'Don\'t output the <mediawiki> header' );
		$this->addOption( 'skip-footer', 'Don\'t output the </mediawiki> footer' );
		$this->addOption( 'stub', 'Don\'t perform old_text lookups; for 2-pass dump' );
		$this->addOption( 'uploads', 'Include upload records without files' );
		$this->addOption( 'include-files', 'Include files within the XML stream' );

		if ( $args ) {
			$this->loadWithArgv( $args );
			$this->processOptions();
		}
	}

	function execute() {
		$this->processOptions();

		$textMode = $this->hasOption( 'stub' ) ? WikiExporter::STUB : WikiExporter::TEXT;

		if ( $this->hasOption( 'full' ) ) {
			$this->dump( WikiExporter::FULL, $textMode );
		} elseif ( $this->hasOption( 'current' ) ) {
			$this->dump( WikiExporter::CURRENT, $textMode );
		} elseif ( $this->hasOption( 'stable' ) ) {
			$this->dump( WikiExporter::STABLE, $textMode );
		} elseif ( $this->hasOption( 'logs' ) ) {
			$this->dump( WikiExporter::LOGS );
		} elseif ( $this->hasOption( 'revrange' ) ) {
			$this->dump( WikiExporter::RANGE, $textMode );
		} else {
			$this->error( 'No valid action specified.', 1 );
		}
	}

	function processOptions() {
		parent::processOptions();

		// Evaluate options specific to this class
		$this->reporting = !$this->hasOption( 'quiet' );

		if ( $this->hasOption( 'pagelist' ) ) {
			$filename = $this->getOption( 'pagelist' );
			$pages = file( $filename );
			if ( $pages === false ) {
				$this->fatalError( "Unable to open file {$filename}\n" );
			}
			$pages = array_map( 'trim', $pages );
			$this->pages = array_filter( $pages, function ( $x ) {
				return $x !== '';
			} );
		}

		if ( $this->hasOption( 'start' ) ) {
			$this->startId = intval( $this->getOption( 'start' ) );
		}

		if ( $this->hasOption( 'end' ) ) {
			$this->endId = intval( $this->getOption( 'end' ) );
		}

		if ( $this->hasOption( 'revstart' ) ) {
			$this->revStartId = intval( $this->getOption( 'revstart' ) );
		}

		if ( $this->hasOption( 'revend' ) ) {
			$this->revEndId = intval( $this->getOption( 'revend' ) );
		}

		$this->skipHeader = $this->hasOption( 'skip-header' );
		$this->skipFooter = $this->hasOption( 'skip-footer' );
		$this->dumpUploads = $this->hasOption( 'uploads' );
		$this->dumpUploadFileContents = $this->hasOption( 'include-files' );
	}
}

$maintClass = 'DumpBackup';
require_once RUN_MAINTENANCE_IF_MAIN;
