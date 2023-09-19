<?php
/**
 * Implements Special:Mostlinked
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason, 2006 Rob Church
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Rob Church <robchur@gmail.com>
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Skin;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * A special page to show pages ordered by the number of pages linking to them.
 *
 * @ingroup SpecialPage
 */
class SpecialMostLinked extends QueryPage {

	private LinksMigration $linksMigration;

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinksMigration $linksMigration
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Mostlinked' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->linksMigration = $linksMigration;
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$tableFields = $this->linksMigration->getTitleFields( 'pagelinks' );
		$fields = [
			'namespace' => $tableFields[0],
			'title' => $tableFields[1],
		];
		$queryInfo = $this->linksMigration->getQueryInfo( 'pagelinks' );
		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ] ),
			'fields' => array_merge( [ 'value' => 'COUNT(*)', 'page_namespace' ], $fields ),
			'options' => [
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => array_merge( $tableFields, [ 'page_namespace' ] )
			],
			'join_conds' => array_merge( $queryInfo['joins'], [
				'page' => [
					'LEFT JOIN',
					[
						'page_namespace = ' . $fields['namespace'],
						'page_title = ' . $fields['title']
					]
				] ] )
		];
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * Make a link to "what links here" for the specified title
	 *
	 * @param Title $title Title being queried
	 * @param string $caption Text to display on the link
	 * @return string
	 */
	private function makeWlhLink( $title, $caption ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedDBkey() );

		$linkRenderer = $this->getLinkRenderer();
		return $linkRenderer->makeKnownLink( $wlh, $caption );
	}

	/**
	 * Make links to the page corresponding to the item,
	 * and the "what links here" page for it
	 *
	 * @param Skin $skin Skin to be used
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					$result->namespace,
					$result->title )
			);
		}

		$linkRenderer = $this->getLinkRenderer();
		$link = $linkRenderer->makeLink( $title );
		$wlh = $this->makeWlhLink(
			$title,
			$this->msg( 'nlinks' )->numParams( $result->value )->text()
		);

		return $this->getLanguage()->specialList( $link, $wlh );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMostLinked::class, 'SpecialMostLinked' );
