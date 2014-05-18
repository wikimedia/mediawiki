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
 */

/**
 * @defgroup Actions Action done on pages
 */

/**
 * An action which just does something, without showing a form first.
 */
abstract class FormlessAction extends Action {

	/**
	 * Show something on GET request.
	 * @return string|null Will be added to the HTMLForm if present, or just added to the
	 *     output if not.  Return null to not add anything
	 */
	abstract public function onView();

	/**
	 * We don't want an HTMLForm
	 * @return bool
	 */
	protected function getFormFields() {
		return false;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function onSubmit( $data ) {
		return false;
	}

	/**
	 * @return bool
	 */
	public function onSuccess() {
		return false;
	}

	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$this->getOutput()->addHTML( $this->onView() );
	}

	/**
	 * Execute the action silently, not giving any output.  Since these actions don't have
	 * forms, they probably won't have any data, but some (eg rollback) may do
	 * @param array $data Values that would normally be in the GET request
	 * @param bool $captureErrors Whether to catch exceptions and just return false
	 * @throws ErrorPageError|Exception
	 * @return bool Whether execution was successful
	 */
	public function execute( array $data = null, $captureErrors = true ) {
		try {
			// Set a new context so output doesn't leak.
			$this->context = clone $this->getContext();
			if ( is_array( $data ) ) {
				$this->context->setRequest( new FauxRequest( $data, false ) );
			}

			// This will throw exceptions if there's a problem
			$this->checkCanExecute( $this->getUser() );

			$this->onView();
			return true;
		}
		catch ( ErrorPageError $e ) {
			if ( $captureErrors ) {
				return false;
			} else {
				throw $e;
			}
		}
	}
}
