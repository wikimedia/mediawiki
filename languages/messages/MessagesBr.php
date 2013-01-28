<?php
/** Breton (brezhoneg)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Als-Holder
 * @author Candalua
 * @author Fohanno
 * @author Fulup
 * @author Gwendal
 * @author Gwenn-Ael
 * @author Kaganer
 * @author Malafaya
 * @author VIGNERON
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
	NS_FILE             => 'Restr',
	NS_FILE_TALK        => 'Kaozeadenn_Restr',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE         => 'Patrom',
	NS_TEMPLATE_TALK    => 'Kaozeadenn_Patrom',
	NS_HELP             => 'Skoazell',
	NS_HELP_TALK        => 'Kaozeadenn_Skoazell',
	NS_CATEGORY         => 'Rummad',
	NS_CATEGORY_TALK    => 'Kaozeadenn_Rummad',
);

$namespaceAliases = array(
	'Skeudenn'            => NS_FILE,
	'Kaozeadenn_Skeudenn' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ImplijerienOberiant' ),
	'Allmessages'               => array( 'HollGemennadennoù' ),
	'Allpages'                  => array( 'AnHollBajennoù' ),
	'Ancientpages'              => array( 'PajennoùKozh' ),
	'Badtitle'                  => array( 'TitlFall' ),
	'Block'                     => array( 'Stankañ' ),
	'Blockme'                   => array( 'MaStankañ' ),
	'Booksources'               => array( 'MammennoùLevr' ),
	'BrokenRedirects'           => array( 'AdkasoùTorr' ),
	'Categories'                => array( 'Rummadoù' ),
	'ChangePassword'            => array( 'KemmañGer-tremen' ),
	'ComparePages'              => array( 'KeñveriañPajennoù' ),
	'Confirmemail'              => array( 'KadarnaatPostel' ),
	'Contributions'             => array( 'Degasadennoù' ),
	'CreateAccount'             => array( 'KrouiñKont' ),
	'Disambiguations'           => array( 'Disheñvelout' ),
	'DoubleRedirects'           => array( 'AdksaoùDoubl' ),
	'Emailuser'                 => array( 'PostelImplijer' ),
	'Export'                    => array( 'Ezporzhiañ' ),
	'Import'                    => array( 'Enporzhiañ' ),
	'LinkSearch'                => array( 'KlaskLiamm' ),
	'Listadmins'                => array( 'RollMerourien' ),
	'Listbots'                  => array( 'RollBotoù' ),
	'Listfiles'                 => array( 'RollSkeudennoù' ),
	'Listgrouprights'           => array( 'RollGwirioùStrollad' ),
	'Listredirects'             => array( 'RollañAdkasoù' ),
	'Listusers'                 => array( 'RollImplijerien' ),
	'Log'                       => array( 'Marilh' ),
	'Lonelypages'               => array( 'PajennoùEnoUnan' ),
	'Longpages'                 => array( 'PajennoùHir' ),
	'MergeHistory'              => array( 'KendeuziñIstor' ),
	'Mostlinkedtemplates'       => array( 'PatromoùImplijetañ' ),
	'Movepage'                  => array( 'AdkasPajenn' ),
	'Mycontributions'           => array( 'MaDegasadennoù' ),
	'Mypage'                    => array( 'MaFajenn' ),
	'Mytalk'                    => array( 'MaC\'haozeadennoù' ),
	'Newimages'                 => array( 'RestroùNevez', 'SkeudennoùNevez' ),
	'Newpages'                  => array( 'PajennoùNevez' ),
	'Popularpages'              => array( 'PajennoùPoblek' ),
	'Preferences'               => array( 'Penndibaboù' ),
	'Protectedpages'            => array( 'PajennoùGwarezet' ),
	'Protectedtitles'           => array( 'TitloùGwarezet' ),
	'Randompage'                => array( 'DreZegouezh' ),
	'Recentchanges'             => array( 'KemmoùDiwezhañ' ),
	'Recentchangeslinked'       => array( 'KemmoùKar' ),
	'Search'                    => array( 'Klask' ),
	'Shortpages'                => array( 'PajennoùBerr' ),
	'Specialpages'              => array( 'PajennoùDibar' ),
	'Statistics'                => array( 'Stadegoù' ),
	'Tags'                      => array( 'Balizennoù' ),
	'Unblock'                   => array( 'Distankañ' ),
	'Uncategorizedcategories'   => array( 'RummadoùDirumm' ),
	'Uncategorizedimages'       => array( 'RestroùDirumm' ),
	'Uncategorizedpages'        => array( 'PajennoùDirumm' ),
	'Uncategorizedtemplates'    => array( 'PatromoùDirumm' ),
	'Undelete'                  => array( 'Diziverkañ' ),
	'Unusedcategories'          => array( 'RummadoùDizimplij' ),
	'Unusedimages'              => array( 'RestroùDizimplij' ),
	'Unusedtemplates'           => array( 'PatromoùDizimplij' ),
	'Unwatchedpages'            => array( 'PajennoùNannEvezhiet' ),
	'Upload'                    => array( 'Pellgargañ' ),
	'Userlogin'                 => array( 'KevreañImplijer' ),
	'Userlogout'                => array( 'DigevreañImplijer' ),
	'Userrights'                => array( 'GwirioùImplijer' ),
	'Version'                   => array( 'Stumm' ),
	'Wantedcategories'          => array( 'RummadoùGoulennet' ),
	'Wantedfiles'               => array( 'RestroùGoulennet' ),
	'Wantedpages'               => array( 'LiammoùTorr' ),
	'Wantedtemplates'           => array( 'PatromoùGoulennet' ),
	'Watchlist'                 => array( 'Roll_evezhiañ' ),
	'Whatlinkshere'             => array( 'PetraGasBetekAmañ' ),
	'Withoutinterwiki'          => array( 'HepEtrewiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ADKAS', '#REDIRECT' ),
	'numberofpages'             => array( '1', 'NIVERABAJENNOU', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NIVERABENNADOU', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NIVERARESTROU', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NIVERAIMPLIJERIEN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'NIVERAIMPLIJERIENOBERIANT', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'NIVERAZEGASEDENNOU', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'NIVERALENNADENNOU', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'ANVPAJENN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ANVPAJENNSK', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ESAOUENNANV', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ESAOUENNANVSK', 'NAMESPACEE' ),
	'fullpagename'              => array( '1', 'ANVPAJENNKLOK', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ANVPAJENNKLOKSK', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ANVISPAJENN', 'SUBPAGENAME' ),
	'img_right'                 => array( '1', 'dehou', 'right' ),
	'img_left'                  => array( '1', 'kleiz', 'left' ),
	'img_none'                  => array( '1', 'netra', 'none' ),
	'img_center'                => array( '1', 'kreizenn', 'center', 'centre' ),
	'img_page'                  => array( '1', 'pajenn=$1', 'pajenn $1', 'page=$1', 'page $1' ),
	'img_sub'                   => array( '1', 'is', 'sub' ),
	'img_top'                   => array( '1', 'krec\'h', 'top' ),
	'img_middle'                => array( '1', 'kreiz', 'middle' ),
	'img_bottom'                => array( '1', 'traoñ', 'bottom' ),
	'img_link'                  => array( '1', 'liamm=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'ANVLEC\'HIENN', 'SITENAME' ),
	'server'                    => array( '0', 'SERVIJER', 'SERVER' ),
	'servername'                => array( '0', 'ANVSERVIJER', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'YEZHADUR:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'JENER:', 'GENDER:' ),
	'plural'                    => array( '0', 'LIESTER:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URLKLOK:', 'FULLURL:' ),
	'currentversion'            => array( '1', 'STUMMRED', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#YEZH:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'dibar', 'special' ),
	'pagesize'                  => array( '1', 'MENTPAJENN', 'PAGESIZE' ),
	'url_path'                  => array( '0', 'HENT', 'PATH' ),
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
'tog-underline' => 'Liammoù islinennet',
'tog-justify' => 'Rannbennadoù marzekaet',
'tog-hideminor' => "Kuzhat ar c'hemmoù nevez dister",
'tog-hidepatrolled' => "Kuzhat ar c'hemmoù evezhiet e-touez ar c'hemmoù diwezhañ",
'tog-newpageshidepatrolled' => 'Kuzhat ar pajennoù evezhiet diouzh roll ar pajennoù nevez',
'tog-extendwatchlist' => 'Astenn ar roll evezhiañ a-benn diskouez an holl gemmoù ha neket ar re ziwezhañ hepken.',
'tog-usenewrc' => "Diskouez ar c'hemmoù nevez en ur feson kempennoc'h (rekis eo JavaScript)",
'tog-numberheadings' => 'Niverenniñ emgefre an titloù',
'tog-showtoolbar' => 'Diskouez ar varrenn gant ar meuzioù skridaozañ',
'tog-editondblclick' => 'Daouglikañ evit kemmañ ur bajenn (JavaScript)',
'tog-editsection' => 'Kemmañ ur rann dre al liammoù [kemmañ]',
'tog-editsectiononrightclick' => 'Kemmañ ur rann dre glikañ a-zehou<br /> war titl ar rann',
'tog-showtoc' => 'Diskouez an daolenn<br /> (evit ar pennadoù zo ouzhpenn 3 rann enno)',
'tog-rememberpassword' => "Derc'hel soñj eus ma ger-tremen war an urzhiataer-mañ (evit $1 devezh{{PLURAL:$1||}} d'ar muiañ)",
'tog-watchcreations' => "Ouzhpennañ ar pajennoù krouet ganin da'm roll evezhiañ",
'tog-watchdefault' => "Ouzhpennañ ar pajennoù kemmet ganin da'm roll evezhiañ",
'tog-watchmoves' => "Ouzhpennañ ar pajennoù dilec'hiet ganin da'm roll evezhiañ",
'tog-watchdeletion' => "Ouzhpennañ ar pajennoù diverket ganin da'm roll evezhiañ",
'tog-minordefault' => "Sellet ouzh ar c'hemmoù degaset ganin<br /> evel kemmoù dister dre ziouer",
'tog-previewontop' => 'Rakwelet tres ar bajenn a-us ar prenestr skridaozañ',
'tog-previewonfirst' => 'Rakwelet tres ar bajenn kerkent hag an aozadenn gentañ',
'tog-nocache' => 'Diweredekaat krubuilh ar pajennoù gant ar merdeer',
'tog-enotifwatchlistpages' => 'Kas ur postel din pa vez kemmet ur bajenn zo war ma roll evezhiañ',
'tog-enotifusertalkpages' => 'Kas ur postel din pa vez kemmet ma fajenn gaozeal',
'tog-enotifminoredits' => 'Kas ur postel din, ha pa vije evit kemenn kemmoù dister',
'tog-enotifrevealaddr' => "Lakaat ma chomlec'h postel war wel er posteloù kemenn-diwall",
'tog-shownumberswatching' => 'Diskouez an niver a lennerien',
'tog-oldsig' => 'Ar sinadur zo evit poent :',
'tog-fancysig' => 'Ober gant ar sinadur evel pa vefe wikitestenn (hep liamm emgefre)',
'tog-externaleditor' => "Ober gant ur skridaozer diavaez dre ziouer (evit arbennigourien hepken rak ezhomm zo arventenniñ hoc'h urzhiataer evit se. [//www.mediawiki.org/wiki/Manual:External_editors Titouroù all.])",
'tog-externaldiff' => "Ober gant ur c'heñverier diavaez dre ziouer (evit arbennigourien hepken rak ezhomm zo arventenniñ hoc'h urzhiataer evit se. [//www.mediawiki.org/wiki/Manual:External_editors Titouroù all.])",
'tog-showjumplinks' => 'Gweredekaat al liammoù moned "lammat da"',
'tog-uselivepreview' => 'Implijout Rakwelet prim (JavaScript) (taol-arnod)',
'tog-forceeditsummary' => 'Kemenn din pa ne skrivan netra er stern diverrañ',
'tog-watchlisthideown' => "Kuzhat ma c'hemmoù er rollad evezhiañ",
'tog-watchlisthidebots' => 'Kuzhat kemmoù ar botoù er rollad evezhiañ',
'tog-watchlisthideminor' => "Kuzhat ar c'hemmoù dister er rollad evezhiañ",
'tog-watchlisthideliu' => 'Er roll evezhiañ, kuzhat kemmoù an implijerien kevreet.',
'tog-watchlisthideanons' => 'Er roll evezhiañ, kuzhat kemmoù an implijerien dianav',
'tog-watchlisthidepatrolled' => "Kuzhat ar c'hemmoù evezhiet diouzh ar roll evezhiañ",
'tog-ccmeonemails' => 'Kas din un eilskrid eus ar posteloù a gasan da implijerien all',
'tog-diffonly' => "Arabat diskouez danvez ar pennadoù dindan an diforc'hioù",
'tog-showhiddencats' => 'Diskouez ar rummadoù kuzhet',
'tog-noconvertlink' => 'Diweredekaat amdroadur an titloù',
'tog-norollbackdiff' => 'Na ziskouez an diff goude un distaoladenn',

'underline-always' => 'Atav',
'underline-never' => 'Morse',
'underline-default' => 'Merdeer dre ziouer',

# Font style option in Special:Preferences
'editfont-style' => 'Stil font an takad skridaozañ :',
'editfont-default' => 'Diouzh ar merdeer',
'editfont-monospace' => 'Font unesaouennet',
'editfont-sansserif' => 'Font hep-serif',
'editfont-serif' => 'Font serif',

# Dates
'sunday' => 'Sul',
'monday' => 'Lun',
'tuesday' => 'Meurzh',
'wednesday' => "Merc'her",
'thursday' => 'Yaou',
'friday' => 'Gwener',
'saturday' => 'Sadorn',
'sun' => 'Sul',
'mon' => 'Lun',
'tue' => 'Meu',
'wed' => 'Mer',
'thu' => 'Meu',
'fri' => 'Gwe',
'sat' => 'Sad',
'january' => 'Genver',
'february' => "C'hwevrer",
'march' => 'Meurzh',
'april' => 'Ebrel',
'may_long' => 'Mae',
'june' => 'Mezheven',
'july' => 'Gouere',
'august' => 'Eost',
'september' => 'Gwengolo',
'october' => 'Here',
'november' => 'Du',
'december' => 'Kerzu',
'january-gen' => 'Genver',
'february-gen' => "C'hwevrer",
'march-gen' => 'Meurzh',
'april-gen' => 'Ebrel',
'may-gen' => 'Mae',
'june-gen' => 'Mezheven',
'july-gen' => 'Gouere',
'august-gen' => 'Eost',
'september-gen' => 'Gwengolo',
'october-gen' => 'Here',
'november-gen' => 'Du',
'december-gen' => 'Kerzu',
'jan' => 'Gen',
'feb' => "C'hwe",
'mar' => 'Meu',
'apr' => 'Ebr',
'may' => 'Mae',
'jun' => 'Mez',
'jul' => 'Gou',
'aug' => 'Eos',
'sep' => 'Gwe',
'oct' => 'Her',
'nov' => 'Du',
'dec' => 'Kzu',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Rummad |Rummad }}',
'category_header' => 'Niver a bennadoù er rummad "$1"',
'subcategories' => 'Isrummad',
'category-media-header' => 'Restroù liesvedia er rummad "$1"',
'category-empty' => "''N'eus na pajenn na media ebet er rummad-mañ evit ar mare.''",
'hidden-categories' => '{{PLURAL:$1|Rummad kuzhet|Rummad kuzhet}}',
'hidden-category-category' => 'Rummadoù kuzhet',
'category-subcat-count' => "{{PLURAL:$2|N'eus er rummad-mañ nemet an isrummad da-heul.|{{PLURAL:$1|isrummad|$1 isrummad}} zo d'ar rummad-mañ diwar un hollad a $2.}}",
'category-subcat-count-limited' => 'Er rummad-mañ e kaver an {{PLURAL:$1|isrummad-se|$1 isrummadoù-se}}.',
'category-article-count' => "{{PLURAL:$2|N'eus er rummad-mañ nemet ar bajenn da-heul.|Emañ ar {{PLURAL:$1|bajenn da-heul|$1 pajenn da-heul}} er rummad-mañ, diwar un hollad a $2.}}",
'category-article-count-limited' => '{{PLURAL:$1|Emañ ar bajenn|Emañ an $1 pajenn}} da-heul er rummad-mañ.',
'category-file-count' => "{{PLURAL:$2|N'eus er rummad-mañ nemet ar restr da-heul.|Emañ ar {{PLURAL:$1|restr|$1 restr}} da-heul er rummad-mañ, diwar un hollad a $2.}}",
'category-file-count-limited' => '{{PLURAL:$1|Emañ ar restr|Emañ an $1 restr}} da-heul er rummad-mañ.',
'listingcontinuesabbrev' => "(war-lerc'h)",
'index-category' => 'Pajennoù menegeret',
'noindex-category' => "Pajennoù n'int ket menegeret",
'broken-file-category' => 'Pajennoù enno liamm torr war-zu restroù',

'about' => 'Diwar-benn',
'article' => 'Pennad',
'newwindow' => '(digeriñ en ur prenestr nevez)',
'cancel' => 'Nullañ',
'moredotdotdot' => "Ha muioc'h c'hoazh...",
'mypage' => 'Ma zammig pajenn',
'mytalk' => "Ma c'haozeadennoù",
'anontalk' => "Kaozeal gant ar chomlec'h IP-mañ",
'navigation' => 'Merdeiñ',
'and' => '&#32;ha(g)',

# Cologne Blue skin
'qbfind' => 'Klask',
'qbbrowse' => 'Furchal',
'qbedit' => 'Kemmañ',
'qbpageoptions' => 'Pajenn an dibaboù',
'qbpageinfo' => 'Pajenn gelaouiñ',
'qbmyoptions' => 'Ma dibaboù',
'qbspecialpages' => 'Pajennoù dibar',
'faq' => 'FAG',
'faqpage' => 'Project:FAG',

# Vector skin
'vector-action-addsection' => 'Rannbennad nevez',
'vector-action-delete' => 'Diverkañ',
'vector-action-move' => 'Adenvel',
'vector-action-protect' => 'Gwareziñ',
'vector-action-undelete' => 'Diziverkañ',
'vector-action-unprotect' => 'Cheñch gwarez',
'vector-simplesearch-preference' => "Aotren ar c'hinnigoù klask gwellaet (gant Vektor nemetken)",
'vector-view-create' => 'Krouiñ',
'vector-view-edit' => 'Kemmañ',
'vector-view-history' => 'Gwelet an istor',
'vector-view-view' => 'Lenn',
'vector-view-viewsource' => 'Sellet ouzh tarzh an destenn',
'actions' => 'Oberoù',
'namespaces' => 'Esaouennoù anv',
'variants' => 'Adstummoù',

'errorpagetitle' => 'Fazi',
'returnto' => "Distreiñ d'ar bajenn $1.",
'tagline' => 'Eus {{SITENAME}}',
'help' => 'Skoazell',
'search' => 'Klask',
'searchbutton' => 'Klask',
'go' => 'Kas',
'searcharticle' => 'Mont',
'history' => 'Istor ar bajenn',
'history_short' => 'Istor',
'updatedmarker' => 'kemmet abaoe ma zaol-sell diwezhañ',
'printableversion' => 'Stumm da voullañ',
'permalink' => "Chomlec'h ar stumm-mañ",
'print' => 'Moullañ',
'view' => 'Gwelet',
'edit' => 'Kemmañ',
'create' => 'Krouiñ',
'editthispage' => 'Kemmañ ar bajenn-mañ',
'create-this-page' => 'Krouiñ ar bajenn-mañ',
'delete' => 'Diverkañ',
'deletethispage' => 'Diverkañ ar bajenn-mañ',
'undelete_short' => "Diziverkañ {{PLURAL:$1|ur c'hemm|$1 kemm}}",
'viewdeleted_short' => "Gwelet {{PLURAL:$1|ur c'hemm diverket|$1 kemm diverket}}",
'protect' => 'Gwareziñ',
'protect_change' => 'kemmañ',
'protectthispage' => 'Gwareziñ ar bajenn-mañ',
'unprotect' => 'Cheñch gwarez',
'unprotectthispage' => 'Cheñch live gwareziñ ar bajenn-mañ',
'newpage' => 'Pajenn nevez',
'talkpage' => 'Pajenn gaozeal',
'talkpagelinktext' => 'Kaozeal',
'specialpage' => 'Pajenn dibar',
'personaltools' => 'Ostilhoù personel',
'postcomment' => 'Rann nevez',
'articlepage' => 'Sellet ouzh ar pennad',
'talk' => 'Kaozeadenn',
'views' => 'Gweladennoù',
'toolbox' => 'Boest ostilhoù',
'userpage' => 'Pajenn implijer',
'projectpage' => 'Pajenn meta',
'imagepage' => 'Gwelet pajenn ar restr',
'mediawikipage' => "Sellet ouzh pajenn ar c'hemennadennoù",
'templatepage' => 'Gwelet patrom ar bajenn',
'viewhelppage' => 'Gwelet ar bajenn skoazell',
'categorypage' => 'Gwelet pajenn ar rummadoù',
'viewtalkpage' => 'Pajenn gaozeal',
'otherlanguages' => 'Yezhoù all',
'redirectedfrom' => '(Adkaset eus $1)',
'redirectpagesub' => 'Pajenn adkas',
'lastmodifiedat' => "Kemmoù diwezhañ degaset d'ar bajenn-mañ, d'an $1 da $2.",
'viewcount' => 'Sellet ez eus bet {{PLURAL:$1|$1 wezh|$1 gwezh}} ouzh ar bajenn-mañ.',
'protectedpage' => 'Pajenn warezet',
'jumpto' => 'Mont da :',
'jumptonavigation' => 'merdeiñ',
'jumptosearch' => 'klask',
'view-pool-error' => 'Ho tigarez, soulgarget eo ar servijerioù evit poent.
Re a implijerien a glask mont war ar bajenn-mañ war un dro.
Gortozit ur pennadig a-raok klask mont war ar bjann-mañ en-dro.

$1',
'pool-timeout' => "Aet eur dreist d'an termen gortoz evit ar stankadenn",
'pool-queuefull' => 'Soulgarget eo ar servijerioù',
'pool-errorunknown' => 'Fazi dianav',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Diwar-benn {{SITENAME}}',
'aboutpage' => 'Project:Diwar-benn',
'copyright' => "Danvez a c'haller implijout dindan $1.",
'copyrightpage' => '{{ns:project}}:Gwirioù aozer (Copyright)',
'currentevents' => 'Keleier',
'currentevents-url' => 'Project:Keleier',
'disclaimers' => 'Kemennoù',
'disclaimerpage' => 'Project:Kemenn hollek',
'edithelp' => 'Skoazell',
'edithelppage' => 'Help:Penaos kemmañ traoù er pennadoù',
'helppage' => 'Help:Skoazell',
'mainpage' => 'Degemer',
'mainpage-description' => 'Degemer',
'policy-url' => 'Project:Reolennoù',
'portal' => 'Porched ar gumuniezh',
'portal-url' => 'Project:Porched ar gumuniezh',
'privacy' => 'Reolennoù prevezded',
'privacypage' => 'Project:Reolennoù prevezded',

'badaccess' => 'Fazi aotre',
'badaccess-group0' => "N'oc'h ket aotreet da seveniñ ar pezh hoc'h eus goulennet.",
'badaccess-groups' => 'Miret eo an ober-mañ evit an implijerien zo {{PLURAL:$2|er strollad|en unan eus ar strolladoù}} : $1.',

'versionrequired' => 'Rekis eo Stumm $1 MediaWiki',
'versionrequiredtext' => 'Rekis eo stumm $1 MediaWiki evit implijout ar bajenn-mañ. Sellit ouzh [[Special:Version]]',

'ok' => 'Mat eo',
'retrievedfrom' => 'Adtapet diwar « $1 »',
'youhavenewmessages' => "$1 zo ganeoc'h ($2).",
'newmessageslink' => 'Kemennoù nevez',
'newmessagesdifflink' => "Diforc'hioù e-keñver ar stumm kent",
'youhavenewmessagesfromusers' => '$1 ho peus eus {{PLURAL:$3|un implijer all|$3 implijer}} ($2).',
'youhavenewmessagesmanyusers' => ' $1 ho peus implijerien a-leizh  ($2).',
'newmessageslinkplural' => "{{PLURAL:$1ur c'hemennad nevez|kemennadoù nevez}}",
'newmessagesdifflinkplural' => '{{PLURAL:$1|kemennad diwezhañ|kemennadoù diwezhañ}}',
'youhavenewmessagesmulti' => "Kemennoù nevez zo ganeoc'h war $1",
'editsection' => 'kemmañ',
'editold' => 'kemmañ',
'viewsourceold' => 'gwelet ar vammenn',
'editlink' => 'kemmañ',
'viewsourcelink' => 'gwelet an tarzh',
'editsectionhint' => 'Kemmañ ar rann : $1',
'toc' => 'Taolenn',
'showtoc' => 'diskouez',
'hidetoc' => 'kuzhat',
'collapsible-collapse' => 'Pakañ',
'collapsible-expand' => 'Dispakañ',
'thisisdeleted' => 'Diskouez pe diziverkañ $1 ?',
'viewdeleted' => 'Gwelet $1?',
'restorelink' => "{{PLURAL:$1|ur c'hemm diverket|$1 kemm diverket}}",
'feedlinks' => 'Lanv :',
'feed-invalid' => 'Seurt lanv direizh.',
'feed-unavailable' => "N'haller ket implijout al lanvadoù koumanatiñ",
'site-rss-feed' => 'Lanv RSS evit $1',
'site-atom-feed' => 'Lanv Atom evit $1',
'page-rss-feed' => 'Lanv RSS evit "$1"',
'page-atom-feed' => 'Lanv Atom evit "$1"',
'red-link-title' => "$1 (n'eus ket eus ar bajenn-mañ)",
'sort-descending' => 'Urzhiañ war-draoñ',
'sort-ascending' => 'Urzhiañ war-laez',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pajenn',
'nstab-user' => 'Pajenn implijer',
'nstab-media' => 'Media',
'nstab-special' => 'Pajenn dibar',
'nstab-project' => 'Diwar-benn',
'nstab-image' => 'Skeudenn',
'nstab-mediawiki' => 'Kemennadenn',
'nstab-template' => 'Patrom',
'nstab-help' => 'Skoazell',
'nstab-category' => 'Rummad',

# Main script and global functions
'nosuchaction' => 'Ober dianav',
'nosuchactiontext' => "Direizh eo an ober spisaet en URL.
Marteze hoc'h eus graet ur fazi bizskrivañ en URL pe heuliet ul liamm kamm.
Marteze zo un draen er meziant implijet gant {{SITENAME}} ivez.",
'nosuchspecialpage' => "N'eus ket eus ar bajenn dibar-mañ",
'nospecialpagetext' => "<strong>Goulennet hoc'h eus ur bajenn dibar n'eus ket anezhi.</strong>

Ur roll eus ar pajennoù dibar reizh a c'hallit kavour war [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Fazi',
'databaseerror' => 'Fazi bank roadennoù',
'dberrortext' => 'C\'hoarvezet ez eus ur fazi ereadur eus ar reked er bank roadennoù, ar pezh a c\'hall talvezout ez eus un draen er meziant.
Setu ar goulenn bet pledet gantañ da ziwezhañ :
<blockquote><code>$1</code></blockquote>
adal an arc\'hwel "<code>$2</code>".
Adkaset eo bet ar fazi "<samp>$3: $4</samp>" gant ar bank roadennoù.',
'dberrortextcl' => 'Ur fazi ereadur zo en ur reked savet ouzh ar bank roadennoù.
Setu ar goulenn bet pledet gantañ da ziwezhañ :
"$1"
adal an arc\'hwel "$2"
Adkaset eo bet ar fazi "$3 : $4" gant ar bank roadennoù.',
'laggedslavemode' => "Diwallit : marteze a-walc'h n'emañ ket ar c'hemmoù diwezhañ war ar bajenn-mañ",
'readonly' => 'Hizivadurioù stanket war ar bank roadennoù',
'enterlockreason' => 'Merkit perak eo stanket hag istimit pegeit e chomo evel-henn',
'readonlytext' => "Evit poent n'haller ket ouzhpennañ pe gemmañ netra er bank roadennoù mui. Un tamm kempenn boutin d'ar bank moarvat. goude-se e vo plaen an traoù en-dro.

Setu displegadenn ar merour bet prennet ar bank gantañ : $1",
'missing-article' => "N'eo ket bet kavet gant an diaz titouroù testenn ur bajenn en dije dleet kavout hag anvet \"\$1\" \$2.

Peurliesañ e c'hoarvez evit bezañ heuliet liamm dispredet un diforc'h pe an istor war-du ur bajenn bet diverket abaoe.

Mard ned eo ket se eo, hoc'h eus marteze kavet un draen er meziant.
Kasit keloù d'ur [[Special:ListUsers/sysop|merer]], en ur verkañ dezhañ chomlec'h an URL.",
'missingarticle-rev' => '(adweladenn # : $1)',
'missingarticle-diff' => '(Dif : $1, $2)',
'readonly_lag' => "Stanket eo bet ar bank roadennoù ent emgefre p'emañ an eilservijerioù oc'h adpakañ o dale e-keñver ar pennservijer",
'internalerror' => 'Fazi diabarzh',
'internalerror_info' => 'Fazi diabarzh : $1',
'fileappenderrorread' => 'Dibosupl eo lenn "$1" e-pad an ensoc\'hañ.',
'fileappenderror' => 'Dibosupl ouzhpennañ « $1 » da « $2 ».',
'filecopyerror' => 'Dibosupl eilañ "$1" war-du "$2".',
'filerenameerror' => 'Dibosupl da adenvel « $1 » e « $2 ».',
'filedeleteerror' => 'Dibosupl eo diverkañ « $1 ».',
'directorycreateerror' => 'N\'eus ket bet gallet krouiñ kavlec\'h "$1".',
'filenotfound' => 'N\'haller ket kavout ar restr "$1".',
'fileexistserror' => 'Dibosupl skrivañ war ar restr "$1": bez\'ez eus eus ar restr-se dija',
'unexpected' => 'Talvoudenn dic\'hortoz : "$1"="$2".',
'formerror' => 'Fazi: Dibosupl eo kinnig ar furmskrid',
'badarticleerror' => "N'haller ket seveniñ an ober-mañ war ar bajenn-mañ.",
'cannotdelete' => 'Dibosupl diverkañ ar bajenn pe ar restr "$1".
Marteze e o bet diverket gant unan bennak all dija.',
'cannotdelete-title' => 'N\'haller ket diverkañ ar bajenn "$1"',
'delete-hook-aborted' => "Nullet ar c'hemmañ gant un astenn.
Abeg dianav.",
'badtitle' => 'Titl fall',
'badtitletext' => "Faziek pe c'houllo eo titl ar bajenn goulennet; pe neuze eo faziek al liamm etreyezhel pe etrewiki.
Marteze ez eus ennañ arouezennoù n'haller ket degemer en titloù.",
'perfcached' => "Krubuilhet eo ar roadennoù da-heul ha marteze n'int ket bet hizivaet. D'ar muiañ e c'haller kaout {{PLURAL:$1|un disoc'h|$1 disoc'h}} er grubuilh.",
'perfcachedts' => "Krubuilhet eo ar roadennoù da-heul hag hizivaet int bet da ziwezhañ d'an $1. D'ar muiañ e c'haller kaout {{PLURAL:$4|un disoc'h|$4 disoc'h}} er grubuilh.",
'querypage-no-updates' => 'Diweredekaet eo an hizivaat evit ar bajenn-mañ. Evit poent ne vo ket nevesaet ar roadennoù amañ.',
'wrong_wfQuery_params' => "Arventennoù faziek war an urzhiad wfQuery()<br />
Arc'hwel : $1<br />
Goulenn : $2",
'viewsource' => 'Sellet ouzh tarzh an destenn',
'viewsource-title' => 'Gwelet an tarzh evit $1',
'actionthrottled' => 'Ober daleet',
'actionthrottledtext' => "A-benn dizarbenn ar strob, n'haller ket implijout an ober-mañ re alies en ur frapad amzer lakaet, hag aet oc'h dreist ar muzul. Klaskit en-dro a-benn un nebeut munutennoù.",
'protectedpagetext' => 'Prennet eo bet ar bajenn-mañ kuit na vefe skrivet pe cheñchet un dra bennak enni',
'viewsourcetext' => 'Gallout a rit gwelet hag eilañ danvez ar bajenn-mañ',
'viewyourtext' => "Gallout a rit gwelet hag eilañ mammenn ho '''kemmoù''' d'ar bajenn-mañ :",
'protectedinterface' => 'Testenn ar bajenn-mañ a dalvez evit etrefas ar meziant er wiki-mañ. Setu perak eo bet gwarezet ar bajenn.',
'editinginterface' => "'''Diwallit :''' Emaoc'h o kempenn ur bajenn a dalvez da sevel skridoù evit etrefas ar meziant. Ar c'hemmoù graet d'ar bajenn-mañ a cheñcho etrefas an holl implijerien. Mar fell deoc'h skoazellañ evit treiñ traoù, soñjit kentoc'h implijout [//translatewiki.net/wiki/Main_Page?setlang=br translatewiki.net], ar raktres evit lec'helaat MediaWiki.",
'sqlhidden' => '(Reked SQL kuzhet)',
'cascadeprotected' => 'Gwarezet eo ar bajenn-mañ; n\'haller ket kemmañ anezhi ken p\'he c\'haver {{PLURAL:$1|er bajenn|e-mesk ar pajennoù}} da-heul zo bet gwarezet en ur zibab an dibarzh "skalierad" :
$2',
'namespaceprotected' => "N'oc'h ket aotreet da gemmañ pajennoù an esaouenn anv '''$1'''.",
'customcssprotected' => "N'oc'h ket aotreet da gemmañ ar bajenn CSS-mañ rak kavout a reer enni arventennoù personel un implijer all.",
'customjsprotected' => "N'oc'h ket aotreet da gemmañ ar bajenn JavaScript-mañ rak kavout a reer enni arventennoù personel un implijer all.",
'ns-specialprotected' => "N'haller ket kemmañ ar pajennoù en esaouenn anv {{ns:special}}.",
'titleprotected' => "Gwarezet eo bet an titl-mañ p'eo bet krouet gant [[User:$1|$1]].
Setu amañ perak ''$2''.",
'filereadonlyerror' => 'Dibosupl kemmañ ar restr "$1" abalamour m\'emañ ar c\'havlec\'h restroù "$2" e mod lenn nemetken.

"\'\'$3\'\'" eo an abeg roet gant ar merour en deus prennet anezhi.',
'invalidtitle-knownnamespace' => 'Titl direizh gant an esaouenn anv "$2" hag an destenn "$3"',
'invalidtitle-unknownnamespace' => 'Titl direizh gant an niverenn esaouenn anv $1 hag an destenn "$2" dianav',
'exception-nologin' => "N'oc'h ket kevreet",
'exception-nologin-text' => "Ar bajenn-mañ pe an ober-mañ a c'houlenn e vefec'h kevreet er wiki-mañ.",

# Virus scanner
'virus-badscanner' => "Kefluniadur fall : skanner viruzoù dianav : ''$1''",
'virus-scanfailed' => "Skannadenn c'hwitet (kod $1)",
'virus-unknownscanner' => 'diviruzer dianav :',

# Login and logout pages
'logouttext' => "'''Digevreet oc'h bremañ.'''

Gallout a rit kenderc'hel da implijout {{SITENAME}} en un doare dizanv, pe [[Special:UserLogin|kevreañ en-dro]] gant an hevelep anv pe un anv all mar fell deoc'h.
Notit mat e c'hallo pajennoù zo kenderc'hel da vezañ diskwelet evel pa vefec'h kevreet c'hoazh, betek ma vo riñset krubuilh ho merdeer ganeoc'h.",
'welcomecreation' => '== Degemer mat, $1! ==

Krouet eo bet ho kont implijer.
Na zisoñjit ket resisaat ho [[Special:Preferences|penndibaboù evit {{SITENAME}}]].',
'yourname' => 'Anv implijer :',
'yourpassword' => 'Ger-tremen :',
'yourpasswordagain' => 'Skrivit ho ker-tremen en-dro',
'remembermypassword' => "Derc'hel soñj eus ma ger-tremen war an urzhiataer-mañ (evit $1 devezh{{PLURAL:$1||}} d'ar muiañ)",
'securelogin-stick-https' => 'Chom kevreet da HTTPS goude bezañ bet kevreet',
'yourdomainname' => 'Ho tomani',
'password-change-forbidden' => "Ne c'hallit ket kemmañ ar gerioù-tremen er wiki-mañ.",
'externaldberror' => "Pe ez eus bet ur fazi gwiriekaat diavaez er bank titouroù pe n'oc'h ket aotreet da nevesaat ho kont diavaez.",
'login' => 'Kevreañ',
'nav-login-createaccount' => 'Krouiñ ur gont pe kevreañ',
'loginprompt' => "Ret eo deoc'h bezañ gweredekaet an toupinoù a-benn gellout kevreañ ouzh {{SITENAME}}.",
'userlogin' => 'Kevreañ / krouiñ ur gont',
'userloginnocreate' => 'Kevreañ',
'logout' => 'Digevreañ',
'userlogout' => 'Digevreañ',
'notloggedin' => 'Digevreet',
'nologin' => "N'hoc'h eus kont ebet ? '''$1'''.",
'nologinlink' => 'Krouiñ ur gont',
'createaccount' => 'Krouiñ ur gont nevez',
'gotaccount' => "Ur gont zo ganeoc'h dija ? '''$1'''.",
'gotaccountlink' => 'Kevreañ',
'userlogin-resetlink' => "Ha disoñjet eo bet ho titouroù kevreañ ganeoc'h ?",
'createaccountmail' => 'dre bostel',
'createaccountreason' => 'Abeg :',
'badretype' => 'Ne glot ket ar gerioù-tremen an eil gant egile.',
'userexists' => "Implijet eo an anv implijer lakaet ganeoc'h dija.
Dibabit un anv all mar plij.",
'loginerror' => 'Kudenn gevreañ',
'createaccounterror' => 'Dibosupl krouiñ ar gont : $1',
'nocookiesnew' => "Krouet eo bet ar gont implijer met n'oc'h ket kevreet. {{SITENAME}} a implij toupinoù evit ar c'hevreañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha da gevreañ en-dro.",
'nocookieslogin' => "{{SITENAME}} a implij toupinoù evit kevreañ met diweredekaet eo an toupinoù ganeoc'h. Trugarez da weredekaat anezho ha da gevreañ en-dro.",
'nocookiesfornew' => "N'eo ket bet krouet ar gont implijer peogwir n'eus ket bet gallet gwiriañ an orin anezhi.
Gwiriit eo bet gweredekaet an toupinoù, adkargit ar bajenn ha klaskit en-dro.",
'noname' => "N'hoc'h eus lakaet anv implijer ebet.",
'loginsuccesstitle' => "Kevreet oc'h.",
'loginsuccess' => "'''Kevreet oc'h bremañ ouzh {{SITENAME}} evel \"\$1\".'''",
'nosuchuser' => 'N\'eus ket eus an implijer "$1".
Kizidik eo anv an implijer ouzh ar pennlizherennoù
Gwiriit eo bet skrivet mat an anv ganeoc\'h pe [[Special:UserLogin/signup|krouit ur gont nevez]].',
'nosuchusershort' => "N'eus perzhiad ebet gantañ an anv « $1 ». Gwiriit ar reizhskrivadur.",
'nouserspecified' => "Ret eo deoc'h spisaat un anv implijer.",
'login-userblocked' => "Stanket eo an implijer-mañ. N'eo ket aotret da gevreañ.",
'wrongpassword' => 'Ger-tremen kamm. Klaskit en-dro.',
'wrongpasswordempty' => 'Ger-tremen ebet. Lakait unan mar plij.',
'passwordtooshort' => '{{PLURAL:$1|1 arouezenn|$1 arouezenn}} hir a rank bezañ ar gerioù-tremen da nebeutañ.',
'password-name-match' => "Rankout a ra ho ker-tremen bezañ disheñvel diouzh hoc'h anv implijer.",
'password-login-forbidden' => 'Berzet eo ober gant an anv-implijer hag ar ger-tremen-mañ.',
'mailmypassword' => 'Kasit din ur ger-tremen nevez',
'passwordremindertitle' => 'Ho ker-tremen berrbad nevez evit {{SITENAME}}',
'passwordremindertext' => "Unan bennak (c'hwi moarvat gant ar chomlec'h IP \$1)
en deus goulennet ma vo kaset dezhañ ur ger-tremen nevez evit {{SITENAME}} (\$4).
Savet ez eus bet ur ger-tremen da c'hortoz evit an implijer \"\$2\" hag a zo \"\$3\".
Mard eo se a felle deoc'h ober e vo ret deoc'h kevreañ ha cheñch ho ker-tremen bremañ. Didalvez e vo ho ker ker-tremen da c'hortoz a-benn {{PLURAL:\$5|un devezh|\$5 devezh}}

Mard eo bet graet ar goulenn gant unan bennak all, pe m'hoc'h eus soñj eus ho ker-tremen bremañ ha
ma ne fell ket deoc'h cheñch anezhañ ken, e c'hallit leuskel ar postel-mañ a-gostez ha kenderc'hel d'ober gant ho ker-tremen kozh.",
'noemail' => 'N\'eus bet enrollet chomlec\'h postel ebet evit an implijer "$1".',
'noemailcreate' => "Ret eo deoc'h merkañ ur chomlec'h postel reizh",
'passwordsent' => 'Kaset ez eus bet ur ger-tremen nevez da chomlec\'h postel an implijer "$1".
Trugarez deoc\'h da gevreañ kerkent ha ma vo bet resevet ganeoc\'h.',
'blocked-mailpassword' => "N'haller ket kemmañ pajennoù gant ar chomlec'h IP-mañ ken rak stanket eo bet. Gant se n'hallit ket implijout an arc'hwel adtapout gerioù-tremen, kuit m'en em ledfe gwallimplijoù.",
'eauthentsent' => "Kaset ez eus bet ur postel kadarnaat war-du ar chomlec'h postel spisaet.
A-raok na vije kaset postel ebet d'ar gont-se e vo ret deoc'h heuliañ ar c'huzulioù merket er postel resevet evit kadarnaat ez eo mat ho kont deoc'h.",
'throttled-mailpassword' => "Kaset ez eus bet deoc'h ur postel degas soñj e-kerzh an
{{PLURAL:$1|eurvezh|$1 eurvezh}} ziwezhañ. Evit mirout ouzh nep gaou ne gaser nemet ur postel a-seurt-se dre {{PLURAL:$1|eurvezh|$1 eurvezh}}.",
'mailerror' => 'Fazi en ur gas ar postel : $1',
'acct_creation_throttle_hit' => "{{PLURAL:$1|1 gont|$1 kont}} zo bet krouet c'hoazh nevez zo dre ho chomlec'h IP gant gweladennerien d'ar wiki-mañ, ar pezh zo an niver brasañ aotreet. Dre se, n'hall ket ket ar weladennerien a implij an IP-mañ krouiñ kontoù mui evit ar mare.",
'emailauthenticated' => "Gwiriet eo bet ho chomlec'h postel d'an $2 da $3.",
'emailnotauthenticated' => "N'eo ket bet gwiriekaet ho chomlec'h postel evit c'hoazh. Ne vo ket tu da gas postel ebet deoc'h evit hini ebet eus an dezverkoù dindan.",
'noemailprefs' => "Merkit ur chomlec'h postel mar fell deoc'h ez afe an arc'hwelioù-mañ en-dro.",
'emailconfirmlink' => "Kadarnait ho chomlec'h postel",
'invalidemailaddress' => "N'haller ket degemer ar chomlec'h postel-mañ rak faziek eo e furmad evit doare.
Merkit ur chomlec'h reizh pe goullonderit ar vaezienn-mañ.",
'cannotchangeemail' => "N'haller ket cheñch chomlec'hioù postel ar c'hontoù war ar wiki-mañ.",
'emaildisabled' => "N'haller ket kas posteloù dre al lec'hienn-mañ.",
'accountcreated' => 'Kont krouet',
'accountcreatedtext' => 'Krouet eo bet kont implijer $1.',
'createaccount-title' => 'Krouiñ ur gont war {{SITENAME}}',
'createaccount-text' => 'Unan bennak en deus krouet ur gont gant ho chomlec\'h postel war {{SITENAME}} ($4) zo e anv "$2" hag a ra gant ar ger-tremen "$3".
Mat e vefe deoc\'h kevreañ ha cheñch ho ker-tremen bremañ.

Na daolit ket evezh ouzh ar c\'hemenn-mañ m\'eo bet krouet ar gont dre fazi.',
'usernamehasherror' => "N'haller ket ober gant an arouezenn # en anvioù an implijerien",
'login-throttled' => "Betek re oc'h heus klasket kevreañ en aner.
Gortozit a-raok klask en-dro.",
'login-abort-generic' => 'Dibosupl ho kevreañ - Dilezet',
'loginlanguagelabel' => 'Yezh : $1',
'suspicious-userlogout' => 'Distaolet eo bet ho koulenn digevreañ rak kaset e oa bet gant ur merdeer direizhet pe krubuilhadenn ur proksi, evit doare.',

# E-mail sending
'php-mail-error-unknown' => "Fazi dianav en arc'hwel postel () PHP",
'user-mail-no-addy' => "Klasket kas ur postel hep lakaat ur chomlec'h postel.",

# Change password dialog
'resetpass' => 'Cheñch ar ger-tremen',
'resetpass_announce' => "Enskrivet oc’h bet dre ur ger-tremen da c'hortoz kaset deoc'h dre bostel. A-benn bezañ enrollet da vat e rankit spisaat ur ger-tremen nevez amañ :",
'resetpass_text' => '<!-- Ouzhpennañ testenn amañ -->',
'resetpass_header' => 'Cheñch ger-tremen ar gont',
'oldpassword' => 'Ger-tremen kozh :',
'newpassword' => 'Ger-tremen nevez :',
'retypenew' => 'Adskrivañ ar ger-tremen nevez :',
'resetpass_submit' => 'Cheñch ar ger-tremen ha kevreañ',
'resetpass_success' => "Cheñchet eo bet ho ker-tremen ! Emaoc'h o kevreañ...",
'resetpass_forbidden' => "N'haller ket cheñch ar gerioù-termen",
'resetpass-no-info' => "Ret eo deoc'h bezañ kevreet a-benn mont d'ar bajenn-se war-eeun.",
'resetpass-submit-loggedin' => 'Cheñch ger-tremen',
'resetpass-submit-cancel' => 'Nullañ',
'resetpass-wrong-oldpass' => "Direizh eo ar ger-tremen a-vremañ pe da c'hortoz.",
'resetpass-temp-password' => "Ger-tremen da c'hortoz :",

# Special:PasswordReset
'passwordreset' => 'Adderaouekaat ar ger-tremen',
'passwordreset-text' => "Leuniañ ar furmskrid-mañ da resev ur postel da zegas soñj deoc'h eus titouroù ho kont.",
'passwordreset-legend' => 'Adsevel ar ger-tremen',
'passwordreset-disabled' => 'Diweredekaet eo bet an adsevel gerioù-tremen war ar wiki-mañ.',
'passwordreset-pretext' => '{{PLURAL:$1||Merkit unan eus an tammoù roadennoù dindan}}',
'passwordreset-username' => 'Anv implijer :',
'passwordreset-domain' => 'Domani :',
'passwordreset-capture' => 'Gwelet ar postel ?',
'passwordreset-capture-help' => "Ma askit al logell-mañ e vo diskouezet deoc'h ar postel (gant ar ger-tremen da c'hortoz) war un dro pa vo kaset d'an implijer.",
'passwordreset-email' => 'Postel :',
'passwordreset-emailtitle' => 'Titouroù kont war {{SITENAME}}',
'passwordreset-emailtext-ip' => "Unan bennak (c'hwi moarvat gant ar chomlec'h IP $1) en deus goulennet ma vefe degaset soñj dezhañ eus titouroù e gont evit {{SITENAME}} ($4). Emañ liammet {{PLURAL:$3|ar gont implijer|ar c'hontoù implijer}} da-heul gant ar chomlec'h postel-mañ :

$2

Mont a raio da get {{PLURAL:$3|ar ger-tremen da c'hortoz|ar gerioù-tremen da c'hortoz}} a-benn {{PLURAL:$5|un devezh|$5 deiz}}.
Mat e vefe deoc'h kevreañ ha dibab ur ger-tremen nevez bremañ. Mard eo bet goulennet kement-se gant unan bennak all pe m'hoc'h eus soñj eus ho ker-tremen orin ha mar ne fell ket deoc'h e cheñch ken, na daolit ket evezh ouzh ar gemennadenn-mañ ha dalc'hit d'ober gant ho ker-tremen kozh.",
'passwordreset-emailtext-user' => "Goulennet en deus an implijer $1 war  {{SITENAME}} e vefe degaset soñj dezhañ eus titouroù e gont evit {{SITENAME}} ($4). Emañ liammet {{PLURAL:$3|ar gont implijer|ar c'hontoù implijer}} da-heul gant ar chomlec'h postel-mañ :

$2

Mont a raio da get {{PLURAL:$3|ar ger-tremen da c'hortoz|ar gerioù-tremen da c'hortoz}} a-benn {{PLURAL:$5|un devezh|$5 deiz}}.
Mat e vefe deoc'h kevreañ ha dibab ur ger-tremen nevez bremañ. Mard eo bet goulennet kement-se gant unan bennak all pe m'hoc'h eus soñj eus ho ker-tremen orin ha mar ne fell ket deoc'h e cheñch ken, na daolit ket evezh ouzh ar gemennadenn-mañ ha dalc'hit d'ober gant ho ker-tremen kozh.",
'passwordreset-emailelement' => "Anv implijer :           $1
Ger-tremen da c'hortoz : $2",
'passwordreset-emailsent' => "Kaset ez eus bet ur postel da zegas soñj deoc'h.",
'passwordreset-emailsent-capture' => 'Ur postel degas da soñj evel zo diskouezet amañ dindan zo bet kaset.',
'passwordreset-emailerror-capture' => "Kaset ez eus bet ur postel degas da soñj evel m'emañ diskouezet amañ dindan met c'hwitet eo bet ar c'has : $1",

# Special:ChangeEmail
'changeemail' => "Kemmañ ar chomlec'h postel",
'changeemail-header' => "Kemmañ chomlec'h postel ar gont",
'changeemail-text' => "Leugnit ar furmskrid-mañ da cheñch ho chomlec'h postel. Ret e vo deoc'h merkañ ho ker-tremen evit kadarnaat ar c'hemm-se.",
'changeemail-no-info' => "Ret eo deoc'h bezañ kevreet a-benn mont d'ar bajenn-se war-eeun.",
'changeemail-oldemail' => "Chomlec'h postel a-vremañ :",
'changeemail-newemail' => "Chomlec'h postel nevez :",
'changeemail-none' => '(hini ebet)',
'changeemail-submit' => "Cheñch chomlec'h postel",
'changeemail-cancel' => 'Nullañ',

# Edit page toolbar
'bold_sample' => 'Testenn dev',
'bold_tip' => 'Testenn dev',
'italic_sample' => 'Testenn italek',
'italic_tip' => 'Testenn italek',
'link_sample' => 'Liamm titl',
'link_tip' => 'Liamm diabarzh',
'extlink_sample' => 'http://www.example.com liamm titl',
'extlink_tip' => 'Liamm diavaez (na zisoñjit ket http://)',
'headline_sample' => 'Testenn istitl',
'headline_tip' => 'Istitl live 2',
'nowiki_sample' => 'Lakait an destenn anfurmadet amañ',
'nowiki_tip' => 'Na ober van ouzh ereadur ar wiki',
'image_sample' => 'Skouer.jpg',
'image_tip' => 'Skeudenn enframmet',
'media_sample' => 'Skouer.ogg',
'media_tip' => 'Liamm restr media',
'sig_tip' => 'Ho sinadur gant an deiziad',
'hr_tip' => 'Liamm a-led (arabat implijout re)',

# Edit pages
'summary' => 'Diverrañ :',
'subject' => 'Danvez/titl:',
'minoredit' => 'Kemm dister',
'watchthis' => 'Evezhiañ ar pennad-mañ',
'savearticle' => 'Enrollañ',
'preview' => 'Rakwelet',
'showpreview' => 'Rakwelet',
'showlivepreview' => 'Rakwelet prim',
'showdiff' => "Diskouez ar c'hemmoù",
'anoneditwarning' => "'''Diwallit :''' N'oc'h ket kevreet. 
Ho chomlec'h IP eo a vo enrollet war istor kemmoù ar bajenn-mañ.",
'anonpreviewwarning' => "''N'oc'h ket kevreet. Enrollañ a lakao war-wel ho chomlec'h IP e istor kemmoù ar bajenn.''",
'missingsummary' => "'''Taolit evezh:''' N'hoc'h eus ket lakaet tamm testenn diverrañ ebet evit ho kemmoù. Mar klikit war enrollañ en-dro, e vo enrollet ho testenn evel m'emañ hepmuiken.",
'missingcommenttext' => "Skrivit hoc'h evezhiadenn a-is.",
'missingcommentheader' => "'''Taolit evezh :''' N'hoc'h eus lakaet tamm danvez/titl ebet d'hoc'h evezhiadenn.
Mar klikit war \"{{int:savearticle}}\" en-dro, e vo enrollet ho testenn evel m'emañ hepmuiken.",
'summary-preview' => 'Rakwelet an diverrañ :',
'subject-preview' => 'Rakwelet danvez/titl :',
'blockedtitle' => 'Implijer stanket',
'blockedtext' => "'''Stanket eo bet ho kont implijer pe ho chomlec'h IP'''

Gant $1 eo bet graet.
Setu an abeg evit se : ''$2''.

* Stanket adalek : $8
* Stanket betek : $6
* Pad ar stankadenn : $7

Gallout a rit mont e darempred gant $1 pe gant unan eus ar [[{{MediaWiki:Grouppage-sysop}}|verourien]] all evit eskemm ganto war se. N'hallit implijout an arc'hwel 'kas ur postel d'an implijer-mañ' nemet ma'z eus bet spisaet ganeoc'h ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù kont]] ha ma n'eo ket bet stanket.
$3 eo ho chomlec'h IP, ha #$5 eo niverenn an identelezh stanket.
Merkit anezho en ho koulennoù bep tro.",
'autoblockedtext' => "Stanket eo bet ho chomlec'h IP ent emgefreek rak implijet e veze gant un implijer all bet stanket gant \$1.
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
'blockednoreason' => "n'eus bet roet abeg ebet",
'whitelistedittext' => "Ret eo deoc'h en em $1 evit gallout skridaozañ.",
'confirmedittext' => "Rankout a ri bezañ kadarnaet ho chomlec'h postel a-raok gallout kemmañ pajennoù. Skrivit ha kadarnait ho chomlec'h postel en ho [[Special:Preferences|penndibaboù implijer]] mar plij.",
'nosuchsectiontitle' => 'Diposupl eo kavout ar rann-mañ',
'nosuchsectiontext' => "Klasket hoc'h eus kemmañ ur rann n'eus ket anezhi.
Moarvat ez eo bet dilerc'hiet pe dilamet abaoe ma oa bet lennet ganeoc'h.",
'loginreqtitle' => 'Anv implijer rekis',
'loginreqlink' => 'kevreañ',
'loginreqpagetext' => "Ret eo deoc'h $1 evit gwelet pajennoù all.",
'accmailtitle' => 'Ger-tremen kaset.',
'accmailtext' => "Kaset ez eus bet ur ger-tremen dargouezhek evit [[User talk:$1|$1]] da $2.

Cheñchet e c'hall ar ger-tremen evit ar gont nevez-mañ bezañ war ar bajenn ''[[Special:ChangePassword|cheñch ger-tremen]]'', ur wezh kevreet.",
'newarticle' => '(Nevez)',
'newarticletext' => "Heuliet hoc'h eus ul liamm a gas d'ur bajenn n'eo ket bet savet evit c'hoazh.
A-benn krouiñ ar bajenn-se, krogit da skrivañ er prenestr skridaozañ dindan (gwelet ar [[{{MediaWiki:Helppage}}|bajenn skoazell]] evit gouzout hiroc'h).
M'emaoc'h en em gavet amañ dre fazi, klikit war bouton '''kent''' ho merdeer evit mont war ho kiz.",
'anontalkpagetext' => "---- ''Homañ eo ar bajenn gaozeal evit un implijer(ez) dizanv n'eus ket krouet kont ebet evit c'hoazh pe na implij ket anezhi.
Setu perak e rankomp ober gant ar chomlec'h IP niverel evit anavezout anezhañ/i.
Gallout a ra ur chomlec'h a seurt-se bezañ rannet etre meur a implijer(ez).
Ma'z oc'h un implijer(ez) dizanv ha ma stadit ez eus bet kaset deoc'h kemennadennoù na sellont ket ouzhoc'h, gallout a rit [[Special:UserLogin/signup|krouiñ ur gont]]pe [[Special:UserLogin|kevreañ]] kuit a vagañ muioc'h a gemmesk gant implijerien dizanv all.",
'noarticletext' => 'N\'eus tamm skrid ebet war ar bajenn-mañ evit poent.
Gallout a rit [[Special:Search/{{PAGENAME}}|klask an titl anezhi]] e pajennoù all,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} klask en oberiadennoù liammet], pe [{{fullurl:{{FULLPAGENAME}}|action=edit}} krouiñ ar bajenn]</span>.',
'noarticletext-nopermission' => "N'eus, evit ar mare, tamm testenn ebet war ar bajenn-mañ.
Gallout a rit [[Special:Search/{{PAGENAME}}|klask titl ar bajenn-mañ]] war pajennoù all,
pe <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} klask er marilhoù kar]</span>, met n'oc'h ket aotreet da grouiñ ar bajenn-mañ.",
'missing-revision' => "N'eus ket eus adwel niv. $1 eus ar bajenn anvet « {{PAGENAME}} ».

C'hoarvezout a ra peurliesañ pa vez heuliet ul liamm istorel dispredet war-zu ur bajenn zo bet dilamet.
Gallout a reot kavout muioc'h a vunudoù e [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} renabl an dilamadurioù].",
'userpage-userdoesnotexist' => 'N\'eo ket enrollet ar gont "<nowiki>$1</nowiki>". Merkit ma fell deoc\'h krouiñ/kemmañ ar bajenn-mañ.',
'userpage-userdoesnotexist-view' => 'N\'eo ket enrollet ar gont implijer "$1".',
'blocked-notice-logextract' => "Stanket eo an implijer-mañ evit poent.
Dindan emañ merket moned diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'clearyourcache' => "Notenn :''' Goude bezañ enrollet ho pajenn e rankot freskaat krubuilh ho merdeer a-benn gwelet ar c'hemmoù.
* '''Firefox / Safari:''' Derc'hel da bouezañ war ''Pennlizherenn'' en ur glikañ war ''Adkargañ'', pe pouezañ war ''Ctrl-F5'' pe ''Ctrl-R'' (''⌘-R'' war ur Mac); 
* ''''Google Chrome:''' Pouezañ war ''Ctrl-Pennlizh-R'' (''⌘-Shift-R'' war ur Mac)
* '''Internet Explorer:''' Derc'hel da bouezañ war ''Ctrl'' en ur glikañ war ''Freskaat,'' pe pouezañ war ''Ctrl-F5''
* ''''Konqueror: '''Klikañ war ''Adkargañ'' pe pouezañ war ''F5;'' 
* '''Opera:''' Riñsañ ar grubuilh e ''Ostilhoù → Penndibaboù''",
'usercssyoucanpreview' => "'''Tun :''' Grit gant ar bouton \"{{int:showpreview}}\" evit testiñ ho follenn CSS nevez a-raok enrollañ anezhi.",
'userjsyoucanpreview' => "'''Tun :''' Grit gant ar bouton \"{{int:showpreview}}\" evit testiñ ho follenn JS nevez a-raok enrollañ anezhi.",
'usercsspreview' => "'''Dalc'hit soñj n'emaoc'h ken nemet o rakwelet ho follenn CSS deoc'h.'''
'''N'eo ket bet enrollet evit c'hoazh!'''",
'userjspreview' => "'''Dalc'hit soñj emaoc'h o rakwelet pe o testiñ ho kod javascript deoc'h ha n'eo ket bet enrollet c'hoazh!'''",
'sitecsspreview' => "'''Dalc'hit soñj n'emaoc'h ken nemet o rakwelet ar follenn CSS-mañ.'''
'''N'eo ket bet enrollet evit c'hoazh!'''",
'sitejspreview' => "'''Dalc'hit soñj n'emaoc'h ken nemet o rakwelet ar c'hod JavaScript-mañ.'''
'''N'eo ket bet enrollet evit c'hoazh!'''",
'userinvalidcssjstitle' => "'''Diwallit:''' N'eus tamm gwiskadur \"\$1\" ebet. Ho pez soñj e vez implijet lizherennoù bihan goude an anv implijer hag ar veskell / gant ar pajennoù personel dezho un astenn .css ha .js; da skouer eo mat ar follenn stil {{ns:user}}:Foo/vector.css ha faziek an hini {{ns:user}}:Foo/Vector.css.",
'updated' => '(Hizivaet)',
'note' => "'''Notenn :'''",
'previewnote' => "'''Diwallit mat, n'eus ken ur rakweled eus an destenn-mañ.'''
N'eo ket bet enrollet ho kemmoù evit c'hoazh !",
'continue-editing' => "Mont d'an takad kemmañ",
'previewconflict' => 'Gant ar rakweled e teu testenn ar bajenn war wel evel ma vo pa vo bet enrollet.',
'session_fail_preview' => "'''Ho tigarez! N'eus ket bet tu da enrollañ ho kemmoù rak kollet eo bet roadennoù an dalc'h.'''
Klaskit en-dro mar plij.
Ma ne'z a ket en-dro c'hoazh, klaskit [[Special:UserLogout|digevreañ]] hag adkevreañ war-lerc'h.",
'session_fail_preview_html' => "'''Ho tigarez! N'omp ket bet gouest da enrollañ ho kemmoù rak kollet ez eus bet roadennoù e-kerzh an dalc'h.'''

''Gweredekaet eo al linennoù HTML e {{SITENAME}}. Rak-se eo kuzh ar rakweledoù a-benn en em zifenn diouzh an tagadennoù JavaScript.''

'''Mard e oa onest ar c'hemmoù hoc'h eus klasket degas, klaskit en-dro. '''
Mar ned a ket en-dro, klaskit [[Special:UserLogout|digevreañ]] ha kevreañ en-dro.",
'token_suffix_mismatch' => "'''Distaolet eo bet ar c'hemmoù degaset ganeoc'h abalamour ma oa bet kemmesket an arouezennoù poentadur gant ho merdeer en daveer kemmañ. Distaolet eo bet ar c'hemmoù kuit na vije breinet ar bajennad skrid.
C'hoarvezout a ra a-wechoù pa implijit ur servijer proksi dreinek dizanav.'''",
'edit_form_incomplete' => "'''Darn eus ar furmskrid kemmañ zo chomet hep tizhout ar servijer ; gwiriit ervat emañ mat ho kemmoù tre evel m'int bet graet ganeoc'h ha klaskit en-dro.'''",
'editing' => "Oc'h aozañ $1",
'creating' => 'O krouiñ $1',
'editingsection' => "Oc'h aozañ $1 (rann)",
'editingcomment' => "Oc'h aozañ $1 (rann nevez)",
'editconflict' => 'tabut kemmañ : $1',
'explainconflict' => "Enrollet eo bet ar bajenn-mañ war-lerc'h m'ho pefe kroget d'he c'hemmañ.
E-krec'h an takad aozañ emañ an destenn evel m'emañ enrollet bremañ er bank roadennoù.
Ho kemmoù deoc'h a zeu war wel en takad aozañ traoñ.
Ret e vo deoc'h degas ho kemmoù d'an destenn zo evit poent.
N'eus '''nemet''' an destenn zo en takad krec'h a vo saveteet pa klikot war \"{{int:savearticle}}\".",
'yourtext' => 'Ho testenn',
'storedversion' => 'Stumm enrollet',
'nonunicodebrowser' => "'''DIWALLIT: N'eo ket skoret an Unicode gant ho merdeer. Un diskoulm da c'hortoz zo bet kavet evit ma c'hallfec'h kemmañ pennadoù : dont a raio war wel an arouezennoù an-ASCII er prenestr skridaozañ evel kodoù eizhdekvedennel.'''",
'editingold' => "'''Diwallit : o kemm ur stumm kozh eus ar bajenn-mañ emaoc'h. Mard enrollit bremañ e vo kollet an holl gemmoù bet graet abaoe ar stumm-se.'''",
'yourdiff' => "Diforc'hioù",
'copyrightwarning' => "Sellet e vez ouzh an holl degasadennoù graet war {{SITENAME}} evel ouzh degasadennoù a zouj da dermenoù ar $2 (Sellet ouzh $1 evit gouzout hiroc'h). Mar ne fell ket deoc'h e vefe embannet ha skignet ho skridoù, arabat kas anezho.<br />
Heñveldra, prometiñ a rit kemer perzh dre zegas skridoù savet ganeoc'h hepken pe tennet eus ur vammenn frank a wirioù.
'''NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER (COPYRIGHT) HEP AOTRE D'OBER KEMENT-SE!'''",
'copyrightwarning2' => "Notit mat e c'hall kement degasadenn graet ganeoc'h war {{SITENAME}} bezañ kemmet, adaozet pe lamet kuit gant an implijerien all. Mar ne fell ket deoc'h e vije kemmet-digemmet ar pezh hoc'h eus skrivet na gemerit ket perzh er raktres-mañ.<br /> Gouestlañ a rit ivez eo bet savet ar boued spered ganeoc'h pe eilet diwar ur vammenn frank a wirioù pe en domani foran (gwelet $1 evit gouzout hiroc'h). '''NA IMPLIJIT KET LABOURIOÙ GANT GWIRIOÙ AOZER HEP AOTRE D'OBER KEMENT-SE!'''",
'longpageerror' => "'''FAZI : {{PLURAL:$1|Ur c'hilookted|$1 kilookted}} hir eo an destenn lakaet ganeoc'h, ar pezh zo hiroc'h eget {{PLURAL:$2|ur c'hilookted|$2 kilookted}}, ar vent vrasañ aotreet. N'haller ket enrollañ.'''",
'readonlywarning' => "'''KEMENN DIWALL : prennet eo bet an diaz titouroù evit bezañ trezalc'het; setu ne viot ket evit enrollañ ho kemmoù diouzhtu-diouzhtu eta.'''

Gallout a rit eilañ ha pegañ ho testenn en ur restr skrid all hag enrollañ anezhi a-benn diwezhatoc'hik.'''

Setu an displegadenn lakaet gant ar merour eo bet prennet an traoù gantañ : $1",
'protectedpagewarning' => "'''KEMENN DIWALL: Gwarezet eo bet ar bajenn-mañ. N'eus nemet an implijerien ganto ar statud merour a c'hall kemmañ anezhi.'''
Enmont diwezhañ ar marilh a ziskouezer amañ a-is evel dave :",
'semiprotectedpagewarning' => "''Notenn :''' Gwarezet eo ar bajenn-mañ; n'eus nemet an implijerien bet krouet ur gont ganto a kemmañ anezhi. Kasadenn ziwezhañ ar marilh zo diskouezet amañ a-is evel dave :",
'cascadeprotectedwarning' => "'''Diwallit :''' Prennet eo ar bajenn-mañ. N'eus nemet ar verourien a c'hall kemmañ anezhi peogwir he c'haver {{PLURAL:\$1|er bajenn|e-mesk ar pajennoù}} da-heul zo bet gwarezet en ur zibab an dibarzh \"skalierad\" :",
'titleprotectedwarning' => "'''DIWALLIT :  Gwarezet eo bet ar bajenn-mañ e doare ma ranker kaout [[Special:ListGroupRights|gwirioù dibar]] a-benn krouiñ anezhi.''' Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'templatesused' => '{{PLURAL:$1|Patrom|Patromoù}} implijet war ar bajenn-mañ :',
'templatesusedpreview' => '{{PLURAL:$1|Patrom|Patromoù}} implijet er rakweladenn-mañ :',
'templatesusedsection' => '{{PLURAL:$1|Patrom|Patromoù}} implijet er rann-mañ :',
'template-protected' => '(gwarezet)',
'template-semiprotected' => '(damwarezet)',
'hiddencategories' => "{{PLURAL:$1|1 rummad kuzhet|$1 rummad kuzhet}} m'emañ rollet ar bajenn-mañ :",
'edittools' => '<!-- Diskouezet e vo an destenn kinniget amañ dindan ar sternioù kemmañ ha kargañ. -->',
'nocreatetitle' => "Strishaet eo bet ar c'hrouiñ pajennoù",
'nocreatetext' => 'Strishaet eo bet an tu da grouiñ pajennoù nevez war {{SITENAME}}.
Gallout a rit mont war-gil ha lakaat kemmañ ur bajenn zo anezhi dija, pe [[Special:UserLogin|en em enrollañ ha krouiñ ur gont]].',
'nocreate-loggedin' => "N'oc'h ket aotreet da grouiñ pajennoù nevez.",
'sectioneditnotsupported-title' => "N'eo ket skoret ar c'hemmañ rannoù",
'sectioneditnotsupported-text' => "N'eo ket skoret ar c'hemmañ rannoù evit ar bajenn-mañ",
'permissionserrors' => 'Fazioù Aotre',
'permissionserrorstext' => "N'oc'h ket aotreet d'ober kement-mañ evit {{PLURAL:$1|an abeg-mañ|an abegoù-mañ}} :",
'permissionserrorstext-withaction' => "N'oc'h ket aotreet da $2, evit an {{PLURAL:$1|abeg-mañ|abeg-mañ}} :",
'recreate-moveddeleted-warn' => "'''Diwallit : Emaoc'h o krouiñ ur bajenn zo bet diverket c'hoazh.'''

En em soñjit ervat ha talvoudus eo kenderc'hel krouiñ ar bajenn.
Deoc'h da c'houzout, aze emañ ar marilhoù diverkañ hag adenvel :",
'moveddeleted-notice' => 'Diverket eo bet ar bajenn-mañ.
Dindan emañ ar marilh diverkañ hag adenvel.',
'log-fulllog' => 'Gwelet ar marilh klok',
'edit-hook-aborted' => "C'hwitet ar c'hemmañ gant un astenn.
Abeg dianav.",
'edit-gone-missing' => 'Dibosupl hizivaat ar bajenn.
Diverket eo bet evit doare.',
'edit-conflict' => 'Tabut kemmañ.',
'edit-no-change' => "N'eo ket bet kemeret ho tegasadenn e kont rak ne oa ket bet kemmet netra en destenn.",
'edit-already-exists' => "N'eus ket bet gallet krouiñ ur bajenn nevez.
Krouet e oa bet c'hoazh.",
'defaultmessagetext' => 'Testenn dre ziouer',

# Parser/template warnings
'expensive-parserfunction-warning' => "Diwallit : Re a c'halvoù koustus e-keñver an arc'hwelioù parser zo gant ar bajenn-mañ.

Dleout a rafe bezañ nebeutoc'h eget $2 {{PLURAL:$2|galv|galv}}, ha {{PLURAL:$1|$1 galv|$1 galv}} zo.",
'expensive-parserfunction-category' => "Pagjennoù enno re a c'halvoù koustus e-keñver an arc'hwelioù parser.",
'post-expand-template-inclusion-warning' => 'Diwallit : re a batromoù zo war ar bajenn-mañ.
Lod anezho a vo lakaet a-gostez.',
'post-expand-template-inclusion-category' => 'Pajennoù enno re a batromoù',
'post-expand-template-argument-warning' => 'Diwallit : war ar bajenn-mañ ez eus eus da nebeutañ un arventenn eus ur patrom zo re vras.
A-gostez eo bet lezet an arventenn-se.',
'post-expand-template-argument-category' => 'Pajennoù enno arventennoù patrom bet lezet a-gostez',
'parser-template-loop-warning' => "Patrom e kelc'h detektet : [[$1]]",
'parser-template-recursion-depth-warning' => 'Tizhet bevenn donder galvoù ar patromoù ($1)',
'language-converter-depth-warning' => "Aet eur en tu all d'ar vevenn amdreiñ yezhoù ($1)",
'node-count-exceeded-category' => "Pajennoù m'eur aet en tu all d'an niver a skoulmoù",
'node-count-exceeded-warning' => "Pajenn a ya en tu all d'an niver a skoulmoù",
'expansion-depth-exceeded-category' => "Pajennoù m'eur aet dreist d'an donder astenn",
'expansion-depth-exceeded-warning' => "Pajenn a ya dreist d'an donder astenn",
'parser-unstrip-loop-warning' => "Detektet ez eus bet ul lagadenn n'haller ket divontañ",
'parser-unstrip-recursion-limit' => "Aet dreist d'ar vevenn rekurziñ n'haller ket divontañ : $1",
'converter-manual-rule-error' => 'Fazi dinodet  er reolenn cheñch yezh dre zorn',

# "Undo" feature
'undo-success' => "Gallout a reer disteurel ar c'hemmoù-mañ. Gwiriit, mar plij, gant ar geñveriadenn a-is evit bezañ sur eo an dra-se a fell deoc'h ober; goude-se enrollit ar c'hemmoù a-is a-benn echuiñ disteurel ar c'hemmoù.",
'undo-failure' => "N'eus ket bet tu da zisteuler ar c'hemm-mañ abalamour d'un tabut gant kemmoù degaset e-keit-se.",
'undo-norev' => "N'eus ket bet gallet degas ar c'hemmoù-mañ rak pe n'eus ket anezho pe int bet diverket.",
'undo-summary' => 'Disteurel kemmoù $1 a-berzh [[Special:Contributions/$2|$2]] ([[User talk:$2|kaozeal]])',

# Account creation failure
'cantcreateaccounttitle' => 'Dibosupl krouiñ ar gont',
'cantcreateaccount-text' => "Stanket eo bet ar c'hrouiñ kontoù adal ar chomlec'h IP ('''$1''') gant [[User:$3|$3]].

An abeg roet gant $3 zo ''$2''",

# History pages
'viewpagelogs' => 'Gwelet ar marilhoù evit ar bajenn-mañ',
'nohistory' => "Ar bajenn-mañ n'he deus tamm istor ebet.",
'currentrev' => 'Stumm a-vremañ pe stumm red',
'currentrev-asof' => 'Stumm red eus an $1',
'revisionasof' => 'Stumm eus an $1',
'revision-info' => 'Stumm eus an $1 gant $2',
'previousrevision' => '← Stumm kent',
'nextrevision' => "Stumm war-lerc'h →",
'currentrevisionlink' => 'Gwelet ar stumm red',
'cur' => 'red',
'next' => "war-lerc'h",
'last' => 'kent',
'page_first' => 'kentañ',
'page_last' => 'diwezhañ',
'histlegend' => "Sellet ouzh an diforc'hioù : lakait un ask adal d'ar stummoù a fell deoc'h keñveriañ ha pouezit war kadarnaat pe war ar bouton en traoñ.<br />
Alc'hwez : (red) = diforc'hioù gant ar stumm a-vremañ,
(diwez) = diforc'hioù gant ar stumm kent, D = kemm dister",
'history-fieldset-title' => 'Furchal en istor',
'history-show-deleted' => 'Diverket hepken',
'histfirst' => 'Kentañ',
'histlast' => 'Diwezhañ',
'historysize' => '({{PLURAL:$1|$1 okted|$1 okted}})',
'historyempty' => '(goullo)',

# Revision feed
'history-feed-title' => "Istor ar c'hemmoù",
'history-feed-description' => "Istor ar c'hemmoù degaset war ar bajenn-mañ eus ar wiki",
'history-feed-item-nocomment' => "$1 d'an $2",
'history-feed-empty' => "Ar bajenn goulennet n'eus ket anezhi.
Marteze eo bet diverket eus ar wiki, pe adanvet.
Implijit [[Special:Search|klaskit er wiki]] evit kavout pajennoù all a c'hallfe klotañ.",

# Revision deletion
'rev-deleted-comment' => "(diverradenn ar c'hemm diverket)",
'rev-deleted-user' => '(anv implijer diverket)',
'rev-deleted-event' => '(elfenn dilamet)',
'rev-deleted-user-contribs' => "[anv implijer pe chomlec'h IP diverket - kemm kuzhet diouzh an degasadennoù]",
'rev-deleted-text-permission' => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Marteze e kavot munudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} roll ar pajennoù diverket].",
'rev-deleted-text-unhide' => "!'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Marteze e kavot munudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].
Gallout a rit [$1 gwelet ar stumm-se] c'hoazh mar fell deoc'h kenderc'hel.",
'rev-suppressed-text-unhide' => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Titouroù zo da gaout war [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].
Gallout a rit [$1 gwelet ar stumm-se] c'hoazh mar fell deoc'h kenderc'hel.",
'rev-deleted-text-view' => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn.
Gallout a rit sellet outañ ; titouroù all a gavot war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].",
'rev-suppressed-text-view' => "'''Diverket''' eo bet ar stumm-mañ eus ar bajenn-mañ.
Gallout a rit sellet outañ ; titouroù all zo war [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].",
'rev-deleted-no-diff' => "N'hallit ket gwelet an diforc'h-mañ rak '''diverket''' eo bet unan eus ar stummoù.
Marteze ez eus muioc'h a vunudoù war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh an diverkadennoù].",
'rev-suppressed-no-diff' => "N'hallit ket gwelet an diforc'h-se rak '''diverket''' ez eus bet unan eus an adweladennoù.",
'rev-deleted-unhide-diff' => "'''Diverket''' ez eus bet unan eus kemmoù an diforc'h-mañ.
Titouroù zo da gaout war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].
Gallout a rit c'hoazh [$1 sellet ouzh an diforc'h-mañ] mar fell deoc'h kenderc'hel.",
'rev-suppressed-unhide-diff' => "'''Diverket''' ez eus bet unan eus adweladennoù an diforc'h-mañ.
Titouroù ouzhpenn zo war [{{lurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket].
Gallout a rit c'hoazh [$1 sellet ouzh an diforc'h-se] mar fell deoc'h kenderc'hel.",
'rev-deleted-diff-view' => "'''Diverket''' ez eus bet unan eus stummoù an diforc'h-mañ.
Gallout a rit gwelet an diforc'h-mañ ; titouroù zo war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket]",
'rev-suppressed-diff-view' => "'''Diverket''' ez eus bet unan eus stummoù an diforc'h-mañ.
Gallout a ri gwelet an diforc'h-mañ ; titouroù zo war [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} marilh ar pajennoù diverket]",
'rev-delundel' => 'diskouez/kuzhat',
'rev-showdeleted' => 'diskouez',
'revisiondelete' => 'Diverkañ/diziverkañ stummoù',
'revdelete-nooldid-title' => "N'eus stumm pal ebet evit an degasadennoù",
'revdelete-nooldid-text' => "Pe n'eo ket bet spisaet ganeoc'h ar stumm(où) pal da implijout an arc'hwel-mañ evito. pe n'eus ket eus ar stummoù spisaet, pe emaoc'h o klask kuzhat ar stumm red.",
'revdelete-nologtype-title' => "N'eus bet resisaet seurt marilh ebet",
'revdelete-nologtype-text' => "N'eus ket bet spisaet ganeoc'h seurt marilh ebet da lakaat an ober-mañ da c'hoarvezout warnañ.",
'revdelete-nologid-title' => 'Enmont ar marilh direizh',
'revdelete-nologid-text' => "Pe n'hoc'h eus ket spisaet enmont ebet er marilh da vezañ sevenet an ober-mañ warnañ, pe n'eus ket eus an darvoud merket.",
'revdelete-no-file' => "N'eus ket eus ar restr spisaet ganeoc'h.",
'revdelete-show-file-confirm' => 'Ha sur oc\'h e fell deoc\'h gwelet stumm diverket ar restr "<nowiki>$1</nowiki>" deiziataet eus an $2 da $3?',
'revdelete-show-file-submit' => 'Ya',
'revdelete-selected' => "'''{{PLURAL:$2|Stumm dibabet|Stummoù dibabet}} eus [[:$1]] :'''",
'logdelete-selected' => "'''{{PLURAL:$1|Darvoud eus ar marilh diuzet|Darvoud eus ar marilh diuzet}} :'''",
'revdelete-text' => "'''Derc'hel a raio ar stummoù ha darvoudoù diverket da zont war wel war istor ar bajenn hag er marilhoù, met dazrn eus ar boued n'hallo ket bezañ gwelet gant an dud.'''
Gouest e vo merourien all {{SITENAME}} da dapout krog en testennoù kuzhet ha da ziziverkañ anezho en-dro dre an hevelep etrefas, nemet e vije bet lakaet e plas strishadurioù ouzhpenn.",
'revdelete-confirm' => "Kadarnait eo mat an dra-se a fell deoc'h ober, e komprenit mat ar pezh a empleg, hag en grit en ur zoujañ d'ar [[{{MediaWiki:Policy-url}}|reolennoù]].",
'revdelete-suppress-text' => "Ne zlefe an dilemel bezañ implijet '''nemet''' abalamour d'an abegoù da-heul :
* Titouroù personel dizere
*: ''chomlec'hioù, niverennoù pellgomz pe surentez sokial personel, hag all''",
'revdelete-legend' => 'Lakaat strishadurioù gwelet',
'revdelete-hide-text' => 'Kuzhat testenn ar stumm',
'revdelete-hide-image' => 'Kuzhat danvez ar restr',
'revdelete-hide-name' => 'Kuzhat an ober hag ar vukadenn',
'revdelete-hide-comment' => "Kuzhat notenn ar c'hemm",
'revdelete-hide-user' => "Kuzhat anv implijer pe chomlec'h IP an aozer",
'revdelete-hide-restricted' => "Diverkañ ar roadennoù kement d'ar verourien ha d'ar re all",
'revdelete-radio-same' => '(arabat cheñch)',
'revdelete-radio-set' => 'Ya',
'revdelete-radio-unset' => 'Ket',
'revdelete-suppress' => 'Diverkañ roadennoù ar verourien hag ar re all',
'revdelete-unsuppress' => 'Lemel ar strishadurioù war ar stummoù assavet',
'revdelete-log' => 'Abeg :',
'revdelete-submit' => 'Lakaat da dalvezout evit an {{PLURAL:$1|adweladenn|adweladennoù}} diuzet',
'revdelete-success' => "''Gweluster ar stummoù hizivaet mat.'''",
'revdelete-failure' => "''Dibosupl hizivaat gweluster ar stumm :'''
$1",
'logdelete-success' => "'''Gweluster ar marilh arventennet evel m'eo dleet.'''",
'logdelete-failure' => "'''N'eus ket bet gallet termeniñ gweluster ar marilh :'''
$1",
'revdel-restore' => 'Cheñch ar gweluster',
'revdel-restore-deleted' => 'adweladennoù bet diverket',
'revdel-restore-visible' => "adweladennoù a c'heller gwelet",
'pagehist' => 'Istor ar bajenn',
'deletedhist' => 'Diverkañ an istor',
'revdelete-hide-current' => "Fazi en ur ziverkañ an elfenn deiziataet eus an $1 da $2 : ar stumm red eo.
N'hall ket bezañ diverket.",
'revdelete-show-no-access' => 'Fazi en ur ziskwel an elfenn deiziataet eus an $1 da $2 : merket eo evel "miret".
N\'oc\'h ket aotreet da vont outi.',
'revdelete-modify-no-access' => 'Fazi en ur gemmañ an elfenn deiziataet eus an $1 da $2 : merket eo an elfenn evel "miret".
N\'oc\'h ket aotreet da vont outi.',
'revdelete-modify-missing' => "Fazi ;en ur gemmañ an elfenn gant ID $1: n'emañ ket er bank roadennoù !",
'revdelete-no-change' => "'''Evezh :''' emañ an arventennoù gweluster goulennet gant an elfenn deiziataet eus an $1 da $2 dija",
'revdelete-concurrent-change' => "Fazi p'eo bet kemmet an elfenn deiziataet eus an $1 da $2 : cheñchet eo bet e statud gant unan bennak all dres pa oac'h-chwi o kemmañ anezhi. Gwiriit ar marilhoù.",
'revdelete-only-restricted' => "Ur fazi zo bet en ur guzhat an elfenn deiziadet eus an $1 da $2 : n'hallit ket kuzhat an elfennoù-mañ ouzh ar verourien hep dibab ivez unan eus an dibarzhioù gweluster all.",
'revdelete-reason-dropdown' => "*Abegoù diverkañ boutin
**Gaou ouzh ar gwirioù implijout
**Titouroù personel dizereat
** Titouroù a c'hall bezañ gwallvrudus",
'revdelete-otherreason' => 'Abeg all/ouzhpenn :',
'revdelete-reasonotherlist' => 'Abeg all',
'revdelete-edit-reasonlist' => 'Kemmañ abegoù an diverkañ',
'revdelete-offender' => 'Aozer an adlenn :',

# Suppression log
'suppressionlog' => 'Marilh diverkañ',
'suppressionlogtext' => 'A-is emañ roll an diverkadennoù hag ar stankadennoù diwezhañ enno an adweladennoù kuzhet ouzh ar verourien. 
Gwelet [[Special:BlockList|roll an IPoù stanket]] evit kaout roll ar stankadennoù ha forbannadennoù e talvoud evit poent.',

# History merging
'mergehistory' => 'Kendeuziñ istor ur bajenn',
'mergehistory-header' => "Gant ar bajenn-mañ e c'hallit kendeuziñ an adweladennoù c'hoarvezet da istor ur bajenn war-du unan nevez.
Gwiriit ne vo ket torret red istor ar bajenn gant ar c'hemm-mañ.",
'mergehistory-box' => 'Kendeuziñ istor div bajenn :',
'mergehistory-from' => 'Pajenn orin :',
'mergehistory-into' => 'Pajenn dal :',
'mergehistory-list' => 'Aozañ an istorioù da gendeuziñ',
'mergehistory-merge' => 'Gallout a reer kendeuziñ ar stummoù da-heul eus [[:$1]] e [[:$2]]. Na implijit bouton radio ar bann nemet evit kendeuziñ ar stummoù bet krouet en deroù betek an deiziad merket. Notit mat e vo nevesaet ar bann mard implijit al liammoù merdeiñ.',
'mergehistory-go' => "Diskouez ar stummoù a c'haller kendeuziñ",
'mergehistory-submit' => 'Kendeuziñ ar stummoù',
'mergehistory-empty' => "N'haller ket kendeuziñ stumm ebet.",
'mergehistory-success' => 'Kendeuzet ez eus bet $3 {{PLURAL:$3|stumm|stumm}} eus [[:$1]] e [[:$2]].',
'mergehistory-fail' => 'Dibosupl kendeuziñ an istorioù. Gwiriit ar bajenn hag arventennoù an deiziadoù.',
'mergehistory-no-source' => "N'eus ket eus ar bajenn orin $1.",
'mergehistory-no-destination' => "N'eus ket eus ar bajenn dal $1.",
'mergehistory-invalid-source' => 'Ret eo da anv ar bajenn orin bezañ reizh.',
'mergehistory-invalid-destination' => 'Ret eo da anv ar bajenn dal bezañ reizh.',
'mergehistory-autocomment' => 'Kendeuzet [[:$1]] gant [[:$2]]',
'mergehistory-comment' => 'Kendeuzet [[:$1]] gant [[:$2]] : $3',
'mergehistory-same-destination' => "N'hall ket ar pajennoù kein hag ar pajennoù tal bezañ an hevelep re",
'mergehistory-reason' => 'Abeg :',

# Merge log
'mergelog' => "Marilh ar c'hendeuzadennoù",
'pagemerge-logentry' => 'kendeuzet [[$1]] gant [[$2]] (stummoù betek an $3)',
'revertmerge' => "Nullañ ar c'hendeuziñ",
'mergelogpagetext' => 'Setu aze roll kendeuzadennoù diwezhañ un eil pajenn istor gant eben.',

# Diffs
'history-title' => 'Istor stummoù disheñvel "$1"',
'difference-title' => 'Diforc\'hioù etre adstummoù "$1"',
'difference-title-multipage' => 'Diforc\'hioù etre ar pajennoù "$1" ha "$2"',
'difference-multipage' => "(diforc'h etre ar pajennoù)",
'lineno' => 'Linenn $1:',
'compareselectedversions' => 'Keñveriañ ar stummoù diuzet',
'showhideselectedversions' => 'Diskouez/Kuzhat ar stummoù diuzet',
'editundo' => 'dizober',
'diff-multi' => "({{PLURAL:$1|Ur reizhadenn da c'hortoz|$1 reizhadenn da c'hortoz}} gant {{PLURAL:$2|un implijer|$2 implijer}} kuzhet.)",
'diff-multi-manyusers' => "({{PLURAL:$1|Ur reizhadenn da c'hortoz|$1 reizhadenn da c'hortoz}} gant muioc'h eget $2 {{PLURAL:$2|implijer|implijer}} kuzhet.)",
'difference-missing-revision' => "!!{{PLURAL:$2|Un adweladur|$2 adweladurioù}} eus an disheñvelder ($1) {{PLURAL:$2|n'eo ket bet kavet|n'int ket bet adkavet}}.

C'hoarvezout a ra peurliesañ pa vez heuliet ul liamm disheñvel dispredet war-zu ur bajenn zo bet dilamet.
Gallout a reot kavout munudoù e [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} renabl an dilamadurioù].",

# Search results
'searchresults' => "Disoc'hoù enklask",
'searchresults-title' => 'Disoc\'hoù klask evit "$1"',
'searchresulttext' => "Evit gouzout hiroc'h diwar-benn ar c'hlask e {{SITENAME}}, sellet ouzh [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle' => 'Klasket hoc\'h eus \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|an holl bajennoù a grog gant "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|an holl bajennoù enno ul liamm war-du "$1"]])',
'searchsubtitleinvalid' => "Klasket hoc'h eus '''$1'''",
'toomanymatches' => 'Re a respontoù a glot gant ar goulenn, klaskit gant ur goulenn all',
'titlematches' => 'Klotadurioù gant an titloù',
'notitlematches' => "N'emañ ar ger(ioù) goulennet e titl pennad ebet",
'textmatches' => 'Klotadurioù en testennoù',
'notextmatches' => "N'emañ ar ger(ioù) goulennet e testenn pennad ebet",
'prevn' => '{{PLURAL:$1|$1}} kent',
'nextn' => "{{PLURAL:$1|$1}} war-lerc'h",
'prevn-title' => "$1 {{PLURAL:$1|disoc'h|disoc'h}} kent",
'nextn-title' => "$1 {{PLURAL:$1|disoc'h|disoc'h}} war-lerc'h",
'shown-title' => "Diskouez $1 {{PLURAL:$1|disoc'h|disoc'h}} dre bajenn",
'viewprevnext' => 'Gwelet ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Dibarzhioù klask',
'searchmenu-exists' => "'''Ur bajenn anvet\"[[:\$1]]\" zo war ar wiki-mañ'''",
'searchmenu-new' => "'''Krouiñ ar bajenn \"[[:\$1]]\" war ar wiki-mañ !'''",
'searchhelp-url' => 'Help:Skoazell',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Furchal er pajennoù a grog gant ar rakger-mañ]]',
'searchprofile-articles' => 'Pajennoù gant boued',
'searchprofile-project' => 'Pajennoù skoazell ha pajennoù ar raktres',
'searchprofile-images' => 'Liesmedia',
'searchprofile-everything' => 'Pep tra',
'searchprofile-advanced' => 'Araokaet',
'searchprofile-articles-tooltip' => 'Klask e $1',
'searchprofile-project-tooltip' => 'Klask e $1',
'searchprofile-images-tooltip' => 'Klask ar restroù',
'searchprofile-everything-tooltip' => "Klask e pep lec'h (er pajennoù kaozeal ivez)",
'searchprofile-advanced-tooltip' => 'Klask en esaouennoù anv personelaet',
'search-result-size' => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-result-category-size' => '{{PLURAL:$1|1|$1}} ezel ({{PLURAL:$2|1|$2}} is-rummad, {{PLURAL:$3|1|$3}} restr)',
'search-result-score' => 'Klotadusted : $1%',
'search-redirect' => '(adkas $1)',
'search-section' => '(rann $1)',
'search-suggest' => "N'hoc'h eus ket soñjet kentoc'h e : $1",
'search-interwiki-caption' => 'Raktresoù kar',
'search-interwiki-default' => "$1 disoc'h :",
'search-interwiki-more' => "(muioc'h)",
'search-relatedarticle' => "Disoc'hoù kar",
'mwsuggest-disable' => 'Diweredekaat kinnigoù AJAX',
'searcheverything-enable' => 'Klask en holl esaouennoù anv',
'searchrelated' => "disoc'hoù kar",
'searchall' => 'An holl',
'showingresults' => "Diskouez betek {{PLURAL:$1|'''1''' disoc'h|'''$1''' disoc'h}} o kregiñ gant #'''$2'''.",
'showingresultsnum' => "Diskouez {{PLURAL:$3|'''1''' disoc'h|'''$3''' disoc'h}} o kregiñ gant #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Disoc'h '''$1''' diwar '''$3'''|Disoc'hoù '''$1 - $2''' diwar '''$3'''}} evit '''$4'''",
'nonefound' => "'''Notenn''' : dre ziouer ne glasker nemet en esaouennoù anv zo. Klaskit spisaat ho koulenn gant '''all :''' evit klask e pep lec'h (e-barzh ar pajennoù-kaozeal, ar patromoù, hag all), pe dibabit an esaouenn anv a zere.",
'search-nonefound' => "An enklask-mañ ne zisoc'h war netra.",
'powersearch' => 'Klask araokaet',
'powersearch-legend' => 'Klask araokaet',
'powersearch-ns' => 'Klask en esaouennoù anv :',
'powersearch-redir' => 'Roll an adkasoù',
'powersearch-field' => 'Klask',
'powersearch-togglelabel' => 'Dibab :',
'powersearch-toggleall' => 'An holl',
'powersearch-togglenone' => 'Hini ebet',
'search-external' => 'Klask diavaez',
'searchdisabled' => "<p>Diweredekaet eo bet an arc'hwel klask war an destenn a-bezh evit ur frapad rak ur samm re vras e oa evit ar servijer. Emichañs e vo tu d'e adlakaat pa vo ur servijer galloudusoc'h ganeomp. Da c'hortoz e c'hallit klask gant Google:</p>",

# Quickbar
'qbsettings' => 'Personelaat ar varrenn ostilhoù',
'qbsettings-none' => 'Hini ebet',
'qbsettings-fixedleft' => 'Kleiz',
'qbsettings-fixedright' => 'Dehou',
'qbsettings-floatingleft' => 'War-neuñv a-gleiz',
'qbsettings-floatingright' => 'War-neuñv a-zehou',
'qbsettings-directionality' => 'Difiñv, hervez an tu ma vez skrivet ho yezh',

# Preferences page
'preferences' => 'Penndibaboù',
'mypreferences' => 'Penndibaboù',
'prefs-edits' => 'Niver a zegasadennoù :',
'prefsnologin' => 'Digevreet',
'prefsnologintext' => 'Ret eo deoc\'h bezañ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kevreet]</span> a-benn gellout cheñch ho tibaboù implijer.',
'changepassword' => 'Kemmañ ar ger-tremen',
'prefs-skin' => 'Gwiskadur',
'skin-preview' => 'Rakwelet',
'datedefault' => 'Dre ziouer',
'prefs-beta' => 'Perzhioù beta',
'prefs-datetime' => 'Deiziad hag eur',
'prefs-labs' => 'Perzhioù "labs"',
'prefs-user-pages' => 'Pajennoù implijer',
'prefs-personal' => 'Titouroù personel',
'prefs-rc' => 'Kemmoù diwezhañ',
'prefs-watchlist' => 'Roll evezhiañ',
'prefs-watchlist-days' => 'Niver a zevezhioù da ziskouez er rollad evezhiañ :',
'prefs-watchlist-days-max' => "D'ar muiañ $1 {{PLURAL:$1|deiz|deiz}}",
'prefs-watchlist-edits' => 'Niver a gemmoù da ziskouez er roll evezhiañ astennet :',
'prefs-watchlist-edits-max' => 'Niver brasañ : 1000',
'prefs-watchlist-token' => 'Jedouer evit ar roll evezhiañ :',
'prefs-misc' => 'Penndibaboù liesseurt',
'prefs-resetpass' => 'Cheñch ar ger-tremen',
'prefs-changeemail' => "Cheñch chomlec'h postel",
'prefs-setemail' => "Termeniñ ur chomlec'h postel",
'prefs-email' => 'Dibarzhioù postel',
'prefs-rendering' => 'Neuz',
'saveprefs' => 'Enrollañ ar penndibaboù',
'resetprefs' => 'Adlakaat ar penndibaboù kent',
'restoreprefs' => 'Adlakaat an holl arventennoù dre ziouer',
'prefs-editing' => 'Prenestr skridaozañ',
'prefs-edit-boxsize' => 'Ment ar prenestr skridaozañ.',
'rows' => 'Linennoù :',
'columns' => 'Bannoù',
'searchresultshead' => 'Klask',
'resultsperpage' => 'Niver a respontoù dre bajenn :',
'stub-threshold' => 'Bevenn uhelañ evit al <a href="#" class="stub">liammoù war-du an danvez pennadoù</a> (okted) :',
'stub-threshold-disabled' => 'Diweredekaet',
'recentchangesdays' => "Niver a zevezhioù da ziskouez er c'hemmoù diwezhañ :",
'recentchangesdays-max' => "D'ar muiañ $1 {{PLURAL:$1|deiz|deiz}}",
'recentchangescount' => 'Niver a gemmoù da ziskouez dre ziouer',
'prefs-help-recentchangescount' => "Kemer a ra an dra-mañ e kont ar c'hemmoù diwezhañ, istor ar pajennoù hag ar marilhoù.",
'prefs-help-watchlist-token' => "Leuniañ ar c'hombod-mañ gant un dalvoudenn guzh a lakaio ul lanvad RSS war-sav evit ho rollad evezhiañ.
Kement den hag a ouio eus ho jedouer a c'hallo lenn ho rollad evezhiañ, dibabit un dalvoudegezh suraet.
Setu aze un dalvoudenn ganet dre zegouezh hag a c'hallfec'h implijout : $1",
'savedprefs' => 'Enrollet eo bet ar penndibaboù.',
'timezonelegend' => 'Takad eur :',
'localtime' => "Eur lec'hel :",
'timezoneuseserverdefault' => 'Ober gant talvoudenn dre ziouer ar wiki ($1)',
'timezoneuseoffset' => 'Arall (resisaat al linkadur)',
'timezoneoffset' => 'Linkadur eur¹ :',
'servertime' => 'Eur ar servijer :',
'guesstimezone' => 'Ober gant talvoudenn ar merdeer',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Azia',
'timezoneregion-atlantic' => 'Meurvor Atlantel',
'timezoneregion-australia' => 'Aostralia',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Meurvor Indez',
'timezoneregion-pacific' => 'Meurvor Habask',
'allowemail' => 'Aotren ar posteloù a-berzh implijerien all',
'prefs-searchoptions' => '!Dibarzhioù klask',
'prefs-namespaces' => 'Esaouennoù',
'defaultns' => 'Klask en esaouennoù-anv a-hend-all :',
'default' => 'dre ziouer',
'prefs-files' => 'Restroù',
'prefs-custom-css' => 'CSS personelaet',
'prefs-custom-js' => 'JS personelaet',
'prefs-common-css-js' => 'JavaScript ha CSS kenrannet evit an holl wiskadurioù :',
'prefs-reset-intro' => "Ober gant ar bajenn-mañ a c'hallit evit adlakaat ho penndibaboù dre ziouer evit al lec'hienn-mañ. Kement-se n'hallo ket bezañ disc'hraet da c'houde.",
'prefs-emailconfirm-label' => 'Kadarnaat ar postel :',
'prefs-textboxsize' => 'Ment ar prenestr skridaozañ',
'youremail' => 'Postel :',
'username' => 'Anv implijer :',
'uid' => 'Niv. identelezh an implijer :',
'prefs-memberingroups' => 'Ezel eus {{PLURAL:$1|ar strollad|ar strolladoù}}:',
'prefs-registration' => 'Deiziad enskrivañ :',
'yourrealname' => 'Anv gwir*',
'yourlanguage' => 'Yezh an etrefas&nbsp;',
'yourvariant' => 'Adstumm yezh :',
'prefs-help-variant' => 'An adstummoù yezh pe ar reizhskrivadur a gavit ar gwellañ evit diskouez pajennoù ar wiki.',
'yournick' => 'Sinadur :',
'prefs-help-signature' => 'Dleout a rafe an evezhiadennoù war ar pajennoù kaozeal bezañ sinet gant "<nowiki>~~~~</nowiki>" a vo treuzfurmet en ho sinadur hag euriet.',
'badsig' => 'Direizh eo ho sinadur kriz; gwiriit ho palizennoù HTML.',
'badsiglength' => "Re hir eo ho sinadur.
Dre ret e rank bezañ nebeutoc'h eget {{PLURAL:$1|arouezenn|arouezenn}} ennañ.",
'yourgender' => 'Jener :',
'gender-unknown' => 'Anresisaet',
'gender-male' => 'Paotr',
'gender-female' => "Plac'h",
'prefs-help-gender' => "Diret : implijet evit kenglotadurioù gour e troidigezh etrefas ar meziant.
A-wel d'an holl e vo an titour-mañ.",
'email' => 'Postel',
'prefs-help-realname' => "Diret eo skrivañ hoc'h anv gwir.
Ma skrivit anezhañ e vo implijet evit lakaat war wel ar pezh a vo bet degaset ganeoc'h.",
'prefs-help-email' => "Diret eo merkañ ur chomlec'h postel met ma lakait unan e vo tu da adkas ur ger-tremen nevez deoc'h ma tichañsfe deoc'h disoñjal ho hini.",
'prefs-help-email-others' => "Gallout a rit lezel tud all da vont e darempred ganeoc'h dre ho pajennoù implijer ha kaozeal hep na vefe ret deoc'h diskuliañ piv oc'h ivez.",
'prefs-help-email-required' => "Ezhomm zo eus ur chomlec'h postel.",
'prefs-info' => 'Titouroù diazez',
'prefs-i18n' => 'Etrebroadelaat',
'prefs-signature' => 'Sinadur',
'prefs-dateformat' => 'Furmad an deiziadoù',
'prefs-timeoffset' => 'Linkadur eur',
'prefs-advancedediting' => 'Dibarzhioù araokaet',
'prefs-advancedrc' => 'Dibarzhioù araokaet',
'prefs-advancedrendering' => 'Dibarzhioù araokaet',
'prefs-advancedsearchoptions' => 'Dibarzhioù araokaet',
'prefs-advancedwatchlist' => 'Dibarzhioù araokaet',
'prefs-displayrc' => 'Dibarzhioù diskwel',
'prefs-displaysearchoptions' => 'Dibarzhioù diskwel',
'prefs-displaywatchlist' => 'Dibarzhioù diskwel',
'prefs-diffs' => "Diforc'hioù",

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => "Reizh eo ar chomlec'h postel war a seblant",
'email-address-validity-invalid' => "Ebarzhit ur chomlec'h postel reizh",

# User rights
'userrights' => 'Merañ statud an implijerien',
'userrights-lookup-user' => 'Merañ strolladoù an implijer',
'userrights-user-editname' => 'Lakait un anv implijer :',
'editusergroup' => 'Kemmañ ar strolladoù implijerien',
'editinguser' => "Kemmañ gwirioù an implijer '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Kemmañ strolladoù an implijer',
'saveusergroups' => 'Enrollañ ar strolladoù implijer',
'userrights-groupsmember' => 'Ezel eus :',
'userrights-groupsmember-auto' => 'Ezel emplegat eus :',
'userrights-groups-help' => "Cheñch strollad an implijer a c'hallit ober.
* Ul log asket a verk emañ an implijer er strollad.
* Ul log diask a verk n'emañ ket an implijer er strollad.
* Ur * a verk n'hallit ket dilemel ar strollad ur wech bet ouzhpennet, pe ar c'hontrol.",
'userrights-reason' => 'Abeg :',
'userrights-no-interwiki' => "N'oc'h ket aotreet da gemmañ ar gwirioù implijer war wikioù all.",
'userrights-nodatabase' => "N'eus ket eus an diaz titouroù $1 pe n'eo ket lec'hel.",
'userrights-nologin' => "Ret eo deoc'h [[Special:UserLogin|bezañ enrollet]] gant ur gont merour a-benn reiñ gwirioù implijer.",
'userrights-notallowed' => "N'eo ket aotreet ho kont da cheñch gwirioù an implijerien.",
'userrights-changeable-col' => "Ar strolladoù a c'hallit cheñch",
'userrights-unchangeable-col' => "Ar strolladoù n'hallit ket cheñch",

# Groups
'group' => 'Strollad :',
'group-user' => 'Implijerien',
'group-autoconfirmed' => 'Implijerien bet kadarnaet ent emgefre',
'group-bot' => 'Robotoù',
'group-sysop' => 'Merourien',
'group-bureaucrat' => 'Burevidi',
'group-suppress' => 'Dindan evezh',
'group-all' => '(pep tra)',

'group-user-member' => '{{GENDER:$1|implijer}}',
'group-autoconfirmed-member' => '{{GENDER:$1|Implijer bet kadarnaet ent emgefre}}',
'group-bot-member' => '{{GENDER:$1|robot}}',
'group-sysop-member' => '{{GENDER:$1|merour}}',
'group-bureaucrat-member' => '{{GENDER:$1|bureviad}}',
'group-suppress-member' => '{{GENDER:$1|evezhier}}',

'grouppage-user' => '{{ns:project}}:Implijerien',
'grouppage-autoconfirmed' => '{{ns:project}}: Implijerien bet kadarnaet ent emgefre',
'grouppage-bot' => '{{ns:project}}:Botoù',
'grouppage-sysop' => '{{ns:project}}:Merourien',
'grouppage-bureaucrat' => '{{ns:project}}: Burevidi',
'grouppage-suppress' => '{{ns:project}}: Dindan evezh',

# Rights
'right-read' => 'Lenn ar pajennoù',
'right-edit' => 'Kemmañ ar pajennoù',
'right-createpage' => 'Krouiñ pajennoù (estreget pajennoù kaozeal)',
'right-createtalk' => 'Krouiñ pajennoù kaozeal',
'right-createaccount' => 'Krouiñ kontoù implijer nevez',
'right-minoredit' => "Merkañ ar c'hemmoù evel kemmoù dister",
'right-move' => 'Adenvel pajennoù',
'right-move-subpages' => "Dilec'hiañ ar pajennoù gant o ispajennoù",
'right-move-rootuserpages' => 'Adenvel pajennoù diazez an implijer',
'right-movefile' => "Dilec'hiañ ar restroù",
'right-suppressredirect' => 'Chom hep sevel un adkas adalek ar bajenn gozh en ur adenvel ar bajenn',
'right-upload' => 'Enporzhiañ restroù',
'right-reupload' => 'Frikañ ur restr zo anezhi dija',
'right-reupload-own' => 'Frikañ ur restr bet pellgarget gant an-unan',
'right-reupload-shared' => "Gwaskañ restroù ent lec'hel war an diellaoueg vedia rannet",
'right-upload_by_url' => "Enporzhiañ ur restr adal ur chomlec'h URL",
'right-purge' => 'Spujañ krubuilh ar pajennoù hep kadarnaat',
'right-autoconfirmed' => 'Kemmañ ar pajennoù damwarezet',
'right-bot' => 'Plediñ ganti evel gant un argerzh emgefre',
'right-nominornewtalk' => 'Arabat diskouez ar c\'hemenn "Kemennoù nevez zo ganeoc\'h" pa vez lakaet kemmoù dister e pajenn gaozeal un implijer',
'right-apihighlimits' => 'Kreskiñ ar bevennoù er goulennoù API',
'right-writeapi' => 'Ober gant an API evit kemmañ ar wiki',
'right-delete' => 'Diverkañ pajennoù',
'right-bigdelete' => 'Diverkañ pajennoù dezho un hir a istor',
'right-deletelogentry' => 'Dilemel hag assevel monedoù dibar eus ar renabl',
'right-deleterevision' => 'Diverkañ ha diziverkañ stummoù zo eus ur pajenn',
'right-deletedhistory' => 'Gwelet anvioù an istorioù diverket hep diskouez an destenn stag outo',
'right-deletedtext' => "Gwelet ar skrid diverket hag an diforc'hioù etre ar stummoù diverket",
'right-browsearchive' => 'Klask pajennoù bet diverket',
'right-undelete' => 'Assevel ur bajenn',
'right-suppressrevision' => 'Teuler ur sell war ar stummoù kuzhet ouzh ar verourien hag assevel anezho',
'right-suppressionlog' => 'Gwelet ar marilhoù prevez',
'right-block' => "Mirout ouzh an implijerien all a gemmañ pajennoù pelloc'h",
'right-blockemail' => 'Mirout ouzh un implijer a gas posteloù',
'right-hideuser' => 'Stankañ un implijer, en ur guzhat anezhañ diouzh ar re all',
'right-ipblock-exempt' => "Tremen dreist an IPoù stanket, ar stankadennoù emgefre hag ar bloc'hadennoù IP stanket",
'right-proxyunbannable' => 'Tremen dreist stankadennoù emgefre ar proksioù',
'right-unblockself' => 'En em zistankañ drezo o unan',
'right-protect' => 'Kemmañ live gwareziñ ar pajennoù ha kemmañ ar pajennoù gwarezet',
'right-editprotected' => 'Kemmañ ar pajennoù gwarezet (hep gwarez dre skalierad)',
'right-editinterface' => 'Kemmañ an etrefas implijer',
'right-editusercssjs' => 'Kemmañ restroù CSS ha JS implijerien all',
'right-editusercss' => 'Kemmañ restroù CSS implijerien all',
'right-edituserjs' => 'Kemmañ restroù JS implijerien all',
'right-rollback' => 'Disteuler prim kemmoù an implijer diwezhañ kemmet gantañ ur bajenn resis',
'right-markbotedits' => "Merkañ ar c'hemmoù distaolet evel kemmoù bet graet gant robotoù.",
'right-noratelimit' => 'Na sell ket ar bevennoù feurioù outañ',
'right-import' => 'Enporzhiañ pajennoù adalek wikioù all',
'right-importupload' => 'Enporzhiañ pajennoù adal ur restr',
'right-patrol' => 'Merkañ kemmoù ar re all evel gwiriet',
'right-autopatrol' => 'Merkañ e gemmoù evel gwiriekaet, ent emgefre',
'right-patrolmarks' => 'Gwelet kemmoù diwezhañ ar merkoù patrouilhañ',
'right-unwatchedpages' => "Gwelet roll ar pajennoù n'int ket evezhiet",
'right-mergehistory' => 'Unvaniñ istor ar pajennoù',
'right-userrights' => 'Kemmañ holl wirioù un implijer',
'right-userrights-interwiki' => 'Kemmañ ar gwirioù implijer zo war ur wiki all',
'right-siteadmin' => 'Prennañ ha dibrennañ ar bank-titouroù',
'right-override-export-depth' => 'Ezporzhiañ ar pajennoù en ur lakaat e-barzh ar pajennoù liammet betek un donder a 5 live',
'right-sendemail' => "Kas ur postel d'an implijerien all",
'right-passwordreset' => 'Gwelet ar posteloù assevel gerioù-tremen',

# User rights log
'rightslog' => 'Marilh statud an implijerien',
'rightslogtext' => "Setu marilh ar c'hemmoù statud bet c'hoarvezet d'an implijerien.",
'rightslogentry' => '{{Gender:.|en|he}} deus cheñchet gwirioù an {{Gender:.|implijer|implijerez}}  $1 a oa $2 hag a zo bet lakaet da $3',
'rightslogentry-autopromote' => 'zo bet anvet ent emgefre a $2 da $3',
'rightsnone' => '(netra)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lenn ar bajenn-mañ',
'action-edit' => 'kemmañ ar bajenn-mañ',
'action-createpage' => 'krouiñ pajennoù',
'action-createtalk' => 'krouiñ pajennoù kaozeal',
'action-createaccount' => 'krouiñ ar gont implijer-mañ',
'action-minoredit' => "merkañ ar c'hemm-mañ evel dister",
'action-move' => "dilec'hiañ ar bajenn-mañ",
'action-move-subpages' => "dilec'hiañ ar bajenn-mañ hag an ispajennoù anezhi",
'action-move-rootuserpages' => "dilec'hiañ pajennoù an implijer diazez.",
'action-movefile' => 'Adenvel ar restr-mañ',
'action-upload' => 'enporzhiañ ar restr-mañ',
'action-reupload' => 'frikañ ar restr-mañ',
'action-reupload-shared' => 'Frikañ ar restr-mañ zo war ur sanailh rannet',
'action-upload_by_url' => "pellgargañ ar restr-mañ adal ur chomlec'h URL",
'action-writeapi' => 'Ober gant an API skrivañ',
'action-delete' => 'diverkañ ar bajenn-mañ',
'action-deleterevision' => 'diverkañ ar stumm-mañ',
'action-deletedhistory' => 'Gwelet istor diverket ar bajenn-mañ',
'action-browsearchive' => 'Klask pajennoù bet diverket',
'action-undelete' => 'Diziverkañ ar bajenn-mañ',
'action-suppressrevision' => 'gwelet hag assevel ar stumm diverket-mañ',
'action-suppressionlog' => 'gwelet ar marilh prevez-mañ',
'action-block' => 'mirout ouzh an impplijer-mañ da zegas kemmoù',
'action-protect' => 'kemmañ liveoù gwareziñ ar bajenn-mañ',
'action-rollback' => 'disteuler prim kemmoù an implijer diwezhañ kemmet gantañ ur bajenn resis',
'action-import' => 'Enporzhiañ ar bajenn-mañ adal ur wiki all',
'action-importupload' => 'Enporzhiañ ar bajenn-mañ adal ur restr pellgarget',
'action-patrol' => 'merkañ kemmoù ar re all evel gwiriet',
'action-autopatrol' => 'bezañ merket ho tegasadennoù evel gwiriet',
'action-unwatchedpages' => "gwelet roll ar pajennoù n'int ket evezhiet",
'action-mergehistory' => 'kendeuziñ istor ar bajenn-mañ',
'action-userrights' => 'Kemmañ an holl wirioù implijer',
'action-userrights-interwiki' => 'Kemmañ gwirioù an implijerien war wikioù all',
'action-siteadmin' => 'Prennañ pe dibrennañ ar bank roadennoù',
'action-sendemail' => 'Kas posteloù',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|kemm|kemm}}',
'recentchanges' => 'Kemmoù diwezhañ',
'recentchanges-legend' => "Dibarzhioù ar c'hemmoù diwezhañ",
'recentchanges-summary' => "Dre ar bajenn-mañ e c'hallit heuliañ ar c'hemmoù diwezhañ bet degaset d'ar wiki.",
'recentchanges-feed-description' => "Heuilhit ar c'hemmoù diwezhañ er wiki el lusk-mañ.",
'recentchanges-label-newpage' => "Gant ar c'hemm-mañ e vo krouet ur bajenn nevez.",
'recentchanges-label-minor' => "Ur c'hemm dister eo hemañ",
'recentchanges-label-bot' => "Gant ur bot eo bet degaset ar c'hemm-mañ.",
'recentchanges-label-unpatrolled' => "N'eo ket bet gwiriet ar c'hemm-mañ evit c'hoazh.",
'rcnote' => "Setu aze an {{PLURAL:$1|'''1''' change|'''$1''' kemm diwezhañ}} bet c'hoarvezet e-pad an {{PLURAL:$2|deiz|'''$2''' deiz}} diwezhañ, savet d'an $4 da $5.",
'rcnotefrom' => "Setu aze roll ar c'hemmoù c'hoarvezet abaoe an '''$2''' ('''$1''' d'ar muiañ).",
'rclistfrom' => "Diskouez ar c'hemmoù diwezhañ abaoe an $1.",
'rcshowhideminor' => "$1 ar c'hemmoù dister",
'rcshowhidebots' => '$1 ar robotoù',
'rcshowhideliu' => '$1 an implijerien enrollet',
'rcshowhideanons' => '$1 an implijerien dizanv',
'rcshowhidepatr' => "$1 ar c'hemmoù gwiriet",
'rcshowhidemine' => "$1 ma c'hemmoù",
'rclinks' => "Diskouez an $1 kemm diwezhañ c'hoarvezet e-pad an $2 devezh diwezhañ<br />$3",
'diff' => "diforc'h",
'hist' => 'ist',
'hide' => 'kuzhat',
'show' => 'Diskouez',
'minoreditletter' => 'D',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|implijer o heuliañ|implijer}} o heuliañ]',
'rc_categories' => 'Bevenn ar rummadoù (dispartiañ gant "|")',
'rc_categories_any' => 'An holl',
'rc-change-size-new' => '$1 {{PLURAL:$1|okted|okted}} goude kemmañ',
'newsectionsummary' => '/* $1 */ rann nevez',
'rc-enhanced-expand' => 'Diskouez ar munudoù (JavaScript rekis)',
'rc-enhanced-hide' => 'Kuzhat munudoù',
'rc-old-title' => 'bet krouet da gentañ gant an anv "$1"',

