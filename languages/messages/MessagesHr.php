<?php
/** Croatian (Hrvatski)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Brest
 * @author Dalibor Bosits
 * @author Demicx
 * @author Dnik
 * @author Ex13
 * @author Excaliboor
 * @author Luka Krstulovic
 * @author MayaSimFan
 * @author Meno25
 * @author Mvrban
 * @author Roberta F.
 * @author SpeedyGonsales
 * @author Tivek
 * @author Treecko
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Mediji',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Suradnik',
	NS_USER_TALK        => 'Razgovor_sa_suradnikom',
	NS_PROJECT_TALK     => 'Razgovor_$1',
	NS_FILE             => 'Datoteka',
	NS_FILE_TALK        => 'Razgovor_o_datoteci',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_razgovor',
	NS_TEMPLATE         => 'Predložak',
	NS_TEMPLATE_TALK    => 'Razgovor_o_predlošku',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

$namespaceAliases = array(
	'Slika' => NS_FILE,
	'Razgovor_o_slici' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dvostruka_preusmjeravanja' ),
	'BrokenRedirects'           => array( 'Kriva_preusmjeravanja' ),
	'Disambiguations'           => array( 'Razdvojbe' ),
	'Userlogin'                 => array( 'Prijava' ),
	'Userlogout'                => array( 'Odjava' ),
	'CreateAccount'             => array( 'Stvori_račun' ),
	'Preferences'               => array( 'Postavke' ),
	'Watchlist'                 => array( 'Praćene_stranice' ),
	'Recentchanges'             => array( 'Nedavne_promjene' ),
	'Upload'                    => array( 'Postavi_datoteku' ),
	'Listfiles'                 => array( 'Datoteke', 'Slike' ),
	'Newimages'                 => array( 'Nove_datoteke', 'Nove_slike' ),
	'Listusers'                 => array( 'Suradnici', 'Popis_suradnika' ),
	'Listgrouprights'           => array( 'Suradničke_skupine' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Slučajna_stranica' ),
	'Lonelypages'               => array( 'Siročad' ),
	'Uncategorizedpages'        => array( 'Nekategorizirane_stranice' ),
	'Uncategorizedcategories'   => array( 'Nekategorizirane_kategorije' ),
	'Uncategorizedimages'       => array( 'Nekategorizirane_slike' ),
	'Uncategorizedtemplates'    => array( 'Nekategorizirani_predlošci' ),
	'Unusedcategories'          => array( 'Nekorištene_kategorije' ),
	'Unusedimages'              => array( 'Nekorištene_datoteke', 'Nekorištene_slike' ),
	'Wantedpages'               => array( 'Tražene_stranice' ),
	'Wantedcategories'          => array( 'Tražene_kategorije' ),
	'Wantedfiles'               => array( 'Tražene_datoteke' ),
	'Wantedtemplates'           => array( 'Traženi_predlošci' ),
	'Mostlinked'                => array( 'Najviše_povezane_stranice' ),
	'Mostlinkedcategories'      => array( 'Najviše_povezane_kategorije' ),
	'Mostlinkedtemplates'       => array( 'Najviše_povezani_predlošci' ),
	'Mostimages'                => array( 'Najviše_povezane_datoteke', 'Najviše_povezane_slike' ),
	'Mostcategories'            => array( 'Najviše_kategorija' ),
	'Mostrevisions'             => array( 'Najviše_uređivane_stranice' ),
	'Fewestrevisions'           => array( 'Najmanje_uređivane_stranice' ),
	'Shortpages'                => array( 'Kratke_stranice' ),
	'Longpages'                 => array( 'Duge_stranice' ),
	'Newpages'                  => array( 'Nove_stranice' ),
	'Ancientpages'              => array( 'Stare_stranice' ),
	'Deadendpages'              => array( 'Slijepe_ulice' ),
	'Protectedpages'            => array( 'Zaštićene_stranice' ),
	'Protectedtitles'           => array( 'Zaštićeni_naslovi' ),
	'Allpages'                  => array( 'Sve_stranice' ),
	'Prefixindex'               => array( 'Prefiks_indeks', 'Stranice_po_prefiksu' ),
	'Ipblocklist'               => array( 'Blokirane_adrese' ),
	'Unblock'                   => array( 'Odblokiraj' ),
	'Specialpages'              => array( 'Posebne_stranice' ),
	'Contributions'             => array( 'Doprinosi' ),
	'Emailuser'                 => array( 'Elektronička_pošta', 'E-mail' ),
	'Confirmemail'              => array( 'E-mail_potvrda' ),
	'Whatlinkshere'             => array( 'Što_vodi_ovamo' ),
	'Recentchangeslinked'       => array( 'Povezane_promjene' ),
	'Movepage'                  => array( 'Premjesti_stranicu' ),
	'Blockme'                   => array( 'Blokiraj_me' ),
	'Booksources'               => array( 'Traži_ISBN' ),
	'Categories'                => array( 'Kategorije' ),
	'Export'                    => array( 'Izvezi' ),
	'Version'                   => array( 'Verzija' ),
	'Allmessages'               => array( 'Sve_poruke' ),
	'Log'                       => array( 'Evidencije' ),
	'Blockip'                   => array( 'Blokiraj' ),
	'Undelete'                  => array( 'Vrati' ),
	'Import'                    => array( 'Uvezi' ),
	'Lockdb'                    => array( 'Zaključaj_bazu' ),
	'Unlockdb'                  => array( 'Otključaj_bazu' ),
	'Userrights'                => array( 'Suradnička_prava' ),
	'MIMEsearch'                => array( 'MIME_tražilica' ),
	'FileDuplicateSearch'       => array( 'Traži_kopije_datoteka' ),
	'Unwatchedpages'            => array( 'Negledane_stranice' ),
	'Listredirects'             => array( 'Preusmjeravanja' ),
	'Revisiondelete'            => array( 'Brisanje_izmjene' ),
	'Unusedtemplates'           => array( 'Nekorišteni_predlošci' ),
	'Randomredirect'            => array( 'Slučajno_preusmjeravanje' ),
	'Mypage'                    => array( 'Moja_stranica' ),
	'Mytalk'                    => array( 'Moj_razgovor' ),
	'Mycontributions'           => array( 'Moji_doprinosi' ),
	'Listadmins'                => array( 'Administratori', 'Admini' ),
	'Listbots'                  => array( 'Botovi' ),
	'Popularpages'              => array( 'Popularne_stranice' ),
	'Search'                    => array( 'Traži' ),
	'Resetpass'                 => array( 'Izmijeni_lozinku' ),
	'Withoutinterwiki'          => array( 'Bez_međuwikipoveznica', 'Bez_interwikija' ),
	'MergeHistory'              => array( 'Spoji_povijest' ),
	'Filepath'                  => array( 'Putanja_datoteke' ),
	'Invalidateemail'           => array( 'Nevaljana_elektronička_pošta' ),
	'Blankpage'                 => array( 'Prazna_stranica' ),
	'LinkSearch'                => array( 'Traži_poveznice', 'Traži_linkove' ),
	'DeletedContributions'      => array( 'Obrisani_doprinosi' ),
	'Tags'                      => array( 'Oznake' ),
	'Activeusers'               => array( 'Aktivni_suradnici' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#PREUSMJERI', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BEZSADRŽAJA__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__BEZGALERIJE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__UKLJUČISADRŽAJ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__SADRŽAJ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BEZUREĐIVANJAODLOMAKA__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__BEZZAGLAVLJA__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'TRENUTAČNIMJESEC', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'TRENUTAČNIMJESEC1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'TRENUTAČNIMJESECIME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'TRENUTAČNIMJESECIMEGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'TRENUTAČNIMJESECKRAT', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'TRENUTAČNIDAN', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'TRENUTAČNIDAN2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'TRENUTAČNIDANIME', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'TRENUTAČNAGODINA', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'TRENUTAČNOVRIJEME', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'TRENUTAČNISAT', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MJESNIMJESEC', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'MJESNIMJESEC1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'MJESNIMJESECIME', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'MJESNIMJESECIMEGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'MJESNIMJESECKRAT', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'MJESNIDAN', 'LOCALDAY' ),
	'localday2'             => array( '1', 'MJESNIDAN2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'MJESNIDANIME', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'MJESNAGODINA', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'MJESNOVRIJEME', 'LOCALTIME' ),
	'localhour'             => array( '1', 'MJESNISAT', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'BROJSTRANICA', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'BROJČLANAKA', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'BROJDATOTEKA', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'BROJSURADNIKA', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'BROJAKTIVNIHSURADNIKA', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'BROJUREĐIVANJA', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'BROJPREGLEDA', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'IMESTRANICE', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'IMESTRANICEE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'IMENSKIPROSTOR', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'IMENSKIPROSTORE', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'RAZGOVOR', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'RAZGOVORE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'PROSTORSTRANICE', 'IMPSTRANICE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'PROSTORSTRANICEE', 'IMPSTRANICEE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'PUNOIMESTRANICE', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'PUNOIMESTRANICEE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'IMEPODSTRANICE', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'IMEPODSTRANICEE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'IMEOSNOVNESTRANICE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'IMEOSNOVNESTRANICEE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'IMERAZGOVORASTRANICE', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'IMERAZGOVORASTRANICEE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'IMEGLAVNESTRANICE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'IMEGLAVNESTRANICEE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'subst'                 => array( '0', 'ZAMJENA:', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'minijatura', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'minijatura=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'desno', 'right' ),
	'img_left'              => array( '1', 'lijevo', 'left' ),
	'img_none'              => array( '1', 'ništa', 'none' ),
	'img_center'            => array( '1', 'središte', 'center', 'centre' ),
	'img_framed'            => array( '1', 'okvir', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'bezokvira', 'frameless' ),
	'img_page'              => array( '1', 'stranica=$1', 'stranica $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'uspravno=$1', 'uspravno $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'obrub', 'border' ),
	'img_baseline'          => array( '1', 'osnovnacrta', 'baseline' ),
	'img_sub'               => array( '1', 'potpis', 'ind', 'sub' ),
	'img_super'             => array( '1', 'natpis', 'eks', 'super', 'sup' ),
	'img_top'               => array( '1', 'vrh', 'top' ),
	'img_text_top'          => array( '1', 'tekst-vrh', 'text-top' ),
	'img_middle'            => array( '1', 'pola', 'middle' ),
	'img_bottom'            => array( '1', 'dno', 'bottom' ),
	'img_text_bottom'       => array( '1', 'tekst-dno', 'text-bottom' ),
	'sitename'              => array( '1', 'IMEPROJEKTA', 'SITENAME' ),
	'ns'                    => array( '0', 'IMP:', 'NS:' ),
	'localurl'              => array( '0', 'MJESNIURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'MJESNIURLE:', 'LOCALURLE:' ),
	'servername'            => array( '0', 'IMESERVERA', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'PUTANJASKRIPTE', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMATIKA:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__BEZPRETVARANJANASLOVA__', '__BPN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__BEZPRETVARANJASADRŽAJA__', '__BPS__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'TRENUTAČNITJEDAN', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'TRENUTAČNIDANTJEDNA', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'MJESNITJEDAN', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'MJESNIDANTJEDNA', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'IDIZMJENE', 'REVISIONID' ),
	'revisionday'           => array( '1', 'DANIZMJENE', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'DANIZMJENE2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'MJESECIZMJENE', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'GODINAIZMJENE', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'VREMENSKAOZNAKAIZMJENE', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'MNOŽINA:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'PUNIURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'PUNIURLE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'MSPRVO:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'VSPRVO:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'MS:', 'LC:' ),
	'uc'                    => array( '0', 'VS:', 'UC:' ),
	'raw'                   => array( '0', 'NEOBRAĐENO:', 'RAW:' ),
	'displaytitle'          => array( '1', 'POKAŽINASLOV', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'NEO', 'R' ),
	'newsectionlink'        => array( '1', '__NOVIODLOMAKPOVEZNICA__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'TRENUTAČNAIZMJENA', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'URLKODIRANJE:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'SIDROKODIRANJE', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'TRENUTAČNAOZNAKAVREMENA', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'MJESNAOZNAKAVREMENA', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#JEZIK:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'JEZIKPROJEKTA', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'STRANICEPOPROSTORU:', 'STRANICEUIMP', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'BROJADMINA', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'OBLIKBROJA', 'FORMATNUM' ),
	'padleft'               => array( '0', 'POSTAVALIJEVO', 'PADLEFT' ),
	'padright'              => array( '0', 'POSTAVADESNO', 'PADRIGHT' ),
	'special'               => array( '0', 'posebno', 'special' ),
	'defaultsort'           => array( '1', 'GLAVNIRASPORED:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'PUTANJADATOTEKE:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'oznaka', 'tag' ),
	'hiddencat'             => array( '1', '__SKRIVENAKAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'STRANICEPOKATEGORIJI', 'STRANICEUKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'VELIČINASTRANICE', 'PAGESIZE' ),
	'index'                 => array( '1', '__KAZALO__', '__INDEX__' ),
	'noindex'               => array( '1', '__BEZKAZALA__', '__NOINDEX__' ),
	'staticredirect'        => array( '1', '__NEPOMIČNOPREUSMJERAVANJE__', '__STATICREDIRECT__' ),
);

$datePreferences = array(
	'default',
	'dmy hr',
	'mdy',
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'dmy hr';

$dateFormats = array(
	'dmy hr time' => 'H:i',
	'dmy hr date' => 'j. F Y.',
	'dmy hr both' => 'H:i, j. F Y.',

	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'H:i, F j, Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$fallback8bitEncoding = 'iso-8859-2';

$linkTrail = '/^([čšžćđßa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Podcrtane poveznice',
'tog-highlightbroken'         => 'Istakni prazne poveznice <a href="" class="new">ovako</a> (inače, ovako<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Poravnaj odlomke i zdesna',
'tog-hideminor'               => 'Sakrij manje izmjene na stranici Nedavnih promjena',
'tog-hidepatrolled'           => 'Sakrij pregledane izmjene u nedavnim promjenama',
'tog-newpageshidepatrolled'   => 'Sakrij pregledane stranice iz popisa novih stranica',
'tog-extendwatchlist'         => 'Proširi popis praćenih stranica tako da prikaže sve promjene, ne samo najnovije',
'tog-usenewrc'                => 'Koristi poboljšan izgled nedavnih promjena (zahtjeva JavaScripte)',
'tog-numberheadings'          => 'Automatski označi naslove brojevima',
'tog-showtoolbar'             => 'Prikaži traku s alatima za uređivanje',
'tog-editondblclick'          => 'Dvoklik otvara uređivanje stranice (JavaScript)',
'tog-editsection'             => 'Prikaži poveznice za uređivanje pojedinih odlomaka',
'tog-editsectiononrightclick' => 'Pritiskom na desnu tipku miša otvori uređivanje pojedinih odlomaka (JavaScript)',
'tog-showtoc'                 => 'U člancima s više od tri odlomka prikaži tablicu sadržaja.',
'tog-rememberpassword'        => 'Zapamti moju lozinku u ovom pregledniku (najduže $1 {{PLURAL:$1|dan|dana|dana}})',
'tog-watchcreations'          => 'Dodaj članke koje kreiram na moj popis praćenja',
'tog-watchdefault'            => 'Dodaj sve nove i izmijenjene stranice u popis praćenja',
'tog-watchmoves'              => 'Dodaj sve stranice koje premjestim na popis praćenja',
'tog-watchdeletion'           => 'Dodaj sve stranice koje izbrišem na popis praćenja',
'tog-minordefault'            => 'Normalno označavaj sve moje izmjene kao manje',
'tog-previewontop'            => 'Prikaži kako će stranica izgledati iznad okvira za uređivanje',
'tog-previewonfirst'          => 'Prikaži kako će stranica izgledati čim otvorim uređivanje',
'tog-nocache'                 => 'Isključi međuspremnik (cache) stranica u pregledniku',
'tog-enotifwatchlistpages'    => 'Pošalji mi e-mail kod izmjene stranice u popisu praćenja',
'tog-enotifusertalkpages'     => 'Pošalji mi e-mail kod izmjene moje stranice za razgovor',
'tog-enotifminoredits'        => 'Pošalji mi e-mail i kod manjih izmjena',
'tog-enotifrevealaddr'        => 'Prikaži moju e-mail adresu u obavijestima o izmjeni',
'tog-shownumberswatching'     => 'Prikaži broj suradnika koji prate stranicu (u nedavnim izmjenama, popisu praćenja i samim člancima)',
'tog-oldsig'                  => 'Pregled postojećeg potpisa:',
'tog-fancysig'                => 'Običan potpis kao wikitekst (bez automatske poveznice)',
'tog-externaleditor'          => 'Uvijek rabi vanjski program za uređivanje (samo za napredne, potrebne su posebne postavke na računalu)',
'tog-externaldiff'            => 'Uvijek koristi vanjski program za usporedbu',
'tog-showjumplinks'           => 'Uključi pomoćne poveznice "Skoči na"',
'tog-uselivepreview'          => 'Uključi trenutačni pretpregled (JavaScript) (eksperimentalno)',
'tog-forceeditsummary'        => 'Podsjeti me ako sažetak uređivanja ostavljam praznim',
'tog-watchlisthideown'        => 'Sakrij moja uređivanja s popisa praćenja',
'tog-watchlisthidebots'       => 'Sakrij uređivanja botova s popisa praćenja',
'tog-watchlisthideminor'      => 'Sakrij manje promjene s popisa praćenja',
'tog-watchlisthideliu'        => 'Sakrij uređivanja prijavljenih s popisa praćenja',
'tog-watchlisthideanons'      => 'Sakrij uređivanja neprijavljenih s popisa praćenja',
'tog-watchlisthidepatrolled'  => 'Sakrij pregledane izmjene u popisu praćenja',
'tog-nolangconversion'        => 'Isključi pretvaranje pisma (latinica-ćirilica, kineske varijante itd.) ako to wiki podržava',
'tog-ccmeonemails'            => 'Pošalji mi kopiju e-maila kojeg pošaljem drugim suradnicima',
'tog-diffonly'                => 'Ne prikazuj sadržaj stranice prilikom usporedbe inačica',
'tog-showhiddencats'          => 'Prikaži skrivene kategorije',
'tog-norollbackdiff'          => 'Izostavi razliku nakon upotrebe ukloni',

'underline-always'  => 'Uvijek',
'underline-never'   => 'Nikad',
'underline-default' => 'Prema postavkama preglednika',

# Font style option in Special:Preferences
'editfont-style'     => 'Uredi područje font stila:',
'editfont-default'   => 'Prema postavkama preglednika',
'editfont-monospace' => 'Font s jednakim razmakom',
'editfont-sansserif' => 'Font Sans-serif',
'editfont-serif'     => 'Font Serif',

# Dates
'sunday'        => 'nedjelja',
'monday'        => 'ponedjeljak',
'tuesday'       => 'utorak',
'wednesday'     => 'srijeda',
'thursday'      => 'četvrtak',
'friday'        => 'petak',
'saturday'      => 'subota',
'sun'           => 'Ned',
'mon'           => 'Pon',
'tue'           => 'Uto',
'wed'           => 'Sri',
'thu'           => 'Čet',
'fri'           => 'Pet',
'sat'           => 'Sub',
'january'       => 'siječnja',
'february'      => 'veljače',
'march'         => 'ožujka',
'april'         => 'travnja',
'may_long'      => 'svibnja',
'june'          => 'lipnja',
'july'          => 'srpnja',
'august'        => 'kolovoza',
'september'     => 'rujna',
'october'       => 'listopada',
'november'      => 'studenog',
'december'      => 'prosinca',
'january-gen'   => 'siječnja',
'february-gen'  => 'veljače',
'march-gen'     => 'ožujka',
'april-gen'     => 'travnja',
'may-gen'       => 'svibnja',
'june-gen'      => 'lipnja',
'july-gen'      => 'srpnja',
'august-gen'    => 'kolovoza',
'september-gen' => 'rujna',
'october-gen'   => 'listopada',
'november-gen'  => 'studenog',
'december-gen'  => 'prosinca',
'jan'           => 'sij',
'feb'           => 'velj',
'mar'           => 'ožu',
'apr'           => 'tra',
'may'           => 'svi',
'jun'           => 'lip',
'jul'           => 'srp',
'aug'           => 'kol',
'sep'           => 'ruj',
'oct'           => 'lis',
'nov'           => 'stu',
'dec'           => 'pro',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategorije}}',
'category_header'                => 'Članci u kategoriji "$1"',
'subcategories'                  => 'Potkategorije',
'category-media-header'          => 'Mediji u kategoriji "$1":',
'category-empty'                 => "''U ovoj kategoriji trenutačno nema članaka ni medija.''",
'hidden-categories'              => '{{PLURAL:$1|Skrivena kategorija|Skrivene kategorije|Skrivenih kategorija}}',
'hidden-category-category'       => 'Skrivene kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Ova kategorija ima samo sljedeću podkategoriju.|Ova kategorija ima {{PLURAL:$1|podkategoriju|$1 podkategorije|$1 podkategorija}}, od njih $2 ukupno.}}',
'category-subcat-count-limited'  => 'Ova kategorija ima {{PLURAL:$1|podkategoriju|$1 podkategorije|$1 podkategorija}}.',
'category-article-count'         => '{{PLURAL:$2|Ova kategorija sadrži $2 članak.|{{PLURAL:$1|Prikazano je $1 članak|Prikazana su $1 članka|Prikazano je $1 članaka}} od njih $2 ukupno.}}',
'category-article-count-limited' => '{{PLURAL:$1|stranica je|$1 stranice su|$1 stranica je}} u ovoj kategoriji.',
'category-file-count'            => '{{PLURAL:$2|Ova kategorija sadrži samo sljedeću datoteku.|{{PLURAL:$1|datoteka je|$1 datoteke su|$1 datoteka je}} u ovoj kategoriji, od njih $2 ukupno.}}',
'category-file-count-limited'    => '{{PLURAL:$1|datoteka je|$1 datoteke su|$1 datoteka su}} u ovoj kategoriji.',
'listingcontinuesabbrev'         => 'nast.',
'index-category'                 => 'Indeksirane stranice',
'noindex-category'               => 'Neindeksirane stranice',

'mainpagetext'      => "'''Softver MediaWiki je uspješno instaliran.'''",
'mainpagedocfooter' => 'Pogledajte [http://meta.wikimedia.org/wiki/MediaWiki_localisation dokumentaciju o prilagodbi sučelja]
i [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Vodič za suradnike] za pomoć pri uporabi i podešavanju.',

'about'         => 'O',
'article'       => 'Članak',
'newwindow'     => '(otvara se u novom prozoru)',
'cancel'        => 'Odustani',
'moredotdotdot' => 'Više...',
'mypage'        => 'Moja stranica',
'mytalk'        => 'Moj razgovor',
'anontalk'      => 'Razgovor za ovu IP adresu',
'navigation'    => 'Orijentacija',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Nađi',
'qbbrowse'       => 'Pregledaj',
'qbedit'         => 'Uredi',
'qbpageoptions'  => 'Postavke stranice',
'qbpageinfo'     => 'O stranici',
'qbmyoptions'    => 'Moje stranice',
'qbspecialpages' => 'Posebne stranice',
'faq'            => 'Najčešća pitanja',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Dodaj temu',
'vector-action-delete'           => 'Izbriši',
'vector-action-move'             => 'Premjesti',
'vector-action-protect'          => 'Zaštiti',
'vector-action-undelete'         => 'Vrati',
'vector-action-unprotect'        => 'Ukloni zaštitu',
'vector-simplesearch-preference' => 'Omogući poboljšane prijedloge za pretraživanje (samo izgled Vector)',
'vector-view-create'             => 'Započni',
'vector-view-edit'               => 'Uredi',
'vector-view-history'            => 'Vidi stare izmjene',
'vector-view-view'               => 'Čitaj',
'vector-view-viewsource'         => 'Vidi izvor',
'actions'                        => 'Radnje',
'namespaces'                     => 'Imenski prostori',
'variants'                       => 'Inačice',

'errorpagetitle'    => 'Pogreška',
'returnto'          => 'Vrati se na $1.',
'tagline'           => 'Izvor: {{SITENAME}}',
'help'              => 'Pomoć',
'search'            => 'Traži',
'searchbutton'      => 'Traži',
'go'                => 'Kreni',
'searcharticle'     => 'Kreni',
'history'           => 'Stare izmjene',
'history_short'     => 'Stare izmjene',
'updatedmarker'     => 'obnovljeno od zadnjeg posjeta',
'info_short'        => 'Informacija',
'printableversion'  => 'Verzija za ispis',
'permalink'         => 'Trajna poveznica',
'print'             => 'Ispiši',
'view'              => 'Vidi',
'edit'              => 'Uredi',
'create'            => 'Započni',
'editthispage'      => 'Uredi ovu stranicu',
'create-this-page'  => 'Započni ovu stranicu',
'delete'            => 'Izbriši',
'deletethispage'    => 'Izbriši ovu stranicu',
'undelete_short'    => 'Vrati {{PLURAL:$1|$1 uređivanje|$1 uređivanja}}',
'viewdeleted_short' => 'Prikaži $1 {{plural: $1|izbrisano uređivanje|izbrisana uređivanja|izbrisanih uređivanja}}',
'protect'           => 'Zaštiti',
'protect_change'    => 'promijeni',
'protectthispage'   => 'Zaštiti ovu stranicu',
'unprotect'         => 'Ukloni zaštitu',
'unprotectthispage' => 'Ukloni zaštitu s ove stranice',
'newpage'           => 'Nova stranica',
'talkpage'          => 'Razgovor o ovoj stranici',
'talkpagelinktext'  => 'Razgovor',
'specialpage'       => 'Posebna stranica',
'personaltools'     => 'Osobni alati',
'postcomment'       => 'Novi odlomak',
'articlepage'       => 'Vidi članak',
'talk'              => 'Razgovor',
'views'             => 'Pogledi',
'toolbox'           => 'Traka s alatima',
'userpage'          => 'Vidi suradnikovu stranicu',
'projectpage'       => 'Vidi stranicu o projektu',
'imagepage'         => 'Vidi stranicu datoteke',
'mediawikipage'     => 'Vidi stranicu za razgovor',
'templatepage'      => 'Vidi ovaj predložak',
'viewhelppage'      => 'Vidi stranicu pomoći',
'categorypage'      => 'Vidi stranicu s kategorijama',
'viewtalkpage'      => 'Vidi razgovor',
'otherlanguages'    => 'Drugi jezici',
'redirectedfrom'    => '(Preusmjereno s $1)',
'redirectpagesub'   => 'Preusmjeravanje',
'lastmodifiedat'    => 'Datum zadnje promjene na ovoj stranici: $2, $1',
'viewcount'         => 'Ova stranica je pogledana {{PLURAL:$1|$1 put|$1 puta}}.',
'protectedpage'     => 'Zaštićena stranica',
'jumpto'            => 'Skoči na:',
'jumptonavigation'  => 'orijentacija',
'jumptosearch'      => 'traži',
'view-pool-error'   => 'Ispričavamo se, poslužitelji su trenutačno preopterećeni.
Previše suradnika pokušava vidjeti ovu stranicu.
Molimo malo pričekajte  prije nego što opet pokušate pristupiti ovoj stranici.

$1',
'pool-timeout'      => "Istek vremena (''timeout'') čekajući zaključavanje",
'pool-queuefull'    => 'Red čekanja je pun',
'pool-errorunknown' => 'Nepoznata pogreška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'aboutpage'            => 'Project:O_projektu_{{SITENAME}}',
'copyright'            => 'Sadržaji se koriste u skladu s $1.',
'copyrightpage'        => '{{ns:project}}:Autorska prava',
'currentevents'        => 'Aktualno',
'currentevents-url'    => 'Project:Novosti',
'disclaimers'          => 'Odricanje od odgovornosti',
'disclaimerpage'       => 'Project:General_disclaimer',
'edithelp'             => 'Kako uređivati stranicu',
'edithelppage'         => 'Help:Kako_uređivati_stranicu',
'helppage'             => 'Help:Pomoć',
'mainpage'             => 'Glavna stranica',
'mainpage-description' => 'Glavna stranica',
'policy-url'           => 'Project:Pravila',
'portal'               => 'Portal zajednice',
'portal-url'           => 'Project:Portal zajednice',
'privacy'              => 'Zaštita privatnosti',
'privacypage'          => 'Project:Zaštita privatnosti',

'badaccess'        => 'Pogreška u ovlaštenjima',
'badaccess-group0' => 'Nije Vam dopušteno izvršiti ovaj zahvat.',
'badaccess-groups' => 'Ovaj zahvat mogu izvršiti samo suradnici iz {{PLURAL:$2|skupine|jedne od skupina}}: $1.',

'versionrequired'     => 'Potrebna inačica $1 MediaWikija',
'versionrequiredtext' => 'Za korištenje ove stranice potrebna je inačica $1 MediaWiki softvera. Pogledaj [[Special:Version|inačice]]',

'ok'                      => 'U redu',
'retrievedfrom'           => 'Dobavljeno iz "$1"',
'youhavenewmessages'      => 'Imate $1 ($2).',
'newmessageslink'         => 'nove poruke',
'newmessagesdifflink'     => 'zadnja promjena na stranici za razgovor',
'youhavenewmessagesmulti' => 'Imate nove poruke na $1',
'editsection'             => 'uredi',
'editold'                 => 'uredi',
'viewsourceold'           => 'vidi izvor',
'editlink'                => 'uredi',
'viewsourcelink'          => 'vidi izvornik',
'editsectionhint'         => 'Uređivanje odlomka: $1',
'toc'                     => 'Sadržaj',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'sakrij',
'collapsible-collapse'    => 'sklopi stablo',
'collapsible-expand'      => 'raširi stablo',
'thisisdeleted'           => 'Vidi ili vrati $1?',
'viewdeleted'             => 'Vidi $1?',
'restorelink'             => '{{PLURAL:$1|$1 pobrisanu izmjenu|$1 pobrisane izmjene|$1 pobrisanih izmjena}}',
'feedlinks'               => 'Izvor:',
'feed-invalid'            => 'Tip izvora nije valjan.',
'feed-unavailable'        => 'RSS izvori nisu dostupni',
'site-rss-feed'           => '$1 RSS izvor',
'site-atom-feed'          => '$1 Atom izvor',
'page-rss-feed'           => '"$1" RSS izvor',
'page-atom-feed'          => '"$1" Atom izvor',
'red-link-title'          => '$1 (stranica ne postoji)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Članak',
'nstab-user'      => 'Stranica suradnika',
'nstab-media'     => 'Mediji',
'nstab-special'   => 'Posebna stranica',
'nstab-project'   => 'Stranica o projektu',
'nstab-image'     => 'Slika',
'nstab-mediawiki' => 'Poruka',
'nstab-template'  => 'Predložak',
'nstab-help'      => 'Pomoć',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nema takve naredbe',
'nosuchactiontext'  => 'Navedeni URL označava nepostojeću naredbu.
Možda se pogrešno upisali URL ili slijedili pogrešnu poveznicu.
Ovo također može ukazivati na grešku u softveru kojeg koristi {{SITENAME}}.',
'nosuchspecialpage' => 'Posebna stranica ne postoji',
'nospecialpagetext' => '<strong>Takva posebna stranica ne postoji.</strong>

Za popis svih posebnih stranica posjetite [[Special:SpecialPages|ovdje]].',

# General errors
'error'                => 'Pogreška',
'databaseerror'        => 'Pogreška baze podataka',
'dberrortext'          => 'Došlo je do sintaksne pogreške u upitu bazi.
Možda se radi o grešci u softveru.
Posljednji pokušaj upita je glasio:
<blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
Baza je vratila pogrešku "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Došlo je do sintaksne pogreške s upitom bazi.
Posljednji pokušaj upita je glasio:
"$1"
iz funkcije "$2".
Baza je vratila pogrešku "$3: $4"',
'laggedslavemode'      => 'Upozorenje: na stranici se možda ne nalaze najnovije promjene.',
'readonly'             => 'Baza podataka je zaključana',
'enterlockreason'      => 'Upiši razlog zaključavanja i procjenu vremena otključavanja',
'readonlytext'         => 'Baza podataka je trenutačno zaključana, nije ju moguće uređivati ili mijenjati. Ovo je obično pokazatelj tekućeg redovitog održavanja. Nakon što se potonja privremena radnja završi, baza podataka će se vratiti u uobičajeno stanje.

Administrator koji je izvršio zaključavanje naveo je ovaj razlog: $1',
'missing-article'      => 'U bazi podataka nije pronađen tekst stranice koji je trebao biti pronađen, nazvane "$1" $2.

Ovo se najčešće događa zbog poveznice na zastarjelu usporedbu ili staru promjenu stranice koja je u međuvremenu izbrisana.

Ako to nije slučaj, možda se radi o softverskoj grešci. Molimo da u tom slučaju pošaljete poruku [[Special:ListUsers/sysop|administratoru]] navodeći URL.',
'missingarticle-rev'   => '(izmjena#: $1)',
'missingarticle-diff'  => '(razlika: $1, $2)',
'readonly_lag'         => 'Baza podataka je automatski zaključana dok se sekundarni bazni poslužitelji ne usklade s glavnim',
'internalerror'        => 'Pogreška sustava',
'internalerror_info'   => 'Interna pogreška: $1',
'fileappenderrorread'  => 'Nije se moglo pročitati "$1" tijekom dodavanja.',
'fileappenderror'      => 'Nije bilo moguće dodati "$1" u "$2".',
'filecopyerror'        => 'Ne mogu kopirati datoteku "$1" u "$2".',
'filerenameerror'      => 'Ne mogu preimenovati datoteku "$1" u "$2".',
'filedeleteerror'      => 'Ne mogu obrisati datoteku "$1".',
'directorycreateerror' => 'Nije moguće kreirati direktorij "$1".',
'filenotfound'         => 'Datoteka "$1" nije nađena.',
'fileexistserror'      => 'Ne mogu stvoriti datoteku "$1": datoteka s tim imenom već postoji',
'unexpected'           => 'Neočekivana vrijednost: "$1"="$2".',
'formerror'            => 'Pogreška: Ne mogu poslati podatke',
'badarticleerror'      => 'Ovu radnju nije moguće izvesti s tom stranicom.',
'cannotdelete'         => 'Ne može se obrisati stranica ili datoteka "$1".
Moguće je da ju je netko drugi već obrisao.',
'badtitle'             => 'Loš naslov',
'badtitletext'         => 'Navedeni naslov stranice nepravilan ili loše formirana interwiki poveznica.',
'perfcached'           => 'Sljedeći podaci su iz međuspremnika i možda nisu najsvježiji:',
'perfcachedts'         => 'Sljedeći podaci su iz međuspremnika i zadnji puta su ažurirani u $1.',
'querypage-no-updates' => 'Osvježavanje ove stranice je trenutačno onemogućeno. Nove promjene neće biti vidljive.',
'wrong_wfQuery_params' => 'Neispravni parametri poslani u wfQuery()<br />
Funkcija: $1<br />
Upit: $2',
'viewsource'           => 'Vidi izvornik',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Uređivanje je usporeno',
'actionthrottledtext'  => 'Kao anti-spam mjeru, ograničeni ste u broju ovih radnji u određenom vremenu, i trenutačno ste dosegli to ograničenje. Pokušajte opet za koju minutu.',
'protectedpagetext'    => 'Ova stranica je zaključana da bi se onemogućile izmjene.',
'viewsourcetext'       => 'Možete pogledati i kopirati izvorni sadržaj ove stranice:',
'protectedinterface'   => 'Ova stranica je zaštićena od izmjena jer sadrži tekst MediaWiki softvera.',
'editinginterface'     => "'''Upozorenje:''' Uređujete stranicu koja se rabi za prikaz teksta u sučelju softvera. Promjene učinjene na ovoj stranici će se odraziti na izgled korisničkog sučelja kod drugih suradnika. Za prijevod, razmotrite korištenje [http://translatewiki.net/wiki/Main_Page?setlang=hr translatewiki.net], projekta lokalizacije MedijeWiki.",
'sqlhidden'            => '(SQL upit sakriven)',
'cascadeprotected'     => 'Ova je stranica zaključana za uređivanja jer je uključena u {{PLURAL:$1|slijedeću stranicu|slijedeće stranice}}, koje su zaštićene "prenosivom zaštitom":
$2',
'namespaceprotected'   => "Ne možete uređivati stranice u imenskom prostoru '''$1'''.",
'customcssjsprotected' => 'Ne možete uređivati ovu stranicu zato što ona sadrži osobne postavke drugog suradnika.',
'ns-specialprotected'  => "Stranice u imenskom prostoru ''{{ns:special}}'' ne mogu se uređivati.",
'titleprotected'       => "Ovaj naslov je od kreiranja zaštitio suradnik [[User:$1|$1]], uz razlog: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Loša konfiguracija: nepoznati skener za viruse: ''$1''",
'virus-scanfailed'     => 'skeniranje neuspješno (kod $1)',
'virus-unknownscanner' => 'nepoznati antivirus:',

# Login and logout pages
'logouttext'                 => "'''Odjavili ste se.'''

Možete nastaviti s korištenjem {{SITENAME}} neprijavljeni, ili se možete ponovo [[Special:UserLogin|prijaviti]] pod istim ili drugim imenom.
Neke se stranice mogu prikazivati kao da ste još uvijek prijavljeni, sve dok ne očistite međuspremnik svog preglednika.",
'welcomecreation'            => '== Dobrodošli, $1! ==
Vaš je suradnički račun otvoren.

Ne zaboravite prilagoditi [[Special:Preferences|{{SITENAME}} postavke]].',
'yourname'                   => 'Suradničko ime',
'yourpassword'               => 'Lozinka:',
'yourpasswordagain'          => 'Ponovno upišite lozinku',
'remembermypassword'         => 'Zapamti moju lozinku na ovom računalu (najduže $1 {{PLURAL:$1|dan|dana}})',
'securelogin-stick-https'    => 'Ostani spojen na HTTPS nakon prijave',
'yourdomainname'             => 'Vaša domena',
'externaldberror'            => 'Došlo je do pogreške s vanjskom autorizacijom ili Vam nije dopušteno osvježavanje vanjskog suradničkog računa.',
'login'                      => 'Prijavi se',
'nav-login-createaccount'    => 'Prijavi se',
'loginprompt'                => 'Za prijavu na sustav {{SITENAME}} morate u pregledniku uključiti kolačiće (cookies).',
'userlogin'                  => 'Prijavi se / stvori račun',
'userloginnocreate'          => 'Prijavi se',
'logout'                     => 'Odjavi se',
'userlogout'                 => 'Odjavi se',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => "Nemate suradnički račun? '''$1'''.",
'nologinlink'                => 'Otvorite račun',
'createaccount'              => 'Otvori novi suradnički račun',
'gotaccount'                 => "Već imate suradnički račun? '''$1'''.",
'gotaccountlink'             => 'Prijavite se',
'createaccountmail'          => 'poštom',
'createaccountreason'        => 'Razlog:',
'badretype'                  => 'Unesene lozinke nisu istovjetne.',
'userexists'                 => 'Uneseno suradničko ime već je u upotrebi.
Unesite neko drugo ime.',
'loginerror'                 => 'Pogreška u prijavi',
'createaccounterror'         => 'Ne može se stvoriti račun: $1',
'nocookiesnew'               => "Suradnički račun je otvoren, ali niste uspješno prijavljeni. Naime, {{SITENAME}} koristi kolačiće (''cookies'') u procesu prijave. Isključili ste kolačiće. Molim uključite ih i pokušajte ponovo s Vašim novim imenom i lozinkom.",
'nocookieslogin'             => "{{SITENAME}} koristi kolačiće (''cookies'') u procesu prijave. Isključili ste kolačiće. Molim uključite ih i pokušajte ponovo.",
'nocookiesfornew'            => "Suradnički račun nije napravljen, jer nismo mogli potvrditi njegov izvor.
Provjerite jesu li kolačići (''cookies'') omogućeni, ponovo učitajte ovu stranicu i pokušajte opet.",
'noname'                     => 'Niste unijeli valjano suradničko ime.',
'loginsuccesstitle'          => 'Prijava uspješna',
'loginsuccess'               => 'Prijavili ste se na wiki kao "$1".',
'nosuchuser'                 => 'Ne postoji suradnik s imenom "$1".
Suradnička imena su osjetljiva na veličinu slova.
Provjerite jeste li točno upisali, ili [[Special:UserLogin/signup|otvorite novi suradnički račun]].',
'nosuchusershort'            => 'Ne postoji suradnik s imenom "<nowiki>$1</nowiki>". Provjerite Vaš unos.',
'nouserspecified'            => 'Molimo navedite suradničko ime.',
'login-userblocked'          => 'Ovaj suradnik je blokiran. Prijava nije dozvoljena.',
'wrongpassword'              => 'Lozinka koju ste unijeli nije ispravna. Pokušajte ponovno.',
'wrongpasswordempty'         => 'Niste unijeli lozinku. Pokušajte ponovno.',
'passwordtooshort'           => 'Lozinka mora sadržavati najmanje {{PLURAL:$1|1 znak|$1 znaka|$1 znakova}}.',
'password-name-match'        => 'Vaša lozinka mora biti različita od Vašeg suradničkog imena.',
'password-login-forbidden'   => 'Uporaba ovog suradničkog imena i lozinke nije dozvoljena.',
'mailmypassword'             => 'Pošalji mi novu lozinku',
'passwordremindertitle'      => '{{SITENAME}}: nova lozinka.',
'passwordremindertext'       => 'Netko je (vjerojatno Vi, s IP adrese $1) zatražio novu lozinku za projekt {{SITENAME}} ($4).
Privremena lozinka za suradnika "$2" je postavljena na "$3".
Ukoliko ste to Vi učinili, molimo Vas da se prijavite i promijenite lozinku.
Privremena lozinka vrijedi još {{PLURAL:$5|$5 dan|$5 dana}}.

Ukoliko niste zatražili novu lozinku, ili ste se sjetili stare lozinke i
više ju ne želite promijeniti, slobodno zanemarite ovu poruku i nastavite
koristiti staru lozinku.',
'noemail'                    => 'Suradnik "$1" nema zapisanu e-mail adresu.',
'noemailcreate'              => 'Morate navesti važeću e-mail adresu',
'passwordsent'               => 'Nova je lozinka poslana na e-mail adresu suradnika "$1"',
'blocked-mailpassword'       => 'Vašoj IP adresi je blokirano uređivanje stranica, a da bi se spriječila nedopuštena radnja, mogućnost zahtijevanja nove lozinke je također onemogućena.',
'eauthentsent'               => 'Na navedenu adresu poslan je e-mail s potvrdom.
Prije nego što pošaljemo daljnje poruke, molimo Vas da otvorite e-mail i slijedite u njemu sadržana uputstva kako biste potvrdili da je e-mail adresa zaista Vaša.',
'throttled-mailpassword'     => 'Već Vam je poslan e-mail za promjenu lozinke, u {{PLURAL:$1|zadnjih sat vremena|zadnja $1 sata|zadnjih $1 sati}}.
Da bi spriječili zloupotrebu, moguće je poslati samo jedan e-mail za promjenu lozinke {{PLURAL:$1|svakih sat vremena|svaka $1 sata|svakih $1 sati}}.',
'mailerror'                  => 'Pogreška pri slanju e-maila: $1',
'acct_creation_throttle_hit' => 'Posjetioci ovog wikija koji koriste Vašu IP adresu napravili su {{PLURAL:$1|1 račun|$1 računa}} u posljednjem danu, što je najveći dozvoljeni broj u tom vremenskom periodu.
Zbog toga posjetioci s ove IP adrese trenutno ne mogu otvoriti nove suradničke račune.',
'emailauthenticated'         => 'Vaša e-mail adresa je ovjerena $2 u $3.',
'emailnotauthenticated'      => 'Vaša e-mail adresa još nije ovjerena.
Ne možemo poslati e-mail ni u jednoj od sljedećih naredbi.',
'noemailprefs'               => 'Nije navedena e-mail adresa, stoga sljedeće naredbe neće raditi.',
'emailconfirmlink'           => 'Potvrdite svoju e-mail adresu',
'invalidemailaddress'        => 'Ne mogu prihvatiti e-mail adresu jer nije valjano oblikovana.
Molim unesite ispravno oblikovanu adresu ili ostavite polje praznim.',
'accountcreated'             => 'Suradnički račun otvoren',
'accountcreatedtext'         => 'Suradnički račun za $1 je otvoren.',
'createaccount-title'        => 'Otvaranje suradničkog računa za {{SITENAME}}',
'createaccount-text'         => 'Netko je stvorio suradnički račun s Vašom adresom elektronske pošte na {{SITENAME}} ($4) nazvan "$2", s lozinkom "$3". Trebali biste se prijaviti i odmah promijeniti lozinku.

Možete zanemariti ovu poruku ako je suradnički račun stvoren nenamjerno.',
'usernamehasherror'          => 'Suradničko ime ne može sadržavati znakove #',
'login-throttled'            => 'Nedavno ste se previše puta pokušali prijaviti.
Molimo Vas da pričekate prije nego što pokušate ponovo.',
'loginlanguagelabel'         => 'Jezik: $1',
'suspicious-userlogout'      => 'Vaš zahtjev za odjavu je odbijen jer to izgleda kao da je poslan preko pokvarenog preglednika ili keširanog posrednika (proxyja).',

# E-mail sending
'php-mail-error-unknown' => 'Nepoznata pogreška u PHP-mail() funkciji',

# JavaScript password checks
'password-strength'            => 'Procijenjena snaga lozinke: $1',
'password-strength-bad'        => 'LOŠA',
'password-strength-mediocre'   => 'osrednja',
'password-strength-acceptable' => 'prihvatljiva',
'password-strength-good'       => 'dobra',
'password-retype'              => 'Ponovno upišite lozinku ovdje',
'password-retype-mismatch'     => 'Lozinke se ne poklapaju',

# Password reset dialog
'resetpass'                 => 'Promijeni lozinku',
'resetpass_announce'        => 'Prijavljeni ste s privremenom lozinkom. Da završite proces mijenjanja lozinke, upišite ovdje novu lozinku:',
'resetpass_header'          => 'Promijeni lozinku računa',
'oldpassword'               => 'Stara lozinka',
'newpassword'               => 'Nova lozinka',
'retypenew'                 => 'Ponovno unesite lozinku',
'resetpass_submit'          => 'Postavite lozinku i prijavite se',
'resetpass_success'         => 'Lozinka uspješno postavljena! Prijava u tijeku...',
'resetpass_forbidden'       => 'Lozinka ne može biti promijenjena',
'resetpass-no-info'         => 'Morate biti prijavljeni da biste izravno pristupili ovoj stranici.',
'resetpass-submit-loggedin' => 'Promijeni lozinku',
'resetpass-submit-cancel'   => 'Odustani',
'resetpass-wrong-oldpass'   => 'Pogrešna privremena ili trenutačna lozinka.
Možda ste već uspješno promijenili Vašu lozinku ili ste zatražili novu privremenu lozinku.',
'resetpass-temp-password'   => 'Privremena lozinka:',

# Edit page toolbar
'bold_sample'     => 'Podebljani tekst',
'bold_tip'        => 'Podebljani tekst',
'italic_sample'   => 'Kurzivni tekst',
'italic_tip'      => 'Kurzivni tekst',
'link_sample'     => 'Tekst poveznice',
'link_tip'        => 'Unutarnja poveznica',
'extlink_sample'  => 'http://www.example.com Tekst poveznice',
'extlink_tip'     => 'Vanjska poveznica (pazi, nužan je prefiks http://)',
'headline_sample' => 'Tekst naslova',
'headline_tip'    => 'Podnaslov',
'math_sample'     => 'Ovdje unesi formulu',
'math_tip'        => 'Matematička formula (LaTeX)',
'nowiki_sample'   => 'Ovdje unesite neoblikovani tekst',
'nowiki_tip'      => 'Neoblikovani tekst',
'image_sample'    => 'Primjer.jpg',
'image_tip'       => 'Uložena slika',
'media_sample'    => 'Primjer.ogg',
'media_tip'       => 'Uloženi medij',
'sig_tip'         => 'Vaš potpis s datumom',
'hr_tip'          => 'Vodoravna crta (koristiti rijetko)',

# Edit pages
'summary'                          => 'Sažetak:',
'subject'                          => 'Predmet:',
'minoredit'                        => 'Ovo je manja promjena',
'watchthis'                        => 'Prati ovaj članak',
'savearticle'                      => 'Sačuvaj stranicu',
'preview'                          => 'Pregled kako će stranica izgledati',
'showpreview'                      => 'Prikaži kako će izgledati',
'showlivepreview'                  => 'Pregled kako će izgledati, uživo',
'showdiff'                         => 'Prikaži promjene',
'anoneditwarning'                  => "'''Upozorenje:''' Niste prijavljeni pod suradničkim imenom. Vaša IP adresa bit će zabilježena u popisu izmjena ove stranice.",
'anonpreviewwarning'               => "''Niste prijavljeni. Spremanjem će Vaše IP adrese ostati zabilježene u starim izmjenama ove stranice.''",
'missingsummary'                   => "'''Podsjetnik:''' Niste unijeli sažetak promjena. Ako ponovno kliknete na \"Sačuvaj stranicu\", Vaše će promjene biti snimljene bez sažetka.",
'missingcommenttext'               => 'Molim unesite sažetak.',
'missingcommentheader'             => "'''Podsjetnik:''' Niste napisali sažetak ovog komentara. Ako ponovno kliknete \"{{int:savearticle}}\", Vaš će komentar biti snimljen bez sažetka.",
'summary-preview'                  => 'Pregled sažetka:',
'subject-preview'                  => 'Pregled predmeta:',
'blockedtitle'                     => 'Suradnik je blokiran',
'blockedtext'                      => '\'\'\'Vaše suradničko ime ili IP adresa je blokirana\'\'\'

Blokirao Vas je $1.
Razlog blokiranja je sljedeći: \'\'$2\'\'.

* Početak blokade: $8
* Istek blokade: $6
* Ime blokiranog suradnika: $7

Možete kontaktirati $1 ili jednog od [[{{MediaWiki:Grouppage-sysop}}|administratora]] kako bi Vam pojasnili razlog blokiranja.

Primijetite da ne možete koristiti opciju "Pošalji mu e-mail" ukoliko niste upisali valjanu e-mail adresu u Vašim [[Special:Preferences|suradničkim postavkama]] i ako niste u tome onemogućeni prilikom blokiranja.

Vaša trenutačna IP adresa je $3, a oznaka bloka #$5. Molimo navedite ovaj broj kod svakog upita vezano za razlog blokiranja.',
'autoblockedtext'                  => 'Vaša IP adresa automatski je blokirana zbog toga što ju je koristio drugi suradnik, kojeg je blokirao $1.
Razlog blokiranja je sljedeći:

:\'\'$2\'\'

* Početak blokade: $8
* Blokada istječe: $6
* Ime blokiranog suradnika: $7

Možete kontaktirati $1 ili jednog od [[{{MediaWiki:Grouppage-sysop}}|administratora]] kako bi Vam pojasnili razlog blokiranja.

Primijetite da ne možete koristiti opciju "Pošalji mu e-mail" ukoliko niste upisali valjanu e-mail adresu u Vašim [[Special:Preferences|suradničkim postavkama]] i ako niste u tome onemogućeni prilikom blokiranja.

Vaša trenutačna IP adresa je $3, a oznaka bloka #$5. Molimo navedite ovaj broj kod svakog upita vezano za razlog blokiranja.',
'blockednoreason'                  => 'bez obrazloženja',
'blockedoriginalsource'            => "Izvorni tekst članka '''$1''' prikazan je ispod:",
'blockededitsource'                => "Tekst '''Vaše izmjene''' na članku '''$1''' prikazan je ispod:",
'whitelistedittitle'               => 'Za uređivanje stranice morate se prijaviti',
'whitelistedittext'                => 'Za uređivanje stranice morate se $1.',
'confirmedittext'                  => 'Morate potvrditi Vašu e-mail adresu prije nego što Vam bude omogućeno uređivanje. Molim unesite i ovjerite Vašu e-mail adresu u [[Special:Preferences|suradničkim postavkama]].',
'nosuchsectiontitle'               => 'Ne mogu pronaći odlomak',
'nosuchsectiontext'                => 'Pokušali ste uređivati odlomak koji ne postoji.
Možda je premješten ili izbrisan dok ste pregledavali stranicu.',
'loginreqtitle'                    => 'Nužna prijava',
'loginreqlink'                     => 'prijavite se',
'loginreqpagetext'                 => 'Morate se $1 da biste vidjeli ostale stranice.',
'accmailtitle'                     => 'Lozinka poslana.',
'accmailtext'                      => "Nova lozinka za [[User talk:$1|$1]] je poslana na $2.

Nakon prijave, lozinka za ovaj novi račun može biti promijenjena na stranici ''[[Special:ChangePassword|promijeni lozinku]]''",
'newarticle'                       => '(Novo)',
'newarticletext'                   => "Došli ste na stranicu koja još ne postoji.
Ako želite stvoriti tu stranicu, počnite tipkati u prozor ispod ovog teksta (pogledajte [[{{MediaWiki:Helppage}}|stranicu za pomoć]]).
Ako ste ovamo dospjeli slučajno, kliknite gumb '''natrag''' (back) u svom pregledniku.",
'anontalkpagetext'                 => "----''Ovo je stranica za razgovor s neprijavljenim suradnikom koji još nije otvorio suradnički račun ili se njime ne koristi. Zbog toga se moramo služiti brojčanom IP adresom kako bismo ga identificirali. Takvu adresu često može dijeliti više ljudi. Ako ste neprijavljeni suradnik i smatrate da su Vam upućeni irelevantni komentari, molimo Vas da [[Special:UserLogin/signup|otvorite suradnički račun]] ili [[Special:UserLogin|se prijavite]] te tako u budućnosti izbjegnete zamjenu s drugim neprijavljenim suradnicima.''",
'noarticletext'                    => 'Na ovoj stranici trenutačno nema sadržaja.
Možete [[Special:Search/{{PAGENAME}}|potražiti ovaj naslov]] na drugim stranicama,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti povezane evidencije]
ili [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti ovu stranicu]</span>.',
'noarticletext-nopermission'       => 'Možete [[Special:Search/{{PAGENAME}}|tražiti naslov ove stranice]] na drugim stranicama ili <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti povezane evidencije]</span>.',
'userpage-userdoesnotexist'        => 'Suradničko ime "$1" nije prijavljeno. Jeste li sigurni da želite stvoriti/uređivati ovu stranicu?',
'userpage-userdoesnotexist-view'   => 'Suradnički račun "$1" nije registriran.',
'blocked-notice-logextract'        => 'Ovaj suradnik je trenutačno blokiran.
Posljednja stavka evidencije blokiranja navedena je niže kao napomena:',
'clearyourcache'                   => "'''Napomena:''' Nakon snimanja trebate očistiti međuspremnik svog preglednika kako biste vidjeli promjene.
'''Mozilla / Firefox / Safari:''' držite ''Shift'' i pritisnite ''Reload'', ili pritisnite ''Ctrl-F5'' ili ''Ctrl-R'' (''Cmd-R'' na Apple Macu); '''Konqueror:''' samo pritisnite dugme ''Reload'' ili pritisnite ''F5''; '''Opera:''' očistiti cache u ''Tools → Preferences;'' '''Internet Explorer:''' držite ''Ctrl'' i pritisnite ''Refresh'', ili pritisnite ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Savjet:''' Rabite dugme \"{{int:showpreview}}\" za testiranje svog CSS prije snimanja.",
'userjsyoucanpreview'              => "'''Savjet:''' Rabite dugme \"{{int:showpreview}}\" za testiranje svog novog JavaScripta prije snimanja.",
'usercsspreview'                   => "'''Ne zaboravite: samo isprobavate/pregledavate svoj suradnički CSS. Još nije snimljen!'''",
'userjspreview'                    => "'''Ne zaboravite: samo isprobavate/pregledavate svoj suradnički JavaScript, i da još nije snimljen!'''",
'sitecsspreview'                   => "'''Ne zaboravite ovo je samo pregled ovog CSS-a.'''
'''Još uvijek nije sačuvan!'''",
'sitejspreview'                    => "'''Ne zaboravite ovo je samo pregled JavaScript kôda.'''
'''Još uvijek nije sačuvan!'''",
'userinvalidcssjstitle'            => "'''Upozorenje:''' Nema sučelja pod imenom \"\$1\". Ne zaboravite da imena stranica s .css and .js kodom počinju malim slovom, npr. {{ns:user}}:Mate/vector.css, a ne {{ns:user}}:Mate/Vector.css.",
'updated'                          => '(Ažurirano)',
'note'                             => "'''Napomena:'''",
'previewnote'                      => "'''Ne zaboravite da je ovo samo pregled kako će stranica izgledati i da stranica još nije snimljena!'''",
'previewconflict'                  => 'Ovaj pregled odražava stanje u gornjem polju za unos koje će biti sačuvano
ako pritisnete "Sačuvaj stranicu".',
'session_fail_preview'             => "'''Ispričavamo se! Nismo mogli obraditi Vašu izmjenu zbog gubitka podataka o prijavi.
Molimo pokušajte ponovno. Ako i dalje ne bude uspijevalo, pokušajte se [[Special:UserLogout|odjaviti]] i ponovno prijaviti.'''",
'session_fail_preview_html'        => "'''Oprostite! Pretpregled nije moguć jer je ''session'' istekao.'''

''Budući da je na ovom wikiju ({{SITENAME}}) omogućen unos HTML oznaka (tagova), pretpregled je skriven kao mjera predstrožnosti protiv napada pomoću JavaScripta.''

'''Ukoliko ste pokušali vidjeti kako stranica izgleda, molimo probajte opet. Ako ne uspije, [[Special:UserLogout|odjavite se]] i prijavite se ponovo.'''",
'token_suffix_mismatch'            => "'''Vaše uređivanje je odbačeno jer je Vaš web preglednik ubacio znak/znakove interpunkcije u token uređivanja.'''
Stoga je uređivanje odbačeno da se spriječi uništavanje teksta stranice.
To se ponekad događa kad rabite neispravan web-baziran anonimni posrednik (proxy).",
'editing'                          => 'Uređujete $1',
'editingsection'                   => 'Uređujete $1 (odlomak)',
'editingcomment'                   => 'Uređujete $1 (novi odlomak)',
'editconflict'                     => 'Istovremeno uređivanje: $1',
'explainconflict'                  => "Netko je u međuvremenu promijenio stranicu.
Gornje polje sadrži sadašnji tekst stranice.
U donjem polju prikazane su Vaše promjene.
Morat ćete unijeti Vaše promjene u sadašnji tekst.
'''Samo''' će tekst u gornjem polju biti sačuvan kad pritisnete \"{{int:savearticle}}\".",
'yourtext'                         => 'Vaš tekst',
'storedversion'                    => 'Pohranjena inačica',
'nonunicodebrowser'                => "'''UPOZORENJE: Vaš preglednik ne podržava Unicode zapis znakova, molimo promijenite ga prije sljedećeg uređivanja članaka.'''",
'editingold'                       => "'''UPOZORENJE: Uređujete stariju inačicu
ove stranice. Ako je sačuvate, sve će promjene učinjene nakon ove inačice biti izgubljene.'''",
'yourdiff'                         => 'Razlike',
'copyrightwarning'                 => "Molimo uočite da se svi doprinosi {{SITENAME}} smatraju objavljenima pod uvjetima $2 (vidi $1 za detalje). Ako ne želite da se Vaše pisanje nemilosrdno uređuje i slobodno raspačava, nemojte ga ovamo slati.<br />
Također nam obećavate da ste ovo sami napisali, ili da ste to prepisali iz nečeg što je u javnom vlasništvu ili pod sličnom slobodnom licencijom.
'''NE POSTAVLJAJTE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOPUŠTENJA!'''",
'copyrightwarning2'                => "Molimo uočite da svi suradnici mogu mijenjati sve doprinose na {{SITENAME}}. Ako ne želite da se Vaše pisanje nemilosrdno uređuje, nemojte ga slati ovdje.<br /> Također nam obećavate da ste ovo sami napisali, ili da ste to prepisali iz nečeg što je u javnom vlasništvu ili pod sličnom slobodnom licencijom (vidi $1 za detalje). '''NE POSTAVLJAJTE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOPUŠTENJA!'''",
'longpageerror'                    => "'''GREŠKA: Tekst koji ste unijeli dug je $1 kilobajta, što je više od maksimalnih $2 kilobajta. Nije ga moguće snimiti.'''",
'readonlywarning'                  => "'''UPOZORENJE: Baza podataka je zaključana zbog održavanja, pa trenutačno ne možete sačuvati svoje
promjene. Najbolje je da kopirate i zaljepite tekst u tekstualnu datoteku te je snimite za kasnije.'''

Administrator je zaključao bazu iz razloga: $1",
'protectedpagewarning'             => "'''UPOZORENJE: Ova stranica je zaključana i mogu je uređivati samo suradnici s administratorskim pravima.'''
Posljednja stavka u evidenciji navedena je niže kao napomena:",
'semiprotectedpagewarning'         => "'''Napomena:''' Ova stranica je zaključana tako da je mogu uređivati samo prijavljeni suradnici.
Posljednja stavka u evidenciji navedena je niže kao napomena:",
'cascadeprotectedwarning'          => "'''UPOZORENJE:''' Ova stranica je zaključana i mogu je uređivati samo suradnici s administratorskim pravima, jer je uključena u {{PLURAL:\$1|slijedeću stranicu|slijedeće stranice}} koje su zaštićene \"prenosivom\" zaštitom:",
'titleprotectedwarning'            => "'''UPOZORENJE:  Ova stranica je zaključana i samo je suradnici s [[Special:ListGroupRights|dodatnim pravima]] mogu stvoriti.'''
Posljednja stavka u evidenciji navedena je niže kao napomena:",
'templatesused'                    => '{{PLURAL:$1|Predložak koji se rabi|Predlošci koji se rabe}} na ovoj stranici:',
'templatesusedpreview'             => '{{PLURAL:$1|Predložak koji se rabi|Predlošci koji se rabe}} u ovom predpregledu:',
'templatesusedsection'             => '{{PLURAL:$1|Predložak koji se rabi|Predlošci koji se rabe}} u ovom odjeljku:',
'template-protected'               => '(zaštićen)',
'template-semiprotected'           => '(djelomično zaštićen)',
'hiddencategories'                 => 'Ova stranica je član {{PLURAL:$1|1 skrivene kategorija|$1 skrivene kategorije|$1 skrivenih kategorija}}:',
'nocreatetitle'                    => 'Otvaranje novih stranica ograničeno',
'nocreatetext'                     => 'Na ovom je projektu ograničeno otvaranje novih stranica.
Možete se vratiti i uređivati već postojeće stranice ili se [[Special:UserLogin|prijaviti ili otvoriti suradnički račun]].',
'nocreate-loggedin'                => 'Nemate ovlasti za stvaranje novih stranica.',
'sectioneditnotsupported-title'    => 'Uređivanje odjeljka nije podržano',
'sectioneditnotsupported-text'     => 'Uređivanje odjeljka nije podržano na ovoj stranici',
'permissionserrors'                => 'Pogreška u pravima',
'permissionserrorstext'            => 'Nemate ovlasti za tu radnju iz sljedećih {{PLURAL:$1|razlog|razloga}}:',
'permissionserrorstext-withaction' => 'Nemate dopuštenje za $2, iz {{PLURAL:$1|razloga|razloga}}:',
'recreate-moveddeleted-warn'       => "'''Upozorenje: Ponovno stvarate stranicu koja je prethodno bila izbrisana.'''

Razmotrite je li prikladno nastaviti s uređivanje ove stranice.
Za Vašu informaciju slijedi evidencija brisanja i premještanja ove stranice:",
'moveddeleted-notice'              => 'Ova stranica je bila izbrisana.
Evidencija brisanja i evidencija premještanja za ovu stranicu je prikazana niže.',
'log-fulllog'                      => 'Prikaži cijelu evidenciju',
'edit-hook-aborted'                => 'Uređivanje prekinuto kukom.
Razlog nije ponuđen.',
'edit-gone-missing'                => 'Stranica nije spremljena.
Čini se kako je obrisana.',
'edit-conflict'                    => 'Sukob uređivanja.',
'edit-no-change'                   => 'Vaše uređivanje je zanemareno, jer nikakva promjena sadržaja nije napravljena.',
'edit-already-exists'              => 'Neuspješno stvaranje nove stranice.
Stranica već postoji.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Upozorenje: Ova stranica sadrži previše opterećujućih poziva parserskih funkcija

Trebala bi imati manje od $2 {{PLURAL:$2|poziva|poziva}}, sada ima {{PLURAL:$1|$1 poziv|$1 poziva}}.',
'expensive-parserfunction-category'       => 'Stranice s previše poziva opterećujućih parserskih funkcija',
'post-expand-template-inclusion-warning'  => 'Upozorenje: Veličina uključenih predložaka je prevelika.
Neki predlošci neće biti uključeni.',
'post-expand-template-inclusion-category' => 'Stranice gdje su uključeni predlošci preveliki',
'post-expand-template-argument-warning'   => 'Upozorenje: Ova stranica sadrži najmanje jedan argument predložaka koji ima preveliko proširenje. Ovi su argumenti izostavljeni.',
'post-expand-template-argument-category'  => 'Stranice koje sadrže izostavljene argumente za predloške',
'parser-template-loop-warning'            => 'Otkrivena petlja predloška: [[$1]]',
'parser-template-recursion-depth-warning' => 'Dubina rekurzije predloška je izvan granice ($1)',
'language-converter-depth-warning'        => 'Prekoračena granica dubine jezičnog pretvarača ($1)',

# "Undo" feature
'undo-success' => 'Izmjena je uklonjena (tekst u okviru ispod ne sadrži zadnju izmjenu). Molim sačuvajte stranicu (provjerite sažetak).',
'undo-failure' => 'Ova izmjena ne može biti uklonjena zbog postojanja međuinačica.',
'undo-norev'   => 'Izmjena nije mogla biti uklonjena jer ne postoji ili je obrisana.',
'undo-summary' => 'Uklanjanje izmjene $1 što ju je unio/unijela [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nije moguće stvoriti suradnički račun',
'cantcreateaccount-text' => "Otvaranje suradničkog računa ove IP adrese ('''$1''') blokirao/la je [[User:$3|$3]].

Razlog koji je dao/la $3 je ''$2''",

# History pages
'viewpagelogs'           => 'Vidi evidencije za ovu stranicu',
'nohistory'              => 'Ova stranica nema starijih izmjena.',
'currentrev'             => 'Trenutačna inačica',
'currentrev-asof'        => 'Trenutačna izmjena od $1',
'revisionasof'           => 'Inačica od $1',
'revision-info'          => 'Inačica od $1 koju je unio/unijela $2',
'previousrevision'       => '←Starija inačica',
'nextrevision'           => 'Novija inačica→',
'currentrevisionlink'    => 'vidi trenutačnu inačicu',
'cur'                    => 'sad',
'next'                   => 'sljed',
'last'                   => 'pret',
'page_first'             => 'prva',
'page_last'              => 'zadnja',
'histlegend'             => 'Uputa: (sad) = razlika od trenutačne inačice,
(pret) = razlika od prethodne inačice, m = manja promjena',
'history-fieldset-title' => 'Pretraži povijest',
'history-show-deleted'   => 'Samo izbrisane',
'histfirst'              => 'Najstarije',
'histlast'               => 'Najnovije',
'historysize'            => '({{PLURAL:$1|$1 bajt|$1 bajta|$1 bajtova}})',
'historyempty'           => '(prazna stranica)',

# Revision feed
'history-feed-title'          => 'Povijest promjena',
'history-feed-description'    => 'Povijest promjena ove stranice na wikiju',
'history-feed-item-nocomment' => '$1 u (test) $2',
'history-feed-empty'          => 'Tražena stranica ne postoji.
Stranica je vjerojatno prethodno izbrisana s wikija, ili preimenovana.
Pokušajte [[Special:Search|pretražiti]] važnije nove stranice na wikiju.',

# Revision deletion
'rev-deleted-comment'         => '(komentar uklonjen)',
'rev-deleted-user'            => '(suradničko ime uklonjeno)',
'rev-deleted-event'           => '(zapis uklonjen)',
'rev-deleted-user-contribs'   => '[suradničko ime ili IP adresa uklonjeni - izmjena skrivena u doprinosima]',
'rev-deleted-text-permission' => "Ova izmjena je '''izbrisana'''.
Detalji se vjerojatno nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja].",
'rev-deleted-text-unhide'     => "Ova izmjena je '''izbrisana.'''
Detalji se vjerojatno nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja]. Kao administrator, možete i dalje [$1 vidjeti ovu izmjenu] ukoliko želite nastaviti.",
'rev-suppressed-text-unhide'  => "Ova izmjena stranice je '''skrivena'''. Vjerojatno postoji više podataka u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} evidenciji skrivanja].
Kao administrator možete [$1 vidjeti ovu izmjenu] ukoliko želite nastaviti.",
'rev-deleted-text-view'       => "Ova izmjena je '''izbrisana'''.
Kao administrator na ovom projektu možete ju vidjeti; detalji se vjerojatno nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja].",
'rev-suppressed-text-view'    => "Ova izmjena stranice je '''skrivena'''.
Kao administrator možete ju pregledati; vjerojatno postoji više podataka u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} evidenciji skrivanja].",
'rev-deleted-no-diff'         => "Ne možete vidjeti ovu inačicu zbog toga što je jedna od izmjena '''izbrisana'''.
Možda postoji više informacija u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja].",
'rev-suppressed-no-diff'      => "Ne možete vidjeti ove razlike jer je jedna od revizija '''obrisana'''.",
'rev-deleted-unhide-diff'     => "Jedna od inačica ove izmjene je '''izbrisana'''.
Detalji se vjerojatno nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja]. Kao administrator, možete i dalje [$1 vidjeti ovu izmjenu] ukoliko želite nastaviti.",
'rev-suppressed-unhide-diff'  => "Jedna od revizija ove razlike je '''uklonjena'''.
Postoji mnogo detalja u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} zapisniku uklanjanja].
Kao administrator i dalje možete [$1 vidjeti ove razlike] ako želite da nastavite.",
'rev-deleted-diff-view'       => "Jedna od izmjena je '''izbrisana'''.
Kao administrator možete ju vidjeti; detalji se vjerojatno nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} evidenciji brisanja].",
'rev-suppressed-diff-view'    => "Jedna od izmjena stranice je '''skrivena'''.
Kao administrator možete ju pregledati; vjerojatno postoji više podataka u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} evidenciji skrivanja].",
'rev-delundel'                => 'pokaži/skrij',
'rev-showdeleted'             => 'prikaži',
'revisiondelete'              => 'Izbriši/vrati izmjene',
'revdelete-nooldid-title'     => 'Nema tražene izmjene',
'revdelete-nooldid-text'      => 'Niste naveli željenu izmjenu (izmjene), željena izmjena ne postoji, ili  pokušavate sakriti trenutačnu izmjenu.',
'revdelete-nologtype-title'   => 'Nije zadana vrsta evidencije',
'revdelete-nologtype-text'    => 'Niste izabrali vrstu evidencije nad kojom se vrši ova radnja.',
'revdelete-nologid-title'     => 'Nevaljani zapis u evidenciji',
'revdelete-nologid-text'      => 'Niste naveli ciljnu evidenciju ili navedeni zapis ne postoji.',
'revdelete-no-file'           => 'Navedena datoteka ne postoji.',
'revdelete-show-file-confirm' => 'Jeste li sigurni da želite pregledati izbrisanu inačicu datoteke "<nowiki>$1</nowiki>" od $2 u $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Odabrana izmjena|Odabrane izmjene|Odabrane izmjene}} stranice [[$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Odabrani zapis u evidenciji|Odabrani zapisi u evidenciji}}:'''",
'revdelete-text'              => "'''Obrisane će se izmjene i dalje nalaziti u javnom popisu izmjena, ali njihov sadržaj neće biti dostupan javnosti.'''
Drugi administratori ovoga projekta ({{SITENAME}}) moći će i dalje pristupiti skrivenom sadržaju i vratiti ga u javni pristup putem ovog sučelja, osim ako operateri na projektu nisu postavili dodatna ograničenja.",
'revdelete-confirm'           => 'Molimo potvrdite da namjeravate ovo učiniti, da razumijete posljedice i da to činite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'revdelete-suppress-text'     => "Sklanjanje uređivanja treba raditi '''iznimno''' u slijedećih par slučajeva:
* Privatne informacije neprilične javnom mediju tipa
*: ''kućna adresa i broj telefona, JMBG ili OIB, itd.''",
'revdelete-legend'            => 'Postavi ograničenja na izmjenu:',
'revdelete-hide-text'         => 'Sakrij tekst izmjene',
'revdelete-hide-image'        => 'Sakrij sadržaj datoteke (sakrij sliku)',
'revdelete-hide-name'         => 'Sakrij uređivanje i njegov predmet',
'revdelete-hide-comment'      => 'Sakrij komentar (sažetak)',
'revdelete-hide-user'         => 'Sakrij suradnikovo ime/IP adresu',
'revdelete-hide-restricted'   => 'Postavi ograničenja i za administratore kao i za ostale suradnike',
'revdelete-radio-same'        => '(ne mijenjaj)',
'revdelete-radio-set'         => 'Da',
'revdelete-radio-unset'       => 'Ne',
'revdelete-suppress'          => 'Sakrij podatke od administratora i ostalih suradnika',
'revdelete-unsuppress'        => 'Ukloni ograničenja na vraćenim izmjenama',
'revdelete-log'               => 'Razlog:',
'revdelete-submit'            => 'Primijeni na {{PLURAL:$1|odabranu inačicu|odabrane inačice}}',
'revdelete-logentry'          => 'promijenjena vidljivost izmjene za [[$1]]',
'logdelete-logentry'          => 'promijenjena vidljivost uređivanja [[$1]]',
'revdelete-success'           => "'''Vidljivost izmjene uspješno ažurirana.'''",
'revdelete-failure'           => "'''Vidljivost inačice nije mogla biti ažurirana:'''
$1",
'logdelete-success'           => "'''Vidljivost uređivanja uspješno postavljena.'''",
'logdelete-failure'           => "'''Vidljivost evidencije ne može biti postavljena:'''
$1",
'revdel-restore'              => 'Promijeni dostupnost',
'revdel-restore-deleted'      => 'izbrisane izmjene',
'revdel-restore-visible'      => 'vidljive izmjene',
'pagehist'                    => 'Povijest stranice',
'deletedhist'                 => 'Obrisana povijest',
'revdelete-content'           => 'sadržaj',
'revdelete-summary'           => 'sažetak',
'revdelete-uname'             => 'suradničko ime',
'revdelete-restricted'        => 'primijenjeno ograničenje za administratore',
'revdelete-unrestricted'      => 'uklonjeno ograničenje za administratore',
'revdelete-hid'               => 'sakrij $1',
'revdelete-unhid'             => 'otkrij $1',
'revdelete-log-message'       => '$1 za $2 {{PLURAL:$2|izmjenu|izmjene}}',
'logdelete-log-message'       => '$1 za $2 {{PLURAL:$2|slučaj|slučaja}}',
'revdelete-hide-current'      => 'Greška u skrivanju stavke datirane $2, $1: ovo je trenutačna inačica. Ne može biti skrivena.',
'revdelete-show-no-access'    => 'Greška u prikazivanju stavke od $2, $1: ova stavka je označena kao "ograničeno".
Nemate pristup do nje.',
'revdelete-modify-no-access'  => 'Greška pri izmjeni stavke od $2, $1: ova stavka je označena kao "ograničeno".
Nemate pristup do nje.',
'revdelete-modify-missing'    => 'Greška pri izmjeni izmjene broj $1: nedostaje u bazi!',
'revdelete-no-change'         => "'''Upozorenje:''' stavka od $2, $1 već ima tražene postavke vidljivosti.",
'revdelete-concurrent-change' => 'Greška pri izmjeni stavke od $2, $1: izgleda da je njen status promijenio netko drugi dok ste ju pokušavali mijenjati.
Provjerite evidencije.',
'revdelete-only-restricted'   => 'Greška pri skrivanju stavke od dana $2, $1: ne možete ukloniti stavke od pregledavanja administratora bez da odaberete neku od drugih mogućnosti vidljivosti.',
'revdelete-reason-dropdown'   => '*Uobičajeni razlozi brisanja
** Kršenje autorskih prava
** Neprimjereni osobni podaci',
'revdelete-otherreason'       => 'Drugi/dodatni razlog:',
'revdelete-reasonotherlist'   => 'Drugi razlog',
'revdelete-edit-reasonlist'   => 'Uredi razloge za brisanje',
'revdelete-offender'          => 'Autor revizije:',

# Suppression log
'suppressionlog'     => 'Evidencije sakrivanja',
'suppressionlogtext' => 'Slijedi popis brisanja i blokiranja koji uključuje sadržaj skriven za administratore.<br />
Vidi [[Special:IPBlockList|Popis blokiranih IP adresa]] za popis trenutačno aktivnih blokiranih adresa.',

# Revision move
'moverevlogentry'              => 'premjestio {{PLURAL:$3|jednu inačicu|$3 inačice}} iz $1 u $2',
'revisionmove'                 => 'Premjesti inačice iz "$1"',
'revmove-explain'              => 'Sljedeće inačice bit će premještene iz $1 u navedenu ciljnu stranicu i spojene u njenu povijest izmjena. Ako ciljna stranica ne postoji, bit će stvorena.',
'revmove-legend'               => 'Postavi odredišnu stranicu i sažetak',
'revmove-submit'               => 'Premjesti inačice u odabranu stranicu',
'revisionmoveselectedversions' => 'Premjesti odabrane inačice',
'revmove-reasonfield'          => 'Razlog:',
'revmove-titlefield'           => 'Ciljna stranica:',
'revmove-badparam-title'       => 'Neispravni parametri',
'revmove-badparam'             => 'Vaš zahtjev sadrži nedopuštene parametre, ili nedovoljan broj parametara. Vratite se na prethodnu stranicu i pokušajte ponovo.',
'revmove-norevisions-title'    => 'Nevaljana ciljna inačica',
'revmove-norevisions'          => 'Niste naveli jednu ili više ciljnih inačica potrebnih za obavljanje ove funkcije, ili odabrana inačica ne postoji.',
'revmove-nullmove-title'       => 'Loš naslov',
'revmove-nullmove'             => 'Odredišna stranica ne može biti ista kao izvorna stranica. Vratite se na prethodnu stranicu i odaberite ime koje nije "$1".',
'revmove-success-existing'     => '{{PLURAL:$1|Jedna inačica iz [[$2]] je premještena|$1 inačice iz [[$2]] su premještene|$1 inačica iz [[$2]] su premještene}} u postojeću stranicu [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Jedna inačica iz [[$2]] je premještena|$1 inačice iz [[$2]] su premještene|$1 inačica iz [[$2]] su premještene}} u novostvorenu stranicu [[$3]].',

# History merging
'mergehistory'                     => 'Spoji povijesti starih izmjena stranice',
'mergehistory-header'              => 'Na ovoj stranici spajate povijest jedne stranice u drugu (noviju) stranicu.
Budite sigurni da ta promjena čuva kontinuitet stranice.',
'mergehistory-box'                 => 'Spoji povijesti starih izmjena dvije stranice:',
'mergehistory-from'                => 'Izvorna stranica:',
'mergehistory-into'                => 'Ciljna stranica:',
'mergehistory-list'                => 'Spojiva povijest uređivanja',
'mergehistory-merge'               => 'Sljedeće promjene stranice [[:$1|$1]] mogu biti spojene u [[:$2|$2]].
Rabite stupac s radio gumbima za spajanje samo određenih promjena.
Primijetite da uporaba navigacijskih poveznica resetira Vaše izbore u stupcu.',
'mergehistory-go'                  => 'Pokaži spojivu povijest uređivanja',
'mergehistory-submit'              => 'Spoji povijesti uređivanja stranica',
'mergehistory-empty'               => 'Nema spojivih promjena (spajanje nije moguće).',
'mergehistory-success'             => '$3 {{PLURAL:$3|izmjena|izmjene}} stranice [[:$1|$1]] uspješno {{PLURAL:$3|spojena|spojene}} u povijest stranice [[:$2|$2]].',
'mergehistory-fail'                => 'Nemoguće spojiti povijest stranica, molimo provjerite stranice i vremenske parametre.',
'mergehistory-no-source'           => 'Izvorna stranica $1 ne postoji.',
'mergehistory-no-destination'      => 'Ciljna stranica $1 ne postoji.',
'mergehistory-invalid-source'      => 'Izvorna stranica mora imati valjani naziv.',
'mergehistory-invalid-destination' => 'Ciljna stranica mora imati valjani naziv.',
'mergehistory-autocomment'         => 'Stranica [[:$1]] je spojena u [[:$2]]',
'mergehistory-comment'             => 'Stranica [[:$1]] je spojena u [[:$2]]: $3',
'mergehistory-same-destination'    => 'Izvorna i ciljana stranica ne mogu biti iste',
'mergehistory-reason'              => 'Razlog:',

# Merge log
'mergelog'           => 'Evidencija spajanja povijesti stranica',
'pagemerge-logentry' => 'spojeno [[$1]] u [[$2]] (promjene do $3)',
'revertmerge'        => 'Razdvoji',
'mergelogpagetext'   => 'Slijedi popis posljednjih spajanja povijesti stranica.',

# Diffs
'history-title'            => 'Povijest izmjena stranice "$1"',
'difference'               => '(Usporedba među inačicama)',
'difference-multipage'     => '(Razlika između stranica)',
'lineno'                   => 'Redak $1:',
'compareselectedversions'  => 'Usporedi odabrane inačice',
'showhideselectedversions' => 'Otkrij/sakrij odabrane izmjene',
'editundo'                 => 'ukloni ovu izmjenu',
'diff-multi'               => '({{PLURAL:$1|Nije prikazana jedna međuinačica|Nisu prikazane $1 međuinačice|Nije prikazano $1 međuinačica}} {{PLURAL:$2|jednog|$2|$2}} suradnika)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Nije prikazana jedna međuinačica|Nisu prikazane $1 međuinačice|Nije prikazano $1 međuinačica}} više od {{PLURAL:$2|jednog|$2|$2}} suradnika)',

# Search results
'searchresults'                    => 'Rezultati pretrage',
'searchresults-title'              => 'Rezultati traženja za "$1"',
'searchresulttext'                 => 'Za više obavijesti o pretraživanju projekta {{SITENAME}} vidi [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Tražili ste \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sve stranice koje počinju sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje povezuju na "$1"]])',
'searchsubtitleinvalid'            => 'Za upit "$1"',
'toomanymatches'                   => 'Preveliki broj rezultata, molimo probajte drukčiji upit',
'titlematches'                     => 'Pronađene stranice prema naslovu',
'notitlematches'                   => 'Nema pronađenih stranica prema naslovu',
'textmatches'                      => 'Pronađene stranice prema tekstu članka',
'notextmatches'                    => 'Nema pronađenih stranica prema tekstu članka',
'prevn'                            => 'prethodnih {{PLURAL:$1|$1}}',
'nextn'                            => 'sljedećih {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|prethodni rezultat|prethodna rezultata|prethodnih rezultata}}',
'nextn-title'                      => '$1 {{PLURAL:$1|sljedeći rezultat|sljedeća rezultata|sljedećih rezultata}}',
'shown-title'                      => 'Prikaži $1 {{PLURAL:$1|rezultat|rezultata|rezultata}} po stranici',
'viewprevnext'                     => 'Vidi ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Mogućnosti pretraživanja',
'searchmenu-exists'                => "* Stranica '''[[$1]]'''",
'searchmenu-new'                   => "'''Stvori stranicu \"[[:\$1]]\" na ovoj wiki!'''",
'searchhelp-url'                   => 'Help:Pomoć',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Pretraži stranice s ovim prefiksom]]',
'searchprofile-articles'           => 'Stranice sa sadržajem',
'searchprofile-project'            => 'Pomoć i stranice projekta',
'searchprofile-images'             => 'Multimedija',
'searchprofile-everything'         => 'Sve',
'searchprofile-advanced'           => 'Napredno',
'searchprofile-articles-tooltip'   => 'Traži u $1',
'searchprofile-project-tooltip'    => 'Traži u $1',
'searchprofile-images-tooltip'     => 'Traži datoteke',
'searchprofile-everything-tooltip' => 'Pretraži sav sadržaj (uključujući i stranice za razgovor)',
'searchprofile-advanced-tooltip'   => 'Traži u zadanom imenskom prostoru',
'search-result-size'               => '$1 ({{PLURAL:$2|1 riječ|$2 riječi}})',
'search-result-category-size'      => '{{PLURAL:$1|1 član|$1 člana|$1 članova}} ({{PLURAL:$2|1 potkategorija|$2 potkategorije|$2 potkategorija}}, {{PLURAL:$3|1 datoteka|$3 datoteke|$3 datoteka}})',
'search-result-score'              => 'Povezanost: $1%',
'search-redirect'                  => '(preusmjeravanje $1)',
'search-section'                   => '(odlomak $1)',
'search-suggest'                   => 'Mislili ste: $1',
'search-interwiki-caption'         => 'Sestrinski projekti',
'search-interwiki-default'         => '$1 rezultati:',
'search-interwiki-more'            => '(više)',
'search-mwsuggest-enabled'         => 's prijedlozima',
'search-mwsuggest-disabled'        => 'nema prijedloga',
'search-relatedarticle'            => 'Povezano',
'mwsuggest-disable'                => 'Isključi AJAX prijedloge',
'searcheverything-enable'          => 'Traži u svim imenskim prostorima',
'searchrelated'                    => 'povezano',
'searchall'                        => 'sve',
'showingresults'                   => "Dolje {{PLURAL:$1|je prikazan '''$1''' rezultat|su prikazana '''$1''' rezultata|je prikazano '''$1''' rezultata}}, počevši od '''$2'''.",
'showingresultsnum'                => "Dolje {{PLURAL:$3|je prikazan '''$3''' rezultat|su prikazana '''$3''' rezultata|je prikazano '''$3''' rezultata}}, počevši s brojem '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultat '''$1''' od '''$3'''|Rezultati '''$1 - $2''' od '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Napomena''': Glavne postavke pretražuju samo određene imenske prostore. Ako želite pretraživati sve, dodajte prefiks '''all:''' ispred traženog sadržaja (ovo uključuje stranice za razgovor, predloške i sl.), ili koristite prefiks željenog imenskog prostora.",
'search-nonefound'                 => 'Ne postoje rezultati koji se podudaraju s upitom.',
'powersearch'                      => 'Traženje',
'powersearch-legend'               => 'Napredno pretraživanje',
'powersearch-ns'                   => 'Traži u imenskom prostoru:',
'powersearch-redir'                => 'Prikaži preusmjerenja',
'powersearch-field'                => 'Traži za',
'powersearch-togglelabel'          => 'Uključi:',
'powersearch-toggleall'            => 'Sve',
'powersearch-togglenone'           => 'Ništa',
'search-external'                  => 'Vanjski pretraživač',
'searchdisabled'                   => '<p>Oprostite! Pretraga po cjelokupnoj bazi je zbog bržeg rada projekta {{SITENAME}} trenutačno onemogućena. Možete se poslužiti tražilicom Google.</p>',

# Quickbar
'qbsettings'               => 'Traka',
'qbsettings-none'          => 'Bez',
'qbsettings-fixedleft'     => 'Lijevo nepomično',
'qbsettings-fixedright'    => 'Desno nepomično',
'qbsettings-floatingleft'  => 'Lijevo leteće',
'qbsettings-floatingright' => 'Desno leteće',

# Preferences page
'preferences'                   => 'Postavke',
'mypreferences'                 => 'Moje postavke',
'prefs-edits'                   => 'Broj uređivanja:',
'prefsnologin'                  => 'Niste prijavljeni',
'prefsnologintext'              => 'Morate biti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prijavljeni]</span> za podešavanje suradničkih postavki.',
'changepassword'                => 'Promjena lozinke',
'prefs-skin'                    => 'Izgled',
'skin-preview'                  => 'Pregled',
'prefs-math'                    => 'Prikaz matematičkih formula',
'datedefault'                   => 'Nemoj postaviti',
'prefs-datetime'                => 'Datum i vrijeme',
'prefs-personal'                => 'Podaci o suradniku',
'prefs-rc'                      => 'Nedavne promjene i kratki članci',
'prefs-watchlist'               => 'Praćene stranice',
'prefs-watchlist-days'          => 'Broj dana koji će se prikazati na popisu praćenja:',
'prefs-watchlist-days-max'      => '(maksimalno 7 dana)',
'prefs-watchlist-edits'         => 'Broj uređivanja koji će se prikazati na proširenom popisu praćenja:',
'prefs-watchlist-edits-max'     => '(maksimalni broj: 1000)',
'prefs-watchlist-token'         => 'Token popisa praćenja:',
'prefs-misc'                    => 'Razno',
'prefs-resetpass'               => 'Promijeni lozinku',
'prefs-email'                   => 'Mogućnosti e-maila',
'prefs-rendering'               => 'Izgled',
'saveprefs'                     => 'Spremi',
'resetprefs'                    => 'Vrati na prvotne postavke',
'restoreprefs'                  => 'Vrati sve postavke na prvotno zadane',
'prefs-editing'                 => 'Širina okvira za uređivanje',
'prefs-edit-boxsize'            => 'Veličina prozora za uređivanje.',
'rows'                          => 'Redova',
'columns'                       => 'Stupaca',
'searchresultshead'             => 'Prikaz rezultata pretrage',
'resultsperpage'                => 'Koliko pogodaka na jednoj stranici',
'contextlines'                  => 'Koliko redova teksta po pogotku',
'contextchars'                  => 'Koliko znakova po retku',
'stub-threshold'                => 'Prag za formatiranje poput <a href="#" class="stub">poveznice mrve</a>:',
'stub-threshold-disabled'       => 'Onemogućeno',
'recentchangesdays'             => 'Broj dana prikazanih u nedavnim promjenama:',
'recentchangesdays-max'         => '(maksimalno $1 {{PLURAL:$1|dan|dana}})',
'recentchangescount'            => 'Broj izmjena za prikaz kao zadano:',
'prefs-help-recentchangescount' => 'Ovo uključuje nedavne promjene, stare izmjene, i evidencije.',
'prefs-help-watchlist-token'    => 'Popunjavanjem ovog polja tajnim ključem generirat će se RSS redak za Vaš popis praćenja. Svatko tko zna ključ moći će čitati Vaš popis praćenih stranica, slijedom toga odaberite sigurnu vrijednost. Ovdje su nasumično generirane vrijednosti koje možete rabiti: $1',
'savedprefs'                    => 'Vaše postavke su sačuvane.',
'timezonelegend'                => 'Vremenska zona:',
'localtime'                     => 'Lokalno vrijeme:',
'timezoneuseserverdefault'      => 'Koristi vrijeme na poslužitelji',
'timezoneuseoffset'             => 'Drugo (odredite razliku)',
'timezoneoffset'                => 'Razlika¹:',
'servertime'                    => 'Vrijeme na poslužitelju:',
'guesstimezone'                 => 'Vrijeme dobiveno od preglednika',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantski ocean',
'timezoneregion-australia'      => 'Australija',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indijski ocean',
'timezoneregion-pacific'        => 'Tihi ocean',
'allowemail'                    => 'Omogući primanje e-maila od drugih suradnika',
'prefs-searchoptions'           => 'Način traženja',
'prefs-namespaces'              => 'Imenski prostori',
'defaultns'                     => 'Ako nije navedeno drugačije, traži u ovim prostorima:',
'default'                       => 'prvotno',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'Prilagođen CSS',
'prefs-custom-js'               => 'Prilagođen JS',
'prefs-common-css-js'           => 'Dijeljeni CSS/JS za sve izglede:',
'prefs-reset-intro'             => 'Možete koristiti ovu stranicu za povrat Vaših postavki na prvotne postavke. Ovo se ne može poništiti.',
'prefs-emailconfirm-label'      => 'Potvrda e-mail adrese:',
'prefs-textboxsize'             => 'Veličina prozora za uređivanje',
'youremail'                     => 'Vaša elektronska pošta *',
'username'                      => 'Suradničko ime:',
'uid'                           => 'Suradnički ID-broj:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|skupine|skupina}}:',
'prefs-registration'            => 'Vrijeme prijave:',
'yourrealname'                  => 'Pravo ime (nije obvezno)*',
'yourlanguage'                  => 'Jezik:',
'yourvariant'                   => 'Inačica:',
'yournick'                      => 'Vaš nadimak (za potpisivanje)',
'prefs-help-signature'          => 'Komentari na stranicama za razgovor trebali bi biti potpisani s "<nowiki>~~~~</nowiki>" što će biti pretvoreno u Vaš potpis i datum.',
'badsig'                        => 'Kôd Vašeg potpisa nije valjan; provjerite HTML tagove.',
'badsiglength'                  => 'Vaš potpis je predugačak.
Ne smije biti duži od $1 {{PLURAL:$1|znaka|znaka|znakova}}.',
'yourgender'                    => 'Spol:',
'gender-unknown'                => 'Neodređeno',
'gender-male'                   => 'Muški',
'gender-female'                 => 'Ženski',
'prefs-help-gender'             => 'Mogućnost: softver koristi za ispravno oslovljavanje razlikujući spol. Ovaj podatak bit će javan.',
'email'                         => 'Adresa elektroničke pošte *',
'prefs-help-realname'           => 'Pravo ime nije obvezno. Ako ga navedete, bit će korišteno za pravnu atribuciju Vaših doprinosa.',
'prefs-help-email'              => 'E-mail adresa nije obvezna, ali omogućuje slanje nove lozinke e-mailom u slučaju da zaboravite svoju.
Možete omogućiti drugima da Vas kontaktiraju na suradničkoj stranici ili stranici za razgovor bez javnog otkrivanja Vaše e-mail adrese.',
'prefs-help-email-required'     => 'Potrebno je navesti adresu e-pošte (e-mail).',
'prefs-info'                    => 'Osnovni podaci',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Potpis',
'prefs-dateformat'              => 'Format datuma',
'prefs-timeoffset'              => 'Vremensko poravnavanje',
'prefs-advancedediting'         => 'Napredne opcije',
'prefs-advancedrc'              => 'Napredne opcije',
'prefs-advancedrendering'       => 'Napredne opcije',
'prefs-advancedsearchoptions'   => 'Napredne opcije',
'prefs-advancedwatchlist'       => 'Napredne opcije',
'prefs-displayrc'               => 'Prikaži opcije',
'prefs-displaysearchoptions'    => 'Opcije prikaza',
'prefs-displaywatchlist'        => 'Opcije prikaza',
'prefs-diffs'                   => 'razl',

# User rights
'userrights'                   => 'Upravljanje suradničkim pravima',
'userrights-lookup-user'       => 'Upravljaj suradničkim skupinama',
'userrights-user-editname'     => 'Unesite suradničko ime:',
'editusergroup'                => 'Uredi suradničke skupine',
'editinguser'                  => "Promjena suradničkih prava za suradnika '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Uredi suradničke skupine',
'saveusergroups'               => 'Snimi suradničke skupine',
'userrights-groupsmember'      => 'Član:',
'userrights-groupsmember-auto' => 'Uključeni član:',
'userrights-groups-help'       => 'Možete promijeniti skupine za ovog suradnika:
* Označena kućica pokazuje skupinu kojoj suradnik pripada.
* Neoznačena kućica pokazuje skupinu kojoj suradnik ne pripada.
* Zvjezdica * označava skupinu koju ne možete ukloniti kad ju jednom dodate, ili obratno.',
'userrights-reason'            => 'Razlog:',
'userrights-no-interwiki'      => 'Nemate dopuštenje za uređivanje suradničkih prava na drugim wikijima.',
'userrights-nodatabase'        => 'Baza podataka $1 ne postoji ili nije lokalno dostupna.',
'userrights-nologin'           => 'Morate se [[Special:UserLogin|prijaviti]] s administratorskim računom da bi mogli dodijeliti suradnička prava.',
'userrights-notallowed'        => 'Vaš trenutačni suradnički račun nema ovlasti mijenjanja suradničkih prava.',
'userrights-changeable-col'    => 'Skupine koje možete promijeniti',
'userrights-unchangeable-col'  => 'Skupine koje ne možete promijeniti',

# Groups
'group'               => 'Skupina:',
'group-user'          => 'Suradnici',
'group-autoconfirmed' => 'Automatski potvrđeni suradnici',
'group-bot'           => 'Botovi',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birokrati',
'group-suppress'      => 'Nadzornici',
'group-all'           => '(svi)',

'group-user-member'          => 'Suradnik',
'group-autoconfirmed-member' => 'Automatski potvrđen suradnik',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birokrat',
'group-suppress-member'      => 'Nadzornik',

'grouppage-user'          => '{{ns:project}}:Suradnici',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatski potvrđeni suradnici',
'grouppage-bot'           => '{{ns:project}}:Botovi',
'grouppage-sysop'         => '{{ns:project}}:Administratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrati',
'grouppage-suppress'      => '{{ns:project}}:Nadzor',

# Rights
'right-read'                  => 'Čitanje stranica',
'right-edit'                  => 'Uređivanje stranica',
'right-createpage'            => 'Stvaranje stranica (stranica koje nisu razgovor)',
'right-createtalk'            => 'Stvaranje stranica za razgovor',
'right-createaccount'         => 'Stvaranje novog suradničkog računa',
'right-minoredit'             => 'Označavanje izmjene manjom',
'right-move'                  => 'Premještanje stranica',
'right-move-subpages'         => 'Premještanje stranica s njihovim podstranicama',
'right-move-rootuserpages'    => 'Premještanje osnovne stranice suradnika',
'right-movefile'              => 'Premještanje datoteka',
'right-suppressredirect'      => 'Ne raditi preusmjeravanje od starog imena prilikom premještanja stranice',
'right-upload'                => 'Postavljanje datoteka',
'right-reupload'              => 'Postavljanje nove inačice datoteke',
'right-reupload-own'          => 'Postavljanje nove inačice vlastite datoteke',
'right-reupload-shared'       => 'Lokalno postavljanje novih inačica datoteka na zajedničkom poslužitelju',
'right-upload_by_url'         => 'Postavljanje datoteke s URL adrese',
'right-purge'                 => 'Čišćenje priručne memorije stranice bez stranice za potvrdu',
'right-autoconfirmed'         => 'Uređivanje stranica zaštićenih za neprijavljene suradnike',
'right-bot'                   => 'Izmjene su tretirane kao automatski proces (bot)',
'right-nominornewtalk'        => 'Bez manjih izmjena na novim stranicama za razgovor',
'right-apihighlimits'         => 'Korištenje viših granica kod API upita',
'right-writeapi'              => 'Mogućnost pisanja API',
'right-delete'                => 'Brisanje stranica',
'right-bigdelete'             => 'Brisanje stranica koje imaju veliku povijest',
'right-deleterevision'        => 'Brisanje i vraćanje određene izmjene na stranici',
'right-deletedhistory'        => 'Gledanje povijesti izmjena izbrisane stranice',
'right-deletedtext'           => 'Pregled izbrisanog teksta i izmjena između izbrisanih izmjena',
'right-browsearchive'         => 'Traženje obrisanih stranica',
'right-undelete'              => 'Vraćanje stranica',
'right-suppressrevision'      => 'Pregledavanje i vraćanje izmjena skrivenih od administratora',
'right-suppressionlog'        => 'Gledanje privatnih evidencija',
'right-block'                 => 'Blokiranje suradnika u uređivanju',
'right-blockemail'            => 'Blokiranje suradnika u slanju elektroničke pošte',
'right-hideuser'              => 'Blokiranje suradničkog imena, skrivajući ga od javnosti',
'right-ipblock-exempt'        => 'Imunitet na IP blokiranje, auto-blok i blokiranje opsega',
'right-proxyunbannable'       => 'Imunitet na automatska blokiranja posrednika (proxya)',
'right-unblockself'           => 'Odblokirati se',
'right-protect'               => 'Mijenjanje razina zaštićivanja i uređivanje zaštićenih stranica',
'right-editprotected'         => 'Uređivanje zaštićenih stranica (s prenosivom zaštitom)',
'right-editinterface'         => 'Uređivanje suradničkog sučelja',
'right-editusercssjs'         => 'Uređivanje CSS i JS stranica drugih suradnika',
'right-editusercss'           => 'Uređivanje CSS stranica drugih suradnika',
'right-edituserjs'            => 'Uređivanje JS stranica drugih suradnika',
'right-rollback'              => 'Brzo uklanjanje izmjena zadnjeg suradnika na određenoj stranici',
'right-markbotedits'          => 'Označavanje uklonjenih izmjena kao izmjenu bota',
'right-noratelimit'           => 'Bez vremenskog ograničenja uređivanja',
'right-import'                => 'Uvoženje stranica s drugih wikija',
'right-importupload'          => 'Uvoženje stranica kao datoteke',
'right-patrol'                => 'Označavanje izmjena pregledanim',
'right-autopatrol'            => 'Izmjene su automatski označene kao pregledane',
'right-patrolmarks'           => 'Vidljive oznake pregledavanja u nedavnim promjenama',
'right-unwatchedpages'        => 'Vidljiv popis nepraćenih stranica',
'right-trackback'             => 'Podnijeti trackback',
'right-mergehistory'          => 'Spajanje povijesti stranica',
'right-userrights'            => 'Uređivanje svih suradničkih prava',
'right-userrights-interwiki'  => 'Uređivanje suradničkih prava na drugim Wikijima',
'right-siteadmin'             => 'Zaključavanje i otključavanje baze podataka',
'right-reset-passwords'       => "Poništi (''resetiraj'') lozinku drugog suradnika",
'right-override-export-depth' => 'Izvezi stranice uključujući i povezane stranice do dubine od 5',
'right-sendemail'             => 'Slanje e-maila drugim korisnicima',
'right-revisionmove'          => 'Premjesti izmjene',
'right-disableaccount'        => 'Onemogući račune',

# User rights log
'rightslog'      => 'Evidencija suradničkih prava',
'rightslogtext'  => 'Ovo je evidencija promjena suradničkih prava.',
'rightslogentry' => 'promijenjena suradnička prava za $1 iz $2 u $3',
'rightsnone'     => '(suradnik)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'čitanje ove stranice',
'action-edit'                 => 'uređivanje ove stranice',
'action-createpage'           => 'stvaranje stranica',
'action-createtalk'           => 'stvaranje stranica za razgovor',
'action-createaccount'        => 'stvaranje ovog suradničkog računa',
'action-minoredit'            => 'označavanje ove izmjene kao manju',
'action-move'                 => 'premještanje ove stranice',
'action-move-subpages'        => 'premještanje ove stranice, i njenih podstranica',
'action-move-rootuserpages'   => 'premještanje osnovne stranice suradnika',
'action-movefile'             => 'premjesti ovu datoteku',
'action-upload'               => 'postavljanje ove datoteke',
'action-reupload'             => 'postavljanje nove inačice ove datoteke',
'action-reupload-shared'      => 'postavljanje nove inačice ove datoteke na zajedničkom poslužitelju',
'action-upload_by_url'        => 'postavljanje ove datoteke preko URL adrese',
'action-writeapi'             => 'za korištenje pisanja API',
'action-delete'               => 'brisanje ove stranice',
'action-deleterevision'       => 'brisanje ove izmjene',
'action-deletedhistory'       => 'gledanje obrisane povijesti ove stranice',
'action-browsearchive'        => 'pretraživanje izbrisanih stranica',
'action-undelete'             => 'vraćanje ove stranice',
'action-suppressrevision'     => 'pregledavanje i vraćanje ove sakrivene izmjene',
'action-suppressionlog'       => 'gledanje ove privatne evidencije',
'action-block'                => 'blokiranje ovog suradnika',
'action-protect'              => 'promjenu stupnja zaštite ove stranice',
'action-import'               => 'uvoženje ove stranice s drugog wikija',
'action-importupload'         => 'uvoženje ove stranice postavljanjem datoteke',
'action-patrol'               => 'označavanje tuđih izmjena pregledanim',
'action-autopatrol'           => 'automatsko označavanje pregledanim za svoje izmjene',
'action-unwatchedpages'       => 'gledanje popisa stranica koje nisu praćene',
'action-trackback'            => 'podnošenje povratne informacije',
'action-mergehistory'         => 'spajanje povijesti ove stranice',
'action-userrights'           => 'uređivanje svih suradničkih prava',
'action-userrights-interwiki' => 'uređivanje suradničkih prava suradnika na drugim wikijima',
'action-siteadmin'            => 'zaključavanje ili otključavanje baze podataka',
'action-revisionmove'         => 'premjesti izmjene',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|$1 promjena|$1 promjene|$1 promjena}}',
'recentchanges'                     => 'Nedavne promjene',
'recentchanges-legend'              => 'Izbornik nedavnih promjena',
'recentchangestext'                 => 'Na ovoj stranici možete pratiti nedavne promjene u wikiju.',
'recentchanges-feed-description'    => 'Na ovoj stranici možete pratiti nedavne promjene u wikiju.',
'recentchanges-label-newpage'       => 'Ova izmjena stvorila je novu stranicu',
'recentchanges-label-minor'         => 'Ovo je manja izmjena',
'recentchanges-label-bot'           => 'Ovu izmjenu napravio je bot',
'recentchanges-label-unpatrolled'   => 'Ova izmjena još nije pregledana',
'rcnote'                            => "{{PLURAL:$1|Slijedi zadnja '''$1''' promjena|Slijede zadnje '''$1''' promjene|Slijedi zadnjih '''$1''' promjena}} u {{PLURAL:$2|zadnjem '''$2''' danu|zadnja '''$2''' dana|zadnjih '''$2''' dana}}, od $5, $4.",
'rcnotefrom'                        => 'Slijede promjene od <b>$2</b> (prikazano ih je do <b>$1</b>).',
'rclistfrom'                        => 'Prikaži nove promjene počevši od $1',
'rcshowhideminor'                   => '$1 manje promjene',
'rcshowhidebots'                    => '$1 botove',
'rcshowhideliu'                     => '$1 prijavljene suradnike',
'rcshowhideanons'                   => '$1 neprijavljene suradnike',
'rcshowhidepatr'                    => '$1 provjerene promjene',
'rcshowhidemine'                    => '$1 moje promjene',
'rclinks'                           => 'Prikaži zadnjih $1 promjena u zadnjih $2 dana; $3',
'diff'                              => 'razl',
'hist'                              => 'pov',
'hide'                              => 'sakrij',
'show'                              => 'prikaži',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|suradnik|suradnika|suradnika}} prati ovu stranicu]',
'rc_categories'                     => 'Ograniči na kategorije (odvojene znakom  "|")',
'rc_categories_any'                 => 'Sve',
'newsectionsummary'                 => '/* $1 */ Novi odlomak',
'rc-enhanced-expand'                => 'Pokaži detalje (potreban JavaScript)',
'rc-enhanced-hide'                  => 'Sakrij detalje',

