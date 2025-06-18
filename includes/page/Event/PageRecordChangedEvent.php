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
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Base class for domain events representing changes to page state.
 *
 * Page state events include life cycle changes to the page (e.g. create, move,
 * delete) as well as changing a page's latest revision. They do not include
 * changes to the page's revision history (except for changes to the latest
 * revision).
 *
 * A sequence of PageRecordChanged events for the same page represent a
 * complete history of changes to pages' PageRecord. However, some subtypes
 * may represent changes that do not affect the PageRecord. Also, state
 * changes may overlap, so the PageRecord returned by getPageRecordBefore()
 * doesn't always match the return value of getPageRecordAfter() of the previous
 * event for the same page ID. The return values of getPageRecordAfter() however
 * form a complete history.
 *
 * @unstable until 1.45
 */
abstract class PageRecordChangedEvent extends PageEvent {

	public const TYPE = 'PageRecordChanged';
	private ?ExistingPageRecord $pageRecordBefore;
	private ?ExistingPageRecord $pageRecordAfter;

	/**
	 * @param string $cause See the self::CAUSE_XXX constants.
	 * @param ?ExistingPageRecord $pageRecordBefore The page record before the
	 *        change, or null if the page didn't exist before.
	 * @param ?ExistingPageRecord $pageRecordAfter The page record after the
	 *        change, or null if the page no longer exists after.
	 * @param UserIdentity $performer The user performing the update.
	 * @param array<string> $tags Applicable tags, see ChangeTags.
	 * @param array<string,bool> $flags See the self::FLAG_XXX constants.
	 * @param string|ConvertibleTimestamp|false $timestamp
	 */
	public function __construct(
		string $cause,
		?ExistingPageRecord $pageRecordBefore,
		?ExistingPageRecord $pageRecordAfter,
		UserIdentity $performer,
		array $tags = [],
		array $flags = [],
		$timestamp = false
	) {
		parent::__construct(
			$cause,
			$pageRecordAfter ? $pageRecordAfter->getId()
				: $pageRecordBefore->getId(),
			$performer,
			$tags,
			$flags,
			$timestamp
		);

		$this->declareEventType( self::TYPE );

		Assert::parameter(
			$pageRecordBefore || $pageRecordAfter,
			'$pageRecordBefore and $pageRecordAfter',
			'must not both be null'
		);

		$this->pageRecordBefore = $pageRecordBefore;
		$this->pageRecordAfter = $pageRecordAfter;
	}

	/**
	 * Returns a PageRecord representing the state of the page before the change,
	 * or null if the page did not exist before.
	 */
	public function getPageRecordBefore(): ?ExistingPageRecord {
		return $this->pageRecordBefore;
	}

	/**
	 * Returns a PageRecord representing the state of the page after the change,
	 * or null if the page no longer exists after.
	 *
	 * Note that the PageRecord returned by this method may be the same as the
	 * one returned by getPageRecordBefore(). This may be the case for
	 * reconciliation events, but also for events that represent changes that
	 * are not reflected in the PageRecord.
	 */
	public function getPageRecordAfter(): ?ExistingPageRecord {
		return $this->pageRecordAfter;
	}

}

// @deprecated temporary alias, remove before 1.45 release
class_alias( PageRecordChangedEvent::class, 'MediaWiki\Page\Event\PageStateEvent' );
