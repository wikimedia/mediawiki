<?php
/**
 * This file contains a class for working with message groups.
 *
 * @file
 * @author Niklas Laxström
 * @author Siebrand Mazeland
 * @copyright Copyright © 2008-2013, Niklas Laxström, Siebrand Mazeland
 * @license GPL-2.0+
 */

/**
 * Factory class for accessing message groups individually by id or
 * all of them as an list.
 * @todo Clean up the mixed static/member method interface.
 */
class MessageGroups {
	/**
	 * Immediately update the cache.
	 *
	 * @since 2015.04
	 */
	public function recache() {
	}

	/**
	 * Manually reset group cache.
	 *
	 * Use when automatic dependency tracking fails.
	 */
	public static function clearCache() {
	}

	/**
	 * Returns a cacher object.
	 *
	 * @return BagOStuff
	 */
	protected function getCache() {
	}

	/**
	 * Override cache, for example during tests.
	 *
	 * @param BagOStuff|null $cache
	 */
	public function setCache( BagOStuff $cache = null ) {
	}

	/**
	 * Safely merges first array to second array, throwing warning on duplicates and removing
	 * duplicates from the first array.
	 * @param array $additions Things to append
	 * @param array $to Where to append
	 */
	protected static function appendAutoloader( array &$additions, array &$to ) {
	}

	/**
	 * This constructs the list of all groups from multiple different
	 * sources. When possible, a cache dependency is created to automatically
	 * recreate the cache when configuration changes.
     *
     * @return array
	 */
	protected function loadGroupDefinitions() {
	}

	/// Hook: TranslatePostInitGroups
	public static function getTranslatablePages( array &$groups, array &$deps, array &$autoload ) {
	}

	/// Hook: TranslatePostInitGroups
	public static function getConfiguredGroups( array &$groups, array &$deps, array &$autoload ) {
	}

	/// Hook: TranslatePostInitGroups
	public static function getWorkflowGroups( array &$groups, array &$deps, array &$autoload ) {
	}

	/// Hook: TranslatePostInitGroups
	public static function getAggregateGroups( array &$groups, array &$deps, array &$autoload ) {
	}

	/// Hook: TranslatePostInitGroups
	public static function getCCGroups( array &$groups, array &$deps, array &$autoload ) {
	}

	/**
	 * Fetch a message group by id.
	 *
	 * @param string $id Message group id.
	 * @return MessageGroup|null if it doesn't exist.
	 */
	public static function getGroup( $id ) {
	}

	/**
	 * Fixes the id and resolves aliases.
	 *
	 * @param string $id
	 * @return string
	 * @since 2016.01
	 */
	public static function normalizeId( $id ) {
	}

	/**
	 * @param string $id
	 * @return bool
	 */
	public static function exists( $id ) {
	}

	/**
	 * Check if a particular aggregate group label exists
	 * @param string $name
	 * @return bool
	 */
	public static function labelExists( $name ) {
	}

	/**
	 * Get all enabled message groups.
	 * @return array ( string => MessageGroup )
	 */
	public static function getAllGroups() {
	}

	/**
	 * We want to de-emphasize time sensitive groups like news for 2009.
	 * They can still exist in the system, but should not appear in front
	 * of translators looking to do some useful work.
	 *
	 * @param MessageGroup|string $group Message group ID
	 * @return string Message group priority
	 * @since 2011-12-12
	 */
	public static function getPriority( $group ) {
	}

	/**
	 * Sets the message group priority.
	 * @see MessageGroups::getPriority
	 *
	 * @param MessageGroup|string $group Message group
	 * @param string $priority Priority (empty string to unset)
	 * @since 2013-03-01
	 */
	public static function setPriority( $group, $priority = '' ) {
	}

	/// @since 2011-12-28
	public static function isDynamic( MessageGroup $group ) {
	}

	/**
	 * Returns a list of message groups that share (certain) messages
	 * with this group.
	 * @since 2011-12-25; renamed in 2012-12-10 from getParentGroups.
	 * @param MessageGroup $group
	 * @return string[]
	 */
	public static function getSharedGroups( MessageGroup $group ) {
	}

	/**
	 * Returns a list of parent message groups. If message group exists
	 * in multiple places in the tree, multiple lists are returned.
	 * @since 2012-12-10
	 * @param MessageGroup $targetGroup
	 * @return array[]
	 */
	public static function getParentGroups( MessageGroup $targetGroup ) {
	}

	/**
	 * Constructor function.
	 * @return MessageGroups
	 */
	public static function singleton() {
	}

	/**
	 * Get all enabled non-dynamic message groups.
	 *
	 * @return array
	 */
	public function getGroups() {
	}

	/**
	 * Get message groups for corresponding message group ids.
	 *
	 * @param string[] $ids Group IDs
	 * @param bool $skipMeta Skip aggregate message groups
	 * @return MessageGroup[]
	 * @since 2012-02-13
	 */
	public static function getGroupsById( array $ids, $skipMeta = false ) {
	}

	/**
	 * If the list of message group ids contains wildcards, this function will match
	 * them against the list of all supported message groups and return matched
	 * message group ids.
	 * @param string[]|string $ids
	 * @return string[]
	 * @since 2012-02-13
	 */
	public static function expandWildcards( $ids ) {
	}

	/**
	 * Contents on these groups changes on a whim.
	 * @since 2011-12-28
	 */
	public static function getDynamicGroups() {
	}

	/**
	 * Get only groups of specific type (class).
	 * @param string $type Class name of wanted type
	 * @return MessageGroupBase[]
	 * @since 2012-04-30
	 */
	public static function getGroupsByType( $type ) {
	}

	/**
	 * Returns a tree of message groups. First group in each subgroup is
	 * the aggregate group. Groups can be nested infinitely, though in practice
	 * other code might not handle more than two (or even one) nesting levels.
	 * One group can exist multiple times in differents parts of the tree.
	 * In other words: [Group1, Group2, [AggGroup, Group3, Group4]]
	 *
	 * @throws MWException If cyclic structure is detected.
	 * @return array
	 */
	public static function getGroupStructure() {
	}

	/// See getGroupStructure, just collects ids into array
	public static function collectGroupIds( MessageGroup $value, $key, $used ) {
	}

	/// Sorts groups by label value
	public static function groupLabelSort( $a, $b ) {
	}

	/**
	 * Like getGroupStructure but start from one root which must be an
	 * AggregateMessageGroup.
	 *
	 * @param AggregateMessageGroup $parent
	 * @throws MWException
	 * @return array
	 * @since Public since 2012-11-29
	 */
	public static function subGroups( AggregateMessageGroup $parent ) {
	}

	/**
	 * Checks whether all the message groups have the same source language.
	 * @param array $groups A list of message groups objects.
	 * @return string Language code if the languages are the same, empty string otherwise.
	 * @since 2013.09
	 */
	public static function haveSingleSourceLanguage( array $groups ) {
	}

	/**
	 * Get all the aggregate messages groups defined in translate_metadata table.
	 *
	 * @return array
	 */
	protected static function loadAggregateGroups() {
	}

	/**
	 * Filters out messages that should not be translated under normal
	 * conditions.
	 *
	 * @param MessageHandle $handle Handle for the translation target.
	 * @return bool
	 * @since 2013.10
	 */
	public static function isTranslatableMessage( MessageHandle $handle ) {
	}
}
