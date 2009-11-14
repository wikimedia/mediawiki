<?php
/** Afrikaans (Afrikaans)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Adriaan
 * @author Anrie
 * @author Arnobarnard
 * @author Byeboer
 * @author Deadelf
 * @author Manie
 * @author Naudefj
 * @author Purodha
 * @author SPQRobin
 * @author Spacebirdy
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spesiaal',
	NS_TALK             => 'Bespreking',
	NS_USER             => 'Gebruiker',
	NS_USER_TALK        => 'Gebruikerbespreking',
	NS_PROJECT_TALK     => '$1bespreking',
	NS_FILE             => 'Lêer',
	NS_FILE_TALK        => 'Lêerbespreking',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWikibespreking',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Sjabloonbespreking',
	NS_HELP             => 'Hulp',
	NS_HELP_TALK        => 'Hulpbespreking',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Kategoriebespreking',
);

$namespaceAliases = array(
	'Beeld' => NS_FILE,
	'Beeldbespreking' => NS_FILE_TALK,
);

$magicWords = array(
	'redirect'              => array( '0', '#AANSTUUR', '#REDIRECT' ),
	'notoc'                 => array( '0', '__GEENIO__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__GEENGALERY__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__DWINGIO__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__IO__', '__TOC__' ),
	'noeditsection'         => array( '0', '__GEENNUWEAFDELING__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__GEENOPSKRIF__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'HUIDIGEMAAND', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'HUIDIGEMAAND1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'HUIDIGEDAG', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'HUIDIGEDAG2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'HUIDIGEJAAR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'HUIDIGETYD', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'HUIDIGEUUR', 'CURRENTHOUR' ),
	'numberofpages'         => array( '1', 'AANTALBLADSYE', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'AANTALARTIKELS', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'AANTALLêERS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'AANTALAKTIEWEGEBRUIKERS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'AANTALWYSIGINGS', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'AANTALKEERGESIEN', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'BLADSYNAAM', 'PAGENAME' ),
	'namespace'             => array( '1', 'NAAMSPASIE', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'BESPREKINGSBLADSY', 'TALKSPACE' ),
	'img_right'             => array( '1', 'regs', 'right' ),
	'img_left'              => array( '1', 'links', 'left' ),
	'img_none'              => array( '1', 'geen', 'none' ),
	'sitename'              => array( '1', 'WERFNAAM', 'SITENAME' ),
	'server'                => array( '0', 'BEDIENER', 'SERVER' ),
	'servername'            => array( '0', 'BEDIENERNAAM', 'SERVERNAME' ),
	'gender'                => array( '0', 'GESLAG:', 'GENDER:' ),
	'localweek'             => array( '1', 'HUIDIGEWEEK', 'LOCALWEEK' ),
	'plural'                => array( '0', 'MEERVOUD', 'PLURAL:' ),
	'fullurl'               => array( '0', 'VOLURL', 'FULLURL:' ),
	'displaytitle'          => array( '1', 'VERTOONTITEL', 'DISPLAYTITLE' ),
	'currentversion'        => array( '1', 'HUIDIGEWEERGAWE', 'CURRENTVERSION' ),
	'language'              => array( '0', '#TAAL:', '#LANGUAGE:' ),
	'special'               => array( '0', 'spesiaal', 'special' ),
	'filepath'              => array( '0', 'LêERPAD:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'etiket', 'tag' ),
	'pagesize'              => array( '1', 'BLADSYGROOTTE', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDEKS__', '__INDEX__' ),
	'noindex'               => array( '1', '__GEENINDEKS__', '__NOINDEX__' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dubbele aansture', 'Dubbeleaansture' ),
	'BrokenRedirects'           => array( 'Stukkende aansture', 'Stukkendeaansture' ),
	'Disambiguations'           => array( 'Dubbelsinnig' ),
	'Userlogin'                 => array( 'Teken in', 'Tekenin' ),
	'Userlogout'                => array( 'Teken uit', 'Tekenuit' ),
	'CreateAccount'             => array( 'SkepRekening', 'MaakGebruiker' ),
	'Preferences'               => array( 'Voorkeure' ),
	'Watchlist'                 => array( 'Dophoulys' ),
	'Recentchanges'             => array( 'Onlangse wysigings', 'Onlangsewysigings' ),
	'Upload'                    => array( 'Laai', 'Oplaai' ),
	'Listfiles'                 => array( 'Beeldelys', 'Prentelys', 'Lêerslys' ),
	'Newimages'                 => array( 'Nuwe beelde', 'Nuwebeelde', 'Nuwe lêers', 'Nuwelêers' ),
	'Listusers'                 => array( 'Gebruikerslys', 'Lysgebruikers' ),
	'Listgrouprights'           => array( 'LysGroepRegte' ),
	'Statistics'                => array( 'Statistiek' ),
	'Randompage'                => array( 'Lukraak', 'Lukrakebladsy' ),
	'Lonelypages'               => array( 'EensaamBladsye' ),
	'Uncategorizedpages'        => array( 'OngekategoriseerdeBladsye' ),
	'Uncategorizedcategories'   => array( 'OngekategoriseerdeKategorieë' ),
	'Uncategorizedimages'       => array( 'OngekategoriseerdeBeelde' ),
	'Uncategorizedtemplates'    => array( 'OngekategoriseerdeSjablone' ),
	'Unusedcategories'          => array( 'OngebruikdeKategorieë' ),
	'Unusedimages'              => array( 'OngebruikdeBeelde' ),
	'Wantedpages'               => array( 'GesoekdeBladsye', 'GebreekteSkakels' ),
	'Wantedcategories'          => array( 'GesoekteKategorieë' ),
	'Wantedfiles'               => array( 'GesoekteLêers' ),
	'Wantedtemplates'           => array( 'GesoekteSjablone' ),
	'Mostlinked'                => array( 'MeeteGeskakel' ),
	'Mostlinkedcategories'      => array( 'MeesGeskakeldeKategorieë' ),
	'Mostlinkedtemplates'       => array( 'MeesGeskakeldeSjablone' ),
	'Mostimages'                => array( 'MeesteBeelde' ),
	'Mostcategories'            => array( 'MeesteKategorieë' ),
	'Mostrevisions'             => array( 'MeesteWysigings' ),
	'Fewestrevisions'           => array( 'MinsteWysigings' ),
	'Shortpages'                => array( 'KortBladsye' ),
	'Longpages'                 => array( 'LangBladsye' ),
	'Newpages'                  => array( 'Nuwe bladsye', 'Nuwebladsye' ),
	'Ancientpages'              => array( 'OuBladsye' ),
	'Deadendpages'              => array( 'DoodloopBladsye' ),
	'Protectedpages'            => array( 'BeskermdeBladsye' ),
	'Protectedtitles'           => array( 'BeskermdeTitels' ),
	'Allpages'                  => array( 'Alle bladsye', 'Allebladsye' ),
	'Prefixindex'               => array( 'VoorvoegselIndeks' ),
	'Ipblocklist'               => array( 'IPBlokLys' ),
	'Specialpages'              => array( 'Spesiale bladsye', 'Spesialebladsye' ),
	'Contributions'             => array( 'Bydraes', 'Gebruikersbydraes' ),
	'Emailuser'                 => array( 'Stuur e-pos', 'Stuure-pos', 'Stuur epos', 'Stuurepos' ),
	'Confirmemail'              => array( 'Bevestig e-posadres', 'Bevestige-posadres', 'Bevestig eposadres', 'Bevestigeposadres' ),
	'Whatlinkshere'             => array( 'Skakels hierheen', 'Skakelshierheen' ),
	'Recentchangeslinked'       => array( 'OnlangseVeranderingsMetSkakels', 'VerwanteVeranderings' ),
	'Movepage'                  => array( 'Skuif bladsy', 'Skuifbladsy' ),
	'Blockme'                   => array( 'BlokMy' ),
	'Booksources'               => array( 'Boekbronne' ),
	'Categories'                => array( 'Kategorieë' ),
	'Export'                    => array( 'Eksporteer' ),
	'Version'                   => array( 'Weergawe' ),
	'Allmessages'               => array( 'Stelselboodskappe', 'Alle stelselboodskappe', 'Allestelselboodskappe', 'Boodskappe' ),
	'Log'                       => array( 'Logboek', 'Logboeke' ),
	'Blockip'                   => array( 'BlokIP' ),
	'Undelete'                  => array( 'Ontskrap' ),
	'Import'                    => array( 'Importeer' ),
	'Lockdb'                    => array( 'SluitDB' ),
	'Unlockdb'                  => array( 'OntsluitDB' ),
	'Userrights'                => array( 'GebruikersRegte' ),
	'MIMEsearch'                => array( 'MIME-soek', 'MIMEsoek', 'MIME soek' ),
	'Randomredirect'            => array( 'Lukrake aanstuur', 'Lukrakeaanstuur' ),
	'Mypage'                    => array( 'Mybladsy' ),
	'Mytalk'                    => array( 'Mybespreking', 'Mybesprekings' ),
	'Mycontributions'           => array( 'Mybydrae' ),
	'Search'                    => array( 'Soek' ),
	'Resetpass'                 => array( 'HerstelWagwoord' ),
	'Withoutinterwiki'          => array( 'Sonder taalskakels', 'Sondertaalskakels' ),
	'Filepath'                  => array( 'Lêerpad' ),
);

# South Africa uses space for thousands and comma for decimal
# Reference: AWS Reël 7.4 p. 52, 2002 edition
# glibc is wrong in this respect in some versions
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# User preference toggles
'tog-underline'               => 'Onderstreep skakels.',
'tog-highlightbroken'         => 'Wys gebroke skakels <a href="" class="new">so</a> (andersins: so<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Justeer paragrawe.',
'tog-hideminor'               => 'Moenie klein wysigings in die onlangse wysigingslys wys nie.',
'tog-hidepatrolled'           => 'Versteek gepatrolleerde wysigings in onlangse wysigingslys',
'tog-newpageshidepatrolled'   => 'Versteek gepatrolleerde wysigings van nuwe bladsy lys',
'tog-extendwatchlist'         => 'Brei dophoulys uit om alle wysigings te wys, nie slegs die nuutste nie',
'tog-usenewrc'                => 'Verbeterde onlangse wysigingslys (benodig JavaScript)',
'tog-numberheadings'          => 'Nommer opskrifte outomaties',
'tog-showtoolbar'             => 'Wys redigeergereedskap (benodig JavaScript)',
'tog-editondblclick'          => 'Dubbelkliek om blaaie te wysig (benodig JavaScript)',
'tog-editsection'             => 'Wys [wysig]-skakels vir elke afdeling',
'tog-editsectiononrightclick' => 'Wysig afdeling met regskliek op afdeling se titel (JavaScript)',
'tog-showtoc'                 => 'Wys inhoudsopgawe (by bladsye met meer as drie opskrifte)',
'tog-rememberpassword'        => 'Onthou wagwoord oor sessies.',
'tog-editwidth'               => 'Verbreed die wysigingsboks oor die volle breedte van die skerm',
'tog-watchcreations'          => 'Voeg bladsye wat ek skep by my dophoulys',
'tog-watchdefault'            => 'Lys nuwe en gewysigde bladsye.',
'tog-watchmoves'              => 'Voeg die bladsye wat ek skuif by my dophoulys',
'tog-watchdeletion'           => 'Voeg bladsye wat ek verwyder by my dophoulys',
'tog-minordefault'            => 'Merk alle wysigings automaties as klein by verstek.',
'tog-previewontop'            => 'Wys voorskou bo wysigingsboks.',
'tog-previewonfirst'          => 'Wys voorskou met eerste wysiging',
'tog-nocache'                 => 'Deaktiveer bladsykasstelsel (Engels: caching)',
'tog-enotifwatchlistpages'    => 'Stuur vir my e-pos met bladsyveranderings',
'tog-enotifusertalkpages'     => 'Stuur vir my e-pos as my eie besprekingsblad verander word',
'tog-enotifminoredits'        => 'Stuur ook e-pos vir klein bladsywysigings',
'tog-enotifrevealaddr'        => 'Stel my e-posadres bloot in kennisgewingspos',
'tog-shownumberswatching'     => 'Wys die aantal gebruikers wat dophou',
'tog-oldsig'                  => 'Voorskou van bestaande handtekening:',
'tog-fancysig'                => 'Hanteer handtekening as wikiteks (sonder outomatiese skakels)',
'tog-externaleditor'          => "Gebruik outomaties 'n eksterne redigeringsprogram",
'tog-externaldiff'            => "Gebruik 'n eksterne vergelykingsprogram (net vir deskundiges - benodig spesiale verstellings op u rekenaar)",
'tog-showjumplinks'           => 'Wys "spring na"-skakels vir toeganklikheid',
'tog-uselivepreview'          => 'Gebruik lewendige voorskou (JavaScript) (eksperimenteel)',
'tog-forceeditsummary'        => "Let my daarop as ek nie 'n opsomming van my wysiging gee nie",
'tog-watchlisthideown'        => 'Versteek my wysigings in dophoulys',
'tog-watchlisthidebots'       => 'Versteek robotwysigings in dophoulys',
'tog-watchlisthideminor'      => 'Versteek klein wysigings van my dophoulys',
'tog-watchlisthideliu'        => 'Versteek wysigings deur aangetekende gebruikers van dophoulys',
'tog-watchlisthideanons'      => 'Versteek wysigings deur anonieme gebruikers van dophoulys',
'tog-watchlisthidepatrolled'  => 'Versteek gepatrolleerde wysigings van dophoulys',
'tog-ccmeonemails'            => "Stuur my 'n kopie van die e-pos wat ek aan ander stuur",
'tog-diffonly'                => "Moenie 'n bladsy se inhoud onder die wysigingsverskil wys nie",
'tog-showhiddencats'          => 'Wys versteekte kategorië',
'tog-norollbackdiff'          => 'Laat verskille weg na terugrol',

'underline-always'  => 'Altyd',
'underline-never'   => 'Nooit',
'underline-default' => 'Blaaierverstek',

# Font style option in Special:Preferences
'editfont-style'     => 'Lettertipe vir wysigingsvenster:',
'editfont-default'   => 'Blaaierverstek',
'editfont-monospace' => 'Monospaced lettertipe',
'editfont-sansserif' => 'Sans-serif lettertipe',
'editfont-serif'     => 'Serif lettertipe',

# Dates
'sunday'        => 'Sondag',
'monday'        => 'Maandag',
'tuesday'       => 'Dinsdag',
'wednesday'     => 'Woensdag',
'thursday'      => 'Donderdag',
'friday'        => 'Vrydag',
'saturday'      => 'Saterdag',
'sun'           => 'So',
'mon'           => 'Ma',
'tue'           => 'Di',
'wed'           => 'Wo',
'thu'           => 'Do',
'fri'           => 'Vr',
'sat'           => 'Sa',
'january'       => 'Januarie',
'february'      => 'Februarie',
'march'         => 'Maart',
'april'         => 'April',
'may_long'      => 'Mei',
'june'          => 'Junie',
'july'          => 'Julie',
'august'        => 'Augustus',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Desember',
'january-gen'   => 'Januarie',
'february-gen'  => 'Februarie',
'march-gen'     => 'Maart',
'april-gen'     => 'April',
'may-gen'       => 'Mei',
'june-gen'      => 'Junie',
'july-gen'      => 'Julie',
'august-gen'    => 'Augustus',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Desember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mrt',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorie|Kategorieë}}',
'category_header'                => 'Bladsye in kategorie "$1"',
'subcategories'                  => 'Subkategorieë',
'category-media-header'          => 'Media in kategorie "$1"',
'category-empty'                 => "''Hierdie kategorie bevat geen bladsye of media nie.''",
'hidden-categories'              => '{{PLURAL:$1|Versteekte kategorie|Versteekte kategorië}}',
'hidden-category-category'       => 'Versteekte kategorieë',
'category-subcat-count'          => "{{PLURAL:$2|Hierdie kategorie bevat slegs die volgende subkategorie.|Hierdie kategorie bevat die volgende {{PLURAL:$1|subkategorie|$1 subkategorië}}, uit 'n totaal van $2.}}",
'category-subcat-count-limited'  => 'Hierdie kategorie het die volgende {{PLURAL:$1|subkategorie|$1 subkategorië}}.',
'category-article-count'         => "{{PLURAL:$2|Hierdie kategorie bevat slegs die volgende bladsy.|Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} is in hierdie kategorie, uit 'n totaal van $2.}}",
'category-article-count-limited' => 'Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} is in die huidige kategorie.',
'category-file-count'            => "{{PLURAL:$2|Hierdie kategorie bevat net die volgende lêer.|Die volgende {{PLURAL:$1|lêer|$1 lêers}} is in hierdie kategorie, uit 'n totaal van $2.}}",
'category-file-count-limited'    => 'Die volgende {{PLURAL:$1|lêer|$1 lêers}} is in die huidige kategorie.',
'listingcontinuesabbrev'         => 'vervolg',
'index-category'                 => 'Geïndekseerde bladsye',
'noindex-category'               => 'Ongeïndekseerde bladsye',

'mainpagetext'      => "<big>'''MediaWiki is suksesvol geïnstalleer.'''</big>",
'mainpagedocfooter' => "Konsulteer '''[http://meta.wikimedia.org/wiki/Help:Contents User's Guide]''' vir inligting oor hoe om die wikisagteware te gebruik.

== Hoe om te Begin ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Aangaande',
'article'       => 'Inhoudbladsy',
'newwindow'     => '(verskyn in nuwe venster)',
'cancel'        => 'Kanselleer',
'moredotdotdot' => 'Meer…',
'mypage'        => 'My bladsy',
'mytalk'        => 'My besprekings',
'anontalk'      => 'Besprekingsblad vir hierdie IP',
'navigation'    => 'Navigasie',
'and'           => '&#32;en',

# Cologne Blue skin
'qbfind'         => 'Vind',
'qbbrowse'       => 'Snuffel',
'qbedit'         => 'Wysig',
'qbpageoptions'  => 'Bladsyopsies',
'qbpageinfo'     => 'Bladsyinligting',
'qbmyoptions'    => 'My bladsye',
'qbspecialpages' => 'Spesiale bladsye',
'faq'            => 'Gewilde vrae',
'faqpage'        => 'Project:GewildeVrae',

# Vector skin
'vector-action-addsection'   => 'Nuwe onderwerp',
'vector-action-delete'       => 'Skrap',
'vector-action-move'         => 'Skuif',
'vector-action-protect'      => 'Beskerm',
'vector-action-undelete'     => 'Ontskrap',
'vector-action-unprotect'    => 'Verwyder beskerming',
'vector-namespace-category'  => 'Kategorie',
'vector-namespace-help'      => 'Hulpbladsy',
'vector-namespace-image'     => 'Lêer',
'vector-namespace-main'      => 'Bladsy',
'vector-namespace-media'     => 'Mediablad',
'vector-namespace-mediawiki' => 'Boodskap',
'vector-namespace-project'   => 'Projekblad',
'vector-namespace-special'   => 'Spesiale bladsy',
'vector-namespace-talk'      => 'Bespreking',
'vector-namespace-template'  => 'Sjabloon',
'vector-namespace-user'      => 'Gebruikersblad',
'vector-view-create'         => 'Skep',
'vector-view-edit'           => 'Wysig',
'vector-view-history'        => 'Wys geskiedenis',
'vector-view-view'           => 'Lees',
'vector-view-viewsource'     => 'Wys bronteks',
'actions'                    => 'Aksies',
'namespaces'                 => 'Naamruimtes',
'variants'                   => 'Variante',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Fout',
'returnto'          => 'Keer terug na $1.',
'tagline'           => 'Vanuit {{SITENAME}}',
'help'              => 'Hulp',
'search'            => 'Soek',
'searchbutton'      => 'Soek',
'go'                => 'Wys',
'searcharticle'     => 'Wys',
'history'           => 'Ouer weergawes',
'history_short'     => 'Geskiedenis',
'updatedmarker'     => 'opgedateer sedert my laaste besoek',
'info_short'        => 'Inligting',
'printableversion'  => 'Drukbare weergawe',
'permalink'         => 'Permanente skakel',
'print'             => 'Druk',
'edit'              => 'Wysig',
'create'            => 'Skep',
'editthispage'      => 'Wysig hierdie bladsy',
'create-this-page'  => 'Skep hierdie bladsy',
'delete'            => 'Skrap',
'deletethispage'    => 'Skrap die bladsy',
'undelete_short'    => 'Herstel {{PLURAL:$1|een wysiging|$1 wysigings}}',
'protect'           => 'Beskerm',
'protect_change'    => 'wysig',
'protectthispage'   => 'Beskerm hierdie bladsy',
'unprotect'         => 'Verwyder beskerming',
'unprotectthispage' => 'Verwyder beskerming vir die bladsy',
'newpage'           => 'Nuwe bladsy',
'talkpage'          => 'Bespreek hierdie bladsy',
'talkpagelinktext'  => 'Besprekings',
'specialpage'       => 'Spesiale bladsy',
'personaltools'     => 'Persoonlike gereedskap',
'postcomment'       => 'Nuwe opskrif',
'articlepage'       => 'Lees artikel',
'talk'              => 'Bespreking',
'views'             => 'Aansigte',
'toolbox'           => 'Gereedskap',
'userpage'          => 'Lees gebruikersbladsy',
'projectpage'       => 'Lees metabladsy',
'imagepage'         => 'Lees bladsy oor lêer',
'mediawikipage'     => 'Bekyk boodskapsbladsy',
'templatepage'      => 'Bekyk sjabloonsbladsy',
'viewhelppage'      => 'Bekyk hulpbladsy',
'categorypage'      => 'Bekyk kategorieblad',
'viewtalkpage'      => 'Lees bespreking',
'otherlanguages'    => 'Ander tale',
'redirectedfrom'    => '(Aangestuur vanaf $1)',
'redirectpagesub'   => 'Aanstuurblad',
'lastmodifiedat'    => 'Laaste wysiging op $2, $1.',
'viewcount'         => 'Hierdie bladsy is al {{PLURAL:$1|keer|$1 kere}} aangevra.',
'protectedpage'     => 'Beskermde bladsy',
'jumpto'            => 'Spring na:',
'jumptonavigation'  => 'navigasie',
'jumptosearch'      => 'soek',
'view-pool-error'   => "Jammer, die bedieners is tans oorbelas.
Te veel gebruikers probeer om na hierdie bladsy te kyk.
Wag asseblief 'n rukkie voordat u weer probeer om die bladsy op te roep.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Inligting oor {{SITENAME}}',
'aboutpage'            => 'Project:Omtrent',
'copyright'            => 'Teks is beskikbaar onderhewig aan $1.',
'copyrightpage'        => '{{ns:project}}:kopiereg',
'currentevents'        => 'Huidige gebeure',
'currentevents-url'    => 'Project:Huidige gebeure',
'disclaimers'          => 'Voorbehoud',
'disclaimerpage'       => 'Project:Voorwaardes',
'edithelp'             => 'Wysighulp',
'edithelppage'         => 'Help:Wysig',
'helppage'             => 'Help:Inhoud',
'mainpage'             => 'Tuisblad',
'mainpage-description' => 'Tuisblad',
'policy-url'           => 'Project:Beleid',
'portal'               => 'Gebruikersportaal',
'portal-url'           => 'Project:Gebruikersportaal',
'privacy'              => 'Privaatheidsbeleid',
'privacypage'          => 'Project:Privaatheidsbeleid',

'badaccess'        => 'Toestemmingsfout',
'badaccess-group0' => 'U is nie toegelaat om die aksie uit te voer wat u aangevra het nie.',
'badaccess-groups' => 'Die aksie wat u aangevra het is beperk tot gebruikers in {{PLURAL:$2|die groep|een van die groepe}}: $1.',

'versionrequired'     => 'Weergawe $1 van MediaWiki benodig',
'versionrequiredtext' => 'Weergawe $1 van MediaWiki word benodig om hierdie bladsy te gebruik. Sien [[Special:Version|version page]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Ontsluit van "$1"',
'youhavenewmessages'      => 'U het $1 (sien $2).',
'newmessageslink'         => 'nuwe boodskappe',
'newmessagesdifflink'     => 'die laaste wysiging',
'youhavenewmessagesmulti' => 'U het nuwe boodskappe op $1',
'editsection'             => 'wysig',
'editold'                 => 'wysig',
'viewsourceold'           => 'bekyk bronteks',
'editlink'                => 'wysig',
'viewsourcelink'          => 'wys bronkode',
'editsectionhint'         => 'Wysig afdeling: $1',
'toc'                     => 'Inhoud',
'showtoc'                 => 'wys',
'hidetoc'                 => 'versteek',
'thisisdeleted'           => 'Bekyk of herstel $1?',
'viewdeleted'             => 'Bekyk $1?',
'restorelink'             => '{{PLURAL:$1|die geskrapte wysiging|$1 geskrapte wysigings}}',
'feedlinks'               => 'Voer:',
'feed-invalid'            => 'Voertipe word nie ondersteun nie.',
'feed-unavailable'        => 'Sindikasievoer is nie beskikbaar',
'site-rss-feed'           => '$1 RSS-voer',
'site-atom-feed'          => '$1 Atom-voer',
'page-rss-feed'           => '"$1" RSS-voer',
'page-atom-feed'          => '"$1" Atom-voer',
'red-link-title'          => '$1 (bladsy bestaan nie)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bladsy',
'nstab-user'      => 'Gebruikerblad',
'nstab-media'     => 'Mediablad',
'nstab-special'   => 'Spesiale bladsy',
'nstab-project'   => 'Projekblad',
'nstab-image'     => 'Lêer',
'nstab-mediawiki' => 'Boodskap',
'nstab-template'  => 'Sjabloon',
'nstab-help'      => 'Hulpblad',
'nstab-category'  => 'Kategorie',

# Main script and global functions
'nosuchaction'      => 'Ongeldige aksie',
'nosuchactiontext'  => "Die opdrag in die URL is ongeldig.
U het moontlik 'n tikfout gemaak of 'n ongeldige skakel gevolg. 
Dit kan ook dui op 'n fout in die sagteware van {{SITENAME}}.",
'nosuchspecialpage' => 'Ongeldige spesiale bladsy',
'nospecialpagetext' => "<strong>U het 'n spesiale bladsy wat nie bestaan nie aangevra.</strong>

'n Lys met geldige spesiale bladsye is beskikbaar by [[Special:SpecialPages|spesiale bladsye]].",

# General errors
'error'                => 'Fout',
'databaseerror'        => 'Databasisfout',
'dberrortext'          => 'Sintaksisfout in databasisnavraag.
Dit kan moontlik dui op \'n fout in die sagteware.
Die laaste navraag was:
<blockquote><tt>$1</tt></blockquote>
vanuit funksie "<tt>$2</tt>".
Databasis gee foutboodskap "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Sintaksisfout in databasisnavraag.
Die laaste navraag was:
"$1"
vanuit funksie "$2".
Databasis gee foutboodskap: "$3: $4".',
'laggedslavemode'      => 'Waarskuwing: Onlangse wysigings dalk nie in bladsy vervat nie.',
'readonly'             => 'Databasis gesluit',
'enterlockreason'      => 'Rede vir die sluiting,
en beraming van wanneer ontsluiting sal plaas vind',
'readonlytext'         => 'Die {{SITENAME}} databasis is tans gesluit vir nuwe
artikelwysigings, waarskynlik vir roetine databasisonderhoud,
waarna dit terug sal wees na normaal.
Die administreerder wat dit gesluit het se verduideliking:

$1',
'missing-article'      => "Die databasis kon nie soos verwag die teks vir die bladsy genaamd \"\$1\" \$2 kry nie.

Dit gebeur gewoonlik as mens 'n verouderde verskil- of geskiedenis-skakel volg na 'n bladsy wat reeds verwyder is.

Indien dit nie die geval is nie, het u moontlik 'n fout in die sagteware ontdek. Rapporteer asseblief die probleem aan 'n [[Special:ListUsers/sysop|administrateur]], en maak 'n nota van die URL.",
'missingarticle-rev'   => '(weergawe#: $1)',
'missingarticle-diff'  => '(Wysiging: $1, $2)',
'readonly_lag'         => 'Die databasis is outomaties gesluit terwyl die slaafdatabasisse sinchroniseer met die meester',
'internalerror'        => 'Interne fout',
'internalerror_info'   => 'Interne fout: $1',
'fileappenderror'      => 'Kon nie "$1" agteraan "$2" voeg nie.',
'filecopyerror'        => 'Kon nie lêer van "$1" na "$2" kopieer nie.',
'filerenameerror'      => 'Kon nie lêernaam van "$1" na "$2" wysig nie.',
'filedeleteerror'      => 'Kon nie lêer "$1" skrap nie.',
'directorycreateerror' => 'Kon nie gids "$1" skep nie.',
'filenotfound'         => 'Kon nie lêer "$1" vind nie.',
'fileexistserror'      => 'Nie moontlik om na lêer "$1" te skryf: lêer bestaan reeds',
'unexpected'           => 'Onverwagte waarde: "$1"="$2".',
'formerror'            => 'Fout: kon vorm nie stuur nie',
'badarticleerror'      => 'Die aksie kon nie op hierdie bladsy uitgevoer word nie.',
'cannotdelete'         => 'Die bladsy of lêer "$1" kon nie skrap word nie.
Iemand anders het dit moontlik reeds geskrap.',
'badtitle'             => 'Ongeldige titel',
'badtitletext'         => "Die bladsytitel waarvoor gevra is, is ongeldig, leeg, of
'n verkeerd geskakelde tussen-taal of tussen-wiki titel.",
'perfcached'           => "Die volgende inligting is 'n gekaste kopie en mag dalk nie volledig op datum wees nie.",
'perfcachedts'         => 'Die volgende data is gekas. Laaste opdatering: $1',
'querypage-no-updates' => 'Opdatering van hierdie bladsy is huidiglik afgeskakel. Inligting hier sal nie tans verfris word nie.',
'wrong_wfQuery_params' => 'Foutiewe parameters na wfQuery()<br />
Funksie: $1<br />
Navraag: $2',
'viewsource'           => 'Bekyk bronteks',
'viewsourcefor'        => 'vir $1',
'actionthrottled'      => 'Outo-rem op aksie uitgevoer',
'actionthrottledtext'  => "As 'n teen-strooi aksie, word u beperk om hierdie aksie te veel keer in 'n kort tyd uit te voer, en u het hierdie limiet oorskry.
Probeer asseblief weer oor 'n paar minute.",
'protectedpagetext'    => 'Hierdie bladsy is beskerm om redigering te verhoed.',
'viewsourcetext'       => 'U mag die bronteks van hierdie bladsy lees en kopieer:',
'protectedinterface'   => 'Hierdie bladsy verskaf teks vir die koppelvlak van die sagteware, en is beskerm om misbruik te voorkom.',
'editinginterface'     => "'''Waarskuwing:''' U is besig om 'n bladsy te redigeer wat koppelvlakinligting aan die programmatuur voorsien. Wysigings aan hierdie bladsy sal die voorkoms van die gebruikerskoppelvlak vir ander gebruikers beïnvloed. Vir vertalings, oorweeg om eerder [http://translatewiki.net/wiki/Main_Page?setlang=af translatewiki.net] (die vertalingsprojek vir MediaWiki) te gebruik.",
'sqlhidden'            => '(SQL navraag versteek)',
'cascadeprotected'     => 'Hierdie bladsy is beskerm teen redigering omdat dit ingesluit is in die volgende {{PLURAL:$1|bladsy|bladsye}} wat beskerm is met die "kaskade" opsie aangeskakel: $2',
'namespaceprotected'   => "U het nie toestemming om bladsye in die '''$1'''-naamruimte te wysig nie.",
'customcssjsprotected' => "U het nie toestemming om hierdie bladsy te redigeer nie, want dit bevat 'n ander gebruiker se persoonlike verstellings.",
'ns-specialprotected'  => 'Spesiale bladsye kan nie geredigeer word nie.',
'titleprotected'       => "Hierdie titel is beskerm teen skepping deur [[User:$1|$1]].
Die rede gegee is ''$2''.",

# Virus scanner
'virus-badscanner'     => "Slegte konfigurasie: onbekende virusskandeerder: ''$1''",
'virus-scanfailed'     => 'skandering het misluk (kode $1)',
'virus-unknownscanner' => 'onbekende antivirus:',

# Login and logout pages
'logouttext'                 => "'''U is nou uitgeteken'''

U kan aanhou om {{SITENAME}} anoniem te gebruik; of u kan [[Special:UserLogin|inteken]] as dieselfde of 'n ander gebruiker.",
'welcomecreation'            => '== Welkom, $1! ==
U rekening is geskep;
moenie vergeet om u [[Special:Preferences|persoonlike voorkeure vir {{SITENAME}}]] te stel nie.',
'yourname'                   => 'Gebruikersnaam:',
'yourpassword'               => 'Wagwoord:',
'yourpasswordagain'          => 'Herhaal wagwoord',
'remembermypassword'         => 'Onthou my wagwoord oor sessies.',
'yourdomainname'             => 'U domein:',
'externaldberror'            => "'n Databasis fout het voorgekom tydens aanmelding of u het nie toestemming om u eksterne rekening op te dateer nie.",
'login'                      => 'Teken in',
'nav-login-createaccount'    => 'Teken in',
'loginprompt'                => 'U blaaier moet koekies toelaat om by {{SITENAME}} te kan aanteken.',
'userlogin'                  => 'Teken in / registreer',
'logout'                     => 'Teken uit',
'userlogout'                 => 'Teken uit',
'notloggedin'                => 'Nie ingeteken nie',
'nologin'                    => "Nog nie geregistreer nie? '''$1'''.",
'nologinlink'                => "Skep gerus 'n rekening",
'createaccount'              => 'Skep nuwe rekening',
'gotaccount'                 => "Het u reeds 'n rekening? '''$1'''.",
'gotaccountlink'             => 'Teken in',
'createaccountmail'          => 'deur e-pos',
'badretype'                  => 'Die ingetikte wagwoorde is nie dieselfde nie.',
'userexists'                 => "Die gebruikersnaam wat u gekies het is reeds geneem.
Kies asseblief 'n ander naam.",
'loginerror'                 => 'Intekenfout',
'createaccounterror'         => "Kon nie 'n rekening skep nie: $1",
'nocookiesnew'               => 'Die gebruikersrekening is geskep, maar u is nie ingeteken nie.
{{SITENAME}} gebruik koekies om gebruikers in te teken.
U rekenaar laat tans nie koekies toe nie.
Stel u rekenaar om dit te aanvaar, dan kan u met u nuwe naam en wagwoord inteken.',
'nocookieslogin'             => '{{SITENAME}} gebruik koekies vir die aanteken van gebruikers, maar u blaaier laat dit nie toe nie. Skakel dit asseblief aan en probeer weer.',
'noname'                     => 'Ongeldige gebruikersnaam.',
'loginsuccesstitle'          => 'Suksesvolle intekening',
'loginsuccess'               => 'U is ingeteken by {{SITENAME}} as "$1".',
'nosuchuser'                 => 'Die gebruiker "$1" bestaan nie. 
Gebruikersname is gevoelig vir hoofletters.
Maak seker dit is reg gespel of [[Special:UserLogin/signup|skep \'n nuwe rekening]].',
'nosuchusershort'            => 'Daar is geen gebruikersnaam "<nowiki>$1</nowiki>" nie. Maak seker dit is reg gespel.',
'nouserspecified'            => "U moet 'n gebruikersnaam spesifiseer.",
'wrongpassword'              => 'Ongeldige wagwoord, probeer weer.',
'wrongpasswordempty'         => 'Die wagwoord was leeg. Probeer asseblief weer.',
'passwordtooshort'           => 'Wagwoorde moet ten minste {{PLURAL:$1|1 karakter|$1 karakters}} lank wees.',
'password-name-match'        => 'U wagwoord mag nie dieselfde as u gebruikersnaam wees nie.',
'mailmypassword'             => "E-pos my 'n nuwe wagwoord",
'passwordremindertitle'      => 'Wagwoordwenk van {{SITENAME}}',
'passwordremindertext'       => 'Iemand (waarskynlik u vanaf IP-adres $1) het \'n nuwe wagwoord vir {{SITENAME}} ($4) gevra. \'n Tydelike wagwoord is vir gebruiker "$2" geskep. Die nuwe wagwoord is "$3". U kan met die tydelike wagwoord aanteken en \'n nuwe wagwoord stel. Die tydelike wagwoord sal na {{PLURAL:$5|een dag|$5 dae}} verval.

Indien iemand anders hierdie navraag gerig het, of u het die wagwoord intussen onthou en wil nie meer die wagwoord wysig nie, kan u die boodskap ignoreer en voortgaan om die ou wagwoord te gebruik.',
'noemail'                    => 'Daar is geen e-posadres vir gebruiker "$1" nie.',
'noemailcreate'              => "U moet 'n geldige e-posadres verskaf",
'passwordsent'               => 'Nuwe wagwoord gestuur na e-posadres vir "$1".
Teken asseblief in na u dit ontvang het.',
'blocked-mailpassword'       => 'U IP-adres is tans teen wysigings geblokkeer. Om verdere misbruik te voorkom is dit dus nie moontlik om die wagwoordherwinningfunksie te gebruik nie.',
'eauthentsent'               => "'n Bevestigingpos is gestuur na die gekose e-posadres.
Voordat ander pos na die adres gestuur word,
moet die instruksies in bogenoemde pos gevolg word om te bevestig dat die adres werklik u adres is.",
'throttled-mailpassword'     => "Daar is reeds 'n wagwoordwenk in die laaste {{PLURAL:$1|uur|$1 ure}} gestuur.
Om misbruik te voorkom, word slegs een wagwoordwenk per {{PLURAL:$1|uur|$1 ure}} gestuur.",
'mailerror'                  => 'Fout tydens e-pos versending: $1',
'acct_creation_throttle_hit' => "Besoekers aan hierdie wiki wat u IP-adres gebruik het reeds {{PLURAL:$1|'n rekening|$1 rekeninge}} in die laaste dag geskep, wat die maksimum toelaatbaar is vir die periode. Dus kan besoekers wat hierdie IP-adres gebruik tans nie meer nuwe gebruikers registreer nie.",
'emailauthenticated'         => 'U e-posadres is op $2 om $3 bevestig.',
'emailnotauthenticated'      => 'U e-poasadres is <strong>nog nie bevestig nie</strong>. Geen e-pos sal gestuur word vir die volgende funksies nie.',
'noemailprefs'               => "Spesifiseer 'n eposadres vir hierdie funksies om te werk.",
'emailconfirmlink'           => 'Bevestig u e-posadres',
'invalidemailaddress'        => "Die e-posadres is nie aanvaar nie, aangesien dit 'n ongeldige formaat blyk te hê.
Voer asseblief 'n geldige e-posadres in, of laat die veld leeg.",
'accountcreated'             => 'Rekening geskep',
'accountcreatedtext'         => 'Die rekening vir gebruiker $1 is geskep.',
'createaccount-title'        => 'Rekeningskepping vir {{SITENAME}}',
'createaccount-text'         => 'Iemand het \'n rekening vir u e-posadres geskep by {{SITENAME}} ($4), met die naam "$2" en "$3". as die wagwoord.
U word aangeraai om in te teken so gou as moontlik u wagwoord te verander.

Indien hierdie rekening foutief geskep is, kan u hierdie boodskap ignoreer.',
'usernamehasherror'          => "'n Gebruikersnaam mag nie 'n hekkie-karakter (#) in hê nie",
'login-throttled'            => "U het al te veel kere met 'n ongeldige wagwoord probeer aanteken.
Wag asseblief alvorens u weer probeer.",
'loginlanguagelabel'         => 'Taal: $1',

# Password reset dialog
'resetpass'                 => 'Verander wagwoord',
'resetpass_announce'        => "U het aangeteken met 'n tydelike e-poskode.
Om voort te gaan moet u 'n nuwe wagwoord hier kies:",
'resetpass_header'          => 'Verander wagwoord',
'oldpassword'               => 'Ou wagwoord',
'newpassword'               => 'Nuwe wagwoord',
'retypenew'                 => 'Tik nuwe wagwoord weer in',
'resetpass_submit'          => 'Stel wagwoord en teken in',
'resetpass_success'         => 'U wagwoord is suksesvol gewysig! Besig om u in te teken ...',
'resetpass_forbidden'       => 'Wagwoorde kannie gewysig word nie.',
'resetpass-no-info'         => 'U moet ingeteken wees om hierdie bladsy direk te kan gebruik.',
'resetpass-submit-loggedin' => 'Verander wagwoord',
'resetpass-wrong-oldpass'   => "Die huidige of tydelike wagwoord is ongeldig.
U het moontlik reeds u wagwoord gewysig of 'n nuwe tydelike wagwoord aangevra.",
'resetpass-temp-password'   => 'Tydelike wagwoord:',

# Edit page toolbar
'bold_sample'     => 'Vetgedrukte teks',
'bold_tip'        => 'Vetdruk',
'italic_sample'   => 'Skuinsgedrukte teks',
'italic_tip'      => 'Skuinsdruk',
'link_sample'     => 'Skakelnaam',
'link_tip'        => 'Interne skakel',
'extlink_sample'  => 'http://www.example.com skakel se titel',
'extlink_tip'     => 'Eksterne skakel (onthou http:// vooraan)',
'headline_sample' => 'Opskrif',
'headline_tip'    => 'Vlak 2-opskrif',
'math_sample'     => 'Plaas formule hier',
'math_tip'        => 'Wiskundige formule (LaTeX)',
'nowiki_sample'   => 'Plaas ongeformatteerde teks hier',
'nowiki_tip'      => 'Ignoreer wiki-formattering',
'image_sample'    => 'Voorbeeld.jpg',
'image_tip'       => 'Beeld/prentjie/diagram',
'media_sample'    => 'Voorbeeld.ogg',
'media_tip'       => 'Skakel na ander tipe medialêer',
'sig_tip'         => 'Handtekening met datum',
'hr_tip'          => 'Horisontale streep (selde nodig)',

# Edit pages
'summary'                          => 'Opsomming:',
'subject'                          => 'Onderwerp/opskrif:',
'minoredit'                        => 'Klein wysiging',
'watchthis'                        => 'Hou bladsy dop',
'savearticle'                      => 'Stoor bladsy',
'preview'                          => 'Voorskou',
'showpreview'                      => 'Wys voorskou',
'showlivepreview'                  => 'Lewendige voorskou',
'showdiff'                         => 'Wys veranderings',
'anoneditwarning'                  => "'''Waarskuwing:''' Aangesien u nie aangeteken is nie, sal u IP-adres in dié blad se wysigingsgeskiedenis gestoor word.",
'missingsummary'                   => "'''Onthou:''' Geen opsomming van die wysiging is verskaf nie. As \"Stoor\" weer geklik word, word die wysiging sonder opsomming gestoor.",
'missingcommenttext'               => 'Tik die opsomming onder.',
'missingcommentheader'             => "'''Let op:''' U het geen onderwerp/opskrif vir die opmerking verskaf nie. As u weer op \"Stoor\" klik, sal u wysiging sonder die onderwerp/opskrif gestoor word.",
'summary-preview'                  => 'Opsomming nakijken:',
'subject-preview'                  => 'Onderwerp/ opskrif voorskou:',
'blockedtitle'                     => 'Gebruiker is geblokkeer',
'blockedtext'                      => "<big>'''U gebruikersnaam of IP-adres is geblokkeer.'''</big>

Die blokkering is deur $1 gedoen.
Die rede gegee is ''$2''.

* Begin van blokkade: $8
* Blokkade eindig: $6
* Blokkering gemik op: $7

U mag $1 of een van die ander [[{{MediaWiki:Grouppage-sysop}}|administreerders]] kontak om dit te bespreek.
U kan nie die 'e-pos hierdie gebruiker'-opsie gebruik tensy 'n geldige e-pos adres gespesifiseer is in u [[Special:Preferences|rekening voorkeure]] en u nie geblokkeer is om dit te gebruik nie. 
U huidige IP-adres is $3, en die blokkering ID is #$5. 
Sluit asseblief een of albei hierdie verwysings in by enige navrae.",
'autoblockedtext'                  => "U IP-adres is outomaties geblok omdat dit deur 'n gebruiker gebruik was, wat deur $1 geblokkeer is. 
Die rede verskaf is:

:''$2''

* Aanvang van blok: $8
* Einde van blok: $6
* Bedoelde blokkeerder: $7

U kan die blok met $1 of enige van die [[{{MediaWiki:Grouppage-sysop}}|administrateurs]] bespreek.

Neem kennis dat u slegs die 'e-pos die gebruiker' funksionaliteit kan gebruik as u 'n geldige e-posadres het in u [[Special:Preferences|voorkeure]] het, en die gebruik daarvan is nie ook geblokkeer is nie.

U huidige IP-adres is $3 en die blokkadenommer is #$5.
Vermeld asseblief die bovermelde bloknommer as u die saak rapporteer,",
'blockednoreason'                  => 'geen rede verskaf nie',
'blockedoriginalsource'            => "Die bronteks van '''$1''' word onder gewys:",
'blockededitsource'                => "Die teks van '''u wysigings''' aan '''$1''' word hier onder vertoon:",
'whitelistedittitle'               => 'U moet aangeteken wees om te kan redigeer.',
'whitelistedittext'                => 'U moet $1 om bladsye te wysig.',
'confirmedittext'                  => 'U moet u e-posadres bevestig voor u bladsye wysig. Verstel en bevestig asseblief u e-posadres by u [[Special:Preferences|voorkeure]].',
'nosuchsectiontitle'               => 'Afdeling bestaan nie',
'nosuchsectiontext'                => "U probeer 'n afdeling wysig wat nie bestaan nie.
Omdat die afdeling $1 nie bestaan nie, kan u wysigings nie gestoor word nie.",
'loginreqtitle'                    => 'Inteken Benodig',
'loginreqlink'                     => 'teken in',
'loginreqpagetext'                 => 'U moet $1 om ander bladsye te bekyk.',
'accmailtitle'                     => 'Wagwoord gestuur.',
'accmailtext'                      => "'n Lukraakgegenereerde wagwoord vir [[User talk:$1|$1]] is na $2 gestuur.

Die wagwoord vir hierdie nuwe gebruiker kan verander word op die ''[[Special:ChangePassword|verander wagwoord]]'' bladsy nadat ingeteken is.",
'newarticle'                       => '(Nuut)',
'newarticletext'                   => "Die bladsy waarna geskakel is, bestaan nie.
Om 'n nuwe bladsy te skep, tik in die invoerboks hier onder. Lees die [[{{MediaWiki:Helppage}}|hulpbladsy]]
vir meer inligting.
Indien u per ongeluk hier is, gebruik u blaaier se '''terug'''- knoppie.",
'anontalkpagetext'                 => "----''Hierdie is die besprekingsblad vir 'n anonieme gebruiker wat nog nie 'n rekening geskep het nie of wat dit nie gebruik nie. Daarom moet ons sy/haar numeriese IP-adres gebruik vir identifikasie. Só 'n adres kan deur verskeie gebruikers gedeel word. Indien u 'n anonieme gebruiker is wat voel dat ontoepaslike kommentaar teen u gerig is, [[Special:UserLogin/signup|skep 'n rekening]] of [[Special:UserLogin|teken in]] om verwarring met ander anonieme gebruikers te voorkom.''",
'noarticletext'                    => 'Hierdie bladsy bevat geen teks nie.
U kan [[Special:Search/{{PAGENAME}}|na hierdie bladsytitel in ander bladsye soek]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} die verwante logs deursoek], of [{{fullurl:{{FULLPAGENAME}}|action=edit}} hierdie bladsy wysig]</span>.',
'noarticletext-nopermission'       => 'Daar is tans geen teks in hierdie bladsy nie. U kan vir die bladsytitel [[Special:Search/{{PAGENAME}}|in ander bladsye soek]] of
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} die verwante logboeke deursoek]</span>.',
'userpage-userdoesnotexist'        => 'U is besig om \'n gebruikersblad wat nie bestaan nie te wysig (gebruiker "$1"). Maak asseblief seker of u die bladsy wil skep/ wysig.',
'userpage-userdoesnotexist-view'   => 'Die gebruiker "$1" is nie geregistreer nie.',
'clearyourcache'                   => "'''Let wel''': Na die voorkeure gestoor is, moet u blaaier se kasgeheue verfris word om die veranderinge te sien: '''Mozilla:''' klik ''Reload'' (of ''Ctrl-R''), '''IE / Opera:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Ctrl-R''.",
'usercssyoucanpreview'             => "'''Wenk:''' Gebruik die \"Wys voorskou\"-knoppie om u nuwe CSS te toets voor u stoor.",
'userjsyoucanpreview'              => "'''Wenk:''' Gebruik die \"Wys voorskou\"-knoppie om u nuwe JS te toets voor u stoor.",
'usercsspreview'                   => "'''Onthou hierdie is slegs 'n voorskou van u persoonlike CSS.'''
'''Dit is nog nie gestoor nie!'''",
'userjspreview'                    => "'''Onthou hierdie is slegs 'n toets/voorskou van u gebruiker-JavaScript, dit is nog nie gestoor nie.'''",
'userinvalidcssjstitle'            => "'''Waarskuwing:''' daar is nie 'n omslag \"\$1\" nie.
Onthou dat u eie .css- en .js-bladsye met 'n kleinletter begin, byvoorbeeld {{ns:user}}:Naam/monobook.css in plaas van {{ns:user}}:Naam/Monobook.css.",
'updated'                          => '(Gewysig)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Onthou dat hierdie slegs 'n voorskou is en nog nie gestoor is nie!'''",
'previewconflict'                  => 'Hierdie voorskou vertoon die teks in die boonste teksarea soos dit sou lyk indien u die bladsy stoor.',
'session_fail_preview'             => "'''Jammer! Weens verlies aan sessie-inligting is die wysiging nie verwerk nie.
Probeer asseblief weer. As dit steeds nie werk nie, probeer om [[Special:UserLogout|af te teken]] en dan weer aan te teken.'''",
'session_fail_preview_html'        => "'''Jammer! U wysigings is nie verwerk nie omdat sessie-data verlore gegaan het.'''

''Omrede rou HTML hier by {{SITENAME}} ingevoer kan word, kan die voorskou nie gesien word nie ter beskerming teen aanvalle met JavaScript.''

'''As dit 'n regmatige wysiging is, probeer asseblief weer. As dit daarna nog nie werk nie, [[Special:UserLogout|teken dan af]] en weer aan.'''",
'editing'                          => 'Besig om $1 te wysig',
'editingsection'                   => 'Besig om $1 (onderafdeling) te wysig',
'editingcomment'                   => 'Besig om $1 te wysig (nuwe opskrif)',
'editconflict'                     => 'Wysigingskonflik: $1',
'explainconflict'                  => 'Iemand anders het hierdie bladsy gewysig sedert u dit begin wysig het.
Die boonste invoerboks het die teks wat tans bestaan.
U wysigings word in die onderste invoerboks gewys.
U sal u wysigings moet saamsmelt met die huidige teks.
<strong>Slegs</strong> die teks in die boonste invoerboks sal gestoor word wanneer u "Stoor bladsy" druk.<br />',
'yourtext'                         => 'U teks',
'storedversion'                    => 'Gestoorde weergawe',
'nonunicodebrowser'                => "'''Waarskuwing: U webblaaier ondersteun nie Unikode nie.'''
Die MediaWiki-sagteware hou hiermee rekening sodat u bladsye veilig kan wysig: nie-ASCII karakters word in die wysigingsvenster as heksadesimale kodes weergegee.",
'editingold'                       => "'''WAARSKUWING: U is besig om 'n ouer weergawe van hierdie bladsy te wysig.
As u dit stoor, sal enige wysigings sedert hierdie een weer uitgewis word.'''",
'yourdiff'                         => 'Wysigings',
'copyrightwarning'                 => "Alle bydraes aan {{SITENAME}} word beskou as beskikbaar gestel onder die $2 (lees $1 vir meer inligting).
As u nie wil toelaat dat u teks deur ander persone gewysig of versprei word nie, moet dit asseblief nie hier invoer nie.<br />
Hierdeur beloof u ons dat u die byvoegings self geskryf het, of gekopieer het van publieke domein of soortgelyke vrye bronne.
'''MOENIE WERK WAT DEUR KOPIEREG BESKERM WORD HIER PLAAS SONDER TOESTEMMING NIE!'''",
'copyrightwarning2'                => "Enige bydraes op {{SITENAME}} mag genadeloos gewysig of selfs verwyder word; indien u dit nie met u bydrae wil toelaat nie, moenie dit hier bylas nie.<br />
Deur enigiets hier te plaas, beloof u dat u dit self geskryf het, of dat dit gekopieer is vanuit \"publieke domein\" of soortgelyke vrye bronne (sien \$1 vir details).
'''MOENIE WERK WAT DEUR KOPIEREG BESKERM WORD HIER PLAAS SONDER TOESTEMMING NIE!'''",
'longpagewarning'                  => 'WAARSKUWING: Hierdie bladsy is $1 kG groot.
Probeer asseblief die bladsy verkort en die detail na subartikels skuif sodat dit nie 32 kG oorskry nie.',
'longpageerror'                    => "'''FOUT: die teks wat u bygevoeg het is $1 kilogrepe groot, wat groter is as die maximum van $2 kilogrepe.
Die bladsy kan nie gestoor word nie.'''",
'readonlywarning'                  => "'''WAARSKUWING: Die databasis is gesluit vir onderhoud. Dus sal u nie nou u wysigings kan stoor nie. Dalk wil u die teks plak in 'n lêer en stoor vir later.'''

Een administrateur het die databasis geblokkeer vir hierdie rede: $1",
'protectedpagewarning'             => "'''WAARSKUWING: Hierdie blad is beskerm, en slegs administrateurs kan die inhoud verander.'''",
'semiprotectedpagewarning'         => "'''Let wel:''' Hierdie artikel is beskerm sodat slegs ingetekende gebruikers dit kan wysig.",
'cascadeprotectedwarning'          => "'''Waarskuwing:''' Die bladsy was beveilig sodat dit slegs deur administrateurs gewysig kan word, omrede dit ingesluit is in die volgende {{PLURAL:$1|bladsy|bladsye}} wat kaskade-beskerming geniet:",
'titleprotectedwarning'            => "'''WAARSKUWING: Die bladsy is gesluit sodat net gebruikers met [[Special:ListGroupRights|spesiale regte]] dit sal kan skep.'''",
'templatesused'                    => 'Hierdie bladsy {{PLURAL:$1|gebruik sjabloon|gebruik sjablone}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Sjabloon|Sjablone}} gebruik in hierdie voorskou:',
'templatesusedsection'             => 'Die volgende {{PLURAL:$1|sjabloon|sjablone}} word in hierdie afdeling gebruik:',
'template-protected'               => '(beskermd)',
'template-semiprotected'           => '(half-beskerm)',
'hiddencategories'                 => "Hierdie bladsy is 'n lid van {{PLURAL:$1|1 versteekte kategorie|$1 versteekte kategorië}}:",
'nocreatetitle'                    => 'Bladsy skepping beperk',
'nocreatetext'                     => '{{SITENAME}} het die skep van nuwe bladsye beperk.
U kan slegs bestaande bladsye wysig, of u kan [[Special:UserLogin|aanteken of registreer]].',
'nocreate-loggedin'                => 'U het nie regte om nuwe blaaie te skep nie.',
'permissionserrors'                => 'Toestemmings Foute',
'permissionserrorstext'            => 'U het nie toestemming om hierdie te doen nie, om die volgende {{PLURAL:$1|rede|redes}}:',
'permissionserrorstext-withaction' => 'U het geen regte om $2, vir die volgende {{PLURAL:$1|rede|redes}}:',
'recreate-moveddeleted-warn'       => "'''Waarskuwing: U skep 'n bladsy wat vantevore verwyder was.'''

U moet besluit of dit wys is om voort te gaan en aan die bladsy te werk. 
Die verwyderingslogboek vir die blad word hier onder vertoon vir u gerief:",
'moveddeleted-notice'              => 'Hierdie bladsy is verwyder.
Die skrap- en skuif-logboeke word hieronder ter inligting weergegee.',
'log-fulllog'                      => 'Wys volledige logboek',
'edit-hook-aborted'                => "Die wysiging is deur 'n hoek gekanselleer.
Geen verduideliking is verskaf nie.",
'edit-gone-missing'                => 'Die bladsy is nie gewysig nie.
Dit lyk of dit verwyder is.',
'edit-conflict'                    => 'Wysigingskonflik',
'edit-no-change'                   => 'U wysiging was geignoreer omdat die teks nie verander is nie.',
'edit-already-exists'              => 'Die bladsy is nie geskep nie.
Dit bestaan alreeds.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Waarskuwing: Die bladsy gebruik te veel duur ontlederfunksies.

Daar is {{PLURAL:$1|$1 funksie|$1 funksies}}, terwyl die bladsy minder as $2 moet hê.',
'expensive-parserfunction-category'       => 'Bladsye wat te veel duur ontlederfunkies gebruik',
'post-expand-template-inclusion-warning'  => "'''Waarskuwing:''' Die maksimum toelaatbare grootte vir die insluiting van sjablone is oorskry.
Sommige van die sjablone sal nie ingesluit word nie.",
'post-expand-template-inclusion-category' => 'Bladsye waar die maksimum sjabloon insluit grootte oorskry is',
'post-expand-template-argument-category'  => 'Bladsye met weggelate sjabloonargumente',
'parser-template-loop-warning'            => "Sjablone is in 'n oneindige lus: [[$1]]",
'parser-template-recursion-depth-warning' => 'Die rekursiediepte vir sjablone is oorskry ($1)',

# "Undo" feature
'undo-success' => 'Die wysiging kan ongedaan gemaak word.
Kontroleer die vergelyking hieronder om seker te maak dis wat u wil doen, en stoor dan om die terugrol te voltooi.',
'undo-failure' => 'Die wysiging kan nie ongedaan gemaak word nie omdat dit met intermediêre wysigings bots.',
'undo-norev'   => 'Die wysiging kon nie ongedaan gemaak word nie omdat dit nie bestaan nie of reeds verwyder is.',
'undo-summary' => 'Rol weergawe $1 deur [[Special:Contributions/$2|$2]] ([[User talk:$2|bespreek]]) terug.',

# Account creation failure
'cantcreateaccounttitle' => 'Kan nie rekening skep nie',
'cantcreateaccount-text' => "Die registrasie van nuwe rekeninge vanaf die IP-adres ('''$1''') is geblok deur [[User:$3|$3]].

Die rede verskaf deur $3 is ''$2''",

# History pages
'viewpagelogs'           => 'Bekyk logboeke vir hierdie bladsy',
'nohistory'              => 'Daar is geen wysigingsgeskiedenis vir hierdie bladsy nie.',
'currentrev'             => 'Huidige wysiging',
'currentrev-asof'        => 'Huidige wysiging per $1',
'revisionasof'           => 'Wysiging soos op $1',
'revision-info'          => 'Weergawe soos op $1 deur $2',
'previousrevision'       => '← Ouer weergawe',
'nextrevision'           => 'Nuwer weergawe →',
'currentrevisionlink'    => 'Huidige weergawe',
'cur'                    => 'huidige',
'next'                   => 'volgende',
'last'                   => 'vorige',
'page_first'             => 'eerste',
'page_last'              => 'laaste',
'histlegend'             => 'Byskrif: (huidige) = verskil van huidige weergawe,
(vorige) = verskil van vorige weergawe, M = klein wysiging',
'history-fieldset-title' => 'Blaai deur geskiedenis',
'histfirst'              => 'Oudste',
'histlast'               => 'Nuutste',
'historysize'            => '({{PLURAL:$1|1 greep|$1 grepe}})',
'historyempty'           => '(leeg)',

# Revision feed
'history-feed-title'          => 'Weergawegeskiedenis',
'history-feed-description'    => 'Wysigingsgeskiedenis vir die bladsy op die wiki',
'history-feed-item-nocomment' => '$1 by $2',
'history-feed-empty'          => 'Die verlangde bladsy bestaan nie.
Dit was moontlik geskrap of geskuif.
[[Special:Search|Deursoek die wiki]] vir relevante bladsye.',

# Revision deletion
'rev-deleted-comment'         => '(opsomming geskrap)',
'rev-deleted-user'            => '(gebruikersnaam geskrap)',
'rev-deleted-event'           => '(stawingsaksie verwyder)',
'rev-deleted-text-permission' => "Die weergawe van die bladsy is '''verwyder'''. 
Vir meer besonderhede, raadpleeg die [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} skrapingslogboek].",
'rev-delundel'                => 'wys/versteek',
'rev-showdeleted'             => 'wys',
'revisiondelete'              => 'Verwyder/herstel weergawes',
'revdelete-nooldid-title'     => 'Ongeldige teiken weergawe',
'revdelete-nologtype-title'   => 'Geen logboektipe verskaf nie',
'revdelete-nologtype-text'    => "U het nie 'n logboektipe verskaf om hierdie handeling op uit te voer nie.",
'revdelete-nologid-title'     => 'Ongeldige logboekreël',
'revdelete-no-file'           => 'Die gespesifiseerde lêer bestaan nie.',
'revdelete-show-file-confirm' => 'Is u seker u wil die geskrapte weergawe van die lêer "<nowiki>$1</nowiki>" van $2 om $3 sien?',
'revdelete-show-file-submit'  => 'Ja',
'revdelete-selected'          => "'''Geselekteerde {{PLURAL:$2|wysiging|wysigings}} vir [[:$1]]:'''",
'logdelete-selected'          => "'''Geselekteerde {{PLURAL:$1|logboek aksie|logboek aksies}}:'''",
'revdelete-legend'            => 'Stel sigbaarheid beperkinge',
'revdelete-hide-text'         => 'Steek hersiening teks weg',
'revdelete-hide-name'         => 'Steek aksie en teiken weg',
'revdelete-hide-comment'      => 'Versteek wysigopsomming',
'revdelete-hide-user'         => 'Steek redigeerder se gebruikersnaam/IP weg',
'revdelete-hide-restricted'   => 'Onderdruk data van administrateurs asook andere',
'revdelete-suppress'          => 'Onderdruk data van administrateurs en ander.',
'revdelete-hide-image'        => 'Steek lêer inhoud weg',
'revdelete-unsuppress'        => 'Verwyder beperkinge op herstelde weergawes',
'revdelete-log'               => 'Rede vir skrapping:',
'revdelete-submit'            => 'Pas op gekose weergawe toe',
'revdelete-logentry'          => 'sigbaarheid van weergawe is gewysig vir [[$1]]',
'logdelete-logentry'          => 'verander sigbaarheid van gebeurtenis [[$1]]',
'revdelete-success'           => "'''Die sigbaarheid van die wysiging is suksesvol opgedateer.'''",
'revdelete-failure'           => "'''Die sigbaarheid van die wysiging kon nie opgedateer word nie:'''
$1",
'logdelete-success'           => "'''Sigbaarheid van die gebeurtenis suksesvol gestel.'''",
'logdelete-failure'           => "'''Sigbaarheid kon nie vir die logboekreël gestel word nie:'''
$1",
'revdel-restore'              => 'Verander sigbaarheid',
'pagehist'                    => 'Bladsy geskiedenis',
'deletedhist'                 => 'Verwyderde geskiedenis',
'revdelete-content'           => 'inhoud',
'revdelete-summary'           => 'redigeringsopsomming',
'revdelete-uname'             => 'gebruikersnaam',
'revdelete-restricted'        => 'beperkings is aan administrateurs opgelê',
'revdelete-unrestricted'      => 'beperkings vir administrateurs is opgehef',
'revdelete-hid'               => '$1 verskuil',
'revdelete-unhid'             => '$1 onverskuil',
'revdelete-log-message'       => '$1 vir $2 {{PLURAL:$2|weergawe|weergawes}}',
'logdelete-log-message'       => '$1 vir $2 {{PLURAL:$2|gebeurtenis|gebeurtenisse}}',
'revdelete-modify-missing'    => 'Fout met die wysiging van item ID $1: dit is nie in die databasis nie!',
'revdelete-no-change'         => "'''Waarskuwing:''' die item van $1 om $2 uur het reeds die gevraagde sigbaarheidsinstellings.",
'revdelete-reason-dropdown'   => '* Algemene redes vir skrapping
** Skending van outeursreg
** Onbetaamlike persoonlike inligting',
'revdelete-otherreason'       => 'Ander rede:',
'revdelete-reasonotherlist'   => 'Ander rede',
'revdelete-edit-reasonlist'   => 'Wysig skrap redes',
'revdelete-offender'          => 'Outeur van hersiening:',

# Suppression log
'suppressionlog' => 'Verbergingslogboek',

# History merging
'mergehistory'                     => 'Geskiedenis van bladsy samesmeltings',
'mergehistory-box'                 => 'Versmelt weergawes van twee bladsye:',
'mergehistory-from'                => 'Bronbladsy:',
'mergehistory-into'                => 'Bestemmingsbladsy:',
'mergehistory-list'                => 'Versmeltbare wysigingsgeskiedenis',
'mergehistory-go'                  => 'Wys versmeltbare wysigings',
'mergehistory-submit'              => 'Versmelt weergawes',
'mergehistory-empty'               => 'Geen weergawes kan versmelt word nie.',
'mergehistory-success'             => '$3 {{PLURAL:$3|weergawe|weergawes}} van [[:$1]] is suksesvol versmelt met [[:$2]].',
'mergehistory-no-source'           => 'Bronbladsy $1 bestaan nie.',
'mergehistory-no-destination'      => 'Bestemmingsbladsy $1 bestaan nie.',
'mergehistory-invalid-source'      => "Bronbladsy moet 'n geldige titel wees.",
'mergehistory-invalid-destination' => "Bestemmingsbladsy moet 'n geldige titel wees.",
'mergehistory-autocomment'         => '[[:$1]] saamgevoeg by [[:$2]]',
'mergehistory-comment'             => '[[:$1]] saamgevoeg by [[:$2]]: $3',
'mergehistory-same-destination'    => 'Die oorsprong en bestemming kan nie dieselfde wees nie',
'mergehistory-reason'              => 'Rede:',

# Merge log
'mergelog'           => 'Versmeltingslogboek',
'pagemerge-logentry' => 'versmelt [[$1]] met [[$2]] (weergawes tot en met $3)',
'revertmerge'        => 'Samesmelting ongedaan maak',

# Diffs
'history-title'            => 'Weergawegeskiedenis van "$1"',
'difference'               => '(Verskil tussen weergawes)',
'lineno'                   => 'Lyn $1:',
'compareselectedversions'  => 'Vergelyk gekose weergawes',
'showhideselectedversions' => 'Wys/versteek gekose weergawes',
'editundo'                 => 'maak ongedaan',
'diff-multi'               => '({{PLURAL:$1|Een tussenin wysiging|$1 tussenin wysigings}} word nie gewys nie.)',

# Search results
'searchresults'                    => 'soekresultate',
'searchresults-title'              => 'Soekresultate vir "$1"',
'searchresulttext'                 => 'Vir meer inligting oor {{SITENAME}} soekresultate, lees [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'U soek vir \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|alle bladsye wat met "$1" begin]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle bladsye wat aan "$1" skakel]])',
'searchsubtitleinvalid'            => 'Vir navraag "$1"',
'toomanymatches'                   => "Te veel resultate. Probeer asseblief 'n ander soektog.",
'titlematches'                     => 'Artikeltitel resultate',
'notitlematches'                   => 'Geen artikeltitel resultate nie',
'textmatches'                      => 'Artikelteks resultate',
'notextmatches'                    => 'Geen artikelteks resultate nie',
'prevn'                            => 'vorige {{PLURAL:$1|$1}}',
'nextn'                            => 'volgende {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Vorige {{PLURAL:$1|resultaat|$1 resultate}}',
'nextn-title'                      => 'Volgende {{PLURAL:$1|resultaat|$1 resultate}}',
'shown-title'                      => '$1 {{PLURAL:$1|resultaat|resultate}} per bladsy',
'viewprevnext'                     => 'Kyk na ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Soekopsies',
'searchmenu-exists'                => "'''Daar is reeds 'n bladsy genaamd \"[[:\$1]]\" op die wiki'''",
'searchmenu-new'                   => "'''Skep die bladsy \"[[:\$1]]\" op hierdie wiki'''",
'searchhelp-url'                   => 'Help:Inhoud',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Wys bladsye wat met die voorvoegsel begin]]',
'searchprofile-articles'           => 'Inhoudelike bladsye',
'searchprofile-project'            => 'Hulp- en projekbladsye',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Uitgebreid',
'searchprofile-articles-tooltip'   => 'Soek in $1',
'searchprofile-project-tooltip'    => 'Soek in $1',
'searchprofile-images-tooltip'     => 'Soek na lêers',
'searchprofile-everything-tooltip' => 'Soek deur alle inhoud (ook besprekingsbladsye)',
'searchprofile-advanced-tooltip'   => 'Soek in spesifieke naamruimtes',
'search-result-size'               => '$1 ({{PLURAL:$2|1 woord|$2 woorde}})',
'search-result-score'              => 'Relevansie: $1%',
'search-redirect'                  => '(aanstuur $1)',
'search-section'                   => '(afdeling $1)',
'search-suggest'                   => 'Het u $1 bedoel?',
'search-interwiki-caption'         => 'Suster projekte',
'search-interwiki-default'         => '$1 resultate:',
'search-interwiki-more'            => '(meer)',
'search-mwsuggest-enabled'         => 'met voorstelle',
'search-mwsuggest-disabled'        => 'geen voorstelle',
'search-relatedarticle'            => 'Verwante',
'mwsuggest-disable'                => 'Deaktiveer AJAX-voorstelle',
'searcheverything-enable'          => 'Soek in alle naamruimtes',
'searchrelated'                    => 'verwante',
'searchall'                        => 'alle',
'showingresults'                   => "Hier volg {{PLURAL:$1|'''1''' resultaat|'''$1''' resultate}} wat met #'''$2''' begin.",
'showingresultsnum'                => "Hier onder {{PLURAL:$3|is '''1''' resultaat|is '''$3''' resultate}} vanaf #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultaat '''$1''' van '''$3'''|Resultate '''$1 - $2''' van '''$3'''}} vir '''$4'''",
'nonefound'                        => "<strong>Opmerking</strong>: nie alle naamruimtes word by verstek deursoek nie. 
As die voorvoegsel \"''all:''\" in 'n soekopdrag gebruik word, word alle bladsye deursoek (inklusief besprekengsbladsye, sjablone, ensovoorts). 
U kan ook 'n naamruimte as voorvoegsel gebruik.",
'search-nonefound'                 => 'Daar is geen resultate vir u soekopdrag nie.',
'powersearch'                      => 'Gevorderde soektog',
'powersearch-legend'               => 'Gevorderde soektog',
'powersearch-ns'                   => 'Soek in naamruimtes:',
'powersearch-redir'                => 'Wys aanstuurbladsye',
'powersearch-field'                => 'Soek vir',
'powersearch-togglelabel'          => 'Kies',
'powersearch-toggleall'            => 'Alles',
'powersearch-togglenone'           => 'Geen',
'search-external'                  => 'Eksterne soektog',
'searchdisabled'                   => '{{SITENAME}} se soekfunksie is tans afgeskakel ter wille van werkverrigting. Gebruik gerus intussen Google of Yahoo! Let daarop dat hulle indekse van die {{SITENAME}}-inhoud verouderd mag wees.',

# Quickbar
'qbsettings'               => 'Snelbalkvoorkeure',
'qbsettings-none'          => 'Geen',
'qbsettings-fixedleft'     => 'Links vas.',
'qbsettings-fixedright'    => 'Regs vas.',
'qbsettings-floatingleft'  => 'Dryf links.',
'qbsettings-floatingright' => 'Dryf regs.',

# Preferences page
'preferences'                   => 'Voorkeure',
'mypreferences'                 => 'My voorkeure',
'prefs-edits'                   => 'Aantal wysigings:',
'prefsnologin'                  => 'Nie ingeteken nie',
'prefsnologintext'              => 'U moet <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} aanteken] om voorkeure te kan verander.',
'changepassword'                => 'Verander wagwoord',
'prefs-skin'                    => 'Omslag',
'skin-preview'                  => 'Voorskou',
'prefs-math'                    => 'Wiskunde',
'datedefault'                   => 'Geen voorkeur',
'prefs-datetime'                => 'Datum en tyd',
'prefs-personal'                => 'Gebruikersdata',
'prefs-rc'                      => 'Onlangse wysigings',
'prefs-watchlist'               => 'Dophoulys',
'prefs-watchlist-days'          => 'Aantal dae om in dophoulys te wys:',
'prefs-watchlist-days-max'      => 'Maksimum 7 dae',
'prefs-watchlist-edits'         => 'Aantal wysigings om in uitgebreide dophoulys te wys:',
'prefs-watchlist-edits-max'     => 'Maksimum aantal: 1000',
'prefs-watchlist-token'         => 'Dophoulys-sleutel:',
'prefs-misc'                    => 'Allerlei',
'prefs-resetpass'               => 'Verander wagwoord',
'prefs-email'                   => 'E-posopsies',
'prefs-rendering'               => 'Uiterlik',
'saveprefs'                     => 'Stoor voorkeure',
'resetprefs'                    => 'Herstel voorkeure',
'restoreprefs'                  => 'Herstel voorkeure',
'prefs-editing'                 => 'Wysigings',
'prefs-edit-boxsize'            => 'Afmetings van die wysigingsvenster.',
'rows'                          => 'Rye',
'columns'                       => 'Kolomme',
'searchresultshead'             => 'Soekresultate',
'resultsperpage'                => 'Aantal resultate om te wys',
'contextlines'                  => 'Aantal lyne per resultaat',
'contextchars'                  => 'Karakters konteks per lyn',
'recentchangesdays'             => 'Aantal dae wat in onlangse wysigings vertoon word:',
'recentchangesdays-max'         => 'Maksimum $1 {{PLURAL:$1|dag|dae}}',
'recentchangescount'            => 'Aantal wysigings om by verstek te vertoon:',
'prefs-help-recentchangescount' => 'Dit geld vir onlangse wysigings, bladsygeskiedenis en logboekbladsye.',
'savedprefs'                    => 'U voorkeure is gestoor.',
'timezonelegend'                => 'Tydsone:',
'localtime'                     => 'Plaaslike tyd:',
'timezoneuseserverdefault'      => 'Bedienerverstek',
'timezoneuseoffset'             => 'Ander (spesifiseer tydsverskil)',
'timezoneoffset'                => 'Tydsverskil¹:',
'servertime'                    => 'Bedienertyd:',
'guesstimezone'                 => 'Vul in vanaf webblaaier',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktiese gebied',
'timezoneregion-asia'           => 'Asië',
'timezoneregion-atlantic'       => 'Atlantiese Oseaan',
'timezoneregion-australia'      => 'Australië',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indiese Oseaan',
'timezoneregion-pacific'        => 'Stille Oseaan',
'allowemail'                    => 'Laat e-pos van ander toe',
'prefs-searchoptions'           => 'Soekopsies',
'prefs-namespaces'              => 'Naamruimtes',
'defaultns'                     => 'Anders soek in hierdie naamruimtes:',
'default'                       => 'verstek',
'prefs-files'                   => 'Lêers',
'prefs-custom-css'              => 'Persoonlike CSS',
'prefs-custom-js'               => 'Persoonlike JS',
'prefs-reset-intro'             => 'U kan die blad gebruik om u voorkeure terug te stel na die webwerf se verstekwaardes.
Die aksie kan nie ongedaan gemaak word nie.',
'prefs-emailconfirm-label'      => 'E-posbevestiging:',
'prefs-textboxsize'             => 'Afmetings van die wysigingsvenster',
'youremail'                     => 'E-pos',
'username'                      => 'Gebruikersnaam:',
'uid'                           => 'Gebruiker-ID:',
'prefs-memberingroups'          => 'Lid van {{PLURAL:$1|groep|groepe}}:',
'prefs-registration'            => 'Registrasiedatum:',
'yourrealname'                  => 'Regte naam:',
'yourlanguage'                  => 'Taal:',
'yournick'                      => 'Bynaam (vir handtekening)',
'prefs-help-signature'          => 'Kommentaar op besprekingsbladsye moet met "<nowiki>~~~~</nowiki>" onderteken word.
Die tildes word in u handtekening omgeskakel en die datum en tyd word insluit.',
'badsig'                        => 'Ongeldige handtekening; gaan HTML na.',
'badsiglength'                  => 'U handtekening is te lank. 
Dit mag nie meer as $1 {{PLURAL:$1|karakter|karakters}} bevat nie.',
'yourgender'                    => 'Geslag:',
'gender-unknown'                => 'Nie gespesifiseer',
'gender-male'                   => 'Man',
'gender-female'                 => 'Vrou',
'prefs-help-gender'             => 'Opsioneel: dit word gebruik om gebruikers korrek aan te spreek in die sagteware.
Die inligting is vir ander gebruikers sigbaar.',
'email'                         => 'E-pos',
'prefs-help-realname'           => 'Regte naam (opsioneel): as u hierdie verskaf, kan dit gebruik word om erkenning vir u werk te gee.',
'prefs-help-email'              => 'E-posadres is opsioneel, maar maak dit moontlik om u wagwoord aan u te pos sou u dit vergeet. 
U kan ook besluit om e-pos te ontvang as ander gebruikers u gebruikers- of besprekingsblad wysig sonder om u identiteit te verraai.',
'prefs-help-email-required'     => 'E-pos adres word benodig.',
'prefs-info'                    => 'Basiese inligting',
'prefs-i18n'                    => 'Taalinstellings',
'prefs-signature'               => 'Handtekening',
'prefs-dateformat'              => 'Datumformaat',
'prefs-timeoffset'              => 'Tydsverskil',
'prefs-advancedediting'         => 'Gevorderde instellings',
'prefs-advancedrc'              => 'Gevorderde instellings',
'prefs-advancedrendering'       => 'Gevorderde instellings',
'prefs-advancedsearchoptions'   => 'Gevorderde instellings',
'prefs-advancedwatchlist'       => 'Gevorderde instellings',
'prefs-display'                 => 'Vertoonopsies',
'prefs-diffs'                   => 'Verskille',

# User rights
'userrights'                  => 'Bestuur gebruikersregte',
'userrights-lookup-user'      => 'Beheer gebruikersgroepe',
'userrights-user-editname'    => 'Voer gebruikersnaam in:',
'editusergroup'               => 'Wysig gebruikersgroepe',
'editinguser'                 => "Besig om gebruikersregte van gebruiker '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) te wysig",
'userrights-editusergroup'    => 'wysig gebruikersgroepe',
'saveusergroups'              => 'Stoor gebruikersgroepe',
'userrights-groupsmember'     => 'Lid van:',
'userrights-groups-help'      => "U kan die groepe waarvan die gebruiker 'n lid is verander. 
* 'n Geselekteerde boks beteken dat die gebruiker lid is van die groep.
* 'n Ongeselekteerde boks beteken dat die gebruiker nie 'n lid van die groep is nie.
* 'n Ster (*) beteken dat u nie die gebruiker uit 'n groep kan verwyder as hy eers daaraan behoort nie, of vice versa.",
'userrights-reason'           => 'Rede vir wysiging:',
'userrights-no-interwiki'     => 'U het nie toestemming om gebruikersregte op ander wikis te verander nie.',
'userrights-nodatabase'       => 'Databasis $1 bestaan nie of is nie hier beskikbaar nie.',
'userrights-nologin'          => "U moet [[Special:UserLogin|aanteken]] as 'n administrateur om gebruikersregte te mag toeken.",
'userrights-notallowed'       => 'U het nie die toestemming om gebruikersregte toe te ken nie.',
'userrights-changeable-col'   => 'Groepe wat u kan verander',
'userrights-unchangeable-col' => 'Groepe wat u nie kan verander nie',

# Groups
'group'               => 'Groep:',
'group-user'          => 'Gebruikers',
'group-autoconfirmed' => 'Bevestigde gebruikers',
'group-bot'           => 'Robotte',
'group-sysop'         => 'Administrateurs',
'group-bureaucrat'    => 'Burokrate',
'group-suppress'      => 'Toesighouers',
'group-all'           => '(alle)',

'group-user-member'          => 'Gebruiker',
'group-autoconfirmed-member' => 'Geregistreerde gebruiker',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrateur',
'group-bureaucrat-member'    => 'Burokraat',
'group-suppress-member'      => 'Toesighouer',

'grouppage-user'          => '{{ns:project}}:Gebruikers',
'grouppage-autoconfirmed' => '{{ns:project}}:Geregistreerde gebruikers',
'grouppage-bot'           => '{{ns:project}}:Robotte',
'grouppage-sysop'         => '{{ns:project}}:Administrateurs',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokrate',
'grouppage-suppress'      => '{{ns:project}}:Toesig',

# Rights
'right-read'                  => 'Lees bladsye',
'right-edit'                  => 'Wysig bladsye',
'right-createpage'            => 'Skep bladsye (nie besprekingsblaaie nie)',
'right-createtalk'            => 'Skep besprekingsbladsye',
'right-createaccount'         => 'Skep nuwe gebruikersrekeninge',
'right-minoredit'             => "Merk as 'n klein verandering",
'right-move'                  => 'Skuif bladsye',
'right-move-subpages'         => 'skuif bladsye met hul subblaaie',
'right-movefile'              => 'Skuif lêers',
'right-upload'                => 'Laai lêers op',
'right-reupload'              => "Oorskryf 'n bestaande lêer",
'right-reupload-own'          => "Oorskryf 'n lêer wat u self opgelaai het",
'right-upload_by_url'         => "Laai lêer van 'n URL",
'right-autoconfirmed'         => 'Wysig half beskermde bladsye',
'right-bot'                   => "Behandel as 'n geoutomatiseerde proses",
'right-apihighlimits'         => 'Gebruik hoër limiete in API-soekopgragte',
'right-writeapi'              => 'Bewerkings m.b.v. die API',
'right-delete'                => 'Vee bladsye uit',
'right-bigdelete'             => 'Skrap bladsye met groot geskiedenisse',
'right-deleterevision'        => 'Skrap en ontskrap spesifieke hersienings van bladsye',
'right-browsearchive'         => 'Soek uigeveede bladsye',
'right-undelete'              => "Ontskrap 'n bladsy",
'right-suppressionlog'        => 'Besigtig privaat logboeke',
'right-block'                 => 'Ontneem ander gebruikers die reg om te wysig',
'right-blockemail'            => "Ontneem 'n gebruiker die reg om E-pos te stuur",
'right-hideuser'              => "Blokkeer 'n gebruiker, versteek dit van die publiek",
'right-editinterface'         => 'Wysig die gebruikerskoppelvlak',
'right-editusercssjs'         => 'Wysig ander gebruikers se CSS- en JS-lêers',
'right-editusercss'           => 'Wysig ander gebruikers se CSS-lêers',
'right-edituserjs'            => 'Wysig ander gebruikers se JS-lêers',
'right-noratelimit'           => 'Negeer tydsafhanklike beperkings',
'right-import'                => "Importeer bladsye vanaf ander wiki's",
'right-importupload'          => "Importeer bladsye vanaf 'n lêer",
'right-patrol'                => 'Merk ander se wysigings as gekontroleer',
'right-unwatchedpages'        => 'Wys lys van bladsye wat nie dopgehou word nie',
'right-trackback'             => "Verskaf 'n terugverwysende bladsy",
'right-mergehistory'          => 'Versmelt die geskiedenis van bladsye',
'right-userrights'            => 'Wysig alle gebruiker regte',
'right-userrights-interwiki'  => 'Wysig gebruikersregte van gebruikers op ander wikis',
'right-siteadmin'             => 'Sluit en ontsluit die datbasis',
'right-reset-passwords'       => 'Herstel ander gebruikers se wagwoorde',
'right-override-export-depth' => "Eksporteer bladsye insluitend geskakelde bladsye tot 'n diepte van 5",
'right-versiondetail'         => 'Wys omvattende sagteware weergawe inligting',
'right-sendemail'             => 'Stuur e-pos aan ander gebruikers',

# User rights log
'rightslog'      => 'Gebruikersregtelogboek',
'rightslogtext'  => 'Hier onder is die logboek van gebruikersregte wat verander is.',
'rightslogentry' => 'groep lidmaatskap verander vir $1 van $2 na $3',
'rightsnone'     => '(geen)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lees die bladsy',
'action-edit'                 => 'hierdie bladsy te wysig nie',
'action-createpage'           => 'skep bladsye',
'action-createtalk'           => 'skep besprekingsblaaie',
'action-createaccount'        => 'skep die genruiker',
'action-minoredit'            => "merk die wysiging as 'n klein verandering",
'action-move'                 => 'skuif die bladsy',
'action-move-subpages'        => 'skuif die bladsy met sy subbladsye',
'action-move-rootuserpages'   => 'gebruikersbladsye van die hoogste vlak te skuif nie',
'action-movefile'             => 'skuif die lêer',
'action-upload'               => 'laai die lêer op',
'action-reupload'             => 'oorskryf die bestaande lêer',
'action-reupload-shared'      => "die lêer op te laai, terwyl daar reeds 'n lêer met dieselfde naam in die gedeelde lêerbank is nie",
'action-upload_by_url'        => "laai die lêer vanaf 'n URL",
'action-writeapi'             => 'die API te gebruik nie',
'action-delete'               => 'verwyder die bladsy',
'action-deleterevision'       => 'skrap die weergawe',
'action-deletedhistory'       => 'wys die bladsy se verwyderingsgeskiedenis',
'action-browsearchive'        => 'te soek vir geskrapte bladsye nie',
'action-undelete'             => 'ontskrap die bladsy',
'action-suppressrevision'     => 'hersiening en terugplaas van hierdie verborge weergawe',
'action-suppressionlog'       => 'na die privaat logboek te kyk nie',
'action-block'                => 'blokkeer die gebruiker om wysigings te maak',
'action-protect'              => 'verander veiligheidsvlak van die bladsy',
'action-import'               => "hierdie bladsy van 'n ander wiki te importeer nie",
'action-importupload'         => "die bladsy van 'n opgelaaide lêer te importeer nie",
'action-patrol'               => 'wysigings van andere as gekontroleer te merk nie',
'action-autopatrol'           => 'eie wysiging as gekontroleerd te laat merk',
'action-unwatchedpages'       => 'wys die lys van blaaie wat deur niemand dopgehou word nie',
'action-trackback'            => "verskaf 'n terugverwysende bladsy",
'action-mergehistory'         => 'versmelt die geskiedenis van die bladsy',
'action-userrights'           => 'Wysig alle gebruikersregte',
'action-userrights-interwiki' => 'wysig gebruikersregte van gebruikers op ander wikis',
'action-siteadmin'            => 'sluit of ontsluit die databasis',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|wysiging|wysigings}}',
'recentchanges'                     => 'Onlangse wysigings',
'recentchanges-legend'              => 'Opsies vir onlangse wysigings',
'recentchangestext'                 => 'Volg die mees onlangse wysigings aan die wiki op die bladsy.',
'recentchanges-feed-description'    => 'Spoor die mees onlangse wysigings op die wiki na in die voer.',
'recentchanges-label-legend'        => 'Sleutel: $1.',
'recentchanges-legend-newpage'      => '$1 - nuwe bladsy',
'recentchanges-label-newpage'       => "Met die wysiging is 'n nuwe bladsy geskep",
'recentchanges-legend-minor'        => '$1 - klein wysiging',
'recentchanges-label-minor'         => "Hierdie is 'n klein wysiging",
'recentchanges-legend-bot'          => '$1 - botbywerking',
'recentchanges-label-bot'           => "Hierdie wysiging was deur 'n bot uitgevoer",
'recentchanges-legend-unpatrolled'  => '$1 - ongekontroleerde wysiging',
'recentchanges-label-unpatrolled'   => 'Die wysiging is nog nie gekontroleer nie',
'rcnote'                            => "Hier volg die laaste {{PLURAL:$1|'''$1''' wysiging|'''$1''' wysigings}} vir die afgelope {{PLURAL:$2|dag|'''$2''' dae}}, soos vanaf $4, $5.",
'rcnotefrom'                        => 'Hier onder is die wysigings sedert <b>$2</b> (tot by <b>$1</b> word gewys).',
'rclistfrom'                        => 'Vertoon wysigings vanaf $1',
'rcshowhideminor'                   => '$1 klein wysigings',
'rcshowhidebots'                    => '$1 robotte',
'rcshowhideliu'                     => '$1 aangetekende gebruikers',
'rcshowhideanons'                   => '$1 anonieme gebruikers',
'rcshowhidepatr'                    => '$1 gepatrolleerde wysigings',
'rcshowhidemine'                    => '$1 my wysigings',
'rclinks'                           => 'Vertoon die laaste $1 wysigings in die afgelope $2 dae<br />$3',
'diff'                              => 'verskil',
'hist'                              => 'geskiedenis',
'hide'                              => 'versteek',
'show'                              => 'Wys',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|gebruiker|gebruikers}} hou die bladsy dop]',
'rc_categories'                     => 'Beperk tot kategorië (skei met "|")',
'rc_categories_any'                 => 'Enige',
'newsectionsummary'                 => '/* $1 */ nuwe afdeling',
'rc-enhanced-expand'                => 'Wys details (benodig JavaScript)',
'rc-enhanced-hide'                  => 'Steek details weg',

