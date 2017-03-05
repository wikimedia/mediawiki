<?php
/** Colognian (Ripoarisch)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Caesius noh en Idee vum Manes
 * @author Geitost
 * @author Matma Rex
 * @author Nemo bis
 * @author Purodha
 * @author Reedy
 * @author Rentenirer
 */

/**
 * Sources:
 * The following expressions are based on the Kölsch dictionaries:
 * Das Kölsche Wörterbuch, written by Christa Bhatt and Alice Herrwegen,
 * published by: Akademie för uns kölsche Sproch, Cologne 2005,
 * ISBN 3-7616-1942-1
 * and
 * Neuer Kölnischer Sprachschatz in 3 Bänden, written by Adam Wrede, Cologne 1958,
 * ISBN 3-7743-0155-7  ISBN 3-7743-0156-5  ISBN 3-7743-0157-3
 *
 * The grammar (especially: conjugation) is taken from:
 * De kölsche Sproch - Kurzgrammatik Kölsch / Deutsch, written by Alice Tiling-Herrwegen,
 * published by: Akademie för uns kölsche Sproch, Cologne 2002,
 * ISBN 3-7616-1604-X
 *
 * Special feature: Because of utilization in modern ripuarian literature
 * (for example: Asterix op kölsch - Däm Asterix singe Jung, ISBN 3-7704-0468-8) the rules for the letters G and J
 * are taken from Adam Wrede (for example: Jedöns, jeeße, jejovve, adich, iggelich, nüdich )
 * and not from the Akademie (for example: Gedöns, geeße, gegovve, aadig, iggelig, nüdig)
 * Otherwise most part of the following expressions are taken from the Akademie.
 *
 */
