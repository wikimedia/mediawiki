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
class NewFilesPager extends ReverseChronologicalPager {

	/**
	 * @var ImageGallery
	 */
	protected $gallery;

	/**
	 * @var FormOptions
	 */
	protected $opts;

	/**
	 * @param IContextSource $context
	 * @param FormOptions $opts
	 */
	function __construct( IContextSource $context, FormOptions $opts ) {
		$this->opts = $opts;

		$this->setLimit( $opts->getValue( 'limit' ) );

		parent::__construct( $context );
	}

	function getQueryInfo() {
		$opts = $this->opts;
		$conds = $jconds = [];
		$tables = [ 'image' ];
		$fields = [ 'img_name', 'img_user', 'img_timestamp' ];
		$options = [];

		if ( !$opts->getValue( 'showbots' ) ) {
			$groupsWithBotPermission = User::getGroupsWithPermission( 'bot' );

			if ( count( $groupsWithBotPermission ) ) {
				$tables[] = 'user_groups';
				$conds[] = 'ug_group IS NULL';
				$jconds['user_groups'] = [
					'LEFT JOIN',
					[
						'ug_group' => $groupsWithBotPermission,
						'ug_user = img_user'
					]
				];
			}
		}

		if ( $opts->getValue( 'hidepatrolled' ) ) {
			$tables[] = 'recentchanges';
			$conds['rc_type'] = RC_LOG;
			$conds['rc_log_type'] = 'upload';
			$conds['rc_patrolled'] = 0;
			$conds['rc_namespace'] = NS_FILE;
			$jconds['recentchanges'] = [
				'INNER JOIN',
				[
					'rc_title = img_name',
					'rc_user = img_user',
					'rc_timestamp = img_timestamp'
				]
			];
			// We're ordering by img_timestamp, so we have to make sure MariaDB queries `image` first.
			// It sometimes decides to query `recentchanges` first and filesort the result set later
			// to get the right ordering. T124205 / https://mariadb.atlassian.net/browse/MDEV-8880
			$options[] = 'STRAIGHT_JOIN';
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

	function getStartBody() {
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

	function getEndBody() {
		return $this->gallery->toHTML();
	}

	function formatRow( $row ) {
		$name = $row->img_name;
		$user = User::newFromId( $row->img_user );

		$title = Title::makeTitle( NS_FILE, $name );
		$ul = Linker::link( $user->getUserPage(), $user->getName() );
		$time = $this->getLanguage()->userTimeAndDate( $row->img_timestamp, $this->getUser() );

		$this->gallery->add(
			$title,
			"$ul<br />\n<i>"
			. htmlspecialchars( $time )
			. "</i><br />\n"
		);
	}
}
