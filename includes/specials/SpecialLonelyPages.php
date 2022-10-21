<?php
/**
 * Implements Special:Lonelypages
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
 */

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinksMigration;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A special page looking for articles with no article linking to them,
 * thus being lonely.
 *
 * @ingroup SpecialPage
 */
class SpecialLonelyPages extends PageQueryPage {

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var LinksMigration */
	private $linksMigration;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param LinksMigration $linksMigration
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Lonelypages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDBLoadBalancer( $loadBalancer );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->setLanguageConverter( $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() ) );
		$this->linksMigration = $linksMigration;
	}

	protected function getPageHeader() {
		return $this->msg( 'lonelypagestext' )->parseAsBlock();
	}

	protected function sortDescending() {
		return false;
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$queryInfo = $this->linksMigration->getQueryInfo(
			'templatelinks',
			'templatelinks',
			'LEFT JOIN'
		);
		[ $ns, $title ] = $this->linksMigration->getTitleFields( 'templatelinks' );
		$tables = array_merge( [ 'page', 'pagelinks' ], $queryInfo['tables'] );
		$conds = [
			'pl_namespace IS NULL',
			'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
			'page_is_redirect' => 0,
			'tl_from IS NULL'
		];
		$joinConds = [
			'pagelinks' => [
				'LEFT JOIN', [
					'pl_namespace = page_namespace',
					'pl_title = page_title'
				]
			],
		];
		$templatelinksJoin = [
			'LEFT JOIN', [
				"$ns = page_namespace",
				"$title = page_title"
			]
		];
		if ( in_array( 'linktarget', $tables ) ) {
			$joinConds['linktarget'] = $templatelinksJoin;
		} else {
			$joinConds['templatelinks'] = $templatelinksJoin;
		}

		// Allow extensions to modify the query
		$this->getHookRunner()->onLonelyPagesQuery( $tables, $conds, $joinConds );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => $conds,
			'join_conds' => array_merge( $joinConds, $queryInfo['joins'] )
		];
	}

	protected function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort in MySQL 5
		if ( count( $this->namespaceInfo->getContentNamespaces() ) > 1 ) {
			return [ 'page_namespace', 'page_title' ];
		} else {
			return [ 'page_title' ];
		}
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
