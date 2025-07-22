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
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of the shortest pages in the database.
 *
 * @ingroup SpecialPage
 */
class SpecialShortPages extends QueryPage {

	private NamespaceInfo $namespaceInfo;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Shortpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$config = $this->getConfig();
		$tables = [ 'page' ];
		$conds = [
			'page_namespace' => array_diff(
				$this->namespaceInfo->getContentNamespaces(),
				$config->get( MainConfigNames::ShortPagesNamespaceExclusions )
			),
			'page_is_redirect' => 0
		];
		$joinConds = [];
		$options = [ 'USE INDEX' => [ 'page' => 'page_redirect_namespace_len' ] ];

		// Allow extensions to modify the query
		$this->getHookRunner()->onShortPagesQuery( $tables, $conds, $joinConds, $options );

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

	public function reallyDoQuery( $limit, $offset = false ) {
		$fname = static::class . '::reallyDoQuery';
		$dbr = $this->getRecacheDB();
		$query = $this->getQueryInfo();
		$conds = isset( $query['conds'] ) ? (array)$query['conds'] : [];
		$namespaces = $conds['page_namespace'];
		unset( $conds['page_namespace'] );

		if ( count( $namespaces ) === 1 || !$dbr->unionSupportsOrderAndLimit() ) {
			return parent::reallyDoQuery( $limit, $offset );
		}

		// Optimization: Fix slow query on MySQL the case of multiple content namespaces,
		// by rewriting this as a UNION of separate single namespace queries (T168010).
		$sqb = $dbr->newSelectQueryBuilder()
			->select( isset( $query['fields'] ) ? (array)$query['fields'] : [] )
			->tables( isset( $query['tables'] ) ? (array)$query['tables'] : [] )
			->where( $conds )
			->options( isset( $query['options'] ) ? (array)$query['options'] : [] )
			->joinConds( isset( $query['join_conds'] ) ? (array)$query['join_conds'] : [] );
		if ( $limit !== false ) {
			if ( $offset !== false ) {
				// We need to increase the limit by the offset rather than
				// using the offset directly, otherwise it'll skip incorrectly
				// in the subqueries.
				$sqb->limit( intval( $limit ) + intval( $offset ) );
			} else {
				$sqb->limit( intval( $limit ) );
			}
		}

		$order = $this->getOrderFields();
		if ( $this->sortDescending() ) {
			foreach ( $order as &$field ) {
				$field .= ' DESC';
			}
		}

		$uqb = $dbr->newUnionQueryBuilder()->all();
		foreach ( $namespaces as $namespace ) {
			$nsSqb = clone $sqb;
			$nsSqb->orderBy( $order );
			$nsSqb->andWhere( [ 'page_namespace' => $namespace ] );
			$uqb->add( $nsSqb );
		}

		if ( $limit !== false ) {
			$uqb->limit( intval( $limit ) );
		}
		if ( $offset !== false ) {
			$uqb->offset( intval( $offset ) );
		}
		$orderBy = 'value';
		if ( $this->sortDescending() ) {
			$orderBy .= ' DESC';
		}
		$uqb->orderBy( $orderBy );
		return $uqb->caller( $fname )->fetchResultSet();
	}

	protected function getOrderFields() {
		return [ 'page_len' ];
	}

	/**
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	protected function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
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
		$contentLanguage = $this->getContentLanguage();
		$bdiAttrs = [
			'dir' => $contentLanguage->getDir(),
			'lang' => $contentLanguage->getHtmlCode(),
		];
		$plink = Html::rawElement( 'bdi', $bdiAttrs, $plink );
		$size = $this->msg( 'nbytes' )->numParams( $result->value )->escaped();
		$result = "{$hlinkInParentheses} {$plink} [{$size}]";

		return $exists ? $result : Html::rawElement( 'del', [], $result );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialShortPages::class, 'SpecialShortPages' );
