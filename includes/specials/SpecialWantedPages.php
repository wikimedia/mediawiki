<?php
/**
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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\WantedQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most-linked pages that do not exist.
 *
 * @ingroup SpecialPage
 */
class SpecialWantedPages extends WantedQueryPage {

	private LinksMigration $linksMigration;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Wantedpages' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->linksMigration = $linksMigration;
	}

	/** @inheritDoc */
	public function isIncludable() {
		return true;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$inc = $this->including();

		if ( $inc ) {
			$this->limit = (int)$par;
			$this->offset = 0;
		}
		$this->shownavigation = !$inc;
		parent::execute( $par );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
		$count = $this->getConfig()->get( MainConfigNames::WantedPagesThreshold ) - 1;
		[ $blNamespace, $blTitle ] = $this->linksMigration->getTitleFields( 'pagelinks' );
		$queryInfo = $this->linksMigration->getQueryInfo( 'pagelinks', 'pagelinks' );
		$query = [
			'tables' => array_merge( $queryInfo['tables'], [
				'pg1' => 'page',
				'pg2' => 'page'
			] ),
			'fields' => [
				'namespace' => $blNamespace,
				'title' => $blTitle,
				'value' => 'COUNT(*)'
			],
			'conds' => [
				'pg1.page_namespace' => null,
				$dbr->expr( $blNamespace, '!=', [ NS_USER, NS_USER_TALK ] ),
				$dbr->expr( 'pg2.page_namespace', '!=', NS_MEDIAWIKI ),
			],
			'options' => [
				'HAVING' => [
					'COUNT(*) > ' . $dbr->addQuotes( $count ),
					'COUNT(*) > SUM(pg2.page_is_redirect)'
				],
				'GROUP BY' => [ $blNamespace, $blTitle ]
			],
			'join_conds' => array_merge( [
				'pg1' => [
					'LEFT JOIN', [
						'pg1.page_namespace = ' . $blNamespace,
						'pg1.page_title = ' . $blTitle
					]
				],
				'pg2' => [ 'LEFT JOIN', 'pg2.page_id = pl_from' ]
			], $queryInfo['joins'] )
		];
		// Replacement for the WantedPages::getSQL hook
		$this->getHookRunner()->onWantedPages__getQueryInfo( $this, $query );

		return $query;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialWantedPages::class, 'WantedPagesPage' );
