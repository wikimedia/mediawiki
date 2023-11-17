<?php

namespace Mediawiki\ParserOutputTransform;

use Language;
use Linker;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlHelper;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use MediaWiki\Tidy\TidyDriverBase;
use Parser;
use ParserOutput;
use Psr\Log\LoggerInterface;
use RawMessage;
use RequestContext;
use Sanitizer;
use Skin;
use Title;
use Wikimedia\RemexHtml\Serializer\SerializerNode;

/**
 * This class contains the default output transformation pipeline for wikitext. It is a postprocessor for
 * ParserOutput objects either directly resulting from a parse or fetched from ParserCache.
 * @unstable
 */
class DefaultOutputTransform {

	private HookRunner $hookRunner;
	private TidyDriverBase $tidy;
	private LanguageFactory $langFactory;
	private Language $contentLang;
	private LoggerInterface $logger;

	private const EDITSECTION_REGEX =
		'#<mw:editsection page="(.*?)" section="(.*?)">(.*?)</mw:editsection>#s';

	public function __construct(
		HookContainer $hc,
		TidyDriverBase $tidy,
		LanguageFactory $langFactory,
		Language $contentLang,
		LoggerInterface $logger
	) {
		$this->hookRunner = new HookRunner( $hc );
		$this->tidy = $tidy;
		$this->langFactory = $langFactory;
		$this->contentLang = $contentLang;
		$this->logger = $logger;
	}

	/**
	 * Transforms the content of the ParserOutput object from "parsed HTML" to "output HTML" and returns it.
	 * @internal
	 * @param ParserOutput $po - may be mutated in place
	 * @param array $options Transformations to apply to the HTML
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - injectTOC: (bool) Replace the TOC_PLACEHOLDER with TOC contents;
	 *     otherwise the marker will be left in the article (and the skin
	 *     will be responsible for replacing or removing it).  Default is
	 *     true.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - userLang: (Language) Language object used for localizing UX messages,
	 *    for example the heading of the table of contents. If omitted, will
	 *    use the language of the main request context.
	 *  - skin: (Skin) Skin object used for transforming section edit links.
	 *  - unwrap: (bool) Return text without a wrapper div. Default is false,
	 *    meaning a wrapper div will be added if getWrapperDivClass() returns
	 *    a non-empty string.
	 *  - wrapperDivClass: (string) Wrap the output in a div and apply the given
	 *    CSS class to that div. This overrides the output of getWrapperDivClass().
	 *    Setting this to an empty string has the same effect as 'unwrap' => true.
	 *  - deduplicateStyles: (bool) When true, which is the default, `<style>`
	 *    tags with the `data-mw-deduplicate` attribute set are deduplicated by
	 *    value of the attribute: all but the first will be replaced by `<link
	 *    rel="mw-deduplicated-inline-style" href="mw-data:..."/>` tags, where
	 *    the scheme-specific-part of the href is the (percent-encoded) value
	 *    of the `data-mw-deduplicate` attribute.
	 *  - absoluteURLs: (bool) use absolute URLs in all links. Default: false
	 *  - includeDebugInfo: (bool) render PP limit report in HTML. Default: false
	 *  - bodyContentOnly: (bool) . Default: true
	 * @return ParserOutput - may be an in-place mutation of the argument input
	 */
	public function transform( ParserOutput $po, array $options = [] ): ParserOutput {
		$options += [
			'allowTOC' => true,
			'injectTOC' => true,
			'enableSectionEditLinks' => true,
			'userLang' => null,
			'skin' => null,
			'unwrap' => false,
			'wrapperDivClass' => $po->getWrapperDivClass(),
			'deduplicateStyles' => true,
			'absoluteURLs' => false,
			'includeDebugInfo' => false,
			'bodyContentOnly' => true,
		];
		$text = $po->getRawText();
		if (
			$options['bodyContentOnly'] &&
			PageBundleParserOutputConverter::hasPageBundle( $po )
		) {
			$text = $this->extractBody( $text );
		}

		$redirectHeader = $po->getRedirectHeader();
		if ( $redirectHeader ) {
			$text = $redirectHeader . $text;
		}

		if ( $options['includeDebugInfo'] ) {
			$text .= $this->renderDebugInfo( $po );
		}

		$this->hookRunner->onParserOutputPostCacheTransform( $po, $text, $options );

		if ( $options['wrapperDivClass'] !== '' && !$options['unwrap'] ) {
			$pageLang = $this->getLanguageWithFallbackGuess( $po );
			$text = Html::rawElement( 'div', [
				'class' => 'mw-content-' . $pageLang->getDir() . ' ' . $options['wrapperDivClass'],
				'lang' => $pageLang->toBcp47Code(),
				'dir' => $pageLang->getDir(),
			], $text );
		}

		'@phan-var string $text';
		if ( $options['enableSectionEditLinks'] ) {
			// TODO: Skin should not be required.
			// It would be better to define one or more narrow interfaces to use here,
			// so this code doesn't have to depend on all of Skin.
			// See OutputPage::addParserOutputText()
			$text = $this->addSectionLinks( $text, $po, $this->resolveSkin( $options ) );
		} else {
			$text = $this->removeSectionLinks( $text );
		}

		if ( $options['allowTOC'] ) {
			if ( $options['injectTOC'] ) {
				$text = $this->injectToc( $text, $this->resolveUserLanguage( $options ), $po );
			}
		} else {
			$text = Parser::replaceTableOfContentsMarker( $text, '' );
		}

		if ( $options['deduplicateStyles'] ) {
			$text = $this->deduplicateStyles( $text );
		}

		// Expand all relative URLs
		if ( $options['absoluteURLs'] && $text ) {
			$text = Linker::expandLocalLinks( $text );
		}

		$text = $this->hydrateHeaderPlaceholders( $text );

		$po->setTransformedText( $text );
		return $po;
	}

