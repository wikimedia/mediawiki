<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\Config\Config;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * @internal
 */
class DateFormatterConfig extends Module {
	/**
	 * Callback for mediawiki.DateFormatter/config.json
	 *
	 * @internal
	 * @param Context $context
	 * @param Config $config
	 * @return array
	 */
	public static function getData( Context $context, Config $config ) {
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()
			->getLanguage( $context->getLanguage() );
		return self::getDataForLang( $lang, $config );
	}

	/**
	 * Get configuration data for DateFormatter given parameters
	 *
	 * @internal
	 * @param Language $lang
	 * @param Config $config
	 * @return array
	 */
	public static function getDataForLang( Language $lang, Config $config ) {
		$locales = [ $lang->getHtmlCode() ];
		foreach ( $lang->getFallbackLanguages() as $code ) {
			$locales[] = LanguageCode::bcp47( $code );
		}

		// Discover which fields are required
		$formats = $lang->getJsDateFormats();
		$haveField = [];
		foreach ( $formats as $format ) {
			$pattern = $format['pattern'] ?? '';
			foreach ( [ 'mwMonth', 'mwMonthGen', 'mwMonthAbbrev' ] as $field ) {
				if ( str_contains( $pattern, '{' . $field . '}' ) ) {
					$haveField[$field] = true;
				}
			}
		}

		$months = [];
		// Include only the required month data
		if ( $haveField ) {
			// Dummy entry for non-existing month zero
			$months[] = [];
			for ( $i = 1; $i <= 12; $i++ ) {
				// The three array elements are expected in this order in DateFormatter.js
				$data = [
					isset( $haveField['mwMonth'] ) ? $lang->getMonthName( $i ) : '',
					isset( $haveField['mwMonthGen'] ) ? $lang->getMonthNameGen( $i ) : '',
					isset( $haveField['mwMonthAbbrev'] ) ? $lang->getMonthAbbreviation( $i ) : ''
				];
				// Trim the end of the array
				while ( array_last( $data ) === '' ) {
					array_pop( $data );
				}
				$months[$i] = $data;
			}
		}

		return [
			'locales' => $locales,
			'formats' => $formats,
			'defaultStyle' => $lang->getDefaultDateFormat(),
			'localZone' => $config->get( MainConfigNames::Localtimezone ),
			'localOffset' => (int)$config->get( MainConfigNames::LocalTZoffset ),
			'months' => $months
		];
	}
}
