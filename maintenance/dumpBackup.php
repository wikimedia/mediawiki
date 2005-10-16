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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

$originalDir = getcwd();

$optionsWithArgs = array( 'server', 'pagelist', 'start', 'end' );

require_once( 'commandLine.inc' );
require_once( 'SpecialExport.php' );
require_once( 'maintenance/backup.inc' );

$dumper = new BackupDumper( $argv );

if( isset( $options['quiet'] ) ) {
	$dumper->reporting = false;
}
if( isset( $options['report'] ) ) {
	$dumper->reportingInterval = intval( $options['report'] );
}
if( isset( $options['server'] ) ) {
	$dumper->server = $options['server'];
}

if ( isset( $options['pagelist'] ) ) {
	$olddir = getcwd();
	chdir( $originalDir );
	$pages = file( $options['pagelist'] );
	chdir( $olddir );
	if ( $pages === false ) {
		print "Unable to open file {$options['pagelist']}\n";
		exit;
	}
	$pages = array_map( 'trim', $pages );
	$dumper->pages = array_filter( $pages, create_function( '$x', 'return $x !== "";' ) );
}

if( isset( $options['start'] ) ) {
	$dumper->startId = intval( $options['start'] );
}
if( isset( $options['end'] ) ) {
	$dumper->endId = intval( $options['end'] );
}
$dumper->skipHeader = isset( $options['skip-header'] );
$dumper->skipFooter = isset( $options['skip-footer'] );

$textMode = isset( $options['stub'] ) ? MW_EXPORT_STUB : MW_EXPORT_TEXT;

if( isset( $options['full'] ) ) {
	$dumper->dump( MW_EXPORT_FULL, $textMode );
} elseif( isset( $options['current'] ) ) {
	$dumper->dump( MW_EXPORT_CURRENT, $textMode );
} else {
	$dumper->progress( <<<END
This script dumps the wiki page database into an XML interchange wrapper
format for export or backup.

XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpBackup.php <action> [<options>]
Actions:
  --full      Dump complete history of every page.
  --current   Includes only the latest revision of each page.

Options:
  --quiet     Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
              (Default: 100)
  --server=h  Force reading from MySQL server h
  --start=n   Start from page_id n
  --end=n     Stop before page_id n (exclusive)
  --skip-header Don't output the <mediawiki> header
  --skip-footer Don't output the </mediawiki> footer
  --stub      Don't perform old_text lookups; for 2-pass dump
END
);
}

?>
