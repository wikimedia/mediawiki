<?php
/** Romanian (Română)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AdiJapan
 * @author Cin
 * @author Emily
 * @author Firilacroco
 * @author KlaudiuMihaila
 * @author Laurap
 * @author Memo18
 * @author Mihai
 * @author Minisarm
 * @author Misterr
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
	'currentmonth'          => array( '1', 'NUMARLUNACURENTA', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'NUMELUNACURENTA', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'NUMELUNACURENTAGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'NUMARLUNACURENTAABREV', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'NUMARZIUACURENTA', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'NUMARZIUACURENTA2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NUMEZIUACURENTA', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANULCURENT', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ORACURENTA', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'NUMARDEARTICOLE', 'NUMBEROFARTICLES' ),
	'msgnw'                 => array( '0', 'MSJNOU:', 'MSGNW:' ),
	'img_right'             => array( '1', 'dreapta', 'right' ),
	'img_left'              => array( '1', 'stanga', 'left' ),
	'img_none'              => array( '1', 'nu', 'none' ),
	'img_center'            => array( '1', 'centru', 'center', 'centre' ),
	'img_framed'            => array( '1', 'cadru', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'faracadru', 'frameless' ),
	'img_page'              => array( '1', 'pagina=$1', 'pagina $1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'chenar', 'border' ),
	'img_sub'               => array( '1', 'indice', 'sub' ),
	'img_super'             => array( '1', 'exponent', 'super', 'sup' ),
	'img_top'               => array( '1', 'sus', 'top' ),
	'img_middle'            => array( '1', 'mijloc', 'middle' ),
	'img_bottom'            => array( '1', 'jos', 'bottom' ),
	'sitename'              => array( '1', 'NUMESITE', 'SITENAME' ),
	'ns'                    => array( '0', 'SN:', 'NS:' ),
	'localurl'              => array( '0', 'URLLOCAL:', 'LOCALURL:' ),
	'grammar'               => array( '0', 'GRAMATICA:', 'GRAMMAR:' ),
	'fullurl'               => array( '0', 'URLCOMPLET:', 'FULLURL:' ),
	'filepath'              => array( '0', 'CALEAFISIERULUI:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'eticheta', 'tag' ),
	'hiddencat'             => array( '1', '__ASCUNDECAT__', '__HIDDENCAT__' ),
	'pagesize'              => array( '1', 'MARIMEPAGINA', 'PAGESIZE' ),
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
	'DeletedContributions'      => array( 'Contibuţii şterse' ),
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
);

$fallback8bitEncoding = 'iso8859-2';

$messages = array(
# User preference toggles
'tog-underline'               => 'Subliniază legăturile',
'tog-highlightbroken'         => 'Afișează <a href="" class="new">așa</a> legăturile către paginile inexistente (alternativă: așa<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Aranjează justificat paragrafele',
'tog-hideminor'               => 'Ascunde modificările minore în schimbări recente',
'tog-hidepatrolled'           => 'Ascunde în schimbări recente editările patrulate',
'tog-newpageshidepatrolled'   => 'Ascunde paginile patrulate din lista de pagini noi',
'tog-extendwatchlist'         => 'Extinde lista de articole urmărite pentru a arăta toate schimbările efectuate, nu doar pe cele mai recente',
'tog-usenewrc'                => 'Afișează varianta îmbunătățită a schimbărilor recente (necesită JavaScript)',
'tog-numberheadings'          => 'Numerotează automat secțiunile',
'tog-showtoolbar'             => 'Afișează bara de unelte pentru modificare (JavaScript)',
'tog-editondblclick'          => 'Activează modificarea paginii prin dublu clic (JavaScript)',
'tog-editsection'             => 'Activează modificarea secțiunilor prin legăturile [modifică]',
'tog-editsectiononrightclick' => 'Activează modificarea secţiunilor prin clic dreapta
pe titlul secțiunii (JavaScript)',
'tog-showtoc'                 => 'Arată cuprinsul (pentru paginile cu mai mult de 3 paragrafe cu titlu)',
'tog-rememberpassword'        => 'Amintește-ți între sesiuni',
'tog-editwidth'               => 'Extinde câmpul de editare pe tot ecranul',
'tog-watchcreations'          => 'Adaugă paginile pe care le creez la lista mea de urmărire',
'tog-watchdefault'            => 'Adaugă paginile pe care le modific la lista mea de urmărire',
'tog-watchmoves'              => 'Adaugă paginile pe care le mut la lista mea de urmărire',
'tog-watchdeletion'           => 'Adaugă paginile pe care le șterg în lista mea de urmărire',
'tog-minordefault'            => 'Marchează din oficiu toate modificările ca fiind minore',
'tog-previewontop'            => 'Arată previzualizarea deasupra căsuței de modificare',
'tog-previewonfirst'          => 'Arată previzualizarea la prima modificare',
'tog-nocache'                 => 'Dezactivează cache-ul paginilor',
'tog-enotifwatchlistpages'    => 'Trimite-mi un email la modificările paginilor',
'tog-enotifusertalkpages'     => 'Trimite-mi un email când pagina mea de discuții este modificată',
'tog-enotifminoredits'        => 'Trimite-mi un email de asemenea pentru modificările minore ale paginilor',
'tog-enotifrevealaddr'        => 'Descoperă-mi adresa email în mesajele de notificare',
'tog-shownumberswatching'     => 'Arată numărul utilizatorilor care urmăresc',
'tog-fancysig'                => 'Tratează semnătura ca wikitext (fără o legătură automată)',
'tog-externaleditor'          => 'Utilizează, în mod implicit, un editor extern (doar pentru experți, necesită setări speciale pe calculatorul dumneavoastră)',
'tog-externaldiff'            => 'Utilizează diferențele externe ca standard',
'tog-showjumplinks'           => 'Activează legăturile de accesibilitate „sari la”',
'tog-uselivepreview'          => 'Folosește previzualizarea în timp real (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avertizează-mă când uit să descriu modificările',
'tog-watchlisthideown'        => 'Ascunde modificările mele la lista mea de urmărire',
'tog-watchlisthidebots'       => 'Ascunde modificările boților la lista mea de urmărire',
'tog-watchlisthideminor'      => 'Ascunde modificările minore la lista mea de urmărire',
'tog-watchlisthideliu'        => 'Ascunde modificările făcute de utilizatori anonimi din lista de pagini urmărite',
'tog-watchlisthideanons'      => 'Ascunde modificările făcute de utilizatori anonimi din lista de pagini urmărite',
'tog-watchlisthidepatrolled'  => 'Ascunde paginile patrulate din lista de pagini urmărite',
'tog-nolangconversion'        => 'Dezactivează conversia variabilelor',
'tog-ccmeonemails'            => 'Trimite-mi o copie când trimit un email altui utilizator',
'tog-diffonly'                => 'Nu arăta conținutul paginii sub dif',
'tog-showhiddencats'          => 'Arată categoriile ascunse',
'tog-noconvertlink'           => 'Dezactivați conversia titlurilor',
'tog-norollbackdiff'          => 'Nu arăta diferența după efectuarea unei reveniri',

'underline-always'  => 'Întotdeauna',
'underline-never'   => 'Niciodată',
'underline-default' => 'Standardul navigatorului',

# Dates
'sunday'        => 'duminică',
'monday'        => 'luni',
'tuesday'       => 'marți',
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
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categorie|Categorii}}',
'category_header'                => 'Pagini din categoria „$1”',
'subcategories'                  => 'Subcategorii',
'category-media-header'          => 'Fișiere media din categoria „$1”',
'category-empty'                 => "''Această categorie nu conține articole sau fișiere media.''",
'hidden-categories'              => '{{PLURAL:$1|categorie ascunsă|categorii ascunse}}',
'hidden-category-category'       => 'Categorii ascunse', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Această categorie conține doar următoarea subcategorie.|Această categorie conține {{PLURAL:$1|următoarea subcategorie|următoarele $1 subcategorii}}, dintr-un total de $2.}}',
'category-subcat-count-limited'  => 'Această categorie conține {{PLURAL:$1|următoarea subcategorie|următoarele $1 subcategorii}}.',
'category-article-count'         => '{{PLURAL:$2|Această categorie conține doar următoarea pagină.|{{PLURAL:$1|Următoarea pagină|Următoarele $1 pagini}} se află în această categorie, dintr-un total de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Următoarea pagină|Următoarele $1 pagini}} se află în categoria curentă.',
'category-file-count'            => '{{PLURAL:$2|Această categorie conține doar următorul fișier.|{{PLURAL:$1|Următorul fișier|Următoarele $1 fișiere}} se află în această categorie, dintr-un total de $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Următorul fișier|Următoarele $1 fișiere}} se află în categoria curentă.',
'listingcontinuesabbrev'         => 'cont.',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "'''Programul Wiki a fost instalat cu succes.'''",
'mainpagedocfooter' => 'Consultă [http://meta.wikimedia.org/wiki/Help:Contents Ghidul utilizatorului (en)] pentru informații despre utilizarea programului wiki.

== Primii pași ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista parametrilor configurabili (en)]
* [http://www.mediawiki.org/wiki/Manual:FAQ Întrebări frecvente despre MediaWiki (en)]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Listă discuții MediaWiki (en)]',

'about'          => 'Despre',
'article'        => 'Articol',
'newwindow'      => '(se deschide într-o fereastră nouă)',
'cancel'         => 'Renunţă',
'qbfind'         => 'Găsește',
'qbbrowse'       => 'Răsfoiește',
'qbedit'         => 'Modifică',
'qbpageoptions'  => 'Opțiuni ale paginii',
'qbpageinfo'     => 'Informații ale paginii',
'qbmyoptions'    => 'Paginile mele',
'qbspecialpages' => 'Pagini speciale',
'moredotdotdot'  => 'Mai mult…',
'mypage'         => 'Pagina mea',
'mytalk'         => 'Discuții',
'anontalk'       => 'Discuția pentru această adresă IP',
'navigation'     => 'Navigare',
'and'            => '&#32;și',

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
'history'           => 'Istoricul paginii',
'history_short'     => 'Istoric',
'updatedmarker'     => 'încărcat de la ultima mea vizită',
'info_short'        => 'Informații',
'printableversion'  => 'Versiune de tipărit',
'permalink'         => 'Legătură permanentă',
'print'             => 'Tipărire',
'edit'              => 'Modifică',
'create'            => 'Creează',
'editthispage'      => 'Modifică pagina',
'create-this-page'  => 'Crează această pagină',
'delete'            => 'Șterge',
'deletethispage'    => 'Șterge pagina',
'undelete_short'    => 'Recuperarea {{PLURAL:$1|unei editări|a $1 editări}}',
'protect'           => 'Protejează',
'protect_change'    => 'schimbă protecția',
'protectthispage'   => 'Protejează pagina',
'unprotect'         => 'Deprotejare',
'unprotectthispage' => 'Deprotejează pagina',
'newpage'           => 'Pagină nouă',
'talkpage'          => 'Discută această pagină',
'talkpagelinktext'  => 'Discuție',
'specialpage'       => 'Pagină Specială',
'personaltools'     => 'Unelte personale',
'postcomment'       => 'Secțiune nouă',
'articlepage'       => 'Vedeți articolul',
'talk'              => 'Discuție',
'views'             => 'Vizualizări',
'toolbox'           => 'Trusa de unelte',
'userpage'          => 'Vizualizați pagina utilizatorului',
'projectpage'       => 'Vezi pagina proiectului',
'imagepage'         => 'Vizualizați pagina fișierului',
'mediawikipage'     => 'Vizualizați pagina mesajului',
'templatepage'      => 'Vizualizați pagina formatului',
'viewhelppage'      => 'Vizualizați pagina de ajutor',
'categorypage'      => 'Vizualizați pagina categoriei',
'viewtalkpage'      => 'Vizualizați discuția',
'otherlanguages'    => 'În alte limbi',
'redirectedfrom'    => '(Redirecționat de la $1)',
'redirectpagesub'   => 'Pagină de redirecționare',
'lastmodifiedat'    => 'Ultima modificare $2, $1.', # $1 date, $2 time
'viewcount'         => 'Pagina a fost vizitată {{PLURAL:$1|odată|de $1 ori}}.',
'protectedpage'     => 'Pagină protejată',
'jumpto'            => 'Salt la:',
'jumptonavigation'  => 'navigare',
'jumptosearch'      => 'căutare',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Despre {{SITENAME}}',
'aboutpage'            => 'Project:Despre',
'copyright'            => 'Conținutul este disponibil sub $1.',
'copyrightpagename'    => 'Drepturi de autor în {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Drepturi de autor',
'currentevents'        => 'Discută la cafenea',
'currentevents-url'    => 'Project:Cafenea',
'disclaimers'          => 'Termeni',
'disclaimerpage'       => 'Project:Termeni',
'edithelp'             => 'Ajutor pentru modificare',
'edithelppage'         => 'Help:Cum să modifici o pagină',
'faq'                  => 'Întrebări frecvente',
'faqpage'              => 'Project:Întrebări frecvente',
'helppage'             => 'Help:Ajutor',
'mainpage'             => 'Pagina principală',
'mainpage-description' => 'Pagina principală',
'policy-url'           => 'Project:Politică',
'portal'               => 'Portalul comunității',
'portal-url'           => 'Project:Portal Comunitate',
'privacy'              => 'Politica de confidențialitate',
'privacypage'          => 'Project:Politica de confidențialitate',

'badaccess'        => 'Eroare permisiune',
'badaccess-group0' => 'Execuția acțiunii cerute nu este permisă.',
'badaccess-groups' => 'Acțiunea cerută este rezervată utilizatorilor din {{PLURAL:$2|grupul|unul din grupurile}}: $1.',

'versionrequired'     => 'Este necesară versiunea $1 MediaWiki',
'versionrequiredtext' => 'Versiunea $1 MediaWiki este necesară pentru a folosi această pagină. Vezi [[Special:Version|versiunea actuală]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Adus de la "$1"',
'youhavenewmessages'      => 'Aveți $1 ($2).',
'newmessageslink'         => 'mesaje noi',
'newmessagesdifflink'     => 'comparație cu versiunea precedentă',
'youhavenewmessagesmulti' => 'Aveți mesaje noi la $1',
'editsection'             => 'modifică',
'editold'                 => 'modifică',
'viewsourceold'           => 'vizualizați sursa',
'editlink'                => 'modifică',
'viewsourcelink'          => 'vezi sursa',
'editsectionhint'         => 'Modifică secțiunea: $1',
'toc'                     => 'Cuprins',
'showtoc'                 => 'arată',
'hidetoc'                 => 'ascunde',
'thisisdeleted'           => 'Vizualizare sau recuperare $1?',
'viewdeleted'             => 'Vizualizează $1?',
'restorelink'             => '{{PLURAL:$1|o modificare ștearsă|$1 modificări șterse}}',
'feedlinks'               => 'Întreținere:',
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
'nstab-image'     => 'Fișier',
'nstab-mediawiki' => 'Mesaj',
'nstab-template'  => 'Format',
'nstab-help'      => 'Ajutor',
'nstab-category'  => 'Categorie',

# Main script and global functions
'nosuchaction'      => 'Această acțiune nu există',
'nosuchactiontext'  => 'Acțiunea specificată în URL este invalidă.
S-ar putea să fi introdus greșit URL-ul, sau să fi urmat o legătură incorectă.
Aceasta s-ar putea să indice și un bug în programul folosit de {{SITENAME}}.',
'nosuchspecialpage' => 'Această pagină specială nu există',
'nospecialpagetext' => 'Ai cerut o [[Special:SpecialPages|pagină specială]] care nu este recunoscută de {{SITENAME}}.',

# General errors
'error'                => 'Eroare',
'databaseerror'        => 'Eroare la baza de date',
'dberrortext'          => 'A apărut o eroare în sintaxa interogării.
Aceasta poate indica o problemă în program.
Ultima interogare încercată a fost:
<blockquote><tt>$1</tt></blockquote>
din cadrul funcției "<tt>$2</tt>".
Baza de date a returnat eroarea "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'A apărut o eroare de sintaxă în interogare.
Ultima interogare încercată a fost:
„$1”
din funcția „$2”.
Baza de date a returnat eroarea „$3: $4”',
'noconnect'            => 'Nu s-a putut conecta baza de date pe $1',
'nodb'                 => 'Nu s-a putut selecta baza de date $1',
'cachederror'          => 'Aceasta este o versiune din cache a paginii cerute și este posibil să nu fie ultima variantă a acesteia.',
'laggedslavemode'      => 'Atenție: S-ar putea ca pagina să nu conțină ultimele actualizări.',
'readonly'             => 'Baza de date este blocată la scriere',
'enterlockreason'      => 'Precizează motivul pentru blocare, incluzând o estimare a termenului de deblocare a bazei de date',
'readonlytext'         => 'Baza de date {{SITENAME}} este momentan blocată la scriere, probabil pentru o operațiune de rutină, după care va fi deblocată și se va reveni la starea normală.

Administratorul care a blocat-o a oferit această explicație: $1',
'missing-article'      => 'Baza de date nu găsește textul unei pagini care ar fi trebuit găsit, numit „$1” $2.

În mod normal faptul este cauzat de urmărirea unei dif neactualizată sau a unei legături din istoric spre o pagină care a fost ștearsă.

Dacă nu acesta e motivul, s-ar putea să fi găsit un bug în program.
Te rog anunță acest aspect unui [[Special:ListUsers/sysop|administrator]], indicându-i adresa URL.',
'missingarticle-rev'   => '(versiunea#: $1)',
'missingarticle-diff'  => '(Dif: $1, $2)',
'readonly_lag'         => 'Baza de date a fost închisă automatic în timp ce serverele secundare ale bazei de date îl urmează pe cel principal.',
'internalerror'        => 'Eroare internă',
'internalerror_info'   => 'Eroare internă: $1',
'filecopyerror'        => 'Fișierul "$1" nu a putut fi copiat la "$2".',
'filerenameerror'      => 'Fișierul "$1" nu a putut fi mutat la "$2".',
'filedeleteerror'      => 'Fișierul "$1" nu a putut fi șters.',
'directorycreateerror' => 'Nu se poate crea directorul "$1".',
'filenotfound'         => 'Fișierul "$1" nu a putut fi găsit.',
'fileexistserror'      => 'Imposibil de scris fișierul "$1": fișierul există deja',
'unexpected'           => 'Valoare neașteptată: "$1"="$2".',
'formerror'            => 'Eroare: datele nu au putut fi trimise',
'badarticleerror'      => 'Această acțiune nu poate fi efectuată pe această pagină.',
'cannotdelete'         => 'Comanda de ştergere nu s-a putut executa! Probabil că ştergerea a fost operată între timp.',
'badtitle'             => 'Titlu incorect',
'badtitletext'         => 'Titlul căutat a fost invalid, gol sau o legătură invalidă inter-linguală sau inter-wiki.

Poate conține unul sau mai multe caractere ce nu poate fi folosit în titluri.',
'perfcached'           => 'Datele următoare au fost păstrate în cache și s-ar putea să nu fie la zi.',
'perfcachedts'         => "Informațiile de mai jos provin din ''cache''; ultima actualizare s-a efectuat la $1.",
'querypage-no-updates' => 'Actualizările acestei pagini sunt momentan dezactivate. Informațiile de aici nu sunt împrospătate.',
'wrong_wfQuery_params' => 'Număr incorect de parametri pentru wfQuery()<br />
Funcția: $1<br />
Interogarea: $2',
'viewsource'           => 'Vezi sursa',
'viewsourcefor'        => 'pentru $1',
'actionthrottled'      => 'Acțiune limitată',
'actionthrottledtext'  => 'Ca o măsură anti-spam, aveți permisiuni limitate în a efectua această acțiune de prea multe ori într-o perioadă scurtă de timp, iar dv. tocmai ați depășit această limită.
Vă rugăm să încercați din nou în câteva minute.',
'protectedpagetext'    => 'Această pagină este protejată împotriva modificărilor.',
'viewsourcetext'       => 'Se poate vizualiza și copia conținutul acestei pagini:',
'protectedinterface'   => 'Această pagină asigură textul interfeței pentru software și este protejată pentru a preveni abuzurile.',
'editinginterface'     => "'''Avertizare''': Editezi o pagină care este folosită pentru a furniza textul interfeței pentru software. Modificările aduse acestei pagini vor afecta aspectul interfeței utilizatorului pentru alți utilizatori. Pentru traduceri, considerați utilizarea [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], proiectul MediaWiki de localizare.",
'sqlhidden'            => '(interogare SQL ascunsă)',
'cascadeprotected'     => 'Această pagină a fost protejată la scriere deoarece este inclusă în {{PLURAL:$1|următoarea pagină|următoarele pagini}}, care {{PLURAL:$1|este protejată|sunt protejate}} în cascadă:
$2',
'namespaceprotected'   => "Nu ai permisiunea de a edita pagini în spațiul de nume '''$1'''.",
'customcssjsprotected' => 'Nu aveți permisiunea să editați această pagină, deoarece conține datele private ale unui alt utilizator.',
'ns-specialprotected'  => 'Paginile din spațiul de nume {{ns:special}} nu pot fi editate.',
'titleprotected'       => "Acest titlu a fos protejat la creare de [[User:$1|$1]].
Motivul invocat este ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configurație greșită: scaner de virus necunoscut: ''$1''",
'virus-scanfailed'     => 'scanare eșuată (cod $1)',
'virus-unknownscanner' => 'antivirus necunoscut:',

# Login and logout pages
'logouttitle'                => 'Sesiune închisă',
'logouttext'                 => 'Sesiunea ta în {{SITENAME}} a fost încheiată. Poți continua să folosești {{SITENAME}} anonim, sau poți să te [[Special:UserLogin|reautentifici]] ca același sau ca alt utilizator.',
'welcomecreation'            => '==Bun venit, $1!==

Contul dumneavoatră a fost creat. Nu uitați să vă personalizați [[Special:Preferences|preferințele]] în {{SITENAME}}.',
'loginpagetitle'             => 'Autentificare utilizator',
'yourname'                   => 'Nume de utilizator:',
'yourpassword'               => 'Parolă:',
'yourpasswordagain'          => 'Repetă parola',
'remembermypassword'         => 'Reține-mi parola între sesiuni',
'yourdomainname'             => 'Domeniul tău',
'externaldberror'            => 'A fost fie o eroare de bază de date pentru o autentificare extenă sau nu aveți permisiunea să actualizați contul extern.',
'login'                      => 'Autentificare',
'nav-login-createaccount'    => 'Creare cont / Autentificare',
'loginprompt'                => 'Trebuie să ai modulele cookie activate pentru a te autentifica la {{SITENAME}}.',
'userlogin'                  => 'Creare cont / Autentificare',
'logout'                     => 'Închide sesiunea',
'userlogout'                 => 'Închide sesiunea',
'notloggedin'                => 'Nu sunteți autentificat',
'nologin'                    => "Nu aveți cont încă? '''$1'''.",
'nologinlink'                => 'Creați-vă un cont de utilizator acum',
'createaccount'              => 'Creare cont',
'gotaccount'                 => "Aveți deja un cont de utilizator? '''$1'''.",
'gotaccountlink'             => 'Autentificați-vă',
'createaccountmail'          => 'după e-mail',
'badretype'                  => 'Parolele pe care le-ai introdus diferă.',
'userexists'                 => 'Numele de utilizator pe care l-ați introdus există deja. Încercați cu un alt nume.',
'youremail'                  => 'E-mail',
'username'                   => 'Nume de utilizator:',
'uid'                        => 'ID utilizator:',
'prefs-memberingroups'       => 'Membru în {{PLURAL:$1|grupul|grupurile}}:',
'yourrealname'               => 'Nume real:',
'yourlanguage'               => 'Limbă:',
'yourvariant'                => 'Varianta:',
'yournick'                   => 'Semnătură:',
'badsig'                     => 'Semnătură brută incorectă; verificați tag-urile HTML.',
'badsiglength'               => 'Semnătura este prea lungă.
Dimensiunea trebuie să fie mai mică de $1 {{PLURAL:$1|caracter|caractere}}.',
'yourgender'                 => 'Gen:',
'gender-unknown'             => 'Nespecificat',
'gender-male'                => 'Bărbat',
'gender-female'              => 'Femeie',
'prefs-help-gender'          => 'Opțional - sexul utilizatorului: folosit pentru adresarea corectă de către software. Această informație va fi publică.',
'email'                      => 'E-mail',
'prefs-help-realname'        => '* Numele dumneavoastră real (opțional): Dacă decideți introducerea numelui real aici, acesta va fi folosit pentru a vă atribui munca.<br />',
'loginerror'                 => 'Eroare de autentificare',
'prefs-help-email'           => '*Adresa de e-mail (opțional): Permite altor utilizatori să vă contacteze prin e-mail via {{SITENAME}}, fără a vă divulga identitatea. De asemenea, permite recuperarea parolei în cazul în care o uitați.',
'prefs-help-email-required'  => 'Adresa de e-mail este necesară.',
'nocookiesnew'               => 'Contul a fost creat, dar dvs. nu sunteți autentificat(ă). {{SITENAME}} folosește cookie-uri pentru a reține utilizatorii autentificați. Browser-ul dvs. are modulele cookie dezactivate (disabled). Vă rugăm să le activați și să vă reautentificați folosind noul nume de utilizator și noua parolă.',
'nocookieslogin'             => '{{SITENAME}} folosește module cookie pentru a autentifica utilizatorii. Browser-ul dvs. are cookie-urile dezactivate. Vă rugăm să le activați și să incercați din nou.',
'noname'                     => 'Numele de utilizator pe care l-ai specificat este invalid.',
'loginsuccesstitle'          => 'Autentificare reușită',
'loginsuccess'               => 'Ați fost autentificat în {{SITENAME}} ca "$1".',
'nosuchuser'                 => 'Nu există nici un utilizator cu numele „$1”.
Numele de utilizatori sunt sensibile la majuscule.
Verifică dacă ai scris corect sau [[Special:UserLogin/signup|creează un nou cont de utilizator]].',
'nosuchusershort'            => 'Nu este nici un utilizator cu numele „<nowiki>$1</nowiki>”. Verificați dacă ați scris corect.',
'nouserspecified'            => 'Trebuie să specificați un nume de utilizator.',
'wrongpassword'              => 'Parola pe care ai introdus-o este greșită. Te rugăm să încerci din nou.',
'wrongpasswordempty'         => 'Spațiul pentru introducerea parolei nu a fost completat. Vă rugăm să încercați din nou.',
'passwordtooshort'           => 'Parola dumneavoastră este invalidă sau prea scurtă.
Trebuie să aibă cel puţin {{PLURAL:$1|1 caracter|$1 caractere}} şi să fie diferită de numele de utilizator.',
'mailmypassword'             => 'Trimite-mi parola pe e-mail!',
'passwordremindertitle'      => 'Noua parolă temporară la {{SITENAME}}',
'passwordremindertext'       => 'Cineva (probabil dumneavoastră, de la adresa $1)
a cerut să vi se trimită o nouă parolă pentru {{SITENAME}} ($4).
O parolă temporară pentru utilizatorul "$2" este acum "$3".
Parola temporară va expirs {{PLURAL:$5|într-o zi|în $5 zile}}.

Dacă această cerere a fost efectuată de altcineva sau dacă v-ați amintit 
parola și nu doriți să o schimbați, ignorați acest mesaj și continuați 
să folosiți vechea parolă.',
'noemail'                    => 'Nu este nici o adresă de e-mail înregistrată pentru utilizatorul „$1”.',
'passwordsent'               => 'O nouă parolă a fost trimisă la adresa de e-mail a utilizatorului "$1". Te rugăm să te autentifici pe {{SITENAME}} după ce o primești.',
'blocked-mailpassword'       => 'Această adresă IP este blocată la editare, și deci nu este permisă utilizarea funcției de recuperare a parolei pentru a preveni abuzul.',
'eauthentsent'               => 'Un email de confirmare a fost trimis adresei nominalizate. Înainte de a fi trimis orice alt email acestui cont, trebuie să urmați intrucțiunile din email, pentru a confirma că acest cont este într-adevăr al dvs.',
'throttled-mailpassword'     => 'O parolă a fost deja trimisă în {{PLURAL:$1|ultima oră|ultimele $1 ore}}. Pentru a preveni abuzul, se poate trimite doar o parolă la {{PLURAL:$1|o oră|$1 ore}}.',
'mailerror'                  => 'Eroare la trimitere e-mail: $1',
'acct_creation_throttle_hit' => 'De la această adresă IP, vizitatorii sitului au creat {{PLURAL:$1|1 cont|$1 conturi}} de utilizator în ultimele zile, acest număr de noi conturi fiind maximul admis în această perioadă de timp.
Prin urmare, vizitatorii care folosesc același IP nu mai pot crea alte conturi pentru moment.',
'emailauthenticated'         => 'Adresa de e-mail a fost autentificată pe $2, la $3.',
'emailnotauthenticated'      => 'Adresa de email <strong>nu este autentificată încă</strong>. Nici un email nu va fi trimis pentru nici una din întrebuințările următoare.',
'noemailprefs'               => 'Nu a fost specificată o adresă email, următoarele nu vor funcționa.',
'emailconfirmlink'           => 'Confirmați adresa dvs. de email',
'invalidemailaddress'        => 'Adresa de email nu a putut fi acceptată pentru că pare a avea un format invalid. Vă rugăm să reintroduceți o adresă bine formatată sau să goliți acel câmp.',
'accountcreated'             => 'Contul a fost creat.',
'accountcreatedtext'         => 'Contul utilizatorului pentru $1 a fost creat.',
'createaccount-title'        => 'Creare de cont la {{SITENAME}}',
'createaccount-text'         => 'Cineva a creat un cont asociat adresei dumneavoastră de e-mail pe {{SITENAME}} ($4) numit „$2” și având parola „$3”.
Este de dorit să vă autentificați și să schimbați parola cât mai repede.

Ignorați acest mesaj dacă crearea contului s-a produs în urma unei greșeli.',
'login-throttled'            => 'Ați avut prea multe încercări de a vă autentifica.
Vă rugăm să așteptați până să mai încercați.',
'loginlanguagelabel'         => 'Limba: $1',

# Password reset dialog
'resetpass'                 => 'Modifică parola',
'resetpass_announce'        => 'Sunteți autentificat cu un cod temporar trimis pe mail. Pentru a termina acțiunea de autentificare, trebuie să setați o parolă nouă aici:',
'resetpass_text'            => '<!-- Adaugă text aici -->',
'resetpass_header'          => 'Modifică parola',
'oldpassword'               => 'Parola veche',
'newpassword'               => 'Parola nouă',
'retypenew'                 => 'Repetă parola nouă',
'resetpass_submit'          => 'Setează parola și autentifică',
'resetpass_success'         => 'Parola a fost schimbată cu succes! Autentificare în curs...',
'resetpass_bad_temporary'   => 'Parola temporară nu este validă. Este posibil să vă fi schimbat deja parola cu succes sau să fi cerut o nouă parolă temporară.',
'resetpass_forbidden'       => 'Parolele nu pot fi schimbate.',
'resetpass-no-info'         => 'Trebuie să fiți autentificat pentru a accesa această pagină direct.',
'resetpass-submit-loggedin' => 'Modifică parola',
'resetpass-wrong-oldpass'   => 'Parolă curentă sau temporară incorectă. 
Este posibil să fi reușit deja schimbarea parolei sau să fi cerut o parolă temporară nouă.',
'resetpass-temp-password'   => 'Parolă temporară:',
'resetpass-log'             => 'Jurnalul resetărilor parolei',
'resetpass-logtext'         => 'Mai jos este jurnalul utilizatorilor care au avut parola resetată de un administrator.',
'resetpass-logentry'        => 'a fost schimbată parola pentru $1',
'resetpass-comment'         => 'Motivul pentru resetarea parolei:',

# Edit page toolbar
'bold_sample'     => 'Text aldin',
'bold_tip'        => 'Text aldin',
'italic_sample'   => 'Text cursiv',
'italic_tip'      => 'Text cursiv',
'link_sample'     => 'Titlul legăturii',
'link_tip'        => 'Legătură internă',
'extlink_sample'  => 'http://www.example.com titlul legăturii',
'extlink_tip'     => 'Legătură externă (nu uitați prefixul http://)',
'headline_sample' => 'Text de titlu',
'headline_tip'    => 'Titlu de nivel 2',
'math_sample'     => 'Introduceți formula aici',
'math_tip'        => 'Formulă matematică (LaTeX)',
'nowiki_sample'   => 'Introduceți text neformatat aici',
'nowiki_tip'      => 'Ignoră formatarea wiki',
'image_sample'    => 'Exemplu.jpg',
'image_tip'       => 'Fișier inserat',
'media_sample'    => 'Exemplu.ogg',
'media_tip'       => 'Legătură la fișier',
'sig_tip'         => 'Semnătura dvs. datată',
'hr_tip'          => 'Linie orizontală (folosiți-o cumpătat)',

# Edit pages
'summary'                          => 'Rezumat:',
'subject'                          => 'Subiect / titlu:',
'minoredit'                        => 'Aceasta este o editare minoră',
'watchthis'                        => 'Urmărește această pagină',
'savearticle'                      => 'Salvează pagina',
'preview'                          => 'Previzualizare',
'showpreview'                      => 'Previzualizare',
'showlivepreview'                  => 'Previzualizare live',
'showdiff'                         => 'Arată diferențele',
'anoneditwarning'                  => "'''Atenție:''' Nu v-ați autentificat. Adresa IP vă va fi înregistrată în istoricul acestei pagini.",
'missingsummary'                   => "'''Atenție:''' Nu ați completat caseta „descriere modificări”. Dacă apăsați din nou butonul „salvează pagina” modificările vor fi salvate fără descriere.",
'missingcommenttext'               => 'Vă rugăm să introduceți un comentariu.',
'missingcommentheader'             => "'''Atenție:''' Nu ați furnizat un titlu/subiect pentru acest comentariu. Dacă dați click pe \"Salvați din nou\", modificarea va fi salvată fără titlu.",
'summary-preview'                  => 'Previzualizare descriere:',
'subject-preview'                  => 'Previzualizare subiect/titlu:',
'blockedtitle'                     => 'Utilizatorul este blocat',
'blockedtext'                      => "'''Adresa IP sau contul dumneavoastră de utilizator a fost blocat.'''

Blocarea a fost făcută de $1.
Motivul blocării este ''$2''.

* Începutul blocării: $8
* Sfârșitul blocării: $6
* Utilizatorul vizat: $7

Îl puteți contacta pe $1 sau pe alt [[{{MediaWiki:Grouppage-sysop}}|administrator]] pentru a discuta blocarea.
Nu puteți folosi opțiunea 'trimite un e-mai utilizatorului' decât dacă o adresă de e-mail validă este specificată în [[Special:Preferences|preferințele contului]] și nu sunteți blocat la folosirea ei.
Adresa dumneavoastră IP curentă este $3, iar ID-ul blocării este $5. Vă rugăm să includeți oricare sau ambele informații în orice interogări.",
'autoblockedtext'                  => 'Această adresă IP a fost blocată automat deoarece a fost folosită de către un alt utilizator, care a fost blocat de $1.
Motivul blocării este:

:\'\'$2\'\'

* Începutul blocării: $8
* Sfârșitul blocării: $6
* Intervalul blocării: $7

Puteți contacta pe $1 sau pe unul dintre ceilalți [[{{MediaWiki:Grouppage-sysop}}|administratori]] pentru a discuta blocarea.

Nu veți putea folosi opțiunea de "trimite e-mail" decât dacă aveți înregistrată o adresă de e-mail validă la [[Special:Preferences|preferințe]] și nu sunteți blocat la folosirea ei.

Aveți adresa IP $3, iar identificatorul dumneavoastră de blocare este $5.
Vă rugăm să includeți detaliile de mai sus în orice interogări pe care le faceți.',
'blockednoreason'                  => 'nici un motiv oferit',
'blockedoriginalsource'            => "Sursa pentru '''$1''' apare mai jos:",
'blockededitsource'                => "Textul '''modificărilor dumneavoastră''' la  '''$1''' este redat mai jos:",
'whitelistedittitle'               => 'Este necesară autentificarea pentru a putea modifica',
'whitelistedittext'                => 'Trebuie să $1 pentru a edita articole.',
'confirmedittitle'                 => 'Pentru a edita e necesară confirmarea adresei de e-mail',
'confirmedittext'                  => 'Trebuie să vă confirmați adresa de e-mail înainte de a edita pagini. Vă rugăm să vă setați și să vă validați adresa de e-mail cu ajutorul [[Special:Preferences|preferințelor utilizatorului]].',
'nosuchsectiontitle'               => 'Secțiunea nu poate fi găsită',
'nosuchsectiontext'                => 'Ați încercat să modificați o secțiune care nu există.
Aceasta fie a fost mutată, fie a fost ștearsă în timp ce vizualizați pagina.',
'loginreqtitle'                    => 'Necesită autentificare',
'loginreqlink'                     => 'autentifici',
'loginreqpagetext'                 => 'Trebuie să te $1 pentru a vizualiza alte pagini.',
'accmailtitle'                     => 'Parola a fost trimisă.',
'accmailtext'                      => "Parola generată automat pentru [[User talk:$1|$1]] a fost trimisă la $2.

Parola pentru acest nou cont poate fi schimbată după autentificare din ''[[Special:ChangePassword|schimbare parolă]]''",
'newarticle'                       => '(Nou)',
'newarticletext'                   => 'Ați încercat să ajungeți la o pagină care nu există. Pentru a o crea, începeți să scrieți în caseta de mai jos (vedeți [[{{MediaWiki:Helppage}}|pagina de ajutor]] pentru mai multe informații). Dacă ați ajuns aici din greșeală, întoarceți-vă folosind controalele navigatorului dumneavoastră.',
'anontalkpagetext'                 => "---- ''Aceasta este pagina de discuții pentru un utilizator care nu și-a creat un cont încă, sau care nu s-a autentificat.
De aceea trebuie să folosim adresă IP pentru a identifica această persoană.
O adresă IP poate fi folosită în comun de mai mulți utilizatori.
Dacă sunteți un astfel de utilizator și credeți că vă sunt adresate mesaje irelevante, vă rugăm să [[Special:UserLogin/signup|vă creați un cont]] sau să [[Special:UserLogin|vă autentificați]] pentru a evita confuzii cu alți utilizatori anonimi în viitor.''",
'noarticletext'                    => 'În acest moment nu este niciun text în această pagină.
Puteți [[Special:Search/{{PAGENAME}}|căuta acest titlu]] în alte pagini,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} căuta înregistrări în jurnale], sau [{{fullurl:{{FULLPAGENAME}}|action=edit}} crea această pagină]</span>.',
'userpage-userdoesnotexist'        => 'Contul de utilizator "$1" nu este înregistrat. Verificați dacă doriți să creați/modificați această pagină.',
'clearyourcache'                   => "'''Notă:''' După salvare, trebuie să treceți peste cache-ul browser-ului pentru a vedea modificările. '''Mozilla/Safari/Konqueror:''' țineți apăsat ''Shift'' în timp ce apăsați ''Reload'' (sau apăsați ''Ctrl-Shift-R''), '''IE:''' apăsați ''Ctrl-F5'', '''Opera:''' apăsați ''F5''.",
'usercssjsyoucanpreview'           => "'''Sfat:''' Folosește butonul 'Arată previzualizare' pentru a testa noul tău css/js înainte de a salva.",
'usercsspreview'                   => "'''Reține că urmărești doar o previzualizare a css-ului tău de utilizator, acesta nu este încă salvat!'''",
'userjspreview'                    => "'''Reține că urmărești doar un test/o previzualizare a javascript-ului tău de utilizator, acesta nu este încă salvat!'''",
'userinvalidcssjstitle'            => '<b>Avertizare:</b> Nu există skin "$1". Amintește-ți că paginile .css and .js specifice utilizatorilor au titluri care încep cu literă mică, de exemplu {{ns:user}}:Foo/monobook.css în comparație cu {{ns:user}}:Foo/Monobook.css.',
'updated'                          => '(Actualizat)',
'note'                             => "'''Notă:'''",
'previewnote'                      => "Aceasta este doar o previzualizare! Pentru a salva pagina în forma actuală, descrieți succint modificările efectuate și apăsați butonul '''Salvează pagina'''.",
'previewconflict'                  => 'Această pre-vizualizare reflectă textul din caseta de sus, respectiv felul în care va arăta articolul dacă alegeți să-l salvați acum.',
'session_fail_preview'             => "'''Ne pare rău! Nu am putut procesa modificarea dumneavoastră din cauza pierderii datelor sesiunii.
Vă rugăm să încercați din nou.
Dacă tot nu funcționează, încercați să [[Special:UserLogout|închideți sesiunea]] și să vă autentificați din nou.'''",
'session_fail_preview_html'        => "'''Ne pare rău! Modificările dvs. nu au putut fi procesate din cauza pierderii datelor sesiunii.''' 

''Deoarece {{SITENAME}} are activat HTML brut, previzualizarea este ascunsă ca măsură de precauție împotriva atacurilor JavaScript.''

'''Dacă această încercare de modificare este legitimă, vă rugăm să încercați din nou. Dacă nu funcționează nici în acest fel, [[Special:UserLogout|închideți sesiunea]] și încearcați să vă autentificați din nou.'''",
'token_suffix_mismatch'            => "'''Modificarea ta a fost refuzată pentru că clientul tău a deformat caracterele de punctuatie în modificarea semnului.
Modificarea a fost respinsă pentru a preveni deformarea textului paginii.
Acest fapt se poate întâmpla atunci când folosești un serviciu proxy anonim.'''",
'editing'                          => 'modificare $1',
'editingsection'                   => 'modificare $1 (secțiune)',
'editingcomment'                   => 'Modificare $1 (secțiune nouă)',
'editconflict'                     => 'Conflict de modificare: $1',
'explainconflict'                  => "Altcineva a modificat această pagină de când ai început să o editezi.
Caseta de text de sus conține pagina așa cum este ea acum (după editarea celeilalte persoane).
Pagina cu modificările tale (așa cum ai încercat să o salvezi) se află în caseta de jos.
Va trebui să editezi manual caseta de sus pentru a reflecta modificările pe care tocmai le-ai făcut în cea de jos.
'''Numai''' textul din caseta de sus va fi salvat atunci când vei apăsa pe \"Salvează pagina\".",
'yourtext'                         => 'Textul tău',
'storedversion'                    => 'Versiunea curentă',
'nonunicodebrowser'                => "'''ATENŢIE: Browser-ul dumneavoastră nu este compilant unicode, vă rugăm să îl schimbați înainte de a începe modificarea unui articol.'''",
'editingold'                       => "'''ATENŢIE! Modifici o variantă mai veche a acestei pagini! Orice modificări care s-au făcut de la această versiune și până la cea curentă se vor pierde!'''",
'yourdiff'                         => 'Diferențe',
'copyrightwarning'                 => "Reține că toate contribuțiile la {{SITENAME}} sunt distribuite sub licența $2 (vezi $1 pentru detalii).
Dacă nu doriți ca ceea ce scrieți să fie modificat fără milă și redistribuit în voie, atunci nu trimiteți materialele respective aici.<br />
De asemenea, ne asigurați că ceea ce ați scris a fost compoziție proprie sau copie dintr-o resursă publică sau liberă.
'''NU INTRODUCEŢI MATERIALE CU DREPTURI DE AUTOR FĂRĂ PERMISIUNE!'''",
'copyrightwarning2'                => "Rețineți că toate contribuțiile la {{SITENAME}} pot fi modificate, alterate sau șterse de alți contribuitori.
Dacă nu doriți ca ceea ce scrieți să fie modificat fără milă și redistribuit în voie, atunci nu trimiteți materialele respective aici.<br />
De asemenea, ne asigurați că ceea ce ați scris a fost compoziție proprie sau copie dintr-o resursă publică sau liberă (vedeți $1 pentru detalii).
'''NU INTRODUCEŢI MATERIALE CU DREPTURI DE AUTOR FĂRĂ PERMISIUNE!'''",
'longpagewarning'                  => "'''ATENŢIE! Conținutul acestei pagini are $1 kB; unele browsere au probleme la modificarea paginilor în jur de 32 kB sau mai mari. Te rugăm să iei în considerare posibilitatea de a împărți pagina în mai multe secțiuni.'''",
'longpageerror'                    => "'''EROARE: Textul pe care vrei să-l salvezi are $1 kilobytes,
ceea ce înseamnă mai mult decât maximum de $2 kilobytes. Salvarea nu este posibilă.'''",
'readonlywarning'                  => "'''ATENŢIE: Baza de date a fost blocată pentru întreținere, deci nu veți putea salva modificările în acest moment. Puteți copia textul într-un fișier text local pentru a-l salva când va fi posibil.'''

Administratorul care a efectuat blocarea a oferit următoarea explicație: $1",
'protectedpagewarning'             => "'''ATENŢIE! Această pagină a fost protejată la scriere şi numai utilizatorii cu privilegii de administrator o pot modifica.'''",
'semiprotectedpagewarning'         => "'''Atenţie:''' Această pagină poate fi modificată numai de utilizatorii înregistraţi.",
'cascadeprotectedwarning'          => "'''Atenție:''' Această pagină a fost blocată astfel încât numai administratorii o pot modifica, deoarece este inclusă în {{PLURAL:$1|următoarea pagină protejată|următoarele pagini protejate}} în cascadă:",
'titleprotectedwarning'            => "'''ATENŢIE:  Această pagină a fost protejată, doar anumiţi [[Special:ListGroupRights|utilizatori]] o pot crea.'''",
'templatesused'                    => 'Formate folosite în această pagină:',
'templatesusedpreview'             => 'Formate utilizate în această previzualizare:',
'templatesusedsection'             => 'Formate utilizate în această secţiune:',
'template-protected'               => '(protejat)',
'template-semiprotected'           => '(semiprotejat)',
'hiddencategories'                 => 'Această pagină este membrul {{PLURAL:$1|unei categorii ascunse|a $1 categorii ascunse}}:',
'edittools'                        => '<!-- Acest text va apărea după caseta de editare și formularele de trimitere fișier. -->',
'nocreatetitle'                    => 'Creare de pagini limitată',
'nocreatetext'                     => '{{SITENAME}} a restricționat abilitatea de a crea pagini noi.
Puteți edita o pagină deja existentă sau puteți să vă [[Special:UserLogin|autentificați/creați]] un cont de utilizator.',
'nocreate-loggedin'                => 'Nu ai permisiunea să creezi pagini noi.',
'permissionserrors'                => 'Erori de permisiune',
'permissionserrorstext'            => 'Nu aveți permisiune pentru a face acest lucru, din următoarele {{PLURAL:$1|motiv|motive}}:',
'permissionserrorstext-withaction' => 'Nu aveți permisiunea să $2, din {{PLURAL:$1|următorul motivul|următoarele motive}}:',
'recreate-deleted-warn'            => "'''Atenţie: Recreaţi o pagină care a fost ştearsă anterior.'''


Pentru a verifica dacă recrearea paginii este într-adevăr oportună, iată aici jurnalul ştergerilor:",
'deleted-notice'                   => 'Această pagină a fost ştearsă.
Pentru detalii puteţi vedea jurnalul ştergerilor de mai jos.',
'deletelog-fulllog'                => 'Vizualizează întreg jurnalul',
'edit-hook-aborted'                => 'Modificarea a fost abandonată din cauza unui hook.
Fără nicio explicație.',
'edit-gone-missing'                => 'Pagina nu s-a putut actualiza.
Se pare că a fost ștearsă.',
'edit-conflict'                    => 'Conflict de modificare.',
'edit-no-change'                   => 'Modificarea dvs. a fost ignorată deoarece nu s-a efectuat nicio schimbare.',
'edit-already-exists'              => 'Pagina nouă nu a putut fi creată.
Ea există deja.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Atenție: Această pagină conține prea multe apelări costisitoare ale funcțiilor parser.

Ar trebui să existe mai puțin de $2 {{PLURAL:$2|apelare|apelări}}, acolo există {{PLURAL:$1|$1 apelare|$1 apelări}}.',
'expensive-parserfunction-category'       => 'Pagini cu prea multe apelări costisitoare de funcții parser',
'post-expand-template-inclusion-warning'  => 'Atenție: Formatele incluse sunt prea mari.
Unele formate nu vor fi incluse.',
'post-expand-template-inclusion-category' => 'Paginile în care este inclus formatul are o dimensiune prea mare',
'post-expand-template-argument-warning'   => 'Atenție: Această pagină conține cel puțin un argument al unui format care are o mărime prea mare atunci când este expandat.
Acsete argumente au fost omise.',
'post-expand-template-argument-category'  => 'Pagini care conțin formate cu argumente omise',
'parser-template-loop-warning'            => 'Buclă de formate detectată: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limită de adâncime a recursiei depășită ($1)',

# "Undo" feature
'undo-success' => 'Modificarea poate fi anulată. Verificați diferența de dedesupt și apoi salvați pentru a termina anularea modificării.',
'undo-failure' => 'Modificarea nu poate fi reversibilă datorită conflictului de modificări intermediare.',
'undo-norev'   => 'Modificarea nu poate fi reversibilă pentru că nu există sau pentru că a fost ștearsă.',
'undo-summary' => 'Anularea modificării $1 făcute de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discuție]])',

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
'revision-info'          => 'Revizia pentru $1; $2', # Additionally available: $3: revision id
'previousrevision'       => '←Versiunea anterioară',
'nextrevision'           => 'Versiunea următoare →',
'currentrevisionlink'    => 'afișează versiunea curentă',
'cur'                    => 'actuală',
'next'                   => 'următoarea',
'last'                   => 'prec',
'page_first'             => 'prim',
'page_last'              => 'ultim',
'histlegend'             => 'Legendă: (actuală) = diferențe față de versiunea curentă,
(prec) = diferențe față de versiunea precedentă, M = modificare minoră',
'history-fieldset-title' => 'Răsfoire istoric',
'deletedrev'             => '[șters]',
'histfirst'              => 'Primele',
'histlast'               => 'Ultimele',
'historysize'            => '({{PLURAL:$1|1 octet|$1 octeți}})',
'historyempty'           => '(gol)',

# Revision feed
'history-feed-title'          => 'Revizia istoricului',
'history-feed-description'    => 'Revizia istoricului pentru această pagină de pe wiki',
'history-feed-item-nocomment' => '$1 la $2', # user at time
'history-feed-empty'          => 'Pagina solicitată nu există.
E posibil să fi fost ștearsă sau redenumită.
Încearcă să [[Special:Search|cauți]] pe wiki pentru pagini noi semnificative.',

# Revision deletion
'rev-deleted-comment'            => '(comentariu șters)',
'rev-deleted-user'               => '(nume de utilizator șters)',
'rev-deleted-event'              => '(intrare ștearsă)',
'rev-deleted-text-permission'    => "Revizia acestei pagini a fost '''ştearsă'''.
Mai multe detalii în [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} jurnalul de ştergeri].",
'rev-deleted-text-view'          => "Revizia acestei pagini a fost '''ştearsă'''.
Ca administrator la acest site poţi să o vezi; s-ar putea să găseşti mai multe detalii la [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} jurnalul de ştergeri].",
'rev-deleted-no-diff'            => "Nu poţi vedea acestă diferenţă deoarece una dintre revizii a fost '''ştearsă'''.
Pot exista mai multe detalii [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].",
'rev-deleted-unhide-diff'        => "Una din reviziile acestui istoric a fost '''ştearsă'''.
Pot exista detalii în [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} jurnalul ştergerilor].
Ca administrator poţi  [$1 vedea acest istoric] dacă doreşti.",
'rev-delundel'                   => 'arată/ascunde',
'revisiondelete'                 => 'Șterge/recuperează revizii',
'revdelete-nooldid-title'        => 'Versiune invalidă',
'revdelete-nooldid-text'         => 'Nu ai specificat revizie pentru a efectua această
funcție, revizia specificată nu există, sau ești pe cale să ascunzi revizia curentă.',
'revdelete-nologtype-title'      => 'Niciun tip de jurnal specificat',
'revdelete-nologtype-text'       => 'Nu ai specificat niciun tip de jurnal pentru a putea efectua această acțiune.',
'revdelete-toomanytargets-title' => 'Prea multe ținte',
'revdelete-toomanytargets-text'  => 'Ați specificat prea multe tipuri de obiective pentru a putea efectua această acțiune.',
'revdelete-nologid-title'        => 'Intrare în jurnal invalidă',
'revdelete-nologid-text'         => 'Ori nu nu ai specificat o țintă pentru jurnal pentru a efectua această funcție sau intrarea specificată nu există.',
'revdelete-selected'             => "'''{{PLURAL:$2|Revizia aleasă|Reviziile alese}} pentru [[:$1]]:'''",
'logdelete-selected'             => "'''{{PLURAL:$1|Revizia aleasă|Reviziile alese}}:'''",
'revdelete-text'                 => "'''Reviziile şterse vor apărea în istoricul paginii, dar conţinutul lor nu va fi accesibil publicului.'''

Alţi administratori {{SITENAME}} vor putea accesa conţinutul ascuns şi îl pot recupera prin aceeaşi interfaţă, dacă nu este impusă altă restricţie de către operatorii sitului. Confirmă că doreşti să finalizezi acţiunea, că înţelegi consecinţele şi că faci acest lucru în concordanţă cu [[{{MediaWiki:Policy-url}}|politica sitului]].",
'revdelete-suppress-text'        => "Suprimarea trebuie folosită '''doar''' în următoarele cazuri:
* Informații personale inadecvate
*: ''adrese și numere de telefon personale, CNP, numere de securitate socială, etc.''",
'revdelete-legend'               => 'Setează restricții pentru vizualizare',
'revdelete-hide-text'            => 'Ascunde textul reviziei',
'revdelete-hide-name'            => 'Ascunde acțiunea și destinația',
'revdelete-hide-comment'         => 'Ascunde descrierea modificării',
'revdelete-hide-user'            => 'Ascunde numele de utilizator/IP-ul editorului',
'revdelete-hide-restricted'      => 'Ascunde informațiile față de administratori și față de alți utilizatori',
'revdelete-suppress'             => 'Ascunde de asemenea reviziile față de administratori',
'revdelete-hide-image'           => 'Ascunde conținutul fișierului',
'revdelete-unsuppress'           => 'Elimină restricțiile în reviziile restaurate',
'revdelete-log'                  => 'Motiv:',
'revdelete-submit'               => 'Aplică reviziilor selectate',
'revdelete-logentry'             => 'vizibilitatea reviziei pentru [[$1]] a fost modificată',
'logdelete-logentry'             => 'a fost modificată vizibilitatea evenimentului [[$1]]',
'revdelete-success'              => "'''Vizibilitatea reviziilor a fost schimbată cu succes.'''",
'logdelete-success'              => "'''Jurnalul vizibilității a fost configurat cu succes.'''",
'revdel-restore'                 => 'Schimbă vizibilitatea',
'pagehist'                       => 'Istoricul paginii',
'deletedhist'                    => 'Istoric șters',
'revdelete-content'              => 'conținut',
'revdelete-summary'              => 'sumarul modificărilor',
'revdelete-uname'                => 'nume de utilizator',
'revdelete-restricted'           => 'restricții aplicate administratorilor',
'revdelete-unrestricted'         => 'restricții eliminate pentru administratori',
'revdelete-hid'                  => 'ascuns $1',
'revdelete-unhid'                => 'arată $1',
'revdelete-log-message'          => '$1 pentru $2 {{PLURAL:$2|versiune|versiuni}}',
'logdelete-log-message'          => '$1 pentru $2 {{PLURAL:$2|eveniment|evenimente}}',

# Suppression log
'suppressionlog'     => 'Înlătură jurnalul',
'suppressionlogtext' => 'Mai jos este afișată o listă a ștergerilor și a blocărilor care implică conținutul ascuns de administratori.
Vezi [[Special:IPBlockList|adresele IP blocate]] pentru o listă a interzicerilor operaționale sau a blocărilor.',

# History merging
'mergehistory'                     => 'Unește istoricul paginilor',
'mergehistory-header'              => 'Această pagină permite să combini reviziile din istoric dintr-o pagină sursă într-o pagină nouă.
Asigură-te că această schimbare va menține continuitatea istoricului paginii.',
'mergehistory-box'                 => 'Combină reviziile a două pagini:',
'mergehistory-from'                => 'Pagina sursă:',
'mergehistory-into'                => 'Pagina destinație:',
'mergehistory-list'                => 'Istoricul la care se aplică combinarea',
'mergehistory-merge'               => 'Ulmătoarele revizii ale [[:$1]] pot fi combinate în [[:$2]].
Folosește butonul pentru a combina reviziile create la și după momentul specificat.
Folosirea linkurilor de navigare va reseta această coloană.',
'mergehistory-go'                  => 'Vezi modificările care pot fi combinate',
'mergehistory-submit'              => 'Unește reviziile',
'mergehistory-empty'               => 'Reviziile nu pot fi combinate.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revizie|revizii}} ale [[:$1]] au fost unite cu succes în [[:$2]].',
'mergehistory-fail'                => 'Nu se poate executa combinarea istoricului, te rog verifică parametrii pagină și timp.',
'mergehistory-no-source'           => 'Pagina sursă $1 nu există.',
'mergehistory-no-destination'      => 'Pagina de destinație $1 nu există.',
'mergehistory-invalid-source'      => 'Pagina sursă trebuie să aibă un titlu valid.',
'mergehistory-invalid-destination' => 'Pagina de destinație trebuie să aibă un titlu valid.',
'mergehistory-autocomment'         => 'Combinat [[:$1]] în [[:$2]]',
'mergehistory-comment'             => 'Combinat [[:$1]] în [[:$2]]: $3',
'mergehistory-same-destination'    => 'Paginile sursă și destinație nu pot fi identice',
'mergehistory-reason'              => 'Motiv:',

# Merge log
'mergelog'           => 'Jurnal unificări',
'pagemerge-logentry' => 'combină [[$1]] cu [[$2]] (revizii până la $3)',
'revertmerge'        => 'Anulează îmbinarea',
'mergelogpagetext'   => 'Mai jos este o listă a celor mai recente combinări ale istoricului unei pagini cu al alteia.',

# Diffs
'history-title'           => 'Istoria reviziilor pentru "$1"',
'difference'              => '(Diferența dintre versiuni)',
'lineno'                  => 'Linia $1:',
'compareselectedversions' => 'Compară versiunile marcate',
'visualcomparison'        => 'Comparare vizuală',
'wikicodecomparison'      => 'Comparare a wikitext-ului',
'editundo'                => 'anulează',
'diff-multi'              => '({{PLURAL:$1|O revizie intermediară neafișată|$1 revizii intermediare neafișate}})',
'diff-movedto'            => 'mutat la $1',
'diff-styleadded'         => 'stilul $1 a fost adăugat',
'diff-added'              => '$1 a fost adăugat',
'diff-changedto'          => 'schimbat în $1',
'diff-movedoutof'         => 'mutat din $1',
'diff-styleremoved'       => 'stilul $1 a fost șters',
'diff-removed'            => '$1 a fost șters',
'diff-changedfrom'        => 'schimbat din $1',
'diff-src'                => 'sursă',
'diff-withdestination'    => 'cu destinația $1',
'diff-with'               => '&#32;cu $1 $2',
'diff-with-additional'    => '$1 $2',
'diff-with-final'         => '&#32;și $1 $2',
'diff-width'              => 'lățime',
'diff-height'             => 'înălțime',
'diff-p'                  => "un '''paragraf'''",
'diff-blockquote'         => "un '''citat'''",
'diff-h1'                 => "un '''antet (nivel 1)'''",
'diff-h2'                 => "un '''antet (nivel 2)'''",
'diff-h3'                 => "un '''antet (nivel 3)'''",
'diff-h4'                 => "un '''antet (nivel 4)'''",
'diff-h5'                 => "un '''antet (nivel 5)'''",
'diff-pre'                => "un '''bloc preformatat'''",
'diff-div'                => "o '''diviziune'''",
'diff-ul'                 => "o '''listă neordonată'''",
'diff-ol'                 => "o '''listă ordonată'''",
'diff-li'                 => "un '''element de listă'''",
'diff-table'              => "un '''tabel'''",
'diff-tbody'              => "un '''conținut al tabelului'''",
'diff-tr'                 => "un '''rând'''",
'diff-td'                 => "o '''celulă'''",
'diff-th'                 => "un '''antet'''",
'diff-br'                 => "o '''rupere de rând'''",
'diff-hr'                 => "o '''bară orizontală'''",
'diff-code'               => "un '''bloc de cod de calculator'''",
'diff-dl'                 => "o '''listă de definiții'''",
'diff-dt'                 => "un '''termen de definiție'''",
'diff-dd'                 => "o '''definiție'''",
'diff-input'              => "o '''introducere'''",
'diff-form'               => "un '''formular'''",
'diff-img'                => "o '''imagine'''",
'diff-span'               => "o '''parte'''",
'diff-a'                  => "o '''legătură'''",
'diff-i'                  => "'''cursiv'''",
'diff-b'                  => "'''aldin'''",
'diff-strong'             => "'''îngroșat'''",
'diff-em'                 => "'''accentuat'''",
'diff-font'               => "'''font'''",
'diff-big'                => "'''mare'''",
'diff-del'                => "'''șters'''",
'diff-tt'                 => "'''lățime fixă'''",
'diff-sub'                => "'''indice inferior'''",
'diff-sup'                => "'''indice superior'''",
'diff-strike'             => "'''tăiat'''",

# Search results
'searchresults'                    => 'Rezultatele căutării',
'searchresults-title'              => 'Caută rezultate pentru „$1”',
'searchresulttext'                 => 'Pentru mai multe detalii despre căutarea în {{SITENAME}}, vezi [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ai căutat \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|toate paginile care încep cu "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toate paginile care se leagă de "$1"]])',
'searchsubtitleinvalid'            => 'Pentru căutarea "$1"',
'noexactmatch'                     => "'''Pagina cu titlul \"\$1\" nu există.''' Poți [[:\$1|crea această pagină]].",
'noexactmatch-nocreate'            => "'''Nu există nici o pagină cu titlul „$1”.'''",
'toomanymatches'                   => 'Prea multe rezultate au fost întoarse, încercă o căutare diferită',
'titlematches'                     => 'Rezultate în titlurile articolelor',
'notitlematches'                   => 'Nici un rezultat în titlurile articolelor',
'textmatches'                      => 'Rezultate în textele articolelor',
'notextmatches'                    => 'Nici un rezultat în textele articolelor',
'prevn'                            => 'anterioarele $1',
'nextn'                            => 'următoarele $1',
'prevn-title'                      => '{{PLURAL:$1|anteriorul|anterioarele}} $1 {{PLURAL:$1|rezultat|rezultate}}',
'nextn-title'                      => '{{PLURAL:$1|următorul|următoarele}} $1 {{PLURAL:$1|rezultat|rezultate}}',
'shown-title'                      => 'Arată $1 {{PLURAL:$1|rezultat|rezultate}} pe pagină',
'viewprevnext'                     => 'Vedeți ($1) ($2) ($3).',
'searchmenu-legend'                => 'Opțiuni căutare',
'searchmenu-exists'                => "* Pagina '''[[$1]]'''",
'searchmenu-new'                   => "'''Creează pagina \"[[:\$1]]\" pe acest wiki!'''",
'searchhelp-url'                   => 'Help:Ajutor',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Răsfoiește paginile cu acest prefix]]',
'searchprofile-articles'           => 'Pagini',
'searchprofile-articles-and-proj'  => 'Articole & pagini',
'searchprofile-project'            => 'Proiect',
'searchprofile-images'             => 'Fişiere',
'searchprofile-everything'         => 'Totul',
'searchprofile-advanced'           => 'Avansat',
'searchprofile-articles-tooltip'   => 'Caută în $1',
'searchprofile-project-tooltip'    => 'Caută în $1',
'searchprofile-images-tooltip'     => 'Caută fișiere',
'searchprofile-everything-tooltip' => 'Caută în tot conținutul (incluzând paginile de discuție)',
'searchprofile-advanced-tooltip'   => 'Caută în namespace-uri personalizate',
'prefs-search-nsdefault'           => 'Caută utilizând standardul:',
'prefs-search-nscustom'            => 'Caută în namespace-uri personalizate:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 cuvânt|$2 cuvinte}})',
'search-result-score'              => 'Relevanță: $1%',
'search-redirect'                  => '(redirecționare către $1)',
'search-section'                   => '(secțiunea $1)',
'search-suggest'                   => 'Te-ai referit la: $1',
'search-interwiki-caption'         => 'Proiecte înrudite',
'search-interwiki-default'         => '$1 rezultate:',
'search-interwiki-more'            => '(mai mult)',
'search-mwsuggest-enabled'         => 'cu sugestii',
'search-mwsuggest-disabled'        => 'fără sugestii',
'search-relatedarticle'            => 'Relaționat',
'mwsuggest-disable'                => 'Dezactivează sugestiile AJAX',
'searchrelated'                    => 'relaționat',
'searchall'                        => 'toate',
'showingresults'                   => "Mai jos {{PLURAL:$1|apare '''1''' rezultat|apar '''$1''' rezultate}} începând cu #<b>$2</b>.",
'showingresultsnum'                => "Mai jos {{PLURAL:$3|apare '''1''' rezultat|apar '''$3''' rezultate}} cu #<b>$2</b>.",
'showingresultstotal'              => "Arată {{PLURAL:$4|rezultatul '''$1''' din '''$3'''|rezultatele '''$1 - $2''' din '''$3'''}}",
'nonefound'                        => "'''Notă''': Numai unele spații de nume sunt căutate implicit.
Încercați să puneți ca și prefix al căutării ''all:'' pentru a căuta în tot conținutul (incluzând și paginile de discuții, formate, etc), sau folosiți spațiul de nume dorit ca și prefix.",
'search-nonefound'                 => 'Nu sunt rezultate conforme interogării.',
'powersearch'                      => 'Căutare avansată',
'powersearch-legend'               => 'Căutare avansată',
'powersearch-ns'                   => 'Căutare în spațiile de nume:',
'powersearch-redir'                => 'Afișează redirectările',
'powersearch-field'                => 'Caută după',
'search-external'                  => 'Căutare externă',
'searchdisabled'                   => '<p>Ne pare rău! Căutarea după text a fost dezactivată temporar, din motive de performanță. Între timp puteți folosi căutarea prin Google mai jos, însă aceasta poate să dea rezultate învechite.</p>',

# Preferences page
'preferences'               => 'Preferințe',
'mypreferences'             => 'Preferințe',
'prefs-edits'               => 'Număr de modificări:',
'prefsnologin'              => 'Neautentificat',
'prefsnologintext'          => 'Trebuie să fiţi <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} autentificat]</span> pentru a vă putea salva preferinţele.',
'prefsreset'                => 'Preferințele au fost resetate.',
'qbsettings'                => 'Setări pentru bara rapidă',
'qbsettings-none'           => 'Fără',
'qbsettings-fixedleft'      => 'Fixă, în stânga',
'qbsettings-fixedright'     => 'Fixă, în dreapta',
'qbsettings-floatingleft'   => 'Liberă',
'qbsettings-floatingright'  => 'Plutire la dreapta',
'changepassword'            => 'Schimbă parola',
'skin'                      => 'Aspect',
'skin-preview'              => 'Previzualizare',
'math'                      => 'Aspect formule',
'dateformat'                => 'Formatul datelor',
'datedefault'               => 'Nici o preferință',
'datetime'                  => 'Data şi ora',
'math_failure'              => 'Nu s-a putut interpreta',
'math_unknown_error'        => 'eroare necunoscută',
'math_unknown_function'     => 'funcție necunoscută',
'math_lexing_error'         => 'eroare lexicală',
'math_syntax_error'         => 'eroare de sintaxă',
'math_image_error'          => 'Conversiune în PNG eșuată',
'math_bad_tmpdir'           => 'Nu se poate crea sau nu se poate scrie în directorul temporar pentru formule matematice',
'math_bad_output'           => 'Nu se poate crea sau nu se poate scrie în directorul de ieșire pentru formule matematice',
'math_notexvc'              => 'Lipsește executabilul texvc; vezi math/README pentru configurare.',
'prefs-personal'            => 'Date de utilizator',
'prefs-rc'                  => 'Schimbări recente',
'prefs-watchlist'           => 'Listă de urmărire',
'prefs-watchlist-days'      => 'Numărul de zile care apar în lista paginilor urmărite:',
'prefs-watchlist-days-max'  => '(maxim 7 zile)',
'prefs-watchlist-edits'     => 'Numărul de editări care apar în lista extinsă a paginilor urmărite:',
'prefs-watchlist-edits-max' => '(număr maxim: 1000)',
'prefs-misc'                => 'Parametri diverși',
'prefs-resetpass'           => 'Modifică parola',
'saveprefs'                 => 'Salvează preferințele',
'resetprefs'                => 'Resetează preferințele',
'restoreprefs'              => 'Restaurează toate valorile implicite',
'textboxsize'               => 'Dimensiunile casetei de text',
'prefs-edit-boxsize'        => 'Mărimea ferestrei de modificare.',
'rows'                      => 'Rânduri:',
'columns'                   => 'Coloane',
'searchresultshead'         => 'Parametri căutare',
'resultsperpage'            => 'Numărul de rezultate per pagină',
'contextlines'              => 'Numărul de linii per rezultat',
'contextchars'              => 'Numărul de caractere per linie',
'stub-threshold'            => 'Valoarea minimă pentru un <a href="#" class="stub">ciot</a> (octeți):',
'recentchangesdays'         => 'Numărul de zile afișate în schimbări recente:',
'recentchangesdays-max'     => '(maxim {{PLURAL:$1|o zi|$1 zile}})',
'recentchangescount'        => 'Numărul de modificări arătate în schimbările recente, istoricul paginii, şi în jurnale, implicit:',
'savedprefs'                => 'Preferințele dumneavoastră au fost salvate.',
'timezonelegend'            => 'Fus orar:',
'timezonetext'              => '¹Introduceți numărul de ore diferență între ora Dv. locală și ora serverului (UTC, timp universal).',
'localtime'                 => 'Ora locală:',
'timezoneselect'            => 'Fus orar:',
'timezoneuseserverdefault'  => 'Folosește ora server-ului',
'timezoneuseoffset'         => 'Altul (specifică diferența)',
'timezoneoffset'            => 'Diferența¹:',
'servertime'                => 'Ora serverului:',
'guesstimezone'             => 'Încearcă determinarea automată a diferenței',
'timezoneregion-africa'     => 'Africa',
'timezoneregion-america'    => 'America',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-arctic'     => 'Oceanul Arctic',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Oceanul Atlantic',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Oceanul Indian',
'timezoneregion-pacific'    => 'Oceanul Pacific',
'allowemail'                => 'Activează email de la alți utilizatori',
'prefs-searchoptions'       => 'Opțiuni de căutare',
'prefs-namespaces'          => 'Spații de nume',
'defaultns'                 => 'Caută în aceste secţiuni implicit:',
'default'                   => 'standard',
'files'                     => 'Fişiere',
'prefs-custom-css'          => 'CSS personalizat',
'prefs-custom-js'           => 'JS personalizat',

# User rights
'userrights'                  => 'Administrarea permisiunilor de utilizator', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Administrare grupuri de utilizatori',
'userrights-user-editname'    => 'Introdu un nume de utilizator:',
'editusergroup'               => 'Modificare grup de utilizatori',
'editinguser'                 => "modificare permisiuni de utilizator ale utilizatorului '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Modifică grupul de utilizatori',
'saveusergroups'              => 'Salvează grupul de utilizatori',
'userrights-groupsmember'     => 'Membru al:',
'userrights-groups-help'      => 'Puteți schimba grupul căruia îi aparține utilizatorul:
*Căsuța bifată înseamnă că utilizatorul este în acel grup.
*Căsuța nebifată înseamnă că utilizatorul nu este în acel grup.
*Steluța (*) indică faptul că utilizatorul nu poate fi eliminat din grup odată adăugat, sau invers',
'userrights-reason'           => 'Motiv:',
'userrights-no-interwiki'     => 'Nu aveți permisiunea de a modifica permisiunile utilizatorilor pe alte wiki.',
'userrights-nodatabase'       => 'Baza de date $1 nu există sau nu este locală.',
'userrights-nologin'          => 'Trebuie să te [[Special:UserLogin|autentifici]] cu un cont de administrator pentru a atribui permisiuni utilizatorilor.',
'userrights-notallowed'       => 'Contul dumneavoastră nu are permisiunea de a acorda permisiuni utilizatorilor.',
'userrights-changeable-col'   => 'Grupuri pe care le puteți schimba',
'userrights-unchangeable-col' => 'Grupuri pe care nu le puteți schimba',

# Groups
'group'               => 'Grup:',
'group-user'          => 'Utilizatori',
'group-autoconfirmed' => 'Utilizatori autoconfirmați',
'group-bot'           => 'Roboți',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birocrați',
'group-suppress'      => 'Oversights',
'group-all'           => '(toți)',

'group-user-member'          => 'Utilizator',
'group-autoconfirmed-member' => 'Utilizator autoconfirmat',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Birocrat',
'group-suppress-member'      => 'oversight',

'grouppage-user'          => '{{ns:project}}:Utilizatori',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilizator autoconfirmați',
'grouppage-bot'           => '{{ns:project}}:Boți',
'grouppage-sysop'         => '{{ns:project}}:Administratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Birocrați',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Citește paginile',
'right-edit'                  => 'Modifică paginile',
'right-createpage'            => 'Creează pagini (altele decât pagini de discuție)',
'right-createtalk'            => 'Creează pagini de discuție',
'right-createaccount'         => 'Creează conturi noi',
'right-minoredit'             => 'Marchează modificările minore',
'right-move'                  => 'Mută paginile',
'right-move-subpages'         => 'Mută paginile cu tot cu subpagini',
'right-move-rootuserpages'    => 'Redenumește pagina principală a unui utilizator',
'right-movefile'              => 'Mută fișierele',
'right-suppressredirect'      => 'Nu crea o redirecționare de la vechiul nume atunci când muți o pagină',
'right-upload'                => 'Încarcă fișiere',
'right-reupload'              => 'Suprascrie un fișier existent',
'right-reupload-own'          => 'Suprascrie un fișier existent propriu',
'right-reupload-shared'       => 'Rescrie fișierele disponibile în depozitul partajat',
'right-upload_by_url'         => 'Încarcă un fișier de la o adresă URL',
'right-purge'                 => 'Curăță memoria cache pentru o pagină fără confirmare',
'right-autoconfirmed'         => 'Modifică paginile semi-protejate',
'right-bot'                   => 'Tratare ca proces automat',
'right-nominornewtalk'        => 'Nu activa mesajul "Aveți un mesaj nou" la modificarea minoră a paginii de discuții a utilizatorului',
'right-apihighlimits'         => 'Folosește o limită mai mare pentru rezultatele cererilor API',
'right-writeapi'              => 'Utilizează API la scriere',
'right-delete'                => 'Şterge pagini',
'right-bigdelete'             => 'Şterge pagini cu istoric lung',
'right-deleterevision'        => 'Şterge și recuperează versiuni specifice ale paginilor',
'right-deletedhistory'        => 'Vezi intrările șterse din istoric, fără textul asociat',
'right-browsearchive'         => 'Caută pagini șterse',
'right-undelete'              => 'Recuperează pagini',
'right-suppressrevision'      => 'Examinează și restaurează reviziile ascunse față de administratori',
'right-suppressionlog'        => 'Vizualizează jurnale private',
'right-block'                 => 'Blocare utilizatori la modificare',
'right-blockemail'            => 'Blocare utilizatori la trimitere email',
'right-hideuser'              => 'Blochează un nume de utilizator, ascunzându-l de public',
'right-ipblock-exempt'        => 'Nu au fost afectați de blocarea făcută IP-ului.',
'right-proxyunbannable'       => 'Treci peste blocarea automată a proxy-urilor',
'right-protect'               => 'Schimbă nivelurile de protejare și modifică pagini protejate',
'right-editprotected'         => 'Modificare pagini protejate (fără protejare în cascadă)',
'right-editinterface'         => 'Modificare interfața cu utilizatorul',
'right-editusercssjs'         => 'Modifică fișierele CSS și JS ale altor utilizatori',
'right-rollback'              => 'Revocarea rapidă a editărilor ultimului utilizator care a modificat o pagină particulară',
'right-markbotedits'          => 'Marchează revenirea ca modificare efectuată de robot',
'right-noratelimit'           => 'Neafectat de limitele raportului',
'right-import'                => 'Importă pagini de la alte wiki',
'right-importupload'          => 'Importă pagini dintr-o încărcare de fișier',
'right-patrol'                => 'Marchează modificările altora ca patrulate',
'right-autopatrol'            => 'Modificările proprii marcate ca patrulate',
'right-patrolmarks'           => 'Vizualizează pagini recent patrulate',
'right-unwatchedpages'        => 'Vizualizezaă listă de pagini neurmărite',
'right-trackback'             => 'Trimite un urmăritor',
'right-mergehistory'          => 'Unește istoricele paginilor',
'right-userrights'            => 'Modifică toate permisiunile de utilizator',
'right-userrights-interwiki'  => 'Modifică permisiunile de utilizator pentru utilizatorii de pe alte wiki',
'right-siteadmin'             => 'Blochează și deblochează baza de date',
'right-reset-passwords'       => 'Resetarea parolelor altor utilizatori',
'right-override-export-depth' => 'Exportă inclusiv paginile legate până la o adâncime de 5',

# User rights log
'rightslog'      => 'Jurnal permisiuni de utilizator',
'rightslogtext'  => 'Acest jurnal cuprinde modificările permisiunilor utilizatorilor.',
'rightslogentry' => 'a schimbat pentru $1 apartenența la un grup de la $2 la $3',
'rightsnone'     => '(niciunul)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'citiți această pagină',
'action-edit'                 => 'modificați această pagină',
'action-createpage'           => 'creați pagini',
'action-createtalk'           => 'creați pagini de discuție',
'action-createaccount'        => 'creați acest cont de utilizator',
'action-minoredit'            => 'marcați această modificare ca minoră',
'action-move'                 => 'mutați această pagină',
'action-move-subpages'        => 'mutați această pagină și subpaginile sale',
'action-move-rootuserpages'   => 'redenumiți pagina principală a unui utilizator',
'action-movefile'             => 'mutați acest fișier',
'action-upload'               => 'încărcați acest fișier',
'action-reupload'             => 'suprascrieți fișierul existent',
'action-reupload-shared'      => 'rescrieți acest fișier în depozitul partajat',
'action-upload_by_url'        => 'încărcați acest fișier de la o adresă URL',
'action-writeapi'             => 'utilizați scrierea prin API',
'action-delete'               => 'ștergeți această pagină',
'action-deleterevision'       => 'ștergeți această revizie',
'action-deletedhistory'       => 'vizualizați istoricul șters al aceste pagini',
'action-browsearchive'        => 'căutați pagini șterse',
'action-undelete'             => 'recuperați această pagină',
'action-suppressrevision'     => 'revizuiți și să restaurați această revizie ascunsă',
'action-suppressionlog'       => 'vizualizați acest jurnal privat',
'action-block'                => 'blocați permisiunea de modificare a acestui utilizator',
'action-protect'              => 'modificați nivelurile de protecție pentru această pagină',
'action-import'               => 'importați această pagină din alt wiki',
'action-importupload'         => 'importați această pagină prin încărcarea unui fișier',
'action-patrol'               => 'marcați modificările celorlalți ca patrulate',
'action-autopatrol'           => 'marcați modificarea drept patrulată',
'action-unwatchedpages'       => 'vizualizați lista de pagini neurmărite',
'action-trackback'            => 'aplicați un trackback',
'action-mergehistory'         => 'uniți istoricul acestei pagini',
'action-userrights'           => 'modificați toate permisiunile utilizatorilor',
'action-userrights-interwiki' => 'modificați permisiunile utilizatorilor de pe alte wiki',
'action-siteadmin'            => 'blocați sau deblocați baza de date',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modificare|modificări}}',
'recentchanges'                     => 'Schimbări recente',
'recentchanges-legend'              => 'Opțiuni schimbări recente',
'recentchangestext'                 => 'Schimbări recente ... (Log)',
'recentchanges-feed-description'    => 'Urmărește cele mai recente schimbări folosind acest flux.',
'rcnote'                            => "Mai jos se află {{PLURAL:$|ultima modificare|ultimele '''$1''' modificări}} din {{PLURAL:$2|ultima zi|ultimele '''$2''' zile}}, începând cu $5, $4.",
'rcnotefrom'                        => 'Dedesubt sunt modificările de la <b>$2</b> (maxim <b>$1</b> de modificări sunt afişate - schimbă numărul maxim de linii alegând altă valoare mai jos).',
'rclistfrom'                        => 'Arată modificările începând de la $1',
'rcshowhideminor'                   => '$1 modificările minore',
'rcshowhidebots'                    => '$1 roboți',
'rcshowhideliu'                     => '$1 utilizatori autentificați',
'rcshowhideanons'                   => '$1 utilizatori anonimi',
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
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilizator|utilizatori}} care urmăresc]',
'rc_categories'                     => 'Limitează la categoriile (separate prin "|")',
'rc_categories_any'                 => 'Oricare',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ secțiune nouă',
'rc-enhanced-expand'                => 'Arată detalii (necesită JavaScript)',
'rc-enhanced-hide'                  => 'Ascunde detaliile',

# Recent changes linked
'recentchangeslinked'          => 'Modificări corelate',
'recentchangeslinked-title'    => 'Modificări legate de „$1”',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Nici o schimbare la paginile legate în perioada dată.',
'recentchangeslinked-summary'  => "Aceasta este o listă a schimbărilor efectuate recent asupra paginilor cu legături de la o anumită pagină (sau asupra membrilor unei anumite categorii).
Paginile pe care le [[Special:Watchlist|urmăriți]] apar în '''aldine'''.",
'recentchangeslinked-page'     => 'Numele paginii:',
'recentchangeslinked-to'       => 'Afișează schimbările în paginile care se leagă de pagina dată',

# Upload
'upload'                      => 'Încarcă fișier',
'uploadbtn'                   => 'Încarcă fișier',
'reupload'                    => 'Re-trimite',
'reuploaddesc'                => 'Revocare încărcare și întoarcere la formularul de trimitere.',
'uploadnologin'               => 'Nu sunteți autentificat',
'uploadnologintext'           => 'Trebuie să fiți [[Special:UserLogin|autentificat]] pentru a putea trimite fișiere.',
'upload_directory_missing'    => 'Directorul în care sunt încărcate fișierele ($1) lipsește și nu poate fi creat de serverul web.',
'upload_directory_read_only'  => 'Directorul de încărcare ($1) nu poate fi scris de server.',
'uploaderror'                 => 'Eroare la trimitere fișier',
'uploadtext'                  => "Folosește formularul de mai jos pentru a trimite fișiere. 
Pentru a vizualiza sau căuta imagini deja trimise, mergi la [[Special:FileList|lista de imagini]], încărcările și ștergerile sunt de asemenea înregistrate în [[Special:Log/upload|jurnalul fișierelor trimise]], ștergerile în [[Special:Log/delete|jurnalul fișierelor șterse]].

Pentru a include un fișier de sunet într-un articol, folosești o legătură de forma:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fișier.jpg]]</nowiki></tt>''' pentru a include versiunea integrală a unui fișier
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fișier.png|200px|thumb|left|alt text]]</nowiki></tt>''' pentru a introduce o imagine de 200px într-un chenar cu textul 'alt text' în partea stângă ca descriere
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fișier.ogg]]</nowiki></tt>''' pentru a lega direct fișierul fără a-l afișa",
'upload-permitted'            => 'Tipuri de fișiere permise: $1.',
'upload-preferred'            => 'Tipuri de fișiere preferate: $1.',
'upload-prohibited'           => 'Tipuri de fișiere interzise: $1.',
'uploadlog'                   => 'jurnal fișiere trimise',
'uploadlogpage'               => 'Jurnal fișiere trimise',
'uploadlogpagetext'           => 'Mai jos este afișată lista ultimelor fișiere trimise.
Vezi [[Special:NewFiles|galeria fișierelor noi]] pentru o mai bună vizualizare.',
'filename'                    => 'Nume fișier',
'filedesc'                    => 'Descriere fișier',
'fileuploadsummary'           => 'Rezumat:',
'filereuploadsummary'         => 'Modificări ale fișierului:',
'filestatus'                  => 'Statutul drepturilor de autor:',
'filesource'                  => 'Sursă:',
'uploadedfiles'               => 'Fișiere trimise',
'ignorewarning'               => 'Ignoră avertismentul și salvează fișierul',
'ignorewarnings'              => 'Ignoră orice avertismente',
'minlength1'                  => 'Numele fișierelor trebuie să fie cel puțin o literă.',
'illegalfilename'             => 'Numele fișierului "$1" conține caractere care nu sunt permise în titlurile paginilor. Vă rugăm redenumiți fișierul și încercați să îl încărcați din nou.',
'badfilename'                 => 'Numele fișierului a fost schimbat în „$1”.',
'filetype-badmime'            => 'Nu este permisă încărcarea de fișiere de tipul MIME "$1".',
'filetype-bad-ie-mime'        => 'Nu puteți încărca acest fișier deoarece Internet Explorer îl va detecta ca și "$1", care este nepermis și poate fi un format periculos.',
'filetype-unwanted-type'      => "'''\".\$1\"''' este un tip de fișier nedorit.
{{PLURAL:\$3|Tipul de fișier preferat este|Tipurile de fișiere preferate sunt}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' este un tip de fișier nepermis. 
{{PLURAL:\$3|Tip de fișier permis:|Tipuri de fișiere permise:}} \$2.",
'filetype-missing'            => 'Fișierul nu are extensie (precum ".jpg").',
'large-file'                  => 'Este recomandat ca fișierele să nu fie mai mari de $1; acest fișier are $2.',
'largefileserver'             => 'Fișierul este mai mare decât este configurat serverul să permită.',
'emptyfile'                   => 'Fișierul pe care l-ați încărcat pare a fi gol. Aceasta poate fi datorită unei greșeli în numele fișierului. Verificați dacă într-adevăr doriți să încărcați acest fișier.',
'fileexists'                  => "Un fișier cu același nume există deja, vă rugăm verificați '''<tt>[[:$1]]</tt>''' dacă nu sunteți sigur dacă doriți să îl modificați.
[[$1|thumb]]",
'filepageexists'              => "Pagina cu descrierea fișierului a fost deja creată la '''<tt>[[:$1]]</tt>''', dar niciun fișier cu acest nume nu există în acest moment.
Sumarul pe care l-ai introdus nu va apărea în pagina cu descriere.
Pentru ca sumarul tău să apară, va trebui să îl adaugi manual.
[[$1|miniatură]]",
'fileexists-extension'        => "Un fișier cu un nume similar există: [[$2|thumb]]
* Numele fișierului de încărcat: '''<tt>[[:$1]]</tt>'''
* Numele fișierului existent: '''<tt>[[:$2]]</tt>'''
Te rog alege alt nume.",
'fileexists-thumb'            => "<center>'''Imagine existentă'''</center>",
'fileexists-thumbnail-yes'    => "Fișierul pare a fi o imagine cu o rezoluție scăzută ''(thumbnail)''. [[$1|thumb]]
Verifică fișierul'''<tt>[[:$1]]</tt>'''.
Dacă fișierul verificat este identic cu imaginea originală nu este necesară încărcarea altui thumbnail.",
'file-thumbnail-no'           => "Numele fișierului începe cu '''<tt>$1</tt>'''.
Se pare că este o imagine cu dimensiune redusă''(thumbnail)''.
Dacă ai această imagine la rezoluție mare încarc-o pe aceasta, altfel schimbă numele fișierului.",
'fileexists-forbidden'        => 'Un fișier cu acest nume există deja și nu poate fi rescris.
Mergeți înapoi și încărcați acest fișier sub un nume nou. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fișier cu acest nume există deja în magazia de imagini comune; mergeți înapoi și încărcați fișierul sub un nou nume. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Acest fișier este dublura {{PLURAL:$1|fișierului|fișierelor}}:',
'file-deleted-duplicate'      => 'Un fișier identic cu acesta ([[$1]]) a fost șters anterior. Verificați istoricul ștergerilor fișierului înainte de a-l reîncărca.',
'successfulupload'            => 'Fișierul a fost trimis',
'uploadwarning'               => 'Avertizare la trimiterea fișierului',
'savefile'                    => 'Salvează fișierul',
'uploadedimage'               => 'a trimis [[$1]]',
'overwroteimage'              => 'încărcat o versiune nouă a fișierului "[[$1]]"',
'uploaddisabled'              => 'Ne pare rău, trimiterea de imagini este dezactivată.',
'uploaddisabledtext'          => 'Încărcările de fișiere sunt dezactivate.',
'php-uploaddisabledtext'      => 'Încărcarea de fișiere este dezactivată în PHP.
Vă rugăm să verificați setările din file_uploads.',
'uploadscripted'              => 'Fișierul conține HTML sau cod script care poate fi interpretat în mod eronat de un browser.',
'uploadcorrupt'               => 'Fișierul este corupt sau are o extensie incorectă. Verifică fișierul și trimite-l din nou.',
'uploadvirus'                 => 'Fișierul conține un virus! Detalii: $1',
'sourcefilename'              => 'Nume fișier sursă:',
'destfilename'                => 'Numele fișierului de destinație:',
'upload-maxfilesize'          => 'Mărimea maximă a unui fișier: $1',
'watchthisupload'             => 'Urmărește acest fișier',
'filewasdeleted'              => 'Un fișier cu acest nume a fost anterior încărcat și apoi șters. Ar trebui să verificați $1 înainte să îl încărcați din nou.',
'upload-wasdeleted'           => "'''Atenție: Sunteți pe cale să încarcați un fișier șters anterior.'''

Vă rugăm să aveți în vedere dacă este utilă reîncărcarea acestuia.
Jurnalul pentru această ștergere este disponibil aici:",
'filename-bad-prefix'         => "Numele fișierului pe care îl încărcați începe cu '''\"\$1\"''', care este un nume non-descriptiv alocat automat în general de camerele digitale.
Vă rugăm, alegeți un nume mai descriptiv pentru fișerul dumneavoastră.",

'upload-proto-error'      => 'Protocol incorect',
'upload-proto-error-text' => 'Importul de la distanță necesită adrese URL care încep cu <code>http://</code> sau <code>ftp://</code>.',
'upload-file-error'       => 'Eroare internă',
'upload-file-error-text'  => 'A apărut o eroare internă la crearea unui fișier temporar pe server.
Vă rugăm să contactați un [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error'       => 'Eroare de încărcare necunoscută',
'upload-misc-error-text'  => 'A apărut o eroare necunoscută în timpul încărcării.
Vă rugăm să verificați dacă adresa URL este validă și accesibilă și încercați din nou.
Dacă problema persistă, contactați un [[Special:ListUsers/sysop|administrator]].',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nu pot găsi adresa URL',
'upload-curl-error6-text'  => 'Adresa URL introdusă nu a putut fi atinsă.
Vă rugăm, verificați că adresa URL este corectă și că situl este funcțional.',
'upload-curl-error28'      => 'Încărcarea a expirat',
'upload-curl-error28-text' => 'Sitelui îi ia prea mult timp pentru a răspunde.
Te rog verifică dacă situl este activ, așteaptă puțin și încearcă apoi.
Poate dorești să încerci la o oră mai puțin ocupată.',

'license'            => 'Licențiere:',
'nolicense'          => 'Nici una selectată',
'license-nopreview'  => '(Previzualizare indisponibilă)',
'upload_source_url'  => ' (un URL valid, accesibil public)',
'upload_source_file' => ' (un fișier de pe computerul tău)',

# Special:ListFiles
'listfiles-summary'     => 'Această pagină specială arată toate fișierele încărcate.
În mod normal ultimul fișier încărcat este așezat în capul listei.
O apăsare pe antetul coloanei schimbă sortarea.',
'listfiles_search_for'  => 'Caută imagine după nume:',
'imgfile'               => 'fișier',
'listfiles'             => 'Lista imaginilor',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nume',
'listfiles_user'        => 'Utilizator',
'listfiles_size'        => 'Mărime (octeți)',
'listfiles_description' => 'Descriere',
'listfiles_count'       => 'Versiuni',

# File description page
'filehist'                  => 'Istoricul fișierului',
'filehist-help'             => "Apăsați pe '''Data și ora''' pentru a vedea versiunea trimisă atunci.",
'filehist-deleteall'        => 'șterge tot',
'filehist-deleteone'        => 'șterge',
'filehist-revert'           => 'revenire',
'filehist-current'          => 'actuală',
'filehist-datetime'         => 'Data și ora',
'filehist-thumb'            => 'Miniatură',
'filehist-thumbtext'        => 'Miniatură pentru versiunea din $1',
'filehist-nothumb'          => 'Nicio miniatură',
'filehist-user'             => 'Utilizator',
'filehist-dimensions'       => 'Dimensiuni',
'filehist-filesize'         => 'Mărimea fișierului',
'filehist-comment'          => 'Comentariu',
'imagelinks'                => 'Legături',
'linkstoimage'              => '{{PLURAL:$1|Următoarea pagină trimite spre|Următoarele $1 pagini trimit spre}} această imagine:',
'linkstoimage-more'         => 'Mai mult de $1 {{PLURAL:$1|pagină este legată|pagini sunt legate}} de acest fișier.
Următoarea listă arată {{PLURAL:$1|prima legătură|primele $1 legături}} către acest fișier.
O [[Special:WhatLinksHere/$2|listă completă]] este disponibilă.',
'nolinkstoimage'            => 'Nici o pagină nu utilizează această imagine.',
'morelinkstoimage'          => 'Vedeți [[Special:WhatLinksHere/$1|mai multe legături]] către acest fișier.',
'redirectstofile'           => 'The following {{PLURAL:$1|file redirects|$1 files redirect}} to this file:
{{PLURAL:$1|Fișierul următor|Următoarele $1 fișiere}} redirectează la acest fișier:',
'duplicatesoffile'          => '{{PLURAL:$1|Fișierul următor este duplicat|Următoarele $1 fișiere sunt duplicate}} ale acestui fișier ([[Special:FileDuplicateSearch/$2|mai multe detalii]]):',
'sharedupload'              => 'Acest fișier provine de la $1 și poate fi folosit și de alte proiecte.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Vă rugăm citiți [$1 pagina de descriere a fișierului] pentru alte informații.',
'shareduploadwiki-desc'     => 'Descrierea din pagina $1 este disponibilă mai jos.',
'shareduploadwiki-linktext' => 'pagina descriptivă a fișierului',
'noimage'                   => 'Nu există nici un fișier cu acest nume, puteți să îl $1.',
'noimage-linktext'          => 'trimiteți',
'uploadnewversion-linktext' => 'Încarcă o versiune nouă a acestui fișier',
'shared-repo-from'          => 'de la $1', # $1 is the repository name
'shared-repo'               => 'un depozit partajat', # used when shared-repo-NAME does not exist

# File reversion
'filerevert'                => 'Revenire $1',
'filerevert-legend'         => 'Revenirea la o versiune anterioară',
'filerevert-intro'          => "Pentru a readuce fișierul '''[[Media:$1|$1]]''' la versiunea din [$4 $2 $3] apasă butonul de mai jos.",
'filerevert-comment'        => 'Motiv:',
'filerevert-defaultcomment' => 'Revenire la versiunea din $2, $1',
'filerevert-submit'         => 'Revenire',
'filerevert-success'        => "'''[[Media:$1|$1]]''' a fost readus [la versiunea $4 din $3, $2].",
'filerevert-badversion'     => 'Nu există o versiune mai veche a fișierului care să corespundă cu data introdusă.',

# File deletion
'filedelete'                  => 'Șterge $1',
'filedelete-legend'           => 'Șterge fișierul',
'filedelete-intro'            => "Sunteți pe cale să ștergeți fișierul '''[[Media:$1|$1]]''' cu tot istoricul acestuia.",
'filedelete-intro-old'        => "Ştergi versiunea fișierului '''[[Media:$1|$1]]''' din [$4 $3, $2].",
'filedelete-comment'          => 'Motiv:',
'filedelete-submit'           => 'Șterge',
'filedelete-success'          => "'''$1''' a fost șters.",
'filedelete-success-old'      => "Versiunea fișierului '''[[Media:$1|$1]]''' din $2 $3 a fost ștearsă.",
'filedelete-nofile'           => "'''$1''' nu există.",
'filedelete-nofile-old'       => "Nu există nicio versiune arhivată a '''$1''' cu atributele specificate.",
'filedelete-otherreason'      => 'Motiv diferit/adițional:',
'filedelete-reason-otherlist' => 'Alt motiv',
'filedelete-reason-dropdown'  => '*Motive uzuale
** Încălcare drepturi de autor
** Fișier duplicat',
'filedelete-edit-reasonlist'  => 'Modifică motivele ștergerii',

# MIME search
'mimesearch'         => 'Căutare MIME',
'mimesearch-summary' => 'This page enables the filtering of files for its MIME-type.
Input: contenttype/subtype, e.g. <tt>image/jpeg</tt>.


Această pagină specială permite căutarea fișierelor în funcție de tipul MIME (Multipurpose Internet Mail Extensions). Cele mai des întâlnite sunt:
* Imagini : <code>image/jpeg</code> 
* Diagrame : <code>image/png</code>, <code>image/svg+xml</code> 
* Imagini animate : <code>image/gif</code> 
* Fișiere sunet : <code>audio/ogg</code>, <code>audio/x-ogg</code> 
* Fișiere video : <code>video/ogg</code>, <code>video/x-ogg</code> 
* Fișiere PDF : <code>application/pdf</code>

Lista tipurilor MIME recunoscute de MediaWiki poate fi găsită la [http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/mime.types?view=markup fișiere mime.types].',
'mimetype'           => 'Tip MIME:',
'download'           => 'descarcă',

# Unwatched pages
'unwatchedpages' => 'Pagini neurmărite',

# List redirects
'listredirects' => 'Lista de redirecționări',

# Unused templates
'unusedtemplates'     => 'Formate neutilizate',
'unusedtemplatestext' => 'Lista de mai jos cuprinde toate formatele care nu sînt incluse în nici o altă pagină.
Înainte de a le șterge asigurați-vă că într-adevăr nu există legături dinspre alte pagini.',
'unusedtemplateswlh'  => 'alte legături',

# Random page
'randompage'         => 'Pagină aleatorie',
'randompage-nopages' => 'Nu există pagini în spaţiul de nume "$1".',

# Random redirect
'randomredirect'         => 'Redirecționare aleatorie',
'randomredirect-nopages' => 'Nu există redirecționări în spațiul de nume "$1".',

# Statistics
'statistics'                   => 'Statistici',
'statistics-header-pages'      => 'Statisticile paginii',
'statistics-header-edits'      => 'Editează statisticile',
'statistics-header-views'      => 'Vizualizează statisticile',
'statistics-header-users'      => 'Statistici legate de utilizatori',
'statistics-articles'          => 'Articole',
'statistics-pages'             => 'Pagini',
'statistics-pages-desc'        => 'Toate paginile din wiki, inclusiv pagini de discuție, redirectări etc.',
'statistics-files'             => 'Fișiere încărcate',
'statistics-edits'             => 'Editări de la instalarea {{SITENAME}}',
'statistics-edits-average'     => 'Media editărilor pe pagină',
'statistics-views-total'       => 'Număr de vizualizări',
'statistics-views-peredit'     => 'Vizualizări pe editare',
'statistics-jobqueue'          => 'Lungime [http://www.mediawiki.org/wiki/Manual:Job_queue listă de așteptare]',
'statistics-users'             => '[[Special:ListUsers|Utilizatori]] înregistrați',
'statistics-users-active'      => 'Utilizatori activi',
'statistics-users-active-desc' => 'Utilizatori care au efectuat o acțiune în {{PLURAL:$1|ultima zi|ultimele $1 zile}}',
'statistics-mostpopular'       => 'Paginile cele mai vizualizate',

'disambiguations'      => 'Pagini de dezambiguizare',
'disambiguationspage'  => 'Template:Dezambiguizare',
'disambiguations-text' => "Paginile următoare conțin legături către o '''pagină de dezambiguizare'''.
În locul acesteia ar trebui să conțină legături către un articol.<br />
O pagină este considerată o pagină de dezambiguizare dacă folosește formate care apar la [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redirecționări duble',
'doubleredirectstext'        => 'Această listă conține pagini care redirecționează la alte pagini de redirecționare.
Fiecare rând conține legături la primele două redirecționări, precum și ținta celei de-a doua redirecționări, care este de obicei pagina țintă "reală", către care ar trebui să redirecționeze prima pagină.
Intrările <s>tăiate</s> au fost rezolvate.',
'double-redirect-fixed-move' => '[[$1]] a fost mutat, acum este un redirect către [[$2]]',
'double-redirect-fixer'      => 'Corector de redirecționări',

'brokenredirects'        => 'Redirecționări greșite',
'brokenredirectstext'    => 'Următoarele redirecționări conduc spre articole inexistente:',
'brokenredirects-edit'   => '(modifică)',
'brokenredirects-delete' => '(şterge)',

'withoutinterwiki'         => 'Pagini fără legături interwiki',
'withoutinterwiki-summary' => 'Următoarele pagini nu se leagă la versiuni ale lor în alte limbi:',
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Arată',

'fewestrevisions' => 'Articole cu cele mai puține revizii',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|un octet|$1 octeți}}',
'ncategories'             => '{{PLURAL:$1|o categorie|$1 categorii}}',
'nlinks'                  => '{{PLURAL:$1|o legătură|$1 legături}}',
'nmembers'                => '$1 {{PLURAL:$1|un membru|membri}}',
'nrevisions'              => '{{PLURAL:$1|o revizie|$1 revizii}}',
'nviews'                  => '{{PLURAL:$1|o accesare|$1 accesări}}',
'specialpage-empty'       => 'Această pagină este goală.',
'lonelypages'             => 'Pagini orfane',
'lonelypagestext'         => 'La următoarele pagini nu se leagă nici o altă pagină din {{SITENAME}}.',
'uncategorizedpages'      => 'Pagini necategorizate',
'uncategorizedcategories' => 'Categorii necategorizate',
'uncategorizedimages'     => 'Fișiere necategorizate',
'uncategorizedtemplates'  => 'Formate necategorizate',
'unusedcategories'        => 'Categorii neutilizate',
'unusedimages'            => 'Pagini neutilizate',
'popularpages'            => 'Pagini populare',
'wantedcategories'        => 'Categorii dorite',
'wantedpages'             => 'Pagini dorite',
'wantedpages-badtitle'    => 'Titlu invalid în rezultatele : $1',
'wantedfiles'             => 'Fișiere dorite',
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
'protectedpages-indef'    => 'Doar protecțiile pe termen nelimitat',
'protectedpages-cascade'  => 'Doar protejări în cascadă',
'protectedpagestext'      => 'Următoarele pagini sunt protejate la mutare sau editare',
'protectedpagesempty'     => 'Nu există pagini protejate',
'protectedtitles'         => 'Titluri protejate',
'protectedtitlestext'     => 'Următoarele titluri sunt protejate la creare',
'protectedtitlesempty'    => 'Nu există titluri protejate cu acești parametri.',
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
'unusedimagestext'        => 'Următoarele fișiere există dar nu sunt incluse în nicio altă pagină.
Vă rugăm să aveți în vedere faptul că alte saituri web pot avea o legătură directă către acest URL și s-ar putea afla aici chiar dacă nu sunt în utlizare activă.',
'unusedcategoriestext'    => 'Următoarele categorii de pagini există și totuși nici un articol sau categorie nu le folosește.',
'notargettitle'           => 'Lipsă țintă',
'notargettext'            => 'Nu ai specificat nici o pagină sau un utilizator țintă pentru care să se efectueze această operațiune.',
'nopagetitle'             => 'Nu există pagina destinație',
'nopagetext'              => 'Pagina destinație specificată nu există.',
'pager-newer-n'           => '{{PLURAL:$1|1 mai nou|$1 mai noi}}',
'pager-older-n'           => '{{PLURAL:$1|1|$1}} mai vechi',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Surse de cărți',
'booksources-search-legend' => 'Caută surse pentru cărți',
'booksources-go'            => 'Du-te',
'booksources-text'          => 'Mai jos se află o listă de legături înspre alte situri care vând cărți noi sau vechi, și care pot oferi informații suplimentare despre cărțile pe care le căutați:',
'booksources-invalid-isbn'  => 'Codul ISBN oferit nu este valid; verificați dacă a fost copiat corect de la sursa originală.',

# Special:Log
'specialloguserlabel'  => 'Utilizator:',
'speciallogtitlelabel' => 'Titlu:',
'log'                  => 'Jurnale',
'all-logs-page'        => 'Toate jurnalele publice',
'alllogstext'          => 'Afișare combinată a tuturor jurnalelor {{SITENAME}}.
Puteți limita vizualizarea selectând tipul jurnalului, numele de utilizator sau pagina afectată.',
'logempty'             => 'Nici o înregistrare în jurnal.',
'log-title-wildcard'   => 'Caută titluri care încep cu acest text',

# Special:AllPages
'allpages'          => 'Toate paginile',
'alphaindexline'    => '$1 către $2',
'nextpage'          => 'Pagina următoare ($1)',
'prevpage'          => 'Pagina anterioară ($1)',
'allpagesfrom'      => 'Afișează paginile pornind de la:',
'allpagesto'        => 'Afișează paginile terminând cu:',
'allarticles'       => 'Toate articolele',
'allinnamespace'    => 'Toate paginile (spațiu de nume $1)',
'allnotinnamespace' => 'Toate paginile (în afara spațiului de nume $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Următor',
'allpagessubmit'    => 'Trimite',
'allpagesprefix'    => 'Afișează paginile cu prefix:',
'allpagesbadtitle'  => 'Titlul paginii este nevalid sau conține un prefix inter-wiki. Este posibil să conțină unul sau mai multe caractere care nu pot fi folosite în titluri.',
'allpages-bad-ns'   => '{{SITENAME}} nu are spațiul de nume „$1”.',

# Special:Categories
'categories'                    => 'Categorii',
'categoriespagetext'            => 'Următoarele categorii conţin pagini sau fişiere.
[[Special:UnusedCategories|Categoriile neutilizate]] nu apar aici.
Vedeţi şi [[Special:WantedCategories|categoriile dorite]].',
'categoriesfrom'                => 'Arată categoriile pornind de la:',
'special-categories-sort-count' => 'ordonează după număr',
'special-categories-sort-abc'   => 'sortează alfabetic',

# Special:DeletedContributions
'deletedcontributions'       => 'Contribuții șterse',
'deletedcontributions-title' => 'Contribuții șterse',

# Special:LinkSearch
'linksearch'       => 'Legături externe',
'linksearch-pat'   => 'Model de căutare:',
'linksearch-ns'    => 'Spațiu de nume:',
'linksearch-ok'    => 'Caută',
'linksearch-text'  => 'Pot fi folosite metacaractere precum "*.wikipedia.org".<br />
Protocoale suportate: <tt>$1</tt>',
'linksearch-line'  => '$1 este legat de $2',
'linksearch-error' => 'Metacaracterele pot să apară doar la începutul hostname-ului.',

# Special:ListUsers
'listusersfrom'      => 'Afișează utilizatori începând cu:',
'listusers-submit'   => 'Arată',
'listusers-noresult' => 'Nici un utilizator găsit.',

# Special:Log/newusers
'newuserlogpage'              => 'Jurnal utilizatori noi',
'newuserlogpagetext'          => 'Acesta este jurnalul creărilor conturilor de utilizator.',
'newuserlog-byemail'          => 'parola trimisă prin e-mail',
'newuserlog-create-entry'     => 'Utilizator nou',
'newuserlog-create2-entry'    => 'a fost creat contul nou $1',
'newuserlog-autocreate-entry' => 'Cont creat automat',

# Special:ListGroupRights
'listgrouprights'                 => 'Permisiunile grupurilor de utilizatori',
'listgrouprights-summary'         => 'Mai jos este afișată o listă a grupurilor de utilizatori definită în această wiki, împreună cu permisiunile de acces asociate.
Pot exista [[{{MediaWiki:Listgrouprights-helppage}}|informații adiționale]] despre permisiunile individuale.',
'listgrouprights-group'           => 'Grup',
'listgrouprights-rights'          => 'Permisiuni',
'listgrouprights-helppage'        => 'Help:Group rights',
'listgrouprights-members'         => '(listă de membri)',
'listgrouprights-addgroup'        => 'Poți adăuga {{PLURAL:$2|grupul|grupurile}}: $1',
'listgrouprights-removegroup'     => 'Poți elimina {{PLURAL:$2|grupul|grupurile}}: $1',
'listgrouprights-addgroup-all'    => 'Pot fi adăugate toate grupurile',
'listgrouprights-removegroup-all' => 'Pot fi eliminate toate grupurile',

# E-mail user
'mailnologin'      => 'Nu există adresă de trimitere',
'mailnologintext'  => 'Trebuie să fii [[Special:UserLogin|autentificat]] și să ai o adresă validă de e-mail în [[Special:Preferences|preferințe]] pentru a trimite e-mail altor utilizatori.',
'emailuser'        => 'Trimite e-mail',
'emailpage'        => 'E-mail către utilizator',
'emailpagetext'    => 'Poți folosi formularul de mai jos pentru a trimite un e-mail acestui utilizator.
Adresa de e-mail introdusă de tine în [[Special:Preferences|preferințele de utilizator]] va apărea ca adresa expeditorului e-mail-ului, deci destinatarul va putea să îți răspundă direct.',
'usermailererror'  => 'Obiectul de mail a dat eroare:',
'defemailsubject'  => 'E-mail {{SITENAME}}',
'noemailtitle'     => 'Fără adresă de e-mail',
'noemailtext'      => 'Utilizatorul nu a specificat o adresă validă de e-mail.',
'nowikiemailtitle' => 'Nu este permis e-mail-ul',
'nowikiemailtext'  => 'Acest utilizator a ales să nu primească e-mail-uri de la alți utilizatori.',
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
'emailuserfooter'  => 'Acest mesaj a fost trimis de $1 către $2 prin intermediul funcției „Trimite e-mail” de la {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Pagini urmărite',
'mywatchlist'          => 'Pagini urmărite',
'watchlistfor'         => "(pentru '''$1''')",
'nowatchlist'          => 'Nu ați ales să urmăriți nici o pagină.',
'watchlistanontext'    => 'Te rugăm să $1 pentru a vizualiza sau edita itemii de pe lista ta de urmărire.',
'watchnologin'         => 'Nu sunteți autentificat',
'watchnologintext'     => 'Trebuie să fiți [[Special:UserLogin|autentificat]] pentru a vă modifica lista de pagini urmărite.',
'addedwatch'           => 'Adăugată la lista de pagini urmărite',
'addedwatchtext'       => 'Pagina „[[:$1]]” a fost adăugată la lista dv. de [[Special:Watchlist|articole urmărite]]. Modificările viitoare ale acestei pagini și a paginii asociate de discuții vor fi listate aici și, în plus, ele vor apărea cu <b>caractere îngroșate</b> în pagina de [[Special:RecentChanges|modificări recente]] pentru evidențiere.

Dacă doriți să eliminați această pagină din lista dv. de pagini urmărite în viitor, apăsați pe „Nu mai urmări” în bara de comenzi în timp ce această pagină este vizibilă.',
'removedwatch'         => 'Ştearsă din lista de pagini urmărite',
'removedwatchtext'     => 'Pagina "[[:$1]]" a fost eliminată din [[Special:Watchlist|lista de pagini urmărite]].',
'watch'                => 'Urmărește',
'watchthispage'        => 'Urmărește pagina',
'unwatch'              => 'Nu mai urmări',
'unwatchthispage'      => 'Nu mai urmări',
'notanarticle'         => 'Nu este un articol',
'notvisiblerev'        => 'Versiunea a fost ștearsă',
'watchnochange'        => 'Nici una dintre paginile pe care le urmăriți nu a fost modificată în perioada de timp afișată.',
'watchlist-details'    => '{{PLURAL:$1|O pagină|$1 pagini urmărite}}, excluzând paginile de discuție.',
'wlheader-enotif'      => '*Notificarea email este activată',
'wlheader-showupdated' => "* Paginile care au modificări de la ultima ta vizită sunt afișate '''îngroșat'''",
'watchmethod-recent'   => 'căutarea schimbărilor recente pentru paginile urmărite',
'watchmethod-list'     => 'căutarea paginilor urmărite pentru schimbări recente',
'watchlistcontains'    => 'Lista de pagini urmărite conține $1 {{PLURAL:$1|element|elemente}}.',
'iteminvalidname'      => "E o problemă cu elementul '$1', numele este invalid...",
'wlnote'               => "Mai jos se află {{PLURAL:$1|ultima schimbare|ultimele $1 schimbări}} din {{PLURAL:$2|ultima oră|ultimele '''$2''' ore}}.",
'wlshowlast'           => 'Arată ultimele $1 ore $2 zile $3',
'watchlist-options'    => 'Opțiunile listei de pagini urmărite',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Urmărește...',
'unwatching' => 'Așteptați...',

'enotif_mailer'                => 'Sistemul de notificare {{SITENAME}}',
'enotif_reset'                 => 'Marchează toate paginile vizitate.',
'enotif_newpagetext'           => 'Aceasta este o pagină nouă.',
'enotif_impersonal_salutation' => '{{SITENAME}} utilizator',
'changed'                      => 'modificat',
'created'                      => 'creat',
'enotif_subject'               => 'Pagina $PAGETITLE de la {{SITENAME}} a fost $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'           => 'Vedeți $1 pentru toate modificările de la ultima dvs. vizită.',
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
{{fullurl:{{ns:special}}:Watchlist/edit}}

Asistenţă şi suport:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Șterge pagina',
'confirm'                => 'Confirmă',
'excontent'              => "conținutul era: '$1'",
'excontentauthor'        => 'conținutul era: „$1” (unicul contribuitor: [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => "conținutul înainte de golire era: '$1'",
'exblank'                => 'pagina era goală',
'delete-confirm'         => 'Şterge "$1"',
'delete-legend'          => 'Şterge',
'historywarning'         => 'Atenţie! Pagina pe care o ştergi are istorie:',
'confirmdeletetext'      => 'Sunteți pe cale să ștergeți permanent o pagină sau imagine din baza de date, împreună cu istoria asociată acesteia. Vă rugăm să confirmați alegerea făcută de dvs., faptul că înțelegeți consecințele acestei acțiuni și faptul că o faceți în conformitate cu [[{{MediaWiki:Policy-url}}|Politica oficială]].',
'actioncomplete'         => 'Acțiune completă',
'deletedtext'            => 'Pagina "<nowiki>$1</nowiki>" a fost ștearsă. Vedeți $2 pentru o listă a elementelor șterse recent.',
'deletedarticle'         => 'a șters "[[$1]]"',
'suppressedarticle'      => 'eliminate "[[$1]]"',
'dellogpage'             => 'Jurnal ștergeri',
'dellogpagetext'         => 'Mai jos se află lista celor mai recente elemente șterse.',
'deletionlog'            => 'jurnal pagini șterse',
'reverted'               => 'Revenire la o versiune mai veche',
'deletecomment'          => 'Motiv:',
'deleteotherreason'      => 'Motiv diferit/suplimentar:',
'deletereasonotherlist'  => 'Alt motiv',
'deletereason-dropdown'  => '*Motive uzuale
** La cererea autorului
** Violarea drepturilor de autor
** Vandalism',
'delete-edit-reasonlist' => 'Modifică motivele ștergerii',
'delete-toobig'          => 'Această pagină are un istoric al modificărilor mare, mai mult de $1 {{PLURAL:$1|revizie|revizii}}.
Ştergerea unei astfel de pagini a fost restricționată pentru a preveni apariția unor erori în {{SITENAME}}.',
'delete-warning-toobig'  => 'Această pagină are un istoric al modificărilor mult prea mare, mai mult de $1 {{PLURAL:$1|revizie|revizii}}.
Ştergere lui poate afecta baza de date a sitului {{SITENAME}};
continuă cu atenție.',

# Rollback
'rollback'         => 'Editări de revenire',
'rollback_short'   => 'Revenire',
'rollbacklink'     => 'revenire',
'rollbackfailed'   => 'Revenirea nu s-a putut face',
'cantrollback'     => 'Nu se poate reveni; ultimul contribuitor este autorul acestui articol.',
'alreadyrolled'    => 'Nu se poate reveni peste ultima modificare a articolului [[:$1]] făcută de către [[User:$2|$2]] ([[User talk:$2|discuție]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); altcineva a modificat articolul sau a revenit deja.

Ultima editare a fost făcută de către [[User:$3|$3]] ([[User talk:$3|discuție]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "Descrierea modificărilor a fost: „''$1''”.", # only shown if there is an edit comment
'revertpage'       => 'Anularea modificărilor efectuate de către [[Special:Contributions/$2|$2]] ([[User talk:$2|discuție]]) și revenire la ultima versiune de către [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => 'Anularea modificărilor făcute de $1;
revenire la ultima versiune de $2.',
'sessionfailure'   => 'Se pare că este o problemă cu sesiunea de autentificare; această acțiune a fost oprită ca o precauție împotriva hijack. Apăsați "back" și reîncărcați pagina de unde ați venit, apoi reîncercați.',

# Protect
'protectlogpage'              => 'Jurnal protecții',
'protectlogtext'              => 'Mai jos se află lista de blocări/deblocări a paginilor. Vezi [[Special:ProtectedPages]] pentru mai multe informații.',
'protectedarticle'            => 'a protejat "[[$1]]"',
'modifiedarticleprotection'   => 'schimbat nivelul de protecție pentru "[[$1]]"',
'unprotectedarticle'          => 'a deprotejat "[[$1]]"',
'movedarticleprotection'      => 'setările de protecție au fost mutate de la „[[$2]]” la „[[$1]]”',
'protect-title'               => 'Protejare "$1"',
'prot_1movedto2'              => 'a mutat [[$1]] la [[$2]]',
'protect-legend'              => 'Confirmă protejare',
'protectcomment'              => 'Motiv:',
'protectexpiry'               => 'Expiră:',
'protect_expiry_invalid'      => 'Timpul de expirare nu este valid.',
'protect_expiry_old'          => 'Timpul de expirare este în trecut.',
'protect-unchain'             => 'Deblochează permisiunile de mutare',
'protect-text'                => "Poți vizualiza sau modifica nivelul de protecție pentru pagina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nu poți schimba nivelurile de protecție fiind blocat.
Iată configurația curentă a paginii '''$1''':",
'protect-locked-dblock'       => "Nivelurile de protecție nu pot fi aplicate deoarece baza de date este închisă.
Iată configurația curentă a paginii '''$1''':",
'protect-locked-access'       => "Contul dumneavoastră nu are permisiunea de a schimba nivelurile de protejare a paginii.
Aici sunt setările curente pentru pagina '''$1''':",
'protect-cascadeon'           => 'Această pagină este protejată deoarece este inclusă în {{PLURAL:$1|următoarea pagină, ce are|următoarele pagini ce au}} activată protejarea la modificare în cascadă.
Puteți schimba nivelul de protejare al acestei pagini, dar asta nu va afecta protecția în cascadă.',
'protect-default'             => 'Permite toți utilizatorii',
'protect-fallback'            => 'Cere permisiunea "$1"',
'protect-level-autoconfirmed' => 'Blochează utilizatorii noi și neînregistrați',
'protect-level-sysop'         => 'Numai administratorii',
'protect-summary-cascade'     => 'în cascadă',
'protect-expiring'            => 'expiră $1 (UTC)',
'protect-expiry-indefinite'   => 'indefinit',
'protect-cascade'             => 'Protejare în cascadă - toate paginile incluse în această pagină vor fi protejate.',
'protect-cantedit'            => 'Nu puteți schimba nivelul de protecție a acestei pagini, deoarece nu aveți permisiunea de a o modifica.',
'protect-othertime'           => 'Alt termen:',
'protect-othertime-op'        => 'alt termen',
'protect-existing-expiry'     => 'Data expirării: $3, $2',
'protect-otherreason'         => 'Motiv diferit/adițional:',
'protect-otherreason-op'      => 'Alt motiv',
'protect-dropdown'            => '*Motive comune de protejare
** Vandalism excesiv
** Spam excesiv
** Modificări neproductive
** Pagină cu trafic mare',
'protect-edit-reasonlist'     => 'Modifică motivele protejării',
'protect-expiry-options'      => '1 oră:1 hour,1 zi:1 day,1 săptămână:1 week,2 săptămâni:2 weeks,1 lună:1 month,3 luni:3 months,6 luni:6 months,1 an:1 year,infinit:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Permisiune:',
'restriction-level'           => 'Nivel de restricție:',
'minimum-size'                => 'Mărime minimă',
'maximum-size'                => 'Mărime maximă:',
'pagesize'                    => '(octeți)',

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
'undelete'                     => 'Recuperează pagina ștearsă',
'undeletepage'                 => 'Vizualizează și recuperează pagini șterse',
'undeletepagetitle'            => "'''Această listă cuprinde versiuni șterse ale paginii [[:$1|$1]].'''",
'viewdeletedpage'              => 'Vezi paginile șterse',
'undeletepagetext'             => '{{PLURAL:$1|Următoarea pagină a fost ștearsă, dar încă se află în arhivă și poate fi recuperată|Următoarele $1 pagini au fost șterse, dar încă se află în arhivă și pot fi recuperate}}. Reține că arhiva se poate șterge din timp în timp.',
'undelete-fieldset-title'      => 'Recuperează versiuni',
'undeleteextrahelp'            => "Pentru a recupera întreaga pagină lăsați toate căsuțele nebifate și apăsați butonul '''''Recuperează'''''. Pentru a realiza o recuperare selectivă bifați versiunile pe care doriți să le recuperați și apăsați butonul '''''Recuperează'''''. Butonul '''''Resetează'''''  va șterge comentariul și toate bifările.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versiune arhivată|versiuni arhivate}}',
'undeletehistory'              => 'Dacă recuperați pagina, toate versiunile asociate vor fi adăugate retroactiv în istorie. Dacă o pagină nouă cu același nume a fost creată de la momentul ștergerii acesteia, versiunile recuperate vor apărea în istoria paginii, iar versiunea curentă a paginii nu va fi înlocuită automat de către versiunea recuperată.',
'undeleterevdel'               => 'Restaurarea unui revizii nu va fi efectuată dacă ea va apărea în capul listei de revizii parțial șterse.
În acest caz, trebuie să debifezi sau să arăți (unhide) cea mai recentă versiune ștearsă.',
'undeletehistorynoadmin'       => 'Acest articol a fost șters. Motivul ștergerii apare mai jos, alături de detaliile utilzatorilor care au editat această pagină înainte de ștergere. Textul prorpiu-zis al reviziilor șterse este disponibil doar administratorilor.',
'undelete-revision'            => 'Ştergere revizia $1 (din $4 $5) de către $3:',
'undeleterevision-missing'     => 'Revizie lipsă sau invalidă.
S-ar putea ca această legătură să fie greșită, sau revizia a fost restaurată ori ștearsă din arhivă.',
'undelete-nodiff'              => 'Nu s-a găsit vreo revizie anterioară.',
'undeletebtn'                  => 'Recuperează',
'undeletelink'                 => 'vezi/recuperează',
'undeletereset'                => 'Resetează',
'undeleteinvert'               => 'Exclude spațiul',
'undeletecomment'              => 'Motiv:',
'undeletedarticle'             => '"[[$1]]" a fost recuperat',
'undeletedrevisions'           => '{{PLURAL:$1|o revizie restaurată|$1 revizii restaurate}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revizie|revizii}} și $2 {{PLURAL:$2|fișier|fișiere}} recuperate',
'undeletedfiles'               => '$1 {{PLURAL:$1|revizie recuperată|revizii recuperate}}',
'cannotundelete'               => 'Recuperarea a eșuat; este posibil ca altcineva să fi recuperat pagina deja.',
'undeletedpage'                => "'''$1 a fost recuperat'''

Consultați [[Special:Log/delete|jurnalul ștergerilor]] pentru a vedea toate ștergerile și recuperările recente.",
'undelete-header'              => 'Vezi [[Special:Log/delete|logul de ștergere]] pentru paginile șterse recent.',
'undelete-search-box'          => 'Caută pagini șterse',
'undelete-search-prefix'       => 'Arată paginile care încep cu:',
'undelete-search-submit'       => 'Caută',
'undelete-no-results'          => 'Nicio pagină potrivită nu a fost găsită în arhiva paginilor șterse.',
'undelete-filename-mismatch'   => 'Nu poate fi restaurată revizia fișierului din data $1: nume nepotrivit',
'undelete-bad-store-key'       => 'Nu poate fi restaurată revizia fișierului din data $1: fișierul lipsea înainte de ștergere.',
'undelete-cleanup-error'       => 'Eroare la ștergerea arhivei nefolosite "$1".',
'undelete-missing-filearchive' => 'Nu poate fi restaurată arhiva fișierul cu ID-ul $1 pentru că nu există în baza de date.
S-ar putea ca ea să fie deja restaurată.',
'undelete-error-short'         => 'Eroare la restaurarea fișierului: $1',
'undelete-error-long'          => 'S-au găsit erori la ștergerea fișierului:

$1',
'undelete-show-file-confirm'   => 'Sunteți sigur că doriți să vizualizați o versiune ștearsă a fișierului "<nowiki>$1</nowiki>" din $2 ora $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'      => 'Spațiu de nume:',
'invert'         => 'Inversează selecţia',
'blanknamespace' => 'Articole',

# Contributions
'contributions'       => 'Contribuțiile utilizatorului',
'contributions-title' => 'Contribuțiile utilizatorului pentru $1',
'mycontris'           => 'Contribuții',
'contribsub2'         => 'Pentru $1 ($2)',
'nocontribs'          => 'Nu a fost găsită nici o modificare care să satisfacă acest criteriu.', # Optional parameter: $1 is the user name
'uctop'               => '(sus)',
'month'               => 'Din luna (și dinainte):',
'year'                => 'Începând cu anul (și precedenții):',

'sp-contributions-newbies'       => 'Arată doar contribuțiile conturilor noi',
'sp-contributions-newbies-sub'   => 'Pentru începători',
'sp-contributions-newbies-title' => 'Contribuțiile utilizatorului pentru conturile noi',
'sp-contributions-blocklog'      => 'jurnal blocări',
'sp-contributions-logs'          => 'jurnale',
'sp-contributions-search'        => 'Caută contribuții',
'sp-contributions-username'      => 'Adresă IP sau nume de utilizator:',
'sp-contributions-submit'        => 'Caută',

# What links here
'whatlinkshere'            => 'Ce trimite aici',
'whatlinkshere-title'      => 'Pagini care conțin legături spre „$1”',
'whatlinkshere-page'       => 'Pagină:',
'linkshere'                => "Următoarele pagini conțin legături către '''[[:$1]]''':",
'nolinkshere'              => "Nici o pagină nu trimite la '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nici o pagină din spațiul de nume ales nu trimite la '''[[:$1]]'''.",
'isredirect'               => 'pagină de redirecționare',
'istemplate'               => 'prin includerea formatului',
'isimage'                  => 'legătura fișierului',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterioara|anterioarele $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|următoarea|urmatoarele $1}}',
'whatlinkshere-links'      => '← legături',
'whatlinkshere-hideredirs' => '$1 redirecționări',
'whatlinkshere-hidetrans'  => '$1 transcluderile',
'whatlinkshere-hidelinks'  => '$1 legături',
'whatlinkshere-hideimages' => '$1 legături către imagine',
'whatlinkshere-filters'    => 'Filtre',

# Block/unblock
'blockip'                         => 'Blochează utilizator / IP',
'blockip-legend'                  => 'Blochează utilizator / IP',
'blockiptext'                     => "Pentru a bloca un utilizator completați rubricile de mai jos.<br />
'''Respectați [[{{MediaWiki:Policy-url}}|politica de blocare]].'''<br />
Precizați motivul blocării; de exemplu indicați paginile vandalizate de acest utilizator.",
'ipaddress'                       => 'Adresă IP:',
'ipadressorusername'              => 'Adresă IP sau nume de utilizator',
'ipbexpiry'                       => 'Expiră',
'ipbreason'                       => 'Motiv:',
'ipbreasonotherlist'              => 'Alt motiv',
'ipbreason-dropdown'              => '*Motivele cele mai frecvente
** Introducere de informații false
** Ştergere conținut fără explicații
** Introducere de legături externe de publicitate (spam)
** Creare pagini fără sens
** Tentative de intimidare
** Abuz utilizare conturi multiple
** Nume de utilizator inacceptabil',
'ipbanononly'                     => 'Blochează doar utilizatorii anonimi',
'ipbcreateaccount'                => 'Nu permite crearea de conturi',
'ipbemailban'                     => 'Nu permite utilizatorului să trimită e-mail',
'ipbenableautoblock'              => 'Blochează automat ultima adresă IP folosită de acest utilizator și toate adresele de la care încearcă să editeze în viitor',
'ipbsubmit'                       => 'Blochează acest utilizator',
'ipbother'                        => 'Alt termen:',
'ipboptions'                      => '2 ore:2 hours,1 zi:1 day,3 zile:3 days,1 săptămână:1 week,2 săptămâni:2 weeks,1 lună:1 month,3 luni:3 months,6 luni:6 months,1 an:1 year,infinit:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'altul',
'ipbotherreason'                  => 'Motiv diferit/adițional:',
'ipbhidename'                     => 'Ascunde numele de utilizator la editare și afișare',
'ipbwatchuser'                    => 'Urmărește pagina sa de utilizator și de discuții',
'ipballowusertalk'                => 'Permite acestui utilizator să-și modifice propria pagină de discuție cât timp este blocat',
'ipb-change-block'                => 'Reblochează utilizatorul cu acești parametri',
'badipaddress'                    => 'Adresa IP este invalidă.',
'blockipsuccesssub'               => 'Utilizatorul a fost blocat',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] a fost blocată.<br />
Vezi [[Special:IPBlockList|lista de adrese IP și conturi blocate]] pentru a revizui adresele blocate.',
'ipb-edit-dropdown'               => 'Modifică motivele blocării',
'ipb-unblock-addr'                => 'Deblochează $1',
'ipb-unblock'                     => 'Deblocați un nume de utilizator sau o adresă IP',
'ipb-blocklist-addr'              => 'Blocări existente pentru $1',
'ipb-blocklist'                   => 'Vezi blocările existente',
'ipb-blocklist-contribs'          => 'Contribuții la $1',
'unblockip'                       => 'Deblochează adresă IP',
'unblockiptext'                   => 'Folosiți formularul de mai jos pentru a restaura permisiunea de scriere pentru adrese IP sau nume de utilizator blocate anterior.',
'ipusubmit'                       => 'Elimină blocarea',
'unblocked'                       => '[[User:$1|$1]] a fost deblocat',
'unblocked-id'                    => 'Blocarea $1 a fost eliminată',
'ipblocklist'                     => 'Lista adreselor IP și a conturilor blocate',
'ipblocklist-legend'              => 'Găsește un utilizator blocat',
'ipblocklist-username'            => 'Nume de utilizator sau adresă IP:',
'ipblocklist-sh-userblocks'       => '$1 blocări de conturi',
'ipblocklist-sh-tempblocks'       => '$1 blocări temporare',
'ipblocklist-sh-addressblocks'    => '$1 blocări de adrese IP',
'ipblocklist-submit'              => 'Caută',
'blocklistline'                   => '$1, $2 a blocat $3 ($4)',
'infiniteblock'                   => 'termen nelimitat',
'expiringblock'                   => 'expiră la $1',
'anononlyblock'                   => 'doar anonimi',
'noautoblockblock'                => 'autoblocare dezactivată',
'createaccountblock'              => 'crearea de conturi blocată',
'emailblock'                      => 'e-mail blocat',
'blocklist-nousertalk'            => 'nu poate modifica propria pagină de discuție',
'ipblocklist-empty'               => 'Lista blocărilor este goală.',
'ipblocklist-no-results'          => 'Nu există blocare pentru adresa IP sau numele de utilizator.',
'blocklink'                       => 'blochează',
'unblocklink'                     => 'deblochează',
'change-blocklink'                => 'modifică blocarea',
'contribslink'                    => 'contribuții',
'autoblocker'                     => 'Autoblocat fiindcă folosiți aceeași adresă IP ca și „[[User:$1|$1]]”.
Motivul blocării utilizatorului $1 este: „$2”',
'blocklogpage'                    => 'Jurnal blocări',
'blocklog-fulllog'                => 'Întregul jurnal al blocărilor',
'blocklogentry'                   => 'a blocat "[[$1]]" pe o perioadă de $2 $3',
'reblock-logentry'                => 'a fost schimbată blocarea pentru [[$1]] cu data expirării la $2 $3',
'blocklogtext'                    => 'Acest jurnal cuprinde acțiunile de blocare și deblocare. Adresele IP blocate automat nu sunt afișate. Vizitați [[Special:IPBlockList|lista de adrese blocate]] pentru o listă explicită a adreselor blocate în acest moment.',
'unblocklogentry'                 => 'a deblocat $1',
'block-log-flags-anononly'        => 'doar utilizatorii anonimi',
'block-log-flags-nocreate'        => 'crearea de conturi dezactivată',
'block-log-flags-noautoblock'     => 'autoblocarea dezactivată',
'block-log-flags-noemail'         => 'e-mail blocat',
'block-log-flags-nousertalk'      => 'nu poate edita propria pagină de discuție',
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
'proxyblocker-disabled'           => 'Această funcție este dezactivată.',
'proxyblockreason'                => 'Adresa ta IP a fost blocată pentru că este un proxy deschis. Te rog, contactează provider-ul tău de servicii Internet sau tehnicieni IT și informează-i asupra acestei probleme serioase de securitate.',
'proxyblocksuccess'               => 'Realizat.',
'sorbsreason'                     => 'Adresa dumneavoastră IP este listată ca un proxy deschis în DNSBL.',
'sorbs_create_account_reason'     => 'Adresa dvs. IP este listată la un proxy deschis în lista neagră DNS. Nu vă puteți crea un cont',
'cant-block-while-blocked'        => 'Nu poți bloca alți utilizatori cât timp și tu ești blocat.',

# Developer tools
'lockdb'              => 'Blochează baza de date',
'unlockdb'            => 'Deblochează baza de date',
'lockdbtext'          => 'Blocarea bazei de date va împiedica pe toți utilizatorii
să modifice pagini, să-și schimbe preferințele, să-și modifice listele de
pagini urmărite și orice alte operațiuni care ar necesita schimări
în baza de date.
Te rugăm să confirmi că intenționezi acest lucru și faptul că vei debloca
baza de date atunci când vei încheia operațiunile de întreținere.',
'unlockdbtext'        => 'Deblocarea bazei de date va permite tuturor utilizatorilor să editeze pagini, să-și schimbe preferințele, să-și editeze listele de pagini urmărite și orice alte operațiuni care ar necesita schimări în baza de date. Te rugăm să-ți confirmi intenția de a face acest lucru.',
'lockconfirm'         => 'Da, chiar vreau să blochez baza de date.',
'unlockconfirm'       => 'Da, chiar vreau să deblochez baza de date.',
'lockbtn'             => 'Blochează baza de date',
'unlockbtn'           => 'Deblochează baza de date',
'locknoconfirm'       => 'Nu ați bifat căsuța de confirmare.',
'lockdbsuccesssub'    => 'Baza de date a fost blocată',
'unlockdbsuccesssub'  => 'Baza de date a fost deblocată',
'lockdbsuccesstext'   => 'Baza de date a fost blocată.<br />
Nu uitați să o [[Special:UnlockDB|deblocați]] la terminarea operațiilor administrative.',
'unlockdbsuccesstext' => 'Baza de date a fost deblocată.',
'lockfilenotwritable' => 'Fișierul bazei de date închise nu poate fi scris.
Pentru a închide sau deschide baza de date, acesta trebuie să poată fi scris de serverul web.',
'databasenotlocked'   => 'Baza de date nu este blocată.',

# Move page
'move-page'                    => 'Mută $1',
'move-page-legend'             => 'Mută pagina',
'movepagetext'                 => "Puteți folosi formularul de mai jos pentru a redenumi o pagină, mutându-i toată istoria sub noul nume.
Pagina veche va deveni o pagină de redirecționare către pagina nouă.
Legăturile către pagina veche nu vor fi redirecționate către cea nouă;
nu uitați să verificați dacă nu există redirecționări [[Special:DoubleRedirects|duble]] sau [[Special:BrokenRedirects|invalide]].

Vă rugăm să rețineți că sunteți responsabil(ă) pentru a face legăturile vechi să rămână valide.

Rețineți că pagina '''nu va fi mutată''' dacă există deja o pagină cu noul titlu, în afară de cazul că este complet goală sau este
o redirecționare și în plus nu are nici o istorie de modificare.
Cu alte cuvinte, veți putea muta înapoi o pagină pe care ați mutat-o greșit, dar nu veți putea suprascrie o pagină validă existentă prin mutarea alteia.

'''ATENŢIE!'''
Aceasta poate fi o schimbare drastică și neașteptată pentru o pagină populară;
vă rugăm, să vă asigurați că înțelegeți toate consecințele înainte de a continua.",
'movepagetalktext'             => "Pagina asociată de discuții, dacă există, va fi mutată
automat odată cu aceasta '''afară de cazul că''':
* Mutați pagina în altă secțiune a {{SITENAME}}
* Există deja o pagină de discuții cu conținut (care nu este goală), sau
* Nu confirmi căsuța de mai jos.

În oricare din cazurile de mai sus va trebui să muți sau să unifici
manual paginile de discuții, dacă dorești acest lucru.",
'movearticle'                  => 'Mută pagina',
'movenologin'                  => 'Nu ești autentificat',
'movenologintext'              => 'Trebuie să fii un utilizator înregistrat și să te [[Special:UserLogin|autentifici]] pentru a muta o pagină.',
'movenotallowed'               => 'Nu ai permisiunea să muți pagini.',
'movenotallowedfile'           => 'Nu ai permisiunea de a muta fișiere.',
'cant-move-user-page'          => 'Nu ai permisiunea de a muta paginile utilizatorului (în afară de subpagini).',
'cant-move-to-user-page'       => 'Nu aveți permisiunea de a muta o pagină în pagina utilizatorului (cu excepția subpaginii utilizatorului).',
'newtitle'                     => 'Titlul nou',
'move-watch'                   => 'Urmărește această pagină',
'movepagebtn'                  => 'Mută pagina',
'pagemovedsub'                 => 'Pagina a fost mutată',
'movepage-moved'               => '\'\'\'Pagina "$1" a fost mutată la pagina "$2"\'\'\'', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => 'O redirecționare a fost creată.',
'movepage-moved-noredirect'    => 'Crearea redirecționărilor a fost suprimată.',
'articleexists'                => 'O pagină cu același nume există deja, sau numele pe care l-ați ales este invalid. Sunteți rugat să alegeți un alt nume.',
'cantmove-titleprotected'      => 'Nu poți muta o pagina în această locație, pentru că noul titlu a fost protejat la creare',
'talkexists'                   => "'''Pagina în sine a fost mutată cu succes, dar pagina de discuții nu a putut fi mutată deoarece o alta deja există la noul titlu.
Te rugăm să le unifici manual.'''",
'movedto'                      => 'mutată la',
'movetalk'                     => 'Mută pagina de discuții asociată',
'move-subpages'                => 'Mută subpaginile (până la $1)',
'move-talk-subpages'           => 'Mută subpaginile paginii de discuții (până la $1)',
'movepage-page-exists'         => 'Pagina $1 există deja și nu poate fi rescrisă automat.',
'movepage-page-moved'          => 'Pagina $1 a fost mutată la $2.',
'movepage-page-unmoved'        => 'Pagina $1 nu a putut fi mutată la $2.',
'movepage-max-pages'           => 'Maxim $1 {{PLURAL:$1|pagină a fost mutată|pagini au fost mutate}}, nicio altă pagină nu va mai fi mutată automat.',
'1movedto2'                    => 'a mutat [[$1]] la [[$2]]',
'1movedto2_redir'              => 'a mutat [[$1]] la [[$2]] prin redirecționare',
'move-redirect-suppressed'     => 'redirecționarea a fost suprimată',
'movelogpage'                  => 'Jurnal mutări',
'movelogpagetext'              => 'Mai jos se află o listă cu paginile mutate.',
'movesubpage'                  => '{{PLURAL:$1|Subpagină|Subpagini}}',
'movesubpagetext'              => 'Această pagină are $1 {{PLURAL:$1|subpagină afișată|subpagini afișate}} mai jos.',
'movenosubpage'                => 'Această pagină nu are subpagini.',
'movereason'                   => 'Motiv:',
'revertmove'                   => 'revenire',
'delete_and_move'              => 'Șterge și redenumește',
'delete_and_move_text'         => '==Ştergere necesară==

Articolul de destinație "[[:$1]]" există deja. Doriți să îl ștergeți pentru a face loc mutării?',
'delete_and_move_confirm'      => 'Da, șterge pagina.',
'delete_and_move_reason'       => 'Şters pentru a face loc mutării',
'selfmove'                     => 'Titlurile sursei și ale destinației sunt aceleași; nu puteți muta o pagină peste ea însăși.',
'immobile-source-namespace'    => 'Nu se pot muta paginile din spațiul de nume "$1"',
'immobile-target-namespace'    => 'Nu se pot muta paginile în spațiul de nume "$1"',
'immobile-target-namespace-iw' => 'Legătura interwiki nu este o țintă validă pentru mutare.',
'immobile-source-page'         => 'Această pagină nu poate fi mutată.',
'immobile-target-page'         => 'Nu poate fi mutat la destinația cu acest titlu.',
'imagenocrossnamespace'        => 'Fișierul nu poate fi mutat la un spațiu de nume care nu este destinat fișierelor',
'imagetypemismatch'            => 'Extensia nouă a fișierului nu se potrivește cu tipul acestuia',
'imageinvalidfilename'         => 'Numele fișierului destinație este invalid',
'fix-double-redirects'         => 'Actualizează toate redirecționările care trimit la titlul original',
'move-leave-redirect'          => 'Lasă în urmă o redirecționare',

# Export
'export'            => 'Exportă pagini',
'exporttext'        => 'Puteți exporta textul și istoricul unei pagini anume sau ale unui grup de pagini în XML. 
Acesta poate fi apoi importate în alt wiki care rulează software MediaWiki prin [[Special:Import|pagina de importare]].

Pentru a exporta, introduceți titlurile în căsuța de mai jos, unul pe linie, și alegeți dacă doriți să exportați doar această versiune sau și cele mai vechi, cu istoricul lor, sau versiunea curentă cu informații despre ultima modificare.

În al doilea caz puteți folosi o legătură, de exemplu [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] pentru pagina „[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Include numai versiunea curentă, nu și toată istoria',
'exportnohistory'   => "---- '''Notă:''' exportarea versiunii complete a paginilor prin acest formular a fost scoasă din uz din motive de performanță.",
'export-submit'     => 'Exportă',
'export-addcattext' => 'Adaugă pagini din categoria:',
'export-addcat'     => 'Adaugă',
'export-addnstext'  => 'Adaugă pagini din spațiul de nume:',
'export-addns'      => 'Adaugă',
'export-download'   => 'Salvează ca fișier',
'export-templates'  => 'Include formate',
'export-pagelinks'  => 'Includere pagini legate de la o adâncime de:',

# Namespace 8 related
'allmessages'               => 'Toate mesajele',
'allmessagesname'           => 'Nume',
'allmessagesdefault'        => 'Textul standard',
'allmessagescurrent'        => 'Textul curent',
'allmessagestext'           => 'Aceasta este lista completă a mesajelor disponibile în domeniul MediaWiki.
Vă rugăm să vizitați [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] și [http://translatewiki.net translatewiki.net] dacă vreți să contribuiți la localizarea programului MediaWiki generic.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nu poate fi folosit deoarece '''\$wgUseDatabaseMessages''' este închisă.",
'allmessagesfilter'         => 'Filtrare în funcție de titlul mesajului:',
'allmessagesmodified'       => 'Arată doar mesajele modificate',

# Thumbnails
'thumbnail-more'           => 'Extinde',
'filemissing'              => 'Fișier lipsă',
'thumbnail_error'          => 'Eroare la generarea previzualizării: $1',
'djvu_page_error'          => 'Numărul paginii DjVu eronat',
'djvu_no_xml'              => 'Imposibil de obținut XML-ul pentru fișierul DjVu',
'thumbnail_invalid_params' => 'Parametrii invalizi ai imaginii miniatură',
'thumbnail_dest_directory' => 'Nu poate fi creat directorul destinație',

# Special:Import
'import'                     => 'Importă pagini',
'importinterwiki'            => 'Import transwiki',
'import-interwiki-text'      => 'Selectează un wiki și titlul paginii care trebuie importate. Datele reviziilor și numele editorilor vor fi salvate. Toate acțiunile de import transwiki pot fi găsite la [[Special:Log/import|log import]]',
'import-interwiki-source'    => 'Wiki/pagină sursă:',
'import-interwiki-history'   => 'Copiază toate versiunile istoricului acestei pagini',
'import-interwiki-templates' => 'Includeți toate formatele',
'import-interwiki-submit'    => 'Importă',
'import-interwiki-namespace' => 'Transferă către spațiul de nume:',
'import-upload-filename'     => 'Nume fișier:',
'import-comment'             => 'Comentariu:',
'importtext'                 => 'Te rog exportă fișierul din sursa wiki folosind [[Special:Export|utilitarul de exportare]].
Salvează-l pe discul tău și trimite-l aici.',
'importstart'                => 'Se importă paginile...',
'import-revision-count'      => '$1 {{PLURAL:$1|versiune|versiuni}}',
'importnopages'              => 'Nu există pagini de importat.',
'importfailed'               => 'Import eșuat: $1',
'importunknownsource'        => 'Tipul sursei de import este necunoscut',
'importcantopen'             => 'Fișierul importat nu a putut fi deschis',
'importbadinterwiki'         => 'Legătură interwiki greșită',
'importnotext'               => 'Gol sau fără text',
'importsuccess'              => 'Import reușit!',
'importhistoryconflict'      => 'Există istorii contradictorii (se poate să fi importat această pagină înainte)',
'importnosources'            => 'Nici o sursă de import transwiki a fost definită și încărcările directe ale istoricului sunt oprite.',
'importnofile'               => 'Nici un fișier pentru import nu a fost încărcat.',
'importuploaderrorsize'      => 'Încărcarea fișierului a eșuat.
Fișierul are o mărime mai mare decât limita de încărcare permisă.',
'importuploaderrorpartial'   => 'Încărcarea fișierului a eșuat.
Fișierul a fost incărcat parțial.',
'importuploaderrortemp'      => 'Încărcarea fișierului a eșuat.
Un dosar temporar lipsește.',
'import-parse-failure'       => 'Eroare la analiza importului XML',
'import-noarticle'           => 'Nicio pagină de importat!',
'import-nonewrevisions'      => 'Toate versiunile au fost importate anterior.',
'xml-error-string'           => '$1 la linia $2, col $3 (octet $4): $5',
'import-upload'              => 'Încărcare date XML',
'import-token-mismatch'      => 'S-au pierdut datele sesiunii. Vă rugăm să încercați din nou.',
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
'tooltip-pt-mytalk'               => 'Pagina dumneavoastră de discuții',
'tooltip-pt-anontalk'             => 'Discuții despre editări pentru adresa IP curentă',
'tooltip-pt-preferences'          => 'Preferințele dumneavoastră',
'tooltip-pt-watchlist'            => 'Lista paginilor pe care le monitorizați',
'tooltip-pt-mycontris'            => 'Listă de contribuții',
'tooltip-pt-login'                => 'Ești încurajat să te autentifici, deși acest lucru nu este obligatoriu.',
'tooltip-pt-anonlogin'            => 'Ești încurajat să te autentifici, deși acest lucru nu este obligatoriu.',
'tooltip-pt-logout'               => 'Închide sesiunea',
'tooltip-ca-talk'                 => 'Discuții despre această pagină',
'tooltip-ca-edit'                 => 'Puteți modifica această pagină. Înainte de a o salva vă rugăm s-o previzualizați.',
'tooltip-ca-addsection'           => 'Adaugă o nouă secțiune.',
'tooltip-ca-viewsource'           => 'Această pagină este protejată. Puteți vizualiza doar codul sursă',
'tooltip-ca-history'              => 'Versiunile anterioare ale paginii și autorii lor.',
'tooltip-ca-protect'              => 'Protejează această pagină.',
'tooltip-ca-delete'               => 'Șterge această pagină.',
'tooltip-ca-undelete'             => 'Restaureaza editările făcute acestui document, înainte să fi fost șters.',
'tooltip-ca-move'                 => 'Redenumește această pagină.',
'tooltip-ca-watch'                => 'Adaugă la lista de pagini urmărite',
'tooltip-ca-unwatch'              => 'Elimină această pagină din lista mea de monitorizare',
'tooltip-search'                  => 'Căutare în {{SITENAME}}',
'tooltip-search-go'               => 'Du-te la pagina cu acest nume dacă există',
'tooltip-search-fulltext'         => 'Caută paginile pentru acest text',
'tooltip-p-logo'                  => 'Pagina principală',
'tooltip-n-mainpage'              => 'Vedeți pagina principală',
'tooltip-n-portal'                => 'Despre proiect, ce puteți face, unde găsiți soluții.',
'tooltip-n-currentevents'         => 'Informații despre evenimentele curente',
'tooltip-n-recentchanges'         => 'Lista ultimelor schimbări realizate în acest wiki.',
'tooltip-n-randompage'            => 'Afișează o pagină aleatoare',
'tooltip-n-help'                  => 'Locul în care găsești ajutor.',
'tooltip-t-whatlinkshere'         => 'Lista tuturor paginilor wiki care conduc spre această pagină',
'tooltip-t-recentchangeslinked'   => 'Schimbări recente în legătură cu această pagină',
'tooltip-feed-rss'                => 'Alimentează fluxul RSS pentru această pagină',
'tooltip-feed-atom'               => 'Alimentează fluxul Atom pentru această pagină',
'tooltip-t-contributions'         => 'Vezi lista de contribuții ale acestui utilizator',
'tooltip-t-emailuser'             => 'Trimite un e-mail acestui utilizator',
'tooltip-t-upload'                => 'Încarcă fișiere',
'tooltip-t-specialpages'          => 'Lista tuturor paginilor speciale',
'tooltip-t-print'                 => 'Versiunea de tipărit a acestei pagini',
'tooltip-t-permalink'             => 'Legătura permanentă către această versiune a paginii',
'tooltip-ca-nstab-main'           => 'Vedeți conținutul paginii',
'tooltip-ca-nstab-user'           => 'Vezi pagina de utilizator',
'tooltip-ca-nstab-media'          => 'Vezi pagina media',
'tooltip-ca-nstab-special'        => 'Aceasta este o pagină specială, nu o puteți modifica direct.',
'tooltip-ca-nstab-project'        => 'Vezi pagina proiectului',
'tooltip-ca-nstab-image'          => 'Vezi pagina fişierului',
'tooltip-ca-nstab-mediawiki'      => 'Vedeți mesajul de sistem',
'tooltip-ca-nstab-template'       => 'Vezi formatul',
'tooltip-ca-nstab-help'           => 'Vezi pagina de ajutor',
'tooltip-ca-nstab-category'       => 'Vezi categoria',
'tooltip-minoredit'               => 'Marcați această modificare ca fiind minoră',
'tooltip-save'                    => 'Salvați modificările dumneavoastră',
'tooltip-preview'                 => 'Previzualizarea modificărilor dvs., folosiți-o vă rugăm înainte de a salva!',
'tooltip-diff'                    => 'Arată-mi modificările efectuate asupra textului',
'tooltip-compareselectedversions' => 'Vezi diferențele între cele două versiuni selectate de pe această pagină.',
'tooltip-watch'                   => 'Adaugă această pagină la lista mea de pagini urmărite',
'tooltip-recreate'                => 'Recreează',
'tooltip-upload'                  => 'Pornește încărcarea',
'tooltip-rollback'                => '„Revenire” anulează modificarea(ările) de pe această pagină a ultimului contribuitor printr-o singură apăsare',
'tooltip-undo'                    => '"Anulează" șterge această modificare și deschide formularul de modificare în modulul de previzualizare.
Permite adăugarea unui motiv în descrierea modificărilor',

# Stylesheets
'common.css'      => '/** CSS plasate aici vor fi aplicate tuturor aparițiilor */',
'standard.css'    => '/* CSS plasate aici vor afecta utilizatorii stilului Standard */',
'nostalgia.css'   => '/* CSS plasate aici vor afecta utilizatorii stilului Nostalgia  */',
'cologneblue.css' => '/* CSS plasate aici vor afecta utilizatorii stilului Cologne Blue */',
'monobook.css'    => '/* modificați acest fișier pentru a adapta înfățișarea monobook-ului pentru tot situl*/',
'myskin.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Myskin */',
'chick.css'       => '/* CSS plasate aici vor afecta utilizatorii stilului Chick */',
'simple.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Simple */',
'modern.css'      => '/* CSS plasate aici vor afecta utilizatorii stilului Modern */',
'print.css'       => '/* CSS plasate aici vor afecta modul în care paginile vor fi imprimate */',