# Recent changes linked
'recentchangeslinked' => 'Heuliañ al liammoù',
'recentchangeslinked-feed' => 'Heuliañ al liammoù',
'recentchangeslinked-toolbox' => 'Heuliañ al liammoù',
'recentchangeslinked-title' => 'Kemmoù a denn da "$1"',
'recentchangeslinked-noresult' => 'Kemm ebet war ar pajennoù liammet e-pad an amzer spisaet.',
'recentchangeslinked-summary' => "Rollet eo war ar bajenn dibar-mañ ar c'hemmoù diwezhañ bet degaset war ar pajennoù liammet ouzh ur bajenn lakaet (pe ouzh izili ur rummad lakaet).
E '''tev''' emañ ar pajennoù zo war ho [[Special:Watchlist|roll evezhiañ]].",
'recentchangeslinked-page' => 'Anv ar bajenn :',
'recentchangeslinked-to' => "Diskouez ar c'hemmoù war-du ar pajennoù liammet kentoc'h eget re ar bajenn lakaet",

# Upload
'upload' => 'Kargañ war ar servijer',
'uploadbtn' => 'Kargañ ur restr',
'reuploaddesc' => "Distreiñ d'ar furmskrid.",
'upload-tryagain' => 'Kas deskrivadur ar restr kemmet',
'uploadnologin' => 'Digevreet',
'uploadnologintext' => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreetet]] a-benn gellout enporzhiañ restroù war ar servijer.",
'upload_directory_missing' => "Mankout a ra ar c'havlec'h enporzhiañ ($1) ha n'eo ket bet ar servijer Web evit e grouiñ.",
'upload_directory_read_only' => "N'hall ket ar servijer skrivañ e renkell ar c'hargadennoù ($1).",
'uploaderror' => 'Fazi enporzhiañ',
'upload-recreate-warning' => "'''Diwallit''' : Diverket pe dilec'hiet ez eus bet ur restr gant an anv-se.'''

