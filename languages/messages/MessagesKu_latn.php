<?php
/** Kurdish (Latin script) (‪Kurdî (latînî)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Asoxor
 * @author Bangin
 * @author Erdal Ronahi
 * @author Ferhengvan
 * @author George Animal
 * @author Gomada
 * @author Kaganer
 * @author Krinkle
 * @author Liangent
 * @author The Evil IP address
 * @author Welathêja
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Taybet',
	NS_TALK             => 'Nîqaş',
	NS_USER             => 'Bikarhêner',
	NS_USER_TALK        => 'Bikarhêner_nîqaş',
	NS_PROJECT_TALK     => '$1_nîqaş',
	NS_FILE             => 'Wêne',
	NS_FILE_TALK        => 'Wêne_nîqaş',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
	NS_HELP             => 'Alîkarî',
	NS_HELP_TALK        => 'Alîkarî_nîqaş',
	NS_CATEGORY         => 'Kategorî',
	NS_CATEGORY_TALK    => 'Kategorî_nîqaş',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Xetekê di bin girêdanê de çêke:',
'tog-highlightbroken'         => 'Girêdanên gotarên vala biguherîne',
'tog-justify'                 => 'Gotar bi forma "block"',
'tog-hideminor'               => 'Guherandinên biçûk ji listêya guherandinên dawî veşêre',
'tog-hidepatrolled'           => 'Guherandinên hatine kontrolkirin ji nav guherandinên dawî veşêre',
'tog-newpageshidepatrolled'   => 'Rûpelên hatine kontrolkirin ji lîsteya rûpelên nû veşêre',
'tog-extendwatchlist'         => 'Lîsteya şopandinê berfireh bike ji bo dîtina hemû guherandinan, ne tenê yên nû',
'tog-usenewrc'                => 'Weşandina zêdetir (JavaScript pêwîst e)',
'tog-numberheadings'          => 'Sernavan otomatîk bihejmêre',
'tog-showtoolbar'             => 'Tiştên guherandinê bibîne (JavaScript bibîne)',
'tog-editondblclick'          => 'Rûpelan bi du klîkan biguherîne (Java Script gireke)',
'tog-editsection'             => 'Girêdanan ji bo guherandina beşan biweşîne',
'tog-editsectiononrightclick' => 'Beşekê bi rast-klîkekê biguherîne (JavaScript gireke)',
'tog-showtoc'                 => 'Tabloya naverokê nîşan bide (ji bo rûpelan zêdetirî sê sernavan)',
'tog-rememberpassword'        => 'Qeyda min di vê komputerê de biparêze (herî zêde ji bo $1 {{PLURAL:$1|rojekê|rojan}})',
'tog-watchcreations'          => 'Rûpelên min çêkirin, têxe nav lîsteya min a şopandinê',
'tog-watchdefault'            => 'Rûpelên min guhertin, têxe nav lîsteya min a şopandinê',
'tog-watchmoves'              => 'Rûpelên min navê wan guhertin, têxe nav lîsteya min a şopandinê',
'tog-watchdeletion'           => 'Rûpelên min jêbirin, têxe nav lîsteya min a şopandinê',
'tog-minordefault'            => 'Her guhertinekê weke guhertineke biçûk nîşan bide',
'tog-previewontop'            => 'Pêşdîtina gotarê li jorî cihê guherandinê nîşan bide',
'tog-previewonfirst'          => 'Li cem guherandinê hertim yekemîn pêşdîtinê nîşan bide',
'tog-nocache'                 => 'Vegirtina rûpelan bisekinîne',
'tog-enotifwatchlistpages'    => 'Heke rûpeleke ez dişopînim hate guhertin ji min re E-nameyekê bişîne',
'tog-enotifusertalkpages'     => 'Dema rûpela min a Guftûgoyê hate guhertin e-nameyekê ji min re bişîne',
'tog-enotifminoredits'        => 'Ji bo guhertinên biçûk jî E-nameyekê ji min re bişîne',
'tog-enotifrevealaddr'        => 'Navnîşana e-nameya min di agahdariyên e-nameyan de nîşan bide',
'tog-shownumberswatching'     => 'Nîşan bide, çiqas bikarhêner dişopînin',
'tog-oldsig'                  => 'Pêşdîtina îmzeya heyî',
'tog-fancysig'                => 'Di îmzeyê de girêdana otomatîk a bikarhêner betal bike',
'tog-externaleditor'          => 'Edîtorekî derve bike "standard" (ji yên bi ezmûn re, tercîhên taybet di komputerê de hewce ne)',
'tog-externaldiff'            => 'Birnemijekî derve biguherîne "standard" (ji yên bi ezmûn re, tercîhên taybet di komputerê de hewce ne)',
'tog-showjumplinks'           => 'Girêdanên "Here-berve" qebûlbike',
'tog-uselivepreview'          => 'Pêşdîtinê "zindî" bikarbîne (JavaScript pêwîst e) (ceribandinî)',
'tog-forceeditsummary'        => 'Hinga kurteyeke vala hate tomarkirin min agahdar bike',
'tog-watchlisthideown'        => 'Guherandinên min ji lîsteya şopandinê veşêre',
'tog-watchlisthidebots'       => "Guherandinên bot'an ji lîsteya şopandinê veşêre",
'tog-watchlisthideminor'      => 'Guhertinên biçûk ji lîsteya şopandinê veşêre',
'tog-watchlisthideliu'        => 'Guherandinên bikarhênerên qeydkirî ji lîsteya şopandinê veşêre',
'tog-watchlisthideanons'      => 'Guherandinên bikarhênerên neqeydkirî ji lîsteya şopandinê veşêre',
'tog-nolangconversion'        => 'Veguhêztina guhertoyên ziman bisekinîne',
'tog-ccmeonemails'            => 'Kopiyên e-nameyên min ji bikarhênerên din re şandî, ji min re bişîne.',
'tog-diffonly'                => 'Li cem guhertinan, naveroka rûpelê nîşan nede',
'tog-showhiddencats'          => 'Kategoriyên veşartî bibîne',
'tog-norollbackdiff'          => 'Ciyawaziyê piştî şûndekirinê veşêre',

'underline-always'  => 'Hertim',
'underline-never'   => 'Qet',
'underline-default' => 'Tercîhên lêgerokê',

# Font style option in Special:Preferences
'editfont-default' => 'Tercîhên lêgerokê',

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
'thu'           => 'Pşem',
'fri'           => 'În',
'sat'           => 'Şem',
'january'       => 'rêbendan',
'february'      => 'reşemî',
'march'         => 'adar',
'april'         => 'avrêl',
'may_long'      => 'gulan',
'june'          => 'pûşper',
'july'          => 'tîrmeh',
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
'jun'           => 'pûş',
'jul'           => 'tîr',
'aug'           => 'teb',
'sep'           => 'rez',
'oct'           => 'kew',
'nov'           => 'ser',
'dec'           => 'ber',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorî|Kategorî}}',
'category_header'                => 'Gotarên di kategoriya "$1" de',
'subcategories'                  => 'Binkategorî',
'category-media-header'          => 'Medya di kategoriya "$1" de',
'category-empty'                 => "''Di vê kategoriyê de niha gotarên medya nîn in.''",
'hidden-categories'              => '{{PLURAL:$1|Kategoriya veşartî|Kategoriyên veşartî}}',
'hidden-category-category'       => 'Kategoriyên veşartî',
'category-subcat-count'          => '{{PLURAL:$2|Di vê kategoriyê de tenê ev binkategorî heye:|Di vê kategoriyê de {{PLURAL:$2|binkategoriyek heye|$2 binkategorî hene}}. Jêr {{PLURAL:$1|binkategoriyek tê|$1 binkategorî tên}} nîşandan.}}',
'category-subcat-count-limited'  => 'Di vê kategoriyê de ev {{PLURAL:$1|binkategorî heye|$1 binkategorî hene}}.',
'category-article-count'         => '{{PLURAL:$2|Di vê kategoriyê de tenê ev rûpel heye:|Di vê kategoriyê de {{PLURAL:$2|rûpelek heye|$2 rûpel hene}}. Jêr {{PLURAL:$1|rûpelek tê|$1 rûpel tên}} nîşandan.}}',
'category-article-count-limited' => 'Ev {{PLURAL:$1|rûpela|$1 rûpelên}} jêr di vê kategoriyê de {{PLURAL:$1|ye|ne}}.',
'category-file-count'            => '{{PLURAL:$2|Di vê kategoriyê de tenê ev dane heye:|Di vê kategoriyê de {{PLURAL:$2|daneyek heye|$2 dane hene}}. Jêr {{PLURAL:$1|daneyek tê|$1 dane tên}} nîşandan.}}',
'category-file-count-limited'    => 'Ev {{PLURAL:$1|daneya|$1 daneyên}} jêr di vê kategoriyê de ne.',
'listingcontinuesabbrev'         => 'dewam',

'mainpagetext'      => "'''MediaWiki serketî hate çêkirin.'''",
'mainpagedocfooter' => 'Alîkarî ji bo bikaranîn û guherandin yê datayê Wîkî tu di bin [http://meta.wikimedia.org/wiki/Help:Contents pirtûka alîkarîyê ji bikarhêneran] da dikarê bibînê.

== Alîkarî ji bo destpêkê ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lîsteya varîyablên konfîgûrasîyonê]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lîsteya e-nameyên versyonên nuh yê MediaWiki]',

'about'         => 'Der barê',
'article'       => 'Gotar',
'newwindow'     => '(di rûpeleke din de wê were nîşandan)',
'cancel'        => 'Betalkirin',
'moredotdotdot' => 'Bêhtir...',
'mypage'        => 'Rûpela min',
'mytalk'        => 'Rûpela gotûbêja min',
'anontalk'      => 'Guftûgo ji bo vê IPê',
'navigation'    => 'Navîgasyon',
'and'           => '&#32;û',

# Cologne Blue skin
'qbfind'         => 'Bibîne',
'qbbrowse'       => 'Bigere',
'qbedit'         => 'Biguherîne',
'qbpageoptions'  => 'Ev rûpel',
'qbpageinfo'     => 'Naverok',
'qbmyoptions'    => 'Rûpelên min',
'qbspecialpages' => 'Rûpelên taybet',
'faq'            => 'PGP',
'faqpage'        => 'Project:PGP',

# Vector skin
'vector-action-addsection' => 'Mijarekê lê zêde bike',
'vector-action-delete'     => 'Jê bibe',
'vector-action-move'       => 'Nav biguherîne',
'vector-action-protect'    => 'Biparêze',
'vector-action-undelete'   => 'Jê nebe',
'vector-action-unprotect'  => 'Parastinê rake',
'vector-view-create'       => 'Çêke',
'vector-view-edit'         => 'Biguherîne',
'vector-view-history'      => 'Dîrokê bibîne',
'vector-view-view'         => 'Bixwîne',
'vector-view-viewsource'   => 'Çavkaniyan bibîne',
'actions'                  => 'Çalakî',
'namespaces'               => 'Valahiya nav',
'variants'                 => 'Variyant',

'errorpagetitle'    => 'Çewtî',
'returnto'          => 'Bizîvire $1.',
'tagline'           => 'Ji {{SITENAME}}',
'help'              => 'Alîkarî',
'search'            => 'Lêgerîn',
'searchbutton'      => 'Lêgerîn',
'go'                => 'Here',
'searcharticle'     => 'Here',
'history'           => 'Dîroka rûpelê',
'history_short'     => 'Dîrok',
'updatedmarker'     => 'ji serdana min a dawî ve hate rojanekirin',
'info_short'        => 'Zanyarî',
'printableversion'  => 'Versiyon ji bo çapkirinê',
'permalink'         => 'Girêdana daîmî',
'print'             => 'Çap',
'edit'              => 'Biguherîne',
'create'            => 'Biafirîne',
'editthispage'      => 'Vê rûpelê biguherîne',
'create-this-page'  => 'Vê rûpelê çêke',
'delete'            => 'Jê bibe',
'deletethispage'    => 'Vê rûpelê jê bibe',
'undelete_short'    => 'Dîsa {{PLURAL:$1|guherandinekî|$1 guherandinan}} çêke',
'protect'           => 'Biparêze',
'protect_change'    => 'guherandin',
'protectthispage'   => 'Vê rûpelê biparêze',
'unprotect'         => 'Parastinê rake',
'unprotectthispage' => 'Parastina vê rûpelê rake',
'newpage'           => 'Rûpela nû',
'talkpage'          => 'Vê rûpelê guftûgo bike',
'talkpagelinktext'  => 'Nîqaş',
'specialpage'       => 'Rûpela taybet',
'personaltools'     => 'Amûrên kesane',
'postcomment'       => 'Beşeke nû',
'articlepage'       => 'Li rûpela naverokê binêre',
'talk'              => 'Guftûgo',
'views'             => 'Dîtin',
'toolbox'           => 'Qutiya amûran',
'userpage'          => 'Li rûpela vê/vî bikarhênerê/î binêre',
'projectpage'       => 'Li rûpela projeyê binêre',
'imagepage'         => 'Rûpela dosyeyan bibîne',
'mediawikipage'     => 'Rûpela peyamê bibîne',
'templatepage'      => 'Rûpela şablonê bibîne',
'viewhelppage'      => 'Rûpela alîkariyê bibîne',
'categorypage'      => 'Li rûpela kategoriyê binêre',
'viewtalkpage'      => 'Li guftûgoyê binêre',
'otherlanguages'    => 'Zimanên din',
'redirectedfrom'    => '(ji $1 hate beralîkirin)',
'redirectpagesub'   => 'Rûpelê beralî bike',
'lastmodifiedat'    => 'Ev rûpel cara dawî di $2, $1 de hate guherandin.',
'viewcount'         => 'Ev rûpel {{PLURAL:$1|carekê|caran}} tê xwestin.',
'protectedpage'     => 'Rûpela parastî',
'jumpto'            => 'Here cem:',
'jumptonavigation'  => 'navîgasyon',
'jumptosearch'      => 'lêbigere',
'view-pool-error'   => 'Bibore, server niha zêde barkirî ne. Gelek bikarhêner niha hewl didin ku vê rûpelê bibînin. Ji kerema xwe kêlîkekê bisekine, berî ku tu dîsa hewl bidî rûpelê bibînî.',
'pool-errorunknown' => 'Çewtiyeke nenas',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Der barê {{SITENAME}} de',
'aboutpage'            => 'Project:Der barê',
'copyright'            => 'Naverok di $1 de derbasdar e.',
'copyrightpage'        => '{{ns:project}}:Mafên nivîsanê',
'currentevents'        => 'Bûyerên rojane',
'currentevents-url'    => 'Project:Bûyerên rojane',
'disclaimers'          => 'Ferexetname',
'disclaimerpage'       => 'Project:Ferexetname',
'edithelp'             => 'Alîkariya guherandinê',
'edithelppage'         => 'Help:Çawa rûpelekê biguherînim',
'helppage'             => 'Help:Alîkarî',
'mainpage'             => 'Destpêk',
'mainpage-description' => 'Destpêk',
'policy-url'           => 'Project:Rêgez',
'portal'               => 'Portala komê',
'portal-url'           => 'Project:Portala komê',
'privacy'              => 'Parastina daneyan',
'privacypage'          => 'Project:Parastina daneyan',

'badaccess'        => 'Çewtiya destûrê',
'badaccess-group0' => 'Tu nikarî vî tiştî bikî.',
'badaccess-groups' => 'Ev tişta tu dixwazî bikî tenê ji bikarhênerên {{PLURAL:$2|van koman|vê komê}} re {{PLURAL:$2|ne|ye}}: $1.',

'versionrequired'     => 'Versiyona $1 a MediaWiki pêwîst e',
'versionrequiredtext' => 'Versiyona $1 a MediaWiki ji bo bikaranîna vê rûpelê pêwîst e. Li [[Special:Versiyon|rûpela versiyonê]] binêre.',

'ok'                      => 'Temam',
'retrievedfrom'           => 'Ji "$1" hatiye standin.',
'youhavenewmessages'      => '$1 yên te hene ($2).',
'newmessageslink'         => 'Peyamên nû',
'newmessagesdifflink'     => 'ciyawazî ji guhertoya berê',
'youhavenewmessagesmulti' => 'Peyamên nû li $1 ji te re hene.',
'editsection'             => 'biguherîne',
'editold'                 => 'biguherîne',
'viewsourceold'           => 'çavkaniyan bibîne',
'editlink'                => 'sererastkirin',
'viewsourcelink'          => 'çavkaniyan bibîne',
'editsectionhint'         => 'Beşê biguherîne: $1',
'toc'                     => 'Naverok',
'showtoc'                 => 'nîşan bide',
'hidetoc'                 => 'veşêre',
'thisisdeleted'           => 'Li $1 binêre an jî serast bike?',
'viewdeleted'             => 'Li $1 binêre?',
'restorelink'             => '{{PLURAL:$1|guherandineke|$1 guherandinên}} jêbirî',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Agahiya nesererast.',
'feed-unavailable'        => 'Agahiya sazûmankirinê nîne.',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (rûpel hê tune)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Rûpel',
'nstab-user'      => 'Bikarhêner',
'nstab-media'     => 'Medya',
'nstab-special'   => 'Rûpela taybet',
'nstab-project'   => 'Rûpela projeyê',
'nstab-image'     => 'Wêne',
'nstab-mediawiki' => 'Peyam',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Alîkarî',
'nstab-category'  => 'Kategorî',

# Main script and global functions
'nosuchaction'      => 'Çalakiyeke bi vî rengî nîne',
'nosuchactiontext'  => "Ew tişta di wê URL'ê de tê gotin ji MediaWiki netê tê çêkirin.",
'nosuchspecialpage' => 'Rûpeleke bi vî rengî nîne',
'nospecialpagetext' => '<strong>Rûpela taybet a te xwestî tune ye.</strong>

Hemû rûpelên taybet dikarin di [[Special:SpecialPages|lîsteya rûpelên taybet]] de werin dîtin.',

# General errors
'error'                => 'Çewtî',
'databaseerror'        => 'Çewtiya bingeha daneyan',
'dberrortext'          => 'Li cem dîtina bingeha daneyan <blockquote><tt>$1</tt></blockquote>
ji fonksiyonê "<tt>$2</tt>" ye.
MySQL ev şaşîtî hate dîtin: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Li cem dîtina bingeha daneyan "$1 ji fonksiyonê "<tt>$2</tt>" ye.
MySQL ev şaşîtî hate dîtin: "<tt>$3: $4</tt>".',
'laggedslavemode'      => "'''Zanibe:''' Dibe ku di vê rûpelê de rojanekirinên dawî nîn bin.",
'readonly'             => 'Bingeha daneyan hatiye girtin',
'enterlockreason'      => 'Sedemeke bestinê binivîse, herwiha demeke texmînkirî ji bo vebûna bestinê binivîse!',
'readonlytext'         => 'Bingeha daneyên {{SITENAME}} ji bo guherandinan û gotarên nû hatiye girtin.

Sedema girtinê ev e: $1',
'missingarticle-rev'   => '(versiyon#: $1)',
'missingarticle-diff'  => '(Cudahî: $1, $2)',
'readonly_lag'         => 'Bingeha daneyan otomatîk hate girtin, ji bo server ên bingeha daneyên girêdayî karibin xwe bikêrhatî bikin.',
'internalerror'        => 'Çewtiyeke navxweyî',
'internalerror_info'   => 'Çewtiya navxweyî: $1',
'fileappenderror'      => '"$1" li "$2" nehate zêdekirin.',
'filecopyerror'        => 'Daneya „$1“ ji bo „$2“ naye kopîkirin.',
'filerenameerror'      => 'Navê pelê "$1" nebû "$2".',
'filedeleteerror'      => '"$1" jê nehate birîn.',
'directorycreateerror' => 'Rêbera "$1" nehate çêkirin.',
'filenotfound'         => 'Pela bi navê "$1" nehate dîtin.',
'fileexistserror'      => '"$1" nehate çêkirin , ji ber ku ev pel heye.',
'unexpected'           => 'Tiştekî nedihate hêvîkirin: "$1"="$2".',
'formerror'            => 'Çewtî: Ew nivîs pêk nehat.',
'badarticleerror'      => 'Ev çalakî di vê rûpelê de nabe.',
'cannotdelete'         => 'Ev rûpel nikare were jêbirin. Dibe ku kesekî din ev rûpel jêbiribe.',
'badtitle'             => 'Sernivîsa nebaş',
'badtitletext'         => 'Sernavê rûpelê yê xwestî ne derbasdar, vala an jî ne xwediyê girêdaneke rast e.
Dibe ku di sernavê de karakterên nayên bikaranîn hatibin nivîsandin.',
'perfcached'           => 'Ev dane hatine veşartin û belkî ne rojane bin.',
'perfcachedts'         => 'Ev dane hatiye veşartin û cara paşîn $1 hatiye rojanekirin.',
'querypage-no-updates' => 'Fonksiyona rojanekirinê ya vê rûpelê hatiye sekinandin. Daneyên vir nayên rojanekirin.',
'wrong_wfQuery_params' => 'Parametreyên şaş ji bo wfQuery()<br />
Fonksiyon: $1<br />
Pirs: $2',
'viewsource'           => 'Çavkaniyê bibîne',
'viewsourcefor'        => 'ji $1 re',
'actionthrottled'      => 'Hejmara guherandinên hatine hesibandin',
'actionthrottledtext'  => 'Te ev tişt di demeke gelekî kin de kir. Ji kerema xwe çend xulekan bisekine û carekî din biceribîne.',
'protectedpagetext'    => 'Ev rûpel ji bo nenivîsandinê hatiye parastin.',
'viewsourcetext'       => 'Tu dikarî li çavkaniya vê rûpelê binêrî û wê kopî bikî:',
'protectedinterface'   => "Di vê rûpelê da nivîsandin ji bo interface'î zimanan yê vê software'ê ye. Ew tê parstin ji bo vandalîzm li vê derê çênebe.",
'editinginterface'     => "'''Hişyarî:''' Tu rûpeleke ku di Wîkîpediya de ji bo sîstemê girîng e diguherînî. Guherandinên di vê rûpelê de wê ji aliyê hemû bikarhêneran ve werin dîtin. Ji bo wergerê ji kerema xwe di [http://translatewiki.net/wiki/Main_Page?setlang=ku translatewiki.net] de bixebite, projeya MediaWiki.",
'sqlhidden'            => '(Jêpirskirina SQL hatiye veşartin)',
'cascadeprotected'     => '<strong>Ev rûpel hatiye parastin ji ber guherandinê, ji ber ku ev rûpela di {{PLURAL:$1|vê rûpelê|van rûpelan da}} tê bikaranîn:
$2

</strong>',
'namespaceprotected'   => "Destûra te ji bo guhertina vê rûpelê '''$1''' di valahiya nav de nîne.",
'customcssjsprotected' => 'Qebûlkirinên te tune ne, tu nikanê vê rûpelê biguherînê, ji ber ku di vir da tercihên bikarhênerekî din hene.',
'ns-specialprotected'  => 'Rûpelên taybet {{ns:special}} nikarin werin guherandin.',
'titleprotected'       => "Rûpelek bi vî navî nikare were çêkirin. Ev astengkirin ji [[User:$1|$1]] bi sedema ''$2'' hate çêkirin.",

# Virus scanner
'virus-unknownscanner' => 'Antîvîrusa nenas:',

# Login and logout pages
'logouttext'                 => "'''Tu niha derketî (logged out).'''

Tu dikarî {{SITENAME}} niha weke bikarhênerekî nediyarkirî bikarbînî, yan jî tu dikarî dîsa bi vî navê xwe yan navekî din wek bikarhêner [[Special:UserLogin|dîsa têkevî]].
Bila di bîra te de be ku gengaz e hin rûpel mîna ku tu hîn bi navê xwe qeyd kiriyî werin nîşandan, heta ku tu nîşanên çavlêgerandina (browser) xwe jênebî.",
'welcomecreation'            => '== Tu bi xêr hatî, $1! ==

Hesabê te hate afirandin. Tu dikarî niha [[Special:Preferences|tercîhên xwe di {{SITENAME}}]] de biguherînî.',
'yourname'                   => 'Navê bikarhêner:',
'yourpassword'               => 'Şîfre:',
'yourpasswordagain'          => 'Şîfreyê dîsa binivîse:',
'remembermypassword'         => 'Şifreya min di her têketina min de bîne bîra xwe (herî zêde $1 {{PLURAL:$1|rojekê|rojan}})',
'yourdomainname'             => 'Domaînê te',
'externaldberror'            => 'Çewtiyeke bingeha daneyan heye, an jî destûra te ya rojanekirina hesabê xweyê navxweyî nîne.',
'login'                      => 'Têkeve',
'nav-login-createaccount'    => 'Têkeve / hesabekî nû çêke',
'loginprompt'                => "<b>Eger tu xwe nû qeyd bikî, nav û şîfreya xwe hilbijêre.</b> Ji bo xwe qeyd kirinê di {{SITENAME}} de divê ku ''cookies'' gengaz be.",
'userlogin'                  => 'Têkeve an hesabeke nû çêke',
'userloginnocreate'          => 'Têkeve',
'logout'                     => 'Derkeve',
'userlogout'                 => 'Derkeve',
'notloggedin'                => 'Xwe qeyd nekir (not logged in)',
'nologin'                    => 'Hesabê te nîne? $1.',
'nologinlink'                => 'Bibe endam',
'createaccount'              => 'Hesabê nû çêke',
'gotaccount'                 => "Hesabê te heye? '''$1'''.",
'gotaccountlink'             => 'Têkeve',
'createaccountmail'          => 'bi e-name',
'createaccountreason'        => 'Sedem:',
'badretype'                  => 'Her du şîfreyên ku te nivîsîn li hevdu nayên.',
'userexists'                 => 'Ev navî bikarhênerî berê tê bikaranîn. Xêra xwe navekî din bibe.',
'loginerror'                 => 'Çewtiya têketinê',
'nocookiesnew'               => "Hesabê bikarhêner hatibû çêkirin, lê te xwe qeyd nekiriye. {{SITENAME}} ji bo qeydkirina bikarhêneran cookie'yan bikartîne. Te bikaranîna cookie'yan girtiye. Xêra xwe cookie'yan qebûl bike, piştre bi navê bikarhêner û şîfreya xwe têkeve.",
'nocookieslogin'             => 'Ji bo qeydkirina bikarhêneran {{SITENAME}} "cookies" bikartîne. Te fonksîyona "cookies" girtîye. Xêra xwe kerema xwe "cookies" gengaz bike û careke din biceribîne.',
'noname'                     => 'Navê ku te nivîsand derbas nabe.',
'loginsuccesstitle'          => 'Têketin serkeftî!',
'loginsuccess'               => 'Tu niha di {{SITENAME}} de qeydkirî yî wek "$1".',
'nosuchuser'                 => 'Bikarhênera/ê bi navê "$1" tune. Navê rast binivîse an bi vê formê <b>hesabeke nû çêke</b>. (Ji bo hevalên nû "Têkeve" çênabe!)',
'nosuchusershort'            => 'Li vê derê ne bikarhênerek bi navî "<nowiki>$1</nowiki>" heye. Li nivîsandinê xwe seke.',
'nouserspecified'            => 'Divê tu navekî ji bo bikarhêneriyê hilbijêrî.',
'login-userblocked'          => 'Rê li ber vî/vê bikarhênerî/ê hatiye girtin. Destûr bo têketinê nîne.',
'wrongpassword'              => 'Şifreya ku te nivîsand şaşe. Ji kerema xwe careke din biceribîne.',
'wrongpasswordempty'         => 'Cihê şîfreya te vala ye. Carekê din binivisîne.',
'passwordtooshort'           => 'Şîfreya te netê qebûlkirin: Şîfreya te gereke bi kêmani {{PLURAL:$1|nîşaneka|$1 nîşanên}} xwe hebe û ne wek navî tê wek bikarhêner be.',
'password-name-match'        => 'Divê şîfreya te ji navê te yê bikaranînê cuda be.',
'password-login-forbidden'   => 'Bikaranîna vî navî û vê şîfreyê hatiye qedexekirin.',
'mailmypassword'             => 'Şîfreyeke nû bi e-mail ji min re bişîne',
'passwordremindertitle'      => 'Şîfreyakekî nuh ji hesabekî {{SITENAME}} ra',
'passwordremindertext'       => 'Kesek (têbê tu, bi IP\'ya $1) xwast ku şîfreyekî nuh ji {{SITENAME}} ($4) ji te ra were şandin. Şîfreya nuh ji bikarhêner "$2" niha "$3" e. Tu dikarî niha têkevê û şîfreya xwe biguherînê.

Eger kesekî din vê xastinê ji te ra xast ya şîfreya kevin dîsa hate bîrê te, tu dikarê guh nedê vê peyamê û tu dikarê bi şîfreya xwe yê kevin hên karbikê.',
'noemail'                    => 'Navnîşana bikarhênerê/î "$1" nehat tomar kirine.',
'noemailcreate'              => 'Divê tu e-nameyeke derbasdar binivîsî',
'passwordsent'               => 'Ji navnîşana e-mail ku ji bo "$1" hat tomarkirin şîfreyekê nû hat şandin. Vê bistîne û dîsa têkeve.',
'blocked-mailpassword'       => "IP'ya te yê ji te niha tê bikaranin ji bo guherandinê ra hatîye astengkirin. Ji bo tiştên şaş çênebin, xastinê te ji bo şifreyeka nuh jî hatîye qedexekirin.",
'eauthentsent'               => 'E-nameyeka naskirinê ji adresa nivîsî ra hate şandin. Berî e-name ji bikarhênerên din bi vê rêkê dikaribim bi te gên, ew adresa û rastbûna xwe gireke werin naskirin. Xêra xwe e-nameyê naskirinê bixûne!',
'throttled-mailpassword'     => 'Berî {{PLURAL:$1|saetekê|$1 saetan}} şîfreyekî nuh hate xastin. Ji bo şaşbûn bi vê fonksyonê çênebin, bes her {{PLURAL:$1|saetekê|$1 saetan}} şîfreyekî nuh dikare were xastin.',
'mailerror'                  => 'Şaşbûnek li cem şandina e-nameyekê: $1',
'acct_creation_throttle_hit' => 'Biborîne! Te hesab $1 vekirine. Tu êdî nikarî hesabên din vekî.',
'emailauthenticated'         => 'Adresa e-nameya te hate naskirin: $1.',
'emailnotauthenticated'      => 'Adresa e-nameyan yê te hên nehatîye naskirin. Fonksyonên e-nameyan piştî naskirina te dikarin ji te werin kirin.',
'noemailprefs'               => "'''Te hên adresa e-nameyan nenivîsandîye''', fonksyonên e-nameyan hên ji te ra ne tên qebûlkirin.",
'emailconfirmlink'           => 'E-Mail adresê xwe nasbike',
'invalidemailaddress'        => 'Adresa e-nameyan yê te ne tê qebûlkirin, ji ber ku formata xwe qedexe ye (belkî nîşanên qedexe). Xêra xwe adreseka serrast binivisîne ya vê derê vala bêle.',
'accountcreated'             => 'Hesab hate çêkirin',
'accountcreatedtext'         => 'Hesabê bikarhêneran ji $1 ra hate çêkirin.',
'createaccount-title'        => 'Çêkirina hesabekî ji {{SITENAME}}',
'createaccount-text'         => 'Kesek ji te ra account\'ekî bikarhêneran "$2" li {{SITENAME}} ($4) çêkir. Şîfreya otomatîk ji "$2" ra "$3" ye.
Niha ê baş be eger tu xwe qeyd bikê û tu şîfreya xwe biguherînê.

Eger account\'a bikarhêneran şaşî hate çêkirin, guhdare vê peyamê meke.',
'usernamehasherror'          => 'Divê karakterên xerab ji bo navê bikarhêner neyên bikaranîn',
'loginlanguagelabel'         => 'Ziman: $1',

# Password reset dialog
'resetpass'                 => 'Şîfreyê biguherîne',
'resetpass_announce'        => 'Te xwe bi şîfreyekê qeydkir, yê bi e-nameyekê ji te ra hate şandin. Ji bo xelaskirinê qeydkirinê, tu niha gireke şîfreyeka nuh binivisînê.',
'resetpass_text'            => '<!-- Nivîsê li vir binivisîne -->',
'resetpass_header'          => 'Şîfreya hesabê xwe biguherîne',
'oldpassword'               => 'Şîfreya kevn',
'newpassword'               => 'Şîfreya nû',
'retypenew'                 => 'Şîfreya nû careke din binîvîse',
'resetpass_submit'          => 'Şîfreyê pêkbîne û têkeve',
'resetpass_success'         => 'Şîfreya te hate guherandin! Niha tu tê qeydkirin...',
'resetpass_forbidden'       => 'Şîfre nikarin werin guhertin',
'resetpass-submit-loggedin' => 'Şîfre biguherîne',
'resetpass-submit-cancel'   => 'Betalkirin',
'resetpass-temp-password'   => 'Şîfreya niha:',

# Edit page toolbar
'bold_sample'     => 'Nivîsa stûr',
'bold_tip'        => 'Nivîsa stûr',
'italic_sample'   => 'Nivîsa xwehr (îtalîk)',
'italic_tip'      => 'Nivîsa xwehr (îtalîk)',
'link_sample'     => 'Navê lînkê',
'link_tip'        => 'Girêdana navxweyî',
'extlink_sample'  => 'http://www.example.com navê lînkê',
'extlink_tip'     => 'Girêdana derve (http:// di destpêkê de ji bîr neke)',
'headline_sample' => 'Nivîsara sernameyê',
'headline_tip'    => 'Sername asta 2',
'math_sample'     => 'Kurteristê matêmatîk li vir binivisîne',
'math_tip'        => 'Kurteristê matêmatîk (LaTeX)',
'nowiki_sample'   => 'Nivîs ku nebe formatkirin',
'nowiki_tip'      => 'Guh nede formatkirina wiki',
'image_sample'    => 'Mînak.jpg',
'image_tip'       => 'Wêne li hundirê gotarê',
'media_sample'    => 'Mînak.ogg',
'media_tip'       => 'Girêdana pelê',
'sig_tip'         => 'Îmze û demxeya wext ya te',
'hr_tip'          => 'Rastexêza berwarî (kêm bi kar bîne)',

# Edit pages
'summary'                          => 'Kurte û çavkanî (Te çi kir?):',
'subject'                          => 'Mijar/sernivîs:',
'minoredit'                        => 'Ev guhertineke biçûk e',
'watchthis'                        => 'Vê gotarê bişopîne',
'savearticle'                      => 'Rûpelê tomar bike',
'preview'                          => 'Pêşdîtin',
'showpreview'                      => 'Pêşdîtin',
'showlivepreview'                  => 'Pêşdîtina zindî',
'showdiff'                         => 'Guherandinê nîşan bide',
'anoneditwarning'                  => "'''Hişyarî:''' Tu netêketî yî! Navnîşana IP'ya te wê di dîroka guherandina vê rûpelê de bê tomarkirin.",
'missingsummary'                   => "<span style=\"color:#990000;\">'''Zanibe:'''</span> Te nivîsekî kurt ji bo guherandinê ra nenivîsand. Eger tu niha carekî din li Tomar xê, guherandinê te vê nivîsekî kurt yê were tomarkirin.",
'missingcommenttext'               => 'Ji kerema xwe kurteya naverokê li jêr binivisîne.',
'missingcommentheader'             => "<span style=\"color:#990000;\">'''Zanibe:'''</span> Te sernavek nenivîsandiye. Heke tu niha carekî din li ser ''tomar bike'' bitikînî, ev guherandina vê sernavê wê were tomarkirin.",
'summary-preview'                  => 'Pêşdîtina kurtenivîsê:',
'subject-preview'                  => 'Pêşdîtina sernivîsê:',
'blockedtitle'                     => 'Bikarhêner hate astengkirin',
'blockedtext'                      => "'''Navê te yê bikarhêneriyê an jî IP'ya te hate astengkirin.'''

Astengkirin ji aliyê $1 ve pêkhat. Sedema astengkirina te ev e: ''$2''.

* Destpêka astengkirinê: $8
* Xelasbûna astengkirinê: $6
* Astengkirin ji van re: $7

Tu dikarî bi $1  re an jî [[{{MediaWiki:Grouppage-sysop}}|koordînatorên]] din re ji bo astengkirinê bikevî têkiliyê. Tu nikarî 'Ji vê/vî bikarhênerê/î re e-name bişîne' bikarbînî heta  di [[Special:Preferences|tercihên xwe]] de navnîşana e-nameyeke derbasdar bikarbînî û tu ji bo bikaranîna vê fonksiyonê nehatî astengkirin.

IP'ya te ya niha $3 ye, û ID'ya astengkirina te #$5 e. Ji kerema xwe yek ji van hejmaran têxe nav peyama xwe.",
'autoblockedtext'                  => "Navnîşana IP ya te otomatîk hate astengkirin, ji ber ku bikarhênerekî/e din wê bikartîne, yê niha ji $1 hate astengkirin.
Sedema astengkirinê ev e:

: ''$2''

*Destpêka astengkirinê: $8
*Dawiya astengkirinê: $6

Eger tu difikirî ku ev astengkirin ne sererast e, ji kerema xwe bi $1 re an jî yekî din ji [[{{MediaWiki:Grouppage-sysop}}|koordînatoran]] re bipeyive.

Zanibe ku tu nikarî e-nameya bişînî heta tu di [[Special:Preferences|tercihên xwe]] de navnîşana e-nameyan binivîsînî û tu ji bo bikaranîna vê nehatî astengkirin.

'''Heke tu bixwazî peyamekê bişînî, ji kerema xwe van tiştan têxe nav nameya xwe:'''

*Koordînator, yê te astengkir: $1
*Sedema astengkirinê: $2
*ID'ya astengkirinê: #$5",
'blockednoreason'                  => 'sedem nehatiye gotin',
'blockedoriginalsource'            => "Çavkaniya '''$1''' tê weşandin:",
'blockededitsource'                => "Nivîsarên '''guherandinên te''' di '''$1''' da tê wêşandan:",
'whitelistedittitle'               => 'Ji bo guherandinê vê gotarê tu gireke xwe qeydbikê.',
'whitelistedittext'                => 'Ji bo guherandina rûpelan, $1 pêwîst e.',
'confirmedittext'                  => 'Tu gireke adrêsa e-nameya xwe nasbikê berî tu rûpelan diguherînê. Xêra xwe adrêsa e-nameya ya xwe di [[Special:Preferences|tercihên xwe]] da binivisîne û nasbike.',
'nosuchsectiontitle'               => 'Beşekî wisa tune ye',
'nosuchsectiontext'                => 'Te dixast beşekê biguherînê yê tune ye.',
'loginreqtitle'                    => 'Têketin pêwîst e',
'loginreqlink'                     => 'têkeve',
'loginreqpagetext'                 => 'Divê tu ji bo dîtina rûpelên din $1.',
'accmailtitle'                     => 'Şîfre hate şandin.',
'accmailtext'                      => "Şîfreya '$1' hat şandin ji $2 re.",
'newarticle'                       => '(Nû)',
'newarticletext'                   => "Ev rûpel hîn tune. Eger tu bixwazî vê rûpelê çêkî, dest bi nivîsandinê bike û piştre qeyd bike. '''Wêrek be''', biceribîne!<br />
Ji bo alîkariyê binêre: [[{{MediaWiki:Helppage}}|Alîkarî]].<br />
Heke tu bi şaşîtî hatî, bizîvire rûpela berê.",
'anontalkpagetext'                 => "----''Ev rûpela guftûgo ye ji bo bikarhênerên nediyarkirî ku hîn hesabekî xwe çênekirine an jî bikarnaînin. Ji ber vê yekê divê em wan bi navnîşana IP ya hejmarî nîşan bikin. Navnîşaneke IP dikare ji aliyê gelek kesan ve were bikaranîn. Heger tu bikarhênerekî nediyarkirî bî û bawerdikî ku nirxandinên bê peywend di der barê te de hatine kirin ji kerema xwe re [[Special:UserLogin|hesabekî xwe veke an jî têkeve]] da ku tu xwe ji tevlîheviyên bi bikarhênerên din re biparêzî.''",
'noarticletext'                    => 'Ev rûpel niha vala ye, tu dikarî [[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an [{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].',
'noarticletext-nopermission'       => 'Ev rûpel niha vala ye, tu dikarî [[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an [{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].
Ev rûpel niha vala ye, tu dikarî [[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an [{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].',
'userpage-userdoesnotexist'        => 'Account\'î bikarhêneran "$1" nehatîye qeydkirin. Xêra xwe seke ku tu dixazê vê rûpelê çêkê/biguherînê.',
'userpage-userdoesnotexist-view'   => 'Hesabê bikarhêner  "$1"  nehatiye qeyd kirin.',
'blocked-notice-logextract'        => 'Ev bikarhêner hatiye astengkirin.
Astengkirina dawî bi referansa li jêr hatiye piştrastkirin:',
'clearyourcache'                   => "'''Zanibe:''' Piştî tomarkirinê, tu gireke cache'a browser'î xwe dîsa wînê ji bo dîtina guherandinan. '''Mozilla / Firefor /Safari:''' Kepsa ''Shift'' bigre û li ''Reload'' xe, ya ''Ctrl-Shift-R'' bikepsîne (''Cmd-Shift-R'' li cem Apple Mac); '''IE:''' Kepsa ''Ctrl'' bigre û li ''Reload'' xe, ya li ''Ctrl-F5''; '''Konqueror:''' bes li ''Reload'' xe ya li kepsa ''F5'' xe; bikarhênerên '''Opera''' girekin belkî cache'a xwe tevda di bin ''Tools → Preferences'' da valabikin.",
'usercssyoucanpreview'             => "'''Tîp:''' 'Pêşdîtin' bikarwîne ji bo tu bibînê çawa CSS'ê te yê nuh e berî tomarkirinê.",
'userjsyoucanpreview'              => "'''Tîp:''' 'Pêşdîtin' bikarwîne ji bo tu bibînê çawa JS'ê te yê nuh e berî tomarkirinê.",
'usercsspreview'                   => "'''Zanibe ku tu bes pêşdîtina CSS dibînî.'''
'''Ew hê nehatiye tomarkirin!'''",
'userjspreview'                    => "'''Zanibe ku tu bes JavaScript'a xwe dicerbînê, ew hên nehatîye tomarkirin!'''",
'updated'                          => '(Hate rojanekirin)',
'note'                             => "'''Nîşe:'''",
'previewnote'                      => "'''Ji bîr neke ku ev bi tenê çavdêriyek e, ev rûpel hîn nehatiye tomarkirin!'''",
'editing'                          => 'Biguherîne: "$1"',
'editingsection'                   => 'Tê guherandin: $1 (beş)',
'editingcomment'                   => '$1 (şîrove) tê guherandin.',
'editconflict'                     => 'Têkçûna guherandinan: $1',
'explainconflict'                  => "Ji dema te dest bi guherandinê kir heta niha kesekê/î din ev rûpel guherand.
Jor guhartoya heyî tê dîtîn.
Guherandinên te jêr tên nîşan dan.
Divê tû wan bikî yek.
Heke niha tomar bikî, '''bi tene''' nivîsara qutiya jor wê bê tomarkirin.",
'yourtext'                         => 'Nivîsara te',
'storedversion'                    => 'Versiyona qeydkirî',
'editingold'                       => "'''Hişyarî: Tu li ser guhertoyeke kevn a vê rûpelê dixebitî.
Heke tu qeyd bikî, hemû guhertinên piştî vê revîzyonê winda dibin.
'''",
'yourdiff'                         => 'Ciyawazî',
'copyrightwarning'                 => "Hişyarî: Hemû tevkariyên {{SITENAME}} di bin $2 de tên belav kirin (ji bo hûragahiyan li $1 binêre). Eger tu nexwazî ku nivîsên te bê dilrehmî bên guherandin û li gora keyfa herkesî bên belavkirin, li vir neweşîne.<br />
Tu soz didî ku te ev bi xwe nivîsand an jî ji çavkaniyekê azad an geliyane ''(public domain)'' girt.
'''BERHEMÊN MAFÊN WAN PARASTÎ (©) BÊ DESTÛR NEWEŞÎNE!'''",
'protectedpagewarning'             => "'''Hişyarî:  Ev rûpel tê parastin. Bi tenê bikarhênerên ku xwediyên mafên \"koordînatoriyê\" ne, dikarin vê rûpelê biguherînin.'''",
'templatesused'                    => 'Şablon di van rûpelan da tê bikaranîn',
'templatesusedpreview'             => 'Şablon yê di vê pêşdîtinê da tên bikaranîn:',
'templatesusedsection'             => 'Şablon yê di vê perçê da tên bikaranîn:',
'template-protected'               => '(tê parastin)',
'template-semiprotected'           => '(nîv-parastî)',
'hiddencategories'                 => 'Ev rûpel endamê{{PLURAL:$1|1 hidden category|$1 hidden categories}} ye:',
'sectioneditnotsupported-title'    => 'Guhertina beşê nayê piştgirîkirin',
'sectioneditnotsupported-text'     => 'Guhertina beşê di vê rûpelê de nayê piştgirîkirin.',
'permissionserrors'                => 'Çewtiyên destûrê',
'permissionserrorstext'            => 'Tu nikanê vê tiştî bikê, ji bo {{PLURAL:$1|vê sedemê|van sedeman}}:',
'permissionserrorstext-withaction' => 'Mafên te bo $2 tune ye ji bo {{PLURAL:$1|vê sedemê|van sedeman}}:',
'recreate-moveddeleted-warn'       => "'''Zanibe: Tu kê rûpelekê çêkê yê niha hate jêbirin!'''

Zanibe ku nuhçêkirinê vê rûpelê hêja ye ya na.
Înformasyon li ser jêbirinê vê rûpelê li vir e:",
'moveddeleted-notice'              => 'Ev rûpel hatiye jêbirin.
The deletion and move log for the page are provided below for reference.',
'log-fulllog'                      => 'Tevahiya wê bibîne',
'edit-conflict'                    => 'Têkçûna guherandinan.',
'edit-no-change'                   => 'Guherandina te nehate hesibandin, ji ber ku guherandinên nivîsê tune bûn.',
'edit-already-exists'              => 'Nikarî rûpeleka nuh çêke.
Ew berê heye.',

# "Undo" feature
'undo-success' => 'Ev guherandina kane were şondakirin. Xêra xwe ferqê piştî tomarkirinê bibîne û seke, ku tu ew versîyona dixwazê û tomarbike. Eger te şaşbûnekî kir, xêra xwe derkeve.',
'undo-failure' => 'Ev guherandina nikane were şondakirin ji ber ku guherandinên piştî wê.',
'undo-summary' => 'Guhertoya $1 ya [[Special:Contributions/$2|$2]] ([[User talk:$2|guftûgo]]) şûnde kir',

# Account creation failure
'cantcreateaccounttitle' => 'Hesab nikarîbû were çêkirin',
'cantcreateaccount-text' => "Çêkirinê hesaban ji vê IP'yê ('''$1''') hatiye qedexekirin ji [[User:$3|$3]].

Sedema qedexekirina $3 ev e: ''$2''",

# History pages
'viewpagelogs'           => 'Guhertinên vê rûpelê bibîne',
'nohistory'              => 'Dîroka guherandina vê rûpelê nîne.',
'currentrev'             => 'Guhertoya niha',
'currentrev-asof'        => 'Guhertoya herî kevn a $1',
'revisionasof'           => 'Guhertoya $1',
'revision-info'          => 'Guhertoya $1 ya ji aliyê $2 ve',
'previousrevision'       => '←Guhertoya kevintir',
'nextrevision'           => 'Guhertoya nûtir→',
'currentrevisionlink'    => 'Guhertoya niha nîşan bide',
'cur'                    => 'ferq',
'next'                   => 'pêş',
'last'                   => 'berê',
'page_first'             => 'yekemîn',
'page_last'              => 'paşîn',
'histlegend'             => 'Rênîşan: (cudahî) = cudahiya nav vê û versiyona niha,
(berê) = cudahiya nav vê û ya berî vê, B = guhertina biçûk',
'history-fieldset-title' => 'Li dîrokê bigere',
'history-show-deleted'   => 'Tenê yên jêbirî',
'histfirst'              => 'Kevintirîn',
'histlast'               => 'Nûtirîn',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vala)',

# Revision feed
'history-feed-title'          => 'Dîroka guhertoyê',
'history-feed-description'    => 'Di wîkî de dîroka guhertina vê rûpelê',
'history-feed-item-nocomment' => '$1 li $2',
'history-feed-empty'          => 'Rûpela xwestî tune ye. Belkî ew rûpel jê hatibe birîn an jî sernavê wê hatibe guherandin. [[Special:Search|Di wîkîyê de li rûpelên nêzîkî wê bigere]].',

# Revision deletion
'rev-deleted-comment'         => '(nivîs hate jêbirin)',
'rev-deleted-user'            => '(navê bikarhêner hate jêbirin)',
'rev-deleted-event'           => '(pêkhatin hate jêbirin)',
'rev-deleted-text-permission' => 'Ev guhertoya vê rûpelê hatiye jêbirin. Belkî agahî di [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} jêbirina têketinê] de hebe.',
'rev-delundel'                => 'nîşan bide/veşêre',
'rev-showdeleted'             => 'nîşan bide',
'revisiondelete'              => 'Guhertoyan jê bibe/nebe',
'revdelete-nologid-title'     => 'Têketina ne derbasdar',
'revdelete-show-file-submit'  => 'Erê',
'revdelete-legend'            => 'Guherandina qebûlkirina dîtinê',
'revdelete-hide-text'         => 'Nivîsa guhertoyê veşêre',
'revdelete-hide-image'        => 'Naveroka pelê veşêre',
'revdelete-hide-name'         => 'Çalakî û hedefê veşêre',
'revdelete-hide-comment'      => 'Nivîsandinê kurte yê guherandinê veşêre',
'revdelete-hide-user'         => "Navê bikarhêner/IP'yê veşêre",
'revdelete-hide-restricted'   => 'Ev qebûlkirinan ji koordînatoran ra ye jî û ev rûpela tê girtin',
'revdelete-radio-same'        => '(neguherîne)',
'revdelete-radio-set'         => 'Erê',
'revdelete-radio-unset'       => 'Na',
'revdelete-suppress'          => 'Sedema jêbirinê ji rêveberan re jî veşêre',
'revdelete-log'               => 'Sedem',
'revdel-restore'              => 'xuyakirinê biguherîne',
'revdel-restore-deleted'      => 'revîzyonên hatine jêbirin',
'revdel-restore-visible'      => 'guhertoyên berbiçav',
'pagehist'                    => 'Dîroka rûpelê',
'deletedhist'                 => 'Dîroka jêbirî',
'revdelete-content'           => 'naverok',
'revdelete-summary'           => 'kurteyê biguherîne',
'revdelete-uname'             => 'navê bikarhêner',
'revdelete-hid'               => '$1 veşart',
'revdelete-unhid'             => '$1 nîşanbide',
'revdelete-otherreason'       => 'Sedemekî din:',
'revdelete-reasonotherlist'   => 'Sedemekî din',
'revdelete-edit-reasonlist'   => 'Sedemên jêbirinê biguherîne',
'revdelete-offender'          => 'Nivîskarê/a guhertoyê:',

# History merging
'mergehistory-box'    => 'Guhertoyên her du rûpelan bike yek:',
'mergehistory-from'   => 'Çavkanîya rûpelê:',
'mergehistory-submit' => 'Guhertoyan bike yek',
'mergehistory-reason' => 'Sedem',

# Merge log
'mergelog'    => 'Yekkirina gotaran',
'revertmerge' => 'Veqetîne',

# Diffs
'history-title'            => 'Dîroka versyonên "$1"',
'difference'               => '(Ciyawaziya nav guhertoyan)',
'difference-multipage'     => '(Cudahî di navbera rûpelan de)',
'lineno'                   => 'Rêz $1:',
'compareselectedversions'  => 'Guhertoyan bide ber hev',
'showhideselectedversions' => 'Revîzyonên bijartî nîşan bide/veşêre',
'editundo'                 => 'betal bike',
'diff-multi'               => '({{PLURAL:$1|Guhertoyeke di navbera herduyan de|$1 guhertoyên di navbera herduyan de}} tê(n) dîtin.)',

# Search results
'searchresults'                  => 'Encamên lêgerînê',
'searchresults-title'            => 'Encamên lêgerrînê bo "$1"',
'searchresulttext'               => 'Ji bo zêdetir agahî der barê lêgerînê di {{SITENAME}} de, binêre [[{{MediaWiki:Helppage}}|Searching {{SITENAME}}]].',
'searchsubtitle'                 => 'Te daxwaza "[[:$1]]" kir. ([[Special:Prefixindex/$1|hemî rûpelên bi "$1" dest pê dikin]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|hemî rûpelên ku bi "$1" ve hatine girêdan]])',
'searchsubtitleinvalid'          => "Tu li '''$1''' geriyayî",
'titlematches'                   => 'Dîtinên di sernivîsên gotaran de',
'notitlematches'                 => 'Di nav sernivîsan de nehat dîtin.',
'textmatches'                    => 'Dîtinên di nivîsara rûpelan de',
'notextmatches'                  => 'Di nivîsarê de nehat dîtin.',
'prevn'                          => '{{PLURAL:$1|$1}} paş',
'nextn'                          => '{{PLURAL:$1|$1}} pêş',
'prevn-title'                    => '{{PLURAL:$1|result|Encamên}} pêştir $1',
'nextn-title'                    => '$1 {{PLURAL:$1|encama|encamên}} pêştir',
'viewprevnext'                   => '($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'              => 'Vebijartinên lêgerrînê',
'searchmenu-new'                 => 'Rûpela "[[:$1]]" li ser vê derê çêke!',
'searchhelp-url'                 => 'Help:Alîkarî',
'searchprofile-articles'         => 'Rûpelên naverokê',
'searchprofile-project'          => 'Rûpelên alîkariyê û projeyê',
'searchprofile-images'           => 'Multimedia',
'searchprofile-everything'       => 'Her tişt',
'searchprofile-advanced'         => 'Pêşketî',
'searchprofile-articles-tooltip' => 'Di $1 da lêbigere',
'searchprofile-project-tooltip'  => 'Di $1 da lêbigere',
'searchprofile-images-tooltip'   => 'Li pelan bigere',
'search-result-size'             => '$1 ({{PLURAL:$2|peyvek|$2 peyv}})',
'search-result-score'            => 'Lêhatin: $1%',
'search-redirect'                => '(redirect $1)',
'search-section'                 => '(beş $1)',
'search-suggest'                 => 'Gelo mebesta te ev bû: $1',
'search-interwiki-caption'       => 'Projeyên hevçeng',
'search-interwiki-default'       => '$1 encam:',
'search-interwiki-more'          => '(bêhtir)',
'search-mwsuggest-enabled'       => 'bi pêşniyazan',
'search-mwsuggest-disabled'      => 'pêşniyaz tune',
'search-relatedarticle'          => 'Pêwendîdar',
'searchrelated'                  => 'pêwendîdar',
'searchall'                      => 'hemû',
'showingresults'                 => "{{PLURAL:$1|Encamek|'''$1''' encam}}, bi #'''$2''' dest pê dike.",
'showingresultsnum'              => "{{PLURAL:$3|'''1'''|'''$3'''}} encam, bi #<b>$2</b> dest pê dike.",
'search-nonefound'               => 'Ti rûpelên wek ya daxwazkirî nînin.',
'powersearch'                    => 'Lê bigere',
'powersearch-legend'             => 'Lê bigere',
'powersearch-ns'                 => "Di namespace'an da lêbigere:",
'powersearch-redir'              => "Lîsteya redirect'an",
'powersearch-field'              => 'Bigere li',
'powersearch-togglelabel'        => 'Kontrol bike:',
'powersearch-toggleall'          => 'Hemû',
'powersearch-togglenone'         => 'Tune',
'search-external'                => 'Lêgerrîna derveyî',
'searchdisabled'                 => '<p>Tu dikarî li {{SITENAME}} bi Google an Yahoo! bigere. Têbînî: Dibe ku encamen lêgerîne ne yên herî nû ne.
</p>',

# Quickbar
'qbsettings-none' => 'Tune',

# Preferences page
'preferences'                 => 'Tercîhên min',
'mypreferences'               => 'Tercihên min',
'prefs-edits'                 => 'Hejmarê guherandinan:',
'prefsnologin'                => 'Xwe qeyd nekir',
'prefsnologintext'            => 'Tu gireke xwe <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} qeydbikê]</span> ji bo guherandina tercihên bikarhêneran.',
'changepassword'              => 'Şîfreyê biguherîne',
'prefs-skin'                  => 'Pêste',
'skin-preview'                => 'Pêşdîtin',
'prefs-math'                  => 'TeX',
'datedefault'                 => 'Tercih tune ne',
'prefs-datetime'              => 'Dîrok û dem',
'prefs-personal'              => 'Profîla bikarhêner',
'prefs-rc'                    => 'Guherandinên dawî',
'prefs-watchlist'             => 'Lîsteya şopandinê',
'prefs-watchlist-days-max'    => 'Ne zêdetirî 7 rojan',
'prefs-watchlist-edits-max'   => 'Hejmara mezintirîn: 1000',
'prefs-misc'                  => 'Eyarên cuda',
'prefs-resetpass'             => 'Şifreyê biguherîne',
'prefs-email'                 => 'Vebijarkên E-nameyê',
'prefs-rendering'             => 'Rû',
'saveprefs'                   => 'Tercîhan qeyd bike',
'resetprefs'                  => 'Guhertinên netomarkirî şûnde vegerîne',
'prefs-editing'               => 'Guherandin',
'rows'                        => 'Rêz',
'columns'                     => 'Stûn:',
'searchresultshead'           => 'Lê bigere',
'recentchangesdays-max'       => 'Herî zêde $1 {{PLURAL:$1|roj|rojan}}',
'savedprefs'                  => 'Tercîhên te qeyd kirî ne.',
'timezonelegend'              => 'Herêma demê:',
'localtime'                   => 'Dema herêmî',
'timezoneoffset'              => 'Cudahî¹:',
'servertime'                  => "Dema server'ê:",
'guesstimezone'               => 'Ji lêgerokê tevlî bike',
'timezoneregion-africa'       => 'Afrîka',
'timezoneregion-america'      => 'Amerîka',
'timezoneregion-antarctica'   => 'Antarktîka',
'timezoneregion-arctic'       => 'Arktîk',
'timezoneregion-asia'         => 'Asya',
'timezoneregion-atlantic'     => 'Okyanûsa Atlantîk',
'timezoneregion-australia'    => 'Awistralya',
'timezoneregion-europe'       => 'Ewropa',
'timezoneregion-indian'       => 'Okyanûsa Hindî',
'timezoneregion-pacific'      => 'Okyanûsa Mezin',
'allowemail'                  => 'Ji bikarhênerên dî e-nameyan qebûl bike',
'prefs-searchoptions'         => 'Tercihên lêgerînê',
'prefs-namespaces'            => 'Valahiyên nav',
'default'                     => 'asayî',
'prefs-files'                 => 'Dosya',
'prefs-emailconfirm-label'    => 'Piştrastkirina E-nameyê:',
'youremail'                   => 'E-nameya te:',
'username'                    => 'Navê bikarhêner:',
'uid'                         => 'Nasnameya bikarhêner:',
'prefs-memberingroups'        => 'Endamê/a {{PLURAL:$1|komê|koman}}:',
'prefs-registration'          => 'Dema xweqeydkirinê:',
'yourrealname'                => 'Navê te yê rastî*',
'yourlanguage'                => 'Ziman',
'yourvariant'                 => 'Cuda:',
'yournick'                    => 'Bernavkê nû (ji bo îmzeyê):',
'badsig'                      => 'Îmzeya ne derbasdar! Li HTML binêre ka sedema şaşbûnê çiye.',
'badsiglength'                => 'Navî te zêde dirêj e; ew gireke di bin {{PLURAL:$1|nîşanekê|nîşanan}} da be.',
'yourgender'                  => 'Zayend:',
'gender-male'                 => 'Nêr',
'gender-female'               => 'Mê',
'email'                       => 'E-name',
'prefs-help-realname'         => 'Navê rastî ne pêwîst e. Heke tu navê xwe binivisî, ewê ji bo karê te were bikaranîn.',
'prefs-help-email'            => 'Adrêsa te yê e-nameyan ne gereke were nivîsandin, lê ew qebûldike, ku bikarhênerên din vê naskirinê te kanibin e-nameyan ji te ra bişînin.',
'prefs-help-email-required'   => 'Navnîşana e-nameyê hewce ye.',
'prefs-info'                  => 'Agahiyên sereke',
'prefs-signature'             => 'Îmze',
'prefs-dateformat'            => 'Formata dîrokê',
'prefs-advancedediting'       => 'Vebijarkên berfireh',
'prefs-advancedrc'            => 'Vebijarkên berfireh',
'prefs-advancedrendering'     => 'Vebijarkên berfireh',
'prefs-advancedsearchoptions' => 'Vebijarkên berfireh',
'prefs-advancedwatchlist'     => 'Vebijarkên berfireh',
'prefs-displayrc'             => 'Vebijarkan nîşan bide',
'prefs-displaysearchoptions'  => 'Vebijarkan nîşan bide',
'prefs-displaywatchlist'      => 'Vebijarkan nîşan bide',
'prefs-diffs'                 => 'Cudahî',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-name derbasdar e',
'email-address-validity-invalid' => 'E-nameyeke derbasdar binivîse',

# User rights
'userrights'                  => 'Îdarekirina mafên bikarhêneran',
'userrights-lookup-user'      => 'Birêvebirina koman',
'userrights-user-editname'    => 'Navekî bikarhêneriyê binivîse:',
'editusergroup'               => 'Komên bikarhêneran biguherîne',
'editinguser'                 => "Mafên bikarhêner '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) tên xeyrandin",
'userrights-editusergroup'    => 'Grûpên bikarhêneran biguherîne',
'saveusergroups'              => 'Komên bikarhêneran tomar bike',
'userrights-groupsmember'     => 'Endamê/a:',
'userrights-groups-help'      => 'Tu dikarî komên bikarhêneran ên vê/vî bikarhênerê/î biguherînî:
* Qutiyeke nîşankirî dibêje ku ev bikarhêner di wê komê de ye.
* Qutiyeke nenîşankirî dibêje ku ev bikarhêner ne di wê komê de ye.
* Stêrkek (*) nîşan dide ku tu nikarî wê komê jêbibî, heke te berê ew lê zêde kiribe.',
'userrights-reason'           => 'Sedem:',
'userrights-no-interwiki'     => 'Mafê te ji bo guherandina mafên bikarhênerên di Wîkiyên din de nîne.',
'userrights-nodatabase'       => 'Danegeh $1 nîne an ne ya vir e.',
'userrights-nologin'          => 'Ji bo guherandina mafên bikarhêneran, divê tu bi hesabê rêveber [[Special:UserLogin|têkevî]].',
'userrights-notallowed'       => "Account'a te mafê xwe tune ye ji bo guherandina mafên bikarhêneran.",
'userrights-changeable-col'   => 'Komên tu dikarî biguherînî',
'userrights-unchangeable-col' => 'Komên tu nikarî biguherînî',

# Groups
'group'            => 'Kom',
'group-user'       => 'Bikarhêner',
'group-bot'        => 'Bot',
'group-sysop'      => 'Rêveber',
'group-bureaucrat' => 'Bûrokrat',
'group-all'        => '(hemû)',

'group-user-member'       => 'Bikarhêner',
'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'rêveber',
'group-bureaucrat-member' => 'Burokrat',

'grouppage-user'       => '{{ns:project}}:Bikarhêner',
'grouppage-bot'        => '{{ns:project}}:Bot',
'grouppage-sysop'      => '{{ns:project}}:Admînistrator',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrat',

# Rights
'right-read'          => 'Rûpelan bixwîne',
'right-edit'          => 'Rûpelan biguherîne',
'right-createtalk'    => 'Rûpelên gotûbêjê çêke',
'right-createaccount' => 'Hesaba bikarînerek nû veke',
'right-minoredit'     => 'Guhertina biçûk e',
'right-move'          => 'Rûpelan bigerîne',
'right-upload'        => 'Dosyeyan lê bar bike',
'right-delete'        => 'Rûpelan jê bibe',
'right-browsearchive' => 'Li rûpelên jêbirî bigerre',
'right-undelete'      => 'Jêbirinê betal bike',
'right-import'        => 'Rûpelan ji wikiyên din împort bike',
'right-userrights'    => 'Hemû mafên bikarhêner biguherîne',
'right-sendemail'     => 'Ji bikarhênerên di re e-name bişîne',

# User rights log
'rightslog'      => 'Reşahîya mafên bikarhêneran',
'rightslogtext'  => 'Ev reşahîyek ji bo guherandinên mafên bikarhêneran e.',
'rightslogentry' => 'grûpa bikarhêneran ji bo $1 ji $2 guherande $3',
'rightsnone'     => '(tune)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'Vê rûpelê bixwîne',
'action-edit'           => 'vê rûpelê biguherîne',
'action-createpage'     => 'rûpelan çêke',
'action-createtalk'     => 'rûpelên guftûgoyan çêke',
'action-createaccount'  => "vê account'ê bikarhênerê çêke",
'action-minoredit'      => 'vê weke guhertineke biçûk nîşan bide',
'action-move'           => 'vê rûpelê bigerîne',
'action-move-subpages'  => 'vê rûpelê û binrûpelên wê bigerîne',
'action-movefile'       => "vê data'yê bigerîne",
'action-upload'         => "vê data'yê barbike",
'action-delete'         => 'vê rûpelê jêbibe',
'action-deleterevision' => 'Vê revîzyonê je bibe',
'action-deletedhistory' => 'dîroka vê rûpelê jêbirî bibîne',
'action-browsearchive'  => 'li rûpelên jêbirî bigere',
'action-undelete'       => 'vê rûpelê dîsa çêke',
'action-userrights'     => 'hemû mafên bikarhêneran biguherîne',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|guherandinek|guherandin}}',
'recentchanges'                     => 'Guherandinên dawî',
'recentchanges-legend'              => 'Vebijarkên guherandinên dawî',
'recentchanges-label-minor'         => 'Ev guhertineka biçûk e',
'rcnote'                            => "Jêr {{PLURAL:$1|guherandinek|'''$1''' guherandinên dawî}} di {{PLURAL:$2|rojê|'''$2''' rojên dawî}} de ji $3 şûnde tên nîşan dan.",
'rclistfrom'                        => 'an jî guherandinên ji $1 şûnda nîşan bide.',
'rcshowhideminor'                   => 'guherandinên biçûk $1',
'rcshowhidebots'                    => "bot'an $1",
'rcshowhideliu'                     => 'bikarhênerên qeydkirî $1',
'rcshowhideanons'                   => 'bikarhênerên neqeydkirî (IP) $1',
'rcshowhidepatr'                    => '$1 guherandinên kontrolkirî',
'rcshowhidemine'                    => 'guherandinên min $1',
'rclinks'                           => '$1 guherandinên di $2 rojên dawî de nîşan bide<br />$3',
'diff'                              => 'ciyawazî',
'hist'                              => 'dîrok',
'hide'                              => 'veşêre',
'show'                              => 'nîşan bide',
'minoreditletter'                   => 'B',
'newpageletter'                     => 'Nû',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|bikarhênerek|$1 bikarhêner}} vê rûpelê {{PLURAL:$1|dişopîne|dişopînin}}.]',
'rc_categories_any'                 => 'Hîç',
'newsectionsummary'                 => '/* $1 */ beşeka nuh',
'rc-enhanced-expand'                => 'Kitûmatan nîşan bide (JavaScript pêdivî ye)',
'rc-enhanced-hide'                  => 'Kitûmatan veşêre',

