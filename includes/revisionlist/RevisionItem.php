<?php
/**
 * Holders of revision list for a single page
 *
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

/**
 * Item class for a live revision table row
 */
class RevisionItem extends RevisionItemBase {
	/** @var Revision */
	protected $revision;

	/** @var RequestContext */
	protected $context;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
		$this->context = $list->getContext();
	}

	public function getIdField() {
		return 'rev_id';
	}

	public function getTimestampField() {
		return 'rev_timestamp';
	}

	public function getAuthorIdField() {
		return 'rev_user';
	}

	public function getAuthorNameField() {
		return 'rev_user_text';
	}

	public function canView() {
		return $this->revision->userCan( Revision::DELETED_RESTRICTED, $this->context->getUser() );
	}

	public function canViewContent() {
		return $this->revision->userCan( Revision::DELETED_TEXT, $this->context->getUser() );
	}

	public function isDeleted() {
		return $this->revision->isDeleted( Revision::DELETED_TEXT );
	}

	/**
	 * Get the HTML link to the revision text.
	 * @todo Essentially a copy of RevDelRevisionItem::getRevisionLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	protected function getRevisionLink() {
		$date = $this->list->getLanguage()->userTimeAndDate(
			$this->revision->getTimestamp(), $this->list->getUser() );

		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return htmlspecialchars( $date );
		}
		$linkRenderer = $this->getLinkRenderer();
		return $linkRenderer->makeKnownLink(
			$this->list->title,
			$date,
			[],
			[
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			]
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * @todo Essentially a copy of RevDelRevisionItem::getDiffLink. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $this->context->msg( 'diff' )->escaped();
		} else {
			$linkRenderer = $this->getLinkRenderer();
			return $linkRenderer->makeKnownLink(
					$this->list->title,
					$this->list->msg( 'diff' )->text(),
					[],
					[
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					]
				);
		}
	}

	/**
	 * @todo Essentially a copy of RevDelRevisionItem::getHTML. That class
	 * should inherit from this one, and implement an appropriate interface instead
	 * of extending RevDelItem
	 * @return string
	 */
	public function getHTML() {
		$difflink = $this->context->msg( 'parentheses' )
			->rawParams( $this->getDiffLink() )->escaped();
		$revlink = $this->getRevisionLink();
		$userlink = Linker::revUserLink( $this->revision );
		$comment = Linker::revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}
		return "<li>$difflink $revlink $userlink $comment</li>";
	}
}
