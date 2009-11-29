<?php
/** Romanian (Română)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Emily
 * @author Firilacroco
 * @author KlaudiuMihaila
 * @author Laurap
 * @author Mihai
 * @author Minisarm
 * @author SCriBu
 * @author Silviubogan
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$separatorTransformTable = array( ',' => ".", '.' => ',' );

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECTEAZA', '#REDIRECT' ),
	'notoc'                 => array( '0', '__FARACUPRINS__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__FARAGALERIE__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORTEAZACUPRINS__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__CUPRINS__', '__TOC__' ),
	'noeditsection'         => array( '0', '__FARAEDITSECTIUNE__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__FARAANTET__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'NUMARLUNACURENTA', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'LUNACURENTA1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'NUMELUNACURENTA', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'NUMELUNACURENTAGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'LUNACURENTAABREV', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'NUMARZIUACURENTA', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'NUMARZIUACURENTA2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NUMEZIUACURENTA', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANULCURENT', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'TIMPULCURENT', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ORACURENTA', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'LUNALOCALA', 'LUNALOCALA2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'LUNALOCALA1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'NUMELUNALOCALA', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'NUMELUNALOCALAGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'LUNALOCALAABREV', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'ZIUALOCALA', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ZIUALOCALA2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NUMEZIUALOCALA', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ANULLOCAL', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'TIMPULLOCAL', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ORALOCALA', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMARDEPAGINI', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMARDEARTICOLE', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMARDEFISIERE', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMARDEUTILIZATORI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'NUMARDEUTILIZATORIACTIVI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'NUMARDEMODIFICARI', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NUMARDEVIZUALIZARI', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'NUMEPAGINA', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'NUMEEPAGINA', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'SPATIUDENUME', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'SPATIUUDENUME', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'SPATIUDEDISCUTIE', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'SPATIUUDEDISCUTIE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'SPATIUSUBIECT', 'SPATIUARTICOL', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'SPATIUUSUBIECT', 'SPATIUUARTICOL', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'NUMEPAGINACOMPLET', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'NUMEEPAGINACOMPLET', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'NUMESUBPAGINA', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'NUMEESUBPAGINA', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'NUMEDEBAZAPAGINA', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'NUMEEDEBAZAPAGINA', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'NUMEPAGINADEDISCUTIE', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'NUMEEPAGINADEDISCUTIE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'NUMEPAGINASUBIECT', 'NUMEPAGINAARTICOL', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'NUMEEPAGINASUBIECT', 'NUMEEPAGINAARTICOL', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'MSJ:', 'MSG:' ),
	'msgnw'                 => array( '0', 'MSJNOU:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'miniatura', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'miniatura=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'dreapta', 'right' ),
	'img_left'              => array( '1', 'stanga', 'left' ),
	'img_none'              => array( '1', 'nu', 'none' ),
	'img_center'            => array( '1', 'centru', 'center', 'centre' ),
	'img_framed'            => array( '1', 'cadru', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'faracadru', 'frameless' ),
	'img_page'              => array( '1', 'pagina=$1', 'pagina $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'dreaptasus', 'dreaptasus=$1', 'dreaptasus $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'chenar', 'border' ),
	'img_baseline'          => array( '1', 'linia de bază', 'baseline' ),
	'img_sub'               => array( '1', 'indice', 'sub' ),
	'img_super'             => array( '1', 'exponent', 'super', 'sup' ),
	'img_top'               => array( '1', 'sus', 'top' ),
	'img_text_top'          => array( '1', 'text-sus', 'text-top' ),
	'img_middle'            => array( '1', 'mijloc', 'middle' ),
	'img_bottom'            => array( '1', 'jos', 'bottom' ),
	'img_text_bottom'       => array( '1', 'text-jos', 'text-bottom' ),
	'img_link'              => array( '1', 'legătură=$1', 'link=$1' ),
	'sitename'              => array( '1', 'NUMESITE', 'SITENAME' ),
	'ns'                    => array( '0', 'SN:', 'NS:' ),
	'localurl'              => array( '0', 'URLLOCAL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URLLOCALE:', 'LOCALURLE:' ),
	'servername'            => array( '0', 'NUMESERVER', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'CALESCRIPT', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMATICA:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'GEN:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__FARACONVERTIRETITLU__', '__FCT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__FARACONVERTIRECONTINUT__', '__FCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'SAPTAMANACURENTA', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'SAPTAMANALOCALA', 'LOCALWEEK' ),
	'revisionid'            => array( '1', 'IDREVIZIE', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ZIREVIZIE', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ZIREVIZIE2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'LUNAREVIZIE', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ANREVIZIE', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'STAMPILATIMPREVIZIE', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'UTILIZATORREVIZIE', 'REVISIONUSER' ),
	'fullurl'               => array( '0', 'URLCOMPLET:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLCOMPLETE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'MINUSCULAPRIMA:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'MAJUSCULAPRIMA:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'MINUSCULA:', 'LC:' ),
	'uc'                    => array( '0', 'MAJUSCULA:', 'UC:' ),
	'raw'                   => array( '0', 'BRUT:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ARATATITLU', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__LEGATURASECTIUNENOUA__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__FARALEGATURASECTIUNENOUA__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'VERSIUNECURENTA', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'CODIFICAREURL:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'CODIFICAREANCORA', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'STAMPILATIMPCURENT', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'STAMPILATIMPLOCAL', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'SEMNDIRECTIE', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#LIMBA:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'LIMBACONTINUT', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'PANIGIINSPATIULDENUME:', 'PAGINIINSN:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'NUMARADMINI', 'NUMBEROFADMINS' ),
	'defaultsort'           => array( '1', 'SORTAREIMPLICITA:', 'CHEIESORTAREIMPLICITA:', 'CATEGORIESORTAREIMPLICITA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'CALEAFISIERULUI:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'eticheta', 'tag' ),
	'hiddencat'             => array( '1', '__ASCUNDECAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAGINIINCATEGORIE', 'PAGINIINCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'MARIMEPAGINA', 'PAGESIZE' ),
	'noindex'               => array( '1', '__FARAINDEX__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'NUMARINGRUP', 'NUMINGRUP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__REDIRECTIONARESTATICA__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'NIVELPROTECTIE', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'formatdata', 'dataformat', 'formatdate', 'dateformat' ),
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Discuţie',
	NS_USER             => 'Utilizator',
	NS_USER_TALK        => 'Discuţie_Utilizator',
	NS_PROJECT_TALK     => 'Discuţie_$1',
	NS_FILE             => 'Fişier',
	NS_FILE_TALK        => 'Discuţie_Fişier',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discuţie_MediaWiki',
	NS_TEMPLATE         => 'Format',
	NS_TEMPLATE_TALK    => 'Discuţie_Format',
	NS_HELP             => 'Ajutor',
	NS_HELP_TALK        => 'Discuţie_Ajutor',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Discuţie_Categorie',
);

$namespaceAliases = array(
	'Imagine' => NS_FILE,
	'Discuţie_Imagine' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redirectări duble' ),
	'BrokenRedirects'           => array( 'Redirectări invalide' ),
	'Disambiguations'           => array( 'Dezambiguizări' ),
	'Userlogin'                 => array( 'Autentificare' ),
	'Userlogout'                => array( 'Ieşire' ),
	'CreateAccount'             => array( 'Înregistrare' ),
	'Preferences'               => array( 'Preferinţe' ),
	'Watchlist'                 => array( 'Pagini urmărite' ),
	'Recentchanges'             => array( 'Schimbări recente' ),
	'Upload'                    => array( 'Încărcare' ),
	'Listfiles'                 => array( 'Listă imagini' ),
	'Newimages'                 => array( 'Imagini noi' ),
	'Listusers'                 => array( 'Listă utilizatori' ),
	'Listgrouprights'           => array( 'Listă drepturi grup' ),
	'Statistics'                => array( 'Statistici' ),
	'Randompage'                => array( 'Aleatoriu', 'Pagină aleatorie' ),
	'Lonelypages'               => array( 'Pagini orfane' ),
	'Uncategorizedpages'        => array( 'Pagini necategorizate' ),
	'Uncategorizedcategories'   => array( 'Categorii necategorizate' ),
	'Uncategorizedimages'       => array( 'Imagini necategorizate' ),
	'Uncategorizedtemplates'    => array( 'Formate necategorizate' ),
	'Unusedcategories'          => array( 'Categorii nefolosite' ),
	'Unusedimages'              => array( 'Imagini nefolosite' ),
	'Wantedpages'               => array( 'Pagini dorite', 'Legături invalide' ),
	'Wantedcategories'          => array( 'Categorii dorite' ),
	'Wantedfiles'               => array( 'Fişiere dorite' ),
	'Wantedtemplates'           => array( 'Formate dorite' ),
	'Mostlinked'                => array( 'Legături multe' ),
	'Mostlinkedcategories'      => array( 'Categorii des folosite' ),
	'Mostlinkedtemplates'       => array( 'Formate des folosite' ),
	'Mostimages'                => array( 'Imagini multe' ),
	'Mostcategories'            => array( 'Categorii multe' ),
	'Mostrevisions'             => array( 'Revizii multe' ),
	'Fewestrevisions'           => array( 'Revizii puţine' ),
	'Shortpages'                => array( 'Pagini scurte' ),
	'Longpages'                 => array( 'Pagini lungi' ),
	'Newpages'                  => array( 'Pagini noi' ),
	'Ancientpages'              => array( 'Pagini vechi' ),
	'Deadendpages'              => array( 'Pagini fără legături' ),
	'Protectedpages'            => array( 'Pagini protejate' ),
	'Protectedtitles'           => array( 'Titluri protejate' ),
	'Allpages'                  => array( 'Toate paginile' ),
	'Prefixindex'               => array( 'Index' ),
	'Ipblocklist'               => array( 'Listă IP blocat' ),
	'Specialpages'              => array( 'Pagini speciale' ),
	'Contributions'             => array( 'Contribuţii' ),
	'Emailuser'                 => array( 'Email utilizator' ),
	'Confirmemail'              => array( 'Confirmă email' ),
	'Whatlinkshere'             => array( 'Ce se leagă aici' ),
	'Recentchangeslinked'       => array( 'Modificări corelate' ),
	'Movepage'                  => array( 'Mută pagina' ),
	'Blockme'                   => array( 'Blochează-mă' ),
	'Booksources'               => array( 'Referinţe în cărţi' ),
	'Categories'                => array( 'Categorii' ),
	'Export'                    => array( 'Exportă' ),
	'Version'                   => array( 'Versiune' ),
	'Allmessages'               => array( 'Toate mesajele' ),
	'Log'                       => array( 'Jurnal', 'Jurnale' ),
	'Blockip'                   => array( 'Blochează IP' ),
	'Undelete'                  => array( 'Restaurează' ),
	'Import'                    => array( 'Importă' ),
	'Lockdb'                    => array( 'Blochează BD' ),
	'Unlockdb'                  => array( 'Deblochează BD' ),
	'Userrights'                => array( 'Drepturi utilizator' ),
	'MIMEsearch'                => array( 'Căutare MIME' ),
	'FileDuplicateSearch'       => array( 'Căutare fişier duplicat' ),
	'Unwatchedpages'            => array( 'Pagini neurmărite' ),
	'Listredirects'             => array( 'Listă redirectări' ),
	'Revisiondelete'            => array( 'Şterge revizie' ),
	'Unusedtemplates'           => array( 'Formate nefolosite' ),
	'Randomredirect'            => array( 'Redirectare aleatorie' ),
	'Mypage'                    => array( 'Pagina mea' ),
	'Mytalk'                    => array( 'Discuţiile mele' ),
	'Mycontributions'           => array( 'Contribuţiile mele' ),
	'Listadmins'                => array( 'Listă administratori' ),
	'Listbots'                  => array( 'Listă roboţi' ),
	'Popularpages'              => array( 'Pagini populare' ),
	'Search'                    => array( 'Căutare' ),
	'Resetpass'                 => array( 'Resetează parola' ),
	'Withoutinterwiki'          => array( 'Fără legături interwiki' ),
	'MergeHistory'              => array( 'Istoria combinărilor' ),
	'Filepath'                  => array( 'Cale fişier' ),
	'Invalidateemail'           => array( 'Invalidează email' ),
	'Blankpage'                 => array( 'Pagină goală' ),
	'LinkSearch'                => array( 'Căutare legături' ),
	'DeletedContributions'      => array( 'Contibuţii şterse' ),
	'Tags'                      => array( 'Etichete' ),
	'Activeusers'               => array( 'Utilizatori activi' ),
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
);

$fallback8bitEncoding = 'iso8859-2';

$linkTrail = '/^([a-zăâîşţșțĂÂÎŞŢȘȚ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Subliniază legăturile',
'tog-highlightbroken'         => 'Formatează legăturile necreate <a href="" class="new">aşa</a> (alternativă: aşa<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Aranjează justificat paragrafele',
'tog-hideminor'               => 'Ascunde modificările minore în schimbări recente',
'tog-hidepatrolled'           => 'Ascunde în schimbări recente editările patrulate',
'tog-newpageshidepatrolled'   => 'Ascunde paginile patrulate din lista de pagini noi',
'tog-extendwatchlist'         => 'Extinde lista de articole urmărite pentru a arăta toate schimbările efectuate, nu doar pe cele mai recente',
'tog-usenewrc'                => 'Afişează varianta îmbunătăţită a schimbărilor recente (necesită JavaScript)',
'tog-numberheadings'          => 'Numerotează automat secţiunile',
'tog-showtoolbar'             => 'Afişează bara de unelte pentru modificare (JavaScript)',
'tog-editondblclick'          => 'Modifică pagini la dublu click (JavaScript)',
'tog-editsection'             => 'Activează modificarea secţiunilor prin legăturile [modifică]',
'tog-editsectiononrightclick' => 'Activează modificarea secţiunilor prin click dreapta
pe titlul secţiunii (JavaScript)',
'tog-showtoc'                 => 'Arată cuprinsul (pentru paginile cu mai mult de 3 paragrafe cu titlu)',
'tog-rememberpassword'        => 'Aminteşte-ţi între sesiuni',
'tog-editwidth'               => 'Extinde câmpul de editare pe tot ecranul',
'tog-watchcreations'          => 'Adaugă paginile pe care le creez la lista mea de urmărire',
'tog-watchdefault'            => 'Adaugă paginile pe care le modific la lista mea de urmărire',
'tog-watchmoves'              => 'Adaugă paginile pe care le mut la lista mea de urmărire',
'tog-watchdeletion'           => 'Adaugă paginile pe care le şterg în lista mea de urmărire',
'tog-minordefault'            => 'Marchează toate modificările minore din oficiu',
'tog-previewontop'            => 'Arată previzualizarea înainte de a modifica secţiunea',
'tog-previewonfirst'          => 'Arată previzualizarea la prima modificare',
'tog-nocache'                 => 'Dezactivează cache-ul paginilor',
'tog-enotifwatchlistpages'    => 'Trimite-mi un email la modificările paginilor',
'tog-enotifusertalkpages'     => 'Trimite-mi un email când pagina mea de discuţii este modificată',
'tog-enotifminoredits'        => 'Trimite-mi un email de asemenea pentru modificările minore ale paginilor',
'tog-enotifrevealaddr'        => 'Descoperă-mi adresa email în mesajele de notificare',
'tog-shownumberswatching'     => 'Arată numărul utilizatorilor care urmăresc',
'tog-oldsig'                  => 'Previzualizarea semnăturii actuale:',
'tog-fancysig'                => 'Tratează semnătura ca wikitext (fără o legătură automată)',
'tog-externaleditor'          => 'Utilizează modificator extern ca standard',
'tog-externaldiff'            => 'Utilizează diferenţele externe ca standard',
'tog-showjumplinks'           => 'Activează legăturile de accesibilitate "salt la"',
'tog-uselivepreview'          => 'Utilizează previzualizarea directă (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avertizează-mă când uit să descriu modificările',
'tog-watchlisthideown'        => 'Ascunde modificările mele la lista mea de urmărire',
'tog-watchlisthidebots'       => 'Ascunde modificările boţilor la lista mea de urmărire',
'tog-watchlisthideminor'      => 'Ascunde modificările minore la lista mea de urmărire',
'tog-watchlisthideliu'        => 'Ascunde modificările făcute de utilizatori anonimi din lista de pagini urmărite',
'tog-watchlisthideanons'      => 'Ascunde modificările făcute de utilizatori anonimi din lista de pagini urmărite',
'tog-watchlisthidepatrolled'  => 'Ascunde paginile patrulate din lista de pagini urmărite',
'tog-nolangconversion'        => 'Dezactivează conversia variabilelor',
'tog-ccmeonemails'            => 'Trimite-mi o copie când trimit un email altui utilizator',
'tog-diffonly'                => 'Nu arăta conţinutul paginii sub dif',
'tog-showhiddencats'          => 'Arată categoriile ascunse',
'tog-noconvertlink'           => 'Dezactivaţi conversia titlurilor',
'tog-norollbackdiff'          => 'Nu arăta diferenţa după efectuarea unei reveniri',

'underline-always'  => 'Întotdeauna',
'underline-never'   => 'Niciodată',
'underline-default' => 'Standardul browser-ului',

# Font style option in Special:Preferences
'editfont-style'     => 'Stilul fontului din zona de modificare:',
'editfont-default'   => 'Standardul browser-ului',
'editfont-monospace' => 'Font monospaţiat',
'editfont-sansserif' => 'Font fără serife',
'editfont-serif'     => 'Font cu serife',

# Dates
'sunday'        => 'duminică',
'monday'        => 'luni',
'tuesday'       => 'marţi',
'wednesday'     => 'miercuri',
'thursday'      => 'joi',
'friday'        => 'vineri',
'saturday'      => 'sâmbătă',
'sun'           => 'dum',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mie',
'thu'           => 'joi',
'fri'           => 'vin',
'sat'           => 'sâm',
'january'       => 'ianuarie',
'february'      => 'februarie',
'march'         => 'martie',
'april'         => 'aprilie',
'may_long'      => 'mai',
'june'          => 'iunie',
'july'          => 'iulie',
'august'        => 'august',
'september'     => 'septembrie',
'october'       => 'octombrie',
'november'      => 'noiembrie',
'december'      => 'decembrie',
'january-gen'   => 'ianuarie',
'february-gen'  => 'februarie',
'march-gen'     => 'martie',
'april-gen'     => 'aprilie',
'may-gen'       => 'mai',
'june-gen'      => 'iunie',
'july-gen'      => 'iulie',
'august-gen'    => 'august',
'september-gen' => 'septembrie',
'october-gen'   => 'octombrie',
'november-gen'  => 'noiembrie',
'december-gen'  => 'decembrie',
'jan'           => 'ian',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'mai',
'jun'           => 'iun',
'jul'           => 'iul',
'aug'           => 'aug',
'sep'           => 'sept',
'oct'           => 'oct',
'nov'           => 'noi',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categorie|Categorii}}',
'category_header'                => 'Articole din categoria "$1"',
'subcategories'                  => 'Subcategorii',
'category-media-header'          => 'Fişiere media din categoria „$1”',
'category-empty'                 => "''Această categorie nu conţine articole sau fişiere media.''",
'hidden-categories'              => '{{PLURAL:$1|categorie ascunsă|categorii ascunse}}',
'hidden-category-category'       => 'Categorii ascunse',
'category-subcat-count'          => '{{PLURAL:$2|Această categorie conţine doar următoarea subcategorie.|Această categorie conţine {{PLURAL:$1|următoarea subcategorie|următoarele $1 subcategorii}}, dintr-un total de $2.}}',
'category-subcat-count-limited'  => 'Această categorie conţine {{PLURAL:$1|următoarea subcategorie|următoarele $1 subcategorii}}.',
'category-article-count'         => '{{PLURAL:$2|Această categorie conţine doar următoarea pagină.|{{PLURAL:$1|Următoarea pagină|Următoarele $1 pagini}} se află în această categorie, dintr-un total de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Următoarea pagină|Următoarele $1 pagini}} se află în categoria curentă.',
'category-file-count'            => '{{PLURAL:$2|Această categorie conţine doar următorul fişier.|{{PLURAL:$1|Următorul fişier|Următoarele $1 fişiere}} se află în această categorie, dintr-un total de $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Următorul fişier|Următoarele $1 fişiere}} se află în categoria curentă.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Pagini indexate',
'noindex-category'               => 'Pagini neindexate',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "<big>'''Programul Wiki a fost instalat cu succes.'''</big>",
'mainpagedocfooter' => 'Consultă [http://meta.wikimedia.org/wiki/Help:Contents Ghidul utilizatorului (en)] pentru informaţii despre utilizarea programului wiki.

== Primii paşi ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista parametrilor configurabili (en)]
* [http://www.mediawiki.org/wiki/Manual:FAQ Întrebări frecvente despre MediaWiki (en)]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Listă discuţii MediaWiki (en)]',

'about'         => 'Despre',
'article'       => 'Articol',
'newwindow'     => '(se deschide într-o fereastră nouă)',
'cancel'        => 'Anulează',
'moredotdotdot' => 'Mai mult…',
'mypage'        => 'Pagina mea',
'mytalk'        => 'Discuţii',
'anontalk'      => 'Discuţia pentru această adresă IP',
'navigation'    => 'Navigare',
'and'           => '&#32;şi',

# Cologne Blue skin
'qbfind'         => 'Găseşte',
'qbbrowse'       => 'Răsfoieşte',
'qbedit'         => 'Modifică',
'qbpageoptions'  => 'Opţiuni ale paginii',
'qbpageinfo'     => 'Informaţii ale paginii',
'qbmyoptions'    => 'Paginile mele',
'qbspecialpages' => 'Pagini speciale',
'faq'            => 'Întrebări frecvente',
'faqpage'        => 'Project:Întrebări frecvente',

# Vector skin
'vector-action-addsection'   => 'Adaugă subiect',
'vector-action-delete'       => 'Şterge',
'vector-action-move'         => 'Redenumeşte',
'vector-action-protect'      => 'Protejează',
'vector-action-undelete'     => 'Recuperare',
'vector-action-unprotect'    => 'Deprotejează',
'vector-namespace-category'  => 'Categorie',
'vector-namespace-help'      => 'Pagină de ajutor',
'vector-namespace-image'     => 'Fişier',
'vector-namespace-main'      => 'Pagină',
'vector-namespace-media'     => 'Pagină media',
'vector-namespace-mediawiki' => 'Mesaj',
'vector-namespace-project'   => 'Pagină proiect',
'vector-namespace-special'   => 'Pagină specială',
'vector-namespace-talk'      => 'Discuţie',
'vector-namespace-template'  => 'Format',
'vector-namespace-user'      => 'Pagină de utilizator',
'vector-view-create'         => 'Creează',
'vector-view-edit'           => 'Modifică',
'vector-view-history'        => 'Vezi istoric',
'vector-view-view'           => 'Citeşte',
'vector-view-viewsource'     => 'Vezi sursă',
'actions'                    => 'Acţiuni',
'namespaces'                 => 'Spaţii de nume',
'variants'                   => 'Variante',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Eroare',
'returnto'          => 'Înapoi la $1.',
'tagline'           => 'De la {{SITENAME}}',
'help'              => 'Ajutor',
'search'            => 'Caută',
'searchbutton'      => 'Caută',
'go'                => 'Du-te',
'searcharticle'     => 'Du-te',
'history'           => 'Versiuni mai vechi',
'history_short'     => 'Istoric',
'updatedmarker'     => 'încărcat de la ultima mea vizită',
'info_short'        => 'Informaţii',
'printableversion'  => 'Versiune de tipărit',
'permalink'         => 'Legătură permanentă',
'print'             => 'Tipărire',
'edit'              => 'Modifică',
'create'            => 'Creează',
'editthispage'      => 'Modifică pagina',
'create-this-page'  => 'Crează această pagină',
'delete'            => 'Şterge',
'deletethispage'    => 'Şterge pagina',
'undelete_short'    => 'Recuperarea {{PLURAL:$1|unei editări|a $1 editări}}',
'protect'           => 'Protejează',
'protect_change'    => 'schimbă protecţia',
'protectthispage'   => 'Protejează pagina',
'unprotect'         => 'Deprotejare',
'unprotectthispage' => 'Deprotejează pagina',
'newpage'           => 'Pagină nouă',
'talkpage'          => 'Discută pagina',
'talkpagelinktext'  => 'Discuţie',
'specialpage'       => 'Pagină Specială',
'personaltools'     => 'Unelte personale',
'postcomment'       => 'Secţiune nouă',
'articlepage'       => 'Vezi articolul',
'talk'              => 'Discuţie',
'views'             => 'Vizualizări',
'toolbox'           => 'Trusa de unelte',
'userpage'          => 'Vizualizaţi pagina utilizatorului',
'projectpage'       => 'Vezi pagina proiectului',
'imagepage'         => 'Vizualizaţi pagina fişierului',
'mediawikipage'     => 'Vizualizaţi pagina mesajului',
'templatepage'      => 'Vizualizaţi pagina formatului',
'viewhelppage'      => 'Vizualizaţi pagina de ajutor',
'categorypage'      => 'Vizualizaţi pagina categoriei',
'viewtalkpage'      => 'Vizualizaţi discuţia',
'otherlanguages'    => 'În alte limbi',
'redirectedfrom'    => '(Redirecţionat de la $1)',
'redirectpagesub'   => 'Pagină de redirecţionare',
'lastmodifiedat'    => 'Ultima modificare $2, $1.',
'viewcount'         => 'Pagina a fost vizitată {{PLURAL:$1|odată|de $1 ori}}.',
'protectedpage'     => 'Pagină protejată',
'jumpto'            => 'Salt la:',
'jumptonavigation'  => 'navigare',
'jumptosearch'      => 'căutare',
'view-pool-error'   => 'Ne pare rău, dar serverele sunt supraîncărcare în acest moment.
Prea multi utilizatori încearcă să vizualizeze această pagină.
Vă rugăm să aşteptaţi un moment înainte să încercaţi să accesaţi pagina din nou.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Despre {{SITENAME}}',
'aboutpage'            => 'Project:Despre',
'copyright'            => 'Conţinutul este disponibil sub $1.',
'copyrightpage'        => '{{ns:project}}:Drepturi de autor',
'currentevents'        => 'Discută la cafenea',
'currentevents-url'    => 'Project:Cafenea',
'disclaimers'          => 'Termeni',
'disclaimerpage'       => 'Project:Termeni',
'edithelp'             => 'Ajutor pentru modificare',
'edithelppage'         => 'Help:Cum să modifici o pagină',
'helppage'             => 'Help:Ajutor',
'mainpage'             => 'Pagina principală',
'mainpage-description' => 'Pagina principală',
'policy-url'           => 'Project:Politică',
'portal'               => 'Portalul comunităţii',
'portal-url'           => 'Project:Portal Comunitate',
'privacy'              => 'Politica de confidenţialitate',
'privacypage'          => 'Project:Politica de confidenţialitate',

'badaccess'        => 'Eroare permisiune',
'badaccess-group0' => 'Execuţia acţiunii cerute nu este permisă.',
'badaccess-groups' => 'Acţiunea cerută este rezervată utilizatorilor din {{PLURAL:$2|grupul|unul din grupurile}}: $1.',

'versionrequired'     => 'Este necesară versiunea $1 MediaWiki',
'versionrequiredtext' => 'Versiunea $1 MediaWiki este necesară pentru a folosi această pagină. Vezi [[Special:Version|versiunea actuală]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Adus de la "$1"',
'youhavenewmessages'      => 'Aveţi $1 ($2).',
'newmessageslink'         => 'mesaje noi',
'newmessagesdifflink'     => 'comparaţie cu versiunea precedentă',
'youhavenewmessagesmulti' => 'Aveţi mesaje noi la $1',
'editsection'             => 'modifică',
'editold'                 => 'modifică',
'viewsourceold'           => 'vizualizaţi sursa',
'editlink'                => 'modifică',
'viewsourcelink'          => 'vezi sursa',
'editsectionhint'         => 'Modifică secţiunea: $1',
'toc'                     => 'Cuprins',
'showtoc'                 => 'arată',
'hidetoc'                 => 'ascunde',
'thisisdeleted'           => 'Vizualizare sau recuperare $1?',
'viewdeleted'             => 'Vizualizează $1?',
'restorelink'             => '{{PLURAL:$1|o modificare ştearsă|$1 modificări şterse}}',
'feedlinks'               => 'Întreţinere:',
'feed-invalid'            => 'Tip de abonament invalid',
'feed-unavailable'        => 'Nu sunt disponibile fluxuri web.',
'site-rss-feed'           => '$1 Abonare RSS',
'site-atom-feed'          => '$1 Abonare Atom',
'page-rss-feed'           => '„$1” Abonare RSS',
'page-atom-feed'          => '„$1” Abonare Atom',
'red-link-title'          => '$1 (pagină inexistentă)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pagină',
'nstab-user'      => 'Pagină de utilizator',
'nstab-media'     => 'Pagină Media',
'nstab-special'   => 'Pagină specială',
'nstab-project'   => 'Proiect',
'nstab-image'     => 'Fişier',
'nstab-mediawiki' => 'Mesaj',
'nstab-template'  => 'Format',
'nstab-help'      => 'Ajutor',
'nstab-category'  => 'Categorie',

# Main script and global functions
'nosuchaction'      => 'Această acţiune nu există',
'nosuchactiontext'  => 'Acţiunea specificată în URL este invalidă.
S-ar putea să fi introdus greşit URL-ul, sau să fi urmat o legătură incorectă.
Aceasta s-ar putea să indice şi un bug în programul folosit de {{SITENAME}}.',
'nosuchspecialpage' => 'Această pagină specială nu există',
'nospecialpagetext' => 'Ai cerut o [[Special:SpecialPages|pagină specială]] care nu este recunoscută de {{SITENAME}}.',

# General errors
'error'                => 'Eroare',
'databaseerror'        => 'Eroare la baza de date',
'dberrortext'          => 'A apărut o eroare în sintaxa interogării.
Aceasta poate indica o problemă în program.
Ultima interogare încercată a fost:
<blockquote><tt>$1</tt></blockquote>
din cadrul funcţiei "<tt>$2</tt>".
Baza de date a returnat eroarea "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'A apărut o eroare de sintaxă în interogare.
Ultima interogare încercată a fost:
„$1”
din funcţia „$2”.
Baza de date a returnat eroarea „$3: $4”',
'laggedslavemode'      => 'Atenţie: S-ar putea ca pagina să nu conţină ultimele actualizări.',
'readonly'             => 'Baza de date este blocată la scriere',
'enterlockreason'      => 'Precizează motivul pentru blocare, incluzând o estimare a termenului de deblocare a bazei de date',
'readonlytext'         => 'Baza de date {{SITENAME}} este momentan blocată la scriere, probabil pentru o operaţiune de rutină, după care va fi deblocată şi se va reveni la starea normală.

Administratorul care a blocat-o a oferit această explicaţie: $1',
'missing-article'      => 'Baza de date nu găseşte textul unei pagini care ar fi trebuit găsit, numit „$1” $2.

În mod normal faptul este cauzat de urmărirea unei dif neactualizată sau a unei legături din istoric spre o pagină care a fost ştearsă.

Dacă nu acesta e motivul, s-ar putea să fi găsit un bug în program.
Te rog anunţă acest aspect unui [[Special:ListUsers/sysop|administrator]], indicându-i adresa URL.',
'missingarticle-rev'   => '(versiunea#: $1)',
'missingarticle-diff'  => '(Dif: $1, $2)',
'readonly_lag'         => 'Baza de date a fost închisă automatic în timp ce serverele secundare ale bazei de date îl urmează pe cel principal.',
'internalerror'        => 'Eroare internă',
'internalerror_info'   => 'Eroare internă: $1',
'filecopyerror'        => 'Fişierul "$1" nu a putut fi copiat la "$2".',
'filerenameerror'      => 'Fişierul "$1" nu a putut fi mutat la "$2".',
'filedeleteerror'      => 'Fişierul "$1" nu a putut fi şters.',
'directorycreateerror' => 'Nu se poate crea directorul "$1".',
'filenotfound'         => 'Fişierul "$1" nu a putut fi găsit.',
'fileexistserror'      => 'Imposibil de scris fişierul "$1": fişierul există deja',
'unexpected'           => 'Valoare neaşteptată: "$1"="$2".',
'formerror'            => 'Eroare: datele nu au putut fi trimise',
'badarticleerror'      => 'Această acţiune nu poate fi efectuată pe această pagină.',
'cannotdelete'         => 'Comanda de ştergere nu s-a putut executa! Probabil că ştergerea a fost operată între timp.',
'badtitle'             => 'Titlu incorect',
'badtitletext'         => 'Titlul căutat a fost invalid, gol sau o legătură invalidă inter-linguală sau inter-wiki.

Poate conţine unul sau mai multe caractere ce nu poate fi folosit în titluri.',
'perfcached'           => 'Datele următoare au fost păstrate în cache şi s-ar putea să nu fie la zi.',
'perfcachedts'         => "Informaţiile de mai jos provin din ''cache''; ultima actualizare s-a efectuat la $1.",
'querypage-no-updates' => 'Actualizările acestei pagini sunt momentan dezactivate. Informaţiile de aici nu sunt împrospătate.',
'wrong_wfQuery_params' => 'Număr incorect de parametri pentru wfQuery()<br />
Funcţia: $1<br />
Interogarea: $2',
'viewsource'           => 'Vezi sursa',
'viewsourcefor'        => 'pentru $1',
'actionthrottled'      => 'Acţiune limitată',
'actionthrottledtext'  => 'Ca o măsură anti-spam, aveţi permisiuni limitate în a efectua această acţiune de prea multe ori într-o perioadă scurtă de timp, iar dv. tocmai aţi depăşit această limită.
Vă rugăm să încercaţi din nou în câteva minute.',
'protectedpagetext'    => 'Această pagină este protejată împotriva modificărilor.',
'viewsourcetext'       => 'Se poate vizualiza şi copia conţinutul acestei pagini:',
'protectedinterface'   => 'Această pagină asigură textul interfeţei pentru software şi este protejată pentru a preveni abuzurile.',
'editinginterface'     => "'''Avertizare''': Editezi o pagină care este folosită pentru a furniza textul interfeţei pentru software. Modificările aduse acestei pagini vor afecta aspectul interfeţei utilizatorului pentru alţi utilizatori. Pentru traduceri, consideraţi utilizarea [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], proiectul MediaWiki de localizare.",
'sqlhidden'            => '(interogare SQL ascunsă)',
'cascadeprotected'     => 'Această pagină a fost protejată la scriere deoarece este inclusă în {{PLURAL:$1|următoarea pagină|următoarele pagini}}, care {{PLURAL:$1|este protejată|sunt protejate}} în cascadă:
$2',
'namespaceprotected'   => "Nu ai permisiunea de a edita pagini în spaţiul de nume '''$1'''.",
'customcssjsprotected' => 'Nu aveţi permisiunea să editaţi această pagină, deoarece conţine datele private ale unui alt utilizator.',
'ns-specialprotected'  => 'Paginile din spaţiul de nume {{ns:special}} nu pot fi editate.',
'titleprotected'       => "Acest titlu a fos protejat la creare de [[User:$1|$1]].
Motivul invocat este ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configuraţie greşită: scaner de virus necunoscut: ''$1''",
'virus-scanfailed'     => 'scanare eşuată (cod $1)',
'virus-unknownscanner' => 'antivirus necunoscut:',

# Login and logout pages
'logouttext'                 => 'Sesiunea ta în {{SITENAME}} a fost încheiată. Poţi continua să foloseşti {{SITENAME}} anonim, sau poţi să te [[Special:UserLogin|reautentifici]] ca acelaşi sau ca alt utilizator.',
'welcomecreation'            => '==Bun venit, $1!==

Contul dumneavoatră a fost creat. Nu uitaţi să vă personalizaţi [[Special:Preferences|preferinţele]] în {{SITENAME}}.',
'yourname'                   => 'Nume de utilizator:',
'yourpassword'               => 'Parolă:',
'yourpasswordagain'          => 'Repetă parola',
'remembermypassword'         => 'Reţine-mi parola între sesiuni',
'yourdomainname'             => 'Domeniul tău',
'externaldberror'            => 'A fost fie o eroare de bază de date pentru o autentificare extenă sau nu aveţi permisiunea să actualizaţi contul extern.',
'login'                      => 'Autentificare',
'nav-login-createaccount'    => 'Creare cont / Autentificare',
'loginprompt'                => 'Trebuie să ai modulele cookie activate pentru a te autentifica la {{SITENAME}}.',
'userlogin'                  => 'Creare cont / Autentificare',
'logout'                     => 'Închide sesiunea',
'userlogout'                 => 'Închide sesiunea',
'notloggedin'                => 'Nu sunteţi autentificat',
'nologin'                    => "Nu aveţi cont încă? '''$1'''.",
'nologinlink'                => 'Creaţi-vă un cont de utilizator acum',
'createaccount'              => 'Creare cont',
'gotaccount'                 => "Aveţi deja un cont de utilizator? '''$1'''.",
'gotaccountlink'             => 'Autentificaţi-vă',
'createaccountmail'          => 'după e-mail',
'badretype'                  => 'Parolele pe care le-ai introdus diferă.',
'userexists'                 => 'Numele de utilizator pe care l-aţi introdus există deja. Încercaţi cu un alt nume.',
'loginerror'                 => 'Eroare de autentificare',
'createaccounterror'         => 'Nu pot crea contul: $1',
'nocookiesnew'               => 'Contul a fost creat, dar dvs. nu sunteţi autentificat(ă). {{SITENAME}} foloseşte cookie-uri pentru a reţine utilizatorii autentificaţi. Browser-ul dvs. are modulele cookie dezactivate (disabled). Vă rugăm să le activaţi şi să vă reautentificaţi folosind noul nume de utilizator şi noua parolă.',
'nocookieslogin'             => '{{SITENAME}} foloseşte module cookie pentru a autentifica utilizatorii. Browser-ul dvs. are cookie-urile dezactivate. Vă rugăm să le activaţi şi să incercaţi din nou.',
'noname'                     => 'Numele de utilizator pe care l-ai specificat este invalid.',
'loginsuccesstitle'          => 'Autentificare reuşită',
'loginsuccess'               => 'Aţi fost autentificat în {{SITENAME}} ca "$1".',
'nosuchuser'                 => 'Nu există nici un utilizator cu numele „$1”.
Numele de utilizatori sunt sensibile la majuscule.
Verifică dacă ai scris corect sau [[Special:UserLogin/signup|creează un nou cont de utilizator]].',
'nosuchusershort'            => 'Nu este nici un utilizator cu numele „<nowiki>$1</nowiki>”. Verificaţi dacă aţi scris corect.',
'nouserspecified'            => 'Trebuie să specificaţi un nume de utilizator.',
'wrongpassword'              => 'Parola pe care ai introdus-o este greşită. Te rugăm să încerci din nou.',
'wrongpasswordempty'         => 'Spaţiul pentru introducerea parolei nu a fost completat. Vă rugăm să încercaţi din nou.',
'passwordtooshort'           => 'Parola trebuie să aibă cel puţin {{PLURAL:$1|1 caracter|$1 caractere}}.',
'password-name-match'        => 'Parola dumneavoastră trebuie să fie diferită de numele de utilizator.',
'mailmypassword'             => 'Trimite-mi parola pe e-mail!',
'passwordremindertitle'      => 'Noua parolă temporară la {{SITENAME}}',
'passwordremindertext'       => 'Cineva (probabil dumneavoastră, de la adresa $1)
a cerut să vi se trimită o nouă parolă pentru {{SITENAME}} ($4).
O parolă temporară pentru utilizatorul "$2" este acum "$3".
Parola temporară va expirs {{PLURAL:$5|într-o zi|în $5 zile}}.

Dacă această cerere a fost efectuată de altcineva sau dacă v-aţi amintit 
parola şi nu doriţi să o schimbaţi, ignoraţi acest mesaj şi continuaţi 
să folosiţi vechea parolă.',
'noemail'                    => 'Nu este nici o adresă de e-mail înregistrată pentru utilizatorul „$1”.',
'noemailcreate'              => 'Trebuie oferită o adresă e e-mail validă.',
'passwordsent'               => 'O nouă parolă a fost trimisă la adresa de e-mail a utilizatorului "$1". Te rugăm să te autentifici pe {{SITENAME}} după ce o primeşti.',
'blocked-mailpassword'       => 'Această adresă IP este blocată la editare, şi deci nu este permisă utilizarea funcţiei de recuperare a parolei pentru a preveni abuzul.',
'eauthentsent'               => 'Un email de confirmare a fost trimis adresei nominalizate. Înainte de a fi trimis orice alt email acestui cont, trebuie să urmaţi intrucţiunile din email, pentru a confirma că acest cont este într-adevăr al dvs.',
'throttled-mailpassword'     => 'O parolă a fost deja trimisă în {{PLURAL:$1|ultima oră|ultimele $1 ore}}. Pentru a preveni abuzul, se poate trimite doar o parolă la {{PLURAL:$1|o oră|$1 ore}}.',
'mailerror'                  => 'Eroare la trimitere e-mail: $1',
'acct_creation_throttle_hit' => 'De la această adresă IP, vizitatorii sitului au creat {{PLURAL:$1|1 cont|$1 conturi}} de utilizator în ultimele zile, acest număr de noi conturi fiind maximul admis în această perioadă de timp.
Prin urmare, vizitatorii care folosesc acelaşi IP nu mai pot crea alte conturi pentru moment.',
'emailauthenticated'         => 'Adresa de e-mail a fost autentificată pe $2, la $3.',
'emailnotauthenticated'      => 'Adresa de email <strong>nu este autentificată încă</strong>. Nici un email nu va fi trimis pentru nici una din întrebuinţările următoare.',
'noemailprefs'               => 'Nu a fost specificată o adresă email, următoarele nu vor funcţiona.',
'emailconfirmlink'           => 'Confirmaţi adresa dvs. de email',
'invalidemailaddress'        => 'Adresa de email nu a putut fi acceptată pentru că pare a avea un format invalid. Vă rugăm să reintroduceţi o adresă bine formatată sau să goliţi acel câmp.',
'accountcreated'             => 'Contul a fost creat.',
'accountcreatedtext'         => 'Contul utilizatorului pentru $1 a fost creat.',
'createaccount-title'        => 'Creare de cont la {{SITENAME}}',
'createaccount-text'         => 'Cineva a creat un cont cu adresa dvs. de e-mail pe {{SITENAME}} ($4) numit "$2", având parola "$3".
Este de dorit să vă autentificaţi şi să schimbaţi parola cât mai repede.

Ignoraţi acest mesaj, dacă acea creare a fost o greşeală.',
'login-throttled'            => 'Aţi avut prea multe încercări de a vă autentifica.
Vă rugăm să aşteptaţi până să mai încercaţi.',
'loginlanguagelabel'         => 'Limba: $1',

# Password reset dialog
'resetpass'                 => 'Modifică parola',
'resetpass_announce'        => 'Sunteţi autentificat cu un cod temporar trimis pe mail. Pentru a termina acţiunea de autentificare, trebuie să setaţi o parolă nouă aici:',
'resetpass_text'            => '<!-- Adaugă text aici -->',
'resetpass_header'          => 'Modifică parola',
'oldpassword'               => 'Parola veche',
'newpassword'               => 'Parola nouă',
'retypenew'                 => 'Repetă parola nouă',
'resetpass_submit'          => 'Setează parola şi autentifică',
'resetpass_success'         => 'Parola a fost schimbată cu succes! Autentificare în curs...',
'resetpass_forbidden'       => 'Parolele nu pot fi schimbate.',
'resetpass-no-info'         => 'Trebuie să fiţi autentificat pentru a accesa această pagină direct.',
'resetpass-submit-loggedin' => 'Modifică parola',
'resetpass-wrong-oldpass'   => 'Parolă curentă sau temporară incorectă. 
Este posibil să fi reuşit deja schimbarea parolei sau să fi cerut o parolă temporară nouă.',
'resetpass-temp-password'   => 'Parolă temporară:',

# Edit page toolbar
'bold_sample'     => 'Text aldin',
'bold_tip'        => 'Text aldin',
'italic_sample'   => 'Text cursiv',
'italic_tip'      => 'Text cursiv',
'link_sample'     => 'Titlul legăturii',
'link_tip'        => 'Legătură internă',
'extlink_sample'  => 'http://www.example.com titlul legăturii',
'extlink_tip'     => 'Legătură externă (nu uitaţi prefixul http://)',
'headline_sample' => 'Text de titlu',
'headline_tip'    => 'Titlu de nivel 2',
'math_sample'     => 'Introduceţi formula aici',
'math_tip'        => 'Formulă matematică (LaTeX)',
'nowiki_sample'   => 'Introduceţi text neformatat aici',
'nowiki_tip'      => 'Ignoră formatarea wiki',
'image_sample'    => 'Exemplu.jpg',
'image_tip'       => 'Fişier inserat',
'media_sample'    => 'Exemplu.ogg',
'media_tip'       => 'Legătură la fişier',
'sig_tip'         => 'Semnătura dvs. datată',
'hr_tip'          => 'Linie orizontală (folosiţi-o cumpătat)',

# Edit pages
'summary'                          => 'Rezumat:',
'subject'                          => 'Subiect / titlu:',
'minoredit'                        => 'Aceasta este o editare minoră',
'watchthis'                        => 'Urmăreşte această pagină',
'savearticle'                      => 'Salvează pagina',
'preview'                          => 'Previzualizare',
'showpreview'                      => 'Arată previzualizare',
'showlivepreview'                  => 'Previzualizare live',
'showdiff'                         => 'Arată diferenţele',
'anoneditwarning'                  => "'''Avertizare:''' Nu sunteţi logat(ă). Adresa IP vă va fi înregistrată în istoricul acestei pagini.",
'missingsummary'                   => "'''Atenţie:''' Nu aţi completat caseta „descriere modificări”. Dacă apăsaţi din nou butonul „salvează pagina” modificările vor fi salvate fără descriere.",
'missingcommenttext'               => 'Vă rugăm să introduceţi un comentariu.',
'missingcommentheader'             => "'''Atenţie:''' Nu aţi furnizat un titlu/subiect pentru acest comentariu. Dacă daţi click pe \"Salvaţi din nou\", modificarea va fi salvată fără titlu.",
'summary-preview'                  => 'Previzualizare descriere:',
'subject-preview'                  => 'Previzualizare subiect/titlu:',
'blockedtitle'                     => 'Utilizatorul este blocat',
'blockedtext'                      => "<big>'''Adresa IP sau contul dumneavoastră de utilizator a fost blocat.'''</big>

Blocarea a fost făcută de $1.
Motivul blocării este ''$2''.

* Începutul blocării: $8
* Sfârşitul blocării: $6
* Utilizatorul vizat: $7

Îl puteţi contacta pe $1 sau pe alt [[{{MediaWiki:Grouppage-sysop}}|administrator]] pentru a discuta blocarea.
Nu puteţi folosi opţiunea 'trimite un e-mai utilizatorului' decât dacă o adresă de e-mail validă este specificată în [[Special:Preferences|preferinţele contului]] şi nu sunteţi blocat la folosirea ei.
Adresa dumneavoastră IP curentă este $3, iar ID-ul blocării este $5. Vă rugăm să includeţi oricare sau ambele informaţii în orice interogări.",
'autoblockedtext'                  => 'Această adresă IP a fost blocată automat deoarece a fost folosită de către un alt utilizator, care a fost blocat de $1.
Motivul blocării este:

:\'\'$2\'\'

* Începutul blocării: $8
* Sfârşitul blocării: $6
* Intervalul blocării: $7

Puteţi contacta pe $1 sau pe unul dintre ceilalţi [[{{MediaWiki:Grouppage-sysop}}|administratori]] pentru a discuta blocarea.

Nu veţi putea folosi opţiunea de "trimite e-mail" decât dacă aveţi înregistrată o adresă de e-mail validă la [[Special:Preferences|preferinţe]] şi nu sunteţi blocat la folosirea ei.

Aveţi adresa IP $3, iar identificatorul dumneavoastră de blocare este $5.
Vă rugăm să includeţi detaliile de mai sus în orice interogări pe care le faceţi.',
'blockednoreason'                  => 'nici un motiv oferit',
'blockedoriginalsource'            => "Sursa pentru '''$1''' apare mai jos:",
'blockededitsource'                => "Textul '''modificărilor dumneavoastră''' la  '''$1''' este redat mai jos:",
'whitelistedittitle'               => 'Este necesară autentificarea pentru a putea modifica',
'whitelistedittext'                => 'Trebuie să $1 pentru a edita articole.',
'confirmedittext'                  => 'Trebuie să vă confirmaţi adresa de e-mail înainte de a edita pagini. Vă rugăm să vă setaţi şi să vă validaţi adresa de e-mail cu ajutorul [[Special:Preferences|preferinţelor utilizatorului]].',
'nosuchsectiontitle'               => 'Nu există o astfel de secţiune',
'nosuchsectiontext'                => 'Aţi încercat să modificaţi o secţiune care nu există. Deoarece nu există secţiunea $1, modificarea nu va fi salvată.',
'loginreqtitle'                    => 'Necesită autentificare',
'loginreqlink'                     => 'autentifici',
'loginreqpagetext'                 => 'Trebuie să te $1 pentru a vizualiza alte pagini.',
'accmailtitle'                     => 'Parola a fost trimisă.',
'accmailtext'                      => "Parola generată automat pentru [[User talk:$1|$1]] a fost trimisă la $2.

Parola pentru acest nou cont poate fi schimbată după autentificare din ''[[Special:ChangePassword|schimbare parolă]]''",
'newarticle'                       => '(Nou)',
'newarticletext'                   => 'Ai ajuns la o pagină care nu există. Pentru a o crea, începe să scrii în caseta de mai jos (vezi [[{{MediaWiki:Helppage}}|pagina de ajutor]] pentru mai multe informaţii). Dacă ai ajuns aici din greşeală, întoarce-te folosind controalele browser-ului tău',
'anontalkpagetext'                 => "---- ''Aceasta este pagina de discuţii pentru un utilizator care nu şi-a creat un cont încă, sau care nu s-a autentificat.
De aceea trebuie să folosim adresă IP pentru a identifica această persoană.
O adresă IP poate fi folosită în comun de mai mulţi utilizatori.
Dacă sunteţi un astfel de utilizator şi credeţi că vă sunt adresate mesaje irelevante, vă rugăm să [[Special:UserLogin/signup|vă creaţi un cont]] sau să [[Special:UserLogin|vă autentificaţi]] pentru a evita confuzii cu alţi utilizatori anonimi în viitor.''",
'noarticletext'                    => 'În acest moment nu este niciun text în această pagină.
Puteţi [[Special:Search/{{PAGENAME}}|căuta acest titlu]] în alte pagini,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} căuta înregistrări în jurnale], sau [{{fullurl:{{FULLPAGENAME}}|action=edit}} crea această pagină]</span>.',
'noarticletext-nopermission'       => 'Nu este niciun text în această pagină.
Puteţi [[Special:Search/{{PAGENAME}}|căuta titlul paginii]] în alte pagini,
sau <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} să căutaţi în jurnale]</span>.',
'userpage-userdoesnotexist'        => 'Contul de utilizator "$1" nu este înregistrat. Verificaţi dacă doriţi să creaţi/modificaţi această pagină.',
'userpage-userdoesnotexist-view'   => 'Contul de utilizator "$1" nu este înregistrat.',
'clearyourcache'                   => "'''Notă:''' După salvare, trebuie să treceţi peste cache-ul browser-ului pentru a vedea modificările. '''Mozilla/Safari/Konqueror:''' ţineţi apăsat ''Shift'' în timp ce apăsaţi ''Reload'' (sau apăsaţi ''Ctrl-Shift-R''), '''IE:''' apăsaţi ''Ctrl-F5'', '''Opera:''' apăsaţi ''F5''.",
'usercssyoucanpreview'             => "'''Sfat:''' Foloseşte butonul 'Arată previzualizare' pentru a testa noul tău css/js înainte de a salva.",
'userjsyoucanpreview'              => "'''Sfat:''' Foloseşte butonul 'Arată previzualizare' pentru a testa noul tău css/js înainte de a salva.",
'usercsspreview'                   => "'''Reţine că urmăreşti doar o previzualizare a css-ului tău de utilizator, acesta nu este încă salvat!'''",
'userjspreview'                    => "'''Reţine că urmăreşti doar un test/o previzualizare a javascript-ului tău de utilizator, acesta nu este încă salvat!'''",
'userinvalidcssjstitle'            => '<b>Avertizare:</b> Nu există skin "$1". Aminteşte-ţi că paginile .css and .js specifice utilizatorilor au titluri care încep cu literă mică, de exemplu {{ns:user}}:Foo/monobook.css în comparaţie cu {{ns:user}}:Foo/Monobook.css.',
'updated'                          => '(Actualizat)',
'note'                             => "'''Notă:'''",
'previewnote'                      => "Aceasta este doar o previzualizare! Pentru a salva pagina în forma actuală, descrieţi succint modificările efectuate şi apăsaţi butonul '''Salvează pagina'''.",
'previewconflict'                  => 'Această pre-vizualizare reflectă textul din caseta de sus, respectiv felul în care va arăta articolul dacă alegeţi să-l salvaţi acum.',
'session_fail_preview'             => "'''Ne pare rău! Nu am putut procesa modificarea dumneavoastră din cauza pierderii datelor sesiunii.
Vă rugăm să încercaţi din nou.
Dacă tot nu funcţionează, încercaţi să [[Special:UserLogout|închideţi sesiunea]] şi să vă autentificaţi din nou.'''",
'session_fail_preview_html'        => "'''Ne pare rău! Modificările dvs. nu au putut fi procesate din cauza pierderii datelor sesiunii.''' 

''Deoarece {{SITENAME}} are activat HTML brut, previzualizarea este ascunsă ca măsură de precauţie împotriva atacurilor JavaScript.''

'''Dacă această încercare de modificare este legitimă, vă rugăm să încercaţi din nou. Dacă nu funcţionează nici în acest fel, [[Special:UserLogout|închideţi sesiunea]] şi încearcaţi să vă autentificaţi din nou.'''",
'token_suffix_mismatch'            => "'''Modificarea ta a fost refuzată pentru că clientul tău a deformat caracterele de punctuatie în modificarea semnului.
Modificarea a fost respinsă pentru a preveni deformarea textului paginii.
Acest fapt se poate întâmpla atunci când foloseşti un serviciu proxy anonim.'''",
'editing'                          => 'modificare $1',
'editingsection'                   => 'modificare $1 (secţiune)',
'editingcomment'                   => 'Modificare $1 (secţiune nouă)',
'editconflict'                     => 'Conflict de modificare: $1',
'explainconflict'                  => "Altcineva a modificat această pagină de când ai început să o editezi.
Caseta de text de sus conţine pagina aşa cum este ea acum (după editarea celeilalte persoane).
Pagina cu modificările tale (aşa cum ai încercat să o salvezi) se află în caseta de jos.
Va trebui să editezi manual caseta de sus pentru a reflecta modificările pe care tocmai le-ai făcut în cea de jos.
'''Numai''' textul din caseta de sus va fi salvat atunci când vei apăsa pe \"Salvează pagina\".",
'yourtext'                         => 'Textul tău',
'storedversion'                    => 'Versiunea curentă',
'nonunicodebrowser'                => "'''ATENŢIE: Browser-ul dumneavoastră nu este compilant unicode, vă rugăm să îl schimbaţi înainte de a începe modificarea unui articol.'''",
'editingold'                       => "'''ATENŢIE! Modifici o variantă mai veche a acestei pagini! Orice modificări care s-au făcut de la această versiune şi până la cea curentă se vor pierde!'''",
'yourdiff'                         => 'Diferenţe',
'copyrightwarning'                 => "Reţine că toate contribuţiile la {{SITENAME}} sunt distribuite sub licența $2 (vezi $1 pentru detalii).
Dacă nu doriţi ca ceea ce scrieţi să fie modificat fără milă şi redistribuit în voie, atunci nu trimiteţi materialele respective aici.<br />
De asemenea, ne asiguraţi că ceea ce aţi scris a fost compoziţie proprie sau copie dintr-o resursă publică sau liberă.
'''NU INTRODUCEŢI MATERIALE CU DREPTURI DE AUTOR FĂRĂ PERMISIUNE!'''",
'copyrightwarning2'                => "Reţineţi că toate contribuţiile la {{SITENAME}} pot fi modificate, alterate sau şterse de alţi contribuitori.
Dacă nu doriţi ca ceea ce scrieţi să fie modificat fără milă şi redistribuit în voie, atunci nu trimiteţi materialele respective aici.<br />
De asemenea, ne asiguraţi că ceea ce aţi scris a fost compoziţie proprie sau copie dintr-o resursă publică sau liberă (vedeţi $1 pentru detalii).
'''NU INTRODUCEŢI MATERIALE CU DREPTURI DE AUTOR FĂRĂ PERMISIUNE!'''",
'longpagewarning'                  => "'''ATENŢIE! Conţinutul acestei pagini are $1 kB; unele browsere au probleme la modificarea paginilor în jur de 32 kB sau mai mari. Te rugăm să iei în considerare posibilitatea de a împărţi pagina în mai multe secţiuni.'''",
'longpageerror'                    => "'''EROARE: Textul pe care vrei să-l salvezi are $1 kilobytes,
ceea ce înseamnă mai mult decât maximum de $2 kilobytes. Salvarea nu este posibilă.'''",
'readonlywarning'                  => "'''ATENŢIE: Baza de date a fost blocată pentru întreţinere, deci nu veţi putea salva modificările în acest moment. Puteţi copia textul într-un fişier text local pentru a-l salva când va fi posibil.'''

Administratorul care a efectuat blocarea a oferit următoarea explicaţie: $1",
'protectedpagewarning'             => "'''ATENŢIE! Această pagină a fost protejată la scriere şi numai utilizatorii cu privilegii de administrator o pot modifica.'''",
'semiprotectedpagewarning'         => "'''Atenţie:''' Această pagină poate fi modificată numai de utilizatorii înregistraţi.",
'cascadeprotectedwarning'          => "'''Atenţie:''' Această pagină a fost blocată astfel încât numai administratorii o pot modifica, deoarece este inclusă în {{PLURAL:$1|următoarea pagină protejată|următoarele pagini protejate}} în cascadă:",
'titleprotectedwarning'            => "'''ATENŢIE:  Această pagină a fost protejată, doar anumiţi [[Special:ListGroupRights|utilizatori]] o pot crea.'''",
'templatesused'                    => '{{PLURAL:$1|Format folosit|Formate folosite}} în această pagină:',
'templatesusedpreview'             => '{{PLURAL:$1|Format folosit|Formate folosite}} în această previzualizare:',
'templatesusedsection'             => '{{PLURAL:$1|Format utilizat|Formate utilizate}} în această secţiune:',
'template-protected'               => '(protejat)',
'template-semiprotected'           => '(semi-protejat)',
'hiddencategories'                 => 'Această pagină este membrul {{PLURAL:$1|unei categorii ascunse|a $1 categorii ascunse}}:',
'edittools'                        => '<!-- Acest text va apărea după caseta de editare şi formularele de trimitere fişier. -->',
'nocreatetitle'                    => 'Creare de pagini limitată',
'nocreatetext'                     => '{{SITENAME}} a restricţionat abilitatea de a crea pagini noi.
Puteţi edita o pagină deja existentă sau puteţi să vă [[Special:UserLogin|autentificaţi/creaţi]] un cont de utilizator.',
'nocreate-loggedin'                => 'Nu ai permisiunea să creezi pagini noi.',
'permissionserrors'                => 'Erori de permisiune',
'permissionserrorstext'            => 'Nu aveţi permisiune pentru a face acest lucru, din următoarele {{PLURAL:$1|motiv|motive}}:',
'permissionserrorstext-withaction' => 'Nu aveţi permisiunea să $2, din {{PLURAL:$1|următorul motivul|următoarele motive}}:',
'recreate-moveddeleted-warn'       => "'''Atenţie: Recreaţi o pagină care a fost ştearsă anterior.'''

Asiguraţi-vă că este oportună recrearea acestei pagini.
Jurnalul ştergerilor şi al mutărilor pentru această pagină este disponibil:",
'moveddeleted-notice'              => 'Această pagină a fost ştearsă.
Jurnalul ştergerilor şi al mutărilor este disponibil mai jos.',
'log-fulllog'                      => 'Vezi tot jurnalul',
'edit-hook-aborted'                => 'Modificarea a fost abandonată din cauza unui hook.
Fără nicio explicaţie.',
'edit-gone-missing'                => 'Pagina nu s-a putut actualiza.
Se pare că a fost ştearsă.',
'edit-conflict'                    => 'Conflict de modificare.',
'edit-no-change'                   => 'Modificarea dvs. a fost ignorată deoarece nu s-a efectuat nicio schimbare.',
'edit-already-exists'              => 'Pagina nouă nu a putut fi creată.
Ea există deja.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Atenţie: Această pagină conţine prea multe apelări costisitoare ale funcţiilor parser.

Ar trebui să existe mai puţin de $2 {{PLURAL:$2|apelare|apelări}}, acolo există {{PLURAL:$1|$1 apelare|$1 apelări}}.',
'expensive-parserfunction-category'       => 'Pagini cu prea multe apelări costisitoare de funcţii parser',
'post-expand-template-inclusion-warning'  => 'Atenţie: Formatele incluse sunt prea mari.
Unele formate nu vor fi incluse.',
'post-expand-template-inclusion-category' => 'Paginile în care este inclus formatul are o dimensiune prea mare',
'post-expand-template-argument-warning'   => 'Atenţie: Această pagină conţine cel puţin un argument al unui format care are o mărime prea mare atunci când este expandat.
Acsete argumente au fost omise.',
'post-expand-template-argument-category'  => 'Pagini care conţin formate cu argumente omise',
'parser-template-loop-warning'            => 'Buclă de formate detectată: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limită de adâncime a recursiei depăşită ($1)',

# "Undo" feature
'undo-success' => 'Modificarea poate fi anulată. Verificaţi diferenţa de dedesupt şi apoi salvaţi pentru a termina anularea modificării.',
'undo-failure' => 'Modificarea nu poate fi reversibilă datorită conflictului de modificări intermediare.',
'undo-norev'   => 'Modificarea nu poate fi reversibilă pentru că nu există sau pentru că a fost ştearsă.',
'undo-summary' => 'Anularea modificării $1 făcute de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discuţie]])',

# Account creation failure
'cantcreateaccounttitle' => 'Crearea contului nu poate fi realizată',
'cantcreateaccount-text' => "Crearea de conturi de la această adresă IP ('''$1''') a fost blocată de [[User:$3|$3]].

Motivul invocat de $3 este ''$2''",

# History pages
'viewpagelogs'           => 'Vezi jurnalele pentru această pagină',
'nohistory'              => 'Nu există istoric pentru această pagină.',
'currentrev'             => 'Versiunea curentă',
'currentrev-asof'        => 'Versiunea curentă din $1',
'revisionasof'           => 'Versiunea de la data $1',
'revision-info'          => 'Revizia pentru $1; $2',
'previousrevision'       => '←Versiunea anterioară',
'nextrevision'           => 'Versiunea următoare →',
'currentrevisionlink'    => 'afişează versiunea curentă',
'cur'                    => 'actuală',
'next'                   => 'următoarea',
'last'                   => 'prec',
'page_first'             => 'prim',
'page_last'              => 'ultim',
'histlegend'             => 'Legendă: (actuală) = diferenţe faţă de versiunea curentă,
(prec) = diferenţe faţă de versiunea precedentă, M = modificare minoră',
'history-fieldset-title' => 'Răsfoieşte istoricul',
'histfirst'              => 'Primele',
'histlast'               => 'Ultimele',
'historysize'            => '({{PLURAL:$1|1 octet|$1 octeţi}})',
'historyempty'           => '(gol)',

# Revision feed
'history-feed-title'          => 'Revizia istoricului',
'history-feed-description'    => 'Revizia istoricului pentru această pagină de pe wiki',
'history-feed-item-nocomment' => '$1 la $2',
'history-feed-empty'          => 'Pagina solicitată nu există.
E posibil să fi fost ştearsă sau redenumită.
Încearcă să [[Special:Search|cauţi]] pe wiki pentru pagini noi semnificative.',

# Revision deletion
'rev-deleted-comment'         => '(comentariu şters)',
'rev-deleted-user'            => '(nume de utilizator şters)',
'rev-deleted-event'           => '(intrare ştearsă)',
'rev-deleted-text-permission' => "Această revizie a paginii a fost '''ştearsă'''.
Mai multe detalii în [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].",
'rev-deleted-text-unhide'     => "Această revizie a paginii a fost '''ştearsă'''.
Pot exista detalii în [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].
Ca administrator puteţi [$1 vedea această revizie] în continuare, dacă doriţi acest lucru.",
'rev-suppressed-text-unhide'  => "Această revizie a paginii a fost '''suprimată'''.
Pot exista detalii în [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jurnalul suprimărilor].
Ca administrator puteţi [$1 vedea această revizie] în continuare, dacă doriţi acest lucru.",
'rev-deleted-text-view'       => "Această revizie a paginii a fost '''ştearsă'''.
Ca administrator puteţi să o vedeţi; s-ar putea să găsiţi mai multe detalii în [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].",
'rev-suppressed-text-view'    => "Această revizie a paginii a fost '''suprimată'''.
Ca administrator puteţi să o vedeţi; s-ar putea să găsiţi mai multe detalii în [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jurnalul suprimărilor].",
'rev-deleted-no-diff'         => "Nu poţi vedea acestă diferenţă deoarece una dintre revizii a fost '''ştearsă'''.
Pot exista mai multe detalii în [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].",
'rev-deleted-unhide-diff'     => "Una din reviziile acestui istoric a fost '''ştearsă'''.
Pot exista detalii în [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].
Ca administrator puteţi [$1 vedea diferenţa] în continuare, dacă doriţi acest lucru.",
'rev-delundel'                => 'arată/ascunde',
'revisiondelete'              => 'Şterge/recuperează revizii',
'revdelete-nooldid-title'     => 'Revizie invalidă',
'revdelete-nooldid-text'      => 'Nu ai specificat revizie pentru a efectua această
funcţie, revizia specificată nu există, sau eşti pe cale să ascunzi revizia curentă.',
'revdelete-nologtype-title'   => 'Niciun tip de jurnal specificat',
'revdelete-nologtype-text'    => 'Nu ai specificat niciun tip de jurnal pentru a putea efectua această acţiune.',
'revdelete-nologid-title'     => 'Intrare în jurnal invalidă',
'revdelete-nologid-text'      => 'Ori nu nu ai specificat o ţintă pentru jurnal pentru a efectua această funcţie sau intrarea specificată nu există.',
'revdelete-no-file'           => 'Fişierul specificat nu există.',
'revdelete-show-file-confirm' => 'Eşti sigur că doreşti să vezi o revizie ştearsă a fişierului "<nowiki>$1</nowiki>" din $2 la $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Revizia aleasă|Reviziile alese}} pentru [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Revizia aleasă|Reviziile alese}}:'''",
'revdelete-text'              => "'''Reviziile şterse vor apărea în istoricul paginii, dar conţinutul lor nu va fi accesibil publicului.'''
Alţi administratori {{SITENAME}} vor putea accesa conţinutul ascuns şi îl pot recupera prin aceeaşi interfaţă, dacă nu este impusă altă restricţie de către operatorii sitului.",
'revdelete-suppress-text'     => "Suprimarea trebuie folosită '''doar''' în următoarele cazuri:
* Informaţii personale inadecvate
*: ''adrese şi numere de telefon personale, CNP, numere de securitate socială, etc.''",
'revdelete-legend'            => 'Setează restricţii pentru vizualizare',
'revdelete-hide-text'         => 'Ascunde textul reviziei',
'revdelete-hide-name'         => 'Ascunde acţiunea şi destinaţia',
'revdelete-hide-comment'      => 'Ascunde descrierea modificării',
'revdelete-hide-user'         => 'Ascunde numele de utilizator/IP-ul editorului',
'revdelete-hide-restricted'   => 'Ascunde informaţiile faţă de administratori şi faţă de alţi utilizatori',
'revdelete-suppress'          => 'Ascunde de asemenea reviziile faţă de administratori',
'revdelete-hide-image'        => 'Ascunde conţinutul fişierului',
'revdelete-unsuppress'        => 'Elimină restricţiile în reviziile restaurate',
'revdelete-log'               => 'Motiv pentru ştergere:',
'revdelete-submit'            => 'Aplică {{PLURAL:$1|reviziei selectate|reviziilor selectate}}',
'revdelete-logentry'          => 'vizibilitatea reviziei pentru [[$1]] a fost modificată',
'logdelete-logentry'          => 'a fost modificată vizibilitatea evenimentului [[$1]]',
'revdelete-success'           => "'''Vizibilitatea reviziilor a fost schimbată cu succes.'''",
'revdelete-failure'           => "'''Vizibilitatea reviziei nu poate fi definită:'''
$1",
'logdelete-success'           => "'''Jurnalul vizibilităţii a fost configurat cu succes.'''",
'logdelete-failure'           => "'''Vizibilitatea jurnalului nu poate fi definită:'''
$1",
'revdel-restore'              => 'Schimbă vizibilitatea',
'pagehist'                    => 'Istoricul paginii',
'deletedhist'                 => 'Istoric şters',
'revdelete-content'           => 'conţinut',
'revdelete-summary'           => 'sumarul modificărilor',
'revdelete-uname'             => 'nume de utilizator',
'revdelete-restricted'        => 'restricţii aplicate administratorilor',
'revdelete-unrestricted'      => 'restricţii eliminate pentru administratori',
'revdelete-hid'               => 'ascuns $1',
'revdelete-unhid'             => 'arată $1',
'revdelete-log-message'       => '$1 pentru $2 {{PLURAL:$2|versiune|versiuni}}',
'logdelete-log-message'       => '$1 pentru $2 {{PLURAL:$2|eveniment|evenimente}}',
'revdelete-hide-current'      => 'Eroare la ascunderea elementului datat $2, $1: reprezintă revizia curentă.
Nu poate fi ascuns.',
'revdelete-show-no-access'    => 'Eroare la afişarea elementului datat $2, $1: elementul a fost marcat ca "restricţionat".
Nu ai acces la acest element.',
'revdelete-modify-no-access'  => 'Eroare la modificarea elementului datat $2, $1: acest element a fost marcat "restricţionat".
Nu ai acces asupra lui.',
'revdelete-modify-missing'    => 'Eroare la modificarea elementului ID $1: lipseşte din baza de date!',
'revdelete-no-change'         => "'''Atenţie:''' elementul datat $2, $1 are deja aplicată vizibilitatea cerută.",
'revdelete-concurrent-change' => 'Eroare la modificarea elementului datat $2, $1: statutul său a fost modificat de altcineva în timpul acestei modificări.',
'revdelete-only-restricted'   => 'Nu poţi suprima aceste elemente la vizualizarea de către administratori fără a marca una din celelalte opţiuni de suprimare.',
'revdelete-reason-dropdown'   => '*Motive de ştergere
** Violare drepturi de autor
** Informaţii personale inadecvate',
'revdelete-otherreason'       => 'Motiv diferit/adiţional',
'revdelete-reasonotherlist'   => 'Alt motiv',
'revdelete-edit-reasonlist'   => 'Modifică motivele ştergerii',
'revdelete-offender'          => 'Autorul reviziei:',

# Suppression log
'suppressionlog'     => 'Înlătură jurnalul',
'suppressionlogtext' => 'Mai jos este afişată o listă a ştergerilor şi a blocărilor care implică conţinutul ascuns de administratori.
Vezi [[Special:IPBlockList|adresele IP blocate]] pentru o listă a interzicerilor operaţionale sau a blocărilor.',

# History merging
'mergehistory'                     => 'Uneşte istoricul paginilor',
'mergehistory-header'              => 'Această pagină permite să combini reviziile din istoric dintr-o pagină sursă într-o pagină nouă.
Asigură-te că această schimbare va menţine continuitatea istoricului paginii.',
'mergehistory-box'                 => 'Combină reviziile a două pagini:',
'mergehistory-from'                => 'Pagina sursă:',
'mergehistory-into'                => 'Pagina destinaţie:',
'mergehistory-list'                => 'Istoricul la care se aplică combinarea',
'mergehistory-merge'               => 'Ulmătoarele revizii ale [[:$1]] pot fi combinate în [[:$2]].
Foloseşte butonul pentru a combina reviziile create la şi după momentul specificat.
Folosirea linkurilor de navigare va reseta această coloană.',
'mergehistory-go'                  => 'Vezi modificările care pot fi combinate',
'mergehistory-submit'              => 'Uneşte reviziile',
'mergehistory-empty'               => 'Reviziile nu pot fi combinate.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revizie|revizii}} ale [[:$1]] au fost unite cu succes în [[:$2]].',
'mergehistory-fail'                => 'Nu se poate executa combinarea istoricului, te rog verifică parametrii pagină şi timp.',
'mergehistory-no-source'           => 'Pagina sursă $1 nu există.',
'mergehistory-no-destination'      => 'Pagina de destinaţie $1 nu există.',
'mergehistory-invalid-source'      => 'Pagina sursă trebuie să aibă un titlu valid.',
'mergehistory-invalid-destination' => 'Pagina de destinaţie trebuie să aibă un titlu valid.',
'mergehistory-autocomment'         => 'Combinat [[:$1]] în [[:$2]]',
'mergehistory-comment'             => 'Combinat [[:$1]] în [[:$2]]: $3',
'mergehistory-same-destination'    => 'Paginile sursă şi destinaţie nu pot fi identice',
'mergehistory-reason'              => 'Motiv:',

# Merge log
'mergelog'           => 'Jurnal unificări',
'pagemerge-logentry' => 'combină [[$1]] cu [[$2]] (revizii până la $3)',
'revertmerge'        => 'Anulează îmbinarea',
'mergelogpagetext'   => 'Mai jos este o listă a celor mai recente combinări ale istoricului unei pagini cu al alteia.',

# Diffs
'history-title'            => 'Istoria reviziilor pentru "$1"',
'difference'               => '(Diferenţa dintre versiuni)',
'lineno'                   => 'Linia $1:',
'compareselectedversions'  => 'Compară versiunile marcate',
'showhideselectedversions' => 'Arată/ascunde reviziile marcate',
'editundo'                 => 'anulează',
'diff-multi'               => '({{PLURAL:$1|O revizie intermediară neafişată|$1 revizii intermediare neafişate}})',

# Search results
'searchresults'                    => 'Rezultatele căutării',
'searchresults-title'              => 'Caută rezultate pentru „$1”',
'searchresulttext'                 => 'Pentru mai multe detalii despre căutarea în {{SITENAME}}, vezi [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ai căutat \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|toate paginile care încep cu "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toate paginile care se leagă de "$1"]])',
'searchsubtitleinvalid'            => 'Pentru căutarea "$1"',
'toomanymatches'                   => 'Prea multe rezultate au fost întoarse, încercă o căutare diferită',
'titlematches'                     => 'Rezultate în titlurile articolelor',
'notitlematches'                   => 'Nici un rezultat în titlurile articolelor',
'textmatches'                      => 'Rezultate în textele articolelor',
'notextmatches'                    => 'Nici un rezultat în textele articolelor',
'prevn'                            => 'anterioarele {{PLURAL:$1|$1}}',
'nextn'                            => 'următoarele {{PLURAL:$1|$1}}',
'prevn-title'                      => '{{PLURAL:$1|anteriorul|anterioarele}} $1 {{PLURAL:$1|rezultat|rezultate}}',
'nextn-title'                      => '{{PLURAL:$1|următorul|următoarele}} $1 {{PLURAL:$1|rezultat|rezultate}}',
'shown-title'                      => 'Arată $1 {{PLURAL:$1|rezultat|rezultate}} pe pagină',
'viewprevnext'                     => 'Vezi ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opţiuni căutare',
'searchmenu-exists'                => "* Pagina '''[[$1]]'''",
'searchmenu-new'                   => "'''Creează pagina \"[[:\$1]]\" pe acest wiki!'''",
'searchhelp-url'                   => 'Help:Ajutor',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Răsfoieşte paginile cu acest prefix]]',
'searchprofile-articles'           => 'Pagini cu conţinut',
'searchprofile-project'            => 'Pagini Proiect şi de Ajutor',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Totul',
'searchprofile-advanced'           => 'Avansat',
'searchprofile-articles-tooltip'   => 'Caută în $1',
'searchprofile-project-tooltip'    => 'Caută în $1',
'searchprofile-images-tooltip'     => 'Caută fişiere',
'searchprofile-everything-tooltip' => 'Caută în tot conţinutul (incluzând paginile de discuţie)',
'searchprofile-advanced-tooltip'   => 'Caută în namespace-uri personalizate',
'search-result-size'               => '$1 ({{PLURAL:$2|1 cuvânt|$2 cuvinte}})',
'search-result-score'              => 'Relevanţă: $1%',
'search-redirect'                  => '(redirecţionare către $1)',
'search-section'                   => '(secţiunea $1)',
'search-suggest'                   => 'Te-ai referit la: $1',
'search-interwiki-caption'         => 'Proiecte înrudite',
'search-interwiki-default'         => '$1 rezultate:',
'search-interwiki-more'            => '(mai mult)',
'search-mwsuggest-enabled'         => 'cu sugestii',
'search-mwsuggest-disabled'        => 'fără sugestii',
'search-relatedarticle'            => 'Relaţionat',
'mwsuggest-disable'                => 'Dezactivează sugestiile AJAX',
'searcheverything-enable'          => 'Caută în toate spaţiile de nume',
'searchrelated'                    => 'relaţionat',
'searchall'                        => 'toate',
'showingresults'                   => "Mai jos {{PLURAL:$1|apare '''1''' rezultat|apar '''$1''' rezultate}} începând cu #<b>$2</b>.",
'showingresultsnum'                => "Mai jos {{PLURAL:$3|apare '''1''' rezultat|apar '''$3''' rezultate}} cu #<b>$2</b>.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultat '''$1'''|Resultate '''$1 - $2'''}} ale '''$3''' pentru '''$4'''",
'nonefound'                        => "'''Notă''': Numai unele spaţii de nume sunt căutate implicit.
Încercaţi să puneţi ca şi prefix al căutării ''all:'' pentru a căuta în tot conţinutul (incluzând şi paginile de discuţii, formate, etc), sau folosiţi spaţiul de nume dorit ca şi prefix.",
'search-nonefound'                 => 'Nu sunt rezultate conforme interogării.',
'powersearch'                      => 'Căutare avansată',
'powersearch-legend'               => 'Căutare avansată',
'powersearch-ns'                   => 'Căutare în spaţiile de nume:',
'powersearch-redir'                => 'Afişează redirectările',
'powersearch-field'                => 'Caută',
'powersearch-togglelabel'          => 'Marchează:',
'powersearch-toggleall'            => 'Tot',
'powersearch-togglenone'           => 'Nimic',
'search-external'                  => 'Căutare externă',
'searchdisabled'                   => '<p>Ne pare rău! Căutarea după text a fost dezactivată temporar, din motive de performanţă. Între timp puteţi folosi căutarea prin Google mai jos, însă aceasta poate să dea rezultate învechite.</p>',

# Quickbar
'qbsettings'               => 'Setări pentru bara rapidă',
'qbsettings-none'          => 'Fără',
'qbsettings-fixedleft'     => 'Fixă, în stânga',
'qbsettings-fixedright'    => 'Fixă, în dreapta',
'qbsettings-floatingleft'  => 'Liberă',
'qbsettings-floatingright' => 'Plutire la dreapta',

# Preferences page
'preferences'                   => 'Preferinţe',
'mypreferences'                 => 'preferinţe',
'prefs-edits'                   => 'Număr de modificări:',
'prefsnologin'                  => 'Neautentificat',
'prefsnologintext'              => 'Trebuie să fiţi <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} autentificat]</span> pentru a vă putea salva preferinţele.',
'changepassword'                => 'Schimbă parola',
'prefs-skin'                    => 'Aspect',
'skin-preview'                  => 'Previzualizare',
'prefs-math'                    => 'Aspect formule',
'datedefault'                   => 'Nici o preferinţă',
'prefs-datetime'                => 'Data şi ora',
'prefs-personal'                => 'Date de utilizator',
'prefs-rc'                      => 'Schimbări recente',
'prefs-watchlist'               => 'Listă de urmărire',
'prefs-watchlist-days'          => 'Numărul de zile care apar în lista paginilor urmărite:',
'prefs-watchlist-days-max'      => '(maxim 7 zile)',
'prefs-watchlist-edits'         => 'Numărul de editări care apar în lista extinsă a paginilor urmărite:',
'prefs-watchlist-edits-max'     => '(număr maxim: 1000)',
'prefs-watchlist-token'         => 'Jeton pentru lista de urmărire:',
'prefs-misc'                    => 'Parametri diverşi',
'prefs-resetpass'               => 'Modifică parola',
'prefs-email'                   => 'Opţiuni e-mail',
'prefs-rendering'               => 'Aspect',
'saveprefs'                     => 'Salvează preferinţele',
'resetprefs'                    => 'Resetează preferinţele',
'restoreprefs'                  => 'Restaurează toate valorile implicite',
'prefs-editing'                 => 'Modificare',
'prefs-edit-boxsize'            => 'Mărimea ferestrei de modificare.',
'rows'                          => 'Rânduri:',
'columns'                       => 'Coloane',
'searchresultshead'             => 'Parametri căutare',
'resultsperpage'                => 'Numărul de rezultate per pagină',
'contextlines'                  => 'Numărul de linii per rezultat',
'contextchars'                  => 'Numărul de caractere per linie',
'stub-threshold'                => 'Valoarea minimă pentru un <a href="#" class="stub">ciot</a> (octeţi):',
'recentchangesdays'             => 'Numărul de zile afişate în schimbări recente:',
'recentchangesdays-max'         => '(maxim {{PLURAL:$1|o zi|$1 zile}})',
'recentchangescount'            => 'Numărul modificărilor arătate implicit:',
'prefs-help-recentchangescount' => 'Sunt incluse schimbările recente, istoricul paginilor şi jurnalele.',
'prefs-help-watchlist-token'    => 'Completând această căsuţă cu o cheie secretă va genera un flux RSS pentru lista dumneavoastră de urmărire.
Oricine vă ştie cheia din această căsuţă va fi capabil să citească lista dumneavoastră de urmărire, deci alegeţi o valoare sigură.
Aici este o valoare generată întâmplător pe care o puteţi folosi: $1',
'savedprefs'                    => 'Preferinţele dumneavoastră au fost salvate.',
'timezonelegend'                => 'Fus orar:',
'localtime'                     => 'Ora locală:',
'timezoneuseserverdefault'      => 'Foloseşte ora server-ului',
'timezoneuseoffset'             => 'Altul (specifică diferenţa)',
'timezoneoffset'                => 'Diferenţa¹:',
'servertime'                    => 'Ora serverului:',
'guesstimezone'                 => 'Încearcă determinarea automată a diferenţei',
'timezoneregion-africa'         => 'Africa',
'timezoneregion-america'        => 'America',
'timezoneregion-antarctica'     => 'Antarctica',
'timezoneregion-arctic'         => 'Oceanul Arctic',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Oceanul Atlantic',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Oceanul Indian',
'timezoneregion-pacific'        => 'Oceanul Pacific',
'allowemail'                    => 'Activează email de la alţi utilizatori',
'prefs-searchoptions'           => 'Opţiuni de căutare',
'prefs-namespaces'              => 'Spaţii de nume',
'defaultns'                     => 'Altfel, caută în aceste spaţii de nume:',
'default'                       => 'standard',
'prefs-files'                   => 'Fişiere',
'prefs-custom-css'              => 'CSS personalizat',
'prefs-custom-js'               => 'JS personalizat',
'prefs-reset-intro'             => 'Poţi folosi această pagină pentru a reseta preferinţele la valorile implicite.
Acţiunea nu este reversibilă.',
'prefs-emailconfirm-label'      => 'Confirmare e-mail:',
'prefs-textboxsize'             => 'Mărimea spaţiului de editare',
'youremail'                     => 'E-mail',
'username'                      => 'Nume de utilizator:',
'uid'                           => 'ID utilizator:',
'prefs-memberingroups'          => 'Membru în {{PLURAL:$1|grupul|grupurile}}:',
'prefs-registration'            => 'Data înregistrării:',
'yourrealname'                  => 'Nume real:',
'yourlanguage'                  => 'Limbă:',
'yourvariant'                   => 'Varianta:',
'yournick'                      => 'Semnătură:',
'prefs-help-signature'          => 'Comentariile de pe paginile de discuţii trebuie să fie semnate cu "<nowiki>~~~~</nowiki>" care va fi transformat în semnătura dumneavoastră urmată de ora la care aţi introdus comentariul.',
'badsig'                        => 'Semnătură brută incorectă; verificaţi tag-urile HTML.',
'badsiglength'                  => 'Semnătura este prea lungă.
Dimensiunea trebuie să fie mai mică de $1 {{PLURAL:$1|caracter|caractere}}.',
'yourgender'                    => 'Gen:',
'gender-unknown'                => 'Nespecificat',
'gender-male'                   => 'Bărbat',
'gender-female'                 => 'Femeie',
'prefs-help-gender'             => 'Opţional - sexul utilizatorului: folosit pentru adresarea corectă de către software. Această informaţie va fi publică.',
'email'                         => 'E-mail',
'prefs-help-realname'           => '* Numele dumneavoastră real (opţional): Dacă decideţi introducerea numelui real aici, acesta va fi folosit pentru a vă atribui munca.<br />',
'prefs-help-email'              => '*Adresa de e-mail (opţional): Permite altor utilizatori să vă contacteze prin e-mail via {{SITENAME}}, fără a vă divulga identitatea. De asemenea, permite recuperarea parolei în cazul în care o uitaţi.',
'prefs-help-email-required'     => 'Adresa de e-mail este necesară.',
'prefs-info'                    => 'Informaţii de bază',
'prefs-i18n'                    => 'Internaţionalizare',
'prefs-signature'               => 'Semnătură',
'prefs-dateformat'              => 'Format dată',
'prefs-timeoffset'              => 'Decalaj orar:',
'prefs-advancedediting'         => 'Opţiuni avansate',
'prefs-advancedrc'              => 'Opţiuni avansate',
'prefs-advancedrendering'       => 'Opţiuni avansate',
'prefs-advancedsearchoptions'   => 'Opţiuni avansate',
'prefs-advancedwatchlist'       => 'Opţiuni avansate',
'prefs-display'                 => 'Opţiuni afişare',
'prefs-diffs'                   => 'Diferenţe',

# User rights
'userrights'                  => 'Administrarea permisiunilor de utilizator',
'userrights-lookup-user'      => 'Administrare grupuri de utilizatori',
'userrights-user-editname'    => 'Introdu un nume de utilizator:',
'editusergroup'               => 'Modificare grup de utilizatori',
'editinguser'                 => "modificare permisiuni de utilizator ale utilizatorului '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Modifică grupul de utilizatori',
'saveusergroups'              => 'Salvează grupul de utilizatori',
'userrights-groupsmember'     => 'Membru al:',
'userrights-groups-help'      => 'Puteţi schimba grupul căruia îi aparţine utilizatorul:
*Căsuţa bifată înseamnă că utilizatorul este în acel grup.
*Căsuţa nebifată înseamnă că utilizatorul nu este în acel grup.
*Steluţa (*) indică faptul că utilizatorul nu poate fi eliminat din grup odată adăugat, sau invers',
'userrights-reason'           => 'Motivul schimbării:',
'userrights-no-interwiki'     => 'Nu aveţi permisiunea de a modifica permisiunile utilizatorilor pe alte wiki.',
'userrights-nodatabase'       => 'Baza de date $1 nu există sau nu este locală.',
'userrights-nologin'          => 'Trebuie să te [[Special:UserLogin|autentifici]] cu un cont de administrator pentru a atribui permisiuni utilizatorilor.',
'userrights-notallowed'       => 'Contul dumneavoastră nu are permisiunea de a acorda permisiuni utilizatorilor.',
'userrights-changeable-col'   => 'Grupuri pe care le puteţi schimba',
'userrights-unchangeable-col' => 'Grupuri pe care nu le puteţi schimba',

# Groups
'group'               => 'Grup:',
'group-user'          => 'Utilizatori',
'group-autoconfirmed' => 'Utilizatori autoconfirmaţi',
'group-bot'           => 'Roboţi',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birocraţi',
'group-suppress'      => 'Oversights',
'group-all'           => '(toţi)',

'group-user-member'          => 'Utilizator',
'group-autoconfirmed-member' => 'Utilizator autoconfirmat',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birocrat',
'group-suppress-member'      => 'oversight',

'grouppage-user'          => '{{ns:project}}:Utilizatori',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilizator autoconfirmaţi',
'grouppage-bot'           => '{{ns:project}}:Boţi',
'grouppage-sysop'         => '{{ns:project}}:Administratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Birocraţi',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Citeşte paginile',
'right-edit'                  => 'Modifică paginile',
'right-createpage'            => 'Creează pagini (altele decât pagini de discuţie)',
'right-createtalk'            => 'Creează pagini de discuţie',
'right-createaccount'         => 'Creează conturi noi',
'right-minoredit'             => 'Marchează modificările minore',
'right-move'                  => 'Mută paginile',
'right-move-subpages'         => 'Mută paginile cu tot cu subpagini',
'right-move-rootuserpages'    => 'Redenumeşte pagina principală a unui utilizator',
'right-movefile'              => 'Mută fişierele',
'right-suppressredirect'      => 'Nu crea o redirecţionare de la vechiul nume atunci când muţi o pagină',
'right-upload'                => 'Încarcă fişiere',
'right-reupload'              => 'Suprascrie un fişier existent',
'right-reupload-own'          => 'Suprascrie un fişier existent propriu',
'right-reupload-shared'       => 'Rescrie fişierele disponibile în depozitul partajat',
'right-upload_by_url'         => 'Încarcă un fişier de la o adresă URL',
'right-purge'                 => 'Curăţă memoria cache pentru o pagină fără confirmare',
'right-autoconfirmed'         => 'Modifică paginile semi-protejate',
'right-bot'                   => 'Tratare ca proces automat',
'right-nominornewtalk'        => 'Nu activa mesajul "Aveţi un mesaj nou" la modificarea minoră a paginii de discuţii a utilizatorului',
'right-apihighlimits'         => 'Foloseşte o limită mai mare pentru rezultatele cererilor API',
'right-writeapi'              => 'Utilizează API la scriere',
'right-delete'                => 'Şterge pagini',
'right-bigdelete'             => 'Şterge pagini cu istoric lung',
'right-deleterevision'        => 'Şterge şi recuperează versiuni specifice ale paginilor',
'right-deletedhistory'        => 'Vezi intrările şterse din istoric, fără textul asociat',
'right-deletedtext'           => 'Vizializaţi textul şters şi modificările dintre versiunile şterse',
'right-browsearchive'         => 'Caută pagini şterse',
'right-undelete'              => 'Recuperează pagini',
'right-suppressrevision'      => 'Examinează şi restaurează reviziile ascunse faţă de administratori',
'right-suppressionlog'        => 'Vizualizează jurnale private',
'right-block'                 => 'Blocare utilizatori la modificare',
'right-blockemail'            => 'Blocare utilizatori la trimitere email',
'right-hideuser'              => 'Blochează un nume de utilizator, ascunzându-l de public',
'right-ipblock-exempt'        => 'Nu au fost afectaţi de blocarea făcută IP-ului.',
'right-proxyunbannable'       => 'Treci peste blocarea automată a proxy-urilor',
'right-protect'               => 'Schimbă nivelurile de protejare şi modifică pagini protejate',
'right-editprotected'         => 'Modificare pagini protejate (fără protejare în cascadă)',
'right-editinterface'         => 'Modificare interfaţa cu utilizatorul',
'right-editusercssjs'         => 'Modifică fişierele CSS şi JS ale altor utilizatori',
'right-editusercss'           => 'Modifică fişierele CSS ale altor utilizatori',
'right-edituserjs'            => 'Modifică fişierele JS ale altor utilizatori',
'right-rollback'              => 'Revocarea rapidă a editărilor ultimului utilizator care a modificat o pagină particulară',
'right-markbotedits'          => 'Marchează revenirea ca modificare efectuată de robot',
'right-noratelimit'           => 'Neafectat de limitele raportului',
'right-import'                => 'Importă pagini de la alte wiki',
'right-importupload'          => 'Importă pagini dintr-o încărcare de fişier',
'right-patrol'                => 'Marchează modificările altora ca patrulate',
'right-autopatrol'            => 'Modificările proprii marcate ca patrulate',
'right-patrolmarks'           => 'Vizualizează pagini recent patrulate',
'right-unwatchedpages'        => 'Vizualizezaă listă de pagini neurmărite',
'right-trackback'             => 'Trimite un urmăritor',
'right-mergehistory'          => 'Uneşte istoricele paginilor',
'right-userrights'            => 'Modifică toate permisiunile de utilizator',
'right-userrights-interwiki'  => 'Modifică permisiunile de utilizator pentru utilizatorii de pe alte wiki',
'right-siteadmin'             => 'Blochează şi deblochează baza de date',
'right-reset-passwords'       => 'Resetarea parolelor altor utilizatori',
'right-override-export-depth' => 'Exportă inclusiv paginile legate până la o adâncime de 5',
'right-versiondetail'         => 'Arată informaţii extise despre versiunea programului',

# User rights log
'rightslog'      => 'Jurnal permisiuni de utilizator',
'rightslogtext'  => 'Acest jurnal cuprinde modificările permisiunilor utilizatorilor.',
'rightslogentry' => 'a schimbat pentru $1 apartenenţa la un grup de la $2 la $3',
'rightsnone'     => '(niciunul)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'citiţi această pagină',
'action-edit'                 => 'modificaţi această pagină',
'action-createpage'           => 'creaţi pagini',
'action-createtalk'           => 'creaţi pagini de discuţie',
'action-createaccount'        => 'creaţi acest cont de utilizator',
'action-minoredit'            => 'marcaţi această modificare ca minoră',
'action-move'                 => 'mutaţi această pagină',
'action-move-subpages'        => 'mutaţi această pagină şi subpaginile sale',
'action-move-rootuserpages'   => 'redenumiţi pagina principală a unui utilizator',
'action-movefile'             => 'mutaţi acest fişier',
'action-upload'               => 'încărcaţi acest fişier',
'action-reupload'             => 'suprascrieţi fişierul existent',
'action-reupload-shared'      => 'rescrieţi acest fişier în depozitul partajat',
'action-upload_by_url'        => 'încărcaţi acest fişier de la o adresă URL',
'action-writeapi'             => 'utilizaţi scrierea prin API',
'action-delete'               => 'ştergeţi această pagină',
'action-deleterevision'       => 'ştergeţi această revizie',
'action-deletedhistory'       => 'vizualizaţi istoricul şters al aceste pagini',
'action-browsearchive'        => 'căutaţi pagini şterse',
'action-undelete'             => 'recuperaţi această pagină',
'action-suppressrevision'     => 'revizuiţi şi să restauraţi această revizie ascunsă',
'action-suppressionlog'       => 'vizualizaţi acest jurnal privat',
'action-block'                => 'blocaţi permisiunea de modificare a acestui utilizator',
'action-protect'              => 'modificaţi nivelurile de protecţie pentru această pagină',
'action-import'               => 'importaţi această pagină din alt wiki',
'action-importupload'         => 'importaţi această pagină prin încărcarea unui fişier',
'action-patrol'               => 'marcaţi modificările celorlalţi ca patrulate',
'action-autopatrol'           => 'marcaţi modificarea drept patrulată',
'action-unwatchedpages'       => 'vizualizaţi lista de pagini neurmărite',
'action-trackback'            => 'aplicaţi un trackback',
'action-mergehistory'         => 'uniţi istoricul acestei pagini',
'action-userrights'           => 'modificaţi toate permisiunile utilizatorilor',
'action-userrights-interwiki' => 'modificaţi permisiunile utilizatorilor de pe alte wiki',
'action-siteadmin'            => 'blocaţi sau deblocaţi baza de date',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modificare|modificări}}',
'recentchanges'                     => 'Schimbări recente',
'recentchanges-legend'              => 'Opţiuni schimbări recente',
'recentchangestext'                 => 'Schimbări recente ... (Log)',
'recentchanges-feed-description'    => 'Urmăreşte cele mai recente schimbări folosind acest flux.',
'recentchanges-label-legend'        => 'Legendă: $1.',
'recentchanges-legend-newpage'      => '$1 - pagină nouă',
'recentchanges-label-newpage'       => 'Această modificare a creat o pagină nouă',
'recentchanges-legend-minor'        => '$1 - modificare minoră',
'recentchanges-label-minor'         => 'Aceasta este o modificare minoră',
'recentchanges-legend-bot'          => '$1 - modificare efectuată de un robot',
'recentchanges-label-bot'           => 'Această modificare a fost efectuată de un robot',
'recentchanges-legend-unpatrolled'  => '$1 - modificare nepatrulată',
'recentchanges-label-unpatrolled'   => 'Această modificare nu a fost patrulată încă',
'rcnote'                            => "Mai jos se află {{PLURAL:$|ultima modificare|ultimele '''$1''' modificări}} din {{PLURAL:$2|ultima zi|ultimele '''$2''' zile}}, începând cu $5, $4.",
'rcnotefrom'                        => 'Dedesubt sunt modificările de la <b>$2</b> (maxim <b>$1</b> de modificări sunt afişate - schimbă numărul maxim de linii alegând altă valoare mai jos).',
'rclistfrom'                        => 'Arată modificările începând de la $1',
'rcshowhideminor'                   => '$1 modificările minore',
'rcshowhidebots'                    => '$1 roboţii',
'rcshowhideliu'                     => '$1 utilizatorii autentificaţi',
'rcshowhideanons'                   => '$1 utilizatorii anonimi',
'rcshowhidepatr'                    => '$1 modificările patrulate',
'rcshowhidemine'                    => '$1 editările mele',
'rclinks'                           => 'Arată ultimele $1 modificări din ultimele $2 zile.<br />
$3',
'diff'                              => 'dif',
'hist'                              => 'ist',
'hide'                              => 'Ascunde',
'show'                              => 'Arată',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilizator|utilizatori}} care urmăresc]',
'rc_categories'                     => 'Limitează la categoriile (separate prin "|")',
'rc_categories_any'                 => 'Oricare',
'newsectionsummary'                 => '/* $1 */ secţiune nouă',
'rc-enhanced-expand'                => 'Arată detalii (necesită JavaScript)',
'rc-enhanced-hide'                  => 'Ascunde detaliile',

