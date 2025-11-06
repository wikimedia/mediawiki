<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

/**
 * Handle action=render
 *
 * This is a wrapper that will call Article::render().
 *
 * @ingroup Actions
 */
class RenderAction extends FormlessAction {

	/** @inheritDoc */
	public function getName() {
		return 'render';
	}

	/** @inheritDoc */
	public function onView() {
		return null;
	}

	/** @inheritDoc */
	public function show() {
		$this->getArticle()->render();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RenderAction::class, 'RenderAction' );
