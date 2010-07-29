<?php
/** Veps (Vepsan kel')
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Sura
 * @author Triple-ADHD-AS
 * @author Игорь Бродский
 */

$fallback = 'et';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specialine',
	NS_TALK             => 'Lodu',
	NS_USER             => 'Kävutai',
	NS_USER_TALK        => 'Lodu_kävutajas',
	NS_PROJECT_TALK     => 'Lodu_$1-saitas',
	NS_FILE             => 'Fail',
	NS_FILE_TALK        => 'Lodu_failas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Lodu_MediaWikiš',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Lodu_šablonas',
	NS_HELP             => 'Abu',
	NS_HELP_TALK        => 'Lodu_abus',
	NS_CATEGORY         => 'Kategorii',
	NS_CATEGORY_TALK    => 'Lodu_kategorijas',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'KaksitadudLäbikosketused' ),
	'BrokenRedirects'           => array( 'RebitadudLäbikosketused' ),
	'Userlogin'                 => array( 'KävutajanTulendnimi' ),
	'Userlogout'                => array( 'KävutajanLäntend' ),
	'CreateAccount'             => array( 'SätaRegistracii' ),
	'Preferences'               => array( 'Järgendused' ),
	'Watchlist'                 => array( 'KaclendNimikirjutez' ),
	'Recentchanges'             => array( 'TantoižedToižetused' ),
	'Upload'                    => array( 'Jügutoitta' ),
	'Newimages'                 => array( 'UdedFailad' ),
	'Listusers'                 => array( 'KävutajidenNimikirjutez' ),
	'Statistics'                => array( 'Statistikad' ),
	'Lonelypages'               => array( 'ÜksjäižedLehtpoled', 'ArmotomadLehtesed' ),
	'Wantedfiles'               => array( 'VarastadudFailad' ),
	'Shortpages'                => array( 'LühüdadLehtpoled' ),
	'Longpages'                 => array( 'Pit\'kädLehtpoled' ),
	'Newpages'                  => array( 'UdedLehtpoled' ),
	'Ancientpages'              => array( 'VanhadLehtpoled' ),
	'Protectedpages'            => array( 'KaitudLehtpoled' ),
	'Protectedtitles'           => array( 'KaitudPälkirjutesed' ),
	'Allpages'                  => array( 'KaikLehtesed' ),
	'Specialpages'              => array( 'SpecialižedLehtpoled' ),
	'Contributions'             => array( 'Tond' ),
	'Categories'                => array( 'Kategorijad' ),
	'Export'                    => array( 'Eksport' ),
	'Version'                   => array( 'Versii' ),
	'Allmessages'               => array( 'KaikTedotused' ),
	'Mypage'                    => array( 'MinunLehtpol\'' ),
	'Mytalk'                    => array( 'MinunLodu' ),
	'Mycontributions'           => array( 'MinunTond' ),
	'Search'                    => array( 'Ectä' ),
);

$magicWords = array(
	'img_right'             => array( '1', 'oiged', 'paremal', 'right' ),
	'img_left'              => array( '1', 'hura', 'vasakul', 'left' ),
	'img_none'              => array( '1', 'eile', 'tühi', 'none' ),
	'img_width'             => array( '1', '$1piks', '$1px' ),
	'img_border'            => array( '1', 'röun', 'ääris', 'border' ),
	'img_top'               => array( '1', 'üläh', 'top' ),
	'img_middle'            => array( '1', 'kesk', 'middle' ),
	'img_bottom'            => array( '1', 'ala', 'bottom' ),
	'sitename'              => array( '1', 'SAITANNIMI', 'KOHANIMI', 'SITENAME' ),
	'grammar'               => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'SUGU:', 'GENDER:' ),
	'plural'                => array( '0', 'ÄILUGU:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'TÄUZ\'URL:', 'KOGUURL:', 'FULLURL:' ),
	'index'                 => array( '1', '__INDEKS__', 'INDEKSIGA', '__INDEX__' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Jonoštada kosketused:',
'tog-highlightbroken'         => 'Ozutada rebitadud kosketused <a href="" class="new">muga,</a> (muite - muga<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Tazoitada tekst lehtpolen levedusen mödhe',
'tog-hideminor'               => 'Peitta pened redakcijad veresiden toižetusiden nimikirjuteses',
'tog-hidepatrolled'           => 'Peitta patruliruidud redakcijad veresiden toižetusiden nimikirjuteses',
'tog-newpageshidepatrolled'   => 'Peitta patruliruidud redakcijad uziden lehtpoliden nimikirjuteses',
'tog-extendwatchlist'         => 'Levitagat kaclendnimikirjutez, miše nähta kaik toižetused.',
'tog-usenewrc'                => 'Kävutagat paremboitud tantoižed toižetused (pidab otta radho JavaScript)',
'tog-numberheadings'          => 'Nomeruida avtomatižikš pälkirjutesed',
'tog-showtoolbar'             => "Ozutada azegiden üläpanel' redaktiruindan aigan (JavaScript)",
'tog-editondblclick'          => 'Redaktiruida lehtpoled kaksitadud plokul (JavaScript)',
'tog-editsection'             => 'Ozutada "Redaktiruida"-kosketuz kaikuččen sekcijan täht',
'tog-editsectiononrightclick' => 'Redaktiruida sekcijad hiren oiktal plokul pälkirjutesele (JavaScript)',
'tog-showtoc'                 => 'Ozutada südäimišt (lehtpoled, kudambil om enamba, mi 3 pälkirjutest)',
'tog-rememberpassword'        => 'Muštta minun kävutajan nimi neciš kompjuteras',
'tog-watchcreations'          => 'Ližata kaik minai sätud lehtpoled minun kaclendkirjuteshe',
'tog-watchdefault'            => 'Ližata kaik minai toižetadud lehtpoled minun kaclendkirjuteshe',
'tog-watchmoves'              => 'Ližata kaik minai udesnimitadud lehtpoled minun kaclendkirjuteshe',
'tog-watchdeletion'           => 'Ližata kaik minai čutud lehtpoled minun kaclendkirjuteshe',
'tog-previewontop'            => 'Panda ezikacund redaktiruindan iknan edehe',
'tog-previewonfirst'          => 'Ozutada ezikacund redaktiruindan augotades',
'tog-nocache'                 => "Kel'ta lehtpoliden keširuind",
'tog-enotifwatchlistpages'    => 'Oigekat minei e-počt, konz lehtpoled minun kaclendnimikirjutesespäi toižetase',
'tog-enotifusertalkpages'     => "Oigeta minei e-počt, konz minun lodulehtpol' toižetase",
'tog-enotifminoredits'        => 'Oigeta minei e-počt eskai siloi, konz toižetused oma minimaližed',
'tog-enotifrevealaddr'        => 'Ozutada minun počtadres tedotuzkirjeižiš',
'tog-shownumberswatching'     => 'Ozutada niiden kävutajiden lugu, kudambad oma mülütanuded lehtpolen ičeze kaclendnimikirjutesihe',
'tog-oldsig'                  => 'Olijan allekirjutesen ezikacund:',
'tog-fancysig'                => 'Ičeze allekirjutesen wiki-znamišt (avtomatižeta kosketuseta)',
'tog-externaleditor'          => 'Kävutada irdredaktor augotižjärgendusen mödhe (vaiše ekspertoiden täht; pidab järgeta specialižikš kompjuter)',
'tog-externaldiff'            => 'Kävutada irdprogramm versijoiden rindatades (vaiše ekspertoiden täht; pidab järgeta specialižikš kompjuter)',
'tog-showjumplinks'           => 'Ližata "hüpähtada..."-abukosketused',
'tog-uselivepreview'          => 'Kävutada hered ezikacund (JavaScript) (Eksperimentaline)',
'tog-forceeditsummary'        => 'Varutada, kunz toižetusen ümbrikirjutandan pöud ei ole täuttud',
'tog-watchlisthideown'        => 'Peitta minun redakcijad kaclendnimikirjutesespäi',
'tog-watchlisthidebots'       => 'Peitta botoiden redakcijad kaclendnimikirjutesespäi',
'tog-watchlisthideminor'      => 'Peitta pened redakcijad kaclendnimikirjutesespäi',
'tog-watchlisthideliu'        => 'Peitta sistemha tulnuziden kävutajiden redakcijad kaclendnimikirjutesespäi',
'tog-watchlisthideanons'      => 'Peitta anonimoiden redakcijad kaclendnimikirjutesespäi',
'tog-watchlisthidepatrolled'  => 'Peitta patruliruidud redakcijad kaclendnimikirjutesespäi',
'tog-nolangconversion'        => 'Saubata kirjutandsistemoiden toižetand',
'tog-ccmeonemails'            => 'Oigeta minei kaikiden minai kirjutadud kirjeižiden kopijad',
'tog-diffonly'                => 'Ala ozuta lehtpolen süadäimištod versijoiden rindatandan al',
'tog-showhiddencats'          => 'Ozutada peittud kategorijad',
'tog-norollbackdiff'          => "Ala ozuta versijoiden eroid endištusen jäl'ghe",

'underline-always'  => 'Kaiken',
'underline-never'   => 'Nikonz',
'underline-default' => 'Kävutada kaclimen järgendused',

# Font style option in Special:Preferences
'editfont-style'     => "Redaktiruindan pöudon šriftan stil':",
'editfont-default'   => 'Järgeline kaclim',
'editfont-monospace' => 'Monoleved šrift',
'editfont-sansserif' => 'Sans-šerifšrift',
'editfont-serif'     => 'Šerifšrift',

# Dates
'sunday'        => 'pühäpäiv',
'monday'        => 'ezmärg',
'tuesday'       => 'tožnarg',
'wednesday'     => 'koumanz’päiv',
'thursday'      => 'nellänz’päiv',
'friday'        => 'videnz’päiv',
'saturday'      => 'sobat',
'sun'           => 'Püh',
'mon'           => 'Ezm',
'tue'           => 'Tož',
'wed'           => 'koum',
'thu'           => 'Nel',
'fri'           => 'Vid',
'sat'           => 'Sob',
'january'       => 'viluku',
'february'      => 'uhoku',
'march'         => 'keväz’ku',
'april'         => 'sulaku',
'may_long'      => 'semendku',
'june'          => 'kezaku',
'july'          => 'heinku',
'august'        => 'eloku',
'september'     => 'sügüz’ku',
'october'       => 'reduku',
'november'      => 'kül’mku',
'december'      => 'tal’vku',
'january-gen'   => 'vilukun',
'february-gen'  => 'uhokun',
'march-gen'     => 'keväz’kun',
'april-gen'     => 'sulakun',
'may-gen'       => 'semendkun',
'june-gen'      => 'kezakun',
'july-gen'      => 'heinkun',
'august-gen'    => 'elokun',
'september-gen' => 'sügüz’kun',
'october-gen'   => 'redukun',
'november-gen'  => 'kül’mkun',
'december-gen'  => 'tal’vkun',
'jan'           => 'Viluku',
'feb'           => 'Uhoku',
'mar'           => "Keväz'ku",
'apr'           => 'Sulaku',
'may'           => 'Semendku',
'jun'           => 'kezaku',
'jul'           => 'heinku',
'aug'           => 'eloku',
'sep'           => "sügüz'ku",
'oct'           => 'reduku',
'nov'           => 'kül’mku',
'dec'           => 'tal’vku',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorii|Kategorijad}}',
'category_header'                => 'Lehtpoled "$1"-kategorijas',
'subcategories'                  => 'Alakategorijad',
'category-media-header'          => 'Mediafailad "$1"-kategorijas',
'category-empty'                 => "''Nece kategorii om nügüd' pall'az.''",
'hidden-categories'              => '{{PLURAL:$1|Peittud kategorii|Peittud kategorijad}}',
'hidden-category-category'       => 'Peittud kategorijad',
'category-subcat-count'          => "{{PLURAL:$2|Neciš kategorijas om vaiše üks' alakategorii.|Neciš kategorijas om {{PLURAL:$1|üks' alakategorii|$1 alakategorijad}}; om kaiked $2 alakategorijad.}}",
'category-subcat-count-limited'  => 'Neciš kategorijas om {{PLURAL:$1|mugoi üks alakategorii| mugomad $1 alakategorijad}}.',
'category-article-count'         => "{{PLURAL:$2|Neciš kategorijas om vaiše üks' ozutadud lehtpol'.| Täs om ozutadud necen kategorijan {{PLURAL:$1|üks' lehtpol'|$1 lehtpol't}}; om kaiked $2.}}",
'category-article-count-limited' => "{{PLURAL:$1|Nece lehtpol' om|$1 nened lehtpoled oma}} neciš kategorijas.",
'category-file-count'            => "{{PLURAL:$2|Neciš kategorijas om vaiše üks' fail.|{{PLURAL:$1|Nece fail om|$1 Nened failad oma}} neciš kategorijas; om kaiked $2.}}",
'category-file-count-limited'    => '{{PLURAL:$1|Nece fail om|$1 Nened failad oma}} neciš kategorijas.',
'listingcontinuesabbrev'         => 'jatktand',
'index-category'                 => 'Indeksiruidud lehtpoled',
'noindex-category'               => 'Indeksiruimatomad lehtpoled',

'mainpagetext'      => "'''MediaWiki-likutim om seižutadud jügedusita.'''",
'mainpagedocfooter' => 'Kc. [http://meta.wikimedia.org/wiki/Help:Kävutajan abukirj], miše sada informacijad wikin kävutamižes.

== Erased tarbhaižed resursad ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Järgendusiden nimikirjutez]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce počtnimikirjutez]',

'about'         => 'Andmused',
'article'       => "Südäimištlehtpol'",
'newwindow'     => '(avaidase udes iknas)',
'cancel'        => 'Heitta pätand',
'moredotdotdot' => 'Edeleze...',
'mypage'        => "Minun lehtpol'",
'mytalk'        => 'Minun lodud',
'anontalk'      => 'Lodud neciš IP-adresas',
'navigation'    => 'Navigacii',
'and'           => '&#32;da',

# Cologne Blue skin
'qbfind'         => 'Ectä',
'qbbrowse'       => 'Kacelta',
'qbedit'         => 'Redaktiruida',
'qbpageoptions'  => 'Necen lehtpolen järgendused',
'qbpageinfo'     => 'Andmused lehtpoles',
'qbmyoptions'    => 'Minun järgendused',
'qbspecialpages' => 'Specialižed lehtpoled',
'faq'            => 'PPK',
'faqpage'        => 'Project:PPK',

# Vector skin
'vector-action-addsection'   => 'Ližada tem',
'vector-action-delete'       => 'Čuta poiš',
'vector-action-move'         => 'Udesnimitada',
'vector-action-protect'      => 'Kaita',
'vector-action-undelete'     => 'Pördutada',
'vector-action-unprotect'    => 'Heitta kaičend',
'vector-namespace-category'  => 'Kategorii',
'vector-namespace-help'      => "Abun lehtpol'",
'vector-namespace-image'     => 'Fail',
'vector-namespace-main'      => "Lehtpol'",
'vector-namespace-media'     => "Medialehtpol'",
'vector-namespace-mediawiki' => 'Kirjeine',
'vector-namespace-project'   => "Projektan lehtpol'",
'vector-namespace-special'   => "Specialine lehtpol'",
'vector-namespace-talk'      => 'Diskussii',
'vector-namespace-template'  => 'Šablon',
'vector-namespace-user'      => "Kävutajan lehtpol'",
'vector-view-create'         => 'Säta',
'vector-view-edit'           => 'Redaktiruida',
'vector-view-history'        => 'Kacta istorii',
'vector-view-view'           => 'Lugeda',
'vector-view-viewsource'     => 'Kacta purtkehe',
'actions'                    => 'Tegendad',
'namespaces'                 => 'Nimiavaruded',
'variants'                   => 'Variantad',

'errorpagetitle'    => 'Petuz',
'returnto'          => 'Pörttas lehtpolele $1.',
'tagline'           => '{{SITENAME}}',
'help'              => 'Abu',
'search'            => 'Ecind',
'searchbutton'      => 'Ectä',
'go'                => 'Mända',
'searcharticle'     => 'Mända',
'history'           => 'Istorii',
'history_short'     => 'Istorii',
'updatedmarker'     => "udištadud minun jäl'gmäižen vizitan jäl'ghe",
'info_short'        => 'Informacii',
'printableversion'  => 'Versii painmižen täht',
'permalink'         => 'Kaikenaigaine kosketuz',
'print'             => 'Painda',
'edit'              => 'Redaktiruida',
'create'            => 'Säta',
'editthispage'      => "Redaktiruida nece lehtpol'",
'create-this-page'  => "Säta nece lehtpol'",
'delete'            => 'Čuta poiš',
'deletethispage'    => "Čuta nece lehtpol'",
'undelete_short'    => "Endištada {{PLURAL:$1|üks' redakcii|$1 redakcijad}}",
'protect'           => 'Kaita',
'protect_change'    => 'toižetada',
'protectthispage'   => "Kaita nece lehtpol'",
'unprotect'         => 'Heitta kaičend',
'unprotectthispage' => 'Heitta necen lehtpolen kaičend',
'newpage'           => 'Uz’ lehtpol’',
'talkpage'          => 'Lodaita necen lehtpolen polhe',
'talkpagelinktext'  => 'Lodu',
'specialpage'       => "Specialine lehtpol'",
'personaltools'     => 'Personaližed azeged',
'postcomment'       => "Uz' jaguz",
'articlepage'       => "Kacu südäimišton lehtpol'",
'talk'              => 'Diskussii',
'views'             => 'Kacundad',
'toolbox'           => 'Azegišt',
'userpage'          => "Kacu kävutajan lehtpol'",
'projectpage'       => "Kacu projektan lehtpol'",
'imagepage'         => "Kacu fajlan lehtpol'",
'mediawikipage'     => "Kacu tedotusen lehtpol'",
'templatepage'      => "Kacu šablonan lehtpol'",
'viewhelppage'      => "Kacu abun lehtpol'",
'categorypage'      => "Kacu kategorijan lehtpol'",
'viewtalkpage'      => 'Kacu diskussii',
'otherlanguages'    => 'Toižil kelil',
'redirectedfrom'    => '(Oigetud lehtpolelpäi $1)',
'redirectpagesub'   => "Oigendai lehtpol'",
'lastmodifiedat'    => "Nece lehtpol' toižetadihe $1, aigal $2.",
'viewcount'         => "Nece lehtpol' kaceltihe {{PLURAL:$1|kerdal|$1 kerdad}}.",
'protectedpage'     => "Saubatud lehtpol'",
'jumpto'            => 'Hüpähtada:',
'jumptonavigation'  => 'navigacii',
'jumptosearch'      => 'Ecind',
'view-pool-error'   => "Pakičem armahtust!
Serverad oma üläkormatud.
Äjahk kävutajid lattäs kacta necidä lehtpol't.
Varastagat pordon aigad i lat'kät pörttas lehtpolele.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Informacii saitas {{SITENAME}}',
'aboutpage'            => 'Project:Informacii',
'copyright'            => 'Südäimišt kävutadas $1-licenzijan mödhe.',
'copyrightpage'        => '{{ns:project}}:tegijan oiktused',
'currentevents'        => 'Nügüdläižed tegod',
'currentevents-url'    => 'Project:Nügüdläižed tegod',
'disclaimers'          => 'Pučind vastusenpidandaspäi',
'disclaimerpage'       => 'Project:Pučind vastusenpidandaspäi',
'edithelp'             => 'Abu redaktiruindas',
'edithelppage'         => 'Help:Abu redaktiruindas',
'helppage'             => 'Help:Südäimišt',
'mainpage'             => 'Pälehtpol’',
'mainpage-description' => 'Pälehtpol’',
'policy-url'           => 'Project:Ohjandimed',
'portal'               => 'Kund',
'portal-url'           => 'Project:Kund',
'privacy'              => 'Konfidencialižusen politik',
'privacypage'          => 'Project:Konfidencialižusen politik',

'badaccess'        => 'Laskendan petuz',
'badaccess-group0' => 'Teile ei sa tehta pakitud tegendad.',
'badaccess-groups' => 'Pakitud tegend sab tehta vaiše {{PLURAL:$2|gruppan|gruppiden}}: $1 ühtnijoile.',

'versionrequired'     => 'Pidab kävutada MediaWikin $1 versii',
'versionrequiredtext' => 'Pidab kävutada MedaWikin $1-versii necen lehtpolen kactes.
Kacu [[Special:Version|informacii kävutadud versijoiš]].',

'ok'                      => 'Ka',
'retrievedfrom'           => 'Purde - "$1"',
'youhavenewmessages'      => 'Tö sat $1 ($2).',
'newmessageslink'         => 'uded tedotused',
'newmessagesdifflink'     => "jäl'gmäine toižetuz",
'youhavenewmessagesmulti' => 'Teil om uzid tedotusid $1-lehtpolel',
'editsection'             => 'redaktiruida',
'editold'                 => 'redaktiruida',
'viewsourceold'           => 'kacta augotižkod',
'editlink'                => 'redaktiruida',
'viewsourcelink'          => 'kacta augotižkod',
'editsectionhint'         => 'Redaktiruida jaguz $1',
'toc'                     => 'Südäimišt',
'showtoc'                 => 'ozutada',
'hidetoc'                 => 'peitta',
'thisisdeleted'           => 'Kacta vai udištada $1?',
'viewdeleted'             => 'Kacta $1?',
'restorelink'             => '{{PLURAL:$1|čutud redakcii|$1 čutud redakcijad}}',
'feedlinks'               => 'Kut:',
'feed-invalid'            => 'Vär ezipakitoitusen kanalan tip.',
'feed-unavailable'        => 'Sindikacijan jonod oma samatomad.',
'site-rss-feed'           => '$1-RSS-jono',
'site-atom-feed'          => '$1-Atom-jono',
'page-rss-feed'           => '"$1" (RSS-jono)',
'page-atom-feed'          => '"$1" (Atom-jono)',
'red-link-title'          => "$1 (mugošt lehtpol't ei ole)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Lehtpol’',
'nstab-user'      => "Kävutajan lehtpol'",
'nstab-media'     => "Medialehtpol'",
'nstab-special'   => "Specialine lehtpol'",
'nstab-project'   => 'Projektan polhe',
'nstab-image'     => 'Fail',
'nstab-mediawiki' => 'Tedotuz',
'nstab-template'  => 'Šablon',
'nstab-help'      => "Abun lehtpol'",
'nstab-category'  => 'Kategorii',

# Main script and global functions
'nosuchaction'      => 'Mugošt tegendad ei ole',
'nosuchactiontext'  => 'URLas ozutadud tegend om petuzline.
Tö olet tehnu petusen URLan kirjutades vai männu värad kosketustme.
Nece voib mugažo ozutada viga {{SITENAME}}-projektan programmištos.',
'nosuchspecialpage' => "Mugošt speciališt lehtpol't ei ole",
'nospecialpagetext' => "<strong>Ectud speciališt lehtpol't ei ole.</strong>

Kc. [[Special:SpecialPages|specialižiden lehtpoliden nimikirj]].",

# General errors
'error'                => 'Petuz',
'databaseerror'        => 'Andmusiden bazan petuz',
'dberrortextcl'        => 'Andmusiden bazas ectes ozaižihe petuz.
Jäl\'gmäine ecind andmusiden bazas oli:
"$1"
funkcijaspäi "$2".
Andmusiden baz pördi petusen "$3: $4"',
'laggedslavemode'      => "Varutuz: voib olda, lehtpolen versijal ei ole jäl'gmäižid ližadusid.",
'readonly'             => 'Andmusiden baz om luklostadud',
'enterlockreason'      => 'Kirjutagat sü da pandud blokiruindan strok',
'missing-article'      => 'Andmuzbazaspäi ei ole löutud ectud lehtpolen tekst: $1 $2.

Mugoi situacii sündub tobjimalaz, konz kävutai ladib lähtta lehtpolen toižetuzistorijaha vanhtunut i čutud kosketustme.

