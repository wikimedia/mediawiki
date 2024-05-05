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

namespace MediaWiki\Pager;

use LocalRepo;
use MediaWiki\Cache\UserCache;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserNameUtils;
use RepoGroup;
use UnexpectedValueException;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IResultWrapper;
use Xml;

/**
 * @ingroup Pager
 */
class ImageListPager extends TablePager {

	/** @var string[]|null */
	protected $mFieldNames = null;
	/**
	 * @deprecated Subclasses should override {@see buildQueryConds} instead
	 * @var array
	 */
	protected $mQueryConds = [];
	/** @var string|null */
	protected $mUserName = null;
	/** @var User|null The relevant user */
	protected $mUser = null;
	/** @var bool */
	protected $mIncluding = false;
	/** @var bool */
	protected $mShowAll = false;
	/** @var string */
	protected $mTableName = 'image';

	private CommentStore $commentStore;
	private LocalRepo $localRepo;
	private UserCache $userCache;
	private CommentFormatter $commentFormatter;

	/**
	 * The unique sort fields for the sort options for unique paginate
	 */
	private const INDEX_FIELDS = [
		'img_timestamp' => [ 'img_timestamp', 'img_name' ],
		'img_name' => [ 'img_name' ],
		'img_size' => [ 'img_size', 'img_name' ],
	];

	/**
	 * @param IContextSource $context
	 * @param CommentStore $commentStore
	 * @param LinkRenderer $linkRenderer
	 * @param IConnectionProvider $dbProvider
	 * @param RepoGroup $repoGroup
	 * @param UserCache $userCache
	 * @param UserNameUtils $userNameUtils
	 * @param CommentFormatter $commentFormatter
	 * @param string $userName
	 * @param string $search
	 * @param bool $including
	 * @param bool $showAll
	 */
	public function __construct(
		IContextSource $context,
		CommentStore $commentStore,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		RepoGroup $repoGroup,
		UserCache $userCache,
		UserNameUtils $userNameUtils,
		CommentFormatter $commentFormatter,
		$userName,
		$search,
		$including,
		$showAll
	) {
		$this->setContext( $context );

		$this->mIncluding = $including;
		$this->mShowAll = $showAll;

		if ( $userName !== null && $userName !== '' ) {
			$nt = Title::makeTitleSafe( NS_USER, $userName );
			if ( $nt === null ) {
				$this->outputUserDoesNotExist( $userName );
			} else {
				$this->mUserName = $nt->getText();
				$user = User::newFromName( $this->mUserName, false );
				if ( $user ) {
					$this->mUser = $user;
				}
				if ( !$user || ( $user->isAnon() && !$userNameUtils->isIP( $user->getName() ) ) ) {
					$this->outputUserDoesNotExist( $userName );
				}
			}
		}

		if ( $including ||
			$this->getRequest()->getText( 'sort', 'img_date' ) === 'img_date'
		) {
			$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
		} else {
			$this->mDefaultDirection = IndexPager::DIR_ASCENDING;
		}
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();

		parent::__construct( $context, $linkRenderer );
		$this->commentStore = $commentStore;
		$this->localRepo = $repoGroup->getLocalRepo();
		$this->userCache = $userCache;
		$this->commentFormatter = $commentFormatter;
	}

	/**
	 * Get the user relevant to the ImageList
	 *
	 * @return User|null
	 */
	public function getRelevantUser() {
		return $this->mUser;
	}

	/**
	 * Add a message to the output stating that the user doesn't exist
	 *
	 * @param string $userName Unescaped user name
	 */
	protected function outputUserDoesNotExist( $userName ) {
		$this->getOutput()->addHTML( Html::warningBox(
			$this->getOutput()->msg( 'listfiles-userdoesnotexist', wfEscapeWikiText( $userName ) )->parse(),
			'mw-userpage-userdoesnotexist'
		) );
	}

