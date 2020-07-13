<?php
/**
 * Base classes for actions done on pages.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

/**
 * An action which just does something, without showing a form first.
 *
 * @stable to extend
 *
 * @ingroup Actions
 */
abstract class FormlessAction extends Action {

	/**
	 * Show something on GET request.
	 * @return string|null Will be added to the HTMLForm if present, or just added to the
	 *     output if not.  Return null to not add anything
	 */
	abstract public function onView();

	/**
	 * @stable to override
	 */
	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$this->getOutput()->addHTML( $this->onView() );
	}
}
