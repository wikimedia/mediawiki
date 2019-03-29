<?php
/**
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
 * @ingroup Pager
 */

/**
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;

class ImageListPager extends TablePager {

	protected $mFieldNames = null;

	// Subclasses should override buildQueryConds instead of using $mQueryConds variable.
	protected $mQueryConds = [];

	protected $mUserName = null;

	/**
	 * The relevant user
	 *
	 * @var User|null
	 */
	protected $mUser = null;

	protected $mSearch = '';

	protected $mIncluding = false;

	protected $mShowAll = false;

	protected $mTableName = 'image';

	public function __construct( IContextSource $context, $userName = null, $search = '',
		$including = false, $showAll = false
	) {
		$this->setContext( $context );
		$this->mIncluding = $including;
		$this->mShowAll = $showAll;

		if ( $userName !== null && $userName !== '' ) {
			$nt = Title::makeTitleSafe( NS_USER, $userName );
			if ( is_null( $nt ) ) {
				$this->outputUserDoesNotExist( $userName );
			} else {
				$this->mUserName = $nt->getText();
				$user = User::newFromName( $this->mUserName, false );
				if ( $user ) {
					$this->mUser = $user;
				}
				if ( !$user || ( $user->isAnon() && !User::isIP( $user->getName() ) ) ) {
					$this->outputUserDoesNotExist( $userName );
				}
			}
		}

		if ( $search !== '' && !$this->getConfig()->get( 'MiserMode' ) ) {
			$this->mSearch = $search;
			$nt = Title::newFromText( $this->mSearch );

			if ( $nt ) {
				$dbr = wfGetDB( DB_REPLICA );
				$this->mQueryConds[] = 'LOWER(img_name)' .
					$dbr->buildLike( $dbr->anyString(),
						strtolower( $nt->getDBkey() ), $dbr->anyString() );
			}
		}

		if ( !$including ) {
			if ( $this->getRequest()->getText( 'sort', 'img_date' ) == 'img_date' ) {
				$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
			} else {
				$this->mDefaultDirection = IndexPager::DIR_ASCENDING;
			}
		} else {
			$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
		}

		parent::__construct( $context );
	}

	/**
	 * Get the user relevant to the ImageList
	 *
	 * @return User|null
	 */
	function getRelevantUser() {
		return $this->mUser;
	}

	/**
	 * Add a message to the output stating that the user doesn't exist
	 *
	 * @param string $userName Unescaped user name
	 */
	protected function outputUserDoesNotExist( $userName ) {
		$this->getOutput()->wrapWikiMsg(
			"<div class=\"mw-userpage-userdoesnotexist error\">\n$1\n</div>",
			[
				'listfiles-userdoesnotexist',
				wfEscapeWikiText( $userName ),
			]
		);
	}

	/**
	 * Build the where clause of the query.
	 *
	 * Replaces the older mQueryConds member variable.
	 * @param string $table Either "image" or "oldimage"
	 * @return array The query conditions.
	 */
	protected function buildQueryConds( $table ) {
		$prefix = $table === 'image' ? 'img' : 'oi';
		$conds = [];

		if ( !is_null( $this->mUserName ) ) {
			// getQueryInfoReal() should have handled the tables and joins.
			$dbr = wfGetDB( DB_REPLICA );
			$actorWhere = ActorMigration::newMigration()->getWhere(
				$dbr,
				$prefix . '_user',
				User::newFromName( $this->mUserName, false ),
				// oldimage doesn't have an index on oi_user, while image does. Set $useId accordingly.
				$prefix === 'img'
			);
			$conds[] = $actorWhere['conds'];
		}

		if ( $this->mSearch !== '' ) {
			$nt = Title::newFromText( $this->mSearch );
			if ( $nt ) {
				$dbr = wfGetDB( DB_REPLICA );
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
	 * The array keys (but not the array values) are used in sql. Phan
	 * gets confused by this, so mark this method as being ok for sql in general.
	 * @return-taint onlysafefor_sql
	 * @return array
	 */
	function getFieldNames() {
		if ( !$this->mFieldNames ) {
			$this->mFieldNames = [
				'img_timestamp' => $this->msg( 'listfiles_date' )->text(),
				'img_name' => $this->msg( 'listfiles_name' )->text(),
				'thumb' => $this->msg( 'listfiles_thumb' )->text(),
				'img_size' => $this->msg( 'listfiles_size' )->text(),
			];
			if ( is_null( $this->mUserName ) ) {
				// Do not show username if filtering by username
				$this->mFieldNames['img_user_text'] = $this->msg( 'listfiles_user' )->text();
			}
			// img_description down here, in order so that its still after the username field.
			$this->mFieldNames['img_description'] = $this->msg( 'listfiles_description' )->text();

			if ( !$this->getConfig()->get( 'MiserMode' ) && !$this->mShowAll ) {
				$this->mFieldNames['count'] = $this->msg( 'listfiles_count' )->text();
			}
			if ( $this->mShowAll ) {
				$this->mFieldNames['top'] = $this->msg( 'listfiles-latestversion' )->text();
			}
		}

		return $this->mFieldNames;
	}

	function isFieldSortable( $field ) {
		if ( $this->mIncluding ) {
			return false;
		}
		$sortable = [ 'img_timestamp', 'img_name', 'img_size' ];
		/* For reference, the indicies we can use for sorting are:
		 * On the image table: img_user_timestamp/img_usertext_timestamp/img_actor_timestamp,
		 * img_size, img_timestamp
		 * On oldimage: oi_usertext_timestamp/oi_actor_timestamp, oi_name_timestamp
		 *
		 * In particular that means we cannot sort by timestamp when not filtering
		 * by user and including old images in the results. Which is sad.
		 */
		if ( $this->getConfig()->get( 'MiserMode' ) && !is_null( $this->mUserName ) ) {
			// If we're sorting by user, the index only supports sorting by time.
			if ( $field === 'img_timestamp' ) {
				return true;
			} else {
				return false;
			}
		} elseif ( $this->getConfig()->get( 'MiserMode' )
			&& $this->mShowAll /* && mUserName === null */
		) {
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
	 * @param string $table Either 'image' or 'oldimage'
	 * @return array Query info
	 */
	protected function getQueryInfoReal( $table ) {
		$dbr = wfGetDB( DB_REPLICA );
		$prefix = $table === 'oldimage' ? 'oi' : 'img';

		$tables = [ $table ];
		$fields = array_keys( $this->getFieldNames() );
		$fields = array_combine( $fields, $fields );
		unset( $fields['img_description'] );
		unset( $fields['img_user_text'] );

		if ( $table === 'oldimage' ) {
			foreach ( $fields as $id => $field ) {
				if ( substr( $id, 0, 4 ) === 'img_' ) {
					$fields[$id] = $prefix . substr( $field, 3 );
				}
			}
			$fields['top'] = $dbr->addQuotes( 'no' );
		} elseif ( $this->mShowAll ) {
			$fields['top'] = $dbr->addQuotes( 'yes' );
		}
		$fields['thumb'] = $prefix . '_name';

		$options = $join_conds = [];

		# Description field
		$commentQuery = CommentStore::getStore()->getJoin( $prefix . '_description' );
		$tables += $commentQuery['tables'];
		$fields += $commentQuery['fields'];
		$join_conds += $commentQuery['joins'];
		$fields['description_field'] = $dbr->addQuotes( "{$prefix}_description" );

		# User fields
		$actorQuery = ActorMigration::newMigration()->getJoin( $prefix . '_user' );
		$tables += $actorQuery['tables'];
		$join_conds += $actorQuery['joins'];
		$fields['img_user'] = $actorQuery['fields'][$prefix . '_user'];
		$fields['img_user_text'] = $actorQuery['fields'][$prefix . '_user_text'];
		$fields['img_actor'] = $actorQuery['fields'][$prefix . '_actor'];

		# Depends on $wgMiserMode
		# Will also not happen if mShowAll is true.
		if ( isset( $fields['count'] ) ) {
			$fields['count'] = $dbr->buildSelectSubquery(
				'oldimage',
				'COUNT(oi_archive_name)',
				'oi_name = img_name',
				__METHOD__
			);
		}

		return [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $this->buildQueryConds( $table ),
			'options' => $options,
			'join_conds' => $join_conds
		];
	}

	/**
	 * Override reallyDoQuery to mix together two queries.
	 *
	 * @note $asc is named $descending in IndexPager base class. However
	 *   it is true when the order is ascending, and false when the order
	 *   is descending, so I renamed it to $asc here.
	 * @param int $offset
	 * @param int $limit
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return FakeResultWrapper
	 * @throws MWException
	 */
	function reallyDoQuery( $offset, $limit, $order ) {
		$prevTableName = $this->mTableName;
		$this->mTableName = 'image';
		list( $tables, $fields, $conds, $fname, $options, $join_conds ) =
			$this->buildQueryInfo( $offset, $limit, $order );
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

		list( $tables, $fields, $conds, $fname, $options, $join_conds ) =
			$this->buildQueryInfo( $offset, $limit, $order );
		$oldimageRes = $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );

		$this->mTableName = $prevTableName;
		$this->mIndexField = $oldIndex;

		return $this->combineResult( $imageRes, $oldimageRes, $limit, $order );
	}

	/**
	 * Combine results from 2 tables.
	 *
	 * Note: This will throw away some results
	 *
	 * @param IResultWrapper $res1
	 * @param IResultWrapper $res2
	 * @param int $limit
	 * @param bool $ascending See note about $asc in $this->reallyDoQuery
	 * @return FakeResultWrapper $res1 and $res2 combined
	 */
	protected function combineResult( $res1, $res2, $limit, $ascending ) {
		$res1->rewind();
		$res2->rewind();
		$topRes1 = $res1->next();
		$topRes2 = $res2->next();
		$resultArray = [];
		for ( $i = 0; $i < $limit && $topRes1 && $topRes2; $i++ ) {
			if ( strcmp( $topRes1->{$this->mIndexField}, $topRes2->{$this->mIndexField} ) > 0 ) {
				if ( !$ascending ) {
					$resultArray[] = $topRes1;
					$topRes1 = $res1->next();
				} else {
					$resultArray[] = $topRes2;
					$topRes2 = $res2->next();
				}
			} elseif ( !$ascending ) {
				$resultArray[] = $topRes2;
				$topRes2 = $res2->next();
			} else {
				$resultArray[] = $topRes1;
				$topRes1 = $res1->next();
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
		if ( $this->mShowAll && $this->getConfig()->get( 'MiserMode' ) && is_null( $this->mUserName ) ) {
			// Unfortunately no index on oi_timestamp.
			return 'img_name';
		} else {
			return 'img_timestamp';
		}
	}

	protected function doBatchLookups() {
		$userIds = [];
		$this->mResult->seek( 0 );
		foreach ( $this->mResult as $row ) {
			$userIds[] = $row->img_user;
		}
		# Do a link batch query for names and userpages
		UserCache::singleton()->doQuery( $userIds, [ 'userpage' ], __METHOD__ );
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
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		switch ( $field ) {
			case 'thumb':
				$opt = [ 'time' => wfTimestamp( TS_MW, $this->mCurrentRow->img_timestamp ) ];
				$file = RepoGroup::singleton()->getLocalRepo()->findFile( $value, $opt );
				// If statement for paranoia
				if ( $file ) {
					$thumb = $file->transform( [ 'width' => 180, 'height' => 360 ] );
					if ( $thumb ) {
						return $thumb->toHtml( [ 'desc-link' => true ] );
					} else {
						return $this->msg( 'thumbnail_error', '' )->escaped();
					}
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

				// Weird files can maybe exist? T24227
				$filePage = Title::makeTitleSafe( NS_FILE, $value );
				if ( $filePage ) {
					$link = $linkRenderer->makeKnownLink(
						$filePage,
						$filePage->getText()
					);
					$download = Xml::element( 'a',
						[ 'href' => wfLocalFile( $filePage )->getUrl() ],
						$imgfile
					);
					$download = $this->msg( 'parentheses' )->rawParams( $download )->escaped();

					// Add delete links if allowed
					// From https://github.com/Wikia/app/pull/3859
					if ( $filePage->userCan( 'delete', $this->getUser() ) ) {
						$deleteMsg = $this->msg( 'listfiles-delete' )->text();

						$delete = $linkRenderer->makeKnownLink(
							$filePage, $deleteMsg, [], [ 'action' => 'delete' ]
						);
						$delete = $this->msg( 'parentheses' )->rawParams( $delete )->escaped();

						return "$link $download $delete";
					}

					return "$link $download";
				} else {
					return htmlspecialchars( $value );
				}
			case 'img_user_text':
				if ( $this->mCurrentRow->img_user ) {
					$name = User::whoIs( $this->mCurrentRow->img_user );
					$link = $linkRenderer->makeLink(
						Title::makeTitle( NS_USER, $name ),
						$name
					);
				} else {
					$link = htmlspecialchars( $value );
				}

				return $link;
			case 'img_size':
				return htmlspecialchars( $this->getLanguage()->formatSize( $value ) );
			case 'img_description':
				$field = $this->mCurrentRow->description_field;
				$value = CommentStore::getStore()->getComment( $field, $this->mCurrentRow )->text;
				return Linker::formatComment( $value );
			case 'count':
				return $this->getLanguage()->formatNum( intval( $value ) + 1 );
			case 'top':
				// Messages: listfiles-latestversion-yes, listfiles-latestversion-no
				return $this->msg( 'listfiles-latestversion-' . $value );
			default:
				throw new MWException( "Unknown field '$field'" );
		}
	}

	function getForm() {
		$formDescriptor = [];
		$formDescriptor['limit'] = [
			'type' => 'select',
			'name' => 'limit',
			'label-message' => 'table_pager_limit_label',
			'options' => $this->getLimitSelectList(),
			'default' => $this->mLimit,
		];

		if ( !$this->getConfig()->get( 'MiserMode' ) ) {
			$formDescriptor['ilsearch'] = [
				'type' => 'text',
				'name' => 'ilsearch',
				'id' => 'mw-ilsearch',
				'label-message' => 'listfiles_search_for',
				'default' => $this->mSearch,
				'size' => '40',
				'maxlength' => '255',
			];
		}

		$formDescriptor['user'] = [
			'type' => 'user',
			'name' => 'user',
			'id' => 'mw-listfiles-user',
			'label-message' => 'username',
			'default' => $this->mUserName,
			'size' => '40',
			'maxlength' => '255',
		];

		$formDescriptor['ilshowall'] = [
			'type' => 'check',
			'name' => 'ilshowall',
			'id' => 'mw-listfiles-show-all',
			'label-message' => 'listfiles-show-all',
			'default' => $this->mShowAll,
		];

		$query = $this->getRequest()->getQueryValues();
		unset( $query['title'] );
		unset( $query['limit'] );
		unset( $query['ilsearch'] );
		unset( $query['ilshowall'] );
		unset( $query['user'] );

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setMethod( 'get' )
			->setId( 'mw-listfiles-form' )
			->setTitle( $this->getTitle() )
			->setSubmitTextMsg( 'table_pager_limit_submit' )
			->setWrapperLegendMsg( 'listfiles' )
			->addHiddenFields( $query )
			->prepareForm()
			->displayForm( '' );
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' listfiles';
	}

	protected function getNavClass() {
		return parent::getNavClass() . ' listfiles_nav';
	}

	protected function getSortHeaderClass() {
		return parent::getSortHeaderClass() . ' listfiles_sort';
	}

	function getPagingQueries() {
		$queries = parent::getPagingQueries();
		if ( !is_null( $this->mUserName ) ) {
			# Append the username to the query string
			foreach ( $queries as &$query ) {
				if ( $query !== false ) {
					$query['user'] = $this->mUserName;
				}
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
