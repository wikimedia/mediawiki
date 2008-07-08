<?php
/** Lingala (Lingála)
 *
 * @ingroup Language
 * @file
 *
 * @author SPQRobin
 * @author Eruedin
 */

$fallback = 'fr';

$linkPrefixExtension = true;

# Same as the French (bug 8485)
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# Dates
'monday'        => 'mokɔlɔ ya libosó',
'tuesday'       => 'mokɔlɔ ya míbalé',
'wednesday'     => 'mokɔlɔ ya mísáto',
'thursday'      => 'mokɔlɔ ya mínei',
'friday'        => 'mokɔlɔ ya mítáno',
'sun'           => 'eye',
'mon'           => 'm1',
'tue'           => 'm2',
'wed'           => 'm3',
'thu'           => 'm4',
'fri'           => 'm5',
'sat'           => 'mps',
'january'       => 'yanwáli',
'february'      => 'febwáli',
'march'         => 'mársi',
'april'         => 'apríli',
'may_long'      => 'máyí',
'june'          => 'yúni',
'july'          => 'yúli',
'august'        => 'augústo',
'september'     => 'sɛtɛ́mbɛ',
'october'       => 'ɔkɔtɔ́bɛ',
'november'      => 'novɛ́mbɛ',
'december'      => 'dɛsɛ́mbɛ',
'january-gen'   => 'yanwáli',
'february-gen'  => 'febwáli',
'march-gen'     => 'mársi',
'april-gen'     => 'apríli',
'may-gen'       => 'máyí',
'june-gen'      => 'yúni',
'july-gen'      => 'yúli',
'august-gen'    => 'augústo',
'september-gen' => 'sɛtɛ́mbɛ',
'october-gen'   => 'ɔkɔtɔ́bɛ',
'november-gen'  => 'novɛ́mbɛ',
'december-gen'  => 'dɛsɛ́mbɛ',
'jan'           => 'yan',
'feb'           => 'feb',
'mar'           => 'már',
'apr'           => 'apr',
'may'           => 'máy',
'jun'           => 'yún',
'jul'           => 'yúl',
'aug'           => 'aug',
'sep'           => 'sɛt',
'oct'           => 'ɔkɔ',
'nov'           => 'nov',
'dec'           => 'dɛs',

# Categories related messages
'category_header' => 'Bikakoli o molɔngɔ́ ya bilɔkɔ ya loléngé mɔ̌kɔ́ « $1 »',

'about'          => 'elɔ́kɔ elobámí',
'article'        => 'ekakoli',
'cancel'         => 'Kozóngela',
'qbedit'         => 'kobalusa',
'qbspecialpages' => 'Nkásá ya ndéngé isúsu',
'mytalk'         => 'Ntembe na ngáí',
'navigation'     => 'Botamboli',
'and'            => 'mpé',

'errorpagetitle'   => 'Mbéba',
'tagline'          => 'Artíclɛ ya {{SITENAME}}.',
'help'             => 'Bosálisi',
'search'           => 'Boluki',
'searchbutton'     => 'Boluki',
'go'               => 'kokɛndɛ',
'searcharticle'    => 'Kɛndɛ́',
'history_short'    => 'likambo',
'printableversion' => 'Mpɔ́ na kofínela',
'permalink'        => 'Ekangeli ya ntángo yɔ́nsɔ',
'print'            => 'kobimisa nkomá',
'edit'             => 'Kokoma',
'editthispage'     => 'Kokoma lokásá loye',
'delete'           => 'Kolímwisa',
'protect'          => 'Kobátela',
'unprotect'        => 'Kobátela tɛ̂',
'talkpagelinktext' => 'Ntembe',
'talk'             => 'Ntembe',
'views'            => 'Bomɔ́niseli',
'toolbox'          => 'Bisáleli',
'otherlanguages'   => 'Na nkótá isúsu',
'redirectedfrom'   => '(Eyendísí útá $1)',
'redirectpagesub'  => 'Lokásá la boyendisi',
'jumptosearch'     => 'boluki',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'elɔ́kɔ elobí {{SITENAME}}',
'currentevents'        => 'Elɔ́kɔ ya sika',
'edithelp'             => 'Kokoma bosálisi',
'mainpage'             => 'Lokásá ya libosó',
'mainpage-description' => 'Lokásá ya libosó',
'portal'               => 'Bísó na bísó',

'ok'                 => 'Nandimi',
'youhavenewmessages' => 'Nazweí $1 ($2).',
'newmessageslink'    => 'monɔkɔ ya sika',
'editsection'        => 'kokoma',
'editold'            => 'kokoma',
'toc'                => 'Etápe',
'showtoc'            => 'komɔ́nisa',
'hidetoc'            => 'kobomba',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'ekakoli',
'nstab-image'    => 'elilingi',
'nstab-help'     => 'Bosálisi',
'nstab-category' => 'loléngé',

# General errors
'error' => 'Mbéba',

