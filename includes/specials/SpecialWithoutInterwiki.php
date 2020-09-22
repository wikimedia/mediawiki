<?php
/**
 * Implements Special:Withoutinterwiki
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

use MediaWiki\MediaWikiServices;

/**
 * Special page lists pages without language links
 *
 * @ingroup SpecialPage
 */
class SpecialWithoutInterwiki extends PageQueryPage {
	private $prefix = '';

	public function __construct( $name = 'Withoutinterwiki' ) {
		parent::__construct( $name );
	}

	public function execute( $par ) {
		$this->prefix = Title::capitalize(
			$this->getRequest()->getVal( 'prefix', $par ), NS_MAIN );
		parent::execute( $par );
	}

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

	protected function sortDescending() {
		return false;
	}

	protected function getOrderFields() {
		return [ 'page_namespace', 'page_title' ];
	}

	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$query = [
			'tables' => [ 'page', 'langlinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			'conds' => [
				'll_title IS NULL',
				'page_namespace' => MediaWikiServices::getInstance()->getNamespaceInfo()->
					getContentNamespaces(),
				'page_is_redirect' => 0
			],
			'join_conds' => [ 'langlinks' => [ 'LEFT JOIN', 'll_from = page_id' ] ]
		];
		if ( $this->prefix ) {
			$dbr = wfGetDB( DB_REPLICA );
			$query['conds'][] = 'page_title ' . $dbr->buildLike( $this->prefix, $dbr->anyString() );
		}

		return $query;
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
