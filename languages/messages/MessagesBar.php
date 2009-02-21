<?php
/** Bavarian (Boarisch)
 *
 * @ingroup Language
 * @file
 *
 * @author Malafaya
 * @author Man77
 * @author Metalhead64
 */

$fallback = 'de';

$messages = array(
# User preference toggles
'tog-watchcreations' => 'Vu mia söiwa nei eigstöide Seitn automatisch beobåchtn',
'tog-watchdefault'   => 'Vu mia söiwa beåabeitete und vu mia nei eigstöide Seitn automatisch beobåchtn',
'tog-watchmoves'     => 'Vu mia söiwa vaschomne Seitn automatisch beobåchtn',
'tog-watchdeletion'  => 'Vu mia söiwa glöschte Seitn automatisch beobåchtn',
'tog-minordefault'   => 'De eigenen Ändarungen standardmäßig åis geringfügig markian',
'tog-fancysig'       => 'Signatua ohne Valinkung zu da Benutzaseitn',
'tog-showhiddencats' => 'Vasteckte Kategorien ãnzoang',

'underline-always' => 'imma',
'underline-never'  => 'nia',

# Dates
'sat'       => 'Sa',
'january'   => 'Jänna',
'april'     => 'Aprüi',
'may_long'  => 'Mai',
'june'      => 'Juni',
'july'      => 'Juli',
'august'    => 'August',
'september' => 'Septemba',
'october'   => 'Oktowa',
'november'  => 'Novemba',
'december'  => 'Dezemba',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'                => 'Seitn in da Kategorie „$1“',
'subcategories'                  => 'Untakategorien',
'category-media-header'          => 'Medien in da Kategorie „$1“',
'category-empty'                 => "''De Kategorie enthåit momentan kane Seitn und kane Medien ned.''",
'hidden-categories'              => '{{PLURAL:$1|Vasteckte Kategorie|Vasteckte Kategorien}}',
'hidden-category-category'       => 'Vasteckte Kategorie', # Name of the category where hidden categories will be listed
'category-subcat-count'          => "{{PLURAL:$2|De Kategorie enthåit netta de foignde Untakategorie:|{{PLURAL:$1|D'foignde Untakategorie is ane vu insgsamt $2 Untakategorien in dea Kategorie:|Vu insgsamt $2 Untakategorien in dea Kategorie wean $1 ãzoagt:}}}}",
'category-subcat-count-limited'  => 'In de Kategorie {{PLURAL:$1|is de foignde Untakategorie|san de foigndn Untakategorien}} eisoatiad:',
'category-article-count-limited' => 'De {{PLURAL:$1|foignde Seitn is|foigndn $1 Seitn san}} in dea Kategorie enthåitn:',
'category-file-count-limited'    => "{{PLURAL:$1|D'foignde Datei is|De foigndn $1 Datein san}} in de Kategorie eisoatiad:",

'mainpagetext' => 'MediaWiki is eafoigreich installiad woan.',

'about'          => 'Üba',
'newwindow'      => '(wiad in am neichn Fensta aufgmåcht)',
'cancel'         => 'Åbbrecha',
'qbmyoptions'    => 'Meine Seitn',
'qbspecialpages' => 'Spezialseitn',
'mypage'         => 'Eigne Seitn',
'mytalk'         => 'Eigne Diskussion',
'anontalk'       => 'Diskussionsseitn vo dera IP',
'and'            => '&#32;und',

'tagline'           => 'Aus {{SITENAME}}',
'history'           => 'Versionen',
'updatedmarker'     => '(gändat)',
'printableversion'  => 'Version zum Ausdruckn',
'edit'              => 'werkln',
'create'            => 'Erstöin',
'editthispage'      => 'Seitn beårbeitn',
'create-this-page'  => 'Seitn eastöin',
'delete'            => 'löschn',
'deletethispage'    => 'De Seitn löschn',
'undelete_short'    => '{{PLURAL:$1|1 Version|$1 Versionen}} wiedaheastöin',
'protect'           => 'Schützn',
'protect_change'    => 'ändan',
'protectthispage'   => 'Seitn schützn',
'unprotect'         => 'freigem',
'unprotectthispage' => 'Schutz aufhem',
'newpage'           => 'Neiche Seitn',
'talkpagelinktext'  => 'bschprecha',
'talk'              => 'bschprecha',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Üba {{SITENAME}}',
'aboutpage'            => 'Project:Üba_{{SITENAME}}',
'disclaimers'          => 'Impressum',
'mainpage'             => 'Hauptsaitn',
'mainpage-description' => 'Hauptsaitn',
'privacy'              => 'Datnschutz',

'ok'                      => 'haut hi',
'retrievedfrom'           => 'Vu „$1“',
'youhavenewmessagesmulti' => 'Sie ham neie Nachrichten: $1',
'editsection'             => 'werkln',
'red-link-title'          => "$1 (de Seitn gibt's ned)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-special' => 'Spezialseitn',
'nstab-image'   => 'Datei',

# General errors
'filedeleteerror' => 'De Datei „$1“ håt net glöscht wern kinna.',

# Login and logout pages
'yourname'                   => 'Benutzernam:',
'yourdomainname'             => 'Eanane Domain:',
'notloggedin'                => 'Net ogmeldt',
'yourrealname'               => 'Echter Nam:',
'yourlanguage'               => 'Sprache vo da Benutzeroberfläche:',
'wrongpassword'              => "Des Passwort is falsch (oda fehlt). Bitte probier's no amoi.",
'wrongpasswordempty'         => 'Des eigemne Passwort is laar gwen. Bitte no amoi probiern.',
'acct_creation_throttle_hit' => 'Du hosst scho $1 {{PLURAL:$1|Benutzakonto|Benutzakonten}} und konnst iatzat koane mehr oleng.',
'accountcreated'             => 'Benutzerkonto is erstellt worn',
'accountcreatedtext'         => "'s Benutzerkonto $1 is eigricht worn.",

# Edit pages
'watchthis'          => 'De Seitn beobachtn',
'showdiff'           => 'Ändarungen zoang',
'whitelistedittitle' => 'Zum Bearbatn miaßn Sie si oomeidn',
'whitelistedittext'  => 'Sie miaßn si $1, um Seiten bearbatn zum kinna.',
'accmailtitle'       => 'Passwort is vaschickt worn',
'newarticle'         => '(Nei)',
'yourtext'           => 'Eana Text',

# History pages
'histlast'     => 'Neiste',
'historyempty' => '(laa)',

# Revision deletion
'rev-delundel' => 'zoang/vastecka',

# Merge log
'revertmerge'      => 'Vareinigung zrucknehma',
'mergelogpagetext' => "Des is s'Logbuach vu de vareinigtn Versionsgschichtn.",

# Diffs
'history-title' => 'Versionsgschicht vu „$1“',
'editundo'      => 'rückgängig',

# Search results
'searchresults-title'   => 'Eagebnisse vu da Suach nåch „$1“',
'searchresulttext'      => "Fia weidare Infos üwa's Suacha schau auf'd [[{{MediaWiki:Helppage}}|Hüifeseitn]].",
'searchsubtitle'        => 'Dei Suachãnfråg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|ålle Seitn, de mid „$1“ ãnfãngan]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ålle Seitn, de wås nåch „$1“ valinkn]])',
'searchsubtitleinvalid' => 'Dei Suachãnfråg: „$1“.',
'noexactmatch'          => "'''Es gibt ka Seitn mi'm Titl „$1“.'''
Wãnn'st di mid dem Thema auskennst, kãnnst [[:$1|de Seitn söiwa schreim]].",
'search-result-size'    => '$1 ({{PLURAL:$2|1 Woat|$2 Wöata}})',
'powersearch-redir'     => 'Weidaleitungen ãnzoang',

# Preferences page
'mypreferences' => 'Eistellunga',

# Recent changes
'minoreditletter' => 'K',

# Upload
'upload'          => 'Aufelådn',
'watchthisupload' => 'De Seitn beobachtn',

# File description page
'filehist-datetime' => 'Version vum',

# Miscellaneous special pages
'newpages'     => 'Neie Seitn',
'ancientpages' => 'Scho länger nimma bearbate Artikel',
'move'         => 'vaschiam',

# Special:Log
'all-logs-page' => 'Alle Logbiacha',

# Special:AllPages
'allpages'          => 'Alle Seitn',
'allarticles'       => 'Alle Seitn',
'allinnamespace'    => 'Alle Seitn (Namensraum: $1)',
'allnotinnamespace' => 'Alle Seitn (net im $1 Namensraum)',
'allpagesprev'      => 'Vorige',
'allpagessubmit'    => 'Owendn',
'allpagesprefix'    => 'Seitn zoagn mit Präfix:',

# Watchlist
'mywatchlist'       => 'Beobachtungslistn',
'watchlistanontext' => 'Sie miaßn si $1, um Eanane Beobachtungslistn zum seng oda Einträge auf ihr zum bearbatn.',
'watchnologin'      => 'Sie san net ogmeidt',
'watch'             => 'Beobåchtn',
'watchthispage'     => 'Seitn beobachtn',
'unwatch'           => 'nimma beobachten',
'unwatchthispage'   => 'Nimma beobåchtn',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Beobåchtn …',

# Delete
'deletedarticle' => 'håd „[[$1]]“ glöscht',

# Protect
'prot_1movedto2' => 'håt [[$1]] nåch [[$2]] verschom',

# Namespace form on various pages
'namespace'      => 'Nãmensraum:',
'blanknamespace' => '(Seitn)',

# Contributions
'contributions' => 'Benutzerbeiträg',
'mycontris'     => 'Eigene Beiträg',

# What links here
'whatlinkshere' => 'Links auf de Seitn',

# Block/unblock
'blocklink'    => 'sperrn',
'unblocklink'  => 'freigem',
'contribslink' => 'Beiträge',

# Move page
'move-page-legend' => 'Seitn vaschiam',
'move-watch'       => 'De Seitn beobachten',
'1movedto2'        => 'håt [[$1]] nåch [[$2]] verschom',
'1movedto2_redir'  => 'håt [[$1]] nåch [[$2]] verschom und dabei a Weiterleitung überschriem',
'revertmove'       => 'zruck vaschiam',
'delete_and_move'  => 'Löschn und vaschiam',

# Namespace 8 related
'allmessagesname'     => 'Nam',
'allmessagescurrent'  => 'Aktuella Text',
'allmessagestext'     => 'Des is a Listn vo de MediaWiki-Systemtexte.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesmodified' => 'Nur geänderte zoagn',

# Tooltip help for the actions
'tooltip-ca-move'    => 'De Seitn vaschiam',
'tooltip-n-mainpage' => "d'Hauptseitn ãnzoang",
'tooltip-t-upload'   => 'Datein aufelådn',

# Special:NewFiles
'newimages' => 'Neie Dateien',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ålle',

);
