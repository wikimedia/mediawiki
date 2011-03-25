<?php
/** Nahuatl (Nāhuatl)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Fluence
 * @author Reedy
 * @author Ricardo gs
 * @author Rob Church <robchur@gmail.com>
 * @author Teòtlalili
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA            => 'Mēdiatl',
	NS_SPECIAL          => 'Nōncuahquīzqui',
	NS_TALK             => 'Tēixnāmiquiliztli',
	NS_USER             => 'Tlatequitiltilīlli',
	NS_USER_TALK        => 'Tlatequitiltilīlli_tēixnāmiquiliztli',
	NS_PROJECT_TALK     => '$1_tēixnāmiquiliztli',
	NS_FILE             => 'Īxiptli',
	NS_FILE_TALK        => 'Īxiptli_tēixnāmiquiliztli',
	NS_MEDIAWIKI        => 'Huiquimedia',
	NS_MEDIAWIKI_TALK   => 'Huiquimedia_tēixnāmiquiliztli',
	NS_TEMPLATE         => 'Nemachiyōtīlli',
	NS_TEMPLATE_TALK    => 'Nemachiyōtīlli_tēixnāmiquiliztli',
	NS_HELP             => 'Tēpalēhuiliztli',
	NS_HELP_TALK        => 'Tēpalēhuiliztli_tēixnāmiquiliztli',
	NS_CATEGORY         => 'Neneuhcāyōtl',
	NS_CATEGORY_TALK    => 'Neneuhcāyōtl_tēixnāmiquiliztli',
);

$namespaceAliases = array(
	'Media'		=> NS_MEDIA,
	'Especial'	=> NS_SPECIAL,
	'Discusión'	=> NS_TALK,
	'Usuario'	=> NS_USER,
	'Usuario_Discusión'	=> NS_USER_TALK,
	'Wikipedia'	=> NS_PROJECT,
	'Wikipedia_Discusión'	=> NS_PROJECT_TALK,
	'Imagen'	=> NS_FILE,
	'Imagen_Discusión'	=> NS_FILE_TALK,
	'MediaWiki'	=> NS_MEDIAWIKI,
	'MediaWiki_Discusión'	=> NS_MEDIAWIKI_TALK,
	'Plantilla'	=> NS_TEMPLATE,
	'Plantilla_Discusión'	=> NS_TEMPLATE_TALK,
	'Ayuda'		=> NS_HELP,
	'Ayuda_Discusión'	=> NS_HELP_TALK,
	'Categoría'	=> NS_CATEGORY,
	'Categoría_Discusión'	=> NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'Tlacalaquiliztli', 'Registrarse' ),
	'Upload'                    => array( 'Quetza', 'Subir' ),
	'Shortpages'                => array( 'Zāzaniltōn', 'PáginasCortas' ),
	'Longpages'                 => array( 'HuēiyacZāzaniltin', 'PáginasLargas' ),
	'Newpages'                  => array( 'YancuīcZāzaniltin', 'PáginasNuevas' ),
	'Ancientpages'              => array( 'HuēhuehZāzaniltin', 'PáginasViejas' ),
	'Allpages'                  => array( 'MochīntīnZāzaniltin', 'TodasPáginas' ),
	'Specialpages'              => array( 'NōncuahquīzquiĀmatl', 'PáginasEspeciales' ),
	'Emailuser'                 => array( 'EmailTlācatl', 'CorreoUsuario' ),
	'Categories'                => array( 'Neneuhcāyōtl', 'Categorías' ),
	'Mypage'                    => array( 'Nozāzanil', 'MiPágina' ),
	'Mytalk'                    => array( 'Notēixnāmiquiliz', 'MiDiscusión' ),
	'Mycontributions'           => array( 'Notlahcuilōl', 'MisContribuciones' ),
	'Search'                    => array( 'Tlatēmōz', 'Buscar' ),
);

$messages = array(
# User preference toggles
'tog-underline'            => 'Mokìnxòîkuilòtzàswis tzòwilistìn:',
'tog-highlightbroken'      => 'Tiquinttāz tzomoc tzonhuiliztli <a href="" class="new">zan iuhquin inīn</a> (ahnozo: zan iuhquin inīn<a href="" class="internal">?</a>).',
'tog-hideminor'            => 'Motlàtìs tepỉtzìn tlayèktlàlilistli ìpan welok tlapảtlalistli',
'tog-extendwatchlist'      => 'Tiquittaz in tlachiyaliztli ic mochīntīn tlapatlaliztli, ahmo zan in ocachi yancuīc.',
'tog-usenewrc'             => 'Tlacualtīlli yancuīc tlapatlaliztli (quinequi JavaScript)',
'tog-showtoolbar'          => 'Tiquittāz in tlein motequitiltia tlapatlaliztechcopa (JavaScript)',
'tog-editondblclick'       => 'Tiquimpatlāz zāzaniltin ōme clicca (JavaScript)',
'tog-showtoc'              => 'Tiquittāz in tlein cah zāzotlahcuilōlco',
'tog-rememberpassword'     => 'Ticpiyāz moMotlatequitiltilīltōca īhuān motlahtōlichtacāyo inīn chīuhpōhualhuazco (īxquich {{PLURAL:$1|tōnalli|tōnalli}})',
'tog-watchcreations'       => 'Tiquintlachiyāz zāzaniltin tiquinchīhua',
'tog-watchdefault'         => 'Tiquintlachiyāz zāzaniltin tiquimpatla',
'tog-watchmoves'           => 'Tiquintlachiyāz zāzaniltin tiquinzaca',
'tog-watchdeletion'        => 'Tiquintlachiyāz zāzaniltin tiquimpoloa',
'tog-minordefault'         => 'Ticmachiyōtīz mochīntīn tlapatlalitzintli ic default',
'tog-previewontop'         => 'Tiquittāz achtochīhualiztli achtopa tlapatlaliztli caxitl',
'tog-previewonfirst'       => 'Xiquitta achtochīhualiztli inic cē tlapatlalizpan',
'tog-enotifwatchlistpages' => 'Timitz-e-mailīzqueh ihcuāc mopatla cē zāzanilli tictlachiya.',
'tog-enotifusertalkpages'  => 'Nēchihtoa ihcuāc tlecpatla motēixnāmiquiliz',
'tog-enotifminoredits'     => 'Timitz-e-mailīzqueh nō zāzanilpatlatzintli ītechcopa',
'tog-enotifrevealaddr'     => 'Ticnēxtīz mo e-mailcān āxcāncayōtechcopa āmatlacuilizpan',
'tog-shownumberswatching'  => 'Tiquinttāz tlatequitiltilīlli tlein tlachiyacateh',
'tog-forceeditsummary'     => 'Xinēchnōtzāz ihcuāc ahmo niquihtōz inōn ōnitlapatlac',
'tog-watchlisthideown'     => 'Tiquintlātīz mopatlaliz motlachiyalizpan',
'tog-watchlisthidebots'    => 'Tiquintlātīz tepozpatlaliztli motlachiyalizpan',
'tog-watchlisthideminor'   => 'Tiquintlātīz tlapatlalitzintli motlachiyalizpan',
'tog-watchlisthideliu'     => 'Tiquintlātīz tlācah ōmocalacqueh īntlapatlaliz motlachiyalizpan',
'tog-watchlisthideanons'   => 'Tiquintlātīz tlācah ahtōcāitl īntlapatlaliz motlachiyalizpan',
'tog-nolangconversion'     => 'Ahmo tictēquitiltia tlahtōlcuepaliztli',
'tog-ccmeonemails'         => 'Nō xinēch-mailīz ihcuāc nitē-mailīz tlatequitiltilīlli',
'tog-diffonly'             => 'Ahmo tiquittāz zāzanilli ītlapiyaliz ahneneuhquilitzīntlan',
'tog-showhiddencats'       => 'Xiquitta motlātiani neneuhcāyōtl',

'underline-always' => 'Mochipa',
'underline-never'  => 'Aīcmah',

# Font style option in Special:Preferences
'editfont-monospace' => 'Cencoyāhualiztli machiyōtlahtōliztli',
'editfont-sansserif' => 'Sans-serif machiyōtlahtōliztli',
'editfont-serif'     => 'Serif machiyōtlahtōliztli',

# Dates
'sunday'        => 'Tōnatiuhtōnal',
'monday'        => 'Mētztlitōnal',
'tuesday'       => 'Huītzilōpōchtōnal',
'wednesday'     => 'Yacatlipotōnal',
'thursday'      => 'Tezcatlipotōnal',
'friday'        => 'Quetzalcōātōnal',
'saturday'      => 'Tlāloctitōnal',
'sun'           => 'Tōn',
'mon'           => 'Mētz',
'tue'           => 'Huītz',
'wed'           => 'Yac',
'thu'           => 'Tez',
'fri'           => 'Quetz',
'sat'           => 'Tlāl',
'january'       => 'Àtemòstli',
'february'      => 'Iskalli',
'march'         => 'Àtlakàwalo',
'april'         => 'Tlàkaxipèwalistli',
'may_long'      => 'Tosostli',
'june'          => 'Toxkatl',
'july'          => 'Tèkòilwitl',
'august'        => 'Tlaxòchimàko',
'september'     => 'Xokowetzi',
'october'       => 'Teòtlêko',
'november'      => 'Tepèilwitl',
'december'      => 'Pànketzalistli',
'january-gen'   => 'Tlacēnti',
'february-gen'  => 'Tlaōnti',
'march-gen'     => 'Tlayēti',
'april-gen'     => 'Tlanāuhti',
'may-gen'       => 'Tlamācuīlti',
'june-gen'      => 'Tlachicuazti',
'july-gen'      => 'Tlachicōnti',
'august-gen'    => 'Tlachicuēiti',
'september-gen' => 'Tlachiucnāuhti',
'october-gen'   => 'Tlamahtlācti',
'november-gen'  => 'Tlamahtlāccēti',
'december-gen'  => 'Tlamahtlācōnti',
'jan'           => 'Cēn',
'feb'           => 'Ōnt',
'mar'           => 'Yēt',
'apr'           => 'Nāuh',
'may'           => 'Mācuīl',
'jun'           => 'Chicuacē',
'jul'           => 'Chicōn',
'aug'           => 'Chicuēi',
'sep'           => 'Chiucnāuh',
'oct'           => 'Mahtlāc',
'nov'           => 'Īhuāncē',
'dec'           => 'Īhuānōme',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Tlaìxmatkàtlàlilòtl|Tlaìxmatkàtlàlilòmë}}',
'category_header'                => 'Tlâkuilòlpiltin ìpan tlaìxmatkàtlàlilòtl "$1"',
'subcategories'                  => 'Tlaìxmatkàtlàlilòpilòmë',
'category-media-header'          => 'Media "$1" neneuhcāyōc',
'category-empty'                 => "''Cah ahtlein inīn neneuhcāyōc.''",
'hidden-categories'              => '{{PLURAL:$1|tlatlàtìlli tlaìxmatkàtlàlilòtl|tlatlàtìltìn tlaìxmatkàtlàlilòmë}}',
'hidden-category-category'       => 'Tlatlàtìlkàtlaìxmatkàtlàlilòmë',
'category-subcat-count'          => '{{PLURAL:$2|Inìn tlaìxmatkàtlàlilòtl kipia san inìn tlaìxmatkàtlàlilòpilli.|Inìn tlaìxmatkàtlàlilòtl {{PLURAL:$1|kipia inìn tlaìxmatkàtlàlilòpilli|kimpia inìn $1 tlaìxmatkàtlàlilòpiltìn}}, ìpan $2.}}',
'category-subcat-count-limited'  => 'Inīn {{PLURAL:$1|neneuhcāyōtzintli cah|$1 neneuhcāyōtzintli cateh}} inīn neneuhcāyōc.',
'category-article-count'         => '{{PLURAL:$2|Inīn neneuhcāyōtl zan quipiya|Inīn neneuhcāyōtl quimpiya {{PLURAL:$1|inīn zāzanilli|inīn $1 zāzanilli}}, īhuīcpa $2.}}',
'category-article-count-limited' => 'Inīn {{PLURAL:$1|zāzanilli cah|$1 zāzanilli cateh}} inīn neneuhcāyōc.',
'category-file-count'            => '{{PLURAL:$2|Inīn neneuhcāyōtl zan quipiya|Inīn neneuhcāyōtl quimpiya {{PLURAL:$1|inīn tlahcuilōlli|inīn $1 tlahcuilōlli}}, īhuīcpa $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Inìn tlâkuilòlèwalli kä|Inîkë $1 tlâkuilòlèwaltìn katêkë}} ìpan inìn tlaìxmatkàtlàlilòtl.',
'listingcontinuesabbrev'         => 'niman',

'mainpagetext' => "'''MediaHuiqui cualli ōmotlahtlāli.'''",

'about'         => 'Ītechpa',
'article'       => 'Tlâkuilòpilli',
'newwindow'     => '(Motlapoāz cē yancuīc tlanexillōtl)',
'cancel'        => 'Ticcuepāz',
'moredotdotdot' => 'Huehca ōmpa...',
'mypage'        => 'Nozāzanil',
'mytalk'        => 'Notēixnāmiquiliz',
'anontalk'      => 'Inīn IP ītēixnāmiquiliz',
'navigation'    => 'Nènemòwalistli',
'and'           => '&#32;īhuān',

# Cologne Blue skin
'qbfind'         => 'Tlatēmōz',
'qbbrowse'       => 'Titlatēmōz',
'qbedit'         => 'Ticpatlāz',
'qbpageoptions'  => 'Inīn zāzanilli',
'qbpageinfo'     => 'Tlahcuilōltechcopa',
'qbmyoptions'    => 'Nozāzanil',
'qbspecialpages' => 'Nònkuâkìskàtlaìxtlapaltìn',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'   => 'Ticpolōz',
'vector-action-move'     => 'Ticzacāz',
'vector-action-protect'  => 'Ticquīxtīz',
'vector-view-create'     => 'Ticchīhuāz',
'vector-view-edit'       => 'Ticpatlāz',
'vector-view-history'    => 'Tlahcuilōlli tlahcuilōlloh',
'vector-view-view'       => 'Tāmapōhuaz',
'vector-view-viewsource' => 'Tiquittāz in mēyalli',
'namespaces'             => 'Tòkâyeyàntìn',

'errorpagetitle'    => 'Ahcuallōtl',
'returnto'          => 'Timocuepāz īhuīc $1.',
'tagline'           => 'Īhuīcpa {{SITENAME}}',
'help'              => 'Tēpalēhuiliztli',
'search'            => 'Tlatēmōz',
'searchbutton'      => 'Tlatēmōz',
'go'                => 'Yāuh',
'searcharticle'     => 'Yāuh',
'history'           => 'tlahcuilōlloh',
'history_short'     => 'Tlahcuilōlloh',
'updatedmarker'     => 'ōmoyancuīx īhuīcpa xōcoyōc notlahpololiz',
'info_short'        => 'Tlanōnōtzaliztli',
'printableversion'  => 'Tepoztlahcuilōlli',
'permalink'         => 'Mochipa tzonhuiliztli',
'print'             => 'Tictepoztlahcuilōz',
'view'              => 'Mà mỏta',
'edit'              => 'Ticpatlāz',
'create'            => 'Ticchīhuāz',
'editthispage'      => 'Ticpatlāz inīn zāzanilli',
'create-this-page'  => 'Ticchīhuāz inīn zāzanilli',
'delete'            => 'Ticpolōz',
'deletethispage'    => 'Ticpolōz inīn zāzanilli',
'undelete_short'    => 'Ahticpolōz {{PLURAL:$1|cē tlapatlaliztli|$1 tlapatlaliztli}}',
'viewdeleted_short' => 'Mà mỏta {{PLURAL:$1|se tlatlaìxpôpolòlli tlayèktlàlilistli|$1 tlatlaìxpôpolòltin tlayèktlàlilistin}}',
'protect'           => 'Ticquīxtīz',
'protect_change'    => 'ticpatlāz',
'protectthispage'   => 'Ticquīxtiāz inīn zāzanilli',
'unprotect'         => 'Ahticquīxtīz',
'unprotectthispage' => 'Ahticquīxtīz inīn zāzanilli',
'newpage'           => 'Yancuīc zāzanilli',
'talkpage'          => 'Tictlahtōz inīn zāzaniltechcopa',
'talkpagelinktext'  => 'Tèìxnàmikilistli',
'specialpage'       => 'Nònkuâkìskàtlaìxtlapalli',
'personaltools'     => 'In tlein nitēquitiltilia',
'postcomment'       => 'Yancuīc xeliuhcāyōtl',
'articlepage'       => 'Xiquittaz in tlahcuilōlli',
'talk'              => 'tēixnāmiquiliztli',
'views'             => 'Tlachiyaliztli',
'toolbox'           => 'In tlein motequitiltia',
'userpage'          => 'Xiquitta tlatequitiltilīlli zāzanilli',
'projectpage'       => 'Xiquitta tlachīhualiztli zāzanilli',
'imagepage'         => 'Tiquittaz in zāzanilli īāma',
'mediawikipage'     => 'Xiquitta tlahcuilōltzin zāzanilli',
'templatepage'      => 'Tiquittāz nemachiyōtīlli zāzanilli',
'viewhelppage'      => 'Xiquitta tēpalēhuiliztli zāzanilli',
'categorypage'      => 'Xiquitta neneuhcāyōtl zāzanilli',
'viewtalkpage'      => 'Xiquitta tēixnāmiquiliztli zāzanilli',
'otherlanguages'    => 'Occequīntīn tlahtōlcopa',
'redirectedfrom'    => '(Ōmotlacuep īhuīcpa $1)',
'redirectpagesub'   => 'Ōmotlacuep zāzanilli',
'lastmodifiedat'    => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1.',
'viewcount'         => 'Inīn zāzanilli quintlapōhua {{PLURAL:$1|cē tlahpololiztli|$1 tlahpololiztli}}.',
'protectedpage'     => 'Ōmoquīxtix zāzanilli',
'jumpto'            => 'Īhuīcpa ticholōz:',
'jumptonavigation'  => 'nènemòwalistli',
'jumptosearch'      => 'tlatēmoliztli',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ītechcopa {{SITENAME}}',
'aboutpage'            => 'Project:Ītechcopa',
'copyright'            => 'Tlahcuilōltzin cah yōllōxoxouhqui īpan $1',
'copyrightpage'        => '{{ns:project}}:Tlachīhualōni ītlapiyaliz',
'currentevents'        => 'Āxcāncāyōtl',
'currentevents-url'    => 'Project:Āxcāncāyōtl',
'disclaimers'          => 'Nahuatīllahtōl',
'edithelp'             => 'Tlapatlaliztechcopa tēpalēhuiliztli',
'edithelppage'         => 'Help:¿Quēn motlahcuiloa cē zāzanilli?',
'helppage'             => 'Help:Tlapiyaliztli',
'mainpage'             => 'Achkàuhìxtlapalli',
'mainpage-description' => 'Achkàuhìxtlapalli',
'policy-url'           => 'Project:Nahuatīltōn',
'portal'               => 'Calīxcuātl tocalpōl',
'portal-url'           => 'Project:Calīxcuātl tocalpōl',
'privacy'              => 'Tlahcuilōlli piyaliznahuatīlli',
'privacypage'          => 'Project:Tlahcuilōlpiyaliztechcopa nahuatīltōn',

'badaccess'        => 'Tlahuelītiliztechcopa ahcuallōtl',
'badaccess-group0' => 'Tehhuātl ahmo tiquichīhua inōn tiquiēlēhuia.',
'badaccess-groups' => 'Inōn tiquiēlēhuia zan quichīhuah tlatequitiltilīlli {{PLURAL:$2|oncān|oncān}}: $1.',

'ok'                      => 'Nopan iti',
'retrievedfrom'           => 'Īhuīcpa "$1"',
'youhavenewmessages'      => 'Tiquimpiya $1 ($2).',
'newmessageslink'         => 'yancuīc tlahcuilōltzintli',
'newmessagesdifflink'     => 'achto tlapatlaliztli',
'youhavenewmessagesmulti' => 'Tiquimpiya yancuīc tlahcuilōlli īpan $1',
'editsection'             => 'ticpatlāz',
'editold'                 => 'ticpatlāz',
'viewsourceold'           => 'xiquitta tlahtōlcaquiliztilōni',
'editlink'                => 'ticpatlāz',
'viewsourcelink'          => 'tiquittāz tlahtōlcaquiliztilōni',
'editsectionhint'         => 'Ticpatlacah: $1',
'toc'                     => 'Inīn tlahcuilōlco',
'showtoc'                 => 'xiquitta',
'hidetoc'                 => 'tictlātīz',
'collapsible-collapse'    => 'Motlàtìs',
'collapsible-expand'      => 'Monèxtìs',
'thisisdeleted'           => '¿Tiquittāz nozo ahticpolōz $1?',
'viewdeleted'             => '¿Tiquiēlēhuia tiquitta $1?',
'restorelink'             => '{{PLURAL:$1|cē tlapatlaliztli polotic|$1 tlapatlaliztli polotic}}',
'feedlinks'               => 'Olōlpōl:',
'site-rss-feed'           => '$1 RSS huelītiliztli',
'site-atom-feed'          => '$1 Atom huelītiliztli',
'page-rss-feed'           => '"$1" RSS huelītiliztli',
'page-atom-feed'          => '"$1" RSS huelītiliztli',
'red-link-title'          => '$1 (ayàk in tlaìxtlapalli)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tlaìxtlapalli',
'nstab-user'      => 'Tlatequitiltilīlli',
'nstab-media'     => 'Mēdiatl',
'nstab-special'   => 'Nònkuâkìskàtlaìxtlapalli',
'nstab-project'   => 'Ìtlaìxtlapal in tlayẻkàntekitl',
'nstab-image'     => 'Īxiptli',
'nstab-mediawiki' => 'Tlahcuilōltzintli',
'nstab-template'  => 'Nemachiòtl',
'nstab-help'      => 'Tèpalèwilistli',
'nstab-category'  => 'Tlaìxmatkàtlàlilòtl',

# Main script and global functions
'nosuchaction'      => 'Ahmo ia tlachīhualiztli',
'nosuchspecialpage' => 'Ahmelāhuac nōncuahquīzqui zāzanilli',
'nospecialpagetext' => '<strong>Ahmo ia nōncuahquīzqui āmatl ticnequi.</strong>

Tihuelīti tiquitta mochi nōncuahquīzqui āmatl īpan [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'               => 'Ahcuallōtl',
'databaseerror'       => 'Tlahcuilōltzintlān īahcuallo',
'laggedslavemode'     => 'Xiquitta: huel ahmo ia achi yancuīc in tlapatlaliztli inīn zāzanilco.',
'missingarticle-rev'  => '(tlachiyaliztli ītlapōhual: $1)',
'missingarticle-diff' => '(Ahneneuh.: $1, $2)',
'internalerror'       => 'Ahcuallōtl tlahtic',
'internalerror_info'  => 'Ahcuallōtl tlahtic: $1',
'filecopyerror'       => 'Ahmo ōmohuelītic tlacopīna "$1" īhuīc "$2".',
'filerenameerror'     => 'Ahmo ōmohuelītic tlazaca "$1" īhuīc "$2".',
'filedeleteerror'     => 'Ahmo ōmohuelītic tlapoloa "$1".',
'filenotfound'        => 'Ahmo ōmohuelītic tlanāmiqui "$1".',
'fileexistserror'     => 'Ahmo ōmohuelītih tlahcuiloa "$1" tlahcuilōlhuīc: tlahcuilōlli ia',
'cannotdelete'        => 'Ahmo ōhuelītic mopoloa in zāzanilli "$1".
Hueli tlein āquin ōquipolo achtopa.',
'badtitle'            => 'Ahcualli tōcāitl',
'badtitletext'        => 'Zāzanilli ticnequi in ītōca cah ahcualli, ahtlein quipiya nozo ahcualtzonhuiliztli interwiki tōcāhuicpa.
Hueliz quimpiya tlahtōl tlein ahmo mohuelītih motequitiltia tōcāpan.',
'viewsource'          => 'Tiquittāz tlahtōlcaquiliztilōni',
'viewsourcefor'       => '$1 ītechcopa',
'actionthrottled'     => 'Tlachīhualiztli ōmotzacuili',
'viewsourcetext'      => 'Tihuelīti tiquitta auh ticcopīna inīn zāzanilli ītlahtōlcaquiliztilōni:',
'sqlhidden'           => '(Tlatēmoliztli SQL omotlāti)',
'namespaceprotected'  => "Ahmo tiquihuelīti tiquimpatla zāzaniltin īpan '''$1'''.",
'ns-specialprotected' => 'Ahmohuelīti quimpatla nōncuahquīzqui zāzaniltin.',
'titleprotected'      => "Inīn zāzanilli ōmoquīxti ic tlachīhualiztli ic [[User:$1|$1]].
Ōquihto: ''$2''",

# Virus scanner
'virus-unknownscanner' => 'ahmatic antivirus:',

# Login and logout pages
'welcomecreation'         => '== ¡Ximopanōlti, $1! ==

Mocuentah ōmochīuh.

Ye tihuelīti titēchihtoa [[Special:Preferences|motlaēlēhuiliz]].',
'yourname'                => 'Motlatequitiltilīltōca:',
'yourpassword'            => 'Motlahtōlichtacāyo',
'yourpasswordagain'       => 'Motlahtōlichtacāyo occeppa',
'remembermypassword'      => 'Ticpiyāz motlacalaquiliz inīn chīuhpōhualhuazco (īxquich {{PLURAL:$1|tōnalli|tōnalli}})',
'yourdomainname'          => 'Moāxcāyō',
'login'                   => 'Ximomachiyōmaca/Ximocalaqui',
'nav-login-createaccount' => 'Ximocalaqui / ximomachiyōmaca',
'loginprompt'             => 'Tihuīquilia tiquimpiyāz cookies ic ticalaquīz {{SITENAME}}.',
'userlogin'               => 'Ximomachiyōmaca/Ximocalaqui',
'userloginnocreate'       => 'Ximocalaqui',
'logout'                  => 'Tiquīzāz',
'userlogout'              => 'Tiquīzāz',
'notloggedin'             => 'Ahmo ōtimocalac',
'nologin'                 => "¿Ahmo ticpiya cuentah? '''$1'''.",
'nologinlink'             => 'Ticchīhuāz cē cuentah',
'createaccount'           => 'Ticchīhuāz cē cuentah',
'gotaccount'              => "¿Ye ticpiya cē cuentah? '''$1'''.",
'gotaccountlink'          => 'Ximocalaqui',
'createaccountmail'       => 'e-mailcopa',
'createaccountreason'     => 'Tlèka:',
'badretype'               => 'Ahneneuhqui motlahtōlichtacāyo.',
'userexists'              => 'Ye ia in tōcāitl ōquihcuilo.
Timitztlātlauhtiah xitlahcuiloa occē.',
'loginerror'              => 'Ahcuallōtl tlacalaquiliztechcopa',
'noname'                  => 'Ahmo ōtiquihto cualli tlatequitiltilīlli tōcāitl.',
'loginsuccesstitle'       => 'Cualli calaquiliztli',
'loginsuccess'            => "'''Ōticalac {{SITENAME}} quemeh \"\$1\".'''",
'nosuchuser'              => 'Ayāc tlatequitiltilīlli motōcāitia "$1".
In tlatequitiltilīltōcāitl quimati in huēyimachiyōtlahtōliztli.
Xiquitta in yēquihcuilōlli, ahnozo [[Special:UserLogin/signup|xicchīhua yancuīc cuenta]].',
'nosuchusershort'         => 'Ayāc tlatequitiltilīlli motōcāitia "<nowiki>$1</nowiki>". Xiquitta in tlein ōtitlahcuiloh melāhuacā cah.
Xiquitta moyēquihcuilōl.',
'nouserspecified'         => 'Mohuīquilia tiquihtoa cualli tlatequitiltilīltōcāitl.',
'wrongpassword'           => 'Ahcualli motlahtōlichtacāyo.
Timitztlātlauhtia xicchīhua occeppa.',
'wrongpasswordempty'      => 'Ayāc motlahtōlichtacāyo.
Timitztlātlauhtia xicchīhua occeppa.',
'mailmypassword'          => 'E-mailīz yancuīc motlahtōlichtacāyo',
'noemail'                 => '"$1" ahmo quipiya īe-mailcān.',
'passwordsent'            => 'Ōmoihuah yancuīc motlahtōlichtacāyo īhuīc mo e-mail ("$1").
Occeppa xicalaqui niman ticmatīz.',
'mailerror'               => 'Ahcuallōtl e-mailcopa: $1',
'emailconfirmlink'        => 'Ticchicāhua mo e-mail',
'accountcreated'          => 'Cuentah ōmochīuh',
'accountcreatedtext'      => 'Tlatequitiltilīlcuentah ic $1 ōmochīuh.',
'createaccount-title'     => 'Cuentah ītlachīhualiz ic {{SITENAME}}',
'loginlanguagelabel'      => 'Tlahtōlli: $1',

# JavaScript password checks
'password-strength-bad'        => 'AHCUALLI',
'password-strength-mediocre'   => 'achi cualli',
'password-strength-acceptable' => 'Tlaceliāni',
'password-strength-good'       => 'cualli',

# Password reset dialog
'resetpass'                 => 'Ticpatlāz motlahtōlichtacāyo',
'resetpass_header'          => 'Xicpatlāz motlahtōlichtacāyo',
'oldpassword'               => 'Huēhueh motlahtōlichtacayo:',
'newpassword'               => 'Yancuīc motlahtōlichtacayo:',
'retypenew'                 => 'Occeppa xiquihcuiloa yancuīc motlahtōlichtacayo:',
'resetpass_submit'          => 'Xicpatlāz motlahtōlichtacāyo auh xicalaquīz',
'resetpass_success'         => '¡Cualli ōmopatlac motlahtōlichtacāyo! Āxcān ticalaquicah...',
'resetpass_forbidden'       => 'Tlahtōlichtacayōtl ahmo mohuelītih mopatlah',
'resetpass-submit-loggedin' => 'Ticpatlāz motlahtōlichtacāyo',
'resetpass-submit-cancel'   => 'Ticcuepāz',

# Edit page toolbar
'bold_sample'     => 'Tlīltic tlahcuilōlli',
'bold_tip'        => 'Tlīltic tlahcuilōlli',
'italic_sample'   => 'Italic tlahcuilōlli',
'italic_tip'      => 'Italic tlahcuilōlli',
'link_sample'     => 'Tzonhuiliztli ītōcā',
'link_tip'        => 'Tzonhuiliztli tlahtic',
'extlink_sample'  => 'http://www.example.com Tōcāitl',
'extlink_tip'     => 'Tzonhuilizcallān (xitequitiltia http://)',
'headline_sample' => 'Cuātlahcuilōlli',
'headline_tip'    => 'Iuhcāyōtl 2 tōcāyōtl',
'math_sample'     => 'Xihcuiloa nicān',
'math_tip'        => 'Tlapōhualmatiliztlahtōl (LaTeX)',
'image_sample'    => 'Machiyōtl.jpg',
'media_sample'    => 'Machiyōtl.ogg',
'media_tip'       => 'Mēdiahuīc tzonhuiliztli',
'sig_tip'         => 'Motōcā īca cāhuitl',
'hr_tip'          => 'Pāntli',

# Edit pages
'summary'                          => 'Mopatlaliz:',
'subject'                          => 'Tōcāitl/Āmoxmachiyōtl:',
'minoredit'                        => 'Inīn cah tlapatlalitzintli',
'watchthis'                        => 'Tictlachiyāz inīn zāzanilli',
'savearticle'                      => 'Ticpiyāz',
'preview'                          => 'Xiquitta achtochīhualiztli',
'showpreview'                      => 'Xiquitta achtochīhualiztli',
'showlivepreview'                  => 'Niman achtochīhualiztli',
'showdiff'                         => 'Tiquinttāz tlapatlaliztli',
'missingcommenttext'               => 'Timitztlātlauhtiah xitlanitlahcuiloa.',
'summary-preview'                  => 'Tlahcuilōltōn achtochīhualiztli:',
'blockedtitle'                     => 'Ōmotzacuili tlatequitiltilīlli',
'blockednoreason'                  => 'ahmo cah īxtlamatiliztli',
'blockedoriginalsource'            => "Nicān motta '''$1''' ītlahtōlcaquiliztilōni:",
'blockededitsource'                => "'''Mopatlaliz''' ītlahtōl īpan '''$1''' motta nicān:",
'whitelistedittitle'               => 'Tihuīquilia timocalaqui ic patla',
'whitelistedittext'                => 'Tihuīquilia $1 ic ticpatla zāzaniltin.',
'nosuchsectiontitle'               => 'In xeliuhcāyōtl ahmo ōquināmic',
'loginreqtitle'                    => 'Ximocalaqui',
'loginreqlink'                     => 'ximocalaqui',
'loginreqpagetext'                 => 'Tihuīquilia $1 ic tiquintta occequīntīn zāzaniltin.',
'accmailtitle'                     => 'Tlahtōlichtacāyōtl ōmoihuah.',
'accmailtext'                      => "Cē zāzotlahtōlichtacāyōtl ōcyōcox [[User talk:$1|$1]]  moquitītlani īhuīc $2.

In tlahtōlichtacāyōtl īpal inīn yancuīc cuenta hueliti ticpatlalo zāzanilpan ''[[Special:ChangePassword|tlahtōlichtacāyōtl patlaliztli]]'' zatepan ōticalac.",
'newarticle'                       => '(Yancuīc)',
'newarticletext'                   => 'Ōtictocac cētiliztli cē zāzanilhuīc oc ahmo ia. Intlā quiēlēhuia quichīhua, xitlahcuiloa niman (nō xiquitta [[{{MediaWiki:Helppage}}|tēpalēhuiliztli zāzanilli]] huehca ōmpa tlapatlaliztli). Intlā ahmo, yāuh achtopa zāzanilli.',
'noarticletext'                    => 'In āxcān, ahmō onca tlahcuilōlli inīn zāzanilpan.
Tihuelīti [[Special:Search/{{PAGENAME}}|tictēmoa inīn zāzaniltōcācopa]] occequīntīn zāzanilpan,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} machiyōmacalpan], ahnozo [{{fullurl:{{FULLPAGENAME}}|action=edit}} ticpatla inīn zāzanilli]</span>.',
'userpage-userdoesnotexist'        => 'Ahmo ia cuentah "$1" ītōca. Timitztlātlauhtiah xitēchquinōtza intlā ticchīhuāz intlā nozo ticpatlāz inīn zāzanilli.',
'usercsspreview'                   => "'''Ca inīn moachtochīhualiz ītechcopa moCSS.'''
'''¡Ahmo ōmochīuh nozan!'''",
'userjspreview'                    => "'''Ca inīn moachtochīhualiz ītechcopa moJavaScript.'''
'''¡Ahmo ōmochīuh nozan!'''",
'updated'                          => '(Ōmoyancuīli)',
'note'                             => "'''Tlahtōlcaquiliztilōni:'''",
'previewnote'                      => "'''¡Ca inīn moachtochīhualiz, auh mopatlaliz ahmō cateh ōmochīhuah nozan!'''",
'editing'                          => 'Ticpatlacah $1',
'editingsection'                   => 'Ticpatlacah $1 (tlahtōltzintli)',
'editingcomment'                   => 'Ticpatlacah $1 (tlahtōltzintli)',
'editconflict'                     => 'Tlapatlaliztli yāōyōtōn: $1',
'yourtext'                         => 'Motlahcuilōl',
'yourdiff'                         => 'Ahneneuhquiliztli',
'copyrightwarning'                 => "<small>Timitztlātlauhtiah xiquitta mochi mopatlaliz cana {{SITENAME}} tlatzayāna īpan $2 (huēhca ōmpa xiquitta $1). Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa; intlā ahmō ticnequi, zātēpan ahmō titlahcuilōz nicān. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli. '''¡AHMŌ XITĒQUITILTIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!'''</small>",
'copyrightwarning2'                => "<small>Āqueh tlācah quipatlazqueh in motlahcuilōl auh tlatzayāna occeppa; intlā ahmō ticnequi, zātēpan ahmō titlahcuilōz nicān {{SITENAME}}. Nō mitzihtoah in ōtitlahcuiloh ahmō quipiya in copyright nozo in yōllōxoxouhqui tlahcuilōlli (huēhca ōmpa xiquitta $1). '''¡AHMŌ TIQUINTEQUITILTIA AHYŌLLŌXOXOUHQUI TLAHCUILŌLLI!'''</small>",
'longpageerror'                    => "'''AHCUALLŌTL: Motlahcuilōl cah huēiyac $1 KB, huehca ōmpa $2 KB. Ahmo mopiyāz.'''",
'templatesused'                    => '{{PLURAL:$1|Nemachiòtl tlèn motekìuhtia|Nemachiòmë tlèn mokìntekìuhtiä}} ìpan inìn tlaìxtlapalli:',
'templatesusedpreview'             => '{{PLURAL:$1|Nemachiòtl tlèn motekìuhtia|Nemachiòmë tlèn mokìntekìuhtiä}} ìpan inìn achtochìwalistli:',
'templatesusedsection'             => '{{PLURAL:$1|Nemachiòtl tlèn motekìuhtia|Nemachiòmë tlèn mokìntekìuhtiä}} ìpan inìn tlaxélòlistli:',
'template-protected'               => '(ōmoquīxti)',
'hiddencategories'                 => 'Inīn zāzanilli mopiya {{PLURAL:$1|1 neneuhcāyōc ōmotlāti|$1 neneuhcāyōc ōmotlāti}}:',
'nocreatetext'                     => 'Inīn huiqui ōquitzacuili tlahuelītiliztli ic tlachīhua yancuīc zāzaniltin. Tichuelīti ticcuepa auh ticpatla cē zāzanilli, [[Special:UserLogin|xicalaqui nozo xicchīhua cē cuentah]].',
'nocreate-loggedin'                => 'Ahmo tihuelīti tiquinchīhua yancuīc zāzaniltin.',
'permissionserrors'                => 'Huelītiliztechcopa ahcuallōtl',
'permissionserrorstext'            => 'Ahmo tihuelīti quichīhua inōn, inīn {{PLURAL:$1|īxtlamatilizpampa|īxtlamatilizpampa}}:',
'permissionserrorstext-withaction' => 'Ahmo tiquihuelīti $2 inīn {{PLURAL:$1|īxtlamatilizpampa|īxtlamatilizpampa}}:',
'moveddeleted-notice'              => 'Inīn zāzanilli ōmopolo.
In tlapololiztli īhuān in tlazacaliztli tlahcuilōlloh cah tlani.',
'edit-gone-missing'                => 'Ahmo huelīti yancuīya zāzanilli.
Hueliz ōmopolo.',
'edit-conflict'                    => 'Tlapatlaliztli yāōyōtōn',
'edit-already-exists'              => 'Ahmo mohuelīti mochīhua yancuīc zāzanilli.
Ye ia.',

# Account creation failure
'cantcreateaccounttitle' => 'Ahmo huelītih mochīhua cuentah',
'cantcreateaccount-text' => "[[User:$3|$3]] ōcquīxti cuentah tlachīhualiztli īpal inīn IP ('''$1''').

Īxtlamatiliztli īpal $3 cah ''$2''",

# History pages
'viewpagelogs'           => 'Tiquinttāz tlahcuilōlloh inīn zāzaniltechcopa',
'nohistory'              => 'Ahmo cah tlapatlaliztechcopa tlahcuilōlloh inīn zāzaniltechcopa.',
'currentrev'             => 'Āxcān tlapatlaliztli',
'currentrev-asof'        => 'Āxcān tlachiyaliztli īpan $1',
'revisionasof'           => 'Tlachiyaliztli īpan $1',
'revision-info'          => 'Tlachiyaliztli īpan $1; $2',
'previousrevision'       => '← Huēhueh tlapatlaliztli',
'nextrevision'           => 'Yancuīc tlapatlaliztli →',
'currentrevisionlink'    => 'Āxcān tlapatlaliztli',
'cur'                    => 'āxcān',
'next'                   => 'niman',
'last'                   => 'xōcoyōc',
'page_first'             => 'achto',
'page_last'              => 'xōcoyōc',
'history-fieldset-title' => 'Tictēmōz īpan tlahcuilōlloh',
'histfirst'              => 'Achto',
'histlast'               => 'Yancuīc',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(iztāc)',

# Revision feed
'history-feed-title'          => 'Tlachiyaliztli tlahcuilōlloh',
'history-feed-description'    => 'Tlachiyaliztli tlahcuilōlloh inīn zāzaniltechcopa huiquipan',
'history-feed-item-nocomment' => '$1 īpan $2',
'history-feed-empty'          => 'In zāzanilli tiquiēlēhuia ahmo ia.
Hueliz ōmopolo huiqui nozo ōmozacac.
[[Special:Search|Xitēmoa huiquipan]] yancuīc huēyi zāzaniltin.',

# Revision deletion
'rev-delundel'              => 'tiquittāz/tictlātīz',
'revisiondelete'            => 'Tiquimpolōz/ahtiquimpolōz tlachiyaliztli',
'revdelete-selected'        => "'''{{PLURAL:$2|Tlachiyaliztli ōmoēlēhui|Tlachiyaliztli ōmoēlēhuih}} [[:$1]] ītechcopa:'''",
'revdelete-hide-text'       => 'Tictlātīz tlachiyaliztli ītlahcuilōl',
'revdelete-hide-image'      => 'Tictlātīz tlahcuilōlli ītlapiyaliz',
'revdelete-radio-set'       => 'Quēmah',
'revdelete-radio-unset'     => 'Ahmo',
'revdel-restore'            => 'Ticpatlāz tlattaliztli',
'pagehist'                  => 'Zāzanilli tlahcuilōlloh',
'deletedhist'               => 'Ōtlapolo tlahcuilōlloh',
'revdelete-content'         => 'tlapiyaliztli',
'revdelete-summary'         => 'ticpatlāz tlahcuilōltōn',
'revdelete-uname'           => 'tlatēquitiltilīltōcāitl',
'revdelete-hid'             => 'xictlātia $1',
'revdelete-unhid'           => 'tiquittāz $1',
'revdelete-log-message'     => '$1 ic $2 {{PLURAL:$2|tlachiyaliztli|tlachiyaliztli}}',
'logdelete-log-message'     => '$1 īhuīc $2 {{PLURAL:$2|tlachīhualiztli|tlachīhualiztli}}',
'revdelete-edit-reasonlist' => 'Tiquimpatlāz īxtlamatiliztli tlapoloaliztechcopa',

# History merging
'mergehistory-from'           => 'Zāzanilhuīcpa:',
'mergehistory-into'           => 'Zāzanilhuīc:',
'mergehistory-no-source'      => 'Zāzanilhuīcpa $1 ahmo ia.',
'mergehistory-no-destination' => 'Zāzanilhuīc $1 ahmo ia.',
'mergehistory-autocomment'    => 'Ōmocēntili [[:$1]] īpan [[:$2]]',
'mergehistory-comment'        => 'Ōmocēntili [[:$1]] īpan [[:$2]]: $3',

# Merge log
'revertmerge' => 'Tiquīxipehuaz',

# Diffs
'history-title' => '"$1" ītlachiyaliz tlahcuilōlloh',
'difference'    => '(Ahneneuhquiliztli tlapatlaliznepantlah)',
'lineno'        => 'Pāntli $1:',
'editundo'      => 'Tichuelōz',
'diff-multi'    => '({{PLURAL:$1|Cē tlapatlaliztli nepantlah ahmo motta in ōquichīuh|$1 Tlapatlaliztli nepantlah ahmo mottah in ōquinchīuh}}  {{PLURAL:$2|cē tlatequitiltilīlli|$2 tlatequitiltilīltin}})',

# Search results
'searchresults'                    => 'Tlatēmoliztli',
'searchsubtitle'                   => 'Ōtictēmōz \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|mochīntīn zāzaniltin mopēhua īca "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|mochīntīn zāzaniltin tzonhuilia "$1" īhuīc]])',
'searchsubtitleinvalid'            => "Ōtictēmo '''$1'''",
'prevn'                            => '{{PLURAL:$1|$1}} achtopa',
'nextn'                            => 'niman {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Xiquintta ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-exists'                => "'''Ye ia zāzanilli ītōca \"[[\$1]]\" inīn huiquipan'''",
'searchmenu-new'                   => "'''Tihuelīti ticchīhuāz zāzanilli ītōca \"[[:\$1]]\" inīn huiquipan'''",
'searchhelp-url'                   => 'Help:Tlapiyaliztli',
'searchprofile-articles'           => 'Tlapiyaliztli zāzanilli',
'searchprofile-project'            => 'Tēpalēhuiliztli īhuān īxiptlahtli āmatl',
'searchprofile-images'             => 'Nepapan media',
'searchprofile-everything'         => 'Mochi',
'searchprofile-advanced'           => 'Huehca ōmpa',
'searchprofile-articles-tooltip'   => 'Tictēmōz īpan $1',
'searchprofile-project-tooltip'    => 'Tictēmōz īpan $1',
'searchprofile-images-tooltip'     => 'Tiquintēmōz tlahcuilōlli',
'searchprofile-everything-tooltip' => 'Tictēmōz mochi tlapiyalizpan (mopiyah tēixnāmiquiliztli zāzanilli)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 tlahtōl|$2 tlahtōltin}})',
'search-redirect'                  => '(tlacuepaliztli $1)',
'search-section'                   => '(tlahtōltzintli $1)',
'search-suggest'                   => 'Mohtoa ahnozo: $1',
'search-interwiki-caption'         => 'Tlachīhualiztli īcnīhuān',
'search-interwiki-more'            => '(huehca ōmpa)',
'search-relatedarticle'            => 'Ītechcopa',
'searchrelated'                    => 'ītechcopa',
'searchall'                        => 'mochīntīn',
'powersearch'                      => 'Chicāhuac tlatēmoliztli',
'powersearch-legend'               => 'Chicāhuac tlatēmoliztli',
'powersearch-ns'                   => 'Tlatēmōz tōcātzimpan:',
'powersearch-redir'                => 'Quimpiya tlacuepaliztli',
'powersearch-field'                => 'Tlatēmōz',
'powersearch-toggleall'            => 'Mochi',
'powersearch-togglenone'           => 'Ahtlein',
'search-external'                  => 'Tlatēmotiliztli calāmpa',

# Quickbar
'qbsettings-none' => 'Ahtlein',

# Preferences page
'preferences'               => 'Tlaēlēhuiliztli',
'mypreferences'             => 'Notlaēlēhuiliz',
'prefs-edits'               => 'Tlapatlaliztli tlapōhualli:',
'prefsnologin'              => 'Ahmo ōtimocalac',
'changepassword'            => 'Ticpatlāz motlahtōlichtacāyo',
'skin-preview'              => 'Xiquitta quemeh yez',
'prefs-math'                => 'Tlapōhualmatiliztli',
'datedefault'               => 'Ayāc tlanequiliztli',
'prefs-datetime'            => 'Cāuhtiliztli īhuān cāhuitl',
'prefs-personal'            => 'Motlācatlanōnōtzaliz',
'prefs-rc'                  => 'Yancuīc tlapatlaliztli',
'prefs-watchlist'           => 'Tlachiyaliztli',
'prefs-watchlist-days'      => 'Tōnaltin tiquinttāz tlachiyalizpan:',
'prefs-watchlist-edits'     => 'Tlapatlaliztli tiquintta tlachiyalizpan:',
'prefs-misc'                => 'Zāzo',
'prefs-resetpass'           => 'Ticpatlāz motlahtōlichtacāyo',
'saveprefs'                 => 'Ticpiyāz',
'prefs-editing'             => 'Tlapatlaliztli',
'rows'                      => 'Pāntli:',
'searchresultshead'         => 'Tlatēmoliztli',
'contextlines'              => 'Pāntli tlahtōltechcopa:',
'contextchars'              => 'Tlahtōltechcopa ic pāntli:',
'recentchangesdays'         => 'Tōnaltin tiquinttāz yancuīc tlapatlalizpan:',
'localtime'                 => 'Cāhuitl nicān:',
'timezoneregion-africa'     => 'Africa',
'timezoneregion-america'    => 'Ixachitlān',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic'     => 'Ártico',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Atlántico Ilhuicaātl',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europan',
'timezoneregion-indian'     => 'Índico Ilhuicaātl',
'timezoneregion-pacific'    => 'Pacífico Ilhuicaātl',
'prefs-searchoptions'       => 'Tlatēmoliztli tlaēlēhuiliztli',
'prefs-namespaces'          => 'Tōcātzin',
'defaultns'                 => 'Tlatēmōz inīn tōcātzimpan achtopa:',
'default'                   => 'ic default',
'prefs-files'               => 'Tlahcuilōlli',
'youremail'                 => 'E-mail:',
'username'                  => 'Tlatequitiltilīltōcāitl:',
'uid'                       => 'Tlatequitiltilīlli ID:',
'prefs-memberingroups'      => 'Tlācatl {{PLURAL:$1|olōlco|olōlco}}:',
'yourrealname'              => 'Melāhuac motōcā:',
'yourlanguage'              => 'Motlahtōl:',
'yournick'                  => 'Motōcātlaliz:',
'badsiglength'              => 'Motōcātlaliz cah ocachi huēyac.
Ahmo quihuīquilia quimpiya achi $1 {{PLURAL:$1|machiyōtlahtōliztli|machiyōtlahtōliztli}}.',
'email'                     => 'E-mail',
'prefs-help-realname'       => 'Melāhuac motōca.
Intlā ticnequi, tlācah quimatīzqueh motequi.',
'prefs-help-email-required' => 'Tihuīquilia quihcuiloa mo e-mailcān.',

# User rights
'userrights-user-editname' => 'Xihcuiloa cē tlatequitiltilīltōcāitl:',
'editusergroup'            => 'Tiquimpatlāz tlatequitiltilīlli olōlli',
'userrights-editusergroup' => 'Tiquimpatlāz tlatequitiltilīlli olōlli',
'saveusergroups'           => 'Tiquimpiyāz tlatequitiltilīlli olōlli',
'userrights-groupsmember'  => 'Olōlco:',
'userrights-reason'        => 'Īxtlamatiliztli:',
'userrights-no-interwiki'  => 'Ahmo tihuelīti ticpatla tlatequitiltilīlli huelītiliztli occequīntīn huiquipan.',

# Groups
'group'       => 'Olōlli:',
'group-user'  => 'Tlatequitiltilīlli',
'group-bot'   => 'Tepoztlācah',
'group-sysop' => 'Tlahcuilōlpixqueh',
'group-all'   => '(mochīntīn)',

'group-user-member'  => 'Tlatequitiltilīlli',
'group-bot-member'   => 'Tepoztlācatl',
'group-sysop-member' => 'Tlahcuilōlpixqui',

'grouppage-user'  => '{{ns:project}}:Tlatequitiltilīlli',
'grouppage-bot'   => '{{ns:project}}:Tepoztlācah',
'grouppage-sysop' => '{{ns:project}}:Tlahcuilōlpixqueh',

# Rights
'right-read'                 => 'Tiquimpōhuāz zāzaniltin',
'right-edit'                 => 'Tiquimpatlāz zāzaniltin',
'right-createpage'           => 'Ticchīhuāz zāzaniltin (ahmo tēixnāmiquiliztli zāzaniltin)',
'right-createtalk'           => 'Ticchīhuāz tēixnāmiquiliztli zāzaniltin',
'right-createaccount'        => 'Tiquinchīhuāz yancuīc cuentah',
'right-minoredit'            => 'Ticpatlāz quemeh tlapatlalitzintli',
'right-move'                 => 'Tiquinzacāz zāzaniltin',
'right-move-subpages'        => 'Tiquinzacāz zāzaniltin auh īzāzaniltōn',
'right-suppressredirect'     => 'Ahmo ticchīhuāz tlacuepaliztli huēhueh tōcāhuīc ihcuāc ticzacāz cē zāzanilli',
'right-upload'               => 'Tiquinquetzāz tlahcuilōlli',
'right-upload_by_url'        => 'Ticquetzāz cē tlahcuilōlli īhuīcpa URL',
'right-delete'               => 'Tiquimpolōz zāzaniltin',
'right-bigdelete'            => 'Tiquimpolōz zāzaniltin īca huēiyac tlahcuilōlloh',
'right-browsearchive'        => 'Tlatēmōz zāzaniltin ōmopoloh',
'right-undelete'             => 'Ahticpolōz cē zāzanilli',
'right-suppressionlog'       => 'Tiquinttāz ichtatlahcuilōlloh',
'right-block'                => 'Tiquintzacuilīz occequīntīn tlatequitiltilīlli',
'right-blockemail'           => 'Titēquīxtīz tlatequitiltilīlli ic tēch-e-mailīz',
'right-hideuser'             => 'Ticquīxtīz cē tlatequitiltilīltōcāitl, āuh ichtac',
'right-import'               => 'Ticcōhuāz zāzaniltin occequīntīn huiquihuīcpa',
'right-importupload'         => 'Tiquincōhuāz zāzaniltin tlahcuilōlquetzalizhuīcpa',
'right-patrolmarks'          => 'Tiquinttāz tlapiyalizmachiyōtl īpan yancuīc tlapatlaliztli',
'right-unwatchedpages'       => 'Tiquinttāz mochi zāzanilli tlein ahmo mochiya',
'right-userrights'           => 'Tiquimpatlāz mochīntīn tlatequitiltilīlli huelītiliztli',
'right-userrights-interwiki' => 'Tiquimpatlāz tlatequitiltilīlli huelītiliztli occequīntīn huiquipan',

# User rights log
'rightslog'  => 'Tlatequitiltilīlli huelītiliztli tlahcuilōlloh',
'rightsnone' => 'ahtlein',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'ticpōhuāz inīn zāzanilli',
'action-edit'           => 'ticpatlāz inīn zāzanilli',
'action-createpage'     => 'tiquinchīhuāz zāzaniltin',
'action-createtalk'     => 'tiquinchīhuāz tēixnāmiquiliztli zāzaniltin',
'action-createaccount'  => 'ticchīhuaz inīn tlatequitiltilīlli īcuentah',
'action-move'           => 'ticpatlāz inīn zāzanilli',
'action-move-subpages'  => 'tiquimpatlāz inīn zāzanilli īhuān zāzaniltōn',
'action-upload'         => 'ticquetzāz inīn tlahcuilōlli',
'action-writeapi'       => 'tictequitiltīz API tlahcuilōliztli',
'action-delete'         => 'ticpolōz inīn zāzanilli',
'action-deleterevision' => 'ticpolōz inīn tlachiyaliztli',
'action-deletedhistory' => 'tiquittāz inīn zāzanilli ītlahcuilōlloh tlein ōmopolo',
'action-browsearchive'  => 'tiquintēmōz zāzanilli tlein ōmopoloh',
'action-undelete'       => 'ahticpolōz inīn zāzanilli',
'action-suppressionlog' => 'tiquittāz inīn ichtac tlahcuilōlloh',
'action-block'          => 'tiquitzacuilīz inīn tlatequitiltilīlli',
'action-userrights'     => 'tiquimpatlāz mochi tlatequitiltilīlli huelītiliztli',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|tlapatlaliztli|tlapatlaliztli}}',
'recentchanges'                     => 'Yancuīc tlapatlaliztli',
'recentchanges-legend'              => 'Yancuīc tlapatlaliztechcopa tlanequiliztli',
'recentchangestext'                 => 'Xiquinttāz in achi yancuīc ahmo occequīntīn tlapatlaliztli huiquipan inīn zāzanilpan.',
'rcnote'                            => "Nicān {{PLURAL:$1|cah '''1''' tlapatlaliaztli|cateh in xōcoyōc '''$1''' tlapatlaliztli}} īpan xōcoyōc {{PLURAL:$2|tōnalli|'''$2''' tōnaltin}} īhuīcpa $5, $4.",
'rclistfrom'                        => 'Xiquinttāz yancuīc tlapatlaliztli īhuīcpa $1',
'rcshowhideminor'                   => '$1 tlapatlalitzintli',
'rcshowhidebots'                    => '$1 tepoztlācah',
'rcshowhideliu'                     => '$1 tlatequitiltilīlli ōmocalacqueh',
'rcshowhideanons'                   => '$1 ahtōcā tlatequitiltilīlli',
'rcshowhidepatr'                    => '$1 tlapatlaliztli mochiyahua',
'rcshowhidemine'                    => '$1 notlahcuilōl',
'rclinks'                           => 'Xiquintta xōcoyōc $1 tlapatlaliztli xōcoyōc $2 tōnalpan.<br />$3',
'diff'                              => 'ahneneuh',
'hist'                              => 'tlahcuil',
'hide'                              => 'Tiquintlātīz',
'show'                              => 'Tiquinttāz',
'minoreditletter'                   => 'p',
'newpageletter'                     => 'Y',
'boteditletter'                     => 'T',
'number_of_watching_users_pageview' => '[$1 tlatequitiltilīlli {{PLURAL:$1|tlachiya|tlachiyah}}]',
'rc_categories_any'                 => 'Zāzo',
'newsectionsummary'                 => 'Yancuīc tlahtōltzintli: /* $1 */',

