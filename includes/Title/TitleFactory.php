<?php
/**
 * Factory for creating Title objects without static coupling.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Title;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MessageLocalizer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Creates Title objects.
 *
 * For now, there is nothing interesting in this class. It is meant for preventing static Title
 * methods causing problems in unit tests.
 *
 * @since 1.35
 */
class TitleFactory {

	/**
	 * @see Title::newFromDBkey
	 * @param string $key
	 * @return Title|null
	 */
	public function newFromDBkey( $key ): ?Title {
		return Title::newFromDBkey( $key );
	}

	/**
	 * @see Title::newFromLinkTarget
	 * @param LinkTarget $linkTarget
	 * @param string $forceClone
	 * @return Title
	 */
	public function newFromLinkTarget( LinkTarget $linkTarget, $forceClone = '' ): Title {
		return Title::newFromLinkTarget( $linkTarget, $forceClone );
	}

	/**
	 * @see Title::castFromLinkTarget
	 * @param LinkTarget|null $linkTarget
	 * @return Title|null
	 */
	public function castFromLinkTarget( ?LinkTarget $linkTarget ): ?Title {
		return Title::castFromLinkTarget( $linkTarget );
	}

	/**
	 * @see Title::newFromPageIdentity
	 * @since 1.41
	 * @param PageIdentity $pageIdentity
	 * @return Title
	 */
	public function newFromPageIdentity( PageIdentity $pageIdentity ): Title {
		return Title::newFromPageIdentity( $pageIdentity );
	}

	/**
	 * @see Title::castFromPageIdentity
	 * @since 1.36
	 * @param PageIdentity|null $pageIdentity
	 * @return Title|null
	 */
	public function castFromPageIdentity( ?PageIdentity $pageIdentity ): ?Title {
		return Title::castFromPageIdentity( $pageIdentity );
	}

	/**
	 * @see Title::newFromPageReference
	 * @since 1.41
	 * @param PageReference $pageReference
	 * @return Title
	 */
	public function newFromPageReference( PageReference $pageReference ): Title {
		return Title::newFromPageReference( $pageReference );
	}

	/**
	 * @see Title::castFromPageReference
	 * @since 1.37
	 * @param PageReference|null $pageReference
	 * @return Title|null
	 */
	public function castFromPageReference( ?PageReference $pageReference ): ?Title {
		return Title::castFromPageReference( $pageReference );
	}

	/**
	 * @see Title::newFromText
	 * @param string|int|null $text
	 * @param int $defaultNamespace
	 * @return Title|null
	 * @throws \InvalidArgumentException
	 */
	public function newFromText( $text, $defaultNamespace = NS_MAIN ): ?Title {
		return Title::newFromText( $text, $defaultNamespace );
	}

	/**
	 * @see Title::newFromTextThrow
	 * @param string $text
	 * @param int $defaultNamespace
	 * @return Title
	 * @throws MalformedTitleException
	 */
	public function newFromTextThrow( $text, $defaultNamespace = NS_MAIN ): Title {
		return Title::newFromTextThrow( $text, $defaultNamespace );
	}

	/**
	 * @see Title::newFromURL
	 * @param string $url
	 * @return Title|null
	 */
	public function newFromURL( $url ): ?Title {
		return Title::newFromURL( $url );
	}

	/**
	 * @see Title::newFromID
	 * @param int $id
	 * @param int $flags
	 * @return Title|null
	 */
	public function newFromID( $id, $flags = 0 ): ?Title {
		return Title::newFromID( $id, $flags );
	}

	/**
	 * @see Title::newFromRow
	 * @param \stdClass $row
	 * @return Title
	 */
	public function newFromRow( $row ): Title {
		return Title::newFromRow( $row );
	}

	/**
	 * @see Title::makeTitle
	 * @param int $ns
	 * @param string $title
	 * @param string $fragment
	 * @param string $interwiki
	 * @return Title
	 */
	public function makeTitle( $ns, $title, $fragment = '', $interwiki = '' ): Title {
		return Title::makeTitle( $ns, $title, $fragment, $interwiki );
	}

	/**
	 * @see Title::makeTitleSafe
	 * @param int $ns
	 * @param string $title
	 * @param string $fragment
	 * @param string $interwiki
	 * @return Title|null
	 */
	public function makeTitleSafe( $ns, $title, $fragment = '', $interwiki = '' ): ?Title {
		return Title::makeTitleSafe( $ns, $title, $fragment, $interwiki );
	}

	/**
	 * @see Title::newMainPage
	 * @param MessageLocalizer|null $localizer
	 * @return Title
	 */
	public function newMainPage( ?MessageLocalizer $localizer = null ): Title {
		return Title::newMainPage( $localizer );
	}

	/**
	 * @since 1.42
	 * @param IResultWrapper $result
	 * @return TitleArrayFromResult
	 */
	public function newTitleArrayFromResult( IResultWrapper $result ) {
		return new TitleArrayFromResult( $result );
	}

}

/** @deprecated class alias since 1.41 */
class_alias( TitleFactory::class, 'TitleFactory' );
