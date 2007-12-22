<?php
/** Kurdish (Latin) (Kurdî / كوردی (Latin))
 *
 * @addtogroup Language
 *
 * @author Bangin
 * @author Nike
 * @author SieBot
 * @author Siebrand
 */

$separatorTransformTable = array(
	',' => '.',
	'.' => ','
);

$extraUserToggles = array(
	'nolangconversion'
);

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Taybet',
	NS_MAIN             => '',
	NS_TALK             => 'Nîqaş',
	NS_USER             => 'Bikarhêner',
	NS_USER_TALK        => 'Bikarhêner_nîqaş',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_nîqaş',
	NS_IMAGE            => 'Wêne',
	NS_IMAGE_TALK       => 'Wêne_nîqaş',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
	NS_HELP             => 'Alîkarî',
	NS_HELP_TALK        => 'Alîkarî_nîqaş',
	NS_CATEGORY         => 'Kategorî',
	NS_CATEGORY_TALK    => 'Kategorî_nîqaş'
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Xetekê di bin lînka çêke:',
'tog-highlightbroken'         => 'Lînkan berve gotarên vala biguherîne',
'tog-justify'                 => 'Gotar bi forma "block"',
'tog-hideminor'               => 'Guherandinên biçûk ji listêya guherandinên dawî veşêre',
'tog-extendwatchlist'         => 'Lîsteya şopandinê veke ji bo dîtinê her xeyrandinên meqbûl',
'tog-usenewrc'                => 'Wêşandinê zêdetir (JavaScript gireke)',
'tog-numberheadings'          => 'Sernavan otomatîk bihejmêre',
'tog-showtoolbar'             => 'Tiştên guherandinê bibîne (JavaScript bibîne)',
'tog-editondblclick'          => 'Rûpelan bi du klîkan biguherîne (Java Script gireke)',
'tog-editsection'             => 'Lînkan ji bo guherandinê beşan biwêşîne',
'tog-editsectiononrightclick' => 'Beşekê bi rast-klîkekê biguherîne (JavaScript gireke)',
'tog-showtoc'                 => 'Tabloya naverokê nîşanbide (ji rûpelan bi zêdetirî sê sernavan)',
'tog-rememberpassword'        => 'Qeydkirinê min di vê komputerê da wîne bîrê',
'tog-editwidth'               => 'Cihê guherandinê yê tewrî mezin',
'tog-watchcreations'          => 'Rûpelan, yê min çêkir, têke lîsteya min ya şopandinê',
'tog-watchdefault'            => 'Rûpelan, yê min guhart, têke lîsteya min ya şopandinê',
'tog-watchmoves'              => 'Rûpelan, yê min navî wan guhart, têke lîsteya min ya şopandinê',
'tog-watchdeletion'           => 'Rûpelan, yê min jêbir, têke lîsteya min ya şopandinê',
'tog-minordefault'            => 'Her guherandinekê min bike wek guherandinekî biçûk be',
'tog-previewontop'            => 'Pêşdîtinê gotarê li jorî cihê guherandinê nîşan bide',
'tog-previewonfirst'          => 'Li cem guherandinê yekemîn hercaran pêşdîtinê nîşan bide',
'tog-nocache'                 => "Cache'ê rûpelan biskînîne",
'tog-enotifwatchlistpages'    => 'E-nameyekê ji min ra bişîne eger rûpelek yê ez dişopînim hate guhartin',
'tog-enotifusertalkpages'     => 'E-nameyekê ji min ra bişîne eger guftûgoyê min hate guhartin',
'tog-enotifminoredits'        => 'E-nameyekê ji min ra bişîne eger bes guherandinekî biçûk be jî',
'tog-enotifrevealaddr'        => 'Adrêsa e-nameya min di e-nameyan înformasyonan bêlibike',
'tog-shownumberswatching'     => 'Nîşan bide, çiqas bikarhêner dişopînin',
'tog-fancysig'                => 'Îmze vê lînkkirinê otomatik berve rûpelê bikarhêner',
'tog-externaleditor'          => 'Edîtorekî derva bike "standard"',
'tog-externaldiff'            => 'Birnemijekî derva yê diff bike "standard"',
'tog-showjumplinks'           => 'Lînkên "Here-berve" qebûlbike',
'tog-uselivepreview'          => 'Pêşdîtinê "live" bikarwîne (JavaScript gireke) (ceribandin)',
'tog-forceeditsummary'        => 'Bibêje, eger kurteyekê vala kê were tomarkirin',
'tog-watchlisthideown'        => 'Guherandinên min ji lîsteya şopandinê veşêre',
'tog-watchlisthidebots'       => "Guherandinên bot'an ji lîsteya şopandinê veşêre",
'tog-watchlisthideminor'      => 'Xeyrandinên biçûk pêşneke',
'tog-nolangconversion'        => 'Konvertkirinê varîyantên zimên biskînîne',
'tog-ccmeonemails'            => 'Kopîyan ji e-nameyan ji min ra bişîne yê min şande bikarhênerên din',
'tog-diffonly'                => 'Li cem nîşandinê versyonan bes ferqê nîşanbide, ne rûpel tevda',

'underline-always'  => 'Tim',
'underline-never'   => 'Ne carekê',
'underline-default' => "Tercihên browser'ê da",

'skinpreview' => '(Pêşdîtin)',

# Dates
'sunday'        => 'yekşem',
'monday'        => 'duşem',
'tuesday'       => 'Sêşem',
'wednesday'     => 'Çarşem',
'thursday'      => 'Pêncşem',
'friday'        => 'În',
'saturday'      => 'şemî',
'sun'           => 'Ykş',
'mon'           => 'Duş',
'tue'           => 'Sêş',
'wed'           => 'Çarş',
'thu'           => 'Sêş',
'fri'           => 'Înê',
'sat'           => 'Şem',
'january'       => 'Rêbendan',
'february'      => 'reşemî',
'march'         => 'adar',
'april'         => 'avrêl',
'may_long'      => 'gulan',
'june'          => 'pûşper',
'july'          => 'Tîrmeh',
'august'        => 'tebax',
'september'     => 'rezber',
'october'       => 'kewçêr',
'november'      => 'sermawez',
'december'      => 'Berfanbar',
'january-gen'   => 'Rêbendan',
'february-gen'  => 'Reşemî',
'march-gen'     => 'Adar',
'april-gen'     => 'Avrêl',
'may-gen'       => 'Gulan',
'june-gen'      => 'Pûşper',
'july-gen'      => 'Tîrmeh',
'august-gen'    => 'Gelawêj',
'september-gen' => 'Rezber',
'october-gen'   => 'Kewçêr',
'november-gen'  => 'Sermawez',
'december-gen'  => 'Berfanbar',
'jan'           => 'rêb',
'feb'           => 'reş',
'mar'           => 'adr',
'apr'           => 'avr',
'may'           => 'gul',
'jun'           => 'pşr',
'jul'           => 'tîr',
'aug'           => 'teb',
'sep'           => 'rez',
'oct'           => 'kew',
'nov'           => 'ser',
'dec'           => 'ber',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategorî|Kategorî}}',
'pagecategories'        => '$1 Kategorîyan',
'category_header'       => 'Gotarên di kategoriya "$1" de',
'subcategories'         => 'Binekategorî',
'category-media-header' => 'Medya di kategorîya "$1" da',
'category-empty'        => "''Di vê kategorîyê da niha gotar ya medya tune ne.''",

'mainpagetext'      => "<big>'''MediaWiki serketî hate çêkirin.'''</big>",
'mainpagedocfooter' => 'Alîkarî ji bo bikaranîn û guherandin yê datayê Wîkî tu di bin [http://meta.wikimedia.org/wiki/Help:Contents pirtûka alîkarîyê ji bikarhêneran] da dikarê bibînê.

== Alîkarî ji bo destpêkê ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lîsteya varîyablên konfîgûrasîyonê]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lîsteya e-nameyên versyonên nuh yê MediaWiki]',

'about'          => 'Der barê',
'article'        => 'Gotar',
'newwindow'      => '(di rûpelekî din da yê were nîşandan)',
'cancel'         => 'Betal',
'qbfind'         => 'Bibîne',
'qbbrowse'       => 'Bigere',
'qbedit'         => 'Biguherîne',
'qbpageoptions'  => 'Ev rûpel',
'qbpageinfo'     => "Data'yên rûpelê",
'qbmyoptions'    => 'Rûpelên min',
'qbspecialpages' => 'Rûpelên taybet',
'moredotdotdot'  => 'Zêde...',
'mypage'         => 'Rûpela min',
'mytalk'         => 'Rûpela guftûgo ya min',
'anontalk'       => 'Guftûgo ji bo vê IPê',
'navigation'     => 'Navîgasyon',

# Metadata in edit box
'metadata_help' => "Data'yên meta:",

