<?php
/** Esperanto (Esperanto)
 *
 * @ingroup Language
 * @file
 *
 * @author Tlustulimu
 * @author Michawiki
 * @author Nike
 * @author Amikeco
 * @author Gangleri
 * @author Jon Harald Søby
 * @author לערי ריינהארט
 * @author Siebrand
 * @author SPQRobin
 * @author Yekrats
 * @author ArnoLagrange
 */

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciala',
	NS_MAIN           => '',
	NS_TALK           => 'Diskuto',
	NS_USER           => 'Vikipediisto', # FIXME: Generalize v-isto kaj v-io
	NS_USER_TALK      => 'Vikipediista_diskuto',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_diskuto',
	NS_IMAGE          => 'Dosiero', #FIXME: Check the magic for Image: and Media:
	NS_IMAGE_TALK     => 'Dosiera_diskuto',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_diskuto',
	NS_TEMPLATE       => 'Ŝablono',
	NS_TEMPLATE_TALK  => 'Ŝablona_diskuto',
	NS_HELP           => 'Helpo',
	NS_HELP_TALK      => 'Helpa_diskuto',
	NS_CATEGORY       => 'Kategorio',
	NS_CATEGORY_TALK  => 'Kategoria_diskuto',
);

$skinNames = array(
	'standard' => 'Klasika',
	'nostalgia' => 'Nostalgio',
	'cologneblue' => 'Kolonja Bluo',
	'monobook' => 'Librejo',
	'chick' => 'Kokido',
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
'tog-justify'                 => 'Alkadrigu liniojn',
'tog-hideminor'               => 'Kaŝu malgrandajn redaktetojn ĉe <i>Lastaj ŝanĝoj</i>',
'tog-extendwatchlist'         => 'Etendu la atentaron por montri ĉiujn aplikeblajn ŝanĝon',
'tog-usenewrc'                => 'Novstila Lastaj Ŝanĝoj (bezonas JavaSkripton)',
'tog-numberheadings'          => 'Aŭtomate numeru sekciojn',
'tog-showtoolbar'             => 'Montru eldonilaron',
'tog-editondblclick'          => 'Redaktu per duobla alklako (JavaScript)',
'tog-editsection'             => 'Montru [redaktu]-ligiloj por sekcioj',
'tog-editsectiononrightclick' => 'Redaktu sekciojn per dekstra musklako',
'tog-showtoc'                 => 'Montru liston de enhavoj',
'tog-rememberpassword'        => 'Rememori mian pasvorton',
'tog-editwidth'               => 'Redaktilo estu plenlarĝa',
'tog-watchcreations'          => 'Aldonu de mi kreitajn paĝojn al mia atentaro',
'tog-watchdefault'            => 'Priatentu paĝojn de vi redaktintajn',
'tog-watchmoves'              => 'Aldonu paĝojn, kiujn mi movas, al mia atentaro',
'tog-watchdeletion'           => 'Aldonu paĝojn, kiujn mi forigas, al mia atentaro',
'tog-minordefault'            => 'Marku ĉiujn redaktojn malgrandaj',
'tog-previewontop'            => 'Montru antaŭrigardon antaŭ redaktilo',
'tog-previewonfirst'          => 'Montru antaŭrigardon je unua redakto',
'tog-nocache'                 => 'Malaktivigu kaŝmemorigon de paĝoj',
'tog-enotifwatchlistpages'    => 'Sendu al mi retmesaĝon kiam tiu paĝo estas ŝanĝita',
'tog-enotifusertalkpages'     => 'Sendu al mi retmesaĝon kiam mia diskutpaĝo estas ŝanĝita',
'tog-enotifminoredits'        => 'Sendu al mi ankaŭ retmesaĝojn por malgrandaj redaktoj de paĝoj',
'tog-enotifrevealaddr'        => 'Malkaŝu mian retpoŝtan adreson en informaj retpoŝtaĵoj',
'tog-shownumberswatching'     => 'Montru la nombron da priatentaj uzantoj',
'tog-fancysig'                => 'Simpla subskribo (sen aŭtomata ligo)',
'tog-externaleditor'          => 'Uzu defaŭlte eksteran tekstprilaborilon',
'tog-externaldiff'            => 'Uzu defaŭlte eksteran ŝanĝmontrilon',
'tog-showjumplinks'           => 'Ebligi alirligojn "salti al"
<!-- Bonvolu kontroli ĉu ĝustas la traduko de : Enable "jump to" accessibility links -->',
'tog-uselivepreview'          => 'Uzu tujan antaŭrigardon (ĜavaSkripto) (Eksperimenta)',
'tog-forceeditsummary'        => 'Informu min kiam aldonanta nulan redakto-resumon',
'tog-watchlisthideown'        => 'Kaŝu miajn redaktojn de la atentaro',
'tog-watchlisthidebots'       => 'Kaŝu bot-redaktojn de la atentaro',
'tog-watchlisthideminor'      => 'Kaŝu malgrandajn redaktojn de la atentaro',
'tog-ccmeonemails'            => 'Sendu al mi kopiojn de retpoŝtaĵoj, kiujn mi sendis al aliaj uzuloj.',
'tog-diffonly'                => 'Ne montru paĝan enhavon sub la ŝanĝoj',
'tog-showhiddencats'          => 'Montru kaŝitajn kategoriojn',

'underline-always'  => 'Ĉiam',
'underline-never'   => 'Neniam',
'underline-default' => 'Defaŭlte laŭ foliumilo',

'skinpreview' => '(Antaŭrigardo)',

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
'hidden-category-category'       => 'Kaŝitaj kategorioj', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Ĉi tiu kategorio havas nur la suban subkategorion.|Ĉi tiu kategorio havas la {{PLURAL:$1|suban subkategorion|$1 subajn subkategoriojn}}, el $2 entute.}}',
'category-subcat-count-limited'  => 'Ĉi tiu kategorio havas la {{PLURAL:$1|jenan subkategorion|jenajn $1 subkategoriojn}}.',
'category-article-count'         => '{{PLURAL:$2|Ĉi tiu kategorio enhavas nur la jenan paĝon.|La {{PLURAL:$1|jena paĝo|jenaj $1 paĝoj}} estas en ĉi tiu kategorio, el $2 entute.}}',
'category-article-count-limited' => 'La {{PLURAL:$1|jena paĝo|jenaj $1 paĝoj}} estas en la nuna kategorio.',
'category-file-count'            => '{{PLURAL:$2|Ĉi tiu kategorio nur enhavas la jenan dosieron.|La {{PLURAL:$1|jena doesiero|jenaj $1 dosieroj}} estas en ĉi tiun kategorion, el $2 entute.}}',
'category-file-count-limited'    => 'La {{PLURAL:$1|jena dosiero|jenaj $1 dosieroj}} estas en la nuna kategorio.',
'listingcontinuesabbrev'         => 'daŭrigo',

'mainpagetext'      => 'Vikisoftvaro sukcese instaliĝis.',
'mainpagedocfooter' => "Konsultu la [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] por informo pri uzado de vikia programaro.

