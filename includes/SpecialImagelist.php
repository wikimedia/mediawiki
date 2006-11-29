<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
function wfSpecialImagelist() {
	global $wgOut;

	$pager = new ImageListPager;

	$limit = $pager->getForm();
	$body = $pager->getBody();
	$nav = $pager->getNavigationBar();
	$wgOut->addHTML( "
		$limit
		<br/>
		$body
		$nav" );
}

class ImageListPager extends TablePager {
	var $mFieldNames = null;
	var $mMessages = array();
	var $mQueryConds = array();

	function __construct() {
		global $wgRequest, $wgMiserMode;
		if ( $wgRequest->getText( 'sort', 'img_date' ) == 'img_date' ) {
			$this->mDefaultDirection = true;
		} else {
			$this->mDefaultDirection = false;
		}
		$search = $wgRequest->getText( 'ilsearch' );
		if ( $search != '' && !$wgMiserMode ) {
			$nt = Title::newFromUrl( $search );
			if( $nt ) {
				$dbr =& wfGetDB( DB_SLAVE );
				$m = $dbr->strencode( strtolower( $nt->getDBkey() ) );
				$m = str_replace( "%", "\\%", $m );
				$m = str_replace( "_", "\\_", $m );
				$this->mQueryConds = array( "LCASE(img_name) LIKE '%{$m}%'" );
			}
		}

		parent::__construct();
	}

	function getFieldNames() {
		if ( !$this->mFieldNames ) {
			$this->mFieldNames = array(
				'links' => '',
				'img_timestamp' => wfMsg( 'imagelist_date' ),
				'img_name' => wfMsg( 'imagelist_name' ),
				'img_user_text' => wfMsg( 'imagelist_user' ),
				'img_size' => wfMsg( 'imagelist_size' ),
				'img_description' => wfMsg( 'imagelist_description' ),
			);
		}
		return $this->mFieldNames;
	}

	function isFieldSortable( $field ) {
		static $sortable = array( 'img_timestamp', 'img_name', 'img_size' );
		return in_array( $field, $sortable );
	}

	function getQueryInfo() {
		$fields = $this->getFieldNames();
		unset( $fields['links'] );
		$fields = array_keys( $fields );
		$fields[] = 'img_user';
		return array(
			'tables' => 'image',
			'fields' => $fields,
			'conds' => $this->mQueryConds
		);
	}

	function getDefaultSort() {
		return 'img_timestamp';
	}

	function getStartBody() {
		# Do a link batch query for user pages
		if ( $this->mResult->numRows() ) {
			$lb = new LinkBatch;
			$this->mResult->seek( 0 );
			while ( $row = $this->mResult->fetchObject() ) {
				if ( $row->img_user ) {
					$lb->add( NS_USER, str_replace( ' ', '_', $row->img_user_text ) );
				}
			}
			$lb->execute();
		}

		# Cache messages used in each row
		$this->mMessages['imgdesc'] = wfMsgHtml( 'imgdesc' );
		$this->mMessages['imgfile'] = wfMsgHtml( 'imgfile' );
		
		return parent::getStartBody();
	}

	function formatValue( $field, $value ) {
		global $wgLang;
		switch ( $field ) {
			case 'links':
				$name = $this->mCurrentRow->img_name;
				$ilink = "<a href=\"" . htmlspecialchars( Image::imageUrl( $name ) ) .
				  "\">" . $this->mMessages['imgfile'] . "</a>";
				$desc = $this->getSkin()->makeKnownLinkObj( Title::makeTitle( NS_IMAGE, $name ),
					$this->mMessages['imgdesc'] );
				return "$desc | $ilink";
			case 'img_timestamp':
				return $wgLang->timeanddate( $value, true );
			case 'img_name':
				return htmlspecialchars( $value );
			case 'img_user_text':
				if ( $this->mCurrentRow->img_user ) {
					$link = $this->getSkin()->makeLinkObj( Title::makeTitle( NS_USER, $value ), 
						htmlspecialchars( $value ) );
				} else {
					$link = htmlspecialchars( $value );
				}
				return $link;
			case 'img_size':
				return $wgLang->formatNum( $value );
			case 'img_description':
				return $this->getSkin()->commentBlock( $value );
		}
	}

	function getForm() {
		global $wgRequest, $wgMiserMode;
		$url = $this->getTitle()->escapeLocalURL();
		$msgSubmit = wfMsgHtml( 'table_pager_limit_submit' );
		$msgSearch = wfMsgHtml( 'imagelist_search_for' );
		$search = $wgRequest->getText( 'ilsearch' );
		$encSearch = htmlspecialchars( $search );
		$s = "<form method=\"get\" action=\"$url\">\n" . 
			wfMsgHtml( 'table_pager_limit', $this->getLimitSelect() );
		if ( !$wgMiserMode ) {
			$s .= "<br/>\n" . $msgSearch .
				" <input type=\"text\" size=\"20\" name=\"ilsearch\" value=\"$encSearch\"/><br/>\n";
		}
		$s .= " <input type=\"submit\" value=\"$msgSubmit\"/>\n" .
			$this->getHiddenFields( array( 'limit', 'ilsearch' ) ) .
			"</form>\n";
		return $s;
	}

	function getTableClass() {
		return 'imagelist ' . parent::getTableClass();
	}

	function getNavClass() {
		return 'imagelist_nav ' . parent::getNavClass();
	}

	function getSortHeaderClass() {
		return 'imagelist_sort ' . parent::getSortHeaderClass();
	}
}

?>
