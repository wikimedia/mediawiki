<?php
/**
 * Implements Special:Mostinterwikis
 *
 * Copyright © 2012 Umherirrender
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
 * @author Umherirrender
 */

use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * A special page that listed pages that have highest interwiki count
 *
 * @ingroup SpecialPage
 */
class MostinterwikisPage extends QueryPage {
	function __construct( $name = 'Mostinterwikis' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		return [
			'tables' => [
				'langlinks',
				'page'
			], 'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)'
			], 'conds' => [
				'page_namespace' => MWNamespace::getContentNamespaces()
			], 'options' => [
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => [
					'page_namespace',
					'page_title'
				]
			], 'join_conds' => [
				'page' => [
					'LEFT JOIN',
					'page_id = ll_from'
				]
			]
		];
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param object $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
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

		$count = $this->msg( 'ninterwikis' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $link, $count );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