# Recent changes linked
'recentchangeslinked'          => 'Povezane stranice',
'recentchangeslinked-feed'     => 'Povezane stranice',
'recentchangeslinked-toolbox'  => 'Povezane stranice',
'recentchangeslinked-title'    => 'Povezane promjene sa "$1"',
'recentchangeslinked-noresult' => 'Nema promjena na povezanim stranicama u zadanom periodu.',
'recentchangeslinked-summary'  => "Ova posebna stranica pokazuje nedavne promjene na povezanim stranicama (ili stranicama određene kategorije). Stranice koje su na [[Special:Watchlist|Vašem popisu praćenja]] su '''podebljane'''.",
'recentchangeslinked-page'     => 'Naslov stranice:',
'recentchangeslinked-to'       => 'Pokaži promjene na stranicama s poveznicom na ovu stranicu',

# Upload
'upload'                      => 'Postavi datoteku',
'uploadbtn'                   => 'Postavi datoteku',
'reuploaddesc'                => 'Vratite se u obrazac za postavljanje.',
'upload-tryagain'             => 'Pošalji izmijenjeni opis datoteke',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Za postavljanje datoteka morate biti  [[Special:UserLogin|prijavljeni]].',
'upload_directory_missing'    => 'Mapa za datoteke ($1) nedostaje i webserver ju ne može napraviti.',
'upload_directory_read_only'  => 'Server ne može pisati u direktorij za postavljanje ($1).',
'uploaderror'                 => 'Pogreška kod postavljanja',
'upload-recreate-warning'     => "'''Upozorenje: datoteka s tim imenom je izbrisana ili premještena.'''

