<?php
/**
 * File for articles
 * @version $Id$
 * @package MediaWiki
 */

/**
 * Need the CacheManager to be loaded
 */
require_once ( 'CacheManager.php' );

$wgArticleCurContentFields = false;
$wgArticleOldContentFields = false;

/**
 * Class representing a Wikipedia article and history.
 *
 * See design.doc for an overview.
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and CacheManager classes.
 *
 * @version $Id$
 * @package MediaWiki
 */
class Article {
	/**#@+
	 * @access private
	 */
	var $mContent, $mContentLoaded;
	var $mUser, $mTimestamp, $mUserText;
	var $mCounter, $mComment, $mCountAdjustment;
	var $mMinorEdit, $mRedirectedFrom;
	var $mTouched, $mFileCache, $mTitle;
	var $mId, $mTable;
	var $mForUpdate;
	/**#@-*/

	/**
	 * Constructor and clear the article
	 * @param mixed &$title
	 */
	function Article( &$title ) {
		$this->mTitle =& $title;
		$this->clear();
	}

	/**
	  * Clear the object
	  * @private
	  */
	function clear() {
		$this->mContentLoaded = false;
		$this->mCurID = $this->mUser = $this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = $this->mUserText =
		$this->mTimestamp = $this->mComment = $this->mFileCache = '';
		$this->mCountAdjustment = 0;
		$this->mTouched = '19700101000000';
		$this->mForUpdate = false;
	}

	/**
	  * Get revision text associated with an old or archive row
	  * $row is usually an object from wfFetchRow(), both the flags and the text
	  * field must be included
	  * @static
	  * @param integer $row Id of a row
	  * @param string $prefix table prefix (default 'old_')
	  * @return string $text|false the text requested
	*/
	function getRevisionText( $row, $prefix = 'old_' ) {
		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = array();
		}

		if ( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			return false;
		}

		if ( in_array( 'link', $flags ) ) {
			# Handle link type
			$text = Article::followLink( $text );
		} elseif ( in_array( 'gzip', $flags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			return gzinflate( $text );
		}
		return $text;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress datas
	 * @static
	 * @param mixed $text reference to a text
	 * @return string 'gzip' if it get compressed, '' overwise
	 */
	function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		if( !$wgCompressRevisions ) {
			return '';
		}
		if( !function_exists( 'gzdeflate' ) ) {
			wfDebug( "Article::compressRevisionText() -- no zlib support, not compressing\n" );
			return '';
		}
		$text = gzdeflate( $text );
		return 'gzip';
	}

	/**
	 * Returns the text associated with a "link" type old table row
	 * @static
	 * @param mixed $link
	 * @return string $text|false
	 */
	function followLink( $link ) {
		# Split the link into fields and values
		$lines = explode( '\n', $link );
		$hash = '';
		$locations = array();
		foreach ( $lines as $line ) {
			# Comments
			if ( $line{0} == '#' ) {
				continue;
			}
			# Field/value pairs
			if ( preg_match( '/^(.*?)\s*:\s*(.*)$/', $line, $matches ) ) {
				$field = strtolower($matches[1]);
				$value = $matches[2];
				if ( $field == 'hash' ) {
					$hash = $value;
				} elseif ( $field == 'location' ) {
					$locations[] = $value;
				}
			}
		}

		if ( $hash === '' ) {
			return false;
		}

		# Look in each specified location for the text
		$text = false;
		foreach ( $locations as $location ) {
			$text = Article::fetchFromLocation( $location, $hash );
			if ( $text !== false ) {
				break;
			}
		}

		return $text;
	}

	/**
	 * @static
	 * @param $location
	 * @param $hash
	 */
	function fetchFromLocation( $location, $hash ) {
		global $wgLoadBalancer;
		$fname = 'fetchFromLocation';
		wfProfileIn( $fname );

		$p = strpos( $location, ':' );
		if ( $p === false ) {
			wfProfileOut( $fname );
			return false;
		}

		$type = substr( $location, 0, $p );
		$text = false;
		switch ( $type ) {
			case 'mysql':
				# MySQL locations are specified by mysql://<machineID>/<dbname>/<tblname>/<index>
				# Machine ID 0 is the current connection
				if ( preg_match( '/^mysql:\/\/(\d+)\/([A-Za-z_]+)\/([A-Za-z_]+)\/([A-Za-z_]+)$/',
				  $location, $matches ) ) {
					$machineID = $matches[1];
					$dbName = $matches[2];
					$tblName = $matches[3];
					$index = $matches[4];
					if ( $machineID == 0 ) {
						# Current connection
						$db =& $this->getDB();
					} else {
						# Alternate connection
						$db =& $wgLoadBalancer->getConnection( $machineID );

						if ( array_key_exists( $machineId, $wgKnownMysqlServers ) ) {
							# Try to open, return false on failure
							$params = $wgKnownDBServers[$machineId];
							$db = Database::newFromParams( $params['server'], $params['user'], $params['password'],
								$dbName, 1, DBO_IGNORE );
						}
					}
					if ( $db->isOpen() ) {
						$index = $db->strencode( $index );
						$res = $db->query( "SELECT blob_data FROM $dbName.$tblName " .
							"WHERE blob_index='$index' " . $this->getSelectOptions(), $fname );
						$row = $db->fetchObject( $res );
						$text = $row->text_data;
					}
				}
				break;
			case 'file':
				# File locations are of the form file://<filename>, relative to the current directory
				if ( preg_match( '/^file:\/\/(.*)$', $location, $matches ) )
				$filename = strstr( $location, 'file://' );
				$text = @file_get_contents( $matches[1] );
		}
		if ( $text !== false ) {
			# Got text, now we need to interpret it
			# The first line contains information about how to do this
			$p = strpos( $text, '\n' );
			$type = substr( $text, 0, $p );
			$text = substr( $text, $p + 1 );
			switch ( $type ) {
				case 'plain':
					break;
				case 'gzip':
					$text = gzinflate( $text );
					break;
				case 'object':
					$object = unserialize( $text );
					$text = $object->getItem( $hash );
					break;
				default:
					$text = false;
			}
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Note that getContent/loadContent may follow redirects if
	 * not told otherwise, and so may cause a change to mTitle.
	 *
	 * @param $noredir
	 * @return Return the text of this revision
	*/
	function getContent( $noredir ) {
		global $wgRequest;

		# Get variables from query string :P
		$action = $wgRequest->getText( 'action', 'view' );
		$section = $wgRequest->getText( 'section' );

		$fname =  'Article::getContent';
		wfProfileIn( $fname );

		if ( 0 == $this->getID() ) {
			if ( 'edit' == $action ) {
				wfProfileOut( $fname );
				return ''; # was "newarticletext", now moved above the box)
			}
			wfProfileOut( $fname );
			return wfMsg( 'noarticletext' );
		} else {
			$this->loadContent( $noredir );
			# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
			if ( $this->mTitle->getNamespace() == NS_USER_TALK &&
			  preg_match('/^\d{1,3}\.\d{1,3}.\d{1,3}\.\d{1,3}$/',$this->mTitle->getText()) &&
			  $action=='view'
			) {
				wfProfileOut( $fname );
				return $this->mContent . "\n" .wfMsg('anontalkpagetext');
			} else {
				if($action=='edit') {
					if($section!='') {
						if($section=='new') {
							wfProfileOut( $fname );
							return '';
						}

						# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
						# comments to be stripped as well)
						$rv=$this->getSection($this->mContent,$section);
						wfProfileOut( $fname );
						return $rv;
					}
				}
				wfProfileOut( $fname );
				return $this->mContent;
			}
		}
	}

	/**
	 * This function returns the text of a section, specified by a number ($section).
	 * A section is text under a heading like == Heading == or <h1>Heading</h1>, or
	 * the first section before any such heading (section 0).
	 *
	 * If a section contains subsections, these are also returned.
	 *
	 * @param string $text text to look in
	 * @param integer $section section number
	 * @return string text of the requested section
	 */
	function getSection($text,$section) {

		# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
		# comments to be stripped as well)
		$striparray=array();
		$parser=new Parser();
		$parser->mOutputType=OT_WIKI;
		$striptext=$parser->strip($text, $striparray, true);

		# now that we can be sure that no pseudo-sections are in the source,
		# split it up by section
		$secs =
		  preg_split(
		  '/(^=+.+?=+|^<h[1-6].*?' . '>.*?<\/h[1-6].*?' . '>)(?!\S)/mi',
		  $striptext, -1,
		  PREG_SPLIT_DELIM_CAPTURE);
		if($section==0) {
			$rv=$secs[0];
		} else {
			$headline=$secs[$section*2-1];
			preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$headline,$matches);
			$hlevel=$matches[1];

			# translate wiki heading into level
			if(strpos($hlevel,'=')!==false) {
				$hlevel=strlen($hlevel);
			}

			$rv=$headline. $secs[$section*2];
			$count=$section+1;

			$break=false;
			while(!empty($secs[$count*2-1]) && !$break) {

				$subheadline=$secs[$count*2-1];
				preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$subheadline,$matches);
				$subhlevel=$matches[1];
				if(strpos($subhlevel,'=')!==false) {
					$subhlevel=strlen($subhlevel);
				}
				if($subhlevel > $hlevel) {
					$rv.=$subheadline.$secs[$count*2];
				}
				if($subhlevel <= $hlevel) {
					$break=true;
				}
				$count++;

			}
		}
		# reinsert stripped tags
		$rv=$parser->unstrip($rv,$striparray);
		$rv=$parser->unstripNoWiki($rv,$striparray);
		$rv=trim($rv);
		return $rv;

	}

