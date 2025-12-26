<?php

namespace MediaWiki\Page;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\Hook\WikiPageFactoryHook;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use stdClass;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Service for creating WikiPage objects.
 *
 * @since 1.36
 */
class WikiPageFactory {
	private TitleFactory $titleFactory;
	private WikiPageFactoryHook $wikiPageFactoryHookRunner;
	private IConnectionProvider $dbProvider;

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
	 * @param PageReference $pageReference
	 * @return WikiPage
	 */
	public function newFromTitle( PageReference $pageReference ): WikiPage {
		if ( $pageReference instanceof WikiPage ) {
			return $pageReference;
		}

		if ( $pageReference instanceof PageIdentity && !$pageReference->canExist() ) {
			// BC with the Title class
			throw new PageAssertionException(
				'The given PageIdentity {pageIdentity} does not represent a proper page',
				[ 'pageIdentity' => $pageReference ]
			);
		}

		$ns = $pageReference->getNamespace();

		// TODO: remove the need for casting to Title. We'll have to create a new hook to
		//       replace the WikiPageFactory hook.
		$title = Title::newFromPageReference( $pageReference );

		$page = null;
		if ( !$this->wikiPageFactoryHookRunner->onWikiPageFactory( $title, $page ) ) {
			return $page;
		}

		return match ( $ns ) {
			NS_FILE => new WikiFilePage( $title ),
			NS_CATEGORY => new WikiCategoryPage( $title ),
			default => new WikiPage( $title )
		};
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
	 *   - "fromdb" or IDBAccessObject::READ_NORMAL: from a replica DB
	 *   - "fromdbmaster" or IDBAccessObject::READ_LATEST: from the primary DB
	 *   - "forupdate" or IDBAccessObject::READ_LOCKING: from the primary DB using SELECT FOR UPDATE
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
		$row = $db->newSelectQueryBuilder()
			->queryInfo( $pageQuery )
			->where( [ 'page_id' => $id ] )
			->caller( __METHOD__ )
			->fetchRow();
		if ( !$row ) {
			return null;
		}
		return $this->newFromRow( $row, $from );
	}

}
