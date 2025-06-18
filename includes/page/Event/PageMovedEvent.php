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

namespace MediaWiki\Page\Event;

use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageRecord;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing page moves.
 *
 * A sequence of PageMoved events for the same page represents a
 * continuous chain of changes to pages' title: for two consecutive
 * PageMovedEvents for the same page ID, the title getPageIdentityAfter()
 * on the first event will match the title from getPageIdentityBefore() on the
 * second event.
 * Other aspects of the PageIdentity returned by these methods, such as the
 * page ID, may change independently
 *
 * PageMoved events are emitted by the MovePage command class.
 *
 * Note that page moves often emit additional events, such as
 * PageLatestRevisionChanged (for the dummy revision), PageCreated (if a redirect
 * is created), and PageDeleted (if a page is moved over an existing page).
 * The PageMoved event itself will be emitted last, when all changes needed to
 * complete the page move are done.
 *
 * @see PageLatestRevisionChangedEvent
 *
 * @unstable until 1.45
 */
class PageMovedEvent extends PageStateEvent {
	public const TYPE = 'PageMoved';
	private string $reason;
	private ?PageRecord $redirectPage;

	/**
	 * @param ExistingPageRecord $pageRecordBefore The page before the move.
	 * @param ExistingPageRecord $pageRecordAfter The page after the move.
	 * @param UserIdentity $performer The user performing the move.
	 * @param string $reason The reason for the move.
	 * @param ?PageRecord $redirectPage The page redirect, if one was created during the move.
	 */
	public function __construct(
		ExistingPageRecord $pageRecordBefore,
		ExistingPageRecord $pageRecordAfter,
		UserIdentity $performer,
		string $reason,
		?PageRecord $redirectPage = null
	) {
		Assert::parameter(
			$pageRecordBefore->getId() === $pageRecordAfter->getId(),
			'$pageRecordBefore and $pageRecordAfter',
			'must represent the same page'
		);

		parent::__construct(
			PageUpdateCauses::CAUSE_MOVE,
			$pageRecordBefore,
			$pageRecordAfter,
			$performer
		);

		$this->reason = $reason;
		$this->redirectPage = $redirectPage;
		$this->declareEventType( self::TYPE );
	}

	/**
	 * @inheritDoc
	 */
	public function getPageRecordBefore(): ExistingPageRecord {
		// Overwritten to guarantee that the return value is not null.
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable
		return parent::getPageRecordBefore();
	}

	/**
	 * @inheritDoc
	 */
	public function getPageRecordAfter(): ExistingPageRecord {
		// Overwritten to guarantee that the return value is not null.
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable
		return parent::getPageRecordAfter();
	}

	/**
	 * Returns the reason for the move action.
	 *
	 * @return string
	 */
	public function getReason(): string {
		return $this->reason;
	}

	/*
	 * Returns a PageRecord representing the page redirect at
	 * the old title, if one was created during the move.
	 *
	 * @return PageRecord|null
	 */
	public function getRedirectPage(): ?PageRecord {
		return $this->redirectPage;
	}

	/**
	 * Whether a redirect page at the old title was created during the move.
	 *
	 * @return bool
	 */
	public function wasRedirectCreated(): bool {
		return $this->redirectPage !== null;
	}
}
