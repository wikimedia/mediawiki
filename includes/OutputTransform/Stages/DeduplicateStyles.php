<?php

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Html\Html;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;

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
		return preg_replace_callback( '#<style\s+([^>]*data-mw-deduplicate\s*=[\'"][^>]*)>.*?</style>#s',
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
}
