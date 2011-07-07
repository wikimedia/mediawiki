<?php
/**
 * Implements Special:Listfiles
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

class SpecialListFiles extends IncludableSpecialPage {

	public function __construct(){
		parent::__construct( 'Listfiles' );
	}

	public function execute( $par ){
		global $wgOut, $wgRequest;
		$this->setHeaders();
		$this->outputHeader();

		if ( $this->including() ) {
			$userName = $par;
			$search = '';
		} else {
			$userName = $wgRequest->getText( 'user', $par );
			$search = $wgRequest->getText( 'ilsearch', '' );
		}

		$pager = new ImageListPager( $userName, $search, $this->including() );

		if ( $this->including() ) {
			$html = $pager->getBody();
		} else {
			$form = $pager->getForm();
			$body = $pager->getBody();
			$nav = $pager->getNavigationBar();
			$html = "$form<br />\n$body<br />\n$nav";
		}
		$wgOut->addHTML( $html );
	}
}

/**
 * @ingroup SpecialPage Pager
 */
class ImageListPager extends TablePager {
	var $mFieldNames = null;
	var $mQueryConds = array();
	var $mUserName = null;
	var $mSearch = '';
	var $mIncluding = false;

	function __construct( $userName = null, $search = '', $including = false ) {
		global $wgRequest, $wgMiserMode;

		$this->mIncluding = $including;

		if ( $userName ) {
			$nt = Title::newFromText( $userName, NS_USER );
			if ( !is_null( $nt ) ) {
				$this->mUserName = $nt->getText();
				$this->mQueryConds['img_user_text'] = $this->mUserName;
			}
		}

		if ( $search != '' && !$wgMiserMode ) {
			$this->mSearch = $search;
			$nt = Title::newFromURL( $this->mSearch );
			if ( $nt ) {
				$dbr = wfGetDB( DB_SLAVE );
				$this->mQueryConds[] = 'LOWER(img_name)' .
					$dbr->buildLike( $dbr->anyString(),
						strtolower( $nt->getDBkey() ), $dbr->anyString() );
			}
		}

		if ( !$including ) {
			if ( $wgRequest->getText( 'sort', 'img_date' ) == 'img_date' ) {
				$this->mDefaultDirection = true;
			} else {
				$this->mDefaultDirection = false;
			}
		} else {
			$this->mDefaultDirection = true;
		}

		parent::__construct();
	}

	function getTitle() {
		return SpecialPage::getTitleFor( 'Listfiles' );
	}

	/**
	 * @return Array
	 */
	function getFieldNames() {
		if ( !$this->mFieldNames ) {
			global $wgMiserMode;
			$this->mFieldNames = array(
				'img_timestamp' => wfMsg( 'listfiles_date' ),
				'img_name' => wfMsg( 'listfiles_name' ),
				'thumb' => wfMsg( 'listfiles_thumb' ),
				'img_size' => wfMsg( 'listfiles_size' ),
				'img_user_text' => wfMsg( 'listfiles_user' ),
				'img_description' => wfMsg( 'listfiles_description' ),
			);
			if( !$wgMiserMode ) {
				$this->mFieldNames['count'] = wfMsg( 'listfiles_count' );
			}
		}
		return $this->mFieldNames;
	}

	function isFieldSortable( $field ) {
		if ( $this->mIncluding ) {
			return false;
		}
		static $sortable = array( 'img_timestamp', 'img_name' );
		if ( $field == 'img_size' ) {
			# No index for both img_size and img_user_text
			return !isset( $this->mQueryConds['img_user_text'] );
		}
		return in_array( $field, $sortable );
	}

	function getQueryInfo() {
		$tables = array( 'image' );
		$fields = array_keys( $this->getFieldNames() );
		$fields[] = 'img_user';
		$fields[array_search('thumb', $fields)] = 'img_name AS thumb';
		$options = $join_conds = array();

		# Depends on $wgMiserMode
		if( isset( $this->mFieldNames['count'] ) ) {
			$tables[] = 'oldimage';

			# Need to rewrite this one
			foreach ( $fields as &$field ) {
				if ( $field == 'count' ) {
					$field = 'COUNT(oi_archive_name) AS count';
				}
			}
			unset( $field );

			$dbr = wfGetDB( DB_SLAVE );
			if( $dbr->implicitGroupby() ) {
				$options = array( 'GROUP BY' => 'img_name' );
			} else {
				$columnlist = implode( ',',
					preg_grep( '/^img/', array_keys( $this->getFieldNames() ) ) );
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
			foreach ( $this->mResult as $row ) {
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
			case 'thumb':
				$file = wfLocalFile( $value );
				$thumb = $file->transform( array( 'width' => 180, 'height' => 360 ) );
				return $thumb->toHtml( array( 'desc-link' => true ) );
			case 'img_timestamp':
				return htmlspecialchars( $wgLang->timeanddate( $value, true ) );
			case 'img_name':
				static $imgfile = null;
				if ( $imgfile === null ) $imgfile = wfMsg( 'imgfile' );

				// Weird files can maybe exist? Bug 22227
				$filePage = Title::makeTitleSafe( NS_FILE, $value );
				if( $filePage ) {
					$link = $this->getSkin()->linkKnown( $filePage, htmlspecialchars( $filePage->getText() ) );
					$download = Xml::element( 'a',
						array( 'href' => wfLocalFile( $filePage )->getURL() ),
						$imgfile
					);
					return "$link ($download)";
				} else {
					return htmlspecialchars( $value );
				}
			case 'img_user_text':
				if ( $this->mCurrentRow->img_user ) {
					$link = $this->getSkin()->link(
						Title::makeTitle( NS_USER, $value ),
						htmlspecialchars( $value )
					);
				} else {
					$link = htmlspecialchars( $value );
				}
				return $link;
			case 'img_size':
				return $this->getSkin()->formatSize( $value );
			case 'img_description':
				return $this->getSkin()->commentBlock( $value );
			case 'count':
				return intval( $value ) + 1;
		}
	}

	function getForm() {
		global $wgScript, $wgMiserMode;
		$inputForm = array();
		$inputForm['table_pager_limit_label'] = $this->getLimitSelect();
		if ( !$wgMiserMode ) {
			$inputForm['listfiles_search_for'] = Html::input( 'ilsearch', $this->mSearch, 'text',
				array(
					'size' 		=> '40',
					'maxlength' => '255',
					'id' 		=> 'mw-ilsearch',
			) );
		}
		$inputForm['username'] = Html::input( 'user', $this->mUserName, 'text', array(
			'size' 		=> '40',
			'maxlength' => '255',
			'id' 		=> 'mw-listfiles-user',
		) );
		return Html::openElement( 'form',
				array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listfiles-form' ) ) .
			Xml::fieldset( wfMsg( 'listfiles' ) ) .
			Xml::buildForm( $inputForm, 'table_pager_limit_submit' ) .
			$this->getHiddenFields( array( 'limit', 'ilsearch', 'user' ) ) .
			Html::closeElement( 'fieldset' ) .
			Html::closeElement( 'form' ) . "\n";
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

	function getPagingQueries() {
		$queries = parent::getPagingQueries();
		if ( !is_null( $this->mUserName ) ) {
			# Append the username to the query string
			foreach ( $queries as &$query ) {
				$query['user'] = $this->mUserName;
			}
		}
		return $queries;
	}

	function getDefaultQuery() {
		$queries = parent::getDefaultQuery();
		if ( !isset( $queries['user'] ) && !is_null( $this->mUserName ) ) {
			$queries['user'] = $this->mUserName;
		}
		return $queries;
	}
}
