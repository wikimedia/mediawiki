<?php
/**
 * Copyright © 2006 Rob Church
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Lists of unused templates
 *
 * @see SpecialMostLinkedTemplates
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialUnusedTemplates extends QueryPage {

	private LinksMigration $linksMigration;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Unusedtemplates' );
		$this->setDatabaseProvider( $dbProvider );
		$this->linksMigration = $linksMigration;
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		return [ 'title' ];
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$queryInfo = $this->linksMigration->getQueryInfo(
			'templatelinks',
			'templatelinks',
			'LEFT JOIN'
		);
		[ $ns, $title ] = $this->linksMigration->getTitleFields( 'templatelinks' );
		$joinConds = [];
		$templatelinksJoin = [
			'LEFT JOIN', [ "$title = page_title",
				"$ns = page_namespace" ] ];
		if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
			$joinConds['linktarget'] = $templatelinksJoin;
		} else {
			$joinConds['templatelinks'] = $templatelinksJoin;
		}
		$joinConds['page_props'] = [ 'LEFT JOIN', [ 'page_id = pp_page', 'pp_propname' => 'expectunusedtemplate' ] ];
		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ], [ 'page_props' ] ),
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'page_namespace' => NS_TEMPLATE,
				'tl_from' => null,
				'page_is_redirect' => 0,
				'pp_page' => null
			],
			'join_conds' => array_merge( $joinConds, $queryInfo['joins'] )
		];
	}

	/** @inheritDoc */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$linkRenderer = $this->getLinkRenderer();
		$title = Title::makeTitle( NS_TEMPLATE, $result->title );
		$pageLink = $linkRenderer->makeKnownLink( $title );
		$wlhLink = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() ),
			$this->msg( 'unusedtemplateswlh' )->text()
		);

		return $this->getLanguage()->specialList( $pageLink, $wlhLink );
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		return $this->msg( 'unusedtemplatestext' )->parseAsBlock();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			TemplateLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnusedTemplates::class, 'SpecialUnusedTemplates' );