# Recent changes linked
'recentchangeslinked'         => 'Guherandinên peywend',
'recentchangeslinked-feed'    => 'Guherandinên peywend',
'recentchangeslinked-toolbox' => 'Guherandinên peywend',
'recentchangeslinked-summary' => "Ev rûpela taybetî guherandinên dawî ji rûpelên lînkkirî nîşandide. Ew rûpel yê di lîsteya te ya şopandinê da ne bi nivîsa '''estûr''' tên nîşandan.",
'recentchangeslinked-page'    => 'Navê rûpelê',

# Upload
'upload'                  => 'Wêneyekî barbike',
'uploadbtn'               => 'Wêneyê (ya tiştekî din ya mêdya) barbike',
'reuploaddesc'            => 'Barkirinê biskîne û dîsa here rûpela barkirinê.',
'uploadnologin'           => 'Xwe qeyd nekir',
'uploadnologintext'       => 'Ji bo barkirina wêneyan divê ku tu [[Special:UserLogin|têkevî]].',
'uploaderror'             => 'Çewtiya barkirinê',
'upload-recreate-warning' => "'''Agahdarî: Peleke bi vî navî hatiye jêbirin an jî raguhestin.'''",
'uploadtext'              => "Berê tu wêneyên nû bar bikî, ji bo dîtin an vedîtina wêneyên ku ji xwe hene binêre: [[Special:FileList|lîsteya wêneyên barkirî]]. Herwisa wêneyên ku hatine barkirin an jî jê birin li vir dikarî bibînî: [[Special:Log/upload|reşahîya barkirîyan]].