	/**
	 * Build the where clause of the query.
	 *
	 * Replaces the older mQueryConds member variable.
	 * @param string $table Either "image" or "oldimage"
	 * @return array The query conditions.
	 */
	protected function buildQueryConds( $table ) {
		$conds = [];

		if ( $this->mUserName !== null ) {
			// getQueryInfoReal() should have handled the tables and joins.
			$conds['actor_name'] = $this->mUserName;
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

	protected function getFieldNames() {
		if ( !$this->mFieldNames ) {
			$this->mFieldNames = [
				'img_timestamp' => $this->msg( 'listfiles_date' )->text(),
				'img_name' => $this->msg( 'listfiles_name' )->text(),
				'thumb' => $this->msg( 'listfiles_thumb' )->text(),
				'img_size' => $this->msg( 'listfiles_size' )->text(),
			];
			if ( $this->mUserName === null ) {
				// Do not show username if filtering by username
				$this->mFieldNames['img_actor'] = $this->msg( 'listfiles_user' )->text();
			}
			// img_description down here, in order so that its still after the username field.
			$this->mFieldNames['img_description'] = $this->msg( 'listfiles_description' )->text();

			if ( $this->mShowAll ) {
				$this->mFieldNames['top'] = $this->msg( 'listfiles-latestversion' )->text();
			} elseif ( !$this->getConfig()->get( MainConfigNames::MiserMode ) ) {
				$this->mFieldNames['count'] = $this->msg( 'listfiles_count' )->text();
			}
		}

		return $this->mFieldNames;
	}

	protected function isFieldSortable( $field ) {
		if ( $this->mIncluding ) {
			return false;
		}
		/* For reference, the indices we can use for sorting are:
		 * On the image table: img_actor_timestamp, img_size, img_timestamp
		 * On oldimage: oi_actor_timestamp, oi_name_timestamp
		 *
		 * In particular that means we cannot sort by timestamp when not filtering
		 * by user and including old images in the results. Which is sad. (T279982)
		 */
		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			if ( $this->mUserName !== null ) {
				// If we're sorting by user, the index only supports sorting by time.
				return $field === 'img_timestamp';
			} elseif ( $this->mShowAll ) {
				// no oi_timestamp index, so only alphabetical sorting in this case.
				return $field === 'img_name';
			}
		}

		return isset( self::INDEX_FIELDS[$field] );
	}

	public function getQueryInfo() {
		// Hacky Hacky Hacky - I want to get query info
		// for two different tables, without reimplementing
		// the pager class.
		return $this->getQueryInfoReal( $this->mTableName );
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
		$dbr = $this->getDatabase();
		$prefix = $table === 'oldimage' ? 'oi' : 'img';

		$tables = [ $table, 'actor' ];
		$join_conds = [];

		if ( $table === 'oldimage' ) {
			$fields = [
				'img_timestamp' => 'oi_timestamp',
				'img_name' => 'oi_name',
				'img_size' => 'oi_size',
				'top' => $dbr->addQuotes( 'no' )
			];
			$join_conds['actor'] = [ 'JOIN', 'actor_id=oi_actor' ];
		} else {
			$fields = [
				'img_timestamp',
				'img_name',
				'img_size',
				'top' => $dbr->addQuotes( 'yes' )
			];
			$join_conds['actor'] = [ 'JOIN', 'actor_id=img_actor' ];
		}

		# Description field
		$commentQuery = $this->commentStore->getJoin( $prefix . '_description' );
		$tables += $commentQuery['tables'];
		$fields += $commentQuery['fields'];
		$join_conds += $commentQuery['joins'];
		$fields['description_field'] = $dbr->addQuotes( "{$prefix}_description" );

		# Actor fields
		$fields[] = 'actor_user';
		$fields[] = 'actor_name';

		# Depends on $wgMiserMode
		# Will also not happen if mShowAll is true.
		if ( array_key_exists( 'count', $this->getFieldNames() ) ) {
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
			'options' => [],
			'join_conds' => $join_conds
		];
	}

	/**
	 * Override reallyDoQuery to mix together two queries.
	 *
	 * @param string $offset
	 * @param int $limit
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return IResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $order ) {
		$dbr = $this->getDatabase();
		$prevTableName = $this->mTableName;
		$this->mTableName = 'image';
		[ $tables, $fields, $conds, $fname, $options, $join_conds ] =
			$this->buildQueryInfo( $offset, $limit, $order );
		$imageRes = $dbr->newSelectQueryBuilder()
			->tables( is_array( $tables ) ? $tables : [ $tables ] )
			->fields( $fields )
			->conds( $conds )
			->caller( $fname )
			->options( $options )
			->joinConds( $join_conds )
			->fetchResultSet();
		$this->mTableName = $prevTableName;

		if ( !$this->mShowAll ) {
			return $imageRes;
		}

		$this->mTableName = 'oldimage';

		# Hacky...
		$oldIndex = $this->mIndexField;
		foreach ( $this->mIndexField as &$index ) {
			if ( !str_starts_with( $index, 'img_' ) ) {
				throw new UnexpectedValueException( "Expected to be sorting on an image table field" );
			}
			$index = 'oi_' . substr( $index, 4 );
		}
		unset( $index );

		[ $tables, $fields, $conds, $fname, $options, $join_conds ] =
			$this->buildQueryInfo( $offset, $limit, $order );
		$oldimageRes = $dbr->newSelectQueryBuilder()
			->tables( is_array( $tables ) ? $tables : [ $tables ] )
			->fields( $fields )
			->conds( $conds )
			->caller( $fname )
			->options( $options )
			->joinConds( $join_conds )
			->fetchResultSet();

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
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return IResultWrapper $res1 and $res2 combined
	 */
	protected function combineResult( $res1, $res2, $limit, $order ) {
		$res1->rewind();
		$res2->rewind();
		$topRes1 = $res1->fetchObject();
		$topRes2 = $res2->fetchObject();
		$resultArray = [];
		for ( $i = 0; $i < $limit && $topRes1 && $topRes2; $i++ ) {
			if ( strcmp( $topRes1->{$this->mIndexField[0]}, $topRes2->{$this->mIndexField[0]} ) > 0 ) {
				if ( $order !== IndexPager::QUERY_ASCENDING ) {
					$resultArray[] = $topRes1;
					$topRes1 = $res1->fetchObject();
				} else {
					$resultArray[] = $topRes2;
					$topRes2 = $res2->fetchObject();
				}
			} elseif ( $order !== IndexPager::QUERY_ASCENDING ) {
				$resultArray[] = $topRes2;
				$topRes2 = $res2->fetchObject();
			} else {
				$resultArray[] = $topRes1;
				$topRes1 = $res1->fetchObject();
			}
		}

		for ( ; $i < $limit && $topRes1; $i++ ) {
			$resultArray[] = $topRes1;
			$topRes1 = $res1->fetchObject();
		}

		for ( ; $i < $limit && $topRes2; $i++ ) {
			$resultArray[] = $topRes2;
			$topRes2 = $res2->fetchObject();
		}

		return new FakeResultWrapper( $resultArray );
	}

	public function getIndexField() {
		return [ self::INDEX_FIELDS[$this->mSort] ];
	}

	public function getDefaultSort() {
		if ( $this->mShowAll &&
			$this->getConfig()->get( MainConfigNames::MiserMode ) &&
			$this->mUserName === null
		) {
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
			if ( $row->actor_user ) {
				$userIds[] = $row->actor_user;
			}
		}
		# Do a link batch query for names and userpages
		$this->userCache->doQuery( $userIds, [ 'userpage' ], __METHOD__ );
	}

	/**
	 * @param string $field
	 * @param string|null $value
	 * @return string
	 */
	public function formatValue( $field, $value ) {
		$linkRenderer = $this->getLinkRenderer();
		switch ( $field ) {
			case 'thumb':
				$opt = [ 'time' => wfTimestamp( TS_MW, $this->mCurrentRow->img_timestamp ) ];
				$file = $this->localRepo->findFile( $this->getCurrentRow()->img_name, $opt );
				// If statement for paranoia
				if ( $file ) {
					$thumb = $file->transform( [ 'width' => 180, 'height' => 360 ] );
					if ( $thumb ) {
						return $thumb->toHtml( [ 'desc-link' => true, 'loading' => 'lazy' ] );
					}
					return $this->msg( 'thumbnail_error', '' )->escaped();
				} else {
					return htmlspecialchars( $this->getCurrentRow()->img_name );
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
					$html = $linkRenderer->makeKnownLink(
						$filePage,
						$filePage->getText()
					);
					$opt = [ 'time' => wfTimestamp( TS_MW, $this->mCurrentRow->img_timestamp ) ];
					$file = $this->localRepo->findFile( $value, $opt );
					if ( $file ) {
						$download = Xml::element(
							'a',
							[ 'href' => $file->getUrl() ],
							$imgfile
						);
						$html .= ' ' . $this->msg( 'parentheses' )->rawParams( $download )->escaped();
					}

					// Add delete links if allowed
					// From https://github.com/Wikia/app/pull/3859
					if ( $this->getAuthority()->probablyCan( 'delete', $filePage ) ) {
						$deleteMsg = $this->msg( 'listfiles-delete' )->text();

						$delete = $linkRenderer->makeKnownLink(
							$filePage, $deleteMsg, [], [ 'action' => 'delete' ]
						);
						$html .= ' ' . $this->msg( 'parentheses' )->rawParams( $delete )->escaped();
					}

					return $html;
				} else {
					return htmlspecialchars( $value );
				}
			case 'img_actor':
				if ( $this->mCurrentRow->actor_user ) {
					$name = $this->mCurrentRow->actor_name;
					$link = $linkRenderer->makeLink(
						Title::makeTitle( NS_USER, $name ),
						$name
					);
				} else {
					$link = $value !== null ? htmlspecialchars( $value ) : '';
				}

				return $link;
			case 'img_size':
				return htmlspecialchars( $this->getLanguage()->formatSize( (int)$value ) );
			case 'img_description':
				$field = $this->mCurrentRow->description_field;
				$value = $this->commentStore->getComment( $field, $this->mCurrentRow )->text;
				return $this->commentFormatter->format( $value );
			case 'count':
				return htmlspecialchars( $this->getLanguage()->formatNum( intval( $value ) + 1 ) );
			case 'top':
				// Messages: listfiles-latestversion-yes, listfiles-latestversion-no
				return $this->msg( 'listfiles-latestversion-' . $value )->escaped();
			default:
				throw new UnexpectedValueException( "Unknown field '$field'" );
		}
	}

	/**
	 * Escape the options list
	 * @return array
	 */
	private function getEscapedLimitSelectList(): array {
		$list = $this->getLimitSelectList();
		$result = [];
		foreach ( $list as $key => $value ) {
			$result[htmlspecialchars( $key )] = $value;
		}
		return $result;
	}

	public function getForm() {
		$formDescriptor = [];
		$formDescriptor['limit'] = [
			'type' => 'radio',
			'name' => 'limit',
			'label-message' => 'table_pager_limit_label',
			'options' => $this->getEscapedLimitSelectList(),
			'flatlist' => true,
			'default' => $this->mLimit
		];

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

		HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'get' )
			->setId( 'mw-listfiles-form' )
			->setTitle( $this->getTitle() )
			->setSubmitTextMsg( 'listfiles-pager-submit' )
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

	public function getPagingQueries() {
		$queries = parent::getPagingQueries();
		if ( $this->mUserName !== null ) {
			# Append the username to the query string
			foreach ( $queries as &$query ) {
				if ( $query !== false ) {
					$query['user'] = $this->mUserName;
				}
			}
		}

		return $queries;
	}

	public function getDefaultQuery() {
		$queries = parent::getDefaultQuery();
		if ( !isset( $queries['user'] ) && $this->mUserName !== null ) {
			$queries['user'] = $this->mUserName;
		}

		return $queries;
	}

	public function getTitle() {
		return SpecialPage::getTitleFor( 'Listfiles' );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( ImageListPager::class, 'ImageListPager' );
