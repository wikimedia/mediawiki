<?php
/**
 * Script that dumps wiki pages or logging database into an XML interchange
 * wrapper format for export or backup
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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

$originalDir = getcwd();

$optionsWithArgs = array( 'pagelist', 'start', 'end', 'revstart', 'revend' );

require_once __DIR__ . '/commandLine.inc';
require_once __DIR__ . '/backup.inc';

$dumper = new BackupDumper( $argv );

if ( isset( $options['quiet'] ) ) {
	$dumper->reporting = false;
}

if ( isset( $options['pagelist'] ) ) {
	$olddir = getcwd();
	chdir( $originalDir );
	$pages = file( $options['pagelist'] );
	chdir( $olddir );
	if ( $pages === false ) {
		echo "Unable to open file {$options['pagelist']}\n";
		die( 1 );
	}
	$pages = array_map( 'trim', $pages );
	$dumper->pages = array_filter( $pages, create_function( '$x', 'return $x !== "";' ) );
}

if ( isset( $options['start'] ) ) {
	$dumper->startId = intval( $options['start'] );
}
if ( isset( $options['end'] ) ) {
	$dumper->endId = intval( $options['end'] );
}

if ( isset( $options['revstart'] ) ) {
	$dumper->revStartId = intval( $options['revstart'] );
}
if ( isset( $options['revend'] ) ) {
	$dumper->revEndId = intval( $options['revend'] );
}
$dumper->skipHeader = isset( $options['skip-header'] );
$dumper->skipFooter = isset( $options['skip-footer'] );
$dumper->dumpUploads = isset( $options['uploads'] );
$dumper->dumpUploadFileContents = isset( $options['include-files'] );

$textMode = isset( $options['stub'] ) ? WikiExporter::STUB : WikiExporter::TEXT;

if ( isset( $options['full'] ) ) {
	$dumper->dump( WikiExporter::FULL, $textMode );
} elseif ( isset( $options['current'] ) ) {
	$dumper->dump( WikiExporter::CURRENT, $textMode );
} elseif ( isset( $options['stable'] ) ) {
	$dumper->dump( WikiExporter::STABLE, $textMode );
} elseif ( isset( $options['logs'] ) ) {
	$dumper->dump( WikiExporter::LOGS );
} elseif ( isset( $options['revrange'] ) ) {
	$dumper->dump( WikiExporter::RANGE, $textMode );
} else {
	$dumper->progress( <<<ENDS
This script dumps the wiki page or logging database into an
XML interchange wrapper format for export or backup.

XML output is sent to stdout; progress reports are sent to stderr.

WARNING: this is not a full database dump! It is merely for public export
		 of your wiki. For full backup, see our online help at:
         https://www.mediawiki.org/wiki/Backup

Usage: php dumpBackup.php <action> [<options>]
Actions:
  --full      Dump all revisions of every page.
  --current   Dump only the latest revision of every page.
  --logs      Dump all log events.
  --stable    Stable versions of pages?
  --pagelist=<file>
			  Where <file> is a list of page titles to be dumped
  --revrange  Dump specified range of revisions, requires
              revstart and revend options.
Options:
  --quiet     Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
			  (Default: 100)
  --server=h  Force reading from MySQL server h
  --start=n   Start from page_id or log_id n
  --end=n     Stop before page_id or log_id n (exclusive)
  --revstart=n  Start from rev_id n
  --revend=n    Stop before rev_id n (exclusive)
  --skip-header Don't output the <mediawiki> header
  --skip-footer Don't output the </mediawiki> footer
  --stub      Don't perform old_text lookups; for 2-pass dump
  --uploads   Include upload records without files
  --include-files Include files within the XML stream
  --conf=<file> Use the specified configuration file (LocalSettings.php)

  --wiki=<wiki>  Only back up the specified <wiki>

Fancy stuff: (Works? Add examples please.)
  --plugin=<class>[:<file>]   Load a dump plugin class
  --output=<type>:<file>      Begin a filtered output stream;
                              <type>s: file, gzip, bzip2, 7zip
  --filter=<type>[:<options>] Add a filter on an output branch

ENDS
);
}