# Metadata
'nodublincore'      => 'Metadatele Dublin Core RDF sunt dezactivate pentru acest server.',
'nocreativecommons' => 'Metadatele Creative Commons RDF dezactivate pentru acest server.',
'notacceptable'     => 'Serverul wiki nu poate oferi date într-un format pe care clientul tău să-l poată citi.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utilizator anonim|Utilizatori anonimi}} ai {{SITENAME}}',
'siteuser'         => 'Utilizator {{SITENAME}} $1',
'lastmodifiedatby' => 'Pagina a fost modificată în $1, la $2 de către $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Bazat pe munca lui $1.',
'others'           => 'alții',
'siteusers'        => '{{PLURAL:$2|Utilizator|Utilizatori}} {{SITENAME}} $1',
'creditspage'      => 'Credențiale',
'nocredits'        => 'Nu există credențiale disponibile pentru această pagină.',

# Spam protection
'spamprotectiontitle' => 'Filtru de protecție spam',
'spamprotectiontext'  => 'Pagina pe care doriți să o salvați a fost blocată de filtrul spam. Aceasta se datorează probabil unei legături spre un site extern. Ați putea verifica următoarea expresie regulată:',
'spamprotectionmatch' => 'Următorul text a fost oferit de filtrul de spam: $1',
'spambot_username'    => 'Curățarea de spam a MediaWiki',
'spam_reverting'      => 'Revenire la ultima versiune care nu conține legături către $1',
'spam_blanking'       => 'Toate reviziile conținând legături către $1, au eșuat',

