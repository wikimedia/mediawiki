<?php
/**
 * action=edit / action=submit handler
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
 * This is a wrapper that will call the EditPage class, or ExternalEdit
 * if $wgUseExternalEditor is set to true and requested by the user.
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
		$page = $this->page;
		$user = $this->getUser();

		if ( wfRunHooks( 'CustomEditor', array( $page, $user ) ) ) {
			$editor = new EditPage( $page );
			$editor->edit();
		}

	}

}

/**
 * Edit submission handler
 *
 * This is the same as EditAction; except that it sets the session cookie.
 *
 * @ingroup Actions
 */
class SubmitAction extends EditAction {

	public function getName() {
		return 'submit';
	}

	public function show() {
		if ( session_id() == '' ) {
			// Send a cookie so anons get talk message notifications
			wfSetupSession();
		}

		parent::show();
	}

}
