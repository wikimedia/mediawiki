<?php
/**
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
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of redirects to non-existent pages.
 *
 * Editors are encouraged to fix these by editing them to redirect to
 * an existing page instead.
 *
 * @ingroup SpecialPage
 */
class SpecialBrokenRedirects extends QueryPage {

	private IContentHandlerFactory $contentHandlerFactory;
	private RedirectLookup $redirectLookup;

	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		RedirectLookup $redirectLookup
	) {
		parent::__construct( 'BrokenRedirects' );
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
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

	protected function getPageHeader() {
		return $this->msg( 'brokenredirectstext' )->parseAsBlock();
	}

	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();

		return [
			'tables' => [
				'redirect',
				'p1' => 'page',
				'p2' => 'page',
			],
			'fields' => [
				'namespace' => 'p1.page_namespace',
				'title' => 'p1.page_title',
				'rd_namespace',
				'rd_title',
				'rd_fragment',
			],
			'conds' => [
				// Exclude pages that don't exist locally as wiki pages, but aren't "broken" either: special
				// pages and interwiki links.
				$dbr->expr( 'rd_namespace', '>=', 0 ),
				'rd_interwiki' => '',
				'p2.page_namespace' => null,
			],
			'join_conds' => [
				'p1' => [ 'JOIN', [
					'rd_from=p1.page_id',
				] ],
				'p2' => [ 'LEFT JOIN', [
					'rd_namespace=p2.page_namespace',
					'rd_title=p2.page_title'
				] ],
			],
		];
	}

	/**
	 * @return array
	 */
	protected function getOrderFields() {
		return [ 'rd_namespace', 'rd_title', 'rd_from' ];
	}

	/**
	 * @param Skin $skin
	 * @param \stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		if ( isset( $result->rd_title ) ) {
			$toObj = Title::makeTitle(
				$result->rd_namespace,
				$result->rd_title,
				$result->rd_fragment
			);
		} else {
			$toObj = Title::castFromLinkTarget(
				$this->redirectLookup->getRedirectTarget( $fromObj )
			);
		}

		$linkRenderer = $this->getLinkRenderer();

		if ( !is_object( $toObj ) || $toObj->exists() ) {
			return '<del>' . $linkRenderer->makeLink( $fromObj ) . '</del>';
		}

		$from = $linkRenderer->makeKnownLink(
			$fromObj,
			null,
			[],
			[ 'redirect' => 'no' ]
		);
		$links = [];
		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getAuthority()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			$this->contentHandlerFactory->getContentHandler( $fromObj->getContentModel() )
				->supportsDirectEditing()
		) {
			$links[] = $linkRenderer->makeKnownLink(
				$fromObj,
				$this->msg( 'brokenredirects-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
		}
		$to = $linkRenderer->makeBrokenLink( $toObj, $toObj->getFullText() );
		$arr = $this->getLanguage()->getArrow();

		$out = $from . $this->msg( 'word-separator' )->escaped();

		if ( $this->getAuthority()->isAllowed( 'delete' ) ) {
			$links[] = $linkRenderer->makeKnownLink(
				$fromObj,
				$this->msg( 'brokenredirects-delete' )->text(),
				[],
				[
					'action' => 'delete',
					'wpReason' => $this->msg( 'brokenredirects-delete-reason' )
						->inContentLanguage()
						->text()
				]
			);
		}

		if ( $links ) {
			$out .= $this->msg( 'parentheses' )->rawParams( $this->getLanguage()
				->pipeList( $links ) )->escaped();
		}
		$out .= " {$arr} {$to}";

		return $out;
	}

	public function execute( $par ) {
		$this->addHelpLink( 'Help:Redirects' );
		parent::execute( $par );
	}

	/**
	 * Cache page content model for performance
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBrokenRedirects::class, 'SpecialBrokenRedirects' );