Ku azj ei ole neciš, ka tö, nägub, olet löudnuded vigan programmas. Olgat hüväd, kirjutagat necen polhe [[Special:ListUsers/sysop|administratorale]].
Ližakat URL.',
'missingarticle-rev'   => '(versii № $1)',
'missingarticle-diff'  => '(Ero: $1, $2)',
'readonly_lag'         => 'Andmusiden baz om luklostadud avtomatižešti pordoks aigad, kuni sinhroniziruiše ezmäškerdaine da toškerdaine serverad',
'internalerror'        => 'Südäipetuz',
'internalerror_info'   => 'Südäipetuz: $1',
'fileappenderror'      => 'Ei voi ližata «$1»-failad «$2»-failha.',
'filecopyerror'        => 'Ei voi kopiruida "$1"-failad "$2"-tahoze.',
'filerenameerror'      => 'Ei voi udesnimitada "$1"-failad "$2"-tahoze.',
'filedeleteerror'      => 'Ei voi čuta poiš "$1"-failad.',
'directorycreateerror' => 'Ei voi säta "$1"-failhodrad.',
'filenotfound'         => 'Ei voi löuta "$1"-failad.',
'fileexistserror'      => 'Ei voi kirjutada "$1"-failha: mugoi fail om wikiš',
'unexpected'           => 'Varastamatoi znamočend: "$1"="$2".',
'formerror'            => 'Petuz: ei voi oigeta forman admusid',
'badarticleerror'      => 'Necidä ei sa tehta necil lehtpolel.',
'cannotdelete'         => 'Ei voi čuta poiš necidä lehtpol\'t vai "$1"-failad.
Sen om jo čutnu ken-se toine.',
'badtitle'             => 'Hond nimi',
'badtitletext'         => 'Ectud lehtpolen nimi om vär, vai mugošt nimed ei ole, vai kelidenkeskeline (interwiki) nimi om kirjutadud värin.
Siš voib olda simvoloid, kudambid ei sa panda nimihe.',
'perfcached'           => 'Nene andmused oma kešespäi i voidas olda vanhtunuzin.',
'perfcachedts'         => "Nened andmused oma kešespäi, ned oma uzištadud jäl'gmäižel kerdal siloi: $1.",
'querypage-no-updates' => "Necen lehtpolen udištand ei ole nügüd' kävutamas.
Anttud naku andmused ei udištugoi.",
'wrong_wfQuery_params' => 'Värad parametrad necen funkcijan täht: wfQuery()<br />
Funkcii: $1<br />
Ecind: $2',
'viewsource'           => 'Kc. purde',
'viewsourcefor'        => '$1-lehtpolele',
'actionthrottled'      => 'Tegendan piguz om kaidetud',
'protectedpagetext'    => "Nece lehtpol' om luklostadud, miše kaita se redaktiruindaspäi.",
'viewsourcetext'       => 'Sab lugeda da kopiruida necen lehtpolen augotižtekst:',
'protectedinterface'   => 'Necil lehtpolel om programman interfeistedotuz. Se om luklostadud, miše kaita se vandalizmaspäi.',
'editinginterface'     => "'''Homaikat:''' Tö ladit redaktiruida lehtpol't, kudambal om programman interfeistekst.
Mugoi tegend toižetab interfeisan irdnägu toižiden kävutajiden täht.
Kändmižen täht om paremb kävutada [http://translatewiki.net/wiki/Main_Page?setlang=vep translatewiki.net] - MediaWikin lokalizacijan projekt.",
'sqlhidden'            => '(SQL-küzelend om peittud)',
'namespaceprotected'   => "Teil ei ole oiktust redaktiruida lehtpolid '''$1'''-nimiavaruses.",
'customcssjsprotected' => "Teile ei sa redaktiruida necidä lehtpol't, sikš miše sil om toižen kävutajan personaližid järgendusid.",
'ns-specialprotected'  => 'Ei sa redaktiruida specialižid lehtpolid.',
'titleprotected'       => '[[User:$1|$1]]-kävutai om kel\'nu mugoižen lehtpolen pälkirjutesen kävutamižen.
Sü om "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Järgendusen petuz: tundmatoi virusoiden skaner: ''$1''",
'virus-scanfailed'     => 'Skaniruindan petuz (kod $1)',
'virus-unknownscanner' => 'tundmatoi antivirus:',

# Login and logout pages
'logouttext'              => "'''Tö olet lähtnuded sistemaspäi.'''

Sab jatkta rad {{SITENAME}}-saital anonimižikš, vai [[Special:UserLogin|kirjutagatoiš udes]] sil-žo vai toižel kävutajan nimel.
Otkat sil'mnägubale, miše erasid lehtpolid ozutaškatas mugažo, kut i edel teiden lähtendad sistemaspäi. Miše vajehtada niiden nägu, puhtastagat teiden kaclimen keš.",
'welcomecreation'         => '== Tulgat tervhen, $1! ==
Teiden registracii om loptud.
Algat unohtagoi [[Special:Preferences|järgeta personaližikš]] sait.',
'yourname'                => 'Kävutajan nimi:',
'yourpassword'            => 'Peitsana:',
'yourpasswordagain'       => 'Kirjutagat peitsana udes:',
'remembermypassword'      => 'Panda muštho minun tulendandmused neciš kompjuteras',
'yourdomainname'          => 'Teiden domen:',
'externaldberror'         => 'Ozaižihe petuz autentifikacijan, kudamb tehtihe andmusiden irdbazan turbiš, aigan, vai teile ei ulotu oiktusid toižetada ičetoi irdregistracijad.',
'login'                   => 'Kirjutadas sistemha',
'nav-login-createaccount' => 'Kirjutadas / Sada registracii',
'loginprompt'             => 'Pidab laskta sada "cookies", miše kirjutadas {{SITENAME}}he.',
'userlogin'               => 'Kirjutadas / Sada registracii',
'userloginnocreate'       => 'Kirjutagatoiš sistemha',
'logout'                  => 'Lähtta',
'userlogout'              => 'Lähtta',
'notloggedin'             => 'Tö et olgoi kirjutanus sistemha',
'nologin'                 => "Ku tö et völ olgoi sanuded registracijad, '''$1'''.",
'nologinlink'             => 'Sada registracii',
'createaccount'           => 'Sada registracii',
'gotaccount'              => "Ku teil om jo registracii, '''$1'''.",
'gotaccountlink'          => 'Kirjutagatoiš sistemha',
'createaccountmail'       => 'e-počtaiči',
'badretype'               => 'Teil kirjutadud peitsanad ei kožugoi toine toižhe.',
'userexists'              => 'Kirjutadud kävutajan nimi om jo rezerviruidud.
Olgat hüväd, valikat toine kävutajan nimi.',
'loginerror'              => 'Sistemha tulendan peituz.',
'createaccounterror'      => 'Ei voi säta registracijad: $1',
'nocookieslogin'          => '{{SITENAME}}-sait kävutab "cookie"-failad, miše tundištada kävutajid.
Tö olet saupnuded "cookie"-failad.
Otkat ned kävutamižhe i toštkat teiden tegend.',
'noname'                  => 'Tö ei olgoi kirjutanuded lasktud kävutajan nimed.',
'loginsuccesstitle'       => 'Tulend sistemha om lopnus satusekahas.',
'loginsuccess'            => "'''Tö radat nügüd' {{SITENAME}}-saital kut \"\$1\".'''",
'nosuchuser'              => 'Ei ole kävutajad "$1"-nimenke.
Kävutajan nimiden oigedkirjutamine rippub kirjamiden registraspäi.
Kodvgat teiden oigedkirjutamine, vai [[Special:UserLogin/signup|säkat uz\' registracii]].',
'nosuchusershort'         => 'Ei ole kävutajad "<nowiki>$1</nowiki>"-nimenke.
Kodvgat teiden oigedkirjutamine.',
'nouserspecified'         => 'Pidab kirjutada kävutajan nimi.',
'login-userblocked'       => "Nece kävutai om blokiruidud. Tulend sistemha om kel'tud.",
'wrongpassword'           => 'Peitsana om vär.
Kirjutagat se völ kerdan.',
'wrongpasswordempty'      => "Nece peitsana om pall'az.
Kirjutagat toine peitsana.",
'passwordtooshort'        => 'Peitsanha pidab mülütada {{PLURAL:$1|1 znam|$1 znamad}}.',
'password-name-match'     => 'Teiden peitsanale pidab erineda kävutajan nimespäi.',
'mailmypassword'          => "Oigeta minei uz' peitsana e-počtadme",
'passwordremindertitle'   => "Uz' pordaigaline peitsana {{SITENAME}}-saitan täht",
'noemail'                 => '"$1"-kävutai ei ole andnu ičeze e-počtan adresad.',
'noemailcreate'           => 'Bidab kirjutada todesine e-počtan adres',
'passwordsent'            => "Uz' peitsana om oigetud $1-kävutajan e-počtan adresale.
Olgat hüväd, kirjutagatoiš sistemha, konz sat sen.",
'blocked-mailpassword'    => "Redaktiruind teiden IP-adresalpäi om kel'tud, peitsanan udištandan funkcii om mugažo blokiruidud, miše kaitas abidoičendaspäi.",
'eauthentsent'            => 'Vahvištoituzkirjeine om oigetud teiden adresale. Kirjeižes om mugažo kirjutadud, midä pidab tehta, miše vahvištoitta teiden registracijad.',
'mailerror'               => 'E-počtan oigendamižen petuz: $1',
'emailauthenticated'      => 'Teiden e-počtan adres vahvištoittihe datal $2 aigal $3.',
'emailnotauthenticated'   => 'Teiden e-počtan adres ei ole völ vahvištoittud.
Wiki-likutimen počtfunkcijad ei olgoi kävutamas.',
'noemailprefs'            => 'Kirjutagat e-počtan adres teiden järgendusihe, miše se oliži kävutamas.',
'emailconfirmlink'        => 'Vahvištoitkat teiden e-počtan adres',
'invalidemailaddress'     => 'Ningomal e-počtan adresal om vär format. Olgat hüväd, kirjutagat e-počtan adres oiktas formatas vai puhtastagat e-počtan pöud.',
'accountcreated'          => 'Registracii om OK',
'accountcreatedtext'      => 'Registracii $1-kävutajan täht om sätud.',
'createaccount-title'     => '{{SITENAME}}: registracijan sädand.',
'usernamehasherror'       => 'Kävutajan nimes ei voi olda mugošt znamad.',
'login-throttled'         => 'Tö olet tehnu äjahkon naprindoid kirjutadas sistemha.
Olgat hüväd, varastagat pordon aigad edel ut naprindad.',
'loginlanguagelabel'      => 'Kel’: $1',

# Password reset dialog
'resetpass'                 => 'Vajehtada peitsana',
'resetpass_announce'        => "Tö olet kirjutanus sistemha sen pordaigaližen peitsanan abul, kudamb oli oigetud teile e-počtadme.
Miše tulda sistemha lopuližikš, teile pidab säta uz' peitsana naku:",
'resetpass_text'            => '<!-- Ližagat tekstad nakhu -->',
'resetpass_header'          => 'Peitsanan vajehtuz',
'oldpassword'               => 'Vanh peitsana:',
'newpassword'               => "Uz' peitsana:",
'retypenew'                 => "Toštkat uz' peitsana:",
'resetpass_submit'          => 'Säta peitsana da kirjutadas sistemha',
'resetpass_success'         => 'Teiden peisana om vajehtadud jügedusita! Tulend sistemha...',
'resetpass_forbidden'       => 'Ei voi vajehtada peitsanad',
'resetpass-no-info'         => 'Miše kirjutada necil lehtpolel, teile pidab kirjutadas sistemha.',
'resetpass-submit-loggedin' => 'Vajehtada peitsana',
'resetpass-submit-cancel'   => 'Heitta pätand',
'resetpass-wrong-oldpass'   => 'Vär pordaigaine vai nügüdläine peitsana.
Voib olda, tö olet jo toižetanuded peitsanan vai pakinuded uden peitsanan.',
'resetpass-temp-password'   => 'Pordaigaline peitsana:',

# Edit page toolbar
'bold_sample'     => 'Lihavoitud tekst',
'bold_tip'        => 'Lihavoitud tekst',
'italic_sample'   => 'Kursivtekst',
'italic_tip'      => 'Kursivtekst',
'link_sample'     => 'Kosketusen pälkirjutez',
'link_tip'        => 'Südäikosketuz',
'extlink_sample'  => 'http://www.example.com kosketusen pälkirjutez',
'extlink_tip'     => 'Irdkosketuz (muštkat prefiksas http://)',
'headline_sample' => 'Pälkirjutesen tekst',
'headline_tip'    => 'Toižen pindan pälkirjutez',
'math_sample'     => 'Pangat formul nakhu',
'math_tip'        => 'Matematine formul (LaTeX-formatas)',
'nowiki_sample'   => 'Pangat formatiruimatoman tekstan nakhu',
'nowiki_tip'      => 'Ignoriruida wiki-formatiruind',
'image_sample'    => 'Ozutez.jpg',
'image_tip'       => 'Mülütadud fail',
'media_sample'    => 'Ozutez.ogg',
'media_tip'       => 'Kosketuz mediafailale',
'sig_tip'         => 'Teiden allekirjutez da tarkoiktan aigan vestatez',
'hr_tip'          => 'Gorizontaline pird (algat kävutagoi paksus)',

# Edit pages
'summary'                          => 'Toižetusiden ümbrikacund:',
'subject'                          => 'Tem/pälkirjutez:',
'minoredit'                        => "Nece om pen' redakcii",
'watchthis'                        => "Kacelta necidä lehtpol't",
'savearticle'                      => "Kirjutada lehtpol'",
'preview'                          => 'Ezikacund',
'showpreview'                      => 'Ozutada ezikacund',
'showlivepreview'                  => 'Hered ezikacund',
'showdiff'                         => 'Ozutada toižetused',
'anoneditwarning'                  => "'''Homaikat:''' Tö et olgoi kirjutanus sistemha.
Teiden IP-adres om kirjutadud necen lehtpolen redaktiruindan istorijaha.",
'missingsummary'                   => "'''Muštatez:''' Tö et olgoi andnuded toižetusiden lühüdad ümbrikacundad.
Ku tö valičet völ kerdan \"Kirjutada lehtpol'\", ka teiden toižetused kirjutase ningoižeta ümbrikacundata.",
'missingcommenttext'               => 'Olgat hüväd, pangat teiden tedotuz alahaks.',
'missingcommentheader'             => "'''Muštatez:''' Tö et olgoi andnuded toižetusiden ümbrikacundoiden pälkirjutest.
Ku tö valičet völ kerdan \"Kirjutada lehtpol'\", ka teiden toižetusiden ümbrikacund kirjutase ningoižeta pälkirjuteseta.",
'summary-preview'                  => 'Ümbrikacund linneb mugoi:',
'subject-preview'                  => 'Ümbrikacundan pälkirjutez linneb mugoi:',
'blockedtitle'                     => 'Kävutai om blokiruidud',
'blockedtext'                      => "'''Teiden kävutajan nimi vai IP-adres om blokiruidud.'''

Teid om blokiruinu $1.
Sü: ''$2''.

* Blokiruindan augotižaig: $8
* Blokiruindan lopaig: $6
* Blokiruidud kävutai: $7

Otkat sil'mnägubale, miše teile ei sa oigeta kirješt administratorale, ku tö et olgoi sanuded registracijad i et olgoi vahvištoitnuded ičetoi e-počtan adresad
[[Special:Preferences|teiden järgendusiden lehtpolel]], vai teile oma kel'tnuded kirjeižid oigendamha blokiruindan aigan.
Teiden IP-adres om $3, teiden blokiruindan ID om #$5.
Olgat hüväd, kirjutagat nened andmused kaikiš kirjeižiš administratorile.",
'autoblockedtext'                  => "Teiden IP-adres om blokiruidud avtomatižešti, sikš miše sen oli kävutanu toine kävutai; se kävutai om blokiruidud $1-kävutajal.

Sü: ''$2''.

* Blokiruindan augotižaig: $8
* Blokiruindan lopaig: $6
* Blokiruidud kävutai: $7

Otkat sil'mnägubale, miše teile ei sa oigeta kirješt administratorale, ku tö et olgoi sanuded registracijad i et olgoi vahvištoitnuded ičetoi e-počtan adresad
[[Special:Preferences|teiden järgendusiden lehtpolel]], vai teile oma kel'tnuded kirjeižid oigendamha blokiruindan aigan.
Teiden IP-adres om $3, teiden blokiruindan ID om #$5.
Olgat hüväd, kirjutagat nened andmused kaikiš kirjeižiš administratorile.",
'blockednoreason'                  => 'Ei ole kirjutadud ni-mittušt süd.',
'blockedoriginalsource'            => "'''$1'''-lehtpolen tekst:",
'blockededitsource'                => "'''Teiden $1-lehtpolen toižetusiden''' tekst:",
'whitelistedittitle'               => 'Kirjutagatoiš sistemha, miše redaktiruida',
'whitelistedittext'                => 'Teile pidab $1, miše redaktiruida lehtpolid.',
'confirmedittext'                  => 'Tarbiž vahvištoitta e-počtan adres, miše redaktiruida lehtpolid.
Olgat hüväd, kirjutagat da vahvištoitkat teiden e-počtan adres [[Special:Preferences|järgendusiden lehtpolel]].',
'nosuchsectiontitle'               => 'Ei voi löuta mugošt jagust',
'nosuchsectiontext'                => 'Tö ladit redaktiruida olmatont lehtpolen jagust.
Voib olda, se om sirtud vai čutud poiš lugemižen aigan.',
'loginreqtitle'                    => 'Pidab kirjutadas sistemha',
'loginreqlink'                     => 'kirjutagatoiš sistemha',
'loginreqpagetext'                 => 'Teile pidab $1, miše lugeda toižid lehtpolid.',
'accmailtitle'                     => 'Peitsana om oigetud.',
'newarticle'                       => '(Uz’)',
'newarticletext'                   => "Tö tulit kosketustme lehtpolele, kudamb ei olend tehtud völ.
Miše säta lehtpol', kirjutaškakat alemba sijatud iknas (kc. [[{{MediaWiki:Helppage}}|abun lehtpol']], miše sada ližainformacijad).
Ku tö olet petnus tänna tuldes, pingat teiden kaclimen '''tagaze'''-kingitimele.",
'noarticletext'                    => 'Necil lehtpolel ei ole nügüd\' tekstad.
Tö voit [[Special:Search/{{PAGENAME}}|ectä nece nimi]] toižil lehtpolil,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ectä pojavid aigkirjoiden kirjutesid],
vai [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaktiruida nece lehtpol\']</span>.',
'userpage-userdoesnotexist'        => "Kävutajan nimed «$1» ei ole. Todeks-ik tahtoit säta vai toižetada nece lehtpol'?",
'userpage-userdoesnotexist-view'   => '"$1"-kävutai ei ole registriruidud.',
'usercssyoucanpreview'             => "'''Nevond:''' Kävutagat 'Ozutada ezikacund'-kingitim, miše kodvda teiden uz' CSS edel mušthopanendad.",
'userjsyoucanpreview'              => "'''Nevond:''' Kävutagat 'Ozutada ezikacund'-kingitim, miše kodvda teiden uz' JS edel mušthopanendad.",
'usercsspreview'                   => "'''Muštkat, miše nece om vaiše teiden CSS-failan ezikacund, se ei ole völ kirjutadud muštho!'''",
'userjspreview'                    => "'''Muštkat, miše nece om vaiše teiden JavaScript-failan ezikacund, se ei ole völ kirjutadud muštho!'''",
'updated'                          => '(Udištadud)',
'note'                             => "'''Homaičend:'''",
'previewnote'                      => "'''Muštkat, miše nece om vaiše ezikacund. Teiden toižetused ei olgoi völ kirjutadud!'''",
'previewconflict'                  => "Tekst redaktiruindan üläiknas kuvazub neche ezikacundha muga, kut se nägub lopkirjutamižen jäl'ghe.",
'editing'                          => '$1-lehtpolen redaktiruind',
'editingsection'                   => '$1-lehtpolen redaktiruind (jaguz)',
'editingcomment'                   => "$1-lehtpolen redaktiruind (uz' jaguz)",
'editconflict'                     => 'Redaktiruindan konflikt: $1',
'yourtext'                         => 'Teiden tekst',
'storedversion'                    => 'Muštho pandud versii',
'nonunicodebrowser'                => "'''HOMAIKAT: Teiden kaclim ei kävuta Unikodad.'''
Kirjutesiden redaktiruindan aigan kaikiden Unikod-simvoliden (kudambad ei olgoi ASCII:š) sijas ozutadas niiden geksadecimaližid ekvivalentoid.",
'editingold'                       => "'''VARUTUZ: tö redaktiruit necen lehtpolen vanhtunut versijad.'''
Ku tö kirjutat sen muštho, kaik möhemba tehtud toižetused kadodas.",
'yourdiff'                         => 'Erod',
'copyrightwarning'                 => "Olgat hüväd, otkat sil'mnägubale, miše kaik kirjutesen tekstan toižetused da ližadused tekstha arvostadas, kut tehtud $2-licenzijan alusel (kc. $1, miše tedištada detalid).
Ku tö et tahtkoi, miše teiden tekstad levitaižiba da redaktiruižiba miččed taht kävutajad, algat pangoi niid tänna.
Tö vahvištoitat mugažo, miše olet toižetusiden avtoran vai olet kopiruinuded tekstad joudjas purtkespäi.

'''ALGAT SIJAKOI LASKMATA AVTORANOIKTUSEL KAITUD MATERIALOID!'''",
'copyrightwarning2'                => "Olgat hüväd, otkat sil'mnägubale, miše kaik kirjutesen tekstan toižetused da ližadused tekstha arvostadas, kut tehtud licenzijan alusel (kc. $1, miše tedištada detalid).
Ku tö et tahtkoi, miše teiden tekstad levitaižiba da redaktiruižiba miččed taht kävutajad, algat pangoi niid tänna.
Tö vahvištoitat mugažo, miše olet toižetusiden avtoran vai olet kopiruinuded tekstad joudjas purtkespäi.

'''ALGAT SIJAKOI LASKMATA AVTORANOIKTUSEL KAITUD MATERIALOID!'''",
'longpagewarning'                  => "'''HOMAIKAT:''' Necen lehtpolen suruz om $1 kb;
erasil kaclimil om problemoid, konz ned kaceltas 32 kb ülitajid lehtpolid.
Olgat hüväd, jagakat lehtpol' penembihe paloihe.",
'longpageerror'                    => "'''PETUZ: Teiden tekstan suruz om $1 kb, a lasktud maksimum om $2 kb.'''
Ei voi kirjutada muštho.",
'protectedpagewarning'             => "'''VARUTUZ: nece lehtpol' om luklostadud, sidä voidas redaktiruida vaiše administratorad.'''
Alemba om anttud jäl'gmäine aiglehtesen kirjutez:",
'semiprotectedpagewarning'         => "'''Tedotuz:''' Nece lehtpol' om kaitud redaktiruindaspäi; vaiše registriruidud kävutajad voidas redaktiruida sidä.
Alemba om anttud jäl'gmäine aiglehtesen kirjutez:",
'cascadeprotectedwarning'          => "'''Homaikat:''' Nece lehtpol' om luklostadud muga, miše vaiše kävutajad administratoriden privilegijoidenke voidas
redaktiruida sidä, sikš miše se oli mülütadud {{PLURAL:$1|neche lehtpol'he, kudamban|nenihe lehtpolihe, kudambiden}}
täht oli sätud kaskadkaičend:",
'titleprotectedwarning'            => "'''Homaikat: Nece lehtpol' om luklostadud, sikš pidab sada [[Special:ListGroupRights|specialižed oiktused]], miše säta se.'''
Alemba om anttud jäl'gmäine aiglehtesen kirjutez:",
'templatesused'                    => 'Necil lehtpolel kävutadud {{PLURAL:$1|šablon|šablonad}}:',
'templatesusedpreview'             => 'Ezikactud lehtpolel kävutadud {{PLURAL:$1|šablon|šablonad}}:',
'templatesusedsection'             => 'Neciš sekcijas kävutadud {{PLURAL:$1|šablon|šablonad}}:',
'template-protected'               => '(kaitud)',
'template-semiprotected'           => '(kaitud anonimoišpäi da uziš kävutajišpäi)',
'hiddencategories'                 => "Nece lehtpol' om {{PLURAL:$1|1 peittud kategorijaspäi|$1 peittud kategorijoišpäi}}:",
'edittools'                        => '<!-- Tänna sijatud tekst ozutadas redaktiruindan da jügutoitandan formiden al. -->',
'nocreatetitle'                    => 'Lehtpoliden sädand om kaidetud',
'nocreatetext'                     => "{{SITENAME}}-saitas uziden lehtpoliden sädand om kaidetud.
Tö voit pörttas tagaze i redaktiruida toine lehtpol', vai [[Special:UserLogin|kirjutagatoiš sistemha, vai säkat registracii]].",
'nocreate-loggedin'                => 'Teile ei sa säta uzid lehtpolid.',
'sectioneditnotsupported-title'    => "Jagadusiden redaktiruind ei ole pid'oiteldud.",
'sectioneditnotsupported-text'     => "Necil lehtpolel jagadusiden redaktiruind ei ole pid'oiteldud.",
'permissionserrors'                => 'Oiktusiden petused',
'permissionserrorstext'            => 'Teile ei sa tehta muga {{PLURAL:$1|necen sün|neniden süiden}} tagut:',
'permissionserrorstext-withaction' => 'Teile ei sa $2 {{PLURAL:$1|necen sün|neciden süiden}} tagut:',
'moveddeleted-notice'              => "Nece lehtpol' om čutud poiš.
Alemba om anttud lehtpolen čudandan vai sirdandan istorii kut abuandmused.",
'log-fulllog'                      => 'Ozutada kaclendnimikirjutez kogonaz',
'edit-hook-aborted'                => 'Redakcijan azoti sabustaiprogramm.
Ei ole ližasüid.',
'edit-gone-missing'                => "Ei voi udištada lehtpol't.
Voib olda, se om jo čutud.",
'edit-conflict'                    => 'Redakcijoiden konflikt.',
'edit-no-change'                   => 'Teiden redakcii ignoriruidihe, sikš miše tekst ei ole toižetadud.',
'edit-already-exists'              => "Ei voi säta uz' lehtpol'.
Wikiš om jo mugoi lehtpol'.",

# Parser/template warnings
'expensive-parserfunction-category'       => 'Lehtpoled, kudambil kävutadas äjahko resursokahid funkcijoid',
'post-expand-template-inclusion-warning'  => 'Varutuz: lehtpolele pandud šablonoiden suruz om surehk.
Ei voi mülütada erasid šablonoid.',
'post-expand-template-inclusion-category' => 'Lehtpoled, kudambiden mülütadud šablonoiden suruz om ülitadud',
'post-expand-template-argument-category'  => 'Lehtpoled, kudambil om keskhe jättud šablonoiden argumentid',
'parser-template-loop-warning'            => "Sol'm om löutud šablonas: [[$1]]",
'parser-template-recursion-depth-warning' => 'Šablonan rekursijan süvuzröun om ülitadud ($1)',

# "Undo" feature
'undo-failure' => 'Ei voi tühjištada redakcijad, sikš miše läbiredakcijad konfliktuidas.',
'undo-norev'   => "Ei voi endištada lehtpol't, sikš miše mugošt lehtpol't ei ole vai se om jo čutud.",

# Account creation failure
'cantcreateaccounttitle' => 'Ei voi säta registracijad',

# History pages
'viewpagelogs'           => 'Ozutada aigkirjad necen lehtpolen täht',
'nohistory'              => 'Necen lehtpolen täht ei ole toižetusiden aigkirjad.',
'currentrev'             => 'Nügüdläine versii',
'currentrev-asof'        => 'Nügüdläine versii $1',
'revisionasof'           => 'Versii $1',
'revision-info'          => 'Tehtud $1 $2-kävutajan versii',
'previousrevision'       => '← Enzne versii',
'nextrevision'           => 'Udemb versii →',
'currentrevisionlink'    => 'Nügüdläine versii',
'cur'                    => 'nüg.',
'next'                   => 'toine',
'last'                   => 'enzne',
'page_first'             => 'ezmäine',
'page_last'              => "jäl'gmäine",
'histlegend'             => "Valičend: virgakat versijad, miše rindatada niid, i paingat \"Mända\"-kosketimele alahan.<br />
Znamoičendad: '''({{int:cur}})''' = erod nügüdläižes versijaspäi, '''({{int:last}})''' = erod enččes vepsijaspäi, '''{{int:minoreditletter}}''' = pen' toižetuz.",
'history-fieldset-title' => 'Lehtelta istorii',
'history-show-deleted'   => 'Vaiše čutud',
'histfirst'              => 'Kaikiš vanhembad',
'histlast'               => 'Tantoižed',
'historysize'            => '({{PLURAL:$1|1 bait|$1 baitad}})',
'historyempty'           => "(pall'az)",

# Revision feed
'history-feed-title'          => 'Toižetusiden istorii',
'history-feed-description'    => 'Necen lehtpolen toižetusen istorii',
'history-feed-item-nocomment' => '$1 aigal $2',
'history-feed-empty'          => "Ectud lehtpol't ei ole.
Sidä voiži čuta wikispäi vai nimitada udes.
Naprigat [[Special:Search|ectä wikišpäi]] sättujid lehtpolid.",

# Revision deletion
'rev-deleted-comment'         => '(komment om čutud poiš)',
'rev-deleted-user'            => '(avtoran nimi om čutud poiš)',
'rev-deleted-event'           => '(kirjutez om čutud poiš)',
'rev-deleted-text-permission' => "Nece lehtpolen versii om '''čutud'''.
Voib olda, süd oma anttud [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} čudandoiden aigkirjas].",
'rev-deleted-text-view'       => "Nece lehtpolen versii om '''čutud'''.
Tö voit lugeda sidä, sikš ku olet saitan administrator.
Voib olda, süd oma anttud [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} čudandoiden aigkirjas].",
'rev-deleted-no-diff'         => "Tö et voigoi nähta necidä versijoiden erod, sikš miše üks' toižetusišpäi om '''čutud'''.
Detalid voidas olda [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} čudandoiden aigkirjas].",
'rev-delundel'                => 'ozutada/peitta',
'rev-showdeleted'             => 'ozutada',
'revisiondelete'              => 'Čuta poiš/endištada lehtpolen versijad',
'revdelete-nooldid-title'     => 'Vär metversii',
'revdelete-nooldid-text'      => 'Tö et olgoi valinuded metversijad (metversijoid) necen funkcijan oigetes.',
'revdelete-nologtype-title'   => 'Tö et olgoi andnuded aigkirjan tipad',
'revdelete-nologtype-text'    => 'Tö et olgoi andnuded aigkirjan tipad, kudambas pidab tehta se tegend.',
'revdelete-nologid-title'     => 'Vär kirjutez aigkirjas',
'revdelete-nologid-text'      => 'Tö et olgoi andnuded aigkirjan metkirjutest tegendan tehtes vai anttud kirjutest ei ole.',
'revdelete-no-file'           => 'Mugošt failad ei ole.',
'revdelete-show-file-submit'  => 'Ka',
'revdelete-selected'          => "'''{{PLURAL:$2|Valitud versii|Valitud versijad}} lehtpolišpäi [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Valitud kirjutez aigkirjas|Valitud kirjutesed aigkirjas}}:'''",
'revdelete-text'              => "'''Čutud versijad ozutadas lehtpolen istorijas da aigkirjoiš, no järgeližed lugijad ei voiškakoi nähta niiden südäimišton erasid paloid.'''
Administratorad voiškatas lugeda peittud südäimištod da endištada sidä necen interfeisan kal't siloi, konz ei ole ližakaidendusid.",
'revdelete-legend'            => 'Säta kaidendused',
'revdelete-hide-text'         => 'Peitta necen lehtpolen versijan tekst',
'revdelete-hide-image'        => 'Peitta failan südäimišt',
'revdelete-hide-name'         => 'Peitta tegend da sen objekt',
'revdelete-hide-comment'      => 'Peitta toižetusiden kirjutez',
'revdelete-hide-user'         => 'Peitta avtoran nimi/IP',
'revdelete-hide-restricted'   => 'Peitta andmusid eskai administratorilpäi-ki',
'revdelete-radio-same'        => '(ala toižeta)',
'revdelete-radio-set'         => 'Ka',
'revdelete-radio-unset'       => 'Ei',
'revdelete-suppress'          => 'Peitta administratorilpäi-ki sadud andmused',
'revdelete-unsuppress'        => 'Heitta kaidendused endištadud versijoilpäi',
'revdelete-log'               => 'Sü:',
'revdelete-submit'            => 'Lattä valitud {{PLURAL:$1|versijaha|versioihe}}',
'revdelete-logentry'          => "Om vajehtanu sen, kut nägub lehtpol' [[$1]]",
'logdelete-logentry'          => 'Om toižetanu sen, kut nägub tego [[$1]]',
'revdelete-success'           => "'''Versijan nägulad om toižetadud tugedusita.'''",
'revdelete-failure'           => "'''Ei voi toižetada versijan läguladud:'''
$1",
'logdelete-success'           => "'''Aigkirjutesen nägulad om toižetadud.'''",
'logdelete-failure'           => "'''Ei voi toižetada aigkirjutesen näguladud:'''
$1",
'revdel-restore'              => 'Toižetada nägubuz',
'pagehist'                    => 'Lehtpolen istorii',
'deletedhist'                 => 'Čudandoiden istorii',
'revdelete-content'           => 'südäimišt',
'revdelete-summary'           => 'kaik toižetused',
'revdelete-uname'             => 'kävutajan nimi',
'revdelete-restricted'        => 'kaidendused administratoriden täht',
'revdelete-unrestricted'      => 'kaidendused heittud administratoriden täht',
'revdelete-hid'               => 'peittud $1',
'revdelete-unhid'             => 'avaitud $1',
'revdelete-log-message'       => '$1 $2 {{PLURAL:$2|versijan|versijoiden}} täht',
'logdelete-log-message'       => '$1 $2 {{PLURAL:$2|tegendan|tegendoiden}} täht',
'revdelete-hide-current'      => '$2, $1-kirjutesen peitmižen petuz: nece versii om nügüdläine.
Sidä ei sa peitta.',
'revdelete-show-no-access'    => '$2, $1-kirjutesen ozutamižen petuz: nece kirjutez om znamoitud "kaidetud".
Tö ei voigoi kävutada sidä.',
'revdelete-modify-no-access'  => '$2, $1-kirjutesen toižetamižen petuz: nece kirjutez om znamoitud "kaidetud".
Tö ei voigoi kävutada sidä.',
'revdelete-modify-missing'    => 'ID $1-kirjutesen kirjutamižen petuz: sidä ei ole andmusiden bazas!',
'revdelete-otherreason'       => 'Toine sü/ližasü',
'revdelete-reasonotherlist'   => 'Toine sü:',
'revdelete-edit-reasonlist'   => 'Redaktiruida čudandan süd',
'revdelete-offender'          => 'Lehtpolen versijan avtor:',

