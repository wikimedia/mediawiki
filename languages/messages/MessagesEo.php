<?php
/** Esperanto (Esperanto)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amikeco
 * @author ArnoLagrange
 * @author Jens Liebenau
 * @author Malafaya
 * @author Melancholie
 * @author Michawiki
 * @author MinuteElectron
 * @author Omnipaedista
 * @author Smeira
 * @author Tlustulimu
 * @author Urhixidur
 * @author Yekrats
 * @author Александр Сигачёв
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciala',
	NS_TALK             => 'Diskuto',
	NS_USER             => 'Vikipediisto',
	NS_USER_TALK        => 'Vikipediista_diskuto',
	NS_PROJECT_TALK     => '$1_diskuto',
	NS_FILE             => 'Dosiero',
	NS_FILE_TALK        => 'Dosiera_diskuto',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskuto',
	NS_TEMPLATE         => 'Ŝablono',
	NS_TEMPLATE_TALK    => 'Ŝablona_diskuto',
	NS_HELP             => 'Helpo',
	NS_HELP_TALK        => 'Helpa_diskuto',
	NS_CATEGORY         => 'Kategorio',
	NS_CATEGORY_TALK    => 'Kategoria_diskuto',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Duoblaj alidirektiloj' ),
	'BrokenRedirects'           => array( 'Rompitaj alidirektiloj' ),
	'Disambiguations'           => array( 'Apartigiloj' ),
	'Userlogin'                 => array( 'Ensaluti' ),
	'Userlogout'                => array( 'Elsaluti' ),
	'CreateAccount'             => array( 'Krei konton' ),
	'Preferences'               => array( 'Preferoj' ),
	'Watchlist'                 => array( 'Atentaro' ),
	'Recentchanges'             => array( 'Lastaj ŝanĝoj' ),
	'Upload'                    => array( 'Alŝuti' ),
	'Listfiles'                 => array( 'Bildolisto' ),
	'Newimages'                 => array( 'Novaj bildoj' ),
	'Listusers'                 => array( 'Listo de uzantoj' ),
	'Listgrouprights'           => array( 'Gruprajtoj de uzantoj' ),
	'Statistics'                => array( 'Statistikoj' ),
	'Randompage'                => array( 'Hazarda paĝo' ),
	'Lonelypages'               => array( 'Neligitaj paĝoj' ),
	'Uncategorizedpages'        => array( 'Paĝoj sen kategorio' ),
	'Uncategorizedcategories'   => array( 'Kategorioj sen kategorio' ),
	'Uncategorizedimages'       => array( 'Bildoj sen kategorio' ),
	'Uncategorizedtemplates'    => array( 'Ŝablonoj sen kategorio' ),
	'Unusedcategories'          => array( 'Malplenaj kategorioj' ),
	'Unusedimages'              => array( 'Neuzataj bildoj' ),
	'Wantedpages'               => array( 'Dezirataj paĝoj', 'Rompitaj ligiloj' ),
	'Wantedcategories'          => array( 'Dezirataj kategorioj' ),
	'Wantedfiles'               => array( 'Dezirataj dosieroj' ),
	'Wantedtemplates'           => array( 'Dezirataj ŝablonoj' ),
	'Mostlinked'                => array( 'Plej ligitaj paĝoj' ),
	'Mostlinkedcategories'      => array( 'Plej ligitaj kategorioj', 'Plej uzataj kategorioj' ),
	'Mostlinkedtemplates'       => array( 'Plej ligitaj ŝablonoj', 'Plej uzataj ŝablonoj' ),
	'Mostimages'                => array( 'Plej multaj bildoj' ),
	'Mostcategories'            => array( 'Plej multaj kategorioj' ),
	'Mostrevisions'             => array( 'Plej multaj revizioj' ),
	'Fewestrevisions'           => array( 'Plej malmultaj revizioj' ),
	'Shortpages'                => array( 'Mallongaj paĝoj' ),
	'Longpages'                 => array( 'Longaj paĝoj' ),
	'Newpages'                  => array( 'Novaj paĝoj' ),
	'Ancientpages'              => array( 'Malnovaj paĝoj' ),
	'Deadendpages'              => array( 'Paĝoj sen interna ligilo' ),
	'Protectedpages'            => array( 'Protektitaj paĝoj' ),
	'Protectedtitles'           => array( 'Protektitaj titoloj' ),
	'Allpages'                  => array( 'Ĉiuj paĝoj' ),
	'Prefixindex'               => array( 'Indekso de prefiksoj' ),
	'Ipblocklist'               => array( 'Forbarlisto de IP-adresoj' ),
	'Specialpages'              => array( 'Specialaj paĝoj' ),
	'Contributions'             => array( 'Kontribuoj' ),
	'Emailuser'                 => array( 'Retpoŝti uzanton' ),
	'Confirmemail'              => array( 'Konfirmi per retpoŝto' ),
	'Whatlinkshere'             => array( 'Kio ligas ĉi tien?' ),
	'Recentchangeslinked'       => array( 'Rilataj ŝanĝoj' ),
	'Movepage'                  => array( 'Alinomigi paĝon' ),
	'Blockme'                   => array( 'Forbari min' ),
	'Booksources'               => array( 'Citoj el libroj' ),
	'Categories'                => array( 'Kategorioj' ),
	'Export'                    => array( 'Eksporti' ),
	'Version'                   => array( 'Versio' ),
	'Allmessages'               => array( 'Ĉiuj mesaĝoj' ),
	'Log'                       => array( 'Loglibro', 'Loglibroj' ),
	'Blockip'                   => array( 'Forbari IP-adreson' ),
	'Undelete'                  => array( 'Restarigi' ),
	'Import'                    => array( 'Importi' ),
	'Popularpages'              => array( 'Popularaj paĝoj' ),
	'Search'                    => array( 'Serĉi' ),
	'Tags'                      => array( 'Etikedoj' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ALIDIREKTU', '#REDIRECT' ),
	'nogallery'             => array( '0', '__SENGALERIO__', '__NOGALLERY__' ),
	'currentmonth'          => array( '1', 'NUNAMONATO', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentdayname'        => array( '1', 'NUNATAGNOMO', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'NUNAJARO', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'NUNATEMPO', 'CURRENTTIME' ),
	'localdayname'          => array( '1', 'LOKATAGNOMO', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'LOKAJARO', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LOKATEMPO', 'LOCALTIME' ),
	'localhour'             => array( '1', 'LOKAHORO', 'LOCALHOUR' ),
	'numberofarticles'      => array( '1', 'NOMBRODEARTIKOLOJ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NOMBRODEDOSIEROJ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NOMBRODEUZANTOJ', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NOMBRODEREDAKTOJ', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'PAĜNOMO', 'PAGXNOMO', 'PAGENAME' ),
	'img_thumbnail'         => array( '1', 'eta', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'eta=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'dekstra', 'right' ),
	'img_left'              => array( '1', 'maldekstra', 'left' ),
	'img_width'             => array( '1', '$1ra', '$1px' ),
	'img_center'            => array( '1', 'centra', 'meza', 'center', 'centre' ),
	'img_framed'            => array( '1', 'kadro', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'senkadra', 'frameless' ),
	'img_text_bottom'       => array( '1', 'suba-teksto', 'text-bottom' ),
	'sitename'              => array( '1', 'TTT-NOMO', 'SITENAME' ),
	'server'                => array( '0', 'SERVILO', 'SERVER' ),
	'servername'            => array( '0', 'NOMODESERVILO', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SKRIPTO-VOJO', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMATIKO:', 'GRAMMAR:' ),
	'revisionyear'          => array( '1', 'JARODEREVIZIO', 'REVISIONYEAR' ),
	'plural'                => array( '0', 'PLURALA:', 'PLURAL:' ),
	'lc'                    => array( '0', 'MINUSKLA:', 'LC:' ),
	'uc'                    => array( '0', 'MAJUSKLA:', 'UC:' ),
	'newsectionlink'        => array( '1', '__LIGLIOALNOVASEKCIO__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'NUNAVERSIO', 'CURRENTVERSION' ),
	'language'              => array( '0', '#LINGVO:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'ENHAVA-LINGVO', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'        => array( '1', 'NOMBRODEADMINOJ', 'NUMBEROFADMINS' ),
	'special'               => array( '0', 'speciala', 'special' ),
	'defaultsort'           => array( '1', 'DEFAŬLTORDIGO:', 'DEFAUXLTORDIGO:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'tag'                   => array( '0', 'etikedo', 'tag' ),
	'hiddencat'             => array( '1', '__KAŜITAKATEGORIO__', '__HIDDENCAT__' ),
	'pagesize'              => array( '1', 'PEZODEPAĜO', 'PAGESIZE' ),
	'noindex'               => array( '1', '__NENIUINDEKSO__', '__NOINDEX__' ),
);

$separatorTransformTable = array(',' => ' ', '.' => ',' );

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Substreki ligilojn',
'tog-highlightbroken'         => 'Ruĝigi ligilojn al neekzistantaj paĝoj',
'tog-justify'                 => 'Alkadrigi liniojn',
'tog-hideminor'               => 'Kaŝi malgrandajn redaktetojn ĉe <i>Lastaj ŝanĝoj</i>',
'tog-hidepatrolled'           => 'Kaŝi patrolitajn redaktojn en lastaj ŝanĝoj',
'tog-newpageshidepatrolled'   => 'Kaŝi patrolitajn paĝojn de listo de novaj paĝoj',
'tog-extendwatchlist'         => 'Etendi la atentaron por montri ĉiujn ŝanĝon, ne nur la plej lastaj',
'tog-usenewrc'                => 'Uzi progresan "Lastaj Ŝanĝoj" (bezonas JavaSkripton)',
'tog-numberheadings'          => 'Aŭtomate numerigi sekciojn',
'tog-showtoolbar'             => 'Montri eldonilaron',
'tog-editondblclick'          => 'Redakti per duobla alklako (JavaScript)',
'tog-editsection'             => 'Montri [redakti]-ligilojn por sekcioj',
'tog-editsectiononrightclick' => 'Ŝalti sekcian redaktadon per dekstra musklako de sekciaj titoloj (kun JavaScript)',
'tog-showtoc'                 => 'Montri liston de enhavoj (por paĝojn kun pli ol 3 sekciojn)',
'tog-rememberpassword'        => 'Rememori mian pasvorton',
'tog-editwidth'               => 'Plenekranigi la redaktilon',
'tog-watchcreations'          => 'Aldoni de mi kreitajn paĝojn al mia atentaro',
'tog-watchdefault'            => 'Priatenti redaktintajn paĝojn de vi',
'tog-watchmoves'              => 'Aldoni paĝojn, kiujn mi movas, al mia atentaro',
'tog-watchdeletion'           => 'Aldoni paĝojn, kiujn mi forigas, al mia atentaro',
'tog-minordefault'            => 'Marki defaŭlte ĉiujn redaktojn kiel etajn',
'tog-previewontop'            => 'Montri antaŭrigardon antaŭ redaktilo',
'tog-previewonfirst'          => 'Montri antaŭrigardon je unua redakto',
'tog-nocache'                 => 'Malŝalti kaŝmemorigon de paĝoj',
'tog-enotifwatchlistpages'    => 'Sendi al mi retmesaĝon kiam tiu paĝo estas ŝanĝita',
'tog-enotifusertalkpages'     => 'Sendi al mi retmesaĝon kiam mia diskutpaĝo estas ŝanĝita',
'tog-enotifminoredits'        => 'Sendi al mi ankaŭ retmesaĝojn por malgrandaj redaktoj de paĝoj',
'tog-enotifrevealaddr'        => 'Malkaŝi mian retadreson en informaj retpoŝtaĵoj',
'tog-shownumberswatching'     => 'Montri la nombron da priatentaj uzantoj',
'tog-oldsig'                  => 'Antaŭvido de nuna subskribo:',
'tog-fancysig'                => 'Trakti subskribon kiel vikitekston (sen aŭtomata ligo)',
'tog-externaleditor'          => 'Uzi defaŭlte eksteran tekstprilaborilon',
'tog-externaldiff'            => 'Uzi defaŭlte eksteran ŝanĝmontrilon',
'tog-showjumplinks'           => 'Ebligi alirligojn "salti al"
<!-- Bonvolu kontroli ĉu ĝustas la traduko de : Enable "jump to" accessibility links -->',
'tog-uselivepreview'          => 'Uzi tujan antaŭrigardon (ĜavaSkripto) (Eksperimenta)',
'tog-forceeditsummary'        => 'Averti min kiam mi konservas malplenan redaktoresumon',
'tog-watchlisthideown'        => 'Kaŝi miajn redaktojn de la atentaro',
'tog-watchlisthidebots'       => 'Kaŝu bot-redaktojn de la atentaro',
'tog-watchlisthideminor'      => 'Kaŝi malgrandajn redaktojn de la atentaro',
'tog-watchlisthideliu'        => 'Kaŝi redaktojn de ensalutitaj uzantoj de la atentaro',
'tog-watchlisthideanons'      => 'Kaŝi redaktojn de anonimuloj de la atentaro',
'tog-watchlisthidepatrolled'  => 'Kaŝi patrolitajn redaktojn de la atentaro',
'tog-nolangconversion'        => 'Malŝalti konvertadon de variantoj',
'tog-ccmeonemails'            => 'Sendi al mi kopiojn de retpoŝtaĵoj, kiujn mi sendis al aliaj uzuloj.',
'tog-diffonly'                => 'Ne montri paĝan enhavon sub la ŝanĝoj',
'tog-showhiddencats'          => 'Montri kaŝitajn kategoriojn',
'tog-norollbackdiff'          => 'Mankigi ŝanĝojn post farante malfaron',

'underline-always'  => 'Ĉiam',
'underline-never'   => 'Neniam',
'underline-default' => 'Defaŭlte laŭ foliumilo',

# Font style option in Special:Preferences
'editfont-style'     => 'Tipara stilo de redakta tekstujo',
'editfont-default'   => 'Retumila defaŭlto',
'editfont-monospace' => 'Egallarĝa tiparo',
'editfont-sansserif' => 'Senserifa tiparo',
'editfont-serif'     => 'Serifa tiparo',

# Dates
'sunday'        => 'dimanĉo',
'monday'        => 'lundo',
'tuesday'       => 'mardo',
'wednesday'     => 'merkredo',
'thursday'      => 'ĵaŭdo',
'friday'        => 'vendredo',
'saturday'      => 'sabato',
'sun'           => 'Dim',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mer',
'thu'           => 'Ĵaŭ',
'fri'           => 'Ven',
'sat'           => 'Sab',
'january'       => 'januaro',
'february'      => 'februaro',
'march'         => 'marto',
'april'         => 'aprilo',
'may_long'      => 'majo',
'june'          => 'junio',
'july'          => 'julio',
'august'        => 'aŭgusto',
'september'     => 'septembro',
'october'       => 'oktobro',
'november'      => 'novembro',
'december'      => 'decembro',
'january-gen'   => 'Januaro',
'february-gen'  => 'Februaro',
'march-gen'     => 'Marto',
'april-gen'     => 'Aprilo',
'may-gen'       => 'Majo',
'june-gen'      => 'Junio',
'july-gen'      => 'Julio',
'august-gen'    => 'Aŭgusto',
'september-gen' => 'Septembro',
'october-gen'   => 'Oktobro',
'november-gen'  => 'Novembro',
'december-gen'  => 'Decembro',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Maj',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aŭg',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorio|Kategorioj}}',
'category_header'                => 'Artikoloj en kategorio "$1"',
'subcategories'                  => 'Subkategorioj',
'category-media-header'          => 'Dosieroj en kategorio "$1"',
'category-empty'                 => "''Ĉi tiu kategorio momente ne enhavas artikolojn aŭ mediojn.''",
'hidden-categories'              => '{{PLURAL:$1|Kaŝita kategorio|Kaŝitaj kategorioj}}',
'hidden-category-category'       => 'Kaŝitaj kategorioj',
'category-subcat-count'          => '{{PLURAL:$2|Ĉi tiu kategorio havas nur la suban subkategorion.|Ĉi tiu kategorio havas la {{PLURAL:$1|suban subkategorion|$1 subajn subkategoriojn}}, el $2 entute.}}',
'category-subcat-count-limited'  => 'Ĉi tiu kategorio havas la {{PLURAL:$1|jenan subkategorion|jenajn $1 subkategoriojn}}.',
'category-article-count'         => '{{PLURAL:$2|Ĉi tiu kategorio enhavas nur la jenan paĝon.|La {{PLURAL:$1|jena paĝo|jenaj $1 paĝoj}} estas en ĉi tiu kategorio, el $2 entute.}}',
'category-article-count-limited' => 'La {{PLURAL:$1|jena paĝo|jenaj $1 paĝoj}} estas en la nuna kategorio.',
'category-file-count'            => '{{PLURAL:$2|Ĉi tiu kategorio nur enhavas la jenan dosieron.|La {{PLURAL:$1|jena doesiero|jenaj $1 dosieroj}} estas en ĉi tiun kategorion, el $2 entute.}}',
'category-file-count-limited'    => 'La {{PLURAL:$1|jena dosiero|jenaj $1 dosieroj}} estas en la nuna kategorio.',
'listingcontinuesabbrev'         => 'daŭrigo',
'index-category'                 => 'Indeksitaj paĝoj',
'noindex-category'               => 'Neindeksitaj paĝoj',

'mainpagetext'      => "<big>'''MediaViki softvaro sukcese instaliĝis.'''</big>",
'mainpagedocfooter' => "Konsultu la [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] por informo pri uzado de vikia programaro.

==Kiel komenci==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listo de konfiguraĵoj] (angla)
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Oftaj Demandoj] (angla)
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki dissendolisto pri anoncoj] (angla)",

'about'         => 'Enkonduko',
'article'       => 'Artikolo',
'newwindow'     => '(en nova fenestro)',
'cancel'        => 'Nuligi',
'moredotdotdot' => 'Pli...',
'mypage'        => 'Mia paĝo',
'mytalk'        => 'Mia diskuto',
'anontalk'      => 'Diskutpaĝo por tiu ĉi IP',
'navigation'    => 'Navigado',
'and'           => '&#32;kaj',

# Cologne Blue skin
'qbfind'         => 'Trovi',
'qbbrowse'       => 'Foliumado',
'qbedit'         => 'Redakti',
'qbpageoptions'  => 'Paĝagado',
'qbpageinfo'     => 'Paĝinformoj',
'qbmyoptions'    => 'Personaĵoj',
'qbspecialpages' => 'Specialaj paĝoj',
'faq'            => 'Oftaj demandoj',
'faqpage'        => 'Project:Oftaj demandoj',

# Vector skin
'vector-action-addsection'   => 'Aldoni temon',
'vector-action-delete'       => 'Forigi',
'vector-action-move'         => 'Alinomigi',
'vector-action-protect'      => 'Protekti',
'vector-action-undelete'     => 'Malforigi',
'vector-action-unprotect'    => 'Malprotekti',
'vector-namespace-category'  => 'Kategorio',
'vector-namespace-help'      => 'Helpopaĝo',
'vector-namespace-image'     => 'Dosiero',
'vector-namespace-main'      => 'Paĝo',
'vector-namespace-media'     => 'Dosiera paĝo',
'vector-namespace-mediawiki' => 'Mesaĝo',
'vector-namespace-project'   => 'Projekta paĝo',
'vector-namespace-special'   => 'Speciala paĝo',
'vector-namespace-talk'      => 'Diskuto',
'vector-namespace-template'  => 'Ŝablono',
'vector-namespace-user'      => 'Uzula paĝo',
'vector-view-create'         => 'Krei',
'vector-view-edit'           => 'Redakti',
'vector-view-history'        => 'Vidi historion',
'vector-view-view'           => 'Legi',
'vector-view-viewsource'     => 'Vidi fonton',
'actions'                    => 'Agoj',
'namespaces'                 => 'Nomspacoj',
'variants'                   => 'Variantoj',

# Metadata in edit box
'metadata_help' => 'Metadatenoj:',

'errorpagetitle'    => 'Eraro',
'returnto'          => 'Reiri al $1.',
'tagline'           => 'El {{SITENAME}}',
'help'              => 'Helpo',
'search'            => 'Serĉi',
'searchbutton'      => 'Serĉi',
'go'                => 'Ek!',
'searcharticle'     => 'Ek!',
'history'           => 'Historio de versioj',
'history_short'     => 'Historio',
'updatedmarker'     => 'ĝisdatita de post mia lasta vizito',
'info_short'        => 'Informo',
'printableversion'  => 'Presebla versio',
'permalink'         => 'Konstanta ligilo',
'print'             => 'Printi',
'edit'              => 'Redakti',
'create'            => 'Krei',
'editthispage'      => 'Redakti la paĝon',
'create-this-page'  => 'Krei ĉi tiun paĝon',
'delete'            => 'Forigi',
'deletethispage'    => 'Forigi ĉi tiun paĝon',
'undelete_short'    => 'Malforigi {{PLURAL:$1|redakton|$1 redaktojn}}',
'protect'           => 'Protekti',
'protect_change'    => 'ŝanĝi',
'protectthispage'   => 'Protekti la paĝon',
'unprotect'         => 'Malprotekti',
'unprotectthispage' => 'Malprotekti la paĝon',
'newpage'           => 'Nova paĝo',
'talkpage'          => 'Diskuti la paĝon',
'talkpagelinktext'  => 'Diskuto',
'specialpage'       => 'Speciala Paĝo',
'personaltools'     => 'Personaj iloj',
'postcomment'       => 'Nova sekcio',
'articlepage'       => 'Rigardi artikolon',
'talk'              => 'Diskuto',
'views'             => 'Vidoj',
'toolbox'           => 'Iloj',
'userpage'          => 'Rigardi personan paĝon',
'projectpage'       => 'Rigardi projektopaĝon',
'imagepage'         => 'Vidi dosieropaĝon',
'mediawikipage'     => 'Vidi mesaĝopaĝon',
'templatepage'      => 'Vidi ŝablonopaĝon',
'viewhelppage'      => 'Vidi helpopaĝon',
'categorypage'      => 'Vidi kategorian paĝon',
'viewtalkpage'      => 'Vidi diskuton',
'otherlanguages'    => 'Aliaj lingvoj',
'redirectedfrom'    => '(Alidirektita el $1)',
'redirectpagesub'   => 'Alidirektilo',
'lastmodifiedat'    => 'Laste redaktita je $2, $1.',
'viewcount'         => 'Montrita {{PLURAL:$1|unufoje|$1 fojojn}}.',
'protectedpage'     => 'Protektita paĝo',
'jumpto'            => 'Iri al:',
'jumptonavigation'  => 'navigado',
'jumptosearch'      => 'serĉi',
'view-pool-error'   => 'Bedaŭrinde la serviloj estas tro uzata ĉi-momente.
Tro da uzantoj provas vidi ĉi tiun paĝon.
Bonvolu atendi iom antaŭ vi provas atingi ĝin denove.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Pri {{SITENAME}}',
'aboutpage'            => 'Project:Enkonduko',
'copyright'            => 'La enhavo estas havebla sub $1.',
'copyrightpage'        => '{{ns:project}}:Kopirajto',
'currentevents'        => 'Aktualaĵoj',
'currentevents-url'    => 'Project:Aktualaĵoj',
'disclaimers'          => 'Malgarantio',
'disclaimerpage'       => 'Project:Malgarantia paĝo',
'edithelp'             => 'Helpo pri redaktado',
'edithelppage'         => 'Help:Kiel redakti paĝon',
'helppage'             => 'Help:Enhavo',
'mainpage'             => 'Ĉefpaĝo',
'mainpage-description' => 'Ĉefpaĝo',
'policy-url'           => 'Project:Konsiletoj',
'portal'               => 'Komunuma portalo',
'portal-url'           => 'Project:Komunuma portalo',
'privacy'              => 'Regularo pri respekto de la privateco',
'privacypage'          => 'Project:Respekto de la privateco',

'badaccess'        => 'Vi ne havas sufiĉe da redaktorajtoj por tiu paĝo.',
'badaccess-group0' => 'Vi ne rajtas plenumi la agon, kiun vi petis.',
'badaccess-groups' => 'La ago, kiun vi petis, estas limigita al uzuloj en {{PLURAL:$2|la grupo|unu el la grupoj}}: $1.',

'versionrequired'     => 'Versio $1 de MediaWiki nepras',
'versionrequiredtext' => 'La versio $1 de MediaWiki estas necesa por uzi ĉi tiun paĝon. Vidu [[Special:Version|paĝon pri versio]].',

'ok'                      => 'Ek!',
'retrievedfrom'           => 'Elŝutita el  "$1"',
'youhavenewmessages'      => 'Por vi estas $1 ($2).',
'newmessageslink'         => 'nova mesaĝo',
'newmessagesdifflink'     => 'ŝanĝoj kompare kun antaŭlasta versio',
'youhavenewmessagesmulti' => 'Vi havas novajn mesaĝojn ĉe $1',
'editsection'             => 'redakti',
'editold'                 => 'redakti',
'viewsourceold'           => 'vidi fonttekston',
'editlink'                => 'redakti',
'viewsourcelink'          => 'vidi fontkodon',
'editsectionhint'         => 'Redakti sekcion: $1',
'toc'                     => 'Enhavo',
'showtoc'                 => 'montri',
'hidetoc'                 => 'kaŝi',
'thisisdeleted'           => 'Vidi aŭ restarigi $1?',
'viewdeleted'             => 'Ĉu rigardi $1?',
'restorelink'             => '{{PLURAL:$1|unu forigitan version|$1 forigitajn versiojn}}',
'feedlinks'               => 'RSS-fonto:',
'feed-invalid'            => 'Ia nevalida fonto.',
'feed-unavailable'        => 'Fontrilataj enfluoj ne estas haveblaj',
'site-rss-feed'           => '$1 RSS-fonto.',
'site-atom-feed'          => '$1 Atom-fonto',
'page-rss-feed'           => '"$1" RSS-fonto',
'page-atom-feed'          => '"$1" Atom-fonto',
'red-link-title'          => '$1 (paĝo ne ekzistas)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Paĝo',
'nstab-user'      => 'Paĝo de uzanto',
'nstab-media'     => 'Media paĝo',
'nstab-special'   => 'Speciala paĝo',
'nstab-project'   => 'Projektpaĝo',
'nstab-image'     => 'Dosiero',
'nstab-mediawiki' => 'Sistema mesaĝo',
'nstab-template'  => 'Ŝablono',
'nstab-help'      => 'Helpo',
'nstab-category'  => 'Kategorio',

# Main script and global functions
'nosuchaction'      => 'Ne ekzistas tia ago',
'nosuchactiontext'  => "La ago ('action') nomita de la URL estas nevalida.
Eble vi mistajpis la URL-on, aŭ sekvis nevalidan ligilon.
Eble ankaŭ ĉi tiel indikus problemon de la programaro de {{SITENAME}}.",
'nosuchspecialpage' => 'Ne ekzistas tia speciala paĝo',
'nospecialpagetext' => '<strong>Vi petis malvalidan specialan paĝon.</strong>

Listo de validaj specialaj paĝoj estas trovebla ĉe [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Eraro',
'databaseerror'        => 'Datumbaza eraro',
'dberrortext'          => 'Sintakseraro okazis dum informpeto al la datumaro.
Ĝi eble indikas cimon en la programaro.
Jen la plej laste provita informpeto:
<blockquote><tt>$1</tt></blockquote>
el la funkcio "<tt>$2</tt>".
MySQL liveris eraron "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Sintaksa eraro de la datumbaza informmendo okazis.
La lasta provita datumbaza informmendo estis:
"$1"
el la funkcio "$2".
Datumbazo liveris la erarmesaĝon "$3: $4".',
'laggedslavemode'      => 'Avertu: la paĝo eble ne enhavas lastatempajn ĝisdatigojn.',
'readonly'             => 'Datumaro ŝlosita, nurlega',
'enterlockreason'      => 'Bonvolu klarigi, kial oni ŝlosas la datumaron, kaj
la estimatan tempon de malŝlosado.',
'readonlytext'         => 'La datumaro de {{SITENAME}} estas nun ŝlosita kontraŭ
novaj aldonaj kaj aliaj ŝanĝoj, probable pro laŭkutima flegado de la datumaro.
Bonvolu reprovu post iom da tempo.

La ŝlosinto lasis la jenan mesaĝon:
<p>$1</p>',
'missing-article'      => 'La datumbazo ne trovis la tekston de paĝo kiun ĝi devas trovi, nomita "$1" $2.

Ĉi tiel ofte estas kaŭzite de sekvado de malfreŝa \'\'diff\'\' aŭ historia ligilo al paĝo kiu estis forigita.

Se ĉi tiel ne okazis, verŝajne vi trovis cimon en la softvaro.
Bonvolu raporti ĉi tiun al [[Special:ListUsers/sysop|administranto]], notante la TTT-adreson.',
'missingarticle-rev'   => '(versio#: $1)',
'missingarticle-diff'  => '(Diferenco inter versioj: $1, $2)',
'readonly_lag'         => 'La datumbazo estis aŭtomate ŝlosita dum la subdatumbazo atingas la ĉefan datumbazon.',
'internalerror'        => 'Interna eraro',
'internalerror_info'   => 'Interna eraro: $1',
'fileappenderror'      => 'Ne eblis postaldoni "$1" al "$2".',
'filecopyerror'        => 'Neeblis kopii dosieron  "$1" al "$2".',
'filerenameerror'      => 'Neeblis alinomi dosieron "$1" al "$2".',
'filedeleteerror'      => 'Neeblis forigi dosieron "$1".',
'directorycreateerror' => 'Ne povis krei dosierujon "$1".',
'filenotfound'         => 'Neeblis trovi dosieron "$1".',
'fileexistserror'      => 'Ne eblas skribi en la dosieron "$1": dosiero ekzistas',
'unexpected'           => 'Neatendita valoro: "$1"="$2".',
'formerror'            => 'Eraro: neeblis liveri formulon',
'badarticleerror'      => 'Tiun ĉi agon oni ne povas apliki al tiu ĉi artikolo.',
'cannotdelete'         => 'Neeblis forigi la elektitan paĝon aŭ dosieron.',
'badtitle'             => 'Fuŝa titolo',
'badtitletext'         => 'La petita paĝotitolo estas nevalida, malplena, aŭ
malĝuste ligita interlingva aŭ intervikia titolo.',
'perfcached'           => 'La sekvantaj informoj venas el kaŝmemoro kaj eble ne estas ĝisdataj :',
'perfcachedts'         => 'La jenaj datenoj estas provizoraj, kaj estis laste ĝisdatigitaj $1.',
'querypage-no-updates' => 'Ĝisdatigoj por ĉi paĝo estas nune neebligitaj. 
Datenoj ĉi tie ne estos nune refreŝigitaj.',
'wrong_wfQuery_params' => 'Malĝustaj parametroj por wfQuery()<br />
Funkcio: $1<br />
Peto: $2',
'viewsource'           => 'Rigardi vikitekston',
'viewsourcefor'        => 'por $1',
'actionthrottled'      => 'Agado limigita',
'actionthrottledtext'  => 'Por kontraŭigi spamon, vi estas limigita farante ĉi tiun agon tro pluroble en mallonga tempdaŭro, kaj vi plialtigis ĉi tiun limon. Bonvolu refaru post kelkaj minutoj.',
'protectedpagetext'    => 'Tiu ĉi paĝo estas ŝlosita por malebligi redaktadon.',
'viewsourcetext'       => 'Vi povas rigardi kaj kopii la fonton de la paĝo:',
'protectedinterface'   => 'Ĉi tiu paĝo provizas interfacan tekston por la softvaro, kaj estas ŝlosita por malabeligi misuzon.',
'editinginterface'     => "'''Atentu:''' Vi redaktas paĝon, kiu estas uzata kiel interfaca teksto por la softvaro. Ŝanĝoj de tiu ĉi teksto povas ŝanĝi aspekton de la interfaco por aliaj uzantoj. Por tradukojn, bonvolu uzi [http://translatewiki.net/wiki/Main_Page?setlang=eo translatewiki.net], la projekto por fari lokajn versiojn de MediaWiki.",
'sqlhidden'            => '(SQL serĉomendo kaŝita)',
'cascadeprotected'     => 'Ĉi tiu paĝo estas protektita kontraŭ redaktado, ĉar ĝi estas inkludita en la {{PLURAL:$1|sekvan paĝon, kiu|sekvajn paĝojn, kiuj}} estas {{PLURAL:$1|protektata|protektataj}} kun la "kaskada" opcio turnita sur:
$2',
'namespaceprotected'   => "Vi ne rajtas redakti paĝojn en la '''$1''' nomspaco.",
'customcssjsprotected' => 'Vi ne rajtas redakti ĉi tiun paĝon, ĉar ĝi enhavas personajn alĝustigojn de alia uzanto.',
'ns-specialprotected'  => 'Paĝoj en la {{ns:special}} nomspaco ne povas esti redaktataj.',
'titleprotected'       => "Ĉi titolo estas protektita de kreado de [[User:$1|$1]].
La kialo donata estis ''$2''.",

# Virus scanner
'virus-badscanner'     => "Malbona konfiguro: nekonata virusa skanilo: ''$1''",
'virus-scanfailed'     => 'skano malsukcesis (kun kodo $1)',
'virus-unknownscanner' => 'nekonata kontraŭviruso:',

# Login and logout pages
'logouttext'                 => "'''Vi nun estas elsalutita.'''

Vi rajtas daŭre vikiumi sennome, aŭ vi povas [[Special:UserLogin|reensaluti]] kiel la sama aŭ kiel alia uzanto.
Notu ke iuj paĝoj daŭre ŝajnos kvazaŭ vi ankoraŭ estas ensalutita, ĝis vi refreŝigu vian retumilan kaŝmemoron.",
'welcomecreation'            => '== Bonvenon, $1! ==
Via konto estas kreita.
Ne forgesu fari viajn [[Special:Preferences|{{SITENAME}}-preferojn]].',
'yourname'                   => 'Salutnomo:',
'yourpassword'               => 'Pasvorto:',
'yourpasswordagain'          => 'Retajpu pasvorton',
'remembermypassword'         => 'Rememori mian pasvorton',
'yourdomainname'             => 'Via domajno',
'externaldberror'            => 'Aŭ estis datenbaza eraro rilate al ekstera aŭtentikigado, aŭ vi ne rajtas ĝisdatigi vian eksteran konton.',
'login'                      => 'Ensaluti',
'nav-login-createaccount'    => 'Ensaluti / Krei novan konton',
'loginprompt'                => 'Via foliumilo nepre permesu kuketojn por ensaluti en la {{SITENAME}}.',
'userlogin'                  => 'Ensaluti / Krei novan konton',
'logout'                     => 'Elsaluti',
'userlogout'                 => 'Elsaluti',
'notloggedin'                => 'Ne ensalutinta',
'nologin'                    => "Ĉu vi ne havas konton? '''$1'''.",
'nologinlink'                => 'Krei konton',
'createaccount'              => 'Krei novan konton',
'gotaccount'                 => "Ĉu vi jam havas konton? '''$1'''.",
'gotaccountlink'             => 'Ensaluti',
'createaccountmail'          => 'retpoŝte',
'badretype'                  => 'La pasvortojn kiujn vi tajpis ne egalas.',
'userexists'                 => 'Salutnomo enigita jam estas uzata.
Bonvolu elekti alian nomon.',
'loginerror'                 => 'Ensaluta eraro',
'createaccounterror'         => 'Ne eblis krei konton: $1',
'nocookiesnew'               => 'La uzantokonto estis kreita sed vi ne estas ensalutinta. *** E-igo lcfirst {{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj ensalutu per viaj novaj salutnomo kaj pasvorto.',
'nocookieslogin'             => '{{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj provu denove.',
'noname'                     => 'Vi ne tajpis validan salutnomon.',
'loginsuccesstitle'          => 'Ensalutado sukcesis',
'loginsuccess'               => 'Vi ensalutis ĉe {{SITENAME}} kiel uzanto "$1".',
'nosuchuser'                 => 'Neniu uzanto havas nomon "$1".
Nomoj por uzantoj estas usklecodistinga.
Kontrolu vian literumadon, aŭ [[Special:UserLogin/signup|kreu novan konton]].',
'nosuchusershort'            => 'Ne ekzistas uzanto kun la nomo "<nowiki>$1</nowiki>". Bonvolu kontroli vian ortografion.',
'nouserspecified'            => 'Vi devas entajpi salutnomon.',
'wrongpassword'              => 'Vi tajpis malĝustan pasvorton. Bonvolu provi denove.',
'wrongpasswordempty'         => 'Vi tajpis malplenan pasvorton. Bonvolu provi denove.',
'passwordtooshort'           => 'Pasvortoj devas esti almenaŭ  $1 {{PLURAL:$1|1 signon|$1 signojn}}.',
'password-name-match'        => 'Via pasvorto devas nepre malsami vian salutnomon.',
'mailmypassword'             => 'Retpoŝti novan pasvorton',
'passwordremindertitle'      => 'Rememorigo el {{SITENAME}} pri perdita pasvorto',
'passwordremindertext'       => 'Iu (probable vi, el IP-adreso $1) petis novan
pasvorton por {{SITENAME}} ($4). Provizora pasvorto por uzanto
"$2" estis kreita kaj estis farita al "$3". Se ĉi tiu estis via
intencio, vi devos ensaluti kaj elekti novan pasvorton nun. Via provizora pasvorto malvalidiĝos post  {{PLURAL:$5|unu tago|$5 tagoj}}.

Se iu alia faris ĉi tiun peton, aŭ se vi estas rememorita vian pasvorton,
kaj ne plu volas ŝanĝi ĝin, vi povas ignori ĉi tiun mesaĝon kaj 
kontinui uzante vian malnovan pasvorton.',
'noemail'                    => 'Retpoŝtadreso ne estas registrita por uzanto "$1".',
'noemailcreate'              => 'Vi devas provizi validan retadreson',
'passwordsent'               => 'Oni sendis novan pasvorton al la retpoŝtadreso
registrita por "$1".
Bonvolu saluti denove ricevinte ĝin.',
'blocked-mailpassword'       => 'Via IP adreso estas forbarita de redaktado, kaj tial
ne rajtas uzi la pasvorto-rekovran funkcion por malebligi misuzon.',
'eauthentsent'               => 'Konfirma retmesaĝo estas sendita al la nomita retadreso. Antaŭ ol iu ajn alia mesaĝo estos sendita al la konto, vi devos sekvi la instrukciojn en la mesaĝo por konfirmi ke la konto ja estas la via.',
'throttled-mailpassword'     => 'Pasvorta rememorigilo estis jam sendita, ene de la {{PLURAL:$1|lasta $1 horo|lastaj $1 horoj}}. Por preventi misuzo, nur unu pasvorto-rememorigilo estos sendita po {{PLURAL:$1|$1 horo|$1 horoj}}.',
'mailerror'                  => 'Okazis eraro sendante retpoŝtaĵon: $1',
'acct_creation_throttle_hit' => 'Vizitintoj al ĉi tiu vikio uzintaj vian IP-adreson kreis {{PLURAL:$1|1 konton|$1 kontojn}} dum la lasta tago, kiu estas la maksimume permesita en ĉi tiu tempoperiodo.
Tial, vizitantoj kun ĉi tiu IP-adreso ne povas krei pluajn kontojn ĉi-momente.',
'emailauthenticated'         => 'Via retadreso estis aŭtentikigita ekde $2 $3.',
'emailnotauthenticated'      => 'Via retadreso <strong>ne jam estas aŭtentigata.</strong> Tial ne eblas elekti ajnan funkcion sube listigatan.',
'noemailprefs'               => 'Specifigu retpoŝtan adreson por ĉi tiuj ecoj funkcii.',
'emailconfirmlink'           => 'Konfirmu vian retpoŝtan adreson',
'invalidemailaddress'        => 'La retadreso ne povas esti akceptita, ĉar ĝi verŝajne havas nevalidan formaton.
Enigi bone formatita adreso aŭ malplenigi tiun kampon.',
'accountcreated'             => 'Konto kreita',
'accountcreatedtext'         => 'La uzanto-konto por $1 estas kreita.',
'createaccount-title'        => 'Konto-kreado por {{SITENAME}}',
'createaccount-text'         => 'Iu kreis konton por via retadreso en {{SITENAME}} ($4) nomata "$2", kun pasvorto "$3". Vi ensalutu kaj ŝanĝu vian pasvorton nun.

Vi povas ignori ĉi mesaĝon, se ĉi konto estis kreita erare.',
'login-throttled'            => 'Vi tro ofte provis eniri la pasvorton por ĉi tiu konto. 
Bonvolu ĝisatendi antaŭ retrovi.',
'loginlanguagelabel'         => 'Lingvo: $1',

# Password reset dialog
'resetpass'                 => 'Ŝanĝi pasvorton',
'resetpass_announce'        => 'Vi ensalutis kun provizora retpoŝtita pasvorto. Por kompleti ensalutadon, vi devas fari novan pasvorton ĉi tien:',
'resetpass_text'            => '<!-- Aldonu tekston ĉi tien -->',
'resetpass_header'          => 'Ŝanĝi kontan pasvorton',
'oldpassword'               => 'Malnova pasvorto',
'newpassword'               => 'Nova pasvorto',
'retypenew'                 => 'Retajpi novan pasvorton',
'resetpass_submit'          => 'Fari pasvorton kaj ensaluti',
'resetpass_success'         => 'Via pasvorto estis sukcese ŝanĝita! Nun ensalutanta vin...',
'resetpass_forbidden'       => 'Pasvortoj ne estas ŝanĝeblaj',
'resetpass-no-info'         => 'Vi devas ensaluti por atingi ĉi tiun paĝon rekte.',
'resetpass-submit-loggedin' => 'Ŝanĝi pasvorton',
'resetpass-wrong-oldpass'   => 'Nevalida provizora aŭ nuna pasvorto.
Vi eble jam ŝanĝis vian pasvorton aŭ petis novan provizoran pasvorton.',
'resetpass-temp-password'   => 'Provizora pasvorto:',

# Edit page toolbar
'bold_sample'     => 'Grasa teksto',
'bold_tip'        => 'Grasa teksto',
'italic_sample'   => 'Kursiva teksto',
'italic_tip'      => 'Kursiva teksto',
'link_sample'     => 'Ligtitolo',
'link_tip'        => 'Interna ligo',
'extlink_sample'  => 'http://www.example.com ligtitolo',
'extlink_tip'     => 'Ekstera ligo (memoru http:// prefikson)',
'headline_sample' => 'Titola teksto',
'headline_tip'    => 'Titololinio je dua nivelo',
'math_sample'     => 'Enmeti formulon ĉi tien',
'math_tip'        => 'Matematika formulo (LaTeX)',
'nowiki_sample'   => 'Enigi ne formatitan tekston ĉi tien',
'nowiki_tip'      => 'Ignori vikiformatadon',
'image_sample'    => 'Ekzemplo.jpg',
'image_tip'       => 'Enŝutita bildo',
'media_sample'    => 'Ekzemplo.ogg',
'media_tip'       => 'Ligo al dosiero sona ...',
'sig_tip'         => 'Via subskribo kun tempstampo',
'hr_tip'          => 'Horizontala linio (uzu ŝpareme)',

# Edit pages
'summary'                          => 'Resumo:',
'subject'                          => 'Temo/subtitolo:',
'minoredit'                        => 'Ĉi tiu ŝanĝo estas redakteto',
'watchthis'                        => 'Atenti ĉi tiun paĝon',
'savearticle'                      => 'Konservi ŝanĝojn',
'preview'                          => 'Antaŭrigardo',
'showpreview'                      => 'Antaŭrigardo',
'showlivepreview'                  => 'Aktiva antaŭvido',
'showdiff'                         => 'Montri ŝanĝojn',
'anoneditwarning'                  => 'Vi ne estas ensalutinta. Via IP-adreso enregistriĝos en la ŝango-historio de tiu ĉi paĝo.',
'missingsummary'                   => "'''Rememorigilo:''' Vi ne provizis redaktan resumon. Se vi alklakos denove la savan butonon, via redaktaĵo estos storata sen resumo.",
'missingcommenttext'               => 'Bonvolu entajpi komenton malsupre.',
'missingcommentheader'             => "'''Atento:''' Vi ne donis temo/subtitolo por ĉi tiu komento. 
Se vi klakos Konservi denove, via redakto estos konservita sen ĝi.",
'summary-preview'                  => 'Resuma antaŭrigardo:',
'subject-preview'                  => 'Antaŭrigardo de Temo/Subitolo:',
'blockedtitle'                     => 'La uzanto estas forbarita.',
'blockedtext'                      => "<big>'''Via konto aŭ IP-adreso estis forbarita'''</big> 

La forbaro estis farita de $1.
La skribita kialo estas ''$2''.

* Komenco de forbaro: $8
* Findato de forbarado: $6
* Intencita forbarito: $7

Vi rajtas kontakti $1 aŭ alian [[{{MediaWiki:Grouppage-sysop}}|administranton]] por pridiskuti la forbaradon.
Vi ne povas uzi la 'retpoŝtan' funkcion, escepte se vi indikis validan retpoŝtan adreson en viaj [[Special:Preferences|kontaj agordoj]] kaj vi ne estas blokita uzi ĝin.
Via IP-adreso estas $3 kaj la ID de la forbarado ests $5. 
Bonvolu mencii jenajn indikojn en viaj ĉi-temaj kontaktoj.",
'autoblockedtext'                  => 'Via IP-adreso estas aŭtomate forbarita, ĉar uzis ĝin alia uzanto, kiun baris $1.
La donita kialo estas:

:\'\'$2\'\'

*Komenco de forbaro: $8
*Limdato de la blokado: $6
*Intencias forbari uzanton: $7

Vi povas kontakti $1 aux iun ajn el la aliaj [[{{MediaWiki:Grouppage-sysop}}|administrantojn]] por diskuti la blokon.

Notu, ke vi ne povas uzi la servon "Retpoŝtu ĉi tiu uzanton" krom se vi havas validan retpost-adreson registritan en viaj [[Special:Preferences|preferojn]], kaj vi estas ne blokita kontraŭ ĝia uzado.

Via nuna IP-adreso estas $3, kaj la forbaro-identigo estas $5.
Bonvolu inkluzivi tiujn detalojn en iuj ajn demandoj kiun vi farus.',
'blockednoreason'                  => 'nenia kialo donata',
'blockedoriginalsource'            => "La fonto de '''$1''' estas montrata malsupre:",
'blockededitsource'                => "La teksto de '''viaj redaktoj''' al '''$1''' estas montrata malsupre:",
'whitelistedittitle'               => 'Ensalutado devigata por redakti',
'whitelistedittext'                => 'Vi devas $1 por redakti paĝojn.',
'confirmedittext'                  => 'Vi devas konfirmi vian retpoŝtan adreson antaŭ ol redakti paĝojn. Bonvolu agordi kaj validigi vian retadreson per viaj [[Special:Preferences|preferoj]].',
'nosuchsectiontitle'               => 'Ne tia sekcio',
'nosuchsectiontext'                => 'Vi provis redakti sekcion, kiu ne ekzistas. Ĉar ne estas sekcio $1, ne  estas loko por savi vian redakton.',
'loginreqtitle'                    => 'Nepre ensaluti',
'loginreqlink'                     => 'ensaluti',
'loginreqpagetext'                 => 'Vi devas $1 por rigardi aliajn paĝojn.',
'accmailtitle'                     => 'Pasvorto sendita.',
'accmailtext'                      => "Hazarde generita pasvorto por [[User talk:$1|$1]] estis sendita al $2.

La pasvorto por ĉi tiu nova konto povas esti ŝanĝita en la paĝo ''[[Special:ChangePassword|ŝanĝi pasvorton]]'' dum ensalutado.",
'newarticle'                       => '(Nova)',
'newarticletext'                   => 'Vi sekvis ligilon al paĝo jam ne ekzistanta. Se vi volas krei ĝin, ektajpu sube (vidu la [[{{MediaWiki:Helppage}}|helpopaĝo]] por klarigoj.) Se vi malintence alvenis ĉi tien, simple alklaku la retrobutonon de via retumilo.',
'anontalkpagetext'                 => "---- ''Jen diskutopaĝo por anonima kontribuanto kiu ne jam kreis konton aŭ ne uzas ĝin. 
Ni tial devas uzi la cifran IP-adreson por identigi lin/ŝin. 
Ĉi tia IP-adreso povas esti uzata de pluraj uzantoj.
Se vi estas anonimulo kaj preferus eviti tiajn mistrafajn komentojn al vi, bonvolu [[Special:UserLogin/signup|krei konton]] aŭ [[Special:UserLogin|ensaluti]] por eviti estontan konfuzon pro aliaj anonimaj uzantoj.''",
'noarticletext'                    => 'Mankas teksto en ĉi tiu paĝo.
Vi povas [[Special:Search/{{PAGENAME}}|serĉi ĉi tiun paĝtitolon]] en aliaj paĝoj,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} serĉi la rilatajn protokolojn],
aŭ [{{fullurl:{{FULLPAGENAME}}|action=edit}} redakti ĉi tiun paĝon]</span>.',
'noarticletext-nopermission'       => 'Estas neniom da teksto en ĉi tiu paĝo.
Vi povas [[Special:Search/{{PAGENAME}}|serĉi ĉi tiun paĝan titolon]] en aliaj paĝoj,
aŭ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} serĉi la rilatajn protokolojn]</span>.',
'userpage-userdoesnotexist'        => 'Uzula konto "$1" ne estas registrita. Bonvolu konfirmi se vi volas krei/redakti ĉi tiu paĝo.',
'userpage-userdoesnotexist-view'   => 'Uzanto-konto "$1" ne estas registrita.',
'clearyourcache'                   => "'''Notu:''' Post konservado vi forviŝu la kaŝmemoron de via foliumilo por vidi la ŝanĝojn : '''Mozilo:''' alklaku ''Reŝarĝi'' (aŭ ''Stir-Shift-R''), '''IE / Opera:''' ''Stir-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Stir-R''.",
'usercssyoucanpreview'             => "'''Konsileto:''' Uzu la \"Antaŭrigardan\" butonon por provi vian novan css/js antaŭ konservi.",
'userjsyoucanpreview'              => "'''Konsileto:''' Uzu la \"Antaŭrigardan\" butonon por provi vian novan css/js antaŭ konservi.",
'usercsspreview'                   => "'''Notu ke vi nur antaŭvidas vian uzanto-CSS.
Ĝi ne jam estis konservita!'''",
'userjspreview'                    => "'''Memoru ke vi nun nur provas kaj antaŭrigardas vian uzantan javaskripton, ĝi ne estas jam konservita'''",
'userinvalidcssjstitle'            => "'''Averto:''' Ne ekzistas aspekto \"\$1\". Rememoru ke individuaj .css-aj kaj .js-aj paĝoj uzas minusklan titolon, ekz. {{ns:user}}:Foo/monobook.css kontraŭe  al {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Ŝanĝo registrita)',
'note'                             => "'''Noto:'''",
'previewnote'                      => "'''Memoru, ke ĉi tio estas nur antaŭrigardo kaj ankoraŭ ne konservita!'''",
'previewconflict'                  => 'La jena antaŭrigardo montras la tekston el la supra tekstujo,
kiel ĝi aperos se vi elektos konservi la paĝon.',
'session_fail_preview'             => "'''Ni ne povas procezi vian redakton pro perdo de seancaj datenoj.
Bonvolu retrovi.
Se ankoraŭ ne funkcios, trovu [[Special:UserLogout|elsaluti]] kaj reensaluti.'''",
'session_fail_preview_html'        => "'''Ne eblas trakti vian redakton pro manko de seancaj datenoj.'''

''Ĉar {{SITENAME}} ebligas krudan HTML, ĉi tiu antaŭrigardo estas kaŝita kiel prevento kontraŭ Javascript-atakoj.''

'''Se ĉi tiu estas taŭga provo por redakti, bonvolu reprovi.
Se ankoraŭ ne funkcias, provu [[Special:UserLogout|elsaluti]] kaj reensaluti.'''",
'token_suffix_mismatch'            => "'''Via redakto estis malpermesita ĉar via klienta fuŝis la interpunkcio en la redakto-signo.
La redakto estis malpermesita por preventi koruptado de la teksto de la paĝo.
Ĉi tiel malofte okazas kiam vi uzas fuŝan TTT-an anoniman prokurilon.'''",
'editing'                          => 'Redaktante $1',
'editingsection'                   => 'Redaktante $1 (sekcion)',
'editingcomment'                   => 'Redaktante $1 (nova sekcio)',
'editconflict'                     => 'Redakta konflikto: $1',
'explainconflict'                  => 'Iu alia ŝanĝis la paĝon post kiam vi ekredaktis.
La supra tekstujo enhavas la aktualan tekston de la artikolo.
Viaj ŝanĝoj estas en la malsupra tekstujo.
Vi devas mem kunfandi viajn ŝanĝojn kaj la jaman tekston.
<b>Nur</b> la teksto en la supra tekstujo estos konservita kiam
vi alklakos "Konservi".<br />',
'yourtext'                         => 'Via teksto',
'storedversion'                    => 'Registrita versio',
'nonunicodebrowser'                => "'''ATENTU: Via foliumilo ne eltenas unikodon, bonvolu ŝanĝi ĝin antaŭ ol redakti artikolon.'''",
'editingold'                       => "'''AVERTO: Vi nun redaktas malnovan version de tiu ĉi artikolo.
Se vi konservos vian redakton, ĉiuj ŝanĝoj faritaj post tiu versio perdiĝos.'''",
'yourdiff'                         => 'Malsamoj',
'copyrightwarning'                 => "Bonvolu noti, ke ĉiu kontribuaĵo al la {{SITENAME}} estu rigardata kiel eldonita laŭ \$2 (vidu je \$1). Se vi volas, ke via verkaĵo ne estu redaktota senkompate kaj disvastigota laŭvole, ne alklaku \"Konservi\".<br />
Vi ankaŭ ĵuras, ke vi mem verkis la tekston, aŭ ke vi kopiis ĝin el fonto senkopirajta.
'''NE UZU KOPIRAJTAJN VERKOJN SENPERMESE!'''",
'copyrightwarning2'                => "Bonvolu noti ke ĉiuj kontribuoj al {{SITENAME}} povas esti reredaktita, ŝanĝita aŭ forigita de aliaj kontribuantoj. Se vi ne deziras ke viaj verkoj estu senkompate reredaktitaj, ne publikigu ilin ĉi tie.<br />
Vi ankaŭ promesu al ni ke vi verkis tion mem aŭ kopiis el publika domajno aŭ simila libera fonto (vidu $1 por detaloj).
'''NE PROPONU KOPIRAJTITAJN VERKOJN SEN PERMESO!'''",
'longpagewarning'                  => "'''AVERTO: Tiu ĉi paĝo longas $1 kilobitokojn; kelkaj retumiloj
povas fuŝi redaktante paĝojn je longo proksime aŭ preter 32 kb.
Se eble, bonvolu disigi la paĝon al malpli grandajn paĝerojn.'''",
'longpageerror'                    => "'''Eraro: La teksto, kiun vi prezentis, longas $1 kilobajtojn, kio estas pli longa ol la maksimumo de $2 kilobajtoj. Ĝi ne povas esti storata.'''",
'readonlywarning'                  => "'''AVERTO: La datumbazo estas ŝlosita por teknika laboro, do vi ne eblas konservi viajn redaktojn nune.
Vi eble volus elkopii kaj englui la tekston al tekstdosiero por konservi ĝin por posta uzo.'''

La administranto kiu ŝlosis ĝin donis ĉi tiun eksplikaĵon: $1",
'protectedpagewarning'             => "'''AVERTO: Tiu ĉi paĝo estas ŝlosita kontraŭ redaktado krom de administrantoj (t.e., vi). Bv certiĝi, ke vi sekvas la normojn de la komunumo per via redaktado.'''",
'semiprotectedpagewarning'         => "'''Notu:''' Ĉi paĝo estas protektita tiel ke nur ensalutintaj uzantoj povas redakti ĝin.",
'cascadeprotectedwarning'          => "'''Averto:''' Ĉi tiu paĝo estas ŝlosita tiel ke nur uzantoj kun administrantaj privilegioj povas redakti ĝin, ĉar ĝi estas inkludita en la {{PLURAL:$1|sekvan kaskade protektitan paĝon|sekvajn kaskade protektitajn paĝojn}}:",
'titleprotectedwarning'            => "'''AVERTO: Ĉi paĝo estis ŝlosita tial nur [[Special:ListGroupRights|specifaj rajtoj]] estas bezonaj por krei ĝin.'''",
'templatesused'                    => '{{PLURAL:$1|Ŝablono uzataj|Ŝablonoj uzataj}} en ĉi tiu paĝo:',
'templatesusedpreview'             => '{{PLURAL:$1|Ŝablono uzata|Ŝablonoj uzataj}} en ĉi tiu antaŭrigardo:',
'templatesusedsection'             => '{{PLURAL:$1|Ŝablono uzata|Ŝablonojuzataj}} en ĉi tiu sekcio:',
'template-protected'               => '(protektita)',
'template-semiprotected'           => '(duone protektita)',
'hiddencategories'                 => 'Ĉi tiu paĝo estas membro de {{PLURAL:$1|1 kaŝita kategorio|$1 kaŝitaj kategorioj}}:',
'edittools'                        => '<!-- Teksto ĉi tie estas montrata sub redaktaj kaj alŝutaj formularoj. -->',
'nocreatetitle'                    => 'Paĝa kreado estas limigita',
'nocreatetext'                     => '{{SITENAME}} restriktas la eblecon krei novajn paĝojn. Vi eblas reiri kaj redakti faritan paĝon, aŭ [[Special:UserLogin|ensaluti aŭ krei konton]].',
'nocreate-loggedin'                => 'Vi ne rajtas krei novajn paĝojn.',
'permissionserrors'                => 'Eraroj pri rajtoj',
'permissionserrorstext'            => 'Vi ne rajtas fari tion pro la {{PLURAL:$1|sekva kialo|sekvaj kialoj}}:',
'permissionserrorstext-withaction' => 'Vi ne rajtas $2, pro la {{PLURAL:$1|jena kialo|jenaj kialoj}}:',
'recreate-moveddeleted-warn'       => "'''Averto: Vi rekreas paĝon tiu estis antaŭe forigita.'''

Vi konsideru ĉu konvenas daŭre redakti ĉi paĝon.
Jen la protokolo de forigoj kaj alinomigado por via oportuno:",
'moveddeleted-notice'              => 'Ĉi tiu paĝo estis forigita. 
Jen la protokolo pri forigado kaj alinomigado por via referenco.',
'log-fulllog'                      => 'Vidi kompletan protokolon',
'edit-hook-aborted'                => 'Redakto ĉesigis per hoko.
Ĝi ne donis eksplikon.',
'edit-gone-missing'                => 'Ne eblis ĝisdatigi la paĝon.
Verŝajne ĝi estis forigita.',
'edit-conflict'                    => 'Redakto-konflikto.',
'edit-no-change'                   => 'Via redakto estis ignorita, ĉar neniu ŝanĝo estis farita al la teksto.',
'edit-already-exists'              => 'Ne eblis krei novan paĝon.
Ĝi jam ekzistas.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Averto: Ĉi tiu paĝo enhavas tro da multekostaj sintaksaj funkcio-vokoj.

Ĝi havu malpli ol $2 {{PLURAL:$2|vokon|vokojn}}, sed nun estas $1 {{PLURAL:$1|voko|vokoj}}.',
'expensive-parserfunction-category'       => 'Paĝoj kun tro da multekostaj sintaksaj funkcio-vokoj',
'post-expand-template-inclusion-warning'  => 'Averto: Inkluziva pezo de ŝablonoj estas tro granda.
Iuj ŝablonoj ne estos inkluzivitaj.',
'post-expand-template-inclusion-category' => 'Paĝoj kie inkluziva pezo de ŝablonoj estas tro granda.',
'post-expand-template-argument-warning'   => 'Averto: Ĉit tiu paĝo enhavas almenaŭ unu ŝablonan argumenton kiu havas tro grandan etendan pezon.
Ĉi tiuj argumentoj estis forlasitaj.',
'post-expand-template-argument-category'  => 'Paĝoj enhavantaj forlasitajn argumentojn de ŝablonoj',
'parser-template-loop-warning'            => 'Rekursiva ŝablono estis trovita: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limo de ŝablona profundeco pligrandiĝis ($1)',

# "Undo" feature
'undo-success' => 'La redakto estas malfarebla.
Bonvolu konfirmi la jenan komparaĵon por verigi ĉi tiel vi volas, kaj konservi la ŝanĝojn suben fini malfarante la redakton.',
'undo-failure' => 'Ne eblas nuligi redakton pro konfliktaj intermezaj redaktoj.',
'undo-norev'   => 'La redakto ne eblis esti malfarita ĉar ĝi aŭ ne ekzistas aŭ estis forigita.',
'undo-summary' => 'Nuligis version $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskuto]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ne eblas krei konton',
'cantcreateaccount-text' => "Konto-kreado de ĉi tiu IP-adreso ('''$1''') estis forbarita de [[User:$3|$3]].

La kialo donata de $3 estas ''$2''.",

# History pages
'viewpagelogs'           => 'Rigardi la protokolojn por tiu ĉi paĝo',
'nohistory'              => 'Ne ekzistas historio de redaktoj por ĉi tiu paĝo.',
'currentrev'             => 'Aktuala versio',
'currentrev-asof'        => 'Nuna versio ekde $1',
'revisionasof'           => 'Kiel registrite je $1',
'revision-info'          => 'Redakto de $1 de $2',
'previousrevision'       => '← Antaŭa versio',
'nextrevision'           => 'Sekva versio →',
'currentrevisionlink'    => 'Rigardi nunan version',
'cur'                    => 'nuna',
'next'                   => 'sekv',
'last'                   => 'antaŭa',
'page_first'             => 'unua',
'page_last'              => 'lasta',
'histlegend'             => 'Klarigo: (nuna) = vidu malsamojn kompare kun la nuna versio, (antaŭa) = malsamojn kompare kun la antaŭa versio, <strong>E</strong> = malgranda redakteto',
'history-fieldset-title' => 'Traserĉi historion',
'histfirst'              => 'plej frua',
'histlast'               => 'plej lasta',
'historysize'            => '({{PLURAL:$1|1 bajto|$1 bajtoj}})',
'historyempty'           => '(malplena)',

# Revision feed
'history-feed-title'          => 'Historio de redaktoj',
'history-feed-description'    => 'Revizia historio por ĉi tiu paĝo en la vikio',
'history-feed-item-nocomment' => '$1 ĉe $2',
'history-feed-empty'          => 'La petita paĝo ne ekzistas.
Ĝi verŝajne estis forigita de la vikio, aŭ alinomita.
Provu [[Special:Search|serĉi en la vikio]] por rilataj novaj paĝoj.',

# Revision deletion
'rev-deleted-comment'         => '(komento forigita)',
'rev-deleted-user'            => '(uzanto-nomo forigita)',
'rev-deleted-event'           => '(protokola ago forigita)',
'rev-deleted-text-permission' => "Ĉi tiu revizio de la paĝo estis '''forigita'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri forigado].",
'rev-deleted-text-unhide'     => "Ĉi tiu revizio de la paĝo estis '''forigita'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri forigado].
Kiel administranto, vi ankoraŭ povas [$1 vidi ĉi tiun revizio] se vi volas kontinui.",
'rev-suppressed-text-unhide'  => "Ĉi tiu paĝa revizio estis '''subpremita'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri subpremado].
Kiel administranto, vi povas ankoraŭ [$1 vidi ĉi tiun revizion] se vi volas kontinui.",
'rev-deleted-text-view'       => "Ĉi tiu revizio de la paĝo estis '''forigita'''.
Kiel administranto vi povas rigardi ĝin; eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri forigado].",
'rev-suppressed-text-view'    => "Ĉi tiu paĝa revizio estis '''subpremita'''.
Kiel administranto, vi povas vidi ĝin; eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri subpremado].",
'rev-deleted-no-diff'         => "Vi ne povas vidi ĉi tiun diferencon ĉar unu el la revizioj estis '''forigitaj'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri forigado].",
'rev-deleted-unhide-diff'     => "Unu el la revizioj de ĉi tiu diferenco estis '''forigita'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} protokolo pri forigado].
Kiel administranto vi povas ankoraŭ [$1 vidi ĉi tiun diferencon] se vi volas kontinui.",
'rev-suppressed-unhide-diff'  => "Unu el la revizioj de ĉi tiu diferenco estis '''subpremita'''.
Eble estas detaloj en la [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} subpremada protokolo].
Kiel administranto, vi povas ankoraŭ [$1 vidi ĉi tiun diferencon] se vi volus.",
'rev-delundel'                => 'montri/kaŝi',
'revisiondelete'              => 'Forigi/malforigi versiojn',
'revdelete-nooldid-title'     => 'Nevalida cela versio',
'revdelete-nooldid-text'      => 'Vi ne specifis celan version aŭ versiojn fari ĉi tiun 
funkcion, la specifigita versio ne ekzistas, aŭ vi estas provanta kaŝi la nunan version.',
'revdelete-nologtype-title'   => 'Neniu protokol-tipo estis donita',
'revdelete-nologtype-text'    => 'Vi ne specifigis protokolan tipon por fari ĉi tiun funkcion.',
'revdelete-nologid-title'     => 'Nevalida protokol-linio',
'revdelete-nologid-text'      => 'Vi aŭ ne specifis celan protokolan eventon por fari ĉi tiun funkcion aŭ la specifa protokolero ne ekzistas.',
'revdelete-no-file'           => 'La dosiero specifigita ne ekzistas.',
'revdelete-show-file-confirm' => 'Ĉu vi certas ke vi volas vidi forigitan revizion de la dosiero "<nowiki>$1</nowiki>" de $2, $3?',
'revdelete-show-file-submit'  => 'Jes',
'revdelete-selected'          => "'''{{PLURAL:$2|Selektata versio|Selektataj versioj}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Selektata protokola evento|Selektataj protokolaj eventoj}}:'''",
'revdelete-text'              => "'''Forigitaj versioj kaj eventoj plu aperos en la historipaĝoj, sed iliaj tekstoj ne estos alireblaj de la publiko.'''

Aliaj administrantoj ĉe {{SITENAME}} plu povos aliri la kaŝitan entenon kaj restarigi ĝin per la sama interfaco, krom se plia limigo estas metita de la paĝaradministrantoj.
Bonvolu konfirmi ke vi intencias fari ĉi tiel, ke vi komprenas la konsekvencojn, kaj ke vi faras ĉi tiel laŭ [[{{MediaWiki:Policy-url}}|la regularo]].",
'revdelete-suppress-text'     => "Subpremo '''nur''' estu uzata por la jenaj kazoj:
* Netaŭga persona informo
*: ''hejmaj adresoj kaj telefonnumeroj, ŝtataj identnumeroj, ktp.''",
'revdelete-legend'            => 'Fari videblecajn limigojn',
'revdelete-hide-text'         => 'Kaŝi tekston de versio',
'revdelete-hide-name'         => 'Kaŝi agon kaj celon',
'revdelete-hide-comment'      => 'Kaŝi komenton de redakto',
'revdelete-hide-user'         => 'Kaŝi nomon aŭ IP-adreson de redaktinto',
'revdelete-hide-restricted'   => 'Subpremi ĉi tiujn datenojn de administrantoj kaj ankaŭ aliaj',
'revdelete-suppress'          => 'Subpremi datenojn de kaj administrantoj kaj aliaj',
'revdelete-hide-image'        => 'Kaŝi enhavon de dosieroj',
'revdelete-unsuppress'        => 'Forigi limigojn al restarigitaj versioj',
'revdelete-log'               => 'Kialo por forigado:',
'revdelete-submit'            => 'Apliki al la selektita versio',
'revdelete-logentry'          => 'ŝanĝis videblecon de versio por [[$1]]',
'logdelete-logentry'          => 'ŝanĝis eventan videblecon de [[$1]]',
'revdelete-success'           => "'''Revizia videbleco estas sukcese farita.'''",
'revdelete-failure'           => "'''Videblecon de revizio ne eblis agordi:'''
$1",
'logdelete-success'           => "'''Videbleco de evento sukcese farita.'''",
'logdelete-failure'           => "'''Protokola videbleco ne estis akordebla:'''
$1",
'revdel-restore'              => 'Ŝanĝi videblecon',
'pagehist'                    => 'Paĝa historio',
'deletedhist'                 => 'Forigita historio',
'revdelete-content'           => 'enhavo',
'revdelete-summary'           => 'redakti resumon',
'revdelete-uname'             => 'salutnomo',
'revdelete-restricted'        => 'aplikis limojn al administrantoj',
'revdelete-unrestricted'      => 'forigis limojn por administrantoj',
'revdelete-hid'               => 'kaŝis $1',
'revdelete-unhid'             => 'malkaŝis $1',
'revdelete-log-message'       => '$1 por $2 {{PLURAL:$2|versio|versioj}}',
'logdelete-log-message'       => '$1 por $2 {{PLURAL:$2|evento|eventoj}}',
'revdelete-hide-current'      => 'Eraro kaŝante la aĵon de $2, $1: ĉi tiu estas la nuna revizio.
Ĝi ne estas kaŝebla.',
'revdelete-show-no-access'    => 'Eraro montrante la aĵon de $2, $1: ĉi tiu estas markita "limigita".
Vi ne rajtas atingi ĝin.',
'revdelete-modify-no-access'  => 'Eraro ŝanĝante la aĵon de $2, $1: ĉi tiu estas markita "limigita".
Vi ne rajtas atingi ĝin.',
'revdelete-modify-missing'    => 'Eraro ŝanĝante aĵon ID $1: ĝi ne estas trovita en la datumbazo!',
'revdelete-no-change'         => "'''Averto:''' la aĵo de $2, $1 jam havas la petitan videblecan agordon.",
'revdelete-concurrent-change' => 'Eraro ŝanĝante la aĵon de $2, $1: ĝia statuso estis ŝanĝita de alia uzanto dume dum vi provis ŝanĝi ĝin.
Bonvolu kontroli la protokolojn.',
'revdelete-only-restricted'   => 'Vi ne povas subpremi aĵojn de vido de administrantoj sen ankaux selekti unu el la aliaj subpremo-opcioj.',
'revdelete-reason-dropdown'   => '*Oftaj kialoj por forigado
** Kopirajta malobservo
** Netaŭga persona informo',
'revdelete-otherreason'       => 'Alia/aldona kialo:',
'revdelete-reasonotherlist'   => 'Alia kialo',
'revdelete-edit-reasonlist'   => 'Redakti kialojn por forigo',
'revdelete-offender'          => 'Aŭtoro de revizio:',

# Suppression log
'suppressionlog'     => 'Protokolo pri subigado',
'suppressionlogtext' => 'Jen listo de forigoj kaj forbaroj pri enhavo kaŝita per administrantoj. 
Rigardu la [[Special:IPBlockList|IP-forbarliston]] por la listo de nune operaciaj forbaroj kaj forigoj.',

# History merging
'mergehistory'                     => 'Kunfandigi historiojn de paĝoj',
'mergehistory-header'              => 'Ĉi tiu paĝo permesas al vi kunigi versiojn de la historio de unu fonta paĝo en pli novan paĝon.
Certigu ke ĉi tiu ŝanĝo tenos kontinuecon de la historia paĝo.',
'mergehistory-box'                 => 'Kunigi versiojn de du paĝoj:',
'mergehistory-from'                => 'Fontpaĝo:',
'mergehistory-into'                => 'Celpaĝo:',
'mergehistory-list'                => 'Kunigebla redakthistorio',
'mergehistory-merge'               => 'La jenaj versioj de [[:$1]] povas esti kunigitaj en [[:$2]]. Uzu la radio-butonan kolumnon por enkunigi nur la versiojn kreitajn ĉe kaj antaŭ la specifigita tempo. Notu ke uzado de navigado-ligiloj restarigos ĉi tiun kolumnon.',
'mergehistory-go'                  => 'Montri kunigeblajn redaktojn',
'mergehistory-submit'              => 'Kunigi versiojn',
'mergehistory-empty'               => 'Neniuj versioj estas kunigeblaj.',
'mergehistory-success'             => '$3 {{PLURAL:$3|versio|versioj}} de [[:$1]] sukcese {{PLURAL:$3|kunigita|kunigitaj}} en [[:$2]].',
'mergehistory-fail'                => 'Ne eblas fari la historian kunigon; bonvolu konstati la paĝon kaj tempajn parametrojn.',
'mergehistory-no-source'           => 'Fontpaĝo $1 ne ekzistas.',
'mergehistory-no-destination'      => 'Celpaĝo $1 ne ekzistas.',
'mergehistory-invalid-source'      => 'Fontpaĝo devas esti valida titolo.',
'mergehistory-invalid-destination' => 'Celpaĝo devas esti valida titolo.',
'mergehistory-autocomment'         => 'Kunigita [[:$1]] en [[:$2]]',
'mergehistory-comment'             => 'Kunigita [[:$1]] en [[:$2]]: $3',
'mergehistory-same-destination'    => 'Fontaj kaj destinaj paĝoj ne povas esti la samon',
'mergehistory-reason'              => 'Kialo:',

# Merge log
'mergelog'           => 'Protokolo de kunigoj',
'pagemerge-logentry' => 'kunigis [[$1]] en [[$2]] (versioj ĝis $3)',
'revertmerge'        => 'Malkunigi',
'mergelogpagetext'   => 'Jen listo de la plej lastatempaj kunigoj de unu paĝhistorio en alian.',

# Diffs
'history-title'            => 'Redakto-historio de "$1"',
'difference'               => '(Malsamoj inter versioj)',
'lineno'                   => 'Linio $1:',
'compareselectedversions'  => 'Kompari la elektitajn versiojn',
'showhideselectedversions' => 'Montri/kaŝi elektitajn versiojn',
'visualcomparison'         => 'Vida komparo',
'wikicodecomparison'       => 'Vikiteksta komparo',
'editundo'                 => 'malfari',
'diff-multi'               => '({{PLURAL:$1|Unu meza versio|$1 mezaj versioj}} ne montrata.)',
'diff-movedto'             => 'alnomita al $1',
'diff-styleadded'          => '$1 stilo aldoniĝis',
'diff-added'               => '$1 aldoniĝis',
'diff-changedto'           => 'ŝanĝiĝis al $1',
'diff-movedoutof'          => 'movita el $1',
'diff-styleremoved'        => '$1 stilo foriĝis',
'diff-removed'             => '$1 foriĝis',
'diff-changedfrom'         => 'ŝanĝiĝis de $1',
'diff-src'                 => 'fonto',
'diff-withdestination'     => 'kun destino $1',
'diff-with'                => '&#32;kun $1 $2',
'diff-with-final'          => '&#32;kaj $1 $2',
'diff-width'               => 'larĝeco',
'diff-height'              => 'alteco',
'diff-p'                   => "'''paragrafo'''",
'diff-blockquote'          => "'''citaĵo'''",
'diff-h1'                  => "'''titolo (nivelo 1)'''",
'diff-h2'                  => "'''titolo (nivelo 2)'''",
'diff-h3'                  => "'''titolo (nivelo 3)'''",
'diff-h4'                  => "'''titolo (nivelo 4)'''",
'diff-h5'                  => "'''titolo (nivelo 5)'''",
'diff-pre'                 => "'''antaŭformatita sekcio'''",
'diff-div'                 => "'''divizio'''",
'diff-ul'                  => "'''senorda listo'''",
'diff-ol'                  => "'''ordigita listo'''",
'diff-li'                  => "'''listano'''",
'diff-table'               => "'''tabelo'''",
'diff-tbody'               => "'''enhavo de tabelo'''",
'diff-tr'                  => "'''vico'''",
'diff-td'                  => "'''ĉelo'''",
'diff-th'                  => "'''tabela titolo'''",
'diff-br'                  => "'''vertikala spaceto'''",
'diff-hr'                  => "'''horizonta linio'''",
'diff-code'                => "'''sekcio de komputika kodo'''",
'diff-dl'                  => "'''difina listo'''",
'diff-dt'                  => "'''defina termino'''",
'diff-dd'                  => "'''difino'''",
'diff-input'               => "'''enigo'''",
'diff-form'                => "'''kamparo'''",
'diff-img'                 => "'''bildo'''",
'diff-span'                => "'''grupigo (span)'''",
'diff-a'                   => "'''ligilo'''",
'diff-i'                   => "'''kursiva'''",
'diff-b'                   => "'''grasigita'''",
'diff-strong'              => "'''forta'''",
'diff-em'                  => "'''emfazita'''",
'diff-font'                => "'''tiparo'''",
'diff-big'                 => "'''granda'''",
'diff-del'                 => "'''forigita'''",
'diff-tt'                  => "'''fiksite larĝa tiparo'''",
'diff-sub'                 => "'''subindico'''",
'diff-sup'                 => "'''supraindico'''",
'diff-strike'              => "'''trostrekita'''",

# Search results
'searchresults'                    => 'Serĉrezultoj',
'searchresults-title'              => 'Serĉrezultoj por "$1"',
'searchresulttext'                 => 'Por pliaj informoj kiel priserĉi la {{SITENAME}}n, vidu [[{{MediaWiki:Helppage}}|serĉi en {{SITENAME}}]].',
'searchsubtitle'                   => 'Vi serĉis "\'\'\'[[$1]]\'\'\'" ([[Special:Prefixindex/$1|ĉiuj paĝoj komence de "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ĉiuj paĝoj ligantaj al "$1"]])',
'searchsubtitleinvalid'            => 'Serĉmendo "$1"',
'noexactmatch'                     => '<b>Ne estas paĝo titolita "$1".</b> Vi povas [[:$1|krei la paĝon]].',
'noexactmatch-nocreate'            => "'''Estas neniu paĝo titolita \"\$1\".'''",
'toomanymatches'                   => 'Tro da serĉo-trafoj estis trovitaj; bonvolu provi malsaman serĉomendon.',
'titlematches'                     => 'Trovitaj laŭ titolo',
'notitlematches'                   => 'Neniu trovita laŭ titolo',
'textmatches'                      => 'Trovitaj laŭ enhavo',
'notextmatches'                    => 'Neniu trovita laŭ enhavo',
'prevn'                            => '{{PLURAL:$1|$1 antaŭa|$1 antaŭaj}}',
'nextn'                            => '{{PLURAL:$1|$1 sekva|$1 sekvaj}}',
'prevn-title'                      => '{{PLURAL:$1|Antaŭa $1 rezulto|Antaŭaj $1 rezultoj}}',
'nextn-title'                      => '{{PLURAL:$1|Posta $1 rezulto|Postaj $1 rezultoj}}',
'shown-title'                      => 'Montri {{PLURAL:$1|$1 rezulton|$1 rezultojn}} en paĝo',
'viewprevnext'                     => 'Montri ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Serĉaj opcioj',
'searchmenu-exists'                => "* Paĝo '''[[$1]]'''",
'searchmenu-new'                   => "'''Krei la paĝon \"[[:\$1]]\" en ĉi tiu vikio!'''",
'searchhelp-url'                   => 'Help:Enhavo',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Traserĉi paĝojn kun ĉi tiu prefikso]]',
'searchprofile-articles'           => 'Enhavaj paĝoj',
'searchprofile-project'            => 'Paĝoj pri Helpo kaj Projektoj',
'searchprofile-images'             => 'Plurmedio',
'searchprofile-everything'         => 'Ĉio',
'searchprofile-advanced'           => 'Progresa',
'searchprofile-articles-tooltip'   => 'Serĉo en $1',
'searchprofile-project-tooltip'    => 'Serĉo en $1',
'searchprofile-images-tooltip'     => 'Serĉi dosierojn',
'searchprofile-everything-tooltip' => 'Traserĉi ĉiun enhavon (inkluzivante diskuto-paĝojn)',
'searchprofile-advanced-tooltip'   => 'Serĉi en specialaj nomspacoj',
'search-result-size'               => '$1 ({{PLURAL:$2|1 vorto|$2 vortoj}})',
'search-result-score'              => 'Trafeco: $1%',
'search-redirect'                  => '(alidirektilo $1)',
'search-section'                   => '(sekcio $1)',
'search-suggest'                   => 'Ĉu vi intenciis: $1',
'search-interwiki-caption'         => 'Kunprojektoj',
'search-interwiki-default'         => '$1 rezultoj:',
'search-interwiki-more'            => '(plu)',
'search-mwsuggest-enabled'         => 'kun sugestoj',
'search-mwsuggest-disabled'        => 'sen sugestoj',
'search-relatedarticle'            => 'Relataj',
'mwsuggest-disable'                => 'Malŝalti AJAX-sugestojn',
'searcheverything-enable'          => 'Traserĉi ĉiujn nomspacojn',
'searchrelated'                    => 'rilataj',
'searchall'                        => 'ĉiuj',
'showingresults'                   => "Montras {{PLURAL:$1|'''1''' trovitan|'''$1''' trovitajn}} ekde la #'''$2'''-a.",
'showingresultsnum'                => "Montras {{PLURAL:$3|'''1''' trovitan|'''$3''' trovitajn}} ekde la #'''$2'''-a.",
'showingresultsheader'             => "{{PLURAL:$5|Rezulto '''$1''' el '''$3'''|Rezultoj '''$1 – $2''' el '''$3'''}} por '''$4'''",
'nonefound'                        => "'''Notu''':  Nur kelkaj nomspacoj estas serĉitaj defaulte.
Provu prefiksi vian mendon kun ''all:'' por serĉi ĉiun enhavon (inkluzivante diskuto-paĝojn, ŝablonojn, ktp), aŭ uzi la deziritan nomspacon kiel prefikson.",
'search-nonefound'                 => 'La serĉomendo rezultis kun neniuj trafoj.',
'powersearch'                      => 'Progresa trovilo',
'powersearch-legend'               => 'Progresa serĉo',
'powersearch-ns'                   => 'Serĉi en nomspacoj:',
'powersearch-redir'                => 'Listigi alidirektilojn',
'powersearch-field'                => 'Serĉi',
'powersearch-togglelabel'          => 'Kontroli:',
'powersearch-toggleall'            => 'Ĉio',
'powersearch-togglenone'           => 'Nenio',
'search-external'                  => 'Ekstera serĉo',
'searchdisabled'                   => '<p>Oni provizore malŝaltis serĉadon per la plenteksta
indekso pro troŝarĝita servilo. Intertempe, vi povas serĉi per <i>guglo</i> aŭ per <i>jahu!</i>:</p>',

# Quickbar
'qbsettings'               => 'Preferoj pri ilaro',
'qbsettings-none'          => 'Neniu',
'qbsettings-fixedleft'     => 'Fiksiĝas maldekstre',
'qbsettings-fixedright'    => 'Fiksiĝas dekstre',
'qbsettings-floatingleft'  => 'Ŝvebas maldekstre',
'qbsettings-floatingright' => 'Ŝvebas dekstre',

# Preferences page
'preferences'                   => 'Preferoj',
'mypreferences'                 => 'Miaj preferoj',
'prefs-edits'                   => 'Nombro de redaktoj:',
'prefsnologin'                  => 'Ne jam salutis!',
'prefsnologintext'              => 'Vi devas esti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ensalutita]</span> por fari viajn preferojn.',
'changepassword'                => 'Ŝanĝi pasvorton',
'prefs-skin'                    => 'Etoso',
'skin-preview'                  => 'Antaŭrigardo',
'prefs-math'                    => 'Matematikaĵoj',
'datedefault'                   => 'Nenia prefero',
'prefs-datetime'                => 'Dato kaj horo',
'prefs-personal'                => 'Datenoj pri uzanto',
'prefs-rc'                      => 'Lastaj ŝanĝoj',
'prefs-watchlist'               => 'Atentaro',
'prefs-watchlist-days'          => 'Nombro de tagoj montri en la atentaro:',
'prefs-watchlist-days-max'      => '(maksimume 7 tagoj)',
'prefs-watchlist-edits'         => 'Maksimuma nombro de ŝanĝoj montrendaj en ekspandita atentaro:',
'prefs-watchlist-edits-max'     => '(maksimuma nombro: 1000)',
'prefs-watchlist-token'         => 'Atentara ĵetono:',
'prefs-misc'                    => 'Miksitaĵoj',
'prefs-resetpass'               => 'Ŝanĝi pasvorton',
'prefs-email'                   => 'Retpoŝtaj opcioj',
'prefs-rendering'               => 'Aspekto',
'saveprefs'                     => 'Konservi preferojn',
'resetprefs'                    => 'Forviŝi nekonservitajn ŝanĝojn',
'restoreprefs'                  => 'Restarigi ĉiujn defaŭltajn preferojn',
'prefs-editing'                 => 'Grandeco de redakta tekstujo',
'prefs-edit-boxsize'            => 'Grandeco de la redakto-kesko.',
'rows'                          => 'Linioj:',
'columns'                       => 'Kolumnoj:',
'searchresultshead'             => 'Serĉi',
'resultsperpage'                => 'Montri trovitajn po',
'contextlines'                  => 'Montri liniojn el paĝoj po:',
'contextchars'                  => 'Montri literojn el linioj ĝis po:',
'stub-threshold'                => 'Ago-sojlo por formatigo de <a href="#" class="stub">ligil-ĝermo (anglalingve: "stub link")</a> (bitikoj):',
'recentchangesdays'             => 'Tagoj montrendaj en lastaj ŝanĝoj:',
'recentchangesdays-max'         => '(maksimume $1 {{PLURAL:$1|tago|tagoj}})',
'recentchangescount'            => 'Nombro de redaktoj por montri defaŭlte:',
'prefs-help-recentchangescount' => 'Ĉi tiu inkluzivas lastajn ŝanĝojn, paĝajn historiojn, kaj protokolojn.',
'prefs-help-watchlist-token'    => 'Plenumado de ĉi tiu kampo kun sekreta ŝlosilo generos RSS-fonto por via atentaro.
Iu kiu scias la ŝlosilo en ĉi tiu kampo eblos legi vian atentaron, do elekti sekuran valoron.
Jen hazarde generita valoron por via uzo: $1',
'savedprefs'                    => 'Viaj preferoj estas konservitaj.',
'timezonelegend'                => 'Horzono:',
'localtime'                     => 'Loka tempo:',
'timezoneuseserverdefault'      => 'Uzi defaŭlton de servilo',
'timezoneuseoffset'             => 'Alia (Enigu diferencon)',
'timezoneoffset'                => 'Diferenco¹:',
'servertime'                    => 'Servila tempo:',
'guesstimezone'                 => 'Plenigita el la foliumilo',
'timezoneregion-africa'         => 'Afriko',
'timezoneregion-america'        => 'Ameriko',
'timezoneregion-antarctica'     => 'Antarkto',
'timezoneregion-arctic'         => 'Arkto',
'timezoneregion-asia'           => 'Azio',
'timezoneregion-atlantic'       => 'Atlantiko',
'timezoneregion-australia'      => 'Aŭstralio',
'timezoneregion-europe'         => 'Eŭropo',
'timezoneregion-indian'         => 'Hinda Oceano',
'timezoneregion-pacific'        => 'Pacifiko',
'allowemail'                    => 'Rajtigi retmesaĝojn de aliaj uzantoj',
'prefs-searchoptions'           => 'Serĉi opciojn',
'prefs-namespaces'              => 'Nomspacoj',
'defaultns'                     => 'Alimaniere, traserĉi la jenajn nomspacojn:',
'default'                       => 'defaŭlte',
'prefs-files'                   => 'Dosieroj',
'prefs-custom-css'              => 'Propra CSS',
'prefs-custom-js'               => 'Propra JS',
'prefs-reset-intro'             => 'Vi povas uzi ĉi tiun paĝon por restarigi viajn agordojn al la originalaj defaŭltoj.
Ĉi tiel ne estus malfarebla.',
'prefs-emailconfirm-label'      => 'Retpoŝta konfirmado:',
'prefs-textboxsize'             => 'Grandeco de redakta fenestro',
'youremail'                     => 'Retadreso:',
'username'                      => 'Salutnomo:',
'uid'                           => 'Uzantnumero:',
'prefs-memberingroups'          => 'Ano de {{PLURAL:$1|grupo|grupoj}}:',
'prefs-registration'            => 'Tempo de registrado:',
'yourrealname'                  => 'Vera nomo:',
'yourlanguage'                  => 'Lingvo',
'yourvariant'                   => 'Varianto',
'yournick'                      => 'Subskribo:',
'prefs-help-signature'          => 'Komentoj en diskuto-paĝoj estu subskribita kun "<nowiki>~~~~</nowiki>" kiu estos konvertita al via subskribo kaj tempindiko.',
'badsig'                        => 'Via kaŝnomo (por subskriboj) malvalidas. Bv. kontroli la HTML-etikedojn!',
'badsiglength'                  => 'La subskribo estas tro longa.
Ĝi devas esti sub $1 {{PLURAL:$1|signo|signoj}}.',
'yourgender'                    => 'Sekso:',
'gender-unknown'                => 'Nespecifita',
'gender-male'                   => 'Vira',
'gender-female'                 => 'Ina',
'prefs-help-gender'             => 'Nedeviga: uzita por sekseca salutado de la programaro. Ĉi tiu informo estos publika.',
'email'                         => 'Retadreso',
'prefs-help-realname'           => '* Vera nomo (opcia): se vi elektas sciigi ĝin, ĝi estos uzita por aŭtorigi vin pri viaj kontribuoj.',
'prefs-help-email'              => 'Retadreso estas nedeviga, sed permesus por via pasvorto esti retpoŝtita al vi se vi forgesus ĝin.
Vi povas ankaŭ elekti permesigi aliaj uzantoj kontakti vin per via uzanto-paĝo aŭ diskuto-paĝo sen malkaŝante vian identeco.',
'prefs-help-email-required'     => 'Ret-adreso estas bezonata.',
'prefs-info'                    => 'Baza informo',
'prefs-i18n'                    => 'Internaciigo',
'prefs-signature'               => 'Subskribo',
'prefs-dateformat'              => 'Data formato',
'prefs-timeoffset'              => 'Tempa deŝovo',
'prefs-advancedediting'         => 'Progresaj opcioj',
'prefs-advancedrc'              => 'Progresaj opcioj',
'prefs-advancedrendering'       => 'Progresaj opcioj',
'prefs-advancedsearchoptions'   => 'Progresaj opcioj',
'prefs-advancedwatchlist'       => 'Progresaj opcioj',
'prefs-display'                 => 'Montraj opcioj',
'prefs-diffs'                   => 'Diferencoj',

# User rights
'userrights'                  => 'Prizorgi rajtojn de uzantoj',
'userrights-lookup-user'      => 'Administri grupojn de uzantoj',
'userrights-user-editname'    => 'Entajpu salutnomon:',
'editusergroup'               => 'Redakti grupojn de uzantoj',
'editinguser'                 => "Redaktante uzanto-rajtojn de uzanto '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Redakti grupojn de uzantoj',
'saveusergroups'              => 'Konservi grupojn de uzantoj',
'userrights-groupsmember'     => 'Membro de:',
'userrights-groups-help'      => 'Vi povas modifi la grupojn kiun ĉi uzanto enestas.
* Markita markbutono signifas ke la uzanto estas en tiu grupo.
* Nemarkita markbutono signifas ke la uzanto ne estas in tiu grupo.
* Steleto (*) signifas ke vi ne povas forigi la grupon post vi aldonis ĝin, aŭ male.',
'userrights-reason'           => 'Kialo por ŝanĝo:',
'userrights-no-interwiki'     => 'Vi ne rajtas redakti uzanto-rajtojn en aliaj vikioj.',
'userrights-nodatabase'       => 'Datumbazo $1 ne ekzistas aŭ ne estas loka.',
'userrights-nologin'          => 'Vi nepre [[Special:UserLogin|ensalutu]] kun administranto-konto doni uzanto-rajtojn.',
'userrights-notallowed'       => 'Via konto ne rajtas doni uzanto-rajtojn.',
'userrights-changeable-col'   => 'Grupoj kiujn vi povas ŝanĝi',
'userrights-unchangeable-col' => 'Grupoj kiujn vi ne povas ŝanĝi',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Uzantoj',
'group-autoconfirmed' => 'Aŭtomate konfirmitaj uzantoj',
'group-bot'           => 'Robotoj',
'group-sysop'         => 'Administrantoj',
'group-bureaucrat'    => 'Burokratoj',
'group-suppress'      => 'Superrigardoj',
'group-all'           => '(ĉiuj)',

'group-user-member'          => 'uzanto',
'group-autoconfirmed-member' => 'Aŭtomate konfirmita uzanto',
'group-bot-member'           => 'Roboto',
'group-sysop-member'         => 'Administranto',
'group-bureaucrat-member'    => 'Burokrato',
'group-suppress-member'      => 'Superrigardo',

'grouppage-user'          => '{{ns:project}}:Uzantoj',
'grouppage-autoconfirmed' => '{{ns:project}}:Aŭtomate konfirmitaj uzantoj',
'grouppage-bot'           => '{{ns:project}}:Robotoj',
'grouppage-sysop'         => '{{ns:project}}:Administrantoj',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokratoj',
'grouppage-suppress'      => '{{ns:project}}:Superrigardo',

# Rights
'right-read'                  => 'Legi paĝojn',
'right-edit'                  => 'Redakti paĝojn',
'right-createpage'            => 'Kreu paĝojn (kiuj ne estas diskuto-paĝoj)',
'right-createtalk'            => 'Krei diskuto-paĝojn',
'right-createaccount'         => 'Krei novajn uzanto-kontojn',
'right-minoredit'             => 'Marki redaktojn kiel etajn',
'right-move'                  => 'Movi paĝojn',
'right-move-subpages'         => 'Alinomigi paĝojn kun ĝiaj subpaĝoj',
'right-move-rootuserpages'    => 'Movi radikajn uzanto-paĝojn',
'right-movefile'              => 'Alinomigi dosierojn',
'right-suppressredirect'      => 'Ne krei alidirektilon de la malnova nomo kiam movante paĝon',
'right-upload'                => 'Alŝuti dosierojn',
'right-reupload'              => 'Anstataŭigi ekzistantan dosieron',
'right-reupload-own'          => 'Anstataŭigi ekzistantan dosieron alŝutitan de la sama uzanto',
'right-reupload-shared'       => 'Anstataŭigi dosierojn en la komuna bildprovizejo loke',
'right-upload_by_url'         => 'Alŝuti dosieron de URL-adreso',
'right-purge'                 => 'Refreŝigi la retejan kaŝmemoron por paĝo sen konfirma paĝo',
'right-autoconfirmed'         => 'Redakti duone protektitajn paĝojn',
'right-bot'                   => 'Traktiĝi kiel aŭtomata procezo',
'right-nominornewtalk'        => 'Ne kaŭzi etajn redaktojn en diskuto-paĝoj sproni la inviton por novaj mesaĝoj',
'right-apihighlimits'         => 'Utiligu pli altajn limojn por API informmendoj',
'right-writeapi'              => 'Uzi la API por modifi la vikion',
'right-delete'                => 'Forigi paĝojn',
'right-bigdelete'             => 'Forigi paĝojn kun grandaj historioj',
'right-deleterevision'        => 'Forigi kaj malforigi specifajn versiojn de paĝoj',
'right-deletedhistory'        => 'Rigardi listanojn de forigitaj historioj, sen ties asociaj tekstoj',
'right-deletedtext'           => 'Rigardi forigitan tekston kaj ŝanĝojn inter forigitaj revizioj.',
'right-browsearchive'         => 'Serĉi forigitajn paĝojn',
'right-undelete'              => 'Restarigi paĝon',
'right-suppressrevision'      => 'Kontroli kaj restarigi versiojn kaŝitajn de administrantoj',
'right-suppressionlog'        => 'Vidi privatajn protokolojn',
'right-block'                 => 'Forbari aliajn uzantoj de redaktado',
'right-blockemail'            => 'Forbari uzanton de retpoŝta sendado',
'right-hideuser'              => 'Forbari salutnomon, kaŝante ĝin de la publiko',
'right-ipblock-exempt'        => 'Preterpasi IP-forbarojn, aŭtomatajn forbarojn, kaj ĝeneralajn forbarojn',
'right-proxyunbannable'       => 'Preterpasi aŭtomatajn forbarojn de prokuriloj',
'right-protect'               => 'Ŝanĝi protektniveloj kaj redakti protektitajn paĝojn',
'right-editprotected'         => 'Redakti protektitajn paĝojn (sen kaskada protektado)',
'right-editinterface'         => 'Redakti la uzulan interfacon',
'right-editusercssjs'         => 'Redaktu CSS- kaj JS-dosierojn de aliaj uzantoj',
'right-editusercss'           => 'Redaktu CSS-dosierojn de aliaj uzantoj',
'right-edituserjs'            => 'Redaktu JS-dosierojn de aliaj uzantoj',
'right-rollback'              => 'Tuj malfari la redaktojn de la lasta uzanto kiu redaktis specifan paĝon',
'right-markbotedits'          => 'Marki restarigitajn redaktojn kiel robotajn redaktojn',
'right-noratelimit'           => 'Ne influita de po-limoj',
'right-import'                => 'Importi paĝojn de aliaj vikioj',
'right-importupload'          => 'Importi paĝojn de dosiera alŝuto',
'right-patrol'                => 'Marki redaktojn kiel patrolitajn',
'right-autopatrol'            => 'Fari redaktojn aŭtomate markitajn kiel patrolitajn',
'right-patrolmarks'           => 'Rigardi patrolmarkojn de lastaj ŝanĝoj',
'right-unwatchedpages'        => 'Vidi la liston de nepriatentitaj paĝoj',
'right-trackback'             => 'Enigi retrovojon',
'right-mergehistory'          => 'Kunfandigi la historiojn de paĝoj',
'right-userrights'            => 'Redakti ĉiujn uzanto-rajtojn',
'right-userrights-interwiki'  => 'Redakti la rajtojn de uzantoj en aliaj vikioj',
'right-siteadmin'             => 'Ŝlosi kaj malŝlosi la datumbazon',
'right-reset-passwords'       => 'Reŝanĝi pasvortojn de aliaj uzantoj',
'right-override-export-depth' => 'Eksporti paĝojn inkluzivante ligitajn paĝojn ĝis profundeco de 5',
'right-versiondetail'         => 'Montri la informon pri la etendita programara versio',
'right-sendemail'             => 'Sendi retpoŝton al aliaj uzantoj',

# User rights log
'rightslog'      => 'Protokolo de uzanto-rajtoj',
'rightslogtext'  => 'Ĉi tio estas protokolo pri la ŝanĝoj de uzantorajtoj.',
'rightslogentry' => 'ŝanĝis grupan membrecon por $1 de $2 al $3',
'rightsnone'     => '(nenia)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'legi ĉi tiun paĝon',
'action-edit'                 => 'redakti ĉi tiun paĝon',
'action-createpage'           => 'krei paĝojn',
'action-createtalk'           => 'krei diskuto-paĝojn',
'action-createaccount'        => 'krei ĉi tiun uzanto-konton',
'action-minoredit'            => 'marki ĉi tiun redakton kiel malgravan',
'action-move'                 => 'movi ĉi tiun paĝon',
'action-move-subpages'        => 'movi ĉi tiun paĝon, kaj ties subpaĝojn',
'action-move-rootuserpages'   => 'movi radikajn uzanto-paĝojn',
'action-movefile'             => 'alinomigi ĉi tiun dosieron',
'action-upload'               => 'alŝuti ĉi tiun dosieron',
'action-reupload'             => 'anstataŭigi ĉi tiun ekzistantan dosieron',
'action-reupload-shared'      => 'anstataŭigi ĉi tiun dosieron en kolektiva dosierujo',
'action-upload_by_url'        => 'alŝuti ĉi tiun dosieron de URL-adreso',
'action-writeapi'             => 'uzi la skriban API-on',
'action-delete'               => 'forigi ĉi tiun paĝon',
'action-deleterevision'       => 'forigi ĉi tiun version',
'action-deletedhistory'       => 'vidi la forigitan historion de ĉi tiu paĝo',
'action-browsearchive'        => 'traserĉi forigitajn paĝojn',
'action-undelete'             => 'malforigi ĉi tiun paĝon',
'action-suppressrevision'     => 'kontroli kaj restarigi ĉi tiun kaŝitan version',
'action-suppressionlog'       => 'vidi ĉi tiun privantan protokolon',
'action-block'                => 'forari ĉi tiun uzanton de redaktado',
'action-protect'              => 'ŝanĝi la protektan nivelon por ĉi tiu paĝo',
'action-import'               => 'importi ĉi tiun paĝon de alia vikio',
'action-importupload'         => 'importi ĉi tiun paĝon de dosiera alŝuto',
'action-patrol'               => 'marki redakton de alia persono kiel patrolitan',
'action-autopatrol'           => 'fari vian redakton markitan kiel patrolitan',
'action-unwatchedpages'       => 'vidi la liston de neatentitaj paĝoj',
'action-trackback'            => 'aldoni retrovojon',
'action-mergehistory'         => 'kunigi la historion de ĉi tiu paĝo',
'action-userrights'           => 'redakti ĉiujn rajtojn de uzantoj',
'action-userrights-interwiki' => 'redakti uzulrajtojn de uzantoj en aliaj vikioj',
'action-siteadmin'            => 'ŝlosi aŭ malŝlosi la datumbazon',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ŝanĝo|ŝanĝoj}}',
'recentchanges'                     => 'Lastaj ŝanĝoj',
'recentchanges-legend'              => 'Opcioj pri lastaj ŝanĝoj',
'recentchangestext'                 => 'Sekvi la plej lastajn ŝanĝojn en la {{SITENAME}} per ĉi tiu paĝo.',
'recentchanges-feed-description'    => 'Sekvi la plej lastatempajn ŝanĝojn al la vikio en ĉi tiu fonto.',
'recentchanges-label-legend'        => 'Klarigeto: $1.',
'recentchanges-legend-newpage'      => '$1 - nova paĝo',
'recentchanges-label-newpage'       => 'Ĉi tiu redakto kreis novan paĝon',
'recentchanges-legend-minor'        => '$1 - eta redakto',
'recentchanges-label-minor'         => 'Ĉi tiu estas eta redakto',
'recentchanges-legend-bot'          => '$1 - robota redakto',
'recentchanges-label-bot'           => 'Ĉi tiu redakto estis farita per roboto.',
'recentchanges-legend-unpatrolled'  => '$1 - nepatrolita redakto',
'recentchanges-label-unpatrolled'   => 'Ĉi tiu redakto ne jam estis patrolata.',
'rcnote'                            => "Jen la {{PLURAL:$1|lasta '''1''' ŝanĝo|lastaj '''$1''' ŝanĝoj}} dum la {{PLURAL:$2|lasta tago|lastaj '''$2''' tagoj}}, ekde $5, $4.",
'rcnotefrom'                        => "Jen la ŝanĝoj ekde '''$2''' (lastaj ĝis '''$1''').",
'rclistfrom'                        => 'Montri novajn ŝanĝojn ekde "$1"',
'rcshowhideminor'                   => '$1 redaktetojn',
'rcshowhidebots'                    => '$1 robotojn',
'rcshowhideliu'                     => '$1 ensalutantojn',
'rcshowhideanons'                   => '$1 anonimajn redaktojn',
'rcshowhidepatr'                    => '$1 patrolitajn redaktojn',
'rcshowhidemine'                    => '$1 miajn redaktojn',
'rclinks'                           => 'Montri $1 lastajn ŝanĝojn dum la $2 lastaj tagoj.<br />$3',
'diff'                              => 'malsamoj',
'hist'                              => 'historio',
'hide'                              => 'Kaŝi',
'show'                              => 'Montri',
'minoreditletter'                   => 'E',
'newpageletter'                     => 'N',
'boteditletter'                     => 'R',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|priatentanta uzanto|priatentantaj uzantoj}}]',
'rc_categories'                     => 'Nur por jenaj kategorioj (disigu per "|")',
'rc_categories_any'                 => 'ĉiu',
'newsectionsummary'                 => '/* $1 */ nova sekcio',
'rc-enhanced-expand'                => 'Montri detalojn (necesas JavaScript-on)',
'rc-enhanced-hide'                  => 'Kaŝi detalojn',

