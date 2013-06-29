<?php
/**
 * Delayed loading of deprecated global objects.
 *
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
 * Class to allow throwing wfDeprecated warnings
 * when people use globals that we do not want them to.
 * (For example like $wgArticle)
 */

class DeprecatedGlobal extends StubObject {
	// The m's are to stay consistent with parent class.
	protected $mRealValue, $mVersion;

	function __construct( $name, $realValue, $version = false ) {
		parent::__construct( $name );
		$this->mRealValue = $realValue;
		$this->mVersion = $version;
	}

	function _newObject() {
		/* Put the caller offset for wfDeprecated as 6, as
		 * that gives the function that uses this object, since:
		 * 1 = this function ( _newObject )
		 * 2 = StubObject::_unstub
		 * 3 = StubObject::_call
		 * 4 = StubObject::__call
		 * 5 = DeprecatedGlobal::<method of global called>
		 * 6 = Actual function using the global.
		 * Of course its theoretically possible to have other call
		 * sequences for this method, but that seems to be
		 * rather unlikely.
		 */
		wfDeprecated( '$' . $this->mGlobal, $this->mVersion, false, 6 );
		return $this->mRealValue;
	}
}
