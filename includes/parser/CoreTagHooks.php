<?php
/**
 * Tag hooks provided by MediaWiki core
 *
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
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use UnexpectedValueException;
use Wikimedia\StringUtils\StringUtils;

/**
 * Various tag hooks, registered in every Parser
 * @ingroup Parser
 */
class CoreTagHooks {

	/**
	 * @internal
	 */
	public const REGISTER_OPTIONS = [
		// See documentation for the corresponding config options
		MainConfigNames::RawHtml,
	];

	/**
	 * @param Parser $parser
	 * @param ServiceOptions $options
	 *
	 * @return void
	 * @internal
	 */
	public static function register( Parser $parser, ServiceOptions $options ) {
		$options->assertRequiredOptions( self::REGISTER_OPTIONS );
		$rawHtml = $options->get( MainConfigNames::RawHtml );
		$parser->setHook( 'pre', [ self::class, 'pre' ] );
		$parser->setHook( 'nowiki', [ self::class, 'nowiki' ] );
		$parser->setHook( 'gallery', [ self::class, 'gallery' ] );
		$parser->setHook( 'indicator', [ self::class, 'indicator' ] );
		$parser->setHook( 'langconvert', [ self::class, 'langconvert' ] );
		if ( $rawHtml ) {
			$parser->setHook( 'html', [ self::class, 'html' ] );
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
	 * @return string HTML
	 * @internal
	 */
	public static function pre( ?string $content, array $attribs, Parser $parser ): string {
		// Backwards-compatibility hack
		$content = StringUtils::delimiterReplace( '<nowiki>', '</nowiki>', '$1', $content ?? '', 'i' );

		$attribs = array_map( static fn ( $s ) => $parser->killMarkers( $s ), $attribs );
		$attribs = Sanitizer::validateTagAttributes( $attribs, 'pre' );
		// We need to let both '"' and '&' through,
		// for strip markers and entities respectively.
		$content = str_replace(
			[ '>', '<' ],
			[ '&gt;', '&lt;' ],
			$content
		);
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
	public static function html( ?string $content, array $attributes, Parser $parser ) {
		$rawHtml = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::RawHtml );
		if ( $rawHtml ) {
			if ( $parser->getOptions()->getAllowUnsafeRawHtml() ) {
				return [ $content ?? '', 'markerType' => 'nowiki' ];
			} else {
				// In a system message where raw html is
				// not allowed (but it is allowed in other
				// contexts).
				return Html::rawElement(
					'span',
					[ 'class' => 'error' ],
					// Using ->text() not ->parse() as
					// a paranoia measure against a loop.
					$parser->msg( 'rawhtml-notallowed' )->escaped()
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
	 * Uses custom html escaping which phan-taint-check won't recognize
	 * hence we suppress the error.
	 * @suppress SecurityCheck-XSS
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array
	 * @internal
	 */
	public static function nowiki( ?string $content, array $attributes, Parser $parser ): array {
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
	public static function gallery( ?string $content, array $attributes, Parser $parser ): string {
		return $parser->renderImageGallery( $content ?? '', $attributes );
	}

	/**
	 * XML-style tag for page status indicators: icons (or short text snippets) usually displayed in
	 * the top-right corner of the page, outside of the main content.
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * @return string
	 * @since 1.25
	 * @internal
	 */
	public static function indicator( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
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
	 * @since 1.36
	 * @internal
	 */
	public static function langconvert( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
		if ( isset( $attributes['from'] ) && isset( $attributes['to'] ) ) {
			$fromArg = trim( $attributes['from'] );
			$toArg = trim( $attributes['to'] );
			$fromLangCode = explode( '-', $fromArg )[0];
			if ( $fromLangCode && $fromLangCode === explode( '-', $toArg )[0] ) {
				$lang = MediaWikiServices::getInstance()->getLanguageFactory()
					->getLanguage( $fromLangCode );
				$converter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
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

/** @deprecated class alias since 1.43 */
class_alias( CoreTagHooks::class, 'CoreTagHooks' );
