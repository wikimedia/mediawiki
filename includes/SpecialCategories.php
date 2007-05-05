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

/**
 * @addtogroup SpecialPage
 * @addtogroup Pager
 */
class CategoryPager extends AlphabeticPager {
	function getQueryInfo() {
		return array(
			'tables' => array('categorylinks'),
			'fields' => array('cl_to','count(*) AS count'),
			'options' => array('GROUP BY' => 'cl_to')
			);
	}
	
	function getIndexField() {
		return "cl_to";
	}
	
	/* Override getBody to apply LinksBatch on resultset before actually outputting anything. */
	function getBody() {
		if (!$this->mQueryDone) {
			$this->doQuery();
		}
		$batch = new LinkBatch;
	
		$this->mResult->rewind();
		
		while ( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_CATEGORY, $row->cl_to ) );
		}
		$batch->execute();
		$this->mResult->rewind();
		return parent::getBody();
	}
	
	function formatRow($result) {
		global $wgLang;
		$title = Title::makeTitle( NS_CATEGORY, $result->cl_to );
		return Xml::tags('li', null,
			$this->getSkin()->makeLinkObj( $title, $title->getText() ) . ' (' .
			wfMsgExt( 'nmembers', array( 'parsemag', 'escape'),
				$wgLang->formatNum( $result->count ) ) . ')'
			) . "\n";
	}
}

?>
