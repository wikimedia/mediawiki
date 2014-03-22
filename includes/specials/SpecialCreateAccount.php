<?php
/**
 * Redirect page: Special:CreateAccount --> Special:UserLogin/signup.
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
 * @ingroup SpecialPage
 */

/**
 * Redirect page: Special:CreateAccount --> Special:UserLogin/signup.
 * @todo FIXME: This (and the rest of the login frontend) needs to die a horrible painful death
 *
 * @ingroup SpecialPage
 */
class SpecialCreateAccount extends SpecialRedirectToSpecial {
	function __construct() {
		parent::__construct( 'CreateAccount', 'Userlogin', 'signup', array( 'returnto', 'returntoquery', 'uselang' ) );
	}

	// No reason to hide this link on Special:Specialpages
	public function isListed() {
		return true;
	}

	protected function getGroupName() {
		return 'login';
	}
}
