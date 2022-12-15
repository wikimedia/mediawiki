<?php

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;

class PagerTools {
	private $preventClickjacking = false;
	private $tools = [];

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
					[ 'verify', 'noBrackets' ]
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
