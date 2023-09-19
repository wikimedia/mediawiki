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

namespace MediaWiki\Title;

use InvalidArgumentException;

/**
 * A class to convert page titles on a foreign wiki (ForeignTitle objects) into
 * page titles on the local wiki (Title objects), placing all pages as subpages
 * of a given root page.
 */
class SubpageImportTitleFactory implements ImportTitleFactory {
	/** @var TitleFactory */
	private $titleFactory;

	/** @var Title */
	private $rootPage;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleFactory $titleFactory
	 * @param Title $rootPage The root page under which all pages should be created
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory,
		Title $rootPage
	) {
		if ( !$namespaceInfo->hasSubpages( $rootPage->getNamespace() ) ) {
			throw new InvalidArgumentException( "The root page you specified, $rootPage, is in a " .
				"namespace where subpages are not allowed" );
		}
		$this->titleFactory = $titleFactory;
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
		return $this->titleFactory->newFromText(
			$this->rootPage->getPrefixedDBkey() . '/' . $foreignTitle->getFullText()
		);
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SubpageImportTitleFactory::class, 'SubpageImportTitleFactory' );
