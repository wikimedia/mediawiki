<?php

namespace Mediawiki\OutputTransform\Stages;

use MediaWiki\Html\HtmlHelper;
use Mediawiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOutput;
use Parser;
use ParserOptions;
use Wikimedia\RemexHtml\Serializer\SerializerNode;

/**
 * Applies base href, and strip everything but the <body>
 * @internal
 */
class ExtractBody extends ContentTextTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return ( $options['isParsoidContent'] ?? false ) && ( $options['bodyContentOnly'] ?? true );
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		// T350952: temporary fix for subpage paths: use Parsoid's
		// <base href> to expand relative links
		$baseHref = '';
		if ( preg_match( '{<base href=["\']([^"\']+)["\'][^>]+>}', $text, $matches ) === 1 ) {
			$baseHref = $matches[1];
		}
		$text = Parser::extractBody( $text );
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
}
