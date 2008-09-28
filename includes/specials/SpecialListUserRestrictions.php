<?php

function wfSpecialListUserRestrictions() {
	global $wgOut, $wgRequest;
	
	$wgOut->addWikiMsg( 'listuserrestrictions-intro' );
	$f = new SpecialListUserRestrictionsForm();
	$wgOut->addHTML( $f->getHTML() );

	if( !mt_rand( 0, 10 ) )
		UserRestriction::purgeExpired();
	$pager = new UserRestrictionsPager( $f->getConds() );
	if( $pager->getNumRows() ) 
		$wgOut->addHTML( $pager->getNavigationBar() .
			Xml::tags( 'ul', null, $pager->getBody() ) .
			$pager->getNavigationBar()
		);
	elseif( $f->getConds() )
		$wgOut->addWikiMsg( 'listuserrestrictions-notfound' );
	else 
		$wgOut->addWikiMsg( 'listuserrestrictions-empty' );
}

class SpecialListUserRestrictionsForm {
	public function getHTML() {
		global $wgRequest, $wgScript, $wgTitle;
		$s = '';
		$legend = wfMsgHtml( 'listuserrestrictions-legend' );
		$s .= "<fieldset><legend>{$legend}</legend>";
		$s .= "<form action=\"{$wgScript}\">";
		$s .= Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() );
		$s .= Xml::label( wfMsgHtml( 'listuserrestrictions-type' ), 'type' ) . '&nbsp;' .
			self::typeSelector( 'type', $wgRequest->getVal( 'type' ), 'type' );
		$s .= '&nbsp;';
		$s .= Xml::inputLabel( wfMsgHtml( 'listuserrestrictions-user' ), 'user', 'user',
			false, $wgRequest->getVal( 'user' ) );
		$s .= '<p>';
		$s .= Xml::label( wfMsgHtml( 'listuserrestrictions-namespace' ), 'namespace' ) . '&nbsp;' .
			Xml::namespaceSelector( $wgRequest->getVal( 'namespace' ), '', false, 'namespace' );
		$s .= '&nbsp;';
		$s .= Xml::inputLabel( wfMsgHtml( 'listuserrestrictions-page' ), 'page', 'page',
			false, $wgRequest->getVal( 'page' ) );
		$s .= Xml::submitButton( wfMsgHtml( 'listuserrestrictions-submit' ) );
		$s .= "</p></form></fieldset>";
		return $s;
	}

	public static function typeSelector( $name = 'type', $value = '', $id = false ) {
		$s = new XmlSelect( $name, $id, $value );
		$s->addOption( wfMsg( 'userrestrictiontype-none' ), '' );
		$s->addOption( wfMsg( 'userrestrictiontype-page' ), UserRestriction::PAGE );
		$s->addOption( wfMsg( 'userrestrictiontype-namespace' ), UserRestriction::NAMESPACE );
		return $s->getHTML();
	}

	public function getConds() {
		global $wgRequest;
		$conds = array();

		$type = $wgRequest->getVal( 'type' );
		if( in_array( $type, array( UserRestriction::PAGE, UserRestriction::NAMESPACE ) ) )
			$conds['ur_type'] = $type;

		$user = $wgRequest->getVal( 'user' );
		if( $user )
			$conds['ur_user_text'] = $user;

		$namespace = $wgRequest->getVal( 'namespace' );
		if( $namespace || $namespace === '0' )
			$conds['ur_namespace'] = $namespace;

		$page = $wgRequest->getVal( 'page' );
		$title = Title::newFromText( $page );
		if( $title ) {
			$conds['ur_page_namespace'] = $title->getNamespace();
			$conds['ur_page_title'] = $title->getDbKey();
		}

		return $conds;
	}
}

class UserRestrictionsPager extends ReverseChronologicalPager {
	public $mConds;

	public function __construct( $conds = array() ) {
		$this->mConds = $conds;
		parent::__construct();
	}

	public function getStartBody() {
		# Copied from Special:Ipblocklist
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		# Faster way
		# Usernames and titles are in fact related by a simple substitution of space -> underscore
		# The last few lines of Title::secureAndSplit() tell the story.
		foreach( $this->mResult as $row ) {
			$name = str_replace( ' ', '_', $row->ur_by_text );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
			$name = str_replace( ' ', '_', $row->ur_user_text );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
			if( $row->ur_type == UserRestriction::PAGE )
				$lb->add( $row->ur_page_namespace, $row->ur_page_title );
		}
		$lb->execute();
		wfProfileOut( __METHOD__ );
		return '';
	}

	public function getQueryInfo() {
		return array(
			'tables' => 'user_restrictions',
			'fields' => '*',
			'conds' => $this->mConds,
		);
	}

	public function formatRow( $row ) {
		return self::formatRestriction( UserRestriction::newFromRow( $row )  );
	}

	// Split off for use on Special:RestrictUser
	public static function formatRestriction( $r ) {
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		$timestamp = $wgLang->timeanddate( $r->getTimestamp(), true );
		$blockerlink = $sk->userLink( $r->getBlockerId(), $r->getBlockerText() ) .
			$sk->userToolLinks( $r->getBlockerId(), $r->getBlockerText() );
		$subjlink = $sk->userLink( $r->getSubjectId(), $r->getSubjectText() ) .
			$sk->userToolLinks( $r->getSubjectId(), $r->getSubjectText() );
		$expiry = is_numeric( $r->getExpiry() ) ?
			wfMsg( 'listuserrestrictions-row-expiry', $wgLang->timeanddate( $r->getExpiry() ) ) :
			wfMsg( 'ipbinfinite' );
		$msg = '';
		if( $r->isNamespace() ) {
			$msg = wfMsgHtml( 'listuserrestrictions-row-ns', $subjlink,
				$wgLang->getDisplayNsText( $r->getNamespace() ), $expiry );
		}
		if( $r->isPage() ) {
			$pagelink = $sk->link( $r->getPage() );
			$msg = wfMsgHtml( 'listuserrestrictions-row-page', $subjlink,
				$pagelink, $expiry );
		}
		$reason = $sk->commentBlock( $r->getReason() );
		$removelink = '';
		if( $wgUser->isAllowed( 'restrict' ) ) {
			$removelink = '(' . $sk->link( SpecialPage::getTitleFor( 'RemoveRestrictions' ),
				wfMsgHtml( 'listuserrestrictions-remove' ), array(), array( 'id' => $r->getId() ) ) . ')';
		}
		return "<li>{$timestamp}, {$blockerlink} {$msg} {$reason} {$removelink}</li>\n";
	}

	public function getIndexField() {
		return 'ur_timestamp';
	}
}