Deoc'h da c'houzout, setu aze marilh an diverkañ hag an dilec'hiañ evit ar bajenn-mañ.",
'uploadtext' => "Grit gant ar furmskrid a-is evit enporzhiañ restroù war ar servijer.
Evit sellet pe klask skeudennoù bet enporzhiet a-raok sellit ouzh [[Special:FileList|roll ar skeudennoù]]. Kavet e vo ar skeudennoù enporzhiet war [[Special:Log/upload|marilh ar pajennoù enporzhiet]] hag an diverkadennoù war [[Special:Log/delete|istor an diverkadennoù]].

Evit enklozañ ur skeudenn en ur pennad, lakait er pennad-se ul liamm skrivet evel-henn :
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:anv_ar_restr.jpg]]</nowiki></code>''' evit diskouez ar restr en he spider brasañ ;
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:anv_ar_restr.png|deskrivadenn]]</nowiki></code>''' evit ober gant ur munud 200 piksel ledander er ur voest a-gleiz enni \"testenn zeskrivañ\" da zeskrivadenn
*'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:anv_ar_restr.ogg]]</nowiki></code>''' evit sevel ul liamm war-eeun war-du ar restr hep diskouez anezhi.",
'upload-permitted' => 'Seurtoù restroù aotreet : $1.',
'upload-preferred' => 'Seurtoù restroù gwellañ : $1.',
'upload-prohibited' => 'Seurtoù restroù berzet : $1.',
'uploadlog' => 'marilh ar pajennoù enporzhiet',
'uploadlogpage' => 'Marilh ar pajennoù enporzhiet',
'uploadlogpagetext' => "Setu a-is marilh ar restroù diwezhañ bet karget war ar servijer.
S.o [[Special:NewFiles|rann ar skeudennoù nevez]] evit kaout ur sell gwiroc'h",
'filename' => 'Anv ar restr',
'filedesc' => 'Deskrivadur',
'fileuploadsummary' => 'Diverrañ :',
'filereuploadsummary' => 'Kemmoù er restr :',
'filestatus' => 'Statud a-fet gwirioù aozer :',
'filesource' => 'Mammenn :',
'uploadedfiles' => 'Restroù karget',
'ignorewarning' => 'Na ober van ouzh an evezhiadenn hag enrollañ ar restr forzh penaos',
'ignorewarnings' => "Na ober van ouzh ar c'hemennoù diwall",
'minlength1' => 'Anv ar restroù a rank bezañ keit hag ul lizherenn da nebeutañ.',
'illegalfilename' => "Lakaet ez eus bet er restr « $1 » arouezennoù n'int ket aotreet evit titl ur bajenn. Mar plij, adanvit ar restr hag adkasit anezhi.",
'filename-toolong' => "N'hallet ket anvioù ar restroù bezañ hiroc'h eget 240 okted.",
'badfilename' => 'Adanvet eo bet ar skeudenn « $1 ».',
'filetype-mime-mismatch' => 'Ne glot ket astenn ar restr ".$1" gant seurt MIME detektet ar restr ($2).',
'filetype-badmime' => 'N\'eo ket aotreet pellgargañ ar restroù a seurt MIME "$1".',
'filetype-bad-ie-mime' => 'Dibosupl enporzhiañ ar restr-mañ rak detektet e vefe evel "$1" gant Internet Explorer, ur seurt restroù berzet rak arvarus sañset.',
'filetype-unwanted-type' => "'''Eus ar seurt restroù n'int ket c'hoantaet eo \".\$1\"'''.  Ar re a zere ar gwellañ zo eus {{PLURAL:\$3|ar seurt|ar seurt}} \$2.",
'filetype-banned-type' => "'''N'eo ket \".\$1\"''' {{PLURAL:\$4|ur seurt restr aotreet|seurtoù restroù aotreet}}.
\$2 eo {{PLURAL:\$3|ar seurt restroù|ar seurtoù restroù}} degemeret.",
'filetype-missing' => 'N\'eus astenn ebet stag ouzh ar restr (evel ".jpg").',
'empty-file' => "Ar restr hoc'h eus roet a oa goullo.",
'file-too-large' => "Ar restr hoc'h eus roet a oa re vras.",
'filename-tooshort' => 'Re verr eo anv ar restr.',
'filetype-banned' => 'Difennet eo ar seurt restroù',
'verification-error' => 'Korbellet eo bet ar restr-mañ gant ar gwiriañ restroù.',
'hookaborted' => "Ar c'hemm hoc'h eus klasket degas zo bet harzet gant ur c'hrog astenn.",
'illegal-filename' => "N'eo ket aotreet anv ar restr.",
'overwrite' => "N'eo ket aotreet frikañ ur restr zo anezhi c'hoazh.",
'unknown-error' => "C'hoarvezet ez eus ur gudenn dianav.",
'tmp-create-error' => 'Dibosupl eo krouiñ ar restr padennek.',
'tmp-write-error' => 'Ur gudenn skrivañ a zo bet evit ar restr padennek.',
'large-file' => "Erbediñ a reer ne vefe ket brasoc'h ar restroù eget $1; $2 eo ment ar restr-mañ.",
'largefileserver' => "Brasoc'h eo ar restr-mañ eget ar pezh a c'hall ar servijer aotren.",
'emptyfile' => "Evit doare eo goullo ar restr bet karget ganeoc'h. Moarvat eo abalamour d'an tipo en anv ar restr. Gwiriit mat e fell deoc'h pellgargañ ar restr-mañ.",
'windows-nonascii-filename' => "N'eo ket skoret anvioù ar restroù enno arouezennoù dibar gant ar wiki-mañ.",
'fileexists' => "Ur restr all gant an anv-se zo c'hoazh.
Trugarez da wiriañ <strong>[[:$1]]</strong> ma n'oc'h ket sur e fell deoc'h kemmañ anezhi.
[[$1|thumb]]",
'filepageexists' => "Amañ <strong>[[:$1]]</strong> eo bet krouet ar bajenn zeskrivañ evit ar restr-mañ, padal n'eus restr ebet dezhi an anv-se evit c'hoazh.
An diverradenn skrivet ganeoc'h ne vo ket gwelet war ar bajenn zeskrivañ.
Mar fell deoc'h e teufe ho tiverradenn war wel eno eo ret deoc'h-c'hwi kemmañ anezhi hoc'h-unan.
[[$1|thumb]]",
'fileexists-extension' => "Bez' ez eus dija ur restr gant an anv-se war-bouez nebeut : [[$2|thumb]]
* Anv ar restr emeur oc'h enporzhiañ : <strong>[[:$1]]</strong>
* Anv ar restr zo anezhi dija : <strong>[[:$2]]</strong>
Dibabit un anv all mar plij.",
'fileexists-thumbnail-yes' => "Evit doare ez eus ur skeudenn krennet he ment eus ar restr ''(thumbnail)''. [[$1|thumb]]
Gwiriit ar restr <strong>[[:$1]]</strong>.
Mard eo an hevelep skeudenn ha hini ar restr orin, ha heñvel he ment, n'eo ket dav pellgargañ ur stumm krennet ouzhpenn.",
'file-thumbnail-no' => "Kregiñ a ra anv ar restr gant <strong>$1</strong>.
Evit doare eo ur skeudenn krennet he ment ''(thumbnail)''.
Ma'z eus ganeoc'h ur skeudenn uhel he fizhder, pellgargit anezhi; a-hend-all cheñchit anv ar restr.",
'fileexists-forbidden' => "Ur restr all gant an anv-se zo c'hoazh ha n'hall ket bezan diverket.
Mar fell deoc'h enporzhiañ ho restr memes tra, kit war ho kiz ha grit gant un anv all [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Ur restr all dezhi an hevelep anv zo c'hoazh er c'havlec'h eskemm restroù.
Mar fell deoc'h enporzhiañ ar restr-mañ da vat, kit war ho kiz hag enporzhiit anezhi adarre dindan un anv all. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate' => 'Un eil eus ar {{PLURAL:$1|restr|restroù}} da-heul eo ar restr-mañ :',
'file-deleted-duplicate' => "Diverket ez eus bet c'hoazh ur restr heñvel-poch ouzh ar restr-mañ ([[:$1]]). Gwelloc'h e vefe deoc'h teuler ur sell war istor diverkadenn ar bajenn-se a-raok hec'h enporzhiañ en-dro.",
'uploadwarning' => 'Kemenn diwall en ur ezporzhiañ',
'uploadwarning-text' => 'Cheñchit deskrivadur ar restr a-is ha klaskit en-dro.',
'savefile' => 'Enrollañ ar restr',
'uploadedimage' => '"[[$1]]" enporzhiet',
'overwroteimage' => 'enporzhiet ur stumm nevez eus "[[$1]]"',
'uploaddisabled' => 'Ho tigarez, diweredekaet eo bet kas ar restr-mañ.',
'copyuploaddisabled' => 'Diweredekaet eo bet ar pellgargañ dre URL.',
'uploadfromurl-queued' => 'Lakaet eo bet ho pellgargadenn er roll gortoz.',
'uploaddisabledtext' => 'Diweredekaet eo an enporzhiañ restroù.',
'php-uploaddisabledtext' => 'Diweredekaet eo bet ar pellgargañ e PHP. Gwiriit an dibarzh arventennoù file_uploads.',
'uploadscripted' => "Er restr-mañ ez eus kodoù HTML pe skriptoù a c'hallfe bezañ kammgomprenet gant ur merdeer Kenrouedad.",
'uploadvirus' => 'Viruzet eo ar restr! Titouroù : $1',
'uploadjava' => "Ur restr ZIP a ra gant Java .class eo homañ.
N'haller ket enporzhiañ restroù Java peogwir e c'haller mont dreist da vevennoù surentez ganto.",
'upload-source' => 'Restr orin',
'sourcefilename' => 'Anv ar restr tarzh :',
'sourceurl' => 'URL tarzh :',
'destfilename' => 'Anv ma vo enrollet ar restr :',
'upload-maxfilesize' => 'Ment vrasañ ar restr : $1',
'upload-description' => 'Deskrivadur ar restr',
'upload-options' => 'Dibaboù kargañ',
'watchthisupload' => 'Evezhiañ ar bajenn-mañ',
'filewasdeleted' => "Ur restr gant an anv-mañ zo bet enporzhiet dija ha diverket goude-se. Mat e vefe deoc'h gwiriañ an $1 a-raok hec'h enporzhiañ en-dro.",
'filename-bad-prefix' => "Anv ar restr emaoc'h oc'h enporzhiañ a grog gant '''\"\$1\"''', da lavaret eo un anv dizeskrivus roet alies ent emgefre gant luc'hskeudennerezioù niverel. Dibabit un anv splannoc'h evit deskrivañ ar restr.",
'filename-prefix-blacklist' => " #<!-- lezel al linenn-mañ tre ha tre evel m'emañ --> <pre>
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
'upload-success-subj' => 'Eiladenn kaset da benn vat',
'upload-success-msg' => 'Ho kargadenn eus [$2] a zo bet graet. Gellout a rit kavout anezhi amañ : [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Kudenn kargañ',
'upload-failure-msg' => "Ur gudenn 'zo bet e-pad ho kargadenn adalek [$2] :

