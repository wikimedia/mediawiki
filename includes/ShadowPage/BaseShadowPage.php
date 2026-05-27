<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\Content;
use MediaWiki\Parser\ParserOptions;
use Wikimedia\Message\MessageSpecifier;

/**
 * @stable to override
 * @since 1.47
 */
class BaseShadowPage implements ShadowPage {

	public function getPreloadContent(): ?Content {
		return null;
	}

	public function hasPreloadContent(): bool {
		return (bool)$this->getPreloadContent();
	}

	public function getContentForTransclusion(): ?Content {
		return null;
	}

	public function existsForEdit(): bool {
		return false;
	}

	public function getDiffTitleMessage(): ?MessageSpecifier {
		return null;
	}

	public function getView( ParserOptions $parserOptions ): ?ShadowPageView {
		return null;
	}
}
