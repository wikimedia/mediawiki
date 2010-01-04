<?php
/** Swahili (Kiswahili)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Lloffiwr
 * @author Malangali
 * @author Marcos
 * @author Muddyb Blast Producer
 * @author Robert Ullmann
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Faili',
	NS_SPECIAL          => 'Maalum',
	NS_TALK             => 'Majadiliano',
	NS_USER             => 'Mtumiaji',
	NS_USER_TALK        => 'Majadiliano_ya_mtumiaji',
	NS_PROJECT_TALK     => 'Majadiliano_ya_$1',
	NS_FILE             => 'Picha',
	NS_FILE_TALK        => 'Majadiliano_ya_faili',
	NS_MEDIAWIKI_TALK   => 'Majadiliano_ya_MediaWiki',
	NS_TEMPLATE         => 'Kigezo',
	NS_TEMPLATE_TALK    => 'Majadiliano_ya_kigezo',
	NS_HELP             => 'Msaada',
	NS_HELP_TALK        => 'Majadiliano_ya_msaada',
	NS_CATEGORY         => 'Jamii',
	NS_CATEGORY_TALK    => 'Majadiliano_ya_jamii',
);

$namespaceAliases = array(
	'$1_majadiliano'        => NS_PROJECT_TALK,
	'Majadiliano_faili'     => NS_FILE_TALK,
	'MediaWiki_majadiliano' => NS_MEDIAWIKI_TALK,
	'Kigezo_majadiliano'    => NS_TEMPLATE_TALK,
	'Msaada_majadiliano'    => NS_HELP_TALK,
	'Jamii_majadiliano'     => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ElekezoMbili' ),
	'BrokenRedirects'           => array( 'ElekezoIliovunjika' ),
	'Disambiguations'           => array( 'Maana' ),
	'Userlogin'                 => array( 'IngiaMtumiaji' ),
	'Userlogout'                => array( 'TokaMtumiaji' ),
	'CreateAccount'             => array( 'SajiliAkaunti' ),
	'Preferences'               => array( 'Mapendekezo' ),
	'Watchlist'                 => array( 'Maangalizi' ),
	'Recentchanges'             => array( 'MabadalikoyaKaribuni' ),
	'Upload'                    => array( 'Pakia' ),
	'Listfiles'                 => array( 'OrodhayaFali', 'OrodhayaPicha' ),
	'Newimages'                 => array( 'FailiMpya', 'FailimpyazaPicha' ),
	'Listusers'                 => array( 'OrodhayaWatumiaji', 'OrodhayaMtumiaji' ),
	'Listgrouprights'           => array( 'OrodhayaWasimamizi' ),
	'Statistics'                => array( 'Takwimu' ),
	'Randompage'                => array( 'UkurasawaBahati' ),
	'Lonelypages'               => array( 'KurasaPweke' ),
	'Uncategorizedpages'        => array( 'KurasaZisizonajamii' ),
	'Uncategorizedcategories'   => array( 'JamiiZisizopangwa' ),
	'Uncategorizedimages'       => array( 'FailiZisizonajamii' ),
	'Uncategorizedtemplates'    => array( 'VigezoVisivyonajamii' ),
	'Unusedcategories'          => array( 'JamiiZisizotumika' ),
	'Unusedimages'              => array( 'FailiZisizotumika', 'PichaZisizotumika' ),
	'Wantedpages'               => array( 'KurasaZinazotakikana', 'ViungoVilivyovunjika' ),
	'Wantedcategories'          => array( 'JamiiZinazotakikana' ),
	'Wantedfiles'               => array( 'FailiZinazotakikana' ),
	'Wantedtemplates'           => array( 'VigezoVinavyotakikana' ),
	'Mostlinked'                => array( 'KurasaZilizoungwasana' ),
	'Mostlinkedcategories'      => array( 'JamiiZilizoungwasana' ),
	'Mostlinkedtemplates'       => array( 'VigezoVilivyoungwasana' ),
	'Mostimages'                => array( 'FailiZilizoungwasana', 'PichaZilizoungwasana' ),
	'Mostcategories'            => array( 'JamiiZaidi' ),
	'Mostrevisions'             => array( 'MarejeoZaidi' ),
	'Fewestrevisions'           => array( 'MarejeoMadogo' ),
	'Shortpages'                => array( 'KurasaFupi' ),
	'Longpages'                 => array( 'KurasaNdefu' ),
	'Newpages'                  => array( 'KurasaMpya' ),
	'Ancientpages'              => array( 'KurasazaZamani' ),
	'Deadendpages'              => array( 'KurasaZilizoondoshwa' ),
	'Protectedpages'            => array( 'KurasaZilizolindwa' ),
	'Protectedtitles'           => array( 'JinaLililolindwa' ),
	'Allpages'                  => array( 'KurasaZote' ),
	'Prefixindex'               => array( 'KurasaKuu' ),
	'Ipblocklist'               => array( 'OrodhayaIPZilizozuiliwa' ),
	'Specialpages'              => array( 'KurasaMaalum' ),
	'Contributions'             => array( 'Michango' ),
	'Emailuser'                 => array( 'BaruapepeyaMtumiaji' ),
	'Confirmemail'              => array( 'ThibitishaBaruapepe' ),
	'Whatlinkshere'             => array( 'VingoViungavyoUkurasahuu' ),
	'Movepage'                  => array( 'HamishaKurasa' ),
	'Blockme'                   => array( 'Nizuiye' ),
	'Booksources'               => array( 'ZuiaChanzo' ),
	'Categories'                => array( 'Jamii' ),
	'Export'                    => array( 'Toa' ),
	'Version'                   => array( 'Toleo' ),
	'Allmessages'               => array( 'UjumbeZote' ),
	'Log'                       => array( 'Kumbukumbu' ),
	'Blockip'                   => array( 'Zui', 'ZuiaIP', 'ZuiaMtumiaji' ),
	'Undelete'                  => array( 'Usifute' ),
	'Import'                    => array( 'Ingiza' ),
	'Lockdb'                    => array( 'FungaDB' ),
	'Unlockdb'                  => array( 'FunguaDB' ),
	'Userrights'                => array( 'HakizaMtumiaji' ),
	'MIMEsearch'                => array( 'TafutaMIME' ),
	'Mypage'                    => array( 'KurasaYangu' ),
	'Mytalk'                    => array( 'MajadilianoYangu' ),
	'Mycontributions'           => array( 'MichangoYangu' ),
	'Listadmins'                => array( 'OrodhayaWakabidhi' ),
	'Listbots'                  => array( 'OrodhayaVikaragosi' ),
	'Popularpages'              => array( 'KurasaMaarufu' ),
	'Search'                    => array( 'Tafuta' ),
	'Blankpage'                 => array( 'KurasaTupu' ),
	'LinkSearch'                => array( 'TafutaKiungo' ),
	'DeletedContributions'      => array( 'MichangoIliyofutwa' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Wekea mstari viungo:',
'tog-highlightbroken'         => 'Muundo wa viungo vilivyovunjika <a href="" class="mpya">kama hii</a> (badala yake: kama hii<a href="" class="kwa ndani">?</a>).',
'tog-justify'                 => 'Sawazisha ibara',
'tog-hideminor'               => 'Ficha mabadilisho madogo ya hivi karibuni',
'tog-hidepatrolled'           => 'Ficha kurasa zilizofanyiwa doria kwenye mabadiliko ya karibuni',
'tog-newpageshidepatrolled'   => 'Ficha kurasa zilizofanyiwa doria kwenye orodha ya kurasa mpya',
'tog-extendwatchlist'         => 'Tanua orodha ya maangalizi ili kuonyesha mabadiliko yote yaliyofanyika, si hilo la mwisho tu.',
'tog-usenewrc'                => 'Mabadiliko ya karibuni yenye maelezo mengine (inatumia JavaScript)',
'tog-numberheadings'          => 'Vichwa vya habari vijipange namba-vyenyewe',
'tog-showtoolbar'             => 'Onyesha mwambaa wa zana za kuhariria (JavaScript)',
'tog-editondblclick'          => 'Hariri ukurasa kwa kubonyeza mara mbili',
'tog-editsection'             => 'Wezesha sehemu ya kuandikia kwa kutumia viungo vya [hariri]',
'tog-editsectiononrightclick' => 'Wezesha sehemu ya kuandikia kwa kubonyeza kitufe cha kulia cha puku yako juu ya sehemu ya majina husika (JavaScript)',
'tog-showtoc'                 => 'Onyesha mistari ya yaliyomo (kwa kila kurasa iliyo na zaidi ya vichwa vya habari 3)',
'tog-rememberpassword'        => 'Kumbuka kuingia kwangu katika kompyuta hii',
'tog-editwidth'               => 'Sanduku la kuhariri liwe na upana wa kujaza skrini nzima',
'tog-watchcreations'          => 'Weka kurasa nilizoumba katika maangalizi yangu',
'tog-watchdefault'            => 'Weka kurasa zote nilizohariri katika maangalizi yangu',
'tog-watchmoves'              => 'Weka kurasa zote nilizohamisha katika maangalizi yangu',
'tog-watchdeletion'           => 'Weka kurasa zote nilizofuta katika maangalizi yangu',
'tog-minordefault'            => 'Weka alama zote za mabadiliko madogo kama matumizi mbadala',
'tog-previewontop'            => 'Onyesha mandhari kabla ya sanduku la kuhariria',
'tog-previewonfirst'          => 'Onyesha mandhari unapoanza kuhariri',
'tog-nocache'                 => 'Lemaza mabaki ya kurasa',
'tog-enotifwatchlistpages'    => 'Nitumie barua pepe pale kurasa zilizopo katika maangalizi yangu zikibadilishwa',
'tog-enotifusertalkpages'     => 'Nitumie barua pepe pale ukurasa wangu wa majadiliano ukiwa na mabadiliko',
'tog-enotifminoredits'        => 'Pia nitumie barua pale kurasa za mabadiliko madogo zikiwa zimebadilishwa',
'tog-enotifrevealaddr'        => 'Onyesha anwani ya barua pepe yangu katika barua pepe za taarifa',
'tog-shownumberswatching'     => 'Onyesha idadi ya watumiaji waangalizi',
'tog-oldsig'                  => 'Sahihi jinsi inayoonekana sasa:',
'tog-fancysig'                => 'Weka sahihi tu (bila kujiweka kiungo yenyewe)',
'tog-externaleditor'          => 'Tumia kiharirio cha nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye kompyuta yako)',
'tog-externaldiff'            => 'Tumia diff za nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye kompyuta yako)',
'tog-showjumplinks'           => 'Wezesha "ruka hadi" viungo vya mafikio',
'tog-uselivepreview'          => 'Tumia kihakikio cha papohapo (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Nishtue pale ninapoingiza muhtasari mtupu wa kuhariri',
'tog-watchlisthideown'        => 'Ficha kuhariri kwangu kwenye orodha ya maangalizi',
'tog-watchlisthidebots'       => 'Ficha uhariri wa vikaragosi kwenye orodha ya maangalizi',
'tog-watchlisthideminor'      => 'Ficha mabadliko madogo kwenye maangalizi',
'tog-watchlisthideliu'        => 'Ficha mabadiliko yaliyofanywa na watumiaji kwenye maangalilizi',
'tog-watchlisthideanons'      => 'Ficha mabadiliko yaliyofanywa na watumiaji wasiojisajili kwenye maangalilizi',
'tog-watchlisthidepatrolled'  => 'Ficha maharirio yaliyodoliwa katika maangalizi',
'tog-nolangconversion'        => 'Lemaza mabadiliko kadhaa',
'tog-ccmeonemails'            => 'Nitumie nakala ya barua pepe nitakazo tuma kwa watumiaji wengine',
'tog-diffonly'                => 'Usionyeshe yaliyomo kwenye ukurasa chini ya faili za diff',
'tog-showhiddencats'          => 'Onyesha jamii zilizofichwa',
'tog-noconvertlink'           => 'Lemaza kiungo cha jina la badiliko',
'tog-norollbackdiff'          => 'Ondoa faili za diff baada ya kufanyakazi ya kurejesha',

'underline-always'  => 'Muda wote',
'underline-never'   => 'Kamwe',
'underline-default' => 'Kivinjari mbadala',

# Font style option in Special:Preferences
'editfont-style'     => 'Mtindo wa maandishi kwenye sanduku la kuhariri:',
'editfont-default'   => 'Kivinjari msingi',
'editfont-monospace' => 'Mwandiko wa monospaced',
'editfont-sansserif' => 'Mwandiko wa sans-serif',
'editfont-serif'     => 'Mwandiko wa serif',

# Dates
'sunday'        => 'Jumapili',
'monday'        => 'Jumatatu',
'tuesday'       => 'Jumanne',
'wednesday'     => 'Jumatano',
'thursday'      => 'Alhamisi',
'friday'        => 'Ijumaa',
'saturday'      => 'Jumamosi',
'sun'           => 'Jpili',
'mon'           => 'Jtatu',
'tue'           => 'Jnne',
'wed'           => 'Jtano',
'thu'           => 'Alham',
'fri'           => 'Iju',
'sat'           => 'Jmosi',
'january'       => 'Januari',
'february'      => 'Februari',
'march'         => 'Machi',
'april'         => 'Aprili',
'may_long'      => 'Mei',
'june'          => 'Juni',
'july'          => 'Julai',
'august'        => 'Agosti',
'september'     => 'Septemba',
'october'       => 'Oktoba',
'november'      => 'Novemba',
'december'      => 'Desemba',
'january-gen'   => 'Januari',
'february-gen'  => 'Februari',
'march-gen'     => 'Machi',
'april-gen'     => 'Aprili',
'may-gen'       => 'Mei',
'june-gen'      => 'Juni',
'july-gen'      => 'Julai',
'august-gen'    => 'Agosti',
'september-gen' => 'Septemba',
'october-gen'   => 'Oktoba',
'november-gen'  => 'Novemba',
'december-gen'  => 'Desemba',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Machi',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Juni',
'jul'           => 'Julai',
'aug'           => 'Ago',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Jamii|Jamii}}',
'category_header'                => 'Makala katika jamii "$1"',
'subcategories'                  => 'Vijamii',
'category-media-header'          => 'Picha, video, na sauti katika jamii  "$1"',
'category-empty'                 => "''Jamii hii bado haina ukurasa, picha, video, wala sauti yoyote.''",
'hidden-categories'              => '{{PLURAL:$1|Jamii iliofichwa|Jamii zilizofichwa}}',
'hidden-category-category'       => 'Jamii zilizofichwa',
'category-subcat-count'          => '{{PLURAL:$2|Jamii hii ina kijamii hiki tu.|Jamii hii ina kijamii kifuatacho {{PLURAL:$1|kijamii|$1 vijamii}}, nje ya $2 jumla.}}',
'category-subcat-count-limited'  => 'Jamii hii ina {{PLURAL:$1|kijamii|$1 vijamii}} vifuatavyo.',
'category-article-count'         => '{{PLURAL:$2|Jamii hii ina makala zifuatazo tu.|Kurasa zifuatazo {{PLURAL:$1|ni|$1 kurasa za}} katika jamii hii, nje ya $2 jumla.}}',
'category-article-count-limited' => 'Kurasa zifuatazo {{PLURAL:$1|ni|$1 kurasa za}} katika jamii hii.',
'category-file-count'            => '{{PLURAL:$2|Jamii hii ina faili zifuatazo tu.|Faili zifuatazo {{PLURAL:$1|ni|$1faili za}} katika jamii hii, nje ya $2 jumla.}}',
'category-file-count-limited'    => 'Faili zifuatazo {{PLURAL:$1|file is|$1 ni faili za}} katika jamii hii.',
'listingcontinuesabbrev'         => 'endelea',
'index-category'                 => 'Kurasa kuu',
'noindex-category'               => 'Kurasa zisizokuu',

'mainpagetext'      => "<big>'''MediaWiki imefanikiwa kuingizwa.'''</big>",
'mainpagedocfooter' => 'Shauriana na [http://meta.wikimedia.org/wiki/Help:Contents Mwongozo wa Mtumiaji] kwa habari juu ya utumiaji wa bidhaa pepe ya wiki.

== Msaada wa kianzio ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Orodha ya mipangilio ya msingi]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ ya MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Orodha ya utoaji wa habari za MediaWiki]',

'about'         => 'Kuhusu',
'article'       => 'Makala',
'newwindow'     => '(Itafungua kwa dirisha jipya)',
'cancel'        => 'Batilisha',
'moredotdotdot' => 'Zaidi...',
'mypage'        => 'Ukurasa wangu',
'mytalk'        => 'Majadiliano yangu',
'anontalk'      => 'Majadiliano ya IP hii',
'navigation'    => 'Safari',
'and'           => '&#32;na',

# Cologne Blue skin
'qbfind'         => 'Gundua',
'qbbrowse'       => 'Vinjari',
'qbedit'         => 'Hariri',
'qbpageoptions'  => 'Ukurasa huu',
'qbpageinfo'     => 'Muktadha',
'qbmyoptions'    => 'Kurasa zangu',
'qbspecialpages' => 'Kurasa za pekee',
'faq'            => 'Maswali ya kawaida',
'faqpage'        => 'Project:Maswali Yaulizwayo Marakwamara',

# Vector skin
'vector-action-addsection'   => 'Weka mada',
'vector-action-delete'       => 'Kufuta',
'vector-action-move'         => 'Kusogeza',
'vector-action-protect'      => 'Kulinda',
'vector-action-undelete'     => 'Kurudisha',
'vector-action-unprotect'    => 'Kuondoa tunzo',
'vector-namespace-category'  => 'Jamii',
'vector-namespace-help'      => 'Ukurasa wa msaada',
'vector-namespace-image'     => 'Faili',
'vector-namespace-main'      => 'Ukurasa',
'vector-namespace-media'     => 'Ukurasa wa faili',
'vector-namespace-mediawiki' => 'Ujumbe',
'vector-namespace-project'   => 'Ukurasa wa mradi',
'vector-namespace-special'   => 'Ukurasa maalum',
'vector-namespace-talk'      => 'Majadiliano',
'vector-namespace-template'  => 'Kigezo',
'vector-namespace-user'      => 'Ukurasa wa mtumiaji',
'vector-view-create'         => 'Kuanzisha',
'vector-view-edit'           => 'Kuhariri',
'vector-view-history'        => 'Kufungua historia',
'vector-view-view'           => 'Kusoma',
'vector-view-viewsource'     => 'Kuonyesha kodi',
'actions'                    => 'Vitendo',
'namespaces'                 => 'Maeneo ya wiki',
'variants'                   => 'Vibadala',

# Metadata in edit box
'metadata_help' => 'Data za meta',

'errorpagetitle'    => 'Hitilafu',
'returnto'          => 'Rudia $1.',
'tagline'           => 'Kutoka {{SITENAME}}',
'help'              => 'Msaada',
'search'            => 'Tafuta',
'searchbutton'      => 'Tafuta',
'go'                => 'Nenda',
'searcharticle'     => 'Nenda',
'history'           => 'Historia ya ukurasa',
'history_short'     => 'Historia',
'updatedmarker'     => 'imebadilishwa tangu nilipoutazama mara ya mwisho',
'info_short'        => 'Maarifa',
'printableversion'  => 'Ukurasa wa kuchapika',
'permalink'         => 'Kiungo cha daima',
'print'             => 'Chapisha',
'edit'              => 'Hariri',
'create'            => 'Anzisha kurasa',
'editthispage'      => 'Hariri ukurasa huu',
'create-this-page'  => 'Anzisha ukurasa huu',
'delete'            => 'Futa',
'deletethispage'    => 'Futa ukurasa huo',
'undelete_short'    => 'Rudisha {{PLURAL:$1|rekebisho moja|marekebisho $1}}',
'protect'           => 'Linda',
'protect_change'    => 'badilisha',
'protectthispage'   => 'Linda ukurasa huu',
'unprotect'         => 'Ondoa tunzo',
'unprotectthispage' => 'Ondoa tunzo la ukurasa',
'newpage'           => 'Ukurasa mpya',
'talkpage'          => 'Jadilia ukurasa huu',
'talkpagelinktext'  => 'Majadiliano',
'specialpage'       => 'Ukurasa maalumu',
'personaltools'     => 'Vifaa binafsi',
'postcomment'       => 'Fungu jipya',
'articlepage'       => 'Onyesha kurasa zilizopo',
'talk'              => 'Majadiliano',
'views'             => 'Mitazamo',
'toolbox'           => 'Vifaa',
'userpage'          => 'Ukurasa wa mtumiaji',
'projectpage'       => 'Onyesha ukurasa wa mradi',
'imagepage'         => 'Tazama ukurasa wa faili',
'mediawikipage'     => 'Tazama ukurasa wa ujumbe',
'templatepage'      => 'Onyesha ukurasa wa kigezo',
'viewhelppage'      => 'Tazama ukurasa wa msaada',
'categorypage'      => 'Tazama ukurasa wa jamii',
'viewtalkpage'      => 'Tazama majadiliano',
'otherlanguages'    => 'Lugha nyingine',
'redirectedfrom'    => '(Elekezwa kutoka $1)',
'redirectpagesub'   => 'Ukurasa wa kuelekeza',
'lastmodifiedat'    => 'Ukurasa huu umebadilishwa kwa mara ya mwisho tarehe $1, saa $2.',
'viewcount'         => 'Ukurasa huu umetembelewa mara {{PLURAL:$1|moja tu|$1}}.',
'protectedpage'     => 'Kurasa iliyolindwa',
'jumpto'            => 'Rukia:',
'jumptonavigation'  => 'urambazaji',
'jumptosearch'      => 'tafuta',
'view-pool-error'   => 'Samahani, seva zimezidiwa kwa wakati huu.
Watumiaji wengi mno wanajaribu kutazama ukurasa huu.
Tafadhali subiri kwa muda kadhaa kabla ya kujaribu kufungua tena.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Kuhusu {{SITENAME}}',
'aboutpage'            => 'Project:Kuhusu',
'copyright'            => 'Yaliyomo yafuata $1.',
'copyrightpage'        => '{{ns:project}}:Hakimiliki',
'currentevents'        => 'Matukio ya hivi karibuni',
'currentevents-url'    => 'Project:Matukio ya hivi karibuni',
'disclaimers'          => 'Kanusho',
'disclaimerpage'       => 'Project:Kanusho kwa jumla',
'edithelp'             => 'Usaidizi kwa uhariri',
'edithelppage'         => 'Help:Usaidizi kwa uhariri',
'helppage'             => 'Help:Yaliyomo',
'mainpage'             => 'Mwanzo',
'mainpage-description' => 'Mwanzo',
'policy-url'           => 'Project:Sera',
'portal'               => 'Jumuia',
'portal-url'           => 'Project:Jumuia',
'privacy'              => 'Sera ya faragha',
'privacypage'          => 'Project:Sera ya faragha',

'badaccess'        => 'Kuna hitilafu ya ruhusa',
'badaccess-group0' => 'Hauruhusiwi kutenda jambo hilo uliloomba.',
'badaccess-groups' => 'Ombi uliloomba limefikia ukingoni mwa watumiaji wa {{PLURAL:$2|the group|one of the groups}}: $1.',

'versionrequired'     => 'Toleo $1 la MediaWiki linahitajika',
'versionrequiredtext' => 'Toleo $1 la MediaWiki linahitajika ili kutumia ukurasa huu.
Tazama [[Special:Version|ukurasa wa toleo]].',

'ok'                      => 'Sawa',
'retrievedfrom'           => 'Rudishwa kutoka "$1"',
'youhavenewmessages'      => 'Una $1 ($2).',
'newmessageslink'         => 'ujumbe mpya',
'newmessagesdifflink'     => 'badiliko la mwisho',
'youhavenewmessagesmulti' => 'Umepokea jumbe mpya kule $1',
'editsection'             => 'hariri',
'editold'                 => 'hariri',
'viewsourceold'           => 'view source',
'editlink'                => 'hariri',
'viewsourcelink'          => 'onyesha kodi za ukurasa',
'editsectionhint'         => 'Hariri fungu: $1',
'toc'                     => 'Yaliyomo',
'showtoc'                 => 'fichua',
'hidetoc'                 => 'ficha',
'thisisdeleted'           => 'Tazama au rudisha $1?',
'viewdeleted'             => 'Tazama $1?',
'restorelink'             => '{{PLURAL:$1|sahihisho lililofutwa moja|masahihisho yaliyofutwa $1}}',
'feedlinks'               => 'Tawanyiko:',
'feed-invalid'            => 'Umekosea kuingiza maelezo ya aina ya tawanyiko.',
'feed-unavailable'        => 'Matawanyiko hayapatikani',
'site-rss-feed'           => 'Tawanyiko la RSS la $1',
'site-atom-feed'          => 'Tawanyiko la Atom la $1',
'page-rss-feed'           => 'Tawanyiko la RSS la "$1"',
'page-atom-feed'          => 'Tawanyiko la Atom la "$1"',
'red-link-title'          => '$1 (bado haujaandikwa)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Makala',
'nstab-user'      => 'Ukurasa wa mtumiaji',
'nstab-media'     => 'Ukurasa wa faili',
'nstab-special'   => 'Ukurasa maalum',
'nstab-project'   => 'Ukurasa wa mradi',
'nstab-image'     => 'Faili',
'nstab-mediawiki' => 'Jumbe',
'nstab-template'  => 'Kigezo',
'nstab-help'      => 'Msaada',
'nstab-category'  => 'Jamii',

# Main script and global functions
'nosuchaction'      => 'Kitendo hiki hakipo',
'nosuchactiontext'  => 'Haiwezikani kutenda kitendo kilichoandikwa kwenye KISARA.
Labda ulikosea kuandika KISARA, au kiungo ulichofuata ina kasoro.
Au labda kuna hitilafu kwenye programu inayotumika na {{SITENAME}}.',
'nosuchspecialpage' => 'Ukurasa maalum huu hakuna',
'nospecialpagetext' => '<strong>Umeomba ukurasa maalumu batili.</strong>

Orodha ya kurasa maalumu zinapatika kwenye [[Special:SpecialPages|{{int:kurasamaalumu}}]].',

# General errors
'error'                => 'Kosa',
'databaseerror'        => 'Hitilafu ya hifadhidata',
'dberrortext'          => 'Shina la kuulizia kihifadhidata kuna hitilafu imetokea.
Hii inaweza kuashiria kuna mdudu katika bidhaa pepe.
Jaribio la ulizio la mwisho la kihifadhidata lilikuwa:
<blockquote><tt>$1</tt></blockquote>
kutoka ndani ya kitendea "<tt>$2</tt>".
Kihifadhidata kikarejesha tatizo "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Shina la kuulizia kihifadhidata kuna hitilafu imetokea.
Jaribio la ulizio la mwisho la kihifadhidata lilikuwa:
"$1"
kutoka ndani ya kitendea "$2".
Kihifadhidata kikarejesha tatizo "<tt>$3: $4</tt>".',
'laggedslavemode'      => "'''Ilani:'''Labda masahihisho ya hivi karibuni bado hayajaonekana.",
'readonly'             => 'Hifadhidata imefungika',
'enterlockreason'      => 'Ingiza sababu za kufunga, pamoja na makisio yake lini itafunguliwa',
'readonlytext'         => 'Kihifadhidata kwa sasa umefungwa kwa maingizo mapya na matengenezo mengine, yamkini kwa ajili ya utaratibu wa matengenezo ya kawaida, baada ya hilo itarudi katika hali yake ya kawaida.

Msimamizi aliyeifunga ametoa maelezo haya: $1',
'missing-article'      => 'Database haijapata maneno ya ukurasa unaotafutwa, unaitwa "$1" $2.

Jambo kama hili kikawaida husababishwa kwa kufuatia kwisha kwa diff au historia ya kiungo ambacho kilifutwa.

Ikiwa hii siyo sababu, basi unaweza kukuta kuna mdudu katika bidhaa pepe.
Tafadhali ripoti hili kwa [[Special:ListUsers/sysop|msimamizi]], na uache jina la URL.',
'missingarticle-rev'   => '(namba ya toleo: $1)',
'missingarticle-diff'  => '(Tofauti: $1, $2)',
'readonly_lag'         => 'Kihifadhidata kimejifunga chenyewe wakati seva za kifadhidata joli imedakwa na seva ya utawala',
'internalerror'        => 'Hitilafu ya ndani',
'internalerror_info'   => 'Hitilafu ya ndani: $1',
'filecopyerror'        => 'Haikuweza kunakili faili "$1" kwa "$2".',
'filerenameerror'      => 'Haikuweza kubadilisha jina la faili "$1" kwa "$2".',
'filedeleteerror'      => 'Haikuweza kufuta faili "$1".',
'directorycreateerror' => 'Haikuweza kuanzisha saraka ya "$1".',
'filenotfound'         => 'Haikuweza kutafuta faili "$1".',
'fileexistserror'      => 'Haiwezi kuandika kwa faili "$1": faili liliopo',
'unexpected'           => 'Jambo lisilotegemewa: "$1"="$2".',
'formerror'            => 'Hitilafu: haikufaulu kuweka fomu',
'badarticleerror'      => 'Ukurasa huu hauwezi kutendewa kitendo hiki.',
'cannotdelete'         => 'Haikuweza kufuta kurasa au faili linaloitwa "$1".
Huenda likawa tayari lishafutwa na mtu mwingine.',
'badtitle'             => 'Jina halifai',
'badtitletext'         => 'Jina la ukurasa ulilotaka ni batilifu, tupu, au limeungwa vibaya na jina la lugha nyingine au Wiki nyingine.  Labda linazo herufi moja a zaidi ambazo hazitumiki katika majina.',
'perfcached'           => 'Data zifuatazo zinatoka kwenye kache na huenda si ya kisasa.',
'perfcachedts'         => 'Data zifuatazo zimetoka kwenye kache iliobadilishwa mara ya mwisho saa $3, tarehe $2.',
'querypage-no-updates' => 'Mabadiliko kwa ajili ya ukurasa huu yamesimamishwa.
Data za hapa haziwezi kunawirishwa kwa sasa.',
'wrong_wfQuery_params' => 'Parameta za ulizio zilizoingizwa wfQuery() na zisizo sahihi ni<br />
Kitenda: $1<br />
Ulizio: $2',
'viewsource'           => 'Onyesha kodi za ukurasa',
'viewsourcefor'        => 'kwa $1',
'actionthrottledtext'  => 'Ikiwa kama hatua ya kupambana na uharibifu, umefika kikomo katika kutenda jambo hili kwa mara nyingi mno tena kwa kipindi cha muda mfupi kama huu, na umevuka kiwango hiki.
Tafadhali jaribu tena baada ya muda mfupi.',
'protectedpagetext'    => 'Ukurasa huu umefungwa ili kuepuka uhariri.',
'viewsourcetext'       => 'Unaweza kutazama na kuiga chanzo cha ukurasa huu:',
'protectedinterface'   => 'Ukurasa huu unatoa maelezo ya msingi ya bidhaa pepe, na pia umefungwa ili kuzuiya uharibifu.',
'editinginterface'     => "'''Ilani:''' Una hariri ukurasa unaotumika kutoa maelezo ya msingi ya bidhaa pepe.
Mabadiliko katika ukurasa huu yataathiri mwonekano mzima wa viungo vya watumiaji wengine.
Kwa lengo la kutaka kutafsiri, tafadhali fikiria kutumia  [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], kwa kuweka miradi ya MediaWiki kwa kienyeji.",
'sqlhidden'            => '(maulizo ya SQL yamefichwa)',
'cascadeprotected'     => 'Ukurasa huu umekingwa usihaririwe, kwa sababu umejumlishwa katika {{PLURAL:$1|ukurasa ufuatao, ambao umekingwa|kurasa zifuatazo, ambazo zimekingwa}} na chagua la "cascadi" iliwashwa:
$2',
'namespaceprotected'   => "Huna ruhusa ya kuhariri kurasa za eneo la wiki la '''$1'''.",
'customcssjsprotected' => 'Huna ruhusa ya kuhariri ukurasa huu, kwa sababu ukurasa umejumlisha mipangilio binafsi ya mtumiaji mwingine.',
'ns-specialprotected'  => 'Kurasa maalumu haziwezi kuhaririwa.',
'titleprotected'       => 'Jina hili limekingwa lisiumbwe na [[User:$1|$1]].
Sababu zilizotolewa ni "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Usanidi mbaya: kiskani virusi hakijulikani: ''$1''",
'virus-scanfailed'     => 'skani imeshindwa (kodi $1)',
'virus-unknownscanner' => 'kipambana na virusi haijulikani:',

# Login and logout pages
'logouttext'                 => "'''Umetoka kwenye akaunti yako.'''

Unaweza kuendelea kutumia {{SITENAME}} bila kutaja jina lako, au unaweza [[Special:UserLogin|kuingia tena]] kwenye akaunti yako. Kumbuka kwamba kurasa nyingine zitaendelea kuonekana kana kwamba bado hujatoka kwenye akaunti yako, hadi utakaposafisha kache ya kivinjari.",
'welcomecreation'            => '== Karibu, $1! ==
Ushafunguliwa akaunti yako tayari.
Usisahau kubadilisha mapendekezo yako ya [[Special:Preferences|{{SITENAME}}]].',
'yourname'                   => 'Jina la mtumiaji:',
'yourpassword'               => 'Nywila',
'yourpasswordagain'          => 'Andika tena neno la siri',
'remembermypassword'         => 'Nikumbuke katika tarakilishi hii',
'yourdomainname'             => 'Tovuti yako:',
'externaldberror'            => 'Huenda kulikuwa na hitilafu ya database au labda hauruhusiwi kubadilisha akaunti yako ya nje.',
'login'                      => 'Ingia',
'nav-login-createaccount'    => 'Ingia/ sajili akaunti',
'loginprompt'                => 'Lazima kompyuta yako ipokee kuki ili uweze kuingia kwenye {{SITENAME}}.',
'userlogin'                  => 'Ingia/ sajili akaunti',
'userloginnocreate'          => 'Ingia',
'logout'                     => 'Toka',
'userlogout'                 => 'Toka',
'notloggedin'                => 'Hujaingia',
'nologin'                    => "Huna akaunti ya kuingilia? '''$1'''.",
'nologinlink'                => 'Sajili akaunti',
'createaccount'              => 'Sajili akaunti',
'gotaccount'                 => "Unayo akaunti tayari? '''$1'''",
'gotaccountlink'             => 'Ingia',
'createaccountmail'          => 'Kwa barua pepe',
'badretype'                  => 'Maneno uliyoyaandika ni tofauti.',
'userexists'                 => 'Jina la mtumiaji uliloingiza tayari linatumika.
Tafadhali chagua jina lingine.',
'loginerror'                 => 'Kosa la kuingia',
'createaccounterror'         => 'Haikufaulu kuanzisha akaunti: $1',
'nocookiesnew'               => "Umesajiliwa, lakini bado hujaingizwa. {{SITENAME}} inatumia ''kuki'' ili watumiaji waingizwe. Kompyuta yako inazuia ''kuki''. Tafadhali, ondoa kizuizi hicho uingie kwa kutumia jina mpya na neno la siri.",
'nocookieslogin'             => '{{SITENAME}} inatumia kuki ili watumiaji waweze kuingia.
Kompyuta yako inakataa kupokea kuki.
Tafadhali, ondoa kizuizi hicho, baadaye jaribu tena.',
'noname'                     => 'Hauja dhihilisha jina la mtumiaji.',
'loginsuccesstitle'          => 'Umefaulu kuingia',
'loginsuccess'               => "'''Umeingia {{SITENAME}} kama \"\$1\".'''",
'nosuchuser'                 => 'Hakuna mtumiaji mwenye jina "$1". 
Kumbuka kwamba programu inatofautishana kati ya herufi kubwa na ndogo.
Labda umeandika vibaya, au [[Special:UserLogin/signup|sajili akaunti mpya]].',
'nosuchusershort'            => 'Hakuna mtumiaji mwenye jina "<nowiki>$1</nowiki>". Labda umeandika vibaya.',
'nouserspecified'            => 'Lazima uandike jina la mtumiaji.',
'wrongpassword'              => 'Umeingiza nywila ya makosa. Jaribu tena.',
'wrongpasswordempty'         => 'Nywila ilikuwa tupu. Jaribu tena.',
'passwordtooshort'           => 'Ni lazima nywila iwe na {{PLURAL:#1|herufi}} $1 au zaidi.',
'password-name-match'        => 'Nywila yako lazima iwe tofauti na jina lako la mtumiaji.',
'mailmypassword'             => 'Nitume nywila mpya kwa barua pepe',
'passwordremindertitle'      => 'Nywila mpya ya muda kwa {{SITENAME}}',
'passwordremindertext'       => 'Mtu mmoja (yamkini wewe, kutoka anwani ya IP $1)
ameulizia nywila mpya kwa {{SITENAME}} ($4).
Nywila ya muda kwa mtumiaji "$2" sasa ni "$3".
Inatakiwa uingie na ubadilishe nywila yako sasa. Nywila yako ya muda itaishia baada ya siku {{PLURAL:$5|moja|$5}}.

Kama mtu mwingine ametoa ombi hili au kama umekumbuka nywila yako na
umeamua kutoibadilisha, unaweza kupuuza ujumbe huu na
kuendelea kutumia nywila yako ya awali.',
'noemail'                    => 'Hatuna anwani ya barua pepe kwa mtumiaji  "$1".',
'noemailcreate'              => 'Unahitajika utoe anwani halali ya barua pepe',
'passwordsent'               => 'Neno mpya la siri limeshatumia kwenye anwani ya baruapepe ya "$1".
Tafadhali, ingia baada ya kulipokea.',
'blocked-mailpassword'       => 'Anwani yako ya IP imezuiwa kuhariri {{SITENAME}}, kwa maana hiyo hairuhusiiswi kuumba nywila mpya kwa lengo la kulinda uharibifu.',
'eauthentsent'               => 'Tumekutuma barua pepe ili kuhakikisha anwani yako.
Kabla ya kutuma barua pepe nyingine kwenye akaunti hiyo, itabidi ufuate maelezo katika barua utakayopokea,
kuthibitisha kwamba wewe ndiyo ni mwenye akaunti.',
'throttled-mailpassword'     => 'Kikumbusho cha nywila tayari kimeshatumwa, ndani ya {{PLURAL:$1|hour|$1masaa}} kadhaa yaliyopita.
Ili kuzuiya uhuni, kiumbusho cha nywila kimoja pekee utakachotumiwa kwa {{PLURAL:$1|hour|$1 masaa}} kadhaa.',
'mailerror'                  => 'Hitilafu ilitokea wakati ulivyoituma barua pepe: $1',
'acct_creation_throttle_hit' => 'Watembeleaji wa wiki hii kwa kutumia anwani yako ya IP amefungua {{PLURAL:$1|akaunti 1|akaunti $1}} katika siku iliyopita, ambayo inaruhusiwa hasa kwa kipindi cha muda huu.
Majibu yake, watumiaji wanaotumia anwani ya IP hii hawawezi kufungua akaunti nyingine tena kwa muda huu.',
'emailauthenticated'         => 'Anwani yako ya barua pepe ilihakikishwa saa $3, tarehe $2.',
'emailnotauthenticated'      => 'Anwani ya barua pepe yako bado haijahakikiwa.
Hakuna hata barua  pepe moja itakayotumwa kwa lolote katika vipengele hivi vifuatavyo.',
'noemailprefs'               => 'Weka anwani ya barua pepe kwenye mapendekezo ili uweze kutumia zana hizi.',
'emailconfirmlink'           => 'Yakinisha anwani yako ya barua pepe',
'invalidemailaddress'        => 'Anwani ya barua pepe haiwezi kukubalika ikiwa inaonekana kuwa na muundo batili.
Tafadhali ingiza anwani ya miundo-mizuri au acha tupu kipengele hicho.',
'accountcreated'             => 'Akounti imeundwa',
'accountcreatedtext'         => 'Akounti imeundwa kwa ajili ya mtumiaji $1.',
'createaccount-title'        => 'Kuanzisha akaunti kwa ajili ya {{SITENAME}}',
'createaccount-text'         => 'Kuna mtu amesajili akaunti kwa kutumia anwani ya barua pepe yako kwenye  {{SITENAME}} ($4) anaitwa "$2", yenye nywila "$3".
Inabidi uingie na kisha ubadilishe nywila yako sasa.

Unaweza kupuuza ujumbe huu, endapo akaunti hii ilianzishwa kimakosa.',
'usernamehasherror'          => 'Jina la mtumiaji haliwezi kuwa na herufi ya alama ya reli',
'login-throttled'            => 'Umejaribu kadha wa kadha kuingia akaunti hii.
Tafadhali subiri kwanza kabla ya kujaribu tena.',
'loginlanguagelabel'         => 'Lugha: $1',

# Password reset dialog
'resetpass'                 => 'Kubadilisha nywila',
'resetpass_announce'        => 'Umeingia na kodi za barua pepe za muda tu.
Kumalizia kuingia ndani, ni lazima urekebishe neno la siri jipya hapa:',
'resetpass_header'          => 'Kubadilisha nywila ya akounti',
'oldpassword'               => 'Neno la siri la zamani',
'newpassword'               => 'Neno mpya la siri',
'retypenew'                 => 'Andika nywila tena:',
'resetpass_submit'          => 'Rekebisha neno la siri na uingie',
'resetpass_success'         => 'Neno lako la siri limefanikiwa kubadilishwa! Sasa unaingia...',
'resetpass_forbidden'       => 'Maneno ya siri hayawezi kubadilishwa',
'resetpass-no-info'         => 'Lazima uwe umeingia ili kuweza kutumia kurasa hii moja kwa moja.',
'resetpass-submit-loggedin' => 'Badilisha nywila',
'resetpass-wrong-oldpass'   => 'Neno la siri la muda au la sasa ni batili.
Inawezekana ikawa tayari umefaulu kubadilisha neno lako la siri au neno la siri jipya la muda.',
'resetpass-temp-password'   => 'Nywila ya muda:',

# Edit page toolbar
'bold_sample'     => 'Maandishi ya kooze',
'bold_tip'        => 'Kukoozesha maandishi',
'italic_sample'   => 'Matini ya italiki',
'italic_tip'      => 'Matini ya italiki',
'link_sample'     => 'Jina la kiungo',
'link_tip'        => 'Kiungo cha ndani',
'extlink_sample'  => 'http://www.example.com jina la kiungo',
'extlink_tip'     => 'Kiungo cha nje (kumbuka kuanza na http:// )',
'headline_sample' => 'Matini ya kichwa cha habari',
'headline_tip'    => 'Kichwa cha habari, saizi 2',
'math_sample'     => 'Ingiza formula hapa',
'math_tip'        => 'Formula ya kihesabu (LaTeX)',
'nowiki_sample'   => 'Weka matini bila fomati hapa',
'nowiki_tip'      => 'Puuza fomati ya Wiki',
'image_tip'       => 'Faili lililotiwa',
'media_tip'       => 'Kiungo cha faili la picha, video, au sauti',
'sig_tip'         => 'Sahihi yako na saa ya kusahihisha',
'hr_tip'          => 'Mstari wa mlalo (usitumie ovyo)',

# Edit pages
'summary'                          => 'Muhtasari:',
'subject'                          => 'Kuhusu/kichwa cha habari:',
'minoredit'                        => 'Haya ni mabadiliko madogo',
'watchthis'                        => 'Fuatilia ukurasa huu',
'savearticle'                      => 'Hifadhi ukurasa',
'preview'                          => 'Hakikisha',
'showpreview'                      => 'Onyesha hakikisho la mabadiliko',
'showlivepreview'                  => 'Tazama moja kwa moja',
'showdiff'                         => 'Onyesha mabadiliko',
'anoneditwarning'                  => "'''Ilani:''' Wewe hujaingia rasmi kwenye tovuti. Anwani ya IP ya tarakilishi yako itahifadhiwa katika historia ya uhariri wa ukurasa huu.",
'missingsummary'                   => "'''Taarifa:''' Hujaandika muhtasari ya kuhariri.
Ukibonyeza 'Hifadhi ukurasa' tena, badilisho lako litahifadhiwa bila muhtasari.",
'missingcommenttext'               => 'Tafadhali andika muhtasari chini.',
'missingcommentheader'             => "'''Kikumbusho:''' Hujaweka kichwa cha habari/mada kwa ajili ya maelezo haya.
Iwapo utabonyeza tena Hifadhi, haririo lako litahifadhiwa bila kichwa cha habari.",
'summary-preview'                  => 'Hakikisho la muhtasari:',
'subject-preview'                  => 'Hakikisha kichwa cha habari/mada:',
'blockedtitle'                     => 'Mtumiaji amezuiwa',
'blockedtext'                      => "<big>'''Jina lako la mtumiaji au anwani yako ya IP imezuiwa.'''</big>

Umezuiwa na $1. 
Sababu aliyetambua ni ''$2''

* Mwanzo wa uzuio: $8
* Mwisho wa uzuio: $6
* Aliyezuiwa: $7

Unaweza kuwasiliana na $1 au [[{{MediaWiki:Grouppage-sysop}}|mkabidhi]] kuzungumza uzuio.
Huwezi kutumia kipengele 'kumtuma mtumiaji barua pepe' isipopatikana anwani halisi ya barua pepe katika
[[Special:Preferences|mapendekezo ya akaunti]] yako, na usipozuiwa kuitumia.
Anwani yako ya IP ni $3, na namba ya uzuio ni #$5. Tafadhali taja namba hizi ukitaka kuwasiliana kuhusu uzuio huu.",
'autoblockedtext'                  => 'Anwani yako ya IP imezuiwa na mashine kwa sababu ilikuwa ikitumiwa na mtumiaji mwingine, ambaye amezuiliwa na $1.
Sababu zilizotolewa ni hizi:

:\'\'$2\'\'

* Imeanza kuzuiwa: $8
* Mwisho wa kuzuiwa: $6
* Mzuiwaji aliyenuiwa: $7

Unaweza kuwasiliana na $1 au mmoja kati ya [[{{MediaWiki:Grouppage-sysop}}|wasimamizi]] wengine ili kujadili uzuio.

Elewa kwamba huwezi kutumia kipengele cha "umtumie barua pepe mtuaji huyu" bila ya kuwa na anwani halali iliosajiliwa kwenye [[Special:Preferences|mapendekezo ya mtumiaji]] na uwe hujazuiliwa kuitumia.

Anwani yako ya sasa ya IP ni $3, na namba ya kuzuiliwa ni #$5.
Tafadhali jumlisha maelezo yote ya juu kwenye kila ulizo utakalolifanya.',
'blockednoreason'                  => 'sababu haikutajwa',
'blockedoriginalsource'            => "Kodi ya '''$1''' imeonyeshwa hapo chini:",
'blockededitsource'                => "Maandishi ya '''maharirio yako''' kwa '''$1''' yameonyeshwa chini:",
'whitelistedittitle'               => 'Kuingia kunahitajika ili uweze kuhariri',
'whitelistedittext'                => 'Inabidi $1 ili uweze kuhariri kurasa.',
'confirmedittext'                  => 'Lazima uthibitishe anwani ya barua pepe yako kabla ya kuhariri kurasa.
Tafadhali thibitisha anwani ya barua pepe yako kupitia [[Special:Preferences|mapendekezo yako ya mtumiaji]].',
'nosuchsectiontitle'               => 'Fungu hili hamna',
'nosuchsectiontext'                => 'Umejaribu kuhariri sehemu ambayo haipo.
Kwa vile hakuna hiyo sehemu $1, hakuna mahali pa kuhifadhia ulichohariri.',
'loginreqtitle'                    => 'Unatakiwa kuingia au kujisajili',
'loginreqlink'                     => 'uingie',
'loginreqpagetext'                 => 'Inabidi $1 ili uweze kutazama kurasa zingine.',
'accmailtitle'                     => 'Neno la siri limeshakutumia.',
'accmailtext'                      => "Nywila limetolewa na programu kwa ajili ya [[User talk:$1|$1]] na limetumwa kwa $2.

Unaweza kubadilisha nywila hili kwenye ukurasa wa ''[[Special:ChangePassword|kubadilisha nywila]]'' baada ya kuingia kwenye wiki.",
'newarticle'                       => '(Mpya)',
'newarticletext'                   => "Ukurasa unaotaka haujaandikwa bado. Ukipenda unaweza kuuandika wewe mwenyewe kwa kutumia sanduku la hapa chini (tazama [[{{MediaWiki:Helppage}}|Mwongozo]] kwa maelezo zaidi). Ukifika hapa kwa makosa, bofya kibonyezi '''back''' (nyuma) cha programu yako.",
'anontalkpagetext'                 => "----''Huu ni ukurasa wa majadiliano wa mtumiaji ambaye hana jina na bado hajaumba akaunti bado, au hajawahi kutumia kabisa.
Kwa hiyo tunatumia namba za anwani ya IP yake kumtambulisha.
Anwani ya IP kama hiyo inaweza kutumika na watumiaji kadhaa.
Labda itakusumbua kwamba kuna maoni mengine yanawekwa hapa na unaamini kwamba haya maoni hayakulengi. Ikiwa hivyo, tafadhali [[Special:UserLogin/signup|fungua akaunti]] au  [[Special:UserLogin|ingia]] ili kuepuka kuchanganywa na watumiaji wengine ambao hawana jina.''",
'noarticletext'                    => 'Ukurasa huu haujaandikwa bado. [[Special:Search/{{PAGENAME}}|tafutia jina hili]] katika kurasa nyingine, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tafuta kumbukumbu zinazohusika], au [{{fullurl:{{FULLPAGENAME}}|action=edit}} hariri ukurasa huu]</span>.',
'noarticletext-nopermission'       => 'Kwa sasa hakuna maandishi katika ukurasa huu.
Unaweza [[Special:Search/{{PAGENAME}}|kutafuta jina la ukurasa huu]] katika kurasa nyingine,
au <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tafuta ingizo linalofanana]</span>.',
'userpage-userdoesnotexist'        => 'Akaunti ya mtumiaji "$1" haijasajilishwa.
Ukitaka kuanzisha au kuhariri ukurasa huu tafadhali ucheki jina la akaunti.',
'userpage-userdoesnotexist-view'   => 'Akaunti ya mtumiaji "$1" haijasajilishwa.',
'clearyourcache'                   => "'''Elewa - Baada ya kuhifadhi, itakubidi uzungushe kivinjali kache chako ili kuona mabadiliko.'''
'''Mozilla / Firefox / Safari:''' shikiria ''Shift'' wakati unabonyeza ''Reload'', au aidha bonyeza ''Ctrl-F5'' au ''Ctrl-R'' (''Command-R'' kwa Macintosh);
'''Konqueror: '''bonyeza ''Reload'' au bonyeza ''F5'';
'''Opera:''' futa kache kwenye ''Tools → Preferences'';
'''Internet Explorer:''' shikiria ''Ctrl'' wakati unabonyeza ''Refresh,'' au bonyeza ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Kidokezi:''' Bonyeza 'Onyesha hakikisho la mabadiliko' kujaribu CSS hii mpya kabla hujaihifadhi.",
'userjsyoucanpreview'              => "'''Kidokezi:''' Bonyeza 'Onyesha hakikisho la mabadiliko' kujaribu JS hii mpya kabla hujaihifadhi.",
'usercsspreview'                   => "'''Kumbuka kwamba unahakiki mandhari ya CSS za ukurasa wako tu.'''
'''Haijahifadhiwa bado!'''",
'userjspreview'                    => "'''Kumbuka kwamba unajaribu/kuhakiki mandhari ya ukurasa wako wa JavaScript tu.'''
'''Haijahifadhiwa bado!'''",
'userinvalidcssjstitle'            => "'''Onyo:''' Hakuna umbo \"\$1\".
Kumbuka kwamba desturi ya kurasa za .css na .js hutumia herufi ndogo, yaani, {{ns:user}}:Foo/monobook.css na si {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Imesasishwa)',
'note'                             => "'''Taarifa:'''",
'previewnote'                      => "'''Hii ni hakikisho tu; mabadiliko hayajahifadhiwa bado!'''",
'previewconflict'                  => 'Hakikisho hii inaonyesha maandiko yaliyopo sanduku la juu yataonekayo ukiyahifadhi.',
'session_fail_preview'             => "'''Pole! Hatukuweza kuhifadhi sahihisho lako kwa sababu data za kipindi zilipotelewa.'''
Tafadhali jaribu tena.
Kama bado haifanyi kazi, jaribu [[Special:UserLogout|kutoka kwenye akounti yako]], halafu ingia tena.",
'session_fail_preview_html'        => "'''Kumradhi! Hatukuweza kushughulikia haririo lako kwa kufuatia upungufu wa ukaaji wa data.'''

''Kwa sababu {{SITENAME}} ina HTML zilizowezeshwa, hakikio limefichwa ikiwa kama tahadhari dhidi ya mashambulio ya JavaScript.''

'''Iwapo hili ni haririo la jaribio halali, tafadhali jaribu tena.'''
Iwapo bado haifanyikazi, jaribu [[Special:UserLogout|kutoka]] na uingie tena.",
'token_suffix_mismatch'            => "'''Uhariri wako umekataliwa kwa sababu koteja yako imeharibu herufi za ishara ya kuhariri.'''
Uharirio umekataliwa ili kuzuia uharibifu wa maandishi ya kurasa.
Haya hutokea kwa muda ambao unatumia huduma ya seva ya wavu isiyotiwa jina na yenye hitilafu nyingi.",
'editing'                          => 'Kuhariri $1',
'editingsection'                   => 'Unahariri $1 (fungu)',
'editingcomment'                   => 'Una hariri $1 (sehemu mpya)',
'editconflict'                     => 'Mgongano wa kuhariri: $1',
'explainconflict'                  => "Mtu mwingine amebadilisha ukurasa huu tangu ulipoanza kuihariri.
Sanduku la juu inaonyesha maandiko yaliyopo sasa hivi kwenye ukurasa.
Mabadiliko yako yanaonyeshwa kwenye sanduku la chini.
Inabidi uingize mabadiliko yako ndani ya sanduku la juu.
Ni maandiko yaliyopo ndani ya sanduku la juu '''tu''' ambayo yatahifadhiwa utakapobonyeza \"Hifadhi ukurasa\".",
'yourtext'                         => 'Maandishi yako',
'storedversion'                    => 'Pitio lililohifadhiwa mwishoni',
'editingold'                       => "'''ANGALIA: Unakuwa unahariri toleo la zamani la ukurasa huu.
Ukiendelea kulihariri, mabadilisho yote yaliyofanywa tangu pale yatapotezwa.'''",
'yourdiff'                         => 'Tofauti',
'copyrightwarning'                 => "Tafadhali zingatia kwamba makala yote ya {{SITENAME}} unayoyaandika yanafuata $2 (tazama $1 kwa maelezo zaidi).
Usipotaka maandishi yako yaweze kuharirishwa bure na kutolewa wakati wowote, basi usiyaandike hapa.<br />
Unakuwa unaahidi kwamba maandishi unayoyaingia ni yako tu, au uliyapata kutoka bure au ni mali ya watu wote. '''USITOLEE MAKALA YALIYOHIFADHIWA HAKI ZAO ZA KUTUMIWA BILA KUPATA RUHUSA HALALI!'''",
'copyrightwarning2'                => "Tafadhali elewa kwamba michango yote ya {{SITENAME}} inaweza kuhahariwa, kubadilishwa, au kuondolewa na wachangiaji wengine.
Ikiwa hutaki maandishi yako yasihaririwe na yeyote, basi usiyaweke hapa.<br />
Pia una tuahidi kwamba umeandika haya wewe mwenyewe, au umenakili kutoka katika tovuti ya umma au chanzo cha wazo sawa na hiki (tazama  $1 kwa maelezo).
'''Usiandike makala yenye hatimiliki bila ya ruhusa halali!'''",
'longpagewarning'                  => "'''ILANI: Urefu wa ukurasa huu ni kilobaiti $1; vivinjari kadhaa vinaweza kuwa na matatizo ukihariri ukurasa wenye urefu zaidi ya kb 32 hivi.
Tafadhali fikiria kuhusu kuvunja ukurasa kwa vipande vifupi.'''",
'longpageerror'                    => "'''Hitilafu: Maandishi uliyoyaweka yana urefu wa kilobati $1, ambayo ni marefu kuliko kiwango cha kawaida cha kilobaiti $2.'''
Hayawezi kuhifadhiwa.",
'readonlywarning'                  => "'''Onyo: Hifadhidata imefungwa kwa ajili ya matengenezo, kwa hiyo hautakuwa na uwezo wa kuhifadhi maharirio yako kwa sasa.'''
Unaweza kukata-na-kabandika maandishi yako kwenye faili na kulihifadhi kwa ajili ya baadaye.

Msimamizi aliyefunga ametoa maelezo haya: $1",
'protectedpagewarning'             => "'''ANGALIA: Ukurasa huu unakingwa kwa hiyo watumiaji wenye haki za wasimamizi tu wanaweza kuuhariri. Hakikisha kwamba unakuwa unafuata mwongozo wa kuhariri kurasa zinazokingwa.'''",
'semiprotectedpagewarning'         => "'''Ilani:''' Ukurasa huu umefungwa kwa hiyo watumiaji waliojisajili tu ndiyo wanaweza kuuhariri.",
'cascadeprotectedwarning'          => "'''Ilani:''' Ukurasa huu umefungwa kwa hiyo watumia wenye haki za usimamizi tu ndiyo wanaweza kuuhariri, kwa sababu umejumlishwa kwenye kurasa zingine zenye {{PLURAL:$1|page|kulindwa}}:",
'titleprotectedwarning'            => "'''Ilani: Ukurasa umefungwa kwa hiyo [[Special:ListGroupRights|haki maalumu]] zinahitajika ili kuanzisha ukurasa huu.'''",
'templatesused'                    => '{{PLURAL:$1|Kigezo kinachotumiwa|Vigezo vinavyotumiwa}} kwenye ukurasa huu:',
'templatesusedpreview'             => '{{PLURAL:$1|Kigezo kinachotumiwa|Vigezo vinavyotumiwa}} katika mandhari haya:',
'templatesusedsection'             => '{{PLURAL:$1|Kigezo kilichotumika|Vigezo vilivyotumika}} katika sehemu hii:',
'template-protected'               => '(kulindwa)',
'template-semiprotected'           => '(ulindaji kwa kiasi)',
'hiddencategories'                 => 'Ukurasa huu uliomo katika jamii {{PLURAL:$1|iliofichwa 1|zilizofichwa $1}}:',
'nocreatetitle'                    => 'Si wote wanaoweza kuanzisha ukurasa',
'nocreatetext'                     => '{{SITENAME}} imebana uwezekano kutengeneza kurasa mpya. Unaweza kurudia na kuhariri kurasa zilizomo, au [[Special:UserLogin|ingia au anza akaunti]].',
'nocreate-loggedin'                => 'Huna ruhusa ya kuazisha kurasa mpya.',
'permissionserrors'                => 'Hitilafu za ruhusa',
'permissionserrorstext'            => 'Huna ruhusa ya kufanya hivyo, kwa ajili ya sababu {{PLURAL:$1|ifuatayo|zifuatazo}}:',
'permissionserrorstext-withaction' => 'Huruhusiwi $2, kwa sababu {{PLURAL:$1|hiyo|hizo}}:',
'recreate-moveddeleted-warn'       => "'''Ilani: Unatengeneza tena ukurasa uliofutwa tayari.'''

Fikiria kama inafaa kuendelea kuhariri ukurasa huu.
Kumbukumbu za kufuta na kuhamisha ukurasa huu zinapatikana hapa kukusaidia:",
'moveddeleted-notice'              => 'Ukurasa huu umefutwa.
Kumbukumbu za kufuta na kuhamisha ukurasa huu zimetolewa chini, ili zifikike kwa urahisi.',
'log-fulllog'                      => 'Tazama kumbukumbu zote',
'edit-gone-missing'                => 'Haikuwezakana kusasisha ukurasa.
Inaonekana kwamba ukurasa umefutwa.',
'edit-conflict'                    => 'Mgongano wa kuhariri.',
'edit-no-change'                   => 'Uhariri wako haukufanikiwa, kwa sababu hapakuwa na mabadiliko yoyote kwenye maandishi.',
'edit-already-exists'              => 'Haikufanikiwa kuanzisha ukurasa mpya.
Ukurasa wa jina hilo unapatikana tayari.',

# "Undo" feature
'undo-summary' => 'Tengua toleo $1 lililoandikwa na [[Special:Contributions/$2|$2]] ([[User talk:$2|Majadiliano]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kushindwa kusajili akaunti',

# History pages
'viewpagelogs'           => 'Tazama kumbukumbu kwa ukurasa huu',
'currentrev'             => 'Toleo la sasa',
'currentrev-asof'        => 'Toleo lililopo $1',
'revisionasof'           => 'Sahihisho kutoka $1',
'revision-info'          => 'Sahihisho ya $1 aliyefanya $2',
'previousrevision'       => '←Sahihisho lililotangulia',
'nextrevision'           => 'Sahihisho linalofuata →',
'currentrevisionlink'    => 'Toleo la sasa',
'cur'                    => 'sasa',
'next'                   => 'linalofuata',
'last'                   => 'kabla',
'page_first'             => 'ya kwanza',
'page_last'              => 'ya mwisho',
'histlegend'             => "Chagua tofauti: tia alama katika vitufe redio kulinganisha matoleo, na bonyeza \"enter\" au kitufe hapo chini.<br />
Ufunguo: '''({{int:cur}})''' = tofauti na toleo la sasa, '''({{int:last}})''' = tofauti na toleo lililotangulia, '''({{int:minoreditletter}})''' = badiliko dogo.",
'history-fieldset-title' => 'Fungua historia',
'histfirst'              => 'Mwanzoni',
'histlast'               => 'Mwishoni',
'historysize'            => '({{PLURAL:$1|baiti}}) $1',
'historyempty'           => '(tupu)',

# Revision feed
'history-feed-title'          => 'Kumbukumbu za mabadiliko',
'history-feed-description'    => 'Kumbukumbu za mabadiliko ya ukurasa huu',
'history-feed-item-nocomment' => '$1 kwenye $2',

# Revision deletion
'rev-deleted-comment'        => '(muhtasari ilifutwa)',
'rev-deleted-user'           => '(jina la mtumiaji lilifutwa)',
'rev-deleted-event'          => '(ingizo lilifutwa)',
'rev-delundel'               => 'onyesha/ficha',
'rev-showdeleted'            => 'onyesha',
'revisiondelete'             => 'Kufuta/kurudisha matoleo',
'revdelete-nologtype-title'  => 'Aina ya kumbukumbu haikutajwa',
'revdelete-show-file-submit' => 'Ndiyo',
'revdelete-radio-same'       => '(isibadilishwe)',
'revdelete-radio-set'        => 'Ndiyo',
'revdelete-log'              => 'Sababu ya kufuta',
'revdel-restore'             => 'badilisha mwonekano',
'pagehist'                   => 'Historia ya ukurasa',
'deletedhist'                => 'Historia iliyofutwa',
'revdelete-content'          => 'Maandiko',
'revdelete-summary'          => 'muhtasari ya kuhariri',
'revdelete-uname'            => 'jina la mtumiaji',
'revdelete-hid'              => '$1 ilifichwa',
'revdelete-unhid'            => '$1 iliachwa kufichwa',
'revdelete-log-message'      => '$1 kwenye {{PLURAL:$2|sahihisho|masahihisho}} $2',
'revdelete-otherreason'      => 'Sababu nyingine:',
'revdelete-reasonotherlist'  => 'Sababu nyingine',
'revdelete-edit-reasonlist'  => 'Kuhariri sababu za kufuta',
'revdelete-offender'         => 'Mhariri wa toleo:',

# History merging
'mergehistory-reason' => 'Sababu:',

# Merge log
'revertmerge' => 'Usiunganishe',

# Diffs
'history-title'           => 'Historia ya masahihisho ya "$1"',
'difference'              => '(Tofauti baina ya masahihisho)',
'lineno'                  => 'Mstari $1:',
'compareselectedversions' => 'Linganisha matoleo mawili uliyochagua',
'editundo'                => 'tengua',
'diff-multi'              => '(Hatuonyeshi {{PLURAL:$1|sahihisho moja la katikati|masahihisho $1 ya katikati}}.)',

# Search results
'searchresults'                  => 'Matokeo ya utafutaji',
'searchresults-title'            => 'Matokeo ya utafutaji kwa ajili ya "$1"',
'searchresulttext'               => 'Kwa maelezo zaidi kuhusu kutafuta {{SITENAME}}, tazama [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                 => 'Ulitafuta \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|kurasa zote zinazoanza "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kurasa zote zinazoungwa na "$1"]])',
'searchsubtitleinvalid'          => "Ulitafuta '''$1'''",
'notitlematches'                 => 'Jina hili la ukurasa halikupatikana',
'notextmatches'                  => 'Maandishi yaliyotafutwa hayakupatikana kwenye kurasa zo zote',
'prevn'                          => '{{PLURAL:$1|uliotangulia|$1 zilizotangulia}}',
'nextn'                          => '{{PLURAL:$1|ujao|$1 zijazo}}',
'prevn-title'                    => '{{PLURAL:$1|Tokeo $1 lililotangulia|Matokeo $1 yaliyotangulia}}',
'nextn-title'                    => '{{PLURAL:$1|Tokeo $1 lijalo|Matokeo $1 yajayo}}',
'viewprevnext'                   => 'Tazama ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'              => 'Hitiari za kutafuta',
'searchhelp-url'                 => 'Help:Yaliyomo',
'searchmenu-prefix'              => '[[Special:PrefixIndex/$1|Tafuta kurasa kwenye eneo hili la wiki]]',
'searchprofile-project'          => 'Kurasa za msaada na za mradi',
'searchprofile-images'           => 'Picha na kadhalika',
'searchprofile-everything'       => 'Zote',
'searchprofile-advanced'         => 'Hali ya juu',
'searchprofile-articles-tooltip' => 'Tafuta kwenye $1',
'searchprofile-project-tooltip'  => 'Tafuta kwenye $1',
'searchprofile-images-tooltip'   => 'Tafuta mafaili',
'search-result-size'             => '$1 ({{PLURAL:$2|neno 1|maneno $2}})',
'search-redirect'                => '(elekezo toka kwa $1)',
'search-section'                 => '(fungu $1)',
'search-suggest'                 => 'Je, ulitaka kutafuta: $1',
'search-interwiki-caption'       => 'Miradi ya jumuia',
'search-interwiki-default'       => 'Matokeo toka $1:',
'search-interwiki-more'          => '(zaidi)',
'search-mwsuggest-enabled'       => 'na mapendekezo',
'search-mwsuggest-disabled'      => 'bila makendekezo',
'searcheverything-enable'        => 'Tafuta katika maeneo yote ya wiki',
'searchall'                      => 'zote',
'nonefound'                      => "'''Zingatia''': Utafutaji wa msingi unatafuta kwenye maeneo machache ya wiki tu.
Ukitaka kutafuta kwenye maeneo yote (pamoja na kurasa za majadiliano, vigezo, nk) andika ''all:'' mwanzoni mwa kisanduku. Ukitaka kutafuta kwenye eneo linaloitwa ''fulani'' andika ''fulani:'' mwanzoni mwa kisanduku.",
'search-nonefound'               => 'Hakuna matokeo ya kutafuta ulizio ule.',
'powersearch'                    => 'Tafuta kwa hali ya juu',
'powersearch-legend'             => 'Tafuta kwa hali ya juu',
'powersearch-ns'                 => 'Tafuta kwenye maeneo ya wiki yafuatayo:',
'powersearch-redir'              => 'Orodhesha kurasa za kuelekeza',
'powersearch-field'              => 'Tafuta huu:',
'powersearch-togglelabel'        => 'Chagua:',
'powersearch-toggleall'          => 'Zote',
'powersearch-togglenone'         => 'Bila',
'search-external'                => 'Kutafuta nje',
'searchdisabled'                 => 'Kutafuta {{SITENAME}} kumesimamishwa.
Unaweza kutafuta kwa kutumia Google punde si punde.
Ujue lakini kwamba kumbukumbu za {{SITENAME}} kule Google labda zilipitwa na wakati.',

# Quickbar
'qbsettings-none' => 'Hakuna',

# Preferences page
'preferences'                 => 'Mapendekezo',
'mypreferences'               => 'Mapendekezo yangu',
'prefs-edits'                 => 'Idadi ya marekebisho:',
'prefsnologin'                => 'Hujaingia',
'prefsnologintext'            => 'Inabidi <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} uingie akaunti yako]</span> ili ubadilishe mapendekezo yako.',
'changepassword'              => 'Badilisha neno la siri',
'prefs-skin'                  => 'Sura',
'skin-preview'                => 'Hakiki',
'prefs-math'                  => 'Hisabati',
'datedefault'                 => 'Chaguo-msingi',
'prefs-datetime'              => 'Tarehe na saa',
'prefs-personal'              => 'Kuhusu mtumiaji',
'prefs-rc'                    => 'Mabadiliko ya karibuni',
'prefs-watchlist'             => 'Maangalizi',
'prefs-watchlist-days'        => 'Ionyeshwe siku ngapi kwenye orodha ya maangalizi?',
'prefs-watchlist-days-max'    => 'Isizidi siku 7',
'prefs-watchlist-edits'       => 'Upeo ya idadi ya mabadiliko yatakayoonyeshwa kwenye orodha ya maangalizi iliyotanuka:',
'prefs-watchlist-edits-max'   => 'Idadi isiyopitishwa: 1000',
'prefs-watchlist-token'       => 'Ufunguo wa orodha ya maangalizi:',
'prefs-misc'                  => 'Mengineyo',
'prefs-resetpass'             => 'Kubadilisha nywila',
'prefs-email'                 => 'Hitiari za barua pepe',
'prefs-rendering'             => 'Umbo',
'saveprefs'                   => 'Hifadhi',
'resetprefs'                  => 'Utupe mabadiliko yasijahifadhika',
'restoreprefs'                => 'Rudisha mapendekezo ya msingi',
'prefs-editing'               => 'Kuhariri',
'prefs-edit-boxsize'          => 'Ukubwa wa dirisha la kuhariri.',
'rows'                        => 'Mistari:',
'columns'                     => 'Safu:',
'searchresultshead'           => 'Kutafuta',
'recentchangesdays'           => 'Ionyeshwe siku ngapi kwenye orodha ya mabadiliko ya karibuni?',
'recentchangesdays-max'       => 'Isizidi {{PLURAL:$1|siku}} $1',
'savedprefs'                  => 'Mapendekezo yako yamehifadhiwa.',
'timezonelegend'              => 'Ukanda saa:',
'localtime'                   => 'Saa ya kwetu:',
'timezoneuseserverdefault'    => 'Tumia saa ya seva',
'timezoneuseoffset'           => 'Nyingine (weka tofauti ya saa)',
'timezoneoffset'              => 'Tofauti ya saa¹:',
'servertime'                  => 'Saa ya seva:',
'guesstimezone'               => 'kivinjari kiweke saa',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Marekani',
'timezoneregion-antarctica'   => 'Antaktika',
'timezoneregion-arctic'       => 'Artiki',
'timezoneregion-asia'         => 'Asia',
'timezoneregion-atlantic'     => 'Bahari ya Atlantiki',
'timezoneregion-australia'    => 'Australia',
'timezoneregion-europe'       => 'Ulaya',
'timezoneregion-indian'       => 'Bahari ya Hindi',
'timezoneregion-pacific'      => 'Bahari ya Pasifiki',
'allowemail'                  => 'Wezesha barua pepe toka kwa watumiaji wengine',
'prefs-searchoptions'         => 'Hitiari za kutafuta',
'prefs-namespaces'            => 'Maeneo ya wiki',
'default'                     => 'chaguo-msingi',
'prefs-files'                 => 'Mafaili',
'prefs-emailconfirm-label'    => 'Kuhakikisha barua pepe:',
'prefs-textboxsize'           => 'Ukubwa wa sanduku la kuhariri',
'youremail'                   => 'Barua pepe yako:',
'username'                    => 'Jina la mtumiaji:',
'uid'                         => 'Namba ya mtumiaji:',
'prefs-memberingroups'        => 'Mwanachama wa {{PLURAL:$1|kundi la|makundi ya}}:',
'prefs-registration'          => 'Wakati wa kusajili:',
'yourrealname'                => 'Jina lako halisi:',
'yourlanguage'                => 'Lugha:',
'yourvariant'                 => 'Variant:',
'yournick'                    => 'Sahihi:',
'prefs-help-signature'        => 'Unapoandika kwenye kurasa za majadiliano tafadhali utie sahihi kwa kuandika "<nowiki>~~~~</nowiki>"; itaonekana jina lako pamoja na saa na tarehe ya kuhifadhi.',
'badsig'                      => 'Umeweka sahihi batili.
Angalia mabano ya HTML.',
'badsiglength'                => 'Sahihi uliyoweka ni ndefu mno.
Haiwezi kuzidi {{PLURAL:$1|tarakimu|tarakimu}} $1.',
'yourgender'                  => 'Jinsi:',
'gender-unknown'              => 'Haitajwi',
'gender-male'                 => 'Mume',
'gender-female'               => 'Mke',
'prefs-help-gender'           => 'Si lazima: inatumika kwenye lugha zinazokuwa na mtindo wa kuitana tofauti kwa ajili ya wanaume na wanawake, ili bidhaa pepe itumie mtindo sahihi.
Taarifa hii itakuwa wazi.',
'email'                       => 'Barua pepe',
'prefs-help-realname'         => 'Jina la kweli si lazima. Ukichagua kutaja jina lako hapa, litatumiwa kuonyesha kwamba ndiyo ulifanya kazi unayochangia.',
'prefs-help-email'            => 'Barua pepe sio lazima, lakini inawezesha kupokea nywila mpya kwa kupitia barua pepe yako endapo utakuwa umeisahau.
Pia unaweza kuchagua kuacha watumiaji wengine kuwasiliana nao kwa kutumia ukurasa wako wa mtumiaji au ule wa majadiliano bila ya kuonyesha jina la akaunti yako.',
'prefs-help-email-required'   => 'Barua pepe inahitajika.',
'prefs-info'                  => 'Maelezo ya kimsingi',
'prefs-i18n'                  => 'Lugha',
'prefs-signature'             => 'Sahihi',
'prefs-dateformat'            => 'Jinsi inayoandikwa tarehe',
'prefs-timeoffset'            => 'Kuweka saa tofauti na saa ya seva',
'prefs-advancedediting'       => 'Hitiari za hali ya juu',
'prefs-advancedrc'            => 'Hitiari za hali ya juu',
'prefs-advancedrendering'     => 'Hitiari za hali ya juu',
'prefs-advancedsearchoptions' => 'Hitiari za hali ya juu',
'prefs-advancedwatchlist'     => 'Hitiari za hali ya juu',
'prefs-display'               => 'Hitiari za kutandaza',

# User rights
'userrights-user-editname' => 'Andika jina la mtumiaji:',
'editusergroup'            => 'Kuhariri vikundi vya watumiaji',
'userrights-groupsmember'  => 'Mwanachama wa:',
'userrights-reason'        => 'Sababu:',

# Groups
'group'       => 'Kundi:',
'group-user'  => 'Watumiaji',
'group-sysop' => 'Wakabidhi',
'group-all'   => '(vyote)',

'group-user-member' => 'Mtumiaji',

'grouppage-user'  => '{{ns:project}}:Watumiaji',
'grouppage-sysop' => '{{ns:project}}:Wakabidhi',

# Rights
'right-read'          => 'Kusoma kurasa',
'right-edit'          => 'Kuhariri kurasa',
'right-createtalk'    => 'Kuanzisha kurasa za majadiliano',
'right-createaccount' => 'Kufungua akounti mpya za watumiaji',
'right-minoredit'     => 'Kutia alama kwamba badiliko ni dogo',
'right-move'          => 'Kusogeza kurasa',
'right-movefile'      => 'Kusogeza mafaili',
'right-upload'        => 'Kupakia mafaili',
'right-delete'        => 'Kufuta kurasa',

# User rights log
'rightslog' => 'Kumbukumbu ya vyeo vya watumiaji',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'kusoma ukurasa huu',
'action-edit'       => 'kuhariri ukurasa huu',
'action-createpage' => 'kuanzisha kurasa',
'action-move'       => 'kusogeza ukurasa huu',
'action-movefile'   => 'kusogeza faili hili',
'action-delete'     => 'kufuta ukurasa huu',
'action-undelete'   => 'kurudisha ukurasa huu',

# Recent changes
'nchanges'                       => '{{PLURAL:$1|badiliko|mabadiliko}} $1',
'recentchanges'                  => 'Mabadiliko ya karibuni',
'recentchanges-legend'           => "Machaguo ya 'mabadaliko ya karibuni'",
'recentchangestext'              => 'Orodha ya mabadilisho yaliyofanywa katika {{SITENAME}} siku zilizopita.',
'recentchanges-feed-description' => 'Tumia tawanyiko hili kufuatilia mabadiliko yote ya hivi karibuni katika Wiki.',
'recentchanges-legend-newpage'   => '$1 - ukurasa upya',
'rcnote'                         => "Yanayofuata ni {{PLURAL:$1|badiliko '''1'''|mabadiliko '''$1''' ya mwisho}} kutoka katika {{PLURAL:$2|siku iliyopita|siku '''$2''' zilizopita}}, hadi $5, $4.",
'rcnotefrom'                     => "Hapo chini yaonekana mabadiliko tangu '''$2''' (tunaonyesha hadi '''$1''').",
'rclistfrom'                     => 'Onyesha mabadiliko mapya kuanzia $1',
'rcshowhideminor'                => '$1 mabadiliko madogo',
'rcshowhidebots'                 => '$1 roboti',
'rcshowhideliu'                  => '$1 watumiaji sasa',
'rcshowhideanons'                => '$1 watumiaji bila majina',
'rcshowhidepatr'                 => '$1 masahihisho yanayofanywa doria',
'rcshowhidemine'                 => '$1 masahihisho yangu',
'rclinks'                        => 'Onyesha mabadiliko $1 yaliyofanywa wakati wa siku $2 zilizopita<br />$3',
'diff'                           => 'tofauti',
'hist'                           => 'hist',
'hide'                           => 'Ficha',
'show'                           => 'Onyesha',
'minoreditletter'                => 'd',
'newpageletter'                  => 'P',
'boteditletter'                  => 'r',
'newsectionsummary'              => '/* $1 */ mjadala mpya',
'rc-enhanced-expand'             => 'Onyesha maelezo mengine (inahitaji JavaScript)',
'rc-enhanced-hide'               => 'Ficha maelezo mengine',