Yek ji lînkên jêr ji bo bikarhînana wêne an file'ê di gotarê de bikar bihîne:
'''<nowiki>[[</nowiki>{{ns:file}}:File.jpg<nowiki>]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}:File.png|alt text<nowiki>]]</nowiki>''',
anjî ji bo file'ên dengî '''<nowiki>[[</nowiki>{{ns:media}}:File.ogg<nowiki>]]</nowiki>'''",
'upload-permitted'        => 'Cureyên pelan yên tên qebûlkirin: $1.',
'upload-preferred'        => 'Cureyên pelan yên tên xwestin: $1.',
'upload-prohibited'       => 'Cureyên pelan yên qedexekirî: $1.',
'uploadlog'               => 'Têketina barkirinê',
'uploadlogpage'           => 'Têketina barkirinê',
'filename'                => 'Navê pelê',
'filedesc'                => 'Kurte',
'fileuploadsummary'       => 'Kurte:',
'filereuploadsummary'     => 'Guhertinên pelê:',
'filestatus'              => 'Rewşa telîfê:',
'filesource'              => 'Çavkanî:',
'uploadedfiles'           => 'Pelên barkirî',
'ignorewarning'           => 'Hişyarê qebûl neke û dosyayê qeyd bike.',
'ignorewarnings'          => 'Guh nede hişyariyan',
'minlength1'              => "Navên data'yan bi kêmani gireke tîpek be.",
'illegalfilename'         => 'Navî datayê "$1" ne tê qebûlkirin ji ber ku tişt tê da hatine nivîsandin yê qedexe ne. Xêra xwe navî datayê biguherîne û carekî din barbike.',
'badfilename'             => 'Navê vî wêneyî hat guherandin û bû "$1".',
'filetype-badmime'        => 'Data bi formata MIME yê "$1" nameşin werin barkirin.',
'filetype-unwanted-type'  => '\'\'\'".$1"\'\'\' formatekî nexastî ye.
Format {{PLURAL:$3|yê tê|yên tên}} qebûlkirin {{PLURAL:$3|ev e|ev in}}: $2.',
'filetype-banned-type'    => '\'\'\'".$1"\'\'\' formatekî qedexe ye.
Format {{PLURAL:$3|yê tê|yên tên}} xastin {{PLURAL:$3|ev e|ev in}}: $2.',
'filetype-missing'        => 'Piştnavê pelê tune (wek ".jpg").',
'unknown-error'           => 'Çewtiyeke nenas pêk hat.',
'large-file'              => 'Mezinbûna pelê bila ji $1 ne mezintir be; ev pel $2 e.',
'emptyfile'               => "Data'ya barkirî vala ye. Sedemê valabûnê belkî şaşnivîsek di navê data'yê da ye. Xêra xwe seke, ku tu rast dixazê vê data'yê barbikê.",
'fileexists'              => "Datayek bi vê navê berê heye.
Eger tu niha li „Tomarbike“ xê, ew wêneyê kevin ê here û wêneyê te ê were barkirin di bin wê navê.
Di bin '''<tt>[[:$1]]</tt>''' du dikarî sekê, ku di dixwazê wê wêneyê biguherînê.
Eger tu naxazê, xêra xwe li „Betal“ xe.
[[$1|thumb]]",
'fileexists-extension'    => "Datayek wek vê navê berê heye: [[$2|thumb]]
* Navî datayê yê tê barkirin: '''<tt>[[:$1]]</tt>'''
* Navî datayê yê berê heyê: '''<tt>[[:$2]]</tt>'''
Xêra xwe navekî din bibîne.",
'file-thumbnail-no'       => "Navî vê datayê bi '''<tt>$1</tt>''' destpêdike. Ev dibêje ku ev wêneyekî çûçik e ''(thumbnail)''. Xêra xwe seke, ku belkî versyonekî mezin yê vê wêneyê li cem te heye û wê wêneyê mezintir di bin navî orîjînal da barbike.",
'fileexists-forbidden'    => 'Medyayek bi vê navî heye; xêra xwe şonda here û vê medyayê bi navekî din barbike.
[[File:$1|thumb|center|$1]]',
'uploadwarning'           => 'Hişyara barkirinê',
'savefile'                => 'Dosyayê tomar bike',
'uploadedimage'           => '"$1" barkirî',
'overwroteimage'          => 'versyonekî nuh ji "[[$1]]" hate barkirin',
'uploaddisabled'          => 'Barkirin hatîye qedexekirin',
'uploaddisabledtext'      => "Barkirinê data'yan  hatiye qedexekirin.",
'uploadvirus'             => "Di vê data'yê da vîrûsek heye! Înformasyon: $1",
'upload-source'           => 'Pela çavkaniyê',
'sourcefilename'          => 'Navê pelê:',
'sourceurl'               => 'URL ya çavkaniyê:',
'destfilename'            => 'Navê pela xwestî:',
'upload-maxfilesize'      => 'Mezinbûna pelê ya herî mezin: $1',
'upload-description'      => 'Danasîna pelê',
'upload-options'          => 'Vebijarkên barkirinê',
'watchthisupload'         => 'Vê rûpelê bişopîne',
'filewasdeleted'          => "Data'yek bi vê navê hatibû barkirin û jêbirin. Xêra xwe li $1 seke ku barkirina te hêja ye ya na.",
'upload-wasdeleted'       => "'''Hîşyar: Tu data'yekê bardikê yê berê hatibû jêbirin.'''

