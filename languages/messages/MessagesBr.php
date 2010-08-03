<?php
/** Breton (Brezhoneg)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Fohanno
 * @author Fulup
 * @author Malafaya
 * @author Y-M D
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Dibar',
	NS_TALK             => 'Kaozeal',
	NS_USER             => 'Implijer',
	NS_USER_TALK        => 'Kaozeadenn_Implijer',
	NS_PROJECT_TALK     => 'Kaozeadenn_$1',
	NS_FILE             => 'Skeudenn',
	NS_FILE_TALK        => 'Kaozeadenn_Skeudenn',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE         => 'Patrom',
	NS_TEMPLATE_TALK    => 'Kaozeadenn_Patrom',
	NS_HELP             => 'Skoazell',
	NS_HELP_TALK        => 'Kaozeadenn_Skoazell',
	NS_CATEGORY         => 'Rummad',
	NS_CATEGORY_TALK    => 'Kaozeadenn_Rummad',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'AdksaoùDoubl' ),
	'BrokenRedirects'           => array( 'AdkasoùTorr' ),
	'Disambiguations'           => array( 'Disheñvelout' ),
	'CreateAccount'             => array( 'KrouiñKont' ),
	'Preferences'               => array( 'Penndibaboù' ),
	'Watchlist'                 => array( 'Roll evezhiañ' ),
	'Recentchanges'             => array( 'KemmoùDiwezhañ' ),
	'Upload'                    => array( 'Pellgargañ' ),
	'Listfiles'                 => array( 'RollSkeudennoù' ),
	'Newimages'                 => array( 'SkeudennoùNevez' ),
	'Statistics'                => array( 'Stadegoù' ),
	'Newpages'                  => array( 'PajennoùNevez' ),
	'Ancientpages'              => array( 'PajennoùKozh' ),
	'Allpages'                  => array( 'AnHollBajennoù' ),
	'Specialpages'              => array( 'PajennoùDibar' ),
	'Categories'                => array( 'Rummadoù' ),
	'Export'                    => array( 'Ezporzhiañ' ),
	'Version'                   => array( 'Stumm' ),
	'Undelete'                  => array( 'Diziverkañ' ),
	'Import'                    => array( 'Enporzhiañ' ),
	'Mypage'                    => array( 'MaFajenn' ),
	'Mytalk'                    => array( 'MaC\'haozeadennoù' ),
	'Search'                    => array( 'Klask' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ADKAS', '#REDIRECT' ),
	'numberofpages'         => array( '1', 'NIVERABAJENNOU', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NIVERABENNADOU', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NIVERARESTROU', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NIVERAIMPLIJERIEN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'NIVERAIMPLIJERIENOBERIANT', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'NIVERAZEGASEDENNOU', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NIVERALENNADENNOU', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'ANVPAJENN', 'PAGENAME' ),
	'namespace'             => array( '1', 'ESAOUENNANV', 'NAMESPACE' ),
	'fullpagename'          => array( '1', 'ANVPAJENNKLOK', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'ANVISPAJENN', 'SUBPAGENAME' ),
	'img_right'             => array( '1', 'dehou', 'right' ),
	'img_left'              => array( '1', 'kleiz', 'left' ),
	'img_none'              => array( '1', 'netra', 'none' ),
	'img_center'            => array( '1', 'kreizenn', 'center', 'centre' ),
	'img_page'              => array( '1', 'pajenn=$1', 'pajenn $1', 'page=$1', 'page $1' ),
	'img_top'               => array( '1', 'krec\'h', 'top' ),
	'img_middle'            => array( '1', 'kreiz', 'middle' ),
	'img_bottom'            => array( '1', 'traoñ', 'bottom' ),
	'sitename'              => array( '1', 'ANVLEC\'HIENN', 'SITENAME' ),
	'server'                => array( '0', 'SERVIJER', 'SERVER' ),
	'servername'            => array( '0', 'ANVSERVIJER', 'SERVERNAME' ),
	'grammar'               => array( '0', 'YEZHADUR:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'JENER:', 'GENDER:' ),
	'plural'                => array( '0', 'LIESTER:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLKLOK:', 'FULLURL:' ),
	'currentversion'        => array( '1', 'STUMMRED', 'CURRENTVERSION' ),
	'language'              => array( '0', '#YEZH:', '#LANGUAGE:' ),
	'special'               => array( '0', 'dibar', 'special' ),
	'pagesize'              => array( '1', 'MENTPAJENN', 'PAGESIZE' ),
	'url_path'              => array( '0', 'HENT', 'PATH' ),
);

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y "da" H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^((?:c\'h|C\'H|C\'h|c’h|C’H|C’h|[a-zA-ZàâçéèêîôûäëïöüùñÇÉÂÊÎÔÛÄËÏÖÜÀÈÙÑ])+)(.*)$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => 'Liammoù islinennet',
'tog-highlightbroken'         => 'Furmad al liammoù torr <a href="" class="new">evel-mañ</a> (pe : evel-se<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Rannbennadoù marzekaet',
'tog-hideminor'               => "Kuzhat ar c'hemmoù nevez dister",
'tog-hidepatrolled'           => "Kuzhat ar c'hemmoù evezhiet e-touez ar c'hemmoù diwezhañ",
'tog-newpageshidepatrolled'   => 'Kuzhat ar pajennoù evezhiet diouzh roll ar pajennoù nevez',
'tog-extendwatchlist'         => 'Astenn ar roll evezhiañ a-benn diskouez an holl gemmoù ha neket ar re ziwezhañ hepken.',
'tog-usenewrc'                => "Ober gant ar c'hemmoù nevez gwellaet<br /> (rekis eo JavaScript)",
'tog-numberheadings'          => 'Niverenniñ emgefre an titloù',
'tog-showtoolbar'             => 'Diskouez ar varrenn gant ar meuzioù skridaozañ',
'tog-editondblclick'          => 'Daouglikañ evit kemmañ ur bajenn (JavaScript)',
'tog-editsection'             => 'Kemmañ ur rann dre al liammoù [kemmañ]',
'tog-editsectiononrightclick' => 'Kemmañ ur rann dre glikañ a-zehou<br /> war titl ar rann',
'tog-showtoc'                 => 'Diskouez an daolenn<br /> (evit ar pennadoù zo ouzhpenn 3 rann enno)',
'tog-rememberpassword'        => "Derc'hel soñj eus ma ger-tremen war an urzhiataer-mañ (evit $1 devezh{{PLURAL:$1||}} d'ar muiañ)",
'tog-watchcreations'          => 'Evezhiañ ar pajennoù krouet ganin',
'tog-watchdefault'            => 'Evezhiañ ar pennadoù savet pe kemmet ganin',
'tog-watchmoves'              => "Ouzhpennañ da'm roll evezhiañ ar pajennoù adanvet ganin",
'tog-watchdeletion'           => "Ouzhpennañ da'm roll evezhiañ ar pajennoù diverket ganin",
'tog-previewontop'            => 'Rakwelet tres ar bajenn a-us ar prenestr skridaozañ',
'tog-previewonfirst'          => 'Rakwelet tres ar bajenn kerkent hag an aozadenn gentañ',
'tog-nocache'                 => 'Diweredekaat krubuilh ar pajennoù',
'tog-enotifwatchlistpages'    => 'Kas ur postel din pa vez degaset kemmoù war ur bajenn evezhiet ganin',
'tog-enotifusertalkpages'     => 'Kas ur postel din pa vez degaset kemmoù war ma fajenn gaozeal',
'tog-enotifminoredits'        => 'Kas ur postel din, ha pa vije evit kemenn kemmoù dister',
'tog-enotifrevealaddr'        => "Lakaat ma chomlec'h postel war wel er posteloù kemenn-diwall",
'tog-shownumberswatching'     => 'Diskouez an niver a lennerien',
'tog-oldsig'                  => 'Rakwelet ar sinadur zo evit poent :',
'tog-fancysig'                => 'Ober gant ar sinadur evel pa vefe wikitestenn (hep liamm emgefre)',
'tog-externaleditor'          => "Ober gant ur skridaozer diavaez dre ziouer (evit arbennigourien hepken rak ezhomm zo arventenniñ hoc'h urzhiataer evit se)",
'tog-externaldiff'            => "Ober gant ur c'heñverier diavaez dre ziouer (evit arbennigourien hepken rak ezhomm zo arventenniñ hoc'h urzhiataer evit se)",
'tog-showjumplinks'           => 'Gweredekaat al liammoù moned "lammat da"',
'tog-uselivepreview'          => 'Implijout Rakwelet prim (JavaScript) (taol-arnod)',
'tog-forceeditsummary'        => 'Kemenn din pa ne skrivan netra er stern diverrañ',
'tog-watchlisthideown'        => "Kuzhat ma c'hemmoù er rollad evezhiañ",
'tog-watchlisthidebots'       => 'Kuzhat kemmoù ar botoù er rollad evezhiañ',
'tog-watchlisthideminor'      => "Kuzhat ar c'hemmoù dister er rollad evezhiañ",
'tog-watchlisthideliu'        => 'Er roll evezhiañ, kuzhat kemmoù an implijerien kevreet.',
'tog-watchlisthideanons'      => 'Er roll evezhiañ, kuzhat kemmoù an implijerien dianav',
'tog-watchlisthidepatrolled'  => "Kuzhat ar c'hemmoù evezhiet diouzh ar roll evezhiañ",
'tog-nolangconversion'        => 'Diweredekaat amdroadur an adstummoù yezh',
'tog-ccmeonemails'            => 'Kas din un eilskrid eus ar posteloù a gasan da implijerien all',
'tog-diffonly'                => "Arabat diskouez danvez ar pennadoù dindan an diforc'hioù",
'tog-showhiddencats'          => 'Diskouez ar rummadoù kuzhet',
'tog-noconvertlink'           => 'Diweredekaat amdroadur an titloù',
'tog-norollbackdiff'          => 'Na ziskouez an diff goude un distaoladenn',

'underline-always'  => 'Atav',
'underline-never'   => 'Morse',
'underline-default' => 'Diouzh ar merdeer',

# Font style option in Special:Preferences
'editfont-style'     => 'Stil font an takad skridaozañ :',
'editfont-default'   => 'Diouzh ar merdeer',
'editfont-monospace' => 'Font unesaouennet',
'editfont-sansserif' => 'Font hep-serif',
'editfont-serif'     => 'Font serif',

# Dates
'sunday'        => 'Sul',
'monday'        => 'Lun',
'tuesday'       => 'Meurzh',
'wednesday'     => "Merc'her",
'thursday'      => 'Yaou',
'friday'        => 'Gwener',
'saturday'      => 'Sadorn',
'sun'           => 'Sul',
'mon'           => 'Lun',
'tue'           => 'Meu',
'wed'           => 'Mer',
'thu'           => 'Meu',
'fri'           => 'Gwe',
'sat'           => 'Sad',
'january'       => 'Genver',
'february'      => "C'hwevrer",
'march'         => 'Meurzh',
'april'         => 'Ebrel',
'may_long'      => 'Mae',
'june'          => 'Mezheven',
'july'          => 'Gouere',
'august'        => 'Eost',
'september'     => 'Gwengolo',
'october'       => 'Here',
'november'      => 'Du',
'december'      => 'Kerzu',
'january-gen'   => 'Genver',
'february-gen'  => "C'hwevrer",
'march-gen'     => 'Meurzh',
'april-gen'     => 'Ebrel',
'may-gen'       => 'Mae',
'june-gen'      => 'Mezheven',
'july-gen'      => 'Gouere',
'august-gen'    => 'Eost',
'september-gen' => 'Gwengolo',
'october-gen'   => 'Here',
'november-gen'  => 'Du',
'december-gen'  => 'Kerzu',
'jan'           => 'Gen',
'feb'           => "C'hwe",
'mar'           => 'Meu',
'apr'           => 'Ebr',
'may'           => 'Mae',
'jun'           => 'Mez',
'jul'           => 'Gou',
'aug'           => 'Eos',
'sep'           => 'Gwe',
'oct'           => 'Her',
'nov'           => 'Du',
'dec'           => 'Kzu',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Rummad |Rummad }}',
'category_header'                => 'Niver a bennadoù er rummad "$1"',
'subcategories'                  => 'Isrummad',
'category-media-header'          => 'Restroù liesvedia er rummad "$1"',
'category-empty'                 => "''N'eus na pajenn na media ebet er rummad-mañ evit ar mare.''",
'hidden-categories'              => '{{PLURAL:$1|Rummad kuzhet|Rummad kuzhet}}',
'hidden-category-category'       => 'Rummadoù kuzhet',
'category-subcat-count'          => "{{PLURAL:$2|N'eus er rummad-mañ nemet an isrummad da-heul.|{{PLURAL:$1|isrummad|$1 isrummad}} zo d'ar rummad-mañ diwar un hollad a $2.}}",
'category-subcat-count-limited'  => 'Er rummad-mañ e kaver an {{PLURAL:$1|isrummad-se|$1 isrummadoù-se}}.',
'category-article-count'         => "{{PLURAL:$2|N'eus er rummad-mañ nemet ar bajenn da-heul.|Emañ ar {{PLURAL:$1|bajenn da-heul|$1 pajenn da-heul}} er rummad-mañ, diwar un hollad a $2.}}",
'category-article-count-limited' => '{{PLURAL:$1|Emañ ar bajenn|Emañ an $1 pajenn}} da-heul er rummad-mañ.',
'category-file-count'            => "{{PLURAL:$2|N'eus er rummad-mañ nemet ar restr da-heul.|Emañ ar {{PLURAL:$1|restr|$1 restr}} da-heul er rummad-mañ, diwar un hollad a $2.}}",
'category-file-count-limited'    => '{{PLURAL:$1|Emañ ar restr|Emañ an $1 restr}} da-heul er rummad-mañ.',
'listingcontinuesabbrev'         => "(war-lerc'h)",
'index-category'                 => 'Pajennoù menegeret',
'noindex-category'               => "Pajennoù n'int ket menegeret",

'mainpagetext'      => "'''Meziant MediaWiki staliet.'''",
'mainpagedocfooter' => "Sellit ouzh [http://meta.wikimedia.org/wiki/Help:Contents Sturlevr an implijerien] evit gouzout hiroc'h war an doare da implijout ar meziant wiki.

== Kregiñ ganti ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Diwar-benn',
'article'       => 'Pennad',
'newwindow'     => '(digeriñ en ur prenestr nevez)',
'cancel'        => 'Nullañ',
'moredotdotdot' => "Ha muioc'h c'hoazh...",
'mypage'        => 'Ma zammig pajenn',
'mytalk'        => "Ma c'haozeadennoù",
'anontalk'      => "Kaozeal gant ar chomlec'h IP-mañ",
'navigation'    => 'Merdeiñ',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Klask',
'qbbrowse'       => 'Furchal',
'qbedit'         => 'Kemmañ',
'qbpageoptions'  => 'Pajenn an dibaboù',
'qbpageinfo'     => 'Pajenn gelaouiñ',
'qbmyoptions'    => 'Ma dibaboù',
'qbspecialpages' => 'Pajennoù dibar',
'faq'            => 'FAG',
'faqpage'        => 'Project:FAG',

# Vector skin
'vector-action-addsection'       => 'Rannbennad nevez',
'vector-action-delete'           => 'Diverkañ',
'vector-action-move'             => 'Adenvel',
'vector-action-protect'          => 'Gwareziñ',
'vector-action-undelete'         => 'Diziverkañ',
'vector-action-unprotect'        => 'Diwareziñ',
'vector-namespace-category'      => 'Rummad',
'vector-namespace-help'          => 'Skoazell',
'vector-namespace-image'         => 'Restr',
'vector-namespace-main'          => 'Pennad',
'vector-namespace-media'         => 'Pajenn vedia',
'vector-namespace-mediawiki'     => 'Kemennadenn',
'vector-namespace-project'       => 'Pajenn ar raktres',
'vector-namespace-special'       => 'Pajenn dibar',
'vector-namespace-talk'          => 'Kaozeal',
'vector-namespace-template'      => 'Patrom',
'vector-namespace-user'          => 'Pajenn implijer',
'vector-simplesearch-preference' => "Aotren ar c'hinnigoù klask gwellaet (gant Vektor nemetken)",
'vector-view-create'             => 'Krouiñ',
'vector-view-edit'               => 'Kemmañ',
'vector-view-history'            => 'Sellet ouzh an istor',
'vector-view-view'               => 'Lenn',
'vector-view-viewsource'         => 'Sellet ouzh tarzh an destenn',
'actions'                        => 'Oberoù',
'namespaces'                     => 'Esaouennoù anv',
'variants'                       => 'Adstummoù',

'errorpagetitle'    => 'Fazi',
'returnto'          => "Distreiñ d'ar bajenn $1.",
'tagline'           => 'Eus {{SITENAME}}',
'help'              => 'Skoazell',
'search'            => 'Klask',
'searchbutton'      => 'Klask',
'go'                => 'Kas',
'searcharticle'     => 'Mont',
'history'           => 'Istor ar bajenn',
'history_short'     => 'Istor',
'updatedmarker'     => 'kemmet abaoe ma zaol-sell diwezhañ',
'info_short'        => 'Titouroù',
'printableversion'  => 'Stumm da voullañ',
'permalink'         => "Chomlec'h ar stumm-mañ",
'print'             => 'Moullañ',
'edit'              => 'Kemmañ',
'create'            => 'Krouiñ',
'editthispage'      => 'Kemmañ ar bajenn-mañ',
'create-this-page'  => 'Krouiñ ar bajenn-mañ',
'delete'            => 'Diverkañ',
'deletethispage'    => 'Diverkañ ar bajenn-mañ',
'undelete_short'    => "Diziverkañ {{PLURAL:$1|ur c'hemm|$1 kemm}}",
'protect'           => 'Gwareziñ',
'protect_change'    => 'kemmañ',
'protectthispage'   => 'Gwareziñ ar bajenn-mañ',
'unprotect'         => 'Diwareziñ',
'unprotectthispage' => 'Diwareziñ ar bajenn-mañ',
'newpage'           => 'Pajenn nevez',
'talkpage'          => 'Pajenn gaozeal',
'talkpagelinktext'  => 'Kaozeal',
'specialpage'       => 'Pajenn dibar',
'personaltools'     => 'Ostilhoù personel',
'postcomment'       => 'Rann nevez',
'articlepage'       => 'Sellet ouzh ar pennad',
'talk'              => 'Kaozeal',
'views'             => 'Gweladennoù',
'toolbox'           => 'Boest ostilhoù',
'userpage'          => 'Pajenn implijer',
'projectpage'       => 'Pajenn meta',
'imagepage'         => 'Gwelet pajenn ar restr',
'mediawikipage'     => "Sellet ouzh pajenn ar c'hemennadennoù",
'templatepage'      => 'Gwelet patrom ar bajenn',
'viewhelppage'      => 'Gwelet ar bajenn skoazell',
'categorypage'      => 'Gwelet pajenn ar rummadoù',
'viewtalkpage'      => 'Pajenn gaozeal',
'otherlanguages'    => 'Yezhoù all',
'redirectedfrom'    => '(Adkaset eus $1)',
'redirectpagesub'   => 'Pajenn adkas',
'lastmodifiedat'    => "Kemmoù diwezhañ degaset d'ar bajenn-mañ, d'an $1 da $2.",
'viewcount'         => 'Sellet euz eus bet {{PLURAL:$1|$1 wech|$1 (g)wech}} ouzh ar bajenn-mañ.',
'protectedpage'     => 'Pajenn warezet',
'jumpto'            => 'Mont da :',
'jumptonavigation'  => 'merdeiñ',
'jumptosearch'      => 'klask',
'view-pool-error'   => 'Ho tigarez, soulgarget eo ar servijerioù evit poent.
Re a implijerien a glask mont war ar bajenn-mañ war un dro.
Gortozit ur pennadig a-raok klask mont war ar bjann-mañ en-dro.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Diwar-benn {{SITENAME}}',
'aboutpage'            => 'Project:Diwar-benn',
'copyright'            => "Danvez a c'haller implijout dindan $1.",
'copyrightpage'        => '{{ns:project}}:Gwirioù aozer (Copyright)',
'currentevents'        => 'Keleier',
'currentevents-url'    => 'Project:Keleier',
'disclaimers'          => 'Kemennoù',
'disclaimerpage'       => 'Project:Kemenn hollek',
'edithelp'             => 'Skoazell',
'edithelppage'         => 'Help:Penaos degas kemmoù en ur bajenn',
'helppage'             => 'Help:Skoazell',
'mainpage'             => 'Degemer',
'mainpage-description' => 'Degemer',
'policy-url'           => 'Project:Reolennoù',
'portal'               => 'Porched ar gumuniezh',
'portal-url'           => 'Project:Porched ar gumuniezh',
'privacy'              => 'Reolennoù prevezded',
'privacypage'          => 'Project:Reolennoù prevezded',

'badaccess'        => 'Fazi aotre',
'badaccess-group0' => "N'oc'h ket aotreet da seveniñ ar pezh hoc'h eus goulennet.",
'badaccess-groups' => 'Miret eo an ober-mañ evit an implijerien zo {{PLURAL:$2|er strollad|en unan eus ar strolladoù}} : $1.',

'versionrequired'     => 'Rekis eo Stumm $1 MediaWiki',
'versionrequiredtext' => 'Rekis eo stumm $1 MediaWiki evit implijout ar bajenn-mañ. Sellit ouzh [[Special:Version]]',

'ok'                      => 'Mat eo',
'retrievedfrom'           => 'Adtapet diwar « $1 »',
'youhavenewmessages'      => "$1 zo ganeoc'h ($2).",
'newmessageslink'         => 'Kemennoù nevez',
'newmessagesdifflink'     => "Diforc'hioù e-keñver ar stumm kent",
'youhavenewmessagesmulti' => "Kemennoù nevez zo ganeoc'h war $1",
'editsection'             => 'kemmañ',
'editold'                 => 'kemmañ',
'viewsourceold'           => 'gwelet ar vammenn',
'editlink'                => 'kemmañ',
'viewsourcelink'          => 'gwelet an tarzh',
'editsectionhint'         => 'Kemmañ ar rann : $1',
'toc'                     => 'Taolenn',
'showtoc'                 => 'diskouez',
'hidetoc'                 => 'kuzhat',
'thisisdeleted'           => 'Diskouez pe diziverkañ $1 ?',
'viewdeleted'             => 'Gwelet $1?',
'restorelink'             => "{{PLURAL:$1|ur c'hemm diverket|$1 kemm diverket}}",
'feedlinks'               => 'Lanv :',
'feed-invalid'            => 'Seurt lanv direizh.',
'feed-unavailable'        => "N'haller ket implijout al lanvadoù koumanatiñ",
'site-rss-feed'           => 'Lanv RSS evit $1',
'site-atom-feed'          => 'Lanv Atom evit $1',
'page-rss-feed'           => 'Lanv RSS evit "$1"',
'page-atom-feed'          => 'Lanv Atom evit "$1"',
'red-link-title'          => "$1 (n'eus ket eus ar bajenn-mañ)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pennad',
'nstab-user'      => 'Pajenn implijer',
'nstab-media'     => 'Media',
'nstab-special'   => 'Pajenn dibar',
'nstab-project'   => 'Diwar-benn',
'nstab-image'     => 'Skeudenn',
'nstab-mediawiki' => 'Kemennadenn',
'nstab-template'  => 'Patrom',
'nstab-help'      => 'Skoazell',
'nstab-category'  => 'Rummad',

# Main script and global functions
'nosuchaction'      => 'Ober dianav',
'nosuchactiontext'  => "Direizh eo an ober spisaet en URL.
Marteze hoc'h eus graet ur fazi bizskrivañ en URL pe heuliet ul liamm kamm.
Marteze zo un draen er meziant implijet gant {{SITENAME}} ivez.",
'nosuchspecialpage' => "N'eus ket eus ar bajenn dibar-mañ",
'nospecialpagetext' => "<strong>Goulennet hoc'h eus ur bajenn dibar n'eus ket anezhi.</strong>

Ur roll eus ar pajennoù dibar reizh a c'hallit kavour war [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Fazi',
'databaseerror'        => 'Fazi bank roadennoù',
'dberrortext'          => 'C\'hoarvezet ez eus ur fazi ereadur eus ar reked er bank roadennoù, ar pezh a c\'hall talvezout ez eus un draen er meziant.
Setu ar goulenn bet pledet gantañ da ziwezhañ :
<blockquote><tt>$1</tt></blockquote>
adal an arc\'hwel "<tt>$2</tt>".
Adkaset eo bet ar fazi "<tt>$3: $4</tt>" gant ar bank roadennoù.',
'dberrortextcl'        => 'Ur fazi ereadur zo en ur reked savet ouzh ar bank roadennoù.
Setu ar goulenn bet pledet gantañ da ziwezhañ :
"$1"
adal an arc\'hwel "$2"
Adkaset eo bet ar fazi "$3 : $4" gant ar bank roadennoù.',
'laggedslavemode'      => "Diwallit : marteze a-walc'h n'emañ ket ar c'hemmoù diwezhañ war ar bajenn-mañ",
'readonly'             => 'Hizivadurioù stanket war ar bank roadennoù',
'enterlockreason'      => 'Merkit perak eo stanket hag istimit pegeit e chomo evel-henn',
'readonlytext'         => "Evit poent n'haller ket ouzhpennañ pe gemmañ netra er bank roadennoù mui. Un tamm kempenn boutin d'ar bank moarvat. goude-se e vo plaen an traoù en-dro.

Setu displegadenn ar merour bet prennet ar bank gantañ : $1",
'missing-article'      => "N'eo ket bet kavet gant an diaz titouroù testenn ur bajenn en dije dleet kavout hag anvet \"\$1\" \$2.

Peurliesañ e c'hoarvez evit bezañ heuliet liamm dispredet un diforc'h pe an istor war-du ur bajenn bet diverket abaoe.

Mard ned eo ket se eo, hoc'h eus marteze kavet un draen er meziant.
Kasit keloù d'ur [[Special:ListUsers/sysop|merer]], en ur verkañ dezhañ chomlec'h an URL.",
'missingarticle-rev'   => '(adweladenn # : $1)',
'missingarticle-diff'  => '(Dif : $1, $2)',
'readonly_lag'         => "Stanket eo bet ar bank roadennoù ent emgefre p'emañ an eilservijerioù oc'h adpakañ o dale e-keñver ar pennservijer",
'internalerror'        => 'Fazi diabarzh',
'internalerror_info'   => 'Fazi diabarzh : $1',
'fileappenderrorread'  => 'Dibosupl eo lenn "$1" e-pad an ensoc\'hañ.',
'fileappenderror'      => 'Dibosupl ouzhpennañ « $1 » da « $2 ».',
'filecopyerror'        => 'Dibosupl eilañ « $1 » war-du « $2 ».',
'filerenameerror'      => 'Dibosupl da adenvel « $1 » e « $2 ».',
'filedeleteerror'      => 'Dibosupl da ziverkañ « $1 ».',
'directorycreateerror' => 'N\'eus ket bet gallet krouiñ kavlec\'h "$1".',
'filenotfound'         => 'N\'haller ket kavout ar restr "$1".',
'fileexistserror'      => 'Dibosupl skrivañ war ar restr "$1": bez\'ez eus eus ar restr-se dija',
'unexpected'           => 'Talvoudenn dic\'hortoz : "$1"="$2".',
'formerror'            => 'Fazi: Dibosupl eo kinnig ar furmskrid',
'badarticleerror'      => "N'haller ket seveniñ an ober-mañ war ar bajenn-mañ.",
'cannotdelete'         => 'Dibosupl diverkañ ar bajenn pe ar restr "$1".
Marteze e o bet diverket gant unan bennak all dija.',
'badtitle'             => 'Titl fall',
'badtitletext'         => "Faziek pe c'houllo eo titl ar bajenn goulennet; pe neuze eo faziek al liamm etreyezhel",
'perfcached'           => "Krubuilhet eo ar roadennoù da-heul ha marteze n'int ket bet hizivaet.",
'perfcachedts'         => "Krubuilhet eo ar roadennoù-mañ; hizivaet int bet da ziwezhañ d'an $1.",
'querypage-no-updates' => 'Diweredekaet eo an hizivaat evit ar bajenn-mañ. Evit poent ne vo ket nevesaet ar roadennoù amañ.',
'wrong_wfQuery_params' => "Arventennoù faziek war an urzhiad wfQuery()<br />
Arc'hwel : $1<br />
Goulenn : $2",
'viewsource'           => 'Sellet ouzh tarzh an destenn',
'viewsourcefor'        => 'evit $1',
'actionthrottled'      => 'Ober daleet',
'actionthrottledtext'  => "A-benn dizarbenn ar strob, n'haller ket implijout an ober-mañ re alies en ur frapad amzer lakaet, hag aet oc'h dreist ar muzul. Klaskit en-dro a-benn un nebeut munutennoù.",
'protectedpagetext'    => "Prennet eo bet ar bajenn-mañ. N'haller ket degas kemmoù enni.",
'viewsourcetext'       => 'Gallout a rit gwelet hag eilañ danvez ar bajenn-mañ',
'protectedinterface'   => 'Testenn ar bajenn-mañ a dalvez evit etrefas ar meziant. Setu perak eo bet gwarezet ar bajenn.',
'editinginterface'     => "'''Diwallit :''' Emaoc'h oc'h adaozañ ur bajenn a dalvez da sevel skridoù evit etrefas ar meziant. Ar c'hemmoù graet d'ar bajenn-mañ a zegaso kemmoù war etrefas an holl implijerien. Mar fell deoc'h skoazellañ evit treiñ traoù, soñjit kentoc'h implijout [http://translatewiki.net/wiki/Main_Page?setlang=br translatewiki.net], ar raktres evit lec'helaat MediaWiki.",
'sqlhidden'            => '(Reked SQL kuzhet)',
'cascadeprotected'     => 'Gwarezet eo ar bajenn-mañ; n\'haller ket degas kemmoù enni peogwir he c\'haver er {{PLURAL:$1|bajenn|pajennoù}} da-heul zo bet gwarezet en ur zibab an dibarzh "skalierad" :
$2',
'namespaceprotected'   => "N'oc'h ket aotreet da zegas kemmoù e pajennoù an esaouenn anv '''$1'''.",
'customcssjsprotected' => "N'oc'h ket aotreet da zegas kemmoù war ar bajenn-mañ rak kavout a reer enni arventennoù personel un implijer all.",
'ns-specialprotected'  => "N'haller ket kemmañ ar pajennoù en esaouenn anv {{ns:special}}.",
'titleprotected'       => "Gwarezet eo bet an titl-mañ p'eo bet krouet gant [[User:$1|$1]].
Setu amañ perak ''$2''.",

# Virus scanner
'virus-badscanner'     => "Kefluniadur fall : skanner viruzoù dianav : ''$1''",
'virus-scanfailed'     => "Skannadenn c'hwitet (kod $1)",
'virus-unknownscanner' => 'diviruzer dianav :',

# Login and logout pages
'logouttext'                 => "'''Digevreet oc'h bremañ.'''

Gallout a rit kenderc'hel da implijout {{SITENAME}} en un doare dizanv, pe [[Special:UserLogin|kevreañ en-dro]] gant an hevelep anv pe un anv all mar fell deoc'h.
Notit mat e c'hallo pajennoù zo kenderc'hel da vezañ diskwelet evel pa vefec'h kevreet c'hoazh, betek ma vo riñset krubuilh ho merdeer ganeoc'h.",
'welcomecreation'            => '== Degemer mat, $1! ==

Krouet eo bet ho kont implijer.
Na zisoñjit ket resisaat ho [[Special:Preferences|penndibaboù evit {{SITENAME}}]].',
'yourname'                   => "Hoc'h anv implijer",
'yourpassword'               => 'Ho ker-tremen',
'yourpasswordagain'          => 'Skrivit ho ker-tremen en-dro',
'remembermypassword'         => "Derc'hel soñj eus ma ger-tremen war an urzhiataer-mañ (evit $1 devezh{{PLURAL:$1||}} d'ar muiañ)",
'yourdomainname'             => 'Ho tomani',
'externaldberror'            => "Pe ez eus bet ur fazi gwiriekaat diavaez er bank titouroù pe n'oc'h ket aotreet da nevesaat ho kont diavaez.",
'login'                      => 'Kevreañ',
'nav-login-createaccount'    => 'Krouiñ ur gont pe kevreañ',
'loginprompt'                => "Ret eo deoc'h bezañ gweredekaet an toupinoù a-benn gellout kevreañ ouzh {{SITENAME}}.",
'userlogin'                  => 'Kevreañ / krouiñ ur gont',
'userloginnocreate'          => 'Kevreañ',
'logout'                     => 'Digevreañ',
'userlogout'                 => 'Digevreañ',
'notloggedin'                => 'Digevreet',
'nologin'                    => "N'oc'h eus kont ebet ? '''$1'''.",
'nologinlink'                => 'Krouiñ ur gont',
'createaccount'              => 'Krouiñ ur gont nevez',
'gotaccount'                 => "Ur gont zo ganeoc'h dija ? '''$1'''.",
'gotaccountlink'             => 'Kevreañ',
'createaccountmail'          => 'dre bostel',
'createaccountreason'        => 'Abeg :',
'badretype'                  => "N'eo ket peurheñvel an eil ouzh egile an daou c'her-tremen bet lakaet ganeoc'h.",
'userexists'                 => "Implijet eo an anv implijer lakaet ganeoc'h dija.
Dibabit un anv all mar plij.",
'loginerror'                 => 'Kudenn kevreañ',
'createaccounterror'         => 'Dibosupl krouiñ ar gont : $1',
'nocookiesnew'               => "krouet eo bet ar gont implijer met n'hoc'h ket kevreet. {{SITENAME}} a implij toupinoù evit ar c'hrevreañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha da gevreañ en-dro.",
'nocookieslogin'             => "{{SITENAME}} a implij toupinoù evit kevreañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha da gevreañ en-dro.",
'noname'                     => "N'hoc'h eus lakaet anv implijer ebet.",
'loginsuccesstitle'          => "Kevreet oc'h.",
'loginsuccess'               => "'''Kevreet oc'h bremañ ouzh {{SITENAME}} evel \"\$1\".'''",
'nosuchuser'                 => 'N\'eus ket eus an implijer "$1".
Kizidik eo anv an implijer ouzh ar pennlizherennoù
Gwiriit eo bet skrivet mat an anv ganeoc\'h pe [[Special:UserLogin/signup|krouit ur gont nevez]].',
'nosuchusershort'            => "N'eus perzhiad ebet gantañ an anv « <nowiki>$1</nowiki> ». Gwiriit ar reizhskrivadur.",
'nouserspecified'            => "Ret eo deoc'h spisaat un anv implijer.",
'login-userblocked'          => "Stanket eo an implijer-mañ. N'eo ket aotret da gevreañ.",
'wrongpassword'              => 'Ger-tremen kamm. Klaskit en-dro.',
'wrongpasswordempty'         => 'Ger-tremen ebet. Lakait unan mar plij.',
'passwordtooshort'           => '{{PLURAL:$1|1 arouezenn|$1 arouezenn}} hir a rank bezañ ar gerioù-tremen da nebeutañ.',
'password-name-match'        => "Rankout a ra ho ker-tremen bezañ disheñvel diouzh hoc'h anv implijer.",
'mailmypassword'             => 'Kasit din ur ger-tremen nevez',
'passwordremindertitle'      => 'Ho ker-tremen berrbad nevez evit {{SITENAME}}',
'passwordremindertext'       => "Unan bennak (c'hwi moarvat gant ar chomlec'h IP \$1)
en deus goulennet ma vo kaset dezhañ ur ger-tremen nevez evit {{SITENAME}} (\$4).
Savet ez eus bet ur ger-tremen da c'hortoz evit an implijer \"\$2\" hag a zo \"\$3\".
Mard eo se a felle deoc'h ober e vo ret deoc'h kevreañ ha cheñch ho ker-tremen bremañ. Didalvez e vo ho ker ker-tremen da c'hortoz a-benn {{PLURAL:\$5|un devezh|\$5 devezh}}

Mard eo bet graet ar goulenn gant unan bennak all, pe m'hoc'h eus soñj eus ho ker-tremen bremañ ha
ma ne fell ket deoc'h cheñch anezhañ ken, e c'hallit leuskel ar postel-mañ a-gostez ha kenderc'hel d'ober gant ho ker-tremen kozh.",
'noemail'                    => 'N\'eus bet enrollet chomlec\'h postel ebet evit an implijer "$1".',
'noemailcreate'              => "Ret eo deoc'h merkañ ur chomlec'h postel reizh",
'passwordsent'               => 'Kaset ez eus bet ur ger-tremen nevez da chomlec\'h postel an implijer "$1".
Trugarez deoc\'h da gevreañ kerkent ha ma vo bet resevet ganeoc\'h.',
'blocked-mailpassword'       => "N'haller ket degas kemmoù adal ar chomlec'h IP-mañ ken, gant se n'hallit ket implijout an arc'hwel adtapout gerioù-tremen, kuit m'en em ledfe kammvoazioù.",
'eauthentsent'               => "Kaset ez eus bet ur postel kadarnaat war-du ar chomlec'h postel spisaet.
A-raok na vije kaset postel ebet d'ar gont-se e vo ret deoc'h heuliañ ar c'huzulioù merket er postel resevet evit kadarnaat ez eo mat ho kont deoc'h.",
'throttled-mailpassword'     => "Kaset ez eus bet deoc'h ur postel degas soñj e-kerzh an
{{PLURAL:$1|eurvezh|$1 eurvezh}} ziwezhañ. Evit mirout ouzh nep gaou ne gaser nemet ur postel a-seurt-se dre {{PLURAL:$1|eurvezh|$1 eurvezh}}.",
'mailerror'                  => 'Fazi en ur gas ar postel : $1',
'acct_creation_throttle_hit' => "{{PLURAL:$1|1 gont|$1 kont}} zo bet krouet c'hoazh nevez zo dre ho chomlec'h IP gant gweladennerien d'ar wiki-mañ, ar pezh zo an niver brasañ aotreet. Dre se, n'hall ket ket ar weladennerien a implij an IP-mañ krouiñ kontoù mui evit ar mare.",
'emailauthenticated'         => "Gwiriet eo bet ho chomlec'h postel d'an $2 da $3.",
'emailnotauthenticated'      => "N'eo ket bet gwiriekaet ho chomlec'h postel evit c'hoazh. Ne vo ket tu da gas postel ebet deoc'h evit hini ebet eus an dezverkoù dindan.",
'noemailprefs'               => "Merkit ur chomlec'h postel mar fell deoc'h ez afe an arc'hwelioù-mañ en-dro.",
'emailconfirmlink'           => "Kadarnait ho chomlec'h postel",
'invalidemailaddress'        => "N'haller ket degemer ar chomlec'h postel-mañ rak faziek eo e furmad evit doare.
Merkit ur chomlec'h reizh pe goullonderit ar vaezienn-mañ.",
'accountcreated'             => 'Kont krouet',
'accountcreatedtext'         => 'Krouet eo bet kont implijer $1.',
'createaccount-title'        => 'Krouiñ ur gont war {{SITENAME}}',
'createaccount-text'         => 'Unan bennak en deus krouet ur gont gant ho chomlec\'h postel war {{SITENAME}} ($4) zo e anv "$2" hag a ra gant ar ger-tremen "$3".
Mat e vefe deoc\'h kevreañ ha cheñch ho ker-tremen bremañ.

Na daolit ket evezh ouzh ar c\'hemenn-mañ m\'eo bet krouet ar gont dre fazi.',
'usernamehasherror'          => "N'haller ket ober gant an arouezenn # en anvioù an implijerien",
'login-throttled'            => "Betek re oc'h eus klasket kevreañ en aner.
Gortozit a-raok klask en-dro.",
'loginlanguagelabel'         => 'Yezh : $1',
'suspicious-userlogout'      => 'Distaolet eo bet ho koulenn digevreañ rak kaset e oa bet gant ur merdeer direizhet pe krubuilhadenn ur proksi, evit doare.',

# Password reset dialog
'resetpass'                 => 'Cheñch ar ger-tremen',
'resetpass_announce'        => "Enskrivet oc’h bet dre ur ger-tremen da c'hortoz kaset deoc'h dre bostel. A-benn bezañ enrollet da vat e rankit spisaat ur ger-tremen nevez amañ :",
'resetpass_text'            => '<!-- Ouzhpennañ testenn amañ -->',
'resetpass_header'          => 'Cheñch ger-tremen ar gont',
'oldpassword'               => 'Ger-tremen kozh :',
'newpassword'               => 'Ger-tremen nevez :',
'retypenew'                 => 'Adskrivañ ar ger-tremen nevez :',
'resetpass_submit'          => 'Cheñch ar ger-tremen ha kevreañ',
'resetpass_success'         => "Cheñchet eo bet ho ker-tremen ! Emaoc'h o kevreañ...",
'resetpass_forbidden'       => "N'haller ket cheñch ar gerioù-termen",
'resetpass-no-info'         => "Ret eo deoc'h bezañ kevreet a-benn mont d'ar bajenn-se war-eeun.",
'resetpass-submit-loggedin' => 'Cheñch ger-tremen',
'resetpass-submit-cancel'   => 'Nullañ',
'resetpass-wrong-oldpass'   => "Direizh eo ar ger-tremen a-vremañ pe da c'hortoz.",
'resetpass-temp-password'   => "Ger-tremen da c'hortoz :",

# Edit page toolbar
'bold_sample'     => 'Testenn dev',
'bold_tip'        => 'Testenn dev',
'italic_sample'   => 'Testenn italek',
'italic_tip'      => 'Testenn italek',
'link_sample'     => 'Liamm titl',
'link_tip'        => 'Liamm diabarzh',
'extlink_sample'  => 'http://www.example.com liamm titl',
'extlink_tip'     => 'Liamm diavaez (na zisoñjit ket http://)',
'headline_sample' => 'Testenn istitl',
'headline_tip'    => 'Istitl live 2',
'math_sample'     => 'Lakait ho formulenn amañ',
'math_tip'        => 'Formulenn jedoniel (LaTeX)',
'nowiki_sample'   => 'Lakait an destenn anfurmadet amañ',
'nowiki_tip'      => 'Na ober van ouzh ereadur ar wiki',
'image_sample'    => 'Skouer.jpg',
'image_tip'       => 'Skeudenn enframmet',
'media_sample'    => 'Skouer.ogg',
'media_tip'       => 'Liamm restr media',
'sig_tip'         => 'Ho sinadur gant an deiziad',
'hr_tip'          => 'Liamm a-led (arabat implijout re)',

# Edit pages
'summary'                          => 'Diverrañ :',
'subject'                          => 'Danvez/titl:',
'minoredit'                        => 'Kemm dister',
'watchthis'                        => 'Evezhiañ ar pennad-mañ',
'savearticle'                      => 'Enrollañ',
'preview'                          => 'Rakwelet',
'showpreview'                      => 'Rakwelet',
'showlivepreview'                  => 'Rakwelet prim',
'showdiff'                         => "Diskouez ar c'hemmoù",
'anoneditwarning'                  => "'''Diwallit :''' N'oc'h ket kevreet. Ho chomlec'h IP eo a vo enrollet war istor kemmoù ar bajenn-mañ.",
'anonpreviewwarning'               => "''N'oc'h ket kevreet. Enrollañ a lakao war-wel ho chomlec'h IP e istor kemmoù ar bajenn.''",
'missingsummary'                   => "'''Taolit evezh:''' N'hoc'h eus ket lakaet tamm testenn diverrañ ebet evit ho kemmoù. Mar klikit war enrollañ en-dro, e vo enrollet ho testenn evel m'emañ hepmuiken.",
'missingcommenttext'               => "Skrivit hoc'h evezhiadenn a-is.",
'missingcommentheader'             => "'''Taolit evezh :''' N'hoc'h eus lakaet tamm danvez/titl ebet d'hoc'h evezhiadenn.
Mar klikit war \"{{int:savearticle}}\" en-dro, e vo enrollet ho testenn evel m'emañ hepmuiken.",
'summary-preview'                  => 'Rakwelet an diverrañ :',
'subject-preview'                  => 'Rakwelet danvez/titl :',
'blockedtitle'                     => 'Implijer stanket',
'blockedtext'                      => "'''Stanket eo bet ho kont implijer pe ho chomlec'h IP'''

Gant $1 eo bet graet.
Setu an abeg evit se : ''$2''.

* Stanket adalek : $8
* Stanket betek : $6
* Pad ar stankadenn : $7

Gallout a rit mont e darempred gant $1 pe gant unan eus ar [[{{MediaWiki:Grouppage-sysop}}|verourien]] all evit eskemm ganto war se. N'hallit implijout an arc'hwel 'kas ur postel d'an implijer-mañ' nemet ma'z eus bet spisaet ganeoc'h ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù kont]] ha ma n'eo ket bet stanket.
$3 eo ho chomlec'h IP, ha #$5 eo niverenn an identelezh stanket.
Merkit anezho en ho koulennoù bep tro.",
'autoblockedtext'                  => "Stanket eo bet ho chomlec'h IP ent emgefreek rak implijet e veze gant un implijer all bet stanket gant \$1.
Setu aze an abeg :

: ''\$2''

* Deroù ar stankadenn : \$8
* Termen ar stankadenn : \$6
* Kont stanket : \$7

Gallout a rit mont e darempred gant \$1 pe gant unan eus ar
[[{{MediaWiki:Grouppage-sysop}}|verourien]] all ma kavit abeg er stankadenn.

Notennit mat ne c'hallot implijout an dibarzh \"kas ur postel d'an implijer\" nemet ma'z eus bet merket ganeoc'h ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù implijer]] ha ma n'eo ket bet stanket ivez.

\$3 eo ho chomlec'h IP evit poent ha #\$5 ho niverenn stankadenn.
Merkit mat an titouroù-se war kement goulenn savet ganeoc'h.

\$5 eo ho niverenn stankadenn. Merkit mat an niverenn-se pa rit goulennoù.",
'blockednoreason'                  => "n'eus bet roet abeg ebet",
'blockedoriginalsource'            => "Kavout a reot mammenn '''$1''' a-is:",
'blockededitsource'                => "Kavout a reot testenn ho '''kemmoù''' war '''$1''' a-is :",
'whitelistedittitle'               => 'Ret eo bezañ kevreet evit gellout skridaozañ',
'whitelistedittext'                => "Ret eo deoc'h en em $1 evit gallout skridaozañ.",
'confirmedittext'                  => "Rankout a ri bezañ kadarnaet ho chomlec'h postel a-raok gellout degas kemmoù er pajennoù. Skrivit ha kadarnait ho chomlec'h postel en ho [[Special:Preferences|penndibaboù implijer]] mar plij.",
'nosuchsectiontitle'               => 'Diposupl eo kavout ar rann-mañ',
'nosuchsectiontext'                => "Klasket hoc'h eus degas kemmoù en ur rann n'eus ket anezhi.
Moarvat ez eo bet dilerc'hiet pe dilamet abaoe m'ho peus he lennet.",
'loginreqtitle'                    => 'Anv implijer rekis',
'loginreqlink'                     => 'Kevreañ',
'loginreqpagetext'                 => "Ret eo deoc'h $1 evit gwelet pajennoù all.",
'accmailtitle'                     => 'Ger-tremen kaset.',
'accmailtext'                      => "Kaset ez eus bet ur ger-tremen dargouezhek evit [[User talk:$1|$1]] da $2.

Cheñchet e c'hall ar ger-tremen evit ar gont nevez-mañ bezañ war ar bajenn ''[[Special:ChangePassword|cheñch ger-tremen]]'', ur wezh kevreet.",
'newarticle'                       => '(Nevez)',
'newarticletext'                   => "Heuliet hoc'h eus ul liamm a gas d'ur bajenn n'eo ket bet savet evit c'hoazh.
A-benn krouiñ ar bajenn-se, krogit da skrivañ er prenestr skridaozañ dindan (gwelet ar [[{{MediaWiki:Helppage}}|bajenn skoazell]] evit gouzout hiroc'h).
M'emaoc'h en em gavet amañ dre fazi, klikit war bouton '''kent''' ho merdeer evit mont war ho kiz.",
'anontalkpagetext'                 => "---- ''Homañ eo ar bajenn gaozeal evit un implijer(ez) dizanv n'eus ket krouet kont ebet evit c'hoazh pe na implij ket anezhi.
Setu perak e rankomp ober gant ar chomlec'h IP niverel evit anavezout anezhañ/i.
Gallout a ra ur chomlec'h a seurt-se bezañ rannet etre meur a implijer(ez).
Ma'z oc'h un implijer(ez) dizanv ha ma stadit ez eus bet kaset deoc'h kemennadennoù na sellont ket ouzhoc'h, gallout a rit [[Special:UserLogin/signup|krouiñ ur gont]]pe [[Special:UserLogin|kevreañ]] kuit a vagañ muioc'h a gemmesk gant implijerien dizanv all.",
'noarticletext'                    => 'N\'eus tamm skrid ebet war ar bajenn-mañ evit poent.
Gallout a rit [[Special:Search/{{PAGENAME}}|klask an titl anezhi]] e pajennoù all,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} klask en oberiadennoù liammet], pe [{{fullurl:{{FULLPAGENAME}}|action=edit}} krouiñ ar bajenn]</span>.',
'noarticletext-nopermission'       => 'N\'eus, evit ar mare, tamm testenn ebet war ar bajenn-mañ.
Gallout a rit [[Special:Search/{{PAGENAME}}|klask titl ar bajenn-mañ]] war pajennoù all,
pe <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} klask er marilhoù kar]</span>.',
'userpage-userdoesnotexist'        => 'N\'eo ket enrollet ar gont "$1". Merkit ma fell deoc\'h krouiñ/kemmañ ar bajenn-mañ.',
'userpage-userdoesnotexist-view'   => 'N\'eo ket enrollet ar gont implijer "$1".',
'blocked-notice-logextract'        => "Stanket eo an implijer-mañ evit poent.
Dindan emañ merket moned diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'clearyourcache'                   => "'''Notenn :''' Goude bezañ enrollet ho pajenn e rankot freskaat krubuilh ho merdeer a-bennn gwelet ar c'hemmoù : '''Mozilla / Firefox / Safari : ''' dalc'hit ''Pennlizherenn'' en ur glikañ war ''Adkargañ'', pe pouezañ war ''Ctrl-F5'' pe ''Ctrl-R'' (''Command-R'' war ur Macintosh); '''Konqueror: '''klikañ war ''Adkargañ'' pe pouezañ war ''F5;'' '''Opera:''' riñsañ ar grubuilh e ''Ostilhoù → Penndibaboù;'' '''Internet Explorer:''' derc'hel ''Ctrl'' en ur glikañ war ''Freskaat,'' pe pouezañ war ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Tun :''' Grit gant ar bouton \"{{int:showpreview}}\" evit testiñ ho follenn CSS nevez a-raok enrollañ anezhi.",
'userjsyoucanpreview'              => "'''Tun :''' Grit gant ar bouton \"{{int:showpreview}}\" evit testiñ ho follenn JS nevez a-raok enrollañ anezhi.",
'usercsspreview'                   => "'''Dalc'hit soñj n'emaoc'h nemet o rakwelet ho follenn CSS deoc'h.'''
'''N'eo ket bet enrollet evit c'hoazh!'''",
'userjspreview'                    => "'''Dalc'hit soñj emaoc'h o rakwelet pe o testiñ ho kod javascript deoc'h ha n'eo ket bet enrollet c'hoazh!'''",
'userinvalidcssjstitle'            => "'''Diwallit:''' N'eus tamm gwiskadur \"\$1\" ebet. Ho pez soñj e vez implijet lizherennoù bihan goude an anv implijer hag ar veskell / gant ar pajennoù personel dezho un astenn .css ha .js; da skouer eo mat ar follenn stil {{ns:user}}:Foo/monobook.css ha faziek an hini {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Hizivaet)',
'note'                             => "'''Notenn :'''",
'previewnote'                      => "'''Diwallit mat, n'eus ken ur rakweled eus an destenn-mañ.'''
N'eo ket bet enrollet ho kemmoù evit c'hoazh !'''",
'previewconflict'                  => 'Gant ar rakweled e teu testenn ar bajenn war wel evel ma vo pa vo bet enrollet.',
'session_fail_preview'             => "'''Ho tigarez! N'eus ket bet tu da enrollañ ho kemmoù rak kollet eo bet roadennoù an dalc'h.'''
Klaskit en-dro mar plij.
Ma ne'z a ket en-dro c'hoazh, klaskit [[Special:UserLogout|digevreañ]] hag adkevreañ war-lerc'h.",
'session_fail_preview_html'        => "'''Ho tigarez! N'omp ket bet gouest da enrollañ ho kemmoù rak kollet ez eus bet roadennoù e-kerzh an dalc'h.'''

''Gweredekaet eo al linennoù HTML e {{SITENAME}}. Rak-se eo kuzh ar rakweledoù a-benn en em zifenn diouzh an tagadennoù JavaScript.''

'''Mard e oa onest ar c'hemmoù oc'h eus klasket degas, klaskit en-dro. '''
Mar ned a ket en-dro, klaskit [[Special:UserLogout|digevreañ]] ha kevreañ en-dro.",
'token_suffix_mismatch'            => "'''Distaolet eo bet ar c'hemmoù degaset ganeoc'h abalamour ma oa bet kemmesket an arouezennoù poentadur gant ho merdeer en daveer kemmañ. Distaolet eo bet ar c'hemmoù kuit na vije breinet ar bajennad skrid.
C'hoarvezout a ra a-wechoù pa implijit ur servijer proksi dreinek dizanav.'''",
'editing'                          => "Oc'h aozañ $1",
'editingsection'                   => "Oc'h aozañ $1 (rann)",
'editingcomment'                   => "Oc'h aozañ $1 (rann nevez)",
'editconflict'                     => 'tabut kemmañ : $1',
'explainconflict'                  => "<b>Enrollet eo bet ar bajenn-mañ war-lerc'h m'ho pefe kroget d'he c'hemmañ.
E-krec'h an takad aozañ emañ an destenn evel m'emañ enrollet bremañ er bank roadennoù. Ho kemmoù deoc'h a zeu war wel en takad aozañ traoñ. Ret e vo deoc'h degas ho kemmoù d'an destenn zo evit poent. N'eus nemet an destenn zo en takad krec'h a vo saveteet.</b><br />",
'yourtext'                         => 'Ho testenn',
'storedversion'                    => 'Stumm enrollet',
'nonunicodebrowser'                => "'''DIWALLIT: N'eo ket skoret an Unicode gant ho merdeer. Un diskoulm da c'hortoz zo bet kavet evit ma c'hallfec'h degas kemmoù er pennadoù : dont a raio war wel an arouezennoù an-ASCII er prenestr skridaozañ evel kodoù eizhdekvedennel.'''",
'editingold'                       => "'''Diwallit : o kemm ur stumm kozh eus ar bajenn-mañ emaoc'h. Mard enrollit bremañ e vo kollet an holl gemmoù bet graet abaoe ar stumm-se.'''",
'yourdiff'                         => "Diforc'hioù",
'copyrightwarning'                 => "Sellet e vez ouzh an holl degasadennoù graet war {{SITENAME}} evel ouzh degasadennoù a zouj da dermenoù ar $2 (Sellet ouzh $1 evit gouzout hiroc'h). Mar ne fell ket deoc'h e vefe embannet ha skignet ho skridoù, arabat kas anezho.<br />
Heñveldra, prometiñ a rit kemer perzh dre zegas skridoù savet ganeoc'h hepken pe tennet eus ur vammenn frank a wirioù.
'''NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER (COPYRIGHT) HEP AOTRE D'OBER KEMENT-SE!'''",
'copyrightwarning2'                => "Notit mat e c'hall kement degasadenn graet ganeoc'h war {{SITENAME}} bezañ kemmet, adaozet pe lamet kuit gant an implijerien all. Mar ne fell ket deoc'h e vije kemmet-digemmet ar pezh hoc'h eus skrivet na gemerit ket perzh er raktres-mañ.<br /> Gouestlañ a rit ivez eo bet savet ar boued spered ganeoc'h pe eilet diwar ur vammenn frank a wirioù pe en domani foran (gwelet $1 evit gouzout hiroc'h). '''NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER HEP AOTRE D'OBER KEMENT-SE!'''",
'longpagewarning'                  => "'''KEMENN DIWALL: $1 ko eo hed ar bajenn-mañ;
merdeerioù zo o deus poan da verañ ar pajennoù tro-dro pe en tu all da 32 ko pa vezont savet.
Marteze e c'hallfec'h rannañ ar bajenn e rannoù bihanoc'h.'''",
'longpageerror'                    => "'''FAZI: $1 kilobit hir eo an destenn lakaet ganeoc'h, ar pezh zo hiroc'h eget $2 kilobit, ar vent vrasañ aotreet. N'haller ket enrollañ.'''",
'readonlywarning'                  => "'''KEMENN DIWALL : stanket eo an diaz titouroù a-benn bezañ trezalc'het; setu ne viot ket evit enrollañ ho kemmoù diouzhtu-diouzhtu eta.
Gallout a rit eilañ-pegañ an destenn en ur restr skrid all hag enrollañ anezhi a-benn diwezhatoc'hik.'''

Setu an displegadenn lakaet gant ar merour en deus stanket an traoù : $1",
'protectedpagewarning'             => "'''KEMENN DIWALL: Gwarezet eo bet ar bajenn-mañ. N'eus nemet an implijerien ganto ar statud merour a c'hall degas kemmoù enni.'''
Moned ziwezhañ ar marilh a vez diskouezet amañ a-is evel dave :",
'semiprotectedpagewarning'         => "''Notenn :''' Gwarezet eo ar bajenn-mañ; n'eus nemet an implijerien bet krouet ur gont ganto a c'hall degas kemmoù enni. Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'cascadeprotectedwarning'          => "'''Diwallit :''' Prennet eo ar bajenn-mañ. N'eus nemet ar verourien a c'hall degas kemmoù enni peogwir he c'haver e-touez ar {{PLURAL:\$1|bajenn|pajennoù}} da-heul zo bet gwarezet en ur zibab an dibarzh \"skalierad\" :",
'titleprotectedwarning'            => "'''DIWALLIT :  Gwarezet eo bet ar bajenn-mañ e doare ma ranker kaout [[Special:ListGroupRights|gwirioù dibar]] a-benn krouiñ anezhi.''' Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'templatesused'                    => '{{PLURAL:$1|Patrom|Patromoù}} implijet war ar bajenn-mañ :',
'templatesusedpreview'             => '{{PLURAL:$1|Patrom|Patromoù}} implijet er rakweladenn-mañ :',
'templatesusedsection'             => '{{PLURAL:$1|Patrom|Patromoù}} implijet er rann-mañ :',
'template-protected'               => '(gwarezet)',
'template-semiprotected'           => '(damwarezet)',
'hiddencategories'                 => "{{PLURAL:$1|1 rummad kuzhet|$1 rummad kuzhet}} m'emañ rollet ar bajenn-mañ :",
'edittools'                        => '<!-- Diskouezet e vo an destenn kinniget amañ dindan ar sternioù kemmañ ha kargañ. -->',
'nocreatetitle'                    => "Strishaet eo bet ar c'hrouiñ pajennoù",
'nocreatetext'                     => 'Strishaet eo bet an tu da grouiñ pajennoù nevez war {{SITENAME}}.
Gallout a rit mont war-gil ha degas kemmoù en ur bajenn zo anezhi dija, pe [[Special:UserLogin|en em enrollañ ha krouiñ ur gont]].',
'nocreate-loggedin'                => "N'oc'h ket aotreet da grouiñ pajennoù nevez.",
'sectioneditnotsupported-title'    => "N'eo ket skoret ar c'hemmañ rannoù",
'sectioneditnotsupported-text'     => "N'eo ket skoret ar c'hemmañ rannoù evit ar bajenn-mañ",
'permissionserrors'                => 'Fazioù Aotre',
'permissionserrorstext'            => "N'oc'h ket aotreet d'ober kement-mañ evit {{PLURAL:$1|an abeg-mañ|an abegoù-mañ}} :",
'permissionserrorstext-withaction' => "N'oc'h ket aotreet da $2, evit an {{PLURAL:$1|abeg-mañ|abeg-mañ}} :",
'recreate-moveddeleted-warn'       => "'''Diwallit : Emaoc'h o krouiñ ur bajenn zo bet diverket c'hoazh.'''

En em soñjit ervat ha talvoudus eo kenderc'hel krouiñ ar bajenn.
Deoc'h da c'houzout, aze emañ ar marilhoù diverkañ hag adenvel :",
'moveddeleted-notice'              => 'Diverket eo bet ar bajenn-mañ.
Dindan emañ ar marilh diverkañ hag adenvel.',
'log-fulllog'                      => 'Gwelet ar marilh klok',
'edit-hook-aborted'                => "C'hwitet ar c'hemmañ gant un astenn.
Abeg dianav.",
'edit-gone-missing'                => 'Dibosupl hizivaat ar bajenn.
Diverket eo bet evit doare.',
'edit-conflict'                    => 'Tabut kemmañ.',
'edit-no-change'                   => "N'eo ket bet kemeret ho tegasadenn e kont rak ne oa ket bet kemmet netra en destenn.",
'edit-already-exists'              => "N'eus ket bet gallet krouiñ ur bajenn nevez.
Krouet e oa bet c'hoazh.",

# Parser/template warnings
'expensive-parserfunction-warning'        => "Diwallit : Re a c'halvoù koustus e-keñver an arc'hwelioù parser zo gant ar bajenn-mañ.

Dleout a rafe bezañ nebeutoc'h eget $2 {{PLURAL:$2|galv|galv}}, ha {{PLURAL:$1|$1 galv|$1 galv}} zo.",
'expensive-parserfunction-category'       => "Pagjennoù enno re a c'halvoù koustus e-keñver an arc'hwelioù parser.",
'post-expand-template-inclusion-warning'  => 'Diwallit : re a batromoù zo war ar bajenn-mañ.
Lod anezho a vo lakaet a-gostez.',
'post-expand-template-inclusion-category' => 'Pajennoù enno re a batromoù',
'post-expand-template-argument-warning'   => 'Diwallit : war ar bajenn-mañ ez eus eus da nebeutañ un arventenn eus ur patrom zo re vras.
A-gostez eo bet lezet an arventenn-se.',
'post-expand-template-argument-category'  => 'Pajennoù enno arventennoù patrom bet lezet a-gostez',
'parser-template-loop-warning'            => "Patrom e kelc'h detektet : [[$1]]",
'parser-template-recursion-depth-warning' => 'Tizhet bevenn donder galvoù ar patromoù ($1)',
'language-converter-depth-warning'        => "Aet eur en tu all d'ar vevenn amdreiñ yezhoù ($1)",

# "Undo" feature
'undo-success' => "Gallout a reer disteurel ar c'hemmoù-mañ. Gwiriit, mar plij, gant ar geñveriadenn a-is evit bezañ sur eo an dra-se a fell deoc'h ober; goude-se enrollit ar c'hemmoù a-is a-benn echuiñ disteurel ar c'hemmoù.",
'undo-failure' => "N'eus ket bet tu da zisteuler ar c'hemm-mañ abalamour d'un tabut gant kemmoù degaset e-keit-se.",
'undo-norev'   => "N'eus ket bet gallet degas ar c'hemmoù-mañ rak pe n'eus ket anezho pe int bet diverket.",
'undo-summary' => 'Disteurel kemmoù $1 a-berzh [[Special:Contributions/$2|$2]] ([[User talk:$2|kaozeal]])',

# Account creation failure
'cantcreateaccounttitle' => 'Dibosupl krouiñ ar gont',
'cantcreateaccount-text' => "Stanket eo bet ar c'hrouiñ kontoù adal ar chomlec'h IP ('''$1''') gant [[User:$3|$3]].

An abeg roet gant $3 zo ''$2''",

# History pages
'viewpagelogs'           => 'Gwelet ar marilhoù evit ar bajenn-mañ',
'nohistory'              => "Ar bajenn-mañ n'he deus tamm istor ebet.",
'currentrev'             => 'Stumm a-vremañ pe stumm red',
'currentrev-asof'        => 'Stumm red eus an $1',
'revisionasof'           => 'Stumm eus an $1',
'revision-info'          => 'Stumm eus an $1 gant $2',
'previousrevision'       => '← Stumm kent',
'nextrevision'           => "Stumm war-lerc'h →",
'currentrevisionlink'    => 'Gwelet ar stumm red',
'cur'                    => 'red',
'next'                   => 'goude',
'last'                   => 'diwez',
'page_first'             => 'kentañ',
'page_last'              => 'diwezhañ',
'histlegend'             => "Sellet ouzh an diforc'hioù : lakait un ask adal d'ar stummoù a fell deoc'h keñveriañ ha pouezit war kadarnaat pe war ar bouton en traoñ.<br />
Alc'hwez : (red) = diforc'hioù gant ar stumm a-vremañ,
(diwez) = diforc'hioù gant ar stumm kent, D = kemm dister",
'history-fieldset-title' => 'Furchal en istor',
'history-show-deleted'   => 'Diverket hepken',
'histfirst'              => 'Kentañ',
'histlast'               => 'Diwezhañ',
'historysize'            => '({{PLURAL:$1|$1 okted|$1 okted}})',
'historyempty'           => '(goullo)',

# Revision feed
'history-feed-title'          => "Istor ar c'hemmoù",
'history-feed-description'    => "Istor ar c'hemmoù degaset war ar bajenn-mañ eus ar wiki",
'history-feed-item-nocomment' => "$1 d'an $2",
'history-feed-empty'          => "Ar bajenn goulennet n'eus ket anezhi.
Marteze eo bet diverket eus ar wiki, pe adanvet.
Implijit [[Special:Search|klaskit er wiki]] evit kavout pajennoù all a c'hallfe klotañ.",

# Revision deletion
'rev-deleted-comment'         => '(evezhiadenn diverket)',
'rev-deleted-user'            => '(anv implijer diverket)',
'rev-deleted-event'           => '(elfenn dilamet)',
'rev-deleted-user-contribs'   => "[anv implijer pe chomlec'h IP diverket - kemm kuzhet diouzh an degasadennoù]",
'rev-deleted-text-permission' => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Marteze e kavot munudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} roll ar pajennoù diverket].",
'rev-deleted-text-unhide'     => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Marteze e kavot munudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].
Evel merour e c'hallit [$1 gwelet ar stumm-se] c'hoazh mar fell deoc'h kenderc'hel.",
'rev-suppressed-text-unhide'  => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Marteze e kavor muioc'h a vunudoù war [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].
Pa'z oc'h merour e c'hallit [$1 gwelet ar stummm-se] mar fell deoc'h kenderc'hel ganti.",
'rev-deleted-text-view'       => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Pa'z oc'h merour e c'hallit sellet outañ;
Marteze e kavot munudoù all war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].",
'rev-suppressed-text-view'    => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn-mañ.
Pa'z oc'h merour e c'hallit sellet outañ; marteze e kavot munudoù war [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].",
'rev-deleted-no-diff'         => "N'hallit ket gwelet an diforc'h-mañ rak '''diverket''' eo bet unan eus ar stummoù.
Marteze ez eus muioc'h a vunudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].",
'rev-suppressed-no-diff'      => "N'hallit ket gwelet an diforc'h-se rak '''diverket''' ez eus bet unan eus an adweladennoù.",
'rev-deleted-unhide-diff'     => "'''Diverket''' eo bet unan eus kemmoù an diforc'h-mañ.
Marteze e kavot muoic'h a ditouroù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].
Evel merour ez oc'h aotreet da [$1 sellet ouzh an diforc'h-mañ] ma karit.",
'rev-suppressed-unhide-diff'  => "'''Diverket''' ez eus bet unan eus adweladennoù an diff-mañ.
Titouroù ouzhpenn a c'hall bezañ war [{{lurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].
Evel merour e c'hallit atav [$1 sellet ouzh an diff-se] mar fell deoc'h kenderc'hel.",
'rev-deleted-diff-view'       => "'''Dilamet''' ez eus bet unan eus stummoù an dif.-mañ.
Evel merour e c'hallit gwelet an dif.-mañ; muioc'h a ditouroù a c'hall bezañ e [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù]",
'rev-suppressed-diff-view'    => "'''Diverket''' ez eus bet unan eus stummoù an dif.-mañ.
Evel merour e c'hallit gwelet an dif.-mañ; muioc'h a ditouroù a c'hall bezañ e [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù]",
'rev-delundel'                => 'diskouez/kuzhat',
'rev-showdeleted'             => 'diskouez',
'revisiondelete'              => 'Diverkañ/diziverkañ stummoù',
'revdelete-nooldid-title'     => "N'eus stumm pal ebet evit an degasadennoù",
'revdelete-nooldid-text'      => "Pe n'eo ket bet spisaet ganeoc'h ar stumm(où) pal da implijout an arc'hwel-mañ evito. pe n'eus ket eus ar stummoù spisaet, pe emaoc'h o klask kuzhat ar stumm red.",
'revdelete-nologtype-title'   => "N'eus bet resisaet seurt marilh ebet",
'revdelete-nologtype-text'    => "N'eus ket bet spisaet ganeoc'h seurt marilh ebet da lakaat an ober-mañ da c'hoarvezout warnañ.",
'revdelete-nologid-title'     => 'Enmont ar marilh direizh',
'revdelete-nologid-text'      => "Pe n'hoc'h eus ket spisaet enmont ebet er marilh da vezañ sevenet an ober-mañ warnañ, pe n'eus ket eus an darvoud merket.",
'revdelete-no-file'           => "N'eus ket eus ar restr spisaet ganeoc'h.",
'revdelete-show-file-confirm' => 'Ha sur oc\'h e fell deoc\'h gwelet stumm diverket ar restr "<nowiki>$1</nowiki>" deiziataet eus an $2 da $3?',
'revdelete-show-file-submit'  => 'Ya',
'revdelete-selected'          => "'''{{PLURAL:$2|Stumm dibabet|Stummoù dibabet}} eus [[:$1]] :'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Darvoud eus ar marilh diuzet|Darvoud eus ar marilh diuzet}} :'''",
'revdelete-text'              => "'''Derc'hel a raio ar stummoù ha darvoudoù diverket da zont war wel war istor ar bajenn hag er marilhoù, met dazrn eus ar boued n'hallo ket bezañ gwelet gant an dud.'''
Gouest e vo merourien all {{SITENAME}} da dapout krog en testennoù kuzhet ha da ziziverkañ anezho en-dro dre an hevelep etrefas, nemet e vije bet lakaet e plas strishadurioù ouzhpenn.",
'revdelete-confirm'           => "Kadarnait eo mat an dra-se a fell deoc'h ober, e komprenit mat ar pezh a empleg, hag en grit en ur zoujañ d'ar [[{{MediaWiki:Policy-url}}|reolennoù]].",
'revdelete-suppress-text'     => "Ne zlefe an dilemel bezañ implijet '''nemet''' abalamour d'an abegoù da-heul :
* Titouroù personel dizere
*: ''chomlec'hioù, niverennoù pellgomz pe surentez sokial personel, hag all''",
'revdelete-legend'            => 'Lakaat strishadurioù gwelet',
'revdelete-hide-text'         => 'Kuzhat testenn ar stumm',
'revdelete-hide-image'        => 'Kuzhat danvez ar restr',
'revdelete-hide-name'         => 'Kuzhat an ober hag ar vukadenn',
'revdelete-hide-comment'      => "Kuzhat notenn ar c'hemm",
'revdelete-hide-user'         => "Kuzhat anv implijer pe chomlec'h IP an aozer",
'revdelete-hide-restricted'   => "Diverkañ ar roadennoù kement d'ar verourien ha d'ar re all",
'revdelete-radio-same'        => '(arabat cheñch)',
'revdelete-radio-set'         => 'Ya',
'revdelete-radio-unset'       => 'Ket',
'revdelete-suppress'          => 'Diverkañ roadennoù ar verourien hag ar re all',
'revdelete-unsuppress'        => 'Lemel ar strishadurioù war ar stummoù assavet',
'revdelete-log'               => 'Abeg :',
'revdelete-submit'            => 'Lakaat da dalvezout evit an {{PLURAL:$1|adweladenn|adweladennoù}} diuzet',
'revdelete-logentry'          => 'Cheñchet eo bet gweluster ar stumm evit [[$1]]',
'logdelete-logentry'          => 'kemmet eo bet gweluster an darvoud evit [[$1]]',
'revdelete-success'           => "''Gweluster ar stummoù hizivaet mat.'''",
'revdelete-failure'           => "''Dibosupl hizivaat gweluster ar stumm :'''
$1",
'logdelete-success'           => "'''Gweluster ar marilh arventennet evel m'eo dleet.'''",
'logdelete-failure'           => "'''N'eus ket bet gallet termeniñ gweluster ar marilh :'''
$1",
'revdel-restore'              => 'Cheñch ar gweluster',
'revdel-restore-deleted'      => 'adweladennoù bet diverket',
'revdel-restore-visible'      => "adweladennoù a c'heller gwelet",
'pagehist'                    => 'Istor ar bajenn',
'deletedhist'                 => 'Diverkañ an istor',
'revdelete-content'           => 'danvez',
'revdelete-summary'           => 'kemmañ an diverrañ',
'revdelete-uname'             => 'anv implijer',
'revdelete-restricted'        => 'Lakaat ar strishadurioù da dalvezout evit ar verourien',
'revdelete-unrestricted'      => 'dilemel ar strishadurioù evit ar verourien',
'revdelete-hid'               => 'kuzhat $1',
'revdelete-unhid'             => 'diguzhat $1',
'revdelete-log-message'       => '$1 evit $2 {{PLURAL:$2|adweladenn|adweladenn}}',
'logdelete-log-message'       => '$1 evit $2 {{PLURAL:$2|darvoud|darvoud}}',
'revdelete-hide-current'      => "Fazi en ur ziverkañ an elfenn deiziataet eus an $1 da $2 : ar stumm red eo.
N'hall ket bezañ diverket.",
'revdelete-show-no-access'    => 'Fazi en ur ziskwel an elfenn deiziataet eus an $1 da $2 : merket eo evel "miret".
N\'oc\'h ket aotreet da vont outi.',
'revdelete-modify-no-access'  => 'Fazi en ur zegas kemmoù en elfenn deiziataet eus an $1 da $2 : merket eo an elfenn evel "miret".
N\'oc\'h ket aotreet da vont outi.',
'revdelete-modify-missing'    => "Fazi ;en ur zegas kemmoù en elfenn gant ID $1: n'emañ ket er bank roadennoù !",
'revdelete-no-change'         => "'''Evezh :''' emañ an arventennoù gweluster goulennet gant an elfenn deiziataet eus an $1 da $2 dija",
'revdelete-concurrent-change' => "Fazi pa'z eus bet bet degaset kemmoù en elfenn deiziataet eus an $1 da $2 : cheñchet eo bet e statud gant unan bennak all dres pa oac'h-chwi o kemmañ anezhi. Gwiriit ar marilhoù.",
'revdelete-only-restricted'   => "Ur fazi zo bet en ur guzhat an elfenn deiziadet eus an $1 da $2 : n'hallit ket kuzhat an elfennoù-mañ ouzh ar verourien hep dibab ivez unan eus an dibarzhioù gweluster all.",
'revdelete-reason-dropdown'   => '*Abegoù diverkañ boutin
**Gaou ouzh ar gwirioù implijout
** Titouroù personel dizereat',
'revdelete-otherreason'       => 'Abeg all/ouzhpenn :',
'revdelete-reasonotherlist'   => 'Abeg all',
'revdelete-edit-reasonlist'   => 'Kemmañ abegoù an diverkañ',
'revdelete-offender'          => 'Aozer an adlenn :',

# Suppression log
'suppressionlog'     => 'Marilh diverkañ',
'suppressionlogtext' => 'A-is emañ roll an diverkadennoù hag ar stankadennoù diwezhañ enno an adweladennoù kuzhet ouzh ar verourien. Gwelet [[Special:IPBlockList|roll an IPoù stanket]] evit kaout roll ar stankadennoù ha forbannadennoù e talvoud evit poent.',

# Revision move
'moverevlogentry'              => "en deus dilec'hiet {{PLURAL:$3|un adweladenn|$3 adweladenn}} eus $1 da $2",
'revisionmove'                 => 'Dilec\'hiañ adweladennoù eus "$1"',
'revmove-explain'              => "Dielc'hiet e vo an adweladennoù da-heul eus $1 d'ar bajenn dal resisaet. Ma n'eus ket eus ar bajenn dal e vo krouet. A-hend-all e vo kendeuzet ar c'hemmoù-mañ gant istor ar bajenn.",
'revmove-legend'               => 'Lakait ar pajenn voned hag an abeg',
'revmove-submit'               => "Dilec'hiañ an adweladennoù davet ar bajenn diuzet",
'revisionmoveselectedversions' => "Dilec'hiañ an adweladennoù diuzet",
'revmove-reasonfield'          => 'Abeg :',
'revmove-titlefield'           => 'Pajenn voned :',
'revmove-badparam-title'       => 'Arventennoù fall',
'revmove-badparam'             => "Direizh pe re zister eo arventennoù ho reked.
Distroit d'ar bajenn a-raok ha klaskit en-dro.",
'revmove-norevisions-title'    => "N'eus stumm pal ebet evit an degasadennoù",
'revmove-norevisions'          => "N'hoc'h eus ket resisaet adweladenn dal pe adweladennoù tal ebet evit seveniñ an arc'hwel-mañ pe neuze n'eus ket eus an adweladenn merket",
'revmove-nullmove-title'       => 'Titl fall',
'revmove-nullmove'             => 'N\'hall ket ar bajenn dal bezañ heñvel ouzh ar bajenn gein.
Distroit d\'ar bajenn a-raok ha dibabit un anv disheñvel diouzh "$1".',
'revmove-success-existing'     => "{{PLURAL:$1|Un|$1}} adweladenn eus [[$2]] a zo bet dilec'hiet davet ar bajenn ez eus outi dija [[$3]].",
'revmove-success-created'      => "{{PLURAL:$1|Un|$1}} adweladenn eus [[$2]] a zo bet dilec'hiet davet ar bajenn [[$3]] bet krouet nevez zo.",

# History merging
'mergehistory'                     => 'Kendeuziñ istor ur bajenn',
'mergehistory-header'              => "Gant ar bajenn-mañ e c'hallit kendeuziñ an adweladennoù c'hoarvezet da istor ur bajenn war-du unan nevez.
Gwiriit ne vo ket torret red istor ar bajenn gant ar c'hemm-mañ.",
'mergehistory-box'                 => 'Kendeuziñ istor div bajenn :',
'mergehistory-from'                => 'Pajenn orin :',
'mergehistory-into'                => 'Pajenn dal :',
'mergehistory-list'                => 'Aozañ an istorioù da gendeuziñ',
'mergehistory-merge'               => 'Gallout a reer kendeuziñ ar stummoù da-heul eus [[:$1]] e [[:$2]]. Na implijit bouton radio ar bann nemet evit kendeuziñ ar stummoù bet krouet en deroù betek an deiziad merket. Notit mat e vo nevesaet ar bann mard implijit al liammoù merdeiñ.',
'mergehistory-go'                  => "Diskouez ar stummoù a c'haller kendeuziñ",
'mergehistory-submit'              => 'Kendeuziñ ar stummoù',
'mergehistory-empty'               => "N'haller ket kendeuziñ stumm ebet.",
'mergehistory-success'             => 'Kendeuzet ez eus bet $3 {{PLURAL:$3|stumm|stumm}} eus [[:$1]] e [[:$2]].',
'mergehistory-fail'                => 'Dibosupl kendeuziñ an istorioù. Gwiriit ar bajenn hag arventennoù an deiziadoù.',
'mergehistory-no-source'           => "N'eus ket eus ar bajenn orin $1.",
'mergehistory-no-destination'      => "N'eus ket eus ar bajenn dal $1.",
'mergehistory-invalid-source'      => 'Ret eo da anv ar bajenn orin bezañ reizh.',
'mergehistory-invalid-destination' => 'Ret eo da anv ar bajenn dal bezañ reizh.',
'mergehistory-autocomment'         => 'Kendeuzet [[:$1]] gant [[:$2]]',
'mergehistory-comment'             => 'Kendeuzet [[:$1]] gant [[:$2]] : $3',
'mergehistory-same-destination'    => "N'hall ket ar pajennoù kein hag ar pajennoù tal bezañ an hevelep re",
'mergehistory-reason'              => 'Abeg :',

# Merge log
'mergelog'           => "Marilh ar c'hendeuzadennoù.",
'pagemerge-logentry' => 'kendeuzet [[$1]] gant [[$2]] (stummoù betek an $3)',
'revertmerge'        => "Nullañ ar c'hendeuziñ",
'mergelogpagetext'   => 'Setu aze roll kendeuzadennoù diwezhañ un eil pajenn istor gant eben.',

# Diffs
'history-title'            => 'Istor stummoù disheñvel "$1"',
'difference'               => "(Diforc'hioù etre ar stummoù)",
'lineno'                   => 'Linenn $1:',
'compareselectedversions'  => 'Keñveriañ ar stummoù diuzet',
'showhideselectedversions' => 'Diskouez/Kuzhat ar stummoù diuzet',
'editundo'                 => 'disteuler',
'diff-multi'               => "({{PLURAL:$1|Ur reizhadenn da c'hortoz|$1 reizhadenn da c'hortoz}} kuzhet.)",

# Search results
'searchresults'                    => "Disoc'h ar c'hlask",
'searchresults-title'              => 'Disoc\'hoù klask evit "$1"',
'searchresulttext'                 => "Evit gouzout hiroc'h diwar-benn ar c'hlask e {{SITENAME}}, sellet ouzh [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Klasket hoc\'h eus \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|an holl bajennoù a grog gant "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|an holl bajennoù enno ul liamm war-du "$1"]])',
'searchsubtitleinvalid'            => "Klasket hoc'h eus '''$1'''",
'toomanymatches'                   => 'Re a respontoù a glot gant ar goulenn, klaskit gant ur goulenn all',
'titlematches'                     => 'Klotadurioù gant an titloù',
'notitlematches'                   => "N'emañ ar ger(ioù) goulennet e titl pennad ebet",
'textmatches'                      => 'Klotadurioù en testennoù',
'notextmatches'                    => "N'emañ ar ger(ioù) goulennet e testenn pennad ebet",
'prevn'                            => '{{PLURAL:$1|$1}} kent',
'nextn'                            => "{{PLURAL:$1|$1}} war-lerc'h",
'prevn-title'                      => "$1 {{PLURAL:$1|disoc'h|disoc'h}} kent",
'nextn-title'                      => "$1 {{PLURAL:$1|disoc'h|disoc'h}} war-lerc'h",
'shown-title'                      => "Diskouez $1 {{PLURAL:$1|disoc'h|disoc'h}} dre bajenn",
'viewprevnext'                     => 'Gwelet ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Dibarzhioù klask',
'searchmenu-exists'                => "'''Ur bajenn anvet\"[[:\$1]]\" zo war ar wiki-mañ'''",
'searchmenu-new'                   => "'''Krouiñ ar bajenn \"[[:\$1]]\" war ar wiki-mañ !'''",
'searchhelp-url'                   => 'Help:Skoazell',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Furchal er pajennoù a grog gant ar rakger-mañ]]',
'searchprofile-articles'           => 'Pajennoù gant boued',
'searchprofile-project'            => 'Pajennoù skoazell ha pajennoù ar raktres',
'searchprofile-images'             => 'Liesmedia',
'searchprofile-everything'         => 'Pep tra',
'searchprofile-advanced'           => 'Araokaet',
'searchprofile-articles-tooltip'   => 'Klask e $1',
'searchprofile-project-tooltip'    => 'Klask e $1',
'searchprofile-images-tooltip'     => 'Klask ar restroù',
'searchprofile-everything-tooltip' => "Klask e pep lec'h (er pajennoù kaozeal ivez)",
'searchprofile-advanced-tooltip'   => 'Klask en esaouennoù anv personelaet',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-result-category-size'      => '{{PLURAL:$1|1|$1}} ezel ({{PLURAL:$2|1|$2}} is-rummad, {{PLURAL:$3|1|$3}} restr)',
'search-result-score'              => 'Klotadusted : $1%',
'search-redirect'                  => '(adkas $1)',
'search-section'                   => '(rann $1)',
'search-suggest'                   => "N'hoc'h eus ket soñjet kentoc'h e : $1",
'search-interwiki-caption'         => 'Raktresoù kar',
'search-interwiki-default'         => "$1 disoc'h :",
'search-interwiki-more'            => "(muioc'h)",
'search-mwsuggest-enabled'         => 'gant kinnigoù',
'search-mwsuggest-disabled'        => 'hep kinnigoù',
'search-relatedarticle'            => "Disoc'hoù kar",
'mwsuggest-disable'                => 'Diweredekaat kinnigoù AJAX',
'searcheverything-enable'          => 'Klask en holl esaouennoù anv',
'searchrelated'                    => "disoc'hoù kar",
'searchall'                        => 'An holl',
'showingresults'                   => "Diskouez betek {{PLURAL:$1|'''1''' disoc'h|'''$1''' disoc'h}} o kregiñ gant #'''$2'''.",
'showingresultsnum'                => "Diskouez {{PLURAL:$3|'''1''' disoc'h|'''$3''' disoc'h}} o kregiñ gant #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Disoc'h '''$1''' diwar '''$3'''|Disoc'hoù '''$1 - $2''' diwar '''$3'''}} evit '''$4'''",
'nonefound'                        => "'''Notenn''' : dre ziouer ne glasker nemet en esaouennoù anv zo. Klaskit spisaat ho koulenn gant '''all :''' evit klask e pep lec'h (e-barzh ar pajennoù-kaozeal, ar patromoù, hag all), pe dibabit an esaouenn anv a zere.",
'search-nonefound'                 => "An enklask-mañ ne zisoc'h war netra.",
'powersearch'                      => 'Klask',
'powersearch-legend'               => 'Klask araokaet',
'powersearch-ns'                   => 'Klask en esaouennoù anv :',
'powersearch-redir'                => 'Roll an adkasoù',
'powersearch-field'                => 'Klask',
'powersearch-togglelabel'          => 'Dibab :',
'powersearch-toggleall'            => 'An holl',
'powersearch-togglenone'           => 'Hini ebet',
'search-external'                  => 'Klask diavaez',
'searchdisabled'                   => "<p>Diweredekaet eo bet an arc'hwel klask war an destenn a-bezh evit ur frapad rak ur samm re vras e oa evit ar servijer. Emichañs e vo tu d'e adlakaat pa vo ur servijer galloudusoc'h ganeomp. Da c'hortoz e c'hallit klask gant Google:</p>",

# Quickbar
'qbsettings'               => 'Personelaat ar varrenn ostilhoù',
'qbsettings-none'          => 'Hini ebet',
'qbsettings-fixedleft'     => 'Kleiz',
'qbsettings-fixedright'    => 'Dehou',
'qbsettings-floatingleft'  => 'War-neuñv a-gleiz',
'qbsettings-floatingright' => 'War-neuñv a-zehou',

# Preferences page
'preferences'                   => 'Penndibaboù',
'mypreferences'                 => 'Penndibaboù',
'prefs-edits'                   => 'Niver a zegasadennoù :',
'prefsnologin'                  => 'Digevreet',
'prefsnologintext'              => 'Ret eo deoc\'h bezañ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kevreet]</span> a-benn gellout cheñch ho tibaboù implijer.',
'changepassword'                => 'Kemmañ ar ger-tremen',
'prefs-skin'                    => 'Gwiskadur',
'skin-preview'                  => 'Rakwelet',
'prefs-math'                    => 'Tres jedoniel',
'datedefault'                   => 'Dre ziouer',
'prefs-datetime'                => 'Deiziad hag eur',
'prefs-personal'                => 'Titouroù personel',
'prefs-rc'                      => 'Kemmoù diwezhañ',
'prefs-watchlist'               => 'Roll evezhiañ',
'prefs-watchlist-days'          => 'Niver a zevezhioù da ziskouez er rollad evezhiañ :',
'prefs-watchlist-days-max'      => "(7 devezh d'ar muiañ)",
'prefs-watchlist-edits'         => 'Niver a gemmoù da ziskouez er roll evezhiañ astennet :',
'prefs-watchlist-edits-max'     => '(niver brasañ : 1000)',
'prefs-watchlist-token'         => 'Jedouer evit ar roll evezhiañ :',
'prefs-misc'                    => 'Penndibaboù liesseurt',
'prefs-resetpass'               => 'Cheñch ar ger-tremen',
'prefs-email'                   => 'Dibarzhioù postel',
'prefs-rendering'               => 'Neuz',
'saveprefs'                     => 'Enrollañ ar penndibaboù',
'resetprefs'                    => 'Adlakaat ar penndibaboù kent',
'restoreprefs'                  => 'Adlakaat an holl arventennoù dre ziouer',
'prefs-editing'                 => 'Prenestr skridaozañ',
'prefs-edit-boxsize'            => 'Ment ar prenestr skridaozañ.',
'rows'                          => 'Linennoù :',
'columns'                       => 'Bannoù',
'searchresultshead'             => 'Enklaskoù',
'resultsperpage'                => 'Niver a respontoù dre bajenn :',
'contextlines'                  => 'Niver a linennoù dre respont',
'contextchars'                  => 'Niver a arouezennoù kendestenn dre linenn',
'stub-threshold'                => 'Bevenn uhelañ evit al <a href="#" class="stub">liammoù war-du an danvez pennadoù</a> (okted) :',
'recentchangesdays'             => "Niver a zevezhioù da ziskouez er c'hemmoù diwezhañ :",
'recentchangesdays-max'         => "(d'ar muiañ $1 {{PLURAL:$1|deiz|deiz}})",
'recentchangescount'            => 'Niver a gemmoù da ziskouez dre ziouer',
'prefs-help-recentchangescount' => "Kemer a ra an dra-mañ e kont ar c'hemmoù diwezhañ, istor ar pajennoù hag ar marilhoù.",
'prefs-help-watchlist-token'    => "Leuniañ ar c'hombod-mañ gant un dalvoudenn guzh a lakaio ul lanvad RSS war-sav evit ho rollad evezhiañ.
Kement den hag a ouio eus ho jedouer a c'hallo lenn ho rollad evezhiañ, dibabit un dalvoudegezh suraet.
Setu aze un dalvoudenn ganet dre zegouezh hag a c'hallfec'h implijout : $1",
'savedprefs'                    => 'Enrollet eo bet ar penndibaboù.',
'timezonelegend'                => 'Takad eur :',
'localtime'                     => "Eur lec'hel :",
'timezoneuseserverdefault'      => 'Ober gant talvoudenn ar servijer',
'timezoneuseoffset'             => 'Arall (resisaat al linkadur)',
'timezoneoffset'                => 'Linkadur eur¹ :',
'servertime'                    => 'Eur ar servijer :',
'guesstimezone'                 => 'Ober gant talvoudenn ar merdeer',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azia',
'timezoneregion-atlantic'       => 'Meurvor Atlantel',
'timezoneregion-australia'      => 'Aostralia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Meurvor Indez',
'timezoneregion-pacific'        => 'Meurvor Habask',
'allowemail'                    => 'Aotren ar posteloù a-berzh implijerien all',
'prefs-searchoptions'           => 'Dibarzhioù klask',
'prefs-namespaces'              => 'Esaouennoù',
'defaultns'                     => 'Klask en esaouennoù-anv a-hend-all :',
'default'                       => 'dre ziouer',
'prefs-files'                   => 'Restroù',
'prefs-custom-css'              => 'CSS personelaet',
'prefs-custom-js'               => 'JS personelaet',
'prefs-common-css-js'           => 'JavaScript ha CSS kenrannet evit an holl wiskadurioù :',
'prefs-reset-intro'             => "Ober gant ar bajenn-mañ a c'hallit evit adlakaat ho penndibaboù dre ziouer evit al lec'hienn-mañ. Kement-se n'hallo ket bezañ disc'hraet da c'houde.",
'prefs-emailconfirm-label'      => 'Kadarnaat ar postel :',
'prefs-textboxsize'             => 'Ment ar prenestr skridaozañ',
'youremail'                     => 'Postel *:',
'username'                      => 'Anv implijer :',
'uid'                           => 'Niv. identelezh an implijer :',
'prefs-memberingroups'          => 'Ezel eus {{PLURAL:$1|ar strollad|ar strolladoù}}:',
'prefs-registration'            => 'Deiziad enskrivañ :',
'yourrealname'                  => 'Anv gwir*',
'yourlanguage'                  => 'Yezh an etrefas&nbsp;',
'yourvariant'                   => 'Adstumm:',
'yournick'                      => 'Sinadur :',
'prefs-help-signature'          => 'Dleout a rafe an evezhiadennoù war ar pajennoù kaozeal bezañ sinet gant "<nowiki>~~~~</nowiki>" a vo treuzfurmet en ho sinadur hag euriet.',
'badsig'                        => 'Direizh eo ho sinadur kriz; gwiriit ho palizennoù HTML.',
'badsiglength'                  => "Re hir eo ho sinadur.
Dre ret e rank bezañ nebeutoc'h eget {{PLURAL:$1|arouezenn|arouezenn}} ennañ.",
'yourgender'                    => 'Jener :',
'gender-unknown'                => 'Anresisaet',
'gender-male'                   => 'Paotr',
'gender-female'                 => "Plac'h",
'prefs-help-gender'             => "Diret : implijet evit kenglotadurioù gour e troidigezh etrefas ar meziant.
A-wel d'an holl e vo an titour-mañ.",
'email'                         => 'Postel',
'prefs-help-realname'           => "Diret eo skrivañ hoc'h anv gwir.
Ma skrivit anezhañ e vo implijet evit lakaat war wel ar pezh a vo bet degaset ganeoc'h.",
'prefs-help-email'              => "Diret eo merkañ ur chomlec'h postel met ma lakait unan e vo tu da adkas ur ger-tremen nevez deoc'h ma tichañsfe deoc'h disoñjal ho hini.
Gallout a rit lezel tud all da vont e darempred ganeoc'h dre ho pajennoù implijer ha kaozeal hep na vefe ret deoc'h diskuliañ piv oc'h ivez.",
'prefs-help-email-required'     => "Ezhomm zo eus ur chomlec'h postel.",
'prefs-info'                    => 'Titouroù diazez',
'prefs-i18n'                    => 'Etrebroadelaat',
'prefs-signature'               => 'Sinadur',
'prefs-dateformat'              => 'Furmad an deiziadoù',
'prefs-timeoffset'              => 'Linkadur eur',
'prefs-advancedediting'         => 'Dibarzhioù araokaet',
'prefs-advancedrc'              => 'Dibarzhioù araokaet',
'prefs-advancedrendering'       => 'Dibarzhioù araokaet',
'prefs-advancedsearchoptions'   => 'Dibarzhioù araokaet',
'prefs-advancedwatchlist'       => 'Dibarzhioù araokaet',
'prefs-displayrc'               => 'Dibarzhioù diskwel',
'prefs-displaysearchoptions'    => 'Dibarzhioù diskwel',
'prefs-displaywatchlist'        => 'Dibarzhioù diskwel',
'prefs-diffs'                   => "Diforc'hioù",

# User rights
'userrights'                   => 'Merañ statud an implijerien',
'userrights-lookup-user'       => 'Merañ strolladoù an implijer',
'userrights-user-editname'     => 'Lakait un anv implijer :',
'editusergroup'                => 'Kemmañ ar strolladoù implijerien',
'editinguser'                  => "Kemmañ gwirioù an implijer '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Kemmañ strolladoù an implijer',
'saveusergroups'               => 'Enrollañ ar strolladoù implijer',
'userrights-groupsmember'      => 'Ezel eus :',
'userrights-groupsmember-auto' => 'Ezel emplegat eus :',
'userrights-groups-help'       => "Cheñch strollad an implijer a c'hallit ober.
* Ul log asket a verk emañ an implijer er strollad.
* Ul log diask a verk n'emañ ket an implijer er strollad.
* Ur * a verk n'hallit ket dilemel ar strollad ur wech bet ouzhpennet, pe ar c'hontrol.",
'userrights-reason'            => 'Abeg :',
'userrights-no-interwiki'      => "N'oc'h ket aotreet da gemmañ ar gwirioù implijer war wikioù all.",
'userrights-nodatabase'        => "N'eus ket eus an diaz titouroù $1 pe n'eo ket lec'hel.",
'userrights-nologin'           => "Ret eo deoc'h [[Special:UserLogin|bezañ enrollet]] gant ur gont merour a-benn reiñ gwirioù implijer.",
'userrights-notallowed'        => "N'eo ket aotreet ho kont da reiñ gwirioù implijer.",
'userrights-changeable-col'    => "Ar strolladoù a c'hallit cheñch",
'userrights-unchangeable-col'  => "Ar strolladoù n'hallit ket cheñch",

# Groups
'group'               => 'Strollad :',
'group-user'          => 'Implijerien',
'group-autoconfirmed' => 'Implijerien bet kadarnaet ent emgefre',
'group-bot'           => 'Botoù',
'group-sysop'         => 'Merourien',
'group-bureaucrat'    => 'Pennoù-bras',
'group-suppress'      => 'Dindan evezh',
'group-all'           => '(pep tra)',

'group-user-member'          => 'Implijer',
'group-autoconfirmed-member' => 'Implijer bet kadarnaet ent emgefre',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Merour',
'group-bureaucrat-member'    => 'Penn-bras',
'group-suppress-member'      => 'Dindan evezh',

'grouppage-user'          => '{{ns:project}}:Implijerien',
'grouppage-autoconfirmed' => '{{ns:project}}: Implijerien bet kadarnaet ent emgefre',
'grouppage-bot'           => '{{ns:project}}:Botoù',
'grouppage-sysop'         => '{{ns:project}}:Merourien',
'grouppage-bureaucrat'    => '{{ns:project}}: Pennoù-bras',
'grouppage-suppress'      => '{{ns:project}}: Dindan evezh',

# Rights
'right-read'                  => 'Lenn ar pajennoù',
'right-edit'                  => 'Kemmañ ar pajennoù',
'right-createpage'            => 'Krouiñ pajennoù (estreget pajennoù kaozeal)',
'right-createtalk'            => 'Krouiñ pajennoù kaozeal',
'right-createaccount'         => 'Krouiñ kontoù implijer nevez',
'right-minoredit'             => "Merkañ ar c'hemmoù evel kemmoù dister",
'right-move'                  => 'Adenvel pajennoù',
'right-move-subpages'         => "Dilec'hiañ ar pajennoù gant o ispajennoù",
'right-move-rootuserpages'    => 'Adenvel pajennoù diazez an implijer',
'right-movefile'              => "Dilec'hiañ ar restroù",
'right-suppressredirect'      => 'Chom hep sevel un adkas adalek ar bajenn gozh en ur adenvel ar bajenn',
'right-upload'                => 'Enporzhiañ restroù',
'right-reupload'              => 'Frikañ ur restr zo anezhi dija',
'right-reupload-own'          => 'Frikañ ur restr bet pellgarget gant an-unan',
'right-reupload-shared'       => "Gwaskañ restroù ent lec'hel war an diellaoueg vedia rannet",
'right-upload_by_url'         => "Enporzhiañ ur restr adal ur chomlec'h URL",
'right-purge'                 => 'Spujañ krubuilh ar pajennoù hep kadarnaat',
'right-autoconfirmed'         => 'Kemmañ ar pajennoù damwarezet',
'right-bot'                   => 'Plediñ ganti evel gant un argerzh emgefre',
'right-nominornewtalk'        => 'Arabat diskouez ar c\'hemenn "Kemennoù nevez zo ganeoc\'h" pa vez degaset kemmoù dister war pajenn gaozeal un implijer',
'right-apihighlimits'         => 'Kreskiñ ar bevennoù er goulennoù API',
'right-writeapi'              => 'Ober gant an API evit kemmañ ar wiki',
'right-delete'                => 'Diverkañ pajennoù',
'right-bigdelete'             => 'Diverkañ pajennoù dezho un hir a istor',
'right-deleterevision'        => 'Diverkañ ha diziverkañ stummoù zo eus ur pajenn',
'right-deletedhistory'        => 'Gwelet anvioù an istorioù diverket hep diskouez an destenn stag outo',
'right-deletedtext'           => "Gwelet ar skrid diverket hag an diforc'hioù etre ar stummoù diverket",
'right-browsearchive'         => 'Klask pajennoù bet diverket',
'right-undelete'              => 'Assevel ur bajenn',
'right-suppressrevision'      => 'Teuler ur sell war ar stummoù kuzhet ouzh ar verourien hag assevel anezho',
'right-suppressionlog'        => 'Gwelet ar marilhoù prevez',
'right-block'                 => "Mirout ouzh an implijerien all a zegas kemmoù pelloc'h",
'right-blockemail'            => 'Mirout ouzh un implijer a gas posteloù',
'right-hideuser'              => 'Stankañ un implijer, en ur guzhat anezhañ diouzh ar re all',
'right-ipblock-exempt'        => "Tremen dreist an IPoù stanket, ar stankadennoù emgefre hag ar bloc'hadennoù IP stanket",
'right-proxyunbannable'       => 'Temen dreist stankadennoù emgefre ar proksioù',
'right-unblockself'           => 'En em zistankañ drezo o unan',
'right-protect'               => 'Kemmañ live gwareziñ ar pajennoù ha kemmañ ar pajennoù gwarezet',
'right-editprotected'         => 'Kemmañ ar pajennoù gwarezet (hep gwarez dre skalierad)',
'right-editinterface'         => 'Degas kemmoù war an etrefas implijer',
'right-editusercssjs'         => 'Kemmañ restroù CSS ha JS implijerien all',
'right-editusercss'           => 'Kemmañ restroù CSS implijerien all',
'right-edituserjs'            => 'Kemmañ restroù JS implijerien all',
'right-rollback'              => 'Disteuler prim kemmoù an implijer diwezhañ en deus kemmet ur bajenn resis',
'right-markbotedits'          => "Merkañ ar c'hemmoù distaolet evel kemmoù bet graet gant robotoù.",
'right-noratelimit'           => 'Na sell ket ar bevennoù feurioù outañ',
'right-import'                => 'Enporzhiañ pajennoù adalek wikioù all',
'right-importupload'          => 'Enporzhiañ pajennoù adal ur restr',
'right-patrol'                => 'Merkañ kemmoù ar re all evel gwiriet',
'right-autopatrol'            => 'Merkañ e gemmoù evel gwiriekaet, ent emgefre',
'right-patrolmarks'           => 'Gwelet kemmoù diwezhañ ar merkoù patrouilhañ',
'right-unwatchedpages'        => "Gwelet roll ar pajennoù n'int ket evezhiet",
'right-trackback'             => "Ouzhpennañ ur c'hilliamm",
'right-mergehistory'          => 'Unvaniñ istor ar pajennoù',
'right-userrights'            => 'Kemmañ holl wirioù un implijer',
'right-userrights-interwiki'  => 'Kemmañ ar gwirioù implijer zo war ur wiki all',
'right-siteadmin'             => 'Prennañ ha dibrennañ ar bank-titouroù',
'right-reset-passwords'       => 'Kemmañ ger-tremen implijerien all',
'right-override-export-depth' => 'Ezporzhiañ ar pajennoù en ur lakaat e-barzh ar pajennoù liammet betek un donder a 5 live',
'right-sendemail'             => "Kas ur postel d'an implijerien all",
'right-revisionmove'          => "Dilec'hiañ an adweladennoù",
'right-selenium'              => 'Seveniñ an testoù seleniom',

# User rights log
'rightslog'      => 'Marilh statud an implijerien',
'rightslogtext'  => "Setu marilh ar c'hemmoù statud bet c'hoarvezet d'an implijerien.",
'rightslogentry' => '{{Gender:.|en|he}} deus cheñchet gwirioù an {{Gender:.|implijer|implijerez}}  $1 a oa $2 hag a zo bet lakaet da $3',
'rightsnone'     => '(netra)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lenn ar bajenn-mañ',
'action-edit'                 => 'degas kemmoù war ar bajenn-mañ',
'action-createpage'           => 'krouiñ pajennoù',
'action-createtalk'           => 'krouiñ pajennoù kaozeal',
'action-createaccount'        => 'krouiñ ar gont implijer-mañ',
'action-minoredit'            => "merkañ ar c'hemm-mañ evel dister",
'action-move'                 => "dilec'hiañ ar bajenn-mañ",
'action-move-subpages'        => "dilec'hiañ ar bajenn-mañ hag an ispajennoù anezhi",
'action-move-rootuserpages'   => "dilec'hiañ pajennoù an implijer diazez.",
'action-movefile'             => 'Adenvel ar restr-mañ',
'action-upload'               => 'enporzhiañ ar restr-mañ',
'action-reupload'             => 'frikañ ar restr-mañ',
'action-reupload-shared'      => 'Frikañ ar restr-mañ zo war ur sanailh rannet',
'action-upload_by_url'        => "pellgargañ ar restr-mañ adal ur chomlec'h URL",
'action-writeapi'             => 'Ober gant an API skrivañ',
'action-delete'               => 'diverkañ ar bajenn-mañ',
'action-deleterevision'       => 'diverkañ ar stumm-mañ',
'action-deletedhistory'       => 'Gwelet istor diverket ar bajenn-mañ',
'action-browsearchive'        => 'Klask pajennoù bet diverket',
'action-undelete'             => 'Diziverkañ ar bajenn-mañ',
'action-suppressrevision'     => 'gwelet hag assevel ar stumm diverket-mañ',
'action-suppressionlog'       => 'gwelet ar marilh prevez-mañ',
'action-block'                => 'mirout ouzh an impplijer-mañ da zegas kemmoù',
'action-protect'              => 'kemmañ liveoù gwareziñ ar bajenn-mañ',
'action-import'               => 'Enporzhiañ ar bajenn-mañ adal ur wiki all',
'action-importupload'         => 'Enporzhiañ ar bajenn-mañ adal ur restr pellgarget',
'action-patrol'               => 'merkañ kemmoù ar re all evel gwiriet',
'action-autopatrol'           => 'bezañ merket ho tegasadennoù evel gwiriet',
'action-unwatchedpages'       => "gwelet roll ar pajennoù n'int ket evezhiet",
'action-trackback'            => "zegas ur c'hilliamm",
'action-mergehistory'         => 'kendeuziñ istor ar bajenn-mañ',
'action-userrights'           => 'Kemmañ an holl wirioù implijer',
'action-userrights-interwiki' => 'Kemmañ gwirioù an implijerien war wikioù all',
'action-siteadmin'            => 'Prennañ pe dibrennañ ar bank roadennoù',
'action-revisionmove'         => "dilec'hiañ an adweladennoù",

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|kemm|kemm}}',
'recentchanges'                     => 'Kemmoù diwezhañ',
'recentchanges-legend'              => "Dibarzhioù ar c'hemmoù diwezhañ",
'recentchangestext'                 => "Dre ar bajenn-mañ e c'hallit heuliañ ar c'hemmoù diwezhañ bet degaset d'ar wiki.",
'recentchanges-feed-description'    => "Heuilhit ar c'hemmoù diwezhañ er wiki el lusk-mañ.",
'recentchanges-label-legend'        => "Alc'hwez : $1.",
'recentchanges-legend-newpage'      => '$1 - pajenn nevez',
'recentchanges-label-newpage'       => "Gant ar c'hemm-mañ e vo krouet ur bajenn nevez.",
'recentchanges-legend-minor'        => '$1 - kemm dister',
'recentchanges-label-minor'         => "Ur c'hemm dister eo hemañ",
'recentchanges-legend-bot'          => '$1 - kemm degaset gant ur robot',
'recentchanges-label-bot'           => "Gant ur bot eo bet degaset ar c'hemm-mañ.",
'recentchanges-legend-unpatrolled'  => "$1 - kemm n'eo ket bet gwiriet",
'recentchanges-label-unpatrolled'   => "N'eo ket bet gwiriet ar c'hemm-mañ evit c'hoazh.",
'rcnote'                            => "Setu aze an {{PLURAL:$1|'''1''' change|'''$1''' kemm diwezhañ}} bet c'hoarvezet e-pad an {{PLURAL:$2|deiz|'''$2''' deiz}} diwezhañ, savet d'an $4 da $5.",
'rcnotefrom'                        => "Setu aze roll ar c'hemmoù c'hoarvezet abaoe an '''$2''' ('''$1''' d'ar muiañ).",
'rclistfrom'                        => "Diskouez ar c'hemmoù diwezhañ abaoe an $1.",
'rcshowhideminor'                   => "$1 ar c'hemmoù dister",
'rcshowhidebots'                    => '$1 ar robotoù',
'rcshowhideliu'                     => '$1 an implijerien enrollet',
'rcshowhideanons'                   => '$1 an implijerien dizanv',
'rcshowhidepatr'                    => "$1 ar c'hemmoù gwiriet",
'rcshowhidemine'                    => "$1 ma c'hemmoù",
'rclinks'                           => "Diskouez an $1 kemm diwezhañ c'hoarvezet e-pad an $2 devezh diwezhañ<br />$3",
'diff'                              => "diforc'h",
'hist'                              => 'ist',
'hide'                              => 'kuzhat',
'show'                              => 'diskouez',
'minoreditletter'                   => 'D',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|implijer o heuliañ|implijer}} o heuliañ]',
'rc_categories'                     => 'Bevenn ar rummadoù (dispartiañ gant "|")',
'rc_categories_any'                 => 'An holl',
'newsectionsummary'                 => '/* $1 */ rann nevez',
'rc-enhanced-expand'                => 'Diskouez ar munudoù (JavaScript rekis)',
'rc-enhanced-hide'                  => 'Kuzhat munudoù',