# Recent changes linked
'recentchangeslinked'         => 'Tlapatlaliztli tzonhuilizpan',
'recentchangeslinked-feed'    => 'Tlapatlaliztli tzonhuilizpan',
'recentchangeslinked-toolbox' => 'Tlapatlaliztli tzonhuilizpan',
'recentchangeslinked-title'   => 'Tlapatlaliztli "$1" ītechcopa',
'recentchangeslinked-page'    => 'Zāzanilli ītōcā:',

# Upload
'upload'                 => 'Tlahcuilōlquetza',
'uploadbtn'              => 'Tlahcuilōlquetza',
'uploadnologin'          => 'Ahmo ōtimocalac',
'uploaderror'            => 'Tlaquetzaliztli ahcuallōtl',
'uploadlog'              => 'tlaquetzaliztli tlahcuilōlloh',
'uploadlogpage'          => 'Tlaquetzaliztli tlahcuilōlloh',
'filename'               => 'Tlahcuilōlli ītōcā',
'filedesc'               => 'Tlahcuilōltōn',
'fileuploadsummary'      => 'Tlahcuilōltōn:',
'filestatus'             => 'Copyright:',
'filesource'             => 'Īhuīcpa:',
'uploadedfiles'          => 'Tlahcuilōlli ōmoquetz',
'minlength1'             => 'Tlahcuilōltōcāitl quihuīlquilia huehca ōmpa cē tlahtōl.',
'badfilename'            => 'Īxiptli ītōcā ōmopatlac īhuīc "$1".',
'filetype-unwanted-type' => "'''\".\$1\"''' ahmo moēlēhuia quemeh tlahcuilōlli iuhcāyōtl.
Tlahcuilōlli iuhcāyōtl {{PLURAL:\$3|moēlēhuia cah|moēlēhuiah cateh}} \$2.",
'filetype-missing'       => 'Tlahcuilōlli ahmo quipiya huēiyaquiliztli (quemeh ".jpg").',
'large-file'             => 'Mā tlahcuilōlli ahmo achi huēiyac $1; inīn cah $2.',
'fileexists-extension'   => "Tlahcuilōlli zan iuh tōcātica ia: [[$2|thumb]]
* Tlahcuilōlli moquetzacah: '''<tt>[[:$1]]</tt>'''
* Tlahcuilōlli tlein ia ītōca: '''<tt>[[:$2]]</tt>'''
Timitztlātlauhtiah, xitlahcuiloa occē tōcāitl.",
'savefile'               => 'Quipiyāz tlahcuilōlli',
'uploadedimage'          => 'ōmoquetz "[[$1]]"',
'overwroteimage'         => 'ōmoquetz yancuīc "[[$1]]" iuhcāyōtl',
'uploaddisabled'         => 'Ahmo mohuelīti tlahcuilōlquetzā',
'uploaddisabledtext'     => 'Ahmo huelīti moquetzazqueh tlahcuilōlli.',
'sourcefilename'         => 'Tōcāhuīcpa:',
'destfilename'           => 'Tōcāhuīc:',
'watchthisupload'        => 'Tictlachiyāz inīn zāzanilli',
'upload-success-subj'    => 'Cualli quetzaliztli',

