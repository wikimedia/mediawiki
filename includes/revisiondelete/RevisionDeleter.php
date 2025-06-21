<?php
/**
 * Revision/log/file deletion backend
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
 * @ingroup RevisionDelete
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;

/**
 * General controller for RevDel, used by both SpecialRevisiondelete and
 * ApiRevisionDelete.
 * @ingroup RevisionDelete
 */
class RevisionDeleter {
	/**
	 * List of known revdel types, with their corresponding ObjectFactory spec to
	 * create the relevant class. All specs need to include DBLoadBalancerFactory,
	 * which is used in the base RevDelList class
	 */
	private const ALLOWED_TYPES = [
		'revision' => [
			'class' => RevDelRevisionList::class,
			'services' => [
				'DBLoadBalancerFactory',
				'HookContainer',
				'HtmlCacheUpdater',
				'RevisionStore',
				'DomainEventDispatcher'
			],
		],
		'archive' => [
			'class' => RevDelArchiveList::class,
			'services' => [
				'DBLoadBalancerFactory',
				'HookContainer',
				'HtmlCacheUpdater',
				'RevisionStore',
				'DomainEventDispatcher'
			],
		],
		'oldimage' => [
			'class' => RevDelFileList::class,
			'services' => [
				'DBLoadBalancerFactory',
				'HtmlCacheUpdater',
				'RepoGroup',
			],
		],
		'filearchive' => [
			'class' => RevDelArchivedFileList::class,
			'services' => [
				'DBLoadBalancerFactory',
				'HtmlCacheUpdater',
				'RepoGroup',
			],
		],
		'logging' => [
			'class' => RevDelLogList::class,
			'services' => [
				'DBLoadBalancerFactory',
				'CommentStore',
				'LogFormatterFactory',
			],
		],
	];

	/** Type map to support old log entries */
	private const DEPRECATED_TYPE_MAP = [
		'oldid' => 'revision',
		'artimestamp' => 'archive',
		'oldimage' => 'oldimage',
		'fileid' => 'filearchive',
		'logid' => 'logging',
	];

	/**
	 * Lists the valid possible types for revision deletion.
	 *
	 * @since 1.22
	 * @return array
	 */
	public static function getTypes() {
		return array_keys( self::ALLOWED_TYPES );
	}

	/**
	 * Gets the canonical type name, if any.
	 *
	 * @since 1.22
	 * @param string $typeName
	 * @return string|null
	 */
	public static function getCanonicalTypeName( $typeName ) {
		if ( isset( self::DEPRECATED_TYPE_MAP[$typeName] ) ) {
			$typeName = self::DEPRECATED_TYPE_MAP[$typeName];
		}
		return isset( self::ALLOWED_TYPES[$typeName] ) ? $typeName : null;
	}

	/**
	 * Instantiate the appropriate list class for a given list of IDs.
	 *
	 * @since 1.22
	 * @param string $typeName RevDel type, see RevisionDeleter::getTypes()
	 * @param IContextSource $context
	 * @param PageIdentity $page
	 * @param array $ids
	 * @return RevDelList
	 */
	public static function createList( $typeName, IContextSource $context, PageIdentity $page, array $ids ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			throw new InvalidArgumentException( __METHOD__ . ": Unknown RevDel type '$typeName'" );
		}
		$spec = self::ALLOWED_TYPES[$typeName];
		$objectFactory = MediaWikiServices::getInstance()->getObjectFactory();

