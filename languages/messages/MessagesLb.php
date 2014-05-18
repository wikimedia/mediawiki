<?php
/** Luxembourgish (Lëtzebuergesch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Bild' => NS_FILE,
	'Bild_Diskussioun' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktiv_Benotzer' ),
	'Allmessages'               => array( 'All_Systemmessagen' ),
	'Allpages'                  => array( 'All_Säiten' ),
	'Ancientpages'              => array( 'Al_Säiten' ),
	'Badtitle'                  => array( 'Falschen_Titel' ),
	'Blankpage'                 => array( 'Eidel_Säit' ),
	'Block'                     => array( 'Spären' ),
	'Booksources'               => array( 'Bicher_mat_hirer_ISBN_sichen' ),
	'BrokenRedirects'           => array( 'Futtis_Viruleedungen' ),
	'Categories'                => array( 'Kategorien' ),
	'ChangeEmail'               => array( 'E-Mailadress_änneren' ),
	'ChangePassword'            => array( 'Passwuert_zrécksetzen' ),
	'ComparePages'              => array( 'Säite_vergkäichen' ),
	'Confirmemail'              => array( 'E-Mail_confirméieren' ),
	'Contributions'             => array( 'Kontributiounen' ),
	'CreateAccount'             => array( 'Benotzerkont_opmaachen' ),
	'Deadendpages'              => array( 'Sakgaasse-Säiten' ),
	'DeletedContributions'      => array( 'Geläschte_Kontributiounen' ),
	'DoubleRedirects'           => array( 'Duebel_Viruleedungen' ),
	'EditWatchlist'             => array( 'Iwwerwaachungslëscht_änneren' ),
	'Emailuser'                 => array( 'Dësem_Benotzer_eng_E-Mail_schécken' ),
	'ExpandTemplates'           => array( 'Schablounen_erweideren' ),
	'Export'                    => array( 'Exportéieren' ),
	'Fewestrevisions'           => array( 'Säite_mat_de_mannsten_Ännerungen' ),
	'FileDuplicateSearch'       => array( 'No_duebele_Fichieren_sichen' ),
	'Filepath'                  => array( 'Pad_bäi_de_Fichier' ),
	'Import'                    => array( 'Importéieren' ),
	'Invalidateemail'           => array( 'E-Mailadress_net_confirméieren' ),
	'JavaScriptTest'            => array( 'JavaScript-Test' ),
	'BlockList'                 => array( 'Lëscht_vu_gespaarten_IPen_a_Benotzer' ),
	'LinkSearch'                => array( 'Weblink-Sichen' ),
	'Listadmins'                => array( 'Lëscht_vun_den_Administrateuren' ),
	'Listbots'                  => array( 'Botten' ),
	'Listfiles'                 => array( 'Billerlëscht' ),
	'Listgrouprights'           => array( 'Lëscht_vun_de_Grupperechter' ),
	'Listredirects'             => array( 'Viruleedungen' ),
	'Listusers'                 => array( 'Lëscht_vun_de_Benotzer' ),
	'Lockdb'                    => array( 'Datebank_spären' ),
	'Log'                       => array( 'Logbicher' ),
	'Lonelypages'               => array( 'Weesesäiten' ),
	'Longpages'                 => array( 'Laang_Säiten' ),
	'MergeHistory'              => array( 'Versiounen_zesummeleeën' ),
	'MIMEsearch'                => array( 'No_MIME-Zorte_sichen' ),
	'Mostcategories'            => array( 'Säite_mat_de_meeschte_Kategorien' ),
	'Mostimages'                => array( 'Dacks_benotzte_Biller' ),
	'Mostinterwikis'            => array( 'Meescht_Interwikien' ),
	'Mostlinked'                => array( 'Dacks_verlinkte_Säiten' ),
	'Mostlinkedcategories'      => array( 'Dacks_benotzte_Kategorien' ),
	'Mostlinkedtemplates'       => array( 'Dacks_benotzte_Schablounen' ),
	'Mostrevisions'             => array( 'Säite_mat_de_meeschten_Ännerungen' ),
	'Movepage'                  => array( 'Säit_réckelen' ),
	'Mycontributions'           => array( 'Meng_Kontributiounen' ),
	'Mypage'                    => array( 'Meng_Benotzersäit' ),
	'Mytalk'                    => array( 'Meng_Diskussiounssäit' ),
	'Myuploads'                 => array( 'Meng_eropgeluede_Fichieren' ),
	'Newimages'                 => array( 'Nei_Biller' ),
	'Newpages'                  => array( 'Nei_Säiten' ),
	'PasswordReset'             => array( 'Zrécksetze_vum_Passwuert' ),
	'PermanentLink'             => array( 'Permanente_Link' ),
	'Popularpages'              => array( 'Beléifste_Säiten' ),
	'Preferences'               => array( 'Astellungen' ),
	'Prefixindex'               => array( 'Indexsich' ),
	'Protectedpages'            => array( 'Protegéiert_Säiten' ),
	'Protectedtitles'           => array( 'Gespaarte_Säiten' ),
	'Randompage'                => array( 'Zoufälleg_Säit' ),
	'RandomInCategory'          => array( 'Zoufälleg_Säit_aus_der_Kategorie' ),
	'Randomredirect'            => array( 'Zoufälleg_Viruleedung' ),
	'Recentchanges'             => array( 'Rezent_Ännerungen' ),
	'Recentchangeslinked'       => array( 'Ännerungen_op_verlinkte_Säiten' ),
	'Redirect'                  => array( 'Viruleedung' ),
	'Revisiondelete'            => array( 'Versioun_läschen' ),
	'Search'                    => array( 'Sichen' ),
	'Shortpages'                => array( 'Kuerz_Säiten' ),
	'Specialpages'              => array( 'Spezialsäiten' ),
	'Statistics'                => array( 'Statistik' ),
	'Tags'                      => array( 'Taggen' ),
	'Unblock'                   => array( 'Spär_ophiewen' ),
	'Uncategorizedcategories'   => array( 'Kategorien_ouni_Kategorie' ),
	'Uncategorizedimages'       => array( 'Biller_ouni_Kategorie' ),
	'Uncategorizedpages'        => array( 'Säiten_ouni_Kategorie' ),
	'Uncategorizedtemplates'    => array( 'Schablounen_ouni_Kategorie' ),
	'Undelete'                  => array( 'Restauréieren' ),
	'Unlockdb'                  => array( 'Spär_vun_der_Datebank_ophiewen' ),
	'Unusedcategories'          => array( 'Onbenotze_Kategorien' ),
	'Unusedimages'              => array( 'Onbenotzte_Biller' ),
	'Unusedtemplates'           => array( 'Onbenotzte_Schablounen' ),
	'Unwatchedpages'            => array( 'Säiten_déi_net_iwwerwaacht_ginn' ),
	'Upload'                    => array( 'Eroplueden' ),
	'Userlogin'                 => array( 'Umellen' ),
	'Userlogout'                => array( 'Ofmellen' ),
	'Userrights'                => array( 'Benotzerrechter' ),
	'Version'                   => array( 'Versioun' ),
	'Wantedcategories'          => array( 'Gewënschte_Kategorien' ),
	'Wantedfiles'               => array( 'Gewënschte_Fichieren' ),
	'Wantedpages'               => array( 'Gewënschte_Säiten' ),
	'Wantedtemplates'           => array( 'Gewënschte_Schablounen' ),
	'Watchlist'                 => array( 'Iwwerwaachungslëscht' ),
	'Whatlinkshere'             => array( 'Linken_op_dës_Säit' ),
	'Withoutinterwiki'          => array( 'Säiten_ouni_Interwiki-Linken' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#VIRULEEDUNG', '#WEITERLEITUNG', '#REDIRECT' ),
	'numberofpages'             => array( '1', 'Säitenzuel', 'SEITENANZAHL', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'Artikelen', 'ARTIKELANZAHL', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'Zuel_vu_Fichieren', 'DATEIANZAHL', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'Benotzerzuel', 'BENUTZERANZAHL', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'Aktiv_Benotzer', 'AKTIVE_BENUTZER', 'NUMBEROFACTIVEUSERS' ),
	'pagename'                  => array( '1', 'Säitennumm', 'SEITENNAME', 'PAGENAME' ),
	'namespace'                 => array( '1', 'Nummraum', 'NAMENSRAUM', 'NAMESPACE' ),
	'subjectspace'              => array( '1', 'Haaptnummraum', 'HAUPTNAMENSRAUM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectpagename'           => array( '1', 'Haaptsäit', 'HAUPTSEITE', 'HAUPTSEITENNAME', 'VORDERSEITE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'img_thumbnail'             => array( '1', 'Miniatur', 'miniatur', 'mini', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'riets', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'lénks', 'links', 'left' ),
	'img_none'                  => array( '1', 'ouni', 'ohne', 'none' ),
	'img_center'                => array( '1', 'zentréiert', 'zentriert', 'center', 'centre' ),
	'img_framed'                => array( '1', 'gerummt', 'gerahmt', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'net_gerummt', 'rahmenlos', 'frameless' ),
	'img_page'                  => array( '1', 'Säit=$1', 'Säit_$1', 'seite=$1', 'seite $1', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', 'bord', 'rand', 'border' ),
	'img_top'                   => array( '1', 'uewen', 'oben', 'top' ),
	'img_bottom'                => array( '1', 'ënnen', 'unten', 'bottom' ),
	'grammar'                   => array( '0', 'GRAMMAIRE', 'GRAMMATIK:', 'GRAMMAR:' ),
	'plural'                    => array( '0', 'PLURAL', 'PLURAL:' ),
	'currentversion'            => array( '1', 'AKTUELL_VERSIOUN', 'JETZIGE_VERSION', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#SPROOCH:', '#SPRACHE:', '#LANGUAGE:' ),
	'formatnum'                 => array( '0', 'ZUELEFORMAT', 'ZAHLENFORMAT', 'FORMATNUM' ),
	'special'                   => array( '0', 'spezial', 'special' ),
	'hiddencat'                 => array( '1', '__VERSTOPPT_KATEGORIE__', '__VERSTECKTE_KATEGORIE__', '__WARTUNGSKATEGORIE__', '__HIDDENCAT__' ),
	'pagesincategory_pages'     => array( '0', 'Säiten', 'seiten', 'pages' ),
	'pagesincategory_files'     => array( '0', 'Fichieren', 'dateien', 'files' ),
);

