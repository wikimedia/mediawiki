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
class SkinStandard extends SkinLegacy {
	var $skinname = 'standard', $stylename = 'standard',
		$template = 'StandardTemplate';

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'skins.standard' );

		$qb = $this->qbSetting();
		$rules = array();

		if ( 2 == $qb ) { # Right
			$rules[] = "/* @noflip */#quickbar { position: absolute; top: 4px; right: 4px; border-left: 2px solid #000000; }";
			$rules[] = "/* @noflip */#article, #mw-data-after-content { margin-left: 4px; margin-right: 152px; }";
			$rules[] = "/* @noflip */#topbar, #footer { margin-right: 152px; }";
		} elseif ( 1 == $qb || 3 == $qb ) {
			$rules[] = "/* @noflip */#quickbar { position: absolute; top: 4px; left: 4px; border-right: 1px solid gray; }";
			$rules[] = "/* @noflip */#article, #mw-data-after-content { margin-left: 152px; margin-right: 4px; }";
			$rules[] = "/* @noflip */#topbar, #footer { margin-left: 152px; }";
			if( 3 == $qb ) {
				$rules[] = "/* @noflip */#quickbar { position: fixed; padding: 4px; }";
			}
		} elseif ( 4 == $qb ) {
			$rules[] = "/* @noflip */#quickbar { position: fixed; right: 0px; top: 0px; padding: 4px;}";
			$rules[] = "/* @noflip */#quickbar { border-right: 1px solid gray; }";
			$rules[] = "/* @noflip */#article, #mw-data-after-content { margin-right: 152px; margin-left: 4px; }";
			$rules[] = "/* @noflip */#topbar, #footer { margin-right: 152px; }";
		}
 		$style = implode( "\n", $rules );
		$out->addInlineStyle( $style, 'flip' );
	}

}

class StandardTemplate extends LegacyTemplate {

	/**
	 * @return string
	 */
	function doAfterContent() {
		wfProfileIn( __METHOD__ );
		wfProfileIn( __METHOD__ . '-1' );

		$s = "\n</div><br style=\"clear:both\" />\n";
		$s .= "\n<div id='footer'>";
		$s .= '<table border="0" cellspacing="0"><tr>';

		wfProfileOut( __METHOD__ . '-1' );
		wfProfileIn( __METHOD__ . '-2' );
		$l = $this->getSkin()->getLang()->alignStart();
		$s .= "<td class='bottom' align='$l' valign='top'>";

		$s .= $this->bottomLinks();
		$s .= "\n<br />" . $this->getSkin()->getLang()->pipeList( array(
			$this->getSkin()->mainPageLink(),
			$this->getSkin()->aboutLink(),
			Linker::specialLink( 'Recentchanges' ),
			$this->searchForm() ) )
			. '<br /><span id="pagestats">' . $this->pageStats() . '</span>';

		$s .= '</td>';
		$s .= "</tr></table>\n</div>\n</div>\n";

		wfProfileOut( __METHOD__ . '-2' );
		wfProfileIn( __METHOD__ . '-3' );
		if ( $this->getSkin()->qbSetting() != 0 ) {
			$s .= $this->quickBar();
		}
		wfProfileOut( __METHOD__ . '-3' );
		wfProfileOut( __METHOD__ );
		return $s;
	}