# Recent changes linked
'recentchangeslinked'          => 'Verwante veranderings',
'recentchangeslinked-feed'     => 'Verwante veranderings',
'recentchangeslinked-toolbox'  => 'Verwante veranderings',
'recentchangeslinked-title'    => 'Wysigings verwant aan "$1"',
'recentchangeslinked-noresult' => 'Geen veranderinge op geskakelde bladsye gedurende die periode nie.',
'recentchangeslinked-summary'  => "Hier volg 'n lys van wysigings wat onlangs gemaak is aan bladsye wat van die gespesifiseerde bladsy geskakel word (of van bladsye van die gespesifiseerde kategorie).
Bladsye op [[Special:Watchlist|u dophoulys]] word in '''vetdruk''' uitgewys.",
'recentchangeslinked-page'     => 'Bladsynaam:',
'recentchangeslinked-to'       => 'Besigtig wysigings aan bladsye met skakels na die bladsy',

# Upload
'upload'                     => 'Laai lêer',
'uploadbtn'                  => 'Laai lêer',
'reuploaddesc'               => 'Keer terug na die laaivorm.',
'uploadnologin'              => 'Nie ingeteken nie',
'uploadnologintext'          => 'Teken eers in [[Special:UserLogin|logged in]]
om lêers te laai.',
'upload_directory_missing'   => 'Die oplaaigids ($1) bestaan nie en kon nie deur die webbediener geskep word nie.',
'upload_directory_read_only' => 'Die webbediener kan nie na die oplaai gids ($1) skryf nie.',
'uploaderror'                => 'Laaifout',
'uploadtext'                 => "'''STOP!''' Voor u iets hier oplaai, lees en volg {{SITENAME}} se
[[{{MediaWiki:Copyrightpage}}|beleid oor prentgebruik]].

