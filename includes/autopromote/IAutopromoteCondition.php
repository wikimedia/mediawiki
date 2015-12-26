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
 * @since 1.28
 */

/**
 * Interface for autopromote criteria. Every criteria has these methods.
 */
interface IAutopromoteCondition {
	/**
	 * The name of the criteria.
	 * @return string
	 */
	public function getName();

	/**
	 * Evaluate the criteria, maybe recursive
	 * @param User $user
	 * @return true
	 */
	public function evaluate( User $user );

	/**
	 * Get all paramater of this criteria
	 * @return array
	 */
	public function getParameter();

	/**
	 * Get a message object as description of this criteria
	 * @return Message
	 */
	public function getDescription( IContextSource $context, User $user );
}