	private function getLanguageWithFallbackGuess( ParserOutput $po ): Language {
		$pageLang = $po->getLanguage();
		if ( $pageLang ) {
			return $this->langFactory->getLanguage( $pageLang );
		} else {
			return $this->contentLang;
		}
	}

	/**
	 * Strip everynthing but the <body>
	 * @param string $text
	 * @return string
	 */
	private function extractBody( string $text ): string {
		// This is a full HTML document, generated by Parsoid.

		// T350952: temporary fix for subpage paths: use Parsoid's
		// <base href> to expand relative links
		$baseHref = '';
		if ( preg_match( '{<base href=["\']([^"\']+)["\'][^>]+>}', $text, $matches ) === 1 ) {
			$baseHref = $matches[1];
		}
		// Strip everything but the <body>
		// Probably would be better to process this as a DOM.
		$text = preg_replace( '!^.*?<body[^>]*>!s', '', $text, 1 );
		$text = preg_replace( '!</body>\s*</html>\s*$!', '', $text, 1 );

		// T350952: Expand relative links
		// What we should be doing here is parsing as a title and then
		// using Title::getLocalURL()
		$text = HtmlHelper::modifyElements(
			$text,
			static function ( SerializerNode $node ): bool {
				return $node->name === 'a' &&
					str_starts_with( $node->attrs['href'] ?? '', './' );
			},
			static function ( SerializerNode $node ) use ( $baseHref ): SerializerNode {
				$href = $baseHref . $node->attrs['href'];
				$node->attrs['href'] =
					wfExpandUrl( $href, PROTO_RELATIVE );
				return $node;
			}
		);
		return $text;
	}

