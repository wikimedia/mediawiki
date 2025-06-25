<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Language\LanguageCode;
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
		self::applyPageBundleDataToParserOutput( $pageBundle, $parserOutput );
		return $parserOutput;
	}

	/**
	 * Given an existing ParserOutput and a PageBundle, applies the PageBundle data to the ParserOutput.
	 * NOTE: it does NOT apply the text of said pageBundle - this should be done by the calling method, if desired.
	 * This way, we can modify a ParserOutput's associated bundle without creating a new ParserOutput,
	 * which makes it easier to deal with in the OutputTransformPipeline.
	 * @param PageBundle|\stdClass $pageBundle
	 * @param ParserOutput $parserOutput
	 * @internal
	 */
	public static function applyPageBundleDataToParserOutput(
		$pageBundle, ParserOutput $parserOutput
	): void {
		// Overwriting ExtensionData was deprecated in 1.38 but it's safe inside an OutputTransform pipeline,
		// which is the only place where this should happen right now.
		$parserOutput->setExtensionData(
			self::PARSOID_PAGE_BUNDLE_KEY,
			[
				'parsoid' => $pageBundle->parsoid ?? null,
				'mw' => $pageBundle->mw ?? null,
				'version' => $pageBundle->version ?? null,
				'headers' => $pageBundle->headers ?? null,
				'contentmodel' => $pageBundle->contentmodel ?? null,
			]
		);

		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$lang = LanguageCode::normalizeNonstandardCodeAndWarn(
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$pageBundle->headers['content-language']
			);
			$parserOutput->setLanguage( $lang );
		}
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
			$headers['content-language'] = $lang->toBcp47Code();
		}

		return new PageBundle(
			$parserOutput->getRawText(),
			$pageBundleData['parsoid'] ?? [ 'ids' => [] ],
			$pageBundleData['mw'] ?? null,
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
