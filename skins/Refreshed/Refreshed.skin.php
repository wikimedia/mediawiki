<?php

// inherit main code from SkinTemplate, set the CSS and template filter
class SkinRefreshed extends SkinTemplate {
	public $skinname = 'refreshed',
		$stylename = 'refreshed',
		$template = 'RefreshedTemplate',
		$useHeadElement = true,
		$headerNav = array();

	/**
	 * Initializes OutputPage and sets up skin-specific parameters
	 *
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath;

		parent::initPage( $out );

		$out->addMeta( 'viewport', 'width=device-width' );

		// prevent iOS from zooming out when the sidebar is opened
		$out->addHeadItem( 'viewportforios',
			Html::element( 'meta', array(
				'name' => 'viewport',
				'content' => 'width=device-width, initial-scale=1.0'
			) )
		);

		// Inject webfont loader CSS file inline here so that it'll work even for IE11
		// Conditional comments aren't supported in IE10+ so we have no way of loading
		// this just for IE, so better to have all font declarations here (and
		// maybe one day we'll rename the file to just "fontloader.css" or something)
		// Based on some quick-ish testing on 25 July 2016, it appears that font
		// declarations need to be loaded before they're used so that they work
		// under IE(11).
		// See https://phabricator.wikimedia.org/T134653 for more info.
		$out->addHeadItem( 'webfontfix',
			 Html::element( 'link', array(
				'href' => $wgLocalStylePath . '/Refreshed/refreshed/styles/screen/iefontfix.css',
				'rel' => 'stylesheet'
			) )
		);

		// Add JavaScript via ResourceLoader
		$out->addModules( 'skins.refreshed.js' );
	}

	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		// Add CSS via ResourceLoader
		$out->addModuleStyles( array(
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			'skins.refreshed'
		) );
	}
}

class RefreshedTemplate extends BaseTemplate {
	/**
	 * Parses MediaWiki:Refreshed-wiki-dropdown.
	 * Forked from Games' parseSidebarMenu(), which in turn was forked from
	 * Monaco's parseSidebarMenu(), but none of these three methods are
	 * identical.
	 *
	 * @param string $messageKey Message name
	 * @return array
	 */
	private function parseDropDownMenu( $messageKey ) {
		$lines = $this->getLines( $messageKey );
		$nodes = array();
		$i = 0;

		if ( is_array( $lines ) ) {
			foreach ( $lines as $line ) {
				# ignore empty lines
				if ( strlen( $line ) == 0 ) {
					continue;
				}

				$node = self::parseItem( $line );
				for ( $x = $i; $x >= 0; $x-- ) {
					if ( $x == 0 ) {
						break;
					}
				}

				$nodes[$i + 1] = $node;
				$i++;
			}
		}

		return $nodes;
	}

	/**
	 * Parse one pipe-separated line from MediaWiki message to array with
	 * indexes 'logo', 'href' and 'wiki_name'.
	 *
	 * @param string $line Line (beginning with a *) from a MediaWiki: message
	 * @return array
	 */
	public static function parseItem( $line ) {
		// trim spaces and asterisks from line and then split it to maximum three chunks
		$line_temp = explode( '|', trim( $line, '* ' ), 3 );

		// trim [ and ] from line to have just http://en.wikipedia.org instead
		// of [http://en.wikipedia.org] for external links
		$line_temp[0] = trim( $line_temp[0], '[]' );

		if ( count( $line_temp ) >= 2 && $line_temp[1] != '' ) {
			$logo = trim( $line_temp[1] );
		} else {
			$logo = trim( $line_temp[0] );
		}

		if (
			isset( $line_temp[2] ) &&
			preg_match( '/^(?:' . wfUrlProtocols() . ')/', $line_temp[2] )
		)
		{
			$href = $line_temp[2];
		} else {
			$href = '#';
		}

		return array(
			'logo' => $logo,
			'href' => $href,
			'wiki_name' => $line_temp[0]
		);
	}

	/**
	 * @param string $messageKey Name of a MediaWiki: message
	 * @return array|null Array if $messageKey has been given, otherwise null
	 */
	private function getMessageAsArray( $messageKey ) {
		$messageObj = $this->getSkin()->msg( $messageKey )->inContentLanguage();
		if ( !$messageObj->isDisabled() ) {
			$lines = explode( "\n", $messageObj->text() );
			if ( count( $lines ) > 0 ) {
				return $lines;
			}
		}
		return null;
	}