# Recent changes linked
'recentchangeslinked'          => 'Mabadiliko husika',
'recentchangeslinked-feed'     => 'Mabadiliko husika',
'recentchangeslinked-toolbox'  => 'Mabadiliko husika',
'recentchangeslinked-title'    => 'Mabadiliko kuhusiana na "$1"',
'recentchangeslinked-noresult' => 'Hakuna mabadiliko kwenye kurasa zilizounganishwa wakati wa muda huo.',
'recentchangeslinked-summary'  => "Ukurasa maalum huu unaorodhesha mabadiliko ya hivi karibuni katika kurasa zinazoungwa (au katika jamii fulani).  Kurasa katika [[Special:Watchlist|maangalizi yako]] ni za '''koze'''.",
'recentchangeslinked-page'     => 'Jina la ukurasa:',
'recentchangeslinked-to'       => 'Onyesha mabadiliko yaliyotokea kwenye kurasa zile zinazoungwa kufikia ukurasa uliotajwa',

# Upload
'upload'              => 'Pakia faili',
'uploadbtn'           => 'Pakia faili',
'uploadnologin'       => 'Hujaingia',
'uploaderror'         => 'Hitilafu ya kupia',
'uploadtext'          => "Tumia fomu hapo chini kwa kupakizia mafaili.
Kwa kutazama au kutafuta faili zilizopakiwa awali, tafadhali nenda kwenye [[Special:FileList|orodha ya mafaili yaliyopakiwa]]. Kwa zile faili ambazo zishapitiwa, basi angalia [[Special:Log/upload|kumbukumbu ya mafaili]]. Kwa mafaili yaliyofutwa, tafadhali [[Special:Log/delete|tazama hapa]].

