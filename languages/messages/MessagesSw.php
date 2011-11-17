<?php
/** Swahili (Kiswahili)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ikiwaner
 * @author Jagwar
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
	'Allmessages'               => array( 'UjumbeZote' ),
	'Allpages'                  => array( 'KurasaZote' ),
	'Ancientpages'              => array( 'KurasazaZamani' ),
	'Blankpage'                 => array( 'KurasaTupu' ),
	'Block'                     => array( 'Zui', 'ZuiaIP', 'ZuiaMtumiaji' ),
	'Blockme'                   => array( 'Nizuiye' ),
	'Booksources'               => array( 'ZuiaChanzo' ),
	'BrokenRedirects'           => array( 'ElekezoIliovunjika' ),
	'Categories'                => array( 'Jamii' ),
	'Confirmemail'              => array( 'ThibitishaBaruapepe' ),
	'Contributions'             => array( 'Michango' ),
	'CreateAccount'             => array( 'SajiliAkaunti' ),
	'Deadendpages'              => array( 'KurasaZilizoondoshwa' ),
	'DeletedContributions'      => array( 'MichangoIliyofutwa' ),
	'Disambiguations'           => array( 'Maana' ),
	'DoubleRedirects'           => array( 'ElekezoMbili' ),
	'Emailuser'                 => array( 'BaruapepeyaMtumiaji' ),
	'Export'                    => array( 'Toa' ),
	'Fewestrevisions'           => array( 'MarejeoMadogo' ),
	'Import'                    => array( 'Ingiza' ),
	'BlockList'                 => array( 'OrodhayaIPZilizozuiliwa' ),
	'LinkSearch'                => array( 'TafutaKiungo' ),
	'Listadmins'                => array( 'OrodhayaWakabidhi' ),
	'Listbots'                  => array( 'OrodhayaVikaragosi' ),
	'Listfiles'                 => array( 'OrodhayaFali', 'OrodhayaPicha' ),
	'Listgrouprights'           => array( 'OrodhayaWasimamizi' ),
	'Listusers'                 => array( 'OrodhayaWatumiaji', 'OrodhayaMtumiaji' ),
	'Lockdb'                    => array( 'FungaDB' ),
	'Log'                       => array( 'Kumbukumbu' ),
	'Lonelypages'               => array( 'KurasaPweke' ),
	'Longpages'                 => array( 'KurasaNdefu' ),
	'MIMEsearch'                => array( 'TafutaMIME' ),
	'Mostcategories'            => array( 'JamiiZaidi' ),
	'Mostimages'                => array( 'FailiZilizoungwasana', 'PichaZilizoungwasana' ),
	'Mostlinked'                => array( 'KurasaZilizoungwasana' ),
	'Mostlinkedcategories'      => array( 'JamiiZilizoungwasana' ),
	'Mostlinkedtemplates'       => array( 'VigezoVilivyoungwasana' ),
	'Mostrevisions'             => array( 'MarejeoZaidi' ),
	'Movepage'                  => array( 'HamishaKurasa' ),
	'Mycontributions'           => array( 'MichangoYangu' ),
	'Mypage'                    => array( 'KurasaYangu' ),
	'Mytalk'                    => array( 'MajadilianoYangu' ),
	'Newimages'                 => array( 'FailiMpya', 'FailimpyazaPicha' ),
	'Newpages'                  => array( 'KurasaMpya' ),
	'Popularpages'              => array( 'KurasaMaarufu' ),
	'Preferences'               => array( 'Mapendekezo' ),
	'Prefixindex'               => array( 'KurasaKuu' ),
	'Protectedpages'            => array( 'KurasaZilizolindwa' ),
	'Protectedtitles'           => array( 'JinaLililolindwa' ),
	'Randompage'                => array( 'UkurasawaBahati' ),
	'Recentchanges'             => array( 'MabadalikoyaKaribuni' ),
	'Search'                    => array( 'Tafuta' ),
	'Shortpages'                => array( 'KurasaFupi' ),
	'Specialpages'              => array( 'KurasaMaalum' ),
	'Statistics'                => array( 'Takwimu' ),
	'Uncategorizedcategories'   => array( 'JamiiZisizopangwa' ),
	'Uncategorizedimages'       => array( 'FailiZisizonajamii' ),
	'Uncategorizedpages'        => array( 'KurasaZisizonajamii' ),
	'Uncategorizedtemplates'    => array( 'VigezoVisivyonajamii' ),
	'Undelete'                  => array( 'Usifute' ),
	'Unlockdb'                  => array( 'FunguaDB' ),
	'Unusedcategories'          => array( 'JamiiZisizotumika' ),
	'Unusedimages'              => array( 'FailiZisizotumika', 'PichaZisizotumika' ),
	'Upload'                    => array( 'Pakia' ),
	'Userlogin'                 => array( 'IngiaMtumiaji' ),
	'Userlogout'                => array( 'TokaMtumiaji' ),
	'Userrights'                => array( 'HakizaMtumiaji' ),
	'Version'                   => array( 'Toleo' ),
	'Wantedcategories'          => array( 'JamiiZinazotakikana' ),
	'Wantedfiles'               => array( 'FailiZinazotakikana' ),
	'Wantedpages'               => array( 'KurasaZinazotakikana', 'ViungoVilivyovunjika' ),
	'Wantedtemplates'           => array( 'VigezoVinavyotakikana' ),
	'Watchlist'                 => array( 'Maangalizi' ),
	'Whatlinkshere'             => array( 'VingoViungavyoUkurasahuu' ),
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
'tog-rememberpassword'        => 'Kumbuka kuingia kwangu pamoja na neno la siri katika kivinjari hiki (kwa muda usiozidi {{PLURAL:$1|siku}} $1)',
'tog-watchcreations'          => 'Weka kurasa nilizoumba katika maangalizi yangu',
'tog-watchdefault'            => 'Weka kurasa zote nilizohariri katika maangalizi yangu',
'tog-watchmoves'              => 'Weka kurasa zote nilizohamisha katika maangalizi yangu',
'tog-watchdeletion'           => 'Weka kurasa zote nilizofuta katika maangalizi yangu',
'tog-minordefault'            => 'Weka alama zote za mabadiliko madogo kama matumizi mbadala',
'tog-previewontop'            => 'Onyesha mandhari kabla ya sanduku la kuhariria',
'tog-previewonfirst'          => 'Onyesha mandhari unapoanza kuhariri',
'tog-nocache'                 => 'Kurasa zisiwekwe katika kache (akiba ya muda) ya kivinjari',
'tog-enotifwatchlistpages'    => 'Nitumie barua pepe pale kurasa zilizopo katika maangalizi yangu zikibadilishwa',
'tog-enotifusertalkpages'     => 'Nitumie barua pepe pale ukurasa wangu wa majadiliano ukiwa na mabadiliko',
'tog-enotifminoredits'        => 'Pia nitumie barua pale mabadiliko ya ukurasa yanapokuwa madogo tu',
'tog-enotifrevealaddr'        => 'Onyesha anwani ya barua pepe yangu katika barua pepe za taarifa',
'tog-shownumberswatching'     => 'Onyesha idadi ya watumiaji waangalizi',
'tog-oldsig'                  => 'Sahihi jinsi inayoonekana sasa:',
'tog-fancysig'                => 'Weka sahihi tu (bila kujiweka kiungo yenyewe)',
'tog-externaleditor'          => 'Tumia kiharirio cha nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye tarakilishi yako. [//www.mediawiki.org/wiki/Manual:External_editors Maelezo zaidi.])',
'tog-externaldiff'            => 'Tumia diff za nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye tarakilishi yako. [//www.mediawiki.org/wiki/Manual:External_editors Maelezo zaidi.])',
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
'category-subcat-count'          => '{{PLURAL:$2|Jamii hii ina kijamii hiki tu.|Jamii hii ina {{PLURAL:$1|kijamii kifuatacho|vijamii $1 vifuatavyo}}, kati ya jumla ya $2.}}',
'category-subcat-count-limited'  => 'Jamii hii ina {{PLURAL:$1|kijamii|vijamii $1 vifuatavyo}}.',
'category-article-count'         => '{{PLURAL:$2|Jamii hii ina ukurasa ufuatao tu.|Jamii hii ina {{PLURAL:$1|ukurasa ufuatao|kurasa $1 zifuatazo}}, kati ya jumla ya $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Ukurasa ufuatao upo|Kurasa $1 zifuatazo zipo}} kati ya kurasa za jamii hii.',
'category-file-count'            => '{{PLURAL:$2|Jamii hii ina faili hili tu.|{{PLURAL:$1|Faili linalofuata limo|Mafaili $1 yanayofuata yamo}} katika jamii hii, kati ya jumla ya $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Faili lifuatalo lipo|Mafaili $1 yafuatayo yapo}} kati ya mafaili ya jamii hii.',
'listingcontinuesabbrev'         => 'endelea',
'index-category'                 => 'Kurasa kuu',
'noindex-category'               => 'Kurasa zisizokuu',
'broken-file-category'           => 'Kurasa zenye viungo vilivyovunjika',

'about'         => 'Kuhusu',
'article'       => 'Makala',
'newwindow'     => '(Itafungua kwa dirisha jipya)',
'cancel'        => 'Batilisha',
'moredotdotdot' => 'Zaidi...',
'mypage'        => 'Ukurasa wangu',
'mytalk'        => 'Majadiliano yangu',
'anontalk'      => 'Majadiliano ya IP hii',
'navigation'    => 'Urambazaji',
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
'vector-action-addsection'       => 'Weka mada',
'vector-action-delete'           => 'Futa',
'vector-action-move'             => 'Hamisha',
'vector-action-protect'          => 'Linda',
'vector-action-undelete'         => 'Rudisha',
'vector-action-unprotect'        => 'Badilisha ulinzi',
'vector-simplesearch-preference' => 'Kuwezesha madokezo imara ya tafutaji (umbo la Vector tu)',
'vector-view-create'             => 'Anzisha',
'vector-view-edit'               => 'Hariri',
'vector-view-history'            => 'Fungua historia',
'vector-view-view'               => 'Soma',
'vector-view-viewsource'         => 'Kuonyesha kodi',
'actions'                        => 'Vitendo',
'namespaces'                     => 'Maeneo ya wiki',
'variants'                       => 'Vibadala',

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
'printableversion'  => 'Ukurasa wa kuchapika',
'permalink'         => 'Kiungo cha daima',
'print'             => 'Chapisha',
'view'              => 'Tazama',
'edit'              => 'Hariri',
'create'            => 'Anzisha kurasa',
'editthispage'      => 'Hariri ukurasa huu',
'create-this-page'  => 'Anzisha ukurasa huu',
'delete'            => 'Futa',
'deletethispage'    => 'Futa ukurasa huo',
'undelete_short'    => 'Rudisha {{PLURAL:$1|haririo moja|maharirio $1}}',
'viewdeleted_short' => 'Tazama {{PLURAL:$1|sahihisho lililofutwa moja|masahihisho yaliyofutwa $1}}',
'protect'           => 'Linda',
'protect_change'    => 'badilisha',
'protectthispage'   => 'Linda ukurasa huu',
'unprotect'         => 'Badilisha ulinzi',
'unprotectthispage' => 'Badilisha ulinzi wa ukurasa huu',
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
'otherlanguages'    => 'Lugha zingine',
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
'pool-queuefull'    => 'Foleni ya michakato imejaa',
'pool-errorunknown' => 'Hitilafu isiyojulikana',

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
'badaccess-groups' => 'Hatua uliyoomba inaweza kutekelezwa na watumiaji wa {{PLURAL:$2|kikundi hiki|vikundi hivi}} tu: $1.',

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
'collapsible-collapse'    => 'Kunja',
'collapsible-expand'      => 'Tanua',
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
'sort-descending'         => 'Pangia kwa kushuka',
'sort-ascending'          => 'Pangia kwa kupanda',

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
'error'                => 'Hitilafu',
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

Mkabidhi aliyeifunga ametoa maelezo haya: $1',
'missing-article'      => 'Database haijapata maneno ya ukurasa unaotafutwa, unaitwa "$1" $2.

Jambo kama hili kikawaida husababishwa kwa kufuatia kwisha kwa diff au historia ya kiungo ambacho kilifutwa.

Ikiwa hii siyo sababu, basi unaweza kukuta kuna mdudu katika bidhaa pepe.
Tafadhali ripoti hili kwa [[Special:ListUsers/sysop|mkabidhi]], na uache jina la URL.',
'missingarticle-rev'   => '(namba ya pitio: $1)',
'missingarticle-diff'  => '(Tofauti: $1, $2)',
'readonly_lag'         => 'Kihifadhidata kimejifunga chenyewe wakati seva za kifadhidata joli imedakwa na seva ya utawala',
'internalerror'        => 'Hitilafu ya ndani',
'internalerror_info'   => 'Hitilafu ya ndani: $1',
'fileappenderrorread'  => 'Haikuweza kusoma "$1" wakati wa kuambatanisha.',
'fileappenderror'      => 'Haikuweza kuongeza "$1" hadi "$2".',
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
'cannotdelete-title'   => 'Ukurasa "$1" hauwezi kufutwa',
'badtitle'             => 'Jina halifai',
'badtitletext'         => 'Jina la ukurasa ulilotaka ni batilifu, tupu, au limeungwa vibaya na jina la lugha nyingine au Wiki nyingine.  Labda linazo herufi moja au zaidi ambazo hazitumiki katika majina.',
'perfcached'           => 'Data zifuatazo zinatoka kwenye kache na huenda si ya kisasa.',
'perfcachedts'         => 'Data zifuatazo zimetoka kwenye kache iliobadilishwa mara ya mwisho saa $3, tarehe $2.',
'querypage-no-updates' => 'Mabadiliko kwa ajili ya ukurasa huu yamesimamishwa.
Data za hapa haziwezi kunawirishwa kwa sasa.',
'wrong_wfQuery_params' => 'Parameta za ulizio zilizoingizwa wfQuery() na zisizo sahihi ni<br />
Kitenda: $1<br />
Ulizio: $2',
'viewsource'           => 'Onyesha kodi za ukurasa',
'actionthrottled'      => 'Tendo limesimamishwa',
'actionthrottledtext'  => 'Ikiwa kama hatua ya kupambana na uharibifu, umefika kikomo katika kutenda jambo hili kwa mara nyingi mno tena kwa kipindi cha muda mfupi kama huu, na umevuka kiwango hiki.
Tafadhali jaribu tena baada ya muda mfupi.',
'protectedpagetext'    => 'Ukurasa huu umefungwa ili kuepuka uhariri.',
'viewsourcetext'       => 'Unaweza kutazama na kuiga chanzo cha ukurasa huu:',
'viewyourtext'         => "Unaweza kutazama na kuiga chanzo cha ''maharirio yako'' katika ukurasa huu:",
'protectedinterface'   => 'Ukurasa huu unatoa maelezo ya msingi ya bidhaa pepe, na pia umefungwa ili kuzuiya uharibifu.',
'editinginterface'     => "'''Ilani:''' Una hariri ukurasa unaotumika kutoa maelezo ya msingi ya bidhaa pepe.
Mabadiliko katika ukurasa huu yataathiri mwonekano mzima wa viungo vya watumiaji wengine.
Kwa lengo la kutaka kutafsiri, tafadhali fikiria kutumia  [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], kwa kuweka miradi ya MediaWiki kwa kienyeji.",
'sqlhidden'            => '(maulizo ya SQL yamefichwa)',
'cascadeprotected'     => 'Ukurasa huu umekingwa usihaririwe, kwa sababu umejumlishwa katika {{PLURAL:$1|ukurasa ufuatao, ambao umekingwa|kurasa zifuatazo, ambazo zimekingwa}} na chagua la "cascadi" iliwashwa:
$2',
'namespaceprotected'   => "Huna ruhusa ya kuhariri kurasa za eneo la wiki la '''$1'''.",
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
'yourpassword'               => 'Neno la siri:',
'yourpasswordagain'          => 'Andika tena neno la siri',
'remembermypassword'         => 'Kumbuka kuingia kwangu katika kivinjari hiki (kwa muda usiozidi {{PLURAL:$1|siku}} $1)',
'securelogin-stick-https'    => 'Endelea kuunganishwa na HTTPS baada ya kuingia',
'yourdomainname'             => 'Tovuti yako:',
'externaldberror'            => 'Huenda kulikuwa na hitilafu ya database au labda hauruhusiwi kubadilisha akaunti yako ya nje.',
'login'                      => 'Ingia',
'nav-login-createaccount'    => 'Ingia/ sajili akaunti',
'loginprompt'                => 'Lazima tarakalishi yako ipokee kuki ili uweze kuingia kwenye {{SITENAME}}.',
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
'userlogin-resetlink'        => 'Umesahau maelezo yako ya kuingia?',
'createaccountmail'          => 'Kwa barua pepe',
'createaccountreason'        => 'Sababu:',
'badretype'                  => 'Maneno uliyoyaandika ni tofauti.',
'userexists'                 => 'Jina la mtumiaji uliloingiza tayari linatumika.
Tafadhali chagua jina lingine.',
'loginerror'                 => 'Hitilafu ya kuingia',
'createaccounterror'         => 'Haikufaulu kuanzisha akaunti: $1',
'nocookiesnew'               => "Umesajiliwa, lakini bado hujaingizwa. {{SITENAME}} inatumia ''kuki'' ili watumiaji waingizwe. Tarakilishi yako inazuia ''kuki''. Tafadhali, ondoa kizuizi hicho halafu uingie kwa kutumia jina jipya na neno la siri.",
'nocookieslogin'             => '{{SITENAME}} inatumia kuki ili watumiaji waweze kuingia.
Tarakilishi yako inakataa kupokea kuki.
Tafadhali, ondoa kizuizi hicho, halafu jaribu tena.',
'nocookiesfornew'            => 'Akaunti ya mtumiaji haijaianzishwa,kwa vile hatujaweza kuthibitisha lilitoka wapi.
Hakikisha kwamba kuki zimewezeshwa kwenye tarakalishi yako, fungua upya ukurasa huu na ujaribu tena.',
'noname'                     => 'Hauja dhihilisha jina la mtumiaji.',
'loginsuccesstitle'          => 'Umefaulu kuingia',
'loginsuccess'               => "'''Umeingia {{SITENAME}} kama \"\$1\".'''",
'nosuchuser'                 => 'Hakuna mtumiaji mwenye jina "$1".
Kumbuka kwamba programu inatofautishana kati ya herufi kubwa na ndogo.
Labda umeandika vibaya, au [[Special:UserLogin/signup|sajili akaunti mpya]].',
'nosuchusershort'            => 'Hakuna mtumiaji mwenye jina "$1". Labda umeandika vibaya.',
'nouserspecified'            => 'Lazima uandike jina la mtumiaji.',
'login-userblocked'          => 'Mtumiaji huyu amezuiwa. Hawezi kuingia.',
'wrongpassword'              => 'Umeingiza neno la siri la makosa.
Jaribu tena.',
'wrongpasswordempty'         => 'Neno la siri lilikuwa tupu. Jaribu tena.',
'passwordtooshort'           => 'Ni lazima neno la siri liwe na {{PLURAL:$1|herufi}} $1 au zaidi.',
'password-name-match'        => 'Neno lako la siri lazima liwe tofauti na jina lako la mtumiaji.',
'password-login-forbidden'   => 'Utumiaji wa jina hili na neno lake siri imekatazwa.',
'mailmypassword'             => 'Nitume neno la siri jipya kwa barua-pepe',
'passwordremindertitle'      => 'Neno la siri jipya la muda kwa ajili ya {{SITENAME}}',
'passwordremindertext'       => 'Mtu mmoja (yamkini wewe, kutoka anwani ya IP $1)
ameulizia neno jipya la siri kwa {{SITENAME}} ($4).
Neno la siri la muda kwa mtumiaji "$2" sasa ni "$3".
Inatakiwa uingie na ubadilishe neno lako la siri sasa. Neno lako la siri la muda litaishia baada ya siku {{PLURAL:$5|moja|$5}}.

Kama mtu mwingine ametoa ombi hili au kama umekumbuka neno lako la siri na
umeamua kutoibadilisha, unaweza kupuuza ujumbe huu na
kuendelea kulitumia neno lako la siri la awali.',
'noemail'                    => 'Hatuna anwani ya barua pepe kwa mtumiaji  "$1".',
'noemailcreate'              => 'Unahitajika utoe anwani halali ya barua pepe',
'passwordsent'               => 'Neno jipya la siri limeshatumwa kwenye anwani ya barua pepe ya "$1".
Tafadhali, ingia baada ya kulipokea.',
'blocked-mailpassword'       => 'Anwani yako ya IP imezuiwa kuhariri {{SITENAME}}, kwa hiyo hairuhusiwi kuomba neno jipya la siri, kwa lengo la kuzuia uharibifu.',
'eauthentsent'               => 'Tumekutuma barua pepe ili kuhakikisha anwani yako.
Kabla ya kutuma barua pepe nyingine kwenye akaunti hiyo, itabidi ufuate maelezo katika barua utakayopokea,
kuthibitisha kwamba wewe ndiyo ni mwenye akaunti.',
'throttled-mailpassword'     => 'Kikumbusho cha neno la siri tayari kimeshatumwa kwako, ndani ya {{PLURAL:$1|saa iliyopita|masaa $1 yaliyopita}}.
Ili kuzuia uhuni, ni kikumbusho kimoja tu cha neno la siri ambacho utatumiwa kwa kila {{PLURAL:$1|saa|masaa $1}}.',
'mailerror'                  => 'Hitilafu ilitokea wakati ulivyoituma barua pepe: $1',
'acct_creation_throttle_hit' => 'Watembeleaji wa wiki hii waliotumia anwani yako ya IP, wamefungua {{PLURAL:$1|akaunti 1|akaunti $1}} katika siku iliyopita, ambayo inaruhusiwa hasa kwa kipindi cha muda huu.
Majibu yake, watumiaji wanaotumia anwani ya IP hii hawawezi kufungua akaunti nyingine tena kwa muda huu.',
'emailauthenticated'         => 'Anwani yako ya barua pepe iliyakinishwa saa $3, tarehe $2.',
'emailnotauthenticated'      => 'Anwani ya barua pepe yako bado haijahakikiwa.
Hakuna hata barua  pepe moja itakayotumwa kwa lolote katika vipengele hivi vifuatavyo.',
'noemailprefs'               => 'Weka anwani ya barua pepe kwenye mapendekezo ili uweze kutumia zana hizi.',
'emailconfirmlink'           => 'Yakinisha anwani yako ya barua pepe',
'invalidemailaddress'        => 'Anwani ya barua pepe haiwezi kukubalika ikiwa inaonekana kuwa na muundo batili.
Tafadhali ingiza anwani ya miundo-mizuri au acha tupu kipengele hicho.',
'cannotchangeemail'          => 'Anwani za barua pepe haziwezi kubadilishwa katika akaunti za wiki hii.',
'accountcreated'             => 'Akaunti imeundwa',
'accountcreatedtext'         => 'Akaunti imeundwa kwa ajili ya mtumiaji $1.',
'createaccount-title'        => 'Kuanzisha akaunti kwa ajili ya {{SITENAME}}',
'createaccount-text'         => 'Kuna mtu amesajili akaunti kwa kutumia anwani ya barua pepe yako kwenye  {{SITENAME}} ($4) anaitwa "$2", yenye neno la siri "$3".
Inabidi uingie na kisha ubadilishe neno la siri lako sasa.

Unaweza kupuuza ujumbe huu, endapo akaunti hii ilianzishwa kimakosa.',
'usernamehasherror'          => 'Jina la mtumiaji haliwezi kuwa na herufi ya alama ya reli',
'login-throttled'            => 'Umejaribu kadha wa kadha kuingia akaunti hii.
Tafadhali subiri kwanza kabla ya kujaribu tena.',
'login-abort-generic'        => 'Kuingia kwako hakujafaulu: Iliachishwa',
'loginlanguagelabel'         => 'Lugha: $1',
'suspicious-userlogout'      => 'Ombi lako la kutoka kwenye akaunti yako limehiniwa, kwa sababu inaonekana kwamba ombi lilitumwa na kivinjari kilichoharibika au seva ya kuwakilisha yenye kache.',

# E-mail sending
'user-mail-no-addy' => 'Umejaribu kutuma barua pepe bila anwani ya barua pepe.',

# Change password dialog
'resetpass'                 => 'Kubadilisha neno la siri',
'resetpass_announce'        => 'Umeingia na kodi za barua pepe za muda tu.
Kumalizia kuingia ndani, ni lazima urekebishe neno la siri jipya hapa:',
'resetpass_header'          => 'Kubadilisha neno la siri la akaunti',
'oldpassword'               => 'Neno la siri la zamani',
'newpassword'               => 'Neno jipya la siri:',
'retypenew'                 => 'Andika neno la siri tena:',
'resetpass_submit'          => 'Rekebisha neno la siri na uingie',
'resetpass_success'         => 'Neno lako la siri limefanikiwa kubadilishwa! Sasa unaingia...',
'resetpass_forbidden'       => 'Maneno ya siri hayawezi kubadilishwa',
'resetpass-no-info'         => 'Lazima uwe umeingia ili kuweza kutumia kurasa hii moja kwa moja.',
'resetpass-submit-loggedin' => 'Badilisha neno la siri',
'resetpass-submit-cancel'   => 'Batilisha',
'resetpass-wrong-oldpass'   => 'Neno la siri la muda au la sasa ni batili.
Inawezekana ikawa tayari umefaulu kubadilisha neno lako la siri au neno la siri jipya la muda.',
'resetpass-temp-password'   => 'Neno la siri la muda:',

# Special:PasswordReset
'passwordreset'                    => 'Seti upya neno la siri',
'passwordreset-text'               => 'Jaza fomu hii ili upate barua pepe inayotoa maelezo ya akaunti yako.',
'passwordreset-legend'             => 'Seti upya neno la siri',
'passwordreset-disabled'           => 'Kuweka neno la siri jipya kumeshitishwa katika wiki hii.',
'passwordreset-pretext'            => '{{PLURAL:$1||Ingiza moja kati ya data hizi hapo chini}}',
'passwordreset-username'           => 'Jina la mtumiaji:',
'passwordreset-capture'            => 'Kutazama barua pepe itakayotumwa',
'passwordreset-email'              => 'Anwani ya barua pepe:',
'passwordreset-emailtitle'         => 'Maelezo ya akaunti kwenye {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'Kuna mtu (huenda ikawa ni wewe, kutoka anwani ya IP $1) aliyeomba kukumbushwa kuhusu maelezo ya akaunti yako katika {{SITENAME}} ($4). {{PLURAL:$3|Akaunti inayofuata imeunganishwa|Akaunti zinazofuata zimeunganishwa}} na anwani ya barua pepe hii:

$2

{{PLURAL:$3|Neno la siri hili litakwisha|Maneno ya siri haya yatakwisha}} baada ya siku {{PLURAL:$5|$5}}.
Tafadhali ingia sasa na uchague neno jipya la siri. Kama mtu mwingine ameomba hili, au ikiwa umekumbuka neno lako la siri na hutaki kulibadilisha tena, basi usijali ujumbe huu, na uendelee kutumia neno la siri lako la zamani.',
'passwordreset-emailtext-user'     => 'Mtumiaji $1 kwenye {{SITENAME}} ameomba akumbushwe maelezo ya akaunti yako katika {{SITENAME}} ($4). {{PLURAL:$3|Akaunti inayofuata imeunganishwa|Akaunti zinazofuata zimeunganishwa}} na anwani ya barua pepe hii:

$2

{{PLURAL:$3|Neno la siri hili litakwisha|Maneno ya siri haya yatakwisha}} baada ya siku {{PLURAL:$5|$5}}.
Tafadhali ingia sasa na uchague neno jipya la siri. Kama mtu mwingine ameomba hili, au ikiwa umekumbuka neno lako la siri na hutaki kulibadilisha tena, basi usijali ujumbe huu, na uendelee kutumia neno la siri lako la zamani.',
'passwordreset-emailelement'       => 'Jina la mtumiaji: $1
Neno la siri la muda: $2',
'passwordreset-emailsent'          => 'Barua pepe ya ukumbusho imetumwa.',
'passwordreset-emailsent-capture'  => 'Barua pepe ya kukumbusha ilitumwa, na inaonekana chini.',
'passwordreset-emailerror-capture' => 'Barua pepe ya kukumbusha iliandikwa, kama inayoonekana chini, lakini kuipeleka kwa mtumiaji ilishindikana: $1',

# Special:ChangeEmail
'changeemail'          => 'Badilisha anwani ya barua pepe',
'changeemail-header'   => 'Badilisha anwani ya barua pepe ya akaunti yako',
'changeemail-text'     => 'Jaza fomu hii ili kubadilisha anwani yako ya barua pepe. Itabidi uingize neno lako la siri ili kukamilisha badiliko hili.',
'changeemail-no-info'  => 'Lazima uwe umeingia ili kuweza kutumia kurasa hii moja kwa moja.',
'changeemail-oldemail' => 'Anwani ya barua pepe ya sasa:',
'changeemail-newemail' => 'Anwani mpya ya barua pepe:',
'changeemail-none'     => '(hakuna)',
'changeemail-submit'   => 'Badilisha anwani ya barua pepe',
'changeemail-cancel'   => 'Batilisha',

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
'preview'                          => 'Hakiki',
'showpreview'                      => 'Onyesha hakikisho la mabadiliko',
'showlivepreview'                  => 'Tazama moja kwa moja',
'showdiff'                         => 'Onyesha mabadiliko',
'anoneditwarning'                  => "'''Ilani:''' Wewe hujaingia rasmi kwenye tovuti. Anwani ya IP ya tarakilishi yako itahifadhiwa katika historia ya uhariri wa ukurasa huu.",
'anonpreviewwarning'               => "''Hujaingia rasmi kwenye tovuti. Ukihifadhi ukurasa anwani ya IP ya tarakilishi yako itahifadhiwa katika historia ya uhariri wa ukurasa huu.''",
'missingsummary'                   => "'''Taarifa:''' Hujaandika muhtasari ya kuhariri.
Ukibonyeza 'Hifadhi ukurasa' tena, badilisho lako litahifadhiwa bila muhtasari.",
'missingcommenttext'               => 'Tafadhali andika muhtasari chini.',
'missingcommentheader'             => "'''Kikumbusho:''' Hujaweka kichwa cha habari/mada kwa ajili ya maelezo haya.
Iwapo utabonyeza tena Hifadhi, haririo lako litahifadhiwa bila kichwa cha habari.",
'summary-preview'                  => 'Hakikisho la muhtasari:',
'subject-preview'                  => 'Hakikisha kichwa cha habari/mada:',
'blockedtitle'                     => 'Mtumiaji amezuiwa',
'blockedtext'                      => "'''Jina lako la mtumiaji au anwani yako ya IP imezuiwa.'''

Umezuiwa na $1.
Sababu aliyetambua ni ''$2''

* Mwanzo wa uzuio: $8
* Mwisho wa uzuio: $6
* Aliyezuiwa: $7

Unaweza kuwasiliana na $1 au [[{{MediaWiki:Grouppage-sysop}}|mkabidhi]] kuzungumza uzuio.
Huwezi kutumia kipengele cha 'kumtuma mtumiaji barua pepe' isipopatikana anwani halisi ya barua pepe katika
[[Special:Preferences|mapendekezo ya akaunti]] yako, na usipozuiwa kuitumia.
Anwani yako ya IP ni $3, na namba ya uzuio ni #$5. Tafadhali taja namba hizi ukitaka kuwasiliana kuhusu uzuio huu.",
'autoblockedtext'                  => 'Anwani yako ya IP imezuiwa na mashine kwa sababu ilikuwa ikitumiwa na mtumiaji mwingine, ambaye amezuiliwa na $1.
Sababu zilizotolewa ni hizi:

:\'\'$2\'\'

* Imeanza kuzuiwa: $8
* Mwisho wa kuzuiwa: $6
* Mzuiwaji aliyenuiwa: $7

Unaweza kuwasiliana na $1 au mmoja kati ya [[{{MediaWiki:Grouppage-sysop}}|wakabidhi]] wengine ili kujadili uzuio.

Elewa kwamba huwezi kutumia kipengele cha "umtumie barua pepe mtuaji huyu" bila ya kuwa na anwani halali iliosajiliwa kwenye [[Special:Preferences|mapendekezo ya mtumiaji]] na uwe hujazuiliwa kuitumia.

Anwani yako ya sasa ya IP ni $3, na namba ya kuzuiliwa ni #$5.
Tafadhali jumlisha maelezo yote ya juu kwenye kila ulizo utakalolifanya.',
'blockednoreason'                  => 'sababu haikutajwa',
'whitelistedittext'                => 'Inabidi $1 ili uweze kuhariri kurasa.',
'confirmedittext'                  => 'Lazima uthibitishe anwani ya barua pepe yako kabla ya kuhariri kurasa.
Tafadhali thibitisha anwani ya barua pepe yako kupitia [[Special:Preferences|mapendekezo yako ya mtumiaji]].',
'nosuchsectiontitle'               => 'Fungu hili halipatikani',
'nosuchsectiontext'                => 'Umejaribu kuhariri sehemu ambayo haipo.
Labda ilihamishwa au ilifutwa endapo unatazama ukurasa.',
'loginreqtitle'                    => 'Unatakiwa kuingia kwanza',
'loginreqlink'                     => 'uingie',
'loginreqpagetext'                 => 'Inabidi $1 ili uweze kutazama kurasa zingine.',
'accmailtitle'                     => 'Neno la siri limetumwa.',
'accmailtext'                      => "Neno la siri limetolewa na programu kwa ajili ya [[User talk:$1|$1]] na limetumwa kwa $2.

Unaweza kubadilisha neno la siri hili kwenye ukurasa wa ''[[Special:ChangePassword|kubadilisha neno la siri]]'' baada ya kuingia kwenye wiki.",
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
'userpage-userdoesnotexist'        => 'Akaunti ya mtumiaji "<nowiki>$1</nowiki>" haijasajilishwa.
Ukitaka kuanzisha au kuhariri ukurasa huu tafadhali ucheki jina la akaunti.',
'userpage-userdoesnotexist-view'   => 'Akaunti ya mtumiaji "$1" haijasajilishwa.',
'blocked-notice-logextract'        => 'Mtumiaji huyu bado amezuiwa.
Rejea kumbukumbu ya uzuio ya mwisho inayoandikwa chini:',
'clearyourcache'                   => "Elewa - Baada ya kuhifadhi, itakubidi uzungushe kivinjali kache chako ili kuona mabadiliko.'''
'''Mozilla / Firefox / Safari:''' shikiria ''Shift'' wakati unabonyeza ''Reload'', au aidha bonyeza ''Ctrl-F5'' au ''Ctrl-R'' (''Command-R'' kwa Mac);
'''Konqueror: '''bonyeza ''Reload'' au bonyeza ''F5'';
'''Opera:''' futa kache kwenye ''Tools → Preferences'';
'''Internet Explorer:''' shikiria ''Ctrl'' wakati unabonyeza ''Refresh,'' au bonyeza ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Kidokezi:''' Bonyeza \"{{int:showpreview}}\" kujaribu CSS hii mpya kabla hujaihifadhi.",
'userjsyoucanpreview'              => "'''Kidokezi:''' Bonyeza \"{{int:showpreview}}\" kujaribu JS hii mpya kabla hujaihifadhi.",
'usercsspreview'                   => "'''Kumbuka kwamba unahakiki mandhari ya CSS za ukurasa wako tu.'''
'''Haijahifadhiwa bado!'''",
'userjspreview'                    => "'''Kumbuka kwamba unajaribu/kuhakiki mandhari ya ukurasa wako wa JavaScript tu.'''
'''Haijahifadhiwa bado!'''",
'sitecsspreview'                   => "'''Kumbuka kwamba unahakiki tu mandhari ya CSS hii.'''
'''Haijahifadhiwa bado!'''",
'sitejspreview'                    => "'''Kumbuka kwamba unahakiki tu mandhari ya JavaScript hii.'''
'''Haijahifadhiwa bado!'''",
'userinvalidcssjstitle'            => "'''Onyo:''' Hakuna umbo \"\$1\".
Kumbuka kwamba desturi ya kurasa za .css na .js hutumia herufi ndogo, yaani, {{ns:user}}:Foo/vector.css na si {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Imesasishwa)',
'note'                             => "'''Taarifa:'''",
'previewnote'                      => "'''Hii ni hakikisho tu; mabadiliko hayajahifadhiwa bado!'''",
'previewconflict'                  => 'Hakikisho hii inaonyesha maandiko yaliyopo sanduku la juu yataonekayo ukiyahifadhi.',
'session_fail_preview'             => "'''Pole! Hatukuweza kuhifadhi sahihisho lako kwa sababu data za kipindi zilipotelewa.'''
Tafadhali jaribu tena.
Kama bado haifanyi kazi, jaribu [[Special:UserLogout|kutoka kwenye akaunti yako]], halafu ingia tena.",
'session_fail_preview_html'        => "'''Kumradhi! Hatukuweza kushughulikia haririo lako kwa kufuatia upungufu wa ukaaji wa data.'''

''Kwa sababu {{SITENAME}} ina HTML zilizowezeshwa, hakikio limefichwa ikiwa kama tahadhari dhidi ya mashambulio ya JavaScript.''

'''Iwapo hili ni haririo la jaribio halali, tafadhali jaribu tena.'''
Iwapo bado haifanyikazi, jaribu [[Special:UserLogout|kutoka]] na uingie tena.",
'token_suffix_mismatch'            => "'''Uhariri wako umekataliwa kwa sababu koteja yako imeharibu herufi za ishara ya kuhariri.'''
Haririo umekataliwa ili kuzuia uharibifu wa maandishi ya kurasa.
Haya hutokea kwa muda ambao unatumia huduma ya seva ya wavu isiyotiwa jina na yenye hitilafu nyingi.",
'edit_form_incomplete'             => "'''Baadhi ya sehemu za fomu ya kuhariria hazikufikia seva. Hakikisha kwamba haririo lako bado lipo na kisha jaribu tena.'''",
'editing'                          => 'Kuhariri $1',
'editingsection'                   => 'Unahariri $1 (fungu)',
'editingcomment'                   => 'Una hariri $1 (sehemu mpya)',
'editconflict'                     => 'Mgongano wa kuhariri: $1',
'explainconflict'                  => "Mtu mwingine amebadilisha ukurasa huu tangu ulipoanza kuihariri.
Sanduku la juu inaonyesha maandiko yaliyopo sasa hivi kwenye ukurasa.
Mabadiliko yako yanaonyeshwa kwenye sanduku la chini.
Inabidi uingize mabadiliko yako ndani ya sanduku la juu.
Ni maandiko yaliyopo ndani ya sanduku la juu '''tu''' ambayo yatahifadhiwa utakapobonyeza \"{{int:savearticle}}\".",
'yourtext'                         => 'Maandishi yako',
'storedversion'                    => 'Pitio lililohifadhiwa mwishoni',
'nonunicodebrowser'                => "'''Ilani: Kivinjari chako hakikubaliani na Unicode.''' 
Ili uweze kuhariri kurasa sawasawa, herufi zisizo za ASCII zitaonekana katika sanduku la kuhariri kama kodi za hexadecimali.",
'editingold'                       => "'''ANGALIA: Unakuwa unahariri toleo la zamani la ukurasa huu.
Ukiendelea kulihariri, mabadilisho yote yaliyofanywa tangu pale yatapotezwa.'''",
'yourdiff'                         => 'Tofauti',
'copyrightwarning'                 => "Tafadhali zingatia kwamba makala yote ya {{SITENAME}} unayoyaandika yanafuata $2 (tazama $1 kwa maelezo zaidi).
Usipotaka maandishi yako yaweze kuharirishwa bure na kutolewa wakati wowote, basi usiyaandike hapa.<br />
Unakuwa unaahidi kwamba maandishi unayoyaingia ni yako tu, au uliyapata kutoka bure au ni mali ya watu wote. '''USITOLEE MAKALA YALIYOHIFADHIWA HAKI ZAO ZA KUTUMIWA BILA KUPATA RUHUSA HALALI!'''",
'copyrightwarning2'                => "Tafadhali elewa kwamba michango yote ya {{SITENAME}} inaweza kuhahariwa, kubadilishwa, au kuondolewa na wachangiaji wengine.
Ikiwa hutaki maandishi yako yasihaririwe na yeyote, basi usiyaweke hapa.<br />
Pia una tuahidi kwamba umeandika haya wewe mwenyewe, au umenakili kutoka katika tovuti ya umma au chanzo cha wazi sawa na hiki (tazama  $1 kwa maelezo).
'''Usiandike makala yenye hakimiliki bila ya ruhusa halali!'''",
'longpageerror'                    => "'''Hitilafu: Maandishi uliyoyaweka yana urefu wa kilobati $1, ambayo ni marefu kuliko kiwango cha kawaida cha kilobaiti $2.'''
Hayawezi kuhifadhiwa.",
'readonlywarning'                  => "'''Onyo: Hifadhidata imefungwa kwa ajili ya matengenezo, kwa hiyo hautakuwa na uwezo wa kuhifadhi maharirio yako kwa sasa.'''
Unaweza kukata-na-kabandika maandishi yako kwenye faili na kulihifadhi kwa ajili ya baadaye.

Mkabidhi aliyefunga ametoa maelezo haya: $1",
'protectedpagewarning'             => "'''ILANI: Ukurasa huu unakingwa kwa hiyo watumiaji wenye haki za wakabidhi tu wanaweza kuuhariri.'''
Rejea kumbukumbu ya mwisho inayoandikwa chini:",
'semiprotectedpagewarning'         => "'''Ilani:''' Ukurasa huu umefungwa kwa hiyo watumiaji waliojisajili tu ndiyo wanaweza kuuhariri.
Rejea kumbukumbu ya mwisho inayoandikwa chini:",
'cascadeprotectedwarning'          => "'''Ilani:''' Ukurasa huu umefungwa kwa hiyo watumiaji wenye uwezo wa ukabidhi tu ndiyo wanaweza kuuhariri, kwa sababu umejumlishwa kwenye {{PLURAL:$1|ukurasa huu mwingine wenye|kurasa hizi zingine zenye}} ulindaji kwa kurasa chini yake:",
'titleprotectedwarning'            => "'''Ilani: Ukurasa umefungwa, kwa hiyo [[Special:ListGroupRights|wezo maalumu]] zinahitajika ili kuuanzisha ukurasa huu.'''
Rejea kumbukumbu ya mwisho inayoandikwa chini:",
'templatesused'                    => '{{PLURAL:$1|Kigezo kinachotumiwa|Vigezo vinavyotumiwa}} kwenye ukurasa huu:',
'templatesusedpreview'             => '{{PLURAL:$1|Kigezo kinachotumiwa|Vigezo vinavyotumiwa}} katika mandhari haya:',
'templatesusedsection'             => '{{PLURAL:$1|Kigezo kinachotumika|Vigezo vinavyotumika}} katika sehemu hii:',
'template-protected'               => '(kulindwa)',
'template-semiprotected'           => '(ulindaji kwa kiasi)',
'hiddencategories'                 => 'Ukurasa huu uliomo katika jamii $1 {{PLURAL:$1|iliofichwa|zilizofichwa}}:',
'nocreatetitle'                    => 'Si wote wanaoweza kuanzisha ukurasa',
'nocreatetext'                     => '{{SITENAME}} imebana uwezekano kutengeneza kurasa mpya. Unaweza kurudia na kuhariri kurasa zilizomo, au [[Special:UserLogin|ingia au anza akaunti]].',
'nocreate-loggedin'                => 'Huna ruhusa ya kuanzisha ukurasa mpya.',
'sectioneditnotsupported-title'    => 'Kuhariri sehemu kwa sehemu haiwezikani',
'sectioneditnotsupported-text'     => 'Haiwezikani kuhariri ukurasa huu sehemu kwa sehemu.',
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

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Ilani:''' Kigezo kinajumlisha ukubwa uliozidi mno.
Baadhi ya vigezo havitaweza kuingizwa.",
'post-expand-template-inclusion-category' => 'Kurasa ambapo vigezo vinajumlisha ukubwa uliozidi',
'post-expand-template-argument-warning'   => "'''Ilani:'''Ukurasa huu una angalau kigezo kimoja au zaidi vye matatizo, ambavyo ukubwa wake wa kupanuliwa unazidi mipaka. Kwa hiyo sehemu za vigezo havitaonekana.",
'post-expand-template-argument-category'  => 'Kurasa zenye shida ya vigezo vilivyorukwa',

# "Undo" feature
'undo-success' => 'Sahihisho linaweza kutenguliwa.
Tafadhali tazama linganisho lililopo chini ili kuthibitisha kwamba kutengua ndiyo inayotakiwa, na kisha uhifadhi mabadiliko ili kukamilisha kutengua sahihisho.',
'undo-failure' => 'Haririo halikuweza kutenguliwa kwa kufuatia mgongano wa maharirio katikati.',
'undo-norev'   => 'Sahihisho halikuweza kutenguliwa kwa sababu halipo au limeshafutwa.',
'undo-summary' => 'Tengua pitio $1 lililoandikwa na [[Special:Contributions/$2|$2]] ([[User talk:$2|Majadiliano]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kushindwa kusajili akaunti',
'cantcreateaccount-text' => "Kusajili akaunti kwa kutumia anwani ya IP hii ('''$1''') imezuiwa na [[User:$3|$3]].

Sababu iliyotolewa na $3 ni ''$2''",

# History pages
'viewpagelogs'           => 'Tazama kumbukumbu kwa ukurasa huu',
'nohistory'              => 'Hakuna historia ya kuhariri kwa ajili ya ukurasa huu.',
'currentrev'             => 'Toleo la sasa',
'currentrev-asof'        => 'Toleo la sasa la $1',
'revisionasof'           => 'Pitio la $1',
'revision-info'          => 'Pitio la $1 aliyefanya $2',
'previousrevision'       => '← Pitio lililotangulia',
'nextrevision'           => 'Pitio linalofuata →',
'currentrevisionlink'    => 'Toleo la sasa',
'cur'                    => 'sasa',
'next'                   => 'linalofuata',
'last'                   => 'kabla',
'page_first'             => 'ya kwanza',
'page_last'              => 'ya mwisho',
'histlegend'             => "Chagua tofauti: tia alama katika vitufe redio kulinganisha mapitio, na bonyeza \"enter\" au kitufe hapo chini.<br />
Ufunguo: '''({{int:cur}})''' = tofauti na toleo la sasa, '''({{int:last}})''' = tofauti na pitio lililotangulia, '''({{int:minoreditletter}})''' = badiliko dogo.",
'history-fieldset-title' => 'Fungua historia',
'history-show-deleted'   => 'Zilizofutwa tu',
'histfirst'              => 'Mwanzoni',
'histlast'               => 'Mwishoni',
'historysize'            => '({{PLURAL:$1|baiti}}) $1',
'historyempty'           => '(tupu)',

# Revision feed
'history-feed-title'          => 'Kumbukumbu za mapitio',
'history-feed-description'    => 'Kumbukumbu za mapitio ya ukurasa huu',
'history-feed-item-nocomment' => '$1 kwenye $2',
'history-feed-empty'          => 'Ukurasa ulioomba haupatikani.
Labda umeshafutwa, au umebadilishwa jina.
Jaribu [[Special:Search|kutafuta kurasa mpya zinazohusika kwenye wiki]].',

# Revision deletion
'rev-deleted-comment'         => '(muhtasari wa kuhariri ulifutwa)',
'rev-deleted-user'            => '(jina la mtumiaji lilifutwa)',
'rev-deleted-event'           => '(ingizo lilifutwa)',
'rev-deleted-user-contribs'   => '[jina la mtumiaji au anwani wa IP umetolewa - sahihisho lilifichwa kutoka kwa orodha ya michango]',
'rev-deleted-text-permission' => "Pitio hilo la ukurasa '''lilifutwa'''.
Maelezo mengine yapo kwenye [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kumbukumbu ya kufuta].",
'rev-deleted-text-unhide'     => "Pitio hilo la ukurasa '''lilifutwa'''.
Maelezo mengine yapo kwenye [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kumbukumbu ya kufuta].
Kwa sababu u mkabidhi, bado unaweza [$1 kulitazama sahihisho hilo] ukitaka.",
'rev-suppressed-text-unhide'  => "Pitio hilo la ukurasa '''lilifichwa'''.
Maelezo mengine yapo kwenye [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} kumbukumbu ya kuficha].
Kwa sababu u mkabidhi, bado unaweza [$1 kulitazama sahihisho hilo] ukitaka.",
'rev-deleted-text-view'       => "Pitio hilo la ukurasa '''lilifutwa'''.
Kwa sababu u mkabidhi, bado unaweza kulitazama; maelezo mengine yapo kwenye [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} kumbukumbu ya kufuta].",
'rev-suppressed-text-view'    => "Pitio hilo la ukurasa '''lilifichwa'''.
Kwa sababu u mkabidhi, bado unaweza kulitazama; maelezo mengine yapo kwenye [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} kumbukumbu ya kuficha].",
'rev-delundel'                => 'onyesha/ficha',
'rev-showdeleted'             => 'onyesha',
'revisiondelete'              => 'Kufuta/kurudisha mapitio',
'revdelete-nologtype-title'   => 'Aina ya kumbukumbu haikutajwa',
'revdelete-nologid-title'     => 'Kumbukumbu batili',
'revdelete-no-file'           => 'Faili ulilotaja halipatikani.',
'revdelete-show-file-confirm' => 'Ni kweli kwamba unataka kulitazama pitio lililofutwa la faili linaloitwa "<nowiki>$1</nowiki>" la tarehe $2 na saa $3?',
'revdelete-show-file-submit'  => 'Ndiyo',
'revdelete-selected'          => "'''{{PLURAL:$2|Pitio lililoteuliwa|Mapitio yaliyoteuliwa}} ya [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Tukio la kumbukumbu lililoteuliwa|Matukio ya kumbukumbu yaliyoteuliwa}}:'''",
'revdelete-text'              => "'''Mapitio bado yataonekana kwenye ukurasa wa historia na matukio bado yataonekana kwenye kumbukumbu, lakini baadhi ya yaliyomo haitaonekana mbele ya watu wote.'''
Wakabidhi wengine wa {{SITENAME}} bado wataweza kuliona lile lililofichwa pamoja na kulirudisha kwa kuutumia ukurasa maalum huu huu, isipowekewa vizuio vingine.",
'revdelete-confirm'           => 'Tafadhali uthibitishe kwamba unataka kufanya hivyo, pamoja na kwamba unaelewa matokeo yake, na unafanya hivyo kutokana na [[{{MediaWiki:Policy-url}}|sera yetu]].',
'revdelete-suppress-text'     => "Kuficha kunaruhisiwa '''tu''' wakati hizo:
* Taarifa zinazowezekana kwamba ni za kukashifu
* Taarifa za mtu binafsi zisizofaa
*: ''anwani za nyumbani na namba za simu, namba za vitambulisho, na kadhalika.''",
'revdelete-legend'            => 'Kubana maelezo yanayoonekana',
'revdelete-hide-text'         => 'Ficha maandishi ya pitio',
'revdelete-hide-image'        => 'Ficha yaliyomo kwenye faili',
'revdelete-hide-name'         => 'Ficha tendo na shabaha',
'revdelete-hide-comment'      => 'Ficha muhtasari wa kuhariri',
'revdelete-hide-user'         => 'Ficha jina la mhariri/anwani ya IP ya mhariri',
'revdelete-hide-restricted'   => 'Wakabidhi (vilevile wengine) wasiweze kuona data',
'revdelete-radio-same'        => '(isibadilishwe)',
'revdelete-radio-set'         => 'Ndiyo',
'revdelete-radio-unset'       => 'Siyo',
'revdelete-suppress'          => 'Wakabidhi (vilevile wengine) wasiweze kuona data',
'revdelete-unsuppress'        => 'Uzuio wa kuona mapitio uondolewe, mapitio yanaporudishwa',
'revdelete-log'               => 'Sababu:',
'revdelete-submit'            => '{{PLURAL:$1|Pitio lililochaguliwa lifanyiwe|Mapitio yaliyochaguliwa yafanyiwe}} kazi.',
'revdelete-success'           => "'''Kubadilisha uwezo wa kuona pitio ulifaulu.'''",
'revdelete-failure'           => "'''Kubadilisha uwezo wa kuona pitio hakufaulu:'''
$1",
'logdelete-success'           => "'''Kubadilisha uwezo wa kuona kumbukumbu ulifaulu.'''",
'logdelete-failure'           => "'''Kubadilisha uwezo wa kuona kumbukumbu ulifaulu:'''
$1",
'revdel-restore'              => 'badilisha mwonekano',
'revdel-restore-deleted'      => 'mapitio yaliyofutwa',
'revdel-restore-visible'      => 'mapitio yanayoonekana',
'pagehist'                    => 'Historia ya ukurasa',
'deletedhist'                 => 'Historia iliyofutwa',
'revdelete-hide-current'      => 'Hitilafu ya kuficha pitio lililotengenezwa saa $2, tarehe $1: hilo ndilo pitio la sasa hivi.
Haliwezi kufichwa.',
'revdelete-reason-dropdown'   => '*Sababu za kufuta zinazotokea mara kwa mara
** Ukiukaji wa hakimiliki
** Taarifa za mtu binafsi zisizofaa
** Taarifa zinazowezekana kwamba ni za kukashifu',
'revdelete-otherreason'       => 'Sababu nyingine:',
'revdelete-reasonotherlist'   => 'Sababu nyingine',
'revdelete-edit-reasonlist'   => 'Kuhariri orodha ya sababu za kufuta',
'revdelete-offender'          => 'Mhariri wa pitio:',

# Suppression log
'suppressionlog'     => 'Kumbukumbu za kuficha',
'suppressionlogtext' => 'Hapo chini panaonyeshwa orodha ya matukio ya ufutaji na ya uzuio ambayo maelezo yao yamefichwa kutoka kwa wakabidhi.
Tazama [[Special:BlockList|orodha ya uzuio wa IP]] kuona orodha ya zuio zilizopo sasa hivi.',

# History merging
'mergehistory'                     => 'Unganisha historia za kurasa',
'mergehistory-box'                 => 'Unganisha mapitio ya kurasa mbili:',
'mergehistory-from'                => 'Ukurasa wa chanzo:',
'mergehistory-into'                => 'Ukurasa wa mwishilio:',
'mergehistory-submit'              => 'Unganisha mapitio',
'mergehistory-empty'               => 'Hakuna mapitio yanayoweza kuunganishwa',
'mergehistory-success'             => '{{PLURAL:$3|Pitio $3 la [[:$1]] liliingizwa|Mapitio $3 ya [[:$1]] yaliingizwa}} ndani ya [[:$2]].',
'mergehistory-no-source'           => 'Chanzo cha ukurasa $1 hakipo.',
'mergehistory-no-destination'      => 'Ukurasa wa makusudio $1 haupo.',
'mergehistory-invalid-source'      => 'Chanzo cha ukurasa lazima kiwe na jina uhalali.',
'mergehistory-invalid-destination' => 'Ukurasa wa makusudio lazima uwe na jina halali.',
'mergehistory-autocomment'         => '[[:$1]] uliunganishwa ndani wa [[:$2]]',
'mergehistory-comment'             => '[[:$1]] uliunganishwa ndani wa [[:$2]]: $3',
'mergehistory-same-destination'    => 'Kurasa za chanzo na za mwishilio haziwezi kuwa sawa',
'mergehistory-reason'              => 'Sababu:',

# Merge log
'mergelog'           => 'Kumbukumbu za kuunganisha',
'pagemerge-logentry' => 'aliunganisha [[$1]] ndani wa [[$2]] (mapitio hadi $3)',
'revertmerge'        => 'Toa muungano',
'mergelogpagetext'   => 'Hapo chini yanaorodheshwa matukio ya hivi karibuni ya kuunganisha historia za kurasa mbili.',

# Diffs
'history-title'            => 'Historia ya mapitio ya "$1"',
'difference'               => '(Tofauti baina ya mapitio)',
'difference-multipage'     => '(Tofauti kati ya kurasa)',
'lineno'                   => 'Mstari $1:',
'compareselectedversions'  => 'Linganisha mapitio mawili uliyochagua',
'showhideselectedversions' => 'Onyesha/ficha mapitio yaliyoteuliwa',
'editundo'                 => 'tengua',
'diff-multi'               => '(Haionyeshwi {{PLURAL:$1|pitio moja la katikati lililoandikwa|mapitio $1 ya katikati yaliyoandikwa}} na {{PLURAL:$2|mtumiaji moja|watumiaji $2}})',

# Search results
'searchresults'                    => 'Matokeo ya utafutaji',
'searchresults-title'              => 'Matokeo ya utafutaji kwa ajili ya "$1"',
'searchresulttext'                 => 'Kwa maelezo zaidi kuhusu kutafuta {{SITENAME}}, tazama [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ulitafuta \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|kurasa zote zinazoanza "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kurasa zote zinazoungwa na "$1"]])',
'searchsubtitleinvalid'            => "Ulitafuta '''$1'''",
'toomanymatches'                   => 'Yalipatikana majibu mengi mno, kwa hiyo tafadhali jaribu ulizo mwingine',
'titlematches'                     => 'Kurasa zinazo majina yenye maneno ya ulizo',
'notitlematches'                   => 'Jina hili la ukurasa halikupatikana',
'textmatches'                      => 'Kurasa zinazo maandishi yenye maneno ya ulizo',
'notextmatches'                    => 'Maandishi yaliyotafutwa hayakupatikana kwenye kurasa zo zote',
'prevn'                            => '{{PLURAL:$1|uliotangulia|$1 zilizotangulia}}',
'nextn'                            => '{{PLURAL:$1|ujao|$1 zijazo}}',
'prevn-title'                      => '{{PLURAL:$1|Tokeo $1 lililotangulia|Matokeo $1 yaliyotangulia}}',
'nextn-title'                      => '{{PLURAL:$1|Tokeo $1 lijalo|Matokeo $1 yajayo}}',
'shown-title'                      => '{{PLURAL:$1|Lionyewshwe tokeo|Yaonyeshwe matokeo}} $1 kwa kila ukurasa',
'viewprevnext'                     => 'Tazama ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Hitiari za kutafuta',
'searchmenu-exists'                => "'''Ukurasa wa \"[[:\$1]]\" upo kwenye wiki hii'''",
'searchmenu-new'                   => "'''Anzisha ukurasa wa \"[[:\$1]]\" katika wiki hii!'''",
'searchhelp-url'                   => 'Help:Yaliyomo',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Tafuta kurasa kwenye eneo hili la wiki]]',
'searchprofile-articles'           => 'Kurasa kwa kusudi ya wiki',
'searchprofile-project'            => 'Kurasa za msaada na za mradi',
'searchprofile-images'             => 'Picha na kadhalika',
'searchprofile-everything'         => 'Zote',
'searchprofile-advanced'           => 'Hali ya juu',
'searchprofile-articles-tooltip'   => 'Tafuta kwenye $1',
'searchprofile-project-tooltip'    => 'Tafuta kwenye $1',
'searchprofile-images-tooltip'     => 'Tafuta mafaili',
'searchprofile-everything-tooltip' => 'Tafuta wiki nzima (pamoja na kurasa za majadiliano)',
'searchprofile-advanced-tooltip'   => 'Tafuta katika maeneo ya wiki utakayoyachagua',
'search-result-size'               => '$1 ({{PLURAL:$2|neno 1|maneno $2}})',
'search-result-category-size'      => '{{PLURAL:$1|ukurasa 1|kurasa $1}} ({{PLURAL:$2|kijamii 1|vijamii $2}}, {{PLURAL:$3|faili 1|mafaili $3}})',
'search-result-score'              => 'Kiwango cha ulinganisho na ulizo: $1%',
'search-redirect'                  => '(elekezo toka kwa $1)',
'search-section'                   => '(fungu $1)',
'search-suggest'                   => 'Je, ulitaka kutafuta: $1',
'search-interwiki-caption'         => 'Miradi ya jumuia',
'search-interwiki-default'         => 'Matokeo toka $1:',
'search-interwiki-more'            => '(zaidi)',
'search-mwsuggest-enabled'         => 'na mapendekezo',
'search-mwsuggest-disabled'        => 'bila makendekezo',
'search-relatedarticle'            => 'Zingine zinazofanana',
'mwsuggest-disable'                => 'Kutoonyesha mapendekezo ya AJAX',
'searcheverything-enable'          => 'Tafuta katika maeneo yote ya wiki',
'searchrelated'                    => 'zingine zinazofanana',
'searchall'                        => 'zote',
'showingresults'                   => "{{PLURAL:$1|Tokeo '''1''' linaonyeshwa|matokeo '''$1''' yanaonyeshwa}} chini, kuanzia na namba '''$2'''.",
'showingresultsnum'                => "{{PLURAL:$3|Tokeo '''1''' linaonyeshwa|Matokeo '''$3''' yanaonyeshwa}} chini, kuanzia na namba '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Tokeo '''$1''' kati ya jumla ya '''$3'''|Matokeo '''$1 - $2''' kati ya jumla ya '''$3'''}} kutokana na kuitafuta '''$4'''",
'nonefound'                        => "'''Zingatia''': Utafutaji wa msingi unatafuta kwenye maeneo machache ya wiki tu.
Ukitaka kutafuta kwenye maeneo yote (pamoja na kurasa za majadiliano, vigezo, nk) andika ''all:'' mwanzoni mwa kisanduku. Ukitaka kutafuta kwenye eneo linaloitwa ''fulani'' andika ''fulani:'' mwanzoni mwa kisanduku.",
'search-nonefound'                 => 'Hakuna matokeo ya kutafuta ulizio ule.',
'powersearch'                      => 'Tafuta kwa hali ya juu',
'powersearch-legend'               => 'Tafuta kwa hali ya juu',
'powersearch-ns'                   => 'Tafuta kwenye maeneo ya wiki yafuatayo:',
'powersearch-redir'                => 'Orodhesha kurasa za kuelekeza',
'powersearch-field'                => 'Tafuta huu:',
'powersearch-togglelabel'          => 'Chagua:',
'powersearch-toggleall'            => 'Chagua yote',
'powersearch-togglenone'           => 'Usichague',
'search-external'                  => 'Kutafuta nje',
'searchdisabled'                   => 'Kutafuta {{SITENAME}} kumesimamishwa.
Unaweza kutafuta kwa kutumia Google punde si punde.
Ujue lakini kwamba kumbukumbu za {{SITENAME}} kule Google labda zilipitwa na wakati.',

# Quickbar
'qbsettings'               => 'Mwambaa pembe',
'qbsettings-none'          => 'Hakuna',
'qbsettings-fixedleft'     => 'Kushoto tuli',
'qbsettings-fixedright'    => 'Kulia tuli',
'qbsettings-floatingleft'  => 'Kushoto geugeu',
'qbsettings-floatingright' => 'Kulia geugeu',

# Preferences page
'preferences'                   => 'Mapendekezo',
'mypreferences'                 => 'Mapendekezo yangu',
'prefs-edits'                   => 'Idadi ya marekebisho:',
'prefsnologin'                  => 'Hujaingia',
'prefsnologintext'              => 'Inabidi <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} uingie akaunti yako]</span> ili ubadilishe mapendekezo yako.',
'changepassword'                => 'Badilisha neno la siri',
'prefs-skin'                    => 'Umbo',
'skin-preview'                  => 'Hakiki',
'datedefault'                   => 'Chaguo-msingi',
'prefs-beta'                    => 'Zana za Beta',
'prefs-datetime'                => 'Tarehe na saa',
'prefs-personal'                => 'Kuhusu mtumiaji',
'prefs-rc'                      => 'Mabadiliko ya karibuni',
'prefs-watchlist'               => 'Maangalizi',
'prefs-watchlist-days'          => 'Ionyeshwe siku ngapi kwenye orodha ya maangalizi?',
'prefs-watchlist-days-max'      => 'Isizidi siku 7',
'prefs-watchlist-edits'         => 'Upeo ya idadi ya mabadiliko yatakayoonyeshwa kwenye orodha ya maangalizi iliyotanuka:',
'prefs-watchlist-edits-max'     => 'Idadi isiyopitishwa: 1000',
'prefs-watchlist-token'         => 'Ufunguo wa orodha ya maangalizi:',
'prefs-misc'                    => 'Mengineyo',
'prefs-resetpass'               => 'Kubadilisha neno la siri',
'prefs-changeemail'             => 'Badilisha anwani ya barua pepe',
'prefs-setemail'                => 'Weka anwani ya barua pepe',
'prefs-email'                   => 'Hitiari za barua pepe',
'prefs-rendering'               => 'Umbo',
'saveprefs'                     => 'Hifadhi',
'resetprefs'                    => 'Utupe mabadiliko yasijahifadhika',
'restoreprefs'                  => 'Rudisha mapendekezo ya msingi',
'prefs-editing'                 => 'Kuhariri',
'prefs-edit-boxsize'            => 'Ukubwa wa dirisha la kuhariri.',
'rows'                          => 'Mistari:',
'columns'                       => 'Safu:',
'searchresultshead'             => 'Kutafuta',
'resultsperpage'                => 'Matokeo yanayoorodheshwa katika ukurasa mmoja:',
'stub-threshold'                => 'Kiwango cha juu cha kuonyesha kiungo kama <a href="#" class="stub">kiungo kinachoelekea mbegu</a> (baiti):',
'stub-threshold-disabled'       => 'Imelemazwa',
'recentchangesdays'             => 'Ionyeshwe siku ngapi kwenye orodha ya mabadiliko ya karibuni?',
'recentchangesdays-max'         => 'Isizidi {{PLURAL:$1|siku}} $1',
'recentchangescount'            => 'Idadi ya masahihisho yatakayoonyeshwa kwa kawaida:',
'prefs-help-recentchangescount' => 'Kwenye kurasa za mabadiliko ya karibuni, za historia ya ukurasa, na za kumbukumbu.',
'prefs-help-watchlist-token'    => 'Ukiandika ufunguo wa siri kwenye kisanduku hiki, programu itaanzisha tawanyiko la RSS kwa ajili ya maangalizi yako.
Mtu wowote anayejua ufunguo wa siri huu ataweza kusoma orodha yako ya maangalizi, kwa hiyo chagua ufunguo salama.
Hapo kuna ufunguo uliotolewa na programu kwa kubahatisha, ambao unaweza kuutumia: $1',
'savedprefs'                    => 'Mapendekezo yako yamehifadhiwa.',
'timezonelegend'                => 'Ukanda saa:',
'localtime'                     => 'Saa ya kwetu:',
'timezoneuseserverdefault'      => 'Tumia saa inayokubali na wiki yenyewe ($1)',
'timezoneuseoffset'             => 'Nyingine (weka tofauti ya saa)',
'timezoneoffset'                => 'Tofauti ya saa¹:',
'servertime'                    => 'Saa ya seva:',
'guesstimezone'                 => 'kivinjari kiweke saa',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Marekani',
'timezoneregion-antarctica'     => 'Antaktika',
'timezoneregion-arctic'         => 'Artiki',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Bahari ya Atlantiki',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Ulaya',
'timezoneregion-indian'         => 'Bahari ya Hindi',
'timezoneregion-pacific'        => 'Bahari ya Pasifiki',
'allowemail'                    => 'Wezesha barua pepe toka kwa watumiaji wengine',
'prefs-searchoptions'           => 'Hitiari za kutafuta',
'prefs-namespaces'              => 'Maeneo ya wiki',
'defaultns'                     => 'La sivyo tafuta kwenye maeneo haya:',
'default'                       => 'chaguo-msingi',
'prefs-files'                   => 'Mafaili',
'prefs-custom-css'              => 'CSS niliyotunga mwenyewe',
'prefs-custom-js'               => 'JS niliyotunga mwenyewe',
'prefs-common-css-js'           => 'CSS/JS inayoshirikishwa na maumbo yote:',
'prefs-reset-intro'             => 'Unaweza kutumia ukurasa huu ili kurudisha mapendekezo yako kwenye yale ya msingi ya tovuti.
Hutaweza kulibatilisha tendo hili baadaye.',
'prefs-emailconfirm-label'      => 'Kuhakikisha barua pepe:',
'prefs-textboxsize'             => 'Ukubwa wa sanduku la kuhariri',
'youremail'                     => 'Barua pepe yako:',
'username'                      => 'Jina la mtumiaji:',
'uid'                           => 'Namba ya mtumiaji:',
'prefs-memberingroups'          => 'Mwanachama wa {{PLURAL:$1|kundi la|makundi ya}}:',
'prefs-registration'            => 'Wakati wa kusajili:',
'yourrealname'                  => 'Jina lako halisi:',
'yourlanguage'                  => 'Lugha:',
'yourvariant'                   => 'Variant:',
'yournick'                      => 'Sahihi:',
'prefs-help-signature'          => 'Unapoandika kwenye kurasa za majadiliano tafadhali utie sahihi kwa kuandika "<nowiki>~~~~</nowiki>"; itaonekana jina lako pamoja na saa na tarehe ya kuhifadhi.',
'badsig'                        => 'Umeweka sahihi batili.
Angalia mabano ya HTML.',
'badsiglength'                  => 'Sahihi uliyoweka ni ndefu mno.
Haiwezi kuzidi {{PLURAL:$1|tarakimu|tarakimu}} $1.',
'yourgender'                    => 'Jinsi:',
'gender-unknown'                => 'Haitajwi',
'gender-male'                   => 'Mume',
'gender-female'                 => 'Mke',
'prefs-help-gender'             => 'Si lazima: inatumika kwenye lugha zinazokuwa na mtindo wa kuitana tofauti kwa ajili ya wanaume na wanawake, ili bidhaa pepe itumie mtindo sahihi.
Taarifa hii itakuwa wazi.',
'email'                         => 'Barua pepe',
'prefs-help-realname'           => 'Jina la kweli si lazima. Ukichagua kutaja jina lako hapa, litatumiwa kuonyesha kwamba ndiyo ulifanya kazi unayochangia.',
'prefs-help-email'              => 'Barua-pepe sio lazima, lakini inawezesha kupokea neno jipya la siri kwa kupitia barua-pepe yako endapo utakuwa umelisahau.',
'prefs-help-email-others'       => 'Unaweza pia kuwezesha wengine wawasiliane nawe kwa njia ya ukurasa wako wa mtumiaji au ukurasa wako wa majadiliano. Anwani ya barua-pepe yako haioneshwi wakati watumiaji wanawasiliana na wewe.',
'prefs-help-email-required'     => 'Barua pepe inahitajika.',
'prefs-info'                    => 'Maelezo ya kimsingi',
'prefs-i18n'                    => 'Lugha',
'prefs-signature'               => 'Sahihi',
'prefs-dateformat'              => 'Jinsi inayoandikwa tarehe',
'prefs-timeoffset'              => 'Kuweka saa tofauti na saa ya seva',
'prefs-advancedediting'         => 'Hitiari za hali ya juu',
'prefs-advancedrc'              => 'Hitiari za hali ya juu',
'prefs-advancedrendering'       => 'Hitiari za hali ya juu',
'prefs-advancedsearchoptions'   => 'Hitiari za hali ya juu',
'prefs-advancedwatchlist'       => 'Hitiari za hali ya juu',
'prefs-displayrc'               => 'Hitiari za kutandaza',
'prefs-displaysearchoptions'    => 'Mapendekezo ya kuzinza',
'prefs-displaywatchlist'        => 'Mapendekezo ya kuzinza',
'prefs-diffs'                   => 'Tofauti',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Anwani ya barua pepe inaonekana kuwa sawa',
'email-address-validity-invalid' => 'Ingiza anwani halisi ya barua pepe',

# User rights
'userrights'                   => 'Usimamizi wa wezo za mtumiaji',
'userrights-lookup-user'       => 'Kusimamia kundi za watumiaji',
'userrights-user-editname'     => 'Andika jina la mtumiaji:',
'editusergroup'                => 'Kuhariri vikundi vya watumiaji',
'editinguser'                  => "Kubadilisha wezo za mtumiaji '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Kuhariri kundi za watumiaji',
'saveusergroups'               => 'Kuhifadhi kundi za watumiaji',
'userrights-groupsmember'      => 'Mwanachama wa:',
'userrights-groupsmember-auto' => 'Mwanachama moja kwa moja wa:',
'userrights-groups-help'       => 'Unaweza kubadilisha kundi mtumiaji huyu alizokuwa mwanachama zao:
* Mtumiaji ni mwanachama wa kundi fulani kama kisanduku chake kina alama.
* Mtumiaji si mwanachama wa kundi fulani kama kisanduku chake hakina alama.
* Alama ya * ina maana ya kwamba ukiongeza kundi fulani, hutaweza kuitoa tena, au ukitoa kundi fulani, hutaweza kuiongeza tena.',
'userrights-reason'            => 'Sababu:',
'userrights-no-interwiki'      => 'Huna ruhusa ya kuhariri haki za mtumiaji kwenye wiki zingine.',
'userrights-nodatabase'        => 'Hakuna hifadhidata inayoitwa $1 au haimo katiko jumuia hii ya wiki.',
'userrights-nologin'           => 'Lazima [[Special:UserLogin|uingie ndani]] ya akaunti ya mkabidhi ili kupanga haki za mtumiaji.',
'userrights-notallowed'        => 'Akaunti yako haina ruhusa ya kupanga haki za mtumiaji.',
'userrights-changeable-col'    => 'Makundi unayoweza kuyabadilisha',
'userrights-unchangeable-col'  => 'Makundi usiyoweza kuyabadilisha',

# Groups
'group'               => 'Kundi:',
'group-user'          => 'Watumiaji',
'group-autoconfirmed' => 'Watumiaji waliothibitishwa na tarakilishi',
'group-bot'           => 'Boti',
'group-sysop'         => 'Wakabidhi',
'group-bureaucrat'    => 'Warasimu',
'group-suppress'      => 'Ukomeshaji',
'group-all'           => '(vyote)',

'group-user-member'          => '{{GENDER:$1|mtumiaji}}',
'group-autoconfirmed-member' => '{{GENDER:$1|mtumiaji aliyethibitishwa na tarakilishi}}',
'group-bot-member'           => '{{GENDER:$1|boti}}',
'group-sysop-member'         => '{{GENDER:$1|mkabidhi}}',
'group-bureaucrat-member'    => '{{GENDER:$1|mrasimu}}',
'group-suppress-member'      => 'usimamizi',

'grouppage-user'          => '{{ns:project}}:Watumiaji',
'grouppage-autoconfirmed' => '{{ns:project}}:Watumiaji waliothibitishwa na tarakilishi',
'grouppage-bot'           => '{{ns:project}}:Boti',
'grouppage-sysop'         => '{{ns:project}}:Wakabidhi',
'grouppage-bureaucrat'    => '{{ns:project}}:Warasimu',
'grouppage-suppress'      => '{{ns:project}}:Usimamizi',

# Rights
'right-read'                 => 'Kusoma kurasa',
'right-edit'                 => 'Kuhariri kurasa',
'right-createpage'           => 'Kuanzisha kurasa (ambazo si kurasa za majadiliano)',
'right-createtalk'           => 'Kuanzisha kurasa za majadiliano',
'right-createaccount'        => 'Kufungua akaunti mpya za watumiaji',
'right-minoredit'            => 'Kutia alama kwamba badiliko ni dogo',
'right-move'                 => 'Kuhamisha kurasa',
'right-move-subpages'        => 'Kuhamisha kurasa pamoja na kurasa zake ndogo',
'right-move-rootuserpages'   => 'Kuhamisha kurasa za watumiaji',
'right-movefile'             => 'Kuhamisha mafaili',
'right-suppressredirect'     => 'Usianzishe elekezo kutoka katika kurasa za chanzo wakati kuhamisha kurasa',
'right-upload'               => 'Kupakia mafaili',
'right-reupload'             => 'Kuandikiza mafaili yaliyopo tayari',
'right-reupload-own'         => 'Kuandikiza mafaili yaliyopakizwa na mimi mwenyewe',
'right-reupload-shared'      => 'Kupuuza mafaili yaliyopo hifadhi ya pamoja ya faili hapo wiki hii',
'right-upload_by_url'        => 'Kupakia mafaili kutoka kwa URL',
'right-purge'                => 'Safisha kache za wavuti kwa ajili ya ukurasa usio na uthibitisho',
'right-autoconfirmed'        => 'Hariri kurasa zilizokingwa-kiasi',
'right-nominornewtalk'       => 'Isiwe kwamba maharirio madogo kwenye kurasa za majadiliano fyatua kunijulisha kuhusu jumbe mpya',
'right-writeapi'             => 'Tumia API ya kuandika',
'right-delete'               => 'Kufuta kurasa',
'right-bigdelete'            => 'Kufuta kurasa zenye mabadiliko mengi',
'right-deleterevision'       => 'Kufuta na kurudisha mapitio fulani ya kurasa',
'right-deletedhistory'       => 'Kutazama kumbukumbu za historia zilizofutwa, bila kuona maandiko yaliyomo',
'right-deletedtext'          => 'Kutazama maandishi yaliyofutwa na mabadiliko kati ya mapitio yaliyofutwa',
'right-browsearchive'        => 'Kutafuta kwenye kurasa zilizofutwa',
'right-undelete'             => 'Kurudisha ukurasa uliofutwa',
'right-suppressrevision'     => 'Kuangalia na kurudisha mapitio yaliyofichwa kwa wakabidhi',
'right-suppressionlog'       => 'Kutazama kumbukumbu za faragha',
'right-block'                => 'Kuwazuia watumiaji wengine wasihariri',
'right-blockemail'           => 'Kumzuia mtumiaji asitume barua-pepe',
'right-hideuser'             => 'Kuzuia jina la mtumiaji, lisionekane mbele ya kadamnasi',
'right-protect'              => 'Badilisha viwango vya ulinzi na hariri kurasa zilizolindwa',
'right-editinterface'        => 'Kuhariri kusano ya mtumiaji',
'right-editusercssjs'        => 'Kuhariri mafaili ya CSS na JavaScript ya watumiaji wengine',
'right-editusercss'          => 'Hariri mafaili ya CSS ya watumiaji wengine',
'right-edituserjs'           => 'Hariri mafaili ya JavaScript ya watumiaji wengine',
'right-import'               => 'Kuleta kurasa kutoka kwa wiki zingine',
'right-importupload'         => 'Ingiza kurasa kutoka kwa faili lililopakiwa',
'right-unwatchedpages'       => 'Kutazama orodha ya kurasa zisizofuatiliwa',
'right-mergehistory'         => 'Kuunganisha historia ya kurasa zingine',
'right-userrights'           => 'Kubadilisha wezo zote za watumiaji',
'right-userrights-interwiki' => 'Kuhariri wezo za watumiaji kwenye wiki zingine',
'right-siteadmin'            => 'Kufunga na kufungua hifadhidata',
'right-sendemail'            => 'Kutuma barua-pepe kwa watumiaji wengine',
'right-passwordreset'        => 'Kuona barua pepe zinazoweka neno la siri upya',

# User rights log
'rightslog'      => 'Kumbukumbu za vyeo vya watumiaji',
'rightslogtext'  => 'Hii ni kumbukumbu za mabadiliko za wezo za watumiaji.',
'rightslogentry' => 'alibadilisha wezo za $1 aliyekuwa na wezo za kundi $2 awe mwanachama wa $3',
'rightsnone'     => '(hana)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'kusoma ukurasa huu',
'action-edit'                 => 'kuhariri ukurasa huu',
'action-createpage'           => 'kuanzisha kurasa',
'action-createtalk'           => 'kuanzisha kurasa za majadiliano',
'action-createaccount'        => 'kusajili akaunti hii ya mtumiaji',
'action-minoredit'            => 'kutia alama ya badiliko dogo',
'action-move'                 => 'kuhamisha ukurasa huu',
'action-move-subpages'        => 'kuhamisha ukurasa huu, pamoja na kurasa zake ndogo',
'action-move-rootuserpages'   => 'kuhamisha kurasa za watumiaji',
'action-movefile'             => 'kuhamisha faili hili',
'action-upload'               => 'kupakia faili hili',
'action-reupload'             => 'kuandikiza faili lililopo tayari',
'action-reupload-shared'      => 'kupuuza faili lililopo hifadhi ya pamoja ya faili',
'action-upload_by_url'        => 'kupakia faili hili kutoka kwa gombo wavu',
'action-writeapi'             => 'tumia API ya kuandika',
'action-delete'               => 'kufuta ukurasa huu',
'action-deleterevision'       => 'kufuta pitio hili',
'action-deletedhistory'       => 'kutazama historia iliyofutwa ya ukurasa huu',
'action-browsearchive'        => 'kutafuta kwenye kurasa zilizofutwa',
'action-undelete'             => 'kurudisha ukurasa huu',
'action-suppressrevision'     => 'kuangalia na kurudisha pitio hilo lililofichwa kwa wakabidhi',
'action-suppressionlog'       => 'kutazama kumbukumbu za faragha',
'action-block'                => 'kumzuia mtumiaji huyu asihariri',
'action-protect'              => 'badilisha viwango vya ulinzi vya ukurasa huu',
'action-import'               => 'kuleta ukurasa huu kutoka kwa wiki nyingine',
'action-importupload'         => 'Ingiza ukurasa huu kutoka kwa faili lilopakiwa',
'action-unwatchedpages'       => 'kutazama orodha ya kurasa zisizofuatiliwa',
'action-mergehistory'         => 'kuunganisha historia ya ukurasa huu',
'action-userrights'           => 'kubadilisha wezo zote za watumiaji',
'action-userrights-interwiki' => 'kuhariri wezo za watumiaji kwenye wiki zingine',
'action-siteadmin'            => 'kufunga na kufungua hifadhidata',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|badiliko|mabadiliko}} $1',
'recentchanges'                     => 'Mabadiliko ya karibuni',
'recentchanges-legend'              => "Machaguo ya 'mabadaliko ya karibuni'",
'recentchangestext'                 => 'Orodha ya mabadilisho yaliyofanywa katika {{SITENAME}} siku zilizopita.',
'recentchanges-feed-description'    => 'Tumia tawanyiko hili kufuatilia mabadiliko yote ya hivi karibuni katika Wiki.',
'recentchanges-label-newpage'       => 'Ukurasa mpya ulianzishwa hapo',
'recentchanges-label-minor'         => 'Hili ni badiliko dogo',
'recentchanges-label-bot'           => 'Sahihisho hili lilitekelezwa na bot',
'recentchanges-label-unpatrolled'   => 'Haririo hili bado halijafanyiwa doria',
'rcnote'                            => "{{PLURAL:$1|Linalofuata ni badiliko '''1'''|Yanayofuata ni mabadiliko '''$1''' ya mwisho}} kutoka katika {{PLURAL:$2|siku iliyopita|siku '''$2''' zilizopita}}, hadi saa $5, tarehe $4.",
'rcnotefrom'                        => "Hapo chini yaonekana mabadiliko tangu '''$2''' (tunaonyesha hadi '''$1''').",
'rclistfrom'                        => 'Onyesha mabadiliko mapya kuanzia $1',
'rcshowhideminor'                   => '$1 mabadiliko madogo',
'rcshowhidebots'                    => 'roboti $1',
'rcshowhideliu'                     => '$1 watumiaji sasa',
'rcshowhideanons'                   => '$1 watumiaji bila majina',
'rcshowhidepatr'                    => '$1 masahihisho yanayofanywa doria',
'rcshowhidemine'                    => '$1 masahihisho yangu',
'rclinks'                           => 'Onyesha mabadiliko $1 yaliyofanywa wakati wa siku $2 zilizopita<br />$3',
'diff'                              => 'tofauti',
'hist'                              => 'hist',
'hide'                              => 'Ficha',
'show'                              => 'Onyesha',
'minoreditletter'                   => 'd',
'newpageletter'                     => 'P',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[idadi ya {{PLURAL:$1|watumiaji}} wanaoufuatilia ni $1]',
'rc_categories'                     => 'Chagua jamii zingine (uzitenge na kigawaji hiki "|")',
'rc_categories_any'                 => 'Yoyote',
'newsectionsummary'                 => '/* $1 */ mjadala mpya',
'rc-enhanced-expand'                => 'Onyesha maelezo mengine (inahitaji JavaScript)',
'rc-enhanced-hide'                  => 'Ficha maelezo mengine',

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
'upload'                      => 'Pakia faili',
'uploadbtn'                   => 'Pakia faili',
'reuploaddesc'                => 'Kubatilisha kupakia na kurudi kwenye fomu ya kupakia',
'upload-tryagain'             => 'Wasilisha maelezo ya faili lililobadilishwa',
'uploadnologin'               => 'Hujaingia',
'uploadnologintext'           => 'Lazima [[Special:UserLogin|uingie akaunti yako]] ile upakie mafaili.',
'uploaderror'                 => 'Hitilafu ya kupia',
'upload-recreate-warning'     => "'''Ilani: Faili lenye jina hilo limeshafutwa au limeshasogezwa.'''

