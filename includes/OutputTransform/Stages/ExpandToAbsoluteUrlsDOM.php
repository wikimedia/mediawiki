<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\DOM\Node;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMTraverser;

/**
 * Expand relative links to absolute URLs in a DOM pass
 * @internal
 */
class ExpandToAbsoluteUrlsDOM extends ContentDOMTransformStage {

	public function __construct(
		ServiceOptions $options, LoggerInterface $logger,
		private readonly UrlUtils $urlUtils,
	) {
		parent::__construct( $options, $logger );
	}

	public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
		return $options['absoluteURLs'] ?? false;
	}

	public function transformDOM(
		DocumentFragment $df, ParserOutput $po, ParserOptions $popts, array &$options
	): DocumentFragment {
		$traverser = new DOMTraverser( false, false );
		$traverser->addHandler( "a", function ( Node $a ) {
			'@phan-var Element $a'; // <a> nodes are Elements
			$href = DOMCompat::getAttribute( $a, 'href' ) ?? '';
			if ( $href !== '' ) {
				$href = $this->urlUtils->expand( $href, PROTO_RELATIVE );
				if ( $href !== null ) {
					$a->setAttribute( 'href', $href );
				}
			}
			return true;
		} );

		$traverser->traverse( null, $df );

		return $df;
	}

}