==Kiel komenci==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listo de konfiguraĵoj] (angla)
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Oftaj Demandoj] (angla)
* [http://lists.wikipedia.org/mailman/listinfo/mediawiki-announce MediaWiki dissendolisto pri anoncoj] (angla)",

'about'          => 'Enkonduko',
'article'        => 'Artikolo',
'newwindow'      => '(en nova fenestro)',
'cancel'         => 'Nuligi',
'qbfind'         => 'Trovi',
'qbbrowse'       => 'Foliumado',
'qbedit'         => 'Redakti',
'qbpageoptions'  => 'Paĝagado',
'qbpageinfo'     => 'Paĝinformoj',
'qbmyoptions'    => 'Personaĵoj',
'qbspecialpages' => 'Specialaj paĝoj',
'moredotdotdot'  => 'Pli...',
'mypage'         => 'Mia paĝo',
'mytalk'         => 'Mia diskuto',
'anontalk'       => 'Diskutpaĝo por tiu ĉi IP',
'navigation'     => 'Navigado',
'and'            => 'kaj',

# Metadata in edit box
'metadata_help' => 'Metadatumoj:',

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
'print'             => 'Printu',
'edit'              => 'Redakti',
'create'            => 'Krei',
'editthispage'      => 'Redaktu la paĝon',
'create-this-page'  => 'Kreu ĉi tiun paĝon',
'delete'            => 'Forigi',
'deletethispage'    => 'Forigi ĉi tiun paĝon',
'undelete_short'    => 'Malforigu {{PLURAL:$1|redakton|$1 redaktojn}}',
'protect'           => 'Protekti',
'protect_change'    => 'ŝanĝu protekton',
'protectthispage'   => 'Protekti la paĝon',
'unprotect'         => 'Malprotektu',
'unprotectthispage' => 'Malprotektu la paĝon',
'newpage'           => 'Nova paĝo',
'talkpage'          => 'Diskuti la paĝon',
'talkpagelinktext'  => 'Diskuto',
'specialpage'       => 'Speciala Paĝo',
'personaltools'     => 'Personaj iloj',
'postcomment'       => 'Afiŝu komenton',
'articlepage'       => 'Vidu la artikolon',
'talk'              => 'Diskuto',
'views'             => 'Vidoj',
'toolbox'           => 'Iloj',
'userpage'          => 'Vidu personan paĝon',
'projectpage'       => 'Vidu projektopaĝon',
'imagepage'         => 'Vidu dosieropaĝon',
'mediawikipage'     => 'Vidu mesaĝopaĝon',
'templatepage'      => 'Vidu ŝablonopaĝon',
'viewhelppage'      => 'Vidu helpopaĝon',
'categorypage'      => 'Vidu kategorian paĝon',
'viewtalkpage'      => 'Vidu diskutopaĝon',
'otherlanguages'    => 'Aliaj lingvoj',
'redirectedfrom'    => '(Alidirektita el $1)',
'redirectpagesub'   => 'Alidirektilo',
'lastmodifiedat'    => 'Laste redaktita je $2, $1.', # $1 date, $2 time
'viewcount'         => 'Montrita {{PLURAL:$1|unufoje|$1 fojojn}}.',
'protectedpage'     => 'Protektita paĝo',
'jumpto'            => 'Iri al:',
'jumptonavigation'  => 'navigado',
'jumptosearch'      => 'serĉi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Pri {{SITENAME}}',
'aboutpage'            => 'Project:Enkonduko',
'bugreports'           => 'Raporti cimojn',
'bugreportspage'       => 'Project:Raportu cimojn',
'copyright'            => 'La enhavo estas havebla sub $1.',
'copyrightpagename'    => '{{SITENAME}}-kopirajto',
'copyrightpage'        => '{{ns:project}}:Kopirajto',
'currentevents'        => 'Aktualaĵoj',
'currentevents-url'    => 'Project:Aktualaĵoj',
'disclaimers'          => 'Malgarantio',
'disclaimerpage'       => 'Project:Malgarantia paĝo',
'edithelp'             => 'Helpo pri redaktado',
'edithelppage'         => 'Help:Kiel redakti paĝon',
'faq'                  => 'Oftaj demandoj',
'faqpage'              => 'Project:Oftaj demandoj',
'helppage'             => 'Help:Enhavo',
'mainpage'             => 'Ĉefpaĝo',
'mainpage-description' => 'Ĉefpaĝo',
'policy-url'           => 'Project:Konsiletoj',
'portal'               => 'Komunuma portalo',
'portal-url'           => 'Project:Komunuma portalo',
'privacy'              => 'Regularo pri respekto de la privateco',
'privacypage'          => 'Project:Respekto de la privateco',
'sitesupport'          => 'Donaci',
'sitesupport-url'      => 'Project:Subteno',

'badaccess'        => 'Vi ne havas sufiĉe da redaktorajtoj por tiu paĝo.',
'badaccess-group0' => 'Vi ne havas permeson plenumi la agon, kiun vi petis.',
'badaccess-group1' => 'La ago, kiun vi petis, estas limigita al uzuloj en la grupo $1.',
'badaccess-group2' => 'La ago, kiun vi petis, estas limigita al uzuloj en unu el la grupoj $1.',
'badaccess-groups' => 'La ago, kiun vi petis, estas limigita al uzuloj en unu el la grupoj $1.',

'versionrequired'     => 'Versio $1 de MediaWiki nepras',
'versionrequiredtext' => 'La versio $1 de MediaWiki estas necesa por uzi ĉi tiun paĝon. Vidu [[Special:Version|paĝon pri versio]].',

'ok'                      => 'Ek!',
'retrievedfrom'           => 'Elŝutita el  "$1"',
'youhavenewmessages'      => 'Por vi estas $1 ($2).',
'newmessageslink'         => 'nova mesaĝo',
'newmessagesdifflink'     => 'ŝanĝoj kompare kun antaŭlasta versio',
'youhavenewmessagesmulti' => 'Vi havas novajn mesaĝojn ĉe $1',
'editsection'             => '<small>redakti</small>',
'editold'                 => 'redakti',
'viewsourceold'           => 'rigardu fonttekston',
'editsectionhint'         => 'Redaktu sekcion: $1',
'toc'                     => 'Enhavo',
'showtoc'                 => 'montri',
'hidetoc'                 => 'kaŝi',
'thisisdeleted'           => 'Vidu aŭ restarigu $1?',
'viewdeleted'             => 'Rigardu $1?',
'restorelink'             => '{{PLURAL:$1|unu forigitan version|$1 forigitajn versiojn}}',
'feedlinks'               => 'RSS-fonto:',
'feed-invalid'            => 'Ia nevalida fonto.',
'feed-unavailable'        => 'Fontrilataj enfluoj ne estas haveblaj ĉe {{SITENAME}}.',
'site-rss-feed'           => '$1 RSS-fonto.',
'site-atom-feed'          => '$1 Atom-fonto',
'page-rss-feed'           => '"$1" RSS-fonto',
'page-atom-feed'          => '"$1" Atom-fonto',
'red-link-title'          => '$1 (ankoraŭ ne verkita)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Paĝo',
'nstab-user'      => 'Paĝo de uzanto',
'nstab-media'     => 'Media paĝo',
'nstab-special'   => 'Speciala paĝo',
'nstab-project'   => 'Projektpaĝo',
'nstab-image'     => 'Bildo / dosiero',
'nstab-mediawiki' => 'Sistema mesaĝo',
'nstab-template'  => 'Ŝablono',
'nstab-help'      => 'Helpo',
'nstab-category'  => 'Kategorio',

# Main script and global functions
'nosuchaction'      => 'Ne ekzistas tia ago',
'nosuchactiontext'  => "La agon ('action') nomitan de la URL
ne agnoskas la programaro de {{SITENAME}}",
'nosuchspecialpage' => 'Ne ekzistas tia speciala paĝo',
'nospecialpagetext' => 'Vi petis [[Special:Specialpages|specialan paĝon]] kiun ne agnoskas la programaro de {{SITENAME}}.',

# General errors
'error'                => 'Eraro',
'databaseerror'        => 'Datumbaza eraro',
'dberrortext'          => 'Sintakseraro okazis en informpeto al la datumaro.
Jen la plej laste provita informmendo:
<blockquote><tt>$1</tt></blockquote>
el la funkcio "<tt>$2</tt>".
MySQL redonis eraron  "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Okazis sintaksa eraro en la informpeto al la datumaro.
La lasta provita peto estis:
"$1"
el la funkcio "$2".
\'\'MySQL\'\' resendis la erarmesaĝon "$3: $4".',
'noconnect'            => 'Neeblis konekti al la datumbazo; estas ia erarao aŭ oni riparadas la servilon.
<br />
$1',
'nodb'                 => 'Neeblis elekti datumaron $1',
'cachederror'          => 'Intertempe, jen konservita kopio de la petita paĝo (ĝi eble ne estas ĝisdata).',
'laggedslavemode'      => 'Avertu: la pagxo eble ne enhavas lastatempajn gxisdatigojn.',
'readonly'             => 'Datumaro ŝlosita, nurlega',
'enterlockreason'      => 'Bonvolu klarigi, kial oni ŝlosas la datumaron, kaj
la estimatan tempon de malŝlosado.',
'readonlytext'         => 'La datumaro de {{SITENAME}} estas nun ŝlosita kontraŭ
novaj aldonaj kaj aliaj ŝanĝoj, probable pro laŭkutima flegado de la datumaro.
Bonvolu reprovu post iom da tempo.

La ŝlosinto lasis la jenan mesaĝon:
<p>$1</p>',
'missingarticle'       => 'La datumbazo ne trovis la tekston de
artikolo, kiun ĝi devus trovi, nomita "$1".
Ĉi tio ne estas eraro de la datumbazo, sed probable cimo en la programo.
Bonvolu raporti ĉi tion al iu sistemestro, kaj rimarkigi la retadreson (URL).',
'missingarticle-rev'   => '(revizio#: $1)',
'missingarticle-diff'  => '(Diferenco inter versioj: $1, $2)',
'readonly_lag'         => 'La datumbazo estis aŭtomate ŝlosita dum la subdatumbazo atingas la ĉefan datumbazon.',
'internalerror'        => 'Interna eraro',
'internalerror_info'   => 'Interna eraro: $1',
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
'perfdisabled'         => 'Ni petas pardonon! La petita funkcio estas malebligita
provizore por konservi la rapidecon de la servilo.',
'perfcached'           => 'La sekvantaj informoj venas el kaŝmemoro kaj eble ne estas ĝisdataj :',
'perfcachedts'         => 'La jena datumo estas provizora, kaj estis laste gxisdatigita $1.',
'querypage-no-updates' => 'Gxisdatigoj por cxi pagxo estas nune neebligitaj. Datumoj cxi tie ne estos nune refresxigitaj.',
'wrong_wfQuery_params' => 'Malĝustaj parametroj por wfQuery()<br />
Funkcio: $1<br />
Peto: $2',
'viewsource'           => 'Rigardi vikitekston',
'viewsourcefor'        => 'por $1',
'actionthrottled'      => 'Agado limigita',
'actionthrottledtext'  => 'Por kontraŭigi spamon, vi estas limigita farante cxi tiun agon tro pluroble en mallonga tempdaŭro, kaj vi plialtigis ĉi limon. Bonvolu refaru post kelkaj minutoj.',
'protectedpagetext'    => 'Tiu ĉi paĝo estas ŝlosita por malebligi redaktadon.',
'viewsourcetext'       => 'Vi povas rigardi kaj kopii la fonton de la paĝo:',
'protectedinterface'   => 'Ĉi tiu paĝo provizas interfacan tekston por la softvaro, kaj estas ŝlosita por malabeligi misuzon.',
'editinginterface'     => "'''Atentu:''' Vi redaktas paĝon, kiu estas uzata kiel interfaca teksto por la softvaro. Ŝanĝoj de tiu ĉi teksto povas ŝanĝi aspekton de la interfaco por aliaj uzantoj. Por tradukojn, bonvolu uzi [http://translatewiki.net/wiki/Main_Page?setlang=eo Betawiki], la projekto por fari lokajn versiojn de MediaWiki.",
'sqlhidden'            => '(SQL serĉomendo kasxita)',
'cascadeprotected'     => 'Ĉi tiu paĝo estas protektita kontraŭ redaktado, ĉar ĝi estas inkludita en la {{PLURAL:$1|sekvan paĝon, kiu|sekvajn paĝojn, kiuj}} estas {{PLURAL:$1|protektata|protektataj}} kun la "kaskada" opcio turnita sur:
$2',
'namespaceprotected'   => "Vi ne rajtas redakti paĝojn en la '''$1''' nomspaco.",
'customcssjsprotected' => 'Vi ne rajtas redakti ĉi tiun paĝon, ĉar ĝi enhavas personajn alĝustigojn de alia uzanto.',
'ns-specialprotected'  => 'Paĝoj en la {{ns:special}} nomspaco ne povas esti redaktataj.',
'titleprotected'       => "Ĉi titolo estas protektita de kreado de [[User:$1|$1]].
La kialo donata estis ''$2''.",

# Login and logout pages
'logouttitle'                => 'Elsalutu!',
'logouttext'                 => '<strong>Vi elsalutis kaj finis vian seancon.</strong><br />
Vi rajtas daŭre vikiumi sennome, aŭ vi povas reensaluti kiel la sama aŭ kiel alia uzanto.',
'welcomecreation'            => '== Bonvenon, $1! ==

Via konto estas kreita. <span style="color:#ff0000">Ne forgesu fari viajn [[special:Preferences|{{SITENAME}}-preferojn]]!</span>',
'loginpagetitle'             => 'Ensalutu / enskribu',
'yourname'                   => 'Salutnomo:',
'yourpassword'               => 'Via pasvorto',
'yourpasswordagain'          => 'Retajpu pasvorton',
'remembermypassword'         => 'Rememori mian pasvorton',
'yourdomainname'             => 'Via domajno',
'externaldberror'            => 'Aŭ estis datenbaza eraro rilate al ekstera aŭtentikigado, aŭ vi ne permesas ĝisdatigi vian eksteran konton.',
'loginproblem'               => '<b>Okazis problemo dum via ensalutado.</b><br />Bonvolu reprovi!',
'login'                      => 'Ensaluti',
'nav-login-createaccount'    => 'Ensalutu / Kreu novan konton',
'loginprompt'                => 'Necesas ke via foliumilo permesu kuketojn por ensaluti en la {{SITENAME}}.',
'userlogin'                  => 'Ensalutu / Kreu novan konton',
'logout'                     => 'Elsaluti',
'userlogout'                 => 'Elsaluti',
'notloggedin'                => 'Ne ensalutinta',
'nologin'                    => 'Ĉu vi ne jam havas salutnomon? $1.',
'nologinlink'                => 'Krei konton',
'createaccount'              => 'Krei novan konton',
'gotaccount'                 => 'Ĉu vi jam havas konton? $1.',
'gotaccountlink'             => 'Ensaluti',
'createaccountmail'          => 'retpoŝte',
'badretype'                  => 'La pasvortojn kiujn vi tajpis ne egalas.',
'userexists'                 => 'Jam estas uzanto kun la nomo kiun vi elektis. Bonvolu elekti alian nomon.',
'youremail'                  => 'Retadreso:',
'username'                   => 'Salutnomo:',
'uid'                        => 'Uzantnumero:',
'yourrealname'               => 'Reala nomo:',
'yourlanguage'               => 'Lingvo',
'yourvariant'                => 'Varianto',
'yournick'                   => 'Subskribo:',
'badsig'                     => 'Via kaŝnomo (por subskriboj) malvalidas. Bv. kontroli la HTML-etikedojn!',
'badsiglength'               => 'La subskribo estas tro longa.
Ĝi devas esti sub $1 signoj.',
'email'                      => 'Retadreso',
'prefs-help-realname'        => '* Vera nomo (opcia): se vi elektas sciigi ĝin, ĝi estos uzita por aŭtorigi vin pri viaj kontribuoj.',
'loginerror'                 => 'Ensaluta eraro',
'prefs-help-email'           => '* Retpoŝto (opcia) : ebligas al aliaj kontakti vin tra via uzantpaĝo aŭ diskutpaĝo sen neceso malkaŝi vian identecon.',
'prefs-help-email-required'  => 'Ret-adreso estas bezonata.',
'nocookiesnew'               => 'La uzantokonto estis kreita sed vi ne estas ensalutinta. *** E-igo lcfirst {{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj ensalutu per viaj novaj salutnomo kaj pasvorto.',
'nocookieslogin'             => '{{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj provu denove.',
'noname'                     => 'Vi ne tajpis validan salutnomon.',
'loginsuccesstitle'          => 'Ensalutado sukcesis',
'loginsuccess'               => 'Vi ensalutis ĉe {{SITENAME}} kiel uzanto "$1".',
'nosuchuser'                 => 'Neniu uzanto nomiĝas "$1".
Bonvolu kontroli vian literumadon, aŭ uzu la malsupran formularon por krei novan konton.',
'nosuchusershort'            => 'Ne ekzistas uzanto kun la nomo "<nowiki>$1</nowiki>". Bonvolu kontroli vian ortografion.',
'nouserspecified'            => 'Vi devas entajpi uzantonomon.',
'wrongpassword'              => 'Vi tajpis malĝustan pasvorton. Bonvolu provi denove.',
'wrongpasswordempty'         => 'Vi tajpis malplenan pasvorton. Bonvolu provi denove.',
'passwordtooshort'           => 'Via pasvorto estas tro mallonga. Ĝi entenu minimume $1 karaktrojn.',
'mailmypassword'             => 'Retpoŝti al mi novan pasvorton',
'passwordremindertitle'      => 'Rememorigo el {{SITENAME}} pri perdita pasvorto',
'passwordremindertext'       => 'Iu (probable vi, el IP-adreso $1)
petis, ke ni sendu al vi novan pasvorton por ensaluti {{SITENAME}}n ($4).
La pasvorto por uzanto "$2" nun estas "$3".
Ni rekomendas, ke vi nun ensalutu kaj ŝanĝu vian pasvorton.',
'noemail'                    => 'Retpoŝtadreso ne estas registrita por uzanto "$1".',
'passwordsent'               => 'Oni sendis novan pasvorton al la retpoŝtadreso
registrita por "$1".
Bonvolu saluti denove ricevinte ĝin.',
'blocked-mailpassword'       => 'Via IP adreso estas forbarita de redaktado, kaj tial
ne estas permesate uzi la pasvorto-rekovran funkcion por malebligi misuzon.',
'eauthentsent'               => 'Konfirma retmesaĝo estas sendita al la nomita retadreso. Antaŭ ol iu ajn alia mesaĝo estos sendita al la konto, vi devos sekvi la instrukciojn en la mesaĝo por konfirmi ke la konto ja estas la via.',
'throttled-mailpassword'     => 'Pasvorta rememorigilo estis jam sendita, ene de la lasta $1 horoj. Por preventi misuzo, nur unu pasvorto-rememorigilo estos sendita por $1 horoj.',
'mailerror'                  => 'Okazis eraro sendante retpoŝtaĵon: $1',
'acct_creation_throttle_hit' => 'Ni pardonpetas! Vi jam kreis $1 kontojn kaj ne povas krei pluajn.',
'emailauthenticated'         => 'Via retpoŝta adreso estis aŭtentikigita ĉe $1.',
'emailnotauthenticated'      => 'Via retadreso <strong>ne jam estas aŭtentigata.</strong> Tial ne eblas elekti ajnan funkcion sube listigatan.',
'noemailprefs'               => 'Specifi retposxtan adreson por cxi funkcioj funkcii.',
'emailconfirmlink'           => 'Konfirmu vian retpoŝtan adreson',
'invalidemailaddress'        => 'La retpoŝt-adreso ne estas akceptebla ĉar ĝi ŝajne havas nevalidan formaton. Bonvole entajpu ĝust-formatan adreson, aŭ malplenigu la zonon.',
'accountcreated'             => 'Konto kreita',
'accountcreatedtext'         => 'La uzanto-konto por $1 estas kreita.',
'createaccount-title'        => 'Konto-kreado por {{SITENAME}}',
'createaccount-text'         => 'Iu kreis konton por via retadreso en {{SITENAME}} ($4) nomata "$2", kun pasvorto "$3". Vi ensalutu kaj ŝanĝu vian pasvorton nun.

Vi povas ignori ĉi mesaĝon, se ĉi konto estis kreita erare.',
'loginlanguagelabel'         => 'Lingvo: $1',

# Password reset dialog
'resetpass'               => 'Refaru konto-pasvorton',
'resetpass_announce'      => 'Vi ensalutis kun provizora retposxtita pasvorto. Por kompleti ensalutadon, vi devas fari novan pasvorton cxi tien:',
'resetpass_text'          => '<!-- Aldonu tekston ĉi tien -->',
'resetpass_header'        => 'Refaru pasvorton.',
'resetpass_submit'        => 'Faru pasvorton kaj ensalutu',
'resetpass_success'       => 'Via pasvorto estis sukcese sxangxita! Nun ensalutanta vin...',
'resetpass_bad_temporary' => 'Nevalida provizora pasvorto. Vi versxajne jam sukcese sxangxis vian pasvorton aux petis novan provizoran pasvorton.',
'resetpass_forbidden'     => 'Ne eblas sxangxi pasvortojn cxe {{SITENAME}}',
'resetpass_missing'       => 'Mankas formularaj datumoj.',

# Edit page toolbar
'bold_sample'     => 'Grasa teksto',
'bold_tip'        => 'Grasa teksto',
'italic_sample'   => 'Kursiva teksto',
'italic_tip'      => 'Kursiva teksto',
'link_sample'     => 'Ligtitolo',
'link_tip'        => 'Interna ligo',
'extlink_sample'  => 'http://www.ekzemplo.com ligtitolo',
'extlink_tip'     => 'Ekstera ligo (memoru http:// prefikson)',
'headline_sample' => 'Titola teksto',
'headline_tip'    => 'Titololinio je dua nivelo',
'math_sample'     => 'Enmetu formulon ĉi tien',
'math_tip'        => 'Matematika formulo (LaTeX)',
'nowiki_sample'   => 'Enigi ne formatitan tekston ĉi tien',
'nowiki_tip'      => 'Ignoru vikiformatadon',
'image_sample'    => 'Ekzemplo.jpg',
'image_tip'       => 'Enŝutita bildo',
'media_sample'    => 'Ekzemplo.mp3',
'media_tip'       => 'Ligo al dosiero sona ...',
'sig_tip'         => 'Via subskribo kun tempstampo',
'hr_tip'          => 'Horizontala linio (uzu ŝpareme)',

# Edit pages
'summary'                   => 'Resumo',
'subject'                   => 'Temo/subtitolo',
'minoredit'                 => 'Ĉi tiu ŝanĝo estas redakteto',
'watchthis'                 => 'Atenti ĉi tiu paĝon',
'savearticle'               => 'Konservi ŝanĝojn',
'preview'                   => 'Antaŭrigardo',
'showpreview'               => 'Antaŭrigardu',
'showlivepreview'           => 'Aktiva antaŭvido',
'showdiff'                  => 'Montru ŝanĝojn',
'anoneditwarning'           => 'Vi ne estas ensalutinta. Via IP-adreso enregistriĝos en la ŝango-historio de tiu ĉi paĝo.',
'missingsummary'            => "'''Rememorigilo:''' Vi ne provizis redaktan resumon. Se vi alklakos denove la savan butonon, via redaktaĵo estos storata sen resumo.",
'missingcommenttext'        => 'Bonvolu entajpi komenton malsupre.',
'missingcommentheader'      => "'''Atento:''' Vi ne donis temo/subtitolo por cxi tiu komento. Se vi klakos Konservu denove, via redakto estos konservita sen gxi.",
'summary-preview'           => 'Resuma antaŭrigardo',
'subject-preview'           => 'Antauxrigardo de Temo/Subitolo',
'blockedtitle'              => 'La uzanto estas forbarita.',
'blockedtext'               => "<big>'''Via konto aŭ IP-adreso estis forbarita'''</big> fare de $1.
Kialo estas ''$2''.

Vi rajtas kontakti $1 aŭ alian [[{{MediaWiki:Grouppage-sysop}}|administranton]] por pridiskuti la forbaradon.
Vi ne povas uzi la 'retpoŝtan' funkcion, escepte se vi indikis validan retpoŝtan adreson en viaj [[Special:Preferences|kontaj agordoj]] kaj vi ne estas blokita uzi ĝin.
Via IP-adreso estas $3 kaj la ID de la forbarado ests $5. Bonvolu mencii jenajn indikojn en ĉiu plendo:
* IP-adreso: $3
* ID de forbarado: $5
* Komenco de la forbarado: $8
* Malvalidiĝo de la forbarado: $6
* Forbaroto: $7",
'autoblockedtext'           => 'Via IP-adreso estas auxtomate forbarita, cxar uzis gxin alia uzanto, kiun baris $1.
La donita kialo estas:

:\'\'$2\'\'

*Komenco de forbaro: $8
*Limdato de la blokado: $6

Vi povas kontakti $1 aux iun ajn el la aliaj [[{{MediaWiki:grouppage-sysop}}|administrantojn]] por diskuti la blokon.

Notu, ke vi ne povas uzi la servon "Retposxtu cxi tiu uzanton" krom se vi havas validan retpost-adreson registritan en viaj [[Special:Preferences|vikipediistajn preferojn]], kaj vi estas ne blokita kontraux gxia uzado.

Via forbaro-identigo estas $5.  Bonvolu inkluzivi tiun identigon en iuj ajn demandoj de vi farotaj.',
'blockednoreason'           => 'nenia kialo donata',
'blockedoriginalsource'     => "La fonto de '''$1''' estas montrata malsupre:",
'blockededitsource'         => "La teksto de '''viaj redaktoj''' al '''$1''' estas montrata malsupre:",
'whitelistedittitle'        => 'Ensalutado devigata por redakti',
'whitelistedittext'         => 'Vi devas $1 por redakti paĝojn.',
'whitelistreadtitle'        => 'Ensalutado devigata por legi',
'whitelistreadtext'         => 'Vi devas [[Special:Userlogin|ensaluti]] por legi paĝojn.',
'whitelistacctitle'         => 'Vi ne rajtas krei konton',
'whitelistacctext'          => 'Por rajti krei kontojn en {{SITENAME}} vi devas [[Special:Userlogin|ensaluti]] kaj havi la taŭgajn permesojn.',
'confirmedittitle'          => 'Nepras konfirmi per retpoŝto por redakti',
'confirmedittext'           => 'Vi devas konfirmi vian retpoŝtan adreson antaŭ ol redakti paĝojn. Bonvolu agordi kaj validigi vian retadreson per viaj [[Special:Preferences|preferoj]].',
'nosuchsectiontitle'        => 'Ne tia sekcio',
'nosuchsectiontext'         => 'Vi provis redakti sekcion, kiu ne ekzistas. Ĉar ne estas sekcio $1, ne  estas loko por savi vian redakton.',
'loginreqtitle'             => 'Nepre ensalutu',
'loginreqlink'              => 'ensaluti',
'loginreqpagetext'          => 'Vi devas $1 por rigardi aliajn paĝojn.',
'accmailtitle'              => 'Pasvorto sendita.',
'accmailtext'               => "La pasvorto por '$1' estis sendita al  $2.",
'newarticle'                => '(Nova)',
'newarticletext'            => 'Vi sekvis ligilon al paĝo jam ne ekzistanta. Se vi volas krei ĝin, ektajpu sube (vidu la [[{{MediaWiki:Helppage}}|helpopaĝo]] por klarigoj.) Se vi malintence alvenis ĉi tien, simple alklaku la retrobutonon de via retumilo.',
'anontalkpagetext'          => "---- ''Jen diskutopaĝo por iu anonima kontribuanto kiu ne jam kreis konton aŭ ne uzas ĝin. Ni tial devas uzi la cifran IP-adreso por identigi lin. la sama IP-adreso povas estis samtempte uzata de pluraj uzantoj. Se vi estas anonimulo kaj preferus eviti tiajn mistrafajn komentojn kaj konfuziĝon kun aliaj anonimuloj de via retejo, bonvolu [[Special:Userlogin|krei konton aŭ ensaluti]].''",
'noarticletext'             => 'La paĝo nune estas malplena. Vi povas [[Special:Search/{{PAGENAME}}|serĉi ĉi tiun paĝtitolon]] en aliaj paĝoj aŭ [{{fullurl:{{FULLPAGENAME}}|action=edit}} redakti ĉi tiun paĝon].',
'userpage-userdoesnotexist' => 'Uzanto-konto "$1" ne estas registrita. Bonvolu konfirmi se vi volas krei/redakti cxi tiu pagxo.',
'clearyourcache'            => "'''Notu:''' Post konservado vi forviŝu la kaŝmemoron de via foliumilo por vidi la ŝanĝojn : '''Mozilo:''' alklaku ''Reŝarĝi'' (aŭ ''Stir-Shift-R''), '''IE / Opera:''' ''Stir-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Stir-R''.",
'usercssjsyoucanpreview'    => '<strong>Konsileto:</strong> Uzu la "Antaŭrigardan" butonon por provi vian novan css/js antaŭ konservi.',
'usercsspreview'            => '<strong>Memoru ke vi nur antaŭrigardas vian uzanto-CSS. Ĝi ne jam estas konservita!</strong>',
'userjspreview'             => "'''Memoru ke vi nun nur provas kaj antaŭrigardas vian uzantan javaskripton, ĝi ne estas jam konservita'''",
'userinvalidcssjstitle'     => "'''Averto:''' Ne ekzistas aspekto \"\$1\". Rememoru ke individuaj .css-aj kaj .js-aj paĝoj uzas minusklan titolon, ekz. {{ns:user}}:Foo/monobook.css kontraŭe  al {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Ŝanĝo registrita)',
'note'                      => '<strong>Noto:</strong>',
'previewnote'               => '<strong>Memoru, ke ĉi tio estas nur antaŭrigardo kaj ankoraŭ ne konservita!</strong>',
'previewconflict'           => 'La jena antaŭrigardo montras la tekston el la supra tekstujo,
kiel ĝi aperos se vi elektos konservi la paĝon.',
'session_fail_preview'      => '<strong>Bedaŭrinde ne eblis trakti vian redakton pro manko de sesiaj datenoj. Bonvolu provi refoje. Se ankoraŭ ne efikas post tio, elsalutu kaj poste re-ensalutu.</strong>',
'session_fail_preview_html' => "<strong>Pardonu! Ne eblas procesi vian redakton pro manko de seanca datumo.</strong>

''Cxar {{SITENAME}} ebligas krudan HTML, cxi tiu antauxrigardo estas kasxita kiel prevento kontraux Javascript-atakoj.

<strong>Se cxi tiu estas tauxga provo por redakti, bonvolu reprovi. Se ankoraux ne funkcias, provu elsaluti kaj reensaluti.</strong>",
'token_suffix_mismatch'     => '<strong>Via redakto estis malpermesita cxar via klienta fusxis la interpunkcio en la redakto-signo.
La redakto estis malpermesita por preventi koruptado de la teksto de la pagxo.
Cxi tiel malofte okazas kiam vi uzas fusxan TTT-an anoniman prokurilon.</strong>',
'editing'                   => 'Redaktante $1',
'editingsection'            => 'Redaktante $1 (sekcion)',
'editingcomment'            => 'Redaktante $1 (komenton)',
'editconflict'              => 'Redakta konflikto: $1',
'explainconflict'           => 'Iu alia ŝanĝis la paĝon post kiam vi ekredaktis.
La supra tekstujo enhavas la aktualan tekston de la artikolo.
Viaj ŝanĝoj estas en la malsupra tekstujo.
Vi devas mem kunfandi viajn ŝanĝojn kaj la jaman tekston.
<b>Nur</b> la teksto en la supra tekstujo estos konservita kiam
vi alklakos "Konservu".<br />',
'yourtext'                  => 'Via teksto',
'storedversion'             => 'Registrita versio',
'nonunicodebrowser'         => '<strong>ATENTU: Via foliumilo ne eltenas unikodon, bonvolu ŝanĝi ĝin antaŭ ol redakti artikolon.</strong>',
'editingold'                => '<strong>AVERTO: Vi nun redaktas malnovan version de tiu ĉi artikolo.
Se vi konservos vian redakton, ĉiuj ŝanĝoj faritaj post tiu versio perdiĝos.</strong>',
'yourdiff'                  => 'Malsamoj',
'copyrightwarning'          => 'Bonvolu noti, ke ĉiu kontribuaĵo al la {{SITENAME}} estu rigardata kiel eldonita laŭ $2 (vidu je $1). Se vi volas, ke via verkaĵo ne estu redaktota senkompate kaj disvastigota laŭvole, ne alklaku "Konservu".<br />
Vi ankaŭ ĵuras, ke vi mem verkis la tekston, aŭ ke vi kopiis ĝin el fonto senkopirajta.
<strong>NE UZU KOPIRAJTAJN VERKOJN SENPERMESE!</strong>',
'copyrightwarning2'         => 'Bonvolu noti ke ĉiuj kontribuoj al {{SITENAME}} povas esti reredaktita, ŝanĝita aŭ forigita de aliaj kontribuantoj. Se vi ne deziras ke viaj verkoj estu senkompate reredaktitaj, ne publikigu ilin ĉi tie.<br />
Vi ankaŭ promesu al ni ke vi verkis tion mem aŭ kopiis el publika domajno aŭ simila libera fonto (vidu $1 por detaloj).
<strong>NE PROPONU KOPIRAJTITAJN VERKOJN SEN PERMESO!</strong>',
'longpagewarning'           => '<strong>AVERTO: Tiu ĉi paĝo longas $1 kilobitokojn; kelkaj retumiloj
povas fuŝi redaktante paĝojn je longo proksime aŭ preter 32kb.
Se eble, bonvolu disigi la paĝon al malpli grandajn paĝerojn.</strong>',
'longpageerror'             => '<strong>Eraro: La teksto, kiun vi prezentis, longas $1 kilobajtojn, kio estas pli longa ol la maksimumo de $2 kilobajtoj. Ĝi ne povas esti storata.</strong>',
'readonlywarning'           => '<strong>AVERTO: La datumbazo estas ŝlosita por teknika laboro;
pro tio neeblas nun konservi vian redaktadon. Vi povas elkopii kaj englui
la tekston al tekstdosiero por poste reenmeti ĝin al la vikio.</strong>',
'protectedpagewarning'      => '<strong>AVERTO: Tiu ĉi paĝo estas ŝlosita kontraŭ redaktado krom de administrantoj (t.e., vi). Bv certiĝi, ke vi sekvas la normojn de la komunumo per via redaktado.</strong>',
'semiprotectedpagewarning'  => '<strong>Notu:</strong> Ĉi paĝo estas protektita tiel ke nur [[Special:Userlogin|ensalutintaj]] uzantoj povas redakti ĝin.',
'cascadeprotectedwarning'   => "'''Averto:''' Ĉi tiu paĝo estas ŝlosita tiel ke nur uzantoj kun administrantaj privilegioj povas redakti ĝin, ĉar ĝi estas inkludita en la {{PLURAL:$1|sekvan kaskade protektitan paĝon|sekvajn kaskade protektitajn paĝojn}}:",
'titleprotectedwarning'     => '<strong>AVERTO: Ĉi paĝo estis ŝlosita tial nur iuj uzantoj povas krei ĝin.</strong>',
'templatesused'             => 'Ŝablonoj uzitaj sur ĉi paĝo:',
'templatesusedpreview'      => 'Ŝablonoj uzataj dum ĉi tiu antaŭrigardo:',
'templatesusedsection'      => 'Ŝablonoj uzataj en ĉi tiu sekcio:',
'template-protected'        => '(protektita)',
'template-semiprotected'    => '(duone protektita)',
'hiddencategories'          => 'Ĉi paĝo estas membro de {{PLURAL:$1|1 kaŝita kategorio|$1 kaŝitaj kategorioj}}:',
'edittools'                 => '<!-- Teksto ĉi tie estas montrata sub redaktaj kaj alŝutaj formularoj. -->',
'nocreatetitle'             => 'Paĝa kreado estas limigita',
'nocreatetext'              => '{{SITENAME}} restriktas la eblecon krei novajn paĝojn. Vi eblas reiri kaj redakti faritan paĝon, aŭ [[Special:Userlogin|ensaluti aŭ krei konton]].',
'nocreate-loggedin'         => 'Vi ne rajtas krei novajn paĝojn en {{SITENAME}}.',
'permissionserrors'         => 'Eraroj pri permesoj',
'permissionserrorstext'     => 'Vi ne rajtas fari tion pro la {{PLURAL:$1|sekva kialo|sekvaj kialoj}}:',
'recreate-deleted-warn'     => "'''Averto: Vi rekreas paĝon tiu estis antaŭe forigita.'''

Vi konsideru ĉu konvenas daŭre redakti ĉi paĝon.
Jen la protokolo de forigoj por via oportuno:",

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Averto: Ĉi tiu paĝo enhavas tro da multekostaj sintaksaj funkcio-vokoj.

Ĝi havu malpli ol $2, sed nun estas $1.',
'expensive-parserfunction-category'       => 'Paĝoj kun tro da multekostaj sintaksaj funkcio-vokoj',
'post-expand-template-inclusion-warning'  => 'Averto: Inkluziva pezo de ŝablonoj estas tro granda.
Iuj ŝablonoj ne estos inkluzivitaj.',
'post-expand-template-inclusion-category' => 'Paĝoj kie inkluziva pezo de ŝablonoj estas tro granda.',
'post-expand-template-argument-warning'   => 'Averto: Ĉit tiu paĝo enhavas almenaŭ unu ŝablonan argumenton kiu havas tro grandan etendan pezon.
Ĉi tiuj argumentoj estis forlasitaj.',
'post-expand-template-argument-category'  => 'Paĝoj enhavantaj forlasitajn argumentojn de ŝablonoj',

# "Undo" feature
'undo-success' => 'La redakto estas malfarebla. Bonvolu konfirmi la jenan komparajxon por verigi cxi tiel vi volas, kaj konservu la sxangxojn suben fini malfarante la redakton.',
'undo-failure' => 'Ne eblas nuligi redakton pro konfliktaj intermezaj redaktoj.',
'undo-norev'   => 'La redakto ne eblis esti malfarita ĉar ĝi aŭ ne ekzistas aŭ estis forigita.',
'undo-summary' => 'Nuligis revizion $1 de [[Special:Contributions/$2|$2]] ([[User_talk:$2|diskuto]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ne povas krei konton',
'cantcreateaccount-text' => "Konto-kreado de cxi tiu IP-adreso ('''$1''') estis forbarita de [[User:$3|$3]].

La kialo donata de $3 estas ''$2''.",

# History pages
'viewpagelogs'        => 'Rigardi la protokolojn por tiu ĉi paĝo',
'nohistory'           => 'Ne ekzistas historio de redaktoj por ĉi tiu paĝo.',
'revnotfound'         => 'Ne ekzistas malnova versio de la artikolo',
'revnotfoundtext'     => 'Ne eblis trovi malnovan version de la artikolo kiun vi petis.
Bonvolu kontroli la retadreson (URL) kiun vi uzis por atingi la paĝon.\\b',
'currentrev'          => 'Aktuala versio',
'revisionasof'        => 'Kiel registrite je $1',
'revision-info'       => 'Redakto de $1 de $2',
'previousrevision'    => '← Antaŭa versio',
'nextrevision'        => 'Sekva versio →',
'currentrevisionlink' => 'Rigardi nunan version',
'cur'                 => 'nuna',
'next'                => 'sekv',
'last'                => 'antaŭa',
'page_first'          => 'unua',
'page_last'           => 'lasta',
'histlegend'          => 'Klarigo: (nuna) = vidu malsamojn kompare kun la nuna versio, (antaŭa) = malsamojn kompare kun la antaŭa versio, <strong>E</strong> = malgranda redakteto',
'deletedrev'          => '[forigita]',
'histfirst'           => 'plej frua',
'histlast'            => 'plej lasta',
'historysize'         => '({{PLURAL:$1|1 bajto|$1 bajtoj}})',
'historyempty'        => '(malplena)',

# Revision feed
'history-feed-title'          => 'Historio de redaktoj',
'history-feed-description'    => 'Revizia historio por ĉi tiu paĝo en la vikio',
'history-feed-item-nocomment' => '$1 ĉe $2', # user at time
'history-feed-empty'          => 'La petata pagxo ne ekzistas.
Gxi versxajne estis forigita de la vikio, aux alinomita.
Provu [[Special:Search|sercxi en la vikio]] por rilataj novaj pagxoj.',

# Revision deletion
'rev-deleted-comment'         => '(komento nuligita)',
'rev-deleted-user'            => '(uzanto-nomo forigita)',
'rev-deleted-event'           => '(loglibrero forigita)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Cxi versio de la pagxo estis forigita de publikaj arhxivoj.
Povas esti detaloj en la [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logolibro de forigoj].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Cxi versio de la pagxo estis forigita de publikaj arhxivoj.
Kiel administranto de {{SITENAME}} vi povas rigardi gxin;
povas esti detaloj en la [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logolibro de forigoj].
</div>',
'rev-delundel'                => 'montru/kaŝu',
'revisiondelete'              => 'Forigu/Malforigu reviziojn',
'revdelete-nooldid-title'     => 'Nevalida cela revizio',
'revdelete-nooldid-text'      => 'Vi ne specifis celan revizion aux reviziojn fari ĉi tiun 
funkcion, la specifigita revizio ne ekzistas, aŭ vi estas provanta kaŝi la nunan revizion.',
'revdelete-selected'          => "{{PLURAL:$2|Selektata revizio|Selektataj revizioj}} de '''[[:$1]]''':",
'logdelete-selected'          => '{{PLURAL:$1|Selektata loglibra evento|Selektataj loglibraj eventoj}}:',
'revdelete-text'              => 'Forigitaj versioj kaj eventoj plu aperos en la historipagxoj, sed iliaj tekstoj ne estos alireblaj de  la publiko.

Aliaj administrantoj cxe {{SITENAME}} plu povos aliri la kasxitan entenon kaj restarigi gxin per la sama interfaco, krom se plia limigo estas metita de la pagxaradministrantoj.',
'revdelete-legend'            => 'Faru videblecajn limigojn',
'revdelete-hide-text'         => 'Kaŝu tekston de versio',
'revdelete-hide-name'         => 'Kaŝu agon kaj celon',
'revdelete-hide-comment'      => 'Kaŝu komenton de redakto',
'revdelete-hide-user'         => 'Kaŝu nomon aux IP-adreson de redaktinto',
'revdelete-hide-restricted'   => 'Apliku ĉi tiujn limigojn al administrantoj kaj ŝlosu ĉi tiun interfacon',
'revdelete-suppress'          => 'Subpremu datumojn de administrantoj aldone al de aliaj',
'revdelete-hide-image'        => 'Kaŝu dosier-enhavon',
'revdelete-unsuppress'        => 'Forigu limigojn al restarigitaj versioj',
'revdelete-log'               => 'Loglibra komento:',
'revdelete-submit'            => 'Apliku al la selektita versio',
'revdelete-logentry'          => 'ŝanĝis videblecon de versio por [[$1]]',
'logdelete-logentry'          => 'ŝanĝis eventan videblecon de [[$1]]',
'revdelete-success'           => "'''Revizia videbleco estas sukcese farita.'''",
'logdelete-success'           => "'''Videbleco de evento sukcese farita.'''",
'revdel-restore'              => 'Ŝanĝu videblecon',
'pagehist'                    => 'Paĝa historio',
'deletedhist'                 => 'Forigita historio',
'revdelete-content'           => 'enhavo',
'revdelete-summary'           => 'redaktu resumon',
'revdelete-uname'             => 'salutnomo',
'revdelete-restricted'        => 'aplikis limojn al administrantoj',
'revdelete-unrestricted'      => 'forigis limojn por administrantoj',
'revdelete-hid'               => 'kaŝis $1',
'revdelete-unhid'             => 'malkaŝis $1',
'revdelete-log-message'       => '$1 por $2 {{PLURAL:$2|revizio|revizioj}}',
'logdelete-log-message'       => '$1 por $2 {{PLURAL:$2|evento|eventoj}}',

# Suppression log
'suppressionlog'     => 'Loglibro pri subigado',
'suppressionlogtext' => 'Jen listo de forigoj kaj forbaroj pri enhavo kaŝita per administrantoj. 
Rigardu la [[Special:Ipblocklist|IP-forbarliston]] por la listo de nune operaciaj forbaroj kaj forigoj.',

# History merging
'mergehistory'                     => 'Kunigu historiojn de paĝoj',
'mergehistory-header'              => 'Ĉi paĝo permesas al vi kunigi reviziojn de la historio de unu fonta paĝo en pli novan paĝon.
Certigu ke ĉi ŝanĝo tenos kontinueco de la historia paĝo.',
'mergehistory-box'                 => 'Kunigu reviziojn de du paĝoj:',
'mergehistory-from'                => 'Fontpaĝo:',
'mergehistory-into'                => 'Celpaĝo:',
'mergehistory-list'                => 'Kunigebla redakthistorio',
'mergehistory-merge'               => 'La jenaj revizioj de [[:$1]] povas esti kunigitaj en [[:$2]]. Uzu la radio-butonan kolumnon por enkunigi nur la reviziojn kreitajn cxe kaj antaux la specifigita tempo. Notu ke uzado de navigado-ligiloj restarigos ĉi kolumnon.',
'mergehistory-go'                  => 'Montru kunigeblajn redaktojn',
'mergehistory-submit'              => 'Kunigu reviziojn',
'mergehistory-empty'               => 'Neniuj reviziojn eblas kunigi.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revizio|revizioj}} de [[:$1]] sukcese kunigita en [[:$2]].',
'mergehistory-fail'                => 'Ne eblas fari la historian kunigon; bonvolu konstati la paĝon kaj tempajn parametrojn.',
'mergehistory-no-source'           => 'Fontpaĝo $1 ne ekzistas.',
'mergehistory-no-destination'      => 'Celpaĝo $1 ne ekzistas.',
'mergehistory-invalid-source'      => 'Fontpaĝo devas esti valida titolo.',
'mergehistory-invalid-destination' => 'Celpaĝo devas esti valida titolo.',
'mergehistory-autocomment'         => 'Kunigita [[:$1]] en [[:$2]]',
'mergehistory-comment'             => 'Kunigita [[:$1]] en [[:$2]]: $3',

# Merge log
'mergelog'           => 'Loglibro de kunigoj',
'pagemerge-logentry' => 'kunigis [[$1]] en [[$2]] (revizioj gxis $3)',
'revertmerge'        => 'Malkunigu',
'mergelogpagetext'   => 'Jen listo de la plej lastatempaj kunigoj de unu paĝhistorio en alian.',

# Diffs
'history-title'           => 'Redakto-historio de "$1"',
'difference'              => '(Malsamoj inter versioj)',
'lineno'                  => 'Linio $1:',
'compareselectedversions' => 'Kompari la selektitajn versiojn',
'editundo'                => 'malfari',
'diff-multi'              => '({{PLURAL:$1|Unu meza versio|$1 mezaj versioj}} ne montrata.)',

# Search results
'searchresults'             => 'Serĉrezultoj',
'searchresulttext'          => 'Por pliaj informoj kiel priserĉi la {{SITENAME}}n, vidu [[{{MediaWiki:Helppage}}|serĉi en {{SITENAME}}]].',
'searchsubtitle'            => 'Serĉmendo "[[$1]]"',
'searchsubtitleinvalid'     => 'Serĉmendo "$1"',
'noexactmatch'              => '<b>Ne estas paĝo titolita "$1".</b> Vi povas [[:$1|krei la paĝon]].',
'noexactmatch-nocreate'     => "'''Estas neniu paĝo titolita \"\$1\".'''",
'toomanymatches'            => 'Tro da serĉo-trafoj estis trovitaj; bonvolu provi malsaman serĉomendon.',
'titlematches'              => 'Trovitaj laŭ titolo',
'notitlematches'            => 'Neniu trovita laŭ titolo',
'textmatches'               => 'Trovitaj laŭ enhavo',
'notextmatches'             => 'Neniu trovita laŭ enhavo',
'prevn'                     => '$1 antaŭajn',
'nextn'                     => '$1 sekvajn',
'viewprevnext'              => 'Montri ($1) ($2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 vorto|$2 vortoj}})',
'search-result-score'       => 'Trafeco: $1%',
'search-redirect'           => '(alidirektilo $1)',
'search-section'            => '(sekcio $1)',
'search-suggest'            => 'Ĉu vi intenciis: $1',
'search-interwiki-caption'  => 'Kunprojektoj',
'search-interwiki-default'  => '$1 rezultoj:',
'search-interwiki-more'     => '(plu)',
'search-mwsuggest-enabled'  => 'kun sugestoj',
'search-mwsuggest-disabled' => 'sen sugestoj',
'search-relatedarticle'     => 'Relataj',
'mwsuggest-disable'         => 'Malebligu AJAX-sugestojn',
'searchrelated'             => 'rilataj',
'searchall'                 => 'ĉiuj',
'showingresults'            => "Montras {{PLURAL:$1|'''1''' trovitan|'''$1''' trovitajn}} ekde la #'''$2'''-a.",
'showingresultsnum'         => "Montras {{PLURAL:$3|'''1''' trovitan|'''$3''' trovitajn}} ekde la #'''$2'''-a.",
'showingresultstotal'       => "Montrante suben rezultojn '''$1 - $2''' el '''$3'''",
'nonefound'                 => '<strong>Noto</strong>: malsukcesaj serĉoj ofte
okazas ĉar oni serĉas tro da ofte uzataj vortoj, kiujn ne enhavas la indekso,
aŭ ĉar oni petas tro da serĉvortoj (nur paĝoj kiuj enhavas ĉiun serĉvorton
montriĝos en la rezulto).',
'powersearch'               => 'Progresa trovilo',
'powersearch-legend'        => 'Progresa serĉo',
'powersearch-ns'            => 'Serĉi en nomspacoj:',
'powersearch-redir'         => 'Listigi alidirektilojn',
'powersearch-field'         => 'Serĉi',
'search-external'           => 'Ekstera serĉo',
'searchdisabled'            => '<p>Oni provizore malŝaltis serĉadon per la plenteksta
indekso pro troŝarĝita servilo. Intertempe, vi povas serĉi per <i>guglo</i> aŭ per <i>jahu!</i>:</p>',

# Preferences page
'preferences'              => 'Preferoj',
'mypreferences'            => 'Miaj preferoj',
'prefs-edits'              => 'Nombro de redaktoj:',
'prefsnologin'             => 'Ne jam salutis!',
'prefsnologintext'         => '[[Special:Userlogin|Ensalutu]] kaj vi povos ŝanĝi viajn preferojn.',
'prefsreset'               => 'Preferoj reprenitaj el la registro.',
'qbsettings'               => 'Preferoj pri ilaro',
'qbsettings-none'          => 'Nenia',
'qbsettings-fixedleft'     => 'Fiksiĝas maldekstre',
'qbsettings-fixedright'    => 'Fiksiĝas dekstre',
'qbsettings-floatingleft'  => 'Ŝvebas maldekstre',
'qbsettings-floatingright' => 'Ŝvebas dekstre',
'changepassword'           => 'Ŝanĝu pasvorton',
'skin'                     => 'Aspekto',
'math'                     => 'Matematikaĵoj',
'dateformat'               => 'Datformato',
'datedefault'              => 'Nenia prefero',
'datetime'                 => 'Dato kaj horo',
'math_failure'             => 'malsukcesis analizi formulon',
'math_unknown_error'       => 'nekonata eraro',
'math_unknown_function'    => 'nekonata funkcio',
'math_lexing_error'        => 'leksika analizo malsukcesis',
'math_syntax_error'        => 'sintakseraro',
'math_image_error'         => 'konverto al PNG malsukcesis',
'math_bad_tmpdir'          => 'Ne povas skribi al aŭ krei matematikian labor-dosierujon.',
'math_bad_output'          => 'Ne eblas enskribi aŭ krei matematikan eligan dosierujon',
'math_notexvc'             => 'Programo texvc ne ekzistas; bonvolu vidi math/README por konfiguri.',
'prefs-personal'           => 'Uzantodatumoj',
'prefs-rc'                 => 'Lastaj ŝanĝoj',
'prefs-watchlist'          => 'Atentaro',
'prefs-watchlist-days'     => 'Nombro de tagoj montri en la atentaro:',
'prefs-watchlist-edits'    => 'Maksimuma nombro de ŝanĝoj montrendaj en ekspandita atentaro:',
'prefs-misc'               => 'Miksitaĵoj',
'saveprefs'                => 'Konservi preferojn',
'resetprefs'               => 'Forviŝu nekonservitajn ŝanĝojn',
'oldpassword'              => 'Malnova pasvorto',
'newpassword'              => 'Nova pasvorto',
'retypenew'                => 'Retajpi novan pasvorton',
'textboxsize'              => 'Grandeco de redakta tekstujo',
'rows'                     => 'Linioj:',
'columns'                  => 'Kolumnoj:',
'searchresultshead'        => 'Serĉi',
'resultsperpage'           => 'Montru trovitajn po',
'contextlines'             => 'Montru liniojn el paĝoj po:',
'contextchars'             => 'Montru literojn el linioj ĝis po:',
'stub-threshold'           => 'Ago-sojlo por formatigo de <a href="#" class="stub">ligil-ĝermo (anglalingve: "stub link")</a> (bitikoj):',
'recentchangesdays'        => 'Tagoj montrendaj en lastaj ŝanĝoj:',
'recentchangescount'       => "Montru kiom da titoloj en 'Lastaj ŝanĝoj'",
'savedprefs'               => 'Viaj preferoj estas konservitaj.',
'timezonelegend'           => 'Horzono',
'timezonetext'             => 'Indiku je kiom da horoj via
loka horzono malsamas disde tiu de la servilo (UTC).
Ekzemple, por la Centra Eŭropa Horzono, indiku "1" vintre aŭ "2" dum somertempo.',
'localtime'                => 'Loka horzono',
'timezoneoffset'           => 'Malsamo',
'servertime'               => 'Horo de la servilo (UTC):',
'guesstimezone'            => 'Plenigita el la foliumilo',
'allowemail'               => 'Permesu retmesaĝojn de aliaj uzantoj',
'defaultns'                => 'Traserĉu la jenajn nomspacojn:',
'default'                  => 'defaŭlte',
'files'                    => 'Dosieroj',

# User rights
'userrights'                       => 'Administrado de uzantorajtoj', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => 'Administru uzantogrupojn',
'userrights-user-editname'         => 'Entajpu uzantonomon:',
'editusergroup'                    => 'Redaktu Uzantgrupojn',
'editinguser'                      => "Redaktante uzanto-rajtojn de uzanto '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Redaktu uzantogrupojn.',
'saveusergroups'                   => 'Konservu uzantogrupojn',
'userrights-groupsmember'          => 'Membro de:',
'userrights-groupsremovable'       => 'Forigeblaj grupoj:',
'userrights-groupsavailable'       => 'Disponeblaj grupoj:',
'userrights-groups-help'           => 'Vi povas modifi la grupojn kiun ĉi uzanto enestas.
* Markita markbutono signifas ke la uzanto estas en tiu grupo.
* Nemarkita markbutono signifas ke la uzanto ne estas in tiu grupo.
* Steleto (*) signifas ke vi ne povas forigi la grupon post vi aldonis ĝin, aŭ male.',
'userrights-reason'                => 'Kialo por ŝanĝo:',
'userrights-available-none'        => 'Vi ne eblas ŝanĝi membrecon de grupoj.',
'userrights-available-add'         => 'Vi povas aldoni iujn ajn uzantojn al {{PLURAL:$2|ĉi tiu grupo|ĉi tiuj grupoj}}: $1.',
'userrights-available-remove'      => 'Vi povas forigi iujn ajn uzantojn de {{PLURAL:$2|ĉi tiu grupo|ĉi tiuj grupoj}}: $1',
'userrights-available-add-self'    => 'Vi povas aldoni vin al {{PLURAL:$2|jena grupo|jenaj grupoj}}: $1',
'userrights-available-remove-self' => 'Vi povas forigi vin al {{PLURAL:$2|jena grupo|jenaj grupoj}}: $1.',
'userrights-no-interwiki'          => 'Vi ne havas permeson redakti uzanto-rajtojn en aliaj vikioj.',
'userrights-nodatabase'            => 'Datumbazo $1 ne ekzistas aŭ ne estas loka.',
'userrights-nologin'               => 'Vi nepre [[Special:Userlogin|ensalutu]] kun administranto-konto doni uzanto-rajtojn.',
'userrights-notallowed'            => 'Via konto ne havas permeson doni uzanto-rajtojn.',
'userrights-changeable-col'        => 'Grupoj kiujn vi povas ŝanĝi',
'userrights-unchangeable-col'      => 'Grupoj kiujn vi ne povas ŝanĝi',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Uzuloj',
'group-autoconfirmed' => 'Aŭtomate konfirmitaj uzantoj',
'group-bot'           => 'Robotoj',
'group-sysop'         => 'Sisopoj',
'group-bureaucrat'    => 'Burokratoj',
'group-suppress'      => 'Superrigardoj',
'group-all'           => '(ĉiuj)',

'group-user-member'          => 'Uzulo',
'group-autoconfirmed-member' => 'Aŭtomate konfirmita uzanto',
'group-bot-member'           => 'Roboto',
'group-sysop-member'         => 'Sisopo',
'group-bureaucrat-member'    => 'Burokrato',
'group-suppress-member'      => 'Superrigardo',

'grouppage-user'          => '{{ns:project}}:Uzuloj',
'grouppage-autoconfirmed' => '{{ns:project}}:Aŭtomate konfirmitaj uzantoj',
'grouppage-bot'           => '{{ns:project}}:Robotoj',
'grouppage-sysop'         => '{{ns:project}}:Administrantoj',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokratoj',
'grouppage-suppress'      => '{{ns:project}}:Superrigardo',

# Rights
'right-read'                 => 'Legu paĝojn',
'right-edit'                 => 'Redaktu paĝojn',
'right-createpage'           => 'Kreu paĝojn (kiuj ne estas diskuto-paĝoj)',
'right-createtalk'           => 'Kreu diskuto-paĝojn',
'right-createaccount'        => 'Kreu novajn uzanto-kontojn',
'right-minoredit'            => 'Marku redaktojn kiel etajn',
'right-move'                 => 'Movu paĝojn',
'right-suppressredirect'     => 'Ne kreu alidirektilon de la malnova nomo kiam movante paĝon',
'right-upload'               => 'Alŝutu dosierojn',
'right-reupload'             => 'Anstataŭigu ekzistantan dosieron',
'right-reupload-own'         => 'Anstataŭigu ekzistantan dosieron alŝutitan de la sama uzanto',
'right-reupload-shared'      => 'Anstataŭigu dosierojn en la komuna bildprovizejo loke',
'right-upload_by_url'        => 'Alŝutu dosieron de URL-adreso',
'right-purge'                => 'Refreŝigu la retejan kaŝmemoron por paĝo sen konfirma paĝo',
'right-autoconfirmed'        => 'Redaktu duone protektitajn paĝojn',
'right-bot'                  => 'Traktiĝu kiel aŭtomata procezo',
'right-nominornewtalk'       => 'Ne kaŭzi etajn redaktojn en diskuto-paĝoj sproni la inviton por novaj mesaĝoj',
'right-apihighlimits'        => 'Utiligu altajn limojn por API informmendoj',
'right-delete'               => 'Forigu paĝojn',
'right-bigdelete'            => 'Forigu paĝojn kun grandaj historioj',
'right-deleterevision'       => 'Forigu kaj restarigu specifajn reviziojn de paĝoj',
'right-deletedhistory'       => 'Rigardu listerojn de forigitaj historioj, sen ties asociaj tekstoj',
'right-browsearchive'        => 'Serĉu forigitajn paĝojn',
'right-undelete'             => 'Restarigu paĝon',
'right-hiderevision'         => 'Kontrolu kaj restarigu reviziojn kaŝitajn de administrantoj',
'right-suppress'             => 'Rigardu privatajn loglibrojn',
'right-block'                => 'Forbaru aliajn uzantoj por redaktado',
'right-blockemail'           => 'Forbaru uzanton de retpoŝta sendado',
'right-hideuser'             => 'Forbaru salutnomon, kaŝante ĝin de la publiko',
'right-ipblock-exempt'       => 'Preterpasu IP-forbarojn, aŭtomatajn forbarojn, kaj ĝeneralajn forbarojn',
'right-proxyunbannable'      => 'Preterpasu aŭtomatajn forbarojn de prokuriloj',
'right-protect'              => 'Ŝanĝu protektniveloj kaj redaktu protektitajn paĝojn',
'right-editprotected'        => 'Redaktu protektitajn paĝojn (sen kaskada protestado)',
'right-editinterface'        => 'Redaktu la uzanto-interfacon',
'right-editusercssjs'        => 'Redaktu CSS- kaj JS-dosierojn de aliaj uzantoj',
'right-rollback'             => 'Tuj restarigu la lastan uzanton kiu redaktis specifan paĝon',
'right-markbotedits'         => 'Marku restarigitajn redaktojn kiel robotajn redaktojn',
'right-import'               => 'Importu paĝojn de aliaj vikioj',
'right-importupload'         => 'Importu paĝojn de dosiera alŝuto',
'right-patrol'               => 'Marku redaktojn kiel patrolitajn',
'right-autopatrol'           => 'Faru redaktojn aŭtomate markitajn kiel patrolitajn',
'right-patrolmarks'          => 'Rigardi patrolmarkojn de lastaj ŝanĝoj',
'right-unwatchedpages'       => 'Vidu la liston de nepriatentitaj paĝoj',
'right-trackback'            => 'Enigu retrovojon',
'right-mergehistory'         => 'Kunfandigu la historiojn de paĝoj',
'right-userrights'           => 'Redaktu ĉiujn uzanto-rajtojn',
'right-userrights-interwiki' => 'Redaktu la rajtojn de uzantoj en aliaj vikioj',
'right-siteadmin'            => 'Ŝlosu kaj malŝlosu la datumbazo',

# User rights log
'rightslog'      => 'Protokolo de uzanto-rajtoj',
'rightslogtext'  => 'Ĉi tio estas loglibro pri la ŝanĝoj de uzantorajtoj.',
'rightslogentry' => 'ŝanĝis grupan membrecon por $1 de $2 al $3',
'rightsnone'     => '(nenia)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ŝanĝo|ŝanĝoj}}',
'recentchanges'                     => 'Lastaj ŝanĝoj',
'recentchangestext'                 => 'Sekvu la plej lastajn ŝanĝojn en la {{SITENAME}} per ĉi tiu paĝo.',
'recentchanges-feed-description'    => 'Sekvi la plej lastatempajn ŝanĝojn al la vikio en ĉi tiu fonto.',
'rcnote'                            => "Jen la {{PLURAL:$1|lasta '''1''' ŝanĝo|lastaj '''$1''' ŝanĝoj}} dum la {{PLURAL:$2|lasta tago|lastaj '''$2''' tagoj}}, ekde $3.",
'rcnotefrom'                        => "Jen la ŝanĝoj ekde '''$2''' (lastaj ĝis '''$1''').",
'rclistfrom'                        => 'Montru novajn ŝanĝojn ekde "$1"',
'rcshowhideminor'                   => '$1 redaktetojn',
'rcshowhidebots'                    => '$1 robotojn',
'rcshowhideliu'                     => '$1 ensalutantojn',
'rcshowhideanons'                   => '$1 anonimajn redaktojn',
'rcshowhidepatr'                    => '$1 patrolitajn redaktojn',
'rcshowhidemine'                    => '$1 miajn redaktojn',
'rclinks'                           => 'Montru $1 lastajn ŝanĝojn; montru la ŝanĝojn dum la $2 lastaj tagoj.<br />$3',
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

# Recent changes linked
'recentchangeslinked'          => 'Rilataj paĝoj',
'recentchangeslinked-title'    => 'Ŝanĝoj rilataj al "$1"',
'recentchangeslinked-noresult' => 'Neniuj ŝanĝoj en ligitaj paĝoj dum la donata periodo.',
'recentchangeslinked-summary'  => "Jen listo de ŝanĝoj faritaj lastatempe al paĝoj ligitaj el specifa paĝo (aŭ al membroj de specifa kategorio).
Paĝoj en [[Special:Watchlist|via atentaro]] estas '''grasaj'''.",
'recentchangeslinked-page'     => 'Nomo de paĝo:',
'recentchangeslinked-to'       => 'Montru ŝanĝojn al paĝoj ligitaj al la specifa paĝo anstataŭe.',

# Upload
'upload'                      => 'Alŝutu dosieron',
'uploadbtn'                   => 'Alŝuti dosieron',
'reupload'                    => 'Realŝutu',
'reuploaddesc'                => 'Revenu al la alŝuta formularo.',
'uploadnologin'               => 'Ne ensalutinta',
'uploadnologintext'           => 'Se vi volas alŝuti dosierojn, vi devas [[Special:Userlogin|ensaluti]].',
'upload_directory_read_only'  => 'La TTT-servilo ne povas alskribi la alŝuto-dosierujon ($1).',
'uploaderror'                 => 'Eraro okazis dum alŝuto',
'uploadtext'                  => "Uzu la jenan formon por alŝuti dosierojn.
Okulumi aux serĉi antaŭe alŝutitajn bildojn, iru la [[Special:Imagelist|Listo de alŝutitaj dosieroj]]. Alŝutaĵoj ankaŭ estas registrita en la [[Special:Log/upload|Loglibro de alŝutoj]].

Por inkluzivi la dosieron en pagxon, skribu ligilon laŭ la formoj

* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Bildo.jpg]]</nowiki>''' aŭ
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Bildo.png|teksto por retumiloj negrafikaj]]</nowiki>''' aŭ
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dosiero.ogg]]</nowiki>''' por ligi rekte al la dosiero.",
'upload-permitted'            => 'Permesitaj dosiertipoj: $1.',
'upload-preferred'            => 'Preferitaj dosiertipoj: $1.',
'upload-prohibited'           => 'Malpermesitaj dosiero-tipoj: $1.',
'uploadlog'                   => 'loglibro de alŝutaĵoj',
'uploadlogpage'               => 'Protokolo de alŝutoj',
'uploadlogpagetext'           => 'Jen la plej laste alŝutitaj dosieroj.
Ĉiuj tempoj montriĝas laŭ la horzono UTC.',
'filename'                    => 'Dosiernomo',
'filedesc'                    => 'Resumo',
'fileuploadsummary'           => 'Resumo:',
'filestatus'                  => 'Kopirajta statuso:',
'filesource'                  => 'Fonto:',
'uploadedfiles'               => 'Alŝutitaj dosieroj',
'ignorewarning'               => 'Ignoru averton kaj konservu dosieron ĉiukaze',
'ignorewarnings'              => 'Ignoru ĉiajn avertojn',
'minlength1'                  => 'Nomoj de dosieroj nepre havas almenaŭ unu literon.',
'illegalfilename'             => 'La dosiernomo $1 entenas karaktrojn kiuj ne estas permesitaj en paĝaj titoloj. Bonvolu renomi la dosieron kaj provu denove alŝuti ĝin.',
'badfilename'                 => 'Dosiernomo estis ŝanĝita al "$1".',
'filetype-badmime'            => 'Dosieroj de la MIME-tipo "$1" ne estas permesitaj por alŝutado.',
'filetype-unwanted-type'      => "'''\".\$1\"''' estas nevolata dosiero-tipo. Prefereataj dosiero-tipoj estas \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' ne estas permesita dosiero-tipo. Permesitaj dosiero-tipoj estas \$2.",
'filetype-missing'            => 'Ĉi dosiero ne inkluzivas finaĵon de dosiernomo (kiel ".jpg").',
'large-file'                  => 'Oni rekomendas, ke dosieroj ne superu grandon de $1 bitokoj; tiu ĉi enhavas $2 bitokojn.',
'largefileserver'             => 'Ĉi tiu dosiero estas pli granda ol permesas la servilaj preferoj.',
'emptyfile'                   => 'La dosiero kiun vi alŝutis ŝajnas malplena. Tio povas esti kaŭzita sde tajperaro en la titolo. Bonvolu kontroli ĉu vi vere volas alŝuti tiun dosieron.',
'fileexists'                  => 'Dosiero kun tia ĉi nomo jam ekzistas. Bonvolu kontroli <strong><tt>$1</tt></strong> krom se vi certas ke vi konscie volas ŝanĝi ĝuste tiun.',
'filepageexists'              => 'La priskriba paĝo por ĉi dosiero jam estis kreita ĉe <strong><tt>$1</tt></strong>, sed neniu dosiero kun ĉi nomo nune ekzistas. La resumo kiun vi entajpos ne aperos en la priskribo-paĝo. Por aperigi vian resumon, vi devos permane redakti ĝin.',
'fileexists-extension'        => 'Dosiero kun simila nomo ekzistas:<br />
Nomo de la alŝuta dosiero: <strong><tt>$1</tt></strong><br />
Nomo de la ekzistanta dosiero: <strong><tt>$2</tt></strong><br />
Bonvolu elekti malsaman nomon.',
'fileexists-thumb'            => "<center>'''Ekzistanta bildo'''</center>",
'fileexists-thumbnail-yes'    => 'Ĉi dosiero ŝajnas kiel bildo de malkreskigita grandeco <i>(bildeto)</i>. Bonvolu kontroli la dosiero <strong><tt>$1</tt></strong>.<br /> Se la kontrolita dosiero estas la sama bildo kiel la originala grandeco, ĝi ne nepras alŝuti plian bideton.',
'file-thumbnail-no'           => 'La dosiernomo komencas kun <strong><tt>$1</tt></strong>. Ĝi ŝajnas kiel bildo de malgrandigita grandeco <i>(thumbnail)</i>.
Se vi havas ĉi bildon en plena distingivo, alŝutu ĉi tiun, alikaze bonvolu ŝanĝi la dosieran nomon.',
'fileexists-forbidden'        => 'Dosiero kun tia ĉi nomo jam ekzistas; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dosiero kun tia ĉi nomo jam ekzistas en la komuna dosiero-deponejo; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Alŝuto sukcesis!',
'uploadwarning'               => 'Averto',
'savefile'                    => 'Konservu dosieron',
'uploadedimage'               => 'alŝutis "[[$1]]"',
'overwroteimage'              => 'alŝutis novan version de "[[$1]]"',
'uploaddisabled'              => 'Ni petas pardonon, sed oni malebligis alŝutadon.',
'uploaddisabledtext'          => 'Alŝutado de dosieroj estas malfunkciigita je tiu ĉi vikio.',
'uploadscripted'              => 'HTML-aĵo aŭ skriptokodaĵo troviĝas en tiu ĉi dosiero, kiun TTT-foliumilo eble interpretus erare.',
'uploadcorrupt'               => 'La dosiero estas difektita aŭ havas malĝustan finaĵon. Bonvolu kontroli la dosieron kaj refoje alŝuti ĝin.',
'uploadvirus'                 => 'Viruso troviĝas en la dosiero! Detaloj: $1',
'sourcefilename'              => 'Fonta dosiernomo:',
'destfilename'                => 'Celdosiernomo:',
'upload-maxfilesize'          => 'Maksimuma dosier-pezo: $1',
'watchthisupload'             => 'Atenti ĉi tiu paĝon',
'filewasdeleted'              => 'Dosiero de ĉi nomo estis antaŭe alŝutita kaj poste redaktita. Vi kontrolu la $1 antaux alŝutante ĝin denove.',
'upload-wasdeleted'           => "'''Averto: Vi alŝutas dosieron kiu estis antaŭe forigita.'''

