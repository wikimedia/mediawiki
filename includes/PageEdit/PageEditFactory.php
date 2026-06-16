<?php

namespace MediaWiki\PageEdit;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\PageEditingHelper;
use MediaWiki\Language\Language;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @internal
 * @since 1.47
 */
class PageEditFactory {

	public const CONSTRUCTOR_OPTIONS = PageEdit::CONSTRUCTOR_OPTIONS;

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly IContentHandlerFactory $contentHandlerFactory,
		private readonly EditConstraintFactory $constraintFactory,
		private readonly IConnectionProvider $connectionProvider,
		private readonly Language $contentLanguage,
		private readonly ContentTransformer $contentTransformer,
		private readonly LoggerInterface $editConflictLogger,
		private readonly PageEditingHelper $pageEditingHelper,
		private readonly RateLimiter $rateLimiter,
		private readonly RevisionStore $revisionStore,
		private readonly ShadowPageLoader $shadowPageLoader,
		private readonly TitleFormatter $titleFormatter,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly WatchlistManager $watchlistManager,
		private readonly WatchedItemStoreInterface $watchedItemStore,
		private readonly WikiPageFactory $wikiPageFactory,
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Create a new PageEdit instance with the given inputs.
	 */
	public function newPageEdit( PageEditInputs $inputs ): PageEdit {
		return new PageEdit(
			$inputs,
			new ServiceOptions( PageEdit::CONSTRUCTOR_OPTIONS, $this->options ),
			$this->contentHandlerFactory,
			$this->constraintFactory,
			$this->connectionProvider,
			$this->contentLanguage,
			$this->contentTransformer,
			$this->editConflictLogger,
			$this->pageEditingHelper,
			$this->rateLimiter,
			$this->revisionStore,
			$this->shadowPageLoader,
			$this->titleFormatter,
			$this->userOptionsLookup,
			$this->watchlistManager,
			$this->watchedItemStore,
			$this->wikiPageFactory,
		);
	}

}
