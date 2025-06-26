<?php
declare( strict_types = 1 );

namespace MediaWiki\OutputTransform\Stages;

use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;

/**
 * Adds RedirectHeader if it exists
 * @internal
 */
class AddRedirectHeader extends ContentTextTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $po->getRedirectHeader() !== null;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		return $po->getRedirectHeader() . $text;
	}
}