$1",
'upload-warning-subj' => 'Kemenn diwall en ur ezporzhiañ',
'upload-warning-msg' => "Ur gudenn zo bet e-kerzh hoc'h ezporzhiadur eus [$2]. Gallout a c'heller distreiñ d'ar [[Special:Upload/stash/$1|furmskrid ezporzhiañ]] evit renkañ ar gudenn.",

'upload-proto-error' => 'Protokol direizh',
'upload-proto-error-text' => 'Rekis eo an URLoù a grog gant <code>http://</code> pe <code>ftp://</code> evit enporzhiañ.',
'upload-file-error' => 'Fazi diabarzh',
'upload-file-error-text' => "Ur fazi diabarzh zo c'hoarvezet en ur grouiñ ur restr da c'hortoz war ar servijer.
Kit e darempred gant [[Special:ListUsers/sysop|unan eus merourien ar reizhiad]].",
'upload-misc-error' => 'Fazi kargañ dianav',
'upload-misc-error-text' => "Ur fazi dianav zo bet e-ser kargañ.
Gwiriit eo reizh an URL hag e c'hall bezañ tizhet ha klaskit en-dro.
Ma talc'h ar gudenn, kit e darempred gant [[Special:ListUsers/sysop|merourien ar reizhiad]].",
'upload-too-many-redirects' => 'Re a adkasoù zo en URL-mañ.',
'upload-unknown-size' => 'Ment dianav',
'upload-http-error' => 'Ur fazi HTTP zo bet : $1',
'upload-copy-upload-invalid-domain' => "N'haller ket seveniñ enporzhiadennoù a-bell adalek an domani-mañ.",

# File backend
'backend-fail-stream' => 'Dibosupl eo lenn ar restr $1.',
'backend-fail-backup' => 'Dibosupl enrollañ ar restr $1.',
'backend-fail-notexists' => "N'eus ket eus ar restr $1.",
'backend-fail-hashes' => 'Dibosupl eo bet tapout hacherezh ar restr evit keñveriañ.',
'backend-fail-notsame' => "Ur restr disheñvel zo e $1 c'hoazh.",
'backend-fail-invalidpath' => "$1 n'eo ket un hent stokañ reizh.",
'backend-fail-delete' => 'Dibosupl eo diverkañ ar restr $1.',
'backend-fail-alreadyexists' => 'Ar restr "$1" zo anezhi c\'hoazh.',
'backend-fail-store' => 'Dibosupl stokañ ar restr $1 e $2.',
'backend-fail-copy' => 'Dibosupl eilañ ar restr "$1" war-du "$2".',
'backend-fail-move' => 'Dibosupl kas ar restr "$1" war-du "$2".',
'backend-fail-opentemp' => 'Dibosupl eo digeriñ ar restr padennek.',
'backend-fail-writetemp' => 'Dibosupl skrivañ er restr padennek.',
'backend-fail-closetemp' => 'Dibosupl eo serriñ ar restr padennek.',
'backend-fail-read' => 'Dibosupl lenn ar restr $1.',
'backend-fail-create' => 'Dibosupl eo krouiñ ar restr $1.',
'backend-fail-maxsize' => "Dibosupl skrivañ er restr $1 peogwir eo brasoc'h eget {{PLURAL:$2|un okted|$2 okted}}.",
'backend-fail-readonly' => 'Emañ an dermenell stokañ "$1" e mod lenn hepken. Setu aze an abeg a oa bet roet : "$2".',
'backend-fail-synced' => 'Emañ ar restr "$1" en ur stad direizhet en termenelloù stokañ diabarzh',
'backend-fail-connect' => 'Dibosupl kevreañ ouzh termenell stokañ ar restr "$1".',
'backend-fail-internal' => 'C\'hoarvezet ez eus ur fazi dianav e termenell stokañ ar restr "$1".',
'backend-fail-contenttype' => 'Dibosupl termeniñ ar seurt danvez da enrollañ e "$1".',
'backend-fail-batchsize' => 'Pourvezet he deus an dermenell stokañ ur pakad a $1 {{PLURAL:$1|oberiadenn|oberiadenn}}; sevel a ra ar vevenn da $2 {{PLURAL:$2|oberiadenn|oberiadenn}}.',
'backend-fail-usable' => 'Dibosupl skrivañ ar restr "$1" rak re skort eo an aotreoù pe mankout a ra kavlec\'hioù/endalc\'herioù.',

# File journal errors
'filejournal-fail-dbconnect' => 'N\'eus ket bet gallet kevreañ ouzh diaz roadennoù ar marilh evit an dermenell stokañ "$1".',
'filejournal-fail-dbquery' => 'N\'eus ket bet gallet hizivaat diaz roadennoù marilh an dermenell stokañ "$1".',

# Lock manager
'lockmanager-notlocked' => 'Dibosupl dibrennañ "$1"; n\'eo ket prennet.',
'lockmanager-fail-closelock' => 'Dibosupl serriñ ar restr prennañ evit "$1".',
'lockmanager-fail-deletelock' => 'Dibosupl diverkañ ar restr prennañ evit "$1".',
'lockmanager-fail-acquirelock' => 'Dibosupl tapout ar prenn evit "$1".',
'lockmanager-fail-openlock' => 'Dibosupl digeriñ ar restr prennañ evit "$1".',
'lockmanager-fail-releaselock' => 'Dibosupl leuskel ar prenn digor evit "$1".',
'lockmanager-fail-db-bucket' => "Dibosupl mont e darempred gant diazoù roadennoù a-walc'h evit ar c'helornad $1.",
'lockmanager-fail-db-release' => 'Dibosupl da leuskel ar prennoù digor war an diaz roadennoù $1.',
'lockmanager-fail-svr-acquire' => 'Dibosupl eo bet tapout ar prennoù war ar servijer $1.',
'lockmanager-fail-svr-release' => 'Dibosupl da leuskel ar prennoù digor war ar servijer $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Kavet ez eus bet ur fazi en ur zigeriñ ar restr evit kas da benn gwiriadennoù ZIP.',
'zip-wrong-format' => "Ar restr spisaet n'eo ket ur restr ZIP anezhi",
'zip-bad' => "Brein eo ar restr pe ur restr ZIP dilennus eo.
N'hall ket bezañ gwiriet ervat evit ar surentez.",
'zip-unsupported' => "Ur restr ZIP a ra gant perzhioù ZIP n'int ket skoret gant MediaWiki eo ar restr-mañ.
N'hall ket bezañ gwiriet ervat evit ar surentez.",

# Special:UploadStash
'uploadstash' => 'Krubuilh enporzhiañ',
'uploadstash-summary' => "Reiñ ar bajenn-mañ tu da vont war ar restroù enporzhiet (pe o vezañ enporzhiet) ha n'int ket bet embannet war ar wiki-mañ evit c'hoazh. Den n'hall gwelet ar restroù-se evit ar mare, nemet an hini en deus enporzhiet anezho.",
'uploadstash-clear' => 'Diverkañ ar restroù krubuilhet',
'uploadstash-nofiles' => "N'eus bet krubuilhet restr ebet.",
'uploadstash-badtoken' => "N'haller ket kas an ober-mañ da benn vat, marteze a-walc'h abalamour m'eo aet d'o zermen an titouroù kred ho poa roet. Klaskit en-dro.",
'uploadstash-errclear' => "N'eus ket bet gallet riñsañ ar restroù.",
'uploadstash-refresh' => 'Freskaat roll ar restroù',
'invalid-chunk-offset' => 'Direizh eo offset ar rannad',

# img_auth script messages
'img-auth-accessdenied' => "Moned nac'het",
'img-auth-nopathinfo' => "Mankout a ra ar PATH_INFO.
N'eo ket kefluniet ho servijer evit reiñ an titour-mañ.
Marteze eo diazezet war CGI ha n'hall ket skorañ img_auth.
Gwelet https://www.mediawiki.org/wiki/Manual:Image_Authorization",
'img-auth-notindir' => "N'emañ ket an hent merket er c'havlec'h enporzhiañ kefluniet.",
'img-auth-badtitle' => 'Dibosupl krouiñ un titl reizh adalek "$1".',
'img-auth-nologinnWL' => 'N\'oc\'h ket kevreet ha n\'emañ ket "$1" war ar roll gwenn',
'img-auth-nofile' => 'n\'eus ket eus ar restr "$1".',
'img-auth-isdir' => "Klakset hoc'h eus monet d'ar c'havlec'h \"\$1\".
N'haller monet nemet d'ar restroù.",
'img-auth-streaming' => 'O lenn en ur dremen "$1"',
'img-auth-public' => "Talvezout a ra an arc'hwel img_auth.php da ezvont restroù adalek ur wiki prevez.
Kefluniet eo bet ar wiki-mañ evel ur wiki foran.
Diweredekaet eo bet img_auth.php evit ur surentez eus ar gwellañ",
'img-auth-noread' => 'N\'eo ket aotreet an implijer da lenn "$1"',
'img-auth-bad-query-string' => 'Un neudennad goulenn direizh zo gant an URL.',

# HTTP errors
'http-invalid-url' => 'URL direizh : $1',
'http-invalid-scheme' => 'N\'eo ket skoret an URLoù gant ar patrom "$1"',
'http-request-error' => "Ur fazi dianavezet 'zo bet pa veze kaset ar reked.",
'http-read-error' => 'Fazi lenn HTTP.',
'http-timed-out' => 'Erru eo termen ar reked HTTP.',
'http-curl-error' => 'Fazi adtapout an URL : $1',
'http-host-unreachable' => "N'eus ket bet gallet tizhout an URL.",
'http-bad-status' => 'Ur gudenn a zo bet e-pad ar reked HTTP : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "N'eus ket bet gallet tizhout an URL",
'upload-curl-error6-text' => "N'eus ket bet gallet tizhout an URL. Gwiriit mat eo reizh an URL hag emañ al lec'hienn enlinenn.",
'upload-curl-error28' => "Aet dreist d'an termen",
'upload-curl-error28-text' => "Re bell eo bet al lec'hienn o respont. Gwiriit mat emañ al lec'hienn enlinenn, gortozit ur pennadig ha klaskit en-dro. Mat e vo deoc'h adklask d'ur mare dibresoc'h marteze ivez.",

'license' => 'Aotre-implijout :',
'license-header' => 'Aotre implijout',
'nolicense' => 'Hini ebet diuzet',
'license-nopreview' => '(Dibosupl rakwelet)',
'upload_source_url' => " (Un URL reizh a c'hall bezañ tizhet gant an holl)",
'upload_source_file' => " (ur restr war hoc'h urzhiataer)",

# Special:ListFiles
'listfiles-summary' => 'Diskouez a ra ar bajenn dibar-mañ an holl restroù bet enporzhiet.
Pa vez silet dre implijerien, ne vez diskouezet nemet ar restroù eo bet enporzhiet ar stumm diwezhañ anezho gant an implijerien-se.',
'listfiles_search_for' => 'Klask anv ar skeudenn :',
'imgfile' => 'restr',
'listfiles' => 'Roll ar skeudennoù',
'listfiles_thumb' => 'Munud',
'listfiles_date' => 'Deiziad',
'listfiles_name' => 'Anv',
'listfiles_user' => 'Implijer',
'listfiles_size' => 'Ment (e bitoù)',
'listfiles_description' => 'Deskrivadur',
'listfiles_count' => 'Stummoù',

# File description page
'file-anchor-link' => 'Skeudenn',
'filehist' => 'Istor ar restr',
'filehist-help' => 'Klikañ war un deiziad/eur da welet ar restr evel ma oa da neuze.',
'filehist-deleteall' => 'diverkañ pep tra',
'filehist-deleteone' => 'diverkañ',
'filehist-revert' => 'disteuler',
'filehist-current' => 'red',
'filehist-datetime' => 'Deiziad/Eur',
'filehist-thumb' => 'Munud',
'filehist-thumbtext' => 'Munud eus stumm an $1',
'filehist-nothumb' => 'Munud ebet',
'filehist-user' => 'Implijer',
'filehist-dimensions' => 'Mentoù',
'filehist-filesize' => 'Ment ar restr',
'filehist-comment' => 'Notenn',
'filehist-missing' => 'Restr diank',
'imagelinks' => 'Implij ar restr',
'linkstoimage' => "Liammet eo {{PLURAL:$1|ar bajenn-mañ|an $1 pajenn-mañ}} d'ar restr-mañ :",
'linkstoimage-more' => "Ouzhpenn $1 {{PLURAL:$1|bajenn zo liammet ouzh|pajenn zo liammet ouzh}} ar restr-mañ.
Ne laka ar roll-mañ war wel nemet {{PLURAL:$1|ar bajenn gentañ liammet ouzh|an $1 pajenn gentañ liammet ouzh}} ar rest-mañ.
Ur [[Special:WhatLinksHere/$2|roll klok]] a c'haller da gaout.",
'nolinkstoimage' => "N'eus liamm ebet war-du ar skeudenn-mañ war pajenn ebet.",
'morelinkstoimage' => 'Gwelet [[Special:WhatLinksHere/$1|liammoù ouzhpenn]] war-du ar restr-mañ.',
'linkstoimage-redirect' => '$1 (adkas restr) $2',
'duplicatesoffile' => "Un eil eus ar restr-mañ eo {{PLURAL:$1|ar restr da-heul|ar restroù da-heul}}, ([[Special:FileDuplicateSearch/$2|evit gouzout hiroc'h]]) :",
'sharedupload' => 'Dont a ra ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.',
'sharedupload-desc-there' => "Tennet eo ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.
Mar fell deoc'h gouzout hiroc'h sellit ouzh [$2 ar bajenn zeskrivañ].",
'sharedupload-desc-here' => 'Tennet eo ar restr-mañ eus $1 ha gallout a ra bezañ implijet evit raktresoù all.
Diskouezet eo deskrivadur he [$2 fajenn zeskrivañ] amañ dindan.',
'sharedupload-desc-edit' => "Dont a ra ar restr-mañ eus $1 hag ober ganti a c'haller evit raktresoù all.
Marteze a-walc'h e fell deoc'h kemmañ an deskrivadur anezhi war ar [$2 bajenn deskrivañ] amañ.",
'sharedupload-desc-create' => "Dont a ra ar restr-mañ eus $1 hag ober ganti a c'haller evit raktresoù all.
Marteze a-walc'h e fell deoc'h kemmañ an deskrivadur anezhi war ar [$2 bajenn deskrivañ] aze.",
'filepage-nofile' => "N'eus restr ebet dezhi an anv-se.",
'filepage-nofile-link' => "N'eus restr ebet dezhi an anv-se, met gallout a rit [$1 pellgargañ anezhi].",
'uploadnewversion-linktext' => 'Kargañ ur stumm nevez eus ar restr-mañ',
'shared-repo-from' => 'eus $1',
'shared-repo' => 'ur sanailh rannet',
'upload-disallowed-here' => "Ne c'hallit ket erlec'hiañ ar restr-mañ",

# File reversion
'filerevert' => 'Disteuler $1',
'filerevert-legend' => 'Disteuler ar restr',
'filerevert-intro' => "Emaoc'h o tistreiñ '''[[Media:$1|$1]]''' d'ar [stumm $4 eus $3, $2].",
'filerevert-comment' => 'Abeg :',
'filerevert-defaultcomment' => 'Distroet da stumm $2, $1',
'filerevert-submit' => 'Disteuler',
'filerevert-success' => "'''Distroet eo bet [[Media:$1|$1]]''' da [stumm $4 an $3, $2].",
'filerevert-badversion' => "N'eus stumm lec'hel kent ebet eus ar restr-mañ d'ar mare spisaet.",

# File deletion
'filedelete' => 'Diverkañ $1',
'filedelete-legend' => 'Diverkañ ar restr',
'filedelete-intro' => "War-nes diverkañ '''[[Media:$1|$1]]''' a-gevret gant e istor emaoc'h.",
'filedelete-intro-old' => "Emaoc'h o tiverkañ stumm '''[[Media:$1|$1]]''' eus [$4 $3, $2].",
'filedelete-comment' => 'Abeg :',
'filedelete-submit' => 'Diverkañ',
'filedelete-success' => "Diverket eo bet '''$1'''.",
'filedelete-success-old' => "Diverket eo bet ar stumm '''[[Media:$1|$1]]''' eus an $2 da $3.",
'filedelete-nofile' => "N'eus ket eus '''$1'''.",
'filedelete-nofile-old' => "N'eus stumm diellaouet ebet eus '''$1''' gant an dezverkoù lakaet.",
'filedelete-otherreason' => 'Abeg all/ouzhpenn :',
'filedelete-reason-otherlist' => 'Abeg all',
'filedelete-reason-dropdown' => "*Abegoù diverkañ boas
** Gaou ouzh ar gwirioù perc'hennañ
** Restr zo anezhi dija",
'filedelete-edit-reasonlist' => 'Kemmañ a ra an abegoù diverkañ',
'filedelete-maintenance' => "Evit ar mare eo diweredekaet an diverkañ hag an assevel restroù, amzer d'ober un tamm trezalc'h.",
'filedelete-maintenance-title' => 'Dibosupl diverkañ ar restr',

