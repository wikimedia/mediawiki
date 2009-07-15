<?php
/**
 * Use this special page to get a list of the MediaWiki system messages.
 * @file
 * @ingroup SpecialPage
 */
class SpecialAllmessages extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Allmessages' );
	}

	/**
	 * Execute
	 */
	function execute( $par ) {
		global $wgOut, $wgRequest;

		$this->setHeaders();

		global $wgUseDatabaseMessages;
		if( !$wgUseDatabaseMessages ) {
			$wgOut->addWikiMsg( 'allmessagesnotsupportedDB' );
			return;
		} else {
			$this->outputHeader( 'allmessagestext' );
		}

		$this->filter = $wgRequest->getVal( 'filter', 'all' );
		$this->prefix = $wgRequest->getVal( 'prefix', '' );

		$this->table = new AllmessagesTablePager( $this,
											$conds=array(),
											wfGetLangObj( $wgRequest->getVal( 'lang', false ) ) );

		$this->langCode = $this->table->lang->getCode();

		$wgOut->addHTML( $this->buildForm() .
						 $this->table->getNavigationBar() .
						 $this->table->getLimitForm() .
						 $this->table->getBody() .
						 $this->table->getNavigationBar() );

	}

	function buildForm() {
		$url = $this->getTitle()->escapeLocalURL();
		$languages = Language::getLanguageNames( false );
		ksort( $languages );

		$out  = "<form method=\"get\" action=\"$url\"><fieldset>\n" .
			Xml::hidden( 'title', $this->getTitle() ) .
					Xml::element( 'legend', null, wfMsg( 'allmessages' ) ) . "<table><tr>\n" .
				"<td class=\"mw-label\">" .
	                Xml::label( wfMsg('allmessages-prefix'), 'am-form-prefix' ) .
	            "</td>\n<td class=\"mw-input\">" .
	                Xml::input( 'prefix', 20, str_replace('_',' ',$this->prefix), array( 'id' => 'am-form-prefix' ) ) .
	            "</select>" .
				"</td>\n</tr><tr>\n<td class='mw-label'>" .
	                Xml::label( wfMsg('allmessages-filter'), 'am-form-filter' ) .
	            "</td>\n<td class='mw-input'>" .
	                Xml::radioLabel( wfMsg('allmessages-filter-unmodified'),
	                				 'filter',
	                				 'unmodified',
	                				 'am-form-filter-unmodified',
	                				 ( $this->filter == 'unmodified' ? true : false )
	                				) .
	                Xml::radioLabel( wfMsg('allmessages-filter-all'),
	                				 'filter',
	                				 'all',
	                				 'am-form-filter-all',
	                				 ( $this->filter == 'all' ? true : false )
	                				) .
	                Xml::radioLabel( wfMsg('allmessages-filter-modified'),
	                				 'filter',
	                				 'modified',
	                				 'am-form-filter-modified',
	                				 ( $this->filter == 'modified' ? true : false )
	                				) .
				"</td>\n</tr><tr>\n<td class=\"mw-label\">" .
	                Xml::label( wfMsg('yourlanguage'), 'am-form-lang' ) .
	            "</td>\n<td class=\"mw-input\">" .
	                Xml::openElement( 'select', array( 'id' => 'am-form-lang', 'name' => 'lang' ) );
		foreach( $languages as $lang => $name ) {
			$selected = $lang == $this->langCode ? 'selected="selected"' : '';
			$out .= "<option value=\"$lang\" $selected>$name</option>\n";
		}
		$out .= "</td>\n</tr><tr>\n<td></td><td>" . Xml::submitButton( wfMsg('allpagessubmit') ) .
				"</table>" .
					$this->table->getHiddenFields( array( 'title', 'prefix', 'filter', 'lang' ) ) .
				"</fieldset></form>";
		return $out;
	}
}

/* use TablePager for prettified output. We have to pretend that we're
 * getting data from a table when in fact not all of it comes from the database.
 */
class AllmessagesTablePager extends TablePager {

	var $messages  = NULL;
	var $talkPages = NULL;

