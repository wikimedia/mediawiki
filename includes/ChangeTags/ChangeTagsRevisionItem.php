<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MediaWikiServices;
use MediaWiki\RevisionList\RevisionItem;

/**
 * Item class for a live revision table row with its associated change tags.
 *
 * @since 1.25
 * @ingroup ChangeTags
 */
class ChangeTagsRevisionItem extends RevisionItem {
	/**
	 * @return string Comma-separated list of tags
	 */
	public function getTags() {
		return $this->row->ts_tags;
	}

	/**
	 * @return string A HTML <li> element representing this revision, showing
	 * change tags and everything
	 */
	public function getHTML() {
		$difflink = $this->list->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->getRevisionRecord() );
		$comment = MediaWikiServices::getInstance()->getCommentFormatter()
			->formatRevision( $this->getRevisionRecord(), $this->list->getAuthority() );
		if ( $this->isDeleted() ) {
			$class = Linker::getRevisionDeletedClass( $this->getRevisionRecord() );
			$revlink = "<span class=\"$class\">$revlink</span>";
		}

		$content = "$difflink $revlink $userlink $comment";
		$attribs = [];
		$tags = $this->getTags();
		if ( $tags ) {
			[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
				$tags,
				'edittags',
				$this->list->getContext()
			);
			$content .= " $tagSummary";
			$attribs['class'] = $classes;
		}
		return Html::rawElement( 'li', $attribs, $content );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ChangeTagsRevisionItem::class, 'ChangeTagsRevisionItem' );
