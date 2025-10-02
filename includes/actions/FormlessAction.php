<?php
/**
 * Base classes for actions done on pages.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

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

/** @deprecated class alias since 1.44 */
class_alias( FormlessAction::class, 'FormlessAction' );
