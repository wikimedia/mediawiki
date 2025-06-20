<?php

// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\StubObject;

use MediaWiki\Context\RequestContext;
use MediaWiki\Language\Language;

/**
 * Stub object for the user language. Assigned to the $wgLang global.
 */
class StubUserLang extends StubObject {

	public function __construct() {
		parent::__construct( 'wgLang' );
	}

	/**
	 * @return Language
	 */
	public function _newObject() {
		return RequestContext::getMain()->getLanguage();
	}
}
