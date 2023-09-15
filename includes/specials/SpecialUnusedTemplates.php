<?php
/**
 * Implements Special:Unusedtemplates
 *
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
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */

namespace MediaWiki\Specials;

use MediaWiki\Linker\LinksMigration;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Skin;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A special page that lists unused templates
 *
 * @ingroup SpecialPage
 */
class SpecialUnusedTemplates extends QueryPage {

	private LinksMigration $linksMigration;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param LinksMigration $linksMigration
	 */
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
		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ] ),
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'page_namespace' => NS_TEMPLATE,
				'tl_from' => null,
				'page_is_redirect' => 0
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
		$pageLink = $linkRenderer->makeKnownLink(
			$title,
			null,
			[],
			[ 'redirect' => 'no' ]
		);
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
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnusedTemplates::class, 'SpecialUnusedTemplates' );
