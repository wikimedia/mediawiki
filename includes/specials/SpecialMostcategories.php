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

/**
 * A special page that list pages that have highest category count
 *
 * @ingroup SpecialPage
 */
class MostcategoriesPage extends QueryPage {
	function __construct( $name = 'Mostcategories' ) {
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
			'tables' => [ 'categorylinks', 'page' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)'
			],
			'conds' => [ 'page_namespace' => MWNamespace::getContentNamespaces() ],
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
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		# There's no point doing a batch check if we aren't caching results;
		# the page must exist for it to have been pulled out of the table
		if ( !$this->isCached() || !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
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

		if ( $this->isCached() ) {
			$link = Linker::link( $title );
		} else {
			$link = Linker::linkKnown( $title );
		}

		$count = $this->msg( 'ncategories' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $link, $count );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
