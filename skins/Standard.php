<?php
/**
 * See skin.doc
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinStandard extends Skin {

	/**
	 *
	 */
	function getHeadScripts() {
		global $wgStylePath;

		$s = parent::getHeadScripts();
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<script language='javascript' type='text/javascript' " .
			  "src='{$wgStylePath}/common/sticky.js'></script>\n";
		}
		return $s;
	}

	/**
	 *
	 */
	function getUserStyles() {
		global $wgStylePath;
		$s = '';
		if ( 3 == $this->qbSetting() ) { # Floating left
			$s .= "<style type='text/css'>\n" .
			  "@import '{$wgStylePath}/common/quickbar.css';\n</style>\n";
		}
		$s .= parent::getUserStyles();
		return $s;
	}

	/**
	 *
	 */
	function doGetUserStyles() {
		global $wgUser, $wgOut, $wgStylePath;

		$s = parent::doGetUserStyles();
		$qb = $this->qbSetting();

		if ( 2 == $qb ) { # Right
			$s .= "#quickbar { position: absolute; top: 4px; right: 4px; " .
			  "border-left: 2px solid #000000; }\n" .
			  "#article { margin-left: 4px; margin-right: 152px; }\n";
		} else if ( 1 == $qb || 3 == $qb ) {
			$s .= "#quickbar { position: absolute; top: 4px; left: 4px; " .
			  "border-right: 1px solid gray; }\n" .
			  "#article { margin-left: 152px; margin-right: 4px; }\n";
		}
		return $s;
	}

	/**
	 *
	 */
	function getBodyOptions() {
		$a = parent::getBodyOptions();

		if ( 3 == $this->qbSetting() ) { # Floating left
			$qb = "setup(\"quickbar\")";
			if($a["onload"]) {
				$a["onload"] .= ";$qb";
			} else {
				$a["onload"] = $qb;
			}
		}
		return $a;
	}

	function doAfterContent() {
		global $wgUser, $wgOut, $wgContLang;
		$fname =  'SkinStandard::doAfterContent';
		wfProfileIn( $fname );
		wfProfileIn( $fname.'-1' );

		$s = "\n</div><br style=\"clear:both\" />\n";
		$s .= "\n<div id='footer'>";
		$s .= '<table border="0" cellspacing="0"><tr>';

		wfProfileOut( $fname.'-1' );
		wfProfileIn( $fname.'-2' );

		$qb = $this->qbSetting();
		$shove = ($qb != 0);
		$left = ($qb == 1 || $qb == 3);
		if($wgContLang->isRTL()) $left = !$left;

		if ( $shove && $left ) { # Left
				$s .= $this->getQuickbarCompensator();
		}
		wfProfileOut( $fname.'-2' );
		wfProfileIn( $fname.'-3' );
		$l = $wgContLang->isRTL() ? 'right' : 'left';
		$s .= "<td class='bottom' align='$l' valign='top'>";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $this->mainPageLink()
		  . ' | ' . $this->aboutLink()
		  . ' | ' . $this->specialLink( 'recentchanges' )
		  . ' | ' . $this->searchForm()
		  . '<br /><span id="pagestats">' . $this->pageStats() . '</span>';

		$s .= "</td>";
		if ( $shove && !$left ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		wfProfileOut( $fname.'-3' );
		wfProfileIn( $fname.'-4' );
		if ( 0 != $qb ) { $s .= $this->quickBar(); }
		wfProfileOut( $fname.'-4' );
		wfProfileOut( $fname );
		return $s;
	}

	function quickBar() {
		global $wgOut, $wgTitle, $wgUser, $wgRequest, $wgContLang;
		global $wgDisableUploads, $wgRemoteUploads, $wgNavigationLinks;

		$fname =  'Skin::quickBar';
		wfProfileIn( $fname );

		$action = $wgRequest->getText( 'action' );
		$wpPreview = $wgRequest->getBool( 'wpPreview' );
		$tns=$wgTitle->getNamespace();

		$s = "\n<div id='quickbar'>";
		$s .= "\n" . $this->logoText() . "\n<hr class='sep' />";

		$sep = "\n<br />";

		foreach ( $wgNavigationLinks as $link ) {
			$msg = wfMsgForContent( $link['href'] );
			if ( $msg != '-' ) {
				$s .= '<a href="' . $this->makeInternalOrExternalUrl( $msg ) . '">' .
					wfMsg( $link['text'] ) . '</a>' . $sep;
			}
		}


		if ($wgUser->getID()) {
			$s.= $this->specialLink( 'watchlist' ) ;
			$s .= $sep . $this->makeKnownLink( $wgContLang->specialPage( 'Contributions' ),
				wfMsg( 'mycontris' ), 'target=' . wfUrlencode($wgUser->getName() ) );
		}
		// only show watchlist link if logged in
		$s .= "\n<hr class='sep' />";
		$articleExists = $wgTitle->getArticleId();
		if ( $wgOut->isArticle() || $action =='edit' || $action =='history' || $wpPreview) {
			if($wgOut->isArticle()) {
				$s .= '<strong>' . $this->editThisPage() . '</strong>';
			} else { # backlink to the article in edit or history mode
				if($articleExists){ # no backlink if no article
					switch($tns) {
						case 0:
						$text = wfMsg('articlepage');
						break;
						case 1:
						$text = wfMsg('viewtalkpage');
						break;
						case 2:
						$text = wfMsg('userpage');
						break;
						case 3:
						$text = wfMsg('viewtalkpage');
						break;
						case 4:
						$text = wfMsg('wikipediapage');
						break;
						case 5:
						$text = wfMsg('viewtalkpage');
						break;
						case 6:
						$text = wfMsg('imagepage');
						break;
						case 7:
						$text = wfMsg('viewtalkpage');
						break;
						default:
						$text= wfMsg('articlepage');
					}

					$link = $wgTitle->getText();
					if ($nstext = $wgContLang->getNsText($tns) ) { # add namespace if necessary
						$link = $nstext . ':' . $link ;
					}

					$s .= $this->makeLink( $link, $text );
				} elseif( $wgTitle->getNamespace() != Namespace::getSpecial() ) {
					# we just throw in a "New page" text to tell the user that he's in edit mode,
					# and to avoid messing with the separator that is prepended to the next item
					$s .= '<strong>' . wfMsg('newpage') . '</strong>';
				}

			}


			if( $tns%2 && $action!='edit' && !$wpPreview) {
				$s.= '<br />'.$this->makeKnownLink($wgTitle->getPrefixedText(),wfMsg('postcomment'),'action=edit&section=new');
			}

			/*
			watching could cause problems in edit mode:
			if user edits article, then loads "watch this article" in background and then saves
			article with "Watch this article" checkbox disabled, the article is transparently
			unwatched. Therefore we do not show the "Watch this page" link in edit mode
			*/
			if ( 0 != $wgUser->getID() && $articleExists) {
				if($action!='edit' && $action != 'submit' )
				{
					$s .= $sep . $this->watchThisPage();
				}
				if ( $wgTitle->userCanEdit() )
					$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isSysop() and $articleExists ) {
				$s .= $sep . $this->deleteThisPage() .
				$sep . $this->protectThisPage();
			}
			$s .= $sep . $this->talkLink();
			if ($articleExists && $action !='history') {
				$s .= $sep . $this->historyLink();
			}
			$s.=$sep . $this->whatLinksHere();

			if($wgOut->isArticleRelated()) {
				$s .= $sep . $this->watchPageLinksLink();
			}

			if ( Namespace::getUser() == $wgTitle->getNamespace()
			|| $wgTitle->getNamespace() == Namespace::getTalk(Namespace::getUser())
			) {

				$id=User::idFromName($wgTitle->getText());
				$ip=User::isIP($wgTitle->getText());

				if($id||$ip) {
					$s .= $sep . $this->userContribsLink();
				}
				if ( 0 != $wgUser->getID() ) {
					if($id) { # can only email real users
						$s .= $sep . $this->emailUserLink();
					}
				}
			}
			$s .= "\n<br /><hr class='sep' />";
		}

		if ( 0 != $wgUser->getID() && ( !$wgDisableUploads || $wgRemoteUploads ) ) {
			$s .= $this->specialLink( 'upload' ) . $sep;
		}
		$s .= $this->specialLink( 'specialpages' )
		  . $sep . $this->bugReportsLink();

		global $wgSiteSupportPage;
		if( $wgSiteSupportPage ) {
			$s .= "\n<br /><a href=\"" . htmlspecialchars( $wgSiteSupportPage ) .
			  '" class="internal">' . wfMsg( 'sitesupport' ) . '</a>';
		}

		$s .= "\n<br /></div>\n";
		wfProfileOut( $fname );
		return $s;
	}


}

?>
