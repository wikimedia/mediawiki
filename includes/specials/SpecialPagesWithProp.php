<?php
/**
 * Implements Special:PagesWithProp
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
 * @since 1.21
 * @file
 * @ingroup SpecialPage
 * @author Brad Jorsch
 */


/**
 * Special:PagesWithProp to search the page_props table
 * @ingroup SpecialPage
 */
class SpecialPagesWithProp extends QueryPage {
	function __construct( $name = 'PagesWithProp' ) {
		parent::__construct( $name );
	}

	function isCacheable() {
		return false;
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$propname = $request->getVal( 'propname', $par );

		$form = new HTMLForm( array(
			'propname' => array(
				'type' => 'text',
				'name' => 'propname',
				'default' => $propname,
				'label-message' => 'pageswithprop-prop',
				'required' => true,
			),
		), $this->getContext() );
		$form->setMethod( 'get' );
		$form->setAction( $this->getTitle()->getFullUrl() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		$form->setWrapperLegend( $this->msg( 'pageswithprop-legend' ) );
		$form->addHeaderText( $this->msg( 'pageswithprop-text' )->parseAsBlock() );
		$form->setSubmitTextMsg( 'pageswithprop-submit' );

		$form->prepareForm();
		$form->displayForm( false );
		if ( $propname !== '' && $propname !== null ) {
			$form->trySubmit();
		}
	}

	public function onSubmit( $data, $form ) {
		$this->mPropName = $data['propname'];
		parent::execute( $data['propname'] );
	}

	/**
	 * Disable RSS/Atom feeds
	 * @return bool
	 */
	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'page_props', 'page' ),
			'fields' => array (
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'pp_page',
				'pp_value' => 'pp_value',
			),
			'conds' => array (
				'page_id = pp_page',
				'pp_propname' => $this->mPropName,
			),
			'options' => array()
		);
	}

	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( $result->namespace, $result->title );
		$ret = Linker::link( $title, htmlspecialchars( $title->getPrefixedText() ) );
		if ( $result->pp_value !== '' ) {
			$value = $this->msg( 'parentheses' )
				->rawParams( Xml::span( $result->pp_value, 'prop-value' ) )
				->escaped();
			$ret = "$ret $value";
		}
		return $ret;
	}
}