# Suppression log
'suppressionlog' => 'Peitandoiden aigkirj',

# History merging
'mergehistory'                     => 'Ühtenzoitta lehtpoliden istorijad',
'mergehistory-box'                 => 'Ühtenzoitta kahten lehtpolen toižetusiden istorijad:',
'mergehistory-from'                => "Lähtmižlehtpol':",
'mergehistory-into'                => "Metlehtpol':",
'mergehistory-list'                => 'Toižetusiden istorii, kudambad ühtenzoittas',
'mergehistory-go'                  => 'Ozutada toižetusid, kudambid ühtenzoittas',
'mergehistory-submit'              => 'Ühtenzoitta redakcijad',
'mergehistory-empty'               => 'Ei voi löuta redakcijoid ühtenzoitandan täht.',
'mergehistory-success'             => '$3 {{PLURAL:$3|redakcii om|redakcijad oma}} sirtud satusekahas [[:$2]]-lahtpolele. Ühthine redakcijoiden lugu om [[:$1]].',
'mergehistory-fail'                => 'Ei voi ühtenzoitta lehtpoliden istorijoid, olgat hüväd, kodvgat lehtpolen da aigan parametrad.',
'mergehistory-no-source'           => "$1-augotižlehtpol't ei ole.",
'mergehistory-no-destination'      => "$1-metlehtpol't ei ole.",
'mergehistory-invalid-source'      => 'Augotižlehtpolele pidab annta todesine nimi.',
'mergehistory-invalid-destination' => 'Metlehtpolele pidab antta todesine nimi.',
'mergehistory-autocomment'         => 'Vei [[:$1]] [[:$2]]-lehtpolele',
'mergehistory-comment'             => 'Vei [[:$1]] [[:$2]]-lehtpolele: $3',
'mergehistory-same-destination'    => 'Augotižlehpolen da metlehpolen keskes pidab tehta eroid',
'mergehistory-reason'              => 'Sü:',

# Merge log
'mergelog'           => 'Ühtenzoitusiden aigkirj',
'pagemerge-logentry' => '[[$1]] da [[$2]]-lehtpoled oma ühtenzoittud (versijad $3-hesai)',
'revertmerge'        => 'Jagada',
'mergelogpagetext'   => 'Naku om tantoižiden lehtpoliden nimiden ühtenzoitusiden nimikirjutez.',

# Diffs
'history-title'            => '"$1"-lehtpolen toižetusiden istorii',
'difference'               => '(Erod versijoiden keskes)',
'lineno'                   => 'Rivi $1:',
'compareselectedversions'  => 'Rindatada valitud versijad',
'showhideselectedversions' => 'Ozutada/peitta valitud versijad',
'editundo'                 => 'heitta pätand',
'diff-multi'               => "({{PLURAL:$1|üks' keskmäine versii ei ole|$1 keskmäšt versijad ei olgoi}} ozutadud)",

# Search results
'searchresults'                    => "Ectä rezul'tatad",
'searchresults-title'              => 'Ecindan rezul\'tatad sanale "$1"',
'searchresulttext'                 => 'Ližainformacijad ecmižes sab sada [[{{MediaWiki:Helppage}}|Abu]]-lehtpolespäi.',
'searchsubtitle'                   => 'Tö ecit \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|kaik lehtpoled, kudambad augotase nimespäi "$1"]]{{int:pipe-separator}}
[[Special:WhatLinksHere/$1|Kaik lehtpoled, kudambad kosketadas necidä nimed]])',
'searchsubtitleinvalid'            => "Tö ecit '''$1'''",
'toomanymatches'                   => "Om löutud äjahko rezul'tatoid, olgat hüväd, eckat toine sana",
'titlematches'                     => 'Löutud lehtpoliden nimed',
'notitlematches'                   => 'Ei ole ningomid lehtpoliden nimid',
'textmatches'                      => 'Löutud tekstanpalad lehtpolil',
'notextmatches'                    => 'Lehtpoliden tekstoiš ei ole ectud sanad',
'prevn'                            => 'vene (ru){{PLURAL:$1|edeline $1|edeližed $1}}',
'nextn'                            => "jäl'ghižed {{PLURAL:$1|$1}}",
'prevn-title'                      => "$1 {{PLURAL:$1|edeline rezul'tat|edelišt rezul'tatad}}",
'nextn-title'                      => "$1 {{PLURAL:$1|jäl'ghine rezul'tat|jäl'ghišt rezul'tatad}}",
'shown-title'                      => "Ozutada $1 {{PLURAL:$1|rezul'tat|rezul'tatad}} lehtpoleks",
'viewprevnext'                     => 'Kacta ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Ecindan järgendused',
'searchmenu-exists'                => "'''Neciš Wikiš om jo lehtpol' ningoižen nimenke: \"[[:\$1]]\"'''",
'searchmenu-new'                   => "'''Säta lehtpol' \"[[:\$1]]\" neciš Wikiš!'''",
'searchhelp-url'                   => 'Help:Südäimišt',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ozutada kaik lehtpoled necen prefiksanke]]',
'searchprofile-articles'           => 'Südäimištlehtpoled',
'searchprofile-project'            => 'Abun da projektoiden lehtpoled',
'searchprofile-images'             => "Mul'timedii",
'searchprofile-everything'         => 'Kaikjal',
'searchprofile-advanced'           => 'Levitoittud',
'searchprofile-articles-tooltip'   => 'Ectä täs: $1',
'searchprofile-project-tooltip'    => 'Ectä täs: $1',
'searchprofile-images-tooltip'     => 'Failoiden ecind',
'searchprofile-everything-tooltip' => 'Ectä kaikil lehtpolil (lodulehtpolid mülütaden)',
'searchprofile-advanced-tooltip'   => 'Ectä märitud nimiavarusiš',
'search-result-size'               => '$1 ({{PLURAL:$2|1 sana|$2 sanad}})',
'search-result-score'              => 'Relevantižuz: $1%',
'search-redirect'                  => '(oigenduz $1)',
'search-section'                   => '(jaguz $1)',
'search-suggest'                   => 'Tahtoižit-ik löuta: $1',
'search-interwiki-caption'         => 'Heimolaižed projektad',
'search-interwiki-default'         => "$1 rezul'tatad:",
'search-interwiki-more'            => '(völ)',
'search-mwsuggest-enabled'         => 'ozuta taričendad',
'search-mwsuggest-disabled'        => 'taričendoita',
'search-relatedarticle'            => 'Sidotud lehtpoled',
'mwsuggest-disable'                => 'Ala ozuta AJAX-taričendoid',
'searcheverything-enable'          => 'Ectä kaikiš nimiavarusiš',
'searchrelated'                    => 'sidotud',
'searchall'                        => 'kaik',
'showingresults'                   => "Alemba ozutadas {{PLURAL:$1|'''1''' rezul'tat|'''$1''' rezul'tatad}} nomeraspäi #'''$2''' augotaden.",
'showingresultsnum'                => "Alemba ozutadas {{PLURAL:$3|'''1''' rezul'tat|'''$3''' rezul'tatad}} nomeraspäi '''$2''' augotaden.",
'nonefound'                        => "'''Note''': Ectäs tobjimalaz kaidetud nimiavaruzišpäi.
Kävutagat prefiks ''all:'', miše ectä kaikes südäimištospäi (lodulehtpolid, šablonoid i m. e. mülütaden), vai kävutagat tarbhaine nimiavaruz.",
'search-nonefound'                 => "Ecmižhe ei löudnus rezul'tatoid.",
'powersearch'                      => 'Levitoittud ecind',
'powersearch-legend'               => 'Levitoittud ecind',
'powersearch-ns'                   => 'Ecind nimiavaruziš:',
'powersearch-redir'                => 'Ozutada oigendused',
'powersearch-field'                => 'Eci',
'powersearch-togglelabel'          => 'Kodvda:',
'powersearch-toggleall'            => 'Kaik',
'powersearch-togglenone'           => 'Ei ole nimidä',
'search-external'                  => 'Irdecind',
'searchdisabled'                   => "{{SITENAME}} ecind om saubatud.
Tö voit nügüd' ectä Google'n turbiš.
Otkat sil'mnägubale üks-se, miše {{SITENAME}}-saitan sädäimišt voib olda vanhtunuden.",

# Quickbar
'qbsettings'               => "Navigacijan panel'",
'qbsettings-none'          => 'Ala ozuta',
'qbsettings-fixedleft'     => 'Likumatoi huralpäi',
'qbsettings-fixedright'    => 'Likumatoi oiktalpäi',
'qbsettings-floatingleft'  => 'Ujui huralpäi',
'qbsettings-floatingright' => 'Ujui oiktalpäi',

# Preferences page
'preferences'                 => 'Järgendused',
'mypreferences'               => 'Minun järgendused',
'prefs-edits'                 => 'Redaktiruindoiden lugu:',
'prefsnologin'                => 'Tö et olgoi kirjutanus sistemha.',
'prefsnologintext'            => 'Teile pidab <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kirjutadas sistemha]</span>, miše toižetada järgendusid.',
'changepassword'              => 'Peitsanan toižetuz',
'prefs-skin'                  => 'Irdnägu',
'skin-preview'                => 'Ezikaclend',
'prefs-math'                  => 'Matematik',
'datedefault'                 => 'Augotižjärgendused',
'prefs-datetime'              => 'Dat da aig',
'prefs-personal'              => "Kävutajan profil'",
'prefs-rc'                    => 'Tantoižed toižetused',
'prefs-watchlist'             => 'Kaclendnimikirjutez',
'prefs-watchlist-days'        => 'Maksimaline päividen lugu kaclendnimikirjuteses:',
'prefs-watchlist-days-max'    => '(ei enamba 7 päiväd)',
'prefs-watchlist-edits'       => 'Maksimaline toižetusiden lugu levitoittud kaclendnimikirjuteses:',
'prefs-watchlist-edits-max'   => '(maksimaline lugu: 1000)',
'prefs-watchlist-token'       => 'Kaclendan nimikirjutesen token:',
'prefs-misc'                  => 'Toine',
'prefs-resetpass'             => 'Toižetada peitsana',
'prefs-email'                 => 'E-počtan opcijad',
'prefs-rendering'             => 'Nägu',
'saveprefs'                   => 'Kirjutada',
'resetprefs'                  => 'Čuta kaičematomad toižetused',
'restoreprefs'                => 'Endištada kaik augotižjärgendused',
'prefs-editing'               => 'Redaktiruind',
'prefs-edit-boxsize'          => 'Redaktiruindan iknan suruz.',
'rows'                        => 'Rivid:',
'columns'                     => 'Pachid:',
'searchresultshead'           => 'Ecind',
'resultsperpage'              => 'Kirjutesiden lugu lehtpolel:',
'contextlines'                => 'Ozutadud rividen lugu kaikuččen löutud kirjutesen täht:',
'contextchars'                => 'Kontekstznamoiden lugu rives:',
'stub-threshold'              => 'Nägutesen märitamižen künduz <a href="#" class="stub">kosketust otetile</a> (baitoiš)',
'recentchangesdays'           => 'Päiväd veresiden toižetusidenke, lugumär:',
'recentchangesdays-max'       => '(maksimum $1 {{PLURAL:$1|päiv|päiväd}})',
'recentchangescount'          => 'Redakcijoiden lugu, kudamb pidab ozutada augotižjärgendusen mödhe:',
'savedprefs'                  => 'Teiden järgendused oma kirjutadud muštho.',
'timezonelegend'              => 'Aigvö:',
'localtime'                   => 'Tahonaig:',
'timezoneuseserverdefault'    => 'Kävutada serveran järgendused',
'timezoneuseoffset'           => 'Toine (kirjutagat sirdandmär)',
'timezoneoffset'              => 'Aigan sirdandmär¹:',
'servertime'                  => 'Serveran aig:',
'guesstimezone'               => 'Täutta kaclimespäi',
'timezoneregion-africa'       => 'Afrik',
'timezoneregion-america'      => 'Amerik',
'timezoneregion-antarctica'   => 'Antarktik',
'timezoneregion-arctic'       => 'Arktik',
'timezoneregion-asia'         => 'Azii',
'timezoneregion-atlantic'     => 'Atlantine valdmeri',
'timezoneregion-australia'    => 'Avstralii',
'timezoneregion-europe'       => 'Evrop',
'timezoneregion-indian'       => 'Indijan valdmeri',
'timezoneregion-pacific'      => "Tün' valdmeri",
'allowemail'                  => 'Laskkat toižile kävutajile oigeta teile e-počtad',
'prefs-searchoptions'         => 'Ecindan järgendused',
'prefs-namespaces'            => 'Nimiavarused',
'defaultns'                   => 'Toižiš statjoiš ectä neniš nimiavarusiš:',
'default'                     => 'augotižjärgendusen mödhe',
'prefs-files'                 => 'Failad',
'prefs-custom-css'            => 'Ičeze CSS',
'prefs-custom-js'             => 'Ičeze JS',
'prefs-emailconfirm-label'    => 'E-počtan vahvištand:',
'prefs-textboxsize'           => 'Redaktiruindan iknan suruz',
'youremail'                   => 'E-počt:',
'username'                    => 'Kävutajan nimi:',
'uid'                         => 'Kävutajan nomer:',
'prefs-memberingroups'        => '{{PLURAL:$1|Gruppan|Gruppiden}} ühtnii:',
'prefs-registration'          => 'Registracijan aig:',
'yourrealname'                => 'Todesine nimi:',
'yourlanguage'                => "Kel':",
'yourvariant'                 => 'Kelenvariant:',
'yournick'                    => 'Allekirjutez:',
'badsig'                      => 'Vär allekirjutez.
Kodvgat HTML-virgad.',
'badsiglength'                => "Pit'kähk allekirjutez.
Pidab tehta se $1 {{PLURAL:$1|simvolaspäi|simvoloišpäi}}.",
'yourgender'                  => 'Sugu:',
'gender-unknown'              => 'Ei ole ozutadud',
'gender-male'                 => 'Mez’',
'gender-female'               => 'Naine',
'prefs-help-gender'           => 'Opcionaline: kävutadas likutimen erasiš tedotusiš, miše ozutada kävutajan sugu oikti. Nece informacii om avoin.',
'email'                       => 'E-počt',
'prefs-help-realname'         => 'Todesine nimi om opcionaline.
Ku tö kirjutat sen, nece nimi kävutadas, miše ozutada lehtpolen toižetajad.',
'prefs-help-email-required'   => 'Pidab kirjutada teiden e-počtan adres.',
'prefs-info'                  => 'Päinformacii',
'prefs-i18n'                  => 'Internacionalizacii',
'prefs-signature'             => 'Allekirjutez',
'prefs-dateformat'            => 'Datan format',
'prefs-timeoffset'            => 'Aigan sirdand',
'prefs-advancedediting'       => 'Ližaopcijad',
'prefs-advancedrc'            => 'Ližaopcijad',
'prefs-advancedrendering'     => 'Ližaopcijad',
'prefs-advancedsearchoptions' => 'Ližaopcijad',
'prefs-advancedwatchlist'     => 'Ližaopcijad',
'prefs-displayrc'             => 'Nägun opcijad',
'prefs-diffs'                 => 'Erod',