Evidencije brisanja i premještanja prikazane su ovdje:",
'uploadtext'                  => "Ovaj obrazac služi za postavljanje datoteka.
Za pregledavanje i pretraživanje već postavljenih datoteka vidi [[Special:FileList|popis postavljenih datoteka]], (ponovljena) postavljanja su također u [[Special:Log/upload|popisu postavljanja]], a brisanja u [[Special:Log/delete|popisu brisanja]].

Da biste na stranicu stavili datoteku, koristite poveznice tipa
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.jpg]]</nowiki></tt>''' za punu verziju datoteke
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Datoteka.png|200px|mini|left|popratni tekst]]</nowiki></tt>''' za datoteku širine 200 px u okviru s 'popratnim tekstom' kao opisom
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datoteka.ogg]]</nowiki></tt>''' za direktno povezivanje na datoteku bez njenog prikazivanja",
'upload-permitted'            => 'Dopušteni tipovi datoteka: $1.',
'upload-preferred'            => 'Poželjni tipovi datoteka: $1.',
'upload-prohibited'           => 'Zabranjeni tipovi datoteka: $1.',
'uploadlog'                   => 'evidencija postavljanja',
'uploadlogpage'               => 'Evidencija_postavljanja',
'uploadlogpagetext'           => 'Dolje je popis nedavno postavljenih slika.',
'filename'                    => 'Ime datoteke',
'filedesc'                    => 'Sažetak',
'fileuploadsummary'           => 'Sažetak:',
'filereuploadsummary'         => 'Izmjene datoteke:',
'filestatus'                  => 'Status autorskih prava:',
'filesource'                  => 'Izvor:',
'uploadedfiles'               => 'Postavljene datoteke',
'ignorewarning'               => 'Zanemari upozorenja i snimi datoteku.',
'ignorewarnings'              => 'Zanemari sva upozorenja',
'minlength1'                  => 'Ime datoteke mora imati barem jedno slovo.',
'illegalfilename'             => 'Ime datoteke "$1" sadrži znakove koji nisu dopušteni u imenima stranica. Preimenujte datoteku i ponovno je postavite.',
'badfilename'                 => 'Ime slike automatski je promijenjeno u "$1".',
'filetype-mime-mismatch'      => 'Proširenje datoteke ne odgovara MIME tipu.',
'filetype-badmime'            => 'Datoteke MIME tipa "$1" ne mogu se snimati.',
'filetype-bad-ie-mime'        => 'Ne mogu postaviti ovu datoteku jer je Internet Explorer prepoznaje kao "$1", koji nije dopušten i potencijalno je opasan tip datoteke.',
'filetype-unwanted-type'      => "'''\".\$1\"''' je neželjena vrsta datoteke. {{PLURAL:\$3|Preporučena vrsta je|Preporučene vrste su}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' je nedopuštena vrsta datoteke. {{PLURAL:\$3|Dopuštena vrsta je|Dopuštene vrste su}} \$2.",
'filetype-missing'            => 'Datoteka nema nastavak koji određuje tip (poput ".jpg").',
'empty-file'                  => 'Datoteka koju ste poslali je prazna.',
'file-too-large'              => 'Datoteka koju ste poslali bila je prevelika.',
'filename-tooshort'           => 'Ime datoteke je prekratko.',
'filetype-banned'             => 'Ova vrsta datoteke je zabranjena.',
'verification-error'          => 'Ova datoteka nije prošla provjeru datoteke.',
'hookaborted'                 => 'Izmjena koju ste pokušali napraviti prekinuta je od strane ekstenzije.',
'illegal-filename'            => 'Ime datoteke nije dopušteno.',
'overwrite'                   => 'Postaviti preko postojeće datoteke nije dozvoljeno.',
'unknown-error'               => 'Nepoznata pogreška.',
'tmp-create-error'            => 'Ne mogu stvoriti privremenu datoteku.',
'tmp-write-error'             => 'Pogreška prilikom pisanja privremene datoteke.',
'large-file'                  => 'Preporučljivo je da datoteke ne prelaze $1; Ova datoteka je $2.',
'largefileserver'             => 'Veličina ove datoteke veća je od one dopuštene postavkama poslužitelja.',
'emptyfile'                   => 'Datoteka koju ste postavili je prazna. Možda se radi o krivo utipkanom imenu datoteke. Provjerite želite li zaista postaviti ovu datoteku.',
'fileexists'                  => "Datoteka s ovim imenom već postoji, pogledajte '''<tt>[[:$1]]</tt>''' ako niste sigurni želite li je uistinu promijeniti.
[[$1|thumb]]",
'filepageexists'              => "Opis stranice za ovu datoteku je već napravljen ovdje '''<tt>[[:$1]]</tt>''', ali datoteka sa ovim nazivom trenutno ne postoji.
Sažetak koji ste naveli neće se pojaviti na stranici opisa.
Da bi se Vaš opis ovdje našao, potrebno je da ga ručno uredite.
[[$1|thumb]]",
'fileexists-extension'        => "Već postoji datoteka sa sličnim imenom: [[$2|thumb]]
* Ime datoteke koju postavljate: '''<tt>[[:$1]]</tt>'''
* Ime postojeće datoteke: '''<tt>[[:$2]]</tt>'''
Molimo da izaberete drugo ime.",
'fileexists-thumbnail-yes'    => "Datoteka je najvjerojatnije slika u smanjenoj veličini ''(thumbnail)''. [[$1|thumb]]
Molimo provjerite datoteku '''<tt>[[:$1]]</tt>'''.
Ukoliko je ta datoteka ista kao i ova koju ste upravo pokušali snimiti, samo u višoj rezoluciji, nije nužno snimanje smanjenje slike ''(thumbnaila)'', prikazivanje smanjene slike iz izvornika radi se softverski.",
'file-thumbnail-no'           => "Ime datoteke počinje s '''<tt>$1</tt>'''.
Čini se da je to slika smanjene veličine ''(minijatura)''.
Ukoliko imate ovu sliku u punoj razlučljivosti (rezoluciji) postavite tu sliku, u protivnom, molimo promijenite ime datoteke.",
'fileexists-forbidden'        => 'Datoteka s ovim imenom već postoji i nemože biti presnimljena.
Ako i dalje želite postaviti svoju datoteku, molimo vratite se i odaberite novo ime. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Datoteka s ovim imenom već postoji u središnjem poslužitelju datoteka.
Ako još uvijek želite postaviti svoju datoteku, idite nazad i postavite ju pod drugim imenom. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ova datoteka je duplikat {{PLURAL:$1|sljedeće datoteke|sljedećih datoteka}}:',
'file-deleted-duplicate'      => 'Datoteka istovjetna ovoj datoteci ([[:$1]]) prethodno je obrisana. Provjerite evidenciju brisanja za tu datoteke datoteku prije nego što nastavite s ponovnim postavljanjem.',
'uploadwarning'               => 'Upozorenje kod postavljanja',
'uploadwarning-text'          => 'Molimo izmijenite opis datoteke ispod i pokušajte kasnije.',
'savefile'                    => 'Sačuvaj datoteku',
'uploadedimage'               => 'postavljeno "$1"',
'overwroteimage'              => 'postavljena nova inačica od "[[$1]]"',
'uploaddisabled'              => 'Postavljanje je onemogućeno',
'copyuploaddisabled'          => 'Postavljanje URL-om onemogućeno.',
'uploadfromurl-queued'        => 'Vaše postavljanje je na čekanju.',
'uploaddisabledtext'          => 'Postavljanje datoteka je onemogućeno.',
'php-uploaddisabledtext'      => 'Postavljanja datoteka su onemogućena u PHP-u. Molimo provjerite postavke za postavljanje datoteka.',
'uploadscripted'              => 'Ova datoteka sadrži HTML ili skriptu, što može dovesti do grešaka u web pregledniku.',
'uploadvirus'                 => 'Datoteka sadrži virus! Podrobnije: $1',
'upload-source'               => 'Izvorna datoteka',
'sourcefilename'              => 'Ime datoteke na Vašem računalu:',
'sourceurl'                   => 'URL izvora:',
'destfilename'                => 'Ime datoteke na wikiju:',
'upload-maxfilesize'          => 'Maksimalna veličina datoteke: $1',
'upload-description'          => 'Opis datoteke',
'upload-options'              => 'Mogućnosti postavljanja',
'watchthisupload'             => 'Prati ovu datoteku',
'filewasdeleted'              => 'Datoteka istog imena već je bila postavljena, a kasnije i obrisana. Trebali bi provjeriti $1 prije nego što ponovno postavite datoteku.',
'upload-wasdeleted'           => "'''Upozorenje: Pokušavate postaviti datoteku koja je prethodno obrisana.'''

