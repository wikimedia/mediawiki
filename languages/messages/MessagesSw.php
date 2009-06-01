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
	NS_PROJECT_TALK     => '$1_majadiliano',
	NS_FILE             => 'Picha',
	NS_FILE_TALK        => 'Majadiliano_faili',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_majadiliano',
	NS_TEMPLATE         => 'Kigezo',
	NS_TEMPLATE_TALK    => 'Kigezo_majadiliano',
	NS_HELP             => 'Msaada',
	NS_HELP_TALK        => 'Msaada_majadiliano',
	NS_CATEGORY         => 'Jamii',
	NS_CATEGORY_TALK    => 'Jamii_majadiliano',
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
'tog-extendwatchlist'         => 'Tanua orodha ya maangalizi ili kuonyesha mabadiliko yote yaliyofanyika',
'tog-usenewrc'                => 'Mabadiliko yaliyoongezeka hivi karibuni (JavaScript)',
'tog-numberheadings'          => 'Vichwa vya habari vijipange namba-vyenyewe',
'tog-showtoolbar'             => 'Onyesha mwambaa wa zana za kuhariria (JavaScript)',
'tog-editondblclick'          => 'Hariri ukurasa kwa kubonyeza mara mbili',
'tog-editsection'             => 'Wezesha sehemu ya kuandikia kwa kutumia viungo vya [hariri]',
'tog-editsectiononrightclick' => 'Wezesha sehemu ya kuandikia kwa kubonyeza kitufe cha kulia cha puku yako juu ya sehemu ya majina husika (JavaScript)',
'tog-showtoc'                 => 'Onyesha mistari ya yaliyomo (kwa kila kurasa iliyo na zaidi ya vichwa vya habari 3)',
'tog-rememberpassword'        => 'Kumbuka kuingia kwangu katika kompyuta hii',
'tog-editwidth'               => 'Sanduku la kuhariri liwe na upana mzima',
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
'tog-fancysig'                => 'Sahihi changa (bila kujiweka kiungo yenyewe)',
'tog-externaleditor'          => 'Tumia kiharirio cha nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye kompyuta yako',
'tog-externaldiff'            => 'Tumia diff za nje inaposhindikana (kwa wataalamu tu, inahitaji marekebisho maalum kwenye kompyuta yako)',
'tog-showjumplinks'           => 'Wezesha "ruka hadi" viungo vya mafikio',
'tog-uselivepreview'          => 'Tumia kihakikio cha papohapo (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Nishtue pale ninapoingiza muhtasari mtupu wa kuhariri',
'tog-watchlisthideown'        => 'Ficha kuhariri kwangu kwenye orodha ya maangalizi',
'tog-watchlisthidebots'       => 'Ficha uhariri wa vikaragosi kwenye orodha ya maangalizi',
'tog-watchlisthideminor'      => 'Ficha mabadliko madogo kwenye maangalizi',
'tog-watchlisthideliu'        => 'Ficha mabadiliko yaliyofanywa na watumiaji kwenye maangalilizi',
'tog-watchlisthideanons'      => 'Ficha mabadiliko yaliyofanywa na watumiaji wasiojisajili kwenye maangalilizi',
'tog-nolangconversion'        => 'Lemaza mabadiliko kadhaa',
'tog-ccmeonemails'            => 'Nitumie nakala ya barua pepe nitakazo tuma kwa watumiaji wengine',
'tog-diffonly'                => 'Usionyeshe yaliyomo kwenye ukurasa chini ya faili za diff',
'tog-showhiddencats'          => 'Onyesha jamii zilizofichwa',
'tog-noconvertlink'           => 'Lemaza kiungo cha jina la badiliko',
'tog-norollbackdiff'          => 'Ondoa faili za diff baada ya kufanyakazi ya kurejesha',

'underline-always'  => 'Muda wote',
'underline-never'   => 'Kamwe',
'underline-default' => 'Kivinjari mbadala',

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
'category-empty'                 => "''Jamii hii haina ukurasa, picha, video, wala sauti yoyote.''",
'hidden-categories'              => '{{PLURAL:$1|Jamii zilizofichwa|Jamii zilizofichwa}}',
'hidden-category-category'       => 'Jamii zilizofichwa', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Jamii hii ina kijamii hiki tu.|Jamii hii ina kijamii kifuatacho {{PLURAL:$1|kijamii|$1 vijamii}}, nje ya $2 jumla.}}',
'category-subcat-count-limited'  => 'Jamii hii ina {{PLURAL:$1|kijamii|$1 vijamii}} vifuatavyo.',
'category-article-count'         => '{{PLURAL:$2|Jamii hii ina makala zifuatazo tu.|Kurasa zifuatazo {{PLURAL:$1|ni|$1 kurasa za}} katika jamii hii, nje ya $2 jumla.}}',
'category-article-count-limited' => 'Kurasa zifuatazo {{PLURAL:$1|ni|$1 kurasa za}} katika jamii hii.',
'category-file-count'            => '{{PLURAL:$2|Jamii hii ina faili zifuatazo tu.|Faili zifuatazo {{PLURAL:$1|ni|$1faili za}} katika jamii hii, nje ya $2 jumla.}}',
'category-file-count-limited'    => 'Faili zifuatazo {{PLURAL:$1|file is|$1 ni faili za}} katika jamii hii.',
'listingcontinuesabbrev'         => 'endelea',

