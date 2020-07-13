<?php
/**
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
 * Page edition handler (action=edit)
 *
 * This is a wrapper that will call the EditPage class or a custom editor from an extension.
 *
 * @stable for subclasssing
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
		$out->enableClientCache( false );

		if ( $this->getContext()->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out->addModuleStyles( [
				'mediawiki.ui.input',
				'mediawiki.ui.checkbox',
			] );
		}

		$article = $this->getArticle();
		if ( $this->getHookRunner()->onCustomEditor( $article, $this->getUser() ) ) {
			$editor = new EditPage( $article );
			$editor->setContextTitle( $this->getTitle() );
			$editor->edit();
		}
	}

	public function doesWrites() {
		return true;
	}
}