Razmislite je li prigodno nastaviti s postavljanjem ove datoteke.
Slijedi evidencija brisanja ove datoteke s obrazloženjem prethodnog brisanja:",
'filename-bad-prefix'         => "Ime datoteke koju snimate počinje s '''\"\$1\"''', što je ime koje slikama tipično dodjeljuju digitalni fotoaparati. Molimo izaberite bolje ime (neko koje bolje opisuje sliku nego \$1).",
'upload-success-subj'         => 'Postavljanje uspješno.',
'upload-success-msg'          => 'Vaša datoteka iz [$2] je uspješno postavljena. Dostupna je ovdje: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Greška pri postavljanju',
'upload-failure-msg'          => 'Došlo je do problema s Vašim postavljanjem datoteke [$2]:

$1',
'upload-warning-subj'         => 'Upozorenje kod postavljanja',
'upload-warning-msg'          => 'Došlo je do problema s Vašim postavljanjem datoteke iz [$2]. Možete se vratiti u [[Special:Upload/stash/$1|obrazac za postavljanje]] i ispraviti problem.',

'upload-proto-error'        => 'Protokol nije valjan',
'upload-proto-error-text'   => 'Udaljeno snimanje zahtijeva URL-ove koji počinju sa <code>http://</code> ili <code>ftp://</code>.',
'upload-file-error'         => 'Interna pogreška',
'upload-file-error-text'    => 'Interna pogreška se dogodila pri pokušaju stvaranja privremene datoteke na poslužitelju. Molimo javite se [[Special:ListUsers/sysop|administratoru]].',
'upload-misc-error'         => 'Nepoznata pogreška pri snimanju',
'upload-misc-error-text'    => 'Dogodila se nepoznata pogreška pri snimanju.
Provjerite valjanost i dostupnost URL-a i pokušajte opet.
Ukoliko se problem ponovi, javite to [[Special:ListUsers/sysop|administratoru]].',
'upload-too-many-redirects' => 'URL je sadržavao previše preusmjeravanja',
'upload-unknown-size'       => 'Nepoznata veličina',
'upload-http-error'         => 'HTTP pogreška: $1',