# Recent changes linked
'recentchangeslinked'          => 'Rilataj paĝoj',
'recentchangeslinked-feed'     => 'Rilataj paĝoj',
'recentchangeslinked-toolbox'  => 'Rilataj paĝoj',
'recentchangeslinked-title'    => 'Ŝanĝoj rilataj al "$1"',
'recentchangeslinked-noresult' => 'Neniuj ŝanĝoj en ligitaj paĝoj dum la donata periodo.',
'recentchangeslinked-summary'  => "Jen listo de ŝanĝoj faritaj lastatempe al paĝoj ligitaj el specifa paĝo (aŭ al membroj de specifa kategorio).
Paĝoj en [[Special:Watchlist|via atentaro]] estas '''grasaj'''.",
'recentchangeslinked-page'     => 'Nomo de paĝo:',
'recentchangeslinked-to'       => 'Montru ŝanĝojn al paĝoj ligitaj al la specifa paĝo anstataŭe.',

# Upload
'upload'                      => 'Alŝuti dosieron',
'uploadbtn'                   => 'Alŝuti dosieron',
'reupload'                    => 'Realŝuti',
'reuploaddesc'                => 'Reveni al la alŝuta formularo.',
'uploadnologin'               => 'Ne ensalutinta',
'uploadnologintext'           => 'Se vi volas alŝuti dosierojn, vi devas [[Special:UserLogin|ensaluti]].',
'upload_directory_missing'    => 'La alŝuta dosierujo ($1) estas nek trovebla nek kreebla de la retservilo.',
'upload_directory_read_only'  => 'La TTT-servilo ne povas alskribi la alŝuto-dosierujon ($1).',
'uploaderror'                 => 'Eraro okazis dum alŝuto',
'uploadtext'                  => "Uzu la jenan formon por alŝuti dosierojn.
Vidi aŭ serĉi antaŭe alŝutitajn bildojn, iru la [[Special:FileList|Listo de alŝutitaj dosieroj]]; (re)alŝutaĵoj ankaŭ estas registrita en la [[Special:Log/upload|Protokolo de alŝutoj]], forigoj en la [[Special:Log/delete|protokolo de forigoj]].

