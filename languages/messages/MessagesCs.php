<?php
/** Czech (čeština)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback8bitEncoding = 'cp1250';

$namespaceNames = array(
	NS_MEDIA            => 'Média',
	NS_SPECIAL          => 'Speciální',
	NS_TALK             => 'Diskuse',
	NS_USER             => 'Uživatel',
	NS_USER_TALK        => 'Diskuse_s_uživatelem',
	NS_PROJECT_TALK     => 'Diskuse_k_{{grammar:3sg|$1}}',
	NS_FILE             => 'Soubor',
	NS_FILE_TALK        => 'Diskuse_k_souboru',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskuse_k_MediaWiki',
	NS_TEMPLATE         => 'Šablona',
	NS_TEMPLATE_TALK    => 'Diskuse_k_šabloně',
	NS_HELP             => 'Nápověda',
	NS_HELP_TALK        => 'Diskuse_k_nápovědě',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Diskuse_ke_kategorii',
);

$namespaceAliases = array(
	'Uživatel_diskuse'      => NS_USER_TALK,      # old literal translation backward compatibility
	'Uživatelka_diskuse'    => NS_USER_TALK,      # female complement to old literal translation style
	'$1_diskuse'            => NS_PROJECT_TALK,   # old literal translation backward compatibility
	'Soubor_diskuse'        => NS_FILE_TALK,      # old literal translation backward compatibility
	'MediaWiki_diskuse'     => NS_MEDIAWIKI_TALK, # old literal translation backward compatibility
	'Šablona_diskuse'       => NS_TEMPLATE_TALK,  # old literal translation backward compatibility
	'Nápověda_diskuse'      => NS_HELP_TALK,      # old literal translation backward compatibility
	'Kategorie_diskuse'     => NS_CATEGORY_TALK,  # old literal translation backward compatibility
);

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Uživatel', 'female' => 'Uživatelka' ),
	NS_USER_TALK => array( 'male' => 'Diskuse_s_uživatelem', 'female' => 'Diskuse_s_uživatelkou' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktivní_uživatelé', 'Aktivni_uzivatele' ),
	'Allmessages'               => array( 'Všechna_hlášení', 'Všechny_zprávy', 'Vsechna_hlaseni', 'Vsechny_zpravy' ),
	'Allpages'                  => array( 'Všechny_stránky', 'Vsechny_stranky' ),
	'Ancientpages'              => array( 'Nejstarší_stránky', 'Staré_stránky', 'Stare_stranky' ),
	'Blankpage'                 => array( 'Prázdná_stránka' ),
	'Block'                     => array( 'Blokování', 'Blokovani', 'Blokovat_uživatele', 'Blokovat_IP', 'Blokovat_uzivatele' ),
	'Booksources'               => array( 'Zdroje_knih' ),
	'BrokenRedirects'           => array( 'Přerušená_přesměrování', 'Prerusena_presmerovani' ),
	'Categories'                => array( 'Kategorie' ),
	'ChangeEmail'               => array( 'Změna_emailu', 'Zmena_emailu' ),
	'ChangePassword'            => array( 'Změna_hesla', 'Zmena_hesla', 'Resetovat_heslo' ),
	'ComparePages'              => array( 'Porovnání_stránek', 'PorovnáníStránek', 'Porovnani_stranek', 'PorovnaniStranek' ),
	'Confirmemail'              => array( 'Potvrdit_e-mail' ),
	'Contributions'             => array( 'Příspěvky', 'Prispevky' ),
	'CreateAccount'             => array( 'Vytvořit_účet', 'Vytvorit_ucet' ),
	'Deadendpages'              => array( 'Slepé_stránky', 'Slepe_stranky' ),
	'DeletedContributions'      => array( 'Smazané_příspěvky', 'Smazane_prispevky' ),
	'DoubleRedirects'           => array( 'Dvojitá_přesměrování', 'Dvojita_presmerovani' ),
	'Emailuser'                 => array( 'E-mail' ),
	'ExpandTemplates'           => array( 'Testy_šablon' ),
	'Export'                    => array( 'Exportovat_stránky' ),
	'Fewestrevisions'           => array( 'Stránky_s_nejméně_editacemi', 'Stranky_s_nejmene_editacemi', 'Stránky_s_nejmenším_počtem_editací' ),
	'FileDuplicateSearch'       => array( 'Hledání_duplicitních_souborů', 'Hledani_duplicitnich_souboru' ),
	'Filepath'                  => array( 'Cesta_k_souboru' ),
	'Import'                    => array( 'Importovat_stránky' ),
	'Invalidateemail'           => array( 'Zneplatnit_e-mail', 'Zrušit_potvrzení_e-mailu' ),
	'BlockList'                 => array( 'Blokovaní_uživatelé', 'Blokovani_uzivatele' ),
	'LinkSearch'                => array( 'Hledání_odkazů', 'Hledani_odkazu' ),
	'Listadmins'                => array( 'Seznam_správců', 'Seznam_spravcu' ),
	'Listbots'                  => array( 'Seznam_botů', 'Seznam_botu' ),
	'Listfiles'                 => array( 'Seznam_souborů', 'Seznam_souboru' ),
	'Listgrouprights'           => array( 'Seznam_uživatelských_práv', 'Seznam_uzivatelskych_prav' ),
	'Listredirects'             => array( 'Seznam_přesměrování', 'Seznam_presmerovani' ),
	'Listusers'                 => array( 'Uživatelé', 'Uzivatele', 'Seznam_uživatelů', 'Seznam_uzivatelu' ),
	'Lockdb'                    => array( 'Zamknout_databázi', 'Zamknout_databazi' ),
	'Log'                       => array( 'Protokolovací_záznamy', 'Protokoly', 'Protokol', 'Protokolovaci_zaznamy' ),
	'Lonelypages'               => array( 'Sirotčí_stránky', 'Sirotci_stranky' ),
	'Longpages'                 => array( 'Nejdelší_stránky', 'Nejdelsi_stranky' ),
	'MergeHistory'              => array( 'Sloučení_historie', 'Slouceni_historie', 'Sloučit_historii' ),
	'MIMEsearch'                => array( 'Hledání_podle_MIME', 'Hledani_podle_MIME', 'Hledat_podle_MIME_typu' ),
	'Mostcategories'            => array( 'Stránky_s_nejvíce_kategoriemi', 'Stranky_s_nejvice_kategoriemi', 'Stránky_s_nejvyšším_počtem_kategorií' ),
	'Mostimages'                => array( 'Nejpoužívanější_soubory', 'Nejpouzivanejsi_soubory' ),
	'Mostlinked'                => array( 'Nejodkazovanější_stránky', 'Nejodkazovanejsi_stranky' ),
	'Mostlinkedcategories'      => array( 'Nejpoužívanější_kategorie', 'Nejpouzivanejsi_kategorie' ),
	'Mostlinkedtemplates'       => array( 'Nejpoužívanější_šablony', 'Nejpouzivanejsi_sablony' ),
	'Mostrevisions'             => array( 'Stránky_s_nejvíce_editacemi', 'Stranky_s_nejvice_editacemi', 'Stránky_s_nejvyšším_počtem_editací' ),
	'Movepage'                  => array( 'Přesunout_stránku' ),
	'Mycontributions'           => array( 'Mé_příspěvky', 'Me_prispevky' ),
	'Mypage'                    => array( 'Moje_stránka', 'Moje_stranka' ),
	'Mytalk'                    => array( 'Moje_diskuse' ),
	'Newimages'                 => array( 'Nové_obrázky', 'Galerie_nových_obrázků', 'Nove_obrazky' ),
	'Newpages'                  => array( 'Nové_stránky', 'Nove_stranky', 'Nejnovější_stránky', 'Nejnovejsi_stranky' ),

	'Preferences'               => array( 'Nastavení', 'Nastaveni' ),
	'Protectedpages'            => array( 'Zamčené_stránky', 'Zamcene_stranky' ),
	'Protectedtitles'           => array( 'Zamčené_názvy', 'Zamcene_nazvy', 'Stránky_které_nelze_vytvořit' ),
	'Randompage'                => array( 'Náhodná_stránka', 'Nahodna_stranka' ),
	'Randomredirect'            => array( 'Náhodné_přesměrování', 'Nahodne_presmerovani' ),
	'Recentchanges'             => array( 'Poslední_změny', 'Posledni_zmeny' ),
	'Recentchangeslinked'       => array( 'Související_změny', 'Souvisejici_zmeny' ),
	'Revisiondelete'            => array( 'Smazat_revizi' ),
	'Search'                    => array( 'Hledání', 'Hledani' ),
	'Shortpages'                => array( 'Nejkratší_stránky', 'Nejkratsi_stranky' ),
	'Specialpages'              => array( 'Speciální_stránky', 'Specialni_stranky' ),
	'Statistics'                => array( 'Statistika', 'Statistiky' ),
	'Tags'                      => array( 'Značky', 'Znacky' ),
	'Unblock'                   => array( 'Odblokování', 'Odblokovani' ),
	'Uncategorizedcategories'   => array( 'Nekategorizované_kategorie', 'Nekategorizovane_kategorie' ),
	'Uncategorizedimages'       => array( 'Nekategorizované_soubory', 'Nekategorizovane_soubory' ),
	'Uncategorizedpages'        => array( 'Nekategorizované_stránky', 'Nekategorizovane_stranky' ),
	'Uncategorizedtemplates'    => array( 'Nekategorizované_šablony', 'Nekategorizovane_sablony' ),
	'Undelete'                  => array( 'Smazané_stránky', 'Smazane_stranky' ),
	'Unlockdb'                  => array( 'Odemknout_databázi', 'Odemknout_databazi' ),
	'Unusedcategories'          => array( 'Nepoužívané_kategorie', 'Nepouzivane_kategorie' ),
	'Unusedimages'              => array( 'Nepoužívané_soubory', 'Nepouzivane_soubory' ),
	'Unusedtemplates'           => array( 'Nepoužívané_šablony', 'Nepouzivane_sablony' ),
	'Unwatchedpages'            => array( 'Nesledované_stránky' ),
	'Upload'                    => array( 'Načíst_soubor', 'Nacist_soubor', 'Načíst_obrázek' ),
	'Userlogin'                 => array( 'Přihlásit', 'Prihlasit' ),
	'Userlogout'                => array( 'Odhlásit', 'Odhlasit' ),
	'Userrights'                => array( 'Uživatelská_práva', 'Správa_uživatelů', 'Uzivatelska_prava' ),
	'Version'                   => array( 'Verze' ),
	'Wantedcategories'          => array( 'Chybějící_kategorie', 'Požadované_kategorie', 'Pozadovane_kategorie' ),
	'Wantedfiles'               => array( 'Chybějící_soubory', 'Požadované_soubory', 'Pozadovane_soubory' ),
	'Wantedpages'               => array( 'Chybějící_stránky', 'Požadované_stránky', 'Pozadovane_stranky' ),
	'Wantedtemplates'           => array( 'Chybějící_šablony', 'Požadované_šablony', 'Pozadovane_sablony' ),
	'Watchlist'                 => array( 'Sledované_stránky', 'Sledovane_stranky' ),
	'Whatlinkshere'             => array( 'Co_odkazuje_na', 'Odkazuje_sem' ),
	'Withoutinterwiki'          => array( 'Bez_interwiki', 'Stránky_bez_interwiki_odkazů' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#PŘESMĚRUJ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__BEZOBSAHU__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__BEZGALERIE__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__VŽDYOBSAH__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__OBSAH__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BEZEDITOVATČÁST__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'AKTUÁLNÍMĚSÍC', 'AKTUÁLNÍMĚSÍC2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'AKTUÁLNÍMĚSÍC1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'AKTUÁLNÍMĚSÍCJMÉNO', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'AKTUÁLNÍMĚSÍCGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'AKTUÁLNÍMĚSÍCZKR', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'AKTUÁLNÍDEN', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'AKTUÁLNÍDEN2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'AKTUÁLNÍDENJMÉNO', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'AKTUÁLNÍROK', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AKTUÁLNÍČAS', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'AKTUÁLNÍHODINA', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MÍSTNÍMĚSÍC', 'MÍSTNÍMĚSÍC2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'MÍSTNÍMĚSÍC1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'MÍSTNÍMĚSÍCJMÉNO', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'MÍSTNÍMĚSÍCGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'MÍSTNÍMĚSÍCZKR', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'MÍSTNÍDEN', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'MÍSTNÍDEN2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'MÍSTNÍDENJMÉNO', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'MÍSTNÍROK', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'MÍSTNÍČAS', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'MÍSTNÍHODINA', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'POČETSTRAN', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'POČETČLÁNKŮ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'POČETSOUBORŮ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'POČETUŽIVATELŮ', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'POČETAKTIVNÍCHUŽIVATELŮ', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'POČETEDITACÍ', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'NÁZEVSTRANY', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NÁZEVSTRANYE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'JMENNÝPROSTOR', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'JMENNÝPROSTORE', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'DISKUSNÍPROSTOR', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'DISKUSNÍPROSTORE', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ČLÁNEKPROSTOR', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ČLÁNEKPROSTORE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'PLNÝNÁZEVSTRANY', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'PLNÝNÁZEVSTRANYE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'NÁZEVPODSTRANY', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'NÁZEVPODSTRANYE', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NÁZEVNADSTRANY', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NÁZEVNADSTRANYE', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NÁZEVDISKUSE', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'NÁZEVDISKUSEE', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'NÁZEVČLÁNKU', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'NÁZEVČLÁNKUE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'subst'                     => array( '0', 'VLOŽIT:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'VLOŽITNW:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'náhled', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'náhled=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'vpravo', 'right' ),
	'img_left'                  => array( '1', 'vlevo', 'left' ),
	'img_none'                  => array( '1', 'žádné', 'none' ),
	'img_width'                 => array( '1', '$1pixelů', '$1px' ),
	'img_center'                => array( '1', 'střed', 'center', 'centre' ),
	'img_framed'                => array( '1', 'rám', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'bezrámu', 'frameless' ),
	'img_lang'                  => array( '1', 'jazyk=$1', 'lang=$1' ),
	'img_page'                  => array( '1', 'strana=$1', 'strana_$1', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', 'okraj', 'border' ),
	'sitename'                  => array( '1', 'NÁZEVWEBU', 'SITENAME' ),
	'ns'                        => array( '0', 'JMENNÝPROSTOR:', 'NS:' ),
	'localurl'                  => array( '0', 'MÍSTNÍURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'MÍSTNÍURLE:', 'LOCALURLE:' ),
	'servername'                => array( '0', 'NÁZEVSERVERU', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'SKLOŇUJ:', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__BEZKONVERZENADPISU__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__BEZKONVERZEOBSAHU__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'AKTUÁLNÍTÝDEN', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'AKTUÁLNÍDENTÝDNE', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'MÍSTNÍTÝDEN', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'MÍSTNÍDENTÝDNE', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'IDREVIZE', 'REVISIONID' ),
	'revisionday'               => array( '1', 'DENREVIZE', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'DENREVIZE2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'MĚSÍCREVIZE', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'ROKREVIZE', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'KÓDČASUREVIZE', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'PLURÁL:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'PLNÉURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'PLNÉURLE:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'PRVNÍMALÉ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'PRVNÍVELKÉ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'MALÁ:', 'LC:' ),
	'uc'                        => array( '0', 'VELKÁ:', 'UC:' ),
	'displaytitle'              => array( '1', 'ZOBRAZOVANÝNADPIS', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__LINKPŘIDATKOMENTÁŘ__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'VERZESOFTWARE', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'ENKÓDOVATURL:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ENKÓDOVATNADPIS', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'AKTUÁLNÍKÓDČASU', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'MÍSTNÍKÓDČASU', 'LOCALTIMESTAMP' ),
	'language'                  => array( '0', '#JAZYK:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'JAZYKOBSAHU', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'STRÁNEKVEJMENNÉMPROSTORU:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'POČETSPRÁVCŮ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMÁTUJČÍSLO', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ZAROVNATVLEVO', 'PADLEFT' ),
	'padright'                  => array( '0', 'ZAROVNATVPRAVO', 'PADRIGHT' ),
	'special'                   => array( '0', 'speciální', 'special' ),
	'defaultsort'               => array( '1', 'KLÍČŘAZENÍ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'CESTAKSOUBORU', 'FILEPATH:' ),
	'tag'                       => array( '0', 'značka', 'tag' ),
	'hiddencat'                 => array( '1', '__SKRÝTKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'STRÁNEKVKATEGORII', 'STRÁNEKVKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'VELIKOSTSTRÁNKY', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEXOVAT__', '__INDEX__' ),
	'noindex'                   => array( '1', '__NEINDEXOVAT__', '__NOINDEX__' ),
	'staticredirect'            => array( '1', '__STATICKÉPŘESMĚROVÁNÍ__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'ÚROVEŇZAMČENÍ', 'PROTECTIONLEVEL' ),
	'pagesincategory_files'     => array( '0', 'soubory', 'files' ),
);

/**
 * Date formats list for Special:Preferences
 * see $dateFormats for definitions
 */
