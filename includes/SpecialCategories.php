<?php
/**
 *
 * @addtogroup SpecialPage
 */

function wfSpecialCategories() {
	global $wgOut;

	$cap = new CategoryPager();
	$wgOut->addHTML( 
		wfMsgWikiHtml( 'categoriespagetext' ) .
		$cap->getNavigationBar()
		. '<ul>' . $cap->getBody() . '</ul>' .
		$cap->getNavigationBar()
		);
}

class CategoryPager extends AlphabeticPager {
	function getQueryInfo() {
		return array(
			'tables' => array('categorylinks'),
			'fields' => array('cl_to','count(*) count'),
			'options' => array('GROUP BY' => 'cl_to')
			);
	}
	
	function getIndexField() {
		return "cl_to";
	}
	
	function formatRow($result) {
		global $wgLang;
		
		$title = Title::makeTitle( NS_CATEGORY, $result->cl_to );
		return ( 
			'<li>' .
			$this->getSkin()->makeLinkObj( $title, $title->getText() )
			. ' ' .
			$nlinks = wfMsgExt( 'nmembers', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->count ) )
			.
			"</li>\n" );
	}
}

?>