'upload_source_file' => ' (cē tlahcuilōlli mochīuhpōhualhuazco)',

# Special:ListFiles
'listfiles_search_for' => 'Tlatēmōz mēdiatl tōcācopa:',
'imgfile'              => 'īxiptli',
'listfiles'            => 'Mochīntīn īxiptli',
'listfiles_name'       => 'Tōcāitl',
'listfiles_user'       => 'Tlatequitiltilīlli',
'listfiles_size'       => 'Octacayōtl (bytes)',

# File description page
'file-anchor-link'          => 'Īxiptli',
'filehist'                  => 'Tlahcuilōlli tlahcuilōlloh',
'filehist-deleteall'        => 'tiquimpolōz mochīntīn',
'filehist-deleteone'        => 'ticpolōz',
'filehist-revert'           => 'tlacuepāz',
'filehist-current'          => 'āxcān',
'filehist-datetime'         => 'Tlapōhualpan/Cāhuitl',
'filehist-thumb'            => 'Īxiptlahtōn',
'filehist-user'             => 'Tlatequitiltilīlli',
'filehist-dimensions'       => 'Octacayōtl',
'filehist-comment'          => 'TlahtōIcaquiliztīlōni',
'imagelinks'                => 'Īxiphuīc tzonhuiliztli',
'linkstoimage'              => 'Inīn {{PLURAL:$1|zāzanilli tzonhuilia|$1 zāzaniltin tzonhuiliah}} inīn tlahcuilōlhuīc:',
'nolinkstoimage'            => 'Ahmo cateh zāzaniltin tlein tzonhuiliah inīn tlahcuilōlhuīc.',
'morelinkstoimage'          => 'Tiquinttāz [[Special:WhatLinksHere/$1|achi tzonhuiliztli]] inīn tlahcuilōlhuīc.',
'redirectstofile'           => 'Inōn {{PLURAL:$1|tlahcuilōlli mocuepa|$1 tlahcuilōlli mocuepah}} nicān:',
'duplicatesoffile'          => 'Inōn {{PLURAL:$1|tlahcuilōlli cah|$1 tlahcuilōlli cateh}} ōntiah inīn zāzanilli ([[Special:FileDuplicateSearch/$2|ocahci]]):',
'sharedupload'              => 'Inīn $1 zāzanilli huelīti motequitiltia zāzocāmpa.',
'uploadnewversion-linktext' => 'Ticquetzāz yancuīc tlahcuilōlli',