	/**
	 * Return an array of the columns of the "cur"-table
	 */
	function &getCurContentFields() {
		global $wgArticleCurContentFields;
		if ( !$wgArticleCurContentFields ) {
			$wgArticleCurContentFields = array( 'cur_text','cur_timestamp','cur_user', 'cur_user_text',
			  'cur_comment','cur_counter','cur_restrictions','cur_touched' );
		}
		return $wgArticleCurContentFields;
	}

	/**
	 * Return an array of the columns of the "old"-table
	 */
	function &getOldContentFields() {
		global $wgArticleOldContentFields;
		if ( !$wgArticleOldContentFields ) {
			$wgArticleOldContentFields = array( 'old_namespace','old_title','old_text','old_timestamp',
			  'old_user','old_user_text','old_comment','old_flags' );
		}
		return $wgArticleOldContentFields;
	}

	/**
	 * Load the revision (including cur_text) into this object
	*/
	function loadContent( $noredir = false ) {
		global $wgOut, $wgRequest;

		if ( $this->mContentLoaded ) return;
		
		$dbr =& $this->getDB();
		# Query variables :P
		$oldid = $wgRequest->getVal( 'oldid' );
		$redirect = $wgRequest->getVal( 'redirect' );

		$fname = 'Article::loadContent';

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.

		$t = $this->mTitle->getPrefixedText();
		if ( isset( $oldid ) ) {
			$oldid = IntVal( $oldid );
			$t .= ',oldid='.$oldid;
		}
		if ( isset( $redirect ) ) {
			$redirect = ($redirect == 'no') ? 'no' : 'yes';
			$t .= ',redirect='.$redirect;
		}
		$this->mContent = wfMsg( 'missingarticle', $t );

		if ( ! $oldid ) {	# Retrieve current version
			$id = $this->getID();
			if ( 0 == $id ) return;

			$s = $dbr->selectRow( 'cur', $this->getCurContentFields(), array( 'cur_id' => $id ), $fname,
				$this->getSelectOptions() );
			if ( $s === false ) {
				return;
			}

			# If we got a redirect, follow it (unless we've been told
			# not to by either the function parameter or the query
			if ( ( 'no' != $redirect ) && ( false == $noredir ) ) {
			  	$rt = Title::newFromRedirect( $s->cur_text );
				# process if title object is valid and not special:userlogout
				if ( $rt && ! ( $rt->getNamespace() == NS_SPECIAL && $rt->getText() == 'Userlogout' ) ) {
					# Gotta hand redirects to special pages differently:
					# Fill the HTTP response "Location" header and ignore
					# the rest of the page we're on.

					if ( $rt->getInterwiki() != '' ) {
						$wgOut->redirect( $rt->getFullURL() ) ;
						return;
					}
					if ( $rt->getNamespace() == NS_SPECIAL ) {
						$wgOut->redirect( $rt->getFullURL() );
						return;
					}
					$rid = $rt->getArticleID();
					if ( 0 != $rid ) {
						$redirRow = $dbr->selectRow( 'cur', $this->getCurContentFields(),
							array( 'cur_id' => $rid ), $fname, $this->getSelectOptions() );

						if ( $redirRow !== false ) {
							$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
							$this->mTitle = $rt;
							$s = $redirRow;
						}
					}
				}
			}

			$this->mContent = $s->cur_text;
			$this->mUser = $s->cur_user;
			$this->mUserText = $s->cur_user_text;
			$this->mComment = $s->cur_comment;
			$this->mCounter = $s->cur_counter;
			$this->mTimestamp = wfTimestamp(TS_MW,$s->cur_timestamp);
			$this->mTouched = wfTimestamp(TS_MW,$s->cur_touched);
			$this->mTitle->mRestrictions = explode( ',', trim( $s->cur_restrictions ) );
			$this->mTitle->mRestrictionsLoaded = true;
		} else { # oldid set, retrieve historical version
			$s = $dbr->getArray( 'old', $this->getOldContentFields(), array( 'old_id' => $oldid ),
				$fname, $this->getSelectOptions() );
			if ( $s === false ) {
				return;
			}

			if( $this->mTitle->getNamespace() != $s->old_namespace ||
				$this->mTitle->getDBkey() != $s->old_title ) {
				$oldTitle = Title::makeTitle( $s->old_namesapce, $s->old_title );
				$this->mTitle = $oldTitle;
				$wgTitle = $oldTitle;
			}
			$this->mContent = Article::getRevisionText( $s );
			$this->mUser = $s->old_user;
			$this->mUserText = $s->old_user_text;
			$this->mComment = $s->old_comment;
			$this->mCounter = 0;
			$this->mTimestamp = wfTimestamp(TS_MW,$s->old_timestamp);
		}
		$this->mContentLoaded = true;
		return $this->mContent;
	}

	/**
	 * Gets the article text without using so many damn globals
	 * Returns false on error
	 *
	 * @param integer $oldid
	 */
	function getContentWithoutUsingSoManyDamnGlobals( $oldid = 0, $noredir = false ) {
		if ( $this->mContentLoaded ) {
			return $this->mContent;
		}
		$this->mContent = false;

		$fname = 'Article::getContentWithout';
		$dbr =& $this->getDB();

		if ( ! $oldid ) {	# Retrieve current version
			$id = $this->getID();
			if ( 0 == $id ) {
				return false;
			}

			$s = $dbr->selectRow( 'cur', $this->getCurContentFields(), array( 'cur_id' => $id ),
				$fname, $this->getSelectOptions() );
			if ( $s === false ) {
				return false;
			}

			# If we got a redirect, follow it (unless we've been told
			# not to by either the function parameter or the query
			if ( !$noredir ) {
				$rt = Title::newFromRedirect( $s->cur_text );
				if( $rt &&  $rt->getInterwiki() == '' && $rt->getNamespace() != NS_SPECIAL ) {
					$rid = $rt->getArticleID();
					if ( 0 != $rid ) {
						$redirRow = $dbr->selectRow( 'cur', $this->getCurContentFields(),
							array( 'cur_id' => $rid ), $fname, $this->getSelectOptions() );

						if ( $redirRow !== false ) {
							$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
							$this->mTitle = $rt;
							$s = $redirRow;
						}
					}
				}
			}

			$this->mContent = $s->cur_text;
			$this->mUser = $s->cur_user;
			$this->mUserText = $s->cur_user_text;
			$this->mComment = $s->cur_comment;
			$this->mCounter = $s->cur_counter;
			$this->mTimestamp = wfTimestamp(TS_MW,$s->cur_timestamp);
			$this->mTouched = wfTimestamp(TS_MW,$s->cur_touched);
			$this->mTitle->mRestrictions = explode( ',', trim( $s->cur_restrictions ) );
			$this->mTitle->mRestrictionsLoaded = true;
		} else { # oldid set, retrieve historical version
			$s = $dbr->selectRow( 'old', $this->getOldContentFields(), array( 'old_id' => $oldid ),
				$fname, $this->getSelectOptions() );
			if ( $s === false ) {
				return false;
			}
			$this->mContent = Article::getRevisionText( $s );
			$this->mUser = $s->old_user;
			$this->mUserText = $s->old_user_text;
			$this->mComment = $s->old_comment;
			$this->mCounter = 0;
			$this->mTimestamp = wfTimestamp(TS_MW,$s->old_timestamp);
		}
		$this->mContentLoaded = true;
		return $this->mContent;
	}

	/**
	 * Read/write accessor to select FOR UPDATE
	 */
	function forUpdate( $x = NULL ) {
		return wfSetVar( $this->mForUpdate, $x );
	}

	/**
	 * Get the database which should be used for reads
	 */
	function &getDB() {
		if ( $this->mForUpdate ) {
			return wfGetDB( DB_MASTER );
		} else {
			return wfGetDB( DB_SLAVE );
		}
	}

	/**
	 * Get options for all SELECT statements
	 * Can pass an option array, to which the class-wide options will be appended
	 */
	function getSelectOptions( $options = '' ) {
		if ( $this->mForUpdate ) {
			if ( $options ) {
				$options[] = 'FOR UPDATE';
			} else {
				$options = 'FOR UPDATE';
			}
		}
		return $options;
	}

	/**
	 * Return the Article ID
	 */
	function getID() {
		if( $this->mTitle ) {
			return $this->mTitle->getArticleID();
		} else {
			return 0;
		}
	}