	/**
	 * Generates a list of unique style links
	 * @param string $text
	 * @return string
	 */
	private function deduplicateStyles( string $text ): string {
		$seen = [];
		return preg_replace_callback( '#<style\s+([^>]*data-mw-deduplicate\s*=[^>]*)>.*?</style>#s',
			static function ( $m ) use ( &$seen ) {
				$attr = Sanitizer::decodeTagAttributes( $m[1] );
				if ( !isset( $attr['data-mw-deduplicate'] ) ) {
					return $m[0];
				}

				$key = $attr['data-mw-deduplicate'];
				if ( !isset( $seen[$key] ) ) {
					$seen[$key] = true;

					return $m[0];
				}

				// We were going to use an empty <style> here, but there
				// was concern that would be too much overhead for browsers.
				// So let's hope a <link> with a non-standard rel and href isn't
				// going to be misinterpreted or mangled by any subsequent processing.
				return Html::element( 'link', [
					'rel' => 'mw-deduplicated-inline-style',
					'href' => "mw-data:" . wfUrlencode( $key ),
				] );
			}, $text );
	}

	/**
	 * Inject table of contents (or empty string if there's no sections)
	 * @param string $text
	 * @param Language $userLang
	 * @param ParserOutput $po
	 * @return string
	 */
	private function injectToc( string $text, Language $userLang, ParserOutput $po ): string {
		if ( count( $po->getSections() ) === 0 ) {
			$toc = '';
		} else {
			$toc = Linker::generateTOC( $po->getTOCData(), $userLang );
			$toc = $this->tidy->tidy( $toc, [ Sanitizer::class, 'armorFrenchSpaces' ] );
		}
		return Parser::replaceTableOfContentsMarker( $text, $toc );
	}

	/**
	 * Hydrate slot section header placeholders generated by RevisionRenderer.
	 * @param string $text
	 * @return string
	 */
	private function hydrateHeaderPlaceholders( string $text ) {
		return preg_replace_callback( '#<mw:slotheader>(.*?)</mw:slotheader>#', static function ( $m ) {
			$role = htmlspecialchars_decode( $m[1] );
			// TODO: map to message, using the interface language. Set lang="xyz" accordingly.
			$headerText = $role;

			return $headerText;
		}, $text );
	}

	/**
	 * Replace the section link placeholders by their proper value
	 * @param string $text
	 * @param ParserOutput $po
	 * @param Skin $skin
	 * @return string
	 */
	private function addSectionLinks( string $text, ParserOutput $po, Skin $skin ): string {
		return preg_replace_callback( self::EDITSECTION_REGEX, function ( $m ) use ( $po, $skin ) {
			$editsectionPage = Title::newFromText( htmlspecialchars_decode( $m[1] ) );
			$editsectionSection = htmlspecialchars_decode( $m[2] );
			$editsectionContent = Sanitizer::decodeCharReferences( $m[3] );

			if ( !is_object( $editsectionPage ) ) {
				$this->logger->error( 'DefaultOutputTransform::transform: bad title in editsection placeholder',
					[
						'placeholder' => $m[0],
						'editsectionPage' => $m[1],
						'titletext' => $po->getTitleText(),
						'phab' => 'T261347',
					]
				);
				return '';
			}

			return $skin->doEditSectionLink( $editsectionPage, $editsectionSection, $editsectionContent,
				$skin->getLanguage() );
		}, $text );
	}

	/**
	 * Drops the section links placeholders
	 * @param string $text
	 * @return string
	 */
	private function removeSectionLinks( string $text ): string {
		return preg_replace( self::EDITSECTION_REGEX, '', $text );
	}

	/**
	 * Extracts the userLanguage from the $options array, with a fallback on skin language and request
	 * context language
	 * @param array $options
	 * @return Language
	 */
	private function resolveUserLanguage( array $options ): Language {
		$userLang = $options['userLang'];
		$skin = $options['skin'];
		if ( ( !$userLang ) && $skin ) {
			// TODO: See above comment about replacing the use of 'skin' here
			$userLang = $skin->getLanguage();
		}
		if ( !$userLang ) {
			// T348853 passing either userLang or skin will be mandatory in the future
			$userLang = RequestContext::getMain()->getLanguage();
		}
		return $userLang;
	}