Vi konsideru ĉu taŭgas alŝuti ĉi dosiero.
jen la loglibro pri forigado por ĉi dosiero por via oportuneco:",
'filename-bad-prefix'         => 'La nomo de la dosiero kiun vi alŝutas komencas kun <strong>"$1"</strong>, kiu estas nepriskriba nomo ofte aŭtomate donata de ciferecaj fotiloj. Bonvolu elekti pli priskriban nomon por via bildo.',

'upload-proto-error'      => 'Nevalida protokolo',
'upload-proto-error-text' => 'Fora alŝuto devas URL-on komence de <code>http://</code> aŭ <code>ftp://</code>.',
'upload-file-error'       => 'Interna eraro',
'upload-file-error-text'  => 'Interna eraro okazis provante krei labordosieron ĉe la servilo. Bonvolu kontakti sistem-administranton.',
'upload-misc-error'       => 'Nekonata eraro pri alŝutado.',
'upload-misc-error-text'  => 'Nekonata eraro okazis dum la alŝuto. Bonvolu kontroli ke la URL-o estas valida kaj atingebla tiam reprovu. Se la problemo kontinuas, kontaku sisteman administranton.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-o ne estis atingebla',
'upload-curl-error6-text'  => 'La donata URL-o ne estis atingita. Bonvolu rekontroli ke la URL-o estas korekta kaj la retejo funkcias.',
'upload-curl-error28'      => 'Tempolimo de alŝuto atingita',
'upload-curl-error28-text' => 'La retejo atendas tro sen respondo. Bonvolu verigi ke la retejo ankoraŭ funkcias kaj reprovi. Vi eble volus trovi dum malpli okupa tempo.',