Por inkluzivi la dosieron en paĝon, skribu ligilon laŭ la formoj

* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bildo.jpg]]</nowiki>''' por uzi la plena versio de la dosiero
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bildo.png|200px|thumb|left|alternativa teksto]]</nowiki>''' por uzi 200-rastrumera versio en kesto al la maldekstro (\"left\") kun \"alternativa teksto\" kiel la priskribo.
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dosiero.ogg]]</nowiki>''' por ligi rekte al la dosiero ne montranta la dosieron.",
'upload-permitted'            => 'Permesitaj dosiertipoj: $1.',
'upload-preferred'            => 'Preferitaj dosiertipoj: $1.',
'upload-prohibited'           => 'Malpermesitaj dosiero-tipoj: $1.',
'uploadlog'                   => 'protokolo de alŝutoj',
'uploadlogpage'               => 'Protokolo de alŝutoj',
'uploadlogpagetext'           => 'Jen la plej laste alŝutitaj dosieroj.
Ĉiuj tempoj montriĝas laŭ la horzono UTC.',
'filename'                    => 'Dosiernomo',
'filedesc'                    => 'Resumo',
'fileuploadsummary'           => 'Resumo:',
'filereuploadsummary'         => 'Dosieraj ŝanĝoj:',
'filestatus'                  => 'Kopirajta statuso:',
'filesource'                  => 'Fonto:',
'uploadedfiles'               => 'Alŝutitaj dosieroj',
'ignorewarning'               => 'Ignori averton kaj konservi dosieron ĉiukaze',
'ignorewarnings'              => 'Ignori ĉiajn avertojn',
'minlength1'                  => 'Nomoj de dosieroj nepre havas almenaŭ unu literon.',
'illegalfilename'             => 'La dosiernomo $1 entenas karaktrojn kiuj ne estas permesitaj en paĝaj titoloj. Bonvolu renomi la dosieron kaj provu denove alŝuti ĝin.',
'badfilename'                 => 'Dosiernomo estis ŝanĝita al "$1".',
'filetype-badmime'            => 'Dosieroj de la MIME-tipo "$1" ne estas permesitaj por alŝutado.',
'filetype-bad-ie-mime'        => 'Ne eblas alŝuti ĉi tiun dosieron, ĉar Interreta Esplorilo detektus ĝin kiel "$1", kiu estas malpermesita kaj eble danĝera dosiertipo.',
'filetype-unwanted-type'      => "'''\".\$1\"''' estas nevolata dosiero-tipo. {{PLURAL:\$3|Preferata dosiero-tipo|Prefereataj dosiero-tipoj}} estas \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' ne estas permesita dosiero-tipo. {{PLURAL:\$3|Permesita dosiero-tipo|Permesitaj dosiero-tipoj}} estas \$2.",
'filetype-missing'            => 'Ĉi tiu dosiero ne inkluzivas finaĵon de dosiernomo (kiel ".jpg").',
'large-file'                  => 'Estas rekomendite, ke dosieroj ne superas grandon de $1 bitokoj; 
tiu ĉi tiu dosiero pezas $2 bitokojn.',
'largefileserver'             => 'Ĉi tiu dosiero estas pli granda ol permesas la servilaj preferoj.',
'emptyfile'                   => 'La dosiero kiun vi alŝutis ŝajnas malplena. Tio povas esti kaŭzita sde tajperaro en la titolo. Bonvolu kontroli ĉu vi vere volas alŝuti tiun dosieron.',
'fileexists'                  => "Dosiero kun tia ĉi nomo jam ekzistas.
Bonvolu kontroli '''<tt>[[:$1]]</tt>''' krom se vi certas ke vi konscie volas ŝanĝi ĝuste tiun.
[[$1|thumb]]",
'filepageexists'              => "La priskriba paĝo por ĉi tiu dosiero jam estis kreita ĉe '''<tt>[[:$1]]</tt>''', sed neniu dosiero kun ĉi tiu nomo nune ekzistas.
La resumo kiun vi entajpos ne aperos en la priskribo-paĝo.
Por aperigi vian resumon, vi devos permane redakti ĝin.
[[$1|thumb]]",
'fileexists-extension'        => "Dosiero kun simila nomo ekzistas: [[$2|thumb]]
* Nomo de la alŝuta dosiero: '''<tt>[[:$1]]</tt>'''
* Nomo de la ekzistanta dosiero: '''<tt>[[:$2]]</tt>'''
Bonvolu elekti malsaman nomon.",
'fileexists-thumbnail-yes'    => "Ĉi tiu dosiero ŝajnas kiel bildo de malkreskigita grandeco ''(bildeto)''. [[$1|thumb]]
Bonvolu kontroli la dosieron '''<tt>[[:$1]]</tt>'''. 
Se la kontrolita dosiero estas la sama bildo kiel la originala grandeco, ĝi ne nepras alŝuti plian bideton.",
'file-thumbnail-no'           => "La dosiernomo komencas kun '''<tt>$1</tt>'''. 
Ĝi ŝajnas kiel bildo de malgrandigita grandeco ''(thumbnail)''.
Se vi havas ĉi tiun bildon en plena distingivo, alŝutu ĉi tiun, alikaze bonvolu ŝanĝi la dosieran nomon.",
'fileexists-forbidden'        => 'Dosiero kun tiu ĉi nomo jam ekzistas, kaj ne eblas anstataŭigi ĝin.
Se vi ankoraŭ volas alŝuti vian dosieron, bonvolu reprovi kun nova nomo. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dosiero kun ĉi tia nomo jam ekzistas en la komuna dosierujo.
Se vi ankoraŭ volas alŝuti vian dosieron, bonvolu retroigi kaj uzi novan nomon.[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ĉi tiu dosiero estas duplikato de la {{PLURAL:$1|jena dosiero|jenaj dosieroj}}:',
'file-deleted-duplicate'      => 'Duplikata dosiero de ĉi tiu dosiero ([[$1]]) estis antaŭe forigita. Vi legu la forigan historion de tiu dosiero antaŭ provi realŝuti ĝin.',
'successfulupload'            => 'Alŝuto sukcesis!',
'uploadwarning'               => 'Alŝuta averto',
'savefile'                    => 'Konservi dosieron',
'uploadedimage'               => 'alŝutis "[[$1]]"',
'overwroteimage'              => 'alŝutis novan version de "[[$1]]"',
'uploaddisabled'              => 'Alŝutado estas malŝaltita',
'uploaddisabledtext'          => 'Alŝutado de dosieroj estas malebligita.',
'php-uploaddisabledtext'      => 'Dosiera alŝutado estas malŝalta en PHP. Bonvolu kontroli la preferon file_uploads.',
'uploadscripted'              => 'HTML-aĵo aŭ skriptokodaĵo troviĝas en tiu ĉi tiu dosiero, kiun TTT-foliumilo eble interpretus erare.',
'uploadcorrupt'               => 'La dosiero estas difektita aŭ havas malĝustan finaĵon. Bonvolu kontroli la dosieron kaj refoje alŝuti ĝin.',
'uploadvirus'                 => 'Viruso troviĝas en la dosiero! Detaloj: $1',
'sourcefilename'              => 'Fonta dosiernomo:',
'destfilename'                => 'Celdosiernomo:',
'upload-maxfilesize'          => 'Maksimuma dosier-pezo: $1',
'watchthisupload'             => 'Atenti ĉi tiun dosieron',
'filewasdeleted'              => 'Dosiero de ĉi nomo estis antaŭe alŝutita kaj poste redaktita. Vi kontrolu la $1 antaux alŝutante ĝin denove.',
'upload-wasdeleted'           => "'''Averto: Vi alŝutas dosieron kiu estis antaŭe forigita.'''

Vi konsideru ĉu taŭgas alŝuti ĉi tiu dosiero.
jen la protokolo pri forigado por ĉi tiu dosiero por via oportuneco:",
'filename-bad-prefix'         => "La nomo de la dosiero kiun vi alŝutas komencas kun '''\"\$1\"''', kiu estas nepriskriba nomo ofte aŭtomate donata de ciferecaj fotiloj. Bonvolu elekti pli priskriban nomon por via bildo.",

'upload-proto-error'        => 'Malvalida protokolo',
'upload-proto-error-text'   => 'Fora alŝuto devas URL-on komence de <code>http://</code> aŭ <code>ftp://</code>.',
'upload-file-error'         => 'Interna eraro',
'upload-file-error-text'    => 'Interna eraro okazis provante krei labordosieron ĉe la servilo. Bonvolu kontakti [[Special:ListUsers/sysop|sistem-administranton]].',
'upload-misc-error'         => 'Nekonata eraro pri alŝutado.',
'upload-misc-error-text'    => 'Nekonata eraro okazis dum la alŝuto. 
Bonvolu kontroli ke la URL-o estas valida kaj atingebla tiam reprovu. 
Se la problemo kontinuas, kontaku [[Special:ListUsers/sysop|sisteman administranton]].',
'upload-too-many-redirects' => 'La URL-o enhavis tro multajn alidirektilojn',
'upload-unknown-size'       => 'Nekonata grandeco',
'upload-http-error'         => 'HTTP-eraro okazis: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Atingo malpermisita',
'img-auth-nopathinfo'   => 'Mankas PATH_INFO. (Informo pri dosiervojo.)
Via servilo ne estas konfigurita por sendi ĉi tiun informon.
Eble ĝi estas CGI-bazita kaj ne subtenas img_auth.
Vidu http://www.mediawiki.org/wiki/Manual:Image_Authorization. (angle)',
'img-auth-notindir'     => 'Petita vojo ne estas en la konfigurita alŝuta dosierujo.',
'img-auth-badtitle'     => 'Ne eblas konstrui validan titolon de "$1".',
'img-auth-nologinnWL'   => 'Vi ne estas ensalutita kaj "$1" ne estas en la blankalisto.',
'img-auth-nofile'       => 'Dosiero "$1" ne ekzistas.',
'img-auth-isdir'        => 'Vi provas atingi dosierujon "$1".
Nur dosiera atingo estas permesita.',
'img-auth-streaming'    => 'Elsendfluante "$1".',
'img-auth-public'       => 'La funkcio de img_auth.php estas eligi dosierojn de privata vikio.
Ĉi tiu vikio estas konfigurita kiel publika vikio.
Por optimuma sekureco, img_auth.php estas malŝalta.',
'img-auth-noread'       => 'Uzanto ne havas atingon por legi "$1".',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-o ne estis atingebla',
'upload-curl-error6-text'  => 'La donata URL-o ne estis atingita. Bonvolu rekontroli ke la URL-o estas korekta kaj la retejo funkcias.',
'upload-curl-error28'      => 'Tempolimo de alŝuto atingita',
'upload-curl-error28-text' => 'La retejo atendas tro sen respondo. Bonvolu verigi ke la retejo ankoraŭ funkcias kaj reprovi. Vi eble volus trovi dum malpli okupa tempo.',

'license'            => 'Licencado:',
'license-header'     => 'Licencado:',
'nolicense'          => 'Neniu elektita',
'license-nopreview'  => '(Antaŭvido ne montrebla)',
'upload_source_url'  => ' (valida, publike atingebla URL-o)',
'upload_source_file' => ' (dosiero en via komputilo)',

# Special:ListFiles
'listfiles-summary'     => 'Ĉi tiu speciala paĝo montras ĉiujn alŝutitajn dosierojn.
Defaŭlte, la lasta alŝutitaj dosieroj estas montrataj supren.
Klaku la kolumnan titolon por ŝanĝi la direkton de ordigo.',
'listfiles_search_for'  => 'Serĉi dosieran nomon:',
'imgfile'               => 'dosiero',
'listfiles'             => 'Listo de alŝutitaj dosieroj',
'listfiles_date'        => 'Dato',
'listfiles_name'        => 'Nomo',
'listfiles_user'        => 'Uzanto',
'listfiles_size'        => 'Grandeco',
'listfiles_description' => 'Priskribo',
'listfiles_count'       => 'Versioj',

# File description page
'file-anchor-link'          => 'Dosiero',
'filehist'                  => 'Historio de dosiero',
'filehist-help'             => 'Klaku daton/tempon por rigardi la dosieron kiel ĝin ŝajnitan tiame.',
'filehist-deleteall'        => 'forigi ĉiujn',
'filehist-deleteone'        => 'forigi',
'filehist-revert'           => 'restarigi',
'filehist-current'          => 'nuna',
'filehist-datetime'         => 'Dato/Tempo',
'filehist-thumb'            => 'Bildeto',
'filehist-thumbtext'        => 'Bildeto por versio ekde $1',
'filehist-nothumb'          => 'Neniu bildeto',
'filehist-user'             => 'Uzanto',
'filehist-dimensions'       => 'Dimensioj',
'filehist-filesize'         => 'Pezo de dosiero',
'filehist-comment'          => 'Komento',
'filehist-missing'          => 'Dosiero mankas',
'imagelinks'                => 'Dosieraj ligiloj',
'linkstoimage'              => 'La {{PLURAL:$1|jena paĝo|jenaj paĝoj}} ligas al ĉi tiu dosiero:',
'linkstoimage-more'         => 'Pli ol $1 {{PLURAL:$1|paĝo|paĝoj}} ligas ĉi tiun dosieron.
La jena listo montras la {{PLURAL:$1|unua paĝligilo|unuaj $1 paĝligiloj}} al nur ĉi tiu dosiero.
[[Special:WhatLinksHere/$2|Plena listo]] estas atingebla.',
'nolinkstoimage'            => 'Neniu paĝo ligas al ĉi tiu dosiero.',
'morelinkstoimage'          => 'Rigardi [[Special:WhatLinksHere/$1|pliajn ligilojn]] al ĉi tiu dosiero.',
'redirectstofile'           => 'Jen {{PLURAL:$1|dosiero liganta|dosieroj ligantaj}} al ĉi tiu dosiero:',
'duplicatesoffile'          => 'La {{PLURAL:$1|jena dosiero estas duplikato|jenaj dosieroj estas duplikatoj}} de ĉi tiu dosiero ([[Special:FileDuplicateSearch/$2|pluaj detaloj]]):',
'sharedupload'              => 'Ĉi tiu dosiero estas de $1 kaj estas uzebla de aliaj projektoj.',
'sharedupload-desc-there'   => 'Ĉi tiu dosiero estas de $1 kaj estas uzebla en aliaj projektoj.
Bonvolu vidi la [$2 dosier-priskriban paĝon] por plua informo.',
'sharedupload-desc-here'    => 'Ĉi tiu dosiero estas de $1 kaj estas uzebla de aliaj projektoj.
Jen la priskribo en ties [$2 dosier-priskriba paĝo].',
'filepage-nofile'           => 'Neniu dosiero de ĉi tiu nomo ekzistas.',
'filepage-nofile-link'      => 'Neniu dosiero de ĉi tiu nomo ekzistas, sed vi povas [$1 alŝuti ĝin].',
'uploadnewversion-linktext' => 'Alŝuti novan version de ĉi tiu dosiero',
'shared-repo-from'          => 'de $1',
'shared-repo'               => 'komuna dosierujo',

# File reversion
'filerevert'                => 'Restarigi $1',
'filerevert-legend'         => 'Restarigi dosieron',
'filerevert-intro'          => "Vi restarigas '''[[Media:$1|$1]]''' al la [$4 versio de $3, $2].",
'filerevert-comment'        => 'Komento:',
'filerevert-defaultcomment' => 'Restarigita al versio ekde $2, $1',
'filerevert-submit'         => 'Restarigi',
'filerevert-success'        => "'''[[Media:$1|$1]]''' estis restarigita al [$4 versio ekde $3, $2].",
'filerevert-badversion'     => 'Ne estas antaŭa loka versio de ĉi tiu dosiero ĉe tiu tempo.',

# File deletion
'filedelete'                  => 'Forigi $1',
'filedelete-legend'           => 'Forigi dosieron.',
'filedelete-intro'            => "Vi preskaŭ forigos dosieron '''[[Media:$1|$1]]''' kune kun ĉiom da ĝia historio.",
'filedelete-intro-old'        => "Vi forigas version de '''[[Media:$1|$1]]''' ekde [$4 $3, $2].",
'filedelete-comment'          => 'Kialo por forigo:',
'filedelete-submit'           => 'Forigi',
'filedelete-success'          => "'''$1''' estas forigita.",
'filedelete-success-old'      => "La versio de '''[[Media:$1|$1]]''' ekde $3, $2 estas forigita.",
'filedelete-nofile'           => "'''$1''' ne ekzistas.",
'filedelete-nofile-old'       => "Estas neniuarkivita versio de '''$1''' kun la specifigitaj atribuoj.",
'filedelete-otherreason'      => 'Alia/plua kialo:',
'filedelete-reason-otherlist' => 'Alia kialo',
'filedelete-reason-dropdown'  => '* Oftaj kialoj de forigo
** Malobservo de kopirajto
** Duplikata dosiero',
'filedelete-edit-reasonlist'  => 'Redakti kialojn por forigo',
'filedelete-maintenance'      => 'Forigado kaj restarigado de dosieroj provizore malŝaltis dum funkciteno.',

# MIME search
'mimesearch'         => 'MIME-serĉilo',
'mimesearch-summary' => 'Ĉi tiu paĝo ebligas la filtradon de dosieroj por ties MIME-tipo. Enigu: enhavo-tipo/subtipo, ekz. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-tipo:',
'download'           => 'elŝuti',

# Unwatched pages
'unwatchedpages' => 'Neatentataj paĝoj',

# List redirects
'listredirects' => 'Listo de alidirektiloj',

# Unused templates
'unusedtemplates'     => 'Neuzitaj ŝablonoj',
'unusedtemplatestext' => 'Ĉi tiu paĝo listigas ĉiujn paĝojn en la nomspaco {{ns:template}} kiuj ne estas enmetitaj en iu ajn alia paĝo. 
Bonvolu kontroli aliajn ligilojn al la ŝablonoj antaŭ ol forigi ilin.',
'unusedtemplateswlh'  => 'aliaj ligiloj',

# Random page
'randompage'         => 'Hazarda paĝo',
'randompage-nopages' => 'Ne ekzistas paĝoj en la {{PLURAL:$2|nomspaco|nomspacoj}}: "$1".',

# Random redirect
'randomredirect'         => 'Hazarda alidirekto',
'randomredirect-nopages' => 'Estas neniuj alidirektiloj en la nomspaco "$1".',

# Statistics
'statistics'                   => 'Statistiko',
'statistics-header-pages'      => 'Paĝaj statistikoj',
'statistics-header-edits'      => 'Redaktaj statistikoj',
'statistics-header-views'      => 'Vidi statistikojn',
'statistics-header-users'      => 'Pri la uzantaro',
'statistics-header-hooks'      => 'Aliaj statistikoj',
'statistics-articles'          => 'Enhavaj paĝoj',
'statistics-pages'             => 'Paĝoj',
'statistics-pages-desc'        => 'Ĉiuj paĝoj en la vikio, inkluzivante diskuto-paĝojn, alidirektilojn, ktp.',
'statistics-files'             => 'Alŝutitaj dosieroj',
'statistics-edits'             => 'Paĝaj redaktoj ekde {{SITENAME}} estis starigita',
'statistics-edits-average'     => 'Averaĝaj redaktoj po paĝo',
'statistics-views-total'       => 'Vidoj entutaj',
'statistics-views-peredit'     => 'Vidoj po redakto',
'statistics-jobqueue'          => 'Longeco de [http://www.mediawiki.org/wiki/Manual:Job_queue vico de prokrastita reenkategoriado]',
'statistics-users'             => 'Registritaj [[Special:ListUsers|uzantoj]]',
'statistics-users-active'      => 'Aktivaj uzantoj',
'statistics-users-active-desc' => 'Uzantoj kiuj faris agon en la {{PLURAL:$1|lasta tago|lastaj $1 tagoj}}',
'statistics-mostpopular'       => 'Plej ofte montrataj paĝoj',

'disambiguations'      => 'Misligitaj apartigiloj',
'disambiguationspage'  => 'Template:Apartigilo',
'disambiguations-text' => "La jenaj paĝoj alligas '''apartigilon'''.
Ili devus anstataŭe alligi la ĝustan temon.<br />
Paĝo estas traktata kiel apartigilo se ĝi uzas ŝablonon kiu estas ligita de [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Duoblaj alidirektadoj',
'doubleredirectstext'        => 'Ĉi tiu paĝo montras paĝojn kiuj alidirektas al aliaj alidirektiloj.
Ĉiu vico enhavas ligilojn ĉe la unua kaj dua alidirektadoj, kaj la unua linio de la dua alidirektado, kiu ĝenerale montras la "veran" celpaĝon, kiu celu la unuan alidirektadon.
<s>Forstrekitaj</s> listeroj estis riparitaj.',
'double-redirect-fixed-move' => '[[$1]] estis alinomita; ĝi nun alidirektas al [[$2]]',
'double-redirect-fixer'      => 'Alidirektila riparilo',

'brokenredirects'        => 'Rompitaj alidirektadoj',
'brokenredirectstext'    => 'La jenaj alidirektadoj ligas al neekzistantaj paĝoj:',
'brokenredirects-edit'   => 'redakti',
'brokenredirects-delete' => 'forigi',

'withoutinterwiki'         => 'Paĝoj sen lingvaj ligiloj',
'withoutinterwiki-summary' => 'Jenaj paĝoj ne ligas al aliaj lingvoversioj:',
'withoutinterwiki-legend'  => 'Prefikso',
'withoutinterwiki-submit'  => 'Montri',

'fewestrevisions' => 'Artikoloj kun la plej malmultaj versioj',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bitoko|bitokoj}}',
'ncategories'             => '{{PLURAL:$1|unu kategorio|$1 kategorioj}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligilo|ligiloj}}',
'nmembers'                => '{{PLURAL:$1|unu membro|$1 membroj}}',
'nrevisions'              => '$1 {{PLURAL:$1|versio|versioj}}',
'nviews'                  => '{{PLURAL:$1|unufoje|$1 fojojn}}',
'specialpage-empty'       => 'Ne estas rezultoj por ĉi tiu raporto.',
'lonelypages'             => 'Neligitaj paĝoj',
'lonelypagestext'         => 'La jenaj paĝoj ne estas ligitaj de aŭ transikluzivita en aliaj paĝoj en {{SITENAME}}.',
'uncategorizedpages'      => 'Neenkategoriitaj paĝoj',
'uncategorizedcategories' => 'Neenkategoriitaj kategorioj',
'uncategorizedimages'     => 'Neenkategoriigitaj dosieroj',
'uncategorizedtemplates'  => 'Neenkategoriigitaj ŝablonoj',
'unusedcategories'        => 'Neuzitaj kategorioj',
'unusedimages'            => 'Neuzataj bildoj',
'popularpages'            => 'Plej vizitataj paĝoj',
'wantedcategories'        => 'Dezirataj kategorioj',
'wantedpages'             => 'Dezirataj paĝoj',
'wantedpages-badtitle'    => 'Nevalida titolo en rezulta aro: $1',
'wantedfiles'             => 'Dezirataj dosieroj',
'wantedtemplates'         => 'Dezirataj ŝablonoj',
'mostlinked'              => 'Plej ligitaj paĝoj',
'mostlinkedcategories'    => 'Plej ligitaj kategorioj',
'mostlinkedtemplates'     => 'Plej ligitaj ŝablonoj',
'mostcategories'          => 'Artikoloj kun la plej multaj kategorioj',
'mostimages'              => 'Plej ligitaj bildoj',
'mostrevisions'           => 'Artikoloj kun la plej multaj versioj',
'prefixindex'             => 'Ĉiuj paĝoj kun prefikso',
'shortpages'              => 'Mallongaj paĝoj',
'longpages'               => 'Longaj paĝoj',
'deadendpages'            => 'Paĝoj sen interna ligilo',
'deadendpagestext'        => 'La sekvaj paĝoj ne ligas al aliaj paĝoj en {{SITENAME}}.',
'protectedpages'          => 'Protektitaj paĝoj',
'protectedpages-indef'    => 'Nur ĉiamaj protektaĵoj',
'protectedpages-cascade'  => 'Nur kaskadaj protektoj',
'protectedpagestext'      => 'La sekvaj paĝoj estas protektitaj kontraŭ movigo aŭ redaktado',
'protectedpagesempty'     => 'Neniuj paĝoj estas momente protektitaj kun ĉi tiuj parametroj.',
'protectedtitles'         => 'Protektitaj titoloj',
'protectedtitlestext'     => 'La jenaj titoloj estas protektitaj kontraŭ kreado',
'protectedtitlesempty'    => 'Neniuj titoloj estas nune protektitaj kun ĉi parametroj.',
'listusers'               => 'Uzantaro',
'listusers-editsonly'     => 'Montri uzantojn kun redaktoj nur',
'listusers-creationsort'  => 'Ordigi laŭ dato de kreado',
'usereditcount'           => '$1 {{PLURAL:$1|redakto|redaktoj}}',
'usercreated'             => 'Kreita je $1, $2',
'newpages'                => 'Novaj paĝoj',
'newpages-username'       => 'Salutnomo:',
'ancientpages'            => 'Plej malnovaj artikoloj',
'move'                    => 'Alinomigi',
'movethispage'            => 'Alinomigi ĉi tiun paĝon',
'unusedimagestext'        => 'Notu, ke aliaj TTT-ejoj, ekzemple
la alilingvaj {{SITENAME}}j, povas rekte ligi al dosiero per URL.
Tio ne estus enkalkutita en la jena listo.',
'unusedcategoriestext'    => 'La paĝoj de la sekvanta kategorio jam ekzistas, sed neniu alia artikolo aŭ kategorio rilatas al ĝi.',
'notargettitle'           => 'Sen celpaĝo',
'notargettext'            => 'Vi ne precizigis, kiun paĝon aŭ uzanton priumi.',
'nopagetitle'             => 'Nenia cela paĝo',
'nopagetext'              => 'La cela paĝo kiun vi enigis ne ekzistas.',
'pager-newer-n'           => '{{PLURAL:$1|pli nova 1|pli novaj $1}}',
'pager-older-n'           => '{{PLURAL:$1|pli malnova 1|pli malnovaj $1}}',
'suppress'                => 'Superrigardo',

