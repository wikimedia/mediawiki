<?php
/**
 * Cologne Blue: A nicer-looking alternative to Standard.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @todo document
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
class SkinCologneBlue extends SkinTemplate {
	var $skinname = 'cologneblue', $stylename = 'cologneblue',
		$template = 'CologneBlueTemplate';
	var $useHeadElement = true;

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ){
		$out->addModuleStyles( 'mediawiki.legacy.shared' );
		$out->addModuleStyles( 'mediawiki.legacy.oldshared' );
		$out->addModuleStyles( 'skins.cologneblue' );
	}

}

class CologneBlueTemplate extends BaseTemplate {
	function execute() {
		$this->html( 'headelement' );
		echo $this->beforeContent();
		$this->html( 'bodytext' );
		echo "\n";
		echo $this->afterContent();
		$this->html( 'dataAfterContent' );
		$this->printTrail();
		echo "\n</body></html>";
	}
	

	/**
	 * Language/charset variant links for classic-style skins
	 * @return string
	 */
	function variantLinks() {
		$s = '';

		/* show links to different language variants */
		global $wgDisableLangConversion, $wgLang;

		$title = $this->getSkin()->getTitle();
		$lang = $title->getPageLanguage();
		$variants = $lang->getVariants();

		if ( !$wgDisableLangConversion && sizeof( $variants ) > 1
			&& !$title->isSpecialPage() ) {
			foreach ( $variants as $code ) {
				$varname = $lang->getVariantname( $code );

				if ( $varname == 'disable' ) {
					continue;
				}
				$s = $wgLang->pipeList( array(
					$s,
					'<a href="' . htmlspecialchars( $title->getLocalURL( 'variant=' . $code ) ) . '" lang="' . $code . '" hreflang="' . $code .  '">' . htmlspecialchars( $varname ) . '</a>'
				) );
			}
		}

		return $s;
	}

	/**
	 * Compatibility for extensions adding functionality through tabs.
	 * Eventually these old skins should be replaced with SkinTemplate-based
	 * versions, sigh...
	 * @return string
	 * @todo Exterminate! ...that, and replace it with normal SkinTemplate stuff
	 */
	function extensionTabLinks() {
		$tabs = array();
		$out = '';
		$s = array();
		wfRunHooks( 'SkinTemplateTabs', array( $this->getSkin(), &$tabs ) );
		foreach ( $tabs as $tab ) {
			$s[] = Xml::element( 'a',
				array( 'href' => $tab['href'] ),
				$tab['text'] );
		}

		if ( count( $s ) ) {
			global $wgLang;

			$out = wfMessage( 'pipe-separator' )->escaped();
			$out .= $wgLang->pipeList( $s );
		}

		return $out;
	}
	
	function otherLanguages() {
		global $wgOut, $wgLang, $wgHideInterlanguageLinks;

		if ( $wgHideInterlanguageLinks ) {
			return '';
		}

		$a = $wgOut->getLanguageLinks();

		if ( 0 == count( $a ) ) {
			return '';
		}

		$s = wfMessage( 'otherlanguages' )->text() . wfMessage( 'colon-separator' )->text();
		$first = true;

		if ( $wgLang->isRTL() ) {
			$s .= '<span dir="ltr">';
		}

		foreach ( $a as $l ) {
			if ( !$first ) {
				$s .= wfMessage( 'pipe-separator' )->escaped();
			}

			$first = false;

			$nt = Title::newFromText( $l );
			$text = Language::fetchLanguageName( $nt->getInterwiki() );

			$s .= Html::element( 'a',
				array( 'href' => $nt->getFullURL(), 'title' => $nt->getText(), 'class' => "external" ),
				$text == '' ? $l : $text );
		}

		if ( $wgLang->isRTL() ) {
			$s .= '</span>';
		}

		return $s;
	}

	// @fixed
	function pageTitleLinks() {
		global $wgLang;
		
		$s = array();
		$footlinks = $this->getFooterLinks();
		
		foreach ( $footlinks['places'] as $item ) {
			$s[] = $this->data[$item];
		}
		
		return $wgLang->pipeList( $s );
	}

	function printableLink() {
		global $wgOut, $wgRequest, $wgLang;

		$s = array();

		if ( !$wgOut->isPrintable() ) {
			$printurl = htmlspecialchars( $this->getSkin()->getTitle()->getLocalUrl(
				$wgRequest->appendQueryValue( 'printable', 'yes', true ) ) );
			$s[] = "<a href=\"$printurl\" rel=\"alternate\">"
				. wfMessage( 'printableversion' )->text() . '</a>';
		}

		if ( $wgOut->isSyndicated() ) {
			foreach ( $wgOut->getSyndicationLinks() as $format => $link ) {
				$feedurl = htmlspecialchars( $link );
				$s[] = "<a href=\"$feedurl\" rel=\"alternate\" type=\"application/{$format}+xml\""
						. " class=\"feedlink\">" . wfMessage( "feed-$format" )->escaped() . "</a>";
			}
		}
		return $wgLang->pipeList( $s );
	}

	/**
	 * Gets the h1 element with the page title.
	 * @return string
	 */
	function pageTitle() {
		global $wgOut;
		$s = '<h1 class="pagetitle"><span dir="auto">' . $wgOut->getPageTitle() . '</span></h1>';
		return $s;
		}

	function pageSubtitle() {
		global $wgOut;

		$sub = $wgOut->getSubtitle();

		if ( $sub == '' ) {
			global $wgExtraSubtitle;
			$sub = wfMessage( 'tagline' )->parse() . $wgExtraSubtitle;
		}

		$subpages = $this->getSkin()->subPageSubtitle();
		$sub .= !empty( $subpages ) ? "</p><p class='subpages'>$subpages" : '';
		$s = "<p class='subtitle'>{$sub}</p>\n";

		return $s;
	}

	function bottomLinks() {
		global $wgOut, $wgUser;
		$sep = wfMessage( 'pipe-separator' )->escaped() . "\n";

		$s = '';
		if ( $wgOut->isArticleRelated() ) {
			$element[] = '<strong>' . $this->editThisPage() . '</strong>';

			if ( $wgUser->isLoggedIn() ) {
				$element[] = $this->watchThisPage();
			}

			$element[] = $this->talkLink();
			$element[] = $this->historyLink();
			$element[] = $this->whatLinksHere();
			$element[] = $this->watchPageLinksLink();

			$title = $this->getSkin()->getTitle();

			if (
				$title->getNamespace() == NS_USER ||
				$title->getNamespace() == NS_USER_TALK
			) {
				$id = User::idFromName( $title->getText() );
				$ip = User::isIP( $title->getText() );

				# Both anons and non-anons have contributions list
				if ( $id || $ip ) {
					$element[] = $this->userContribsLink();
				}

				if ( $this->getSkin()->showEmailUser( $id ) ) {
					$element[] = $this->emailUserLink();
				}
			}

			$s = implode( $element, $sep );

			if ( $title->getArticleID() ) {
				$s .= "\n<br />";

				// Delete/protect/move links for privileged users
				if ( $wgUser->isAllowed( 'delete' ) ) {
					$s .= $this->deleteThisPage();
				}

				if ( $wgUser->isAllowed( 'protect' ) ) {
					$s .= $sep . $this->protectThisPage();
				}

				if ( $wgUser->isAllowed( 'move' ) ) {
					$s .= $sep . $this->moveThisPage();
				}
			}

			$s .= "<br />\n" . $this->otherLanguages();
		}

		return $s;
	}

	function editThisPage() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			$s = wfMessage( 'protectedpage' )->text();
		} else {
			$title = $this->getSkin()->getTitle();
			if ( $title->quickUserCan( 'edit' ) && $title->exists() ) {
				$t = wfMessage( 'editthispage' )->text();
			} elseif ( $title->quickUserCan( 'create' ) && !$title->exists() ) {
				$t = wfMessage( 'create-this-page' )->text();
			} else {
				$t = wfMessage( 'viewsource' )->text();
			}

			$s = Linker::linkKnown(
				$title,
				$t,
				array(),
				$this->getSkin()->editUrlOptions()
			);
		}

		return $s;
	}

	function deleteThisPage() {
		global $wgUser, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( !$diff ) && $wgUser->isAllowed( 'delete' ) ) {
			$t = wfMessage( 'deletethispage' )->text();

			$s = Linker::linkKnown(
				$title,
				$t,
				array(),
				array( 'action' => 'delete' )
			);
		} else {
			$s = '';
		}

		return $s;
	}

	function protectThisPage() {
		global $wgUser, $wgRequest;

		$diff = $wgRequest->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( ! $diff ) && $wgUser->isAllowed( 'protect' ) ) {
			if ( $title->isProtected() ) {
				$text = wfMessage( 'unprotectthispage' )->text();
				$query = array( 'action' => 'unprotect' );
			} else {
				$text = wfMessage( 'protectthispage' )->text();
				$query = array( 'action' => 'protect' );
			}

			$s = Linker::linkKnown(
				$title,
				$text,
				array(),
				$query
			);
		} else {
			$s = '';
		}

		return $s;
	}

	function watchThisPage() {
		global $wgOut, $wgUser;

		// Cache
		$title = $this->getSkin()->getTitle();

		if ( $wgOut->isArticleRelated() ) {
			if ( $wgUser->isWatched( $title ) ) {
				$text = wfMessage( 'unwatchthispage' )->text();
				$query = array(
					'action' => 'unwatch',
					'token' => UnwatchAction::getUnwatchToken( $title, $wgUser ),
				);
				$id = 'mw-unwatch-link';
			} else {
				$text = wfMessage( 'watchthispage' )->text();
				$query = array(
					'action' => 'watch',
					'token' => WatchAction::getWatchToken( $title, $wgUser ),
				);
				$id = 'mw-watch-link';
			}

			$s = Linker::linkKnown(
				$title,
				$text,
				array( 'id' => $id ),
				$query
			);
		} else {
			$s = wfMessage( 'notanarticle' )->text();
		}

		return $s;
	}

	function moveThisPage() {
		if ( $this->getSkin()->getTitle()->quickUserCan( 'move' ) ) {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Movepage' ),
				wfMessage( 'movethispage' )->text(),
				array(),
				array( 'target' => $this->getSkin()->getTitle()->getPrefixedDBkey() )
			);
		} else {
			// no message if page is protected - would be redundant
			return '';
		}
	}

	function historyLink() {
		return Linker::link(
			$this->getSkin()->getTitle(),
			wfMessage( 'history' )->escaped(),
			array( 'rel' => 'archives' ),
			array( 'action' => 'history' )
		);
	}

	function whatLinksHere() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Whatlinkshere', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
			wfMessage( 'whatlinkshere' )->escaped()
		);
	}

	function userContribsLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Contributions', $this->getSkin()->getTitle()->getDBkey() ),
			wfMessage( 'contributions' )->escaped()
		);
	}

	function emailUserLink() {
		return Linker::linkKnown(
			SpecialPage::getTitleFor( 'Emailuser', $this->getSkin()->getTitle()->getDBkey() ),
			wfMessage( 'emailuser' )->escaped()
		);
	}

	function watchPageLinksLink() {
		global $wgOut;

		if ( !$wgOut->isArticleRelated() ) {
			return wfMessage( 'parentheses', wfMessage( 'notanarticle' )->text() )->escaped();
		} else {
			return Linker::linkKnown(
				SpecialPage::getTitleFor( 'Recentchangeslinked', $this->getSkin()->getTitle()->getPrefixedDBkey() ),
				wfMessage( 'recentchangeslinked-toolbox' )->escaped()
			);
		}
	}

	function talkLink() {
		$title = $this->getSkin()->getTitle();
		if ( NS_SPECIAL == $title->getNamespace() ) {
			# No discussion links for special pages
			return '';
		}

		$linkOptions = array();

		if ( $title->isTalkPage() ) {
			$link = $title->getSubjectPage();
			switch( $link->getNamespace() ) {
				case NS_MAIN:
					$text = wfMessage( 'articlepage' );
					break;
				case NS_USER:
					$text = wfMessage( 'userpage' );
					break;
				case NS_PROJECT:
					$text = wfMessage( 'projectpage' );
					break;
				case NS_FILE:
					$text = wfMessage( 'imagepage' );
					# Make link known if image exists, even if the desc. page doesn't.
					if ( wfFindFile( $link ) )
						$linkOptions[] = 'known';
					break;
				case NS_MEDIAWIKI:
					$text = wfMessage( 'mediawikipage' );
					break;
				case NS_TEMPLATE:
					$text = wfMessage( 'templatepage' );
					break;
				case NS_HELP:
					$text = wfMessage( 'viewhelppage' );
					break;
				case NS_CATEGORY:
					$text = wfMessage( 'categorypage' );
					break;
				default:
					$text = wfMessage( 'articlepage' );
			}
		} else {
			$link = $title->getTalkPage();
			$text = wfMessage( 'talkpage' );
		}

		$s = Linker::link( $link, $text->text(), array(), array(), $linkOptions );

		return $s;
	}
	
	/**
	 * @return string
	 */
	function beforeContent() {
		$mainPageObj = Title::newMainPage();

		$s = "\n<div id='content'>\n";
		ob_start();
?>
<div id="topbar">
	<p id="sitetitle">
		<a href="<?php echo htmlspecialchars( $mainPageObj->getLocalURL() ) ?>">
			<?php echo wfMessage( 'sitetitle' )->escaped() ?>
		</a>
	</p>
	<p id="sitesub">
		<?php echo wfMessage( 'sitesubtitle' )->escaped() ?>
	</p>
	
	<p id="syslinks">
		<span><?php echo $this->sysLinks() ?></span>
	</p>
	<div id="linkcollection">
		<div id="langlinks"><?php echo str_replace( '<br />', '', $this->otherLanguages() ) ?></div>
		<?php echo $this->getSkin()->getCategories() ?>
		<div id="titlelinks"><?php echo $this->pageTitleLinks() ?></div>
		<?php if ( $this->data['newtalk'] ) { ?>
		<div class="usermessage"><strong><?php echo $this->data['newtalk'] ?></strong></div>
		<?php } ?>
	</div>
</div>
<?php
		$s .= ob_get_contents();
		ob_end_clean();
		
		$s .= "\n<div id='article'>";

		$notice = $this->getSkin()->getSiteNotice();
		if( $notice ) {
			$s .= "\n<div id='siteNotice'>$notice</div>\n";
		}
		$s .= $this->pageTitle();
		$s .= $this->pageSubtitle() . "\n";
		return $s;
	}

	/**
	 * @return string
	 */
	function afterContent(){
		$s = "\n</div>\n";

		$s .= "\n<div id='footer'>";

		$s .= $this->bottomLinks();
		$s .= $this->getSkin()->getLanguage()->pipeList( array(
			"\n<br />" . Linker::linkKnown(
				Title::newMainPage()
			),
			$this->getSkin()->aboutLink(),
			$this->searchForm( 'afterContent' )
		) );
		
		$s .= "\n<br />";
		$footlinks = $this->getFooterLinks();
		if ( $footlinks['info'] ) {
			foreach ( $footlinks['info'] as $item ) {
				$s .= $this->data[$item] . ' ';
			}
		}

		$s .= "\n</div>\n</div>\n";

		$s .= $this->quickBar();
		return $s;
	}

	/**
	 * @return string
	 */
	function sysLinks() {
		$li = SpecialPage::getTitleFor( 'Userlogin' );
		$lo = SpecialPage::getTitleFor( 'Userlogout' );

		$rt = $this->getSkin()->getTitle()->getPrefixedURL();
		if ( 0 == strcasecmp( urlencode( $lo ), $rt ) ) {
			$q = array();
		} else {
			$q = array( 'returnto' => $rt );
		}

		$s = array(
			$this->getSkin()->mainPageLink(),
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'aboutpage' )->inContentLanguage()->text() ),
				wfMessage( 'about' )->text()
			),
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'helppage' )->inContentLanguage()->text() ),
				wfMessage( 'help' )->text()
			),
			Linker::linkKnown(
				Title::newFromText( wfMessage( 'faqpage' )->inContentLanguage()->text() ),
				wfMessage( 'faq' )->text()
			),
			Linker::specialLink( 'Specialpages' )
		);

		/* show links to different language variants */
		if( $this->variantLinks() ) {
			$s[] = $this->variantLinks();
		}
		if( $this->extensionTabLinks() ) {
			$s[] = $this->extensionTabLinks();
		}
		if ( $this->data['loggedin'] ) {
			$s[] = Linker::linkKnown(
				$lo,
				wfMessage( 'logout' )->text(),
				array(),
				$q
			);
		} else {
			$s[] = Linker::linkKnown(
				$li,
				wfMessage( 'login' )->text(),
				array(),
				$q
			);
		}

		return $this->getSkin()->getLanguage()->pipeList( $s );
	}



	
	/**
	 * @param $heading string
	 * @return string
	 * 
	 * @fixed
	 */
	function menuHead( $heading ) {
		return "\n<h6>" . htmlspecialchars( $heading ) . "</h6>";
	}

	/**
	 * Compute the sidebar
	 * @access private
	 *
	 * @return string
	 * 
	 * @fixed
	 */
	function quickBar(){
		$s = "\n<div id='quickbar'>";

		$sep = "<br />\n";
		
		$plain_bar = $this->data['sidebar'];
		$bar = array();
		
		// Massage the sidebar
		// We want to place SEARCH at the beginning and a lot of stuff before TOOLBOX (or at the end, if it's missing)
		$additions_done = false;
		while ( !$additions_done ) {
			$bar = array(); // Empty it out
			
			// Always display search on top
			$bar['SEARCH'] = true;
				
			foreach ( $plain_bar as $heading => $links ) {
				if ( $heading == 'TOOLBOX' ) {
					if( $links !== NULL ) {
						// If this is not a toolbox prosthetic we inserted outselves, fill it out
						$plain_bar['TOOLBOX'] = $this->getToolbox();
					}
					
					// And insert the stuff
					
					// "This page" and "Edit" menus
					// We need to do some massaging here... we reuse all of the items, except for $...['views']['view'],
					// as $...['namespaces']['main'] and $...['namespaces']['talk'] together serve the same purpose.
					// We also don't use $...['variants'], these are displayed in the top menu.
					$content_navigation = $this->data['content_navigation'];
					$qbpageoptions = array_merge(
						$content_navigation['namespaces'],
						array(
							'history' => $content_navigation['views']['history'],
							'watch' => $content_navigation['actions']['watch'],
							'unwatch' => $content_navigation['actions']['unwatch'],
						)
					);
					$content_navigation['actions']['watch'] = null;
					$content_navigation['actions']['unwatch'] = null;
					$qbedit = array_merge(
						array(
							'edit' => $content_navigation['views']['edit'],
							'addsection' => $content_navigation['views']['addsection'],
						),
						$content_navigation['actions']
					);
					$bar['qbedit'] = $qbedit;
					$bar['qbpageoptions'] = $qbpageoptions;
					
					// Personal tools ("My pages")
					$bar['qbmyoptions'] = $this->getPersonalTools();
					
					$additions_done = true;
				}
				
				// Re-insert current heading, unless it's SEARCH
				if ( $heading != 'SEARCH' ) {
					$bar[$heading] = $plain_bar[$heading];
				}
			}
			
			// If TOOLBOX is missing, $additions_done is still false
			if ( !$additions_done ) {
				$plain_bar['TOOLBOX'] = false;
			}
		}
		
		foreach ( $bar as $heading => $links ) {
			if ( $heading == 'SEARCH' ) {
				$s .= $this->menuHead( wfMessage( 'qbfind' )->text() );
				$s .= $this->searchForm( 'sidebar' );
			} elseif ( $heading == 'LANGUAGES' ) {
				// discard these; we display languages below page content
			} else {
				if ( $links ) {
					// Use the navigation heading from standard sidebar as the "browse" section
					if ( $heading == 'navigation' ) {
						$heading = 'qbbrowse';
					}
					if ( $heading == 'TOOLBOX' ) {
						$heading = 'toolbox';
					}
					
					$headingMsg = wfMessage( $heading );
					$any_link = false;
					$t = $this->menuHead( $headingMsg->exists() ? $headingMsg->text() : $heading );
					
					foreach ( $links as $key => $link ) {
						// Can be empty due to rampant sidebar massaging we're doing above
						if ( $link ) {
							$any_link = true;
							$t .= $this->makeListItem( $key, $link, array( 'tag' => 'span' ) ) . $sep;
						}
					}
					
					if ( $any_link ) {
						$s .= $t;
					}
				}
			}
		}

		$s .= $sep . "\n</div>\n";
		return $s;
	}

	/**
	 * @param $label string
	 * @return string
	 * 
	 * @fixed
	 */
	function searchForm( $which ) {
		global $wgUseTwoButtonsSearchForm;

		$search = $this->getSkin()->getRequest()->getText( 'search' );
		$action = $this->data['searchaction'];
		$s = "<form id=\"searchform-" . htmlspecialchars($which) . "\" method=\"get\" class=\"inline\" action=\"$action\">";
		if( $which == 'afterContent' ) {
			$s .= wfMessage( 'qbfind' )->text() . ": ";
		}

		$s .= "<input type='text' class=\"mw-searchInput\" name=\"search\" size=\"14\" value=\""
			. htmlspecialchars( substr( $search, 0, 256 ) ) . "\" />"
			. ($which == 'afterContent' ? " " : "<br />")
			. "<input type='submit' class=\"searchButton\" name=\"go\" value=\"" . wfMessage( 'searcharticle' )->escaped() . "\" />";

		if( $wgUseTwoButtonsSearchForm ) {
			$s .= " <input type='submit' class=\"searchButton\" name=\"fulltext\" value=\"" . wfMessage( 'search' )->escaped() . "\" />\n";
		} else {
			$s .= '<div><a href="' . $action . '" rel="search">' . wfMessage( 'powersearch-legend' )->escaped() . "</a></div>\n";
		}

		$s .= '</form>';

		return $s;
	}
}
