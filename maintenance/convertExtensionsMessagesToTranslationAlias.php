<?php

use MediaWiki\Json\FormatJson;

require_once __DIR__ . '/Maintenance.php';

/**
 * Convert existing ExtensionMessagesFiles to JSON files in different language codes that can be used as
 * input for TranslationAliasesDirs configuration.
 *
 * @since 1.42
 * @ingroup Maintenance
 */

class ConvertExtensionsMessagesToTranslationAlias extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Convert ExtensionMessagesFiles to JSON files in different language codes.' );

		$this->addArg( 'destination', 'Destination folder where the JSON files should be created' );
		$this->addArg(
			'files', 'ExtensionMessageFiles to be converted', true, true
		);
	}

	public function execute() {
		$errors = [];
		$destinationFolder = $this->getArg( 0 );
		if ( !is_dir( $destinationFolder ) || !is_writable( $destinationFolder ) ) {
			$errors[] = "The path: $destinationFolder does not exist, is not a folder or is not writable.";
		}

		$messageFiles = $this->getArgs( 1 );
		foreach ( $messageFiles as $file ) {
			if ( !file_exists( $file ) ) {
				$errors[] = "The message file: $file does not exist";
			}
		}

		if ( $errors ) {
			$this->fatalError( implode( "\n* ", $errors ) );
		}

		$data = [];

		foreach ( $messageFiles as $file ) {
			include $file;

			foreach ( LocalisationCache::ALL_ALIAS_KEYS as $key ) {
				// Can be removed once LocalisationCache::ALL_ALIAS_KEYS has multiple entries
				// @phan-suppress-next-line PhanImpossibleConditionInLoop False positive
				if ( isset( $$key ) ) {
					// Can be removed once LocalisationCache::ALL_ALIAS_KEYS has multiple entries
					// @phan-suppress-next-line PhanUndeclaredVariable Possibly declared in $file
					$data[$key] = $$key;
				}
			}
		}

		$json = [];
		foreach ( $data as $key => $item ) {
			$normalizedKey = ucfirst( $key );
			// @phan-suppress-next-line PhanTypeMismatchForeach False positive
			foreach ( $item as $languageCode => $itemData ) {
				$json[$languageCode][$normalizedKey] = $itemData;
			}
		}

		foreach ( $json as $languageCode => $data ) {
			$filePath = $destinationFolder . '/' . $languageCode . ".json";
			file_put_contents(
				$filePath,
				FormatJson::encode( $data, "\t", FormatJson::UTF8_OK ) . "\n"
			);
		}

		$this->output( "Done!\n" );
	}
}

$maintClass = ConvertExtensionsMessagesToTranslationAlias::class;
require_once RUN_MAINTENANCE_IF_MAIN;