# Book sources
'booksources'               => 'Libroservoj',
'booksources-search-legend' => 'Serĉi librofontojn',
'booksources-go'            => 'Ek',
'booksources-text'          => 'Jen ligilaro al aliaj TTT-ejoj, kiuj vendas librojn,
kaj/aŭ informumos pri la libro ligita.
La {{SITENAME}} ne estas komerce ligita al tiuj vendejoj, kaj la listo ne estu
komprenata kiel rekomendo aŭ reklamo.',
'booksources-invalid-isbn'  => 'La donata ISBN verŝajne esats nevalida; kontroli por eraroj kopiitaj el la originala fonto.',

# Special:Log
'specialloguserlabel'  => 'Uzanto:',
'speciallogtitlelabel' => 'Titolo:',
'log'                  => 'Protokoloj',
'all-logs-page'        => 'Ĉiuj publikaj protokoloj',
'alllogstext'          => 'Suma kompilaĵo de ĉiuj protokoloj de {{SITENAME}}.
Vi povas plistrikti la mendon per selektado de protokola speco, la salutnomo (inkluzivante uskladon) aŭ la efika paĝo (ankaŭ inkluzivas uskladon).',
'logempty'             => 'Neniaj artikoloj en la protokolo.',
'log-title-wildcard'   => 'Serĉi titolojn komencantajn kun ĉi tiu teksto',