# User rights
'userrights'                     => 'Kävutajiden oiktusiden ohjandamine',
'userrights-lookup-user'         => 'Kävutajiden gruppiden ohjendamine',
'userrights-user-editname'       => 'Kävutajan nimi:',
'editusergroup'                  => 'Redaktiruida kävutajiden gruppad',
'userrights-editusergroup'       => 'Toižetada kävutajan gruppad',
'saveusergroups'                 => 'Kirjutada muštho kävutajan gruppad',
'userrights-groupsmember'        => 'Kävutai om neniden gruppiden ühtnii:',
'userrights-reason'              => 'Sü:',
'userrights-no-interwiki'        => 'Teile ei sa toižetada toižiden wikiden kävutajiden oiktusid.',
'userrights-nodatabase'          => 'Andmusiden $1-bazad ei ole vai se ei ole lokaline.',
'userrights-nologin'             => 'Teile pidab [[Special:UserLogin|kirjutadas sistemha]] administratoran oiktusidenke, miše antta oiktusid kävutajile.',
'userrights-notallowed'          => 'Teiden kävutajan registracijale ei sa antta oiktusid toižile kävutajile.',
'userrights-changeable-col'      => 'Gruppad, kudambad teile sab toižetada',
'userrights-unchangeable-col'    => 'Gruppad, kudambid teile ei sa toižetada',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Kävutajad',
'group-autoconfirmed' => 'Avtovahvištoittud kävutajad',
'group-bot'           => 'Botad',
'group-sysop'         => 'Administratorad',
'group-bureaucrat'    => 'Bürokratad',
'group-suppress'      => 'Revizorad',
'group-all'           => '(Kaik)',

'group-user-member'          => 'Kävutai',
'group-autoconfirmed-member' => 'Avtomatižešti vahvištoittud kävutai',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Bürokrat',
'group-suppress-member'      => 'Revizor',

'grouppage-user'          => '{{ns:project}}:Kävutajad',
'grouppage-autoconfirmed' => '{{ns:project}}:Avtovahvištoittud kävutajad',
'grouppage-bot'           => '{{ns:project}}:Botad',
'grouppage-sysop'         => '{{ns:project}}:Administratorad',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokratad',
'grouppage-suppress'      => '{{ns:project}}:Revizorad',

# Rights
'right-read'                 => 'Lugeda lehtpoled',
'right-edit'                 => 'Redaktiruida lehtpoled',
'right-createpage'           => 'Säta lehtpolid (päiči lodulehtpoliš)',
'right-createtalk'           => 'Säta lodulehtpolid',
'right-createaccount'        => 'Säta uded kävutajiden registracijad',
'right-minoredit'            => 'Znamoita redakcijad penikš',
'right-move'                 => 'Udesnimitada lehtpolid',
'right-move-subpages'        => 'Udesnimitada lehtpoled niiden alalehtpolidenke',
'right-move-rootuserpages'   => "Udesnimitada kävutajiden jur'lehtpoled",
'right-movefile'             => 'Udesnimitada failad',
'right-suppressredirect'     => 'Ala tege läbikosketust vanhal nimelpäi lehtesen udesnimitades',
'right-upload'               => 'Jügutoitta failoid',
'right-reupload'             => 'Udeskirjutada failad',
'right-reupload-own'         => 'Failan udeskirjutand sen jügutoitajal.',
'right-reupload-shared'      => 'Vajehtada failad ühthižiš varaaitoišpäi lokaližil',
'right-upload_by_url'        => 'Jügutoitta failoid URL-adresalpäi',
'right-purge'                => 'Puhtastada lehtpolen keš vahvištoitmata',
'right-autoconfirmed'        => "Redaktiruida pol'kaitud lehtpoled",
'right-bot'                  => 'Lugeda avtomatižeks processaks',
'right-apihighlimits'        => 'Kävutada korktembid tazopindoid API-küzumižes',
'right-writeapi'             => 'Kävutada API kirjutades',
'right-delete'               => 'Čuta poiš lehtpoled',
'right-bigdelete'            => "Čuta poiš lehtpoled pit'kiden istorijoidenke",
'right-deleterevision'       => 'Čuta poiš da endištada lehtpoliden konkretižed versijad',
'right-deletedhistory'       => 'Ozutada čutud lehtpolid (ei sa lugeda čutud tekstad)',
'right-browsearchive'        => 'Ectä čutud lehtpoled',
'right-undelete'             => "Endištada lehtpol'",
'right-suppressrevision'     => 'Administratorilpäi peittud lehtesiden versijoiden lugend da endištand.',
'right-suppressionlog'       => 'Privatižiden aigkirjoiden lugend.',
'right-block'                => "Kel'ta toižid kävutajid redaktiruindas",
'right-blockemail'           => "Kel'ta kävutajad e-počtad oigendamas",
'right-hideuser'             => "Kävutajan nimen kel'dand da sen peitand",
'right-ipblock-exempt'       => 'Ümbärta IP-blokiruindad, avtoblokiruindad da diapazoniden blokiruindad',
'right-proxyunbannable'      => 'Ümbärta proksiden avtomatine blokiruind',
'right-protect'              => 'Vajehtada lehtpoliden kaičendan tazopind i redaktiruida kaitud lehtpoled',
'right-editprotected'        => 'Redaktiruida kaitud lehtpoled (kaskadkaičemata)',
'right-editinterface'        => 'Redaktiruida kävutajan intefeis',
'right-editusercssjs'        => 'Redaktiruida toižiden kävutajiden CSS- da JS-failad',
'right-editusercss'          => 'Redaktiruida toižiden kävutajiden CSS-failad',
'right-edituserjs'           => 'Redaktiruida toižiden kävutajiden JS-failad',
'right-noratelimit'          => 'Ei ole kaidendust piguden mödhe',
'right-import'               => 'Importiruida lehtpolid toižiš wikišpäi',
'right-importupload'         => 'Importiruida lehtpolid failoid jügutoitten',
'right-patrol'               => 'Znamoita toižiden kävutajiden toižetused kut patruliruidud',
'right-autopatrol'           => 'Znamoita ičeze toižetusid avtomatižikš kut patruliruidud',
'right-patrolmarks'          => 'Nähta patruliruindvirgad veresiš redakcijoiš',
'right-unwatchedpages'       => 'Lugeda kacelmatomiden lehtpoliden nimikirjutest',
'right-trackback'            => 'Oigeta trackback',
'right-mergehistory'         => 'Ühtenzoitta lehtpoliden istorijad',
'right-userrights'           => 'Redaktiruida kaikiden kävutajiden oiktused',
'right-userrights-interwiki' => 'Toižetada toižiden wiki-saitoiden kävutajiden oiktused',
'right-siteadmin'            => 'Luklostada da avaita andmusiden baz',
'right-reset-passwords'      => 'Heitta toižiden kävutajiden peitsanad',
'right-sendemail'            => 'Oigeta e-počtad toižile kävutajile',

# User rights log
'rightslog'      => 'Kävutajan oiktusiden aigkirj',
'rightslogtext'  => 'Nece om kävutajan oiktusiden toižetusen aigkirj.',
'rightslogentry' => 'toižetin $1-kävutajan ühtnend gruppiš - oli $2, linneb $3',
'rightsnone'     => '(ei ole)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => "lugeda necidä lehtpol't",
'action-edit'                 => "redaktiruida (koheta) nece lehtpol'",
'action-createpage'           => 'säta lehtpolid',
'action-createtalk'           => 'säta lodulehtpolid',
'action-createaccount'        => 'säta nece kävutajan nimi',
'action-minoredit'            => 'znamoita nece redakcii peneks',
'action-move'                 => "udesnimitada nece lehtpol'",
'action-move-subpages'        => "udesnimitada nece lehtpol' da kaik sidotud lehtpoled",
'action-move-rootuserpages'   => "udesnimitada kävutajiden jur'lehtpoled",
'action-movefile'             => 'udesnimitada nece fail',
'action-upload'               => 'jügutoitta nece fail',
'action-reupload'             => 'kirjutada nece fail udes',
'action-upload_by_url'        => 'jügutoitta nece fail URL-adresalpäi',
'action-writeapi'             => 'kävutada API kirjutades',
'action-delete'               => "čuta poiš nece lehtpol'",
'action-deleterevision'       => 'čuta poiš nece lehtpolen versii',
'action-deletedhistory'       => 'lugeda necen lehtpolen čutud istorijad',
'action-browsearchive'        => 'ectä čutud lehtpolid',
'action-undelete'             => 'endištada nece lehtez',
'action-suppressrevision'     => 'lugeda da endištada nece lehtpolen peitversii',
'action-suppressionlog'       => 'lugeda necidä privatišt aigkirjad',
'action-block'                => 'blokiruida necidä kävutajad redaktiruindaspäi',
'action-protect'              => 'necen lehtpolen kaičendpindan toižetamine',
'action-import'               => "importiruida nece lehtpol' toižes Wikišpäi",
'action-importupload'         => "importiruida nece lehtpol' jügutoittud failaspäi",
'action-patrol'               => 'znamoita toižiden kävutajiden toižetused kut patruliruidud',
'action-autopatrol'           => 'znamoita ičeze toižetused kut patruliruidud',
'action-unwatchedpages'       => 'lugeda kacelmatomiden lehtpoliden nimikirjutez',
'action-trackback'            => 'oigeta trackback',
'action-mergehistory'         => 'sidoda necen lehtpolen toižetusen istorii mihe-se',
'action-userrights'           => 'redaktiruida kaik kävutajan oiktused',
'action-userrights-interwiki' => 'redaktiruida kävutajiden oiktused toižiš wikiš',
'action-siteadmin'            => 'andmusiden bazan blokiruind i blokiruindan heitand',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|toižetuz|toižetust}}',
'recentchanges'                     => 'Tantoižed toižetused',
'recentchanges-legend'              => 'Tantoižiden toižetusiden järgendused',
'recentchangestext'                 => 'Necil lehtpolil om tantoižid toižetusid {{SITENAME}}-saital.',
'recentchanges-feed-description'    => "Kacelta jäl'gmäižid toižetusid wikiš neciš valus.",
'recentchanges-label-legend'        => 'Legend: $1.',
'recentchanges-legend-newpage'      => "$1 - uz' lehtpol'",
'recentchanges-label-newpage'       => 'Nece redakcii sädi uden lehtpolen',
'recentchanges-legend-minor'        => '$1 - penikaine redakcii',
'recentchanges-label-minor'         => 'Nece redakcii om penikaine',
'recentchanges-legend-bot'          => '$1 - botan redakcii',
'recentchanges-label-bot'           => 'Necen redakcijan tegi bot',
'recentchanges-legend-unpatrolled'  => '$1 - patruliruimatoi redakcii',
'recentchanges-label-unpatrolled'   => 'Necidä redakcijad ei völ patruliruinugoi',
'rcnote'                            => "Alahan om ozutadud {{PLURAL:$1| '''1''' toižetuz|'''$1''' toižetust}} {{PLURAL:$2|jäl'gmäižes päiväs|jäl'gmäižiš '''$2''' päiviš}}, aigal $5, $4.",
'rcnotefrom'                        => "Alemba oma anttud toižetused '''$2'''-späi ( '''$1'''-hesai).",
'rclistfrom'                        => 'Ozutada uded toižetused dataspäi $1 augotaden',
'rcshowhideminor'                   => '$1 pened redakcijad',
'rcshowhidebots'                    => '$1 botad',
'rcshowhideliu'                     => '$1 sistemha kirjutadud kävutajad',
'rcshowhideanons'                   => '$1 anonimišt kävutajad',
'rcshowhidepatr'                    => '$1 kodvdud toižetust',
'rcshowhidemine'                    => '$1 ičein redakcijad',
'rclinks'                           => "Ozutada jäl'gmäižed $1 toižetust $2 päiväs<br />$3",
'diff'                              => 'erod',
'hist'                              => 'istorii',
'hide'                              => 'Peitta',
'show'                              => 'Ozutada',
'minoreditletter'                   => 'p',
'newpageletter'                     => 'U',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|kaclii kävutai|kaclijad kävutajad}}]',
'rc_categories'                     => 'Vaiše kategorijoišpäi (erigoitkat znamaižel "|")',
'rc_categories_any'                 => 'Eraz',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => "/* $1 */ uz' jaguz",
'rc-enhanced-expand'                => 'Ozutada detalid (JavaScript)',
'rc-enhanced-hide'                  => 'Peitta detalid',

# Recent changes linked
'recentchangeslinked'          => 'Sidotud toižetused',
'recentchangeslinked-feed'     => 'Sidotud toižetused',
'recentchangeslinked-toolbox'  => 'Sidotud toižetused',
'recentchangeslinked-title'    => '"$1"-lehtpol\'he sidotud toižetused',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Sidotud lehtpolil ei olend toižetusid anttud pordon aigan.',
'recentchangeslinked-summary'  => "Neciš nimikirjuteses om tantoižid toižetusid lehtpoliš, kudambid kosketab ozutadud lehtpol'.
[[Special:Watchlist|Teiden kaclendnimikirjutesen]] lehtpoled oma erigoittud lihavoitud šriftal.",
'recentchangeslinked-page'     => 'Lehtpolen nimi:',
'recentchangeslinked-to'       => "Vastkarin, ozutada toižetused niil lehtpolil, kudambad kosketadas ozutadud lehtpol't",

# Upload
'upload'                     => 'Jügutoitta fail',
'uploadbtn'                  => 'Jügutoitta fail',
'reuploaddesc'               => 'Pörttas jügetoitandan formannoks',
'uploadnologin'              => 'Tö ei olgoi kirjutanus sistemha',
'uploadnologintext'          => 'Teile pidab [[Special:UserLogin|kirjutadas sistemha]], miše failoid jügetoitmaha.',
'upload_directory_missing'   => 'Failhodr jügetoitandan täht ($1) om kadonu, i server ei voi säta sidä.',
'upload_directory_read_only' => 'Server ei voi kirjutada failhodrha jügedoitandan täht ($1).',
'uploaderror'                => 'Jügedoitandan petuz',
'upload-permitted'           => 'Lasktud failoiden tipad: $1.',
'upload-preferred'           => 'Hüvästadud failoiden tipad: $1.',
'upload-prohibited'          => "Kel'tud failoiden tipad: $1.",
'uploadlog'                  => 'jügetoitusiden aigkirj',
'uploadlogpage'              => 'Jügutoitusiden aigkirj',
'uploadlogpagetext'          => 'Alemba om tantoižiden jügutoitusiden nimikirjutez.
[[Special:NewFiles|Uziden failoiden galerejas]] informacii tantoižiš jügutoitusiš anttas vizualižikš.',
'filename'                   => 'Failan nimi:',
'filedesc'                   => 'Lühüd ümbrikirjutand',
'fileuploadsummary'          => 'Lühüd ümbrikirjutand:',
'filereuploadsummary'        => 'Toižetused failas:',
'filestatus'                 => 'Avtoranoiktusen status:',
'filesource'                 => 'Purde:',
'uploadedfiles'              => 'Jügutoittud failad',
'ignorewarning'              => 'Ignoriruida varutuz i kirjutada fail muštho',
'ignorewarnings'             => 'Ignoriruida kaik varutused',
'minlength1'                 => "Failan nimehe pidab jätta hot' üks' kirjam.",
'badfilename'                => 'Failan nimi om vajehtud, sen uz\' nimi om "$1".',
'filetype-badmime'           => 'Ei sa jügutoitta "$1"-MIME-tipan failoid.',
'filetype-unwanted-type'     => "'''\".\$1\"''' om nevomatoi failan tip.
{{PLURAL:\$3|Paremb failan tip om|Parembad failoiden tipad oma}} \$2.",
'filetype-banned-type'       => "'''\".\$1\"''' om laskmatoi failan tip.
Lasktud {{PLURAL:\$3|failan tip om|failoiden tipad oma}} \$2.",
'filetype-missing'           => 'Necil failal ei ole ližad (ozutesikš, ".jpg").',
'largefileserver'            => 'Failan suruz om suremb, mi lasktud serveral.',
'emptyfile'                  => "Teil jügutoittud fail nägub pal'haks.
Voib olda, necen sü om vär failannimen kirjutamine.
Olgat hüväd, kodvgat, todeks-ik tö tahtoit jügutoitta nece fail.",
'file-exists-duplicate'      => 'Nece fail om {{PLURAL:$1|necen failan|neniden failoiden}} dublikat:',
'uploadwarning'              => 'Varutuz jügutoitmižes',
'savefile'                   => 'Kirjutada fail',
'uploadedimage'              => 'om jügutoitnu "[[$1]]"',
'overwroteimage'             => 'jügutoiti "[[$1]]"-failan uden versijan',
'uploaddisabled'             => "Jügutoitand om kel'tud",
'uploaddisabledtext'         => 'Failoiden jügutoitand om sauptud.',
'php-uploaddisabledtext'     => 'Failoiden jügutoitand om kel\'tud PHP:n järgendusiš. Kodvgat "file_uploads"-järgenduz.',
'uploadscripted'             => 'Neciš failas om HTML-kod vai skript, kudamban kaclim voiži interpretiruida värin.',
'uploadvirus'                => 'Failas om virus! Kc: $1',
'upload-source'              => 'Lähtmižfail',
'sourcefilename'             => 'Failan purdenimi:',
'sourceurl'                  => 'URLan purde:',
'destfilename'               => 'Failan metnimi:',
'upload-maxfilesize'         => 'Failan maksimaline suruz: $1',
'upload-description'         => 'failan ümbrikirjutand',
'watchthisupload'            => 'Kacelta necidä failad',
'upload-success-subj'        => 'Jügutoitand lopihe satusekahas',

'upload-proto-error'        => 'Vär protokol',
'upload-proto-error-text'   => 'Miše jügutoitta edahanpäi, kävutagat URL, kudamb augotase <code>http://</code> vai <code>ftp://</code>.',
'upload-file-error'         => 'Südäipetuz',
'upload-misc-error'         => 'Jügutoitandan tundmatoi petuz',
'upload-misc-error-text'    => "Tundmatoi petuz om ozainus jügutoitandan aigan.
Olgat hüväd, kodvgat, om-ik URL oiged i voib-ik säta sido sen purtkenke, sid' toštkat jügutoituz.
Ku problem jäb jäl'ghepäi-ki, säkat pagin [[Special:ListUsers/sysop|sistemadministratoranke]].",
'upload-too-many-redirects' => 'Neciš URL-as om äjahk läbikosketusid',
'upload-unknown-size'       => 'Tundmatoi suruz',
'upload-http-error'         => 'HTTP-petuz: $1',

# img_auth script messages
'img-auth-accessdenied' => "Pästand tänna om kel'tud",
'img-auth-badtitle'     => 'Ei voi tehta oiktad pälkirjutest "$1"-späi.',
'img-auth-nofile'       => 'Ei ole mugošt failad: "$1".',
'img-auth-streaming'    => '"$1"-joksmuz.',
'img-auth-noread'       => 'Kävutajal ei ole oiktusid lugeda "$1"-failad.',

# HTTP errors
'http-invalid-url'      => 'Vär URL: $1',
'http-invalid-scheme'   => 'URLad "$1"-shemanke ei pid\'oitelgoi.',
'http-read-error'       => "HTTP'd lugemižen petuz.",
'http-curl-error'       => "Petuz URL'ad ecmäs: $1",
'http-host-unreachable' => 'Ei voi säta sidod URL:anke',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'      => 'Ei voi säta sidod URL:anke',
'upload-curl-error6-text' => 'Ei voi säta sidod anttud URL:anke.
Olgat hüväd, kodvgat, om-ik adres oiged i voib-ik säta sido saitanke.',
'upload-curl-error28'     => 'Jügutoitandan aig om männu',

'license'            => 'Licenzii:',
'license-header'     => 'Licenzii',
'nolicense'          => 'Ei ole licenzijoid',
'license-nopreview'  => '(Ei voi ezikacta)',
'upload_source_url'  => '(oiged verkadres)',
'upload_source_file' => ' (fail teiden kompjuteras)',

# Special:ListFiles
'listfiles-summary'     => 'Necil specialižel lehtpolel oma kaik jügetoittud failad.
Tantoi jügetadud failad augotižjärgendusen mödhe ozutadas nimikirjutesen üläpoles.
Plok pachan pälkirjutesele toižetab sortiruindan järgenduz.',
'listfiles_search_for'  => 'Mediafailoiden nimiden ecind:',
'imgfile'               => 'fail',
'listfiles'             => 'Failoiden nimikirjutez',
'listfiles_date'        => 'Dat',
'listfiles_name'        => 'Nimi',
'listfiles_user'        => 'Kävutai',
'listfiles_size'        => 'Suruz’',
'listfiles_description' => 'Ümbrikirjutand',
'listfiles_count'       => 'Versijad',

# File description page
'file-anchor-link'          => 'Fail',
'filehist'                  => 'Failan istorii',
'filehist-help'             => 'Paingat datale/aigale, miše nägištada, mitte oli olnu fail siloi (möhembita toižetusita).',
'filehist-deleteall'        => 'Čuta poiš kaik',
'filehist-deleteone'        => 'čuta poiš',
'filehist-revert'           => 'endištada',
'filehist-current'          => 'nügüdläine',
'filehist-datetime'         => 'Dat/Aig',
'filehist-thumb'            => 'Pičukuva',
'filehist-thumbtext'        => 'Pičukuva versijale $1 i norembile versijoile',
'filehist-nothumb'          => 'Ei ole pičukuvad',
'filehist-user'             => 'Kävutai',
'filehist-dimensions'       => 'Objektan suruz',
'filehist-filesize'         => 'Failan suruz',
'filehist-comment'          => 'Homaičend',
'filehist-missing'          => 'Ei ole failad',
'imagelinks'                => 'Kosketused failale',
'linkstoimage'              => "{{PLURAL:$1|Nece lehtpol' kosketab|$1 Nened lehtpoled kosketadas}} necidä failad:",
'nolinkstoimage'            => 'Ei ole necidä failad kosketajid lehtpolid.',
'morelinkstoimage'          => 'Ozutada [[Special:WhatLinksHere/$1|toižid kosketusid]] necile failale.',
'redirectstofile'           => '{{PLURAL:$1|Nece fail läbikosketab|$1 Nened failad läbikosketadas}} necile failale:',
'duplicatesoffile'          => '{{PLURAL:$1|Nece fail om|$1 Nened failad oma}} ([[Special:FileDuplicateSearch/$2|ližainformacii]])-failan {{PLURAL:$1|dublikat|$1 dublikatad}}:',
'sharedupload'              => 'Nece fail om ühthižes $1-varaaitaspäi, sidä voiži kävutada mugažo toižiš projektoiš.',
'filepage-nofile'           => 'Ei ole failad mugoižen nimenke.',
'filepage-nofile-link'      => 'Ei ole failad mugoižen nimenke, no tö voit [$1 jügutoitta se].',
'uploadnewversion-linktext' => "Jügutoitta necen failan uz' versii",
'shared-repo-from'          => '$1-projektaspäi',
'shared-repo'               => 'ühthine mediavaraait',