Om prente wat voorheen gelaai is te sien of te soek, gaan na die
[[Special:FileList|lys van gelaaide prente]].
Laai van lêers en skrappings word aangeteken in die
[[Special:Log/upload|laailog]].

Gebruik die vorm hier onder om nuwe prente te laai wat u ter illustrasie in u artikels wil gebruik.
In die meeste webblaaiers sal u 'n \"Browse...\" knop sien, wat u bedryfstelsel se standaard lêeroopmaakdialoogblokkie sal oopmaak.
Deur 'n lêer in hierdie dialoogkassie te kies, vul u die teksboks naas die knop met die naam van die lêer.
U moet ook die blokkie merk om te bevestig dat u geen kopieregte skend deur die lêer op te laai nie.
Kliek die \"Laai\" knop om die laai af te handel.
Dit mag dalk 'n rukkie neem as u 'n stadige internetverbinding het.

Die voorkeurformate is JPEG vir fotografiese prente, PNG vir tekeninge en ander ikoniese prente, en OGG vir klanklêers.
Gebruik asseblief beskrywende lêername om verwarring te voorkom.
Om die prent in 'n artikel te gebruik, gebruik 'n skakel met die formaat '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.jpg]]</nowiki>''' of
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:file.png|alt text]]</nowiki>''' of
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:file.ogg]]</nowiki>''' vir klanklêers.",
'upload-permitted'           => 'Toegelate lêertipes: $1.',
'upload-preferred'           => 'Aanbevole lêertipes: $1.',
'upload-prohibited'          => 'Verbode lêertipes: $1.',
'uploadlog'                  => 'laailog',
'uploadlogpage'              => 'laai_log',
'uploadlogpagetext'          => "Hier volg 'n lys van die mees onlangse lêers wat gelaai is.",
'filename'                   => 'Lêernaam',
'filedesc'                   => 'Opsomming',
'fileuploadsummary'          => 'Opsomming:',
'filereuploadsummary'        => 'Lêerwysigings:',
'filestatus'                 => 'Outeursregsituasie:',
'filesource'                 => 'Bron:',
'uploadedfiles'              => 'Gelaaide lêers',
'ignorewarning'              => 'Ignoreer waarskuwings en stoor die lêer',
'ignorewarnings'             => 'Ignoreer enige waarskuwings',
'minlength1'                 => 'Prentname moet ten minste een letter lank wees.',
'illegalfilename'            => 'Die lêernaam "$1" bevat karakters wat nie toegelaat word in bladsytitels nie. Verander asseblief die naam en probeer die lêer weer laai.',
'badfilename'                => 'Prentnaam is verander na "$1".',
'filetype-badmime'           => 'Lêers met MIME-tipe "$1" word nie toegelaat nie.',
'filetype-bad-ie-mime'       => 'Die lêer kan nie opgelaai word nie omdat Internet Explorer dit sal identifiseer as "$1", \'n nie toegelate lêertipe wat moontlik skadelik is.',
'filetype-unwanted-type'     => "'''\".\$1\"''' is 'n ongewenste lêertipe. 
Aanbevole {{PLURAL:\$3|lêertipe|lêertipes}} is \$2.",
'filetype-banned-type'       => "'''\".\$1\"''' is nie 'n toegelate lêertipe nie.
Toelaatbare {{PLURAL:\$3|lêertipes|lêertipes}} is \$2.",
'filetype-missing'           => 'Die lêer het geen uitbreiding (soos ".jpg").',
'large-file'                 => 'Aanbeveling: maak lêer kleiner as $1;
die lêer is $2.',
'largefileserver'            => 'Hierdie lêer is groter as wat die bediener se opstelling toelaat.',
'emptyfile'                  => "Die lêer wat u probeer oplaai het blyk leeg te wees. Dit mag wees omdat u 'n tikfout in die lêernaam gemaak het. Gaan asseblief na en probeer weer.",
'fileexists'                 => "'n Lêer met die naam bestaan reeds, kyk na '''<tt>[[:$1]]</tt>''' as u nie seker is dat u dit wil wysig nie.
[[$1|thumb]]",
'file-exists-duplicate'      => "Die lêer is 'n duplikaat van die volgende {{PLURAL:$1|lêer|lêers}}:",
'file-deleted-duplicate'     => "'n Lêer identies aan dié een ([[$1]]) was al voorheen geskrap. <br>
Dit word aanbeveel dat u die lêer se skrapgeskiedenis besigtig voor u poog om dit weer op te laai.",
'successfulupload'           => 'Laai suksesvol',
'uploadwarning'              => 'Laaiwaarskuwing',
'savefile'                   => 'Stoor lêer',
'uploadedimage'              => 'het "[[$1]]" gelaai',
'overwroteimage'             => 'het een nuwe weergawe van "[[$1]]" gelaai',
'uploaddisabled'             => 'Laai is uitgeskakel',
'uploaddisabledtext'         => 'Die oplaai van lêers is afgeskakel.',
'php-uploaddisabledtext'     => 'Die oplaai van lêers is in PHP afgeskakel.
Kyk na die "file_uploads"-instelling.',
'uploadcorrupt'              => "Die lêer is foutief of is van 'n verkeerde tipe. Gaan asseblief die lêer na en laai weer op.",
'uploadvirus'                => "Hierdie lêer bevat 'n virus! Inligting: $1",
'upload-source'              => 'Bronlêer',
'sourcefilename'             => 'Bronlêernaam:',
'sourceurl'                  => 'Bron-URL:',
'destfilename'               => 'Teikenlêernaam:',
'upload-maxfilesize'         => 'Maksimum lêer grootte: $1',
'upload-description'         => 'Lêerbeskrywing',
'upload-options'             => 'Oplaai-opsies',
'watchthisupload'            => 'Hou die lêer dop',
'upload-wasdeleted'          => "'''Waarskuwing: U is besig om 'n lêer op te laai wat voorheen verwyder is.'''

