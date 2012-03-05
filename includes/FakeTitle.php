<?php

/**
 * Fake title class that triggers an error if any members are called
 */
class FakeTitle extends Title {
	function error() { throw new MWException( "Attempt to call member function of FakeTitle\n" ); }

	// PHP 5.1 method overload
	function __call( $name, $args ) { $this->error(); }

	// PHP <5.1 compatibility
	function isLocal() { $this->error(); }
	function isTrans() { $this->error(); }
	function getText() { $this->error(); }
	function getPartialURL() { $this->error(); }
	function getDBkey() { $this->error(); }
	function getNamespace() { $this->error(); }
	function getNsText() { $this->error(); }
	function getUserCaseDBKey() { $this->error(); }
	function getSubjectNsText() { $this->error(); }
	function getTalkNsText() { $this->error(); }
	function canTalk() { $this->error(); }
	function getInterwiki() { $this->error(); }
	function getFragment() { $this->error(); }
	function getFragmentForURL() { $this->error(); }
	function getDefaultNamespace() { $this->error(); }
	function getIndexTitle() { $this->error(); }
	function getPrefixedDBkey() { $this->error(); }
	function getPrefixedText() { $this->error(); }
	function getFullText() { $this->error(); }
	function getBaseText() { $this->error(); }
	function getSubpageText() { $this->error(); }
	function getSubpageUrlForm() { $this->error(); }
	function getPrefixedURL() { $this->error(); }
	function getFullURL( $query = '', $variant = false ) {$this->error(); }
	function getLocalURL( $query = '', $variant = false ) { $this->error(); }
	function getLinkUrl( $query = array(), $variant = false ) { $this->error(); }
	function escapeLocalURL( $query = '' ) { $this->error(); }
	function escapeFullURL( $query = '' ) { $this->error(); }
	function getInternalURL( $query = '', $variant = false ) { $this->error(); }
	function getEditURL() { $this->error(); }
	function getEscapedText() { $this->error(); }
	function isExternal() { $this->error(); }
	function isSemiProtected( $action = 'edit' ) { $this->error(); }
	function isProtected( $action = '' ) { $this->error(); }
	function isConversionTable() { $this->error(); }
	function userIsWatching() { $this->error(); }
	function quickUserCan( $action ) { $this->error(); }
	function isNamespaceProtected() { $this->error(); }
	function userCan( $action, $doExpensiveQueries = true ) { $this->error(); }
	function getUserPermissionsErrors( $action, $user, $doExpensiveQueries = true, $ignoreErrors = array() ) { $this->error(); }
	function updateTitleProtection( $create_perm, $reason, $expiry ) { $this->error(); }
	function deleteTitleProtection() { $this->error(); }
	function isMovable() { $this->error(); }
	function userCanRead() { $this->error(); }
	function isTalkPage() { $this->error(); }
	function isSubpage() { $this->error(); }
	function hasSubpages() { $this->error(); }
	function getSubpages( $limit = -1 ) { $this->error(); }
	function isCssJsSubpage() { $this->error(); }
	function isCssOrJsPage() { $this->error(); }
	function isValidCssJsSubpage() { $this->error(); }
	function getSkinFromCssJsSubpage() { $this->error(); }
	function isCssSubpage() { $this->error(); }
	function isJsSubpage() { $this->error(); }
	function userCanEditCssJsSubpage() { $this->error(); }
	function userCanEditCssSubpage() { $this->error(); }
	function userCanEditJsSubpage() { $this->error(); }
	function isCascadeProtected() { $this->error(); }
	function getCascadeProtectionSources( $get_pages = true ) { $this->error(); }
	function areRestrictionsCascading() { $this->error(); }
	function loadRestrictionsFromRows( $rows, $oldFashionedRestrictions = null ) { $this->error(); }
	function loadRestrictions( $res = null ) { $this->error(); }
	function getRestrictions( $action ) { $this->error(); }
	function getRestrictionExpiry( $action ) { $this->error(); }
	function isDeleted() { $this->error(); }
	function isDeletedQuick() { $this->error(); }
	function getArticleID( $flags = 0 ) { $this->error(); }
	function isRedirect( $flags = 0 ) { $this->error(); }
	function getLength( $flags = 0 ) { $this->error(); }
	function getLatestRevID( $flags = 0 ) { $this->error(); }
	function resetArticleID( $newid ) { $this->error(); }
	function invalidateCache() { $this->error(); }
	function getTalkPage() { $this->error(); }
	function setFragment( $fragment ) { $this->error(); }
	function getSubjectPage() { $this->error(); }
	function getLinksTo( $options = array(), $table = 'pagelinks', $prefix = 'pl' ) { $this->error(); }
	function getTemplateLinksTo( $options = array() ) { $this->error(); }
	function getBrokenLinksFrom() { $this->error(); }
	function getSquidURLs() { $this->error(); }
	function purgeSquid() { $this->error(); }
	function moveNoAuth( &$nt ) { $this->error(); }
	function isValidMoveOperation( &$nt, $auth = true, $reason = '' ) { $this->error(); }
	function moveTo( &$nt, $auth = true, $reason = '', $createRedirect = true ) { $this->error(); }
	function moveOverExistingRedirect( &$nt, $reason = '', $createRedirect = true ) { $this->error(); }
	function moveToNewTitle( &$nt, $reason = '', $createRedirect = true ) { $this->error(); }
	function moveSubpages( $nt, $auth = true, $reason = '', $createRedirect = true ) { $this->error(); }
	function isSingleRevRedirect() { $this->error(); }
	function isValidMoveTarget( $nt ) { $this->error(); }
	function isWatchable() { $this->error(); }
	function getParentCategories() { $this->error(); }
	function getParentCategoryTree( $children = array() ) { $this->error(); }
	function pageCond() { $this->error(); }
	function getPreviousRevisionID( $revId, $flags=0 ) { $this->error(); }
	function getNextRevisionID( $revId, $flags=0 ) { $this->error(); }
	function getFirstRevision( $flags=0 ) { $this->error(); }
	function isNewPage() { $this->error(); }
	function getEarliestRevTime() { $this->error(); }
	function countRevisionsBetween( $old, $new ) { $this->error(); }
	function equals( Title $title ) { $this->error(); }
	function exists() { $this->error(); }
	function isAlwaysKnown() { $this->error(); }
	function isKnown() { $this->error(); }
	function canExist() { $this->error(); }
	function touchLinks() { $this->error(); }
	function getTouched( $db = null ) { $this->error(); }
	function getNotificationTimestamp( $user = null ) { $this->error(); }
	function trackbackURL() { $this->error(); }
	function trackbackRDF() { $this->error(); }
	function getNamespaceKey( $prepend = 'nstab-' ) { $this->error(); }
	function isSpecialPage() { $this->error(); }
	function isSpecial( $name ) { $this->error(); }
	function fixSpecialName() { $this->error(); }
	function isContentPage() { $this->error(); }
	function getRedirectsHere( $ns = null ) { $this->error(); }
	function isValidRedirectTarget() { $this->error(); }
	function getBacklinkCache() { $this->error(); }
	function canUseNoindex() { $this->error(); }
	function getRestrictionTypes() { $this->error(); }
}
