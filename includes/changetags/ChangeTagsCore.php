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
 * Provides static methods to associate change tags defined in core to
 * an edit or action presenting certain identifiable characteristics
 * @since 1.27
 */
class ChangeTagsCore {

	/**
	 * Return applicable autotags for an edit updating a page, if any.
	 * Based in part on getAutoSummaries from ContentHandler
	 *
	 * To get only active core tags, ChangeTags::filterInactiveTags()
	 * must be applied to the result
	 *
	 * @since 1.27
	 *
	 * @param Content $oldContent The previous text of the page.
	 * @param Content $newContent The submitted text of the page.
	 * @param Title $pageTitle The title of the page whose content is being edited.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForEditUpdate( Content $oldContent, Content $newContent,
		Title $pageTitle ) {
		$config = RequestContext::getMain()->getConfig();
		if ( !$config->get( 'UseAutoTagging' ) ) {
			return [];
		}
		$tags = [];

		// content model change
		if ( $oldContent->getModel() !== $newContent->getModel() ) {
			$tags[] = 'mw-contentmodelchange';
		}

		// Redirect-related tags
		$oldTarget = $oldContent->getRedirectTarget();
		$newTarget = $newContent->getRedirectTarget();
		if ( is_object( $newTarget ) ) {
			if ( $newTarget->equals( $pageTitle ) ) {
				// page redirected to itself
				$tags[] = 'mw-redirect-self';
			} elseif ( !$newTarget->exists() ) {
				// redirected to non-existent page
				$tags[] = 'mw-redirect-nonexistent';
			}
			if ( !is_object( $oldTarget ) ) {
				// non-redirect page gets redirected
				$tags[] = 'mw-redirect-new';
			} elseif ( ( !$newTarget->equals( $oldTarget ) || $oldTarget->getFragment()
				!= $newTarget->getFragment() ) ) {
				// target of a redirect gets changed
				$tags[] = 'mw-redirect-changed';
			}
		} elseif ( is_object( $oldTarget ) ) { {
				// redirect transformed in non-redirect page
				$tags[] = 'mw-redirect-removed';
			}
		}

		// Tags not related to redirects
		if ( !empty( $oldContent ) && $oldContent->getSize() > 0 &&
			$newContent->getSize() == 0 ) {
			// edit blanked a non-blank page
			$tags[] = 'mw-edit-blank';
		} elseif ( !empty( $oldContent )
			&& $oldContent->getSize() > 10 * $newContent->getSize()
			&& $newContent->getSize() < 500 ) {
			// edit replaced the page content
			$tags[] = 'mw-edit-replace';
		}

		return self::filterInactiveTags( $tags, $config->get( 'CoreTags' ) );
	}

	/**
	 * Return applicable autotags for an edit creating a page, if any.
	 *
	 * To get only active core tags, ChangeTags::filterInactiveTags()
	 * must be applied to the result
	 *
	 * @since 1.27
	 *
	 * @param Content $newContent The submitted text of the page.
	 * @param Title $pageTitle The title of the page whose content is being edited.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForEditNew( Content $newContent, Title $pageTitle ) {
		$config = RequestContext::getMain()->getConfig();
		if ( !$config->get( 'UseAutoTagging' ) ) {
			return [];
		}
		$tags = [];

		if ( $newContent->getSize() == 0 ) {
			$tags[] = 'mw-newpage-blank';
		}

		return self::filterInactiveTags( $tags, $config->get( 'CoreTags' ) );
	}

	/**
	 * Return applicable autotags for the given move, if any.
	 *
	 * To get only active core tags, ChangeTags::filterInactiveTags()
	 * must be applied to the result
	 *
	 * @since 1.27
	 *
	 * @param Title $oldTitle The old title of the page.
	 * @param Title $newTitle The new title of the page.
	 * @param User $user The user performing the move.
	 *
	 * @return array An array of tags, or an empty array.
	 */
	public static function getAutotagsForMove( Title $oldTitle, Title $newTitle, User $user ) {
		$tags = [];
		$config = RequestContext::getMain()->getConfig();
		if ( !$config->get( 'UseAutoTagging' ) ) {
			return [];
		}

		$oldNamespace = $oldTitle->getNamespace();
		$newNamespace = $newTitle->getNamespace();
		if ( $oldNamespace != $newNamespace ) {
			# cross namespace move
			$tags[] = 'mw-move-crossnamespace';
			if ( $newNamespace == NS_MAIN ) {
				# move to mainspace
				$tags[] = 'mw-move-tomainspace';
			}
		} elseif ( $oldNamespace == NS_USER && $newNamespace == NS_USER &&
			$oldTitle->getDBkey() == $user->getTitleKey() &&
			strpos( $newTitle->getDBkey(), '/' ) == false ) {
			# user trying to rename
			$tags[] = 'mw-move-rename';
		}

		return self::filterInactiveTags( $tags, $config->get( 'CoreTags' ) );
	}

	/**
	 * Filter out inactive tags
	 *
	 * @param array $tags Tags to filter
	 * @param array $coreTags
	 * @return array An array of tags, or an empty array.
	 */
	private static function filterInactiveTags( $tags, $coreTags ) {
		foreach ( $tags as $key => $tag ) {
			if ( !$coreTags[$tag]['active'] ) {
				unset( $tags[$key] );
			}
		}
		return $tags;
	}
}