# Special:AllPages
'allpages'          => 'Ĉiuj paĝoj',
'alphaindexline'    => '$1 ĝis $2',
'nextpage'          => 'Sekvanta paĝo ($1)',
'prevpage'          => 'Antaŭa paĝo ($1)',
'allpagesfrom'      => 'Montri paĝojn ekde:',
'allpagesto'        => 'Montri paĝoj finante de:',
'allarticles'       => 'Ĉiuj paĝoj',
'allinnamespace'    => 'Ĉiuj paĝoj ($1 nomspaco)',
'allnotinnamespace' => 'Ĉiuj paĝoj (ne en nomspaco $1)',
'allpagesprev'      => 'Antaŭen',
'allpagesnext'      => 'Sekven',
'allpagessubmit'    => 'Ek!',
'allpagesprefix'    => 'Montri paĝojn kun prefikso:',
'allpagesbadtitle'  => 'La donata paĝo estis nevalida aŭ havis interlingvan aŭ intervikian prefikson. Ĝi eble enhavas unu aŭ plurajn signojn kiu ne povas esti uzataj en titoloj.',
'allpages-bad-ns'   => '{{SITENAME}} ne havas nomspacon "$1".',

# Special:Categories
'categories'                    => 'Kategorioj',
'categoriespagetext'            => 'La {{PLURAL:$1|jena kategorio|jenaj kategorioj}} ekzistas jam en la vikio.
[[Special:UnusedCategories|Neuzataj kategorioj]] ne estis montrataj ĉi tie.
Vidu ankaŭ [[Special:WantedCategories|Dezirataj kategorioj]].',
'categoriesfrom'                => 'Montri kategoriojn komencante de:',
'special-categories-sort-count' => 'oridigi laŭ nombrado',
'special-categories-sort-abc'   => 'ordigi laŭ alfabeto',

# Special:DeletedContributions
'deletedcontributions'             => 'Forigitaj kontribuoj de uzantoj',
'deletedcontributions-title'       => 'Forigitaj kontribuoj de uzantoj',
'sp-deletedcontributions-contribs' => 'kontribuoj',

# Special:LinkSearch
'linksearch'       => 'Eksteraj ligiloj',
'linksearch-pat'   => 'Serĉesprimo:',
'linksearch-ns'    => 'Nomspaco:',
'linksearch-ok'    => 'Serĉi',
'linksearch-text'  => 'Regulesprimoj kiel "*.wikipedia.org" povas esti uzataj.<br />Subtenataj protokoloj: <tt>$1</tt>',
'linksearch-line'  => '$1 ligita de $2',
'linksearch-error' => 'Regulesprimoj povas aperi nur ĉe la komenco de la retnoda nomo.',

# Special:ListUsers
'listusersfrom'      => 'Montri uzantojn ekde:',
'listusers-submit'   => 'Montri',
'listusers-noresult' => 'Neniu uzanto trovita.',
'listusers-blocked'  => '(forbarita)',

# Special:ActiveUsers
'activeusers'          => 'Listo de aktivaj uzantoj',
'activeusers-count'    => '$1 {{PLURAL:$1|lasta redakto|lastaj redaktoj}} dum la {{PLURAL:$3|lasta tago|lastaj $3 tagoj}}',
'activeusers-from'     => 'Montri uzantojn ekde:',
'activeusers-noresult' => 'Neniuj uzantoj trovitaj.',

# Special:Log/newusers
'newuserlogpage'              => 'Protokolo de uzanto-kreado',
'newuserlogpagetext'          => 'Jen protokolo de lastaj kreadoj de uzantoj.',
'newuserlog-byemail'          => 'pasvorto sendita retpoŝte',
'newuserlog-create-entry'     => 'Nova uzanto',
'newuserlog-create2-entry'    => 'kreis novan konton $1',
'newuserlog-autocreate-entry' => 'Konto kreita aŭtomate',

# Special:ListGroupRights
'listgrouprights'                      => 'Gruprajtoj de uzantoj',
'listgrouprights-summary'              => 'Jen listo de uzanto-grupoj difinitaj en ĉi tiu vikio, kun ties asociaj atingrajtoj.
Estas [[{{MediaWiki:Listgrouprights-helppage}}|aldona informo]] pri individuaj rajtoj.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Donita rajto</span>
* <span class="listgrouprights-revoked">Maldonita rajto</span>',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Rajtoj',
'listgrouprights-helppage'             => 'Help:Grupaj rajtoj',
'listgrouprights-members'              => '(listo de anoj)',
'listgrouprights-addgroup'             => 'Povi aldoni {{PLURAL:$2|grupon|grupojn}}: $1',
'listgrouprights-removegroup'          => 'Povi forigi {{PLURAL:$2|grupon|grupojn}}: $1',
'listgrouprights-addgroup-all'         => 'Povi aldoni ĉiujn grupojn',
'listgrouprights-removegroup-all'      => 'Povi forigi ĉiujn grupojn',
'listgrouprights-addgroup-self'        => 'Povas aldoni {{PLURAL:$2|grupon|grupojn}} al propra konto: $1',
'listgrouprights-removegroup-self'     => 'Povas forigi {{PLURAL:$2|grupon|grupojn}} de propra konto: $1',
'listgrouprights-addgroup-self-all'    => 'Povas aldoni ĉiujn grupojn al propra konto',
'listgrouprights-removegroup-self-all' => 'Povas forigi ĉiujn grupojn de propra konto',

# E-mail user
'mailnologin'      => 'Neniu alsendota adreso',
'mailnologintext'  => 'Vi nepre estu [[Special:UserLogin|salutanta]] kaj havanta validan retpoŝtadreson en viaj [[Special:Preferences|preferoj]] por retpoŝti al aliaj uzantoj.',
'emailuser'        => 'Retpoŝti ĉi tiun uzanton',
'emailpage'        => 'Retpoŝti uzanton',
'emailpagetext'    => 'Vi povas uzi la jenan paĝon por sendi retpoŝtan mesaĝon al ĉi tiu uzanto.
La retadreso kiun vi enigis en [[Special:Preferences|viaj preferoj]] aperos kiel la "De" adreso de la retpoŝto, do la ricevonto eblos respondi rekte al vi.',
'usermailererror'  => 'Resendita retmesaĝa erarsubjekto:',
'defemailsubject'  => '{{SITENAME}} retmesaĝo',
'noemailtitle'     => 'Neniu retpoŝtadreso',
'noemailtext'      => 'Ĉi tiu uzanto ne donis validan retadreson.',
'nowikiemailtitle' => 'Retpoŝto ne permesita',
'nowikiemailtext'  => 'Ĉi tiu uzanto elektis ne ricevi retpoŝton de aliaj uzantoj.',
'email-legend'     => 'Sendi retpoŝton al alia {{SITENAME}}-uzanto',
'emailfrom'        => 'De:',
'emailto'          => 'Al:',
'emailsubject'     => 'Temo:',
'emailmessage'     => 'Mesaĝo:',
'emailsend'        => 'Sendi',
'emailccme'        => 'Retpoŝti al mi kopion de mia mesaĝo.',
'emailccsubject'   => 'Kopio de via mesaĝo al $1: $2',
'emailsent'        => 'Retmesaĝo sendita',
'emailsenttext'    => 'Via retmesaĝo estas sendita.',
'emailuserfooter'  => 'Ĉi tiun retpoŝton sendis $1 al $2 per funkcio "Email user" ĉe {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Atentaro',
'mywatchlist'          => 'Atentaro',
'watchlistfor'         => '(por <b>$1</b>)',
'nowatchlist'          => 'Vi ne jam elektis priatenti iun ajn paĝon.',
'watchlistanontext'    => 'Bonvolu $1 por vidi aŭ redakti erojn en via atentaro.',
'watchnologin'         => 'Ne ensalutinta',
'watchnologintext'     => 'Nepras [[Special:UserLogin|ensaluti]] por ŝanĝi vian atentaron.',
'addedwatch'           => 'Aldoniĝis al atentaro',
'addedwatchtext'       => "La paĝo \"[[:\$1]]\" estis aldonita al via [[Special:Watchlist|atentaro]]. Estontaj ŝanĝoj de tiu ĉi paĝo aperos en '''grasa tiparo''' en la [[Special:RecentChanges|listo de Lastaj Ŝanĝoj]], kaj estos listigitaj en via atentaro. Se vi poste volos forigi la paĝon el via atentaro, alklaku \"Malatentu paĝon\" en la ilobreto.",
'removedwatch'         => 'Forigis el atentaro',
'removedwatchtext'     => 'La paĝo "[[:$1]]" estas forigita el via [[Special:Watchlist|atentaro]].',
'watch'                => 'Atenti',
'watchthispage'        => 'Priatenti paĝon',
'unwatch'              => 'Malatenti',
'unwatchthispage'      => 'Malatenti paĝon',
'notanarticle'         => 'Ne estas artikolo',
'notvisiblerev'        => 'Versio estis forigita',
'watchnochange'        => 'Neniu artikolo en via atentaro redaktiĝis dum la prispektita tempoperiodo.',
'watchlist-details'    => '{{PLURAL:$1|$1 paĝon|$1 paĝojn}} en via atentaro, krom diskutpaĝoj.',
'wlheader-enotif'      => '* Retpoŝta sciigo estas ebligita',
'wlheader-showupdated' => "* Montriĝas per '''dikaj literoj''' tiuj paĝoj, kiujn oni ŝanĝis ekde kiam vi laste vizitis ilin",
'watchmethod-recent'   => 'traserĉas lastajn redaktojn',
'watchmethod-list'     => 'traserĉas priatentitajn',
'watchlistcontains'    => 'Via atentaro enhavas $1 {{PLURAL:$1|paĝon|paĝojn}}.',
'iteminvalidname'      => 'Ia eraro pri "$1", nevalida titolo...',
'wlnote'               => "Jen la {{PLURAL:$1|lasta redakto|lastaj '''$1''' redaktoj}} dum la {{PLURAL:$2|lasta horo|lastaj '''$2''' horoj}}.",
'wlshowlast'           => 'Montri el lastaj $1 horoj $2 tagoj $3',
'watchlist-options'    => 'Opcioj por atentaro',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Aldonante al la atentaro...',
'unwatching' => 'Malatentante...',

'enotif_mailer'                => 'Averta retmesaĝo de {{SITENAME}}',
'enotif_reset'                 => 'Marki ĉiujn vizititajn paĝojn',
'enotif_newpagetext'           => 'Tiu ĉi estas nova paĝo',
'enotif_impersonal_salutation' => 'Uzanto de {{SITENAME}}',
'changed'                      => 'ŝanĝita',
'created'                      => 'kreita',
'enotif_subject'               => 'la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'           => 'Vidi $1 por ĉiuj ŝanĝoj de post via lasta vizito.',
'enotif_lastdiff'              => 'Vidi $1 por rigardi ĉi tiun ŝanĝon.',
'enotif_anon_editor'           => 'anonima uzanto $1',
'enotif_body'                  => 'Kara $WATCHINGUSERNAME,

La paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED je $PAGEEDITDATE de $PAGEEDITOR, vidu $PAGETITLE_URL por la nuna versio.

$NEWPAGE

Redakta resumo: $PAGESUMMARY $PAGEMINOREDIT

Kontaktu la redaktinton:
retpoŝte: $PAGEEDITOR_EMAIL
vikie: $PAGEEDITOR_WIKI

Ne estos aliaj avertoj kaze de sekvaj ŝanĝoj krom se vi vizitas la paĝon. 
Vi povas ankaŭ malaktivigi la avertsignalon por ĉiuj priatentitaj paĝoj de via atentaro.

             Sincere via, la avertsistemo de {{SITENAME}}

