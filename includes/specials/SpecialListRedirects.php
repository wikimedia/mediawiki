<?php
/**
 * Implements Special:Listredirects
 *
 * Copyright © 2006 Rob Church
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
 * @author Rob Church <robchur@gmail.com>
 */

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special:Listredirects - Lists all the redirects on the wiki.
 * @ingroup SpecialPage
 */
class SpecialListRedirects extends QueryPage {
	function __construct( $name = 'Listredirects' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function sortDescending() {
		return false;
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'p1' => 'page', 'redirect', 'p2' => 'page' ],
			'fields' => [ 'namespace' => 'p1.page_namespace',
				'title' => 'p1.page_title',
				'rd_namespace',
				'rd_title',
				'rd_fragment',
				'rd_interwiki',
				'redirid' => 'p2.page_id' ],
			'conds' => [ 'p1.page_is_redirect' => 1 ],
			'join_conds' => [ 'redirect' => [
				'LEFT JOIN', 'rd_from=p1.page_id' ],
				'p2' => [ 'LEFT JOIN', [
					'p2.page_namespace=rd_namespace',
					'p2.page_title=rd_title' ] ] ]
		];
	}

	function getOrderFields() {
		return [ 'p1.page_namespace', 'p1.page_title' ];
	}

	/**
	 * Cache page existence for performance
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
			$redirTarget = $this->getRedirectTarget( $row );
			if ( $redirTarget ) {
				$batch->addObj( $redirTarget );
			}
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	/**
	 * @param stdClass $row
	 * @return Title|null
	 */
	protected function getRedirectTarget( $row ) {
		if ( isset( $row->rd_title ) ) {
			return Title::makeTitle( $row->rd_namespace,
				$row->rd_title, $row->rd_fragment,
				$row->rd_interwiki
			);
		} else {
			$title = Title::makeTitle( $row->namespace, $row->title );
			$article = WikiPage::factory( $title );

			return $article->getRedirectTarget();
		}
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$linkRenderer = $this->getLinkRenderer();
		# Make a link to the redirect itself
		$rd_title = Title::makeTitle( $result->namespace, $result->title );
		$rd_link = $linkRenderer->makeLink(
			$rd_title,
			null,
			[],
			[ 'redirect' => 'no' ]
		);

		# Find out where the redirect leads
		$target = $this->getRedirectTarget( $result );
		if ( $target ) {
			# Make a link to the destination page
			$lang = $this->getLanguage();
			$arr = $lang->getArrow() . $lang->getDirMark();
			$targetLink = $linkRenderer->makeLink( $target, $target->getFullText() );

			return "$rd_link $arr $targetLink";
		} else {
			return "<del>$rd_link</del>";
		}
	}

	public function execute( $par ) {
		$this->addHelpLink( 'Help:Redirects' );
		parent::execute( $par );
	}

	protected function getGroupName() {
		return 'pages';
	}
}
