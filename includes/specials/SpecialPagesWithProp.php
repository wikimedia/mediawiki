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
 * @since 1.21
 */
class SpecialPagesWithProp extends QueryPage {
	private $propName = null;

	function __construct( $name = 'PagesWithProp' ) {
		parent::__construct( $name );
	}

	function isCacheable() {
		return false;
	}

	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special.pagesWithProp' );

		$request = $this->getRequest();
		$propname = $request->getVal( 'propname', $par );

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'page_props',
			'pp_propname',
			'',
			__METHOD__,
			array( 'DISTINCT', 'ORDER BY' => 'pp_propname' )
		);
		$propnames = array();
		foreach ( $res as $row ) {
			$propnames[$row->pp_propname] = $row->pp_propname;
		}

		$form = new HTMLForm( array(
			'propname' => array(
				'type' => 'selectorother',
				'name' => 'propname',
				'options' => $propnames,
				'default' => $propname,
				'label-message' => 'pageswithprop-prop',
				'required' => true,
			),
		), $this->getContext() );
		$form->setMethod( 'get' );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		$form->setWrapperLegendMsg( 'pageswithprop-legend' );
		$form->addHeaderText( $this->msg( 'pageswithprop-text' )->parseAsBlock() );
		$form->setSubmitTextMsg( 'pageswithprop-submit' );

		$form->prepareForm();
		$form->displayForm( false );
		if ( $propname !== '' && $propname !== null ) {
			$form->trySubmit();
		}
	}

	public function onSubmit( $data, $form ) {
		$this->propName = $data['propname'];
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
		return array(
			'tables' => array( 'page_props', 'page' ),
			'fields' => array(
				'page_id' => 'pp_page',
				'page_namespace',
				'page_title',
				'page_len',
				'page_is_redirect',
				'page_latest',
				'pp_value',
			),
			'conds' => array(
				'page_id = pp_page',
				'pp_propname' => $this->propName,
			),
			'options' => array()
		);
	}

	function getOrderFields() {
		return array( 'page_id' );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$title = Title::newFromRow( $result );
		$ret = Linker::link( $title, null, array(), array(), array( 'known' ) );
		if ( $result->pp_value !== '' ) {
			// Do not show very long or binary values on the special page
			$valueLength = strlen( $result->pp_value );
			$isBinary = strpos( $result->pp_value, "\0" ) !== false;
			$isTooLong = $valueLength > 1024;

			if ( $isBinary || $isTooLong ) {
				$message = $this
					->msg( $isBinary ? 'pageswithprop-prophidden-binary' : 'pageswithprop-prophidden-long' )
					->params( $this->getLanguage()->formatSize( $valueLength ) );

				$propValue = Html::element( 'span', array( 'class' => 'prop-value-hidden' ), $message->text() );
			} else {
				$propValue = Html::element( 'span', array( 'class' => 'prop-value' ), $result->pp_value );
			}

			$ret .= $this->msg( 'colon-separator' )->escaped() . $propValue;
		}

		return $ret;
	}

	protected function getGroupName() {
		return 'pages';
	}
}