'about'          => 'Kuhusu',
'article'        => 'Makala',
'newwindow'      => '(Itafungua kwa dirisha jipya)',
'cancel'         => 'Batilisha',
'qbfind'         => 'Gundua',
'qbedit'         => 'Hariri',
'qbpageoptions'  => 'Ukurasa huu',
'qbmyoptions'    => 'Kurasa zangu',
'qbspecialpages' => 'Kurasa za pekee',
'moredotdotdot'  => 'Zaidi...',
'mypage'         => 'Ukurasa wangu',
'mytalk'         => 'Majadiliano yangu',
'anontalk'       => 'Majadiliano ya IP hii',
'navigation'     => 'Safari',
'and'            => '&#32;na',

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
'protect'           => 'Linda',
'protect_change'    => 'badilisha',
'protectthispage'   => 'Linda ukurasa huu',
'unprotect'         => 'Usilinde',
'unprotectthispage' => 'Ondoa tunzo la ukurasa',
'newpage'           => 'Ukurasa mpya',
'talkpage'          => 'Jadilia ukurasa huu',
'talkpagelinktext'  => 'Majadiliano',
'specialpage'       => 'Ukurasa maalumu',
'personaltools'     => 'Vifaa binafsi',
'postcomment'       => 'Fungu jipya',
'talk'              => 'Majadiliano',
'views'             => 'Mitazamo',
'toolbox'           => 'Vifaa',
'userpage'          => 'Ukurasa wa mtumiaji',
'imagepage'         => 'Tazama ukurasa wa faili',
'mediawikipage'     => 'Tazama ukurasa wa ujumbe',
'viewhelppage'      => 'Tazama ukurasa wa msaada',
'categorypage'      => 'Tazama ukurasa wa jamii',
'viewtalkpage'      => 'Tazama majadiliano',
'otherlanguages'    => 'Lugha nyingine',
'redirectedfrom'    => '(Elekezwa kutoka $1)',
'redirectpagesub'   => 'Elekeza ukurasa',
'lastmodifiedat'    => 'Ukurasa huu umebadilishwa kwa mara ya mwisho tarehe $1, saa $2.', # $1 date, $2 time
'protectedpage'     => 'Kurasa iliyolindwa',
'jumpto'            => 'Rukia:',
'jumptonavigation'  => 'urambazaji',
'jumptosearch'      => 'tafuta',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Kuhusu {{SITENAME}}',
'aboutpage'            => 'Project:Kuhusu',
'copyright'            => 'Yaliyomo yafuata $1.',
'copyrightpage'        => '{{ns:project}}:Hatimiliki',
'currentevents'        => 'Matukio ya hivi karibuni',
'currentevents-url'    => 'Project:Matukio ya hivi karibuni',
'disclaimers'          => 'Kanusho',
'disclaimerpage'       => 'Project:Kanusho kwa jumla',
'edithelp'             => 'Usaidizi kwa uhariri',
'edithelppage'         => 'Help:Usaidizi kwa uhariri',
'faq'                  => 'Maswali ya kawaida',
'helppage'             => 'Help:Yaliyomo',
'mainpage'             => 'Mwanzo',
'mainpage-description' => 'Mwanzo',
'portal'               => 'Jumuia',
'portal-url'           => 'Project:Jumuia',
'privacy'              => 'Sera ya faragha',
'privacypage'          => 'Project:Sera ya faragha',

'badaccess'        => 'Kuna hitilafu ya ruhusa',
'badaccess-groups' => 'Ombi uliloomba limefikia ukingoni mwa watumiaji wa {{PLURAL:$2|the group|one of the groups}}: $1.',

'ok'                  => 'Sawa',
'retrievedfrom'       => 'Rudishwa kutoka "$1"',
'youhavenewmessages'  => 'Una $1 ($2).',
'newmessageslink'     => 'ujumbe mpya',
'newmessagesdifflink' => 'badiliko la mwisho',
'editsection'         => 'hariri',
'editold'             => 'hariri',
'viewsourceold'       => 'view source',
'editlink'            => 'hariri',
'viewsourcelink'      => 'onyesha kodi za ukurasa',
'editsectionhint'     => 'Hariri kipande: $1',
'toc'                 => 'Yaliyomo',
'showtoc'             => 'fichua',
'hidetoc'             => 'ficha',
'site-rss-feed'       => '$1 tawanyiko la RSS',
'site-atom-feed'      => '$1 tawanyiko la Atom',
'page-rss-feed'       => '"$1" tawanyiko la RSS',
'red-link-title'      => '$1 (bado haujaandikwa)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Makala',
'nstab-user'      => 'Ukurasa wa mtumiaji',
'nstab-special'   => 'Ukurasa maalum',
'nstab-project'   => 'Ukurasa wa mradi',
'nstab-image'     => 'Faili',
'nstab-mediawiki' => 'Jumbe',
'nstab-template'  => 'Kigezo',
'nstab-help'      => 'Msaada',
'nstab-category'  => 'Jamii',

# General errors
'error'              => 'Kosa',
'badtitle'           => 'Jina halifai',
'badtitletext'       => 'Jina la ukurasa ulilotaka ni batilifu, tupu, au limeungwa vibaya na jina la lugha nyingine au Wiki nyingine.  Labda linazo herufi moja a zaidi ambazo hazitumiki katika majina.',
'viewsource'         => 'Onyesha kodi za ukurasa',
'viewsourcefor'      => 'kwa $1',
'protectedpagetext'  => 'Ukurasa huu umefungwa ili kuepuka uhariri.',
'viewsourcetext'     => 'Unaweza kutazama na kuiga chanzo cha ukurasa huu:',
'protectedinterface' => 'Ukurasa huu unatoa maelezo ya msingi ya bidhaa pepe, na pia umefungwa ili kuzuiya uharibifu.',
'editinginterface'   => "'''Ilani:''' Una hariri ukurasa unaotumika kutoa maelezo ya msingi ya bidhaa pepe.
Mabadiliko katika ukurasa huu yataathiri mwonekano mzima wa viungo vya watumiaji wengine.
Kwa lengo la kutaka kutafsiri, tafadhali fikiria kutumia  [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], kwa kuweka miradi ya MediaWiki kwa kienyeji.",
'sqlhidden'          => '(maulizo ya SQL yamefichwa)',

