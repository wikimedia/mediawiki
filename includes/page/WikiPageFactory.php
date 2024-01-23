<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\Hook\WikiPageFactoryHook;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use stdClass;
use WikiCategoryPage;
use WikiFilePage;
use Wikimedia\Rdbms\IConnectionProvider;
use WikiPage;

/**
 * Service for creating WikiPage objects.
 *
 * @since 1.36
 */
class WikiPageFactory {
	/** @var TitleFactory */
	private $titleFactory;
	/** @var WikiPageFactoryHook */
	private $wikiPageFactoryHookRunner;
	/** @var IConnectionProvider */
	private $dbProvider;

	/**
	 * @param TitleFactory $titleFactory
	 * @param WikiPageFactoryHook $wikiPageFactoryHookRunner
	 * @param IConnectionProvider $dbProvider
	 */
	public function __construct(
		TitleFactory $titleFactory,
		WikiPageFactoryHook $wikiPageFactoryHookRunner,
		IConnectionProvider $dbProvider
	) {
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactoryHookRunner = $wikiPageFactoryHookRunner;
		$this->dbProvider = $dbProvider;
	}

	/**
	 * Create a WikiPage object from a title.
	 *
	 * @param PageIdentity $pageIdentity
	 * @return WikiPage
	 */
	public function newFromTitle( PageIdentity $pageIdentity ): WikiPage {
		if ( $pageIdentity instanceof WikiPage ) {
			return $pageIdentity;
		}

		if ( !$pageIdentity->canExist() ) {
			// BC with the Title class
			throw new PageAssertionException(
				'The given PageIdentity {pageIdentity} does not represent a proper page',
				[ 'pageIdentity' => $pageIdentity ]
			);
		}

		$ns = $pageIdentity->getNamespace();

		// TODO: remove the need for casting to Title. We'll have to create a new hook to
		//       replace the WikiPageFactory hook.
		$title = Title::newFromPageIdentity( $pageIdentity );

		$page = null;
		if ( !$this->wikiPageFactoryHookRunner->onWikiPageFactory( $title, $page ) ) {
			return $page;
		}

		switch ( $ns ) {
			case NS_FILE:
				$page = new WikiFilePage( $title );
				break;
			case NS_CATEGORY:
				$page = new WikiCategoryPage( $title );
				break;
			default:
				$page = new WikiPage( $title );
		}

		return $page;
	}

	/**
	 * Create a WikiPage object from a link target.
	 *
	 * @param LinkTarget $title
	 * @return WikiPage
	 */
	public function newFromLinkTarget( LinkTarget $title ): WikiPage {
		return $this->newFromTitle( $this->titleFactory->newFromLinkTarget( $title ) );
	}

	/**
	 * Create a WikiPage object from a database row
	 *
	 * @param stdClass $row Database row containing at least fields returned by getQueryInfo().
	 * @param string|int $from Source of $data:
	 *        - "fromdb" or IDBAccessObject::READ_NORMAL: from a replica DB
	 *        - "fromdbmaster" or IDBAccessObject::READ_LATEST: from the primary DB
	 *        - "forupdate" or IDBAccessObject::READ_LOCKING: from the primary DB using SELECT FOR UPDATE
	 *
	 * @return WikiPage
	 */
	public function newFromRow( $row, $from = 'fromdb' ) {
		$page = $this->newFromTitle( $this->titleFactory->newFromRow( $row ) );
		$page->loadFromRow( $row, $from );
		return $page;
	}

	/**
	 * Create a WikiPage object from a page ID
	 *
	 * @param int $id Article ID to load
	 * @param string|int $from One of the following values:
	 *        - "fromdb" or IDBAccessObject::READ_NORMAL to select from a replica DB
	 *        - "fromdbmaster" or IDBAccessObject::READ_LATEST to select from the primary database
	 *
	 * @return WikiPage|null Null when no page exists with that ID
	 */
	public function newFromID( $id, $from = 'fromdb' ) {
		// page ids are never 0 or negative, see T63166
		if ( $id < 1 ) {
			return null;
		}
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, WikiPage::convertSelectType( $from ) );
		$pageQuery = WikiPage::getQueryInfo();
		$row = $db->selectRow(
			$pageQuery['tables'], $pageQuery['fields'], [ 'page_id' => $id ], __METHOD__,
			[], $pageQuery['joins']
		);
		if ( !$row ) {
			return null;
		}
		return $this->newFromRow( $row, $from );
	}

}
