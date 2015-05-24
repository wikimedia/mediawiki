<?php
/**
 * Recent changes tagging.
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

class ChangeTagsCore {

	/**
	 * Return applicable autotags for an edit updating a page, if any.
	 * Based on getAutoSummaries from ContentHandler
	 *
	 * @since 1.26
	 *
	 * @param Content $oldContent The previous text of the page.
	 * @param Content $newContent The submitted text of the page.
	 * @param Title $pageTitle The title of the page whose content is being edited.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForEditUpdate( Content $oldContent = null,
		Content $newContent = null, $pageTitle ) {
		$tags = array();

		// Redirect-related tags
		$oldTarget = !is_null( $oldContent ) ? $oldContent->getRedirectTarget() : null;
		$newTarget = !is_null( $newContent ) ? $newContent->getRedirectTarget() : null;
		if ( is_object( $newTarget ) ) {
			if ( $newTarget->equals( $pageTitle ) ) {
				// page redirected to itself !
				$tags[] = 'core-redirect-self';
			} elseif ( !$newTarget->exists() ) {
				// redirected to non-existent page !
				$tags[] = 'core-redirect-nonexistent';
			}
			if ( !is_object( $oldTarget ) ) {
				// non-redirect page gets redirected !
				$tags[] = 'core-redirect-new';
			} elseif ( ( !$newTarget->equals( $oldTarget ) || $oldTarget->getFragment()
				!= $newTarget->getFragment() ) ) {
				// target of a redirect gets changed !
				$tags[] = 'core-redirect-changed';
			}
		} elseif ( is_object( $oldTarget ) ) { {
				// redirect transformed in non-redirect page !
				$tags[] = 'core-redirect-removed';
			}
		}

		// Tags not related to redirects
		if ( !empty( $oldContent ) && $oldContent->getSize() > 0 &&
			$newContent->getSize() == 0 ) {
			// edit blanked a non-blank page !
			$tags[] = 'core-edit-blank';
		} elseif ( !empty( $oldContent )
			&& $oldContent->getSize() > 10 * $newContent->getSize()
			&& $newContent->getSize() < 500 ) {
			// edit replaced the page content !
			$tags[] = 'core-edit-replace';
		}

		return self::filterAutoTags( $tags );
	}

	/**
	 * Return applicable autotags for an edit creating a page, if any.
	 * Based on getAutoSummaries from ContentHandler
	 *
	 * @since 1.26
	 *
	 * @param Content $newContent The submitted text of the page.
	 * @param Title $pageTitle The title of the page whose content is being edited.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForEditNew( Content $newContent = null, $pageTitle ) {
		$tags = array();

		if ( $newContent->getSize() == 0 ) {
			$tags[] = 'core-newpage-blank';
		}

		return self::filterAutoTags( $tags );
	}

	/**
	 * Return applicable autotags for the given move, if any.
	 *
	 * @since 1.26
	 *
	 * @param Title $oldTitle The old title of the page.
	 * @param Title $newTitle The new title of the page.
	 * @param User $user The user performing the move.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForMove( $oldTitle, $newTitle, $user ) {
		$tags = array();

		$oldNamespace = $oldTitle->getNamespace();
		$newNamespace = $newTitle->getNamespace();
		if ( $oldNamespace != $newNamespace ) {
			# cross namespace move !
			$tags[] = 'core-move-crossnamespace';
			if ( $newNamespace == NS_MAIN ) {
				# move to mainspace !
				$tags[] = 'core-move-tomainspace';
			}
		} elseif ( $oldNamespace == NS_USER && $newNamespace == NS_USER &&
			$oldTitle->getDBkey() == $user->getTitleKey() &&
			strpos( $newTitle->getDBkey(), '/' ) == false ) {
			# user trying to rename !
			$tags[] = 'core-move-rename';
		}

		return self::filterAutoTags( $tags );
	}

	/**
	 * Filter out inactive core tags
	 *
	 * @since 1.26
	 *
	 * @param array $tags Tags to filter
	 * @return array An array of tags, or an empty array.
	 */
	public static function filterAutoTags( $tags ) {
		global $wgCoreTags;
		foreach ( $tags as $key => $tag ) {
			if ( isset( $wgCoreTags[$tag] ) && !$wgCoreTags[$tag]['active'] ) {
				unset( $tags[$key] );
			}
		}
		return $tags;
	}
}
