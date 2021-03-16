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

namespace MediaWiki\EditPage\Constraint;

use ChangeTags;
use MediaWiki\Permissions\Authority;
use StatusValue;

/**
 * Verify user can add change tags
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ChangeTagsConstraint implements IEditConstraint {

	/** @var Authority */
	private $performer;

	/** @var array */
	private $tags;

	/** @var StatusValue|string|null */
	private $result;

	/**
	 * @param Authority $performer
	 * @param string[] $tags
	 */
	public function __construct(
		Authority $performer,
		array $tags
	) {
		$this->performer = $performer;
		$this->tags = $tags;
	}

	public function checkConstraint() : string {
		if ( !$this->tags ) {
			$this->result = self::CONSTRAINT_PASSED;
			return self::CONSTRAINT_PASSED;
		}

		// TODO inject a service once canAddTagsAccompanyingChange is moved to a
		// service as part of T245964
		$changeTagStatus = ChangeTags::canAddTagsAccompanyingChange(
			$this->tags,
			$this->performer
		);

		if ( $changeTagStatus->isOK() ) {
			$this->result = self::CONSTRAINT_PASSED;
			return self::CONSTRAINT_PASSED;
		}

		$this->result = $changeTagStatus; // The same status object is returned
		return self::CONSTRAINT_FAILED;
	}

	public function getLegacyStatus() : StatusValue {
		if ( $this->result === self::CONSTRAINT_PASSED ) {
			$statusValue = StatusValue::newGood();
		} else {
			$statusValue = $this->result;
			$statusValue->value = self::AS_CHANGE_TAG_ERROR;
		}
		return $statusValue;
	}

}