	/**
	 * Extracts the skin from the $options array, with a fallback on request context skin
	 * @param array $options
	 * @return Skin
	 */
	private function resolveSkin( array $options ): Skin {
		$skin = $options[ 'skin' ] ?? null;
		if ( !$skin ) {
			// T348853 passing $skin will be mandatory in the future
			$skin = RequestContext::getMain()->getSkin();
		}
		return $skin;
	}

	/**
	 * @return string
	 */
	private function renderDebugInfo( ParserOutput $po ): string {
		$text = '';

		$limitReportData = $po->getLimitReportData();
		// If nothing set it, we can't get it.
		if ( $limitReportData ) {
			$limitReport = "NewPP limit report\n";

			if ( array_key_exists( 'cachereport-origin', $limitReportData ) ) {
				$limitReport .= "Parsed by {$limitReportData['cachereport-origin']}\n";
			}

			if ( array_key_exists( 'cachereport-timestamp', $limitReportData ) ) {
				$limitReport .= "Cached time: {$limitReportData['cachereport-timestamp']}\n";
			}

			if ( array_key_exists( 'cachereport-ttl', $limitReportData ) ) {
				$limitReport .= "Cache expiry: {$limitReportData['cachereport-ttl']}\n";
			}

			if ( array_key_exists( 'cachereport-transientcontent', $limitReportData ) ) {
				$transient = $limitReportData['cachereport-transientcontent'] ? 'true' : 'false';
				$limitReport .= "Reduced expiry: $transient\n";
			}

			// TODO: flags should go into limit report too.
			$limitReport .= 'Complications: [' . implode( ', ', $po->getAllFlags() ) . "]\n";

			foreach ( $limitReportData as $key => $value ) {
				if ( in_array( $key, [
					'cachereport-origin',
					'cachereport-timestamp',
					'cachereport-ttl',
					'cachereport-transientcontent',
					'limitreport-timingprofile',
				] ) ) {
					// These keys are processed separately.
					continue;
				}
				if ( $this->hookRunner->onParserLimitReportFormat(
					$key, $value, $limitReport, false, false )
				) {
					$keyMsg = wfMessage( $key )->inLanguage( 'en' )->useDatabase( false );
					$valueMsg = wfMessage( [ "$key-value-text", "$key-value" ] )
						->inLanguage( 'en' )->useDatabase( false );
					if ( !$valueMsg->exists() ) {
						$valueMsg = new RawMessage( '$1' );
					}
					if ( !$keyMsg->isDisabled() && !$valueMsg->isDisabled() ) {
						$valueMsg->params( $value );
						$limitReport .= "{$keyMsg->text()}: {$valueMsg->text()}\n";
					}
				}
			}
			// Since we're not really outputting HTML, decode the entities and
			// then re-encode the things that need hiding inside HTML comments.
			$limitReport = htmlspecialchars_decode( $limitReport );

			// Sanitize for comment. Note '‐' in the replacement is U+2010,
			// which looks much like the problematic '-'.
			$limitReport = str_replace( [ '-', '&' ], [ '‐', '&amp;' ], $limitReport );
			$text = "\n<!-- \n$limitReport-->\n";

			$profileReport = $limitReportData['limitreport-timingprofile'] ?? null;
			if ( $profileReport ) {
				$text .= "<!--\nTransclusion expansion time report (%,ms,calls,template)\n";
				$text .= implode( "\n", $profileReport ) . "\n-->\n";
			}
		}

		if ( $po->getCacheMessage() ) {
			$text .= "\n<!-- " . $po->getCacheMessage() . "\n -->\n";
		}

		$parsoidVersion = $po->getExtensionData( 'core:parsoid-version' );
		if ( $parsoidVersion ) {
			$text .= "\n<!--Parsoid $parsoidVersion-->\n";
		}

		return $text;
	}
}
