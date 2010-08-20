<?php
/**
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
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

$optionsWithArgs = array( 'pagelist', 'start', 'end' );

require_once( dirname( __FILE__ ) . '/commandLine.inc' );
require_once( 'backup.inc' );

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
		wfDie( "Unable to open file {$options['pagelist']}\n" );
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
$dumper->skipHeader = isset( $options['skip-header'] );
$dumper->skipFooter = isset( $options['skip-footer'] );
$dumper->dumpUploads = isset( $options['uploads'] );

$textMode = isset( $options['stub'] ) ? WikiExporter::STUB : WikiExporter::TEXT;

if ( isset( $options['full'] ) ) {
	$dumper->dump( WikiExporter::FULL, $textMode );
} elseif ( isset( $options['current'] ) ) {
	$dumper->dump( WikiExporter::CURRENT, $textMode );
} elseif ( isset( $options['stable'] ) ) {
	$dumper->dump( WikiExporter::STABLE, $textMode );
} elseif ( isset( $options['logs'] ) ) {
	$dumper->dump( WikiExporter::LOGS );
} else {
	$dumper->progress( <<<ENDS
This script dumps the wiki page or logging database into an
XML interchange wrapper format for export or backup.

XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpBackup.php <action> [<options>]
Actions:
  --full      Dump all revisions of every page.
  --current   Dump only the latest revision of every page.
  --logs      Dump all log events.
  --stable    Stable versions of pages?
  --pagelist=<file>
              Where <file> is a list of page titles to be dumped

Options:
  --quiet     Don't dump status reports to stderr.
  --start=n   Start from page_id or log_id n
  --end=n     Stop before page_id or log_id n (exclusive)
  --skip-header Don't output the <mediawiki> header
  --skip-footer Don't output the </mediawiki> footer
  --stub      Don't perform old_text lookups; for 2-pass dump
  --uploads   Include upload records (experimental)
ENDS
);
}
