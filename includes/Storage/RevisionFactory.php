<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 29.08.17
 * Time: 19:19
 */
namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;
use MWException;
use User;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for looking up page revisions.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
interface RevisionFactory {

	/**
	 * @since 1.31
	 * @deprecated since 1.31.
	 *
	 * @param object|array $row
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow_1_30( $row );

	/**
	 * @since 1.31
	 *
	 * @param object|array $row
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, $slots );

}