Kwa kutumia faili katika makala, tumia moja kati ya viungo vifuatavyo:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Picha.jpg]]</nowiki></tt>''' kwa kutumia toleo zima la faili
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Picha.png|200px|thumb|left|maelezo ya picha]]</nowiki></tt>''' tumia pixel 200 kwa ukubwa mzuri na sehemu ya 'maelezo ya picha' ikiwa kama maelezo husika na picha iliyopo
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' kwa kuunga moja kwa moja bila kuonyesga faili",
'uploadlog'           => 'kumbukumbu za kupakia',
'uploadlogpage'       => 'Kumbukumbu ya upakiaji',
'filename'            => 'Jina la faili',
'filedesc'            => 'Muhtasari',
'fileuploadsummary'   => 'Muhtasari:',
'filereuploadsummary' => 'Mabadiliko ya faili:',
'filestatus'          => 'Hali ya hakimiliki:',
'uploadedfiles'       => 'Mafaili yaliyopakiwa:',
'ignorewarning'       => 'Hifadhi bila kujali maonyo yoyote.',
'ignorewarnings'      => 'Usijali ilani zozote',
'successfulupload'    => 'Upakiaji ulifaulu',
'uploadwarning'       => 'Ilani kuhusu kupakia',
'savefile'            => 'Hifadhi faili',
'uploadedimage'       => 'ameipakia "[[$1]]"',
'overwroteimage'      => 'alipakia toleo jipya la "[[$1]]"',
'sourcefilename'      => 'Jina la faili la chanzo:',
'destfilename'        => 'Jina la faili la mwishilio:',
'upload-maxfilesize'  => 'Ukubwa wa faili lisizidi: $1',
'upload-description'  => 'Elezo la faili',
'watchthisupload'     => 'Kufuatilia faili hili',

