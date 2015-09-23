<?php
/**
 * Format RELEASE-NOTE file to wiki text or HTML markup.
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
 * Maintenance script that formats RELEASE-NOTE file to wiki text or HTML markup.
 *
 * @ingroup Maintenance
 */
class MaintenanceFormatInstallDoc extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addArg( 'path', 'The file name to format', false );
		$this->addOption( 'outfile', 'The output file name', false, true );
		$this->addOption( 'html', 'Use HTML output format. By default, wikitext is used.' );
	}

	function execute() {
		if ( $this->hasArg( 0 ) ) {
			$fileName = $this->getArg( 0 );
			$inFile = fopen( $fileName, 'r' );
			if ( !$inFile ) {
				$this->error( "Unable to open input file \"$fileName\"" );
				exit( 1 );
			}
		} else {
			$inFile = STDIN;
		}

		if ( $this->hasOption( 'outfile' ) ) {
			$fileName = $this->getOption( 'outfile' );
			$outFile = fopen( $fileName, 'w' );
			if ( !$outFile ) {
				$this->error( "Unable to open output file \"$fileName\"" );
				exit( 1 );
			}
		} else {
			$outFile = STDOUT;
		}

		$inText = stream_get_contents( $inFile );
		$outText = InstallDocFormatter::format( $inText );

		if ( $this->hasOption( 'html' ) ) {
			global $wgParser;
			$opt = new ParserOptions;
			$title = Title::newFromText( 'Text file' );
			$out = $wgParser->parse( $outText, $title, $opt );
			$outText = "<html><body>\n" . $out->getText() . "\n</body></html>\n";
		}

		fwrite( $outFile, $outText );
	}
}

$maintClass = 'MaintenanceFormatInstallDoc';
require_once RUN_MAINTENANCE_IF_MAIN;