'errorpagetitle'    => 'Çewtî (Error)',
'returnto'          => 'Bizivire $1.',
'tagline'           => 'Ji {{SITENAME}}',
'help'              => 'Alîkarî',
'search'            => 'Lêbigere',
'searchbutton'      => 'Lêbigere',
'go'                => 'Gotar',
'searcharticle'     => 'Gotar',
'history'           => 'Dîroka rûpelê',
'history_short'     => 'Dîrok / Nivîskar',
'updatedmarker'     => 'hate guherandin ji serlêdana dawî yê min da',
'info_short'        => 'Zanyarî',
'printableversion'  => 'Versiyon ji bo çapkirinê',
'permalink'         => 'Lînkê tim',
'print'             => 'Çap',
'edit'              => 'Biguherîne',
'editthispage'      => 'Vê rûpelê biguherîne',
'delete'            => 'Jê bibe',
'deletethispage'    => 'Vê rûpelê jê bibe',
'undelete_short'    => 'Dîsa {{PLURAL:$1|guherandinekî|$1 guherandinan}} çêke',
'protect'           => 'Biparêze',
'protect_change'    => 'parastinê biguherîne',
'protectthispage'   => 'Vê rûpelê biparêze',
'unprotect'         => 'Parastinê rake',
'unprotectthispage' => 'Parastina vê rûpelê rake',
'newpage'           => 'Rûpela nû',
'talkpage'          => 'Vê rûpelê guftûgo bike',
'talkpagelinktext'  => 'Nîqaş',
'specialpage'       => 'Rûpela taybet',
'personaltools'     => 'Amûrên şexsî',
'postcomment'       => 'Şîroveyekê bişîne',
'articlepage'       => 'Li naveroka rûpelê binêre',
'talk'              => 'Guftûgo (nîqaş)',
'views'             => 'Dîtin',
'toolbox'           => 'Qutiya amûran',
'userpage'          => 'Rûpelê vê/vî bikarhênerê/î temaşe bike',
'projectpage'       => 'Li rûpelê projektê seke',
'imagepage'         => 'Li rûpelê wêneyê seke',
'mediawikipage'     => 'Li rûpelê mêsajê seke',
'templatepage'      => 'Rûpelê şablonê seke',
'viewhelppage'      => 'Rûpelê alîkarîyê seke',
'categorypage'      => 'Li rûpelê kategorîyê seke',
'viewtalkpage'      => 'Guftûgoyê temaşe bike',
'otherlanguages'    => 'Zimanên din',
'redirectedfrom'    => '(Hat ragihandin ji $1)',
'redirectpagesub'   => 'Rûpelê redirect',
'lastmodifiedat'    => 'Ev rûpel carî dawî di $2, $1 de hat guherandin.', # $1 date, $2 time
'viewcount'         => 'Ev rûpel {{PLURAL:$1|carekê|caran}} tê xwestin.',
'protectedpage'     => 'Rûpela parastî',
'jumpto'            => 'Here cem:',
'jumptonavigation'  => 'navîgasyon',
'jumptosearch'      => 'lêbigere',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Der barê {{SITENAME}}',
'aboutpage'         => 'Project:Der barê',
'bugreports'        => 'Raporên çewtiyan',
'bugreportspage'    => 'Project:Raporên çewtiyan',
'copyright'         => 'Ji bo naverokê $1 derbas dibe.',
'copyrightpagename' => 'Mafên nivîsanê',
'copyrightpage'     => '{{ns:project}}:Mafên nivîsanê',
'currentevents'     => 'Bûyerên rojane',
'currentevents-url' => 'Project:Bûyerên rojane',
'disclaimers'       => 'Ferexetname',
'disclaimerpage'    => 'Project:Ferexetname',
'edithelp'          => 'Alîkarî ji bo guherandin',
'edithelppage'      => 'Help:Rûpeleke çawa biguherînim',
'faq'               => 'Pirs û Bersîv (FAQ)',
'faqpage'           => 'Project:Pirs û Bersîv',
'helppage'          => 'Help:Alîkarî',
'mainpage'          => 'Destpêk',
'policy-url'        => 'Project:Qebûlkirin',
'portal'            => 'Portala komê',
'portal-url'        => 'Project:Portala komê',
'privacy'           => "Parastinê data'yan",
'privacypage'       => "Project:Parastinê data'yan",
'sitesupport'       => 'Ji bo Weqfa Wikimedia Beş',
'sitesupport-url'   => 'Project:Alîkarîya diravî',

'badaccess'        => 'Eror li bi dest Hînan',
'badaccess-group0' => 'Tu nikanî vê tiştî bikê.',
'badaccess-group1' => 'Ev tişta yê tu dixazê bikê bes ji bikarhênerên yê grupê $1 tê qebûlkirin.',
'badaccess-group2' => 'Ev tişta yê tu dixazê bikê bes ji bikarhênerên ra ye, yê bi kêmani di grupê $1 da ne.',
'badaccess-groups' => 'Ev tişta yê tu dixazê bikê bes ji bikarhênerên ra ye, yê bi kêmani di grupê $1 da ne.',

'versionrequired'     => 'Verzîyonê $1 ji MediaWiki pêwîste',
'versionrequiredtext' => 'Verzîyonê $1 ji MediaWiki pêwîste ji bo bikaranîna vê rûpelê. Li [[Special:version|versyon]] seke.',

'ok'                      => 'Temam',
'retrievedfrom'           => 'Ji "$1" hatiye standin.',
'youhavenewmessages'      => '$1 yên te hene ($2).',
'newmessageslink'         => 'Nameyên nû',
'newmessagesdifflink'     => 'Ciyawazî ji revîzyona berê re',
'youhavenewmessagesmulti' => 'Nameyên nih li $1 ji te ra hene.',
'editsection'             => 'biguherîne',
'editold'                 => 'biguherîne',
'editsectionhint'         => 'Beşê biguherîne: $1',
'toc'                     => 'Tabloya Naverokê',
'showtoc'                 => 'nîşan bide',
'hidetoc'                 => 'veşêre',
'thisisdeleted'           => '$1 lêsekê ya dîsa çêkê?',
'viewdeleted'             => 'Li $1 seke?',
'restorelink'             => '{{PLURAL:$1|guherandinekî|$1 guherandinên}} jêbirî',
'feed-invalid'            => "Feed'ekî neserrast.",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Gotar',
'nstab-user'      => 'Bikarhêner',
'nstab-media'     => 'Medya',
'nstab-special'   => 'Taybet',
'nstab-project'   => 'Rûpela projeyê',
'nstab-image'     => 'Wêne',
'nstab-mediawiki' => 'Peyam',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Alîkarî',
'nstab-category'  => 'Kategorî',

# Main script and global functions
'nosuchaction'      => 'Çalakiyek bi vê rengê tune',
'nosuchactiontext'  => "Ew tişta yê di wê URL'ê da tê gotin ji MediaWiki netê çêkirin.",
'nosuchspecialpage' => 'Rûpeleke taybet bi vê rengê tune',
'nospecialpagetext' => "<big>'''Rûpelê taybetî yê te xwastîyê tune ye.'''</big>

Hemû rûpelên taybetî di [[{{ns:special}}:Specialpages|lîsteya rûpelên taybetî]] da werin dîtin.",

# General errors
'error'                => 'Çewtî (Error)',
'databaseerror'        => "Şaşbûnek di database'ê da",
'dberrortext'          => 'Li cem sekirina database <blockquote><tt>$1</tt></blockquote>
ji fonksyonê "<tt>$2</tt>" yê
MySQL ev şaşbûna hate dîtin: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Li cem sekirina database "$1 ji fonksyonê "<tt>$2</tt>" yê
MySQL ev şaşbûna hate dîtin: "<tt>$3: $4</tt>".',
'noconnect'            => 'Bibexşîne! Çend pirsgrêkên teknîkî heye, girêdan ji pêşkêşvanê (suxrekirê, server) re niha ne gengaz e. <br />
$1',
'nodb'                 => 'Database $1 nikanî hatiba sekirin. Xêra xwe derengtir dîsa bicerbîne.',
'cachederror'          => "Evê jêr bes kopîyek ji cache'ê ye û belkî ne yê niha ye.",
'laggedslavemode'      => 'Zanibe: Ev rûpela belkî guherandinên yê ne niha nîşandide.',
'readonly'             => 'Database hatîye girtin',
'enterlockreason'      => 'Hoyek ji bo bestin binav bike, herweha zemaneke mezende kirî ji bo helgirtina bestinê!',
'readonlytext'         => "Database'ê {{SITENAME}} ji bo guherandinan û gotarên nuh hatîye girtin.

Sedemê girtinê ev e: $1",
'missingarticle'       => '<p><span class="error">Şaşbûn:</span> Nivîsê „$1“ di database\'ê da nehate dîtin.</p>
<p>Ew rûpela belkî <span class="plainlinks">[{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} hatîye jêbirin]</span> ya <span class="plainlinks">[{{fullurl:Special:Log/move|page={{FULLPAGENAMEE}}}} navî wê gotarê hatîye guherandin]</span>.</p>
<p>Belkî jî mişklek li cem sekirinê database\'ê heye. Eger wisa be, xêra xwe derengtir dîsa bicerbîne.</p>',
'readonly_lag'         => "Database otomatîk hate girtin, ji bo server'î database'î slave kanibe xwe wekî server'î database'î master'ê bike.",
'internalerror'        => 'Şaşbûnekî înternal',
'internalerror_info'   => 'Şaşbûnê înternal: $1',
'filecopyerror'        => 'Datayê „$1“ nikanî çûba berve „$2“ kopîkirin.',
'filerenameerror'      => 'Navê faylê "$1" nebû "$2".',
'filedeleteerror'      => '"$1" nikanî hatiba jêbirin.',
'directorycreateerror' => '"$1" nikanî hatiba çêkirin.',
'filenotfound'         => 'Dosya bi navê "$1" nehat dîtin.',
'fileexistserror'      => 'Di data\'yê "$1" nikanî hatiba nivîsandin, ji ber ku ew data\'ya berê heye.',
'unexpected'           => 'Tiştek yê nehatibû zanîn: "$1"="$2".',
'formerror'            => 'Şaşbûn: Ew nivîs nikanîn hatibana bikaranîn.',
'badarticleerror'      => 'Ev çalakî di vê rûpelê de nabe.',
'cannotdelete'         => 'Ev rûpela nikanî hatiba jêbirin. Meqûle ku kesekî din vê rûpelê jêbir.',
'badtitle'             => 'Sernivîsa nebaş',
'badtitletext'         => "Sernavê rûpelê xastî qedexe ye, vala ye ya lînkekî zimanekî wîkî'yekî din e.",
'perfdisabled'         => "Bibexşîne! Ev fonksîyona ji bo westîyanê server'ê niha hatîye sikinandin.",
'perfcached'           => "The following data is cached and may not be up to date.

