<?php
/**
 * Lists all language variants
 *
 * Copyright © 2014 MediaWiki developers
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Json\FormatJson;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once dirname( __DIR__ ) . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @since 1.24
 */
class ListVariants extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Outputs a list of language variants' );
		$this->addOption( 'flat', 'Output variants in a flat list' );
		$this->addOption( 'json', 'Output variants as JSON' );
	}

	public function execute() {
		$services = $this->getServiceContainer();
		$languageFactory = $services->getLanguageFactory();
		$languageConverterFactory = $services->getLanguageConverterFactory();
		$variantLangs = [];
		$variants = [];
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$lang = $languageFactory->getLanguage( $langCode );
			$langConv = $languageConverterFactory->getLanguageConverter( $lang );
			if ( $langConv->hasVariants() ) {
				$variants += array_fill_keys( $langConv->getVariants(), true );
				$variantLangs[$langCode] = $langConv->getVariants();
			}
		}
		$variants = array_keys( $variants );
		sort( $variants );
		$result = $this->hasOption( 'flat' ) ? $variants : $variantLangs;

		// Not using $this->output() because muting makes no sense here
		if ( $this->hasOption( 'json' ) ) {
			echo FormatJson::encode( $result, true ) . "\n";
		} else {
			foreach ( $result as $key => $value ) {
				if ( is_array( $value ) ) {
					echo "$key\n";
					foreach ( $value as $variant ) {
						echo "   $variant\n";
					}
				} else {
					echo "$value\n";
				}
			}
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ListVariants::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
