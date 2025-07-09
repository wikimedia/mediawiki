<?php
/** Serbo-Croatian (Latin script) (srpskohrvatski (latinica))
 *
 * @file
 * @ingroup Languages
 *
 * @author Aca
 * @author Kaganer
 * @author Kolega2357
 * @author Nemo bis
 * @author OC Ripper
 * @author לערי ריינהארט
 */

/**
 * Fallback prioritizes language codes in the following order:
 *
 * 1. Latin-script Ijekavian codes
 * 2. Latin-script Ekavian codes
 * 3. Cyrillic-script Ekavian codes
 *
 * This order aligns with T399126.
 */
$fallback = 'sh, bs, hr, sr-latn, sr-el, sh-cyrl, sr-cyrl, sr-ec';

$namespaceNames = [
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_s_korisnikom',
	NS_PROJECT_TALK     => 'Razgovor_o_$1',
	NS_FILE             => 'Datoteka',
	NS_FILE_TALK        => 'Razgovor_o_datoteci',
	NS_MEDIAWIKI        => 'MediaWiki', // T385825
	NS_MEDIAWIKI_TALK   => 'Razgovor_o_MediaWikiju',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
];

$namespaceAliases = [
	'Razgovor_sa_korisnikom' => NS_USER_TALK,
	'MediaWiki_razgovor' => NS_MEDIAWIKI_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Korisnik', 'female' => 'Korisnica' ],
	NS_USER_TALK => [ 'male' => 'Razgovor_s_korisnikom', 'female' => 'Razgovor_s_korisnicom' ],
];

$datePreferences = [
	'default',
	'dmy sh-latn',
	'ISO 8601',
];

$defaultDateFormat = 'dmy sh-latn';

