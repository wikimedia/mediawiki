<?php

namespace Mediawiki\OutputTransform\Stages;

use MediaWiki\Linker\Linker;
use Mediawiki\OutputTransform\ContentTextTransformStage;
use ParserOptions;
use ParserOutput;

/**
 * Expand relative links to absolute URLs
 * @internal
 */
class ExpandToAbsoluteUrls extends ContentTextTransformStage {

	public function shouldRun( ParserOutput $po, ?ParserOptions $popts, array $options = [] ): bool {
		return $options['absoluteURLs'] ?? false;
	}

	protected function transformText( string $text, ParserOutput $po, ?ParserOptions $popts, array &$options ): string {
		return Linker::expandLocalLinks( $text );
	}

}