# Recent changes linked
'recentchangeslinked'          => 'Modificări corelate',
'recentchangeslinked-feed'     => 'Modificări corelate',
'recentchangeslinked-toolbox'  => 'Modificări corelate',
'recentchangeslinked-title'    => 'Modificări legate de "$1"',
'recentchangeslinked-noresult' => 'Nici o schimbare la paginile legate în perioada dată.',
'recentchangeslinked-summary'  => "Aceasta este o listă a schimbărilor efectuate recent asupra paginilor cu legături de la o anumită pagină (sau asupra membrilor unei anumite categorii).
Paginile pe care le [[Special:Watchlist|urmăriţi]] apar în '''aldine'''.",
'recentchangeslinked-page'     => 'Numele paginii:',
'recentchangeslinked-to'       => 'Afişează schimbările în paginile care se leagă de pagina dată',

# Upload
'upload'                      => 'Încarcă fişier',
'uploadbtn'                   => 'Încarcă fişier',
'reuploaddesc'                => 'Revocare încărcare şi întoarcere la formularul de trimitere.',
'uploadnologin'               => 'Nu sunteţi autentificat',
'uploadnologintext'           => 'Trebuie să fiţi [[Special:UserLogin|autentificat]] pentru a putea trimite fişiere.',
'upload_directory_missing'    => 'Directorul în care sunt încărcate fişierele ($1) lipseşte şi nu poate fi creat de serverul web.',
'upload_directory_read_only'  => 'Directorul de încărcare ($1) nu poate fi scris de server.',
'uploaderror'                 => 'Eroare la trimitere fişier',
'uploadtext'                  => "Foloseşte formularul de mai jos pentru a trimite fişiere. 
Pentru a vizualiza sau căuta imagini deja trimise, mergi la [[Special:FileList|lista de imagini]], încărcările şi ştergerile sunt de asemenea înregistrate în [[Special:Log/upload|jurnalul fişierelor trimise]], ştergerile în [[Special:Log/delete|jurnalul fişierelor şterse]].

