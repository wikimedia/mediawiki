<?php
/**
 * Redirect from Special:ProtectPage/$1 to index.php?title=$1&action=protect.
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
 * @author Zabe
 *
 * @since 1.38
 */
class SpecialProtectPage extends SpecialRedirectWithAction {
	public function __construct() {
		parent::__construct( 'ProtectPage', 'protect', 'protectpage' );
	}

	// Messages, for grep:
	// specialprotectpage-page
	// specialprotectpage-submit
}