$dateFormats = [
	'dmy sh-latn time' => 'H:i',
	'dmy sh-latn date' => 'j. xg Y.',
	'dmy sh-latn monthonly' => 'xg Y.',
	'dmy sh-latn both' => 'j. xg Y. u H:i',
	'dmy sh-latn pretty' => 'j. xg',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Aktivni_korisnici' ],
	'Allmessages'               => [ 'Sve_poruke' ],
	'Allpages'                  => [ 'Sve_stranice' ],
	'Ancientpages'              => [ 'Najstarije_stranice' ],
	'Blankpage'                 => [ 'Prazna_stranica' ],
	'Booksources'               => [ 'Traži_ISBN' ],
	'BrokenRedirects'           => [ 'Kriva_preusmjerenja' ],
	'Categories'                => [ 'Kategorije' ],
	'Confirmemail'              => [ 'E-mail_potvrda' ],
	'Contributions'             => [ 'Doprinosi' ],
	'CreateAccount'             => [ 'Stvori_račun' ],
	'Deadendpages'              => [ 'Mrtve_stranice' ],
	'DeletedContributions'      => [ 'Obrisani_doprinosi' ],
	'DoubleRedirects'           => [ 'Dvostruka_preusmjerenja' ],
	'Emailuser'                 => [ 'E-mail', 'Elektronska_pošta' ],
	'Export'                    => [ 'Izvezi' ],
	'Fewestrevisions'           => [ 'Najmanje_uređivane_stranice' ],
	'FileDuplicateSearch'       => [ 'Traži_kopije_datoteka' ],
	'Filepath'                  => [ 'Putanja_datoteke' ],
	'Import'                    => [ 'Uvezi' ],
	'LinkSearch'                => [ 'Traži_poveznice', 'Traži_linkove' ],
	'Listadmins'                => [ 'Popis_administratora' ],
	'Listbots'                  => [ 'Popis_botova' ],
	'Listgrouprights'           => [ 'Popis_korisničkih_prava' ],
	'Listredirects'             => [ 'Popis_preusmjerenja' ],
	'Listusers'                 => [ 'Popis_korisnika', 'Korisnički_popis' ],
	'Lockdb'                    => [ 'Zaključaj_bazu' ],
	'Log'                       => [ 'Evidencije', 'Registri' ],
	'Lonelypages'               => [ 'Usamljene_stranice', 'Siročad' ],
	'Longpages'                 => [ 'Duge_stranice' ],
	'MergeHistory'              => [ 'Spoji_historiju' ],
	'MIMEsearch'                => [ 'MIME_pretraga' ],
	'Mostcategories'            => [ 'Najviše_kategorija' ],
	'Mostimages'                => [ 'Najviše_povezane_datoteke', 'Najviše_povezane_slike' ],
	'Mostlinked'                => [ 'Najviše_povezane_stranice' ],
	'Mostlinkedcategories'      => [ 'Najviše_povezane_kategorije', 'Najviše_korištene_kategorije' ],
	'Mostlinkedtemplates'       => [ 'Najviše_povezani_šabloni', 'Najviše_korišteni_šabloni' ],
	'Mostrevisions'             => [ 'Najviše_uređivane_stranice' ],
	'Movepage'                  => [ 'Premjesti_stranicu' ],
	'Mycontributions'           => [ 'Moji_doprinosi' ],
	'Mypage'                    => [ 'Moja_stranica' ],
	'Mytalk'                    => [ 'Moj_razgovor' ],
	'Myuploads'                 => [ 'Moje_postavljene_datoteke' ],
	'Newimages'                 => [ 'Nove_datoteke', 'Nove_slike' ],
	'Newpages'                  => [ 'Nove_stranice' ],
	'Preferences'               => [ 'Postavke' ],
	'Prefixindex'               => [ 'Prefiks_indeks', 'Stranice_po_prefiksu' ],
	'Protectedpages'            => [ 'Zaštićene_stranice' ],
	'Protectedtitles'           => [ 'Zaštićeni_naslovi' ],
	'Randompage'                => [ 'Slučajna_stranica' ],
	'Randomredirect'            => [ 'Slučajno_preusmjerenje' ],
	'Recentchanges'             => [ 'Nedavne_izmjene' ],
	'Recentchangeslinked'       => [ 'Povezane_izmjene' ],
	'Revisiondelete'            => [ 'Brisanje_izmjene' ],
	'Search'                    => [ 'Traži' ],
	'Shortpages'                => [ 'Kratke_stranice' ],
	'Specialpages'              => [ 'Posebne_stranice' ],
	'Statistics'                => [ 'Statistike' ],
	'Unblock'                   => [ 'Odblokiraj' ],
	'Uncategorizedcategories'   => [ 'Nekategorizirane_kategorije' ],
	'Uncategorizedimages'       => [ 'Nekategorizirane_datoteke', 'Nekategorizirane_slike' ],
	'Uncategorizedpages'        => [ 'Nekategorizirane_stranice' ],
	'Uncategorizedtemplates'    => [ 'Nekategorizirani_šabloni' ],
	'Undelete'                  => [ 'Vrati' ],
	'Unlockdb'                  => [ 'Otključaj_bazu' ],
	'Unusedcategories'          => [ 'Nekorištene_kategorije' ],
	'Unusedimages'              => [ 'Nekorištene_datoteke', 'Nekorištene_slike' ],
	'Unusedtemplates'           => [ 'Nekorišteni_šabloni' ],
	'Unwatchedpages'            => [ 'Negledane_stranice' ],
	'Upload'                    => [ 'Postavi_datoteku' ],
	'Userrights'                => [ 'Korisnička_prava' ],
	'Version'                   => [ 'Verzija' ],
	'Wantedcategories'          => [ 'Tražene_kategorije' ],
	'Wantedfiles'               => [ 'Tražene_datoteke' ],
	'Wantedpages'               => [ 'Tražene_stranice' ],
	'Wantedtemplates'           => [ 'Traženi_šabloni' ],
	'Watchlist'                 => [ 'Spisak_praćenja' ],
	'Whatlinkshere'             => [ 'Što_vodi_ovdje' ],
	'Withoutinterwiki'          => [ 'Bez_interwikija' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'TRENUTNIDAN', 'TRENUTAČNIDAN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'TRENUTNIDAN2', 'TRENUTAČNIDAN2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'TRENUTNIDANIME', 'TRENUTAČNIDANIME', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'TRENUTNIDANSEDMICE', 'TRENUTAČNIDANTJEDNA', 'TRENUTNIDANNEDELJE', 'TRENUTNIDANTJEDNA', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'TRENUTNISAT', 'TRENUTAČNISAT', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'TRENUTNIMJESEC', 'TRENUTNIMESEC', 'TRENUTAČNIMJESEC', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'TRENUTNIMJESEC1', 'TRENUTNIMESEC1', 'TRENUTAČNIMJESEC1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'TRENUTNIMJESECSKR', 'TRENUTNIMESECSKR', 'TRENUTAČNIMJESECSKR', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'TRENUTNIMJESECIME', 'TRENUTNIMESECIME', 'TRENUTAČNIMJESECIME', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'TRENUTNIMJESECROD', 'TRENUTNIMESECROD', 'TRENUTAČNIMJESECROD', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'TRENUTNOVRIJEME', 'TRENUTNOVREME', 'TRENUTAČNOVRIJEME', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'TRENUTNAOZNAKAVREMENA', 'TRENUTAČNAOZNAKAVREMENA', 'CURRENTTIMESTAMP' ],
	'currentweek'               => [ '1', 'TRENUTNASEDMICA', 'TRENUTAČNITJEDAN', 'TRENUTNANEDELJA', 'TRENUTNITJEDAN', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'TRENUTNAGODINA', 'TRENUTAČNAGODINA', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__FORSIRANISADRŽAJ__', '__UKLJUČISADRŽAJ__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'formatdatuma', 'formatdate', 'dateformat' ],
	'fullpagename'              => [ '1', 'PUNOIMESTRANE', 'PUNOIMESTRANICE', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'PUNOIMESTRANEE', 'PUNOIMESTRANICEE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'PUNIURL:', 'PUNURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'PUNIURLE:', 'PUNURLE:', 'FULLURLE:' ],
	'hiddencat'                 => [ '1', '__SAKRIVENAKATEGORIJA__', 'SKRIVENAKAT', '__SAKRIVENAKAT__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'osnovnacrta', 'pocetna_linija', 'baseline' ],
	'img_border'                => [ '1', 'granica', 'obrub', 'border' ],
	'img_bottom'                => [ '1', 'dno', 'bottom' ],
	'img_center'                => [ '1', 'centar', 'c', 'središte', 'center', 'centre' ],
	'img_framed'                => [ '1', 'okvir', 'ram', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'bezokvira', 'bez_okvira', 'frameless' ],
	'img_left'                  => [ '1', 'lijevo', 'levo', 'left' ],
	'img_manualthumb'           => [ '1', 'minijatura=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'sredina', 'middle' ],
	'img_none'                  => [ '1', 'n', 'ništa', 'bez', 'none' ],
	'img_page'                  => [ '1', 'stranica=$1', 'stranica_$1', 'strana=$1', 'strana_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'desno', 'right' ],
	'img_text_bottom'           => [ '1', 'tekst-dno', 'text-bottom' ],
	'img_text_top'              => [ '1', 'vrh_teksta', 'tekst_vrh', 'text-top' ],
	'img_thumbnail'             => [ '1', 'mini', 'minijatura', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'vrh', 'top' ],
	'img_upright'               => [ '1', 'na_gore', 'na_gore=$1', 'na_gore_$1', 'uspravno', 'uspravno=$1', 'uspravno_$1', 'upright', 'upright=$1', 'upright $1' ],
	'localday'                  => [ '1', 'LOKALNIDAN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALNIDAN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'LOKALNIDANIME', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'LOKALNIDANSEDMICE', 'LOKALNIDANTJEDNA', 'LOKALNIDANNEDELJE', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'LOKALNISAT', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'LOKALNIMJESEC', 'LOKALNIMESEC', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKALNIMJESEC1', 'LOKALNIMESEC1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'LOKALNIMJESECSKR', 'LOKALNIMESECSKR', 'LOKALNIMJESECKRAT', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'LOKALNIMJESECIME', 'LOKALNIMESECIME', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'LOKALNIMJESECROD', 'LOKALNIMESECROD', 'LOKALNIMJESECGEN', 'LOKALNIMESECGEN', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'LOKALNOVRIJEME', 'LOKALNOVREME', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'LOKALNAOZNAKAVREMENA', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'LOKALNIURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALNIURLE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKALNASEDMICA', 'LOKALNITJEDAN', 'LOKALNANEDELJA', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'LOKALNAGODINA', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'POR:', 'MSG:' ],
	'msgnw'                     => [ '0', 'NVPOR:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'IMENSKIPROSTOR', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'IMENSKIPROSTORI', 'NAMESPACEE' ],
	'nocontentconvert'          => [ '0', '__BEZCC__', '__BPS__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__BEZ_IZMJENA__', '__BEZIZMJENA__', '__BEZ_IZMENA__', '__BEZIZMENA__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__BEZGALERIJE__', '__NOGALLERY__' ],
	'notitleconvert'            => [ '0', '__BEZTC__', '__BEZKN__', '__BPN__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__BEZSADRŽAJA__', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'BROJAKTIVNIHKORISNIKA', 'NUMBEROFACTIVEUSERS' ],
	'numberofarticles'          => [ '1', 'BROJČLANAKA', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'BROJIZMJENA', 'BROJIZMENA', 'BROJUREĐIVANJA', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'BROJDATOTEKA', 'BROJFAJLOVA', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'BROJSTRANICA', 'BROJSTRANA', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'BROJKORISNIKA', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'STRANICA', 'IMESTRANICE', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'STRANICE', 'IMESTRANICEE', 'PAGENAMEE' ],
	'pagesize'                  => [ '1', 'VELICINASTRANICE', 'VELIČINASTRANICE', 'VELIČINASTRANE', 'VELICINASTRANE', 'PAGESIZE' ],
	'plural'                    => [ '0', 'MNOŽINA:', 'PLURAL:' ],
	'redirect'                  => [ '0', '#PREUSMJERI', '#PREUSMERI', '#REDIRECT' ],
	'revisionday'               => [ '1', 'IZMJENEDANA', 'IZMENEDANA', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'IZMJENEDANA2', 'IZMENEDANA2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'IDIZMJENE', 'IDIZMENE', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'MJESECIZMJENE', 'MESECIZMENE', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'MJESECIZMJENE1', 'MESECIZMENE1', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'OZNAKAVREMENAIZMJENE', 'OZNAKAVREMENAIZMENE', 'REVISIONTIMESTAMP' ],
	'revisionyear'              => [ '1', 'GODINAIZMJENE', 'GODINAIZMENE', 'REVISIONYEAR' ],
	'special'                   => [ '0', 'posebno', 'special' ],
	'subst'                     => [ '0', 'ZAMJENI:', 'ZAMENI:', 'ZAMJENA:', 'SUBST:' ],
	'talkspace'                 => [ '1', 'PROSTORZARAZGOVOR', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'PROSTORIZARAZGOVOR', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__SADRŽAJ__', '__TOC__' ],
];

$linkTrail = '/^([a-zčćđžš]+)(.*)$/sDu';
