<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\DOMTransformStage;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMTraverser;

/**
 * Generates a list of unique style links
 *
 * This is a DOM transform but uses OutputTransformStage so that
 * we can maintain some state between fragments.
 *
 * @internal
 */
class DeduplicateStylesDOM extends OutputTransformStage
	implements DOMTransformStage {

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return ( $options['deduplicateStyles'] ?? true );
	}

	public function transform(
		ParserOutput $po, ParserOptions $popts, array &$options
	): ParserOutput {
		$contentHolder = $po->getContentHolder();
		$seen = [];
		$traverser = new DOMTraverser( false, false );
		$traverser->addHandler( "style", static function ( Node $node ) use ( &$seen ) {
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
			$link = $node->ownerDocument->createElement( 'link' );
			$link->setAttribute( 'rel', 'mw-deduplicated-inline-style' );
			$link->setAttribute( 'href', "mw-data:" . wfUrlencode( $key ) );
			$node->parentNode->insertBefore( $link, $node );
			$node->parentNode->removeChild( $node );
			return $link;
		} );

		// Do body fragment first, since that will be the canonical location
		// of the style
		$fragmentNames = $contentHolder->getFragmentNames( bodyFirst: true );
		foreach ( $fragmentNames as $name ) {
			$df = $contentHolder->getAsDom( $name );
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable $df is non-null
			$traverser->traverse( null, $df );
		}
		return $po;
	}
}