	/**
	 * Get the view count for this article
	 */
	function getCount() {
		if ( -1 == $this->mCounter ) {
			$id = $this->getID();
			$dbr =& $this->getDB();
			$this->mCounter = $dbr->selectField( 'cur', 'cur_counter', 'cur_id='.$id,
				'Article::getCount', $this->getSelectOptions() );
		}
		return $this->mCounter;
	}

	/**
	 * Would the given text make this article a "good" article (i.e.,
	 * suitable for including in the article count)?
	 */
	function isCountable( $text ) {
		global $wgUseCommaCount;

		if ( 0 != $this->mTitle->getNamespace() ) { return 0; }
		if ( $this->isRedirect( $text ) ) { return 0; }
		$token = ($wgUseCommaCount ? ',' : '[[' );
		if ( false === strstr( $text, $token ) ) { return 0; }
		return 1;
	}

	/** 
	 * Tests if the article text represents a redirect
	 */
	function isRedirect( $text = false ) {
		if ( $text === false ) {
			$this->loadContent();
			$titleObj = Title::newFromRedirect( $this->mText );
		} else {
			$titleObj = Title::newFromRedirect( $text );
		}
		return $titleObj !== NULL;
	}

	/**
	 * Loads everything from cur except cur_text
	 * This isn't necessary for all uses, so it's only done if needed.
	 * @private
	 */
	function loadLastEdit() {
		global $wgOut;
		if ( -1 != $this->mUser ) return;

		$fname = 'Article::loadLastEdit';

		$dbr =& $this->getDB();
		$s = $dbr->getArray( 'cur',
		  array( 'cur_user','cur_user_text','cur_timestamp', 'cur_comment','cur_minor_edit' ),
		  array( 'cur_id' => $this->getID() ), $fname, $this->getSelectOptions() );

		if ( $s !== false ) {
			$this->mUser = $s->cur_user;
			$this->mUserText = $s->cur_user_text;
			$this->mTimestamp = wfTimestamp(TS_MW,$s->cur_timestamp);
			$this->mComment = $s->cur_comment;
			$this->mMinorEdit = $s->cur_minor_edit;
		}
	}

	function getTimestamp() {
		$this->loadLastEdit();
		return $this->mTimestamp;
	}

	function getUser() {
		$this->loadLastEdit();
		return $this->mUser;
	}

	function getUserText() {
		$this->loadLastEdit();
		return $this->mUserText;
	}

	function getComment() {
		$this->loadLastEdit();
		return $this->mComment;
	}

	function getMinorEdit() {
		$this->loadLastEdit();
		return $this->mMinorEdit;
	}

	function getContributors($limit = 0, $offset = 0) {
		$fname = 'Article::getContributors';

		# XXX: this is expensive; cache this info somewhere.

		$title = $this->mTitle;
		$contribs = array();
		$dbr =& $this->getDB();
		$oldTable = $dbr->tableName( 'old' );
		$userTable = $dbr->tableName( 'user' );
		$encDBkey = $dbr->addQuotes( $title->getDBkey() );
		$ns = $title->getNamespace();
		$user = $this->getUser();

		$sql = "SELECT old_user, old_user_text, user_real_name, MAX(old_timestamp) as timestamp
			FROM $oldTable LEFT JOIN $userTable ON old_user = user_id
			WHERE old_namespace = $user
			AND old_title = $encDBkey
			AND old_user != $user
			GROUP BY old_user, old_user_text, user_real_name
			ORDER BY timestamp DESC";

		if ($limit > 0) { $sql .= ' LIMIT '.$limit; }
		$sql .= ' '. $this->getSelectOptions();

		$res = $dbr->query($sql, $fname);

		while ( $line = $dbr->fetchObject( $res ) ) {
			$contribs[] = array($line->old_user, $line->old_user_text, $line->user_real_name);
		}

		$dbr->freeResult($res);
		return $contribs;
	}

	/**
	 * This is the default action of the script: just view the page of
	 * the given title.
	*/
	function view()	{
		global $wgUser, $wgOut, $wgRequest, $wgOnlySysopsCanPatrol;
		global $wgLinkCache, $IP, $wgEnableParserCache, $wgStylePath, $wgUseRCPatrol;
		$sk = $wgUser->getSkin();

		$fname = 'Article::view';
		wfProfileIn( $fname );
		# Get variables from query string
		$oldid = $wgRequest->getVal( 'oldid' );
		$diff = $wgRequest->getVal( 'diff' );
		$rcid = $wgRequest->getVal( 'rcid' );

		$wgOut->setArticleFlag( true );
		$wgOut->setRobotpolicy( 'index,follow' );
		# If we got diff and oldid in the query, we want to see a
		# diff page instead of the article.

		if ( !is_null( $diff ) ) {
			require_once( 'DifferenceEngine.php' );
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$de = new DifferenceEngine( $oldid, $diff, $rcid );
			$de->showDiffPage();
			wfProfileOut( $fname );
			if( $diff == 0 ) {
				# Run view updates for current revision only
				$this->viewUpdates();
			}
			return;
		}
		if ( empty( $oldid ) && $this->checkTouched() ) {
			if( $wgOut->checkLastModified( $this->mTouched ) ){
				return;
			} else if ( $this->tryFileCache() ) {
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->viewUpdates();
				return;
			}
		}
		# Should the parser cache be used?
		if ( $wgEnableParserCache && intval($wgUser->getOption( 'stubthreshold' )) == 0 && empty( $oldid ) ) {
			$pcache = true;
		} else {
			$pcache = false;
		}

		$outputDone = false;
		if ( $pcache ) {
			if ( $wgOut->tryParserCache( $this, $wgUser ) ) {
				$outputDone = true;
			}
		}
		if ( !$outputDone ) {
			$text = $this->getContent( false ); # May change mTitle by following a redirect

			# Another whitelist check in case oldid or redirects are altering the title
			if ( !$this->mTitle->userCanRead() ) {
				$wgOut->loginToUse();
				$wgOut->output();
				exit;
			}


			# We're looking at an old revision

			if ( !empty( $oldid ) ) {
				$this->setOldSubtitle();
				$wgOut->setRobotpolicy( 'noindex,follow' );
			}
			if ( '' != $this->mRedirectedFrom ) {
				$sk = $wgUser->getSkin();
				$redir = $sk->makeKnownLink( $this->mRedirectedFrom, '',
				  'redirect=no' );
				$s = wfMsg( 'redirectedfrom', $redir );
				$wgOut->setSubtitle( $s );

				# Can't cache redirects
				$pcache = false;
			}

			# wrap user css and user js in pre and don't parse
			# XXX: use $this->mTitle->usCssJsSubpage() when php is fixed/ a workaround is found
			if (
				$this->mTitle->getNamespace() == NS_USER &&
				preg_match('/\\/[\\w]+\\.(css|js)$/', $this->mTitle->getDBkey())
			) {
				$wgOut->addWikiText( wfMsg('clearyourcache'));
				$wgOut->addHTML( '<pre>'.htmlspecialchars($this->mContent)."\n</pre>" );
			} else if ( $rt = Title::newFromRedirect( $text ) ) {
				# Display redirect
				$imageUrl = $wgStylePath.'/common/images/redirect.png';
				$targetUrl = $rt->escapeLocalURL();
				$titleText = htmlspecialchars( $rt->getPrefixedText() );
				$link = $sk->makeLinkObj( $rt );
				$wgOut->addHTML( '<img valign="center" src="'.$imageUrl.'">' .
				  '<span class="redirectText">'.$link.'</span>' );
			} else if ( $pcache ) {
				# Display content and save to parser cache
				$wgOut->addPrimaryWikiText( $text, $this );
			} else {
				# Display content, don't attempt to save to parser cache
				$wgOut->addWikiText( $text );
			}
		}
		$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
		# If we have been passed an &rcid= parameter, we want to give the user a
		# chance to mark this new article as patrolled.
		if ( $wgUseRCPatrol && !is_null ( $rcid ) && $rcid != 0 && $wgUser->getID() != 0 &&
		     ( $wgUser->isSysop() || !$wgOnlySysopsCanPatrol ) )
		{
			$wgOut->addHTML( wfMsg ( 'markaspatrolledlink',
				$sk->makeKnownLinkObj ( $this->mTitle, wfMsg ( 'markaspatrolledtext' ),
					'action=markpatrolled&rcid='.$rcid )
			 ) );
		}

		# Put link titles into the link cache
		$wgOut->transformBuffer();

		# Add link titles as META keywords
		$wgOut->addMetaTags() ;

		$this->viewUpdates();
		wfProfileOut( $fname );
	}