Ev data'yan ji cache'ê ne û belkî ne zindî bin.
----
<div style=\"text-align:center; font-size:90%\">'''Rûpelên taybetî:''' [[Special:Shortpages|Gotarên kin]] · [[Special:Longpages|Gotarên dirêj]] · [[Special:Wantedpages|Rûpelên xastî]] · [[Special:Newpages|Gotarên nuh]] · [[Special:Lonelypages|Rûpelên sêwî]] · [[Special:Deadendpages|Gotarên bê dergeh]] · [[Special:CrossNamespaceLinks|Gotar bi lînkan berve namespace'ên din]] · [[Special:Protectedpages|Rûpelên parastî]]</div>
----",
'perfcachedts'         => "Ev data'ya hatîye cache'kirin û carê paşîn $1 hate zindîkirin.",
'querypage-no-updates' => "Fonksîyonê zindîkirinê yê vê rûpelê hatîye sikinandin. Data'yên vir netên zindîkirin.",
'wrong_wfQuery_params' => "Parameter'ên şaş ji bo wfQuery()<br />
Fonksîyon: $1<br />
Jêpirskirin: $2",
'viewsource'           => 'Çavkanî',
'viewsourcefor'        => 'ji $1 ra',
'actionthrottled'      => 'Hejmarê guherandinan hatîye hesibandin',
'protectedpagetext'    => 'Ev rûpela hatîye parastin ji bo nenivîsandinê.',
'viewsourcetext'       => 'Tu dikarê li çavkanîyê vê rûpelê sekê û wê kopîbikê:',
'protectedinterface'   => "Di vê rûpelê da nivîsandin ji bo interface'î zimanan yê vê software'ê ye. Ew tê parstin ji bo vandalîzm li vê derê çênebe.",
'editinginterface'     => "'''Hîşyar:''' Tu rûpelekî diguherînê yê ji wêşandinê înformasyonan di sistêmê da girîn in. Guherandin di vê rûpelê da ji her bikarhêneran ra yê were dîtin.",
'sqlhidden'            => '(Jêpirskirina SQL hatîye veşartin)',
'cascadeprotected'     => '<strong>Ev rûpela hatîye parastin ji ber guherandinê, ji ber ku ev rûpela di {{PLURAL:$1|vê rûpelê|van rûpelan da}} tê bikaranîn:
$2

</strong>',
'namespaceprotected'   => "Qebûlkirinê te tune, ku tu vê rûpelê di namespace'a $1 da biguherînê.",
'customcssjsprotected' => 'Qebûlkirinên te tune ne, tu nikanê vê rûpelê biguherînê, ji ber ku di vir da tercihên bikarhênerekî din hene.',
'ns-specialprotected'  => "Rûpel di namespace'a {{ns:special}} nikanin werin guherandin.",

# Login and logout pages
'logouttitle'                => 'Derketina bikarhêner',
'logouttext'                 => '<strong>Tu niha derketî (logged out).</strong><br />
Tu dikarî {{SITENAME}} niha weke bikarhênerekî nediyarkirî bikarbînî, yan jî tu dikarî dîsa bi vî navê xwe yan navekî din wek bikarhêner têkevî. Bila di bîra te de be ku gengaz e hin rûpel mîna ku tu hîn bi navê xwe qeyd kiriyî werin nîşandan, heta ku tu nîşanên çavlêgerandina (browser) xwe jênebî.',
'welcomecreation'            => '== Bi xêr hatî, $1! ==

Hesaba te hat afirandin. Tu dikarî niha tercîhên xwe eyar bikî.',
'loginpagetitle'             => 'Qeyda bikarhêner (User login)',
'yourname'                   => 'Navê te wek bikarhêner (user name)',
'yourpassword'               => 'Şîfreya te (password)',
'yourpasswordagain'          => 'Şîfreya xwe careke din binîvîse',
'remembermypassword'         => 'Şifreya min di her rûniştdemê de bîne bîra xwe.',
'yourdomainname'             => 'Domaînê te',
'externaldberror'            => "Ya şaşbûnek di naskirinê derva heye, ya tu nikarî account'î xwe yê derva bikarwînê.",
'loginproblem'               => '<b>Di qeyda te (login) de pirsgirêkek derket.</b><br />Careke din biceribîne!',
'login'                      => 'Têkeve (login)',
'loginprompt'                => "<b>Eger tu xwe nû qeyd bikî, nav û şîfreya xwe hilbijêre.</b> Ji bo xwe qeyd kirinê di {{SITENAME}} de divê ku ''cookies'' gengaz be.",
'userlogin'                  => 'Têkeve an hesabeke nû çêke',
'logout'                     => 'Derkeve (log out)',
'userlogout'                 => 'Derkeve',
'notloggedin'                => 'Xwe qeyd nekir (not logged in)',
'nologin'                    => 'Tu hêj ne endamî? $1.',
'nologinlink'                => 'Bibe endam',
'createaccount'              => 'Hesabê nû çêke',
'gotaccount'                 => 'Hesabê te heye? $1.',
'gotaccountlink'             => 'Têkeve (login)',
'createaccountmail'          => 'bi e-name',
'badretype'                  => 'Herdu şîfreyên ku te nivîsîn hevûdin nagirin.',
'userexists'                 => 'Ev navî bikarhênerî berê tê bikaranîn. Xêra xwe navekî din bibe.',
'youremail'                  => 'E-maila te*',
'username'                   => 'Navê bikarhêner:',
'uid'                        => "ID'ya bikarhêner:",
'yourrealname'               => 'Navê te yê rastî*',
'yourlanguage'               => 'Ziman',
'yourvariant'                => 'Varîyant:',
'yournick'                   => 'Leqeba te (ji bo îmza)',
'badsig'                     => 'Nivîsandinê îmzê ne baş e; xêra xwe nivîsandina HTML seke, ku şaşbûn hene ya na.',
'badsiglength'               => 'Navî te zêde dirêj e; ew gireke di bin $1 nîşanan da be.',
'email'                      => 'E-name',
'prefs-help-realname'        => 'Ne gereke. Tu dikarî navî xwe binivisînê, ew ê bi karkirên te were nivîsandin.',
'loginerror'                 => 'Çewtî (Login error)',
'prefs-help-email'           => 'Adrêsa te yê e-nameyan ne gereke were nivîsandin, lê ew qebûldike, ku bikarhênerên din vê naskirinê te kanibin e-nameyan ji te ra bişînin.',
'prefs-help-email-required'  => 'Adrêsa e-nameyan gereke.',
'nocookiesnew'               => "Account'î bikarhêner hatibû çêkirin, lê te xwe qeyd nekirîye. {{SITENAME}} cookie'yan bikartîne ji bo qeydkirinê bikarhêneran. Te cookie'yan girtîye. Xêra xwe cookie'yan qebûlbike, manê tu kanibê bi navî bikarhêner û şîfreya xwe qeydbikê.",
'nocookieslogin'             => 'Ji bo qeydkirina bikarhêneran {{SITENAME}} "cookies" bikartîne. Te fonksîyona "cookies" girtîye. Xêra xwe kerema xwe "cookies" gengaz bike û careke din biceribîne.',
'noname'                     => 'Navê ku te nivîsand derbas nabe.',
'loginsuccesstitle'          => 'Têketin serkeftî!',
'loginsuccess'               => 'Tu niha di {{SITENAME}} de qeydkirî yî wek "$1".',
'nosuchuser'                 => 'Bikarhênera/ê bi navê "$1" tune. Navê rast binivîse an bi vê formê <b>hesabeke nû çêke</b>. (Ji bo hevalên nû "Têkeve" çênabe!)',
'nosuchusershort'            => 'Li vê derê ne bikarhênerek bi navî "$1" heye. Li nivîsandinê xwe seke.',
'nouserspecified'            => 'Navî xwe wek bikarhêner têkê.',
'wrongpassword'              => 'Şifreya ku te nivîsand şaşe. Ji kerema xwe careke din biceribîne.',
'wrongpasswordempty'         => 'Cîhê şîfreya te vala ye. Carekê din binivisîne.',
'passwordtooshort'           => 'Şîfreya te netê qebûlkirin: Şîfreya te gereke bi kêmani $1 nîşanên xwe hebe û ne wek navî tê wek bikarhêner be.',
'mailmypassword'             => 'Şîfreyeke nû bi e-mail ji min re bişîne',
'passwordremindertitle'      => 'Şîfreyakekî nuh ji hesabekî {{SITENAME}} ra',
'passwordremindertext'       => 'Kesek (têbê tu, bi IP\'ya $1) xwast ku şîfreyekî nuh ji {{SITENAME}} ($4) ji te ra were şandin. Şîfreya nuh ji bikarhêner "$2" niha "$3" e. Tu dikarî niha têkevê û şîfreya xwe biguherînê.

Eger kesekî din vê xastinê ji te ra xast ya şîfreya kevin dîsa hate bîrê te, tu dikarê guh nedê vê peyamê û tu dikarê bi şîfreya xwe yê kevin hên karbikê.',
'noemail'                    => 'Navnîşana bikarhênerê/î "$1" nehat tomar kirine.',
'passwordsent'               => 'Ji navnîşana e-mail ku ji bo "$1" hat tomarkirin şîfreyekê nû hat şandin. Vê bistîne û dîsa têkeve.',
'mailerror'                  => 'Şaşbûnek li cem şandina e-nameyekê: $1',
'acct_creation_throttle_hit' => 'Biborîne! Te hesab $1 vekirine. Tu êdî nikarî hesabên din vekî.',
'emailauthenticated'         => 'Adresa e-nameya hate naskirin: $1.',
'emailconfirmlink'           => 'E-Mail adresê xwe nasbike',
'accountcreated'             => 'Account hate çêkirin',
'accountcreatedtext'         => 'Hesabê bikarhêneran ji $1 ra hate çêkirin.',
'createaccount-title'        => 'Çêkirina hesabekî ji {{SITENAME}}',
'loginlanguagelabel'         => 'Ziman: $1',

# Password reset dialog
'resetpass_text'   => '<!-- Nivîsê xwe li vir binivisîne -->',
'resetpass_header' => 'Şîfreya xwe betalbike',

# Edit page toolbar
'bold_sample'     => 'Nivîsa estûr',
'bold_tip'        => 'Nivîsa estûr',
'italic_sample'   => 'Nivîsa xwar (îtalîk)',
'italic_tip'      => 'Nivîsa xwar (îtalîk)',
'link_sample'     => 'Navê lînkê',
'link_tip'        => 'Lînka hundir',
'extlink_sample'  => 'http://www.minak.com navê lînkê',
'extlink_tip'     => 'Lînka derve (http:// di destpêkê de ji bîr neke)',
'headline_sample' => 'Nivîsara sernameyê',
'headline_tip'    => 'Sername asta 2',
'nowiki_sample'   => 'Nivîs ku nebe formatkirin',
'image_sample'    => 'Mînak.jpg',
'image_tip'       => 'Wêne li hundirê gotarê',
'media_sample'    => 'Mînak.ogg',
'sig_tip'         => 'Îmze û demxeya wext ya te',
'hr_tip'          => 'Rastexêza berwarî (kêm bi kar bîne)',

