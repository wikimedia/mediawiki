<?php
/**
 *
 * @addtogroup SpecialPage
 */

function wfSpecialCategories() {
	global $wgOut;

	$cap = new CategoryPager();
	$wgOut->addHTML( 
		wfMsgExt( 'categoriespagetext', array( 'parse' ) ) .
		$cap->getNavigationBar()
		. '<ul>' . $cap->getBody() . '</ul>' .
		$cap->getNavigationBar()
		);
}

/**
 * @addtogroup SpecialPage
 * @addtogroup Pager
 */
class CategoryPager extends AlphabeticPager {
	function getQueryInfo() {
		global $wgRequest;
		return array(
			'tables' => array('category'),
			'fields' => array('cat_title','cat_pages')
		);
	}
	
	function getIndexField() {
		return "cat_title";
	}
	
	/* Override getBody to apply LinksBatch on resultset before actually outputting anything. */
	function getBody() {
		if (!$this->mQueryDone) {
			$this->doQuery();
		}
		$batch = new LinkBatch;
	
		$this->mResult->rewind();
		
		while ( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_CATEGORY, $row->cat_title ) );
		}
		$batch->execute();
		$this->mResult->rewind();
		return parent::getBody();
	}
	
	function formatRow($result) {
		global $wgLang;
		$title = Title::makeTitle( NS_CATEGORY, $result->cat_title );
		$titleText = $this->getSkin()->makeLinkObj( $title, htmlspecialchars( $title->getText() ) );
		$count = wfMsgExt( 'nmembers', array( 'parsemag', 'escape' ),
				$wgLang->formatNum( $result->cat_pages ) );
		return Xml::tags('li', null, "$titleText ($count)" ) . "\n";
	}
}


