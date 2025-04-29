<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