'license'            => 'Permesilo:',
'nolicense'          => 'Neniu elektita',
'license-nopreview'  => '(Antaŭvido ne montrebla)',
'upload_source_url'  => ' (valida, publike atingebla URL-o)',
'upload_source_file' => ' (dosiero en via komputilo)',

# Special:Imagelist
'imagelist-summary'     => 'Ĉi tiu speciala paĝo montras ĉiujn alŝutitajn dosierojn.
Defaŭlte, la lasta alŝutitaj dosieroj estas montrataj supren.
Klaku la kolumnan titolon por ŝanĝi la direkton de ordigo.',
'imagelist_search_for'  => 'Serĉu por dosiera nomo:',
'imgfile'               => 'dosiero',
'imagelist'             => 'Listo de alŝutitaj dosieroj',
'imagelist_date'        => 'Dato',
'imagelist_name'        => 'Nomo',
'imagelist_user'        => 'Uzanto',
'imagelist_size'        => 'Grandeco',
'imagelist_description' => 'Priskribo',

# Image description page
'filehist'                       => 'Historio de dosiero',
'filehist-help'                  => 'Klaku daton/tempon por rigardi la dosieron kiel ĝin ŝajnitan tiame.',
'filehist-deleteall'             => 'forigu ĉiujn',
'filehist-deleteone'             => 'forigi',
'filehist-revert'                => 'restarigi',
'filehist-current'               => 'nuna',
'filehist-datetime'              => 'Dato/Tempo',
'filehist-user'                  => 'Uzanto',
'filehist-dimensions'            => 'Dimensioj',
'filehist-filesize'              => 'Grandeco de dosiero',
'filehist-comment'               => 'Komento',
'imagelinks'                     => 'Ligiloj al la dosiero',
'linkstoimage'                   => 'La jenaj paĝoj ligas al ĉi tiu dosiero:',
'nolinkstoimage'                 => 'Neniu paĝo ligas al ĉi tiu dosiero.',
'morelinkstoimage'               => 'Rigardi [[Special:Whatlinkshere/$1|pliajn ligilojn]] al ĉi tiu dosiero.',
'redirectstofile'                => 'Jen dosieroj ligantaj al ĉi tiu dosiero:',
'duplicatesoffile'               => 'La jenaj dosieroj estas duplikatoj de ĉi tiu dosiero:',
'sharedupload'                   => 'Cxi tiu dosiero estas komunuma alsxuto kaj estas uzebla de aliaj projektoj.',
'shareduploadwiki'               => 'Bonvolu vidi la $1 por plua informo.',
'shareduploadwiki-desc'          => 'Jen la priskribo de ties $1 en la kolektiva dosierujo sube.',
'shareduploadwiki-linktext'      => 'paĝon pri dosiera priskribo',
'shareduploadduplicate'          => 'Ĉi tiu dosiero estas duplikato de $1 de la kolektiva dosierujo.',
'shareduploadduplicate-linktext' => 'alia dosiero',
'shareduploadconflict'           => 'Ĉi tiu dosiero havas la saman nomon kiel $1 de la kolektiva dosierujo.',
'shareduploadconflict-linktext'  => 'alia dosiero',
'noimage'                        => 'Ne ekzistas dosiero kun tia nomo vi povas $1.',
'noimage-linktext'               => 'alŝuti ĝin',
'uploadnewversion-linktext'      => 'Alŝuti novan version de ĉi tiu dosiero',
'imagepage-searchdupe'           => 'Serĉu duplikatajn dosierojn',

