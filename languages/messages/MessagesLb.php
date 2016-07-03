<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Geitost
 * @author Hercule
 * @author Kaffi
 * @author Kaganer
 * @author Les Meloures
 * @author MF-Warburg
 * @author Purodha
 * @author Reedy
 * @author Robby
 * @author Soued031
 * @author Urhixidur
 * @author VT98Fan
 * @author Zinneke
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Diskussioun',
	NS_USER             => 'Benotzer',
	NS_USER_TALK        => 'Benotzer_Diskussioun',
	NS_PROJECT_TALK     => '$1_Diskussioun',
	NS_FILE             => 'Fichier',
	NS_FILE_TALK        => 'Fichier_Diskussioun',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Diskussioun',
	NS_TEMPLATE         => 'Schabloun',
	NS_TEMPLATE_TALK    => 'Schabloun_Diskussioun',
	NS_HELP             => 'Hëllef',
	NS_HELP_TALK        => 'Hëllef_Diskussioun',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategorie_Diskussioun',
];

$namespaceAliases = [
	'Bild' => NS_FILE,
	'Bild_Diskussioun' => NS_FILE_TALK,
];

// Remove German aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktiv_Benotzer' ],
	'Allmessages'               => [ 'All_Systemmessagen' ],
	'AllMyUploads'              => [ 'All_meng_Fichieren' ],
	'Allpages'                  => [ 'All_Säiten' ],
	'Ancientpages'              => [ 'Al_Säiten' ],
	'Badtitle'                  => [ 'Falschen_Titel' ],
	'Blankpage'                 => [ 'Eidel_Säit' ],
	'Block'                     => [ 'Spären' ],
	'Booksources'               => [ 'Bicher_mat_hirer_ISBN_sichen' ],
	'BrokenRedirects'           => [ 'Futtis_Viruleedungen' ],
	'Categories'                => [ 'Kategorien' ],
	'ChangeEmail'               => [ 'E-Mailadress_änneren' ],
	'ChangePassword'            => [ 'Passwuert_zrécksetzen' ],
	'ComparePages'              => [ 'Säite_vergläichen' ],
	'Confirmemail'              => [ 'E-Mail_confirméieren' ],
	'Contributions'             => [ 'Kontributiounen' ],
	'CreateAccount'             => [ 'Benotzerkont_opmaachen' ],
	'Deadendpages'              => [ 'Sakgaasse-Säiten' ],
	'DeletedContributions'      => [ 'Geläscht_Kontributiounen' ],
	'DoubleRedirects'           => [ 'Duebel_Viruleedungen' ],
	'EditWatchlist'             => [ 'Iwwerwaachungslëscht_änneren' ],
	'Emailuser'                 => [ 'Dësem_Benotzer_eng_E-Mail_schécken' ],
	'ExpandTemplates'           => [ 'Schablounen_erweideren' ],
	'Export'                    => [ 'Exportéieren' ],
	'Fewestrevisions'           => [ 'Säite_mat_de_mannsten_Ännerungen' ],
	'FileDuplicateSearch'       => [ 'No_duebele_Fichiere_sichen' ],
	'Filepath'                  => [ 'Pad_bäi_de_Fichier' ],
	'Import'                    => [ 'Importéieren' ],
	'Invalidateemail'           => [ 'E-Mailadress_net_confirméieren' ],
	'JavaScriptTest'            => [ 'JavaScript-Test' ],
	'BlockList'                 => [ 'Lëscht_vu_gespaarten_IPen_a_Benotzer' ],
	'LinkSearch'                => [ 'Weblink-Sichen' ],
	'Listadmins'                => [ 'Lëscht_vun_den_Administrateuren' ],
	'Listbots'                  => [ 'Botten' ],
	'Listfiles'                 => [ 'Billerlëscht' ],
	'Listgrouprights'           => [ 'Lëscht_vun_de_Grupperechter' ],
	'Listredirects'             => [ 'Viruleedungen' ],
	'ListDuplicatedFiles'       => [ 'Lëscht_vun_den_duebele_Fichieren' ],
	'Listusers'                 => [ 'Lëscht_vun_de_Benotzer' ],
	'Lockdb'                    => [ 'Datebank_spären' ],
	'Log'                       => [ 'Logbicher' ],
	'Lonelypages'               => [ 'Weesesäiten' ],
	'Longpages'                 => [ 'Laang_Säiten' ],
	'MergeHistory'              => [ 'Versiounen_zesummeleeën' ],
	'MIMEsearch'                => [ 'No_MIME-Zorte_sichen' ],
	'Mostcategories'            => [ 'Säite_mat_de_meeschte_Kategorien' ],
	'Mostimages'                => [ 'Dacks_benotzt_Biller' ],
	'Mostinterwikis'            => [ 'Meescht_Interwikien' ],
	'Mostlinked'                => [ 'Dacks_verlinkt_Säiten' ],
	'Mostlinkedcategories'      => [ 'Dacks_benotzt_Kategorien' ],
	'Mostlinkedtemplates'       => [ 'Dacks_benotzt_Schablounen' ],
	'Mostrevisions'             => [ 'Säite_mat_de_meeschten_Ännerungen' ],
	'Movepage'                  => [ 'Säit_réckelen' ],
	'Mycontributions'           => [ 'Meng_Kontributiounen' ],
	'MyLanguage'                => [ 'Meng_Sprooch' ],
	'Mypage'                    => [ 'Meng_Benotzersäit' ],
	'Mytalk'                    => [ 'Meng_Diskussiounssäit' ],
	'Myuploads'                 => [ 'Meng_eropgeluede_Fichieren' ],
	'Newimages'                 => [ 'Nei_Biller' ],
	'Newpages'                  => [ 'Nei_Säiten' ],
	'PagesWithProp'             => [ 'Säite_mat_Eegeschaften' ],
	'PageLanguage'              => [ 'Sprooch_vun_der_Säit' ],
	'PasswordReset'             => [ 'Zrécksetze_vum_Passwuert' ],
	'PermanentLink'             => [ 'Permanente_Link' ],
	'Preferences'               => [ 'Astellungen' ],
	'Prefixindex'               => [ 'Indexsich' ],
	'Protectedpages'            => [ 'Protegéiert_Säiten' ],
	'Protectedtitles'           => [ 'Gespaart_Säiten' ],
	'Randompage'                => [ 'Zoufälleg_Säit' ],
	'RandomInCategory'          => [ 'Zoufälleg_Säit_an_der_Kategorie' ],
	'Randomredirect'            => [ 'Zoufälleg_Viruleedung' ],
	'Recentchanges'             => [ 'Rezent_Ännerungen' ],
	'Recentchangeslinked'       => [ 'Ännerungen_op_verlinkte_Säiten' ],
	'Redirect'                  => [ 'Viruleedung' ],
	'ResetTokens'               => [ 'Token_zrécksetzen' ],
	'Revisiondelete'            => [ 'Versioun_läschen' ],
	'Search'                    => [ 'Sichen' ],
	'Shortpages'                => [ 'Kuerz_Säiten' ],
	'Specialpages'              => [ 'Spezialsäiten' ],
	'Statistics'                => [ 'Statistik' ],
	'Tags'                      => [ 'Taggen' ],
	'Unblock'                   => [ 'Spär_ophiewen' ],
	'Uncategorizedcategories'   => [ 'Kategorien_ouni_Kategorie' ],
	'Uncategorizedimages'       => [ 'Biller_ouni_Kategorie' ],
	'Uncategorizedpages'        => [ 'Säiten_ouni_Kategorie' ],
	'Uncategorizedtemplates'    => [ 'Schablounen_ouni_Kategorie' ],
	'Undelete'                  => [ 'Restauréieren' ],
	'Unlockdb'                  => [ 'Spär_vun_der_Datebank_ophiewen' ],
	'Unusedcategories'          => [ 'Onbenotz_Kategorien' ],
	'Unusedimages'              => [ 'Onbenotzt_Biller' ],
	'Unusedtemplates'           => [ 'Onbenotzt_Schablounen' ],
	'Unwatchedpages'            => [ 'Säiten_déi_net_iwwerwaacht_ginn' ],
	'Upload'                    => [ 'Eroplueden' ],
	'Userlogin'                 => [ 'Umellen' ],
	'Userlogout'                => [ 'Ofmellen' ],
	'Userrights'                => [ 'Benotzerrechter' ],
	'Version'                   => [ 'Versioun' ],
	'Wantedcategories'          => [ 'Gewënscht_Kategorien' ],
	'Wantedfiles'               => [ 'Gewënscht_Fichieren' ],
	'Wantedpages'               => [ 'Gewënscht_Säiten' ],
	'Wantedtemplates'           => [ 'Gewënscht_Schablounen' ],
	'Watchlist'                 => [ 'Iwwerwaachungslëscht' ],
	'Whatlinkshere'             => [ 'Linken_op_dës_Säit' ],
	'Withoutinterwiki'          => [ 'Säiten_ouni_Interwiki-Linken' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#VIRULEEDUNG', '#WEITERLEITUNG', '#REDIRECT' ],
	'numberofpages'             => [ '1', 'Säitenzuel', 'SEITENANZAHL', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'Artikelen', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'Zuel_vu_Fichieren', 'DATEIANZAHL', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'Benotzerzuel', 'BENUTZERANZAHL', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'Aktiv_Benotzer', 'AKTIVE_BENUTZER', 'NUMBEROFACTIVEUSERS' ],
	'pagename'                  => [ '1', 'Säitennumm', 'SEITENNAME', 'PAGENAME' ],
	'namespace'                 => [ '1', 'Nummraum', 'NAMENSRAUM', 'NAMESPACE' ],
	'subjectspace'              => [ '1', 'Haaptnummraum', 'HAUPTNAMENSRAUM', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectpagename'           => [ '1', 'Haaptsäit', 'HAUPTSEITE', 'HAUPTSEITENNAME', 'VORDERSEITE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'img_thumbnail'             => [ '1', 'miniatur', 'Miniatur', 'mini', 'thumb', 'thumbnail' ],
	'img_right'                 => [ '1', 'riets', 'rechts', 'right' ],
	'img_left'                  => [ '1', 'lénks', 'links', 'left' ],
	'img_none'                  => [ '1', 'ohne', 'ouni', 'none' ],
	'img_center'                => [ '1', 'zentréiert', 'zentriert', 'center', 'centre' ],
	'img_framed'                => [ '1', 'gerummt', 'gerahmt', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'rahmenlos', 'net_gerummt', 'frameless' ],
	'img_page'                  => [ '1', 'Säit=$1', 'Säit_$1', 'seite=$1', 'seite $1', 'page=$1', 'page $1' ],
	'img_border'                => [ '1', 'rand', 'bord', 'border' ],
	'img_top'                   => [ '1', 'uewen', 'oben', 'top' ],
	'img_middle'                => [ '1', 'mëtt', 'mitte', 'middle' ],
	'img_bottom'                => [ '1', 'ënnen', 'unten', 'bottom' ],
	'grammar'                   => [ '0', 'GRAMMAIRE', 'GRAMMATIK:', 'GRAMMAR:' ],
	'plural'                    => [ '0', 'PLURAL', 'PLURAL:' ],
	'currentversion'            => [ '1', 'AKTUELL_VERSIOUN', 'JETZIGE_VERSION', 'CURRENTVERSION' ],
	'language'                  => [ '0', '#SPROOCH:', '#SPRACHE:', '#LANGUAGE:' ],
	'formatnum'                 => [ '0', 'ZUELEFORMAT', 'ZAHLENFORMAT', 'FORMATNUM' ],
	'special'                   => [ '0', 'spezial', 'special' ],
	'hiddencat'                 => [ '1', '__VERSTOPPT_KATEGORIE__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ],
	'pagesincategory_pages'     => [ '0', 'Säiten', 'seiten', 'pages' ],
	'pagesincategory_files'     => [ '0', 'Fichieren', 'dateien', 'files' ],
];

