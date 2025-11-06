<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

use InvalidArgumentException;

/**
 * A class to convert page titles on a foreign wiki (ForeignTitle objects) into
 * page titles on the local wiki (Title objects), placing all pages in a fixed
 * local namespace.
 */
class NamespaceImportTitleFactory implements ImportTitleFactory {
	private TitleFactory $titleFactory;

	private int $ns;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param TitleFactory $titleFactory
	 * @param int $ns The namespace to use for all pages
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		TitleFactory $titleFactory,
		int $ns
	) {
		if ( !$namespaceInfo->exists( $ns ) ) {
			throw new InvalidArgumentException( "Namespace $ns doesn't exist on this wiki" );
		}
		$this->titleFactory = $titleFactory;
		$this->ns = $ns;
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
		return $this->titleFactory->makeTitleSafe( $this->ns, $foreignTitle->getText() );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( NamespaceImportTitleFactory::class, 'NamespaceImportTitleFactory' );
