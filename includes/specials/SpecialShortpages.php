<?php
/**
 * Implements Special:Shortpages
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

/**
 * SpecialShortpages extends QueryPage. It is used to return the shortest
 * pages in the database.
 *
 * @ingroup SpecialPage
 */
class ShortPagesPage extends QueryPage {

	function __construct( $name = 'Shortpages' ) {
		parent::__construct( $name );
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'page' ),
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_len'
			),
			'conds' => array(
				'page_namespace' => MWNamespace::getContentNamespaces(),
				'page_is_redirect' => 0
			),
			'options' => array( 'USE INDEX' => 'page_redirect_namespace_len' )
		);
	}

	function getOrderFields() {
		return array( 'page_len' );
	}

	/**
	 * @param $db DatabaseBase
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

	function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$dm = $this->getLanguage()->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element( 'span', array( 'class' => 'mw-invalidtitle' ),
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		$hlink = Linker::linkKnown(
			$title,
			$this->msg( 'hist' )->escaped(),
			array(),
			array( 'action' => 'history' )
		);
		$hlinkInParentheses = $this->msg( 'parentheses' )->rawParams( $hlink )->escaped();

		if ( $this->isCached() ) {
			$plink = Linker::link( $title );
			$exists = $title->exists();
		} else {
			$plink = Linker::linkKnown( $title );
			$exists = true;
		}

		$size = $this->msg( 'nbytes' )->numParams( $result->value )->escaped();

		return $exists
			? "${hlinkInParentheses} {$dm}{$plink} {$dm}[{$size}]"
			: "<del>${hlinkInParentheses} {$dm}{$plink} {$dm}[{$size}]</del>";
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
