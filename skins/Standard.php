<?php
/**
 * Standard (a.k.a. Classic) skin: old MediaWiki default skin
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * @todo document
 * @ingroup Skins
 */
class SkinStandard extends Skin {

	/**
	 *
	 */
	function setupSkinUserCss( OutputPage $out ){
		global $wgContLang;
		$qb = $this->qbSetting();
		if ( 2 == $qb ) { # Right
			$rules[] = "#quickbar { position: absolute; top: 4px; right: 4px; border-left: 2px solid #000000; }";
			$rules[] = "#article, #mw-data-after-content { margin-left: 4px; margin-right: 152px; }";
		} elseif ( 1 == $qb || 3 == $qb ) {
			$rules[] = "#quickbar { position: absolute; top: 4px; left: 4px; border-right: 1px solid gray; }";
			$rules[] = "#article, #mw-data-after-content { margin-left: 152px; margin-right: 4px; }";
			if( 3 == $qb ) {
				$rules[] = "#quickbar { position: fixed; padding: 4px; }";
			}
		} elseif ( 4 == $qb ) {
			$rules[] = "#quickbar { position: fixed; right: 0px; top: 0px; padding: 4px;}";
			$rules[] = "#quickbar { border-right: 1px solid gray; }";
			$rules[] = "#article, #mw-data-after-content { margin-right: 152px; margin-left: 4px; }";
		}
 		$style = implode( "\n", $rules );
 		if ( $wgContLang->getDir() === 'rtl' ) {
 			$style = CSSJanus::transform( $style, true, false );
		}
		$out->addInlineStyle( $style );
		parent::setupSkinUserCss( $out );
	}

	function doAfterContent() {
		global $wgContLang, $wgLang;
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-1' );

		$s = "\n</div><br style=\"clear:both\" />\n";
		$s .= "\n<div id='footer'>";
		$s .= '<table border="0" cellspacing="0"><tr>';

		wfProfileOut( __METHOD__ . '-1' );
		wfProfileIn( __METHOD__ . '-2' );

		$qb = $this->qbSetting();
		$shove = ( $qb != 0 );
		$left = ( $qb == 1 || $qb == 3 );
		if( $wgContLang->isRTL() ) {
			$left = !$left;
		}

		if ( $shove && $left ) { # Left
			$s .= $this->getQuickbarCompensator();
		}
		wfProfileOut( __METHOD__ . '-2' );
		wfProfileIn( __METHOD__ . '-3' );
		$l = $wgContLang->alignStart();
		$s .= "<td class='bottom' align='$l' valign='top'>";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $wgLang->pipeList( array(
			$this->mainPageLink(),
			$this->aboutLink(),
			$this->specialLink( 'Recentchanges' ),
			$this->searchForm() ) )
			. '<br /><span id="pagestats">' . $this->pageStats() . '</span>';

		$s .= '</td>';
		if ( $shove && !$left ) { # Right
			$s .= $this->getQuickbarCompensator();
		}
		$s .= "</tr></table>\n</div>\n</div>\n";

		wfProfileOut( __METHOD__ . '-3' );
		wfProfileIn( __METHOD__ . '-4' );
		if ( 0 != $qb ) {
			$s .= $this->quickBar();
		}
		wfProfileOut( __METHOD__ . '-4' );
		wfProfileOut( __METHOD__ );
		return $s;
	}

