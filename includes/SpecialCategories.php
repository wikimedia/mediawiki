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
	private $mOrderType = 'abc';

	public function __construct() {
		parent::__construct();
		if( $this->mRequest->getText( 'order' ) == 'count' ) {
			$this->mOrderType = 'count';
		}
		if( $this->mRequest->getText( 'direction' ) == 'asc' ) {
			$this->mDefaultDirection = false;
		} elseif( $this->mRequest->getText( 'direction' ) == 'desc'
		|| $this->mOrderType == 'count' ) {
			$this->mDefaultDirection = true;
		}
	}

	function getQueryInfo() {
		global $wgRequest;
		return array(
			'tables' => array( 'category' ),
			'fields' => array( 'cat_title','cat_pages' ),
			'conds' => array( 'cat_pages > 0' )
		);
	}
	
	function getIndexField() {
		# We can't use mOrderType here, since this is called from the parent
		# constructor.  Hmm.
		if( $this->mRequest->getText( 'order' ) == 'count' ) {
			return 'cat_pages';
		} else {
			return "cat_title";
		}
	}
	
	/* Override getBody to apply LinksBatch on resultset before actually outputting anything. */
	public function getBody() {
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

	/** Override this to order by count */
	public function getNavigationBar() {
		$nav = parent::getNavigationBar() . ' (';
		if( $this->mOrderType == 'abc' ) {
			$nav .= $this->makeLink(
				wfMsgHTML( 'special-categories-sort-count' ),
				array( 'order' => 'count' )
			);
		} else {
			$nav .= $this->makeLink(
				wfMsgHTML( 'special-categories-sort-abc' ),
				array( 'order' => 'abc' )
			);
		}
		$nav .= ') (';
		# FIXME, these are stupid query names.  "order" and "dir" are already
		# used.
		if( $this->mDefaultDirection ) {
			# Descending
			$nav .= $this->makeLink(
				wfMsgHTML( 'special-categories-sort-asc' ),
				array( 'direction' => 'asc' )
			);
		} else {
			$nav .= $this->makeLink(
				wfMsgHTML( 'special-categories-sort-desc' ),
				array( 'direction' => 'desc' )
			);
		}
		$nav .= ')';
		return $nav;
	}
}