# MIME search
'mimesearch' => 'Klask MIME',
'mimesearch-summary' => 'Aotren a ra ar bajenn-mañ ar silañ restroù evit ar seurt restroù MIME. Enmont : seurt/isseurt, evel <code>skeudenn/jpeg</code>.',
'mimetype' => 'Seurt MIME :',
'download' => 'pellgargañ',

# Unwatched pages
'unwatchedpages' => "Pajennoù n'int ket evezhiet",

# List redirects
'listredirects' => 'Roll an adkasoù',

# Unused templates
'unusedtemplates' => 'Patromoù dizimplij',
'unusedtemplatestext' => 'Rollet eo amañ an holl bajennoù zo en esaouenn anv "{{ns:template}}" ha n\'int ket implijet war pajenn ebet. Ho pet soñj da wiriañ mat hag-eñ n\'eus ket liammoù all war-du ar patromoù-se a-raok diverkañ anezho.',
'unusedtemplateswlh' => 'liammoù all',

# Random page
'randompage' => 'Ur bajenn dre zegouezh',
'randompage-nopages' => 'N\'eus pajenn ebet en {{PLURAL:$2|esaouennn anv|esaouennoù anv}} da-heul : "$1".',

# Random redirect
'randomredirect' => 'Ur bajenn adkas dre zegouezh',
'randomredirect-nopages' => 'N\'eus pajenn adkas ebet en esaouenn anv "$1".',

# Statistics
'statistics' => 'Stadegoù',
'statistics-header-pages' => 'Stadegoù ar pajennoù',
'statistics-header-edits' => "Stadegoù ar c'hemmoù",
'statistics-header-views' => 'Stadegoù ar selladennoù',
'statistics-header-users' => 'Stadegoù implijer',
'statistics-header-hooks' => 'Stadegoù all',
'statistics-articles' => "Pajennoù endalc'had",
'statistics-pages' => 'Pajennoù',
'statistics-pages-desc' => 'Holl bajennoù ar wiki, en o zouez ar pajennoù kaozeal, an adkasoù, h.a.',
'statistics-files' => 'Restroù enporzhiet',
'statistics-edits' => 'Kemmoù war ar pajennoù abaoe krouidigezh {{SITENAME}}',
'statistics-edits-average' => "Keidenn ar c'hemmoù dre bajenn",
'statistics-views-total' => 'Hollad ar selladennoù',
'statistics-views-total-desc' => "N'haller ket mont war ar pajennoù n'eus ket anezho pe war ar pajennoù dibar.",
'statistics-views-peredit' => 'Keidenn gweladenniñ dre gemmoù',
'statistics-users' => '[[Special:ListUsers|Implijerien]] enrollet',
'statistics-users-active' => 'Implijerien oberiant',
'statistics-users-active-desc' => "Implijerien o deus degaset da nebeutañ ur c'hemm {{PLURAL:$1|an deiz paseet|e-kerzh an $1 deiz diwezhañ}}",
'statistics-mostpopular' => 'Pajennoù muiañ sellet',

'disambiguations' => 'Pajennoù enno liammoù war-zu pajennoù disheñvelout',
'disambiguationspage' => 'Template:Disheñvelout',
'disambiguations-text' => "Liammet eo ar pajennoù da-heul ouzh ur '''bajenn disheñvelout'''.
Padal e tlefent kas war-eeun d'an danvez anezho.<br />
Sellet e vez ouzh ur bajenn evel ouzh ur bajenn disheñvelout ma ra gant ur patrom liammet ouzh [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Adkasoù doubl',
'doubleredirectstext' => 'Rollañ a ra ar bajenn-mañ ar pajennoù a adkas da bajennoù adkas all.
War bep linenn ez eus liammoù war-du pajennoù an adkas kentañ hag en eil adkas, hag ivez war-du pajenn-dal an eil adkas zo sañset bezañ ar pal "gwirion" a zlefe an adkas kentañ kas di.
Diskoulmet eo bet an enmontoù <del>barrennet</del>.',
'double-redirect-fixed-move' => 'Adanvet eo bet [[$1]], adkaset eo war-du [[$2]] bremañ',
'double-redirect-fixed-maintenance' => 'O reizhañ an adkas doubl adalek [[$1]] war-zu [[$2]].',
'double-redirect-fixer' => 'Reizher adkasoù',

'brokenredirects' => 'Adkasoù torret',
'brokenredirectstext' => "Kas a ra an adkasoù-mañ da bajennoù n'eus ket anezho.",
'brokenredirects-edit' => 'kemmañ',
'brokenredirects-delete' => 'diverkañ',

'withoutinterwiki' => 'Pajennoù hep liammoù yezh',
'withoutinterwiki-summary' => "Ar pajennoù da-heul n'int ket liammet ouzh yezh all ebet :",
'withoutinterwiki-legend' => 'Rakger',
'withoutinterwiki-submit' => 'Diskouez',

'fewestrevisions' => 'Pennadoù reizhet an nebeutañ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|eizhbit|eizhbit}}',
'ncategories' => '
$1 {{PLURAL:$1|rummad|rummad}}',
'ninterwikis' => ' {{PLURAL:$1|interwiki|interwikis}}',
'nlinks' => '$1 {{PLURAL:$1|liamm|liamm}}',
'nmembers' => '$1 {{PLURAL:$1|elfenn|elfenn}}',
'nrevisions' => '$1 {{PLURAL:$1|stumm|stumm}}',
'nviews' => '$1 {{PLURAL:$1|selladenn|selladenn}}',
'nimagelinks' => 'Implijet e $1 {{PLURAL:$1|pajenn|pajenn}}',
'ntransclusions' => 'implijet e $1 {{PLURAL:$1|pajenn|pajenn}}',
'specialpage-empty' => 'Goullo eo ar bajenn-mañ.',
'lonelypages' => 'Pajennoù hep liamm daveto',
'lonelypagestext' => "N'eo ket liammet pe enframmet ar pajennoù da-heul ouzh pajenn all ebet eus {{SITENAME}}.",
'uncategorizedpages' => 'Pajennoù dirumm',
'uncategorizedcategories' => 'Rummadoù dirumm',
'uncategorizedimages' => 'Restroù hep rummad',
'uncategorizedtemplates' => 'Patromoù hep rummad',
'unusedcategories' => 'Rummadoù dizimplij',
'unusedimages' => 'Skeudennoù en o-unan',
'popularpages' => 'Pajennoù sellet ar muiañ',
'wantedcategories' => 'Rummadoù goulennet a vank',
'wantedpages' => 'Pajennoù goulennet ar muiañ',
'wantedpages-badtitle' => "Titl direizh er strollad disoc'hoù : $1",
'wantedfiles' => 'Restroù a vank',
'wantedfiletext-cat' => "Ober a reer gant ar restroù da-heul koulskoude n'eus ket anezho. Gallout a reer rollañ kavlec'hioù diavaez ha pa vefe anezho. <del>Barrennet</del> e vo an holl falspozitivoù-se. Ouzhpenn-se emañ renablet an holl bajennoù zo enno restroù n'eus ket anezho e [[:$1]].",
'wantedfiletext-nocat' => "Ober a reer gant ar restroù da-heul koulskoude n'eus ket anezho. Gallout a reer rollañ kavlec'hioù diavaez ha pa vefe anezho. <del>Barrennet</del> e vo an holl falspozitivoù-se.",
'wantedtemplates' => 'Patromoù a vank',
'mostlinked' => 'Pajennoù dezho al liammoù niverusañ',
'mostlinkedcategories' => 'Rummadoù dezho al liammoù niverusañ',
'mostlinkedtemplates' => 'Patromoù implijet ar muiañ',
'mostcategories' => 'Pennadoù rummatet ar muiañ',
'mostimages' => 'Skeudennoù implijet ar muiañ',
'mostinterwikis' => 'Pajennoù gant an niver brasañ a etrewikioù',
'mostrevisions' => 'Pennadoù bet kemmet ar muiañ',
'prefixindex' => 'An holl bajennoù a grog gant...',
'prefixindex-namespace' => 'An holl bajennoù enno ur rakger (esaouenn anv $1)',
'shortpages' => 'Pennadoù berr',
'longpages' => 'Pennadoù hir',
'deadendpages' => 'Pajennoù dall (hep liamm diabarzh)',
'deadendpagestext' => "Ar pajennoù da-heul n'int ket liammet ouzh pajenn ebet all eus {{SITENAME}}.",
'protectedpages' => 'Pajennoù gwarezet',
'protectedpages-indef' => 'Gwarezoù da badout hepken',
'protectedpages-cascade' => 'Gwarez dre skalierad hepken',
'protectedpagestext' => "Gwarezet eo ar pajennoù da-heul; n'haller na kemmañ anezho nag o dilec'hiañ",
'protectedpagesempty' => "N'eus pajenn gwarezet ebet gant an arventennoù-mañ evit poent.",
'protectedtitles' => 'Titloù gwarezet',
'protectedtitlestext' => "An titloù da-heul zo bet gwarezet p'int bet krouet",
'protectedtitlesempty' => "N'eus bet gwarezet titl ebet dezhañ an arventennoù-se evit poent.",
'listusers' => 'Roll an implijerien',
'listusers-editsonly' => 'Na ziskouez nemet an implijerien o deus degaset un dra bennak',
'listusers-creationsort' => 'Renket dre urzh krouiñ',
'usereditcount' => '$1 {{PLURAL:$1|kemm|kemm}}',
'usercreated' => "{{GENDER:$3|Krouet}} d'an $1 da $2",
'newpages' => 'Pajennoù nevez',
'newpages-username' => 'Anv implijer :',
'ancientpages' => 'Pennadoù koshañ',
'move' => 'adenvel',
'movethispage' => 'Adenvel ar bajenn',
'unusedimagestext' => "Ar restroù da-heul zo anezho e gwirionez met n'int ket enframmet e pajenn ebet.
Na zisoñjit ket e c'hall lec'hiennoù all kaout ul liamm eeun war-du ur restr bennak hag e c'hall neuze ar restr-se bezañ rollet amañ c'hoazh daoust dezhi bezañ implijet e lec'hiennoù all.",
'unusedcategoriestext' => "Krouet eo bet ar rummadoù-mañ met n'int ket bet implijet e pennad pe rummad ebet.",
'notargettitle' => 'netra da gavout',
'notargettext' => 'Merkit anv ur bajenn da gavout pe hini un implijer.',
'nopagetitle' => 'Pajenn dal ebet a seurt-se',
'nopagetext' => "N'eus ket eus ar bajenn dal merket ganeoc'h.",
'pager-newer-n' => "{{PLURAL:$1|1 nevesoc'h|$1 nevesoc'h}}",
'pager-older-n' => "{{PLURAL:$1|1 koshoc'h|$1 koshoc'h}}",
'suppress' => 'Dindan evezh',
'querypage-disabled' => "Diweredekaet eo bet ar bajenn dibar-mañ evit aesaat d'ar reizhiad un tammig.",

# Book sources
'booksources' => 'Oberennoù dave',
'booksources-search-legend' => 'Klask en oberennoù dave',
'booksources-isbn' => 'ISBN :',
'booksources-go' => 'Kadarnaat',
'booksources-text' => "Ur roll liammoù a gas da lec'hiennoù all ma werzher levrioù kozh ha nevez a gavot a-is; marteze e kavot eno titouroù pelloc'h war al levrioù a glaskit :",
'booksources-invalid-isbn' => "Evit doare n'eo ket reizh an ISBN merket; gwiriit ha n'oc'h ket faziet en ur eilañ adal ar vammenn orin.",

# Special:Log
'specialloguserlabel' => 'Implijer :',
'speciallogtitlelabel' => 'Bukadenn (titl pe implijer) :',
'log' => 'Marilhoù',
'all-logs-page' => 'An holl varilhoù foran',
'alllogstext' => "Diskwel a-gevret an holl varilhoù hegerz war {{SITENAME}}.
Gallout a rit strishaat ar mod diskwel en ur zibab ar marilh, an anv implijer (diwallit ouzh ar pennlizherennoù) pe ar bajenn a fell deoc'h (memes tra).",
'logempty' => 'Goullo eo istor ar bajenn-mañ.',
'log-title-wildcard' => 'Klask an titloù a grog gant an destenn-mañ',
'showhideselectedlogentries' => 'Diskouez/kuzhat penngerioù ar marilh bet diuzet',

# Special:AllPages
'allpages' => 'An holl bajennoù',
'alphaindexline' => '$1 da $2',
'nextpage' => "Pajenn war-lerc'h ($1)",
'prevpage' => 'Pajenn gent ($1)',
'allpagesfrom' => 'Diskouez ar pajennoù adal :',
'allpagesto' => 'Diskouez ar pajennoù betek :',
'allarticles' => 'An holl bajennoù',
'allinnamespace' => 'An holl bajennoù (esaouenn $1)',
'allnotinnamespace' => "An holl bajennoù (ar re n'emaint ket en esaouenn anv $1)",
'allpagesprev' => 'Kent',
'allpagesnext' => "War-lerc'h",
'allpagessubmit' => 'Kadarnaat',
'allpagesprefix' => 'Diskouez ar pajennoù a grog gant :',
'allpagesbadtitle' => "Fall e oa anv ar bajenn lakaet pe neuze ez eus ennañ ur rakger etrewiki pe etreyezhoù. Evit doare ez arouezennoù n'haller ket implijout en titloù.",
'allpages-bad-ns' => 'N\'eus ket a esaouenn anv anvet "$1" war {{SITENAME}}.',
'allpages-hide-redirects' => 'Kuzhat an adkasoù',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => "Emaoc'h o sellet ouzh ur stumm krubuilhet eus ar bajenn-mañ, a c'hall bezañ kement ha $1 kozh",
'cachedspecial-viewing-cached-ts' => "Emaoc'h o sellet ouzh ur stumm krubuilhet eus ar bajenn-mañ a c'hall bezañ dispredet un disterañ.",
'cachedspecial-refresh-now' => 'Gwelet an hini nevesañ.',

# Special:Categories
'categories' => 'Roll ar rummadoù',
'categoriespagetext' => 'Er {{PLURAL:$1|rummad|rummadoù}} da-heul ez eus pajennoù pe restroù media.
Ne ziskouezer ket amañ ar [[Special:UnusedCategories|Rummadoù dizimplij]].
Gwelet ivez ar [[Special:WantedCategories|rummadoù goulennet a vank]].',
'categoriesfrom' => 'Diskouez ar rummadoù en ur gregiñ gant :',
'special-categories-sort-count' => 'Urzhiañ dre gont',
'special-categories-sort-abc' => 'urzh al lizherenneg',

# Special:DeletedContributions
'deletedcontributions' => 'Degasadennoù diverket un implijer',
'deletedcontributions-title' => 'Degasadennoù diverket un implijer',
'sp-deletedcontributions-contribs' => 'Degasadennoù',

# Special:LinkSearch
'linksearch' => 'Klask liammoù diavaez',
'linksearch-pat' => 'Klask an droienn :',
'linksearch-ns' => 'Esaouenn anv :',
'linksearch-ok' => 'Klask',
'linksearch-text' => 'Gallout a reer implijout arouezennoù "joker" evel, da skouer, "*.wikipedia.org".
Rekis eo dezho un domani a-us da nebeutañ evel, da skouer, "*.org".<br />
Protokoloù skoret : <code>$1</code> (defaults to http:// na lakait hini ebet eus ar re-se en ho klask)',
'linksearch-line' => '$1 gant ul liamm adal $2',
'linksearch-error' => "N'hall an arouezennoù joker bezañ implijet nemet e deroù anv domani an ostiz.",

# Special:ListUsers
'listusersfrom' => 'Diskouez anv an implijerien adal :',
'listusers-submit' => 'Diskouez',
'listusers-noresult' => "N'eus bet kavet implijer ebet.",
'listusers-blocked' => '(stanket)',

# Special:ActiveUsers
'activeusers' => 'Roll an implijerien oberiant',
'activeusers-intro' => 'Setu aze ur roll eus an implijerien zo bet oberiant mui pe vui e-pad an $1 {{PLURAL:$1|deiz|deiz}} diwezhañ.',
'activeusers-count' => '$1 {{PLURAL:$1|degasadenn}} abaoe an {{PLURAL:$3|deiz|$3 deiz}} diwezhañ',
'activeusers-from' => 'Diskouez an implijerien adal :',
'activeusers-hidebots' => 'Kuzhat ar robotoù',
'activeusers-hidesysops' => 'Kuzhat ar verourien',
'activeusers-noresult' => "N'eus bet kavet implijer ebet.",

# Special:Log/newusers
'newuserlogpage' => "Marilh ar c'hontoù krouet",
'newuserlogpagetext' => "Marilh krouiñ ar c'hontoù implijer.",

# Special:ListGroupRights
'listgrouprights' => 'Gwirioù ar strolladoù implijer',
'listgrouprights-summary' => 'Da-heul ez eus ur roll eus ar strolladoù implijerien termenet war ar wiki-mañ, gant ar gwirioù moned stag outo.
Gallout a ra bezañ [[{{MediaWiki:Listgrouprights-helppage}}|titouroù ouzhpenn]] diwar-benn ar gwirioù hiniennel.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Gwirioù grataet</span>
* <span class="listgrouprights-revoked">Gwirioù lamet</span>',
'listgrouprights-group' => 'Strollad',
'listgrouprights-rights' => 'Gwirioù',
'listgrouprights-helppage' => 'Help:Gwirioù ar strolladoù',
'listgrouprights-members' => '(roll an izili)',
'listgrouprights-addgroup' => 'Gallout a reer ouzhpennañ {{PLURAL:$2|ur strollad|strolladoù}}: $1',
'listgrouprights-removegroup' => 'Gallout a reer dilemel {{PLURAL:$2|ar strollad|ar strolladoù}}: $1',
'listgrouprights-addgroup-all' => 'Gallout a reer ouzhpennañ an holl strolladoù',
'listgrouprights-removegroup-all' => 'Gallout a reer dilemel an holl strolladoù',
'listgrouprights-addgroup-self' => 'Gallout a ra ouzhpennañ {{PLURAL:$2|ar strollad|ar strolladoù}} da gont an-unan : $1',
'listgrouprights-removegroup-self' => 'Gallout a ra tennañ {{PLURAL:$2|ar strollad|strolladoù}} eus kont an-unan : $1',
'listgrouprights-addgroup-self-all' => 'Gallout a ra ouzhpennañ an holl strolladoù da gont an-unan',
'listgrouprights-removegroup-self-all' => 'Gallout a ra tennañ kuit an holl strolladoù eus kont an-unan.',

# E-mail user
'mailnologin' => "Chomlec'h ebet",
'mailnologintext' => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreet]]
ha bezañ merket ur chomlec'h postel reizh en ho [[Special:Preferences|penndibaboù]]
evit gallout kas ur postel d'un implijer all.",
'emailuser' => "Kas ur postel d'an implijer-mañ",
'emailuser-title-target' => "Kas ur postel d'an {{PLURAL:$1|an implijer-mañ|an implijerez-mañ}}",
'emailuser-title-notarget' => "Kas ur postel d'un implijer",
'emailpage' => 'Postel implijer',
'emailpagetext' => "Gallout a rit ober gant ar furmskrid a-is a-benn kas ur postel d'an {{GENDER:\$1|implijer|implijerez}}-mañ.
E maezienn \"Kaser\" ho postel e vo merket ar chomlec'h postel resisaet ganeoc'h-c'hwi en ho [[Special:Preferences|Penndibaboù]], d'ar resever da c'hallout respont deoc'h war-eeun ma kar.",
'usermailererror' => 'Fazi postel :',
'defemailsubject' => 'Postel kaset eus {{SITENAME}} gant an implijer "$1"',
'usermaildisabled' => "Diweredekaet eo ar c'has posteloù etre an implijerien.",
'usermaildisabledtext' => "Ne c'helloc'h ket kas posteloù da implijerien all er wiki-mañ",
'noemailtitle' => "Chomlec'h postel ebet",
'noemailtext' => "N'en deus ket an implijer-mañ resisaet chomlec'h postel reizh ebet.",
'nowikiemailtitle' => 'Berzet kas posteloù',
'nowikiemailtext' => 'Dibabet ez eus bet gant an implijerien-mañ chom hep resev posteloù a-berzh implijerien all.',
'emailnotarget' => "N'eus ket eus ar c'haser-mañ pe faziek eo an anv implijer lakaet.",
'emailtarget' => 'Merkañ anav implijer ar resever',
'emailusername' => 'Anv implijer :',
'emailusernamesubmit' => 'Kas',
'email-legend' => "Kas ur postel d'un implijer all eus {{SITENAME}}",
'emailfrom' => 'Kaser :',
'emailto' => 'Resever :',
'emailsubject' => 'Danvez :',
'emailmessage' => 'Postel :',
'emailsend' => 'Kas',
'emailccme' => "Kas din un eilskrid eus ma c'hemennadenn dre bostel.",
'emailccsubject' => 'Eilenn eus ho kemennadenn da $1: $2',
'emailsent' => 'Postel kaset',
'emailsenttext' => 'Kaset eo bet ho postel.',
'emailuserfooter' => 'Kaset eo bet ar postel-mañ gant $1 da $2 dre an arc\'hwel "Kas ur postel d\'an implijer" war {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'En deus laosket ur gemennadenn sistem.',
'usermessage-editor' => 'Kemennerezh ar reizhiad',

# Watchlist
'watchlist' => 'Roll evezhiañ',
'mywatchlist' => 'Ma roll evezhiañ',
'watchlistfor2' => 'Evit $1 $2',
'nowatchlist' => "N'eus pennad ebet en ho roll evezhiañ.",
'watchlistanontext' => "Ret eo deoc'h $1 evit gwelet pe kemmañ an elfennoù zo en ho roll evezhiañ.",
'watchnologin' => 'Digevreet',
'watchnologintext' => "Ret eo deoc'h bezañ [[Special:UserLogin|kevreet]]
a-benn gellout kemmañ ho roll evezhiañ.",
'addwatch' => "Ouzhpennañ d'ar roll evezhiañ",
'addedwatchtext' => 'Ouzh ho [[Special:Watchlist|rollad evezhiañ]] eo bet ouzhpennet ar bajenn "[[:$1]]".
Kemmoù da zont ar bajenn-mañ ha re ar bajenn gaozeal stag outi a vo rollet amañ hag e teuio ar bajenn <b>e tev</b> er [[Special:RecentChanges|roll kemmoù diwezhañ]] evit bezañ gwelet aesoc\'h ganeoc\'h.

Evit tennañ ar bajenn-mañ a-ziwar ho rollad evezhiañ, klikit war "Paouez da evezhiañ" er framm merdeiñ.',
'removewatch' => 'Lemel a-ziwar ar roll evezhiañ',
'removedwatchtext' => 'Lamet eo bet ar bajenn "[[:$1]]" a-ziwar ho [[Special:Watchlist|roll evezhiañ]].',
'watch' => 'Evezhiañ',
'watchthispage' => 'Evezhiañ ar bajenn-mañ',
'unwatch' => 'paouez da evezhiañ',
'unwatchthispage' => 'Paouez da evezhiañ',
'notanarticle' => 'Pennad ebet',
'notvisiblerev' => 'Stumm diverket',
'watchnochange' => "N'ez eus elfenn ebet eus ar re evezhiet ganeoc'h a zo bet kemmet e-pad ar prantad spisaet",
'watchlist-details' => "Lakaet hoc'h eus {{PLURAL:$1|$1 bajenn|$1 pajenn}} dindan evezh, anez kontañ ar pajennoù kaozeal.",
'wlheader-enotif' => "* War enaou emañ ar c'has posteloù.",
'wlheader-showupdated' => "* E '''tev''' emañ merket ar pajennoù bet kemmet abaoe ar wezh ziwezhañ hoc'h eus sellet outo",
'watchmethod-recent' => "Gwiriañ ar c'hemmoù diwezhañ er pajennoù dindan evezh",
'watchmethod-list' => "Gwiriañ ar c'hemmoù diwezhañ evit ar pajennoù evezhiet",
'watchlistcontains' => '$1 {{PLURAL:$1|pajenn|pajenn}} zo en ho rollad evezhiañ',
'iteminvalidname' => "Ur gudenn zo gant ar pennad « $1 » : n'eo ket mat e anv...",
'wlnote' => "Setu aze {{PLURAL:$1|ar c'hemm diwezhañ|ar '''$1''' kemm diwezhañ}} c'hoarvezet e-kerzh an {{PLURAL:$2|eurvezh|'''$2''' eurvezh}} ziwezhañ, evit an $3 da $4.",
'wlshowlast' => 'Diskouez an $1 eurvezh $2 devezh diwezhañ $3',
'watchlist-options' => 'Dibarzhioù ar roll evezhiañ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Heuliet...',
'unwatching' => 'Paouez da evezhiañ...',
'watcherrortext' => 'Ur gudenn zo bet en ur gemmañ arventennoù ho roll evezhiañ evit "$1".',

'enotif_mailer' => 'Posteler Kemenn {{SITENAME}}',
'enotif_reset' => 'Merkañ an holl bajennoù evel gwelet',
'enotif_newpagetext' => 'Ur bajenn nevez eo homañ.',
'enotif_impersonal_salutation' => 'implijer {{SITENAME}}',
'changed' => 'kemmet',
'created' => 'Krouet',
'enotif_subject' => '$CHANGEDORCREATED eo bet pajenn $PAGETITLE {{SITENAME}} gant $PAGEEDITOR',
'enotif_lastvisited' => 'Sellet ouzh $1 evit gwelet an holl gemmoù abaoe ho selladenn ziwezhañ.',
'enotif_lastdiff' => "Gwelet $1 evit sellet ouzh ar c'hemm-mañ.",
'enotif_anon_editor' => 'implijer dizanv $1',
'enotif_body' => '$WATCHINGUSERNAME ker,

$CHANGEDORCREATED eo bet pajenn $PAGETITLE {{SITENAME}} gant $PAGEEDITOR d\'an $PAGEEDITDATE gwelet $PAGETITLE_URL evit gwelet ar stumm red.

$NEWPAGE

Diverrañ an aozer : $PAGESUMMARY $PAGEMINOREDIT

Mont e darempred gant an aozer :
postel: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nemet ez afec\'h da welet ar bajenn end-eeun, ne vo kemenn all ebet ma vez kemmet pelloc\'h.
Gallout a rit nevesaat doare ar pennadoù evezhiet ganeoc\'h en ho rollad evezhiañ ivez.

            Ho reizhiad kemenn {{SITENAME}} muiañ karet

