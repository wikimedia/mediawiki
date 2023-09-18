<?php
/**
 * Implements Special:Unusedcategories
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

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use Skin;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup SpecialPage
 */
class SpecialUnusedCategories extends QueryPage {

	/**
	 * @param IConnectionProvider $dbProvider
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Unusedcategories' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	public function isExpensive() {
		return true;
	}

	protected function getPageHeader() {
		return $this->msg( 'unusedcategoriestext' )->parseAsBlock();
	}

	protected function getOrderFields() {
		return [ 'title' ];
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'page', 'categorylinks', 'page_props' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'cl_from' => null,
				'page_namespace' => NS_CATEGORY,
				'page_is_redirect' => 0,
				'pp_page' => null,
			],
			'join_conds' => [
				'categorylinks' => [ 'LEFT JOIN', 'cl_to = page_title' ],
				'page_props' => [ 'LEFT JOIN', [
					'page_id = pp_page',
					'pp_propname' => 'expectunusedcategory'
				] ]
			]
		];
	}

	/**
	 * A should come before Z (T32907)
	 * @return bool
	 */
	protected function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_CATEGORY, $result->title );

		return $this->getLinkRenderer()->makeLink( $title, $title->getText() );
	}

	protected function getGroupName() {
		return 'maintenance';
	}

	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnusedCategories::class, 'SpecialUnusedCategories' );
