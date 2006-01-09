<?php
if (!defined('MEDIAWIKI')) die();
/**
 * A Special Page to replace Special:Imagelist and Special:Newimages
 *
 * @package MediaWiki
 * @subpackage Extensions
 *
 * @author Magnus Manske <magnus.manske@web.de>
 * @copyright Copyright © 2006, Magnus Manske
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once( 'ImageGallery.php' );

function wfSpecialFilelist ( $par, $specialPage , $force_gallery = true ) {

	class SpecialFilelist extends SpecialPage {

		var $dbr , $sk ;
	
		/**
		 * Constructor
		 */
		function SpecialFilelist() {
			SpecialPage::SpecialPage( 'Filelist' );
			$this->includable( false );
		}
		
		/**
		 * Get the SQL to hide bot-users
		 @param hide Hide the bots? (boolean)
		*/
		function getHideBotSQL ( $hide ) {
			if ( !$hide ) {
				# Don't hide bots
				return "" ;
			}
			
			global $wgGroupPermissions ;
			$botconds=array();
			foreach ($wgGroupPermissions as $groupname=>$perms) {
				if(array_key_exists('bot',$perms) && $perms['bot']) {
					$botconds[]="ug_group='$groupname'";
				}
			}
			$isbotmember=$this->dbr->makeList($botconds, LIST_OR);

			/** This join, in conjunction with WHERE ug_group
			    IS NULL, returns only those rows from IMAGE
			    where the uploading user is not a member of
			    a group which has the 'bot' permission set.
			*/
			$ug = $this->dbr->tableName('user_groups');
			$joinsql=" LEFT OUTER JOIN $ug ON img_user=ug_user AND ("
			  . $isbotmember.')';
			return $joinsql ;
		}
		
		/**
		 * Returns the latest timestamp for an image in the image table
		 @param params Parameters, not changed by this function (passed as refrence for speedup)
		*/
		function getTimeStamp ( &$params ) {
			$sql="SELECT img_timestamp from " . $params['imagetable'];
			if($params['hidebots']) {
				$sql .= $params['botsql'].' WHERE ug_group IS NULL';
			}
			if ( isset ( $params['user'] ) ) {
				$sql .= " AND img_user='" . $params['user']->getID() . "'" ;
			}
			$sql .= ' ORDER BY img_timestamp' ;
			$sql .= ' DESC' ;
			$sql .= ' LIMIT 1';
			$res = $this->dbr->query($sql, 'SpecialFilelist::getTimeStamp');
			$row = $this->dbr->fetchRow($res);
			if($row!==false) {
				$ts=$row[0];
			} else {
				$ts=false;
			}
			$this->dbr->freeResult($res);

			/** If we were clever, we'd use this to cache. */
			$latestTimestamp = wfTimestamp( TS_MW, $ts);
			return $latestTimestamp ;
		}
		
		/**
		 * Returns an array of images from the database
		 @param params Parameters, not changed by this function (passed as refrence for speedup)
		*/
		function getImages ( &$params ) {
			$where = array();
			$searchpar = '';
			if ( $params['match'] != '' ) {
				$nt = Title::newFromUrl( $params['match'] );
				if($nt ) {
					$m = $this->dbr->strencode( strtolower( $nt->getDBkey() ) );
					$m = str_replace( '%', "\\%", $m );
					$m = str_replace( '_', "\\_", $m );
					$where[] = "LCASE(img_name) LIKE '%{$m}%'";
					$searchpar = '&wpIlMatch=' . ( $params['match'] );
				}
			}

			if( $params['until'] != "" ) {
				$where[] = 'img_timestamp >= ' . $this->dbr->timestamp( $params['until'] );
			} else if ( $params['date'] != "" ) {
				$where[] = 'img_timestamp < ' . $this->dbr->timestamp( $params['date'] );
			}

			$sql = 'SELECT img_size, img_name, img_user, img_user_text,'.
			     'img_description,img_timestamp FROM ' . $params['imagetable'] ;

			# Hide the bots?
			if($params['hidebots']) {
				$sql .= $params['botsql'] ;
				$where[] = 'ug_group IS NULL' ;
			}
			
			# Single user?
			if ( isset ( $params['user'] ) ) {
				$where[] = "img_user='" . $params['user']->getID() . "'" ;
			}
			
			if(count($where)) {
				$sql.=' WHERE '.$this->dbr->makeList($where, LIST_AND);
			}
			$sql.=' ORDER BY img_timestamp '. ( $params['latestfirst'] ? '' : ' DESC' );
			$sql.=' LIMIT '.($params['limit']+1);
			$res = $this->dbr->query($sql, 'SpecialFilelist::getImages');

			/**
			 * We have to flip things around to get the last N after a certain date
			 */
			$images = array();
			while ( $s = $this->dbr->fetchObject( $res ) ) {
				if( $params['latestfirst'] ) {
					array_unshift( $images, $s );
				} else {
					array_push( $images, $s );
				}
			}
			$this->dbr->freeResult( $res );
			return $images ;		
		}
		
		/**
		 * Returns HTML for a gallery
		*/
		function makeGallery ( &$images , &$params ) {
			global $wgLang ;
			$gallery = new ImageGallery();
			$firstTimestamp = null;
			$lastTimestamp = null;
			$shownImages = 0;
			$params['therearemore'] = false ;
			foreach( $images as $s ) {
				if( ++$shownImages > $params['limit'] ) {
					# One extra just to test for whether to show a page link;
					# don't actually show it, but remember there are more.
					$params['therearemore'] = true ;
					break;
				}
	
				$name = $s->img_name;
				$ut = $s->img_user_text;
	
				$nt = Title::newFromText( $name, NS_IMAGE );
				$img = Image::newFromTitle( $nt );
				$ul = $this->sk->makeLinkObj( Title::makeTitle( NS_USER, $ut ), $ut );
		
				$gallery->add( $img, "$ul<br />\n<i>".$wgLang->timeanddate( $s->img_timestamp, true )."</i><br />\n" );
	
				$timestamp = wfTimestamp( TS_MW, $s->img_timestamp );
				if( empty( $firstTimestamp ) ) {
					$firstTimestamp = $timestamp;
				}
				$lastTimestamp = $timestamp;
			}
			$params['lasttimestamp'] = $lastTimestamp ;
			$params['firsttimestamp'] = $firstTimestamp ;
			return $gallery->toHTML() ;
		}

		/**
		 * Returns a list of files
		*/
		function makeList ( &$images , &$params ) {
			global $wgLang ;
			$firstTimestamp = null;
			$lastTimestamp = null;
			$shownImages = 0;
			$params['therearemore'] = false ;
			$out = "" ;
			foreach( $images as $s ) {
				if( ++$shownImages > $params['limit'] ) {
					# One extra just to test for whether to show a page link;
					# don't actually show it, but remember there are more.
					$params['therearemore'] = true ;
					break;
				}
				
				$name = $s->img_name;
				$ut = $s->img_user_text;
				if ( 0 == $s->img_user ) {
					$ul = $ut;
				} else {
					$ul = $this->sk->makeLinkObj( Title::makeTitle( NS_USER, $ut ), $ut );
				}
	
				$ilink = "<a href=\"" . htmlspecialchars( Image::imageUrl( $name ) ) .
				  "\">" . strtr(htmlspecialchars( $name ), '_', ' ') . "</a>";
	
				$nb = wfMsg( "nbytes", $wgLang->formatNum( $s->img_size ) );
				$l = "(" .
				  $this->sk->makeKnownLinkObj( Title::makeTitle( NS_IMAGE, $name ),
				  wfMsg( "imgdesc" ) ) .
				  ") {$ilink} . . {$nb} . . {$ul} . . " .
				  $wgLang->timeanddate( $s->img_timestamp, true );
			
				$l .= $this->sk->commentBlock( $s->img_description );
				$out .= $l . "<br />\n" ;

				$timestamp = wfTimestamp( TS_MW, $s->img_timestamp );
				if( empty( $firstTimestamp ) ) {
					$firstTimestamp = $timestamp;
				}
				$lastTimestamp = $timestamp;
			}
			$params['lasttimestamp'] = $lastTimestamp ;
			$params['firsttimestamp'] = $firstTimestamp ;
			return $out ;
		}

		/**
		 * Preparing parameter for URL
		*/
		function convertURLparams ( &$params ) {
			$p2 = array() ;
			if ( isset ( $p2['user'] ) ) {
				$p2['user'] = urlencode ( $p2['user']->getName() ) ;
			}
			
			if ( $params['gallery'] != true ) $p2['gallery'] = $params['gallery'] ? "1" : "0" ;
			if ( $params['hidebots'] != true ) $p2['hidebots'] = $params['hidebots'] ? "1" : "0" ;
			if ( $params['date'] != "" ) $p2['date'] = $params['date'] ;
			if ( $params['until'] != "" ) $p2['until'] = $params['until'] ;
			if ( $params['match'] != "" ) $p2['match'] = $params['match'] ;
			$p2['limit'] = $params['limit'] ;

			return $p2 ;
		}
		
		/**
		 * Return URL parameters
		*/
		function getURLparams ( &$params ) {
			$ret = "" ;
			foreach ( $params AS $k => $v ) {
				if ( $v == "" ) continue ;
				if ( $ret != "" ) {
					$ret .= "&" ;
				}
				$ret .= $k . "=" . urlencode ( $v ) ;
			}
			return $ret ;
		}

		/**
		 * Returns the form for keyword matching
		*/
		function matchform ( &$params ) {
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Filelist' );
			$action = $titleObj->escapeLocalURL();
			$sub = wfMsg( 'ilsubmit' );
			$p2 = $this->convertURLparams ( $params ) ;
			unset ( $p2['match'] ) ;
			$action .= "?" . $this->getURLparams ( $p2 ) ;
			$ret = "<form id=\"matchform\" method=\"post\" action=\"" .
			  "{$action}\">" .
			  "<input type='text' size='20' name=\"match\" value=\"" .
			  htmlspecialchars( $params['match'] ) . "\" /> " .
			  "<input type='submit' name=\"domatch\" value=\"{$sub}\" /></form>" ;
			return "<p>" . $ret . "</p>" ;
		}
		
		/**
		 * Returns the limits links
		*/
		function limits ( &$params ) {
			global $wgLang ;
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Filelist' );
			$ret = array () ;
			
			if ( $params['gallery'] ) {
				$al = array ( 12 , 36 , 48 , 60 ) ;
			} else {
				$al = array ( 10 , 25 , 50 , 100 ) ;
			}

			$p2 = $this->convertURLparams ( $params ) ;
			
			foreach ( $al AS $l ) {
				$p2['limit'] = $l ;
				$p = $this->getURLparams ( $p2 ) ;

				$ret[] = $this->sk->makeKnownLinkObj( $titleObj, $wgLang->formatNum( $l ), $p ) ;
			}

			$text = wfMsg( "showlast", implode ( " | " , $ret ), wfMsg('bydate') );
			return $text ;
		}
		
		/**
		 * Returns the option links
		*/
		function options ( &$params ) {
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Filelist' );
			$ret = array () ;

			$p2 = $this->convertURLparams ( $params ) ;
			$p2['hidebots'] = $params['hidebots'] ? "0" : "1" ;
			$bots = wfMsg( 'showhidebots', ($params['hidebots'] ? wfMsg('show') : wfMsg('hide'))) ;
			$ret[] = $this->sk->makeKnownLinkObj( $titleObj, $bots, $this->getURLparams ( $p2 ) );
			
			$p2 = $this->convertURLparams ( $params ) ;
			$p2['gallery'] = $params['gallery'] ? "0" : "1" ;
			$bots = $params['gallery'] ? wfMsg('filelist_show_list') : wfMsg('filelist_show_gallery') ;
			$ret[] = $this->sk->makeKnownLinkObj( $titleObj, $bots, $this->getURLparams ( $p2 ) );
			
			return "<p>" . implode ( " | " , $ret ) . "</p>" ;
		}
		
		/**
		 * Returns the previous/next links
		*/
		function prevnext ( &$params ) {
			global $wgLang ;
			$out = "" ;
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Filelist' );
				
			$prevLink = wfMsg( 'prevn', $wgLang->formatNum( $params['limit'] ) );
			if( $params['therearebefore'] ) {
				$p2 = $this->convertURLparams ( $params ) ;
				$p2['until'] = $params['firsttimestamp'] ;
				unset ( $p2['date'] ) ;
				$prevLink = $this->sk->makeKnownLinkObj( $titleObj, $prevLink, $this->getURLparams ( $p2 ) );
			}

			$nextLink = wfMsg( 'nextn', $wgLang->formatNum( $params['limit'] ) );
			if( $params['therearemore'] ) {
				$p2 = $this->convertURLparams ( $params ) ;
				$p2['date'] = $params['lasttimestamp'] ;
				unset ( $p2['until'] ) ;
				$nextLink = $this->sk->makeKnownLinkObj( $titleObj, $nextLink, $this->getURLparams ( $p2 ) );
			}
			
			$out .= $prevLink . " | " . $nextLink ;
			
			return "<p>" . $out . "</p>" ;
		}

		/**
		 * main()
		 */
		function execute( $par = null , $specialPage , $force_gallery = true ) {
			global $wgOut, $wgRequest, $wgUser;
			$this->dbr =& wfGetDB( DB_SLAVE );
			$this->sk = $wgUser->getSkin();
			
			# Setting a bunch of parameters to passed or default values; also some variables which makes them easier to pass to functions
			$params['gallery'] = $wgRequest->getBool ( 'gallery' , $force_gallery ) ;
			$params['hidebots'] = $wgRequest->getBool ( 'hidebots' , true ) ;
			$params['date'] = $wgRequest->getVal ( 'date' , "" ) ;
			$params['until'] = $wgRequest->getVal ( 'until' , "" ) ;
			$params['match'] = $wgRequest->getVal ( 'match' , "" ) ;
			$params['limit'] = $wgRequest->getInt ( 'limit' , ($params['gallery']?48:50) ) ;
			$params['user'] = urldecode ( $wgRequest->getVal ( 'user' , ($par==NULL?"":$par) ) ) ;
			$params['botsql'] = $this->getHideBotSQL ( $params['hidebots'] ) ;
			$params['imagetable'] = $this->dbr->tableName('image');
			
			# If "until" is set, "date" should be invalid; also, "latestfirst" should be true to force inverted 
			if ( $params['until'] != "" ) {
				$params['date'] = "" ;
				$params['latestfirst'] = true ;
			} else {
				$params['latestfirst'] = false ;
			}

			# Preventing full DB scan for single user; remove this and the following line once the user field has an index
			$params['user'] = "" ;
			
			# Set $user variable if there is a valid user requested
			if ( $params['user'] != "" ) {
				$user = User::newFromName ( $params['user'] ) ;
				if ( 0 != $user->getID() ) {
					$params['user'] = $user ;
				} else {
					unset ( $params['user'] ) ;
				}
				unset ( $user ) ;
			} else {
				unset ( $params['user'] ) ;
			}

			# Ths following depends on the user above, so don't move it upwards!
			$params['timestamp'] = $this->getTimeStamp ( $params ) ;
			
			$images = $this->getImages ( $params ) ;
			
			if ( $params['gallery'] ) {
				$between = $this->makeGallery ( $images , $params ) ;
			} else {
				$between = $this->makeList ( $images , $params ) ;
			}
			
			# This is strange
			$params['therearebefore'] = ( $params['firsttimestamp'] != $params['timestamp'] ) ;
			
			$noi = count ( $images ) > $params['limit'] ? $params['limit'] : count ( $images ) ;
			
			$out = '<p>' . wfMsgForContent ( "imagelisttext" , $noi , wfMsg('bydate') ) . '</p>' ;
			$out .= $this->matchform ( $params ) ;
			$out .= $this->options ( $params ) ;
			$out .= $this->limits ( $params ) ;
			$out .= $this->prevnext ( $params ) ;
			$out .= $between ;
			$out .= $this->prevnext ( $params ) ;
			
			
			$this->setHeaders();
			$wgOut->addHtml( $out );
		}
	} # End of class
	
	$sp = new SpecialFilelist ;
	$sp->execute( $par , $specialPage , $force_gallery) ;

}
