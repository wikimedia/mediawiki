<?php
/** Czech (čeština)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'sk';
$fallback8bitEncoding = 'cp1250';

$namespaceNames = [
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
];

$namespaceAliases = [
	'Uživatel_diskuse'      => NS_USER_TALK,      # old literal translation backward compatibility
	'Uživatelka_diskuse'    => NS_USER_TALK,      # female complement to old literal translation style
	'$1_diskuse'            => NS_PROJECT_TALK,   # old literal translation backward compatibility
	'Soubor_diskuse'        => NS_FILE_TALK,      # old literal translation backward compatibility
	'MediaWiki_diskuse'     => NS_MEDIAWIKI_TALK, # old literal translation backward compatibility
	'Šablona_diskuse'       => NS_TEMPLATE_TALK,  # old literal translation backward compatibility
	'Nápověda_diskuse'      => NS_HELP_TALK,      # old literal translation backward compatibility
	'Kategorie_diskuse'     => NS_CATEGORY_TALK,  # old literal translation backward compatibility
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Uživatel', 'female' => 'Uživatelka' ],
	NS_USER_TALK => [ 'male' => 'Diskuse_s_uživatelem', 'female' => 'Diskuse_s_uživatelkou' ],
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktivní_uživatelé', 'Aktivni_uzivatele' ],
	'Allmessages'               => [ 'Všechna_hlášení', 'Všechny_zprávy', 'Vsechna_hlaseni', 'Vsechny_zpravy' ],
	'AllMyUploads'              => [ 'Všechny_moje_soubory', 'Všechny_mé_soubory' ],
	'Allpages'                  => [ 'Všechny_stránky', 'Vsechny_stranky' ],
	'Ancientpages'              => [ 'Nejstarší_stránky', 'Staré_stránky', 'Stare_stranky' ],
	'ApiHelp'                   => [ 'Nápověda_k_API' ],
	'ApiSandbox'                => [ 'API_pískoviště' ],
	'AutoblockList'             => [ 'Seznam_automatických_blokování' ],
	'Badtitle'                  => [ 'Neplatný_název' ],
	'Blankpage'                 => [ 'Prázdná_stránka' ],
	'Block'                     => [ 'Blokování', 'Blokovani', 'Blokovat_uživatele', 'Blokovat_IP', 'Blokovat_uzivatele' ],
	'Booksources'               => [ 'Zdroje_knih' ],
	'BotPasswords'              => [ 'Hesla_pro_boty' ],
	'BrokenRedirects'           => [ 'Přerušená_přesměrování', 'Prerusena_presmerovani' ],
	'Categories'                => [ 'Kategorie' ],
	'ChangeContentModel'        => [ 'Změnit_model_obsahu_stránky' ],
	'ChangeCredentials'         => [ 'Změna_přihlašovacích_údajů' ],
	'ChangeEmail'               => [ 'Změna_emailu', 'Zmena_emailu' ],
	'ChangePassword'            => [ 'Změna_hesla', 'Zmena_hesla' ],
	'ComparePages'              => [ 'Porovnání_stránek', 'PorovnáníStránek', 'Porovnani_stranek', 'PorovnaniStranek' ],
	'Confirmemail'              => [ 'Potvrdit_e-mail' ],
	'Contributions'             => [ 'Příspěvky', 'Prispevky' ],
	'CreateAccount'             => [ 'Vytvořit_účet', 'Vytvorit_ucet' ],
	'Deadendpages'              => [ 'Slepé_stránky', 'Slepe_stranky' ],
	'DeletedContributions'      => [ 'Smazané_příspěvky', 'Smazane_prispevky' ],
	'Diff'                      => [ 'Rozdíl' ],
	'DoubleRedirects'           => [ 'Dvojitá_přesměrování', 'Dvojita_presmerovani' ],
	'EditTags'                  => [ 'Upravit_značky' ],
	'EditWatchlist'             => [ 'Upravit_seznam_sledovaných_stránek' ],
	'Emailuser'                 => [ 'E-mail' ],
	'ExpandTemplates'           => [ 'Testy_šablon' ],
	'Export'                    => [ 'Exportovat_stránky' ],
	'Fewestrevisions'           => [ 'Stránky_s_nejméně_editacemi', 'Stranky_s_nejmene_editacemi', 'Stránky_s_nejmenším_počtem_editací' ],
	'FileDuplicateSearch'       => [ 'Hledání_duplicitních_souborů', 'Hledani_duplicitnich_souboru' ],
	'Filepath'                  => [ 'Cesta_k_souboru' ],
	'Import'                    => [ 'Importovat_stránky' ],
	'Invalidateemail'           => [ 'Zneplatnit_e-mail', 'Zrušit_potvrzení_e-mailu' ],
	'BlockList'                 => [ 'Blokovaní_uživatelé', 'Blokovani_uzivatele', 'Zablokovaní_uživatelé' ],
	'LinkSearch'                => [ 'Hledání_odkazů', 'Hledani_odkazu' ],
	'Listadmins'                => [ 'Seznam_správců', 'Seznam_spravcu' ],
	'Listbots'                  => [ 'Seznam_botů', 'Seznam_botu' ],
	'ListDuplicatedFiles'       => [ 'Seznam_duplicitních_souborů' ],
	'Listfiles'                 => [ 'Seznam_souborů', 'Seznam_souboru' ],
	'Listgrants'                => [ 'Seznam_skupin_oprávnění', 'Seznam_skupin_opravneni', 'Seznam_grantů' ],
	'Listgrouprights'           => [ 'Práva_uživatelských_skupin', 'Seznam_uživatelských_práv', 'Seznam_uzivatelskych_prav' ],
	'Listredirects'             => [ 'Seznam_přesměrování', 'Seznam_presmerovani' ],
	'Listusers'                 => [ 'Uživatelé', 'Uzivatele', 'Seznam_uživatelů', 'Seznam_uzivatelu' ],
	'Lockdb'                    => [ 'Zamknout_databázi', 'Zamknout_databazi' ],
	'Log'                       => [ 'Protokolovací_záznamy', 'Protokoly', 'Protokol', 'Protokolovaci_zaznamy' ],
	'Lonelypages'               => [ 'Sirotčí_stránky', 'Sirotci_stranky' ],
	'Longpages'                 => [ 'Nejdelší_stránky', 'Nejdelsi_stranky' ],
	'MediaStatistics'           => [ 'Statistika_souborů', 'Statistiky_souborů' ],
	'MergeHistory'              => [ 'Sloučení_historie', 'Slouceni_historie', 'Sloučit_historii' ],
	'MIMEsearch'                => [ 'Hledání_podle_MIME', 'Hledani_podle_MIME', 'Hledat_podle_MIME_typu' ],
	'Mostcategories'            => [ 'Stránky_s_nejvíce_kategoriemi', 'Stranky_s_nejvice_kategoriemi', 'Stránky_s_nejvyšším_počtem_kategorií' ],
	'Mostimages'                => [ 'Nejpoužívanější_soubory', 'Nejpouzivanejsi_soubory' ],
	'Mostlinked'                => [ 'Nejodkazovanější_stránky', 'Nejodkazovanejsi_stranky' ],
	'Mostlinkedcategories'      => [ 'Nejpoužívanější_kategorie', 'Nejpouzivanejsi_kategorie' ],
	'Mostlinkedtemplates'       => [ 'Nejpoužívanější_šablony', 'Nejpouzivanejsi_sablony' ],
	'Mostrevisions'             => [ 'Stránky_s_nejvíce_editacemi', 'Stranky_s_nejvice_editacemi', 'Stránky_s_nejvyšším_počtem_editací' ],
	'Movepage'                  => [ 'Přesunout_stránku', 'Přejmenovat_stránku' ],
	'Mycontributions'           => [ 'Moje_příspěvky', 'Mé_příspěvky', 'Me_prispevky' ],
	'MyLanguage'                => [ 'V_mém_jazyce', 'Můj_jazyk' ],
	'Mypage'                    => [ 'Moje_stránka', 'Moje_stranka', 'Má_stránka' ],
	'Mytalk'                    => [ 'Moje_diskuse', 'Má_diskuse' ],
	'Myuploads'                 => [ 'Moje_soubory', 'Mé_soubory' ],
	'Newimages'                 => [ 'Nové_soubory', 'Nové_obrázky', 'Galerie_nových_obrázků', 'Nove_obrazky' ],
	'Newpages'                  => [ 'Nové_stránky', 'Nove_stranky', 'Nejnovější_stránky', 'Nejnovejsi_stranky' ],
	'PagesWithProp'             => [ 'Stránky_s_vlastností', 'Stránky_s_vlastnostmi' ],
	'PasswordReset'             => [ 'Reset_hesla', 'Resetovat_heslo', 'Obnova_hesla', 'Obnovit_heslo' ],
	'PermanentLink'             => [ 'Trvalý_odkaz' ],
	'Preferences'               => [ 'Nastavení', 'Nastaveni' ],
	'Prefixindex'               => [ 'Stránky_podle_začátku' ],
	'Protectedpages'            => [ 'Zamčené_stránky', 'Zamcene_stranky' ],
	'Protectedtitles'           => [ 'Zamčené_názvy', 'Zamcene_nazvy', 'Stránky_které_nelze_vytvořit' ],
	'Randompage'                => [ 'Náhodná_stránka', 'Nahodna_stranka' ],
	'Randomredirect'            => [ 'Náhodné_přesměrování', 'Nahodne_presmerovani' ],
	'RandomInCategory'          => [ 'Náhodná_stránka_v_kategorii' ],
	'Randomrootpage'            => [ 'Náhodná_kořenová_stránka' ],
	'Recentchanges'             => [ 'Poslední_změny', 'Posledni_zmeny' ],
	'Recentchangeslinked'       => [ 'Související_změny', 'Souvisejici_zmeny' ],
	'Redirect'                  => [ 'Přesměrování', 'Přesměrovat' ],
	'RemoveCredentials'         => [ 'Odstranění_přihlašovacích_údajů' ],
	'ResetTokens'               => [ 'Reinicializace_klíčů' ],
	'Revisiondelete'            => [ 'Smazat_revizi' ],
	'Search'                    => [ 'Hledání', 'Hledani' ],
	'Shortpages'                => [ 'Nejkratší_stránky', 'Nejkratsi_stranky' ],
	'Specialpages'              => [ 'Speciální_stránky', 'Specialni_stranky' ],
	'Statistics'                => [ 'Statistika', 'Statistiky' ],
	'Tags'                      => [ 'Značky', 'Znacky' ],
	'TrackingCategories'        => [ 'Sledovací_kategorie', 'Sledovaci_kategorie' ],
	'Unblock'                   => [ 'Odblokování', 'Odblokovani' ],
	'Uncategorizedcategories'   => [ 'Nekategorizované_kategorie', 'Nekategorizovane_kategorie' ],
	'Uncategorizedimages'       => [ 'Nekategorizované_soubory', 'Nekategorizovane_soubory' ],
	'Uncategorizedpages'        => [ 'Nekategorizované_stránky', 'Nekategorizovane_stranky' ],
	'Uncategorizedtemplates'    => [ 'Nekategorizované_šablony', 'Nekategorizovane_sablony' ],
	'Undelete'                  => [ 'Smazané_stránky', 'Smazane_stranky' ],
	'Unlockdb'                  => [ 'Odemknout_databázi', 'Odemknout_databazi' ],
	'Unusedcategories'          => [ 'Nepoužívané_kategorie', 'Nepouzivane_kategorie' ],
	'Unusedimages'              => [ 'Nepoužívané_soubory', 'Nepouzivane_soubory' ],
	'Unusedtemplates'           => [ 'Nepoužívané_šablony', 'Nepouzivane_sablony' ],
	'Unwatchedpages'            => [ 'Nesledované_stránky' ],
	'Upload'                    => [ 'Načíst_soubor', 'Nacist_soubor', 'Načíst_obrázek' ],
	'Userlogin'                 => [ 'Přihlásit', 'Prihlasit' ],
	'Userlogout'                => [ 'Odhlásit', 'Odhlasit' ],
	'Userrights'                => [ 'Uživatelská_práva', 'Správa_uživatelů', 'Uzivatelska_prava' ],
	'Version'                   => [ 'Verze' ],
	'Wantedcategories'          => [ 'Chybějící_kategorie', 'Požadované_kategorie', 'Pozadovane_kategorie' ],
	'Wantedfiles'               => [ 'Chybějící_soubory', 'Požadované_soubory', 'Pozadovane_soubory' ],
	'Wantedpages'               => [ 'Chybějící_stránky', 'Požadované_stránky', 'Pozadovane_stranky' ],
	'Wantedtemplates'           => [ 'Chybějící_šablony', 'Požadované_šablony', 'Pozadovane_sablony' ],
	'Watchlist'                 => [ 'Sledované_stránky', 'Sledovane_stranky' ],
	'Whatlinkshere'             => [ 'Co_odkazuje_na', 'Odkazuje_sem' ],
	'Withoutinterwiki'          => [ 'Bez_interwiki', 'Stránky_bez_interwiki_odkazů' ],
];

// TODO: unify "Strana" with "Stránka"
$magicWords = [
	'redirect'                  => [ '0', '#PŘESMĚRUJ', '#REDIRECT' ],
	'notoc'                     => [ '0', '__BEZOBSAHU__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__BEZGALERIE__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__VŽDYOBSAH__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__OBSAH__', '__TOC__' ],
	'noeditsection'             => [ '0', '__BEZEDITOVATČÁST__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'AKTUÁLNÍMĚSÍC', 'AKTUÁLNÍMĚSÍC2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'AKTUÁLNÍMĚSÍC1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'AKTUÁLNÍMĚSÍCJMÉNO', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'AKTUÁLNÍMĚSÍCGEN', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'AKTUÁLNÍMĚSÍCZKR', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'AKTUÁLNÍDEN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'AKTUÁLNÍDEN2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'AKTUÁLNÍDENJMÉNO', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'AKTUÁLNÍROK', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'AKTUÁLNÍČAS', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'AKTUÁLNÍHODINA', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MÍSTNÍMĚSÍC', 'MÍSTNÍMĚSÍC2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'MÍSTNÍMĚSÍC1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'MÍSTNÍMĚSÍCJMÉNO', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'MÍSTNÍMĚSÍCGEN', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'MÍSTNÍMĚSÍCZKR', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'MÍSTNÍDEN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'MÍSTNÍDEN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'MÍSTNÍDENJMÉNO', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'MÍSTNÍROK', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'MÍSTNÍČAS', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'MÍSTNÍHODINA', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'POČETSTRAN', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'POČETČLÁNKŮ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'POČETSOUBORŮ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'POČETUŽIVATELŮ', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'POČETAKTIVNÍCHUŽIVATELŮ', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'POČETEDITACÍ', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'NÁZEVSTRANY', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'NÁZEVSTRANYE', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'JMENNÝPROSTOR', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'JMENNÝPROSTORE', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'ČÍSLOJMENNÉHOPROSTORU', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', 'DISKUSNÍPROSTOR', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUSNÍPROSTORE', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ČLÁNEKPROSTOR', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ČLÁNEKPROSTORE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'PLNÝNÁZEVSTRANY', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'PLNÝNÁZEVSTRANYE', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'NÁZEVPODSTRANY', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'NÁZEVPODSTRANYE', 'SUBPAGENAMEE' ],
	'rootpagename'              => [ '1', 'NÁZEVKOŘENOVÉSTRANY', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', 'NÁZEVKOŘENOVÉSTRANYE', 'ROOTPAGENAMEE' ],
	'basepagename'              => [ '1', 'NÁZEVNADSTRANY', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'NÁZEVNADSTRANYE', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'NÁZEVDISKUSE', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'NÁZEVDISKUSEE', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'NÁZEVČLÁNKU', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'NÁZEVČLÁNKUE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subst'                     => [ '0', 'VLOŽIT:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'VLOŽITNW:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'náhled', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'náhled=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'vpravo', 'right' ],
	'img_left'                  => [ '1', 'vlevo', 'left' ],
	'img_none'                  => [ '1', 'žádné', 'none' ],
	'img_width'                 => [ '1', '$1pixelů', '$1px' ],
	'img_center'                => [ '1', 'střed', 'center', 'centre' ],
	'img_framed'                => [ '1', 'rám', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'bezrámu', 'frameless' ],
	'img_lang'                  => [ '1', 'jazyk=$1', 'lang=$1' ],
	'img_page'                  => [ '1', 'strana=$1', 'strana_$1', 'page=$1', 'page $1' ],
	'img_border'                => [ '1', 'okraj', 'border' ],
	'img_link'                  => [ '1', 'odkaz=$1', 'link=$1' ],
	'img_class'                 => [ '1', 'třída=$1', 'class=$1' ],
	'int'                       => [ '0', 'HLÁŠENÍ:', 'INT:' ],
	'sitename'                  => [ '1', 'NÁZEVWEBU', 'SITENAME' ],
	'ns'                        => [ '0', 'JMENNÝPROSTOR:', 'NS:' ],
	'nse'                       => [ '0', 'JMENNÝPROSTORE:', 'NSE:' ],
	'localurl'                  => [ '0', 'MÍSTNÍURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'MÍSTNÍURLE:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', 'CESTAKČLÁNKU', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', 'IDSTRÁNKY', 'PAGEID' ],
	'servername'                => [ '0', 'NÁZEVSERVERU', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'CESTAKESKRIPTŮM', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'CESTAKESTYLŮM', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'SKLOŇUJ:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'POHLAVÍ:', 'GENDER:' ],
	'bidi'                      => [ '0', 'OBASMĚRY:', 'BIDI:' ],
	'notitleconvert'            => [ '0', '__BEZKONVERZENADPISU__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__BEZKONVERZEOBSAHU__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'AKTUÁLNÍTÝDEN', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'AKTUÁLNÍDENTÝDNE', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'MÍSTNÍTÝDEN', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'MÍSTNÍDENTÝDNE', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'IDREVIZE', 'REVISIONID' ],
	'revisionday'               => [ '1', 'DENREVIZE', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DENREVIZE2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'MĚSÍCREVIZE', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'MĚSÍCREVIZE1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'ROKREVIZE', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'KÓDČASUREVIZE', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'AUTORREVIZE', 'REVISIONUSER' ],
	'revisionsize'              => [ '1', 'VELIKOSTREVIZE', 'REVISIONSIZE' ],
	'plural'                    => [ '0', 'PLURÁL:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'PLNÉURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'PLNÉURLE:', 'FULLURLE:' ],
	'canonicalurl'              => [ '0', 'KANONICKÉURL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'KANONICKÉURLE:', 'CANONICALURLE:' ],
	'lcfirst'                   => [ '0', 'PRVNÍMALÉ:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'PRVNÍVELKÉ:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'MALÁ:', 'LC:' ],
	'uc'                        => [ '0', 'VELKÁ:', 'UC:' ],
	'displaytitle'              => [ '1', 'ZOBRAZOVANÝNADPIS', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__LINKPŘIDATKOMENTÁŘ__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__BEZLINKUPŘIDATKOMENTÁŘ__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'VERZESOFTWARE', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'ENKÓDOVATURL:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'ENKÓDOVATNADPIS', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'AKTUÁLNÍKÓDČASU', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'MÍSTNÍKÓDČASU', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'ZNAKSMĚRU', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#JAZYK:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'JAZYKOBSAHU', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagelanguage'              => [ '1', 'JAZYKSTRÁNKY', 'PAGELANGUAGE' ],
	'pagesinnamespace'          => [ '1', 'STRÁNEKVEJMENNÉMPROSTORU:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'POČETSPRÁVCŮ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'FORMÁTUJČÍSLO', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'ZAROVNATVLEVO', 'PADLEFT' ],
	'padright'                  => [ '0', 'ZAROVNATVPRAVO', 'PADRIGHT' ],
	'special'                   => [ '0', 'speciální', 'special' ],
	'defaultsort'               => [ '1', 'KLÍČŘAZENÍ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'CESTAKSOUBORU:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'značka', 'tag' ],
	'hiddencat'                 => [ '1', '__SKRÝTKAT__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'STRÁNEKVKATEGORII', 'STRÁNEKVKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'VELIKOSTSTRÁNKY', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEXOVAT__', '__INDEX__' ],
	'noindex'                   => [ '1', '__NEINDEXOVAT__', '__NOINDEX__' ],
	'staticredirect'            => [ '1', '__STATICKÉPŘESMĚROVÁNÍ__', '__STATICREDIRECT__' ],
	'numberingroup'             => [ '1', 'POČETVESKUPINĚ', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'protectionlevel'           => [ '1', 'ÚROVEŇZAMČENÍ', 'PROTECTIONLEVEL' ],
	'protectionexpiry'          => [ '1', 'VYPRŠENÍZAMČENÍ', 'PROTECTIONEXPIRY' ],
	'formatdate'                => [ '0', 'formátujdatum', 'formatdate', 'dateformat' ],
	'pagesincategory_all'       => [ '0', 'vše', 'all' ],
	'pagesincategory_pages'     => [ '0', 'stránky', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'kategorie', 'subcats' ],
	'pagesincategory_files'     => [ '0', 'soubory', 'files' ],
];

/**
 * Date formats list for Special:Preferences
 * see $dateFormats for definitions
 */
$datePreferences = [
	'ČSN basic dt',
	'ČSN padded dt',
	'ČSN basic td',
	'ČSN padded td',
	'PČP dt',
	'PČP td',
	'ISO dt',
];

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
$dateFormats = [
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
];

/**
 * Default list of book sources
 * Hledání knihy podle ISBN
 */
$bookstoreList = [
	'Národní knihovna'          => 'http://aleph.nkp.cz/F/?func=find-a&find_code=ISN&request=$1',
	'Státní technická knihovna' => 'http://www.stk.cz/cgi-bin/dflex/CZE/STK/BROWSE?A=01&V=$1',
	'inherit' => true,
];

/**
 * Regular expression matching the "link trail", e.g. "ed" in [[Toast]]ed, as
 * the first group, and the remainder of the string as the second group.
 */
# Písmena, která se mají objevit jako část odkazu ve formě '[[jazyk]]y' atd:
$linkTrail = '/^([a-záčďéěíňóřšťúůýž]+)(.*)$/sDu';

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];
