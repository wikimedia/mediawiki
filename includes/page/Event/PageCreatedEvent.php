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
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing page creation.
 *
 * Listeners registered for the "PageCreated" event type will receive a
 * partial history of changes to pages' PageIdentity, namely changes
 * where the page's ID used to be 0 before the change (that is, the page didn't
 * previously exist).
 *
 * PageCreatedEvents are emitted by DerivedPageDataUpdater, typically
 * triggered by PageUpdater.
 *
 * User activities that trigger PageCreatedEvents event include:
 * - editing a non-existing page
 * - undeleting pages
 * - importing pages
 * - moving pages, when a redirect is created under the old title
 * - uploading media files
 *
 * @see PageUpdatedEvent
 * @see PageDeletedEvent
 *
 * @since 1.45
 */
class PageCreatedEvent extends PageRecordChangedEvent {
	public const TYPE = 'PageCreated';

	private RevisionRecord $latestRevisionAfter;
	private string $reason;

	/**
	 * @param string $cause See the self::CAUSE_XXX constants.
	 * @param ExistingPageRecord $pageRecordAfter
	 * @param RevisionRecord $latestRevisionAfter
	 * @param UserIdentity $performer The user performing the update.
	 */
	public function __construct(
		string $cause,
		ExistingPageRecord $pageRecordAfter,
		RevisionRecord $latestRevisionAfter,
		UserIdentity $performer,
		string $reason
	) {
		parent::__construct(
			$cause,
			null,
			$pageRecordAfter,
			$performer
		);

		Assert::parameter(
			$pageRecordAfter->exists(),
			'$pageRecordAfter',
			'must represent an existing page'
		);

		$this->declareEventType( self::TYPE );

		$this->latestRevisionAfter = $latestRevisionAfter;
		$this->reason = $reason;
	}

	/**
	 * Returns the reason supplied by the user.
	 * If the page was created by a regular edit, this is the same as the edit summary.
	 *
	 * @return string
	 */
	public function getReason(): string {
		return $this->reason;
	}

	/**
	 * Returns the revision that became the page's latest revision when the
	 * page was created.
	 */
	public function getLatestRevisionAfter(): RevisionRecord {
		return $this->latestRevisionAfter;
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
	 * @inheritDoc
	 *
	 * @return null
	 */
	public function getPageRecordBefore(): ?ExistingPageRecord {
		// Overwritten to guarantee that the return value is always null.
		// XXX: This may not work for a reconsolidation version of this event!
		return null;
	}

}