	function quickBar() {
		global $wgOut, $wgUser, $wgRequest, $wgContLang;

		wfProfileIn( __METHOD__ );

		$action = $wgRequest->getText( 'action' );
		$wpPreview = $wgRequest->getBool( 'wpPreview' );
		$tns = $this->mTitle->getNamespace();

		$s = "\n<div id='quickbar'>";
		$s .= "\n" . $this->logoText() . "\n<hr class='sep' />";

		$sep = "\n<br />";

		# Use the first heading from the Monobook sidebar as the "browse" section
		$bar = $this->buildSidebar();
		unset( $bar['SEARCH'] );
		unset( $bar['LANGUAGES'] );
		unset( $bar['TOOLBOX'] );
		$browseLinks = reset( $bar );

		foreach ( $browseLinks as $link ) {
			if ( $link['text'] != '-' ) {
				$s .= "<a href=\"{$link['href']}\">" .
					htmlspecialchars( $link['text'] ) . '</a>' . $sep;
			}
		}

		if( $wgUser->isLoggedIn() ) {
			$s.= $this->specialLink( 'Watchlist' ) ;
			$s .= $sep . $this->linkKnown(
				SpecialPage::getTitleFor( 'Contributions' ),
				wfMsg( 'mycontris' ),
				array(),
				array( 'target' => $wgUser->getName() )
			);
		}
		// only show watchlist link if logged in
		$s .= "\n<hr class='sep' />";
		$articleExists = $this->mTitle->getArticleId();
		if ( $wgOut->isArticle() || $action == 'edit' || $action == 'history' || $wpPreview ) {
			if( $wgOut->isArticle() ) {
				$s .= '<strong>' . $this->editThisPage() . '</strong>';
			} else { # backlink to the article in edit or history mode
				if( $articleExists ){ # no backlink if no article
					switch( $tns ) {
						case NS_TALK:
						case NS_USER_TALK:
						case NS_PROJECT_TALK:
						case NS_FILE_TALK:
						case NS_MEDIAWIKI_TALK:
						case NS_TEMPLATE_TALK:
						case NS_HELP_TALK:
						case NS_CATEGORY_TALK:
							$text = wfMsg('viewtalkpage');
							break;
						case NS_MAIN:
							$text = wfMsg( 'articlepage' );
							break;
						case NS_USER:
							$text = wfMsg( 'userpage' );
							break;
						case NS_PROJECT:
							$text = wfMsg( 'projectpage' );
							break;
						case NS_FILE:
							$text = wfMsg( 'imagepage' );
							break;
						case NS_MEDIAWIKI:
							$text = wfMsg( 'mediawikipage' );
							break;
						case NS_TEMPLATE:
							$text = wfMsg( 'templatepage' );
							break;
						case NS_HELP:
							$text = wfMsg( 'viewhelppage' );
							break;
						case NS_CATEGORY:
							$text = wfMsg( 'categorypage' );
							break;
						default:
							$text = wfMsg( 'articlepage' );
					}

					$link = $this->mTitle->getText();
					$nstext = $wgContLang->getNsText( $tns );
					if( $nstext ) { # add namespace if necessary
						$link = $nstext . ':' . $link;
					}

					$s .= $this->link(
						Title::newFromText( $link ),
						$text
					);
				} elseif( $this->mTitle->getNamespace() != NS_SPECIAL ) {
					# we just throw in a "New page" text to tell the user that he's in edit mode,
					# and to avoid messing with the separator that is prepended to the next item
					$s .= '<strong>' . wfMsg( 'newpage' ) . '</strong>';
				}
			}

			# "Post a comment" link
			if( ( $this->mTitle->isTalkPage() || $wgOut->showNewSectionLink() ) && $action != 'edit' && !$wpPreview )
				$s .= '<br />' . $this->link(
					$this->mTitle,
					wfMsg( 'postcomment' ),
					array(),
					array(
						'action' => 'edit',
						'section' => 'new'
					),
					array( 'known', 'noclasses' )
				);

			/*
			watching could cause problems in edit mode:
			if user edits article, then loads "watch this article" in background and then saves
			article with "Watch this article" checkbox disabled, the article is transparently
			unwatched. Therefore we do not show the "Watch this page" link in edit mode
			*/
			if ( $wgUser->isLoggedIn() && $articleExists ) {
				if( $action != 'edit' && $action != 'submit' ) {
					$s .= $sep . $this->watchThisPage();
				}
				if ( $this->mTitle->userCan( 'edit' ) )
					$s .= $sep . $this->moveThisPage();
			}
			if ( $wgUser->isAllowed( 'delete' ) && $articleExists ) {
				$s .= $sep . $this->deleteThisPage() .
				$sep . $this->protectThisPage();
			}
			$s .= $sep . $this->talkLink();
			if( $articleExists && $action != 'history' ) {
				$s .= $sep . $this->historyLink();
			}
			$s .= $sep . $this->whatLinksHere();

			if( $wgOut->isArticleRelated() ) {
				$s .= $sep . $this->watchPageLinksLink();
			}

			if (
				NS_USER == $this->mTitle->getNamespace() ||
				$this->mTitle->getNamespace() == NS_USER_TALK
			) {

				$id = User::idFromName( $this->mTitle->getText() );
				$ip = User::isIP( $this->mTitle->getText() );

				if( $id || $ip ){
					$s .= $sep . $this->userContribsLink();
				}
				if( $this->showEmailUser( $id ) ) {
					$s .= $sep . $this->emailUserLink();
				}
			}
			$s .= "\n<br /><hr class='sep' />";
		}

		if( UploadBase::isEnabled() && UploadBase::isAllowed( $wgUser ) === true ) {
			$s .= $this->getUploadLink() . $sep;
		}

		$s .= $this->specialLink( 'Specialpages' );

		global $wgSiteSupportPage;
		if( $wgSiteSupportPage ) {
			$s .= "\n<br /><a href=\"" . htmlspecialchars( $wgSiteSupportPage ) .
			  '" class="internal">' . wfMsg( 'sitesupport' ) . '</a>';
		}

		$s .= "\n<br /></div>\n";
		wfProfileOut( __METHOD__ );
		return $s;
	}

}
