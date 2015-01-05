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
 * @license GPL 2+
 */

/**
 * A class to convert page titles on a foreign wiki (ForeignTitle objects) into
 * page titles on the local wiki (Title objects), placing all pages as subpages
 * of a given root page.
 */
class SubpageImportTitleFactory implements ImportTitleFactory {
	/** @var Title */
	protected $rootPage;

	/**
	 * @param Title $rootPage The root page under which all pages should be
	 * created
	 */
	public function __construct( Title $rootPage ) {
		if ( !MWNamespace::hasSubpages( $rootPage->getNamespace() ) ) {
			throw new MWException( "The root page you specified, $rootPage, is in a " .
				"namespace where subpages are not allowed" );
		}
		$this->rootPage = $rootPage;
	}

	/**
	 * Determines which local title best corresponds to the given foreign title.
	 * If such a title can't be found or would be locally invalid, null is
	 * returned.
	 *
	 * @param ForeignTitle $foreignTitle The ForeignTitle to convert
	 * @return Title|null
	 */
	public function createTitleFromForeignTitle( ForeignTitle $foreignTitle ) {
		return Title::newFromText( $this->rootPage->getPrefixedDBkey() . '/' .
			$foreignTitle->getFullText() );
	}
}
