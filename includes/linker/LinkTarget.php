<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Addshore
 */
namespace MediaWiki\Linker;

use Stringable;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;

/**
 * Represents the target of a wiki link.
 *
 * @see https://www.mediawiki.org/wiki/Manual:Modeling_pages
 *
 * @since 1.27
 */
interface LinkTarget extends Stringable, ParsoidLinkTarget {

	/**
	 * Get the namespace index.
	 *
	 * @since 1.27
	 * @return int Namespace index
	 */
	public function getNamespace(): int;

	/**
	 * Convenience function to check if the target is in a given namespace.
	 *
	 * @since 1.27
	 * @param int $ns
	 * @return bool
	 */
	public function inNamespace( int $ns ): bool;

	/**
	 * Get the link fragment in text form (i.e. the bit after the hash `#`).
	 *
	 * @since 1.27
	 * @return string link fragment
	 */
	public function getFragment(): string;

	/**
	 * Whether the link target has a fragment.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function hasFragment(): bool;

	/**
	 * Get the main part of the link target, in canonical database form.
	 *
	 * The main part is the link target without namespace prefix or hash fragment.
	 * The database form means that spaces become underscores, this is also
	 * used for URLs.
	 *
	 * @since 1.27
	 * @return string
	 */
	public function getDBkey(): string;

	/**
	 * Get the main part of the link target, in text form.
	 *
	 * The main part is the link target without namespace prefix or hash fragment.
	 * The text form is used for display purposes.
	 *
	 * This is computed from the DB key by replacing any underscores with spaces.
	 *
	 * @note To get a title string that includes the namespace and/or fragment,
	 *       use a TitleFormatter.
	 *
	 * @since 1.27
	 * @return string
	 */
	public function getText(): string;

	/**
	 * Create a new LinkTarget with a different fragment on the same page.
	 *
	 * It is expected that the same type of object will be returned, but the
	 * only requirement is that it is a LinkTarget.
	 *
	 * @since 1.27
	 * @param string $fragment The fragment override, or "" to remove it.
	 * @return LinkTarget
	 */
	public function createFragmentTarget( string $fragment );

	/**
	 * Whether this LinkTarget has an interwiki component.
	 *
	 * @since 1.27
	 * @return bool
	 */
	public function isExternal(): bool;

	/**
	 * The interwiki component of this LinkTarget.
	 *
	 * @since 1.27
	 * @return string
	 */
	public function getInterwiki(): string;

	/**
	 * Check whether the given LinkTarget refers to the same target as this LinkTarget.
	 *
	 * Two link targets are considered the same if they have the same interwiki prefix,
	 * are in the same namespace, have the same main part, and the same fragment.
	 *
	 * @since 1.36
	 * @param ParsoidLinkTarget $other
	 * @return bool
	 */
	public function isSameLinkAs( ParsoidLinkTarget $other ): bool;

	/**
	 * Return an informative human-readable representation of the link target,
	 * for use in logging and debugging.
	 *
	 * @return string
	 */
	public function __toString(): string;

}
