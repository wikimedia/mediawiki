<?php
/**
 * Zealandic (Zeêuws)
 *
 * @addtogroup Language
 * @author Rob Church <robchur@gmail.com>
 * @author SQPRobin
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

'edit'           => 'Bewerken',
'otherlanguages' => 'In aore taelen',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'    => 'Bladzie',
'nstab-user'    => 'Gebruker',
'nstab-special' => 'Speciaol',

# Preferences page
'mypreferences' => 'Mien vòkeuren',

# Miscellaneous special pages
'newpages-username' => 'Gebrukersnaem:',

# Special:Allpages
'nextpage' => 'Volgende bladzie ($1)',

# Watchlist
'mywatchlist' => 'Mien volglieste',

# Contributions
'mycontris' => 'Mien biedraegen',

);