# Login and logout pages
'welcomecreation'           => '== Karibu, $1! ==
Ushafunguliwa akaunti yako tayari.
Usisahau kubadilisha mapendekezo yako ya [[Special:Preferences|{{SITENAME}}]].',
'loginpagetitle'            => 'Kuingia kwa watumiaji',
'yourname'                  => 'Jina la mtumiaji:',
'yourpassword'              => 'Nywila',
'yourpasswordagain'         => 'Andika tena neno la siri',
'remembermypassword'        => 'Nikumbuke katika tarakilishi hii',
'yourdomainname'            => 'Tovuti yako:',
'externaldberror'           => 'Huenda kulikuwa na hitilafu ya database au labda hauruhusiwi kubadilisha akaunti yako ya nje.',
'login'                     => 'Ingia',
'nav-login-createaccount'   => 'Ingia/ sajili akaunti',
'loginprompt'               => 'Lazima kompyuta yako ipokee kuki ili uweze kuingia kwenye {{SITENAME}}.',
'userlogin'                 => 'Ingia/ sajili akaunti',
'logout'                    => 'Toka',
'userlogout'                => 'Toka',
'notloggedin'               => 'Hujajiandikisha',
'nologin'                   => 'Huna akaunti ya kuingilia? $1.',
'nologinlink'               => 'Sajili akaunti',
'createaccount'             => 'Sajili akaunti',
'gotaccount'                => 'Unayo akaunti tayari? $1',
'gotaccountlink'            => 'Ingia',
'createaccountmail'         => 'Kwa barua pepe',
'badretype'                 => 'Maneno uliyoyaandika ni tofauti.',
'userexists'                => 'Jina la mtumiaji uliloingiza tayari linatumika.
Tafadhali chagua jina lingine.',
'youremail'                 => 'Barua pepe yako:',
'username'                  => 'Jina la mtumiaji:',
'uid'                       => 'Namba ya mtumiaji:',
'prefs-memberingroups'      => 'Mwanachama wa {{PLURAL:$1|group|makundi}}:',
'yourrealname'              => 'Jina lako halisi:',
'yourlanguage'              => 'Lugha:',
'yourvariant'               => 'Mbalimbali:',
'yournick'                  => 'Sahihi:',
'badsig'                    => 'Umeweka sahihi batili.
Angalia mabano ya HTML.',
'badsiglength'              => 'Sahihi uliyoweka ni ndefu mno.
Haiwezi kuzidi {{PLURAL:$1|tarakimu|tarakimu}} $1.',
'email'                     => 'Barua pepe',
'prefs-help-realname'       => 'Jina la kweli si lazima. Ukichagua kutaja jina lako hapa, litatumiwa kuonyesha kwamba ndiyo ulifanya kazi unayochangia.',
'loginerror'                => 'Kosa la kuingia',
'prefs-help-email'          => 'Barua pepe sio lazima, lakini inawezesha kupokea nywila mpya kwa kupitia barua pepe yako endapo utakuwa umeisahau.
Pia unaweza kuchagua kuacha watumiaji wengine kuwasiliana nao kwa kutumia ukurasa wako wa mtumiaji au ule wa majadiliano bila ya kuonyesha jina la akaunti yako.',
'prefs-help-email-required' => 'Barua pepe inahitajika.',
'nocookiesnew'              => "Umesajiliwa, lakini bado hujaingizwa. {{SITENAME}} inatumia ''kuki'' ili watumiaji waingizwe. Kompyuta yako inazuia ''kuki''. Tafadhali, ondoa kizuizi hicho uingie kwa kutumia jina mpya na neno la siri.",
'nocookieslogin'            => '{{SITENAME}} inatumia kuki ili watumiaji waweze kuingia.
Kompyuta yako inakataa kupokea kuki.
Tafadhali, ondoa kizuizi hicho, baadaye jaribu tena.',
'noname'                    => 'Hauja dhihilisha jina la mtumiaji.',
'loginsuccesstitle'         => 'Umefaulu kuingia',
'loginsuccess'              => "'''Umeingia {{SITENAME}} kama \"\$1\".'''",
'nosuchuser'                => 'Hakuna mtumiaji mwenye jina "$1". Labda umeandika vibaya, au [[Special:UserLogin/signup|sajili akaunti mpya]].',
'nosuchusershort'           => 'Hakuna mtumiaji mwenye jina "<nowiki>$1</nowiki>". Labda umeandika vibaya.',
'nouserspecified'           => 'Lazima uandike jina la mtumiaji.',
'wrongpassword'             => 'Umeingiza nywila ya makosa. Jaribu tena.',
'wrongpasswordempty'        => 'Nywila ilikuwa tupu. Jaribu tena.',
'passwordtooshort'          => 'Nywila yako haifai.  Ni lazima iwe na {{PLURAL:#1|herufi}} $1 au zaidi, na inabidi nywila na jina la mtumiaji ziwe tofauti.',
'mailmypassword'            => 'Nitume nywila mpya kwa barua pepe',
'passwordremindertitle'     => 'Nywila mpya ya muda kwa {{SITENAME}}',
'passwordremindertext'      => 'Mtu mmoja (yamkini wewe, kutoka anwani ya IP $1)
ameulizia nywila mpya kwa {{SITENAME}} ($4).
Nywila ya muda kwa mtumiaji "$2" sasa ni "$3".
Inatakiwa uingie na ubadilishe nywila yako sasa. Nywila yako ya muda itaishia baada ya siku {{PLURAL:$5|moja|$5}}.

Kama mtu mwingine ametoa ombi hili au kama umekumbuka nywila yako na
umeamua kutoibadilisha, unaweza kupuuza ujumbe huu na
kuendelea kutumia nywila yako ya awali.',
'noemail'                   => 'Hatuna anwani ya barua pepe kwa mtumiaji  "$1".',
'passwordsent'              => 'Neno mpya la siri limeshatumia kwenye anwani ya baruapepe ya "$1".
Tafadhali, ingia baada ya kulipokea.',
'blocked-mailpassword'      => 'Anwani yako ya IP imezuiwa kuhariri {{SITENAME}}, kwa maana hiyo hairuhusiiswi kuumba nywila mpya kwa lengo la kulinda uharibifu.',
'eauthentsent'              => 'Tumekutuma barua pepe ili kuhakikisha anwani yako.
Kabla ya kutuma barua pepe nyingine kwenye akaunti hiyo, itabidi ufuate maelezo katika barua utakayopokea,
kuthibitisha kwamba wewe ndiyo ni mwenye akaunti.',
'throttled-mailpassword'    => 'Kikumbusho cha nywila tayari kimeshatumwa, ndani ya {{PLURAL:$1|hour|$1masaa}} kadhaa yaliyopita.
Ili kuzuiya uhuni, kiumbusho cha nywila kimoja pekee utakachotumiwa kwa {{PLURAL:$1|hour|$1 masaa}} kadhaa.',
'createaccount-text'        => 'Kuna mtu amesajili akaunti kwa kutumia anwani ya barua pepe yako kwenye  {{SITENAME}} ($4) anaitwa "$2", yenye nywila "$3".
Inabidi uingie na kisha ubadilishe nywila yako sasa.