# Login and logout pages
'yourname'                => 'Nkómbó ya ekitoli :',
'yourpasswordagain'       => 'Banda naíno :',
'login'                   => 'komíkitola (log in)',
'nav-login-createaccount' => 'Komíkomisa tǒ kokɔtɔ',
'userlogin'               => 'Komíkomisa tǒ kokɔtɔ',
'logout'                  => 'kolongwa',
'userlogout'              => 'kolongwa (log out)',
'nologin'                 => 'Omíkomísí naíno tɛ̂ ? $1.',
'nologinlink'             => 'Míkomísá yɔ̌-mɛ́i',
'gotaccount'              => 'Omíkomísí naíno ? $1.',
'createaccountmail'       => 'na mokánda',
'youremail'               => 'Mokandá (e-mail) *',
'username'                => 'Nkómbó ya ekitoli :',
'yourrealname'            => 'nkómbó ya sɔ̂lɔ́ *',
'yourlanguage'            => 'Lokótá',
'email'                   => 'Mokánda',

# Edit pages
'summary'        => 'Likwé ya mokusé',
'minoredit'      => 'Eza mbóngwana ya mokɛ́',
'watchthis'      => 'Kolanda lokásá loye',
'savearticle'    => 'kobómbisa ekakoli',
'editing'        => 'Kokoma « $1 »',
'editingcomment' => 'Kokoma « $1 » (ndimbola)',
'yourtext'       => 'Nkomá na yɔ̌',

# History pages
'cur'        => 'sika',
'next'       => 'bolɛngɛli',
'last'       => 'ya nsúka',
'deletedrev' => '[elímwísámí]',

# Revision deletion
'rev-delundel' => 'komɔ́nisa/kobomba',

# Diffs
'lineno'   => 'Mokɔlɔ́tɔ $1 :',
'editundo' => 'kozóngela',

# Search results
'prevn'        => '$1 ya libosó',
'nextn'        => 'bolɛngɛli $1',
'viewprevnext' => 'Komɔ́na ($1) ($2) ($3)',
'powersearch'  => 'Boluki',

# Preferences page
'preferences'       => 'Malúli',
'mypreferences'     => 'Malúli ma ngáí',
'dateformat'        => 'bokomi ya mokɔlɔ',
'datetime'          => 'Mokɔlɔ mpé ntángo',
'prefs-rc'          => 'Mbóngwana ya nsúka',
'saveprefs'         => 'kobómbisa',
'searchresultshead' => 'Boluki',
'allowemail'        => 'Enable mokánda from other users',

# Groups
'group-sysop' => 'Bayángeli',

'group-sysop-member' => 'Moyángeli',

# Recent changes
'recentchanges'   => 'Mbóngwana ya nsúka',
'rcnote'          => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana '''1'''|izalí mbóngwana '''$1'''}} o {{PLURAL:$2|mokɔlɔ|mikɔlɔ '''$2'''}} ya nsúka, {{PLURAL:$1|etángámí|itángámí}} o $3.",
'rcshowhideminor' => '$1 mbóngwana ya mokɛ́',
'rcshowhidemine'  => '$1 mbóngwana ya ngáí',
'rclinks'         => 'Komɔ́nisa mbóngwana $1 ya nsúka o mikɔ́lɔ $2<br />$3',
'diff'            => 'mbó.',
'hist'            => 'likambo',
'hide'            => 'kobomba',
'show'            => 'Komɔ́nisa',
'minoreditletter' => 'm',
'newpageletter'   => 'S',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked' => 'Bolandi ekangisi',

# Upload
'upload'    => 'Kokumbisa (elilingi)',
'uploadbtn' => 'kokumbisa',
'savefile'  => 'kobómbisa kásá-kásá',

# Special:Imagelist
'imagelist_date' => 'Mokɔlɔ',

# File deletion
'filedelete-submit' => 'Kolímwisa',

# Random page
'randompage' => 'Lokásá epɔní tɛ́',

# Statistics
'statistics' => 'Mitúya',
'sitestats'  => 'Mitúya mya {{SITENAME}}',

'disambiguations' => 'Bokokani',

'doubleredirects' => 'Boyendisi mbala míbalé',

# Miscellaneous special pages
'nmembers'          => '{{PLURAL:$1|ekakoli|bikakoli}} $1',
'shortpages'        => 'Nkásá ya mokúsé',
'longpages'         => 'Nkásá ya molaí',
'newpages'          => 'Ekakoli ya sika',
'newpages-username' => 'Nkómbó ya ekitoli :',
'move'              => 'Kobóngola nkómbó',

# Book sources
'booksources-go' => 'Kɛndɛ́',

# Special:Allpages
'allpages'       => 'Nkásá ínsɔ',
'alphaindexline' => '$1 kina $2',
'nextpage'       => 'Lokásá ya nsima ($1)',
'prevpage'       => 'Lokasá ya libosó ($1)',
'allpagesprev'   => '< ya libosó',
'allpagesnext'   => 'bolɛngɛli >',
'allpagessubmit' => 'kokɛndɛ',