# Special:UploadStash
'uploadstash'          => 'Snimi niz datoteka',
'uploadstash-summary'  => 'Ova stranica pruža pristup datotekama koje su snimljene na wiki (ili u procesu snimanja), ali još nisu objavljeni na wiki. Ove datoteke nisu vidljive nikome, osim suradniku koji ih je snimio.',
'uploadstash-clear'    => 'Očisti niz datoteka',
'uploadstash-nofiles'  => 'Nemate neobjavljenih datoteka',
'uploadstash-badtoken' => 'Obavljanje akcije je bilo neuspješano, možda jer je vaša prijava istekla. Pokušajte ponovno.',
'uploadstash-errclear' => 'Brisanje neobjavljenih datoteka nije uspjelo.',
'uploadstash-refresh'  => 'Osvježi popis datoteka',

# img_auth script messages
'img-auth-accessdenied' => 'Pristup onemogućen',
'img-auth-nopathinfo'   => 'Nedostaje PATH_INFO.
Vaš poslužitelj nije postavljen da prosljeđuje ovu informaciju.
Možda se temelji na CGI i ne može podržavati img_auth.
Pogledajte http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Zahtjevana putanja nije u direktoriju podešenom za postavljanje.',
'img-auth-badtitle'     => 'Ne mogu stvoriti valjani naslov iz "$1".',
'img-auth-nologinnWL'   => 'Niste prijavljeni i "$1" nije na popisu dozvoljenih.',
'img-auth-nofile'       => 'Datoteka "$1" ne postoji.',
'img-auth-isdir'        => 'Pokušavate pristupiti direktoriju "$1".
Dozvoljen je samo pristup datotekama.',
'img-auth-streaming'    => 'Tok "$1".',
'img-auth-public'       => 'Funkcija img_auth.php služi za izlaz datoteka s privatnih wikija.
Ovaj wiki je postavljena kao javni wiki.
Za optimalnu sigurnost, img_auth.php je onemogućena.',
'img-auth-noread'       => 'Suradnik nema pristup za čitanje "$1".',

# HTTP errors
'http-invalid-url'      => 'Nevaljan URL: $1',
'http-invalid-scheme'   => 'URL-ovi s prefiksom "$1" nisu podržani.',
'http-request-error'    => 'HTTP zahtjev nije uspio zbog nepoznate pogreške.',
'http-read-error'       => 'Greška pri čitanju HTTP.',
'http-timed-out'        => 'HTTP zahtjev je istekao.',
'http-curl-error'       => 'Greška pri otvaranju URL-a: $1',
'http-host-unreachable' => 'URL nije dostupan.',
'http-bad-status'       => 'Došlo je do problema tijekom HTTP zahtjeva: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL nije dostupan',
'upload-curl-error6-text'  => 'Dani URL nije dostupan. Provjerite da li je URL ispravno upisan i da li su web stranice dostupne.',
'upload-curl-error28'      => "Istek vremena snimanja (''timeout'')",
'upload-curl-error28-text' => 'Poslužitelj ne odgovara na upit. Provjerite da li su web stranice dostupne, pričekajte i pokušajte ponovo. Možete pokušati kasnije, kad bude manja gužva.',

'license'            => 'Licencija:',
'license-header'     => 'Licencija',
'nolicense'          => 'Ništa nije odabrano',
'license-nopreview'  => '(Prikaz nije moguć)',
'upload_source_url'  => ' (valjani, javno dostupni URL)',
'upload_source_file' => '(datoteka na Vašem računalu)',

# Special:ListFiles
'listfiles-summary'     => 'Ova posebna stranica pokazuje sve postavljene datoteke.
Na vrhu popisa se nalaze najnovije postavljene datoteke.
Poredak datoteka mijenja se pritiskom na naslov stupca.',
'listfiles_search_for'  => 'Traži ime slike:',
'imgfile'               => 'datoteka',
'listfiles'             => 'Popis slika',
'listfiles_thumb'       => 'Smanjeni pregled',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Naziv slike',
'listfiles_user'        => 'Suradnik',
'listfiles_size'        => 'Veličina (u bajtovima)',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Inačice',

# File description page
'file-anchor-link'          => 'Slika',
'filehist'                  => 'Povijest datoteke',
'filehist-help'             => 'Kliknite na datum/vrijeme kako biste vidjeli datoteku kakva je tada bila.',
'filehist-deleteall'        => 'izbriši sve',
'filehist-deleteone'        => 'izbriši',
'filehist-revert'           => 'vrati',
'filehist-current'          => 'sadašnja',
'filehist-datetime'         => 'Datum/Vrijeme',
'filehist-thumb'            => 'Minijatura',
'filehist-thumbtext'        => 'Minijatura za inačicu od $1',
'filehist-nothumb'          => 'Nema minijature',
'filehist-user'             => 'Suradnik',
'filehist-dimensions'       => 'Dimenzije',
'filehist-filesize'         => 'Veličina datoteke',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Nedostaje datoteka',
'imagelinks'                => 'Poveznice datoteke',
'linkstoimage'              => '{{PLURAL:$1|Sljedeća stranica povezuje|$1 sljedećih stranice povezuju}} na ovu datoteku:',
'linkstoimage-more'         => 'Više od $1 {{PLURAL:$1|stranice povezuje|stranica povezuje}} na ovu datoteku.
Slijedeći popis prikazuje {{PLURAL:$1|stranice koje|prvih $1 stranica koje}} vode na ovu datoteku.
[[Special:WhatLinksHere/$2|Ovdje se nalazi]] potpuni popis.',
'nolinkstoimage'            => 'Nijedna stranica ne povezuje na ovu sliku.',
'morelinkstoimage'          => 'Pogledaj [[Special:WhatLinksHere/$1|više poveznica]] za ovu datoteku.',
'redirectstofile'           => '{{PLURAL:$1|Sljedeća datoteka preusmjerava|$1 Sljedeće datoteke preusmjeravaju}} na ovu datoteku:',
'duplicatesoffile'          => '{{PLURAL:$1|Sljedeća datoteka je kopija|$1 sljedeće datoteke su kopije|$1 sljedećih datoteka su kopije}} ove datoteke ([[Special:FileDuplicateSearch/$2|više detalja]]):',
'sharedupload'              => 'Ova je datoteka postavljena na $1 i mogu je koristiti ostali projekti.',
'sharedupload-desc-there'   => 'Ova datoteka je s $1 i mogu je koristiti drugi projekti. Pogledajte [$2 stranicu s opisom datoteke] za dodatne informacije.',
'sharedupload-desc-here'    => 'Ova datoteka je s $1 i mogu je koristiti drugi projekti. Opis s njezine [$2 stranice s opisom datoteke] prikazan je ispod.',
'filepage-nofile'           => 'Ne postoji datoteka s ovim imenom.',
'filepage-nofile-link'      => 'Ne postoji datoteka s ovim imenom, ali možete je [$1 postaviti].',
'uploadnewversion-linktext' => 'Postavi novu inačicu datoteke',
'shared-repo-from'          => 's projekta $1',
'shared-repo'               => 'zajednički poslužitelj',

# File reversion
'filerevert'                => 'Ukloni ← $1',
'filerevert-legend'         => 'Vrati datoteku',
'filerevert-intro'          => "Vraćate '''[[Media:$1|$1]]''' na [$4 promjenu od $3, $2].",
'filerevert-comment'        => 'Razlog:',
'filerevert-defaultcomment' => 'Vraćeno na inačicu od $2, $1',
'filerevert-submit'         => 'Vrati',
'filerevert-success'        => "'''[[Media:$1|$1]]''' je vraćena na [$4 promjenu od $3, $2].",
'filerevert-badversion'     => 'Nema prethodne lokalne inačice datoteke s zadanim datumom i vremenom.',

# File deletion
'filedelete'                  => 'Izbriši $1',
'filedelete-legend'           => 'Izbriši datoteku',
'filedelete-intro'            => "Brišete datoteku '''[[Media:$1|$1]]''' uključujući i sve njezine stare izmjene.",
'filedelete-intro-old'        => "Brišete inačicu '''[[Media:$1|$1]]''' od [$4 $3, $2].",
'filedelete-comment'          => 'Razlog:',
'filedelete-submit'           => 'Izbriši',
'filedelete-success'          => "Datoteka '''$1''' je izbrisana.",
'filedelete-success-old'      => "Inačica datoteke '''[[Media:$1|$1]]''' od $3, $2 je obrisana.",
'filedelete-nofile'           => "'''$1''' ne postoji.",
'filedelete-nofile-old'       => "Nema arhivirane verzije datoteke '''$1''' s zadanim parametrima.",
'filedelete-otherreason'      => 'Drugi/dodatni razlog:',
'filedelete-reason-otherlist' => 'Drugi razlog',
'filedelete-reason-dropdown'  => '*Česti razlozi brisanja
** Kršenje autorskih prava
** Dupla datoteka
** Nekorištena datoteka',
'filedelete-edit-reasonlist'  => 'Uredi razloge za brisanje',
'filedelete-maintenance'      => 'Brisanje i vraćanje datoteka privremeno je onemogućeno zbog održavanja.',

# MIME search
'mimesearch'         => 'MIME tražilica',
'mimesearch-summary' => 'Ova stranica omogućuje pretraživanje datoteka prema njihovim MIME zaglavljima. Ulazni parametar: tip_datoteke/podtip, npr. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tip datoteke:',
'download'           => 'skidanje',

# Unwatched pages
'unwatchedpages' => 'Nepraćene stranice',

# List redirects
'listredirects' => 'Popis preusmjeravanja',

# Unused templates
'unusedtemplates'     => 'Nekorišteni predlošci',
'unusedtemplatestext' => 'Slijedi popis svih stranica imenskog prostora {{ns:template}}, koje nisu umetnute na drugim stranicama. Pripazite da prije brisanja provjerite druge poveznice koje vode na te predloške.',
'unusedtemplateswlh'  => 'druge poveznice',

# Random page
'randompage'         => 'Slučajna stranica',
'randompage-nopages' => 'Nema stranica u {{PLURAL:$2|imenskom prostoru|imenskim prostorima}}: $1.',

# Random redirect
'randomredirect'         => 'Slučajno preusmjeravanje',
'randomredirect-nopages' => 'Nema preusmjeravanja u imenskom prostoru "$1".',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistika stranica',
'statistics-header-edits'      => 'Statistika uređivanja',
'statistics-header-views'      => 'Statistika posjećivanja',
'statistics-header-users'      => 'Statistika suradnika',
'statistics-header-hooks'      => 'Ostale statistike',
'statistics-articles'          => 'Stranice sa sadržajem',
'statistics-pages'             => 'Stranice',
'statistics-pages-desc'        => 'Sve stranice na wikiju, uključujući stranice za razgovor, preusmjeravanja i dr.',
'statistics-files'             => 'Postavljene datoteke',
'statistics-edits'             => 'Broj uređivanja od nastanka projekta {{SITENAME}}',
'statistics-edits-average'     => 'Prosječan broj uređivanja po stranici',
'statistics-views-total'       => 'Posjeta ukupno',
'statistics-views-total-desc'  => 'Posjeti nepostojećim i posebnim stranicama nisu uključeni',
'statistics-views-peredit'     => 'Posjeta po uređivanju',
'statistics-users'             => 'Prijavljeni [[Special:ListUsers|suradnici]]',
'statistics-users-active'      => 'Aktivni suradnici',
'statistics-users-active-desc' => 'Suradnici koji su napravili neku od radnji u posljednjih {{PLURAL:$1|dan|$1 dana}}',
'statistics-mostpopular'       => 'Najposjećenije stranice',

'disambiguations'      => 'Razdvojbene stranice',
'disambiguationspage'  => 'Template:Razdvojba',
'disambiguations-text' => "Sljedeće stranice povezuju na '''razdvojbenu stranicu'''.
Umjesto toga bi trebale povezivati na prikladnu temu.<br />
Stranica se tretira kao razdvojbena stranica ako koristi predložak na kojega vodi [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Dvostruka preusmjeravanja',
'doubleredirectstext'        => 'Ova stranica sadrži popis stranica koje preusmjeravju na druge stranice za preusmjeravanje.
Svaki redak sadrži poveznice na prvo i drugo preusmjeravanje, kao i odredište drugog preusmjeravanja
koja obično ukazuje na "pravu" odredišnu stranicu, na koju bi trebalo pokazivati prvo preusmjeravanje.
<del>Precrtane</del> stavke su riješene.',
'double-redirect-fixed-move' => '[[$1]] je premješten, sada je preusmjeravanje na [[$2]]',
'double-redirect-fixer'      => 'Popravljač preusmjeravanja',

'brokenredirects'        => 'Kriva preusmjeravanja',
'brokenredirectstext'    => 'Sljedeća preusmjeravanja povezuju na nepostojeće stranice:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'izbriši',

'withoutinterwiki'         => 'Stranice bez međuwiki poveznica',
'withoutinterwiki-summary' => 'Sljedeće stranice nemaju poveznice na projekte na drugim jezicima:',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Prikaži',

'fewestrevisions' => 'Članci s najmanje izmjena',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajta|bajtova}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategorije|kategorija}}',
'nlinks'                  => '$1 {{PLURAL:$1|poveznica|poveznice|poveznica}}',
'nmembers'                => '$1 {{PLURAL:$1|član|članova}}',
'nrevisions'              => '$1 {{PLURAL:$1|inačica|inačice|inačica}}',
'nviews'                  => '$1 {{PLURAL:$1|put pogledano|puta pogledano|puta pogledano}}',
'nimagelinks'             => 'Koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'ntransclusions'          => 'koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'specialpage-empty'       => 'Nema rezultata za traženi izvještaj.',
'lonelypages'             => 'Stranice siročad',
'lonelypagestext'         => 'Sljedeće stranice nemaju poveznicu na druge stranice niti su uključene transkluzijom u druge stranice projekta {{SITENAME}}.',
'uncategorizedpages'      => 'Nekategorizirane stranice',
'uncategorizedcategories' => 'Nekategorizirane kategorije',
'uncategorizedimages'     => 'Nekategorizirane datoteke',
'uncategorizedtemplates'  => 'Nekategorizirani predlošci',
'unusedcategories'        => 'Nekorištene kategorije',
'unusedimages'            => 'Nekorištene slike',
'popularpages'            => 'Popularne stranice',
'wantedcategories'        => 'Tražene kategorije',
'wantedpages'             => 'Tražene stranice',
'wantedpages-badtitle'    => 'Nevaljani naslov kao rezultat: $1',
'wantedfiles'             => 'Tražene datoteke',
'wantedtemplates'         => 'Traženi predlošci',
'mostlinked'              => 'Stranice na koje vodi najviše poveznica',
'mostlinkedcategories'    => 'Kategorije na koje vodi najviše poveznica',
'mostlinkedtemplates'     => 'Predlošci na koje vodi najviše poveznica',
'mostcategories'          => 'Popis članaka po broju kategorija',
'mostimages'              => 'Slike na koje vodi najviše poveznica',
'mostrevisions'           => 'Popis članaka po broju uređivanja',
'prefixindex'             => 'Sve stranice prema početku naslova',
'shortpages'              => 'Kratke stranice',
'longpages'               => 'Duge stranice',
'deadendpages'            => 'Slijepe ulice',
'deadendpagestext'        => 'Slijedeće stranice nemaju poveznice na druge stranice na ovom wikiju ({{SITENAME}}).',
'protectedpages'          => 'Zaštićene stranice',
'protectedpages-indef'    => 'Samo neograničene zaštite',
'protectedpages-cascade'  => 'Samo prenosiva zaštita',
'protectedpagestext'      => 'Slijedeće stranice su zaštićene od premještanja ili uređivanja',
'protectedpagesempty'     => 'Nema zaštićenih stranica koje ispunjavaju uvjete koje ste postavili.',
'protectedtitles'         => 'Zaštićeni naslovi',
'protectedtitlestext'     => 'Sljedeći naslovi su zaštićeni od kreiranja',
'protectedtitlesempty'    => 'Nijedan naslov nije trenutačno zaštićen s tim parametrima.',
'listusers'               => 'Popis suradnika',
'listusers-editsonly'     => 'Pokaži samo suradnike s uređivanjem',
'listusers-creationsort'  => 'Razvrstaj po datumu stvaranja',
'usereditcount'           => '$1 {{PLURAL:$1|uređivanje|uređivanja|uređivanja}}',
'usercreated'             => 'Otvoren $1 u $2',
'newpages'                => 'Nove stranice',
'newpages-username'       => 'Suradničko ime:',
'ancientpages'            => 'Najstarije stranice',
'move'                    => 'Premjesti',
'movethispage'            => 'Premjesti ovu stranicu',
'unusedimagestext'        => 'Sljedeće datoteke postoje, ali nisu uključene ni u jednu stranicu.
Molimo obratite pozornost da druge web stranice mogu povezivati sliku izravnim URL-om, i tako mogu  još uvijek biti prikazani ovdje unatoč činjenici da više nisu u aktivnoj uporabi.',
'unusedcategoriestext'    => 'Na navedenim stranicama kategorija nema ni jednog članka ili potkategorije.',
'notargettitle'           => 'Nema odredišta',
'notargettext'            => 'Niste naveli ciljnu stranicu ili suradnika za izvršavanje ove funkcije.',
'nopagetitle'             => 'Nema ciljane stranice',
'nopagetext'              => 'Ciljana stranica koju ste odabrali ne postoji.',
'pager-newer-n'           => '{{PLURAL:$1|novija $1|novije $1|novijih $1}}',
'pager-older-n'           => '{{PLURAL:$1|starija $1|starije $1|starijih $1}}',
'suppress'                => 'Nadzor',
'querypage-disabled'      => 'Ova posebna stranica onemogućena je jer bi usporila funkcioniranje projekta.',

# Book sources
'booksources'               => 'Pretraživanje po ISBN-u',
'booksources-search-legend' => 'Traženje izvora za knjigu',
'booksources-go'            => 'Kreni',
'booksources-text'          => 'Ovdje je popis vanjskih poveznica na internetskim stranicama koje prodaju nove i rabljene knjige, ali mogu sadržavati i ostale podatke o knjigama koje tražite:',
'booksources-invalid-isbn'  => 'Čini se da dani ISBN nije valjan; provjerite greške kopirajući iz izvornika.',

# Special:Log
'specialloguserlabel'  => 'Suradnik:',
'speciallogtitlelabel' => 'Naslov:',
'log'                  => 'Evidencije',
'all-logs-page'        => 'Sve javne evidencije',
'alllogstext'          => 'Skupni prikaz svih dostupnih evidencija za {{SITENAME}}.
Možete suziti prikaz odabirući tip evidencije, suradničko ime ili stranicu u upitu.',
'logempty'             => 'Nema pronađenih stavki.',
'log-title-wildcard'   => 'Traži stranice koje počinju s navedenim izrazom',

# Special:AllPages
'allpages'          => 'Sve stranice',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'Sljedeća stranica ($1)',
'prevpage'          => 'Prethodna stranica ($1)',
'allpagesfrom'      => 'Pokaži stranice počevši od:',
'allpagesto'        => 'Pokaži stranice koje završavaju na:',
'allarticles'       => 'Svi članci',
'allinnamespace'    => 'Svi članci (prostor $1)',
'allnotinnamespace' => 'Sve stranice koje nisu u prostoru $1',
'allpagesprev'      => 'Prijašnje',
'allpagesnext'      => 'Sljedeće',
'allpagessubmit'    => 'Kreni',
'allpagesprefix'    => 'Stranice čiji naslov počinje s:',
'allpagesbadtitle'  => 'Zadana stranica nije valjana, ili je imala međuwiki predmetak. Možda sadrži jedan ili više znakova koji ne mogu biti uporabljeni u nazivu stranice.',
'allpages-bad-ns'   => '{{SITENAME}} nema imenski prostor "$1".',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => 'Sljedeće {{PLURAL:$1|kategorija sadrži|kategorije sadrže}} stranice ili datoteke.
[[Special:UnusedCategories|Nekorištene kategorije]] ovdje nisu prikazane.
Također pogledajte [[Special:WantedCategories|tražene kategorije]].',
'categoriesfrom'                => 'Prikaži kategorije počevši od:',
'special-categories-sort-count' => 'razvrstavanje po broju',
'special-categories-sort-abc'   => 'abecedno razvrstavanje',

# Special:DeletedContributions
'deletedcontributions'             => 'Obrisani suradnički doprinosi',
'deletedcontributions-title'       => 'Obrisani suradnički doprinosi',
'sp-deletedcontributions-contribs' => 'doprinosi',

# Special:LinkSearch
'linksearch'       => 'Vanjske poveznice',
'linksearch-pat'   => 'Uzorak traženja:',
'linksearch-ns'    => 'Imenski prostor:',
'linksearch-ok'    => 'Traži',
'linksearch-text'  => 'Možete koristiti džoker znakove poput "*.wikipedia.org".<br />Podržani su protokoli: <tt>$1</tt>',
'linksearch-line'  => '$1 poveznica s članka $2',
'linksearch-error' => 'Džoker znakovi se mogu rabiti samo na početku imena poslužitelja.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži suradnike počevši od:',
'listusers-submit'   => 'Prikaži',
'listusers-noresult' => 'Nema takvih suradnika.',
'listusers-blocked'  => '(blokiran)',

# Special:ActiveUsers
'activeusers'            => 'Popis aktivnih suradnika',
'activeusers-intro'      => 'Ovo je popis suradnika koji su napravili neku aktivnost u {{PLURAL:$1|zadnji $1 dan|zadnja $1 dana|zadnjih $1 dana}}.',
'activeusers-count'      => '{{PLURAL:$1|nedavna $1 izmjena|nedavne $1 izmjene|nedavnih $1 izmjena}} u {{PLURAL:$3|posljednji $3 dan|posljednja $3 dana|posljednjih $3 dana}}',
'activeusers-from'       => 'Prikaži suradnike počevši od:',
'activeusers-hidebots'   => 'Sakrij botove',
'activeusers-hidesysops' => 'Sakrij administratore',
'activeusers-noresult'   => 'Niti jedan suradnik nije nađen.',

# Special:Log/newusers
'newuserlogpage'              => 'Evidencija novih suradnika',
'newuserlogpagetext'          => 'Ispod je popis nedavno otvorenih suradničkih imena.',
'newuserlog-byemail'          => 'lozinka poslana e-poštom',
'newuserlog-create-entry'     => 'Novi suradnik',
'newuserlog-create2-entry'    => 'otvoren je novi suradnički račun $1',
'newuserlog-autocreate-entry' => 'Automatski stvoren račun',

