<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMTraverser;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * Applies base href to relative urls in attributes
 * @internal
 */
class ExpandRelativeAttrs extends ContentDOMTransformStage {

	public function __construct(
		ServiceOptions $options,
		LoggerInterface $logger,
		private UrlUtils $urlUtils,
		private SiteConfig $siteConfig,
		// @phan-suppress-next-line PhanUndeclaredTypeParameter, PhanUndeclaredTypeProperty
		private ?\MobileContext $mobileContext
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $po->getContentHolder()->isParsoidContent();
	}

	private const EXPAND_ELEMENTS = [
		'a' => 'href',
		'area' => 'href',
		'audio' => 'resource',
		'img' => 'resource',
		'video' => 'resource',
	];

	private static function expandRelativeAttrs(
		Element $node,
		string $baseHref,
		string $pageFragmentPrefix,
		UrlUtils $urlUtils
	): void {
		// T350952: Expand relative links
		// It would be nice if we could share more code with Title::getLocalURL
		// but we don't want to have to reparse the Title.  Maybe if
		// TitleValue::getLocalURL() existed we could use that.
		$attr = self::EXPAND_ELEMENTS[DOMUtils::nodeName( $node )] ?? null;
		if ( $attr == null ) {
			return;
		}
		$href = DOMCompat::getAttribute( $node, $attr ) ?? '';
		if ( !str_starts_with( $href, './' ) ) {
			return;
		}

		// Convert page fragment urls to true fragment urls
		// This ensures that those fragments include any URL query params
		// and resolve internally. (Ex: on pages with ?useparsoid=1,
		// cite link fragments should not take you to a different page).
		if ( $pageFragmentPrefix && str_starts_with( $href, $pageFragmentPrefix ) ) {
			$href = substr( $href, strlen( $pageFragmentPrefix ) - 1 );
		} else {
			$href = $urlUtils->expand( $baseHref . $href, PROTO_RELATIVE ) ?? false;
		}
		$node->setAttribute( $attr, $href );
	}

	public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ?ParserOptions $popts, array &$options
	): DocumentFragment {
		$title = $po->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY );
		if ( !$title ) {
			// We don't think this should ever trigger, but being conservative
			$this->logger->error( __METHOD__ . ": Missing title information in ParserOutput" );
		}
		$pageFragmentPrefix = "./" . $title . "#";

		// Once the ParserCache content has turned over, we can rely exclusively
		// on extension-data to get base href. Till then, we need the shared state fix.
		$baseHref = $this->siteConfig->baseURI();
		// @phan-suppress-next-line PhanUndeclaredClassMethod
		if ( $this->mobileContext !== null && $this->mobileContext->usingMobileDomain() ) {
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			$mobileUrl = $this->mobileContext->getMobileUrl( $baseHref );
			if ( $mobileUrl !== false ) {
				$baseHref = $mobileUrl;
			}
		}

		// Convert the main doc
		$traverser = new DOMTraverser( false, false );
		$traverser->addHandler( null, function ( $node ) use ( $baseHref, $pageFragmentPrefix ) {
			if ( $node instanceof Element ) {
				$this->expandRelativeAttrs( $node, $baseHref, $pageFragmentPrefix, $this->urlUtils );
			}
			return true;
		} );
		$traverser->traverse( null, $df );

		// Convert indicators
		$ownerDoc = $df->ownerDocument;
		'@phan-var Document $ownerDoc';
		foreach ( $po->getIndicators() as $name => $html ) {
			$indicatorFrag = DOMUtils::parseHTMLToFragment( $ownerDoc, $html );
			$traverser->traverse( null, $indicatorFrag );
			$po->setIndicator(
				$name,
				ContentUtils::toXML( $indicatorFrag, [ 'innerXML' => true ] )
			);
		}

		return $df;
	}
}