# Info page
'infosubtitle'   => 'Informații pentru pagină',
'numedits'       => 'Număr de modificări (articole): $1',
'numtalkedits'   => 'Număr de modificări (pagina de discuții): $1',
'numwatchers'    => 'Număr de utilizatori care urmăresc: $1',
'numauthors'     => 'Număr de autori distincți (articole): $1',
'numtalkauthors' => 'Număr de autori distincți (pagini de discuții): $1',

# Skin names
'skinname-standard'    => 'Normală',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Albastru de Cologne',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'StilulMeu',
'skinname-chick'       => 'Şic',
'skinname-simple'      => 'Simplu',
'skinname-modern'      => 'Modern',

# Math options
'mw_math_png'    => 'Întodeauna afișează PNG',
'mw_math_simple' => 'HTML pentru formule simple, altfel PNG',
'mw_math_html'   => 'HTML dacă este posibil, altfel PNG',
'mw_math_source' => 'Lasă ca TeX (pentru browser-ele text)',
'mw_math_modern' => 'Recomandat pentru browser-ele moderne',
'mw_math_mathml' => 'MathML dacă este posibil (experimental)',

# Patrolling
'markaspatrolleddiff'                 => 'Marchează ca patrulat',
'markaspatrolledtext'                 => 'Marchează acest articol ca patrulat',
'markedaspatrolled'                   => 'A fost marcat ca patrulat',
'markedaspatrolledtext'               => 'Modificarea selectată a fost marcată ca patrulată.',
'rcpatroldisabled'                    => 'Opțiunea de patrulare a modificărilor recente este dezactivată',
'rcpatroldisabledtext'                => 'Patrularea modificărilor recente este în prezent dezactivată.',
'markedaspatrollederror'              => 'Nu se poate marca ca patrulat',
'markedaspatrollederrortext'          => 'Trebuie să specificați o revizie care să fie marcată ca patrulată.',
'markedaspatrollederror-noautopatrol' => 'Nu puteți marca propriile modificări ca patrulate.',