# File reversion
'filerevert'                => 'Restarigu $1',
'filerevert-legend'         => 'Restarigu dosieron',
'filerevert-intro'          => '<span class="plainlinks">Vi restarigas \'\'\'[[Media:$1|$1]]\'\'\' al la [$4 versio de $3, $2].</span>',
'filerevert-comment'        => 'Komento:',
'filerevert-defaultcomment' => 'Restarigita al versio ekde $2, $1',
'filerevert-submit'         => 'Restarigi',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' estis restarigita al [$4 versio ekde $3, $2].</span>',
'filerevert-badversion'     => 'Ne estas antaŭa loka versio de ĉi dosiero ĉe tiu tempo.',

# File deletion
'filedelete'                  => 'Forigu $1',
'filedelete-legend'           => 'Forigu dosieron.',
'filedelete-intro'            => "Vi forigas '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Vi forigas version de \'\'\'[[Media:$1|$1]]\'\'\' ekde [$4 $3, $2].</span>',
'filedelete-comment'          => 'Kialo por forigo:',
'filedelete-submit'           => 'Forigi',
'filedelete-success'          => "'''$1''' estas forigita.",
'filedelete-success-old'      => '<span class="plainlinks">La versio de \'\'\'[[Media:$1|$1]]\'\'\' ekde $3, $2 estas forigita.</span>',
'filedelete-nofile'           => "'''$1''' ne ekzistas en {{SITENAME}}.",
'filedelete-nofile-old'       => "Estas neniuarkivita versio de '''$1''' kun la specifigitaj atribuoj.",
'filedelete-iscurrent'        => 'Vi provas forigi la plej lastan version de la dosiero. Bonvolu restarigi pli malnovan version antaŭe.',
'filedelete-otherreason'      => 'Alia/plua kialo:',
'filedelete-reason-otherlist' => 'Alia kialo',
'filedelete-reason-dropdown'  => '* Oftaj kialoj de forigo
** Malobservo de kopirajto
** Duplikata dosiero',
'filedelete-edit-reasonlist'  => 'Redaktu kialojn de forigo',

# MIME search
'mimesearch'         => 'MIME-serĉilo',
'mimesearch-summary' => 'Ĉi paĝo ebligas la filtradon de dosieroj por ties MIME-tipo. Enigu: enhavo-tipo/subtipo, ekz. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-tipo:',
'download'           => 'elŝutu',

# Unwatched pages
'unwatchedpages' => 'Neatentataj paĝoj',

# List redirects
'listredirects' => 'Listo de redirektiloj',

# Unused templates
'unusedtemplates'     => 'Neuzitaj ŝablonoj',
'unusedtemplatestext' => 'Ĉi paĝo listigas ĉiujn paĝojn en la nomspaco "Ŝablono" kiuj ne estas enmetitaj en alia paĝo. Bonvolu kontroli aliajn ligilojn al la ŝablonoj antaŭ ol forigi ilin.',
'unusedtemplateswlh'  => 'aliaj ligiloj',

# Random page
'randompage'         => 'Hazarda paĝo',
'randompage-nopages' => 'Ne ekzistas paĝoj en ĉi tiu nomspaco.',

# Random redirect
'randomredirect'         => 'Hazarda alidirekto',
'randomredirect-nopages' => 'Estas neniuj alidirektiloj en ĉi nomspaco.',

# Statistics
'statistics'             => 'Statistiko',
'sitestats'              => 'Pri la retejo',
'userstats'              => 'Pri la uzantaro',
'sitestatstext'          => "Troviĝas en nia datumaro sume '''\$1''' {{PLURAL:\$1|paĝo|paĝoj}}.
Tiu nombro enhavas \"diskutpaĝojn\", paĝojn pri {{SITENAME}}, \"artikoletetojn\", alidirektilojn, kaj aliajn, kiuj eble ne vere estas artikoloj. Malatentante ilin, oni povas nombri '''\$2''' {{PLURAL:\$2|probable ĝustan artikolon|probable ĝustajn artikolojn}}.

'''\$8''' {{PLURAL:\$8|dosiero|dosieroj}} estis {{PLURAL:\$8|alŝutita|alŝutitaj}}.

Oni vidis sume '''\$3''' {{PLURAL:\$3|paĝo|paĝojn}}, kaj redaktis sume '''\$4''' {{PLURAL:\$4|paĝo|paĝojn}} ekde la starigo de la vikio.
Tio estas meznombre po unu paĝo por '''\$5''' paĝoj viditaj, kaj por '''\$6''' redaktoj.

La nuna longeco de la [http://meta.wikimedia.org/wiki/Help:Job_queue laborenda vico] estas '''\$7'''.",
'userstatstext'          => "Enskribiĝis '''$1''' {{PLURAL:$1|[[Special:Listusers|uzanto]]|[[Special:Listusers|uzantoj]]}}. El tiuj, '''$2''' (aŭ '''$4%''') havas rajtojn de $5.",
'statistics-mostpopular' => 'Plej ofte montrataj paĝoj',

'disambiguations'      => 'Misligitaj apartigiloj',
'disambiguationspage'  => 'Template:Apartigilo',
'disambiguations-text' => 'La jenaj paĝoj alligas <i>paĝon-apartigilon</i>. Ili devus anstataŭe alligi la ĝustan temon.<br />Oni konsideras tiujn paĝojn, kiujn alligas $1 apartigiloj.<br />Ligado el ne-artikolaj sekcioj <i>ne</i> listiĝas ĉi tie.',

'doubleredirects'     => 'Duoblaj alidirektadoj',
'doubleredirectstext' => '<b>Atentu:</b> Eblas, ke la jena listo enhavas falsajn rezultojn. Ĝenerale, tio signifas, ke estas plua teksto kun ligiloj post la #REDIRECT.<br />
Ĉiu linio montras ligilojn ĉe la unua kaj dua alidirektadoj, kaj la unua linio de la teksto de la dua alidirektado, kiu ĝenerale montras la "veran" artikolon, kiu devus celi la unuan alidirektadon.',

'brokenredirects'        => 'Rompitaj alidirektadoj',
'brokenredirectstext'    => 'La jenaj alidirektadoj ligas al neekzistantaj artikoloj.',
'brokenredirects-edit'   => '(redakti)',
'brokenredirects-delete' => '(forigi)',

'withoutinterwiki'         => 'Paĝoj sen lingvaj ligiloj',
'withoutinterwiki-summary' => 'Jenaj paĝoj ne ligas al aliaj lingvoversioj:',
'withoutinterwiki-legend'  => 'Prefikso',
'withoutinterwiki-submit'  => 'Montri',

'fewestrevisions' => 'Artikoloj kun la plej malmultaj revizioj',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bitoko|bitokoj}}',
'ncategories'             => '{{PLURAL:$1|unu kategorio|$1 kategorioj}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligilo|ligiloj}}',
'nmembers'                => '{{PLURAL:$1|unu membero|$1 memberoj}}',
'nrevisions'              => '$1 {{PLURAL:$1|revizio|revizioj}}',
'nviews'                  => '{{PLURAL:$1|unufoje|$1 fojojn}}',
'specialpage-empty'       => 'Ne estas rezultoj por ĉi tiu raporto.',
'lonelypages'             => 'Neligitaj paĝoj',
'lonelypagestext'         => 'Jenaj paĝoj ne estas ligitaj de aliaj paĝoj en {{SITENAME}}.',
'uncategorizedpages'      => 'Neenkategoriitaj paĝoj',
'uncategorizedcategories' => 'Neenkategoriitaj kategorioj',
'uncategorizedimages'     => 'Neenkategoriigitaj dosieroj',
'uncategorizedtemplates'  => 'Neenkategoriigitaj ŝablonoj',
'unusedcategories'        => 'Neuzitaj kategorioj',
'unusedimages'            => 'Neuzataj bildoj',
'popularpages'            => 'Plej vizitataj paĝoj',
'wantedcategories'        => 'Dezirataj kategorioj',
'wantedpages'             => 'Dezirataj paĝoj',
'mostlinked'              => 'Plej ligitaj paĝoj',
'mostlinkedcategories'    => 'Plej ligitaj kategorioj',
'mostlinkedtemplates'     => 'Plej ligitaj ŝablonoj',
'mostcategories'          => 'Artikoloj kun la plej multaj kategorioj',
'mostimages'              => 'Plej ligitaj bildoj',
'mostrevisions'           => 'Artikoloj kun la plej multaj revizioj',
'prefixindex'             => 'Indeksa prefikso',
'shortpages'              => 'Mallongaj paĝoj',
'longpages'               => 'Longaj paĝoj',
'deadendpages'            => 'Paĝoj sen interna ligilo',
'deadendpagestext'        => 'La sekvaj paĝoj ne ligas al aliaj paĝoj en {{SITENAME}}.',
'protectedpages'          => 'Protektitaj paĝoj',
'protectedpages-indef'    => 'Nur ĉiamaj protektaĵoj',
'protectedpagestext'      => 'La sekvaj paĝoj estas protektitaj kontraŭ movigo aŭ redaktado',
'protectedpagesempty'     => 'Neniuj paĝoj estas momente protektitaj kun ĉi tiuj parametroj.',
'protectedtitles'         => 'Protektitaj titoloj',
'protectedtitlestext'     => 'La jenaj titoloj estas protektitaj kontraŭ kreado',
'protectedtitlesempty'    => 'Neniuj titoloj estas nune protektitaj kun ĉi parametroj.',
'listusers'               => 'Uzantaro',
'specialpages'            => 'Specialaj paĝoj',
'spheading'               => 'Specialaj paĝoj',
'restrictedpheading'      => 'Alirlimigitaj specialaj paĝoj',
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
'booksources-search-legend' => 'Serĉu pri librofontoj',
'booksources-go'            => 'Ek',
'booksources-text'          => 'Jen ligilaro al aliaj TTT-ejoj, kiuj vendas librojn,
kaj/aŭ informumos pri la libro ligita.
La {{SITENAME}} ne estas komerce ligita al tiuj vendejoj, kaj la listo ne estu
komprenata kiel rekomendo aŭ reklamo.',

# Special:Log
'specialloguserlabel'  => 'Uzanto:',
'speciallogtitlelabel' => 'Titolo:',
'log'                  => 'Protokoloj',
'all-logs-page'        => 'Ĉiuj protokoloj',
'log-search-legend'    => 'Serĉu loglibrojn',
'log-search-submit'    => 'Ek',
'alllogstext'          => 'Suma kompilaĵo de ĉiuj alŝutoj, forigoj, protektoj, blokadoj kaj agoj de administrantoj. Vi povas pliprecizigi la kompilaĵon laŭ loglibra tipo, **** vikipediista **** nomo aŭ koncernita paĝo.',
'logempty'             => 'Neniaj artikoloj en la loglibro.',
'log-title-wildcard'   => 'Serĉu titolojn komencantajn kun ĉi teksto',

# Special:Allpages
'allpages'          => 'Ĉiuj paĝoj',
'alphaindexline'    => '$1 ĝis $2',
'nextpage'          => 'Sekvanta paĝo ($1)',
'prevpage'          => 'Antaŭa paĝo ($1)',
'allpagesfrom'      => 'Montri paĝojn ekde:',
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
'categories'                    => '{{PLURAL:$1|Kategorio|Kategorioj}}',
'categoriespagetext'            => 'La sekvantaj kategorioj ekzistas jam en la vikio.',
'categoriesfrom'                => 'Montri kategoriojn komencante de:',
'special-categories-sort-count' => 'oridigu laŭ nombrado',
'special-categories-sort-abc'   => 'ordigu alfabete',

# Special:Listusers
'listusersfrom'      => 'Montru uzantojn ekde:',
'listusers-submit'   => 'Montri',
'listusers-noresult' => 'Neniu uzanto trovita.',

# Special:Listgrouprights
'listgrouprights'          => 'Gruprajtoj de uzantoj',
'listgrouprights-summary'  => 'Jen listo de uzanto-grupoj difinitaj en ĉi tiu vikio, kun ties asociaj atingrajtoj. Aldona informo pri individuaj rajtoj estas trovebla [[{{int:Listgrouprights-helppage}}|ĉi tie]].',
'listgrouprights-group'    => 'Grupo',
'listgrouprights-rights'   => 'Rajtoj',
'listgrouprights-helppage' => 'Help:Grupaj rajtoj',
'listgrouprights-members'  => '(listo de anoj)',