# File reversion
'filerevert'                => '$1n endištand vanhemban versijannoks',
'filerevert-legend'         => 'Endištada failan versii',
'filerevert-intro'          => "Tö takait endištada '''[[Media:$1|$1]]'''-fail [$4-versijahasai, kudamb oli tehtud datal $3, $2].",
'filerevert-comment'        => 'Homaičend:',
'filerevert-defaultcomment' => 'Om endištadud versijahasai, kudamban dat om $2, $1',
'filerevert-submit'         => 'Endištada',
'filerevert-success'        => "'''[[Media:$1|$1]]''' om endištadud [$4 versijannoks datal $3, $2].",

# File deletion
'filedelete'                  => 'Čuta $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Čuta poiš fail',
'filedelete-intro'            => "Tö takait čuta fail '''[[Media:$1|$1]]''' kaiken sen istorijanke.",
'filedelete-comment'          => 'Sü:',
'filedelete-submit'           => 'Čuta poiš',
'filedelete-success'          => "'''$1'''-fail om čutud poiš.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''-failan versii, kudamb datiruiše datal $3, $2, om čutud.",
'filedelete-nofile'           => "'''$1'''-failad ei ole wikiš.",
'filedelete-nofile-old'       => "Ei ole '''$1'''-failan arhivversijad ningomiden atributoidenke.",
'filedelete-otherreason'      => 'Toine sü/ližasü:',
'filedelete-reason-otherlist' => 'Toine sü',
'filedelete-reason-dropdown'  => '*Čudandan päsüd
** avtoranoiktusen murenduz
** failan dublikat',
'filedelete-edit-reasonlist'  => 'Redaktiruida čudandan süd',

# MIME search
'mimesearch' => 'MIME-ecind',
'mimetype'   => 'MIME-tip:',
'download'   => 'jügutoitta',

# Unwatched pages
'unwatchedpages' => 'Kaclematomad lehtpoled',

# List redirects
'listredirects' => 'Läbioigendusiden nimikirjutez',

# Unused templates
'unusedtemplates'    => 'Kävutamatomad šablonad',
'unusedtemplateswlh' => 'toižed kosketused',

# Random page
'randompage'         => "Statjaline lehtpol'",
'randompage-nopages' => '"$1"-{{PLURAL:$2|Nimiavarudes|Nimiavaruziš}} ei ole lehtpolid.',

# Random redirect
'randomredirect'         => 'Statjaline läbikosketuz',
'randomredirect-nopages' => '"$1"-nimiavaruses ei ole läbikosketusid.',

# Statistics
'statistics'               => 'Statistik',
'statistics-header-pages'  => 'Lehtpoliden statistik',
'statistics-header-edits'  => 'Redaktiruida statistikad',
'statistics-header-views'  => 'Kacta statistikha',
'statistics-header-users'  => 'Kävutajiden statistik',
'statistics-header-hooks'  => 'Toine statistik',
'statistics-articles'      => "Südäimištlehtpol't",
'statistics-pages'         => "Lehtpol't",
'statistics-pages-desc'    => 'Kaik lehtpoled wikiš, lodulehtpolid da läbioigendusid mülütaden, i m. e.',
'statistics-files'         => 'Jügetoittud failad',
'statistics-edits'         => 'Toižetusiden lugu {{SITENAME}}-saitan seižutamižessai.',
'statistics-edits-average' => 'Toižetusiden keskmäine lugu lehtpolel',
'statistics-views-total'   => 'Kaiked kacundoid',
'statistics-views-peredit' => 'Kacundoid redakcijas',
'statistics-users'         => 'Registriruidud [[Special:ListUsers|kävutajad]]',
'statistics-users-active'  => 'Activižed kävutajad',
'statistics-mostpopular'   => 'Kaikiš populärižembad lehtpoled',

'disambiguations'     => 'Lehtpoled, kudambil om kosketusid äiznamoičendaižile terminoile',
'disambiguationspage' => 'Template:Äiznamoičenduz',

'doubleredirects'            => 'Kaksitadud läbikosketused',
'double-redirect-fixed-move' => "[[$1]]-lehtpol' om udesnimitadud. Se läbikosketab nügüd' [[$2]]-lehtpolele.",
'double-redirect-fixer'      => 'Läbikosketusiden kohendai',

'brokenredirects'        => 'Rebitadud läbikosketused',
'brokenredirectstext'    => 'Nened läbikosketused ozutadas olmatomihe lehtpolihe:',
'brokenredirects-edit'   => 'redaktiruida',
'brokenredirects-delete' => 'čuta poiš',

'withoutinterwiki'         => "Lehtpoled kel'kosketusita",
'withoutinterwiki-summary' => 'Nenil lehtpolil ei ole interwiki-kosketusid.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Ozutada',

'fewestrevisions' => 'Lehtpoled, kudambil om vähemb versijoid',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bait|baitad}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorii|kategorijad}}',
'nlinks'                  => '$1 {{PLURAL:$1|kosketuz|kosketust}}',
'nmembers'                => '$1 {{PLURAL:$1|ühtnii|ühtnijad}}',
'nrevisions'              => '$1 {{PLURAL:$1|versii|versijad}}',
'nviews'                  => '$1 {{PLURAL:$1|kacund|kacundad}}',
'specialpage-empty'       => "Ecind ei ole andnu rezul'tatad.",
'lonelypages'             => 'Üksjäižed lehtpoled',
'lonelypagestext'         => 'Ozutadud lehtpolid ei kosketagoi toižed necen saitan lehtpoled; ozutadud lehtpoled mugažo ei olgoi mülütadud toižihe lehtpolihe.',
'uncategorizedpages'      => 'Järgendamatomad lehtpoled',
'uncategorizedcategories' => 'Järgendamatomad kategorijad',
'uncategorizedimages'     => 'Järgendamatomad failad',
'uncategorizedtemplates'  => 'Järgendamatomad šablonad',
'unusedcategories'        => 'Kävutamatomad kategorijad',
'unusedimages'            => 'Kävutamatomad failad',
'popularpages'            => 'Populärižed lehtpoled',
'wantedcategories'        => 'Ectud kategorijad',
'wantedpages'             => 'Ectud lehtpoled',
'wantedpages-badtitle'    => "Petuzline pälkirjutez küzumižen rezul'tatoiš: $1",
'wantedfiles'             => 'Ectud failad',
'wantedtemplates'         => 'Ectud šablonad',
'mostlinked'              => 'Lehtpoled, kudambid kosketadas enamba',
'mostlinkedcategories'    => 'Kategorijad, kudambid kosketadas enamba',
'mostlinkedtemplates'     => 'Šablonad, kudambid kosketadas enamba',
'mostcategories'          => 'Lehtpoled, kudambid mülütadas äiluguižihe kategorijoihe',
'mostimages'              => 'Failad, kudambid kävutadas paksumba',
'mostrevisions'           => 'Lehtpoled, kudambid redaktiruidas paksumba',
'prefixindex'             => 'Kaik lehtpoled prefiksoidenke',
'shortpages'              => 'Lühüdad lehtpoled',
'longpages'               => "Pit'käd lehtpoled",
'deadendpages'            => 'Lehtpoled, kudambid ei kosketagoi toižed lehtpoled',
'deadendpagestext'        => 'Nened lehtpoled ei kosketagoi toižid necen wikin lehtpolid.',
'protectedpages'          => 'Kaitud lehtpoled',
'protectedpages-indef'    => 'Vaiše strokutomad kaičendad',
'protectedpages-cascade'  => 'Vaiše kaskadkaičendad',
'protectedpagestext'      => 'Nened lehtpoled oma kaitud redaktiruindaspäi da sirdandaspäi',
'protectedpagesempty'     => "Nügüd' ei ole neniden parametriden mödhe kaitud lehtpolid.",
'protectedtitles'         => 'Kaitud lehtpoliden nimed',
'protectedtitlestext'     => 'Nenid lehtpoliden nimid ei sa kävutada.',
'protectedtitlesempty'    => "Nügüd' ei ole neniden parametriden mödhe kaitud lehtpoliden nimid.",
'listusers'               => 'Kävutajiden nimikirjutez',
'listusers-editsonly'     => 'Ozutada vaiše kävutajid, kudambid om redakcijoid',
'listusers-creationsort'  => 'Järgeta sündumiždatan mödhe',
'usereditcount'           => '$1 {{PLURAL:$1|redakcii|redakcijad}}',
'usercreated'             => 'Om sätud $1 aigal $2',
'newpages'                => 'Uded lehtpoled',
'newpages-username'       => 'Kävutai:',
'ancientpages'            => 'Kaikiš vanhembad lehtpoled',
'move'                    => 'Udesnimitada',
'movethispage'            => "Sirda nece lehtpol'",
'unusedimagestext'        => "Mugoižed failad oma olemas, no ned ei kävutagoi ni-miččel lehtpolel.
Olgat hüväd, otkat sil'mnägubale, miše toižed-ki saitad voidas kosketada fail oiktan URLan turbiš, i sikš nece fail voib olda nimikirjuteses, hot' sidä kävutadas aktivižešti.",
'unusedcategoriestext'    => "Om mugomid kategorijoiden lehtpolid, hot' niken ei kävuta niid.",
'notargettitle'           => 'Ei ole metod',
'notargettext'            => "Tö ei olgoi ozutanuded metlehtpol't vai kävutajad necen tegendan täht.",
'nopagetitle'             => "Ei ole mugošt metlehtpol't",
'nopagetext'              => "Ei ole mugošt ozutadud metlehtpol't.",
'pager-newer-n'           => '{{PLURAL:$1|1 udemb|$1 udembad}}',
'pager-older-n'           => '{{PLURAL:$1|1 vanhemb|$1 vanhembad}}',
'suppress'                => 'Peitmine',

# Book sources
'booksources'               => 'Kirjpurtked',
'booksources-search-legend' => 'Ectä kirjpurtkid',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Ectä',
'booksources-text'          => 'Naku om kosketusid saitoile, kudambil mödas uzid da kuluid kirjoid. Niilpäi voib löuta ližainformacijad ectud kirjoiš:',
'booksources-invalid-isbn'  => 'Nece ISBN, näguse, om vär; Kodvgat, oikti-k oled kopiruinuded sidä originaližes purtkespäi.',

# Special:Log
'specialloguserlabel'  => 'Kävutai:',
'speciallogtitlelabel' => 'Pälkirjutez:',
'log'                  => 'Aigkirjad',
'all-logs-page'        => 'Kaik avoinuded aigkirjad',
'logempty'             => 'Ei ole sättujid kirjutesid aigkirjas.',
'log-title-wildcard'   => 'Ectä pälkirjutesid, kudambil augoitišes om ningomid simvoloid',

# Special:AllPages
'allpages'          => 'Kaik lehtpoled',
'alphaindexline'    => '$1... $2',
'nextpage'          => 'Toine lehtpol’ ($1)',
'prevpage'          => "Edeline lehtpol' ($1)",
'allpagesfrom'      => 'Ozutada lehtpoled, kudambad augotase necil tekstal:',
'allpagesto'        => 'Ozutada lehtpoled lopuidenke:',
'allarticles'       => 'Kaiked lehtpoled',
'allinnamespace'    => 'Kaik lehtpoled ($1-nimiavaruz)',
'allnotinnamespace' => 'Kaik lehtpoled (päiči $1-nimiavarust)',
'allpagesprev'      => 'Edeližed',
'allpagesnext'      => "Jäl'ghižed",
'allpagessubmit'    => 'Tehta',
'allpagesprefix'    => 'Ozutada lehtpoled prefiksoidenke:',
'allpages-bad-ns'   => '{{SITENAME}}-saital ei ole "$1"-nimiavarust.',

# Special:Categories
'categories'                    => 'Kategorijad',
'categoriespagetext'            => '{{PLURAL:$1|Neciš kategorijas|Neniš kategorijoiš }} om lehtpolid vai mediafailoid.
[[Special:UnusedCategories|Kävumatomid kategorijoid]] ei ozutagoi naku.
Kc. mugažo [[Special:WantedCategories|ectud kategorijoiden nimikirjutez]].',
'categoriesfrom'                => 'Ozutada kategorijad, necišpäi augotaden:',
'special-categories-sort-count' => 'järgeta lugumäran mödhe',
'special-categories-sort-abc'   => 'järgeta kirjamišton mödhe',

# Special:DeletedContributions
'deletedcontributions'             => 'Čutud tond',
'deletedcontributions-title'       => 'Čutud tond',
'sp-deletedcontributions-contribs' => 'tond',

# Special:LinkSearch
'linksearch'       => 'Irdkosketused',
'linksearch-pat'   => 'Ecindan šablon:',
'linksearch-ns'    => 'Nimiavaruz:',
'linksearch-ok'    => 'Löuta',
'linksearch-line'  => '$1 om kosketadud $2-lehtpolelpäi',
'linksearch-error' => 'Džokersimvolid sab kävutada vaiše adresoiden augotišes.',

# Special:ListUsers
'listusersfrom'      => 'Ozutada kävutajid augotaden kävutajaspäi:',
'listusers-submit'   => 'Ozutada',
'listusers-noresult' => 'Kävutajad ei olgoi löutud.',
'listusers-blocked'  => '(blokiruidud)',

# Special:ActiveUsers
'activeusers'          => 'Aktivižiden kävutajiden nimikirjutez',
'activeusers-from'     => 'Oyutada kävutajid, augotaden necišpäi:',
'activeusers-noresult' => 'Kävutajad ei olgoi löutud.',

# Special:Log/newusers
'newuserlogpage'              => 'Kävutajiden registracijan aigkirj',
'newuserlogpagetext'          => 'Tantoi registriruidud kävutajiden nimikirjutez.',
'newuserlog-byemail'          => 'peisana om oigetud e-počtadme',
'newuserlog-create-entry'     => "Uz' kävutai",
'newuserlog-create2-entry'    => 'om sänu uden $1-kävutajan registracii',
'newuserlog-autocreate-entry' => 'Registracii om sätud avtomatižešti',

# Special:ListGroupRights
'listgrouprights'                 => 'Kävutajiden gruppiden oiktused',
'listgrouprights-group'           => 'Grupp',
'listgrouprights-rights'          => 'Oiktused',
'listgrouprights-helppage'        => 'Help:Gruppiden oiktused',
'listgrouprights-members'         => '(ühtnijoiden nimikirjutez)',
'listgrouprights-addgroup'        => 'Voib ližata kävutajid {{PLURAL:$2|gruppha|gruppihe}}: $1',
'listgrouprights-removegroup'     => 'Voib čuta poiš {{PLURAL:$2|gruppan|gruppid}}: $1',
'listgrouprights-addgroup-all'    => 'Voib ližata ühtnijoid kaikihe gruppihe',
'listgrouprights-removegroup-all' => 'Voib čuta poiš kaik gruppad',
'listgrouprights-addgroup-self'   => 'Voib ližata {{PLURAL:$2|grupp|gruppad}} ičeleze: $1',

# E-mail user
'mailnologin'      => 'Ei ole adresan oigendamižen täht',
'emailuser'        => 'Oigeta e-kirjeine necile kävutajale',
'emailpage'        => 'Kirjeine kävutajale',
'usermailererror'  => 'Počtan adresat pörduti tedotusen petuses:',
'defemailsubject'  => 'E-počt {{SITENAME}}-saitalpäi',
'noemailtitle'     => 'Ei ole e-počtan adresad',
'noemailtext'      => 'Nece kävutai ei ole andnu ni-üht oiktad e-počtan adresad.',
'nowikiemailtitle' => 'Ei ole laskendad kirjeižid oigeta',
'nowikiemailtext'  => 'Nece kävutai ei tahtoi sada kirjeižid toižil kävutajilpäi.',
'email-legend'     => 'Oigeta kirjeine toižele {{SITENAME}}-saitan kävutajale',
'emailfrom'        => 'Oigendai:',
'emailto'          => 'Sai:',
'emailsubject'     => 'Tem:',
'emailmessage'     => 'Tedotuz:',
'emailsend'        => 'Oigeta',
'emailccme'        => 'Oigeta minei minun kirjeižen kopii.',
'emailccsubject'   => 'Teiden kirjeižen $1-kävutajale kopii: $2',
'emailsent'        => 'Kirjeine om oigetud',
'emailsenttext'    => 'Teiden e-kirjeine om oigetud.',

# Watchlist
'watchlist'          => 'Kaclendnimikirjutez',
'mywatchlist'        => 'Minun kaclendnimikirjutez',
'watchlistfor'       => "('''$1''')",
'nowatchlist'        => "Teiden kaclendnimikirjutez om pall'az.",
'watchlistanontext'  => 'Olgat hüväd, $1, miše lugeda vai redaktiruida teiden kaclendnimikirjutez.',
'watchnologin'       => 'Pidab kirjutadas sistemha',
'addedwatch'         => 'Om ližatud kaclendnimikirjuteshe',
'addedwatchtext'     => '"[[:$1]]"-lehtpol\' om ližadud teiden [[Special:Watchlist|kaclendnimikirjuteshe]]. Necen lehtpolen (i sidotud lehtpoliden) tulebiš toižetusiš voiškandeb tedištada neciš nimikirjutesespäi; necen polhe kirjutaškatas mugažo [[Special:RecentChanges|uziden toižetusiden lehtpolel]] lihavoitud kirjamil, miše oliži kebnemb homaita.',
'removedwatch'       => 'Om heittud kaclendnimikirjutesespäi',
'removedwatchtext'   => '"[[:$1]]"-lehtpol\' om heittud [[Special:Watchlist|teiden kaclendnimikirjutesespäi]].',
'watch'              => 'Pida sil’miš',
'watchthispage'      => "Kacelta necidä lehtpol't",
'unwatch'            => 'Ala kacle',
'unwatchthispage'    => 'Ala kacle',
'notanarticle'       => "Nece ei ole lehtpol'",
'notvisiblerev'      => 'Versijad oma čutud',
'watchlist-details'  => "Teiden kaclendnimikirjuteses om {{PLURAL:$1|$1 lehtpol'|$1 lehtpol't}}. Lodulehtpoled ei olgoi neciš lugus.",
'wlheader-enotif'    => '* Tedotand e-počtadme om kävutamas.',
'watchmethod-recent' => 'ozutadas kaceltud lehtpoliden tantoižed toižetused',
'watchmethod-list'   => 'kaceltud lehtpoliden kodvind tantoižid toižetusid ectes',
'watchlistcontains'  => "Teiden kaclendnimikirjuteses om $1 {{PLURAL:$1|lehtpol'|lehtpol't}}.",
'iteminvalidname'    => "'$1'-elemental om problem, sen nimi om vär...",
'wlshowlast'         => "Ozutada jäl'gmäižiš $1 časuiš da $2 päiviš $3",
'watchlist-options'  => 'Kaclendnimikirjutesen järgendused',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ližaduz kaclendnimikirjuteshe...',
'unwatching' => 'Heitmine kaclendnimikirjutesespäi...',

'enotif_mailer'                => "{{SITENAME}}-saitan lehtpol' om toižetadud - tedotuz",
'enotif_reset'                 => 'Znamoita kaik lehtpoled kut kactud',
'enotif_newpagetext'           => 'Nece om uz’ lehtpol’',
'enotif_impersonal_salutation' => '{{SITENAME}}-saitan kävutai',
'changed'                      => 'om toižetadud',
'created'                      => 'om sätud',
'enotif_subject'               => '$PAGEEDITOR om $CHANGEDORCREATED $PAGETITLE',
'enotif_lastvisited'           => "Kc. $1, miše nähta kaik teiden jäl'gmäižen vizitan jäl'ghe tehtud toižetused.",
'enotif_lastdiff'              => 'Kc. $1, miše kacelta toižetusid.',
'enotif_anon_editor'           => 'anonimine kävutai $1',
'enotif_body'                  => 'Kalliž $WATCHINGUSERNAME,


{{SITENAME}}-saitan $PAGETITLE-lehtpol\' om $CHANGEDORCREATED $PAGEEDITDATE; toižetusen avtor om $PAGEEDITOR, kc. $PAGETITLE_URL, miše nägištada jäl\'gmäine versii.

$NEWPAGE

Toižetajan ümbrikacund: $PAGESUMMARY $PAGEMINOREDIT

Säkat pagin toižetusiden avtoranke:
e-počt: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ku tö et tuleskekoi necile lehtpolile, ka sistem ei oigendaškande enamba teile tedotusid toižetusiš.
Tö voižit mugažo saubata tedotusiden opcii kaikiden lehtpoliden täht teiden kaclendnimikirjuteses.

             {{SITENAME}}-saitan ustavakaz tedotuzsistem

