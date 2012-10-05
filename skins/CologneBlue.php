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
	
	/**
	 * Override langlink formatting behavior not to uppercase the language names.
	 * See otherLanguages() in CologneBlueTemplate.
	 */
	function formatLanguageName( $name ) {
		return $name;
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

		// We override SkinTemplate->formatLanguageName() in SkinCologneBlue
		// not to capitalize the language names.
		$language_urls = $this->data['language_urls'];
		if ( empty( $language_urls ) ) {
			return "";
		}

		$s = array();
		foreach ( $language_urls as $key => $data ) {
			$s[] = $this->makeListItem( $key, $data, array( 'tag' => 'span' ) );
		}

		return wfMessage( 'otherlanguages' )->text()
			. wfMessage( 'colon-separator' )->text()
			. $this->getSkin()->getLanguage()->pipeList( $s );
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

	/**
	 * Used in bottomLinks() to elliminate repetitive code.
	 *
	 * @param $key string Key to be passed to makeListItem()
	 * @param $navlink array Navlink suitable for processNavlinkForDocument()
	 * @param $message string Key of the message to use in place of standard text
	 *
	 * @return string
	 * @fixed
	 */
	function processBottomLink( $key, $navlink, $message=null ) {
		if ( !$navlink ) {
			return null; // empty navlinks might be passed
		}

		if ( $message ) {
			$navlink['text'] = wfMessage( $message )->escaped();
		}

		return $this->makeListItem( $key, $this->processNavlinkForDocument( $navlink ), array( 'tag' => 'span' ) );
	}

	// @fixed
	function bottomLinks() {
		$toolbox = $this->getToolbox();
		$content_nav = $this->data['content_navigation'];

		$lines = array();

		if ( $this->getSkin()->getOutput()->isArticleRelated() ) {
			// First row. Regular actions.
			$element = array();

			$editLinkMessage = $this->getSkin()->getTitle()->exists() ? 'editthispage' : 'create-this-page';
			$element[] = $this->processBottomLink( 'edit', $content_nav['views']['edit'], $editLinkMessage );
			$element[] = $this->processBottomLink( 'viewsource', $content_nav['views']['viewsource'], 'viewsource' );

			$element[] = $this->processBottomLink( 'watch', $content_nav['actions']['watch'], 'watchthispage' );
			$element[] = $this->processBottomLink( 'unwatch', $content_nav['actions']['unwatch'], 'unwatchthispage' );

			$element[] = $this->talkLink();

			$element[] = $this->processBottomLink( 'history', $content_nav['views']['history'], 'history' );
			$element[] = $this->processBottomLink( 'whatlinkshere', $toolbox['whatlinkshere'] );
			$element[] = $this->processBottomLink( 'recentchangeslinked', $toolbox['recentchangeslinked'] );

			$element[] = $this->processBottomLink( 'contributions', $toolbox['contributions'] );
			$element[] = $this->processBottomLink( 'emailuser', $toolbox['emailuser'] );

			$lines[] = $this->getSkin()->getLanguage()->pipeList( array_filter( $element ) );


			// Second row. Privileged actions.
			$element = array();

			$element[] = $this->processBottomLink( 'delete', $content_nav['actions']['delete'], 'deletethispage' );
			$element[] = $this->processBottomLink( 'undelete', $content_nav['actions']['undelete'], 'undeletethispage' );

			$element[] = $this->processBottomLink( 'protect', $content_nav['actions']['protect'], 'protectthispage' );
			$element[] = $this->processBottomLink( 'unprotect', $content_nav['actions']['unprotect'], 'unprotectthispage' );

			$element[] = $this->processBottomLink( 'move', $content_nav['actions']['move'], 'movethispage' );

			$lines[] = $this->getSkin()->getLanguage()->pipeList( array_filter( $element ) );


			// Third row. Language links.
			$lines[] = $this->otherLanguages();
		}

		return implode( array_filter( $lines ), "<br />\n" ) . "<br />\n";
	}

	// @fixed
	function talkLink() {
		$title = $this->getSkin()->getTitle();
		$companionTitle = $title->isTalkPage() ? $title->getSubjectPage() : $title->getTalkPage();
		
		if ( $title->getNamespace() == NS_SPECIAL ) {
			// No discussion links for special pages
			return "";
		}
		
		// TODO these messages appear to only be used by CologneBlue, kill and replace with something more sensibly named?
		$nsToMessage = array(
			NS_MAIN => 'articlepage',
			NS_USER => 'userpage',
			NS_PROJECT => 'projectpage',
			NS_FILE => 'imagepage',
			NS_MEDIAWIKI => 'mediawikipage',
			NS_TEMPLATE => 'templatepage',
			NS_HELP => 'viewhelppage',
			NS_CATEGORY => 'categorypage',
			NS_FILE => 'imagepage',
		);
		
		// Find out the message to use for link text. Use either the array above or,
		// for non-talk pages, a generic "discuss this" message.
		// Default is the same as for main namespace.
		$message = $nsToMessage[ $companionTitle->getNamespace() ];
		if ( !$message ) {
			$message = $companionTitle->isTalkPage() ? 'talkpage' : 'articlepage';
		}
		
		// Obviously this can't be reasonable and just return the key for talk namespace, only for content ones.
		// Thus we have to mangle it in exactly the same way SkinTemplate does. (bug 40805)
		$key = $companionTitle->getNamespaceKey( '' );
		if ( $companionTitle->isTalkPage() ) {
			$key = ( $key == 'main' ? 'talk' : $key . "_talk" );
		}
		
		// Use the regular navigational link, but replace its text. Everything else stays unmodified.
		$namespacesLinks = $this->data['content_navigation']['namespaces'];
		return $this->processBottomLink( $message,  $namespacesLinks[$key], $message );
	}

	/**
	 * Takes a navigational link generated by SkinTemplate in whichever way
	 * and mangles attributes unsuitable for repeated use. In particular, this modifies the ids
	 * and removes the accesskeys. This is necessary to be able to use the same navlink twice,
	 * e.g. in sidebar and in footer.
	 *
	 * @param $navlink array Navigational link generated by SkinTemplate
	 * @param $idPrefix mixed Prefix to add to id of this navlink. If false, id is removed entirely. Default is 'cb-'.
	 */
	function processNavlinkForDocument( $navlink, $idPrefix='cb-' ) {
		if ( $navlink['id'] ) {
			$navlink['single-id'] = $navlink['id']; // to allow for tooltip generation
			$navlink['tooltiponly'] = true; // but no accesskeys

			// mangle or remove the id
			if ( $idPrefix === false ) {
				unset( $navlink['id'] );
			} else {
				$navlink['id'] =  $idPrefix . $navlink['id'];
			}
		}

		return $navlink;
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