Unaweza kupuuza ujumbe huu, endapo akaunti hii ilianzishwa kimakosa.',
'login-throttled'           => 'Umefanya majaribio kadha wa kadha ya kutaka kuingiza nywila ya akaunti hii. Tafadhali subiri kwanza kabla ya kujaribu tena.',
'loginlanguagelabel'        => 'Lugha: $1',

# Password reset dialog
'oldpassword' => 'Neno la siri la zamani',
'newpassword' => 'Neno mpya la siri',
'retypenew'   => 'Andika nywila tena:',

# Edit page toolbar
'bold_sample'     => 'Matini ya koze',
'bold_tip'        => 'Matini ya koze',
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
'summary'                => 'Muhtasari:',
'subject'                => 'Kuhusu/kichwa cha habari:',
'minoredit'              => 'Haya ni mabadiliko madogo',
'watchthis'              => 'Fuatilia ukurasa huu',
'savearticle'            => 'Hifadhi ukurasa',
'preview'                => 'Hakikisha',
'showpreview'            => 'Onyesha hakikisho la mabadiliko',
'showdiff'               => 'Onyesha mabadiliko',
'anoneditwarning'        => "'''Ilani:''' Wewe hujaingia rasmi kwenye tovuti. Anwani ya IP ya tarakilishi yako itahifadhiwa katika historia ya uhariri wa ukurasa huu.",
'summary-preview'        => 'Hakikisho la muhtasari:',
'blockedtext'            => "<big>'''Jina lako la mtumiaji au anwani yako ya IP imezuiwa.'''</big>

Umezuiwa na $1. 
Sababu aliyetambua ni ''$2''

* Mwanzo wa uzuio: $8
* Mwisho wa uzuio: $6
* Aliyezuiwa: $7

Unaweza kuwasiliana na $1 au [[{{MediaWiki:Grouppage-sysop}}|mkabidhi]] kuzungumza uzuio.
Huwezi kutumia kipengele 'kumtuma mtumiaji barua pepe' isipopatikana anwani halisi ya barua pepe katika
[[Special:Preferences|mapendekezo ya akaunti]] yako, na usipozuiwa kuitumia.
Anwani yako ya IP ni $3, na namba ya uzuio ni #$5. Tafadhali taja namba hizi ukitaka kuwasiliana kuhusu uzuio huu.",
'loginreqtitle'          => 'Unatakiwa kuingia au kujisajili',
'accmailtitle'           => 'Neno la siri limeshakutumia.',
'accmailtext'            => "Neno la siri la '$1' limeshatumwa kwa $2.",
'newarticle'             => '(Mpya)',
'newarticletext'         => "Ukurasa unaotaka haujaandikwa bado. Ukipenda unaweza kuuandika wewe mwenyewe kwa kutumia sanduku la hapa chini (tazama [[{{MediaWiki:Helppage}}|Mwongozo]] kwa maelezo zaidi). Ukifika hapa kwa makosa, bofya kibonyezi '''back''' (nyuma) cha programu yako.",
'noarticletext'          => 'Ukurasa huu haujaandikwa bado. [[Special:Search/{{PAGENAME}}|tafutia jina hili]] katika kurasa nyingine, <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} tafuta kumbukumbu zinazohusika], au [{{fullurl:{{FULLPAGENAME}}|action=edit}} hariri ukurasa huu]</span>.',
'previewnote'            => "'''Hii ni hakikisho tu; mabadiliko hayajahifadhiwa bado!'''",
'editing'                => 'Kuhariri $1',
'editingsection'         => 'Unahariri $1 (kipande)',
'yourtext'               => 'Maandishi yako',
'editingold'             => "'''ANGALIA: Unakuwa unahariri nakala ya zamani ya ukurasa huu.
Ukiendelea kuihariri, mabadilisho yote yaliyofanywa tangu pale yatapotezwa.'''",
'copyrightwarning'       => "Tafadhali zingatia kwamba makala yote ya {{SITENAME}} unayoyaandika yanafuata $2 (tazama $1 kwa maelezo zaidi).
Usipotaka maandishi yako yaweze kuharirishwa bure na kutolewa wakati wowote, basi usiyaandike hapa.<br />
Unakuwa unaahidi kwamba maandishi unayoyaingia ni yako tu, au uliyapata kutoka bure au ni mali ya watu wote. '''USITOLEE MAKALA YALIYOHIFADHIWA HAKI ZAO ZA KUTUMIWA BILA KUPATA RUHUSA HALALI!'''",
'longpagewarning'        => "'''ILANI: Urefu wa ukurasa huu ni kilobaiti $1; vivinjari kadhaa vinaweza kuwa na matatizo ukihariri ukurasa wenye urefu zaidi ya kb 32 hivi.
Tafadhali fikiria kuhusu kuvunja ukurasa kwa vipande vifupi.'''",
'protectedpagewarning'   => "'''ANGALIA: Ukurasa huu unakingwa kwa hiyo watumiaji wenye haki za wasimamizi tu wanaweza kuuhariri. Hakikisha kwamba unakuwa unafuata mwongozo wa kuhariri kurasa zinazokingwa.'''",
'templatesused'          => 'Vigezo vinavyotumiwa kwenye ukurasa huu:',
'templatesusedpreview'   => 'Vigezo vinavyotumiwa katika mandhari haya:',
'template-protected'     => '(kulindwa)',
'template-semiprotected' => '(ulindaji kwa kiasi)',
'nocreatetext'           => '{{SITENAME}} imebana uwezekano kutengeneza kurasa mpya. Unaweza kurudia na kuhariri kurasa zilizomo, au [[Special:UserLogin|ingia au anza akaunti]].',
'nocreate-loggedin'      => 'Huna ruhusa ya kuazisha kurasa mpya.',
'recreate-deleted-warn'  => "'''Ilani: Unatengeneza tena ukurasa uliofutwa tayari.'''

Fikiria kama inafaa kuendelea kuhariri ukurasa huu.
Kumbukumbu ya kufuta ukurasa huu linapatikana hapa kukusaidia:",

# History pages
'viewpagelogs'           => 'Tazama kumbukumbu kwa ukurasa huu',
'currentrev'             => 'Kiungo cha daima',
'revisionasof'           => 'Sahihisho kutoka $1',
'revision-info'          => 'Sahihisho ya $1 aliyefanya $2', # Additionally available: $3: revision id
'previousrevision'       => '←Sahihisho lililotangulia',
'nextrevision'           => 'Sahihisho linalofuata →',
'currentrevisionlink'    => 'Sahahisho ya sasa hivi',
'cur'                    => 'sasa',
'last'                   => 'kabla',
'page_first'             => 'ya kwanza',
'page_last'              => 'ya mwisho',
'histlegend'             => 'Chagua tofauti: tia alama katika vitufe redio kulinganisha matoleo, na bonyeza "enter" au kitufe hapo chini.<br />
Ufunguo: (sasa) = tofauti na toleo la sasa, (kabla) = tofauti na toleo lililotangulia, D = mabadiliko maDogo.',
'history-fieldset-title' => 'Fungua historia',
'histfirst'              => 'Mwanzoni',
'histlast'               => 'Mwishoni',

# Revision feed
'history-feed-item-nocomment' => '$1 kwenye $2', # user at time

# Revision deletion
'rev-delundel' => 'onyesha/ficha',

# Diffs
'history-title'           => 'Historia ya masahihisho ya "$1"',
'difference'              => '(Tofauti baina ya masahihisho)',
'lineno'                  => 'Mstari $1:',
'compareselectedversions' => 'Linganisha matoleo mawili uliyochagua',
'editundo'                => 'tengua',
'diff-multi'              => '(Hatuonyeshi {{PLURAL:$1|sahihisho moja la katikati|masahihisho $1 ya katikati}}.)',

# Search results
'searchresults'                => 'Matokeo ya utafutaji',
'searchresults-title'          => 'Matokeo ya utafutaji kwa ajili ya "$1"',
'searchsubtitle'               => 'Ulitafuta \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|kurasa zote zinazoanza "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kurasa zote zinazoungwa na "$1"]])',
'searchsubtitleinvalid'        => "Ulitafuta '''$1'''",
'noexactmatch'                 => "'''Hakuna ukurasa wenye jina \"\$1\".''' Unaweza [[:\$1|kuanza ukurasa huu]].",
'notitlematches'               => 'Jina hili la ukurasa halikupatikana',
'prevn'                        => '$1 iliyotangulia',
'nextn'                        => '$1 ijayo',
'viewprevnext'                 => 'Tazama ($1) ($2) ($3)',
'searchmenu-legend'            => 'Hitiari za kutafuta',
'searchhelp-url'               => 'Help:Yaliyomo',
'searchmenu-prefix'            => '[[Special:PrefixIndex/$1|Tafuta kurasa kwenye eneo hili la wiki]]',
'searchprofile-project'        => 'Kurasa za mradi',
'searchprofile-images'         => 'Mafaili',
'searchprofile-everything'     => 'Zote',
'searchprofile-advanced'       => 'Hali ya juu',
'searchprofile-images-tooltip' => 'Tafuta mafaili',
'search-result-size'           => '$1 ({{PLURAL:$2|neno 1|maneno $2}})',
'search-suggest'               => 'Je, ulitaka kutafuta: $1',
'search-interwiki-default'     => 'Matokeo toka $1:',
'search-interwiki-more'        => '(zaidi)',
'search-mwsuggest-enabled'     => 'na mapendekezo',
'search-mwsuggest-disabled'    => 'bila makendekezo',
'searchall'                    => 'zote',
'showingresultstotal'          => "{{PLURAL:$4|Tokeo '''$1''' kati ya jumla ya '''$3'''|Matokeo '''$1 - $2''' kati ya jumla ya '''$3'''}} yanaorodheshwa chini.",
'powersearch'                  => 'Tafuta kwa hali ya juu',
'powersearch-legend'           => 'Tafuta kwa hali ya juu',
'powersearch-ns'               => 'Tafuta kwenye maeneo ya wiki yafuatayo:',
'powersearch-redir'            => 'Orodhesha kurasa za kuelekeza',
'powersearch-field'            => 'Tafuta huu:',