# Special:Categories
'categories' => '{{PLURAL:$1|Loléngé|Ndéngé}}',

# E-mail user
'defemailsubject' => '{{SITENAME}} mokánda',
'emailfrom'       => 'útá',
'emailto'         => 'epái',
'emailmessage'    => 'Nsango',
'emailsend'       => 'kotínda',
'emailsent'       => 'mokandá etíndámá',
'emailsenttext'   => 'Mokandá ya yɔ̌ etíndámá',

# Watchlist
'watchlist'            => 'Nkásá nalandí',
'mywatchlist'          => 'Nkásá nalandí',
'watch'                => 'Kolanda',
'watchthispage'        => 'Kolanda lokásá loye',
'unwatch'              => 'Kolanda tɛ́',
'watchlist-details'    => '{{PLURAL:$1|lokásá $1 lolandámí|nkásá $1 ilandámí}}, longola nkásá ya ntembe.',
'wlnote'               => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana ya nsúka|izalí mbóngwana '''$1''' ya nsúka}} o {{PLURAL:$2|ngonga|ngonga '''$2'''}} ya nsúka.",
'wlshowlast'           => 'Komɔ́nisa ngónga $1 ya nsúka, mikɔ́lɔ $2 mya nsúka tǒ $3',
'watchlist-show-bots'  => 'Komɔ́nisa mbóngwana ya bot',
'watchlist-hide-bots'  => 'Kobomba mbóngwana ya bot',
'watchlist-show-own'   => 'Komɔ́nisa mbóngwana ya ngáí',
'watchlist-hide-own'   => 'Kobomba mbóngwana ya ngáí',
'watchlist-show-minor' => 'Komɔ́nisa mbóngwana ya mokɛ́',
'watchlist-hide-minor' => 'Kobomba mbóngwana ya mokɛ́',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Bonɔ́ngi...',

'created' => 'ekomákí',

# Delete/protect/revert
'deletedarticle' => 'elímwísí "[[$1]]"',
'dellogpage'     => 'Zonálɛ ya bolímwisi',
'deletionlog'    => 'zonálɛ ya bolímwisi',
'deletecomment'  => 'Ntína ya bolímwisi',

# Restrictions (nouns)
'restriction-edit' => 'Kokoma',
'restriction-move' => 'Kobóngola nkómbó',

# Contributions
'mycontris' => 'Nkásá nakomí',

# What links here
'whatlinkshere'       => 'Ekangísí áwa',
'isredirect'          => 'Lokásá ya boyendisi',
'whatlinkshere-links' => '← ekangisi',

# Move page
'movearticle'             => 'Kobóngola nkómbó ya ekakoli :',
'move-watch'              => 'Kolánda lokásá loye',
'movepagebtn'             => 'Kobóngola lokásá',
'movedto'                 => 'nkómbó ya sika',
'delete_and_move'         => 'Kolímwisa mpé kobóngola nkómbó',
'delete_and_move_confirm' => 'Boye, kolímwisa lokásá',
'delete_and_move_reason'  => 'Ntína ya bolímwisi mpé bobóngoli bwa nkómbó',

# Export
'export'        => 'komɛmɛ na...',
'export-submit' => 'Komɛmɛ',

# Thumbnails
'thumbnail-more' => 'Koyéisa monɛ́nɛ',

# Special:Import
'import' => 'koútisa...',

# Tooltip help for the actions
'tooltip-pt-userpage'    => 'Lokásá la ngáí',
'tooltip-pt-mytalk'      => 'Lokásá ntembe la ngáí',
'tooltip-pt-preferences' => 'Malúli ma ngáí',
'tooltip-pt-watchlist'   => 'Nkásá nalandí mpɔ̂ na mbóngwana',
'tooltip-pt-mycontris'   => 'Nkásá nakomí',
'tooltip-search'         => 'Boluki {{SITENAME}}',
'tooltip-p-logo'         => 'Lokásá ya libosó',
'tooltip-n-mainpage'     => 'Kokɛndɛ na Lokásá ya libosó',

# Browsing diffs
'previousdiff' => '← diff ya libosó',

# Special:Newimages
'ilsubmit' => 'Boluki',

# EXIF tags
'exif-artist' => 'Mokeli',

'exif-subjectdistancerange-2' => 'kokanga view',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'nyɔ́nsɔ',

# HTML dump
'redirectingto' => 'Eyendísí na [[$1]]...',

# action=purge
'confirm_purge_button' => 'Nandimi',

# Multipage image navigation
'imgmultigo' => 'Kɛndɛ́!',

# Table pager
'table_pager_limit_submit' => 'kokɛndɛ',

# Special:SpecialPages
'specialpages' => 'Nkásá ya ndéngé mosúsu',

);
