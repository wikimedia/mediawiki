<?php
/**
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
 * @ingroup Change tagging
 */

/**
 * Allows to manage change tags, checking permissions for doing so
 * @since 1.28
 */
class ChangeTagsManager {

	/**
	 * @var ChangeTagsContext
	 */
	protected $context;

	/**
	 * @var User
	 */
	protected $performer;

	/**
	 * @var bool
	 */
	protected $ignoreWarnings;

	/**
	 * @param ChangeTagsContext $context
	 * @param User $performer
	 * @param bool $ignoreWarnings
	 * @since 1.28
	 */
	public function __construct( ChangeTagsContext $context, User $performer,
		$ignoreWarnings = false ) {
		$this->context = $context;
		$this->performer = $performer;
		$this->ignoreWarnings = $ignoreWarnings;
	}

	/**
	 * Activates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canActivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function activateTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsContext::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this->context );

		// are we allowed to do this?
		$result = $changeTag->canActivate( $this->performer );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			[ 'vt_tag' ],
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// clear the memcache of stored tags
		ChangeTagsContext::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'activate', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Deactivates a tag, checking whether it is allowed first, and adding a log
	 * entry afterwards.
	 *
	 * Includes a call to ChangeTag::canDeactivate(), so your code doesn't need
	 * to do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function deactivateTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsContext::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this->context );

		// are we allowed to do this?
		$result = $changeTag->canDeactivate( $this->performer );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'valid_tag', [ 'vt_tag' => $tag ], __METHOD__ );

		// clear the memcache of stored tags
		ChangeTagsContext::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'deactivate', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Creates a tag by adding a row to the `valid_tag` table.
	 *
	 * Includes a call to ChangeTag::canCreate(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function createTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsContext::purgeRegisteredTagsCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this->context );

		// are we allowed to do this?
		$result = $changeTag->canCreate( $this->performer );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'valid_tag',
			[ 'vt_tag' ],
			[ 'vt_tag' => $tag ],
			__METHOD__ );

		// purge stored tags cache
		ChangeTagsContext::purgeStoredTagsCache();

		// log it
		$logId = $this->logTagManagementAction( 'create', $tag, $reason );
		return Status::newGood( $logId );
	}

	/**
	 * Deletes a tag, checking whether it is allowed first, and adding a log entry
	 * afterwards.
	 *
	 * Includes a call to ChangeTag::canDelete(), so your code doesn't need to
	 * do that.
	 *
	 * @param string $tag
	 * @param string $reason
	 * @return Status If successful, the Status contains the ID of the added log
	 * entry as its value
	 * @since 1.28
	 */
	public function deleteTagWithChecks( $tag, $reason ) {

		// purging cache for the sake of extensions that might not do it
		ChangeTagsContext::purgeRegisteredTagsCache();
		// purging stats cache to get the up to date hitcount
		ChangeTagsContext::purgeTagUsageCache();

		// get change tag object
		$changeTag = new ChangeTag( $tag, $this->context );
		$hitcount = $changeTag->getHitcount();

		// are we allowed to do this?
		$result = $changeTag->canDelete( $this->performer );
		if ( $this->ignoreWarnings ? !$result->isOK() : !$result->isGood() ) {
			$result->value = null;
			return $result;
		}

		// do it!
		$deleteResult = ChangeTagsUpdater::deleteTagEverywhere( $tag );
		if ( !$deleteResult->isOK() ) {
			return $deleteResult;
		}

		// log it
		$logId = $this->logTagManagementAction( 'delete', $tag, $reason, $hitcount );
		$deleteResult->value = $logId;
		return $deleteResult;
	}

	/**
	 * Writes a tag action into the tag management log.
	 *
	 * @param string $action
	 * @param string $tag
	 * @param string $reason
	 * @param int $tagCount For deletion only, how many usages the tag had before
	 * it was deleted.
	 * @return int ID of the inserted log entry
	 * @since 1.25
	 */
	protected function logTagManagementAction( $action, $tag, $reason, $tagCount = null ) {

		$dbw = wfGetDB( DB_MASTER );

		$logEntry = new ManualLogEntry( 'managetags', $action );
		$logEntry->setPerformer( $this->performer );
		// target page is not relevant, but it has to be set, so we just put in
		// the title of Special:Tags
		$logEntry->setTarget( Title::newFromText( 'Special:Tags' ) );
		$logEntry->setComment( $reason );

		$params = [ '4::tag' => $tag ];
		if ( !is_null( $tagCount ) ) {
			$params['5:number:count'] = $tagCount;
		}
		$logEntry->setParameters( $params );
		$logEntry->setRelations( [ 'Tag' => $tag ] );

		$logId = $logEntry->insert( $dbw );
		$logEntry->publish( $logId );
		return $logId;
	}
}