# Preferences page
'preferences'               => 'Mapendekezo',
'mypreferences'             => 'Mapendekezo yangu',
'changepassword'            => 'Badilisha neno la siri',
'skin'                      => 'Sura',
'prefs-watchlist'           => 'Maangalizi',
'saveprefs'                 => 'Hifadhi',
'rows'                      => 'Mistari:',
'columns'                   => 'Safu:',
'searchresultshead'         => 'Kutafuta',
'timezonelegend'            => 'Ukanda saa',
'timezoneselect'            => 'Ukanda saa:',
'timezoneregion-africa'     => 'Afrika',
'timezoneregion-america'    => 'Marekani',
'timezoneregion-antarctica' => 'Antaktika',
'timezoneregion-arctic'     => 'Artiki',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Bahari ya Atlantiki',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Ulaya',
'timezoneregion-indian'     => 'Bahari ya Hindi',
'timezoneregion-pacific'    => 'Bahari ya Pasifiki',
'prefs-searchoptions'       => 'Hitiari za kutafuta',
'files'                     => 'Mafaili',

# Groups
'group'       => 'Kundi:',
'group-sysop' => 'Wakabidhi',

'grouppage-sysop' => '{{ns:project}}:Wakabidhi',

# User rights log
'rightslog' => 'Kumbukumbu ya vyeo vya watumiaji',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'kusoma ukurasa huu',
'action-edit'       => 'kuhariri ukurasa huu',
'action-createpage' => 'kuanzisha kurasa',
'action-move'       => 'kusogeza ukurasa huu',

