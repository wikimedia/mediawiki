<?php
/**
 * Copyright © 2006 Rob Church
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

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	protected function sortDescending() {
		return false;
	}

	protected function getOrderFields() {
		return [ 'title' ];
	}

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

	protected function getPageHeader() {
		return $this->msg( 'unusedtemplatestext' )->parseAsBlock();
	}

	protected function getGroupName() {
		return 'maintenance';
	}

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