	function __construct( $page, $conds, $langObj = NULL ) {
		parent::__construct();
		$this->mIndexField = 'am_title';
		$this->mPage = $page;
		$this->mConds = $conds;
		$this->mDefaultDirection = true;	//always sort ascending

		global $wgLang, $wgContLang, $wgRequest;

		$this->talk = $wgLang->lc( htmlspecialchars( wfMsg( 'talkpagelinktext' ) ) );

		$this->lang = ( $langObj ? $langObj : $wgContLang );
		$this->langcode = $this->lang->getCode();
		$this->foreign  = $this->langcode != $wgContLang->getCode();

		if( $wgRequest->getVal( 'filter', 'all' ) === 'all' ){
			$this->custom = NULL;	//So won't match in either case
		} else {
			$this->custom = $wgRequest->getVal( 'filter' ) == 'unmodified' ? 1 : 0;
		}

		$prefix = $wgLang->ucfirst( $wgRequest->getVal( 'prefix', '' ) );
		$prefix = $prefix != '' ? Title::makeTitleSafe( NS_MEDIAWIKI, $wgRequest->getVal( 'prefix', NULL ) ) : NULL;
		if( $prefix !== NULL ){
			$this->prefix = '/^' . preg_quote( $prefix->getDBkey() ) . '/i';
		} else {
			$this->prefix = false;
		}
		$this->getSkin();

		//The suffix that may be needed for message names if we're in a
		//different language (eg [[MediaWiki:Foo/fr]]: $suffix = '/fr'
		if( $this->foreign ) {
			$this->suffix = '/' . $this->langcode;
		} else {
			$this->suffix = '';
		}
	}

	function getAllMessages( $desc ){

		wfProfileIn( __METHOD__ . '-cache' );

		# Make sure all extension messages are available
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages( 'en' );
		$sortedArray = Language::getMessagesFor( 'en' );
		if( $desc ){
			krsort( $sortedArray );
		} else {
			ksort( $sortedArray );
		}

		$this->messages = array();
		foreach( $sortedArray as $key => $value ) {
			// All messages start with lowercase, but wikis might have both
			// upper and lowercase MediaWiki: pages if $wgCapitalLinks=false.
			$ukey = $this->lang->ucfirst( $key );

			// The value without any overrides from the MediaWiki: namespace
			$this->messages[$ukey]['default'] = wfMsgGetKey( $key, /*useDB*/false, $this->langcode, false );

			// The message that's actually used by the site
			$this->messages[$ukey]['actual'] = wfMsgGetKey( $key, /*useDB*/true, $this->langcode, false );

			$this->messages[$ukey]['customised'] = 0; //for now

			$sortedArray[$key] = NULL; // trade bytes from $sortedArray to this
		}

		wfProfileOut( __METHOD__ . '-cache' );

		return true;
	}

	# We only need a list of which messages have *been* customised;
	# their content is already in the message cache.
	function markCustomisedMessages(){
		$this->talkPages = array();

		wfProfileIn( __METHOD__ . "-db" );

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'page',
							 array( 'page_namespace', 'page_title' ),
							 array( 'page_namespace' => array(NS_MEDIAWIKI,NS_MEDIAWIKI_TALK) ),
							 __METHOD__,
							 array( 'USE INDEX' => 'name_title' )
						   );

		while( $s = $dbr->fetchObject( $res ) ) {
			if( $s->page_namespace == NS_MEDIAWIKI ){
				if( $this->foreign ){
					$title = explode( '/', $s->page_title );
					if( $this->langcode == $title[1] && array_key_exists( $title[0], $this->messages ) ){
						$this->messages["{$title[0]}"]['customised'] = 1;
				    }
				} else if( array_key_exists( $s->page_title , $this->messages ) ){
					$this->messages[$s->page_title]['customised'] = 1;
				}
			} else if( $s->page_namespace == NS_MEDIAWIKI_TALK ){
				$this->talkPages[$s->page_title] = 1;
			}
		}
		$dbr->freeResult( $res );

		wfProfileOut( __METHOD__ . "-db" );

