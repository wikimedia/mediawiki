<?php

namespace MediaWiki\Specials\Pager;

use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\RecentChanges\ChangeTools\ChangeTools;
use MediaWiki\Revision\RevisionRecord;

/**
 * Generate a set of tools for a revision.
 *
 * @deprecated since 1.47, use ChangeToolsFactory instead
 * @since 1.40
 */
class PagerTools {
	private ChangeTools $changeTools;

	/**
	 * Generate a set of tools for a revision.
	 * Will perform permission checks where necessary.
	 * @param RevisionRecord $revRecord The revision to generate tools for.
	 * @param RevisionRecord|null $previousRevRecord The previous revision (if any). Optional.
	 *   Used to produce undo links.
	 * @param bool $showRollbackLink Whether to show the rollback link. Only set to true if the
	 *   revision is the latest revision of its page and it has a parent.
	 *   FIXME why don't we do these checks ourselves?
	 * @param HookRunner $hookRunner Unused since 1.47
	 * @param PageIdentity $title Unused since 1.47
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer Unused since 1.47
	 */
	public function __construct(
		RevisionRecord $revRecord,
		?RevisionRecord $previousRevRecord,
		bool $showRollbackLink,
		HookRunner $hookRunner,
		PageIdentity $title,
		IContextSource $context,
		LinkRenderer $linkRenderer,
	) {
		wfDeprecated( __CLASS__, '1.47' );
		$this->changeTools = MediaWikiServices::getInstance()->getChangeToolsFactory()->buildChangeTools(
			$revRecord,
			$previousRevRecord,
			$showRollbackLink,
			$context
		);
	}

	public function shouldPreventClickjacking(): bool {
		return $this->changeTools->shouldPreventClickjacking();
	}

	public function toHTML(): string {
		return $this->changeTools->toHtml();
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( PagerTools::class, 'PagerTools' );

/** @deprecated class alias since 1.46 */
class_alias( PagerTools::class, 'MediaWiki\\Pager\\PagerTools' );