# Recent changes linked
'recentchangeslinked'          => 'Heuliañ al liammoù',
'recentchangeslinked-feed'     => 'Heuliañ al liammoù',
'recentchangeslinked-toolbox'  => 'Heuliañ al liammoù',
'recentchangeslinked-title'    => 'Kemmoù a denn da "$1"',
'recentchangeslinked-noresult' => 'Kemm ebet war ar pajennoù liammet e-pad an amzer spisaet.',
'recentchangeslinked-summary'  => "Rollet eo war ar bajenn dibar-mañ ar c'hemmoù diwezhañ bet degaset war ar pajennoù liammet ouzh ur bajenn lakaet (pe ouzh izili ur rummad lakaet).
E '''tev''' emañ ar pajennoù zo war ho [[Special:Watchlist|roll evezhiañ]].",
'recentchangeslinked-page'     => 'Anv ar bajenn:',
'recentchangeslinked-to'       => "Diskouez ar c'hemmoù war-du ar pajennoù liammet kentoc'h eget re ar bajenn lakaet",

# Upload
'upload'                      => 'Kargañ war ar servijer',
'uploadbtn'                   => 'Kargañ ur restr',
'reuploaddesc'                => "Distreiñ d'ar furmskrid.",
'upload-tryagain'             => 'Kas deskrivadur ar restr kemmet',
'uploadnologin'               => 'digevreet',
'uploadnologintext'           => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreetet]] a-benn gellout enporzhiañ restroù war ar servijer.",
'upload_directory_missing'    => "Mankout a ra ar c'havlec'h enporzhiañ ($1) ha n'eo ket bet ar servijer Web evit e grouiñ.",
'upload_directory_read_only'  => "N'hall ket ar servijer skrivañ e renkell ar c'hargadennoù ($1).",
'uploaderror'                 => 'Fazi',
'upload-recreate-warning'     => "'''Diwallit''' : Diverket pe dilec'hiet ez eus bet ur restr gant an anv-se.'''