'upload-unknown-size' => 'Ukubwa haujulikani',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'KISARA (URL) haikupatikana',

'license'        => 'Hatimiliki:',
'license-header' => 'Hatimiliki',
'nolicense'      => 'Haikuchaguliwa',

# Special:ListFiles
'imgfile'               => 'faili',
'listfiles'             => 'Orodha ya mafaili',
'listfiles_date'        => 'Tarehe',
'listfiles_name'        => 'Jina',
'listfiles_user'        => 'Mtumiaji',
'listfiles_size'        => 'Ukubwa',
'listfiles_description' => 'Maelezo',
'listfiles_count'       => 'Matoleo',

# File description page
'file-anchor-link'          => 'Faili',
'filehist'                  => 'Historia ya faili',
'filehist-help'             => 'Bonyeza tarehe/saa kuona faili kama ilivyoonekana wakati huo.',
'filehist-deleteall'        => 'futa zote',
'filehist-deleteone'        => 'futa',
'filehist-revert'           => 'rejesha',
'filehist-current'          => 'sasa hivi',
'filehist-datetime'         => 'Tarehe/Saa',
'filehist-thumb'            => 'Picha ndogo',
'filehist-thumbtext'        => 'Picha ndogo ya toleo la $1',
'filehist-nothumb'          => 'Hakuna picha ndogo',
'filehist-user'             => 'Mtumiaji',
'filehist-dimensions'       => 'Vipimo',
'filehist-filesize'         => 'Ukubwa wa faili',
'filehist-comment'          => 'Maelezo',
'filehist-missing'          => 'Faili halipo',
'imagelinks'                => 'Viungo vya mafaili',
'linkstoimage'              => '{{PLURAL:$1|Ukurasa huu|Kurasa hizi $1}} zimeunganishwa na faili hili:',
'nolinkstoimage'            => 'Hakuna kurasa zozote zilizounganishwa na faili hii.',
'sharedupload'              => 'Faili hili linatoka $1 na linaweza kushirikiwa na miradi mingine.',
'filepage-nofile'           => 'Hakuna faili yenye jina hili.',
'uploadnewversion-linktext' => 'Pakia toleo jipya la faili hii',

