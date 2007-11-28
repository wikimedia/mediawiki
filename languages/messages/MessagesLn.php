<?php
/**
 * Lingala language
 *
 * @addtogroup Language
 *
 * @author SPQRobin
 */

$fallback = 'fr';

$linkPrefixExtension = true;

# Same as the French (bug 8485)
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# Dates
'october'      => 'ɔkɔtɔ́bɛ',
'november'     => 'novɛ́mbɛ',
'october-gen'  => 'ɔkɔtɔ́bɛ',
'november-gen' => 'novɛ́mbɛ',
'oct'          => 'ɔkɔ',
'nov'          => 'nov',

'qbspecialpages' => 'Nkásá ya ndéngé isúsu',
'mytalk'         => 'Ntembe na ngáí',
'navigation'     => 'Botamboli',

'search'           => 'Boluki',
'history_short'    => 'likambo',
'printableversion' => 'Mpɔ́ na kofínela',
'permalink'        => 'Ekangeli ya ntángo yɔ́nsɔ',
'print'            => 'kobimisa nkomá',
'edit'             => 'Kokoma',
'delete'           => 'Kolímwisa',
'talkpagelinktext' => 'Ntembe',
'talk'             => 'Ntembe',
'otherlanguages'   => 'Na nkótá isúsu',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage' => 'Lokásá ya libosó',
'portal'   => 'Bísó na bísó',

'ok'      => 'Nandimi',
'hidetoc' => 'kobomba',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'     => 'ekakoli',
'nstab-image'    => 'elilingi',
'nstab-help'     => 'Bosálisi',
'nstab-category' => 'loléngé',

# History pages
'next' => 'bolɛngɛli',

# Search results
'prevn' => '$1 ya libosó',
'nextn' => 'bolɛngɛli $1',

# Preferences page
'preferences'   => 'Malúli',
'mypreferences' => 'Malúli ma ngáí',
'prefs-rc'      => 'Mbóngwana ya nsúka',

# Recent changes
'recentchanges' => 'Mbóngwana ya nsúka',
'hist'          => 'likambo',
'hide'          => 'kobomba',

# File deletion
'filedelete-submit' => 'Kolímwisa',

# Miscellaneous special pages
'randompage' => 'Lokásá epɔní tɛ́',
'newpages'   => 'Ekakoli ya sika',
'move'       => 'Kobóngola nkómbó',

# Watchlist
'mywatchlist' => 'Nkásá nalandí',
'watch'       => 'Kolanda',
'unwatch'     => 'Kolanda tɛ́',

# Contributions
'mycontris' => 'Nkásá nakomí',

# Browsing diffs
'previousdiff' => '← diff ya libosó',

);
