<?php
/** Esperanto (Esperanto)
 *
 * @addtogroup Language
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
'tog-underline'               => 'Substreku ligilojn',
'tog-highlightbroken'         => 'Ruĝigu ligilojn al neekzistantaj paĝoj',
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
'tog-rememberpassword'        => 'Rememoru mian pasvorton',
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

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategorio|Kategorioj}}',
'pagecategories'        => '{{PLURAL:$1|Kategorio|Kategorioj}}',
'category_header'       => 'Artikoloj en kategorio "$1"',
'subcategories'         => 'Subkategorioj',
'category-media-header' => 'Dosieroj en kategorio "$1"',
'category-empty'        => "''Ĉi tiu kategorio momente ne enhavas artikolojn aŭ mediojn.''",

'mainpagetext'      => 'Vikisoftvaro sukcese instaliĝis.',
'mainpagedocfooter' => "Konsultu la [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] por informo pri uzado de vikia programaro.

==Kiel komenci==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Listo de konfigurajxoj] (angla)
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Oftaj Demandoj] (angla)
* [http://lists.wikipedia.org/mailman/listinfo/mediawiki-announce MediaWiki dissendolisto pri anoncoj] (angla)",

'about'          => 'Enkonduko',
'article'        => 'Artikolo',
'newwindow'      => '(en nova fenestro)',
'cancel'         => 'Nuligu',
'qbfind'         => 'Trovu',
'qbbrowse'       => 'Foliumado',
'qbedit'         => 'redaktu',
'qbpageoptions'  => 'Paĝagado',
'qbpageinfo'     => 'Paĝinformoj',
'qbmyoptions'    => 'Personaĵoj',
'qbspecialpages' => 'Specialaj paĝoj',
'moredotdotdot'  => 'Pli...',
'mypage'         => 'Mia paĝo',
'mytalk'         => 'Mia diskuto',
'anontalk'       => 'Diskutpaĝo por tiu ĉi IP',
'navigation'     => 'Navigado',

# Metadata in edit box
'metadata_help' => 'Metadatumoj:',

'errorpagetitle'    => 'Eraro',
'returnto'          => 'Revenu al $1.',
'tagline'           => 'El {{SITENAME}}',
'help'              => 'Helpo',
'search'            => 'Serĉu',
'searchbutton'      => 'Serĉu',
'go'                => 'Ek!',
'searcharticle'     => 'Ek!',
'history'           => 'Historio de versioj',
'history_short'     => 'Historio',
'updatedmarker'     => 'ĝisdatita de post mia lasta vizito',
'info_short'        => 'Informo',
'printableversion'  => 'Presebla versio',
'permalink'         => 'Konstanta ligilo',
'print'             => 'Printu',
'edit'              => 'Redaktu',
'editthispage'      => 'Redaktu la paĝon',
'delete'            => 'Forigu',
'deletethispage'    => 'Forigu la paĝon',
'undelete_short'    => 'Malforigu {{PLURAL:$1|redakton|$1 redaktojn}}',
'protect'           => 'Protektu',
'protect_change'    => 'ŝanĝu protekton',
'protectthispage'   => 'Protektu la paĝon',
'unprotect'         => 'Malprotektu',
'unprotectthispage' => 'Malprotektu la paĝon',
'newpage'           => 'Nova paĝo',
'talkpage'          => 'Diskutu la paĝon',
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
'redirectpagesub'   => 'Redirekta paĝo',
'lastmodifiedat'    => 'Laste redaktita je $2, $1.', # $1 date, $2 time
'viewcount'         => 'Montrita {{PLURAL:$1|unufoje|$1 fojojn}}.',
'protectedpage'     => 'Protektita paĝo',
'jumpto'            => 'Saltu al:',
'jumptonavigation'  => 'navigado',
'jumptosearch'      => 'serĉo',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Pri {{SITENAME}}',
'aboutpage'         => 'Project:Enkonduko',
'bugreports'        => 'Raportu cimojn',
'bugreportspage'    => 'Project:Raportu cimojn',
'copyright'         => 'La enhavo estas havebla sub $1.',
'copyrightpagename' => '{{SITENAME}}-kopirajto',
'copyrightpage'     => '{{ns:project}}:Kopirajto',
'currentevents'     => 'Aktualaĵoj',
'currentevents-url' => 'Project:Aktualaĵoj',
'disclaimers'       => 'Malgarantio',
'disclaimerpage'    => 'Project:Malgarantia paĝo',
'edithelp'          => 'Helpo pri redaktado',
'edithelppage'      => 'Help:Kiel redakti paĝon',
'faq'               => 'Oftaj demandoj',
'faqpage'           => 'Project:Oftaj demandoj',
'helppage'          => 'Help:Enhavo',
'mainpage'          => 'Ĉefpaĝo',
'policy-url'        => 'Project:Konsiletoj',
'portal'            => 'Komunuma portalo',
'portal-url'        => 'Project:Komunuma portalo',
'privacy'           => 'Regularo pri respekto de la privateco',
'privacypage'       => 'Project:Respekto de la privateco',
'sitesupport'       => 'Subteno',
'sitesupport-url'   => 'Project:Subteno',

'badaccess'        => 'Vi ne havas sufiĉe da redaktorajtoj por tiu paĝo.',
'badaccess-group0' => 'Vi ne havas permeson plenumi la agon, kiun vi petis.',
'badaccess-group1' => 'La ago, kiun vi petis, estas limigita al uzuloj en la grupo $1.',
'badaccess-group2' => 'La ago, kiun vi petis, estas limigita al uzuloj en unu el la grupoj $1.',
'badaccess-groups' => 'La ago, kiun vi petis, estas limigita al uzuloj en unu el la grupoj $1.',

'versionrequired'     => 'Versio $1 de MediaWiki nepras',
'versionrequiredtext' => 'La versio $1 de MediaWiki estas necesa por uzi ĉi tiun paĝon. Vidu [[Special:Version]]',

'ok'                      => 'Ek!',
'retrievedfrom'           => 'Elŝutita el  "$1"',
'youhavenewmessages'      => 'Por vi estas $1 ($2).',
'newmessageslink'         => 'nova mesaĝo',
'newmessagesdifflink'     => 'ŝanĝoj kompare kun antaŭlasta versio',
'youhavenewmessagesmulti' => 'Vi havas novajn mesaĝojn ĉe $1',
'editsection'             => '<small>redaktu</small>',
'editold'                 => 'redaktu',
'editsectionhint'         => 'Redaktu sekcion: $1',
'toc'                     => 'Enhavo',
'showtoc'                 => 'montru',
'hidetoc'                 => 'kaŝu',
'thisisdeleted'           => 'Vidu aŭ restarigu $1?',
'viewdeleted'             => 'Rigardu $1?',
'restorelink'             => '{{PLURAL:$1|unu forigitan version|$1 forigitajn versiojn}}',
'feedlinks'               => 'RSS-abonilo:',
'feed-invalid'            => 'Ia nevalida abonilo.',
'site-rss-feed'           => '$1 RSS-abonilo.',
'site-atom-feed'          => '$1 Atom-fonto',
'page-rss-feed'           => '"$1" RSS-abonilo',
'page-atom-feed'          => '"$1" Atom-abonilo',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikolo',
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
'readonly_lag'         => 'La datumbazo estis auxtomate sxlosita dum la subdatumbazo atingas la cxefan datumbazon.',
'internalerror'        => 'Interna eraro',
'internalerror_info'   => 'Interna eraro: $1',
'filecopyerror'        => 'Neeblis kopii dosieron  "$1" al "$2".',
'filerenameerror'      => 'Neeblis alinomi dosieron "$1" al "$2".',
'filedeleteerror'      => 'Neeblis forigi dosieron "$1".',
'directorycreateerror' => 'Ne povis krei dosierujon "$1".',
'filenotfound'         => 'Neeblis trovi dosieron "$1".',
'fileexistserror'      => 'Ne eblas skribi en la dosieron "$1": dosiero ekzistas',
'unexpected'           => 'Neatendita valuto: "$1"="$2".',
'formerror'            => 'Eraro: neeblis liveri formulon',
'badarticleerror'      => 'Tiun ĉi agon oni ne povas apliki al tiu ĉi artikolo.',
'cannotdelete'         => 'Neeblis forigi la elektitan paĝon aŭ dosieron.',
'badtitle'             => 'Nevalida titolo',
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
'viewsource'           => 'Vidu vikitekston',
'viewsourcefor'        => 'por $1',
'actionthrottled'      => 'Agado limigita',
'actionthrottledtext'  => 'Por kontrauxigi spamon, vi estas limigita farante cxi tiun agon tro pluroble en mallonga tempdauxro, kaj vi plialtigis cxi limon. Bonvolu refaru post kelkaj minutoj.',
'protectedpagetext'    => 'Tiu ĉi paĝo estas ŝlosita por malebligi redaktadon.',
'viewsourcetext'       => 'Vi povas rigardi kaj kopii la fonton de la paĝo:',
'protectedinterface'   => 'Ĉi tiu paĝo provizas interfacan tekston por la softvaro, kaj estas ŝlosita por malabeligi misuzon.',
'editinginterface'     => "'''Atentu:''' Vi redaktas paĝon, kiu estas uzata kiel interfaca teksto por la softvaro. Ŝanĝoj de tiu ĉi teksto povas ŝanĝi aspekton de la interfaco por aliaj uzantoj.",
'sqlhidden'            => '(SQL serĉomendo kasxita)',
'cascadeprotected'     => 'Ĉi tiu paĝo estas protektita kontraŭ redaktado, ĉar ĝi estas inkludita en la {{PLURAL:$1|sekvan paĝon, kiu|sekvajn paĝojn, kiuj}} estas {{PLURAL:$1|protektata|protektataj}} kun la "kaskada" opcio turnita sur:
$2',
'namespaceprotected'   => "Vi ne rajtas redakti paĝojn en la '''$1''' nomspaco.",
'customcssjsprotected' => 'Vi ne rajtas redakti ĉi tiun paĝon, ĉar ĝi enhavas personajn alĝustigojn de alia uzanto.',
'ns-specialprotected'  => 'Paĝoj en la {{ns:special}} nomspaco ne povas esti redaktataj.',
'titleprotected'       => 'Cxi titolo estas protektita de kreado de [[User:$1|$1]]. La kialo donata estis <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Elsalutu!',
'logouttext'                 => '<strong>Vi elsalutis kaj finis vian seancon.</strong><br />
Vi rajtas daŭre vikiumi sennome, aŭ vi povas reensaluti kiel la sama aŭ kiel alia uzanto.',
'welcomecreation'            => '== Bonvenon, $1! ==

Via konto estas kreita. <span style="color:#ff0000">Ne forgesu fari viajn [[special:Preferences|{{SITENAME}}-preferojn]]!</span>',
'loginpagetitle'             => 'Ensalutu / enskribu',
'yourname'                   => 'Via salutnomo',
'yourpassword'               => 'Via pasvorto',
'yourpasswordagain'          => 'Retajpu pasvorton',
'remembermypassword'         => 'Rememoru mian pasvorton',
'yourdomainname'             => 'Via domajno',
'externaldberror'            => 'Aŭ estis datenbaza eraro rilate al ekstera aŭtentikigado, aŭ vi ne permesas ĝisdatigi vian eksteran konton.',
'loginproblem'               => '<b>Okazis problemo dum via ensalutado.</b><br />Bonvolu reprovi!',
'login'                      => 'Ensalutu',
'loginprompt'                => 'Necesas ke via foliumilo permesu kuketojn por ensaluti en la {{SITENAME}}.',
'userlogin'                  => 'Ensalutu / Kreu novan konton',
'logout'                     => 'Elsalutu',
'userlogout'                 => 'Elsalutu',
'notloggedin'                => 'Ne ensalutinta',
'nologin'                    => 'Ĉu vi ne jam havas salutnomon? $1.',
'nologinlink'                => 'Kreu konton',
'createaccount'              => 'Kreu novan konton',
'gotaccount'                 => 'Ĉu vi jam havas konton? $1.',
'gotaccountlink'             => 'Ensalutu',
'createaccountmail'          => 'retpoŝte',
'badretype'                  => 'La pasvortojn kiujn vi tajpis ne egalas.',
'userexists'                 => 'Jam estas uzanto kun la nomo kiun vi elektis. Bonvolu elekti alian nomon.',
'youremail'                  => 'Via retpoŝtadreso',
'username'                   => 'Salutnomo:',
'uid'                        => 'Uzantnumero:',
'yourrealname'               => 'Vera nomo¹',
'yourlanguage'               => 'Lingvo',
'yourvariant'                => 'Varianto',
'yournick'                   => 'Via kaŝnomo (por subskriboj)',
'badsig'                     => 'Via kaŝnomo (por subskriboj) malvalidas. Bv. kontroli la HTML-etikedojn!',
'badsiglength'               => 'Salutnomo estas tro longa; gxi nepre estas sub $1 signoj.',
'email'                      => 'Retpoŝto',
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
'nosuchusershort'            => 'Ne ekzistas uzanto kun la nomo "$1". Bonvolu kontroli vian ortografion.',
'nouserspecified'            => 'Vi devas entajpi uzantonomon.',
'wrongpassword'              => 'Vi tajpis malĝustan pasvorton. Bonvolu provi denove.',
'wrongpasswordempty'         => 'Vi tajpis malplenan pasvorton. Bonvolu provi denove.',
'passwordtooshort'           => 'Via pasvorto estas tro mallonga. Ĝi entenu minimume $1 karaktrojn.',
'mailmypassword'             => 'Retpoŝtu al mi novan pasvorton',
'passwordremindertitle'      => 'Rememorigo el {{SITENAME}} pri perdita pasvorto',
'passwordremindertext'       => 'Iu (probable vi, el IP-adreso $1)
petis, ke ni sendu al vi novan pasvorton por ensaluti {{SITENAME}}n ($4).
La pasvorto por uzanto "$2" nun estas "$3".
Ni rekomendas, ke vi nun ensalutu kaj ŝanĝu vian pasvorton.',
'noemail'                    => 'Retpoŝtadreso ne estas registrita por uzanto "$1".',
'passwordsent'               => 'Oni sendis novan pasvorton al la retpoŝtadreso
registrita por "$1".
Bonvolu saluti denove ricevinte ĝin.',
'blocked-mailpassword'       => 'Via IP adreso estas blokita de redaktado, kaj tial
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
'createaccount-text'         => 'Iu ($1) kreis konton por $2 en {{SITENAME}}
($4). La pasvorto por "$2" estas "$3". Vi ensalutu kaj sxangxu vian pasvorton nun.

Vi eblus ignori cxi mesagxon, se cxi konto estis kreita erare.',
'loginlanguagelabel'         => 'Lingvo: $1',

# Password reset dialog
'resetpass'               => 'Refaru konto-pasvorton',
'resetpass_announce'      => 'Vi ensalutis kun provizora retposxtita pasvorto. Por kompleti ensalutadon, vi devas fari novan pasvorton cxi tien:',
'resetpass_header'        => 'Refaru pasvorton.',
'resetpass_submit'        => 'Faru pasvorton kaj ensalutu',
'resetpass_success'       => 'Via pasvorto estis sukcese sxangxita! Nun ensalutanta vin...',
'resetpass_bad_temporary' => 'Nevalida provizora pasvorto. Vi versxajne jam sukcese sxangxis vian pasvorton aux petis novan provizoran pasvorton.',
'resetpass_forbidden'     => 'Ne eblas sxangxi pasvortojn cxe {{SITENAME}}',

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
'nowiki_sample'   => '	 Enmetu ne formatitan tekston ĉi tien',
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
'watchthis'                 => 'Atentadu la artikolon',
'savearticle'               => 'Konservu ŝanĝojn',
'preview'                   => 'Antaŭrigardo',
'showpreview'               => 'Antaŭrigardu',
'showlivepreview'           => 'Aktiva antauxvido',
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

Vi povas kontakti $1 aux iun ajn el la aliaj 
[[{{MediaWiki:grouppage-sysop}}|administrantojn]] por diskuti la blokon.

Notu, ke vi ne povas uzi la servon "Retposxtu cxi tiu uzanton" krom se vi havas validan retpost-adreson registritan en viaj [[Special:Preferences|vikipediistajn preferojn]], kaj vi estas ne blokita kontraux gxia uzado.

Via forbaro-identigo estas $5.  Bonvolu inkluzivi tiun identigon en iuj ajn demandoj de vi farotaj.',
'blockednoreason'           => 'Nenia kialo donata',
'blockedoriginalsource'     => "La fonto de '''$1''' estas 
montrata malsupre:",
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
'loginreqlink'              => 'Ensalutu',
'loginreqpagetext'          => 'Vi devas $1 por rigardi aliajn paĝojn.',
'accmailtitle'              => 'Pasvorto sendita.',
'accmailtext'               => "La pasvorto por '$1' estis sendita al  $2.",
'newarticle'                => '(Nova)',
'newarticletext'            => 'Vi sekvis ligilon al paĝo jam ne ekzistanta. Se vi volas krei ĝin, ektajpu sube (vidu la [[{{MediaWiki:Helppage}}|helpopaĝo]] por klarigoj.) Se vi malintence alvenis ĉi tien, simple alklaku la retrobutonon de via retumilo.',
'anontalkpagetext'          => "---- ''Jen diskutopaĝo por iu anonima kontribuanto kiu ne jam kreis konton aŭ ne uzas ĝin. Ni tial devas uzi la cifran IP-adreso por identigi lin. la sama IP-adreso povas estis samtempte uzata de pluraj uzantoj. Se vi estas anonimulo kaj preferus eviti tiajn mistrafajn komentojn kaj konfuziĝon kun aliaj anonimuloj de via retejo, bonvolu [[Special:Userlogin|krei konton aŭ ensaluti]].''",
'noarticletext'             => '(La paĝo nun estas malplena. Se vi ĵus kreis tiun ĉi paĝon klaku [{{fullurl:{{FULLPAGENAME}}|action=purge}} ĉi tien].)',
'userpage-userdoesnotexist' => 'Uzanto-konto "$1" ne estas registrita. Bonvolu konfirmi se vi volas krei/redakti cxi tiu pagxo.',
'clearyourcache'            => "'''Notu:''' Post konservado vi forviŝu la kaŝmemoron de via foliumilo por vidi la ŝanĝojn : '''Mozilo:''' alklaku ''Reŝarĝi'' (aŭ ''Stir-Shift-R''), '''IE / Opera:''' ''Stir-F5'', '''Safari:''' ''Cmd-R'', '''Konqueror''' ''Stir-R''.",
'usercssjsyoucanpreview'    => '<strong>Konsileto:</strong> Uzu la "Antaŭrigardan" butonon por provi vian novan css/js antaŭ konservi.',
'usercsspreview'            => '<strong>Memoru ke vi nur antaŭrigardas vian uzanto-CSS. Ĝi ne jam estas konservita!</strong>',
'userjspreview'             => "'''Memoru ke vi nun nur provas kaj antaŭrigardas vian uzantan javaskripton, ĝi ne estas jam konservita'''",
'userinvalidcssjstitle'     => "'''Averto:''' Ne ekzistas aspekto \"\$1\". Rememoru ke individuaj .css-aj kaj .js-aj paĝoj uzas minusklan titolon, ekz. {{ns:user}}:Foo/monobook.css kontraŭe  al {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Ŝanĝo registrita)',
'note'                      => '<strong>Noto:</strong>',
'previewnote'               => 'Memoru, ke ĉi tio estas nur antaŭrigardo kaj ankoraŭ ne konservita!',
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
'editinguser'               => 'Redaktante $1',
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
'copyrightwarning2'         => 'Bonvolu noti ke ĉiuj kontribuoj al {{SITENAME}} povas esti reredaktita, ŝanĝita aŭ forigita de aliaj kontribuantoj. Se vi ne deziras ke viaj verkoj estu senkompate reredaktitaj, ne publikigu ilin ĉi tie.

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
'titleprotectedwarning'     => '<strong>AVERTO: Cxi tiu pagxo estis sxlosita tial nur iuj uzantoj povas krei gxin.</strong>',
'templatesused'             => 'Ŝablonoj uzitaj sur ĉi paĝo:',
'templatesusedpreview'      => 'Ŝablonoj uzataj dum ĉi tiu antaŭrigardo:',
'templatesusedsection'      => 'Ŝablonoj uzataj en ĉi tiu sekcio:',
'template-protected'        => '(protektita)',
'template-semiprotected'    => '(duone protektita)',
'edittools'                 => '<!-- Teksto ĉi tie estas montrata sub redaktaj kaj alŝutaj formularoj. -->',
'nocreatetitle'             => 'Paĝa kreado estas limigita',
'nocreatetext'              => '{{SITENAME}} restriktas la eblecon krei novajn pagxojn. Vi eblas reiri kaj redakti faritan pagxon, aux [[Special:Userlogin|ensaluti aux krei konton]].',
'nocreate-loggedin'         => 'Vi ne rajtas krei novajn paĝojn en {{SITENAME}}.',
'permissionserrors'         => 'Eraroj pri permesoj',
'permissionserrorstext'     => 'Vi ne rajtas fari tion pro la {{PLURAL:$1|sekva kialo|sekvaj kialoj}}:',
'recreate-deleted-warn'     => "'''Averto: Vi rekreas pagxon tiu estis antauxe forigita.'''

Vi konsideru cxu konvenas dauxre redakti cxi pagxon. 
Jen la loglibro de forigoj por via oportuno:",

# "Undo" feature
'undo-success' => 'La redakto estas malfarebla. Bonvolu konfirmi la jenan komparajxon por verigi cxi tiel vi volas, kaj konservu la sxangxojn suben fini malfarante la redakton.',
'undo-failure' => 'Ne eblas nuligi redakton pro konfliktaj intermezaj redaktoj.',
'undo-summary' => 'Nuligis revizion $1 de [[{{ns:2}}:$2|$2]] ([[Speciala:Contributions/$2|kontribuoj]], [[{{ns:3}}:$2|diskutpagxo]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ne povas krei konton',
'cantcreateaccount-text' => "Konto-kreado de cxi tiu IP-adreso (<b>$1</b>) estis forbarita de [[User:$3|$3]].

La kialo donata de $3 estas ''$2''.",

# History pages
'viewpagelogs'        => 'Vidu la loglibrojn por tiu ĉi paĝo',
'nohistory'           => 'Ne ekzistas historio de redaktoj por ĉi tiu paĝo.',
'revnotfound'         => 'Ne ekzistas malnova versio de la artikolo',
'revnotfoundtext'     => 'Ne eblis trovi malnovan version de la artikolo kiun vi petis.
Bonvolu kontroli la retadreson (URL) kiun vi uzis por atingi la paĝon.\\b',
'loadhist'            => 'Ŝarĝas redaktohistorion',
'currentrev'          => 'Aktuala versio',
'revisionasof'        => 'Kiel registrite je $1',
'revision-info'       => 'Redakto de $1 de $2',
'previousrevision'    => '← Antaŭa versio',
'nextrevision'        => 'Sekva versio →',
'currentrevisionlink' => 'vidu nunan version',
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
'history-feed-item-nocomment' => '$1 cxe $2', # user at time
'history-feed-empty'          => 'La petata pagxo ne ekzistas.
Gxi versxajne estis forigita de la vikio, aux alinomita.
Provu [[Special:Search|sercxi en la vikio]] por rilataj novaj pagxoj.',

# Revision deletion
'rev-deleted-comment'         => '(komento nuligita)',
'rev-deleted-user'            => '(uzanto-nomo forigita)',
'rev-deleted-event'           => '(ero forigita)',
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
'revdelete-nooldid-title'     => 'Neniu cela revizio',
'revdelete-nooldid-text'      => 'Vi ne specifis celan revizion aux reviziojn fari cxi funkcion.',
'revdelete-selected'          => "{{PLURAL:$2|Elektata revizio|Elektataj revizioj}} de '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Selektata loglibra evento|Selektataj loglibraj eventoj}} por '''$1:'''",
'revdelete-text'              => 'Forigitaj versioj kaj eventoj plu aperos en la historipagxoj, sed iliaj tekstoj ne estos alireblaj de  la publiko. 

Aliaj administrantoj cxe {{SITENAME}} plu povos aliri la kasxitan entenon kaj restarigi gxin per la sama interfaco, krom se plia limigo estas metita de la pagxaradministrantoj.',
'revdelete-legend'            => 'Limigu ecojn de versio:',
'revdelete-hide-text'         => 'Kaŝu tekston de versio',
'revdelete-hide-name'         => 'Kaŝu agon kaj celon',
'revdelete-hide-comment'      => 'Kaŝu komenton de redakto',
'revdelete-hide-user'         => 'Kaŝu nomon aux IP-adreson de redaktinto',
'revdelete-hide-restricted'   => 'Apliku ĉi limigojn al administrantoj same kiel al aliaj uzantoj',
'revdelete-suppress'          => 'Subpremu datumojn de administrantoj aldone al de aliaj',
'revdelete-hide-image'        => 'Kaŝu dosier-enhavon',
'revdelete-unsuppress'        => 'Forigu limigojn al restarigitaj versioj',
'revdelete-log'               => 'Loglibra komento:',

# Diffs
'history-title'           => 'Redakto-historio de "$1"',
'difference'              => '(Malsamoj inter versioj)',
'lineno'                  => 'Linio $1:',
'compareselectedversions' => 'Komparu la selektitajn versiojn',
'editundo'                => 'malfaru',
'diff-multi'              => '({{PLURAL:$1|Unu meza versio|$1 mezaj versioj}} ne montrata.)',

# Search results
'searchresults'         => 'Serĉrezultoj',
'searchresulttext'      => 'Por pliaj informoj kiel priserĉi la {{SITENAME}}n, vidu [[{{MediaWiki:Helppage}}|serĉi en {{SITENAME}}]].',
'searchsubtitle'        => 'Serĉmendo "[[$1]]"',
'searchsubtitleinvalid' => 'Serĉmendo "$1"',
'noexactmatch'          => '<b>Ne estas paĝo titolita "$1".</b> Vi povas [[:$1|krei la paĝon]].',
'titlematches'          => 'Trovitaj laŭ titolo',
'notitlematches'        => 'Neniu trovita laŭ titolo',
'textmatches'           => 'Trovitaj laŭ enhavo',
'notextmatches'         => 'Neniu trovita laŭ enhavo',
'prevn'                 => '$1 antaŭajn',
'nextn'                 => '$1 sekvajn',
'viewprevnext'          => 'Montru ($1) ($2) ($3).',
'showingresults'        => "Montras {{PLURAL:$1|'''1''' trovitan|'''$1''' trovitajn}} ekde la #'''$2'''-a.",
'showingresultsnum'     => "Montras {{PLURAL:$3|'''1''' trovitan|'''$3''' trovitajn}} ekde la #'''$2'''-a.",
'nonefound'             => '<strong>Noto</strong>: malsukcesaj serĉoj ofte
okazas ĉar oni serĉas tro da ofte uzataj vortoj, kiujn ne enhavas la indekso,
aŭ ĉar oni petas tro da serĉvortoj (nur paĝoj kiuj enhavas ĉiun serĉvorton
montriĝos en la rezulto).',
'powersearch'           => 'Trovu',
'powersearchtext'       => '
Serĉu en sekcioj: :<br />
$1<br />
$2 Kun alidirektiloj   Serĉu $3 $9',
'searchdisabled'        => '<p>Oni provizore malŝaltis serĉadon per la plenteksta
indekso pro troŝarĝita servilo. Intertempe, vi povas serĉi per <i>guglo</i> aŭ per <i>jahu!</i>:</p>',

# Preferences page
'preferences'              => 'Preferoj',
'mypreferences'            => 'Miaj preferoj',
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
'prefs-personal'           => 'Uzantodatumoj',
'prefs-rc'                 => 'Lastaj ŝanĝoj kaj elmontro de stumpoj',
'prefs-watchlist'          => 'Atentaro',
'prefs-watchlist-days'     => 'Maksimuma nombro de tagoj montrendaj en la atentaro:',
'prefs-watchlist-edits'    => 'Maksimuma nombro de ŝanĝoj montrendaj en ekspandita atentaro:',
'prefs-misc'               => 'Miksitaĵoj',
'saveprefs'                => 'Konservu preferojn',
'resetprefs'               => 'Restarigi antaŭajn preferojn',
'oldpassword'              => 'Malnova pasvorto',
'newpassword'              => 'Nova pasvorto',
'retypenew'                => 'Retajpu novan pasvorton',
'textboxsize'              => 'Grandeco de redakta tekstujo',
'rows'                     => 'Linioj:',
'columns'                  => 'Kolumnoj:',
'searchresultshead'        => 'Agordaĵoj pri serĉorezulto',
'resultsperpage'           => 'Montru trovitajn po',
'contextlines'             => 'Montru liniojn el paĝoj po:',
'contextchars'             => 'Montru literojn el linioj ĝis po:',
'recentchangesdays'        => 'Tagoj montrendaj en lastaj ŝanĝoj:',
'recentchangescount'       => "Montru kiom da titoloj en 'Lastaj ŝanĝoj'",
'savedprefs'               => 'Viaj preferoj estas konservitaj.',
'timezonelegend'           => 'Horzono',
'timezonetext'             => 'Indiku je kiom da horoj via
loka horzono malsamas disde tiu de la servilo (UTC).
Ekzemple, por la Centra Eŭropa Horzono, indiku "1" vintre aŭ "2" dum somertempo.',
'localtime'                => 'Loka horzono',
'timezoneoffset'           => 'Malsamo',
'servertime'               => 'Loka horzono (UTC)',
'guesstimezone'            => 'Plenigita el la foliumilo',
'allowemail'               => 'Permesu retmesaĝojn de aliaj uzantoj',
'defaultns'                => 'Traserĉu la jenajn nomspacojn:',
'default'                  => 'defaŭlte',
'files'                    => 'Dosieroj',

# User rights
'userrights-lookup-user'     => 'Administru uzantogrupojn',
'userrights-user-editname'   => 'Entajpu uzantonomon:',
'editusergroup'              => 'Redaktu Uzantgrupojn',
'userrights-editusergroup'   => 'Redaktu uzantogrupojn.',
'saveusergroups'             => 'Konservu uzantogrupojn',
'userrights-groupsmember'    => 'Membro de:',
'userrights-groupsavailable' => 'Disponeblaj grupoj:',
'userrights-groupshelp'      => 'Selektu grupojn el kiuj vi volas forigi aŭ al kiuj vi volas aldoni uzanton. Neselektitaj grupoj ne estos ŝanĝitaj. Vi povas malselekti grupon per STR.',

# Groups
'group'            => 'Grupo:',
'group-bot'        => 'Robotoj',
'group-sysop'      => 'Sisopoj',
'group-bureaucrat' => 'Burokratoj',
'group-all'        => '(ĉiuj)',

'group-bot-member'        => 'Roboto',
'group-sysop-member'      => 'Sisopo',
'group-bureaucrat-member' => 'Burokrato',

'grouppage-bot'        => '{{ns:project}}:Robotoj',
'grouppage-sysop'      => '{{ns:project}}:Administrantoj',
'grouppage-bureaucrat' => '{{ns:project}}:Burokratoj',

# User rights log
'rightslog'      => 'Loglibro de uzanto-rajtoj',
'rightslogtext'  => 'Ĉi tio estas loglibro pri la ŝanĝoj de uzantorajtoj.',
'rightslogentry' => 'ŝanĝis grupan membrecon por $1 de $2 al $3',
'rightsnone'     => '(nenia)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ŝanĝo|ŝanĝoj}}',
'recentchanges'                     => 'Lastaj ŝanĝoj',
'recentchangestext'                 => 'Sekvu la plej lastajn ŝanĝojn en la {{SITENAME}} per ĉi tiu paĝo.',
'recentchanges-feed-description'    => 'Sekvu la plej lastatempajn sxangxojn al la vikio en cxi tiu fonto.',
'rcnote'                            => "Jen la {{PLURAL:$1|lasta '''1''' ŝanĝo|lastaj '''$1''' ŝanĝoj}} dum la {{PLURAL:$2|lasta tago|lastaj '''$2''' tagoj}}, ekde $3.",
'rcnotefrom'                        => 'Jen la ŝanĝoj ekde <strong>$2</strong> (lastaj ĝis <strong>$1</strong>).',
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
'hide'                              => 'kaŝu',
'show'                              => 'montru',
'minoreditletter'                   => 'E',
'newpageletter'                     => 'N',
'boteditletter'                     => 'R',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|unu atentanto|$1 atentantoj}}]',
'rc_categories'                     => 'Nur por jenaj kategorioj (disigu per "|")',
'rc_categories_any'                 => 'ĉiu',

# Recent changes linked
'recentchangeslinked'          => 'Rilataj paĝoj',
'recentchangeslinked-title'    => 'Sxangxoj rilataj al $1',
'recentchangeslinked-noresult' => 'Neniuj sxangxoj en ligitaj pagxoj dum la donata periodo.',
'recentchangeslinked-summary'  => "Cxi tiu speciala pagxo listigas la lastajn sxangxojn en ligitaj pagxoj. Pagxoj en via atentaro estas '''grasaj'''.",

# Upload
'upload'                      => 'Alŝutu dosieron',
'uploadbtn'                   => 'Alŝutu dosieron',
'reupload'                    => 'Realŝutu',
'reuploaddesc'                => 'Revenu al la alŝuta formularo.',
'uploadnologin'               => 'Ne ensalutinta',
'uploadnologintext'           => 'Se vi volas alŝuti dosierojn, vi devas [[Special:Userlogin|ensaluti]].',
'upload_directory_read_only'  => 'La TTT-servilo ne povas alskribi la alŝuto-dosierujon ($1).',
'uploaderror'                 => 'Eraro okazis dum alŝuto',
'uploadtext'                  => 'Por okulumi aŭ serĉi jam alŝutitajn dosierojn, aliru la [[Special:Imagelist|liston de alŝutaĵoj]]. Ĉiuj alŝutoj kaj forigoj estas registrataj en la [[Special:Log/upload|alŝuta loglibro]].

Uzu ĉi tiun formularon por alŝuti novajn bildojn kaj aliajn dosierojn por ilustrado de viaj artikoloj. Ĉe kutimaj retumiloj, vi vidos ĉi-sube butonon "Foliumi..." aŭ simile; tiu malfermas la dosierelektilon de via operaciumo. Kiam vi elektos dosieron, ĝia nomo plenigos la tekstujon apud la butono. Vi ankaŭ nepre devas klakjesi la skatolon por aserti, ke vi ne malobeas la leĝan kopirajton de aliuloj per alŝuto de la dosiero. Por plenumi la alŝutadon, alklaku la butono "Alŝutu". Tio ĉi eble iomete longe daŭros, se estas granda dosiero kaj se via interreta konekto malrapidas.

La dosiertipoj preferataj ĉe {{SITENAME}} estas JPEG por fotografaĵoj, PNG por grafikaĵoj, diagramoj, ktp; kaj OGG por sonregistraĵoj. Bonvolu doni al via dosiero nomon informan, por eviti konfuzon. Por enmeti la dosieron en artikolon, skribu ligilon laŭ la formoj

* <nowiki>[[</nowiki>{{ns:image}}<nowiki>:Dosiero.jpg]]</nowiki>
* <nowiki>[[</nowiki>{{ns:image}}<nowiki>:Bildo.png|teksto por retumiloj negrafikaj]]</nowiki>
aŭ por sono
* <nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dosiero.ogg]]</nowiki>

Bonvolu rimarki, ke same kiel artikoloj en la {{SITENAME}}, aliaj uzantoj rajtas redakti, anstataŭigi, aŭ forigi viajn alŝutaĵojn se ili pensas, ke tio servus la vikion. Se vi aĉe misuzas la sistemon, eblas ke vi estos forbarita.',
'uploadlog'                   => 'loglibro de alŝutaĵoj',
'uploadlogpage'               => 'Loglibro de alŝutaĵoj',
'uploadlogpagetext'           => 'Jen la plej laste alŝutitaj dosieroj.
Ĉiuj tempoj montriĝas laŭ la horzono UTC.',
'filename'                    => 'Dosiernomo',
'filedesc'                    => 'Priskribo -> Resumo',
'fileuploadsummary'           => 'Resumo:',
'filestatus'                  => 'Kopirajta statuso',
'filesource'                  => 'Fonto',
'uploadedfiles'               => 'Alŝutitaj dosieroj',
'ignorewarning'               => 'Ignoru averton kaj konservu dosieron ĉiukaze',
'ignorewarnings'              => 'Ignoru ĉiajn avertojn',
'illegalfilename'             => 'La dosiernomo $1 entenas karaktrojn kiuj ne estas permesitaj en paĝaj titoloj. Bonvolu renomi la dosieron kaj provu denove alŝuti ĝin.',
'badfilename'                 => 'Dosiernomo estis ŝanĝita al "$1".',
'large-file'                  => 'Oni rekomendas, ke dosieroj ne superu grandon de $1 bitokoj; tiu ĉi enhavas $2 bitokojn.',
'largefileserver'             => 'Ĉi tiu dosiero estas pli granda ol permesas la servilaj preferoj.',
'emptyfile'                   => 'La dosiero kiun vi alŝutis ŝajnas malplena. Tio povas esti kaŭzita sde tajperaro en la titolo. Bonvolu kontroli ĉu vi vere volas alŝuti tiun dosieron.',
'fileexists'                  => 'Dosiero kun tia ĉi nomo jam ekzistas. Bonvolu kontroli $1 krom se vi certas ke vi konscie volas ŝanĝi ĝuste tiun.',
'fileexists-forbidden'        => 'Dosiero kun tia ĉi nomo jam ekzistas; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dosiero kun tia ĉi nomo jam ekzistas en la komuna dosiero-deponejo; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Alŝuto sukcesis!',
'uploadwarning'               => 'Averto',
'savefile'                    => 'Konservu dosieron',
'uploadedimage'               => 'alŝutis "[[$1]]"',
'uploaddisabled'              => 'Ni petas pardonon, sed oni malebligis alŝutadon.',
'uploaddisabledtext'          => 'Alŝutado de dosieroj estas malfunkciigita je tiu ĉi vikio.',
'uploadscripted'              => 'HTML-aĵo aŭ skriptokodaĵo troviĝas en tiu ĉi dosiero, kiun TTT-foliumilo eble interpretus erare.',
'uploadcorrupt'               => 'La dosiero estas difektita aŭ havas malĝustan finaĵon. Bonvolu kontroli la dosieron kaj refoje alŝuti ĝin.',
'uploadvirus'                 => 'Viruso troviĝas en la dosiero! Detaloj: $1',
'sourcefilename'              => 'Fonta dosiernomo',
'destfilename'                => 'Celdosiernomo',

'upload-file-error' => 'Interna eraro',

# Image list
'imagelist'                 => 'Listo de alŝutitaj dosieroj',
'imagelisttext'             => "Jen listo de '''$1''' {{PLURAL:$1|dosiero|dosieroj}}, ordigitaj laŭ $2.",
'getimagelist'              => 'akiras dosierliston',
'ilsubmit'                  => 'Trovu!',
'showlast'                  => 'Montru la $1 lastajn bildojn laŭ $2.',
'byname'                    => 'nomo',
'bydate'                    => 'dato',
'bysize'                    => 'grandeco',
'imgdelete'                 => 'forigu',
'imgdesc'                   => 'pri',
'imgfile'                   => 'dosiero',
'filehist'                  => 'Historio de dosiero',
'filehist-help'             => 'Klaku daton/tempon por rigardi la dosieron kiel gxinaa sxajnita tiame.',
'filehist-current'          => 'nuna',
'filehist-datetime'         => 'Dato/Tempo',
'filehist-user'             => 'Uzanto',
'filehist-dimensions'       => 'Dimensioj',
'filehist-filesize'         => 'Grandeco de dosiero',
'filehist-comment'          => 'Komento',
'imagelinks'                => 'Ligiloj al la dosiero',
'linkstoimage'              => 'La jenaj paĝoj ligas al ĉi tiu dosiero:',
'nolinkstoimage'            => 'Neniu paĝo ligas al ĉi tiu dosiero.',
'sharedupload'              => 'Cxi tiu dosiero estas komunuma alsxuto kaj estas uzebla de aliaj projektoj.',
'noimage'                   => 'Ne ekzistas dosiero kun tia nomo vi povas $1.',
'noimage-linktext'          => 'alŝuti ĝin',
'uploadnewversion-linktext' => 'Alŝutu novan version de ĉi tiu dosiero',
'imagelist_date'            => 'Dato',
'imagelist_name'            => 'Nomo',
'imagelist_description'     => 'Priskribo',

# File deletion
'filedelete-intro'   => "Vi forigas '''[[Media:$1|$1]]'''.",
'filedelete-comment' => 'Komento:',
'filedelete-submit'  => 'Forigu',
'filedelete-success' => "'''$1''' estas forigita.",

# MIME search
'mimesearch' => 'MIME-serĉilo',
'download'   => 'elŝutu',

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
'randomredirect' => 'Hazarda alidirekto',

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

'brokenredirects'     => 'Rompitaj alidirektadoj',
'brokenredirectstext' => 'La jenaj alidirektadoj ligas al neekzistantaj artikoloj.',

'withoutinterwiki' => 'Pagxoj sen lingvaj ligiloj',

'fewestrevisions' => 'Artikoloj kun la plej malmultaj revizioj',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bitoko|bitokoj}}',
'ncategories'             => '{{PLURAL:$1|unu kategorio|$1 kategorioj}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligilo|ligiloj}}',
'nmembers'                => '{{PLURAL:$1|unu membero|$1 memberoj}}',
'nrevisions'              => '$1 {{PLURAL:$1|revizio|revizioj}}',
'nviews'                  => '{{PLURAL:$1|unufoje|$1 fojojn}}',
'lonelypages'             => 'Neligitaj paĝoj',
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
'mostlinkedtemplates'     => 'Plej ligitaj sxablonoj',
'mostcategories'          => 'Artikoloj kun la plej multaj kategorioj',
'mostimages'              => 'Plej ligitaj bildoj',
'mostrevisions'           => 'Artikoloj kun la plej multaj revizioj',
'allpages'                => 'Ĉiuj paĝoj',
'prefixindex'             => 'Indeksa prefikso',
'shortpages'              => 'Paĝetoj',
'longpages'               => 'Paĝegoj',
'deadendpages'            => 'Seneliraj paĝoj',
'deadendpagestext'        => 'La sekvaj paĝoj ne ligas al aliaj paĝoj en ĉi tiu vikio.',
'protectedpages'          => 'Protektitaj paĝoj',
'protectedpagestext'      => 'La sekvaj paĝoj estas protektitaj kontraŭ movigo aŭ redaktado',
'protectedpagesempty'     => 'Neniuj paĝoj estas momente protektitaj kun ĉi tiuj parametroj.',
'listusers'               => 'Uzantaro',
'specialpages'            => 'Specialaj paĝoj',
'spheading'               => 'Specialaj paĝoj',
'restrictedpheading'      => 'Alirlimigitaj specialaj paĝoj',
'newpages'                => 'Novaj paĝoj',
'newpages-username'       => 'Salutnomo:',
'ancientpages'            => 'Plej malnovaj artikoloj',
'intl'                    => 'Interlingvaj ligiloj',
'move'                    => 'Movu',
'movethispage'            => 'Movu la paĝon',
'unusedimagestext'        => 'Notu, ke aliaj TTT-ejoj, ekzemple
la alilingvaj {{SITENAME}}j, povas rekte ligi al dosiero per URL.
Tio ne estus enkalkutita en la jena listo.',
'unusedcategoriestext'    => 'La paĝoj de la sekvanta kategorio jam ekzistas, sed neniu alia artikolo aŭ kategorio rilatas al ĝi.',
'notargettitle'           => 'Sen celpaĝo',
'notargettext'            => 'Vi ne precizigis, kiun paĝon aŭ uzanton priumi.',

# Book sources
'booksources'      => 'Libroservoj',
'booksources-text' => 'Jen ligilaro al aliaj TTT-ejoj, kiuj vendas librojn,
kaj/aŭ informumos pri la libro ligita.
La {{SITENAME}} ne estas komerce ligita al tiuj vendejoj, kaj la listo ne estu
komprenata kiel rekomendo aŭ reklamo.',

'categoriespagetext' => 'La sekvantaj kategorioj ekzistas jam en la vikio.',
'userrights'         => 'Administrado de uzantorajtoj',
'groups'             => 'Uzantogrupoj',
'alphaindexline'     => '$1 ĝis $2',
'version'            => 'Versio',

# Special:Log
'specialloguserlabel'  => 'Uzanto:',
'speciallogtitlelabel' => 'Titolo:',
'log'                  => 'Loglibroj',
'all-logs-page'        => 'Ĉiuj loglibroj',
'log-search-legend'    => 'Serĉu loglibrojn',
'log-search-submit'    => 'Ek',
'alllogstext'          => 'Suma kompilaĵo de ĉiuj alŝutoj, forigoj, protektoj, blokadoj kaj agoj de administrantoj. Vi povas pliprecizigi la kompilaĵon laŭ loglibra tipo, **** vikipediista **** nomo aŭ koncernita paĝo.',

# Special:Allpages
'nextpage'          => 'Sekvanta paĝo ($1)',
'prevpage'          => 'Antaŭa paĝo ($1)',
'allpagesfrom'      => 'Montru paĝojn ekde :',
'allarticles'       => 'Ĉiuj artikoloj',
'allinnamespace'    => 'Ĉiuj paĝoj ($1 nomspaco)',
'allnotinnamespace' => 'Ĉiuj paĝoj (ne en nomspaco $1)',
'allpagesprev'      => 'Antaŭen',
'allpagesnext'      => 'Sekven',
'allpagessubmit'    => 'Ek!',
'allpagesprefix'    => 'Montru paĝojn kun prefikso:',

# Special:Listusers
'listusers-submit' => 'Montri',

# E-mail user
'mailnologin'     => 'Neniu alsendota adreso',
'mailnologintext' => 'Vi nepre estu [[Special:Userlogin|salutanta]] kaj havanta validan retpoŝtadreson en viaj [[Special:Preferences|preferoj]] por retpoŝti al aliaj uzantoj.',
'emailuser'       => 'Retpoŝtu',
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
'emailsent'       => 'Retmesaĝo sendita',
'emailsenttext'   => 'Via retmesaĝo estas sendita.',

# Watchlist
'watchlist'            => 'Atentaro',
'mywatchlist'          => 'Atentaro',
'watchlistfor'         => '(por <b>$1</b>)',
'nowatchlist'          => 'Vi ne jam elektis priatenti iun ajn paĝon.',
'watchnologin'         => 'Ne ensalutinta',
'watchnologintext'     => 'Nepras [[Special:Userlogin|ensaluti]] por ŝanĝi vian atentaron.',
'addedwatch'           => 'Aldonis al atentaro',
'addedwatchtext'       => "La paĝo \"[[:\$1]]\" estis aldonita al via [[Special:Watchlist|atentaro]]. Estontaj ŝanĝoj de tiu ĉi paĝo aperos en '''grasa tiparo''' en la [[Special:Recentchanges|listo de Lastaj Ŝanĝoj]], kaj estos listigitaj en via atentaro. Se vi poste volos forigi la paĝon el via atentaro, alklaku \"Malatentu paĝon\" en la ilobreto.",
'removedwatch'         => 'Forigis el atentaro',
'removedwatchtext'     => 'La paĝo "[[:$1]]" estas forigita el via atentaro.',
'watch'                => 'Atentu',
'watchthispage'        => 'Priatentu paĝon',
'unwatch'              => 'Malatentu',
'unwatchthispage'      => 'Malatentu paĝon',
'notanarticle'         => 'Ne estas artikolo',
'watchnochange'        => 'Neniu artikolo en via atentaro redaktiĝis dum la prispektita tempoperiodo.',
'watchlist-details'    => 'Vi priatentas {{PLURAL:$1|$1 paĝon|$1 paĝojn}}, krom diskutpaĝoj.',
'wlheader-enotif'      => '* Retpoŝta sciigo estas ebligita',
'wlheader-showupdated' => "* Montriĝas per '''dikaj literoj''' tiuj paĝoj, kiujn oni ŝanĝis ekde kiam vi laste vizitis ilin",
'watchmethod-recent'   => 'traserĉas lastajn redaktojn',
'watchmethod-list'     => 'traserĉas priatentitajn',
'watchlistcontains'    => 'Via atentaro enhavas $1 {{PLURAL:$1|paĝon|paĝojn}}.',
'iteminvalidname'      => 'Ia eraro pri "$1", nevalida titolo...',
'wlnote'               => "Jen la {{PLURAL:$1|lasta redakto|lastaj '''$1''' redaktoj}} dum la {{PLURAL:$2|lasta horo|lastaj '''$2''' horoj}}.",
'wlshowlast'           => 'Montru el lastaj $1 horoj $2 tagoj $3',
'watchlist-show-bots'  => 'Montru robotajn redaktojn',
'watchlist-hide-bots'  => 'Kaŝu robotajn redaktojn',
'watchlist-show-own'   => 'Montru miajn redaktojn',
'watchlist-hide-own'   => 'Kaŝu miajn redaktojn',
'watchlist-show-minor' => 'Montru redaktetojn',
'watchlist-hide-minor' => 'Kaŝu redaktetojn',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Rigardante...',
'unwatching' => 'Malrigardante...',

'enotif_mailer'      => 'Averta retmesaĝo de {{SITENAME}}',
'enotif_reset'       => 'Marku ĉiujn vizititajn paĝojn',
'enotif_newpagetext' => 'Tiu ĉi estas nova paĝo',
'changed'            => 'ŝanĝita',
'enotif_subject'     => 'la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited' => 'Vidu $1 por ĉiuj ŝanĝoj de post via lasta vizito.',
'enotif_body'        => 'Kara $WATCHINGUSERNAME,

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
'deletepage'                  => 'Forigu paĝon',
'confirm'                     => 'Konfirmu',
'excontent'                   => "enhavis: '$1'",
'excontentauthor'             => "la enteno estis : '$1' (kaj la sola kontribuinto estis '$2')",
'exbeforeblank'               => "antaŭ malplenigo enhavis: '$1'",
'exblank'                     => 'estis malplena',
'confirmdelete'               => 'Konfirmu forigadon',
'deletesub'                   => '(Forigas "$1")',
'historywarning'              => 'Averto: la forigota paĝo havas historion:',
'confirmdeletetext'           => 'Vi forigos la artikolon aŭ dosieron kaj forviŝos ĝian tutan historion el la datumaro.<br /> Bonvolu konfirmi, ke vi vere intencas tion, kaj ke vi komprenas la sekvojn, kaj ke vi ja sekvas la [[{{MediaWiki:Policy-url}}|regulojn pri forigado]].',
'actioncomplete'              => 'Ago farita',
'deletedtext'                 => '"$1" estas forigita.
Vidu la paĝon $2 por registro de lastatempaj forigoj.',
'deletedarticle'              => 'forigis "$1"',
'dellogpage'                  => 'Loglibro de forigoj',
'dellogpagetext'              => 'Jen listo de la plej lastaj forigoj el la datumaro.
Ĉiuj tempoj sekvas la horzonon UTC.',
'deletionlog'                 => 'loglibro de forigoj',
'reverted'                    => 'Restarigis antaŭan version',
'deletecomment'               => 'Kialo por forigo',
'deleteotherreason'           => 'Alia/plua kialo:',
'deletereasonotherlist'       => 'Alia kialo',
'rollback'                    => 'Restarigu antaŭan redakton',
'rollback_short'              => 'Restarigo',
'rollbacklink'                => 'restarigu antaŭan',
'rollbackfailed'              => 'Restarigo malsukcesis',
'cantrollback'                => 'Neeblas restarigi antaŭan redakton; la redaktinto lasta estas la sola de la paĝo.',
'alreadyrolled'               => 'Ne eblas restarigi la lastan redakton de [[$1]] de la [[User:$2|$2]] ([[User talk:$2|diskuto]]) pro tio, ke oni intertempe redaktis la paĝon. La lasta redaktinto estas [[User:$3|$3]] ([[User talk:$3|diskuto]]).',
'editcomment'                 => "La komento estis: '<i>$1</i>'.", # only shown if there is an edit comment
'revertpage'                  => 'Forigis redaktojn de [[Special:Contributions/$2|$2]] ([[User talk:$2|diskuto]]); restarigis al la lasta versio de [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'sessionfailure'              => 'Ŝajnas ke estas problemo kun via ensalutado;
Ĉi ago estis nuligita por malhelpi fiensalutadon.
Bonvolu alklalki la reirbutonon kaj reŝarĝi la paĝon el kiu vi venas, kaj provu denove.',
'protectlogpage'              => 'Protektloglibro',
'protectlogtext'              => 'Sube estas listo de paĝ-ŝlosoj kaj malŝlosoj.
Vidu [[Special:Protectedpages|liston de protektitaj paĝoj]] por pli da informoj.',
'protectedarticle'            => 'protektita [[:$1]]',
'unprotectedarticle'          => 'malprotektita [[$1]]',
'protectsub'                  => '(Protektante "$1")',
'confirmprotect'              => 'Konfirmu protektadon',
'protectcomment'              => 'Kialo por protekti',
'protectexpiry'               => 'Eksvalidigxas:',
'protect_expiry_invalid'      => 'Nevalida findaŭro.',
'protect_expiry_old'          => 'Eksvalidigxa tempo jam pasis.',
'unprotectsub'                => '(Malprotektanta "$1")',
'protect-unchain'             => 'Malŝlosu movpermesojn',
'protect-text'                => 'Vi povas ĉi tie vidi kaj ŝanĝi la protektnivelon de la paĝo [[$1]]. Bonvolu certiĝi ke vi respektas la [[Special:Protectedpages|gvidliniojn de la projekto]].',
'protect-locked-access'       => 'Via konto ne havas permeson ŝanĝi protekto-nivelojn. 
Jen la aktualaj valoroj por la paĝo <strong>$1</strong>:',
'protect-cascadeon'           => 'Cxi pagxo estas nun protektita kontraux redaktado cxar gxi estas inkluzivita en {{PLURAL:$1|jena pagxo, kiu mem estas protektita|jenaj pagxoj, kiuj mem estas protektitaj}} kun kaskada protekto. Vi povas sxangxi ties protektnivelon, sed tio ne sxangxos la kaskadan protekton.',
'protect-default'             => '(defaŭlte)',
'protect-fallback'            => '"$1" permeso bezonata',
'protect-level-autoconfirmed' => 'Bloki neensalutintajn uzantojn',
'protect-level-sysop'         => 'Nur administrantoj',
'protect-summary-cascade'     => 'kaskada',
'protect-expiring'            => 'finiĝas je $1 (UTC)',
'protect-cascade'             => 'Protektu cxiujn pagxojn inkluzivitajn en cxi pagxo (kaskada protekto)',
'protect-cantedit'            => 'Vi ne povas sxangxi la protekt-nivelojn de cxi tiu pagxo, cxar vi ne havas permeson redakti gxin.',
'restriction-type'            => 'Permeso:',
'restriction-level'           => 'Nivelo de limigo:',

# Restrictions (nouns)
'restriction-edit' => 'Redaktu',
'restriction-move' => 'Movu',

# Undelete
'undelete'                 => 'Restarigu forigitajn paĝojn',
'undeletepage'             => 'Montru kaj restarigu forigitajn paĝojn',
'viewdeletedpage'          => 'Rigardu forigitajn paĝojn',
'undeletepagetext'         => 'La jenaj paĝoj estis forigitaj, sed ankoraŭ restas arkivitaj,
kaj oni povas restarigi ilin. La arkivo povas esti malplenigita periode.',
'undeleteextrahelp'        => "Por restarigi la tuton de la paĝo, marku neniun markobutonon kaj klaku la butonon '''''Restarigu'''''. Por restarigi selektitajn versiojn de la paĝo, marku la butonojn konformajn al la dezirataj versioj, kaj klaku la butonon '''''Restarigu'''''. Klako je '''''Restarigu''''' malplenigos la komentozonon kaj malmarkos ĉiujn la markobutonojn.",
'undeleterevisions'        => '$1 {{PLURAL:$1|versio arkivita|versioj arkivitaj}}',
'undeletehistory'          => 'Se vi restarigos la paĝon, ĉiuj versioj estos restarigitaj
en la historio. Se nova paĝo kun la sama nomo estis kreita post la forigo, la restarigitaj
versioj aperos antaŭe en la historio, kaj la aktuala versio ne estos anstataŭigita.',
'undeletehistorynoadmin'   => 'Ĉi tiu artikolo estis forigita. La kaŭzo por la forigo estas montrata en la malsupra resumo, kune kun detaloj pri la uzantoj, kiuj redaktis ĉi tiun paĝon antaŭ la forigo. La aktuala teksto de ĉi tiuj forigitaj revizioj estas atingebla nur por administrantoj.',
'undeletebtn'              => 'Restarigu!',
'undeletereset'            => 'Reŝarĝu',
'undeletecomment'          => 'Komento:',
'undeletedarticle'         => 'restarigis "$1"',
'undeletedrevisions'       => '{{PLURAL:$1|1 versio restarigita|$1 versioj restarigitaj}}',
'undeletedrevisions-files' => '{{PLURAL:$1|1 versio|$1 versioj}} kaj {{PLURAL:$2|1 dosiero|$2 dosieroj}} restarigitaj',
'undeletedfiles'           => '{{PLURAL:$1|1 dosiero restarigita|$1 dosieroj restarigitaj}}',
'undeletedpage'            => "<big>'''$1 estis restarigita'''</big>

Konsultu la [[Special:Log/delete|deletion log]] por protokolo pri la lastatempaj forigoj kaj restarigoj.",
'undelete-search-submit'   => 'Serĉi',

# Namespace form on various pages
'namespace'      => 'Nomspaco:',
'invert'         => 'Inversu selektaĵon',
'blanknamespace' => '(Artikoloj)',

# Contributions
'contributions' => 'Kontribuoj de uzanto',
'mycontris'     => 'Miaj kontribuoj',
'contribsub2'   => 'De $1 ($2)',
'nocontribs'    => 'Trovis neniajn redaktojn laŭ tiu kriterio.',
'ucnote'        => 'Jen la <b>$1</b> lastaj redaktoj de tiu uzanto dum la <b>$2</b> lastaj tagoj.',
'uclinks'       => 'Montru la $1 lastajn redaktojn; montru la $2 lastajn tagojn.',
'uctop'         => ' (lasta)',
'month'         => 'Ekde monato (kaj pli frue):',
'year'          => 'Ekde jaro (kaj pli frue):',

'sp-contributions-newbies'     => 'Montru kontribuojn nur de novaj kontoj',
'sp-contributions-newbies-sub' => 'Kontribuoj de novaj uzantoj. Forigitaj paĝoj ne estas montritaj.',
'sp-contributions-blocklog'    => 'Blokada loglibro',
'sp-contributions-search'      => 'Serĉado de kontribuoj',
'sp-contributions-username'    => 'IP-adreso aŭ uzantonomo:',
'sp-contributions-submit'      => 'Serĉu',

'sp-newimages-showfrom' => 'Montru novajn bildojn komencante de $1',

# What links here
'whatlinkshere'       => 'Ligiloj ĉi tien',
'whatlinkshere-title' => 'Pagxoj ligantaj al $1',
'whatlinkshere-page'  => 'Paĝo:',
'linklistsub'         => '(Listo de ligiloj)',
'linkshere'           => "La jenaj paĝoj ligas al '''[[:$1]]''':",
'nolinkshere'         => "Neniu paĝo ligas al '''[[:$1]]'''.",
'isredirect'          => 'alidirekto',
'istemplate'          => 'inkludo',
'whatlinkshere-prev'  => '{{PLURAL:$1|antauxa|antauxa $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|posta|posta $1}}',
'whatlinkshere-links' => '← ligiloj',

# Block/unblock
'blockip'              => 'Forbaru uzanton/IP-adreson',
'blockiptext'          => "Per jena formularo vi povas forpreni de ajna nomo aŭ IP-adreso la rajton skribi en la vikio. Oni faru tion ''nur'' por eviti vandalismon, kaj sekvante la [[{{MediaWiki:Policy-url}}|regulojn pri forbarado]]. Klarigu la precizan kialon malsupre (ekzemple, citu paĝojn, kiuj estis vandaligitaj).",
'ipaddress'            => 'IP-adreso/nomo',
'ipadressorusername'   => 'IP adreso aŭ uzantonomo',
'ipbexpiry'            => 'Blokdaŭro',
'ipbreason'            => 'Kialo',
'ipbreasonotherlist'   => 'Alia kaŭzo',
'ipbsubmit'            => 'Forbaru la adreson',
'ipbother'             => 'Alia daŭro',
'ipboptions'           => '2 horoj:2 hours,1 tago:1 day,3 tagoj:3 days,1 semajno:1 week,2 semajnoj:2 weeks,1 monato:1 month,3 monatoj:3 months,6 monatoj:6 months,1 jaro:1 year,porĉiam:infinite', # display1:time1,display2:time2,...
'ipbotheroption'       => 'alia',
'ipbotherreason'       => 'Alia/aldona kaŭzo:',
'badipaddress'         => 'Neniu uzanto, aŭ la IP-adreso estas misformita.',
'blockipsuccesssub'    => 'Oni sukcese forbaris la adreson/nomon.',
'blockipsuccesstext'   => '"$1" estas forbarita. <br />Vidu la [[Special:Ipblocklist|liston de IP-forbaroj]].',
'unblockip'            => 'Malforbaru IP-adreson/nomon',
'unblockiptext'        => 'Per la jena formulo vi povas repovigi al iu
forbarita IP-adreso/nomo la povon enskribi en la vikio.',
'ipusubmit'            => 'Malforbaru la adreson',
'ipblocklist'          => 'Listo de forbaritaj IP-adresoj/nomoj',
'ipblocklist-username' => 'Uzantonomo aŭ IP-adreso:',
'ipblocklist-submit'   => 'Serĉi',
'blocklistline'        => 'Je $1, $2 forbaris $3 ($4)',
'infiniteblock'        => 'senfina',
'expiringblock'        => 'finiĝas je $1',
'blocklink'            => 'forbaru',
'unblocklink'          => 'malforbaru',
'contribslink'         => 'kontribuoj',
'autoblocker'          => 'Provizore forbarita aŭtomate pro tio, ke vi uzas la saman IP-adreson kiel "$1", kiu estis forbarita pro : "$2".',
'blocklogpage'         => 'Forbarlibro',
'blocklogentry'        => 'forbaris [[$1]] por daŭro de $2 $3',
'blocklogtext'         => 'Ĉi tio estas loglibro pri forbaraj kaj malforbaraj agoj. Aŭtomate forbaritaj IP adresoj ne estas listigitaj. Vidu la [[Special:Ipblocklist|IP forbarliston]] por ĉi-momente fobaritaj uzantoj kaj IP-adresoj.',
'unblocklogentry'      => '$1 estis malbarita',
'ipb_expiry_invalid'   => 'Nevalida blokdaŭro.',

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

# Move page
'movepage'                => 'Movu paĝon',
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
'movearticle'             => 'Movu paĝon',
'movenologin'             => 'Ne ensalutinta',
'movenologintext'         => 'Vi nepre estu registrita uzanto kaj [[Special:Userlogin|ensalutu]] por rajti movi paĝojn.',
'newtitle'                => 'Al nova titolo',
'move-watch'              => 'Atentu cxi tiun pagxon',
'movepagebtn'             => 'Movu paĝon',
'pagemovedsub'            => 'Sukcesis movi',
'movepage-moved'          => '<big>\'\'\'"$1" estis movita al "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Paĝo kun tiu nomo jam ekzistas, aŭ la nomo kiun vi elektis ne validas.
Bonvolu elekti alian nomon.',
'talkexists'              => 'Oni ja sukcesis movi la paĝon mem, sed
ne movis la diskuto-paĝon ĉar jam ekzistas tia ĉe la nova titolo.
Bonvolu permane kunigi ilin.',
'movedto'                 => 'movita al',
'movetalk'                => 'Movu ankaŭ la "diskuto"-paĝon, se ĝi ekzistas.',
'talkpagemoved'           => 'Ankaŭ la diskutpaĝo estas movita.',
'talkpagenotmoved'        => 'La diskutpaĝo <strong>ne</strong> estas movita.',
'1movedto2'               => '[[$1]] movita al [[$2]]',
'1movedto2_redir'         => '[[$1]] movita al [[$2]], redirekto lasita',
'movelogpage'             => 'Loglibro de paĝmovoj',
'movelogpagetext'         => 'Jen listo de movitaj paĝoj',
'movereason'              => 'Kialo',
'revertmove'              => 'restarigu',
'delete_and_move'         => 'Forigu kaj movu',
'delete_and_move_text'    => '==Forigo nepras==

La celartikolo "[[$1]]" jam ekzistas. Ĉu vi volas forigi ĝin por krei spacon por la movo?',
'delete_and_move_confirm' => 'Jes, forigu la paĝon',
'delete_and_move_reason'  => 'Forigita por ebligi movon',
'selfmove'                => 'Font- kaj cel-titoloj samas; ne eblas movi paĝon sur ĝin mem.',
'immobile_namespace'      => 'La celtitolo estas de speciala speco; ne eblas movi paĝojn en tiun nomspacon.',

# Export
'export'            => 'Eksportu paĝojn',
'exporttext'        => 'Vi povas eksporti la tekston kaj la redaktohistorion de aparta paĝo aŭ de paĝaro kolektita en ia XML ; tio povas esti importita en alian programon funkciantan per MediaWiki-softvaro, ŝanĝita, aŭ nur prenita por propra privata uzo.',
'exportcuronly'     => 'Entenas nur la aktualan version, ne la malnovajn.',
'export-submit'     => 'Eksporti',
'export-addcattext' => 'Aldoni paĝojn el kategorio:',
'export-addcat'     => 'Aldoni',

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
'thumbnail-more'  => 'Pligrandigu',
'missingimage'    => '<b>Mankanta bildo</b><br /><i>$1</i>',
'filemissing'     => 'Mankanta dosiero',
'thumbnail_error' => 'Okazis eraro kreante antaŭvidan bildeton: $1',

# Special:Import
'import'                  => 'Importitaj paĝoj',
'importinterwiki'         => 'Transvikia importo',
'import-interwiki-submit' => 'Importi',
'importtext'              => 'Bonvole eksportu la dosieron el la fonta vikio per la ilo Speciala:Export, konservu ĝin sur via disko kaj poste alŝutu ĝin tien ĉi.',
'import-revision-count'   => '$1 {{PLURAL:$1|versio|versioj}}',
'importfailed'            => 'Malsukcesis la importo: $1',
'importnotext'            => 'Malplena aŭ senteksta',
'importsuccess'           => 'La importo sukcesis!',
'importhistoryconflict'   => 'Malkongrua historia versio ekzistas (eble la paĝo importiĝis antaŭe)',
'importnosources'         => 'Neniu transvikia importfonto estis difinita kaj rekta historio de alŝutoj estas malaktivigita.',

# Import log
'importlogpage' => 'Loglibro de importaĵoj',

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
'tooltip-pt-logout'               => 'Elsalutu',
'tooltip-ca-talk'                 => 'Diskuto pri la artikolo',
'tooltip-ca-edit'                 => 'Vi povas redakti tiun ĉi paĝon. Bv uzi la antaŭvidbutonon antaŭ ol konservi.',
'tooltip-ca-addsection'           => 'Aldonu komenton al tiu diskuto.',
'tooltip-ca-viewsource'           => 'Tiu paĝo estas protektita. Vi povas nur rigardi ties fonton.',
'tooltip-ca-history'              => 'Antaŭaj versioj de tiu ĉi paĝo.',
'tooltip-ca-protect'              => 'Protektu tiun ĉi paĝon',
'tooltip-ca-delete'               => 'Forigu tiun ĉi paĝon',
'tooltip-ca-undelete'             => 'Restarigu la redaktojn faritajn al tiu ĉi paĝo antaŭ ties forigo',
'tooltip-ca-move'                 => 'Movu tiun ĉi paĝon',
'tooltip-ca-watch'                => 'Aldonu tiun ĉi paĝon al via atentaro',
'tooltip-ca-unwatch'              => 'Forigu tiun ĉi paĝon el via atentaro',
'tooltip-search'                  => 'Traserĉu ĉi tiun vikion',
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
'tooltip-t-contributions'         => 'Vidu la liston de kontribuoj de tiu ĉi uzanto',
'tooltip-t-emailuser'             => 'Sendu retmesaĝon al tiu ĉi uzanto',
'tooltip-t-upload'                => 'Alŝutu bildojn aŭ dosierojn',
'tooltip-t-specialpages'          => 'Listo de ĉiuj specialaj paĝoj',
'tooltip-ca-nstab-main'           => 'Vidu la artikolon',
'tooltip-ca-nstab-user'           => 'Vidu la personan paĝon de la uzanto',
'tooltip-ca-nstab-media'          => 'Vidu la paĝon de la dosiero',
'tooltip-ca-nstab-special'        => 'Estas speciala paĝo, vi ne rajtas redakti ĝin.',
'tooltip-ca-nstab-project'        => 'Vidu la paĝon de la projekto',
'tooltip-ca-nstab-image'          => 'Vidu la paĝon de la bildo',
'tooltip-ca-nstab-mediawiki'      => 'Vidu la sisteman mesaĝon',
'tooltip-ca-nstab-template'       => 'Vidu la ŝablonon',
'tooltip-ca-nstab-help'           => 'Vidu la helppaĝon',
'tooltip-ca-nstab-category'       => 'Vidu la paĝon de kategorioj',
'tooltip-minoredit'               => 'Marku tiun ŝanĝon kiel malgrava',
'tooltip-save'                    => 'Konservu viajn ŝanĝojn',
'tooltip-preview'                 => 'Antaŭrigardu viajn ŝanĝojn. Bonvolu uzi tion antaŭ ol konservi ilin!',
'tooltip-diff'                    => 'Montru la sxangxojn kiujn vi faris de la teksto.',
'tooltip-compareselectedversions' => 'Vidu la malsamojn inter ambaŭ selektitaj versioj de ĉi paĝo.',
'tooltip-watch'                   => 'Aldonu ĉi paĝon al via atentaro',

# Attribution
'anonymous'        => 'Anonima(j) uzanto(j) de {{SITENAME}}',
'siteuser'         => '{{SITENAME}} uzanto $1',
'lastmodifiedatby' => 'Ĉi paĝo estis laste ŝanĝita je $2, $1 de $3.', # $1 date, $2 time, $3 user
'and'              => 'kaj',
'othercontribs'    => 'Bazita sur la laboro de $1.',
'others'           => 'aliaj',
'siteusers'        => '{{SITENAME}} uzanto(j) $1',

# Spam protection
'spamprotectiontitle'    => 'Filtrilo kontraŭ spamo',
'spamprotectiontext'     => 'La paĝo kiun vi trovis konservi estis blokita per la spam-filtrilo. Ĉi tia eraro estas kaŭzata pro ekstera ligilo al malpermesata retejo.',
'spamprotectionmatch'    => 'La jena teksto ekagigis la spam-filtrilon: $1',
'subcategorycount'       => 'Estas {{PLURAL:$1|unu subkategorio|$1 subkategorioj}} en tiu kategorio.',
'categoryarticlecount'   => 'Estas {{PLURAL:$1|unu artikolo|$1 artikoloj}} en tiu kategorio.',
'category-media-count'   => 'Estas {{PLURAL:$1|unu dosiero|$1 dosieroj}} en cxi tiu kategorio.',
'listingcontinuesabbrev' => 'daŭrigo',
'spambot_username'       => 'Trudmesaĝa forigo de MediaWiki',
'spam_reverting'         => 'Restarigo de lasta versio ne entenante ligilojn al $1',
'spam_blanking'          => 'Forviŝo de ĉiuj versioj entenate ligilojn al $1',

# Info page
'infosubtitle' => 'Informoj por paĝo',

# Math options
'mw_math_png'    => 'Ĉiam krei PNG-bildon',
'mw_math_simple' => 'HTMLigu se simple, aŭ PNG',
'mw_math_html'   => 'HTMLigu se eble, aŭ PNG',
'mw_math_source' => 'Lasu TeX-fonton (por tekstfoliumiloj)',
'mw_math_modern' => 'Rekomendita por modernaj foliumiloj',
'mw_math_mathml' => 'MathML seeble (provizora)',

# Patrolling
'markaspatrolleddiff'   => 'Marku kiel patrolita',
'markaspatrolledtext'   => 'Marku ĉi artikolon patrolita',
'markedaspatrolled'     => 'Markita kiel patrolita',
'markedaspatrolledtext' => 'La elektita versio estas markita kiel patrolita.',
'rcpatroldisabled'      => 'Patrolado de lastaj ŝanĝoj malaktivigita',
'rcpatroldisabledtext'  => 'La funkcio patrolado de la lastaj ŝanĝoj estas nun malaktivigita.',

# Image deletion
'deletedrevision' => 'Forigita malnova versio $1',

# Browsing diffs
'previousdiff' => '← Iru al antaŭa ŝanĝo',
'nextdiff'     => 'Iru al sekvanta ŝanĝo →',

# Media information
'mediawarning'         => "'''Warning''': This file may contain malicious code, by executing it your system may be compromised.
<hr />",
'imagemaxsize'         => 'Elmontru bildojn en bildpriskribaj paĝoj je maksimume :',
'thumbsize'            => 'Grandeco de bildetoj :',
'widthheightpage'      => '$1×$2, $3 paĝoj',
'file-info-size'       => '($1 × $2 rastrumeroj, dosiera grandeco: $3, MIME-tipo: $4)',
'file-nohires'         => '<small>Nenia pli granda distingivo havebla.</small>',
'svg-long-desc'        => '(SVG-dosiero, $1 × $2 rastrumeroj, grandeco de dosiero: $3)',
'show-big-image'       => 'Plena distingivo',
'show-big-image-thumb' => '<small>Grandeco de cxi antauxvido: $1 × $2 rastrumeroj</small>',

# Special:Newimages
'newimages'    => 'Aro da novaj bildoj',
'showhidebots' => '($1 robotojn)',
'noimages'     => 'Nenio videbla.',

# Metadata
'metadata'          => 'Metadatumo',
'metadata-help'     => 'Cxi tiu dosiero enhavas pluan informon, versxajne aldonita de la cifereca fotilo aux skanilo uzata krei aux skani gxin. Se la dosiero estis sxangxita de ties originala stato, iuj detaloj eble ne estas tute estos sama kiel la modifita bildo.',
'metadata-expand'   => 'Montru etendajn detalojn',
'metadata-collapse' => 'Kaŝu etendajn detalojn',
'metadata-fields'   => 'La jenaj EXIF-metadatumaj kampoj estos inkluzivitaj en bildo-pagxoj kiam la metadatuma tabelo estas disfaldigita. Aliaj estos kasxita defauxlte.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'      => 'Larĝeco',
'exif-imagelength'     => 'Alteco',
'exif-artist'          => 'Kreinto',
'exif-pixelxdimension' => 'Valind image height',
'exif-aperturevalue'   => 'Aperturo',
'exif-brightnessvalue' => 'Heleco',
'exif-contrast'        => 'Kontrasto',

'exif-unknowndate' => 'Nekonata dato',

'exif-orientation-1' => 'Normala', # 0th row: top; 0th column: left

'exif-componentsconfiguration-0' => 'ne ekzistas',

'exif-meteringmode-0' => 'Nekonata',

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
'confirmemail'            => 'Konfirmu retpoŝtadreson',
'confirmemail_text'       => 'Ĉi tiu vikio postulas ke vi validigu vian retadreson antaŭ ol uzadi la retmesaĝpreferojn. Bonvolu alklaki la suban butonon por sendi konfirmesaĝon al via adreso. La mesaĝo entenos ligilon kun kodo; bonvolu alŝuti la ligilon en vian foliumilon por konfirmi ke via retadreso validas.',
'confirmemail_send'       => 'Retmesaĝi konfirmkodon',
'confirmemail_sent'       => 'Konfirma retmesaĝo estas sendita.',
'confirmemail_sendfailed' => 'Ne eblis sendi konfirmretmesaĝon. Bonvolu kontroli ĉu en la adreso ne estus nevalidaj karaktroj.

Retpoŝta programo sciigis: $1',
'confirmemail_invalid'    => 'Nevalida konfirmkodo. La kodo eble ne plu validas.',
'confirmemail_needlogin'  => 'Vi devas $1 por konfirmi vian retpoŝtan adreson.',
'confirmemail_success'    => 'Via retadreso estas konfirmita. Vi povas nun ensaluti kaj ĝui la vikion.',
'confirmemail_loggedin'   => 'Via retadreso estas nun konfirmita.',
'confirmemail_error'      => 'Io misokazis dum konservo de via konfirmo.',
'confirmemail_body'       => 'Iu, verŝajne vi ĉe la IP-adreso $1, enregistrigis per tiu 
ĉi retpoŝtadreso la konton "$2" ĉe {{SITENAME}}.

Malfermu tiun ĉi ligon en via retumilo, por konfirmi ke la
konto ja apartenas al vi kaj por malŝlosi retpoŝtajn
kapablojn ĉe {{SITENAME}}:

$3

Se vi ne mendis ĉi tiun mesaĝon, ne alklaku la ligon. Tiu
ĉi konfirmokodo eksvalidiĝos je $4.',

# Scary transclusion
'scarytranscludetoolong' => '[Bedaŭrinde la URL estas tro longa]',

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
'recreate'            => 'Rekreu',

# HTML dump
'redirectingto' => 'Redirektante al [[$1]]...',

# action=purge
'confirm_purge'        => 'Ĉu forviŝiĝu la enhavo de tiu ĉi paĝo?

$1',
'confirm_purge_button' => 'Bone',

# AJAX search
'articletitles' => "Artikoloj komencante de ''$1''",
'hideresults'   => 'Kaŝu rezultojn',

# Table pager
'table_pager_next'  => 'Sekva paĝo',
'table_pager_prev'  => 'Antaŭa paĝo',
'table_pager_first' => 'Unua paĝo',
'table_pager_last'  => 'Lasta paĝo',
'table_pager_empty' => 'Neniaj rezultoj',

# Auto-summaries
'autoredircomment' => 'Redirektante al [[$1]]',
'autosumm-new'     => 'Nova paĝo: $1',

# Watchlist editing tools
'watchlisttools-view' => 'Montru koncernajn ŝanĝojn',
'watchlisttools-edit' => 'Vidi kaj redakti atentaron',
'watchlisttools-raw'  => 'Redakti krudan atentaron',

);
