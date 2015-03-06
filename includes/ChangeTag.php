<?php
/**
 * Class for a change tag object.
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
 */

class ChangeTag {

	/**
	 * Name of the tag
	 */
	protected $name;

	/**
	 * Properties retrieved from change_tag table
	 */
	protected $hitcount;
	protected $isApplied;
	protected $isBig;
	// Function enabling retrieval
	protected $getStats;

	/**
	 * Properties retrieved from the source of the tag
	 */
	protected $isStored;
	protected $extensionDefined;
	protected $userDefined;
	protected $params;
	// Function enabling retrieval
	protected $getParams;

	/**
	 * Extra properties (requiring both stats and params)
	 */
	protected $exists;
	protected $isActive;
	protected $canActivate;
	protected $canDeactivate;
	protected $canDelete;

	/**
	 * @param string $tag tag's name
	 * @since 1.26
	 */
	function __construct( $tag ) {
		$this->name = $tag;
	}

	/**
	 * Gets hitcount for the tag instance, and some basic info
	 *
	 * @param array $tagStats tag usage statistics mapping each tag to its hitcount
	 * @since 1.26
	 */
	public function getStats( $tagStats ) {
		$this->isApplied = isset( $tagStats[$this->name()] );
		$this->hitcount = $this->isApplied ? $tagStats[$this->name()] : 0;
		$this->isBig = ( $this->hitcount > self::MAX_DELETE_USES );
	}

	/**
	 * Gets params for the tag instance, by checking its source
	 *
	 * @param array $storedTags tags stored in the change_tag table of the database
	 * @param array $registeredTags tags defined by extensions mapped to tag params
	 * @since 1.26
	 */
	public function getParams( $storedTags, $registeredTags ) {
		$this->isStored = isset( $storedTags[$this->name()] );
		$this->extensionDefined = isset( $registeredTags[$this->name()] );
		$this->userDefined = $this->isStored && !$this->extensionDefined;

		if ( $this->extensionDefined ) {
			$this->params = $registeredTags[$this->name()];
		} else {
			$this->params = array();
		}
	}

	/**
	 * Retrieves 'active' status for tags
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function isActive() {
		if ( $this->extensionDefined ) {
			// is the tag in active use by the extension ?
			return isset( $this->params['active'] ) &&
				$this->params['active'];
		} elseif ( $this->userDefined ) {
			// is the tag allowed to be applied by users and bots ?
			return true;
		} else {
			// for undefined tags
			return false;
		}
	}

	/**
	 * Checks if a tag exists, either because it is applied
	 * to edits, or it is defined somewhere
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function exists() {
		return $this->isApplied || $this->extensionDefined || $this->userDefined;
	}

	/**
	 * Checks if a tag can be activated by admins
	 *
	 * This is a quick check, that assumes the tag exists, makes
	 * no user permission check, and does not purge the caches (so
	 * might be based on outdated information).
	 * For a full but expansive check, use ChangeTags::canActivateTag.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canActivate() {
		return !$this->extensionDefined && !$this->userDefined;
	}

	/**
	 * Checks if a tag can be activated by admins
	 *
	 * This is a quick check, that assumes the tag exists, makes
	 * no user permission check, and does not purge the caches (so
	 * might be based on outdated information).
	 * For a full but expansive check, use ChangeTags::canDeactivateTag.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canDeactivate() {
		return $this->userDefined && $this->isApplied;
	}

	/**
	 * Checks if a tag can be deleted by admins
	 * Tags defined by extensions cannot be deleted unless
	 * the optional param 'canDelete' is set to true
	 *
	 * This is a quick check, that assumes the tag exists, makes
	 * no user permission check, and does not purge the caches (so
	 * might be based on outdated information).
	 * For a full but expansive check, use ChangeTags::canDeleteTag.
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canDelete() {
		if ( $this->isBig ) {
			return false;
		}

		if ( $this->extensionDefined ) {
			return isset( $this->params['canDelete'] ) &&
				$this->params['canDelete'];
		} else {
			return true;
		}
	}

	/**
	 * This creates a change tag object where context is completely rebuilt
	 * and provided in full, as needed for expansive permission checks
	 *
	 * @param string $tag tag's name
	 * @return change tag object
	 * @since 1.26
	 */
	public static function rebuildChangeTagObject( $tag ) {

		// some of the caches might be outdated due to extensions not purging them
		ChangeTags::purgeTagUsageCache( $tag );
		ChangeTags::purgeStoredTagsCache();
		ChangeTags::purgeRegisteredTagsCache();

		return new ChangeTag( $tag );
		$changeTag->getStats( ChangeTags::getTagUsageStatistics() );
		$changeTag->getParams( ChangeTags::getStoredTags(), ChangeTags::getRegisteredTags() );
	}
}