Rejea kumbukumbu za kufuta au kuhamisha ukurasa huu zinazotolewa chini:",
'uploadtext'                  => "Tumia fomu hapo chini kwa kupakizia mafaili.
Kwa kutazama au kutafuta faili zilizopakiwa awali, tafadhali nenda kwenye [[Special:FileList|orodha ya mafaili yaliyopakiwa]]. Kwa zile faili ambazo zishapitiwa, basi angalia [[Special:Log/upload|kumbukumbu ya mafaili]]. Kwa mafaili yaliyofutwa, tafadhali [[Special:Log/delete|tazama hapa]].

Kwa kutumia faili katika makala, tumia moja kati ya viungo vifuatavyo:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Picha.jpg]]</nowiki></tt>''' kwa kutumia toleo zima la faili
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Picha.png|200px|thumb|left|maelezo ya picha]]</nowiki></tt>''' tumia pixel 200 kwa ukubwa mzuri na sehemu ya 'maelezo ya picha' ikiwa kama maelezo husika na picha iliyopo
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' kwa kuunga moja kwa moja bila kuonyesga faili",
'upload-permitted'            => 'Aina ya mafaili yanayoruhusiwa: $1.',
'upload-preferred'            => 'Aina za mafaili yaliyopendelewa: $1.',
'upload-prohibited'           => 'Aina za mafaili yanayokataliwa: $1.',
'uploadlog'                   => 'kumbukumbu za kupakia',
'uploadlogpage'               => 'Kumbukumbu ya upakiaji',
'uploadlogpagetext'           => 'Hapo chini kuna orodha wa mafaili yaliyopakizwa hivi karibuni.
Tazama [[Special:NewFiles|mkusanyiko wa mafaili mapya]] kuona picha zenyewe.',
'filename'                    => 'Jina la faili',
'filedesc'                    => 'Muhtasari',
'fileuploadsummary'           => 'Muhtasari:',
'filereuploadsummary'         => 'Mabadiliko ya faili:',
'filestatus'                  => 'Hali ya hakimiliki:',
'filesource'                  => 'Chanzo:',
'uploadedfiles'               => 'Mafaili yaliyopakiwa:',
'ignorewarning'               => 'Hifadhi bila kujali maonyo yoyote.',
'ignorewarnings'              => 'Usijali ilani zozote',
'minlength1'                  => 'Majina ya mafaili yanatakiwa kuwa na herufi moja au zaidi.',
'illegalfilename'             => 'Jina la faili la "$1" lina herufi zisizoruhusiwa katika majina ya kurasa.
Tafadhali uweke jina jipya kwenye faili, halafu jaribu kulipakia upya.',
'filename-toolong'            => 'Majina ya mafaili yasizidi baiti 240.',
'badfilename'                 => 'Jina la faili limebadilishwa kuwa "$1".',
'filetype-mime-mismatch'      => 'Tawi (extension) ".$1" la faili halingani na aina yake ya MIME ($2).',
'filetype-badmime'            => 'Mafaili ya aina ya MIME ya "$1" hayaruhusiwi kupakiwa.',
'filetype-bad-ie-mime'        => 'Haiwezi kupakia faili hili kwa sababu Internet Explorer ingefikiri kwamba ni "$1". Mafaili ya aina hii hayaruhusiwa, na huenda ni ya hatari.',
'filetype-unwanted-type'      => "Aina la faili '''\".\$1\"''' halitakiwi.
{{PLURAL:\$3|Aina ya faili inayopendelewa|Aina za faili zinazopendelewa}} ni \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' sio aina {{PLURAL:$4|ya faili linaloruhusiwa|za faili zinazoruhusiwa}}.
{{PLURAL:$3|Aina ya faili inayoruhusiwa|Aina za faili zinazoruhusiwa}} ni $2.',
'filetype-missing'            => 'Faili halina mnyambuliko (kama ".jpg").',
'empty-file'                  => 'Faili ulilowasilisha ni tupu.',
'file-too-large'              => 'Faili ulilowasilisha ni kubwa mno.',
'filename-tooshort'           => 'Jina la faili ni fupi mno.',
'filetype-banned'             => 'Aina hili la faili haliruhusiwi.',
'verification-error'          => 'Faili hili halikupitishwa katika ukaguzi wa faili.',
'illegal-filename'            => 'Jina hilo la faili haliruhusiwi.',
'overwrite'                   => 'Kuandikiza faili lililopo tayari hairuhusiwi.',
'unknown-error'               => 'Ilitokea hitilafu isiyojulikana.',
'tmp-create-error'            => 'Haikuweza kuanzisha faili la muda.',
'tmp-write-error'             => 'Hitilafu ya kuandika faili la muda.',
'large-file'                  => 'Tunashauri mafaili yasizidi $1;
faili hili lina $2.',
'largefileserver'             => 'Faili hili ni kubwa kuliko seva ilivyopangwa kuruhusu.',
'emptyfile'                   => 'Faili ulilolipakia linaonekana kuwa tupu.
Hii huenda ikawa jina lake limeandikwa vibaya.
Tafadhali uhakikishe kwamba ni kweli unataka kupakia faili hili.',
'windows-nonascii-filename'   => 'Wiki hii haiwezi kutumia majina ya mafaili yenye herufi maalum.',
'fileexists'                  => "Faili lenye jina hili lipo tayari, tafadhali tazama '''<tt>[[:$1]]</tt>''' ikiwa una mashaka kuhusu kulibadilisha.
[[$1|thumb]]",
'filepageexists'              => "Ukurasa wa maelezo kwa ajili ya faili hili tayari umeshaanzishwa katika '''<tt>[[:$1]]</tt>''', lakini bado hakuna faili lenye jina hili kwa sasa.
Muhtasari utakaoandika hautaonekana katika ukurasa wa maelezo.
Kufanya muhtasari wako uonekana pale, utahitajika uhariri ukurasa kwa mikono.
[[$1|thumb]]",
'fileexists-extension'        => "Faili lenye jina linalofanana nalo lipo tayari:[[$2|thumb]]
* Jina la faili linalopakiwa: '''<tt>[[:$1]]</tt>'''
* Jina la faili lililopo tayari: '''<tt>[[:$2]]</tt>'''
Tafadhali chagua jina lingine.",
'fileexists-thumbnail-yes'    => "Faili linaonekana kuwa ni ''picha'' iliyopunguzwa ukubwa.
[[$1|thumb]]
Tafadhali tazama faili la '''<tt>[[:$1]]</tt>'''.
Ikiwa faili hili linaonyesha picha ile ile kwa ukubwa wa kawaida hakuna haja ya kupakia faili lingine la picha ndogo.",
'file-thumbnail-no'           => "Jina la faili linaloanza na '''<tt>$1</tt>'''.
Inaonekana kuwa ni picha iliyopunguzwa ukubwa''(thumbnail)''.
Ikiwa unaoyo picha hii kwa ukubwa wa kawaida tafadhali pakia picha hii, vinginevyo tafadhali badilisha jina la faili.",
'fileexists-forbidden'        => 'Faili lenye jina hili lipo tayari, na haliwezi kuandikizwa.
Kama unataka kupakia faili lako, tafadhali rudia kwa kutumia jina lingine. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Faili lenye jina hili tayari lipo katika hifadhi ya pamoja ya faili.
Kama bado unataka kupakia faili lako, tafadhali rudi na utumie jina jipya.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Faili hili ni nakala ya {{PLURAL:$1|faili lifuatalo|mafaili yafuatayo}}:',
'file-deleted-duplicate'      => 'Faili linalofanana na faili hili ([[:$1]]) limeshafutwa hapo awali.
Tazama kumbukumbu za kufuta faili lile kabla hujaendelea kulipakia upya.',
'uploadwarning'               => 'Ilani kuhusu kupakia',
'uploadwarning-text'          => 'Tafadhali ubadilishe maelezo ya faili hapo chini, halafu jaribu tena.',
'savefile'                    => 'Hifadhi faili',
'uploadedimage'               => 'ameipakia "[[$1]]"',
'overwroteimage'              => 'alipakia toleo jipya la "[[$1]]"',
'uploaddisabled'              => 'Upakiaji umelemazwa.',
'copyuploaddisabled'          => 'Kupakia kwa kupitia URL kumelemazwa.',
'uploadfromurl-queued'        => 'Upakiaji wako umewekwa kwenye foleni.',
'uploaddisabledtext'          => 'Upakiaji wa mafaili umelemazwa.',
'php-uploaddisabledtext'      => 'Upakiaji wa mafaili umelemazwa katika PHP.
Tafadhali utazame kipimo cha file_uploads.',
'uploadscripted'              => 'Faili hili lina HTML au kodi ambazo labda itaeleweka vibaya na kivinjari.',
'uploadvirus'                 => 'Faili lina kirusi!
Maelezo mengine: $1',
'uploadjava'                  => 'Faili ZIP hili lina faili Java .class humo ndani.
Hairuhusiki kupakia mafaili ya Java, kwa sababu yanawezesha kuambaa vizuio vya usalama.',
'upload-source'               => 'Faili la chanzo',
'sourcefilename'              => 'Jina la faili la chanzo:',
'sourceurl'                   => 'URL ya chanzo:',
'destfilename'                => 'Jina la faili la mwishilio:',
'upload-maxfilesize'          => 'Ukubwa wa faili lisizidi: $1',
'upload-description'          => 'Elezo la faili',
'upload-options'              => 'Machaguo ya kupakia',
'watchthisupload'             => 'Kufuatilia faili hili',
'filewasdeleted'              => 'Faili lenye jina hili limeshapakiwa halafu limefutwa.
Unapaswa kuangalia $1 kabla hujapakia tena.',
'filename-bad-prefix'         => "Jina la faili unalolipakia huanza na '''\"\$1\"''', ambalo ni jina lisilo na maana yanayoeleweka kirahisi, ya aina inayotolewa huwa na kamera dijiti.
Tafadhali chagua jina linaloeleweka kirahisi kwa ajili ya faili lako.",
'upload-success-subj'         => 'Upakiaji ulifaulu',
'upload-success-msg'          => 'Umefaulu kupakia faili kutoka kwa [$2]. Faili linapatikana hapa: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Hitilafu ya kupakia',
'upload-failure-msg'          => 'Kulikuwa na tatizo ulipopakia faili kutoka kwa [$2]:

