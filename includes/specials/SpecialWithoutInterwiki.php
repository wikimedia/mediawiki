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
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\SpecialPage\PageQueryPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * List of pages without any language links
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialWithoutInterwiki extends PageQueryPage {
	/** @var string */
	private $prefix = '';

	private NamespaceInfo $namespaceInfo;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Withoutinterwiki' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->setLanguageConverter( $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() ) );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$prefix = $this->getRequest()->getVal( 'prefix', $par );
		$this->prefix = $prefix !== null ? Title::capitalize( $prefix, NS_MAIN ) : '';
		parent::execute( $par );
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		# Do not show useless input form if special page is cached
		if ( $this->isCached() ) {
			return '';
		}

		$formDescriptor = [
			'prefix' => [
				'label-message' => 'allpagesprefix',
				'name' => 'prefix',
				'id' => 'wiprefix',
				'type' => 'text',
				'size' => 20,
				'default' => $this->prefix
			]
		];

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setWrapperLegend( '' )
			->setSubmitTextMsg( 'withoutinterwiki-submit' )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
		return '';
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	protected function getOrderFields() {
		return [ 'page_namespace', 'page_title' ];
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$query = [
			'tables' => [ 'page', 'langlinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'll_title' => null,
				'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
				'page_is_redirect' => 0
			],
			'join_conds' => [ 'langlinks' => [ 'LEFT JOIN', 'll_from = page_id' ] ]
		];
		if ( $this->prefix ) {
			$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
			$query['conds'][] = $dbr->expr(
				'page_title',
				IExpression::LIKE,
				new LikeValue( $this->prefix, $dbr->anyString() )
			);
		}

		return $query;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialWithoutInterwiki::class, 'SpecialWithoutInterwiki' );