		return true;
	}

	/* This function normally does a database query to get the results; we need
	 * to make a pretend result using a FakeResultWrapper.
	 */
	function reallyDoQuery( $offset , $limit , $descending ){
		$mResult = new FakeResultWrapper( array() );

		if( !$this->messages ) $this->getAllMessages( $descending );
		if( $this->talkPages === NULL ) $this->markCustomisedMessages();

		$count = 0;
		foreach( $this->messages as $key => $value ){
			if( $value['customised'] !== $this->custom &&
			    ( $descending && ( $key < $offset || !$offset ) || !$descending && $key > $offset ) &&
				(( $this->prefix && preg_match( $this->prefix, $key ) ) || $this->prefix === false )
			  ){
				$mResult->result[] = array( 'am_title'      => $key,
										    'am_actual'     => $value['actual'],
										    'am_default'    => $value['default'],
											'am_customised' => $value['customised'],
									  );
				unset( $this->messages[$key] );	// save a few bytes
				$count++;
			}
			if( $count == $limit ) break;
		}
		unset( $this->messages ); //no longer needed, free up some memory
		return $mResult;
	}

	function getStartBody() {
		return "<table border=\"1\" class=\"TablePager\" style=\"width:100%;\" id=\"allmessagestable\"><thead>\n<tr>" .
			   "<th rowspan=\"2\">" . wfMsg('allmessagesname') . "</th><th>" . wfMsg('allmessagesdefault') .
			   "</tr>\n<tr><th>" . wfMsg('allmessagescurrent') . "</th></tr>\n";
	}

	function formatValue( $field , $value ){
		global $wgLang;
		switch( $field ){

			case 'am_title' :

				$title = Title::makeTitle( NS_MEDIAWIKI, $value . $this->suffix );
				$talk  = Title::makeTitle( NS_MEDIAWIKI_TALK, $value . $this->suffix );

				if( $this->mCurrentRow->am_customised ){
					$title = $this->mSkin->linkKnown( $title, $wgLang->lcfirst( $value ) );
				} else {
					$title = $this->mSkin->link( $title,
												$wgLang->lcfirst( $value ),
												array(),
												array(),
												array( 'broken' ) );
				}
				if( array_key_exists( $talk->getDBkey() , $this->talkPages ) ) {
					$talk = $this->mSkin->linkKnown( $talk , $this->talk );
				} else {
					$talk = $this->mSkin->link( $talk,
												$this->talk,
												array(),
												array(),
												array( 'broken' ) );
				}
				return $title . ' (' . $talk . ')';

			case 'am_default' :
				return Sanitizer::escapeHtmlAllowEntities( $value, ENT_QUOTES );
			case 'am_actual' :
				return Sanitizer::escapeHtmlAllowEntities( $value, ENT_QUOTES );
		}
		return '';
	}

	function formatRow( $row ){
		//Do all the normal stuff
		$s = parent::formatRow( $row );

		//But if there's a customised message, add that too.
		if( $row->am_customised ){
			$s .= Xml::openElement( 'tr', $this->getRowAttrs( $row, true ) );
			$formatted = strval( $this->formatValue( 'am_actual', $row->am_actual ) );
			if ( $formatted == '' ) {
				$formatted = '&nbsp;';
			}
			$s .= Xml::tags( 'td', $this->getCellAttrs( 'am_actual', $row->am_actual ), $formatted )
			   . "</tr>\n";
		}
		return $s;
	}

	function getRowAttrs( $row, $isSecond=false ){
		$arr = array();
		global $wgLang;
		if( $row->am_customised ){
			$arr['class'] =  'allmessages-customised';
		}
		if( !$isSecond ){
			$arr['id'] = Sanitizer::escapeId( 'msg_' . $wgLang->lcfirst( $row->am_title ) );
		}
		return $arr;
	}

	function getCellAttrs( $field, $value ){
		if( $this->mCurrentRow->am_customised && $field == 'am_title' ){
			return array( 'rowspan' => '2', 'class' => $field );
		} else {
			return array( 'class' => $field );
		}
	}

	// This is not actually used, as getStartBody is overridden above
	function getFieldNames() {
		return array( 'am_title'   => wfMsg('allmessagesname'),
					  'am_default' => wfMsg('allmessagesdefault') );
	}
	function getTitle() {
		return SpecialPage::getTitleFor( 'Allmessages', false );
	}
	function isFieldSortable( $x ){
		return false;
	}
	function getDefaultSort(){
		return '';
	}
	function getQueryInfo(){
		return '';
	}
}
/* Overloads the relevant methods of the real ResultsWrapper so it
 * doesn't go anywhere near an actual database.
 */
class FakeResultWrapper extends ResultWrapper {

	var $result     = array();
	var $db         = NULL;		//And it's going to stay that way :D
	var $pos        = 0;
	var $currentRow = NULL;

	function __construct( $array ){
		$this->result = $array;
	}

	function numRows() {
		return count( $this->result );
	}

	function fetchRow() {
		$this->currentRow = $this->result[$this->pos++];
		return $this->currentRow;
	}

	function seek( $row ) {
		$this->pos = $row;
	}

	function free() {}

	// Callers want to be able to access fields with $this->fieldName
	function fetchObject(){
		$this->currentRow = $this->result[$this->pos++];
		return (object)$this->currentRow;
	}

	function rewind() {
		$this->pos = 0;
		$this->currentRow = NULL;
	}
}
