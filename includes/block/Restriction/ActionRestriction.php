<?php
/**
 * A block restriction object of type 'Action'.
 *
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

namespace MediaWiki\Block\Restriction;

use MediaWiki\Title\Title;

/**
 * Restriction for partial blocks of actions.
 *
 * @since 1.37
 */
class ActionRestriction extends AbstractRestriction {

	/**
	 * @inheritDoc
	 */
	public const TYPE = 'action';

	/**
	 * @inheritDoc
	 */
	public const TYPE_ID = 3;

	/**
	 * @inheritDoc
	 */
	public function matches( Title $title ) {
		// Action blocks don't apply to particular titles. For example,
		// if a block only blocked uploading, the target would still be
		// allowed to edit any page.
		return false;
	}

}
