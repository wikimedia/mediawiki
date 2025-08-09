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

/**
 * Make sure user doesn't accidentally recreate a page deleted after they started editing
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class AccidentalRecreationConstraint implements IEditConstraint {

	public function __construct(
		private readonly bool $deletedSinceLastEdit,
		private readonly bool $allowRecreation,
	) {
	}

	public function checkConstraint(): string {
		if ( $this->deletedSinceLastEdit && !$this->allowRecreation ) {
			return self::CONSTRAINT_FAILED;
		}
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();
		if ( $this->deletedSinceLastEdit && !$this->allowRecreation ) {
			$statusValue->setResult( false, self::AS_ARTICLE_WAS_DELETED );
		}
		return $statusValue;
	}

}