# Special:ListGroupRights
'listgrouprights'                      => 'Prava suradničkih skupina',
'listgrouprights-summary'              => 'Ovo je popis suradničkih skupina određenih na ovoj wiki, s njihovim pripadajućim pravima.
Dodatne informacije o pojedinim pravim se mogu pronaći [[{{MediaWiki:Listgrouprights-helppage}}|ovdje]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dodijeljeno pravo</span>
* <span class="listgrouprights-revoked">Ukinuto pravo</span>',
'listgrouprights-group'                => 'Skupina',
'listgrouprights-rights'               => 'Prava',
'listgrouprights-helppage'             => 'Help:Suradničke skupine',
'listgrouprights-members'              => '(popis članova)',
'listgrouprights-addgroup'             => 'Moguće dodati {{PLURAL:$2|skupinu|skupine}}: $1',
'listgrouprights-removegroup'          => 'Moguće ukloniti {{PLURAL:$2|skupinu|skupine}}: $1',
'listgrouprights-addgroup-all'         => 'Moguće dodati sve skupine',
'listgrouprights-removegroup-all'      => 'Moguće ukloniti sve skupine',
'listgrouprights-addgroup-self'        => 'Dodaj {{PLURAL:$2|skupinu|skupine}} vlastitom računu: $1',
'listgrouprights-removegroup-self'     => 'Ukloni {{PLURAL:$2|skupinu|skupine}} iz vlastitog računa: $1',
'listgrouprights-addgroup-self-all'    => 'Dodaj sve skupine vlastitom računu',
'listgrouprights-removegroup-self-all' => 'Uklonite sve skupine iz vlastitog računa',

# E-mail user
'mailnologin'          => 'Nema adrese pošiljaoca',
'mailnologintext'      => 'Morate biti [[Special:UserLogin|prijavljeni]]
i imati valjanu adresu e-pošte u svojim [[Special:Preferences|postavkama]]
da bi mogli slati poštu drugim suradnicima.',
'emailuser'            => 'Pošalji e-poštu ovom suradniku',
'emailpage'            => 'Pošalji e-poštu suradniku',
'emailpagetext'        => 'Možete koristiti ovaj obrazac za slanje elektroničke pošte ovom suradniku.
E-mail adresa iz Vaših [[Special:Preferences|postavki]] nalazit će se u "From" polju poruke i primatelj će Vam moći izravno odgovoriti.',
'usermailererror'      => 'Sustav pošte javio je pogrešku:',
'defemailsubject'      => '{{SITENAME}} elektronička pošta (e-mail)',
'usermaildisabled'     => 'Suradnička e-pošta je onemogućena',
'usermaildisabledtext' => 'Ne možete slati e-poštu drugim suradnicima na ovom wikiju',
'noemailtitle'         => 'Nema adrese primaoca',
'noemailtext'          => 'Ovaj suradnik nije odredio valjanu e-mail adresu.',
'nowikiemailtitle'     => 'E-mail nije dozvoljen',
'nowikiemailtext'      => 'Ovaj suradnik je odlučio ne primati e-mail od drugih suradnika.',
'email-legend'         => 'Pošalji elektroničku poštu drugom suradniku projekta {{SITENAME}}',
'emailfrom'            => 'Od:',
'emailto'              => 'Za:',
'emailsubject'         => 'Tema:',
'emailmessage'         => 'Poruka:',
'emailsend'            => 'Pošalji',
'emailccme'            => 'Pošalji mi e-mailom kopiju moje poruke.',
'emailccsubject'       => 'Kopija Vaše poruke suradniku $1: $2',
'emailsent'            => 'E-mail poslan',
'emailsenttext'        => 'Vaša poruka je poslana.',
'emailuserfooter'      => 'Ovaj e-mail je poslan od $1 za $2 korištenjem "elektroničke pošte" s projekta {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Ostavljanje poruke sustava.',
'usermessage-editor'  => 'Uređivač sistemskih poruka',

# Watchlist
'watchlist'            => 'Moj popis praćenja',
'mywatchlist'          => 'Moj popis praćenja',
'watchlistfor2'        => 'Za $1 $2',
'nowatchlist'          => 'Na Vašem popisu praćenja nema nijednog članka.',
'watchlistanontext'    => 'Molimo Vas $1 kako biste mogli vidjeti ili uređivati Vaš popis praćenih stranica.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Morate biti [[Special:UserLogin|prijavljeni]]
za promjene u popisu praćenja.',
'addedwatch'           => 'Dodano u popis praćenja',
'addedwatchtext'       => "Stranica \"[[:\$1]]\" je dodana na Vaš [[Special:Watchlist|popis praćenja]].
Promjene na toj stranici i njenoj stranici za razgovor bit će prikazane na popisu praćenja, a stranica će biti ispisana '''podebljano''' u [[Special:RecentChanges|popisu nedavnih promjena]] kako biste je lakše primijetili. Ako poželite ukloniti stranicu s popisa praćenja, pritisnite \"Prekini praćenje\" u traci s naredbama.",
'removedwatch'         => 'Uklonjeno s popisa praćenja',
'removedwatchtext'     => 'Stranica "[[:$1]]" je uklonjena s [[Special:Watchlist|Vašeg popisa praćenja]].',
'watch'                => 'Prati',
'watchthispage'        => 'Prati ovu stranicu',
'unwatch'              => 'Prekini praćenje',
'unwatchthispage'      => 'Prekini praćenje',
'notanarticle'         => 'Nije članak',
'notvisiblerev'        => 'Izmjena je obrisana',
'watchnochange'        => 'Niti jedna od praćenih stranica nije promijenjena od Vašeg zadnjeg posjeta.',
'watchlist-details'    => '{{PLURAL:$1|$1 stranica|$1 stranice|$1 stranica}} se nalazi na popisu praćenja, ne brojeći stranice za razgovor.',
'wlheader-enotif'      => '* Uključeno je izvješćivanje e-mailom.',
'wlheader-showupdated' => "* Stranice koje su promijenjene od Vašeg zadnjeg posjeta prikazane su '''podebljano'''",
'watchmethod-recent'   => 'provjera nedavnih promjena praćenih stranica',
'watchmethod-list'     => 'provjera praćanih stranica za nedavne promjene',
'watchlistcontains'    => 'Vaš popis praćenja sadrži $1 {{PLURAL:$1|stranicu|stranice|stranica}}.',
'iteminvalidname'      => "Problem s izborom '$1', ime nije valjano...",
'wlnote'               => "Ovdje {{PLURAL:$1|je posljednja $1 promjena|su posljednje $1 promjene|je posljednjih $1 promjena}} u {{PLURAL:$2|posljednjem '''$2''' satu|posljednja '''$2''' sata|posljednjih '''$2''' sati}}.",
'wlshowlast'           => 'Prikaži zadnjih $1 sati $2 dana $3',
'watchlist-options'    => 'Izbornik popisa praćenja',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Pratim...',
'unwatching' => 'Prestajem pratiti...',

'enotif_mailer'                => '{{SITENAME}} - izvješća o promjenama',
'enotif_reset'                 => 'Označi sve stranice kao već posjećene',
'enotif_newpagetext'           => 'Ovo je nova stranica.',
'enotif_impersonal_salutation' => '{{SITENAME}} suradnik',
'changed'                      => 'promijenio',
'created'                      => 'stvorio',
'enotif_subject'               => '{{SITENAME}}: Stranicu $PAGETITLE je $CHANGEDORCREATED suradnik $PAGEEDITOR',
'enotif_lastvisited'           => 'Pogledaj $1 za promjene od zadnjeg posjeta.',
'enotif_lastdiff'              => 'Pogledajte $1 kako biste mogli vidjeti tu izmjenu.',
'enotif_anon_editor'           => 'neprijavljeni suradnik $1',
'enotif_body'                  => 'Poštovani $WATCHINGUSERNAME,

stranicu na projektu {{SITENAME}} s naslovom $PAGETITLE je dana $PAGEEDITDATE $CHANGEDORCREATED suradnik $PAGEEDITOR, pogledajte $PAGETITLE_URL za trenutačnu inačicu.

$NEWPAGE

Sažetak urednika: $PAGESUMMARY $PAGEMINOREDIT

Možete se javiti uredniku:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Do Vašeg ponovnog posjeta stranici nećete dobivati daljnje obavijesti.
Postavke za izvješćivanje možete resetirati za sve praćene stranice svog popisa praćenja.

            Vaš sustav izvješćivanja {{SITENAME}}.

--
Za promjene svog popisa praćenja, posjetite
{{fullurl:{{#special:Watchlist}}/edit}}

Za brisanje stranica iz svog popisa praćenja, posjetite
$UNWATCHURL

Za povratne informacije i pomoć posjetite:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Izbriši stranicu',
'confirm'                => 'Potvrdi',
'excontent'              => "sadržaj je bio: '$1'",
'excontentauthor'        => "sadržaj je bio: '$1' (a jedini urednik '$2')",
'exbeforeblank'          => "sadržaj prije brisanja je bio: '$1'",
'exblank'                => 'stranica je bila prazna',
'delete-confirm'         => 'Obriši "$1"',
'delete-legend'          => 'Izbriši',
'historywarning'         => "'''Upozorenje''':  Stranica koju želite obrisati ima starije izmjene s približno $1 {{PLURAL:$1|inačicom|inačice|inačica}}:",
'confirmdeletetext'      => 'Zauvijek ćete izbrisati stranicu ili sliku zajedno s prijašnjim inačicama.
Molim potvrdite svoju namjeru, da razumijete posljedice i da ovo radite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'actioncomplete'         => 'Zahvat završen',
'actionfailed'           => 'Radnja nije uspjela',
'deletedtext'            => '"<nowiki>$1</nowiki>" je izbrisana.
Vidi $2 za evidenciju nedavnih brisanja.',
'deletedarticle'         => 'izbrisano "$1"',
'suppressedarticle'      => 'sakriven "[[$1]]"',
'dellogpage'             => 'Evidencija_brisanja',
'dellogpagetext'         => 'Dolje je popis nedavnih brisanja.
Sva vremena su prema poslužiteljevom vremenu.',
'deletionlog'            => 'evidencija brisanja',
'reverted'               => 'Vraćeno na prijašnju inačicu',
'deletecomment'          => 'Razlog:',
'deleteotherreason'      => 'Drugi/dodatni razlog:',
'deletereasonotherlist'  => 'Drugi razlog',
'deletereason-dropdown'  => '*Razlozi brisanja stranica
** Zahtjev autora
** Kršenje autorskih prava
** Vandalizam',
'delete-edit-reasonlist' => 'Uredi razloge brisanja',
'delete-toobig'          => 'Ova stranica ima veliku povijest uređivanja, preko $1 {{PLURAL:$1|promjene|promjena}}. Brisanje takvih stranica je ograničeno da se onemoguće slučajni problemi u radu {{SITENAME}}.',
'delete-warning-toobig'  => 'Ova stranica ima veliku povijest uređivanja, preko $1 {{PLURAL:$1|promjene|promjena}}. Brisanje može poremetiti bazu podataka {{SITENAME}}; postupajte s oprezom.',

# Rollback
'rollback'          => 'Ukloni posljednju promjenu',
'rollback_short'    => 'Ukloni',
'rollbacklink'      => 'ukloni',
'rollbackfailed'    => 'Uklanjanje neuspješno',
'cantrollback'      => 'Ne mogu ukloniti posljednju promjenu, postoji samo jedna promjena.',
'alreadyrolled'     => 'Ne mogu ukloniti posljednju promjenu članka [[:$1]] koju je napravio  [[User:$2|$2]] ([[User talk:$2|Razgovor]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); netko je već promijenio stranicu ili uklonio promjenu.

Posljednju promjenu napravio je [[User:$3|$3]] ([[User talk:$3|Razgovor]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Sažetak promjene je bio: \"''\$1''\".",
'revertpage'        => 'Uklonjena promjena suradnika $2, vraćeno na zadnju inačicu suradnika $1',
'revertpage-nouser' => 'Vraćene izmjene suradnika (suradničko ime uklonjeno) na posljednju inačicu suradnika [[User:$1|$1]]',
'rollback-success'  => 'Uklonjeno uređivanje suradnika $1; vraćeno na zadnju inačicu suradnika $2.',

# Edit tokens
'sessionfailure-title' => 'Prekid sesije',
'sessionfailure'       => 'Uočili smo problem s Vašom prijavom. Zadnja naredba nije izvršena kako bi se izbjegla zloupotreba. Molimo Vas da se u pregledniku vratite natrag na prethodnu stranicu, ponovno je učitate i zatim pokušate opet.',

# Protect
'protectlogpage'              => 'Evidencija zaštićivanja',
'protectlogtext'              => 'Ispod je evidencija zaštićivanja i uklanjanja zaštite pojedinih stranica.
Pogledajte [[Special:ProtectedPages|zaštićene stranice]] za popis trenutačno zaštićenih stranica.',
'protectedarticle'            => 'članak "[[$1]]" je zaštićen',
'modifiedarticleprotection'   => 'promijenjen stupanj zaštite za "[[$1]]"',
'unprotectedarticle'          => 'uklonjena zaštita članka "[[$1]]"',
'movedarticleprotection'      => 'premještene postavke zaštite s "[[$2]]" na "[[$1]]"',
'protect-title'               => 'Zaštićujem "$1"',
'prot_1movedto2'              => '$1 premješteno na $2',
'protect-legend'              => 'Potvrda zaštite',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Trajanje zaštite:',
'protect_expiry_invalid'      => 'Upisani vremenski rok nije valjan.',
'protect_expiry_old'          => 'Vrijeme isteka je u prošlosti.',
'protect-unchain-permissions' => 'Otključaj daljnje mogućnosti zaštićivanja',
'protect-text'                => "Ovdje možete pregledati i promijeniti razinu zaštite za stranicu '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Ne možete mijenjati nivo zaštite dok ste blokirani.
Slijede postavke stranice '''$1''':",
'protect-locked-dblock'       => "Razina zaštite ne može biti promijenjena jer je baza zaključana.
Slijede postavke stranice '''$1''':",
'protect-locked-access'       => "Nemate ovlasti za mijenjanje razine zaštite.
Slijede trenutačne postavke stranice '''$1''':",
'protect-cascadeon'           => 'Ova stranica je zaštićena jer je uključena u {{PLURAL:$1|stranicu, koja ima|stranice, koje imaju|stranice, koje imaju}} uključenu prenosivu zaštitu. Možete promijeniti stupanj zaštite ove stranice, no to neće utjecati na prenosivu zaštitu.',
'protect-default'             => 'Omogući svim suradnicima',
'protect-fallback'            => 'Potrebno je imati "$1" ovlasti',
'protect-level-autoconfirmed' => 'Blokiraj nove i neprijavljene suradnike',
'protect-level-sysop'         => 'Samo administratori',
'protect-summary-cascade'     => 'prenosiva zaštita',
'protect-expiring'            => 'istječe $1 (UTC)',
'protect-expiry-indefinite'   => 'neograničeno',
'protect-cascade'             => 'Prenosiva zaštita - zaštiti sve stranice koje su uključene u ovu.',
'protect-cantedit'            => 'Ne možete mijenjati razinu zaštite ove stranice, jer nemate prava uređivati ju.',
'protect-othertime'           => 'Drugo vrijeme:',
'protect-othertime-op'        => 'drugo vrijeme',
'protect-existing-expiry'     => 'Postojeće vrijeme zaštite: $3, $2',
'protect-otherreason'         => 'Drugi/dodatni razlog:',
'protect-otherreason-op'      => 'drugi/dodatni razlog',
'protect-dropdown'            => '*Najčešći razlozi zaštićivanja
** Učestalo vandaliziranje
** Učestalo spamiranje
** Neproduktivni uređivački rat
** Zatrpavanje nedavnih promjena',
'protect-edit-reasonlist'     => 'Uredi razloge zaštićivanja',
'protect-expiry-options'      => '1 sat:1 hour,1 dan:1 day,1 tjedan:1 week,2 tjedna:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godina:1 year,neograničeno:infinite',
'restriction-type'            => 'Dopuštenje:',
'restriction-level'           => 'Stupanj ograničenja:',
'minimum-size'                => 'Najmanja veličina',
'maximum-size'                => 'Najveća veličina:',
'pagesize'                    => '(bajtova)',

# Restrictions (nouns)
'restriction-edit'   => 'Uređivanje',
'restriction-move'   => 'Premještanje',
'restriction-create' => 'Stvori',
'restriction-upload' => 'Postavi',

# Restriction levels
'restriction-level-sysop'         => 'samo administratori',
'restriction-level-autoconfirmed' => 'samo prijavljeni suradnici',
'restriction-level-all'           => 'sve razine',

# Undelete
'undelete'                     => 'Vrati izbrisanu stranicu',
'undeletepage'                 => 'Vidi i/ili vrati izbrisane stranice',
'undeletepagetitle'            => "'''Sljedeći sadržaj se sastoji od izbrisanih izmjena [[:$1]]'''.",
'viewdeletedpage'              => 'Pogledaj izbrisanu stranicu',
'undeletepagetext'             => '{{PLURAL:$1|Sljedeća stranica je obrisana, ali se još uvijek nalazi|Sljedećih $1 stranica su obrisane, ali se još uvijek nalaze}} u bazi i mogu se obnoviti.
Baza se povremeno čisti od ovakvih stranica.',
'undelete-fieldset-title'      => 'Vrati izmjene',
'undeleteextrahelp'            => "Da biste vratili cijelu stranicu, ostavite sve ''kućice'' neoznačene i kliknite '''Vrati!'''. Ako želite vratiti određene izmjene, označite ih i kliknite '''Vrati!'''. Klik na gumb '''Očisti''' će odznačiti sve ''kućice'' i obrisati polje za komentar.",
'undeleterevisions'            => '$1 {{PLURAL:$1|inačica je arhivirana|inačice su arhivirane|inačica je arhivirano}}',
'undeletehistory'              => 'Ako vratite izbrisanu stranicu, bit će vraćene i sve prijašnje promjene. Ako je u međuvremenu stvorena nova stranica s istim imenom, vraćena stranica bit će upisana kao prijašnja promjena sadašnje.',
'undeleterevdel'               => 'Vraćanje stranice neće biti izvršeno ako je rezultat toga djelomično brisanje zadnjeg uređivanja.
U takvim slučajevima morate isključiti ili otkriti najnovije obrisane promjene.',
'undeletehistorynoadmin'       => 'Ovaj je članak izbrisan. Razlog za brisanje prikazan je u donjem sažetku, zajedno s
detaljima o suradnicima koji su uređivali ovu stranicu prije brisanja.
Tekst izbrisanih inačica dostupan je samo administratorima.',
'undelete-revision'            => 'Izbrisana inačica članka $1 (od $4, $5) izbrisao $3:',
'undeleterevision-missing'     => 'Nevaljana ili nepostojeća promjena. Poveznica je nevaljana,
ili je promjena vraćena ili uklonjena iz arhive.',
'undelete-nodiff'              => 'Prethodne promjene nisu nađene.',
'undeletebtn'                  => 'Vrati!',
'undeletelink'                 => 'vidi/vrati',
'undeleteviewlink'             => 'pregled',
'undeletereset'                => 'Očisti',
'undeleteinvert'               => 'Obrni odabir',
'undeletecomment'              => 'Razlog:',
'undeletedarticle'             => 'vraćena stranica "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|$1 inačica vraćena|$1 inačice vraćene|$1 inačica vraćeno}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|$1 promjena|$1 promjene|$1 promjena}} i {{PLURAL:$2|$2 datoteka vraćena|$2 datototeke vraćene|$2 datoteka vraćeno}}',
'undeletedfiles'               => '{{PLURAL:$1|$1 datoteka vraćena|$1 datoteke vraćene|$1 datoteka vraćeno}}',
'cannotundelete'               => 'Vraćanje obrisane inačice nije uspjelo; netko drugi je stranicu već vratio.',
'undeletedpage'                => "'''$1 je vraćena'''

Pogledajte [[Special:Log/delete|evidenciju brisanja]] za zapise nedavnih brisanja i vraćanja.",
'undelete-header'              => 'Pogledaj [[Special:Log/delete|evidenciju brisanja]] za nedavno obrisane stranice.',
'undelete-search-box'          => 'Pretraži obrisane stranice',
'undelete-search-prefix'       => 'Pretraži stranice koje počinju s:',
'undelete-search-submit'       => 'Pretraži',
'undelete-no-results'          => 'Nije pronađena odgovarajuća stranica u arhivu brisanja.',
'undelete-filename-mismatch'   => "Ne mogu vratiti inačicu datoteke s vremenom i datumom $1: imena se ne slažu (''filename mismatch'')",
'undelete-bad-store-key'       => 'Ne mogu vratiti inačicu datoteke s vremenom i datumom $1: datoteka ne postoji (obrisana je) prije Vašeg pokušaja brisanja.',
'undelete-cleanup-error'       => 'Pogreška pri brisanju nekorištene arhivske datoteke "$1".',
'undelete-missing-filearchive' => 'Vraćanje arhivske datoteke s oznakom $1 nije moguće jer ne postoji u bazi podataka. Moguće je već vraćena.',
'undelete-error-short'         => 'Pogreška pri vraćanju datoteke: $1',
'undelete-error-long'          => 'Dogodila se pogreška pri vraćanju datoteke:

$1',
'undelete-show-file-confirm'   => 'Jeste li sigurni da želite vidjeti izbrisanu inačicu datoteke "<nowiki>$1</nowiki>" od $2 u $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'      => 'Imenski prostor:',
'invert'         => 'Sve osim odabranog',
'blanknamespace' => '(Glavni)',

# Contributions
'contributions'       => 'Doprinosi suradnika',
'contributions-title' => 'Suradnički doprinosi za $1',
'mycontris'           => 'Moji doprinosi',
'contribsub2'         => 'Za $1 ($2)',
'nocontribs'          => 'Nema promjena koje udovoljavaju ovim kriterijima.',
'uctop'               => ' (vrh)',
'month'               => 'Od mjeseca (i ranije):',
'year'                => 'Od godine (i ranije):',

'sp-contributions-newbies'             => 'Prikaži samo doprinose novih suradnika',
'sp-contributions-newbies-sub'         => 'Za nove suradnike',
'sp-contributions-newbies-title'       => 'Doprinosi novih suradnika',
'sp-contributions-blocklog'            => 'Evidencija blokiranja',
'sp-contributions-deleted'             => 'obrisani suradnički doprinosi',
'sp-contributions-uploads'             => "snimljene datoteke (''uploads'')",
'sp-contributions-logs'                => 'evidencije',
'sp-contributions-talk'                => 'razgovor',
'sp-contributions-userrights'          => 'upravljanje suradničkim pravima',
'sp-contributions-blocked-notice'      => 'Ovaj suradnik je trenutačno blokiran. Posljednja stavka evidencije blokiranja navedena je niže kao napomena:',
'sp-contributions-blocked-notice-anon' => 'Ova IP adresa je trenutačno blokirana.
Posljednja stavka evidencije blokiranja navedena je niže kao napomena:',
'sp-contributions-search'              => 'Pretraži doprinose',
'sp-contributions-username'            => 'IP adresa ili suradnik:',
'sp-contributions-toponly'             => 'Prikaži samo najnovije izmjene',
'sp-contributions-submit'              => 'Traži',

# What links here
'whatlinkshere'            => 'Što vodi ovamo',
'whatlinkshere-title'      => 'Stranice koje vode na "$1"',
'whatlinkshere-page'       => 'Stranica:',
'linkshere'                => 'Sljedeće stranice povezuju ovamo ([[:$1]]):',
'nolinkshere'              => 'Nijedna stranica ne vodi ovamo (tj. nema poveznica na stranicu [[:$1]]).',
'nolinkshere-ns'           => "Nijedna stranica ne vodi na '''[[:$1]]''' u odabranom imenskom prostoru.",
'isredirect'               => 'stranica za preusmjeravanje',
'istemplate'               => 'kao predložak',
'isimage'                  => 'poveznica slike',
'whatlinkshere-prev'       => '{{PLURAL:$1|prethodna|prethodne|prethodnih}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|slijedeća|slijedeće|slijedećih}} $1',
'whatlinkshere-links'      => '← poveznice',
'whatlinkshere-hideredirs' => '$1 preusmjeravanja',
'whatlinkshere-hidetrans'  => '$1 transkluzije',
'whatlinkshere-hidelinks'  => '$1 poveznice',
'whatlinkshere-hideimages' => '$1 poveznice slike',
'whatlinkshere-filters'    => 'Filteri',

# Block/unblock
'blockip'                         => 'Blokiraj suradnika',
'blockip-title'                   => 'Blokiraj suradnika',
'blockip-legend'                  => 'Blokiraj suradnika',
'blockiptext'                     => 'Koristite donji obrazac za blokiranje pisanja pojedinih suradnika ili IP adresa .
To biste trebali raditi samo zbog sprječavanja vandalizma i u skladu
sa [[{{MediaWiki:Policy-url}}|smjernicama]].
Upišite i razlog za ovo blokiranje (npr. stranice koje su
vandalizirane).',
'ipaddress'                       => 'IP adresa',
'ipadressorusername'              => 'IP adresa ili suradničko ime',
'ipbexpiry'                       => 'Rok (na engleskom)',
'ipbreason'                       => 'Razlog:',
'ipbreasonotherlist'              => 'Drugi razlog',
'ipbreason-dropdown'              => "*Najčešći razlozi za blokiranje
** Netočne informacije
** Uklanjanje sadržaja stranica
** Postavljanje ''spam'' vanjskih poveznica
** Grafiti
** Osobni napadi (ili napadačko ponašanje)
** Čarapare (zloporaba više suradničkih računa)
** Neprihvatljivo suradničko ime",
'ipbanononly'                     => 'Blokiraj samo neprijavljene suradnike',
'ipbcreateaccount'                => 'Spriječi otvaranje suradničkih računa',
'ipbemailban'                     => 'Onemogući blokiranom suradniku slanje e-mailova',
'ipbenableautoblock'              => 'Automatski blokiraj IP adrese koje koristi ovaj suradnik',
'ipbsubmit'                       => 'Blokiraj ovog suradnika',
'ipbother'                        => 'Neki drugi rok (na engleskom, npr. 6 days):',
'ipboptions'                      => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 tjedan:1 week,2 tjedna:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godine:1 year,neograničeno:infinite',
'ipbotheroption'                  => 'drugo',
'ipbotherreason'                  => 'Drugi/dodatni razlog:',
'ipbhidename'                     => 'Sakrij suradničko ime iz uređivanja i popisa',
'ipbwatchuser'                    => 'Prati suradničku stranicu i stranicu za razgovor ovog suradnika',
'ipballowusertalk'                => 'Omogući suradniku uređivanje vlastite stranice za razgovor',
'ipb-change-block'                => 'Ponovno blokiraj suradnika s ovim postavkama',
'badipaddress'                    => 'Nevaljana IP adresa.',
'blockipsuccesssub'               => 'Uspješno blokirano',
'blockipsuccesstext'              => 'Suradnik [[Special:Contributions/$1|$1]] je blokiran.<br />
Pogledaj [[Special:IPBlockList|popis blokiranih IP adresa]] za pregled.',
'ipb-edit-dropdown'               => 'Uredi razloge blokiranja',
'ipb-unblock-addr'                => 'Odblokiraj $1',
'ipb-unblock'                     => 'Odblokiraj suradničko ime ili IP adresu',
'ipb-blocklist'                   => 'Vidi postojeća blokiranja',
'ipb-blocklist-contribs'          => 'Doprinosi za $1',
'unblockip'                       => 'Deblokiraj suradnika',
'unblockiptext'                   => 'Ovaj se obrazac koristi za vraćanje prava na pisanje prethodno blokiranoj IP adresi.',
'ipusubmit'                       => 'Ukloni ovaj blok',
'unblocked'                       => '[[User:$1|$1]] je deblokiran',
'unblocked-id'                    => 'Blok $1 je uklonjen',
'ipblocklist'                     => 'Popis blokiranih IP adresa i suradničkih računa',
'ipblocklist-legend'              => 'Pronađi blokiranog suradnika',
'ipblocklist-username'            => 'Ime suradnika ili IP adresa:',
'ipblocklist-sh-userblocks'       => '$1 blokirane račune',
'ipblocklist-sh-tempblocks'       => '$1 privremena blokiranja',
'ipblocklist-sh-addressblocks'    => '$1 blokirane IP-eve',
'ipblocklist-submit'              => 'Traži',
'ipblocklist-localblock'          => 'Lokalno blokiranje',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Ostalo blokiranje|Ostala blokiranja}}',
'blocklistline'                   => '$1, $2 je blokirao $3 ($4)',
'infiniteblock'                   => 'neograničeno',
'expiringblock'                   => 'istječe $1 u $2',
'anononlyblock'                   => 'samo IP adrese',
'noautoblockblock'                => 'blokiranje samoga sebe je onemogućeno',
'createaccountblock'              => 'blokirano stvaranje suradničkog računa',
'emailblock'                      => 'e-mail je blokiran',
'blocklist-nousertalk'            => 'bez uređivanja vlastite stranice za razgovor',
'ipblocklist-empty'               => 'Popis blokiranja je prazan.',
'ipblocklist-no-results'          => 'Tražena IP adresa ili suradničko ime nije blokirano.',
'blocklink'                       => 'blokiraj',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'promijeni blokiranje',
'contribslink'                    => 'doprinosi',
'autoblocker'                     => 'Automatski ste blokirani jer je Vašu IP adresu nedavno koristio "[[User:$1|$1]]" koji je blokiran zbog: "$2".',
'blocklogpage'                    => 'Evidencija blokiranja',
'blocklog-showlog'                => 'Ovaj suradnik je ranije blokiran.
Evidencija blokiranja je prikazan ispod kao napomena:',
'blocklog-showsuppresslog'        => 'Ovaj suradnik je ranije blokiran i skriven.
Zapisnik skrivanja je prikazan ispod kao napomena:',
'blocklogentry'                   => 'Blokiran je "[[$1]]" na rok $2 $3',
'reblock-logentry'                => 'promijenjene postavke blokiranja za [[$1]] na rok od $2 $3',
'blocklogtext'                    => 'Ovo je evidencija blokiranja i deblokiranja. Na popisu nema automatski blokiranih IP adresa. Za popis trenutačnih zabrana i blokiranja vidi [[Special:IPBlockList|popis IP blokiranja]].',
'unblocklogentry'                 => 'Deblokiran "$1"',
'block-log-flags-anononly'        => 'samo za neprijavljene suradnike',
'block-log-flags-nocreate'        => 'otvaranje novih suradničkih imena nije moguće',
'block-log-flags-noautoblock'     => 'autoblok je onemogućen',
'block-log-flags-noemail'         => 'e-mail je blokiran',
'block-log-flags-nousertalk'      => 'bez uređivanja vlastite stranice za razgovor',
'block-log-flags-angry-autoblock' => 'Poboljšan autoblok uključen',
'block-log-flags-hiddenname'      => 'suradničko ime skriveno',
'range_block_disabled'            => 'Isključena je administratorska naredba za blokiranje raspona IP adresa.',
'ipb_expiry_invalid'              => 'Vremenski rok nije valjan.',
'ipb_expiry_temp'                 => 'Sakriveni računi bi trebali biti trajno blokirani.',
'ipb_hide_invalid'                => 'Ne može se sakriti ovaj račun, možda ima previše uređivanja.',
'ipb_already_blocked'             => '"$1" je već blokiran',
'ipb-needreblock'                 => '== Već blokiran ==
$1 je već blokiran. Želite promijeniti postavke blokiranja?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Ostalo blokiranje|Ostala blokiranja}}',
'ipb_cant_unblock'                => 'Pogreška: blok ID $1 nije nađen. Moguće je da je suradnik već odblokiran.',
'ipb_blocked_as_range'            => 'Pogreška: IP adresa $1 nije blokirana direktno te stoga ne može biti odblokirana. Blokirana je kao dio opsega $2, koji može biti odblokiran.',
'ip_range_invalid'                => 'Raspon IP adresa nije valjan.',
'ip_range_toolarge'               => 'Opsezi blokiranja veći od /$1 nisu dozvoljeni.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Zaštita od otvorenih posrednika (proxyja)',
'proxyblocker-disabled'           => 'Ova funkcija je onemogućena.',
'proxyblockreason'                => 'Vaša je IP adresa blokirana jer se radi o otvorenom posredniku (proxyju). Molimo stupite u vezu s Vašim davateljem internetskih usluga (ISP-om) ili službom tehničke podrške i obavijestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess'               => 'Napravljeno.',
'sorbsreason'                     => 'Vaša IP adresa je na popisu otvorenih posrednika na poslužitelju DNSBL.',
'sorbs_create_account_reason'     => 'Vaša IP adresa je na popisu otvorenih posrednika na poslužitelju DNSBL. Ne možete otvoriti račun.',
'cant-block-while-blocked'        => 'Ne možete blokirati druge suradnike dok ste blokirani.',
'cant-see-hidden-user'            => 'Korisnik kojeg pokušavate blokirati je već blokiran i sakriven. Pošto nemate prava hideuser (sakrivanje korisnika), ne možete vidjeti ni urediti korisnikovu blokadu.',
'ipbblocked'                      => 'Ne možete blokirati ili odblokirati druge suradnike, jer ste blokirani',
'ipbnounblockself'                => 'Nije Vam dopušteno odblokirati se',

# Developer tools
'lockdb'              => 'Zaključaj bazu podataka',
'unlockdb'            => 'Otključaj bazu podataka',
'lockdbtext'          => 'Zaključavanjem baze će se suradnicima onemogućiti uređivanje stranica, mijenjanje postavki i popisa praćenja, i sve drugo što zahtijeva promjene u bazi podataka.
Molim potvrdite svoju namjeru zaključavanja, te da ćete otključati bazu čim završite s održavanjem.',
'unlockdbtext'        => 'Otključavanjem baze omogućit ćete suradnicima uređivanje stranica,
mijenjanje postavki, uređivanje popisa praćenja i druge stvari koje zahtijevaju promjene u bazi. Molim potvrdite svoju namjeru.',
'lockconfirm'         => 'Da, sigurno želim zaključati bazu.',
'unlockconfirm'       => 'Da, sigurno želim otključati bazu.',
'lockbtn'             => 'Zaključaj bazu podataka',
'unlockbtn'           => 'Otključaj bazu podataka',
'locknoconfirm'       => 'Niste potvrdili svoje namjere.',
'lockdbsuccesssub'    => 'Zaključavanje baze podataka uspjelo',
'unlockdbsuccesssub'  => 'Otključavanje baze podataka uspjelo',
'lockdbsuccesstext'   => 'Baza podataka je zaključana.
<br />Ne zaboravite otključati po završetku održavanja.',
'unlockdbsuccesstext' => 'Baza podataka je otključana.',
'lockfilenotwritable' => "Web poslužitelj ne može pisati u ''lock'' datoteku. Za zaključavanje ili otključavanje baze podataka, web poslužitelj mora moći pisati u ovu datoteku.",
'databasenotlocked'   => 'Baza podataka nije zaključana.',

# Move page
'move-page'                    => 'Premjesti $1',
'move-page-legend'             => 'Premjesti stranicu',
'movepagetext'                 => "Korištenjem ovog obrasca ćete preimenovati stranicu i premjestiti sve stare izmjene na novo ime.
Stari će se naslov pretvoriti u stranicu koja automatski preusmjerava na novi naslov.
Možete odabrati automatsko ažuriranje preusmjeravanja na originalni naslov.
Ako se ne odlučite na to, provjerite [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|neispravna preusmjeravanja]].
Dužni ste provjeriti da sve poveznice i dalje nastave voditi na prave stranice.

Stranica se '''neće''' premjestiti ako već postoji stranica s novim naslovom, osim u slučaju prazne stranice ili stranice za preusmjeravanje koja nema nikakvih starih izmjena.
To znači: 1. ako pogriješite, možete opet preimenovati stranicu na stari naslov, 2. ne može se dogoditi da izbrišete neku postojeću stranicu.

'''Upozorenje!'''
Ovo može biti drastična i neočekivana promjena kad su u pitanju popularne stranice. Molimo dobro razmislite prije nego što preimenujete stranicu.",
'movepagetext-noredirectfixer' => "Pomoću donjeg obrasca ćete preimenovati stranicu i premjestiti sve stare izmjene na novo ime. 
Stari će se naslov pretvoriti u stranicu koja automatski preusmjerava na novi naslov. 
Budite sigurni da ste provjerili [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|nevaljana preusmjeravanja]]. 
Vi ste odgovorni za to da poveznice i dalje povezuju tamo gdje treba.

Imajte na umu da će stranica'''neće''' premjestiti ako već postoji stranica s novim naslovom, osim u slučaju prazne stranice ili stranice za preusmjeravanje koja nema nikakvih starih izmjena. 
To znači da možete preimenovati stranicu natrag do mjesta gdje je preimenovan iz ako pogriješite, i ne možete prepisati postojeću stranicu. 

 '''Upozorenje!''' 
Ovo može biti drastična i neočekivana promjena kad su u pitanju popularne stranice; 
budite sigurni da razumijete posljedice ove akcije prije nastavka.",
'movepagetalktext'             => "Stranica za razgovor, ako postoji, automatski će se premjestiti zajedno sa stranicom koju premještate. '''Stranica za razgovor neće se premjestiti ako:'''
*premještate stranicu iz jednog prostora u drugi,
*pod novim imenom već postoji stranica za razgovor s nekim sadržajem, ili
*maknete kvačicu u kućici na dnu ove stranice.

U tim slučajevima ćete morati sami premjestiti ili iskopirati stranicu za razgovor,
ako to želite.",
'movearticle'                  => 'Premjesti stranicu',
'moveuserpage-warning'         => "'''Upozorenje:''' Premještate suradničku stranicu. Imajte na umu da će stranica biti premještena, ali suradnik ''neće'' biti preimenovan.",
'movenologin'                  => 'Niste prijavljeni',
'movenologintext'              => 'Ako želite premjestiti stranicu morate biti [[Special:UserLogin|prijavljeni]].',
'movenotallowed'               => 'Nemate pravo premještanja stranica.',
'movenotallowedfile'           => 'Nemate ovlasti za premještanje datoteka.',
'cant-move-user-page'          => 'Nemate dopuštenja za premještanje root suradničkih stranica.',
'cant-move-to-user-page'       => 'Nemate dopuštenje za premještanje stranice na suradničku stranicu (osim kao podstranicu)',
'newtitle'                     => 'Na novi naslov',
'move-watch'                   => 'Prati ovu stranicu',
'movepagebtn'                  => 'Premjesti stranicu',
'pagemovedsub'                 => 'Premještanje uspjelo',
'movepage-moved'               => '\'\'\'"$1" je premješteno na "$2"\'\'\'',
'movepage-moved-redirect'      => 'Napravljeno je preusmjeravanje.',
'movepage-moved-noredirect'    => 'Stvaranje preusmjeravanja je izostavljeno.',
'articleexists'                => 'Stranica pod tim imenom već postoji ili ime koje ste odabrali nije u skladu s pravilima.
Molimo odaberite drugo ime.',
'cantmove-titleprotected'      => 'Ne možete premjestiti ovu stranicu na ovo mjesto, jer je novi naslov zaštićen od kreiranja',
'talkexists'                   => "'''Sama stranica je uspješno prenesena, ali stranicu za razgovor nije bilo moguće prenijeti jer na odredištu već postoji stranica za razgovor. Molimo da ih ručno spojite.'''",
'movedto'                      => 'premješteno na',
'movetalk'                     => 'Premjesti i njezinu stranicu za razgovor ako je moguće.',
'move-subpages'                => 'Premjesti podstranice (na $1)',
'move-talk-subpages'           => 'Premjesti podstranice od stranice za razgovor (na $1)',
'movepage-page-exists'         => 'Stranica $1 već postoji i ne može biti automatski prepisana',
'movepage-page-moved'          => 'Stranica $1 je premještena na $2.',
'movepage-page-unmoved'        => 'Stranica $1 nije mogla biti premještena na $2.',
'movepage-max-pages'           => 'Najveća količina od $1 {{PLURAL:$1|stranice|stranica}} je premještena i više od toga neće biti automatski premješteno.',
'1movedto2'                    => '[[$1]] premješteno na [[$2]]',
'1movedto2_redir'              => '[[$1]] premješteno na [[$2]] preko postojećeg preusmjeravanja',
'move-redirect-suppressed'     => 'izostavljeno preusmjeravanje',
'movelogpage'                  => 'Evidencija premještanja',
'movelogpagetext'              => 'Ispod je popis premještenih stranica.',
'movesubpage'                  => '{{PLURAL:$1|Podstranica|Podstranice}}',
'movesubpagetext'              => 'Ova stranica ima $1 {{PLURAL:$1|podstarnicu|podstranice}} koje su prikazane ispod.',
'movenosubpage'                => 'Ova stranica nema podstranica.',
'movereason'                   => 'Razlog:',
'revertmove'                   => 'vrati',
'delete_and_move'              => 'Izbriši i premjesti',
'delete_and_move_text'         => '==Nužno brisanje==

Odredišni članak "[[:$1]]" već postoji. Želite li ga obrisati da biste napravili mjesto za premještaj?',
'delete_and_move_confirm'      => 'Da, izbriši stranicu',
'delete_and_move_reason'       => 'Obrisano kako bi se napravilo mjesta za premještaj.',
'selfmove'                     => 'Izvorni i odredišni naslov su isti; ne mogu premjestiti stranicu na nju samu.',
'immobile-source-namespace'    => 'Ne mogu premjestiti stranice u imenski prostor "$1"',
'immobile-target-namespace'    => 'Ne mogu premjestiti stranice u imenski prostor "$1"',
'immobile-target-namespace-iw' => 'Međuwiki poveznica nije valjano odredište za premještanje stranice.',
'immobile-source-page'         => 'Ova stranica je se ne može premjestiti.',
'immobile-target-page'         => 'Ne mogu premjestiti na željeni naslov.',
'imagenocrossnamespace'        => 'Datoteka ne može biti premještena u imenski prostor koji nije za datoteke',
'nonfile-cannot-move-to-file'  => 'Ne mogu premjestiti nešto što nije datoteka u imenski prostor za datoteke',
'imagetypemismatch'            => 'Ekstenzija nove datoteke se ne poklapa sa svojim tipom.',
'imageinvalidfilename'         => 'Ciljano ime datoteke je nevaljano',
'fix-double-redirects'         => 'Ažuriraj sva preusmjeravanja koja vode na originalni naslov',
'move-leave-redirect'          => 'Ostavi preusmjeravanje',
'protectedpagemovewarning'     => "'''Upozorenje:''' Ova je stranica zaključana tako da je mogu premjestiti samo suradnici s administratorskim pravima.
Posljednja stavka u evidenciji navedena je niže kao napomena:",
'semiprotectedpagemovewarning' => "'''Napomena:''' Ova je stranica zaključana tako da je samo prijavljeni suradnici mogu premjestiti.
Posljednja stavka u evidenciji navedena je niže kao napomena:",
'move-over-sharedrepo'         => '== Datoteka postoji ==
[[:$1]] postoji na zajednički korištenom repozitoriju. Premještanje datoteke na ovaj naslov će prepisati zajednički korištenu datoteku.',
'file-exists-sharedrepo'       => 'Naziv datoteke koje ste odabrali već se rabi na zajednički korištenom repozitoriju.
Molimo odaberite drugo ime.',

# Export
'export'            => 'Izvezi stranice',
'exporttext'        => 'Možete izvesti tekst i prijašnje promjene jedne ili više stranica uklopljene u XML kod. U budućim verzijama MediaWiki softvera bit će moguće uvesti ovakvu stranicu u neki drugi wiki. Trenutačna verzija to još ne podržava.

Za izvoz stranica unesite njihove naslove u polje ispod, jedan naslov po retku, i označite želite li trenutačnu inačicu zajedno sa svim prijašnjima, ili samo trenutačnu inačicu s informacijom o zadnjoj promjeni.

U potonjem slučaju možete koristiti i poveznicu, npr. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za članak [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Uključi samo trenutačnu inačicu, ne i sve prijašnje',
'exportnohistory'   => "----
'''Napomena:''' izvoz cjelokupne stranice sa svim prethodnim izmjenama onemogućen je zbog opterećenja poslužitelja.",
'export-submit'     => 'Izvezi',
'export-addcattext' => 'Dodaj stranice iz kategorije:',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj stranice iz imenskog prostora:',
'export-addns'      => 'Dodaj',
'export-download'   => 'Ponudi opciju snimanja u datoteku',
'export-templates'  => 'Uključi predloške',
'export-pagelinks'  => 'Uključi povezane stranice do dubine od:',

# Namespace 8 related
'allmessages'                   => 'Sve sistemske poruke',
'allmessagesname'               => 'Ime',
'allmessagesdefault'            => 'Prvotni tekst',
'allmessagescurrent'            => 'Trenutačni tekst',
'allmessagestext'               => 'Ovo je popis svih sistemskih poruka u imenskom prostoru MediaWiki. Molimo posjetite [http://www.mediawiki.org/wiki/Localisation lokalizaciju MediaWikija] i [http://translatewiki.net translatewiki.net] ukoliko želite doprinjeti lokalizaciji MediaWiki softvera.',
'allmessagesnotsupportedDB'     => "Ova stranica ne može biti korištena jer je isključen parametar '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend'     => 'Filtriraj',
'allmessages-filter'            => 'Filtriraj prema prilagođenom obliku:',
'allmessages-filter-unmodified' => 'Nepreinačeno',
'allmessages-filter-all'        => 'Sve',
'allmessages-filter-modified'   => 'Preinačeno',
'allmessages-prefix'            => 'Filtriraj prema prefiksu:',
'allmessages-language'          => 'Jezik:',
'allmessages-filter-submit'     => 'Idi',

# Thumbnails
'thumbnail-more'           => 'Povećaj',
'filemissing'              => 'Nedostaje datoteka',
'thumbnail_error'          => 'Pogreška pri izradbi sličice: $1',
'djvu_page_error'          => "DjVu stranica nije dohvatljiva (''out of range'')",
'djvu_no_xml'              => 'Ne mogu dohvatiti XML za DjVu datoteku',
'thumbnail_invalid_params' => "Nevaljani parametri za smanjenu sliku (''thumbnail'')",
'thumbnail_dest_directory' => 'Ne mogu stvoriti ciljni direktorij',
'thumbnail_image-type'     => 'Tip slike nije podržan',
'thumbnail_gd-library'     => 'Nepotpune konfiguracija GD knjižnice: nedostaje funkcija $1',
'thumbnail_image-missing'  => 'Čini se da datoteka nedostaje: $1',

# Special:Import
'import'                     => 'Uvezi stranice',
'importinterwiki'            => 'Transwiki uvoz',
'import-interwiki-text'      => 'Izaberite wiki i ime stranice za uvoz.
Povijest stranice i imena suradnika će biti sačuvani.
Transwiki uvoz stranica je zabilježen u [[Special:Log/import|evidenciji uvoza stranica]].',
'import-interwiki-source'    => 'Izvor wiki/stranica:',
'import-interwiki-history'   => 'Prenesi sve inačice ove stranice',
'import-interwiki-templates' => 'Uključi sve predloške',
'import-interwiki-submit'    => 'Uvezi',
'import-interwiki-namespace' => 'Odredišni imenski prostor:',
'import-upload-filename'     => 'Ime datoteke:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Molim da izvezete ovu datoteku iz izvorišnog wikija koristeći pomagalo Special:Export, snimite je na svoj disk i postavite je ovdje.',
'importstart'                => 'Uvozim stranice...',
'import-revision-count'      => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',
'importnopages'              => 'Nema stranica za uvoz.',
'imported-log-entries'       => '{{PLURAL:$1|Uvezen $1 unos u evidenciju|Uvezena $1 unosa u evidenciju|Uvezeno $1 unosa u evidenciju}}.',
'importfailed'               => 'Uvoz nije uspio: $1',
'importunknownsource'        => 'Nepoznat tip stranica za uvoz',
'importcantopen'             => 'Ne mogu otvoriti datoteku za uvoz',
'importbadinterwiki'         => 'Neispravna međuwiki poveznica',
'importnotext'               => 'Prazno ili bez teksta',
'importsuccess'              => 'Uvoz je uspio!',
'importhistoryconflict'      => 'Došlo je do konflikta među prijašnjim inačicama (ova je stranica možda već uvezena)',
'importnosources'            => 'Nije unesen nijedan izvor za transwiki uvoz i neposredno postavljanje povijesti je onemogućeno.',
'importnofile'               => 'Nije postavljena uvozna datoteka.',
'importuploaderrorsize'      => 'Uvoz datoteke nije uspio. Datoteka je veća od dopuštene veličine.',
'importuploaderrorpartial'   => 'Uvoz datoteke nije uspio. Datoteka je djelomično uvezena/snimljena.',
'importuploaderrortemp'      => 'Uvoz datoteke nije uspio. Nema privremenog direktorija.',
'import-parse-failure'       => 'Pogreška u parsiranju kod uvoza XML-a',
'import-noarticle'           => 'Nema stranice za uvoz!',
'import-nonewrevisions'      => 'Sve inačice su bile prethodno uvezene.',
'xml-error-string'           => '$1 u retku $2, stupac $3 (bajt $4): $5',
'import-upload'              => 'Postavljanje XML datoteka',
'import-token-mismatch'      => 'Izgubljeni su podaci o sesiji. Molimo pokušajte ponovno.',
'import-invalid-interwiki'   => 'Ne mogu uvesti iz navedene wiki.',

# Import log
'importlogpage'                    => 'Evidencija uvoza članaka',
'importlogpagetext'                => 'Administrativni uvoz stranica s poviješću uređivanja s drugih wikija.',
'import-logentry-upload'           => 'uvezeno [[$1]] uvozom datoteke',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',
'import-logentry-interwiki'        => 'transwiki uvezeno $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|promjena|promjene|promjena}} od $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Moja suradnička stranica',
'tooltip-pt-anonuserpage'         => 'Suradnička stranica za IP adresu pod kojom uređujete',
'tooltip-pt-mytalk'               => 'Moja stranica za razgovor',
'tooltip-pt-anontalk'             => 'Razgovor o suradnicima s ove IP adrese',
'tooltip-pt-preferences'          => 'Moje postavke',
'tooltip-pt-watchlist'            => 'Popis stranica koje pratite.',
'tooltip-pt-mycontris'            => 'Popis mojih doprinosa',
'tooltip-pt-login'                => 'Predlažemo Vam da se prijavite, ali nije obvezno.',
'tooltip-pt-anonlogin'            => 'Predlažemo Vam da se prijavite, ali nije obvezno.',
'tooltip-pt-logout'               => 'Odjavi se',
'tooltip-ca-talk'                 => 'Razgovor o stranici',
'tooltip-ca-edit'                 => 'Možete uređivati ovu stranicu. Koristite Pregled kako će izgledati prije nego što snimite.',
'tooltip-ca-addsection'           => 'Dodaj novi odlomak',
'tooltip-ca-viewsource'           => 'Ova stranica je zaštićena. Možete pogledati izvorni kod.',
'tooltip-ca-history'              => 'Ranije izmjene na ovoj stranici.',
'tooltip-ca-protect'              => 'Zaštiti ovu stranicu',
'tooltip-ca-unprotect'            => 'Ukloni zaštitu s ove stranice',
'tooltip-ca-delete'               => 'Izbriši ovu stranicu',
'tooltip-ca-undelete'             => 'Vrati uređivanja na ovoj stranici prije nego što je izbrisana',
'tooltip-ca-move'                 => 'Premjesti ovu stranicu',
'tooltip-ca-watch'                => 'Dodaj ovu stranicu na svoj popis praćenja',
'tooltip-ca-unwatch'              => 'Ukloni ovu stranicu s popisa praćenja',
'tooltip-search'                  => 'Pretraži ovaj wiki',
'tooltip-search-go'               => 'Idi na stranicu s ovim imenom ako ona postoji',
'tooltip-search-fulltext'         => 'Traži ovaj tekst na svim stranicama',
'tooltip-p-logo'                  => 'Glavna stranica',
'tooltip-n-mainpage'              => 'Posjeti glavnu stranicu',
'tooltip-n-mainpage-description'  => 'Posjeti glavnu stranicu',
'tooltip-n-portal'                => 'O projektu, što možete učiniti, gdje je što',
'tooltip-n-currentevents'         => 'O trenutačnim događajima',
'tooltip-n-recentchanges'         => 'Popis nedavnih promjena u wikiju.',
'tooltip-n-randompage'            => 'Učitaj slučajnu stranicu',
'tooltip-n-help'                  => 'Mjesto za pomoć suradnicima.',
'tooltip-t-whatlinkshere'         => 'Popis svih stranica koje sadrže poveznice ovamo',
'tooltip-t-recentchangeslinked'   => 'Nedavne promjene na stranicama na koje vode ovdašnje poveznice',
'tooltip-feed-rss'                => 'RSS feed za ovu stranicu',
'tooltip-feed-atom'               => 'Atom feed za ovu stranicu',
'tooltip-t-contributions'         => 'Pogledaj popis suradnikovih doprinosa',
'tooltip-t-emailuser'             => 'Pošalji suradniku e-mail',
'tooltip-t-upload'                => 'Postavi slike i druge medije',
'tooltip-t-specialpages'          => 'Popis posebnih stranica',
'tooltip-t-print'                 => 'Verzija za ispis ove stranice',
'tooltip-t-permalink'             => 'Trajna poveznica na ovu verziju stranice',
'tooltip-ca-nstab-main'           => 'Pogledaj sadržaj',
'tooltip-ca-nstab-user'           => 'Pogledaj suradničku stranicu',
'tooltip-ca-nstab-media'          => 'Pogledaj stranicu s opisom medija',
'tooltip-ca-nstab-special'        => 'Ovo je posebna stranica koju nije moguće izravno uređivati.',
'tooltip-ca-nstab-project'        => 'Pogledaj stranicu o projektu',
'tooltip-ca-nstab-image'          => 'Pogledaj stranicu o slici',
'tooltip-ca-nstab-mediawiki'      => 'Pogledaj sistemske poruke',
'tooltip-ca-nstab-template'       => 'Pogledaj predložak',
'tooltip-ca-nstab-help'           => 'Pogledaj stranicu za pomoć',
'tooltip-ca-nstab-category'       => 'Pogledaj stranicu kategorije',
'tooltip-minoredit'               => 'Označi kao manju promjenu',
'tooltip-save'                    => 'Sačuvaj promjene',
'tooltip-preview'                 => 'Prikaži kako će izgledati, molimo koristite prije snimanja!',
'tooltip-diff'                    => 'Prikaži promjene učinjene u tekstu.',
'tooltip-compareselectedversions' => 'Prikaži usporedbu izabranih inačica ove stranice.',
'tooltip-watch'                   => 'Dodaj na popis praćenja',
'tooltip-recreate'                => 'Vrati stranicu unatoč tome što je obrisana',
'tooltip-upload'                  => "Pokreni snimanje (''upload'')",
'tooltip-rollback'                => '"Ukloni" uklanja uređivanja zadnjeg suradnika na ovoj stranici.',
'tooltip-undo'                    => '"Ukloni ovu izmjenu" uklanja ovu izmjenu i otvara okvir za uređivanje. Omogućava unošenje razloga u sažetak.',
'tooltip-preferences-save'        => 'Spremi postavke',
'tooltip-summary'                 => 'Unesite kratki sažetak',

# Stylesheets
'common.css'   => '/** Uređivanje ove CSS datoteke će se odraziti na sve skinove */',
'monobook.css' => '/** Ovdje idu izmjene monobook stylesheeta */',

# Scripts
'common.js'   => '/* JavaScript kod na ovoj stranici će biti izvršen kod svakog suradnika pri svakom učitavanju svake stranice wikija. */',
'monobook.js' => '/* Ne rabi se više; molimo rabite [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metapodaci su isključeni na ovom serveru.',
'nocreativecommons' => 'Creative Commons RDF metapodaci su isključeni na ovom serveru.',
'notacceptable'     => 'Wiki server ne može dobaviti podatke u obliku kojega Vaš preglednik može pročitati.',

# Attribution
'anonymous'        => 'Neprijavljeni {{PLURAL:$1|suradnik|suradnici}} projekta {{SITENAME}}',
'siteuser'         => 'Suradnik $1 na projektu {{SITENAME}}',
'anonuser'         => '{{SITENAME}} anonimni suradnik $1',
'lastmodifiedatby' => 'Ovu je stranicu zadnji put mijenjao dana $2, $1 suradnik $3.',
'othercontribs'    => 'Temelji se na doprinosu suradnika $1.',
'others'           => 'drugih',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|suradnik|suradnici}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonimni suradnik|anonimni suradnici}} $1',
'creditspage'      => 'Autori stranice',
'nocredits'        => 'Za ovu stranicu nema podataka o autorima.',

