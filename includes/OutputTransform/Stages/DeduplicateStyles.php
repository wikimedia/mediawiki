<?php

namespace Mediawiki\OutputTransform\Stages;

use MediaWiki\Html\HtmlHelper;
use Mediawiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOutput;
use ParserOptions;
use Wikimedia\RemexHtml\Serializer\SerializerNode;
use Wikimedia\RemexHtml\Tokenizer\PlainAttributes;

/**
 * Generates a list of unique style links
 * @internal
 */
class DeduplicateStyles extends ContentTextTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $options['deduplicateStyles'] ?? true;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		$seen = [];
		return HtmlHelper::modifyElements(
			$text,
			static function ( SerializerNode $node ): bool {
				return $node->name === 'style' &&
					( $node->attrs['data-mw-deduplicate'] ?? '' ) !== '';
			},
			static function ( SerializerNode $node ) use ( &$seen ): SerializerNode {
				$key = $node->attrs['data-mw-deduplicate'];
				if ( !isset( $seen[$key] ) ) {
					$seen[$key] = true;
					return $node;
				}
				// We were going to use an empty <style> here, but there
				// was concern that would be too much overhead for browsers.
				// So let's hope a <link> with a non-standard rel and href isn't
				// going to be misinterpreted or mangled by any subsequent processing.
				$node->name = 'link';
				$node->attrs = new PlainAttributes( [
					'rel' => 'mw-deduplicated-inline-style',
					'href' => "mw-data:" . wfUrlencode( $key ),
				] );
				$node->children = [];
				$node->void = true;
				return $node;
			},
			$options['isParsoidContent'] ?? false
		);
	}
}
