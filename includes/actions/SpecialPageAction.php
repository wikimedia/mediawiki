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
 * An action that just passes the request to the relevant special page
 *
 * @ingroup Actions
 * @since 1.25
 */
class SpecialPageAction extends FormlessAction {
	/**
	 * @var array A mapping of action names to special page names.
	 */
	public static $actionToSpecialPageMapping = [
		'revisiondelete' => 'Revisiondelete',
		'editchangetags' => 'EditTags',
	];

	public function getName() {
		$request = $this->getRequest();
		$actionName = $request->getVal( 'action', 'view' );
		// TODO: Shouldn't need to copy-paste this code from Action::getActionName!
		if ( $actionName === 'historysubmit' ) {
			if ( $request->getBool( 'revisiondelete' ) ) {
				$actionName = 'revisiondelete';
			} elseif ( $request->getBool( 'editchangetags' ) ) {
				$actionName = 'editchangetags';
			}
		}

		if ( isset( self::$actionToSpecialPageMapping[$actionName] ) ) {
			return $actionName;
		}

		return 'nosuchaction';
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
		$special = $this->getSpecialPage();
		if ( !$special ) {
			throw new ErrorPageError(
				$this->msg( 'nosuchaction' ), $this->msg( 'nosuchactiontext' ) );
		}

		$special->setContext( $this->getContext() );
		$special->getContext()->setTitle( $special->getPageTitle() );
		$special->run( '' );
	}

	public function doesWrites() {
		$special = $this->getSpecialPage();

		return $special ? $special->doesWrites() : false;
	}

	/**
	 * @return SpecialPage|null
	 */
	protected function getSpecialPage() {
		$action = $this->getName();
		if ( $action === 'nosuchaction' ) {
			return null;
		}

		// map actions to (whitelisted) special pages
		return SpecialPageFactory::getPage( self::$actionToSpecialPageMapping[$action] );
	}
}
