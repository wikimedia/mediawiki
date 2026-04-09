<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Language\ConverterRule;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Language\LanguageConverterFactory;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Page\LinkBatchFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\DOM\Text;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMDataUtils;
use Wikimedia\Parsoid\Utils\DOMTraverser;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * Resolves Parsoid language converter markup to the appropriate
 * variant.
 * @internal
 */
class ParsoidLanguageConverter extends ContentDOMTransformStage {
	/**
	 * @var array<string,true> List of tags inside which content should
	 * not be converted.
	 */
	private static array $skipTags = [
		'script' => true,
		'code' => true,
		'pre' => true,
		'math' => true,
		'svg' => true,
		'style' => true,
	];

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger,
		private SiteConfig $siteConfig,
		private LanguageFactory $languageFactory,
		private LanguageConverterFactory $languageConverterFactory,
		private TitleFactory $titleFactory,
		private UrlUtils $urlUtils,
		private LinkBatchFactory $linkBatchFactory,
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $po->getContentHolder()->isParsoidContent() &&
			// For back-compatibility with old data from the cache, which has
			// already been converted.
			$po->getExtensionData( 'core:parsoid-languageconverter' ) === 'postprocess';
	}

	public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ParserOptions $popts, array &$options
	): DocumentFragment {
		$title = $po->getTitle();
		$title = $title ? $this->titleFactory->newFromLinkTarget( $title ) :
			$this->titleFactory->newFromTextThrow( 'Special:BadTitle/LanguageConverter' );
		$toVariant = $popts->getOption( 'parsoidnewlc' );
		if ( $toVariant ) {
			$lang = $this->languageFactory->getLanguage( $toVariant );
			$targetLanguage = $this->languageFactory->getParentLanguage( $lang ) ?? $lang;
		} else {
			// Note that technically page language is hookable via
			// ContentHandler::getPageLanguage() and could technically be
			// the user language, which then wouldn't be marked as 'used'
			// in the parser options. *However* the only special cases in
			// production seem to be special pages, which aren't cacheable,
			// and the MediaWiki namespace, where the title suffix determines
			// the page language.
			// T267067: This should eventually be handled by putting the
			// target variant into the ParserOptions
			$targetLanguage = $title->getPageLanguage();
		}
		$converter = $this->languageConverterFactory->getLanguageConverter(
			$targetLanguage
		);
		if (
			$toVariant !== null &&
			$converter->hasVariants() &&
			// From parser.php
			( !$popts->getDisableContentConversion() ) &&
			( !$popts->getInterfaceMessage() ) &&
			$po->getPageProperty( 'nocontentconvert' ) === null
		) {
			// Converter::loadTables() is protected, so call ::translate()
			// to load the converter tables. Otherwise trying to add a rule
			// as the very first thing in the wikitext will fail/crash because
			// the tables won't have been loaded yet.
			$converter->translate( 'x', $toVariant );
			// Now actually do the language conversion on the DOM
			$redLinks = [];
			$this->doTraversal( $df, $converter, $toVariant, $redLinks );
			// Adjust red links
			if ( !$this->languageConverterFactory->isLinkConversionDisabled() ) {
				$this->resolveRedLinks( $title, $converter, $toVariant, $redLinks );
			}
			// Set language
			$po->setLanguage( new Bcp47CodeValue(
				LanguageCode::bcp47( $toVariant )
			) );
		} else {
			$targetLanguage = $popts->getTargetLanguage() ??
				( $popts->getInterfaceMessage() ? $popts->getUserLangObj() : null ) ??
				$targetLanguage;
			$po->setLanguage( $targetLanguage );
		}
		/**
		 * A converted title will be provided in the output object if title and
		 * content conversion are enabled, the article text does not contain
		 * a conversion-suppressing double-underscore tag, and no
		 * {{DISPLAYTITLE:...}} is present. DISPLAYTITLE takes precedence over
		 * automatic link conversion.
		 */
		if (
			!$popts->getDisableTitleConversion() &&
			$po->getPageProperty( 'nocontentconvert' ) === null &&
			$po->getPageProperty( 'notitleconvert' ) === null &&
			$po->getDisplayTitle() === false
		) {
			// Apply display title
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
			$titleText = $converter->getConvRuleTitleFragment( $df->ownerDocument );
			if ( $titleText !== null ) {
				// XXX we should be able to sanitize without serializing
				// and reparsing.
				// XXX getFragmentInnerHTML() doesn't serialize rich attributes
				// XXX use ContentHolder?
				$titleText = ContentUtils::toXML( $titleText, [
					'noSideEffects' => true,
				] );
				$titleText = Sanitizer::removeSomeTags( $titleText );
			} else {
				[ $nsText, $nsSeparator, $mainText ] = $converter->convertSplitTitle( $title );
				// In the future, those three pieces could be stored separately rather than joined into $titleText,
				// and OutputPage would format them and join them together, to resolve T314399.
				$titleLang = $this->languageFactory->getLanguage( $po->getLanguage() ?? $targetLanguage );
				$titleText = Parser::formatPageTitle( $nsText, $nsSeparator, $mainText, $titleLang );
			}
			$po->setTitleText( $titleText );
		}
		// Localize/convert TOC
		// (even if conversion is disabled/$converter is null)
		Parser::localizeTOC( $po->getTOCData(), $targetLanguage, $converter );
		return $df;
	}

	private function doTraversal(
		Node $rootNode, ILanguageConverter $converter, string $toVariant,
		array &$redLinks
	): void {
		$traverser = new DOMTraverser(
			traverseWithTplInfo: false,
			applyToAttributeEmbeddedHTML: true,
		);
		$traverser->addHandler( null, function ( $node ) use ( $converter, $toVariant, &$redLinks ) {
			'@phan-var LanguageConverter $converter';
			return $this->convertNode( $node, $converter, $toVariant, $redLinks );
		}, beforeAttributes: true );
		// For efficiency skip the traversal if this is trivial.
		if ( $converter->hasVariants() && $toVariant ) {
			$traverser->traverse( $this->siteConfig, $rootNode );
		}
	}

	/**
	 * Process text and link nodes and Parsoid LanguageConverter markup.
	 * @return null|Node|true
	 */
	protected function convertNode(
		Node $node, ILanguageConverter $converter, string $toVariant,
		array &$redLinks
	) {
		$next = $node->nextSibling;
		if ( $node instanceof Text ) {
			$oldText = $node->data;
			if ( $converter->guessVariant( $oldText, $toVariant ) ) {
				// Yuck: guessVariant should be deprecated.
				return $next;
			}
			$newText = $converter->translate( $oldText, $toVariant );
			if ( $newText !== $oldText ) {
				$node->replaceWith( $newText );
			}
			return $next;
		}
		if ( !( $node instanceof Element ) ) {
			return true;
		}
		$el = $node;
		'@phan-var Element $el';
		// Convert alt or title attributes
		foreach ( [ 'title', 'alt' ] as $attrName ) {
			$val = DOMCompat::getAttribute( $el, $attrName );
			// Don't convert URLs
			if ( $val === null || str_contains( $val, '://' ) ) {
				continue;
			}
			// XXX should look at rich values of attribute
			$newVal = $converter->translate( $val, $toVariant );
			if ( $newVal !== $val ) {
				$el->setAttribute( $attrName, $newVal );
			}
		}
		// Handle skipped nodes
		if ( self::$skipTags[DOMUtils::nodeName( $el )] ?? false ) {
			// don't convert the contents of this node unless it contains
			// -{ (this is emergent behavior inherited from the legacy
			// implementation)

			// XXX Should we also skip over non-parsoid content ("strip tags"
			// in legacy parlance)? Should we do <math> and <svg>?

			if ( DOMUtils::nodeName( $el ) === 'code' &&
				DOMCompat::querySelector( $el, '[typeof="mw:LanguageVariant"]' ) ) {
				// Recurse into <code> if there is language converter markup
				// inside.
				return true;
			}
			if ( DOMUtils::nodeName( $el ) === 'pre' ) {
				// This case should probably be handled in Parsoid, since we
				// need to parse the language converter rules somehow. As a
				// hack we're going to use the legacy ConverterRule parser to
				// parse and convert the contents.
				$contents = $el->textContent;
				if ( str_contains( $contents, '-{' ) ) {
					// Hack: use the legacy parser to parse rules inside <pre>
					DOMCompat::replaceChildren(
						$el, $converter->convertTo(
							$contents, $toVariant, false
						)
					);
				}
			}
			return $el->nextSibling;
		} elseif ( DOMUtils::nodeName( $el ) === 'a' ) {
			// Special handling for links.
			$contents = $el->textContent;
			$classList = DOMCompat::getClassList( $el );
			if ( $classList->contains( 'free' ) ||
				preg_match( '/^(?:' . $this->urlUtils->validAbsoluteProtocols() . ')/', $el->textContent ) ) {
				// Don't recurse into content.
				return $el->nextSibling;
			}
			if ( DOMUtils::hasRel( $el, 'mw:WikiLink' ) && $classList->contains( 'new' ) ) {
				// In a batch at the end we'll attempt to re-resolve the link
				// to a variant title
				$redLinks[] = $el;
			}
		} elseif (
			DOMUtils::hasTypeOf( $el, 'mw:LanguageVariant' ) &&
			$converter instanceof LanguageConverter
		) {
			$rule = new ConverterRule( $converter );
			$nextVariant = $rule->parseElement( $el, $toVariant );
			$converter->applyManualConv( $rule );
			# We use <meta> for rules which aren't expected to produce output.
			if ( DOMUtils::nodeName( $node ) !== 'meta' ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
				$df = $rule->getDisplayFragment( $el->ownerDocument );
				// Do we need to clone this fragment? Should we do it in ConverterRule?
				$df = DOMDataUtils::cloneDocumentFragment( $df );
				if ( $nextVariant !== null ) {
					$this->doTraversal( $df, $converter, $nextVariant, $redLinks );
				}
				DOMCompat::replaceChildren( $el, $df );
			}
			return $el->nextSibling;
		}
		return true;
	}

	/**
	 * @param Title $baseTitle
	 * @param ILanguageConverter $converter
	 * @param string $toVariant
	 * @param array<Element> $redLinks
	 */
	private function resolveRedLinks(
		Title $baseTitle, ILanguageConverter $converter, string $toVariant,
		array $redLinks
	): void {
		$baseHref = $this->siteConfig->baseURI();
		$pageFragmentPrefix = "./" . $baseTitle->getPrefixedDBkey() . '#';

		// Collect all titles for lookup
		$elMap = [];
		foreach ( $redLinks as $a ) {
			$href = DOMCompat::getAttribute( $a, 'href' );
			$wasRelative = false;
			if ( $href === null ) {
				continue;
			}
			// Make absolute URL, for compatibility with passes which use
			// relative URLs here.
			if ( str_starts_with( $href, $pageFragmentPrefix ) ) {
				// relative URLs to current page should not be redlinks
				continue;
			} elseif ( str_starts_with( $href, './' ) ) {
				$href = $this->urlUtils->expand( $baseHref . $href, PROTO_RELATIVE ) ?? $href;
				$wasRelative = true;
			}
			if ( str_starts_with( $href, '#' ) ) {
				// Relative links to current page shouldn't be redlinks!
				continue;
			}
			if ( !str_starts_with( $href, $baseHref ) ) {
				// Not a link to this wiki.
				continue;
			}
			$href = substr( $href, strlen( $baseHref ) );
			$bits = parse_url( "http://example.com/$href" );
			if ( $bits === false ) {
				// Malformed URL in some way
				continue;
			}
			$queryElts = [];
			if ( isset( $bits['query'] ) ) {
				parse_str( $bits['query'], $queryElts );
			}
			// Use the ?title=... part of the query string, if present;
			// otherwise assume the title is in the path.
			$titlePart = $queryElts['title'] ?? substr( $bits['path'] ?? '/', 1 );
			$title = $this->titleFactory->newFromText( $titlePart );
			if ( $title === null ) {
				continue;
			}
			$elMap[] = [ $a, $wasRelative, $title, $bits['fragment'] ?? null, $queryElts ];
		}

		// Collect a link batch
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		$varLookup = [];
		foreach ( $elMap as [ $a, $wasRelative, $title, $fragment, $queryElts ] ) {
			$ns = $title->getNamespace();
			$link = $title->getText();
			if ( isset( $varLookup[$ns][$link] ) ) {
				// We've already created all variants of this title
				continue;
			}
			foreach ( $converter->autoConvertToAllVariants( $link ) as $v ) {
				if ( $v !== $link ) {
					$varnt = $this->titleFactory->newFromText( $v, $ns );
					if ( $varnt !== null ) {
						$linkBatch->addObj( $varnt );
						$varLookup[$ns][$link][] = $varnt;
					}
				}
			}
		}

		// Fetch all variants in a single query
		$ids = $linkBatch->execute();
		// Make a helper function that looks up IDs in this result
		// We're not going to rely on the LinkCache here, since we could
		// potentially have more than LinkCache::MAX_SIZE titles to lookup.
		$titleExists = static fn ( $t ) => ( $ids[$t->getPrefixedDBkey() ] ?? 0 ) > 0;

		// Now see if any of these red links should be made not-red
		foreach ( $elMap as [ $a, $wasRelative, $title, $fragment, $queryElts ] ) {
			$ns = $title->getNamespace();
			$link = $title->getText();
			$variants = $varLookup[$ns][$link] ?? [];
			// Find self-links first, which take precedence
			$varnt = array_find(
				$variants, static fn ( $t ) => $t->isSamePageAs( $baseTitle )
			);
			// Find the first title which resolves to an existing page
			$varnt ??= array_find( $variants, $titleExists );
			if ( $varnt === null ) {
				// No matching variant title found, leave this as a red link
				continue;
			}
			// Found a non-red link, rewrite it.
			$suffix = ( $fragment !== null ) ? "#$fragment" : "";
			unset( $queryElts['title'] );
			unset( $queryElts['action'] );
			unset( $queryElts['redlink'] );
			$href = $wasRelative ? $varnt->getLocalURL( $queryElts ) :
				  $varnt->getFullURL( $queryElts );
			$a->setAttribute( 'href', $href . $suffix );
			DOMCompat::getClassList( $a )->remove( 'new' );
			$a->removeAttribute( 'data-mw-i18n' );
			$a->removeAttribute( 'typeof' );
			$a->setAttribute( 'title', $converter->translate(
				$varnt->getPrefixedText(), $toVariant
			) );
			if ( $varnt->isSamePageAs( $baseTitle ) ) {
				$a->removeAttribute( 'title' );
				if ( $fragment !== null ) {
					$a->setAttribute( 'href', "#$fragment" );
					DOMCompat::getClassList( $a )->add( 'mw-selflink-fragment' );
				} else {
					DOMCompat::getClassList( $a )->add( 'mw-selflink', 'selflink' );
				}
			}
		}
	}
}