$1',
'upload-warning-subj'         => 'Ilani ya kupakia',
'upload-warning-msg'          => 'Kulitokea tatizo na upakiaji wako kuanzia [$2]. Unaweza kurudi katika [[Special:Upload/stash/$1|fomu ya kupakia]] ili kurekebisha tatizo hili.',

'upload-proto-error'        => 'Itifaki isio sahihi',
'upload-proto-error-text'   => 'Upakiaji wa mbali lazima URL ianze na <code>http://</code> au <code>ftp://</code>.',
'upload-file-error'         => 'Hitilafu ya ndani',
'upload-file-error-text'    => 'Hitilafu ya ndani ilitokea wakati unajaribu kuanzisha faili la muda kwenye seva.
Tafadhali wasiliana na [[Special:ListUsers/sysop|mkabidhi]].',
'upload-misc-error'         => 'Hitilafu ya kupakia isiyojulikana',
'upload-misc-error-text'    => 'Hitilafu isiyojulikana ilitokea wakati wa kupakia.
Tafadhali uhakikishe kwamba URL ni halali na inafikika, halafu jaribu tena.
Tatizo likiendelea, uwasiliane na [[Special:ListUsers/sysop|mkabidhi]].',
'upload-too-many-redirects' => 'URL ilikuwa na maelekezo mengi mno',
'upload-unknown-size'       => 'Ukubwa haujulikani',
'upload-http-error'         => 'Imetokea hitilafu ya HTTP: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'Ilitokea hitilafu wakati wa kufungua faili kwa ajili ya ukaguzi wa ZIP.',
'zip-wrong-format'    => 'Faili lililotajwa si faili la ZIP.',
'zip-bad'             => 'Faili ZIP hili limevurugika ama halisomeka.
Haliwezi kugakuliwa ili kukinga usalama.',
'zip-unsupported'     => 'Faili ZIP hili linatumia bidhaa pepe ya ZIP zisizoeleweka na MediaWiki.
Haliwezi kugakuliwa ili kukinga usalama.',

