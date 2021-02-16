<?php

namespace MediaWiki\Page;

use DBAccessObjectUtils;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\Hook\WikiPageFactoryHook;
use stdClass;
use Title;
use TitleFactory;
use WikiCategoryPage;
use WikiFilePage;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * @since 1.36
 */
class WikiPageFactory {

	/** @var TitleFactory */
	private $titleFactory;

	/** @var WikiPageFactoryHook */
	private $wikiPageFactoryHookRunner;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @param TitleFactory $titleFactory
	 * @param WikiPageFactoryHook $wikiPageFactoryHookRunner
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		TitleFactory $titleFactory,
		WikiPageFactoryHook $wikiPageFactoryHookRunner,
		ILoadBalancer $loadBalancer
	) {
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactoryHookRunner = $wikiPageFactoryHookRunner;
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Create a WikiPage object from a title.
	 *
	 * @param PageIdentity $pageIdentity
	 *
	 * @return WikiPage
	 */
	public function newFromTitle( PageIdentity $pageIdentity ) {
		if ( $pageIdentity instanceof WikiPage ) {
			return $pageIdentity;
		}

		if ( !$pageIdentity->canExist() ) {
			throw new InvalidArgumentException(
				"The given PageIdentity does not represent a proper page"
			);
		}

		$ns = $pageIdentity->getNamespace();

		// TODO: remove the need for casting to Title. We'll have to create a new hook to
		//       replace the WikiPageFactory hook.
		$title = Title::castFromPageIdentity( $pageIdentity );

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
	 *
	 * @return WikiPage
	 */
	public function newFromLinkTarget( LinkTarget $title ) {
		return $this->newFromTitle( $this->titleFactory->newFromLinkTarget( $title ) );
	}

	/**
	 * Create a WikiPage object from a database row
	 *
	 * @param stdClass $row Database row containing at least fields returned by getQueryInfo().
	 * @param string|int $from Source of $data:
	 *        - "fromdb" or WikiPage::READ_NORMAL: from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST: from the master DB
	 *        - "forupdate" or WikiPage::READ_LOCKING: from the master DB using SELECT FOR UPDATE
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
	 *        - "fromdb" or WikiPage::READ_NORMAL to select from a replica DB
	 *        - "fromdbmaster" or WikiPage::READ_LATEST to select from the master database
	 *
	 * @return WikiPage|null Null when no page exists with that ID
	 */
	public function newFromID( $id, $from = 'fromdb' ) {
		// page ids are never 0 or negative, see T63166
		if ( $id < 1 ) {
			return null;
		}

		$from = WikiPage::convertSelectType( $from );
		[ $index ] = DBAccessObjectUtils::getDBOptions( $from );
		$db = $this->loadBalancer->getMaintenanceConnectionRef( $index );
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