	/**
	 * Theoretically we could defer these whole insert and update
	 * functions for after display, but that's taking a big leap
	 * of faith, and we want to be able to report database
	 * errors at some point.
	 * @private
	 */
	function insertNewArticle( $text, $summary, $isminor, $watchthis ) {
		global $wgOut, $wgUser;
		global $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer;

		$fname = 'Article::insertNewArticle';

		$this->mCountAdjustment = $this->isCountable( $text );

		$ns = $this->mTitle->getNamespace();
		$ttl = $this->mTitle->getDBkey();
		$text = $this->preSaveTransform( $text );
		if ( $this->isRedirect( $text ) ) { $redir = 1; }
		else { $redir = 0; }

		$now = wfTimestampNow();
		$won = wfInvertTimestamp( $now );
		wfSeedRandom();
		$rand = number_format( mt_rand() / mt_getrandmax(), 12, '.', '' );
		$dbw =& wfGetDB( DB_MASTER );

		$cur_id = $dbw->nextSequenceValue( 'cur_cur_id_seq' );

		$isminor = ( $isminor && $wgUser->getID() ) ? 1 : 0;

		$dbw->insertArray( 'cur', array(
			'cur_id' => $cur_id,
			'cur_namespace' => $ns,
			'cur_title' => $ttl,
			'cur_text' => $text,
			'cur_comment' => $summary,
			'cur_user' => $wgUser->getID(),
			'cur_timestamp' => $dbw->timestamp($now),
			'cur_minor_edit' => $isminor,
			'cur_counter' => 0,
			'cur_restrictions' => '',
			'cur_user_text' => $wgUser->getName(),
			'cur_is_redirect' => $redir,
			'cur_is_new' => 1,
			'cur_random' => $rand,
			'cur_touched' => $dbw->timestamp($now),
			'inverse_timestamp' => $won,
		), $fname );

		$newid = $dbw->insertId();
		$this->mTitle->resetArticleID( $newid );

		Article::onArticleCreate( $this->mTitle );
		RecentChange::notifyNew( $now, $this->mTitle, $isminor, $wgUser, $summary );

		if ($watchthis) {
			if(!$this->mTitle->userIsWatching()) $this->watch();
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->unwatch();
			}
		}

		# The talk page isn't in the regular link tables, so we need to update manually:
		$talkns = $ns ^ 1; # talk -> normal; normal -> talk
		$dbw->updateArray( 'cur', array('cur_touched' => $dbw->timestamp($now) ),
			array(  'cur_namespace' => $talkns, 'cur_title' => $ttl ), $fname );

		# standard deferred updates
		$this->editUpdates( $text );

