<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Lists all the redirecting pages on the wiki.
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialListRedirects extends QueryPage {

	private LinkBatchFactory $linkBatchFactory;
	private WikiPageFactory $wikiPageFactory;
	private RedirectLookup $redirectLookup;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		WikiPageFactory $wikiPageFactory,
		RedirectLookup $redirectLookup
	) {
		parent::__construct( 'Listredirects' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->setDatabaseProvider( $dbProvider );
		$this->wikiPageFactory = $wikiPageFactory;
		$this->redirectLookup = $redirectLookup;
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	protected function sortDescending() {
		return false;
	}

	public function getQueryInfo() {
		return [
			'tables' => [ 'page', 'redirect' ],
			'fields' => [ 'namespace' => 'page_namespace',
				'title' => 'page_title',
				'rd_namespace',
				'rd_title',
				'rd_fragment',
				'rd_interwiki',
			],
			'conds' => [ 'page_is_redirect' => 1 ],
			'join_conds' => [ 'redirect' => [
				'LEFT JOIN', 'rd_from=page_id' ],
			]
		];
	}

	protected function getOrderFields() {
		return [ 'page_namespace', 'page_title' ];
	}

	/**
	 * Cache page existence for performance
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
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
			return Title::makeTitle(
				$row->rd_namespace,
				$row->rd_title,
				$row->rd_fragment,
				$row->rd_interwiki
			);
		} else {
			$title = Title::makeTitle( $row->namespace, $row->title );
			if ( !$title->canExist() ) {
				return null;
			}

			return Title::castFromLinkTarget(
				$this->redirectLookup->getRedirectTarget( $title )
			);
		}
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
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
			$arr = $lang->getArrow();
			$rd_link = Html::rawElement( 'bdi', [ 'dir' => $lang->getDir() ], $rd_link );
			$targetLink = $linkRenderer->makeLink( $target, $target->getFullText() );
			$targetLink = Html::rawElement( 'bdi', [ 'dir' => $lang->getDir() ], $targetLink );

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

/** @deprecated class alias since 1.41 */
class_alias( SpecialListRedirects::class, 'SpecialListRedirects' );
