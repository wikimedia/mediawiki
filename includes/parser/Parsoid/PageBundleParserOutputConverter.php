<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Language\LanguageCode;
use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\Core\BasePageBundle;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * Provides methods for conversion between HtmlPageBundle and ParserOutput
 * TODO: Convert to a trait once we drop support for PHP < 8.2 since
 * support for constants in traits was added in PHP 8.2
 * @since 1.40
 * @internal
 */
final class PageBundleParserOutputConverter {
	/**
	 * @var string Key used to store parsoid page bundle data in ParserOutput
	 * @deprecated since 1.45; use ParserOutput::PARSOID_PAGE_BUNDLE_KEY
	 */
	public const PARSOID_PAGE_BUNDLE_KEY = ParserOutput::PARSOID_PAGE_BUNDLE_KEY;

	/**
	 * We do not want instances of this class to be created
	 * @return void
	 */
	private function __construct() {
	}

	/**
	 * Creates a ParserOutput object containing the relevant data from
	 * the given HtmlPageBundle object.
	 *
	 * We need to inject data-parsoid and other properties into the
	 * parser output object for caching, so we can use it for VE edits
	 * and transformations.
	 *
	 * @param HtmlPageBundle $pageBundle
	 * @param ?ParserOutput $originalParserOutput Any non-parsoid metadata
	 *  from $originalParserOutput will be copied into the new ParserOutput object.
	 *
	 * @return ParserOutput
	 */
	public static function parserOutputFromPageBundle(
		HtmlPageBundle $pageBundle, ?ParserOutput $originalParserOutput = null
	): ParserOutput {
		$parserOutput = new ParserOutput();
		$parserOutput->setContentHolder(
			ContentHolder::createFromParsoidPageBundle( $pageBundle )
		);
		if ( $originalParserOutput ) {
			// Merging metadata from the original parser output will also
			// potentially transfer fragments from
			// $originalParserOutput->getContentHolder() to
			// $parserOutput->getContentHolder()
			$parserOutput->mergeHtmlMetaDataFrom( $originalParserOutput );
			$parserOutput->mergeTrackingMetaDataFrom( $originalParserOutput );
			$parserOutput->mergeInternalMetaDataFrom( $originalParserOutput );
		}
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
	 * Returns a Parsoid HtmlPageBundle equivalent to the given ParserOutput.
	 * @param ParserOutput $parserOutput
	 *
	 * @return HtmlPageBundle
	 * @deprecated Use ::htmlPageBundleFromParserOutput
	 */
	public static function pageBundleFromParserOutput( ParserOutput $parserOutput ): HtmlPageBundle {
		return self::htmlPageBundleFromParserOutput( $parserOutput );
	}

	/**
	 * Returns a Parsoid HtmlPageBundle equivalent to the given ParserOutput.
	 * @param ParserOutput $parserOutput
	 *
	 * @return HtmlPageBundle
	 */
	public static function htmlPageBundleFromParserOutput( ParserOutput $parserOutput ): HtmlPageBundle {
		$bpb = self::basePageBundleFromParserOutput( $parserOutput );
		$pb = $bpb->withHtml( $parserOutput->getContentHolderText() );
		// NOTE that the fragments from the ContentHolder are missing
		// from this page bundle.  It is assumed that the fragments
		// are referenced from other parts of the ParserOutput; aka that
		// they are loaded/saved as part of ParserOuput::$mIndicators
		return $pb;
	}

	private static function basePageBundleFromParserOutput( ParserOutput $parserOutput ): BasePageBundle {
		$contentHolder = $parserOutput->getContentHolder();
		$basePageBundle = $contentHolder->isParsoidContent() ?
			$contentHolder->getBasePageBundle() :
			new BasePageBundle(
				parsoid: [ 'ids' => [] ],
				headers: [],
				// It would be nice to have this be "null", but
				// ParsoidFormatHelper chokes on that: T325137.
				version: '0.0.0',
			);
		$lang = $parserOutput->getLanguage();

		if ( $lang ) {
			$basePageBundle->headers ??= [];
			$basePageBundle->headers['content-language'] = $lang->toBcp47Code();
		}
		return $basePageBundle;
	}

	public static function hasPageBundle( ParserOutput $parserOutput ): bool {
		return $parserOutput->getContentHolder()->isParsoidContent();
	}
}
