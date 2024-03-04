<?php

namespace MediaWiki\Parser\Parsoid;

use LanguageCode;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * Provides methods for conversion between PageBundle and ParserOutput
 * TODO: Convert to a trait once we drop support for PHP < 8.2 since
 * support for constants in traits was added in PHP 8.2
 * @since 1.40
 * @internal
 */
final class PageBundleParserOutputConverter {
	/**
	 * @var string Key used to store parsoid page bundle data in ParserOutput
	 */
	public const PARSOID_PAGE_BUNDLE_KEY = 'parsoid-page-bundle';

	/**
	 * We do not want instances of this class to be created
	 * @return void
	 */
	private function __construct() {
	}

	/**
	 * Creates a ParserOutput object containing the relevant data from
	 * the given PageBundle object.
	 *
	 * We need to inject data-parsoid and other properties into the
	 * parser output object for caching, so we can use it for VE edits
	 * and transformations.
	 *
	 * @param PageBundle $pageBundle
	 * @param ?ParserOutput $originalParserOutput Any non-parsoid metadata
	 *  from $originalParserOutput will be copied into the new ParserOutput object.
	 *
	 * @return ParserOutput
	 */
	public static function parserOutputFromPageBundle(
		PageBundle $pageBundle, ?ParserOutput $originalParserOutput = null
	): ParserOutput {
		$parserOutput = new ParserOutput( $pageBundle->html );

		if ( $originalParserOutput ) {
			$parserOutput->mergeHtmlMetaDataFrom( $originalParserOutput );
			$parserOutput->mergeTrackingMetaDataFrom( $originalParserOutput );
			$parserOutput->mergeInternalMetaDataFrom( $originalParserOutput );
		}

		$parserOutput->setExtensionData(
			self::PARSOID_PAGE_BUNDLE_KEY,
			[
				'parsoid' => $pageBundle->parsoid,
				'mw' => $pageBundle->mw,
				'version' => $pageBundle->version,
				'headers' => $pageBundle->headers,
				'contentmodel' => $pageBundle->contentmodel,
			]
		);

		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$lang = LanguageCode::normalizeNonstandardCodeAndWarn(
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$pageBundle->headers['content-language']
			);
			$parserOutput->setLanguage( $lang );
		}

		return $parserOutput;
	}

	/**
	 * Returns a Parsoid PageBundle equivalent to the given ParserOutput.
	 *
	 * @param ParserOutput $parserOutput
	 *
	 * @return PageBundle
	 */
	public static function pageBundleFromParserOutput( ParserOutput $parserOutput ): PageBundle {
		$pageBundleData = $parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY );
		$lang = $parserOutput->getLanguage();

		$headers = $pageBundleData['headers'] ?? [];

		if ( $lang ) {
			$headers['content-language'] = $lang;
		}

		return new PageBundle(
			$parserOutput->getRawText(),
			$pageBundleData['parsoid'] ?? [],
			$pageBundleData['mw'] ?? [],
			// It would be nice to have this be "null", but PageBundle::responseData()
			// chocks on that: T325137.
			$pageBundleData['version'] ?? '0.0.0',
			$pageBundleData['headers'] ?? $headers,
			$pageBundleData['contentmodel'] ?? null
		);
	}

	public static function hasPageBundle( ParserOutput $parserOutput ) {
		return $parserOutput->getExtensionData( self::PARSOID_PAGE_BUNDLE_KEY ) !== null;
	}
}
