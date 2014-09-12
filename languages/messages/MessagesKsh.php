<?php
/** Colognian (Ripoarisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([äöüėëĳßəğåůæœça-z]+)(.*)$/sDu';

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktive', 'AktiveMetmaacher', 'Aktive_Metmaacher', 'AktiveMedmaacher', 'Aktive_Medmaacher' ),
	'Allmessages'               => array( 'MediaWiki-Appachtemang' ),
	'Allpages'                  => array( 'All_Sigge' ),
	'Ancientpages'              => array( 'Ahl_Atikelle' ),
	'Blankpage'                 => array( 'Leddijje_Sigge' ),
	'Block'                     => array( 'IP-Sperre' ),
	'Booksources'               => array( 'ISBN', 'Böcher', 'Böösher' ),
	'BrokenRedirects'           => array( 'Ömleitunge_en_et_Leere' ),
	'Categories'                => array( 'Saachjruppe' ),
	'ChangePassword'            => array( 'Neu_Passwood' ),
	'ComparePages'              => array( 'SiggeVerjliesche', 'Sigge_verjliesche', 'SiggeVerjlieche', 'Sigge_verjieche' ),
	'Confirmemail'              => array( 'Email_Bestätije', 'E-mail_Bestätije', 'EmailBestätije', 'E-mailBestätije' ),
	'Contributions'             => array( 'Beidräch', 'Beidrääsh' ),
	'CreateAccount'             => array( 'Aanmelde', 'Medmaacher_wääde', 'Metmaacher_wääde' ),
	'Deadendpages'              => array( 'Sigge_ohne_Links_dren' ),
	'DeletedContributions'      => array( 'Fotjeschmeße' ),
	'DoubleRedirects'           => array( 'Ömleitunge_op_Ömleitunge' ),
	'Emailuser'                 => array( 'Email', 'E-mail' ),
	'Export'                    => array( 'Expocht' ),
	'Fewestrevisions'           => array( 'Winnig_beärbeit', 'Winnish_beärbeidt', 'Winnich_bearbeit' ),
	'FileDuplicateSearch'       => array( 'Dubbel_Dateie' ),
	'Filepath'                  => array( 'Dateipaad' ),
	'Import'                    => array( 'Emport', 'Empocht' ),
	'Invalidateemail'           => array( 'Onjöltije_e-mail_Addräß', 'Onjöltije_E-Mail_Adress' ),
	'BlockList'                 => array( 'Jesperrt', 'Jeshpächt' ),
	'LinkSearch'                => array( 'Websigge_Söke' ),
	'Listadmins'                => array( 'Köbese', 'Köbeße', 'Wiki-Köbesse' ),
	'Listbots'                  => array( 'Bots' ),
	'Listfiles'                 => array( 'Datei', 'Dateie' ),
	'Listgrouprights'           => array( 'Jrupperääschte', 'Jropperrääschte' ),
	'Listredirects'             => array( 'Ömleitunge' ),
	'Listusers'                 => array( 'Medmaacher', 'Metmaacher' ),
	'Lockdb'                    => array( 'Datebank-deeschmaache' ),
	'Log'                       => array( 'Logböcher', 'Logböösher' ),
	'Lonelypages'               => array( 'Sigge_ohne_Links_drop' ),
	'Longpages'                 => array( 'Lang_Atikelle' ),
	'MergeHistory'              => array( 'Versione_zosammeschmieße' ),
	'MIMEsearch'                => array( 'MIME-Typ', 'MIMEtüp' ),
	'Mostcategories'            => array( 'Sigge_met_de_mieste_Saachjroppe', 'Sigge_met_de_mieste_Saachjruppe' ),
	'Mostimages'                => array( 'Dateie_met_de_mieste_Links_drop' ),
	'Mostlinked'                => array( 'Sigge_met_de_mieste_Links_drop' ),
	'Mostlinkedcategories'      => array( 'Et_miehts_jebruchte_Saachjruppe', 'Et_miehts_jebruchte_Saachjroppe' ),
	'Mostlinkedtemplates'       => array( 'Et_miehts_jebruchte_Schablone' ),
	'Mostrevisions'             => array( 'Öff_beärbeit', 'Öff_beärbeidt', 'Off_bearbeit' ),
	'Movepage'                  => array( 'Ömnenne', 'Ömdäufe' ),
	'Mycontributions'           => array( 'Ming_Beidräch', 'Ming_Beidrääsh' ),
	'Mypage'                    => array( 'Ming_Medmaachersigg', 'Ming_Metmaachersigg', 'Medmaachersigg', 'Metmaachersigg' ),
	'Mytalk'                    => array( 'Ming_Klaafsigg', 'Klaaf' ),
	'Newimages'                 => array( 'Neu_Dateie' ),
	'Newpages'                  => array( 'Neu_Atikelle' ),
	'Popularpages'              => array( 'Miehts_affjeroofe_Sigge' ),
	'Preferences'               => array( 'Ming_Enstellunge', 'Enstellunge' ),
	'Prefixindex'               => array( 'Sigge_met_Aanfang' ),
	'Protectedpages'            => array( 'Siggeschotz' ),
	'Protectedtitles'           => array( 'Tittelschotz' ),
	'Randompage'                => array( 'Zofällije_Sigg' ),
	'Randomredirect'            => array( 'Zofällije_Ömleitung' ),
	'Recentchanges'             => array( 'Neuste_Änderunge', 'Änderunge' ),
	'Recentchangeslinked'       => array( 'Änderungen_an_verlinkte_Sigge' ),
	'Revisiondelete'            => array( 'Version_fottschmieße' ),
	'Search'                    => array( 'Sök', 'Söök', 'Söke', 'Sööke' ),
	'Shortpages'                => array( 'Koote_Atikelle' ),
	'Specialpages'              => array( 'Sondersigge', 'Söndersigge' ),
	'Statistics'                => array( 'Statistik', 'Shtatißtike' ),
	'Tags'                      => array( 'Makeerunge' ),
	'Unblock'                   => array( 'Freijävve', 'Frei_jävve', 'Freijevve', 'Frei_jevve' ),
	'Uncategorizedcategories'   => array( 'Saachjruppe_ohne_Saachjruppe' ),
	'Uncategorizedimages'       => array( 'Dateie_ohne_Saachjruppe' ),
	'Uncategorizedpages'        => array( 'Sigge_ohne_Saachjruppe' ),
	'Uncategorizedtemplates'    => array( 'Schablone_ohne_Saachjruppe' ),
	'Undelete'                  => array( 'Zeröckholle' ),
	'Unlockdb'                  => array( 'Datebank-opmaache' ),
	'Unusedcategories'          => array( 'Schablone_ohne_Links_drop' ),
	'Unusedimages'              => array( 'Dateie_ohne_Links_drop' ),
	'Unusedtemplates'           => array( 'Nit_jebruchte_Schablone' ),
	'Unwatchedpages'            => array( 'Sigge_oohne_Oppasser' ),
	'Upload'                    => array( 'Daate_huhlade', 'Huhlade' ),
	'Userlogin'                 => array( 'Enlogge' ),
	'Userlogout'                => array( 'Ußlogge' ),
	'Userrights'                => array( 'Medmaacherrääschte', 'Metmaacherrääschte' ),
	'Version'                   => array( 'Väsjohn' ),
	'Wantedcategories'          => array( 'Saachjruppe_fähle', 'Saachjroppe_fähle' ),
	'Wantedfiles'               => array( 'Dateie_fähle' ),
	'Wantedpages'               => array( 'Sigge_fähle' ),
	'Wantedtemplates'           => array( 'Schablone_fähle' ),
	'Watchlist'                 => array( 'Ming_Oppassliss', 'Oppassliss' ),
	'Whatlinkshere'             => array( 'Wat_noh_hee_link' ),
	'Withoutinterwiki'          => array( 'Ohne_Shproche_Lenks' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ÖMLEIDE_OP', '#ÖMLEIDE', '#LEIDT_ÖM_OP', '#ÖMLEIDUNG', '#WEITERLEITUNG', '#REDIRECT' ),
	'nogallery'                 => array( '0', '__KEIN_JALLERIE__', '__KEINE_GALERIE__', '__KEINEGALERIE__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__ENHALLT__', '__INHALTSVERZEICHNIS__', '__TOC__' ),
	'img_right'                 => array( '1', 'rähß', 'räts', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'lengks', 'lenks', 'links', 'left' ),
	'language'                  => array( '0', '#SHPROOCH:', '#SPROCH:', '#SPRACHE:', '#LANGUAGE:' ),
	'hiddencat'                 => array( '1', '__VERSHTOCHE_SAACHJRUPP__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
);

$imageFiles = array(
    'button-italic'   => 'ksh/button_S_italic.png',
);

