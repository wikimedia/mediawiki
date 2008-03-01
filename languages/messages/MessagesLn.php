<?php
/** Lingala (Lingála)
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
'searchbutton'     => 'Boluki',
'history_short'    => 'likambo',
'printableversion' => 'Mpɔ́ na kofínela',
'permalink'        => 'Ekangeli ya ntángo yɔ́nsɔ',
'print'            => 'kobimisa nkomá',
'edit'             => 'Kokoma',
'delete'           => 'Kolímwisa',
'talkpagelinktext' => 'Ntembe',
'talk'             => 'Ntembe',
'toolbox'          => 'Bisáleli',
'otherlanguages'   => 'Na nkótá isúsu',
'redirectedfrom'   => '(Eyendísí útá $1)',
'redirectpagesub'  => 'Lokásá la boyendisi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage'    => 'Lokásá ya libosó',
'portal'      => 'Bísó na bísó',
'sitesupport' => 'Kofutela',

'ok'      => 'Nandimi',
'toc'     => 'Etápe',
'showtoc' => 'komɔ́nisa',
'hidetoc' => 'kobomba',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'ekakoli',
'nstab-image'    => 'elilingi',
'nstab-help'     => 'Bosálisi',
'nstab-category' => 'loléngé',

# Login and logout pages
'userlogin' => 'Komíkomisa tǒ kokɔtɔ',

# Edit pages
'summary' => 'Likwé ya mokusé',

# History pages
'next' => 'bolɛngɛli',

# Search results
'prevn' => '$1 ya libosó',
'nextn' => 'bolɛngɛli $1',

# Preferences page
'preferences'       => 'Malúli',
'mypreferences'     => 'Malúli ma ngáí',
'prefs-rc'          => 'Mbóngwana ya nsúka',
'saveprefs'         => 'kobómbisa',
'searchresultshead' => 'Boluki',

# Recent changes
'recentchanges' => 'Mbóngwana ya nsúka',
'hist'          => 'likambo',
'hide'          => 'kobomba',
'show'          => 'Komɔ́nisa',

# Recent changes linked
'recentchangeslinked' => 'Bolandi ekangisi',

# Upload
'upload'    => 'Kokumbisa (elilingi)',
'uploadbtn' => 'kokumbisa',
'savefile'  => 'kobómbisa kásá-kásá',

# File deletion
'filedelete-submit' => 'Kolímwisa',

# Random page
'randompage' => 'Lokásá epɔní tɛ́',

# Statistics
'statistics' => 'Mitúya',
'sitestats'  => 'Mitúya mya {{SITENAME}}',

# Miscellaneous special pages
'shortpages'   => 'Nkásá ya mokúsé',
'specialpages' => 'Nkásá ya ndéngé mosúsu',
'newpages'     => 'Ekakoli ya sika',
'move'         => 'Kobóngola nkómbó',

# Watchlist
'mywatchlist' => 'Nkásá nalandí',
'watch'       => 'Kolanda',
'unwatch'     => 'Kolanda tɛ́',

# Restrictions (nouns)
'restriction-edit' => 'Kokoma',
'restriction-move' => 'Kobóngola nkómbó',

# Contributions
'mycontris' => 'Nkásá nakomí',

# What links here
'whatlinkshere' => 'Ekangísí áwa',

# Tooltip help for the actions
'tooltip-pt-mytalk'      => 'Lokásá ntembe la ngáí',
'tooltip-pt-preferences' => 'Malúli ma ngáí',
'tooltip-pt-mycontris'   => 'Nkásá nakomí',
'tooltip-search'         => 'Boluki {{SITENAME}}',
'tooltip-p-logo'         => 'Lokásá ya libosó',
'tooltip-n-mainpage'     => 'Kokɛndɛ na Lokásá ya libosó',

# Browsing diffs
'previousdiff' => '← diff ya libosó',

# HTML dump
'redirectingto' => 'Eyendísí na [[$1]]...',

);
