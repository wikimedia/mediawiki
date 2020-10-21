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

use MediaWiki\EditPage\IEditObject;
use StatusValue;

/**
 * Interface for all constraints that can prevent edits
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
interface IEditConstraint extends IEditObject {

	/** @var string - Constaint passed, no error */
	public const CONSTRAINT_PASSED = 'constraint-passed';

	/** @var string - Constaint failed, use getLegacyStatus to see the failure */
	public const CONSTRAINT_FAILED = 'constraint-failed';

	/**
	 * @return string whether the constraint passed, either CONSTRAINT_PASSED or CONSTRAINT_FAILED
	 */
	public function checkConstraint() : string;

	/**
	 * Get the legacy status for failure (or success)
	 *
	 * Called "legacy" status because this part of the interface should probably be redone;
	 * Currently Status objects have a value of an IEditObject constant, as well as a fatal
	 * message
	 *
	 * @return StatusValue
	 */
	public function getLegacyStatus() : StatusValue;

}