# Special:UploadStash
'uploadstash-refresh' => 'Zimua orodha ya mafaili',

# img_auth script messages
'img-auth-accessdenied' => 'Ruksa imekataliwa',
'img-auth-nofile'       => 'Hakuna faili la "$1".',
'img-auth-noread'       => 'Mtumiaji hana fursa ya kusoma "$1".',

# HTTP errors
'http-invalid-url'      => 'URL batili: $1',
'http-request-error'    => 'Ombi la HTTP limeshindikana kutokana na hitilafu isiyojulikana.',
'http-read-error'       => 'Hitilafu ya kusoma HTTP.',
'http-timed-out'        => 'Ombi la HTTP muda umepita.',
'http-curl-error'       => 'Hitilafu ya kuleta URL: $1',
'http-host-unreachable' => 'KISARA (URL) haikupatikana',
'http-bad-status'       => 'Kulikuwa na tatizo wakati wa kutekeleza ombi la HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'KISARA (URL) haikupatikana',
'upload-curl-error6-text'  => 'URL iliyotajwa haikuweza kupatikana.
Tafadhali hakikisha kwamba URL ni sahihi na tovuti inafanya kazi.',
'upload-curl-error28'      => 'Muda wa upakiaji umeisha',
'upload-curl-error28-text' => 'Tovuti imechelewa mno kuitikia.
Tafadhali uhakikishe kwamba tovuti inafanya kazi, subiri kidogo halafu jaribu tena.
Unaweza kujaribu wakati tovuti haina kazi nyingi.',

