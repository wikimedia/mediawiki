<?php

namespace MediaWiki\Linker;

use Language;
use MediaWiki\Context\IContextSource;
use MediaWiki\Parser\Parser;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @since 1.18
 * @deprecated since 1.42
 */
class DummyLinker {

	public function __construct() {
		// Avoid deprecation warning when calling the 'ImageBeforeProduceHTML' hook.
		// Warnings will be emitted instead if any method is accessed by a hook handler.
		if ( wfGetCaller() !== Linker::class . '::makeImageLink' ) {
			wfDeprecated( __METHOD__, '1.42' );
		}
	}

	/**
	 * @deprecated since 1.42
	 */
	public function link(
		$target,
		$html = null,
		$customAttribs = [],
		$query = [],
		$options = []
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::link(
			$target,
			$html,
			$customAttribs,
			$query,
			$options
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function linkKnown(
		$target,
		$html = null,
		$customAttribs = [],
		$query = [],
		$options = [ 'known' ]
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::linkKnown(
			$target,
			$html,
			$customAttribs,
			$query,
			$options
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeSelfLinkObj(
		$nt,
		$html = '',
		$query = '',
		$trail = '',
		$prefix = ''
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeSelfLinkObj(
			$nt,
			$html,
			$query,
			$trail,
			$prefix
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function getInvalidTitleDescription(
		IContextSource $context,
		$namespace,
		$title
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::getInvalidTitleDescription(
			$context,
			$namespace,
			$title
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeExternalImage( $url, $alt = '' ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeExternalImage( $url, $alt );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeImageLink(
		Parser $parser,
		Title $title,
		$file,
		$frameParams = [],
		$handlerParams = [],
		$time = false,
		$query = "",
		$widthOption = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeImageLink(
			$parser,
			$title,
			$file,
			$frameParams,
			$handlerParams,
			$time,
			$query,
			$widthOption
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeThumbLinkObj(
		Title $title,
		$file,
		$label = '',
		$alt = '',
		$align = 'right',
		$params = [],
		$framed = false,
		$manualthumb = ""
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeThumbLinkObj(
			$title,
			$file,
			$label,
			$alt,
			$align,
			$params,
			$framed,
			$manualthumb
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeThumbLink2(
		Title $title,
		$file,
		$frameParams = [],
		$handlerParams = [],
		$time = false,
		$query = ""
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeThumbLink2(
			$title,
			$file,
			$frameParams,
			$handlerParams,
			$time,
			$query
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function processResponsiveImages( $file, $thumb, $hp ) {
		wfDeprecated( __METHOD__, '1.42' );
		Linker::processResponsiveImages(
			$file,
			$thumb,
			$hp
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeBrokenImageLinkObj(
		$title,
		$label = '',
		$query = '',
		$unused1 = '',
		$unused2 = '',
		$time = false
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeBrokenImageLinkObj(
			$title,
			$label,
			$query,
			$unused1,
			$unused2,
			$time
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeMediaLinkObj( $title, $html = '', $time = false ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeMediaLinkObj(
			$title,
			$html,
			$time
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeMediaLinkFile( Title $title, $file, $html = '' ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeMediaLinkFile(
			$title,
			$file,
			$html
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function specialLink( $name, $key = '' ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::specialLink( $name, $key );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeExternalLink(
		$url,
		$text,
		$escape = true,
		$linktype = '',
		$attribs = [],
		$title = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeExternalLink(
			$url,
			$text,
			$escape,
			$linktype,
			$attribs,
			$title
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function userLink( $userId, $userName, $altUserName = false, $attributes = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::userLink(
			$userId,
			$userName,
			$altUserName,
			$attributes
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function userToolLinks(
		$userId,
		$userText,
		$redContribsWhenNoEdits = false,
		$flags = 0,
		$edits = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::userToolLinks(
			$userId,
			$userText,
			$redContribsWhenNoEdits,
			$flags,
			$edits
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function userToolLinksRedContribs( $userId, $userText, $edits = null ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::userToolLinksRedContribs(
			$userId,
			$userText,
			$edits
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function userTalkLink( $userId, $userText ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::userTalkLink( $userId, $userText );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function blockLink( $userId, $userText ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::blockLink( $userId, $userText );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function emailLink( $userId, $userText ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::emailLink( $userId, $userText );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function revUserLink( RevisionRecord $revRecord, $isPublic = false ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::revUserLink( $revRecord, $isPublic );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function revUserTools( RevisionRecord $revRecord, $isPublic = false ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::revUserTools( $revRecord, $isPublic );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function formatComment(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::formatComment(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function formatLinksInComment(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::formatLinksInComment(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function normalizeSubpageLink( $contextTitle, $target, &$text ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::normalizeSubpageLink(
			$contextTitle,
			$target,
			$text
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function commentBlock(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::commentBlock(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function revComment( RevisionRecord $revRecord, $local = false, $isPublic = false ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::revComment( $revRecord, $local, $isPublic );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function formatRevisionSize( $size ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::formatRevisionSize( $size );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tocIndent() {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tocIndent();
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tocUnindent( $level ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tocUnindent( $level );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tocLine( $anchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tocLine(
			$anchor,
			$tocline,
			$tocnumber,
			$level,
			$sectionIndex
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tocLineEnd() {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tocLineEnd();
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tocList( $toc, Language $lang = null ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tocList( $toc, $lang );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function generateTOC( $tree, Language $lang = null ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::generateTOC( $tree, $lang );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function makeHeadline(
		$level,
		$attribs,
		$anchor,
		$html,
		$link,
		$legacyAnchor = false
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::makeHeadline(
			$level,
			$attribs,
			$anchor,
			$html,
			$link,
			$legacyAnchor
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function splitTrail( $trail ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::splitTrail( $trail );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function generateRollback(
		RevisionRecord $revRecord,
		IContextSource $context = null,
		$options = []
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::generateRollback(
			$revRecord,
			$context,
			$options
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function getRollbackEditCount( RevisionRecord $revRecord, $verify ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::getRollbackEditCount( $revRecord, $verify );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function buildRollbackLink(
		RevisionRecord $revRecord,
		IContextSource $context = null,
		$editCount = false
	) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::buildRollbackLink(
			$revRecord,
			$context,
			$editCount
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function formatHiddenCategories( $hiddencats ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::formatHiddenCategories( $hiddencats );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function titleAttrib( $name, $options = null, array $msgParams = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::titleAttrib(
			$name,
			$options,
			$msgParams
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function accesskey( $name ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::accesskey( $name );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function getRevDeleteLink( User $user, RevisionRecord $revRecord, Title $title ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::getRevDeleteLink(
			$user,
			$revRecord,
			$title
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function revDeleteLink( $query = [], $restricted = false, $delete = true ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::revDeleteLink(
			$query,
			$restricted,
			$delete
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function revDeleteLinkDisabled( $delete = true ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::revDeleteLinkDisabled( $delete );
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tooltipAndAccesskeyAttribs( $name, array $msgParams = [] ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tooltipAndAccesskeyAttribs(
			$name,
			$msgParams
		);
	}

	/**
	 * @deprecated since 1.42
	 */
	public function tooltip( $name, $options = null ) {
		wfDeprecated( __METHOD__, '1.42' );
		return Linker::tooltip( $name, $options );
	}

}

/** @deprecated class alias since 1.40 */
class_alias( DummyLinker::class, 'DummyLinker' );