# Recent changes
'nchanges'                       => '{{PLURAL:$1|badiliko|mabadiliko}} $1',
'recentchanges'                  => 'Mabadiliko ya karibuni',
'recentchanges-legend'           => "Machaguo ya 'mabadaliko ya karibuni'",
'recentchangestext'              => 'Orodha ya mabadilisho yaliyofanywa katika {{SITENAME}} siku zilizopita.',
'recentchanges-feed-description' => 'Tumia tawanyiko hili kufuatilia mabadiliko yote ya hivi karibuni katika Wiki.',
'rcnote'                         => "Yanayofuata ni {{PLURAL:$1|badiliko '''1'''|mabadiliko '''$1''' ya mwisho}} kutoka katika {{PLURAL:$2|siku iliyopita|siku '''$2''' zilizopita}}, hadi $5, $4.",
'rcnotefrom'                     => "Hapo chini yaonekana mabadiliko tangu '''$2''' (tunaonyesha hadi '''$1''').",
'rclistfrom'                     => 'Onyesha mabadiliko mapya kuanzia $1',
'rcshowhideminor'                => '$1 mabadiliko madogo',
'rcshowhidebots'                 => '$1 roboti',
'rcshowhideliu'                  => '$1 watumiaji sasa',
'rcshowhideanons'                => '$1 watumiaji bila majina',
'rcshowhidepatr'                 => '$1 masahihisho yanayofanywa doria',
'rcshowhidemine'                 => '$1 masahihisho zangu',
'rclinks'                        => 'Onyesha mabadiliko $1 yaliyofanywa wakati wa siku $2 zilizopita<br />$3',
'diff'                           => 'tofauti',
'hist'                           => 'hist',
'hide'                           => 'Ficha',
'show'                           => 'Onyesha',
'minoreditletter'                => 'd',
'newpageletter'                  => 'P',
'boteditletter'                  => 'r',
'newsectionsummary'              => '/* $1 */ mjadala mpya',

# Recent changes linked
'recentchangeslinked'          => 'Mabadiliko husika',
'recentchangeslinked-title'    => 'Mabadiliko kuhusiana na "$1"',
'recentchangeslinked-noresult' => 'Hakuna mabadiliko kwenye kurasa zilizounganishwa wakati wa muda huo.',
'recentchangeslinked-summary'  => "Ukurasa maalum huu unaorodhesha mabadiliko ya hivi karibuni katika kurasa zinazoungwa (au katika jamii fulani).  Kurasa katika [[Special:Watchlist|maangalizi yako]] ni za '''koze'''.",
'recentchangeslinked-page'     => 'Jina la ukurasa:',

# Upload
'upload'            => 'Pakia faili',
'uploadbtn'         => 'Pakia faili',
'uploadlogpage'     => 'Kumbukumbu ya upakiaji',
'filedesc'          => 'Muhtasari',
'fileuploadsummary' => 'Muhtasari:',
'ignorewarning'     => 'Hifadhi bila kujali maonyo yoyote.',
'uploadedimage'     => ' "[[$1]]" imepakiwa',

'license' => 'Hatimiliki:',

# Special:ListFiles
'listfiles' => 'Orodha ya mafaili',

# File description page
'filehist'                  => 'Historia ya faili',
'filehist-help'             => 'Bonyeza tarehe/saa kuona faili kama ilivyoonekana wakati huo.',
'filehist-current'          => 'sasa hivi',
'filehist-datetime'         => 'Tarehe/Saa',
'filehist-user'             => 'Mtumiaji',
'filehist-dimensions'       => 'Vipimo',
'filehist-filesize'         => 'Ukubwa wa faili',
'filehist-comment'          => 'Maoni',
'imagelinks'                => 'Viungo vya mafaili',
'linkstoimage'              => '{{PLURAL:$1|Ukurasa huu|Kurasa hizi $1}} zimeunganishwa na faili hili:',
'nolinkstoimage'            => 'Hakuna kurasa zozote zilizounganishwa na faili hii.',
'sharedupload'              => 'Faili hili linatoka $1 na linaweza kushirikiwa na miradi mingine.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki-linktext' => 'ukurasa wa maelezo ya faili',
'noimage'                   => 'Hakuna faili yenye jina hili, $1 kama unayo.',
'noimage-linktext'          => 'pakia faili',
'uploadnewversion-linktext' => 'Pakia toleo jipya la faili hii',

# File deletion
'filedelete-intro-old'   => "You are deleting the version of '''[[Media:$1|$1]]''' as of [$4 $3, $2].",
'filedelete-success-old' => "The version of '''[[Media:$1|$1]]''' as of $3, $2 has been deleted.",
'filedelete-nofile-old'  => "There is no archived version of '''$1''' with the specified attributes.",

# MIME search
'mimesearch' => 'Utafutaji wa MIME',

# List redirects
'listredirects' => 'Maelekezo',

# Unused templates
'unusedtemplates' => 'Vigezo ambavyo havitumiwi',

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

'brokenredirects'      => 'Maelekezo yenye hitilafu',
'brokenredirects-edit' => '(hariri)',

'withoutinterwiki' => 'Kurasa bila viungo kwenye lugha nyingine',

'fewestrevisions' => 'Kurasa zenye masahihisho machache kuliko zote',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|baiti|baiti}} $1',
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
'ancientpages'            => 'Kurasa za kale',
'move'                    => 'Sogeza',
'movethispage'            => 'Sogeza ukurasa huu',
'pager-newer-n'           => '{{PLURAL:$1|1 ya karibu zaidi|$1 ya karibu zaidi}}',
'pager-older-n'           => '{{PLURAL:$1|$1 ya zamani zaidi}}',

# Book sources
'booksources'    => 'Vyanzo vya vitabu',
'booksources-go' => 'Nenda',

# Special:Log
'specialloguserlabel'  => 'Mtumiaji:',
'speciallogtitlelabel' => 'Kichwa:',
'log'                  => 'Kumbukumbu',
'all-logs-page'        => 'Kumbukumbu zote',

# Special:AllPages
'allpages'       => 'Kurasa zote',
'alphaindexline' => '$1 hadi $2',
'nextpage'       => 'Ukurasa ujao ($1)',
'prevpage'       => 'Ukurasa uliotangulia ($1)',
'allpagesfrom'   => 'Onyesha kurasa zinazoanza kutoka:',
'allarticles'    => 'Kurasa zote',
'allpagessubmit' => 'Nenda',
'allpagesprefix' => 'Onyesha kurasa zenye kiambishi awali:',

# Special:Categories
'categories' => 'Jamii',

# Special:ListGroupRights
'listgrouprights-group'   => 'Kundi',
'listgrouprights-members' => '(orodha ya wanachama)',

# E-mail user
'emailuser'    => 'Mtumie mtumiaji huyu barua pepe',
'emailto'      => 'Kwa:',
'emailmessage' => 'Ujumbe:',
'emailsend'    => 'Tuma',

