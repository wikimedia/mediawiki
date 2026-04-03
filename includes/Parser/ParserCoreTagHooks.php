<?php
/**
 * Tag hooks provided by MediaWiki core
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverterFactory;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\MainConfigNames;
use UnexpectedValueException;
use Wikimedia\StringUtils\StringUtils;

/**
 * Various tag hooks, registered in every Parser
 * @ingroup Parser
 * @since 1.46
 */
class ParserCoreTagHooks {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		// See documentation for the corresponding config options
		MainConfigNames::RawHtml,
	];

	/**
	 * @internal
	 */
	public function __construct(
		private readonly ServiceOptions $svcOptions,
		private readonly LanguageFactory $languageFactory,
		private readonly LanguageConverterFactory $languageConverterFactory,
	) {
		$svcOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * @internal
	 */
	public function register( Parser $parser ): void {
		$rawHtml = $this->svcOptions->get( MainConfigNames::RawHtml );
		$parser->setHook( 'pre', $this->pre( ... ) );
		$parser->setHook( 'nowiki', $this->nowiki( ... ) );
		$parser->setHook( 'gallery', $this->gallery( ... ) );
		$parser->setHook( 'indicator', $this->indicator( ... ) );
		$parser->setHook( 'langconvert', $this->langconvert( ... ) );
		if ( $rawHtml ) {
			$parser->setHook( 'html', $this->html( ... ) );
		}
	}

	/**
	 * Core parser tag hook function for 'pre'.
	 * Text is treated roughly as 'nowiki' wrapped in an HTML 'pre' tag;
	 * valid HTML attributes are passed on.
	 *
	 * @param ?string $content
	 * @param array $attribs
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string HTML
	 * @internal
	 */
	public function pre(
		?string $content, array $attribs, Parser $parser, PPFrame $frame
	): string {
		$content ??= '';

		if ( ( $attribs['format'] ?? '' ) === 'wikitext' ) {
			// T348722: $frame is omitted here.  Editors should
			// use the tag parser function if they want access
			// to template args, for compatibility with Parsoid
			$content = $parser->recursiveTagParse( $content );
		} else {
			// Backwards-compatibility hack
			$content = StringUtils::delimiterReplace(
				'<nowiki>', '</nowiki>', '$1', $content, 'i'
			);

			// We need to let both '"' and '&' through,
			// for strip markers and entities respectively.
			$content = str_replace(
				[ '>', '<' ],
				[ '&gt;', '&lt;' ],
				$content
			);
		}

		$attribs = array_map( $parser->killMarkers( ... ), $attribs );
		$attribs = Sanitizer::validateTagAttributes( $attribs, 'pre' );

		// @phan-suppress-next-line SecurityCheck-XSS escaped in previous line
		return Html::rawElement( 'pre', $attribs, $content );
	}

	/**
	 * Core parser tag hook function for 'html', used only when
	 * $wgRawHtml is enabled.
	 *
	 * This is potentially unsafe and should be used only in very careful
	 * circumstances, as the contents are emitted as raw HTML.
	 *
	 * Uses undocumented extended tag hook return values, introduced in r61913.
	 *
	 * @suppress SecurityCheck-XSS
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array|string Output of tag hook
	 * @internal
	 */
	public function html( ?string $content, array $attributes, Parser $parser ) {
		$rawHtml = $this->svcOptions->get( MainConfigNames::RawHtml );
		if ( $rawHtml ) {
			if ( $parser->getOptions()->getAllowUnsafeRawHtml() ) {
				return [ $content ?? '', 'markerType' => 'nowiki' ];
			} else {
				// In a system message where raw html is
				// not allowed (but it is allowed in other
				// contexts).
				return Html::element( 'span',
					[ 'class' => 'error' ],
					$parser->msg( 'rawhtml-notallowed' )->text()
				);
			}
		} else {
			throw new UnexpectedValueException( '<html> extension tag encountered unexpectedly' );
		}
	}

	/**
	 * Core parser tag hook function for 'nowiki'. Text within this section
	 * gets interpreted as a string of text with HTML-compatible character
	 * references, and wiki markup within it will not be expanded.
	 *
	 * Uses undocumented extended tag hook return values, introduced in r61913.
	 *
	 * Used outside core by the Scribunto extension.
	 *
	 * Uses custom html escaping which phan-taint-check won't recognize
	 * hence we suppress the error.
	 * @suppress SecurityCheck-XSS
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array
	 */
	public function nowiki( ?string $content, array $attributes, Parser $parser ): array {
		$content = strtr( $content ?? '', [
			// lang converter
			'-{' => '-&#123;',
			'}-' => '&#125;-',
			// html tags
			'<' => '&lt;',
			'>' => '&gt;'
			// Note: Both '"' and '&' are not converted.
			// This allows strip markers and entities through.
		] );
		return [ $content, 'markerType' => 'nowiki' ];
	}

	/**
	 * Core parser tag hook function for 'gallery'.
	 *
	 * Renders a thumbnail list of the given images, with optional captions.
	 * Full syntax documented on the wiki:
	 *
	 *   https://www.mediawiki.org/wiki/Help:Images#Gallery_syntax
	 *
	 * @todo break Parser::renderImageGallery out here too.
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return string HTML
	 * @internal
	 */
	public function gallery( ?string $content, array $attributes, Parser $parser ): string {
		return $parser->renderImageGallery( $content ?? '', $attributes );
	}

	/**
	 * XML-style tag for page status indicators: icons (or short text snippets) usually displayed in
	 * the top-right corner of the page, outside of the main content.
	 *
	 * Used outside core by the CommunityRequests extension.
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 */
	public function indicator( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
		if ( !isset( $attributes['name'] ) || trim( $attributes['name'] ) === '' ) {
			return '<span class="error">' .
				$parser->msg( 'invalid-indicator-name' )->parse() .
				'</span>';
		}

		$parser->getOutput()->setIndicator(
			trim( $parser->killMarkers( $attributes['name'] ) ),
			Parser::stripOuterParagraph( $parser->recursiveTagParseFully( $content ?? '', $frame ) )
		);

		return '';
	}

	/**
	 * Returns content converted into the requested language variant, using LanguageConverter.
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 * @internal
	 */
	public function langconvert( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
		if ( isset( $attributes['from'] ) && isset( $attributes['to'] ) ) {
			$fromArg = trim( $attributes['from'] );
			$toArg = trim( $attributes['to'] );
			$fromLangCode = explode( '-', $fromArg )[0];
			if ( $fromLangCode && $fromLangCode === explode( '-', $toArg )[0] ) {
				$lang = $this->languageFactory
					->getLanguage( $fromLangCode );
				$converter = $this->languageConverterFactory
					->getLanguageConverter( $lang );

				# ensure that variants are available,
				# and the variants are valid BCP 47 codes
				if ( $converter->hasVariants()
					&& strcasecmp( $fromArg, LanguageCode::bcp47( $fromArg ) ) === 0
					&& strcasecmp( $toArg, LanguageCode::bcp47( $toArg ) ) === 0
				) {
					$toVariant = $converter->validateVariant( $toArg );

					if ( $toVariant ) {
						return $converter->autoConvert(
							$parser->recursiveTagParse( $content ?? '', $frame ),
							$toVariant
						);
					}
				}
			}
		}

		return Html::rawElement(
			'span',
			[ 'class' => 'error' ],
			$parser->msg( 'invalid-langconvert-attrs' )->parse()
		);
	}

}
