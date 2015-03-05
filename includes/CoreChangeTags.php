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
 */

/**
 * Implementation of some basic change tags.
 *
 * The tags in here are very simple and frequently requested change tags AbuseFilter extension should be 
 *
 * This is very much an internal class; functions in here should not be called
 * by extensions.
 * @since 1.25
 */
class CoreChangeTags {
	/**
	 * Lists the change tags that may be automatically applied by MediaWiki core.
	 *
	 * @return array
	 */
	public static function listTags() {
		return array(
			'mw-redirected',
			'mw-redirect-removed',
		);
	}

	/**
	 * Applies core change tags to the given revision/Recent Changes entry.
	 *
	 * @param Revision $revision The revision to apply tags to
	 * @param RecentChange|null $rc The recent change entry to apply tags to,
	 * or null if not applicable
	 * @param Content|null $oldContent The content of the revision before changes
	 * were made, or null if the page is newly created (has no previous revision)
	 * @param Content $content The content of the revision as it is now
	 */
	public static function addToRevision( Revision $revision, $rc, $oldContent,
		Content $content ) {

		$tags = array();

		// Redirection tags, only for pre-existing pages
		if ( $oldContent !== null ) {
			$oldIsRedirect = $oldContent->isRedirect();
			$newIsRedirect = $content->isRedirect();
			if ( $oldIsRedirect && !$newIsRedirect ) {
				$tags[] = 'mw-redirect-removed';
			} elseif ( !$oldIsRedirect && $newIsRedirect ) {
				$tags[] = 'mw-redirected';
			}
		}

		if ( $tags ) {
			$rc_id = $rc ? $rc->mAttribs['rc_id'] : null;
			ChangeTags::addTags( $tags, $rc_id, $revision->getId() );
		}
	}
}