# Watchlist
'watchlist'         => 'Maangalizi yangu',
'mywatchlist'       => 'Maangalizi yangu',
'watchlistfor'      => "(kwa '''$1''')",
'addedwatch'        => 'Imeongezwa kwenye maangalizi yako',
'addedwatchtext'    => "Ukurasa \"[[:\$1]]\" umewekwa kwenye [[Special:Watchlist|maangalizi]] yako.
Mabadiliko katika ukurasa huo na ukurasa wake wa majadiliano utaonekana hapo,
na ukurasa utaonyeshwa wenye '''koze''' kwenye [[Special:RecentChanges|orodha ya mabadiliko ya karibuni]]
ili kukusaidia kutambua.

Ukitaka kufuta ukurasa huo kutoka maangalizi yako baadaye, bonyeza \"Acha kufuatilia\" katika mwamba pembeni.",
'removedwatch'      => 'Imefutwa kutoka maangalizi yako',
'removedwatchtext'  => 'Ukurasa "[[:$1]]" umefutwa kutoka maangalizi yako.',
'watch'             => 'Fuatilia',
'watchthispage'     => 'Fuatilia ukurasa huu',
'unwatch'           => 'Acha kufuatilia',
'watchlist-details' => 'Unafuatilia {{PLURAL:$1|ukurasa $1|kurasa $1}} bila kuzingatia kurasa za majadiliano.',
'wlshowlast'        => 'Onyesha kutoka masaa $1 siku $2 $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Unafuatilia...',
'unwatching' => 'Umeacha kufuatilia...',

# Delete
'deletepage'            => 'Futa ukurasa',
'historywarning'        => 'Ilani: Ukurasa unaotaka kufuta una historia yake:',
'confirmdeletetext'     => 'Wewe unategemea kufuta ukurasa pamoja na historia yake yote.
Tafadhali hakikisha kwamba unalenga kufanya hivyo, na kwamba unaelewa matokeo yake, na kwamba unafuata [[{{MediaWiki:Policy-url}}|sera]].',
'actioncomplete'        => 'Kitendo kimekwisha',
'deletedtext'           => '"<nowiki>$1</nowiki>" imefutwa. Ona $2 kwa historia ya kurasa zilizofutwa hivi karibuni.',
'deletedarticle'        => '"[[$1]]" ilifutwa',
'dellogpage'            => 'Kumbukumbu ya ufutaji',
'deletecomment'         => 'Sababu ya kufuta',
'deleteotherreason'     => 'Sababu nyingine:',
'deletereasonotherlist' => 'Sababu nyingine',

# Rollback
'rollbacklink' => 'rejesha',

# Protect
'protectlogpage'              => 'Kumbukumbu ya ulindaji',
'prot_1movedto2'              => '[[$1]] umesogezwa hapa [[$2]]',
'protect-legend'              => 'Hakikisha ukingo',
'protectcomment'              => 'Maoni:',
'protectexpiry'               => 'Itakwisha:',
'protect_expiry_invalid'      => 'Muda wa kwisha ni batilifu.',
'protect_expiry_old'          => 'Muda wa kuishi umepita tayari.',
'protect-unchain'             => 'Fungua ruhusa za kusogeza',
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
'protect-expiry-options'      => 'saa 1:1 hour,siku 1:1 day,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Ruhusa:',
'restriction-level'           => 'Kiwango cha kizuio:',

# Restrictions (nouns)
'restriction-edit'   => 'Kuhariri',
'restriction-move'   => 'Kusogeza',
'restriction-create' => 'Kuanzisha',
'restriction-upload' => 'Kupakia',

# Undelete
'viewdeletedpage' => 'Tazama kurasa zilizofutwa',
'undeletebtn'     => 'Rudisha',

# Namespace form on various pages
'namespace'      => 'Chagua eneo la wiki:',
'invert'         => 'Geuza uteuzi',
'blanknamespace' => '(Kuu)',

# Contributions
'contributions' => 'Michango ya watumiaji',
'mycontris'     => 'Michango yangu',
'contribsub2'   => 'Kwa $1 ($2)',
'uctop'         => '(juu)',
'month'         => 'Kutoka mwezi (na zamani zaidi):',
'year'          => 'Kutoka mwakani (na zamani zaidi):',

'sp-contributions-newbies'     => 'Onyesha michango ya akaunti mpya tu',
'sp-contributions-newbies-sub' => 'Kwa akaunti mpya',
'sp-contributions-blocklog'    => 'Kumbukumbu ya uzuio',
'sp-contributions-search'      => 'Tafuta michango',
'sp-contributions-username'    => 'Anwani ya IP au jina la mtumiaji:',
'sp-contributions-submit'      => 'Tafuta',

# What links here
'whatlinkshere'       => 'Viungo viungavyo ukurasa huu',
'whatlinkshere-title' => 'Kurasa zilizounganishwa na "$1"',
'whatlinkshere-page'  => 'Ukurasa:',
'linkshere'           => "Kurasa zifuatazo zimeunganishwa na '''[[:$1]]''':",
'nolinkshere'         => "Hakuna kurasa zilizounganishwa na '''[[:$1]]'''.",
'isredirect'          => 'elekeza ukurasa',
'istemplate'          => 'jumuisho',
'whatlinkshere-prev'  => '{{PLURAL:$1|uliotangulia|$1 zilizotangulia}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ujao|$1 zijazo}}',
'whatlinkshere-links' => '← viungo',

# Block/unblock
'blockip'       => 'Zuia mtumiaji',
'ipboptions'    => 'Masaa 2:2 hours,siku 1:1 day,siku 3:3 days,wiki 1:1 week,wiki 2:2 weeks,mwezi 1:1 month,miezi 3:3 months,miezi 6:6 months,mwaka 1:1 year,milele:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => 'Anwani za IP na majina ya watumiaji waliozuiliwa',
'blocklink'     => 'zuia',
'unblocklink'   => 'acha kuzuia',
'contribslink'  => 'michango',
'blocklogpage'  => 'Kumbukumbu ya uzuio',
'blocklogentry' => 'amemzuia [[$1]] mpaka $2 $3',

# Move page
'move-page-legend' => 'Sogeza ukurasa',
'movepagetext'     => "Tumia fomu hapo chini itabadilisha jina la ukurasa, na itahamisha historia yake yote katika jina jipya lile lile.
Jina la awali litahamishwa na kuelekezwa katika mahali pa jina jipya.
Viungo vilivyounganishwa na ukurasa wa awali havitabadilishwa;
tafadhali tafutia maelekezo yenye hitilafu na maelekezo mawilimawili.
Wewe una madaraka kuhakikisha kwamba viungo viendelee kuelekea vinapolengwa.

