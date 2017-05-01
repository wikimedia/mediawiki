<?php

/**
 * @since 1.18
 */
class DummyLinker {

	/**
	 * @deprecated since 1.28, use LinkRenderer::getLinkClasses() instead
	 */
	public function getLinkColour( $t, $threshold ) {
		wfDeprecated( __METHOD__, '1.28' );
		return Linker::getLinkColour( $t, $threshold );
	}

	public function link(
		$target,
		$html = null,
		$customAttribs = [],
		$query = [],
		$options = []
	) {
		return Linker::link(
			$target,
			$html,
			$customAttribs,
			$query,
			$options
		);
	}

	public function linkKnown(
		$target,
		$html = null,
		$customAttribs = [],
		$query = [],
		$options = [ 'known' ]
	) {
		return Linker::linkKnown(
			$target,
			$html,
			$customAttribs,
			$query,
			$options
		);
	}

	public function makeSelfLinkObj(
		$nt,
		$html = '',
		$query = '',
		$trail = '',
		$prefix = ''
	) {
		return Linker::makeSelfLinkObj(
			$nt,
			$html,
			$query,
			$trail,
			$prefix
		);
	}

	public function getInvalidTitleDescription(
		IContextSource $context,
		$namespace,
		$title
	) {
		return Linker::getInvalidTitleDescription(
			$context,
			$namespace,
			$title
		);
	}

	public function normaliseSpecialPage( Title $title ) {
		return Linker::normaliseSpecialPage( $title );
	}

	public function makeExternalImage( $url, $alt = '' ) {
		return Linker::makeExternalImage( $url, $alt );
	}

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

