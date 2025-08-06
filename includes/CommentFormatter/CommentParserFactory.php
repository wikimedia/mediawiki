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
	/** @var LinkRenderer */
	private $linkRenderer;
	/** @var LinkBatchFactory */
	private $linkBatchFactory;
	/** @var LinkCache */
	private $linkCache;
	/** @var RepoGroup */
	private $repoGroup;
	/** @var Language */
	private $contLang;
	/** @var TitleParser */
	private $titleParser;
	/** @var NamespaceInfo */
	private $namespaceInfo;
	/** @var HookContainer */
	private $hookContainer;

	/**
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkCache $linkCache
	 * @param RepoGroup $repoGroup
	 * @param Language $contLang
	 * @param TitleParser $titleParser
	 * @param NamespaceInfo $namespaceInfo
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		LinkCache $linkCache,
		RepoGroup $repoGroup,
		Language $contLang,
		TitleParser $titleParser,
		NamespaceInfo $namespaceInfo,
		HookContainer $hookContainer
	) {
		$this->linkRenderer = $linkRenderer;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linkCache = $linkCache;
		$this->repoGroup = $repoGroup;
		$this->contLang = $contLang;
		$this->titleParser = $titleParser;
		$this->namespaceInfo = $namespaceInfo;
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @return CommentParser
	 */
	public function create() {
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
