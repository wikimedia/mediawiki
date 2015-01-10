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

/**
 * A special page looking for page without any category.
 *
 * @ingroup SpecialPage
 */
class UncategorizedPagesPage extends PageQueryPage {
	protected $requestedNamespace = null;

	function __construct( $name = 'Uncategorizedpages' ) {
		parent::__construct( $name );
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

	function execute( $par ) {
		$request = $this->getRequest();
		$namespace = ($request->getIntOrNull('namespace') !== null)
			? $request->getIntOrNull('namespace')
			: (int) $par;

		if ( MWNamespace::exists($namespace) ) {
			$this->requestedNamespace = $namespace;
		}
		parent::execute( $par );
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'page', 'categorylinks' ),
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'page_title'
			),
			// default for page_namespace is all content namespaces (if requestedNamespace is false)
			// otherwise, page_namespace is requestedNamespace
			'conds' => array(
				'cl_from IS NULL',
				'page_namespace' => $this->requestedNamespace !== null
						? $this->requestedNamespace
						: MWNamespace::getContentNamespaces(),
				'page_is_redirect' => 0
			),
			'join_conds' => array(
				'categorylinks' => array( 'LEFT JOIN', 'cl_from = page_id' )
			)
		);
	}

	function getOrderFields() {
		// For some crazy reason ordering by a constant
		// causes a filesort
		if ( $this->requestedNamespace === null && count( MWNamespace::getContentNamespaces() ) > 1 ) {
			return array( 'page_namespace', 'page_title' );
		}

		return array( 'page_title' );
	}

	protected function getGroupName() {
		return 'maintenance';
	}

	function getPageHeader() {
		$fields = array(
			'namespace' => array(
				'name' => 'namespace',
				'type' => 'namespaceselect',
				'default' => $this->requestedNamespace === null ? 0 : $this->requestedNamespace,
				'label-message' => 'namespace'
			),
		);
		$form = new HTMLForm($fields, $this->getContext());
		$form->setMethod( 'get' );
		$form->setTitle($this->getPageTitle());
		$form->setWrapperLegendMsg( 'uncategorizedpages' );
		$form->prepareForm();
		$form->setSubmitText($this->msg( 'allpagessubmit' )->text());

		return $form->getHTML('');
	}
}

/**
 * Redirect page: Special:UncategorizedCategories --> Special:UncategorizedPages.
 *
 * @ingroup SpecialPage
 */
class UncategorizedCategoriesPage extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'Uncategorizedcategories', 'Uncategorizedpages', NS_CATEGORY );
	}
}

/**
 * Redirect page: Special:UncategorizedTemplates --> Special:UncategorizedPages.
 *
 * @ingroup SpecialPage
 */
class UncategorizedTemplatesPage extends SpecialRedirectToSpecial {
	public function __construct() {
		parent::__construct( 'Uncategorizedtemplates', 'Uncategorizedpages', NS_TEMPLATE );
	}
}
