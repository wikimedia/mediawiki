<?php
/**
 * Special handling for category pages.
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
 * Special handling for category pages
 */
class WikiCategoryPage extends WikiPage {

	/**
	 * Don't return a 404 for categories in use.
	 * In use defined as: either the actual page exists
	 * or the category currently has members.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		if ( parent::hasViewableContent() ) {
			return true;
		} else {
			$cat = Category::newFromTitle( $this->mTitle );
			// If any of these are not 0, then has members
			if ( $cat->getPageCount()
				|| $cat->getSubcatCount()
				|| $cat->getFileCount()
			) {
				return true;
			}
		}
		return false;
	}
}