'license'            => 'Hatimiliki:',
'license-header'     => 'Hatimiliki',
'nolicense'          => 'Haikuchaguliwa',
'license-nopreview'  => '(Hakikisho hakipatikani)',
'upload_source_url'  => '(URL halali, inayopatikana kwa umma)',
'upload_source_file' => '(faili kwenye tarakilishi yako)',

# Special:ListFiles
'listfiles-summary'     => 'Ukurasa huu maalum unaonyesha mafaili yote yaliyopakiwa.
Kwa kawaida mafaili ya mwisho kupakiwa yanaonekana juu kabisa ya orodha.
Ukibofya kichwa cha safu mtindo wa kupanga orodha utabadilika.',
'listfiles_search_for'  => 'Tafuta jina la faili:',
'imgfile'               => 'faili',
'listfiles'             => 'Orodha ya mafaili',
'listfiles_thumb'       => 'Picha ndogo',
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
'imagelinks'                => 'Matumizi ya faili',
'linkstoimage'              => '{{PLURAL:$1|Ukurasa huu umeunganishwa|Kurasa hizi $1 zimeunganishwa}} na faili hili:',
'linkstoimage-more'         => 'Zipo {{PLURAL:$1|kurasa|kurasa}} zaidi ya $1 zinazounga na faili hili.
Orodha inayofuata inaonyesha {{PLURAL:$1|kiungo cha kwanza|viungo $1 vya kwanza}} tu vinavyoungana na faili hili.
[[Special:WhatLinksHere/$2|Orodha nzima]] inapatikana.',
'nolinkstoimage'            => 'Hakuna kurasa zozote zilizounganishwa na faili hii.',
'morelinkstoimage'          => 'Tazama [[Special:WhatLinksHere/$1|viungo vingine]] vinavyoelekeza faili hili.',
'linkstoimage-redirect'     => '$1 (elekezo la faili) $2',
'duplicatesoffile'          => '{{PLURAL:$1|Faili linalofuata ni nakala ya|Mafaili $1 yanayofuata ni nakala za}} faili hili ([[Special:FileDuplicateSearch/$2|maelezo mengine]]):',
'sharedupload'              => 'Faili hili linatoka $1 na linaweza kushirikiwa na miradi mingine.',
'sharedupload-desc-there'   => 'Faili hili linatoka $1 na huenda likawa limetumika na miradi mingine.
Tafadhali tazama [$2 ukurasa wa maelezo ya faili] kwa maelezo zaidi.',
'sharedupload-desc-here'    => 'Faili hili linatoka $1 na huenda likawa limetumika na miradi mingine.
Maelezo yaliyopo katika [$2 ukurasa wa maelezo ya faili] linaonyeshwa hapa.',
'filepage-nofile'           => 'Hakuna faili yenye jina hili.',
'filepage-nofile-link'      => 'Hakuna faili lenye jina hili, lakini unaweza [$1 kulipakia].',
'uploadnewversion-linktext' => 'Pakia toleo jipya la faili hili',
'shared-repo-from'          => 'kutoka kwa $1',
'shared-repo'               => 'hifadhi ya pamoja',

# File reversion
'filerevert'                => 'Rejesha $1',
'filerevert-legend'         => 'Rejesha faili',
'filerevert-intro'          => "Unataka kulirudisha faili la '''[[Media:$1|$1]]''' hadi [$4 pitio la saa $3, tarehe $2].",
'filerevert-comment'        => 'Sababu:',
'filerevert-defaultcomment' => 'Ilirejeshwa hadi sahihisho lile la $2, $1',
'filerevert-submit'         => 'Rejesha',
'filerevert-success'        => "'''[[Media:$1|$1]]''' limerudishwa hadi [$4 pitio la saa $3, tarehe $2].",
'filerevert-badversion'     => 'Katika wiki hii hakuna mtindo wa awali wa faili hili lenye stempu ya saa iliyotajwa.',

# File deletion
'filedelete'                   => 'Futa $1',
'filedelete-legend'            => 'Futa faili',
'filedelete-intro'             => "Unataka kufuta faili la '''[[Media:$1|$1]]''' pamoja na historia yake yote.",
'filedelete-intro-old'         => "You are deleting the version of '''[[Media:$1|$1]]''' as of [$4 $3, $2].",
'filedelete-comment'           => 'Sababu:',
'filedelete-submit'            => 'Futa',
'filedelete-success'           => "'''$1''' limefutwa.",
'filedelete-success-old'       => "The version of '''[[Media:$1|$1]]''' as of $3, $2 has been deleted.",
'filedelete-nofile'            => "Hakuna faili la '''$1'''.",
'filedelete-nofile-old'        => "There is no archived version of '''$1''' with the specified attributes.",
'filedelete-otherreason'       => 'Sababu nyingine:',
'filedelete-reason-otherlist'  => 'Sababu nyingine',
'filedelete-reason-dropdown'   => '*Sababu zinazotolewa mara kwa mara
** Kosa la hakimiliki
** Faili la nakili',
'filedelete-edit-reasonlist'   => 'Kuhariri orodha ya sababu za kufuta',
'filedelete-maintenance'       => 'Tovuti inarekebishwa. Kwa muda huo kufuta na kurudisha mafaili haiwezikani.',
'filedelete-maintenance-title' => 'Faili hilifutiki',

# MIME search
'mimesearch'         => 'Utafutaji wa MIME',
'mimesearch-summary' => 'Ukarasa huu unawezesha kuchuja mafaili kutokana na aina ya MIME. 
Ingiza: aina ya faili/aina mahususi, kwa mfano <tt>image/jpeg</tt>.',
'mimetype'           => 'Aina ya MIME:',
'download'           => 'pakua',

# Unwatched pages
'unwatchedpages' => 'Kurasa zisizofuatiliwa',

# List redirects
'listredirects' => 'Maelekezo',

# Unused templates
'unusedtemplates'     => 'Vigezo ambavyo havitumiwi',
'unusedtemplatestext' => 'Ukurasa huu unaorodhesha kurasa zote katika eneo la wiki la {{ns:template}} zisizowekwa ndani ya ukurasa mwingine.
Kumbuka kuhakikisha kwamba hakuna viungo vingine vinavyoelekea kigezo fulani kabla hujakifuta.',
'unusedtemplateswlh'  => 'viungo vingine',

# Random page
'randompage'         => 'Ukurasa wa bahati',
'randompage-nopages' => 'Hakuna kurasa katika {{PLURAL:$2|eneo la wiki lifuatalo|maeneo ya wiki yafuatayo}}: $1.',

# Random redirect
'randomredirect'         => 'Elekezo la bahati',
'randomredirect-nopages' => 'Hakuna maelekezo katika eneo la wiki la "$1".',

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
'statistics-views-total'       => 'Jumla ya mitazamaji',
'statistics-views-total-desc'  => 'Ziara za kurasa zisizopatikana na kurasa maalum hazihesabiwi',
'statistics-views-peredit'     => 'Mitazamaji kwa haririo',
'statistics-users'             => '[[Special:ListUsers|Watumiaji]] waliojisajiri',
'statistics-users-active'      => 'Watumiaji wanaofanya kazi',
'statistics-users-active-desc' => 'Watumiaji waliofanya kazi katika siku {{PLURAL:$1|iliyopita|$1 zilizopita}}',
'statistics-mostpopular'       => 'Kurasa zinazotazamwa sana',

'disambiguations'      => 'Kurasa za kuainisha maneno',
'disambiguationspage'  => 'Template:Maana',
'disambiguations-text' => "Kurasa zinazofuata zina viungo vinavyoelekea '''kurasa ya kutofautishana maana'''.
Ni afadhali kiungo kiende makala inayostahili moja kwa moja.<br />
Kurasa za kutofautishana maana ni zile zinazotumia kigezo kinachoorodheshwa katika ukurasa wa [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Maelekezo mawilimawili',
'doubleredirectstext'               => 'Ukurasa huu unaorodhesha kurasa zinazoelekeza kurasa zingine za kuelekeza.
Katika kila mstari kuna viungo vinavyokwenda katika kurasa za kuelekeza zote mbili, pamoja na ukurasa wa mwishilio mwa elekezo la pili. Ukurasa huu wa mwishilio huwa ni ukurasa unaostahili kuelekezwa kutoka kwa ukurasa wa kuelekeza wa kwanza. Vitu <del>vilivyokatwa kwa mstari</del> vimeshatatuliwa.',
'double-redirect-fixed-move'        => '[[$1]] umehamishwa.
Sasa unaelekeza [[$2]].',
'double-redirect-fixed-maintenance' => 'Elekezo maradufu inarekebishwa toka [[$1]] kwenda [[$2]].',
'double-redirect-fixer'             => 'Boti ya kurekebisha maelekezo',

'brokenredirects'        => 'Maelekezo yenye hitilafu',
'brokenredirectstext'    => 'Maelekezo yafuatayo yanaelekeza katika kurasa zisizopatikana:',
'brokenredirects-edit'   => 'hariri',
'brokenredirects-delete' => 'futa',

'withoutinterwiki'         => 'Kurasa bila viungo kwenye lugha zingine',
'withoutinterwiki-summary' => 'Kurasa zifuatazo hazijaunganishwa na kurasa za matoleo ya lugha nyingine.',
'withoutinterwiki-legend'  => 'Kiambishi awali (jina la eneo la wiki)',
'withoutinterwiki-submit'  => 'Onyesha',

'fewestrevisions' => 'Kurasa zenye mapitio machache kuliko zote',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|baiti|baiti}} $1',
'ncategories'             => '{{PLURAL:$1|jamii|jamii}} $1',
'nlinks'                  => '{{PLURAL:$1|kiungo|viungo}} $1',
'nmembers'                => '{{PLURAL:$1|kitu|vitu}} $1',
'nrevisions'              => '{{PLURAL:$1|pitio|mapitio}} $1',
'nviews'                  => '{{PLURAL:$1|mtazamaji|mitazamaji}} $1',
'nimagelinks'             => 'Inatumika katika {{PLURAL:$1|ukurasa moja tu|kurasa $1}}',
'ntransclusions'          => 'inatumika katika {{PLURAL:$1|ukurasa moja tu|kurasa $1}}',
'specialpage-empty'       => 'Hakuna matokeo katika taarifa hii.',
'lonelypages'             => 'Kurasa ambazo haziungwi kutoka ukurasa mwingine wowote',
'uncategorizedpages'      => 'Kurasa ambazo hazijawekwa katika jamii',
'uncategorizedcategories' => 'Jamii ambazo hazijawekwa katika jamii',
'uncategorizedimages'     => 'Mafaili ambazo hazijawekwa katika jamii',
'uncategorizedtemplates'  => 'Vigezo ambavyo havijawekwa katika jamii',
'unusedcategories'        => 'Jamii ambazo hazitumiwi',
'unusedimages'            => 'Mafaili ambayo hayatumiwi',
'popularpages'            => 'Kurasa zinazopendelewa',
'wantedcategories'        => 'Jamii zinazotakiwa',
'wantedpages'             => 'Kurasa zinazotakiwa',
'wantedpages-badtitle'    => 'Lipo jina batili katika matokeo: $1',
'wantedfiles'             => 'Mafaili yanayokosekana',
'wantedtemplates'         => 'Vigezo vinavyotakiwa',
'mostlinked'              => 'Kurasa zinazoungwa kuliko zote',
'mostlinkedcategories'    => 'Jamii zinazoungwa kuliko zote',
'mostlinkedtemplates'     => 'Vigezo vinavyoungwa kuliko zote',
'mostcategories'          => 'Jamii ambazo hazitumiwi',
'mostimages'              => 'Mafaili yanayoungwa kuliko yote',
'mostrevisions'           => 'Kurasa zenye mapitio mengi kuliko zote',
'prefixindex'             => 'Kurasa zote zenye viambishi awali',
'shortpages'              => 'Kurasa fupi',
'longpages'               => 'Kurasa ndefu',
'deadendpages'            => 'Kurasa ambazo haziungi na ukurasa mwingine wowote',
'deadendpagestext'        => 'Kurasa zifuatazo haziungana na kurasa zingine katika {{SITENAME}}.',
'protectedpages'          => 'Kurasa zinazolindwa',
'protectedpages-indef'    => 'Zinazolindwa kwa muda wote tu',
'protectedpages-cascade'  => 'zinazokuwa na ulindaji kwa kurasa chini zake tu',
'protectedpagestext'      => 'Kurasa zifuatazo zinalindwa zisisogezwe wala zisihaririwe',
'protectedtitles'         => 'Majina yanayozuluiwa',
'protectedtitlestext'     => 'Yafuatayo ni majina ya kurasa yanayozuluiwa kuyatumia',
'listusers'               => 'Orodha ya Watumiaji',
'listusers-editsonly'     => 'Onyesha watumiaji wenye kuhariri tu',
'listusers-creationsort'  => 'Panga kwa tarehe ya kuanzishwa',
'usereditcount'           => '{{PLURAL:$1|haririo|maharirio}} $1',
'usercreated'             => '{{GENDER:$3|Iliwekewa}} saa $2 tarehe $1',
'newpages'                => 'Kurasa mpya',
'newpages-username'       => 'Jina la mtumiaji:',
'ancientpages'            => 'Kurasa za kale',
'move'                    => 'Hamisha',
'movethispage'            => 'Hamisha ukurasa huu',
'unusedcategoriestext'    => 'Kurasa za jamii zifuatazo zinapatikana, ingawaje hakuna ukurasa wala jamii nyingine iliyowekwa ndani ya jamii hizi.',
'pager-newer-n'           => '{{PLURAL:$1|1 ya karibu zaidi|$1 ya karibu zaidi}}',
'pager-older-n'           => '{{PLURAL:$1|$1 ya zamani zaidi}}',

# Book sources
'booksources'               => 'Vyanzo vya vitabu',
'booksources-search-legend' => 'Tafuta mahali panopopatikana kitabu',
'booksources-go'            => 'Nenda',
'booksources-invalid-isbn'  => 'ISBN iliyoandikwa haonekani kuwa halali; hakikisha kwamba ni sawa na ISBN ya asili.',

# Special:Log
'specialloguserlabel'  => 'Mtumiaji:',
'speciallogtitlelabel' => 'Cheo:',
'log'                  => 'Kumbukumbu',
'all-logs-page'        => 'Kumbukumbu zote zilizo wazi',
'alllogstext'          => 'Hapa panaonyeshwa kumbukumbu zote za {{SITENAME}} kwa pamoja.
Unaweza kuona baadhi yao tu kwa kuchagua aina fulani ya kumbukumbu, jina la mtumiaji fulani (zingatia herufi kubwa na ndogo), au jina la ukurasa fulani (zingatia herufi kubwa na ndogo).',
'logempty'             => 'Vitu vyenye vipengele hivi havipo kwenye kumbukumbu.',
'log-title-wildcard'   => 'Tafuta kurasa zenye vichwa vinavyoanza na maandishi haya',

# Special:AllPages
'allpages'          => 'Kurasa zote',
'alphaindexline'    => '$1 hadi $2',
'nextpage'          => 'Ukurasa ujao ($1)',
'prevpage'          => 'Ukurasa uliotangulia ($1)',
'allpagesfrom'      => 'Onyesha kurasa zinazoanza kutoka:',
'allpagesto'        => 'Onyesha kurasa zinazoishia na:',
'allarticles'       => 'Kurasa zote',
'allinnamespace'    => 'Kurasa zote (eneo la wiki $1)',
'allnotinnamespace' => 'Kurasa zote (zisizo katika eneo la wiki ya $1)',
'allpagesprev'      => 'Iliyotangulia',
'allpagesnext'      => 'Ijayo',
'allpagessubmit'    => 'Nenda',
'allpagesprefix'    => 'Onyesha kurasa zenye kiambishi awali:',
'allpagesbadtitle'  => 'Jina la ukurasa ni batili au linatumia kiambishi awali cha mradi mwingine.
Inaweza kuwa na herufi isiyoweza kutumiwa ndani ya majina ya kurasa.',
'allpages-bad-ns'   => 'Eneo la "$1" halipatikani kwenye {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Jamii',
'categoriespagetext'            => 'Jamii {{PLURAL:$1|inayofuata ina|zinazofuata zina}} kurasa au mafaili ya picha au sauti.
[[Special:UnusedCategories|Jamii zisizotumiwa]] hazitandazwi hapa.
Tazama pia [[Special:WantedCategories|jamii zinazohitajika]].',
'categoriesfrom'                => 'Tandaza jamii kuanzia na:',
'special-categories-sort-count' => 'panga kwa idadi',
'special-categories-sort-abc'   => 'panga kwa herufi',

# Special:DeletedContributions
'deletedcontributions'             => 'Michango ya mtumiaji aliyefutwa',
'deletedcontributions-title'       => 'Michango ya mtumiaji aliyefutwa',
'sp-deletedcontributions-contribs' => 'michango',

# Special:LinkSearch
'linksearch'       => 'Kutafuta viungo vya nje',
'linksearch-pat'   => 'Herufi zitakazotafutwa:',
'linksearch-ns'    => 'Eneo la wiki:',
'linksearch-ok'    => 'Tafuta',
'linksearch-text'  => 'Alama maalum za kutafuta kama "*.wikipedia.org" zinaweza kutumika.<br />
Itifaki zinazoungwa mkono: <tt>$1</tt>',
'linksearch-line'  => '$2 umeungwa kutoka $1',
'linksearch-error' => 'Alama maalum za kutafuta zinaweza kutumika mwanzoni mwa URL tu.',

# Special:ListUsers
'listusersfrom'      => 'Onyesha watumiaji kuanzia:',
'listusers-submit'   => 'Onyesha',
'listusers-noresult' => 'Mtumiaji hakupatikana.',
'listusers-blocked'  => '(imezuiwa)',

# Special:ActiveUsers
'activeusers'            => 'Orodha ya watumiaji hai',
'activeusers-intro'      => 'Hii ni orodha ya watumiaji walioshughulika jambo fulani ndani ya siku $1 {{PLURAL:$1|iliyopita|zilizopita}}.',
'activeusers-count'      => '{{PLURAL:$1|haririo|maharirio}} $1 katika siku $3 {{PLURAL:$3|iliyopita|zilizopita}}',
'activeusers-from'       => 'Onyesha watumiaji kuanzia:',
'activeusers-hidebots'   => 'Ficha boti',
'activeusers-hidesysops' => 'Ficha wakabidhi',
'activeusers-noresult'   => 'Watumiaji hawakupatikana.',

# Special:Log/newusers
'newuserlogpage'     => 'Kumbukumbu za kuanzisha akaunti za watumiaji',
'newuserlogpagetext' => 'Hii ni kumbukumbu ya akaunti mpya zilizosajiliwa.',

# Special:ListGroupRights
'listgrouprights'                      => 'Wezo za kundi za watumiaji',
'listgrouprights-summary'              => 'Inafuata orodha ya kundi za watumiaji wa wiki hii, pamoja na maelezo ya wezo zao za kushughulika mambo.
Labda patakuwa na [[{{MediaWiki:Listgrouprights-helppage}}|maelezo mengine]] kuhusu wezo zingine.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Uwezo uliopewa</span>
* <span class="listgrouprights-revoked">Uwezo uliotolewa</span>',
'listgrouprights-group'                => 'Kundi',
'listgrouprights-rights'               => 'Wezo',
'listgrouprights-helppage'             => 'Help:Uwezo wa makundi',
'listgrouprights-members'              => '(orodha ya wanachama)',
'listgrouprights-addgroup'             => 'Kuongeza {{PLURAL:$2|kundi|makundi}}: $1',
'listgrouprights-removegroup'          => 'Kuondoa {{PLURAL:$2|kundi|makundi}}: $1',
'listgrouprights-addgroup-all'         => 'Kuongeza makundi yote',
'listgrouprights-removegroup-all'      => 'Kuondoa makundi yote',
'listgrouprights-addgroup-self'        => 'Kuongeza {{PLURAL:$2|kundi|makundi}} katika akaunti ya binafsi: $1',
'listgrouprights-removegroup-self'     => 'Kuondoa {{PLURAL:$2|kundi|makundi}} kutoka katika akaunti ya binafsi: $1',
'listgrouprights-addgroup-self-all'    => 'Kuongeza makundi yote katika akaunti ya binafsi',
'listgrouprights-removegroup-self-all' => 'Kuondoa makundi yote kutoka akaunti ya binafsi',

# E-mail user
'mailnologin'          => 'Hakuna anwani wa kutuma',
'mailnologintext'      => 'Ukitaka kutuma barua pepe kwa watumiaji wengine inabidi uwe [[Special:UserLogin|umeshaingia kwenye akaunti yako]] na pia uwe na anwani ya barua pepe sahihi pale [[Special:Preferences|mapendekezo yako]].',
'emailuser'            => 'Mtumie mtumiaji huyu barua pepe',
'emailpage'            => 'Kumtumia mtumiaji barua pepe',
'emailpagetext'        => 'Utumie fomu iliopo chini ili kutuma barua pepe kwa mtumiaji huyu.
Anwani yako ya barua pepe ulioitaja katika [[Special:Preferences|mapendekezo yako]] itaandikwa kwenye sanduku la anwani "Kutoka kwa" katika barua pepe, ili mtu atakayeipokea aweze kukujibu moja kwa moja.',
'usermailererror'      => 'Chombo cha ujumbe kimerejesha hitilafu:',
'defemailsubject'      => 'Barua pepe ya {{SITENAME}} iliyotumwa na mtumiaji "$1"',
'usermaildisabled'     => 'Uwezo wa kutuma barua pepe kwa mtumiaji umesitishwa',
'usermaildisabledtext' => 'Huwezi kutuma barua pepe kwa watumiaji wengine wa wiki hii',
'noemailtitle'         => 'Anwani ya barua pepe hakuna',
'noemailtext'          => 'Mtumiaji huyu hajataja anwani sahihi ya barua pepe.',
'nowikiemailtitle'     => 'Barua pepe haziruhusiwi',
'nowikiemailtext'      => 'Mtumiaji huyu hajakubali kupokea barua pepe kutoka kwa watumiaji wengine.',
'emailnotarget'        => 'Jina la mpokeaji uliloweka halipatikani',
'emailtarget'          => 'Andika jina la mtumiaji la mpokeaji',
'emailusername'        => 'Jina la mtumiaji:',
'emailusernamesubmit'  => 'Wasilisha',
'email-legend'         => 'Tuma barua pepe kwa mtumiaji mwingine wa {{SITENAME}}',
'emailfrom'            => 'Kutoka kwa:',
'emailto'              => 'Kwa:',
'emailsubject'         => 'Mada:',
'emailmessage'         => 'Ujumbe:',
'emailsend'            => 'Tuma',
'emailccme'            => 'Tuma nakala ya barua yangu ya pepe kwangu.',
'emailccsubject'       => 'Nakala ya barua pepe uliotuma kwa $1: $2',
'emailsent'            => 'Barua pepe imetumwa',
'emailsenttext'        => 'Barua pepe yako imetumwa.',
'emailuserfooter'      => 'Barua pepe hii imetumwa na $1 kwa $2 kwa kutumia zana ya "Kumtumia mtumiaji barua pepe" iliyopo {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Maangalizi yangu',
'mywatchlist'          => 'Maangalizi yangu',
'watchlistfor2'        => 'Kwa ajili ya $1 $2',
'nowatchlist'          => 'Hamna vitu katika maangalizi yako.',
'watchlistanontext'    => 'Tafadhali $1 ili kutazama au kuhariri vitu vilivyopo katika orodha yako ya maangalizi.',
'watchnologin'         => 'Hujaingia',
'watchnologintext'     => 'Lazima uwe [[Special:UserLogin|umeshaingia]] ili uweze kuhariri orodha ya maangalizi yako.',
'addwatch'             => 'Ongeza kwenye orodha ya maangalizi',
'addedwatchtext'       => "Ukurasa \"[[:\$1]]\" umewekwa kwenye [[Special:Watchlist|maangalizi]] yako.
Mabadiliko katika ukurasa huo na ukurasa wake wa majadiliano utaonekana hapo,
na ukurasa utaonyeshwa wenye '''koze''' kwenye [[Special:RecentChanges|orodha ya mabadiliko ya karibuni]]
ili kukusaidia kutambua.

