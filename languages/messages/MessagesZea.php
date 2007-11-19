<?php
/** Zeêuws (Zeêuws)
 *
 * @addtogroup Language
 *
 * @author Rob Church <robchur@gmail.com>
 * @author SPQRobin
 */

$fallback = 'nl';

/**
 * Namespace names
 * (bug 8708)
 */
$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaol',
	NS_MAIN             => '',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_IMAGE            => 'Plaetje',
	NS_IMAGE_TALK       => 'Overleg_plaetje',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
	NS_HELP             => 'Ulpe',
	NS_HELP_TALK        => 'Overleg_ulpe',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Overleg_categorie',
);

$messages = array(
'mytalk'     => 'Mien overleg',
'navigation' => 'Navigaotie',

'help'             => 'Ulpe',
'history_short'    => 'Geschiedenisse',
'printableversion' => 'Printbaere versie',
'edit'             => 'Bewerken',
'editthispage'     => 'Deêze bladzie bewerken',
'delete'           => 'Wissen',
'deletethispage'   => 'Wis deêze bladzie',
'talkpagelinktext' => 'Overleg',
'talk'             => 'Overleg',
'toolbox'          => 'Ulpmiddels',
'otherlanguages'   => 'In aore taelen',
'lastmodifiedat'   => "Deêze bladzie is vò 't lèst bewerkt op $1 om $2.", # $1 date, $2 time

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'edithelp'    => "Ulpe bie't bewerken",
'mainpage'    => 'Vòblad',
'portal'      => "'t Durpsuus",
'sitesupport' => 'Donaoties',

'editsection' => 'bewerken',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'Bladzie',
'nstab-user'    => 'Gebruker',
'nstab-special' => 'Speciaol',

# General errors
'viewsource' => 'brontekst bekieken',

# Login and logout pages
'userlogin' => 'Anmelden / Inschrieven',

# Preferences page
'mypreferences' => 'Mien vòkeuren',
'datetime'      => 'Daotum en tied',

# Recent changes
'recentchanges' => 'Juust angepast',
'diff'          => 'wiez',

# Recent changes linked
'recentchangeslinked' => 'Gerelateerde bewerkiengen',

# Random page
'randompage' => 'Bladzie op goed geluk',

# Miscellaneous special pages
'listusers'         => 'Gebrukerslieste',
'specialpages'      => 'Speciaole bladzies',
'newpages-username' => 'Gebrukersnaem:',

# Special:Allpages
'nextpage' => 'Volgende bladzie ($1)',

# Watchlist
'mywatchlist' => 'Mien volglieste',

# Delete/protect/revert
'deletedarticle' => 'wiste "[[$1]]"',
'dellogpage'     => 'Wislogboek',

# Contributions
'mycontris' => 'Mien biedraegen',

# What links here
'whatlinkshere' => 'Links nae deze bladzie',

# Multipage image navigation
'imgmultipageprev' => '← vorrege bladzie',
'imgmultipagenext' => 'volgende bladzie →',

);