Deoc'h da c'houzout, setu aze marilh an diverkañ hag an dilec'hiañ evit ar bajenn-mañ.",
'uploadtext'                  => "Grit gant ar furmskrid a-is evit enporzhiañ restroù war ar servijer.
Evit sellet pe klask skeudennoù bet enporzhiet a-raok sellit ouzh [[Special:FileList|roll ar skeudennoù]]. Kavet e vo ar skeudennoù enporzhiet war [[Special:Log/upload|marilh ar pajennoù enporzhiet]] hag an diverkadennoù war [[Special:Log/delete|istor an diverkadennoù]].

Evit enklozañ ur skeudenn en ur pennad, lakait er pennad-se ul liamm skrivet evel-henn :
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:anv_ar_restr.jpg]]</nowiki></code>''' evit diskouez ar restr en he spider brasañ ;
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:anv_ar_restr.png|deskrivadenn]]</nowiki></code>''' evit ober gant ur munud 200 piksel ledander er ur voest a-gleiz enni \"testenn zeskrivañ\" da zeskrivadenn
*'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:anv_ar_restr.ogg]]</nowiki></code>''' evit sevel ul liamm war-eeun war-du ar restr hep diskouez anezhi.",
'upload-permitted'            => 'Seurtoù restroù aotreet : $1.',
'upload-preferred'            => 'Seurtoù restroù gwellañ : $1.',
'upload-prohibited'           => 'Seurtoù restroù berzet : $1.',
'uploadlog'                   => 'marilh ar pajennoù enporzhiet',
'uploadlogpage'               => 'Marilh ar pajennoù enporzhiet',
'uploadlogpagetext'           => "Setu a-is marilh ar restroù diwezhañ bet karget war ar servijer.
S.o [[Special:NewFiles|rann ar skeudennoù nevez]] evit kaout ur sell gwiroc'h",
'filename'                    => 'Anv&nbsp;',
'filedesc'                    => 'Deskrivadur',
'fileuploadsummary'           => 'Diverrañ :',
'filereuploadsummary'         => 'Kemmoù er restr :',
'filestatus'                  => 'Statud ar gwirioù aozer:',
'filesource'                  => 'Mammenn :',
'uploadedfiles'               => 'Restroù karget',
'ignorewarning'               => "Na ober van ouzh ar c'hemennoù diwall ha saveteiñ ar restr forzh penaos",
'ignorewarnings'              => "Na ober van ouzh ar c'hemennoù diwall",
'minlength1'                  => 'Anv ar restroù a rank bezañ keit hag ul lizherenn da nebeutañ.',
'illegalfilename'             => "Lakaet ez eus bet er restr « $1 » arouezennoù n'int ket aotreet evit titl ur bajenn. Mar plij, adanvit ar restr hag adkasit anezhi.",
'badfilename'                 => 'Anvet eo bet ar skeudenn « $1 ».',
'filetype-mime-mismatch'      => 'Ne glot ket astenn ar restr gant ar seurt MIME.',
'filetype-badmime'            => 'N\'eo ket aotreet pellgargañ ar restroù a seurt MIME "$1".',
'filetype-bad-ie-mime'        => 'Dibosupl enporzhiañ ar restr-mañ rak detektet e vefe evel "$1" gant Internet Explorer, ur seurt restroù berzet rak arvarus sañset.',
'filetype-unwanted-type'      => "'''Eus ar seurt restroù n'int ket c'hoantaet eo \".\$1\"'''.  Ar re a zere ar gwellañ zo eus {{PLURAL:\$3|ar seurt|ar seurt}} \$2.",
'filetype-banned-type'        => "'''N'eo ket \".\$1\"''' eus ar seurt restroù aotreet.
\$2 eo {{PLURAL:\$3|ar seurt restroù|ar seurt restroù}} degemeret.",
'filetype-missing'            => 'N\'eus astenn ebet stag ouzh ar restr (evel ".jpg").',
'empty-file'                  => "Ar restr hoc'h eus roet a oa goullo.",
'file-too-large'              => "Ar restr hoc'h eus roet a oa re vras.",
'filename-tooshort'           => 'Re verr eo anv ar restr.',
'filetype-banned'             => 'Difennet eo ar seurt restroù',
'verification-error'          => 'Korbellet eo bet ar restr-mañ gant ar gwiriañ restroù.',
'hookaborted'                 => "Ar c'hemm hoc'h eus klasket ober a zo bet paouezet gant ur sonnell astenn.",
'illegal-filename'            => "N'eo ket aotreet anv ar restr.",
'overwrite'                   => "N'eo ket aotreet frikañ ur restr zo anezhi c'hoazh.",
'unknown-error'               => 'Ur gudenn dizanv a zo bet.',
'tmp-create-error'            => 'Dibosupl eo krouiñ ur restr padennek.',
'tmp-write-error'             => 'Ur gudenn skrivañ a zo bet evit ar restr padennek.',
'large-file'                  => "Erbediñ a reer ne vefe ket brasoc'h ar restroù eget $1; $2 eo ment ar restr-mañ.",
'largefileserver'             => "Brasoc'h eo ar restr-mañ eget ar pezh a c'hall ar servijer aotren.",
'emptyfile'                   => "Evit doare eo goullo ar restr bet karget ganeoc'h. Moarvat eo abalamour d'an tipo en anv ar restr. Gwiriit mat e fell deoc'h pellgargañ ar restr-mañ.",
'fileexists'                  => "Ur restr all gant an anv-se zo c'hoazh.
Trugarez da wiriañ '''<tt>[[:$1]]</tt>''' ma n'oc'h ket sur e fell deoc'h kemmañ anezhi.
[[$1|thumb]]",
'filepageexists'              => "Amañ '''<tt>[[:$1]]</tt>''' eo bet krouet ar bajenn zeskrivañ evit ar restr-mañ, padal n'eus restr ebet dezhi an anv-se evit c'hoazh.
An diverradenn skrivet ganeoc'h ne vo ket gwelet war ar bajenn zeskrivañ.
Mar fell deoc'h e teufe ho tiverradenn war wel eno eo ret deoc'h-c'hwi kemmañ anezhi hoc'h-unan.
[[$1|thumb]]",
'fileexists-extension'        => "Bez' ez eus dija ur restr gant an anv-se war-bouez nebeut : [[$2|thumb]]
* Anv ar restr emeur oc'h enporzhiañ : '''<tt>[[:$1]]</tt>'''
* Anv ar restr zo anezhi dija : '''<tt>[[:$2]]</tt>'''
Dibabit un anv all mar plij.",
'fileexists-thumbnail-yes'    => "Evit doare ez eus ur skeudenn krennet he ment eus ar restr ''(thumbnail)''. [[$1|thumb]]
Gwiriit ar restr '''<tt>[[:$1]]</tt>'''.
Mard eo an hevelep skeudenn ha hini ar restr orin, ha heñvel he ment, n'eo ket dav pellgargañ ur stumm krennet ouzhpenn.",
'file-thumbnail-no'           => "Kregiñ a ra anv ar restr gant '''<tt>$1</tt>'''.
Evit doare eo ur skeudenn krennet he ment ''(thumbnail)''.
Ma'z eus ganeoc'h ur skeudenn uhel he fizhder, pellgargit anezhi; a-hend-all cheñchit anv ar restr.",
'fileexists-forbidden'        => "Ur restr all gant an anv-se zo c'hoazh ha n'hall ket bezan diverket.
Mar fell deoc'h enporzhiañ ho restr memes tra, kit war ho kiz ha grit gant un anv all [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Ur restr all dezhi an hevelep anv zo c'hoazh er c'havlec'h eskemm restroù.
Mar fell deoc'h enporzhiañ ar restr-mañ da vat, kit war ho kiz hag enporzhiit anezhi adarre dindan un anv all. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => 'Un eil eus ar {{PLURAL:$1|restr|restroù}} da-heul eo ar restr-mañ :',
'file-deleted-duplicate'      => "Diverket ez eus bet c'hoazh ur restr heñvel-poch ouzh ar restr-mañ ([[$1]]). Gwelloc'h e vefe deoc'h teuler ur sell war istor diverkadenn ar bajenn-se a-raok hec'h enporzhiañ en-dro.",
'uploadwarning'               => 'Diwallit!',
'uploadwarning-text'          => 'Cheñchit deskrivadur ar restr a-is ha klaskit en-dro.',
'savefile'                    => 'Enrollañ ar restr',
'uploadedimage'               => '"[[$1]]" enporzhiet',
'overwroteimage'              => 'enporzhiet ur stumm nevez eus "[[$1]]"',
'uploaddisabled'              => 'Ho tigarez, diweredekaet eo bet kas ar restr-mañ.',
'copyuploaddisabled'          => 'Diweredekaet eo bet ar pellgargañ dre URL.',
'uploadfromurl-queued'        => 'Lakaet eo bet ho pellgargadenn er roll gortoz.',
'uploaddisabledtext'          => 'Diweredekaet eo an enporzhiañ restroù.',
'php-uploaddisabledtext'      => 'Diweredekaet eo bet ar pellgargañ e PHP. Gwiriit an dibarzh arventennoù file_uploads.',
'uploadscripted'              => "Er restr-mañ ez eus kodoù HTML pe skriptoù a c'hallfe bezañ kammgomprenet gant ur merdeer Kenrouedad.",
'uploadvirus'                 => 'Viruzet eo ar restr! Titouroù : $1',
'upload-source'               => 'Restr tarzh',
'sourcefilename'              => 'Anv ar restr tarzh :',
'sourceurl'                   => 'URL tarzh :',
'destfilename'                => 'Anv ma vo enrollet ar restr :',
'upload-maxfilesize'          => 'Ment vrasañ ar restr : $1',
'upload-description'          => 'Deskrivadur ar restr',
'upload-options'              => 'Dibaboù kargañ',
'watchthisupload'             => 'Evezhiañ ar bajenn-mañ',
'filewasdeleted'              => "Ur restr gant an anv-mañ zo bet enporzhiet dija ha diverket goude-se. Mat e vefe deoc'h gwiriañ an $1 a-raok hec'h enporzhiañ en-dro.",
'upload-wasdeleted'           => "'''Diwallit : Oc'h enporzhiañ ur bajenn bet diverket c'hoazh emaoc'h.'''