/**
 * Hints for editing
 * Avoid Ã¤ and other special codings because of legibility for those users,
 * who will take this as a basis for further ripuarian message interfaces
 * Ã¤ => ä, Ã¶ => ö, Ã¼ => ü, Ã„ => Ä, Ã– => Ö, Ãœ => Ü, ÃŸ => ß
 * â€ž => „, â€œ => “
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Medie',
	NS_SPECIAL          => 'Extra',
	NS_TALK             => 'Klaaf',
	NS_USER             => 'Metmaacher',
	NS_USER_TALK        => 'Metmaacher_Klaaf',
	NS_PROJECT_TALK     => '$1_Klaaf',
	NS_FILE             => 'Datei',
	NS_FILE_TALK        => 'Dateie_Klaaf',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Klaaf',
	NS_TEMPLATE         => 'Schablon',
	NS_TEMPLATE_TALK    => 'Schablone_Klaaf',
	NS_HELP             => 'Hölp',
	NS_HELP_TALK        => 'Hölp_Klaaf',
	NS_CATEGORY         => 'Saachjrupp',
	NS_CATEGORY_TALK    => 'Saachjruppe_Klaaf',
];

$namespaceAliases = [
	'Medium'		=> NS_MEDIA,
	'Meedije'		=> NS_MEDIA,
	'Meedijum'		=> NS_MEDIA,
	'Spezial'		=> NS_SPECIAL,
	'Shpezjal'		=> NS_SPECIAL,
	'Medmaacher'		=> NS_USER,
	'Metmaacherin'		=> NS_USER,
	'Medmaacherin'		=> NS_USER,
	'Metmaacheren'		=> NS_USER,
	'Medmaacheren'		=> NS_USER,
	'Medmaacher_Klaaf'	=> NS_USER_TALK,
	'Beld'			=> NS_FILE,
	'Belld'			=> NS_FILE,
	'Belder_Klaaf'		=> NS_FILE_TALK,
	'Bellder_Klaaf'		=> NS_FILE_TALK,
	'MedijaWikki'		=> NS_MEDIAWIKI,
	'MedijaWikki_Klaaf'	=> NS_MEDIAWIKI_TALK,
	'Hülp'			=> NS_HELP,
	'Hülp_Klaaf'		=> NS_HELP_TALK,
	'Sachjrop'		=> NS_CATEGORY,
	'Saachjrop'		=> NS_CATEGORY,
	'Saachjropp'		=> NS_CATEGORY,
	'Kattejori'		=> NS_CATEGORY,
	'Kategorie'		=> NS_CATEGORY,
	'Katejori'		=> NS_CATEGORY,
	'Sachjrop_Klaaf'	=> NS_CATEGORY_TALK,
	'Saachjroppe_Klaaf'	=> NS_CATEGORY_TALK,
	'Saachjrupp_Klaaf'	=> NS_CATEGORY_TALK,
	'Kattejori_Klaaf'	=> NS_CATEGORY_TALK,
	'Kattejorije_Klaaf'	=> NS_CATEGORY_TALK,
	'Kategorie_Klaaf'	=> NS_CATEGORY_TALK,
	'Katejorije_Klaaf'	=> NS_CATEGORY_TALK,
];

$separatorTransformTable = [ ',' => "\xc2\xa0", '.' => ',' ];
$linkTrail = '/^([äöüėëĳßəğåůæœça-z]+)(.*)$/sDu';

// Remove German aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktive', 'AktiveMetmaacher', 'Aktive_Metmaacher', 'AktiveMedmaacher', 'Aktive_Medmaacher' ],
	'Allmessages'               => [ 'MediaWiki-Appachtemang' ],
	'Allpages'                  => [ 'All_Sigge' ],
	'Ancientpages'              => [ 'Ahl_Atikelle' ],
	'Blankpage'                 => [ 'Leddijje_Sigge' ],
	'Block'                     => [ 'IP-Sperre' ],
	'Booksources'               => [ 'ISBN', 'Böcher', 'Böösher' ],
	'BrokenRedirects'           => [ 'Ömleitunge_en_et_Leere' ],
	'Categories'                => [ 'Saachjruppe' ],
	'ChangePassword'            => [ 'Neu_Passwood' ],
	'ComparePages'              => [ 'SiggeVerjliesche', 'Sigge_verjliesche', 'SiggeVerjlieche', 'Sigge_verjieche' ],
	'Confirmemail'              => [ 'Email_Bestätije', 'E-mail_Bestätije', 'EmailBestätije', 'E-mailBestätije' ],
	'Contributions'             => [ 'Beidräch', 'Beidrääsh' ],
	'CreateAccount'             => [ 'Aanmelde', 'Medmaacher_wääde', 'Metmaacher_wääde' ],
	'Deadendpages'              => [ 'Sigge_ohne_Links_dren' ],
	'DeletedContributions'      => [ 'Fotjeschmeße' ],
	'DoubleRedirects'           => [ 'Ömleitunge_op_Ömleitunge' ],
	'Emailuser'                 => [ 'E-mail' ],
	'Export'                    => [ 'Expocht' ],
	'Fewestrevisions'           => [ 'Winnig_beärbeit', 'Winnish_beärbeidt', 'Winnich_bearbeit' ],
	'FileDuplicateSearch'       => [ 'Dubbel_Dateie' ],
	'Filepath'                  => [ 'Dateipaad' ],
	'Import'                    => [ 'Emport', 'Empocht' ],
	'Invalidateemail'           => [ 'Onjöltije_e-mail_Addräß', 'Onjöltije_E-Mail_Adress' ],
	'BlockList'                 => [ 'Jesperrt', 'Jeshpächt' ],
	'LinkSearch'                => [ 'Websigge_Söke' ],
	'Listadmins'                => [ 'Köbese', 'Köbeße', 'Wiki-Köbesse' ],
	'Listbots'                  => [ 'Bots' ],
	'Listfiles'                 => [ 'Datei', 'Dateie' ],
	'Listgrouprights'           => [ 'Jrupperääschte', 'Jropperrääschte' ],
	'Listredirects'             => [ 'Ömleitunge' ],
	'Listusers'                 => [ 'Medmaacher', 'Metmaacher' ],
	'Lockdb'                    => [ 'Datebank-deeschmaache' ],
	'Log'                       => [ 'Logböcher', 'Logböösher' ],
	'Lonelypages'               => [ 'Sigge_ohne_Links_drop' ],
	'Longpages'                 => [ 'Lang_Atikelle' ],
	'MergeHistory'              => [ 'Versione_zosammeschmieße' ],
	'MIMEsearch'                => [ 'MIME-Typ', 'MIMEtüp' ],
	'Mostcategories'            => [ 'Sigge_met_de_mieste_Saachjroppe', 'Sigge_met_de_mieste_Saachjruppe' ],
	'Mostimages'                => [ 'Dateie_met_de_mieste_Links_drop' ],
	'Mostlinked'                => [ 'Sigge_met_de_mieste_Links_drop' ],
	'Mostlinkedcategories'      => [ 'Et_miehts_jebruchte_Saachjruppe', 'Et_miehts_jebruchte_Saachjroppe' ],
	'Mostlinkedtemplates'       => [ 'Et_miehts_jebruchte_Schablone' ],
	'Mostrevisions'             => [ 'Öff_beärbeit', 'Öff_beärbeidt', 'Off_bearbeit' ],
	'Movepage'                  => [ 'Ömnenne', 'Ömdäufe' ],
	'Mycontributions'           => [ 'Ming_Beidräch', 'Ming_Beidrääsh' ],
	'Mypage'                    => [ 'Ming_Medmaachersigg', 'Ming_Metmaachersigg', 'Medmaachersigg', 'Metmaachersigg' ],
	'Mytalk'                    => [ 'Ming_Klaafsigg', 'Klaaf' ],
	'Newimages'                 => [ 'Neu_Dateie' ],
	'Newpages'                  => [ 'Neu_Atikelle' ],
	'Preferences'               => [ 'Ming_Enstellunge', 'Enstellunge' ],
	'Prefixindex'               => [ 'Sigge_met_Aanfang' ],
	'Protectedpages'            => [ 'Siggeschotz' ],
	'Protectedtitles'           => [ 'Tittelschotz' ],
	'Randompage'                => [ 'Zofällije_Sigg' ],
	'Randomredirect'            => [ 'Zofällije_Ömleitung' ],
	'Recentchanges'             => [ 'Neuste_Änderunge', 'Änderunge' ],
	'Recentchangeslinked'       => [ 'Änderungen_an_verlinkte_Sigge' ],
	'Revisiondelete'            => [ 'Version_fottschmieße' ],
	'Search'                    => [ 'Sök', 'Söök', 'Söke', 'Sööke' ],
	'Shortpages'                => [ 'Koote_Atikelle' ],
	'Specialpages'              => [ 'Sondersigge', 'Söndersigge' ],
	'Statistics'                => [ 'Statistik', 'Shtatißtike' ],
	'Tags'                      => [ 'Makeerunge' ],
	'Unblock'                   => [ 'Freijävve', 'Frei_jävve', 'Freijevve', 'Frei_jevve' ],
	'Uncategorizedcategories'   => [ 'Saachjruppe_ohne_Saachjruppe' ],
	'Uncategorizedimages'       => [ 'Dateie_ohne_Saachjruppe' ],
	'Uncategorizedpages'        => [ 'Sigge_ohne_Saachjruppe' ],
	'Uncategorizedtemplates'    => [ 'Schablone_ohne_Saachjruppe' ],
	'Undelete'                  => [ 'Zeröckholle' ],
	'Unlockdb'                  => [ 'Datebank-opmaache' ],
	'Unusedcategories'          => [ 'Schablone_ohne_Links_drop' ],
	'Unusedimages'              => [ 'Dateie_ohne_Links_drop' ],
	'Unusedtemplates'           => [ 'Nit_jebruchte_Schablone' ],
	'Unwatchedpages'            => [ 'Sigge_oohne_Oppasser' ],
	'Upload'                    => [ 'Daate_huhlade', 'Huhlade' ],
	'Userlogin'                 => [ 'Enlogge' ],
	'Userlogout'                => [ 'Ußlogge' ],
	'Userrights'                => [ 'Medmaacherrääschte', 'Metmaacherrääschte' ],
	'Version'                   => [ 'Väsjohn' ],
	'Wantedcategories'          => [ 'Saachjruppe_fähle', 'Saachjroppe_fähle' ],
	'Wantedfiles'               => [ 'Dateie_fähle' ],
	'Wantedpages'               => [ 'Sigge_fähle' ],
	'Wantedtemplates'           => [ 'Schablone_fähle' ],
	'Watchlist'                 => [ 'Ming_Oppassliss', 'Oppassliss' ],
	'Whatlinkshere'             => [ 'Wat_noh_hee_link' ],
	'Withoutinterwiki'          => [ 'Ohne_Shproche_Lenks' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#ÖMLEIDE_OP', '#ÖMLEIDE', '#LEIDT_ÖM_OP', '#ÖMLEIDUNG', '#WEITERLEITUNG', '#REDIRECT' ],
	'nogallery'                 => [ '0', '__KEIN_JALLERIE__', '__KEINE_GALERIE__', '__KEINEGALERIE__', '__NOGALLERY__' ],
	'toc'                       => [ '0', '__ENHALLT__', '__INHALTSVERZEICHNIS__', '__TOC__' ],
	'img_right'                 => [ '1', 'rähß', 'räts', 'rechts', 'right' ],
	'img_left'                  => [ '1', 'links', 'lengks', 'lenks', 'left' ],
	'language'                  => [ '0', '#SHPROOCH:', '#SPROCH:', '#SPRACHE:', '#LANGUAGE:' ],
	'hiddencat'                 => [ '1', '__VERSHTOCHE_SAACHJRUPP__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ],
];

$imageFiles = [
	'button-italic'   => 'ksh/button_italic.png',
];
