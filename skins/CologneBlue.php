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
	 *
	 * @fixed
	 */
	function variantLinks() {
		$s = array();

		$variants = $this->data['content_navigation']['variants'];

		foreach ( $variants as $key => $link ) {
			$s[] = $this->makeListItem( $key, $link, array( 'tag' => 'span' ) );
		}

		return $this->getSkin()->getLanguage()->pipeList( $s );
	}
	
	// @fixed
	function otherLanguages() {
		global $wgHideInterlanguageLinks;
		if ( $wgHideInterlanguageLinks ) {
			return "";
		}
		
		// This is mostly copied from SkinTemplate.
		// Unfortunately all links in $this->data['language_urls'] are always ucfirsted,
		// and we do not want this (it's only good if the links are placed in the sidebar).
		// TODO: remove this.
		$language_urls = array();
		foreach ( $this->getSkin()->getOutput()->getLanguageLinks() as $l ) {
			$tmp = explode( ':', $l, 2 );
			$class = 'interwiki-' . $tmp[0];
			unset( $tmp );
			$nt = Title::newFromText( $l );
			if ( $nt ) {
				$ilLangName = Language::fetchLanguageName( $nt->getInterwiki() );
				if ( strval( $ilLangName ) === '' ) {
					$ilLangName = $l;
				}
				$language_urls[] = array(
					'href' => $nt->getFullURL(),
					'text' => $ilLangName,
					'title' => $nt->getText(),
					'class' => $class,
					'lang' => $nt->getInterwiki(),
					'hreflang' => $nt->getInterwiki(),
				);
			}
		}

		$s = array();
		foreach ( $language_urls as $key => $data ) {
			$s[] = $this->makeListItem( $key, $data, array( 'tag' => 'span' ) );
		}
		
		if ( empty( $s ) ) {
			return "";
		} else {
			return wfMessage( 'otherlanguages' )->text()
				. wfMessage( 'colon-separator' )->text()
				. $this->getSkin()->getLanguage()->pipeList( $s );
		}
	}

	// @fixed
	function pageTitleLinks() {
		$s = array();
		$footlinks = $this->getFooterLinks();
		
		foreach ( $footlinks['places'] as $item ) {
			$s[] = $this->data[$item];
		}
		
		return $this->getSkin()->getLanguage()->pipeList( $s );
	}

	function bottomLinks() {
		$sep = wfMessage( 'pipe-separator' )->escaped() . "\n";

		$s = '';
		if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
			$element[] = '<strong>' . $this->editThisPage() . '</strong>';

			if ( $this->getSkin()->getUser()->isLoggedIn() ) {
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
				if ( $this->getSkin()->getUser()->isAllowed( 'delete' ) ) {
					$s .= $this->deleteThisPage();
				}

				if ( $this->getSkin()->getUser()->isAllowed( 'protect' ) ) {
					$s .= $sep . $this->protectThisPage();
				}

				if ( $this->getSkin()->getUser()->isAllowed( 'move' ) ) {
					$s .= $sep . $this->moveThisPage();
				}
			}

			$s .= "<br />\n" . $this->otherLanguages();
		}

		return $s;
	}

	function editThisPage() {
		if ( !$this->getSkin()->getOutput()->isArticleRelated() ) {
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
		$diff = $this->getSkin()->getRequest()->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( !$diff ) && $this->getSkin()->getUser()->isAllowed( 'delete' ) ) {
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
		$diff = $this->getSkin()->getRequest()->getVal( 'diff' );
		$title = $this->getSkin()->getTitle();

		if ( $title->getArticleID() && ( ! $diff ) && $this->getSkin()->getUser()->isAllowed( 'protect' ) ) {
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
		// Cache
		$title = $this->getSkin()->getTitle();

		if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
			if ( $this->getSkin()->getUser()->isWatched( $title ) ) {
				$text = wfMessage( 'unwatchthispage' )->text();
				$query = array(
					'action' => 'unwatch',
					'token' => UnwatchAction::getUnwatchToken( $title, $this->getSkin()->getUser() ),
				);
				$id = 'mw-unwatch-link';
			} else {
				$text = wfMessage( 'watchthispage' )->text();
				$query = array(
					'action' => 'watch',
					'token' => WatchAction::getWatchToken( $title, $this->getSkin()->getUser() ),
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
		if ( !$this->getSkin()->getOutput()->isArticleRelated() ) {
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
	 * 
	 * @fixed
	 */
	function beforeContent() {
		ob_start();
?>
<div id="content">
	<div id="topbar">
		<p id="sitetitle">
			<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>">
				<?php echo wfMessage( 'sitetitle' )->escaped() ?>
			</a>
		</p>
		<p id="sitesub"><?php echo wfMessage( 'sitesubtitle' )->escaped() ?></p>
		
		<div id="toplinks">
			<p id="syslinks"><?php echo $this->sysLinks() ?></p>
			<p id="variantlinks"><?php echo $this->variantLinks() ?></p>
		</div>
		<div id="linkcollection">
			<div id="langlinks"><?php echo str_replace( '<br />', '', $this->otherLanguages() ) ?></div>
			<?php echo $this->getSkin()->getCategories() ?>
			<div id="titlelinks"><?php echo $this->pageTitleLinks() ?></div>
			<?php if ( $this->data['newtalk'] ) { ?>
			<div class="usermessage"><strong><?php echo $this->data['newtalk'] ?></strong></div>
			<?php } ?>
		</div>
	</div>
	<div id="article">
		<?php if ( $this->getSkin()->getSiteNotice() ) { ?>
		<div id="siteNotice"><?php echo $this->getSkin()->getSiteNotice() ?></div>
		<?php } ?>
		<h1 id="firstHeading"><span dir="auto"><?php echo $this->data['title'] ?></span></h1>
		<?php if ( $this->translator->translate( 'tagline' ) ) { ?>
		<p class="tagline"><?php echo htmlspecialchars( $this->translator->translate( 'tagline' ) ) ?></p>
		<?php } ?>
		<?php if ( $this->getSkin()->getOutput()->getSubtitle() ) { ?>
		<p class="subtitle"><?php echo $this->getSkin()->getOutput()->getSubtitle() ?></p>
		<?php } ?>
		<?php if ( $this->getSkin()->subPageSubtitle() ) { ?>
		<p class="subpages"><?php echo $this->getSkin()->subPageSubtitle() ?></p>
		<?php } ?>
<?php
		$s = ob_get_contents();
		ob_end_clean();
		
		return $s;
	}

	/**
	 * @return string
	 * 
	 * @fixed
	 */
	function afterContent() {
		ob_start();
?>
	</div>
	<div id='footer'>
<?php
		// Page-related links
		echo $this->bottomLinks();
		echo "\n<br />";
		
		// Footer and second searchbox
		echo $this->getSkin()->getLanguage()->pipeList( array(
			$this->getSkin()->mainPageLink(),
			$this->getSkin()->aboutLink(),
			$this->searchForm( 'footer' )
		) );
		echo "\n<br />";
		
		// Standard footer info
		$footlinks = $this->getFooterLinks();
		if ( $footlinks['info'] ) {
			foreach ( $footlinks['info'] as $item ) {
				echo $this->data[$item] . ' ';
			}
		}
?>
	</div>
</div>
<?php echo $this->quickBar() ?>
<?php
		$s = ob_get_contents();
		ob_end_clean();

		return $s;
	}

	/**
	 * @return string
	 * 
	 * @fixed
	 */
	function sysLinks() {
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
		);

		$personalUrls = $this->getPersonalTools();
		foreach ( array ( 'logout', 'createaccount', 'login', 'anonlogin' ) as $key ) {
			if ( $personalUrls[$key] ) {
				$s[] = $this->makeListItem( $key, $personalUrls[$key], array( 'tag' => 'span' ) );
			}
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
					foreach ( array ( 'logout', 'createaccount', 'login', 'anonlogin' ) as $key ) {
						$bar['qbmyoptions'][$key] = false;
					}
					
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
		if( $which == 'footer' ) {
			$s .= wfMessage( 'qbfind' )->text() . ": ";
		}

		$s .= "<input type='text' class=\"mw-searchInput\" name=\"search\" size=\"14\" value=\""
			. htmlspecialchars( substr( $search, 0, 256 ) ) . "\" />"
			. ($which == 'footer' ? " " : "<br />")
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
