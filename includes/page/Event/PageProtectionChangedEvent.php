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

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing changes to page protection (aka restriction levels).
 * They are emitted by WikiPage::doUpdateRestrictions().
 *
 * @see RestrictionStore
 * @see PageRevisionUpdatedEvent
 *
 * @unstable until 1.45
 */
class PageProtectionChangedEvent extends PageEvent {
	public const TYPE = 'PageProtectionChanged';
	private string $reason;

	/**
	 * @var array<string,string>
	 */
	private array $expiryAfter;
	private bool $isCascadingAfter;
	private PageIdentity $page;

	/**
	 * @var array<string,list<string>>
	 */
	private array $restrictionMapAfter;

	/**
	 * @var array<string,list<string>>
	 */
	private array $restrictionMapBefore;

	/**
	 * @param PageIdentity $page
	 * @param array<string,list<string>> $restrictionMapBefore Page protection
	 *  before the change, given as an associative array that maps actions to a
	 *  list of permissions a user must have to perform it.
	 * @param array<string,list<string>> $restrictionMapAfter Page protection
	 *   after the change, given as an associative array that maps actions to a
	 *   list of permissions a user must have to perform it.
	 * @param array<string,string> $expiryAfter An expiry timestamp (or infinite)
	 *   for each action in $restrictionMapAfter.
	 * @param bool $isCascadingAfter
	 * @param UserIdentity $performer
	 * @param string $reason
	 * @param array $tags
	 */
	public function __construct(
		PageIdentity $page,
		array $restrictionMapBefore,
		array $restrictionMapAfter,
		array $expiryAfter,
		bool $isCascadingAfter,
		UserIdentity $performer,
		string $reason,
		array $tags = []
	) {
		parent::__construct(
			PageUpdateCauses::CAUSE_PROTECTION_CHANGE,
			$page->getId(),
			$performer,
			$tags
		);

		$this->declareEventType( self::TYPE );

		Assert::parameter(
			self::haveSameKeys( $restrictionMapAfter, $restrictionMapBefore ),
			'$restrictionMapAfter and $restrictionMapBefore',
			'must contain the same keys'
		);

		Assert::parameter(
			self::haveSameKeys( $restrictionMapAfter, $expiryAfter ),
			'$restrictionMapAfter and $expiryAfter',
			'must contain the same keys'
		);

		$this->expiryAfter = $expiryAfter;
		$this->isCascadingAfter = $isCascadingAfter;
		$this->page = $page;
		$this->reason = $reason;
		$this->restrictionMapAfter = $restrictionMapAfter;
		$this->restrictionMapBefore = $restrictionMapBefore;
	}

	private static function haveSameKeys( array $array1, array $array2 ): bool {
		$keys1 = array_keys( $array1 );
		$keys2 = array_keys( $array2 );

		return ( count( array_diff( $keys1, $keys2 ) )
			+ count( array_diff( $keys2, $keys1 ) ) ) === 0;
	}

	/**
	 * The expiry timestamp for the restrictions on each action
	 * returned by getRestrictionMapAfter().
	 *
	 * @return array<string,string>
	 */
	public function getExpiryAfter(): array {
		return $this->expiryAfter;
	}

	/**
	 * Whether the updated restrictions are applied in cascading mode.
	 * Actions not affected by this change may retain a different cascading mode.
	 */
	public function isCascadingAfter(): bool {
		return $this->isCascadingAfter;
	}

	public function getPage(): PageIdentity {
		return $this->page;
	}

	/**
	 * Returns the reason supplied be the user.
	 */
	public function getReason(): string {
		return $this->reason;
	}

	/**
	 * Returns the restrictions that are updated by this change.
	 *
	 * This map should provide information for all actions that are also
	 * present in the return value of getRestrictionMapAfter().
	 *
	 * Actions not mentioned in this map are unaffected by the change,
	 * they may or may not have been restricted.
	 *
	 * @return array<string,list<string>> An associative array that maps
	 * actions to a list of permissions a user must have to perform it.
	 */
	public function getRestrictionMapBefore(): array {
		return $this->restrictionMapBefore;
	}

	/**
	 * Returns the restrictions that are updated by this change.
	 * Actions not mentioned in this map are unaffected by the change,
	 * they may or may not be restricted.
	 *
	 * @return array<string,list<string>> An associative array that maps
	 * actions to a list of permissions a user must have to perform it.
	 */
	public function getRestrictionMapAfter(): array {
		return $this->restrictionMapAfter;
	}
}