En em soñjit ervat ha mat eo kenderc'hel da enporzhiañ ar restr-mañ.
Kavit amañ ar marilh diverkañ evit ar restr-mañ :",
'filename-bad-prefix'         => "Anv ar restr emaoc'h oc'h enporzhiañ a grog gant '''\"\$1\"''', da lavaret eo un anv dizeskrivus roet alies ent emgefre gant luc'hskeudennerezioù niverel. Dibabit un anv splannoc'h evit deskrivañ ar restr.",
'filename-prefix-blacklist'   => " #<!-- lezel al linenn-mañ tre ha tre evel m'emañ --> <pre>
# Setu penaos emañ an ereadur :
#   * Pep tra adal un arouezenn \"#\" betek dibenn al linenn a ya d'ober un notenn
#   * Pep linenn n'eo ket goullo zo ur rakger evit anvioù restroù heverk roet ent emgefre gant luc'hskeudennerezioù niverel
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # pellgomzerioù hezoug zo
IMG # jenerik
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- leave this line exactly as it is -->",
'upload-success-subj'         => 'Eiladenn kaset da benn vat',
'upload-success-msg'          => 'Ho kargadenn eus [$2] a zo bet graet. Gellout a rit kavout anezhi amañ : [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Kudenn kargañ',
'upload-failure-msg'          => "Ur gudenn 'zo bet e-pad ho kargadenn :

$1",
'upload-warning-subj'         => "Kemmen diwall e-pad ar c'hargañ",

'upload-proto-error'        => 'Protokol direizh',
'upload-proto-error-text'   => 'Rekis eo an URLoù a grog gant <code>http://</code> pe <code>ftp://</code> evit enporzhiañ.',
'upload-file-error'         => 'Fazi diabarzh',
'upload-file-error-text'    => "Ur fazi diabarzh zo c'hoarvezet en ur grouiñ ur restr da c'hortoz war ar servijer.
Kit e darempred gant [[Special:ListUsers/sysop|unan eus merourien ar reizhiad]].",
'upload-misc-error'         => 'Fazi kargañ dianav',
'upload-misc-error-text'    => "Ur fazi dianav zo bet e-ser kargañ.
Gwiriit eo reizh an URL hag e c'hall bezañ tizhet ha klaskit en-dro.
Ma talc'h ar gudenn, kit e darempred gant [[Special:ListUsers/sysop|merourien ar reizhiad]].",
'upload-too-many-redirects' => 'Re a adkasoù zo en URL-mañ.',
'upload-unknown-size'       => 'Ment dianav',
'upload-http-error'         => 'Ur fazi HTTP zo bet : $1',

# img_auth script messages
'img-auth-accessdenied' => "Moned nac'het",
'img-auth-nopathinfo'   => "Mankout a ra ar PATH_INFO.
N'eo ket kefluniet ho servijer evit reiñ an titour-mañ.
Marteze eo diazezet war CGI-based ha n'hall ket skorañ img_auth.
Gwelet http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir'     => "N'emañ ket an hent merket er c'havlec'h enporzhiañ kefluniet.",
'img-auth-badtitle'     => 'Dibosupl krouiñ un titl reizh adalek "$1".',
'img-auth-nologinnWL'   => 'N\'oc\'h ket kevreet ha n\'emañ ket "$1" war ar roll gwenn',
'img-auth-nofile'       => 'n\'eus ket eus ar restr "$1".',
'img-auth-isdir'        => "Klakset hoc'h eus monet d'ar c'havlec'h \"\$1\".
N'haller monet nemet d'ar restroù.",
'img-auth-streaming'    => 'O lenn en ur dremen "$1"',
'img-auth-public'       => "Talvezout a ra an arc'hwel img_auth.php da ezvont restroù adalek ur wiki prevez.
Kefluniet eo bet ar wiki-mañ evel ur wiki foran.
Diweredekaet eo bet img_auth.php evit ur surentez eus ar gwellañ",
'img-auth-noread'       => 'N\'eo ket aotreet an implijer da lenn "$1"',

# HTTP errors
'http-invalid-url'      => 'URL direizh : $1',
'http-invalid-scheme'   => 'N\'eo ket skoret an URLoù gant ar patrom "$1"',
'http-request-error'    => "Ur fazi dianavezet 'zo bet pa veze kaset ar reked.",
'http-read-error'       => 'Fazi lenn HTTP.',
'http-timed-out'        => 'Erru eo termen ar reked HTTP.',
'http-curl-error'       => 'Fazi adtapout an URL : $1',
'http-host-unreachable' => "N'eus ket bet gallet tizhout an URL.",
'http-bad-status'       => 'Ur gudenn a zo bet e-pad ar reked HTTP : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "N'eus ket bet gallet tizhout an URL",
'upload-curl-error6-text'  => "N'eus ket bet gallet tizhout an URL. Gwiriit mat eo reizh an URL hag emañ al lec'hienn enlinenn.",
'upload-curl-error28'      => "Aet dreist d'an termen",
'upload-curl-error28-text' => "Re bell eo bet al lec'hienn o respont. Gwiriit mat emañ al lec'hienn enlinenn, gortozit ur pennadig ha klaskit en-dro. Mat e vo deoc'h adklask d'ur mare dibresoc'h marteze ivez.",

'license'            => 'Aotre implijout :',
'license-header'     => 'Aotre implijout',
'nolicense'          => 'Hini ebet diuzet',
'license-nopreview'  => '(Dibosupl rakwelet)',
'upload_source_url'  => " (Un URL reizh a c'hall bezañ tizhet gant an holl)",
'upload_source_file' => " (ur restr war hoc'h urzhiataer)",

