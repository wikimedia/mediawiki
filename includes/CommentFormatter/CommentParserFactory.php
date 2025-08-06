<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;

/**
 * @internal
 */
class CommentParserFactory {

	public function __construct(
		private readonly LinkRenderer $linkRenderer,
		private readonly LinkBatchFactory $linkBatchFactory,
		private readonly LinkCache $linkCache,
		private readonly RepoGroup $repoGroup,
		private readonly Language $contLang,
		private readonly TitleParser $titleParser,
		private readonly NamespaceInfo $namespaceInfo,
		private readonly HookContainer $hookContainer
	) {
	}

	public function create(): CommentParser {
		return new CommentParser(
			$this->linkRenderer,
			$this->linkBatchFactory,
			$this->linkCache,
			$this->repoGroup,
			RequestContext::getMain()->getLanguage(),
			$this->contLang,
			$this->titleParser,
			$this->namespaceInfo,
			$this->hookContainer
		);
	}

}
