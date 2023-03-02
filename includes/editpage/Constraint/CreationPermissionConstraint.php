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

use MediaWiki\Permissions\Authority;
use MediaWiki\Title\Title;
use StatusValue;

/**
 * Verify be able to create the page in question if it is a new page
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class CreationPermissionConstraint implements IEditConstraint {

	/** @var Authority */
	private $performer;

	/** @var Title */
	private $title;

	/** @var string|null */
	private $result;

	/**
	 * @param Authority $performer
	 * @param Title $title
	 */
	public function __construct(
		Authority $performer,
		Title $title
	) {
		$this->performer = $performer;
		$this->title = $title;
	}

	public function checkConstraint(): string {
		// Check isn't simple enough to just repeat when getting the status
		if ( !$this->performer->authorizeWrite( 'create', $this->title ) ) {
			$this->result = self::CONSTRAINT_FAILED;
			return self::CONSTRAINT_FAILED;
		}

		$this->result = self::CONSTRAINT_PASSED;
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->fatal( 'nocreatetext' );
			$statusValue->value = self::AS_NO_CREATE_PERMISSION;
		}

		return $statusValue;
	}

}