Dink twee keer na of dit wel gepas is om die lêer hier op te laai. 
Die verwyderingsinligting van die lêer word vir u gemak hier herhaal:",

'upload-proto-error'        => 'Verkeerde protokol',
'upload-proto-error-text'   => 'Oplaaie via hierdie metode vereis dat die URL met <code>http://</code> of <code>ftp://</code> begin.',
'upload-file-error'         => 'Interne fout',
'upload-misc-error'         => 'Onbekende laai fout',
'upload-too-many-redirects' => 'Die URL bevat te veel aansture',
'upload-unknown-size'       => 'Onbekende grootte',
'upload-http-error'         => "'n HTTP-fout het voorgekom: $1",

# img_auth script messages
'img-auth-accessdenied' => 'Toegang geweier',
'img-auth-notindir'     => 'Die aangevraagde pad is nie die ingestelde oplaaigids nie.',
'img-auth-badtitle'     => 'Dit was nie moontlik om \'n geldige bladsynaam van "$1" te maak nie.',
'img-auth-nologinnWL'   => 'U is nie aangeteken en "$1" is nie op die witlys nie.',
'img-auth-nofile'       => 'Lêer "$1" bestaan nie.',
'img-auth-isdir'        => 'U probeer om toegang na gids "$1" te kry.
Slegs toegang tot lêers word toegelaat.',
'img-auth-streaming'    => 'Besig met die stoom van "$1".',
'img-auth-noread'       => 'Gebruiker het nie toegang om "$1" te lees nie.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kon nie die URL bereik nie',
'upload-curl-error6-text'  => 'Die URL is nie bereikbaar nie.
Kontroleer of die URL korrek is, en of die webwerf wel beskikbaar is.',
'upload-curl-error28'      => 'Oplaai neem te lank',
'upload-curl-error28-text' => "Die webwerf neem te lank om te antwoord.
Kontroleer of die webwerf wel beskikbaar is of wag 'n rukkie en probeer dan weer.
U kan miskien selfs tydens 'n minder besige tyd weer probeer.",

