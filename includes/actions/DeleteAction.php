<?php
/**
 * Handle page deletion
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
 * Handle page deletion
 *
 * This is a wrapper that will call Article::delete().
 *
 * @ingroup Actions
 */
class DeleteAction extends FormlessAction {

	public function getName() {
		return 'delete';
	}

	public function onView() {
		return null;
	}

	public function show() {
		$this->useTransactionalTimeLimit();

		$out = $this->getOutput();
		if ( $this->getContext()->getConfig()->get( 'UseMediaWikiUIEverywhere' ) ) {
			$out->addModuleStyles( array(
				'mediawiki.ui.input',
				'mediawiki.ui.checkbox',
			) );
		}
		$this->addHelpLink( 'Help:Sysop deleting and undeleting' );
		$this->page->delete();
	}
}