Pentru a include un fişier de sunet într-un articol, foloseşti o legătură de forma:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fişier.jpg]]</nowiki></tt>''' pentru a include versiunea integrală a unui fişier
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fişier.png|200px|thumb|left|alt text]]</nowiki></tt>''' pentru a introduce o imagine de 200px într-un chenar cu textul 'alt text' în partea stângă ca descriere
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fişier.ogg]]</nowiki></tt>''' pentru a lega direct fişierul fără a-l afişa",
'upload-permitted'            => 'Tipuri de fişiere permise: $1.',
'upload-preferred'            => 'Tipuri de fişiere preferate: $1.',
'upload-prohibited'           => 'Tipuri de fişiere interzise: $1.',
'uploadlog'                   => 'jurnal fişiere trimise',
'uploadlogpage'               => 'Jurnal fişiere trimise',
'uploadlogpagetext'           => 'Mai jos este afişată lista ultimelor fişiere trimise.
Vezi [[Special:NewFiles|galeria fişierelor noi]] pentru o mai bună vizualizare.',
'filename'                    => 'Nume fişier',
'filedesc'                    => 'Descriere fişier',
'fileuploadsummary'           => 'Rezumat:',
'filereuploadsummary'         => 'Modificări ale fişierului:',
'filestatus'                  => 'Statutul drepturilor de autor:',
'filesource'                  => 'Sursă:',
'uploadedfiles'               => 'Fişiere trimise',
'ignorewarning'               => 'Ignoră avertismentul şi salvează fişierul',
'ignorewarnings'              => 'Ignoră orice avertismente',
'minlength1'                  => 'Numele fişierelor trebuie să fie cel puţin o literă.',
'illegalfilename'             => 'Numele fişierului "$1" conţine caractere care nu sunt permise în titlurile paginilor. Vă rugăm redenumiţi fişierul şi încercaţi să îl încărcaţi din nou.',
'badfilename'                 => 'Numele fişierului a fost schimbat în „$1”.',
'filetype-badmime'            => 'Nu este permisă încărcarea de fişiere de tipul MIME "$1".',
'filetype-bad-ie-mime'        => 'Nu puteţi încărca acest fişier deoarece Internet Explorer îl va detecta ca şi "$1", care este nepermis şi poate fi un format periculos.',
'filetype-unwanted-type'      => "'''\".\$1\"''' este un tip de fişier nedorit.
{{PLURAL:\$3|Tipul de fişier preferat este|Tipurile de fişiere preferate sunt}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' este un tip de fişier nepermis. 
{{PLURAL:\$3|Tip de fişier permis:|Tipuri de fişiere permise:}} \$2.",
'filetype-missing'            => 'Fişierul nu are extensie (precum ".jpg").',
'large-file'                  => 'Este recomandat ca fişierele să nu fie mai mari de $1; acest fişier are $2.',
'largefileserver'             => 'Fişierul este mai mare decât este configurat serverul să permită.',
'emptyfile'                   => 'Fişierul pe care l-aţi încărcat pare a fi gol. Aceasta poate fi datorită unei greşeli în numele fişierului. Verificaţi dacă într-adevăr doriţi să încărcaţi acest fişier.',
'fileexists'                  => "Un fişier cu acelaşi nume există deja, vă rugăm verificaţi '''<tt>[[:$1]]</tt>''' dacă nu sunteţi sigur dacă doriţi să îl modificaţi.
[[$1|thumb]]",
'filepageexists'              => "Pagina cu descrierea fişierului a fost deja creată la '''<tt>[[:$1]]</tt>''', dar niciun fişier cu acest nume nu există în acest moment.
Sumarul pe care l-ai introdus nu va apărea în pagina cu descriere.
Pentru ca sumarul tău să apară, va trebui să îl adaugi manual.
[[$1|miniatură]]",
'fileexists-extension'        => "Un fişier cu un nume similar există: [[$2|thumb]]
* Numele fişierului de încărcat: '''<tt>[[:$1]]</tt>'''
* Numele fişierului existent: '''<tt>[[:$2]]</tt>'''
Te rog alege alt nume.",
'fileexists-thumbnail-yes'    => "Fişierul pare a fi o imagine cu o rezoluţie scăzută ''(thumbnail)''. [[$1|thumb]]
Verifică fişierul'''<tt>[[:$1]]</tt>'''.
Dacă fişierul verificat este identic cu imaginea originală nu este necesară încărcarea altui thumbnail.",
'file-thumbnail-no'           => "Numele fişierului începe cu '''<tt>$1</tt>'''.
Se pare că este o imagine cu dimensiune redusă''(thumbnail)''.
Dacă ai această imagine la rezoluţie mare încarc-o pe aceasta, altfel schimbă numele fişierului.",
'fileexists-forbidden'        => 'Un fişier cu acest nume există deja şi nu poate fi rescris.
Mergeţi înapoi şi încărcaţi acest fişier sub un nume nou. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fişier cu acest nume există deja în magazia de imagini comune; mergeţi înapoi şi încărcaţi fişierul sub un nou nume. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Acest fişier este dublura {{PLURAL:$1|fişierului|fişierelor}}:',
'file-deleted-duplicate'      => 'Un fişier identic cu acesta ([[$1]]) a fost şters anterior. Verificaţi istoricul ştergerilor fişierului înainte de a-l reîncărca.',
'successfulupload'            => 'Fişierul a fost trimis',
'uploadwarning'               => 'Avertizare la trimiterea fişierului',
'savefile'                    => 'Salvează fişierul',
'uploadedimage'               => 'a trimis [[$1]]',
'overwroteimage'              => 'încărcat o versiune nouă a fişierului "[[$1]]"',
'uploaddisabled'              => 'Ne pare rău, trimiterea de imagini este dezactivată.',
'uploaddisabledtext'          => 'Încărcările de fişiere sunt dezactivate.',
'php-uploaddisabledtext'      => 'Încărcarea de fişiere este dezactivată în PHP.
Vă rugăm să verificaţi setările din file_uploads.',
'uploadscripted'              => 'Fişierul conţine HTML sau cod script care poate fi interpretat în mod eronat de un browser.',
'uploadcorrupt'               => 'Fişierul este corupt sau are o extensie incorectă. Verifică fişierul şi trimite-l din nou.',
'uploadvirus'                 => 'Fişierul conţine un virus! Detalii: $1',
'sourcefilename'              => 'Nume fişier sursă:',
'destfilename'                => 'Numele fişierului de destinaţie:',
'upload-maxfilesize'          => 'Mărimea maximă a unui fişier: $1',
'watchthisupload'             => 'Urmăreşte acest fişier',
'filewasdeleted'              => 'Un fişier cu acest nume a fost anterior încărcat şi apoi şters. Ar trebui să verificaţi $1 înainte să îl încărcaţi din nou.',
'upload-wasdeleted'           => "'''Atenţie: Sunteţi pe cale să încarcaţi un fişier şters anterior.'''

Vă rugăm să aveţi în vedere dacă este utilă reîncărcarea acestuia.
Jurnalul pentru această ştergere este disponibil aici:",
'filename-bad-prefix'         => "Numele fişierului pe care îl încărcaţi începe cu '''\"\$1\"''', care este un nume non-descriptiv alocat automat în general de camerele digitale.
Vă rugăm, alegeţi un nume mai descriptiv pentru fişerul dumneavoastră.",

'upload-proto-error'        => 'Protocol incorect',
'upload-proto-error-text'   => 'Importul de la distanţă necesită adrese URL care încep cu <code>http://</code> sau <code>ftp://</code>.',
'upload-file-error'         => 'Eroare internă',
'upload-file-error-text'    => 'A apărut o eroare internă la crearea unui fişier temporar pe server.
Vă rugăm să contactaţi un [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error'         => 'Eroare de încărcare necunoscută',
'upload-misc-error-text'    => 'A apărut o eroare necunoscută în timpul încărcării.
Vă rugăm să verificaţi dacă adresa URL este validă şi accesibilă şi încercaţi din nou.
Dacă problema persistă, contactaţi un [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'URL-ul conţinea prea multe redirecţionări',
'upload-unknown-size'       => 'Mărime necunoscută',
'upload-http-error'         => 'A avut loc o eroare HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acces interzis',
'img-auth-nopathinfo'   => 'PATH_INFO lipseşte.
Serverul dumneavoastră nu a fost setat pentru a trece aceste informaţii.
S-ar putea să fie bazat pe CGI şi să nu suporte img_auth.
Vedeţi http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-nofile'       => 'Fişierul "$1" nu există.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nu pot găsi adresa URL',
'upload-curl-error6-text'  => 'Adresa URL introdusă nu a putut fi atinsă.
Vă rugăm, verificaţi că adresa URL este corectă şi că situl este funcţional.',
'upload-curl-error28'      => 'Încărcarea a expirat',
'upload-curl-error28-text' => 'Sitelui îi ia prea mult timp pentru a răspunde.
Te rog verifică dacă situl este activ, aşteaptă puţin şi încearcă apoi.
Poate doreşti să încerci la o oră mai puţin ocupată.',

'license'            => 'Licenţiere:',
'license-header'     => 'Licenţiere',
'nolicense'          => 'Nici una selectată',
'license-nopreview'  => '(Previzualizare indisponibilă)',
'upload_source_url'  => ' (un URL valid, accesibil public)',
'upload_source_file' => ' (un fişier de pe computerul tău)',

# Special:ListFiles
'listfiles-summary'     => 'Această pagină specială arată toate fişierele încărcate.
În mod normal ultimul fişier încărcat este aşezat în capul listei.
O apăsare pe antetul coloanei schimbă sortarea.',
'listfiles_search_for'  => 'Caută imagine după nume:',
'imgfile'               => 'fişier',
'listfiles'             => 'Lista imaginilor',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nume',
'listfiles_user'        => 'Utilizator',
'listfiles_size'        => 'Mărime (octeţi)',
'listfiles_description' => 'Descriere',
'listfiles_count'       => 'Versiuni',

# File description page
'file-anchor-link'          => 'Fişier',
'filehist'                  => 'Istoricul fişierului',
'filehist-help'             => 'Apasă pe dată/timp pentru a vedea fişierul aşa cum era la data respectivă.',
'filehist-deleteall'        => 'şterge tot',
'filehist-deleteone'        => 'şterge',
'filehist-revert'           => 'revenire',
'filehist-current'          => 'curentă',
'filehist-datetime'         => 'Dată/Timp',
'filehist-thumb'            => 'Miniatură',
'filehist-thumbtext'        => 'Miniatură pentru versiunea din $1',
'filehist-nothumb'          => 'Nicio miniatură',
'filehist-user'             => 'Utilizator',
'filehist-dimensions'       => 'Dimensiuni',
'filehist-filesize'         => 'Mărimea fişierului',
'filehist-comment'          => 'Comentariu',
'filehist-missing'          => 'Fişier lipsă',
'imagelinks'                => 'Legături',
'linkstoimage'              => '{{PLURAL:$1|Următoarea pagină trimite spre|Următoarele $1 pagini trimit spre}} această imagine:',
'linkstoimage-more'         => 'Mai mult de $1 {{PLURAL:$1|pagină este legată|pagini sunt legate}} de acest fişier.
Următoarea listă arată {{PLURAL:$1|prima legătură|primele $1 legături}} către acest fişier.
O [[Special:WhatLinksHere/$2|listă completă]] este disponibilă.',
'nolinkstoimage'            => 'Nici o pagină nu se leagă la această imagine.',
'morelinkstoimage'          => 'Vedeţi [[Special:WhatLinksHere/$1|mai multe legături]] către acest fişier.',
'redirectstofile'           => 'The following {{PLURAL:$1|file redirects|$1 files redirect}} to this file:
{{PLURAL:$1|Fişierul următor|Următoarele $1 fişiere}} redirectează la acest fişier:',
'duplicatesoffile'          => '{{PLURAL:$1|Fişierul următor este duplicat|Următoarele $1 fişiere sunt duplicate}} ale acestui fişier ([[Special:FileDuplicateSearch/$2|mai multe detalii]]):',
'sharedupload'              => 'Acest fişier provine de la $1 şi poate fi folosit şi de alte proiecte.',
'sharedupload-desc-there'   => 'Fişierul acesta este de la $1 şi poate fi folosit de alte proiecte.
Vezi [$2 pagina de descriere a fişierului] pentru mai multe detalii.',
'sharedupload-desc-here'    => 'Fişierul acesta este de la $1 şi poate fi folosit de alte proiecte.
Descrierea de mai jos poate fi consultată la [$2 pagina de descriere a fişierului].',
'filepage-nofile'           => 'Nu există niciun fişier cu acest nume.',
'filepage-nofile-link'      => 'Nu există niciun fişier cu acest nume, dar îl poţi [$1 încărca].',
'uploadnewversion-linktext' => 'Încarcă o versiune nouă a acestui fişier',
'shared-repo-from'          => 'de la $1',
'shared-repo'               => 'un depozit partajat',

# File reversion
'filerevert'                => 'Revenire $1',
'filerevert-legend'         => 'Revenirea la o versiune anterioară',
'filerevert-intro'          => "Pentru a readuce fişierul '''[[Media:$1|$1]]''' la versiunea din [$4 $2 $3] apasă butonul de mai jos.",
'filerevert-comment'        => 'Comentariu:',
'filerevert-defaultcomment' => 'Revenire la versiunea din $2, $1',
'filerevert-submit'         => 'Revenire',
'filerevert-success'        => "'''[[Media:$1|$1]]''' a fost readus [la versiunea $4 din $3, $2].",
'filerevert-badversion'     => 'Nu există o versiune mai veche a fişierului care să corespundă cu data introdusă.',

# File deletion
'filedelete'                  => 'Şterge $1',
'filedelete-legend'           => 'Şterge fişierul',
'filedelete-intro'            => "Sunteţi pe cale să ştergeţi fişierul '''[[Media:$1|$1]]''' cu tot istoricul acestuia.",
'filedelete-intro-old'        => "Ştergi versiunea fişierului '''[[Media:$1|$1]]''' din [$4 $3, $2].",
'filedelete-comment'          => 'Motiv pentru ştergere:',
'filedelete-submit'           => 'Şterge',
'filedelete-success'          => "'''$1''' a fost şters.",
'filedelete-success-old'      => "Versiunea fişierului '''[[Media:$1|$1]]''' din $2 $3 a fost ştearsă.",
'filedelete-nofile'           => "'''$1''' nu există.",
'filedelete-nofile-old'       => "Nu există nicio versiune arhivată a '''$1''' cu atributele specificate.",
'filedelete-otherreason'      => 'Motiv diferit/adiţional:',
'filedelete-reason-otherlist' => 'Alt motiv',
'filedelete-reason-dropdown'  => '*Motive uzuale
** Încălcare drepturi de autor
** Fişier duplicat',
'filedelete-edit-reasonlist'  => 'Modifică motivele ştergerii',

# MIME search
'mimesearch'         => 'Căutare MIME',
'mimesearch-summary' => 'This page enables the filtering of files for its MIME-type.
Input: contenttype/subtype, e.g. <tt>image/jpeg</tt>.


Această pagină specială permite căutarea fişierelor în funcţie de tipul MIME (Multipurpose Internet Mail Extensions). Cele mai des întâlnite sunt:
* Imagini : <code>image/jpeg</code> 
* Diagrame : <code>image/png</code>, <code>image/svg+xml</code> 
* Imagini animate : <code>image/gif</code> 
* Fişiere sunet : <code>audio/ogg</code>, <code>audio/x-ogg</code> 
* Fişiere video : <code>video/ogg</code>, <code>video/x-ogg</code> 
* Fişiere PDF : <code>application/pdf</code>

Lista tipurilor MIME recunoscute de MediaWiki poate fi găsită la [http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/mime.types?view=markup fişiere mime.types].',
'mimetype'           => 'Tip MIME:',
'download'           => 'descarcă',

# Unwatched pages
'unwatchedpages' => 'Pagini neurmărite',

# List redirects
'listredirects' => 'Lista de redirecţionări',

# Unused templates
'unusedtemplates'     => 'Formate neutilizate',
'unusedtemplatestext' => 'Lista de mai jos cuprinde toate formatele care nu sînt incluse în nici o altă pagină.
Înainte de a le şterge asiguraţi-vă că într-adevăr nu există legături dinspre alte pagini.',
'unusedtemplateswlh'  => 'alte legături',

# Random page
'randompage'         => 'Pagină aleatorie',
'randompage-nopages' => 'Nu există pagini în {{PLURAL:$2|spaţiul|spaţiile}} de nume: $1.',

# Random redirect
'randomredirect'         => 'Redirecţionare aleatorie',
'randomredirect-nopages' => 'Nu există redirecţionări în spaţiul de nume "$1".',

# Statistics
'statistics'                   => 'Statistici',
'statistics-header-pages'      => 'Statisticile paginii',
'statistics-header-edits'      => 'Editează statisticile',
'statistics-header-views'      => 'Vizualizează statisticile',
'statistics-header-users'      => 'Statistici legate de utilizatori',
'statistics-header-hooks'      => 'Alte statistici',
'statistics-articles'          => 'Articole',
'statistics-pages'             => 'Pagini',
'statistics-pages-desc'        => 'Toate paginile din wiki, inclusiv pagini de discuţie, redirectări etc.',
'statistics-files'             => 'Fişiere încărcate',
'statistics-edits'             => 'Editări de la instalarea {{SITENAME}}',
'statistics-edits-average'     => 'Media editărilor pe pagină',
'statistics-views-total'       => 'Număr de vizualizări',
'statistics-views-peredit'     => 'Vizualizări pe editare',
'statistics-jobqueue'          => 'Lungime [http://www.mediawiki.org/wiki/Manual:Job_queue listă de aşteptare]',
'statistics-users'             => '[[Special:ListUsers|Utilizatori]] înregistraţi',
'statistics-users-active'      => 'Utilizatori activi',
'statistics-users-active-desc' => 'Utilizatori care au efectuat o acţiune în {{PLURAL:$1|ultima zi|ultimele $1 zile}}',
'statistics-mostpopular'       => 'Paginile cele mai vizualizate',

'disambiguations'      => 'Pagini de dezambiguizare',
'disambiguationspage'  => 'Template:Dezambiguizare',
'disambiguations-text' => "Paginile următoare conţin legături către o '''pagină de dezambiguizare'''.
În locul acesteia ar trebui să conţină legături către un articol.<br />
O pagină este considerată o pagină de dezambiguizare dacă foloseşte formate care apar la [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redirecţionări duble',
'doubleredirectstext'        => 'Această listă conţine pagini care redirecţionează la alte pagini de redirecţionare.
Fiecare rând conţine legături la primele două redirecţionări, precum şi ţinta celei de-a doua redirecţionări, care este de obicei pagina ţintă "reală", către care ar trebui să redirecţioneze prima pagină.
Intrările <s>tăiate</s> au fost rezolvate.',
'double-redirect-fixed-move' => '[[$1]] a fost mutat, acum este un redirect către [[$2]]',
'double-redirect-fixer'      => 'Corector de redirecţionări',

'brokenredirects'        => 'Redirecţionări greşite',
'brokenredirectstext'    => 'Următoarele redirecţionări conduc spre articole inexistente:',
'brokenredirects-edit'   => 'modifică',
'brokenredirects-delete' => 'şterge',

'withoutinterwiki'         => 'Pagini fără legături interwiki',
'withoutinterwiki-summary' => 'Următoarele pagini nu se leagă la versiuni ale lor în alte limbi:',
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Arată',

'fewestrevisions' => 'Articole cu cele mai puţine revizii',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|un octet|$1 octeţi}}',
'ncategories'             => '{{PLURAL:$1|o categorie|$1 categorii}}',
'nlinks'                  => '{{PLURAL:$1|o legătură|$1 legături}}',
'nmembers'                => '{{PLURAL:$1|un membru|$1 membri}}',
'nrevisions'              => '{{PLURAL:$1|o revizie|$1 revizii}}',
'nviews'                  => '{{PLURAL:$1|o accesare|$1 accesări}}',
'specialpage-empty'       => 'Această pagină este goală.',
'lonelypages'             => 'Pagini orfane',
'lonelypagestext'         => 'La următoarele pagini nu se leagă nici o altă pagină din {{SITENAME}}.',
'uncategorizedpages'      => 'Pagini necategorizate',
'uncategorizedcategories' => 'Categorii necategorizate',
'uncategorizedimages'     => 'Fişiere necategorizate',
'uncategorizedtemplates'  => 'Formate necategorizate',
'unusedcategories'        => 'Categorii neutilizate',
'unusedimages'            => 'Pagini neutilizate',
'popularpages'            => 'Pagini populare',
'wantedcategories'        => 'Categorii dorite',
'wantedpages'             => 'Pagini dorite',
'wantedpages-badtitle'    => 'Titlu invalid în rezultatele : $1',
'wantedfiles'             => 'Fişiere dorite',
'wantedtemplates'         => 'Formate dorite',
'mostlinked'              => 'Cele mai căutate articole',
'mostlinkedcategories'    => 'Cele mai căutate categorii',
'mostlinkedtemplates'     => 'Cele mai folosite formate',
'mostcategories'          => 'Articole cu cele mai multe categorii',
'mostimages'              => 'Cele mai căutate imagini',
'mostrevisions'           => 'Articole cu cele mai multe revizuiri',
'prefixindex'             => 'Toate paginile cu prefix',
'shortpages'              => 'Pagini scurte',
'longpages'               => 'Pagini lungi',
'deadendpages'            => 'Pagini fără legături',
'deadendpagestext'        => 'Următoarele pagini nu se leagă de alte pagini din acestă wiki.',
'protectedpages'          => 'Pagini protejate',
'protectedpages-indef'    => 'Doar protecţiile pe termen nelimitat',
'protectedpages-cascade'  => 'Doar protejări în cascadă',
'protectedpagestext'      => 'Următoarele pagini sunt protejate la mutare sau editare',
'protectedpagesempty'     => 'Nu există pagini protejate',
'protectedtitles'         => 'Titluri protejate',
'protectedtitlestext'     => 'Următoarele titluri sunt protejate la creare',
'protectedtitlesempty'    => 'Nu există titluri protejate cu aceşti parametri.',
'listusers'               => 'Lista de utilizatori',
'listusers-editsonly'     => 'Arată doar utilizatorii cu modificări',
'listusers-creationsort'  => 'Sortează după data creării',
'usereditcount'           => '$1 {{PLURAL:$1|editare|editări}}',
'usercreated'             => 'Creat în $1 la $2',
'newpages'                => 'Pagini noi',
'newpages-username'       => 'Nume de utilizator:',
'ancientpages'            => 'Cele mai vechi articole',
'move'                    => 'Mută',
'movethispage'            => 'Mută această pagină',
'unusedimagestext'        => 'Te rugăm ţine cont de faptul că alte situri, inclusiv alte versiuni de limbă {{SITENAME}} pot să aibă legături aici fără ca aceste pagini să fie listate aici - această listă se referă strict la {{SITENAME}} în română.',
'unusedcategoriestext'    => 'Următoarele categorii de pagini există şi totuşi nici un articol sau categorie nu le foloseşte.',
'notargettitle'           => 'Lipsă ţintă',
'notargettext'            => 'Nu ai specificat nici o pagină sau un utilizator ţintă pentru care să se efectueze această operaţiune.',
'nopagetitle'             => 'Nu există pagina destinaţie',
'nopagetext'              => 'Pagina destinaţie specificată nu există.',
'pager-newer-n'           => '{{PLURAL:$1|1 mai nou|$1 mai noi}}',
'pager-older-n'           => '{{PLURAL:$1|1|$1}} mai vechi',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Surse de cărţi',
'booksources-search-legend' => 'Caută surse pentru cărţi',
'booksources-go'            => 'Du-te',
'booksources-text'          => 'Mai jos se află o listă de legături înspre alte situri care vând cărţi noi sau vechi, şi care pot oferi informaţii suplimentare despre cărţile pe care le căutaţi:',
'booksources-invalid-isbn'  => 'Codul ISBN oferit nu este valid; verificaţi dacă a fost copiat corect de la sursa originală.',

# Special:Log
'specialloguserlabel'  => 'Utilizator:',
'speciallogtitlelabel' => 'Titlu:',
'log'                  => 'Jurnale',
'all-logs-page'        => 'Toate jurnalele publice',
'alllogstext'          => 'Afişare combinată a tuturor jurnalelor {{SITENAME}}.
Puteţi limita vizualizarea selectând tipul jurnalului, numele de utilizator sau pagina afectată.',
'logempty'             => 'Nici o înregistrare în jurnal.',
'log-title-wildcard'   => 'Caută titluri care încep cu acest text',

# Special:AllPages
'allpages'          => 'Toate paginile',
'alphaindexline'    => '$1 către $2',
'nextpage'          => 'Pagina următoare ($1)',
'prevpage'          => 'Pagina anterioară ($1)',
'allpagesfrom'      => 'Afişează paginile pornind de la:',
'allpagesto'        => 'Afişează paginile terminând cu:',
'allarticles'       => 'Toate articolele',
'allinnamespace'    => 'Toate paginile (spaţiu de nume $1)',
'allnotinnamespace' => 'Toate paginile (în afara spaţiului de nume $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Următor',
'allpagessubmit'    => 'Trimite',
'allpagesprefix'    => 'Afişează paginile cu prefix:',
'allpagesbadtitle'  => 'Titlul paginii este nevalid sau conţine un prefix inter-wiki. Este posibil să conţină unul sau mai multe caractere care nu pot fi folosite în titluri.',
'allpages-bad-ns'   => '{{SITENAME}} nu are spaţiul de nume „$1”.',

# Special:Categories
'categories'                    => 'Categorii',
'categoriespagetext'            => '{{PLURAL:$1|Următoarea categorie conţine|Următoarele categorii conţin}} pagini sau fişiere.
[[Special:UnusedCategories|Categoriile neutilizate]] nu apar aici.
Vedeţi şi [[Special:WantedCategories|categoriile dorite]].',
'categoriesfrom'                => 'Arată categoriile pornind de la:',
'special-categories-sort-count' => 'ordonează după număr',
'special-categories-sort-abc'   => 'sortează alfabetic',

# Special:DeletedContributions
'deletedcontributions'             => 'Contribuţii şterse',
'deletedcontributions-title'       => 'Contribuţii şterse',
'sp-deletedcontributions-contribs' => 'contribuţii',

# Special:LinkSearch
'linksearch'       => 'Legături externe',
'linksearch-pat'   => 'Model de căutare:',
'linksearch-ns'    => 'Spaţiu de nume:',
'linksearch-ok'    => 'Caută',
'linksearch-text'  => 'Pot fi folosite metacaractere precum "*.wikipedia.org".<br />
Protocoale suportate: <tt>$1</tt>',
'linksearch-line'  => '$1 este legat de $2',
'linksearch-error' => 'Metacaracterele pot să apară doar la începutul hostname-ului.',

# Special:ListUsers
'listusersfrom'      => 'Afişează utilizatori începând cu:',
'listusers-submit'   => 'Arată',
'listusers-noresult' => 'Nici un utilizator găsit.',
'listusers-blocked'  => '(blocat{{GENDER:$1||ă|}})',

# Special:ActiveUsers
'activeusers'          => 'Lista de utilizatori activi',
'activeusers-count'    => '$1 {{PLURAL:$1|modificare recentă|modificări recente}} în {{PLURAL:$3|ultima zi|ultimele $3 zile}}',
'activeusers-from'     => 'Afişează utilizatori începând cu:',
'activeusers-noresult' => 'Niciun utilizator găsit.',

# Special:Log/newusers
'newuserlogpage'              => 'Jurnal utilizatori noi',
'newuserlogpagetext'          => 'Acesta este jurnalul creărilor conturilor de utilizator.',
'newuserlog-byemail'          => 'parola trimisă prin e-mail',
'newuserlog-create-entry'     => 'Utilizator nou',
'newuserlog-create2-entry'    => 'a fost creat contul nou $1',
'newuserlog-autocreate-entry' => 'Cont creat automat',

# Special:ListGroupRights
'listgrouprights'                      => 'Permisiunile grupurilor de utilizatori',
'listgrouprights-summary'              => 'Mai jos este afişată o listă a grupurilor de utilizatori definită în această wiki, împreună cu permisiunile de acces asociate.
Pot exista [[{{MediaWiki:Listgrouprights-helppage}}|informaţii adiţionale]] despre permisiunile individuale.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Drept acordat</span>
* <span class="listgrouprights-revoked">Drept revocat</span>',
'listgrouprights-group'                => 'Grup',
'listgrouprights-rights'               => 'Permisiuni',
'listgrouprights-helppage'             => 'Help:Group rights',
'listgrouprights-members'              => '(listă de membri)',
'listgrouprights-addgroup'             => 'Poţi adăuga {{PLURAL:$2|grupul|grupurile}}: $1',
'listgrouprights-removegroup'          => 'Poţi elimina {{PLURAL:$2|grupul|grupurile}}: $1',
'listgrouprights-addgroup-all'         => 'Pot fi adăugate toate grupurile',
'listgrouprights-removegroup-all'      => 'Pot fi eliminate toate grupurile',
'listgrouprights-addgroup-self'        => '{{PLURAL:$2|Poate fi adăugat grupul|Pot fi adăugate grupurile}} pentru contul propriu: $1',
'listgrouprights-removegroup-self'     => '{{PLURAL:$2|Poate fi şters grupul|Pot fi şterse grupurile}} pentru contul propriu: $1',
'listgrouprights-addgroup-self-all'    => 'Pot fi adăugate toate grupurile contului propriu',
'listgrouprights-removegroup-self-all' => 'Pot fi şterse toate grupurile din contul propriu',

# E-mail user
'mailnologin'      => 'Nu există adresă de trimitere',
'mailnologintext'  => 'Trebuie să fii [[Special:UserLogin|autentificat]] şi să ai o adresă validă de e-mail în [[Special:Preferences|preferinţe]] pentru a trimite e-mail altor utilizatori.',
'emailuser'        => 'Trimite e-mail',
'emailpage'        => 'E-mail către utilizator',
'emailpagetext'    => 'Poţi folosi formularul de mai jos pentru a trimite un e-mail acestui utilizator.
Adresa de e-mail introdusă de tine în [[Special:Preferences|preferinţele de utilizator]] va apărea ca adresa expeditorului e-mail-ului, deci destinatarul va putea să îţi răspundă direct.',
'usermailererror'  => 'Obiectul de mail a dat eroare:',
'defemailsubject'  => 'E-mail {{SITENAME}}',
'noemailtitle'     => 'Fără adresă de e-mail',
'noemailtext'      => 'Utilizatorul nu a specificat o adresă validă de e-mail.',
'nowikiemailtitle' => 'Nu este permis e-mail-ul',
'nowikiemailtext'  => 'Acest utilizator a ales să nu primească e-mail-uri de la alţi utilizatori.',
'email-legend'     => 'Trimite e-mail altui utilizator de la {{SITENAME}}',
'emailfrom'        => 'De la:',
'emailto'          => 'Către:',
'emailsubject'     => 'Subiect:',
'emailmessage'     => 'Mesaj:',
'emailsend'        => 'Trimite',
'emailccme'        => 'Trimite-mi pe e-mail o copie a mesajului meu.',
'emailccsubject'   => 'O copie a mesajului la $1: $2',
'emailsent'        => 'E-mail trimis',
'emailsenttext'    => 'E-mailul tău a fost trimis.',
'emailuserfooter'  => 'Acest mesaj a fost trimis de $1 către $2 prin intermediul funcţiei „Trimite e-mail” de la {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Pagini urmărite',
'mywatchlist'          => 'Pagini urmărite',
'watchlistfor'         => "(pentru '''$1''')",
'nowatchlist'          => 'Nu aţi ales să urmăriţi nici o pagină.',
'watchlistanontext'    => 'Te rugăm să $1 pentru a vizualiza sau edita itemii de pe lista ta de urmărire.',
'watchnologin'         => 'Nu sunteţi autentificat',
'watchnologintext'     => 'Trebuie să fiţi [[Special:UserLogin|autentificat]] pentru a vă modifica lista de pagini urmărite.',
'addedwatch'           => 'Adăugată la lista de pagini urmărite',
'addedwatchtext'       => 'Pagina „[[:$1]]” a fost adăugată la lista dv. de [[Special:Watchlist|articole urmărite]]. Modificările viitoare ale acestei pagini şi a paginii asociate de discuţii vor fi listate aici şi, în plus, ele vor apărea cu <b>caractere îngroşate</b> în pagina de [[Special:RecentChanges|modificări recente]] pentru evidenţiere.

Dacă doriţi să eliminaţi această pagină din lista dv. de pagini urmărite în viitor, apăsaţi pe „Nu mai urmări” în bara de comenzi în timp ce această pagină este vizibilă.',
'removedwatch'         => 'Ştearsă din lista de pagini urmărite',
'removedwatchtext'     => 'Pagina "[[:$1]]" a fost eliminată din [[Special:Watchlist|lista de pagini urmărite]].',
'watch'                => 'Urmăreşte',
'watchthispage'        => 'Urmăreşte pagina',
'unwatch'              => 'Nu mai urmări',
'unwatchthispage'      => 'Nu mai urmări',
'notanarticle'         => 'Nu este un articol',
'notvisiblerev'        => 'Versiunea a fost ştearsă',
'watchnochange'        => 'Nici una dintre paginile pe care le urmăriţi nu a fost modificată în perioada de timp afişată.',
'watchlist-details'    => '{{PLURAL:$1|O pagină|$1 pagini urmărite}}, excluzând paginile de discuţie.',
'wlheader-enotif'      => '*Notificarea email este activată',
'wlheader-showupdated' => "* Paginile care au modificări de la ultima ta vizită sunt afişate '''îngroşat'''",
'watchmethod-recent'   => 'căutarea schimbărilor recente pentru paginile urmărite',
'watchmethod-list'     => 'căutarea paginilor urmărite pentru schimbări recente',
'watchlistcontains'    => 'Lista de pagini urmărite conţine $1 {{PLURAL:$1|element|elemente}}.',
'iteminvalidname'      => "E o problemă cu elementul '$1', numele este invalid...",
'wlnote'               => "Mai jos se află {{PLURAL:$1|ultima schimbare|ultimele $1 schimbări}} din {{PLURAL:$2|ultima oră|ultimele '''$2''' ore}}.",
'wlshowlast'           => 'Arată ultimele $1 ore $2 zile $3',
'watchlist-options'    => 'Opţiunile listei de pagini urmărite',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Urmăreşte...',
'unwatching' => 'Aşteptaţi...',

'enotif_mailer'                => 'Sistemul de notificare {{SITENAME}}',
'enotif_reset'                 => 'Marchează toate paginile vizitate.',
'enotif_newpagetext'           => 'Aceasta este o pagină nouă.',
'enotif_impersonal_salutation' => '{{SITENAME}} utilizator',
'changed'                      => 'modificat',
'created'                      => 'creat',
'enotif_subject'               => 'Pagina $PAGETITLE de la {{SITENAME}} a fost $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'           => 'Vedeţi $1 pentru toate modificările de la ultima dvs. vizită.',
'enotif_lastdiff'              => 'Apasă $1 pentru a vedea această schimbare.',
'enotif_anon_editor'           => 'utilizator anonim $1',
'enotif_body'                  => 'Domnule/Doamnă $WATCHINGUSERNAME,

pagina $PAGETITLE de la {{SITENAME}} a fost $CHANGEDORCREATED în $PAGEEDITDATE de $PAGEEDITOR, vedeţi la $PAGETITLE_URL versiunea curentă.

$NEWPAGE

Sumarul utilizatorului: $PAGESUMMARY $PAGEMINOREDIT

Contactaţi utilizatorul:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nu vor mai fi alte notificări în cazul unor viitoare modificări în afara cazului în care vizitaţi pagina. Puteţi de asemenea reseta notificările pentru alte pagini urmărite.

             Al dvs. amic, sistemul de notificare {{SITENAME}}

--
Pentru a modifica preferinţele listei de urmărire, vizitaţi
{{fullurl:{{#special:Watchlist}}/edit}}

Asistenţă şi suport:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Şterge pagina',
'confirm'                => 'Confirmă',
'excontent'              => "conţinutul era: '$1'",
'excontentauthor'        => "conţinutul a fost: '$1' (şi unicul contribuitor era '$2')",
'exbeforeblank'          => "conţinutul înainte de golire era: '$1'",
'exblank'                => 'pagina era goală',
'delete-confirm'         => 'Şterge "$1"',
'delete-legend'          => 'Şterge',
'historywarning'         => "'''Atenţie:''' Pagina pe care o ştergi are o istorie cu $1 {{PLURAL:$1|revizie|revizii}}:",
'confirmdeletetext'      => 'Sunteţi pe cale să ştergeţi permanent o pagină sau imagine din baza de date, împreună cu istoria asociată acesteia. Vă rugăm să confirmaţi alegerea făcută de dvs., faptul că înţelegeţi consecinţele acestei acţiuni şi faptul că o faceţi în conformitate cu [[{{MediaWiki:Policy-url}}|Politica oficială]].',
'actioncomplete'         => 'Acţiune finalizată',
'actionfailed'           => 'Acţiunea a eşuat',
'deletedtext'            => 'Pagina "<nowiki>$1</nowiki>" a fost ştearsă. Vedeţi $2 pentru o listă a elementelor şterse recent.',
'deletedarticle'         => 'a şters "[[$1]]"',
'suppressedarticle'      => 'eliminate "[[$1]]"',
'dellogpage'             => 'Jurnal pagini şterse',
'dellogpagetext'         => 'Mai jos se află lista celor mai recente elemente şterse.',
'deletionlog'            => 'jurnal pagini şterse',
'reverted'               => 'Revenire la o versiune mai veche',
'deletecomment'          => 'Motiv pentru ştergere:',
'deleteotherreason'      => 'Motiv diferit/suplimentar:',
'deletereasonotherlist'  => 'Alt motiv',
'deletereason-dropdown'  => '*Motive uzuale
** Cererea autorului
** Violare drepturi de autor
** Vandalism',
'delete-edit-reasonlist' => 'Modifică motivele ştergerii',
'delete-toobig'          => 'Această pagină are un istoric al modificărilor mare, mai mult de $1 {{PLURAL:$1|revizie|revizii}}.
Ştergerea unei astfel de pagini a fost restricţionată pentru a preveni apariţia unor erori în {{SITENAME}}.',
'delete-warning-toobig'  => 'Această pagină are un istoric al modificărilor mult prea mare, mai mult de $1 {{PLURAL:$1|revizie|revizii}}.
Ştergere lui poate afecta baza de date a sitului {{SITENAME}};
continuă cu atenţie.',

# Rollback
'rollback'         => 'Editări de revenire',
'rollback_short'   => 'Revenire',
'rollbacklink'     => 'revenire',
'rollbackfailed'   => 'Revenirea nu s-a putut face',
'cantrollback'     => 'Nu se poate reveni; ultimul contribuitor este autorul acestui articol.',
'alreadyrolled'    => 'Nu se poate reveni peste ultima modificare a articolului [[:$1]] făcută de către [[User:$2|$2]] ([[User talk:$2|discuţie]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); altcineva a modificat articolul sau a revenit deja.

Ultima editare a fost făcută de către [[User:$3|$3]] ([[User talk:$3|discuţie]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Descrierea modificărilor a fost: „''$1''”.",
'revertpage'       => 'Anularea modificărilor efectuate de către [[Special:Contributions/$2|$2]] ([[User talk:$2|discuţie]]) şi revenire la ultima versiune de către [[User:$1|$1]]',
'rollback-success' => 'Anularea modificărilor făcute de $1;
revenire la ultima versiune de $2.',
'sessionfailure'   => 'Se pare că este o problemă cu sesiunea de autentificare; această acţiune a fost oprită ca o precauţie împotriva hijack. Apăsaţi "back" şi reîncărcaţi pagina de unde aţi venit, apoi reîncercaţi.',

# Protect
'protectlogpage'              => 'Jurnal protecţii',
'protectlogtext'              => 'Mai jos se află lista de blocări/deblocări a paginilor. Vezi [[Special:ProtectedPages]] pentru mai multe informaţii.',
'protectedarticle'            => 'a protejat "[[$1]]"',
'modifiedarticleprotection'   => 'schimbat nivelul de protecţie pentru "[[$1]]"',
'unprotectedarticle'          => 'a deprotejat "[[$1]]"',
'movedarticleprotection'      => 'setările de protecţie au fost mutate de la „[[$2]]” la „[[$1]]”',
'protect-title'               => 'Protejare "$1"',
'prot_1movedto2'              => 'a mutat [[$1]] la [[$2]]',
'protect-legend'              => 'Confirmă protejare',
'protectcomment'              => 'Motiv:',
'protectexpiry'               => 'Expiră:',
'protect_expiry_invalid'      => 'Timpul de expirare este nevalid.',
'protect_expiry_old'          => 'Timpul de expirare este în trecut.',
'protect-text'                => "Poţi vizualiza sau modifica nivelul de protecţie pentru pagina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nu poţi schimba nivelurile de protecţie fiind blocat.
Iată configuraţia curentă a paginii '''$1''':",
'protect-locked-dblock'       => "Nivelurile de protecţie nu pot fi aplicate deoarece baza de date este închisă.
Iată configuraţia curentă a paginii '''$1''':",
'protect-locked-access'       => "Contul dumneavoastră nu are permisiunea de a schimba nivelurile de protejare.
Aici sunt setările curente pentru pagina '''$1''':",
'protect-cascadeon'           => 'Această pagină este protejată deoarece este inclusă în {{PLURAL:$1|următoarea pagină, ce are|următoarele pagini ce au}} activată protejarea la modificare în cascadă.
Puteţi schimba nivelul de protejare al acestei pagini, dar asta nu va afecta protecţia în cascadă.',
'protect-default'             => 'Permite toţi utilizatorii',
'protect-fallback'            => 'Cere permisiunea "$1"',
'protect-level-autoconfirmed' => 'Blochează utilizatorii noi şi neînregistraţi',
'protect-level-sysop'         => 'Numai administratorii',
'protect-summary-cascade'     => 'în cascadă',
'protect-expiring'            => 'expiră $1 (UTC)',
'protect-expiry-indefinite'   => 'indefinit',
'protect-cascade'             => 'Protejare în cascadă - toate paginile incluse în această pagină vor fi protejate.',
'protect-cantedit'            => 'Nu puteţi schimba nivelul de protecţie a acestei pagini, deoarece nu aveţi permisiunea de a o modifica.',
'protect-othertime'           => 'Alt termen:',
'protect-othertime-op'        => 'alt termen',
'protect-existing-expiry'     => 'Data expirării: $3, $2',
'protect-otherreason'         => 'Motiv diferit/adiţional:',
'protect-otherreason-op'      => 'motiv diferit/adiţional',
'protect-dropdown'            => '*Motive comune de protejare
** Vandalism excesiv
** Spam excesiv
** Modificări neproductive
** Pagină cu trafic mare',
'protect-edit-reasonlist'     => 'Modifică motivele protejării',
'protect-expiry-options'      => '1 oră:1 hour,1 zi:1 day,1 săptămână:1 week,2 săptămâni:2 weeks,1 lună:1 month,3 luni:3 months,6 luni:6 months,1 an:1 year,infinit:infinite',
'restriction-type'            => 'Permisiune:',
'restriction-level'           => 'Nivel de restricţie:',
'minimum-size'                => 'Mărime minimă',
'maximum-size'                => 'Mărime maximă:',
'pagesize'                    => '(octeţi)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifică',
'restriction-move'   => 'Mută',
'restriction-create' => 'Creează',
'restriction-upload' => 'Încarcă',

# Restriction levels
'restriction-level-sysop'         => 'protejat complet',
'restriction-level-autoconfirmed' => 'semi-protejat',
'restriction-level-all'           => 'orice nivel',

# Undelete
'undelete'                     => 'Recuperează pagina ştearsă',
'undeletepage'                 => 'Vizualizează şi recuperează pagini şterse',
'undeletepagetitle'            => "'''Această listă cuprinde versiuni şterse ale paginii [[:$1|$1]].'''",
'viewdeletedpage'              => 'Vezi paginile şterse',
'undeletepagetext'             => '{{PLURAL:$1|Următoarea pagină a fost ştearsă, dar încă se află în arhivă şi poate fi recuperată|Următoarele $1 pagini au fost şterse, dar încă se află în arhivă şi pot fi recuperate}}. Reţine că arhiva se poate şterge din timp în timp.',
'undelete-fieldset-title'      => 'Recuperează versiuni',
'undeleteextrahelp'            => "Pentru a recupera întreaga pagină lăsaţi toate căsuţele nebifate şi apăsaţi butonul '''''Recuperează'''''. Pentru a realiza o recuperare selectivă bifaţi versiunile pe care doriţi să le recuperaţi şi apăsaţi butonul '''''Recuperează'''''. Butonul '''''Resetează'''''  va şterge comentariul şi toate bifările.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versiune arhivată|versiuni arhivate}}',
'undeletehistory'              => 'Dacă recuperaţi pagina, toate versiunile asociate vor fi adăugate retroactiv în istorie. Dacă o pagină nouă cu acelaşi nume a fost creată de la momentul ştergerii acesteia, versiunile recuperate vor apărea în istoria paginii, iar versiunea curentă a paginii nu va fi înlocuită automat de către versiunea recuperată.',
'undeleterevdel'               => 'Restaurarea unui revizii nu va fi efectuată dacă ea va apărea în capul listei de revizii parţial şterse.
În acest caz, trebuie să debifezi sau să arăţi (unhide) cea mai recentă versiune ştearsă.',
'undeletehistorynoadmin'       => 'Acest articol a fost şters. Motivul ştergerii apare mai jos, alături de detaliile utilzatorilor care au editat această pagină înainte de ştergere. Textul prorpiu-zis al reviziilor şterse este disponibil doar administratorilor.',
'undelete-revision'            => 'Ştergere revizia $1 (din $4 $5) de către $3:',
'undeleterevision-missing'     => 'Revizie lipsă sau invalidă.
S-ar putea ca această legătură să fie greşită, sau revizia a fost restaurată ori ştearsă din arhivă.',
'undelete-nodiff'              => 'Nu s-a găsit vreo revizie anterioară.',
'undeletebtn'                  => 'Recuperează',
'undeletelink'                 => 'vezi/recuperează',
'undeleteviewlink'             => 'vezi',
'undeletereset'                => 'Resetează',
'undeleteinvert'               => 'Exclude spaţiul',
'undeletecomment'              => 'Comentariu:',
'undeletedarticle'             => '"[[$1]]" a fost recuperat',
'undeletedrevisions'           => '{{PLURAL:$1|o revizie restaurată|$1 revizii restaurate}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revizie|revizii}} şi $2 {{PLURAL:$2|fişier|fişiere}} recuperate',
'undeletedfiles'               => '$1 {{PLURAL:$1|revizie recuperată|revizii recuperate}}',
'cannotundelete'               => 'Recuperarea a eşuat; este posibil ca altcineva să fi recuperat pagina deja.',
'undeletedpage'                => "<big>'''$1 a fost recuperat'''</big>

Consultaţi [[Special:Log/delete|jurnalul ştergerilor]] pentru a vedea toate ştergerile şi recuperările recente.",
'undelete-header'              => 'Vezi [[Special:Log/delete|logul de ştergere]] pentru paginile şterse recent.',
'undelete-search-box'          => 'Caută pagini şterse',
'undelete-search-prefix'       => 'Arată paginile care încep cu:',
'undelete-search-submit'       => 'Caută',
'undelete-no-results'          => 'Nicio pagină potrivită nu a fost găsită în arhiva paginilor şterse.',
'undelete-filename-mismatch'   => 'Nu poate fi restaurată revizia fişierului din data $1: nume nepotrivit',
'undelete-bad-store-key'       => 'Nu poate fi restaurată revizia fişierului din data $1: fişierul lipsea înainte de ştergere.',
'undelete-cleanup-error'       => 'Eroare la ştergerea arhivei nefolosite "$1".',
'undelete-missing-filearchive' => 'Nu poate fi restaurată arhiva fişierul cu ID-ul $1 pentru că nu există în baza de date.
S-ar putea ca ea să fie deja restaurată.',
'undelete-error-short'         => 'Eroare la restaurarea fişierului: $1',
'undelete-error-long'          => 'S-au găsit erori la ştergerea fişierului:

$1',
'undelete-show-file-confirm'   => 'Sunteţi sigur că doriţi să vizualizaţi o versiune ştearsă a fişierului "<nowiki>$1</nowiki>" din $2 ora $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'      => 'Spaţiu de nume:',
'invert'         => 'Exclude spaţiul',
'blanknamespace' => 'Articole',

# Contributions
'contributions'       => 'Contribuţii ale utilizatorului',
'contributions-title' => 'Contribuţiile utilizatorului pentru $1',
'mycontris'           => 'Contribuţii',
'contribsub2'         => 'Pentru $1 ($2)',
'nocontribs'          => 'Nu a fost găsită nici o modificare care să satisfacă acest criteriu.',
'uctop'               => '(sus)',
'month'               => 'Din luna (şi dinainte):',
'year'                => 'Începând cu anul (şi precedenţii):',

'sp-contributions-newbies'       => 'Arată doar contribuţiile conturilor noi',
'sp-contributions-newbies-sub'   => 'Pentru începători',
'sp-contributions-newbies-title' => 'Contribuţiile utilizatorului pentru conturile noi',
'sp-contributions-blocklog'      => 'Jurnal blocări',
'sp-contributions-deleted'       => 'contribuţiile şterse ale utilizatorului',
'sp-contributions-logs'          => 'jurnale',
'sp-contributions-talk'          => 'discuţie',
'sp-contributions-userrights'    => 'administrarea permisiunilor de utilizator',
'sp-contributions-search'        => 'Caută contribuţii',
'sp-contributions-username'      => 'Adresă IP sau nume de utilizator:',
'sp-contributions-submit'        => 'Caută',

# What links here
'whatlinkshere'            => 'Ce se leagă aici',
'whatlinkshere-title'      => 'Pagini care se leagă de "$1"',
'whatlinkshere-page'       => 'Pagină:',
'linkshere'                => "Următoarele pagini conţin legături către '''[[:$1]]''':",
'nolinkshere'              => "Nici o pagină nu se leagă la '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nici o pagină din spaţiul de nume ales nu se leagă la '''[[:$1]]'''.",
'isredirect'               => 'pagină de redirecţionare',
'istemplate'               => 'prin includerea formatului',
'isimage'                  => 'legătura fişierului',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterioara|anterioarele $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|următoarea|urmatoarele $1}}',
'whatlinkshere-links'      => '← legături',
'whatlinkshere-hideredirs' => '$1 redirecturile',
'whatlinkshere-hidetrans'  => '$1 transcluderile',
'whatlinkshere-hidelinks'  => '$1 legături',
'whatlinkshere-hideimages' => '$1 legături către imagine',
'whatlinkshere-filters'    => 'Filtre',

# Block/unblock
'blockip'                         => 'Blochează utilizator / IP',
'blockip-legend'                  => 'Blochează utilizator / IP',
'blockiptext'                     => "Pentru a bloca un utilizator completaţi rubricile de mai jos.<br />
'''Respectaţi [[{{MediaWiki:Policy-url}}|politica de blocare]].'''<br />
Precizaţi motivul blocării; de exemplu indicaţi paginile vandalizate de acest utilizator.",
'ipaddress'                       => 'Adresă IP:',
'ipadressorusername'              => 'Adresă IP sau nume de utilizator',
'ipbexpiry'                       => 'Expiră',
'ipbreason'                       => 'Motiv:',
'ipbreasonotherlist'              => 'Alt motiv',
'ipbreason-dropdown'              => '*Motivele cele mai frecvente
** Introducere de informaţii false
** Ştergere conţinut fără explicaţii
** Introducere de legături externe de publicitate (spam)
** Creare pagini fără sens
** Tentative de intimidare
** Abuz utilizare conturi multiple
** Nume de utilizator inacceptabil',
'ipbanononly'                     => 'Blochează doar utilizatorii anonimi',
'ipbcreateaccount'                => 'Nu permite crearea de conturi',
'ipbemailban'                     => 'Nu permite utilizatorului să trimită e-mail',
'ipbenableautoblock'              => 'Blochează automat ultima adresă IP folosită de acest utilizator şi toate adresele de la care încearcă să editeze în viitor',
'ipbsubmit'                       => 'Blochează acest utilizator',
'ipbother'                        => 'Alt termen:',
'ipboptions'                      => '2 ore:2 hours,1 zi:1 day,3 zile:3 days,1 săptămână:1 week,2 săptămâni:2 weeks,1 lună:1 month,3 luni:3 months,6 luni:6 months,1 an:1 year,infinit:infinite',
'ipbotheroption'                  => 'altul',
'ipbotherreason'                  => 'Motiv diferit/adiţional:',
'ipbhidename'                     => 'Ascunde numele de utilizator la editare şi afişare',
'ipbwatchuser'                    => 'Urmăreşte pagina sa de utilizator şi de discuţii',
'ipballowusertalk'                => 'Permite acestui utilizator să-şi modifice propria pagină de discuţie cât timp este blocat',
'ipb-change-block'                => 'Reblochează utilizatorul cu aceşti parametri',
'badipaddress'                    => 'Adresa IP este invalidă.',
'blockipsuccesssub'               => 'Utilizatorul a fost blocat',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] a fost blocată.<br />
Vezi [[Special:IPBlockList|lista de adrese IP şi conturi blocate]] pentru a revizui adresele blocate.',
'ipb-edit-dropdown'               => 'Modifică motivele blocării',
'ipb-unblock-addr'                => 'Deblochează $1',
'ipb-unblock'                     => 'Deblocaţi un nume de utilizator sau o adresă IP',
'ipb-blocklist-addr'              => 'Blocări existente pentru $1',
'ipb-blocklist'                   => 'Vezi blocările existente',
'ipb-blocklist-contribs'          => 'Contribuţii la $1',
'unblockip'                       => 'Deblochează adresă IP',
'unblockiptext'                   => 'Folosiţi formularul de mai jos pentru a restaura permisiunea de scriere pentru adrese IP sau nume de utilizator blocate anterior.',
'ipusubmit'                       => 'Elimină blocarea',
'unblocked'                       => '[[User:$1|$1]] a fost deblocat',
'unblocked-id'                    => 'Blocarea $1 a fost eliminată',
'ipblocklist'                     => 'Lista adreselor IP şi a conturilor blocate',
'ipblocklist-legend'              => 'Găseşte un utilizator blocat',
'ipblocklist-username'            => 'Nume de utilizator sau adresă IP:',
'ipblocklist-sh-userblocks'       => '$1 blocări de conturi',
'ipblocklist-sh-tempblocks'       => '$1 blocări temporare',
'ipblocklist-sh-addressblocks'    => '$1 blocări de adrese IP',
'ipblocklist-submit'              => 'Caută',
'blocklistline'                   => '$1, $2 a blocat $3 ($4)',
'infiniteblock'                   => 'termen nelimitat',
'expiringblock'                   => 'expiră în $1 la $2',
'anononlyblock'                   => 'doar anonimi',
'noautoblockblock'                => 'autoblocare dezactivată',
'createaccountblock'              => 'crearea de conturi blocată',
'emailblock'                      => 'e-mail blocat',
'blocklist-nousertalk'            => 'nu poate modifica propria pagină de discuţie',
'ipblocklist-empty'               => 'Lista blocărilor este goală.',
'ipblocklist-no-results'          => 'Nu există blocare pentru adresa IP sau numele de utilizator.',
'blocklink'                       => 'blochează',
'unblocklink'                     => 'deblochează',
'change-blocklink'                => 'modifică blocarea',
'contribslink'                    => 'contribuţii',
'autoblocker'                     => 'Autoblocat fiindcă folosiţi aceeaşi adresă IP ca şi „[[User:$1|$1]]”.
Motivul blocării utilizatorului $1 este: „$2”',
'blocklogpage'                    => 'Jurnal blocări',
'blocklogentry'                   => 'a blocat "[[$1]]" pe o perioadă de $2 $3',
'reblock-logentry'                => 'a fost schimbată blocarea pentru [[$1]] cu data expirării la $2 $3',
'blocklogtext'                    => 'Acest jurnal cuprinde acţiunile de blocare şi deblocare. Adresele IP blocate automat nu sunt afişate. Vizitaţi [[Special:IPBlockList|lista de adrese blocate]] pentru o listă explicită a adreselor blocate în acest moment.',
'unblocklogentry'                 => 'a deblocat $1',
'block-log-flags-anononly'        => 'doar utilizatorii anonimi',
'block-log-flags-nocreate'        => 'crearea de conturi dezactivată',
'block-log-flags-noautoblock'     => 'autoblocarea dezactivată',
'block-log-flags-noemail'         => 'e-mail blocat',
'block-log-flags-nousertalk'      => 'nu poate edita propria pagină de discuţie',
'block-log-flags-angry-autoblock' => 'autoblocarea avansată activată',
'block-log-flags-hiddenname'      => 'nume de utilizator ascuns',
'range_block_disabled'            => 'Abilitatea dezvoltatorilor de a bloca serii de adrese este dezactivată.',
'ipb_expiry_invalid'              => 'Dată de expirare invalidă.',
'ipb_expiry_temp'                 => 'Blocarea numelor de utilizator ascunse trebuie să fie permanentă.',
'ipb_hide_invalid'                => 'Imposibil de a suprima acest cont; acesta poate avea prea multe modificări.',
'ipb_already_blocked'             => '"$1" este deja blocat',
'ipb-needreblock'                 => '== Deja blocat ==
$1 este deja blocat. Vrei să schimbi parametrii?',
'ipb_cant_unblock'                => 'Eroare: nu găsesc identificatorul $1. Probabil a fost deja deblocat.',
'ipb_blocked_as_range'            => 'Eroare: Adresa IP $1 nu este blocată direct deci nu poate fi deblocată.
Face parte din area de blocare $2, care nu poate fi deblocată.',
'ip_range_invalid'                => 'Serie IP invalidă.',
'blockme'                         => 'Blochează-mă',
'proxyblocker'                    => 'Blocaj de proxy',
'proxyblocker-disabled'           => 'Această funcţie este dezactivată.',
'proxyblockreason'                => 'Adresa ta IP a fost blocată pentru că este un proxy deschis. Te rog, contactează provider-ul tău de servicii Internet sau tehnicieni IT şi informează-i asupra acestei probleme serioase de securitate.',
'proxyblocksuccess'               => 'Realizat.',
'sorbsreason'                     => 'Adresa dumneavoastră IP este listată ca un proxy deschis în DNSBL.',
'sorbs_create_account_reason'     => 'Adresa dvs. IP este listată la un proxy deschis în lista neagră DNS. Nu vă puteţi crea un cont',
'cant-block-while-blocked'        => 'Nu poţi bloca alţi utilizatori cât timp şi tu eşti blocat.',

# Developer tools
'lockdb'              => 'Blochează baza de date',
'unlockdb'            => 'Deblochează baza de date',
'lockdbtext'          => 'Blocarea bazei de date va împiedica pe toţi utilizatorii
să modifice pagini, să-şi schimbe preferinţele, să-şi modifice listele de
pagini urmărite şi orice alte operaţiuni care ar necesita schimări
în baza de date.
Te rugăm să confirmi că intenţionezi acest lucru şi faptul că vei debloca
baza de date atunci când vei încheia operaţiunile de întreţinere.',
'unlockdbtext'        => 'Deblocarea bazei de date va permite tuturor utilizatorilor să editeze pagini, să-şi schimbe preferinţele, să-şi editeze listele de pagini urmărite şi orice alte operaţiuni care ar necesita schimări în baza de date. Te rugăm să-ţi confirmi intenţia de a face acest lucru.',
'lockconfirm'         => 'Da, chiar vreau să blochez baza de date.',
'unlockconfirm'       => 'Da, chiar vreau să deblochez baza de date.',
'lockbtn'             => 'Blochează baza de date',
'unlockbtn'           => 'Deblochează baza de date',
'locknoconfirm'       => 'Nu aţi bifat căsuţa de confirmare.',
'lockdbsuccesssub'    => 'Baza de date a fost blocată',
'unlockdbsuccesssub'  => 'Baza de date a fost deblocată',
'lockdbsuccesstext'   => 'Baza de date {{SITENAME}} a fost blocată la scriere.<br />
Nu uita să o deblochezi după ce termini operaţiunile administrative pentru care ai blocat-o.',
'unlockdbsuccesstext' => 'Baza de date a fost deblocată.',
'lockfilenotwritable' => 'Fişierul bazei de date închise nu poate fi scris.
Pentru a închide sau deschide baza de date, acesta trebuie să poată fi scris de serverul web.',
'databasenotlocked'   => 'Baza de date nu este blocată.',

# Move page
'move-page'                    => 'Mută $1',
'move-page-legend'             => 'Mută pagina',
'movepagetext'                 => "Puteţi folosi formularul de mai jos pentru a redenumi o pagină, mutându-i toată istoria sub noul nume.
Pagina veche va deveni o pagină de redirecţionare către pagina nouă.
Legăturile către pagina veche nu vor fi redirecţionate către cea nouă;
nu uitaţi să verificaţi dacă nu există redirecţionări [[Special:DoubleRedirects|duble]] sau [[Special:BrokenRedirects|invalide]].

Vă rugăm să reţineţi că sunteţi responsabil(ă) pentru a face legăturile vechi să rămână valide.

Reţineţi că pagina '''nu va fi mutată''' dacă există deja o pagină cu noul titlu, în afară de cazul că este complet goală sau este
o redirecţionare şi în plus nu are nici o istorie de modificare.
Cu alte cuvinte, veţi putea muta înapoi o pagină pe care aţi mutat-o greşit, dar nu veţi putea suprascrie o pagină validă existentă prin mutarea alteia.

'''ATENŢIE!'''
Aceasta poate fi o schimbare drastică şi neaşteptată pentru o pagină populară;
vă rugăm, să vă asiguraţi că înţelegeţi toate consecinţele înainte de a continua.",
'movepagetalktext'             => "Pagina asociată de discuţii, dacă există, va fi mutată
automat odată cu aceasta '''afară de cazul că''':
* Mutaţi pagina în altă secţiune a {{SITENAME}}
* Există deja o pagină de discuţii cu conţinut (care nu este goală), sau
* Nu confirmi căsuţa de mai jos.

În oricare din cazurile de mai sus va trebui să muţi sau să unifici
manual paginile de discuţii, dacă doreşti acest lucru.",
'movearticle'                  => 'Mută pagina',
'movenologin'                  => 'Nu eşti autentificat',
'movenologintext'              => 'Trebuie să fii un utilizator înregistrat şi să te [[Special:UserLogin|autentifici]] pentru a muta o pagină.',
'movenotallowed'               => 'Nu ai permisiunea să muţi pagini.',
'movenotallowedfile'           => 'Nu ai permisiunea de a muta fişiere.',
'cant-move-user-page'          => 'Nu ai permisiunea de a muta paginile utilizatorului (în afară de subpagini).',
'cant-move-to-user-page'       => 'Nu aveţi permisiunea de a muta o pagină în pagina utilizatorului (cu excepţia subpaginii utilizatorului).',
'newtitle'                     => 'Titlul nou',
'move-watch'                   => 'Urmăreşte această pagină',
'movepagebtn'                  => 'Mută pagina',
'pagemovedsub'                 => 'Pagina a fost mutată',
'movepage-moved'               => '<big>\'\'\'Pagina "$1" a fost mutată la pagina "$2"\'\'\'</big>',
'movepage-moved-redirect'      => 'O redirecţionare a fost creată.',
'movepage-moved-noredirect'    => 'Crearea redirecţionărilor a fost suprimată.',
'articleexists'                => 'O pagină cu acelaşi nume există deja, sau numele pe care l-aţi ales este invalid. Sunteţi rugat să alegeţi un alt nume.',
'cantmove-titleprotected'      => 'Nu poţi muta o pagina în această locaţie, pentru că noul titlu a fost protejat la creare',
'talkexists'                   => "'''Pagina în sine a fost mutată, dar pagina de discuţii nu a putut fi mutată deoarece deja există o alta cu acelaşi nume. Te rugăm să unifici manual cele două pagini de discuţii.'''",
'movedto'                      => 'mutată la',
'movetalk'                     => 'Mută şi pagina de "discuţii" dacă se poate.',
'move-subpages'                => 'Mută subpaginile (până la $1)',
'move-talk-subpages'           => 'Mută subpaginile paginii de discuţii (până la $1)',
'movepage-page-exists'         => 'Pagina $1 există deja şi nu poate fi rescrisă automat.',
'movepage-page-moved'          => 'Pagina $1 a fost mutată la $2.',
'movepage-page-unmoved'        => 'Pagina $1 nu a putut fi mutată la $2.',
'movepage-max-pages'           => 'Maxim $1 {{PLURAL:$1|pagină a fost mutată|pagini au fost mutate}}, nicio altă pagină nu va mai fi mutată automat.',
'1movedto2'                    => 'a mutat [[$1]] la [[$2]]',
'1movedto2_redir'              => 'a mutat [[$1]] la [[$2]] prin redirecţionare',
'move-redirect-suppressed'     => 'redirecţionarea a fost suprimată',
'movelogpage'                  => 'Jurnal mutări',
'movelogpagetext'              => 'Mai jos se află o listă cu paginile mutate.',
'movesubpage'                  => '{{PLURAL:$1|Subpagină|Subpagini}}',
'movesubpagetext'              => 'Această pagină are $1 {{PLURAL:$1|subpagină afişată|subpagini afişate}} mai jos.',
'movenosubpage'                => 'Această pagină nu are subpagini.',
'movereason'                   => 'Motiv:',
'revertmove'                   => 'revenire',
'delete_and_move'              => 'Şterge şi mută',
'delete_and_move_text'         => '==Ştergere necesară==

Articolul de destinaţie "[[:$1]]" există deja. Doriţi să îl ştergeţi pentru a face loc mutării?',
'delete_and_move_confirm'      => 'Da, şterge pagina.',
'delete_and_move_reason'       => 'Şters pentru a face loc mutării',
'selfmove'                     => 'Titlurile sursei şi ale destinaţiei sunt aceleaşi; nu puteţi muta o pagină peste ea însăşi.',
'immobile-source-namespace'    => 'Nu se pot muta paginile din spaţiul de nume "$1"',
'immobile-target-namespace'    => 'Nu se pot muta paginile în spaţiul de nume "$1"',
'immobile-target-namespace-iw' => 'Legătura interwiki nu este o ţintă validă pentru mutare.',
'immobile-source-page'         => 'Această pagină nu poate fi mutată.',
'immobile-target-page'         => 'Nu poate fi mutat la destinaţia cu acest titlu.',
'imagenocrossnamespace'        => 'Fişierul nu poate fi mutat la un spaţiu de nume care nu este destinat fişierelor',
'imagetypemismatch'            => 'Extensia nouă a fişierului nu se potriveşte cu tipul acestuia',
'imageinvalidfilename'         => 'Numele fişierului destinaţie este invalid',
'fix-double-redirects'         => 'Actualizează toate redirecţionările care trimit la titlul original',
'move-leave-redirect'          => 'Lasă în urmă o redirecţionare',
'protectedpagemovewarning'     => "'''Atenţie:''' Această pagină a fost blocată şi poate fi mutată de utilizatorii cu drepturi de administrator.",
'semiprotectedpagemovewarning' => "'''Notă:''' Această pagină a fost blocată şi poate fi mutată doar de utilizatorii înregistraţi.",

# Export
'export'            => 'Exportă pagini',
'exporttext'        => 'Puteţi exporta textul şi istoricul unei pagini anume sau ale unui grup de pagini în XML. 
Acesta poate fi apoi importate în alt wiki care rulează software MediaWiki prin [[Special:Import|pagina de importare]].

Pentru a exporta, introduceţi titlurile în căsuţa de mai jos, unul pe linie, şi alegeţi dacă doriţi să exportaţi doar această versiune sau şi cele mai vechi, cu istoricul lor, sau versiunea curentă cu informaţii despre ultima modificare.

În al doilea caz puteţi folosi o legătură, de exemplu [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pentru pagina „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Include numai versiunea curentă, nu şi toată istoria',
'exportnohistory'   => "---- '''Notă:''' exportarea versiunii complete a paginilor prin acest formular a fost scoasă din uz din motive de performanţă.",
'export-submit'     => 'Exportă',
'export-addcattext' => 'Adaugă pagini din categoria:',
'export-addcat'     => 'Adaugă',
'export-addnstext'  => 'Adaugă pagini din spaţiul de nume:',
'export-addns'      => 'Adaugă',
'export-download'   => 'Salvează ca fişier',
'export-templates'  => 'Include formate',
'export-pagelinks'  => 'Includere pagini legate de la o adâncime de:',

# Namespace 8 related
'allmessages'                   => 'Toate mesajele',
'allmessagesname'               => 'Nume',
'allmessagesdefault'            => 'Textul standard',
'allmessagescurrent'            => 'Textul curent',
'allmessagestext'               => 'Aceasta este lista completă a mesajelor disponibile în domeniul MediaWiki.
Vă rugăm să vizitaţi [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] şi [http://translatewiki.net translatewiki.net] dacă vreţi să contribuiţi la localizarea programului MediaWiki generic.',
'allmessagesnotsupportedDB'     => "'''{{ns:special}}:Allmessages''' nu poate fi folosit deoarece '''\$wgUseDatabaseMessages''' este închisă.",
'allmessages-filter-legend'     => 'Filtru',
'allmessages-filter'            => 'Filtru după statutul de modificare:',
'allmessages-filter-unmodified' => 'Nemodificat',
'allmessages-filter-all'        => 'Toţi',
'allmessages-filter-modified'   => 'Modificat',
'allmessages-prefix'            => 'Filtru după prefix:',
'allmessages-language'          => 'Limbă:',
'allmessages-filter-submit'     => 'Du-te',

# Thumbnails
'thumbnail-more'           => 'Extinde',
'filemissing'              => 'Fişier lipsă',
'thumbnail_error'          => 'Eroare la generarea previzualizării: $1',
'djvu_page_error'          => 'Numărul paginii DjVu eronat',
'djvu_no_xml'              => 'Imposibil de obţinut XML-ul pentru fişierul DjVu',
'thumbnail_invalid_params' => 'Parametrii invalizi ai imaginii miniatură',
'thumbnail_dest_directory' => 'Nu poate fi creat directorul destinaţie',
'thumbnail_image-type'     => 'Acest tip de imagine nu este suportat',
'thumbnail_gd-library'     => 'Configuraţie incompletă a bibliotecii GD: lipseşte funcţia $1',
'thumbnail_image-missing'  => 'Fişierul următor nu poate fi găsit: $1',

# Special:Import
'import'                     => 'Importă pagini',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Selectează un wiki şi titlul paginii care trebuie importate. Datele reviziilor şi numele editorilor vor fi salvate. Toate acţiunile de import transwiki pot fi găsite la [[Special:Log/import|log import]]',
'import-interwiki-source'    => 'Wiki/pagină sursă:',
'import-interwiki-history'   => 'Copiază toate versiunile istoricului acestei pagini',
'import-interwiki-templates' => 'Includeţi toate formatele',
'import-interwiki-submit'    => 'Importă',
'import-interwiki-namespace' => 'Transferă către spaţiul de nume:',
'import-upload-filename'     => 'Nume fişier:',
'import-comment'             => 'Comentariu:',
'importtext'                 => 'Te rog exportă fişierul din sursa wiki folosind [[Special:Export|utilitarul de exportare]].
Salvează-l pe discul tău şi trimite-l aici.',
'importstart'                => 'Se importă paginile...',
'import-revision-count'      => '$1 {{PLURAL:$1|versiune|versiuni}}',
'importnopages'              => 'Nu există pagini de importat.',
'importfailed'               => 'Import eşuat: $1',
'importunknownsource'        => 'Tipul sursei de import este necunoscut',
'importcantopen'             => 'Fişierul importat nu a putut fi deschis',
'importbadinterwiki'         => 'Legătură interwiki greşită',
'importnotext'               => 'Gol sau fără text',
'importsuccess'              => 'Import reuşit!',
'importhistoryconflict'      => 'Există istorii contradictorii (se poate să fi importat această pagină înainte)',
'importnosources'            => 'Nici o sursă de import transwiki a fost definită şi încărcările directe ale istoricului sunt oprite.',
'importnofile'               => 'Nici un fişier pentru import nu a fost încărcat.',
'importuploaderrorsize'      => 'Încărcarea fişierului a eşuat.
Fişierul are o mărime mai mare decât limita de încărcare permisă.',
'importuploaderrorpartial'   => 'Încărcarea fişierului a eşuat.
Fişierul a fost incărcat parţial.',
'importuploaderrortemp'      => 'Încărcarea fişierului a eşuat.
Un dosar temporar lipseşte.',
'import-parse-failure'       => 'Eroare la analiza importului XML',
'import-noarticle'           => 'Nicio pagină de importat!',
'import-nonewrevisions'      => 'Toate versiunile au fost importate anterior.',
'xml-error-string'           => '$1 la linia $2, col $3 (octet $4): $5',
'import-upload'              => 'Încărcare date XML',
'import-token-mismatch'      => 'S-au pierdut datele sesiunii. Vă rugăm să încercaţi din nou.',
'import-invalid-interwiki'   => 'Nu se poate importa din wiki-ul specificat.',

# Import log
'importlogpage'                    => 'Log import',
'importlogpagetext'                => 'Imoprturi administrative de pagini de la alte wiki, cu istoricul editărilor.',
'import-logentry-upload'           => '$1 importate prin upload',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|versiune|versiuni}}',
'import-logentry-interwiki'        => 'transwikificat $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versiune|versiuni}} de la $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Pagina dumneavoastră de utilizator',
'tooltip-pt-anonuserpage'         => 'Pagina de utilizator pentru adresa IP curentă',
'tooltip-pt-mytalk'               => 'Pagina dumneavoastră de discuţii',
'tooltip-pt-anontalk'             => 'Discuţii despre editări pentru adresa IP curentă',
'tooltip-pt-preferences'          => 'Preferinţele mele',
'tooltip-pt-watchlist'            => 'Lista paginilor pe care le monitorizez.',
'tooltip-pt-mycontris'            => 'Listă de contribuţii',
'tooltip-pt-login'                => 'Eşti încurajat să te autentifici, deşi acest lucru nu este obligatoriu.',
'tooltip-pt-anonlogin'            => 'Eşti încurajat să te autentifici, deşi acest lucru nu este obligatoriu.',
'tooltip-pt-logout'               => 'Închide sesiunea',
'tooltip-ca-talk'                 => 'Discuţie despre articol',
'tooltip-ca-edit'                 => 'Poţi edita această pagină. Te rugăm să previzualizezi conţinutul înainte de salvare.',
'tooltip-ca-addsection'           => 'Adaugă o nouă secţiune.',
'tooltip-ca-viewsource'           => 'Aceasta pagina este protejată. Poţi sa vezi doar codul sursă.',
'tooltip-ca-history'              => 'Versiuni vechi ale acestui document.',
'tooltip-ca-protect'              => 'Protejează acest document.',
'tooltip-ca-unprotect'            => 'Deprotejează această pagină',
'tooltip-ca-delete'               => 'Şterge acest document.',
'tooltip-ca-undelete'             => 'Restaureaza editările făcute acestui document, înainte să fi fost şters.',
'tooltip-ca-move'                 => 'Mută acest document.',
'tooltip-ca-watch'                => 'Adaugă acest document în lista ta de monitorizare.',
'tooltip-ca-unwatch'              => 'Şterge acest document din lista ta de monitorizare.',
'tooltip-search'                  => 'Căutare în {{SITENAME}}',
'tooltip-search-go'               => 'Du-te la pagina cu acest nume dacă există',
'tooltip-search-fulltext'         => 'Caută paginile pentru acest text',
'tooltip-p-logo'                  => 'Pagina principală',
'tooltip-n-mainpage'              => 'Vizitează pagina principală',
'tooltip-n-mainpage-description'  => 'Vizitaţi pagina principală',
'tooltip-n-portal'                => 'Despre proiect, ce poţi face tu, unde găseşti soluţii.',
'tooltip-n-currentevents'         => 'Găseşte informaţii despre evenimente curente',
'tooltip-n-recentchanges'         => 'Lista ultimelor schimbări realizate în acest wiki.',
'tooltip-n-randompage'            => 'Mergi spre o pagină aleatoare',
'tooltip-n-help'                  => 'Locul în care găseşti ajutor.',
'tooltip-t-whatlinkshere'         => 'Lista tuturor paginilor wiki care conduc spre această pagină',
'tooltip-t-recentchangeslinked'   => 'Schimbări recente în legătură cu această pagină',
'tooltip-feed-rss'                => 'Alimentează fluxul RSS pentru această pagină',
'tooltip-feed-atom'               => 'Alimentează fluxul Atom pentru această pagină',
'tooltip-t-contributions'         => 'Vezi lista de contribuţii ale acestui utilizator',
'tooltip-t-emailuser'             => 'Trimite un e-mail acestui utilizator',
'tooltip-t-upload'                => 'Încarcă fişiere',
'tooltip-t-specialpages'          => 'Lista tuturor paginilor speciale',
'tooltip-t-print'                 => 'Versiunea de tipărit a acestei pagini',
'tooltip-t-permalink'             => 'Legătura permanentă către această versiune a paginii',
'tooltip-ca-nstab-main'           => 'Vezi articolul',
'tooltip-ca-nstab-user'           => 'Vezi pagina de utilizator',
'tooltip-ca-nstab-media'          => 'Vezi pagina media',
'tooltip-ca-nstab-special'        => 'Aceasta este o pagină specială, (nu) poţi edita pagina în sine.',
'tooltip-ca-nstab-project'        => 'Vezi pagina proiectului',
'tooltip-ca-nstab-image'          => 'Vezi pagina imaginii',
'tooltip-ca-nstab-mediawiki'      => 'Vezi mesajul de sistem',
'tooltip-ca-nstab-template'       => 'Vezi formatul',
'tooltip-ca-nstab-help'           => 'Vezi pagina de ajutor',
'tooltip-ca-nstab-category'       => 'Vezi categoria',
'tooltip-minoredit'               => 'Marcaţi această modificare ca fiind minoră',
'tooltip-save'                    => 'Salvaţi modificările dumneavoastră',
'tooltip-preview'                 => 'Previzualizarea modificărilor dvs., folosiţi-o vă rugăm înainte de a salva!',
'tooltip-diff'                    => 'Arată ce modificări ai făcut textului.',
'tooltip-compareselectedversions' => 'Vezi diferenţele între cele două versiuni selectate de pe această pagină.',
'tooltip-watch'                   => 'Adaugă această pagină la lista mea de pagini urmărite',
'tooltip-recreate'                => 'Recreează',
'tooltip-upload'                  => 'Porneşte încărcare',
'tooltip-rollback'                => '"Revenire" anulează modificarea(ările) de pe această pagină a ultimului contibuitor dintr-un singur click',
'tooltip-undo'                    => '"Anulează" şterge această modificare şi deschide formularul de modificare în modulul de previzualizare.
Permite adăugarea unui motiv în descrierea modificărilor',

# Stylesheets
'common.css'      => '/** CSS plasate aici vor fi aplicate tuturor apariţiilor */',
'standard.css'    => '/* CSS plasate aici vor afecta utilizatorii stilului Standard */',
'nostalgia.css'   => '/* CSS plasate aici vor afecta utilizatorii stilului Nostalgia  */',
'cologneblue.css' => '/* CSS plasate aici vor afecta utilizatorii stilului Cologne Blue */',
'monobook.css'    => '/* modificaţi acest fişier pentru a adapta înfăţişarea monobook-ului pentru tot situl*/',
'myskin.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Myskin */',
'chick.css'       => '/* CSS plasate aici vor afecta utilizatorii stilului Chick */',
'simple.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Simple */',
'modern.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Modern */',
'vector.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Vector */',
'print.css'       => '/* CSS plasate aici vor afecta modul în care paginile vor fi imprimate */',

# Metadata
'nodublincore'      => 'Metadatele Dublin Core RDF sunt dezactivate pentru acest server.',
'nocreativecommons' => 'Metadatele Creative Commons RDF dezactivate pentru acest server.',
'notacceptable'     => 'Serverul wiki nu poate oferi date într-un format pe care clientul tău să-l poată citi.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utilizator anonim|Utilizatori anonimi}} ai {{SITENAME}}',
'siteuser'         => 'Utilizator {{SITENAME}} $1',
'lastmodifiedatby' => 'Pagina a fost modificată în $1, la $2 de către $3.',
'othercontribs'    => 'Bazat pe munca lui $1.',
'others'           => 'alţii',
'siteusers'        => '{{PLURAL:$2|Utilizator|Utilizatori}} {{SITENAME}} $1',
'creditspage'      => 'Credenţiale',
'nocredits'        => 'Nu există credenţiale disponibile pentru această pagină.',

# Spam protection
'spamprotectiontitle' => 'Filtru de protecţie spam',
'spamprotectiontext'  => 'Pagina pe care doriţi să o salvaţi a fost blocată de filtrul spam. Aceasta se datorează probabil unei legături spre un site extern. Aţi putea verifica următoarea expresie regulată:',
'spamprotectionmatch' => 'Următorul text a fost oferit de filtrul de spam: $1',
'spambot_username'    => 'Curăţarea de spam a MediaWiki',
'spam_reverting'      => 'Revenire la ultima versiune care nu conţine legături către $1',
'spam_blanking'       => 'Toate reviziile conţinând legături către $1, au eşuat',

# Info page
'infosubtitle'   => 'Informaţii pentru pagină',
'numedits'       => 'Număr de modificări (articole): $1',
'numtalkedits'   => 'Număr de modificări (pagina de discuţii): $1',
'numwatchers'    => 'Număr de utilizatori care urmăresc: $1',
'numauthors'     => 'Număr de autori distincţi (articole): $1',
'numtalkauthors' => 'Număr de autori distincţi (pagini de discuţii): $1',

# Skin names
'skinname-standard'    => 'Normală',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Albastru de Cologne',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'StilulMeu',
'skinname-chick'       => 'Şic',
'skinname-simple'      => 'Simplu',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vector',

# Math options
'mw_math_png'    => 'Întodeauna afişează PNG',
'mw_math_simple' => 'HTML pentru formule simple, altfel PNG',
'mw_math_html'   => 'HTML dacă este posibil, altfel PNG',
'mw_math_source' => 'Lasă ca TeX (pentru browser-ele text)',
'mw_math_modern' => 'Recomandat pentru browser-ele moderne',
'mw_math_mathml' => 'MathML dacă este posibil (experimental)',

# Math errors
'math_failure'          => 'Nu s-a putut interpreta',
'math_unknown_error'    => 'eroare necunoscută',
'math_unknown_function' => 'funcţie necunoscută',
'math_lexing_error'     => 'eroare lexicală',
'math_syntax_error'     => 'eroare de sintaxă',
'math_image_error'      => 'Conversiune în PNG eşuată',
'math_bad_tmpdir'       => 'Nu se poate crea sau nu se poate scrie în directorul temporar pentru formule matematice',
'math_bad_output'       => 'Nu se poate crea sau nu se poate scrie în directorul de ieşire pentru formule matematice',
'math_notexvc'          => 'Lipseşte executabilul texvc; vezi math/README pentru configurare.',

# Patrolling
'markaspatrolleddiff'                 => 'Marchează ca patrulat',
'markaspatrolledtext'                 => 'Marchează acest articol ca patrulat',
'markedaspatrolled'                   => 'A fost marcat ca patrulat',
'markedaspatrolledtext'               => 'Modificarea selectată a fost marcată ca patrulată.',
'rcpatroldisabled'                    => 'Opţiunea de patrulare a modificărilor recente este dezactivată',
'rcpatroldisabledtext'                => 'Patrularea modificărilor recente este în prezent dezactivată.',
'markedaspatrollederror'              => 'Nu se poate marca ca patrulat',
'markedaspatrollederrortext'          => 'Trebuie să specificaţi o revizie care să fie marcată ca patrulată.',
'markedaspatrollederror-noautopatrol' => 'Nu puteţi marca propriile modificări ca patrulate.',

# Patrol log
'patrol-log-page'      => 'Jurnal patrulări',
'patrol-log-header'    => 'Mai jos apare o listă a tuturor paginilor marcate ca verificate.',
'patrol-log-line'      => 'a marcat versiunea $1 a $2 ca verificată $3',
'patrol-log-auto'      => '(automat)',
'patrol-log-diff'      => 'revizia $1',
'log-show-hide-patrol' => '$1 istoricul versiunilor patrulate',

# Image deletion
'deletedrevision'                 => 'A fost ştearsă vechea revizie $1.',
'filedeleteerror-short'           => 'Eroare la ştergerea fişierului: $1',
'filedeleteerror-long'            => 'Au apărut erori când se încerca ştergerea fişierului:

$1',
'filedelete-missing'              => 'Fişierul „$1” nu poate fi şters, deoarece nu există.',
'filedelete-old-unregistered'     => 'Revizia specificată a fişierului "$1" nu este în baza de date.',
'filedelete-current-unregistered' => 'Fişierul specificat "$1" nu este în baza de date.',
'filedelete-archive-read-only'    => 'Directorul arhivei "$1" nu poate fi scris de serverul web.',

# Browsing diffs
'previousdiff' => '← Diferenţa anterioară',
'nextdiff'     => 'Diferenţa următoare →',

# Media information
'mediawarning'         => "'''Atenţie''': Acest fişier poate conţine cod maliţios, executându-l, sistemul dvs. poate fi compromis.<hr />",
'imagemaxsize'         => "Limita mărimii imaginilor:<br />''(pentru paginile de descriere)''",
'thumbsize'            => 'Mărime thumbnail:',
'widthheight'          => '$1x$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pagină|pagini}}',
'file-info'            => '(mărime fişier: $1, tip MIME: $2)',
'file-info-size'       => '($1 × $2 pixeli, mărime fişier: $3, tip MIME: $4)',
'file-nohires'         => '<small>Rezoluţii mai mari nu sunt disponibile.</small>',
'svg-long-desc'        => '(fişier SVG, cu dimensiunea nominală de $1 × $2 pixeli, mărime fişier: $3)',
'show-big-image'       => 'Măreşte rezoluţia imaginii',
'show-big-image-thumb' => '<small>Mărimea acestei previzualizări: $1 × $2 pixeli</small>',
'file-info-gif-looped' => 'în buclă',
'file-info-gif-frames' => '$1 {{PLURAL:$1|imagine|imagini}}',

# Special:NewFiles
'newimages'             => 'Galeria de imagini noi',
'imagelisttext'         => "Mai jos se află lista a '''$1''' {{PLURAL:$1|fişier ordonat|fişiere ordonate}} $2.",
'newimages-summary'     => 'Această pagină specială arată ultimele fişiere încărcate.',
'newimages-legend'      => 'Filtru',
'newimages-label'       => 'Numele fişierului (sau parte din el):',
'showhidebots'          => '($1 roboţi)',
'noimages'              => 'Nimic de văzut.',
'ilsubmit'              => 'Caută',
'bydate'                => 'după dată',
'sp-newimages-showfrom' => 'Arată imaginile noi începând cu $1, ora $2',

# Bad image list
'bad_image_list' => 'Formatul este următorul:

Numai elementele unei liste sunt luate în considerare. (Acestea sunt linii ce încep cu *)

Prima legătură de pe linie trebuie să fie spre un fişier defectuos.

Orice legături ce urmează pe aceeaşi linie sunt considerate excepţii, adică pagini unde fişierul poate apărea inclus direct.',

# Metadata
'metadata'          => 'Informaţii',
'metadata-help'     => 'Acest fişier conţine informaţii suplimentare, introduse probabil de aparatul fotografic digital sau scannerul care l-a generat. Dacă fişierul a fost modificat între timp, este posibil ca unele detalii să nu mai fie valabile.',
'metadata-expand'   => 'Afişează detalii suplimentare',
'metadata-collapse' => 'Ascunde detalii suplimentare',
'metadata-fields'   => 'Datele suplimentare EXIF listate aici vor fi incluse în pagina dedicată imaginii când tabelul cu metadata este restrâns.
Altele vor fi ascunse implicit.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Lăţime',
'exif-imagelength'                 => 'Înălţime',
'exif-bitspersample'               => 'Biţi pe componentă',
'exif-compression'                 => 'Metodă de comprimare',
'exif-photometricinterpretation'   => 'Compoziţia pixelilor',
'exif-orientation'                 => 'Orientare',
'exif-samplesperpixel'             => 'Numărul de componente',
'exif-planarconfiguration'         => 'Aranjarea datelor',
'exif-ycbcrsubsampling'            => 'Mostră din fracţia Y/C',
'exif-ycbcrpositioning'            => 'Poziţionarea Y şi C',
'exif-xresolution'                 => 'Rezoluţie orizontală',
'exif-yresolution'                 => 'Rezoluţie verticală',
'exif-resolutionunit'              => 'Unitate de rezoluţie pentru X şi Y',
'exif-stripoffsets'                => 'Locaţia datelor imaginii',
'exif-rowsperstrip'                => 'Numărul de linii per bandă',
'exif-stripbytecounts'             => 'Biţi corespunzători benzii comprimate',
'exif-jpeginterchangeformat'       => 'Offset pentru JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Biţi de date JPEG',
'exif-transferfunction'            => 'Funcţia de transfer',
'exif-whitepoint'                  => 'Cromaticitatea punctului alb',
'exif-primarychromaticities'       => 'Coordonatele cromatice ale culorilor primare',
'exif-ycbcrcoefficients'           => 'Tăria culorii coeficienţilor matricei de transformare',
'exif-referenceblackwhite'         => 'Perechile de valori de referinţă albe şi negre',
'exif-datetime'                    => 'Data şi ora modificării fişierului',
'exif-imagedescription'            => 'Titlul imaginii',
'exif-make'                        => 'Producătorul aparatului foto',
'exif-model'                       => 'Modelul aparatului foto',
'exif-software'                    => 'Software folosit',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titularul drepturilor de autor',
'exif-exifversion'                 => 'Versiune exif',
'exif-flashpixversion'             => 'Versiune susţinută de Flashpix',
'exif-colorspace'                  => 'Spaţiu de culoare',
'exif-componentsconfiguration'     => 'Semnificaţia componentelor',
'exif-compressedbitsperpixel'      => 'Mod de comprimare a imaginii',
'exif-pixelydimension'             => 'Lăţimea validă a imaginii',
'exif-pixelxdimension'             => 'Valind image height',
'exif-makernote'                   => 'Observaţiile producătorului',
'exif-usercomment'                 => 'Comentariile utilizatorilor',
'exif-relatedsoundfile'            => 'Fişierul audio asemănător',
'exif-datetimeoriginal'            => 'Data şi ora producerii imaginii',
'exif-datetimedigitized'           => 'Data şi ora digitizării',
'exif-subsectime'                  => 'Data/Ora milisecunde',
'exif-subsectimeoriginal'          => 'Data/Ora/Original milisecunde',
'exif-subsectimedigitized'         => 'Milisecunde DateTimeDigitized',
'exif-exposuretime'                => 'Timp de expunere',
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'Diafragmă',
'exif-exposureprogram'             => 'Program de expunere',
'exif-spectralsensitivity'         => 'Sensibilitate spectrală',
'exif-isospeedratings'             => 'Evaluarea vitezei ISO',
'exif-oecf'                        => 'Factorul de conversie optoelectronic',
'exif-shutterspeedvalue'           => 'Viteza de închidere',
'exif-aperturevalue'               => 'Diafragmă',
'exif-brightnessvalue'             => 'Luminozitate',
'exif-exposurebiasvalue'           => 'Ajustarea expunerii',
'exif-maxaperturevalue'            => 'Apertura maximă',
'exif-subjectdistance'             => 'Distanţa faţă de subiect',
'exif-meteringmode'                => 'Forma de măsurare',
'exif-lightsource'                 => 'Sursă de lumină',
'exif-flash'                       => 'Bliţ',
'exif-focallength'                 => 'Distanţa focală a obiectivului',
'exif-subjectarea'                 => 'Suprafaţa subiectului',
'exif-flashenergy'                 => 'Energie bliţ',
'exif-spatialfrequencyresponse'    => 'Răspunsul frecvenţei spaţiale',
'exif-focalplanexresolution'       => 'Rezoluţia focală plană X',
'exif-focalplaneyresolution'       => 'Rezoluţia focală plană Y',
'exif-focalplaneresolutionunit'    => 'Unitatea de măsură pentru rezoluţia focală plană',
'exif-subjectlocation'             => 'Locaţia subiectului',
'exif-exposureindex'               => 'Indexul expunerii',
'exif-sensingmethod'               => 'Metoda sensibilă',
'exif-filesource'                  => 'Fişier sursă',
'exif-scenetype'                   => 'Tipul scenei',
'exif-cfapattern'                  => 'Mozaic CFA (filtre color)',
'exif-customrendered'              => 'Prelucrarea imaginii',
'exif-exposuremode'                => 'Mod de expunere',
'exif-whitebalance'                => 'Balanţa albă',
'exif-digitalzoomratio'            => 'Raportul zoom-ului digital',
'exif-focallengthin35mmfilm'       => 'Distanţă focală pentru film de 35 mm',
'exif-scenecapturetype'            => 'Tipul de surprindere a scenei',
'exif-gaincontrol'                 => 'Controlul scenei',
'exif-contrast'                    => 'Contrast',
'exif-saturation'                  => 'Saturaţie',
'exif-sharpness'                   => 'Ascuţime',
'exif-devicesettingdescription'    => 'Descrierea reglajelor aparatului',
'exif-subjectdistancerange'        => 'Distanţa faţă de subiect',
'exif-imageuniqueid'               => 'Identificarea imaginii unice',
'exif-gpsversionid'                => 'Versiunea de conversie GPS',
'exif-gpslatituderef'              => 'Latitudine nordică sau sudică',
'exif-gpslatitude'                 => 'Latitudine',
'exif-gpslongituderef'             => 'Longitudine estică sau vestică',
'exif-gpslongitude'                => 'Longitudine',
'exif-gpsaltituderef'              => 'Indicarea altitudinii',
'exif-gpsaltitude'                 => 'Altitudine',
'exif-gpstimestamp'                => 'ora GPS (ceasul atomic)',
'exif-gpssatellites'               => 'Sateliţi utilizaţi pentru măsurare',
'exif-gpsstatus'                   => 'Starea receptorului',
'exif-gpsmeasuremode'              => 'Mod de măsurare',
'exif-gpsdop'                      => 'Precizie de măsurare',
'exif-gpsspeedref'                 => 'Unitatea de măsură pentru viteză',
'exif-gpsspeed'                    => 'Viteza receptorului GPS',
'exif-gpstrackref'                 => 'Referinţă pentru direcţia de mişcare',
'exif-gpstrack'                    => 'Direcţie de mişcare',
'exif-gpsimgdirectionref'          => 'Referinţă pentru direcţia imaginii',
'exif-gpsimgdirection'             => 'Direcţia imaginii',
'exif-gpsmapdatum'                 => 'Expertiza geodezică a datelor utilizate',
'exif-gpsdestlatituderef'          => 'Referinţă pentru latitudinea destinaţiei',
'exif-gpsdestlatitude'             => 'Destinaţia latitudinală',
'exif-gpsdestlongituderef'         => 'Referinţă pentru longitudinea destinaţiei',
'exif-gpsdestlongitude'            => 'Longitudinea destinaţiei',
'exif-gpsdestbearingref'           => 'Referinţă pentru raportarea destinaţiei',
'exif-gpsdestbearing'              => 'Raportarea destinaţiei',
'exif-gpsdestdistanceref'          => 'Referinţă pentru distanţa până la destinaţie',
'exif-gpsdestdistance'             => 'Distanţa până la destinaţie',
'exif-gpsprocessingmethod'         => 'Numele metodei de procesare GPS',
'exif-gpsareainformation'          => 'Numele domeniului GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Corecţia diferenţială GPS',

# EXIF attributes
'exif-compression-1' => 'Necomprimată',

'exif-unknowndate' => 'Dată necunoscută',

'exif-orientation-1' => 'Normală',
'exif-orientation-2' => 'Oglindită orizontal',
'exif-orientation-3' => 'Rotită cu 180°',
'exif-orientation-4' => 'Oglindită vertical',
'exif-orientation-5' => 'Rotită 90° în sens opus acelor de ceasornic şi oglindită vertical',
'exif-orientation-6' => 'Rotită 90° în sensul acelor de ceasornic',
'exif-orientation-7' => 'Rotită 90° în sensul acelor de ceasornic şi oglindită vertical',
'exif-orientation-8' => 'Rotită 90° în sens opus acelor de ceasornic',

'exif-planarconfiguration-1' => 'format compact',
'exif-planarconfiguration-2' => 'format plat',

'exif-componentsconfiguration-0' => 'neprecizat',

'exif-exposureprogram-0' => 'Neprecizat',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritate diafragmă',
'exif-exposureprogram-4' => 'Prioritate timp',
'exif-exposureprogram-5' => 'Program creativ (prioritate dată profunzimii)',
'exif-exposureprogram-6' => 'Program acţiune (prioritate dată timpului de expunere scurt)',
'exif-exposureprogram-7' => 'Mod portret (focalizare pe subiect şi fundal neclar)',
'exif-exposureprogram-8' => 'Mod peisaj (focalizare pe fundal)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Necunoscut',
'exif-meteringmode-1'   => 'Medie',
'exif-meteringmode-2'   => 'Media ponderată la centru',
'exif-meteringmode-3'   => 'Punct',
'exif-meteringmode-4'   => 'MultiPunct',
'exif-meteringmode-5'   => 'Model',
'exif-meteringmode-6'   => 'Parţial',
'exif-meteringmode-255' => 'Alta',

'exif-lightsource-0'   => 'Necunoscută',
'exif-lightsource-1'   => 'Lumină solară',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungsten (lumină incandescentă)',
'exif-lightsource-4'   => 'Bliţ',
'exif-lightsource-9'   => 'Vreme frumoasă',
'exif-lightsource-10'  => 'Cer noros',
'exif-lightsource-11'  => 'Umbră',
'exif-lightsource-12'  => 'Fluorescent luminos (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescent luminos alb (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescent alb rece (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescent alb (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lumină standard A',
'exif-lightsource-18'  => 'Lumină standard B',
'exif-lightsource-19'  => 'Lumină standard C',
'exif-lightsource-24'  => 'Lumină artificială normată ISO în studio',
'exif-lightsource-255' => 'Altă sursă de lumină',

# Flash modes
'exif-flash-fired-0'    => 'Flash-ul nu a fost declanşat',
'exif-flash-fired-1'    => 'Flash declanşat',
'exif-flash-return-0'   => 'niciun stroboscop nu întoarce funcţie de detecţie',
'exif-flash-return-2'   => 'stroboscopul întoarce o lumină nedetectată',
'exif-flash-return-3'   => 'stroboscopul întoarce o lumină detectată',
'exif-flash-mode-1'     => 'obligatorie declanşarea flash-ului',
'exif-flash-mode-2'     => 'obligatorie suprimarea flash-ului',
'exif-flash-mode-3'     => 'modul automat',
'exif-flash-function-1' => 'Fără funcţie flash',
'exif-flash-redeye-1'   => 'mod de îndepărtare a ochilor roşii',

'exif-focalplaneresolutionunit-2' => 'ţoli',

'exif-sensingmethod-1' => 'Nedefinit',
'exif-sensingmethod-2' => 'Senzorul suprafeţei color one-chip',
'exif-sensingmethod-3' => 'Senzorul suprafeţei color two-chip',
'exif-sensingmethod-4' => 'Senzorul suprafeţei color three-chip',
'exif-sensingmethod-5' => 'Senzorul suprafeţei color secvenţiale',
'exif-sensingmethod-7' => 'Senzor triliniar',
'exif-sensingmethod-8' => 'Senzorul linear al culorii secvenţiale',

'exif-scenetype-1' => 'O imagine fotografiată direct',

'exif-customrendered-0' => 'Prelucrare normală',
'exif-customrendered-1' => 'Prelucrare nestandard',

'exif-exposuremode-0' => 'Expunere automată',
'exif-exposuremode-1' => 'Expunere manuală',
'exif-exposuremode-2' => 'Serie automată de expuneri',

'exif-whitebalance-0' => 'Auto-balanţa albă',
'exif-whitebalance-1' => 'Balanţa manuală albă',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Portret',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Scenă nocturnă',

'exif-gaincontrol-0' => 'Niciuna',
'exif-gaincontrol-1' => 'Avantajul scăzut de sus',
'exif-gaincontrol-2' => 'Avantajul mărit de sus',
'exif-gaincontrol-3' => 'Avantajul scăzut de jos',
'exif-gaincontrol-4' => 'Avantajul mărit de jos',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Redus',
'exif-contrast-2' => 'Mărit',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturaţie redusă',
'exif-saturation-2' => 'Saturaţie ridicată',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Uşor',
'exif-sharpness-2' => 'Tare',

'exif-subjectdistancerange-0' => 'Necunoscut',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Apropiat',
'exif-subjectdistancerange-3' => 'Îndepărtat',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'latitudine nordică',
'exif-gpslatitude-s' => 'latitudine sudică',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'longitudine estică',
'exif-gpslongitude-w' => 'longitudine vestică',

'exif-gpsstatus-a' => 'Măsurare în curs',
'exif-gpsstatus-v' => 'Măsurarea interoperabilităţii',

'exif-gpsmeasuremode-2' => 'măsurătoare bidimensională',
'exif-gpsmeasuremode-3' => 'măsurătoare tridimensională',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometri pe oră',
'exif-gpsspeed-m' => 'Mile pe oră',
'exif-gpsspeed-n' => 'Noduri',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcţia reală',
'exif-gpsdirection-m' => 'Direcţie magnetică',

# External editor support
'edit-externally'      => 'Editează acest fişier folosind o aplicaţie externă.',
'edit-externally-help' => '(Vedeţi [http://www.mediawiki.org/wiki/Manual:External_editors instrucţiuni de instalare] pentru mai multe informaţii)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tot',
'imagelistall'     => 'toate',
'watchlistall2'    => 'toate',
'namespacesall'    => 'toate',
'monthsall'        => 'toate',

# E-mail address confirmation
'confirmemail'             => 'Confirmă adresa de email',
'confirmemail_noemail'     => 'Nu aveţi o adresă de email validă setată la [[Special:Preferences|preferinţe]].',
'confirmemail_text'        => '{{SITENAME}} necesită validarea adresei de e-mail înaintea folosirii funcţiilor e-mail. 
Apăsaţi butonul de dedesupt pentru a trimite un e-mail de confirmare către adresa dvs. 
Acesta va include o legătură care va conţine codul; 
încărcaţi legătura în browser pentru a valida adresa de e-mail.',
'confirmemail_pending'     => 'Un cod de confirmare a fost trimis deja la adresa de e-mail indicată;
dacă ai creat recent contul, ar fi bine să aștepți câteva minute e-mailul de confirmare, înainte de a cere un nou cod.',
'confirmemail_send'        => 'Trimite un cod de confirmare',
'confirmemail_sent'        => 'E-mailul de confirmare a fost trimis.',
'confirmemail_oncreate'    => 'Un cod de confirmare a fost trimis la adresa de e-mail.
Acest cod nu este necesar pentru autentificare, dar trebuie transmis înainte de activarea oricăror proprietăţi bazate pe e-mail din wiki.',
'confirmemail_sendfailed'  => 'Nu am putut trimite e-mailul de confirmare. Verificaţi adresa după caractere invalide.

Serverul de mail a returnat: $1',
'confirmemail_invalid'     => 'Cod de confirmare invalid. Acest cod poate fi expirat.',
'confirmemail_needlogin'   => 'Trebuie să vă $1 pentru a vă confirma adresa de email.',
'confirmemail_success'     => 'Adresa de email a fost confirmată. Vă puteţi autentifica şi bucura de wiki.',
'confirmemail_loggedin'    => 'Adresa de email a fost confirmată.',
'confirmemail_error'       => 'Ceva nu a funcţionat la salvarea confirmării.',
'confirmemail_subject'     => 'Confirmare adresă email la {{SITENAME}}',
'confirmemail_body'        => 'Cineva, probabil dumneavoastră de la adresa IP $1, şi-a înregistrat un cont "$2" cu această adresă de email la {{SITENAME}}.

Pentru a confirma că acest cont aparţine într-adevăr dumneavoastră şi să vă activaţi funcţionalităţile email la {{SITENAME}}, deschideţi această legătură în browser:

$3

Dacă *nu* sunteţi dumneavoastră, deschideţi această legătură în browser:

$5

Codul de confirmare va expira la $4.',
'confirmemail_invalidated' => 'Confirmarea adresei de e-mail a fost anulată',
'invalidateemail'          => 'Anulează confirmarea adresei de e-mail',

# Scary transclusion
'scarytranscludedisabled' => '[Transcluderea interwiki este dezactivată]',
'scarytranscludefailed'   => '[Şiretlicul formatului a dat greş pentru $1]',
'scarytranscludetoolong'  => '[URL-ul este prea lung]',

# Trackbacks
'trackbackbox'      => 'Urmăritori la acest articol:<br />
$1',
'trackbackremove'   => '([$1 Şterge])',
'trackbacklink'     => 'Urmăritor',
'trackbackdeleteok' => 'Urmăritorul a fost şters cu succes.',

# Delete conflict
'deletedwhileediting' => "'''Atenţie''': Această pagină a fost ştearsă după ce ai început să o modifici!",
'confirmrecreate'     => "Utilizatorul [[User:$1|$1]] ([[User talk:$1|discuţie]]) a şters acest articol după ce aţi început să contribuţi la el din motivul:
: ''$2''
Vă rugăm să confirmaţi faptul că într-adevăr doriţi să recreaţi acest articol.",
'recreate'            => 'Recreează',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Doriţi să reîncărcaţi pagina?',
'confirm-purge-bottom' => 'Actualizaea unei pagini şterge cache-ul şi forţează cea mai recentă variantă să apară.',

# Multipage image navigation
'imgmultipageprev' => '← pagina anterioară',
'imgmultipagenext' => 'pagina următoare →',
'imgmultigo'       => 'Du-te!',
'imgmultigoto'     => 'Du-te la pagina $1',

# Table pager
'ascending_abbrev'         => 'cresc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Pagina următoare',
'table_pager_prev'         => 'Pagina anterioară',
'table_pager_first'        => 'Prima pagină',
'table_pager_last'         => 'Ultima pagină',
'table_pager_limit'        => 'Arată $1 itemi pe pagină',
'table_pager_limit_submit' => 'Du-te',
'table_pager_empty'        => 'Nici un rezultat',

# Auto-summaries
'autosumm-blank'   => 'Şters conţinutul paginii',
'autosumm-replace' => 'Pagină înlocuită cu „$1”',
'autoredircomment' => 'Redirecţionat înspre [[$1]]',
'autosumm-new'     => 'Pagină nouă: $1',

# Live preview
'livepreview-loading' => 'Încărcare…',
'livepreview-ready'   => 'Încărcare… Gata!',
'livepreview-failed'  => 'Previzualizarea directă a eşuat! Încearcă previzualizarea normală.',
'livepreview-error'   => 'Conectarea a eşuat: $1 „$2”.
Încearcă previzualizarea normală.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Modificările mai noi de $1 {{PLURAL:$1|secondă|seconde}} pot să nu apară în listă.',
'lag-warn-high'   => 'Serverul bazei de date este suprasolicitat, astfel încît modificările făcute în ultimele $1 {{PLURAL:$1|secundă|secunde}} pot să nu apară în listă.',

# Watchlist editor
'watchlistedit-numitems'       => 'Lista ta de pagini urmărite conţine {{PLURAL:$1|1 titlu|$1 titluri}}, excluzând paginile de discuţii.',
'watchlistedit-noitems'        => 'Lista de pagini urmărite este goală.',
'watchlistedit-normal-title'   => 'Editează lista de urmărire',
'watchlistedit-normal-legend'  => 'Şterge titluri din lista de urmărire',
'watchlistedit-normal-explain' => 'Titlurile din lista ta de pagini urmărite sunt enumerate mai jos.
Pentru a elimina un titlu, validează chenarul de lângă el şi apasă pe Şterge Titluri.
Poţi şi să editezi direct o [[Special:Watchlist/raw|listă brută a paginilor urmărite]].',
'watchlistedit-normal-submit'  => 'Şterge titluri',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titlu a fost şters|$1 titluri au fost şterse}} din lista de urmărire:',
'watchlistedit-raw-title'      => 'Modifică lista brută a paginilor urmărite',
'watchlistedit-raw-legend'     => 'Modifică lista brută de pagini urmărite',
'watchlistedit-raw-explain'    => 'Titlurile din lista paginilor urmărite este afişată mai jos, şi poate fi modificată adăugând şi eliminând pagini;
un titlu pe linie.
Când ai terminat de modificat lista, apasă pe Actualizează lista paginilor urmărite.
Poţi şi să [[Special:Watchlist/edit|foloseşti un editor standard]].',
'watchlistedit-raw-titles'     => 'Titluri:',
'watchlistedit-raw-submit'     => 'Actualizează lista paginilor urmărite',
'watchlistedit-raw-done'       => 'Lista paginilor urmărite a fost actualizată.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titlu a fost adăugat|$1 titluri au fost adăugate}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titlu a fost şters|$1 titluri au fost şterse}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Vizualizează schimbările relevante',
'watchlisttools-edit' => 'Vezi şi modifică lista paginilor urmărite',
'watchlisttools-raw'  => 'Modifică lista brută a paginilor urmărite',

# Core parser functions
'unknown_extension_tag' => 'Extensie etichetă necunoscută "$1"',
'duplicate-defaultsort' => '\'\'\'Atenţie:\'\'\' Cheia de sortare implicită "$2" o înlocuieşte pe precedenta "$1".',

# Special:Version
'version'                          => 'Versiune',
'version-extensions'               => 'Extensii instalate',
'version-specialpages'             => 'Pagini speciale',
'version-parserhooks'              => 'Hook-uri parser',
'version-variables'                => 'Variabile',
'version-other'                    => 'Altele',
'version-mediahandlers'            => 'Suport media',
'version-hooks'                    => 'Hook-uri',
'version-extension-functions'      => 'Funcţiile extensiilor',
'version-parser-extensiontags'     => 'Taguri extensie parser',
'version-parser-function-hooks'    => 'Hook-uri funcţii parser',
'version-skin-extension-functions' => 'Funcţiile extensiei interfeţei',
'version-hook-name'                => 'Nume hook',
'version-hook-subscribedby'        => 'Subscris de',
'version-version'                  => '(Versiune $1)',
'version-license'                  => 'Licenţă',
'version-software'                 => 'Software instalat',
'version-software-product'         => 'Produs',
'version-software-version'         => 'Versiune',

# Special:FilePath
'filepath'         => 'Cale fişier',
'filepath-page'    => 'Fişier:',
'filepath-submit'  => 'Cale',
'filepath-summary' => 'Această pagină specială întoarce calea completă a fişierului.
Imaginile sunt prezentate la rezoluţia maximă, alte tipuri de fişiere vor porni direct în programele asociate.

Introdu numele fişierului fără prefixul "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Caută fişiere duplicate',
'fileduplicatesearch-summary'  => 'Caută fişiere duplicat bazate pe valoarea sa hash.

Introdu numele fişierului fără prefixul "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Caută un duplicat',
'fileduplicatesearch-filename' => 'Nume fişier:',
'fileduplicatesearch-submit'   => 'Caută',
'fileduplicatesearch-info'     => '$1 × $2 pixeli<br />Mărime fişier: $3<br />Tip MIME: $4',
'fileduplicatesearch-result-1' => 'Fişierul "$1" nu are un duplicat identic.',
'fileduplicatesearch-result-n' => 'Fişierul "$1" are {{PLURAL:$2|1 duplicat identic|$2 duplicate identice}}.',

# Special:SpecialPages
'specialpages'                   => 'Pagini speciale',
'specialpages-note'              => '----
* Pagini speciale normale.
* <strong class="mw-specialpagerestricted">Pagini speciale restricţionate.</strong>',
'specialpages-group-maintenance' => 'Întreţinere',
'specialpages-group-other'       => 'Alte pagini speciale',
'specialpages-group-login'       => 'Autentificare / Înregistrare',
'specialpages-group-changes'     => 'Schimbări recente şi jurnale',
'specialpages-group-media'       => 'Fişiere',
'specialpages-group-users'       => 'Utilizatori şi permisiuni',
'specialpages-group-highuse'     => 'Pagini utilizate intens',
'specialpages-group-pages'       => 'Liste de pagini',
'specialpages-group-pagetools'   => 'Unelte pentru pagini',
'specialpages-group-wiki'        => 'Date şi unelte wiki',
'specialpages-group-redirects'   => 'Pagini speciale de redirecţionare',
'specialpages-group-spam'        => 'Unelte spam',

# Special:BlankPage
'blankpage'              => 'Pagină goală',
'intentionallyblankpage' => 'Această pagină este goală în mod intenţionat',

# External image whitelist
'external_image_whitelist' => ' #Lasă această linie exact aşa cum este <pre>
#Pune fragmentele expresiei regulate (doar partea care merge între //) mai jos
#Acestea vor fi potrivite cu URL-uri de exterior (hotlinked)
#Acelea care se potrivesc vor fi afişate ca imagini, altfel va fi afişat doar un link la imagine
#Liniile care încep cu # sunt tratate ca comentarii
#Acesta este insensibil la majuscule sau minuscule

#Pune toate fragmentele regex deasupra aceastei linii. Lasă această linie exact aşa cum este</pre>',

# Special:Tags
'tags'                    => 'Taguri de modificare valide',
'tag-filter'              => 'Filtrează [[Special:Tags|etichetele]]:',
'tag-filter-submit'       => 'Filtru',
'tags-title'              => 'Etichete',
'tags-intro'              => 'Această pagină afişează etichetele pe care software-ul le poate marca cu o editare, şi semnificaţia lor.',
'tags-tag'                => 'Nume de etichetă internă',
'tags-display-header'     => 'Aspect în listele schimbărilor',
'tags-description-header' => 'Descriere completă a sensului',
'tags-hitcount-header'    => 'Modificări etichetate',
'tags-edit'               => 'modifică',
'tags-hitcount'           => '$1 {{PLURAL:$1|schimbare|schimbări}}',

# Database error messages
'dberr-header'      => 'Acest site are o problemă',
'dberr-problems'    => 'Ne cerem scuze! Acest site întâmpină dificultăţi tehnice.',
'dberr-again'       => 'Aşteaptă câteva minute şi încearcă din nou.',
'dberr-info'        => '(Nu pot contacta baza de date a serverului: $1)',
'dberr-usegoogle'   => 'Între timp poţi efectua căutarea folosind Google.',
'dberr-outofdate'   => 'De reţinut ca indexarea conţinutului nostru de către ei poate să nu fie actualizată.',
'dberr-cachederror' => 'Următoarea pagină este o copie în cache a paginii cerute, s-ar putea să nu fie actualizată.',

# HTML forms
'htmlform-invalid-input'       => 'Există probleme la valorile introduse',
'htmlform-select-badoption'    => 'Valoarea specificată nu este o opţiune validă.',
'htmlform-int-invalid'         => 'Valoarea specificată nu este un întreg.',
'htmlform-float-invalid'       => 'Valoarea specificată nu este un număr.',
'htmlform-int-toolow'          => 'Valoarea specificată este sub maximul $1',
'htmlform-int-toohigh'         => 'Valoarea specificată este peste maximul $1',
'htmlform-submit'              => 'Trimite',
'htmlform-reset'               => 'Anulează modificările',
'htmlform-selectorother-other' => 'Altul',

# Add categories per AJAX
'ajax-add-category-summary'    => 'Adaugă categoria "$1"',
'ajax-remove-category-summary' => 'Elimină categoria "$1"',
'ajax-error-title'             => 'Eroare',
'ajax-error-dismiss'           => 'OK',
'ajax-remove-category-error'   => 'Eliminarea categoriei nu a fost posibilă.
Acest lucru are loc de obicei atunci când categoria a fost adăugată în pagină printr-un format.',

);