'license'            => 'Lisensiëring:',
'license-header'     => 'Lisensiëring',
'nolicense'          => 'Niks gekies',
'license-nopreview'  => '(Voorskou nie beskikbaar)',
'upload_source_url'  => " ('n geldige, publiek toeganklike URL)",
'upload_source_file' => " ('n lêer op u rekenaar)",

# Special:ListFiles
'listfiles-summary'     => 'Die spesiale bladsy wys al die opgelaaide lêers.
Die nuutste lêer word eerste vertoon.
Klik op die opskrifte om die tabel anders te sorteer.',
'listfiles_search_for'  => 'Soek vir medianaam:',
'imgfile'               => 'lêer',
'listfiles'             => 'Lêerlys',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Naam',
'listfiles_user'        => 'Gebruiker',
'listfiles_size'        => 'Grootte',
'listfiles_description' => 'Beskrywing',
'listfiles_count'       => 'Weergawes',

# File description page
'file-anchor-link'          => 'Lêer',
'filehist'                  => 'Lêergeskiedenis',
'filehist-help'             => 'Klik op die datum/tyd om te sien hoe die lêer destyds gelyk het.',
'filehist-deleteall'        => 'verwyder alles',
'filehist-deleteone'        => 'skrap',
'filehist-revert'           => 'rol terug',
'filehist-current'          => 'huidig',
'filehist-datetime'         => 'Datum/Tyd',
'filehist-thumb'            => 'Duimnaelskets',
'filehist-thumbtext'        => 'Duimnaelskets vir weergawe vanaf $1',
'filehist-nothumb'          => 'Geen duimnaelskets',
'filehist-user'             => 'Gebruiker',
'filehist-dimensions'       => 'Dimensies',
'filehist-filesize'         => 'Lêergrootte',
'filehist-comment'          => 'Opmerking',
'filehist-missing'          => 'Die lêer kon nie gevind word nie',
'imagelinks'                => 'Lêerskakels',
'linkstoimage'              => 'Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} gebruik hierdie prent:',
'nolinkstoimage'            => 'Daar is geen bladsye wat hierdie prent gebruik nie.',
'morelinkstoimage'          => 'Wys [[Special:WhatLinksHere/$1|meer skakels]] na die lêer.',
'redirectstofile'           => "Die volgende {{PLURAL:$1|lêer is 'n aanstuur|$1 lêers is aansture}} na die lêer:",
'duplicatesoffile'          => "Die volgende {{PLURAL:$1|lêer is 'n duplikaat|$1 lêers is duplikate}} van die lêer ([[Special:FileDuplicateSearch/$2|meer details]]):",
'sharedupload'              => 'Die lêer kom vanaf $1 en mag moontlik ook op ander projekte gebruik word.',
'sharedupload-desc-there'   => 'Hierdie lêer kom vanaf $1 en kan ook in ander projekte gebruik word.
Sien die [$2 lêer se beskrywingsblad] vir meer inligting.',
'sharedupload-desc-here'    => 'Hierdie lêer kom vanaf $1 en kan ook in ander projekte gebruik word.
Die beskrywing op die [$2 lêer se inligtingsblad] word hieronder weergegee.',
'filepage-nofile'           => "Daar bestaan nie 'n lêer met die naam nie.",
'filepage-nofile-link'      => "Daar bestaan nie 'n lêer met die naam nie, maar u kan een [$1 oplaai].",
'uploadnewversion-linktext' => 'Laai een nuwe weergawe van hierdie lêer',
'shared-repo-from'          => 'vanaf $1',
'shared-repo'               => "'n gedeelde lêerbank",

# File reversion
'filerevert'                => 'Maak $1 ongedaan',
'filerevert-legend'         => 'Maak lêer ongedaan',
'filerevert-intro'          => "U is besig om die lêer '''[[Media:$1|$1]]''' terug te rol tot die [$4 weergawe op $2, $3]",
'filerevert-comment'        => 'Opmerking:',
'filerevert-defaultcomment' => 'Teruggerol na die weergawe van $1, $2',
'filerevert-submit'         => 'Rol terug',
'filerevert-success'        => "'''[[Media:$1|$1]]''' is teruggerol na die [$4 weergawe op $2, $3].",
'filerevert-badversion'     => 'Daar is geen vorige plaaslike weergawe van die lêer vir die gespesifiseerde tydstip nie.',

# File deletion
'filedelete'                  => 'Skrap $1',
'filedelete-legend'           => 'Skrap lêer',
'filedelete-intro'            => "U is op die punt om die lêer '''[[Media:$1|$1]]''' te verwyder, inklusief alle ouer weergawes daarvan.",
'filedelete-intro-old'        => "U is besig om die weergawe van '''[[Media:$1|$1]]''' van [$4 $3, $2] te verwyder.",
'filedelete-comment'          => 'Rede vir skrapping:',
'filedelete-submit'           => 'Skrap',
'filedelete-success'          => "'''$1''' is geskrap.",
'filedelete-success-old'      => "Die weergawe van '''[[Media:$1|$1]]''' op $3, $2 is geskrap.",
'filedelete-nofile'           => "'''$1''' bestaan nie.",
'filedelete-nofile-old'       => "Daar is geen weergawe van '''$1''' in die argief met die aangegewe eienskappe nie.",
'filedelete-otherreason'      => 'Ander/ekstra rede:',
'filedelete-reason-otherlist' => 'Andere rede',
'filedelete-reason-dropdown'  => '*Algemene skrappingsredes:
** Kopieregskending
** Duplikaatlêer',
'filedelete-edit-reasonlist'  => 'Wysig skrap redes',
'filedelete-maintenance'      => 'Die verwydering en terugplasing van lêers is tydelik opgeskort weens onderhoud.',

# MIME search
'mimesearch'         => 'MIME-soek',
'mimesearch-summary' => 'Hierdie bladsy maak dit moontlik om lêers te filtreer volgens hulle MIME-tipe. Invoer: inhoudtipe/subtipe, byvoorbeeld <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-tipe:',
'download'           => 'laai af',

# Unwatched pages
'unwatchedpages' => 'Bladsye wat nie dopgehou word nie',

# List redirects
'listredirects' => 'Lys aansture',

# Unused templates
'unusedtemplates'     => 'Ongebruikte sjablone',
'unusedtemplatestext' => "Hierdie blad lys alle bladsye in die {{ns:template}}-naamruimte wat nêrens in 'n ander blad ingesluit word nie. Onthou om ook ander skakels na die sjablone na te gaan voor verwydering.",
'unusedtemplateswlh'  => 'ander skakels',

# Random page
'randompage'         => 'Lukrake bladsy',
'randompage-nopages' => 'Daar is geen bladye in die volgende {{PLURAL:$2|naamspasie|naamspasies}}: $1.',

# Random redirect
'randomredirect'         => 'Lukrake aanstuur',
'randomredirect-nopages' => 'Daar is geen aansture in naamspasie "$1".',

# Statistics
'statistics'                   => 'Statistieke',
'statistics-header-pages'      => 'Bladsy statistieke',
'statistics-header-edits'      => 'Wysig statistieke',
'statistics-header-views'      => 'Wys statistieke',
'statistics-header-users'      => 'Gebruikerstatistiek',
'statistics-header-hooks'      => 'Ander statistieke',
'statistics-articles'          => 'Inhoudelike bladsye',
'statistics-pages'             => 'Bladsye',
'statistics-pages-desc'        => 'Alle bladsye in die wiki, insluitend besprekings-, aanstuur- en ander bladsye.',
'statistics-files'             => 'Opgelaaide lêers',
'statistics-edits'             => 'Wysigings sedert {{SITENAME}} begin is',
'statistics-edits-average'     => 'Gemiddelde wysigings per bladsy',
'statistics-views-total'       => 'Totale aantal bladsye vertoon',
'statistics-views-peredit'     => 'Bladsye besigtig per wysiging',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Job queue] lengte',
'statistics-users'             => 'Geregistreerde [[Special:ListUsers|gebruikers]]',
'statistics-users-active'      => 'Aktiewe grbruikers',
'statistics-users-active-desc' => "Gebruikers wat in die afgelope {{PLURAL:$1|dag|$1 dae}} 'n handeling uitgevoer het",
'statistics-mostpopular'       => 'Mees bekykte bladsye',

'disambiguations'      => 'Bladsye wat onduidelikhede opklaar',
'disambiguationspage'  => 'Template:Dubbelsinnig',
'disambiguations-text' => "Die volgende bladsye skakel na '''dubbelsinnigheidsbladsye'''.
Die bladsye moet gewysig word om eerder direk na die regte onderwerpe te skakel.<br />
'n Bladsy word beskou as 'n dubbelsinnigheidsbladsy as dit 'n sjabloon bevat wat geskakel is vanaf [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Dubbele aansture',
'doubleredirectstext'        => '<b>Let op:</b> Hierdie lys bevat moontlik vals positiewes. Dit beteken gewoonlik dat daar nog teks met skakels onder die eerste #REDIRECT/#AANSTUUR is.<br />
Elke ry bevat skakels na die eerste en die tweede aanstuur, asook die eerste reël van van die tweede aanstuur se teks, wat gewoonlik die "regte" teiken bladsy gee waarna die eerste aanstuur behoort te wys.',
'double-redirect-fixed-move' => "[[$1]] was geskuif en is nou 'n deurverwysing na [[$2]].",
'double-redirect-fixer'      => 'Aanstuur hersteller',

'brokenredirects'        => 'Stukkende aansture',
'brokenredirectstext'    => 'Die volgende aansture skakel na bladsye wat nie bestaan nie.',
'brokenredirects-edit'   => 'wysig',
'brokenredirects-delete' => 'skrap',

'withoutinterwiki'         => 'Bladsye sonder taalskakels',
'withoutinterwiki-summary' => 'Die volgende bladsye het nie skakels na weergawes in ander tale nie:',
'withoutinterwiki-legend'  => 'Voorvoegsel',
'withoutinterwiki-submit'  => 'Wys',

'fewestrevisions' => 'Artikels met die minste wysigings',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|greep|grepe}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorie|kategorieë}}',
'nlinks'                  => '$1 {{PLURAL:$1|skakel|skakels}}',
'nmembers'                => '$1 {{PLURAL:$1|lid|lede}}',
'nrevisions'              => '$1 {{PLURAL:$1|weergawe|weergawes}}',
'nviews'                  => '$1 {{PLURAL:$1|keer|kere}} aangevra',
'specialpage-empty'       => 'Die verslag lewer geen resultate nie.',
'lonelypages'             => 'Weesbladsye',
'lonelypagestext'         => 'Die volgende bladsye is nie geskakel of ingesluit in ander bladsye op {{SITENAME}} nie:',
'uncategorizedpages'      => 'Ongekategoriseerde bladsye',
'uncategorizedcategories' => 'Ongekategoriseerde kategorieë',
'uncategorizedimages'     => 'Ongekategoriseerde lêers',
'uncategorizedtemplates'  => 'Ongekategoriseerde sjablone',
'unusedcategories'        => 'Ongebruikte kategorieë',
'unusedimages'            => 'Ongebruikte lêers',
'popularpages'            => 'Gewilde bladsye',
'wantedcategories'        => 'Begeerde kategorieë',
'wantedpages'             => 'Begeerde bladsye',
'wantedpages-badtitle'    => 'Ongeldige bladsynaam in resultate: $1',
'wantedfiles'             => 'Begeerde lêers',
'wantedtemplates'         => 'Begeerde sjablone',
'mostlinked'              => 'Bladsye met meeste skakels daarheen',
'mostlinkedcategories'    => 'Kategorieë met die meeste skakels daarheen',
'mostlinkedtemplates'     => 'Sjablone met die meeste skakels daarheen',
'mostcategories'          => 'Artikels met die meeste kategorieë',
'mostimages'              => 'Beelde met meeste skakels daarheen',
'mostrevisions'           => 'Artikels met meeste wysigings',
'prefixindex'             => 'Alle bladsye (voorvoegselindeks)',
'shortpages'              => 'Kort bladsye',
'longpages'               => 'Lang bladsye',
'deadendpages'            => 'Doodloopbladsye',
'deadendpagestext'        => 'Die volgende bladsye bevat nie skakels na ander bladsye in {{SITENAME}} nie:',
'protectedpages'          => 'Beskermde bladsye',
'protectedpages-indef'    => 'Slegs blokkades sonder vervaldatum',
'protectedpages-cascade'  => 'Slegs blokkades wat neergolf',
'protectedpagestext'      => 'Die volgende bladsye is beskerm teen verskuiwing of wysiging:',
'protectedpagesempty'     => 'Geen bladsye is tans met die parameters beveilig nie.',
'protectedtitles'         => 'Beskermde titels',
'protectedtitlestext'     => 'Die volgende titels is beveilig en kan nie geskep word nie',
'protectedtitlesempty'    => 'Geen titels is tans met die parameters beveilig nie.',
'listusers'               => 'Gebruikerslys',
'listusers-editsonly'     => 'Slegs gebruikers met wysigings',
'listusers-creationsort'  => 'Sorteer volgens registrasiedatum',
'usereditcount'           => '$1 {{PLURAL:$1|wysiging|wysigings}}',
'usercreated'             => 'geskep op $1 om $2',
'newpages'                => 'Nuwe bladsye',
'newpages-username'       => 'Gebruikersnaam:',
'ancientpages'            => 'Oudste bladsye',
'move'                    => 'Skuif',
'movethispage'            => 'Skuif hierdie bladsy',
'unusedimagestext'        => "Let asseblief op dat ander webwerwe, soos die internasionale {{SITENAME}}s, dalk met 'n direkte URL na 'n prent skakel, so die prent sal dus hier verskyn al word dit aktief gebruik.",
'unusedcategoriestext'    => 'Die volgende kategoriebladsye bestaan alhoewel geen artikel of kategorie hulle gebruik nie.',
'notargettitle'           => 'Geen teiken',
'notargettext'            => "U het nie 'n teikenbladsy of gebruiker waarmee hierdie funksie moet werk, gespesifiseer nie.",
'nopagetitle'             => 'Die bestemming bestaan nie',
'nopagetext'              => 'Die bladsy wat u wil skuif bestaan nie.',
'pager-newer-n'           => '{{PLURAL:$1|nuwer 1|nuwer $1}}',
'pager-older-n'           => '{{PLURAL:$1|ouer 1|ouer $1}}',
'suppress'                => 'Toesig',

# Book sources
'booksources'               => 'Boekbronne',
'booksources-search-legend' => 'Soek vir boekbronne',
'booksources-go'            => 'Soek',
'booksources-text'          => "Gevolg is 'n lys van skakels wat na ander webtuistes lei wat nuwe en gebruikte boeke verkoop, en wat dalk meer inligting kan bevat oor die boeke waarop u opsoek is:",
'booksources-invalid-isbn'  => 'Die ingevoerde ISBN-kode blyk asof dit ongeldig is; maak asseblief seker dat u dit sonder fout oorgekopiëer het vanaf die oorspronklike bron.',

# Special:Log
'specialloguserlabel'  => 'Gebruiker:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logboeke',
'all-logs-page'        => 'Alle openbare logboeke',
'alllogstext'          => "Vertoon 'n samestelling van alle boekstawings van {{SITENAME}}.
U kan die resultate vernou deur 'n boekstaaftipe, gebruikersnaam (kas-sensitief) of spesifieke blad (ook kas-sensitief) te kies.",
'logempty'             => 'Geen inskrywings in die logboek voldoen aan die kriteria nie.',
'log-title-wildcard'   => 'Soek bladsye wat met die naam begin',

# Special:AllPages
'allpages'          => 'Alle bladsye',
'alphaindexline'    => '$1 tot $2',
'nextpage'          => 'Volgende blad ($1)',
'prevpage'          => 'Vorige bladsye ($1)',
'allpagesfrom'      => 'Wys bladsye vanaf:',
'allpagesto'        => 'Wys bladsye tot:',
'allarticles'       => 'Alle bladsye',
'allinnamespace'    => 'Alle bladsye (naamruimte $1)',
'allnotinnamespace' => 'Alle bladsye (nie in naamruimte $1 nie)',
'allpagesprev'      => 'Vorige',
'allpagesnext'      => 'Volgende',
'allpagessubmit'    => 'Gaan',
'allpagesprefix'    => 'Wys bladsye wat begin met:',
'allpages-bad-ns'   => '{{SITENAME}} het geen naamspasie "$1" nie.',

# Special:Categories
'categories'                    => 'Kategorieë',
'categoriespagetext'            => 'Die volgende {{PLURAL:$1|kategorie|kategorieë}} bevat bladsye of media.
[[Special:UnusedCategories|Ongebruikte kategorieë]] word nie hier weergegee nie.
Sie ook [[Special:WantedCategories|nie-bestaande kategorieë met verwysings]].',
'categoriesfrom'                => 'Wys kategorieë vanaf:',
'special-categories-sort-count' => 'sorteer volgens getal',
'special-categories-sort-abc'   => 'sorteer alfabeties',

# Special:DeletedContributions
'deletedcontributions'             => 'Geskrapte gebruikersbydraes',
'deletedcontributions-title'       => 'Geskrapte gebruikersbydraes',
'sp-deletedcontributions-contribs' => 'bydraes',

# Special:LinkSearch
'linksearch'       => 'Eksterne skakels',
'linksearch-pat'   => 'Soekpatroon:',
'linksearch-ns'    => 'Naamruimte:',
'linksearch-ok'    => 'Soek',
'linksearch-text'  => 'Patrone soos "*.wikipedia.org" of "*.org" kan gebruik word.<br />
Ondersteunde protokolle: <tt>$1</tt>',
'linksearch-line'  => '$1 geskakel vanaf $2',
'linksearch-error' => 'Patrone kan slegs aan die begin van die rekenaarnaam geplaas word.',

# Special:ListUsers
'listusersfrom'      => 'Wys gebruikers, beginnende by:',
'listusers-submit'   => 'Wys',
'listusers-noresult' => 'Geen gebruiker gevind.',
'listusers-blocked'  => '(geblokkeer)',

# Special:ActiveUsers
'activeusers'          => 'Aktiewe gebruikers',
'activeusers-intro'    => "Hierdie is 'n lys van gebruikers wat die laaste {{PLURAL:$1|dag|$1 dae}} enige aktiwiteit getoon het.",
'activeusers-count'    => '$1 onlangse {{PLURAL:$1|wysiging|wysigings}} in die {{PLURAL:$3|afgelope dag|laatste $3 dae}}',
'activeusers-from'     => 'Wys gebruikers, beginnende by:',
'activeusers-noresult' => 'Geen gebruikers gevind nie.',

# Special:Log/newusers
'newuserlogpage'              => 'Logboek van nuwe gebruikers',
'newuserlogpagetext'          => "Dit is 'n logboek van gebruikers wat onlangs ingeteken het.",
'newuserlog-byemail'          => 'wagwoord is per e-pos versend',
'newuserlog-create-entry'     => 'Nuwe gebruiker',
'newuserlog-create2-entry'    => 'rekening is geskep vir $1',
'newuserlog-autocreate-entry' => 'Gebruiker outomaties geskep',

# Special:ListGroupRights
'listgrouprights'                      => 'Gebruikersgroepregte',
'listgrouprights-summary'              => "Hier volg 'n lys van gebruikersgroepe met hulle ooreenstemmende regte wat op die wiki gedefinieer is.
Daar kan [[{{MediaWiki:Listgrouprights-helppage}}|extra inligting]] oor individuele regte aanwesig wees.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Toegekende regte</span>
* <span class="listgrouprights-revoked">Teruggetrekte regte</span>',
'listgrouprights-group'                => 'Groep',
'listgrouprights-rights'               => 'Regte',
'listgrouprights-helppage'             => 'Help:Groep regte',
'listgrouprights-members'              => '(lys van lede)',
'listgrouprights-addgroup'             => 'Gebruikers by die volgende {{PLURAL:$2|groep|groepe}} byvoeg: $1',
'listgrouprights-removegroup'          => 'Gebruikers uit die volgende {{PLURAL:$2|groep|groepe}} verwyder: $1',
'listgrouprights-addgroup-all'         => 'Gebruikers van alle groepe byvoeg',
'listgrouprights-removegroup-all'      => 'Gebruikers uit alle groepe verwyder',
'listgrouprights-addgroup-self'        => 'Die volgende {{PLURAL:$2|groep|groepe}} byvoeg tot eie gebruiker: $1',
'listgrouprights-removegroup-self'     => 'Die volgende {{PLURAL:$2|groep|groepe}} verwyder van eie gebruiker: $1',
'listgrouprights-addgroup-self-all'    => 'Alle groepe byvoeg tot eie gebruiker',
'listgrouprights-removegroup-self-all' => 'Alle groepe verwyder van eie gebruiker',

# E-mail user
'mailnologin'      => 'Geen versendadres beskikbaar',
'mailnologintext'  => "U moet [[Special:UserLogin|ingeteken]] wees en 'n geldige e-posadres in die [[Special:Preferences|voorkeure]] hê om e-pos aan ander gebruikers te stuur.",
'emailuser'        => 'Stuur e-pos na hierdie gebruiker',
'emailpage'        => 'Stuur e-pos na gebruiker',
'emailpagetext'    => 'As dié gebruiker \'n geldige e-posadres in sy/haar gebruikersvoorkeure het, sal hierdie vorm \'n enkele boodskap stuur. Die e-posadres in u [[Special:Preferences|gebruikersvoorkeure]] sal verkyn as die "Van"-adres van die pos. Dus sal die ontvanger kan terug antwoord.',
'usermailererror'  => 'Fout met versending van e-pos:',
'defemailsubject'  => '{{SITENAME}}-epos',
'noemailtitle'     => 'Geen e-posadres',
'noemailtext'      => "Hierdie gebruiker het nie 'n geldige e-posadres gespesifiseer nie.",
'nowikiemailtitle' => 'Geen E-pos toegelaat nie',
'nowikiemailtext'  => 'Hierdie gebruiker wil geen e-pos van andere gebruikers ontvang nie.',
'email-legend'     => "Stuur 'n E-pos na 'n ander gebruiker van {{SITENAME}}",
'emailfrom'        => 'Van:',
'emailto'          => 'Aan:',
'emailsubject'     => 'Onderwerp:',
'emailmessage'     => 'Boodskap:',
'emailsend'        => 'Stuur',
'emailccme'        => "E-pos vir my 'n kopie van my boodskap.",
'emailccsubject'   => 'Kopie van u boodskap aan $1: $2',
'emailsent'        => 'E-pos gestuur',
'emailsenttext'    => 'U e-pos is gestuur.',
'emailuserfooter'  => 'Hierdie e-pos is gestuur deur $1 aan $2 met behulp van die "Stuur e-pos aan die gebruiker"-funksie van {{SITENAME}}.',

# Watchlist
'watchlist'            => 'My dophoulys',
'mywatchlist'          => 'My dophoulys',
'watchlistfor'         => "(vir '''$1''')",
'nowatchlist'          => 'U het geen items in u dophoulys nie.',
'watchlistanontext'    => '$1 is noodsaaklik om u dophoulys te sien of te wysig.',
'watchnologin'         => 'Nie ingeteken nie',
'watchnologintext'     => 'U moet [[Special:UserLogin|ingeteken]]
wees om u dophoulys te verander.',
'addedwatch'           => 'Bygevoeg tot dophoulys',
'addedwatchtext'       => "Die bladsy \"\$1\" is by u [[Special:Watchlist|dophoulys]] gevoeg. Toekomstige veranderinge aan hierdie bladsy en sy verwante besprekingsblad sal daar verskyn en die bladsy sal in '''vetdruk''' verskyn in die [[Special:RecentChanges|lys van onlangse wysigings]], sodat u dit makliker kan raaksien.