	public function makeThumbLinkObj(
		Title $title,
		$file,
		$label = '',
		$alt,
		$align = 'right',
		$params = [],
		$framed = false,
		$manualthumb = ""
	) {
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

	public function makeThumbLink2(
		Title $title,
		$file,
		$frameParams = [],
		$handlerParams = [],
		$time = false,
		$query = ""
	) {
		return Linker::makeThumbLink2(
			$title,
			$file,
			$frameParams,
			$handlerParams,
			$time,
			$query
		);
	}

	public function processResponsiveImages( $file, $thumb, $hp ) {
		Linker::processResponsiveImages(
			$file,
			$thumb,
			$hp
		);
	}

	public function makeBrokenImageLinkObj(
		$title,
		$label = '',
		$query = '',
		$unused1 = '',
		$unused2 = '',
		$time = false
	) {
		return Linker::makeBrokenImageLinkObj(
			$title,
			$label,
			$query,
			$unused1,
			$unused2,
			$time
		);
	}

	public function makeMediaLinkObj( $title, $html = '', $time = false ) {
		return Linker::makeMediaLinkObj(
			$title,
			$html,
			$time
		);
	}

	public function makeMediaLinkFile( Title $title, $file, $html = '' ) {
		return Linker::makeMediaLinkFile(
			$title,
			$file,
			$html
		);
	}

	public function specialLink( $name, $key = '' ) {
		return Linker::specialLink( $name, $key );
	}

	public function makeExternalLink(
		$url,
		$text,
		$escape = true,
		$linktype = '',
		$attribs = [],
		$title = null
	) {
		return Linker::makeExternalLink(
			$url,
			$text,
			$escape,
			$linktype,
			$attribs,
			$title
		);
	}

	public function userLink( $userId, $userName, $altUserName = false ) {
		return Linker::userLink(
			$userId,
			$userName,
			$altUserName
		);
	}

	public function userToolLinks(
		$userId,
		$userText,
		$redContribsWhenNoEdits = false,
		$flags = 0,
		$edits = null
	) {
		return Linker::userToolLinks(
			$userId,
			$userText,
			$redContribsWhenNoEdits,
			$flags,
			$edits
		);
	}

	public function userToolLinksRedContribs( $userId, $userText, $edits = null ) {
		return Linker::userToolLinksRedContribs(
			$userId,
			$userText,
			$edits
		);
	}

	public function userTalkLink( $userId, $userText ) {
		return Linker::userTalkLink( $userId, $userText );
	}

	public function blockLink( $userId, $userText ) {
		return Linker::blockLink( $userId, $userText );
	}

	public function emailLink( $userId, $userText ) {
		return Linker::emailLink( $userId, $userText );
	}

	public function revUserLink( $rev, $isPublic = false ) {
		return Linker::revUserLink( $rev, $isPublic );
	}

	public function revUserTools( $rev, $isPublic = false ) {
		return Linker::revUserTools( $rev, $isPublic );
	}

	public function formatComment(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		return Linker::formatComment(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	public function formatLinksInComment(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		return Linker::formatLinksInComment(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	public function makeCommentLink(
		Title $title,
		$text,
		$wikiId = null,
		$options = []
	) {
		return Linker::makeCommentLink(
			$title,
			$text,
			$wikiId,
			$options
		);
	}

	public function normalizeSubpageLink( $contextTitle, $target, &$text ) {
		return Linker::normalizeSubpageLink(
			$contextTitle,
			$target,
			$text
		);
	}

	public function commentBlock(
		$comment,
		$title = null,
		$local = false,
		$wikiId = null
	) {
		return Linker::commentBlock(
			$comment,
			$title,
			$local,
			$wikiId
		);
	}

	public function revComment( Revision $rev, $local = false, $isPublic = false ) {
		return Linker::revComment( $rev, $local, $isPublic );
	}

	public function formatRevisionSize( $size ) {
		return Linker::formatRevisionSize( $size );
	}

	public function tocIndent() {
		return Linker::tocIndent();
	}

	public function tocUnindent( $level ) {
		return Linker::tocUnindent( $level );
	}

	public function tocLine( $anchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
		return Linker::tocLine(
			$anchor,
			$tocline,
			$tocnumber,
			$level,
			$sectionIndex
		);
	}

	public function tocLineEnd() {
		return Linker::tocLineEnd();
	}

	public function tocList( $toc, $lang = false ) {
		return Linker::tocList( $toc, $lang );
	}

	public function generateTOC( $tree, $lang = false ) {
		return Linker::generateTOC( $tree, $lang );
	}

	public function makeHeadline(
		$level,
		$attribs,
		$anchor,
		$html,
		$link,
		$legacyAnchor = false
	) {
		return Linker::makeHeadline(
			$level,
			$attribs,
			$anchor,
			$html,
			$link,
			$legacyAnchor
		);
	}

	public function splitTrail( $trail ) {
		return Linker::splitTrail( $trail );
	}

	public function generateRollback(
		$rev,
		IContextSource $context = null,
		$options = [ 'verify' ]
	) {
		return Linker::generateRollback(
			$rev,
			$context,
			$options
		);
	}

	public function getRollbackEditCount( $rev, $verify ) {
		return Linker::getRollbackEditCount( $rev, $verify );
	}

	public function buildRollbackLink(
		$rev,
		IContextSource $context = null,
		$editCount = false
	) {
		return Linker::buildRollbackLink(
			$rev,
			$context,
			$editCount
		);
	}

	/**
	 * @deprecated since 1.28, use TemplatesOnThisPageFormatter directly
	 */
	public function formatTemplates(
		$templates,
		$preview = false,
		$section = false,
		$more = null
	) {
		wfDeprecated( __METHOD__, '1.28' );

		return Linker::formatTemplates(
			$templates,
			$preview,
			$section,
			$more
		);
	}

	public function formatHiddenCategories( $hiddencats ) {
		return Linker::formatHiddenCategories( $hiddencats );
	}

	/**
	 * @deprecated since 1.28, use Language::formatSize() directly
	 */
	public function formatSize( $size ) {
		wfDeprecated( __METHOD__, '1.28' );

		return Linker::formatSize( $size );
	}

	public function titleAttrib( $name, $options = null, array $msgParams = [] ) {
		return Linker::titleAttrib(
			$name,
			$options,
			$msgParams
		);
	}

	public function accesskey( $name ) {
		return Linker::accesskey( $name );
	}

	public function getRevDeleteLink( User $user, Revision $rev, Title $title ) {
		return Linker::getRevDeleteLink(
			$user,
			$rev,
			$title
		);
	}

	public function revDeleteLink( $query = [], $restricted = false, $delete = true ) {
		return Linker::revDeleteLink(
			$query,
			$restricted,
			$delete
		);
	}

	public function revDeleteLinkDisabled( $delete = true ) {
		return Linker::revDeleteLinkDisabled( $delete );
	}

	public function tooltipAndAccesskeyAttribs( $name, array $msgParams = [] ) {
		return Linker::tooltipAndAccesskeyAttribs(
			$name,
			$msgParams
		);
	}

	public function tooltip( $name, $options = null ) {
		return Linker::tooltip( $name, $options );
	}

}
