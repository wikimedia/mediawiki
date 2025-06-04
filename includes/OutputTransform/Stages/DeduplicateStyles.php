<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Html\Html;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

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
		$isParsoidContent = $options['isParsoidContent'] ?? false;

		$transform = static function ( $fragment ) use ( &$seen, $isParsoidContent ) {
			return HtmlHelper::modifyElements(
				$fragment,
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
				$isParsoidContent
			);
		};

		if ( !$isParsoidContent ) {
			// Optimization: Only transform possible style nodes to avoid having to tokenize the entire output,
			// which is expensive for large pages (T394059).
			// This is unsafe to do for Parsoid content, since the naïve regex below might match encoded style
			// tags within data-parsoid attribute values, so only apply it to legacy parser output.
			// Parsoid content transformations will be further optimized in T394005.
			return preg_replace_callback(
				'#<style\s+([^>]*data-mw-deduplicate\s*=[\'"][^>]*)>.*?</style>#s',
				static fn ( array $matches ) => $transform( $matches[0] ),
				$text
			);
		}

		return $transform( $text );
	}
}
