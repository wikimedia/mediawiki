<?php
/**
 * An action that just pass the request to Special:RevisionDelete
 *
 * Copyright © 2011 Alexandre Emsenhuber
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
 * @author Alexandre Emsenhuber
 */

/**
 * An action that just pass the request to Special:RevisionDelete
 *
 * @ingroup Actions
 * @deprecated since 1.25 This class has been replaced by SpecialPageAction, but
 * you really shouldn't have been using it outside core in the first place
 */
class RevisiondeleteAction extends FormlessAction {
	public function __construct( Page $page, IContextSource $context = null ) {
		wfDeprecated( 'RevisiondeleteAction class', '1.25' );
		parent::__construct( $page, $context );
	}

	public function getName() {
		return 'revisiondelete';
	}

	public function requiresUnblock() {
		return false;
	}

	public function getDescription() {
		return '';
	}

	public function onView() {
		return '';
	}

	public function show() {
		$special = SpecialPageFactory::getPage( 'Revisiondelete' );
		$special->setContext( $this->getContext() );
		$special->getContext()->setTitle( $special->getPageTitle() );
		$special->run( '' );
	}

	public function doesWrites() {
		return true;
	}
}