Ukitaka kufuta ukurasa huo kutoka maangalizi yako baadaye, bonyeza \"Acha kufuatilia\" katika mwamba pembeni.",
'removewatch'          => 'Ondoa kutoka orodha ya maangalizi',
'removedwatchtext'     => 'Ukurasa "[[:$1]]" umeondoshwa kutoka katika [[Special:Watchlist|maangalizi yako]].',
'watch'                => 'Fuatilia',
'watchthispage'        => 'Fuatilia ukurasa huu',
'unwatch'              => 'Acha kufuatilia',
'unwatchthispage'      => 'Acha kufuatilia',
'notanarticle'         => 'Ukurasa nje ya kusudi ya wiki',
'notvisiblerev'        => 'Haririo ya mwisho, iliotendwa na mtumiaji mwingine, imefutwa',
'watchnochange'        => 'Hakuna kitu kati ya maangalizi yako kilichohaririwa katika kipindi kilichotajwa.',
'watchlist-details'    => 'Unafuatilia {{PLURAL:$1|ukurasa $1|kurasa $1}} bila kuzingatia kurasa za majadiliano.',
'wlheader-enotif'      => '* Huduma ya kuarifu kwa barua pepe imewezeshwa.',
'wlheader-showupdated' => "* Kurasa zilizobadilika tangu ulipoziona mwishoni zinaonyeshwa '''kooze'''",
'watchmethod-recent'   => 'kupitia madabiliko ya karibuni ili kupata kurasa za maangalizi',
'watchmethod-list'     => 'kupitia kurasa za maangalizi ili kupata madabiliko ya karibuni',
'watchlistcontains'    => 'Orodha ya maangalizi yako ina {{PLURAL:$1|kitu|vitu}} $1.',
'iteminvalidname'      => "Kitu '$1' kina tatizo la jina batili...",
'wlnote'               => "{{PLURAL:$1|Badiliko la|Mabadiliko '''$1''' ya}} mwisho katika {{PLURAL:$2|saa iliyopita linaonyeshwa|masaa '''$2''' yaliyopita yanaonyeshwa}} chini.",
'wlshowlast'           => 'Onyesha kutoka masaa $1 siku $2 $3',
'watchlist-options'    => 'Hitiari za maangalizi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Unafuatilia...',
'unwatching'     => 'Umeacha kufuatilia...',
'watcherrortext' => 'Hitilafu ilitokea ulipojaribu kubadilisha vipimo vya maangalizi yako kwa ajili ya "$1".',

'enotif_mailer'                => 'Huduma ya taarifa ya barua pepe kutoka kwa {{SITENAME}}',
'enotif_reset'                 => 'Weka alama ya kutembelewa kwenye kurasa zote',
'enotif_newpagetext'           => 'Ukurasa huu ni mpya.',
'enotif_impersonal_salutation' => 'Kwa mtumiaji wa {{SITENAME}}',
'changed'                      => 'alibadilisha',
'created'                      => 'alianzisha',
'enotif_subject'               => '$PAGEEDITOR $CHANGEDORCREATED ukurasa wa $PAGETITLE kwenye {{SITENAME}}',
'enotif_lastvisited'           => 'Tazama mabadiliko yote tangu ziara yako iliyopita kwenye ukurasa wa $1.',
'enotif_lastdiff'              => 'Tazama badiliko hili hapo $1.',
'enotif_anon_editor'           => 'mtumiaji bila jina $1',
'enotif_body'                  => 'Mpendwa $WATCHINGUSERNAME,


$PAGEEDITOR $CHANGEDORCREATED ukurasa wa $PAGETITLE kwenye {{SITENAME}} saa $PAGEEDITDATE. Tazama $PAGETITLE_URL kuona ukurasa ulivyo sasa hivi.

$NEWPAGE

Muhtasari wa mhariri: $PAGESUMMARY $PAGEMINOREDIT

Uwasiliane na mhariri kwa njia hizi:
barua pepe: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Hutapata taarifa za mabadiliko mengine yatakayotokea kwenye ukurasa huu hadi utakapotazama ukurasa.
Au unaweza kuweka upya maombi ya kupewa taarifa kwa ajili ya kurasa zote zilizopo kwenye orodha yako ya maangalizi.

             Kutoka kwa {{SITENAME}}

