<?php
/**
 * Provide things related to namespaces.
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
use MediaWiki\MediaWikiServices;

/**
 * @deprecated since 1.34, use NamespaceInfo instead
 */
class MWNamespace {
	/**
	 * Can pages in the given namespace be moved?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 */
	public static function isMovable( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isMovable( $index );
	}

	/**
	 * Is the given namespace is a subject (non-talk) namespace?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 * @since 1.19
	 */
	public static function isSubject( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isSubject( $index );
	}

	/**
	 * Is the given namespace a talk namespace?
	 *
	 * @param int $index Namespace index
	 * @return bool
	 */
	public static function isTalk( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isTalk( $index );
	}

	/**
	 * Get the talk namespace index for a given namespace
	 *
	 * @param int $index Namespace index
	 * @return int
	 */
	public static function getTalk( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getTalk( $index );
	}

	/**
	 * Get the subject namespace index for a given namespace
	 * Special namespaces (NS_MEDIA, NS_SPECIAL) are always the subject.
	 *
	 * @param int $index Namespace index
	 * @return int
	 */
	public static function getSubject( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getSubject( $index );
	}

	/**
	 * Get the associated namespace.
	 * For talk namespaces, returns the subject (non-talk) namespace
	 * For subject (non-talk) namespaces, returns the talk namespace
	 *
	 * @param int $index Namespace index
	 * @return int|null If no associated namespace could be found
	 */
	public static function getAssociated( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getAssociated( $index );
	}

	/**
	 * Returns whether the specified namespace exists
	 *
	 * @param int $index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function exists( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->exists( $index );
	}

	/**
	 * Returns whether the specified namespaces are the same namespace
	 *
	 * @note It's possible that in the future we may start using something
	 * other than just namespace indexes. Under that circumstance making use
	 * of this function rather than directly doing comparison will make
	 * sure that code will not potentially break.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function equals( $ns1, $ns2 ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->equals( $ns1, $ns2 );
	}

	/**
	 * Returns whether the specified namespaces share the same subject.
	 * eg: NS_USER and NS_USER wil return true, as well
	 *     NS_USER and NS_USER_TALK will return true.
	 *
	 * @param int $ns1 The first namespace index
	 * @param int $ns2 The second namespace index
	 *
	 * @return bool
	 * @since 1.19
	 */
	public static function subjectEquals( $ns1, $ns2 ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			subjectEquals( $ns1, $ns2 );
	}

	/**
	 * Returns array of all defined namespaces with their canonical
	 * (English) names.
	 *
	 * @return array
	 * @since 1.17
	 */
	public static function getCanonicalNamespaces() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getCanonicalNamespaces();
	}

	/**
	 * Returns the canonical (English) name for a given index
	 *
	 * @param int $index Namespace index
	 * @return string|bool If no canonical definition.
	 */
	public static function getCanonicalName( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getCanonicalName( $index );
	}

	/**
	 * Returns the index for a given canonical name, or NULL
	 * The input *must* be converted to lower case first
	 *
	 * @param string $name Namespace name
	 * @return int
	 */
	public static function getCanonicalIndex( $name ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getCanonicalIndex( $name );
	}

	/**
	 * Returns an array of the namespaces (by integer id) that exist on the
	 * wiki. Used primarily by the api in help documentation.
	 * @return array
	 */
	public static function getValidNamespaces() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getValidNamespaces();
	}

	/**
	 * Does this namespace ever have a talk namespace?
	 *
	 * @since 1.30
	 *
	 * @param int $index Namespace ID
	 * @return bool True if this namespace either is or has a corresponding talk namespace.
	 */
	public static function hasTalkNamespace( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->hasTalkNamespace( $index );
	}

	/**
	 * Does this namespace contain content, for the purposes of calculating
	 * statistics, etc?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isContent( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isContent( $index );
	}

	/**
	 * Might pages in this namespace require the use of the Signature button on
	 * the edit toolbar?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function wantSignatures( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->wantSignatures( $index );
	}

	/**
	 * Can pages in a namespace be watched?
	 *
	 * @param int $index
	 * @return bool
	 */
	public static function isWatchable( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isWatchable( $index );
	}

	/**
	 * Does the namespace allow subpages?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function hasSubpages( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->hasSubpages( $index );
	}

	/**
	 * Get a list of all namespace indices which are considered to contain content
	 * @return array Array of namespace indices
	 */
	public static function getContentNamespaces() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getContentNamespaces();
	}

	/**
	 * List all namespace indices which are considered subject, aka not a talk
	 * or special namespace. See also MWNamespace::isSubject
	 *
	 * @return array Array of namespace indices
	 */
	public static function getSubjectNamespaces() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getSubjectNamespaces();
	}

	/**
	 * List all namespace indices which are considered talks, aka not a subject
	 * or special namespace. See also MWNamespace::isTalk
	 *
	 * @return array Array of namespace indices
	 */
	public static function getTalkNamespaces() {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->getTalkNamespaces();
	}

	/**
	 * Is the namespace first-letter capitalized?
	 *
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isCapitalized( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isCapitalized( $index );
	}

	/**
	 * Does the namespace (potentially) have different aliases for different
	 * genders. Not all languages make a distinction here.
	 *
	 * @since 1.18
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function hasGenderDistinction( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			hasGenderDistinction( $index );
	}

	/**
	 * It is not possible to use pages from this namespace as template?
	 *
	 * @since 1.20
	 * @param int $index Index to check
	 * @return bool
	 */
	public static function isNonincludable( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->isNonincludable( $index );
	}

	/**
	 * Get the default content model for a namespace
	 * This does not mean that all pages in that namespace have the model
	 *
	 * @note To determine the default model for a new page's main slot, or any slot in general,
	 * use SlotRoleHandler::getDefaultModel() together with SlotRoleRegistry::getRoleHandler().
	 *
	 * @since 1.21
	 * @param int $index Index to check
	 * @return null|string Default model name for the given namespace, if set
	 */
	public static function getNamespaceContentModel( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			getNamespaceContentModel( $index );
	}

	/**
	 * Determine which restriction levels it makes sense to use in a namespace,
	 * optionally filtered by a user's rights.
	 *
	 * @since 1.23
	 * @param int $index Index to check
	 * @param User|null $user User to check
	 * @return array
	 */
	public static function getRestrictionLevels( $index, User $user = null ) {
		return MediaWikiServices::getInstance()
			->getPermissionManager()
			->getNamespaceRestrictionLevels( $index, $user );
	}

	/**
	 * Returns the link type to be used for categories.
	 *
	 * This determines which section of a category page titles
	 * in the namespace will appear within.
	 *
	 * @since 1.32
	 * @param int $index Namespace index
	 * @return string One of 'subcat', 'file', 'page'
	 */
	public static function getCategoryLinkType( $index ) {
		return MediaWikiServices::getInstance()->getNamespaceInfo()->
			getCategoryLinkType( $index );
	}
}
