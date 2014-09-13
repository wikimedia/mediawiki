<?php
/**
 * Special page which uses an HTMLForm to handle processing.
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
 * @ingroup SpecialPage
 */

/**
 * Special page which uses an HTMLForm to handle processing.  This is mostly a
 * clone of FormAction.  More special pages should be built this way; maybe this could be
 * a new structure for SpecialPages.
 *
 * @ingroup SpecialPage
 */
abstract class FormSpecialPage extends SpecialPage {
	/**
	 * The sub-page of the special page.
	 * @var string
	 */
	protected $par = null;

	/**
	 * Get an HTMLForm descriptor array
	 * @return Array
	 */
	abstract protected function getFormFields();

	/**
	 * Add pre-text to the form
	 * @return String HTML which will be sent to $form->addPreText()
	 */
	protected function preText() {
		return '';
	}

	/**
	 * Add post-text to the form
	 * @return String HTML which will be sent to $form->addPostText()
	 */
	protected function postText() {
		return '';
	}

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @param $form HTMLForm
	 */
	protected function alterForm( HTMLForm $form ) {
	}

	/**
	 * Get message prefix for HTMLForm
	 *
	 * @since 1.21
	 * @return string
	 */
	protected function getMessagePrefix() {
		return strtolower( $this->getName() );
	}

	/**
	 * Get the HTMLForm to control behavior
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		$form = new HTMLForm( $this->fields, $this->getContext(), $this->getMessagePrefix() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		// If the form is a compact vertical form, then don't output this ugly
		// fieldset surrounding it.
		// XXX Special pages can setDisplayFormat to 'vform' in alterForm(), but that
		// is called after this.
		if ( !$form->isVForm() ) {
			$form->setWrapperLegendMsg( $this->getMessagePrefix() . '-legend' );
		}

		$headerMsg = $this->msg( $this->getMessagePrefix() . '-text' );
		if ( !$headerMsg->isDisabled() ) {
			$form->addHeaderText( $headerMsg->parseAsBlock() );
		}

		// Retain query parameters (uselang etc)
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(), array( 'title' => null ) );
		$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );

		$form->addPreText( $this->preText() );
		$form->addPostText( $this->postText() );
		$this->alterForm( $form );

		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( "Special{$this->getName()}BeforeFormDisplay", array( &$form ) );

		return $form;
	}

	/**
	 * Process the form on POST submission.
	 * @param $data Array
	 * @return Bool|Array true for success, false for didn't-try, array of errors on failure
	 */
	abstract public function onSubmit( array $data );

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 * @since 1.22 Default is to do nothing
	 */
	public function onSuccess() {
	}

	/**
	 * Basic SpecialPage workflow: get a form, send it to the user; get some data back,
	 *
	 * @param string $par Subpage string if one was specified
	 */
	public function execute( $par ) {
		$this->setParameter( $par );
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkExecutePermissions( $this->getUser() );

		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	/**
	 * Maybe do something interesting with the subpage parameter
	 * @param string $par
	 */
	protected function setParameter( $par ) {
		$this->par = $par;
	}

	/**
	 * Called from execute() to check if the given user can perform this action.
	 * Failures here must throw subclasses of ErrorPageError.
	 * @param $user User
	 * @throws UserBlockedError
	 * @return Bool true
	 */
	protected function checkExecutePermissions( User $user ) {
		$this->checkPermissions();

		if ( $this->requiresUnblock() && $user->isBlocked() ) {
			$block = $user->getBlock();
			throw new UserBlockedError( $block );
		}

		if ( $this->requiresWrite() ) {
			$this->checkReadOnly();
		}

		return true;
	}

	/**
	 * Whether this action requires the wiki not to be locked
	 * @return Bool
	 */
	public function requiresWrite() {
		return true;
	}

	/**
	 * Whether this action cannot be executed by a blocked user
	 * @return Bool
	 */
	public function requiresUnblock() {
		return true;
	}
}