# Special:ListFiles
'listfiles-summary'     => 'Diskouez a ra ar bajenn dibar-mañ an holl restroù bet enporzhiet.
Dre ziouer e teu ar restroù enporzhiet da ziwezhañ e laez ar roll.
Klikañ e penn ar bann a cheñch an urzh kinnig.',
'listfiles_search_for'  => 'Klask anv ar skeudenn :',
'imgfile'               => 'restr',
'listfiles'             => 'Roll ar skeudennoù',
'listfiles_date'        => 'Deiziad',
'listfiles_name'        => 'Anv',
'listfiles_user'        => 'Implijer',
'listfiles_size'        => 'Ment (e bitoù)',
'listfiles_description' => 'Deskrivadur',
'listfiles_count'       => 'Stummoù',

# File description page
'file-anchor-link'          => 'Skeudenn',
'filehist'                  => 'Istor ar restr',
'filehist-help'             => 'Klikañ war un deiziad/eur da welet ar restr evel ma oa da neuze.',
'filehist-deleteall'        => 'diverkañ pep tra',
'filehist-deleteone'        => 'diverkañ',
'filehist-revert'           => 'disteuler',
'filehist-current'          => 'red',
'filehist-datetime'         => 'Deiziad/Eur',
'filehist-thumb'            => 'Munud',
'filehist-thumbtext'        => 'Munud eus stumm an $1',
'filehist-nothumb'          => 'Munud ebet',
'filehist-user'             => 'Implijer',
'filehist-dimensions'       => 'Mentoù',
'filehist-filesize'         => 'Ment ar restr',
'filehist-comment'          => 'Notenn',
'filehist-missing'          => 'Restr diank',
'imagelinks'                => "Liammoù d'ar restr",
'linkstoimage'              => "Liammet eo {{PLURAL:$1|ar bajenn-mañ|an $1 pajenn-mañ}} d'ar restr-mañ :",
'linkstoimage-more'         => "Ouzhpenn $1 {{PLURAL:$1|bajenn zo liammet ouzh|pajenn zo liammet ouzh}} ar restr-mañ.
Ne laka ar roll-mañ war wel nemet {{PLURAL:$1|ar bajenn gentañ liammet ouzh|an $1 pajenn gentañ liammet ouzh}} ar rest-mañ.
Ur [[Special:WhatLinksHere/$2|roll klok]] a c'haller da gaout.",
'nolinkstoimage'            => "N'eus liamm ebet war-du ar skeudenn-mañ war pajenn ebet.",
'morelinkstoimage'          => 'Gwelet [[Special:WhatLinksHere/$1|liammoù ouzhpenn]] war-du ar restr-mañ.',
'redirectstofile'           => 'Adkas a ra ar{{PLURAL:$1|restr-mañ|$1 restr-mañ}} war-du homañ :',
'duplicatesoffile'          => "Un eil eus ar restr-mañ eo {{PLURAL:$1|ar restr da-heul|ar restroù da-heul}}, ([[Special:FileDuplicateSearch/$2|evit gouzout hiroc'h]]) :",
'sharedupload'              => 'Dont a ra ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.',
'sharedupload-desc-there'   => "Tennet eo ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.
Mar fell deoc'h gouzout hiroc'h sellit ouzh [$2 ar bajenn zeskrivañ].",
'sharedupload-desc-here'    => 'Tennet eo ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.
Diskouezet eo deskrivadur he [$2 fajenn zeskrivañ] amañ dindan.',
'filepage-nofile'           => "N'eus restr ebet dezhi an anv-se.",
'filepage-nofile-link'      => "N'eus restr ebet dezhi an anv-se, met gallout a rit [$1 pellgargañ anezhi].",
'uploadnewversion-linktext' => 'Kargañ ur stumm nevez eus ar restr-mañ',
'shared-repo-from'          => 'eus $1',
'shared-repo'               => 'ur sanailh rannet',

# File reversion
'filerevert'                => 'Disteuler $1',
'filerevert-legend'         => 'Disteuler ar restr',
'filerevert-intro'          => "Emaoc'h o tistreiñ '''[[Media:$1|$1]]''' d'ar [stumm $4 eus $3, $2].",
'filerevert-comment'        => 'Abeg :',
'filerevert-defaultcomment' => 'Distroet da stumm $2, $1',
'filerevert-submit'         => 'Disteuler',
'filerevert-success'        => "'''Distroet eo bet [[Media:$1|$1]]''' da [stumm $4 an $3, $2].",
'filerevert-badversion'     => "N'eus stumm lec'hel kent ebet eus ar restr-mañ d'ar mare spisaet.",

# File deletion
'filedelete'                  => 'Diverkañ $1',
'filedelete-legend'           => 'Diverkañ ar restr',
'filedelete-intro'            => "War-nes diverkañ '''[[Media:$1|$1]]''' a-gevret gant e istor emaoc'h.",
'filedelete-intro-old'        => "Emaoc'h o tiverkañ stumm '''[[Media:$1|$1]]''' eus [$4 $3, $2].",
'filedelete-comment'          => 'Abeg :',
'filedelete-submit'           => 'Diverkañ',
'filedelete-success'          => "Diverket eo bet '''$1'''.",
'filedelete-success-old'      => "Diverket eo bet ar stumm '''[[Media:$1|$1]]''' eus an $2 da $3.",
'filedelete-nofile'           => "N'eus ket eus '''$1'''.",
'filedelete-nofile-old'       => "N'eus stumm diellaouet ebet eus '''$1''' gant an dezverkoù lakaet.",
'filedelete-otherreason'      => 'Abeg all/ouzhpenn :',
'filedelete-reason-otherlist' => 'Abeg all',
'filedelete-reason-dropdown'  => "*Abegoù diverkañ boas
** Gaou ouzh ar gwirioù perc'hennañ
** Restr zo anezhi dija",
'filedelete-edit-reasonlist'  => 'Kemmañ a ra an abegoù diverkañ',
'filedelete-maintenance'      => "Evit ar mare eo diweredekaet an diverkañ hag an assevel restroù, amzer d'ober un tamm trezalc'h.",

# MIME search
'mimesearch'         => 'Klask MIME',
'mimesearch-summary' => 'Aotren a ra ar bajenn-mañ ar silañ restroù evit ar seurt restroù MIME. Enmont : seurt/isseurt, evel <tt>skeudenn/jpeg</tt>.',
'mimetype'           => 'Seurt MIME :',
'download'           => 'pellgargañ',

# Unwatched pages
'unwatchedpages' => "Pajennoù n'int ket evezhiet",

# List redirects
'listredirects' => 'Roll an adkasoù',

# Unused templates
'unusedtemplates'     => 'Patromoù dizimplij',
'unusedtemplatestext' => 'Rollet eo amañ an holl bajennoù zo en esaouenn anv "{{ns:template}}" ha n\'int ket implijet war pajenn ebet. Ho pet soñj da wiriañ mat hag-eñ n\'eus ket liammoù all war-du ar patromoù-se a-raok diverkañ anezho.',
'unusedtemplateswlh'  => 'liammoù all',

# Random page
'randompage'         => 'Ur bajenn dre zegouezh',
'randompage-nopages' => 'N\'eus pajenn ebet en {{PLURAL:$2|esaouennn anv|esaouennoù anv}} da-heul : "$1".',

# Random redirect
'randomredirect'         => 'Ur bajenn adkas dre zegouezh',
'randomredirect-nopages' => 'N\'eus pajenn adkas ebet en esaouenn anv "$1".',

# Statistics
'statistics'                   => 'Stadegoù',
'statistics-header-pages'      => 'Stadegoù ar pajennoù',
'statistics-header-edits'      => "Stadegoù ar c'hemmoù",
'statistics-header-views'      => 'Stadegoù ar selladennoù',
'statistics-header-users'      => 'Stadegoù implijer',
'statistics-header-hooks'      => 'Stadegoù all',
'statistics-articles'          => "Pajennoù endalc'had",
'statistics-pages'             => 'Pajennoù',
'statistics-pages-desc'        => 'Holl bajennoù ar wiki, en o zouez ar pajennoù kaozeal, an adkasoù, h.a.',
'statistics-files'             => 'Restroù enporzhiet',
'statistics-edits'             => 'Kemmoù war ar pajennoù abaoe krouidigezh {{SITENAME}}',
'statistics-edits-average'     => "Keidenn ar c'hemmoù dre bajenn",
'statistics-views-total'       => 'Hollad ar selladennoù',
'statistics-views-peredit'     => 'Keidenn gweladenniñ dre gemmoù',
'statistics-users'             => '[[Special:ListUsers|Implijerien]] enrollet',
'statistics-users-active'      => 'Implijerien oberiant',
'statistics-users-active-desc' => "Implijerien o deus degaset da nebeutañ ur c'hemm {{PLURAL:$1|an deiz paseet|e-kerzh an $1 deiz diwezhañ}}",
'statistics-mostpopular'       => 'Pajennoù muiañ sellet',

'disambiguations'      => 'Pajennoù disheñvelout',
'disambiguationspage'  => 'Template:Disheñvelout',
'disambiguations-text' => "Liammet eo ar pajennoù da-heul ouzh ur '''bajenn disheñvelout'''.
Padal e tlefent kas war-eeun d'an danvez anezho.<br />
Sellet e vez ouzh ur bajenn evel ouzh ur bajenn disheñvelout ma ra gant ur patrom liammet ouzh [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Adkasoù doubl',
'doubleredirectstext'        => 'Rollañ a ra ar bajenn-mañ ar pajennoù a adkas da bajennoù adkas all.
War bep linenn ez eus liammoù war-du pajennoù an adkas kentañ hag en eil adkas, hag ivez war-du pajenn-dal an eil adkas zo sañset bezañ ar pal "gwirion" a zlefe an adkas kentañ kas di.
Diskoulmet eo bet an enmontoù <del>barrennet</del>.',
'double-redirect-fixed-move' => 'Adanvet eo bet [[$1]], adkaset eo war-du [[$2]] bremañ',
'double-redirect-fixer'      => 'Reizher adkasoù',

'brokenredirects'        => 'Adkasoù torret',
'brokenredirectstext'    => "Kas a ra an adkasoù-mañ da bajennoù n'eus ket anezho.",
'brokenredirects-edit'   => 'kemmañ',
'brokenredirects-delete' => 'diverkañ',

'withoutinterwiki'         => 'Pajennoù hep liammoù yezh',
'withoutinterwiki-summary' => "Ar pajennoù da-heul n'int ket liammet ouzh yezh all ebet :",
'withoutinterwiki-legend'  => 'Rakger',
'withoutinterwiki-submit'  => 'Diskouez',

'fewestrevisions' => 'Pennadoù reizhet an nebeutañ',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|eizhbit|eizhbit}}',
'ncategories'             => '
$1 {{PLURAL:$1|rummad|rummad}}',
'nlinks'                  => '$1 {{PLURAL:$1|liamm|liamm}}',
'nmembers'                => '$1 {{PLURAL:$1|elfenn|elfenn}}',
'nrevisions'              => '$1 {{PLURAL:$1|stumm|stumm}}',
'nviews'                  => '$1 {{PLURAL:$1|selladenn|selladenn}}',
'specialpage-empty'       => 'Goullo eo ar bajenn-mañ.',
'lonelypages'             => 'Pajennoù hep liamm daveto',
'lonelypagestext'         => "N'eo ket liammet pe enframmet ar pajennoù da-heul ouzh pajenn all ebet eus {{SITENAME}}.",
'uncategorizedpages'      => 'Pajennoù dirumm',
'uncategorizedcategories' => 'Rummadoù dirumm',
'uncategorizedimages'     => 'Restroù hep rummad',
'uncategorizedtemplates'  => 'Patromoù hep rummad',
'unusedcategories'        => 'Rummadoù dizimplij',
'unusedimages'            => 'Skeudennoù en o-unan',
'popularpages'            => 'Pajennoù sellet ar muiañ',
'wantedcategories'        => 'Rummadoù a vank',
'wantedpages'             => 'Pajennoù goulennet ar muiañ',
'wantedpages-badtitle'    => "Titl direizh er strollad disoc'hoù : $1",
'wantedfiles'             => 'Restroù a vank',
'wantedtemplates'         => 'Patromoù a vank',
'mostlinked'              => 'Pajennoù dezho al liammoù niverusañ',
'mostlinkedcategories'    => 'Rummadoù dezho al liammoù niverusañ',
'mostlinkedtemplates'     => 'Patromoù implijet ar muiañ',
'mostcategories'          => 'Pennadoù rummatet ar muiañ',
'mostimages'              => 'Skeudennoù implijet ar muiañ',
'mostrevisions'           => 'Pennadoù bet kemmet ar muiañ',
'prefixindex'             => 'An holl bajennoù a grog gant...',
'shortpages'              => 'Pennadoù berr',
'longpages'               => 'Pennadoù hir',
'deadendpages'            => 'Pajennoù dall (hep liamm diabarzh)',
'deadendpagestext'        => "Ar pajennoù da-heul n'int ket liammet ouzh pajenn ebet all eus {{SITENAME}}.",
'protectedpages'          => 'Pajennoù gwarezet',
'protectedpages-indef'    => 'Gwarezoù da badout hepken',
'protectedpages-cascade'  => 'Gwarez dre skalierad hepken',
'protectedpagestext'      => "Gwarezet eo ar pajennoù da-heul; n'haller na degas kemmoù enno nag o dilec'hiañ",
'protectedpagesempty'     => "N'eus pajenn gwarezet ebet gant an arventennoù-mañ evit poent.",
'protectedtitles'         => 'Titloù gwarezet',
'protectedtitlestext'     => "An titloù da-heul zo bet gwarezet p'int bet krouet",
'protectedtitlesempty'    => "N'eus bet gwarezet titl ebet dezhañ an arventennoù-se evit poent.",
'listusers'               => 'Roll an implijerien',
'listusers-editsonly'     => 'Diskouez an implijerien o deus degaset kemmoù hepken',
'listusers-creationsort'  => 'Renket dre urzh krouiñ',
'usereditcount'           => '$1 {{PLURAL:$1|kemm|kemm}}',
'usercreated'             => "Krouet d'an $1 da $2",
'newpages'                => 'Pajennoù nevez',
'newpages-username'       => 'Anv implijer :',
'ancientpages'            => 'Pennadoù koshañ',
'move'                    => 'adenvel',
'movethispage'            => 'Adenvel ar bajenn',
'unusedimagestext'        => "Ar restroù da-heul zo anezho e gwirionez met n'int ket enframmet e pajenn ebet.
Na zisoñjit ket e c'hall lec'hiennoù all kaout ul liamm eeun war-du ur restr bennak hag e c'hall neuze ar restr-se bezañ rollet amañ c'hoazh daoust dezhi bezañ implijet e lec'hiennoù all.",
'unusedcategoriestext'    => "Krouet eo bet ar rummadoù-mañ met n'int ket bet implijet e pennad pe rummad ebet.",
'notargettitle'           => 'netra da gavout',
'notargettext'            => 'Merkit anv ur bajenn da gavout pe hini un implijer.',
'nopagetitle'             => 'Pajenn dal ebet a seurt-se',
'nopagetext'              => "N'eus ket eus ar bajenn dal merket ganeoc'h.",
'pager-newer-n'           => "{{PLURAL:$1|1 nevesoc'h|$1 nevesoc'h}}",
'pager-older-n'           => "{{PLURAL:$1|1 koshoc'h|$1 koshoc'h}}",
'suppress'                => 'Dindan evezh',

# Book sources
'booksources'               => 'Oberennoù dave',
'booksources-search-legend' => 'Klask en oberennoù dave',
'booksources-isbn'          => 'ISBN :',
'booksources-go'            => 'Kadarnaat',
'booksources-text'          => "Ur roll liammoù a gas da lec'hiennoù all ma werzher levrioù kozh ha nevez a gavot a-is; marteze e kavot eno titouroù pelloc'h war al levrioù a glaskit :",
'booksources-invalid-isbn'  => "Evit doare n'eo ket reizh an ISBN merket; gwiriit ha n'oc'h ket faziet en ur eilañ adal ar vammenn orin.",

# Special:Log
'specialloguserlabel'  => 'Implijer :',
'speciallogtitlelabel' => 'Titl :',
'log'                  => 'Marilhoù',
'all-logs-page'        => 'An holl varilhoù foran',
'alllogstext'          => "Diskwel a-gevret an holl varilhoù hegerz war {{SITENAME}}.
Gallout a rit strishaat ar mod diskwel en ur zibab ar marilh, an anv implijer (diwallit ouzh ar pennlizherennoù) pe ar bajenn a fell deoc'h (memes tra).",
'logempty'             => 'Goullo eo istor ar bajenn-mañ.',
'log-title-wildcard'   => 'Klask an titloù a grog gant an destenn-mañ',

# Special:AllPages
'allpages'          => 'An holl bajennoù',
'alphaindexline'    => '$1 da $2',
'nextpage'          => "Pajenn war-lerc'h ($1)",
'prevpage'          => 'Pajenn gent ($1)',
'allpagesfrom'      => 'Diskouez ar pajennoù adal :',
'allpagesto'        => 'Diskouez ar pajennoù betek :',
'allarticles'       => 'An holl bennadoù',
'allinnamespace'    => 'An holl bajennoù (esaouenn $1)',
'allnotinnamespace' => "An holl bajennoù (ar re n'emaint ket en esaouenn anv $1)",
'allpagesprev'      => 'Kent',
'allpagesnext'      => "War-lerc'h",
'allpagessubmit'    => 'Kadarnaat',
'allpagesprefix'    => 'Diskouez ar pajennoù a grog gant :',
'allpagesbadtitle'  => "Fall e oa anv ar bajenn lakaet pe neuze ez eus ennañ ur rakger etrewiki pe etreyezhoù. Evit doare ez arouezennoù n'haller ket implijout en titloù.",
'allpages-bad-ns'   => 'N\'eus ket a esaouenn anv anvet "$1" war {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Roll ar rummadoù',
'categoriespagetext'            => 'Er {{PLURAL:$1|rummad|rummadoù}}da-heul ez eus pajennoù pe restroù media.
Ne ziskouezer ket amañ ar [[Special:UnusedCategories|Rummadoù dizimplij]].
Gwelet ivez ar [[Special:WantedCategories|rummadoù a vank]].',
'categoriesfrom'                => 'Diskouez ar rummadoù en ur gregiñ gant :',
'special-categories-sort-count' => 'Urzhiañ dre gont',
'special-categories-sort-abc'   => 'urzh al lizherenneg',

# Special:DeletedContributions
'deletedcontributions'             => 'Degasadennoù diverket un implijer',
'deletedcontributions-title'       => 'Degasadennoù diverket un implijer',
'sp-deletedcontributions-contribs' => 'Degasadennoù',

# Special:LinkSearch
'linksearch'       => 'Liammoù diavaez',
'linksearch-pat'   => 'Klask an droienn :',
'linksearch-ns'    => 'Esaouenn anv :',
'linksearch-ok'    => 'Klask',
'linksearch-text'  => 'Gallout a reer implijout arouezennoù "joker" evel, da skouer, "*.wikipedia.org".<br />
Protokoloù skoret : <tt>$1</tt>',
'linksearch-line'  => '$1 gant ul liamm adal $2',
'linksearch-error' => "N'hall an arouezennoù joker bezañ implijet nemet e deroù anv domani an ostiz.",

# Special:ListUsers
'listusersfrom'      => 'Diskouez anv an implijerien adal :',
'listusers-submit'   => 'Diskouez',
'listusers-noresult' => "N'eus bet kavet implijer ebet.",
'listusers-blocked'  => '(stanket)',

# Special:ActiveUsers
'activeusers'            => 'Roll an implijerien oberiant',
'activeusers-intro'      => 'Setu aze ur roll eus an implijerien zo bet oberiant mui pe vui e-pad an $1 {{PLURAL:$1|deiz|deiz}} diwezhañ.',
'activeusers-count'      => '$1 {{PLURAL:$1|degasadenn}} abaoe an {{PLURAL:$3|deiz}} diwezhañ',
'activeusers-from'       => 'Diskouez an implijerien adal :',
'activeusers-hidebots'   => 'Kuzhat ar robotoù',
'activeusers-hidesysops' => 'Kuzhat ar verourien',
'activeusers-noresult'   => "N'eus bet kavet implijer ebet.",

# Special:Log/newusers
'newuserlogpage'              => "Marilh ar c'hontoù krouet",
'newuserlogpagetext'          => "Marilh krouiñ ar c'hontoù implijer.",
'newuserlog-byemail'          => 'ger-tremen kaset dre bostel',
'newuserlog-create-entry'     => 'Implijer nevez',
'newuserlog-create2-entry'    => 'krouet ar gont nevez $1',
'newuserlog-autocreate-entry' => 'Kont krouet ent emgefre',

# Special:ListGroupRights
'listgrouprights'                      => 'Gwirioù ar strolladoù implijer',
'listgrouprights-summary'              => 'Da-heul ez eus ur roll eus ar strolladoù implijerien termenet war ar wiki-mañ, gant ar gwirioù moned stag outo.
Gallout a ra bezañ [[{{MediaWiki:Listgrouprights-helppage}}|titouroù ouzhpenn]] diwar-benn ar gwirioù hiniennel.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Gwirioù grataet</span>
* <span class="listgrouprights-revoked">Gwirioù lamet</span>',
'listgrouprights-group'                => 'Strollad',
'listgrouprights-rights'               => 'Gwirioù',
'listgrouprights-helppage'             => 'Help:Gwirioù ar strolladoù',
'listgrouprights-members'              => '(roll an izili)',
'listgrouprights-addgroup'             => 'Gallout a reer ouzhpennañ {{PLURAL:$2|ur strollad|strolladoù}}: $1',
'listgrouprights-removegroup'          => 'Gallout a reer dilemel {{PLURAL:$2|ar strollad|ar strolladoù}}: $1',
'listgrouprights-addgroup-all'         => 'Gallout a reer ouzhpennañ an holl strolladoù',
'listgrouprights-removegroup-all'      => 'Gallout a reer dilemel an holl strolladoù',
'listgrouprights-addgroup-self'        => 'Gallout a ra ouzhpennañ {{PLURAL:$2|ar strollad|ar strolladoù}} da gont an-unan : $1',
'listgrouprights-removegroup-self'     => 'Gallout a ra tennañ {{PLURAL:$2|ar strollad|strolladoù}} eus kont an-unan : $1',
'listgrouprights-addgroup-self-all'    => 'Gallout a ra ouzhpennañ an holl strolladoù da gont an-unan',
'listgrouprights-removegroup-self-all' => 'Gallout a ra tennañ kuit an holl strolladoù eus kont an-unan.',

# E-mail user
'mailnologin'          => "Chomlec'h ebet",
'mailnologintext'      => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreet]]
ha bezañ merket ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù]]
evit gallout kas ur postel d'un implijer all.",
'emailuser'            => "Kas ur postel d'an implijer-mañ",
'emailpage'            => 'Postel implijer',
'emailpagetext'        => "Gallout a rit ober gant ar furmskrid a-is a-benn kas ur postel d'an implijer-mañ.
E maezienn \"Kaser\" ho postel e vo merket ar chomlec'h postel resisaet ganeoc'h-c'hwi en ho [[Special:Preferences|Penndibaboù]], d'ar resever da c'hallout respont deoc'h war-eeun ma kar.",
'usermailererror'      => 'Fazi postel :',
'defemailsubject'      => 'postel kaset eus {{SITENAME}}',
'usermaildisabled'     => "Diweredekaet eo ar c'has posteloù etre an implijerien.",
'usermaildisabledtext' => "Ne c'helloc'h ket kas posteloù da implijerien all er wiki-mañ",
'noemailtitle'         => "Chomlec'h postel ebet",
'noemailtext'          => "N'en deus ket an implijer-mañ resisaet chomlec'h postel reizh ebet.",
'nowikiemailtitle'     => 'Berzet kas posteloù',
'nowikiemailtext'      => 'Dibabet ez eus bet gant an implijerien-mañ chom hep resev posteloù a-berzh implijerien all.',
'email-legend'         => "Kas ur postel d'un implijer all eus {{SITENAME}}",
'emailfrom'            => 'Kaser :',
'emailto'              => 'Resever :',
'emailsubject'         => 'Danvez :',
'emailmessage'         => 'Postel :',
'emailsend'            => 'Kas',
'emailccme'            => "Kas din un eilskrid eus ma c'hemennadenn dre bostel.",
'emailccsubject'       => 'Eilenn eus ho kemennadenn da $1: $2',
'emailsent'            => 'Postel kaset',
'emailsenttext'        => 'Kaset eo bet ho postel.',
'emailuserfooter'      => 'Kaset eo bet ar postel-mañ gant $1 da $2 dre an arc\'hwel "Kas ur postel d\'an implijer" war {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'En deus laosket ur gemennadenn sistem.',
'usermessage-editor'  => 'Kemennerezh ar reizhiad',

# Watchlist
'watchlist'            => 'Roll evezhiañ',
'mywatchlist'          => 'Ma roll evezhiañ',
'watchlistfor'         => "(evit '''$1''')",
'nowatchlist'          => "N'eus pennad ebet en ho roll evezhiañ.",
'watchlistanontext'    => "Ret eo deoc'h $1 evit gwelet pe kemmañ an elfennoù zo en ho roll evezhiañ.",
'watchnologin'         => 'Digevreet',
'watchnologintext'     => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreet]]
a-benn gellout kemmañ ho roll evezhiañ.",
'addedwatch'           => "Ouzhpennet d'ar roll",
'addedwatchtext'       => 'Ouzh ho [[Special:Watchlist|rollad evezhiañ]] eo bet ouzhpennet ar bajenn "[[:$1]]".
Kemmoù da zont ar bajenn-mañ ha re ar bajenn gaozeal stag outi a vo rollet amañ hag e teuio ar bajenn <b>e tev</b> er [[Special:RecentChanges|roll kemmoù diwezhañ]] evit bezañ gwelet aesoc\'h ganeoc\'h.

Evit tennañ ar bajenn-mañ a-ziwar ho rollad evezhiañ. klikit war "Paouez da evezhiañ" er framm merdeiñ.',
'removedwatch'         => 'Lamet a-ziwar ar rollad evezhiañ',
'removedwatchtext'     => 'Lamet eo bet ar bajenn "[[:$1]]" a-ziwar ho [[Special:Watchlist|roll evezhiañ]].',
'watch'                => 'Evezhiañ',
'watchthispage'        => 'Evezhiañ ar bajenn-mañ',
'unwatch'              => 'paouez da evezhiañ',
'unwatchthispage'      => 'Paouez da evezhiañ',
'notanarticle'         => 'Pennad ebet',
'notvisiblerev'        => 'Stumm diverket',
'watchnochange'        => "Pajenn ebet eus ar re evezhiet ganeoc'h n'eo bet kemmet e-pad ar prantad spisaet",
'watchlist-details'    => "Lakaet hoc'h eus {{PLURAL:$1|$1 bajenn|$1 pajenn}} dindan evezh, anez kontañ ar pajennoù kaozeal.",
'wlheader-enotif'      => "* War enaou emañ ar c'has posteloù.",
'wlheader-showupdated' => "* E '''tev''' emañ merket ar pajennoù bet degaset kemmoù enno abaoe ar wech ziwezhañ hoc'h eus sellet outo",
'watchmethod-recent'   => "Gwiriañ ar c'hemmoù diwezhañ er pajennoù dindan evezh",
'watchmethod-list'     => "Gwiriañ ar c'hemmoù diwezhañ evit ar pajennoù evezhiet",
'watchlistcontains'    => '$1 {{PLURAL:$1|pajenn|pajenn}} zo en ho rollad evezhiañ',
'iteminvalidname'      => "Ur gudenn zo gant ar pennad « $1 » : n'eo ket mat e anv...",
'wlnote'               => "Setu aze {{PLURAL:$1|ar c'hemm diwezhañ|ar '''$1''' kemm diwezhañ}} e-kerzh an {{PLURAL:$2|eurvezh|'''$2''' eurvezh}} ziwezhañ.",
'wlshowlast'           => 'Diskouez an $1 eurvezh $2 devezh diwezhañ $3',
'watchlist-options'    => 'Dibarzhioù ar roll evezhiañ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Heuliet...',
'unwatching' => 'Paouez da evezhiañ...',

'enotif_mailer'                => 'Posteler Kemenn {{SITENAME}}',
'enotif_reset'                 => 'Merkañ an holl bajennoù evel gwelet',
'enotif_newpagetext'           => 'Ur bajenn nevez eo homañ.',
'enotif_impersonal_salutation' => 'implijer {{SITENAME}}',
'changed'                      => 'kemmet',
'created'                      => 'Krouet',
'enotif_subject'               => '$CHANGEDORCREATED eo bet pajenn $PAGETITLE {{SITENAME}} gant $PAGEEDITOR',
'enotif_lastvisited'           => 'Sellet ouzh $1 evit gwelet an holl gemmoù abaoe ho selladenn ziwezhañ.',
'enotif_lastdiff'              => "Gwelet $1 evit sellet ouzh ar c'hemm-mañ.",
'enotif_anon_editor'           => 'implijer dizanv $1',
'enotif_body'                  => '$WATCHINGUSERNAME ker,

$CHANGEDORCREATED eo bet ar bajenn {{SITENAME}} evit $PAGETITLE gant $PAGEEDITOR d\'an $PAGEEDITDATE, gwelet $PAGETITLE_URL evit gwelet ar stumm red.

$NEWPAGE

Diverrañ an aozer : $PAGESUMMARY $PAGEMINOREDIT

Mont e darempred gant an aozer :
postel: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nemet e yafec\'h da welet ar bajenn end-eeun, ne vo kemenn all ebet ma vez degaset kemmoù enni pelloc\'h.
Gallout a rit nevesaat doare ar pennadoù evezhiet ganeoc\'h en ho rollad evezhiañ ivez.

            Ho reizhiad kemenn {{SITENAME}} muiañ karet