		$this->showArticle( $text, wfMsg( 'newarticle' ) );
	}


	/**
	 * Side effects: loads last edit if $edittime is NULL
	 */
	function getTextOfLastEditWithSectionReplacedOrAdded($section, $text, $summary = '', $edittime = NULL) {
		$fname = 'Article::getTextOfLastEditWithSectionReplacedOrAdded';
		if(is_null($edittime)) {
			$this->loadLastEdit();
			$oldtext = $this->getContent( true );
		} else {
			$dbw =& wfGetDB( DB_MASTER );
			$ns = $this->mTitle->getNamespace();
			$title = $this->mTitle->getDBkey();
			$obj = $dbw->getArray( 'old', 
				array( 'old_text','old_flags'), 
				array( 'old_namespace' => $ns, 'old_title' => $title, 
					'old_timestamp' => $dbw->timestamp($edittime)),
				$fname );
			$oldtext = Article::getRevisionText( $obj );
		}
		if ($section != '') {
			if($section=='new') {
				if($summary) $subject="== {$summary} ==\n\n";
				$text=$oldtext."\n\n".$subject.$text;
			} else {

				# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
				# comments to be stripped as well)
				$striparray=array();
				$parser=new Parser();
				$parser->mOutputType=OT_WIKI;
				$oldtext=$parser->strip($oldtext, $striparray, true);

				# now that we can be sure that no pseudo-sections are in the source,
				# split it up
				# Unfortunately we can't simply do a preg_replace because that might
				# replace the wrong section, so we have to use the section counter instead
				$secs=preg_split('/(^=+.+?=+|^<h[1-6].*?' . '>.*?<\/h[1-6].*?' . '>)(?!\S)/mi',
				  $oldtext,-1,PREG_SPLIT_DELIM_CAPTURE);
				$secs[$section*2]=$text."\n\n"; // replace with edited

				# section 0 is top (intro) section
				if($section!=0) {

					# headline of old section - we need to go through this section
					# to determine if there are any subsections that now need to
					# be erased, as the mother section has been replaced with
					# the text of all subsections.
					$headline=$secs[$section*2-1];
					preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$headline,$matches);
					$hlevel=$matches[1];

					# determine headline level for wikimarkup headings
					if(strpos($hlevel,'=')!==false) {
						$hlevel=strlen($hlevel);
					}

					$secs[$section*2-1]=''; // erase old headline
					$count=$section+1;
					$break=false;
					while(!empty($secs[$count*2-1]) && !$break) {

						$subheadline=$secs[$count*2-1];
						preg_match(
						 '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$subheadline,$matches);
						$subhlevel=$matches[1];
						if(strpos($subhlevel,'=')!==false) {
							$subhlevel=strlen($subhlevel);
						}
						if($subhlevel > $hlevel) {
							// erase old subsections
							$secs[$count*2-1]='';
							$secs[$count*2]='';
						}
						if($subhlevel <= $hlevel) {
							$break=true;
						}
						$count++;

					}

				}
				$text=join('',$secs);
				# reinsert the stuff that we stripped out earlier
				$text=$parser->unstrip($text,$striparray);
				$text=$parser->unstripNoWiki($text,$striparray);
			}

		}
		return $text;
	}

	/**
	 * Change an existing article. Puts the previous version back into the old table, updates RC 
	 * and all necessary caches, mostly via the deferred update array.
	 *
	 * It is possible to call this function from a command-line script, but note that you should 
	 * first set $wgUser, and clean up $wgDeferredUpdates after each edit.
	 */
	function updateArticle( $text, $summary, $minor, $watchthis, $forceBot = false, $sectionanchor = '' ) {
		global $wgOut, $wgUser;
		global $wgDBtransactions, $wgMwRedir;
		global $wgUseSquid, $wgInternalServer;

		$fname = 'Article::updateArticle';
		$good = true;

		if ( $this->mMinorEdit ) { $me1 = 1; } else { $me1 = 0; }
		if ( $minor && $wgUser->getID() ) { $me2 = 1; } else { $me2 = 0; }
		if ( $this->isRedirect( $text ) ) {
			# Remove all content but redirect
			# This could be done by reconstructing the redirect from a title given by 
			# Title::newFromRedirect(), but then we wouldn't know which synonym the user
			# wants to see
			if ( preg_match( "/^((" . $wgMwRedir->getBaseRegex() . ')[^\\n]+)/i', $text, $m ) ) {
				$redir = 1;
				$text = $m[1] . "\n";
			}
		}
		else { $redir = 0; }

		$text = $this->preSaveTransform( $text );
		$dbw =& wfGetDB( DB_MASTER );

		# Update article, but only if changed.

		# It's important that we either rollback or complete, otherwise an attacker could
		# overwrite cur entries by sending precisely timed user aborts. Random bored users
		# could conceivably have the same effect, especially if cur is locked for long periods.
		if( $wgDBtransactions ) {
			$dbw->query( 'BEGIN', $fname );
		} else {
			$userAbort = ignore_user_abort( true );
		}

		$oldtext = $this->getContent( true );

		if ( 0 != strcmp( $text, $oldtext ) ) {
			$this->mCountAdjustment = $this->isCountable( $text )
			  - $this->isCountable( $oldtext );
			$now = wfTimestampNow();
			$won = wfInvertTimestamp( $now );

			# First update the cur row
			$dbw->updateArray( 'cur',
				array( /* SET */
					'cur_text' => $text,
					'cur_comment' => $summary,
					'cur_minor_edit' => $me2,
					'cur_user' => $wgUser->getID(),
					'cur_timestamp' => $dbw->timestamp($now),
					'cur_user_text' => $wgUser->getName(),
					'cur_is_redirect' => $redir,
					'cur_is_new' => 0,
					'cur_touched' => $dbw->timestamp($now),
					'inverse_timestamp' => $won
				), array( /* WHERE */
					'cur_id' => $this->getID(),
					'cur_timestamp' => $dbw->timestamp($this->getTimestamp())
				), $fname
			);

			if( $dbw->affectedRows() == 0 ) {
				/* Belated edit conflict! Run away!! */
				$good = false;
			} else {
				# Now insert the previous revision into old

				# This overwrites $oldtext if revision compression is on
				$flags = Article::compressRevisionText( $oldtext );

				$dbw->insertArray( 'old',
					array(
						'old_id' => $dbw->nextSequenceValue( 'old_old_id_seq' ),
						'old_namespace' => $this->mTitle->getNamespace(),
						'old_title' => $this->mTitle->getDBkey(),
						'old_text' => $oldtext,
						'old_comment' => $this->getComment(),
						'old_user' => $this->getUser(),
						'old_user_text' => $this->getUserText(),
						'old_timestamp' => $dbw->timestamp($this->getTimestamp()),
						'old_minor_edit' => $me1,
						'inverse_timestamp' => wfInvertTimestamp( $this->getTimestamp() ),
						'old_flags' => $flags,
					), $fname
				);

				$oldid = $dbw->insertId();

				$bot = (int)($wgUser->isBot() || $forceBot);
				RecentChange::notifyEdit( $now, $this->mTitle, $me2, $wgUser, $summary,
					$oldid, $this->getTimestamp(), $bot );
				Article::onArticleEdit( $this->mTitle );
			}
		}

		if( $wgDBtransactions ) {
			$dbw->query( 'COMMIT', $fname );
		} else {
			ignore_user_abort( $userAbort );
		}

		if ( $good ) {
		if ($watchthis) {
			if (!$this->mTitle->userIsWatching()) $this->watch();
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->unwatch();
			}
		}
		# standard deferred updates
		$this->editUpdates( $text );


		$urls = array();
		# Template namespace
		# Purge all articles linking here
		if ( $this->mTitle->getNamespace() == NS_TEMPLATE) {
			$titles = $this->mTitle->getLinksTo();
			Title::touchArray( $titles );
			if ( $wgUseSquid ) {
					foreach ( $titles as $title ) {
						$urls[] = $title->getInternalURL();
					}
			}
		}

		# Squid updates
		if ( $wgUseSquid ) {
			$urls = array_merge( $urls, $this->mTitle->getSquidURLs() );
			$u = new SquidUpdate( $urls );
			$u->doUpdate();
		}

		$this->showArticle( $text, wfMsg( 'updated' ), $sectionanchor );
		}
		return $good;
	}

	/**
	 * After we've either updated or inserted the article, update
	 * the link tables and redirect to the new page.
	 */
	function showArticle( $text, $subtitle , $sectionanchor = '' ) {
		global $wgOut, $wgUser, $wgLinkCache;

		$wgLinkCache = new LinkCache();
		# Select for update
		$wgLinkCache->forUpdate( true );

		# Get old version of link table to allow incremental link updates
		$wgLinkCache->preFill( $this->mTitle );
		$wgLinkCache->clear();

		# Parse the text and replace links with placeholders
		$wgOut = new OutputPage();
		$wgOut->addWikiText( $text );

		# Look up the links in the DB and add them to the link cache
		$wgOut->transformBuffer( RLH_FOR_UPDATE );

		if( $this->isRedirect( $text ) )
			$r = 'redirect=no';
		else
			$r = '';
		$wgOut->redirect( $this->mTitle->getFullURL( $r ).$sectionanchor );
	}

	/**
	 * Validate article
	 * @todo document this function a bit more
	 */
	function validate () {
		global $wgOut, $wgUseValidation;
		if( $wgUseValidation ) {
			require_once ( 'SpecialValidate.php' ) ;
			$wgOut->setPagetitle( wfMsg( 'validate' ) . ': ' . $this->mTitle->getPrefixedText() );
			$wgOut->setRobotpolicy( 'noindex,follow' );
			if( $this->mTitle->getNamespace() != 0 ) {
				$wgOut->addHTML( wfMsg( 'val_validate_article_namespace_only' ) );
				return;
			}
			$v = new Validation;
			$v->validate_form( $this->mTitle->getDBkey() );
		} else {
			$wgOut->errorpage( 'nosuchaction', 'nosuchactiontext' );
		}
	}

	/**
	 * Mark this particular edit as patrolled
	 */
	function markpatrolled() {
		global $wgOut, $wgRequest, $wgOnlySysopsCanPatrol, $wgUseRCPatrol, $wgUser;
		$wgOut->setRobotpolicy( 'noindex,follow' );

		if ( !$wgUseRCPatrol )
		{
			$wgOut->errorpage( 'rcpatroldisabled', 'rcpatroldisabledtext' );
			return;
		}
		if ( $wgUser->getID() == 0 )
		{
			$wgOut->loginToUse();
			return;
		}
		if ( $wgOnlySysopsCanPatrol && !$wgUser->isSysop() )
		{
			$wgOut->sysopRequired();
			return;
		}
		$rcid = $wgRequest->getVal( 'rcid' );
		if ( !is_null ( $rcid ) )
		{
			RecentChange::markPatrolled( $rcid );
			$wgOut->setPagetitle( wfMsg( 'markedaspatrolled' ) );
			$wgOut->addWikiText( wfMsg( 'markedaspatrolledtext' ) );
			$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
		}
		else
		{
			$wgOut->errorpage( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}
	}


	/**
	 * Add or remove this page to my watchlist based on value of $add
	 */
	function watch( $add = true ) {
		global $wgUser, $wgOut;
		global $wgDeferredUpdateList;

		if ( 0 == $wgUser->getID() ) {
			$wgOut->errorpage( 'watchnologin', 'watchnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if( $add )
			$wgUser->addWatch( $this->mTitle );
		else
			$wgUser->removeWatch( $this->mTitle );

		$wgOut->setPagetitle( wfMsg( $add ? 'addedwatch' : 'removedwatch' ) );
		$wgOut->setRobotpolicy( 'noindex,follow' );

		$sk = $wgUser->getSkin() ;
		$link = $this->mTitle->getPrefixedText();

		if($add)
			$text = wfMsg( 'addedwatchtext', $link );
		else
			$text = wfMsg( 'removedwatchtext', $link );
		$wgOut->addWikiText( $text );

		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );

		$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
	}

	/**
	 * Stop watching a page, it act just like a call to watch(false)
	 */
	function unwatch() {
		$this->watch( false );
	}

	/**
	 * protect a page
	 */
	function protect( $limit = 'sysop' ) {
		global $wgUser, $wgOut, $wgRequest;

		if ( ! $wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		$id = $this->mTitle->getArticleID();
		if ( 0 == $id ) {
			$wgOut->fatalError( wfMsg( 'badarticleerror' ) );
			return;
		}

		$confirm = $wgRequest->getBool( 'wpConfirmProtect' ) && $wgRequest->wasPosted();
		$reason = $wgRequest->getText( 'wpReasonProtect' );

		if ( $confirm ) {
			$dbw =& wfGetDB( DB_MASTER );
			$dbw->updateArray( 'cur',
				array( /* SET */
					'cur_touched' => $dbw->timestamp(),
					'cur_restrictions' => (string)$limit
				), array( /* WHERE */
					'cur_id' => $id
				), 'Article::protect'
			);

			$log = new LogPage( 'protect' );
			if ( $limit === '' ) {
					$log->addEntry( 'unprotect', $this->mTitle, $reason );
			} else {
					$log->addEntry( 'protect', $this->mTitle, $reason );
			}
			$wgOut->redirect( $this->mTitle->getFullURL() );
			return;
		} else {
			$reason = htmlspecialchars( wfMsg( 'protectreason' ) );
			return $this->confirmProtect( '', $reason, $limit );
		}
	}

	/**
	 * Output protection confirmation dialog
	 */
	function confirmProtect( $par, $reason, $limit = 'sysop'  ) {
		global $wgOut;

		wfDebug( "Article::confirmProtect\n" );

		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$check = '';
		$protcom = '';

		if ( $limit === '' ) {
			$wgOut->setPageTitle( wfMsg( 'confirmunprotect' ) );
			$wgOut->setSubtitle( wfMsg( 'unprotectsub', $sub ) );
			$wgOut->addWikiText( wfMsg( 'confirmunprotecttext' ) );
			$check = htmlspecialchars( wfMsg( 'confirmunprotect' ) );
			$protcom = htmlspecialchars( wfMsg( 'unprotectcomment' ) );
			$formaction = $this->mTitle->escapeLocalURL( 'action=unprotect' . $par );
		} else {
			$wgOut->setPageTitle( wfMsg( 'confirmprotect' ) );
			$wgOut->setSubtitle( wfMsg( 'protectsub', $sub ) );
			$wgOut->addWikiText( wfMsg( 'confirmprotecttext' ) );
			$check = htmlspecialchars( wfMsg( 'confirmprotect' ) );
			$protcom = htmlspecialchars( wfMsg( 'protectcomment' ) );
			$formaction = $this->mTitle->escapeLocalURL( 'action=protect' . $par );
		}

		$confirm = htmlspecialchars( wfMsg( 'confirm' ) );

		$wgOut->addHTML( "
<form id='protectconfirm' method='post' action=\"{$formaction}\">
	<table border='0'>
		<tr>
			<td align='right'>
				<label for='wpReasonProtect'>{$protcom}:</label>
			</td>
			<td align='left'>
				<input type='text' size='60' name='wpReasonProtect' id='wpReasonProtect' value=\"" . htmlspecialchars( $reason ) . "\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='right'>
				<input type='checkbox' name='wpConfirmProtect' value='1' id='wpConfirmProtect' />
			</td>
			<td>
				<label for='wpConfirmProtect'>{$check}</label>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='submit' name='wpConfirmProtectB' value=\"{$confirm}\" />
			</td>
		</tr>
	</table>
</form>\n" );

		$wgOut->returnToMain( false );
	}

	/**
	 * Unprotect the pages
	 */
	function unprotect() {
		return $this->protect( '' );
	}

	/*
	 * UI entry point for page deletion
	 */
	function delete() {
		global $wgUser, $wgOut, $wgMessageCache, $wgRequest;
		$fname = 'Article::delete';
		$confirm = $wgRequest->getBool( 'wpConfirm' ) && $wgRequest->wasPosted();
		$reason = $wgRequest->getText( 'wpReason' );

		# This code desperately needs to be totally rewritten

		# Check permissions
		if ( ( ! $wgUser->isSysop() ) ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( 'confirmdelete' ) );
		if ( ( '' == trim( $this->mTitle->getText() ) )
		  or ( $this->mTitle->getArticleId() == 0 ) ) {
			$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
			return;
		}

		if ( $confirm ) {
			$this->doDelete( $reason );
			return;
		}

		# determine whether this page has earlier revisions
		# and insert a warning if it does
		# we select the text because it might be useful below
		$dbr =& $this->getDB();
		$ns = $this->mTitle->getNamespace();
		$title = $this->mTitle->getDBkey();
		$old = $dbr->getArray( 'old',
			array( 'old_text', 'old_flags' ),
			array(
				'old_namespace' => $ns,
				'old_title' => $title,
			), $fname, $this->getSelectOptions( array( 'ORDER BY' => 'inverse_timestamp' ) )
		);

		if( $old !== false && !$confirm ) {
			$skin=$wgUser->getSkin();
			$wgOut->addHTML('<b>'.wfMsg('historywarning'));
			$wgOut->addHTML( $skin->historyLink() .'</b>');
		}

		# Fetch cur_text
		$s = $dbr->getArray( 'cur',
			array( 'cur_text' ),
			array(
				'cur_namespace' => $ns,
				'cur_title' => $title,
			), $fname, $this->getSelectOptions()
		);

		if( $s !== false ) {
			# if this is a mini-text, we can paste part of it into the deletion reason

			#if this is empty, an earlier revision may contain "useful" text
			$blanked = false;
			if($s->cur_text != '') {
				$text=$s->cur_text;
			} else {
				if($old) {
					$text = Article::getRevisionText( $old );
					$blanked = true;
				}

			}

			$length=strlen($text);

			# this should not happen, since it is not possible to store an empty, new
			# page. Let's insert a standard text in case it does, though
			if($length == 0 && $reason === '') {
				$reason = wfMsg('exblank');
			}

			if($length < 500 && $reason === '') {

				# comment field=255, let's grep the first 150 to have some user
				# space left
				$text=substr($text,0,150);
				# let's strip out newlines and HTML tags
				$text=preg_replace('/\"/',"'",$text);
				$text=preg_replace('/\</','&lt;',$text);
				$text=preg_replace('/\>/','&gt;',$text);
				$text=preg_replace("/[\n\r]/",'',$text);
				if(!$blanked) {
					$reason=wfMsg('excontent'). " '".$text;
				} else {
					$reason=wfMsg('exbeforeblank') . " '".$text;
				}
				if($length>150) { $reason .= '...'; } # we've only pasted part of the text
				$reason.="'";
			}
		}

		return $this->confirmDelete( '', $reason );
	}

	/**
	 * Output deletion confirmation dialog
	 */
	function confirmDelete( $par, $reason ) {
		global $wgOut;

		wfDebug( "Article::confirmDelete\n" );

		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'deletesub', $sub ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addWikiText( wfMsg( 'confirmdeletetext' ) );

		$formaction = $this->mTitle->escapeLocalURL( 'action=delete' . $par );

		$confirm = htmlspecialchars( wfMsg( 'confirm' ) );
		$check = htmlspecialchars( wfMsg( 'confirmcheck' ) );
		$delcom = htmlspecialchars( wfMsg( 'deletecomment' ) );

		$wgOut->addHTML( "
<form id='deleteconfirm' method='post' action=\"{$formaction}\">
	<table border='0'>
		<tr>
			<td align='right'>
				<label for='wpReason'>{$delcom}:</label>
			</td>
			<td align='left'>
				<input type='text' size='60' name='wpReason' id='wpReason' value=\"" . htmlspecialchars( $reason ) . "\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align='right'>
				<input type='checkbox' name='wpConfirm' value='1' id='wpConfirm' />
			</td>
			<td>
				<label for='wpConfirm'>{$check}</label>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='submit' name='wpConfirmB' value=\"{$confirm}\" />
			</td>
		</tr>
	</table>
</form>\n" );

		$wgOut->returnToMain( false );
	}


	/**
	 * Perform a deletion and output success or failure messages
	 */
	function doDelete( $reason ) {
		global $wgOut, $wgUser, $wgContLang;
		$fname = 'Article::doDelete';
		wfDebug( $fname."\n" );

		if ( $this->doDeleteArticle( $reason ) ) {
			$deleted = $this->mTitle->getPrefixedText();

			$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );

			$sk = $wgUser->getSkin();
			$loglink = $sk->makeKnownLink( $wgContLang->getNsText( NS_PROJECT ) .
			  ':' . wfMsgForContent( 'dellogpage' ), wfMsg( 'deletionlog' ) );

			$text = wfMsg( 'deletedtext', $deleted, $loglink );

			$wgOut->addHTML( '<p>' . $text . "</p>\n" );
			$wgOut->returnToMain( false );
		} else {
			$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
		}
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 * Returns success
	 */
	function doDeleteArticle( $reason ) {
		global $wgUser;
		global  $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer;

		$fname = 'Article::doDeleteArticle';
		wfDebug( $fname."\n" );

		$dbw =& wfGetDB( DB_MASTER );
		$ns = $this->mTitle->getNamespace();
		$t = $this->mTitle->getDBkey();
		$id = $this->mTitle->getArticleID();

		if ( $t == '' || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, -$this->isCountable( $this->getContent( true ) ) );
		array_push( $wgDeferredUpdateList, $u );

		$linksTo = $this->mTitle->getLinksTo();

		# Squid purging
		if ( $wgUseSquid ) {
			$urls = array(
				$this->mTitle->getInternalURL(),
				$this->mTitle->getInternalURL( 'history' )
			);
			foreach ( $linksTo as $linkTo ) {
				$urls[] = $linkTo->getInternalURL();
			}

			$u = new SquidUpdate( $urls );
			array_push( $wgDeferredUpdateList, $u );

		}

		# Client and file cache invalidation
		Title::touchArray( $linksTo );

		# Move article and history to the "archive" table
		$archiveTable = $dbw->tableName( 'archive' );
		$oldTable = $dbw->tableName( 'old' );
		$curTable = $dbw->tableName( 'cur' );
		$recentchangesTable = $dbw->tableName( 'recentchanges' );
		$linksTable = $dbw->tableName( 'links' );
		$brokenlinksTable = $dbw->tableName( 'brokenlinks' );

		$dbw->insertSelect( 'archive', 'cur',
			array(
				'ar_namespace' => 'cur_namespace',
				'ar_title' => 'cur_title',
				'ar_text' => 'cur_text',
				'ar_comment' => 'cur_comment',
				'ar_user' => 'cur_user',
				'ar_user_text' => 'cur_user_text',
				'ar_timestamp' => 'cur_timestamp',
				'ar_minor_edit' => 'cur_minor_edit',
				'ar_flags' => 0,
			), array(
				'cur_namespace' => $ns,
				'cur_title' => $t,
			), $fname
		);

		$dbw->insertSelect( 'archive', 'old',
			array(
				'ar_namespace' => 'old_namespace',
				'ar_title' => 'old_title',
				'ar_text' => 'old_text',
				'ar_comment' => 'old_comment',
				'ar_user' => 'old_user',
				'ar_user_text' => 'old_user_text',
				'ar_timestamp' => 'old_timestamp',
				'ar_minor_edit' => 'old_minor_edit',
				'ar_flags' => 'old_flags'
			), array(
				'old_namespace' => $ns,
				'old_title' => $t,
			), $fname
		);

		# Now that it's safely backed up, delete it

		$dbw->delete( 'cur', array( 'cur_namespace' => $ns, 'cur_title' => $t ), $fname );
		$dbw->delete( 'old', array( 'old_namespace' => $ns, 'old_title' => $t ), $fname );
		$dbw->delete( 'recentchanges', array( 'rc_namespace' => $ns, 'rc_title' => $t ), $fname );

		# Finally, clean up the link tables
		$t = $this->mTitle->getPrefixedDBkey();

		Article::onArticleDelete( $this->mTitle );

		# Insert broken links
		$brokenLinks = array();
		foreach ( $linksTo as $titleObj ) {
			# Get article ID. Efficient because it was loaded into the cache by getLinksTo().
			$linkID = $titleObj->getArticleID();
			$brokenLinks[] = array( 'bl_from' => $linkID, 'bl_to' => $t );
		}
		$dbw->insertArray( 'brokenlinks', $brokenLinks, $fname, 'IGNORE' );

		# Delete live links
		$dbw->delete( 'links', array( 'l_to' => $id ) );
		$dbw->delete( 'links', array( 'l_from' => $id ) );
		$dbw->delete( 'imagelinks', array( 'il_from' => $id ) );
		$dbw->delete( 'brokenlinks', array( 'bl_from' => $id ) );
		$dbw->delete( 'categorylinks', array( 'cl_from' => $id ) );

		# Log the deletion
		$log = new LogPage( 'delete' );
		$log->addEntry( 'delete', $this->mTitle, $reason );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );
		$this->mTitle->mArticleID = 0;
		return true;
	}

	/**
	 * Revert a modification
	 */
	function rollback() {
		global $wgUser, $wgOut, $wgRequest;
		$fname = 'Article::rollback';

		if ( ! $wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage( $this->getContent( true ) );
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );

		# Enhanced rollback, marks edits rc_bot=1
		$bot = $wgRequest->getBool( 'bot' );

		# Replace all this user's current edits with the next one down
		$tt = $this->mTitle->getDBKey();
		$n = $this->mTitle->getNamespace();

		# Get the last editor, lock table exclusively
		$s = $dbw->getArray( 'cur',
			array( 'cur_id','cur_user','cur_user_text','cur_comment' ),
			array( 'cur_title' => $tt, 'cur_namespace' => $n ),
			$fname, 'FOR UPDATE'
		);
		if( $s === false ) {
			# Something wrong
			$wgOut->addHTML( wfMsg( 'notanarticle' ) );
			return;
		}
		$ut = $dbw->strencode( $s->cur_user_text );
		$uid = $s->cur_user;
		$pid = $s->cur_id;

		$from = str_replace( '_', ' ', $wgRequest->getVal( 'from' ) );
		if( $from != $s->cur_user_text ) {
			$wgOut->setPageTitle(wfmsg('rollbackfailed'));
			$wgOut->addWikiText( wfMsg( 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText()),
				htmlspecialchars( $from ),
				htmlspecialchars( $s->cur_user_text ) ) );
			if($s->cur_comment != '') {
				$wgOut->addHTML(
					wfMsg('editcomment',
					htmlspecialchars( $s->cur_comment ) ) );
			}
			return;
		}

		# Get the last edit not by this guy
		$s = $dbw->getArray( 'old',
			array( 'old_text','old_user','old_user_text','old_timestamp','old_flags' ),
			array(
				'old_namespace' => $n,
				'old_title' => $tt,
				"old_user <> {$uid} OR old_user_text <> '{$ut}'"
			), $fname, array( 'FOR UPDATE', 'USE INDEX' => 'name_title_timestamp' )
		);
		if( $s === false ) {
			# Something wrong
			$wgOut->setPageTitle(wfMsg('rollbackfailed'));
			$wgOut->addHTML( wfMsg( 'cantrollback' ) );
			return;
		}

		if ( $bot ) {
			# Mark all reverted edits as bot
			$dbw->updateArray( 'recentchanges',
				array( /* SET */
					'rc_bot' => 1
				), array( /* WHERE */
					'rc_user' => $uid,
					"rc_timestamp > '{$s->old_timestamp}'",
				), $fname
			);
		}

		# Save it!
		$newcomment = wfMsg( 'revertpage', $s->old_user_text, $from );
		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( '<h2>' . $newcomment . "</h2>\n<hr />\n" );
		$this->updateArticle( Article::getRevisionText( $s ), $newcomment, 1, $this->mTitle->userIsWatching(), $bot );
		Article::onArticleEdit( $this->mTitle );
		$wgOut->returnToMain( false );
	}


	/**
	 * Do standard deferred updates after page view
	 * @private
	 */
	function viewUpdates() {
		global $wgDeferredUpdateList;
		if ( 0 != $this->getID() ) {
			global $wgDisableCounters;
			if( !$wgDisableCounters ) {
				Article::incViewCount( $this->getID() );
				$u = new SiteStatsUpdate( 1, 0, 0 );
				array_push( $wgDeferredUpdateList, $u );
			}
		}
		$u = new UserTalkUpdate( 0, $this->mTitle->getNamespace(),
		  $this->mTitle->getDBkey() );
		array_push( $wgDeferredUpdateList, $u );
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Every 1000th edit, prune the recent changes table.
	 * @private
	 * @param string $text
	 */
	function editUpdates( $text ) {
		global $wgDeferredUpdateList, $wgDBname, $wgMemc;
		global $wgMessageCache;

		wfSeedRandom();
		if ( 0 == mt_rand( 0, 999 ) ) {
			$dbw =& wfGetDB( DB_MASTER );
			$cutoff = $dbw->timestamp( time() - ( 7 * 86400 ) );
			$sql = "DELETE FROM recentchanges WHERE rc_timestamp < '{$cutoff}'";
			$dbw->query( $sql );
		}
		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		$adj = $this->mCountAdjustment;

		if ( 0 != $id ) {
			$u = new LinksUpdate( $id, $title );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SiteStatsUpdate( 0, 1, $adj );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SearchUpdate( $id, $title, $text );
			array_push( $wgDeferredUpdateList, $u );

			$u = new UserTalkUpdate( 1, $this->mTitle->getNamespace(), $shortTitle );
			array_push( $wgDeferredUpdateList, $u );

			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$wgMessageCache->replace( $shortTitle, $text );
			}
		}
	}

	/**
	 * @todo document this function
	 * @private
	 */
	function setOldSubtitle() {
		global $wgLang, $wgOut, $wgUser;

		$td = $wgLang->timeanddate( $this->mTimestamp, true );
		$sk = $wgUser->getSkin();
		$lnk = $sk->makeKnownLinkObj ( $this->mTitle, wfMsg( 'currentrevisionlink' ) );
		$r = wfMsg( 'revisionasofwithlink', $td, $lnk );
		$wgOut->setSubtitle( $r );
	}

	/**
	 * This function is called right before saving the wikitext,
	 * so we can do things like signatures and links-in-context.
	 *
	 * @param string $text
	 */
	function preSaveTransform( $text ) {
		global $wgParser, $wgUser;
		return $wgParser->preSaveTransform( $text, $this->mTitle, $wgUser, ParserOptions::newFromUser( $wgUser ) );
	}

	/* Caching functions */

	/**
	 * checkLastModified returns true if it has taken care of all
	 * output to the client that is necessary for this request.
	 * (that is, it has sent a cached version of the page)
	 */
	function tryFileCache() {
		static $called = false;
		if( $called ) {
			wfDebug( " tryFileCache() -- called twice!?\n" );
			return;
		}
		$called = true;
		if($this->isFileCacheable()) {
			$touched = $this->mTouched;
			if( $this->mTitle->getPrefixedDBkey() == wfMsg( 'mainpage' ) ) {
				# Expire the main page quicker
				$expire = wfUnix2Timestamp( time() - 3600 );
				$touched = max( $expire, $touched );
			}
			$cache = new CacheManager( $this->mTitle );
			if($cache->isFileCacheGood( $touched )) {
				global $wgOut;
				wfDebug( " tryFileCache() - about to load\n" );
				$cache->loadFromFileCache();
				return true;
			} else {
				wfDebug( " tryFileCache() - starting buffer\n" );
				ob_start( array(&$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( " tryFileCache() - not cacheable\n" );
		}
	}
	
	/**
	 * Check if the page can be cached
	 * @return bool
	 */
	function isFileCacheable() {
		global $wgUser, $wgUseFileCache, $wgShowIPinHeader, $wgRequest;
		extract( $wgRequest->getValues( 'action', 'oldid', 'diff', 'redirect', 'printable' ) );

		return $wgUseFileCache
			and (!$wgShowIPinHeader)
			and ($this->getID() != 0)
			and ($wgUser->getId() == 0)
			and (!$wgUser->getNewtalk())
			and ($this->mTitle->getNamespace() != NS_SPECIAL )
			and (empty( $action ) || $action == 'view')
			and (!isset($oldid))
			and (!isset($diff))
			and (!isset($redirect))
			and (!isset($printable))
			and (!$this->mRedirectedFrom);
	}

	/**
	 * Loads cur_touched and returns a value indicating if it should be used
	 *
	 */
	function checkTouched() {
		$fname = 'Article::checkTouched';
		$id = $this->getID();
		$dbr =& $this->getDB();
		$s = $dbr->getArray( 'cur', array( 'cur_touched', 'cur_is_redirect' ),
			array( 'cur_id' => $id ), $fname, $this->getSelectOptions() );
		if( $s !== false ) {
			$this->mTouched = wfTimestamp(TS_MW,$s->cur_touched);
			return !$s->cur_is_redirect;
		} else {
			return false;
		}
	}

	/**
	 * Edit an article without doing all that other stuff
	 *
	 * @param string $text text submitted
	 * @param string $comment comment submitted
	 * @param integer $minor whereas it's a minor modification
	 */
	function quickEdit( $text, $comment = '', $minor = 0 ) {
		global $wgUser;
		$fname = 'Article::quickEdit';
		wfProfileIn( $fname );

		$dbw =& wfGetDB( DB_MASTER );
		$ns = $this->mTitle->getNamespace();
		$dbkey = $this->mTitle->getDBkey();
		$encDbKey = $dbw->strencode( $dbkey );
		$timestamp = wfTimestampNow();

		# Save to history
		$dbw->insertSelect( 'old', 'cur',
			array(
				'old_namespace' => 'cur_namespace',
				'old_title' => 'cur_title',
				'old_text' => 'cur_text',
				'old_comment' => 'cur_comment',
				'old_user' => 'cur_user',
				'old_user_text' => 'cur_user_text',
				'old_timestamp' => 'cur_timestamp',
				'inverse_timestamp' => '99999999999999-cur_timestamp',
			), array(
				'cur_namespace' => $ns,
				'cur_title' => $dbkey,
			), $fname
		);

		# Use the affected row count to determine if the article is new
		$numRows = $dbw->affectedRows();

		# Make an array of fields to be inserted
		$fields = array(
			'cur_text' => $text,
			'cur_timestamp' => $timestamp,
			'cur_user' => $wgUser->getID(),
			'cur_user_text' => $wgUser->getName(),
			'inverse_timestamp' => wfInvertTimestamp( $timestamp ),
			'cur_comment' => $comment,
			'cur_is_redirect' => $this->isRedirect( $text ) ? 1 : 0,
			'cur_minor_edit' => intval($minor),
			'cur_touched' => $dbw->timestamp($timestamp),
		);

		if ( $numRows ) {
			# Update article
			$fields['cur_is_new'] = 0;
			$dbw->updateArray( 'cur', $fields, array( 'cur_namespace' => $ns, 'cur_title' => $dbkey ), $fname );
		} else {
			# Insert new article
			$fields['cur_is_new'] = 1;
			$fields['cur_namespace'] = $ns;
			$fields['cur_title'] = $dbkey;
			$fields['cur_random'] = $rand = number_format( mt_rand() / mt_getrandmax(), 12, '.', '' );
			$dbw->insertArray( 'cur', $fields, $fname );
		}
		wfProfileOut( $fname );
	}

	/**
	 * Used to increment the view counter
	 *
	 * @static
	 * @param integer $id article id
	 */
	function incViewCount( $id ) {
		$id = intval( $id );
		global $wgHitcounterUpdateFreq;

		$dbw =& wfGetDB( DB_MASTER );
		$curTable = $dbw->tableName( 'cur' );
		$hitcounterTable = $dbw->tableName( 'hitcounter' );
		$acchitsTable = $dbw->tableName( 'acchits' );

		if( $wgHitcounterUpdateFreq <= 1 ){ //
			$dbw->query( "UPDATE $curTable SET cur_counter = cur_counter + 1 WHERE cur_id = $id" );
			return;
		}

		# Not important enough to warrant an error page in case of failure
		$oldignore = $dbw->ignoreErrors( true );

		$dbw->query( "INSERT INTO $hitcounterTable (hc_id) VALUES ({$id})" );

		$checkfreq = intval( $wgHitcounterUpdateFreq/25 + 1 );
		if( (rand() % $checkfreq != 0) or ($dbw->lastErrno() != 0) ){
			# Most of the time (or on SQL errors), skip row count check
			$dbw->ignoreErrors( $oldignore );
			return;
		}

		$res = $dbw->query("SELECT COUNT(*) as n FROM $hitcounterTable");
		$row = $dbw->fetchObject( $res );
		$rown = intval( $row->n );
		if( $rown >= $wgHitcounterUpdateFreq ){
			wfProfileIn( 'Article::incViewCount-collect' );
			$old_user_abort = ignore_user_abort( true );

			$dbw->query("LOCK TABLES $hitcounterTable WRITE");
			$dbw->query("CREATE TEMPORARY TABLE $acchitsTable TYPE=HEAP ".
				"SELECT hc_id,COUNT(*) AS hc_n FROM $hitcounterTable ".
				'GROUP BY hc_id');
			$dbw->query("DELETE FROM $hitcounterTable");
			$dbw->query('UNLOCK TABLES');
			$dbw->query("UPDATE $curTable,$acchitsTable SET cur_counter=cur_counter + hc_n ".
				'WHERE cur_id = hc_id');
			$dbw->query("DROP TABLE $acchitsTable");

			ignore_user_abort( $old_user_abort );
			wfProfileOut( 'Article::incViewCount-collect' );
		}
		$dbw->ignoreErrors( $oldignore );
	}

	/**#@+
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 * 
	 * This is a good place to put code to clear caches, for instance.
	 * 
	 * This is called on page move and undelete, as well as edit
	 * @static
	 * @param $title_obj a title object
	 */

	function onArticleCreate($title_obj) {
		global $wgUseSquid, $wgDeferredUpdateList;

		$titles = $title_obj->getBrokenLinksTo();

		# Purge squid
		if ( $wgUseSquid ) {
			$urls = $title_obj->getSquidURLs();
			foreach ( $titles as $linkTitle ) {
				$urls[] = $linkTitle->getInternalURL();
			}
			$u = new SquidUpdate( $urls );
			array_push( $wgDeferredUpdateList, $u );
		}

		# Clear persistent link cache
		LinkCache::linksccClearBrokenLinksTo( $title_obj->getPrefixedDBkey() );
	}

	function onArticleDelete($title_obj) {
		LinkCache::linksccClearLinksTo( $title_obj->getArticleID() );
	}
	function onArticleEdit($title_obj) {
		LinkCache::linksccClearPage( $title_obj->getArticleID() );
	}
	/**#@-*/

	/**
	 * Info about this page
	 */
	function info() {
		global $wgUser, $wgTitle, $wgOut, $wgAllowPageInfo;
		$fname = 'Article::info';

		if ( !$wgAllowPageInfo ) {
			$wgOut->errorpage( 'nosuchaction', 'nosuchactiontext' );
			return;
		}

		$dbr =& $this->getDB();

		$basenamespace = $wgTitle->getNamespace() & (~1);
		$cur_clause = array( 'cur_title' => $wgTitle->getDBkey(), 'cur_namespace' => $basenamespace );
		$old_clause = array( 'old_title' => $wgTitle->getDBkey(), 'old_namespace' => $basenamespace );
		$wl_clause  = array( 'wl_title' => $wgTitle->getDBkey(), 'wl_namespace' => $basenamespace );
		$fullTitle = $wgTitle->makeName($basenamespace, $wgTitle->getDBKey());
		$wgOut->setPagetitle(  $fullTitle );
		$wgOut->setSubtitle( wfMsg( 'infosubtitle' ));

		# first, see if the page exists at all.
		$exists = $dbr->selectField( 'cur', 'COUNT(*)', $cur_clause, $fname, $this->getSelectOptions() );
		if ($exists < 1) {
			$wgOut->addHTML( wfMsg('noarticletext') );
		} else {
			$numwatchers = $dbr->selectField( 'watchlist', 'COUNT(*)', $wl_clause, $fname,
				$this->getSelectOptions() );
			$wgOut->addHTML( "<ul><li>" . wfMsg("numwatchers", $numwatchers) . '</li>' );
			$old = $dbr->selectField( 'old', 'COUNT(*)', $old_clause, $fname, $this->getSelectOptions() );
			$wgOut->addHTML( "<li>" . wfMsg('numedits', $old + 1) . '</li>');

			# to find number of distinct authors, we need to do some
			# funny stuff because of the cur/old table split:
			# - first, find the name of the 'cur' author
			# - then, find the number of *other* authors in 'old'

			# find 'cur' author
			$cur_author = $dbr->selectField( 'cur', 'cur_user_text', $cur_clause, $fname,
				$this->getSelectOptions() );

			# find number of 'old' authors excluding 'cur' author
			$authors = $dbr->selectField( 'old', 'COUNT(DISTINCT old_user_text)',
				$old_clause + array( 'old_user_text<>' . $dbr->addQuotes( $cur_author ) ), $fname,
				$this->getSelectOptions() ) + 1;

			# now for the Talk page ...
			$cur_clause = array( 'cur_title' => $wgTitle->getDBkey(), 'cur_namespace' => $basenamespace+1 );
			$old_clause = array( 'old_title' => $wgTitle->getDBkey(), 'old_namespace' => $basenamespace+1 );

			# does it exist?
			$exists = $dbr->selectField( 'cur', 'COUNT(*)', $cur_clause, $fname, $this->getSelectOptions() );

			# number of edits
			if ($exists > 0) {
				$old = $dbr->selectField( 'old', 'COUNT(*)', $old_clause, $fname, $this->getSelectOptions() );
				$wgOut->addHTML( '<li>' . wfMsg("numtalkedits", $old + 1) . '</li>');
			}
			$wgOut->addHTML( '<li>' . wfMsg("numauthors", $authors) . '</li>' );

			# number of authors
			if ($exists > 0) {
				$cur_author = $dbr->selectField( 'cur', 'cur_user_text', $cur_clause, $fname,
					$this->getSelectOptions() );
				$authors = $dbr->selectField( 'cur', 'COUNT(DISTINCT old_user_text)',
					$old_clause + array( 'old_user_text<>' . $dbr->addQuotes( $cur_author ) ),
					$fname, $this->getSelectOptions() );

				$wgOut->addHTML( '<li>' . wfMsg('numtalkauthors', $authors) . '</li></ul>' );
			}
		}
	}
}

?>
