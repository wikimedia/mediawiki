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
 * An action which shows a form and does something based on the input from the form
 *
 * @ingroup Actions
 */
abstract class FormAction extends Action {

	/**
	 * Get an HTMLForm descriptor array
	 * @return array
	 */
	abstract protected function getFormFields();

	/**
	 * Add pre- or post-text to the form
	 * @return string HTML which will be sent to $form->addPreText()
	 */
	protected function preText() {
		return '';
	}

	/**
	 * @return string
	 */
	protected function postText() {
		return '';
	}

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
	}

	/**
	 * Get the HTMLForm to control behavior
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		// Give hooks a chance to alter the form, adding extra fields or text etc
		Hooks::run( 'ActionModifyFormFields', array( $this->getName(), &$this->fields, $this->page ) );

		$form = new HTMLForm( $this->fields, $this->getContext(), $this->getName() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );

		// Retain query parameters (uselang etc)
		$form->addHiddenField( 'action', $this->getName() ); // Might not be the same as the query string
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(),
			array( 'action' => null, 'title' => null )
		);
		$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );

		$form->addPreText( $this->preText() );
		$form->addPostText( $this->postText() );
		$this->alterForm( $form );

		// Give hooks a chance to alter the form, adding extra fields or text etc
		Hooks::run( 'ActionBeforeFormDisplay', array( $this->getName(), &$form, $this->page ) );

		return $form;
	}

	/**
	 * Process the form on POST submission.  If you return false from getFormFields(),
	 * this will obviously never be reached.  If you don't want to do anything with the
	 * form, just return false here
	 * @param array $data
	 * @return bool|array True for success, false for didn't-try, array of errors on failure
	 */
	abstract public function onSubmit( $data );

	/**
	 * Do something exciting on successful processing of the form.  This might be to show
	 * a confirmation message (watch, rollback, etc) or to redirect somewhere else (edit,
	 * protect, etc).
	 */
	abstract public function onSuccess();

	/**
	 * The basic pattern for actions is to display some sort of HTMLForm UI, maybe with
	 * some stuff underneath (history etc); to do some processing on submission of that
	 * form (delete, protect, etc) and to do something exciting on 'success', be that
	 * display something new or redirect to somewhere.  Some actions have more exotic
	 * behavior, but that's what subclassing is for :D
	 */
	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}
}
