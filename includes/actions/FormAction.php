<?php
/**
 * Base classes for actions done on pages.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Status\Status;

/**
 * An action which shows a form and does something based on the input from the form
 *
 * @stable to extend
 *
 * @ingroup Actions
 */
abstract class FormAction extends Action {

	/**
	 * Get an HTMLForm descriptor array
	 * @stable to override
	 * @return array
	 */
	protected function getFormFields() {
		// Default to an empty form with just a submit button
		return [];
	}

	/**
	 * Add pre- or post-text to the form
	 * @stable to override
	 * @return string HTML which will be sent to $form->addPreHtml()
	 */
	protected function preText() {
		return '';
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function postText() {
		return '';
	}

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @stable to override
	 * @param HTMLForm $form
	 */
	protected function alterForm( HTMLForm $form ) {
	}

	/**
	 * Whether the form should use OOUI
	 * @stable to override
	 * @return bool
	 */
	protected function usesOOUI() {
		return false;
	}

	/**
	 * Get the HTMLForm to control behavior
	 * @stable to override
	 * @return HTMLForm
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		// Give hooks a chance to alter the form, adding extra fields or text etc
		$this->getHookRunner()->onActionModifyFormFields(
			$this->getName(),
			$this->fields,
			$this->getArticle()
		);

		if ( $this->usesOOUI() ) {
			$form = HTMLForm::factory( 'ooui', $this->fields, $this->getContext(), $this->getName() );
		} else {
			$form = new HTMLForm( $this->fields, $this->getContext(), $this->getName() );
		}
		$form->setSubmitCallback( $this->onSubmit( ... ) );

		$title = $this->getTitle();
		$form->setAction( $title->getLocalURL( [ 'action' => $this->getName() ] ) );
		// Retain query parameters (uselang etc)
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(),
			[ 'action' => null, 'title' => null ]
		);
		if ( $params ) {
			$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );
		}

		$form->addPreHtml( $this->preText() );
		$form->addPostHtml( $this->postText() );
		$this->alterForm( $form );

		// Give hooks a chance to alter the form, adding extra fields or text etc
		$this->getHookRunner()->onActionBeforeFormDisplay(
			$this->getName(),
			$form,
			$this->getArticle()
		);

		return $form;
	}

	/**
	 * Process the form on POST submission.
	 *
	 * If you don't want to do anything with the form, just return false here.
	 *
	 * This method will be passed to the HTMLForm as a submit callback (see
	 * HTMLForm::setSubmitCallback) and must return as documented for HTMLForm::trySubmit.
	 *
	 * @see HTMLForm::setSubmitCallback()
	 * @see HTMLForm::trySubmit()
	 * @param array $data
	 * @return bool|string|array|Status Must return as documented for HTMLForm::trySubmit
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
	 * @stable to override
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

	/**
	 * @stable to override
	 * @return bool
	 */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( FormAction::class, 'FormAction' );