Zanibe, ku ev barkirina kê were qebûlkirin ya na.

Înformasyonan li ser jêbirinê kevin ra:",
'filename-bad-prefix'     => "Nava wê data'yê, yê tu niha bardikê, bi '''\"\$1\"''' destpêdike. Kamêrayên dîjîtal wan navan didin wêneyên xwe. Ji kerema xwe navekî baştir binivisîne ji bo mirov zûtir zanibin ku şayeşê vê wêneyê çî ye.",
'upload-success-subj'     => 'Barkirin serkeftî',
'upload-failure-subj'     => 'Pirsgirêka barkirinê',
'upload-warning-subj'     => 'Hişyariya barkirinê',

'upload-file-error'   => 'Çewtiya navxweyî',
'upload-unknown-size' => 'Mezinahiya nayê zanîn',

'license'           => 'Lîsans:',
'license-header'    => 'Lîsens:',
'nolicense'         => 'ya hilbijartî nîne',
'license-nopreview' => 'Pêşdîtin ne gengaz e.',

# Special:ListFiles
'listfiles_search_for'  => 'Li navî wêneyê bigere:',
'imgfile'               => 'dosye',
'listfiles'             => 'Listeya wêneyan',
'listfiles_date'        => 'Dem',
'listfiles_name'        => 'Nav',
'listfiles_user'        => 'Bikarhêner',
'listfiles_size'        => 'Mezinbûn',
'listfiles_description' => 'Danasîn',
'listfiles_count'       => 'Versiyon',

