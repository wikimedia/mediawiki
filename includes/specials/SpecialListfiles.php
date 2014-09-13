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
	public function __construct() {
		parent::__construct( 'Listfiles' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		if ( $this->including() ) {
			$userName = $par;
			$search = '';
			$showAll = false;
		} else {
			$userName = $this->getRequest()->getText( 'user', $par );
			$search = $this->getRequest()->getText( 'ilsearch', '' );
			$showAll = $this->getRequest()->getBool( 'ilshowall', false );
		}

		$pager = new ImageListPager(
			$this->getContext(),
			$userName,
			$search,
			$this->including(),
			$showAll
		);

		if ( $this->including() ) {
			$html = $pager->getBody();
		} else {
			$form = $pager->getForm();
			$body = $pager->getBody();
			$nav = $pager->getNavigationBar();
			$html = "$form<br />\n$body<br />\n$nav";
		}
		$this->getOutput()->addHTML( $html );
	}

	protected function getGroupName() {
		return 'media';
	}
}

/**
 * @ingroup SpecialPage Pager
 */
class ImageListPager extends TablePager {
	var $mFieldNames = null;
	// Subclasses should override buildQueryConds instead of using $mQueryConds variable.
	var $mQueryConds = array();
	var $mUserName = null;
	var $mSearch = '';
	var $mIncluding = false;
	var $mShowAll = false;
	var $mTableName = 'image';

	function __construct( IContextSource $context, $userName = null, $search = '',
		$including = false, $showAll = false
	) {
		global $wgMiserMode;

		$this->mIncluding = $including;
		$this->mShowAll = $showAll;

		if ( $userName ) {
			$nt = Title::newFromText( $userName, NS_USER );
			if ( !is_null( $nt ) ) {
				$this->mUserName = $nt->getText();
			}
		}

		if ( $search !== '' && !$wgMiserMode ) {
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
			if ( $context->getRequest()->getText( 'sort', 'img_date' ) == 'img_date' ) {
				$this->mDefaultDirection = true;
			} else {
				$this->mDefaultDirection = false;
			}
		} else {
			$this->mDefaultDirection = true;
		}

		parent::__construct( $context );
	}

	/**
	 * Build the where clause of the query.
	 *
	 * Replaces the older mQueryConds member variable.
	 * @param $table String Either "image" or "oldimage"
	 * @return array The query conditions.
	 */
	protected function buildQueryConds( $table ) {
		$prefix = $table === 'image' ? 'img' : 'oi';
		$conds = array();

		if ( !is_null( $this->mUserName ) ) {
			$conds[$prefix . '_user_text'] = $this->mUserName;
		}

		if ( $this->mSearch !== '' ) {
			$nt = Title::newFromURL( $this->mSearch );
			if ( $nt ) {
				$dbr = wfGetDB( DB_SLAVE );
				$conds[] = 'LOWER(' . $prefix . '_name)' .
					$dbr->buildLike( $dbr->anyString(),
						strtolower( $nt->getDBkey() ), $dbr->anyString() );
			}
		}

		if ( $table === 'oldimage' ) {
			// Don't want to deal with revdel.
			// Future fixme: Show partial information as appropriate.
			// Would have to be careful about filtering by username when username is deleted.
			$conds['oi_deleted'] = 0;
		}

		// Add mQueryConds in case anyone was subclassing and using the old variable.
		return $conds + $this->mQueryConds;
	}

	/**
	 * @return Array
	 */
	function getFieldNames() {
		if ( !$this->mFieldNames ) {
			global $wgMiserMode;
			$this->mFieldNames = array(
				'img_timestamp' => $this->msg( 'listfiles_date' )->text(),
				'img_name' => $this->msg( 'listfiles_name' )->text(),
				'thumb' => $this->msg( 'listfiles_thumb' )->text(),
				'img_size' => $this->msg( 'listfiles_size' )->text(),
				'img_user_text' => $this->msg( 'listfiles_user' )->text(),
				'img_description' => $this->msg( 'listfiles_description' )->text(),
			);
			if ( !$wgMiserMode && !$this->mShowAll ) {
				$this->mFieldNames['count'] = $this->msg( 'listfiles_count' )->text();
			}
			if ( $this->mShowAll ) {
				$this->mFieldNames['top'] = $this->msg( 'listfiles-latestversion' )->text();
			}
		}

		return $this->mFieldNames;
	}