--
Por ŝanĝi la elektojn de via atentaro, bv viziti
{{fullurl:{{#special:Watchlist}}/edit}}

Reagoj kaj plia helpo:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Forigi paĝon',
'confirm'                => 'Konfirmi',
'excontent'              => "enhavis: '$1'",
'excontentauthor'        => "la enteno estis : '$1' (kaj la sola kontribuinto estis '$2')",
'exbeforeblank'          => "antaŭ malplenigo enhavis: '$1'",
'exblank'                => 'estis malplena',
'delete-confirm'         => 'Forigi "$1"',
'delete-legend'          => 'Forigi',
'historywarning'         => 'Averto: la forigota paĝo havas historion:',
'confirmdeletetext'      => 'Vi forigos la artikolon aŭ dosieron kaj forviŝos ĝian tutan historion el la datumaro.<br /> Bonvolu konfirmi, ke vi vere intencas tion, kaj ke vi komprenas la sekvojn, kaj ke vi ja sekvas la [[{{MediaWiki:Policy-url}}|regulojn pri forigado]].',
'actioncomplete'         => 'Ago farita',
'actionfailed'           => 'Ago malsukcesis',
'deletedtext'            => '"<nowiki>$1</nowiki>" estas forigita.
Vidu la paĝon $2 por registro de lastatempaj forigoj.',
'deletedarticle'         => 'forigis "$1"',
'suppressedarticle'      => '"[[$1]]" estas subigita',
'dellogpage'             => 'Protokolo pri forigoj',
'dellogpagetext'         => 'Jen listo de la plej lastaj forigoj el la datumaro.
Ĉiuj tempoj sekvas la horzonon UTC.',
'deletionlog'            => 'protokolo pri forigoj',
'reverted'               => 'Malfaris al antaŭa revisio',
'deletecomment'          => 'Kialo por forigo:',
'deleteotherreason'      => 'Alia/plua kialo:',
'deletereasonotherlist'  => 'Alia kialo',
'deletereason-dropdown'  => '*Oftaj kialoj por forigo
** Peto de aŭtoro
** Malobservo de kopirajto
** Vandalismo',
'delete-edit-reasonlist' => 'Redakti kialojn de forigo',
'delete-toobig'          => 'Ĉi tiu paĝo havas grandan redakto-historion, pli ol $1 {{PLURAL:$1|version|versiojn}}. Forigo de ĉi tiaj paĝoj estis limigitaj por preventi akcidentan disrompigon de {{SITENAME}}.',
'delete-warning-toobig'  => 'Ĉi tiu paĝo havas grandan redakto-historion, pli ol $1 {{PLURAL:$1|version|versiojn}}. Forigo de ĝi povas disrompigi operacion de {{SITENAME}}; forigu singarde.',

# Rollback
'rollback'          => 'Restarigi antaŭan redakton',
'rollback_short'    => 'Malfari',
'rollbacklink'      => 'malfari',
'rollbackfailed'    => 'Malfaro malsukcesis',
'cantrollback'      => 'Neeblas restarigi antaŭan redakton; la redaktinto lasta estas la sola de la paĝo.',
'alreadyrolled'     => 'Ne eblas restarigi la lastan redakton de [[:$1]] de la [[User:$2|$2]] ([[User talk:$2|diskuto]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
pro tio, ke oni intertempe redaktis aŭ restarigis la paĝon. 
La lasta redaktinto estas [[User:$3|$3]] ([[User talk:$3|diskuto]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "La resumo de la komento estis: \"''\$1''\".",
'revertpage'        => 'Malfaris redaktojn de [[Special:Contributions/$2|$2]] ([[User talk:$2|diskuto]]) al la lasta versio de [[User:$1|$1]]',
'revertpage-nouser' => 'Restarigita redaktoj de (salutnomo forigita) al lasta revizio de [[User:$1|$1]]',
'rollback-success'  => 'Restaris redaktojn de $1; ŝanĝis al lasta versio de $2.',
'sessionfailure'    => 'Ŝajnas ke estas problemo kun via ensalutado;
Ĉi ago estis nuligita por malhelpi fiensalutadon.
Bonvolu alklalki la reirbutonon kaj reŝarĝi la paĝon el kiu vi venas, kaj provu denove.',

# Protect
'protectlogpage'              => 'Protokolo pri protektoj',
'protectlogtext'              => 'Sube estas listo de paĝ-ŝlosoj kaj malŝlosoj.
Vidu [[Special:ProtectedPages|liston de protektitaj paĝoj]] por pli da informoj.',
'protectedarticle'            => 'protektis "[[$1]]"',
'modifiedarticleprotection'   => 'ŝanĝis nivelon de protekto por "[[$1]]"',
'unprotectedarticle'          => 'malprotektis "[[$1]]"',
'movedarticleprotection'      => 'movis protektadajn preferojn de "[[$2]]" al "[[$1]]"',
'protect-title'               => 'Protektante "$1"',
'prot_1movedto2'              => '[[$1]] movita al [[$2]]',
'protect-legend'              => 'Konfirmi protektadon',
'protectcomment'              => 'Kialo:',
'protectexpiry'               => 'Eksvalidiĝas:',
'protect_expiry_invalid'      => 'Nevalida findaŭro.',
'protect_expiry_old'          => 'Eksvalidiĝa tempo jam pasis.',
'protect-unchain'             => 'Malŝlosi movrajtojn',
'protect-text'                => "Vi povas ĉi tie vidi kaj ŝanĝi la protektnivelon de la paĝo '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Vi ne povas ŝanĝi prokekto-nivelojn dum forbarita. Jen la nunaj ecoj de la paĝo '''$1''':",
'protect-locked-dblock'       => "Ne eblas ŝanĝi nivelojn de protekto pro aktiva datumbaza ŝloso.
Jen la nunaj ecoj de la paĝo '''$1''':",
'protect-locked-access'       => "Via konto ne havas rajton ŝanĝi protekto-nivelojn.
Jen la aktualaj valoroj por la paĝo '''$1''':",
'protect-cascadeon'           => 'Ĉi paĝo estas nun protektita kontraŭ redaktado ĉar ĝi estas inkluzivita en {{PLURAL:$1|jena paĝo, kiu mem estas protektita|jenaj paĝoj, kiuj mem estas protektitaj}} per kaskada protekto. 
Vi povas ŝanĝi ties protektnivelon, sed tio ne ŝanĝos la kaskadan protekton.',
'protect-default'             => 'Permesigi ĉiujn uzantojn',
'protect-fallback'            => 'Rajto "$1" nepras.',
'protect-level-autoconfirmed' => 'Bloki novajn kaj neregistritajn uzantojn',
'protect-level-sysop'         => 'Nur administrantoj',
'protect-summary-cascade'     => 'kaskada',
'protect-expiring'            => 'finiĝas je $1 (UTC)',
'protect-expiry-indefinite'   => 'sendifina',
'protect-cascade'             => 'Protekti ĉiujn paĝojn inkluzivitajn en ĉi paĝo (kaskada protekto)',
'protect-cantedit'            => 'Vi ne povas ŝanĝi la protekt-nivelojn de ĉi tiu paĝo, ĉar vi ne rajtas redakti ĝin.',
'protect-othertime'           => 'Alia tempo:',
'protect-othertime-op'        => 'alia tempo',
'protect-existing-expiry'     => 'Ekzistanta protektdaŭro: $3, $2',
'protect-otherreason'         => 'Alia/plua kialo:',
'protect-otherreason-op'      => 'alia/plua kialo',
'protect-dropdown'            => '*Oftaj kialoj por protektado
** Tro da vanadlismo
** Tro da spamado
** Malutila redakto-milito
** Paĝo kun multo da trafiko',
'protect-edit-reasonlist'     => 'Redakti kialojn de protektado',
'protect-expiry-options'      => '1 horo:1 hour,1 tago:1 day,1 semajno:1 week,2 semajnoj:2 weeks,1 monato:1 month,3 monatoj:3 months,6 monatoj:6 months,1 jaro:1 year,porĉiam:infinite',
'restriction-type'            => 'Permeso:',
'restriction-level'           => 'Nivelo de limigo:',
'minimum-size'                => 'Minimuma pezo',
'maximum-size'                => 'Maksimuma pezo:',
'pagesize'                    => '(bitokoj)',

# Restrictions (nouns)
'restriction-edit'   => 'Redakti',
'restriction-move'   => 'Alinomigi',
'restriction-create' => 'Krei',
'restriction-upload' => 'Alŝuti',

# Restriction levels
'restriction-level-sysop'         => 'plene protektita',
'restriction-level-autoconfirmed' => 'duone protektita',
'restriction-level-all'           => 'iu nivelo',

# Undelete
'undelete'                     => 'Rigardi forigitajn paĝojn',
'undeletepage'                 => 'Montri kaj restarigi forigitajn paĝojn',
'undeletepagetitle'            => "'''Jen la forigitaj versioj de [[:$1]]'''.",
'viewdeletedpage'              => 'Rigardi forigitajn paĝojn',
'undeletepagetext'             => 'La {{PLURAL:$1|jena paĝo estis forigita|jenaj paĝoj estis forigitaj}}, sed ankoraŭ restas {{PLURAL:$1|arkivita|arkivitaj}} kaj {{PLURAL:$1|restarigebla|restarigeblaj}}. 
La arkivo povas esti malplenigita periode.',
'undelete-fieldset-title'      => 'Malforigi versiojn',
'undeleteextrahelp'            => "Por restarigi la tuton de la paĝo, lasu ĉiujn markobutonoj malŝaltitaj kaj klaku la butonon '''''Restarigi'''''. 
Por restarigi selektitajn versiojn de la paĝo, marku la butonojn konformajn al la dezirataj versioj, kaj klaku la butonon '''''Restarigi'''''. 
Klakante butonon '''''Restarigi''''' viŝigos la komentokampon kaj malmarkos ĉiujn la markobutonojn.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versio arkivita|versioj arkivitaj}}',
'undeletehistory'              => 'Se vi restarigos la paĝon, ĉiuj versioj estos restarigitaj en la historio. 
Se nova paĝo kun la sama nomo estis kreita post la forigo, la restarigitaj versioj aperos antaŭe en la antaŭa historio.',
'undeleterevdel'               => 'Restarigo ne estos farita se ĝi rezultos en la supera paĝa aŭ dosiera versio estonte parte forigita. Tiuzake, vi malmarku aŭ malkaŝu la plej novajn forigitajn versiojn.',
'undeletehistorynoadmin'       => 'Ĉi tiu artikolo estis forigita. La kaŭzo por la forigo estas montrata en la malsupra resumo, kune kun detaloj pri la uzantoj, kiuj redaktis ĉi tiun paĝon antaŭ la forigo. La aktuala teksto de ĉi tiuj forigitaj versioj estas atingebla nur de administrantoj.',
'undelete-revision'            => 'Forigita versio de $1 (ekde $4, $5) fare de $3:',
'undeleterevision-missing'     => 'Nevalida aŭ malaperita versio. 
Vi verŝajne havas malbonan ligilon, aŭ la versio eble estis restarigita aŭ forigita de la arkivo.',
'undelete-nodiff'              => 'Neniu antaŭa versio troviĝis.',
'undeletebtn'                  => 'Restarigi',
'undeletelink'                 => 'vidi/restarigi',
'undeleteviewlink'             => 'vidi',
'undeletereset'                => 'Restarigi',
'undeleteinvert'               => 'Inversigi selektaĵon',
'undeletecomment'              => 'Komento:',
'undeletedarticle'             => 'restarigis "$1"',
'undeletedrevisions'           => '{{PLURAL:$1|1 versio restarigita|$1 versioj restarigitaj}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 versio|$1 versioj}} kaj {{PLURAL:$2|1 dosiero|$2 dosieroj}} restarigitaj',
'undeletedfiles'               => '{{PLURAL:$1|1 dosiero restarigita|$1 dosieroj restarigitaj}}',
'cannotundelete'               => 'Restarigo malsukcesis; iu eble restarigis la paĝon antaŭe.',
'undeletedpage'                => "<big>'''$1 estis restarigita'''</big>

Konsultu la [[Special:Log/delete|deletion log]] por protokolo pri la lastatempaj forigoj kaj restarigoj.",
'undelete-header'              => 'Konsulti la [[Special:Log/delete|protokolo de forigoj]] por lastatempaj forigoj.',
'undelete-search-box'          => 'Serĉi forigitajn paĝojn',
'undelete-search-prefix'       => 'Montri paĝojn komence kun:',
'undelete-search-submit'       => 'Serĉi',
'undelete-no-results'          => 'Neniuj kongruaj paĝoj trovitaj en la forigo-arkivo.',
'undelete-filename-mismatch'   => 'Ne eblas restarigi dosiero-version kun tempo $1: malkongrua dosiernomo',
'undelete-bad-store-key'       => 'Ne eblas restarigi dosiero-versio de tempo $1: dosiero estis malaperita antaŭ forigo.',
'undelete-cleanup-error'       => 'Eraro forigante la neuzatan arkivon "$1".',
'undelete-missing-filearchive' => 'Ne eblas restarigi dosiera arkivo ID $1 ĉar ĝi ne estas en la datumbazo. Verŝajne ĝi jam estis restarigita.',
'undelete-error-short'         => 'Eraro pro restarigo de dosiero: $1',
'undelete-error-long'          => 'Jen eraroj dum restarigo de dosiero:

$1',
'undelete-show-file-confirm'   => 'Ĉu vi vere volas vidi forigitan version de la dosiero "<nowiki>$1</nowiki>" je $2 $3?',
'undelete-show-file-submit'    => 'Jes',

# Namespace form on various pages
'namespace'      => 'Nomspaco:',
'invert'         => 'Inversigi elektaĵon',
'blanknamespace' => '(Artikoloj)',

# Contributions
'contributions'       => 'Kontribuoj de uzanto',
'contributions-title' => 'Uzulaj kontribuoj de $1',
'mycontris'           => 'Miaj kontribuoj',
'contribsub2'         => 'De $1 ($2)',
'nocontribs'          => 'Trovis neniajn redaktojn laŭ tiu kriterio.',
'uctop'               => ' (lasta)',
'month'               => 'Ekde monato (kaj pli frue):',
'year'                => 'Ekde jaro (kaj pli frue):',

'sp-contributions-newbies'        => 'Montri nur kontribuojn de novaj kontoj',
'sp-contributions-newbies-sub'    => 'Kontribuoj de novaj uzantoj. Forigitaj paĝoj ne estas montritaj.',
'sp-contributions-newbies-title'  => 'Uzulaj kontribuoj de novaj kontoj',
'sp-contributions-blocklog'       => 'Protokolo de forbaroj',
'sp-contributions-deleted'        => 'forigitaj kontribuoj de uzantoj',
'sp-contributions-logs'           => 'protokoloj',
'sp-contributions-talk'           => 'diskuto',
'sp-contributions-userrights'     => 'administri rajtojn de uzantoj',
'sp-contributions-blocked-notice' => 'La uzanto nune estas forbarita. Jen la lasta blokada listero en protokolo:',
'sp-contributions-search'         => 'Serĉado de kontribuoj',
'sp-contributions-username'       => 'IP-adreso aŭ salutnomo:',
'sp-contributions-submit'         => 'Serĉi',

# What links here
'whatlinkshere'            => 'Ligiloj ĉi tien',
'whatlinkshere-title'      => 'Paĝoj ligantaj al "$1"',
'whatlinkshere-page'       => 'Paĝo:',
'linkshere'                => "La jenaj paĝoj ligas al '''[[:$1]]''':",
'nolinkshere'              => "Neniu paĝo ligas al '''[[:$1]]'''.",
'nolinkshere-ns'           => "Neniuj paĝoj ligas al '''[[:$1]]''' en la elektita nomspaco.",
'isredirect'               => 'alidirektilo',
'istemplate'               => 'inkludo',
'isimage'                  => 'ligilo al bildo',
'whatlinkshere-prev'       => '{{PLURAL:$1|antaŭa|antaŭaj $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|posta|postaj $1}}',
'whatlinkshere-links'      => '← ligiloj',
'whatlinkshere-hideredirs' => '$1 alidirektiloj',
'whatlinkshere-hidetrans'  => '$1 transinkluzivaĵoj',
'whatlinkshere-hidelinks'  => '$1 ligiloj',
'whatlinkshere-hideimages' => '$1 ligiloj al bildo',
'whatlinkshere-filters'    => 'Filtriloj',

# Block/unblock
'blockip'                         => 'Forbari uzanton/IP-adreson',
'blockip-legend'                  => 'Forbari uzanton',
'blockiptext'                     => "Per jena formularo vi povas forpreni de ajna nomo aŭ IP-adreso la rajton skribi en la vikio. Oni faru tion ''nur'' por eviti vandalismon, kaj sekvante la [[{{MediaWiki:Policy-url}}|regulojn pri forbarado]]. Klarigu la precizan kialon malsupre (ekzemple, citu paĝojn, kiuj estis vandaligitaj).",
'ipaddress'                       => 'IP-adreso/nomo',
'ipadressorusername'              => 'IP-adreso aŭ salutnomo:',
'ipbexpiry'                       => 'Blokdaŭro',
'ipbreason'                       => 'Kialo:',
'ipbreasonotherlist'              => 'Alia kaŭzo',
'ipbreason-dropdown'              => '*Oftaj kialoj de forbaro
** Enmetas malveraĵojn
** Forviŝas entenon el paĝoj
** Entrudas ligilojn al eksteraj paĝaroj
** Enmetas sensencaĵojn
** Terurigema sinteno
** Misuzo de pluraj salutnomoj
** Neakceptebla uzanto-nomo',
'ipbanononly'                     => 'Forbari nur anonimulojn',
'ipbcreateaccount'                => 'Preventi kreadon de konto',
'ipbemailban'                     => 'Malebligi al uzanto sendi retpoŝton.',
'ipbenableautoblock'              => 'Aŭtomate forbaru la lastan IP-adreson uzitan de la uzanto, kaj ĉiajn subsekvantajn adresojn el kiuj tiu provos redakti',
'ipbsubmit'                       => 'Forbari ĉi tiun uzanton',
'ipbother'                        => 'Alia daŭro:',
'ipboptions'                      => '2 horoj:2 hours,1 tago:1 day,3 tagoj:3 days,1 semajno:1 week,2 semajnoj:2 weeks,1 monato:1 month,3 monatoj:3 months,6 monatoj:6 months,1 jaro:1 year,porĉiam:infinite',
'ipbotheroption'                  => 'alia',
'ipbotherreason'                  => 'Alia/kroma kialo:',
'ipbhidename'                     => 'Kaŝi salutnomon de redaktoj kaj listoj',
'ipbwatchuser'                    => 'Kontroli la paĝojn por uzanto kaj diskuto de ĉi tiu uzanto.',
'ipballowusertalk'                => 'Permsi al ĉi tiu uzanto redakti propran diskuto-paĝon dum forbaro',
'ipb-change-block'                => 'Reforbari la uzanton kun ĉi tiuj preferoj',
'badipaddress'                    => 'Neniu uzanto, aŭ la IP-adreso estas misformita.',
'blockipsuccesssub'               => 'Oni sukcese forbaris la adreson/nomon.',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] estas forbarita. <br />
Vidu la [[Special:IPBlockList|liston de IP-forbaroj]] por kontroli.',
'ipb-edit-dropdown'               => 'Redakti kialojn por forbaro.',
'ipb-unblock-addr'                => 'Restarigi $1',
'ipb-unblock'                     => 'Malforbari salutnomon aŭ IP-adreson',
'ipb-blocklist-addr'              => 'Ekzistantaj forbaroj por $1',
'ipb-blocklist'                   => 'Vidi ekzistantajn forbarojn',
'ipb-blocklist-contribs'          => 'Kontribuoj de $1',
'unblockip'                       => 'Malforbari IP-adreson/nomon',
'unblockiptext'                   => 'Per la jena formulo vi povas repovigi al iu
forbarita IP-adreso/nomo la povon enskribi en la vikio.',
'ipusubmit'                       => 'Forigi ĉi tiun forbaron',
'unblocked'                       => '[[User:$1|$1]] estas restarigita.',
'unblocked-id'                    => 'Forbaro $1 estas forigita.',
'ipblocklist'                     => 'Forbaritaj IP-adresoj kaj salutnomoj',
'ipblocklist-legend'              => 'Trovi forbaritan uzanton.',
'ipblocklist-username'            => 'Salutnomo aŭ IP-adreso:',
'ipblocklist-sh-userblocks'       => '$1 kontaj forbaroj',
'ipblocklist-sh-tempblocks'       => '$1 provizoraj forbaroj',
'ipblocklist-sh-addressblocks'    => '$1 unuopaj IP-adresaj foraroj',
'ipblocklist-submit'              => 'Serĉi',
'blocklistline'                   => 'Je $1, $2 forbaris $3 ($4)',
'infiniteblock'                   => 'senfina',
'expiringblock'                   => 'senvalidiĝas je $1 $2',
'anononlyblock'                   => 'nur anonimuloj',
'noautoblockblock'                => 'aŭtomata blokado estas malebligita',
'createaccountblock'              => 'Kreado de kontoj forbarita',
'emailblock'                      => 'retpoŝto forbarita',
'blocklist-nousertalk'            => 'ne povas redakti sian propran diskuto-paĝon',
'ipblocklist-empty'               => 'La forbarlibro estas malplena.',
'ipblocklist-no-results'          => 'Ĉi tiu IP-adreso aŭ salutnomo ne estas forbarita.',
'blocklink'                       => 'forbari',
'unblocklink'                     => 'restarigi',
'change-blocklink'                => 'ŝanĝi forbaron',
'contribslink'                    => 'kontribuoj',
'autoblocker'                     => 'Aŭtomate forbarita ĉar via IP-adreso estis lastatempe uzita de "[[User:$1|$1]]".
La kialo donita por la forbaro de $1 estis: "$2"',
'blocklogpage'                    => 'Protokolo pri forbaroj',
'blocklog-showlog'                => 'Ĉi tiu uzanto estis forbarita antaŭe. Jen la forbara protokolo por via informo:',
'blocklog-showsuppresslog'        => 'Ĉi tiu uzanto estis forbarita kaj kaŝita antaŭe. Jen la protokolo pri subpremado por via informo:',
'blocklogentry'                   => 'forbaris [[$1]] por daŭro de $2 $3',
'reblock-logentry'                => 'ŝanĝis forbarajn opciojn [[$1]] kun findato de $2 $3',
'blocklogtext'                    => 'Ĉi tio estas protokolo pri forbaraj kaj malforbaraj agoj. Aŭtomate forbaritaj IP adresoj ne estas listigitaj. Vidu la [[Special:IPBlockList|IP forbarliston]] por ĉi-momente fobaritaj uzantoj kaj IP-adresoj.',
'unblocklogentry'                 => '$1 estis restarigita',
'block-log-flags-anononly'        => 'nur anonimaj uzantoj',
'block-log-flags-nocreate'        => 'kreado de kontoj malebligita',
'block-log-flags-noautoblock'     => 'aŭtomata forbaro malŝaltita',
'block-log-flags-noemail'         => 'retpoŝto blokita',
'block-log-flags-nousertalk'      => 'ne povas redakti propran diskuto-paĝon',
'block-log-flags-angry-autoblock' => 'progresa aŭtoforbaro ebliĝis',
'block-log-flags-hiddenname'      => 'salutnomo kaŝita',
'range_block_disabled'            => 'La ebleco de administranto krei forbaritajn intervalojn da IP-adresoj estas malebligita.',
'ipb_expiry_invalid'              => 'Nevalida blokdaŭro.',
'ipb_expiry_temp'                 => 'Kaŝitaj salutnomaj blokoj estu daŭraj.',
'ipb_hide_invalid'                => 'Ne eblas subpremi ĉi tiun konton; ĝi eble havas tro multajn redaktojn.',
'ipb_already_blocked'             => '"$1" estas jam forbarita',
'ipb-needreblock'                 => '== Jam forbarita ==
$1 estas jam forbarita. Ĉu vi volas ŝanĝi la opciojn?',
'ipb_cant_unblock'                => 'Eraro: Forbar-identigo $1 ne estas trovita. Ĝi eble estis jam malforbarita.',
'ipb_blocked_as_range'            => 'Eraro: La IP-adreso $1 ne estas forbarita rekte kaj ne povas esti malforbarita. Tamen ĝi estas forbarita kiel parto de la intervalo $2, kiu ne povas esti malforbarita.',
'ip_range_invalid'                => 'Malvalida IP-adresa intervalo.',
'blockme'                         => 'Forbari min',
'proxyblocker'                    => 'Forbarilo por prokuriloj.',
'proxyblocker-disabled'           => 'Ĉi tiu funkcio estas malŝaltita.',
'proxyblockreason'                => 'Via IP-adreso estis forbarita ĉar ĝi estas malferma prokurilo. Bonvolu kontakti vian provizanto de retservo aŭ komputika helpisto kaj informu ilin de ĉi serioza problemo pri sekureco.',
'proxyblocksuccess'               => 'Farita.',
'sorbsreason'                     => 'Via IP-adreso estas listigita kiel malferma prokurilo en la DNSBL uzata de {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Via IP-adreso estas listigita kiel malferma prokurilo en la DNSBL uzata de {{SITENAME}}. Vi ne rajtas krei konton.',
'cant-block-while-blocked'        => 'Vi ne povas forbari aliajn uzantojn dum vi estas forbarita.',
'cant-see-hidden-user'            => 'La uzanto kiun vi provas forbari jam estis forbarita kaj kaŝita. Kiel vi ne havas la rajton kaŝi uzanton, vi ne povas vidi aŭ redakti la forbaron de la uzanto.',

# Developer tools
'lockdb'              => 'Ŝlosi datumbazon',
'unlockdb'            => 'Malŝlosi datumaron',
'lockdbtext'          => 'Se vi ŝlosos la datumaron, tio malebligos al ĉiuj uzantoj
redakti paĝojn, ŝanĝi preferojn, priumi atentarojn, kaj fari diversajn aliajn
aferojn, por kiuj nepras ŝanĝi la datumaron.
Bonvolu certigu, ke vi efektive intencas tion fari, kaj ke vi ja malŝlosos
la datumaron post ol vi finos vian riparadon.',
'unlockdbtext'        => 'Se vi malŝlosos la datumaron, tio reebligos al ĉiuj uzantoj
redakti paĝojn, ŝanĝi preferojn, priumi la atentaron, kaj fari aliajn aferojn,
por kiuj nepras ŝanĝi al la datumaro.
Bonvolu certigu, ke vi efektive intencas tion fari.',
'lockconfirm'         => 'Jes, mi vere volas ŝlosi la datumaron.',
'unlockconfirm'       => 'Jes, mi vere volas malŝlosi la datumaron.',
'lockbtn'             => 'Ŝlosi datumbazon',
'unlockbtn'           => 'Malŝlosi datumaron',
'locknoconfirm'       => 'Vi ne konfirmis.',
'lockdbsuccesssub'    => 'Datumaro ŝlosita',
'unlockdbsuccesssub'  => 'Datumaro malŝlosita',
'lockdbsuccesstext'   => 'La datumaro de {{SITENAME}} estas ŝlosita.
<br />Ne forgesu malŝlosi ĝin post kiam vi finos la riparadon.',
'unlockdbsuccesstext' => 'La datumaro de {{SITENAME}} estas malŝlosita.',
'lockfilenotwritable' => 'La datumbaza dosiero pri ŝlosado ne estas skribebla. Por ŝlosi aŭ malŝlosi la datumbazon, ĉi devas esti skribebla de la TTT-servilo.',
'databasenotlocked'   => 'La datumbazo ne estas ŝlosita.',

# Move page
'move-page'                    => 'Alinomigi $1',
'move-page-legend'             => 'Alinomigi paĝon',
'movepagetext'                 => "Per la jena formulo vi povas ŝanĝi la nomon de iu paĝo, kunportante
ĝian historion de redaktoj je la nova nomo.
La antaŭa titolo fariĝos alidirektilo al la nova titolo.
Ligiloj al la antaŭa titolo <i>ne</i> estos ŝanĝitaj; uzu
la riparilojn kaj zorgilojn por certigi,
ke ne restos duoblaj aŭ fuŝitaj alidirektiloj.
Kiel movanto, vi respondecas pri ĝustigado de fuŝitaj ligiloj.

Notu, ke la paĝo '''ne''' estos movita se jam ekzistas paĝo
ĉe la nova titolo, krom se ĝi estas malplena aŭ alidirektilo
al ĉi tiu paĝo, kaj sen antaŭa redaktohistorio. Pro tio, vi ja
povos removi la paĝon je la antaŭa titolo se vi mistajpus, kaj
neeblas ke vi neintence forviŝus ekzistantan paĝon per movo.

<b>AVERTO!</b>
Tio povas esti drasta kaj neatendita ŝanĝo por populara paĝo;
bonvolu certigi vin, ke vi komprenas ties konsekvencojn antaŭ
ol vi antaŭeniru.",
'movepagetalktext'             => "La movo aŭtomate kunportos la diskuto-paĝon, se tia ekzistas, '''krom se:'''
*Vi movas la paĝon tra nomspacoj (ekz de ''Nomo'' je ''User:Nomo''),
*Ne malplena diskuto-paĝo jam ekzistas je la nova nomo, aŭ
*Vi malelektas la suban ŝaltilon.

Tiujokaze, vi nepre permane kunigu la diskuto-paĝojn se vi tion deziras.",
'movearticle'                  => 'Alinomigi paĝon',
'movenologin'                  => 'Ne ensalutinta',
'movenologintext'              => 'Vi nepre estu registrita uzanto kaj [[Special:UserLogin|ensalutu]] por rajti movi paĝojn.',
'movenotallowed'               => 'Vi ne rajtas movi paĝojn.',
'movenotallowedfile'           => 'Vi ne havas rajton alinomigi dosierojn.',
'cant-move-user-page'          => 'Vi ne rajtas movi radikajn uzanto-paĝojn.',
'cant-move-to-user-page'       => 'Vi ne rajtas movi paĝon al uzula paĝo (krom al uzula subpaĝo).',
'newtitle'                     => 'Al nova titolo',
'move-watch'                   => 'Atenti ĉi tiun paĝon',
'movepagebtn'                  => 'Alinomigi paĝon',
'pagemovedsub'                 => 'Sukcesis alinomigo',
'movepage-moved'               => '<big>\'\'\'"$1" estis alinomigita al "$2"\'\'\'</big>',
'movepage-moved-redirect'      => 'Alidirektilo estis kreita.',
'movepage-moved-noredirect'    => 'La kreado de alidirektilo estis nuligita.',
'articleexists'                => 'Paĝo kun tiu nomo jam ekzistas, aŭ la nomo kiun vi elektis ne validas.
Bonvolu elekti alian nomon.',
'cantmove-titleprotected'      => 'Vi ne povas movi paĝo al ĉi loko, ĉar la nova titolo estis protektita kontraŭ kreado',
'talkexists'                   => 'Oni ja sukcesis movi la paĝon mem, sed
ne movis la diskuto-paĝon ĉar jam ekzistas tia ĉe la nova titolo.
Bonvolu permane kunigi ilin.',
'movedto'                      => 'alinomita al',
'movetalk'                     => 'Transigi ankaŭ la "diskuto"-paĝon, se ĝi ekzistas.',
'move-subpages'                => 'Alinomigi ĉiujn subpaĝojn (maksimume $1)',
'move-talk-subpages'           => 'Alinomigi subpaĝojn de diskuto-paĝo (ĝis $1)',
'movepage-page-exists'         => 'La paĝo $1 jam ekzistas kaj ne povas esti aŭtomate anstataŭigita.',
'movepage-page-moved'          => 'La paĝo $1 estis alinomita al $2.',
'movepage-page-unmoved'        => 'La paĝo $1 ne povas esti alinomigita al $2.',
'movepage-max-pages'           => 'La maksimumo de $1 {{PLURAL:$1|paĝo|paĝoj}} estis {{PLURAL:$1|alinomita|alinomitaj}} kaj neniuj pliaj estos alinomitaj aŭtomate.',
'1movedto2'                    => '[[$1]] movita al [[$2]]',
'1movedto2_redir'              => '[[$1]] movita al [[$2]], redirekto lasita',
'move-redirect-suppressed'     => 'alidirektilo subpremita',
'movelogpage'                  => 'Protokolo pri paĝmovoj',
'movelogpagetext'              => 'Jen listo de movitaj paĝoj',
'movesubpage'                  => '{{PLURAL:$1|Subpaĝo|Subpaĝoj}}',
'movesubpagetext'              => 'Ĉi tiu paĝo havas $1 {{PLURAL:$1|subpaĝon montritan|subpaĝojn montritajn}} sube.',
'movenosubpage'                => 'Ĉi tiu paĝo havas neniujn subpaĝojn.',
'movereason'                   => 'Kialo:',
'revertmove'                   => 'restarigi',
'delete_and_move'              => 'Forigi kaj alinomigi',
'delete_and_move_text'         => '==Forigo nepras==

La celartikolo "[[:$1]]" jam ekzistas. Ĉu vi volas forigi ĝin por krei spacon por la movo?',
'delete_and_move_confirm'      => 'Jes, forigu la paĝon',
'delete_and_move_reason'       => 'Forigita por ebligi movon',
'selfmove'                     => 'Font- kaj cel-titoloj samas; ne eblas movi paĝon sur ĝin mem.',
'immobile-source-namespace'    => 'Ne eblas movi paĝojn en nomspaco "$1"',
'immobile-target-namespace'    => 'Ne eblas movi paĝojn en nomspacon "$1"',
'immobile-target-namespace-iw' => 'Intervikia ligilo ne estas valida celo por paĝa movo.',
'immobile-source-page'         => 'Ĉi tiu paĝo ne estas movebla.',
'immobile-target-page'         => 'Ne eblas movi al tiu destina titolo.',
'imagenocrossnamespace'        => 'Ne eblas movi dosieron al nedosiera nomspaco',
'imagetypemismatch'            => 'La nova dosierfinaĵo ne kongruas ĝian dosiertipon.',
'imageinvalidfilename'         => 'La cela dosiernomo estas nevalida',
'fix-double-redirects'         => 'Ĝisdatigi iujn alidirektilojn kiuj direktas al la originala titolo',
'move-leave-redirect'          => 'Forlasi kiel alidirektilon',
'protectedpagemovewarning'     => "'''Averto:''' Ĉi tiu paĝo estis ŝlosita tiel nur uzantoj kun administranto-rajtoj povas movi ĝin.",
'semiprotectedpagemovewarning' => "'''Notu:''' Ĉi tiu paĝo estis ŝlosita tiel ĝi estas nur movebla de registritaj uzantoj.",

# Export
'export'            => 'Eksporti paĝojn',
'exporttext'        => 'Vi povas eksporti la tekston kaj la redaktohistorion de aparta paĝo aŭ de paĝaro kolektita en ia XML.
Ĉi tio povas esti importita en alian programon funkciantan per MediaWiki-softvaro per la [[Special:Import|import-paĝo]].

Eksporti paĝojn, enigu la titolojn en la jena tekst-skatolo, po unu por linio, kaj elektu ĉu vi volas kaj la nunan version kaj ĉiujn antaŭajn versiojn, kun la paĝaj historiaj linioj, a la nunan version kun la informo pri la lasta redakto.

En la lasta kazo, vi ankaŭ povas uzi ligilo, ekz-e [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] por la paĝo "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Entenas nur la aktualan version, ne la malnovajn.',
'exportnohistory'   => "----
'''Notu:''' Eksportado de la plena historio de paĝoj per ĉi paĝo estis malebligita pro funkciigaj kialoj.",
'export-submit'     => 'Eksporti',
'export-addcattext' => 'Aldoni paĝojn el kategorio:',
'export-addcat'     => 'Aldoni',
'export-addnstext'  => 'Aldoni paĝojn de nomspaco:',
'export-addns'      => 'Aldoni',
'export-download'   => 'Konservi kiel dosieron',
'export-templates'  => 'Inkluzivi ŝablonojn',
'export-pagelinks'  => 'Inkluzivi ligitajn paĝoj al profundo de:',

# Namespace 8 related
'allmessages'                   => 'Ĉiuj mesaĝoj',
'allmessagesname'               => 'Nomo',
'allmessagesdefault'            => 'Defaŭlta teksto',
'allmessagescurrent'            => 'Nuna teksto',
'allmessagestext'               => 'Ĉi tio estas listo de ĉiuj mesaĝoj haveblaj en la MediaWiki-nomspaco.
Bonvolu aliri [http://www.mediawiki.org/wiki/Localisation MediaWiki-Asimilado] kaj [http://translatewiki.net translatewiki.net]
se vi volus kontribui al la komuna MediaWiki-asimilado.',
'allmessagesnotsupportedDB'     => '{{ns:special}}:Allmessages ne subtenata ĉar la variablo wgUseDatabaseMessages estas malkonektita.',
'allmessages-filter-legend'     => 'Filtri',
'allmessages-filter'            => 'Filtri laŭ ŝanĝada stato',
'allmessages-filter-unmodified' => 'Neŝanĝitaĵoj',
'allmessages-filter-all'        => 'Ĉio',
'allmessages-filter-modified'   => 'Ŝanĝitaĵoj',
'allmessages-prefix'            => 'Filtri laŭ prefikso:',
'allmessages-language'          => 'Lingvo:',
'allmessages-filter-submit'     => 'Ek!',

# Thumbnails
'thumbnail-more'           => 'Pligrandigi',
'filemissing'              => 'Mankanta dosiero',
'thumbnail_error'          => 'Okazis eraro kreante antaŭvidan bildeton: $1',
'djvu_page_error'          => 'DjVu-a paĝo el intervalo',
'djvu_no_xml'              => 'Ne eblas akiri XML por DjVu dosiero',
'thumbnail_invalid_params' => 'Nevalidaj bildetaj parametroj',
'thumbnail_dest_directory' => 'Ne eblas krei destinan dosierujon',
'thumbnail_image-type'     => 'Tia bildo ne subtenata',
'thumbnail_gd-library'     => 'Nekompleta GD-biblioteka konfiguro: mankas funkcio $1',
'thumbnail_image-missing'  => 'Dosiero verŝajne estas foriga: $1',

# Special:Import
'import'                     => 'Importitaj paĝoj',
'importinterwiki'            => 'Transvikia importo',
'import-interwiki-text'      => 'Elektu vikion kaj paĝan titolon por importi.
Datoj de versioj kaj nomoj de redaktantoj estos preservitaj.
Ĉiuj transvikaj importoj estas raportitaj ĉe la [[Special:Log/import|loglibro de importoj]].',
'import-interwiki-source'    => 'Fonta vikio/paĝo:',
'import-interwiki-history'   => 'Kopiu ĉiujn historiajn versiojn por ĉi tiu pago.',
'import-interwiki-templates' => 'Inkluzivi ĉiujn ŝablonojn',
'import-interwiki-submit'    => 'Importi',
'import-interwiki-namespace' => 'Cela nomspaco:',
'import-upload-filename'     => 'Dosiernomo:',
'import-comment'             => 'Komento:',
'importtext'                 => 'Bonvole eksportu la dosieron el la fonta vikio per la ilo Speciala:Export, konservu ĝin sur via disko kaj poste alŝutu ĝin tien ĉi.',
'importstart'                => 'Importante paĝojn...',
'import-revision-count'      => '$1 {{PLURAL:$1|versio|versioj}}',
'importnopages'              => 'Neniu paĝo por importi.',
'importfailed'               => 'Malsukcesis la importo: $1',
'importunknownsource'        => 'Nekonata fonta speco de alŝuto',
'importcantopen'             => 'Ne eblas malfermi import-dosieron',
'importbadinterwiki'         => 'Malbona intervikia ligilo',
'importnotext'               => 'Malplena aŭ senteksta',
'importsuccess'              => 'La importo sukcesis!',
'importhistoryconflict'      => 'Malkongrua historia versio ekzistas (eble la paĝo importiĝis antaŭe)',
'importnosources'            => 'Neniu transvikia importfonto estis difinita kaj rekta historio de alŝutoj estas malaktivigita.',
'importnofile'               => 'Neniu import-dosiero estis alŝutita.',
'importuploaderrorsize'      => 'Alŝuto de import-dosiero malsukcesis. La dosiero estas pli granda ol la permesita alŝut-pezo.',
'importuploaderrorpartial'   => 'Alŝuto de la import-dosiero malsukcesis. La dosiero estis nur parte alŝutita.',
'importuploaderrortemp'      => 'Alŝutigo de import-dosiero malsukcesis. Labor-dosierujo ne estas trovita.',
'import-parse-failure'       => 'sintaksa fuŝo de XML-importo',
'import-noarticle'           => 'Neniu paĝo por importi!',
'import-nonewrevisions'      => 'Ĉiuj versioj estis antaŭe importitaj.',
'xml-error-string'           => '$1 ĉe linio $2, kolumno $3 (bitiko $4): $5',
'import-upload'              => 'Alŝuti XML-datenojn',
'import-token-mismatch'      => 'Seancaj datenoj perdiĝis. Bonvolu reprovi.',
'import-invalid-interwiki'   => 'Ne povas importi de la specifigita vikio.',

# Import log
'importlogpage'                    => 'Protokolo de importaĵoj',
'importlogpagetext'                => 'Administrantecaj importoj de paĝoj kun redakto-historio de aliaj vikioj.',
'import-logentry-upload'           => 'importita [[$1]] de dosiera alŝuto',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|versio|versioj}}',
'import-logentry-interwiki'        => 'transvikiigita $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versio|versioj}} de $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Via uzantopaĝo',
'tooltip-pt-anonuserpage'         => 'La uzantopaĝo por la IP adreso sub kiu vi estas redaktanta',
'tooltip-pt-mytalk'               => 'Via diskutpaĝo',
'tooltip-pt-anontalk'             => 'Diskuto pri redaktoj sub tiu ĉi IP adreso',
'tooltip-pt-preferences'          => 'Miaj preferoj',
'tooltip-pt-watchlist'            => 'Listo de paĝoj kies ŝanĝojn vi priatentas.',
'tooltip-pt-mycontris'            => 'Listo de viaj kontribuoj',
'tooltip-pt-login'                => 'Vi estas invitita ensaluti, tamen ne estas devige.',
'tooltip-pt-anonlogin'            => 'Vi estas invitita ensaluti, tamen ne estas devige.',
'tooltip-pt-logout'               => 'Elsaluti',
'tooltip-ca-talk'                 => 'Diskuto pri la artikolo',
'tooltip-ca-edit'                 => 'Vi povas redakti tiun ĉi paĝon. Bv uzi la antaŭvidbutonon antaŭ ol konservi.',
'tooltip-ca-addsection'           => 'Starti novan sekcion',
'tooltip-ca-viewsource'           => 'Tiu paĝo estas protektita. Vi povas nur rigardi ties fonton.',
'tooltip-ca-history'              => 'Antaŭaj versioj de tiu ĉi paĝo.',
'tooltip-ca-protect'              => 'Protekti tiun ĉi paĝon',
'tooltip-ca-unprotect'            => 'Malprotekti ĉi tiun paĝon',
'tooltip-ca-delete'               => 'Forigi tiun ĉi paĝon',
'tooltip-ca-undelete'             => 'Restarigu la redaktojn faritajn al tiu ĉi paĝo antaŭ ties forigo',
'tooltip-ca-move'                 => 'Alinomigi tiun ĉi paĝon',
'tooltip-ca-watch'                => 'Aldoni tiun ĉi paĝon al via atentaro',
'tooltip-ca-unwatch'              => 'Forigi tiun ĉi paĝon el via atentaro',
'tooltip-search'                  => 'Traserĉi {{SITENAME}}',
'tooltip-search-go'               => 'Iru al paĝo kun ĉi preciza nomo se ĝi ekzistas',
'tooltip-search-fulltext'         => 'Serĉi la paĝojn por ĉi tiu teksto',
'tooltip-p-logo'                  => 'Ĉefpaĝo',
'tooltip-n-mainpage'              => 'Viziti la Ĉefpaĝon',
'tooltip-n-mainpage-description'  => 'Iri al la ĉefpaĝon',
'tooltip-n-portal'                => 'Pri la projekto, kion vi povas fari, kie vi povas trovi ion',
'tooltip-n-currentevents'         => 'Trovi fonajn informojn pri nunaj eventoj',
'tooltip-n-recentchanges'         => 'Listo de la lastaj ŝanĝoj en la vikio.',
'tooltip-n-randompage'            => 'Iri al hazarda paĝo',
'tooltip-n-help'                  => 'Serĉopaĝo.',
'tooltip-t-whatlinkshere'         => 'Listo de ĉiuj vikiaj paĝoj kij ligas ĉi tien',
'tooltip-t-recentchangeslinked'   => 'Lastaj ŝanĝoj en paĝoj kiuj ligas al tiu ĉi paĝo',
'tooltip-feed-rss'                => 'RSS-fonto por tiu ĉi paĝo',
'tooltip-feed-atom'               => 'Atom-fonto por ĉi tiu paĝo',
'tooltip-t-contributions'         => 'Rigardi la liston de kontribuoj de tiu ĉi uzanto',
'tooltip-t-emailuser'             => 'Sendi retmesaĝon al tiu ĉi uzanto',
'tooltip-t-upload'                => 'Alŝuti bildojn aŭ dosierojn',
'tooltip-t-specialpages'          => 'Listo de ĉiuj specialaj paĝoj',
'tooltip-t-print'                 => 'Printebla versio de ĉi tiu paĝo',
'tooltip-t-permalink'             => 'Konstanta ligilo al ĉi versio de la paĝo',
'tooltip-ca-nstab-main'           => 'Vidi la artikolon',
'tooltip-ca-nstab-user'           => 'Vidi la personan paĝon de la uzanto',
'tooltip-ca-nstab-media'          => 'Vidi la paĝon de la dosiero',
'tooltip-ca-nstab-special'        => 'Estas speciala paĝo, vi ne rajtas redakti ĝin.',
'tooltip-ca-nstab-project'        => 'Rigardi la paĝon de la projekto',
'tooltip-ca-nstab-image'          => 'Rigardi la dosierpaĝon',
'tooltip-ca-nstab-mediawiki'      => 'Rigardi la sisteman mesaĝon',
'tooltip-ca-nstab-template'       => 'Rigardi la ŝablonon',
'tooltip-ca-nstab-help'           => 'Rigardi la helppaĝon',
'tooltip-ca-nstab-category'       => 'Vidu la paĝon de kategorioj',
'tooltip-minoredit'               => 'Marki tiun ŝanĝon kiel etan',
'tooltip-save'                    => 'Konservi viajn ŝanĝojn',
'tooltip-preview'                 => 'Antaŭrigardi viajn ŝanĝojn. Bonvolu uzi tion antaŭ ol konservi ilin!',
'tooltip-diff'                    => 'Montri la ŝanĝojn kiujn vi faris de la teksto.',
'tooltip-compareselectedversions' => 'Rigardi la malsamojn inter ambaŭ selektitaj versioj de ĉi tiu paĝo.',
'tooltip-watch'                   => 'Aldoni ĉi paĝon al via atentaro',
'tooltip-recreate'                => 'Rekrei la paĝon malgraŭ ĝi estis forigita',
'tooltip-upload'                  => 'Ekalŝuti',
'tooltip-rollback'                => '"Restarigi antaŭan" restarigas redakto(j)n al ĉi tiu paĝo de la lasta kontribuanto per unu klako.',
'tooltip-undo'                    => '"Malfari" malfaris ĉi tiun redakton kaj malfermas la redakto-paĝon en antaŭvida reĝimo. Permesas aldoni kialon en la resumo.',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadatumo estas malebligita por ĉi servilo.',
'nocreativecommons' => 'Kreiva Komunejo RDF metadatumo estas malebligita por ĉi servilo.',
'notacceptable'     => 'La viki-servilo ne povas doni datumon en formato kiun via kliento povas legi.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonima uzanto|Anonimaj uzantoj}} de {{SITENAME}}',
'siteuser'         => '{{SITENAME}} uzanto $1',
'anonuser'         => '{{SITENAME}}-anonimulo $1',
'lastmodifiedatby' => 'Ĉi paĝo estis laste ŝanĝita je $2, $1 de $3.',
'othercontribs'    => 'Bazita sur la laboro de $1.',
'others'           => 'aliaj',
'siteusers'        => '{{PLURAL:$2|uzanto|uzantoj}} de {{SITENAME}} $1',
'anonusers'        => '{{SITENAME}}-{{PLURAL:$2|anonimulo|anonimuloj}} $1',
'creditspage'      => 'Atribuoj de paĝo',
'nocredits'        => 'Ne estas informo pri atribuoj por ĉi paĝo.',

# Spam protection
'spamprotectiontitle' => 'Filtrilo kontraŭ spamo',
'spamprotectiontext'  => 'La paĝo kiun vi volis konservi estis blokita per la spam-filtrilo.
Ĉi tia eraro estas verŝajne kaŭzata pro ekstera ligilo al malpermesata (nigralistigita) ekstera retejo.',
'spamprotectionmatch' => 'La jena teksto ekagigis la spam-filtrilon: $1',
'spambot_username'    => 'Trudmesaĝa forigo de MediaWiki',
'spam_reverting'      => 'Restarigo de lasta versio ne entenante ligilojn al $1',
'spam_blanking'       => 'Forviŝo de ĉiuj versioj entenantaj ligilojn al $1',

# Info page
'infosubtitle'   => 'Informoj por paĝo',
'numedits'       => 'Nombro de redaktoj (paĝo): $1',
'numtalkedits'   => 'Nombro de redaktoj (diskuto-paĝo): $1',
'numwatchers'    => 'Nombro de atentantoj: $1',
'numauthors'     => 'Nombro de apartaj aŭtoroj (paĝo): $1',
'numtalkauthors' => 'Nombro de apartaj aŭtoroj (diskuto-paĝo): $1',

# Skin names
'skinname-standard'    => 'Klasika',
'skinname-nostalgia'   => 'Nostalgio',
'skinname-cologneblue' => 'Kolonja Bluo',
'skinname-monobook'    => 'Librejo',
'skinname-chick'       => 'Kokido',

# Math options
'mw_math_png'    => 'Ĉiam krei PNG-bildon',
'mw_math_simple' => 'HTMLigu se simple, aŭ PNG',
'mw_math_html'   => 'HTMLigu se eble, aŭ PNG',
'mw_math_source' => 'Lasu TeX-fonton (por tekstfoliumiloj)',
'mw_math_modern' => 'Rekomendita por modernaj foliumiloj',
'mw_math_mathml' => 'MathML seeble (provizora)',

# Math errors
'math_failure'          => 'malsukcesis analizi formulon',
'math_unknown_error'    => 'nekonata eraro',
'math_unknown_function' => 'nekonata funkcio',
'math_lexing_error'     => 'leksika analizo malsukcesis',
'math_syntax_error'     => 'sintakseraro',
'math_image_error'      => 'konverto al PNG malsukcesis',
'math_bad_tmpdir'       => 'Ne povas skribi al aŭ krei matematikian labor-dosierujon.',
'math_bad_output'       => 'Ne eblas enskribi aŭ krei matematikan eligan dosierujon',
'math_notexvc'          => 'Programo texvc ne ekzistas; bonvolu vidi math/README por konfiguri.',

# Patrolling
'markaspatrolleddiff'                 => 'Marki kiel patrolitan',
'markaspatrolledtext'                 => 'Marki ĉi tiun paĝon kiel patrolitan',
'markedaspatrolled'                   => 'Markita kiel patrolita',
'markedaspatrolledtext'               => 'La elektita versio estas markita kiel patrolita.',
'rcpatroldisabled'                    => 'Patrolado de lastaj ŝanĝoj malaktivigita',
'rcpatroldisabledtext'                => 'La funkcio patrolado de la lastaj ŝanĝoj estas nun malaktivigita.',
'markedaspatrollederror'              => 'Ne povas marki kiel patrolitan',
'markedaspatrollederrortext'          => 'Vi devas specifigi version por marki kiel patrolitan.',
'markedaspatrollederror-noautopatrol' => 'Vi ne rajtas marki viajn proprajn ŝanĝojn kiel patrolitajn.',

# Patrol log
'patrol-log-page'      => 'Protokolo pri patrolado',
'patrol-log-header'    => 'Jen protokolo de patrolitaj versioj.',
'patrol-log-line'      => 'markis $1 el $2 patrolitajn $3',
'patrol-log-auto'      => '(aŭtomata)',
'patrol-log-diff'      => 'kontrolo $1',
'log-show-hide-patrol' => '$1 protokolo pri patrolado',

# Image deletion
'deletedrevision'                 => 'Forigita malnova versio $1',
'filedeleteerror-short'           => 'Eraro dum forigo de dosiero: $1',
'filedeleteerror-long'            => 'Eraroj renkontritaj kiam forigante la dosieron:

$1',
'filedelete-missing'              => 'La dosiero "$1" ne estas forigebla, ĉar ĝi ne ekzistas.',
'filedelete-old-unregistered'     => 'La donita dosier-versio "$1" ne estas en la datumbazo.',
'filedelete-current-unregistered' => 'La entajpita dosiero "$1" ne estas en la datumbazo.',
'filedelete-archive-read-only'    => 'La arkiva dosierujo "$1" ne estas skribebla de la retservilo.',

# Browsing diffs
'previousdiff' => '← Pli malnova redakto',
'nextdiff'     => 'Pli nova redakto →',

# Visual comparison
'visual-comparison' => 'Vida komparo',

# Media information
'mediawarning'         => "'''Warning''': This file may contain malicious code, by executing it your system may be compromised.
<hr />",
'imagemaxsize'         => "Limo por bildoj:<br />''(por dosieraj priskribo-paĝoj)''",
'thumbsize'            => 'Grandeco de bildetoj:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|paĝo|paĝoj}}',
'file-info'            => '(pezo de dosiero: $1, MIME-tipo: $2)',
'file-info-size'       => '($1 × $2 rastrumeroj, dosiera grandeco: $3, MIME-tipo: $4)',
'file-nohires'         => '<small>Nenia pli granda distingivo havebla.</small>',
'svg-long-desc'        => '(SVG-dosiero, $1 × $2 rastrumeroj, grandeco de dosiero: $3)',
'show-big-image'       => 'Plena distingivo',
'show-big-image-thumb' => '<small>Grandeco de ĉi antaŭvido: $1 × $2 rastrumeroj</small>',
'file-info-gif-looped' => 'ripeta GIF',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ĉelo|ĉeloj}}',

