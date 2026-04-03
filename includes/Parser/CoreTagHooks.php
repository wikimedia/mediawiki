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
use MediaWiki\MediaWikiServices;

/**
 * Various tag hooks, registered in every Parser
 * @ingroup Parser
 * @deprecated since 1.46, use the ParserCoreTagHooks service instead.
 */
class CoreTagHooks {

	/**
	 * @param Parser $parser
	 * @param ServiceOptions $options
	 *
	 * @return void
	 * @internal
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function register( Parser $parser, ServiceOptions $options ) {
		wfDeprecated( __METHOD__, '1.46' );
		MediaWikiServices::getInstance()->getParserCoreTagHooks()->register(
			$parser
		);
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
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function pre(
		?string $content, array $attribs, Parser $parser, PPFrame $frame
	): string {
		wfDeprecated( __METHOD__, '1.46' );
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->pre(
			$content, $attribs, $parser, $frame
		);
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
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array|string Output of tag hook
	 * @internal
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function html( ?string $content, array $attributes, Parser $parser ) {
		wfDeprecated( __METHOD__, '1.46' );
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->html(
			$content, $attributes, $parser
		);
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
	 *
	 * @param ?string $content
	 * @param array $attributes
	 * @param Parser $parser
	 * @return array
	 * @internal
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function nowiki( ?string $content, array $attributes, Parser $parser ): array {
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->nowiki(
			$content, $attributes, $parser
		);
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
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function gallery( ?string $content, array $attributes, Parser $parser ): string {
		wfDeprecated( __METHOD__, '1.46' );
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->gallery(
			$content, $attributes, $parser
		);
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
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function indicator( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->indicator(
			$content, $attributes, $parser, $frame
		);
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
	 * @deprecated since 1.46; use the ParserCoreTagHooks service instead
	 */
	public static function langconvert( ?string $content, array $attributes, Parser $parser, PPFrame $frame ): string {
		wfDeprecated( __METHOD__, '1.46' );
		return MediaWikiServices::getInstance()->getParserCoreTagHooks()->langconvert(
			$content, $attributes, $parser, $frame
		);
	}

}

/** @deprecated class alias since 1.43 */
class_alias( CoreTagHooks::class, 'CoreTagHooks' );
