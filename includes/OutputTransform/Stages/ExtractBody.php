<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Html\HtmlHelper;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\RemexHtml\Serializer\SerializerNode;

/**
 * Applies base href, and strip everything but the <body>
 * @internal
 */
class ExtractBody extends ContentTextTransformStage {

	private UrlUtils $urlUtils;

	// @phan-suppress-next-line PhanUndeclaredTypeProperty
	private ?\MobileContext $mobileContext;

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger, UrlUtils $urlUtils,
		// @phan-suppress-next-line PhanUndeclaredTypeParameter
		?\MobileContext $mobileContext
	) {
		parent::__construct( $options, $logger );
		$this->urlUtils = $urlUtils;
		$this->mobileContext = $mobileContext;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return ( $options['isParsoidContent'] ?? false );
	}

	private const EXPAND_ELEMENTS = [
		'a' => 'href',
		'area' => 'href',
		'audio' => 'resource',
		'img' => 'resource',
		'video' => 'resource',
	];

	private static function expandRelativeAttrs(
		string $text,
		string $baseHref,
		string $pageFragmentPrefix,
		UrlUtils $urlUtils
	): string {
		// T350952: Expand relative links
		// What we should be doing here is parsing as a title and then
		// using Title::getLocalURL()
		return HtmlHelper::modifyElements(
			$text,
			static function ( SerializerNode $node ): bool {
				$attr = self::EXPAND_ELEMENTS[$node->name] ?? null;
				if ( $attr === null ) {
					return false;
				}
				return str_starts_with( $node->attrs[$attr] ?? '', './' );
			},
			static function ( SerializerNode $node ) use ( $baseHref, $pageFragmentPrefix, $urlUtils ): SerializerNode {
				$attr = self::EXPAND_ELEMENTS[$node->name];
				$href = $node->attrs[$attr];
				// Convert page fragment urls to true fragment urls
				// This ensures that those fragments include any URL query params
				// and resolve internally. (Ex: on pages with ?useparsoid=1,
				// cite link fragments should not take you to a different page).
				if ( $pageFragmentPrefix && str_starts_with( $href, $pageFragmentPrefix ) ) {
					$node->attrs[$attr] = substr( $href, strlen( $pageFragmentPrefix ) - 1 );
				} else {
					$href = $baseHref . $href;
					$node->attrs[$attr] = $urlUtils->expand( $href, PROTO_RELATIVE ) ?? false;
				}
				return $node;
			}
		);
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		// T350952: temporary fix for subpage paths: use Parsoid's
		// <base href> to expand relative links
		$baseHref = '';
		if ( preg_match( '{<base href=["\']([^"\']+)["\'][^>]+>}', $text, $matches ) === 1 ) {
			$baseHref = $matches[1];
			// @phan-suppress-next-line PhanUndeclaredClassMethod
			if ( $this->mobileContext !== null && $this->mobileContext->usingMobileDomain() ) {
				// @phan-suppress-next-line PhanUndeclaredClassMethod
				$mobileUrl = $this->mobileContext->getMobileUrl( $baseHref );
				if ( $mobileUrl !== false ) {
					$baseHref = $mobileUrl;
				}
			}
		}
		$title = $po->getExtensionData( ParsoidParser::PARSOID_TITLE_KEY );
		if ( !$title ) {
			// We don't think this should ever trigger, but being conservative
			$this->logger->error( __METHOD__ . ": Missing title information in ParserOutput" );
		}
		$pageFragmentPrefix = "./" . $title . "#";
		foreach ( $po->getIndicators() as $name => $html ) {
			$po->setIndicator(
				$name,
				self::expandRelativeAttrs( $html, $baseHref, $pageFragmentPrefix, $this->urlUtils )
			);
		}
		$text = Parser::extractBody( $text );
		return self::expandRelativeAttrs( $text, $baseHref, $pageFragmentPrefix, $this->urlUtils );
	}
}
