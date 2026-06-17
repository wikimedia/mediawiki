<?php

namespace MediaWiki\RecentChanges\ChangeTools;

use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Revision\RevisionRecord;

/**
 * A factory for ChangeTools objects.
 *
 * @since 1.47
 */
class ChangeToolsFactory {

	/**
	 * @internal
	 */
	public function __construct(
		private readonly HookRunner $hookRunner,
		private readonly LinkRenderer $linkRenderer,
	) {
	}

	/**
	 * Generate a set of tools for a revision.
	 * Will perform permission checks where necessary.
	 *
	 * @param RevisionRecord $revision The revision to generate tools for.
	 * @param RevisionRecord|null $previousRevision The previous revision (if any). Optional
	 * Used to produce undo links.
	 * @param bool $showRollbackLink Whether to show the rollback link. Only set to true if the
	 *   revision is the latest revision of its page, and it has a parent.
	 * @param IContextSource $context
	 */
	public function buildChangeTools(
		RevisionRecord $revision,
		?RevisionRecord $previousRevision,
		bool $showRollbackLink,
		IContextSource $context,
	): ChangeTools {
		$tools = [];
		$preventClickjacking = false;
		$title = $revision->getPage();
		$authority = $context->getAuthority();

		// Rollback and undo links
		if ( ( $showRollbackLink || $previousRevision )
			// probablyCan loads page restriction data, call only when needed
			&& $authority->probablyCan( 'edit', $title )
		) {
			if ( $showRollbackLink && $authority->probablyCan( 'rollback', $title ) ) {
				// Get a rollback link without the brackets
				$rollbackLink = Linker::generateRollback(
					$revision,
					$context,
					[ 'noBrackets' ]
				);
				if ( $rollbackLink ) {
					$preventClickjacking = true;
					$tools['mw-rollback'] = $rollbackLink;
				}
			}
			if ( $previousRevision
				&& !$revision->isDeleted( RevisionRecord::DELETED_TEXT )
				&& !$previousRevision->isDeleted( RevisionRecord::DELETED_TEXT )
			) {
				// Create undo tooltip for the first (=latest) line only
				$undoTooltip = $showRollbackLink
					? [ 'title' => $context->msg( 'tooltip-undo' )->text() ]
					: [];
				$undoLink = $this->linkRenderer->makeKnownLink(
					$title,
					$context->msg( 'editundo' )->text(),
					$undoTooltip,
					[
						'action' => 'edit',
						'undoafter' => $previousRevision->getId(),
						'undo' => $revision->getId()
					]
				);
				$tools['mw-undo'] = Html::rawElement(
					'span',
					[
						'class' => [ 'mw-history-undo', 'mw-change-tools-undo' ]
					],
					$undoLink,
				);
			}
		}

		// Allow extension to add their own links here
		// FIXME previously this was only called on history; restore that and deprecate in favor
		//   of a more generic hook (See T326180)
		$this->hookRunner->onHistoryTools(
			$revision,
			$tools,
			$previousRevision,
			$authority->getUser()
		);
		return new ChangeTools( $tools, $preventClickjacking );
	}

}
