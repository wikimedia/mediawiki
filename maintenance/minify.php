<?php
/**
 * Minify a file or set of files
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
use Wikimedia\AtEase\AtEase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that minifies a file or set of files.
 *
 * @ingroup Maintenance
 */
class MinifyScript extends Maintenance {
	public $outDir;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'outfile',
			'Write minified output to this file (instead of standard out).',
			false, true, 'o'
		);
		$this->addOption( 'type',
			'Override the input type (one of "js" or "css"). Defaults to file extension. ' .
				'Required if reading from standard input.',
			false, true, 'o'
		);
		$this->addArg( 'file', 'Input file. Use - to read from standard input.' );
		$this->addDescription(
			"Minify one or more JavaScript or CSS files.\n" .
				"If multiple input files are given, they will be concatenated."
		);
	}

	public function execute() {
		$outputFile = $this->getOption( 'outfile', false );
		if ( $outputFile === false ) {
			// Only output the minified result (or errors)
			// Avoid output() because this should not honour --quiet
			foreach ( $this->mArgs as $arg ) {
				print $this->minify( $arg ) . "\n";
			}
		} else {
			$result = '';
			foreach ( $this->mArgs as $arg ) {
				$this->output( "Minifying {$arg} ...\n" );
				$result .= $this->minify( $arg );
			}
			$this->output( "Writing to {$outputFile} ...\n" );
			file_put_contents( $outputFile, $result );
			$this->output( "Done!\n" );
		}
	}

	public function getExtension( $fileName ) {
		$dotPos = strrpos( $fileName, '.' );
		if ( $dotPos === false ) {
			$this->fatalError( "Unknown file type ($fileName). Use --type." );
		}
		return substr( $fileName, $dotPos + 1 );
	}

	private function readFile( $fileName ) {
		if ( $fileName === '-' ) {
			$inText = $this->getStdin( self::STDIN_ALL );
		} else {
			AtEase::suppressWarnings();
			$inText = file_get_contents( $fileName );
			AtEase::restoreWarnings();
			if ( $inText === false ) {
				$this->fatalError( "Unable to open file $fileName for reading." );
			}
		}
		return $inText;
	}

	public function minify( $inPath ) {
		$extension = $this->getOption( 'type', null ) ?? $this->getExtension( $inPath );
		$inText = $this->readFile( $inPath );

		switch ( $extension ) {
			case 'js':
				$outText = JavaScriptMinifier::minify( $inText );
				break;
			case 'css':
				$outText = CSSMin::minify( $inText );
				break;
			default:
				$this->fatalError( "Unsupported file type \"$extension\"." );
		}

		return $outText;
	}
}

$maintClass = MinifyScript::class;
require_once RUN_MAINTENANCE_IF_MAIN;
