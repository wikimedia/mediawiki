<?php

namespace Mediawiki\OutputTransform\Stages;

use Mediawiki\OutputTransform\ContentTextTransformStage;
use ParserOptions;
use ParserOutput;

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