# E-mail user
'mailnologin'     => 'Neniu alsendota adreso',
'mailnologintext' => 'Vi nepre estu [[Special:Userlogin|salutanta]] kaj havanta validan retpoŝtadreson en viaj [[Special:Preferences|preferoj]] por retpoŝti al aliaj uzantoj.',
'emailuser'       => 'Retpoŝti ĉi tiun uzanton',
'emailpage'       => 'Retpoŝtu',
'emailpagetext'   => 'Se la alsendota uzanto donis validan retpoŝtadreson en la preferoj, vi povas sendi unu mesaĝon per la jena formulo. La retpoŝtadreso, kiun vi metis en la preferoj, aperos kiel "El"-adreso de la poŝto, por ke la alsendonto povos respondi.',
'usermailererror' => 'Resendita retmesaĝa erarsubjekto:',
'defemailsubject' => '{{SITENAME}} ****-retmesaĝo',
'noemailtitle'    => 'Neniu retpoŝtadreso',
'noemailtext'     => 'Ĉi tiu uzanto aŭ ne donis validan retpoŝtadreson aŭ elektis ne ricevi retpoŝton de aliaj uzantoj.',
'emailfrom'       => 'El',
'emailto'         => 'Al',
'emailsubject'    => 'Subjekto',
'emailmessage'    => 'Mesaĝo',
'emailsend'       => 'Sendu',
'emailccme'       => 'Retpoŝtu al mi kopion de mia mesaĝo.',
'emailccsubject'  => 'Kopio de via mesaĝo al $1: $2',
'emailsent'       => 'Retmesaĝo sendita',
'emailsenttext'   => 'Via retmesaĝo estas sendita.',

# Watchlist
'watchlist'            => 'Atentaro',
'mywatchlist'          => 'Atentaro',
'watchlistfor'         => '(por <b>$1</b>)',
'nowatchlist'          => 'Vi ne jam elektis priatenti iun ajn paĝon.',
'watchlistanontext'    => 'Bonvolu $1 okulumi aŭ redakti la anojn de via atentaro.',
'watchnologin'         => 'Ne ensalutinta',
'watchnologintext'     => 'Nepras [[Special:Userlogin|ensaluti]] por ŝanĝi vian atentaron.',
'addedwatch'           => 'Aldonis al atentaro',
'addedwatchtext'       => "La paĝo \"[[:\$1]]\" estis aldonita al via [[Special:Watchlist|atentaro]]. Estontaj ŝanĝoj de tiu ĉi paĝo aperos en '''grasa tiparo''' en la [[Special:Recentchanges|listo de Lastaj Ŝanĝoj]], kaj estos listigitaj en via atentaro. Se vi poste volos forigi la paĝon el via atentaro, alklaku \"Malatentu paĝon\" en la ilobreto.",
'removedwatch'         => 'Forigis el atentaro',
'removedwatchtext'     => 'La paĝo "[[:$1]]" estas forigita el via atentaro.',
'watch'                => 'Atenti',
'watchthispage'        => 'Priatenti paĝon',
'unwatch'              => 'Malatenti',
'unwatchthispage'      => 'Malatentu paĝon',
'notanarticle'         => 'Ne estas artikolo',
'notvisiblerev'        => 'Revizio estis forigita',
'watchnochange'        => 'Neniu artikolo en via atentaro redaktiĝis dum la prispektita tempoperiodo.',
'watchlist-details'    => 'Vi priatentas {{PLURAL:$1|$1 paĝon|$1 paĝojn}}, krom diskutpaĝoj.',
'wlheader-enotif'      => '* Retpoŝta sciigo estas ebligita',
'wlheader-showupdated' => "* Montriĝas per '''dikaj literoj''' tiuj paĝoj, kiujn oni ŝanĝis ekde kiam vi laste vizitis ilin",
'watchmethod-recent'   => 'traserĉas lastajn redaktojn',
'watchmethod-list'     => 'traserĉas priatentitajn',
'watchlistcontains'    => 'Via atentaro enhavas $1 {{PLURAL:$1|paĝon|paĝojn}}.',
'iteminvalidname'      => 'Ia eraro pri "$1", nevalida titolo...',
'wlnote'               => "Jen la {{PLURAL:$1|lasta redakto|lastaj '''$1''' redaktoj}} dum la {{PLURAL:$2|lasta horo|lastaj '''$2''' horoj}}.",
'wlshowlast'           => 'Montri el lastaj $1 horoj $2 tagoj $3',
'watchlist-show-bots'  => 'Montru robotajn redaktojn',
'watchlist-hide-bots'  => 'Kaŝi robotajn redaktojn',
'watchlist-show-own'   => 'Montru miajn redaktojn',
'watchlist-hide-own'   => 'Kaŝi miajn redaktojn',
'watchlist-show-minor' => 'Montru redaktetojn',
'watchlist-hide-minor' => 'Kaŝi redaktetojn',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Atentante...',
'unwatching' => 'Malatentante...',

'enotif_mailer'                => 'Averta retmesaĝo de {{SITENAME}}',
'enotif_reset'                 => 'Marku ĉiujn vizititajn paĝojn',
'enotif_newpagetext'           => 'Tiu ĉi estas nova paĝo',
'enotif_impersonal_salutation' => '{{SITENAME}}-uzanto',
'changed'                      => 'ŝanĝita',
'created'                      => 'kreita',
'enotif_subject'               => 'la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'           => 'Vidu $1 por ĉiuj ŝanĝoj de post via lasta vizito.',
'enotif_lastdiff'              => 'Vidu $1 por okulumi cxi ŝanĝon.',
'enotif_anon_editor'           => 'anonima uzanto $1',
'enotif_body'                  => 'Kara $WATCHINGUSERNAME,

la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED je $PAGEEDITDATE de $PAGEEDITOR, vidu {{fullurl:$PAGETITLE RAWURL}} por la nuna versio.

$NEWPAGE

Redakta resumo : $PAGESUMMARY $PAGEMINOREDIT

Kontaktu la redaktinton:
retpoŝto {{fullurl:Special:Emailuser/$PAGEEDITOR RAWURL}}
vikio {{fullurl:User:$PAGEEDITOR RAWURL}}

Ne estos aliaj avertoj kaze de sekvaj ŝanĝoj krom se vi vizitas la paĝon. Vi povas ankaŭ malaktivigi la avertsignalon por ĉiuj priatentitaj paĝoj de via atentaro.

             Sincere via, la avertsistemo de {{SITENAME}}

--
Por ŝanĝi la elektojn de via atentaro, bv viziti
{{fullurl:Special:Watchlist/edit}}

Reagoj kaj plia helpo :
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Forigi paĝon',
'confirm'                     => 'Konfirmu',
'excontent'                   => "enhavis: '$1'",
'excontentauthor'             => "la enteno estis : '$1' (kaj la sola kontribuinto estis '$2')",
'exbeforeblank'               => "antaŭ malplenigo enhavis: '$1'",
'exblank'                     => 'estis malplena',
'delete-confirm'              => 'Forviŝu "$1"',
'delete-legend'               => 'Forigi',
'historywarning'              => 'Averto: la forigota paĝo havas historion:',
'confirmdeletetext'           => 'Vi forigos la artikolon aŭ dosieron kaj forviŝos ĝian tutan historion el la datumaro.<br /> Bonvolu konfirmi, ke vi vere intencas tion, kaj ke vi komprenas la sekvojn, kaj ke vi ja sekvas la [[{{MediaWiki:Policy-url}}|regulojn pri forigado]].',
'actioncomplete'              => 'Ago farita',
'deletedtext'                 => '"<nowiki>$1</nowiki>" estas forigita.
Vidu la paĝon $2 por registro de lastatempaj forigoj.',
'deletedarticle'              => 'forigis "$1"',
'suppressedarticle'           => '"[[$1]]" estas subigita',
'dellogpage'                  => 'Protokolo de forigoj',
'dellogpagetext'              => 'Jen listo de la plej lastaj forigoj el la datumaro.
Ĉiuj tempoj sekvas la horzonon UTC.',
'deletionlog'                 => 'loglibro de forigoj',
'reverted'                    => 'Restarigis antaŭan version',
'deletecomment'               => 'Kialo por forigo:',
'deleteotherreason'           => 'Alia/plua kialo:',
'deletereasonotherlist'       => 'Alia kialo',
'deletereason-dropdown'       => '*Oftaj kialoj por forigo
** Peto de aŭtoro
** Malobservo de kopirajto
** Vandalismo',
'delete-edit-reasonlist'      => 'Redaktu kialojn de forigo',
'delete-toobig'               => 'Ĉi paĝo havas grandan redakto-historion, pli ol $1 reviziojn. Forigo de ĉi tiaj paĝoj estis limigitaj por preventi akcidentan disrompigon de {{SITENAME}}.',
'delete-warning-toobig'       => 'Ĉi paĝo havas grandan redakto-historion, pli ol $1 reviziojn. Forigo de ĝi povas disrompigi operacion de {{SITENAME}}; forigu singarde.',
'rollback'                    => 'Restarigu antaŭan redakton',
'rollback_short'              => 'Restarigo',
'rollbacklink'                => 'restarigi antaŭan',
'rollbackfailed'              => 'Restarigo malsukcesis',
'cantrollback'                => 'Neeblas restarigi antaŭan redakton; la redaktinto lasta estas la sola de la paĝo.',
'alreadyrolled'               => 'Ne eblas restarigi la lastan redakton de [[$1]] de la [[User:$2|$2]] ([[User talk:$2|diskuto]]) pro tio, ke oni intertempe redaktis la paĝon. La lasta redaktinto estas [[User:$3|$3]] ([[User talk:$3|diskuto]]).',
'editcomment'                 => "La komento estis: '<i>$1</i>'.", # only shown if there is an edit comment
'revertpage'                  => 'Forigis redaktojn de [[Special:Contributions/$2|$2]] ([[User talk:$2|diskuto]]); restarigis al la lasta versio de [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Restaris redaktojn de $1; ŝanĝis al lasta versio de $2.',
'sessionfailure'              => 'Ŝajnas ke estas problemo kun via ensalutado;
Ĉi ago estis nuligita por malhelpi fiensalutadon.
Bonvolu alklalki la reirbutonon kaj reŝarĝi la paĝon el kiu vi venas, kaj provu denove.',
'protectlogpage'              => 'Protokolo de protektoj',
'protectlogtext'              => 'Sube estas listo de paĝ-ŝlosoj kaj malŝlosoj.
Vidu [[Special:Protectedpages|liston de protektitaj paĝoj]] por pli da informoj.',
'protectedarticle'            => 'protektita [[:$1]]',
'modifiedarticleprotection'   => 'ŝanĝis nivelon de protekto por "[[$1]]"',
'unprotectedarticle'          => 'malprotektita [[$1]]',
'protect-title'               => 'Protektante "$1"',
'protect-legend'              => 'Konfirmu protektadon',
'protectcomment'              => 'Komento:',
'protectexpiry'               => 'Eksvalidiĝas:',
'protect_expiry_invalid'      => 'Nevalida findaŭro.',
'protect_expiry_old'          => 'Eksvalidiĝa tempo jam pasis.',
'protect-unchain'             => 'Malŝlosi movpermesojn',
'protect-text'                => 'Vi povas ĉi tie vidi kaj ŝanĝi la protektnivelon de la paĝo [[$1]]. Bonvolu certiĝi ke vi respektas la [[Special:Protectedpages|gvidliniojn de la projekto]].',
'protect-locked-blocked'      => 'Vi ne povas ŝanĝi prokekto-nivelojn dum forbarita. Jen la nunaj ecoj de la paĝo <strong>$1</strong>:',
'protect-locked-dblock'       => 'Ne eblas ŝanĝi nivelojn de protekto pro aktiva datumbaza ŝloso.
Jen la nunaj ecoj de la paĝo <strong>$1</strong>:',
'protect-locked-access'       => 'Via konto ne havas permeson ŝanĝi protekto-nivelojn.
Jen la aktualaj valoroj por la paĝo <strong>$1</strong>:',
'protect-cascadeon'           => 'Ĉi paĝo estas nun protektita kontraŭ redaktado ĉar gxi estas inkluzivita en {{PLURAL:$1|jena paĝo, kiu mem estas protektita|jenaj paĝoj, kiuj mem estas protektitaj}} kun kaskada protekto. Vi povas ŝanĝi ties protektnivelon, sed tio ne ŝanĝos la kaskadan protekton.',
'protect-default'             => '(defaŭlte)',
'protect-fallback'            => '"$1" permeso bezonata',
'protect-level-autoconfirmed' => 'Bloki neensalutintajn uzantojn',
'protect-level-sysop'         => 'Nur administrantoj',
'protect-summary-cascade'     => 'kaskada',
'protect-expiring'            => 'finiĝas je $1 (UTC)',
'protect-cascade'             => 'Protekti ĉiujn paĝojn inkluzivitajn en ĉi paĝo (kaskada protekto)',
'protect-cantedit'            => 'Vi ne povas ŝanĝi la protekt-nivelojn de ĉi tiu paĝo, ĉar vi ne havas permeson redakti ĝin.',
'restriction-type'            => 'Permeso:',
'restriction-level'           => 'Nivelo de limigo:',
'minimum-size'                => 'Minimuma grandeco',
'maximum-size'                => 'Maksimuma grandeco:',
'pagesize'                    => '(bitokoj)',

# Restrictions (nouns)
'restriction-edit'   => 'Redakti',
'restriction-move'   => 'Alinomigi',
'restriction-create' => 'Krei',
'restriction-upload' => 'Alŝutu',

# Restriction levels
'restriction-level-sysop'         => 'plene protektita',
'restriction-level-autoconfirmed' => 'duone protektita',
'restriction-level-all'           => 'iu nivelo',

# Undelete
'undelete'                     => 'Restarigu forigitajn paĝojn',
'undeletepage'                 => 'Montru kaj restarigu forigitajn paĝojn',
'undeletepagetitle'            => "'''Jen la forigitaj revizioj de [[:$1]]'''.",
'viewdeletedpage'              => 'Rigardu forigitajn paĝojn',
'undeletepagetext'             => 'La jenaj paĝoj estis forigitaj, sed ankoraŭ restas arkivitaj,
kaj oni povas restarigi ilin. La arkivo povas esti malplenigita periode.',
'undeleteextrahelp'            => "Por restarigi la tuton de la paĝo, marku neniun markobutonon kaj klaku la butonon '''''Restarigu'''''. Por restarigi selektitajn versiojn de la paĝo, marku la butonojn konformajn al la dezirataj versioj, kaj klaku la butonon '''''Restarigu'''''. Klako je '''''Restarigu''''' malplenigos la komentozonon kaj malmarkos ĉiujn la markobutonojn.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versio arkivita|versioj arkivitaj}}',
'undeletehistory'              => 'Se vi restarigos la paĝon, ĉiuj versioj estos restarigitaj
en la historio. Se nova paĝo kun la sama nomo estis kreita post la forigo, la restarigitaj
versioj aperos antaŭe en la historio, kaj la aktuala versio ne estos anstataŭigita.',
'undeleterevdel'               => 'Restarigo ne estos farita se ĝi rezultos en la supera paĝa aŭ dosiera revizio estonte parte forigita. Tiuzake, vi malmarku aŭ malkaŝu la plej novajn forigitajn reviziojn.',
'undeletehistorynoadmin'       => 'Ĉi tiu artikolo estis forigita. La kaŭzo por la forigo estas montrata en la malsupra resumo, kune kun detaloj pri la uzantoj, kiuj redaktis ĉi tiun paĝon antaŭ la forigo. La aktuala teksto de ĉi tiuj forigitaj revizioj estas atingebla nur por administrantoj.',
'undelete-revision'            => 'Forigita revizio de $1 (de $2) fare de $3:',
'undeleterevision-missing'     => 'Nevalida aŭ malaperita revizio. Vi verŝajne havas malbonan ligilon, aŭ la
revizio eble estis restarigita aŭ forigita de la arkivo.',
'undelete-nodiff'              => 'Neniu antaŭa revizio trovebla.',
'undeletebtn'                  => 'Restarigi',
'undeletelink'                 => 'restarigi',
'undeletereset'                => 'Reŝarĝu',
'undeletecomment'              => 'Komento:',
'undeletedarticle'             => 'restarigis "$1"',
'undeletedrevisions'           => '{{PLURAL:$1|1 versio restarigita|$1 versioj restarigitaj}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 versio|$1 versioj}} kaj {{PLURAL:$2|1 dosiero|$2 dosieroj}} restarigitaj',
'undeletedfiles'               => '{{PLURAL:$1|1 dosiero restarigita|$1 dosieroj restarigitaj}}',
'cannotundelete'               => 'Restarigo malsukcesis; iu eble restarigis la paĝon antaŭe.',
'undeletedpage'                => "<big>'''$1 estis restarigita'''</big>

Konsultu la [[Special:Log/delete|deletion log]] por protokolo pri la lastatempaj forigoj kaj restarigoj.",
'undelete-header'              => 'Konsultu la [[Special:Log/delete|loglibro de forigoj]] por lastatempaj forigoj.',
'undelete-search-box'          => 'Serĉu forigitajn paĝojn',
'undelete-search-prefix'       => 'Montru paĝojn komence kun:',
'undelete-search-submit'       => 'Serĉi',
'undelete-no-results'          => 'Neniuj kongruaj paĝoj trovitaj en la forigo-arkivo.',
'undelete-filename-mismatch'   => 'Ne eblas restarigi dosiero-revizion kun tempo $1: malkongrua dosiernomo',
'undelete-bad-store-key'       => 'Ne eblas restarigi dosiero-revizio de tempo $1: dosiero estis malaperita antaŭ forigo.',
'undelete-cleanup-error'       => 'Eraro forigante la neuzatan arkivon "$1".',
'undelete-missing-filearchive' => 'Ne eblas restarigi dosiera arkivo ID $1 ĉar ĝi ne estas en la datumbazo. Verŝajne ĝi jam estis restarigita.',
'undelete-error-short'         => 'Eraro pro restarigo de dosiero: $1',
'undelete-error-long'          => 'Jen eraroj dum restarigo de dosiero:

$1',

# Namespace form on various pages
'namespace'      => 'Nomspaco:',
'invert'         => 'Inversi selektaĵon',
'blanknamespace' => '(Artikoloj)',

# Contributions
'contributions' => 'Kontribuoj de uzanto',
'mycontris'     => 'Miaj kontribuoj',
'contribsub2'   => 'De $1 ($2)',
'nocontribs'    => 'Trovis neniajn redaktojn laŭ tiu kriterio.',
'uctop'         => ' (lasta)',
'month'         => 'Ekde monato (kaj pli frue):',
'year'          => 'Ekde jaro (kaj pli frue):',

'sp-contributions-newbies'     => 'Montru kontribuojn nur de novaj kontoj',
'sp-contributions-newbies-sub' => 'Kontribuoj de novaj uzantoj. Forigitaj paĝoj ne estas montritaj.',
'sp-contributions-blocklog'    => 'Protokolo de forbaroj',
'sp-contributions-search'      => 'Serĉado de kontribuoj',
'sp-contributions-username'    => 'IP-adreso aŭ uzantonomo:',
'sp-contributions-submit'      => 'Serĉi',