# Spam protection
'spamprotectiontitle' => 'Zaštita od spama',
'spamprotectiontext'  => 'Stranicu koju ste željeli snimiti blokirao je filter spama.
Razlog je vjerojatno vanjska poveznica koja se nalazi na crnom popisu.',
'spamprotectionmatch' => 'Naš filter spama reagirao je na sljedeći tekst: $1',
'spambot_username'    => 'MediaWiki zaštita od spama',
'spam_reverting'      => 'Vraćam na zadnju inačicu koja ne sadrži poveznice na $1',
'spam_blanking'       => 'Sve inačice sadrže poveznice na $1, brišem cjelokupni sadržaj',

# Info page
'infosubtitle'   => 'Podaci o stranici',
'numedits'       => 'Broj promjena (članak): $1',
'numtalkedits'   => 'Broj promjena (stranica za razgovor): $1',
'numwatchers'    => 'Broj pratitelja: $1',
'numauthors'     => 'Broj autora (članak): $1',
'numtalkauthors' => 'Broj autora (stranica za razgovor): $1',

# Skin names
'skinname-standard'    => 'Standardna',
'skinname-nostalgia'   => 'Nostalgija',
'skinname-cologneblue' => 'Kölnska plava',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',

# Math options
'mw_math_png'    => 'Uvijek kao PNG',
'mw_math_simple' => 'Ako je vrlo jednostavno HTML, inače PNG',
'mw_math_html'   => 'Ako je moguće HTML, inače PNG',
'mw_math_source' => 'Ostavi u formatu TeX (za tekstualne preglednike)',
'mw_math_modern' => 'Preporučeno za današnje preglednike',
'mw_math_mathml' => 'Ako je moguće MathML (u pokusnoj fazi)',

# Math errors
'math_failure'          => 'Obrada nije uspjela.',
'math_unknown_error'    => 'nepoznata pogreška',
'math_unknown_function' => 'nepoznata funkcija',
'math_lexing_error'     => 'rječnička pogreška (lexing error)',
'math_syntax_error'     => 'sintaksna pogreška',
'math_image_error'      => 'Konverzija u PNG nije uspjela; provjerite jesu li dobro instalirani latex, dvips, gs, i convert',
'math_bad_tmpdir'       => 'Ne mogu otvoriti ili pisati u privremeni direktorij za matematiku',
'math_bad_output'       => 'Ne mogu otvoriti ili pisati u odredišni direktorij za matematiku',
'math_notexvc'          => 'Nedostaje izvršna datoteka texvc-a; pogledajte math/README za postavke.',

# Patrolling
'markaspatrolleddiff'                 => 'Označi za pregledano',
'markaspatrolledtext'                 => 'Označi ovaj članak pregledanim',
'markedaspatrolled'                   => 'Pregledano',
'markedaspatrolledtext'               => 'Odabrana promjena [[:$1]] označena je pregledanom.',
'rcpatroldisabled'                    => 'Nadzor nedavnih promjena isključen',
'rcpatroldisabledtext'                => 'Naredba "Nadziri nedavne promjene" trenutačno je isključena.',
'markedaspatrollederror'              => 'Ne mogu označiti za pregledano',
'markedaspatrollederrortext'          => 'Morate odabrati inačicu koju treba označiti za pregledanu.',
'markedaspatrollederror-noautopatrol' => 'Ne možete vlastite promjene označiti patroliranima.',

# Patrol log
'patrol-log-page'      => 'Evidencija pregledavanja promjena',
'patrol-log-header'    => 'Ovo su evidencije patroliranih izmjena.',
'patrol-log-line'      => 'promjena broj $1 stranice $2 pregledana $3',
'patrol-log-auto'      => '(automatski pregledano)',
'patrol-log-diff'      => 'promjena $1',
'log-show-hide-patrol' => '$1 evidenciju patroliranja',

# Image deletion
'deletedrevision'                 => 'Izbrisana stara inačica $1',
'filedeleteerror-short'           => 'Pogreška u brisanju datoteke: $1',
'filedeleteerror-long'            => 'Dogodila se pogreška prilikom brisanja datoteke:

$1',
'filedelete-missing'              => 'Datoteka "$1" ne može biti obrisana, jer ne postoji.',
'filedelete-old-unregistered'     => 'Navedena promjena datoteke "$1" ne postoji u bazi podataka.',
'filedelete-current-unregistered' => 'Navedene datoteke "$1" nema u bazi podataka.',
'filedelete-archive-read-only'    => 'Web poslužitelj nema pravo pisanja u direktorij "$1".',

# Browsing diffs
'previousdiff' => '← Starija izmjena',
'nextdiff'     => 'Novija izmjena →',

# Media information
'mediawarning'         => "'''Upozorenje''': Ova datoteka možda sadrži štetan kod.
Njegovim izvršavanjem mogli biste oštetiti svoj sustav.",
'imagemaxsize'         => "Ograniči veličinu slike:<br />''(za stranicu s opisom datoteke)''",
'thumbsize'            => 'Veličina sličice (umanjene inačice slike):',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|stranica|stranice}}',
'file-info'            => 'veličina datoteke: $1, MIME tip: $2',
'file-info-size'       => '$1 × $2 piksela, veličina datoteke: $3, MIME tip: $4',
'file-nohires'         => '<small>Viša rezolucija nije dostupna.</small>',
'svg-long-desc'        => 'SVG datoteka, nominalno $1 × $2 piksela, veličina datoteke: $3',
'show-big-image'       => 'Vidi sliku u punoj veličini (rezoluciji)',
'show-big-image-thumb' => '<small>Veličina pretpregleda: $1 × $2 piksela</small>',
'file-info-gif-looped' => 'animacija se ponavlja',
'file-info-gif-frames' => '$1 {{PLURAL:$1|okvir|okvira}}',
'file-info-png-looped' => 'animacija se ponavlja',
'file-info-png-repeat' => 'prikazano $1 {{PLURAL:$1|puta|puta|puta}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|okvir|okvira}}',

# Special:NewFiles
'newimages'             => 'Galerija novih datoteka',
'imagelisttext'         => 'Ispod je popis {{PLURAL:$1|$1 slike|$1 slike|$1 slika}} složen $2.',
'newimages-summary'     => 'Ova posebna stranica pokazuje zadnje nedavno postavljene datoteke.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Naziv datoteke (ili njen dio):',
'showhidebots'          => '($1 botova)',
'noimages'              => 'Nema slika.',
'ilsubmit'              => 'Traži',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'Prikaži nove slike počevši od $2, $1',

# Bad image list
'bad_image_list' => "Rabi se sljedeći format:

Samo retci koji počinju sa zvjezdicom su prikazani. Prva poveznica u retku mora biti poveznica na nevaljanu sliku.
Svaka sljedeća poveznica u istom retku je izuzetak, npr. kod stranica gdje se slike pojavljuju ''inline''.",

# Variants for Serbian language
'variantname-sr-ec' => 'ћирилица',
'variantname-sr-el' => 'latinica',