		// ObjectFactory::createObject accepts an array, not just a callable (phan bug)
		// @phan-suppress-next-line PhanTypeInvalidCallableArrayKey
		return $objectFactory->createObject(
			$spec,
			[
				'extraArgs' => [ $context, $page, $ids ],
				'assertClass' => RevDelList::class,
			]
		);
	}

	/**
	 * Checks for a change in the bitfield for a certain option and updates the
	 * provided array accordingly.
	 *
	 * @param string $desc Description to add to the array if the option was
	 * enabled / disabled.
	 * @param int $field The bitmask describing the single option.
	 * @param int $diff The xor of the old and new bitfields.
	 * @param int $new The new bitfield
	 * @param array &$arr The array to update.
	 */
	protected static function checkItem( $desc, $field, $diff, $new, &$arr ) {
		if ( $diff & $field ) {
			$arr[( $new & $field ) ? 0 : 1][] = $desc;
		}
	}

	/**
	 * Gets an array of message keys describing the changes made to the
	 * visibility of the revision.
	 *
	 * If the resulting array is $arr, then $arr[0] will contain an array of
	 * keys describing the items that were hidden, $arr[1] will contain
	 * an array of keys describing the items that were unhidden, and $arr[2]
	 * will contain an array with a single message key, which can be one of
	 * "revdelete-restricted", "revdelete-unrestricted" indicating (un)suppression
	 * or null to indicate nothing in particular.
	 * You can turn the keys in $arr[0] and $arr[1] into message keys by
	 * appending -hid and -unhid to the keys respectively.
	 *
	 * @param int $n The new bitfield.
	 * @param int $o The old bitfield.
	 * @return array An array as described above.
	 * @since 1.19 public
	 */
	public static function getChanges( $n, $o ) {
		$diff = $n ^ $o;
		$ret = [ 0 => [], 1 => [], 2 => [] ];
		// Build bitfield changes in language
		self::checkItem( 'revdelete-content',
			RevisionRecord::DELETED_TEXT, $diff, $n, $ret );
		self::checkItem( 'revdelete-summary',
			RevisionRecord::DELETED_COMMENT, $diff, $n, $ret );
		self::checkItem( 'revdelete-uname',
			RevisionRecord::DELETED_USER, $diff, $n, $ret );
		// Restriction application to sysops
		if ( $diff & RevisionRecord::DELETED_RESTRICTED ) {
			if ( $n & RevisionRecord::DELETED_RESTRICTED ) {
				$ret[2][] = 'revdelete-restricted';
			} else {
				$ret[2][] = 'revdelete-unrestricted';
			}
		}
		return $ret;
	}

	/** Get DB field name for URL param...
	 * Future code for other things may also track
	 * other types of revision-specific changes.
	 * @param string $typeName
	 * @return string|null One of log_id/rev_id/fa_id/ar_timestamp/oi_archive_name
	 */
	public static function getRelationType( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		$class = self::ALLOWED_TYPES[$typeName]['class'];
		return $class::getRelationType();
	}

	/**
	 * Get the user right required for the RevDel type
	 * @since 1.22
	 * @param string $typeName
	 * @return string|null User right
	 */
	public static function getRestriction( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		$class = self::ALLOWED_TYPES[$typeName]['class'];
		return $class::getRestriction();
	}

	/**
	 * Get the revision deletion constant for the RevDel type
	 * @since 1.22
	 * @param string $typeName
	 * @return int|null RevDel constant
	 */
	public static function getRevdelConstant( $typeName ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return null;
		}
		$class = self::ALLOWED_TYPES[$typeName]['class'];
		return $class::getRevdelConstant();
	}

	/**
	 * Suggest a target for the revision deletion
	 * @since 1.22
	 * @param string $typeName
	 * @param Title|null $target User-supplied target
	 * @param array $ids
	 * @return Title|null
	 */
	public static function suggestTarget( $typeName, $target, array $ids ) {
		$typeName = self::getCanonicalTypeName( $typeName );
		if ( !$typeName ) {
			return $target;
		}
		$class = self::ALLOWED_TYPES[$typeName]['class'];
		return $class::suggestTarget(
			$target,
			$ids
		);
	}

	/**
	 * Put together a rev_deleted bitfield
	 * @since 1.22
	 * @param array $bitPars associative array mapping bit masks to 0, 1 or -1.
	 *        A value of 0 unsets the bits in the mask, 1 will set the bits in
	 *        the mask, and any other value will retain the bits already present
	 *        in $oldfield.
	 * @param int $oldfield Current bitfield
	 *
	 * @internal
	 * @return int
	 */
	public static function extractBitfield( array $bitPars, $oldfield ) {
		// Build the actual new rev_deleted bitfield
		$newBits = $oldfield;
		foreach ( $bitPars as $const => $val ) {
			// $const is the XXX_DELETED const

			if ( $val == 1 ) {
				$newBits |= $const; // set the bit
			} elseif ( $val == 0 ) {
				$newBits &= ~$const; // unset the bit
			}
		}
		return $newBits;
	}
}