# Special:NewFiles
'newimages'             => 'Aro da novaj bildoj',
'imagelisttext'         => "Jen listo de '''$1''' {{PLURAL:$1|dosiero|dosieroj}}, ordigitaj laŭ $2.",
'newimages-summary'     => 'Ĉi tiu speciala paĝo montras la lastajn alŝutitajn dosierojn.',
'newimages-legend'      => 'Dosiernomo',
'newimages-label'       => 'Dosiernomo (aŭ parto de ĝi):',
'showhidebots'          => '($1 robotojn)',
'noimages'              => 'Nenio videbla.',
'ilsubmit'              => 'Serĉi',
'bydate'                => 'laŭ dato',
'sp-newimages-showfrom' => 'Montru novajn dosierojn komencante de $2, $1',

# Bad image list
'bad_image_list' => 'La formato estas jen:

Nur listeroj (kun linio komence de steleto *) estas konsiderata.
La komenca ligilo de linio devas esti ligilo al malbona bildo.
Sekvaj ligilo en la sama linio estas konsiderata kiel esceptoj (paĝoj kiel la bildo rajtas esti montrata.)',

# Metadata
'metadata'          => 'Metadatenoj',
'metadata-help'     => 'Ĉi tiu dosiero enhavas plian informon, verŝajne aldonita de la cifereca fotilo aŭ skanilo uzata krei aux skani ĝin. Se la dosiero estis modifita de ties originala stato, iuj detaloj eble ne estas tute estos sama kiel la modifita bildo.',
'metadata-expand'   => 'Montri etendajn detalojn',
'metadata-collapse' => 'Kaŝi etendajn detalojn',
'metadata-fields'   => 'La jenaj EXIF-metadatumaj kampoj estos inkluzivitaj en bildo-paĝoj kiam la metadatuma tabelo estas disfaldigita. Aliaj estos kaŝita defaŭlte.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Larĝeco',
'exif-imagelength'                 => 'Alteco',
'exif-bitspersample'               => 'Bitokoj po komponanto',
'exif-compression'                 => 'Densiga procedo',
'exif-photometricinterpretation'   => 'Komponaĵo de rastrumeroj',
'exif-orientation'                 => 'Orientiĝo',
'exif-samplesperpixel'             => 'Nombro de komponaĵoj',
'exif-planarconfiguration'         => 'Datuma aranĝo',
'exif-ycbcrsubsampling'            => 'Subdiskretiga proporcio de Y al C',
'exif-ycbcrpositioning'            => 'Y kaj C situado',
'exif-xresolution'                 => 'Horizontala distingivo',
'exif-yresolution'                 => 'Vertikala distingivo',
'exif-resolutionunit'              => 'Unuo de X kaj Y distingivo',
'exif-stripoffsets'                => 'Loko de bilda datumo',
'exif-rowsperstrip'                => 'Nombro de vicoj por strio',
'exif-stripbytecounts'             => 'Bitikoj por densigita strio',
'exif-jpeginterchangeformat'       => 'Flankigo al JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bitokoj de JPEG-datumo',
'exif-transferfunction'            => 'Transiga funkcio',
'exif-whitepoint'                  => 'Koloreco de blanka punkto',
'exif-primarychromaticities'       => 'Kolorecoj de primaraĵoj',
'exif-ycbcrcoefficients'           => 'Koeficientoj de kolorspaca transformiga matrikso',
'exif-referenceblackwhite'         => 'Paro de nigraj kaj blankaj referencaj valutoj',
'exif-datetime'                    => 'Dato kaj tempo de dosiera ŝanĝo',
'exif-imagedescription'            => 'Titolo de bildo',
'exif-make'                        => 'Fabrikejo de fotilo',
'exif-model'                       => 'Speco de fotilo',
'exif-software'                    => 'Programaro uzata',
'exif-artist'                      => 'Kreinto',
'exif-copyright'                   => 'Posedanto de kopirajto',
'exif-exifversion'                 => 'Exif-versio',
'exif-flashpixversion'             => 'Subtena Flashpix-versio',
'exif-colorspace'                  => 'Kolor-spaco',
'exif-componentsconfiguration'     => 'Signifo de ĉiu kompono',
'exif-compressedbitsperpixel'      => 'Reĝimo de bilda densigado',
'exif-pixelydimension'             => 'Valida larĝeco de bildo',
'exif-pixelxdimension'             => 'Valida alteco de bildo',
'exif-makernote'                   => 'Notoj de fabrikejo',
'exif-usercomment'                 => 'Komentoj de uzanto',
'exif-relatedsoundfile'            => 'Rilata son-dosiero',
'exif-datetimeoriginal'            => 'Dato kaj tempo de datuma generado',
'exif-datetimedigitized'           => 'Dato kaj tempo de ciferecigado',
'exif-subsectime'                  => 'DatoTempo subsekundoj',
'exif-subsectimeoriginal'          => 'DatoTempoOriginalaj subsekundoj',
'exif-subsectimedigitized'         => 'DatoTempoCiferecigitaj subsekundoj',
'exif-exposuretime'                => 'Tempo de ekspono',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'F-nombro',
'exif-exposureprogram'             => 'Ekspona programo',
'exif-spectralsensitivity'         => 'Spektruma sensemo',
'exif-isospeedratings'             => 'ISO sentiveco',
'exif-oecf'                        => 'Optikelektronika konverada faktoro',
'exif-shutterspeedvalue'           => 'Rapido de obturatoro',
'exif-aperturevalue'               => 'Aperturo',
'exif-brightnessvalue'             => 'Heleco',
'exif-exposurebiasvalue'           => 'Ekspona emo',
'exif-maxaperturevalue'            => 'Maksimuma pejzaĝa diafragmo',
'exif-subjectdistance'             => 'Distanco de subjekto',
'exif-meteringmode'                => 'Mezurila reĝimo',
'exif-lightsource'                 => 'Fonto de lumo',
'exif-flash'                       => 'Fulmilo',
'exif-focallength'                 => 'Fokusa longo de lenso',
'exif-subjectarea'                 => 'Subjekta areo',
'exif-flashenergy'                 => 'Fulmila energio',
'exif-spatialfrequencyresponse'    => 'Spaca frekvenco-respondo',
'exif-focalplanexresolution'       => 'X distingivo de fokusa ebeno',
'exif-focalplaneyresolution'       => 'Y distingivo de fokusa ebeno',
'exif-focalplaneresolutionunit'    => 'Distingivo-unuo de fokusa ebeno',
'exif-subjectlocation'             => 'Loko de subjekto',
'exif-exposureindex'               => 'Ekspona indekso',
'exif-sensingmethod'               => 'Metodo de sensado',
'exif-filesource'                  => 'Dosiera fonto',
'exif-scenetype'                   => 'Speco de sceno',
'exif-cfapattern'                  => 'CFA skemo',
'exif-customrendered'              => 'Propra foto-rivelado',
'exif-exposuremode'                => 'Ekspona reĝimo',
'exif-whitebalance'                => 'Blanka balanciĝo',
'exif-digitalzoomratio'            => 'Cifereca zumproporcio',
'exif-focallengthin35mmfilm'       => 'fokusa longo en 35-mm filmo',
'exif-scenecapturetype'            => 'Scenkapta speco',
'exif-gaincontrol'                 => 'Scena kontrolo',
'exif-contrast'                    => 'Kontrasto',
'exif-saturation'                  => 'Saturado',
'exif-sharpness'                   => 'Akreco',
'exif-devicesettingdescription'    => 'Resumo pri aparataj reguligiloj',
'exif-subjectdistancerange'        => 'Subjekta distanco',
'exif-imageuniqueid'               => 'Unika identigo de bildo',
'exif-gpsversionid'                => 'versio de GPS etikedo',
'exif-gpslatituderef'              => 'Norda aŭ suda latitudo',
'exif-gpslatitude'                 => 'Latitudo',
'exif-gpslongituderef'             => 'Orienta aŭ uesta longitudo',
'exif-gpslongitude'                => 'Longitudo',
'exif-gpsaltituderef'              => 'Altituda referenco',
'exif-gpsaltitude'                 => 'Alteco',
'exif-gpstimestamp'                => 'GPS tempo (atoma horloĝo)',
'exif-gpssatellites'               => 'Satelitoj uzataj por mezurado',
'exif-gpsstatus'                   => 'Statuso de recevilo',
'exif-gpsmeasuremode'              => 'Mezura reĝimo',
'exif-gpsdop'                      => 'Precizeco de mezuro',
'exif-gpsspeedref'                 => 'Unuo de rapido',
'exif-gpsspeed'                    => 'Rapido de GPS recevilo',
'exif-gpstrackref'                 => 'Referenco por direkto de movado',
'exif-gpstrack'                    => 'Direkto de movado',
'exif-gpsimgdirectionref'          => 'Referenco por direkto de bildo',
'exif-gpsimgdirection'             => 'Direkto de bildo',
'exif-gpsmapdatum'                 => 'Datenoj uzatoj de geodezia esploro',
'exif-gpsdestlatituderef'          => 'Referenco por latitudo de destino',
'exif-gpsdestlatitude'             => 'Latituda destino',
'exif-gpsdestlongituderef'         => 'Referenco por longitudo de destino',
'exif-gpsdestlongitude'            => 'Longitudo de destino',
'exif-gpsdestbearingref'           => 'Referenco por direkto de destino',
'exif-gpsdestbearing'              => 'Direkto aŭ destino',
'exif-gpsdestdistanceref'          => 'Referenco por distanco al destino',
'exif-gpsdestdistance'             => 'Distanco al destino',
'exif-gpsprocessingmethod'         => 'Nomo de GPS procesmetodo',
'exif-gpsareainformation'          => 'Nomo de GPS areo',
'exif-gpsdatestamp'                => 'GPS dato',
'exif-gpsdifferential'             => 'GPS diferenca korektado',

