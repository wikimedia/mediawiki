<?php
/**
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
 */

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;

/**
 * API module to enumerate language information.
 *
 * @ingroup API
 */
class ApiQueryLanguageinfo extends ApiQueryBase {

	/**
	 * The maximum time for {@link execute()};
	 * if execution takes longer than this, apply continuation.
	 *
	 * If the localization cache is used, this time is not expected to ever be
	 * exceeded; on the other hand, if it is not used, a typical request will
	 * not yield more than a handful of languages before the time is exceeded
	 * and continuation is applied, if one of the expensive props is requested.
	 *
	 * @var float
	 */
	private const MAX_EXECUTE_SECONDS = 2.0;

	/** @var LanguageFactory */
	private $languageFactory;

	/** @var LanguageNameUtils */
	private $languageNameUtils;

	/** @var LanguageFallback */
	private $languageFallback;

	/** @var LanguageConverterFactory */
	private $languageConverterFactory;

	/** @var callable|null */
	private $microtimeFunction;

	/**
	 * @param ApiQuery $queryModule
	 * @param string $moduleName
	 * @param LanguageFactory $languageFactory
	 * @param LanguageNameUtils $languageNameUtils
	 * @param LanguageFallback $languageFallback
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param callable|null $microtimeFunction Function to use instead of microtime(), for testing.
	 * Should accept no arguments and return float seconds. (null means real microtime().)
	 */
	public function __construct(
		ApiQuery $queryModule,
		$moduleName,
		LanguageFactory $languageFactory,
		LanguageNameUtils $languageNameUtils,
		LanguageFallback $languageFallback,
		LanguageConverterFactory $languageConverterFactory,
		$microtimeFunction = null
	) {
		parent::__construct( $queryModule, $moduleName, 'li' );
		$this->languageFactory = $languageFactory;
		$this->languageNameUtils = $languageNameUtils;
		$this->languageFallback = $languageFallback;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->microtimeFunction = $microtimeFunction;
	}

	/** @return float */
	private function microtime() {
		if ( $this->microtimeFunction ) {
			return ( $this->microtimeFunction )();
		} else {
			return microtime( true );
		}
	}

	public function execute() {
		$endTime = $this->microtime() + self::MAX_EXECUTE_SECONDS;

		$props = array_flip( $this->getParameter( 'prop' ) );
		$includeCode = isset( $props['code'] );
		$includeBcp47 = isset( $props['bcp47'] );
		$includeDir = isset( $props['dir'] );
		$includeAutonym = isset( $props['autonym'] );
		$includeName = isset( $props['name'] );
		$includeFallbacks = isset( $props['fallbacks'] );
		$includeVariants = isset( $props['variants'] );

		$targetLanguageCode = $this->getLanguage()->getCode();
		$include = 'all';

		$availableLanguageCodes = array_keys( $this->languageNameUtils->getLanguageNames(
			// MediaWiki and extensions may return different sets of language codes
			// when asked for language names in different languages;
			// asking for English language names is most likely to give us the full set,
			// even though we may not need those at all
			'en',
			$include
		) );
		$selectedLanguageCodes = $this->getParameter( 'code' );
		if ( $selectedLanguageCodes === [ '*' ] ) {
			$languageCodes = $availableLanguageCodes;
		} else {
			$languageCodes = array_values( array_intersect(
				$availableLanguageCodes,
				$selectedLanguageCodes
			) );
			$unrecognizedCodes = array_values( array_diff(
				$selectedLanguageCodes,
				$availableLanguageCodes
			) );
			if ( $unrecognizedCodes !== [] ) {
				$this->addWarning( [
					'apiwarn-unrecognizedvalues',
					$this->encodeParamName( 'code' ),
					Message::listParam( $unrecognizedCodes, 'comma' ),
					count( $unrecognizedCodes ),
				] );
			}
		}
		// order of $languageCodes is guaranteed by Language::fetchLanguageNames()
		// and preserved by array_values() + array_intersect()

		$continue = $this->getParameter( 'continue' );
		if ( $continue === null ) {
			$continue = reset( $languageCodes );
		}

		$result = $this->getResult();
		$rootPath = [
			$this->getQuery()->getModuleName(),
			$this->getModuleName(),
		];
		$result->addArrayType( $rootPath, 'assoc' );

		foreach ( $languageCodes as $languageCode ) {
			if ( $languageCode < $continue ) {
				continue;
			}

			$now = $this->microtime();
			if ( $now >= $endTime ) {
				$this->setContinueEnumParameter( 'continue', $languageCode );
				break;
			}

			$info = [];
			ApiResult::setArrayType( $info, 'assoc' );

			if ( $includeCode ) {
				$info['code'] = $languageCode;
			}

			if ( $includeBcp47 ) {
				$bcp47 = LanguageCode::bcp47( $languageCode );
				$info['bcp47'] = $bcp47;
			}

			if ( $includeDir ) {
				$dir = $this->languageFactory->getLanguage( $languageCode )->getDir();
				$info['dir'] = $dir;
			}

			if ( $includeAutonym ) {
				$autonym = $this->languageNameUtils->getLanguageName(
					$languageCode,
					LanguageNameUtils::AUTONYMS,
					$include
				);
				$info['autonym'] = $autonym;
			}

			if ( $includeName ) {
				$name = $this->languageNameUtils->getLanguageName(
					$languageCode,
					$targetLanguageCode,
					$include
				);
				$info['name'] = $name;
			}

			if ( $includeFallbacks ) {
				$fallbacks = $this->languageFallback->getAll(
					$languageCode,
					// allow users to distinguish between implicit and explicit 'en' fallbacks
					LanguageFallback::STRICT
				);
				ApiResult::setIndexedTagName( $fallbacks, 'fb' );
				$info['fallbacks'] = $fallbacks;
			}

			if ( $includeVariants ) {
				$language = $this->languageFactory->getLanguage( $languageCode );
				$converter = $this->languageConverterFactory->getLanguageConverter( $language );
				$variants = $converter->getVariants();
				ApiResult::setIndexedTagName( $variants, 'var' );
				$info['variants'] = $variants;
			}

			$fit = $result->addValue( $rootPath, $languageCode, $info );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $languageCode );
				break;
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return [
			'prop' => [
				self::PARAM_DFLT => 'code',
				self::PARAM_ISMULTI => true,
				self::PARAM_TYPE => [
					'code',
					'bcp47',
					'dir',
					'autonym',
					'name',
					'fallbacks',
					'variants',
				],
				self::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'code' => [
				self::PARAM_DFLT => '*',
				self::PARAM_ISMULTI => true,
			],
			'continue' => [
				self::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		$pathUrl = 'action=' . $this->getQuery()->getModuleName() .
			'&meta=' . $this->getModuleName();
		$pathMsg = $this->getModulePath();
		$prefix = $this->getModulePrefix();

		return [
			"$pathUrl"
				=> "apihelp-$pathMsg-example-simple",
			"$pathUrl&{$prefix}prop=autonym|name&uselang=de"
				=> "apihelp-$pathMsg-example-autonym-name-de",
			"$pathUrl&{$prefix}prop=fallbacks|variants&{$prefix}code=oc"
				=> "apihelp-$pathMsg-example-fallbacks-variants-oc",
			"$pathUrl&{$prefix}prop=bcp47|dir"
				=> "apihelp-$pathMsg-example-bcp47-dir",
		];
	}

}