As u die bladsy later van u dophoulys wil verwyder, kliek \"verwyder van dophoulys\" in die kieslys bo-aan die bladsy.",
'removedwatch'         => 'Afgehaal van dophoulys',
'removedwatchtext'     => 'Die bladsy "[[:$1]]" is van [[Special:Watchlist|u dophoulys]] afgehaal.',
'watch'                => 'Hou dop',
'watchthispage'        => 'Hou hierdie bladsy dop',
'unwatch'              => 'Verwyder van dophoulys',
'unwatchthispage'      => 'Moenie meer dophou',
'notanarticle'         => "Nie 'n artikel",
'notvisiblerev'        => 'Weergawe is verwyder',
'watchnochange'        => 'Geen item op die dophoulys is geredigeer in die gekose periode nie.',
'watchlist-details'    => '{{PLURAL:$1|$1 bladsy|$1 bladsye}} in u dophoulys, besprekingsbladsye uitgesluit.',
'wlheader-enotif'      => '* E-pos notifikasie is aangeskakel.',
'wlheader-showupdated' => "* Bladsye wat verander is sedert u hulle laas besoek het word in '''vetdruk''' uitgewys",
'watchmethod-recent'   => 'Kontroleer onlangse wysigings aan bladsye op dophoulys',
'watchmethod-list'     => 'kontroleer bladsye op dophoulys vir wysigings',
'watchlistcontains'    => 'U dophoulys bevat $1 {{PLURAL:$1|bladsy|bladsye}}.',
'iteminvalidname'      => "Probleem met item '$1', ongeldige naam...",
'wlnote'               => "Hier volg die laaste {{PLURAL:$1|verandering|'''$1''' veranderings}} binne die laaste {{PLURAL:$2|uur|'''$2''' ure}}.",
'wlshowlast'           => 'Wys afgelope $1 ure, $2 dae of $3',
'watchlist-options'    => 'Opsies vir dophoulys',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Plaas op dophoulys...',
'unwatching' => 'Verwyder van dophoulys...',

'enotif_mailer'                => '{{SITENAME}} E-pos kennisgewings',
'enotif_reset'                 => 'Merk alle bladsye as besoek',
'enotif_newpagetext'           => "Dis 'n nuwe bladsy.",
'enotif_impersonal_salutation' => '{{SITENAME}} gebruiker',
'changed'                      => 'verander',
'created'                      => 'geskep',
'enotif_subject'               => 'Bladsy $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED deur $PAGEEDITOR',
'enotif_lastvisited'           => 'Sien $1 vir alle wysigings sedert u laaste besoek.',
'enotif_lastdiff'              => 'Sien $1 om hierdie wysiging te bekyk.',
'enotif_anon_editor'           => 'anonieme gebruiker $1',
'enotif_body'                  => 'Beste $WATCHINGUSERNAME,

Die bladsy $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED op $PAGEEDITDATE deur $PAGEEDITOR, sien $PAGETITLE_URL vir die nuutste weergawe.

$NEWPAGE

Samevatting van die wysiging: $PAGESUMMARY $PAGEMINOREDIT

Outeur se kontakdetails:
E-pos: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Tensy u hierdie bladsy besoek, sal u geen verdere kennisgewings ontvang nie.
U kan ook die waarskuwingsvlag op u dophoulys verstel.

             Groete van die {{SITENAME}} waarskuwingssisteem.