	function isFieldSortable( $field ) {
		global $wgMiserMode;
		if ( $this->mIncluding ) {
			return false;
		}
		$sortable = array( 'img_timestamp', 'img_name', 'img_size' );
		/* For reference, the indicies we can use for sorting are:
		 * On the image table: img_usertext_timestamp, img_size, img_timestamp
		 * On oldimage: oi_usertext_timestamp, oi_name_timestamp
		 *
		 * In particular that means we cannot sort by timestamp when not filtering
		 * by user and including old images in the results. Which is sad.
		 */
		if ( $wgMiserMode && !is_null( $this->mUserName ) ) {
			// If we're sorting by user, the index only supports sorting by time.
			if ( $field === 'img_timestamp' ) {
				return true;
			} else {
				return false;
			}
		} elseif ( $wgMiserMode && $this->mShowAll /* && mUserName === null */ ) {
			// no oi_timestamp index, so only alphabetical sorting in this case.
			if ( $field === 'img_name' ) {
				return true;
			} else {
				return false;
			}
		}

		return in_array( $field, $sortable );
	}

	function getQueryInfo() {
		// Hacky Hacky Hacky - I want to get query info
		// for two different tables, without reimplementing
		// the pager class.
		$qi = $this->getQueryInfoReal( $this->mTableName );

		return $qi;
	}

	/**
	 * Actually get the query info.
	 *
	 * This is to allow displaying both stuff from image and oldimage table.
	 *
	 * This is a bit hacky.
	 *
	 * @param $table String Either 'image' or 'oldimage'
	 * @return array Query info
	 */
	protected function getQueryInfoReal( $table ) {
		$prefix = $table === 'oldimage' ? 'oi' : 'img';

		$tables = array( $table );
		$fields = array_keys( $this->getFieldNames() );

		if ( $table === 'oldimage' ) {
			foreach ( $fields as $id => &$field ) {
				if ( substr( $field, 0, 4 ) !== 'img_' ) {
					continue;
				}
				$field = $prefix . substr( $field, 3 ) . ' AS ' . $field;
			}
			$fields[array_search( 'top', $fields )] = "'no' AS top";
		} else {
			if ( $this->mShowAll ) {
				$fields[array_search( 'top', $fields )] = "'yes' AS top";
			}
		}
		$fields[] = $prefix . '_user AS img_user';
		$fields[array_search( 'thumb', $fields )] = $prefix . '_name AS thumb';

		$options = $join_conds = array();

		# Depends on $wgMiserMode
		# Will also not happen if mShowAll is true.
		if ( isset( $this->mFieldNames['count'] ) ) {
			$tables[] = 'oldimage';

			# Need to rewrite this one
			foreach ( $fields as &$field ) {
				if ( $field == 'count' ) {
					$field = 'COUNT(oi_archive_name) AS count';
				}
			}
			unset( $field );

			$dbr = wfGetDB( DB_SLAVE );
			if ( $dbr->implicitGroupby() ) {
				$options = array( 'GROUP BY' => 'img_name' );
			} else {
				$columnlist = preg_grep( '/^img/', array_keys( $this->getFieldNames() ) );
				$options = array( 'GROUP BY' => array_merge( array( 'img_user' ), $columnlist ) );
			}
			$join_conds = array( 'oldimage' => array( 'LEFT JOIN', 'oi_name = img_name' ) );
		}

		return array(
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $this->buildQueryConds( $table ),
			'options' => $options,
			'join_conds' => $join_conds
		);
	}