# Edit pages
'summary'                => 'Kurte û çavkanî (Te çi kir?)',
'subject'                => 'Mijar/sernivîs',
'minoredit'              => 'Ev guheraniyekê biçûk e',
'watchthis'              => 'Vê gotarê bişopîne',
'savearticle'            => 'Rûpelê tomar bike',
'preview'                => 'Pêşdîtin',
'showpreview'            => 'Pêşdîtin',
'showlivepreview'        => 'Pêşdîtinê zindî',
'showdiff'               => 'Guherandinê nîşan bide',
'anoneditwarning'        => "'''Zanibe:''' Tu neketîyê! Navnîşana IP'ya te wê di dîroka guherandina vê rûpelê da bê tomar kirin.",
'summary-preview'        => 'Pêşdîtinê kurtenivîsê',
'subject-preview'        => 'Pêşdîtinê sernivîsê',
'blockedtitle'           => 'Bikarhêner hat asteng kirin',
'blockedtext'            => "<big>'''Navî te ya IP'ya te hate astengkirin.'''</big>

Astengkirinê te ji $1 hate çêkirin. Sedemê astengkirinê te ev e: ''$2''.

* Destpêkê astengkirinê: $8
* Xelasbûnê astengkirinê: $6
* Astengkirinê ji van ra: $7

Tu dikarî bi $1 ya [[{{MediaWiki:Grouppage-sysop}}|koordînatorekî]] din ra ji astengkirinê te ra dengkê. Tu nikanê 'Ji vê/î bikarhênerê/î re e-name bişîne' bikarwîne eger te di [[Special:Preferences|tercihên xwe]] da adrêsê e-nameyekê nenivîsandîye ya tu ji vê fonksîyonê ra jî hatîyê astengkirin.

