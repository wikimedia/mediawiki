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

use MediaWiki\DomainEvent\DomainEvent;
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
 * The basic page state before and after the change is represented by
 * PageRecord objects. However, not all changes that trigger a PageStateEvents
 * will cause differences in the page record.
 *
 * @unstable until 1.45
 */
abstract class PageStateEvent extends DomainEvent {

	public const TYPE = 'PageState';

	/**
	 * @var string This is a reconciliation event, triggered in order to give
	 *      listeners an opportunity to catch up on missed events or recreate
	 *      corrupted data. Can be triggered by a user action such as a null
	 *      edit, or by a maintenance script.
	 */
	public const FLAG_RECONCILIATION_REQUEST = 'reconciliation_request';

	private string $cause;
	private ?ExistingPageRecord $pageRecordBefore;
	private ?ExistingPageRecord $pageRecordAfter;
	private UserIdentity $performer;

	/** @var array<string,bool> */
	private array $flags;

	/** @var array<string> */
	private array $tags;

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
			$timestamp,
			$flags[self::FLAG_RECONCILIATION_REQUEST] ?? false
		);

		$this->declareEventType( self::TYPE );

		Assert::parameterElementType( 'string', $tags, '$tags' );
		Assert::parameterKeyType( 'integer', $tags, '$tags' );

		Assert::parameterElementType( 'boolean', $flags, '$flags' );
		Assert::parameterKeyType( 'string', $flags, '$flags' );

		Assert::parameter(
			$pageRecordBefore || $pageRecordAfter,
			'$pageRecordBefore and $pageRecordAfter',
			'must not both be null'
		);

		$this->cause = $cause;
		$this->pageRecordBefore = $pageRecordBefore;
		$this->pageRecordAfter = $pageRecordAfter;
		$this->performer = $performer;
		$this->tags = $tags;
		$this->flags = $flags;
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

	/**
	 * Returns the ID of the page affected by the change.
	 * Note that the ID may no longer be valid after the change (e.g. if the
	 * page was deleted).
	 */
	public function getPageId(): int {
		return $this->pageRecordAfter ? $this->pageRecordAfter->getId()
			: $this->pageRecordBefore->getId();
	}

	/**
	 * Returns the user that performed the update.
	 * For an edit, this will be the same as the user returned by getAuthor().
	 * However, it may be a different user for update events caused e.g. by
	 * undeletion or imports.
	 */
	public function getPerformer(): UserIdentity {
		return $this->performer;
	}

	/**
	 * Checks flags describing the page update.
	 * Use with FLAG_XXX constants declared by subclasses.
	 */
	protected function hasFlag( string $name ): bool {
		return $this->flags[$name] ?? false;
	}

	/**
	 * Indicates the cause of the update.
	 * See the self::CAUSE_XXX constants.
	 * @return string
	 */
	public function getCause(): string {
		return $this->cause;
	}

	/**
	 * Checks whether the update had the given cause.
	 *
	 * @see self::CAUSE_XXX constants
	 */
	public function hasCause( string $cause ): bool {
		return $this->cause === $cause;
	}

	/**
	 * Returns any tags applied to the edit.
	 * @see ChangeTags
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * Checks for a tag associated the page update.
	 *
	 * @see ChangeTags
	 */
	public function hasTag( string $name ): bool {
		return in_array( $name, $this->tags );
	}

}