# Patrol log
'patrol-log-page'      => 'Jurnal patrulări',
'patrol-log-header'    => 'Mai jos apare o listă a tuturor paginilor marcate ca verificate.',
'patrol-log-line'      => 'a marcat versiunea $1 a $2 ca verificată $3',
'patrol-log-auto'      => '(automat)',
'patrol-log-diff'      => 'revizia $1',
'log-show-hide-patrol' => '$1 istoricul versiunilor patrulate',

# Image deletion
'deletedrevision'                 => 'A fost ștearsă vechea revizie $1.',
'filedeleteerror-short'           => 'Eroare la ștergerea fișierului: $1',
'filedeleteerror-long'            => 'Au apărut erori când se încerca ștergerea fișierului:

$1',
'filedelete-missing'              => 'Fișierul „$1” nu poate fi șters, deoarece nu există.',
'filedelete-old-unregistered'     => 'Revizia specificată a fișierului "$1" nu este în baza de date.',
'filedelete-current-unregistered' => 'Fișierul specificat "$1" nu este în baza de date.',
'filedelete-archive-read-only'    => 'Directorul arhivei "$1" nu poate fi scris de serverul web.',

# Browsing diffs
'previousdiff' => '← Diferența anterioară',
'nextdiff'     => 'Diferența următoare →',