# File reversion
'filerevert'                => 'Rejesha $1',
'filerevert-legend'         => 'Rejesha faili',
'filerevert-comment'        => 'Sababu:',
'filerevert-defaultcomment' => 'Ilirejeshwa hadi sahihisho lile la $2, $1',
'filerevert-submit'         => 'Rejesha',

# File deletion
'filedelete'                  => 'Futa $1',
'filedelete-legend'           => 'Futa faili',
'filedelete-intro-old'        => "You are deleting the version of '''[[Media:$1|$1]]''' as of [$4 $3, $2].",
'filedelete-comment'          => 'Sababu ya kufuta:',
'filedelete-submit'           => 'Futa',
'filedelete-success-old'      => "The version of '''[[Media:$1|$1]]''' as of $3, $2 has been deleted.",
'filedelete-nofile-old'       => "There is no archived version of '''$1''' with the specified attributes.",
'filedelete-otherreason'      => 'Sababu nyingine:',
'filedelete-reason-otherlist' => 'Sababu nyingine',

# MIME search
'mimesearch' => 'Utafutaji wa MIME',

# List redirects
'listredirects' => 'Maelekezo',

# Unused templates
'unusedtemplates'    => 'Vigezo ambavyo havitumiwi',
'unusedtemplateswlh' => 'viungo vingine',

