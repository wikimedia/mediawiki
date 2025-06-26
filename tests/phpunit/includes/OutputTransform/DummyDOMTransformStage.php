<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform;

use MediaWiki\OutputTransform\ContentDOMTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\Parsoid\DOM\Document;

class DummyDOMTransformStage extends ContentDOMTransformStage {

	public function transformDOM( Document $dom, ParserOutput $po, ?ParserOptions $popts, array &$options
	): Document {
		return $dom;
	}

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return true;
	}
}