	/**
	 * @return string
	 */
	function quickBar() {
		global $wgContLang;

		wfProfileIn( __METHOD__ );

		$action = $this->getSkin()->getRequest()->getText( 'action' );
		$wpPreview = $this->getSkin()->getRequest()->getBool( 'wpPreview' );
		$title = $this->getSkin()->getTitle();
		$tns = $title->getNamespace();

		$s = "\n<div id='quickbar'>";
		$s .= "\n" . $this->getSkin()->logoText() . "\n<hr class='sep' />";

		$sep = "\n<br />";

		# Use the first heading from the Monobook sidebar as the "browse" section
		$bar = $this->getSkin()->buildSidebar();
		unset( $bar['SEARCH'] );
		unset( $bar['LANGUAGES'] );
		unset( $bar['TOOLBOX'] );

		$barnumber = 1;
		foreach ( $bar as $browseLinks ) {
			if ( $barnumber > 1 ) {
				$s .= "\n<hr class='sep' />";
			} 
			foreach ( $browseLinks as $link ) {
				if ( $link['text'] != '-' ) {
					$s .= "<a href=\"{$link['href']}\">" .
						htmlspecialchars( $link['text'] ) . '</a>' . $sep;
				}
			}
			if ( $barnumber == 1 ) {
				// only show watchlist link if logged in
				if( $this->data['loggedin'] ) {
					$s.= Linker::specialLink( 'Watchlist' ) ;
					$s .= $sep . Linker::linkKnown(
						SpecialPage::getTitleFor( 'Contributions' ),
						wfMsg( 'mycontris' ),
						array(),
						array( 'target' => $this->data['username'] )
					);
				}
			}
			$barnumber = $barnumber + 1;
		}

		$s .= "\n<hr class='sep' />";
		$articleExists = $title->getArticleId();
		if ( $this->data['isarticle'] || $action == 'edit' || $action == 'history' || $wpPreview ) {
			if( $this->data['isarticle'] ) {
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

					$link = $title->getText();
					$nstext = $wgContLang->getNsText( $tns );
					if( $nstext ) { # add namespace if necessary
						$link = $nstext . ':' . $link;
					}

					$s .= Linker::link( Title::newFromText( $link ), $text );
				} elseif( $title->getNamespace() != NS_SPECIAL ) {
					# we just throw in a "New page" text to tell the user that he's in edit mode,
					# and to avoid messing with the separator that is prepended to the next item
					$s .= '<strong>' . wfMsg( 'newpage' ) . '</strong>';
				}
			}

			# "Post a comment" link
			if( ( $title->isTalkPage() || $this->getSkin()->getOutput()->showNewSectionLink() ) && $action != 'edit' && !$wpPreview )
				$s .= '<br />' . Linker::link(
					$title,
					wfMsg( 'postcomment' ),
					array(),
					array(
						'action' => 'edit',
						'section' => 'new'
					)
				);

			/*
			watching could cause problems in edit mode:
			if user edits article, then loads "watch this article" in background and then saves
			article with "Watch this article" checkbox disabled, the article is transparently
			unwatched. Therefore we do not show the "Watch this page" link in edit mode
			*/
			if ( $this->data['loggedin'] && $articleExists ) {
				if( $action != 'edit' && $action != 'submit' ) {
					$s .= $sep . $this->watchThisPage();
				}
				if ( $title->userCan( 'edit' ) )
					$s .= $sep . $this->moveThisPage();
			}
			if ( $this->getSkin()->getUser()->isAllowed( 'delete' ) && $articleExists ) {
				$s .= $sep . $this->deleteThisPage() .
				$sep . $this->protectThisPage();
			}
			$s .= $sep . $this->talkLink();
			if( $articleExists && $action != 'history' ) {
				$s .= $sep . $this->historyLink();
			}
			$s .= $sep . $this->whatLinksHere();

			if( $this->getSkin()->getOutput()->isArticleRelated() ) {
				$s .= $sep . $this->watchPageLinksLink();
			}

			if (
				NS_USER == $title->getNamespace() ||
				$title->getNamespace() == NS_USER_TALK
			) {

				$id = User::idFromName( $title->getText() );
				$ip = User::isIP( $title->getText() );

				if( $id || $ip ){
					$s .= $sep . $this->userContribsLink();
				}
				if( $this->getSkin()->showEmailUser( $id ) ) {
					$s .= $sep . $this->emailUserLink();
				}
			}
			$s .= "\n<br /><hr class='sep' />";
		}

		if( UploadBase::isEnabled() && UploadBase::isAllowed( $this->getSkin()->getUser() ) === true ) {
			$s .= $this->getUploadLink() . $sep;
		}

		$s .= Linker::specialLink( 'Specialpages' );

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
