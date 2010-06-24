<?php
/**
 * Take page text out of an XML dump file and render basic HTML out to files.
 * This is *NOT* suitable for publishing or offline use; it's intended for
 * running comparative tests of parsing behavior using real-world data.
 *
 * Templates etc are pulled from the local wiki database, not from the dump.
 *
 * Copyright (C) 2006 Brion Vibber <brion@pobox.com>
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
 * @ingroup Maintenance
 */
 
require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DumpRenderer extends Maintenance {

	private $count = 0;
	private $outputDirectory, $startTime;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Take page text out of an XML dump file and render basic HTML out to files";
		$this->addOption( 'output-dir', 'The directory to output the HTML files to', true, true );
	}

	public function execute() {
		$this->outputDirectory = $this->getOption( 'output-dir' );
		$this->startTime = wfTime();

		$source = new ImportStreamSource( $this->getStdin() );
		$importer = new WikiImporter( $source );

		$importer->setRevisionCallback(
			array( &$this, 'handleRevision' ) );

		return $importer->doImport();
	}
	
	/**
	 * Callback function for each revision, turn into HTML and save
	 * @param $rev Revision
	 */
	public function handleRevision( $rev ) {
		$title = $rev->getTitle();
		if ( !$title ) {
			$this->error( "Got bogus revision with null title!" );
			return;
		}
		$display = $title->getPrefixedText();

		$this->count++;

		$sanitized = rawurlencode( $display );
		$filename = sprintf( "%s/wiki-%07d-%s.html",
			$this->outputDirectory,
			$this->count,
			$sanitized );
		$this->output( sprintf( "%s\n", $filename, $display ) );

		// fixme (what?)
		$user = new User();
		$parser = new Parser();
		$options = ParserOptions::newFromUser( $user );

		$output = $parser->parse( $rev->getText(), $title, $options );

		file_put_contents( $filename,
			"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" " .
			"\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n" .
			"<html xmlns=\"http://www.w3.org/1999/xhtml\">\n" .
			"<head>\n" .
			"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n" .
			"<title>" . htmlspecialchars( $display ) . "</title>\n" .
			"</head>\n" .
			"<body>\n" .
			$output->getText() .
			"</body>\n" .
			"</html>" );
	}
}

$maintClass = "DumpRenderer";
require_once( DO_MAINTENANCE );
