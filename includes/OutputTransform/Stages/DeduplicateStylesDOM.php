<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMTraverser;

/**
 * Generates a list of unique style links
 * @internal
 */
class DeduplicateStylesDOM extends ContentDOMTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return ( $options['deduplicateStyles'] ?? true );
	}

	public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ?ParserOptions $popts, array &$options
	): DocumentFragment {
		$seen = [];
		$traverser = new DOMTraverser( false, false );
		$traverser->addHandler( "style", static function ( Node $node ) use ( $df, &$seen ) {
			'@phan-var Element $node'; // <style> nodes have Element type
			$key = DOMCompat::getAttribute( $node, 'data-mw-deduplicate' ) ?? '';
			if ( $key === '' ) {
				return true;
			}
			if ( !isset( $seen[$key] ) ) {
				$seen[$key] = true;
				return true;
			}
			// We were going to use an empty <style> here, but there
			// was concern that would be too much overhead for browsers.
			// So let's hope a <link> with a non-standard rel and href isn't
			// going to be misinterpreted or mangled by any subsequent processing.
			$link = $df->ownerDocument->createElement( 'link' );
			$link->setAttribute( 'rel', 'mw-deduplicated-inline-style' );
			$link->setAttribute( 'href', "mw-data:" . wfUrlencode( $key ) );
			$node->parentNode->insertBefore( $link, $node );
			$node->parentNode->removeChild( $node );
			return $link;
		} );

		$traverser->traverse( null, $df );

		return $df;
	}
}