# Metadata
'metadata'          => 'Metapodaci',
'metadata-help'     => 'Ova datoteka sadržava dodatne podatke koje je vjerojatno dodala digitalna kamera ili skener u procesu snimanja odnosno digitalizacije. Ako je datoteka mijenjana, podatci možda nisu u skladu sa stvarnim stanjem.',
'metadata-expand'   => 'Pokaži sve podatke',
'metadata-collapse' => 'Sakrij dodatne podatke',
'metadata-fields'   => "Slijedeći EXIF metapodaci će biti prikazani ispod slike u tablici s metapodacima. Ostali će biti sakriveni (možete ih vidjeti ako kliknete na poveznicu ''Pokaži sve podatke'').
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Širina',
'exif-imagelength'                 => 'Visina',
'exif-bitspersample'               => 'Dubina boje',
'exif-compression'                 => 'Način sažimanja',
'exif-photometricinterpretation'   => 'Kolor model',
'exif-orientation'                 => 'Orijentacija kadra',
'exif-samplesperpixel'             => 'Broj kolor komponenata',
'exif-planarconfiguration'         => 'Princip rasporeda podataka',
'exif-ycbcrsubsampling'            => 'Omjer komponente Y prema C',
'exif-ycbcrpositioning'            => 'Razmještaj komponenata Y i C',
'exif-xresolution'                 => 'Vodoravna razlučivost',
'exif-yresolution'                 => 'Okomita razlučivost',
'exif-resolutionunit'              => 'Jedinica razlučivosti',
'exif-stripoffsets'                => 'Položaj bloka podataka',
'exif-rowsperstrip'                => 'Broj redova u bloku',
'exif-stripbytecounts'             => 'Veličina komprimiranog bloka',
'exif-jpeginterchangeformat'       => 'Udaljenost JPEG previewa od početka datoteke',
'exif-jpeginterchangeformatlength' => 'Količina bajtova JPEG previewa',
'exif-transferfunction'            => 'Funkcija preoblikovanja kolor prostora',
'exif-whitepoint'                  => 'Kromaticitet bijele točke',
'exif-primarychromaticities'       => 'Kromaticitet primarnih boja',
'exif-ycbcrcoefficients'           => 'Matrični koeficijenti preobrazbe kolor prostora',
'exif-referenceblackwhite'         => 'Mjesto bijele i crne točke',
'exif-datetime'                    => 'Datum zadnje promjene datoteke',
'exif-imagedescription'            => 'Ime slike',
'exif-make'                        => 'Proizvođač kamere',
'exif-model'                       => 'Model kamere',
'exif-software'                    => 'Korišteni softver',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Nositelj prava',
'exif-exifversion'                 => 'Exif verzija',
'exif-flashpixversion'             => 'Podržana verzija Flashpixa',
'exif-colorspace'                  => 'Kolor prostor',
'exif-componentsconfiguration'     => 'Značenje pojedinih komponenti',
'exif-compressedbitsperpixel'      => 'Dubina boje poslije sažimanja',
'exif-pixelydimension'             => 'Puna visina slike',
'exif-pixelxdimension'             => 'Puna širina slike',
'exif-makernote'                   => 'Napomene proizvođača',
'exif-usercomment'                 => 'Suradnički komentar',
'exif-relatedsoundfile'            => 'Povezani zvučni zapis',
'exif-datetimeoriginal'            => 'Datum i vrijeme slikanja',
'exif-datetimedigitized'           => 'Datum i vrijeme digitalizacije',
'exif-subsectime'                  => 'Dio sekunde u kojem je slikano',
'exif-subsectimeoriginal'          => 'Dio sekunde u kojem je fotografirano',
'exif-subsectimedigitized'         => 'Dio sekunde u kojem je digitalizirano',
'exif-exposuretime'                => 'Ekspozicija',
'exif-exposuretime-format'         => '$1 sekunda ($2)',
'exif-fnumber'                     => 'F broj dijafragme',
'exif-exposureprogram'             => 'Program ekspozicije',
'exif-spectralsensitivity'         => 'Spektralna osjetljivost',
'exif-isospeedratings'             => 'ISO vrijednost',
'exif-oecf'                        => 'Optoelektronski faktor konverzije',
'exif-shutterspeedvalue'           => 'Brzina zatvarača',
'exif-aperturevalue'               => 'Dijafragma',
'exif-brightnessvalue'             => 'Osvijetljenost',
'exif-exposurebiasvalue'           => 'Kompenzacija ekspozicije',
'exif-maxaperturevalue'            => 'Minimalni broj dijafragme',
'exif-subjectdistance'             => 'Udaljenost do objekta',
'exif-meteringmode'                => 'Režim mjerača vremena',
'exif-lightsource'                 => 'Izvor svjetlosti',
'exif-flash'                       => 'Bljeskalica',
'exif-focallength'                 => 'Žarišna duljina leće',
'exif-subjectarea'                 => 'Položaj i površina objekta snimke',
'exif-flashenergy'                 => 'Energija bljeskalice',
'exif-spatialfrequencyresponse'    => 'Prostorna frekvencijska karakteristika',
'exif-focalplanexresolution'       => 'Vodoravna razlučivost žarišne ravnine',
'exif-focalplaneyresolution'       => 'Okomita razlučivost žarišne ravnine',
'exif-focalplaneresolutionunit'    => 'Jedinica razlučivosti žarišne ravnine',
'exif-subjectlocation'             => 'Položaj subjekta',
'exif-exposureindex'               => 'Indeks ekspozicije',
'exif-sensingmethod'               => 'Tip senzora',
'exif-filesource'                  => 'Izvorna datoteka',
'exif-scenetype'                   => 'Tip scene',
'exif-cfapattern'                  => 'Tip kolor filtera',
'exif-customrendered'              => 'Dodatna obrada slike',
'exif-exposuremode'                => 'Režim izbora ekspozicije',
'exif-whitebalance'                => 'Balans bijele',
'exif-digitalzoomratio'            => 'Razmjer digitalnog zooma',
'exif-focallengthin35mmfilm'       => 'Ekvivalent žarišne daljine za 35 mm film',
'exif-scenecapturetype'            => 'Tip scene na snimci',
'exif-gaincontrol'                 => 'Kontrola osvijetljenosti',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Zasićenje',
'exif-sharpness'                   => 'Oštrina',
'exif-devicesettingdescription'    => 'Opis postavki uređaja',
'exif-subjectdistancerange'        => 'Raspon udaljenosti subjekata',
'exif-imageuniqueid'               => 'Jedinstveni identifikator slike',
'exif-gpsversionid'                => 'Verzija bloka GPS-informacije',
'exif-gpslatituderef'              => 'Sjeverna ili južna širina',
'exif-gpslatitude'                 => 'Širina',
'exif-gpslongituderef'             => 'Istočna ili zapadna dužina',
'exif-gpslongitude'                => 'Dužina',
'exif-gpsaltituderef'              => 'Visina ispod ili iznad mora',
'exif-gpsaltitude'                 => 'Visina',
'exif-gpstimestamp'                => 'Vrijeme po GPS-u (atomski sat)',
'exif-gpssatellites'               => 'Korišteni sateliti',
'exif-gpsstatus'                   => 'Status prijemnika',
'exif-gpsmeasuremode'              => 'Režim mjerenja',
'exif-gpsdop'                      => 'Preciznost mjerenja',
'exif-gpsspeedref'                 => 'Jedinica brzine',
'exif-gpsspeed'                    => 'Brzina GPS prijemnika',
'exif-gpstrackref'                 => 'Tip azimuta prijemnika (pravi ili magnetni)',
'exif-gpstrack'                    => 'Azimut prijemnika',
'exif-gpsimgdirectionref'          => 'Tip azimuta slike (pravi ili magnetni)',
'exif-gpsimgdirection'             => 'Azimut slike',
'exif-gpsmapdatum'                 => 'Korišteni geodetski koordinatni sustav',
'exif-gpsdestlatituderef'          => 'Indeks zemlj. širine objekta',
'exif-gpsdestlatitude'             => 'Zemlj. širina objekta',
'exif-gpsdestlongituderef'         => 'Indeks zemlj. dužine objekta',
'exif-gpsdestlongitude'            => 'Zemljopisna dužina objekta',
'exif-gpsdestbearingref'           => 'Indeks pelenga objekta',
'exif-gpsdestbearing'              => 'Peleng objekta',
'exif-gpsdestdistanceref'          => 'Mjerne jedinice udaljenosti objekta',
'exif-gpsdestdistance'             => 'Udaljenost objekta',
'exif-gpsprocessingmethod'         => 'Ime metode obrade GPS podataka',
'exif-gpsareainformation'          => 'Ime GPS područja',
'exif-gpsdatestamp'                => 'GPS datum',
'exif-gpsdifferential'             => 'GPS diferencijalna korekcija',

# EXIF attributes
'exif-compression-1' => 'Nesažeto',

'exif-unknowndate' => 'Datum nepoznat',

'exif-orientation-1' => 'Normalno',
'exif-orientation-2' => 'Zrcaljeno po horizontali',
'exif-orientation-3' => 'Zaokrenuto 180°',
'exif-orientation-4' => 'Zrcaljeno po vertikali',
'exif-orientation-5' => 'Zaokrenuto 90° suprotno od sata i zrcaljeno po vertikali',
'exif-orientation-6' => 'Zaokrenuto 90° u smjeru sata',
'exif-orientation-7' => 'Zaokrenuto 90° u smjeru sata i zrcaljeno po vertikali',
'exif-orientation-8' => 'Zaokrenuto 90° suprotno od sata',

'exif-planarconfiguration-1' => 'zrnasti format',
'exif-planarconfiguration-2' => 'planarni format',

'exif-componentsconfiguration-0' => 'ne postoji',

'exif-exposureprogram-0' => 'Nepoznato',
'exif-exposureprogram-1' => 'Ručno',
'exif-exposureprogram-2' => 'Normalni program',
'exif-exposureprogram-3' => 'Prioritet dijafragme',
'exif-exposureprogram-4' => 'Prioritet zatvarača',
'exif-exposureprogram-5' => 'Umjetnički program (na temelju nužne dubine polja)',
'exif-exposureprogram-6' => 'Sportski program (na temelju što bržeg zatvarača)',
'exif-exposureprogram-7' => 'Portretni režim (za krupne planove s neoštrom pozadinom)',
'exif-exposureprogram-8' => 'Režim krajolika (za slike krajolika s oštrom pozadinom)',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-0'   => 'Nepoznato',
'exif-meteringmode-1'   => 'Prosjek',
'exif-meteringmode-2'   => 'Prosjek s težištem na sredini',
'exif-meteringmode-3'   => 'Točka',
'exif-meteringmode-4'   => 'Više točaka',
'exif-meteringmode-5'   => 'Matrični',
'exif-meteringmode-6'   => 'Djelomični',
'exif-meteringmode-255' => 'Drugo',

'exif-lightsource-0'   => 'Nepoznato',
'exif-lightsource-1'   => 'Dnevna svjetlost',
'exif-lightsource-2'   => 'Fluorescentno',
'exif-lightsource-3'   => 'Volframska žarulja',
'exif-lightsource-4'   => 'Bljeskalica',
'exif-lightsource-9'   => 'Lijepo vrijeme',
'exif-lightsource-10'  => 'Oblačno vrijeme',
'exif-lightsource-11'  => 'Sjena',
'exif-lightsource-12'  => 'Fluorescentna svjetlost (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescentna svjetlost (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescentna svjetlost (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bijela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardno svjetlo A',
'exif-lightsource-18'  => 'Standardno svjetlo B',
'exif-lightsource-19'  => 'Standardno svjetlo C',
'exif-lightsource-24'  => 'ISO studijska svjetiljka',
'exif-lightsource-255' => 'Drugi izvor svjetla',

# Flash modes
'exif-flash-fired-0'    => 'Bez upotrebe bljeskalice',
'exif-flash-fired-1'    => 'S upotrebom bljeskalice',
'exif-flash-return-0'   => 'bez upotrebe funkcije stroboskopa',
'exif-flash-return-2'   => 'stroboskop nije opazio svjetlo',
'exif-flash-return-3'   => 'stroboskop je opazio svjetlo',
'exif-flash-mode-1'     => 'bljeskalica ručno uključena',
'exif-flash-mode-2'     => 'bljeskalica ručno isključena',
'exif-flash-mode-3'     => 'automatski način rada',
'exif-flash-function-1' => 'Nema funkcije bljeskalice',
'exif-flash-redeye-1'   => 'način rada za smanjenje crvenih očiju',

'exif-focalplaneresolutionunit-2' => 'inči',

'exif-sensingmethod-1' => 'Nedefinirano',
'exif-sensingmethod-2' => 'Jednokristalni matrični senzor',
'exif-sensingmethod-3' => 'Dvokristalni matrični senzor',
'exif-sensingmethod-4' => 'Trokristalni matrični senzor',
'exif-sensingmethod-5' => 'Sekvencijalni matrični senzor',
'exif-sensingmethod-7' => 'Trobojni linearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni linearni senzor',

'exif-filesource-3' => 'Digitalni fotoaparat',

'exif-scenetype-1' => 'Izravno fotografirana slika',

'exif-customrendered-0' => 'Normalni proces',
'exif-customrendered-1' => 'Nestadardni proces',

'exif-exposuremode-0' => 'Automatski',
'exif-exposuremode-1' => 'Ručno',
'exif-exposuremode-2' => 'Automatski sa zadanim rasponom',

'exif-whitebalance-0' => 'Automatski',
'exif-whitebalance-1' => 'Ručno',

'exif-scenecapturetype-0' => 'Standardno',
'exif-scenecapturetype-1' => 'Pejzaž',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Noćno',

'exif-gaincontrol-0' => 'Nema',
'exif-gaincontrol-1' => 'Malo povećanje',
'exif-gaincontrol-2' => 'Veliko povećanje',
'exif-gaincontrol-3' => 'Malo smanjenje',
'exif-gaincontrol-4' => 'Veliko smanjenje',

'exif-contrast-0' => 'Normalno',
'exif-contrast-1' => 'Meko',
'exif-contrast-2' => 'Tvrdo',

'exif-saturation-0' => 'Normalno',
'exif-saturation-1' => 'Niska saturacija',
'exif-saturation-2' => 'Visoka saturacija',

'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Meko',
'exif-sharpness-2' => 'Tvrdo',

'exif-subjectdistancerange-0' => 'Nepoznato',
'exif-subjectdistancerange-1' => 'Krupni plan',
'exif-subjectdistancerange-2' => 'Bliski plan',
'exif-subjectdistancerange-3' => 'Udaljeno',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Sjever',
'exif-gpslatitude-s' => 'Jug',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istok',
'exif-gpslongitude-w' => 'Zapad',

'exif-gpsstatus-a' => 'Mjerenje u tijeku',
'exif-gpsstatus-v' => 'Spreman za prijenos',

'exif-gpsmeasuremode-2' => 'Dvodimenzionalno mjerenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno mjerenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'kmh',
'exif-gpsspeed-m' => 'mph',
'exif-gpsspeed-n' => 'čv',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi sjever',
'exif-gpsdirection-m' => 'Magnetni sjever',

# External editor support
'edit-externally'      => 'Uredi koristeći se vanjskom aplikacijom',
'edit-externally-help' => '(Vidi [http://www.mediawiki.org/wiki/Manual:External_editors setup upute] za više informacija)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sve',
'imagelistall'     => 'sve',
'watchlistall2'    => 'sve',
'namespacesall'    => 'sve',
'monthsall'        => 'sve',
'limitall'         => 'sve',

# E-mail address confirmation
'confirmemail'              => 'Potvrda e-mail adrese',
'confirmemail_noemail'      => 'Niste unijeli važeću e-mail adresu u Vaše [[Special:Preferences|suradničke postavke]].',
'confirmemail_text'         => 'U ovom wikiju morate prije korištenja e-mail naredbi potvrditi svoju e-mail adresu. Kliknite na gumb ispod kako biste poslali poruku s potvrdom na Vašu adresu. U poruci će biti poveznica koju morate otvoriti u svom web pregledniku i time potvrditi svoju e-mail adresu.',
'confirmemail_pending'      => 'Već Vam je e-mailom poslan potvrdni kôd;
ako ste upravo otvorili suradnički račun, molimo pričekajte još nekoliko minuta da e-mail stigne prije nego što zatražite novi kôd.',
'confirmemail_send'         => 'Pošalji kôd za potvrdu e-mail adrese',
'confirmemail_sent'         => 'Poruka s potvrdom je poslana.',
'confirmemail_oncreate'     => 'Potvrdni kôd poslan je na Vašu e-mail adresu.
Ovaj kôd nije potreban za prijavljivanje, no bit će Vam potreban kako biste osposobili neke od postavki na Wikipediji koje uključuju elektroničku poštu.',
'confirmemail_sendfailed'   => 'Projekt {{SITENAME}} nije uspio poslati Vaš potvrdni e-mail.
Provjerite sadrži li adresa nedopuštene znakove.

Poruka o pogrešci e-mail poslužitelja: $1',
'confirmemail_invalid'      => 'Pogrešna potvrda. Kôd je možda istekao.',
'confirmemail_needlogin'    => 'Molimo $1 kako biste potvrdili Vašu e-mail adresu.',
'confirmemail_success'      => 'Vaša je e-mail adresa potvrđena. Možete se prijaviti i uživati u wikiju.',
'confirmemail_loggedin'     => 'Vaša je e-mail adresa potvrđena.',
'confirmemail_error'        => 'Došlo je do greške kod snimanja Vaše potvrde.',
'confirmemail_subject'      => '{{SITENAME}}: potvrda e-mail adrese',
'confirmemail_body'         => 'Netko, vjerojatno Vi, s IP adrese $1 je otvorio
suradnički račun pod imenom "$2" s ovom e-mail adresom na {{SITENAME}}.

Kako biste potvrdili da je ovaj suradnički račun uistinu Vaš i
omogućili e-mail funkcije na {{SITENAME}}, otvorite u Vašem
pregledniku sljedeću poveznicu:

$3

Ako to *niste* Vi, slijedite ovaj link za poništavanje potvrde:

$5

Valjanost ovog potvrdnog koda istječe na $4.',
'confirmemail_body_changed' => 'Netko, vjerojatno Vi, s IP adrese $1,
promijenio je adresu e-pošte suradničkog računa "$2" u ovu adresu na {{SITENAME}}.

Kako biste potvrdili da je ovaj suradnički račun uistinu Vaš te uključili
mogućnosti e-pošte na {{SITENAME}}, otvorite u Vašem pregledniku sljedeću poveznicu:

$3

Ukoliko suradnički račun *ne* pripada Vama, slijedite ovu poveznicu
za poništavanje potvrde adrese e-pošte:

$5

Valjanost ovog potvrdnog koda istječe $4.',
'confirmemail_invalidated'  => 'Potvrda E-mail adrese je otkazana',
'invalidateemail'           => 'Poništi potvrđivanje elektroničke pošte',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transkluzija isključena]',
'scarytranscludefailed'   => '[Dobava predloška nije uspjela za $1]',
'scarytranscludetoolong'  => '[URL je predug]',

# Trackbacks
'trackbackbox'      => "''Trackbackovi'' za ovaj članak:<br />
$1",
'trackbackremove'   => '([$1 izbrisati])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback izbrisan.',

# Delete conflict
'deletedwhileediting' => "'''Upozorenje''': Ova stranica je obrisana nakon što ste počeli uređivati!",
'confirmrecreate'     => "Suradnik [[User:$1|$1]] ([[User talk:$1|talk]]) izbrisao je ovaj članak nakon što ste ga počeli uređivati. Razlog brisanja
: ''$2''
Potvrdite namjeru vraćanja ovog članka.",
'recreate'            => 'Vrati',

# action=purge
'confirm_purge_button' => 'U redu',
'confirm-purge-top'    => 'Isprazniti međuspremnik stranice?',
'confirm-purge-bottom' => 'Čišćenje stranice čisti priručnu memoriju i prikazuje trenutačnu inačicu stranice.',

# Multipage image navigation
'imgmultipageprev' => '← prethodna slika',
'imgmultipagenext' => 'slijedeća slika →',
'imgmultigo'       => 'Kreni!',
'imgmultigoto'     => 'Idi na stranicu $1',

# Table pager
'ascending_abbrev'         => 'rast',
'descending_abbrev'        => 'pad',
'table_pager_next'         => 'Sljedeća stranica',
'table_pager_prev'         => 'Prethodna stranica',
'table_pager_first'        => 'Prva stranica',
'table_pager_last'         => 'Zadnja stranica',
'table_pager_limit'        => 'Prikaži $1 slika po stranici',
'table_pager_limit_label'  => 'Stavke po stranici:',
'table_pager_limit_submit' => 'Idi',
'table_pager_empty'        => 'Nema rezultata',

# Auto-summaries
'autosumm-blank'   => 'Uklonjen cjelokupni sadržaj stranice',
'autosumm-replace' => "Tekst stranice se zamjenjuje s '$1'",
'autoredircomment' => 'Preusmjeravanje na [[$1]]',
'autosumm-new'     => 'Nova stranica: $1',

# Live preview
'livepreview-loading' => 'Učitavam…',
'livepreview-ready'   => 'Učitavam… gotovo!',
'livepreview-failed'  => 'Lokalni (JavaScript) pretpregled nije uspio! Pokušajte normalni pretpregled.',
'livepreview-error'   => 'Spajanje nije uspjelo: $1 "$2". Pokušajte normalni pretpregled.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Moguće je da izmjene nastale zadnjih $1 {{PLURAL:$1|sekundu|sekundi}} neće biti vidljive na ovom popisu.',
'lag-warn-high'   => 'Zbog kašnjenja baze podataka, moguće je da promjene napravljene u zadnjih $1 {{PLURAL:$1|sekundu|sekunde|sekundi}} nisu prikazane u popisu.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vaš popis praćenja sadrži {{PLURAL:$1|1 stranicu|$1 stranica}}, bez stranica za razgovor.',
'watchlistedit-noitems'        => 'Vaš popis praćenja je prazan.',
'watchlistedit-normal-title'   => 'Uredi popis praćenih stranica',
'watchlistedit-normal-legend'  => 'Ukloni stranice iz popisa praćenja',
'watchlistedit-normal-explain' => 'Prikazane su stranice na Vašem popisu praćenja.
Da uklonite stranicu s popisa praćenja, označite kućicu kraj nje i kliknite gumb "{{int:Watchlistedit-normal-submit}}".
Možete također [[Special:Watchlist/raw|uređivati ovaj popis u okviru za uređivanje]].',
'watchlistedit-normal-submit'  => 'Ukloni stranice',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 stranica je uklonjena|$1 stranice su uklonjene|$1 stranica je uklonjeno}} iz Vašeg popisa praćenja:',
'watchlistedit-raw-title'      => 'Uredi praćene stranice u okviru za uređivanje',
'watchlistedit-raw-legend'     => 'Uredi praćene stranice',
'watchlistedit-raw-explain'    => 'Stranice na Vašem popisu praćenja su prikazane ispod, možete uređivati taj popis dodavanjem novih stranica ili brisanjem postojećih; u jednom retku je ime jedne stranice.
Kad završite s uređivanjem, kliknite na "{{int:Watchlistedit-raw-submit}}".
Također možete koristiti [[Special:Watchlist/edit|standardni editor]].',
'watchlistedit-raw-titles'     => 'Imena stranica:',
'watchlistedit-raw-submit'     => 'Snimi promjene',
'watchlistedit-raw-done'       => 'Vaš popis praćenja je snimljen.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 stranica je dodana|$1 stranice su dodane}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 stranica je uklonjena|$1 stranice su ukonjene}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Pregled promjena praćenih stranica',
'watchlisttools-edit' => 'Pregled i uređivanje praćenih stranica',
'watchlisttools-raw'  => 'Uređivanje praćenih stranica u okviru za uređivanje',

# Core parser functions
'unknown_extension_tag' => "Nepoznat ''tag'' ekstenzije \"\$1\"",
'duplicate-defaultsort' => '\'\'\'Upozorenje:\'\'\' Razvrstavanje po "$2" poništava ranije razvrstavanje po "$1".',

# Special:Version
'version'                          => 'Inačica softvera',
'version-extensions'               => 'Instalirana proširenja',
'version-specialpages'             => 'Posebne stranice',
'version-parserhooks'              => 'Kuke parsera',
'version-variables'                => 'Varijable',
'version-antispam'                 => 'Sprječavanje spama',
'version-skins'                    => 'Izgledi',
'version-other'                    => 'Ostalo',
'version-mediahandlers'            => 'Rukovatelji medijima',
'version-hooks'                    => 'Kuke',
'version-extension-functions'      => 'Funkcije proširenja',
'version-parser-extensiontags'     => 'Oznake proširenja parsera',
'version-parser-function-hooks'    => 'Kuke funkcija parsera',
'version-skin-extension-functions' => 'Funkcije proširenja izgleda',
'version-hook-name'                => 'Ime kuke',
'version-hook-subscribedby'        => 'Pretplaćeno od',
'version-version'                  => '(Inačica $1)',
'version-license'                  => 'Licencija',
'version-poweredby-credits'        => "Ovaj wiki pogoni '''[http://www.mediawiki.org/ MediaWiki]''', autorska prava © 2001-$1 $2.",
'version-poweredby-others'         => 'ostali',
'version-license-info'             => 'MediaWiki je slobodni softver; možete ga distribuirati i/ili mijenjati pod uvjetima GNU opće javne licencije u obliku u kojem ju je objavila Free Software Foundation; bilo verzije 2 licencije, ili (Vama na izbor) bilo koje kasnije verzije.

MediaWiki je distribuiran u nadi da će biti koristan, no BEZ IKAKVOG JAMSTVA; čak i bez impliciranog jamstva MOGUĆNOSTI PRODAJE ili PRIKLADNOSTI ZA ODREĐENU NAMJENU. Pogledajte GNU opću javnu licenciju za više detalja.

Trebali ste primiti [{{SERVER}}{{SCRIPTPATH}}/COPYING kopiju GNU opće javne licencije] uz ovaj program; ako ne, pišite na Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA, ili je [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html pročitajte online].',
'version-software'                 => 'Instalirani softver',
'version-software-product'         => 'Proizvod',
'version-software-version'         => 'Verzija',

# Special:FilePath
'filepath'         => 'Putanja datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Idi',
'filepath-summary' => "Ova posebna stranica daje Vam kompletnu putanju do neke datoteke. Slike se na taj način prikazuju u punoj rezoluciji, a drugi tipovi datoteka se otvaraju na klik (kako je već namješteno u Vašem operacijskom sustavu).

Unesite ime datoteke bez predmetka (''prefiksa'') imenskog prostora \"{{ns:file}}:\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Traži kopije datoteka',
'fileduplicatesearch-summary'  => 'Traži kopije datoteka na temelju njihove hash vrijednosti.',
'fileduplicatesearch-legend'   => 'Traži kopije datoteka',
'fileduplicatesearch-filename' => 'Ime datoteke:',
'fileduplicatesearch-submit'   => 'Traži',
'fileduplicatesearch-info'     => '$1 × $2 piksela<br />Veličina datoteke: $3<br />MIME tip: $4',
'fileduplicatesearch-result-1' => 'Datoteka "$1" nema identičnih kopija.',
'fileduplicatesearch-result-n' => 'Datoteka "$1" ima {{PLURAL:$2|1 identičnu kopiju|$2 identične kopije}}.',

# Special:SpecialPages
'specialpages'                   => 'Posebne stranice',
'specialpages-note'              => '----
* Normalne posebne stranice
*<strong class="mw-specialpagerestricted">Posebne stranice s ograničenim pristupom</strong>',
'specialpages-group-maintenance' => 'Izvještaji za održavanje',
'specialpages-group-other'       => 'Ostale posebne stranice',
'specialpages-group-login'       => 'Prijava / Otvaranje računa',
'specialpages-group-changes'     => 'Nedavne promjene i evidencije',
'specialpages-group-media'       => 'Izvještaji i postavljanje datoteka',
'specialpages-group-users'       => 'Suradnici i suradnička prava',
'specialpages-group-highuse'     => 'Najčešće korištene stranice',
'specialpages-group-pages'       => 'Popisi stranica',
'specialpages-group-pagetools'   => 'Alati za stranice',
'specialpages-group-wiki'        => 'Wiki podaci i alati',
'specialpages-group-redirects'   => 'Preusmjeravajuće posebne stranice',
'specialpages-group-spam'        => 'Spam alati',

# Special:BlankPage
'blankpage'              => 'Prazna stranica',
'intentionallyblankpage' => 'Ova stranica je namjerno ostavljena praznom',

# External image whitelist
'external_image_whitelist' => '#Ovaj redak ostavite točno ovakvim kakav je<pre>
#Stavite ulomke s regularnim izrazom (samo dio koji ide između //) ispod
#Ovo će biti usklađeno s URL-ovima vanjskih slika (hotlink)
#Oni koji se poklapaju će biti prikazani kao slike, u suprotnom će biti prikazana samo poveznica do slike
#Redovi koji počinju sa # smatraju se komentarom
#Ovo je osjetljivo na velika slova

#Stavite sve regularne izraze iznad ovog reda. Ostavite ovaj redak točno ovakvim kakav je</pre>',

# Special:Tags
'tags'                    => 'Valjane oznake izmjena',
'tag-filter'              => 'Filter [[Special:Tags|oznaka]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Oznake',
'tags-intro'              => 'Ova je stranica popis oznaka s kojima softver može označiti promjenu te njihovo značenje.',
'tags-tag'                => 'Naziv oznake',
'tags-display-header'     => 'Izgled na popisima izmjena',
'tags-description-header' => 'Puni opis značenja',
'tags-hitcount-header'    => 'Označene izmjene',
'tags-edit'               => 'uredi',
'tags-hitcount'           => '$1 {{PLURAL:$1|promjena|promjene|promjena}}',

# Special:ComparePages
'comparepages'     => 'Usporedite stranice',
'compare-selector' => 'Usporedite inačice stranice',
'compare-page1'    => 'Stranica 1',
'compare-page2'    => 'Stranica 2',
'compare-rev1'     => 'Izmjena 1',
'compare-rev2'     => 'Izmjena 2',
'compare-submit'   => 'Usporedite',

# Database error messages
'dberr-header'      => 'Ovaj wiki ima problem',
'dberr-problems'    => 'Ispričavamo se! Ova stranica ima tehničkih poteškoća.',
'dberr-again'       => 'Pričekajte nekoliko minuta i ponovno učitajte.',
'dberr-info'        => '(Ne mogu se spojiti na poslužitelj baze: $1)',
'dberr-usegoogle'   => 'U međuvremenu pokušajte tražiti putem Googlea.',
'dberr-outofdate'   => 'Imajte na umu da su njihova kazala našeg sadržaja možda zastarjela.',
'dberr-cachederror' => 'Sljedeće je dohvaćena kopija tražene stranice, te možda nije ažurirana.',

# HTML forms
'htmlform-invalid-input'       => 'Postoje problemi s dijelom Vašeg unosa',
'htmlform-select-badoption'    => 'Vrijednost koju ste naveli nije ispravan izbor.',
'htmlform-int-invalid'         => 'Vrijednost koju ste naveli nije cijeli broj.',
'htmlform-float-invalid'       => 'Vrijednost koju ste naveli nije broj.',
'htmlform-int-toolow'          => 'Vrijednost koju ste naveli je ispod minimuma od $1',
'htmlform-int-toohigh'         => 'Vrijednost koju ste naveli je iznad maksimuma od $1',
'htmlform-required'            => 'Ova vrijednost je potrebna',
'htmlform-submit'              => 'Pošalji',
'htmlform-reset'               => 'Poništi izmjene',
'htmlform-selectorother-other' => 'Drugi',

# SQLite database support
'sqlite-has-fts' => '$1 s podrškom pretraživanja cijelog teksta',
'sqlite-no-fts'  => '$1 bez podrške pretraživanja cijelog teksta',

# Special:DisableAccount
'disableaccount'             => 'Onemogući suradnički račun',
'disableaccount-user'        => 'Suradničko ime:',
'disableaccount-reason'      => 'Razlog:',
'disableaccount-confirm'     => "Onemogućite ovaj suradnički račun. 
Suradnik neće biti u mogućnosti prijaviti se, promijeniti svoju lozinku, niti primati e-mail obavijesti. 
Ako je suradnik trenutno prijavljen negdje, odmah će biti odjavljen. 
''Napominjemo da onemogućavanje računa nije reverzibilno bez intervencije administratora sustava.''",
'disableaccount-mustconfirm' => 'Morate potvrditi da želite onemogućiti ovaj račun.',
'disableaccount-nosuchuser'  => 'Suradnik "$1" ne postoji.',
'disableaccount-success'     => 'Suradnički račun "$1" je trajno onemogućen.',
'disableaccount-logentry'    => 'trajno onesposobljen suradnički račun [[$1]]',

);
