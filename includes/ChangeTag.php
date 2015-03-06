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
	 * @var string $name Internal name of the tag
	 */
	protected $name;

	/**
	 * @var int $hitcount Hitcount of the tag, obtained from change_tag table
	 */
	protected $hitcount = 0;

	/**
	 * @var bool $isStored Whether the tag is stored in the valid_tag table
	 */
	protected $isStored = false;

	/**
	 * @var bool $extensionDefined Whether the tag is registered by an extension
	 */
	protected $extensionDefined = false;

	/**
	 * @var bool $userDefined Whether the tag is user defined
	 */
	protected $userDefined = false;

	/**
	 * @var array $params Params for the tags, as defined by its source
	 */
	protected $params = null;

	/**
	 * @var bool $exists Whether the tag has been applied to edits or is defined
	 */
	protected $exists = null;

	/**
	 * @param string $tag tag's name
	 * @since 1.26
	 */
	function __construct( $tag ) {
		$this->name = $tag;
	}

	/**
	 * Set hitcount for the current instance, by checking tag stats.
	 *
	 * @param array $tagStats tag usage statistics mapping each tag to its hitcount
	 * @since 1.26
	 */
	public function getHitcount( $tagStats ) {
		$this->hitcount = $tagStats[$this->name];
	}

	/**
	 * Set params for the current instance, by checking potential sources.
	 *
	 * @param array $storedTags tags stored in the change_tag table of the database
	 * @param array $registeredTags tags defined by extensions mapped to tag params
	 * @since 1.26
	 */
	public function getSourceParams( $storedTags, $registeredTags ) {
		$this->isStored = isset( $storedTags[$this->name] );
		$this->extensionDefined = isset( $registeredTags[$this->name] );
		$this->userDefined = $this->isStored && !$this->extensionDefined;

		if ( $this->extensionDefined ) {
			$this->params =& $registeredTags[$this->name];
		}
	}

	/**
	 * This creates a change tag object where context is completely rebuilt
	 * and provided in full, as needed for expansive permission checks
	 * If $tag is already a tag instance, this does nothing.
	 *
	 * @param string $tag tag's name or changeTag instance
	 * @return change tag object
	 * @since 1.26
	 */
	public static function getChangeTagObject( $tag ) {
		if ( $tag instanceof ChangeTag ) {
			return $tag;
		}

		// some of the caches might be outdated due to extensions not purging them
		ChangeTags::purgeTagUsageCache( $tag );
		ChangeTags::purgeStoredTagsCache();
		ChangeTags::purgeRegisteredTagsCache();

		$changeTag = new ChangeTag( $tag );

		// retrieve hitcount
		$changeTag->getHitcount( ChangeTags::getTagUsageStatistics() );

		// retrieve params from source
		$changeTag->getSourceParams( ChangeTags::getStoredTags(), ChangeTags::getRegisteredTags() );

		// Checks if a tag exists, either because it is applied to edits, or defined somewhere
		$changeTag->exists = ( $changeTag->hitcount > 0 ) ||
			$changeTag->extensionDefined || $changeTag->userDefined;

		return $changeTag;
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
			// user defined tags are assumed to be active
			return true;
		} else {
			// for undefined tags
			return false;
		}
	}

	/**
	 * Checks if a tag can be activated by admins
	 *
	 * This is a quick check, that makes no user permission check, and does
	 * not purge the caches (so might be based on outdated information).
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canActivate() {
		return ChangeTags::canActivateTag( $this, null )->isOK();
	}

	/**
	 * Checks if a tag can be activated by admins
	 *
	 * This is a quick check, that makes no user permission check, and does
	 * not purge the caches (so might be based on outdated information).
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canDeactivate() {
		return ChangeTags::canDeactivateTag( $this, null )->isOK();
	}

	/**
	 * Checks if a tag can be deleted by admins
	 * Tags defined by extensions cannot be deleted unless
	 * the optional param 'canDelete' is set to true
	 *
	 * This is a quick check, that makes no user permission check, and does
	 * not purge the caches (so might be based on outdated information).
	 *
	 * @return bool
	 * @since 1.26
	 */
	public function canDelete() {
		return ChangeTags::canDeleteTag( $this, null )->isOK();
	}
}