--
Ukitaka kubadilisha mapendekezo yako yanayohusika orodha ya maangalizi yako, nenda
{{canonicalurl:{{#special:EditWatchlist}}}}

Ukitaka kutoa ukurasa huu kwenye orodha ya maangalizi yako, nenda
$UNWATCHURL

Kutoa maoni yako au kupata msaada mwingine:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Futa ukurasa',
'confirm'                => 'Yakinisha',
'excontent'              => "iliyokuwemo: '$1'",
'excontentauthor'        => 'yaliyomo yalikuwa: "$1" (yaliyechangiwa na mchangiaji mmoja tu anayeitwa "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => 'maandishi kabla hayajafutwa yote yalikuwa: "$1"',
'exblank'                => 'ukurasa ulikuwa tupu',
'delete-confirm'         => 'Futa "$1"',
'delete-legend'          => 'Futa',
'historywarning'         => "'''Ilani:''' Ukurasa unaotaka kufuta una historia ya {{PLURAL:$1|pitio|mapitio}} $1 hivi:",
'confirmdeletetext'      => 'Wewe unategemea kufuta ukurasa pamoja na historia yake yote.
Tafadhali hakikisha kwamba unalenga kufanya hivyo, na kwamba unaelewa matokeo yake, na kwamba unafuata [[{{MediaWiki:Policy-url}}|sera]].',
'actioncomplete'         => 'Kitendo kimekwisha',
'actionfailed'           => 'Tendo halikufaulu',
'deletedtext'            => '"$1" imefutwa. Ona $2 kwa historia ya kurasa zilizofutwa hivi karibuni.',
'dellogpage'             => 'Kumbukumbu ya ufutaji',
'dellogpagetext'         => 'Kurasa na mafaili zilizofutwa hivi karibuni zinaorodheshwa chini.',
'deletionlog'            => 'kumbukumbu za kufuta',
'reverted'               => 'Ilirejeshwa hadi pitio la zamani',
'deletecomment'          => 'Sababu:',
'deleteotherreason'      => 'Sababu nyingine:',
'deletereasonotherlist'  => 'Sababu nyingine',
'deletereason-dropdown'  => '*Sababu za kawaida za ufutaji
** Ombi la mmiliki
** Ukiukaji wa hakimiliki
** Uharabu',
'delete-edit-reasonlist' => 'Uhariri orodha ya sababu za kufuta',
'delete-toobig'          => 'Ukurasa huu una historia ya kuhariri ndefu sana, yenye {{PLURAL:$1|badiliko|mabadiliko}} zaidi na $1.
Ufutaji wa kurasa hizi moja kwa moja umezuluiwa ili {{SITENAME}} isivurugwe kwa bahati mbaya.',
'delete-warning-toobig'  => 'Ukurasa huu unao mapitio mengi, zaida ya {{PLURAL:$1|pitio|mapitio}} $1.
Ukiufuta labda itavuruga uendeshaji wa hifadhidata ya {{SITENAME}};
endelea kwa uangalifu.',

# Rollback
'rollback'          => 'Rejesha masahihisho',
'rollback_short'    => 'Rejesha',
'rollbacklink'      => 'rejesha',
'rollbackfailed'    => 'Haikufaulu kurejesha',
'cantrollback'      => 'Haiwezekana kujesha sahihisho;
ukurasa huu una mhariri mmoja tu.',
'editcomment'       => "Muhtasari wa kuhariri ilikuwa: \"''\$1''\".",
'revertpage'        => 'Masahihisho aliyefanya [[Special:Contributions/$2|$2]] ([[User talk:$2|Majadiliano]]) yalirejeshwa hadi sahihisho la mwisho na [[User:$1|$1]]',
'revertpage-nouser' => 'Masahihisho ya mtumiaji (jina lake limefichwa) yamerudishwa hadi pitio la mwisho lililotengenezwa na [[User:$1|$1]].',
'rollback-success'  => 'Masahihisho aliyeyafanya $1 yalirejeshwa hadi kufika sahihisho la mwisho aliyefanya $2.',

# Edit tokens
'sessionfailure-title' => 'Kushindikana cha kipindi',

# Protect
'protectlogpage'              => 'Kumbukumbu ya ulindaji',
'protectedarticle'            => 'aliulinda "[[$1]]"',
'modifiedarticleprotection'   => 'alibadilisha kiwango cha ulindaji kwa ajili ya "[[$1]]"',
'unprotectedarticle'          => 'alitoa ulindaji wa "[[$1]]"',
'movedarticleprotection'      => 'alihamisha ulindaji wa "[[$2]]" hadi "[[$1]]"',
'protect-title'               => 'Kubadilisha kiwango cha ulindaji wa "$1"',
'protect-title-notallowed'    => 'Tazama kiwango cha ulindaji wa "$1"',
'prot_1movedto2'              => 'alihamisha [[$1]] hadi [[$2]]',
'protect-badnamespace-title'  => 'Eneo la wiki lisiloweza kulindwa',
'protect-badnamespace-text'   => 'Kurasa zilizopo katika eneo hili la wiki haziwezi kulindwa',
'protect-legend'              => 'Hakikisha ukingo',
'protectcomment'              => 'Sababu:',
'protectexpiry'               => 'Itakwisha:',
'protect_expiry_invalid'      => 'Muda wa kwisha ni batilifu.',
'protect_expiry_old'          => 'Muda wa kuishi umepita tayari.',
'protect-unchain-permissions' => 'Fungua chaguzi zingine za ulinzi',
'protect-text'                => "Unaweza kutazama na kubadilisha kiwango cha ulindaji hapa kwa ukurasa '''$1'''.",
'protect-locked-dblock'       => "Viwango vya ulindaji haviwezi kubadilishwa kwa sababu hifadhidata imefungwa.
Hapo panaandikwa viwango vya ulindaji wa ukurasa '''$1''':",
'protect-locked-access'       => "Akaunti yako hairuhusiwi kubadilisha viwango vya ulindaji.
Hivi ni vipimo kwa ukurasa '''$1''':",
'protect-cascadeon'           => 'Ukurasa huu umelindwa kwa sababu umezingatiwa katika {{PLURAL:$1|ukurasa $1 unaolinda kurasa chini yake|kurasa $1 zinazolinda kurasa chini yake}}. Unaweza kubadilisha kiwango cha ulindaji wa ukurasa huu, lakini hutaathirika ulindaji kutoka kurasa juu yake.',
'protect-default'             => 'Kubalia watumiaji wote',
'protect-fallback'            => 'Lazimisha ruhusa "$1"',
'protect-level-autoconfirmed' => 'Zuia watumiaji wapya au wale ambao hawajajisajilisha',
'protect-level-sysop'         => 'Wakabidhi tu',
'protect-summary-cascade'     => 'ulindaji kwa kurasa chini yake',
'protect-expiring'            => 'itakwisha $1 (UTC)',
'protect-expiring-local'      => 'inaishia saa $1',
'protect-expiry-indefinite'   => 'bila mwisho',
'protect-cascade'             => 'Linda kurasa zinazozingatiwa chini ya ukurasa huu',
'protect-cantedit'            => 'Huwezi kubadilisha kiwango cha ulindaji wa ukurasa huu, kwa sababu huruhusiwi kuuhariri.',
'protect-othertime'           => 'Kipindi kingine:',
'protect-othertime-op'        => 'kipindi kingine',
'protect-existing-expiry'     => 'Kipindi cha ulindaji uliowekwa unaishia: $3, $2',
'protect-otherreason'         => 'Sababu nyingine:',
'protect-otherreason-op'      => 'Sababu nyingine',
'protect-dropdown'            => '*Sababu za kawaida za ulindaji
** Uharabu kupindukia
** Upuuzi kupindukia
** Onyo-la-kuzuia kuhariri
** Kurasa inatembelewa sana',
'protect-edit-reasonlist'     => 'Hariri sababu za kulinda',
'protect-expiry-options'      => 'saa 1:1 hour,siku 1:1 day,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite',
'restriction-type'            => 'Ruhusa:',
'restriction-level'           => 'Kiwango cha kizuia:',
'minimum-size'                => 'Saizi ndogo mno',
'maximum-size'                => 'Saizi kubwa mno:',
'pagesize'                    => '(baiti)',

# Restrictions (nouns)
'restriction-edit'   => 'Kuhariri',
'restriction-move'   => 'Kuhamisha',
'restriction-create' => 'Kuanzisha',
'restriction-upload' => 'Kupakia',

# Restriction levels
'restriction-level-sysop'         => 'umelindwa kabisa',
'restriction-level-autoconfirmed' => 'umelindwa kwa kiasi',
'restriction-level-all'           => 'chochote',

# Undelete
'undelete'                  => 'Kuzitazama kurasa zilizofutwa',
'undeletepage'              => 'Kutazama na kurudisha kurasa zilizofutwa',
'undeletepagetitle'         => "'''Ifuatayo ni mapitio yaliyofutwa ya [[:$1|$1]]'''.",
'viewdeletedpage'           => 'Tazama kurasa zilizofutwa',
'undelete-fieldset-title'   => 'Kurudisha mapitio',
'undeletebtn'               => 'Rudisha',
'undeletelink'              => 'onyesha/rejesha',
'undeleteviewlink'          => 'tazama',
'undeletereset'             => 'Seti upya',
'undeleteinvert'            => 'Geuza uteuzi',
'undeletecomment'           => 'Sababu:',
'undeletedrevisions'        => '{{PLURAL:$1|pitio 1 lilirudishwa|mapitio $1 yalirudishwa}}',
'undeletedfiles'            => '{{PLURAL:$1|faili 1 lilirudishwa|mafaili $1 yalirudishwa}}',
'cannotundelete'            => 'Kurudisha ukurasa imeshindikana;
huenda ikawa mtu mwingine ameurudisha tayari.',
'undelete-header'           => 'Tazama [[Special:Log/delete|kumbukumbu za ufutaji]] ili kujua kurasa zipi zilizofutwa hivi karibuni.',
'undelete-search-box'       => 'Tafuta kwenye kurasa zilizofutwa',
'undelete-search-prefix'    => 'Onyesha kurasa kuanzia na:',
'undelete-search-submit'    => 'Tafuta',
'undelete-error-short'      => 'Hitilafu wakati wa kurudisha faili: $1',
'undelete-error-long'       => 'Ilitokea hitilafu wakati wa kurudisha faili:

$1',
'undelete-show-file-submit' => 'Ndiyo',

# Namespace form on various pages
'namespace'             => 'Chagua eneo la wiki:',
'invert'                => 'Geuza uteuzi',
'namespace_association' => 'Eneo linalohusisha',
'blanknamespace'        => '(Kuu)',

# Contributions
'contributions'       => 'Michango ya mtumiaji',
'contributions-title' => 'Michango ya mtumiaji $1',
'mycontris'           => 'Michango yangu',
'contribsub2'         => 'Kwa $1 ($2)',
'nocontribs'          => 'Mabadiliko yanayolingana na vigezo vilivyoulizwa hayakupatikana.',
'uctop'               => '(juu)',
'month'               => 'Kutoka mwezi (na zamani zaidi):',
'year'                => 'Kutoka mwakani (na zamani zaidi):',

'sp-contributions-newbies'             => 'Onyesha michango ya akaunti mpya tu',
'sp-contributions-newbies-sub'         => 'Kwa akaunti mpya',
'sp-contributions-newbies-title'       => 'Michango ya watumiaji wenye akaunti mpya',
'sp-contributions-blocklog'            => 'Kumbukumbu ya uzuio',
'sp-contributions-deleted'             => 'michango iliyofutwa ya mtumiaji',
'sp-contributions-uploads'             => 'vipakizaji',
'sp-contributions-logs'                => 'kumbukumbu',
'sp-contributions-talk'                => 'majadiliano',
'sp-contributions-userrights'          => 'usimamizi wa wezo za mtumiaji',
'sp-contributions-blocked-notice'      => 'Mtumiaji huyu bado amezuiwa.
Rejea kumbukumbu ya uzuio ya mwisho inayoandikwa chini:',
'sp-contributions-blocked-notice-anon' => 'Anwani huyu ya IP bado imezuiwa.
Rejea kumbukumbu ya uzuio ya mwisho inayoandikwa chini:',
'sp-contributions-search'              => 'Tafuta michango',
'sp-contributions-username'            => 'Anwani ya IP au jina la mtumiaji:',
'sp-contributions-toponly'             => 'Onesha maharirio ambayo ni mapitio mapya tu',
'sp-contributions-submit'              => 'Tafuta',

# What links here
'whatlinkshere'            => 'Viungo viungavyo ukurasa huu',
'whatlinkshere-title'      => 'Kurasa zilizounganishwa na "$1"',
'whatlinkshere-page'       => 'Ukurasa:',
'linkshere'                => "Kurasa zifuatazo zimeunganishwa na '''[[:$1]]''':",
'nolinkshere'              => "Hakuna kurasa zilizounganishwa na '''[[:$1]]'''.",
'nolinkshere-ns'           => "Hakuna kurasa zilizounganishwa na '''[[:$1]]''' katika eneo la wiki lililochaguliwa.",
'isredirect'               => 'elekeza ukurasa',
'istemplate'               => 'jumuisho',
'isimage'                  => 'kiungo cha faili',
'whatlinkshere-prev'       => '{{PLURAL:$1|uliotangulia|$1 zilizotangulia}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ujao|$1 zijazo}}',
'whatlinkshere-links'      => '← viungo',
'whatlinkshere-hideredirs' => '$1 maelekezo',
'whatlinkshere-hidetrans'  => '$1 majumuisho',
'whatlinkshere-hidelinks'  => '$1 viungo',
'whatlinkshere-hideimages' => '$1 viungo vya picha',
'whatlinkshere-filters'    => 'Machujio',

# Block/unblock
'block'                      => 'Kumzuia mtumiaji',
'unblock'                    => 'Kuacha kumzuia mtumiaji',
'blockip'                    => 'Zuia mtumiaji',
'blockip-title'              => 'Kumzuia mtumiaji',
'blockip-legend'             => 'Kumzuia mtumiaji',
'blockiptext'                => 'Tumia fomu iliyopo chini kumzuia mtu asihariri kwa kupitia anwani fulani wa IP au kwa kutumia jina fulani la mtumiaji.
Nia ya kumzuia mtu inatakiwa kuwa kuzuia uharibifu tu, na ifanikiwe kutokana na masharti ya [[{{MediaWiki:Policy-url}}|sera]].
Andika sababu ya kuzuia chini (kwa mfano, kwa kutaja mifano ya kurasa zilizoharibiwa).',
'ipadressorusername'         => 'Anwani ya IP au jina la mtumiaji:',
'ipbexpiry'                  => 'Itakwisha:',
'ipbreason'                  => 'Sababu:',
'ipbreasonotherlist'         => 'Sababu nyingine',
'ipbreason-dropdown'         => '*Sababu za kawaida za kuzuia
** Kuingiza habari za uongo
** Kuondosha yaliyomo katika kurasa
** Viungo vya nje visivyotakiwa
** Kuingiza upuuzi/ujinga sioeleweka kwenye makala
** Adabu mbaya/kero
** Kusumbua akaunti nyinginyingi
** Jina la mutumiaji lisilokubalika',
'ipb-hardblock'              => 'Kuzuia watumiaji walioingia katika akaunti zao wasihariri kwa kutumia anwani hii ya IP',
'ipbcreateaccount'           => 'Kinga usajili wa akaunti',
'ipbemailban'                => 'Kinga mtumiaji asitume barua-pepe',
'ipbenableautoblock'         => 'Mashine izuie anwani ya mwisho ya IP iliotumiwa na mtumiaji huyu, na IP zozote za baadaye atakayejaribu kutumia',
'ipbsubmit'                  => 'Zuia mtumiaji huyu',
'ipbother'                   => 'Muda mwingine:',
'ipboptions'                 => 'Masaa 2:2 hours,siku 1:1 day,siku 3:3 days,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite',
'ipbotheroption'             => 'engine',
'ipbotherreason'             => 'Engine/sababu ya ziada:',
'ipbhidename'                => 'Ficha jina la mtumiaji katika orodha na kuhariri',
'ipbwatchuser'               => 'Fuatilia kurasa za mtumiaji na majadiliano ya mtumiaji huyu.',
'ipb-disableusertalk'        => 'Kuzuia mtumiaji huu asihariri ukurasa wake mwenyewe wa majadiliano wakati amezuluiwa',
'ipb-confirm'                => 'Uthibitishe uzuio',
'badipaddress'               => 'Anwani batili ya IP',
'blockipsuccesssub'          => 'Kulifaulu kumzuia',
'ipb-edit-dropdown'          => 'Hariri sababu za kuzuia',
'ipb-unblock-addr'           => 'Acha kumzuia $1',
'ipb-unblock'                => 'Acha kumzuia mtumiaji au anwani wa IP',
'ipb-blocklist-contribs'     => 'Michango ya $1',
'blocklist'                  => 'Watumiaji waliozuiliwa',
'ipblocklist'                => 'Watumiaji waliozuiliwa',
'blocklist-timestamp'        => 'Tarehe na saa',
'blocklist-expiry'           => 'Itakwisha',
'blocklist-reason'           => 'Sababu',
'ipblocklist-submit'         => 'Tafuta',
'ipblocklist-otherblocks'    => ' {{PLURAL:$1|Uzuio mwingine|Zuio zingine}}',
'infiniteblock'              => 'milele',
'expiringblock'              => 'inakwisha tarehe $1 saa $2',
'emailblock'                 => 'barua pepe imezuiliwa',
'blocklink'                  => 'zuia',
'unblocklink'                => 'acha kuzuia',
'change-blocklink'           => 'badilisha zuia',
'contribslink'               => 'michango',
'blocklogpage'               => 'Kumbukumbu ya uzuio',
'blocklogentry'              => 'amemzuia [[$1]] mpaka $2 $3',
'unblocklogentry'            => 'aliachisha kuzuia $1',
'block-log-flags-nocreate'   => 'uwezo wa kuunda akaunti imesitishwa',
'block-log-flags-noemail'    => 'barua pepe imezuiliwa',
'block-log-flags-hiddenname' => 'jina la mtumiaji limefichwa',
'ipb_already_blocked'        => '"$1" tayari imeshazuiwa',
'blockme'                    => 'Unizuishe',
'proxyblocksuccess'          => 'Tayari.',

# Developer tools
'lockdb'              => 'Kufunga hifadhidata',
'unlockdb'            => 'Kufungua hifadhidata',
'lockconfirm'         => 'Ndiyo, kwa kweli nataka kufunga hifadhidata.',
'unlockconfirm'       => 'Ndiyo, kwa kweli nataka kufungua hifadhidata.',
'lockbtn'             => 'Funga hifadhidata',
'unlockbtn'           => 'Fungua hifadhidata',
'locknoconfirm'       => 'Hujaweka alama katika sanduku la kuitika kitendo.',
'lockdbsuccesssub'    => 'Kufunga hifadhidata kumefaulu',
'unlockdbsuccesssub'  => 'Kufungua hifadhidata kumefaulu',
'lockdbsuccesstext'   => 'Hifadhidata imefungwa.<br />
Kumbuka [[Special:UnlockDB|kuifungua tena]] baada ya kumaliza kuitengeneza.',
'unlockdbsuccesstext' => 'Hifadhidata imefunguliwa.',
'databasenotlocked'   => 'Hifadhidata haijafunguliwa.',
'lockedbyandtime'     => '(kwa $1 saa $3 tarehe $2)',

# Move page
'move-page'                    => 'Hamisha $1',
'move-page-legend'             => 'Kuhamisha ukurasa',
'movepagetext'                 => "Tumia fomu hapo chini ili kubadilisha jina la ukurasa, pamoja na kuhamisha historia yake yote katika jina jipya lile lile.
Jina la awali litahamishwa na kuelekezwa kwa ukurasa wa jina jipya.
Unaweza kurekebisha maelekezo yanayokwenda kwenye ukurasa wa zamani kwa kujiendesha.
Usipotaka marekebisho yafanyike kwa kujiendesha, kumbuka kutafutia maelekezo [[Special:DoubleRedirects|mawilimawili]] au maelezo [[Special:BrokenRedirects|yenye hitilafu]].
Wewe mwenyewe una madaraka kuhakikisha kwamba viungo viendelee kuelekea vinapolengwa.

Uwe mwangalifu kwamba ukurasa '''hautahamishwa''' kama tayari kuna ukurasa wenye jina jipya, isipokuwa wakati ukurasa mpya ni tupu au ni elekezo, na hauna historia ya kuhaririwa.
Yaani unaweza kurudisha ukurasa kwenye jina la awali ukikosea, na haiwezekani kufuta ukurasa mwingine kwa kuchukua nafasi yake.

'''ILANI!'''
Kuhamisha ukurasa wenye wasomaji wengi kunaweza kuathirika watumiaji wetu.
Tafadhali hakikisha kwamba unaelewa matokeo ya kitendo hiki kabla ya kuendelea.",
'movepagetalktext'             => "Ukurasa wa majadiliano wa ukurasa huu utasogezwa pamoja yake
'''ila:'''
*tayari kuna ukurasa wa majadiliano (usiyo tupu) kwenye jina jipya, au
*ukifuta tiki katika kisanduku hapa chini.

Kama tayari kuna ukurasa au ukifuta tiki, itabidi usogeze au uunganishe ukurasa kwa mkono ukitaka.",
'movearticle'                  => "Ukurasa wa majadiliano wa ukurasa huu utasogezwa pamoja yake '''ila:'''
*tayari kuna ukurasa wa majadiliano (usiyo tupu) kwenye jina jipya, au
*ukifuta tiki katika kisanduku hapa chini.

Kama tayari kuna ukurasa au ukifuta tiki, itabidi usogeze au uunganishe ukurasa kwa mkono ukitaka.",
'moveuserpage-warning'         => "'''Ilani:''' Unatarajia kuhamisha ukurasa wa mtumiaji. Tafadhali kumbuka kwamba ni ukurasa tu utakaohamishwa; jina la mtumiaji ''haitabadilishwa''.",
'movenologin'                  => 'Hujaingia',
'movenologintext'              => 'Lazima uwe mtumiaji uliyesajiliwa na [[Special:UserLogin|uliyeingizwa]] ili uhamishe ukurasa.',
'movenotallowed'               => 'Huna ruhusa ya kuhamisha kurasa.',
'movenotallowedfile'           => 'Huna ruhusa ya kuhamisha mafaili.',
'cant-move-user-page'          => 'Huna fursa ya kuhamisha ukurasa wa mtumiaji (isopokuwa kurasa ndogo).',
'cant-move-to-user-page'       => 'Huna ruhusa ya kuhamisha ukurasa katika ukurasa wa mtumiaji (isipokuwa katika ukurasa mdogo wa mtumiaji).',
'newtitle'                     => 'Kuelekeza jina jipya:',
'move-watch'                   => 'Fuatilia ukurasa huu',
'movepagebtn'                  => 'Hamisha ukurasa',
'pagemovedsub'                 => 'Umefaulu kuhamisha ukurasa',
'movepage-moved'               => '\'\'\'"$1" imesogezwa kwenye "$2"\'\'\'',
'movepage-moved-redirect'      => 'Elekezo limetengenezwa.',
'articleexists'                => 'Tayari kuna ukurasa wenye jina hilo, au
jina ulilochagua ni batilifu.
Chagua jina lengine.',
'talkexists'                   => "'''Ukurasa wenyewe ulisogezwa salama, lakini ukurasa wake wa majadiliano haujasogezwa kwa sababu tayari kuna ukurasa wenye jina lake.  Tafadhali ziunganishe kwa mkono.'''",
'movedto'                      => 'imesogezwa hadi',
'movetalk'                     => 'Hamisha ukurasa wake wa majadiliano',
'move-subpages'                => 'Hamisha kurasa ndogo (hadi $1)',
'move-talk-subpages'           => 'Hamisha kurasa ndogo za ukurasa wa majadiliano (hadi $1)',
'movepage-page-moved'          => 'Ukurasa wa $1 umehamishwa hadi $2.',
'movepage-page-unmoved'        => 'Ukurasa wa $1 hakuweza kuhamishwa hadi $2.',
'movelogpage'                  => 'Kumbukumbu ya uhamiaji',
'movelogpagetext'              => 'Hapo chini panaorodheshwa kurasa zote zilizohamishwa.',
'movesubpage'                  => '{{PLURAL:$1|Ukurasa mdogo|Kurasa ndogo}}',
'movesubpagetext'              => 'Ukurasa huu una {{PLURAL:$1|ukurasa mdogo unao|kurasa ndogo $1 zinazo}}onyeshwa hapo.',
'movenosubpage'                => 'Ukurasa huu hauna kurasa ndogo.',
'movereason'                   => 'Sababu:',
'revertmove'                   => 'rejesha',
'delete_and_move'              => 'Kufuta na kuhamisha',
'delete_and_move_confirm'      => 'Ndiyo, ukurasa ufutwe',
'immobile-source-namespace'    => 'Kurasa haziwezi kuhamisha ndani ya eneo la wiki la "$1"',
'immobile-target-namespace'    => 'Kurasa haziwezi kuhamishwa zifike eneo la wiki la "$1"',
'immobile-target-namespace-iw' => 'Kiungo kinachoelekea wiki nyingine hakiwezi kuwa ukurasa wa mwishilio wa kuhamisha ukurasa.',
'immobile-source-page'         => 'Ukurasa huu hauwezi kuhamishwa.',
'immobile-target-page'         => 'Kuhamisha katika jina hilo la mwishilio haiwezikani.',
'imagenocrossnamespace'        => 'Faili linaweza kuhamishwa katika eneo la wiki la faili tu.',
'nonfile-cannot-move-to-file'  => 'Ni mafaili tu yanayoweza kuhamishwa katika eneo la faili',
'imageinvalidfilename'         => 'Jina la faili la mwishilio ni batili',
'fix-double-redirects'         => 'Sasisha maelekezo yanayoelekeza jina la awali la faili',
'move-leave-redirect'          => 'Weka elekezo mahali pake',
'protectedpagemovewarning'     => "'''Ilani:''' Ukurasa huu umelindwa, kwa hiyo ni watumiaji wenye wezo za wakabidhi tu wanaoweza  kuuhamisha.
Kumbukumbu ya mwisho inaandikwa chini:",
'semiprotectedpagemovewarning' => "'''Ilani:''' Ukurasa huu umelindwa, kwa hiyo ni watumiaji waliosajiliwa tu wanaoweza  kuuhamisha.
Kumbukumbu ya mwisho inaandikwa chini:",
'move-over-sharedrepo'         => '== Faili linapatikana ==
[[:$1]] lipo katika hifadhi ya pamoja. Ukihamisha faili kwa jina hili litapuuza faili la hifadhi ya pamoja.',
'file-exists-sharedrepo'       => 'Jina la faili ulilolichagua linatumiwa tayari katika hifadhi ya pamoja.
Tafadhali chagua jina lingine.',

# Export
'export'            => 'Peleka kurasa',
'exporttext'        => 'Unaweza kupeleka nakala za maandishi ya kurasa fulani, pamoja na mapitio yao, kwa kuiviringishia na XML.
Nakala hizi zinaweza kuletwa katika wiki nyingine ya MediaWiki kwa kupitia [[Special:Import|ukurasa wa kuleta]].

Kupeleka kurasa, andika majina yao katika sanduku chini, jina moja kwa kila mstari. Chagua kupeleka ama haririo ya kisasa pamoja na mapitio yote ya awali na maelezo ya historia, ama haririo ya kisasa pamoja na maelezo ya haririo la mwisho tu.

Ukipeleka haririo ya kisasa tu, unaweza kutumia kiungo kinachokwenda ukurasa wa chanzo, kwa mfano [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] kwa ajili ya ukurasa wa "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Peleka haririo la kisasa tu, bila mapitio ya awali',
'exportnohistory'   => "----
'''Ilani:''' Kupeleka mapitio yote ya kurasa kwa kutumia fomu hii kumesimamishwa ili utendaji wa tovuti isiathiriwe.",
'export-submit'     => 'Peleka',
'export-addcattext' => 'Ongeza kurasa kutoka jamii:',
'export-addcat'     => 'Ongeza',
'export-addnstext'  => 'Ongeza kurasa kutoka eneo la wiki:',
'export-addns'      => 'Ongeza',
'export-download'   => 'Hifadhi kama faili',
'export-templates'  => 'Peleka pamoja na vigezo vyote',
'export-pagelinks'  => 'Ni pamoja na kurasa zinazoungwa kwa kina cha:',

# Namespace 8 related
'allmessages'                   => 'Jumbe za mfumo',
'allmessagesname'               => 'Jina',
'allmessagesdefault'            => 'Ujumbe uliopo bidhaa pepe',
'allmessagescurrent'            => 'Ujumbe unapo sasa hivi',
'allmessagestext'               => 'Hii ni orodha ya jumbe za mfumo zilizopo katika eneo la MediaWiki.
Ukitaka kusaidia kazi ya kutohoa MediaWiki yote katika lugha nyingi, tafadhali uende tovuti ya [//www.mediawiki.org/wiki/Localisation Kutohoa MediaWiki Kwenye Lugha Nyingi] na [//translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB'     => "Ukurasa huu hauwezi kutumika kwa sababu '''\$wgUseDatabaseMessages''' imelemazwa.",
'allmessages-filter-legend'     => 'Chuja',
'allmessages-filter'            => 'Zichujwe kwa hali ya kutengenezwa:',
'allmessages-filter-unmodified' => 'Zisizotengenezwa',
'allmessages-filter-all'        => 'Zote',
'allmessages-filter-modified'   => 'Zilizotengenezwa',
'allmessages-prefix'            => 'Zichujwe kwa kiambishi awali:',
'allmessages-language'          => 'Lugha:',
'allmessages-filter-submit'     => 'Uende',

# Thumbnails
'thumbnail-more'          => 'Kuza',
'filemissing'             => 'Faili halipo',
'thumbnail_error'         => 'Hitilafu kutengeneza picha ndogo: $1',
'thumbnail_image-missing' => 'Faili halipatikani: $1',

# Special:Import
'import'                     => 'Kuleta kurasa',
'importinterwiki'            => 'Kuleta kutoka wiki nyingine',
'import-interwiki-text'      => 'Chagua wiki na jina la ukurasa unaotaka kuuleta.
Tarehe za mapitio na majina ya wahariri zitaletwa pia.
Vitendo vyote vya kuleta kutoka wiki nyingine vinaandikwa katika [[Special:Log/import|kumbukumbu za kuleta]].',
'import-interwiki-source'    => 'Wiki/ukurasa wa chanzo:',
'import-interwiki-history'   => 'Leta pamoja na mapitio yote ya ukurasa huu',
'import-interwiki-templates' => 'Leta pamoja na vigezo vyote',
'import-interwiki-submit'    => 'Leta',
'import-interwiki-namespace' => 'Eneo la wiki la mwishilio:',
'import-upload-filename'     => 'Jina la faili:',
'import-comment'             => 'Maelezo:',
'importtext'                 => 'Tafadhali upeleke faili kutoka wiki lake kwa kutumia [[Special:Export|zana ya kupeleka]].
Ulihafadhie katika tarakalishi yako, halafu ulipakie hapa.',
'importstart'                => 'Kurasa zinaletwa...',
'import-revision-count'      => '{{PLURAL:$1|pitio|mapitio}} $1',
'importnopages'              => 'Hakuna kurasa za kuleta.',
'imported-log-entries'       => 'Kumbukumbu $1 {{PLURAL:$1|ililetwa|zililetwa}}.',
'importfailed'               => 'Kuleta hakufaulu: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Aina ya kitu unacholeta haijulikani',
'importcantopen'             => 'Faili lililoletwa halikuweza kufunguliwa',
'importbadinterwiki'         => 'Kiungo kibovu kati za wiki',
'importnotext'               => 'Tupu au bila maandishi',
'importsuccess'              => 'Kuleta kumekamilishwa!',
'import-noarticle'           => 'Hakuna kurasa za kuleta!',
'import-token-mismatch'      => 'Data ya kipindi zilipotelewa.
Tafadhali jaribu tena.',

# Import log
'importlogpage'                 => 'Kumbukumbu ya kuleta',
'import-logentry-upload-detail' => '{{PLURAL:$1|pitio|mapitio}} $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ukurasa wako',
'tooltip-pt-anonuserpage'         => 'Ukurasa wa mtumiaji kwa ajili ya anwani wa IP unaoitumia kuhariri',
'tooltip-pt-mytalk'               => 'Majadiliano yako',
'tooltip-pt-anontalk'             => 'Majadiliano ya masahihisho yaliyofanikiwa kutoka kwa anwani huu wa IP',
'tooltip-pt-preferences'          => 'Mapendekezo yangu',
'tooltip-pt-watchlist'            => 'Orodha ya kurasa unazofuatilia kwa mabadiliko',
'tooltip-pt-mycontris'            => 'Orodha ya michango yako',
'tooltip-pt-login'                => 'Tunakushajiisha kuingia, lakini siyo lazima.',
'tooltip-pt-anonlogin'            => 'Tunakushajiisha kuingia, lakini siyo lazima',
'tooltip-pt-logout'               => 'Toka',
'tooltip-ca-talk'                 => 'Mazungumzo kuhusu makala',
'tooltip-ca-edit'                 => 'Unaweza kuhariri ukurasa huu.  Tafadhali tumia kitufe cha kuhakikisha kabla ya kuhifadhi.',
'tooltip-ca-addsection'           => 'Anzisha fungu jipya.',
'tooltip-ca-viewsource'           => 'Ukurasa huu umelindwa.  Unaweza kutazama chanzo chake.',
'tooltip-ca-history'              => 'Mapitio ya awali ya ukurasa huu',
'tooltip-ca-protect'              => 'Linda ukurasa huu',
'tooltip-ca-unprotect'            => 'Kuondoa tunzo la ukurasa',
'tooltip-ca-delete'               => 'Futa ukurasa huu',
'tooltip-ca-undelete'             => 'Rudisha masahihisho yaliyofanyiwa katika ukurasa huu kabla haujafutwa',
'tooltip-ca-move'                 => 'Kuhamisha ukurasa huu',
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
'tooltip-t-permalink'             => 'Kiungo cha daima cha kufikisha pitio hili la ukurasa',
'tooltip-ca-nstab-main'           => 'Onyesha kurasa zilizopo',
'tooltip-ca-nstab-user'           => 'Tazama ukurasa wa mtumiaji',
'tooltip-ca-nstab-media'          => 'Kutazama ukurasa wa picha, video au sauti',
'tooltip-ca-nstab-special'        => 'Huu ni ukurasa maalum ambao hauwezi kuhaririwa',
'tooltip-ca-nstab-project'        => 'Tazama ukurasa wa mradi',
'tooltip-ca-nstab-image'          => 'Angalia ukurasa wa faili',
'tooltip-ca-nstab-mediawiki'      => 'Tazama ujumbe wa mfumo',
'tooltip-ca-nstab-template'       => 'Tazama kigezo',
'tooltip-ca-nstab-help'           => 'Tazama ukurasa wa msaada',
'tooltip-ca-nstab-category'       => 'Tazama ukurasa wa jamii',
'tooltip-minoredit'               => 'Tia alama kwamba hii ni badiliko dogo',
'tooltip-save'                    => 'Hifadhi mabadiliko yako',
'tooltip-preview'                 => 'Hakikisha mabadiliko yako, tafadhali fanya kabla ya kuhifadhi!',
'tooltip-diff'                    => 'Onyesha mabadiliko uliyofanya kwenye maandishi.',
'tooltip-compareselectedversions' => 'Tazama tofauti baina ya mapitio mawili uliochagua ya ukurasa huu.',
'tooltip-watch'                   => 'Fuatilia ukurasa huu kwenye maangalizi yako',
'tooltip-recreate'                => 'Kuanzisha ukurasa upya ingawa umekuwa umefutwa',
'tooltip-upload'                  => 'Kuanza kupakia',
'tooltip-rollback'                => '"Rejesha" inarejesha (ma)sahihisho ya ukurasa huu yaliyofanyika na yule aliyeuhariri mwishoni, kwa kubofya mara moja tu.',
'tooltip-undo'                    => 'Ukibonyeza "tengua" sahihisho hili litarejeshwa na hakiki yake itaonekana pamoja na dirisha la kuhariri, ili uweze kuandika sababu na maelezo kwenye muhtasari.',
'tooltip-preferences-save'        => 'Uhifadhi mapendekezo',
'tooltip-summary'                 => 'Andika muhtasari mfupi',

# Attribution
'anonymous'        => '{{PLURAL:$1|mtumiaji bila jina|watumiaji bila majina}} wa {{SITENAME}}',
'siteuser'         => 'mtumiaji $1 wa {{SITENAME}}',
'anonuser'         => 'Mtumiaji wa {{SITENAME}} asiyekuwa na jina $1',
'lastmodifiedatby' => 'Ukurasa huu umebadilishwa kwa mara ya mwisho saa $2, tarehe $1 na $3.',
'othercontribs'    => 'Ukurasa uliandikwa pia na $1.',
'others'           => 'wengine',
'siteusers'        => '{{PLURAL:$2|mtumiaji|watumiaji}} wa {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|Mtumiaji|Watumiaji}} wa {{SITENAME}} {{PLURAL:$2|asiyetiwa jina|wasiotiwa jina}} $1',
'creditspage'      => 'Wandishi wa ukurasa',
'nocredits'        => 'Taarifa kuhusu wandishi wa ukurasa huu haipatikana.',

# Info page
'pageinfo-title'            => 'Taarifa juu ya "$1"',
'pageinfo-header-edits'     => 'Maharirio',
'pageinfo-header-watchlist' => 'Maangalizi',
'pageinfo-subjectpage'      => 'Ukurasa',
'pageinfo-talkpage'         => 'Ukurasa wa majadiliano',
'pageinfo-watchers'         => 'Idadi ya wanaofuatilia',
'pageinfo-edits'            => 'Idadi ya haririo',

# Image deletion
'deletedrevision'                 => 'Pitio la awali lililofutwa $1',
'filedeleteerror-short'           => 'Hitilafu wakati wa kufuta faili: $1',
'filedelete-missing'              => 'Faili "$1" haliwezi kufutwa, kwa sababu halipo.',
'filedelete-old-unregistered'     => 'The specified file revision "$1" is not in the database.',
'filedelete-current-unregistered' => 'Faili lilotajwa la "$1" halipo katika hifadhidata.',

# Browsing diffs
'previousdiff' => '← Badilisho lililopita',
'nextdiff'     => 'Badilisho lijalo →',

# Media information
'mediawarning'           => 'Ilani: Huenda faili hili lina msimbo mbaya.
Ukilitekeleza faili, mashine yako huenda ikawa matatani.',
'imagemaxsize'           => "Kikomo cha ukubwa wa picha:<br />''(cha kurasa za maelezo ya mafaili)''",
'thumbsize'              => 'Ukubwa wa picha ndogo:',
'widthheightpage'        => '$1×$2, {{PLURAL:$3|ukurasa|kurasa}} $3',
'file-info'              => 'ukubwa wa faili: $1, aina ya MIME: $2',
'file-info-size'         => 'piseli $1 × $2, saizi ya faili: $3, aina ya MIME: $4',
'file-info-size-pages'   => 'Piseli $1 × $2, ukubwa wa faili: $3, aina ya MIME: $4, {{PLURAL:$5|ukurasa|kurasa}} $5',
'file-nohires'           => 'Hakuna saizi kubwa zaidi.',
'svg-long-desc'          => 'faili la SVG, husemwa kuwa piseli $1 × $2, saizi ya faili: $3',
'show-big-image'         => 'Ukubwa wa awali',
'show-big-image-preview' => 'Ukubwa wa hakikisho: $1.',
'show-big-image-other'   => '{{PLURAL:$2|Ukubwa mwingine|Ukubwa zingine}}: $1.',
'show-big-image-size'    => 'piseli $1 × $2',
'file-info-png-repeat'   => 'inachezwa {{PLURAL:$1|mara}} $1',

# Special:NewFiles
'newimages'             => 'Mkusanyiko wa mafaili mapya',
'imagelisttext'         => "Orodha iliyopo chini inataja {{PLURAL:$1|faili '''$1''' lililopangwa|mafaili '''$1''' yaliyopangwa}} $2.",
'newimages-summary'     => 'Ukurasa maalum huu unaonyesha mafaili yaliyopakiwa hivi karibuni.',
'newimages-legend'      => 'Chuja',
'newimages-label'       => 'Jina la faili (au sehemu yake):',
'showhidebots'          => '(roboti $1)',
'noimages'              => 'Hakuna picha.',
'ilsubmit'              => 'Tafuta',
'bydate'                => 'kwa tarehe',
'sp-newimages-showfrom' => 'Onyesha mafaili mapya kuanzia saa $2, tarehe $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => 'sekunde {{PLURAL:$1|$1}}',
'minutes' => 'dakika {{PLURAL:$1|$1}}',
'hours'   => '{{PLURAL:$1|saa $1|masaa $1}}',
'days'    => 'siku {{PLURAL:$1|$1}}',
'ago'     => '$1 zilizopita',

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
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'Upana',
'exif-imagelength'                 => 'Urefu',
'exif-jpeginterchangeformatlength' => 'Idadi ya baiti za data ya JPEG',
'exif-datetime'                    => 'Tarehe na saa ya kubadilisha faili',
'exif-imagedescription'            => 'Jina la picha',
'exif-make'                        => 'Mtengenezaji wa kamera',
'exif-model'                       => 'Aina ya<!--Mtindo wa--> kamera',
'exif-software'                    => 'Bidhaa pepe inayotumika',
'exif-artist'                      => 'Mwandishi',
'exif-copyright'                   => 'Mwenye hatimiliki',
'exif-exifversion'                 => 'Mtindo wa Exif',
'exif-componentsconfiguration'     => 'Maana ya kila kijenzi',
'exif-pixelydimension'             => 'Upana wa picha',
'exif-pixelxdimension'             => 'Urefu wa picha',
'exif-usercomment'                 => 'Maoni ya mtumiaji',
'exif-relatedsoundfile'            => 'Faili la sauti linalohusika',
'exif-lightsource'                 => 'Mwanga',
'exif-flash'                       => 'Taa ya picha',
'exif-flashenergy'                 => 'Nguvu ya taa ya picha',
'exif-gpslatituderef'              => 'Latitudo kwenda kaskazini au kusini',
'exif-gpslatitude'                 => 'Latitudo',
'exif-gpslongituderef'             => 'Longitudo kwenda mashariki au magharibi',
'exif-gpslongitude'                => 'Longitudo',
'exif-gpsaltituderef'              => 'Rejeo ya mwinuko',
'exif-gpsaltitude'                 => 'Mwinuko',
'exif-gpsspeedref'                 => 'Kizio cha kupima mwendo',
'exif-gpsspeed'                    => 'Mwendo wa sanduku la GPS',
'exif-gpsdestlatitude'             => 'Latitudo ya kikomo',
'exif-gpsdestlongitude'            => 'Longitudo ya kikomo',
'exif-gpsdestdistance'             => 'Mbali wa kikomo',
'exif-gpsareainformation'          => 'Jina la eneo la GPS',
'exif-gpsdatestamp'                => 'Tarehe ya GPS',
'exif-jpegfilecomment'             => 'Maoni juu ya faili la JPEG',
'exif-keywords'                    => 'Maneno yahusika',
'exif-worldregioncreated'          => 'Eneo la dunia palipopigwa picha',
'exif-countrycreated'              => 'Nchi palipopigwa picha',
'exif-countrycodecreated'          => 'Kodi ya nchi picha palipopigwa',
'exif-countrydest'                 => 'Nchi inayoonyeshwa',
'exif-objectname'                  => 'Jina fupi',
'exif-specialinstructions'         => 'Maelekezo maalum',
'exif-headline'                    => 'Kichwa cha habari',
'exif-urgency'                     => 'Umuhimu',
'exif-writer'                      => 'Mwandishi',
'exif-languagecode'                => 'Lugha',
'exif-iimcategory'                 => 'Jamii',
'exif-cameraownername'             => 'Mwenye kamera',
'exif-copyrighted'                 => 'Hali ya hakimiliki',
'exif-copyrightowner'              => 'Mwenye hatimiliki',
'exif-pngfilecomment'              => 'Maoni juu ya faili la PNG',
'exif-giffilecomment'              => 'Maoni juu ya faili la GIF',
'exif-personinimage'               => 'Mtu aliyepigwa picha',

'exif-unknowndate' => 'Tarehe haijulikani',

'exif-orientation-1' => 'Kawaida',

'exif-componentsconfiguration-0' => 'haipo',

'exif-exposureprogram-1' => 'Kwa mikono',
'exif-exposureprogram-2' => 'Programu ya kawaida',

'exif-subjectdistance-value' => 'mita $1',

'exif-meteringmode-0'   => 'Haijulikani',
'exif-meteringmode-1'   => 'Wastani',
'exif-meteringmode-3'   => 'Ibura',
'exif-meteringmode-4'   => 'IburaMengi',
'exif-meteringmode-5'   => 'Rembo',
'exif-meteringmode-6'   => 'Ya sehemu',
'exif-meteringmode-255' => 'Nyingine',

'exif-lightsource-0'   => 'Haijulikani',
'exif-lightsource-1'   => 'Jua',
'exif-lightsource-2'   => 'Taa ya kuakisi mwanga',
'exif-lightsource-4'   => 'Taa ya picha',
'exif-lightsource-9'   => 'Mwangaza wa mchana',
'exif-lightsource-10'  => 'Mawingu',
'exif-lightsource-11'  => 'Kivuli',
'exif-lightsource-255' => 'Mwingine',

'exif-focalplaneresolutionunit-2' => 'inchi',

'exif-scenecapturetype-0' => 'Kawaida',
'exif-scenecapturetype-1' => 'Mandhari',
'exif-scenecapturetype-2' => 'Watu',
'exif-scenecapturetype-3' => 'Usiku',

'exif-gaincontrol-0' => 'Bila',

'exif-contrast-0' => 'Kawaida',
'exif-contrast-1' => 'Laini',
'exif-contrast-2' => 'Gumu',

'exif-saturation-0' => 'Kawaida',

'exif-sharpness-0' => 'Kawaida',
'exif-sharpness-1' => 'Laini',
'exif-sharpness-2' => 'Gumu',

'exif-subjectdistancerange-0' => 'Haujulikani',
'exif-subjectdistancerange-1' => 'Mandhari ya karibu mno',
'exif-subjectdistancerange-2' => 'Mandhari ya karibu',
'exif-subjectdistancerange-3' => 'Mandhari ya mbali',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitudo ya kaskazini',
'exif-gpslatitude-s' => 'Latitudo ya kusini',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitudo kwenda mashariki',
'exif-gpslongitude-w' => 'Longitudo kwenda magharibi',

'exif-gpsstatus-a' => 'Kipimo kinaendelea',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometa kwa saa',
'exif-gpsspeed-m' => 'Maili kwa saa',
'exif-gpsspeed-n' => 'Noti',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilomita',
'exif-gpsdestdistance-m' => 'Maili',
'exif-gpsdestdistance-n' => 'Maili ya baharia',

'exif-gpsdop-excellent' => 'Nzuri sana ($1)',
'exif-gpsdop-good'      => 'Nzuri ($1)',
'exif-gpsdop-moderate'  => 'Nzuri kiasi ($)',
'exif-gpsdop-fair'      => 'Nzuri kidogo ($1)',
'exif-gpsdop-poor'      => ' Si nzuri ($1)',

'exif-objectcycle-a' => 'Asubuhi tu',
'exif-objectcycle-p' => 'Jioni tu',
'exif-objectcycle-b' => 'Asubuhi na jioni zote mbili',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Mwelekeo halisi',
'exif-gpsdirection-m' => 'Mwelekeo wa sumaku',

'exif-dc-contributor' => 'Wengine waliochangia',
'exif-dc-date'        => 'Tarehe',
'exif-dc-publisher'   => 'Mchapishaji',
'exif-dc-rights'      => 'Haki',

'exif-rating-rejected' => 'Lilikataliwa',

'exif-isospeedratings-overflow' => 'Zaidi na 65535',

'exif-iimcategory-ace' => 'Sanaa, utamaduni na burudani',
'exif-iimcategory-clj' => 'Uhalifu na sheria',
'exif-iimcategory-dis' => 'Maafa na ajali',
'exif-iimcategory-fin' => 'Uchumi na biashara',
'exif-iimcategory-edu' => 'Elimu',
'exif-iimcategory-evn' => 'Mazingira',
'exif-iimcategory-hth' => 'Afya',
'exif-iimcategory-hum' => 'Maslahi ya binadamu',
'exif-iimcategory-lab' => 'Kazi',
'exif-iimcategory-lif' => 'Mtindo wa maisha na burudani',
'exif-iimcategory-pol' => 'Siasa',
'exif-iimcategory-rel' => 'Dini na imani',
'exif-iimcategory-sci' => 'Sayansi na teknolojia',
'exif-iimcategory-spo' => 'Michezo',
'exif-iimcategory-war' => 'Vita, migogoro na vurugu',
'exif-iimcategory-wea' => 'Hali ya hewa',

'exif-urgency-normal' => 'Kawaida ($1)',
'exif-urgency-low'    => 'Chini ($1)',
'exif-urgency-high'   => 'Juu ($1)',
'exif-urgency-other'  => 'Upaumbele uliotajwa na mtumiaji ($1)',

# External editor support
'edit-externally'      => 'Tumia programu ya nje kuhariri faili hii',
'edit-externally-help' => '(Ona [//www.mediawiki.org/wiki/Manual:External_editors maelezo (kwa Kiingereza)] kwa maarifa mengine.)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'zote',
'namespacesall' => 'zote',
'monthsall'     => 'yote',
'limitall'      => 'zote',

# E-mail address confirmation
'confirmemail'              => 'Kuyakinisha anwani ya barua pepe',
'confirmemail_noemail'      => 'Hakuna anwani ya barua pepe halali kwenye [[Special:Preferences|mapendekezo yako]].',
'confirmemail_text'         => '{{SITENAME}} inakutakia uyakinishe anwani yako ya barua pepe kabla kutumia zana zinazohusika barua pepe.
Bofya kibonyezi cha chini kuituma barua pepe ya kuyakinisha kwa anwani yako.
Ndani ya barua pepe patakuwa na kiungo chenye ishara;
tumia kiungo kwenye kivinjari chako ili kuyakinisha kwamba anwani yako ya barua pepe ni halali.',
'confirmemail_pending'      => 'Ishara za kuidhinisha zimeshatumwa kwako kwa njia ya barua pepe;
ikiwa umeanzisha akaunti yako sasa hivi tu, tafadhali subiri dakika chache zifike, kabla hujaribu kuomba ishara zingine.',
'confirmemail_send'         => 'Nitumie ishara za kuyakinisha',
'confirmemail_sent'         => 'Barua pepe ya kuyakinisha imetumwa.',
'confirmemail_oncreate'     => 'Ishara za kuyakinisha zilitumwa kwa anwani yako ya barua pepe.
Huhitaji ishara hizi ili kuingia akaunti yako, lakini utazihitaji ili kuwezesha zana zozote za wiki hii zinazotumia barua pepe.',
'confirmemail_sendfailed'   => '{{SITENAME}} haikufaulu kutuma barua pepe ya kuyakinisha kwako.
Tafadhali uhakikishe kwamba hakuna ishara batili katika anwani yako ya barua pepe.

Huduma ya barua pepe inasema: $1',
'confirmemail_invalid'      => 'Ishara za kuyakinisha ni batili.
Huenda zimepitwa na wakati.',
'confirmemail_needlogin'    => 'Unahitajika $1 kuthibitisha anwani ya barua pepe yako.',
'confirmemail_success'      => 'Barua pepe yako imethibitishwa.
Sasa unaweza [[Special:UserLogin|kuingia]] na kuifurahia {{SITENAME}}.',
'confirmemail_loggedin'     => 'Anwani ya barua pepe yako imethibishwa sasa.',
'confirmemail_error'        => 'Kuna mambo yameenda kombo hifadhi ukamilisho wako.',
'confirmemail_subject'      => 'Barua pepe ya uthibitisho ya {{SITENAME}}',
'confirmemail_body'         => 'Kuna mtu, huenda ikawa wewe, kutoka anwani ya IP $1, amesajili akaunti "$2" na anwani ya barua pepe hii kwenye {{SITENAME}}.

Kuthibitisha ya kwamba akaunti hii inamilikiwa na wewe, unatakiwa kuwezesha njia ya barua pepe kwenye  {{SITENAME}}, fungua kiungo hiki katika kivinjari chako:

$3

na kama *huja* sajili akaunti hii, fuata kiungo hiki ili kubatilisha uthibitisho wa anwani ya barua pepe:

$5

Kodi hizi za uthibitisho zitaishia mnamo $4.',
'confirmemail_body_changed' => 'Kuna mtu, huenda ikawa wewe, kutoka anwani ya IP $1, ambaye amebadilisha anwani ya barua pepe ya akaunti "$2" iwe anwani ya barua pepe hii, kule {{SITENAME}}.

Ili kuthibitisha ya kwamba akaunti hii inamilikiwa na wewe, pamoja na kuwezesha upya zana zinazotumia barua pepe kule {{SITENAME}}, ufungue kiungo hiki katika kivinjari chako:

$3

na kama *huja* sajili akaunti hii, fuata kiungo hiki ili kubatilisha uthibitisho wa anwani ya barua pepe:

$5

Ishara hizi za uthibitisho zitaishia mnamo $4.',
'confirmemail_body_set'     => 'Kuna mtu, huenda ikawa wewe, kutoka anwani ya IP $1, ambaye ameweka anwani ya barua pepe ya akaunti "$2" iwe anwani ya barua pepe hii, kule {{SITENAME}}.

Ili kuthibitisha ya kwamba akaunti hii inamilikiwa na wewe, pamoja na kuwezesha upya zana zinazotumia barua pepe kule {{SITENAME}}, ufungue kiungo hiki katika kivinjari chako:

$3

na kama *huja* sajili akaunti hii, fuata kiungo hiki ili kubatilisha uthibitisho wa anwani ya barua pepe:

$5

Ishara hizi za uthibitisho zitaishia mnamo $4.',
'confirmemail_invalidated'  => 'Uthibitisho wa barua pepe umebatilishwa.',
'invalidateemail'           => 'Batilisha barua pepe ya uthibitisho.',

# Scary transclusion
'scarytranscludefailed'  => '[Kuleta kigezo imeshindikana kwa ajili ya $1]',
'scarytranscludetoolong' => '[URL ni ndefu mno]',

# Trackbacks
'trackbackremove' => '([$1 Futa])',

# Delete conflict
'deletedwhileediting'      => "'''Ilani''': Ukurasa huu ulifutwa ulipokwisha kuanza huuhariri!",
'confirmrecreate'          => "Mtumiaji [[User:$1|$1]] ([[User talk:$1|majadiliano]]) aliufuta ukurasa huu wakati umeshaanza kuuhariri, akaandika sababu hii ya kufuta:
: ''$2''
Tafadhali uthibitishe kwamba kweli unataka kuanzisha ukurasa huu upya.",
'confirmrecreate-noreason' => 'Mtumiaji [[User:$1|$1]] ([[User talk:$1|majadiliano]]) aliufuta ukurasa huu wakati umeshaanza kuuhariri. Tafadhali uthibitishe kwamba kweli unataka kuanzisha ukurasa huu upya.',
'recreate'                 => 'Anzisha upya',

# action=purge
'confirm_purge_button' => 'Sawa',
'confirm-purge-top'    => 'Ghili ya ukurasa huu ifutwe?',
'confirm-purge-bottom' => 'Unaposafisha ukurasa ghili yake inasafishwa na haririo la kisasa linaonekana.',

# action=watch/unwatch
'confirm-watch-button'   => 'Sawa',
'confirm-watch-top'      => 'Je, ukurasa huu uongezwe katika maangalizi yako?',
'confirm-unwatch-button' => 'Sawa',
'confirm-unwatch-top'    => 'Ukurasa huu uondolewa katika orodha ya maangalizi yako?',

# Multipage image navigation
'imgmultipageprev' => '← ukurasa uliotangulia',
'imgmultipagenext' => 'ukurasa ujao →',
'imgmultigo'       => 'Nenda!',
'imgmultigoto'     => 'Uende kwenye ukurasa wa $1',

# Table pager
'ascending_abbrev'         => 'pand',
'descending_abbrev'        => 'shuk',
'table_pager_next'         => 'Ukurasa ujao',
'table_pager_prev'         => 'Ukurasa uliotangulia',
'table_pager_first'        => 'Ukurasa wa kwanza',
'table_pager_last'         => 'Ukurasa wa mwisho',
'table_pager_limit'        => 'Ionyeshwe vitu $1 katika kila ukurasa',
'table_pager_limit_label'  => 'Vitu kwa ukurasa:',
'table_pager_limit_submit' => 'Nenda',
'table_pager_empty'        => 'Hakuna matokeo',

# Auto-summaries
'autosumm-blank'   => 'Kaondosha yaliyomo',
'autosumm-replace' => "Maandishi yaliyokuwepo yalichukuliwa nafasi na '$1'",
'autoredircomment' => 'Ukurasa umeelekezwa kwenda [[$1]]',
'autosumm-new'     => "Ukurasa ulianzishwa kwa kuandika '$1'",

# Live preview
'livepreview-loading' => 'Inapakizwa...',
'livepreview-ready'   => 'Inapakizwa... Tayari!',
'livepreview-failed'  => 'Hakikisho la kisasa hakufaulu!
Jaribu hakikisho la kawaida.',
'livepreview-error'   => 'Imeshindikana kuunganisha: $1 "$2".
Jaribu hakikisho la kawaida.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Huenda mabadiliko yaliyowekwa tangu sekunde $1 {{PLURAL:$1|iliyopita|zilizopita}} hayataonyeshwa katika orodha hii.',
'lag-warn-high'   => 'Kutokana na seva ya hifadhidata kukawia sana, huenda mabadiliko yaliyowekwa tangu sekunde $1 {{PLURAL:$1|iliyopita|zilizopita}} yanaweza yasioneshwe kwenye orodha hii.',

# Watchlist editor
'watchlistedit-numitems'       => 'Orodha ya maangalizi yako ina {{PLURAL:$1|ukurasa 1|kurasa $1}}, bila kuhesabu kurasa za majadiliano.',
'watchlistedit-noitems'        => 'Orodha ya maangalizi yako haina kitu.',
'watchlistedit-normal-title'   => 'Kuhariri orodha ya maangalizi',
'watchlistedit-normal-legend'  => 'Kuondoa majina kwenye orodha ya maangalizi',
'watchlistedit-normal-explain' => 'Majina kwenye orodha ya maangalizi yako yapo chini.
Ili kuondoa jina, weka alama katika kisanduku chake, na bonyeza "{{int:Watchlistedit-normal-submit}}".
Unaweza pia [[Special:EditWatchlist/raw|kuhariri orodha ya ghafi]].',
'watchlistedit-normal-submit'  => 'Ondoa majina',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Jina 1 iliondolewa|Majina $1 yaliondolewa}} kutoka kwa orodha yako ya maangalizi:',
'watchlistedit-raw-title'      => 'Kuhariri maangalizi ghafi',
'watchlistedit-raw-legend'     => 'Kuhariri maangalizi ghafi',
'watchlistedit-raw-explain'    => 'Majina ya kwenye ukurasa wako wa maangalizi yanaonekana hapo chini, na yanaweza kuharirika kwa kuongezea au hata kuondoa katika orodha; na liwe jina moja kwa mstari.
Ukimaliza, bonyeza "{{int:Watchlistedit-raw-submit}}".
Pia unaweza [[Special:EditWatchlist|kutumia kihariri cha kawaida]].',
'watchlistedit-raw-titles'     => 'Majina:',
'watchlistedit-raw-submit'     => 'Sasisha orodha ya maangalizi',
'watchlistedit-raw-done'       => 'Orodha yako ya maangalizi imesasishwa.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Jina 1 liliongezwa|Majina $1 yaliongezwa}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Jina 1 liliondolewa|Majina $1 yaliondolewa}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Tazama mabadiliko yanayohusiana',
'watchlisttools-edit' => 'Tazama na hariri maangalizi',
'watchlisttools-raw'  => 'Hariri maangalizi ghafi',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|majadiliano]])',

