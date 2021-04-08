<?php
/**
 * Implements Special:Mostinterwikis
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

use MediaWiki\Cache\LinkBatchFactory;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * A special page that listed pages that have highest interwiki count
 *
 * @ingroup SpecialPage
 */
class SpecialMostInterwikis extends QueryPage {

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Mostinterwikis' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDBLoadBalancer( $loadBalancer );
		$this->setLinkBatchFactory( $linkBatchFactory );
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
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
				'page_namespace' => $this->namespaceInfo->getContentNamespaces()
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
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result
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

		$count = $this->msg( 'ninterwikis' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $link, $count );
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
