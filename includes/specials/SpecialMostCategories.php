<?php
/**
 * Implements Special:Mostcategories
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
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
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * A special page that list pages that have highest category count
 *
 * @ingroup SpecialPage
 */
class SpecialMostCategories extends QueryPage {
	public function __construct( $name = 'Mostcategories' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'categorylinks', 'page' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)'
			],
			'conds' => [ 'page_namespace' =>
				MediaWikiServices::getInstance()->getNamespaceInfo()->getContentNamespaces() ],
			'options' => [
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => [ 'page_namespace', 'page_title' ]
			],
			'join_conds' => [
				'page' => [
					'LEFT JOIN',
					'page_id = cl_from'
				]
			]
		];
	}

	/**
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
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
					$result->title
				)
			);
		}

		$linkRenderer = $this->getLinkRenderer();
		if ( $this->isCached() ) {
			$link = $linkRenderer->makeLink( $title );
		} else {
			$link = $linkRenderer->makeKnownLink( $title );
		}

		$count = $this->msg( 'ncategories' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $link, $count );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