$datePreferences = array(
	'ČSN basic dt',
	'ČSN padded dt',
	'ČSN basic td',
	'ČSN padded td',
	'PČP dt',
	'PČP td',
	'ISO dt',
);

/**
 * Default date format to be used
 */
$defaultDateFormat = 'ČSN basic dt';

/**
 * Date formats definitions
 *
 * ČSN - Česká státní norma 01 6910 / Czech state norm 01 6910; numeral representation, basic = 1-12(31), padded = 01-12(31)
 * PČP - Pravidla českého pravopisu / The rules of Czech ortography (ISBN 80-200-0475-0); verbal representation
 * ISO - ISO 8601:2004 - Data elements and interchange formats -- Information interchange -- Representation of dates and times
 * dt - date, time order
 * td - time, date order
 */
$dateFormats = array(
	'ČSN basic dt time' => 'H:i',
	'ČSN basic dt date' => 'j. n. Y',
	'ČSN basic dt both' => 'j. n. Y, H:i',

	'ČSN padded dt time' => 'H:i',
	'ČSN padded dt date' => 'd.m.Y',
	'ČSN padded dt both' => 'd.m.Y, H:i',

	'ČSN basic td time' => 'H:i',
	'ČSN basic td date' => 'j. n. Y',
	'ČSN basic td both' => 'H:i, j. n. Y',

	'ČSN padded td time' => 'H:i',
	'ČSN padded td date' => 'd.m.Y',
	'ČSN padded td both' => 'H:i, d.m.Y',

	'PČP dt time' => 'H.i',
	'PČP dt date' => 'j. xg Y',
	'PČP dt both' => 'j. xg Y, H.i',

	'PČP td time' => 'H.i',
	'PČP td date' => 'j. xg Y',
	'PČP td both' => 'H.i, j. xg Y',

	'ISO dt time' => 'xnH:xni:xns',
	'ISO dt date' => 'xnY-xnm-xnd',
	'ISO dt both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

/**
 * Default list of book sources
 * Hledání knihy podle ISBN
 */
$bookstoreList = array(
	'Národní knihovna'          => 'http://aleph.nkp.cz/F/?func=find-a&find_code=ISN&request=$1',
	'Státní technická knihovna' => 'http://www.stk.cz/cgi-bin/dflex/CZE/STK/BROWSE?A=01&V=$1',
	'inherit' => true,
);

/**
 * Regular expression matching the "link trail", e.g. "ed" in [[Toast]]ed, as
 * the first group, and the remainder of the string as the second group.
 */
# Písmena, která se mají objevit jako část odkazu ve formě '[[jazyk]]y' atd:
$linkTrail = '/^([a-záčďéěíňóřšťúůýž]+)(.*)$/sDu';

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

