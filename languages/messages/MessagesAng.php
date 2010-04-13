<?php
/** Old English (Anglo-Saxon)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gott wisst
 * @author JJohnson
 * @author Omnipaedista
 * @author Spacebirdy
 * @author Wōdenhelm
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Syndrig',
	NS_TALK             => 'Gesprec',
	NS_FILE             => 'Biliþ',
	NS_FILE_TALK        => 'Biliþgesprec',
	NS_TEMPLATE         => 'Bysen',
	NS_TEMPLATE_TALK    => 'Bysengesprec',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Helpgesprec',
	NS_CATEGORY         => 'Flocc',
	NS_CATEGORY_TALK    => 'Floccgesprec',
);

$messages = array(
# User preference toggles
'tog-underline'          => 'Hlenċa undermearcian:',
'tog-hideminor'          => 'Hȳdan smalla ādihtunga in nīwlicum wendungum',
'tog-rememberpassword'   => 'Mīne inmeldunge ȝemyndan on þissum spearcatelle',
'tog-watchcreations'     => 'Sīdan þe iċ scieppe ēacian tō mīnre ƿæccȝetale',
'tog-watchdefault'       => 'Sīdan þe iċ ādihte ēacian tō mīnre ƿæccȝetale',
'tog-watchmoves'         => 'Sīdan þe iċ hƿeorfe ēacian tō mīnre ƿæccȝetale',
'tog-watchdeletion'      => 'Sīdan þe iċ forlēose ēacian tō mīnre ƿæccȝetale',
'tog-minordefault'       => 'Ealle ādihtende mearcian tōlas ȝeƿunelīċe',
'tog-watchlisthideown'   => 'Hȳdan mīna ādihtunga wiþ þā behealdnestale',
'tog-watchlisthideminor' => 'Minliċa ādihtunga hȳdan ƿiþ þæt ƿæccbrede',
'tog-ccmeonemails'       => 'Mē tƿifealda sendan þāra e-ǣrenda þe iċ ōðrum brūcendum sende',
'tog-diffonly'           => 'Ne scēaƿian sīdan innunge under scādungum',
'tog-showhiddencats'     => 'Ȝehȳdede floccas scēaƿian',

'underline-always' => 'Ǣfre',
'underline-never'  => 'Nǣfre',

# Dates
'sunday'        => 'Sunnandæȝ',
'monday'        => 'Mōnandæȝ',
'tuesday'       => 'Tīƿesdæȝ',
'wednesday'     => 'Ƿēdnesdæȝ',
'thursday'      => 'Þunresdæȝ',
'friday'        => 'Frīȝedæȝ',
'saturday'      => 'Sæternesdæȝ',
'sun'           => 'Sun',
'mon'           => 'Mōn',
'tue'           => 'Tīƿ',
'wed'           => 'Ƿēd',
'thu'           => 'Þun',
'fri'           => 'Frī',
'sat'           => 'Sæt',
'january'       => 'Se Æfterra Ȝēola',
'february'      => 'Solmōnaþ',
'march'         => 'Hrēþmōnaþ',
'april'         => 'Ēostremōnaþ',
'may_long'      => 'Þrimilcemōnaþ',
'june'          => 'Sēremōnaþ',
'july'          => 'Mǣdmōnaþ',
'august'        => 'Ƿēodmōnaþ',
'september'     => 'Hāliȝmōnaþ',
'october'       => 'Ƿinterfylleþ',
'november'      => 'Blōtmōnaþ',
'december'      => 'Ȝēolmōnaþ',
'january-gen'   => 'þæs Æfterran Ȝēolan',
'february-gen'  => 'Solmōnþes',
'march-gen'     => 'Hrēþmōnþes',
'april-gen'     => 'Ēostremōnþes',
'may-gen'       => 'Þrimilcemōnþes',
'june-gen'      => 'Sēremōnþes',
'july-gen'      => 'Mǣdmōnþes',
'august-gen'    => 'Ƿēodmōnþes',
'september-gen' => 'Hāliȝmōnþes',
'october-gen'   => 'Ƿinterfylleðes',
'november-gen'  => 'Blōtmōnþes',
'december-gen'  => 'Ȝēolmōnþes',
'jan'           => 'ÆȜē',
'feb'           => 'Sol',
'mar'           => 'Hrē',
'apr'           => 'Ēos',
'may'           => 'Þri',
'jun'           => 'Sēr',
'jul'           => 'Mǣd',
'aug'           => 'Ƿēo',
'sep'           => 'Hāl',
'oct'           => 'Ƿin',
'nov'           => 'Blō',
'dec'           => 'Ȝēo',

# Categories related messages
'pagecategories'                => '{{PLURAL:$1|Flocc|Floccas}}',
'category_header'               => 'Ȝeƿritu in flocce "$1"',
'subcategories'                 => 'Underfloccas',
'category-media-header'         => 'Ȝemynda in flocce "$1"',
'category-empty'                => "''Þes flocc hæfþ nū nān ȝeƿritu oþþe ȝemynda in.''",
'hidden-categories'             => '{{PLURAL:$1|Ȝehȳded flocc|Ȝehȳdede floccas}}',
'hidden-category-category'      => 'Ȝehȳdede floccas',
'category-subcat-count-limited' => 'Þes flocc hæfþ {{PLURAL:$1|þisne underflocc|$1 þās underfloccas}}.',
'category-file-count-limited'   => '{{PLURAL:$1|Þēos fīl is|$1 Þās fīlan sind}} in þissum flocce.',
'listingcontinuesabbrev'        => 'mā',

'mainpagedocfooter' => 'Þeahtian [http://meta.wikimedia.org/wiki/Help:Contents Brūcendlǣdend] for helpe on bryce þǣre wiki software.

== Onginnende ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'      => 'Ymbe',
'article'    => 'Innungsīde',
'newwindow'  => '(openaþ in nīƿum ēagþyrele)',
'mypage'     => 'Mīnu sīde',
'mytalk'     => 'Mīn ȝespreċ',
'anontalk'   => 'Ȝespreċ for þissum IP',
'navigation' => 'Þurhfōr',

# Cologne Blue skin
'qbfind'         => 'Findan',
'qbbrowse'       => 'Ofer sēċan',
'qbedit'         => 'Ādihtan',
'qbpageoptions'  => 'Þēos sīde',
'qbpageinfo'     => 'Ȝeƿef',
'qbmyoptions'    => 'Mīna sīdan',
'qbspecialpages' => 'Syndriȝa sīdan',
'faq'            => 'Oftost ascoda ascunga',

# Vector skin
'vector-action-move'         => 'Ƿeȝan',
'vector-action-protect'      => 'Beorgan',
'vector-action-undelete'     => 'Edƿyrcan',
'vector-action-unprotect'    => 'Unbeorgan',
'vector-namespace-category'  => 'Flocc',
'vector-namespace-help'      => 'Helpe sīde',
'vector-namespace-image'     => 'Fīl',
'vector-namespace-main'      => 'Sīde',
'vector-namespace-media'     => 'Ȝemyndesīde',
'vector-namespace-mediawiki' => 'Ǣrend',
'vector-namespace-project'   => 'Ƿeorcsīde',
'vector-namespace-special'   => 'Syndriȝu sīde',
'vector-namespace-talk'      => 'Ȝespreċ',
'vector-namespace-template'  => 'Bysen',
'vector-namespace-user'      => 'Brūcendsīde',
'vector-view-edit'           => 'Ādihtan',
'vector-view-history'        => 'Stǣr sēon',
'vector-view-view'           => 'Rǣdan',
'vector-view-viewsource'     => 'Fruma sēon',
'actions'                    => 'Fremmendas',

'errorpagetitle'    => 'Ƿōh',
'returnto'          => 'To $1 eftgān.',
'tagline'           => 'Fram {{SITENAME}}n',
'search'            => 'Sēċan',
'searchbutton'      => 'Sēċan',
'go'                => 'Gān',
'searcharticle'     => 'Gān',
'history'           => 'Sīdan stǣr',
'history_short'     => 'Stǣr',
'info_short'        => 'Cȳþþu',
'printableversion'  => 'Ȝemǣlendliċu fadung',
'permalink'         => 'Fæst hlenċ',
'print'             => 'Ȝemǣlan',
'edit'              => 'Ādihtan',
'create'            => 'Scieppan',
'editthispage'      => 'Þās sīdan ādihtan',
'create-this-page'  => 'Þās sīdan scieppan',
'delete'            => 'āfeorsian',
'deletethispage'    => 'Þisne tramet āfeorsian',
'protect'           => 'Beorgan',
'protect_change'    => 'hƿeorfan',
'protectthispage'   => 'Þās sīdan beorgan',
'unprotect'         => 'Unbeorgan',
'unprotectthispage' => 'Þās sīdan unbeorgan',
'newpage'           => 'Nīƿu sīde',
'talkpage'          => 'Þās sīdan ymbspreċan',
'talkpagelinktext'  => 'Ȝespreċ',
'specialpage'       => 'Syndriȝ',
'personaltools'     => 'Āgen tōl',
'postcomment'       => 'Nīƿe dǣl',
'articlepage'       => 'Innunga sīdan sēon',
'talk'              => 'Mōtung',
'toolbox'           => 'Tōlbox',
'imagepage'         => 'Scēawian biliþtramet',
'otherlanguages'    => 'Ōðera sprǣca',
'redirectedfrom'    => '(Edlǣded of $1)',
'redirectpagesub'   => 'Edlǣdungtramet',
'protectedpage'     => 'Geweardod tramet',
'jumpto'            => 'Forðgangan on:',
'jumptosearch'      => 'sēcan',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ymbe {{SITENAME}}n',
'aboutpage'            => 'Project:Ymbe',
'copyright'            => 'Innung biþ gefunden under $1.',
'currentevents'        => 'Efenealde belimpas',
'currentevents-url'    => 'Project:Efenealde belimpas',
'edithelp'             => 'Ādihtunge help',
'edithelppage'         => 'Help:Ādihtung',
'helppage'             => 'Help:Innung',
'mainpage'             => 'Hēafodsīde',
'mainpage-description' => 'Hēafodsīde',
'portal'               => 'Gemǣnscipe Ingang',
'portal-url'           => 'Project:Gemǣnscipe Ingang',
'privacy'              => 'Ānlīepnesse þēaw',

'versionrequired' => 'Fadunge $1 þæs MediaWicis nēodaþ',

'retrievedfrom'           => 'Gefangen fram "$1"',
'youhavenewmessages'      => 'Þu hæfst $1 ($2).',
'newmessageslink'         => 'nīwu ǣrendgewritu',
'newmessagesdifflink'     => 'nīwoste wendung',
'youhavenewmessagesmulti' => 'Þu hæfst nīwu ǣrendu on $1',
'editsection'             => 'ādihtan',
'editold'                 => 'ādihtan',
'viewsourceold'           => 'Sēon andweorc',
'editlink'                => 'Ādihtan',
'viewsourcelink'          => 'Sēon andweorc',
'editsectionhint'         => 'Ādihtunge tōdǣlung',
'toc'                     => 'Innungbred',
'showtoc'                 => 'geswutelian',
'hidetoc'                 => 'hȳdan',
'feedlinks'               => 'Flōd:',
'red-link-title'          => '$1 (nā gīet gewriten)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Gewrit',
'nstab-user'     => 'Brūcendtramet',
'nstab-special'  => 'Syndriȝu sīde',
'nstab-project'  => 'Ƿeorces sīde',
'nstab-image'    => 'Mētung',
'nstab-template' => 'Bisen',
'nstab-help'     => 'Helptramet',
'nstab-category' => 'Flocc',

# Main script and global functions
'nosuchaction'      => 'Nān swilc dǣd',
'nosuchspecialpage' => 'Nān swilc syndrig tramet',

# General errors
'error'           => 'Gemearr',
'laggedslavemode' => 'Warnung: Tramet ne hæbbe recent updates.',
'internalerror'   => 'Inlic gemearr',
'filerenameerror' => 'Ne cúðe ednemnan þrǽd "$1" tó "$2".',
'filenotfound'    => 'Ne cūðe findan þrǣd "$1".',
'badarticleerror' => 'Þēos dǣd ne cann bēon gefremed on þissum tramete.',
'badtitle'        => 'Unandfenge títul',

# Login and logout pages
'logouttext'                 => "'''Þū eart nū ūtmeldod.'''

Þū canst ætfeolan tō brūcenne {{SITENAME}} ungecūðe, oþþe þū canst [[Special:UserLogin|inmeldian eft]] tō ylcan oþþe ōðrum brūcende.
Cnāw þæt sume sīdan cunnon gelǣstende ēowod wesan swā þū wǣre gīet inmeldod, oþ þæt þū clǣnsie þīnes sēcendtōles gemynd.",
'welcomecreation'            => '== Wilcume, $1! ==

Þín hordcleofa wearþ geseted.  Ne forgiet tó hwierfenne þína {{SITENAME}} foreberunga.',
'yourname'                   => 'Þīn brūcendnama',
'yourpassword'               => 'Þīn gelēafnesword',
'yourpasswordagain'          => 'Edwrītan gelēafnesword',
'yourdomainname'             => 'Þīn geweald',
'login'                      => 'Inmeldian',
'nav-login-createaccount'    => 'Settan nīwne hordcleofan oþþe inmeldian',
'userlogin'                  => 'Macian nīwne grīman oþþe grīman brūcan',
'userloginnocreate'          => 'Inmeldian',
'logout'                     => 'Ūtmeldian',
'userlogout'                 => 'Ūtmeldian',
'notloggedin'                => 'Ne ingemeldod',
'createaccount'              => 'Nīwne hordcleofan settan',
'gotaccountlink'             => 'Inmeldian',
'badretype'                  => 'Þá geléafnesword, þe þu write, ne efenlǽcaþ.',
'loginerror'                 => 'Inmeldunggemearr',
'loginsuccesstitle'          => 'Inmeldung gesǣlde',
'loginsuccess'               => "'''Þu eart nū inmeldod tō {{SITENAME}} swā \"\$1\".'''",
'nosuchuser'                 => 'Þǣr is nān brūcere be þǣm naman "$1".
Edscēawa þīne wrītunge, oþþe brūc þone form under tō [[Special:UserLogin/signup|settene nīwne brūcendhordcleofan]].',
'nosuchusershort'            => 'Þǣr is nān brūcend mid þǣm naman "<nowiki>$1</nowiki>".  Edscēawa on þīne wrītunge.',
'acct_creation_throttle_hit' => 'Hwæt, þu hæfst gēo geseted {{PLURAL:$1|1 hordcleofan|$1 -}}. Þu ne canst settan ǣnige māran.',
'accountcreated'             => 'Hordcleofan gescapen',
'loginlanguagelabel'         => 'Sprǣc: $1',

# Password reset dialog
'oldpassword' => 'Eald gelēafnesword:',
'newpassword' => 'Nīwe gelēafnesword',
'retypenew'   => 'Nīwe gelēafnesword edwrītan',

# Edit page toolbar
'bold_sample'     => 'Beald traht',
'bold_tip'        => 'Beald traht',
'italic_sample'   => 'Flōwende traht',
'italic_tip'      => 'Flōwende traht',
'link_sample'     => 'Bendtítul',
'link_tip'        => 'Inlic bend',
'extlink_sample'  => 'http://www.example.com bendtītul',
'extlink_tip'     => 'Ūtanweard bend (gemune http:// foredǣl)',
'headline_sample' => 'Hēafodlīnan traht',
'image_sample'    => 'Bisen.jpg',
'image_tip'       => 'Impod biliþ',
'media_sample'    => 'Bisen.ogg',
'sig_tip'         => 'Þín namansegn mid tídstempunge',

# Edit pages
'summary'                    => 'Scortnes:',
'minoredit'                  => 'Þēos is lȳtlu ādihtung',
'watchthis'                  => 'Þās sīde ƿæccan',
'savearticle'                => 'Sparian tramet',
'preview'                    => 'Forescēawian',
'showpreview'                => 'Forescēawian',
'whitelistedittitle'         => 'Inmeldunge behófod tó ádihtenne',
'whitelistedittext'          => 'Þu scealt $1 tó ádihtenne trametas.',
'loginreqlink'               => 'inmeldian',
'loginreqpagetext'           => 'Þū scealt $1 tō scēawienne view ōðre trametas.',
'accmailtitle'               => 'Gelēafnesword gesended.',
'accmailtext'                => "Þæt Gelēafnesword for '$1' wearþ gesend tō $2.",
'newarticle'                 => '(Nīwe)',
'newarticletext'             => "Þu hæfst bende tō tramete gefolgod þe nū gīet ne stendeþ.
Tō scieppene þone tramet, onginn þyddan in þǣre boxe under (sēo þone [[{{MediaWiki:Helppage}}|helptramet]] for mā gefrǣge).
Gif þu hider misfōn cōme, cnoca þā þīnne webbscēaweres '''on bæc''' cnæpp.",
'usercssyoucanpreview'       => "'''Rǣd:''' Brūc þone 'Forescēawian' cnæpp tō āfandienne þīne nīwe css/js beforan sparunge.",
'userjsyoucanpreview'        => "'''Rǣd:''' Brūc þone 'Forescēawian' cnæpp tō āfandienne þīne nīwe css/js beforan sparunge.",
'updated'                    => '(Ednīwod)',
'editing'                    => 'Ādihtend',
'editingsection'             => 'Ādihtend $1 (dǣl)',
'editingcomment'             => 'Ādihtung $1 (ymbsprǣc)',
'yourtext'                   => 'Þīn traht',
'editingold'                 => "'''WARNUNG: Þu ādihtest ealde fadunge þisses trametes.
Gif þu hine sparie, ǣniga onwendunga gemacod siþþan þisse fadunge bēoþ sōðes forloren.'''",
'yourdiff'                   => 'Tōdǣlednessa',
'copyrightwarning2'          => "Bidde macie nōt þæt ealla forðunga tō {{SITENAME}}
mæg bēon ādihted, gewended, oþþe āfyrðed fram ōðrum forðerum.
Gif þu nelt þīne wrītunge tō bēonne ādihtod unmildheortlīce, þonne ne þafie hīe hēr.<br />
Þu behǣtst ēac þæt þu selfa write þis, oþþe efenlǣhtest of sumre
gemǣnscipes āgnunge oþþe gelīcum frēom horde (sēo $1 for āscungum).
'''NE ÞAFIE EFENLǢHTSCIELDED WEORC BŪTAN GELĪEFNESSE!'''",
'longpagewarning'            => 'WARNUNG: Þes tramet is $1 kilobyta lang; sume
webbscēaweras hæbben earfoðu mid þȳ þe hīe ādihtaþ trametas nēa oþþe lengran þonne 32kb.
Bidde behycge þæt þu bricst þone tramet intō smalrum dǣlum.',
'templatesused'              => 'Onȝelīċnessa ȝebrȳcda on þissum tramete:',
'template-protected'         => '(geborgen)',
'template-semiprotected'     => '(sāmborgen)',
'nocreatetitle'              => 'Gewrit nā gefunden',
'recreate-moveddeleted-warn' => "'''Warnung: Þu edsciepst tramet þe wæs ǣr āfeorsod.'''

Þu sceoldest smēagan, hwæðer hit gerādlic sīe, forþ tō gānne mid ādihtunge þisses trametes.
Þæt āfeorsungbred þisses trametes is hēr geīeht for behēfnesse:",

# History pages
'nohistory'              => 'Nis nān ādihtungstǣr for þissum tramete.',
'revisionasof'           => 'Nīƿung fram',
'next'                   => 'nīehst',
'history-fieldset-title' => 'Stǣr sēċan',
'histfirst'              => 'Ǣrest',
'histlast'               => 'Endenīehst',

# Revision deletion
'rev-delundel' => 'scēawian/hȳdan',

# Merge log
'revertmerge' => 'Unȝeþēodan',

# Diffs
'difference'              => '(Scēadung betwēonan hweorfungum)',
'lineno'                  => 'Līne $1:',
'compareselectedversions' => 'Geefnettan gecorena fadunga',
'editundo'                => 'undōn',

# Search results
'searchresults'            => 'Sōcnfintan',
'searchsubtitle'           => "Þū sōhtest '''[[:$1]]'''",
'searchsubtitleinvalid'    => "Þu sōhtest '''$1'''",
'notextmatches'            => 'Nāne trametrahtes mæccan',
'nextn'                    => 'nīehst {{PLURAL:$1|$1}}',
'searchhelp-url'           => 'Help:Innung',
'search-interwiki-caption' => 'Sƿeostorƿeorc',
'showingresults'           => 'Īewan under oþ <b>$1</b> tōhīgunga onginnenda mid #<b>$2</b>.',
'showingresultsnum'        => 'Under sind <b>$3</b> tóhígunga onginnende mid #<b>$2</b>.',
'powersearch'              => 'Sēcan',

# Preferences page
'preferences'        => 'Foreberunga',
'mypreferences'      => 'Mīna foreberunga',
'prefsnologin'       => 'Ne ingemeldod',
'prefs-skin'         => 'Scynn',
'skin-preview'       => 'Forescēawian',
'prefs-datetime'     => 'Tælmearc and tīd',
'prefs-rc'           => 'Nīwlica hweorfunga',
'prefs-watchlist'    => 'Behealdnestalu',
'saveprefs'          => 'Sparian',
'rows'               => 'Rǣwa',
'columns'            => 'Sȳla:',
'searchresultshead'  => 'Sōcnfintan',
'resultsperpage'     => 'Tōhīgunga tō īewenne for tramete',
'contextlines'       => 'Līnan tō īewenne in tōhīgunge',
'recentchangescount' => 'Tītula getæl in nīwlicum hweorfungum',
'savedprefs'         => 'Þīna foreberunga wurdon gesparod.',
'timezonelegend'     => 'Tīdgyrtel',
'servertime'         => 'Bryttantīma is nū',
'defaultns'          => 'Sēcan in þissum namstedum be frambyge:',
'default'            => 'gewunelic',
'youremail'          => 'E-ǣrende *',
'username'           => 'Brūcendnama:',
'yourrealname'       => 'Þīn rihtnama*',
'yourlanguage'       => 'Brūcendofermearces sprǣc',
'yourvariant'        => 'Sprǣce wendung',

# User rights
'editusergroup'           => 'Ādihtan Brūcendsamþrēatas',
'saveusergroups'          => 'Sparian Brūcendsamþrēatas',
'userrights-groupsmember' => 'Geglida þæs:',

# Recent changes
'nchanges'        => '$1 hwierfunga',
'recentchanges'   => 'Nīwlica hweorfunga',
'rcnote'          => "Under {{PLURAL:$1|... '''1''' ...|sind þā æftemestan '''$1''' hweorfunga}} in {{PLURAL:$2|...|þǣm æftemestum '''$2''' dagum}}, . . $5, $4.",
'rcnotefrom'      => 'Under sind þā hweorfunga siþþan <b>$2</b> (oþ <b>$1</b> geīewed).',
'rclistfrom'      => 'Īewan nīwa hweorfunga, onginnenda of $1',
'rcshowhideminor' => '$1 lȳtla ādihtunga',
'rcshowhideliu'   => '$1 inmeldode brūcend',
'rcshowhideanons' => '$1 unnemnode brūcend',
'rcshowhidemine'  => '$1 mīna ādihtunga',
'rclinks'         => 'Īewan æftemestan $1 hweorfunga in æftemestum $2 dagum<br />$3',
'diff'            => 'scēa',
'hist'            => 'Stǣr',
'hide'            => 'hȳdan',
'show'            => 'īewan',

# Recent changes linked
'recentchangeslinked'         => 'Sibba hweorfunga',
'recentchangeslinked-feed'    => 'Sibba hweorfunga',
'recentchangeslinked-toolbox' => 'Sibba hweorfunga',
'recentchangeslinked-page'    => 'Sīdenama:',

# Upload
'upload'        => 'Fīl forþsendan',
'uploadnologin' => 'Ne inmeldod',
'filename'      => 'Þrǣdnama',
'filedesc'      => 'Scortnes',
'filesource'    => 'Fruma:',
'badfilename'   => 'Onlīcnesnama wearþ gewend tō "$1(e/an)".',
'savefile'      => 'Sparian þrǣd',

'nolicense' => 'Nǣnne gecorenne',

# Special:ListFiles
'listfiles_search_for'  => 'Sēcan biliþnaman:',
'listfiles'             => 'Biliþgetalu',
'listfiles_date'        => 'Tælmearc',
'listfiles_name'        => 'Nama',
'listfiles_user'        => 'Brūcend',
'listfiles_description' => 'Tōwritennes',

# File description page
'file-anchor-link' => 'Mētung',
'filehist-user'    => 'Brūcend',
'imagelinks'       => 'Biliþhlenċan',
'linkstoimage'     => 'Þā folgendan trametas bindaþ tō þissum biliðe:',
'nolinkstoimage'   => 'Þǣr sind nāne trametas þe bindaþ tō þissum biliðe.',

# File deletion
'filedelete-submit' => 'āfeorsian',

# Unused templates
'unusedtemplateswlh' => 'ōðere bendas',

# Random page
'randompage' => 'Hlīetlic tramet',

'doubleredirects' => 'Twifealdlice Ymblǣderas',

'brokenredirects'        => 'Gebrocene Ymblǣderas',
'brokenredirectstext'    => 'Þā folgendan edlǣdunga bendaþ tō unedwistlicum trametum.',
'brokenredirects-edit'   => 'ādihtan',
'brokenredirects-delete' => 'āfeorsian',

'withoutinterwiki'         => 'Trametas būtan sprǣcbendum',
'withoutinterwiki-summary' => 'Þā folgendan trametas ne bindaþ tō ōðrum sprǣcfadungum:',

# Miscellaneous special pages
'ncategories'          => '$1 {{PLURAL:$1|flocca|floccas}}',
'nlinks'               => '$1 bendas',
'specialpage-empty'    => 'Þis tramet is ǣmtig.',
'lonelypages'          => 'Ealdorlēase trametas',
'unusedimages'         => 'Ungebrȳcodu biliðu',
'popularpages'         => 'Dēore trametas',
'wantedcategories'     => 'Gewilnode floccas',
'wantedpages'          => 'Gewilnode trametas',
'mostlinked'           => 'Gebundenostan trametas',
'mostlinkedcategories' => 'Gebundenostan floccas',
'mostlinkedtemplates'  => 'Gebundenostan bysena',
'shortpages'           => 'Scorte trametas',
'longpages'            => 'Lange trametas',
'newpages'             => 'Nīwe trametas',
'newpages-username'    => 'Brūcendnama:',
'ancientpages'         => 'Ieldestan Trametas',
'move'                 => 'Gān',

# Book sources
'booksources'               => 'Bōcfruman',
'booksources-search-legend' => 'Sēcan bōcfruman',
'booksources-go'            => 'Gān',
'booksources-text'          => 'Under is getalu benda tō ōðrum webstedum þe bebycgaþ nīwa and gebrocena bēc, and hæbben 
ēac mā āscunga ymbe bēc þe þu sēcst:',

# Special:Log
'specialloguserlabel'  => 'Brūcend:',
'speciallogtitlelabel' => 'Titul:',
'log'                  => 'Cranicas',

# Special:AllPages
'allpages'       => 'Trametas',
'alphaindexline' => '$1 tō $2',
'nextpage'       => 'Nīehsta tramet ($1)',
'allarticles'    => 'Eall gewritu',
'allinnamespace' => 'Ealle trametas ($1 namanstede)',
'allpagesprev'   => 'Beforan',
'allpagesnext'   => 'Nīehst',
'allpagessubmit' => 'Gān',

# Special:Categories
'categories'         => 'Floccas',
'categoriespagetext' => 'Þā folgendan floccas standaþ in þǣm wici.',

# Special:ListUsers
'listusers-noresult' => 'Nǣnne brūcend gefundenne.',

# Special:Log/newusers
'newuserlogpage'          => 'Brūcend ġesceaft talu',
'newuserlog-create-entry' => 'Nīwe brūcend',

# E-mail user
'emailfrom'     => 'Fram',
'emailto'       => 'Tō:',
'emailsubject'  => 'Forþsetennes',
'emailmessage'  => 'Ǣrendgewrit',
'emailsend'     => 'Ǣrendian',
'emailsent'     => 'E-mail gesend',
'emailsenttext' => 'Þīn e-mail ǣrendgewrit wearþ gesend.',

# Watchlist
'watchlist'         => 'Mīn behealdnestalu',
'mywatchlist'       => 'Mīn behealdnestalu',
'addedwatch'        => 'Geīeht tō wæcctale',
'watch'             => 'Behealdan',
'unwatch'           => 'Unbehealdan',
'watchlistcontains' => 'Þīn behealdnestalu hæfþ $1 {{PLURAL:$1|trameta|trametas}} inn.',
'wlnote'            => 'Under sind þā æftemestan $1 hweorfunga in þǣm æftemestum <b>$2</b> stundum.',
'wlshowlast'        => 'Īewan æftemestan $1 stunda $2 daga $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Behealdende...',
'unwatching' => 'Unbehealdende...',

'enotif_newpagetext' => 'Þēs is nīwe tramet.',
'created'            => 'gescapen',

# Delete
'deletepage'      => 'Āfeorsian tramet',
'excontent'       => "innung wæs: '$1'",
'excontentauthor' => "innung wæs: '$1' (and se āna forðiend wæs '[[Special:Contributions/$2|$2]]')",
'exblank'         => 'tramet wæs ǣmtig',
'historywarning'  => 'Warnung: Se tramet, þone þu āfeorsian teohhast, hæfþ stǣre:',
'actioncomplete'  => 'Weorcdǣd geendod',
'deletedarticle'  => 'āfeorsod "[[$1]]"',
'dellogpage'      => 'Āfeorsunge_wisbōc',
'deletionlog'     => 'āfeorsunge wisbōc',
'deletecomment'   => 'Racu for āfeorsunge',

# Rollback
'rollback_short' => 'Edhwierfan',
'rollbacklink'   => 'Edhwierfan',
'rollbackfailed' => 'Edhwierft misfangen',
'editcomment'    => "Sēo ādihtungymbsprǣc wæs: \"''\$1''\".",
'revertpage'     => 'Ācierde ādihtunga fram [[Special:Contributions/$2|$2]] ([[User talk:$2|Gesprec]]); wendede on bæc tō ǣrran fadunge fram [[User:$1|$1]]',

# Protect
'unprotectedarticle'     => 'unweardod "[[$1]]"',
'protect-title'          => 'Weardiende "$1"',
'prot_1movedto2'         => '[[$1]] gefered tō [[$2]]',
'protect-expiry-options' => '1 stund:1 hour,1 dæg:1 day,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 gēar:1 year,unendiendlic:infinite',
'restriction-type'       => 'Gelēafnes:',

# Restrictions (nouns)
'restriction-edit'   => 'Ādihtan',
'restriction-move'   => 'Gān',
'restriction-create' => 'Scieppan',

# Undelete
'undeletebtn'            => 'Edstaðola!',
'undeletedarticle'       => 'edstaðolod "[[$1]]"',
'undelete-search-submit' => 'Sēcan',

# Namespace form on various pages
'namespace' => 'Namanstede:',

# Contributions
'contributions' => 'Brūcendforðunga',
'mycontris'     => 'Mīna forðunga',
'uctop'         => '(hēafod)',
'month'         => 'Fram mōnþe (and ǣror)',
'year'          => 'Fram ȝēare (and ǣror)',

'sp-contributions-talk'   => 'Gesprec',
'sp-contributions-submit' => 'Sēcan',

# What links here
'whatlinkshere'       => 'Hwæt bindaþ hider',
'whatlinkshere-page'  => 'Tramet:',
'linkshere'           => 'Þā folgendan trametas bindaþ hider:',
'nolinkshere'         => 'Nāne trametas bindaþ hider.',
'isredirect'          => 'edlǣdungtramet',
'isimage'             => 'biliþbend',
'whatlinkshere-links' => '← bendas',

# Block/unblock
'ipbreason'          => 'Racu',
'ipbreasonotherlist' => 'Ōðeru racu',
'ipbreason-dropdown' => '*Gemǣna gǣlungraca
** Insettung falses gefrǣges
** Āfēorsung innunge of trametum
** Spamming benda tō ūtanweardum webbstedum
** Insettung gedofes/dwolunge intō trametum
** Þrǣstiendlicu gebǣrnes/tirgung
** Miswendung manigfealdra brūcendhorda
** Uncwēme brūcendnama',
'ipbsubmit'          => 'Gǣlan þisne brūcend',
'ipbother'           => 'Ōðeru tīd',
'ipboptions'         => '1 stund:1 hour, 2 stunda:2 hours,1 dæg:1 day,3 dagas:3 days,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 gēar:1 year,unendiendlic:infinite',
'ipbotheroption'     => 'ōðer',
'ipbotherreason'     => 'Ōðeru/geīecendlicu racu:',
'ipblocklist-submit' => 'Sēcan',
'infiniteblock'      => 'unendiendlic',
'expiringblock'      => 'forealdaþ $1 $2',
'contribslink'       => 'forðunga',
'proxyblocksuccess'  => 'Gedōn.',

# Move page
'movearticle'     => 'Geferan tramet',
'newtitle'        => 'Tō nīwum tītule',
'articleexists'   => 'Tramet on þǣm naman ǣr stendeþ, oþþe þone
naman þu cēas nis andfenge.
Bidde cēos ōðerne naman.',
'movedto'         => 'gefered tō',
'1movedto2'       => '[[$1]] gefered tō [[$2]]',
'1movedto2_redir' => '[[$1]] gefered tō [[$2]] ofer edlǣdunge',
'movereason'      => 'Racu:',
'revertmove'      => 'Undōn',

# Namespace 8 related
'allmessagesname'    => 'Nama',
'allmessagesdefault' => 'Fūslic traht',
'allmessagescurrent' => 'Genge traht',

# Thumbnails
'thumbnail-more' => 'Gebrǣdan',
'filemissing'    => 'Þrǣd unandweard',

# Special:Import
'import'        => 'Trametas inbringan',
'importfailed'  => 'Inbringung tōsǣlede: $1',
'importnotext'  => 'Ǣmtig oþþe nān traht',
'importsuccess' => 'Geinnung gesǣled!',

# Tooltip help for the actions
'tooltip-pt-userpage'     => 'Mīnu brūcendsīde',
'tooltip-pt-mytalk'       => 'Þīn gesprece sīde',
'tooltip-pt-preferences'  => 'Mīna foreberunga',
'tooltip-pt-mycontris'    => 'Ȝetalu mīnra forðunga',
'tooltip-pt-logout'       => 'Ūtmeldian',
'tooltip-ca-addsection'   => 'Nīƿe dǣl beȝinnan',
'tooltip-ca-history'      => 'Forþgewitena fadunga þisses trametes.',
'tooltip-search'          => 'Sēcan {{SITENAME}}',
'tooltip-search-fulltext' => 'Þā sīdan sēċan mid þissum trahte',
'tooltip-p-logo'          => 'Hēafodsīde',
'tooltip-n-currentevents' => 'Findan stǣrlice gewitnesse ymb nīwa gelimpunga',
'tooltip-minoredit'       => 'Mearcian þis swā lȳtle ādihtunge',
'tooltip-save'            => 'Sparian þīna onwendunga',
'tooltip-preview'         => 'Forescēawa þīne āwendednessa, bidde brūc þis ǣr þǣm þe þu sparast! [alt-p]',

# Attribution
'anonymous' => 'Namcūþlēas(e) brūcend {{SITENAME}}n',
'siteuser'  => '{{SITENAME}}n brūcere $1',
'others'    => 'ōðru',

# Info page
'numedits'     => 'Ádihtunga tæl (gewrit): $1',
'numtalkedits' => 'Rīm ādihtunga (mōtungtramet): $1',
'numwatchers'  => 'Scēawera tæl: $1',

# Math errors
'math_unknown_error' => 'ungewiss gemearr',

# Media information
'imagemaxsize' => 'Settan biliðu on biliþgemearcungtrametum tō:',
'thumbsize'    => 'Þumannæglmicelnes:',

# Special:NewFiles
'imagelisttext' => 'Under is getalu $1 biliða gedæfted $2.',
'noimages'      => 'Nāht tō sēonne.',
'ilsubmit'      => 'Sēcan',
'bydate'        => 'be tælmearce',

# EXIF tags
'exif-imagewidth'       => 'Wīdu',
'exif-compression'      => 'Geþryccungwīse',
'exif-ycbcrpositioning' => 'Y and C gesetednes',
'exif-imagedescription' => 'Biliðes tītul',
'exif-artist'           => 'Fruma',
'exif-usercomment'      => 'Brūcendes trahtnunga',
'exif-exposuretime'     => 'Blicestīd',
'exif-brightnessvalue'  => 'Beorhtnes',
'exif-lightsource'      => 'Lēohtfruma',
'exif-whitebalance'     => 'Hwītefnetta',
'exif-sharpness'        => 'Scearpnes',
'exif-gpslatituderef'   => 'Norþ oþþe Sūþ Brǣdu',
'exif-gpslatitude'      => 'Brǣdu',
'exif-gpslongituderef'  => 'Ēast oþþe West Lengu',
'exif-gpslongitude'     => 'Lengu',
'exif-gpsmeasuremode'   => 'Metungwīse',
'exif-gpsimgdirection'  => 'Rihtung þæs biliðes',

# EXIF attributes
'exif-compression-1' => 'Ungeþrycced',

'exif-lightsource-1' => 'Dægeslēoht',

'exif-focalplaneresolutionunit-2' => 'yncas',

'exif-exposuremode-1' => 'Handlic blice',

'exif-whitebalance-0' => 'Selfgedōn hwītefnetta',

'exif-contrast-1' => 'Sōfte',
'exif-contrast-2' => 'Heard',

'exif-sharpness-1' => 'Sōfte',
'exif-sharpness-2' => 'Heard',

'exif-subjectdistancerange-2' => 'Nēa hāwung',
'exif-subjectdistancerange-3' => 'Feorr hāwung',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norþ brǣdu',
'exif-gpslatitude-s' => 'Sūþ brǣdu',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ēast lengu',
'exif-gpslongitude-w' => 'West lengu',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sōþ rihtung',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'eall',
'imagelistall'     => 'eall',
'watchlistall2'    => 'eall',
'namespacesall'    => 'eall',
'monthsall'        => 'eall',

# E-mail address confirmation
'confirmemail_body' => 'Hwilchwega, gewēne þu of IP stōwe $1, hæfþ in namanbēc gestt ǣnne hordcleofan
"$2" mid þissum e-ǣrendes naman on {{SITENAME}}n.

Tō āsēðenne þæt þes hordcleofa tō þē gebyraþ and tō openienne
e-ǣrenda hwilcnessa on {{SITENAME}}n, opena þisne bend in þīnum webbscēawere:

$3

Gif þis is *nā* þū, ne folga þisne bend.

$5

Þēos āsēðungrūn forealdaþ æt $4.',

# Scary transclusion
'scarytranscludefailed'  => '[Bisenfeccung getrucod for $1; sarig]',
'scarytranscludetoolong' => '[URL is tō lang; sarig]',

# Multipage image navigation
'imgmultigo' => 'Gān!',

# Table pager
'table_pager_first'        => 'Forma tramet',
'table_pager_last'         => 'Hindemesta tramet',
'table_pager_limit_submit' => 'Gān',

# Auto-summaries
'autosumm-new' => 'Nīwe tramet: $1',

# Special:Version
'version'       => 'Fadung',
'version-other' => 'Ōðer',

# Special:SpecialPages
'specialpages' => 'Syndrige trametas',

);