# File description page
'file-anchor-link'          => 'Wêne',
'filehist'                  => 'Dîroka datayê',
'filehist-help'             => 'Ji bo dîtina guhertoya wê demê bişkoka dîrokê bitikîne.',
'filehist-deleteall'        => 'giştika jêbibe',
'filehist-deleteone'        => 'jê bibe',
'filehist-revert'           => 'şûnde vegerîne',
'filehist-current'          => 'niha',
'filehist-datetime'         => 'Dîrok/Katjimêr',
'filehist-thumb'            => 'Thumbnail',
'filehist-user'             => 'Bikarhêner',
'filehist-dimensions'       => 'Mezinahî',
'filehist-filesize'         => 'Mezinahiya pelê',
'filehist-comment'          => 'Nivîs',
'filehist-missing'          => 'Pel nîne',
'imagelinks'                => 'Girêdanên vî wêneyî',
'linkstoimage'              => 'Di van rûpelan de lînkek ji vî wêneyî re heye:',
'nolinkstoimage'            => 'Rûpelekî ku ji vî wêneyî re girêdankê çêdike nîne.',
'uploadnewversion-linktext' => 'Versyonekî nû yê vê datayê barbike',
'shared-repo-from'          => 'ji $1',

# File reversion
'filerevert'         => '"$1" şondabike',
'filerevert-legend'  => 'Pelê vegerîne',
'filerevert-comment' => 'Sedem:',
'filerevert-submit'  => 'Şûnde vegerîne',

# File deletion
'filedelete'                  => '$1 jêbibe',
'filedelete-legend'           => 'Pelê jê bibe',
'filedelete-intro'            => "Tu kê '''[[Media:$1|$1]]''' jêbibê.",
'filedelete-intro-old'        => "Tu niha guhertoya '''[[Media:$1|$1]]''' [$4 guherto, ji $2, saet $3] jê dibî.",
'filedelete-comment'          => 'Sedem:',
'filedelete-submit'           => 'Jê bibe',
'filedelete-success'          => "'''$1''' hate jêbirin.",
'filedelete-success-old'      => "<span class=\"plainlinks\">Verzyona \$2 ji data'ya '''[[Media:\$1|\$1]]''' di saet \$3 da hate jêbirin.</span>",
'filedelete-nofile'           => "'''$1''' nîne.",
'filedelete-otherreason'      => 'Sedemên din:',
'filedelete-reason-otherlist' => 'Sedemên din',
'filedelete-reason-dropdown'  => '*Sedemên jêbirina wêneyan
** wêneyekî nebaş e
** kopiyek e',
'filedelete-edit-reasonlist'  => 'Sedemên jêbirinê biguherîne',

# MIME search
'mimesearch' => 'Gerrîna li MIME',
'download'   => 'daxistin',

# Unwatched pages
'unwatchedpages' => 'Rûpelên nayên şopandin',

# List redirects
'listredirects' => 'Lîsteya beralîkirinan',

# Unused templates
'unusedtemplates'    => 'Şablonên nayên bikaranîn',
'unusedtemplateswlh' => 'lînkên din',

# Random page
'randompage' => 'Rûpeleke ketober',

# Random redirect
'randomredirect' => 'Beralîkirina ketober',

# Statistics
'statistics'              => 'Statîstîk',
'statistics-header-pages' => 'Statîstîkên rûpelê',
'statistics-header-edits' => 'Statîstîkan biguherîne',
'statistics-header-views' => 'Amaran bibîne',
'statistics-header-users' => 'Statistîkên bikarhêner',
'statistics-header-hooks' => 'Statîstîkên din',
'statistics-articles'     => 'Rûpelên naverokê',
'statistics-pages'        => 'Rûpel',
'statistics-pages-desc'   => 'Hemû rûpelên di vê wîkiyê de, bi hemû rûpelên nîqaş, beralîkirin, hwd.',
'statistics-files'        => 'Wêneyên barkirî',
'statistics-views-total'  => 'Hemû nîşandan',
'statistics-users'        => '[[Special:ListUsers|Bikarhênerên tomarkirî]]',
'statistics-users-active' => 'Bikarhênerên çalak',
'statistics-mostpopular'  => 'Rûpelên herî lênerî',

'disambiguations'     => 'Rûpelên cudakirinê',
'disambiguationspage' => 'Template:disambig',

'doubleredirects'            => 'Beralîkirinên ducarî',
'double-redirect-fixed-move' => 'Cihê [[$1]] hatiye guhertin, ew niha beralîkirina [[$2]] ye.',

'brokenredirects'        => 'Beralîkirinên xerabûyî',
'brokenredirects-edit'   => 'biguherîne',
'brokenredirects-delete' => 'jêbibe',

'withoutinterwiki'        => 'Rûpelên bê girêdanên ziman',
'withoutinterwiki-legend' => 'Pêşbendik',
'withoutinterwiki-submit' => 'Nîşan bide',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|Kategorî|Kategorî}}',
'nlinks'                  => '$1 {{PLURAL:$1|girêdan|girêdan}}',
'nmembers'                => '$1 {{PLURAL:$1|endam|endam}}',
'nrevisions'              => '$1 {{PLURAL:$1|guherandin|guherandin}}',
'nviews'                  => '$1 {{PLURAL:$1|dîtin|dîtin}}',
'lonelypages'             => 'Rûpelên sêwî',
'uncategorizedpages'      => 'Rûpelên bê kategorî',
'uncategorizedcategories' => 'Kategoriyên bê kategorî',
'uncategorizedimages'     => 'Wêneyên bê kategorî',
'uncategorizedtemplates'  => 'Şablonên bê kategorî',
'unusedcategories'        => 'Kategoriyên ku nayên bi kar anîn',
'unusedimages'            => 'Wêneyên ku nayên bi kar anîn',
'popularpages'            => 'Rûpelên populer',
'wantedcategories'        => 'Kategoriyên tên xwestin',
'wantedpages'             => 'Rûpelên ku tên xwestin',
'wantedfiles'             => 'Wêneyên ku tên xwestin',
'wantedtemplates'         => 'Şablonên tên xwestin',
'mostlinked'              => 'Rûpelên bi bêhtirîn girêdan',
'mostlinkedcategories'    => 'Kategoriyên bi bêhtirîn girêdan',
'mostcategories'          => 'Rûpelên bi pir kategorî',
'prefixindex'             => 'Hemû rûpelên bi pêşbendik',
'shortpages'              => 'Rûpelên kurt',
'longpages'               => 'Rûpelên dirêj',
'deadendpages'            => 'Rûpelên bê dergeh',
'protectedpages'          => 'Rûpelên parastî',
'protectedtitles'         => 'Sernavên parastî',
'listusers'               => 'Lîsteya bikarhêneran',
'listusers-editsonly'     => 'Tenê bikarhênerên bi guherrandinan nîşan bide',
'usercreated'             => 'di $1 de, li $2 hate çêkirin',
'newpages'                => 'Rûpelên nû',
'newpages-username'       => 'Navê bikarhêner:',
'ancientpages'            => 'Gotarên kevintirîn',
'move'                    => 'Navê rûpelê biguherîne',
'movethispage'            => 'Vê rûpelê bigerîne',
'notargettitle'           => 'Hedef tune',
'pager-newer-n'           => '{{PLURAL:$1|nûtir 1|nûtir $1}}',
'pager-older-n'           => '{{PLURAL:$1|kevintirin 1|kevintirin $1}}',

# Book sources
'booksources'               => 'Çavkaniyên pirtûkan',
'booksources-search-legend' => 'Li pirtûkan bigere',
'booksources-go'            => 'Here',
'booksources-text'          => 'Li vir listek ji lînkên rûpelên, yê pirtûkên nuh ya kevin difiroşin, heye. Hên jî li vir tu dikarî înformasyonan li ser wan pirtûkan tê derxê.',