# Visual comparison
'visual-comparison' => 'Comparare vizuală',

# Media information
'mediawarning'         => "'''Atenție''': Acest tip de fișier poate conține cod periculos.
Executându-l, sistemul dvs. poate fi compromis.",
'imagemaxsize'         => 'Limitează dimensiunea imaginilor, pe paginile de descriere, la:',
'thumbsize'            => 'Dimensiunea miniaturii:',
'widthheight'          => '$1x$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pagină|pagini}}',
'file-info'            => '(mărime fișier: $1, tip MIME: $2)',
'file-info-size'       => '($1 × $2 pixeli, mărime fișier: $3, tip MIME: $4)',
'file-nohires'         => '<small>Rezoluții mai mari nu sunt disponibile.</small>',
'svg-long-desc'        => '(fișier SVG, cu dimensiunea nominală de $1 × $2 pixeli, mărime fișier: $3)',
'show-big-image'       => 'Mărește rezoluția imaginii',
'show-big-image-thumb' => '<small>Mărimea acestei previzualizări: $1 × $2 pixeli</small>',

# Special:NewFiles
'newimages'             => 'Galeria de imagini noi',
'imagelisttext'         => "Mai jos se află lista a '''$1''' {{PLURAL:$1|fișier ordonat|fișiere ordonate}} $2.",
'newimages-summary'     => 'Această pagină specială arată ultimele fișiere încărcate.',
'newimages-legend'      => 'Filtru',
'newimages-label'       => 'Numele fișierului (sau parte din el):',
'showhidebots'          => '($1 roboți)',
'noimages'              => 'Nimic de văzut.',
'ilsubmit'              => 'Caută',
'bydate'                => 'după dată',
'sp-newimages-showfrom' => 'Arată imaginile noi începând cu $1, ora $2',

