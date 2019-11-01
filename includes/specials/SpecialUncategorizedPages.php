<?php
/**
 * Implements Special:Uncategorizedpages
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

use MediaWiki\MediaWikiServices;

/**
 * A special page looking for page without any category.
 *
 * @ingroup SpecialPage
 * @todo FIXME: Make $requestedNamespace selectable, unify all subclasses into one
 */
class SpecialUncategorizedPages extends PageQueryPage {
	/** @var int|false */
	protected $requestedNamespace = false;

	function __construct( $name = 'Uncategorizedpages' ) {
		parent::__construct( $name );
		$this->addHelpLink( 'Help:Categories' );
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return [
			'tables' => [ 'page', 'categorylinks' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
			],
			// default for page_namespace is all content namespaces (if requestedNamespace is false)
			// otherwise, page_namespace is requestedNamespace
			'conds' => [
				'cl_from IS NULL',
				'page_namespace' => $this->requestedNamespace !== false
						? $this->requestedNamespace
						: MediaWikiServices::getInstance()->getNamespaceInfo()->
							getContentNamespaces(),
				'page_is_redirect' => 0
			],
			'join_conds' => [
				'categorylinks' => [ 'LEFT JOIN', 'cl_from = page_id' ]
			]
		];
	}

	function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort
		if ( $this->requestedNamespace === false &&
			count( MediaWikiServices::getInstance()->getNamespaceInfo()->
				getContentNamespaces() ) > 1
		) {
			return [ 'page_namespace', 'page_title' ];
		}

		return [ 'page_title' ];
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