--
Miše toižetada teiden kaclendnimikirjutesen järgendused, mängat tänna:
{{fullurl:{{#special:Watchlist}}/edit}}

Miše čuta lehtpol\' teiden kaclendnimikirjutesespäi, mängat tänna:
$UNWATCHURL

Pid\'oitelend da joksii abu:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => "Čuta lehtpol' poiš",
'confirm'                => 'Vahvištoitkat',
'excontent'              => "südäimišt: '$1'",
'excontentauthor'        => "südäimišt: '$1' (üksjäine avtor oli '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "südäimišt edel puhtastamišt oli: '$1'",
'exblank'                => "lehtpol' oli pall'az",
'delete-confirm'         => '"$1"-lehtpolen čudand',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Čuta poiš',
'historywarning'         => 'Homaikat: lehtpolel, kudamb tö takait čuta, om istorii {{PLURAL:$1|ühtes redakcijaspäi|$1 redakcijoišpäi}}:',
'confirmdeletetext'      => "Tö takait čuta poiš lehtpol' kaiken sen istorijanke.
Olgat hüväd, vahvištoitkat, miše tö todeks himoičet čuta se, el'gendat ičetoi tegendan jäl'gused, i miše teiden tegend sättub [[{{MediaWiki:Policy-url}}|saitan politikha]].",
'actioncomplete'         => 'Tegend om loptud',
'actionfailed'           => 'Tegend lopihe onetomašti',
'deletedtext'            => '"<nowiki>$1</nowiki>" om čutud poiš.
Kc. $2, miše lugeda tantoižiden čudandoiden nimikirjutez.',
'deletedarticle'         => '"[[$1]]"-lehtpol\' om čutud poiš',
'suppressedarticle'      => '"[[$1]]"-lehtpol\' om peittud',
'dellogpage'             => 'Čudandoiden aigkirj',
'dellogpagetext'         => 'Naku om tantoižiden čudandoiden nimikirjutez.',
'deletionlog'            => 'čudandoiden aigkirj',
'reverted'               => 'Endištadud aigembaha versijahasai',
'deletecomment'          => 'Sü:',
'deleteotherreason'      => 'Toine sü/ližasü:',
'deletereasonotherlist'  => 'Toinejitte sü',
'deletereason-dropdown'  => '*Tipižed čudandan süd:
** Avtoran pakičend
** Avtoran oiktusen murenduz
** Vandalizm',
'delete-edit-reasonlist' => 'Redaktiruida čudandan süiden nimikirjutez',
'delete-toobig'          => "Necil lehtpolel om avar redaktiruinadan istorii - enamba {{PLURAL:$1|versii|versijad}}.
Mugoižiden lehtpoliden čudand om kel'tud, miše sait radaiži normaližikš.",
'delete-warning-toobig'  => 'Necil lehtpolel om avar redaktiruinadan istorii - enamba $1 {{PLURAL:$1|versii|versijad}}.
Mugoižiden lehtpoliden čudand voiži telustada {{SITENAME}}-saitan andmuzbazan normaližele radole.
Tehkat kaik varumujandanke!',

# Rollback
'rollback'         => 'Endištada toižetused',
'rollback_short'   => 'Endištuz',
'rollbacklink'     => 'Endištada',
'rollbackfailed'   => 'Endištuz om keskustadud petusen tagut.',
'cantrollback'     => "Ei voi endištada toižetusid;
Jäl'gmäine toižetai om üksjäižen lehtpolen avtoran.",
'alreadyrolled'    => "Ei voi endištada jäl'gmäšt [[:$1]]-lehtpolen redakcijad (avtor: [[User:$2|$2]]). ([[User talk:$2|Lodud]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); ken-se toine om jo redaktiruinu vai endištanu sen.

Jäl'gmäižed toižetused om tehnu [[User:$3|$3]] ([[User talk:$3|Lodud]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'      => "Redaktiruindan ümbrikacund oli: \"''\$1''\".",
'revertpage'       => 'Kävutajan [[Special:Contributions/$2|$2]] ([[User talk:$2|Lodud]]) tožetused oma endištadud edeližhe versijahasai (avtor: [[User:$1|$1]])',
'rollback-success' => '$1-kävutajan toižetused oma endištadud $2-kävutajan versijahasai.',

# Edit tokens
'sessionfailure' => 'Om problemoid nügüdläižes radseansas; nece tegend om kel\'tud, miše kaita "seansan anastusespäi".
Olgat hüväd, paingat "tagaze"-kingitimele i jügutoitkat lehtpol\' udes, sid\' naprigat toštta tegend völ kerdan.',

# Protect
'protectlogpage'              => 'Kaičendan aigkirj',
'protectlogtext'              => 'Naku om lehtpoliden luklostandoiden da avaidandoiden aigkirj.
Kc. [[Special:ProtectedPages|kaitud lehtpoliden nimikirjutez]].',
'protectedarticle'            => '"[[$1]]"-lehtpol\' om kaitud',
'modifiedarticleprotection'   => '"[[$1]]"-lehtpolen kaičendpind om toižetadud',
'unprotectedarticle'          => 'Kaičend om heittud "[[$1]]"-lehtpolespäi',
'movedarticleprotection'      => 'Kaičendan järgendused oma sirtud "[[$2]]"-lehtpolespäi "[[$1]]"-lehtpol\'he.',
'protect-title'               => '"$1"-lehtpolen kaičendpindan toižetuz',
'prot_1movedto2'              => '[[$1]] om nimitadud [[$2]]-nimel',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Vahvištoitkat kaičendan augotiž',
'protectcomment'              => 'Sü:',
'protectexpiry'               => 'lopstrok:',
'protect_expiry_invalid'      => 'Lopstrok om vär.',
'protect_expiry_old'          => 'Lopstrokun aig om jo männu.',
'protect-unchain-permissions' => 'Avaita kaičendan ližaparametrad',
'protect-text'                => "Sab nähta da toižetada kaičendpind naku '''<nowiki>$1</nowiki>'''-lehtpolen täht.",
'protect-locked-blocked'      => "Teiden registracii om blokiruidud, i sikš tö et voigoi toižetada lehtpolen kaičendpindad.
Naku oma nügüdläižed järgendused '''$1'''-lehtpolen täht.",
'protect-locked-dblock'       => "Ei sa toižetada lehtpolen kaičendpindad, sikš miše päandmuzbaz om blokiruidud aigaks.
Naku oma nügüdläižed järgendused '''$1'''-lehtpolen täht:",
'protect-locked-access'       => "Teiden registracijal ei ole oiktust lehtpoliden kaičendpindoid toižetada.
Naku oma nügüdläižed järgendused '''$1'''-lehtpolen täht:",
'protect-cascadeon'           => "Nece lehtpol' om nügüd' kaitud, sikš miše se om mülütadud {{PLURAL:$1|lehtpol'he, kudamb om|lehtpolihe, kudambad oma}} kaitud kaskadkaičendal.
Sab toižetada necen lehtpolen kaičendpind, no se ei voi toižetada kaskadkaičendad.",
'protect-default'             => 'Kaičemata',
'protect-fallback'            => 'Pidab sada "$1"-kävutajan laskend',
'protect-level-autoconfirmed' => 'Blokiruida uzid da registriruimatomid kävutajid',
'protect-level-sysop'         => 'Vaiše administratorad',
'protect-summary-cascade'     => 'Kaskadine',
'protect-expiring'            => 'strok lähteb datal $1 (UTC)',
'protect-expiry-indefinite'   => 'strokuta',
'protect-cascade'             => "Kaita neche lehtpol'he mülütadud lehtesed (kaskadkaičend)",
'protect-cantedit'            => 'Teile ei sa toižetada necen lehtpolen kaičendpindad, sikš miše teil ei ole laskendad toižetada sidä.',
'protect-othertime'           => 'Toine aig:',
'protect-othertime-op'        => 'toine aig',
'protect-existing-expiry'     => 'Nügüdläine lopstrokun aig: $3, $2',
'protect-otherreason'         => 'Toine sü/ližasü:',
'protect-otherreason-op'      => 'toine sü/ližasü',
'protect-dropdown'            => '*Kaičendan tipižed süd
** Jatkui vandalizm
** Jatkui spamoind
** Satusitoi redakcijoiden soda
** Luja lehtpolen populärižuz',
'protect-edit-reasonlist'     => 'Redaktiruida kaičendan süd',
'protect-expiry-options'      => "1 čas:1 hour,1 päiv:1 day,1 nedal':1 week,2 nedalid:2 weeks,1 ku:1 month,3 kud:3 months,6 kud:6 months,1 voz':1 year,strokuta:infinite",
'restriction-type'            => 'Oiktused:',
'restriction-level'           => 'Tulendan laskendpind:',
'minimum-size'                => 'Minimaline suruz',
'maximum-size'                => 'Maksimaline suruz:',
'pagesize'                    => '(baitad)',

# Restrictions (nouns)
'restriction-edit'   => 'Redaktiruind',
'restriction-move'   => 'Udesnimitand',
'restriction-create' => 'Säta',
'restriction-upload' => 'Jügutoitand',

# Restriction levels
'restriction-level-sysop'         => "Täuz' kaičend",
'restriction-level-autoconfirmed' => 'Polesine kaičend',
'restriction-level-all'           => 'kaik pindad',

# Undelete
'undelete'                   => 'Kirjutada čutud lehtpoled',
'undeletepage'               => 'Lugeda da endištada čutud lehtpoled',
'viewdeletedpage'            => 'Lugeda čutud lehtpolid',
'undelete-fieldset-title'    => 'Endištada versijad',
'undeleterevisions'          => '$1 {{PLURAL:$1|versii|versijad}} om arhiviruidud',
'undelete-nodiff'            => 'Ei voi löuta edelišt versijad.',
'undeletebtn'                => 'Endištada',
'undeletelink'               => 'endištada',
'undeleteviewlink'           => 'nähta',
'undeletereset'              => 'Puhtastada',
'undeletecomment'            => 'Kommentarii:',
'undeletedarticle'           => 'om endištanu "[[$1]]"',
'undeletedrevisions'         => '{{PLURAL:$1|1 toižetuz|$1 toižetust}} om endištadud',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 toižetuz|$1 toižetust}} da {{PLURAL:$2|1 fail|$2 failad}} oma endištadud',
'undeletedfiles'             => '{{PLURAL:$1|1 fail om|$1 failad oma}} endištadud',
'cannotundelete'             => 'Endištandan petuz;
Voib olda, ken-se om jo endištanu necen lehtpolen ende.',
'undeletedpage'              => "'''$1-lehtpol' om endištadud'''

Kc. [[Special:Log/delete|čudandoiden aigkirj]], miše tedištada tantoižiš čudandoiš da endištandoiš.",
'undelete-header'            => 'Kc. [[Special:Log/delete|čudendoiden aigkirj]], miše tedištada tantoi čudud lehtpoliš.',
'undelete-search-box'        => 'Ectä čutud lehtpolid',
'undelete-search-prefix'     => 'Ozutada lehtpoled, kudambad augotase mugomal tekstal:',
'undelete-search-submit'     => 'Ectä',
'undelete-cleanup-error'     => 'Kävutamatoman "$1"-arhivfailan čudandan petuz.',
'undelete-error-short'       => 'Failan endištandan petuz: $1',
'undelete-error-long'        => 'Necen failan endištamižen aigan ozaižihe petused:

$1',
'undelete-show-file-confirm' => 'Todest-ik tahtoit nähta "<nowiki>$1</nowiki>"-failan versii aigalpäi $2 $3?',
'undelete-show-file-submit'  => 'Ka',

# Namespace form on various pages
'namespace'      => 'Nimiavaruz:',
'invert'         => 'Käta erigoittud sanad vastkarin',
'blanknamespace' => '(Pälehtpoled)',

# Contributions
'contributions'       => 'Kävutajan tond',
'contributions-title' => '$1-kävutajan tond',
'mycontris'           => 'Minun tond',
'contribsub2'         => '$1-kävutajan ($2) tond',
'uctop'               => "(jäl'gmäine)",
'month'               => 'Ku:',
'year'                => 'Voz’:',

'sp-contributions-newbies'        => 'Ozutada vaiše uziden kävutajiden tondad',
'sp-contributions-newbies-sub'    => 'Uziden registracijoiden täht',
'sp-contributions-newbies-title'  => 'Uziden kävutajiden tond',
'sp-contributions-blocklog'       => 'Blokiruindoiden aigkirj',
'sp-contributions-deleted'        => 'Čutud kävutajan tond',
'sp-contributions-logs'           => 'aigkirjad',
'sp-contributions-talk'           => 'lodu',
'sp-contributions-userrights'     => 'kävutajiden oiktusiden ohjandamine',
'sp-contributions-blocked-notice' => "Nece kävutai om nügüd' blokiruidud.
Alemba om anttud jälgmäine kirjutuz blokiruindaiglehtesespäi:",
'sp-contributions-search'         => 'Ectä tond',
'sp-contributions-username'       => 'IP-adres vai kävutajan nimi:',
'sp-contributions-submit'         => 'Ectä',

# What links here
'whatlinkshere'            => 'Kosketused - nakhu',
'whatlinkshere-title'      => 'Lehtpoled, kudambad kosketadas "$1"-lehtpolen',
'whatlinkshere-page'       => 'Lehtpol’:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Nened lehtpoled kosketadas '''[[:$1]]''':",
'nolinkshere'              => "'''[[:$1]]'''-lehtpol't ei kosketa ni üks' lehtpol'.",
'nolinkshere-ns'           => "'''[[:$1]]'''-lehtpol't ei kosketa ni üks' lehtpol' valitud nimiavarudes.",
'isredirect'               => "Oigendai lehtpol'",
'istemplate'               => 'mülütand',
'isimage'                  => 'Kosketuz kuvaspäi',
'whatlinkshere-prev'       => "{{PLURAL:$1|edeline lehtpol'|$1 edelišt lehtpol't}}",
'whatlinkshere-next'       => "{{PLURAL:$1|jäl'ghine lehtpol'|$1 jäl'ghišt lehtpol't}}",
'whatlinkshere-links'      => '← kosketused',
'whatlinkshere-hideredirs' => '$1 oigendused',
'whatlinkshere-hidetrans'  => '$1 mülütandad',
'whatlinkshere-hidelinks'  => '$1 kosketused',
'whatlinkshere-hideimages' => '$1 kosketused kuvišpäi',
'whatlinkshere-filters'    => "Fil'trad",

# Block/unblock
'blockip'                         => 'Blokiruida kävutajad',
'blockip-title'                   => 'Blokiruida kävutajad',
'blockip-legend'                  => 'Blokiruida kävutajad',
'blockiptext'                     => 'Kävutagat alemba anttud form, miše blokiruida kirjutandan voimuz märitud IP-adresaspäi.
Nece sab tehta vaiše sen täht, miše borcuidas vandalizmanke, kut om kirjutadud [[{{MediaWiki:Policy-url}}|ohjandimiš]].
Kirjutagat sü alemba (ozutesikš, citiruigat vandaliziruidud lehtpoled).',
'ipaddress'                       => 'IP-adres:',
'ipadressorusername'              => 'IP-adres vai kävutajan nimi:',
'ipbexpiry'                       => 'Lopindan strok:',
'ipbreason'                       => 'Sü:',
'ipbreasonotherlist'              => 'Toine sü',
'ipbreason-dropdown'              => "*Blokiruindan päsüd
** Väran informacijan andand
** Lehtpoliden südäimišton čudand
** Spamkosketused irdsaitoihe
** Hamatoman tekstan da rujon kirjutamine lehtpolile
** Toižiden kävutajiden pöl'gästoitand
** Erazvuiččiden registracijoiden kävutand ühtel ristitul
** Paha kävutajan nimi",
'ipbanononly'                     => 'Blokiruida vaiše anonimoid',
'ipbcreateaccount'                => "Kel'ta uded registracijad",
'ipbemailban'                     => "Kel'ta kävutajid e-počtan oigendamaspäi",
'ipbenableautoblock'              => 'Blokiruida avtomatižešti kävutajal kävutadud IP-adresad',
'ipbsubmit'                       => 'Blokiruida nece kävutai',
'ipbother'                        => 'Toine aig:',
'ipboptions'                      => "2 časud:2 hours,1 päiv:1 day,3 päiväd:3 days,1 nedal':1 week,2 nedalid:2 weeks,1 ku:1 month,3 kud:3 months,6 kud:6 months,1 voz':1 year,strokuta:infinite",
'ipbotheroption'                  => 'toine',
'ipbotherreason'                  => 'Toine sü/Ližasü:',
'ipbhidename'                     => 'Peitta kävutajan nimi redakcijoišpäi da nimikirjutesišpäi',
'ipbwatchuser'                    => "Kacelta necen kävutajan personališt lehtpol't da lodulehtpol't",
'ipballowusertalk'                => "Laskta necile blokiruidud kävutajale redaktiruida ičeze lodulehtpol'",
'ipb-change-block'                => 'Blokirujda kävutajad udes neniden järgendusidenke',
'badipaddress'                    => 'Vär IP-adres',
'blockipsuccesssub'               => 'Blokiruind om tehtud',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] om blokiruidud.<br />
Kc. [[Special:IPBlockList|blokiruidud IP-adresoiden nimikirjuteshe]].',
'ipb-edit-dropdown'               => 'Redaktiruida süiden nimikirjutez',
'ipb-unblock-addr'                => 'Heitta blokiruind kävutajalpäi $1',
'ipb-unblock'                     => 'Heitta blokiruind kävutajan nimelpäi vai IP-adresalpäi',
'ipb-blocklist-addr'              => 'Kävutajan $1 aktualižed blokiruindad',
'ipb-blocklist'                   => 'Ozutada aktualižed blokiruindad',
'ipb-blocklist-contribs'          => 'Kävutajan $1 tond',
'unblockip'                       => 'Heitta blokiruind IP-adresalpäi',
'unblockiptext'                   => 'Kävutagat nece form, miše endištada kirjutandvoimuz blokiruidud IP-adresalpäi vai kävutajan nimelpäi.',
'ipusubmit'                       => 'Heitta nece blokiruind',
'unblocked'                       => '[[User:$1|$1]]-kävutajan blokiruind om heittud',
'unblocked-id'                    => 'Blokiruind $1 om heittud',
'ipblocklist'                     => 'Blokiruidud IP-adresad da kävutajiden nimed',
'ipblocklist-legend'              => 'Löuta blokiruidud kävutajad',
'ipblocklist-username'            => 'Kävutajan nimi vai IP-adres:',
'ipblocklist-sh-userblocks'       => '$1 kävutajiden nimiden blokiruindad',
'ipblocklist-sh-tempblocks'       => '$1 pordaigaližed blokiruindad',
'ipblocklist-sh-addressblocks'    => '$1 erasiden IP-den blokiruindad',
'ipblocklist-submit'              => 'Ecind',
'ipblocklist-localblock'          => 'Sijaline blokiruind',
'blocklistline'                   => '$1, $2 om blokiruinu kävutajad $3 ($4)',
'infiniteblock'                   => 'strokutoi blokiruind',
'expiringblock'                   => 'lopiše $1 $2',
'anononlyblock'                   => 'vaiše anonimad',
'noautoblockblock'                => 'avtoblokiruind om sauptud',
'createaccountblock'              => 'Uziden kävutajiden registriruind om blokiruidud',
'emailblock'                      => 'e-počt om blokiruidud',
'blocklist-nousertalk'            => "ei sa redaktiruida ičeze lodulehtpol't",
'ipblocklist-empty'               => 'Blokiruindoiden nimikirjutez om palläz.',
'ipblocklist-no-results'          => 'Ectud IP-adres vai kävutajan nimi ei ole blokiruidud.',
'blocklink'                       => 'blokiruida',
'unblocklink'                     => 'heitta blokiruind',
'change-blocklink'                => 'toižetada blokiruind',
'contribslink'                    => 'tond',
'autoblocker'                     => 'Blokiruidud avtomatižešti, sikš miše teiden IP-adres om tantoi kävutanu "[[User:$1|$1]]".
Adresan blokiruindan sü - "$2".',
'blocklogpage'                    => 'Blokiruindoiden aigkirj',
'blocklog-showlog'                => 'Nece kävutai blokiruiltihe jo.
Alemba om anttud blokiruindaiglehtez:',
'blocklogentry'                   => 'blokiruidihe [[$1]] pordoks $2 $3',
'reblock-logentry'                => 'om toižetanu [[$1]]-kävutajan blokiruindan järgendused. Blokiruind lopiše $2 $3',
'unblocklogentry'                 => 'om heitnu $1-kävutajan blokiruindan',
'block-log-flags-anononly'        => 'vaiše anonimižed kävutajad',
'block-log-flags-nocreate'        => "uziden kävutajiden registracii om kel'tud",
'block-log-flags-noautoblock'     => 'avtoblokiruind ei ole kävutamas',
'block-log-flags-noemail'         => "e-počtan oigendamine om kel'tud",
'block-log-flags-nousertalk'      => "ei voi redaktiruida ičeze lodulehtpol't",
'block-log-flags-angry-autoblock' => 'levitadud avtoblokiruind om kävutamas',
'block-log-flags-hiddenname'      => 'kävutajan nimi om peittud',
'range_block_disabled'            => 'Administratoriden oiktuz blokiruida diapazonad ei ole kävutamas.',
'ipb_expiry_invalid'              => 'Vär lopstrok.',
'ipb_expiry_temp'                 => 'Peittud kävutajiden nimiden blokiruindoile pidab olda strokutomin.',
'ipb_hide_invalid'                => 'Ei sa peitta registracijad. Voib olda, sišpäi on tehtud äjahk redakcijoid.',
'ipb_already_blocked'             => '"$1" om jo blokiruidud',
'ipb-needreblock'                 => '== Om jo blokiruidud ==
$1 om jo blokiruidud. Tahtoižit-ik toižetada järgendusid?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Toine blokiruind|Toižed blokiruindad}}',
'ipb_cant_unblock'                => 'Petuz: ei voi löuta ID $1:n blokiruindad.
Voib olda, se om jo heittud.',
'ip_range_invalid'                => 'Vär IP-diapazon.',
'blockme'                         => 'Blokiruigat mindai',
'proxyblocker'                    => 'Proxy-blokator',
'proxyblocker-disabled'           => 'Nece funkcii ei ole kävutamas.',
'proxyblockreason'                => 'Teiden IP-adres om blokiruidud, sikš miše se om avoin proksi.
Olgat hüväd, säkat pagin teiden Internet-provaideranke i kirjutagat hänele necen varuitomuden problemas.',
'proxyblocksuccess'               => 'Vaumiž.',
'sorbsreason'                     => 'Teiden IP-adres om ozutadud kut avaitud proksi {{SITENAME}}-saitan DNSBL-an mustas nimikirjuteses.',
'cant-block-while-blocked'        => 'Teile ei sa blokiruida toižid kävutajid, sikš miše tö iče olet blokiruidud.',

# Developer tools
'lockdb'              => 'Luklostada andmusiden baz',
'unlockdb'            => 'Heitta luklostand andmusiden bazalpäi',
'lockconfirm'         => 'Ka, minä todest tahtoin luklostada andmusiden baz.',
'unlockconfirm'       => 'Ka, minä todest tahtoin heitta luklostand andmusiden bazalpäi.',
'lockbtn'             => 'Luklostada andmusiden baz',
'unlockbtn'           => 'Heitta luklostand andmusiden bazalpäi',
'locknoconfirm'       => 'Pidab panda lindut vahvištoitandpöudho.',
'lockdbsuccesssub'    => 'Andmusiden baz om luklostadud',
'unlockdbsuccesssub'  => 'Luklostand om heittud andmusiden bazalpäi',
'lockdbsuccesstext'   => "Andmusiden baz om luklostadud.<br />
Algat unohtagoi [[Special:UnlockDB|heitta luklostand]] teiden holituzradon jäl'ghe.",
'unlockdbsuccesstext' => 'Luklostand om heittud andmusiden bazalpäi.',
'databasenotlocked'   => 'Andmusiden baz ei ole luklostadud.',

# Move page
'move-page'                 => 'Udesnimitada $1',
'move-page-backlink'        => '← $1',
'move-page-legend'          => "Udesnimitada lehtpol'",
'movepagetext'              => "Alemba anttud formad kävutaden sab nimitada lehtpolen udes; siloi kaclendnimikirjuteses se mugažo linneb uden nimenke.
Vanhemb nimi linneb kosketuseks udembale nimele.
Sab udištada avtomatižešti kosketusid vanhembale nimele.
Ku tö et tahtoigoi tehta necidä, olgat hüväd, kodvgat, ei-k ole lehtpolil [[Special:DoubleRedirects|kaksitadud]] vai [[Special:BrokenRedirects|rebitadud]] kosketusid.
Tö pidat vastust siš, miše kaik kosketused ozutaižiba sinnä, kuna tarbiž.

Otkat sil'mnägubale, miše lehtpol' ei nimitagoi udes, ku om jo lehtpol' mugoižen nimenke, siš statjoiš päiči, konz se om pall'az, sil ei ole toižetuzistorijad, vai se om oigendamižlehtpol'.
Nece znamoičeb, miše tö voit pörtta lehtpolen vanhemb nimi, ku tö olet petnus, no et voigoi čuta statjaližikš tarbhašt lehtpol't.

'''HOMAIKAT!'''
Udesnimituz voib toda masštabižid da varastamatomid toižetusid ''populärižile'' lehtpolile.
Olgat hüväd, vahvištoitkatoiš, miše tö el'gendat kaik jäl'gused.",
'movepagetalktext'          => "Sidotud lodulehtpol' udesnimitadas avtomatižikš, '''päiči niiš statjoiš, konz:'''
*Om jo täuttud lodulehtpol' mugoižen nimenke;
*Tö ei olgoi pannuded \"lindušt\" pöudho alemba.

Nenil statjoil tegese sirta vai ühtenzoitta lehtpoled ičeksaz.",
'movearticle'               => "Udesnimitada lehtpol':",
'movenologin'               => 'Tö et olgoi kirjutanus sistemha',
'movenotallowed'            => 'Teile ei sa udesnimitada lehtpolid.',
'movenotallowedfile'        => 'Teile ei sa udesnimitada failoid.',
'cant-move-user-page'       => 'Teile ei sa udesnimitada kävutajiden pälehtpolid.',
'newtitle'                  => "Uz' nimi:",
'move-watch'                => "Kacelta necidä lehtpol't",
'movepagebtn'               => "Udesnimitada lehtpol'",
'pagemovedsub'              => "Lehtpol' om nimitadud udes",
'movepage-moved'            => "'''\"\$1\" om nimitadud udes; uz' nimi om \"\$2\"'''",
'movepage-moved-redirect'   => 'Läbikosketuz om tehtud.',
'movepage-moved-noredirect' => "Läbikosketusen sämine om kel'tud.",
'articleexists'             => "Lehtpol' mugoižen nimenke om jo wikiš, vai valitud nimi ei sättu. Olgat hüväd, valikat toine nimi.",
'cantmove-titleprotected'   => "Ei sa udesnimitada lehtpol't, sikš miše uz' nimi om kel'tud nimiden nimikirjuteses.",
'talkexists'                => "'''Iče lehtpol' om nimitadud udes jügedusita, no lodulehtpol't ei voi udesnimitada, sikš miše wikiš om jo lodulehtpol' mugoižen nimenke. Pidab erigoitta niid ičeksaz.'''",
'movedto'                   => 'Sirtud udhe tahoze',
'movetalk'                  => "Udesnimitada sidotud lodulehtpol'",
'move-subpages'             => 'Udesnimitada alalehtpoled ($1-hesai)',
'1movedto2'                 => "om nimitanu udes [[$1]]; uz' nimi om [[$2]]",
'1movedto2_redir'           => '[[$1]] om nimitadud udel nimel "[[$2]]" udesoigendamižen päle.',
'move-redirect-suppressed'  => 'läbikosketuz om azotadud',
'movelogpage'               => 'Udesnimitandoiden aigkirj',
'movelogpagetext'           => 'Naku om kaikiden lehtesiden udesnimitandoiden nimikirjutez.',
'movesubpage'               => "{{PLURAL:$1|Alalehtpol'|Alalehtpol't}}",
'movenosubpage'             => 'Necil lehtpolel ei ole alalehtpolid.',
'movereason'                => 'Sü:',
'revertmove'                => 'heitta pätand',
'delete_and_move'           => 'Čuta poiš da udesnimitada',
'delete_and_move_confirm'   => "Ka, čuta lehtpol' poiš",
'delete_and_move_reason'    => 'Čutud poiš udesnimitamižen voimusen täht.',
'immobile-source-namespace' => 'Ei voi udesnimitada lehtpolid "$1"-nimiavaruses',
'immobile-target-namespace' => 'Ei voi udesnimitada lehtpolid "$1"-nimiavarushe',
'immobile-source-page'      => "Necidä lehtpol't ei sa udesnimitada.",
'immobile-target-page'      => 'Ei sa antta lehtpolele nece nimi.',
'imagenocrossnamespace'     => 'Ei sa antta kuvale nimi toižes nimiavarusespäi.',
'imagetypemismatch'         => "Uz' failan liža ei sättu sen tipale",
'imageinvalidfilename'      => 'Metfailan nimi om vär',
'move-leave-redirect'       => 'Jätta läbikosketuz',

# Export
'export'            => 'Lehtpoliden eksport',
'exportcuronly'     => 'Mülütada vaiše nügüdläine versii täudeta istorijata',
'export-submit'     => 'Eksport',
'export-addcattext' => 'Kaiked lehtesed kategorijaspäi:',
'export-addcat'     => 'Liža',
'export-addnstext'  => 'Ližata lehtpolid nimiavarusespäi:',
'export-addns'      => 'Ližata',
'export-download'   => 'Panda muštho kut fail',
'export-templates'  => 'Mülütada šablonad',

# Namespace 8 related
'allmessages'                   => 'Sisteman tedotused',
'allmessagesname'               => 'Nimi',
'allmessagesdefault'            => 'Tekst augotižjärgendusen mödhe',
'allmessagescurrent'            => 'Nügüdläine tekst',
'allmessagesnotsupportedDB'     => "Nece lehtpol' ei ole kävutamižes, sikš miše '''\$wgUseDatabaseMessages'''-opcii ei ole radmas.",
'allmessages-filter-legend'     => "Fil'tr",
'allmessages-filter'            => "Fil'tr toižetusen statusan mödhe:",
'allmessages-filter-unmodified' => 'Toižetamatomad',
'allmessages-filter-all'        => 'Kaik',
'allmessages-filter-modified'   => 'Toižetadud',
'allmessages-prefix'            => "Fil'tr prefiksan mödhe:",
'allmessages-language'          => "Kel'",
'allmessages-filter-submit'     => 'Mäne',

# Thumbnails
'thumbnail-more'           => 'Tobjeta',
'filemissing'              => 'Fail ei ole löutud',
'thumbnail_error'          => 'Pičukuvan sädandan petuz: $1',
'djvu_page_error'          => 'En voi sadas DjVu-lehtpolen nomerhasai',
'djvu_no_xml'              => 'Ei voi sada XMLad DjVu-failan täht',
'thumbnail_invalid_params' => 'Värad pičukuvan parametrad',
'thumbnail_dest_directory' => 'Ei voi säta metfailhodrad',
'thumbnail_image-type'     => "Kuvan tip ei ole pid'oiteldud",
'thumbnail_gd-library'     => 'Vajag GD-kirjišton konfiguracii: ei ole $1-funkcijad',
'thumbnail_image-missing'  => 'Näguse, ei ole $1-failad',

# Special:Import
'import'                     => 'Toda lehtesid toižiš saitoišpäi',
'importinterwiki'            => 'Transwiki-tomine',
'import-interwiki-source'    => "Wikipurde/lehtpol':",
'import-interwiki-history'   => 'Kopiruida kaik necen lehtpolen toižetamižen istorii',
'import-interwiki-templates' => 'Mülütada kaik šablonad',
'import-interwiki-submit'    => 'Import',
'import-interwiki-namespace' => 'Metnimiavaruz:',
'import-upload-filename'     => 'Failan nimi:',
'import-comment'             => 'Kommentarii:',
'importstart'                => 'Lehtpoliden import...',
'import-revision-count'      => '$1 {{PLURAL:$1|versii|versijad}}',
'importnopages'              => 'Ei ole lehtpolid importiruides.',
'importfailed'               => 'Ei voi importiruida: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Importiruidud lehtpolen tundmatoi tip',
'importcantopen'             => 'Ei voi avaita importiruidud failad',
'importbadinterwiki'         => 'Vär interwiki-kosketuz',
'importnotext'               => 'Ei ole tekstad',
'importsuccess'              => 'Import lopihe!',
'importnofile'               => 'Fail importaks ei ole jügutoittud.',
'importuploaderrorsize'      => 'Ei voi jügutoitta vai importiruida failad.
Failan suruz om suremb pandud üläröunad.',
'importuploaderrorpartial'   => 'Ei voi jügutoitta vai importiruida failad.
Vaiše failan pala om jügutoittud.',
'importuploaderrortemp'      => 'Ei voi jügutoitta vai importiruida failad.
Ei ole pordaigašt failhodrad.',
'import-parse-failure'       => 'XML-tundištandan petuz importan aigan',
'import-noarticle'           => "Ei ole lehtpol't importiruides!",
'import-nonewrevisions'      => 'Kaik redakcijad oma importiruidud aigemba.',
'import-upload'              => 'Jügutoitta XML-andmusid',
'import-token-mismatch'      => 'Andmused oma kadonuded. Olgat hüväd, toštkat tegend völ kerdan.',
'import-invalid-interwiki'   => 'Ei voi importiruida neciš wikišpäi.',

# Import log
'importlogpage'                    => 'Importan aigkirj',
'import-logentry-upload-detail'    => "{{PLURAL:$1|üks' versii|$1 versijad}}",
'import-logentry-interwiki'        => 'toi toižes wikišpäi lehtpolen $1',
'import-logentry-interwiki-detail' => "$1 {{PLURAL:$1|üks' versii|versijad}} $2-wikišpäi",

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Teiden kävutajan lehtpol'",
'tooltip-pt-anonuserpage'         => "Kähutajan lehtpol' minun IP-n täht",
'tooltip-pt-mytalk'               => "Teiden lodulehtpol'",
'tooltip-pt-preferences'          => 'Teiden järgendused',
'tooltip-pt-watchlist'            => 'Lehtpoled, kudambid minä kaclen',
'tooltip-pt-mycontris'            => 'Minun redakcijoiden nimikirjutez',
'tooltip-pt-login'                => 'Naku sab kirjutadas sistemha, no necidä ei tarbiž radon täht',
'tooltip-pt-anonlogin'            => 'Naku sab kirjutadas sistemha, no necidä ei tarbiž radon täht',
'tooltip-pt-logout'               => 'Lähtta sistemaspäi',
'tooltip-ca-talk'                 => 'Diskussii neciš lehtpoles',
'tooltip-ca-edit'                 => "Sab redaktiruida necidä lehtpol't.
Olgat hüväd, kävutagat ezikacund.",
'tooltip-ca-addsection'           => "Augotada uz' jaguz",
'tooltip-ca-viewsource'           => "Nece lehtpol' om kaitud.
Sab lugeda sen augotižkod da kopiruida se.",
'tooltip-ca-history'              => 'Necen lehtpolen enččed versijad',
'tooltip-ca-protect'              => "Kaita necidä lehtpol't",
'tooltip-ca-unprotect'            => 'Heitta necen lehtpolen kaičend',
'tooltip-ca-delete'               => "Čuta poiš nece lehtpol'",
'tooltip-ca-undelete'             => 'Endištada kaik toižetused, kudambad oliba tehtud edel lehtpolen čudandad',
'tooltip-ca-move'                 => "Udesnimitada nece lehtpol'",
'tooltip-ca-watch'                => "Ližata nece lehtpol' teiden kaclendnimikirjuteshe",
'tooltip-ca-unwatch'              => "Čuta nece lehtpol' teiden kaclendnimikirjutesespäi",
'tooltip-search'                  => 'Ectä {{SITENAME}}-saitaspäi',
'tooltip-search-go'               => 'Mända lehtpolele, kudambal om tarkoiktas mugoi nimi',
'tooltip-search-fulltext'         => 'Ectä lehtpoled, kudambil om mugoi tekst',
'tooltip-p-logo'                  => "Pälehtpol'",
'tooltip-n-mainpage'              => 'Mäne pälehtpolele',
'tooltip-n-mainpage-description'  => 'Tulgat pälehtpolele',
'tooltip-n-portal'                => 'Lodud projektas',
'tooltip-n-currentevents'         => 'Löuta ližainformacijad nügüdläižiš aigtegoiš',
'tooltip-n-recentchanges'         => 'Tantoižiden toižetusiden nimikirjutez',
'tooltip-n-randompage'            => "Kacta statjaline lehtpol'",
'tooltip-n-help'                  => 'Abu',
'tooltip-t-whatlinkshere'         => "Lehtpoled, kudambad kosketadas necidä lehtpol't",
'tooltip-t-recentchangeslinked'   => "Tantoižed toižetused lehtpolil, kudambid kosketab nece lehtpol'",
'tooltip-feed-rss'                => 'RSS-purde necen lehtpolen täht',
'tooltip-feed-atom'               => 'Atom-purde necen lehtpolen täht',
'tooltip-t-contributions'         => 'Kaikiden necil kävutajal toižetadud lehtpoliden nimikirjutez',
'tooltip-t-emailuser'             => 'Oigeta e-počt necile kävutajale',
'tooltip-t-upload'                => 'Jügutoitta failad',
'tooltip-t-specialpages'          => 'Kaikiden specialižiden lehtpoliden nimikirutez',
'tooltip-t-print'                 => 'Necen lehtpolen versii painmižen täht',
'tooltip-t-permalink'             => 'Kaikenaigaine necen lehtpolen versijan kosketuz',
'tooltip-ca-nstab-main'           => "Südäimištlehtpol'",
'tooltip-ca-nstab-user'           => "Kacta kävutajan lehtpol'he",
'tooltip-ca-nstab-media'          => 'Mediafail',
'tooltip-ca-nstab-special'        => "Nece om specialine lehtpol', tö et voigoi toižetada sidä",
'tooltip-ca-nstab-project'        => "Kacta projektan lehtpol'he",
'tooltip-ca-nstab-image'          => "Kacta failan lehtpol'he",
'tooltip-ca-nstab-mediawiki'      => 'Lugeda sisteman tedotuz',
'tooltip-ca-nstab-template'       => 'Kacta šablonha',
'tooltip-ca-nstab-help'           => "Lugeda abulehtpol'",
'tooltip-ca-nstab-category'       => "Kacta kategorijan lehtpol'he",
'tooltip-minoredit'               => "Znamoita nece toižetuz kut pen'",
'tooltip-save'                    => 'Kirjutada teiden toižetused',
'tooltip-preview'                 => 'Ezikacelta teiden toižetusid. Olgat hüväd, kävutagat edel lopkirjutandad!',
'tooltip-diff'                    => 'Ozutada tantoižed toižetused',
'tooltip-compareselectedversions' => 'Kacelta valitud versijoiden erod',
'tooltip-watch'                   => "Ližata nece lehtpol' kaclendnimikirjuteshe",
'tooltip-recreate'                => "Endištada nece lehtpol'",
'tooltip-upload'                  => 'Augotada jügutoitand',
'tooltip-rollback'                => "Čukat jäl'gmäižel redaktoral tehtud toižetused ühtel painandal",
'tooltip-undo'                    => "Čuta jäl'gmäine toižetuz i avaita redaktiruindan lehtpol'. Sab kirjutada čudandad sü.",

# Stylesheets
'common.css' => '/* Pandud tänna CSS painastaškandeb kaikile irdnägun temoile */',

# Attribution
'siteuser'         => '{{SITENAME}}-saitan kävutai $1',
'anonuser'         => '{{GRAMMAR:genitive|{{SITENAME}}}} anonimine kävutai $1',
'lastmodifiedatby' => "Necidä lehtpol't toižeti jäl'gmäižen kerdan $2, $1 časul $3.",
'othercontribs'    => 'Aluz - tö, kudamban tegi $1.',
'others'           => 'toižed',
'siteusers'        => '{{SITENAME}}-saitan {{PLURAL:$2|kävutai|kävutajad}} $1',
'creditspage'      => 'Kitändad',

# Spam protection
'spamprotectiontitle' => "Fil'tr spamaspäi",
'spamprotectiontext'  => "Lehtpolen, kudamban tö tahtoit kirjutada muštho, om blokiruidud spamanfil'tral.
Voib olda, necil lehtpolel om kosketuz irdsaitale, kudamb om mustas nimikirjuteses.",
'spamprotectionmatch' => "Meiden spamanfil'tr tedoti necil statjal mugošt: $1",
'spambot_username'    => 'MediaWikid puhtastadas spamaspäi',

# Info page
'infosubtitle'   => 'Informacii lehtpoles',
'numedits'       => "Redakcijoiden lugu (lehtpol'): $1",
'numtalkedits'   => "Redakcijoiden lugu (lodulehtpol'): $1",
'numwatchers'    => 'Kaclijoiden lugu: $1',
'numauthors'     => "Erazvuiččiden avtoriden lugu (lehtpol'): $1",
'numtalkauthors' => "Erazvuiččiden avtoriden lugu (lodulehtpol'): $1",

# Skin names
'skinname-standard'    => 'Klassine',
'skinname-nostalgia'   => "Nostal'gii",
'skinname-cologneblue' => "Köl'nan sinine",
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Ičeze',
'skinname-chick'       => 'Cipuine',
'skinname-simple'      => 'Koveritoi',
'skinname-modern'      => "Nügüd'aigaine",

# Math options
'mw_math_png'    => 'Kaiken generiruida PNG',
'mw_math_simple' => 'Ozutada HTML koveritomil statjoil, ika ozutada PNG',
'mw_math_html'   => 'Ozutada, ku voib, HTML, ika ozutada PNG',
'mw_math_source' => 'Jätkat nece TeX-formas (tekstkaclimiden täht)',
'mw_math_modern' => "Kut rekomeduidas nügüd'aigaižiden kaclimiden täht",
'mw_math_mathml' => 'MathML, ku sab (eksperimentaližikš)',

# Math errors
'math_failure'          => 'Ei voi palastada',
'math_unknown_error'    => 'tundmatoi petuz',
'math_unknown_function' => 'tundmatoi funkcii',
'math_lexing_error'     => 'leksine petuz',
'math_syntax_error'     => 'sintaksine petuz',
'math_image_error'      => 'PNG-ks kändmižes ozaižihe petuz;
kodvgat, oiged-ik oma järgetud: latex, dvips, gs da convert.',
'math_bad_tmpdir'       => 'Ei voi säta pordaigaline matematine katalog vai ei voi kirjutada sinnä',
'math_bad_output'       => 'Ei voi säta matematine lähtmižkatalog vai ei voi kirjutada sinnä',
'math_notexvc'          => 'En voi löuta texvc-töfailad;
kc. math/README järgendamižen täht.',

# Patrolling
'markaspatrolleddiff'                 => 'Znamoita kut patruliruidud',
'markaspatrolledtext'                 => "Zmamoita nece lehtpol' kut patruliruidud",
'markedaspatrolled'                   => 'Om znamoitud kut patruliruidud',
'markedaspatrolledtext'               => 'Valitud [[:$1]]-n versii om znamoitud kut patruliruidud.',
'rcpatroldisabled'                    => "Jäl'gmäižiden toižetusiden patruliruind ei ole kävutamas",
'rcpatroldisabledtext'                => "Jäl'gmäižiden toižetusiden patruliruindan opcii ei ole kävutamas.",
'markedaspatrollederror'              => 'Ei voi znamoita kut patruliruidud',
'markedaspatrollederror-noautopatrol' => 'Teile ei sa znamoita ičetoi toižetusid kut patruliruidud.',

# Patrol log
'patrol-log-page'      => 'Patruliruindan aigkirj',
'patrol-log-header'    => 'Nece om patruliruidud versijoiden aiglehtez.',
'patrol-log-auto'      => '(avtomatižikš)',
'patrol-log-diff'      => 'versii $1',
'log-show-hide-patrol' => '$1 patruliruindan aigkirj',

# Image deletion
'deletedrevision'       => '$1-lehtpolen vanh versii om čutud',
'filedeleteerror-short' => 'Failan čudandan petuz: $1',

# Browsing diffs
'previousdiff' => '← Vanhemb redakcii',
'nextdiff'     => 'Udemb redakcii →',

# Media information
'thumbsize'            => 'Pičukuvan suruz:',
'widthheightpage'      => "$1×$2, $3 {{PLURAL:$3|lehtpol'|lehtpol't}}",
'file-info'            => '(failan suruz: $1, MIME-tip: $2)',
'file-info-size'       => '($1 × $2 pikselad, failan suruz: $3, MIME-tip: $4)',
'file-nohires'         => '<small>Ei ole versijad paremban tarkoiktusenke.</small>',
'svg-long-desc'        => '(SVG-fail, nominaližikš $1 × $2 pikselid, failan suruz: $3)',
'show-big-image'       => 'Korgedtarkoiktuseline kuvan versii',
'show-big-image-thumb' => '<small>Ezikacundan suruz: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'toštase',
'file-info-gif-frames' => '$1 {{PLURAL:$1|freim|freimad}}',

# Special:NewFiles
'newimages'             => 'Uziden failoiden galerei',
'imagelisttext'         => "Naku om '''$1''' {{PLURAL:$1|fail, kudamb om|failad, kudambad oma}} sortiruidud $2.",
'newimages-summary'     => 'Necil specialižel lehtpolel ozutadas tantoi jügutoittud failoid.',
'newimages-legend'      => "Fil'tr",
'newimages-label'       => 'Failan nimi (vai sen pala):',
'showhidebots'          => '($1 botad)',
'noimages'              => 'Ei ole uzid kuvid.',
'ilsubmit'              => 'Ectä',
'bydate'                => 'datan mödhe',
'sp-newimages-showfrom' => 'Ozutada kaik uded failad aigalpäi $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'č',

# Bad image list
'bad_image_list' => "Formatale pidab olda mugoman:

Vaiše nimikirjutesen elementad (rived, kudambad augotase *-znamaižespäi) oma ottud sil'mnägubale.
Ezmäižele kosketusele rives pidab ozutada hondole faile.
Jäl'ghižed kosketused siš-žo rives oma arvostadud kut erindad (lehtpoled, kudambihe sab mülütada kuvid).",

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',

# Metadata
'metadata'          => 'Metaandmused',
'metadata-help'     => 'Neciš failas om ližainformacijad, kudambad ližaltas tobjimalaz lugukamerad da skanerad.
Ku fail redaktiruidihe sändan polhe, erased parametrad voidas erineda nügüdläižes kuvaspäi.',
'metadata-expand'   => 'Ozutada ližaandmused',
'metadata-collapse' => 'Peitta ližaandmused',
'metadata-fields'   => 'Pandud neche nimikirjuteshe EXIF-metaandmusiden pöudad ozutadas kuvalehtpolel augotižjärgendusen mödhe, a toižid pöudoid peittas.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Leveduz’',
'exif-imagelength'                 => 'Korktuz’',
'exif-bitspersample'               => 'Mujun süvuz',
'exif-compression'                 => 'Ahtištandan metod',
'exif-photometricinterpretation'   => "Mujumodel'",
'exif-orientation'                 => 'Kadran orientacii',
'exif-samplesperpixel'             => 'Mujukomponentoiden lugu',
'exif-planarconfiguration'         => 'Andmusiden järgendamižen princip',
'exif-ycbcrsubsampling'            => 'Y da C-komponentoiden surusiden korreläcii',
'exif-ycbcrpositioning'            => 'Y:n da C:n sijaduz',
'exif-xresolution'                 => 'Gorizontaline tarkoiktuz',
'exif-yresolution'                 => 'Vertikaline tarkoiktuz',
'exif-resolutionunit'              => 'Tarkoiktusen märičendan ühtnik.',
'exif-stripoffsets'                => 'Andmusiden sijaduz',
'exif-rowsperstrip'                => 'Rividen lugu ühtes särmas',
'exif-stripbytecounts'             => 'Ahtištadud särman suruz',
'exif-jpeginterchangeformat'       => '"Ezikacund"-blokan augotišen sijaduz.',
'exif-jpeginterchangeformatlength' => 'Baitoiden lugumär JPEG-as',
'exif-transferfunction'            => 'Mujuavarusen toižetamižen funkcii',
'exif-whitepoint'                  => 'Vauktan čokkoimen mujumär',
'exif-primarychromaticities'       => 'Pämujuiden mujumär',
'exif-ycbcrcoefficients'           => 'Mujumodelin toižetusen koefficientad',
'exif-referenceblackwhite'         => 'Vauktan da mustan čokkoimiden sijaduz',
'exif-datetime'                    => 'Failan toižetusen dat da aig',
'exif-imagedescription'            => 'Kuvan nimi',
'exif-make'                        => 'Kameran tegii',
'exif-model'                       => "Kameran model'",
'exif-software'                    => 'Kävutadud programmišt',
'exif-artist'                      => 'Tegii',
'exif-copyright'                   => 'Avtoranoiktusen pidai',
'exif-exifversion'                 => 'Exif-versii',
'exif-flashpixversion'             => "Pid'oiteldud Flashpix-versii",
'exif-colorspace'                  => 'Mujuavaruz',
'exif-componentsconfiguration'     => 'Mujukomponentoiden konfiguracii',
'exif-compressedbitsperpixel'      => "Mujun süvuz ahtištusen jäl'ghe",
'exif-pixelydimension'             => "Kuvan täuz' korktuz",
'exif-pixelxdimension'             => "Täuz' kuvan korktuz",
'exif-makernote'                   => 'Tegijan ližaandmused',
'exif-usercomment'                 => 'Kävutajan homaičendad',
'exif-relatedsoundfile'            => 'Kulundkommentarijan fail',
'exif-datetimeoriginal'            => 'Todesine dat da aig',
'exif-datetimedigitized'           => 'Digitaliziruindan dat da aig',
'exif-subsectime'                  => 'Failan toižetamižen aigan sekundpalad',
'exif-subsectimeoriginal'          => 'Originaližen aigan sekundpalad',
'exif-subsectimedigitized'         => 'Digitaliziruindan aigan sekundpalad',
'exif-exposuretime'                => 'Ekspozicijan aig',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Diafragman lugu',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Ekspozicijan programm',
'exif-spectralsensitivity'         => 'Spektraline mujandmär',
'exif-isospeedratings'             => 'ISO vauktusenmujandmär',
'exif-oecf'                        => 'Optoelectronižen konversijan koefficient',
'exif-shutterspeedvalue'           => 'Avaidamižaig',
'exif-aperturevalue'               => 'Diafragm',
'exif-brightnessvalue'             => 'Loštuz',
'exif-exposurebiasvalue'           => 'Ekspozicijan kompensacii',
'exif-maxaperturevalue'            => 'Diafragman minimaline lugu',
'exif-subjectdistance'             => 'Edahaižuz objekthasai',
'exif-meteringmode'                => 'Ekspozicijan märičemižen režim',
'exif-lightsource'                 => 'Vauktusenlähte',
'exif-flash'                       => 'Töngahtusen status',
'exif-focallength'                 => 'Linzan fokusedhuz',
'exif-subjectarea'                 => 'Objektan sijaduz',
'exif-flashenergy'                 => 'Töngahtusen energii',
'exif-spatialfrequencyresponse'    => 'Avaruzline paksuzline harakteristik',
'exif-focalplanexresolution'       => 'Fokalplanan X-tarkoiktuz',
'exif-focalplaneyresolution'       => "Fokal'planan Y-tarkoiktuz",
'exif-focalplaneresolutionunit'    => "Fokal'planan tarkoiktusen ühtnik",
'exif-subjectlocation'             => 'Subjektan sijaduz',
'exif-exposureindex'               => 'Ekspozicijan index',
'exif-sensingmethod'               => 'Sensoran tip',
'exif-filesource'                  => 'Failan purde',
'exif-scenetype'                   => 'Scenan tip',
'exif-cfapattern'                  => "CFA - mujufil'tran tip",
'exif-customrendered'              => 'Ližaradamine',
'exif-exposuremode'                => 'Ekspozicijan režiman valičend',
'exif-whitebalance'                => 'Vauktan balans',
'exif-digitalzoomratio'            => 'Digitaline zum',
'exif-focallengthin35mmfilm'       => "Ekvivalentine fokuskeskust (35-mm fil'man täht)",
'exif-scenecapturetype'            => 'Scenan tip kuvadamižen aigan',
'exif-gaincontrol'                 => 'Loštusen tobnenduz',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Mujuküllästuz',
'exif-sharpness'                   => 'Teravuz',
'exif-devicesettingdescription'    => 'Kameran augotižjärgendused',
'exif-subjectdistancerange'        => 'Edhuz kuvadusen objekthasai',
'exif-imageuniqueid'               => 'Kuvan nomer (ID)',
'exif-gpsversionid'                => 'GPS-virgan versii',
'exif-gpslatituderef'              => 'Pohjoine leveduz vai suvileveduz',
'exif-gpslatitude'                 => 'Leveduz',
'exif-gpslongituderef'             => 'Päivnouzmpiduz vai päivlaskmpiduz',
'exif-gpslongitude'                => 'Piduz',
'exif-gpsaltituderef'              => 'Korktusen indeks',
'exif-gpsaltitude'                 => 'Korktuz',
'exif-gpstimestamp'                => 'GPS-aig (atomižed časud)',
'exif-gpssatellites'               => 'Kävutadud kaimnikoiden ümbrikirjutand',
'exif-gpsstatus'                   => 'Saimen status',
'exif-gpsmeasuremode'              => 'Märičemižen metod',
'exif-gpsdop'                      => 'Märičemižen tarkoiktuz',
'exif-gpsspeedref'                 => 'Piguden ühtnik',
'exif-gpsspeed'                    => 'GPS-saimen piguz',
'exif-gpstrackref'                 => 'GPS-sandapparatan azimutan tip (todesine, magnitine)',
'exif-gpstrack'                    => 'GPS-sandapparatan azimut',
'exif-gpsimgdirection'             => 'Kuvan azimut',
'exif-gpsmapdatum'                 => 'Geodezine koordinatoiden sistem om kävutadud',
'exif-gpsdestbearing'              => 'Objektan peleng',
'exif-gpsdestdistance'             => 'Keskust metčokkoimhesai',
'exif-gpsprocessingmethod'         => 'GPS-lugemižen metod',
'exif-gpsareainformation'          => 'GPS-agjan nimi',
'exif-gpsdatestamp'                => 'GPS-dat',
'exif-gpsdifferential'             => 'GPS-differencialine kohenduz',

# EXIF attributes
'exif-compression-1' => 'Ahtištamatoi',

'exif-unknowndate' => 'Tundmatoi dat',

'exif-orientation-1' => 'Normaline',
'exif-orientation-2' => 'Kuvastadud gorizonaližikš',
'exif-orientation-3' => 'Kätud 180°',
'exif-orientation-4' => 'Kuvastadud vertikalidme',

'exif-planarconfiguration-1' => 'chunky-format',
'exif-planarconfiguration-2' => 'planarformat',

'exif-componentsconfiguration-0' => 'ei ole',

'exif-exposureprogram-0' => 'Märičemata',
'exif-exposureprogram-1' => 'Käzirežim',
'exif-exposureprogram-2' => 'Normaline programm',
'exif-exposureprogram-4' => 'Uudimen prioritet',

'exif-subjectdistance-value' => '$1 metrad',

'exif-meteringmode-0'   => 'Tundmatoi',
'exif-meteringmode-1'   => 'Keskmäine',
'exif-meteringmode-2'   => 'Keskmäine veduz',
'exif-meteringmode-3'   => 'Čokkoimine',
'exif-meteringmode-4'   => 'Äičokkoimine',
'exif-meteringmode-5'   => 'Matricaline',
'exif-meteringmode-6'   => 'Palahine',
'exif-meteringmode-255' => 'Toine',

'exif-lightsource-0'   => 'Tundmatoi',
'exif-lightsource-1'   => 'Päi',
'exif-lightsource-2'   => 'Päilamp',
'exif-lightsource-3'   => 'Kalindlamp',
'exif-lightsource-4'   => 'Töngahtuz',
'exif-lightsource-9'   => 'Poud',
'exif-lightsource-10'  => "Pil'vesine sä",
'exif-lightsource-11'  => 'Kuvahaine',
'exif-lightsource-12'  => 'Päilamp (tip D 5700 – 7100K)',
'exif-lightsource-13'  => 'Päilamp (tip N 4600 – 5400K)',
'exif-lightsource-14'  => 'Päilamp (tip W 3900 – 4500K)',
'exif-lightsource-15'  => 'Päilamp (tip WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standartine A-tipan vauktusenlähte',
'exif-lightsource-18'  => 'Standartine B-tipan vauktusenlähte',
'exif-lightsource-19'  => 'Standartine C-tipan vauktusenlähte',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ISO-standartan studiilamp',
'exif-lightsource-255' => 'Toine vauktusenlähte',

# Flash modes
'exif-flash-fired-0'    => 'Töngahtust ei olend.',
'exif-flash-fired-1'    => 'Töngahtuz',
'exif-flash-return-0'   => 'ei ole ezitöngahtusen režimad',
'exif-flash-return-2'   => "ei ole ezitöngahtusen tagazimpul'sad",
'exif-flash-return-3'   => "ezitöngahtusen tagazimpul's om sadud",
'exif-flash-mode-1'     => "täutajan töngahtusen impul's",
'exif-flash-mode-2'     => 'täutajan töngahtusen ahtištuz',
'exif-flash-mode-3'     => 'avtorežim',
'exif-flash-function-1' => 'Ei ole töngahtust',
'exif-flash-redeye-1'   => '"rusttan sil\'män"-kohendamižen režim',

'exif-focalplaneresolutionunit-2' => 'düimad',

'exif-sensingmethod-1' => 'Märhapanmatoi',
'exif-sensingmethod-2' => 'Monokristaline mujun sensor',
'exif-sensingmethod-3' => "Kaks'čipaine mujun sensor",
'exif-sensingmethod-4' => 'Koumečipaine mujun sensor',
'exif-sensingmethod-7' => 'Trilinearine sensor',

'exif-customrendered-0' => 'Normaline process',
'exif-customrendered-1' => 'Ičenaine process',

'exif-exposuremode-0' => 'Avtoekspozicii',
'exif-exposuremode-1' => 'Säta ekspozicijad ičeksaz',
'exif-exposuremode-2' => 'Breketing',

'exif-whitebalance-0' => 'Vauktan avtomatine balans',
'exif-whitebalance-1' => 'Säta vauktan balans ičeksaz',

'exif-scenecapturetype-0' => 'Standartine',
'exif-scenecapturetype-1' => 'Landšaft',
'exif-scenecapturetype-2' => 'Modkuva',
'exif-scenecapturetype-3' => 'Öfotokuva',

'exif-gaincontrol-0' => 'Ei ole',
'exif-gaincontrol-1' => 'Madal tobjenduz',
'exif-gaincontrol-2' => 'Korged tobjenduz',
'exif-gaincontrol-3' => 'Madal penenduz',
'exif-gaincontrol-4' => 'Korged penenduz',

'exif-contrast-0' => 'Normaline',
'exif-contrast-1' => 'Pehmed',
'exif-contrast-2' => 'Kova',

'exif-saturation-0' => 'Normaline',
'exif-saturation-1' => 'Alaküllästuz',
'exif-saturation-2' => 'Üläküllästuz',

'exif-sharpness-0' => 'Normaline',
'exif-sharpness-1' => 'Pehmed',
'exif-sharpness-2' => 'Kova',

'exif-subjectdistancerange-0' => 'Tundmatoi',
'exif-subjectdistancerange-1' => 'Makrokuvaduz',
'exif-subjectdistancerange-2' => 'Lähekuvaduz',
'exif-subjectdistancerange-3' => 'Kuvaduz edahanpäi',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'pohjošt levedust',
'exif-gpslatitude-s' => 'Suvilevedust',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'päivnouzmpidust',
'exif-gpslongitude-w' => 'päivlaskmpidust',

'exif-gpsstatus-a' => 'Märičemine ei ole loptud völ',
'exif-gpsstatus-v' => 'Om vaumiž andmusiden oigendamižeks',

'exif-gpsmeasuremode-2' => '2-koordinatine märičemine',
'exif-gpsmeasuremode-3' => '3-koordinatine märičemine',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/č',
'exif-gpsspeed-m' => 'milid časus',
'exif-gpsspeed-n' => "sol'med",

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Todesine oigendamine',
'exif-gpsdirection-m' => 'Magnitine oigendamine',

# External editor support
'edit-externally'      => 'Redaktiruida nece fail irdprogrammal',
'edit-externally-help' => '(Kc. [http://www.mediawiki.org/wiki/Manual:External_editors seižutamižinstrukcijoid])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'kaik',
'imagelistall'     => 'kaik',
'watchlistall2'    => 'kaik',
'namespacesall'    => 'kaik',
'monthsall'        => 'kaik',
'limitall'         => 'kaik',

# E-mail address confirmation
'confirmemail'             => 'Vahvištoitta e-počtan adres',
'confirmemail_send'        => 'Oigekat vahvištoitandkod',
'confirmemail_sent'        => 'E-počtan adresan vahvištoitandkod om oigetud.',
'confirmemail_invalid'     => 'Vär vahvištoitandkod.
Kod voiži vanhtuda.',
'confirmemail_needlogin'   => 'Pidab $1, miše vahvištoitta teiden e-počtan adres.',
'confirmemail_success'     => "Teiden e-počtan adres om vahvištoittud.
Nügüd' tö voit [[Special:UserLogin|kirjutadas sistemha]] da rata wikiš.",
'confirmemail_loggedin'    => 'Teiden e-počtan adres om vahvištoittud.',
'confirmemail_error'       => 'Teiden e-počtan adresan vahvištoitandan aigan ozaižihe petuz.',
'confirmemail_subject'     => '{{SITENAME}}-sait paikičeb teid vahvištoitta teiden e-počtan adres',
'confirmemail_body'        => 'Ken-se, voib olda, tö, om sänu "$2"-registracijan {{SITENAME}}-saital. Se ristit om kävutanu IP-adresan "$1" da necen e-počtan adresan.

Miše vahvištoitta, miše nece om todeks teiden registracii, i vahvištoitta teiden e-počtan adres, mängat necidä kosketustme:

$3

Ku tö et olgoi sänuded registracijad {{SITENAME}}-saital, mängat necidä kosketustme, miše  saubata e-počtan adresan vahvištoitand.

$5

Necen vahvištoitandkodan kävutamižen lopstrok om $4.',
'confirmemail_invalidated' => 'E-počtan adresan vahvištoitand om keskustadud',
'invalidateemail'          => 'Keskustada e-počtan adresan vahvištoitand',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki:n täutmine ei ole kävutamas]',
'scarytranscludefailed'   => '[Sidomine $1-šablonha om keskustadud petusen tagut]',
'scarytranscludetoolong'  => "[URL om pit'kähk]",

# Trackbacks
'trackbackbox'      => 'Trackback:ad necen lehtpolen täht:<br />
$1',
'trackback'         => '; $4 $5: [$2 $1]',
'trackbackexcerpt'  => '; $4 $5: [$2 $1]: <nowiki>$3</nowiki>',
'trackbackremove'   => '([$1 Čuta poiš])',
'trackbacklink'     => 'Trekbek',
'trackbackdeleteok' => 'Trekbek om čutud poiš.',

# Delete conflict
'deletedwhileediting' => "'''Homaikat''': Nece lehtpol' čutihe poiš jälges sidä, konz tö olit toižetaškanuded necidä lehtpol't!",
'recreate'            => 'Säta udes',

'unit-pixel' => 'piks.',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Puhtastada necen lehtpolen keš?',

# Multipage image navigation
'imgmultipageprev' => "← edeline lehtpol'",
'imgmultipagenext' => "jäl'ghine lehtpol' →",
'imgmultigo'       => 'Mända!',
'imgmultigoto'     => 'Mända lehtpolele $1',

# Table pager
'ascending_abbrev'         => 'lib.',
'descending_abbrev'        => 'lask.',
'table_pager_next'         => "Jäl'ghine lehtpol'",
'table_pager_prev'         => "Edeline lehtpol'",
'table_pager_first'        => 'Ezmäine lehtpol’',
'table_pager_last'         => "Jäl'gmäine lehtpol'",
'table_pager_limit'        => 'Ozutada $1 elementad ühtel lehtpolel',
'table_pager_limit_submit' => 'Tehta',
'table_pager_empty'        => 'Ei voi löuta nimidä',

# Auto-summaries
'autosumm-blank'   => 'Lehtpolen südäimišt om čutud',
'autosumm-replace' => "Lehtpolen südäimišt om vajehtud '$1'-südäimištoks",
'autoredircomment' => 'Om läbioigetud [[$1]]-lehtpolele',
'autosumm-new'     => "Om sätud uz' lehtpol', kudamban suruz om '$1'",

# Size units
'size-bytes'     => '$1 b',
'size-kilobytes' => '$1 kb',
'size-megabytes' => '$1 mb',
'size-gigabytes' => '$1 gb',

# Live preview
'livepreview-loading' => 'Ozutase…',
'livepreview-ready'   => 'Jügutoitmine… Vaumiž!',
'livepreview-failed'  => 'Ei voi kävutada heredad ezikacundad! Kävutagat normaline ezikacund.',
'livepreview-error'   => 'Ei voi säta sidod saitha: $1 "$2".
Kävutagat normaline ezikacund.',

# Watchlist editor
'watchlistedit-numitems'      => 'Teiden kaclendnimikirjuteses om {{PLURAL:$1|1 kirjutuz|$1 kirjutust}} lodulehtpolita.',
'watchlistedit-noitems'       => 'Teiden kaclendnimikirjuteses ei ole kirjutusid.',
'watchlistedit-normal-title'  => 'Redaktiruida kaclendnimikirjutez',
'watchlistedit-normal-legend' => 'Heitta kirjutesid kaclendnimikirjutesespäi',
'watchlistedit-normal-submit' => 'Čuta poiš kirjutesed',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 kirjutez|$1 kijutest}} om čutud teiden kaclendnimikirjutesespäi:',
'watchlistedit-raw-title'     => 'Redaktiruida "toreh" keclendnimikirjutez',
'watchlistedit-raw-legend'    => 'Redaktiruida "toreh" keclendnimikirjutez',
'watchlistedit-raw-titles'    => 'Kirjutesed:',
'watchlistedit-raw-submit'    => 'Udištada kaclendnimikirjutez',
'watchlistedit-raw-done'      => 'Teiden kaclendnimikirjutez om udištadud.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 kirjutez|$1 kirjutest}} om ližatud:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 kirjutez|$1 kirjutest}} om heittud nimikirjutesespäi:',

