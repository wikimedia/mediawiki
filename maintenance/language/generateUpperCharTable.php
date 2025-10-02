<?php
/**
 * Generate a json file containing an array of
 *   utf8_lowercase => utf8_uppercase
 * for all of the utf-8 range. This provides the input for generateUcfirstOverrides.php
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup MaintenanceLanguage
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class GenerateUpperCharTable extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Generates the lowercase => uppercase json table' );
		$this->addOption( 'outfile', 'Output file', true, true, 'o' );
		$this->addOption( 'titlecase', 'Use title case instead of upper case' );
	}

	public function execute() {
		$outfile = $this->getOption( 'outfile', 'upperchar.json' );
		$toUpperTable = [];
		$titlecase = $this->getOption( 'titlecase' );
		for ( $i = 0; $i <= 0x10ffff; $i++ ) {
			// skip all surrogate codepoints or json_encode would fail.
			if ( $i >= 0xd800 && $i <= 0xdfff ) {
				continue;
			}
			$char = UtfNormal\Utils::codepointToUtf8( $i );
			if ( $titlecase ) {
				$upper = mb_convert_case( $char, MB_CASE_TITLE );
			} else {
				$upper = mb_strtoupper( $char );
			}
			$toUpperTable[$char] = $upper;
		}
		file_put_contents( $outfile, json_encode( $toUpperTable ) );
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateUpperCharTable::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