# File reversion
'filerevert'         => 'Ticcuepāz $1',
'filerevert-legend'  => 'Tlahcuilōlli tlacuepaliztli',
'filerevert-comment' => 'Tlèka:',
'filerevert-submit'  => 'Tlacuepāz',

# File deletion
'filedelete'                  => 'Ticpolōz $1',
'filedelete-legend'           => 'Ticpolōz tlahcuilōlli',
'filedelete-comment'          => 'Īxtlamatiliztli:',
'filedelete-submit'           => 'Ticpolōz',
'filedelete-success'          => "Ōmopolo '''$1'''.",
'filedelete-nofile'           => "'''$1''' ahmo ia.",
'filedelete-otherreason'      => 'Occē īxtlamatiliztli:',
'filedelete-reason-otherlist' => 'Occē īxtlamatiliztli',
'filedelete-edit-reasonlist'  => 'Tiquimpatlāz īxtlamatiliztli tlapoloaliztechcopa',

# MIME search
'mimesearch' => 'MIME tlatēmoliztli',
'mimetype'   => 'MIME iuhcāyōtl:',
'download'   => 'tictemōz',

# Unwatched pages
'unwatchedpages' => 'Zāzaniltin ahmo motlachiya',

# List redirects
'listredirects' => 'Tlacuepaliztli',