# What links here
'whatlinkshere'            => 'Ligiloj ĉi tien',
'whatlinkshere-title'      => 'Paĝoj ligantaj al $1',
'whatlinkshere-page'       => 'Paĝo:',
'linklistsub'              => '(Listo de ligiloj)',
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
'blockip'                     => 'Forbaru uzanton/IP-adreson',
'blockip-legend'              => 'Forbaru uzanton',
'blockiptext'                 => "Per jena formularo vi povas forpreni de ajna nomo aŭ IP-adreso la rajton skribi en la vikio. Oni faru tion ''nur'' por eviti vandalismon, kaj sekvante la [[{{MediaWiki:Policy-url}}|regulojn pri forbarado]]. Klarigu la precizan kialon malsupre (ekzemple, citu paĝojn, kiuj estis vandaligitaj).",
'ipaddress'                   => 'IP-adreso/nomo',
'ipadressorusername'          => 'IP adreso aŭ uzantonomo',
'ipbexpiry'                   => 'Blokdaŭro',
'ipbreason'                   => 'Kialo:',
'ipbreasonotherlist'          => 'Alia kaŭzo',
'ipbreason-dropdown'          => '*Oftaj kialoj de forbaro
** Enmetas malveraĵojn
** Forviŝas entenon el paĝoj
** Entrudas ligilojn al eksteraj paĝaroj
** Enmetas sensencaĵojn
** Terurigema sinteno
** Misuzo de pluraj salutnomoj
** Neakceptebla uzanto-nomo',
'ipbanononly'                 => 'Forbaru nur anonimulojn',
'ipbcreateaccount'            => 'Malpermesu kreadon de konto',
'ipbemailban'                 => 'Malebligu al uzanto sendi retpoŝton.',
'ipbenableautoblock'          => 'Aŭtomate forbaru la lastan IP-adreson uzitan de la uzanto, kaj ĉiajn subsekvantajn adresojn el kiuj tiu provos redakti',
'ipbsubmit'                   => 'Forbaru la adreson',
'ipbother'                    => 'Alia daŭro:',
'ipboptions'                  => '2 horoj:2 hours,1 tago:1 day,3 tagoj:3 days,1 semajno:1 week,2 semajnoj:2 weeks,1 monato:1 month,3 monatoj:3 months,6 monatoj:6 months,1 jaro:1 year,porĉiam:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'alia',
'ipbotherreason'              => 'Alia/aldona kaŭzo:',
'ipbhidename'                 => 'Kaŝu la uzantonomon de la forbara loglibro, de la aktuala forbarlisto kaj de la uzantolisto.',
'ipbwatchuser'                => 'Kontroli la paĝojn por uzanto kaj diskuto de ĉi tiu uzanto.',
'badipaddress'                => 'Neniu uzanto, aŭ la IP-adreso estas misformita.',
'blockipsuccesssub'           => 'Oni sukcese forbaris la adreson/nomon.',
'blockipsuccesstext'          => '"$1" estas forbarita. <br />Vidu la [[Special:Ipblocklist|liston de IP-forbaroj]].',
'ipb-edit-dropdown'           => 'Redaktu kialojn de forbaro.',
'ipb-unblock-addr'            => 'Malforbaru $1',
'ipb-unblock'                 => 'Malforbaru uzantnomon aŭ IP-adreson',
'ipb-blocklist-addr'          => 'Rigardu ekzistantajn forbarojn por $1',
'ipb-blocklist'               => 'Rigardu ekzistantajn blokojn',
'unblockip'                   => 'Malforbaru IP-adreson/nomon',
'unblockiptext'               => 'Per la jena formulo vi povas repovigi al iu
forbarita IP-adreso/nomo la povon enskribi en la vikio.',
'ipusubmit'                   => 'Malforbaru la adreson',
'unblocked'                   => '[[User:$1|$1]] estas malforbarita.',
'unblocked-id'                => 'Forbaro $1 estas forigita.',
'ipblocklist'                 => 'Listo de forbaritaj IP-adresoj/nomoj',
'ipblocklist-legend'          => 'Trovu forbaritan uzanton.',
'ipblocklist-username'        => 'Uzantonomo aŭ IP-adreso:',
'ipblocklist-submit'          => 'Serĉi',
'blocklistline'               => 'Je $1, $2 forbaris $3 ($4)',
'infiniteblock'               => 'senfina',
'expiringblock'               => 'finiĝas je $1',
'anononlyblock'               => 'nur anonimuloj',
'noautoblockblock'            => 'aŭtomata blokado estas malebligita',
'createaccountblock'          => 'Kreado de kontoj forbarita',
'emailblock'                  => 'retpoŝto forbarita',
'ipblocklist-empty'           => 'La forbarlibro estas malplena.',
'ipblocklist-no-results'      => 'Ĉi tiu IP-adreso aŭ salutnomo ne estas forbarita.',
'blocklink'                   => 'forbaru',
'unblocklink'                 => 'malforbari',
'contribslink'                => 'kontribuoj',
'autoblocker'                 => 'Provizore forbarita aŭtomate pro tio, ke vi uzas la saman IP-adreson kiel "$1", kiu estis forbarita pro : "$2".',
'blocklogpage'                => 'Protokolo de forbaroj',
'blocklogentry'               => 'forbaris [[$1]] por daŭro de $2 $3',
'blocklogtext'                => 'Ĉi tio estas loglibro pri forbaraj kaj malforbaraj agoj. Aŭtomate forbaritaj IP adresoj ne estas listigitaj. Vidu la [[Special:Ipblocklist|IP forbarliston]] por ĉi-momente fobaritaj uzantoj kaj IP-adresoj.',
'unblocklogentry'             => '$1 estis malbarita',
'block-log-flags-anononly'    => 'nur anonimaj uzantoj',
'block-log-flags-nocreate'    => 'kreado de kontoj malebligita',
'block-log-flags-noautoblock' => 'aŭtoblokado malebligita',
'block-log-flags-noemail'     => 'retpoŝto blokita',
'range_block_disabled'        => 'La ebleco de administranto krei forbaritajn intervalojn da IP-adresoj estas malebligita.',
'ipb_expiry_invalid'          => 'Nevalida blokdaŭro.',
'ipb_already_blocked'         => '"$1" estas jam forbarita',
'ipb_cant_unblock'            => 'Eraro: Forbar-identigo $1 ne estas trovita. Ĝi eble estis jam malforbarita.',
'ipb_blocked_as_range'        => 'Eraro: La IP-adreso $1 ne estas forbarita rekte kaj ne povas esti malforbarita. Tamen ĝi estas forbarita kiel parto de la intervalo $2, kiu ne povas esti malforbarita.',
'ip_range_invalid'            => 'Nevalida IP-adresa intervalo.',
'blockme'                     => 'Forbaru min',
'proxyblocker'                => 'Forbarilo por prokuriloj.',
'proxyblocker-disabled'       => 'Ĉi funkcio estas malebligita.',
'proxyblockreason'            => 'Via IP-adreso estis forbarita ĉar ĝi estas malferma prokurilo. Bonvolu kontakti vian provizanto de retservo aŭ komputika helpisto kaj informu ilin de ĉi serioza problemo pri sekureco.',
'proxyblocksuccess'           => 'Farita.',
'sorbsreason'                 => 'Via IP-adreso estas listigita kiel malferma prokurilo en la DNSBL uzata de {{SITENAME}}.',
'sorbs_create_account_reason' => 'Via IP-adreso estas listigita kiel malferma prokurilo en la DNSBL uzata de {{SITENAME}}. Vi ne estas permesita krei konton.',

# Developer tools
'lockdb'              => 'Ŝlosi datumaron',
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
'lockbtn'             => 'Ŝlosi datumaron',
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
'move-page'               => 'Movu $1',
'move-page-legend'        => 'Alinomigi paĝon',
'movepagetext'            => "Per la jena formulo vi povas ŝanĝi la nomon de iu paĝo, kunportante
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
'movepagetalktext'        => "La movo aŭtomate kunportos la diskuto-paĝon, se tia ekzistas, '''krom se:'''
*Vi movas la paĝon tra nomspacoj (ekz de ''Nomo'' je ''User:Nomo''),
*Ne malplena diskuto-paĝo jam ekzistas je la nova nomo, aŭ
*Vi malelektas la suban ŝaltilon.

Tiujokaze, vi nepre permane kunigu la diskuto-paĝojn se vi tion deziras.",
'movearticle'             => 'Alinomigi paĝon',
'movenologin'             => 'Ne ensalutinta',
'movenologintext'         => 'Vi nepre estu registrita uzanto kaj [[Special:Userlogin|ensalutu]] por rajti movi paĝojn.',
'movenotallowed'          => 'Vi ne havas permeson movi paĝojn en {{SITENAME}}.',
'newtitle'                => 'Al nova titolo',
'move-watch'              => 'Atenti ĉi tiun paĝon',
'movepagebtn'             => 'Alinomigi paĝon',
'pagemovedsub'            => 'Sukcesis movi',
'movepage-moved'          => '<big>\'\'\'"$1" estis movita al "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Paĝo kun tiu nomo jam ekzistas, aŭ la nomo kiun vi elektis ne validas.
Bonvolu elekti alian nomon.',
'cantmove-titleprotected' => 'Vi ne povas movi paĝo al ĉi loko, ĉar la nova titolo estis protektita kontraŭ kreado',
'talkexists'              => 'Oni ja sukcesis movi la paĝon mem, sed
ne movis la diskuto-paĝon ĉar jam ekzistas tia ĉe la nova titolo.
Bonvolu permane kunigi ilin.',
'movedto'                 => 'movita al',
'movetalk'                => 'Movu ankaŭ la "diskuto"-paĝon, se ĝi ekzistas.',
'talkpagemoved'           => 'Ankaŭ la diskutpaĝo estas movita.',
'talkpagenotmoved'        => 'La diskutpaĝo <strong>ne</strong> estas movita.',
'1movedto2'               => '[[$1]] movita al [[$2]]',
'1movedto2_redir'         => '[[$1]] movita al [[$2]], redirekto lasita',
'movelogpage'             => 'Protokolo de paĝmovoj',
'movelogpagetext'         => 'Jen listo de movitaj paĝoj',
'movereason'              => 'Kialo:',
'revertmove'              => 'restarigi',
'delete_and_move'         => 'Forigu kaj movu',
'delete_and_move_text'    => '==Forigo nepras==

La celartikolo "[[$1]]" jam ekzistas. Ĉu vi volas forigi ĝin por krei spacon por la movo?',
'delete_and_move_confirm' => 'Jes, forigu la paĝon',
'delete_and_move_reason'  => 'Forigita por ebligi movon',
'selfmove'                => 'Font- kaj cel-titoloj samas; ne eblas movi paĝon sur ĝin mem.',
'immobile_namespace'      => 'La celtitolo estas de speciala speco; ne eblas movi paĝojn en tiun nomspacon.',
'imagenocrossnamespace'   => 'Ne eblas movi dosieron al nedosiera nomspaco',
'imagetypemismatch'       => 'La nova dosierfinaĵo ne kongruas ĝian dosiertipon.',

# Export
'export'            => 'Eksportu paĝojn',
'exporttext'        => 'Vi povas eksporti la tekston kaj la redaktohistorion de aparta paĝo aŭ de paĝaro kolektita en ia XML ; tio povas esti importita en alian programon funkciantan per MediaWiki-softvaro, ŝanĝita, aŭ nur prenita por propra privata uzo.',
'exportcuronly'     => 'Entenas nur la aktualan version, ne la malnovajn.',
'exportnohistory'   => "----
'''Notu:''' Eksportado de la plena historio de paĝoj per ĉi paĝo estis malebligita pro funkciigaj kialoj.",
'export-submit'     => 'Eksporti',
'export-addcattext' => 'Aldoni paĝojn el kategorio:',
'export-addcat'     => 'Aldoni',
'export-download'   => 'Konservu kiel dosieron',
'export-templates'  => 'Inkluzivu ŝablonojn',

# Namespace 8 related
'allmessages'               => 'Ĉiuj mesaĝoj',
'allmessagesname'           => 'Nomo',
'allmessagesdefault'        => 'Defaŭlta teksto',
'allmessagescurrent'        => 'Nuna teksto',
'allmessagestext'           => 'Ĉi tio estas listo de ĉiuj mesaĝoj haveblaj en la MediaWiki: nomspaco',
'allmessagesnotsupportedDB' => '{{ns:special}}:Allmessages ne subtenata ĉar la variablo wgUseDatabaseMessages estas malkonektita.',
'allmessagesfilter'         => 'Filtrilo laŭ racia esprimo :',
'allmessagesmodified'       => 'Montru nur ŝanĝitajn',

# Thumbnails
'thumbnail-more'           => 'Pligrandigu',
'filemissing'              => 'Mankanta dosiero',
'thumbnail_error'          => 'Okazis eraro kreante antaŭvidan bildeton: $1',
'djvu_page_error'          => 'DjVu-a paĝo el intervalo',
'djvu_no_xml'              => 'Ne eblas akiri XML por DjVu dosiero',
'thumbnail_invalid_params' => 'Nevalidaj bildetaj parametroj',
'thumbnail_dest_directory' => 'Ne eblas krei destinan dosierujon',

# Special:Import
'import'                     => 'Importitaj paĝoj',
'importinterwiki'            => 'Transvikia importo',
'import-interwiki-text'      => 'Elektu vikion kaj paĝan titolon por importi.
Datoj de revizioj kaj nomoj de redaktantoj estos preservitaj.
Ĉiuj transvikaj importoj estas raportitaj ĉe la [[Special:Log/import|loglibro de importoj]].',
'import-interwiki-history'   => 'Kopiu ĉiuj versioj el historio por ĉi pago.',
'import-interwiki-submit'    => 'Importi',
'import-interwiki-namespace' => 'Transigu paĝoj en nomspaco:',
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
'import-nonewrevisions'      => 'Ĉiuj revizioj estis antaŭe importitaj.',
'xml-error-string'           => '$1 ĉe linio $2, kolumno $3 (bitiko $4): $5',

# Import log
'importlogpage'                    => 'Protokolo de importoj',
'importlogpagetext'                => 'Administrantecaj importoj de paĝoj kun redakto-historio de aliaj vikioj.',
'import-logentry-upload'           => 'importita [[$1]] de dosiera alŝuto',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revizio|revizioj}}',
'import-logentry-interwiki'        => 'transvikiigita $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revizio|revizioj}} de $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mia uzantopaĝo',
'tooltip-pt-anonuserpage'         => 'La uzantopaĝo por la IP adreso sub kiu vi estas redaktanta',
'tooltip-pt-mytalk'               => 'Mia diskutpaĝo',
'tooltip-pt-anontalk'             => 'Diskuto pri redaktoj sub tiu ĉi IP adreso',
'tooltip-pt-preferences'          => 'Miaj preferoj',
'tooltip-pt-watchlist'            => 'Listo de paĝoj kies ŝanĝojn vi priatentas.',
'tooltip-pt-mycontris'            => 'Listo de miaj kontribuoj',
'tooltip-pt-login'                => 'Vi estas invitita ensaluti, tamen ne estas devige.',
'tooltip-pt-anonlogin'            => 'Vi estas invitita ensaluti, tamen ne estas devige.',
'tooltip-pt-logout'               => 'Elsaluti',
'tooltip-ca-talk'                 => 'Diskuto pri la artikolo',
'tooltip-ca-edit'                 => 'Vi povas redakti tiun ĉi paĝon. Bv uzi la antaŭvidbutonon antaŭ ol konservi.',
'tooltip-ca-addsection'           => 'Aldoni komenton al ĉi tiu diskuto.',
'tooltip-ca-viewsource'           => 'Tiu paĝo estas protektita. Vi povas nur rigardi ties fonton.',
'tooltip-ca-history'              => 'Antaŭaj versioj de tiu ĉi paĝo.',
'tooltip-ca-protect'              => 'Protekti tiun ĉi paĝon',
'tooltip-ca-delete'               => 'Forigi tiun ĉi paĝon',
'tooltip-ca-undelete'             => 'Restarigu la redaktojn faritajn al tiu ĉi paĝo antaŭ ties forigo',
'tooltip-ca-move'                 => 'Alinomigi tiun ĉi paĝon',
'tooltip-ca-watch'                => 'Aldoni tiun ĉi paĝon al via atentaro',
'tooltip-ca-unwatch'              => 'Forigi tiun ĉi paĝon el via atentaro',
'tooltip-search'                  => 'Traserĉu ĉi tiun vikion',
'tooltip-search-go'               => 'Iru al paĝo kun ĉi preciza nomo se ĝi ekzistas',
'tooltip-search-fulltext'         => 'Serĉu la paĝojn por ĉi teksto',
'tooltip-p-logo'                  => 'Ĉefpaĝo',
'tooltip-n-mainpage'              => 'Vizitu la Ĉefpaĝon',
'tooltip-n-portal'                => 'Pri la projekto, kion vi povas fari, kie vi povas trovi ion',
'tooltip-n-currentevents'         => 'Trovu fonajn informojn pri nunaj eventoj',
'tooltip-n-recentchanges'         => 'Listo de la lastaj ŝanĝoj en la vikio.',
'tooltip-n-randompage'            => 'Vidu hazardan paĝon',
'tooltip-n-help'                  => 'Serĉopaĝo.',
'tooltip-n-sitesupport'           => 'Subtenu nin per mono',
'tooltip-t-whatlinkshere'         => 'Listo de ĉiuj vikiaj paĝoj kij ligas ĉi tien',
'tooltip-t-recentchangeslinked'   => 'Lastaj ŝanĝoj en paĝoj kiuj ligas al tiu ĉi paĝo',
'tooltip-feed-rss'                => 'RSS-fonto por tiu ĉi paĝo',
'tooltip-feed-atom'               => 'Atom-fonto por ĉi paĝo',
'tooltip-t-contributions'         => 'Rigardi la liston de kontribuoj de tiu ĉi uzanto',
'tooltip-t-emailuser'             => 'Sendi retmesaĝon al tiu ĉi uzanto',
'tooltip-t-upload'                => 'Alŝuti bildojn aŭ dosierojn',
'tooltip-t-specialpages'          => 'Listo de ĉiuj specialaj paĝoj',
'tooltip-t-print'                 => 'Printebla versio de ĉi paĝo',
'tooltip-t-permalink'             => 'Konstanta ligilo al ĉi versio de la paĝo',
'tooltip-ca-nstab-main'           => 'Vidu la artikolon',
'tooltip-ca-nstab-user'           => 'Vidu la personan paĝon de la uzanto',
'tooltip-ca-nstab-media'          => 'Vidu la paĝon de la dosiero',
'tooltip-ca-nstab-special'        => 'Estas speciala paĝo, vi ne rajtas redakti ĝin.',
'tooltip-ca-nstab-project'        => 'Vidu la paĝon de la projekto',
'tooltip-ca-nstab-image'          => 'Vidu la paĝon de la bildo',
'tooltip-ca-nstab-mediawiki'      => 'Vidu la sisteman mesaĝon',
'tooltip-ca-nstab-template'       => 'Rigardi la ŝablonon',
'tooltip-ca-nstab-help'           => 'Rigardi la helppaĝon',
'tooltip-ca-nstab-category'       => 'Vidu la paĝon de kategorioj',
'tooltip-minoredit'               => 'Marku tiun ŝanĝon kiel malgrava',
'tooltip-save'                    => 'Konservi viajn ŝanĝojn',
'tooltip-preview'                 => 'Antaŭrigardu viajn ŝanĝojn. Bonvolu uzi tion antaŭ ol konservi ilin!',
'tooltip-diff'                    => 'Montru la ŝanĝojn kiujn vi faris de la teksto.',
'tooltip-compareselectedversions' => 'Rigardi la malsamojn inter ambaŭ selektitaj versioj de ĉi tiu paĝo.',
'tooltip-watch'                   => 'Aldoni ĉi paĝon al via atentaro',
'tooltip-recreate'                => 'Rekreu la paĝon malgraŭ ĝi estis forigita',
'tooltip-upload'                  => 'Ekalŝutu',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadatumo estas malebligita por ĉi servilo.',
'nocreativecommons' => 'Kreiva Komunejo RDF metadatumo estas malebligita por ĉi servilo.',
'notacceptable'     => 'La viki-servilo ne povas doni datumon en formato kiun via kliento povas legi.',

