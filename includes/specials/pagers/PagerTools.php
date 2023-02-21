<?php

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;

/**
 * Generate a set of tools for a revision.
 * @since 1.40
 */
class PagerTools {
	private $preventClickjacking = false;
	private $tools = [];

	/**
	 * Generate a set of tools for a revision.
	 * Will performs permission checks where necessary.
	 * @param RevisionRecord $revRecord The revision to generate tools for.
	 * @param RevisionRecord|null $previousRevRecord The previous revision (if any). Optional.
	 *   Used to produce undo links.
	 * @param bool $showRollbackLink Whether to show the rollback link. Only set to true if the
	 *   revision is the latest revision of its page and it has a parent.
	 *   FIXME why don't we do these checks ourselves?
	 * @param HookRunner $hookRunner
	 * @param PageIdentity $title The page to generate tools for. It is the caller's responsibility
	 *   to ensure that the page is already in the link cache.
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct(
		RevisionRecord $revRecord,
		?RevisionRecord $previousRevRecord,
		bool $showRollbackLink,
		HookRunner $hookRunner,
		PageIdentity $title,
		IContextSource $context,
		LinkRenderer $linkRenderer
	) {
		$tools = [];
		$authority = $context->getAuthority();
		# Rollback and undo links
		$userCanEditTitle = $authority->probablyCan( 'edit', $title );
		if ( $showRollbackLink && $userCanEditTitle ) {
			if ( $authority->probablyCan( 'rollback', $title ) ) {
				// Get a rollback link without the brackets
				$rollbackLink = Linker::generateRollback(
					$revRecord,
					$context,
					[ 'noBrackets' ]
				);
				if ( $rollbackLink ) {
					$this->preventClickjacking = true;
					$tools[] = $rollbackLink;
				}
			}
		}
		if ( $userCanEditTitle && $previousRevRecord ) {
			if ( !$revRecord->isDeleted( RevisionRecord::DELETED_TEXT )
				&& !$previousRevRecord->isDeleted( RevisionRecord::DELETED_TEXT )
			) {
				# Create undo tooltip for the first (=latest) line only
				$undoTooltip = $showRollbackLink
					? [ 'title' => $context->msg( 'tooltip-undo' )->text() ]
					: [];
				$undolink = $linkRenderer->makeKnownLink(
					$title,
					$context->msg( 'editundo' )->text(),
					$undoTooltip,
					[
						'action' => 'edit',
						'undoafter' => $previousRevRecord->getId(),
						'undo' => $revRecord->getId()
					]
				);
				$tools[] = "<span class=\"mw-history-undo\">{$undolink}</span>";
			}
		}
		// Allow extension to add their own links here
		// FIXME previously this was only called on history; restore that and deprecate in favor
		//   of a more generic hook (See T326180)
		$hookRunner->onHistoryTools(
			$revRecord,
			$tools,
			$previousRevRecord,
			$authority->getUser()
		);
		$this->tools = $tools;
	}

	public function shouldPreventClickjacking() {
		return $this->preventClickjacking;
	}

	public function toHTML() {
		$tools = $this->tools;
		$s2 = '';
		if ( $tools ) {
			$s2 .= ' ' . Html::openElement( 'span', [ 'class' => 'mw-changeslist-links mw-pager-tools' ] );
			foreach ( $tools as $tool ) {
				$s2 .= Html::rawElement( 'span', [], $tool );
			}
			$s2 .= Html::closeElement( 'span' );
		}
		return $s2;
	}
}
