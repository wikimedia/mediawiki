<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 *
 */
function wfSpecialListfiles() {
	global $wgOut;

	$pager = new ImageListPager;

	$limit = $pager->getForm();
	$body = $pager->getBody();
	$nav = $pager->getNavigationBar();
	$wgOut->addHTML( "$limit<br />\n$body<br />\n$nav" );
}

/**
 * @ingroup SpecialPage Pager
 */
class ImageListPager extends TablePager {
	var $mFieldNames = null;
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
				$dbr = wfGetDB( DB_SLAVE );
				$m = $dbr->strencode( strtolower( $nt->getDBkey() ) );
				$m = str_replace( "%", "\\%", $m );
				$m = str_replace( "_", "\\_", $m );
				$this->mQueryConds = array( "LOWER(img_name) LIKE '%{$m}%'" );
			}
		}

		parent::__construct();
	}

	function getFieldNames() {
		if ( !$this->mFieldNames ) {
			global $wgMiserMode;
			$this->mFieldNames = array(
				'img_timestamp' => wfMsg( 'listfiles_date' ),
				'img_name' => wfMsg( 'listfiles_name' ),
				'img_user_text' => wfMsg( 'listfiles_user' ),
				'img_size' => wfMsg( 'listfiles_size' ),
				'img_description' => wfMsg( 'listfiles_description' ),
			);
			if( !$wgMiserMode ) {
				$this->mFieldNames['count'] = wfMsg( 'listfiles_count' );
			}
		}
		return $this->mFieldNames;
	}

	function isFieldSortable( $field ) {
		static $sortable = array( 'img_timestamp', 'img_name', 'img_size' );
		return in_array( $field, $sortable );
	}

	function getQueryInfo() {
		$tables = array( 'image' );
		$fields = array_keys( $this->getFieldNames() );
		$fields[] = 'img_user';
		$options = $join_conds = array();

		# Depends on $wgMiserMode
		if( isset( $this->mFieldNames['count'] ) ) {
			$tables[] = 'oldimage';

			# Need to rewrite this one
			foreach ( $fields as &$field )
				if ( $field == 'count' )
					$field = 'COUNT(oi_archive_name) as count';
			unset( $field );

			$dbr = wfGetDB( DB_SLAVE );
			if( $dbr->implicitGroupby() ) {
				$options = array( 'GROUP BY' => 'img_name' );
			} else {
				$columnlist = implode( ',', preg_grep( '/^img/', array_keys( $this->getFieldNames() ) ) );
				$options = array( 'GROUP BY' => "img_user, $columnlist" );
			}
			$join_conds = array( 'oldimage' => array( 'LEFT JOIN', 'oi_name = img_name' ) );
		}
		return array(
			'tables'     => $tables,
			'fields'     => $fields,
			'conds'      => $this->mQueryConds,
			'options'    => $options,
			'join_conds' => $join_conds
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

		return parent::getStartBody();
	}

	function formatValue( $field, $value ) {
		global $wgLang;
		switch ( $field ) {
			case 'img_timestamp':
				return $wgLang->timeanddate( $value, true );
			case 'img_name':
				static $imgfile = null;
				if ( $imgfile === null ) $imgfile = wfMsg( 'imgfile' );

				$name = $this->mCurrentRow->img_name;
				$link = $this->getSkin()->makeKnownLinkObj( Title::makeTitle( NS_FILE, $name ), $value );
				$image = wfLocalFile( $value );
				$url = $image->getURL();
				$download = Xml::element('a', array( 'href' => $url ), $imgfile );
				return "$link ($download)";
			case 'img_user_text':
				if ( $this->mCurrentRow->img_user ) {
					$link = $this->getSkin()->makeLinkObj( Title::makeTitle( NS_USER, $value ),
						htmlspecialchars( $value ) );
				} else {
					$link = htmlspecialchars( $value );
				}
				return $link;
			case 'img_size':
				return $this->getSkin()->formatSize( $value );
			case 'img_description':
				return $this->getSkin()->commentBlock( $value );
			case 'count':
				return intval($value)+1;
		}
	}

	function getForm() {
		global $wgRequest, $wgMiserMode;
		$search = $wgRequest->getText( 'ilsearch' );

		$s = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $this->getTitle()->getLocalURL(), 'id' => 'mw-listfiles-form' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'listfiles' ) ) .
			Xml::tags( 'label', null, wfMsgHtml( 'table_pager_limit', $this->getLimitSelect() ) );

		if ( !$wgMiserMode ) {
			$s .= "<br />\n" .
				Xml::inputLabel( wfMsg( 'listfiles_search_for' ), 'ilsearch', 'mw-ilsearch', 20, $search );
		}
		$s .= ' ' .
			Xml::submitButton( wfMsg( 'table_pager_limit_submit' ) ) ."\n" .
			$this->getHiddenFields( array( 'limit', 'ilsearch' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) . "\n";
		return $s;
	}

	function getTableClass() {
		return 'listfiles ' . parent::getTableClass();
	}

	function getNavClass() {
		return 'listfiles_nav ' . parent::getNavClass();
	}

	function getSortHeaderClass() {
		return 'listfiles_sort ' . parent::getSortHeaderClass();
	}
}