# Attribution
'anonymous'        => 'Anonima(j) uzanto(j) de {{SITENAME}}',
'siteuser'         => '{{SITENAME}} uzanto $1',
'lastmodifiedatby' => 'Ĉi paĝo estis laste ŝanĝita je $2, $1 de $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Bazita sur la laboro de $1.',
'others'           => 'aliaj',
'siteusers'        => '{{SITENAME}} uzanto(j) $1',
'creditspage'      => 'Atribuoj de paĝo',
'nocredits'        => 'Ne estas informo pri atribuoj por ĉi paĝo.',

# Spam protection
'spamprotectiontitle' => 'Filtrilo kontraŭ spamo',
'spamprotectiontext'  => 'La paĝo kiun vi trovis konservi estis blokita per la spam-filtrilo. Ĉi tia eraro estas kaŭzata pro ekstera ligilo al malpermesata retejo.',
'spamprotectionmatch' => 'La jena teksto ekagigis la spam-filtrilon: $1',
'spambot_username'    => 'Trudmesaĝa forigo de MediaWiki',
'spam_reverting'      => 'Restarigo de lasta versio ne entenante ligilojn al $1',
'spam_blanking'       => 'Forviŝo de ĉiuj versioj entenate ligilojn al $1',

# Info page
'infosubtitle'   => 'Informoj por paĝo',
'numedits'       => 'Nombro de redaktoj (paĝo): $1',
'numtalkedits'   => 'Nombro de redaktoj (diskuto-paĝo): $1',
'numwatchers'    => 'Nombro de atentantoj: $1',
'numauthors'     => 'Nombro de apartaj aŭtoroj (paĝo): $1',
'numtalkauthors' => 'Nombro de apartaj aŭtoroj (diskuto-paĝo): $1',

# Math options
'mw_math_png'    => 'Ĉiam krei PNG-bildon',
'mw_math_simple' => 'HTMLigu se simple, aŭ PNG',
'mw_math_html'   => 'HTMLigu se eble, aŭ PNG',
'mw_math_source' => 'Lasu TeX-fonton (por tekstfoliumiloj)',
'mw_math_modern' => 'Rekomendita por modernaj foliumiloj',
'mw_math_mathml' => 'MathML seeble (provizora)',

# Patrolling
'markaspatrolleddiff'                 => 'Marku kiel patrolita',
'markaspatrolledtext'                 => 'Marku ĉi artikolon patrolita',
'markedaspatrolled'                   => 'Markita kiel patrolita',
'markedaspatrolledtext'               => 'La elektita versio estas markita kiel patrolita.',
'rcpatroldisabled'                    => 'Patrolado de lastaj ŝanĝoj malaktivigita',
'rcpatroldisabledtext'                => 'La funkcio patrolado de la lastaj ŝanĝoj estas nun malaktivigita.',
'markedaspatrollederror'              => 'Ne povas marki kiel patrolitan',
'markedaspatrollederrortext'          => 'Vi devas specifi revizion por marki kiel patrolitan.',
'markedaspatrollederror-noautopatrol' => 'Vi ne estas permesita marki viajn proprajn ŝanĝojn kiel patrolitajn.',

# Patrol log
'patrol-log-page' => 'Loglibro pri patrolado',
'patrol-log-line' => 'markis $1 el $2 patrolitajn $3',
'patrol-log-auto' => '(aŭtomata)',

# Image deletion
'deletedrevision'                 => 'Forigita malnova versio $1',
'filedeleteerror-short'           => 'Eraro dum forigo de dosiero: $1',
'filedeleteerror-long'            => 'Eraroj renkontritaj kiam forigante la dosieron:

$1',
'filedelete-missing'              => 'La dosiero "$1" ne estas forigebla, ĉar ĝi ne ekzistas.',
'filedelete-old-unregistered'     => 'La donita dosier-revizio "$1" ne estas en la datumbazo.',
'filedelete-current-unregistered' => 'La entajpita dosiero "$1" ne estas en la datumbazo.',
'filedelete-archive-read-only'    => 'La arkiva dosierujo "$1" ne estas skribebla de la retservilo.',

# Browsing diffs
'previousdiff' => '← Iru al antaŭa ŝanĝo',
'nextdiff'     => 'Iri al sekvanta ŝanĝo →',

# Media information
'mediawarning'         => "'''Warning''': This file may contain malicious code, by executing it your system may be compromised.
<hr />",
'imagemaxsize'         => 'Elmontru bildojn en bildpriskribaj paĝoj je maksimume :',
'thumbsize'            => 'Grandeco de bildetoj:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|paĝo|paĝoj}}',
'file-info'            => '(pezo de dosiero: $1, MIME-tipo: $2)',
'file-info-size'       => '($1 × $2 rastrumeroj, dosiera grandeco: $3, MIME-tipo: $4)',
'file-nohires'         => '<small>Nenia pli granda distingivo havebla.</small>',
'svg-long-desc'        => '(SVG-dosiero, $1 × $2 rastrumeroj, grandeco de dosiero: $3)',
'show-big-image'       => 'Plena distingivo',
'show-big-image-thumb' => '<small>Grandeco de ĉi antaŭvido: $1 × $2 rastrumeroj</small>',

# Special:Newimages
'newimages'             => 'Aro da novaj bildoj',
'imagelisttext'         => "Jen listo de '''$1''' {{PLURAL:$1|dosiero|dosieroj}}, ordigitaj laŭ $2.",
'newimages-summary'     => 'Ĉi tiu paĝo montras la lastajn alŝutitajn dosierojn',
'showhidebots'          => '($1 robotojn)',
'noimages'              => 'Nenio videbla.',
'ilsubmit'              => 'Serĉi',
'bydate'                => 'dato',
'sp-newimages-showfrom' => 'Montru novajn dosierojn komencante de $2, $1',

# Bad image list
'bad_image_list' => 'La formato estas jen:

Nur listeroj (kun linio komence de steleto *) estas konsiderata.
La komenca ligilo de linio devas esti ligilo al malbona bildo.
Sekvaj ligilo en la sama linio estas konsiderata kiel esceptoj (paĝoj kiel la bildo estas permesita esti montrata.)',

# Metadata
'metadata'          => 'Metadatumo',
'metadata-help'     => 'Ĉi dosiero enhavas plian informon, verŝajne aldonita de la cifereca fotilo aux skanilo uzata krei aux skani ĝin. Se la dosiero estis ŝanĝita de ties originala stato, iuj detaloj eble ne estas tute estos sama kiel la modifita bildo.',
'metadata-expand'   => 'Montri etendajn detalojn',
'metadata-collapse' => 'Kaŝi etendajn detalojn',
'metadata-fields'   => 'La jenaj EXIF-metadatumaj kampoj estos inkluzivitaj en bildo-paĝoj kiam la metadatuma tabelo estas disfaldigita. Aliaj estos kaŝita defaŭlte.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

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
'exif-jpeginterchangeformat'       => 'Flankigu al JPEG SOI',
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
'exif-pixelxdimension'             => 'Valind image height',
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

'exif-orientation-1' => 'Normala', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Spegulumita horizontale', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotaciigita 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Spegulumita vertikale', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Turnita 90° maldekstre kaj spegulita vertikale', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Turnita 90° dekstre', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Turnita 90° dekstre kaj spegulita vertikale', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Turnita 90° maldekstre', # 0th row: left; 0th column: bottom

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
'exif-meteringmode-3'   => 'Eksponometro selekt-angula',
'exif-meteringmode-4'   => 'eksponometro mult-selekt-angula',
'exif-meteringmode-5'   => 'Skemo',
'exif-meteringmode-6'   => 'Parta',
'exif-meteringmode-255' => 'Alia',

'exif-lightsource-0'   => 'Nesciata',
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

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometroj por horo',
'exif-gpsspeed-m' => 'Mejloj por horo',
'exif-gpsspeed-n' => 'Knotoj',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Vera direkto',
'exif-gpsdirection-m' => 'Magneta direkto',

# External editor support
'edit-externally'      => 'Ŝanĝu ĉi dosieron per ekstera softvaro',
'edit-externally-help' => "Vidu la [http://meta.wikimedia.org/wiki/Help:External_editors instalinstrukciojn] por pliaj informoj ''(angle)''.",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ĉiuj',
'imagelistall'     => 'ĉiuj',
'watchlistall2'    => 'ĉiuj',
'namespacesall'    => 'ĉiuj',
'monthsall'        => 'ĉiuj',

# E-mail address confirmation
'confirmemail'             => 'Konfirmu retpoŝtadreson',
'confirmemail_noemail'     => 'Vi ne havas validan retpoŝtan adreson notitan en viaj [[Special:Preferences|Preferoj]].',
'confirmemail_text'        => 'Ĉi tiu vikio postulas ke vi validigu vian retadreson antaŭ ol uzadi la retmesaĝpreferojn. Bonvolu alklaki la suban butonon por sendi konfirmesaĝon al via adreso. La mesaĝo entenos ligilon kun kodo; bonvolu alŝuti la ligilon en vian foliumilon por konfirmi ke via retadreso validas.',
'confirmemail_pending'     => '<div class="error">Konfirma kodo estis jam repoŝtis al vi; se vi lastatempe kreis vian konton, vi eble volus atenti kelkajn minutojn por ĝi aliĝi antaŭ vi petus novan kodon.</div>',
'confirmemail_send'        => 'Retmesaĝi konfirmkodon',
'confirmemail_sent'        => 'Konfirma retmesaĝo estas sendita.',
'confirmemail_oncreate'    => 'Konfirma kodo estis sendita al via retpoŝta adreso.
Ĉi kodo ne estas bezonata ensaluti, sed vi bezonos doni ĝin antaŭ uzante iujn ajn retpoŝt-bazitajn ecojn de la vikio.',
'confirmemail_sendfailed'  => 'Ne eblis sendi konfirmretmesaĝon. Bonvolu kontroli ĉu en la adreso ne estus nevalidaj karaktroj.

Retpoŝta programo sciigis: $1',
'confirmemail_invalid'     => 'Nevalida konfirmkodo. La kodo eble ne plu validas.',
'confirmemail_needlogin'   => 'Vi devas $1 por konfirmi vian retpoŝtan adreson.',
'confirmemail_success'     => 'Via retadreso estas konfirmita. Vi povas nun ensaluti kaj ĝui la vikion.',
'confirmemail_loggedin'    => 'Via retadreso estas nun konfirmita.',
'confirmemail_error'       => 'Io misokazis dum konservo de via konfirmo.',
'confirmemail_subject'     => '{{SITENAME}} konfirmado de retadreso',
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
'scarytranscludefailed'   => '[Bedaŭrinde, akiro de ŝablono $1 malsukcesis.]',
'scarytranscludetoolong'  => '[Bedaŭrinde la URL estas tro longa]',

# Trackbacks
'trackbackbox'      => "<div id='mw_trackbacks'>
Postspuroj por ĉi artikolo:<br />p
$1
</div>",
'trackbackremove'   => ' ([$1 Forigu])',
'trackbacklink'     => 'Postspurado',
'trackbackdeleteok' => 'La postspurado esti sukcese forigita.',

# Delete conflict
'deletedwhileediting' => 'Averto: Oni forigis ĉi tiun paĝon post tiam, kiam vi ekredaktis ĝin!',
'confirmrecreate'     => "Uzanto [[User:$1|$1]] ([[User talk:$1|diskuto]]) forigis ĉi paĝon post vi ekredaktis ĝin kun kialo:
: ''$2''
Bonvolu konfirmi ke vi ja volas rekrei la paĝon.",
'recreate'            => 'Rekreu',

# HTML dump
'redirectingto' => 'Redirektante al [[$1]]...',

# action=purge
'confirm_purge'        => 'Ĉu forviŝiĝu la enhavo de tiu ĉi paĝo?

$1',
'confirm_purge_button' => 'Ek!',

# AJAX search
'searchcontaining' => "Serĉu paĝojn enhavantajn ''$1''.",
'searchnamed'      => "Serĉu paĝojn nomatajn ''$1''.",
'articletitles'    => "Artikoloj komencante de ''$1''",
'hideresults'      => 'Kaŝu rezultojn',
'useajaxsearch'    => 'Uzu AJAX serĉon',

# Multipage image navigation
'imgmultipageprev' => '← antaŭa paĝo',
'imgmultipagenext' => 'sekva paĝo →',
'imgmultigo'       => 'Ek!',
'imgmultigoto'     => 'Iru al paĝo $1',

# Table pager
'ascending_abbrev'         => 'sprn',
'descending_abbrev'        => 'subn',
'table_pager_next'         => 'Sekva paĝo',
'table_pager_prev'         => 'Antaŭa paĝo',
'table_pager_first'        => 'Unua paĝo',
'table_pager_last'         => 'Lasta paĝo',
'table_pager_limit'        => 'Montru $1 aĵojn por paĝo',
'table_pager_limit_submit' => 'Ek',
'table_pager_empty'        => 'Neniaj rezultoj',

# Auto-summaries
'autosumm-blank'   => 'Forviŝantaj ĉion el paĝo',
'autosumm-replace' => "Anstataŭigante paĝojn kun '$1'",
'autoredircomment' => 'Redirektante al [[$1]]',
'autosumm-new'     => 'Nova paĝo: $1',

# Live preview
'livepreview-loading' => 'Ŝargiĝadas…',
'livepreview-ready'   => 'Ŝargiĝadas… Preta!',
'livepreview-failed'  => 'Aktiva antaŭvido malsukcesis! Provu normalan antaŭvidon.',
'livepreview-error'   => 'Malsukcesis konekti: $1 "$2". Provu norman antaŭvidon.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ŝanĝoj pli novaj ol $1 sekundoj eble ne estos montrataj en ĉi listo.',
'lag-warn-high'   => 'Pro malrapideco de la servila datumbazo, ŝanĝoj pli novaj ol $1 sekundoj eble ne montriĝos en ĉi listo.',

# Watchlist editor
'watchlistedit-numitems'       => 'Via atentaro enhavas {{PLURAL:$1|1 titolon|$1 titolojn}}, escepte de diskuto-paĝoj.',
'watchlistedit-noitems'        => 'Via atentaro enhavas neniujn titolojn.',
'watchlistedit-normal-title'   => 'Redaktu atentaron',
'watchlistedit-normal-legend'  => 'Forigu titolojn de atentaro',
'watchlistedit-normal-explain' => 'Titoloj de via atentaro estas montrata sube.
Forigi titolon, marku la skatoleto apude de ĝi, kaj klaku Forigu Titolojn.
Vi ankaŭ povas [[Special:Watchlist/raw|redakti la krudan liston]].',
'watchlistedit-normal-submit'  => 'Forigu titolojn',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titolo estis forigita|$1 titoloj estis forigitaj}} de via atentaro:',
'watchlistedit-raw-title'      => 'Redaktu krudan atentaron',
'watchlistedit-raw-legend'     => 'Redaktu krudan atentaron',
'watchlistedit-raw-explain'    => 'Titoloj en via atentaro estas montrata sube, kaj povas esti redaktita de aldono aŭ forigo de la listo: unu titolo por linio. Kiam finite, klaku Ĝisdatigu Atentaron.
Vi povas ankaŭ [[Special:Watchlist/edit|uzu la norman redaktilon]].',
'watchlistedit-raw-titles'     => 'Titoloj:',
'watchlistedit-raw-submit'     => 'Ĝisdatigu atentaron',
'watchlistedit-raw-done'       => 'Via atentaro estas ĝisdatigita.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titolo estis aldonita|$1 titoloj estis aldonitaj}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titolo estis forigita|$1 titoloj estis forigitaj}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Montri koncernajn ŝanĝojn',
'watchlisttools-edit' => 'Rigardi kaj redakti atentaron',
'watchlisttools-raw'  => 'Redakti krudan atentaron',

# Core parser functions
'unknown_extension_tag' => 'Nekonata etend-etikedo "$1"',

# Special:Version
'version'                          => 'Versio', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Instalitaj etendiloj',
'version-specialpages'             => 'Specialaj paĝoj',
'version-parserhooks'              => 'Sintaksaj hokoj',
'version-variables'                => 'Variabloj',
'version-other'                    => 'Alia',
'version-mediahandlers'            => 'Mediaj traktiloj',
'version-hooks'                    => 'Hokoj',
'version-extension-functions'      => 'Etendiloj',
'version-parser-extensiontags'     => 'Sintaksaj etend-etikedoj',
'version-parser-function-hooks'    => 'Hokoj de sintaksaj funkcioj',
'version-skin-extension-functions' => 'Etendaj funkcioj pri grafika etoso',
'version-hook-name'                => 'Nomo de hoko',
'version-hook-subscribedby'        => 'Abonita de',
'version-version'                  => 'Versio',
'version-license'                  => 'Permesilo',
'version-software'                 => 'Instalita programaro',
'version-software-product'         => 'Produkto',
'version-software-version'         => 'Versio',

# Special:Filepath
'filepath'         => 'Dosiera pado',
'filepath-page'    => 'Dosiero:',
'filepath-submit'  => 'Pado',
'filepath-summary' => 'Ĉi tiu speciala paĝo redonas la kompletan padon por dosiero. Bildoj estas montrataj en alta distingivo, aliaj dosieraj tipoj estas rekte startataj per ties asociita programo.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Serĉu duplikatajn dosierojn',
'fileduplicatesearch-summary'  => 'Serĉu duplikatajn dosierojn bazite de haketvaluto.

Enigu la dosiernomon sen la "{{ns:image}}:" prefikso.',
'fileduplicatesearch-legend'   => 'Serĉu duplikaton',
'fileduplicatesearch-filename' => 'Dosiernomo:',
'fileduplicatesearch-submit'   => 'Serĉi',
'fileduplicatesearch-info'     => '$1 × $2 rastrumero<br />Dosiera pezo: $3<br />MIME-tipo: $4',
'fileduplicatesearch-result-1' => 'La dosiero "$1" ne havas identan duplikaton.',
'fileduplicatesearch-result-n' => 'La dosiero "$1" havas {{PLURAL:$2|1 identan duplikaton|$2 identajn duplikatojn}}.',

# Special:SpecialPages
'specialpages-group-maintenance' => 'Raportoj pri prizorgado',
'specialpages-group-other'       => 'Aliaj specialaj paĝoj',
'specialpages-group-login'       => 'Ensalutu / Kreu novan konton',
'specialpages-group-changes'     => 'Lastaj ŝanĝoj kaj loglibroj',
'specialpages-group-media'       => 'Gazetaj raportoj kaj alŝutoj',
'specialpages-group-users'       => 'Uzantoj kaj rajtoj',
'specialpages-group-needy'       => 'Paĝoj bezonantaj laboron',
'specialpages-group-highuse'     => 'Plej uzitaj paĝoj',

);