# EXIF attributes
'exif-compression-1' => 'Nedensigita',

'exif-unknowndate' => 'Nekonata dato',

'exif-orientation-1' => 'Normala',
'exif-orientation-2' => 'Spegulumita horizontale',
'exif-orientation-3' => 'Rotaciigita 180°',
'exif-orientation-4' => 'Spegulumita vertikale',
'exif-orientation-5' => 'Turnita 90° maldekstre kaj spegulita vertikale',
'exif-orientation-6' => 'Turnita 90° dekstre',
'exif-orientation-7' => 'Turnita 90° dekstre kaj spegulita vertikale',
'exif-orientation-8' => 'Turnita 90° maldekstre',

'exif-planarconfiguration-1' => 'bloka formato',
'exif-planarconfiguration-2' => 'ebena formato',

'exif-componentsconfiguration-0' => 'ne ekzistas',

'exif-exposureprogram-0' => 'Ne difinita',
'exif-exposureprogram-1' => 'Permana',
'exif-exposureprogram-2' => 'Normala programo',
'exif-exposureprogram-3' => 'Diafragma prioritato <!-- vidu http://www.fw.hu/eventoj/steb/vortaroj/fotografio/fotografio.htm -->',
'exif-exposureprogram-4' => 'Prioritato de obturatoro',
'exif-exposureprogram-5' => 'Kreiva programo (emata al kampa profundo)',
'exif-exposureprogram-6' => 'Agada programo (ema al rapida ekspon-daŭro)',
'exif-exposureprogram-7' => 'Portreta reĝimo (por apudaj fotoj kun la fono malfokusita)',
'exif-exposureprogram-8' => 'Pejzaĝa reĝimo (por pejzaĝaj fotoj kun la fono en fokuso)',

'exif-subjectdistance-value' => '$1 metroj',

'exif-meteringmode-0'   => 'Nekonata',
'exif-meteringmode-1'   => 'Averaĝo',
'exif-meteringmode-2'   => 'CentraPezAveraĝo',
'exif-meteringmode-3'   => 'Elekt-angula eksponometro (Spot)',
'exif-meteringmode-4'   => 'Mult-elekt-angula eksponometro (MultiSpot)',
'exif-meteringmode-5'   => 'Skemo',
'exif-meteringmode-6'   => 'Parta',
'exif-meteringmode-255' => 'Alia',

'exif-lightsource-0'   => 'Nekonata',
'exif-lightsource-1'   => 'Taglumo',
'exif-lightsource-2'   => 'Fluoreska',
'exif-lightsource-3'   => 'Volframa (inkandeska lumo)',
'exif-lightsource-4'   => 'Fulmilo',
'exif-lightsource-9'   => 'Bona vetero',
'exif-lightsource-10'  => 'Nuba vetero',
'exif-lightsource-11'  => 'Ombro',
'exif-lightsource-12'  => 'Tagluma fluoreska (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Tag-blanka fluoreska (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Malvarmblanka fluoreska (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Blanka fluoreska (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Norma lumo A',
'exif-lightsource-18'  => 'Norma lumo B',
'exif-lightsource-19'  => 'Norma lumo C',
'exif-lightsource-24'  => 'ISO artefarita lumo volframa',
'exif-lightsource-255' => 'Alia luma fonto',

# Flash modes
'exif-flash-fired-0'    => 'Fulmilo ne ekbruliĝis',
'exif-flash-fired-1'    => 'Fulmilo ekbriliĝis',
'exif-flash-return-0'   => 'neniu funkcio por detekti liveraĵon de stroboskopo',
'exif-flash-return-2'   => 'revenanta lumo de stroboskopo ne detektiĝis',
'exif-flash-return-3'   => 'revenanta lumo de stroboskopo detektiĝis',
'exif-flash-mode-1'     => 'deviga fulmado',
'exif-flash-mode-2'     => 'deviga dampado de fulmilo',
'exif-flash-mode-3'     => 'aŭtomata reĝimo',
'exif-flash-function-1' => 'Neniu fulmila funkcio',
'exif-flash-redeye-1'   => 'reĝimo por ruĝokula redukcio',

'exif-focalplaneresolutionunit-2' => 'coloj',

'exif-sensingmethod-1' => 'Nedefinita',
'exif-sensingmethod-2' => 'Sensilo de zono de unukromataj koloroj',
'exif-sensingmethod-3' => 'Sensilo de zono de dukromataj koloroj',
'exif-sensingmethod-4' => 'Sensilo de zono de trikromataj koloroj',
'exif-sensingmethod-5' => 'Sensilo de laŭvicaj zonaj koloroj',
'exif-sensingmethod-7' => 'Trilinia sensilo',
'exif-sensingmethod-8' => 'Sensilo de laŭvicaj liniaj koloroj',

'exif-scenetype-1' => 'Rekte fotita bildo',

'exif-customrendered-0' => 'Norma proceso',
'exif-customrendered-1' => 'Propra procezo',

'exif-exposuremode-0' => 'Automata ekspono',
'exif-exposuremode-1' => 'Permana ekspono',
'exif-exposuremode-2' => 'Aŭtomata krampo',

'exif-whitebalance-0' => 'Aŭtomata blank-egaleco',
'exif-whitebalance-1' => 'Permana blank-egaleco',

'exif-scenecapturetype-0' => 'Norma',
'exif-scenecapturetype-1' => 'Pejzaĝo',
'exif-scenecapturetype-2' => 'Portreta',
'exif-scenecapturetype-3' => 'Nokta sceno',

'exif-gaincontrol-0' => 'Neniu',
'exif-gaincontrol-1' => 'Malalta teleobjektivo supren',
'exif-gaincontrol-2' => 'Alta teleobjektivo supren',
'exif-gaincontrol-3' => 'Malalta teleobjektivo malsupren',
'exif-gaincontrol-4' => 'Alta teleobjektivo malsupren',

'exif-contrast-0' => 'Norma',
'exif-contrast-1' => 'Mola',
'exif-contrast-2' => 'Malmola',

'exif-saturation-0' => 'Norma',
'exif-saturation-1' => 'Malalta saturado',
'exif-saturation-2' => 'Alta saturado',

'exif-sharpness-0' => 'Ordinara',
'exif-sharpness-1' => 'Mola',
'exif-sharpness-2' => 'Malmola',

'exif-subjectdistancerange-0' => 'Nekonata',
'exif-subjectdistancerange-1' => 'Makroo',
'exif-subjectdistancerange-2' => 'Apuda perspektivo',
'exif-subjectdistancerange-3' => 'Fora perspektivo',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norda latitudo',
'exif-gpslatitude-s' => 'Suda latitudo',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Orienta longitudo',
'exif-gpslongitude-w' => 'Uesta longitudo',

'exif-gpsstatus-a' => 'Mezurado estanta',
'exif-gpsstatus-v' => 'Mezurada interoperaciado',

'exif-gpsmeasuremode-2' => '2-dimensia mezuro',
'exif-gpsmeasuremode-3' => '3-dimensia mezuro',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometroj por horo',
'exif-gpsspeed-m' => 'Mejloj por horo',
'exif-gpsspeed-n' => 'Knotoj',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Vera direkto',
'exif-gpsdirection-m' => 'Magneta direkto',

# External editor support
'edit-externally'      => 'Ŝanĝi ĉi tiun dosieron per ekstera programaro',
'edit-externally-help' => "(Vidu la [http://www.mediawiki.org/wiki/Manual:External_editors instalinstrukciojn] por pliaj informoj.) ''[angle]''.",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ĉiuj',
'imagelistall'     => 'ĉiuj',
'watchlistall2'    => 'ĉiuj',
'namespacesall'    => 'ĉiuj',
'monthsall'        => 'ĉiuj',
'limitall'         => 'ĉiuj',

# E-mail address confirmation
'confirmemail'             => 'Konfirmi retadreson',
'confirmemail_noemail'     => 'Vi ne havas validan retpoŝtan adreson notitan en viaj [[Special:Preferences|Preferoj]].',
'confirmemail_text'        => 'Ĉi tiu vikio postulas ke vi validigu vian retadreson antaŭ ol uzadi la retmesaĝpreferojn. Bonvolu alklaki la suban butonon por sendi konfirmesaĝon al via adreso. La mesaĝo entenos ligilon kun kodo; bonvolu alŝuti la ligilon en vian foliumilon por konfirmi ke via retadreso validas.',
'confirmemail_pending'     => 'Konfirma kodo estis jam repoŝtis al vi; se vi lastatempe kreis vian konton, vi eble volus atenti kelkajn minutojn por ĝi aliĝi antaŭ vi petus novan kodon.',
'confirmemail_send'        => 'Retmesaĝi konfirmkodon',
'confirmemail_sent'        => 'Konfirma retmesaĝo estas sendita.',
'confirmemail_oncreate'    => 'Konfirma kodo estis sendita al via retpoŝta adreso.
Ĉi kodo ne estas bezonata ensaluti, sed vi bezonos doni ĝin antaŭ uzante iujn ajn retpoŝt-bazitajn ecojn de la vikio.',
'confirmemail_sendfailed'  => '{{SITENAME}} ne eblis sendi vian konfirmretmesaĝon. 
Bonvolu kontroli vian retadreson por nevalidaj signoj.

Retpoŝta programo respondis: $1',
'confirmemail_invalid'     => 'Nevalida konfirmkodo. La kodo eble ne plu validas.',
'confirmemail_needlogin'   => 'Vi devas $1 por konfirmi vian retpoŝtan adreson.',
'confirmemail_success'     => 'Via retadreso estas konfirmita. Vi povas nun ensaluti kaj ĝui la vikion.',
'confirmemail_loggedin'    => 'Via retadreso estas nun konfirmita.',
'confirmemail_error'       => 'Io misokazis dum konservo de via konfirmo.',
'confirmemail_subject'     => 'Konfirmo de retadreso por {{SITENAME}}',
'confirmemail_body'        => 'Iu, verŝajne vi, ĉe la IP-adreso $1, 
enregistrigis konton "$2" ĉe {{SITENAME}} kun ĉi retadreso ĉe {{SITENAME}}.

Konfirmi ke ĉi tiu konto ja apartenas al vi kaj por malŝlosi 
retpoŝtajn kapablojn ĉe {{SITENAME}}, malfermu tiun ĉi ligon en via retumilo:

$3

Se vi ne enregistrigis la konton, sekvu ĉi tiu ligilo por 
nuligi la retpoŝtan konfirmadon.

$5


Ĉi tiu konfirmokodo eksvalidiĝos je $4.',
'confirmemail_invalidated' => 'Konfirmado de retadreso estas nuligita',
'invalidateemail'          => 'Nuligi konfirmadon de retadreso',

# Scary transclusion
'scarytranscludedisabled' => '[Intervikia transinkluzivado estas malebligita.]',
'scarytranscludefailed'   => '[Akiro de ŝablono $1 malsukcesis.]',
'scarytranscludetoolong'  => '[URL-o estas tro longa]',

# Trackbacks
'trackbackbox'      => 'Respuradoj por ĉi tiu paĝo:<br />
$1',
'trackbackremove'   => '([$1 Forigi])',
'trackbacklink'     => 'Postspurado',
'trackbackdeleteok' => 'La postspurado estis sukcese forigita.',

# Delete conflict
'deletedwhileediting' => "'''Averto''': Ĉi tiu paĝo estis forigita post vi ekredaktis!",
'confirmrecreate'     => "Uzanto [[User:$1|$1]] ([[User talk:$1|diskuto]]) forigis ĉi paĝon post vi ekredaktis ĝin kun kialo:
: ''$2''
Bonvolu konfirmi ke vi ja volas rekrei la paĝon.",
'recreate'            => 'Rekrei',

# action=purge
'confirm_purge_button' => 'Ek!',
'confirm-purge-top'    => 'Ĉu forviŝigi la kaŝmemoron de tiu ĉi paĝo?',
'confirm-purge-bottom' => 'Refreŝigante paĝon forviŝas la memorkaŝejon kaj devigas la plej lastan version aperi.',

# Multipage image navigation
'imgmultipageprev' => '← antaŭa paĝo',
'imgmultipagenext' => 'sekva paĝo →',
'imgmultigo'       => 'Ek!',
'imgmultigoto'     => 'Iri al paĝo $1',

# Table pager
'ascending_abbrev'         => 'sprn',
'descending_abbrev'        => 'subn',
'table_pager_next'         => 'Sekva paĝo',
'table_pager_prev'         => 'Antaŭa paĝo',
'table_pager_first'        => 'Unua paĝo',
'table_pager_last'         => 'Lasta paĝo',
'table_pager_limit'        => 'Montri $1 aĵojn por paĝo',
'table_pager_limit_submit' => 'Ek',
'table_pager_empty'        => 'Neniaj rezultoj',

# Auto-summaries
'autosumm-blank'   => 'Forviŝis la paĝon',
'autosumm-replace' => "Anstataŭigante paĝojn kun '$1'",
'autoredircomment' => 'Redirektante al [[$1]]',
'autosumm-new'     => "Nova paĝo kun '$1'",

# Live preview
'livepreview-loading' => 'Ŝarĝante...',
'livepreview-ready'   => 'Ŝarĝante… Prete!',
'livepreview-failed'  => 'Aktiva antaŭvido malsukcesis! Provu normalan antaŭvidon.',
'livepreview-error'   => 'Malsukcesis konekti: $1 "$2". Provu norman antaŭvidon.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ŝanĝoj pli novaj ol $1 {{PLURAL:$1|sekundo|sekundoj}} eble ne estos montrataj en ĉi tiu listo.',
'lag-warn-high'   => 'Pro malrapideco de la servila datumbazo, ŝanĝoj pli novaj ol $1 {{PLURAL:$1|sekundo|sekundoj}} eble ne montriĝos en ĉi tiu listo.',

# Watchlist editor
'watchlistedit-numitems'       => 'Via atentaro enhavas {{PLURAL:$1|1 titolon|$1 titolojn}}, escepte de diskuto-paĝoj.',
'watchlistedit-noitems'        => 'Via atentaro enhavas neniujn titolojn.',
'watchlistedit-normal-title'   => 'Redakti atentaron',
'watchlistedit-normal-legend'  => 'Forigi titolojn de atentaro',
'watchlistedit-normal-explain' => 'Titoloj de via atentaro estas montrata sube.
Forigi titolon, marku la skatoleto apude de ĝi, kaj klaku Forigu Titolojn.
Vi ankaŭ povas [[Special:Watchlist/raw|redakti la krudan liston]].',
'watchlistedit-normal-submit'  => 'Forigi Titolojn',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titolo estis forigita|$1 titoloj estis forigitaj}} de via atentaro:',
'watchlistedit-raw-title'      => 'Redakti krudan atentaron',
'watchlistedit-raw-legend'     => 'Redakti krudan atentaron',
'watchlistedit-raw-explain'    => 'Titoloj en via atentaro estas montrata sube, kaj povas esti redaktita de aldono aŭ forigo de la listo: unu titolo por linio. Kiam finite, klaku Ĝisdatigu Atentaron.
Vi povas ankaŭ [[Special:Watchlist/edit|uzu la norman redaktilon]].',
'watchlistedit-raw-titles'     => 'Titoloj:',
'watchlistedit-raw-submit'     => 'Ĝisdatigi atentaron',
'watchlistedit-raw-done'       => 'Via atentaro estas ĝisdatigita.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titolo estis aldonita|$1 titoloj estis aldonitaj}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titolo estis forigita|$1 titoloj estis forigitaj}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Rigardi koncernajn ŝanĝojn',
'watchlisttools-edit' => 'Rigardi kaj redakti atentaron',
'watchlisttools-raw'  => 'Redakti krudan atentaron',

# Core parser functions
'unknown_extension_tag' => 'Nekonata etend-etikedo "$1"',
'duplicate-defaultsort' => '\'\'\'Averto:\'\'\' Defaŭlta ordiga ŝlosilo "$2" anstataŭigas pli fruan defaŭltan ordigan ŝlosilon "$1".',

# Special:Version
'version'                          => 'Versio',
'version-extensions'               => 'Instalitaj kromprogramoj',
'version-specialpages'             => 'Specialaj paĝoj',
'version-parserhooks'              => 'Sintaksaj hokoj',
'version-variables'                => 'Variabloj',
'version-other'                    => 'Alia',
'version-mediahandlers'            => 'Mediaj traktiloj',
'version-hooks'                    => 'Hokoj',
'version-extension-functions'      => 'Kromprogramaj funkcioj',
'version-parser-extensiontags'     => 'Sintaksaj etend-etikedoj',
'version-parser-function-hooks'    => 'Hokoj de sintaksaj funkcioj',
'version-skin-extension-functions' => 'Etendaj funkcioj pri grafika etoso',
'version-hook-name'                => 'Nomo de hoko',
'version-hook-subscribedby'        => 'Abonita de',
'version-version'                  => '(Versio $1)',
'version-license'                  => 'Permesilo',
'version-software'                 => 'Instalita programaro',
'version-software-product'         => 'Produkto',
'version-software-version'         => 'Versio',

# Special:FilePath
'filepath'         => 'Vojo al dosiero',
'filepath-page'    => 'Dosiero:',
'filepath-submit'  => 'Vojo',
'filepath-summary' => 'Ĉi tiu speciala paĝo redonas la kompletan padon por dosiero. Bildoj estas montrataj en alta distingivo, aliaj dosieraj tipoj estas rekte startataj per ties asociita programo.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Serĉu duplikatajn dosierojn',
'fileduplicatesearch-summary'  => 'Serĉu duplikatajn dosierojn bazite de haketvaluto.

Enigu la dosiernomon sen la "{{ns:file}}:" prefikso.',
'fileduplicatesearch-legend'   => 'Serĉi duplikaton',
'fileduplicatesearch-filename' => 'Dosiernomo:',
'fileduplicatesearch-submit'   => 'Serĉi',
'fileduplicatesearch-info'     => '$1 × $2 rastrumero<br />Dosiera pezo: $3<br />MIME-tipo: $4',
'fileduplicatesearch-result-1' => 'La dosiero "$1" ne havas identan duplikaton.',
'fileduplicatesearch-result-n' => 'La dosiero "$1" havas {{PLURAL:$2|1 identan duplikaton|$2 identajn duplikatojn}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialaj paĝoj',
'specialpages-note'              => '----
* Normaj specialaj paĝoj.
* <strong class="mw-specialpagerestricted">Limigitaj specialaj paĝoj.</strong>',
'specialpages-group-maintenance' => 'Raportoj pri prizorgado',
'specialpages-group-other'       => 'Aliaj specialaj paĝoj',
'specialpages-group-login'       => 'Ensaluti / Krei novan konton',
'specialpages-group-changes'     => 'Lastaj ŝanĝoj kaj protokoloj',
'specialpages-group-media'       => 'Gazetaj raportoj kaj alŝutoj',
'specialpages-group-users'       => 'Uzantoj kaj rajtoj',
'specialpages-group-highuse'     => 'Plej uzitaj paĝoj',
'specialpages-group-pages'       => 'Listoj de paĝoj',
'specialpages-group-pagetools'   => 'Paĝaj iloj',
'specialpages-group-wiki'        => 'Vikidatenoj kaj iloj',
'specialpages-group-redirects'   => 'Alidirektantaj specialaj paĝoj',
'specialpages-group-spam'        => 'Kontraŭspamiloj',

# Special:BlankPage
'blankpage'              => 'Malplena paĝo',
'intentionallyblankpage' => 'Ĉi tiu paĝo intencie estas malplena kaj estas uzata por testado, ktp.',

# External image whitelist
'external_image_whitelist' => ' #Lasu ĉi tiun linion senŝanĝe<pre>
#Enmetu parto de regula esprimo (nur la parton enmetinda en //) suben
#Ĝi estos kongruita kun la URL-o de eksteraj (ligeblaj) bildoj
#Kongruantaĵoj estos montritaj kiel bildoj; se ne eble montri, nur ligilo estos montrita
#Linioj komencantaj kun # estas traktata kiel komentoj.
#Ĉi tiu estas usklecodistinga.

#Enmetu ĉiujn koderojn de regulaj esprimoj super ĉi tiu linio. Lasu la linion senŝanĝe.</pre>',

# Special:Tags
'tags'                    => 'Validaj ŝanĝaj etikedoj',
'tag-filter'              => '[[Special:Tags|Etikeda]] filtrilo:',
'tag-filter-submit'       => 'Filtrilo',
'tags-title'              => 'Etikedoj',
'tags-intro'              => 'Ĉi tiu paĝo montras la etikedojn kun kiuj la programaro markus redakton, kaj iliaj signifoj.',
'tags-tag'                => 'Etikeda nomo',
'tags-display-header'     => 'Aspekto en ŝanĝaj listoj',
'tags-description-header' => 'Plena priskribo pri signifo',
'tags-hitcount-header'    => 'Markitaj ŝanĝoj',
'tags-edit'               => 'redakti',
'tags-hitcount'           => '$1 {{PLURAL:$1|ŝanĝo|ŝanĝoj}}',

# Database error messages
'dberr-header'      => 'Ĉi tiu vikio havas problemon',
'dberr-problems'    => 'Bedaŭrinde, ĉi tiu retejo suferas pro teknikaj problemoj.',
'dberr-again'       => 'Bonvolu atendi kelkajn minutojn kaj reŝarĝi.',
'dberr-info'        => '(Ne eblas kontakti la datenbazan servilon: $1)',
'dberr-usegoogle'   => 'Vi povas serĉi Guglon dume.',
'dberr-outofdate'   => 'Notu ke iliaj indeksoj de nia enhavo eble ne estas ĝisdatigaj.',
'dberr-cachederror' => 'Jen kaŝmemorigita kopio de la petita paĝo, kaj eble ne estas ĝisdatigita.',

# HTML forms
'htmlform-invalid-input'       => 'Estas problemoj kun iom da via enigo',
'htmlform-select-badoption'    => 'La valuto kiun vi specifigis ne estas valida.',
'htmlform-int-invalid'         => 'La valuto kiun vi specifigis ne estas entjero.',
'htmlform-float-invalid'       => 'La valuto specifigita ne estas numero.',
'htmlform-int-toolow'          => 'La valuto kiun vi specifigis estas sub la minimumo de $1',
'htmlform-int-toohigh'         => 'La valuto kiun vi specifigis estas super la maksimumo de $1',
'htmlform-submit'              => 'Ek!',
'htmlform-reset'               => 'Malfari ŝanĝojn',
'htmlform-selectorother-other' => 'Alia',

# Add categories per AJAX
'ajax-add-category'            => 'Aldoni kategorion',
'ajax-add-category-submit'     => 'Aldoni',
'ajax-confirm-title'           => 'Konfirmi agon',
'ajax-confirm-prompt'          => 'Vi povas provizi redaktan resumon suben.
Klaku butonon "Konservi" por konservi vian redakton.',
'ajax-confirm-save'            => 'Konservi',
'ajax-add-category-summary'    => 'Aldoni kategorion "$1"',
'ajax-remove-category-summary' => 'Forigi kategorion "$1"',
'ajax-confirm-actionsummary'   => 'Ago por fari:',
'ajax-error-title'             => 'Eraro',
'ajax-error-dismiss'           => 'Ek!',
'ajax-remove-category-error'   => 'Ne eblas forigi ĉi tiun kategorion.
Ĉi tiel okazas kiam la kategorio estis aldonita al la paĝo per ŝablono.',

);
