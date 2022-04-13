<?php
/**
 * Implements Special:Wantedtemplates
 *
 * Copyright © 2008, Danny B.
 * Based on SpecialWantedcategories.php by Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * makeWlhLink() taken from SpecialMostlinkedtemplates by Rob Church <robchur@gmail.com>
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
 * @author Danny B.
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A querypage to list the most wanted templates
 *
 * @ingroup SpecialPage
 */
class SpecialWantedTemplates extends WantedQueryPage {

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Wantedtemplates' );
		$this->setDBLoadBalancer( $loadBalancer );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	public function getQueryInfo() {
		$linksMigration = MediaWikiServices::getInstance()->getLinksMigration();
		$queryInfo = $linksMigration->getQueryInfo( 'templatelinks' );
		list( $ns, $title ) = $linksMigration->getTitleFields( 'templatelinks' );
		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ] ),
			'fields' => [
				'namespace' => $ns,
				'title' => $title,
				'value' => 'COUNT(*)'
			],
			'conds' => [
				'page_title IS NULL',
				$ns => NS_TEMPLATE
			],
			'options' => [ 'GROUP BY' => [ $ns, $title ] ],
			'join_conds' => array_merge(
				[ 'page' => [ 'LEFT JOIN',
					[ "page_namespace = $ns", "page_title = $title" ] ] ],
				$queryInfo['joins']
			)
		];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
