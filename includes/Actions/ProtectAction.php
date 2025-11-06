<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

/**
 * Handle page protection (action=protect)
 *
 * This is a wrapper that will call Article::protect().
 *
 * @ingroup Actions
 */
class ProtectAction extends FormlessAction {

	/** @inheritDoc */
	public function getName() {
		return 'protect';
	}

	/** @inheritDoc */
	public function onView() {
		return null;
	}

	/** @inheritDoc */
	public function show() {
		$this->getArticle()->protect();
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ProtectAction::class, 'ProtectAction' );
