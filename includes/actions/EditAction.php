<?php
/**
 * action=edit handler
 *
 * Copyright © 2012 Timo Tijhof
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
 * @author Timo Tijhof
 */

/**
 * Page edition handler
 *
 * This is a wrapper that will call the Editor class or a custom editor from an extension.
 *
 * @ingroup Actions
 */
class EditAction extends FormlessAction {

	public function getName() {
		return 'edit';
	}

	public function onView() {
		return null;
	}

	public function show() {
		global $wgUseNewEditorBackend;
		$this->useTransactionalTimeLimit();

		$out = $this->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );
		if ( $this->getContext()->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out->addModuleStyles( [
				'mediawiki.ui.input',
				'mediawiki.ui.checkbox',
			] );
		}
		$article = $this->page;
		$user = $this->getUser();

		if ( Hooks::run( 'CustomEditor', [ $article, $user ] ) ) {
			if ( $wgUseNewEditorBackend ) {
				$editor = new StandardEditor( $article, $user, $article->getTitle() );
				$editor->edit();
			} else {
				$editPage = new EditPage( $article );
				$editPage->edit();
			}
		}
	}

	public function doesWrites() {
		return true;
	}
}
