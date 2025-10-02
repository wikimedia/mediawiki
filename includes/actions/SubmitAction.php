<?php
/**
 * Wrapper for EditAction; sets the session cookie.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

/**
 * This is the same as EditAction; except that it sets the session cookie.
 *
 * @ingroup Actions
 */
class SubmitAction extends EditAction {

	/** @inheritDoc */
	public function getName() {
		return 'submit';
	}

	/** @inheritDoc */
	public function show() {
		// Send a cookie so anons get talk message notifications
		$this->getRequest()->getSession()->persist();

		parent::show();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( SubmitAction::class, 'SubmitAction' );