# Bad image list
'bad_image_list' => 'Formatul este următorul:

Numai elementele unei liste sunt luate în considerare. (Acestea sunt linii ce încep cu *)

Prima legătură de pe linie trebuie să fie spre un fișier defectuos.

Orice legături ce urmează pe aceeași linie sunt considerate excepții, adică pagini unde fișierul poate apărea inclus direct.',

# Metadata
'metadata'          => 'Informații',
'metadata-help'     => 'Acest fișier conține informații suplimentare, introduse probabil de aparatul fotografic digital sau scanerul care l-a generat. 
Dacă fișierul a fost modificat între timp, este posibil ca unele detalii să nu mai fie valabile.',
'metadata-expand'   => 'Afișează detalii suplimentare',
'metadata-collapse' => 'Ascunde detalii suplimentare',
'metadata-fields'   => 'Datele suplimentare EXIF listate aici vor fi incluse în pagina dedicată imaginii când tabelul cu metadata este restrâns.
Altele vor fi ascunse implicit.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Lățime',
'exif-imagelength'                 => 'Înălțime',
'exif-bitspersample'               => 'Biți pe componentă',
'exif-compression'                 => 'Metodă de comprimare',
'exif-photometricinterpretation'   => 'Compoziția pixelilor',
'exif-orientation'                 => 'Orientare',
'exif-samplesperpixel'             => 'Numărul de componente',
'exif-planarconfiguration'         => 'Aranjarea datelor',
'exif-ycbcrsubsampling'            => 'Mostră din fracția Y/C',
'exif-ycbcrpositioning'            => 'Poziționarea Y și C',
'exif-xresolution'                 => 'Rezoluție orizontală',
'exif-yresolution'                 => 'Rezoluție verticală',
'exif-resolutionunit'              => 'Unitate de rezoluție pentru X și Y',
'exif-stripoffsets'                => 'Locația datelor imaginii',
'exif-rowsperstrip'                => 'Numărul de linii per bandă',
'exif-stripbytecounts'             => 'Biți corespunzători benzii comprimate',
'exif-jpeginterchangeformat'       => 'Offset pentru JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Biți de date JPEG',
'exif-transferfunction'            => 'Funcția de transfer',
'exif-whitepoint'                  => 'Cromaticitatea punctului alb',
'exif-primarychromaticities'       => 'Coordonatele cromatice ale culorilor primare',
'exif-ycbcrcoefficients'           => 'Tăria culorii coeficienților matricei de transformare',
'exif-referenceblackwhite'         => 'Perechile de valori de referință albe și negre',
'exif-datetime'                    => 'Data și ora modificării fișierului',
'exif-imagedescription'            => 'Titlul imaginii',
'exif-make'                        => 'Producătorul aparatului foto',
'exif-model'                       => 'Modelul aparatului foto',
'exif-software'                    => 'Software folosit',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titularul drepturilor de autor',
'exif-exifversion'                 => 'Versiune exif',
'exif-flashpixversion'             => 'Versiune susținută de Flashpix',
'exif-colorspace'                  => 'Spațiu de culoare',
'exif-componentsconfiguration'     => 'Semnificația componentelor',
'exif-compressedbitsperpixel'      => 'Mod de comprimare a imaginii',
'exif-pixelydimension'             => 'Lățimea validă a imaginii',
'exif-pixelxdimension'             => 'Valind image height',
'exif-makernote'                   => 'Observațiile producătorului',
'exif-usercomment'                 => 'Comentariile utilizatorilor',
'exif-relatedsoundfile'            => 'Fișierul audio asemănător',
'exif-datetimeoriginal'            => 'Data și ora producerii imaginii',
'exif-datetimedigitized'           => 'Data și ora digitizării',
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
'exif-subjectdistance'             => 'Distanța față de subiect',
'exif-meteringmode'                => 'Forma de măsurare',
'exif-lightsource'                 => 'Sursă de lumină',
'exif-flash'                       => 'Bliț',
'exif-focallength'                 => 'Distanța focală a obiectivului',
'exif-subjectarea'                 => 'Suprafața subiectului',
'exif-flashenergy'                 => 'Energie bliț',
'exif-spatialfrequencyresponse'    => 'Răspunsul frecvenței spațiale',
'exif-focalplanexresolution'       => 'Rezoluția focală plană X',
'exif-focalplaneyresolution'       => 'Rezoluția focală plană Y',
'exif-focalplaneresolutionunit'    => 'Unitatea de măsură pentru rezoluția focală plană',
'exif-subjectlocation'             => 'Locația subiectului',
'exif-exposureindex'               => 'Indexul expunerii',
'exif-sensingmethod'               => 'Metoda sensibilă',
'exif-filesource'                  => 'Fișier sursă',
'exif-scenetype'                   => 'Tipul scenei',
'exif-cfapattern'                  => 'Mozaic CFA (filtre color)',
'exif-customrendered'              => 'Prelucrarea imaginii',
'exif-exposuremode'                => 'Mod de expunere',
'exif-whitebalance'                => 'Balanța albă',
'exif-digitalzoomratio'            => 'Raportul zoom-ului digital',
'exif-focallengthin35mmfilm'       => 'Distanță focală pentru film de 35 mm',
'exif-scenecapturetype'            => 'Tipul de surprindere a scenei',
'exif-gaincontrol'                 => 'Controlul scenei',
'exif-contrast'                    => 'Contrast',
'exif-saturation'                  => 'Saturație',
'exif-sharpness'                   => 'Ascuțime',
'exif-devicesettingdescription'    => 'Descrierea reglajelor aparatului',
'exif-subjectdistancerange'        => 'Distanța față de subiect',
'exif-imageuniqueid'               => 'Identificarea imaginii unice',
'exif-gpsversionid'                => 'Versiunea de conversie GPS',
'exif-gpslatituderef'              => 'Latitudine nordică sau sudică',
'exif-gpslatitude'                 => 'Latitudine',
'exif-gpslongituderef'             => 'Longitudine estică sau vestică',
'exif-gpslongitude'                => 'Longitudine',
'exif-gpsaltituderef'              => 'Indicarea altitudinii',
'exif-gpsaltitude'                 => 'Altitudine',
'exif-gpstimestamp'                => 'ora GPS (ceasul atomic)',
'exif-gpssatellites'               => 'Sateliți utilizați pentru măsurare',
'exif-gpsstatus'                   => 'Starea receptorului',
'exif-gpsmeasuremode'              => 'Mod de măsurare',
'exif-gpsdop'                      => 'Precizie de măsurare',
'exif-gpsspeedref'                 => 'Unitatea de măsură pentru viteză',
'exif-gpsspeed'                    => 'Viteza receptorului GPS',
'exif-gpstrackref'                 => 'Referință pentru direcția de mișcare',
'exif-gpstrack'                    => 'Direcție de mișcare',
'exif-gpsimgdirectionref'          => 'Referință pentru direcția imaginii',
'exif-gpsimgdirection'             => 'Direcția imaginii',
'exif-gpsmapdatum'                 => 'Expertiza geodezică a datelor utilizate',
'exif-gpsdestlatituderef'          => 'Referință pentru latitudinea destinației',
'exif-gpsdestlatitude'             => 'Destinația latitudinală',
'exif-gpsdestlongituderef'         => 'Referință pentru longitudinea destinației',
'exif-gpsdestlongitude'            => 'Longitudinea destinației',
'exif-gpsdestbearingref'           => 'Referință pentru raportarea destinației',
'exif-gpsdestbearing'              => 'Raportarea destinației',
'exif-gpsdestdistanceref'          => 'Referință pentru distanța până la destinație',
'exif-gpsdestdistance'             => 'Distanța până la destinație',
'exif-gpsprocessingmethod'         => 'Numele metodei de procesare GPS',
'exif-gpsareainformation'          => 'Numele domeniului GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Corecția diferențială GPS',