IP'yê te yê niha $3 ye, û ID'ya astengkirinê te #$5 e. Xêra xwe yek ji van nimran têke peyamê xwe.",
'whitelistedittitle'     => 'Ji bo guherandinê vê gotarê tu gireke xwe qeydbikê.',
'whitelistedittext'      => 'Ji bo guherandina rûpelan, $1 pêwîst e.',
'whitelistreadtitle'     => 'Ji xandinê vê gotarê tu gireke xwe qeydbikê',
'whitelistreadtext'      => 'Ji bo xandinê vê gotarê tu gireke xwe [[Special:Userlogin|li vir]] qedybikê.',
'whitelistacctitle'      => 'Tu nikanê xwe qeydbikê.',
'loginreqtitle'          => 'Têketin pêwîst e',
'loginreqlink'           => 'têkevê',
'accmailtitle'           => 'Şîfre hat şandin.',
'accmailtext'            => "Şîfreya '$1' hat şandin ji $2 re.",
'newarticle'             => '(Nû)',
'newarticletext'         => "Ev rûpel hîn tune. Eger tu bixwazî vê rûpelê çêkî, dest bi nivîsandinê bike û piştre qeyd bike. '''Wêrek be''', biceribîne!<br />
Ji bo alîkarî binêre: [[{{MediaWiki:Helppage}}|Alîkarî]].<br />
Eger tu bi şaştî hatî, bizivire rûpela berê.",
'anontalkpagetext'       => "----
''Ev rûpela guftûgo ye ji bo bikarhênerên nediyarkirî ku hîn hesabekî xwe çênekirine an jî bikarnaînin. Ji ber vê yekê divê em wan bi navnîşana IP ya hejmarî nîşan bikin. Navnîşaneke IP dikare ji aliyê gelek kesan ve were bikaranîn. Heger tu bikarhênerekî nediyarkirî bî û bawerdikî ku nirxandinên bê peywend di der barê te de hatine kirin ji kerema xwe re [[Special:Userlogin|hesabekî xwe veke an jî têkeve]] da ku tu xwe ji tevlîheviyên bi bikarhênerên din re biparêzî.''",
'noarticletext'          => 'Ev rûpel niha vala ye, tu dikarî
[[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an
[{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].',
'usercssjsyoucanpreview' => "<strong>Tîp:</strong> 'Pêşdîtin' bikarwîne ji bo tu bibînê çawa CSS/JS'ê te yê nuh e berî tomarkirinê.",
'usercsspreview'         => "'''Zanibe ku tu bes CSS'ê xwe pêşdibînê, ew ne hatîye tomarkirin!'''",
'updated'                => '(Hat taze kirin)',
'note'                   => '<strong>Not:</strong>',
'previewnote'            => 'Ji bîr neke ku ev bi tenê çavdêriyek e, ev rûpel hîn nehat qeyd kirin!',
'editing'                => 'Biguherîne: "$1"',
'editinguser'            => 'Biguherîne: "$1"',
'editingsection'         => 'Tê guherandin: $1 (beş)',
'editingcomment'         => '$1 (şîrove) tê guherandin.',
'editconflict'           => 'Têkçûna guherandinan: $1',
'explainconflict'        => 'Ji dema te dest bi guherandinê kir heta niha kesekê/î din ev rûpel guherand.

Jor guhartoya heyî tê dîtîn. Guherandinên te jêr tên nîşan dan. Divê tû wan bikî yek. Heke niha tomar bikî, <b>bi tene</b> nivîsara qutiya jor wê bê tomarkirin.
<br />',
'yourtext'               => 'Nivîsara te',
'storedversion'          => 'Versiyona qeydkirî',
'editingold'             => '<strong>HÎŞYAR: Tu ser revîsyoneke kevn a vê rûpelê dixebitî.
Eger tu qeyd bikî, hemû guhertinên ji vê revîzyonê piştre winda dibin.
</strong>',
'yourdiff'               => 'Ciyawazî',
'copyrightwarning'       => "Dîqat bike: Hemû tevkariyên {{SITENAME}} di bin $2 de tên belav kirin (ji bo hûragahiyan li $1 binêre). Eger tu nexwazî ku nivîsên te bê dilrehmî bên guherandin û li gora keyfa herkesî bên belavkirin, li vir neweşîne.<br />
Tu soz didî ku te ev bi xwe nivîsand an jî ji çavkaniyekê azad an geliyane ''(public domain)'' girt.
<strong>BERHEMÊN MAFÊN WAN PARASTÎ (©) BÊ DESTÛR NEWEŞÎNE!</strong>",
'longpagewarning'        => "HIŞYAR: Drêjahiya vê rûpelê $1 kB (kilobayt) e, ev pir e. Dibe ku çend ''browser''
baş nikarin rûpelên ku ji 32 kB drêjtir in biguherînin. Eger tu vê rûpelê beş beş bikî gelo ne çêtir e?",
'protectedpagewarning'   => '<strong>ŞIYARÎ:  Ev rûpel tê parastin. Bi tenê bikarhênerên ku xwediyên mafên "sysop" ne, dikarin vê rûpelê biguherînin.</strong>',
'templatesused'          => 'Şablon di van rûpelan da tê bikaranîn',
'templatesusedpreview'   => 'Şablon yê di vê pêşdîtinê da tên bikaranîn:',
'templatesusedsection'   => 'Şablon yê di vê perçê da tên bikaranîn:',
'template-protected'     => '(tê parastin)',
'template-semiprotected' => '(nîv-parastî)',
'permissionserrorstext'  => 'Tu nikanê vê tiştî bikê, ji bo {{PLURAL:$1|vê sedemê|van sedeman}}:',
'recreate-deleted-warn'  => "'''Zanibe: Tu kê rûpelekê çêkê yê niha hate jêbirin!'''

Zanibe ku nuhçêkirinê vê rûpelê hêja ye ya na.
Înformasyon li ser jêbirinê vê rûpelê li vir e:",

# "Undo" feature
'undo-success' => 'Ev guherandina kane were şondakirin. Xêra xwe ferqê piştî tomarkirinê bibîne û seke, ku tu ew versîyona dixwazê û tomarbike. Eger te şaşbûnekî kir, xêra xwe derkeve.',
'undo-failure' => 'Ev guherandina nikane were şondakirin ji ber ku guherandinên piştî wê.',
'undo-summary' => 'Rêvîsyona $1 yê [[Special:Contributions/$2|$2]] ([[User talk:$2|guftûgo]]) şondakir',

# Account creation failure
'cantcreateaccounttitle' => 'Account nikanî hatiba çêkirin',
'cantcreateaccount-text' => "Çêkirinê account'an ji vê IP'yê (<b>$1</b>) hatîye qedexekirin ji [[User:$3|$3]].

Sedemê qedexekirinê ji $3 ev e: ''$2''",

# History pages
'viewpagelogs'        => 'Înformasîyonan li ser vê rûpelê seke',
'nohistory'           => 'Ew rûpel dîroka guherandinê tune.',
'revnotfound'         => 'Revîzyon nehat dîtin',
'currentrev'          => 'Revîzyona niha',
'revisionasof'        => 'Revîzyon a $1',
'previousrevision'    => '←Rêvîzyona kevintir',
'nextrevision'        => 'Revîzyona nûtir→',
'currentrevisionlink' => 'Revîzyona niha nîşan bide',
'cur'                 => 'ferq',
'next'                => 'pêş',
'last'                => 'berê',
'orig'                => 'orîj',
'page_first'          => 'yekemîn',
'page_last'           => 'paşîn',
'histlegend'          => 'Legend: (ferq) = cudayî nav vê û versiyon a niha,
(berê) = cudayî nav vê û yê berê vê, B = guhêrka biçûk',
'deletedrev'          => '[jêbir]',
'histfirst'           => 'Kevintirîn',
'histlast'            => 'Nûtirîn',
'historysize'         => '($1 bytes)',
'historyempty'        => '(vala)',

# Revision feed
'history-feed-title'          => 'Dîroka versyona',
'history-feed-item-nocomment' => '$1 li $2', # user at time

# Revision deletion
'rev-deleted-comment'       => '(nivîs hate jêbirin)',
'rev-deleted-user'          => '(navî bikarhêner hate jêbirin)',
'rev-delundel'              => 'nîşan bide/veşêre',
'revisiondelete'            => 'Rêvîsyona jêbibe/dîsa çêke',
'revdelete-legend'          => 'Qebûlkirinan ji vê versyonê ra:',
'revdelete-hide-comment'    => 'Nivîsandinê kurte yê guherandinê veşêre',
'revdelete-hide-user'       => "Navî bikarhêner/IP'yê veşêre",
'revdelete-hide-restricted' => 'Ev qebûlkirin ji koordînatoran ra ye jî',
'revdelete-suppress'        => 'Sedemê jêbirinê ji koordînatoran ra jî veşêre',

# Oversight log
'overlogpagetext' => 'Below is a list of the most recent deletions and blocks involving content 
hidden from Sysops. See the [[Special:Ipblocklist|IP block list]] for the list of currently operational bans and blocks.',

# Diffs
'history-title'           => 'Dîroka versyonên "$1"',
'difference'              => '(Ciyawaziya nav revîzyonan)',
'lineno'                  => 'Dêrra $1:',
'compareselectedversions' => 'Guhartoyan Helsengêne',
'editundo'                => 'Betalbike',

# Search results
'searchresults'         => 'Encamên lêgerînê',
'searchresulttext'      => 'Ji bo zêdetir agahî der barê lêgerînê di {{SITENAME}} de, binêre [[{{MediaWiki:Helppage}}|Searching {{SITENAME}}]].',
'searchsubtitle'        => 'Ji bo query "[[:$1]]"',
'searchsubtitleinvalid' => 'Ji bo query "$1"',
'noexactmatch'          => "'''Rûpeleke bi navê \"\$1\" tune.''' Tu dikarî [[:\$1|vê rûpelê biafirînî]]",
'titlematches'          => 'Dîtinên di sernivîsên gotaran de',
'notitlematches'        => 'Di nav sernivîsan de nehat dîtin.',
'textmatches'           => 'Dîtinên di nivîsara rûpelan de',
'notextmatches'         => 'Di nivîsarê de nehat dîtin.',
'prevn'                 => '$1 paş',
'nextn'                 => '$1 pêş',
'viewprevnext'          => '($1) ($2) ($3).',
'showingresults'        => '<b>$1</b> encam, bi #<b>$2</b> dest pê dike.',
'showingresultsnum'     => '<b>$3</b> encam, bi #<b>$2</b> dest pê dike.',
'powersearch'           => 'Lê bigere',
'powersearchtext'       => 'Lêgerîn di nav cihên navan de:<br />
$1<br />
$2 Ragihandinan nîşan bide &amp;nbsp; Lêbigere: $3 $9',
'searchdisabled'        => '<p>Tu dikarî li {{SITENAME}} bi Google an Yahoo! bigere. Têbînî: Dibe ku encamen lêgerîne ne yên herî nû ne.
</p>',

# Preferences page
'preferences'       => 'Tercîhên min',
'mypreferences'     => 'Tercihên min',
'prefs-edits'       => 'Hejmarê guherandinan:',
'prefsnologin'      => 'Xwe qeyd nekir',
'changepassword'    => 'Şîfre biguherîne',
'skin'              => 'Pêste',
'math'              => 'TeX',
'dateformat'        => 'Formata rojê',
'datedefault'       => 'Tercih tune ne',
'datetime'          => 'Dem û rêkewt',
'prefs-personal'    => 'Agahiyên bikarhênerê/î',
'prefs-rc'          => 'Guherandinên dawî',
'prefs-misc'        => 'Eyaren cuda',
'saveprefs'         => 'Tercîhan qeyd bike',
'oldpassword'       => 'Şîfreya kevn',
'newpassword'       => 'Şîfreya nû',
'retypenew'         => 'Şîfreya nû careke din binîvîse',
'textboxsize'       => 'Guheranin',
'rows'              => 'Rêz',
'columns'           => 'sitûn',
'searchresultshead' => 'Eyarên encamên lêgerinê',
'savedprefs'        => 'Tercîhên te qeyd kirî ne.',
'default'           => 'asayî',
'files'             => 'Dosya',

# User rights
'userrights-lookup-user'   => 'Îdarekirina grûpan',
'userrights-user-editname' => 'Navî bikarhênerê têke:',
'userrights-groupsmember'  => 'Endamê:',

# Groups
'group-sysop' => 'Koordînatoran',

'group-sysop-member' => 'Koordînator',

# User rights log
'rightsnone' => '(tune)',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|guherandinek|guherandin}}',
'recentchanges'   => 'Guherandinên dawî',
'rcnote'          => "Jêr {{PLURAL:$1|guherandinek|'''$1''' guherandinên dawî}} di {{PLURAL:$2|rojê|'''$2''' rojên dawî}} de ji $3 şûnde tên nîşan dan.",
'rclistfrom'      => 'an jî guherandinên ji $1 şûnda nîşan bide.',
'rcshowhideminor' => '$1 guherandinên biçûk',
'rcshowhideliu'   => '$1 bikarhênerê qeydkirî',
'rcshowhideanons' => '$1 bikarhênerên neqeydkirî (IP)',
'rcshowhidemine'  => '$1 guherandinên min',
'rclinks'         => '$1 guherandinên di $2 rojên dawî de nîşan bide<br />$3',
'diff'            => 'ciyawazî',
'hist'            => 'dîrok',
'hide'            => 'veşêre',
'show'            => 'nîşan bide',
'minoreditletter' => 'B',
'newpageletter'   => 'Nû',

# Recent changes linked
'recentchangeslinked' => 'Guherandinên peywend',

# Upload
'upload'               => 'Wêneyekî barbike',
'uploadbtn'            => 'Wêneyê (ya tiştekî din ya mêdya) barbike',
'reupload'             => 'Dîsa barbike',
'uploadnologin'        => 'Xwe qeyd nekir',
'uploadnologintext'    => 'Ji bo barkirina wêneyan divê ku tu [[Special:Userlogin|têkevî]].',
'uploadtext'           => "Berê tu wêneyên nû bar bikî, ji bo dîtin an vedîtina wêneyên ku ji xwe hene binêre: [[Special:Imagelist|lîsteya wêneyên barkirî]]. Herwisa wêneyên ku hatine barkirin an jî jê birin li vir dikarî bibînî: [[Special:Log/upload|reşahiya barkiriyan]]. 

Yek ji lînkên jêr ji bo bikarhînana wêne an faylê di gotarê de bikar bihîne:

* '''<nowiki>[[{{ns:image}}:File.jpg]]</nowiki>'''
* '''<nowiki>[[{{ns:image}}:File.png|alt text]]</nowiki>'''
anjî ji bo faylên dengî
* '''<nowiki>[[{{ns:media}}:File.ogg]]</nowiki>'''",
'uploadlogpage'        => 'Înformasyon li ser barkirinê',
'filename'             => 'Navê dosyayê',
'filedesc'             => 'Kurte',
'fileuploadsummary'    => 'Kurte:',
'filesource'           => 'Çavkanî',
'uploadedfiles'        => 'Dosyayên bar kirî',
'ignorewarning'        => 'Hişyarê qebûl neke û dosyayê qeyd bike.',
'ignorewarnings'       => 'Guh nede hîşyaran',
'illegalfilename'      => 'Navî datayê "$1" ne tê qebûlkirin ji ber ku tişt tê da hatine nivîsandin yê qedexe ne. Xêra xwe navî datayê biguherîne û carekî din barbike.',
'badfilename'          => 'Navê vî wêneyî hat guherandin û bû "$1".',
'filetype-missing'     => 'Piştnavî datayê tune (wek ".jpg").',
'fileexists'           => 'Datayek bi vê navê berê heye. Eger tu niha li „Tomarbike“ xê, ew wêneyê kevin ê here û wêneyê te ê were barkirin di bin wê navê. Di bin $1 du dikarî sekê, ku di dixwazê wê wêneyê biguherînê. Eger tu naxazê, xêra xwe li „Betal“ xe.',
'fileexists-extension' => 'Datayek wek vê navê berê heye:<br />
Navî datayê yê tê barkirin: <strong><tt>$1</tt></strong><br />
Navî datayê yê berê heyê: <strong><tt>$2</tt></strong><br />
Xêra xwe navekî din bibîne.',
'fileexists-thumb'     => "<center>'''Wêne yê berê heye'''</center>",
'file-thumbnail-no'    => 'Navî vê datayê bi <strong><tt>$1</tt></strong> destpêdike. Ev dibêje ku ev wêneyekî çûçik e <i>(thumbnail)</i>. Xêra xwe seke, ku belkî versyonekî mezin yê vê wêneyê li cem te heye û wê wêneyê mezintir di bin navî orîjînal da barbike.',
'fileexists-forbidden' => 'Medyayek bi vê navî heye; xêra xwe şonda here û vê medyayê bi navekî din barbike.
[[Image:$1|thumb|center|$1]]',
'successfulupload'     => 'Barkirin serkeftî',
'uploadwarning'        => 'Hişyara barkirinê',
'savefile'             => 'Dosyayê tomar bike',
'uploadedimage'        => '"$1" barkirî',
'sourcefilename'       => 'Navî wêneyê (ya tiştekî din ya mêdya)',
'destfilename'         => 'Navî wêneyê (ya tiştekî din ya mêdya) yê xastî',
'watchthisupload'      => 'Vê rûpelê bişopîne',

# Image list
'imagelist'                 => 'Listeya wêneyan',
'imagelisttext'             => "Jêr lîsteyek ji $1 file'an heye, duxrekirin $2.",
'ilsubmit'                  => 'Lêbigere',
'showlast'                  => '$1 wêneyên dawî bi rêz kirî $2 nîşan bide.',
'byname'                    => 'li gor navê',
'bydate'                    => 'li gor dîrokê',
'bysize'                    => 'li gor mezinayiyê',
'filehist'                  => 'Dîroka datayê',
'filehist-help'             => 'Li demekê xwe, manê tu kanibê verzîyona data di wê demê da bibînê.',
'filehist-deleteall'        => 'giştika jêbibe',
'filehist-deleteone'        => 'vî jêbibe',
'filehist-revert'           => 'şonda bibe',
'filehist-current'          => 'niha',
'filehist-datetime'         => 'Roj / Katjimêr',
'filehist-user'             => 'Bikarhêner',
'filehist-dimensions'       => 'Mezinbûn',
'filehist-filesize'         => "Mezinbûna data'yê",
'filehist-comment'          => 'Nivîs',
'imagelinks'                => 'Lînkên vî wêneyî',
'linkstoimage'              => 'Di van rûpelan de lînkek ji vî wêneyî re heye:',
'nolinkstoimage'            => 'Rûpelekî ku ji vî wêneyî re girêdankê çêdike nîne.',
'noimage'                   => 'Medyayek bi vê navî tune, lê tu kanî $1',
'noimage-linktext'          => 'wê barbike',
'uploadnewversion-linktext' => 'Versyonekî nû yê vê datayê barbike',
'imagelist_date'            => 'Dem',
'imagelist_name'            => 'Nav',
'imagelist_description'     => 'Wesif',
'imagelist_search_for'      => 'Li navî wêneyê bigere:',

# File deletion
'filedelete'         => '$1 jêbibe',
'filedelete-legend'  => 'Data jêbibe',
'filedelete-intro'   => "Tu kê '''[[Media:$1|$1]]''' jêbibê.",
'filedelete-comment' => 'Nivîs:',
'filedelete-submit'  => 'Jêbibe',
'filedelete-success' => "'''$1''' hate jêbirin.",
'filedelete-nofile'  => "'''$1''' li vê rûpelê tune.",

# MIME search
'download' => 'dabezandin',

# Unwatched pages
'unwatchedpages' => 'Gotar ê ne tên şopandin',

# Unused templates
'unusedtemplateswlh' => 'lînkên din',

# Random page
'randompage' => 'Rûpelek bi helkeft',

# Statistics
'statistics'    => 'Statîstîk',
'sitestats'     => 'Statîstîkên rûpelê',
'userstats'     => 'Statistîkên bikarhêneran',
'sitestatstext' => "Di ''database'' de {{PLURAL:$1|rûpelek|'''$1''' rûpel}} hene.
Tê de rûpelên guftûgoyê, rûpelên der barê {{SITENAME}}, rûpelên pir kurt (stub), rûpelên ragihandinê (redirect) û rûpelên din ku qey ne gotar in hene.
Derve wan, {{PLURAL:$2|rûpelek|'''$2''' rûpel}} hene, ku qey {{PLURAL:$2|gotarêkî rewa ye|gotarên rewa ne}}. 

{{PLURAL:$8|Dosyayek hatîye|'''$8''' dosya hatine}} barkirin.

Ji afirandina Wîkiyê heta roja îro '''$3''' {{PLURAL:$3|cara rûpelek hate|caran rûpelan hatin}} mezekirin û '''$4''' {{PLURAL:$3|cara rûpelek hate|caran rûpelan hatin}} guherandin ji destpêkê {{SITENAME}} da.
Ji ber wê di nîvî de her rûpel '''$5''' carî hatiye guherandin, û nîspeta dîtun û guherandinan '''$6''' e.

Dirêjahîya [http://meta.wikimedia.org/wiki/Help:Job_queue ''job queue''] '''$7''' e.",
'userstatstext' => "Li vir {{PLURAL:$1|[[Special:Listusers|bikarhênerekî]]|'''$1''' [[Special:Listusers|bikarhênerên]]}} qeydkirî {{PLURAL:$1|heye|hene}}, ji wan '''$2''' (an '''$4%''') qebûlkirinên $5 {{PLURAL:$2|birîye|birine}}.",

'disambiguations' => 'Rûpelên cudakirinê',

'brokenredirects'        => 'Ragihandinên jê bûye',
'brokenredirects-edit'   => '(biguherîne)',
'brokenredirects-delete' => '(jêbibe)',

# Miscellaneous special pages
'nbytes'                  => "$1 {{PLURAL:$1|byte|byte'an}}",
'nlinks'                  => '$1 {{PLURAL:$1|lînk|lînkan}}',
'nmembers'                => '$1 {{PLURAL:$1|endam|endam}}',
'nviews'                  => '$1 {{PLURAL:$1|dîtin|dîtin}}',
'lonelypages'             => 'Rûpelên sêwî',
'uncategorizedpages'      => 'Rûpelên bê kategorî',
'uncategorizedcategories' => 'Kategoriyên bê kategorî',
'unusedcategories'        => 'Kategoriyên ku nayên bi kar anîn',
'unusedimages'            => 'Wêneyên ku nayên bi kar anîn',
'popularpages'            => 'Rûpelên populer',
'wantedcategories'        => 'Kategorîyên tên xwestin',
'wantedpages'             => 'Rûpelên ku tên xwestin',
'mostcategories'          => 'Gotar bi pir kategorîyan',
'allpages'                => 'Hemû rûpel',
'shortpages'              => 'Rûpelên kurt',
'longpages'               => 'Rûpelên dirêj',
'deadendpages'            => 'Rûpelên bê dergeh',
'listusers'               => 'Lîsteya bikarhêneran',
'specialpages'            => 'Rûpelên taybet',
'spheading'               => 'Rûpelên taybet ji bo hemû bikarhêneran',
'newpages'                => 'Rûpelên nû',
'newpages-username'       => 'Navê bikarhêner:',
'ancientpages'            => 'Gotarên kevintirîn',
'move'                    => 'Navê rûpelê biguherîne',
'movethispage'            => 'Vê rûpelê bigerîne',
'notargettitle'           => 'Hedef tune',

# Book sources
'booksources'               => 'Çavkaniyên pirtûkan',
'booksources-summary'       => "Di vê rûpelê da tu dikarî li pirtûkan bi rêkê ISBN'ê wan bigerê. Xet ya cihên boş mişkla ji bo lêgerînê ra çênakin.",
'booksources-search-legend' => 'Li pirtûkan bigere',
'booksources-go'            => 'Lêbigere',
'booksources-text'          => 'Li vir listek ji lînkên rûpelên, yê pirtûkên nuh ya kevin difiroşin, heye. Hên jî li vir tu dikarî înformasyonan li ser wan pirtûkan tê derxê.',

'categoriespagetext' => 'Di vê wîkiyê de ev kategorî hene:',
'groups'             => 'Grûpen bikarhêneran',
'alphaindexline'     => '$1 heta $2',
'version'            => 'Verzîyon',

# Special:Log
'specialloguserlabel'  => 'Bikarhêner:',
'speciallogtitlelabel' => 'Sernav:',
'log'                  => 'Reşahiyan',
'logempty'             => 'Tişt di vir da tune.',
'log-title-wildcard'   => 'Li sernavan bigere, yê bi vê destpêdikin',

# Special:Allpages
'nextpage'         => 'Rûpela pêşî ($1)',
'allpagesfrom'     => 'Pêşdîtina rûpelan bi dest pê kirin ji',
'allarticles'      => 'Hemû gotar',
'allinnamespace'   => 'Hemû rûpelan ($1 boşahî a nav)',
'allpagesprev'     => 'Pêş',
'allpagesnext'     => 'Paş',
'allpagessubmit'   => 'Biçe',
'allpagesprefix'   => 'Nîşan bide rûpelên bi pêşgira:',
'allpagesbadtitle' => 'Sernavê rûpelê qedexe bû ya "interwiki"- ya "interlanguage"-pêşnavekî xwe hebû. Meqûle ku zêdertirî tiştekî nikanin werin bikaranîn di sernavê da.',

# Special:Listusers
'listusers-submit'   => 'Pêşêkê',
'listusers-noresult' => 'Ne bikarhênerek hate dîtin.',

# E-mail user
'mailnologin'     => 'Navnîşan neşîne',
'emailuser'       => 'Ji vê/î bikarhênerê/î re e-name bişîne',
'emailpage'       => 'E-name bikarhêner',
'defemailsubject' => '{{SITENAME}} e-name',
'noemailtitle'    => 'Navnîşana e-name tune',
'emailfrom'       => 'Ji',
'emailto'         => 'Bo',
'emailsubject'    => 'Mijar',
'emailmessage'    => 'Name',
'emailsend'       => 'Bişîne',
'emailccme'       => "Kopîyekê ji min ra ji vê E-Mail'ê ra bişîne.",
'emailsent'       => 'E-name hat şandin',
'emailsenttext'   => 'E-nameya te hat şandin.',

# Watchlist
'watchlist'            => 'Lîsteya min ya şopandinê',
'mywatchlist'          => 'Lîsteya min ya şopandinê',
'watchlistfor'         => "(ji bo '''$1''')",
'watchlistanontext'    => 'Ji bo sekirinê ya xeyrandinê lîsteya te ya şopandinê tu gireke xwe $1.',
'watchnologin'         => 'Te xwe qeyd nekirîye.',
'watchnologintext'     => 'Ji bo xeyrandinê lîsteya te ya şopandinê tu gireke xwe [[Special:Userlogin|qedy kiribe]].',
'addedwatch'           => 'Hat îlawekirinî listeya şopandinê',
'addedwatchtext'       => "Rûpela \"\$1\" çû ser [[Special:Watchlist|lîsteya te ya şopandinê]].
Li dahatû de her guhartoyek li wê rûpelê û rûpela guftûgo ya wê were kirin li vir dêt nîşan dan,
 
Li rûpela [[Special:Recentchanges|Guherandinên dawî]] jî ji bo hasan dîtina wê, ew rûpel bi '''Nivîsa estûr''' dê nîşan dayîn.


<p>Her dem tu bixwazî ew rûpel li nav lîsteya te ya şopandinê derbikî, li ser wê rûpelê, klîk bike \"êdî neşopîne\".</p>",
'removedwatch'         => 'Ji lîsteya şopandinê hate jêbirin',
'removedwatchtext'     => 'Rûpela "$1" ji lîsteya te ya şopandinê hate jêbirin.',
'watch'                => 'Bişopîne',
'watchthispage'        => 'Vê rûpelê bişopîne',
'unwatch'              => 'Êdî neşopîne',
'notanarticle'         => 'Ne gotar e',
'watchnochange'        => 'Ne rûpelek, yê tu dişopînê, hate xeyrandin di vê wextê da, yê tu dixazê bibînê.',
'watchlist-details'    => '* {{PLURAL:$1|Rûpelek tê|$1 rûpel tên}} şopandin, rûpelên guftûgoyê netên jimartin.',
'wlheader-enotif'      => '* E-mail-şandin çêbû.',
'wlheader-showupdated' => "* Ew rûpel yê hatin xeyrandin jilkî te li wan sekir di '''nivîsa estûr''' tên pêşandin.",
'watchlistcontains'    => 'Di lîsteya şopandina te de {{PLURAL:$1|rûpelek heye|$1 rûpel hene}}.',
'wlnote'               => "Niha {{PLURAL:$1|xeyrandinê|'''$1''' xeyrandinên}} dawî yê {{PLURAL:$2|seetê|'''$2''' seetên}} dawî {{PLURAL:$1|tê|tên}} dîtin.",
'wlshowlast'           => 'Xeyrandînên berî $1 seetan, $2 rojan, ya $3 (di rojên sîyî paşî)',
'watchlist-hide-bots'  => "Guherandinên Bot'an veşêre",
'watchlist-show-own'   => 'Guherandinên min pêşke',
'watchlist-hide-own'   => 'Guherandinên min veşêre',
'watchlist-show-minor' => 'Guherandinên biçûk pêşke',
'watchlist-hide-minor' => 'Guherandinên biçûk veşêre',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Bişopîne...',
'unwatching' => 'Neşopîne...',

'enotif_newpagetext' => 'Ev rûpeleke nû ye.',
'changed'            => 'guhart',
'created'            => 'afirandî',
'enotif_subject'     => '[{{SITENAME}}] Rûpelê "$PAGETITLE" ji $PAGEEDITOR hate $CANGEDORCREATED',
'enotif_body'        => '$WATCHINGUSERNAME,
	

Rûpelê {{SITENAME}} $PAGETITLE hate $CHANGEDORCREATED di rojê $PAGEEDITDATE da ji $PAGEEDITOR, xêra xwe li $PAGETITLE_URL ji versyonê niha ra seke.

$NEWPAGE

Kurtnivîsê wê bikarhênerî: $PAGESUMMARY $PAGEMINOREDIT

Ji wî bikarhênerî mêsajekî binivisîne:
E-name: $PAGEEDITOR_EMAIL
{{SITENAME}}: $PAGEEDITOR_WIKI

Heta tu vê guherandinê senekê, mêsajên din ji ber ku guherandinê wê rûpelê yê netên. 

             Sîstêmê mêsajan yê {{SITENAME}}

--
Eger tu dixazê lîstêya xwe yê şopandinê biguherînê, li 
{{fullurl:{{ns:special}}:Watchlist/edit}} seke.

"Feedback" û alîkarîyê din:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Rûpelê jê bibe',
'confirm'                     => 'Pesend bike',
'excontent'                   => "Naveroka berê: '$1'",
'excontentauthor'             => "Nawerokê wê rûpelê ew bû: '$1' (û tenya bikarhêner '$2' bû)",
'exbeforeblank'               => "Nawerok berî betal kirinê ew bû: '$1'",
'exblank'                     => 'rûpel vala bû',
'confirmdelete'               => 'Teyîda jêbirinê',
'deletesub'                   => '("$1" tê jêbirin)',
'historywarning'              => 'Hîşyar: Ew rûpel ku tu dixwazî jê bibî dîrokek heye:',
'actioncomplete'              => 'Çalakî temam',
'deletedtext'                 => '"$1" hat jêbirin. Ji bo qeyda rûpelên ku di dema nêzîk de hatin jêbirin binêre $2.',
'deletedarticle'              => '"$1" hat jêbirin',
'dellogpage'                  => 'Reşahiya_jêbirin',
'dellogpagetext'              => 'Li jêr lîsteyek ji jêbirinên dawî heye.',
'deletionlog'                 => 'reşahiya jêbirin',
'deletecomment'               => 'Sedema jêbirinê',
'rollback_short'              => 'Bizivirîne pêş',
'rollbacklink'                => 'bizivirîne pêş',
'cantrollback'                => "Guharto naye vegerandin; bikarhêrê dawî, '''tenya''' nivîskarê wê rûpelê ye.",
'alreadyrolled'               => 'Guherandina dawiya [[$1]]
bi [[User:$2|$2]] ([[User talk:$2|guftûgo]]) venizivre; keseke din wê rûpelê zivrandiye an guherandiye.

Guhartoya dawî bi [[User:$3|$3]] ([[User talk:$3|guftûgo]]).',
'revertpage'                  => 'Guherandina $2 hat betal kirin, vegerand guhartoya dawî ya $1',
'protectlogpage'              => 'Reşahiya _parastiyan',
'protectedarticle'            => 'parastî [[$1]]',
'unprotectedarticle'          => '"[[$1]]" niha vê parastin e',
'confirmprotect'              => 'Parastinê teyîd bike',
'protectcomment'              => 'Sedema parastinê',
'unprotectsub'                => 'Parastinê "$1" rake',
'protect-default'             => '(standard)',
'protect-level-autoconfirmed' => 'Bikarhênerên neqeydkirî astengbike',
'protect-level-sysop'         => 'Bes koordînatoran (admînan)',
'protect-expiring'            => 'heta rojê $1 (UTC)',
'restriction-type'            => 'Destûr:',

# Restrictions (nouns)
'restriction-edit' => 'Biguherîne',
'restriction-move' => 'Nav biguherîne',

# Undelete
'undelete'               => 'Li rûpelên jêbirî seke',
'viewdeletedpage'        => 'Rûpelên vemirandî seke',
'undeletebtn'            => 'Dîsa çêke!',
'undeletedarticle'       => '"[[$1]]" dîsa çêkir',
'undelete-search-box'    => 'Rûpelên jêbirî lêbigere',
'undelete-search-prefix' => 'Rûpela pêşe min ke ê bi vê destpêdîkin:',
'undelete-search-submit' => 'Lêbigere',

# Namespace form on various pages
'namespace'      => 'Boşahiya nav:',
'invert'         => 'Hilbijardinê pêçewane bike',
'blanknamespace' => '(Serekî)',

# Contributions
'contributions' => 'Beşdariyên vê bikarhêner',
'mycontris'     => 'Beşdariyên min',
'contribsub2'   => 'Ji bo $1 ($2)',
'uclinks'       => '$1 guherandinên dawî; $2 rojên dawî',
'uctop'         => ' (ser)',
'month'         => 'Ji mihê (û zûtir):',
'year'          => 'Ji salê (û zûtir):',

'sp-contributions-newbies'     => 'Bes beşdarîyên bikarhênerê nû pêşêkê',
'sp-contributions-newbies-sub' => 'Ji bikarhênerên nû re',
'sp-contributions-blocklog'    => 'Înformasyon li ser astengkirinê',
'sp-contributions-search'      => 'Li beşdarîyan bigere',
'sp-contributions-username'    => 'Adresê IP ya navî bikarhêner:',
'sp-contributions-submit'      => 'Lêbigere',

# What links here
'whatlinkshere'       => 'Lînk yê tên ser vê rûpelê',
'whatlinkshere-title' => 'Rûpelan, yê berve $1 tên',
'linklistsub'         => '(Listeya lînkan)',
'linkshere'           => "Ev rûpel tên ser vê rûpelê '''„[[:$1]]“''':",
'nolinkshere'         => "Ne ji rûpelekê lînk tên ser '''„[[:$1]]“'''.",
'isredirect'          => 'rûpela ragihandinê',

# Block/unblock
'blockip'                => 'Bikarhêner asteng bike',
'blockiptext'            => 'Ji bo astengkirina nivîsandinê ji navnîşaneke IP an bi navekî bikarhêner, vê formê bikarbîne.
Ev bes gireke were bikaranîn ji bo vandalîzmê biskinîne (bi vê [[{{MediaWiki:Policy-url}}|qebûlkirinê]]). 

Sedemekê binivîse!',
'ipaddress'              => "adresê IP'yekê",
'ipadressorusername'     => "adresê IP'yekê ya navekî bikarhênerekî",
'ipbexpiry'              => 'Dem:',
'ipbreason'              => 'Sedem',
'ipbreasonotherlist'     => 'Sedemekî din',
'ipbreason-dropdown'     => '*Sedemên astengkirinê (bi tevayî)
** vandalîzm
** înformasyonên şaş kir gotarekê
** rûpelê vala kir
** bes lînkan dikir rûpelan
** kovan dikir gotaran
** heqaretkirin
** pir accounts dikaranîn
** navekî pîs',
'ipbanononly'            => 'Bes bikarhênerî veşartî astengbike (bikarhênerên qeydkirî bi vê IP-adresê ne tên astengkirin).',
'ipbcreateaccount'       => "Çêkirina account'an qedexebike.",
'ipbemailban'            => 'Şandinê E-Nameyan qedexe bike.',
'ipbenableautoblock'     => "Otomatîk IP'yên niha û yên nuh yê vê bikarhênerê astengbike.",
'ipbsubmit'              => 'Vê bikarhêner asteng bike',
'ipbother'               => 'demekî din',
'ipboptions'             => '1 seet:1 hour,2 seet:2 hours,6 seet:6 hours,1 roj:1 day,3 roj:3 days,1 hefte:1 week,2 hefte:2 weeks,1 mihe:1 month,3 mihe:3 months,1 sal:1 year,ji her demê ra:infinite', # display1:time1,display2:time2,...
'ipbotheroption'         => 'yên din',
'ipbotherreason'         => 'Sedemekî din',
'ipbhidename'            => 'Navî bikarhêner / adresê IP ji "pirtûkê" astengkirinê, lîsteya astengkirinên nuh û lîsteya bikarhêneran veşêre',
'badipaddress'           => 'Bikarhêner bi vî navî tune',
'blockipsuccesssub'      => 'Blok serkeftî',
'blockipsuccesstext'     => '"$1" hat asteng kirin.
<br />Bibîne [[Special:Ipblocklist|Lîsteya IP\'yan hatî asteng kirin]] ji bo lîsteya blokan.',
'ipb-unblock-addr'       => 'Astengkirinê $1 rake',
'ipblocklist'            => "Listek ji adresên IP'yan û bikarhêneran yê hatine astengkirin",
'ipblocklist-legend'     => 'Bikarhênerekî astengkirî bibîne',
'ipblocklist-submit'     => 'Lêbigere',
'blocklistline'          => '$1, $2 $3 asteng kir ($4)',
'expiringblock'          => 'heta $1',
'emailblock'             => 'E-Mail hate girtin',
'ipblocklist-empty'      => 'Lîsteya astengkirinê vala ye.',
'ipblocklist-no-results' => "Ew IP'ya ya bikarhênera nehatîye astengkirin.",
'blocklink'              => 'asteng bike',
'unblocklink'            => 'betala astengê',
'contribslink'           => 'Beşdarî',
'autoblocker'            => 'Otomatîk hat bestin jiberku IP-ya we û ya "[[User:$1|$1]]" yek in. Sedem: "\'\'\'$2\'\'\'"',
'blocklogpage'           => 'Reşahiya_asteng kiriyan',
'blocklogentry'          => '"[[$1]]" ji bo dema $2 hatiye asteng kirin',
'unblocklogentry'        => 'astenga "$1" hat betal kirin',
'ipb_expiry_invalid'     => 'Dem ne serrast e.',
'ipb_already_blocked'    => '"$1" berê hatîye astengkirin',
'proxyblocksuccess'      => 'Çêbû.',

# Move page
'movepage'                => 'Vê rûpelê bigerîne',
'movepagetalktext'        => "Rûpela '''guftûgoyê''' vê rûpelê wê were, eger hebe, gerandin. Lê ev tişta nameşe, eger

*berê guftûgoyek bi wê navê hebe ya
*tu tiştekî jêr hilbijêrê.

Eger ev mişkla çêbû, tu gireke vê rûpelê bi xwe bigerînê.

Xêra xwe navî nuh û sedemê navgerandinê binivisîne.",
'movearticle'             => 'Rûpelê bigerîne',
'movenologin'             => 'Xwe qeyd nekir',
'movenologintext'         => 'Tu dive bikarhênereke qeydkirî bî û [[Special:Userlogin|werî nav sîstemê]]
da bikarî navê wê rûpelê biguherînî.',
'newtitle'                => 'Sernivîsa nû',
'movepagebtn'             => 'Vê rûpelê bigerîne',
'pagemovedsub'            => 'Gerandin serkeftî',
'movepage-moved'          => '<big>\'\'\'"$1" çû "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Rûpela bi vî navî heye, an navê ku te hilbijart derbas nabe. Navekî din hilbijêre.',
'movedto'                 => 'bû',
'movetalk'                => "Rûpela '''guftûgo''' ya wê jî bigerîne, eger gengaz be.",
'1movedto2'               => '$1 çû cihê $2',
'1movedto2_redir'         => '$1 çû cihê $2 ser redirect',
'movelogpage'             => 'Reşahiya nav guherandin',
'movelogpagetext'         => 'Li jêr lîsteyek ji rûpelan ku navê wan hatiye guherandin heye.',
'movereason'              => 'Sedem',
'revertmove'              => 'şondabike',
'delete_and_move'         => 'Jêbibe û nav biguherîne',
'delete_and_move_confirm' => 'Erê, wê rûpelê jêbibe',
'delete_and_move_reason'  => 'Jêbir ji bo navguherandinê',

# Namespace 8 related
'allmessages'               => 'Hemû mesajên sîstemê',
'allmessagesname'           => 'Nav',
'allmessagescurrent'        => 'Texta niha',
'allmessagestext'           => 'Ev lîsteya hemû mesajên di namespace a MediaWiki: de ye.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' cannot be used because '''\$wgUseDatabaseMessages''' is switched off.",

# Thumbnails
'thumbnail-more' => 'Mezin bike',
'filemissing'    => 'Data tune',

# Special:Import
'importtext'         => 'Please export the file from the source wiki using the {{ns:special}}:Export utility, save it to your disk and upload it here.',
'importstart'        => 'Rûpel tên împortkirin...',
'importnopages'      => 'Ne rûpelek ji împortkirinê ra heye.',
'importfailed'       => 'Împort nebû: $1',
'importbadinterwiki' => 'Interwiki-lînkekî xerab',
'importnotext'       => 'Vala an nivîs tune',
'importsuccess'      => 'Împort çêbû!',

# Import log
'importlogpage' => 'Înformasyon li ser Împortê',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Rûpela min a şexsî',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Rûpela guftûgo ya min',
'tooltip-pt-preferences'          => ',Tercîhên min',
'tooltip-pt-watchlist'            => 'The list of pages you',
'tooltip-pt-mycontris'            => 'Lîsteya tevkariyên min',
'tooltip-pt-logout'               => 'Derkeve (Log out)',
'tooltip-ca-talk'                 => 'guftûgo û şîrove ser vê rûpelê',
'tooltip-ca-edit'                 => 'Vê rûpelê biguherîne! Berê qeydkirinê bişkoka "Pêşdîtin',
'tooltip-ca-addsection'           => 'Beşekê zêde bike.',
'tooltip-ca-viewsource'           => 'Ev rûpela tê parastin. Tu dikarê bes li çavkanîyê sekê.',
'tooltip-ca-history'              => 'Versyonên berê yên vê rûpelê.',
'tooltip-ca-protect'              => 'Vê rûplê biparêze',
'tooltip-ca-delete'               => 'Vê rûpelê jê bibe',
'tooltip-ca-move'                 => 'Navekî nû bide vê rûpelê',
'tooltip-search'                  => 'Li vê wikiyê bigêre',
'tooltip-p-logo'                  => 'Destpêk',
'tooltip-n-mainpage'              => 'Biçe Destpêkê',
'tooltip-n-help'                  => 'Bersivên ji bo pirsên te.',
'tooltip-t-whatlinkshere'         => 'Lîsteya hemû rûpelên ku ji vê re grêdidin.',
'tooltip-t-recentchangeslinked'   => 'Recent changes in pages linking to this page',
'tooltip-t-emailuser'             => 'Jê re name bişîne',
'tooltip-ca-nstab-user'           => 'Rûpela bikarhênerê/î temaşe bike',
'tooltip-ca-nstab-special'        => 'This is a special page, you can',
'tooltip-compareselectedversions' => 'Cudatiyên guhartoyên hilbijartî yên vê rûpelê bibîne.',

# Attribution
'anonymous' => 'Bikarhênera/ê nediyarkirî ya/yê {{SITENAME}}',
'siteuser'  => 'Bikarhênera/ê $1 a/ê {{SITENAME}}',
'and'       => 'û',
'others'    => 'ên din',
'siteusers' => 'Bikarhênerên $1 yên {{SITENAME}}',

# Spam protection
'spamprotectiontitle'    => 'Parastina spam',
'spamprotectiontext'     => 'Ew rûpela yê tu dixast tomarbikê hate astengkirin ji ber ku parastina spam. Ew çêbû ji ber ku lînkekî derva di vê rûpelê da ye.',
'spamprotectionmatch'    => 'Ev nivîsa parastinê spam vêxist: $1',
'subcategorycount'       => 'Di vê kategoriyê de {{PLURAL:$1|binkategorîyek heye|$1 binkategorî hene}}.',
'categoryarticlecount'   => 'Di vê kategoriyê de {{PLURAL:$1|gotarek heye|$1 gotar hene}}.',
'listingcontinuesabbrev' => 'dewam',

# Image deletion
'deletedrevision'                 => 'Rêvîsîyona berê $1 hate jêbirin.',
'filedelete-missing'              => 'Data\'yê "$1" nikane were jêbirin, ji ber ku ew tune.',
'filedelete-current-unregistered' => 'Datayê "$1" di sistêmê da tune.',

# Browsing diffs
'previousdiff' => '← Ciyawaziya pêştir',
'nextdiff'     => 'Ciyawaziya paştir →',

# Media information
'widthheight'    => '$1 x $2',
'file-info'      => '(mezinbûnê data: $1, MIME-typ: $2)',
'file-info-size' => '($1 × $2 pixel, mezinbûnê data: $3, MIME-typ: $4)',
'file-nohires'   => '<small>Versyonekî jê mezintir tune.</small>',
'show-big-image' => 'Mezînbûn',

# Special:Newimages
'newimages' => 'Pêşangeha wêneyên nû',

# EXIF tags
'exif-imagedescription' => 'Navî wêneyê',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'hemû',
'imagelistall'     => 'hemû',
'watchlistall2'    => 'hemû',
'namespacesall'    => 'Hemû',
'monthsall'        => 'giştik',

# E-mail address confirmation
'confirmemail_noemail'  => 'Te e-mail-adressê xwe di [[Special:Preferences|tercihên xwe da]] nenivîsandîye.',
'confirmemail_success'  => 'E-Mail adrêsa te hate naskirin. Tu niha dikarî xwe qeydbikê û kêfkê.',
'confirmemail_loggedin' => 'Adrêsa te yê E-Mail hate qebûlkirin.',
'confirmemail_body'     => 'Kesek, dibê tu, bi IP adressê $1, xwe li {{SITENAME}} bi vê navnîşana e-name tomar kir ("$2") .

Eger ev rast qeydkirinê te ye û di dixwazî bikaranîna e-nama ji te ra çêbibe li {{SITENAME}}, li vê lînkê bitikîne:

$3

Lê eger ev *ne* tu bû, li lînkê netikîne. Ev e-nameya di rojê $4 da netê qebûlkirin.',

# Scary transclusion
'scarytranscludefailed' => '[Anîna şablona $1 biserneket; biborîne]',

# Delete conflict
'deletedwhileediting' => 'Hîşyar: Piştî te guherandinê xwe dest pê kir ev rûpela hate jêbirin!',
'confirmrecreate'     => "Bikarhêner [[User:$1|$1]] ([[User talk:$1|guftûgo]]) vê rûpelê jêbir, piştî te destpêkir bi guherandinê. Sedemê jêbirinê ev bû:
: ''$2''
Xêra xwe zanibe ku tu bi rastî dixwazê vê rûpelê dîsa çêkê",

# action=purge
'confirm_purge'        => 'Bîra vê rûpelê jêbîbe ?

$1',
'confirm_purge_button' => 'Temam',

# Auto-summaries
'autosumm-blank'   => 'Rûpel hate vala kirin',
'autosumm-replace' => "'$1' ket şûna rûpelê.",
'autoredircomment' => 'Redirect berve [[$1]]',
'autosumm-new'     => 'Rûpela nû: $1',

# Live preview
'livepreview-loading' => 'Tê…',
'livepreview-ready'   => 'Tê… Çêbû!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Xeyrandin yê piştî $1 sanîyan hatine çêkirin belkî netên wêşendan.',
'lag-warn-high'   => 'Ji bo westinê sistêmê ew xeyrandin, yê piştî $1 sanîyan hatine çêkirin netên wêşendan.',

# Watchlist editor
'watchlistedit-noitems'       => 'Di lîsteya te ya şopandinê gotar tune ne.',
'watchlistedit-normal-title'  => 'Lîsteya xwe ya şopandinê biguherîne',
'watchlistedit-normal-submit' => 'Gotaran jêbibe',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 gotar hate|$1 gotaran hatin}} jêbirin ji lîsteya te yê şopandinê:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 gotar hate|$1 gotar hatin}} jêbirin:',

);