--
A-benn kemmañ doare ho rollad evezhiañ, sellit ouzh
{{fullurl:{{#special:Watchlist}}/edit}}

A-benn dilemel ar bajenn eus ho rollad evezhiañ, sellit ouzh
$UNWATCHURL

Evezhiadennoù ha skoazell pelloc\'h :
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Diverkañ ur bajenn',
'confirm'                => 'Kadarnaat',
'excontent'              => "endalc'had '$1'",
'excontentauthor'        => "an danvez a oa : '$1' (ha '[[Special:Contributions/$2|$2]]' a oa bet an implijer nemetañ)",
'exbeforeblank'          => "A-raok diverkañ e oa an endalc'had : '$1'",
'exblank'                => "pajenn c'houllo",
'delete-confirm'         => 'Diverkañ "$1"',
'delete-legend'          => 'Diverkañ',
'historywarning'         => "'''Diwallit :''' Emaoc'h war-nes diverkañ ur bajenn dezhi un istor gant e-tro {{PLURAL:$1|adweladenn|adweladenn}} :",
'confirmdeletetext'      => "War-nes diverkañ da viken ur bajenn pe ur skeudenn eus ar bank roadennoù emaoc'h. Diverket e vo ivez an holl stummoù kozh stag outi.
Kadarnait, mar plij, eo mat an dra-se hoc'h eus c'hoant da ober, e komprenit mat an heuliadoù, hag e rit se diouzh ar [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'         => 'Diverkadenn kaset da benn',
'actionfailed'           => "Ober c'hwitet",
'deletedtext'            => '"Diverket eo bet <nowiki>$1</nowiki>".
Sellet ouzh $2 evit roll an diverkadennoù diwezhañ.',
'deletedarticle'         => 'diverket "$1"',
'suppressedarticle'      => 'diverket "[[$1]]"',
'dellogpage'             => 'Roll ar pajennoù diverket',
'dellogpagetext'         => 'Setu roll ar pajennnoù diwezhañ bet diverket.',
'deletionlog'            => 'roll an diverkadennoù',
'reverted'               => 'Adlakaat ar stumm kent',
'deletecomment'          => 'Abeg :',
'deleteotherreason'      => 'Abegoù/traoù all :',
'deletereasonotherlist'  => 'Abeg all',
'deletereason-dropdown'  => "*Abegoù diverkañ boazetañ
** Goulenn gant saver ar pennad
** Gaou ouzh ar gwirioù perc'hennañ
** Vandalerezh",
'delete-edit-reasonlist' => 'Kemmañ a ra an abegoù diverkañ',
'delete-toobig'          => 'Bras eo istor ar bajenn-mañ, ouzhpenn $1 {{PLURAL:$1|stumm|stumm}} zo. Bevennet eo bet an diverkañ pajennoù a-seurt-se kuit da zegas reuz war {{SITENAME}} dre fazi .',
'delete-warning-toobig'  => "Bras eo istor ar bajenn-mañ, ouzhpenn {{PLURAL:$1|stumm|stumm}} zo.
Diverkañ anezhi a c'hallo degas reuz war mont en-dro diaz titouroù {{SITENAME}};
taolit evezh bras.",

# Rollback
'rollback'          => "disteuler ar c'hemmoù",
'rollback_short'    => 'Disteuler',
'rollbacklink'      => 'disteuler',
'rollbackfailed'    => "C'hwitet eo bet an distaoladenn",
'cantrollback'      => 'Dibosupl da zisteuler: an aozer diwezhañ eo an hini nemetañ da vezañ kemmet ar pennad-mañ',
'alreadyrolled'     => "Dibosupl eo disteuler ar c'hemm diwezhañ graet d'ar bajenn [[:$1]] gant [[User:$2|$2]] ([[User talk:$2|Kaozeal]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
kemmet pe distaolet eo bet c'hoazh gant unan bennak all.

Ar c'hemm diwezhañ d'ar bajenn-mañ a oa bet graet gant [[User:$3|$3]] ([[User talk:$3|Kaozeal]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'       => "Diverradenn ar c'hemm a oa : \"''\$1''\".",
'revertpage'        => "Kemmoù distaolet gant [[Special:Contributions/$2|$2]] ([[User talk:$2|Kaozeal]]); adlakaet d'ar stumm diwezhañ a-gent gant [[User:$1|$1]]",
'revertpage-nouser' => "Disteuler kemmoù (anv implijer distaolet) ha distreiñ d'ar stumm diwezhañ gant [[User:$1|$1]]",
'rollback-success'  => 'Disteuler kemmoù $1; distreiñ da stumm diwezhañ $2.',

# Edit tokens
'sessionfailure-title' => "Fazi dalc'h",
'sessionfailure'       => 'Evit doare ez eus ur gudenn gant ho talc\'h;
Nullet eo bet an ober-mañ a-benn en em wareziñ diouzh an tagadennoù preizhañ.
Klikit war "kent" hag adkargit ar bajenn oc\'h deuet drezi; goude klaskit en-dro.',

# Protect
'protectlogpage'              => 'Log_gwareziñ',
'protectlogtext'              => 'Kavit a-is ur roll pajennoù gwarezet ha diwarezet. Sellet ouzh ar [[Special:ProtectedPages|roll ar pajennoù gwarezet]] evit kaout roll ar pajennoù gwarezet bremañ.',
'protectedarticle'            => '{{Gender:.|en|he}} deus gwarezet [[$1]]',
'modifiedarticleprotection'   => 'Kemmañ live gwareziñ "[[$1]]"',
'unprotectedarticle'          => '{{Gender:.|en|he}} deus diwarezet [[$1]]',
'movedarticleprotection'      => 'en deus adkaset an arventennoù gwareziñ eus "[[$2]]" da "[[$1]]"',
'protect-title'               => 'Kemmañ al live gwareziñ evit "$1"',
'prot_1movedto2'              => '[[$1]] adkaset war-du [[$2]]',
'protect-legend'              => 'Kadarnaat ar gwareziñ',
'protectcomment'              => 'Abeg :',
'protectexpiry'               => 'Termen',
'protect_expiry_invalid'      => 'Direizh eo termen ar prantad.',
'protect_expiry_old'          => 'Echuet eo ar prantad termen.',
'protect-unchain-permissions' => "Dibrennañ muioc'h a zibarzhioù gwareziñ",
'protect-text'                => "Amañ e c'hallit gwelet ha cheñch live gwareziñ ar bajenn '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "E-keit ha ma viot stanket ne viot ket evit cheñch al live gwareziñ. Setu aze arventennoù a-vremañ ar bajenn '''$1''':",
'protect-locked-dblock'       => "N'haller ket cheñch al liveoù gwareziñ rak prennet eo an diaz titouroù.
Setu doare a-vremañ ar bajenn '''$1''' :",
'protect-locked-access'       => "N'eo ket aotreet ho kont da zegas kemmoù e live gwareziñ ur bajenn.
Setu an doare a-vremañ evit ar bajenn-mañ '''$1''':",
'protect-cascadeon'           => "Gwarezet eo ar bajenn-mañ peogwir he c'haver er {{PLURAL:$1|bajenn|pajennoù}} da-heul zo gweredekaet enno ar gwareziñ dre skalierad. Gallout a rit kemmañ al live gwareziñ met ne cheñcho ket ar gwareziñ dre skalierad.",
'protect-default'             => 'Aotren an holl implijerien',
'protect-fallback'            => 'Ezhomm zo aotre "$1"',
'protect-level-autoconfirmed' => "Stankañ an implijerien nevez hag ar re n'int ket enrollet",
'protect-level-sysop'         => 'Merourien hepken',
'protect-summary-cascade'     => 'Gwareziñ dre skalierad',
'protect-expiring'            => "a zeu d'e dermen d'an $1",
'protect-expiry-indefinite'   => 'da viken',
'protect-cascade'             => 'Gwareziñ dre skalierad - gwareziñ a ra an holl bajennoù zo er bajenn-mañ. ARABAT IMPLIJOUT.',
'protect-cantedit'            => "N'oc'h ket evit cheñch live gwareziñ ar bajenn-mañ rak n'oc'h ket aotreet da zegas kemmoù enni.",
'protect-othertime'           => 'Termen all :',
'protect-othertime-op'        => 'termen all',
'protect-existing-expiry'     => 'Termen echuiñ merket : $3, $2',
'protect-otherreason'         => 'Abeg all/ouzhpenn :',
'protect-otherreason-op'      => 'Abeg all',
'protect-dropdown'            => '*Abegoù gwareziñ boutin
** Vandalerezh betek re
** Stroberezh betek re
** Tabutoù toull war kemmoù zo
** Pajenn kemmet alies-tre',
'protect-edit-reasonlist'     => 'Kemmañ abegoù ar gwareziñ',
'protect-expiry-options'      => '1 eurvezh:1 hour,1 deiz:1 day,1 sizhun:1 week,2 sizhun:2 weeks,1 miz:1 month,3 miz:3 months,6 miz:6 months,1 bloaz:1 year,da viken:infinite',
'restriction-type'            => 'Aotre',
'restriction-level'           => 'Live strishaat :',
'minimum-size'                => 'Ment vihanañ',
'maximum-size'                => 'Ment vrasañ:',
'pagesize'                    => '(okted)',

# Restrictions (nouns)
'restriction-edit'   => 'Kemmañ',
'restriction-move'   => 'Adenvel',
'restriction-create' => 'Krouiñ',
'restriction-upload' => 'Enporzhiañ',

# Restriction levels
'restriction-level-sysop'         => 'Gwarez klok',
'restriction-level-autoconfirmed' => 'Gwarez darnel',
'restriction-level-all'           => 'ne vern pe live',

# Undelete
'undelete'                     => 'Diziverkañ ar bajenn ziverket',
'undeletepage'                 => 'Gwelet ha diziverkañ ar bajenn ziverket',
'undeletepagetitle'            => "'''Mont a ra stummoù diverket eus [[:$1]] d'ober ar roll da-heul'''.",
'viewdeletedpage'              => 'Gwelet ar pajennoù diverket',
'undeletepagetext'             => "Diverket eo bet {{PLURAL:$1|ar bajenn da-heul; emañ|ar pajennoù da-heul; emaint}} e bank roadennoù an dielloù, ma c'hallont bezañ assavet.
Ingal e c'hall an diell bezañ goullonderet.",
'undelete-fieldset-title'      => 'Assevel ar stummoù',
'undeleteextrahelp'            => "Evit diziverkañ ar bajenn en he fezh, laoskit goullo an holl logoù bihan ha klikit war '''''Diziverkañ!'''''.
Evit diziverkañ stummoù zo hepken, askit ar logoù bihan a glot gant ar stummoù da vezañ adsavet, ha klikit war '''''Diziverkañ!'''''.
Mar klikit war '''''Adderaouiñ''''' e vo naetaet ar stern diverrañ hag al logoù asket.",
'undeleterevisions'            => '$1 {{PLURAL:$1|stumm|stumm}} diellaouet',
'undeletehistory'              => "Ma tiziverkit ar bajenn e vo erlec'hiet an holl gemmoù bet degaset enni er roll istor.

Ma'z eus bet krouet ur bajenn nevez dezhi an hevelep anv abaoe an diverkadenn, e teuio war wel ar c'hemmoù assavet er roll istor kent ha ne vo ket erlec'hiet ar stumm red en un doare emgefre ken.",
'undeleterevdel'               => 'Ne vo ket adsavet ar stumm-se eus ar bajenn ma talvez kement ha diverkañ evit darn an doare diwezhañ anezhi. En degouezh-mañ e rankit diaskañ pe diguzhat ar stummoù diverket da ziwezhañ.',
'undeletehistorynoadmin'       => "Diverket eo bet ar pennad-mañ. Displeget eo perak en diverradenn a-is, war un dro gant munudoù an implijerien o deus bet degaset kemmoù er bajenn a-raok na vije diverket. N'eus nemet ar verourien a c'hall tapout krog war an destenn bet diverket.",
'undelete-revision'            => 'Stumm diverket eus $1, (gwiriadenn eus $4 da $5) gant $3 :',
'undeleterevision-missing'     => "Stumm fall pe diank. Pe hoc'h eus heuliet ul liamm fall, pe eo bet diziverket ar stumm pe c'hoazh eo bet lamet diouzh an dielloù.",
'undelete-nodiff'              => "N'eus bet kavet stumm kent ebet.",
'undeletebtn'                  => 'Diziverkañ!',
'undeletelink'                 => 'gwelet/assevel',
'undeleteviewlink'             => 'gwelet',
'undeletereset'                => 'Adderaouiñ',
'undeleteinvert'               => 'Eilpennañ diuzadenn',
'undeletecomment'              => 'Abeg :',
'undeletedarticle'             => 'Diziverket"$1"',
'undeletedrevisions'           => 'Adsavet {{PLURAL:$1|1 stumm|$1 stumm}}',
'undeletedrevisions-files'     => 'Adsavet ez ez eus bet {{PLURAL:$1|1 stumm|$1 stumm}} ha {{PLURAL:$2|1 restr|$2 restr}}',
'undeletedfiles'               => '{{PLURAL:$1|1 restr|$1 restr}} adsavet',
'cannotundelete'               => "Dibosupl eo diziverkañ; moarvat eo bet diziverket gant unan bennak all araozoc'h.",
'undeletedpage'                => "'''Diziverket eo bet $1'''

Sellit ouzh [[Special:Log/delete|marilh an diverkadennoù]] evit teuler ur sell ouzh an diverkadennoù diwezhañ.",
'undelete-header'              => 'Gwelet [[Special:Log/delete|al log diverkañ]] evit ar pajennoù diverket nevez zo.',
'undelete-search-box'          => 'Klask pajennoù diverket',
'undelete-search-prefix'       => 'Diskouez ar pajennoù a grog gant :',
'undelete-search-submit'       => 'Klask',
'undelete-no-results'          => "N'eus bet kavet pajenn ebet a glotje e dielloù an diverkadennoù.",
'undelete-filename-mismatch'   => "Dibosupl diziverkañ stumm ar restr d'ar mare $1: ne glot ket anv ar restr",
'undelete-bad-store-key'       => "Dibosupl diziverkañ stumm ar restr d'ar mare $1: ezvezant e oa ar restr a-raok an diverkadenn.",
'undelete-cleanup-error'       => 'Fazi en ur ziverkañ ar restr diellaouet dizimplij "$1".',
'undelete-missing-filearchive' => "Dibosupl adsevel ID diell ar restr $1 rak n'emañ ket er bank ditouroù. Diziverket eo bet c'hoazh, marteze a-walc'h.",
'undelete-error-short'         => 'Fazi e-ser diziverkañ ar restr : $1',
'undelete-error-long'          => 'Fazioù zo bet kavet e-ser diziverkañ ar restr :

$1',
'undelete-show-file-confirm'   => 'Ha sur oc\'h e fell deoc\'h sellet ouzh ur stumm diverket eus ar restr "<nowiki>$1</nowiki>" a sav d\'an $2 da $3?',
'undelete-show-file-submit'    => 'Ya',

# Namespace form on various pages
'namespace'      => 'Esaouenn anv :',
'invert'         => 'Eilpennañ an dibab',
'blanknamespace' => '(Pennañ)',

# Contributions
'contributions'       => 'Degasadennoù an implijer',
'contributions-title' => 'Degasadennoù an implijer evit $1',
'mycontris'           => 'Ma degasadennnoù',
'contribsub2'         => 'Evit $1 ($2)',
'nocontribs'          => "N'eus bet kavet kemm ebet o klotañ gant an dezverkoù-se.",
'uctop'               => ' (diwezhañ)',
'month'               => 'Abaoe miz (hag a-raok) :',
'year'                => 'Abaoe bloaz (hag a-raok) :',

'sp-contributions-newbies'             => "Diskouez hepken degasadennoù ar c'hontoù nevez",
'sp-contributions-newbies-sub'         => 'Evit an implijerien nevez',
'sp-contributions-newbies-title'       => "Degasadennoù implijer evit ar c'hontoù nevez",
'sp-contributions-blocklog'            => 'Roll ar stankadennoù',
'sp-contributions-deleted'             => 'Degasadennoù diverket',
'sp-contributions-logs'                => 'marilhoù',
'sp-contributions-talk'                => 'kaozeal',
'sp-contributions-userrights'          => 'Merañ ar gwirioù',
'sp-contributions-blocked-notice'      => "Stanket eo an implijer-mañ evit poent. Dindan emañ merket moned diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'sp-contributions-blocked-notice-anon' => "Stanket eo ar chomlec'h IP-mañ evit ar mare.
Dindan emañ merket enmont diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'sp-contributions-search'              => 'Klask degasadennoù',
'sp-contributions-username'            => "Anv implijer pe chomlec'h IP :",
'sp-contributions-toponly'             => 'Na ziskouez nemet an adweladennoù diwezhañ',
'sp-contributions-submit'              => 'Klask',

# What links here
'whatlinkshere'            => 'Pajennoù liammet',
'whatlinkshere-title'      => 'Pajennoù liammet ouzh "$1"',
'whatlinkshere-page'       => 'Pajenn :',
'linkshere'                => "Ar pajennoù a-is zo enno ul liamm a gas war-du '''[[:$1]]''':",
'nolinkshere'              => "N'eus pajenn ebet enni ul liamm war-du '''[[:$1]]'''.",
'nolinkshere-ns'           => "Pajenn ebet n'eo liammet ouzh '''[[:$1]]''' en esaouenn anv dibabet.",
'isredirect'               => 'pajenn adkas',
'istemplate'               => 'enframmet',
'isimage'                  => 'liamm ar restr',
'whatlinkshere-prev'       => '{{PLURAL:$1|kent|kent $1}}',
'whatlinkshere-next'       => "{{PLURAL:$1|war-lerc'h|war-lerc'h $1}}",
'whatlinkshere-links'      => '← liammoù',
'whatlinkshere-hideredirs' => '$1 adkas',
'whatlinkshere-hidetrans'  => '$1 treuzkluzadur',
'whatlinkshere-hidelinks'  => '$1 liamm',
'whatlinkshere-hideimages' => '$1 liamm skeudennoù',
'whatlinkshere-filters'    => 'Siloù',

# Block/unblock
'blockip'                         => "Stankañ ur chomlec'h IP",
'blockip-title'                   => 'Stankañ an implijer',
'blockip-legend'                  => 'Stankañ an implijer',
'blockiptext'                     => "Grit gant ar furmskrid a-is evit stankañ ar moned skrivañ ouzh ur chomlec'h IP pe un implijer bennak.
Seurt diarbennoù n'hallont bezañ kemeret nemet evit mirout ouzh ar vandalerezh hag a-du gant an [[{{MediaWiki:Policy-url}}|erbedadennoù ha reolennoù da heuliañ]].
Roit a-is an abeg resis (o verkañ, da skouer, roll ar pajennoù bet graet gaou outo).",
'ipaddress'                       => "Chomlec'h IP",
'ipadressorusername'              => "Chomlec'h IP pe anv implijer",
'ipbexpiry'                       => 'Pad ar stankadenn',
'ipbreason'                       => 'Abeg :',
'ipbreasonotherlist'              => 'Abeg all',
'ipbreason-dropdown'              => "*Abegoù stankañ boutinañ
** Degas titouroù faos
** Tennañ danvez eus ar pajennoù
** Degas liammoù Strobus war-du lec'hiennoù diavaez
** Degas danvez diboell/dizoare er pajennoù
** Emzalc'h hegazus/handeus betek re
** Mont re bell gant implij meur a gont
** Anv implijer n'eo ket aotreet",
'ipbanononly'                     => 'Stankañ an implijerien dianav hepken',
'ipbcreateaccount'                => 'Mirout ouzh an implijer da grouiñ kontoù',
'ipbemailban'                     => 'Mirout ouzh an implijer da gas posteloù',
'ipbenableautoblock'              => "Stankañ war-eeun ar chomlec'h IP diwezhañ implijet gant an den-mañ hag an holl chomlec'hioù en deus klasket degas kemmoù drezo war-lerc'h",
'ipbsubmit'                       => "Stankañ ouzh ar chomlec'h-mañ",
'ipbother'                        => 'Prantad all',
'ipboptions'                      => '2 eurvezh:2 hours,1 devezh:1 day,3 devezh:3 days,1 sizhunvezh:1 week,2 sizhunvezh:2 weeks,1 mizvezh:1 month,3 mizvezh:3 months,6 mizvezh:6 months,1 bloaz:1 year,da viken:infinite',
'ipbotheroption'                  => 'prantad all',
'ipbotherreason'                  => 'Abeg all/ouzhpenn',
'ipbhidename'                     => "Kuzhat anv an implijer er rolloù hag er c'hemmoù",
'ipbwatchuser'                    => 'Evezhiañ pajennoù implijer ha kaozeal an implijer-mañ',
'ipballowusertalk'                => 'Aotren an implijer-mañ da zegas kemmoù war e bajenn gaozeal dezhañ e-unan pa vez stanket',
'ipb-change-block'                => 'Adstankañ an implijer-mañ gant an hevelep arventennoù',
'badipaddress'                    => "Kamm eo ar chomlec'h IP.",
'blockipsuccesssub'               => 'Stankadenn deuet da benn vat',
'blockipsuccesstext'              => 'Stanket eo bet chomlec\'h IP "$1".
<br />Gallout a rit sellet ouzh ar [[Special:IPBlockList|bajenn-mañ]] evit gwelet roll ar chomlec\'hioù IP stanket.

Stanket eo bet [[Special:Contributions/$1|$1]].<br />
Sellit ouzh [[Special:IPBlockList|roll ar chomlec\'hioù IP ha kontoù stanket]] evit gwiriañ ar stankadennoù.',
'ipb-edit-dropdown'               => 'Kemmañ an abegoù stankañ',
'ipb-unblock-addr'                => 'Distankañ $1',
'ipb-unblock'                     => "Distankañ un implijer pe ur chomlec'h IP",
'ipb-blocklist-addr'              => 'Stankadennoù zo evit $1',
'ipb-blocklist'                   => 'Teuler ur sell ouzh roll an dud stanket evit poent',
'ipb-blocklist-contribs'          => 'Degasadennoù evit $1',
'unblockip'                       => "Distankañ ur chomlec'h IP",
'unblockiptext'                   => "Grit gant ar furmskrid a-is evit adsevel ar moned skrivañ ouzh ur chomlec'h IP bet stanket a-gent.",
'ipusubmit'                       => 'Paouez gant ar stankadenn-mañ',
'unblocked'                       => 'Distanket eo bet [[User:$1|$1]]',
'unblocked-id'                    => 'Distanket eo bet $1',
'ipblocklist'                     => "Roll ar chomlec'hioù IP hag an anvioù kont stanket",
'ipblocklist-legend'              => 'Kavout un implijer stanket',
'ipblocklist-username'            => "Anv implijer pe chomlec'h IP :",
'ipblocklist-sh-userblocks'       => "$1 stankadennoù ar c'hontoù",
'ipblocklist-sh-tempblocks'       => '$1 ar stankadennoù dibad',
'ipblocklist-sh-addressblocks'    => "$1 stankadennoù ar chomlec'hioù IP unan",
'ipblocklist-submit'              => 'Klask',
'ipblocklist-localblock'          => "Stankadenn lec'hel",
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Stankadenn|Stankadennoù}} all',
'blocklistline'                   => '$1, $2 en/he deus stanket $3 ($4)',
'infiniteblock'                   => 'da viken',
'expiringblock'                   => "a zeu d'e dermen d'an $1 da $2",
'anononlyblock'                   => 'implijerien dizanv hepken',
'noautoblockblock'                => 'emstankañ diweredekaet',
'createaccountblock'              => "Harzet eo ar c'hrouiñ kontoù",
'emailblock'                      => 'Postel stanket',
'blocklist-nousertalk'            => "n'hall ket degas kemmoù war e bajenn gaozeal dezhañ e-unan",
'ipblocklist-empty'               => 'Goullo eo roll ar stankadennoù.',
'ipblocklist-no-results'          => "An anv implijer pe ar chomlec'h IP goulennet n'eo ket stanket anezhañ.",
'blocklink'                       => 'stankañ',
'unblocklink'                     => 'distankañ',
'change-blocklink'                => 'Kemmañ ar stankadenn',
'contribslink'                    => 'degasadennoù',
'autoblocker'                     => 'Emstanket rak implijet eo bet ho chomlec\'h IP gant "[[User:$1|$1]]" nevez zo.
Setu aze an abeg(où) m\'eo bet stanket $1 : "$2$',
'blocklogpage'                    => 'Roll ar stankadennoù',
'blocklog-showlog'                => "Stanket eo bet an implijer-mañ c'hoazh. A-is emañ marilh ar stankadennoù, d'ho titouriñ :",
'blocklog-showsuppresslog'        => "Stanket ha kuzhet eo bet an implijer-mañ c'hoazh. A-is emañ marilh ar diverkadennoù, d'ho titouriñ :",
'blocklogentry'                   => 'stanket "[[$1]]" $2 $3',
'reblock-logentry'                => "en deus kemmet arventennoù ar stankañ [[$1]] gant un termen d'an $2 $3",
'blocklogtext'                    => "Setu roud stankadennoù ha distankadennoù an implijerien. N'eo ket bet rollet ar chomlec'hioù IP bet stanket outo ent emgefre. Sellet ouzh [[Special:IPBlockList|roll an implijerien stanket]] evit gwelet piv zo stanket e gwirionez.",
'unblocklogentry'                 => 'distanket "$1"',
'block-log-flags-anononly'        => 'implijerien dizanv hepken',
'block-log-flags-nocreate'        => 'berzet eo krouiñ kontoù',
'block-log-flags-noautoblock'     => 'Emstankañ diweredekaet',
'block-log-flags-noemail'         => 'postel stanket',
'block-log-flags-nousertalk'      => "n'hall ket degas kemmoù war e bajenn gaozeal dezhañ e-unan",
'block-log-flags-angry-autoblock' => 'Emstankañ gwellaet gweredekaet',
'block-log-flags-hiddenname'      => 'anv implijer kuzhet',
'range_block_disabled'            => "Diweredekaet eo bet ar stankañ stuc'hadoù IP.",
'ipb_expiry_invalid'              => 'amzer termen direizh.',
'ipb_expiry_temp'                 => "Peurbadus e rank bezañ bloc'hadoù an implijerien guzh.",
'ipb_hide_invalid'                => 'Dibosupl diverkañ ar gont-mañ; evit doare ez eus bet graet re a gemmoù enni.',
'ipb_already_blocked'             => 'Stanket eo "$1" dija',
'ipb-needreblock'                 => "== Stanket dija ==
Stanket eo $1 c'hoazh. Kemmañ an arventennoù a fell deoc'h ?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Stankadenn|Stankadnenoù}} all',
'ipb_cant_unblock'                => "Fazi: N'eo ket stanket an ID $1. Moarvat eo bet distanket c'hoazh.",
'ipb_blocked_as_range'            => "Fazi : N'eo ket bet stanket ar chomlec'h IP $1 war-eeun, setu n'hall ket bezañ distanket. Stanket eo bet dre al live $2 avat, hag a c'hall bezañ distanket.",
'ip_range_invalid'                => 'Stankañ IP direizh.',
'ip_range_toolarge'               => "N'eo ket aotreet stankañ pajennoù brasoc'h evit /$1.",
'blockme'                         => "Stankit ac'hanon",
'proxyblocker'                    => 'Stanker proksi',
'proxyblocker-disabled'           => "Diweredekaet eo an arc'hwel-mañ.",
'proxyblockreason'                => "Stanket eo bet hoc'h IP rak ur proksi digor eo. Trugarez da gelaouiñ ho pourvezer moned ouzh ar Genrouedad pe ho skoazell deknikel eus ar gudenn surentez-mañ.",
'proxyblocksuccess'               => 'Echu.',
'sorbsreason'                     => "Rollet eo ho chomlec'h IP evel ur proksi digor en DNSBL implijet gant {{SITENAME}}.",
'sorbs_create_account_reason'     => "Rollet eo ho chomlec'h IP evel ur proksi digor war an DNSBL implijet gant {{SITENAME}}. N'hallit ket krouiñ ur gont",
'cant-block-while-blocked'        => "N'hallit ket stankañ implijerien all ma'z oc'h stanket c'hwi hoc'h-unan.",
'cant-see-hidden-user'            => "Stanket ha kuzhet eo bet dija an implijer emaoc'h o klask stankañ. Dre ma n'emañ ket ganeoc'h ar gwir hideuser, n'hallit ket gwelet pe kemmañ stankadenn an implijer.",
'ipbblocked'                      => "Ne c'hellit ket stankañ pe distankañ implijerien all, dre ma 'z oc'h stanket",
'ipbnounblockself'                => "N'oc'h ket aotreet d'en em zistankañ ho unan",

# Developer tools
'lockdb'              => 'Prennañ ar bank',
'unlockdb'            => 'Dibrennañ ar bank',
'lockdbtext'          => "Ma vez prennet ar bank roadennoù n'hallo ket mui implijer ebet kemmañ pajennoù, enrollañ e benndibaboù, kemmañ e rollad evezhiañ na seveniñ oberiadenn ebet a c'houlenn degas kemm pe gemm er bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober hag e vo dibrennet ar bank ganeoc'h kerkent ha ma vo bet kaset da benn hoc'h oberiadenn drezalc'h.",
'unlockdbtext'        => "Dibrennañ ar bank a lakay adarre an holl implijerien e-tailh da gemmañ pajennoù, hizivaat o fenndibaboù hag o rollad evezhiañ ha seveniñ an holl oberiadennoù a c'houlenn ma vefe kemmet ar bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober.",
'lockconfirm'         => 'Ya, kadarnaat a ran e fell din prennañ ar bank roadennoù.',
'unlockconfirm'       => 'Ya, kadarnaat a ran e fell din dibrennañ ar bank roadennoù.',
'lockbtn'             => 'Prennañ ar bank',
'unlockbtn'           => 'Dibrennañ ar bank',
'locknoconfirm'       => "N'eo ket bet asket al log kadarnaat ganeoc'h.",
'lockdbsuccesssub'    => 'Bank prennet.',
'unlockdbsuccesssub'  => 'Bank dibrennet.',
'lockdbsuccesstext'   => "Prennet eo bank roadennnoù {{SITENAME}}.

<br />Na zisoñjit ket e zibrennañ pa vo bet kaset da benn vat hoc'h oberiadenn drezalc'h.",
'unlockdbsuccesstext' => 'Dibrennet eo bank roadennoù {{SITENAME}}.',
'lockfilenotwritable' => "N'haller ket skrivañ war restr prennañ ar bank roadennoù. A-benn prennañ-dibrennañ ar bank e rankit bezañ aotreet da skrivañ war ar servijer Kenrouedad.",
'databasenotlocked'   => "N'eo ket prennet ar bank titouroù.",

# Move page
'move-page'                    => "Dilec'hiañ $1",
'move-page-legend'             => 'Adenvel ur pennad',
'movepagetext'                 => "Grit gant ar furmskrid a-is evit adenvel ur pennad hag adkas an holl stummoù kent anezhañ war-du an anv nevez.
Dont a raio an titl kentañ da vezañ ur bajenn adkas war-du an titl nevez.
Ne vo ket kemmet liammoù an titl kozh ha ne vo ket dilec'hiet ar bajenn gaozeal, ma'z eus anezhi.

'''DIWALLIT!'''
Gallout a ra kement-se bezañ ur c'hemm bras ha dic'hortoz evit ur pennad a vez sellet outi alies;
bezit sur e komprenit mat an heuliadoù a-raok kenderc'hel ganti.",
'movepagetalktext'             => "Gant se e vo adanvet ent emgefre ar bajenn gaozeal stag, ma'z eus anezhi '''nemet ma:'''
*ec'h adanvit ur bajenn war-du ul lec'h all,
*ez eus ur bajenn gaozeal c'hoazh gant an anv nevez, pe
*diweredekaet hoc'h eus ar bouton a-is.

En degouezh-se e rankot adenvel pe gendeuziñ ar bajenn c'hwi hoc'h-unan ma karit.",
'movearticle'                  => "Dilec'hiañ ar pennad",
'moveuserpage-warning'         => "'''Diwallit : ''' War-nes dilec'hiañ ur bajenn implijer emaoc'h. Notit mat n'eus nemet ar bajenn a vo dilec'hiet ha ne vo ''ket'' adanvet an implijer.",
'movenologin'                  => 'Digevreet',
'movenologintext'              => 'A-benn gellout adenvel ur pennad e rankit bezañ un implijer enrollet ha bezañ [[Special:UserLogin|kevreet]].',
'movenotallowed'               => "N'oc'h ket aotreet da zilec'hiañ pajennoù.",
'movenotallowedfile'           => "N'oc'h ket aoteret da adenvel restroù.",
'cant-move-user-page'          => "Noc'h ket aotreet da adenvel pajennoù pennañ an implijerien (er-maez eus o ispajennoù).",
'cant-move-to-user-page'       => "Noc'h ket aotreet da adenvel ur bajenn gant anv hini un implijer all (nemet un ispajenn e vefe).",
'newtitle'                     => 'anv nevez',
'move-watch'                   => 'Evezhiañ ar bajenn-mañ',
'movepagebtn'                  => 'Adenvel ar pennad',
'pagemovedsub'                 => "Dilec'hiadenn kaset da benn vat",
'movepage-moved'               => '\'\'\'Adkaset eo bet "$1" war-du "$2"\'\'\'',
'movepage-moved-redirect'      => 'Krouet ez eus bet un adkas.',
'movepage-moved-noredirect'    => 'Nullet eo bet krouidigezeh un adkas adal an anv kozh.',
'articleexists'                => "Ur pennad gantañ an anv-se zo dija pe n'eo ket reizh an titl hoc'h eus dibabet.
Dibabit unan all mar plij.",
'cantmove-titleprotected'      => "N'hallit ket dilec'hiañ ur bajenn d'al lec'h-mañ rak gwarezet eo bet an titl nevez p'eo bet krouet.",
'talkexists'                   => "Dilec'hiet mat eo bet ar bajenn hec'h-unan met chomet eo ar bajenn gaozeal rak unan all a oa dija gant an anv nevez-se. Kendeuzit anezho c'hwi hoc'h-unan mar plij.",
'movedto'                      => 'adanvet e',
'movetalk'                     => 'Adenvel ivez ar bajenn "gaozeal", mar bez ret.',
'move-subpages'                => 'Adenvel an ispajennoù (betek $1 pajenn)',
'move-talk-subpages'           => 'Adenvel ispajennoù ar bajenn gaozeal (betek $1 pajenn).',
'movepage-page-exists'         => "Bez' ez eus eus ar bajenn $1 c'hoazh ha n'hall ket bezañ friket ent emgefre.",
'movepage-page-moved'          => 'Anv nevez ar bajenn $1 zo $2.',
'movepage-page-unmoved'        => "N'eus ket bet gallet adenvel ar bajenn $1 e $2.",
'movepage-max-pages'           => 'Tizhet eo bet ar vevenn uhelañ a $1 {{PLURAL:$1|bajenn|pajenn}} da adenvel ha ne vo ket adanvet hini all ebet ken ent emgefre.',
'1movedto2'                    => '[[$1]] adkaset war-du [[$2]]',
'1movedto2_redir'              => '[[$1]] adkaset war-du [[$2]] (adkas)',
'move-redirect-suppressed'     => 'adkas nullet',
'movelogpage'                  => 'Roll an adkasoù',
'movelogpagetext'              => 'Setu roll ar pajennoù bet savet un adkas evito.',
'movesubpage'                  => '{{PLURAL:$1|Ispajenn|Ispajenn}}',
'movesubpagetext'              => "Bez' ez eus $1 {{PLURAL:$1|ispajenn|ispajenn}} diskouezet a-is d'ar bajenn-mañ.",
'movenosubpage'                => "Ispajenn ebet d'ar bajenn-mañ.",
'movereason'                   => 'Abeg :',
'revertmove'                   => 'nullañ',
'delete_and_move'              => 'Diverkañ ha sevel adkas',
'delete_and_move_text'         => "==Ezhomm diverkañ==

Savet eo ar pennad tal \"[[:\$1]]\" c'hoazh.
Diverkañ anezhañ a fell deoc'h ober evit reiñ lec'h d'an adkas ?",
'delete_and_move_confirm'      => 'Ya, diverkañ ar bajenn',
'delete_and_move_reason'       => "Diverket evit ober lec'h d'an adkas",
'selfmove'                     => "Heñvel eo titl ar poent loc'hañ ha hini ar pal; n'haller ket adkas ur bajenn war-du he lec'h orin.",
'immobile-source-namespace'    => 'n\'haller kas ar pajennoù war-du an esaouenn anv "$1"',
'immobile-target-namespace'    => 'N\'hallit ket adenvel pajennoù war-du an esaouenn anv "$1"',
'immobile-target-namespace-iw' => "N'eo ket ur pal mat al liammoù Interwiki evit adenvel pajennoù.",
'immobile-source-page'         => "N'haller ket adenvel ar bajenn-mañ.",
'immobile-target-page'         => "N'haller ket kas ar bajenn-mañ war-du an titl-se.",
'imagenocrossnamespace'        => "N'haller ket dilec'hiañ ur skeudenn war-du un esaouenn anv n'eo ket hini ur skeudenn.",
'imagetypemismatch'            => 'Ne glot ket astenn nevez ar restr gant ar furmad-mañ.',
'imageinvalidfilename'         => 'Fall eo anv ar restr tal',
'fix-double-redirects'         => 'Hizivaat an holl adkasoù a gas war-du an titl orin',
'move-leave-redirect'          => 'Lezel un adkas war-du an titl nevez',
'protectedpagemovewarning'     => "'''DIWALLIT :''' Prennet eo bet ar bajenn-mañ, setu n'eus nemet an implijerien ganto gwrioù merañ a c'hall adenvel anezhi. Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'semiprotectedpagemovewarning' => "'''NOTENN :''' Prennet eo bet ar bajenn-mañ, setu n'hall bezañ adanvet nemet gant an implijerien enskrivet. Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'move-over-sharedrepo'         => "== Bez' ez eus eus ar restr-se dija ==
Bez' ez eus eus [[:$1]] war ur sanailh kenrannet dija. Ma cheñchit anv ar restr ne viot ket mui evit tizhout ar restr zo er sanailh kenrannet.",
'file-exists-sharedrepo'       => "Implijet c'hoazh eo an anv dibabet gant ur restr zo war ur sanailh kenrannet.
Grit gant un anv all.",

# Export
'export'            => 'Ezporzhiañ pajennoù',
'exporttext'        => "Gallout a rit ezporzhiañ en XML an destenn ha pennad istor ur bajenn pe ur strollad pajennoù;
a-benn neuze e c'hall an disoc'h bezañ enporzhiet en ur wiki all a ya en-dro gant ar meziant MediaWiki dre [[Special:Import|ar bajenn enporzhiañ]].

A-benn ezporzhiañ pajennoù, merkit an titloù anezho er voest skrid a-is, un titl dre linenn. Diuzit mar fell deoc'h kaout, pe get, ar stumm a-vremañ gant an holl stummoù kozh, gant linennoù itor ar bajenn, pe just ar bajenn red gant titouroù diwar-benn ar c'hemm diwezhañ.

Mard eo se e c'hallit ivez implijout ul liamm a seurt gant [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] evit ar bajenn [[{{MediaWiki:Mainpage}}]].",
'exportcuronly'     => 'Ezporzhiañ hepken ar stumm red hep an istor anezhañ',
'exportnohistory'   => "----
'''Notenn :''' Dilezet eo bet an ezporzhiañ istor klok ar pajennoù evit poent peogwir e veze gorrekaet ar reizhiad diwar se.",
'export-submit'     => 'Ezporzhiañ',
'export-addcattext' => 'Ouzhpennañ pajennoù ar rummad :',
'export-addcat'     => 'Ouzhpennañ',
'export-addnstext'  => 'Ouzhpennañ pajennoù eus an esaouenn anv :',
'export-addns'      => 'Ouzhpennañ',
'export-download'   => 'Aotren enrollañ evel ur restr',
'export-templates'  => 'Lakaat ar patromoù e-barzh ivez',
'export-pagelinks'  => 'Lakaat ar pajennoù liammet e-barzh betek un donder a :',

# Namespace 8 related
'allmessages'                   => 'Roll kemennoù ar reizhiad',
'allmessagesname'               => 'Anv',
'allmessagesdefault'            => 'Testenn dre ziouer',
'allmessagescurrent'            => 'Testenn zo bremañ',
'allmessagestext'               => "Setu roll ar c'hemennadennoù reizhiad a c'haller kaout en esaouennoù anv MediaWiki.
Kit da welet [http://www.mediawiki.org/wiki/Localisation Lec'heladur MediaWiki] ha [http://translatewiki.net translatewiki.net] mar fell deoc'h kemer perzh e lec'heladur boutin MediaWiki.",
'allmessagesnotsupportedDB'     => "N'haller ket kaout {{ns:special}}:AllMessages rak diweredekaet eo bet wgUseDatabaseMessages.",
'allmessages-filter-legend'     => 'Sil',
'allmessages-filter'            => "Silañ dre stad ar c'hemmoù",
'allmessages-filter-unmodified' => 'Digemm',
'allmessages-filter-all'        => 'An holl',
'allmessages-filter-modified'   => 'Kemmet',
'allmessages-prefix'            => 'Silañ dre rakger',
'allmessages-language'          => 'Yezh :',
'allmessages-filter-submit'     => 'Mont',

# Thumbnails
'thumbnail-more'           => 'Brasaat',
'filemissing'              => 'Restr ezvezant',
'thumbnail_error'          => 'Fazi e-ser krouiñ an alberz : $1',
'djvu_page_error'          => 'Pajenn DjVu er-maez ar bevennoù',
'djvu_no_xml'              => 'Dibosupl da dapout an XML evit ar restr DjVu',
'thumbnail_invalid_params' => 'Arventennoù direizh evit ar munud',
'thumbnail_dest_directory' => "Dibosupl krouiñ ar c'havlec'h pal",
'thumbnail_image-type'     => "N'eo ket skoret ar seurt skeudennoù",
'thumbnail_gd-library'     => "Kefluniadur diglok al levraoueg GD : dibosupl kavout an arc'hwel $1",
'thumbnail_image-missing'  => "Evit doare n'eus ket eus ar restr : $1",

# Special:Import
'import'                     => 'Enporzhiañ pajennoù',
'importinterwiki'            => 'enporzhiadenn etrewiki',
'import-interwiki-text'      => 'Diuzit ur wiki hag ur bajenn da enporzhiañ.
Miret e vo deiziadoù ar stummmoù hag anvioù an aozerien.
Miret eo an holl enporzhiadennoù etrewiki e-barzh [[Special:Log/import|log an enporzhiadennoù]].',
'import-interwiki-source'    => 'wiki ha pajennoù tarzh :',
'import-interwiki-history'   => 'Eilañ holl stummoù istor ar bajenn-mañ',
'import-interwiki-templates' => 'Lakaat e-barzh an holl batromoù',
'import-interwiki-submit'    => 'Enporzhiañ',
'import-interwiki-namespace' => 'Esaouenn anv ar pal :',
'import-upload-filename'     => 'Anv ar restr :',
'import-comment'             => 'Notenn :',
'importtext'                 => "Ezporzhiit ur restr adal ar wiki orin en ur implij an arc'hwel Special:Export, enrollit ar bajenn war ho pladenn ha degasit anezhi amañ.",
'importstart'                => "Oc'h enporzhiañ pajennoù...",
'import-revision-count'      => '$1 {{PLURAL:$1|stumm|stumm}}',
'importnopages'              => 'Pajenn ebet da enporzhiañ.',
'imported-log-entries'       => '$1 moned{{PLURAL:$1||}} eus ar marilh enporzhiet{{PLURAL:$1||}}.',
'importfailed'               => "C'hwitet eo an enporzhiadenn: $1",
'importunknownsource'        => 'Dianav eo seurt ar vammenn enporzhiañ',
'importcantopen'             => "N'eus ket bet gallet digeriñ ar restr enporzhiet",
'importbadinterwiki'         => 'Liamm etrewiki fall',
'importnotext'               => 'Goullo pe hep tamm testenn ebet',
'importsuccess'              => 'Deuet eo an enporzhiadenn da benn vat!',
'importhistoryconflict'      => "Divankadennoù zo er pennad istor ha tabut zo gant se (marteze eo bet enporzhiet ar bajenn araozoc'h)",
'importnosources'            => "N'eus bet spisaet tamm mammenn etrewiki ebet ha diweredekaet eo enporzhiañ an Istor war-eeun.",
'importnofile'               => "N'eus bet enporzhiet restr ebet.",
'importuploaderrorsize'      => "C'hwitet eo bet enporzhiañ ar restr. Brasoc'h eo ar restr eget ar vent aotreet.",
'importuploaderrorpartial'   => "C'hwitet eo vet enporzhiañ ar restr. Enporzhiet evit darn eo bet hepken.",
'importuploaderrortemp'      => "C'hwitet eo bet enporzhiañ ar restr. Mankout a ra ur restr badennek.",
'import-parse-failure'       => "Troc'h e dielfennadenn an enporzh XML",
'import-noarticle'           => 'Pajenn ebet da enporzhiañ !',
'import-nonewrevisions'      => "Enporzhiet eo bet an holl degasadennoù c'hoazh.",
'xml-error-string'           => '$1 war al linenn $2, bann $3 (okted $4) : $5',
'import-upload'              => 'Enporzhiañ roadennoù XML',
'import-token-mismatch'      => "Kollet eo bet roadennoù an dalc'h. Klaskit en-dro.",
'import-invalid-interwiki'   => 'Dibosupl enporzhiañ adal ar wiki spisaet.',

# Import log
'importlogpage'                    => 'Log an enporzhiadennoù',
'importlogpagetext'                => "Enporzhiadennoù melestradurel eus pajennoù adal wikioù all gant istor ar c'hemmadennoù degaset enno.",
'import-logentry-upload'           => 'en/he deus enporzhiet (pellgarget) [[$1]]',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|stumm|stumm}}',
'import-logentry-interwiki'        => 'treuzwikiet $1',
'import-logentry-interwiki-detail' => "$1 {{PLURAL:$1|c'hemm|kemm}} abaoe $2",

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ho pajenn implijer',
'tooltip-pt-anonuserpage'         => "Ar bajenn implijer evit ar c'homlec'h IP implijet ganeoc'h",
'tooltip-pt-mytalk'               => 'Ho pajenn gaozeal',
'tooltip-pt-anontalk'             => "Kaozeadennoù diwar-benn ar c'hemmoù graet adal ar chomlec'h-mañ",
'tooltip-pt-preferences'          => 'Ma fenndibaboù',
'tooltip-pt-watchlist'            => "Roll ar pajennoù evezhiet ganeoc'h.",
'tooltip-pt-mycontris'            => 'Roll ho tegasadennoù',
'tooltip-pt-login'                => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h kevreañ",
'tooltip-pt-anonlogin'            => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h kevreañ.",
'tooltip-pt-logout'               => 'Digevreañ',
'tooltip-ca-talk'                 => 'Kaozeadennoù diwar-benn ar pennad',
'tooltip-ca-edit'                 => 'Gallout a rit degas kemmoù er bajenn-mañ. Implijit ar stokell Rakwelet a-raok enrollañ, mar plij.',
'tooltip-ca-addsection'           => 'Kregiñ gant ur rann nevez.',
'tooltip-ca-viewsource'           => 'Gwarezet eo ar bajenn-mañ. Gallout a rit gwelet an danvez anezhañ memes tra.',
'tooltip-ca-history'              => 'Stummoù kozh ar bajenn-mañ gant an aozerien anezhi.',
'tooltip-ca-protect'              => 'Gwareziñ ar bajenn-mañ',
'tooltip-ca-unprotect'            => 'Diwareziñ ar bajenn',
'tooltip-ca-delete'               => 'Diverkañ ar bajenn-mañ',
'tooltip-ca-undelete'             => 'Adsevel ar bajenn-mañ',
'tooltip-ca-move'                 => 'Adenvel ar bajenn-mañ',
'tooltip-ca-watch'                => "Ouzhpennañ ar bajenn-mañ d'ho roll evezhiañ",
'tooltip-ca-unwatch'              => 'Paouez da evezhiañ ar bajenn-mañ',
'tooltip-search'                  => 'Klaskit er wiki-mañ',
'tooltip-search-go'               => "Mont d'ar bajenn dezhi an anv-mañ rik, ma'z eus anezhi",
'tooltip-search-fulltext'         => 'Klask an destenn-mañ er pajennoù',
'tooltip-p-logo'                  => 'Pajenn bennañ',
'tooltip-n-mainpage'              => 'Diskouez ar Bajenn bennañ',
'tooltip-n-mainpage-description'  => 'Kit da welet an degemer',
'tooltip-n-portal'                => "Diwar-benn ar raktres, ar pezh a c'hallit ober, pelec'h kavout an traoù",
'tooltip-n-currentevents'         => 'Tapout keleier diwar-benn an darvoudoù diwezhañ',
'tooltip-n-recentchanges'         => "Roll ar c'hemmoù diwezhañ c'hoarvezet war ar wiki.",
'tooltip-n-randompage'            => 'Diskwel ur bajenn dre zegouezh',
'tooltip-n-help'                  => 'Skoazell.',
'tooltip-t-whatlinkshere'         => 'Roll ar pajennoù liammet ouzh ar bajenn-mañ',
'tooltip-t-recentchangeslinked'   => "Roll ar c'hemmoù diwezhañ war ar pajennoù liammet ouzh ar bajenn-mañ",
'tooltip-feed-rss'                => 'Magañ ar red RSS evit ar bajenn-mañ',
'tooltip-feed-atom'               => 'Magañ ar red Atom evit ar bajenn-mañ',
'tooltip-t-contributions'         => 'Gwelet roll degasadennoù an implijer-mañ',
'tooltip-t-emailuser'             => "Kas ur postel d'an implijer-mañ",
'tooltip-t-upload'                => 'Enporzhiañ ur skeudenn pe ur restr media war ar servijer',
'tooltip-t-specialpages'          => 'Roll an holl bajennoù dibar',
'tooltip-t-print'                 => 'Stumm moulladus ar bajenn-mañ',
'tooltip-t-permalink'             => 'Liamm padus war-du ar stumm-mañ eus ar bajenn',
'tooltip-ca-nstab-main'           => 'Gwelet ar pennad',
'tooltip-ca-nstab-user'           => 'Gwelet ar bajenn implijer',
'tooltip-ca-nstab-media'          => 'Gwelet pajenn ar media',
'tooltip-ca-nstab-special'        => "Ur bajenn dibar eo homañ, n'oc'h ket evit degas kemmoù enni.",
'tooltip-ca-nstab-project'        => 'Gwelet pajenn ar raktres',
'tooltip-ca-nstab-image'          => 'Gwelet pajenn deskrivañ ar bajenn-mañ',
'tooltip-ca-nstab-mediawiki'      => 'Gwelet kemenn ar reizhiad',
'tooltip-ca-nstab-template'       => 'Gwelet ar patrom',
'tooltip-ca-nstab-help'           => 'Gwelet ar bajenn soazell',
'tooltip-ca-nstab-category'       => 'Gwelet pajenn ar rummad',
'tooltip-minoredit'               => "Merkañ ar c'hemm-mañ evel dister",
'tooltip-save'                    => 'Enrollañ ho kemmoù',
'tooltip-preview'                 => "Rakwelet ar c'hemmoù; trugarez d'ober gantañ a-raok enrollañ!",
'tooltip-diff'                    => "Diskouez ar c'hemmoù degaset ganeoc'h en destenn.",
'tooltip-compareselectedversions' => "Sellet ouzh an diforc'hioù zo etre daou stumm diuzet ar bajenn-mañ.",
'tooltip-watch'                   => 'Ouzhpennañ ar bajenn-mañ ouzh ho rollad evezhiañ',
'tooltip-recreate'                => 'Adkrouiñ ar bajenn ha pa vije bet diverket a-raok',
'tooltip-upload'                  => 'Kregiñ da enporzhiañ',
'tooltip-rollback'                => "\"Disteuler\" a zistaol en ur c'hlik ar c'hemm(où) bet degaset d'ar bajenn-mañ gant an implijer diwezhañ.",
'tooltip-undo'                    => '"Dizober" a zistaol ar c\'hemm-mañ hag a zigor ar prenestr skridaozañ er mod rakwelet.
Talvezout a ra da ouzhpennañ un displegadenn er c\'hombod diverrañ.',
'tooltip-preferences-save'        => 'Enrollañ ar penndibaboù',
'tooltip-summary'                 => 'Skrivit un diveradenn verr',

# Stylesheets
'common.css'      => '/** Talvezout a raio ar CSS lakaet amañ evit an holl gwiskadurioù */',
'standard.css'    => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Standard */',
'nostalgia.css'   => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Melkoni */',
'cologneblue.css' => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Glaz Kologn */',
'monobook.css'    => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Monobook */',
'myskin.css'      => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Myskin */',
'chick.css'       => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Plogig */',
'simple.css'      => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Eeun */',
'modern.css'      => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Modern */',
'vector.css'      => '/* Ar CSS lakaet amañ a dalvezo evit implijerien ar gwiskadur Vektor */',
'print.css'       => '/* Ar CSS lakaet amañ a dalvezo evit ar moullañ */',
'handheld.css'    => '/* Ar CSS lakaet amañ a dalvezo evit an ardivinkoù hezoug diouzh ar gwiskadur kefluniet e $wgHandheldStyle */',

# Scripts
'common.js'      => '* Forzh pe JavaScript amañ a vo karget evit an holl implijerien war kement pajenn lennet ganto. */',
'standard.js'    => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Standard */',
'nostalgia.js'   => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Melkoni */',
'cologneblue.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Glaz Kologn */',
'monobook.js'    => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur MonoBook */',
'myskin.js'      => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Myskin */',
'chick.js'       => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Plogig */',
'simple.js'      => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Eeun */',
'modern.js'      => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Modern */',
'vector.js'      => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Vektor */',

# Metadata
'nodublincore'      => "Diweredekaet eo ar metaroadennoù 'Dublin Core RDF' war ar servijer-mañ.",
'nocreativecommons' => "N'eo ket gweredekaet ar metaroadennoù 'Creative Commons RDF' war ar servijer-mañ.",
'notacceptable'     => "N'eo ket ar servijer wiki-mañ evit pourchas stlennoù en ur furmad lennus evit ho arval.",

# Attribution
'anonymous'        => '{{PLURAL:$1|Implijer|Implijerien}} dizanv war {{SITENAME}}',
'siteuser'         => 'Implijer(ez) $1 eus {{SITENAME}}',
'anonuser'         => 'implijer dizanv $1 eus {{SITENAME}}',
'lastmodifiedatby' => "Kemmet eo bet ar bajenn-mañ da ziwezhañ da $2, d'an $1 gant $3",
'othercontribs'    => 'Diazezet war labour $1.',
'others'           => 're all',
'siteusers'        => '$1 {{PLURAL:$2|implijer|implijer}} eus {{SITENAME}}',
'anonusers'        => '{{PLURAL:$2|implijer dizanv|implijerien dizanv}} $1 eus {{SITENAME}}',
'creditspage'      => 'Pajennoù kredoù',
'nocredits'        => "N'eus tamm titour kred hegerz ebet evit ar bajenn-mañ.",

# Spam protection
'spamprotectiontitle' => "Sil gwareziñ a-enep d'ar Strob",
'spamprotectiontext'  => "Stanket eo bet ar bajenn a felle deoc'h enrollañ gant ar siler stroboù.
Sur a-walc'h abalamour d'ul liamm enni a gas d'ul lec'hienn ziavaez berzet.",
'spamprotectionmatch' => 'Dihunet eo bet an detektour Strob gant an destenn-mañ : $1',
'spambot_username'    => 'Naetaat ar strob gant MediaWiki',
'spam_reverting'      => "Distreiñ d'ar stumm diwezhañ hep liamm davet $1",
'spam_blanking'       => 'Diverkañ an holl stummoù enno liammoù davet $1',

# Info page
'infosubtitle'   => 'Titouroù evit ar bajenn',
'numedits'       => 'Niver a gemmoù (pennad) : $1',
'numtalkedits'   => 'Niver a gemmoù (pajenn gaozeal) : $1',
'numwatchers'    => 'Niver a dud o lenn : $1',
'numauthors'     => 'Niver a aozerien zisheñvel (pennad) : $1',
'numtalkauthors' => 'Niver a aozerien zisheñvel (pajenn gaozeal) : $1',

# Skin names
'skinname-standard'    => 'Standard',
'skinname-nostalgia'   => 'Melkoni',
'skinname-cologneblue' => 'Glaz Kologn',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Plogig',
'skinname-simple'      => 'Eeun',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vektor',

# Math options
'mw_math_png'    => 'Produiñ atav ur skeudenn PNG',
'mw_math_simple' => "HTML m'eo eeun-kenañ, a-hend-all ober gant PNG",
'mw_math_html'   => 'HTML mar bez tu, a-hend-all PNG',
'mw_math_source' => "Leuskel ar c'hod TeX orin",
'mw_math_modern' => 'Evit ar merdeerioù arnevez',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Fazi jedoniezh',
'math_unknown_error'    => 'fazi dianav',
'math_unknown_function' => 'kevreizhenn jedoniel dianav',
'math_lexing_error'     => 'fazi ger',
'math_syntax_error'     => 'fazi ereadur',
'math_image_error'      => "C'hwitet eo bet ar gaozeadenn e PNG, gwiriit staliadur Latex, dvips, gs ha convert",
'math_bad_tmpdir'       => "N'hall ket krouiñ pe skrivañ er c'havlec'h da c'hortoz",
'math_bad_output'       => "N'hall ket krouiñ pe skrivañ er c'havlec'h ermaeziañ",
'math_notexvc'          => "N'hall ket an erounezeg 'texvc' bezañ kavet. Lennit math/README evit he c'hefluniañ.",

# Patrolling
'markaspatrolleddiff'                 => 'Merkañ evel gwiriet',
'markaspatrolledtext'                 => 'Merkañ ar pennad-mañ evel gwiriet',
'markedaspatrolled'                   => 'Merkañ evel gwiriet',
'markedaspatrolledtext'               => 'Merket eo bet ar stumm diuzet eus [[:$1]] evel gwiriet.',
'rcpatroldisabled'                    => "Diweredekaet ar gwiriañ ar C'hemmoù diwezhañ",
'rcpatroldisabledtext'                => "Diweredekaet eo bet an arc'hwel evezhiañ ar c'hemmoù diwezhañ.",
'markedaspatrollederror'              => "N'hall ket bezañ merket evel gwiriet",
'markedaspatrollederrortext'          => "Ret eo deoc'h spisaat ur stumm a-benn e verkañ evel gwiriet.",
'markedaspatrollederror-noautopatrol' => "N'oc'h ket aotreet da verkañ evel gwiriet ar c'hemmoù degaset ganeoc'h.",

# Patrol log
'patrol-log-page'      => 'Log gwiriañ',
'patrol-log-header'    => 'Setu ur marilh eus ar stummoù patrouilhet.',
'patrol-log-line'      => 'en/he deus merket ar stumm $1 eus $2 evel gwiriet $3',
'patrol-log-auto'      => '(emgefre)',
'patrol-log-diff'      => 'kemm $1',
'log-show-hide-patrol' => '$1 istor ar stummoù gwiriet',

# Image deletion
'deletedrevision'                 => 'Diverket stumm kozh $1.',
'filedeleteerror-short'           => 'Fazi e-ser diverkañ ar restr : $1',
'filedeleteerror-long'            => 'Fazioù zo bet kavet e-ser diverkañ ar restr :

$1',
'filedelete-missing'              => 'N\'haller ket diverkañ ar restr "$1" peogwir n\'eus ket anezhi.',
'filedelete-old-unregistered'     => 'Stumm spisaet ar restr "$1" n\'emañ ket er bank titouroù.',
'filedelete-current-unregistered' => 'Ar restr spisaet "$1" n\'emañ ket er bank titouroù.',
'filedelete-archive-read-only'    => 'N\'hall ket ar servijer web skrivañ war ar c\'havlec\'h dielloù "$1".',

# Browsing diffs
'previousdiff' => '← Stumm kent',
'nextdiff'     => "Stumm nevesoc'h →",

# Media information
'mediawarning'         => "'''Diwallit :''' Kodoù siek a c'hall bezañ er seurt restr-mañ.
Ma vez erounezet ganeoc'h e c'hallje tagañ ho reizhiad.",
'imagemaxsize'         => "Bevenn ment vrasañ ar skeudennoù :<br />''(evit ar pajennoù deskrivañ)''",
'thumbsize'            => 'Ment an alberz :',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pajenn|pajenn}}',
'file-info'            => '(ment ar restr : $1, seurt MIME : $2)',
'file-info-size'       => '($1 × $2 piksel, ment ar restr : $3, seurt MIME : $4)',
'file-nohires'         => "<small>N'haller ket gwellaat ar pizhder.</small>",
'svg-long-desc'        => '(restr SVG file, pizhder $1 × $2 piksel, ment ar restr : $3)',
'show-big-image'       => 'Pizhder leun',
'show-big-image-thumb' => '<small>Ment ar rakweled-mañ : $1 × $2 piksel</small>',
'file-info-gif-looped' => "e kelc'h",
'file-info-gif-frames' => '$1 {{PLURAL:$1|skeudenn|skeudenn}}',
'file-info-png-looped' => "e kelc'h",
'file-info-png-repeat' => 'lennet $1 {{PLURAL:$1|wezh|gwezh}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|skeudenn|skeudenn}}',

# Special:NewFiles
'newimages'             => 'Roll ar restroù nevez',
'imagelisttext'         => "Setu aze ur roll '''$1''' {{PLURAL:$1|file|files}} rummet $2.",
'newimages-summary'     => 'Diskouez a ra ar bajenn dibar-mañ roll ar restroù diwezhañ bet enporzhiet.',
'newimages-legend'      => 'Sil',
'newimages-label'       => 'Anv ar restr (pe darn anezhi) :',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Netra da welet.',
'ilsubmit'              => 'Klask',
'bydate'                => 'dre an deiziad anezho',
'sp-newimages-showfrom' => 'Diskouez ar restroù nevez adal $1, $2',

# Bad image list
'bad_image_list' => "Setu doare ar furmad :

Ne seller nemet ouzh roll an elfennoù (linennoù a grog gant *). Ret eo d'al liamm kentañ war ul linenn bezañ ul liamm war-du ur skeudenn fall.
Kement liamm all war an hevelep linenn a seller outañ evel un nemedenn, da skouer pennadoù ma c'hall ar skeudenn dont war wel.",

# Metadata
'metadata'          => 'Metaroadennoù',
'metadata-help'     => "Titouroù ouzhpen zo er restr-mañ; bet lakaet moarvat gant ar c'hamera niverel pe ar skanner implijet evit he niverelaat. Mard eo bet cheñchet ar skeudenn e-keñver he stad orin marteze ne vo ket kenkoulz munudoù zo e-keñver ar skeudenn kemmet.",
'metadata-expand'   => 'Dispakañ ar munudoù',
'metadata-collapse' => 'Krennañ ar munudoù',
'metadata-fields'   => "Ensoc'het e vo ar maeziennoù metastlennoù EXIF rollet er gemennadenn-mañ e pajenn deskrivañ ar skeudenn pa vo punet taolenn ar metaroadennoù. Kuzhet e vo ar re all dre ziouer.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Led',
'exif-imagelength'                 => 'Hed',
'exif-bitspersample'               => 'Niv. a vitoù dre barzhioù',
'exif-compression'                 => 'Seurt gwaskadur',
'exif-photometricinterpretation'   => 'Kenaozadur piksel',
'exif-orientation'                 => 'Tuadur',
'exif-samplesperpixel'             => 'Niver a standilhonoù',
'exif-planarconfiguration'         => 'Kempenn ar roadennoù',
'exif-ycbcrsubsampling'            => 'Feur standilhoniñ Y da C',
'exif-ycbcrpositioning'            => "Lec'hiadur Y ha C",
'exif-xresolution'                 => 'Pizhder led ar skeudenn',
'exif-yresolution'                 => 'Pizhder hed ar skeudenn',
'exif-resolutionunit'              => 'Unanennoù pizhder X ha Y',
'exif-stripoffsets'                => "Lec'hiadur roadennoù ar skeudenn",
'exif-rowsperstrip'                => 'Niver a linennoù dre vandenn',
'exif-stripbytecounts'             => 'Ment e oktedoù dre vandenn',
'exif-jpeginterchangeformat'       => "Lec'hiadur ar SOI JPEG",
'exif-jpeginterchangeformatlength' => 'Ment ar roadennoù JPEG en eizhbitoù',
'exif-transferfunction'            => "Arc'hwel treuzkas",
'exif-whitepoint'                  => 'Kromategezh ar poent gwenn',
'exif-primarychromaticities'       => 'Kromategezh al livioù orin',
'exif-ycbcrcoefficients'           => 'Kenefederioù moull treuzfurmiñ an egorenn liv',
'exif-referenceblackwhite'         => 'Talvoudenn dave gwenn ha du',
'exif-datetime'                    => 'Deiziad hag eur kemm restr',
'exif-imagedescription'            => 'Titl ar skeudenn',
'exif-make'                        => 'Oberier ar benveg',
'exif-model'                       => 'Doare ar benveg',
'exif-software'                    => 'Meziant bet implijet',
'exif-artist'                      => 'Aozer',
'exif-copyright'                   => "Perc'henn ar gwirioù aozer (copyright)",
'exif-exifversion'                 => 'Stumm exif',
'exif-flashpixversion'             => 'Skoret ganti stumm Flashpix',
'exif-colorspace'                  => "Lec'h al livioù",
'exif-componentsconfiguration'     => 'Talvoudegezh pep parzh',
'exif-compressedbitsperpixel'      => 'Doare gwaskañ ar skeudenn',
'exif-pixelydimension'             => 'Ledander skeudenn gwiriek',
'exif-pixelxdimension'             => 'Uhelder skeudenn gwiriek',
'exif-makernote'                   => 'Notennoù an oberier',
'exif-usercomment'                 => 'Evezhiadennoù',
'exif-relatedsoundfile'            => 'Restr son stag',
'exif-datetimeoriginal'            => 'Deiziad hag eur ar sevel roadoù',
'exif-datetimedigitized'           => 'Deiziad hag eur an niverelaat',
'exif-subsectime'                  => 'Deiziad kemmañ diwezhañ',
'exif-subsectimeoriginal'          => 'Deiziad an dennadenn orin',
'exif-subsectimedigitized'         => 'Deiziad niverelaat',
'exif-exposuretime'                => "Amzer louc'hañ",
'exif-exposuretime-format'         => '$1 eilenn ($2)',
'exif-fnumber'                     => 'Hed etre sti',
'exif-exposureprogram'             => "Programm louc'hañ",
'exif-spectralsensitivity'         => 'Kizidigezh spektrel',
'exif-isospeedratings'             => 'Kizidigezh ISO',
'exif-oecf'                        => 'Faktor amdreiñ elektronek',
'exif-shutterspeedvalue'           => 'Tizh klozañ',
'exif-aperturevalue'               => 'Digorder',
'exif-brightnessvalue'             => 'Sklêrder',
'exif-exposurebiasvalue'           => "Reizhadenn louc'hañ",
'exif-maxaperturevalue'            => 'Maezienn digeriñ vrasañ',
'exif-subjectdistance'             => 'Hed ar sujed',
'exif-meteringmode'                => 'Doare muzuliañ',
'exif-lightsource'                 => "Mammenn c'houloù",
'exif-flash'                       => "Luc'h",
'exif-focallength'                 => 'Hirder ar fokalenn',
'exif-subjectarea'                 => 'Gorread ar sujed',
'exif-flashenergy'                 => "Nerzh al luc'h",
'exif-spatialfrequencyresponse'    => 'Frekañs egorel',
'exif-focalplanexresolution'       => 'Muzuliadur a-led ur fokalenn blaen',
'exif-focalplaneyresolution'       => 'Muzuliadur a-serzh ur fokalenn blaen',
'exif-focalplaneresolutionunit'    => 'Unanenn spisder evit ur fokalenn blaen',
'exif-subjectlocation'             => "Lec'hiadur ar sujed",
'exif-exposureindex'               => "Meneger louc'hañ",
'exif-sensingmethod'               => 'Hentenn detektiñ',
'exif-filesource'                  => 'Tarzh ar restr',
'exif-scenetype'                   => 'Seurt arvest',
'exif-cfapattern'                  => 'Framm silañ al livioù',
'exif-customrendered'              => 'Plediñ gant ar skeudennoù personelaet',
'exif-exposuremode'                => "Mod louc'hañ",
'exif-whitebalance'                => 'Mentel ar gwennoù',
'exif-digitalzoomratio'            => 'Feur brasaat niverel (zoum)',
'exif-focallengthin35mmfilm'       => 'Hirder ar fokalenn e filmoù 35 mm',
'exif-scenecapturetype'            => 'Doare pakañ an arvest',
'exif-gaincontrol'                 => 'Reizhañ ar sklêrder',
'exif-contrast'                    => 'Dargemm',
'exif-saturation'                  => 'Saturadur',
'exif-sharpness'                   => 'Spisder',
'exif-devicesettingdescription'    => 'Deskrivadur doare ar wikefre',
'exif-subjectdistancerange'        => 'Hed ar sujed',
'exif-imageuniqueid'               => 'Anavezer nemetañ ar skeudenn',
'exif-gpsversionid'                => 'Stumm an neudennad GPS',
'exif-gpslatituderef'              => 'Ledred Norzh pe su',
'exif-gpslatitude'                 => 'Ledred',
'exif-gpslongituderef'             => 'Hedred kornôg pe reter',
'exif-gpslongitude'                => 'Hedred',
'exif-gpsaltituderef'              => 'Daveenn uhelder',
'exif-gpsaltitude'                 => 'Uhelder',
'exif-gpstimestamp'                => 'Eur GPS (eurier atomek)',
'exif-gpssatellites'               => 'Loarelloù implijet evit ar muzuliañ',
'exif-gpsstatus'                   => 'Statud ar resever',
'exif-gpsmeasuremode'              => 'Doare muzuliañ',
'exif-gpsdop'                      => 'Resisder ar muzul',
'exif-gpsspeedref'                 => 'Unanenn dizh',
'exif-gpsspeed'                    => 'Tizh ar resever GPS',
'exif-gpstrackref'                 => "Daveenn evit durc'hadur ar fiñv",
'exif-gpstrack'                    => "Durc'hadur ar fiñv",
'exif-gpsimgdirectionref'          => "Daveenn evit durc'hadur ar skeudenn",
'exif-gpsimgdirection'             => "Durc'hadur ar skeudenn",
'exif-gpsmapdatum'                 => 'Reizhiad geodetek implijet',
'exif-gpsdestlatituderef'          => 'Daveenn evit ledred ar pal',
'exif-gpsdestlatitude'             => 'Ledred ar pal',
'exif-gpsdestlongituderef'         => 'Daveenn evit hedred ar pal',
'exif-gpsdestlongitude'            => 'Hedred ar pal',
'exif-gpsdestbearingref'           => 'Daveenn evit notenniñ ar pal',
'exif-gpsdestbearing'              => 'Notenniñ ar pal',
'exif-gpsdestdistanceref'          => 'Daveenn evit an hed betek ar pal',
'exif-gpsdestdistance'             => 'Hed betek ar pal',
'exif-gpsprocessingmethod'         => 'Anv hentenn blediñ ar GPS',
'exif-gpsareainformation'          => 'Anv an takad GPS',
'exif-gpsdatestamp'                => 'Deiziad GPS',
'exif-gpsdifferential'             => "Reizhadenn diforc'hadus GPS",

# EXIF attributes
'exif-compression-1' => 'Hep gwaskañ',

'exif-unknowndate' => 'Deiziad dianav',

'exif-orientation-1' => 'Boutin',
'exif-orientation-2' => 'Eilpennet a-hed',
'exif-orientation-3' => 'Troet eus 180°',
'exif-orientation-4' => 'Eilpennet a-serzh',
'exif-orientation-5' => 'Troet eus 90° a-gleiz hag eilpennet a-serzh',
'exif-orientation-6' => 'Troet eus 90° a-zehou',
'exif-orientation-7' => 'Troet eus 90° a-zehou hag eilpennet a-serzh',
'exif-orientation-8' => 'Troet eus 90° a-gleiz',

'exif-planarconfiguration-1' => 'Roadennoù kenstok',
'exif-planarconfiguration-2' => 'Roadennoù distag',

'exif-componentsconfiguration-0' => "n'eus ket anezhi",

'exif-exposureprogram-0' => 'Anspisaet',
'exif-exposureprogram-1' => 'Dre zorn',
'exif-exposureprogram-2' => 'Programm boutin',
'exif-exposureprogram-3' => 'Rakgwir digeriñ',
'exif-exposureprogram-4' => 'Rakkwir serriñ',
'exif-exposureprogram-5' => 'Programm krouiñ (tuadur e-keñver donder ar maez)',
'exif-exposureprogram-6' => 'Programm seveniñ (tuadur e-keñver an tizh serriñ)',
'exif-exposureprogram-7' => 'Mod poltred (evit skeudennoù a-dost gant an diadreñv dispis)',
'exif-exposureprogram-8' => 'Mod gweledva (evit skeudennoù gweledva gant an diadreñv spis)',

'exif-subjectdistance-value' => '$1 metr',

'exif-meteringmode-0'   => 'Dianav',
'exif-meteringmode-1'   => 'Keitat',
'exif-meteringmode-2'   => 'Muzul kreiz keitat',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'Liesspot',
'exif-meteringmode-5'   => 'Patrom',
'exif-meteringmode-6'   => 'Darnek',
'exif-meteringmode-255' => 'All',

'exif-lightsource-0'   => 'Dianav',
'exif-lightsource-1'   => 'Gouloù deiz',
'exif-lightsource-2'   => "Treluc'hus",
'exif-lightsource-3'   => 'Tungsten (gouloù kann)',
'exif-lightsource-4'   => "Luc'h",
'exif-lightsource-9'   => 'Amzer digoumoul',
'exif-lightsource-10'  => 'Amzer goumoulek',
'exif-lightsource-11'  => 'Skeud',
'exif-lightsource-12'  => "Gouloù deiz treluc'hus (D 5700 – 7100K)",
'exif-lightsource-13'  => "Gouloù deiz treluc'hus gwenn (N 4600 – 5400K)",
'exif-lightsource-14'  => "Gouloù treluc'hus gwenn yen (W 3900 – 4500K)",
'exif-lightsource-15'  => "Gouloù treluc'hus gwenn (WW 3200 – 3700K)",
'exif-lightsource-17'  => 'Gouloù standard A',
'exif-lightsource-18'  => 'Gouloù standard B',
'exif-lightsource-19'  => 'Gouloù standard C',
'exif-lightsource-24'  => 'Goulaouiñ studio gant tungsten ISO',
'exif-lightsource-255' => "Mammenn c'houloù all",

# Flash modes
'exif-flash-fired-0'    => "Tamm luc'h ebet",
'exif-flash-fired-1'    => "Luc'h taolet",
'exif-flash-return-0'   => "ne zistro arc'hwel detektiñ ebet gant stroboskop ebet",
'exif-flash-return-2'   => "disteuler a ra ar stroboskop ur goulou n'eo ket deteket",
'exif-flash-return-3'   => 'ur goulou detektet a zistro gant ar stroboskop',
'exif-flash-mode-1'     => "Taol luc'h dre ret",
'exif-flash-mode-2'     => "tennañ an taol luc'h dre ret",
'exif-flash-mode-3'     => 'Mod emgefre',
'exif-flash-function-1' => "Arc'hwel luc'h ebet",
'exif-flash-redeye-1'   => 'Mod hep lagadoù ruz',

'exif-focalplaneresolutionunit-2' => 'meudad',

'exif-sensingmethod-1' => 'Hep resisaat',
'exif-sensingmethod-2' => 'Detekter takad liv monokromatek',
'exif-sensingmethod-3' => 'Detekter takad liv bikromatek',
'exif-sensingmethod-4' => 'Detekter takad liv trikromatek',
'exif-sensingmethod-5' => 'Detekter takad liv kemalennek',
'exif-sensingmethod-7' => 'Detekter teirlinennek',
'exif-sensingmethod-8' => 'Detekter liv linennek kemalennek',

'exif-scenetype-1' => "Lun luc'hskeudennet war-eeun",

'exif-customrendered-0' => 'Plediñ boutin',
'exif-customrendered-1' => 'Plediñ personelaet',

'exif-exposuremode-0' => "Emlouc'hañ",
'exif-exposuremode-1' => "Louc'hañ dre zorn",
'exif-exposuremode-2' => 'Emvraketiñ',

'exif-whitebalance-0' => 'Mentel ar gwennoù emgefre',
'exif-whitebalance-1' => 'Mentel ar gwennoù dre zorn',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Gweledva',
'exif-scenecapturetype-2' => 'Poltred',
'exif-scenecapturetype-3' => 'Arvest noz',

'exif-gaincontrol-0' => 'Hini ebet',
'exif-gaincontrol-1' => 'Kresk pakañ izel',
'exif-gaincontrol-2' => 'Kresk pakañ uhel',
'exif-gaincontrol-3' => 'Digresk pakañ izel',
'exif-gaincontrol-4' => 'Digresk pakañ uhel',

'exif-contrast-0' => 'Boutin',
'exif-contrast-1' => 'Dister',
'exif-contrast-2' => 'Kreñv',

'exif-saturation-0' => 'Boutin',
'exif-saturation-1' => 'Saturadur izel',
'exif-saturation-2' => 'Saturadur uhel',

'exif-sharpness-0' => 'Boutin',
'exif-sharpness-1' => 'Dister',
'exif-sharpness-2' => 'Kreñv',

'exif-subjectdistancerange-0' => 'Dianav',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Gwelet a-dost',
'exif-subjectdistancerange-3' => 'Gwelet a-bell',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Ledred norzh',
'exif-gpslatitude-s' => 'Ledred su',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Hedred reter',
'exif-gpslongitude-w' => 'Hedred kornôg',

'exif-gpsstatus-a' => 'O vuzuliañ',
'exif-gpsstatus-v' => 'etreoberatadusted ar muzul',

'exif-gpsmeasuremode-2' => 'Muzuliañ divventek',
'exif-gpsmeasuremode-3' => 'Muzuliañ teirventek',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometr dre eur',
'exif-gpsspeed-m' => 'Miltir dre eur',
'exif-gpsspeed-n' => 'Skoulm',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => "Durc'hadur gwir",
'exif-gpsdirection-m' => 'Norzh magnetek',

# External editor support
'edit-externally'      => 'Kemmañ ar restr-mañ dre un arload diavaez',
'edit-externally-help' => "(Gwelet [http://www.mediawiki.org/wiki/Manual:External_editors erbedadennoù staliañ an aozer diavaez] a-benn gouzout hiroc'h).",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'an holl',
'imagelistall'     => 'an holl',
'watchlistall2'    => 'pep tra',
'namespacesall'    => 'pep tra',
'monthsall'        => 'an holl',
'limitall'         => 'An holl',

# E-mail address confirmation
'confirmemail'              => "Kadarnaat ar chomlec'h postel",
'confirmemail_noemail'      => "N'hoc'h eus ket spisaet chomlec'h postel mat ebet en ho [[Special:Preferences|penndibaboù implijer]].",
'confirmemail_text'         => "Rankout a ra ar wiki-mañ bezañ gwiriet ho chomlec'h postel a-raok gallout implijout nep arc'hwel postel. Implijit ar bouton a-is evit kas ur postel kadarnaat d'ho chomlec'h. Ul liamm ennañ ur c'hod a vo er postel. Kargit al liamm-se en o merdeer evit kadarnaat ho chomlec'h.",
'confirmemail_pending'      => "Ur c'hod kadarnaat zo bet kaset deoc'h dre bostel c'hoazh;
a-raok klask goulenn unan nevez, m'emaoc'h o paouez krouiñ ho kont, e vo fur eus ho perzh gortoz un nebeud munutoù ha leuskel amzer dezhañ d'en em gavout betek ennoc'h.",
'confirmemail_send'         => "Kas ur c'hod kadarnaat",
'confirmemail_sent'         => 'Postel kadarnaat kaset',
'confirmemail_oncreate'     => "Kaset ez eus bet ur c'hod kadarnaat d'ho chomlec'h postel.
N'eus ket ezhomm eus ar c'hod-mañ evit kevreañ met ret e vo deoc'h ober gantañ evit aotren hini pe hini eus arc'hwelioù postel ar wiki.",
'confirmemail_sendfailed'   => "Dibosupl kas ar postel kadarnaat deoc'h gant {{SITENAME}}.
Gwiriit ha n'eus ket arouezennoù direizh en ho chomlec'h.

Distro ar posteler : $1",
'confirmemail_invalid'      => "Kod kadarnaat kamm. Marteze eo aet ar c'hod d'e dermen",
'confirmemail_needlogin'    => "Ret eo deoc'h $1 evit kadarnaat ho chomlec'h postel.",
'confirmemail_success'      => "Kadarnaet eo ho chomlec'h postel. A-benn bremañ e c'hallit [[Special:UserLogin|kevreañ]] hag ober ho mad eus ar wiki.",
'confirmemail_loggedin'     => "Kadarnaet eo ho chomlec'h bremañ",
'confirmemail_error'        => 'Ur gudenn zo bet e-ser enrollañ ho kadarnadenn',
'confirmemail_subject'      => "Kadarnadenn chomlec'h postel evit {{SITENAME}}",
'confirmemail_body'         => "Unan bennak, c'hwi moarvat, gant ar chomlec'h IP \$1,
en deus enrollet ur gont \"\$2\" gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

A-benn kadarnaat eo deoc'h ar gont-se ha gweredekaat
an arc'hwelioù postelerezh war {{SITENAME}}, digorit al liamm a-is en ho merdeer :

\$3

Ma n'eo *ket* bet enrollet ganeoc'h heuilhit al liamm-mañ
evit nullañ kadarnaat ar chomlec'h postel :

\$5

Mont a raio ar c'hod-mañ d'e dermen d'ar \$4.",
'confirmemail_body_changed' => "↓ Unan bennak, c'hwi sur a-walc'h, gant ar chomlec'h IP \$1,
en deus cheñchet chomlec'h postel ar gont \"\$2\" gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

A-benn kadarnaat eo deoc'h ar gont-se hag adgweredekaat
ar perzhioù postel war {{SITENAME}}, digorit al liamm-mañ en ho merdeer :

\$3

Ma n'eo *ket* deoc'h ar gont, heuilhit al liamm-mañ
evit nullañ kadarnaat ar chomlec'h postel :

\$5

Mont a raio ar c'hod kadarnaat-mañ d'e dermen d'ar \$4.",
'confirmemail_invalidated'  => "Nullet eo bet kadarnaat ar chomlec'h postel",
'invalidateemail'           => 'Nullañ kadarnaat ar postel',

# Scary transclusion
'scarytranscludedisabled' => '[Diweredekaet eo an treuzkludañ etrewiki]',
'scarytranscludefailed'   => "[N'eus ket bet gallet tapout ar patrom evit $1]",
'scarytranscludetoolong'  => '[URL re hir]',

# Trackbacks
'trackbackbox'      => 'Liamm war-gil betek al liamm-mañ :<br />
$1',
'trackbackremove'   => '([$1 Diverkañ])',
'trackbacklink'     => 'Liamm war-gil',
'trackbackdeleteok' => 'Diverket mat eo bet al liamm war-gil.',

# Delete conflict
'deletedwhileediting' => "'''Diwallit''' : Diverket eo bet ar bajenn-mañ bremañ ha krog e oac'h da zegas kemmoù enni!",
'confirmrecreate'     => "Diverket eo bet ar pennad-mañ gant [[User:$1|$1]] ([[User talk:$1|kaozeal]]) goude ma vije bet kroget ganeoc'h kemmañ anezhañ :
: ''$2''
Kadarnait mar plij e fell deoc'h krouiñ ar pennad-mañ da vat.",
'recreate'            => 'Adkrouiñ',

# action=purge
'confirm_purge_button' => 'Mat eo',
'confirm-purge-top'    => 'Spurjañ krubuilh ar bajenn-mañ?',
'confirm-purge-bottom' => 'Spurjañ ur bajenn a a naeta ar grubuilh hag a redi ar stumm nevesañ da zont war wel.',

# Multipage image navigation
'imgmultipageprev' => '&larr; pajenn gent',
'imgmultipagenext' => "pajenn war-lerc'h &rarr;",
'imgmultigo'       => 'Mont !',
'imgmultigoto'     => "Mont d'ar bajenn $1",

# Table pager
'ascending_abbrev'         => 'pignat',
'descending_abbrev'        => 'diskenn',
'table_pager_next'         => "Pajenn war-lerc'h",
'table_pager_prev'         => 'Pajenn gent',
'table_pager_first'        => 'Pajenn gentañ',
'table_pager_last'         => 'Pajenn ziwezhañ',
'table_pager_limit'        => 'Diskouez $1 elfenn dre bajenn',
'table_pager_limit_label'  => "Disoc'hoù dre bajenn :",
'table_pager_limit_submit' => 'Mont',
'table_pager_empty'        => "Disoc'h ebet",

# Auto-summaries
'autosumm-blank'   => 'Riñset ar bajenn',
'autosumm-replace' => "Oc'h erlec'hiañ ar bajenn gant '$1'",
'autoredircomment' => 'Adkas war-du [[$1]]',
'autosumm-new'     => 'Krouet pajenn gant : $1',

# Size units
'size-bytes'     => '$1 o',
'size-kilobytes' => '$1 Kio',
'size-megabytes' => '$1 Mio',
'size-gigabytes' => '$1 Gio',

# Live preview
'livepreview-loading' => 'O kargañ...',
'livepreview-ready'   => 'O kargañ... Prest !',
'livepreview-failed'  => "C'hwitet eo rakwelet diouzhtu !
Klaskit rakwelet er mod boutin.",
'livepreview-error'   => 'C\'hwitet kevreañ : $1 "$2"
Klaskit rakwelet er mod boutin.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Marteze ne ziskouezo ket ar roll-mañ an degasadennoù c'hoarvezet $1 {{PLURAL:$1|eilenn|eilenn}} zo hepken.",
'lag-warn-high'   => "Dre m'eo soulgarget ar bankoù roadennoù, marteze ne vo ket gwelet er roll-mañ ar c'hemmoù deuet $1 {{PLURAL:$1|eilenn|eilenn}} zo hepken.",

# Watchlist editor
'watchlistedit-numitems'       => '{{PLURAL:$1|1 pajenn|$1 pajenn}} zo war ho roll evezhiañ, hep kontañ ar pajennoù kaozeal.',
'watchlistedit-noitems'        => "N'eus pajenn ebet war ho roll evezhiañ.",
'watchlistedit-normal-title'   => 'Kemmañ ar roll evezhiañ',
'watchlistedit-normal-legend'  => 'Tennañ ar pajennoù a-ziwar ho roll evezhiañ',
'watchlistedit-normal-explain' => 'Dindan emañ diskouezet titloù ar pajennoù zo war ho roll evezhiañ.
Evit tennañ unan, sellet ouzh ar voest e-kichen ha klikañ war "{{int:Watchlistedit-normal-submit}}".
Gellout a reer [[Special:Watchlist/raw|kemmañ ar roll (mod diginkl) ivez]].',
'watchlistedit-normal-submit'  => 'Tennañ ar pajennoù',
'watchlistedit-normal-done'    => 'Tennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} a-ziwar ho roll evezhiañ :',
'watchlistedit-raw-title'      => 'Kemmañ ar roll evezhiañ (mod diginkl)',
'watchlistedit-raw-legend'     => 'Kemmañ ar roll evezhiañ (mod diginkl)',
'watchlistedit-raw-explain'    => 'Dindan emañ titloù ar pajennoù zo war ho roll evezhiañ; gallout a rit kemmañ anezho en ur ouzhpennañ pe tennañ pajennoù a-ziwar ar roll; un titl dre linenn. Ur wech graet, klikañ war "{{int:Watchlistedit-raw-submit}}".
Gallout a rit [[Special:Watchlist/edit|implijout an aozer boutin ivez]].',
'watchlistedit-raw-titles'     => 'Titloù :',
'watchlistedit-raw-submit'     => 'Nevesaat ar roll evezhiañ',
'watchlistedit-raw-done'       => 'Nevesaet eo bet ho roll evezhiañ.',
'watchlistedit-raw-added'      => 'Ouzhpennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} :',
'watchlistedit-raw-removed'    => 'Tennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} :',

# Watchlist editing tools
'watchlisttools-view' => "Gwelet ar c'hemmoù degaset",
'watchlisttools-edit' => 'Gwelet ha kemmañ ar roll evezhiañ',
'watchlisttools-raw'  => 'Kemmañ ar roll (mod diginkl)',

# Iranian month names
'iranian-calendar-m1'  => '1añ miz Jalāli',
'iranian-calendar-m2'  => '2l miz Jalāli',
'iranian-calendar-m3'  => '3e miz Jalāli',
'iranian-calendar-m4'  => '4e miz Jalāli',
'iranian-calendar-m5'  => '5vet miz Jalāli',
'iranian-calendar-m6'  => '6vet miz Jalāli',
'iranian-calendar-m7'  => '7vet miz Jalāli',
'iranian-calendar-m8'  => '8vet miz Jalāli',
'iranian-calendar-m9'  => '9vet miz Jalāli',
'iranian-calendar-m10' => '10vet miz Jalāli',
'iranian-calendar-m11' => '11vet miz Jalāli',
'iranian-calendar-m12' => '12vet miz Jalāli',

# Core parser functions
'unknown_extension_tag' => 'Balizenn astenn "$1" dianav',
'duplicate-defaultsort' => 'Diwallit : Frikañ a ra an alc\'hwez dre ziouer "$2" an hini a oa a-raok "$1".',

# Special:Version
'version'                          => 'Stumm',
'version-extensions'               => 'Astennoù staliet',
'version-specialpages'             => 'Pajennoù dibar',
'version-parserhooks'              => 'Galvoù dielfennañ',
'version-variables'                => 'Argemmoù',
'version-other'                    => 'Diseurt',
'version-mediahandlers'            => 'Merer danvez liesvedia',
'version-hooks'                    => 'Galvoù',
'version-extension-functions'      => "Arc'hwelioù an astennoù",
'version-parser-extensiontags'     => 'Balizenn dielfennañ o tont eus an astennoù',
'version-parser-function-hooks'    => "Galv an arc'hwelioù dielfennañ",
'version-skin-extension-functions' => "Arc'hwelioù etrefas astennoù",
'version-hook-name'                => 'Anv ar galv',
'version-hook-subscribedby'        => 'Termenet gant',
'version-version'                  => '(Stumm $1)',
'version-license'                  => 'Aotre-implijout',
'version-software'                 => 'Meziant staliet',
'version-software-product'         => 'Produ',
'version-software-version'         => 'Stumm',

# Special:FilePath
'filepath'         => 'Hent moned ur restr',
'filepath-page'    => 'Restr :',
'filepath-submit'  => 'Mont',
'filepath-summary' => 'Diskouez a ra ar bajenn-mañ hent moned klok ur restr. Diskouezet eo ar skeudennoù gant ur pizhder uhel, erounit a ra ar restroù all war-eeun gant o frogramm stag.

Merkit anv ar restr hep ar rakger "{{ns:file}} :"',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Klask ar restroù e doubl',
'fileduplicatesearch-summary'  => 'Klask restroù e doubl war diazez talvoudennoù darnek.

Merkañ anv ar restr hep ar rakger "{{ns:file}}:"',
'fileduplicatesearch-legend'   => 'Klask un doubl',
'fileduplicatesearch-filename' => 'Anv ar restr :',
'fileduplicatesearch-submit'   => 'Klask',
'fileduplicatesearch-info'     => '$1 × $2 piksel<br />Ment ar restr : $3<br />seurt MIME : $4',
'fileduplicatesearch-result-1' => 'N\'eus ket a zoubloù heñvel-poch gant ar restr "$1".',
'fileduplicatesearch-result-n' => '{{PLURAL:$2|1 doubl heñvel-poch|$2 doubl heñvel-poch}} zo gant ar restr "$1".',

# Special:SpecialPages
'specialpages'                   => 'Pajennoù dibar',
'specialpages-note'              => '----
* Pajennoù dibar boutin.
* <strong class="mw-specialpagerestricted">Pajennoù dibar miret strizh.</strong>',
'specialpages-group-maintenance' => "Rentaoù-kont trezalc'h",
'specialpages-group-other'       => 'Pajennoù dibar all',
'specialpages-group-login'       => 'Kevreañ / en em enrollañ',
'specialpages-group-changes'     => 'Kemmoù diwezhañ ha marilhoù',
'specialpages-group-media'       => 'Danevelloù ar restroù media hag an enporzhiadennoù',
'specialpages-group-users'       => 'An implijerien hag o gwirioù',
'specialpages-group-highuse'     => 'Implij stank ar pajennoù',
'specialpages-group-pages'       => 'Rolloù pajennoù',
'specialpages-group-pagetools'   => 'Ostilhoù evit ar pajennoù',
'specialpages-group-wiki'        => 'Roadennoù ar wiki hag ostilhoù',
'specialpages-group-redirects'   => 'Adkas ar pajennoù dibar',
'specialpages-group-spam'        => 'Ostilh enepstrob',

# Special:BlankPage
'blankpage'              => "Pajenn c'houllo",
'intentionallyblankpage' => 'A-ratozh e leusker gwenn ar bajenn-mañ',

# External image whitelist
'external_image_whitelist' => "  #Lezel al linenn-mañ tre evel m'emañ<pre>
#Merkañ an tammoù bommoù reoliek (ar rann zo etre ar // nemetken) a-is
#Klotañ a raint gant URLoù ar skeudennoù diavaez (gourliammet)
#En em ziskwel evel skeudennoù a raio ar re a glot, evit ar re all e vo diskwelet ul liamm war-du ar skeudenn nemetken
#Sellet e vo ouzh a linennoù a grog gant # evel ouzh notennoù
#Kizidik eo ar roll-mañ ouzh an diforc'h etre lizherennoù bihan ha lizherennoù bras

#Merkit holl rannoù ar bommoù reoliek a-us d'al linenn-mañ. Lezit al linenn ziwezhañ-mañ tre evel m'emañ</pre>",

# Special:Tags
'tags'                    => "Balizennoù ar c'hemmoù reizh",
'tag-filter'              => 'Silañ ar [[Special:Tags|balizennoù]] :',
'tag-filter-submit'       => 'Silañ',
'tags-title'              => 'Balizennoù',
'tags-intro'              => "Rollañ a ra ar bajenn-mañ ar balizennoù a c'hall ar meziant implijout da verkañ kemmoù hag an dalvoudegezh anezho.",
'tags-tag'                => 'Anv ar valizenn',
'tags-display-header'     => "Neuz e rolloù ar c'hemmoù",
'tags-description-header' => 'Deskrivadur klok ar valizenn',
'tags-hitcount-header'    => 'Kemmoù balizennet',
'tags-edit'               => 'aozañ',
'tags-hitcount'           => '$1 {{PLURAL:$1|kemm|kemm}}',

# Special:ComparePages
'comparepages'     => 'Keñveriañ pajennoù',
'compare-selector' => 'Keñveriañ stummoù ar pajennoù',
'compare-page1'    => 'Pajenn 1',
'compare-page2'    => 'Pajenn 2',
'compare-rev1'     => 'Adweladenn 1',
'compare-rev2'     => 'Adweladenn 2',
'compare-submit'   => 'Keñveriañ',

# Database error messages
'dberr-header'      => 'ur gudenn zo gant ar viki-mañ',
'dberr-problems'    => "Ho tigarez ! Kudennoù teknikel zo gant al lec'hienn-mañ.",
'dberr-again'       => 'Gortozit un nebeud munutennoù a-raok adkargañ.',
'dberr-info'        => '(Dibosupl kevreañ ouzh servijer an diaz roadennoù: $1)',
'dberr-usegoogle'   => "E-keit-se esaeit klask dre c'hGoogle.",
'dberr-outofdate'   => "Notit mat e c'hall o menegerioù dezho bezañ dispredet e-keñver ar boued zo ganeomp.",
'dberr-cachederror' => 'Un eilstumm memoret eus ar bajenn goulennet eo hemañ, gallout a ra bezañ dispredet.',

# HTML forms
'htmlform-invalid-input'       => "Kudennoù zo gant talvoudennoù zo merket ganeoc'h.",
'htmlform-select-badoption'    => "Direizh eo an dalvoudenn skrivet ganeoc'h.",
'htmlform-int-invalid'         => "N'eus ket un niver anterin eus an dalvoudenn skrivet ganeoc'h.",
'htmlform-float-invalid'       => "An dalvoudenn bet lakaet ganeoc'h n'eo ket un niver.",
'htmlform-int-toolow'          => "Skrivet hoc'h eus un dalvoudenn zo dindan an niver bihanañ aotreet a $1",
'htmlform-int-toohigh'         => "Skrivet hoc'h eus un dalvoudenn a ya dreist d'an niver uhelañ aotreet a $1",
'htmlform-required'            => 'An talvoudenn-mañ a zo ret',
'htmlform-submit'              => 'Kas',
'htmlform-reset'               => "Dizober ar c'hemmoù",
'htmlform-selectorother-other' => 'Unan all',

);
