<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\DOM\DocumentFragment;

class DummyDOMTransformStage extends ContentDOMTransformStage {

	public function transformDOM( DocumentFragment $df, ParserOutput $po, ?ParserOptions $popts, array &$options
	): DocumentFragment {
		return $df;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return true;
	}
}