# EXIF attributes
'exif-compression-1' => 'Necomprimată',

'exif-unknowndate' => 'Dată necunoscută',

'exif-orientation-1' => 'Normală', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Oglindită orizontal', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotită cu 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Oglindită vertical', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotită 90° în sens opus acelor de ceasornic și oglindită vertical', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotită 90° în sensul acelor de ceasornic', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotită 90° în sensul acelor de ceasornic și oglindită vertical', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotită 90° în sens opus acelor de ceasornic', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'format compact',
'exif-planarconfiguration-2' => 'format plat',

'exif-componentsconfiguration-0' => 'neprecizat',

'exif-exposureprogram-0' => 'Neprecizat',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritate diafragmă',
'exif-exposureprogram-4' => 'Prioritate timp',
'exif-exposureprogram-5' => 'Program creativ (prioritate dată profunzimii)',
'exif-exposureprogram-6' => 'Program acțiune (prioritate dată timpului de expunere scurt)',
'exif-exposureprogram-7' => 'Mod portret (focalizare pe subiect și fundal neclar)',
'exif-exposureprogram-8' => 'Mod peisaj (focalizare pe fundal)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Necunoscut',
'exif-meteringmode-1'   => 'Medie',
'exif-meteringmode-2'   => 'Media ponderată la centru',
'exif-meteringmode-3'   => 'Punct',
'exif-meteringmode-4'   => 'MultiPunct',
'exif-meteringmode-5'   => 'Model',
'exif-meteringmode-6'   => 'Parțial',
'exif-meteringmode-255' => 'Alta',

