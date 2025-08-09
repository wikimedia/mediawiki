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

use StatusValue;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Verify site is not in read only mode
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ReadOnlyConstraint implements IEditConstraint {

	private string $result;

	public function __construct(
		private readonly ReadOnlyMode $readOnlyMode,
	) {
	}

	public function checkConstraint(): string {
		$this->result = $this->readOnlyMode->isReadOnly() ?
			self::CONSTRAINT_FAILED :
			self::CONSTRAINT_PASSED;
		return $this->result;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			// Saved that this is read only in case getLegacyStatus is called
			// after the read only ends, because it still caused the failure
			$statusValue->fatal( 'readonlytext' );
			$statusValue->value = self::AS_READ_ONLY_PAGE;
		}

		return $statusValue;
	}

}
