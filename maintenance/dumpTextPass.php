<?php
/**
 * Script that postprocesses XML dumps from dumpBackup.php to add page text
 *
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
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

$originalDir = getcwd();

require_once __DIR__ . '/commandLine.inc';
require_once __DIR__ . '/backupTextPass.inc';

$dumper = new TextPassDumper( $argv );

if ( !isset( $options['help'] ) ) {
	$dumper->dump( true );
} else {
	$dumper->progress( <<<ENDS
This script postprocesses XML dumps from dumpBackup.php to add
page text which was stubbed out (using --stub).

XML input is accepted on stdin.
XML output is sent to stdout; progress reports are sent to stderr.

Usage: php dumpTextPass.php [<options>]
Options:
  --stub=<type>:<file> To load a compressed stub dump instead of stdin
  --prefetch=<type>:<file> Use a prior dump file as a text source, to save
			  pressure on the database.
			  (Requires the XMLReader extension)
  --maxtime=<minutes> Write out checkpoint file after this many minutes (writing
	          out complete page, closing xml file properly, and opening new one
	          with header).  This option requires the checkpointfile option.
  --checkpointfile=<filenamepattern> Use this string for checkpoint filenames,
		      substituting first pageid written for the first %s (required) and the
              last pageid written for the second %s if it exists.
  --quiet	  Don't dump status reports to stderr.
  --report=n  Report position and speed after every n pages processed.
			  (Default: 100)
  --server=h  Force reading from MySQL server h
  --current	  Base ETA on number of pages in database instead of all revisions
  --spawn	  Spawn a subprocess for loading text records
  --help      Display this help message
ENDS
);
}