# Random page
'randompage' => 'Ukurasa wa bahati',

# Random redirect
'randomredirect' => 'Elekezo la bahati',

# Statistics
'statistics'                   => 'Takwimu',
'statistics-header-pages'      => 'Takwimu za kurasa',
'statistics-header-edits'      => 'Takwimu za kuhariri',
'statistics-header-views'      => 'Onyesha takwimu',
'statistics-header-users'      => 'Takwimu za watumiaji',
'statistics-header-hooks'      => 'Takwimu zingine',
'statistics-articles'          => 'Kurasa zilizopo',
'statistics-pages'             => 'Kurasa',
'statistics-pages-desc'        => 'Kurasa zote za katika wiki, zikiwemo kurasa za majadiliano, elekezo, n.k.',
'statistics-files'             => 'Faili zilizopakiwa',
'statistics-edits'             => 'Kurasa zilizohaririwa tangu {{SITENAME}} ilivyoanzishwa',
'statistics-edits-average'     => 'Wastani wa uhariri kwa kurasa',
'statistics-views-total'       => 'Onyesha kwa jumla',
'statistics-views-peredit'     => 'Onyesha kwa uhariri',
'statistics-jobqueue'          => 'Urefu wa [http://www.mediawiki.org/wiki/Manual:Job_queue foleni ya kazi]',
'statistics-users'             => '[[Special:ListUsers|Watumiaji]] waliojisajiri',
'statistics-users-active'      => 'Watumiaji wanaofanya kazi',
'statistics-users-active-desc' => 'Watumiaji waliofanya kazi katika siku {{PLURAL:$1|siku|$1 zilizopita}}',
'statistics-mostpopular'       => 'Kurasa zinazotazamwa sana',

'disambiguations' => 'Kurasa za kuainisha maneno',

'doubleredirects' => 'Maelekezo mawilimawili',

'brokenredirects'        => 'Maelekezo yenye hitilafu',
'brokenredirects-edit'   => 'hariri',
'brokenredirects-delete' => 'futa',

'withoutinterwiki'        => 'Kurasa bila viungo kwenye lugha nyingine',
'withoutinterwiki-submit' => 'Onyesha',

'fewestrevisions' => 'Kurasa zenye masahihisho machache kuliko zote',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|baiti|baiti}} $1',
'ncategories'             => '{{PLURAL:$1|jamii|jamii}} $1',
'nlinks'                  => '{{PLURAL:$1|kiungo|viungo}} $1',
'nmembers'                => '{{PLURAL:$1|mtumiaji|watumiaji}} $1',
'lonelypages'             => 'Kurasa ambazo haziungwi kutoka ukurasa mwingine wowote',
'uncategorizedpages'      => 'Kurasa ambazo hazijawekwa katika jamii',
'uncategorizedcategories' => 'Jamii ambazo hazijawekwa katika jamii',
'uncategorizedimages'     => 'Mafaili ambazo hazijawekwa katika jamii',
'uncategorizedtemplates'  => 'Vigezo ambavyo havijawekwa katika jamii',
'unusedcategories'        => 'Jamii ambazo hazitumiwi',
'unusedimages'            => 'Mafaili ambayo hayatumiwi',
'wantedcategories'        => 'Jamii zinazotakiwa',
'wantedpages'             => 'Kurasa zinazotakiwa',
'mostlinked'              => 'Kurasa zinazoungwa kuliko zote',
'mostlinkedcategories'    => 'Jamii zinazoungwa kuliko zote',
'mostlinkedtemplates'     => 'Vigezo vinavyoungwa kuliko zote',
'mostcategories'          => 'Jamii ambazo hazitumiwi',
'mostimages'              => 'Mafaili yanayoungwa kuliko yote',
'mostrevisions'           => 'Kurasa zenye masahihisho mengi kuliko zote',
'prefixindex'             => 'Kurasa zote zenye viambishi awali',
'shortpages'              => 'Kurasa fupi',
'longpages'               => 'Kurasa ndefu',
'deadendpages'            => 'Kurasa ambazo haziungi na ukurasa mwingine wowote',
'protectedpages'          => 'Kurasa zinazolindwa',
'listusers'               => 'Orodha ya Watumiaji',
'usercreated'             => 'Iliwekewa tarehe $1 saa $2',
'newpages'                => 'Kurasa mpya',
'newpages-username'       => 'Jina la mtumiaji:',
'ancientpages'            => 'Kurasa za kale',
'move'                    => 'Sogeza',
'movethispage'            => 'Sogeza ukurasa huu',
'pager-newer-n'           => '{{PLURAL:$1|1 ya karibu zaidi|$1 ya karibu zaidi}}',
'pager-older-n'           => '{{PLURAL:$1|$1 ya zamani zaidi}}',

# Book sources
'booksources'               => 'Vyanzo vya vitabu',
'booksources-search-legend' => 'Tafuta mahali panopopatikana kitabu',
'booksources-go'            => 'Nenda',

# Special:Log
'specialloguserlabel'  => 'Mtumiaji:',
'speciallogtitlelabel' => 'Kichwa:',
'log'                  => 'Kumbukumbu',
'all-logs-page'        => 'Kumbukumbu zote zilizo wazi',
'logempty'             => 'Vitu vyenye vipengele hivi havipo kwenye kumbukumbu.',
'log-title-wildcard'   => 'Tafuta kurasa zenye vichwa vinavyoanza na maandishi haya',

# Special:AllPages
'allpages'        => 'Kurasa zote',
'alphaindexline'  => '$1 hadi $2',
'nextpage'        => 'Ukurasa ujao ($1)',
'prevpage'        => 'Ukurasa uliotangulia ($1)',
'allpagesfrom'    => 'Onyesha kurasa zinazoanza kutoka:',
'allpagesto'      => 'Onyesha kurasa zinazoishia na:',
'allarticles'     => 'Kurasa zote',
'allinnamespace'  => 'Kurasa zote (eneo la wiki $1)',
'allpagesprev'    => 'Iliyotangulia',
'allpagesnext'    => 'Ijayo',
'allpagessubmit'  => 'Nenda',
'allpagesprefix'  => 'Onyesha kurasa zenye kiambishi awali:',
'allpages-bad-ns' => 'Eneo la "$1" halipatikani kwenye {{SITENAME}}.',

# Special:Categories
'categories' => 'Jamii',

# Special:DeletedContributions
'deletedcontributions'             => 'Michango ya mtumiaji aliyefutwa',
'deletedcontributions-title'       => 'Michango ya mtumiaji aliyefutwa',
'sp-deletedcontributions-contribs' => 'michango',

# Special:LinkSearch
'linksearch'    => 'Viungo vya nje',
'linksearch-ns' => 'Eneo la wiki:',
'linksearch-ok' => 'Tafuta',

# Special:ListUsers
'listusersfrom'      => 'Onyesha watumiaji kuanzia:',
'listusers-submit'   => 'Onyesha',
'listusers-noresult' => 'Mtumiaji hakupatikana.',

# Special:ActiveUsers
'activeusers-count'    => '{{PLURAL:$1|sahihisho|masahihisho}} $1 katika {{PLURAL:$3|siku}} $3 ya mwisho',
'activeusers-from'     => 'Onyesha watumiaji kuanzia:',
'activeusers-noresult' => 'Watumiaji hawakupatikana.',

# Special:Log/newusers
'newuserlogpage'          => 'Kumbukumbu za kuanzisha akounti za watumiaji',
'newuserlog-create-entry' => 'Akaunti ya mtumiaji mgeni',

# Special:ListGroupRights
'listgrouprights-group'   => 'Kundi',
'listgrouprights-members' => '(orodha ya wanachama)',

