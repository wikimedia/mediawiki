<?php
/**
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Special page to direct the user to a random redirect page (minus the second redirect)
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>, Ilmari Karonen
 * @license GNU General Public Licence 2.0 or later
 */
class SpecialRandomredirect extends RandomPage {
	function __construct(){
		parent::__construct( 'Randomredirect' );
		$this->isRedir = true;
	}

}