'exif-lightsource-0'   => 'Necunoscută',
'exif-lightsource-1'   => 'Lumină solară',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungsten (lumină incandescentă)',
'exif-lightsource-4'   => 'Bliț',
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
'exif-flash-fired-0'    => 'Flash-ul nu a fost declanșat',
'exif-flash-fired-1'    => 'Flash declanșat',
'exif-flash-return-0'   => 'niciun stroboscop nu întoarce funcție de detecție',
'exif-flash-return-2'   => 'stroboscopul întoarce o lumină nedetectată',
'exif-flash-return-3'   => 'stroboscopul întoarce o lumină detectată',
'exif-flash-mode-1'     => 'obligatorie declanșarea flash-ului',
'exif-flash-mode-2'     => 'obligatorie suprimarea flash-ului',
'exif-flash-mode-3'     => 'modul automat',
'exif-flash-function-1' => 'Fără funcție flash',
'exif-flash-redeye-1'   => 'mod de îndepărtare a ochilor roșii',

'exif-focalplaneresolutionunit-2' => 'țoli',

'exif-sensingmethod-1' => 'Nedefinit',
'exif-sensingmethod-2' => 'Senzorul suprafeței color one-chip',
'exif-sensingmethod-3' => 'Senzorul suprafeței color two-chip',
'exif-sensingmethod-4' => 'Senzorul suprafeței color three-chip',
'exif-sensingmethod-5' => 'Senzorul suprafeței color secvențiale',
'exif-sensingmethod-7' => 'Senzor triliniar',
'exif-sensingmethod-8' => 'Senzorul linear al culorii secvențiale',

'exif-scenetype-1' => 'O imagine fotografiată direct',

'exif-customrendered-0' => 'Prelucrare normală',
'exif-customrendered-1' => 'Prelucrare nestandard',

'exif-exposuremode-0' => 'Expunere automată',
'exif-exposuremode-1' => 'Expunere manuală',
'exif-exposuremode-2' => 'Serie automată de expuneri',

'exif-whitebalance-0' => 'Auto-balanța albă',
'exif-whitebalance-1' => 'Balanța manuală albă',

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
'exif-saturation-1' => 'Saturație redusă',
'exif-saturation-2' => 'Saturație ridicată',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Ușor',
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
'exif-gpsstatus-v' => 'Măsurarea interoperabilității',

'exif-gpsmeasuremode-2' => 'măsurătoare bidimensională',
'exif-gpsmeasuremode-3' => 'măsurătoare tridimensională',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometri pe oră',
'exif-gpsspeed-m' => 'Mile pe oră',
'exif-gpsspeed-n' => 'Noduri',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcția reală',
'exif-gpsdirection-m' => 'Direcție magnetică',

# External editor support
'edit-externally'      => 'Editează acest fișier folosind o aplicație externă.',
'edit-externally-help' => '(Vedeți [http://www.mediawiki.org/wiki/Manual:External_editors instrucțiuni de instalare] pentru mai multe informații)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tot',
'imagelistall'     => 'toate',
'watchlistall2'    => 'toate',
'namespacesall'    => 'toate',
'monthsall'        => 'toate',

# E-mail address confirmation
'confirmemail'             => 'Confirmă adresa de email',
'confirmemail_noemail'     => 'Nu aveți o adresă de email validă setată la [[Special:Preferences|preferințe]].',
'confirmemail_text'        => '{{SITENAME}} necesită validarea adresei de e-mail înaintea folosirii funcțiilor e-mail. 
Apăsați butonul de dedesupt pentru a trimite un e-mail de confirmare către adresa dvs. 
Acesta va include o legătură care va conține codul; 
încărcați legătura în browser pentru a valida adresa de e-mail.',
'confirmemail_pending'     => 'Un cod de confirmare a fost trimis deja la adresa de e-mail indicată;
dacă ai creat recent contul, ar fi bine să aștepți câteva minute e-mailul de confirmare, înainte de a cere un nou cod.',
'confirmemail_send'        => 'Trimite un cod de confirmare',
'confirmemail_sent'        => 'E-mailul de confirmare a fost trimis.',
'confirmemail_oncreate'    => 'Un cod de confirmare a fost trimis la adresa de e-mail.
Acest cod nu este necesar pentru autentificare, dar trebuie transmis înainte de activarea oricăror proprietăți bazate pe e-mail din wiki.',
'confirmemail_sendfailed'  => 'Nu am putut trimite e-mailul de confirmare. Verificați adresa după caractere invalide.

Serverul de mail a returnat: $1',
'confirmemail_invalid'     => 'Cod de confirmare invalid. Acest cod poate fi expirat.',
'confirmemail_needlogin'   => 'Trebuie să vă $1 pentru a vă confirma adresa de email.',
'confirmemail_success'     => 'Adresa de e-mail a fost confirmată. Acum vă puteți [[Special:UserLogin|autentifica]] și bucura de wiki.',
'confirmemail_loggedin'    => 'Adresa de email a fost confirmată.',
'confirmemail_error'       => 'Ceva nu a funcționat la salvarea confirmării.',
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
'scarytranscludefailed'   => '[Şiretlicul formatului a dat greș pentru $1]',
'scarytranscludetoolong'  => '[URL-ul este prea lung]',

# Trackbacks
'trackbackbox'      => 'Urmăritori la acest articol:<br />
$1',
'trackbackremove'   => '([$1 Șterge])',
'trackbacklink'     => 'Urmăritor',
'trackbackdeleteok' => 'Urmăritorul a fost șters cu succes.',

# Delete conflict
'deletedwhileediting' => "'''Atenție''': Această pagină a fost ștearsă după ce ai început să o modifici!",
'confirmrecreate'     => "Utilizatorul [[User:$1|$1]] ([[User talk:$1|discuție]]) a șters acest articol după ce ați început să contribuți la el din motivul:
: ''$2''
Vă rugăm să confirmați faptul că într-adevăr doriți să recreați acest articol.",
'recreate'            => 'Recreează',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Doriți să reîncărcați pagina?',
'confirm-purge-bottom' => 'Actualizaea unei pagini șterge cache-ul și forțează cea mai recentă variantă să apară.',

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
'autosumm-blank'   => 'Şters conținutul paginii',
'autosumm-replace' => 'Pagină înlocuită cu „$1”',
'autoredircomment' => 'Redirecționat înspre [[$1]]',
'autosumm-new'     => 'Pagină nouă: $1',

# Live preview
'livepreview-loading' => 'Încărcare…',
'livepreview-ready'   => 'Încărcare… Gata!',
'livepreview-failed'  => 'Previzualizarea directă a eșuat! Încearcă previzualizarea normală.',
'livepreview-error'   => 'Conectarea a eșuat: $1 „$2”.
Încearcă previzualizarea normală.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Modificările mai noi de $1 {{PLURAL:$1|secondă|seconde}} pot să nu apară în listă.',
'lag-warn-high'   => 'Serverul bazei de date este suprasolicitat, astfel încît modificările făcute în ultimele $1 {{PLURAL:$1|secundă|secunde}} pot să nu apară în listă.',

# Watchlist editor
'watchlistedit-numitems'       => 'Lista ta de pagini urmărite conține {{PLURAL:$1|1 titlu|$1 titluri}}, excluzând paginile de discuții.',
'watchlistedit-noitems'        => 'Lista de pagini urmărite este goală.',
'watchlistedit-normal-title'   => 'Editează lista de urmărire',
'watchlistedit-normal-legend'  => 'Şterge titluri din lista de urmărire',
'watchlistedit-normal-explain' => 'Lista de mai jos cuprinde paginile pe care le urmăriți.
Pentru a elimina un titlu, bifați-l și apăsați „{{int:Watchlistedit-normal-submit}}”.
Puteți edita și direct [[Special:Watchlist/raw|listă brută]].',
'watchlistedit-normal-submit'  => 'Şterge titluri',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titlu a fost șters|$1 titluri au fost șterse}} din lista de urmărire:',
'watchlistedit-raw-title'      => 'Modifică lista brută a paginilor urmărite',
'watchlistedit-raw-legend'     => 'Modifică lista brută de pagini urmărite',
'watchlistedit-raw-explain'    => 'Lista de mai jos cuprinde paginile pe care le urmăriți. O puteți modifica adăugînd sau ștergînd titluri (cîte un titlu pe rînd).
După ce terminați apăsați „{{int:Watchlistedit-raw-submit}}”.
Puteți folosi în schimb [[Special:Watchlist/edit|editorul standard]].',
'watchlistedit-raw-titles'     => 'Titluri:',
'watchlistedit-raw-submit'     => 'Actualizează lista paginilor urmărite',
'watchlistedit-raw-done'       => 'Lista paginilor urmărite a fost actualizată.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titlu a fost adăugat|$1 titluri au fost adăugate}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titlu a fost șters|$1 titluri au fost șterse}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Vizualizează schimbările relevante',
'watchlisttools-edit' => 'Vezi și modifică lista paginilor urmărite',
'watchlisttools-raw'  => 'Modifică lista brută a paginilor urmărite',

# Core parser functions
'unknown_extension_tag' => 'Extensie etichetă necunoscută "$1"',
'duplicate-defaultsort' => '\'\'\'Atenție:\'\'\' Cheia de sortare implicită "$2" o înlocuiește pe precedenta "$1".',

# Special:Version
'version'                          => 'Versiune', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Extensii instalate',
'version-specialpages'             => 'Pagini speciale',
'version-parserhooks'              => 'Hook-uri parser',
'version-variables'                => 'Variabile',
'version-other'                    => 'Altele',
'version-mediahandlers'            => 'Suport media',
'version-hooks'                    => 'Hook-uri',
'version-extension-functions'      => 'Funcțiile extensiilor',
'version-parser-extensiontags'     => 'Taguri extensie parser',
'version-parser-function-hooks'    => 'Hook-uri funcții parser',
'version-skin-extension-functions' => 'Funcțiile extensiei interfeței',
'version-hook-name'                => 'Nume hook',
'version-hook-subscribedby'        => 'Subscris de',
'version-version'                  => 'Versiune',
'version-license'                  => 'Licență',
'version-software'                 => 'Software instalat',
'version-software-product'         => 'Produs',
'version-software-version'         => 'Versiune',

# Special:FilePath
'filepath'         => 'Cale fișier',
'filepath-page'    => 'Fișier:',
'filepath-submit'  => 'Du-te',
'filepath-summary' => 'Această pagină specială recreează calea completă a fișierului.
Imaginile sunt afișate la rezoluția lor maximă, în timp ce alte tipuri de fișiere vor porni direct în programele asociate.

Introduceți numele fișierului fără prefixul „{{ns:file}}:”.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Caută fișiere duplicate',
'fileduplicatesearch-summary'  => 'Caută fișiere duplicat bazate pe valoarea sa hash.

Introdu numele fișierului fără prefixul "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Caută un duplicat',
'fileduplicatesearch-filename' => 'Nume fișier:',
'fileduplicatesearch-submit'   => 'Caută',
'fileduplicatesearch-info'     => '$1 × $2 pixeli<br />Mărime fișier: $3<br />Tip MIME: $4',
'fileduplicatesearch-result-1' => 'Fișierul "$1" nu are un duplicat identic.',
'fileduplicatesearch-result-n' => 'Fișierul "$1" are {{PLURAL:$2|1 duplicat identic|$2 duplicate identice}}.',

# Special:SpecialPages
'specialpages'                   => 'Pagini speciale',
'specialpages-note'              => '----
* Pagini speciale normale.
* <strong class="mw-specialpagerestricted">Pagini speciale restricționate.</strong>',
'specialpages-group-maintenance' => 'Întreținere',
'specialpages-group-other'       => 'Alte pagini speciale',
'specialpages-group-login'       => 'Autentificare / Înregistrare',
'specialpages-group-changes'     => 'Schimbări recente și jurnale',
'specialpages-group-media'       => 'Fișiere',
'specialpages-group-users'       => 'Utilizatori și permisiuni',
'specialpages-group-highuse'     => 'Pagini utilizate intens',
'specialpages-group-pages'       => 'Liste de pagini',
'specialpages-group-pagetools'   => 'Unelte pentru pagini',
'specialpages-group-wiki'        => 'Date și unelte wiki',
'specialpages-group-redirects'   => 'Pagini speciale de redirecționare',
'specialpages-group-spam'        => 'Unelte spam',

# Special:BlankPage
'blankpage'              => 'Pagină goală',
'intentionallyblankpage' => 'Această pagină este goală în mod intenționat',

# External image whitelist
'external_image_whitelist' => ' #Lasă această linie exact așa cum este <pre>
#Pune fragmentele expresiei regulate (doar partea care merge între //) mai jos
#Acestea vor fi potrivite cu URL-uri de exterior (hotlinked)
#Acelea care se potrivesc vor fi afișate ca imagini, altfel va fi afișat doar un link la imagine
#Liniile care încep cu # sunt tratate ca comentarii
#Acesta este insensibil la majuscule sau minuscule

#Pune toate fragmentele regex deasupra aceastei linii. Lasă această linie exact așa cum este</pre>',

# Special:Tags
'tags'                    => 'Taguri de modificare valide',
'tag-filter'              => 'Filtrează [[Special:Tags|etichetele]]:',
'tag-filter-submit'       => 'Filtru',
'tags-title'              => 'Etichete',
'tags-intro'              => 'Această pagină afișează etichetele pe care software-ul le poate marca cu o editare, și semnificația lor.',
'tags-tag'                => 'Nume de etichetă internă',
'tags-display-header'     => 'Aspect în listele schimbărilor',
'tags-description-header' => 'Descriere completă a sensului',
'tags-hitcount-header'    => 'Modificări etichetate',
'tags-edit'               => 'modifică',
'tags-hitcount'           => '$1 {{PLURAL:$1|schimbare|schimbări}}',

# Database error messages
'dberr-header'      => 'Acest site are o problemă',
'dberr-problems'    => 'Ne cerem scuze! Acest site întâmpină dificultăți tehnice.',
'dberr-again'       => 'Așteaptă câteva minute și încearcă din nou.',
'dberr-info'        => '(Nu pot contacta baza de date a serverului: $1)',
'dberr-usegoogle'   => 'Între timp poți efectua căutarea folosind Google.',
'dberr-outofdate'   => 'De reținut ca indexarea conținutului nostru de către ei poate să nu fie actualizată.',
'dberr-cachederror' => 'Următoarea pagină este o copie în cache a paginii cerute, s-ar putea să nu fie actualizată.',

);
