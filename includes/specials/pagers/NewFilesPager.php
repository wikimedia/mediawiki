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
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

class NewFilesPager extends RangeChronologicalPager {

	/**
	 * @var ImageGalleryBase
	 */
	protected $gallery;

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @param IContextSource $context
	 * @param FormOptions $opts
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( IContextSource $context, FormOptions $opts,
		LinkRenderer $linkRenderer
	) {
		parent::__construct( $context, $linkRenderer );

		$this->opts = $opts;
		$this->setLimit( $opts->getValue( 'limit' ) );

		$startTimestamp = '';
		$endTimestamp = '';
		if ( $opts->getValue( 'start' ) ) {
			$startTimestamp = $opts->getValue( 'start' ) . ' 00:00:00';
		}
		if ( $opts->getValue( 'end' ) ) {
			$endTimestamp = $opts->getValue( 'end' ) . ' 23:59:59';
		}
		$this->getDateRangeCond( $startTimestamp, $endTimestamp );
	}

	function getQueryInfo() {
		$opts = $this->opts;
		$conds = [];
		$actorQuery = ActorMigration::newMigration()->getJoin( 'img_user' );
		$tables = [ 'image' ] + $actorQuery['tables'];
		$fields = [ 'img_name', 'img_timestamp' ] + $actorQuery['fields'];
		$options = [];
		$jconds = $actorQuery['joins'];

		$user = $opts->getValue( 'user' );
		if ( $user !== '' ) {
			$conds[] = ActorMigration::newMigration()
				->getWhere( wfGetDB( DB_REPLICA ), 'img_user', User::newFromName( $user, false ) )['conds'];
		}

		if ( !$opts->getValue( 'showbots' ) ) {
			$groupsWithBotPermission = MediaWikiServices::getInstance()
				->getPermissionManager()
				->getGroupsWithPermission( 'bot' );

			if ( count( $groupsWithBotPermission ) ) {
				$dbr = wfGetDB( DB_REPLICA );
				$tables[] = 'user_groups';
				$conds[] = 'ug_group IS NULL';
				$jconds['user_groups'] = [
					'LEFT JOIN',
					[
						'ug_group' => $groupsWithBotPermission,
						'ug_user = ' . $actorQuery['fields']['img_user'],
						'ug_expiry IS NULL OR ug_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() )
					]
				];
			}
		}

		if ( $opts->getValue( 'hidepatrolled' ) ) {
			$tables[] = 'recentchanges';
			$conds['rc_type'] = RC_LOG;
			$conds['rc_log_type'] = 'upload';
			$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
			$conds['rc_namespace'] = NS_FILE;

			$jconds['recentchanges'] = [
				'JOIN',
				[
					'rc_title = img_name',
					'rc_actor = ' . $actorQuery['fields']['img_actor'],
					'rc_timestamp = img_timestamp'
				]
			];
			// We're ordering by img_timestamp, so we have to make sure MariaDB queries `image` first.
			// It sometimes decides to query `recentchanges` first and filesort the result set later
			// to get the right ordering. T124205 / https://mariadb.atlassian.net/browse/MDEV-8880
			$options[] = 'STRAIGHT_JOIN';
		}

		if ( $opts->getValue( 'mediatype' ) ) {
			$conds['img_media_type'] = $opts->getValue( 'mediatype' );
		}

		$likeVal = $opts->getValue( 'like' );
		if ( !$this->getConfig()->get( 'MiserMode' ) && $likeVal !== '' ) {
			$dbr = wfGetDB( DB_REPLICA );
			$likeObj = Title::newFromText( $likeVal );
			if ( $likeObj instanceof Title ) {
				$like = $dbr->buildLike(
					$dbr->anyString(),
					strtolower( $likeObj->getDBkey() ),
					$dbr->anyString()
				);
				$conds[] = "LOWER(img_name) $like";
			}
		}

		$query = [
			'tables' => $tables,
			'fields' => $fields,
			'join_conds' => $jconds,
			'conds' => $conds,
			'options' => $options,
		];

		return $query;
	}

	function getIndexField() {
		return 'img_timestamp';
	}

	protected function getStartBody() {
		if ( !$this->gallery ) {
			// Note that null for mode is taken to mean use default.
			$mode = $this->getRequest()->getVal( 'gallerymode', null );
			try {
				$this->gallery = ImageGalleryBase::factory( $mode, $this->getContext() );
			} catch ( Exception $e ) {
				// User specified something invalid, fallback to default.
				$this->gallery = ImageGalleryBase::factory( false, $this->getContext() );
			}
		}

		return '';
	}

	protected function getEndBody() {
		return $this->gallery->toHTML();
	}

	function formatRow( $row ) {
		$name = $row->img_name;
		$user = User::newFromId( $row->img_user );

		$title = Title::makeTitle( NS_FILE, $name );
		$ul = $this->getLinkRenderer()->makeLink(
			$user->getUserPage(),
			$user->getName()
		);
		$time = $this->getLanguage()->userTimeAndDate( $row->img_timestamp, $this->getUser() );

		$this->gallery->add(
			$title,
			"$ul<br />\n<i>"
			. htmlspecialchars( $time )
			. "</i><br />\n"
		);
		return '';
	}
}