# Watchlist editing tools
'watchlisttools-view' => 'Ozutada toižetused lehtpolil nimikirjutesespäi',
'watchlisttools-edit' => 'Lugeda da redaktiruida nimikirjutez',
'watchlisttools-raw'  => 'Redaktiruida kut tekst',

# Iranian month names
'iranian-calendar-m1'  => 'Farvardin',
'iranian-calendar-m2'  => 'Ordibehešt',
'iranian-calendar-m3'  => 'Hordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Šahrivar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dei',
'iranian-calendar-m11' => 'Bahman',
'iranian-calendar-m12' => 'Esfand',

# Hijri month names
'hijri-calendar-m1' => 'Muharram',
'hijri-calendar-m2' => 'Safar',
'hijri-calendar-m3' => 'Rabi al-aual',

# Core parser functions
'unknown_extension_tag' => 'Tundmatoi "$1"-ližanvirg',
'duplicate-defaultsort' => '\'\'\'Varutuz:\'\'\' Sortiruindan avadim äugotižjärgendusen mödhe "$2" toižetab edeližen avadimen äugotižjärgendusen mödhe "$1".',

# Special:Version
'version'                          => 'Versii',
'version-extensions'               => 'Seižutadud ližad',
'version-specialpages'             => 'Specialižed lehtpoled',
'version-parserhooks'              => 'Sintaksižen analizatoran sabustajad',
'version-variables'                => 'Vajehtujad lugud',
'version-other'                    => 'Toine',
'version-mediahandlers'            => 'Median radimed',
'version-hooks'                    => 'Sabutajad',
'version-extension-functions'      => 'Ližoiden funkcijad',
'version-parser-extensiontags'     => 'Sintaksižen analizatoran ližoiden virgad',
'version-parser-function-hooks'    => 'Sintaksižen analizatoran funkcijoiden sabutajad',
'version-skin-extension-functions' => 'Irdnäguližoiden funkcijad',
'version-hook-name'                => 'Sabustajan nimi',
'version-hook-subscribedby'        => 'Ezipakitoitajad',
'version-version'                  => '(Versii $1)',
'version-license'                  => 'Licenzii',
'version-software'                 => 'Seižutadud programmišt',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Versii',