# Unused templates
'unusedtemplates'    => 'Nemachiyōtīlli ahmotequitiltiah',
'unusedtemplateswlh' => 'occequīntīn tzonhuiliztli',

# Random page
'randompage'         => 'Zāzozāzanilli',
'randompage-nopages' => 'Ahmo oncah zāzanilli īpan inīn {{PLURAL:$2|tōcāitl|tōcāitl}}: $1.',

# Random redirect
'randomredirect' => 'Zāzotlacuepaliztli',

# Statistics
'statistics'               => 'Tlapōhualiztli',
'statistics-header-pages'  => 'Zāzaniltin tlapōhualli',
'statistics-header-edits'  => 'Tlapatlaliztli tlapōhualli',
'statistics-header-views'  => 'Tlahpololiztli tlapōhualli',
'statistics-header-users'  => 'Tlatequitiltilīlli ītlapōhualiz',
'statistics-articles'      => 'Tlapiyaliztli zāzanilli',
'statistics-pages'         => 'Zāzaniltin',
'statistics-pages-desc'    => 'Mochīntīn zāzaniltin huiquipan, mopiyah tēixnāmiquiliztli, tlacuepaliztli, etz.',
'statistics-files'         => 'Tlahcuilōlli ōmoquetz',
'statistics-views-peredit' => 'Tlahpololiztli ic tlapatlaliztli',

