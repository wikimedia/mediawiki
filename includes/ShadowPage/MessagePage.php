<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\Content;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * Shadow page for nonexistent pages in the NS_MEDIAWIKI namespace
 * @since 1.47
 */
class MessagePage extends BaseShadowPage {
	public function __construct(
		private ParseHelper $parseHelper,
		private Content $content,
		private PageReference $title,
	) {
	}

	public function getPreloadContent(): ?Content {
		return $this->content;
	}

	public function getContentForTransclusion(): ?Content {
		return $this->content;
	}

	public function existsForEdit(): bool {
		return true;
	}

	public function getDiffTitleMessage(): ?MessageSpecifier {
		return new MessageValue( 'defaultmessagetext' );
	}

	public function getView( ParserOptions $parserOptions ): ?ShadowPageView {
		return $this->parseHelper->getParsedContentView(
			$this->content, $this->title, $parserOptions );
	}

}