# Special:FilePath
'filepath'        => 'Te failannoks',
'filepath-page'   => 'Fail:',
'filepath-submit' => 'Te',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Ectä kaksitadud failad',
'fileduplicatesearch-summary'  => 'Ühtejiččiden failoiden ecmine niiden heš-kodan mödhe.

Kirjutagat failan nimi «{{ns:file}}:»-pkefiksata.',
'fileduplicatesearch-legend'   => 'Ectä dublikatoid',
'fileduplicatesearch-filename' => 'Failannimi:',
'fileduplicatesearch-submit'   => 'Ectä',
'fileduplicatesearch-info'     => '$1 × $2 pikselad<br />Failan suruz: $3<br />MIME-tip: $4',
'fileduplicatesearch-result-1' => '"$1"-failal ei ole identižid dublikatoid.',
'fileduplicatesearch-result-n' => '"$1"-failal om {{PLURAL:$2|1 identine kopii|$2 identišt kopijad}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialižed lehtpoled',
'specialpages-note'              => '----
* Järgeližed specialižed lehtpoled.
* <strong class="mw-specialpagerestricted">Kaitud specialižed lehtpoled.</strong>',
'specialpages-group-maintenance' => 'Tehnižen holitandan satusenladindad',
'specialpages-group-other'       => 'Toižed specialižed lehtpoled',
'specialpages-group-login'       => 'Kirjutadas sistemha / Sada registracii',
'specialpages-group-changes'     => 'Tantoižed toižetused da aigkirjad',
'specialpages-group-media'       => 'Media: satusenladindad da jügutoitand',
'specialpages-group-users'       => 'Kävutajad da oiktused',
'specialpages-group-highuse'     => 'Intensivižešti kävutadud lehtpoled',
'specialpages-group-pages'       => 'Lehtpoliden nimikirjutesed',
'specialpages-group-pagetools'   => 'Azegišt lehtpoliden täht',
'specialpages-group-wiki'        => 'Wiki-andmused da azegišt',
'specialpages-group-redirects'   => 'Oigendajad specialižed lehtpoled',
'specialpages-group-spam'        => 'Azeged spaman vaste',

# Special:BlankPage
'blankpage'              => "Puhtaz lehtpol'",
'intentionallyblankpage' => "Nece lehtpol' om jättud puhthaks tahtonke.",

# External image whitelist
'external_image_whitelist' => '  #Jätkat nece rivi kändmatoman<pre>
#Sijakat tänna regulärižiden kuvandoiden fragmentad (vaiše //-znamoiden keskpala)
#Ned korreliruitadas irdkuviden URLoidenke
#Sättujad ozutadas kuvil, toižed - kut kosketused kuvile
#Rived, kudambiden augoitšes oma #-znamad, ozutadas kut kommentarijad
#Rived mujadas registrad

#Sijakat regulärižiden kuvandoiden fragmentad necen riven päle. Jätkat nece rivi kändmatoman</pre>',

# Special:Tags
'tags'                    => 'Aktualižed toižetusiden vestatesed',
'tag-filter'              => "[[Special:Tags|Vestatesiden]] fil'tr:",
'tag-filter-submit'       => "Fil'tr",
'tags-title'              => 'Virgad',
'tags-intro'              => 'Necil lehtpolel om virgoiden nimikirj i neniden virgoiden znamoičendad. Programmad znamoitas virgoil toižetusid.',
'tags-tag'                => 'Tegan (virgan) nimi',
'tags-display-header'     => 'Nägu toižetisiden aigkirjoiš',
'tags-description-header' => "Znamoičendan täuz' ümbrikirjutand",
'tags-hitcount-header'    => 'Virgastadud redakcijad',
'tags-edit'               => 'redaktiruida',
'tags-hitcount'           => '$1 {{PLURAL:$1|toižetuz|toižetust}}',

# Database error messages
'dberr-header'      => 'Necil wikil om problemoid',
'dberr-problems'    => 'Pakičem armahtust! Necil saital om tehnižid problemoid.',
'dberr-again'       => "Varastagat pordon aigad da udištagat lehtpol'.",
'dberr-info'        => '(Ei voi säta sidod admusiden baziden serveranke: $1)',
'dberr-usegoogle'   => "Täl aigal tö voit ectä Google'an abul.",
'dberr-outofdate'   => "Google'an indeks voib olda vanhtunuden.",
'dberr-cachederror' => 'Naku om ectud lehtpolen keširuidud versii. Voib olda, siš ei ole tantoižid toižetusid.',

# HTML forms
'htmlform-invalid-input'       => 'Erasil teil anttud andmusil om problem',
'htmlform-select-badoption'    => 'Teil anttud znamoičend ei ole lasktud.',
'htmlform-int-invalid'         => 'Teil anttud znamoičend ei ole kogonaine lugu.',
'htmlform-float-invalid'       => 'Teil anttud znamoičend ei ole lugu.',
'htmlform-int-toolow'          => 'Teil znamoitud znamoičend om madalamb minimališt - $1',
'htmlform-int-toohigh'         => 'Teil znamoitud znamoičend om korktemb maksikališt - $1',
'htmlform-submit'              => 'Oigeta',
'htmlform-reset'               => 'Tühjitada toižetused',
'htmlform-selectorother-other' => 'Toine',

);