# Core parser functions
'duplicate-defaultsort' => '!\'\'\'Ilani:\'\'\' Neno msingi la kupanga "$2" linafunika neno msingi la kupanga la awali "$1".',

# Special:Version
'version'                   => 'Toleo',
'version-specialpages'      => 'Kurasa maalum',
'version-skins'             => 'Maumbo',
'version-other'             => 'Zingine',
'version-version'           => '(Toleo $1)',
'version-license'           => 'Ruhusa',
'version-poweredby-credits' => "Wiki hii inaendeshwa na bidhaa pepe ya '''[//www.mediawiki.org/ MediaWiki]''', hakimiliki © 2001-$1 $2.",
'version-poweredby-others'  => 'wengine',
'version-license-info'      => 'MediaWiki ni bidhaa pepe huru; unaweza kuisambaza pamoja na kuitumia na kuibadilisha kutokana na masharti ya leseni ya GNU General Public License inayotolewa na Free Software Foundation (Shirika la Bidhaa Pepe Huru); ama toleo 2 la hakimiliki, ama (ukitaka) toleo lolote linalofuata.

MediaWiki inatolewa kwa matumaini ya kwamba ni ya manufaa, lakini BILA JUKUMU; hata bila jukumu linalojitokeza la KUWA TAYARI KUUZIKA KIBIASHARA au KUFAA KWA KUSUDIO FULANI. Tazama leseni ya GNU General Public License kuona maelezo mengine.

Huwa unapokea [{{SERVER}}{{SCRIPTPATH}}/COPYING nakala ya GNU General Public License] pamoja na programu hii; la sivyo, andika kuomba nakala kwa Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA au [//www.gnu.org/licenses/old-licenses/gpl-2.0.html uisome mkondoni].',
'version-software'          => 'Bidhaa pepe iliyosakinishwa',
'version-software-product'  => 'Bidhaa',
'version-software-version'  => 'Toleo',

# Special:FilePath
'filepath'         => 'Njia ya faili',
'filepath-page'    => 'Faili:',
'filepath-submit'  => 'Nenda',
'filepath-summary' => 'Ukurasa huu maalumu unarejesha njia kamili ya faili. Picha inaonyeshwa ukubwa wote, faili za aina zingine zinaanza na programu zake zinazohusiana moja kwa moja.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Tafuta mafaili ya nakili',
'fileduplicatesearch-summary'   => 'Kutafuta mafaili ya nakili kwa kuzingatia thamani za reli.',
'fileduplicatesearch-legend'    => 'Kutafuta kifani',
'fileduplicatesearch-filename'  => 'Jina la faili:',
'fileduplicatesearch-submit'    => 'Tafuta',
'fileduplicatesearch-info'      => 'Piseli $1 × $2<br />Ukubwa wa faili: $3<br />Aina ya MIME: $4',
'fileduplicatesearch-result-1'  => 'Faili la "$1" halina kifani.',
'fileduplicatesearch-result-n'  => 'Faili la "$1" lina {{PLURAL:$2|kifani 1|vifani $2}}.',
'fileduplicatesearch-noresults' => 'Faili linaloitwa "$1" halikupatikana.',

# Special:SpecialPages
'specialpages'                   => 'Kurasa maalum',
'specialpages-note'              => '----
* Kurasa maalum za kawaida.
* <span class="mw-specialpagerestricted">Kurasa maalum zisizoonekana na wote.</span>
* <span class="mw-specialpagecached">Kurasa maalum zinazotoka "cache" (might be obsolete).</span>',
'specialpages-group-maintenance' => 'Ripoti za kurekebisha na kutunza kurasa',
'specialpages-group-other'       => 'Kurasa maalum zingine',
'specialpages-group-login'       => 'Ingia / sajili akaunti',
'specialpages-group-changes'     => 'Mabadiliko ya karibuni na kumbukumbu',
'specialpages-group-media'       => 'Ripoti za mafaili na kuyapakia',
'specialpages-group-users'       => 'Watumiaji na wezo zao',
'specialpages-group-highuse'     => 'Kurasa zinazotumika sana',
'specialpages-group-pages'       => 'Orodha za kurasa',
'specialpages-group-pagetools'   => 'Zana za kuushughulika ukurasa',
'specialpages-group-wiki'        => 'Zana na data za wiki',
'specialpages-group-redirects'   => 'Kurasa maalum za kuelekeza',

# Special:BlankPage
'blankpage'              => 'Ukurasa tupu',
'intentionallyblankpage' => 'Ukurasa huu umeachwa tupu kwa makusudi.',

# External image whitelist
'external_image_whitelist' => '#Acha mstari huu jinsi vile ulivyo<pre>
#Weka vipande vya uchanuzi wa kawaida (regex) (kipande kinachoingia kati ya // tu) hapo chini
#Vipande hivi vitaoanishwa na URL ya picha za nje (na kiungo cha moto)
#Vipande vinavyooanishwa vitaonekana kama picha, la sivyo, itaonyeshwa kiungo kinachokwenda katika picha tu
#Mistari inayoanza na # inachukuliwa kama maelezo tu
#Haita-tofautishwa kati ya herufi kubwa na ndogo

#Weka vipande vyote vya regex juu ya mstari huu. Acha mstari huu jinsi vile ulivyo</pre>',

# Special:Tags
'tag-filter'              => 'Chujio cha [[Special:Tags|tagi]]:',
'tag-filter-submit'       => 'Chuja',
'tags-title'              => 'Tagi',
'tags-description-header' => 'Maelezo kamili ya maana',
'tags-edit'               => 'hariri',
'tags-hitcount'           => '{{PLURAL:$1|badiliko|mabadiliko}} $1',

# Special:ComparePages
'comparepages'                => 'Linganisha kurasa',
'compare-selector'            => 'Kulinganisha mapitio',
'compare-page1'               => 'Ukurasa wa kwanza',
'compare-page2'               => 'Ukurasa wa pili',
'compare-rev1'                => 'Pitio la kwanza',
'compare-rev2'                => 'Pitio la pili',
'compare-submit'              => 'Linganisha',
'compare-invalid-title'       => 'Jina la ukurasa uliloliandika ni batili.',
'compare-title-not-exists'    => 'Jina la ukurasa ulilotaja halipatikani.',
'compare-revision-not-exists' => 'Pitio ulilotaja halipatikani.',

# Database error messages
'dberr-header'      => 'Wiki imekuta tatizo',
'dberr-problems'    => 'Kumradhi!
Tovuti hii inapata matatatizo wakati huu.',
'dberr-again'       => 'Jaribu tena baada ya kusubiri dakika chache.',
'dberr-info'        => '(Hamna mawasiliano na seva ya hifadhidata: $1)',
'dberr-usegoogle'   => 'Unaposubiri unaweza kujaribu kutafuta kwa kutumia Google.',
'dberr-outofdate'   => 'Elewa kwamba fahirisi yao ya yaliyomo katika tovuti hii inaweza kuwa imepitwa na wakati.',
'dberr-cachederror' => 'Ifuatayo ni nakala ya kache ya ukurasa uliyoombwa, na huenda isiwe ya sasa.',

# HTML forms
'htmlform-invalid-input'       => 'Kuna matatizo na baadhi ya maingizo yako',
'htmlform-select-badoption'    => 'Thamani ulioiandika si chaguo halali.',
'htmlform-int-invalid'         => 'Kitu ulichokiandika si namba kamili.',
'htmlform-float-invalid'       => 'Kitu ulichokiandika si namba.',
'htmlform-int-toolow'          => 'Namba uliyoiandika iko chini ya kiwango cha chini cha $1',
'htmlform-int-toohigh'         => 'Namba uliyoiandika iko juu ya kiwango cha juu cha $1',
'htmlform-required'            => 'Ni lazima kujaza kitu hapa',
'htmlform-submit'              => 'Wasilisha',
'htmlform-reset'               => 'Tengua mabadiliko',
'htmlform-selectorother-other' => 'Nyingine',

# New logging system
'logentry-delete-delete'            => '$1 {{GENDER:$2|alifuta}} ukurasa wa $3',
'logentry-delete-restore'           => '$1 {{GENDER:$2|alirudisha}} ukurasa wa $3',
'logentry-delete-event'             => '$1 {{GENDER:$2|alibadilisha}} hali ya kuonekana wazi ya {{PLURAL:$5|kumbukumbu $5}} katika $3: $4',
'logentry-delete-revision'          => '$1 {{GENDER:$2|alibadilisha}} hali ya kuonekana wazi ya {{PLURAL:$5|pitio 1|mapitio $5}} kwenye ukurasa wa $3: $4',
'logentry-delete-event-legacy'      => '$1 {{GENDER:$2|alibadilisha}} hali ya kuonekana wazi ya kumbukumbu za $3',
'logentry-delete-revision-legacy'   => '$1 {{GENDER:$2|alibadilisha}} hali ya kuonekana wazi ya mapitio ya ukurasa $3',
'logentry-suppress-delete'          => '$1 {{GENDER:$2|alificha}} ukurasa wa $3',
'logentry-suppress-event'           => '$1 {{GENDER:$2|alibadilisha}} kwa siri hali ya kuonekana wazi {{PLURAL:$5|kumbukumbu $5}} katika $3: $4',
'logentry-suppress-revision'        => '$1 {{GENDER:$2|alibadilisha}} kwa siri hali ya kuonekana wazi {{PLURAL:$5|kumbukumbu $5 ya|kumbukumbu $5 za}} ukurasa wa $3: $4',
'logentry-suppress-event-legacy'    => '$1 alibadilisha kwa siri hali ya kuonekana wazi ya kumbukumbu za $3',
'logentry-suppress-revision-legacy' => '$1 alibadilisha kwa siri hali ya kuonekana wazi mapitio ya ukurasa $3',
'revdelete-restricted'              => 'aliwazuia pia wakabidhi wasiyaone maelezo',
'revdelete-unrestricted'            => 'aliwarudishia wakabidhi uwezo wa kuona maelezo',
'logentry-move-move'                => '$1 {{GENDER:$2|alihamisha}} ukurasa wa $3 hadi $4',
'logentry-newusers-newusers'        => '$1 {{GENDER:$2|alianzisha}} akaunti ya mtumiaji',
'logentry-newusers-create'          => '$1 {{GENDER:$2|alianzisha}} akaunti ya mtumiaji',
'logentry-newusers-create2'         => '$1 {{GENDER:$2|alianzisha}} {{GENDER:$4|akaunti ya mtumiaji}} $3',
'newuserlog-byemail'                => 'neno la siri limetumwa kwa barua pepe',

);