	/**
	 * Override reallyDoQuery to mix together two queries.
	 *
	 * @note $asc is named $descending in IndexPager base class. However
	 *   it is true when the order is ascending, and false when the order
	 *   is descending, so I renamed it to $asc here.
	 */
	function reallyDoQuery( $offset, $limit, $asc ) {
		$prevTableName = $this->mTableName;
		$this->mTableName = 'image';
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = $this->buildQueryInfo( $offset, $limit, $asc );
		$imageRes = $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );
		$this->mTableName = $prevTableName;

		if ( !$this->mShowAll ) {
			return $imageRes;
		}

		$this->mTableName = 'oldimage';

		# Hacky...
		$oldIndex = $this->mIndexField;
		if ( substr( $this->mIndexField, 0, 4 ) !== 'img_' ) {
			throw new MWException( "Expected to be sorting on an image table field" );
		}
		$this->mIndexField = 'oi_' . substr( $this->mIndexField, 4 );

		list( $tables, $fields, $conds, $fname, $options, $join_conds ) = $this->buildQueryInfo( $offset, $limit, $asc );
		$oldimageRes = $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );

		$this->mTableName = $prevTableName;
		$this->mIndexField = $oldIndex;

		return $this->combineResult( $imageRes, $oldimageRes, $limit, $asc );
	}

	/**
	 * Combine results from 2 tables.
	 *
	 * Note: This will throw away some results
	 *
	 * @param $res1 ResultWrapper
	 * @param $res2 ResultWrapper
	 * @param $limit int
	 * @param $ascending boolean See note about $asc in $this->reallyDoQuery
	 * @return FakeResultWrapper $res1 and $res2 combined
	 */
	protected function combineResult( $res1, $res2, $limit, $ascending ) {
		$res1->rewind();
		$res2->rewind();
		$topRes1 = $res1->next();
		$topRes2 = $res2->next();
		$resultArray = array();
		for ( $i = 0; $i < $limit && $topRes1 && $topRes2; $i++ ) {
			if ( strcmp( $topRes1->{$this->mIndexField}, $topRes2->{$this->mIndexField} ) > 0 ) {
				if ( !$ascending ) {
					$resultArray[] = $topRes1;
					$topRes1 = $res1->next();
				} else {
					$resultArray[] = $topRes2;
					$topRes2 = $res2->next();
				}
			} else {
				if ( !$ascending ) {
					$resultArray[] = $topRes2;
					$topRes2 = $res2->next();
				} else {
					$resultArray[] = $topRes1;
					$topRes1 = $res1->next();
				}
			}
		}
		for ( ; $i < $limit && $topRes1; $i++ ) {
			$resultArray[] = $topRes1;
			$topRes1 = $res1->next();
		}
		for ( ; $i < $limit && $topRes2; $i++ ) {
			$resultArray[] = $topRes2;
			$topRes2 = $res2->next();
		}

		return new FakeResultWrapper( $resultArray );
	}

	function getDefaultSort() {
		global $wgMiserMode;
		if ( $this->mShowAll && $wgMiserMode && is_null( $this->mUserName ) ) {
			// Unfortunately no index on oi_timestamp.
			return 'img_name';
		} else {
			return 'img_timestamp';
		}
	}

	function doBatchLookups() {
		$userIds = array();
		$this->mResult->seek( 0 );
		foreach ( $this->mResult as $row ) {
			$userIds[] = $row->img_user;
		}
		# Do a link batch query for names and userpages
		UserCache::singleton()->doQuery( $userIds, array( 'userpage' ), __METHOD__ );
	}

	/**
	 * @param string $field
	 * @param string $value
	 * @return Message|string|int The return type depends on the value of $field:
	 *   - thumb: string
	 *   - img_timestamp: string
	 *   - img_name: string
	 *   - img_user_text: string
	 *   - img_size: string
	 *   - img_description: string
	 *   - count: int
	 *   - top: Message
	 * @throws MWException
	 */
	function formatValue( $field, $value ) {
		switch ( $field ) {
			case 'thumb':
				$opt = array( 'time' => $this->mCurrentRow->img_timestamp );
				$file = RepoGroup::singleton()->getLocalRepo()->findFile( $value, $opt );
				// If statement for paranoia
				if ( $file ) {
					$thumb = $file->transform( array( 'width' => 180, 'height' => 360 ) );

					return $thumb->toHtml( array( 'desc-link' => true ) );
				} else {
					return htmlspecialchars( $value );
				}
			case 'img_timestamp':
				// We may want to make this a link to the "old" version when displaying old files
				return htmlspecialchars( $this->getLanguage()->userTimeAndDate( $value, $this->getUser() ) );
			case 'img_name':
				static $imgfile = null;
				if ( $imgfile === null ) {
					$imgfile = $this->msg( 'imgfile' )->text();
				}

				// Weird files can maybe exist? Bug 22227
				$filePage = Title::makeTitleSafe( NS_FILE, $value );
				if ( $filePage ) {
					$link = Linker::linkKnown(
						$filePage,
						htmlspecialchars( $filePage->getText() )
					);
					$download = Xml::element( 'a',
						array( 'href' => wfLocalFile( $filePage )->getURL() ),
						$imgfile
					);
					$download = $this->msg( 'parentheses' )->rawParams( $download )->escaped();

					return "$link $download";
				} else {
					return htmlspecialchars( $value );
				}
			case 'img_user_text':
				if ( $this->mCurrentRow->img_user ) {
					$name = User::whoIs( $this->mCurrentRow->img_user );
					$link = Linker::link(
						Title::makeTitle( NS_USER, $name ),
						htmlspecialchars( $name )
					);
				} else {
					$link = htmlspecialchars( $value );
				}

				return $link;
			case 'img_size':
				return htmlspecialchars( $this->getLanguage()->formatSize( $value ) );
			case 'img_description':
				return Linker::formatComment( $value );
			case 'count':
				return intval( $value ) + 1;
			case 'top':
				// Messages: listfiles-latestversion-yes, listfiles-latestversion-no
				return $this->msg( 'listfiles-latestversion-' . $value );
			default:
				throw new MWException( "Unknown field '$field'" );
		}
	}

	function getForm() {
		global $wgScript, $wgMiserMode;
		$inputForm = array();
		$inputForm['table_pager_limit_label'] = $this->getLimitSelect( array( 'tabindex' => 1 ) );
		if ( !$wgMiserMode ) {
			$inputForm['listfiles_search_for'] = Html::input(
				'ilsearch',
				$this->mSearch,
				'text',
				array(
					'size' => '40',
					'maxlength' => '255',
					'id' => 'mw-ilsearch',
					'tabindex' => 2,
				)
			);
		}
		$inputForm['username'] = Html::input( 'user', $this->mUserName, 'text', array(
			'size' => '40',
			'maxlength' => '255',
			'id' => 'mw-listfiles-user',
			'tabindex' => 3,
		) );

		$inputForm['listfiles-show-all'] = Html::input( 'ilshowall', 1, 'checkbox', array(
			'checked' => $this->mShowAll,
			'tabindex' => 4,
		) );

		return Html::openElement( 'form',
			array( 'method' => 'get', 'action' => $wgScript, 'id' => 'mw-listfiles-form' )
		) .
			Xml::fieldset( $this->msg( 'listfiles' )->text() ) .
			Html::hidden( 'title', $this->getTitle()->getPrefixedText() ) .
			Xml::buildForm( $inputForm, 'table_pager_limit_submit', array( 'tabindex' => 5 ) ) .
			$this->getHiddenFields( array( 'limit', 'ilsearch', 'user', 'title', 'ilshowall' ) ) .
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

	function getTitle() {
		return SpecialPage::getTitleFor( 'Listfiles' );
	}
}
