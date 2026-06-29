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
		$fallbacks = $lang->getFallbackLanguages();
		foreach ( $fallbacks as $code ) {
			$locales[] = LanguageCode::bcp47( $code );
		}

		// Discover which fields are required
		$formats = $lang->getJsDateFormats();
		$haveField = [];
		foreach ( $formats as $format ) {
			$pattern = $format['pattern'] ?? '';
			foreach ( [ 'mwMonth', 'mwMonthGen', 'mwMonthAbbrev' ] as $field ) {
				if ( str_contains( $pattern, "{$field}" ) ) {
					$haveField[$field] = true;
				}
			}
		}

		// Include only the required month data
		if ( $haveField ) {
			$months = [ [] ];
			for ( $i = 1; $i <= 12; $i++ ) {
				$data = [
					isset( $haveField['mwMonth'] ) ? $lang->getMonthName( $i ) : '',
					isset( $haveField['mwMonthGen'] ) ? $lang->getMonthNameGen( $i ) : '',
					isset( $haveField['mwMonthAbbrev'] ) ? $lang->getMonthAbbreviation( $i ) : ''
				];
				// Trim the end of the array
				while ( end( $data ) === '' ) {
					unset( $data[ array_key_last( $data ) ] );
				}
				$months[] = $data;
			}
		} else {
			$months = [];
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
