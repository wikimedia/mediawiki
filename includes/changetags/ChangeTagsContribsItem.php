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
 * @ingroup Change tagging
 */

/**
 * Item class for a live contribution with its associated change tags.
 * @since 1.26
 */
class ChangeTagsContribsItem extends ChangeTagsRevisionItem {
	/**
	 * Get the HTML link to the revision text.
	 * @return string
	 */
	protected function getRevisionLink() {
		$date = htmlspecialchars( $this->list->getLanguage()->userTimeAndDate(
			$this->revision->getTimestamp(), $this->list->getUser() ) );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}

		return Linker::linkKnown(
			$this->revision->getTitle(),
			$date,
			array(),
			array(
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			)
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * @return string
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->list->msg( 'diff' )->escaped();
		} else {
			return Linker::linkKnown(
					$this->revision->getTitle(),
					$this->list->msg( 'diff' )->escaped(),
					array(),
					array(
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					)
				);
		}
	}

	/**
	 * @return string A HTML <li> element representing this contribution, showing
	 * change tags and everything
	 */
	public function getHTML() {
		$difflink = $this->list->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$articlelink = Linker::linkKnown( $this->revision->getTitle() );
		$comment = Linker::revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}

		$content = "$difflink $revlink $articlelink $comment";
		$attribs = array();
		$tags = $this->getTags();
		if ( $tags ) {
			list( $tagSummary, $classes ) = ChangeTags::formatSummaryRow( $tags, 'edittags' );
			$content .= " $tagSummary";
			$attribs['class'] = implode( ' ', $classes );
		}
		return Xml::tags( 'li', $attribs, $content );
	}
}