# E-mail user
'emailuser'       => 'Mtumie mtumiaji huyu barua pepe',
'emailpage'       => 'Kumtumia mtumiaji barua pepe',
'defemailsubject' => 'Barua pepe ya {{SITENAME}}',
'noemailtitle'    => 'Anwani ya barua pepe hakuna',
'emailfrom'       => 'Kutoka kwa:',
'emailto'         => 'Kwa:',
'emailsubject'    => 'Mada:',
'emailmessage'    => 'Ujumbe:',
'emailsend'       => 'Tuma',
'emailccme'       => 'Tuma nakala ya barua yangu ya pepe kwangu.',
'emailccsubject'  => 'Nakala ya barua pepe uliotuma kwa $1: $2',
'emailsent'       => 'Barua pepe imetumwa',

# Watchlist
'watchlist'         => 'Maangalizi yangu',
'mywatchlist'       => 'Maangalizi yangu',
'watchlistfor'      => "(kwa '''$1''')",
'watchnologin'      => 'Hujaingia',
'addedwatch'        => 'Imeongezwa kwenye maangalizi yako',
'addedwatchtext'    => "Ukurasa \"[[:\$1]]\" umewekwa kwenye [[Special:Watchlist|maangalizi]] yako.
Mabadiliko katika ukurasa huo na ukurasa wake wa majadiliano utaonekana hapo,
na ukurasa utaonyeshwa wenye '''koze''' kwenye [[Special:RecentChanges|orodha ya mabadiliko ya karibuni]]
ili kukusaidia kutambua.

Ukitaka kufuta ukurasa huo kutoka maangalizi yako baadaye, bonyeza \"Acha kufuatilia\" katika mwamba pembeni.",
'removedwatch'      => 'Imefutwa kutoka maangalizi yako',
'removedwatchtext'  => 'Ukurasa "[[:$1]]" umeondoshwa kutoka katika [[Special:Watchlist|maangalizi yako]].',
'watch'             => 'Fuatilia',
'watchthispage'     => 'Fuatilia ukurasa huu',
'unwatch'           => 'Acha kufuatilia',
'unwatchthispage'   => 'Acha kufuatilia',
'watchlist-details' => 'Unafuatilia {{PLURAL:$1|ukurasa $1|kurasa $1}} bila kuzingatia kurasa za majadiliano.',
'wlshowlast'        => 'Onyesha kutoka masaa $1 siku $2 $3',
'watchlist-options' => 'Hitiari za maangalizi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Unafuatilia...',
'unwatching' => 'Umeacha kufuatilia...',

'enotif_newpagetext'           => 'Ukurasa huu ni mpya.',
'enotif_impersonal_salutation' => 'Kwa mtumiaji wa {{SITENAME}}',
'changed'                      => 'alibadilisha',
'created'                      => 'alianzisha',
'enotif_subject'               => '$PAGEEDITOR $CHANGEDORCREATED ukurasa wa $PAGETITLE kwenye {{SITENAME}}',
'enotif_body'                  => 'Mpendwa $WATCHINGUSERNAME,


$PAGEEDITOR $CHANGEDORCREATED ukurasa wa $PAGETITLE kwenye {{SITENAME}} saa $PAGEEDITDATE. Tazama $PAGETITLE_URL kuona ukurasa ulivyo sasa hivi.

$NEWPAGE

Editor\'s summary: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page.
You could also reset the notification flags for all your watched pages on your watchlist.

             Your friendly {{SITENAME}} notification system

