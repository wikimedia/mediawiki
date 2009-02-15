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
'tog-fancysig'       => 'Signatua ohne Valinkung zu da Benutzaseitn',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|Kategorie|Kategorien}}',
'category_header'               => 'Seitn in da Kategorie „$1“',
'subcategories'                 => 'Untakategorien',
'category-media-header'         => 'Medien in da Kategorie „$1“',
'category-empty'                => "''De Kategorie enthåit momentan kane Seitn und kane Medien ned.''",
'hidden-category-category'      => 'Vasteckte Kategorie', # Name of the category where hidden categories will be listed
'category-subcat-count'         => "{{PLURAL:$2|De Kategorie enthåit netta de foignde Untakategorie:|{{PLURAL:$1|D'foignde Untakategorie is ane vu insgsamt $2 Untakategorien in dea Kategorie:|Vu insgsamt $2 Untakategorien in dea Kategorie wean $1 ãzoagt:}}}}",
'category-subcat-count-limited' => 'In de Kategorie {{PLURAL:$1|is de foignde Untakategorie|san de foigndn Untakategorien}} eisoatiad:',
'category-file-count-limited'   => "{{PLURAL:$1|D'foignde Datei is|De foigndn $1 Datein san}} in de Kategorie eisoatiad:",

'mainpagetext' => 'MediaWiki is eafoigreich installiad woan.',

'about'    => 'Üba',
'cancel'   => 'Åbbrecha',
'mypage'   => 'Eigne Seitn',
'mytalk'   => 'Eigne Diskussion',
'anontalk' => 'Diskussionsseitn vo dera IP',

'edit'              => 'werkln',
'delete'            => 'löschn',
'unprotect'         => 'freigem',
'unprotectthispage' => 'Schutz aufhem',
'talk'              => 'bschprecha',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Üba {{SITENAME}}',
'aboutpage'            => 'Project:Üba_{{SITENAME}}',
'mainpage'             => 'Hauptsaitn',
'mainpage-description' => 'Hauptsaitn',

'ok'                      => 'haut hi',
'youhavenewmessagesmulti' => 'Sie ham neie Nachrichten: $1',

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
'whitelistedittitle' => 'Zum Bearbatn miaßn Sie si oomeidn',
'whitelistedittext'  => 'Sie miaßn si $1, um Seiten bearbatn zum kinna.',
'accmailtitle'       => 'Passwort is vaschickt worn',
'newarticle'         => '(Nei)',
'yourtext'           => 'Eana Text',

# History pages
'histlast'     => 'Neiste',
'historyempty' => '(laa)',

# Preferences page
'mypreferences' => 'Eistellunga',

# Upload
'watchthisupload' => 'De Seitn beobachtn',

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
'watchthispage'     => 'Seitn beobachtn',
'unwatch'           => 'nimma beobachten',

# Protect
'prot_1movedto2' => 'håt [[$1]] nåch [[$2]] verschom',

# Contributions
'contributions' => 'Benutzerbeiträg',
'mycontris'     => 'Eigene Beiträg',

# What links here
'whatlinkshere' => 'Links auf de Seitn',

# Block/unblock
'blocklink' => 'sperrn',

# Move page
'move-page-legend' => 'Seitn vaschiam',
'move-watch'       => 'De Seitn beobachten',
'1movedto2'        => 'håt [[$1]] nåch [[$2]] verschom',
'1movedto2_redir'  => 'håt [[$1]] nåch [[$2]] verschom und dabei a Weiterleitung überschriem',

# Namespace 8 related
'allmessagesname'     => 'Nam',
'allmessagescurrent'  => 'Aktuella Text',
'allmessagestext'     => 'Des is a Listn vo de MediaWiki-Systemtexte.
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net Betawiki] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesmodified' => 'Nur geänderte zoagn',

# Tooltip help for the actions
'tooltip-t-upload' => 'Datein aufelådn',

# Special:NewFiles
'newimages' => 'Neie Dateien',

);