'disambiguations' => 'Ōmetōcāitl zāzaniltin',

'doubleredirects' => 'Ōntetl tlacuepaliztli',

'brokenredirects'        => 'Tzomoc tlacuepaliztli',
'brokenredirects-edit'   => 'ticpatlāz',
'brokenredirects-delete' => 'ticpolōz',

'withoutinterwiki'        => 'Zāzaniltin ahtle tzonhuiliztli',
'withoutinterwiki-submit' => 'Tiquittāz',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|tlaìxmatkàtlàlilòtl|tlaìxmatkàtlàlilòmë}}',
'nlinks'                  => '$1 {{PLURAL:$1|tzòwilistli|tzòwilistìn}}',
'nmembers'                => '$1 {{PLURAL:$1|tlâkuilòpilli|tlâkuilòpiltìn}}',
'nrevisions'              => '$1 {{PLURAL:$1|tlapiyaliztli|tlapiyaliztli}}',
'nimagelinks'             => 'Motekìuhtia ìpan $1 {{PLURAL:$1|tlaìxtlapalli|tlaìxtlapaltìn}}',
'ntransclusions'          => 'motekìuhtia ìpan $1 {{PLURAL:$1|tlaìxtlapalli|tlaìxtlapaltìn}}',
'uncategorizedpages'      => 'Zāzaniltin ahmoneneuhcāyōtiah',
'uncategorizedcategories' => 'Tlaìxmatkàtlàlilòmë âmò tlatlaìxmatkàtlàlìltìn',
'uncategorizedimages'     => 'Ìxiptìn âmò tlatlaìxmatkàtlàlìltìn',
'uncategorizedtemplates'  => 'Nemachiòmë âmò tlatlaìxmatkàtlàlìltìn',
'unusedcategories'        => 'Tlaìxmatkàtlàlilòmë tlèn âmò mokìntekìuhtia',
'unusedimages'            => 'Ìxiptìn tlèn âmò mokìntekìuhtia',
'popularpages'            => 'Màsèwàltlaìxtlapaltìn',
'wantedcategories'        => 'Ìtech kineki tlaìxmatkàtlàlilòtl',
'wantedpages'             => 'Zāzaniltin moēlēhuiah',
'wantedfiles'             => 'Ìpan moneki èwaltìn',
'wantedtemplates'         => 'Ìtech moneki nemachiòmë',
'mostlinked'              => 'Tlâkuilòlpiltìn tlèn okachi tlatzòtzòwìllôkë',
'mostlinkedcategories'    => 'Tlaìxmatkàtlàlilòmë tlèn okachi tlatzòtzòwìllôkë',
'mostlinkedtemplates'     => 'Nemachiòmë tlèn okachi tlatzòtzòwìllôkë',
'mostcategories'          => 'Tlaìxtlapaltìn ìwàn okachi mièk tlaìxmatkàtlàlilòmë',
'mostimages'              => 'Īxiptli tlein in achi motzonhuilia',
'shortpages'              => 'Ahhuēiyac zāzaniltin',
'longpages'               => 'Huēiyac zāzaniltin',
'deadendpages'            => 'Ahtlaquīzaliztli zāzaniltin',
'protectedpages'          => 'Zāzaniltin ōmoquīxti',
'protectedpages-indef'    => 'Zan ahcāhuitl tlaquīxtiliztli',
'protectedpagestext'      => 'Inīn zāzaniltin ōmoquīxtih, auh ahmo mohuelītih mozacah nozo mopatlah',
'protectedtitles'         => 'Tōcāitl ōmoquīxtih',
'protectedtitlestext'     => 'Inīn tōcāitl ōmoquīxtih, auh ahmo mohuelītih mochīhuah',
'listusers'               => 'Tlatequitiltilīlli',
'newpages'                => 'Yancuīc zāzaniltin',
'newpages-username'       => 'Tlatequitiltilīltōcāitl:',
'ancientpages'            => 'Huēhuehzāzanilli',
'move'                    => 'Ticzacāz',
'movethispage'            => 'Ticzacāz inīn zāzanilli',
'pager-newer-n'           => '{{PLURAL:$1|1 yancuīc|$1 yancuīc}}',
'pager-older-n'           => '{{PLURAL:$1|1 huēhuetl|$1 huēhueh}}',

# Book sources
'booksources'    => 'āmoxmēyalli',
'booksources-go' => 'Yāuh',

# Special:Log
'specialloguserlabel'  => 'Tlatequitiltilīlli:',
'speciallogtitlelabel' => 'Tōcāitl:',
'log'                  => 'Tlahcuilōlloh',
'all-logs-page'        => 'Mochīntīn tlācah īntlahcuilōlloh',

# Special:AllPages
'allpages'          => 'Mochīntīn zāzanilli',
'alphaindexline'    => '$1 oc $2',
'nextpage'          => 'Niman zāzanilli ($1)',
'prevpage'          => 'Achto zāzanilli ($1)',
'allarticles'       => 'Mochīntīn tlahcuilōlli',
'allinnamespace'    => 'Mochīntīn zāzanilli (īpan $1)',
'allnotinnamespace' => 'Mochīntīn zāzanilli (quihcuāc $1)',
'allpagesprev'      => 'Achtopa',
'allpagesnext'      => 'Niman',
'allpagessubmit'    => 'Tiquittāz',

# Special:Categories
'categories'                    => 'Tlaìxmatkàtlàlilòmë',
'categoriespagetext'            => 'Inīn neneuhcāyōtl {{PLURAL:$1|quipiya|quimpiyah}} zāzanilli ahnozo medios.
Ahmo ahmo mottah nicān in [[Special:UnusedCategories|Neneuhcāyōtl ahmo motequitiltia]].
Nō xiquitta [[Special:WantedCategories|neneuhcāyōtl monequi]].',
'categoriesfrom'                => 'Xiquittaz neneuhcāyōtl mopēhuah īca:',
'special-categories-sort-count' => 'tlapōhualcopa',
'special-categories-sort-abc'   => 'tlahtōlcopa',

# Special:LinkSearch
'linksearch'    => 'Calān tzonhuiliztli',
'linksearch-ns' => 'Tōcātzin:',
'linksearch-ok' => 'Tictēmōz',

# Special:ListUsers
'listusers-submit' => 'Tiquittāz',

# Special:Log/newusers
'newuserlog-create-entry'  => 'Yancuīc tlatequitiltilīlli',
'newuserlog-create2-entry' => 'Yancuīc cuenta ōmochīuh $1',

# Special:ListGroupRights
'listgrouprights-group'  => 'Olōlli',
'listgrouprights-rights' => 'Huelītiliztli',

# E-mail user
'emailuser'       => 'Tique-mailīz inīn tlatequitiltilīlli',
'defemailsubject' => '{{SITENAME}} e-mail',
'emailfrom'       => 'Īhuīcpa:',
'emailto'         => 'Īhuīc:',
'emailmessage'    => 'Tlahcuilōltzintli:',
'emailsend'       => 'Ticquihuāz',
'emailsent'       => 'E-mail ōmoihuah',

# Watchlist
'watchlist'         => 'Notlachiyaliz',
'mywatchlist'       => 'Notlachiyaliz',
'watchnologin'      => 'Ahmo ōtimocalac',
'addedwatch'        => 'Ōmocētili tlachiyalizpan',
'removedwatch'      => 'Ōmopolo īpan motlachiyaliz',
'removedwatchtext'  => 'Zāzanilli "[[:$1]]" ōmopolo [[Special:Watchlist|motlachiyalizco]].',
'watch'             => 'Tictlachiyāz',
'watchthispage'     => 'Tictlachiyāz inīn zāzanilli',
'unwatch'           => 'Ahtictlachiyāz',
'watchlist-details' => '{{PLURAL:$1|$1 zāzanilli|$1 zāzaniltin}} motlachiyaliz, ahmo mopōhua tēixnāmiquiliztli.',
'wlshowlast'        => 'Tiquinttāz tlapatlaliztli īhuīcpa achto $1 yēmpohualminuhtli, $2 tōnaltin $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Tlachiyacah...',
'unwatching' => 'Ahtlachiyacah...',

'enotif_newpagetext'           => 'Inīn cah yancuīc zāzanilli.',
'enotif_impersonal_salutation' => 'tlatequitiltilīlli īpan {{SITENAME}}',
'changed'                      => 'ōmotlacuep',
'created'                      => 'ōmochīuh',
'enotif_anon_editor'           => 'ahtōcātlatequitiltilīlli $1',
'enotif_body'                  => 'Māhuizzoh $WATCHINGUSERNAME,

In {{SITENAME}} zāzanilli "$PAGETITLE" $CHANGEDORCREATED īpal in tlatequitiltilīlli $PAGEEDITOR īpan $PAGEEDITDATE.
Xiquitta in āxcān tlachiyaliztli īpan $PAGETITLE_URL.

$NEWPAGE

In tlapatlaliztli īxtlamatiliztli cah: $PAGESUMMARY $PAGEMINOREDIT

Ic ticnotzāz in tlatequitiltilīlli:
correo electrónico: {{fullurl:Special:Emailuser|target=$PAGEEDITOR}}
huiquipan: {{fullurl:User:$PAGEEDITOR}}

Ic ticpiyāz yancuīc tlanōnōtzaliztli tlapatlalizcopa inīn zāzanilpan, tihuīquilīz tictlahpolōz occeppa.
Nō tihuelīti, motlachiyalizpan, ticpatlāz motlanequiliz tlanōnōtzaliztechcopa in zāzanilli tiquinchiya.

             Mocnīuh {{SITENAME}} ītlanōnōtzaliz.

