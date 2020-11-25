<?php
/**
 * Implements Special:Ancientpages
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
use MediaWiki\Languages\LanguageConverterFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Implements Special:Ancientpages
 *
 * @ingroup SpecialPage
 */
class SpecialAncientPages extends QueryPage {

	/** @var NamespaceInfo */
	private $namespaceInfo;

	/** @var ILanguageConverter */
	private $languageConverter;

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LanguageConverterFactory $languageConverterFactory
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Ancientpages' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDBLoadBalancer( $loadBalancer );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$tables = [ 'page', 'revision' ];
		$conds = [
			'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
			'page_is_redirect' => 0
		];
		$joinConds = [
			'revision' => [
				'JOIN', [
					'page_latest = rev_id'
				]
			],
		];

		// Allow extensions to modify the query
		$this->getHookRunner()->onAncientPagesQuery( $tables, $conds, $joinConds );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'rev_timestamp'
			],
			'conds' => $conds,
			'join_conds' => $joinConds
		];
	}

	public function usesTimestamps() {
		return true;
	}

	protected function sortDescending() {
		return false;
	}

	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$d = $this->getLanguage()->userTimeAndDate( $result->value, $this->getUser() );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$linkRenderer = $this->getLinkRenderer();

		$link = $linkRenderer->makeKnownLink(
			$title,
			new HtmlArmor( $this->languageConverter->convertHtml( $title->getPrefixedText() ) )
		);

		return $this->getLanguage()->specialList( $link, htmlspecialchars( $d ) );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
