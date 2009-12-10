<?php
/** Hausa (هَوُسَ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Mladanali
 */

$messages = array(
# User preference toggles
'tog-underline'               => 'A shaya zaruruwa',
'tog-highlightbroken'         => 'A haskaka katsattsin zaruruwa <a href="" class="new">kamar haka</a> (koko: kamar haka<a href="" class="internal">?</a>)',
'tog-justify'                 => 'A daidaita sakin layuka',
'tog-hideminor'               => 'A ɓoye ƙananan gyare-gyare na baya-bayan nan',
'tog-hidepatrolled'           => 'A ɓoye gyare-gyaren kan ido a cikin gyare-gyare bayan-bayan nan',
'tog-newpageshidepatrolled'   => 'A ɓoye shafuna kan ido a cikin sabbin shafuna',
'tog-extendwatchlist'         => 'A faɗaɗa jerin kan ido ya nuna duka gyare-gyare, ba na baya-bayan nan kawai ba',
'tog-usenewrc'                => 'A yi amfani da kyautattun gyare-gyare na baya-bayan nan (ana buƙatar Javascript)',
'tog-numberheadings'          => 'A lambace kanun matani kai tsaye',
'tog-showtoolbar'             => 'A nuna sandar kayan aiki ta gyarawa (ana buƙatar JavaScript)',
'tog-editondblclick'          => 'A gyara shafuna da dabar-kiliki (ana buƙatar JavaScript)',
'tog-editsection'             => 'A lamunta gyara sashe ta hanyar zaruruwan [gyarawa]',
'tog-editsectiononrightclick' => 'A lamunta gyara shashe da kilikin dama a kan kanun shashe (ana buƙatar JavaScript)',
'tog-showtoc'                 => 'A nuna jadawalin kanu (cikin shafuna masu fiye da kanu 3)',
'tog-rememberpassword'        => 'A adana bayanan loginkina a wannan kwamfyuta',
'tog-editwidth'               => 'A faɗaɗa sararin gyarawa ya cika duka bangon',
'tog-watchcreations'          => 'A daɗa shafunan da na ƙirƙira a cikin jerina na kan ido',
'tog-watchdefault'            => 'A daɗa shafunan da na gyara a cikin jerina na kan ido',
'tog-watchmoves'              => 'A daɗa shafunan da na gusar a cikin jerina na kan ido',
'tog-watchdeletion'           => 'A daɗa shafunan da na shafe a cikin jerina na kan ido',

# Dates
'january'   => 'Janairu',
'february'  => 'Faburairu',
'march'     => 'Maris',
'april'     => 'Afirilu',
'may_long'  => 'Mayu',
'june'      => 'Yuni',
'july'      => 'Yuli',
'august'    => 'Agusta',
'september' => 'Satumba',
'october'   => 'Oktoba',
'november'  => 'Nuwamba',
'december'  => 'Disamba',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Rukuni|Rukunoni}}',

'navigation' => 'Shawagi',

'tagline'          => 'Daga {{SITENAME}}',
'search'           => 'Nema',
'searchbutton'     => 'Binciko',
'searcharticle'    => 'Mu je',
'history_short'    => 'Tarihi',
'printableversion' => 'Sigar bugawa',
'permalink'        => 'Dawwamammen zare',
'edit'             => 'Gyarawa',
'talkpagelinktext' => 'Hira',
'personaltools'    => 'Zaɓaɓɓin kayan aiki',
'talk'             => 'Mahawara',
'views'            => 'Hange',
'toolbox'          => 'Akwatin kayan aiki',
'otherlanguages'   => 'A wasu harsuna',
'lastmodifiedat'   => 'Sauyin baya na wannan shafi ran $1, a $2.',
'jumpto'           => 'A tsallaka zuwa:',
'jumptonavigation' => 'Shawagi',
'jumptosearch'     => 'Nema',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'Game da {{SITENAME}}',
'aboutpage'      => 'Project:Game da',
'copyright'      => 'Bayannai sun samu a ƙarƙashin $1.',
'disclaimers'    => 'Gargaɗi',
'disclaimerpage' => 'Project:Babban gargaɗi',
'mainpage'       => 'Marhabin',
'privacy'        => 'Manufar kare sirri',
'privacypage'    => 'Project:Manufar kare sirri',