# Special:Log
'specialloguserlabel'  => 'Bikarhêner:',
'speciallogtitlelabel' => 'Sernav:',
'log'                  => 'Têketin',
'all-logs-page'        => 'Hemû têketin',
'alllogstext'          => 'Hemû têketinên {{SITENAME}} li jêr tên nîşandan.
Tu dikarî ji xwe re têketinekê hilbijêrî, navê bikarhêneriyê an navê rûpelekê binivîse û agahiyan li ser wê bibîne.',
'logempty'             => 'Tiştek di vir de nîne.',
'log-title-wildcard'   => 'Li sernavan bigere, yê bi vê destpêdikin',

# Special:AllPages
'allpages'          => 'Hemû rûpel',
'alphaindexline'    => '$1 heta $2',
'nextpage'          => 'Rûpela pêşî ($1)',
'prevpage'          => 'Rûpelê berî vê ($1)',
'allpagesfrom'      => 'Rûpela di rêza yekemîn de:',
'allpagesto'        => 'Rûpela di rêza dawî de:',
'allarticles'       => 'Hemû gotar',
'allinnamespace'    => 'Hemû rûpel (valahiya nav a $1)',
'allnotinnamespace' => 'Hemû rûpel (ne di valahiya nav a $1 de ye)',
'allpagesprev'      => 'Pêş',
'allpagesnext'      => 'Paş',
'allpagessubmit'    => 'Here',
'allpagesprefix'    => 'Rûpelên bi pêşbendik nîşan bide:',
'allpagesbadtitle'  => 'Sernavê rûpelê qedexe bû ya "interwiki"- ya "interlanguage"-pêşnavekî xwe hebû. Meqûle ku zêdertirî tiştekî nikanin werin bikaranîn di sernavê da.',
'allpages-bad-ns'   => 'Namespace\'a "$1" di {{SITENAME}} da tune ye.',

# Special:Categories
'categories'                    => 'Kategorî',
'categoriespagetext'            => 'Di van kategoriyan de rûpel an jî medya hene.
[[Special:UnusedCategories|Kategoriyên nayên bikaranîn]] li vir nayên nîşandan.
Li [[Special:WantedCategories|kategoriyên xwestî]] binêre.',
'special-categories-sort-count' => 'hatîye rêzkirin li gorî hejmaran',
'special-categories-sort-abc'   => 'li gorî alfabeyê rêzkirî ye',

# Special:DeletedContributions
'deletedcontributions'             => 'Guherandinên bikarhênerekî yê jêbirî',
'deletedcontributions-title'       => 'Guherandinên bikarhênerekî yê jêbirî',
'sp-deletedcontributions-contribs' => 'tevkarî',

# Special:LinkSearch
'linksearch'    => 'Girêdanên derveyî',
'linksearch-ns' => 'Valahiya nav:',
'linksearch-ok' => 'Lêbigere',

# Special:ListUsers
'listusers-submit'   => 'Nîşan bide',
'listusers-noresult' => 'Bikarhêner nehate dîtin.',
'listusers-blocked'  => '(hate astengkirin)',

# Special:ActiveUsers
'activeusers'            => 'Lîsteya bikarhênerên çalak',
'activeusers-hidebots'   => "Bot'an veşêre",
'activeusers-hidesysops' => 'Rêveberan veşêre',
'activeusers-noresult'   => 'Tu bikarhêner nehate dîtin.',

# Special:Log/newusers
'newuserlogpage'          => 'Çêkirina hesabê nû',
'newuserlog-byemail'      => 'şîfre bi e-nameyê hate şandin',
'newuserlog-create-entry' => 'Bikarhênerê/a nû',

# Special:ListGroupRights
'listgrouprights'                 => 'Mafên koma bikarhêner',
'listgrouprights-group'           => 'Kom',
'listgrouprights-rights'          => 'Maf',
'listgrouprights-members'         => '(lîsteya endaman)',
'listgrouprights-addgroup-all'    => 'Hemû koman tevlî bike',
'listgrouprights-removegroup-all' => 'Hemû koman jê bibe',

# E-mail user
'mailnologin'     => 'Navnîşanê neşîne',
'mailnologintext' => 'Te gireke xwe [[Special:UserLogin|qeydbikê]] û adrêsa e-nameyan di [[Special:Preferences|tercihên xwe]] da nivîsandibe ji bo şandina e-nameyan ji bikarhênerên din ra.',
'emailuser'       => 'Ji vê/î bikarhênerê/î re e-name bişîne',
'emailpage'       => 'E-name bikarhêner',
'defemailsubject' => '{{SITENAME}} e-name',
'noemailtitle'    => 'Navnîşana e-name tune',
'emailfrom'       => 'Ji',
'emailto'         => 'Ji bo:',
'emailsubject'    => 'Mijar',
'emailmessage'    => 'Peyam:',
'emailsend'       => 'Bişîne',
'emailccme'       => 'Kopiyeke vê peyamê ji min re bişîne.',
'emailsent'       => 'E-name hat şandin',
'emailsenttext'   => 'E-nameya te hat şandin.',

# User Messenger
'usermessage-editor' => 'Peyamnêra sîstemê',

# Watchlist
'watchlist'            => 'Lîsteya min a şopandinê',
'mywatchlist'          => 'Lîsteya min a şopandinê',
'nowatchlist'          => 'Tiştek di lîsteya te ya şopandinê de tune ye.',
'watchlistanontext'    => 'Ji bo sekirinê ya xeyrandinê lîsteya te ya şopandinê tu gireke xwe $1.',
'watchnologin'         => 'Te xwe qeyd nekiriye.',
'watchnologintext'     => 'Ji bo xeyrandinê lîsteya te ya şopandinê tu gireke xwe [[Special:UserLogin|qedy kiribe]].',
'addedwatch'           => 'Hat îlawekirinî listeya şopandinê',
'addedwatchtext'       => "Rûpela \"<nowiki>\$1</nowiki>\" çû ser [[Special:Watchlist|lîsteya te ya şopandinê]].
Li dahatû de her guhartoyek li wê rûpelê û rûpela guftûgo ya wê were kirin li vir dêt nîşan dan,

Li rûpela [[Special:RecentChanges|Guherandinên dawî]] jî ji bo hasan dîtina wê, ew rûpel bi '''Nivîsa estûr''' dê nîşan dayîn.


<p>Her dem tu bixwazî ew rûpel li nav lîsteya te ya şopandinê derbikî, li ser wê rûpelê, klîk bike \"êdî neşopîne\".</p>",
'removedwatch'         => 'Ji lîsteya şopandinê hate jêbirin',
'removedwatchtext'     => 'Rûpela "[[:$1]]" ji lîsteya te ya şopandinê hate jêbirin.',
'watch'                => 'Bişopîne',
'watchthispage'        => 'Vê rûpelê bişopîne',
'unwatch'              => 'Êdî neşopîne',
'unwatchthispage'      => 'Êdî neşopîne',
'notanarticle'         => 'Ne gotar e',
'watchnochange'        => 'Ne rûpelek, yê tu dişopînê, hate xeyrandin di vê wextê da, yê tu dixazê bibînê.',
'watchlist-details'    => '* {{PLURAL:$1|Rûpelek tê|$1 rûpel tên}} şopandin, rûpelên guftûgoyê netên jimartin.',
'wlheader-enotif'      => '* Agahdariya E-nameyê pêk tê.',
'wlheader-showupdated' => "* Ew rûpel yê hatin xeyrandin jilkî te li wan sekir di '''nivîsa estûr''' tên pêşandin.",
'watchlistcontains'    => 'Di lîsteya şopandina te de {{PLURAL:$1|rûpelek heye|$1 rûpel hene}}.',
'wlnote'               => "Niha {{PLURAL:$1|xeyrandinê|'''$1''' xeyrandinên}} dawî yê {{PLURAL:$2|seetê|'''$2''' seetên}} dawî {{PLURAL:$1|tê|tên}} dîtin.",
'wlshowlast'           => 'Guhertinên berî $1 saetan, $2 rojan, ya $3 (di sih rojên dawî de)',
'watchlist-options'    => 'Vebijarkên lîsteya şopandinê',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Bişopîne...',
'unwatching' => 'Neşopîne...',