	/**
	 * @param string $messageKey Name of a MediaWiki: message
	 * @return array
	 */
	private function getLines( $messageKey ) {
		$title = Title::newFromText( $messageKey, NS_MEDIAWIKI );
		$revision = Revision::newFromTitle( $title );
		if ( is_object( $revision ) ) {
			$contentText = ContentHandler::getContentText( $revision->getContent() );
			if ( trim( $contentText ) != '' ) {
				$temp = $this->getMessageAsArray( $messageKey );
				if ( count( $temp ) > 0 ) {
					wfDebugLog( 'Refreshed', sprintf( 'Get LOCAL %s, which contains %s lines', $messageKey, count( $temp ) ) );
					$lines = $temp;
				}
			}
		}

		if ( empty( $lines ) ) {
			$lines = $this->getMessageAsArray( $messageKey );
			wfDebugLog( 'Refreshed', sprintf( 'Get %s, which contains %s lines', $messageKey, count( $lines ) ) );
		}

		return $lines;
	}

	public function execute() {
		global $wgMemc;

		$skin = $this->getSkin();
		$config = $skin->getConfig();
		$user = $skin->getUser();

		// Title processing
		$titleBase = $skin->getTitle();
		$title = $titleBase->getSubjectPage();
		$titleNamespace = $titleBase->getNamespace();

		$key = wfMemcKey( 'refreshed', 'header' );
		$headerNav = $wgMemc->get( $key );
		if ( !$headerNav ) {
			$headerNav = array();
			$skin->addToSidebar( $headerNav, 'refreshed-navigation' );
			$wgMemc->set( $key, $headerNav, 60 * 60 * 24 ); // 24 hours
		}

		$dropdownCacheKey = wfMemcKey( 'refreshed', 'dropdownmenu' );
		$dropdownNav = $wgMemc->get( $dropdownCacheKey );
		if ( !$dropdownNav ) {
			$dropdownNav = $this->parseDropDownMenu( 'Refreshed-wiki-dropdown' );
			$wgMemc->set( $dropdownCacheKey, $dropdownNav, 60 * 60 * 24 ); // 24 hours
		}

		$thisWikiURLMsg = $skin->msg( 'refreshed-this-wiki-url' );
		if ( $thisWikiURLMsg->isDisabled() ) {
			$thisWikiURL = htmlspecialchars( Title::newMainPage()->getFullURL() );
		} else {
			$thisWikiURL = $skin->msg( 'refreshed-this-wiki-url' )->escaped();
		}
		$thisWikiWordmarkLogo = $skin->msg( 'refreshed-this-wiki-wordmark' )->escaped();
		$logoImgElement = Html::element( 'img', array(
			'src' => $thisWikiWordmarkLogo,
			'alt' => $config->get( 'Sitename' ),
			'width' => 144,
			'height' => 30
		) );
		$thisWikiMobileLogo = $skin->msg( 'refreshed-this-wiki-mobile-logo' );
		$thisWikiMobileLogoImgElement = '';
		if ( !$thisWikiMobileLogo->isDisabled() ) {
			$thisWikiMobileLogoImgElement = Html::element( 'img', array(
				'src' => $thisWikiMobileLogo->escaped(),
				'alt' => $config->get( 'Sitename' ),
				// 'width' => ???,
				// 'height' => ???
			) );
		}

		// Output the <html> tag and whatnot
		$this->html( 'headelement' );
		?>
		<a id="fade-overlay" role="presentation"></a>
		<header id="header-wrapper">
			<section id="site-info">
				<?php
				if ( $dropdownNav ) { // if there is a site dropdown (so there are multiple wikis)
					?>
					<nav id="site-info-main" class="multiple-wikis">
						<a class="main header-button" href="<?php echo $thisWikiURL ?>"><?php echo $logoImgElement ?></a><a class="header-button collapse-trigger site-info-arrow"><span class="arrow wikiglyph wikiglyph-caret-down"></span></a>
						<ul class="header-menu refreshed-menu-collapsible refreshed-menu-collapsed">
							<?php
							foreach ( $dropdownNav as $index => $entry ) {
								$dropDownLogo = Html::element( 'img', array(
									'src' => $entry['logo'],
									'alt' => $entry['wiki_name'],
									'width' => 144,
									'height' => 30
								) );
								?>
								<li class="header-dropdown-item">
									<a href="<?php echo htmlspecialchars( $entry['href'] ) ?>"><?php echo $dropDownLogo ?></a>
								</li>
								<?php
							}
							?>
						</ul>
					</nav>
					<?php
				} else {
					?>
					<div id="site-info-main">
						<a class="main header-button" href="<?php echo $thisWikiURL ?>"><?php echo $logoImgElement ?></a>
					</div>
				<?php
				}
				if ( !$thisWikiMobileLogo->isDisabled() ) { // if a mobile logo has been defined
					?>
					<div id="site-info-mobile">
						<a class="main header-button" href="<?php echo $thisWikiURL ?>"><?php echo $thisWikiMobileLogoImgElement ?></a>
					</div>
					<?php
				}
				?>
			</section>
			<section class="search">
				<a class ="search-shower header-button fade-trigger fadable">
					<span class="wikiglyph wikiglyph-magnifying-glass"></span>
				</a>
				<a class="search-closer header-button fade-trigger fadable faded">
					<span class="wikiglyph wikiglyph-x"></span>
				</a>
				<form class="search-form fadable faded" action="<?php $this->text( 'wgScript' ) ?>" method="get">
					<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
					<?php echo $this->makeSearchInput( array( 'id' => 'searchInput' ) ); ?>
				</form>
			</section>

			<?php
			// test if Echo is installed
			if ( ExtensionRegistry::getInstance()->isLoaded( 'Echo' ) ) {
				?>
				<div id="echo" role="log"></div>
				<?php
			}
			?>

			<section id="user-info">
				<a class="header-button collapse-trigger">
					<span class="arrow wikiglyph wikiglyph-caret-down"></span>
					<?php
					$avatarElement = '';
					// display the user's username if logged in, otherwise display the "login" message
					$usernameText = $this->data['loggedin'] ? $user->getName() : $this->getMsg( 'login' )->text();
					// Show the user's avatar image in the top left drop-down
					// menu, but only if SocialProfile is installed
					if ( class_exists( 'wAvatar' ) ) {
						$avatar = new wAvatar( $user->getId(), 'l' );
						$avatarElement = $avatar->getAvatarURL( array(
							'class' => 'avatar avatar-image'
						) );
					} elseif ( $this->data['loggedin'] ) { // if no SocialProfile but user is logged in
						if ( $this->getMsg( 'refreshed-icon-logged-in' )->isDisabled() ) { // if wiki has not set a custom image for logged in users
							$avatarElement = Html::element( 'span', array(
								'class' => 'avatar avatar-no-socialprofile wikiglyph wikiglyph-user-smile'
							) );
						} else { // if wiki has set custom image for logged in users
							$avatarElement = Html::element( 'img', array(
								'src' => $this->getMsg( 'refreshed-icon-logged-in' )->escaped(),
								'class' => 'avatar avatar-no-socialprofile avatar-image'
							) );
						}
					} else { // if no SocialProfile but user is logged out
						if ( $this->getMsg( 'refreshed-icon-logged-out' )->isDisabled() ) { // if wiki has not set a custom image for logged out users
							$avatarElement = Html::element( 'span', array(
								'class' => 'avatar avatar-no-socialprofile wikiglyph wikiglyph-user-sleep'
							) );
						} else { // if wiki has set custom image for logged out users
							$avatarElement = Html::element( 'img', array(
								'src' => $this->getMsg( 'refreshed-icon-logged-out' )->escaped(),
								'class' => 'avatar avatar-no-socialprofile avatar-image'
							) );
						}
					}
					echo $avatarElement;
					?>
					<span class="username"><?php echo $usernameText ?></span>
				</a>
				<ul class="header-menu refreshed-menu-collapsible refreshed-menu-collapsed">
					<?php
					// generate user tools (and notifications item in user tools if needed)
					$personalToolsCount = 0;
					foreach ( $this->getPersonalTools() as $key => $tool ) {
						$tool['class'] = 'header-dropdown-item'; // add the "header-dropdown-item" class to each li element
						echo $this->makeListItem( $key, $tool );
						if ( class_exists( 'EchoHooks' ) && $this->data['loggedin'] && $personalToolsCount == 2 ) { // if Echo is installed, user is logged in, and the first two tools have been generated (user and user talk)...
							?>
							<li id="pt-notifications-personaltools" class="header-dropdown-item">
								<?php
								echo Linker::link(
									SpecialPage::getTitleFor( 'Notifications' ),
									$this->getMsg( 'notifications' )->plain(),
									Linker::tooltipAndAccesskeyAttribs( 'pt-notifications' )
								)
								?>
							</li>
						<?php
						}
						$personalToolsCount++;
					}
					?>
				</ul>
			</section>
			<?php
			if ( $headerNav ) {
				?>
				<section id="header-categories">
				<?php
					foreach ( $headerNav as $main => $sub ) {
						?>
						<div class="page-item<?php echo ( $sub ? ' page-item-has-children' : '' ) ?>">
							<a class="header-button collapse-trigger">
								<span class="header-category-name"><?php echo htmlspecialchars( $main ) ?></span>
								<span class="arrow wikiglyph wikiglyph-caret-down"></span>
							</a>
							<ul class="header-menu refreshed-menu-collapsible refreshed-menu-collapsed">
								<?php
									foreach ( $sub as $key => $item ) {
										$item['class'] = 'header-dropdown-item';
										echo $this->makeListItem(
											$key,
											$item
										);
									}
								?>
							</ul>
						</div>
					<?php
					}
				?>
				</section>
				<?php
			}
			?>
		</header>
		<aside id="sidebar-wrapper">
			<a class="sidebar-shower header-button"></a>
			<div id="sidebar-logo">
				<a class="main" href="<?php echo $thisWikiURL ?>"><?php echo $logoImgElement ?></a>
			</div>
			<div id="sidebar">
				<?php
				unset( $this->data['sidebar']['SEARCH'] );
				unset( $this->data['sidebar']['TOOLBOX'] );
				unset( $this->data['sidebar']['LANGUAGES'] );

				foreach ( $this->data['sidebar'] as $main => $sub ) {
					?>
					<section class="sidebar-section">
						<h1 class="main"><?php echo htmlspecialchars( $main ) ?></h1>
						<ul>
							<?php
							if ( is_array( $sub ) ) { // MW-generated stuff from the sidebar message
								foreach ( $sub as $key => $action ) {
									echo $this->makeListItem(
										$key,
										$action,
										array(
											'link-class' => 'sub',
											'link-fallback' => 'span'
										)
									);
								}
							} else {
								// allow raw HTML block to be defined by extensions (like NewsBox)
								echo $sub;
							}
							?>
						</ul>
					</section>
					<?php
				}

				if ( $this->data['language_urls'] ) {
					?>
					<section class="sidebar-section">
						<h1 class="main"><?php echo $this->getMsg( 'otherlanguages' )->text() ?></h1>
							<ul id="languages">
								<?php
								foreach ( $this->data['language_urls'] as $key => $link ) {
									echo $this->makeListItem( $key, $link, array( 'link-class' => 'sub', 'link-fallback' => 'span' ) );
								}
								?>
							</ul>
						</div>
					</section>
					<?php
				}

				// Hook point for injecting ads
				Hooks::run( 'RefreshedInSidebar', array( $this ) ); ?>
			</div>
		</aside>
		<div id="content-wrapper" class="mw-body-content">
			<?php
			if ( $this->data['sitenotice'] ) {
				?>
				<div id="site-notice" role="banner">
					<?php $this->html( 'sitenotice' ) ?>
				</div>
			<?php
			}
			// Only output this if we need to (T153625)
			if ( $this->data['newtalk'] ) {
			?>
			<div id="new-talk">
				<?php $this->html( 'newtalk' ) ?>
			</div>
			<?php } ?>
			<main id="content">
				<article>
					<header id="content-heading">
						<?php
						if ( method_exists( $this, 'getIndicators' ) ) {
							echo $this->getIndicators();
						}
						?>
						<h1 id="firstHeading" class="scroll-shadow"><?php $this->html( 'title' ) ?></h1>
						<div id="main-title-messages">
							<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
							<?php
							if ( $this->data['subtitle'] || $this->data['undelete'] ) {
								?>
								<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?><?php $this->html( 'undelete' ) ?></div>
							<?php
							}
							?>
						</div>
						<div class="standard-toolbox static-toolbox" role="menubar">
							<?php
							$lastLinkOutsideOfStandardToolboxDropdownHasBeenGenerated = false;
							$amountOfToolsGenerated = 0;

							$toolbox = $this->getToolbox();

							// if there are actions like "edit," etc.
							// (not counting generic toolbox tools like "upload file")
							// in addition to non-page-specific ones like "page" (so a "more..." link is needed)
							if ( sizeof( $this->data['content_actions'] ) > 1 ) {
								foreach ( $this->data['content_actions'] as $key => $action ) {
									if ( !$lastLinkOutsideOfStandardToolboxDropdownHasBeenGenerated ) { // this runs until all the actions outside the dropdown have been generated (generates actions outside dropdown)
										echo $this->makeLink( $key, $action, array(
											'text-wrapper' => array(
												'tag' => 'span'
											)
										) );
										$amountOfToolsGenerated++;
										if (
											sizeof( $this->data['content_actions'] ) == $amountOfToolsGenerated ||
											$key == 'history' || $key == 'addsection' ||
											$key == 'protect' || $key == 'unprotect'
										)
										{
											// if this is the last action or it is the
											// history, new section, or protect/unprotect action
											// (whichever comes first)
											$lastLinkOutsideOfStandardToolboxDropdownHasBeenGenerated = true;
											?>
											<div class="toolbox-container">
												<a class="toolbox-link collapse-trigger"><?php echo $this->getMsg( 'moredotdotdot' )->text() ?></a>
												<div class="standard-toolbox-dropdown refreshed-menu-collapsible refreshed-menu-collapsed">
													<div class="dropdown-triangle"></div>
													<ul>
										<?php
										}
									} else { // generates actions inside dropdown
										?>
										<li class="toolbox-dropdown-item toolbox-dropdown-page-action">
											<?php echo $this->makeLink( $key, $action, array(
												'text-wrapper' => array(
													'tag' => 'span',
													'attributes' => array(
														'class' => 'toolbox-item-text'
													)
												)
											) );
											?>
										</li>
										<?php
									}
								}
								foreach ( $toolbox as $tool => $toolData ) { // generates toolbox tools inside dropdown (e.g. "upload file")
									?>
									<li class="toolbox-dropdown-item toolbox-dropdown-tool">
										<?php echo $this->makeLink( $tool, $toolData, array(
											'text-wrapper' => array(
												'tag' => 'span',
												'attributes' => array(
													'class' => 'toolbox-item-text'
												)
											)
										) );
										?>
									</li>
									<?php
								}
							} else { // if there aren't actions like edit, etc. (so a "tools" link is needed instead of a "more..." link)
								foreach ( $this->data['content_actions'] as $key => $action ) { // generates first link (i.e. "page" button on the mainspace, "special page" on Special namespace, etc.); the foreach loop should once run once since there should only be one link
									echo $this->makeLink( $key, $action );
								}
							?>
								<div class="toolbox-container">
									<a class="toolbox-link collapse-trigger"><?php echo $this->getMsg( 'toolbox' )->text() ?></a>
									<div class="standard-toolbox-dropdown refreshed-menu-collapsible refreshed-menu-collapsed">
										<div class="dropdown-triangle"></div>
										<ul>
											<?php
											foreach ( $toolbox as $tool => $toolData ) { // generates toolbox tools inside dropdown (e.g. "upload file")
												if ( $tool == 'feeds' ) {
													// HACK! Technically this should
													// use $wgAdvertisedFeedTypes, which
													// *can* include 'rss' in addition
													// to 'atom', but only 'atom'
													// is enabled by default.
													// I wasn't able to get 'rss' working
													// locally either, so...
													$dataForLink = $toolData['links']['atom'];
												} else {
													$dataForLink = $toolData;
												}
												?>
												<li class="toolbox-dropdown-item tool-dropdown-item toolbox-dropdown-tool">
													<?php echo $this->makeLink( $tool, $dataForLink, array(
														'text-wrapper' => array(
															'tag' => 'span',
															'attributes' => array(
																'class' => 'toolbox-item-text'
															)
														)
													) );
													?>
											</li>
												<?php
											}
							}
							Hooks::run( 'SkinTemplateToolboxEnd', array( &$this, true ) );
							?>
										</ul>
								</div>
							</div>
						</div>
						<?php
						if ( MWNamespace::isTalk( $titleNamespace ) ) { // if talk namespace
							echo Linker::link(
								$title,
								$this->getMsg( 'backlinksubtitle', $title->getPrefixedText() )->escaped(),
								array( 'id' => 'back-to-subject' )
							);
						}
						?>
					</header>
					<?php
					reset( $this->data['content_actions'] );
					$pageTab = key( $this->data['content_actions'] );
					$isEditing = in_array(
						$skin->getRequest()->getText( 'action' ),
						array( 'edit', 'submit' )
					);

					// determining how many tools need to be generated
					$totalSmallToolsToGenerate = 0;
					$listOfToolsToGenerate = array(
						'wikiglyph wikiglyph-speech-bubbles' => 'ca-talk',
						'wikiglyph wikiglyph-pencil-lock-full' => 'ca-viewsource',
						'wikiglyph wikiglyph-pencil' => 'ca-edit',
						'wikiglyph wikiglyph-clock' => 'ca-history',
						'wikiglyph wikiglyph-trash' => 'ca-delete',
						'wikiglyph wikiglyph-move' => 'ca-move',
						'wikiglyph wikiglyph-lock' => 'ca-protect',
						'wikiglyph wikiglyph-unlock' => 'ca-unprotect',
						'wikiglyph wikiglyph-star' => 'ca-watch',
						'wikiglyph wikiglyph-unstar' => 'ca-unwatch'
					);

					foreach ( $this->data['content_actions'] as $action ) {
						if ( in_array( $action['id'], $listOfToolsToGenerate ) ) { // if the icon in question is one of the listed ones
							$totalSmallToolsToGenerate++;
						}
					}
					if ( MWNamespace::isTalk( $titleNamespace ) ) { // if talk namespace
						$totalSmallToolsToGenerate--; // remove a tool (the talk page tool) if the user is on a talk page
					}

					if ( $totalSmallToolsToGenerate > 0 && !$isEditing ) { // if there's more than zero tools to be generated and the user isn't editing a page
						?>
						<div id="small-toolbox-wrapper">
							<div class="small-toolbox">
								<?php
								$smallToolBeingTested = 1;
								$amountOfSmallToolsToSkipInFront = 1; // skip the "page" (or equivalent) link
								$amountOfSmallToolsGenerated = 0;

								if ( MWNamespace::isTalk( $titleNamespace ) ) { // if talk namespace
									$amountOfSmallToolsToSkipInFront = 2; // skip the "page" (or equivalent) and "talk" links
								}
								foreach ( $this->data['content_actions'] as $action ) {
									if ( $smallToolBeingTested > $amountOfSmallToolsToSkipInFront ) { // if we're not supposed to skip this tool (e.g. if we're supposed to skip the first 2 tools and we're at the 3rd tool, then the boolean is true)
										// @todo Maybe write a custom makeLink()-like function for generating this code?
										if ( in_array( $action['id'], $listOfToolsToGenerate ) ) { // if the icon being rendered is one of the listed ones (if we're supposed to generate this tool)
											?><a href="<?php echo htmlspecialchars( $action['href'] ) ?>" title="<?php echo $action['text'] ?>" class="small-tool"><span class="<?php echo array_search( $action['id'], $listOfToolsToGenerate ) // key (wikiglyph) from $listOfToolsToGenerate ?>"></span></a><?php
											$amountOfSmallToolsGenerated++; // if a tool is indeed generated, increment this variable
										}
									}
									$smallToolBeingTested++; // increment this variable (amount of tools that have been tested) regardless of whether or not the tool was generated
								}
								?>
							</div><?php if ( $totalSmallToolsToGenerate > 3 ) { ?><div id="small-tool-more"><a title="<?php echo $this->getMsg( 'moredotdotdot' )->text() ?>" class="small-tool"><span class="wikiglyph wikiglyph-ellipsis"></span></a></div><?php } ?>
						</div>
						<?php
					}
					?>
					<div id="bodyContent" role="article">
						<?php $this->html( 'bodytext' ) ?>
					</div>
					</article>
					<?php
					$this->html( 'catlinks' );
					if ( $this->data['dataAfterContent'] ) {
						$this->html( 'dataAfterContent' );
					}
					?>
				</main>
		</div>
		<footer id="footer">
			<?php
			$footerExtra = '';
			Hooks::run( 'RefreshedFooter', array( &$footerExtra ) );
			echo $footerExtra;

			foreach ( $this->getFooterLinks() as $category => $links ) {
				?>
				<ul class="footer-row">
					<?php
					foreach ( $links as $link ) {
						?>
						<li class="footer-row-item"><?php $this->html( $link ); ?></li>
						<?php
						$noSkip = true;
					}
					?>
				</ul>
				<?php
			}
			$footerIcons = $this->getFooterIcons( 'icononly' );
			if ( count( $footerIcons ) > 0 ) { ?>
				<ul class="footer-row">
				<?php foreach ( $footerIcons as $blockName => $footerIcons ) {
						foreach ( $footerIcons as $icon ) {
							?>
							<li class="footer-row-item"><?php echo $skin->makeFooterIcon( $icon ); ?></li>
							<?php
						}
				}
				?>
				</ul> <?php
			}
			?>
		</footer>
		<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
	}
}