'retrievedfrom'   => 'Daga "$1"',
'editsection'     => 'gyarawa',
'editsectionhint' => 'Gyara sashe: $1',
'site-rss-feed'   => 'Kwararen RSS na $1',
'site-atom-feed'  => 'Kwararen Atom na $1',
'red-link-title'  => '$1 (babu wannan shafi)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'Shafi',
'nstab-special' => 'Shafi na musamman',

# General errors
'missing-article' => 'Taskar bayannai ba ta samo matanin wani shafin da ya kamata ta samo ba, mai suna "$1" $2.

Mafarin haka yawanci shi ne zare mai zuwa ga shafin da aka shafe ko gusar.

In ba haka ba ne, to kun takalo wata tangarɗa a furogiram kin.
Don Allah a aika ruhoto zuwa ga [[Special:ListUsers/sysop|administrator]], tare da nuna URL kin.',

# Login and logout pages
'nav-login-createaccount' => 'login ko buɗa akwanti',

# Diffs
'lineno'   => 'Layi $1:',
'editundo' => 'Janyewa',

# Search results
'searchresults'             => 'Sakamakon bincike',
'searchresults-title'       => 'Sakamakon bincike na "$1"',
'searchsubtitle'            => 'Kun nemi \'\'\'[[:$1]]\'\'\'  ([[Special:Prefixindex/$1|duka shafuna masu farawa da "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|duka shafuna masu zare zuwa "$1"]])',
'viewprevnext'              => 'A duba ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 kalma|$2 kalmomi}})',
'search-mwsuggest-enabled'  => 'Tare da shawarwari',
'search-mwsuggest-disabled' => 'Banda shawarwari',

# Recent changes
'hist'            => 'Tarihi',
'minoreditletter' => 'm',

# Recent changes linked
'recentchangeslinked' => 'Sauye-sauye masu dangantaka',

# Upload
'upload' => 'Girke fayil',

# Namespace form on various pages
'blanknamespace' => '(Babba)',

# What links here
'whatlinkshere' => 'Zaruruwan wannan shafi',

# Block/unblock
'blocklink'    => 'Hanawa',
'contribslink' => 'Gudummuwa',

# Tooltip help for the actions
'tooltip-pt-login'               => 'Ana shawarar ku shiga akwantinku, amma ba dole ba ne',
'tooltip-ca-talk'                => 'Mahawara kan shafin bayannai',
'tooltip-ca-edit'                => 'Ana iya gyara wannan shafi
A yi amfani da maɓallin tantancewa kafin a adina',
'tooltip-ca-history'             => 'Tsoffin sufofin wannan shafi',
'tooltip-search'                 => 'Binciko {{SITENAME}}',
'tooltip-search-go'              => 'A je ga shafi mai wannan suna idan akwai shi',
'tooltip-search-fulltext'        => 'Binciko shafuka masu wannan matani',
'tooltip-n-mainpage'             => 'Duba shafin Marhabin',
'tooltip-n-mainpage-description' => 'Duba shafin marhabin',
'tooltip-n-portal'               => 'A game da wannan shiri, abinda za a iya yi, ina za a samu abubuwa',
'tooltip-n-recentchanges'        => 'Jerin sabin sauye-sauye a wannan wiki',
'tooltip-n-randompage'           => 'A nuno wani shafi da ka',
'tooltip-n-help'                 => 'A nuno taimako',
'tooltip-t-whatlinkshere'        => 'Jerin duk shafunan Wiki da ke da zare a nan',
'tooltip-t-recentchangeslinked'  => 'Sauye-sauyen baya-bayan nan a shafuna masu zare daga wannan shafi',
'tooltip-t-upload'               => 'Girke fayiloli',
'tooltip-t-specialpages'         => 'Jerin duk shafuka na musamman',
'tooltip-t-print'                => 'Wannan shafi a sufar bugawa',
'tooltip-t-permalink'            => 'Zaren dindindin zuwa ga sufar baya ta wannan shafi',
'tooltip-ca-nstab-main'          => 'Duba shafin bayannai',
'tooltip-ca-nstab-special'       => 'Wannan shafi ne na musamman, ba za ku iya yi masa gyara ba',

# Bad image list
'bad_image_list' => 'Fasalin yana kamar haka:

Za a lura da layukan jeri kawai (masu farawa da *).
Zaren farko a kan layi ya kamata ya nuna fayil maras kyau.
Sauran zaruruwa a kan layin keɓaɓɓu ne, wato zuwa ga shafuna inda fayil kin zai iya kasancewa.',

# Special:SpecialPages
'specialpages' => 'Shafuka na musamman',

);
