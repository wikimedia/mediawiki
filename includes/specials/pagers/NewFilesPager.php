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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\FormOptions;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup Pager
 */
class NewFilesPager extends RangeChronologicalPager {

	/**
	 * @var ImageGalleryBase
	 */
	protected $gallery;

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/** @var GroupPermissionsLookup */
	private $groupPermissionsLookup;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/**
	 * @param IContextSource $context
	 * @param GroupPermissionsLookup $groupPermissionsLookup
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkRenderer $linkRenderer
	 * @param ILoadBalancer $loadBalancer
	 * @param FormOptions $opts
	 */
	public function __construct(
		IContextSource $context,
		GroupPermissionsLookup $groupPermissionsLookup,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		ILoadBalancer $loadBalancer,
		FormOptions $opts
	) {
		// Set database before parent constructor to avoid setting it there with wfGetDB
		$this->mDb = $loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );

		parent::__construct( $context, $linkRenderer );

		$this->opts = $opts;
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->linkBatchFactory = $linkBatchFactory;
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

	public function getQueryInfo() {
		$opts = $this->opts;
		$conds = [];
		$dbr = $this->getDatabase();
		$tables = [ 'image', 'actor' ];
		$fields = [ 'img_name', 'img_timestamp', 'actor_user', 'actor_name' ];
		$options = [];
		$jconds = [ 'actor' => [ 'JOIN', 'actor_id=img_actor' ] ];

		$user = $opts->getValue( 'user' );
		if ( $user !== '' ) {
			$conds['actor_name'] = $user;
		}

		if ( !$opts->getValue( 'showbots' ) ) {
			$groupsWithBotPermission = $this->groupPermissionsLookup->getGroupsWithPermission( 'bot' );

			if ( count( $groupsWithBotPermission ) ) {
				$tables[] = 'user_groups';
				$conds[] = 'ug_group IS NULL';
				$jconds['user_groups'] = [
					'LEFT JOIN',
					[
						'ug_group' => $groupsWithBotPermission,
						'ug_user = actor_user',
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
					'rc_actor = img_actor',
					'rc_timestamp = img_timestamp'
				]
			];
		}

		if ( $opts->getValue( 'mediatype' ) ) {
			$conds['img_media_type'] = $opts->getValue( 'mediatype' );
		}

		// We're ordering by img_timestamp, but MariaDB sometimes likes to query other tables first
		// and filesort the result set later.
		// See T124205 / https://mariadb.atlassian.net/browse/MDEV-8880, and T244533
		// Twist: This would cause issues if the user is set and we need to check user existence first
		if ( $user === '' ) {
			$options[] = 'STRAIGHT_JOIN';
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

	public function getIndexField() {
		return [ [ 'img_timestamp', 'img_name' ] ];
	}

	protected function getStartBody() {
		if ( !$this->gallery ) {
			// Note that null for mode is taken to mean use default.
			$mode = $this->getRequest()->getVal( 'gallerymode', null );
			try {
				$this->gallery = ImageGalleryBase::factory( $mode, $this->getContext() );
			} catch ( ImageGalleryClassNotFoundException $e ) {
				// User specified something invalid, fallback to default.
				$this->gallery = ImageGalleryBase::factory( false, $this->getContext() );
			}
		}

		return '';
	}

	protected function getEndBody() {
		return $this->gallery->toHTML();
	}

	protected function doBatchLookups() {
		$this->mResult->seek( 0 );
		$lb = $this->linkBatchFactory->newLinkBatch();
		foreach ( $this->mResult as $row ) {
			if ( $row->actor_user ) {
				$lb->add( NS_USER, $row->actor_name );
			}
		}
		$lb->execute();
	}

	public function formatRow( $row ) {
		$username = $row->actor_name;

		if ( ExternalUserNames::isExternal( $username ) ) {
			$ul = htmlspecialchars( $username );
		} else {
			$ul = $this->getLinkRenderer()->makeLink(
				new TitleValue( NS_USER, $username ),
				$username
			);
		}
		$time = $this->getLanguage()->userTimeAndDate( $row->img_timestamp, $this->getUser() );

		$this->gallery->add(
			Title::makeTitle( NS_FILE, $row->img_name ),
			"$ul<br />\n<i>"
				. htmlspecialchars( $time )
				. "</i><br />\n",
			'',
			'',
			[],
			ImageGalleryBase::LOADING_LAZY
		);

		return '';
	}
}