Uwe mwangalifu kwamba ukurasa '''hautahamishwa''' kama tayari kuna ukurasa wenye jina jipya, ila ni tupu au ni maelekezo na hauna historia ya kuhaririwa.
Yaani unaweza kurudisha ukurasa kwenye jina la awali ukikosa, na haiwezekani kufuta ukurasa mwingine kwa nasibu.

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
'newtitle'         => 'Kuelekeza jina jipya:',
'move-watch'       => 'Fuatilia ukurasa huu',
'movepagebtn'      => 'Sogeza ukurasa',
'pagemovedsub'     => 'Umefaulu kusogeza ukurasa',
'movepage-moved'   => '<big>\'\'\'"$1" imesogezwa kwenye "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Tayari kuna ukurasa wenye jina hilo, au
jina ulilochagua ni batilifu.
Chagua jina lengine.',
'talkexists'       => "'''Ukurasa wenyewe ulisogezwa salama, lakini ukurasa wake wa majadiliano haujasogezwa kwa sababu tayari kuna ukurasa wenye jina lake.  Tafadhali ziunganishe kwa mkono.'''",
'movedto'          => 'imesogezwa hadi',
'movetalk'         => 'Sogeza ukurasa wake wa majadiliano',
'1movedto2'        => '[[$1]] umesogezwa hapa [[$2]]',
'movelogpage'      => 'Kumbukumbu ya uhamiaji',
'movereason'       => 'Sababu:',
'revertmove'       => 'rejesha',

# Export
'export' => 'Hamisha kurasa',

# Namespace 8 related
'allmessages'     => 'Ujumbe za mfumo',
'allmessagesname' => 'Jina',

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
'tooltip-ca-protect'              => 'Linda ukurasa huu',
'tooltip-ca-delete'               => 'Futa ukurasa huu',
'tooltip-ca-move'                 => 'Sogeza ukurasa huu',
'tooltip-ca-watch'                => 'Fuatilia ukurasa huu kwenye maangalizi yako',
'tooltip-ca-unwatch'              => 'Futa ukurasa huu kutoka maangalizi yako',
'tooltip-search'                  => 'Tafuta {{SITENAME}}',
'tooltip-search-fulltext'         => 'Tafuta kurasa kwa maandishi haya',
'tooltip-n-mainpage'              => 'Tembelea Mwanzo',
'tooltip-n-portal'                => 'Kuhusu mradi, mambo unaweza kufanya, na mahali pa kugundua vitu',
'tooltip-n-currentevents'         => 'Maarifa kuhusu habari za siku hizi',
'tooltip-n-recentchanges'         => 'Orodha ya mabadiliko ya hivi karibuni katika Wiki.',
'tooltip-n-randompage'            => 'Onyesha ukurasa wa bahati',
'tooltip-n-help'                  => 'Mahali pa kueleweshwa.',
'tooltip-t-whatlinkshere'         => 'Orodha ya kurasa zote za Wiki zilizounganishwa na ukurasa huu',
'tooltip-t-contributions'         => 'Tazama orodha ya michango kwa mtumiaji huyu',
'tooltip-t-emailuser'             => 'Mtumie mtumiaji huyu barua pepe',
'tooltip-t-upload'                => 'Pakia picha, video, au sauti',
'tooltip-t-specialpages'          => 'Orodha ya kurasa maalum zote',
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

# Attribution
'anonymous'        => '{{PLURAL:$1|mtumiaji bila jina|watumiaji bila majina}} wa {{SITENAME}}',
'siteuser'         => 'mtumiaji $1 wa {{SITENAME}}',
'lastmodifiedatby' => 'Ukurasa huu umebadilishwa kwa mara ya mwisho saa $2, tarehe $1 na $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Ukurasa uliandikwa pia na $1.',
'others'           => 'wengine',
'siteusers'        => '{{PLURAL:$2|mtumiaji|watumiaji}} wa {{SITENAME}} $1',
'creditspage'      => 'Wandishi wa ukurasa',
'nocredits'        => 'Taarifa kuhusu wandishi wa ukurasa huu haipatikana.',

# Image deletion
'filedelete-old-unregistered' => 'The specified file revision "$1" is not in the database.',

# Browsing diffs
'previousdiff' => '← Badilisho lililopita',
'nextdiff'     => 'Badilisho lijalo →',

# Media information
'file-info-size'       => '(piseli $1 × $2, saizi ya faili: $3, aina ya MIME: $4)',
'file-nohires'         => '<small>Hakuna saizi kubwa zaidi.</small>',
'svg-long-desc'        => '(faili ya SVG, husemwa kuwa piseli $1 × $2, saizi ya faili: $3)',
'show-big-image'       => 'Ukubwa wa awali',
'show-big-image-thumb' => '<small>Ukubwa wa hakikisho hili: piseli $1 x $2</small>',

# Special:NewFiles
'newimages' => 'Mkusanyiko wa faili jipya',

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
* focallength', # Do not translate list items

# EXIF tags
'exif-componentsconfiguration' => 'Maana ya kila kijenzi',
'exif-makernote'               => 'Maelezo ya mtengenezaji',
'exif-usercomment'             => 'Maoni ya mtumiaji',
'exif-relatedsoundfile'        => 'Faili ya sauti inayohusika',

# External editor support
'edit-externally'      => 'Tumia programu ya nje kuhariri faili hii',
'edit-externally-help' => '(Ona [http://www.mediawiki.org/wiki/Manual:External_editors maelezo (kwa Kiingereza)] kwa maarifa mengine.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'yote',
'watchlistall2'    => 'zote',
'namespacesall'    => 'zote',
'monthsall'        => 'zote',

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

# Watchlist editing tools
'watchlisttools-view' => 'Tazama mabadiliko yanayohusiana',
'watchlisttools-edit' => 'Tazama na hariri maangalizi',
'watchlisttools-raw'  => 'Hariri maangalizi ghafi',

# Special:Version
'version' => 'Toleo', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages'               => 'Kurasa maalum',
'specialpages-group-changes' => 'Mabadiliko ya karibuni na kumbukumbu',
'specialpages-group-pages'   => 'Orodha za kurasa',

# Special:Tags
'tags-edit' => 'hariri',

);
