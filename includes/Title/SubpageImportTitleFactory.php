<?php
/**
 * @license GPL-2.0-or-later
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
	private TitleFactory $titleFactory;
	private Title $rootPage;

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

/** @deprecated class alias since 1.41 */
class_alias( SubpageImportTitleFactory::class, 'SubpageImportTitleFactory' );
