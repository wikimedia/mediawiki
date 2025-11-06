<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\EditPage\EditPage;

/**
 * Page edition handler (action=edit)
 *
 * This is a wrapper that will call the EditPage class or a custom editor from an extension.
 *
 * @stable to extend
 * @ingroup Actions
 */
class EditAction extends FormlessAction {

	/**
	 * @stable to override
	 * @return string
	 */
	public function getName() {
		return 'edit';
	}

	/**
	 * @stable to override
	 * @return string|null
	 */
	public function onView() {
		return null;
	}

	/**
	 * @stable to override
	 */
	public function show() {
		$this->useTransactionalTimeLimit();

		$out = $this->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );

		// The editor should always see the latest content when starting their edit.
		// Also to ensure cookie blocks can be set (T152462).
		$out->disableClientCache();

		$article = $this->getArticle();
		if ( $this->getHookRunner()->onCustomEditor( $article, $this->getUser() ) ) {
			$editor = new EditPage( $article );
			$editor->setContextTitle( $this->getTitle() );
			$editor->edit();
		}
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( EditAction::class, 'EditAction' );