--
Evit kemmañ arventennoù ho kemennoù dre bostel, sellit ouzh
{{canonicalurl:{{#special:Preferences}}}}

Evit kemmañ doare ho rollad evezhiañ, sellit ouzh
{{canonicalurl:{{#special:EditWatchlist}}}}

Evit dilemel ar bajenn eus ho rollad evezhiañ, sellit ouzh
$UNWATCHURL

Evezhiadennoù ha skoazell pelloc\'h :
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Diverkañ ur bajenn',
'confirm' => 'Kadarnaat',
'excontent' => "endalc'had '$1'",
'excontentauthor' => "an danvez a oa : '$1' (ha '[[Special:Contributions/$2|$2]]' a oa bet an implijer nemetañ)",
'exbeforeblank' => "A-raok diverkañ e oa an endalc'had : '$1'",
'exblank' => "pajenn c'houllo",
'delete-confirm' => 'Diverkañ "$1"',
'delete-legend' => 'Diverkañ',
'historywarning' => "'''Diwallit :''' Emaoc'h war-nes diverkañ ur bajenn dezhi un istor gant e-tro {{PLURAL:$1|adweladenn|adweladenn}} :",
'confirmdeletetext' => "War-nes diverkañ da viken ur bajenn pe ur skeudenn eus ar bank roadennoù emaoc'h. Diverket e vo ivez an holl stummoù kozh stag outi.
Kadarnait, mar plij, eo mat an dra-se hoc'h eus c'hoant da ober, e komprenit mat an heuliadoù, hag e rit se diouzh ar [[{{MediaWiki:Policy-url}}]].",
'actioncomplete' => 'Diverkadenn kaset da benn',
'actionfailed' => "Ober c'hwitet",
'deletedtext' => '"Diverket eo bet $1".
Sellet ouzh $2 evit roll an diverkadennoù diwezhañ.',
'dellogpage' => 'Roll ar pajennoù diverket',
'dellogpagetext' => 'Setu roll ar pajennnoù diwezhañ bet diverket.',
'deletionlog' => 'roll an diverkadennoù',
'reverted' => 'Adlakaat ar stumm kent',
'deletecomment' => 'Abeg :',
'deleteotherreason' => 'Abegoù/traoù all :',
'deletereasonotherlist' => 'Abeg all',
'deletereason-dropdown' => "*Abegoù diverkañ boazetañ
** Goulenn gant saver ar pennad
** Gaou ouzh ar gwirioù perc'hennañ
** Vandalerezh",
'delete-edit-reasonlist' => 'Kemmañ a ra an abegoù diverkañ',
'delete-toobig' => 'Bras eo istor ar bajenn-mañ, ouzhpenn $1 {{PLURAL:$1|stumm|stumm}} zo. Bevennet eo bet an diverkañ pajennoù a-seurt-se kuit da zegas reuz war {{SITENAME}} dre fazi .',
'delete-warning-toobig' => "Bras eo istor ar bajenn-mañ, ouzhpenn {{PLURAL:$1|stumm|stumm}} zo.
Diverkañ anezhi a c'hallo degas reuz war mont en-dro diaz titouroù {{SITENAME}};
taolit evezh bras.",

# Rollback
'rollback' => "disteuler ar c'hemmoù",
'rollback_short' => 'Disteuler',
'rollbacklink' => 'disteuler',
'rollbacklinkcount' => 'terriñ $1 {{PLURAL:$1|kemm|kemmañ}}',
'rollbacklinkcount-morethan' => 'terriñ ouzhpenn $1 {{PLURAL:$1|kemm|kemmoù}}',
'rollbackfailed' => "C'hwitet eo bet an distaoladenn",
'cantrollback' => 'Dibosupl da zisteuler: an aozer diwezhañ eo an hini nemetañ da vezañ kemmet ar pennad-mañ',
'alreadyrolled' => "Dibosupl eo disteuler ar c'hemm diwezhañ graet d'ar bajenn [[:$1]] gant [[User:$2|$2]] ([[User talk:$2|Kaozeal]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
kemmet pe distaolet eo bet c'hoazh gant unan bennak all.

Ar c'hemm diwezhañ d'ar bajenn-mañ a oa bet graet gant [[User:$3|$3]] ([[User talk:$3|Kaozeal]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment' => "Diverradenn ar c'hemm a oa : \"''\$1''\".",
'revertpage' => "Kemmoù distaolet gant [[Special:Contributions/$2|$2]] ([[User talk:$2|Kaozeal]]); adlakaet d'ar stumm diwezhañ a-gent gant [[User:$1|$1]]",
'revertpage-nouser' => "Disteuler kemmoù (anv implijer distaolet) ha distreiñ d'ar stumm diwezhañ gant [[User:$1|$1]]",
'rollback-success' => 'Disteuler kemmoù $1; distreiñ da stumm diwezhañ $2.',

# Edit tokens
'sessionfailure-title' => "Fazi dalc'h",
'sessionfailure' => 'Evit doare ez eus ur gudenn gant ho talc\'h;
Nullet eo bet an ober-mañ a-benn en em wareziñ diouzh an tagadennoù preizhañ.
Klikit war "kent" hag adkargit ar bajenn oc\'h deuet drezi; goude klaskit en-dro.',

# Protect
'protectlogpage' => 'Log_gwareziñ',
'protectlogtext' => "Setu aze a-is roll ar c'hemmoù degaset ouzh live gwareziñ ar pajennoù.
Sellet ouzh ar [[Special:ProtectedPages|roll ar pajennoù gwarezet]] evit kaout roll ar pajennoù gwarezet bremañ.",
'protectedarticle' => '{{Gender:.|en|he}} deus gwarezet [[$1]]',
'modifiedarticleprotection' => 'Kemmañ live gwareziñ "[[$1]]"',
'unprotectedarticle' => "diwarezet eo bet ''[[$1]]''",
'movedarticleprotection' => 'en deus adkaset an arventennoù gwareziñ eus "[[$2]]" da "[[$1]]"',
'protect-title' => 'Kemmañ al live gwareziñ evit "$1"',
'protect-title-notallowed' => 'Gwelet al live gwareziñ evit "$1"',
'prot_1movedto2' => '[[$1]] adkaset war-du [[$2]]',
'protect-badnamespace-title' => 'Esaouennoù anv diwarezadus',
'protect-badnamespace-text' => "N'haller ket gwareziñ ar pajennoù en esaouenn anv-mañ.",
'protect-legend' => 'Kadarnaat ar gwareziñ',
'protectcomment' => 'Abeg :',
'protectexpiry' => 'Termen',
'protect_expiry_invalid' => 'Direizh eo termen ar prantad.',
'protect_expiry_old' => 'Echuet eo ar prantad termen.',
'protect-unchain-permissions' => "Dibrennañ muioc'h a zibarzhioù gwareziñ",
'protect-text' => "Amañ e c'hallit gwelet ha cheñch live gwareziñ ar bajenn '''$1'''.",
'protect-locked-blocked' => "E-keit ha ma viot stanket ne viot ket evit cheñch al live gwareziñ. Setu aze arventennoù a-vremañ ar bajenn '''$1''':",
'protect-locked-dblock' => "N'haller ket cheñch al liveoù gwareziñ rak prennet eo an diaz titouroù.
Setu doare a-vremañ ar bajenn '''$1''' :",
'protect-locked-access' => "N'eo ket aotreet ho kont da zegas kemmoù e live gwareziñ ur bajenn.
Setu an doare a-vremañ evit ar bajenn-mañ '''$1''':",
'protect-cascadeon' => "Gwarezet eo ar bajenn-mañ peogwir he c'haver er {{PLURAL:$1|bajenn|pajennoù}} da-heul zo gweredekaet enno ar gwareziñ dre skalierad. Gallout a rit kemmañ al live gwareziñ met ne cheñcho ket ar gwareziñ dre skalierad.",
'protect-default' => 'Aotren an holl implijerien',
'protect-fallback' => 'Ezhomm zo aotre "$1"',
'protect-level-autoconfirmed' => "Stankañ an implijerien nevez hag ar re n'int ket enrollet",
'protect-level-sysop' => 'Aotren ar verourien hepken',
'protect-summary-cascade' => 'Gwareziñ dre skalierad',
'protect-expiring' => "a zeu d'e dermen d'an $1",
'protect-expiring-local' => "a ya d'e dermen d'an $1",
'protect-expiry-indefinite' => 'da viken',
'protect-cascade' => 'Gwareziñ dre skalierad - gwareziñ a ra an holl bajennoù zo er bajenn-mañ. ARABAT IMPLIJOUT.',
'protect-cantedit' => "N'oc'h ket evit cheñch live gwareziñ ar bajenn-mañ rak n'oc'h ket aotreet da zegas kemmoù enni.",
'protect-othertime' => 'Termen all :',
'protect-othertime-op' => 'termen all',
'protect-existing-expiry' => 'Termen echuiñ merket : $3, $2',
'protect-otherreason' => 'Abeg all/ouzhpenn :',
'protect-otherreason-op' => 'Abeg all',
'protect-dropdown' => '*Abegoù gwareziñ boutin
** Vandalerezh betek re
** Stroberezh betek re
** Tabutoù toull war kemmoù zo
** Pajenn kemmet alies-tre',
'protect-edit-reasonlist' => 'Kemmañ abegoù ar gwareziñ',
'protect-expiry-options' => '1 eurvezh:1 hour,1 deiz:1 day,1 sizhun:1 week,2 sizhun:2 weeks,1 miz:1 month,3 miz:3 months,6 miz:6 months,1 bloaz:1 year,da viken:infinite',
'restriction-type' => 'Aotre',
'restriction-level' => 'Live strishaat :',
'minimum-size' => 'Ment vihanañ',
'maximum-size' => 'Ment vrasañ:',
'pagesize' => '(okted)',

# Restrictions (nouns)
'restriction-edit' => 'Kemmañ',
'restriction-move' => 'Adenvel',
'restriction-create' => 'Krouiñ',
'restriction-upload' => 'Enporzhiañ',

# Restriction levels
'restriction-level-sysop' => 'Gwarez klok',
'restriction-level-autoconfirmed' => 'Gwarez darnel',
'restriction-level-all' => 'ne vern pe live',

# Undelete
'undelete' => 'Diziverkañ ar bajenn ziverket',
'undeletepage' => 'Gwelet ha diziverkañ ar bajenn ziverket',
'undeletepagetitle' => "'''Mont a ra stummoù diverket eus [[:$1]] d'ober ar roll da-heul'''.",
'viewdeletedpage' => 'Gwelet ar pajennoù diverket',
'undeletepagetext' => "Diverket eo bet {{PLURAL:$1|ar bajenn da-heul; emañ|ar pajennoù da-heul; emaint}} e bank roadennoù an dielloù, ma c'hallont bezañ assavet.
Ingal e c'hall an diell bezañ goullonderet.",
'undelete-fieldset-title' => 'Assevel ar stummoù',
'undeleteextrahelp' => "Evit assevel istor klok ar bajenn, laoskit goullo an holl logoù ha klikit war '''''{{int:undeletebtn}}'''''.
Evit assevel stummoù zo hepken, askit ar logoù a glot gant ar stummoù da vezañ assavet, ha klikit war '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|stumm|stumm}} diellaouet',
'undeletehistory' => "Ma tiziverkit ar bajenn e vo erlec'hiet an holl gemmoù bet degaset enni er roll istor.

Ma'z eus bet krouet ur bajenn nevez dezhi an hevelep anv abaoe an diverkadenn, e teuio war wel ar c'hemmoù assavet er roll istor kent ha ne vo ket erlec'hiet ar stumm red en un doare emgefre ken.",
'undeleterevdel' => 'Ne vo ket adsavet ar stumm-se eus ar bajenn ma talvez kement ha diverkañ evit darn an doare diwezhañ anezhi. En degouezh-mañ e rankit diaskañ pe diguzhat ar stummoù diverket da ziwezhañ.',
'undeletehistorynoadmin' => "Diverket eo bet ar pennad-mañ. Displeget eo perak en diverradenn a-is, war un dro gant munudoù an implijerien o deus kemmet ar bajenn a-raok na vije diverket. N'eus nemet ar verourien a c'hall tapout krog en destenn bet diverket.",
'undelete-revision' => 'Stumm diverket eus $1, (gwiriadenn eus $4 da $5) gant $3 :',
'undeleterevision-missing' => "Stumm fall pe diank. Pe hoc'h eus heuliet ul liamm fall, pe eo bet diziverket ar stumm pe c'hoazh eo bet lamet diouzh an dielloù.",
'undelete-nodiff' => "N'eus bet kavet stumm kent ebet.",
'undeletebtn' => 'Assevel',
'undeletelink' => 'gwelet/assevel',
'undeleteviewlink' => 'gwelet',
'undeletereset' => 'Adderaouekaat',
'undeleteinvert' => 'Eilpennañ diuzadenn',
'undeletecomment' => 'Abeg :',
'undeletedrevisions' => 'Adsavet {{PLURAL:$1|1 stumm|$1 stumm}}',
'undeletedrevisions-files' => 'Adsavet ez ez eus bet {{PLURAL:$1|1 stumm|$1 stumm}} ha {{PLURAL:$2|1 restr|$2 restr}}',
'undeletedfiles' => '{{PLURAL:$1|1 restr|$1 restr}} adsavet',
'cannotundelete' => "Dibosupl eo diziverkañ; moarvat eo bet diziverket gant unan bennak all araozoc'h.",
'undeletedpage' => "'''Diziverket eo bet $1'''

Sellit ouzh [[Special:Log/delete|marilh an diverkadennoù]] evit teuler ur sell ouzh an diverkadennoù diwezhañ.",
'undelete-header' => 'Gwelet [[Special:Log/delete|al log diverkañ]] evit ar pajennoù diverket nevez zo.',
'undelete-search-title' => 'Klask pajennoù bet diverket',
'undelete-search-box' => 'Klask pajennoù diverket',
'undelete-search-prefix' => 'Diskouez ar pajennoù a grog gant :',
'undelete-search-submit' => 'Klask',
'undelete-no-results' => "N'eus bet kavet pajenn ebet a glotje e dielloù an diverkadennoù.",
'undelete-filename-mismatch' => "Dibosupl diziverkañ stumm ar restr d'ar mare $1: ne glot ket anv ar restr",
'undelete-bad-store-key' => "Dibosupl diziverkañ stumm ar restr d'ar mare $1: ezvezant e oa ar restr a-raok an diverkadenn.",
'undelete-cleanup-error' => 'Fazi en ur ziverkañ ar restr diellaouet dizimplij "$1".',
'undelete-missing-filearchive' => "Dibosupl adsevel ID diell ar restr $1 rak n'emañ ket er bank ditouroù. Diziverket eo bet c'hoazh, marteze a-walc'h.",
'undelete-error' => 'Pajenn ar fazioù diziverkañ',
'undelete-error-short' => 'Fazi e-ser diziverkañ ar restr : $1',
'undelete-error-long' => 'Fazioù zo bet kavet e-ser diziverkañ ar restr :

$1',
'undelete-show-file-confirm' => 'Ha sur oc\'h e fell deoc\'h sellet ouzh ur stumm diverket eus ar restr "<nowiki>$1</nowiki>" a sav d\'an $2 da $3?',
'undelete-show-file-submit' => 'Ya',

# Namespace form on various pages
'namespace' => 'Esaouenn anv :',
'invert' => 'Eilpennañ an dibab',
'tooltip-invert' => "Askañ ar voest-mañ da guzhat ar c'hemmoù er pajennoù stag ouzh an esaouenn anv diuzet (hag an esaouenn anv stag, m'emañ asket)",
'namespace_association' => 'Esaouennoù anv all da ouzhpennañ',
'tooltip-namespace_association' => 'Askañ ar voest-mañ da lakaat ivez e-barzh ar gaozeadenn pe danvez an esaouenn anv liammet ouzh an esaouenn anv diuzet',
'blanknamespace' => '(Pennañ)',

# Contributions
'contributions' => 'Degasadennoù an implijer',
'contributions-title' => 'Degasadennoù an implijer evit $1',
'mycontris' => 'Ma degasadennoù',
'contribsub2' => 'Evit $1 ($2)',
'nocontribs' => "N'eus bet kavet kemm ebet o klotañ gant an dezverkoù-se.",
'uctop' => ' (diwezhañ)',
'month' => 'Abaoe miz (hag a-raok) :',
'year' => 'Abaoe bloaz (hag a-raok) :',

'sp-contributions-newbies' => "Diskouez hepken degasadennoù ar c'hontoù nevez",
'sp-contributions-newbies-sub' => 'Evit an implijerien nevez',
'sp-contributions-newbies-title' => "Degasadennoù implijer evit ar c'hontoù nevez",
'sp-contributions-blocklog' => 'Roll ar stankadennoù',
'sp-contributions-deleted' => 'Degasadennoù diverket',
'sp-contributions-uploads' => 'Enporzhiadennoù',
'sp-contributions-logs' => 'marilhoù',
'sp-contributions-talk' => 'kaozeal',
'sp-contributions-userrights' => 'Merañ ar gwirioù',
'sp-contributions-blocked-notice' => "Stanket eo an implijer-mañ evit poent. Dindan emañ merket moned diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'sp-contributions-blocked-notice-anon' => "Stanket eo ar chomlec'h IP-mañ evit ar mare.
Dindan emañ merket enmont diwezhañ marilh ar stankadennoù, d'ho kelaouiñ :",
'sp-contributions-search' => 'Klask degasadennoù',
'sp-contributions-username' => "Anv implijer pe chomlec'h IP :",
'sp-contributions-toponly' => 'Na ziskouez nemet an adweladennoù diwezhañ',
'sp-contributions-submit' => 'Klask',

# What links here
'whatlinkshere' => 'Pajennoù liammet',
'whatlinkshere-title' => 'Pajennoù liammet ouzh "$1"',
'whatlinkshere-page' => 'Pajenn :',
'linkshere' => "Ar pajennoù a-is zo enno ul liamm a gas war-du '''[[:$1]]''':",
'nolinkshere' => "N'eus pajenn ebet enni ul liamm war-du '''[[:$1]]'''.",
'nolinkshere-ns' => "Pajenn ebet n'eo liammet ouzh '''[[:$1]]''' en esaouenn anv dibabet.",
'isredirect' => 'pajenn adkas',
'istemplate' => 'enframmet',
'isimage' => 'Liamm war-zu ar restr',
'whatlinkshere-prev' => '{{PLURAL:$1|kent|kent $1}}',
'whatlinkshere-next' => "{{PLURAL:$1|war-lerc'h|war-lerc'h $1}}",
'whatlinkshere-links' => '← liammoù',
'whatlinkshere-hideredirs' => '$1 adkas',
'whatlinkshere-hidetrans' => '$1 treuzkluzadur',
'whatlinkshere-hidelinks' => '$1 liamm',
'whatlinkshere-hideimages' => '$1 ar restroù liammet',
'whatlinkshere-filters' => 'Siloù',

# Block/unblock
'autoblockid' => 'Emstankañ #$1',
'block' => 'Stankañ an implijer',
'unblock' => 'Distankañ an implijer',
'blockip' => "Stankañ ur chomlec'h IP",
'blockip-title' => 'Stankañ an implijer',
'blockip-legend' => 'Stankañ an implijer',
'blockiptext' => "Grit gant ar furmskrid a-is evit stankañ ar moned skrivañ ouzh ur chomlec'h IP pe un implijer bennak.
Seurt diarbennoù n'hallont bezañ kemeret nemet evit mirout ouzh ar vandalerezh hag a-du gant an [[{{MediaWiki:Policy-url}}|erbedadennoù ha reolennoù da heuliañ]].
Roit a-is an abeg resis (o verkañ, da skouer, roll ar pajennoù bet graet gaou outo).",
'ipadressorusername' => "Chomlec'h IP pe anv implijer",
'ipbexpiry' => 'Pad ar stankadenn',
'ipbreason' => 'Abeg :',
'ipbreasonotherlist' => 'Abeg all',
'ipbreason-dropdown' => "*Abegoù stankañ boutinañ
** Degas titouroù faos
** Tennañ danvez eus ar pajennoù
** Degas liammoù Strobus war-du lec'hiennoù diavaez
** Degas danvez diboell/dizoare er pajennoù
** Emzalc'h hegazus/handeus betek re
** Mont re bell gant implij meur a gont
** Anv implijer n'eo ket aotreet",
'ipb-hardblock' => "Mirout a ra ouzh an implijerien kevreet da zegas kemmoù adalek ar c'homlec'h IP-mañ",
'ipbcreateaccount' => 'Mirout ouzh an implijer da grouiñ kontoù',
'ipbemailban' => 'Mirout ouzh an implijer da gas posteloù',
'ipbenableautoblock' => "Stankañ war-eeun ar chomlec'h IP diwezhañ implijet gant an den-mañ hag an holl chomlec'hioù a c'hallfe klask kemmañ traoù drezo drezo diwezhatoc'h",
'ipbsubmit' => 'Stankañ an implijer-mañ',
'ipbother' => 'Prantad all',
'ipboptions' => '2 eurvezh:2 hours,1 devezh:1 day,3 devezh:3 days,1 sizhunvezh:1 week,2 sizhunvezh:2 weeks,1 mizvezh:1 month,3 mizvezh:3 months,6 mizvezh:6 months,1 bloaz:1 year,da viken:infinite',
'ipbotheroption' => 'prantad all',
'ipbotherreason' => 'Abeg all/ouzhpenn',
'ipbhidename' => "Kuzhat anv an implijer er rolloù hag er c'hemmoù",
'ipbwatchuser' => 'Evezhiañ pajennoù implijer ha kaozeal an implijer-mañ',
'ipb-disableusertalk' => 'Mirout ouzh an implijer-mañ da implijout e bajenn gaozeal dezhañ e-unan e-keit hag emañ stanket',
'ipb-change-block' => 'Adstankañ an implijer-mañ gant an hevelep arventennoù',
'ipb-confirm' => 'Kadarnaat ar stankadenn',
'badipaddress' => "Kamm eo ar chomlec'h IP.",
'blockipsuccesssub' => 'Stankadenn deuet da benn vat',
'blockipsuccesstext' => "Stanket eo bet [[Special:Contributions/$1|$1]].<br />
Sellit ouzh [[Special:BlockList|roll ar chomlec'hioù IP ha kontoù stanket]] evit gwiriañ ar stankadennoù.",
'ipb-blockingself' => "Emaoc'h war-nes stankañ ac'hanoc'h hoc'h-unan ! Ha sur oc'h eo se a fell deoc'h ober ?",
'ipb-confirmhideuser' => 'Emaoc\'h war-nes stankañ un implijer ha gweredekaet hoc\'h eus "kuzhat an implijer". Diverkañ a ra an dra-se anv an implijer en holl rolloù ha monedoù ar marilh.',
'ipb-edit-dropdown' => 'Kemmañ an abegoù stankañ',
'ipb-unblock-addr' => 'Distankañ $1',
'ipb-unblock' => "Distankañ un implijer pe ur chomlec'h IP",
'ipb-blocklist' => 'Teuler ur sell ouzh roll an dud stanket evit poent',
'ipb-blocklist-contribs' => 'Degasadennoù evit $1',
'unblockip' => "Distankañ ur chomlec'h IP",
'unblockiptext' => "Grit gant ar furmskrid a-is evit adsevel ar moned skrivañ ouzh ur chomlec'h IP bet stanket a-gent.",
'ipusubmit' => 'Paouez gant ar stankadenn-mañ',
'unblocked' => 'Distanket eo bet [[User:$1|$1]]',
'unblocked-range' => 'Distanket eo bet $1',
'unblocked-id' => 'Distanket eo bet $1',
'blocklist' => 'Implijerien stanket',
'ipblocklist' => 'Implijerien stanket',
'ipblocklist-legend' => 'Kavout un implijer stanket',
'blocklist-userblocks' => 'Kuzhat ar stankadennoù kont',
'blocklist-tempblocks' => 'Kuzhat ar stankadennoù berrbad',
'blocklist-addressblocks' => "Kuzhat stankadennoù ar chomlec'hioù IP en o-unan",
'blocklist-rangeblocks' => "Kuzhat ar bloc'hadoù renkennek",
'blocklist-timestamp' => 'Deiziad hag eur',
'blocklist-target' => 'pal',
'blocklist-expiry' => "A ya d'e dermen",
'blocklist-by' => 'Merour ar stankadenn',
'blocklist-params' => 'Arventennoù stankañ',
'blocklist-reason' => 'Abeg',
'ipblocklist-submit' => 'Klask',
'ipblocklist-localblock' => "Stankadenn lec'hel",
'ipblocklist-otherblocks' => '{{PLURAL:$1|Stankadenn|Stankadennoù}} all',
'infiniteblock' => 'da viken',
'expiringblock' => "a zeu d'e dermen d'an $1 da $2",
'anononlyblock' => 'implijerien dizanv hepken',
'noautoblockblock' => 'emstankañ diweredekaet',
'createaccountblock' => "Harzet eo ar c'hrouiñ kontoù",
'emailblock' => 'Postel stanket',
'blocklist-nousertalk' => "n'hall ket kemmañ e bajenn gaozeal dezhañ e-unan",
'ipblocklist-empty' => 'Goullo eo roll ar stankadennoù.',
'ipblocklist-no-results' => "An anv implijer pe ar chomlec'h IP goulennet n'eo ket stanket anezhañ.",
'blocklink' => 'stankañ',
'unblocklink' => 'distankañ',
'change-blocklink' => 'Kemmañ ar stankadenn',
'contribslink' => 'degasadennoù',
'emaillink' => 'Kas ur postel',
'autoblocker' => 'Emstanket rak implijet eo bet ho chomlec\'h IP gant "[[User:$1|$1]]" nevez zo.
Setu aze an abeg(où) m\'eo bet stanket $1 : "\'\'$2\'\'"',
'blocklogpage' => 'Roll ar stankadennoù',
'blocklog-showlog' => "Stanket eo bet an implijer-mañ c'hoazh. A-is emañ marilh ar stankadennoù, d'ho titouriñ :",
'blocklog-showsuppresslog' => "Stanket ha kuzhet eo bet an implijer-mañ c'hoazh. A-is emañ marilh ar diverkadennoù, d'ho titouriñ :",
'blocklogentry' => 'en/he deus stanket [[$1]] betek an $2 $3',
'reblock-logentry' => "en deus kemmet an arventennoù stankañ evit [[$1]] gant un termen d'an $2 $3",
'blocklogtext' => "Setu roud stankadennoù ha distankadennoù an implijerien. N'eo ket bet rollet ar chomlec'hioù IP bet stanket ent emgefre. Sellet ouzh [[Special:BlockList|roll an implijerien stanket]] evit gwelet piv zo stanket e gwirionez.",
'unblocklogentry' => 'distanket "$1"',
'block-log-flags-anononly' => 'implijerien dizanv hepken',
'block-log-flags-nocreate' => 'berzet eo krouiñ kontoù',
'block-log-flags-noautoblock' => 'Emstankañ diweredekaet',
'block-log-flags-noemail' => 'postel stanket',
'block-log-flags-nousertalk' => "n'hall ket kemmañ e bajenn gaozeal dezhañ e-unan",
'block-log-flags-angry-autoblock' => 'Emstankañ gwellaet gweredekaet',
'block-log-flags-hiddenname' => 'anv implijer kuzhet',
'range_block_disabled' => "Diweredekaet eo bet ar stankañ stuc'hadoù IP.",
'ipb_expiry_invalid' => 'amzer termen direizh.',
'ipb_expiry_temp' => "Peurbadus e rank bezañ bloc'hadoù an implijerien guzh.",
'ipb_hide_invalid' => 'Dibosupl diverkañ ar gont-mañ; evit doare ez eus bet graet re a gemmoù enni.',
'ipb_already_blocked' => 'Stanket eo "$1" dija',
'ipb-needreblock' => "Stanket eo $1 c'hoazh. Kemmañ an arventennoù a fell deoc'h ?",
'ipb-otherblocks-header' => '{{PLURAL:$1|Stankadenn|Stankadnenoù}} all',
'unblock-hideuser' => "N'hallit ket distankañ an implijer-mañ rak kuzhet eo bet e anv kont.",
'ipb_cant_unblock' => "Fazi: N'eo ket stanket an ID $1. Moarvat eo bet distanket c'hoazh.",
'ipb_blocked_as_range' => "Fazi : N'eo ket bet stanket ar chomlec'h IP $1 war-eeun, setu n'hall ket bezañ distanket. Stanket eo bet dre al live $2 avat, hag a c'hall bezañ distanket.",
'ip_range_invalid' => 'Stankañ IP direizh.',
'ip_range_toolarge' => "N'eo ket aotreet stankañ pajennoù brasoc'h evit /$1.",
'blockme' => "Stankit ac'hanon",
'proxyblocker' => 'Stanker proksi',
'proxyblocker-disabled' => "Diweredekaet eo an arc'hwel-mañ.",
'proxyblockreason' => "Stanket eo bet hoc'h IP rak ur proksi digor eo. Trugarez da gelaouiñ ho pourvezer moned ouzh ar Genrouedad pe ho skoazell deknikel eus ar gudenn surentez-mañ.",
'proxyblocksuccess' => 'Echu.',
'sorbsreason' => "Rollet eo ho chomlec'h IP evel ur proksi digor en DNSBL implijet gant {{SITENAME}}.",
'sorbs_create_account_reason' => "Rollet eo ho chomlec'h IP evel ur proksi digor war an DNSBL implijet gant {{SITENAME}}. N'hallit ket krouiñ ur gont",
'cant-block-while-blocked' => "N'hallit ket stankañ implijerien all ma'z oc'h stanket c'hwi hoc'h-unan.",
'cant-see-hidden-user' => "Stanket ha kuzhet eo bet dija an implijer emaoc'h o klask stankañ. Dre ma n'emañ ket ganeoc'h ar gwir hideuser, n'hallit ket gwelet pe kemmañ stankadenn an implijer.",
'ipbblocked' => "Ne c'hellit ket stankañ pe distankañ implijerien all, dre ma 'z oc'h stanket",
'ipbnounblockself' => "N'oc'h ket aotreet d'en em zistankañ ho unan",

# Developer tools
'lockdb' => 'Prennañ ar bank',
'unlockdb' => 'Dibrennañ ar bank',
'lockdbtext' => "Ma vez prennet ar bank roadennoù n'hallo ket mui implijer ebet kemmañ pajennoù, enrollañ e benndibaboù, kemmañ e rollad evezhiañ na seveniñ oberiadenn ebet a c'houlenn degas kemm pe gemm er bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober hag e vo dibrennet ar bank ganeoc'h kerkent ha ma vo bet kaset da benn hoc'h oberiadenn drezalc'h.",
'unlockdbtext' => "Dibrennañ ar bank a lakay adarre an holl implijerien e-tailh da gemmañ pajennoù, hizivaat o fenndibaboù hag o rollad evezhiañ ha seveniñ an holl oberiadennoù a c'houlenn ma vefe kemmet ar bank roadennoù.
Kadarnait, mar plij, eo se hoc'h eus c'hoant da ober.",
'lockconfirm' => 'Ya, kadarnaat a ran e fell din prennañ ar bank roadennoù.',
'unlockconfirm' => 'Ya, kadarnaat a ran e fell din dibrennañ ar bank roadennoù.',
'lockbtn' => 'Prennañ ar bank',
'unlockbtn' => 'Dibrennañ ar bank',
'locknoconfirm' => "N'eo ket bet asket al log kadarnaat ganeoc'h.",
'lockdbsuccesssub' => 'Bank prennet.',
'unlockdbsuccesssub' => 'Bank dibrennet.',
'lockdbsuccesstext' => "Prennet eo bank roadennnoù {{SITENAME}}.

<br />Na zisoñjit ket e zibrennañ pa vo bet kaset da benn vat hoc'h oberiadenn drezalc'h.",
'unlockdbsuccesstext' => 'Dibrennet eo bank roadennoù {{SITENAME}}.',
'lockfilenotwritable' => "N'haller ket skrivañ war restr prennañ ar bank roadennoù. A-benn prennañ-dibrennañ ar bank e rankit bezañ aotreet da skrivañ war ar servijer Kenrouedad.",
'databasenotlocked' => "N'eo ket prennet an diaz roadennoù.",
'lockedbyandtime' => "(gant $1 d'an $2 da $3)",

# Move page
'move-page' => "Dilec'hiañ $1",
'move-page-legend' => 'Adenvel ur pennad',
'movepagetext' => "Grit gant ar furmskrid a-is evit adenvel ur pennad hag adkas an holl stummoù kent anezhañ war-du an anv nevez.
Dont a raio an titl kentañ da vezañ ur bajenn adkas war-du an titl nevez.
Ne vo ket kemmet liammoù an titl kozh ha ne vo ket dilec'hiet ar bajenn gaozeal, ma'z eus anezhi.

'''DIWALLIT!'''
Gallout a ra kement-se bezañ ur c'hemm bras ha dic'hortoz evit ur pennad a vez sellet outi alies;
bezit sur e komprenit mat an heuliadoù a-raok kenderc'hel ganti.",
'movepagetext-noredirectfixer' => "Grit gant ar furmskrid a-is evit adenvel ur bajenn hag adkas an istor anezhi war-zu an anv nevez.
Dont a raio an titl kozh da vezañ ur bajenn adkas war-zu an titl nevez.
Gwiriit mat an [[Special:DoubleRedirects|adkasoù doubl]] hag an [[Special:BrokenRedirects|adkasoù torr]].
Ennoc'h emañ fiziet gwiriañ e kendalc'h al liammoù da gas war-zu ar bajenn a rankont kas daveti.

Notit mat ne vo '''ket''' dilec'hiet ar bajenn ma'z eus dija unan gant an titl nevez nemet e vefe goullo istor ar c'hemmoù degaset enni hag e vefe pe goullo ar bajenn pe e vefe un adkas anezhi. Gant se e c'haller adenvel ur bajenn war-zu he lec'h orin mard eo faziek an dilec'hiañ ha dibosupl eo frikañ ur bajenn zo anezhi c'hoazh. 

'''Diwallit !'''
Ur c'hemm bras ha dic'hortoz e c'hall bezañ evit ur bajenn a vez sellet outi alies ; bezit sur hoc'h eus komprenet mat an heuliadoù a-raok kenderc'hel ganti.",
'movepagetalktext' => "Gant se e vo adanvet ent emgefre ar bajenn gaozeal stag, ma'z eus anezhi '''nemet ma:'''
*ec'h adanvit ur bajenn war-du ul lec'h all,
*ez eus ur bajenn gaozeal c'hoazh gant an anv nevez, pe
*diweredekaet hoc'h eus ar bouton a-is.

En degouezh-se e rankot adenvel pe gendeuziñ ar bajenn c'hwi hoc'h-unan ma karit.",
'movearticle' => "Dilec'hiañ ar pennad",
'moveuserpage-warning' => "'''Diwallit : ''' War-nes dilec'hiañ ur bajenn implijer emaoc'h. Notit mat n'eus nemet ar bajenn a vo dilec'hiet ha ne vo ''ket'' adanvet an implijer.",
'movenologin' => 'Digevreet',
'movenologintext' => 'A-benn gellout adenvel ur pennad e rankit bezañ un implijer enrollet ha bezañ [[Special:UserLogin|kevreet]].',
'movenotallowed' => "N'oc'h ket aotreet da zilec'hiañ pajennoù.",
'movenotallowedfile' => "N'oc'h ket aoteret da adenvel restroù.",
'cant-move-user-page' => "Noc'h ket aotreet da adenvel pajennoù pennañ an implijerien (er-maez eus o ispajennoù).",
'cant-move-to-user-page' => "Noc'h ket aotreet da adenvel ur bajenn gant anv hini un implijer all (nemet un ispajenn e vefe).",
'newtitle' => 'anv nevez',
'move-watch' => 'Evezhiañ ar bajenn-mañ',
'movepagebtn' => 'Adenvel ar pennad',
'pagemovedsub' => "Dilec'hiadenn kaset da benn vat",
'movepage-moved' => '\'\'\'Adkaset eo bet "$1" war-du "$2"\'\'\'',
'movepage-moved-redirect' => 'Krouet ez eus bet un adkas.',
'movepage-moved-noredirect' => 'Nullet eo bet krouidigezeh un adkas adal an anv kozh.',
'articleexists' => "Ur pennad gantañ an anv-se zo dija pe n'eo ket reizh an titl hoc'h eus dibabet.
Dibabit unan all mar plij.",
'cantmove-titleprotected' => "N'hallit ket dilec'hiañ ur bajenn d'al lec'h-mañ rak gwarezet eo bet an titl nevez p'eo bet krouet.",
'talkexists' => "Dilec'hiet mat eo bet ar bajenn hec'h-unan met chomet eo ar bajenn gaozeal rak unan all a oa dija gant an anv nevez-se. Kendeuzit anezho c'hwi hoc'h-unan mar plij.",
'movedto' => 'adanvet e',
'movetalk' => 'Adenvel ivez ar bajenn "gaozeal", mar bez ret.',
'move-subpages' => 'Adenvel an ispajennoù (betek $1 pajenn)',
'move-talk-subpages' => 'Adenvel ispajennoù ar bajenn gaozeal (betek $1 pajenn).',
'movepage-page-exists' => "Bez' ez eus eus ar bajenn $1 c'hoazh ha n'hall ket bezañ friket ent emgefre.",
'movepage-page-moved' => 'Anv nevez ar bajenn $1 zo $2.',
'movepage-page-unmoved' => "N'eus ket bet gallet adenvel ar bajenn $1 e $2.",
'movepage-max-pages' => 'Tizhet eo bet ar vevenn uhelañ a $1 {{PLURAL:$1|bajenn|pajenn}} da adenvel ha ne vo ket adanvet hini all ebet ken ent emgefre.',
'movelogpage' => 'Roll an adkasoù',
'movelogpagetext' => 'Setu roll ar pajennoù bet savet un adkas evito.',
'movesubpage' => '{{PLURAL:$1|Ispajenn|Ispajenn}}',
'movesubpagetext' => "Bez' ez eus $1 {{PLURAL:$1|ispajenn|ispajenn}} diskouezet a-is d'ar bajenn-mañ.",
'movenosubpage' => "Ispajenn ebet d'ar bajenn-mañ.",
'movereason' => 'Abeg :',
'revertmove' => 'nullañ',
'delete_and_move' => 'Diverkañ ha sevel adkas',
'delete_and_move_text' => "==Ezhomm diverkañ==

Savet eo ar pennad tal \"[[:\$1]]\" c'hoazh.
Diverkañ anezhañ a fell deoc'h ober evit reiñ lec'h d'an adkas ?",
'delete_and_move_confirm' => 'Ya, diverkañ ar bajenn',
'delete_and_move_reason' => 'Diverket evit ober lec\'h d\'an adkas "[[$1]]"',
'selfmove' => "Heñvel eo titl ar poent loc'hañ ha hini ar pal; n'haller ket adkas ur bajenn war-du he lec'h orin.",
'immobile-source-namespace' => 'n\'haller kas ar pajennoù war-du an esaouenn anv "$1"',
'immobile-target-namespace' => 'N\'hallit ket adenvel pajennoù war-du an esaouenn anv "$1"',
'immobile-target-namespace-iw' => "N'eo ket ur pal mat al liammoù Interwiki evit adenvel pajennoù.",
'immobile-source-page' => "N'haller ket adenvel ar bajenn-mañ.",
'immobile-target-page' => "N'haller ket kas ar bajenn-mañ war-du an titl-se.",
'imagenocrossnamespace' => "N'haller ket dilec'hiañ ur skeudenn war-du un esaouenn anv n'eo ket hini ur skeudenn.",
'nonfile-cannot-move-to-file' => "N'haller ket dilec'hiañ un dra ha n'eo ket ur restr war-du an esaouenn anv restr",
'imagetypemismatch' => 'Ne glot ket astenn nevez ar restr gant ar furmad-mañ.',
'imageinvalidfilename' => 'Fall eo anv ar restr tal',
'fix-double-redirects' => 'Hizivaat an holl adkasoù a gas war-du an titl orin',
'move-leave-redirect' => 'Lezel un adkas war-du an titl nevez',
'protectedpagemovewarning' => "'''DIWALLIT :''' Prennet eo bet ar bajenn-mañ, setu n'eus nemet an implijerien ganto gwrioù merañ a c'hall adenvel anezhi. Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'semiprotectedpagemovewarning' => "'''NOTENN :''' Prennet eo bet ar bajenn-mañ, setu n'hall bezañ adanvet nemet gant an implijerien enskrivet. Kasadenn ziwezhañ ar marilh a zo diskouezet amañ a-is evel dave :",
'move-over-sharedrepo' => "== Bez' ez eus eus ar restr-se dija ==
Bez' ez eus eus [[:$1]] war ur sanailh kenrannet dija. Ma cheñchit anv ar restr ne viot ket mui evit tizhout ar restr zo er sanailh kenrannet.",
'file-exists-sharedrepo' => "Implijet c'hoazh eo an anv dibabet gant ur restr zo war ur sanailh kenrannet.
Grit gant un anv all.",

# Export
'export' => 'Ezporzhiañ pajennoù',
'exporttext' => "Gallout a rit ezporzhiañ en XML an destenn ha pennad istor ur bajenn pe ur strollad pajennoù;
a-benn neuze e c'hall an disoc'h bezañ enporzhiet en ur wiki all a ya en-dro gant ar meziant MediaWiki dre [[Special:Import|ar bajenn enporzhiañ]].

A-benn ezporzhiañ pajennoù, merkit an titloù anezho er voest skrid a-is, un titl dre linenn. Diuzit mar fell deoc'h kaout, pe get, ar stumm a-vremañ gant an holl stummoù kozh, gant linennoù itor ar bajenn, pe just ar bajenn red gant titouroù diwar-benn ar c'hemm diwezhañ.

Mard eo se e c'hallit ivez implijout ul liamm a seurt gant [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] evit ar bajenn [[{{MediaWiki:Mainpage}}]].",
'exportall' => 'Ezporzhiañ an holl bajennoù',
'exportcuronly' => 'Ezporzhiañ hepken ar stumm red hep an istor anezhañ',
'exportnohistory' => "----
'''Notenn :''' Dilezet eo bet an ezporzhiañ istor klok ar pajennoù evit poent peogwir e veze gorrekaet ar reizhiad diwar se.",
'exportlistauthors' => 'Lakaat e-barzh ur roll klok eus ar berzhidi evit pep pajenn',
'export-submit' => 'Ezporzhiañ',
'export-addcattext' => 'Ouzhpennañ pajennoù ar rummad :',
'export-addcat' => 'Ouzhpennañ',
'export-addnstext' => 'Ouzhpennañ pajennoù eus an esaouenn anv :',
'export-addns' => 'Ouzhpennañ',
'export-download' => 'Aotren enrollañ evel ur restr',
'export-templates' => 'Lakaat ar patromoù e-barzh ivez',
'export-pagelinks' => 'Lakaat ar pajennoù liammet e-barzh betek un donder a :',

# Namespace 8 related
'allmessages' => 'Roll kemennoù ar reizhiad',
'allmessagesname' => 'Anv',
'allmessagesdefault' => 'Kemennadenn dre ziouer',
'allmessagescurrent' => 'Kemennadenn zo bremañ',
'allmessagestext' => "Setu roll ar c'hemennadennoù reizhiad a c'haller kaout en esaouennoù anv MediaWiki.
Kit da welet [//www.mediawiki.org/wiki/Localisation Lec'heladur MediaWiki] ha [//translatewiki.net translatewiki.net] mar fell deoc'h kemer perzh e lec'heladur boutin MediaWiki.",
'allmessagesnotsupportedDB' => "N'haller ket kaout {{ns:special}}:AllMessages rak diweredekaet eo bet wgUseDatabaseMessages.",
'allmessages-filter-legend' => 'Sil',
'allmessages-filter' => "Silañ dre stad ar c'hemmoù",
'allmessages-filter-unmodified' => 'Digemm',
'allmessages-filter-all' => 'An holl',
'allmessages-filter-modified' => 'Kemmet',
'allmessages-prefix' => 'Silañ dre rakger',
'allmessages-language' => 'Yezh :',
'allmessages-filter-submit' => 'Mont',

# Thumbnails
'thumbnail-more' => 'Brasaat',
'filemissing' => 'Restr ezvezant',
'thumbnail_error' => 'Fazi e-ser krouiñ an alberz : $1',
'djvu_page_error' => 'Pajenn DjVu er-maez ar bevennoù',
'djvu_no_xml' => 'Dibosupl da dapout an XML evit ar restr DjVu',
'thumbnail-temp-create' => 'Dibosupl krouiñ ur restr vunut padennek',
'thumbnail-dest-create' => 'Dibosupl enrollañ ar munud.',
'thumbnail_invalid_params' => 'Arventennoù direizh evit ar munud',
'thumbnail_dest_directory' => "Dibosupl krouiñ ar c'havlec'h pal",
'thumbnail_image-type' => "N'eo ket skoret ar seurt skeudennoù",
'thumbnail_gd-library' => "Kefluniadur diglok al levraoueg GD : dibosupl kavout an arc'hwel $1",
'thumbnail_image-missing' => "Evit doare n'eus ket eus ar restr : $1",

# Special:Import
'import' => 'Enporzhiañ pajennoù',
'importinterwiki' => 'enporzhiadenn etrewiki',
'import-interwiki-text' => 'Diuzit ur wiki hag ur bajenn da enporzhiañ.
Miret e vo deiziadoù ar stummmoù hag anvioù an aozerien.
Miret eo an holl enporzhiadennoù etrewiki e-barzh [[Special:Log/import|log an enporzhiadennoù]].',
'import-interwiki-source' => 'wiki ha pajennoù tarzh :',
'import-interwiki-history' => 'Eilañ holl stummoù istor ar bajenn-mañ',
'import-interwiki-templates' => 'Lakaat e-barzh an holl batromoù',
'import-interwiki-submit' => 'Enporzhiañ',
'import-interwiki-namespace' => 'Esaouenn anv ar pal :',
'import-interwiki-rootpage' => 'Pennpajenn kas (war zibab)',
'import-upload-filename' => 'Anv ar restr :',
'import-comment' => 'Notenn :',
'importtext' => "Ezporzhiit ar restr adal ar wiki orin en ur ober gant an arc'hwel [[Special:Export|ezporzhiañ]].
Enrollit ar bajenn war hoc'h urzhiataer ha kargit anezhi amañ.",
'importstart' => "Oc'h enporzhiañ pajennoù...",
'import-revision-count' => '$1 {{PLURAL:$1|stumm|stumm}}',
'importnopages' => 'Pajenn ebet da enporzhiañ.',
'imported-log-entries' => '$1 moned{{PLURAL:$1||}} eus ar marilh enporzhiet{{PLURAL:$1||}}.',
'importfailed' => "C'hwitet eo an enporzhiadenn: $1",
'importunknownsource' => 'Dianav eo seurt ar vammenn enporzhiañ',
'importcantopen' => "N'eus ket bet gallet digeriñ ar restr enporzhiet",
'importbadinterwiki' => 'Liamm etrewiki fall',
'importnotext' => 'Goullo pe hep tamm testenn ebet',
'importsuccess' => 'Deuet eo an enporzhiadenn da benn vat!',
'importhistoryconflict' => "Divankadennoù zo er pennad istor ha tabut zo gant se (marteze eo bet enporzhiet ar bajenn araozoc'h)",
'importnosources' => "N'eus bet spisaet tamm mammenn etrewiki ebet ha diweredekaet eo enporzhiañ an Istor war-eeun.",
'importnofile' => "N'eus bet enporzhiet restr ebet.",
'importuploaderrorsize' => "C'hwitet eo bet enporzhiañ ar restr. Brasoc'h eo ar restr eget ar vent aotreet.",
'importuploaderrorpartial' => "C'hwitet eo vet enporzhiañ ar restr. Enporzhiet evit darn eo bet hepken.",
'importuploaderrortemp' => "C'hwitet eo bet enporzhiañ ar restr. Mankout a ra ur restr badennek.",
'import-parse-failure' => "Troc'h e dielfennadenn an enporzh XML",
'import-noarticle' => 'Pajenn ebet da enporzhiañ !',
'import-nonewrevisions' => "Enporzhiet eo bet an holl degasadennoù c'hoazh.",
'xml-error-string' => '$1 war al linenn $2, bann $3 (okted $4) : $5',
'import-upload' => 'Enporzhiañ roadennoù XML',
'import-token-mismatch' => "Kollet eo bet roadennoù an dalc'h. Klaskit en-dro.",
'import-invalid-interwiki' => 'Dibosupl enporzhiañ adal ar wiki spisaet.',
'import-error-edit' => 'N\'eo ket bet enporzhiet ar bajenn "$1" peogwir n\'oc\'h ket aotreet da zegas kemmoù enni.',
'import-error-create' => 'N\'eo ket bet enporzhiet ar bajenn "$1" peogwir n\'oc\'h ket aotreet da grouiñ anezhi.',
'import-error-interwiki' => 'Ne vez ket enporzhiet ar bajenn "$1" rak miret eo an anv evit liammoù diavaez (etrewiki).',
'import-error-special' => 'Ne vez ket enporzhiet ar bajenn "$1" rak stag eo ouzh un esaouenn anv dibar na aotre ket pajennoù.',
'import-error-invalid' => 'Ne vez ket enporzhiet ar bajenn "$1" rak direizh eo hec\'h anv.',
'import-options-wrong' => '{{PLURAL:$2|Dibab fall|Dibaboù fall}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => "Pourchas a ra ar bennbajenn un titl n'eo ket reizh.",
'import-rootpage-nosubpage' => 'Esaouenn anvioù "$1" eus ar bennpajenn ne aotre ket an ispajennoù.',

# Import log
'importlogpage' => 'Log an enporzhiadennoù',
'importlogpagetext' => "Enporzhiadennoù melestradurel eus pajennoù adal wikioù all gant istor ar c'hemmadennoù degaset enno.",
'import-logentry-upload' => 'en/he deus enporzhiet (pellgarget) [[$1]]',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|stumm|stumm}}',
'import-logentry-interwiki' => 'treuzwikiet $1',
'import-logentry-interwiki-detail' => "$1 {{PLURAL:$1|c'hemm|kemm}} abaoe $2",

# JavaScriptTest
'javascripttest' => 'Amprouadenn JavaScript',
'javascripttest-disabled' => "N'eo ket bet gweredekaet an arc'hwel-mañ war ar wiki.",
'javascripttest-title' => 'Emeur o seveniñ $1 amprouadenn',
'javascripttest-pagetext-noframework' => 'Miret eo ar bajenn-mañ evit amprouiñ JavaScript.',
'javascripttest-pagetext-unknownframework' => 'Framm amprouiñ "$1" dianav.',
'javascripttest-pagetext-frameworks' => 'Diuzit unan eus ar frammoù amprouiñ da-heul : $1',
'javascripttest-pagetext-skins' => 'Diuzit ar gwiskadur da vezañ implijet evit an amprouadennoù :',
'javascripttest-qunit-intro' => 'Sellet ouzh [$1 an teulioù amprouiñ] e mediawiki.org.',
'javascripttest-qunit-heading' => 'Heuliad amprouadennoù QUnit eus JavaScript war MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Ho pajenn implijer',
'tooltip-pt-anonuserpage' => "Ar bajenn implijer evit ar c'homlec'h IP implijet ganeoc'h",
'tooltip-pt-mytalk' => 'Ho pajenn gaozeal',
'tooltip-pt-anontalk' => "Kaozeadennoù diwar-benn ar c'hemmoù graet adal ar chomlec'h-mañ",
'tooltip-pt-preferences' => 'Ma fenndibaboù',
'tooltip-pt-watchlist' => "Roll ar pajennoù evezhiet ganeoc'h.",
'tooltip-pt-mycontris' => 'Roll ho tegasadennoù',
'tooltip-pt-login' => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h kevreañ",
'tooltip-pt-anonlogin' => "Daoust ma n'eo ket ret, ec'h aliomp deoc'h kevreañ.",
'tooltip-pt-logout' => 'Digevreañ',
'tooltip-ca-talk' => 'Kaozeadennoù diwar-benn ar pennad',
'tooltip-ca-edit' => 'Gallout a rit kemmañ ar bajenn-mañ. Implijit ar stokell Rakwelet a-raok enrollañ, mar plij.',
'tooltip-ca-addsection' => 'Kregiñ gant ur rann nevez.',
'tooltip-ca-viewsource' => 'Gwarezet eo ar bajenn-mañ. Gallout a rit gwelet an danvez anezhañ memes tra.',
'tooltip-ca-history' => 'Stummoù kozh ar bajenn-mañ gant an aozerien anezhi.',
'tooltip-ca-protect' => 'Gwareziñ ar bajenn-mañ',
'tooltip-ca-unprotect' => 'Cheñch live gwareziñ ar bajenn-mañ',
'tooltip-ca-delete' => 'Diverkañ ar bajenn-mañ',
'tooltip-ca-undelete' => 'Adsevel ar bajenn-mañ',
'tooltip-ca-move' => 'Adenvel ar bajenn-mañ',
'tooltip-ca-watch' => "Ouzhpennañ ar bajenn-mañ d'ho roll evezhiañ",
'tooltip-ca-unwatch' => 'Paouez da evezhiañ ar bajenn-mañ',
'tooltip-search' => 'Klaskit er wiki-mañ',
'tooltip-search-go' => "Mont d'ar bajenn dezhi an anv-mañ rik, ma'z eus anezhi",
'tooltip-search-fulltext' => 'Klask an destenn-mañ er pajennoù',
'tooltip-p-logo' => 'Pajenn bennañ',
'tooltip-n-mainpage' => 'Diskouez ar Bajenn bennañ',
'tooltip-n-mainpage-description' => 'Kit da welet an degemer',
'tooltip-n-portal' => "Diwar-benn ar raktres, ar pezh a c'hallit ober, pelec'h kavout an traoù",
'tooltip-n-currentevents' => 'Tapout keleier diwar-benn an darvoudoù diwezhañ',
'tooltip-n-recentchanges' => "Roll ar c'hemmoù diwezhañ c'hoarvezet war ar wiki.",
'tooltip-n-randompage' => 'Diskwel ur bajenn dre zegouezh',
'tooltip-n-help' => 'Skoazell.',
'tooltip-t-whatlinkshere' => 'Roll ar pajennoù liammet ouzh ar bajenn-mañ',
'tooltip-t-recentchangeslinked' => "Roll ar c'hemmoù diwezhañ war ar pajennoù liammet ouzh ar bajenn-mañ",
'tooltip-feed-rss' => 'Magañ ar red RSS evit ar bajenn-mañ',
'tooltip-feed-atom' => 'Magañ ar red Atom evit ar bajenn-mañ',
'tooltip-t-contributions' => 'Gwelet roll degasadennoù an implijer-mañ',
'tooltip-t-emailuser' => "Kas ur postel d'an implijer-mañ",
'tooltip-t-upload' => 'Enporzhiañ ur skeudenn pe ur restr media war ar servijer',
'tooltip-t-specialpages' => 'Roll an holl bajennoù dibar',
'tooltip-t-print' => 'Stumm moulladus ar bajenn-mañ',
'tooltip-t-permalink' => 'Liamm padus war-du ar stumm-mañ eus ar bajenn',
'tooltip-ca-nstab-main' => 'Gwelet ar pennad',
'tooltip-ca-nstab-user' => 'Gwelet ar bajenn implijer',
'tooltip-ca-nstab-media' => 'Gwelet pajenn ar media',
'tooltip-ca-nstab-special' => "Ur bajenn dibar eo homañ, n'oc'h ket evit kemmañ anezhi.",
'tooltip-ca-nstab-project' => 'Gwelet pajenn ar raktres',
'tooltip-ca-nstab-image' => 'Gwelet pajenn deskrivañ ar bajenn-mañ',
'tooltip-ca-nstab-mediawiki' => 'Gwelet kemenn ar reizhiad',
'tooltip-ca-nstab-template' => 'Gwelet ar patrom',
'tooltip-ca-nstab-help' => 'Gwelet ar bajenn soazell',
'tooltip-ca-nstab-category' => 'Gwelet pajenn ar rummad',
'tooltip-minoredit' => "Merkañ ar c'hemm-mañ evel dister",
'tooltip-save' => 'Enrollañ ho kemmoù',
'tooltip-preview' => "Rakwelet ar c'hemmoù; trugarez d'ober gantañ a-raok enrollañ!",
'tooltip-diff' => "Diskouez ar c'hemmoù degaset ganeoc'h en destenn.",
'tooltip-compareselectedversions' => "Sellet ouzh an diforc'hioù zo etre daou stumm diuzet ar bajenn-mañ.",
'tooltip-watch' => "Ouzhpennañ ar bajenn-mañ d'ho roll evezhiañ",
'tooltip-watchlistedit-normal-submit' => 'Tennañ an titloù',
'tooltip-watchlistedit-raw-submit' => 'Hizivaat ar roll evezhiañ',
'tooltip-recreate' => 'Adkrouiñ ar bajenn ha pa vije bet diverket a-raok',
'tooltip-upload' => 'Kregiñ da enporzhiañ',
'tooltip-rollback' => "\"Disteuler\" a zistaol en ur c'hlik ar c'hemm(où) bet degaset d'ar bajenn-mañ gant an implijer diwezhañ.",
'tooltip-undo' => '"Dizober" a zistaol ar c\'hemm-mañ hag a zigor ar prenestr skridaozañ er mod rakwelet.
Talvezout a ra da ouzhpennañ un displegadenn er c\'hombod diverrañ.',
'tooltip-preferences-save' => 'Enrollañ ar penndibaboù',
'tooltip-summary' => 'Skrivit un diveradenn verr',

# Stylesheets
'common.css' => '/** Talvezout a raio ar CSS lakaet amañ evit an holl wiskadurioù */',
'standard.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Standard */',
'nostalgia.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Melkoni */',
'cologneblue.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Glaz Kologn */',
'monobook.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Monobook */',
'myskin.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur MySkin */',
'chick.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Plogig */',
'simple.css' => '/* Talvezout a raio ar CSS lakaet amañ implijerien ar gwiskadur Eeun */',
'modern.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Modern */',
'vector.css' => '/* Talvezout a raio ar CSS lakaet amañ evit implijerien ar gwiskadur Vektor */',
'print.css' => '/* Talvezout a raio ar CSS lakaet amañ evit ar moullañ */',
'handheld.css' => '/* Talvezout a raio ar CSS lakaet amañ evit an ardivinkoù hezoug diouzh ar gwiskadur kefluniet e $wgHandheldStyle */',
'noscript.css' => '/* Talvezout a raio ar CSS lakaet amañ evit an implijerien o deus diweredekaet JavaScript */',
'group-autoconfirmed.css' => '/* Talvezout a raio ar CSS lakaet amañ evit an impjerien bet kadarnaet ent emgefre hepken */',
'group-bot.css' => '/* Talvezout a raio ar CSS lakaet amañ evit ar robotoù hepken */',
'group-sysop.css' => '/* Talvezout a raio ar CSS lakaet amañ evit ar verourien hepken */',
'group-bureaucrat.css' => '/* Talvezout a raio ar CSS lakaet amañ evit ar vureveien hepken */',

# Scripts
'common.js' => '/* Kement JavaScript amañ a vo karget evit an holl implijerien war kement pajenn lennet ganto. */',
'standard.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Standard */',
'nostalgia.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Melkoni */',
'cologneblue.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Glaz Kologn */',
'monobook.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur MonoBook */',
'myskin.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur MySkin */',
'chick.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Plogig */',
'simple.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Eeun */',
'modern.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Modern */',
'vector.js' => '/* Kement JavaScript amañ a vo karget evit an implijerien a ra gant ar gwiskadur Vektor */',

# Metadata
'notacceptable' => "N'eo ket ar servijer wiki-mañ evit pourchas stlennoù en ur furmad lennus evit ho arval.",

# Attribution
'anonymous' => '{{PLURAL:$1|Implijer|Implijerien}} dizanv war {{SITENAME}}',
'siteuser' => 'Implijer(ez) $1 eus {{SITENAME}}',
'anonuser' => 'implijer dizanv $1 eus {{SITENAME}}',
'lastmodifiedatby' => "Kemmet eo bet ar bajenn-mañ da ziwezhañ da $2, d'an $1 gant $3",
'othercontribs' => 'Diazezet war labour $1.',
'others' => 're all',
'siteusers' => '$1 {{PLURAL:$2|implijer|implijer}} eus {{SITENAME}}',
'anonusers' => '{{PLURAL:$2|implijer dizanv|implijerien dizanv}} $1 eus {{SITENAME}}',
'creditspage' => 'Pajennoù kredoù',
'nocredits' => "N'eus tamm titour kred hegerz ebet evit ar bajenn-mañ.",

# Spam protection
'spamprotectiontitle' => "Sil gwareziñ a-enep d'ar Strob",
'spamprotectiontext' => "Stanket eo bet ar bajenn a felle deoc'h enrollañ gant ar siler stroboù.
Sur a-walc'h abalamour d'ul liamm enni a gas d'ul lec'hienn ziavaez berzet.",
'spamprotectionmatch' => 'Dihunet eo bet an detektour Strob gant an destenn-mañ : $1',
'spambot_username' => 'Naetaat ar strob gant MediaWiki',
'spam_reverting' => "Distreiñ d'ar stumm diwezhañ hep liamm davet $1",
'spam_blanking' => 'Diverkañ an holl stummoù enno liammoù davet $1',
'spam_deleting' => 'An holl stummoù enno liammoù war-zu $1, o tiverkañ',

# Info page
'pageinfo-title' => 'Titouroù evit "$1"',
'pageinfo-not-current' => "Hon digarezit, ne c'haller ket reiñ an titouroù-mañ evit an adweloù kozh.",
'pageinfo-header-basic' => 'Titouroù diazez',
'pageinfo-header-edits' => 'Kemmoù',
'pageinfo-header-restrictions' => 'Gwarez ar bajenn',
'pageinfo-header-properties' => 'Perzhioù ar bajenn',
'pageinfo-display-title' => 'Titl diskwelet',
'pageinfo-default-sort' => "Alc'hwez rummañ dre ziouer",
'pageinfo-length' => 'Ment ar bajenn (en oktedoù)',
'pageinfo-article-id' => 'Niverenn ar bajenn',
'pageinfo-robot-policy' => 'Statud al lusker klask',
'pageinfo-robot-index' => "A c'haller menegeriñ",
'pageinfo-robot-noindex' => "Ne c'haller ket menegeriñ",
'pageinfo-views' => 'Niver a weladennoù',
'pageinfo-watchers' => 'Niver a dud o heuliañ',
'pageinfo-redirects-name' => 'Adkas war-zu ar bajenn-mañ',
'pageinfo-subpages-name' => 'Ispajennoù eus ar bajenn-mañ',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|kasadur|kasadurioù}}; $3 {{PLURAL:$3|nann kasaduri|nann kasadurioù}})',
'pageinfo-firstuser' => 'Krouer ar bajenn',
'pageinfo-firsttime' => 'Deiziad krouiñ ar bajenn',
'pageinfo-lastuser' => 'Kontroller diwezhañ',
'pageinfo-lasttime' => "Deiziad ar c'hemm diwezhañ",
'pageinfo-edits' => 'Niver a gemmoù',
'pageinfo-authors' => 'Niver a aozerien disheñvel',
'pageinfo-recent-edits' => 'Niver a gemmoù nevez (er $1 diwezhañ)',
'pageinfo-recent-authors' => "Niver a aozerien nevez a-ziforc'h",
'pageinfo-magic-words' => '{{PLURAL:$1|Ger hud |Gerioù hud}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Rumm kuzh|Rummoù kuzh}} ($1)',
'pageinfo-templates' => "{{PLURAL:$1|Patrom endalc'het|Patromoù endalc'het}} ($1)",

# Skin names
'skinname-standard' => 'Standard',
'skinname-nostalgia' => 'Melkoni',
'skinname-cologneblue' => 'Glaz Kologn',
'skinname-monobook' => 'MonoBook',
'skinname-myskin' => 'MySkin',
'skinname-chick' => 'Plogig',
'skinname-simple' => 'Eeun',
'skinname-modern' => 'Modern',
'skinname-vector' => 'Vektor',

# Patrolling
'markaspatrolleddiff' => 'Merkañ evel gwiriet',
'markaspatrolledtext' => 'Merkañ ar pennad-mañ evel gwiriet',
'markedaspatrolled' => 'Merkañ evel gwiriet',
'markedaspatrolledtext' => 'Merket eo bet ar stumm diuzet eus [[:$1]] evel gwiriet.',
'rcpatroldisabled' => "Diweredekaet ar gwiriañ ar C'hemmoù diwezhañ",
'rcpatroldisabledtext' => "Diweredekaet eo bet an arc'hwel evezhiañ ar c'hemmoù diwezhañ.",
'markedaspatrollederror' => "N'hall ket bezañ merket evel gwiriet",
'markedaspatrollederrortext' => "Ret eo deoc'h spisaat ur stumm a-benn e verkañ evel gwiriet.",
'markedaspatrollederror-noautopatrol' => "N'oc'h ket aotreet da verkañ evel gwiriet ar c'hemmoù degaset ganeoc'h.",

# Patrol log
'patrol-log-page' => 'Log gwiriañ',
'patrol-log-header' => 'Setu ur marilh eus ar stummoù patrouilhet.',
'log-show-hide-patrol' => '$1 istor ar stummoù gwiriet',

# Image deletion
'deletedrevision' => 'Diverket stumm kozh $1.',
'filedeleteerror-short' => 'Fazi e-ser diverkañ ar restr : $1',
'filedeleteerror-long' => 'Fazioù zo bet kavet e-ser diverkañ ar restr :

$1',
'filedelete-missing' => 'N\'haller ket diverkañ ar restr "$1" peogwir n\'eus ket anezhi.',
'filedelete-old-unregistered' => 'Stumm spisaet ar restr "$1" n\'emañ ket er bank titouroù.',
'filedelete-current-unregistered' => 'Ar restr spisaet "$1" n\'emañ ket er bank titouroù.',
'filedelete-archive-read-only' => 'N\'hall ket ar servijer web skrivañ war ar c\'havlec\'h dielloù "$1".',

# Browsing diffs
'previousdiff' => '← Kemm kent',
'nextdiff' => "Kemm nevesoc'h →",

# Media information
'mediawarning' => "'''Diwallit :''' Kodoù siek a c'hall bezañ er seurt restr-mañ.
Ma vez erounezet ganeoc'h e c'hallje tagañ ho reizhiad.",
'imagemaxsize' => "Bevenn ment vrasañ ar skeudennoù :<br />''(evit ar pajennoù deskrivañ)''",
'thumbsize' => 'Ment an alberz :',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|pajenn|pajenn}}',
'file-info' => 'ment ar restr : $1, seurt MIME : $2',
'file-info-size' => '$1 × $2 piksel, ment ar restr : $3, seurt MIME : $4',
'file-info-size-pages' => '$1 × $2 piksel, ment ar restr : $3, seurt MIME : $4, $5 {{PLURAL:$5|pajenn|pajenn}}',
'file-nohires' => "N'haller ket gwellaat ar pizhder.",
'svg-long-desc' => 'restr SVG file, pizhder $1 × $2 piksel, ment ar restr : $3',
'svg-long-desc-animated' => 'Restr SVG bev, ment $1 × $2 piksel, ment ar restr: $3',
'show-big-image' => 'Pizhder leun',
'show-big-image-preview' => 'Ment ar rakweled-mañ : $1.',
'show-big-image-other' => '{{PLURAL:$2|pizhder all|pizhderioù all}} : $1.',
'show-big-image-size' => '$1 × $2 piksel',
'file-info-gif-looped' => "e kelc'h",
'file-info-gif-frames' => '$1 {{PLURAL:$1|skeudenn|skeudenn}}',
'file-info-png-looped' => "e kelc'h",
'file-info-png-repeat' => 'lennet $1 {{PLURAL:$1|wezh|gwezh}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|skeudenn|skeudenn}}',
'file-no-thumb-animation' => 'Evezhiadenn: En abeg  da vevennoù teknikel ne vo ket bevaet skeudennoùigoù ar restr-mañ',
'file-no-thumb-animation-gif' => 'Evezhiadenn: En abeg  da vevennoù teknikel ne vo ket bevaet ar skeudennoù GIF uhel o diarunusted evel homañ.',

# Special:NewFiles
'newimages' => 'Roll ar restroù nevez',
'imagelisttext' => "Setu aze ur roll '''$1''' {{PLURAL:$1|file|files}} rummet $2.",
'newimages-summary' => 'Diskouez a ra ar bajenn dibar-mañ roll ar restroù diwezhañ bet enporzhiet.',
'newimages-legend' => 'Sil',
'newimages-label' => 'Anv ar restr (pe darn anezhi) :',
'showhidebots' => '($1 bot)',
'noimages' => 'Netra da welet.',
'ilsubmit' => 'Klask',
'bydate' => 'dre an deiziad anezho',
'sp-newimages-showfrom' => 'Diskouez ar restroù nevez adal $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 eilenn|$1 eilenn}}',
'minutes' => '{{PLURAL:$1|$1 vunutenn|$1 munutenn}}',
'hours' => '{{PLURAL:$1|$1 eurvezh|$1 eurvezh}}',
'days' => '{{PLURAL:$1|$1 deiz|$1 deiz}}',
'ago' => '$1 zo',

# Bad image list
'bad_image_list' => "Setu doare ar furmad :

Ne seller nemet ouzh roll an elfennoù (linennoù a grog gant *). Ret eo d'al liamm kentañ war ul linenn bezañ ul liamm war-du ur skeudenn fall.
Kement liamm all war an hevelep linenn a seller outañ evel un nemedenn, da skouer pennadoù ma c'hall ar skeudenn dont war wel.",

# Metadata
'metadata' => 'Metaroadennoù',
'metadata-help' => "Titouroù ouzhpenn zo er restr-mañ; bet lakaet moarvat gant ar c'hamera niverel pe ar skanner implijet evit he niverelaat. Mard eo bet cheñchet ar skeudenn e-keñver he stad orin marteze ne vo ket kenkoulz munudoù zo e-keñver ar skeudenn kemmet.",
'metadata-expand' => 'Dispakañ ar munudoù',
'metadata-collapse' => 'Krennañ ar munudoù',
'metadata-fields' => "Ensoc'het e vo maeziennoù metaroadennoù ar skeudenn rollet er gemennadenn-mañ war pajenn deskrivañ ar skeudenn pa vo punet taolenn ar metaroadennoù. 
Kuzhet e vo ar re all dre ziouer.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude",

# EXIF tags
'exif-imagewidth' => 'Ledander',
'exif-imagelength' => 'Hed',
'exif-bitspersample' => 'Niv. a vitoù dre barzhioù',
'exif-compression' => 'Seurt gwaskadur',
'exif-photometricinterpretation' => 'Kenaozadur piksel',
'exif-orientation' => 'Tuadur',
'exif-samplesperpixel' => 'Niver a standilhonoù',
'exif-planarconfiguration' => 'Kempenn ar roadennoù',
'exif-ycbcrsubsampling' => 'Feur standilhoniñ Y da C',
'exif-ycbcrpositioning' => "Lec'hiadur Y ha C",
'exif-xresolution' => 'Pizhder led ar skeudenn',
'exif-yresolution' => 'Pizhder hed ar skeudenn',
'exif-stripoffsets' => "Lec'hiadur roadennoù ar skeudenn",
'exif-rowsperstrip' => 'Niver a linennoù dre vandenn',
'exif-stripbytecounts' => 'Ment e oktedoù dre vandenn',
'exif-jpeginterchangeformat' => "Lec'hiadur ar SOI JPEG",
'exif-jpeginterchangeformatlength' => 'Ment ar roadennoù JPEG en eizhbitoù',
'exif-whitepoint' => 'Kromategezh ar poent gwenn',
'exif-primarychromaticities' => 'Kromategezh al livioù orin',
'exif-ycbcrcoefficients' => 'Kenefederioù moull treuzfurmiñ an egorenn liv',
'exif-referenceblackwhite' => 'Talvoudenn dave gwenn ha du',
'exif-datetime' => 'Deiziad hag eur kemm restr',
'exif-imagedescription' => 'Titl ar skeudenn',
'exif-make' => 'Oberier ar benveg',
'exif-model' => 'Doare ar benveg',
'exif-software' => 'Meziant bet implijet',
'exif-artist' => 'Aozer',
'exif-copyright' => "Perc'henn ar gwirioù aozer (copyright)",
'exif-exifversion' => 'Stumm exif',
'exif-flashpixversion' => 'Skoret ganti stumm Flashpix',
'exif-colorspace' => "Lec'h al livioù",
'exif-componentsconfiguration' => 'Talvoudegezh pep parzh',
'exif-compressedbitsperpixel' => 'Doare gwaskañ ar skeudenn',
'exif-pixelydimension' => 'Ledander ar skeudenn',
'exif-pixelxdimension' => 'Sav ar skeudenn',
'exif-usercomment' => 'Evezhiadennoù',
'exif-relatedsoundfile' => 'Restr son stag',
'exif-datetimeoriginal' => 'Deiziad hag eur ar sevel roadoù',
'exif-datetimedigitized' => 'Deiziad hag eur an niverelaat',
'exif-subsectime' => 'Deiziad kemmañ diwezhañ',
'exif-subsectimeoriginal' => 'Deiziad an dennadenn orin',
'exif-subsectimedigitized' => 'Deiziad niverelaat',
'exif-exposuretime' => "Amzer louc'hañ",
'exif-exposuretime-format' => '$1 eilenn ($2)',
'exif-fnumber' => 'Hed etre sti',
'exif-exposureprogram' => "Programm louc'hañ",
'exif-spectralsensitivity' => 'Kizidigezh spektrel',
'exif-isospeedratings' => 'Kizidigezh ISO',
'exif-shutterspeedvalue' => "Tizh ar c'hlozer APEX",
'exif-aperturevalue' => 'Digorder APEX',
'exif-brightnessvalue' => 'Sklêrder APEX',
'exif-exposurebiasvalue' => "Reizhadenn louc'hañ",
'exif-maxaperturevalue' => 'Maezienn digeriñ vrasañ',
'exif-subjectdistance' => 'Hed ar sujed',
'exif-meteringmode' => 'Doare muzuliañ',
'exif-lightsource' => "Mammenn c'houloù",
'exif-flash' => "Luc'h",
'exif-focallength' => 'Hirder ar fokalenn',
'exif-subjectarea' => 'Gorread ar sujed',
'exif-flashenergy' => "Nerzh al luc'h",
'exif-focalplanexresolution' => 'Muzuliadur a-led ur fokalenn blaen',
'exif-focalplaneyresolution' => 'Muzuliadur a-serzh ur fokalenn blaen',
'exif-focalplaneresolutionunit' => 'Unanenn spisder evit ur fokalenn blaen',
'exif-subjectlocation' => "Lec'hiadur an danvez",
'exif-exposureindex' => "Meneger louc'hañ",
'exif-sensingmethod' => 'Hentenn detektiñ',
'exif-filesource' => 'Tarzh ar restr',
'exif-scenetype' => 'Seurt arvest',
'exif-customrendered' => 'Plediñ gant ar skeudennoù personelaet',
'exif-exposuremode' => "Mod louc'hañ",
'exif-whitebalance' => 'Mentel ar gwennoù',
'exif-digitalzoomratio' => 'Feur brasaat niverel (zoum)',
'exif-focallengthin35mmfilm' => 'Hirder ar fokalenn e filmoù 35 mm',
'exif-scenecapturetype' => 'Doare pakañ an arvest',
'exif-gaincontrol' => 'Reizhañ ar sklêrder',
'exif-contrast' => 'Dargemm',
'exif-saturation' => 'Saturadur',
'exif-sharpness' => 'Spisder',
'exif-devicesettingdescription' => 'Deskrivadur doare ar wikefre',
'exif-subjectdistancerange' => 'Hed ar sujed',
'exif-imageuniqueid' => 'Anavezer nemetañ ar skeudenn',
'exif-gpsversionid' => 'Stumm an neudennad GPS',
'exif-gpslatituderef' => 'Ledred Norzh pe su',
'exif-gpslatitude' => 'Ledred',
'exif-gpslongituderef' => 'Hedred kornôg pe reter',
'exif-gpslongitude' => 'Hedred',
'exif-gpsaltituderef' => 'Daveenn uhelder',
'exif-gpsaltitude' => 'Uhelder',
'exif-gpstimestamp' => 'Eur GPS (eurier atomek)',
'exif-gpssatellites' => 'Loarelloù implijet evit ar muzuliañ',
'exif-gpsstatus' => 'Statud ar resever',
'exif-gpsmeasuremode' => 'Doare muzuliañ',
'exif-gpsdop' => 'Resisder ar muzul',
'exif-gpsspeedref' => 'Unanenn dizh',
'exif-gpsspeed' => 'Tizh ar resever GPS',
'exif-gpstrackref' => "Daveenn evit durc'hadur ar fiñv",
'exif-gpstrack' => "Durc'hadur ar fiñv",
'exif-gpsimgdirectionref' => "Daveenn evit durc'hadur ar skeudenn",
'exif-gpsimgdirection' => "Durc'hadur ar skeudenn",
'exif-gpsmapdatum' => 'Reizhiad geodetek implijet',
'exif-gpsdestlatituderef' => 'Daveenn evit ledred ar pal',
'exif-gpsdestlatitude' => 'Ledred ar pal',
'exif-gpsdestlongituderef' => 'Daveenn evit hedred ar pal',
'exif-gpsdestlongitude' => 'Hedred ar pal',
'exif-gpsdestbearingref' => 'Daveenn evit notenniñ ar pal',
'exif-gpsdestbearing' => 'Notenniñ ar pal',
'exif-gpsdestdistanceref' => 'Daveenn evit an hed betek ar pal',
'exif-gpsdestdistance' => 'Hed betek ar pal',
'exif-gpsprocessingmethod' => 'Anv hentenn blediñ ar GPS',
'exif-gpsareainformation' => 'Anv an takad GPS',
'exif-gpsdatestamp' => 'Deiziad GPS',
'exif-gpsdifferential' => "Reizhadenn diforc'hadus GPS",
'exif-jpegfilecomment' => 'Evezhiadenn digant ar restr JPEG',
'exif-keywords' => "Gerioù alc'hwez",
'exif-worldregioncreated' => 'Ranndir ma voe tennet ar skeudenn',
'exif-countrycreated' => 'Bro ma voe tennet ar skeudenn',
'exif-countrycodecreated' => 'Kod ar vro ma voe tennet ar skeudenn',
'exif-provinceorstatecreated' => 'Proviñs pe Stad ma voe tennet ar skeudenn',
'exif-citycreated' => 'Kêr ma voe tennet ar skeudenn',
'exif-sublocationcreated' => "Islec'hiadur ar gêr ma voe tennet ar skeudenn",
'exif-worldregiondest' => 'Ranndir diskouezet',
'exif-countrydest' => 'Bro diskouezet',
'exif-countrycodedest' => 'Kod evit ar vro diskouezet',
'exif-provinceorstatedest' => 'Proviñs pe Stad diskouezet',
'exif-citydest' => 'Kêr diskouezet',
'exif-sublocationdest' => "Islec'hiadur ar gêr diskouezet",
'exif-objectname' => 'Titl berr',
'exif-specialinstructions' => 'Kemennadurioù dibar',
'exif-headline' => 'Titl',
'exif-credit' => 'Kred/Pourvezer',
'exif-source' => 'Mammenn',
'exif-editstatus' => 'Statud skridaozañ ar skeudenn',
'exif-urgency' => 'Malluster',
'exif-fixtureidentifier' => 'Anv elefenn a zistro ingal',
'exif-locationdest' => "Lec'h diskouezet",
'exif-locationdestcode' => "Kod al lec'h diskouezet",
'exif-objectcycle' => "Mare eus an deiz m'eo bet soñjet ar media-mañ evitañ",
'exif-contact' => 'Titouroù diwar-benn an darempred',
'exif-writer' => 'Skrivagner',
'exif-languagecode' => 'Yezh',
'exif-iimversion' => 'Stumm IIM',
'exif-iimcategory' => 'Rummad',
'exif-iimsupplementalcategory' => 'Rummadoù ouzhpenn',
'exif-datetimeexpires' => 'Arabat implijout goude',
'exif-datetimereleased' => "Embannet d'an",
'exif-originaltransmissionref' => "Treuzkas orin ar c'hod lec'hiañ",
'exif-identifier' => 'Anaouder',
'exif-lens' => 'Ferenn bet implijet',
'exif-serialnumber' => 'Niverenn rummad ar benveg',
'exif-cameraownername' => "Perc'henn ar benveg",
'exif-label' => 'Tikedenn',
'exif-datetimemetadata' => 'Deiziad ma voe kemmet ar metaroadennoù da ziwezhañ',
'exif-nickname' => 'Anv anfurmel ar skeudenn',
'exif-rating' => 'Priziañ (war 5)',
'exif-rightscertificate' => 'Testeni merañ ar gwirioù',
'exif-copyrighted' => 'Statud a-fet gwirioù aozer',
'exif-copyrightowner' => "Perc'henn ar gwirioù aozer (copyright)",
'exif-usageterms' => 'Termenoù implijout',
'exif-webstatement' => 'Disklêriadur gwrioù-aozer enlinenn',
'exif-originaldocumentid' => 'ID nemetañ an teul orin',
'exif-licenseurl' => "Chomlec'h Internet evit an aotre gwirioù-aozer",
'exif-morepermissionsurl' => 'Titouroù all war an aotre-implijout',
'exif-attributionurl' => 'Ma adimplijt an oberenn-mañ, lakait ul liamm war-zu',
'exif-preferredattributionname' => 'Ma adimplijt an oberenn-mañ, roit kred da',
'exif-pngfilecomment' => 'Evezhiadenn digant ar restr PNG',
'exif-disclaimer' => 'Kemenn hollek',
'exif-contentwarning' => 'Kemenn-diwall diwar-benn an danvez',
'exif-giffilecomment' => 'Evezhiadenn digant ar restr GIF',
'exif-intellectualgenre' => 'Seurt elfenn',
'exif-subjectnewscode' => 'Kod ar sujed',
'exif-scenecode' => 'Kod leurenniñ IPTC',
'exif-event' => 'Darvoud diskouezet',
'exif-organisationinimage' => 'Anv an aozadurioù diskouezet war ar skeudenn',
'exif-personinimage' => 'Den diskouezet',
'exif-originalimageheight' => 'Sav ar skeudenn a-raok na vije bet krennet',
'exif-originalimagewidth' => 'Ledander ar skeudenn a-raok na vije bet krennet',

# EXIF attributes
'exif-compression-1' => 'Hep gwaskañ',
'exif-compression-2' => 'CCITT Strollad 3 1 Hirder kodañ Huffman kemmet a vent 1',
'exif-compression-3' => 'CCITT Strollad 3 kodañ ar pelleiler',
'exif-compression-4' => 'CCITT Strollad 4 kodañ ar pelleiler',

'exif-copyrighted-true' => 'Pep gwir miret strizh',
'exif-copyrighted-false' => 'Domani foran',

'exif-unknowndate' => 'Deiziad dianav',

'exif-orientation-1' => 'Boutin',
'exif-orientation-2' => 'Eilpennet a-hed',
'exif-orientation-3' => 'Troet eus 180°',
'exif-orientation-4' => 'Eilpennet a-serzh',
'exif-orientation-5' => 'Troet eus 90° a-gleiz hag eilpennet a-serzh',
'exif-orientation-6' => "Troet eus 90° a-enep d'an eur",
'exif-orientation-7' => 'Troet eus 90° a-zehou hag eilpennet a-serzh',
'exif-orientation-8' => 'Troet eus 90° a-gleiz',

'exif-planarconfiguration-1' => 'Roadennoù kenstok',
'exif-planarconfiguration-2' => 'Roadennoù distag',

'exif-colorspace-65535' => "N'eo ket diouzh ur stalon",

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

'exif-meteringmode-0' => 'Dianav',
'exif-meteringmode-1' => 'Keidenn',
'exif-meteringmode-2' => 'Muzul kreiz keitat',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'Liesspot',
'exif-meteringmode-5' => 'Patrom',
'exif-meteringmode-6' => 'Darnek',
'exif-meteringmode-255' => 'All',

'exif-lightsource-0' => 'Dianav',
'exif-lightsource-1' => 'Gouloù deiz',
'exif-lightsource-2' => "Treluc'hus",
'exif-lightsource-3' => 'Tungsten (gouloù kann)',
'exif-lightsource-4' => "Luc'h",
'exif-lightsource-9' => 'Amzer digoumoul',
'exif-lightsource-10' => 'Amzer goumoulek',
'exif-lightsource-11' => 'Skeud',
'exif-lightsource-12' => "Gouloù deiz treluc'hus (D 5700 – 7100K)",
'exif-lightsource-13' => "Gouloù deiz treluc'hus gwenn (N 4600 – 5400K)",
'exif-lightsource-14' => "Gouloù treluc'hus gwenn yen (W 3900 – 4500K)",
'exif-lightsource-15' => "Gouloù treluc'hus gwenn (WW 3200 – 3700K)",
'exif-lightsource-17' => 'Gouloù standard A',
'exif-lightsource-18' => 'Gouloù standard B',
'exif-lightsource-19' => 'Gouloù standard C',
'exif-lightsource-24' => 'Goulaouiñ studio gant tungsten ISO',
'exif-lightsource-255' => "Mammenn c'houloù all",

# Flash modes
'exif-flash-fired-0' => "Tamm luc'h ebet",
'exif-flash-fired-1' => "Luc'h taolet",
'exif-flash-return-0' => "ne zistro arc'hwel detektiñ ebet gant stroboskop ebet",
'exif-flash-return-2' => "disteuler a ra ar stroboskop ur goulou n'eo ket deteket",
'exif-flash-return-3' => 'ur goulou detektet a zistro gant ar stroboskop',
'exif-flash-mode-1' => "Taol luc'h dre ret",
'exif-flash-mode-2' => "tennañ an taol luc'h dre ret",
'exif-flash-mode-3' => 'Mod emgefre',
'exif-flash-function-1' => "Arc'hwel luc'h ebet",
'exif-flash-redeye-1' => 'Mod hep lagadoù ruz',

'exif-focalplaneresolutionunit-2' => 'meudad',

'exif-sensingmethod-1' => 'Hep resisaat',
'exif-sensingmethod-2' => 'Detekter takad liv monokromatek',
'exif-sensingmethod-3' => 'Detekter takad liv bikromatek',
'exif-sensingmethod-4' => 'Detekter takad liv trikromatek',
'exif-sensingmethod-5' => 'Detekter takad liv kemalennek',
'exif-sensingmethod-7' => 'Detekter teirlinennek',
'exif-sensingmethod-8' => 'Detekter liv linennek kemalennek',

'exif-filesource-3' => "Luc'hskeudennerez niverel",

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

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metr|metr}} a-us da live ar mor',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metr|metr}} a-is da live ar mor',

'exif-gpsstatus-a' => 'O vuzuliañ',
'exif-gpsstatus-v' => 'etreoberatadusted ar muzul',

'exif-gpsmeasuremode-2' => 'Muzuliañ divventek',
'exif-gpsmeasuremode-3' => 'Muzuliañ teirventek',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometr dre eur',
'exif-gpsspeed-m' => 'Miltir dre eur',
'exif-gpsspeed-n' => 'Skoulm',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometr',
'exif-gpsdestdistance-m' => 'Miltir',
'exif-gpsdestdistance-n' => 'Miltir',

'exif-gpsdop-excellent' => 'Mat-kenañ ($1)',
'exif-gpsdop-good' => 'Mat ($1)',
'exif-gpsdop-moderate' => 'Etre ($1)',
'exif-gpsdop-fair' => 'Propik ($1)',
'exif-gpsdop-poor' => 'Dister ($1)',

'exif-objectcycle-a' => 'Da vintin hepken',
'exif-objectcycle-p' => 'Diouzh an abardaez hepken',
'exif-objectcycle-b' => 'Da vintin ha diouzh an abaradez',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => "Durc'hadur gwir",
'exif-gpsdirection-m' => 'Norzh magnetek',

'exif-ycbcrpositioning-1' => 'Kreizet',
'exif-ycbcrpositioning-2' => "Ken-lec'hiet",

'exif-dc-contributor' => 'Aozerien',
'exif-dc-coverage' => 'Goloadur ar media a-fet amzer pe tachenn',
'exif-dc-date' => 'Deiziad(où)',
'exif-dc-publisher' => 'Embanner',
'exif-dc-relation' => 'Media kar',
'exif-dc-rights' => 'Gwirioù',
'exif-dc-source' => 'Media orin',
'exif-dc-type' => 'Seurt media',

'exif-rating-rejected' => 'Distaolet',

'exif-isospeedratings-overflow' => "Brasoc'h eget 65535",

'exif-iimcategory-ace' => 'Arzoù, sevenadur ha diduamantoù',
'exif-iimcategory-clj' => 'Torfedoù ha lezennoù',
'exif-iimcategory-dis' => 'Reuzioù ha gwallzarvoudoù',
'exif-iimcategory-fin' => 'Armerzh hag aferioù',
'exif-iimcategory-edu' => 'Deskadurezh',
'exif-iimcategory-evn' => 'Endro',
'exif-iimcategory-hth' => "Yec'hed",
'exif-iimcategory-hum' => 'Dedenn denel',
'exif-iimcategory-lab' => 'Bed al labour',
'exif-iimcategory-lif' => 'Doare bevañ ha dudiamantoù',
'exif-iimcategory-pol' => 'Politikerezh',
'exif-iimcategory-rel' => 'Relijion ha kredennoù',
'exif-iimcategory-sci' => 'Skiantoù ha teknologiezhoù',
'exif-iimcategory-soi' => 'Temoù kevredigezhel',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Brezelioù, tabutoù ha turmud',
'exif-iimcategory-wea' => 'Amzer',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => 'Izel ($1)',
'exif-urgency-high' => 'Uhel ($1)',
'exif-urgency-other' => 'Priorelezh termenet gant an aozer ($1)',

# External editor support
'edit-externally' => 'Kemmañ ar restr-mañ dre un arload diavaez',
'edit-externally-help' => "(Gwelet [//www.mediawiki.org/wiki/Manual:External_editors erbedadennoù staliañ an aozer diavaez] a-benn gouzout hiroc'h).",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'pep tra',
'namespacesall' => 'pep tra',
'monthsall' => 'an holl',
'limitall' => 'An holl',

# E-mail address confirmation
'confirmemail' => "Kadarnaat ar chomlec'h postel",
'confirmemail_noemail' => "N'hoc'h eus ket spisaet chomlec'h postel mat ebet en ho [[Special:Preferences|penndibaboù implijer]].",
'confirmemail_text' => "Rankout a ra ar wiki-mañ bezañ gwiriet ho chomlec'h postel a-raok gallout implijout nep arc'hwel postel. Implijit ar bouton a-is evit kas ur postel kadarnaat d'ho chomlec'h. Ul liamm ennañ ur c'hod a vo er postel. Kargit al liamm-se en o merdeer evit kadarnaat ho chomlec'h.",
'confirmemail_pending' => "Ur c'hod kadarnaat zo bet kaset deoc'h dre bostel c'hoazh;
a-raok klask goulenn unan nevez, m'emaoc'h o paouez krouiñ ho kont, e vo fur eus ho perzh gortoz un nebeud munutoù ha leuskel amzer dezhañ d'en em gavout betek ennoc'h.",
'confirmemail_send' => "Kas ur c'hod kadarnaat",
'confirmemail_sent' => 'Postel kadarnaat kaset.',
'confirmemail_oncreate' => "Kaset ez eus bet ur c'hod kadarnaat d'ho chomlec'h postel.
N'eus ket ezhomm eus ar c'hod-mañ evit kevreañ met ret e vo deoc'h ober gantañ evit aotren hini pe hini eus arc'hwelioù postel ar wiki.",
'confirmemail_sendfailed' => "Dibosupl kas ar postel kadarnaat deoc'h gant {{SITENAME}}.
Gwiriit ha n'eus ket arouezennoù direizh en ho chomlec'h.

Distro ar posteler : $1",
'confirmemail_invalid' => "Kod kadarnaat kamm. Marteze eo aet ar c'hod d'e dermen",
'confirmemail_needlogin' => "Ret eo deoc'h $1 evit kadarnaat ho chomlec'h postel.",
'confirmemail_success' => "Kadarnaet eo ho chomlec'h postel. A-benn bremañ e c'hallit [[Special:UserLogin|kevreañ]] hag ober ho mad eus ar wiki.",
'confirmemail_loggedin' => "Kadarnaet eo ho chomlec'h bremañ",
'confirmemail_error' => 'Ur gudenn zo bet e-ser enrollañ ho kadarnadenn',
'confirmemail_subject' => "Kadarnadenn chomlec'h postel evit {{SITENAME}}",
'confirmemail_body' => "Unan bennak, c'hwi moarvat, gant ar chomlec'h IP \$1,
en deus enrollet ur gont \"\$2\" gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

A-benn kadarnaat eo deoc'h ar gont-se ha gweredekaat
an arc'hwelioù postelerezh war {{SITENAME}}, digorit al liamm a-is en ho merdeer :

\$3

Ma n'eo *ket* bet enrollet ganeoc'h heuilhit al liamm-mañ
evit nullañ kadarnaat ar chomlec'h postel :

\$5

Mont a raio ar c'hod-mañ d'e dermen d'ar \$4.",
'confirmemail_body_changed' => "Unan bennak, c'hwi sur a-walc'h, gant ar chomlec'h IP \$1,
en deus cheñchet chomlec'h postel ar gont \"\$2\" gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

A-benn kadarnaat eo deoc'h ar gont-se hag adgweredekaat
ar perzhioù postel war {{SITENAME}}, digorit al liamm-mañ en ho merdeer :

\$3

Ma n'eo *ket* deoc'h ar gont, heuilhit al liamm-mañ
evit nullañ kadarnaat ar chomlec'h postel :

\$5

Mont a raio ar c'hod kadarnaat-mañ d'e dermen d'ar \$4.",
'confirmemail_body_set' => "Unan bennak, c'hwi moarvat, gant ar chomlec'h IP \$1,
en deus enrollet ur gont \"\$2\" gant ar chomlec'h postel-mañ war lec'hienn {{SITENAME}}.

Evit kadarnaat eo deoc'h ar gont-se ha gweredekaat en-dro
an arc'hwelioù postelerezh war {{SITENAME}}, digorit al liamm-mañ en ho merdeer :

\$3

Ma n'eo *ket* deoc'h ar gont heuilhit al liamm-mañ
evit nullañ kadarnaat ar chomlec'h postel :

\$5

Mont a raio ar c'hod-mañ d'e dermen d'ar \$4.",
'confirmemail_invalidated' => "Nullet eo bet kadarnaat ar chomlec'h postel",
'invalidateemail' => 'Nullañ kadarnaat ar postel',

# Scary transclusion
'scarytranscludedisabled' => '[Diweredekaet eo an treuzkludañ etrewiki]',
'scarytranscludefailed' => "[N'eus ket bet gallet tapout ar patrom evit $1]",
'scarytranscludetoolong' => '[URL re hir]',

# Delete conflict
'deletedwhileediting' => "'''Diwallit''' : Diverket eo bet ar bajenn-mañ bremañ ha krog e oac'h da zegas kemmoù enni!",
'confirmrecreate' => "Diverket eo bet ar pennad-mañ gant [[User:$1|$1]] ([[User talk:$1|kaozeal]]) goude ma vije bet kroget ganeoc'h kemmañ anezhañ :
: ''$2''
Kadarnait mar plij e fell deoc'h krouiñ ar pennad-mañ da vat.",
'confirmrecreate-noreason' => "Diverket eo bet ar pennad-mañ gant [[User:$1|$1]] ([[User talk:$1|kaozeal]]) goude ma vije bet kroget ganeoc'h kemmañ anezhañ. Kadarnait e fell deoc'h adkrouiñ ar pennad-mañ e gwirionez.",
'recreate' => 'Adkrouiñ',

# action=purge
'confirm_purge_button' => 'Mat eo',
'confirm-purge-top' => 'Spurjañ krubuilh ar bajenn-mañ?',
'confirm-purge-bottom' => 'Spurjañ ur bajenn a a naeta ar grubuilh hag a redi ar stumm nevesañ da zont war wel.',

# action=watch/unwatch
'confirm-watch-button' => 'Mat eo',
'confirm-watch-top' => "Ouzhpennañ ar bajenn-mañ d'ho roll evezhiañ",
'confirm-unwatch-button' => 'Mat eo',
'confirm-unwatch-top' => 'Lemel ar bajenn-mañ a-ziwar ho roll evezhiañ',

# Multipage image navigation
'imgmultipageprev' => '&larr; pajenn gent',
'imgmultipagenext' => "pajenn war-lerc'h &rarr;",
'imgmultigo' => 'Mont !',
'imgmultigoto' => "Mont d'ar bajenn $1",

# Table pager
'ascending_abbrev' => 'pignat',
'descending_abbrev' => 'diskenn',
'table_pager_next' => "Pajenn war-lerc'h",
'table_pager_prev' => 'Pajenn gent',
'table_pager_first' => 'Pajenn gentañ',
'table_pager_last' => 'Pajenn ziwezhañ',
'table_pager_limit' => 'Diskouez $1 elfenn dre bajenn',
'table_pager_limit_label' => "Disoc'hoù dre bajenn :",
'table_pager_limit_submit' => 'Mont',
'table_pager_empty' => "Disoc'h ebet",

# Auto-summaries
'autosumm-blank' => 'Riñset ar bajenn',
'autosumm-replace' => "Oc'h erlec'hiañ ar bajenn gant '$1'",
'autoredircomment' => 'Adkas war-du [[$1]]',
'autosumm-new' => 'Pajenn krouet gant : "$1"',

# Size units
'size-bytes' => '$1 o',
'size-kilobytes' => '$1 Kio',
'size-megabytes' => '$1 Mio',
'size-gigabytes' => '$1 Gio',

# Live preview
'livepreview-loading' => 'O kargañ...',
'livepreview-ready' => 'O kargañ... Prest !',
'livepreview-failed' => "C'hwitet eo rakwelet diouzhtu !
Klaskit rakwelet er mod boutin.",
'livepreview-error' => 'C\'hwitet kevreañ : $1 "$2"
Klaskit rakwelet er mod boutin.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Marteze ne ziskouezo ket ar roll-mañ an degasadennoù c'hoarvezet $1 {{PLURAL:$1|eilenn|eilenn}} zo hepken.",
'lag-warn-high' => "Dre m'eo soulgarget ar bankoù roadennoù, marteze ne vo ket gwelet er roll-mañ ar c'hemmoù deuet $1 {{PLURAL:$1|eilenn|eilenn}} zo hepken.",

# Watchlist editor
'watchlistedit-numitems' => '{{PLURAL:$1|1 pajenn|$1 pajenn}} zo war ho roll evezhiañ, hep kontañ ar pajennoù kaozeal.',
'watchlistedit-noitems' => "N'eus pajenn ebet war ho roll evezhiañ.",
'watchlistedit-normal-title' => 'Kemmañ ar roll evezhiañ',
'watchlistedit-normal-legend' => 'Tennañ ar pajennoù a-ziwar ho roll evezhiañ',
'watchlistedit-normal-explain' => 'Dindan emañ diskouezet titloù ar pajennoù zo war ho roll evezhiañ.
Evit tennañ unan, sellet ouzh ar voest e-kichen ha klikañ war "{{int:Watchlistedit-normal-submit}}".
Gellout a reer [[Special:EditWatchlist/raw|kemmañ ar roll (mod diginkl) ivez]].',
'watchlistedit-normal-submit' => 'Tennañ an titloù',
'watchlistedit-normal-done' => 'Tennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} a-ziwar ho roll evezhiañ :',
'watchlistedit-raw-title' => 'Kemmañ ar roll evezhiañ (mod diginkl)',
'watchlistedit-raw-legend' => 'Kemmañ ar roll evezhiañ (mod diginkl)',
'watchlistedit-raw-explain' => 'Dindan emañ titloù ar pajennoù zo war ho roll evezhiañ; gallout a rit kemmañ anezho en ur ouzhpennañ pe tennañ pajennoù a-ziwar ar roll; un titl dre linenn. Ur wech graet, klikañ war "{{int:Watchlistedit-raw-submit}}".
Gallout a rit [[Special:EditWatchlist|implijout an aozer boutin ivez]].',
'watchlistedit-raw-titles' => 'Titloù :',
'watchlistedit-raw-submit' => 'Hizivaat ar roll evezhiañ',
'watchlistedit-raw-done' => 'Nevesaet eo bet ho roll evezhiañ.',
'watchlistedit-raw-added' => 'Ouzhpennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} :',
'watchlistedit-raw-removed' => 'Tennet ez eus bet {{PLURAL:$1|1 pajenn|$1 pajenn}} :',

# Watchlist editing tools
'watchlisttools-view' => "Gwelet ar c'hemmoù degaset",
'watchlisttools-edit' => 'Gwelet ha kemmañ ar roll evezhiañ',
'watchlisttools-raw' => 'Kemmañ ar roll (mod diginkl)',

# Iranian month names
'iranian-calendar-m1' => '1añ miz Jalāli',
'iranian-calendar-m2' => '2l miz Jalāli',
'iranian-calendar-m3' => '3e miz Jalāli',
'iranian-calendar-m4' => '4e miz Jalāli',
'iranian-calendar-m5' => '5vet miz Jalāli',
'iranian-calendar-m6' => '6vet miz Jalāli',
'iranian-calendar-m7' => '7vet miz Jalāli',
'iranian-calendar-m8' => '8vet miz Jalāli',
'iranian-calendar-m9' => '9vet miz Jalāli',
'iranian-calendar-m10' => '10vet miz Jalāli',
'iranian-calendar-m11' => '11vet miz Jalāli',
'iranian-calendar-m12' => '12vet miz Jalāli',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|kaozeal]])',

# Core parser functions
'unknown_extension_tag' => 'Balizenn astenn "$1" dianav',
'duplicate-defaultsort' => 'Diwallit : Frikañ a ra an alc\'hwez dre ziouer "$2" an hini a oa a-raok "$1".',

# Special:Version
'version' => 'Stumm',
'version-extensions' => 'Astennoù staliet',
'version-specialpages' => 'Pajennoù dibar',
'version-parserhooks' => 'Galvoù dielfennañ',
'version-variables' => 'Argemmennoù',
'version-antispam' => 'Mirout ouzh ar strob',
'version-skins' => 'Gwiskadurioù',
'version-other' => 'Diseurt',
'version-mediahandlers' => 'Merer danvez liesvedia',
'version-hooks' => 'Galvoù',
'version-extension-functions' => "Arc'hwelioù an astennoù",
'version-parser-extensiontags' => 'Balizenn dielfennañ o tont eus an astennoù',
'version-parser-function-hooks' => "Galv an arc'hwelioù dielfennañ",
'version-hook-name' => 'Anv ar galv',
'version-hook-subscribedby' => 'Termenet gant',
'version-version' => '(Stumm $1)',
'version-license' => 'Aotre-implijout',
'version-poweredby-credits' => "Mont a ra ar wiki-mañ en-dro a-drugarez da '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 're all',
'version-license-info' => "Ur meziant frank eo MediaWiki; gallout a rit skignañ anezhañ ha/pe kemmañ anezhañ dindan termenoù ar GNU Aotre-implijout Foran Hollek evel m'emañ embannet gant Diazezadur ar Meziantoù Frank; pe diouzh stumm 2 an aotre-implijout, pe (evel mar karit) ne vern pe stumm nevesoc'h.

Ingalet eo MediaWiki gant ar spi e vo talvoudus met n'eus TAMM GWARANT EBET; hep zoken gwarant empleg ar VARC'HADUSTED pe an AZASTER OUZH UR PAL BENNAK. Gwelet ar GNU Aotre-Implijout Foran Hollek evit muioc'h a ditouroù.

Sañset oc'h bezañ resevet [{{SERVER}}{{SCRIPTPATH}}/COPYING un eilskrid eus ar GNU Aotre-implijout Foran Hollek] a-gevret gant ar programm-mañ; ma n'hoc'h eus ket, skrivit da Diazezadur ar Meziantoù Frank/Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, SUA pe [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lennit anezhañ enlinenn].",
'version-software' => 'Meziant staliet',
'version-software-product' => 'Produ',
'version-software-version' => 'Stumm',
'version-entrypoints' => 'URLoù ar poent mont e-barzh',
'version-entrypoints-header-entrypoint' => 'Poent mont e-barzh',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Hent moned ur restr',
'filepath-page' => 'Restr :',
'filepath-submit' => 'Mont',
'filepath-summary' => 'Diskouez a ra ar bajenn-mañ hent moned klok ur restr.
Diskouezet eo ar skeudennoù gant ur pizhder uhel, erounit a ra ar restroù all war-eeun gant o frogramm stag.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Klask ar restroù e doubl',
'fileduplicatesearch-summary' => 'Klask restroù e doubl war diazez talvoudennoù krennet.',
'fileduplicatesearch-legend' => 'Klask un doubl',
'fileduplicatesearch-filename' => 'Anv ar restr :',
'fileduplicatesearch-submit' => 'Klask',
'fileduplicatesearch-info' => '$1 × $2 piksel<br />Ment ar restr : $3<br />seurt MIME : $4',
'fileduplicatesearch-result-1' => 'N\'eus ket a zoubloù heñvel-poch gant ar restr "$1".',
'fileduplicatesearch-result-n' => '{{PLURAL:$2|1 doubl heñvel-poch|$2 doubl heñvel-poch}} zo gant ar restr "$1".',
'fileduplicatesearch-noresults' => 'N\'eus bet kavet restr ebet anvet "$1".',

# Special:SpecialPages
'specialpages' => 'Pajennoù dibar',
'specialpages-note' => '----
* Pajennoù dibar boutin.
* <span class="mw-specialpagerestricted">Pajennoù dibar miret strizh.</span>
* <span class="mw-specialpagecached">Pajennoù dibar krubuilhet hepken (a c\'hellfe bezañ re gozh).</span>',
'specialpages-group-maintenance' => "Rentaoù-kont trezalc'h",
'specialpages-group-other' => 'Pajennoù dibar all',
'specialpages-group-login' => 'Kevreañ / krouiñ ur gont',
'specialpages-group-changes' => 'Kemmoù diwezhañ ha marilhoù',
'specialpages-group-media' => 'Danevelloù ar restroù media hag an enporzhiadennoù',
'specialpages-group-users' => 'An implijerien hag o gwirioù',
'specialpages-group-highuse' => 'Implij stank ar pajennoù',
'specialpages-group-pages' => 'Rolloù pajennoù',
'specialpages-group-pagetools' => 'Ostilhoù evit ar pajennoù',
'specialpages-group-wiki' => 'Roadennoù ar wiki hag ostilhoù',
'specialpages-group-redirects' => 'Adkas ar pajennoù dibar',
'specialpages-group-spam' => 'Ostilh enepstrob',

# Special:BlankPage
'blankpage' => "Pajenn c'houllo",
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
'tags' => "Balizennoù ar c'hemmoù reizh",
'tag-filter' => 'Silañ ar [[Special:Tags|balizennoù]] :',
'tag-filter-submit' => 'Silañ',
'tags-title' => 'Balizennoù',
'tags-intro' => "Rollañ a ra ar bajenn-mañ ar balizennoù a c'hall ar meziant implijout da verkañ kemmoù hag an dalvoudegezh anezho.",
'tags-tag' => 'Anv ar valizenn',
'tags-display-header' => "Neuz e rolloù ar c'hemmoù",
'tags-description-header' => 'Deskrivadur klok ar valizenn',
'tags-hitcount-header' => 'Kemmoù balizennet',
'tags-edit' => 'aozañ',
'tags-hitcount' => '$1 {{PLURAL:$1|kemm|kemm}}',

# Special:ComparePages
'comparepages' => 'Keñveriañ pajennoù',
'compare-selector' => 'Keñveriañ stummoù ar pajennoù',
'compare-page1' => 'Pajenn 1',
'compare-page2' => 'Pajenn 2',
'compare-rev1' => 'Adweladenn 1',
'compare-rev2' => 'Adweladenn 2',
'compare-submit' => 'Keñveriañ',
'compare-invalid-title' => "Kamm eo an titl hoc'h eus merket.",
'compare-title-not-exists' => "N'eus ket eus an titl spisaet ganeoc'h.",
'compare-revision-not-exists' => "N'eus ket eus an adweladenn spisaet ganeoc'h.",

# Database error messages
'dberr-header' => 'Ur gudenn zo gant ar wiki-mañ',
'dberr-problems' => "Ho tigarez ! Kudennoù teknikel zo gant al lec'hienn-mañ.",
'dberr-again' => 'Gortozit un nebeud munutennoù a-raok adkargañ.',
'dberr-info' => '(Dibosupl kevreañ ouzh servijer an diaz roadennoù: $1)',
'dberr-usegoogle' => "E-keit-se esaeit klask dre c'hGoogle.",
'dberr-outofdate' => "Notit mat e c'hall o menegerioù dezho bezañ dispredet e-keñver ar boued zo ganeomp.",
'dberr-cachederror' => 'Un eilstumm memoret eus ar bajenn goulennet eo hemañ, gallout a ra bezañ dispredet.',

# HTML forms
'htmlform-invalid-input' => "Kudennoù zo gant talvoudennoù zo merket ganeoc'h.",
'htmlform-select-badoption' => "Direizh eo an dalvoudenn skrivet ganeoc'h.",
'htmlform-int-invalid' => "N'eus ket un niver anterin eus an dalvoudenn skrivet ganeoc'h.",
'htmlform-float-invalid' => "An dalvoudenn bet lakaet ganeoc'h n'eo ket un niver.",
'htmlform-int-toolow' => "Skrivet hoc'h eus un dalvoudenn zo dindan an niver bihanañ aotreet a $1",
'htmlform-int-toohigh' => "Skrivet hoc'h eus un dalvoudenn a ya dreist d'an niver uhelañ aotreet a $1",
'htmlform-required' => 'An talvoudenn-mañ a zo ret',
'htmlform-submit' => 'Kas',
'htmlform-reset' => "Dizober ar c'hemmoù",
'htmlform-selectorother-other' => 'Unan all',

# SQLite database support
'sqlite-has-fts' => '$1 gant enklask eus an destenn a-bezh embreget',
'sqlite-no-fts' => '$1 hep enklask eus an destenn a-bezh embreget',

# New logging system
'logentry-delete-delete' => 'Diverket eo bet ar bajenn $3 gant $1',
'logentry-delete-restore' => 'Assavet eo bet ar bajenn $3 gant $1',
'logentry-delete-event' => "Kemmet eo bet gwelusted {{PLURAL:$5|un darvoud eus ar marilh|$5 darvoud eus ar marilh}} d'an $3 gant $1 : $4",
'logentry-delete-revision' => 'Kemmet eo bet gwelusted {{PLURAL:$5|ur reizhadenn|$5 reizhadenn}} war ar bajenn $3 gant $1 : $4',
'logentry-delete-event-legacy' => 'Kemmet eo bet gwelusted darvoudoù ar marilh $3 gant $1',
'logentry-delete-revision-legacy' => 'Kemmet eo bet gwelusted ar reizhadennoù war ar bajenn $3 gant $1',
'logentry-suppress-delete' => 'Diverket eo bet ar bajenn $3 gant $1',
'logentry-suppress-event' => "Kemmet eo bet dre guzh gwelusted {{PLURAL:$5|un darvoud eus ar marilh|$5 darvoud eus ar marilh}} d'an $3 gant $1 : $4",
'logentry-suppress-revision' => 'Kemmet eo bet dre guzh gwelusted {{PLURAL:$5|ur reizhadenn|$5 reizhadenn}} war ar bajenn $3 gant $1 : $4',
'logentry-suppress-event-legacy' => 'Kemmet eo bet dre guzh gwelusted darvoudoù ar marilh $3 gant $1',
'logentry-suppress-revision-legacy' => 'Kemmet eo bet dre guzh gwelusted ar reizhadennoù war ar bajenn $3 gant $1',
'revdelete-content-hid' => 'danvez kuzet',
'revdelete-summary-hid' => 'kemmañ an diverrañ kuzhet',
'revdelete-uname-hid' => 'anv implijer kuzhet',
'revdelete-content-unhid' => 'danvez war wel',
'revdelete-summary-unhid' => 'kemmañ an diverrañ zo war wel',
'revdelete-uname-unhid' => 'anv implijer war wel',
'revdelete-restricted' => 'Lakaat ar strishadurioù da dalvezout evit ar verourien',
'revdelete-unrestricted' => 'dilemel ar strishadurioù evit ar verourien',
'logentry-move-move' => '$1 kaset ar bajenn $3 da $4',
'logentry-move-move-noredirect' => 'kaset ar bajenn $3 da $4 gant $1 hep adkas',
'logentry-move-move_redir' => 'kaset ar bajenn $3 da $4 gant $1 dreist un adkas',
'logentry-move-move_redir-noredirect' => 'kaset ar bajenn $3 da $4 gant $1 dreist un adkas hep lezel un adkas',
'logentry-patrol-patrol' => 'Merket eo bet an adweladenn $4 eus ar bajenn $3 evel gwiriet gant $1',
'logentry-patrol-patrol-auto' => 'Merket eo bet ent emgefre an adweladenn $4 eus ar bajenn $3 evel gwiriet gant $1',
'logentry-newusers-newusers' => 'Krouet eo bet ar gont implijer $1',
'logentry-newusers-create' => 'Krouet eo bet ar gont implijer $1',
'logentry-newusers-create2' => 'Gant $1 eo bet krouet ar gont implijer $3',
'logentry-newusers-autocreate' => 'Krouet eo bet kont $1 ent emgefre',
'newuserlog-byemail' => 'ger-tremen kaset dre bostel',

# Feedback
'feedback-bugornote' => "Ma'z oc'h prest da zeskrivañ ur gudenn deknikel dre ar munud e c'hallit [\$1 kemenn un draen].
A-hend-all e c'hallit ober gant ar furmskrid eeunaet dindan. Ouzhpennet e vo hoc'h evezhiadenn d'ar bajenn \"[\$3 \$2]\", a-gevret gant hoc'h anv implijer hag anv ar merdeer a rit gantañ.",
'feedback-subject' => 'Danvez :',
'feedback-message' => 'Kemennadenn :',
'feedback-cancel' => 'Nullañ',
'feedback-submit' => 'Kas ho soñj',
'feedback-adding' => "Oc'h ouzhpennañ ho soñj war ar bajenn...",
'feedback-error1' => "Fazi : disoc'h dianav a-berzh an API",
'feedback-error2' => "Fazi : N'eus ket bet gallet degemer ar c'hemmoù",
'feedback-error3' => 'Fazi : respont ebet a-berzh an API',
'feedback-thanks' => 'Ho trugarekaat ! Postet eo bet hoc\'h evezhiadenn d\'ar bajenn "[$2 $1]".',
'feedback-close' => 'Graet',
'feedback-bugcheck' => "Eus ar c'hentañ ! Gwiriit mat n'emañ ket e-touez an [$1 draen diskoachet c'hoazh].",
'feedback-bugnew' => 'Gwiriet em eus. Kemenn un draen nevez',

# Search suggestions
'searchsuggest-search' => 'Klask',
'searchsuggest-containing' => 'ennañ...',

# API errors
'api-error-badaccess-groups' => "N'oc'h ket aotreet da enporzhiañ restroù war ar wiki-mañ.",
'api-error-badtoken' => 'Fazi diabarzh : "jedouer" fall.',
'api-error-copyuploaddisabled' => 'Diweredekaet eo an enporzhioù dre URL war ar servijer-mañ.',
'api-error-duplicate' => "Bez' {{PLURAL:$1|ez eus [$2 restr all]|[$2 restr all]}} gant an hevelep danvez war al lec'hienn-mañ c'hoazh",
'api-error-duplicate-archive' => "Bez' e oa {{PLURAL:$1|[$2 ur restr all]|[$2 restroù all]}} c'hoazh enno an hevelep danvez, nemet {{PLURAL:$1|eo bet diverket|int bet diverket}}.",
'api-error-duplicate-archive-popup-title' => "Eilañ ar {{PLURAL:$1|restr|restroù}} zo bet diverket c'hoazh",
'api-error-duplicate-popup-title' => 'Eilañ {{PLURAL:$1|ar restr|ar restroù}}',
'api-error-empty-file' => "Ar restr hoc'h eus roet a oa goullo.",
'api-error-emptypage' => "N'eo ket aotreet krouiñ pajennoù goullo.",
'api-error-fetchfileerror' => 'Fazi diabarzh : aet ez eus un dra bennak a-dreuz en ur glask adtapout ar restr.',
'api-error-fileexists-forbidden' => 'Bez\' ez eus c\'hoazh eus ur restr anvet "$1" ha n\'hall ket bezañ friket.',
'api-error-fileexists-shared-forbidden' => "Bez' ez eus c'hoazh eus ur restr anvet \"\$1\" er c'havlec'h kenrannañ restroù ha n'hall ket bezañ friket anezhi.",
'api-error-file-too-large' => "Ar restr hoc'h eus roet a oa re vras.",
'api-error-filename-tooshort' => 'Re verr eo anv ar restr.',
'api-error-filetype-banned' => 'Difennet eo ar seurt restroù',
'api-error-filetype-banned-type' => "N'eo ket $1{{PLURAL:$4|ur seurt restr aotreet | seurtoù restroù aotreet}}. $2 zo {{PLURAL:$3|ar seurt restroù|ar seurtoù restroù}} degemeret.?",
'api-error-filetype-missing' => "Un astenn a vank d'ar restr.",
'api-error-hookaborted' => "Ar c'hemm hoc'h eus klasket degas zo bet harzet gant ur c'hrog astenn.",
'api-error-http' => "Fazi diabarzh : dibosupl kevreañ d'ar servijer.",
'api-error-illegal-filename' => "N'eo ket aotreet anv ar restr.",
'api-error-internal-error' => "Fazi diabarzh : aet ez eus un dra bennak a dreuz en ur dretiñ hoc'h enporzhiadenn war ar wiki.",
'api-error-invalid-file-key' => "Fazi diabarzh : n'eo ket bet kavet ar restr er stokañ da c'hortoz.",
'api-error-missingparam' => 'Fazi diabarzh : Arventennoù a vank er reked.',
'api-error-missingresult' => 'Fazi diabarzh : dibosupl termeniñ hag eilet eo bet an traoù ervat.',
'api-error-mustbeloggedin' => "Ret eo deoc'h bezañ kevreet evit pellgargañ restroù.",
'api-error-mustbeposted' => 'Un draen a zo er poellad-mañ ; ne implij ket an hentenn HTTP ret.',
'api-error-noimageinfo' => "Kaset eo bet ar pellgargañ ervat met n'eus ket bet roet tamm titour ebet deomp diwar-benn ar restr gant ar servijer",
'api-error-nomodule' => 'Fazi diabarzh : tamm modulenn enporzhiañ ebet.',
'api-error-ok-but-empty' => 'Fazi diabarzh : respont ebet a-berzh ar servijer.',
'api-error-overwrite' => "N'eo ket aotreet frikañ ur restr zo anezhi c'hoazh.",
'api-error-stashfailed' => "Fazi diabarzh : dibosupl d'ar servijer enrollañ ar restr padennek.",
'api-error-timeout' => "N'eo ket bet ar servijer evit respont en termen lakaet.",
'api-error-unclassified' => "C'hoarvezet ez eus ur gudenn dianav.",
'api-error-unknown-code' => 'Fazi dianav : "$1"',
'api-error-unknown-error' => 'Fazi diabarzh : aet ez eus un dra bennak a-dreuz en ur glask enporzhiañ ho restr.',
'api-error-unknown-warning' => 'Kemenn diwall dianav : $1',
'api-error-unknownerror' => 'Fazi dianav : "$1".',
'api-error-uploaddisabled' => 'Diweredekaat eo an enporzh war ar wiki-mañ.',
'api-error-verification-error' => 'Pe eo brein ar restr pe eo fall an astenn anezhi.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|eilenn|eilenn}}',
'duration-minutes' => '$1 {{PLURAL:$1|munut|munut}}',
'duration-hours' => '($1 {{PLURAL:$1|eurvezh|eurvezh}})',
'duration-days' => '($1 {{PLURAL:$1|deiz|deiz}})',
'duration-weeks' => '$1 {{PLURAL:$1|sizhun|sizhun}}',
'duration-years' => '$1 {{PLURAL:$1|bloaz|bloaz}}',
'duration-decades' => '$1 {{PLURAL:$1|degad|degas}}',
'duration-centuries' => "$1 {{PLURAL:$1|c'hantved|kantved}}",
'duration-millennia' => '$1 {{PLURAL:$1|milvloaz|milvoaz}}',

);
