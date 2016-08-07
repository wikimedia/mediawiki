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

	public function getQueryInfo() {
		$tables = [ 'page' ];
		$conds = [
			'page_namespace' => MWNamespace::getContentNamespaces(),
			'page_is_redirect' => 0
		];
		$joinConds = [];
		$options = [ 'USE INDEX' => [ 'page' => 'page_redirect_namespace_len' ] ];

		// Allow extensions to modify the query
		Hooks::run( 'ShortPagesQuery', [ &$tables, &$conds, &$joinConds, &$options ] );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_len'
			],
			'conds' => $conds,
			'join_conds' => $joinConds,
			'options' => $options
		];
	}

	function getOrderFields() {
		return [ 'page_len' ];
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
			return Html::element( 'span', [ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		$linkRenderer = $this->getLinkRenderer();
		$hlink = $linkRenderer->makeKnownLink(
			$title,
			$this->msg( 'hist' )->text(),
			[],
			[ 'action' => 'history' ]
		);
		$hlinkInParentheses = $this->msg( 'parentheses' )->rawParams( $hlink )->escaped();

		if ( $this->isCached() ) {
			$plink = $linkRenderer->makeLink( $title );
			$exists = $title->exists();
		} else {
			$plink = $linkRenderer->makeKnownLink( $title );
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