--
To change your watchlist settings, visit
{{fullurl:{{#special:Watchlist}}/edit}}

Feedback and further assistance:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Futa ukurasa',
'delete-legend'         => 'Futa',
'historywarning'        => "'''Ilani:''' Ukurasa unaotaka kufuta una historia ya {{PLURAL:$1|sahihisho|masahihisho}} $1:",
'confirmdeletetext'     => 'Wewe unategemea kufuta ukurasa pamoja na historia yake yote.
Tafadhali hakikisha kwamba unalenga kufanya hivyo, na kwamba unaelewa matokeo yake, na kwamba unafuata [[{{MediaWiki:Policy-url}}|sera]].',
'actioncomplete'        => 'Kitendo kimekwisha',
'deletedtext'           => '"<nowiki>$1</nowiki>" imefutwa. Ona $2 kwa historia ya kurasa zilizofutwa hivi karibuni.',
'deletedarticle'        => 'alifuta "[[$1]]"',
'dellogpage'            => 'Kumbukumbu ya ufutaji',
'reverted'              => 'Ilirejeshwa hadi sahihisho la zamani',
'deletecomment'         => 'Sababu ya kufuta',
'deleteotherreason'     => 'Sababu nyingine:',
'deletereasonotherlist' => 'Sababu nyingine',
'deletereason-dropdown' => '*Sababu za kawaida za ufutaji
** Ombi la mmiliki
** Ukiukaji wa hakimiliki
** Uharabu',

# Rollback
'rollback'         => 'Rejesha masahihisho',
'rollback_short'   => 'Rejesha',
'rollbacklink'     => 'rejesha',
'rollbackfailed'   => 'Haikufaulu kurejesha',
'cantrollback'     => 'Haiwezekana kujesha sahihisho;
ukurasa huu una mhariri mmoja tu.',
'revertpage'       => 'Masahihisho aliyefanya [[Special:Contributions/$2|$2]] ([[User talk:$2|Majadiliano]]) yalirejeshwa hadi sahihisho la mwisho na [[User:$1|$1]]',
'rollback-success' => 'Masahihisho aliyeyafanya $1 yalirejeshwa hadi kufika sahihisho la mwisho aliyefanya $2.',

# Protect
'protectlogpage'              => 'Kumbukumbu ya ulindaji',
'protectedarticle'            => 'aliulinda "[[$1]]"',
'modifiedarticleprotection'   => 'alibadilisha kiwango cha ulindaji kwa ajili ya "[[$1]]"',
'prot_1movedto2'              => 'alisogeza [[$1]] hadi [[$2]]',
'protect-legend'              => 'Hakikisha ukingo',
'protectcomment'              => 'Sababu:',
'protectexpiry'               => 'Itakwisha:',
'protect_expiry_invalid'      => 'Muda wa kwisha ni batilifu.',
'protect_expiry_old'          => 'Muda wa kuishi umepita tayari.',
'protect-text'                => "Unaweza kutazama na kubadilisha kiwango cha ulindaji hapa kwa ukurasa '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Akaunti yako hairuhusiwi kubadilisha viwango vya ulindaji.
Hivi ni vipimo kwa ukurasa '''$1''':",
'protect-cascadeon'           => 'Ukurasa huu umelindwa kwa sababu umezingatiwa katika {{PLURAL:$1|ukurasa $1 unaolinda kurasa chini yake|kurasa $1 zinazolinda kurasa chini yake}}. Unaweza kubadilisha kiwango cha ulindaji wa ukurasa huu, lakini hutaathirika ulindaji kutoka kurasa juu yake.',
'protect-default'             => 'Kubalia watumiaji wote',
'protect-fallback'            => 'Lazimisha ruhusa "$1"',
'protect-level-autoconfirmed' => 'Zuia watumiaji wapya au wale ambao hawajajisajilisha',
'protect-level-sysop'         => 'Wakabidhi tu',
'protect-summary-cascade'     => 'ulindaji kwa kurasa chini yake',
'protect-expiring'            => 'itakwisha $1 (UTC)',
'protect-cascade'             => 'Linda kurasa zinazozingatiwa chini ya ukurasa huu',
'protect-cantedit'            => 'Huwezi kubadilisha kiwango cha ulindaji wa ukurasa huu, kwa sababu huruhusiwi kuuhariri.',
'protect-othertime'           => 'Kipindi kingine:',
'protect-othertime-op'        => 'kipindi kingine',
'protect-dropdown'            => '*Sababu za kawaida za ulindaji
** Uharabu kupindukia
** Upuuzi kupindukia
** Onyo-la-kuzuia kuhariri
** Kurasa inatembelewa sana',
'protect-edit-reasonlist'     => 'Hariri sababu za kulinda',
'protect-expiry-options'      => 'saa 1:1 hour,siku 1:1 day,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite',
'restriction-type'            => 'Ruhusa:',
'restriction-level'           => 'Kiwango cha kizuia:',

# Restrictions (nouns)
'restriction-edit'   => 'Kuhariri',
'restriction-move'   => 'Kusogeza',
'restriction-create' => 'Kuanzisha',
'restriction-upload' => 'Kupakia',

# Undelete
'viewdeletedpage'           => 'Tazama kurasa zilizofutwa',
'undeletebtn'               => 'Rudisha',
'undeletelink'              => 'onyesha/rejesha',
'undeleteviewlink'          => 'tazama',
'undeletecomment'           => 'Sababu:',
'undeletedarticle'          => 'alirudisha "[[$1]]"',
'undelete-search-submit'    => 'Tafuta',
'undelete-show-file-submit' => 'Ndiyo',

# Namespace form on various pages
'namespace'      => 'Chagua eneo la wiki:',
'invert'         => 'Geuza uteuzi',
'blanknamespace' => '(Kuu)',

# Contributions
'contributions'       => 'Michango ya mtumiaji',
'contributions-title' => 'Michango ya mtumiaji $1',
'mycontris'           => 'Michango yangu',
'contribsub2'         => 'Kwa $1 ($2)',
'uctop'               => '(juu)',
'month'               => 'Kutoka mwezi (na zamani zaidi):',
'year'                => 'Kutoka mwakani (na zamani zaidi):',

'sp-contributions-newbies'     => 'Onyesha michango ya akaunti mpya tu',
'sp-contributions-newbies-sub' => 'Kwa akaunti mpya',
'sp-contributions-blocklog'    => 'Kumbukumbu ya uzuio',
'sp-contributions-logs'        => 'kumbukumbu',
'sp-contributions-talk'        => 'majadiliano',
'sp-contributions-search'      => 'Tafuta michango',
'sp-contributions-username'    => 'Anwani ya IP au jina la mtumiaji:',
'sp-contributions-submit'      => 'Tafuta',

# What links here
'whatlinkshere'            => 'Viungo viungavyo ukurasa huu',
'whatlinkshere-title'      => 'Kurasa zilizounganishwa na "$1"',
'whatlinkshere-page'       => 'Ukurasa:',
'linkshere'                => "Kurasa zifuatazo zimeunganishwa na '''[[:$1]]''':",
'nolinkshere'              => "Hakuna kurasa zilizounganishwa na '''[[:$1]]'''.",
'isredirect'               => 'elekeza ukurasa',
'istemplate'               => 'jumuisho',
'isimage'                  => 'kiungo cha picha',
'whatlinkshere-prev'       => '{{PLURAL:$1|uliotangulia|$1 zilizotangulia}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ujao|$1 zijazo}}',
'whatlinkshere-links'      => '← viungo',
'whatlinkshere-hideredirs' => '$1 maelekezo',
'whatlinkshere-hidetrans'  => '$1 majumuisho',
'whatlinkshere-hidelinks'  => '$1 viungo',
'whatlinkshere-filters'    => 'Machujio',

# Block/unblock
'blockip'                  => 'Zuia mtumiaji',
'ipaddress'                => 'Anwani ya IP:',
'ipadressorusername'       => 'Anwani ya IP au jina la mtumiaji:',
'ipbreason'                => 'Sababu:',
'ipbreasonotherlist'       => 'Sababu nyingine',
'ipbreason-dropdown'       => '*Sababu za kawaida za kuzuia
** Kuingiza habari za uongo
** Kuondosha yaliyomo katika kurasa
** Viungo vya nje visivyotakiwa
** Kuingiza upuuzi/ujinga sioeleweka kwenye makala
** Adabu mbaya/kero
** Kusumbua akaunti nyinginyingi
** Jina la mutumiaji lisilokubalika',
'ipbanononly'              => 'Zuia watumiaji wasio na majina tu',
'ipbcreateaccount'         => 'Kinga usajili wa akaunti',
'ipbemailban'              => 'Kinga mtumiaji asitume barua-pepe',
'ipbenableautoblock'       => 'Mashine izuie anwani ya mwisho ya IP iliotumiwa na mtumiaji huyu, na IP zozote za baadaye atakayejaribu kutumia',
'ipbsubmit'                => 'Zuia mtumiaji huyu',
'ipbother'                 => 'Muda mwingine:',
'ipboptions'               => 'Masaa 2:2 hours,siku 1:1 day,siku 3:3 days,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite',
'ipbotheroption'           => 'engine',
'ipbotherreason'           => 'Engine/sababu ya ziada:',
'ipbhidename'              => 'Ficha jina la mtumiaji katika orodha na kuhariri',
'ipbwatchuser'             => 'Fuatilia kurasa za mtumiaji na majadiliano ya mtumiaji huyu.',
'ipballowusertalk'         => 'Ruhusu mtumiaji huyu kuhariri ukurasa wake mwenyewe wa majadiliano wakati kazuiliwa',
'ipblocklist'              => 'Anwani za IP na majina ya watumiaji waliozuiliwa',
'emailblock'               => 'barua pepe imezuiliwa',
'blocklink'                => 'zuia',
'unblocklink'              => 'acha kuzuia',
'change-blocklink'         => 'badilisha zuia',
'contribslink'             => 'michango',
'blocklogpage'             => 'Kumbukumbu ya uzuio',
'blocklogentry'            => 'amemzuia [[$1]] mpaka $2 $3',
'unblocklogentry'          => 'aliachisha kuzuia $1',
'block-log-flags-nocreate' => 'uwezo wa kuunda akaunti imesitishwa',

# Move page
'move-page'        => 'Hamisha $1',
'move-page-legend' => 'Sogeza ukurasa',
'movepagetext'     => "Tumia fomu hapo chini ili kubadilisha jina la ukurasa, pamoja na kuhamisha historia yake yote katika jina jipya lile lile.
Jina la awali litahamishwa na kuelekezwa kwa ukurasa wa jina jipya.
Unaweza kurekebisha maelekezo yanayokwenda kwenye ukurasa wa zamani kwa kujiendesha.
Usipotaka marekebisho yafanyike kwa kujiendesha, kumbuka kutafutia maelekezo [[Special:DoubleRedirects|mawilimawili]] au maelezo [[Special:BrokenRedirects|yenye hitilafu]].
Wewe mwenyewe una madaraka kuhakikisha kwamba viungo viendelee kuelekea vinapolengwa.

Uwe mwangalifu kwamba ukurasa '''hautahamishwa''' kama tayari kuna ukurasa wenye jina jipya, isipokuwa wakati ukurasa mpya ni tupu au ni elekezo, na hauna historia ya kuhaririwa.
Yaani unaweza kurudisha ukurasa kwenye jina la awali ukikosea, na haiwezekani kufuta ukurasa mwingine kwa kuchukua nafasi yake.

'''ILANI!'''
Kuhamisha ukurasa wenye wasomaji wengi kunaweza kuathirika watumiaji wetu.
Tafadhali hakikisha kwamba unaelewa matokeo ya kitendo hiki kabla ya kuendelea.",
'movepagetalktext' => "Ukurasa wa majadiliano wa ukurasa huu utasogezwa pamoja yake
'''ila:'''
*tayari kuna ukurasa wa majadiliano (usiyo tupu) kwenye jina jipya, au
*ukifuta tiki katika kisanduku hapa chini.

Kama tayari kuna ukurasa au ukifuta tiki, itabidi usogeze au uunganishe ukurasa kwa mkono ukitaka.",
'movearticle'      => "Ukurasa wa majadiliano wa ukurasa huu utasogezwa pamoja yake '''ila:'''
*tayari kuna ukurasa wa majadiliano (usiyo tupu) kwenye jina jipya, au
*ukifuta tiki katika kisanduku hapa chini.

Kama tayari kuna ukurasa au ukifuta tiki, itabidi usogeze au uunganishe ukurasa kwa mkono ukitaka.",
'movenologin'      => 'Hujaingia',
'newtitle'         => 'Kuelekeza jina jipya:',
'move-watch'       => 'Fuatilia ukurasa huu',
'movepagebtn'      => 'Sogeza ukurasa',
'pagemovedsub'     => 'Umefaulu kusogeza ukurasa',
'movepage-moved'   => '<big>\'\'\'"$1" imesogezwa kwenye "$2"\'\'\'</big>',
'articleexists'    => 'Tayari kuna ukurasa wenye jina hilo, au
jina ulilochagua ni batilifu.
Chagua jina lengine.',
'talkexists'       => "'''Ukurasa wenyewe ulisogezwa salama, lakini ukurasa wake wa majadiliano haujasogezwa kwa sababu tayari kuna ukurasa wenye jina lake.  Tafadhali ziunganishe kwa mkono.'''",
'movedto'          => 'imesogezwa hadi',
'movetalk'         => 'Sogeza ukurasa wake wa majadiliano',
'1movedto2'        => '[[$1]] umesogezwa hapa [[$2]]',
'1movedto2_redir'  => 'alihamisha [[$1]] kwenda [[$2]] kwa kutengeneza elekezo',
'movelogpage'      => 'Kumbukumbu ya uhamiaji',
'movereason'       => 'Sababu:',
'revertmove'       => 'rejesha',

# Export
'export' => 'Hamisha kurasa',

# Namespace 8 related
'allmessages'               => 'Jumbe za mfumo',
'allmessagesname'           => 'Jina',
'allmessages-filter-legend' => 'Chuja',
'allmessages-filter-all'    => 'Zote',
'allmessages-language'      => 'Lugha:',

# Thumbnails
'thumbnail-more'  => 'Kuza',
'thumbnail_error' => 'Hitilafu kutengeneza picha ndogo: $1',

# Import log
'importlogpage' => 'Kumbukumbu ya kuingizwa',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ukurasa wako',
'tooltip-pt-mytalk'               => 'Majadiliano yako',
'tooltip-pt-preferences'          => 'Mapendekezo yangu',
'tooltip-pt-watchlist'            => 'Orodha ya kurasa unazofuatilia kwa mabadiliko',
'tooltip-pt-mycontris'            => 'Orodha ya michango yako',
'tooltip-pt-login'                => 'Tunakushajisha kuingia, lakini siyo lazima.',
'tooltip-pt-logout'               => 'Toka',
'tooltip-ca-talk'                 => 'Mazungumzo kuhusu makala',
'tooltip-ca-edit'                 => 'Unaweza kuhariri ukurasa huu.  Tafadhali tumia kitufe cha kuhakikisha kabla ya kuhifadhi.',
'tooltip-ca-addsection'           => 'Anzisha fungu jipya.',
'tooltip-ca-viewsource'           => 'Ukurasa huu umelindwa.  Unaweza kutazama chanzo chake.',
'tooltip-ca-history'              => 'Mabadiliko ya awali ya ukurasa huu',
'tooltip-ca-protect'              => 'Linda ukurasa huu',
'tooltip-ca-delete'               => 'Futa ukurasa huu',
'tooltip-ca-move'                 => 'Sogeza ukurasa huu',
'tooltip-ca-watch'                => 'Fuatilia ukurasa huu kwenye maangalizi yako',
'tooltip-ca-unwatch'              => 'Futa ukurasa huu kutoka maangalizi yako',
'tooltip-search'                  => 'Tafuta {{SITENAME}}',
'tooltip-search-go'               => 'Nenda katika ukurasa wenye jina hilihili kama upo',
'tooltip-search-fulltext'         => 'Tafuta kurasa kwa maandishi haya',
'tooltip-p-logo'                  => 'Tembelea Mwanzo',
'tooltip-n-mainpage'              => 'Tembelea Mwanzo',
'tooltip-n-mainpage-description'  => 'Tembelea Mwanzo',
'tooltip-n-portal'                => 'Kuhusu mradi, mambo unaweza kufanya, na mahali pa kugundua vitu',
'tooltip-n-currentevents'         => 'Maarifa kuhusu habari za siku hizi',
'tooltip-n-recentchanges'         => 'Orodha ya mabadiliko ya hivi karibuni katika Wiki.',
'tooltip-n-randompage'            => 'Onyesha ukurasa wa bahati',
'tooltip-n-help'                  => 'Mahali pa kueleweshwa.',
'tooltip-t-whatlinkshere'         => 'Orodha ya kurasa zote za Wiki zilizounganishwa na ukurasa huu',
'tooltip-t-recentchangeslinked'   => 'Mabadiliko ya karibuni ya katika kurasa zilizounganishwa na ukurasa huu',
'tooltip-feed-rss'                => 'Tawanyiko la RSS kwa ajili ya ukurasa huu',
'tooltip-feed-atom'               => 'Tawanyiko la Atom kwa ajili ya ukurasa huu',
'tooltip-t-contributions'         => 'Tazama orodha ya michango kwa mtumiaji huyu',
'tooltip-t-emailuser'             => 'Mtumie mtumiaji huyu barua pepe',
'tooltip-t-upload'                => 'Pakia picha, video, au sauti',
'tooltip-t-specialpages'          => 'Orodha ya kurasa maalum zote',
'tooltip-t-print'                 => 'Toleo linalochapika la ukurasa huu',
'tooltip-t-permalink'             => 'Kiungo cha daima cha kufikisha toleo hili la ukurasa',
'tooltip-ca-nstab-main'           => 'Onyesha kurasa zilizopo',
'tooltip-ca-nstab-user'           => 'Tazama ukurasa wa mtumiaji',
'tooltip-ca-nstab-special'        => 'Huu ni ukurasa maalum ambao hauwezi kuhaririwa',
'tooltip-ca-nstab-project'        => 'Tazama ukurasa wa mradi',
'tooltip-ca-nstab-image'          => 'Angalia ukurasa wa faili',
'tooltip-ca-nstab-template'       => 'Tazama kigezo',
'tooltip-ca-nstab-help'           => 'Tazama ukurasa wa msaada',
'tooltip-ca-nstab-category'       => 'Tazama ukurasa wa jamii',
'tooltip-minoredit'               => 'Tia alama kwamba hii ni badiliko dogo',
'tooltip-save'                    => 'Hifadhi mabadiliko yako',
'tooltip-preview'                 => 'Hakikisha mabadiliko yako, tafadhali fanya kabla ya kuhifadhi!',
'tooltip-diff'                    => 'Onyesha mabadiliko uliyofanya kwenye maandishi.',
'tooltip-compareselectedversions' => 'Tazama tofauti baina ya matoleo mawili uliochagua ya ukurasa huu.',
'tooltip-watch'                   => 'Fuatilia ukurasa huu kwenye maangalizi yako',
'tooltip-rollback'                => '"Rejesha" inarejesha (ma)sahihisho ya ukurasa huu yaliyofanyika na yule aliyeuhariri mwishoni, kwa kubofya mara moja tu.',
'tooltip-undo'                    => 'Ukibonyeza "tengua" sahihisho hili litarejeshwa na hakiki yake itaonekana pamoja na dirisha la kuhariri, ili uweze kuandika sababu na maelezo kwenye muhtasari.',

# Attribution
'anonymous'        => '{{PLURAL:$1|mtumiaji bila jina|watumiaji bila majina}} wa {{SITENAME}}',
'siteuser'         => 'mtumiaji $1 wa {{SITENAME}}',
'lastmodifiedatby' => 'Ukurasa huu umebadilishwa kwa mara ya mwisho saa $2, tarehe $1 na $3.',
'othercontribs'    => 'Ukurasa uliandikwa pia na $1.',
'others'           => 'wengine',
'siteusers'        => '{{PLURAL:$2|mtumiaji|watumiaji}} wa {{SITENAME}} $1',
'creditspage'      => 'Wandishi wa ukurasa',
'nocredits'        => 'Taarifa kuhusu wandishi wa ukurasa huu haipatikana.',

# Math errors
'math_unknown_error' => 'hitilafu isiyojulikana',

# Image deletion
'filedelete-old-unregistered' => 'The specified file revision "$1" is not in the database.',

# Browsing diffs
'previousdiff' => '← Badilisho lililopita',
'nextdiff'     => 'Badilisho lijalo →',

# Media information
'file-info-size'       => '(piseli $1 × $2, saizi ya faili: $3, aina ya MIME: $4)',
'file-nohires'         => '<small>Hakuna saizi kubwa zaidi.</small>',
'svg-long-desc'        => '(faili la SVG, husemwa kuwa piseli $1 × $2, saizi ya faili: $3)',
'show-big-image'       => 'Ukubwa wa awali',
'show-big-image-thumb' => '<small>Ukubwa wa hakikisho hili: piseli $1 x $2</small>',

# Special:NewFiles
'newimages'        => 'Mkusanyiko wa faili jipya',
'newimages-legend' => 'Chuja',
'ilsubmit'         => 'Tafuta',

# Bad image list
'bad_image_list' => 'Fomati ni hii:

Tunazingatia madondoo katika orodha (mistari inayoanza na *) tu.  
Inabidi kiungo cha kwanza katika mstari kiunge na faili baya.
Viungo vinavyofuata katika mstari ule ule vitaelewa kuwa mambo ya pekee, yaani kurasa zinazoruhusiwa kuonyesha faili hilo.',

# Metadata
'metadata'          => 'Data juu',
'metadata-help'     => 'Faili hili lina maarifa mengine, yamkini kutoka kemra au skana iliyotumiwa kulitengeneza au kuliandaa kwa tarakilishi.  
Kama faili limebadilishwa kutoka hali yake ya awali, inawezekana kwamba vipengele kadhaa vitakuwa tofauti kuliko hali ya picha ilivyo sasa.',
'metadata-expand'   => 'Onyesha maarifa vinaganaga',
'metadata-collapse' => 'Ficha maarifa vinaganaga',
'metadata-fields'   => 'Nyuga za data juu za EXIF zinazoorodheshwa katika ujumbe huu
utazingatiwa kwenye ukurasa wa picha wakati jedwali la data juu
likifupishwa. Nyuga zingine zitafichwa kama chaguo-msingi.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-componentsconfiguration' => 'Maana ya kila kijenzi',
'exif-makernote'               => 'Maelezo ya mtengenezaji',
'exif-usercomment'             => 'Maoni ya mtumiaji',
'exif-relatedsoundfile'        => 'Faili la sauti linalohusika',

'exif-subjectdistancerange-0' => 'Haujulikani',

# External editor support
'edit-externally'      => 'Tumia programu ya nje kuhariri faili hii',
'edit-externally-help' => '(Ona [http://www.mediawiki.org/wiki/Manual:External_editors maelezo (kwa Kiingereza)] kwa maarifa mengine.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'yote',
'imagelistall'     => 'zote',
'watchlistall2'    => 'zote',
'namespacesall'    => 'zote',
'monthsall'        => 'yote',

# E-mail address confirmation
'confirmemail_needlogin'   => 'Unahitajika $1 kuthibitisha anwani ya barua pepe yako.',
'confirmemail_success'     => 'Barua pepe yako imethibitishwa.
Sasa unaweza [[Special:UserLogin|kuingia]] na kuifurahia {{SITENAME}}.',
'confirmemail_loggedin'    => 'Anwani ya barua pepe yako imethibishwa sasa.',
'confirmemail_error'       => 'Kuna mambo yameenda kombo hifadhi ukamilisho wako.',
'confirmemail_subject'     => 'Barua pepe ya uthibitisho ya {{SITENAME}}',
'confirmemail_body'        => 'Kuna mtu, huenda ikawa wewe, kutoka anwani ya IP $1, amesajili akaunti "$2" na anwani ya barua pepe hii kwenye {{SITENAME}}.

Kuthibitisha ya kwamba akaunti hii inamilikiwa na wewe, unatakiwa kuwezesha njia ya barua pepe kwenye  {{SITENAME}}, fungua kiungo hiki katika kivinjari chako:

$3

na kama *huja* sajili akaunti hii, fuata kiungo hiki ili kubatilisha uthibitisho wa anwani ya barua pepe:

$5

Kodi hizi za uthibitisho zitaishia mnamo $4.',
'confirmemail_invalidated' => 'Uthibitisho wa barua pepe umebatilishwa.',
'invalidateemail'          => 'Batilisha barua pepe ya uthibitisho.',

# Delete conflict
'deletedwhileediting' => "'''Ilani''': Ukurasa huu ulifutwa ulipokwisha kuanza huuhariri!",

# action=purge
'confirm_purge_button' => 'Sawa',

# Multipage image navigation
'imgmultipageprev' => '← ukurasa uliotangulia',
'imgmultipagenext' => 'ukurasa ujao →',
'imgmultigo'       => 'Nenda!',

# Table pager
'table_pager_next'         => 'Ukurasa ujao',
'table_pager_prev'         => 'Ukurasa uliotangulia',
'table_pager_first'        => 'Ukurasa wa kwanza',
'table_pager_last'         => 'Ukurasa wa mwisho',
'table_pager_limit_submit' => 'Nenda',
'table_pager_empty'        => 'Hakuna matokeo',

# Watchlist editor
'watchlistedit-raw-titles' => 'Vichwa:',

# Watchlist editing tools
'watchlisttools-view' => 'Tazama mabadiliko yanayohusiana',
'watchlisttools-edit' => 'Tazama na hariri maangalizi',
'watchlisttools-raw'  => 'Hariri maangalizi ghafi',

# Special:Version
'version'                  => 'Toleo',
'version-specialpages'     => 'Kurasa maalum',
'version-other'            => 'Zingine',
'version-version'          => '(Toleo $1)',
'version-license'          => 'Ruhusa',
'version-software-version' => 'Toleo',

# Special:FilePath
'filepath'        => 'Njia ya faili',
'filepath-page'   => 'Faili:',
'filepath-submit' => 'Njia',

# Special:FileDuplicateSearch
'fileduplicatesearch-legend'   => 'Tafuta kifani',
'fileduplicatesearch-filename' => 'Jina la faili:',
'fileduplicatesearch-submit'   => 'Tafuta',

# Special:SpecialPages
'specialpages'               => 'Kurasa maalum',
'specialpages-note'          => '----
* Kurasa maalum ya kawaida.
* <strong class="mw-specialpagerestricted">Kurasa maalum zisizoonekana na wote.</strong>',
'specialpages-group-other'   => 'Kurasa maalum nyingine',
'specialpages-group-login'   => 'Ingia / sajili akounti',
'specialpages-group-changes' => 'Mabadiliko ya karibuni na kumbukumbu',
'specialpages-group-highuse' => 'Kurasa zinazotumika sana',
'specialpages-group-pages'   => 'Orodha za kurasa',

# Special:BlankPage
'blankpage' => 'Ukurasa tupu',

# Special:Tags
'tag-filter'        => 'Chujio cha [[Special:Tags|tagi]]:',
'tag-filter-submit' => 'Chuja',
'tags-title'        => 'Tagi',
'tags-edit'         => 'hariri',

# Database error messages
'dberr-header' => 'Wiki imekuta tatizo',

# HTML forms
'htmlform-reset'               => 'Tengua mabadiliko',
'htmlform-selectorother-other' => 'Nyingine',

# Add categories per AJAX
'ajax-confirm-save'            => 'Hifadhi',
'ajax-remove-category-summary' => 'Ondoa jamii ya "$1"',
'ajax-confirm-actionsummary'   => 'Chagua kitendo:',
'ajax-error-title'             => 'Hitilafu',
'ajax-error-dismiss'           => 'Sawa',

);