--
U kan u dophoulys wysig by:
{{fullurl:{{#special:Watchlist}}/edit}}

Terugvoer en verdere bystand:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Skrap bladsy',
'confirm'                => 'Bevestig',
'excontent'              => "inhoud was: '$1'",
'excontentauthor'        => "Inhoud was: '$1' (en '[[Special:Contributions/$2|$2]]' was die enigste bydraer)",
'exbeforeblank'          => "Inhoud voor uitwissing was: '$1'",
'exblank'                => 'bladsy was leeg',
'delete-confirm'         => 'Skrap "$1"',
'delete-legend'          => 'Skrap',
'historywarning'         => "Waarskuwing: Die bladsy het 'n geskiedenis:",
'confirmdeletetext'      => "U staan op die punt om 'n bladsy of prent asook al hulle geskiedenis uit die databasis te skrap.
Bevestig asseblief dat u dit wil doen, dat u die gevolge verstaan en dat u dit doen in ooreenstemming met die [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'         => 'Aksie uitgevoer',
'actionfailed'           => 'Aksie het gefaal',
'deletedtext'            => '"<nowiki>$1</nowiki>" is geskrap.
Kyk na $2 vir \'n rekord van onlangse skrappings.',
'deletedarticle'         => '"$1" geskrap',
'suppressedarticle'      => 'het "[[$1]]" verberg',
'dellogpage'             => 'Skraplogboek',
'dellogpagetext'         => "Hier onder is 'n lys van die mees onlangse skrappings. Alle tye is bedienertyd (UGT).",
'deletionlog'            => 'skrappings-logboek',
'reverted'               => 'Het terug gegaan na vroeëre weergawe',
'deletecomment'          => 'Rede vir skrapping:',
'deleteotherreason'      => 'Ander/ekstra rede:',
'deletereasonotherlist'  => 'Andere rede',
'deletereason-dropdown'  => '*Algemene redes vir verwydering
** Op aanvraag van outeur
** Skending van kopieregte
** Vandalisme',
'delete-edit-reasonlist' => 'Wysig skrap redes',
'delete-toobig'          => "Die bladsy het 'n lang wysigingsgeskiedenis, meer as $1 {{PLURAL:$1|weergawe|weergawes}}.
Verwydering van die soort blaaie is beperk om ontwrigting van {{SITENAME}} te voorkom.",
'delete-warning-toobig'  => "Hierdie bladsy het 'n lang wysigingsgeskiedenis; meer as $1 {{PLURAL:$1|wysiging|wysigings}}.
Deur weg te doen met hierdie bladsy mag dalk die werking van {{SITENAME}} versteur;
Tree asseblief versigtig op.",

# Rollback
'rollback'          => 'Rol veranderinge terug',
'rollback_short'    => 'Rol terug',
'rollbacklink'      => 'Rol terug',
'rollbackfailed'    => 'Terugrol onsuksesvol',
'cantrollback'      => 'Kan nie na verandering terug keer nie; die laaste bydraer is die enigste outer van hierdie bladsy.',
'editcomment'       => "Die wysigsopsomming was: \"''\$1''\".",
'revertpage'        => 'Wysigings deur [[Special:Contributions/$2|$2]] teruggerol na laaste weergawe deur $1',
'revertpage-nouser' => 'Rol wysigings deur (gebruikersnaam verwyder) terug na die laaste weergawe deur [[User:$1|$1]]',
'rollback-success'  => 'Wysigings deur $1 teruggerol; terugverander na laaste weergawe deur $2.',

# Protect
'protectlogpage'              => 'Beskermlogboek',
'protectedarticle'            => 'het [[$1]] beskerm',
'modifiedarticleprotection'   => 'Die beskermingsvlak vir "[[$1]]" is gewysig',
'unprotectedarticle'          => 'het beskerming van [[$1]] verwyder',
'protect-title'               => 'Beskerm "$1"',
'prot_1movedto2'              => '[[$1]] geskuif na [[$2]]',
'protect-legend'              => 'Bevestig beskerming',
'protectcomment'              => 'Rede:',
'protectexpiry'               => 'Verval:',
'protect_expiry_invalid'      => 'Vervaltyd is ongeldig.',
'protect_expiry_old'          => 'Vervaltyd is in die verlede.',
'protect-text'                => "U kan die veiligheidsvlak vir blad '''<nowiki>$1</nowiki>''' hier bekyk of verander.",
'protect-locked-blocked'      => "U kan nie beskermingsvlakke verander terwyl u geblok is nie.
Hier volg die huidige oprigtings vir die bladsy '''$1''':",
'protect-locked-access'       => "U rekening het nie regte om 'n bladsy se veiligheidsvlakke te verander nie.
Hier is die huidige verstellings vir bladsy '''$1''':",
'protect-cascadeon'           => 'Die bladsy word beskerm want dit is ingesluit by die volgende {{PLURAL:$1|blad|blaaie}} wat kaskade-beskerming geniet. U kan die veiligheidsvlak van die bladsy verander, maar dit sal nie die ander kaskade blaaie beïnvloed nie.',
'protect-default'             => 'Laat alle gebruikers toe',
'protect-fallback'            => 'Hiervoor is "$1" regte nodig',
'protect-level-autoconfirmed' => 'Nuwe en ongeregistreerde gebruikers versper',
'protect-level-sysop'         => 'Slegs administrateurs',
'protect-summary-cascade'     => 'kaskade',
'protect-expiring'            => 'verval op $2 om $3 (UTC)',
'protect-expiry-indefinite'   => 'verval nie',
'protect-cascade'             => 'Beveilig bladsye insluitend die bladsy (kaskade effek)',
'protect-cantedit'            => 'U kan nie die veiligheidsvlak van die blad verander nie, want u het nie regte om dit te wysig nie.',
'protect-othertime'           => 'Ander tyd:',
'protect-othertime-op'        => 'ander tyd',
'protect-existing-expiry'     => 'Bestaande vervaldatum: $2 om $3',
'protect-otherreason'         => 'Ander/addisionele rede:',
'protect-otherreason-op'      => 'ander/addisionele rede',
'protect-dropdown'            => '*Algemene redes vir beveiliging
** Vandalisme
** Spam
** Wysigingsoorlog
** Voorkomende beveiliging vir besige bladsye',
'protect-edit-reasonlist'     => 'Rede vir beveiliging',
'protect-expiry-options'      => '1 uur:1 hour,1 dag:1 day,1 week:1 week,2 weke:2 weeks,1 maand:1 month,3 maande:3 months,6 maande:6 months,1 jaar:1 year,onbeperk:infinite',
'restriction-type'            => 'Regte:',
'restriction-level'           => 'Beperkingsvlak:',
'minimum-size'                => 'Minimum grootte',
'maximum-size'                => 'Maksimum grootte:',
'pagesize'                    => '(grepe)',

# Restrictions (nouns)
'restriction-edit'   => 'Wysig',
'restriction-move'   => 'Skuif',
'restriction-create' => 'Skep',
'restriction-upload' => 'Oplaai',

# Restriction levels
'restriction-level-sysop'         => 'volledig beveilig',
'restriction-level-autoconfirmed' => 'semibeveilig',
'restriction-level-all'           => 'enige vlak',

# Undelete
'undelete'                   => 'Besigtig geskrapte bladsye',
'undeletepage'               => 'Bekyk en herstel geskrapte bladsye',
'undeletepagetitle'          => "'''Hier onder is die verwyderde bydraes van [[:$1]]'''.",
'viewdeletedpage'            => 'Bekyk geskrapte bladsye',
'undeletepagetext'           => 'Die volgende {{PLURAL:$1|bladsy|$1 bladsye}} is geskrap, maar is nog in die argief en kan teruggeplaas word. Die argief van geskrapte blaaie kan periodiek skoongemaak word.',
'undelete-fieldset-title'    => 'Weergawes terugplaas',
'undeleterevisions'          => '$1 {{PLURAL:$1|weergawe|weergawes}} in argief',
'undeletehistory'            => "As u die bladsy herstel, sal alle weergawes herstel word.
As 'n nuwe bladsy met dieselfde naam sedert die skrapping geskep is, sal die herstelde weergawes in die nuwe bladsy se voorgeskiedenis verskyn en die huidige weergawe van die lewendige bladsy sal nie outomaties vervang word nie.",
'undeletehistorynoadmin'     => 'Die bladsy is geskrap.
Die rede hiervoor word onder in die opsomming aangedui, saam met besonderhede van die gebruikers wat die bladsy gewysig het voordat dit verwyder is.
Die verwyderde inhoud is slegs vir administrateurs sigbaar.',
'undelete-revision'          => 'Verwyder weergawe van $1 (per $4 om $5) deur $3:',
'undelete-nodiff'            => 'Geen vorige wysigings gevind.',
'undeletebtn'                => 'Herstel',
'undeletelink'               => 'bekyk/herstel',
'undeleteviewlink'           => 'bekyk',
'undeletereset'              => 'Herstel',
'undeleteinvert'             => 'Omgekeerde seleksie',
'undeletecomment'            => 'Opmerking:',
'undeletedarticle'           => 'het "$1" herstel',
'undeletedrevisions'         => '{{PLURAL:$1|1 weergawe|$1 weergawes}} herstel',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 weergawe|$1 weergawes}} en {{PLURAL:$2|1 lêer|$2 lêers}} herstel',
'undeletedfiles'             => '{{PLURAL:$1|1 lêer|$1 lêers}} herstel',
'cannotundelete'             => 'Skrapping onsuksesvol; miskien het iemand anders dié bladsy al geskrap.',
'undeletedpage'              => "<big>'''$1 is teruggeplaas'''</big>

Konsulteer die [[Special:Log/delete|verwyderingslogboek]] vir 'n rekord van onlangse verwyderings en terugplasings.",
'undelete-header'            => 'Sien die [[Special:Log/delete|skraplogboek]] vir onlangs verwyderde bladsye.',
'undelete-search-box'        => 'Soek verwyderde bladsye',
'undelete-search-prefix'     => 'Wys bladsye wat begin met:',
'undelete-search-submit'     => 'Soek',
'undelete-no-results'        => 'Geen bladsye gevind in die argief van geskrapte bladsye.',
'undelete-cleanup-error'     => 'Fout met die herstel van die ongebruikte argieflêer "$1".',
'undelete-error-short'       => 'Fout met herstel van lêer: $1',
'undelete-error-long'        => 'Foute het voorgekom tydens die herstel van die lêer:

$1',
'undelete-show-file-confirm' => 'Is u seker u wil na die verwyderde weergawe van die lêer "<nowiki>$1</nowiki>" van $2 om $3 kyk?',
'undelete-show-file-submit'  => 'Ja',

# Namespace form on various pages
'namespace'      => 'Naamruimte:',
'invert'         => 'Omgekeerde seleksie',
'blanknamespace' => '(Hoof)',

# Contributions
'contributions'       => 'Gebruikersbydraes',
'contributions-title' => '$1 se bydraes',
'mycontris'           => 'My bydraes',
'contribsub2'         => 'Vir $1 ($2)',
'nocontribs'          => 'Geen veranderinge wat by hierdie kriteria pas, is gevind nie.',
'uctop'               => ' (boontoe)',
'month'               => 'Vanaf maand (en vroeër):',
'year'                => 'Vanaf jaar (en vroeër):',

'sp-contributions-newbies'        => 'Wys slegs bydraes deur nuwe rekenings',
'sp-contributions-newbies-sub'    => 'Vir nuwe gebruikers',
'sp-contributions-newbies-title'  => 'Bydraes van nuwe gebruikers',
'sp-contributions-blocklog'       => 'Blokkeer-logboek',
'sp-contributions-deleted'        => 'geskrapte gebruikersbydraes',
'sp-contributions-logs'           => 'logboeke',
'sp-contributions-talk'           => 'bespreking',
'sp-contributions-userrights'     => 'bestuur gebruikersregte',
'sp-contributions-blocked-notice' => 'Hierdie gebruiker is tans geblokkeer. Die laaste inskrywing in die blokkeerlogboek word hieronder vertoon:',
'sp-contributions-search'         => 'Soek na bydraes',
'sp-contributions-username'       => 'IP-adres of gebruikersnaam:',
'sp-contributions-submit'         => 'Vertoon',

# What links here
'whatlinkshere'            => 'Skakels hierheen',
'whatlinkshere-title'      => 'Bladsye wat verwys na "$1"',
'whatlinkshere-page'       => 'Bladsy:',
'linkshere'                => "Die volgende bladsye skakel na '''[[:$1]]''':",
'nolinkshere'              => "Geen bladsye skakel na '''[[:$1]]'''.",
'nolinkshere-ns'           => "Geen bladsye skakel na '''[[:$1]]''' in die verkose naamruimte nie.",
'isredirect'               => 'aanstuurblad',
'istemplate'               => 'insluiting',
'isimage'                  => 'lêerskakel',
'whatlinkshere-prev'       => '{{PLURAL:$1|vorige|vorige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links'      => '← skakels',
'whatlinkshere-hideredirs' => '$1 aansture',
'whatlinkshere-hidetrans'  => '$1 insluitings',
'whatlinkshere-hidelinks'  => '$1 skakels',
'whatlinkshere-hideimages' => '$1 beeldskakels',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                      => 'Blok gebruiker',
'blockip-legend'               => 'Blok gebruiker of IP-adres',
'blockiptext'                  => "Gebruik die vorm hier onder om skryftoegang van 'n sekere IP-adres te blok.
Dit moet net gedoen word om vandalisme te voorkom en in ooreenstemming met [[{{MediaWiki:Policy-url}}|{{SITENAME}}-beleid]].
Vul 'n spesifieke rede hier onder in (haal byvoorbeeld spesifieke bladsye wat gevandaliseer is, aan).",
'ipaddress'                    => 'IP-adres:',
'ipadressorusername'           => 'IP-adres of gebruikernaam:',
'ipbexpiry'                    => 'Duur:',
'ipbreason'                    => 'Rede:',
'ipbreasonotherlist'           => 'Ander rede',
'ipbreason-dropdown'           => '*Algemene redes vir versperring
** Invoeg van valse inligting
** Skrap van bladsyinhoud
** "Spam" van skakels na eksterne webwerwe
** Invoeg van gemors op bladsye
** Intimiderende gedrag (teistering)
** Misbruik van veelvuldige rekeninge
** Onaanvaarbare gebruikersnaam',
'ipbanononly'                  => 'Blokkeer slegs anonieme gebruikers',
'ipbcreateaccount'             => 'Blokkeer registrasie van gebruikers',
'ipbemailban'                  => 'Verbied gebruiker om e-pos te stuur',
'ipbenableautoblock'           => 'Outomaties die IP-adresse van die gebruiker blokkeer',
'ipbsubmit'                    => 'Versper hierdie gebruiker',
'ipbother'                     => 'Ander tydperk:',
'ipboptions'                   => '2 ure:2 hours,1 dag:1 day,3 dae:3 days,1 week:1 week,2 weke:2 weeks,1 maand:1 month,3 maande:3 months,6 maande:6 months,1 jaar:1 year,onbeperk:infinite',
'ipbotheroption'               => 'ander',
'ipbotherreason'               => 'Ander/ekstra rede:',
'ipbhidename'                  => 'Verberg gebruiker van wysigings en lyste',
'ipbwatchuser'                 => 'Hou die gebruiker se bladsy en besprekingsbladsy dop.',
'ipballowusertalk'             => 'Laat gebruiker toe om sy eie besprekingsblad tydens die blokkade te wysig',
'ipb-change-block'             => 'Herblokkeer die gebruiker met hierdie instellings',
'badipaddress'                 => 'Die IP-adres is nie in die regte formaat nie.',
'blockipsuccesssub'            => 'Blokkering het geslaag',
'blockipsuccesstext'           => "[[Special:Contributions/$1|$1]] is geblokkeer.<br />
Sien die [[Special:IPBlockList|IP-bloklys]] vir 'n oorsig van blokkerings.",
'ipb-edit-dropdown'            => 'Werk lys van redes by',
'ipb-unblock-addr'             => 'Deblokkeer $1',
'ipb-unblock'                  => "Deblokkeer 'n gebruiker of IP-adres",
'ipb-blocklist-addr'           => 'Bestaande blokkades vir $1',
'ipb-blocklist'                => 'Wys bestaande blokkades',
'ipb-blocklist-contribs'       => 'Bydraes van $1',
'unblockip'                    => 'Maak IP-adres oop',
'unblockiptext'                => "Gebruik die vorm hier onder om skryftoegang te herstel vir 'n voorheen geblokkeerde IP-adres.",
'ipusubmit'                    => 'Hef blokkade op',
'unblocked'                    => 'Blokkade van [[User:$1|$1]] is opgehef',
'unblocked-id'                 => 'Blokkade $1 is opgehef',
'ipblocklist'                  => 'Geblokkeerde IP-adresse en gebruikers',
'ipblocklist-legend'           => "Soek 'n geblokkeerde gebruiker",
'ipblocklist-username'         => 'Gebruikersnaam of IP adres:',
'ipblocklist-sh-userblocks'    => 'gebruikersblokkades $1',
'ipblocklist-sh-tempblocks'    => 'tydelike blokkades $1',
'ipblocklist-sh-addressblocks' => 'enkel IP-blokkades $1',
'ipblocklist-submit'           => 'Soek',
'blocklistline'                => '$1, $2 het $3 geblok ($4)',
'infiniteblock'                => 'is onbeperk',
'expiringblock'                => 'verval op $1 om $2',
'anononlyblock'                => 'anoniem-alleen',
'noautoblockblock'             => 'autoblok afgeskakel',
'createaccountblock'           => 'skep van gebruikersrekeninge is geblokkeer',
'emailblock'                   => 'e-pos versper',
'blocklist-nousertalk'         => 'kan nie eie besprekingsblad wysig nie',
'ipblocklist-empty'            => 'Die blokkeerlys is leeg.',
'ipblocklist-no-results'       => 'Die IP-adres of gebruikersnaam is nie geblokkeer nie.',
'blocklink'                    => 'blok',
'unblocklink'                  => 'maak oop',
'change-blocklink'             => 'versperring wysig',
'contribslink'                 => 'bydraes',
'blocklogpage'                 => 'Blokkeer-logboek',
'blocklogentry'                => '"[[$1]]" is vir \'n periode van $2 $3 geblok',
'blocklogtext'                 => "Hier is 'n lys van onlangse blokkeer en deblokkeer aksies. Outomaties geblokkeerde IP-adresse word nie vertoon nie. 
Sien die [[Special:IPBlockList|IP-bloklys]] vir geblokkeerde adresse.",
'unblocklogentry'              => 'blokkade van $1 is opgehef:',
'block-log-flags-anononly'     => 'anonieme gebruikers alleenlik',
'block-log-flags-nocreate'     => 'Registrasie van gebruikers buite werking',
'block-log-flags-noautoblock'  => 'outoblokkering is afgeskakel',
'block-log-flags-noemail'      => 'e-pos versper',
'block-log-flags-nousertalk'   => 'kan nie eie besprekingsblad wysig nie',
'block-log-flags-hiddenname'   => 'gebruikernaam versteek',
'ipb_expiry_invalid'           => 'Ongeldige duur.',
'ipb_already_blocked'          => '"$1" is reeds geblok',
'ipb-needreblock'              => '== Hierdie gebruiker is reeds geblokkeer ==
$1 is al geblokkeer.
Wil u die instellings wysig?',
'ipb_cant_unblock'             => 'Fout: Blokkade-ID $1 kan nie gevind word nie.
Die blokkade is moontlik reeds opgehef.',
'ip_range_invalid'             => 'Ongeldige IP waardegebied.',
'blockme'                      => 'Versper my',
'proxyblocker'                 => 'Proxyblokker',
'proxyblocker-disabled'        => 'Die funksie is gedeaktiveer.',
'proxyblocksuccess'            => 'Voltooi.',
'cant-block-while-blocked'     => 'U kan nie ander gebruikers blokkeer terwyl u self geblokkeer is nie.',

# Developer tools
'lockdb'              => 'Sluit databasis',
'unlockdb'            => 'Ontsluit databasis',
'lockdbtext'          => 'As u die databasis sluit, kan geen gebruiker meer bladsye redigeer, voorkeure verander, dophoulyste verander, of ander aksies uitvoer wat veranderinge in die databasis verg nie.
Bevestig asseblief dat dit is wat u wil doen en dat u die databasis sal ontsluit sodra u u instandhouding afgehandel het.',
'unlockdbtext'        => 'As u die databasis ontsluit, kan gebruikers weer bladsye redigeer, voorkeure verander, dophoulyste verander, of ander aksies uitvoer wat veranderinge in die databasis verg.
Bevestig asseblief dat dit is wat u wil doen.',
'lockconfirm'         => 'Ja, ek wil regtig die databasis sluit.',
'unlockconfirm'       => 'Ja, ek wil regtig die databasis ontsluit.',
'lockbtn'             => 'Sluit die databasis',
'unlockbtn'           => 'Ontsluit die databasis',
'locknoconfirm'       => "U het nie die 'bevestig'-blokkie gemerk nie.",
'lockdbsuccesssub'    => 'Databasissluit het geslaag',
'unlockdbsuccesssub'  => 'Databasisslot is verwyder',
'lockdbsuccesstext'   => 'Die {{SITENAME}} databasis is gesluit.
<br />Onthou om dit te ontsluit wanneer u onderhoud afgehandel is.',
'unlockdbsuccesstext' => 'Die {{SITENAME}}-databasis is ontsluit.',
'databasenotlocked'   => 'Die databasis is nie gesluit nie.',

# Move page
'move-page'                    => 'Skuif "$1"',
'move-page-legend'             => 'Skuif bladsy',
'movepagetext'                 => "Die vorm hier onder hernoem 'n bladsy en skuif die hele wysigingsgeskiedenis na die nuwe naam.
Die ou bladsy sal vervang word met 'n aanstuurblad na die nuwe titel.
'''Skakels na die ou bladsytitel sal nie outomaties verander word nie; maak seker dat dubbele aanstuurverwysings nie voorkom nie deur die \"wat skakel hierheen\"-funksie na die skuif te gebruik.''' Dit is u verantwoordelikheid om seker te maak dat skakels steeds wys na waarheen hulle behoort te gaan.

Let daarop dat 'n bladsy '''nie''' geskuif sal word indien daar reeds 'n bladsy met dieselfde titel bestaan nie, tensy dit leeg of 'n aanstuurbladsy is en geen wysigingsgeskiedenis het nie. Dit beteken dat u 'n bladsy kan terugskuif na sy ou titel indien u 'n fout gemaak het, maar u kan nie 'n bestaande bladsy oorskryf nie.

<b>WAARSKUWING!</b>
Hierdie kan 'n drastiese en onverwagte verandering vir 'n gewilde bladsy wees;
maak asseblief seker dat u die gevolge van hierdie aksie verstaan voordat u voortgaan. Gebruik ook die ooreenstemmende besprekingsbladsy om oorleg te pleeg met ander bydraers.",
'movepagetalktext'             => "Die ooreenstemmende besprekingsblad sal outomaties saam geskuif word, '''tensy:'''
*'n Besprekengsblad met die nuwe naam reeds bestaan, of
*U die keuse hier onder deselekteer.

Indien wel sal u self die blad moet skuif of versmelt (indien nodig).",
'movearticle'                  => 'Skuif bladsy',
'movenologin'                  => 'Nie ingeteken nie',
'movenologintext'              => "U moet 'n geregistreerde gebruiker wees en [[Special:UserLogin|ingeteken]]
wees om 'n bladsy te skuif.",
'movenotallowed'               => 'U het nie regte om bladsye te skuif nie.',
'movenotallowedfile'           => 'U het nie die nodige regte om lêers te kan skuif nie.',
'cant-move-user-page'          => 'U het nie die nodige regte om gebruikersbladsye te kan skuif nie.',
'cant-move-to-user-page'       => "U het nie die nodige regte om 'n bladsy na 'n gebruikersbladsy te kan skuif nie.",
'newtitle'                     => 'Na nuwe titel',
'move-watch'                   => 'Hou hierdie bladsy dop',
'movepagebtn'                  => 'Skuif bladsy',
'pagemovedsub'                 => 'Verskuiwing het geslaag',
'movepage-moved'               => '<big>\'\'\'"$1" is geskuif na "$2"\'\'\'</big>',
'movepage-moved-redirect'      => "'n Aanstuur is geskep.",
'movepage-moved-noredirect'    => 'Geen aanstuurblad is geskep nie.',
'articleexists'                => "'n Bladsy met daardie naam bestaan reeds, of die naam wat u gekies het, is nie geldig nie.
Kies asseblief 'n ander naam.",
'cantmove-titleprotected'      => "U kan nie 'n bladsy na die titel skuif nie, omdat die nuwe titel beskerm is teen die skep daarvan.",
'talkexists'                   => "'''Die bladsy self is suksesvol geskuif, maar die besprekingsbladsy is nie geskuif nie omdat een reeds bestaan met die nuwe titel. Smelt hulle asseblief met die hand saam.'''",
'movedto'                      => 'geskuif na',
'movetalk'                     => 'Skuif besprekingsblad ook, indien van toepassing.',
'move-subpages'                => 'Skuif al die subbladsye (maksimaal $1)',
'move-talk-subpages'           => 'Skuif al die subbladsye van die besprekingsblad (maksimaal $1)',
'movepage-page-exists'         => 'Die bladsy $1 bestaan reeds en kan nie outomaties oorskryf word nie.',
'movepage-page-moved'          => 'Die bladsy $1 was na $2 geskuif.',
'movepage-page-unmoved'        => 'Die bladsy $1 kon nie na $2 geskuif word nie.',
'movepage-max-pages'           => 'Die maksimum van $1 {{PLURAL:$1|bladsy|bladsye}} is geskuif. Die oorblywende bladsye na nie outomaties geskuif word nie.',
'1movedto2'                    => '[[$1]] geskuif na [[$2]]',
'1movedto2_redir'              => '[[$1]] geskuif na [[$2]] oor bestaande aanstuur',
'move-redirect-suppressed'     => 'aanstuur is onderdruk',
'movelogpage'                  => 'Skuiflogboek',
'movelogpagetext'              => "Hier onder is 'n lys van geskuifde bladsye.",
'movesubpage'                  => '{{PLURAL:$1|Subbladsy|Subbladsye}}',
'movesubpagetext'              => 'Die {{PLURAL:$1|subbladsy|$1 subbladsye}} van hierdie blad word hieronder gewys.',
'movenosubpage'                => 'Die bladsy het geen subbladsye.',
'movereason'                   => 'Rede:',
'revertmove'                   => 'rol terug',
'delete_and_move'              => 'Skrap en skuif',
'delete_and_move_text'         => '==Skrapping benodig==

Die teikenartikel "[[:$1]]" bestaan reeds. Wil u dit skrap om plek te maak vir die skuif?',
'delete_and_move_confirm'      => 'Ja, skrap die bladsy',
'delete_and_move_reason'       => 'Geskrap om plek te maak vir skuif',
'selfmove'                     => 'Bron- en teikentitels is dieselfde; kan nie bladsy oor homself skuif nie.',
'immobile-source-namespace'    => 'Bladsye in naamruimte "$1" kan nie geskuif word nie',
'immobile-target-namespace'    => 'Bladsye kan nie na naamruimte "$1" geskuif word nie',
'immobile-target-namespace-iw' => "'n Interwiki-skakel is nie 'n geldige bestemming vir die skuif van die bladsy nie.",
'immobile-source-page'         => 'Die bladsy kan nie geskuif word nie.',
'immobile-target-page'         => 'Dit is nie moontlik om na die titel toe te skuif nie.',
'imagenocrossnamespace'        => "'n Medialêer kan nie na 'n ander naamruimte geskuif word nie",
'imagetypemismatch'            => 'Die nuwe lêer se uitbreiding pas nie by die lêertipe nie',
'imageinvalidfilename'         => 'Die nuwe lêernaam is ongeldig',
'fix-double-redirects'         => 'Opdateer alle aansture wat na die oorspronklike titel wys',
'move-leave-redirect'          => "Los 'n aanstuur agter",
'protectedpagemovewarning'     => "'''Waarskuwing:''' Hierdie bladsy kan slegs deur administrateurs geskuif word.",
'semiprotectedpagemovewarning' => "'''Let op:''' Hierdie bladsy kan slegs deur geregistreerde gebruikers geskuif word.",

# Export
'export'            => 'Eksporteer bladsye',
'exporttext'        => 'U kan die teks en geskiedenis van \'n bladsy of bladsye na XML-formaat eksporteer.
Die eksportlêer kan daarna geïmporteer word na enige ander MediaWiki webwerf via die [[Special:Import|importeer bladsy]] skakel.

Verskaf die name van die bladsye wat geëksporteer moet word in die onderstaande veld, een bladsy per lyn, en kies of u alle weergawes (met geskiedenis) of slegs die nuutste weergawe soek.

In die laatste geval kan u ook \'n verwysing gebruik, byvoorbeeld [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] vir die bladsy "{{MediaWiki:Mainpage}}".',
'exportcuronly'     => 'Slegs die nuutste weergawes, sonder volledige geskiedenis',
'export-submit'     => 'Eksporteer',
'export-addcattext' => 'Voeg bladsye by van kategorie:',
'export-addcat'     => 'Voeg by',
'export-addnstext'  => 'Voeg bladsye uit die volgende naamruimtes by:',
'export-addns'      => 'Byvoeg',
'export-download'   => 'Stoor as lêer',
'export-templates'  => 'Sluit sjablone in',
'export-pagelinks'  => "Sluit geskakelde bladsye by tot 'n diepte van:",

# Namespace 8 related
'allmessages'                   => 'Stelselboodskappe',
'allmessagesname'               => 'Naam',
'allmessagesdefault'            => 'Verstekteks',
'allmessagescurrent'            => 'Huidige teks',
'allmessagestext'               => "Hier is 'n lys boodskappe wat in die ''MediaWiki''-naamspasie beskikbaar is.
Gaan na [http://www.mediawiki.org/wiki/Localisation MediaWiki-lokalisasie] en [http://translatewiki.net translatewiki.net] as u wil help om MediaWiki te vertaal.",
'allmessagesnotsupportedDB'     => "Daar is geen ondersteuning vir '''{{ns:special}}:Allmessages''' omdat '''\$wgUseDatabaseMessages''' uitgeskakel is.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter-unmodified' => 'Onveranderd',
'allmessages-filter-all'        => 'Alle',
'allmessages-filter-modified'   => 'Verander',
'allmessages-prefix'            => 'Filter op voorvoegsel:',
'allmessages-language'          => 'Taal:',
'allmessages-filter-submit'     => 'Laat waai',

# Thumbnails
'thumbnail-more'           => 'Vergroot',
'filemissing'              => 'Lêer is weg',
'thumbnail_error'          => 'Fout met die skep van duimnaelsketse: $1',
'djvu_page_error'          => 'DjVu-bladsy buite bereik',
'djvu_no_xml'              => 'Die XML vir die DjVu-lêer kon nie bekom word nie',
'thumbnail_invalid_params' => 'Ongeldige parameters vir duimnaelskets',
'thumbnail_dest_directory' => 'Nie in staat om die gids te skep nie',
'thumbnail_image-type'     => 'Die lêertipe word nie ondersteun nie',
'thumbnail_gd-library'     => 'Onvolledige GD-biblioteek verstellings: die funksie $1 is weg',
'thumbnail_image-missing'  => 'Lêer blyk weg te wees: $1',

# Special:Import
'import'                     => 'Voer bladsye in',
'importinterwiki'            => 'Transwiki-importeer',
'import-interwiki-source'    => 'Bronwiki/bladsy:',
'import-interwiki-history'   => 'Kopieer ook volledige geskiedenis van hierdie bladsy',
'import-interwiki-templates' => 'Sluit alle sjablone in',
'import-interwiki-submit'    => 'Importeer',
'import-interwiki-namespace' => 'Doelnaamruimte:',
'import-upload-filename'     => 'Lêernaam:',
'import-comment'             => 'Opmerking:',
'importstart'                => 'Importeer bladsye...',
'import-revision-count'      => '$1 {{PLURAL:$1|weergawe|weergawes}}',
'importnopages'              => 'Geen bladsye om te importeer nie.',
'importfailed'               => 'Intrek onsuksesvol: $1',
'importunknownsource'        => 'Onbekende brontipe.',
'importcantopen'             => 'Kon nie lêer oopmaak nie',
'importbadinterwiki'         => 'Verkeerde interwiki skakel',
'importnotext'               => 'Leeg of geen teks',
'importsuccess'              => 'Klaar met importering!',
'importnofile'               => 'Geen importlêer was opgelaai nie.',
'importuploaderrorsize'      => 'Oplaai van invoer-lêer het misluk. 
Die lêer is groter as die toelaatbare limiet.',
'importuploaderrorpartial'   => 'Oplaai van invoer-lêer het misluk. 
Die lêer is slegs gedeeltelik opgelaai.',
'importuploaderrortemp'      => "Oplaai van invoer-lêer het misluk.
'n Tydelike gids bestaan nie.",
'import-noarticle'           => 'Geen bladsye om te importeer nie!',
'import-nonewrevisions'      => 'Alle weergawes was voorheen ingevoer.',
'xml-error-string'           => '$1 op reël $2, kolom $3 (greep $4): $5',
'import-upload'              => 'Laai XML-data op',
'import-token-mismatch'      => 'Sessiegegewens is verloor. Probeer asseblief weer.',
'import-invalid-interwiki'   => 'Kan nie vanaf die gespesifiseerde importeer nie.',

# Import log
'importlogpage'                    => 'Invoer logboek',
'import-logentry-upload'           => "[[$1]] ingevoer deur 'n lêer op te laai",
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|weergawe|weergawes}}',
'import-logentry-interwiki'        => 'importeer $1 via transwiki',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|weergawe|weergawes}} vanaf $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'U gebruikerbladsy',
'tooltip-pt-anonuserpage'         => 'Die gebruikerbladsy vir die IP-adres waaronder u redigeer',
'tooltip-pt-mytalk'               => 'U besprekingsbladsy',
'tooltip-pt-anontalk'             => 'Bespreking oor bydraes van hierdie IP-adres',
'tooltip-pt-preferences'          => 'My voorkeure',
'tooltip-pt-watchlist'            => 'Die lys bladsye wat u vir veranderinge dophou',
'tooltip-pt-mycontris'            => 'Lys van u bydraes',
'tooltip-pt-login'                => 'U word aangemoedig om in te teken; dit is egter nie verpligtend nie.',
'tooltip-pt-anonlogin'            => 'U word aangemoedig om in te teken; dit is egter nie verpligtend nie.',
'tooltip-pt-logout'               => 'Teken uit',
'tooltip-ca-talk'                 => 'Bespreking oor die inhoudsbladsy',
'tooltip-ca-edit'                 => 'U kan hierdie bladsy redigeer. Gebruik asseblief die voorskouknop vóór u dit stoor.',
'tooltip-ca-addsection'           => 'Nuwe afdeling',
'tooltip-ca-viewsource'           => 'Hierdie bladsy is beskerm. U kan die bronteks besigtig.',
'tooltip-ca-history'              => 'Ouer weergawes van hierdie bladsy.',
'tooltip-ca-protect'              => 'Beskerm hierdie bladsy',
'tooltip-ca-unprotect'            => 'Verwyder beskerming vir die bladsy',
'tooltip-ca-delete'               => 'Skrap hierdie bladsy',
'tooltip-ca-undelete'             => 'Herstel die bydraes aan hierdie bladsy voordat dit geskrap is',
'tooltip-ca-move'                 => 'Skuif hierdie bladsy',
'tooltip-ca-watch'                => 'Voeg hierdie bladsy by u dophoulys',
'tooltip-ca-unwatch'              => 'Verwyder hierdie bladsy van u dophoulys',
'tooltip-search'                  => 'Deursoek {{SITENAME}}',
'tooltip-search-go'               => "Gaan na 'n bladsy met hierdie naam indien dit bestaan",
'tooltip-search-fulltext'         => 'Deursoek bladsye vir hierdie teks',
'tooltip-p-logo'                  => 'Besoek die tuisblad',
'tooltip-n-mainpage'              => 'Besoek die Tuisblad',
'tooltip-n-mainpage-description'  => 'Gaan na die tuisblad',
'tooltip-n-portal'                => 'Meer oor die projek, wat u kan doen, nuttige skakels',
'tooltip-n-currentevents'         => "'n Plek waar almal gesellig kan verkeer",
'tooltip-n-recentchanges'         => "'n Lys van onlangse wysigings",
'tooltip-n-randompage'            => "Laai 'n lukrake bladsye",
'tooltip-n-help'                  => 'Vind meer uit oor iets',
'tooltip-t-whatlinkshere'         => "'n Lys bladsye wat hierheen skakel",
'tooltip-t-recentchangeslinked'   => 'Onlangse wysigings aan bladsye wat vanaf hierdie bladsy geskakel is',
'tooltip-feed-rss'                => 'RSS-voed vir hierdie bladsy',
'tooltip-feed-atom'               => 'Atom-voed vir hierdie bladsy',
'tooltip-t-contributions'         => "Bekyk 'n lys van bydraes deur hierdie gebruiker",
'tooltip-t-emailuser'             => "Stuur 'n e-pos aan hierdie gebruiker",
'tooltip-t-upload'                => 'Laai lêers op',
'tooltip-t-specialpages'          => "'n Lys van al die spesiale bladsye",
'tooltip-t-print'                 => 'Drukbare weergawe van hierdie bladsy',
'tooltip-t-permalink'             => "'n Permanente skakel na hierdie weergawe van die bladsy",
'tooltip-ca-nstab-main'           => 'Bekyk die inhoudbladsy',
'tooltip-ca-nstab-user'           => 'Bekyk die gebruikerbladsy',
'tooltip-ca-nstab-media'          => 'Bekyk die mediabladsy',
'tooltip-ca-nstab-special'        => "Hierdie is 'n spesiale bladsy; u kan dit nie wysig nie",
'tooltip-ca-nstab-project'        => 'Bekyk die projekbladsy',
'tooltip-ca-nstab-image'          => 'Bekyk die leêrbladsy',
'tooltip-ca-nstab-mediawiki'      => 'Bekyk die stelselboodskap',
'tooltip-ca-nstab-template'       => 'Bekyk die sjabloon',
'tooltip-ca-nstab-help'           => 'Bekyk die hulpbladsy',
'tooltip-ca-nstab-category'       => 'Bekyk die kategoriebladsy',
'tooltip-minoredit'               => "Dui aan hierdie is 'n klein wysiging",
'tooltip-save'                    => 'Stoor u wysigings',
'tooltip-preview'                 => "Sien 'n voorskou van u wysigings, gebruik dit voor u die blad stoor!",
'tooltip-diff'                    => 'Wys watter veranderinge u aan die teks gemaak het.',
'tooltip-compareselectedversions' => 'Vergelyk die twee gekose weergawes van hierdie blad.',
'tooltip-watch'                   => 'Voeg hierdie blad by u dophoulys',
'tooltip-recreate'                => 'Herskep hierdie bladsy al is dit voorheen geskrap',
'tooltip-upload'                  => 'Begin oplaai',
'tooltip-rollback'                => '"Terugrol" rol met een kliek wysiging(s) terug wat die laaste gebruiker aan hierdie bladsy aangebring het.',
'tooltip-undo'                    => 'Met "ongedaan maak" maak u hierdie wysiging ongedaan en land u in die wysigingsvenster.
U kan daar \'n wysigingsopsomming byvoeg.',

# Stylesheets
'common.css' => '/** Gemeenskaplike CSS vir alle omslae */',

# Attribution
'anonymous'        => 'Anonieme {{PLURAL:$1|gebruiker|gebruikers}} van {{SITENAME}}',
'siteuser'         => '{{SITENAME}} gebruiker $1',
'anonuser'         => 'Anonieme {{SITENAME}}-gebruiker $1',
'lastmodifiedatby' => 'Hierdie bladsy is laas op $1 om $2 deur $3 gewysig.',
'othercontribs'    => 'Gebaseer op werk van $1.',
'others'           => 'ander',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|gebruikers|gebruikers}} $1',
'anonusers'        => 'Anonieme {{SITENAME}}-{{PLURAL:$2|gebruiker|gebruikers}} $1',
'creditspage'      => 'Outeursblad',
'nocredits'        => 'Geen outeursinligting is vir hierdie bladsy nie beskikbaar nie.',

# Spam protection
'spamprotectiontitle' => 'Spamfilter',
'spamprotectiontext'  => "Die bladsy wat u wou stoor was geblok deur die gemorspos-filter.
Hierdie situasie was waarskynlik deur 'n skakel na 'n eksterne webtuiste op ons swartlys veroorsaak.",
'spamprotectionmatch' => 'Die volgende teks is wat ons gemorspos-filter geaktiveer het: $1',

# Info page
'infosubtitle'   => 'Inligting vir bladsy',
'numedits'       => 'Aantal wysigings (bladsy): $1',
'numtalkedits'   => 'Aantal wysigings (besprekingsblad): $1',
'numwatchers'    => 'Aantal dophouers: $1',
'numauthors'     => 'Aantal outeurs (bladsy): $1',
'numtalkauthors' => 'Aantal outeurs (besprekingsblad): $1',

# Skin names
'skinname-standard'    => 'Standaard',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Keulen blou',

# Math options
'mw_math_png'    => 'Gebruik altyd PNG.',
'mw_math_simple' => 'Gebruik HTML indien dit eenvoudig is, andersins PNG.',
'mw_math_html'   => 'Gebruik HTML wanneer moontlik, andersins PNG.',
'mw_math_source' => 'Los as TeX (vir teksblaaiers).',
'mw_math_modern' => 'Moderne blaaiers.',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Kon nie verbeeld nie',
'math_unknown_error'    => 'onbekende fout',
'math_unknown_function' => 'onbekende funksie',
'math_lexing_error'     => 'leksikale fout',
'math_syntax_error'     => 'sintaksfout',
'math_image_error'      => 'PNG-omskakeling het gefaal.
Kontroleer of latex, dvips en gs korrek geïnstalleer is en skakel om',

# Patrolling
'markaspatrolleddiff'                 => 'Merk as gekontroleerd',
'markaspatrolledtext'                 => 'Merk hierdie bladsy as gekontroleerd',
'markedaspatrolled'                   => 'As gekontroleerd gemerk',
'markedaspatrolledtext'               => 'Die gekose weergawe is as gekontroleerd gemerk.',
'rcpatroldisabled'                    => 'Onlangse Wysigingskontrolering buiten staat gestel',
'rcpatroldisabledtext'                => 'Die Onlangse Wysigingskontroleringsfunksie is tans buiten staat gestel.',
'markedaspatrollederror'              => 'Kan nie as gekontroleerd merk nie',
'markedaspatrollederrortext'          => "U moet 'n weergawe spesifiseer om as gekontroleerd te merk.",
'markedaspatrollederror-noautopatrol' => 'U kan nie u eie veranderinge as gekontroleerd merk nie.',

# Patrol log
'patrol-log-page'      => 'Kontroleringslogboek',
'patrol-log-header'    => 'Die logboek wys weergawes wat as gekontroleer gemerk is.',
'patrol-log-line'      => 'merk $1 van $2 as gekontroleer $3',
'patrol-log-auto'      => '(outomaties)',
'patrol-log-diff'      => 'weergawe $1',
'log-show-hide-patrol' => 'Nasienlogboek $1',

# Image deletion
'deletedrevision'                 => 'Ou weergawe $1 geskrap',
'filedeleteerror-short'           => 'Fout met verwydering van lêer: $1',
'filedeleteerror-long'            => 'Foute het voorgekom by die skraping van die lêer:

$1',
'filedelete-missing'              => 'Die lêer "$1" kan nie geskrap word nie, want dit bestaan nie.',
'filedelete-current-unregistered' => 'Die gespesifiseerde lêer "$1" is nie in die databasis nie.',

# Browsing diffs
'previousdiff' => '← Ouer wysiging',
'nextdiff'     => 'Nuwer wysiging →',

# Media information
'mediawarning'         => "'''Waarskuwing''': hierdie lêer bevat moontlik programkode wat u stelsel skade kan berokken.<hr />",
'imagemaxsize'         => "Beperk beeldgrootte tot:<br />''(vir lêerbeskrywingsbladsye)''",
'thumbsize'            => 'Grootte van duimnaelskets:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|bladsy|bladsye}}',
'file-info'            => '(lêergrootte: $1, MIME-tipe: $2)',
'file-info-size'       => '($1 × $2 pixels, lêergrootte: $3, MIME type: $4)',
'file-nohires'         => '<small>Geen hoër resolusie is beskikbaar nie.</small>',
'svg-long-desc'        => '(SVG-lêer, nominaal $1 × $2 pixels, lêergrootte: $3)',
'show-big-image'       => 'Volle resolusie',
'show-big-image-thumb' => '<small>Grootte van hierdie voorskou: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'herhalend',
'file-info-gif-frames' => '$1 {{PLURAL:$1|raam|rame}}',

# Special:NewFiles
'newimages'             => 'Gallery van nuwe beelde',
'imagelisttext'         => "Hier onder is a lys van '''$1''' {{PLURAL:$1|lêer|lêers}}, $2 gesorteer.",
'newimages-summary'     => 'Die spesiale bladsy wys die nuutste lêers wat na die wiki opgelaai is.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Lêernaam (of deel daarvan):',
'showhidebots'          => '($1 robotte)',
'noimages'              => 'Niks te sien nie.',
'ilsubmit'              => 'Soek',
'bydate'                => 'volgens datum',
'sp-newimages-showfrom' => 'Wys nuwe lêers vanaf $2, $1',

# Bad image list
'bad_image_list' => "Die formaat is as volg:

Slegs lys-items (lyne wat met * begin) word verwerk.
Die eerste skakel op 'n lyn moet na 'n ongewenste lêer skakel.
Enige opeenvolgende skakels op dieselfde lyn word as uitsonderings beskou, bv. blaaie waar die lêer inlyn kan voorkom.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Die lêer bevat aanvullende inligting wat moontlik deur 'n digitale kamera of skandeerder bygevoeg is. 
As die lêer verander is, mag sekere inligting nie meer ooreenkom met die van die gewysigde lêer nie.",
'metadata-expand'   => 'Wys uitgebreide gegewens',
'metadata-collapse' => 'Steek uitgebreide gegewens weg',
'metadata-fields'   => 'Die EXIF-metadatavelde wat in die boodskap gelys is sal op die beeld se bladsy ingesluit word as die metadatabel ingevou is. 
Ander velde sal versteek wees.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Wydte',
'exif-imagelength'                 => 'Hoogte',
'exif-bitspersample'               => 'Grepe per komponent',
'exif-compression'                 => 'Kompressiemetode',
'exif-photometricinterpretation'   => 'Pixel-komposisie',
'exif-orientation'                 => 'Oriëntasie',
'exif-samplesperpixel'             => 'Aantal komponente',
'exif-planarconfiguration'         => 'Datastruktuur',
'exif-ycbcrpositioning'            => 'Y- en C-posisionering',
'exif-xresolution'                 => 'Horisontale resolusie',
'exif-yresolution'                 => 'Vertikale resolusie',
'exif-resolutionunit'              => 'Eenheid X en Y resolusie',
'exif-rowsperstrip'                => 'Rye per strook',
'exif-stripbytecounts'             => 'Grepe per gekompakteerde strook',
'exif-jpeginterchangeformatlength' => 'Grepe van JPEG-gegewens',
'exif-transferfunction'            => 'Oordragfunksie',
'exif-primarychromaticities'       => 'Chromasiteit van primêre kleure',
'exif-referenceblackwhite'         => 'Paar swart en wit verwysingswaardes',
'exif-datetime'                    => 'Tydstip laaste lêerwysiging',
'exif-imagedescription'            => 'Beeldtitel',
'exif-make'                        => 'Kamera vervaardiger:',
'exif-model'                       => 'Kamera model',
'exif-software'                    => 'Sagteware gebruik',
'exif-artist'                      => 'Outeur',
'exif-copyright'                   => 'Kopiereghouer',
'exif-exifversion'                 => 'Exif weergawe',
'exif-flashpixversion'             => 'Ondersteunde Flashpix-weergawe',
'exif-colorspace'                  => 'Kleurruimte',
'exif-componentsconfiguration'     => 'Betekenis van elke komponent',
'exif-compressedbitsperpixel'      => 'Beeldkompressiemetode',
'exif-pixelydimension'             => 'Bruikbare beeldbreedte',
'exif-pixelxdimension'             => 'Bruikbare beeldhoogte',
'exif-makernote'                   => 'Notas van vervaardiger',
'exif-usercomment'                 => 'Opmerkings',
'exif-relatedsoundfile'            => 'Verwante klanklêer',
'exif-datetimeoriginal'            => 'Gegewens opgestel op',
'exif-datetimedigitized'           => 'Datum en tyd van digitalisering',
'exif-subsectime'                  => 'Datum tyd subsekondes',
'exif-subsectimeoriginal'          => 'Subsekondes tydstip datagenerasie',
'exif-subsectimedigitized'         => 'Subsekondes tydstip digitalisasie',
'exif-exposuretime'                => 'Beligtingstyd',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'F-getal',
'exif-exposureprogram'             => 'Beligtingsprogram',
'exif-spectralsensitivity'         => 'Spektrale gevoeligheid',
'exif-isospeedratings'             => 'ISO/ASA-waarde',
'exif-oecf'                        => 'Opto-elektroniese konversiefaktor',
'exif-shutterspeedvalue'           => 'Sluitersnelheid',
'exif-aperturevalue'               => 'Diafragma',
'exif-brightnessvalue'             => 'Helderheid',
'exif-exposurebiasvalue'           => 'Beligtingskompensasie',
'exif-maxaperturevalue'            => 'Maksimale diafragma-opening',
'exif-subjectdistance'             => 'Afstand na onderwerp',
'exif-meteringmode'                => 'Metode van ligmeting',
'exif-lightsource'                 => 'Ligbron',
'exif-flash'                       => 'Flits',
'exif-focallength'                 => 'Brandpuntsafstand',
'exif-subjectarea'                 => 'Objekruimte',
'exif-flashenergy'                 => 'Flitssterkte',
'exif-spatialfrequencyresponse'    => 'Ruimtelike frekwensiereaksie',
'exif-focalplanexresolution'       => 'Brandpuntsvlak-X-resolusie',
'exif-focalplaneyresolution'       => 'Brandpuntsvlak-Y-resolusie',
'exif-focalplaneresolutionunit'    => 'Eenheid CCD-resolusie',
'exif-subjectlocation'             => 'Objekligging',
'exif-exposureindex'               => 'Beligtingsindeks',
'exif-sensingmethod'               => 'Meetmetode',
'exif-filesource'                  => 'Lêerbron',
'exif-scenetype'                   => 'Soort toneel',
'exif-cfapattern'                  => 'CFA-patroon',
'exif-customrendered'              => 'Aangepaste beeldverwerking',
'exif-exposuremode'                => 'Beligtingsinstelling',
'exif-whitebalance'                => 'Witbalans',
'exif-digitalzoomratio'            => 'Digitale zoomfaktor',
'exif-focallengthin35mmfilm'       => 'Brandpuntsafstand (35mm-ekwivalent)',
'exif-scenecapturetype'            => 'Soort opname',
'exif-gaincontrol'                 => 'Toneelbeheer',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Versadiging',
'exif-sharpness'                   => 'Skerpte',
'exif-subjectdistancerange'        => 'Bereik objekafstand',
'exif-imageuniqueid'               => 'Unieke beeld ID',
'exif-gpsversionid'                => 'GPS-merkerweergawe',
'exif-gpslatituderef'              => 'Noorder- of suiderbreedte',
'exif-gpslatitude'                 => 'Breedtegraad',
'exif-gpslongituderef'             => 'Ooster- of westerlengte',
'exif-gpslongitude'                => 'Lengtegraad',
'exif-gpsaltituderef'              => 'Hoogteverwysing',
'exif-gpsaltitude'                 => 'Hoogte',
'exif-gpstimestamp'                => 'GPS-tyd (atoomhorlosie)',
'exif-gpssatellites'               => 'Satelliete gebruik vir meting',
'exif-gpsstatus'                   => 'Ontvangerstatus',
'exif-gpsmeasuremode'              => 'Meetmodus',
'exif-gpsdop'                      => 'Meetpresisie',
'exif-gpsspeedref'                 => 'Snelheid eenheid',
'exif-gpsspeed'                    => 'Snelheid van GPS-ontvanger',
'exif-gpstrackref'                 => 'Verwysing vir bewegingsrigting',
'exif-gpstrack'                    => 'Bewegingsrigting',
'exif-gpsimgdirectionref'          => 'Verwysing vir rigting van beeld',
'exif-gpsimgdirection'             => 'Rigting van beeld',
'exif-gpsmapdatum'                 => 'Daar word van aardmeetkundige ondersoekdata gebruik gemaak',
'exif-gpsdestlatituderef'          => 'Verwysing na breedtelyn van die bestemming',
'exif-gpsdestlatitude'             => 'Breedtegraad bestemming',
'exif-gpsdestlongituderef'         => 'Verwysing na lengtelyn van die bestemming',
'exif-gpsdestlongitude'            => 'Lengtegraad bestemming',
'exif-gpsdestbearingref'           => 'Verwysing na ligging van die bestemming',
'exif-gpsdestbearing'              => 'Rigting na bestemming',
'exif-gpsdestdistance'             => 'Afstand na bestemming',
'exif-gpsprocessingmethod'         => 'GPS-verwerkingsmetode',
'exif-gpsareainformation'          => 'Naam van GPS-gebied',
'exif-gpsdatestamp'                => 'GPS-datum',
'exif-gpsdifferential'             => 'Differensiële GPS-korreksie',

# EXIF attributes
'exif-compression-1' => 'Ongekompakteerd',

'exif-unknowndate' => 'Datum onbekend',

'exif-orientation-1' => 'Normaal',
'exif-orientation-2' => 'Horisontaal gespieël',
'exif-orientation-3' => '180° gedraai',
'exif-orientation-4' => 'Vertikaal gespieël',
'exif-orientation-6' => '90° regs gedraai',
'exif-orientation-8' => '90° links gedraai',

'exif-componentsconfiguration-0' => 'bestaan nie',

'exif-exposureprogram-0' => 'Nie bepaal',
'exif-exposureprogram-1' => 'Handmatig',
'exif-exposureprogram-2' => 'Normale program',
'exif-exposureprogram-3' => 'Diafragma-prioriteit',
'exif-exposureprogram-4' => 'Sluiterprioriteit',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Onbekend',
'exif-meteringmode-1'   => 'Gemiddeld',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'Multi-spot',
'exif-meteringmode-5'   => 'Patroon',
'exif-meteringmode-6'   => 'Gedeeltelik',
'exif-meteringmode-255' => 'Ander',

'exif-lightsource-0'   => 'Onbekend',
'exif-lightsource-1'   => 'Sonlig',
'exif-lightsource-2'   => 'Fluoresserend',
'exif-lightsource-4'   => 'Flits',
'exif-lightsource-9'   => 'Mooi weer',
'exif-lightsource-10'  => 'Bewolkte weer',
'exif-lightsource-11'  => 'Skaduwee',
'exif-lightsource-17'  => 'Standaard lig A',
'exif-lightsource-18'  => 'Standaard lig B',
'exif-lightsource-19'  => 'Standaard lig C',
'exif-lightsource-255' => 'Ander ligbron',

# Flash modes
'exif-flash-fired-0'    => 'Flits het nie afgegaan',
'exif-flash-fired-1'    => 'Flits het afgegaan',
'exif-flash-mode-3'     => 'outomatiese modus',
'exif-flash-function-1' => 'Geen flitserfunksie',

'exif-focalplaneresolutionunit-2' => 'duim',

'exif-sensingmethod-1' => 'Ongedefineer',
'exif-sensingmethod-7' => 'Drielynige sensor',
'exif-sensingmethod-8' => 'Kleurvolgende lynsensor',

'exif-scenetype-1' => "'n Direk gefotografeerde beeld",

'exif-customrendered-0' => 'Normale verwerking',
'exif-customrendered-1' => 'Aangepaste verwerking',

'exif-exposuremode-0' => 'Outomatiese beligting',
'exif-exposuremode-1' => 'Handmatige beligting',

'exif-whitebalance-0' => 'Outomatiese witbalans',
'exif-whitebalance-1' => 'Handmatige witbalans',

'exif-scenecapturetype-0' => 'Standaard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nagtoneel',

'exif-gaincontrol-0' => 'Geen',

'exif-contrast-0' => 'Normaal',
'exif-contrast-1' => 'Sag',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normaal',
'exif-saturation-1' => 'Laag',
'exif-saturation-2' => 'Hoog',

'exif-sharpness-0' => 'Normaal',
'exif-sharpness-1' => 'Sag',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Onbekend',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Naby',
'exif-subjectdistancerange-3' => 'Vêr weg',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Noorderbreedte',
'exif-gpslatitude-s' => 'Suiderbreedte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Oosterlengte',
'exif-gpslongitude-w' => 'Westerlengte',

'exif-gpsstatus-a' => 'Besig met meting',

'exif-gpsmeasuremode-2' => '2-dimensionele meting',
'exif-gpsmeasuremode-3' => '3-dimensionele meting',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer per huur',
'exif-gpsspeed-m' => 'Myl per huur',
'exif-gpsspeed-n' => 'Knope',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Regte rigting',
'exif-gpsdirection-m' => 'Magnetiese rigting',

# External editor support
'edit-externally'      => "Wysig hierdie lêer met 'n eksterne program",
'edit-externally-help' => '(Sien [http://www.mediawiki.org/wiki/Manual:External_editors instruksies] vir meer inligting)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alles',
'imagelistall'     => 'alle',
'watchlistall2'    => 'alles',
'namespacesall'    => 'alle',
'monthsall'        => 'alle',
'limitall'         => 'alle',

# E-mail address confirmation
'confirmemail'             => 'Bevestig e-posadres',
'confirmemail_noemail'     => "U het nie 'n geldige e-posadres in u [[Special:Preferences|gebruikersvoorkeure]] gestel nie.",
'confirmemail_text'        => "Hierdie wiki vereis dat u e-posadres bevestig word voordat epos-funksies gebruik word. Klik onderstaande knoppie om 'n bevestigingspos na u adres te stuur. Die pos sal 'n skakel met 'n kode insluit; maak hierdie skakel oop in u webblaaier om te bevestig dat die adres geldig is.",
'confirmemail_send'        => "Pos 'n bevestigingkode",
'confirmemail_sent'        => 'Bevestigingpos gestuur.',
'confirmemail_sendfailed'  => '{{SITENAME}} kon nie u bevestigings-epos uitstuur nie.
Kontroleer u e-posadres vir ongeldige karakters.

Die e-posprogram meld: $1',
'confirmemail_invalid'     => 'Ongeldige bevestigingkode. Die kode het moontlik verval.',
'confirmemail_needlogin'   => 'U moet $1 om u e-posadres te bevestig.',
'confirmemail_success'     => 'U e-posadres is bevestig. U kan nou aanteken en die wiki gebruik.',
'confirmemail_loggedin'    => 'U e-posadres is nou bevestig.',
'confirmemail_error'       => 'Iets het foutgegaan met die stoor van u bevestiging.',
'confirmemail_subject'     => '{{SITENAME}}: E-posadres-bevestiging',
'confirmemail_body'        => 'Iemand, waarskynlik u vanaf IP-adres: $1, het \'n rekening "$2" met hierdie e-posadres by {{SITENAME}} geregistreer.

Om te bevestig dat hierdie adres werklik aan u behoort, en om die posfasiliteite by {{SITENAME}} te aktiveer, besoek hierdie skakel in u webblaaier:

$3

Indien dit nié u was nie, ignoreer bloot die skakel (en hierdie e-pos).
Volg hierdie skakel om die bevestiging van u e-posadres te kanselleer:

$5

Hierdie bevestigingkode verval om $4.',
'confirmemail_invalidated' => 'Die e-pos bevestiging is gekanselleer.',
'invalidateemail'          => 'Kanselleer e-pos bevestiging',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-invoeging van sjablone is afgeskakel]',
'scarytranscludefailed'   => '[Sjabloon $1 kon nie gelaai word nie]',
'scarytranscludetoolong'  => '[Die URL is te lank]',

# Trackbacks
'trackbackbox'      => 'Terugverwysende bladsye vir die blad:<br />
$1',
'trackbackremove'   => '([$1 Skrap])',
'trackbacklink'     => 'Verwysende bladsy',
'trackbackdeleteok' => 'Die verwysende bladsy is suksesvol verwyder.',

# Delete conflict
'deletedwhileediting' => "'''Let op''': die bladsy is verwyder terwyl u besig was om dit te wysig!",
'confirmrecreate'     => "Gebruiker [[User:$1|$1]] ([[User talk:$1|bespreek]]) het hierdie blad uitgevee ná u begin redigeer het met rede: : ''$2''
Bevestig asseblief dat u regtig hierdie blad oor wil skep.",
'recreate'            => 'Herskep',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Verwyder die kas van hierdie blad?',

# Multipage image navigation
'imgmultipageprev' => '← vorige bladsy',
'imgmultipagenext' => 'volgende bladsy →',
'imgmultigo'       => 'Laat waai!',
'imgmultigoto'     => 'Gaan na bladsy $1',

# Table pager
'ascending_abbrev'         => 'op',
'descending_abbrev'        => 'af',
'table_pager_next'         => 'Volgende bladsy',
'table_pager_prev'         => 'Vorige bladsy',
'table_pager_first'        => 'Eerste bladsy',
'table_pager_last'         => 'Laaste bladsy',
'table_pager_limit'        => 'Wys $1 resultate per bladsy',
'table_pager_limit_submit' => 'Laat waai',
'table_pager_empty'        => 'Geen resultate',

# Auto-summaries
'autosumm-blank'   => 'Alle inhoud uit bladsy verwyder',
'autosumm-replace' => "Vervang bladsyinhoud met '$1'",
'autoredircomment' => 'Stuur aan na [[$1]]',
'autosumm-new'     => "Nuwe bladsy geskep met '$1'",

# Size units
'size-bytes'     => '$1 G',
'size-kilobytes' => '$1 KG',
'size-megabytes' => '$1 MG',
'size-gigabytes' => '$1 GG',

# Live preview
'livepreview-loading' => 'Laai tans…',
'livepreview-ready'   => 'Laai tans… Gereed!',
'livepreview-failed'  => 'Lewendige voorskou het gefaal.
Probeer normale voorskou.',
'livepreview-error'   => 'Verbinding het misluk: $1 "$2".
Probeer normale voorskou.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Veranderinge nuwer as $1 {{PLURAL:$1|sekonde|sekondes}} mag moontlik nie gewys word nie.',
'lag-warn-high'   => 'Weens hoë databasis toevoer word wysigings nuwer as $1 {{PLURAL:$1|sekonde|sekondes}} moontlik nie in die lys vertoon nie.',

# Watchlist editor
'watchlistedit-numitems'       => 'U dophoulys bevat {{PLURAL:$1|1 bladsy|$1 bladsye}}, besprekingsbladsye uitgesluit.',
'watchlistedit-noitems'        => 'U dophoulys bevat geen bladsye.',
'watchlistedit-normal-title'   => 'Wysig dophoulys',
'watchlistedit-normal-legend'  => 'Verwyder titels van dophoulys',
'watchlistedit-normal-explain' => "Die bladsye in u dophoulys word hier onder vertoon. 
Selekteer die titels wat verwyder moet word en klik op 'Verwyder Titels' onder aan die bladsy.
Alternatiewelik kan u die [[Special:Watchlist/raw|bronkode wysig]].",
'watchlistedit-normal-submit'  => 'Verwyder Titels',
'watchlistedit-normal-done'    => 'Daar is {{PLURAL:$1|1 bladsy|$1 bladsye}} van u dophoulys verwyder:',
'watchlistedit-raw-title'      => 'Wysig u dophoulys se bronkode',
'watchlistedit-raw-legend'     => 'Wysig u dophoulys se bronkode',
'watchlistedit-raw-explain'    => "Die bladsye in u dophoulys word hier onder vertoon.
U kan die lys wysig deur titels by te sit of te verwyder (een bladsy per lyn).
As u klaar is, klik op 'Opdateer Dophoulys' onder aan die bladsy.
U kan ook die [[Special:Watchlist/edit|standaard opdaterigskerm gebruik]].",
'watchlistedit-raw-titles'     => 'Titels:',
'watchlistedit-raw-submit'     => 'Opdateer Dophoulys',
'watchlistedit-raw-done'       => 'U dophoulys is opgedateer.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titel|$1 titels}} was bygevoeg:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titel|$1 titels}} verwyder:',

# Watchlist editing tools
'watchlisttools-view' => 'Besigtig veranderinge',
'watchlisttools-edit' => 'Bekyk en wysig dophoulys',
'watchlisttools-raw'  => 'Wysig bronkode',

# Core parser functions
'unknown_extension_tag' => 'Onbekende etiket "$1"',
'duplicate-defaultsort' => 'Waarskuwing: Die standaardsortering "$2" kry voorrang voor die sortering "$1".',

# Special:Version
'version'                       => 'Weergawe',
'version-extensions'            => 'Uitbreidings geïnstalleer',
'version-specialpages'          => 'Spesiale bladsye',
'version-parserhooks'           => 'Ontlederhoeke',
'version-variables'             => 'Veranderlikes',
'version-other'                 => 'Ander',
'version-mediahandlers'         => 'Mediaverwerkers',
'version-hooks'                 => 'Hoeke',
'version-extension-functions'   => 'Uitbreidingsfunksies',
'version-parser-extensiontags'  => 'Ontleder-uitbreidingsetikette',
'version-parser-function-hooks' => 'Ontleder-funksiehoeke',
'version-hook-name'             => 'Hoek naam',
'version-hook-subscribedby'     => 'Gebruik deur',
'version-version'               => '(Weergawe $1)',
'version-license'               => 'Lisensie',
'version-software'              => 'Geïnstalleerde sagteware',
'version-software-product'      => 'Produk',
'version-software-version'      => 'Weergawe',

# Special:FilePath
'filepath'         => 'Lêerpad',
'filepath-page'    => 'Lêer:',
'filepath-submit'  => 'Pad',
'filepath-summary' => 'Die spesiale bladsy wys die volledige pad vir \'n lêer. 
Beelde word in hulle volle resolusie gewys. Ander lêertipes word direk met hulle MIME-geskakelde programme geopen.

Sleutel die lêernaam in sonder die "{{ns:file}}:" voorvoegsel.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Soek duplikaat lêers',
'fileduplicatesearch-summary'  => 'Soek na duplikaat lêers volgends hul hashwaardes.

Verskaf die lêernaam sonder die "{{ns:file}}:" voorvoegsel.',
'fileduplicatesearch-legend'   => "Soek vir 'n duplikaat",
'fileduplicatesearch-filename' => 'Lêernaam:',
'fileduplicatesearch-submit'   => 'Soek',
'fileduplicatesearch-info'     => '$1 × $2 pixels<br />Lêergrootte: $3<br />MIME-tipe: $4',
'fileduplicatesearch-result-1' => 'Die lêer "$1" het geen identiese duplikate nie.',
'fileduplicatesearch-result-n' => 'Die lêer "$1" het {{PLURAL:$2|een identiese duplikaat|$2 identiese duplikate}}.',

# Special:SpecialPages
'specialpages'                   => 'Spesiale bladsye',
'specialpages-note'              => '----
* Normale spesiale bladsye.
* <strong class="mw-specialpagerestricted">Beperkte spesiale bladsye.</strong>',
'specialpages-group-maintenance' => 'Onderhoud verslae',
'specialpages-group-other'       => 'Ander spesiale bladsye',
'specialpages-group-login'       => 'Inteken / aansluit',
'specialpages-group-changes'     => 'Onlangse wysigings en boekstawings',
'specialpages-group-media'       => 'Media verslae en oplaai',
'specialpages-group-users'       => 'Gebruikers en regte',
'specialpages-group-highuse'     => 'Baie gebruikte bladsye',
'specialpages-group-pages'       => 'Lyste van bladsye',
'specialpages-group-pagetools'   => 'Bladsyhulpmiddels',
'specialpages-group-wiki'        => 'Wiki data en hulpmiddels',
'specialpages-group-redirects'   => 'Aanstuur gewone bladsye',
'specialpages-group-spam'        => 'Spam-hulpmiddels',

# Special:BlankPage
'blankpage'              => 'Leë bladsy',
'intentionallyblankpage' => 'Die bladsy is bewustelik leeg gelaat',

# Special:Tags
'tags'                    => 'Geldige wysigings-etikette',
'tag-filter'              => '[[Special:Tags|Etiketfilter]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Etikette',
'tags-intro'              => 'Hierdie bladsy bevat die etikette waarmee die sagteware wysigings mee kan merk, en hul betekenis.',
'tags-tag'                => 'Etiketnaam',
'tags-display-header'     => 'Weergawe in wysigingslyste',
'tags-description-header' => 'Volledige beskrywing van betekenis',
'tags-hitcount-header'    => 'Geëtiketteerde veranderings',
'tags-edit'               => 'wysig',
'tags-hitcount'           => '$1 {{PLURAL:$1|wysiging|wysigings}}',

# Database error messages
'dberr-header'      => "Die wiki het 'n probleem",
'dberr-problems'    => 'Jammer! Die webwerf ondervind op die oomblik tegniese probleme.',
'dberr-again'       => "Wag 'n paar minute en probeer dan weer.",
'dberr-info'        => '(Kan nie die databasisbediener kontak nie: $1)',
'dberr-usegoogle'   => 'Tot tyd en wyl kan u inligting op Google soek.',
'dberr-outofdate'   => 'Let daarop dat hulle indekse van ons inhoud moontlik verouderd mag wees.',
'dberr-cachederror' => "Hierdie is 'n gekaste kopie van die aangevraagde blad, en dit mag moontlik nie op datum wees nie.",

# HTML forms
'htmlform-invalid-input'       => 'Daar is probleme met van die ingevoerde waardes',
'htmlform-select-badoption'    => 'Die ingevoerde waarde is ongeldig.',
'htmlform-int-invalid'         => "Die ingevoer waarde is nie 'n heeltal nie.",
'htmlform-float-invalid'       => "Die waarde wat u ingevoer het is nie 'n getal nie.",
'htmlform-int-toolow'          => 'Die ingevoerde waarde is laer as die minimum van $1',
'htmlform-int-toohigh'         => 'Die ingevoerde waarde is groter as die maksimum van $1',
'htmlform-submit'              => 'Dien in',
'htmlform-reset'               => 'Maak wysigings ongedaan',
'htmlform-selectorother-other' => 'Ander',

# Add categories per AJAX
'ajax-add-category'            => 'Voeg kategorie by',
'ajax-add-category-submit'     => 'Byvoeg',
'ajax-confirm-title'           => 'Bevestig aksie',
'ajax-confirm-prompt'          => 'U kan \'n wysigingsopsomming hier onder verskaf.
Kliek "Stoor" om u wysiging te bêre.',
'ajax-confirm-save'            => 'Stoor',
'ajax-add-category-summary'    => 'Voeg kategorie "$1" by',
'ajax-remove-category-summary' => 'Verwyder kategorie "$1"',
'ajax-confirm-actionsummary'   => 'Aksie om uit te voer:',
'ajax-error-title'             => 'Fout',
'ajax-error-dismiss'           => 'OK',
'ajax-remove-category-error'   => "Ongelukkig was nie moontlik om die kategorie te verwyder nie.
Dit gebeur gewoonlik as die kategorie via 'n sjabloon by die bladsy bygevoeg is.",

);
