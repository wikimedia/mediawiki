<?php
/**
 * Implements Special:Unwatchedpages
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

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * A special page that displays a list of pages that are not on anyones watchlist.
 *
 * @ingroup SpecialPage
 */
class UnwatchedpagesPage extends QueryPage {

	function __construct( $name = 'Unwatchedpages' ) {
		parent::__construct( $name, 'unwatchedpages' );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	/**
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	public function getQueryInfo() {
		$dbr = wfGetDB( DB_REPLICA );
		return [
			'tables' => [ 'page', 'watchlist' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_namespace'
			],
			'conds' => [
				'wl_title IS NULL',
				'page_is_redirect' => 0,
				'page_namespace != ' . $dbr->addQuotes( NS_MEDIAWIKI ),
			],
			'join_conds' => [ 'watchlist' => [
				'LEFT JOIN', [ 'wl_title = page_title',
					'wl_namespace = page_namespace' ] ] ]
		];
	}

	function sortDescending() {
		return false;
	}

	function getOrderFields() {
		return [ 'page_namespace', 'page_title' ];
	}

	/**
	 * Add the JS
	 * @param string|null $par
	 */
	public function execute( $par ) {
		parent::execute( $par );
		$this->getOutput()->addModules( 'mediawiki.special.unwatchedPages' );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$nt ) {
			return Html::element( 'span', [ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription( $this->getContext(), $result->namespace, $result->title ) );
		}

		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$linkRenderer = $this->getLinkRenderer();

		$plink = $linkRenderer->makeKnownLink( $nt, $text );
		$wlink = $linkRenderer->makeKnownLink(
			$nt,
			$this->msg( 'watch' )->text(),
			[ 'class' => 'mw-watch-link' ],
			[ 'action' => 'watch' ]
		);

		return $this->getLanguage()->specialList( $plink, $wlink );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
