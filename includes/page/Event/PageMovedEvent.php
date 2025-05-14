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
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing page moves.
 * PageMovedEvent is a special case of an PageRevisionUpdatedEvent.
 * It exists as a separate event to accommodate listeners that are only
 * interested in the title change.
 *
 * @see PageRevisionUpdatedEvent
 *
 * @unstable until 1.45
 */
class PageMovedEvent extends PageStateEvent {
	public const TYPE = 'PageMoved';
	private string $reason;

	/**
	 * @param ExistingPageRecord $pageRecordBefore The page before the move.
	 * @param ExistingPageRecord $pageRecordAfter The page after the move.
	 * @param UserIdentity $performer The user performing the move.
	 * @param string $reason The reason for the move.
	 */
	public function __construct(
		ExistingPageRecord $pageRecordBefore,
		ExistingPageRecord $pageRecordAfter,
		UserIdentity $performer,
		string $reason
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
}
