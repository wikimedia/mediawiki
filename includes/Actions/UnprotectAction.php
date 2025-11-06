<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

/**
 * Handle page unprotection (action=unprotect)
 *
 * This is a wrapper that will call Article::unprotect().
 *
 * @ingroup Actions
 */
class UnprotectAction extends ProtectAction {

	/** @inheritDoc */
	public function getName() {
		return 'unprotect';
	}

	public function show() {
		$this->getArticle()->unprotect();
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( UnprotectAction::class, 'UnprotectAction' );