--
Ic ticpatla totlachiyaliz, xiquitta
{{fullurl:{{#special:Watchlist}}/edit}}

Ic ticpoloa in zāzanilli ītech totlachiyaliz, xiquitta
$UNWATCHURL

Tlacaquiztiliztli īhuān ocachi tēpalēhuiliztli:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Ticpolōz inīn zāzanilli',
'excontent'              => "Tlapiyaliztli ōcatca: '$1'",
'excontentauthor'        => "Tlapiyaliztli ōcatca: '$1' (auh zancē ōquipatlac ōcatca '[[Special:Contributions/$2|$2]]')",
'exblank'                => 'zāzanilli ōcatca iztāc',
'delete-confirm'         => 'Ticpolōz "$1"',
'delete-legend'          => 'Ticpolōz',
'actioncomplete'         => 'Cēntetl',
'deletedtext'            => '"<nowiki>$1</nowiki>" ōmopolo.
Xiquitta $2 ic yancuīc tlapololiztli.',
'deletedarticle'         => 'ōmopolo "[[$1]]"',
'dellogpage'             => 'Tlapololiztli tlahcuilōlloh',
'deletionlog'            => 'tlapololiztli tlahcuilōlloh',
'deletecomment'          => 'Īxtlamatiliztli:',
'deleteotherreason'      => 'Occē īxtlamatiliztli:',
'deletereasonotherlist'  => 'Occē īxtlamatiliztli',
'delete-edit-reasonlist' => 'Tiquimpatlāz īxtlamatiliztli tlapoloaliztechcopa',

# Rollback
'rollback_short'   => 'Tlacuepāz',
'rollbacklink'     => 'tlacuepāz',
'rollback-success' => 'Ōmotlacuep $1 ītlahcuilōl; āxcān achto $2 ītlahcuilōl.',

# Protect
'protectedarticle'            => 'ōmoquīxti "[[$1]]"',
'unprotectedarticle'          => 'ōahmoquīxti "[[$1]]"',
'prot_1movedto2'              => '[[$1]] ōmozacac īhuīc [[$2]]',
'protectexpiry'               => 'Tlamiliztli:',
'protect_expiry_invalid'      => 'Ahcualli tlamiliztli cāhuitl.',
'protect-default'             => 'Ticmācāhuaz mochintin in tlatequitiltilīltin',
'protect-fallback'            => 'Tiquihuīquilia tlahuelītiliztli "$1"',
'protect-level-autoconfirmed' => 'Tiquinquīxtīz yancuīc tlatequitiltilīltin īhuan in ahmo ōmocalacqueh',
'protect-level-sysop'         => 'Zan tētlamahmacanih',
'protect-expiring'            => 'motlamīz $1 (UTC)',
'protect-expiry-options'      => '1 hora:1 hour,1 tōnalli:1 day,1 chicuēyilhuitl:1 week,2 chicuēyilhuitl:2 weeks,1 mētztli:1 month,3 mētztli:3 months,6 mētztli:6 months,1 xihuitl:1 year,mochipa:infinite',
'restriction-type'            => 'Mācāhualiztli:',

# Restrictions (nouns)
'restriction-edit'   => 'Ticpatlāz',
'restriction-move'   => 'Ticzacāz',
'restriction-create' => 'Ticchīhuāz',
'restriction-upload' => 'Tlahcuilōlquetza',

# Undelete
'undelete'                  => 'Tiquinttāz zāzaniltin ōmopolōzqueh',
'viewdeletedpage'           => 'Tiquinttāz zāzaniltin ōmopolōzqueh',
'undelete-revision'         => 'Tlapoloc $1 ītlachiyaliz (īpan $4, $5) īpal $3:',
'undeletebtn'               => 'Ahticpolōz',
'undeletelink'              => 'tiquittaz/ticpahtīz',
'undeleteviewlink'          => 'tiquittāz',
'undeletedarticle'          => 'ōticcualtilih "[[$1]]"',
'undelete-search-box'       => 'Tiquintlatēmōz zāzaniltin ōmopolōz',
'undelete-search-prefix'    => 'Tiquittāz zāzaniltin mopēhua īca:',
'undelete-search-submit'    => 'Tlatēmōz',
'undelete-error-short'      => 'Ahcuallōtl ihcuāc momāquīxtiya: $1',
'undelete-show-file-submit' => 'Quemah',

# Namespace form on various pages
'namespace'      => 'Tōcātzin:',
'invert'         => 'Tlacuepāz motlahtōl',
'blanknamespace' => '(Huēyi)',

# Contributions
'contributions'       => 'Ītlahcuilōl',
'contributions-title' => 'Tlatequitiltilīlli $1 ītlahcuilōl',
'mycontris'           => 'Notlahcuilōl',
'contribsub2'         => '$1 ($2)',
'uctop'               => '(ahco)',
'month'               => 'Īhuīcpa mētztli (auh achtopa):',
'year'                => 'Xiuhhuīcpa (auh achtopa):',

'sp-contributions-newbies'       => 'Tiquinttāz zan yancuīc tlatequitiltilīlli īntlapatlaliz',
'sp-contributions-newbies-sub'   => 'Ic yancuīc',
'sp-contributions-newbies-title' => 'Yancuīc tlatequitiltilīlli ītlahcuilōl',
'sp-contributions-blocklog'      => 'Tlatzacuiliztli tlahcuilōlloh',
'sp-contributions-talk'          => 'tēixnāmiquiliztli',
'sp-contributions-search'        => 'Tiquintlatēmōz tlapatlaliztli',
'sp-contributions-username'      => 'IP nozo tlatequitiltilīlli ītōcā:',
'sp-contributions-submit'        => 'Tlatēmōz',

# What links here
'whatlinkshere'            => 'In tlein quitzonhuilia nicān',
'whatlinkshere-title'      => 'Zāzaniltin quitzonhuiliah $1',
'whatlinkshere-page'       => 'Zāzanilli:',
'linkshere'                => "Inīn zāzaniltin quitzonhuiliah '''[[:$1]]''' īhuīc:",
'nolinkshere'              => "Ahtle quitzonhuilia '''[[:$1]]''' īhuīc.",
'isredirect'               => 'ōmotlacuep zāzanilli',
'isimage'                  => 'īxiptli tzonhuiliztli',
'whatlinkshere-prev'       => '{{PLURAL:$1|achtopa|$1 achtopa}}',
'whatlinkshere-next'       => '{{PLURAL:$1|niman|$1 niman}}',
'whatlinkshere-links'      => '← tzòwilistìn',
'whatlinkshere-hideredirs' => '$1 tlacuepaliztli',
'whatlinkshere-hidelinks'  => '$1 tzòwilistìn',
'whatlinkshere-hideimages' => '$1 ìxiptzòwilistli',

# Block/unblock
'blockip'            => 'Tiquitzacuilīz tlatequitiltilīlli',
'blockip-legend'     => 'Tiquitzacuilīz tlatequitiltilīlli',
'ipadressorusername' => 'IP nozo tlatequitiltilīlli ītōcā:',
'ipbexpiry'          => 'Motlamia:',
'ipbreason'          => 'Īxtlamatiliztli:',
'ipbreasonotherlist' => 'Occē īxtlamatiliztli',
'ipbsubmit'          => 'Tiquitzacuilīz inīn tlatequitiltilīlli',
'ipbother'           => 'Occē cāuhpan:',
'ipboptions'         => '2 yēmpōhualminutl:2 hours,1 tōnalli:1 day,3 tōnaltin:3 days,7 tōnaltin:1 week,14 tōnaltin:2 weeks,1 mētztli:1 month,3 mētztli:3 months,6 mētztli:6 months,1 xihuitl:1 year,Mochipa:infinite',
'ipbotheroption'     => 'occē',
'ipbotherreason'     => 'Occē īxtlamatiliztli:',
'ipbwatchuser'       => 'Tiquinchiyāz inīn tlatequitiltilīlli in ītlatequitiltilīlzāzanil auh in ītēixnāmiquiliz',
'badipaddress'       => 'Ahcualli IP',
'blockipsuccesssub'  => 'Cualli tlatzacuiliztli',
'ipb-unblock-addr'   => 'Ahtiquitzacuilīz $1',
'ipb-unblock'        => 'Ahtiquitzacuilīz IP nozo tlatequitiltilīlli',
'unblockip'          => 'Ahtiquitzacuilīz tlatequitiltilīlli',
'ipblocklist-submit' => 'Tlatēmōz',
'infiniteblock'      => 'ahtlamic',
'expiringblock'      => 'tlami īpan $1 īpan $2',
'anononlyblock'      => 'zan ahtōcā',
'blocklink'          => 'tiquitzacuilīz',
'unblocklink'        => 'ahtiquitzacuilīz',
'contribslink'       => 'tlapatlaliztli',
'blocklogpage'       => 'Tlatequitiltilīlli ōmotzacuili',
'blockme'            => 'Timitzcuilīz',
'proxyblocksuccess'  => 'Ōmochīuh.',

# Move page
'move-page'                 => 'Ticzacāz $1',
'move-page-legend'          => 'Ticzacāz zāzanilli',
'movepagetext'              => "Nicān mohcuiloa quemeh ticzacāz cē zāzanilli auh mochi in ītlahcuillōloh īhuīc occē yancuīc ītōca.
Huēhuehtōcāitl yez tlacuepaliztli yancuīc tōcāhuīc.
Tzonhuiliztli huēhuehzāzanilhuīc ahmo mopatlāz.
Xiquitta ic māca xicchīhua [[Special:DoubleRedirects|ōntlacuepaliztli]] ahnozo [[Special:BrokenRedirects|tzomoc]].
Titzonhuilizpiyāz.

Xicmati in zāzanilli ahmo mozacāz intlā ye ia cē zāzanilli tōcātica, zan cah iztāc zāzanilli ahnozo tlacuepaliztli īca ahmo tlahcuilōlloh.
Quihtōznequi tihuelītīz ticuepāz cē zāzanilli īhuīc ītlācatōca intlā ahcuallōtl ticchīhuāz, tēl ahmo tihuelītīz occeppa tihcuilōz īpan zāzanilli tlein ia.

'''¡XICPŌHUA!'''
Hueliz cah inīn huēyi tlapatlaliztli. Timitztlātlauhtia ticmatīz cuallōtl auh ahcuallōtl achtopa ticzacāz.",
'movearticle'               => 'Ticzacāz tlahcuilōlli',
'movenologin'               => 'Ahmo ōtimocalac',
'movenotallowed'            => 'Ahmo tihuelīti tiquinzaca zāzaniltin.',
'newtitle'                  => 'Yancuīc tōcāhuīc',
'move-watch'                => 'Tictlachiyāz inīn zāzanilli',
'movepagebtn'               => 'Ticzacāz zāzanilli',
'pagemovedsub'              => 'Cualli ōmozacac',
'movepage-moved'            => '\'\'\'"$1" ōmotlacuep īhuīc "$2".\'\'\'',
'movedto'                   => 'ōmozacac īhuīc',
'movetalk'                  => 'Ticzacāz nō tēixnāmiquiliztli tlahcuilōltechcopa.',
'movepage-page-moved'       => 'Zāzanilli $1 ōmozacac īhuīc $2.',
'movepage-page-unmoved'     => 'Ahmo huelīti $1 mozaca īhuīc $2.',
'1movedto2'                 => '[[$1]] ōmozacac īhuīc [[$2]]',
'1movedto2_redir'           => '[[$1]] ōmozacac īhuīc [[$2]] tlacuepalpampa',
'movelogpage'               => 'Tlazacaliztli tlahcuilōlloh',
'movereason'                => 'Īxtlamatiliztli:',
'revertmove'                => 'tlacuepāz',
'delete_and_move'           => 'Ticpolōz auh ticzacāz',
'delete_and_move_confirm'   => 'Quēmah, ticpolōz in zāzanilli',
'immobile-source-namespace' => 'Ahmo huelīti mozaca zāzanilli tōcātzimpan "$1"',
'immobile-target-namespace' => 'Ahmo huelīti mozaca zāzanilli tōcātzinhuīc "$1"',
'immobile-source-page'      => 'Ahmo huelīti mozacāz zāzanilli.',
'move-leave-redirect'       => 'Ticcāhuāz cē tlacuepaliztli',

# Export
'export'            => 'Tiquinnamacāz zāzaniltin',
'export-submit'     => 'Ticnamacāz',
'export-addcattext' => 'Ticcēntilīz zāzanilli īhuīcpa neneuhcāyōtl:',
'export-addcat'     => 'Ticcētilīz',
'export-download'   => 'Ticpiyāz quemeh tlahcuilōlli',
'export-templates'  => 'Tiquimpiyāz nemachiyōtīlli',

# Namespace 8 related
'allmessages'               => 'Mochīntīn Huiquimedia tlahcuilōltzintli',
'allmessagesname'           => 'Tōcāitl',
'allmessagescurrent'        => 'Āxcān tlahcuilōlli',
'allmessages-filter-all'    => 'Mochi',
'allmessages-language'      => 'Tlâtòlli:',
'allmessages-filter-submit' => 'Yāuh',

# Thumbnails
'thumbnail-more'  => 'Tiquihuēyiyāz',
'thumbnail_error' => 'Ahcuallōtl ihcuāc mochīhuaya tepitōntli: $1',

# Special:Import
'import'                  => 'Tiquincōhuāz zāzaniltin',
'import-interwiki-submit' => 'Tiquicōhuāz',
'import-upload-filename'  => 'Tlahcuilōltōcāitl:',
'importstart'             => 'Motlacōhua zāzaniltin...',
'import-revision-count'   => '$1 {{PLURAL:$1|tlachiyaliztli|tlachiyaliztli}}',
'importbadinterwiki'      => 'Ahcualli interhuiqui tzonhuiliztli',
'importnotext'            => 'Ahtlein ahnozo ahtlahtōl',
'import-upload'           => 'Tiquinquetzāz XML tlahcuilōlli',

# Import log
'importlogpage' => 'Tiquincōhuāz tlahcuilōlloh',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Notlatequitiltilīlzāzanil',
'tooltip-pt-mytalk'               => 'Notēixnāmiquiliz',
'tooltip-pt-preferences'          => 'Notlaēlēhuiliz',
'tooltip-pt-watchlist'            => 'Zāzaniltin tiquintlachiya ic tlapatlaliztli',
'tooltip-pt-mycontris'            => 'Notlahcuilōl',
'tooltip-pt-login'                => 'Tihuelīti timocalaqui, tēl ahmo tihuīquilia.',
'tooltip-pt-anonlogin'            => 'Tihuelīti timocalaqui, tēl ahmo tihuīquilia.',
'tooltip-pt-logout'               => 'Tiquīzāz',
'tooltip-ca-talk'                 => 'Inīn tlahcuilōlli ītēixnāmiquiliz',
'tooltip-ca-edit'                 => 'Tihuelīti ticpatla inīn zāzanilli. Timitztlātlauhtiah, tiquiclica achtochīhualizpan achtopa ticpiya.',
'tooltip-ca-addsection'           => 'Tictzintīz yancuīc xeliuhcāyōtl.',
'tooltip-ca-viewsource'           => 'Inīn zāzanilli ōmoquīxti. Tihuelīti tiquitta ītlahtōlcaquiliztilōni.',
'tooltip-ca-history'              => 'Achtopa āxcān zāzanilli īhuān in tlatequitiltilīlli ōquinchīuhqueh',
'tooltip-ca-protect'              => 'Ticquīxtiāz inīn zāzanilli',
'tooltip-ca-delete'               => 'Ticpolōz inīn zāzanilli',
'tooltip-ca-undelete'             => 'Ahticpolōz inīn zāzanilli',
'tooltip-ca-move'                 => 'Ticzacāz inīn zāzanilli',
'tooltip-ca-watch'                => 'Ticcēntilīz inīn zāzanilli motlachiyalizhuīc',
'tooltip-ca-unwatch'              => 'Ahtictlachiyāz inīn zāzanilli',
'tooltip-search'                  => 'Tlatēmōz īpan {{SITENAME}}',
'tooltip-search-go'               => 'Tiyaz in zāzanilhuīc īca inīn huel melāhuac tōcaitl intlā yez',
'tooltip-p-logo'                  => 'Calīxatl',
'tooltip-n-mainpage'              => 'Tictlahpolōz in Calīxatl',
'tooltip-n-mainpage-description'  => 'Tiquittaz in calīxatl',
'tooltip-n-portal'                => 'Tlachīhualiztechcopa, inōn tihuelīti titlachīhua, tlatēmoyān',
'tooltip-n-recentchanges'         => 'Yancuīc tlapatlaliztli huiquipan',
'tooltip-n-randompage'            => 'Tiquittāz cē zāzotlein zāzanilli',
'tooltip-n-help'                  => 'Tlamachtiyān.',
'tooltip-t-whatlinkshere'         => 'Mochīntīn zāzaniltin huiquipan quitzonhuiliah nicān',
'tooltip-t-recentchangeslinked'   => 'Yancuīc tlapatlaliztli inīn zāzanilhuīcpa moquintzonhuilia',
'tooltip-feed-rss'                => 'RSS tlachicāhualiztli inīn zāzaniltechcopa',
'tooltip-feed-atom'               => 'Atom tlachicāhualiztli inīn zāzaniltechcopa',
'tooltip-t-contributions'         => 'Xiquitta inīn tlatequitiltilīlli ītlahcuilōl',
'tooltip-t-emailuser'             => 'Tiquihcuilōz inīn tlatequitiltililhuīc',
'tooltip-t-upload'                => 'Tiquinquetzāz tlahcuilōlli',
'tooltip-t-specialpages'          => 'Mochīntīn nōncuahquīzqui zāzaniltin',
'tooltip-t-print'                 => 'Tepoztlahcuilōlli',
'tooltip-ca-nstab-main'           => 'Xiquitta in tlahcuilōlli',
'tooltip-ca-nstab-user'           => 'Xiquitta tlatequitiltilīlli īzāzanil',
'tooltip-ca-nstab-special'        => 'Inīn cē nōncuahquīzqui zāzanilli; ahmo ticpatlahuelitīz in zāzanilli',
'tooltip-ca-nstab-project'        => 'Xiquitta tlachīhualiztli īzāzanil',
'tooltip-ca-nstab-image'          => 'Xiquittāz īxipzāzanilli',
'tooltip-ca-nstab-mediawiki'      => 'Xiquitta in tlahcuilōltzin',
'tooltip-ca-nstab-template'       => 'Xiquitta in nemachiyōtīlli',
'tooltip-ca-nstab-help'           => 'Xiquitta in tēpalēhuiliztli zāzanilli',
'tooltip-ca-nstab-category'       => 'Xiquitta in neneuhcāyōtl zāzanilli',
'tooltip-minoredit'               => 'Ticmachiyōz quemeh tlapatlalitzintli',
'tooltip-save'                    => 'Ticpiyāz mopatlaliz',
'tooltip-preview'                 => 'Xiquitta achtopa mopatlaliz, ¡timitztlātlauhtiah quitēquitiltilia achto ticpiya!',
'tooltip-diff'                    => 'Xiquitta in tlein ōticpatlāz tlahcuilōlco.',
'tooltip-compareselectedversions' => 'Tiquinttāz ahneneuhquiliztli ōme zāzanilli tlapatlaliznepantlah.',
'tooltip-watch'                   => 'Ticcēntilīz inīn zāzanilli motlachiyalizhuīc',
'tooltip-upload'                  => 'Ticpēhua quetzaliztli',

# Attribution
'anonymous'        => 'Ahtōcāitl {{PLURAL:$1|tlatequitiltilīlli|tlatequitiltilīlli}} īpan {{SITENAME}}',
'siteuser'         => '$1 tlatequitiltilīlli īpan {{SITENAME}}',
'lastmodifiedatby' => 'Inīn zāzanilli ōtlapatlac catca īpan $2, $1 īpal $3.',
'others'           => 'occequīntīn',
'siteusers'        => '$1 {{PLURAL:$2|tlatequitiltilīlli|tlatequitiltilīlli}} īpan {{SITENAME}}',

# Spam protection
'spam_reverting' => 'Mocuepacah īhuīc xōcoyōc tlapatlaliztli ahmo tzonhuilizca īhuīc $1',
'spam_blanking'  => 'Mochi tlapatlaliztli quimpiyah tzonhuiliztli īhuīc $1, iztāctiliacah',

# Info page
'infosubtitle'   => 'Zāzaniltechcopa',
'numedits'       => 'Tlapatlaliztli tlapōhualli (tlahcuilōlli): $1',
'numtalkedits'   => 'Tlapatlaliztli tlapōhualli (tēixnāmiquiliztli): $1',
'numwatchers'    => 'Tlachiyalōnih tlapōhualli: $1',
'numauthors'     => 'Ahneneuhqui tlapatlalōnih tlapōhualli (tlahcuilōlli): $1',
'numtalkauthors' => 'Ahneneuhqui tlapatlalōnih tlapōhualli (tēixnāmiquiliztli): $1',

# Browsing diffs
'previousdiff' => '← Achtopa',
'nextdiff'     => 'Oc ye cencah yancuīc tlapatlaliztli →',

# Media information
'widthheightpage' => '$1×$2, $3 {{PLURAL:|zāzanilli|zāzanilli}}',
'file-info-size'  => '$1 × $2 pixel; zāzanilli octacayōtl: $3; machiyōtl MIME: $4',
'file-nohires'    => '<small>Ahmo ia achi cualli ahmo occē īxiptli.</small>',
'show-big-image'  => 'Mochi cuallōtl',

# Special:NewFiles
'newimages'     => 'Yancuīc īxipcān',
'imagelisttext' => "Nicān {{PLURAL:$1|mopiya|mopiyah}} '''$1''' īxiptli $2 iuhcopa.",
'showhidebots'  => '($1 tepoztlācah)',
'noimages'      => 'Ahtlein ic tlatta.',
'ilsubmit'      => 'Tlatēmōz',
'bydate'        => 'tōnalcopa',

# Metadata
'metadata'          => 'Metadata',
'metadata-expand'   => 'Tiquittāz tlanōnōtzaliztli huehca ōmpa',
'metadata-collapse' => 'Tictlātīz tlanōnōtzaliztli huehca ōmpa',

# EXIF tags
'exif-photometricinterpretation' => 'Pixel tlachīhualiztli',
'exif-imagedescription'          => 'Īxiptli ītōcā',
'exif-software'                  => 'Software ōmotēquitilti',
'exif-artist'                    => 'Chīhualōni',
'exif-usercomment'               => 'Quihtoa tlatequitiltilīlli',
'exif-exposuretime'              => 'Cāuhcāyōtl',
'exif-fnumber'                   => 'F Tlapōhualli',
'exif-isospeedratings'           => 'ISO iciuhquiliztli tlapōhualcāyōtl',
'exif-flash'                     => 'Flax',
'exif-flashenergy'               => 'Flax chicāhualiztli',
'exif-gpslatituderef'            => 'Mictlāmpa ahnozo huitztlāmpa āncāyōtl',
'exif-gpslatitude'               => 'Āncāyōtl',
'exif-gpslongituderef'           => 'Tlāpcopa ahnozo cihuātlāmpa huehtlatzīncāyōtl',
'exif-gpslongitude'              => 'Huehtlatzīncāyōtl',
'exif-gpsaltitude'               => 'Huehcapancayōtl',
'exif-gpstimestamp'              => 'GPS cāhuitl (atomic tepozcāhuitl)',

'exif-orientation-1' => 'Yēctli',

'exif-meteringmode-255' => 'Occē',

'exif-lightsource-1'   => 'Tōnameyōtl',
'exif-lightsource-2'   => 'Nāltic',
'exif-lightsource-4'   => 'Flax',
'exif-lightsource-10'  => 'Mixxoh',
'exif-lightsource-11'  => 'Ecahuīlli',
'exif-lightsource-12'  => 'Nāltic tōnallāhuīlli (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Nāltic iztāc tōnallāhuīlli (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Nāltic cecec iztāc (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Nāltic iztāc (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Yēctli tlāhuīlli A',
'exif-lightsource-18'  => 'Yēctli tlāhuīlli B',
'exif-lightsource-19'  => 'Yēctli tlāhuīlli C',
'exif-lightsource-255' => 'Occequīntīn tlāhuīlli',

'exif-scenecapturetype-3' => 'Yohualcopa',

'exif-gaincontrol-0' => 'Ahtlein',

'exif-contrast-0' => 'Yēctli',

'exif-saturation-0' => 'Yēctli',

'exif-sharpness-0' => 'Yēctli',

'exif-subjectdistancerange-0' => 'Ahmatic',
'exif-subjectdistancerange-1' => 'Huēyi',
'exif-subjectdistancerange-2' => 'Ahhuehca tlattaliztli',
'exif-subjectdistancerange-3' => 'Huehca tlattaliztli',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Ayamictlān',
'exif-gpslatitude-s' => 'Huiztlān',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Tlāpcopa huehtlatzīncāyōtl',
'exif-gpslongitude-w' => 'Cihuātlāmpa huehtlatzīncāyōtl',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'mochīntīn',
'imagelistall'     => 'mochīntīn',
'watchlistall2'    => 'mochīntīn',
'namespacesall'    => 'mochīntīn',
'monthsall'        => '(mochīntīn)',
'limitall'         => 'mochi',

# E-mail address confirmation
'confirmemail'           => 'Ticchicāhuāz e-mail',
'confirmemail_needlogin' => 'Tihuīquilia $1 ic ticchicāhua mo e-mail.',
'confirmemail_success'   => 'Mocorreo ōmotlahtōlneltilih
Niman tihuelīti [[Special:UserLogin|timocalaqui]] auh ticpactiāz huiquitica.',
'confirmemail_loggedin'  => 'Mo e-mailcān ōmochicāuh.',
'confirmemail_error'     => 'Achi ōcatcah ahcualli mochicāhualiztechcopa.',
'confirmemail_subject'   => 'e-mailcān {{SITENAME}} ītlachicāhualiz',

# Scary transclusion
'scarytranscludetoolong' => '[In URL achi huel huēiyac ca]',

# Trackbacks
'trackbackremove' => '([$1 Ticpolōz])',

# Delete conflict
'recreate' => 'Ticchīhuāz occeppa',

# action=purge
'confirm_purge_button' => 'Cualli',

# Multipage image navigation
'imgmultipageprev' => '← achto zāzanilli',
'imgmultipagenext' => 'niman zāzanilli →',
'imgmultigo'       => '¡Yāuh!',
'imgmultigoto'     => 'Yāuh $1 zāzanilhuīc',

# Table pager
'ascending_abbrev'         => 'quetza',
'descending_abbrev'        => 'temoa',
'table_pager_next'         => 'Niman zāzanilli',
'table_pager_prev'         => 'Achto zāzanilli',
'table_pager_first'        => 'Achtopa zāzanilli',
'table_pager_last'         => 'Xōcoyōc zāzanilli',
'table_pager_limit_submit' => 'Yāuh',
'table_pager_empty'        => 'Ahtlein',

# Auto-summaries
'autosumm-blank'   => 'Tlaiztāctilīlli zāzanilli',
'autoredircomment' => 'Mocuepahua īhuīc [[$1]]',
'autosumm-new'     => 'Tlachīhualli zāzanilli īca: "$1"',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Tēmohua...',
'livepreview-ready'   => 'Motemocah... ¡Ye!',

# Watchlist editor
'watchlistedit-numitems'     => 'Motlachiyaliz {{PLURAL:$1|quipiya cē zāzanilli|quimpiya $1 zāzaniltin}}, ahtle tēixnāmiquiliztli.',
'watchlistedit-normal-title' => 'Ticpatlāz motlachiyaliz',
'watchlistedit-raw-added'    => '{{PLURAL:$1|Ōmocentili cē zāzanilli|Ōmocentilih $1 zāzaniltin}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Tiquinttāz huēyi tlapatlaliztli',
'watchlisttools-edit' => 'Tiquittāz auh ticpatlāz motlachiyaliz',

# Special:Version
'version'                  => 'Machiyōtzin',
'version-specialpages'     => 'Nònkuâkìskàtlaìxtlapaltìn',
'version-other'            => 'Occē',
'version-version'          => '(Machiyōtzin $1)',
'version-software-version' => 'Machiyōtzin',

# Special:FilePath
'filepath-page'   => 'Tlahcuilōlli:',
'filepath-submit' => 'Yāuh',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Tlahcuilōlli ītōcā:',
'fileduplicatesearch-submit'   => 'Tlatēmōz',
'fileduplicatesearch-info'     => '$1 × $2 pixelli<br />Tlahcuilōlli īxquichiliz: $3<br />MIME iuhcāyōtl: $4',

# Special:SpecialPages
'specialpages'                 => 'Nōncuahquīzqui āmatl',
'specialpages-note'            => '----
* Nōncuahquīzqui.
* <strong class="mw-specialpagerestricted">Tzacuilic.</strong>',
'specialpages-group-other'     => 'Occequīntīn nōncuahquīzqui zāzaniltin',
'specialpages-group-login'     => 'Ximocalaqui / ximomachiyōmaca',
'specialpages-group-changes'   => 'Yancuīc tlapatlaliztli īhuān tlahcuilōlloh',
'specialpages-group-users'     => 'Tlatequitiltilīlli īhuān huelītiliztli',
'specialpages-group-highuse'   => 'Zāzaniltin tlatequitiliztechcopa',
'specialpages-group-pages'     => 'Mochīntīn zāzaniltin',
'specialpages-group-redirects' => 'Tlatēmoliztli īhuān  tlacuepaliztli',

# Special:BlankPage
'blankpage' => 'Iztāc zāzanilli',

# HTML forms
'htmlform-selectorother-other' => 'Occē',

# Special:DisableAccount
'disableaccount-reason' => 'Tlèka:',

);
