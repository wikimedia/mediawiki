<?php

/**
 * Update list of upper case differences between JS and PHP
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Shell\Shell;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Update list of upper case differences between JS and PHP
 *
 * @ingroup Maintenance
 * @since 1.33
 */
class GeneratePhpCharToUpperMappings extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Update list of upper case differences between JS and PHP.' );
	}

	public function execute() {
		global $IP;

		$data = [];

		$result = Shell::command(
				[ 'node', $IP . '/maintenance/mediawiki.Title/generateJsToUpperCaseList.js' ]
			)
			// Node allocates lots of memory
			->limits( [ 'memory' => 1024 * 1024 ] )
			->execute();

		if ( $result->getExitCode() !== 0 ) {
			$this->output( $result->getStderr() );
			return;
		}

		$jsUpperChars = json_decode( $result->getStdout() );
		'@phan-var string[] $jsUpperChars';

		$contentLanguage = $this->getServiceContainer()->getContentLanguage();
		for ( $i = 0; $i <= 0x10ffff; $i++ ) {
			if ( $i >= 0xd800 && $i <= 0xdfff ) {
				// Skip surrogate pairs
				continue;
			}
			$char = \UtfNormal\Utils::codepointToUtf8( $i );
			$phpUpper = $contentLanguage->ucfirst( $char );
			$jsUpper = $jsUpperChars[$i];
			if ( $jsUpper !== $phpUpper ) {
				if ( $char === $phpUpper ) {
					// Optimisation: Use 0 to signal "leave character unchanged".
					// Reduces the transfer size by ~50%. Reduces browser memory cost as well.
					$data[$char] = 0;
				} else {
					$data[$char] = $phpUpper;
				}
			}
		}

		$mappingJson = str_replace( '    ', "\t",
			json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE )
		) . "\n";
		$outputPath = '/resources/src/mediawiki.Title/phpCharToUpper.json';
		$file = fopen( $IP . $outputPath, 'w' );
		if ( !$file ) {
			$this->fatalError( "Unable to write file \"$IP$outputPath\"" );
		}
		fwrite( $file, $mappingJson );

		$this->output( count( $data ) . " differences found.\n" );
		$this->output( "Written to $outputPath\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = GeneratePhpCharToUpperMappings::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