'enotif_reset'                 => 'Hemû rûpelan wek lêsekirî nîşanbide',
'enotif_newpagetext'           => 'Ev rûpeleke nû ye.',
'enotif_impersonal_salutation' => 'Bikarhênerî {{SITENAME}}',
'changed'                      => 'hate guhertin',
'created'                      => 'hate afirandin',
'enotif_subject'               => '[{{SITENAME}}] Rûpelê "$PAGETITLE" ji $PAGEEDITOR hate $CANGEDORCREATED',
'enotif_anon_editor'           => 'Bikarhênerê/a neqeydkirî $1',
'enotif_body'                  => '$WATCHINGUSERNAME,


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
{{fullurl:{{#special:Watchlist}}/edit}} seke.

"Feedback" û alîkarîyê din:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Rûpelê jê bibe',
'confirm'                => 'Pesend bike',
'excontent'              => "Naveroka berê: '$1'",
'excontentauthor'        => "Naverroka vê rûpelê ev bû: '$1' (û tenya bikarhêner '$2' bû)",
'exbeforeblank'          => "Nawerok berî betal kirinê ew bû: '$1'
Naverroka berî betalkirinê ev bû:'$1'",
'exblank'                => 'rûpel vala bû',
'delete-confirm'         => 'Jêbirina "$1"',
'delete-legend'          => 'Jêbirin',
'historywarning'         => "'''Hişyarî''': Dîrokeke vê rûpela tu dixwazî jê bibî heye:",
'confirmdeletetext'      => 'Tu kê niha rûpelekê bi tev dîroka wê jêbibê. Xêra xwe zanibe tu kê niha çi bikê û zanibe, çi di wîkîyê da yê bibe. Hên jî seke, ku ev jêbirina bi [[{{MediaWiki:Policy-url}}|mafên wîkîyê]] ra dimeşin ya na.',
'actioncomplete'         => 'Çalakî pêk hat',
'actionfailed'           => 'Çalakî têkçû',
'deletedtext'            => '"<nowiki>$1</nowiki>" hat jêbirin. Ji bo qeyda rûpelên ku di dema nêzîk de hatin jêbirin binêre $2.',
'deletedarticle'         => '"[[$1]]" hat jêbirin',
'dellogpage'             => 'Jêbirina rûpelê',
'dellogpagetext'         => 'Li jêr lîsteyek ji jêbirinên dawî heye.',
'deletionlog'            => 'jêbirina rûpelê',
'reverted'               => 'Hate şondabirin berve verzyonekî berê',
'deletecomment'          => 'Sedem:',
'deleteotherreason'      => 'Sedemekî din:',
'deletereasonotherlist'  => 'Sedemekî din',
'deletereason-dropdown'  => '*Sedemên jêbirinê
** Daxwaziya xwedî
** Pirsgirêka lîsansê
** Vandalîzm',
'delete-edit-reasonlist' => 'Sedemên jêbirinê biguherîne',
'delete-toobig'          => 'Dîroka vê rûpelê pir mezin e, zêdetirî $1 guherandin. Jêbirina van rûpelan hatîye sînorkirin, ji bo pir şaşbûn (error) di {{SITENAME}} da çênebin.',
'delete-warning-toobig'  => "Dîroka vê rûpelê pir mezin e, zêdetirî $1 guherandin. Jêbirina van rûpelan dikarin şaşbûnan di database'ê {{SITENAME}} da çêkin; zandibe tu çi dikê!",

# Rollback
'rollback_short'   => 'Bizivirîne pêş',
'rollbacklink'     => 'bizivirîne pêş',
'cantrollback'     => "Guharto naye vegerandin; bikarhêrê dawî, '''tenya''' nivîskarê wê rûpelê ye.",
'alreadyrolled'    => 'Guherandina dawiya [[$1]]
bi [[User:$2|$2]] ([[User talk:$2|guftûgo]]) venizivre; keseke din wê rûpelê zivrandiye an guherandiye.

Guhartoya dawî bi [[User:$3|$3]] ([[User talk:$3|guftûgo]]).',
'editcomment'      => "Kurtenivîsê guherandinê ev bû: \"''\$1''\".",
'revertpage'       => 'Guherandina $2 hat betal kirin, vegerand guhartoya dawî ya $1',
'rollback-success' => 'Guherandina $1 şondakir; dîsa guharte verzyona $2.',

# Protect
'protectlogpage'              => 'Têketina parastinê',
'protectedarticle'            => 'parastî [[$1]]',
'modifiedarticleprotection'   => 'parastina "[[$1]]" guherand',
'unprotectedarticle'          => '"[[$1]]" niha vê parastin e',
'protect-title'               => 'parastina "$1" biguherîne',
'prot_1movedto2'              => '$1 çû cihê $2',
'protect-legend'              => 'Parastinê teyîd bike',
'protectcomment'              => 'Sedem:',
'protectexpiry'               => 'Heta:',
'protect_expiry_invalid'      => 'Dema nivîsandî şaş e.',
'protect_expiry_old'          => 'Dema girtinê di zemanê berê da ye.',
'protect-default'             => 'Destûrê bide hemû bikarhêneran',
'protect-level-autoconfirmed' => 'Bikarhênerên neqeydkirî astengbike',
'protect-level-sysop'         => 'Tenê rêveber (admîn)',
'protect-expiring'            => 'heta rojê $1 (UTC)',
'protect-othertime'           => 'Demeke din:',
'protect-othertime-op'        => 'dema din',
'protect-otherreason'         => 'Sedemekî din:',
'protect-otherreason-op'      => 'Sedemekî din',
'protect-edit-reasonlist'     => 'Sedemên parastinê biguherîne',
'protect-expiry-options'      => 'saetekê:1 hour,rojekê:1 day,hefteyekê:1 week,2 hefte:2 weeks,mehekê:1 month,3 meh:3 months,6 meh:6 months,salekê:1 year,hertim:infinite',
'restriction-type'            => 'Destûr:',
'minimum-size'                => 'Biçûktirîn mezinahî',
'maximum-size'                => 'Zêdetirîn mezinahî',

# Restrictions (nouns)
'restriction-edit'   => 'Biguherîne',
'restriction-move'   => 'Nav biguherîne',
'restriction-create' => 'Çêke',
'restriction-upload' => 'Bar bike',

# Restriction levels
'restriction-level-sysop'         => 'tev-parastî',
'restriction-level-autoconfirmed' => 'nîv-parastî',

# Undelete
'undelete'                  => 'Li rûpelên jêbirî seke',
'undeletepage'              => 'Rûpelên jêbirî bibîne û dîsa çêke',
'viewdeletedpage'           => 'Rûpelên vemirandî seke',
'undeletepagetext'          => 'Rûpelên jêr hatine jêbirin, lê ew hên di arşîvê da ne û dikarin dîsa werin çêkirin. Ev arşîva piştî demekê tê pakkirin.',
'undeleteextrahelp'         => "Ji bo dîsaçêkirina vê rûpelê, li checkbox'an nexe û li '''''Dîsa çêke''''' klîk bike. Eger tu naxazî ku hemû verzyon dîsa werin çêkirin, li checkbox'ên wan verzyonan xe, yê tu dixazî dîsa çêkê û paşê li '''''Dîsa çêke'''' klîk bike. Eger tu li '''''Biskine''''' xê, hemû checkbox û cihê sedemê yê werin valakirin.",
'undeleterevisions'         => '$1 {{PLURAL:$1|rêvîzyonek çû|rêvîzyon çûn}} arşîv',
'undeletehistory'           => 'Eger tu vê rûpelê dîsa çêkê, hemû rêvîzyon ê dîsa di dîrokê da werin çêkirin. Eger rûpeleka nuh ji dema jêbirinê da hatîye çêkirin, ew rêvîzyon ê werin pêşî diroka nuh.',
'undelete-revision'         => 'Rêvîzyonên jêbirî yê $1 (di $2) ji $3:',
'undelete-nodiff'           => 'Guhertoyên berê nehatin dîtin.',
'undeletebtn'               => 'Dîsa çêke!',
'undeletelink'              => 'dîtin/dîsa çêkirin',
'undeleteviewlink'          => 'bibîne:',
'undeletereset'             => 'Nû bike',
'undeleteinvert'            => 'Hilbijartinê şûnde vegerîne',
'undeletecomment'           => 'Sedem:',
'undeletedarticle'          => '"[[$1]]" dîsa çêkir',
'undeletedrevisions'        => '{{PLURAL:$1|Verzyonek dîsa hate|$1 verzyon dîsa hatin}} çêkirin',
'undeletedrevisions-files'  => '{{PLURAL:$1|Verzyonek|$1 verzyon}} û {{PLURAL:$2|medyayek hate|$2 medya hatin}} çêkirin',
'undeletedfiles'            => '{{PLURAL:$1|Medyayek hate|$1 medya hatin}} çêkirin',
'undeletedpage'             => "'''$1 dîsa hate çêkirin'''

Ji bo jêbirinan û çêkirinên nuh ra, xêra xwe di [[Special:Log/delete|reşahîya jêbirinê]] da seke.",
'undelete-header'           => '[[Special:Log/delete|Reşahîya jêbirinê]] bibîne ji bo rûpelên jêbirî.',
'undelete-search-box'       => 'Rûpelên jêbirî lêbigere',
'undelete-search-prefix'    => 'Rûpela pêşe min ke ê bi vê destpêdîkin:',
'undelete-search-submit'    => 'Lêbigere',
'undelete-show-file-submit' => 'Erê',

# Namespace form on various pages
'namespace'      => 'Valahiya nav:',
'invert'         => 'Hilbijardinê pêçewane bike',
'blanknamespace' => '(Sereke)',

# Contributions
'contributions'       => 'Beşdariyên bikarhêner',
'contributions-title' => 'Beşdariyên ji bo $1',
'mycontris'           => 'Beşdariyên min',
'contribsub2'         => 'Ji bo $1 ($2)',
'uctop'               => '(ser)',
'month'               => 'Ji mehê (û zûtir):',
'year'                => 'Ji salê (û zûtir):',

'sp-contributions-newbies'       => 'Tenê beşdariyên bikarhênerên nû nîşan bide',
'sp-contributions-newbies-sub'   => 'Ji bikarhênerên nû re',
'sp-contributions-newbies-title' => 'Tevkariyên bikarhêner ji bo hesabên nû',
'sp-contributions-blocklog'      => 'Astengkirina bikarhêner',
'sp-contributions-deleted'       => 'Guherandinên bikarhênerekî yê jêbirî',
'sp-contributions-uploads'       => 'yên barkirî',
'sp-contributions-logs'          => 'têketin',
'sp-contributions-talk'          => 'nîqaş',
'sp-contributions-userrights'    => 'Îdarekirina mafên bikarhêneran',
'sp-contributions-search'        => 'Li beşdariyan bigere',
'sp-contributions-username'      => 'Adresê IP ya navî bikarhêner:',
'sp-contributions-submit'        => 'Lêbigere',

# What links here
'whatlinkshere'            => 'Girêdanên li ser vê rûpelê',
'whatlinkshere-title'      => 'Rûpelan, yê berve $1 tên',
'whatlinkshere-page'       => 'Rûpel:',
'linkshere'                => "Ev rûpel tên ser vê rûpelê '''„[[:$1]]“''':",
'nolinkshere'              => "Ne ji rûpelekê lînk tên ser '''„[[:$1]]“'''.",
'nolinkshere-ns'           => "Ne lînkek berve '''[[:$1]]''' di vê namespace'a da tê.",
'isredirect'               => 'rûpelê beralî bike',
'istemplate'               => 'tê bikaranîn',
'isimage'                  => 'lînka wêneyê',
'whatlinkshere-prev'       => '{{PLURAL:$1|yê|$1 yên}} berê',
'whatlinkshere-next'       => '{{PLURAL:$1|yê|$1 yên}} din',
'whatlinkshere-links'      => '← girêdan',
'whatlinkshere-hideredirs' => '$1 beralîkirin',
'whatlinkshere-hidelinks'  => '$1 lînkan',
'whatlinkshere-hideimages' => '$1 lînkên wêneyan',
'whatlinkshere-filters'    => 'Parzûn',

# Block/unblock
'blockip'                     => 'Bikarhêner asteng bike',
'blockip-title'               => 'Bikarhêner asteng bike',
'blockip-legend'              => 'Bikarhêner asteng bike',
'blockiptext'                 => 'Ji bo astengkirina nivîsandinê ji navnîşaneke IP an bi navekî bikarhêner, vê formê bikarbîne.
Ev bes gireke were bikaranîn ji bo vandalîzmê biskinîne (bi vê [[{{MediaWiki:Policy-url}}|qebûlkirinê]]).

Sedemekê binivîse!',
'ipaddress'                   => "adresê IP'yekê",
'ipadressorusername'          => "adresê IP'yekê ya navekî bikarhênerekî",
'ipbexpiry'                   => 'Dem:',
'ipbreason'                   => 'Sedem',
'ipbreasonotherlist'          => 'Sedemekî din',
'ipbreason-dropdown'          => '*Sedemên astengkirinê
** vandalîzm
** agahiya şaş kire gotarekê
** naveroka rûpelekê vala kir
** girêdanên xerab tevlî rûpelan dikir
** tiştên tewş dikir gotaran
** heqaretkirin
** pir hesab bikaranîn
** navekî ku nayê pejirandin',
'ipbanononly'                 => 'Bes bikarhênerî veşartî astengbike (bikarhênerên qeydkirî bi vê IP-adresê ne tên astengkirin).',
'ipbcreateaccount'            => 'Çêkirina hesaban qedexe bike',
'ipbemailban'                 => 'Ji bo şandina e-nameyan qedexe bike.',
'ipbenableautoblock'          => "Otomatîk IP'yên niha û yên nuh yê vê bikarhênerê astengbike.",
'ipbsubmit'                   => 'Vê bikarhêner asteng bike',
'ipbother'                    => 'Demekî din:',
'ipboptions'                  => '1 seet:1 hour,2 seet:2 hours,6 seet:6 hours,1 roj:1 day,3 roj:3 days,1 hefte:1 week,2 hefte:2 weeks,1 mihe:1 month,3 mihe:3 months,1 sal:1 year,ji her demê ra:infinite',
'ipbotheroption'              => 'yên din',
'ipbotherreason'              => 'Sedemekî din',
'ipbhidename'                 => 'Navê bikarhêner / adresê IP ji "pirtûkê" astengkirinê, lîsteya astengkirinên nuh û lîsteya bikarhêneran veşêre',
'ipbwatchuser'                => 'Rûpelên bikarhêner û guftûgoyê bişopîne',
'badipaddress'                => 'Bikarhêner bi vî navî tune',
'blockipsuccesssub'           => 'Astengkirin serkeftî bû',
'blockipsuccesstext'          => '"$1" hat asteng kirin.
<br />Bibîne [[Special:IPBlockList|Lîsteya IP\'yan hatî asteng kirin]] ji bo lîsteya blokan.',
'ipb-edit-dropdown'           => 'Sedemên astengkirinê',
'ipb-unblock-addr'            => 'Astengkirinê $1 rake',
'ipb-unblock'                 => "Astengkirina bikarhênerekî ya adrêsa IP'yekê rake",
'ipb-blocklist'               => 'Astengkirinên niha bibîne',
'ipb-blocklist-contribs'      => 'Beşdariyên ji bo $1',
'unblockip'                   => "IP'yekê dîsa veke",
'unblockiptext'               => "Nivîsara jêr bikarwîne ji bo qebûlkirina nivîsandinê bikarhênerekî ya IP'yeka berê astengkirî.",
'ipusubmit'                   => 'Astengkirina vê adrêsê rake',
'unblocked'                   => '[[User:$1|$1]] niha vê astengkirinê ye',
'unblocked-id'                => '$1 dîsa vê astengkirinê ye',
'ipblocklist'                 => "Listek ji adresên IP'yan û bikarhêneran yê hatine astengkirin",
'ipblocklist-legend'          => 'Bikarhênerekî astengkirî bibîne',
'ipblocklist-username'        => "Navî bikarhêner ya adrêsa IP'yê:",
'ipblocklist-sh-tempblocks'   => 'Astengkirinên niha $1',
'ipblocklist-submit'          => 'Lêbigere',
'ipblocklist-localblock'      => 'Astengkirina herêmî',
'ipblocklist-otherblocks'     => '{{PLURAL:$1|Astengkirin|Astengkirinên}} din',
'blocklistline'               => '$1, $2 $3 asteng kir ($4)',
'infiniteblock'               => 'ji her demê ra',
'expiringblock'               => 'heta $1 $2',
'anononlyblock'               => 'bes kesên netên zanîn',
'noautoblockblock'            => 'astengkirina otomatîk hatîye temirandin',
'createaccountblock'          => 'çêkirina hesaban hate qedexekirin',
'emailblock'                  => 'E-Mail hate girtin',
'blocklist-nousertalk'        => 'nikarê rûpela gûftugoya xwe biguherînê',
'ipblocklist-empty'           => 'Lîsteya astengkirinê vala ye.',
'ipblocklist-no-results'      => "Ew IP'ya ya bikarhênera nehatîye astengkirin.",
'blocklink'                   => 'asteng bike',
'unblocklink'                 => 'astengkirinê rake',
'change-blocklink'            => 'Astengkirinê biguherîne',
'contribslink'                => 'beşdarî',
'autoblocker'                 => 'Otomatîk hat bestin jiberku IP-ya we û ya "[[User:$1|$1]]" yek in. Sedem: "\'\'\'$2\'\'\'"',
'blocklogpage'                => 'Astengkirina bikarhêner',
'blocklogentry'               => '"[[$1]]" ji bo dema $2 $3 hatîye asteng kirin',
'blocklogtext'                => "Ev reşahîyek ji astengkirinên û rakirina astengkirinên bikarhêneran ra ye. Adrêsên IP'yan, yê otomatîk hatine astengkirin, nehatine nivîsandin. [[Special:IPBlockList|Lîsteya IP'yên astengkirî]] bibîne ji bo dîtina astengkirinên IP'yan.",
'unblocklogentry'             => 'astenga "$1" betalkir',
'block-log-flags-anononly'    => 'bes bikarhênerên neqeydkirî',
'block-log-flags-nocreate'    => 'çêkirina hesaban hate qedexekirin',
'block-log-flags-noautoblock' => 'astengkirina otomatik tune',
'block-log-flags-noemail'     => 'Şandina e-nameyan hatîye qedexekirin',
'block-log-flags-nousertalk'  => 'nikare guftûgoyê xwe biguherîne',
'block-log-flags-hiddenname'  => 'navê bikarhêneriyê yê veşartî',
'ipb_expiry_invalid'          => 'Dem ne serrast e.',
'ipb_already_blocked'         => '"$1" berê hatîye astengkirin',
'ipb-needreblock'             => '== Hatîye astengkirin ==
$1 berê hatîye astengkirin. Tu dixazî astengkirinê biguherînê?',
'ipb_cant_unblock'            => "Şaşbûn: ID'ya astengkirinê $1 nehate dîtin. Astengkirinê xwe niha belkî hatîye rakirin.",
'blockme'                     => 'Min astengbike',
'proxyblocksuccess'           => 'Çêbû.',
'sorbsreason'                 => "Adrêsa IP ya te ji DNSBL'a {{SITENAME}} wek proxy'eka vekirî tê naskirin.",
'sorbs_create_account_reason' => "Adrêsa IP ya te ji DNSBL'a {{SITENAME}} wek proxy'eka vekirî tê naskirin. Tu nikarê account'ekê ji xwe ra çêkê.",

# Developer tools
'lockdb'            => 'Danegehê bigire',
'unlockdb'          => 'Danegehê veke',
'lockconfirm'       => 'Erê, ez bi rastî dixwazim danegehê bigirim.',
'unlockconfirm'     => 'Erê, ez bi rastî dixwazim danegehê vekim.',
'lockbtn'           => 'Danegehê bigire',
'unlockbtn'         => 'Danegehê veke',
'databasenotlocked' => 'Danegeh ne girtî ye.',

# Move page
'move-page'                 => '$1 bigerîne',
'move-page-legend'          => 'Vê rûpelê bigerîne',
'movepagetalktext'          => "Rûpela '''guftûgoyê''' vê rûpelê wê were, eger hebe, gerandin. Lê ev tişta nameşe, eger

*berê guftûgoyek bi wê navê hebe ya
*tu tiştekî jêr hilbijêrê.

Eger ev mişkla çêbû, tu gireke vê rûpelê bi xwe bigerînê.

Xêra xwe navî nuh û sedemê navgerandinê binivisîne.",
'movearticle'               => 'Rûpelê bigerîne',
'movenologin'               => 'Xwe qeyd nekir',
'movenologintext'           => 'Tu dive bikarhênereke qeydkirî bî û [[Special:UserLogin|werî nav sîstemê]]
da bikarî navê wê rûpelê biguherînî.',
'movenotallowed'            => 'Mafên te bo guherandina navên gotaran tune ye.',
'movenotallowedfile'        => "Mafê te bo guherandina navên data'yan tune ye.",
'cant-move-user-page'       => 'Mafê te bo guherandina navên rûpelên bikarhêneran tune ye.',
'cant-move-to-user-page'    => 'Mafê te bo guherandina navên rûpelan berve rûpelên bikarhêneran da tune ye.',
'newtitle'                  => 'Sernivîsa nû',
'move-watch'                => 'Vê rûpelê bişopîne',
'movepagebtn'               => 'Vê rûpelê bigerîne',
'pagemovedsub'              => 'Gerandin serkeftî',
'movepage-moved'            => '\'\'\'"$1" çû cihê "$2"\'\'\'',
'movepage-moved-redirect'   => 'Beralîkirinek hate çêkirin.',
'movepage-moved-noredirect' => 'Beralîkirin nehate çêkirin.',
'articleexists'             => 'Rûpela bi vî navî heye, an navê ku te hilbijart derbas nabe. Navekî din hilbijêre.',
'cantmove-titleprotected'   => 'Tu nikanê vê rûpelê bervê vê cihê bigerînê ji ber ku sernava nuh tê parastin ji bo çêkirinê',
'movedto'                   => 'bû',
'movetalk'                  => "Rûpela '''guftûgo''' ya wê jî bigerîne, eger gengaz be.",
'movepage-page-exists'      => 'Rûpela $1 berê heye û ew nikane otomatîk were jêbirin.',
'movepage-page-moved'       => 'Rûpela $1 çû cihê $2.',
'movepage-page-unmoved'     => 'Rûpela $1 nikanî çûba ciha $2.',
'1movedto2'                 => '[[$1]] çû cihê [[$2]]',
'1movedto2_redir'           => '[[$1]] çû cihê [[$2]] ser redirect',
'movelogpage'               => 'Nav guherandin',
'movelogpagetext'           => 'Li jêr lîsteyek ji rûpelan ku navê wan hatiye guherandin heye.',
'movereason'                => 'Sedem',
'revertmove'                => 'şûnde vegerîne',
'delete_and_move'           => 'Jêbibe û nav biguherîne',
'delete_and_move_text'      => '== Jêbirin gireke ==

Rûpela "[[:$1]]" berê heye. Tu rast dixazê wê jêbibê ji bo navguherandinê ra?',
'delete_and_move_confirm'   => 'Erê, wê rûpelê jêbibe',
'delete_and_move_reason'    => 'Jêbir ji bo navguherandinê',
'immobile-source-page'      => 'Navê vê rûpelê nikare were guherandin.',

# Export
'export'          => 'Rûpelan eksport bike',
'export-addcat'   => 'Zêde bike',
'export-addns'    => 'Zêde bike',
'export-download' => 'Weka dosyeyê qeyd bike',

# Namespace 8 related
'allmessages'                   => 'Hemû mesajên sîstemê',
'allmessagesname'               => 'Nav',
'allmessagescurrent'            => 'Nivîsa niha',
'allmessagestext'               => 'Ev lîsteya hemû mesajên di namespace a MediaWiki: de ye.',
'allmessages-filter-legend'     => 'Parzûn',
'allmessages-filter-unmodified' => 'Neguhertî',
'allmessages-filter-all'        => 'hemû',
'allmessages-filter-modified'   => 'Guhertî',
'allmessages-language'          => 'Ziman',
'allmessages-filter-submit'     => 'Gotar',

# Thumbnails
'thumbnail-more' => 'Mezin bike',
'filemissing'    => 'Rûpel tune',

# Special:Import
'import'                  => 'Rûpelan wîne (import)',
'import-interwiki-submit' => 'Tevlî bike',
'import-upload-filename'  => 'Navê pelê:',
'import-comment'          => 'Şîrove:',
'importtext'              => 'Please export the file from the source wiki using the {{ns:special}}:Export utility, save it to your disk and upload it here.',
'importstart'             => 'Rûpel tên împortkirin...',
'importnopages'           => 'Ne rûpelek ji împortkirinê ra heye.',
'importfailed'            => 'Împort nebû: $1',
'importbadinterwiki'      => 'Interwiki-lînkekî xerab',
'importnotext'            => 'Vala an nivîs tune',
'importsuccess'           => 'Împort çêbû!',

# Import log
'importlogpage' => 'Têketina tevlîkirinê',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Rûpela min',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Rûpela guftûgo ya min',
'tooltip-pt-preferences'          => 'Tercîhên min',
'tooltip-pt-watchlist'            => 'The list of pages you',
'tooltip-pt-mycontris'            => 'Lîsteya beşdariyên min',
'tooltip-pt-logout'               => 'Derkeve (Log out)',
'tooltip-ca-talk'                 => 'Guftûgo li ser rûpela naverokê',
'tooltip-ca-edit'                 => 'Vê rûpelê biguherîne! Berê qeydkirinê bişkoka "Pêşdîtin',
'tooltip-ca-addsection'           => 'Beşekê zêde bike.',
'tooltip-ca-viewsource'           => 'Ev rûpela tê parastin. Tu dikarê bes li çavkanîyê sekê.',
'tooltip-ca-history'              => 'Versyonên berê yên vê rûpelê.',
'tooltip-ca-protect'              => 'Vê rûpelê biparêze',
'tooltip-ca-unprotect'            => 'Parastina vê rûpelê rake',
'tooltip-ca-delete'               => 'Vê rûpelê jê bibe',
'tooltip-ca-move'                 => 'Navekî nû bide vê rûpelê',
'tooltip-ca-watch'                => 'Vê rûpelê têke nav lîsteya te ya şopandinê',
'tooltip-ca-unwatch'              => 'Vê rûpelê ji lîsteya te ya şopandinê rake',
'tooltip-search'                  => 'Li {{SITENAME}} bigere',
'tooltip-search-go'               => 'Here rûpeleke tev bi vî navî, heke hebe',
'tooltip-search-fulltext'         => 'Di nav rûpelan de li vê nivîsê bigerre',
'tooltip-p-logo'                  => 'Biçe Destpêkê',
'tooltip-n-mainpage'              => 'Biçe Destpêkê',
'tooltip-n-mainpage-description'  => 'Biçe Destpêkê',
'tooltip-n-portal'                => 'Agahdarî li ser {{SITENAME}}, tu dikarî çi bikî, tu dikarî çi li ku bîbînî',
'tooltip-n-recentchanges'         => "Lîsteya guherandinên dawî di vê Wîkî'yê da.",
'tooltip-n-randompage'            => 'Rûpelekî helkeft biwêşîne',
'tooltip-n-help'                  => 'Bersivên ji bo pirsên te.',
'tooltip-t-whatlinkshere'         => 'Lîsteya hemû rûpelên ku ji vê re grêdidin.',
'tooltip-t-recentchangeslinked'   => 'Recent changes in pages linking to this page',
'tooltip-feed-rss'                => "RSS feed'ên ji bo rûpelê",
'tooltip-feed-atom'               => "Atom feed'ên ji bo vê rûpelê",
'tooltip-t-contributions'         => 'Lîsteya beşdariyên bikarhêner bibîne',
'tooltip-t-emailuser'             => 'Jê re name bişîne',
'tooltip-t-upload'                => 'Wêneyan bar bike',
'tooltip-t-specialpages'          => 'Lîsteya hemû rûpelên taybetî',
'tooltip-t-print'                 => 'Versiyona çapkirinê ya vê rûpelê',
'tooltip-ca-nstab-main'           => 'Li rûpela naverokê binêre',
'tooltip-ca-nstab-user'           => 'Rûpela bikarhênerê/î temaşe bike',
'tooltip-ca-nstab-special'        => 'This is a special page, you can',
'tooltip-ca-nstab-project'        => 'Li rûpelê projektê seke',
'tooltip-ca-nstab-image'          => 'Rûpela dosyeyê bibîne',
'tooltip-ca-nstab-template'       => 'Şablonê nîşanbide',
'tooltip-ca-nstab-help'           => 'Rûpela alîkariyê bibîne',
'tooltip-ca-nstab-category'       => 'Li rûpelê kategorîyê seke',
'tooltip-minoredit'               => 'Vê guherandinê weka biçûk îşaret bike',
'tooltip-save'                    => 'Guherandinên xwe tomarbike',
'tooltip-preview'                 => 'Guherandinên xwe bibîne, berî ku tu wî qeyd bikî!',
'tooltip-diff'                    => 'Guherandinên ku te di nivîsê de kirîyî nîşan bide',
'tooltip-compareselectedversions' => 'Cudatiyên guhartoyên hilbijartî yên vê rûpelê bibîne.',
'tooltip-watch'                   => 'Vê rûpelê têke nav lîsteya te ya şopandinê',
'tooltip-upload'                  => 'Barkirinê destpêke',

# Stylesheets
'monobook.css' => '/* CSS placed here will affect users of the Monobook skin */',

# Scripts
'common.js' => '/* Any JavaScript here will be loaded for all users on every page load. */',

# Attribution
'anonymous' => 'Bikarhênera/ê nediyarkirî ya/yê {{SITENAME}}',
'siteuser'  => 'Bikarhênera/ê $1 a/ê {{SITENAME}}',
'others'    => 'ên din',
'siteusers' => 'Bikarhênerên $1 yên {{SITENAME}}',

# Spam protection
'spamprotectiontitle' => 'Parastina spam',
'spamprotectiontext'  => 'Ew rûpela yê tu dixast tomarbikê hate astengkirin ji ber ku parastina spam. Ew çêbû ji ber ku lînkekî derva di vê rûpelê da ye.',
'spamprotectionmatch' => 'Ev nivîsa parastinê spam vêxist: $1',

# Info page
'numedits'     => 'Hejmara guherandinan (rûpel): $1',
'numtalkedits' => 'Hejmara guherandinan (guftûgo): $1',
'numwatchers'  => 'Hejmara kesên dişopînin: $1',

# Math options
'mw_math_png'    => 'Her caran wek PNG nîşanbide',
'mw_math_simple' => 'HTML eger asan be, wekî din PNG',
'mw_math_html'   => 'HTML eger bibe, wekî din PNG',
'mw_math_source' => "Wek TeX bêle (ji browser'ên gotaran ra)",
'mw_math_modern' => "Baştir e ji browser'ên nuhtir",
'mw_math_mathml' => 'MathML eger bibe (ceribandin)',

# Math errors
'math_unknown_error' => 'şaşbûnekî nezanîn',
'math_image_error'   => 'Wêşandana PNG nemeşî',

# Patrolling
'markaspatrolleddiff'   => 'Wek serrastkirî nîşanbide',
'markaspatrolledtext'   => 'Vê rûpelê wek serrastkirî nîşanbide',
'markedaspatrolled'     => 'Wek serrastkirî tê nîşandan',
'markedaspatrolledtext' => 'Guherandina rûpelê wek serrastkirî tê nîşandan.',

# Patrol log
'patrol-log-page' => 'Têketina kontrolkirinê',
'patrol-log-line' => '$1 ji $2 hate kontrolkirin $3',
'patrol-log-auto' => '(otomatîk)',
'patrol-log-diff' => 'revîzyona $1',

# Image deletion
'deletedrevision'                 => 'Guhertoya berê $1 hate jêbirin.',
'filedelete-missing'              => 'Data\'yê "$1" nikane were jêbirin, ji ber ku ew tune.',
'filedelete-current-unregistered' => 'Datayê "$1" di sistêmê da tune.',

# Browsing diffs
'previousdiff' => '← Ciyawaziya pêştir',
'nextdiff'     => 'Ciyawaziya paştir →',

# Media information
'thumbsize'            => 'Mezinahiya wêne:',
'widthheight'          => '$1 x $2',
'widthheightpage'      => '$1 × $2, $3 rûpel',
'file-info'            => 'mezinbûnê data: $1, MIME-typ: $2',
'file-info-size'       => '$1 × $2 pixel, mezinbûnê data: $3, MIME-typ: $4',
'file-nohires'         => '<small>Versyonekî jê mezintir tune.</small>',
'svg-long-desc'        => "Data'ya SVG, mezinbûna rast: $1 × $2 pixel; mezinbûna data'yê: $3",
'show-big-image'       => 'Mezînbûn',
'show-big-image-thumb' => '<small>Mezinbûna vê pêşnîşandanê: $1 × $2 pixel</small>',

# Special:NewFiles
'newimages'             => 'Pêşangeha wêneyên nû',
'imagelisttext'         => "Jêr lîsteyek ji $1 file'an heye, duxrekirin $2.",
'newimages-summary'     => 'Ev rûpela taybet dosyeyên ku herî dawî hatine barkirin, nîşan dide.',
'newimages-legend'      => 'Parzûn',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Ne tiştek tê dîtin.',
'ilsubmit'              => 'Lêbigere',
'bydate'                => 'li gor dîrokê',
'sp-newimages-showfrom' => "Data'yên nuh ji dema $1, saet $2 da bibîne",

# Variants for Kurdish language
'variantname-ku-arab' => 'tîpên erebî',
'variantname-ku-latn' => 'tîpên latînî',
'variantname-ku'      => 'disable',

# Metadata
'metadata'          => "Data'yên meta",
'metadata-expand'   => 'Detayên dirêj nîşan bide',
'metadata-collapse' => 'Detayên dirêj veşêre',

# EXIF tags
'exif-imagewidth'                  => 'Panbûn',
'exif-imagelength'                 => 'Dirêjbûn',
'exif-jpeginterchangeformatlength' => "Byte'ên daneya JPEG",
'exif-imagedescription'            => 'Navî wêneyê',
'exif-model'                       => 'Modela kamerayê',
'exif-artist'                      => 'Nûser',
'exif-pixelydimension'             => 'Firehiya wêne',
'exif-pixelxdimension'             => 'Dirêjiya wêne',
'exif-usercomment'                 => 'Şîroveyên bikarhêner',
'exif-exposuretime-format'         => '$1 sanî ($2)',
'exif-brightnessvalue'             => 'Zelalî',
'exif-flash'                       => 'Flaş',
'exif-filesource'                  => 'Çavkaniya pelê',
'exif-contrast'                    => 'Kontrast',
'exif-objectname'                  => 'Sernavê kurt',

'exif-unknowndate' => 'Dîroka nayê zanîn',

'exif-orientation-1' => 'Normal',

'exif-exposureprogram-2' => 'Programa normal',

'exif-meteringmode-0'   => 'Nenas',
'exif-meteringmode-255' => 'Yên din',

'exif-lightsource-0'  => 'Nenas',
'exif-lightsource-9'  => 'Hewaya baş',
'exif-lightsource-10' => 'Hewaya bi ewr',

'exif-scenecapturetype-0' => 'Standart',
'exif-scenecapturetype-1' => 'Bergeh',
'exif-scenecapturetype-2' => 'Portre',

'exif-gaincontrol-0' => 'Nîne',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Nerm',

'exif-saturation-0' => 'Normal',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Nerm',

'exif-subjectdistancerange-0' => 'Nenas',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-m' => 'Serê saetê mîl',

# External editor support
'edit-externally-help' => '(Ji bo agahîyên zav [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] li vir binêre)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'hemû',
'imagelistall'     => 'hemû',
'watchlistall2'    => 'hemû',
'namespacesall'    => 'Hemû',
'monthsall'        => 'giştik',
'limitall'         => 'hemû',

# E-mail address confirmation
'confirmemail'          => 'Adrêsa e-nameyan nasbike',
'confirmemail_noemail'  => 'Te e-mail-adressê xwe di [[Special:Preferences|tercihên xwe da]] nenivîsandîye.',
'confirmemail_success'  => 'E-Mail adrêsa te hate naskirin. Tu niha dikarî xwe qeydbikê û kêfkê.',
'confirmemail_loggedin' => 'Adrêsa te yê E-Mail hate qebûlkirin.',
'confirmemail_body'     => 'Kesek, dibê tu, bi IP adressê $1, xwe li {{SITENAME}} bi vê navnîşana e-name tomar kir ("$2") .

Eger ev rast qeydkirinê te ye û di dixwazî bikaranîna e-nama ji te ra çêbibe li {{SITENAME}}, li vê lînkê bitikîne:

$3

Lê eger ev *ne* tu bû, li lînkê netikîne. Ev e-nameya di rojê $4 da netê qebûlkirin.',

# Scary transclusion
'scarytranscludefailed'  => '[Anîna şablona $1 biserneket; biborîne]',
'scarytranscludetoolong' => '[URL zêde dirêj e; bibore]',

# Trackbacks
'trackbackremove' => '([$1 jêbibe])',

# Delete conflict
'deletedwhileediting' => 'Hîşyar: Piştî te guherandinê xwe dest pê kir ev rûpela hate jêbirin!',
'confirmrecreate'     => "Bikarhêner [[User:$1|$1]] ([[User talk:$1|nîqaş]]) vê rûpelê jêbir, piştî te destpêkir bi guherandinê. Sedemê jêbirinê ev bû:
: ''$2''
Xêra xwe zanibe ku tu bi rastî dixwazê vê rûpelê dîsa çêkê",
'recreate'            => 'Dîsa çêke',

# action=purge
'confirm_purge_button' => 'Temam',
'confirm-purge-top'    => 'Bîra vê rûpelê jêbîbe ?',

# Multipage image navigation
'imgmultipageprev' => '← rûpela berî vê',
'imgmultipagenext' => 'rûpela din →',
'imgmultigo'       => 'Here!',
'imgmultigoto'     => 'Here rûpela $1',

# Table pager
'table_pager_next'         => 'Rûpelê din',
'table_pager_prev'         => 'Rûpelê berî',
'table_pager_first'        => 'Rûpelê yekemîn',
'table_pager_last'         => 'Rûpelê dawî',
'table_pager_limit_submit' => 'Biçe',

# Auto-summaries
'autosumm-blank'   => 'Rûpel hate vala kirin',
'autosumm-replace' => "'$1' ket şûna rûpelê.",
'autoredircomment' => 'ji bo [[$1]] hate beralîkirin',
'autosumm-new'     => 'Rûpela nû: $1',

# Live preview
'livepreview-loading' => 'Tê…',
'livepreview-ready'   => 'Bar dibe… Amade ye!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Xeyrandin yê piştî $1 sanîyan hatine çêkirin belkî netên wêşendan.',
'lag-warn-high'   => 'Ji bo westinê sistêmê ew xeyrandin, yê piştî $1 sanîyan hatine çêkirin netên wêşendan.',

# Watchlist editor
'watchlistedit-numitems'      => 'Di lîsteya te ya şopandinê de {{PLURAL:$1|gotarek heye.|$1 gotar hene.}} (ji xeynî rûpela guftûgoyan).',
'watchlistedit-noitems'       => 'Di lîsteya te ya şopandinê  de gotar tune ne.',
'watchlistedit-normal-title'  => 'Lîsteya xwe ya şopandinê biguherîne',
'watchlistedit-normal-legend' => 'Gotaran ji lîsteya min ya şopandinê rake',
'watchlistedit-normal-submit' => 'Gotaran jê bibe',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 gotar hate|$1 gotaran hatin}} jêbirin ji lîsteya te yê şopandinê:',
'watchlistedit-raw-titles'    => 'Sernav:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 gotar hate|$1 gotar hatin}} jêbirin:',

# Watchlist editing tools
'watchlisttools-edit' => 'Lîsteya şopandinê bibîne û biguherîne',

# Special:Version
'version'                  => 'Versiyon',
'version-specialpages'     => 'Rûpelên taybet',
'version-other'            => 'yên din',
'version-version'          => ' (Verzîyon $1)',
'version-license'          => 'Destûr',
'version-software-product' => 'Berhem',
'version-software-version' => 'Versiyon',

# Special:FilePath
'filepath-page'   => 'Wêne:',
'filepath-submit' => 'Gotar',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Navê dosyeyê:',
'fileduplicatesearch-submit'   => 'Lêbigere',

# Special:SpecialPages
'specialpages'                 => 'Rûpelên taybet',
'specialpages-note'            => '----
* Rûpelên taybetî ji her kesan ra
* <strong class="mw-specialpagerestricted">Rûpelên taybetî ji bikarhêneran bi mafên zêdetir ra</strong>',
'specialpages-group-other'     => 'Rûpelên taybetî yên din',
'specialpages-group-login'     => 'Têkeve',
'specialpages-group-changes'   => 'Guherandinên dawî û reşahîyan',
'specialpages-group-media'     => 'Nameyên medyayan û barkirinan',
'specialpages-group-users'     => 'Bikarhêner û maf',
'specialpages-group-pages'     => 'Lîstên rûpelan',
'specialpages-group-pagetools' => 'Amûrên rûpelê',

# Special:BlankPage
'blankpage' => 'Rûpela vala',

# Special:Tags
'tag-filter-submit' => 'Parzûn',
'tags-title'        => 'Nîşankirin',
'tags-tag'          => 'Tag name',
'tags-edit'         => 'biguherîne',

# Special:ComparePages
'comparepages'     => 'Rûpelan bide ber hev',
'compare-selector' => 'Guhertoyên rûpelan bide ber hev',
'compare-page1'    => 'Rûpel 1',
'compare-page2'    => 'Rûpel 2',
'compare-rev1'     => 'Revîzyon 1',
'compare-rev2'     => 'Revîzyon 2',
'compare-submit'   => 'Bide berhev',

# Database error messages
'dberr-header' => "Problemeka vê wiki'yê heye.",

# HTML forms
'htmlform-submit'              => 'Tomar bike',
'htmlform-reset'               => 'Guherandinan vegerîne',
'htmlform-selectorother-other' => 'Yên din',

);
