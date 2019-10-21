<?php
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

/**
 * Stub object for the user language. Assigned to the $wgLang global.
 */
class StubUserLang extends StubObject {

	public function __construct() {
		parent::__construct( 'wgLang' );
	}

	/**
	 * Call Language::findVariantLink after unstubbing $wgLang.
	 *
	 * This method is implemented with a full signature rather than relying on
	 * __call so that the pass-by-reference signature of the proxied method is
	 * honored.
	 *
	 * @param string &$link The name of the link
	 * @param Title &$nt The title object of the link
	 * @param bool $ignoreOtherCond To disable other conditions when
	 *   we need to transclude a template or update a category's link
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		global $wgLang;
		$this->_unstub( 'findVariantLink', 3 );
		$wgLang->findVariantLink( $link, $nt, $ignoreOtherCond );
	}

	/**
	 * @return Language
	 */
	public function _newObject() {
		return RequestContext::getMain()->getLanguage();
	}
}
