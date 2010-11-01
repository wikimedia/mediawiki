<?php
/** Old English (Ænglisc)
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
'tog-underline'               => 'Hlenċa undermearcian:',
'tog-highlightbroken'         => 'Sete unfulfylede hlencan <a href="" class="new">sƿā þis</a> (þīn ōðer cyre is tō habbenne hīe sƿā þis:<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Macian cwidfloccas riht',
'tog-hideminor'               => 'Lytla ādihtas hȳdan in nīƿum hƿeorfum',
'tog-hidepatrolled'           => 'Ƿeardede ādihtas hȳdan in nīƿe hƿearfas',
'tog-newpageshidepatrolled'   => 'Ƿeardede sīdan hȳdan in nīƿe hƿearfas',
'tog-extendwatchlist'         => 'Ƿæccȝetale sprædan to scēaƿenne ealle hƿearfas, ne efne þā nīƿoste',
'tog-usenewrc'                => 'Benota forðod ȝetæl nīƿra hƿeorfunȝa (þis þearf JavaScript)',
'tog-numberheadings'          => 'Selffremmende-rīm hēafodingas',
'tog-showtoolbar'             => 'Þone tōlstæf scēaƿian (þearf JavaScript)',
'tog-editondblclick'          => 'Sīdan ādihtan bȳ tƿicnæppende (þearf JavaScript)',
'tog-editsection'             => 'Dǣla ādihtende þafian bȳ [ādihtan] hlenċum',
'tog-editsectiononrightclick' => 'Þafa dǣla ādihtune þurh sƿenȝas þǣre sƿīðran healfe on dǣla titulum (þis þearf JavaScript)',
'tog-showtoc'                 => 'Innoðes tæfle sēon (for sīdum þe mā þonne 3 hēafodingas habbaþ)',
'tog-rememberpassword'        => 'Mīne inmeldunge ȝemyndan on þissum spearcatelle (oþ $1 {{PLURAL:$1|dæȝ|dagas}})',
'tog-watchcreations'          => 'Sīdan þe iċ scieppe ēacian tō mīnre ƿæccȝetale',
'tog-watchdefault'            => 'Sīdan þe iċ ādihte ēacian tō mīnre ƿæccȝetale',
'tog-watchmoves'              => 'Sīdan þe iċ hƿeorfe ēacian tō mīnre ƿæccȝetale',
'tog-watchdeletion'           => 'Sīdan þe iċ forlēose ēacian tō mīnre ƿæccȝetale',
'tog-previewontop'            => 'Forescēaƿe sēon fore ādihtbox',
'tog-previewonfirst'          => 'Forescēaƿe sēon on formestum ādihte',
'tog-nocache'                 => 'Ne þafa trameta settunȝe',
'tog-enotifwatchlistpages'    => 'Send mē spearccræftiȝ ǣrend þǣr tramet on mīnum ƿæccȝetæle ƿierþ andƿended',
'tog-enotifusertalkpages'     => 'Send mē spearccræftiȝ ǣrend þǣr mīn brūcendtramet is andƿended',
'tog-enotifminoredits'        => 'Send mē spearccræftiȝ ǣrend þǣr trametas sind efne lytellīce andƿended',
'tog-enotifrevealaddr'        => 'Ēoƿa mīn spearccræftiȝra ǣrenda stōƿnaman on sprearccræftiȝum ȝecȳðendum ǣrendum',
'tog-shownumberswatching'     => 'Hū mæniȝ ƿæccende brūcendas sēon',
'tog-oldsig'                  => 'Foresihþ þæs selftācnes þe is nū ȝenotod:',
'tog-fancysig'                => 'Dō mid þissum selftācne sƿā mid Ƿikitext (lēas ǣr ȝedōnes hlencan)',
'tog-externaleditor'          => 'Nota ūtƿeardne ādihttōl tō ȝeƿunelicre ȝesetednesse (synderlīce tō sƿīðe cræftiȝum mannum - þearf ānlica ȝesetednessa on þīnum spearctelle)',
'tog-externaldiff'            => 'Nota ūtƿearde scādunȝe tō ȝeƿunelicre ȝesetednesse (synderlīce tō sƿīðe cræftiȝum mannum - þearf ānlica ȝesetednesse on þīnum spearctelle)',
'tog-showjumplinks'           => 'Lǣt "ȝā tō" ȝefēre hlencan',
'tog-uselivepreview'          => 'Nota andefene foresihþe (þearf JavaScript) (tō costnunȝe)',
'tog-forceeditsummary'        => 'Scyhte mē þǣr ic inƿrīte nāne ādihtsceortnesse',
'tog-watchlisthideown'        => 'Mīna ādihtunga hȳdan ƿiþ þā ƿæccȝetale',
'tog-watchlisthidebots'       => 'Searuþrala ādihtas hȳdan ƿiþ þā ƿæccȝetale',
'tog-watchlisthideminor'      => 'Lȳtl ādihtas hȳdan ƿiþ þā ƿæccȝetale',
'tog-watchlisthideliu'        => 'Ādihtas bȳ inmeldedum brūcendum hȳdan ƿiþ þā ƿæccȝetale',
'tog-watchlisthideanons'      => 'Hȳd ādihtas fram uncūðum brūcendum ƿiþ þæt ƿæccȝetæl',
'tog-watchlisthidepatrolled'  => 'Hȳd ƿeardode ādihtas ƿiþ þæt ƿæccȝetæl',
'tog-nolangconversion'        => 'Ne lǣt missenlicnessa æfter āwendungum',
'tog-ccmeonemails'            => 'Send mē ȝelīcnessa þāra spearcræftiȝena ǣrenda þe ic ōðrum brūcendum sende',
'tog-diffonly'                => 'Ne ēoƿa sīdan innunȝe under scādunȝum',
'tog-showhiddencats'          => 'Ēoƿa ȝehȳdede floccas',
'tog-noconvertlink'           => 'Ne lǣt hlencena titula āwendunge',
'tog-norollbackdiff'          => 'Forlǣt scādunȝe æfter edƿeorc is ȝedōn',
'tog-minordefault'           => 'Ealle ādihtende mearcian tōlas ȝeƿunelīċe',

'underline-always'  => 'Ǣfre',
'underline-never'   => 'Nǣfre',
'underline-default' => 'Ƿebbsēcendes ȝeƿunelic ȝesetedness',

# Font style option in Special:Preferences
'editfont-style'     => 'Stæfcynd for þǣre ƿrītunȝe on þǣm ādihtearce:',
'editfont-default'   => 'Ƿebbsēcendes ȝeƿunelic ȝesetedness',
'editfont-monospace' => 'Ānbrǣded stæfcynd',
'editfont-sansserif' => 'Tæȝellēas stæfcynd',
'editfont-serif'     => 'Tæȝelbǣr stæfcynd',

# Dates
'sunday'        => 'Sunnandæȝ',
'monday'        => 'Mōnandæȝ',
'tuesday'       => 'Tīƿesdæȝ',
'wednesday'     => 'Ƿēdnesdæȝ',
'thursday'      => 'Þunresdæȝ',
'friday'        => 'Frīȝedæȝ',
'saturday'      => 'Sæterndæȝ',
'sun'           => 'Sun',
'mon'           => 'Mōn',
'tue'           => 'Tīƿ',
'wed'           => 'Ƿēd',
'thu'           => 'Þun',
'fri'           => 'Frī',
'sat'           => 'Sæt',
'january'       => 'Æfterra Ȝēola',
'february'      => 'Solmōnaþ',
'march'         => 'Hrēþmōnaþ',
'april'         => 'Ēostremōnaþ',
'may_long'      => 'Þrimilcemōnaþ',
'june'          => 'Sēarmōnaþ',
'july'          => 'Mǣdmōnaþ',
'august'        => 'Ƿēodmōnaþ',
'september'     => 'Hāliȝmōnaþ',
'october'       => 'Ƿinterfylleþ',
'november'      => 'Blōtmōnaþ',
'december'      => 'Ǣrra Ȝēola',
'january-gen'   => 'Æfterran Ȝēolan',
'february-gen'  => 'Solmōnþes',
'march-gen'     => 'Hrēþmōnþes',
'april-gen'     => 'Ēostremōnþes',
'may-gen'       => 'Þrimilcemōnþes',
'june-gen'      => 'Sēarmōnþes',
'july-gen'      => 'Mǣdmōnþes',
'august-gen'    => 'Ƿēodmōnþes',
'september-gen' => 'Hāliȝmōnþes',
'october-gen'   => 'Ƿinterfylleðes',
'november-gen'  => 'Blōtmōnþes',
'december-gen'  => 'Ǣrran Ȝēolan',
'jan'           => 'Ǣr Ȝē',
'feb'           => 'Sol',
'mar'           => 'Hrē',
'apr'           => 'Ēos',
'may'           => 'Þri',
'jun'           => 'Sēr',
'jul'           => 'Mǣd',
'aug'           => 'Ƿēo',
'sep'           => 'Hāl',
'oct'           => 'Ƿinfyl',
'nov'           => 'Blō',
'dec'           => 'Æf Ȝē',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Flocc|Floccas}}',
'category_header'                => 'Trametas in flocce "$1"',
'subcategories'                  => 'Underfloccas',
'category-media-header'          => 'Missenmiddel in flocce "$1"',
'category-empty'                 => "''Þes flocc hæfþ nū nǣnȝu ȝeƿritu oþþe missenmiddel.''",
'hidden-categories'              => '{{PLURAL:$1|Ȝehȳded flocc|$1 Ȝehȳdede floccas}}',
'hidden-category-category'       => 'Ȝehȳdede floccas',
'category-subcat-count'          => '{{PLURAL:$2|Þes flocc hæfþ efne þone folȝiendan underflocc.|Þes flocc hæfþ {{PLURAL:$1|þone folȝiendan underflocc|$1 þā folȝiendan underfloccas}}, þāra fullena $2.}}',
'category-subcat-count-limited'  => 'Þes flocc hæfþ {{PLURAL:$1|þisne underflocc|$1 þās underfloccas}}.',
'category-article-count'         => '{{PLURAL:$2|Þes flocc hæfþ efne þā folȝiendan āne sīdan.|{{PLURAL:$1|Sēo folȝiende sīde is|$1 Þā folȝiendan sīdan sind}} in þissum flocce, þāra fullena $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Se folȝienda tramet is|$1 Þā folȝiendan trametas sind}} on þissum flocce hēr.',
'category-file-count'            => ' {{PLURAL:$2|Þes flocc hæfþ efne þæt folȝiende ȝeƿithord.|{{PLURAL:$1|Þæt folȝiende ȝeƿithord is|$1 Þā folȝiendan ȝeƿithord sind}} in þissum flocce, þāra fullena $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Þis ȝeƿithord is|$1 Þās ȝeƿithord sind}} in þissum flocce hēr.',
'listingcontinuesabbrev'         => 'mā',
'index-category'                 => 'Ȝebēacniende trametas',
'noindex-category'               => 'Unȝebēacniende trametas',

'mainpagetext'      => "'''MediaǷiki hafaþ ȝeƿorden spēdiȝe inseted.'''",
'mainpagedocfooter' => 'Þeahta þone [http://meta.wikimedia.org/wiki/Help:Contents Brūcenda Lǣdend]  on helpe mid þǣre nytte of ƿikisōftƿare.

== Beȝinnunȝ ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Onfæstnunȝa ȝesetednessa ȝetæl]
* [http://www.mediawiki.org/wiki/Manual:FAQ Ȝetæl oft ascodra ascunȝa ymb MediaǷiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Ǣrendunȝȝetæl nīƿra MediaǷiki forþsendnessa]',

'about'         => 'Ymbe',
'article'       => 'Innunȝsīde',
'newwindow'     => '(openaþ in nīƿum ēaȝþyrelum)',
'cancel'        => 'Undō',
'moredotdotdot' => 'Mā...',
'mypage'        => 'Mīn sīde',
'mytalk'        => 'Mīnu ȝespreċ',
'anontalk'      => 'Þisses IP stōƿnaman talu',
'navigation'    => 'Þurhfōr',
'and'           => '&#32;and',

# Cologne Blue skin
'qbfind'         => 'Find',
'qbbrowse'       => 'Onbirȝe',
'qbedit'         => 'Ādihte',
'qbpageoptions'  => 'Þēos sīde',
'qbpageinfo'     => 'Ȝeƿef',
'qbmyoptions'    => 'Mīna sīdan',
'qbspecialpages' => 'Syndriȝa sīdan',
'faq'            => 'Oftost ascoda ascunȝa',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Mearcunge ēacian',
'vector-action-delete'           => 'Forlēos',
'vector-action-move'             => 'Ƿeȝ',
'vector-action-protect'          => 'Beorȝa',
'vector-action-undelete'         => 'Sciepe tramet eft',
'vector-action-unprotect'        => 'Unbeorȝa',
'vector-simplesearch-preference' => 'Lǣt forðoda sēcunge tōtyhtinga (synderlīce for Vector scinne)',
'vector-view-create'             => 'Sciepe',
'vector-view-edit'               => 'Ādihte',
'vector-view-history'            => 'Stǣr',
'vector-view-view'               => 'Rǣd',
'vector-view-viewsource'         => 'Sēo fruman',
'actions'                        => 'Fremmunga',
'namespaces'                     => 'Namstedas',
'variants'                       => 'Missenlicnessa',

'errorpagetitle'    => 'Ƿōh',
'returnto'          => 'Ȝā eft tō $1',
'tagline'           => 'Fram {{SITENAME}}',
'help'              => 'Help',
'search'            => 'Sēc',
'searchbutton'      => 'Sēc',
'go'                => 'Gā',
'searcharticle'     => 'Gān',
'history'           => 'Sīdan stǣr',
'history_short'     => 'Stǣr',
'updatedmarker'     => 'nīƿod æfter ic cōm hider ǣror',
'info_short'        => 'Cȳþþu',
'printableversion'  => 'Ūtmǣlendlicu fadunȝ',
'permalink'         => 'Fæst hlenċe',
'print'             => 'Ūtmǣl',
'edit'              => 'Ādiht',
'create'            => 'Sciepe',
'editthispage'      => 'Ādiht þās sīdan',
'create-this-page'  => 'Sciepe þās sīdan',
'delete'            => 'Forlēos',
'deletethispage'    => 'Forlēos þās sīdan',
'undelete_short'    => 'Maca {{PLURAL:$1|ānne ādiht|$1 ādihtas}} eft',
'protect'           => 'Beorȝa',
'protect_change'    => 'Hƿeorf',
'protectthispage'   => 'Beorȝa þās sīdan',
'unprotect'         => 'Unbeorgan',
'unprotectthispage' => 'Unbeorȝa þās sīdan',
'newpage'           => 'Nīƿu sīde',
'talkpage'          => 'Sprec ymb þās sīdan',
'talkpagelinktext'  => 'ȝespreċ',
'specialpage'       => 'Syndriȝ sīde',
'personaltools'     => 'Āgne tōlas',
'postcomment'       => 'Nīƿe dǣl',
'articlepage'       => 'Seoh innungsīdan',
'talk'              => 'Ȝespreċ',
'views'             => 'Ansīena',
'toolbox'           => 'Tōlearc',
'userpage'          => 'Seoh brūcendsīdan',
'projectpage'       => 'Seoh ƿeorcsīdan',
'imagepage'         => 'Seoh ȝeƿithordsīdan',
'mediawikipage'     => 'Ȝeƿritsīdan sēon',
'templatepage'      => 'Seoh bysensīdan',
'viewhelppage'      => 'Seoh helpsīdan',
'categorypage'      => 'Seoh floccsīdan',
'viewtalkpage'      => 'Seoh tæle',
'otherlanguages'    => 'On ōðrum sprǣċum',
'redirectedfrom'    => '(Edlǣded fram $1)',
'redirectpagesub'   => 'Edlǣdsīde',
'lastmodifiedat'    => 'Man nīwanost þās sīdan hƿearf on þǣre $2 stunde þæs $1.',
'viewcount'         => 'Þēos sīde hæfþ ȝeƿorden ȝeseƿen {{PLURAL:$1|āne|$1 hwīlum}}.',
'protectedpage'     => 'Borȝod sīde',
'jumpto'            => 'Gā tō:',
'jumptonavigation'  => 'þurhfōr',
'jumptosearch'      => 'sēċan',
'view-pool-error'   => 'Ƿē sind sāriȝe for þǣm þe þās þeȝntōlas nū oferlīce ƿyrcaþ.
Tō mæniȝe brūcendas ȝesēcaþ to sēonne þās sīdan.
Ƿ̈ē biddaþ þæt þū abīde scortre tīde fore þū ȝesēce to sēonne þās sīdan eft.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ymbe {{SITENAME}}',
'aboutpage'            => 'Project:Ymbe',
'copyright'            => 'Man mæȝ innunȝe under $1 findan.',
'copyrightpage'        => '{{ns:project}}:Ȝelīcnessriht',
'currentevents'        => 'Ȝelimpunȝa þisses tīman',
'currentevents-url'    => 'Project:Ȝelimpunga þisses tīman',
'disclaimers'          => 'Ætsacunga',
'disclaimerpage'       => 'Project:Ætsacunga',
'edithelp'             => 'Help mid ādihtunge',
'edithelppage'         => 'Help:Ādihtung',
'helppage'             => 'Help:Innoþ',
'mainpage'             => 'Hēafodsīde',
'mainpage-description' => 'Hēafodsīde',
'policy-url'           => 'Project:Rǣd',
'portal'               => 'Ȝemǣnscipes ingang',
'portal-url'           => 'Project:Ȝemǣnscipes inȝanȝ',
'privacy'              => 'Ānlēpnesse rǣd',
'privacypage'          => 'Project:Ānlēpnesse rǣd',

'badaccess'        => 'Þafunȝe ƿōh',
'badaccess-group0' => 'Þū ne mōst dōn þā dǣde þǣre þe þū hafast abeden.',
'badaccess-groups' => 'Þēos dǣd þǣre þū hafast abeden is synderlīce alȳfedlic brūcendum on {{PLURAL:$2|þissum þrēate|ānum þāra þrēata}}: $1.',

'versionrequired'     => '$1 fadunȝ of MediaǷiki is ȝeþorften',
'versionrequiredtext' => 'Fadung $1 MediaǷiki is ȝeþorften tō notiennde þisne tramet.
Sēoh þone [[Special:Version|fadunge tramet]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Fram "$1" beȝieten',
'youhavenewmessages'      => 'Þū hæfst $1 ($2).',
'newmessageslink'         => 'nīƿu ǣrendȝeƿritu',
'newmessagesdifflink'     => 'nīƿost hƿearf',
'youhavenewmessagesmulti' => 'Þū hæfst nīƿe ǣrende on $1',
'editsection'             => 'ādihtan',
'editold'                 => 'ādihtan',
'viewsourceold'           => 'Sēon andweorc',
'editlink'                => 'ādihtan',
'viewsourcelink'          => 'Fruman sēon',
'editsectionhint'         => 'Dǣl ādihtan: $1',
'toc'                     => 'Innoþ',
'showtoc'                 => 'sēon',
'hidetoc'                 => 'hȳdan',
'thisisdeleted'           => '$1 sēon oþþe nīƿian?',
'viewdeleted'             => '$1 sēon?',
'restorelink'             => '{{PLURAL:$1|ān āfeorsed ādiht|$1 āfeorsed ādihtas}}',
'feedlinks'               => 'Flōd:',
'feed-invalid'            => 'Ungenge underƿrītunge inlāde ȝecynd.',
'feed-unavailable'        => 'Fruman inlāda ne sind ȝearƿa',
'site-rss-feed'           => '$1 RSS strēam',
'site-atom-feed'          => '$1 Atom strēam',
'page-rss-feed'           => '$1 RSS strēam',
'page-atom-feed'          => '$1 Atom strēam',
'red-link-title'          => '$1 (ne ȝiet ƿriten)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sīde',
'nstab-user'      => 'Brūcendsīde',
'nstab-media'     => 'Ȝemyndsīde',
'nstab-special'   => 'Syndriȝu sīde',
'nstab-project'   => 'Ƿeorces sīde',
'nstab-image'     => 'Fīl',
'nstab-mediawiki' => 'Ǣrendȝeƿrit',
'nstab-template'  => 'Bysen',
'nstab-help'      => 'Helpsīde',
'nstab-category'  => 'Flocc',

# Main script and global functions
'nosuchaction'      => 'Nǣniȝ dǣd',
'nosuchactiontext'  => 'Þæt weorc þe se nettfrumfinded wile is ungenge.
Þū meahtlīce miswrite þone nettfrumfindend, oþþe folgode unrihtne hlencan.
Þis mæg ēac tācnian unrihtnesse on þǣre sōftware þe is gebrocen fram {{SITENAME}}.',
'nosuchspecialpage' => 'Nǣniȝu syndriȝu sīde',
'nospecialpagetext' => '<strong>Þū hafast abiden ungenges ānlices trametes.</strong>

Getæl gengra ānlicra trameta cann mann findand be [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'               => 'Ƿōh',
'databaseerror'       => 'Cȳþþuhordes ƿōh',
'dberrortext'         => 'Cȳþþuhordes bēnes endebyrdnesse fremmode ƿōh.
Þis mæȝe mǣnan regolƿōh on þǣre sōftƿare.
Sēo nīƿoste ȝesōhte sōftƿare bēn ƿæs:
<blockquote><tt>$1</tt></blockquote>
fram innan ƿeorce "<tt>$2</tt>".
Cȳþþuhord edƿende ƿōh "<tt>$3: $4</tt>"',
'laggedslavemode'     => "'''Ƿarnung:''' Sīde ne mihteliċ ne hæfþ nīƿa nīƿunga.",
'enterlockreason'     => 'Wrīt race þǣre forwiernunge and apunsunge be þǣm tīman on þǣm bēo sēo forwiernung forlǣten',
'missingarticle-rev'  => '(nīƿung#: $1)',
'internalerror'       => 'Innan ƿōh',
'internalerror_info'  => 'Innan ƿōh: $1',
'fileappenderrorread' => 'Ne meahte "$1" rǣdan on ēacunge.',
'fileappenderror'     => 'Ne meahte "$1" to "$2" ēacian.',
'filerenameerror'     => 'Ne cúðe ednemnan þrǽd "$1" tó "$2".',
'filenotfound'        => 'Ne cūðe findan þrǣd "$1".',
'formerror'           => 'Ƿōh: ne meahte cȳþþuȝeƿrit forþsendan',
'badarticleerror'     => 'Þēos dǣd ne cann bēon gefremed on þissum tramete.',
'badtitle'            => 'Unandfenge títul',
'viewsource'          => 'Fruman sēon',
'cascadeprotected'    => 'Þis trament hafaþ geworden gebeorgod wiþ ādihtunge, for þǣm þe hē is geinnod in þissum trament {{PLURAL:$1|tramente, þe is| tramentum, þe sind}} geborgod mid þǣre "cascading" cyre gesett wyrcende: $2',

# Login and logout pages
'logouttext'                 => "'''Þū eart nū ūtmeldod.'''

Þū canst ætfeolan tō brūcenne {{SITENAME}} ungecūðe, oþþe þū canst [[Special:UserLogin|inmeldian eft]] tō ylcan oþþe ōðrum brūcende.
Cnāw þæt sume sīdan cunnon gelǣstende ēowod wesan swā þū wǣre gīet inmeldod, oþ þæt þū clǣnsie þīnes sēcendtōles gemynd.",
'welcomecreation'            => '== Ƿilcumen, $1! ==

Þīn hordcleofa ƿearþ ȝescapen.  Ne forȝiet tō hƿierfenne þīna [[Special:Preferences|{{SITENAME}} foreberunga]].',
'yourname'                   => 'Þīn brūcendnama',
'yourpassword'               => 'Þafungƿord:',
'yourpasswordagain'          => 'Þafungƿord edƿrītan:',
'remembermypassword'         => 'Mīne inmeldunge ȝemyndan on þissum spearcatelle (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => 'Þīn ȝeƿeald:',
'login'                      => 'Inmeldian',
'nav-login-createaccount'    => 'Nīƿne hordcleofan scieppan oþþe inmeldian',
'userlogin'                  => 'Macian nīwne grīman oþþe grīman brūcan',
'userloginnocreate'          => 'Inmeldian',
'logout'                     => 'Ūtmeldian',
'userlogout'                 => 'Ūtmeldian',
'notloggedin'                => 'Ne inȝemelded',
'nologinlink'                => 'Hordcleofan scieppan',
'createaccount'              => 'Hordcleofan scieppan',
'gotaccountlink'             => 'Inmeldian',
'createaccountmail'          => 'Þurh spearcenaǣrend',
'createaccountreason'        => 'Racu:',
'badretype'                  => 'Þā þafungƿord þe ƿrite þū, bēoþ unȝemæcca.',
'userexists'                 => 'Hƿā hæfþ þæt brūcendnama.
Bidde ōðer brūcendnama ċēosan.',
'loginerror'                 => 'Inmeldunge ƿōh',
'createaccounterror'         => 'Ne cūðe macian reccend: $1',
'nocookiesnew'               => 'Se brūcendreccend wæs gemacod, ac þū neart inmedlod.
{{SITENAME}} brȳcþ tācninclu tō inmeldienne brūcendas.
Þū hafast forwierned tācninclu.
Bidde þē, lǣt hīe tō twyrcenne, and þǣræfter inmelda þurh þīnne nīwan brūcendnaman and gelēafnessword.',
'loginsuccesstitle'          => 'Inmeldung gesǣlde',
'loginsuccess'               => "'''Þu eart nū inmeldod tō {{SITENAME}} swā \"\$1\".'''",
'nosuchuser'                 => 'Þǣr nis nān brūcere þe hæfþ þone naman "$1".
Stafena micelnesse sind hefige and ānlica on brūcendnamum.
Scēawa þīne wrītunge eft, oþþe brūc þā cartan þe is hērunder tō [[Special:UserLogin/signup|settene nīwne brūcendreccend]].',
'nosuchusershort'            => 'Þǣr is nān brūcend mid þǣm naman "<nowiki>$1</nowiki>".  Edscēawa on þīne wrītunge.',
'passwordtooshort'           => 'Gelēafword sculon habban læst {{PLURAL:$1|1 stafan|$1 stafan}}.',
'mailmypassword'             => 'Nīƿe þafungƿord bȳ e-mail sendan',
'acct_creation_throttle_hit' => 'Hwæt, þu hæfst gēo geseted {{PLURAL:$1|1 hordcleofan|$1 -}}. Þu ne canst settan ǣnige māran.',
'accountcreated'             => 'Hordcleofan ȝescapen',
'loginlanguagelabel'         => 'Sprǣċ: $1',

# JavaScript password checks
'password-retype' => 'Þafungƿord edƿrītan',

# Password reset dialog
'resetpass'                 => 'Þafungƿord hƿeorfan',
'oldpassword'               => 'Eald þafungƿord:',
'newpassword'               => 'Nīƿu þafungƿord:',
'retypenew'                 => 'Nīƿe þafungƿord edƿrītan',
'resetpass-submit-loggedin' => 'Þafungƿord hƿeorfan',
'resetpass-submit-cancel'   => 'Undōn',

# Edit page toolbar
'bold_sample'     => 'Þicce traht',
'bold_tip'        => 'Þicce traht',
'italic_sample'   => 'Flōƿende traht',
'italic_tip'      => 'Flōƿende traht',
'link_sample'     => 'Hlenċnama',
'link_tip'        => 'Innanƿeard hlenċe',
'extlink_sample'  => 'http://www.bisen.com hlenċnama',
'extlink_tip'     => 'Ūtanƿeard hlenċe (ȝemune http:// foredǣl)',
'headline_sample' => 'Hēafodlīnan traht',
'headline_tip'    => 'Emnet 2 hēafodlīn',
'math_sample'     => 'Ƿiċunge hēr ēacian',
'math_tip'        => 'Rīmcræftisc ƿiċung (LaTeX)',
'nowiki_sample'   => 'Unȝeƿorhtne traht hēr stellan',
'nowiki_tip'      => 'Ƿiki ȝeƿeorc forȝietan',
'image_sample'    => 'Bisen.jpg',
'image_tip'       => 'Impod biliþ',
'media_sample'    => 'Bisen.ogg',
'media_tip'       => 'Fīlhlenċe',
'sig_tip'         => 'Þīn namanseȝn mid tīdstempunge',
'hr_tip'          => 'Brād līn (ne oft brūcan)',

# Edit pages
'summary'                          => 'Scortnes:',
'subject'                          => 'Ymbe/hēafodlīn:',
'minoredit'                        => 'Þes is lȳtl ādiht',
'watchthis'                        => 'Þās sīdan ƿæccan',
'savearticle'                      => 'Sīdan sparian',
'preview'                          => 'Forescēaƿian',
'showpreview'                      => 'Forescēaƿian',
'showlivepreview'                  => 'Līfe forescēaƿe',
'showdiff'                         => 'Hƿearfas sēon',
'summary-preview'                  => 'Scortnesse forescēaƿe:',
'blockednoreason'                  => 'nānu racu ȝiefen',
'whitelistedittitle'               => 'Inmeldunge behōfed to ādihtenne',
'whitelistedittext'                => 'Þū scealt $1 to ādihtenne sīdan.',
'nosuchsectiontitle'               => 'Ne mæȝ dǣl findan',
'loginreqtitle'                    => 'Inmeldung ābeden',
'loginreqlink'                     => 'inmeldian',
'loginreqpagetext'                 => 'Þū scealt $1 tō sēonne ōðre sīdan.',
'accmailtitle'                     => 'Þafungƿord sended.',
'accmailtext'                      => "Hlīetemaced þafungƿord for [[User talk:$1|$1]] ƿæs to $2 sended.

Þū meaht þæt þafungƿord hƿeorfan for þissum nīƿan hordcleofa on þǣre ''[[Special:ChangePassword|change password]]'' sīde æfter inmeldiende.",
'newarticle'                       => '(Nīƿe)',
'newarticletext'                   => "Þu hæfst bende tō tramete gefolgod þe nū gīet ne stendeþ.
Tō scieppene þone tramet, onginn þyddan in þǣre boxe under (sēo þone [[{{MediaWiki:Helppage}}|helptramet]] for mā gefrǣge).
Gif þu hider misfōn cōme, cnoca þā þīnne webbscēaweres '''on bæc''' cnæpp.",
'usercssyoucanpreview'             => "'''Rǣd:''' Brūc þone 'Forescēawian' cnæpp tō āfandienne þīne nīwe css/js beforan sparunge.",
'userjsyoucanpreview'              => "'''Rǣd:''' Brūc þone 'Forescēawian' cnæpp tō āfandienne þīne nīwe css/js beforan sparunge.",
'updated'                          => '(Ednīƿed)',
'note'                             => "'''Behielde:'''",
'previewnote'                      => "'''Ȝemune þe þēos efne forescēaƿe is.'''
Þīne hƿearfas ne ȝiet bēoþ spared!",
'editing'                          => 'Ādihtende $1',
'editingsection'                   => 'Ādihtende $1 (dǣl)',
'editingcomment'                   => 'Ādihtende $1 (nīƿe dǣl)',
'editconflict'                     => 'Ādihtes ƿiþfeoht: $1',
'yourtext'                         => 'Þīn traht',
'editingold'                       => "'''ǷARNUNG: Þū ādihtest ealde fadunge þisre sīdan.'''
Ȝif þū hine sparie, ǣniȝ hƿearfas ȝemaced siþþan þisse fadunge bēoþ sōðes forloren.",
'yourdiff'                         => 'Tōdǣlednessa',
'copyrightwarning2'                => "Bidde behielde þæt man mæȝ ealla forðunga tō {{SITENAME}}
ādihtan, hƿeorfan, oþþe forniman.
Ȝif þū ne ƿille man þīn ȝeƿrit ādihtan unmildheorte, þonne hīe hēr ne forþsendan.<br />
Þū behǣtst ēac þæt þū selfa þis ƿrite, oþþe efenlǣhtest of sumre
folcliċum āgnunge oþþe ȝelīċum frēom horde (sēo $1 for āscungum).
'''Ne forþsend efenlǣhtscielded ƿeorc būtan þafunge!'''",
'longpagewarning'                  => 'WARNUNG: Þes tramet is $1 kilobyta lang; sume
webbscēaweras hæbben earfoðu mid þȳ þe hīe ādihtaþ trametas nēa oþþe lengran þonne 32kb.
Bidde behycge þæt þu bricst þone tramet intō smalrum dǣlum.',
'templatesused'                    => '{{PLURAL:$1|Bysen|Bysena}} brocen on þisre sīdan:',
'templatesusedpreview'             => '{{PLURAL:$1|Bysen|Bysena}} brocen on þisre forescēaƿe:',
'template-protected'               => '(borgen)',
'template-semiprotected'           => '(sāmborgen)',
'hiddencategories'                 => 'Þēos sīde is ȝesīþ {{PLURAL:$1|1 ȝehȳdedes flocces|$1 ȝehȳdeda flocca}}:',
'nocreatetitle'                    => 'Sīdan ȝesceaft mǣte',
'nocreate-loggedin'                => 'Þū ne hæfst þafunge to scieppenne nīƿa sīdan.',
'permissionserrors'                => 'Þafunga ƿōh',
'permissionserrorstext-withaction' => 'Þū ne hæfst þafunge for $2, forþǣm þe {{PLURAL:$1|race|racum}}:',
'recreate-moveddeleted-warn'       => "'''Warnung: Þu edsciepst tramet þe wæs ǣr āfeorsod.'''

Þu sceoldest smēagan, hwæðer hit gerādlic sīe, forþ tō gānne mid ādihtunge þisses trametes.
Þæt āfeorsungbred þisses trametes is hēr geīeht for behēfnesse:",

# History pages
'viewpagelogs'           => 'Ealdhordas sēon for þisse sīdan',
'nohistory'              => 'Nis nān ādihtungstǣr for þissum tramete.',
'currentrev-asof'        => 'Nīƿe fadung sƿā $1',
'revisionasof'           => 'Nīƿung fram',
'previousrevision'       => '← Ieldra fadung',
'nextrevision'           => 'Nīƿra fadung →',
'currentrevisionlink'    => 'Nīƿu fadung',
'cur'                    => 'nū',
'next'                   => 'nīehst',
'last'                   => 'ǣr',
'history-fieldset-title' => 'Stǣr sēċan',
'histfirst'              => 'Ǣrest',
'histlast'               => 'Nīƿost',
'historyempty'           => '(æmettiȝ)',

# Revision feed
'history-feed-title'          => 'Ednīƿunge stǣr',
'history-feed-description'    => 'Ednīƿunge stǣr þisse sīdan on þǣre ƿiki',
'history-feed-item-nocomment' => '$1 on $2',

# Revision deletion
'rev-deleted-comment'        => '(cƿide fornōm)',
'rev-deleted-user'           => '(brūcendnama fornōm)',
'rev-delundel'               => 'scēaƿian/hȳdan',
'rev-showdeleted'            => 'scēaƿan',
'revdelete-show-file-submit' => 'Ȝēa',
'revdelete-hide-text'        => 'Ednīƿungtraht hȳdan',
'revdelete-hide-image'       => 'Fīlinnoþ hȳdan',
'revdelete-hide-comment'     => 'Ādihtcƿide hȳdan',
'revdelete-hide-user'        => 'Ādihteres brūcendnama/IP address hȳdan',
'revdelete-radio-same'       => '(ne hƿeorfan)',
'revdelete-radio-set'        => 'Ȝēa',
'revdelete-radio-unset'      => 'Nā',
'revdel-restore'             => 'scēaƿnesse hƿeorfan',
'pagehist'                   => 'Sīdan stǣr',
'revdelete-content'          => 'innoþ',
'revdelete-summary'          => 'ādihtscortnes',
'revdelete-uname'            => 'brūcendnama',
'revdelete-reasonotherlist'  => 'Ōðru racu',

# History merging
'mergehistory-from'   => 'Frumasīde:',
'mergehistory-submit' => 'Ednīƿunga ȝeþēodian',
'mergehistory-reason' => 'Racu:',

# Merge log
'revertmerge' => 'Unȝeþēodan',

# Diffs
'history-title'           => 'Ednīƿunge stǣr for "$1"',
'difference'              => '(Scēadung betwēonan hweorfungum)',
'lineno'                  => 'Līne $1:',
'compareselectedversions' => 'Corena fadunga metan',
'editundo'                => 'undōn',

# Search results
'searchresults'                  => 'Sōcne becymas',
'searchresults-title'            => 'Sōcne becymas for "$1"',
'searchresulttext'               => 'For mā cȳþþe ymbe {{SITENAME}} sēċan, sēo [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                 => "Þū sōhtest '''[[:$1]]'''",
'searchsubtitleinvalid'          => "Þū sōhtest '''$1'''",
'notitlematches'                 => 'Nān sīdenama mæccan',
'notextmatches'                  => 'Nāne sīdetrahtes mæccan',
'prevn'                          => 'ǣror {{PLURAL:$1|$1}}',
'nextn'                          => 'nīehst {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Sēon ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                 => "'''Þā sīdan \"[[:\$1]]\" scieppan on þisre ƿiki!'''",
'searchhelp-url'                 => 'Help:Innoþ',
'searchprofile-articles'         => 'Innoþsīdan',
'searchprofile-project'          => 'Help and Ƿeorc sīdan',
'searchprofile-images'           => 'Mæniȝȝemyndisc',
'searchprofile-everything'       => 'Ȝehƿæt',
'searchprofile-articles-tooltip' => 'In $1 sēċan',
'searchprofile-project-tooltip'  => 'In $1 sēċan',
'searchprofile-images-tooltip'   => 'Fīlan sēċan',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ƿord|$2 ƿord}})',
'search-redirect'                => '(edlǣd $1)',
'search-section'                 => '(dǣl $1)',
'search-suggest'                 => 'Mǣnst þū: $1',
'search-interwiki-caption'       => 'Sƿeostorƿeorc',
'search-interwiki-default'       => '$1 becymas:',
'search-interwiki-more'          => '(mā)',
'search-mwsuggest-enabled'       => 'mid teohhungum',
'search-mwsuggest-disabled'      => 'nān teohhunga',
'searchrelated'                  => 'ȝesibbed',
'searchall'                      => 'eall',
'showingresults'                 => 'Īewan under oþ <b>$1</b> tōhīgunga onginnenda mid #<b>$2</b>.',
'showingresultsnum'              => 'Under sind <b>$3</b> tóhígunga onginnende mid #<b>$2</b>.',
'powersearch'                    => 'Sēċan',
'powersearch-legend'             => 'Forþliċ sōcn',
'powersearch-ns'                 => 'In namanstedum sēċan:',
'powersearch-redir'              => 'Edlǣdas scēaƿian',
'powersearch-field'              => 'Sēċan',
'search-external'                => 'Ūtan sōcn',

# Quickbar
'qbsettings-none' => 'Nān',

# Preferences page
'preferences'        => 'Foreberunga',
'mypreferences'      => 'Mīna foreberunga',
'prefsnologin'       => 'Ne inȝemelded',
'prefs-skin'         => 'Scynn',
'skin-preview'       => 'Forescēaƿian',
'prefs-math'         => 'Rīmcræft',
'prefs-datetime'     => 'Tælmearc and tīd',
'prefs-rc'           => 'Nīƿe hƿearfas',
'prefs-watchlist'    => 'Ƿæccȝetalu',
'saveprefs'          => 'Sparian',
'rows'               => 'Rǣƿa',
'columns'            => 'Sȳla:',
'searchresultshead'  => 'Sōcnfintan',
'resultsperpage'     => 'Tōhīgunga tō īewenne for tramete',
'contextlines'       => 'Līnan tō īewenne in tōhīgunge',
'recentchangescount' => 'Hū mæniȝ ādihtas to scēaƿenne ȝeþēaƿe:',
'savedprefs'         => 'Þīna foreberunga ƿurdon ȝespared.',
'timezonelegend'     => 'Tīdstell',
'servertime'         => 'Bryttantīma is nū',
'defaultns'          => 'Sēcan in þissum namstedum be frambyge:',
'default'            => 'gewunelic',
'youremail'          => 'E-ǣrende *',
'username'           => 'Brūcendnama:',
'yourrealname'       => 'Þīn rihtnama*',
'yourlanguage'       => 'Brūcendofermearces sprǣc',
'yourvariant'        => 'Sprǣce wendung',
'yourgender'         => 'Ȝecynd:',
'gender-male'        => 'Ƿer',
'gender-female'      => 'Frēo',

# User rights
'userrights-user-editname' => 'Brūcendnama ƿrītan:',
'editusergroup'            => 'Ādihtan Brūcendsamþrēatas',
'userrights-editusergroup' => 'Brūcenda clīeƿenas ādihtan:',
'saveusergroups'           => 'Brūcenda clīeƿenas sparian',
'userrights-groupsmember'  => 'Ȝesīþ þæs:',
'userrights-reason'        => 'Racu:',

# Groups
'group'            => 'Clīeƿen:',
'group-user'       => 'Brūcendas:',
'group-bot'        => 'Searuþralas',
'group-sysop'      => 'Beƿitendas',
'group-bureaucrat' => 'Tōþeȝnas',
'group-suppress'   => 'Oferȝesihta',
'group-all'        => '(eall)',

'group-user-member'     => 'brūcend',
'group-bot-member'      => 'searuþræl',
'group-sysop-member'    => 'beƿitend',
'group-suppress-member' => 'oferȝesiht',

'grouppage-sysop' => '{{ns:project}}:Beƿitendas',

# User rights log
'rightslog' => 'Brūcenda riht cranic',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'þās sīdan ādihtan',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|hƿearf|hƿeafas}}',
'recentchanges'                  => 'Nīƿe hƿearfas',
'recentchanges-legend'           => 'Nīƿa hƿearfa forebearƿan',
'recentchanges-feed-description' => 'Þā mǣst nīƿoste hƿearfan huntan to þisse ƿiki in þissum strēame',
'recentchanges-label-newpage'    => 'Þes ādiht macode nīƿa sīdan',
'recentchanges-label-minor'      => 'Þes is lȳtl ādiht',
'recentchanges-label-bot'        => 'Searuþræl fremmode þisne ādiht',
'rcnote'                         => "Under {{PLURAL:$1|... '''1''' ...|sind þā æftemestan '''$1''' hweorfunga}} in {{PLURAL:$2|...|þǣm æftemestum '''$2''' dagum}}, . . $5, $4.",
'rcnotefrom'                     => 'Under sind þā hweorfunga siþþan <b>$2</b> (oþ <b>$1</b> geīewed).',
'rclistfrom'                     => 'Nīƿe hƿeorfan sēon beȝinnende fram $1',
'rcshowhideminor'                => '$1 lȳtl ādihtas',
'rcshowhidebots'                 => '$1 searuþralas',
'rcshowhideliu'                  => '$1 inmeldede brūcendas',
'rcshowhideanons'                => '$1 uncūþ brūcendas',
'rcshowhidemine'                 => '$1 mīne ādihtas',
'rclinks'                        => 'Læste $1 hƿearfas sēon in læstum $2 dagum<br />$3',
'diff'                           => 'scēa',
'hist'                           => 'Stǣr',
'hide'                           => 'hȳdan',
'show'                           => 'Scēaƿan',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc_categories_any'              => 'Ǣniȝ',
'rc-enhanced-expand'             => 'Ȝehanda sēon (þearf JavaScript)',
'rc-enhanced-hide'               => 'Ȝehanda hȳdan',

# Recent changes linked
'recentchangeslinked'         => 'Sibbhƿearfas',
'recentchangeslinked-feed'    => 'Sibbhƿearfas',
'recentchangeslinked-toolbox' => 'Sibbhƿearfas',
'recentchangeslinked-title'   => 'Hƿearfas ȝesibbed to "$1"',
'recentchangeslinked-page'    => 'Sīdenama:',
'recentchangeslinked-to'      => 'Hƿearfas to sīdan sēon þe hlenċan habbaþ to þǣre ȝiefen sīdan in stede',

# Upload
'upload'            => 'Fīl forþsendan',
'uploadbtn'         => 'Fīl forþsendan',
'uploadnologin'     => 'Ne inmeldod',
'uploaderror'       => 'Ƿōh on forþsendende',
'upload-permitted'  => 'Þafed fīlcynn: $1.',
'upload-preferred'  => 'Foreboren fīlcynn: $1.',
'upload-prohibited' => 'Forboden fīlcynn: $1.',
'uploadlogpage'     => 'Forþsend ealdhord',
'filename'          => 'Fīlnama',
'filedesc'          => 'Scortnes',
'filesource'        => 'Fruma:',
'badfilename'       => 'Onlīcnesnama wearþ gewend tō "$1(e/an)".',
'savefile'          => 'Þrǣd sparian',
'uploadedimage'     => 'forþsendode "[[$1]]"',
'sourcefilename'    => 'Fruman þrǣdnama:',

'license'           => 'Ȝelēaf:',
'license-header'    => 'Ȝelēaf:',
'nolicense'         => 'Nǣnne gecorenne',
'license-nopreview' => '(Forescēaƿe nis ȝearu)',

# Special:ListFiles
'listfiles-summary'     => 'Þēos syndriȝa sīde ēoƿaþ ealle forþsendede fīlas.
Æfter ȝeƿuneliċum ƿīsum, þā nīƿostan fīlas sind ēoƿod be hēafde þæs ȝetæles.
Cnæpp on sƿeorhēafde hƿeorfþ þā endebyrdnessa.',
'listfiles_search_for'  => 'Sēcan biliþnaman:',
'imgfile'               => 'fīl',
'listfiles'             => 'Biliþgetalu',
'listfiles_date'        => 'Tælmearc',
'listfiles_name'        => 'Nama',
'listfiles_user'        => 'Brūcend',
'listfiles_size'        => 'Miċelnes',
'listfiles_description' => 'Tōƿritennes',
'listfiles_count'       => 'Fadunga',

# File description page
'file-anchor-link'          => 'Fīl',
'filehist'                  => 'Fīlanstǣr',
'filehist-help'             => 'Cnæpp on dæȝe/tīde to sēonne þā fīlan sƿā ƿæs hēo on þǣre tīde.',
'filehist-deleteall'        => 'eall āfeorsian',
'filehist-deleteone'        => 'āfeorsian',
'filehist-revert'           => 'undōn',
'filehist-current'          => 'nū',
'filehist-datetime'         => 'Dæȝ/Tīd',
'filehist-thumb'            => 'Lȳtlbiliþ',
'filehist-thumbtext'        => 'Lȳtlbiliþ for fadunge sƿā $1',
'filehist-nothumb'          => 'Nān biliþinċel',
'filehist-user'             => 'Brūcend',
'filehist-dimensions'       => 'Miċela',
'filehist-filesize'         => 'Fīlmiċelnes',
'filehist-comment'          => 'Ymbsprǣċ',
'filehist-missing'          => 'Fīl lēas',
'imagelinks'                => 'Fīlhlenċan',
'linkstoimage'              => 'Þā folgendan {{PLURAL:$1|sīde hæfþ hlenċe|sīdan habbaþ hlenċan}} for þissum fīle:',
'nolinkstoimage'            => 'Þǣr sind nāne trametas þe bindaþ tō þissum biliðe.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Mā hlenċan]] sēon tō þissum fīle.',
'redirectstofile'           => '{{PLURAL:$1|Þēos fīl edlǣdeþ|$1 Þās fīlan hēr edlǣdaþ}} tō þissum  fīle:',
'duplicatesoffile'          => '{{PLURAL:$1|Sēo folgende fīl is ȝelīċnes|Þā folgende fīlan sind ȝelīċnessa}} þisses fīles (sēo [[Special:FileDuplicateSearch/$2|mā ȝeƿitnesse hērymb]]):',
'sharedupload'              => 'Þēos fīl is fram $1 and man mæȝ hīe brūcan on ōðrum ƿeorcum.',
'uploadnewversion-linktext' => 'Nīƿe fadunge þisse fīlan forþsendan',

# File reversion
'filerevert-legend' => 'Fīlan eftdōn',

# File deletion
'filedelete-submit' => 'āfeorsian',

# Unused templates
'unusedtemplateswlh' => 'ōðre hlenċan',

# Random page
'randompage' => 'Hlīetliċu sīde',

# Statistics
'statistics'              => 'Cȳþþu',
'statistics-articles'     => 'Innungsīdan',
'statistics-pages'        => 'Sīdan',
'statistics-users-active' => 'Hƿate brūcendas',
'statistics-mostpopular'  => 'Mǣst saƿen sīdan',

'doubleredirects' => 'Tƿifealde ymblǣderas',

'brokenredirects'        => 'Brocene ymblǣderas',
'brokenredirectstext'    => 'Þā folgendan edlǣdunga bendaþ tō unedwistlicum trametum.',
'brokenredirects-edit'   => 'ādihtan',
'brokenredirects-delete' => 'āfeorsian',

'withoutinterwiki'         => 'Trametas būtan sprǣcbendum',
'withoutinterwiki-summary' => 'Þā folgendan trametas ne bindaþ tō ōðrum sprǣcfadungum:',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'          => '$1 {{PLURAL:$1|flocca|floccas}}',
'nlinks'               => '$1 bendas',
'nmembers'             => '$1 {{PLURAL:$1|ȝesīþ|ȝesīðas}}',
'specialpage-empty'    => 'Þēos sīde is ǣmtiȝ.',
'lonelypages'          => 'Ealdorlēase trametas',
'unusedimages'         => 'Unbrocene fīlan',
'popularpages'         => 'Dēore trametas',
'wantedcategories'     => 'Gewilnode floccas',
'wantedpages'          => 'Gewilnode trametas',
'mostlinked'           => 'Gebundenostan trametas',
'mostlinkedcategories' => 'Gebundenostan floccas',
'mostlinkedtemplates'  => 'Gebundenostan bysena',
'prefixindex'          => 'Ealla sīdan mid foredǣle',
'shortpages'           => 'Scorte trametas',
'longpages'            => 'Lange trametas',
'listusers'            => 'Brūcenda ȝetalu',
'newpages'             => 'Nīƿa sīdan',
'newpages-username'    => 'Brūcendnama:',
'ancientpages'         => 'Ieldestan Trametas',
'move'                 => 'Gān',
'movethispage'         => 'Þās sīdan ȝeferan',
'pager-newer-n'        => '{{PLURAL:$1|nīƿra 1|nīƿra $1}}',
'pager-older-n'        => '{{PLURAL:$1|ieldra 1|ieldra $1}}',

# Book sources
'booksources'               => 'Bōcfruman',
'booksources-search-legend' => 'Sēcan bōcfruman',
'booksources-go'            => 'Gān',
'booksources-text'          => 'Under is getalu benda tō ōðrum webstedum þe bebycgaþ nīwa and gebrocena bēc, and hæbben
ēac mā āscunga ymbe bēc þe þu sēcst:',

# Special:Log
'specialloguserlabel'  => 'Brūcend:',
'speciallogtitlelabel' => 'Nama:',
'log'                  => 'Ealdhord',

# Special:AllPages
'allpages'       => 'Ealla sīdan',
'alphaindexline' => '$1 tō $2',
'nextpage'       => 'Nīehsta sīde ($1)',
'prevpage'       => 'Ǣror sīde ($1)',
'allpagesfrom'   => 'Sīdan scēaƿian beȝinnende æt:',
'allpagesto'     => 'Sīdan scēaƿian endende æt:',
'allarticles'    => 'Ealla sīdan',
'allinnamespace' => 'Ealle trametas ($1 namanstede)',
'allpagesprev'   => 'Fore',
'allpagesnext'   => 'Nīehst',
'allpagessubmit' => 'Gān',

# Special:Categories
'categories'         => 'Floccas',
'categoriespagetext' => 'Þā folgendan floccas standaþ in þǣm wici.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'forðunga',

# Special:LinkSearch
'linksearch'    => 'Ūtanƿeard hlenċan',
'linksearch-ok' => 'Sēċan',

# Special:ListUsers
'listusers-noresult' => 'Nǣnne brūcend gefundenne.',

# Special:ActiveUsers
'activeusers' => 'Hƿata brūcenda ȝetalu',

# Special:Log/newusers
'newuserlogpage'          => 'Brūcenda ȝesceaft ȝetalu',
'newuserlog-create-entry' => 'Nīƿe brūcend',

# Special:ListGroupRights
'listgrouprights-group'           => 'Clīeƿen',
'listgrouprights-rights'          => 'Riht',
'listgrouprights-helppage'        => 'Help:Clīeƿenes riht',
'listgrouprights-members'         => '(ȝesīða ȝetalu)',
'listgrouprights-removegroup'     => '{{PLURAL:$2|Clīeƿen|Clīeƿenas}} forniman: $1',
'listgrouprights-addgroup-all'    => 'Eall clīeƿenas ēacian',
'listgrouprights-removegroup-all' => 'Ealle clīeƿenas forniman',

# E-mail user
'emailuser'     => 'To þissum brūcende ƿrītan',
'emailfrom'     => 'Fram',
'emailto'       => 'Tō:',
'emailsubject'  => 'Forþsetennes',
'emailmessage'  => 'Ǣrendȝeƿrit',
'emailsend'     => 'Ǣrendian',
'emailsent'     => 'Ǣrendȝeƿrit sended',
'emailsenttext' => 'Þīn e-mail ǣrendȝeƿrit ƿearþ ȝesend.',

# Watchlist
'watchlist'         => 'Mīnu ƿæcceȝetalu',
'mywatchlist'       => 'Mīnu ƿæcceȝetalu',
'addedwatch'        => 'To ƿæcceȝetale ēacod',
'removedwatch'      => 'Fornōm fram ƿæccȝetale',
'removedwatchtext'  => 'Sēo sīde "[[:$1]]" ƿæs fram [[Special:Watchlist|þīnre ƿæccȝetale]] fornōm.',
'watch'             => 'Ƿæccan',
'watchthispage'     => 'Þās sīdan ƿæccan',
'unwatch'           => 'Unƿæccan',
'unwatchthispage'   => 'Ƿæccende healtian',
'watchlist-details' => '{{PLURAL:$1|$1 sīde|$1 sīdan}} on þīnre ƿæccȝetale, ne beinnende ȝespreċsīdan.',
'watchlistcontains' => 'Þīn behealdnestalu hæfþ $1 {{PLURAL:$1|trameta|trametas}} inn.',
'wlnote'            => 'Under sind þā æftemestan $1 hweorfunga in þǣm æftemestum <b>$2</b> stundum.',
'wlshowlast'        => 'Īewan æftemestan $1 stunda $2 daga $3',
'watchlist-options' => 'Ƿæccȝetale forebearƿan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ƿæccende...',
'unwatching' => 'Unƿæccende...',

'enotif_newpagetext'           => 'Þēos is nīƿu sīde.',
'enotif_impersonal_salutation' => '{{SITENAME}} brūcend',
'changed'                      => 'hƿorfen',
'created'                      => 'ȝescapen',
'enotif_lastvisited'           => 'Sēo $1 for eall hƿearfas siþþan þīn læst cyme.',
'enotif_lastdiff'              => 'Sēo $1 to sēonne þisne hƿearf.',
'enotif_anon_editor'           => 'uncūþ brūcend $1',

# Delete
'deletepage'            => 'Sīdan āfeorsian',
'excontent'             => "innung ƿæs: '$1'",
'excontentauthor'       => "innung ƿæs: '$1' (and se āna forðiend ƿæs '[[Special:Contributions/$2|$2]]')",
'exblank'               => 'tramet wæs ǣmtig',
'historywarning'        => 'Warnung: Se tramet, þone þu āfeorsian teohhast, hæfþ stǣre:',
'actioncomplete'        => 'Ƿeorcdǣd fuldōn',
'deletedarticle'        => 'āfeorsode "[[$1]]"',
'dellogpage'            => 'Āfeorsunge ƿīsbōc',
'deletionlog'           => 'āfeorsunge wisbōc',
'deletecomment'         => 'Racu:',
'deleteotherreason'     => 'Ōðra/ēaca racu:',
'deletereasonotherlist' => 'Ōðru racu',

# Rollback
'rollback_short' => 'Edhƿeorfan',
'rollbacklink'   => 'Edhƿeorfan',
'rollbackfailed' => 'Edhƿeorf misfangen',
'editcomment'    => "Sēo ādihtungymbsprǣc wæs: \"''\$1''\".",
'revertpage'     => 'Ācierde ādihtunga fram [[Special:Contributions/$2|$2]] ([[User talk:$2|Gesprec]]); wendede on bæc tō ǣrran fadunge fram [[User:$1|$1]]',

# Protect
'protectlogpage'              => 'Beorges ƿīsbōc',
'protectedarticle'            => 'borgen "[[$1]]"',
'unprotectedarticle'          => 'unborgen "[[$1]]"',
'protect-title'               => 'Beorges emnet hƿeorfan for "$1"',
'prot_1movedto2'              => '[[$1]] ȝefered tō [[$2]]',
'protectcomment'              => 'Racu:',
'protectexpiry'               => 'Endaþ:',
'protect_expiry_invalid'      => 'Endende tīde is unriht.',
'protect_expiry_old'          => 'Endende tīde is in ȝēara dagum.',
'protect-text'                => "Þū meaht þæt beorges emnet sēon and hƿeorfan hēr for þǣre sīdan '''<nowiki>$1</nowiki>'''.",
'protect-default'             => 'Eall brūcendas þafian',
'protect-fallback'            => '"$1" þafunge ābiddan',
'protect-level-autoconfirmed' => 'Nīƿe and unbōcen brūcendas fortȳnan',
'protect-level-sysop'         => 'Efne for beƿitendum',
'protect-summary-cascade'     => 'beflōƿende',
'protect-expiring'            => 'endaþ $1 (UTC)',
'protect-cascade'             => 'Sīdan beorgan beinnodon þisse sīdan (flōƿende ȝebeorg)',
'protect-cantedit'            => 'Þū ne meaht þæt beorges emnet hƿeorfan þisre sīdan, forþǣm ne hæfst þū þafunge to ādihtenne hīe.',
'protect-expiry-options'      => '1 stund:1 hour,1 dæg:1 day,1 wucu:1 week,2 wuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 gēar:1 year,unendiendlic:infinite',
'restriction-type'            => 'Þafung:',
'restriction-level'           => 'Ȝehæftes emnet:',

# Restrictions (nouns)
'restriction-edit'   => 'Ādihtan',
'restriction-move'   => 'Gān',
'restriction-create' => 'Scieppan',
'restriction-upload' => 'Forþsendan',

# Restriction levels
'restriction-level-sysop'         => 'fulborgen',
'restriction-level-autoconfirmed' => 'sāmborgen',
'restriction-level-all'           => 'ǣniȝ emnet',

# Undelete
'undeletebtn'            => 'Edstaðola!',
'undeletelink'           => 'sēon/nīƿian',
'undeleteviewlink'       => 'sēon',
'undeletedarticle'       => 'edstaðolod "[[$1]]"',
'undelete-search-submit' => 'Sēċan',

# Namespace form on various pages
'namespace'      => 'Namanstede:',
'invert'         => 'Cyre edƿendan',
'blanknamespace' => '(Hēafod)',

# Contributions
'contributions'       => 'Brūcendforðunga',
'contributions-title' => 'Brūcendforðunga for $1',
'mycontris'           => 'Mīna forðunga',
'contribsub2'         => 'For $1 ($2)',
'uctop'               => '(hēafod)',
'month'               => 'Fram mōnþe (and ǣror)',
'year'                => 'Fram ȝēare (and ǣror)',

'sp-contributions-talk'     => 'ȝespreċ',
'sp-contributions-search'   => 'Forðunga sēċan',
'sp-contributions-username' => 'IP address oþþe brūcendnama:',
'sp-contributions-submit'   => 'Sēċan',

# What links here
'whatlinkshere'            => 'Hƿæt hæfþ hlenċan hider',
'whatlinkshere-title'      => 'Sīdan þe hlenċan habbaþ to "$1"',
'whatlinkshere-page'       => 'Sīde:',
'linkshere'                => "Þā folgenda sīdan habbaþ hlenċan þe to þisse sīdan lǣdan: '''[[:$1]]'''",
'nolinkshere'              => 'Nāne trametas bindaþ hider.',
'isredirect'               => 'edlǣdungtramet',
'istemplate'               => 'bysentraht',
'isimage'                  => 'biliþhlenċ',
'whatlinkshere-links'      => '← hlenċan',
'whatlinkshere-hideredirs' => '$1 edlǣdas',
'whatlinkshere-hidetrans'  => '$1 bysentraht',
'whatlinkshere-hidelinks'  => '$1 hlenċan',
'whatlinkshere-filters'    => 'Seohhunga',

# Block/unblock
'blockip'                  => 'Brūcend fortȳnan',
'ipbreason'                => 'Racu:',
'ipbreasonotherlist'       => 'Ōðeru racu',
'ipbreason-dropdown'       => '*Gemǣna gǣlungraca
** Insettung falses gefrǣges
** Āfēorsung innunge of trametum
** Spamming benda tō ūtanweardum webbstedum
** Insettung gedofes/dwolunge intō trametum
** Þrǣstiendlicu gebǣrnes/tirgung
** Miswendung manigfealdra brūcendhorda
** Uncwēme brūcendnama',
'ipbsubmit'                => 'Þisne brūcend gǣlan',
'ipbother'                 => 'Ōðeru tīd',
'ipboptions'               => '2 stunda:2 hours,1 dæȝ:1 day,3 dagas:3 days,1 ƿucu:1 week,2 ƿuca:2 weeks,1 mōnaþ:1 month,3 mōnþas:3 months,6 mōnþas:6 months,1 ȝēar:1 year,unendiend:infinite',
'ipbotheroption'           => 'ōðer',
'ipbotherreason'           => 'Ōðeru/geīecendlicu racu:',
'ipblocklist-submit'       => 'Sēċan',
'infiniteblock'            => 'unendiende',
'expiringblock'            => 'forealdaþ $1 $2',
'blocklink'                => 'fortȳnan',
'unblocklink'              => 'unfortȳnan',
'change-blocklink'         => 'Fortȳne hƿeorfan',
'contribslink'             => 'forðunga',
'unblocklogentry'          => 'unfortȳnode $1',
'block-log-flags-nocreate' => 'Hordcleofan scieppende forboden',
'proxyblocksuccess'        => 'Ȝedōn.',

# Move page
'movearticle'     => 'Sīdan ȝeferan:',
'newtitle'        => 'To nīƿum name:',
'move-watch'      => 'Frumasīdan and endesīdan ƿæccan',
'movepagebtn'     => 'Sīdan ȝeferan',
'pagemovedsub'    => 'Ȝefōr spēdde',
'movepage-moved'  => '\'\'\'"$1" ƿæs to "$2"\'\'\' ȝefered',
'articleexists'   => 'Tramet on þǣm naman ǣr stendeþ, oþþe þone
naman þu cēas nis andfenge.
Bidde cēos ōðerne naman.',
'movedto'         => 'ȝefered to',
'movetalk'        => 'Ȝesibbed ȝespreċsīdan ȝeferan',
'1movedto2'       => '[[$1]] ȝefered to [[$2]]',
'1movedto2_redir' => '[[$1]] ȝefered to [[$2]] ofer edlǣdunge',
'movelogpage'     => 'Ȝeferan ealdhord',
'movereason'      => 'Racu:',
'revertmove'      => 'Undōn',

# Export
'export' => 'Sīdan ūtsendan',

# Namespace 8 related
'allmessagesname'               => 'Nama',
'allmessagesdefault'            => 'Fūsliċ traht',
'allmessagescurrent'            => 'Nū traht',
'allmessages-filter-unmodified' => 'Unhƿorfen',
'allmessages-filter-all'        => 'Eall',
'allmessages-filter-modified'   => 'Hƿorfen',
'allmessages-language'          => 'Sprǣċ:',
'allmessages-filter-submit'     => 'Gān',

# Thumbnails
'thumbnail-more' => 'Mǣrsian',
'filemissing'    => 'Þrǣd unandƿeard',

# Special:Import
'import'                  => 'Sīdan inbringan',
'import-interwiki-submit' => 'Inbringan',
'importstart'             => 'Inbringende sīdan...',
'importnopages'           => 'Nān sīdan to inbringenne.',
'importfailed'            => 'Inbringung tōsǣlede: $1',
'importnotext'            => 'Ǣmtiȝ oþþe nān traht',
'importsuccess'           => 'Inbringoþ ȝesǣled!',
'import-noarticle'        => 'Nān sīde to inbringenne!',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Þīnu brūcendsīde',
'tooltip-pt-mytalk'               => 'Þīnu ȝespreċsīde',
'tooltip-pt-preferences'          => 'Þīna forebearƿan',
'tooltip-pt-watchlist'            => 'Sēo ȝetalu sīdena þe ƿæccest þū for hƿearfum',
'tooltip-pt-mycontris'            => 'Ȝetalu þīnra forðunga',
'tooltip-pt-login'                => 'Man þē byldeþ to inmeldienne; þēah, þis nis ābeden',
'tooltip-pt-logout'               => 'Ūtmeldian',
'tooltip-ca-talk'                 => 'Ȝespreċ ymbe þǣre innoþsīdan',
'tooltip-ca-edit'                 => 'Þū meaht þās sīdan ādihtan. Bidde brūc þone forescēaƿecnæpp fore spariende',
'tooltip-ca-addsection'           => 'Nīƿe dǣl beȝinnan',
'tooltip-ca-viewsource'           => 'Þēos sīde is borgen.
Þū meaht hire fruman sēon.',
'tooltip-ca-history'              => 'Ǣror fadunga þisse sīdan',
'tooltip-ca-protect'              => 'Þās sīdan beorgan',
'tooltip-ca-unprotect'            => 'Þās sīdan unbeorgan',
'tooltip-ca-delete'               => 'Þās sīdan āfeorsian',
'tooltip-ca-move'                 => 'Þās sīdan ȝeferan',
'tooltip-ca-watch'                => 'Þās sīdan ēacian to þīnre ƿæccȝetale',
'tooltip-ca-unwatch'              => 'Þās sīdan forniman ƿiþ þīne ƿæccȝetale',
'tooltip-search'                  => 'Sēcan {{SITENAME}}',
'tooltip-search-go'               => 'To sīdan gān ȝif bēo þes rihtnama',
'tooltip-search-fulltext'         => 'Þā sīdan sēċan mid þissum trahte',
'tooltip-p-logo'                  => 'Hēafodsīde',
'tooltip-n-mainpage'              => 'Þǣre hēafodsīdan gān',
'tooltip-n-mainpage-description'  => 'Þǣre hēafodsīdan gān',
'tooltip-n-portal'                => 'Ymbe þǣm ƿeorce, hƿæt meaht þū dōn, hƿǣr to findenne þing',
'tooltip-n-currentevents'         => 'Ieldran cȳþþe findan ymbe nīƿum ȝelimpum',
'tooltip-n-recentchanges'         => 'Sēo ȝetalu nīƿa hƿearfa in þǣre ƿiki',
'tooltip-n-randompage'            => 'Hlīeta sīdan hladan',
'tooltip-n-help'                  => 'Cunnunge stede',
'tooltip-t-whatlinkshere'         => 'Ȝetalu eallra ƿikisīdan þe mid hlenċum hider habbaþ',
'tooltip-t-recentchangeslinked'   => 'Nīƿe hƿearfas in sīdum mid hlenċum fram þisse sīdan',
'tooltip-feed-rss'                => 'RSS strēam for þisse sīdan',
'tooltip-feed-atom'               => 'Atom strēam for þisse sīdan',
'tooltip-t-contributions'         => 'Þā ȝetale sēon þāra forðunga þisses brūcendes',
'tooltip-t-emailuser'             => 'E-mail to þissum brūcende sendan',
'tooltip-t-upload'                => 'Fīlan forþsendan',
'tooltip-t-specialpages'          => 'Ȝetalu eallra syndriȝa sīdena',
'tooltip-t-print'                 => 'Ȝemǣnendliċu fadung þisse sīdan',
'tooltip-t-permalink'             => 'Fæst hlenċe for þisse fadunge þǣre sīdan',
'tooltip-ca-nstab-main'           => 'Þā innoþsīdan sēon',
'tooltip-ca-nstab-user'           => 'Þā brūcendsīdan sēon',
'tooltip-ca-nstab-special'        => 'Þēos is syndriȝu sīde, þū ne meaht þā sīdan hireself ādihtan',
'tooltip-ca-nstab-project'        => 'Þā ƿeorces sīdan sēon',
'tooltip-ca-nstab-image'          => 'Þā fīlsīde sēon',
'tooltip-ca-nstab-template'       => 'Þæt bysen sēon',
'tooltip-ca-nstab-category'       => 'Þā floccsīdan sēon',
'tooltip-minoredit'               => 'Þis sƿā lȳtl ādiht mearcian',
'tooltip-save'                    => 'Þīnne hƿearfas sparian',
'tooltip-preview'                 => 'Forescēaƿe þīne hƿearfas, bidde brūc þis fore sparest þū!',
'tooltip-diff'                    => 'Þā hƿearfas sēon þe dydest þū þǣm trahte',
'tooltip-compareselectedversions' => 'Þā tōdāl sēon betƿēonan þǣre tƿǣm coren fadungum þisse sīdan',
'tooltip-watch'                   => 'Þās sīdan ēacian to þīnre ƿæccȝetale',
'tooltip-undo'                    => '"Undōn" undēþ þisne ādiht and þæt ādihtcynd openaþ in forescēaƿemōde. Þis þafaþ race ēaciende in þǣre scortnesse.',

# Attribution
'anonymous' => 'Namcūþlēas(e) brūcend {{SITENAME}}n',
'siteuser'  => '{{SITENAME}}n brūcere $1',
'others'    => 'ōðru',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|uncūþ brūcend|uncūðe brūcendas}} $1',

# Info page
'numedits'     => 'Hū mæniȝ ādihtas (sīde): $1',
'numtalkedits' => 'Hū mæniȝ ādihtas (ȝespreċsīde): $1',
'numwatchers'  => 'Hū mæniȝ ƿæcceras: $1',

# Math errors
'math_unknown_error' => 'uncūþ ƿōh',

# Patrol log
'patrol-log-auto' => '(selffremmende)',
'patrol-log-diff' => 'nīƿung $1',

# Browsing diffs
'previousdiff' => '← Ieldra ādiht',
'nextdiff'     => 'Nīƿra ādiht',

# Media information
'imagemaxsize'         => 'Settan biliðu on biliþgemearcungtrametum tō:',
'thumbsize'            => 'Þumannæglmicelnes:',
'file-info-size'       => '($1 × $2 pixels, fīlmiċelu: $3, MIMEcynn: $4)',
'file-nohires'         => '<small>Þǣr nis nǣniȝ mā miċelu.</small>',
'svg-long-desc'        => '(SVG fīl, rihte $1 × $2 pixels, fīlmiċelu: $3)',
'show-big-image'       => 'Fulmiċelu',
'show-big-image-thumb' => '<small>Þēos forescēaƿe miċelu: $1 × $2 pixels</small>',

# Special:NewFiles
'imagelisttext' => 'Under is getalu $1 biliða gedæfted $2.',
'noimages'      => 'Nāht tō sēonne.',
'ilsubmit'      => 'Sēċan',
'bydate'        => 'be tælmearce',

# Metadata
'metadata'          => 'Metacȳþþu',
'metadata-expand'   => 'Oferȝehanda sēon',
'metadata-collapse' => 'Oferȝehanda hȳdan',

# EXIF tags
'exif-imagewidth'       => 'Ƿīdnes',
'exif-imagelength'      => 'Hīehþ',
'exif-compression'      => 'Ȝeþryccungmōd',
'exif-ycbcrpositioning' => 'Y and C ȝesetednes',
'exif-imagedescription' => 'Biliðes nama',
'exif-artist'           => 'Fruma',
'exif-usercomment'      => 'Brūcendes trahtnunga',
'exif-exposuretime'     => 'Blicestīd',
'exif-brightnessvalue'  => 'Beorhtnes',
'exif-lightsource'      => 'Lēohtfruma',
'exif-whitebalance'     => 'Hƿītefnetta',
'exif-sharpness'        => 'Scearpnes',
'exif-gpslatituderef'   => 'Norþ oþþe sūþ brǣdu',
'exif-gpslatitude'      => 'Brǣdu',
'exif-gpslongituderef'  => 'Ēast oþþe ƿest lengu',
'exif-gpslongitude'     => 'Lengu',
'exif-gpsmeasuremode'   => 'Mētungmōd',
'exif-gpsimgdirection'  => 'Rihtung þæs biliðes',

# EXIF attributes
'exif-compression-1' => 'Unȝeþrycced',

'exif-meteringmode-0'   => 'Uncūþ',
'exif-meteringmode-1'   => 'Ȝeþēaƿisc',
'exif-meteringmode-6'   => 'Sām',
'exif-meteringmode-255' => 'Ōðer',

'exif-lightsource-0' => 'Uncūþ',
'exif-lightsource-1' => 'Dæȝeslēoht',

# Flash modes
'exif-flash-mode-3' => 'selffremmende mōd',

'exif-focalplaneresolutionunit-2' => 'ynċas',

'exif-exposuremode-1' => 'Handlic blice',

'exif-whitebalance-0' => 'Selffremmende hƿītefnetta',

'exif-scenecapturetype-1' => 'Landsceap',

'exif-gaincontrol-0' => 'Nān',

'exif-contrast-1' => 'Sōfte',
'exif-contrast-2' => 'Heard',

'exif-sharpness-1' => 'Sōfte',
'exif-sharpness-2' => 'Heard',

'exif-subjectdistancerange-2' => 'Nēah hāƿung',
'exif-subjectdistancerange-3' => 'Feorr hāƿung',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Norþ brǣdu',
'exif-gpslatitude-s' => 'Sūþ brǣdu',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ēast lengu',
'exif-gpslongitude-w' => 'Ƿest lengu',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sōþ rihtung',

# External editor support
'edit-externally-help' => '(Þā [http://www.mediawiki.org/wiki/Manual:External_editors ȝearƿunga tyhtas] sēon for mā cȳþþe)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'eall',
'imagelistall'     => 'eall',
'watchlistall2'    => 'eall',
'namespacesall'    => 'eall',
'monthsall'        => 'eall',
'limitall'         => 'eall',

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
'scarytranscludetoolong' => '[URL is tō lang]',

# Multipage image navigation
'imgmultigo' => 'Gān!',

# Table pager
'table_pager_first'        => 'Forma tramet',
'table_pager_last'         => 'Hindemesta tramet',
'table_pager_limit_submit' => 'Gān',
'table_pager_empty'        => 'Nān becymas',

# Auto-summaries
'autosumm-blank' => 'Þā sīdan blæċode',
'autosumm-new'   => "Sīdan mid '$1' ȝescapen",

# Watchlist editor
'watchlistedit-noitems'       => 'Þīnu ƿæccȝetalu ne hæfþ nǣniȝ naman.',
'watchlistedit-normal-title'  => 'Ƿæccȝetale ādihtan',
'watchlistedit-normal-legend' => 'Naman forniman ƿiþ ƿæccȝetale',
'watchlistedit-normal-submit' => 'Naman forniman',
'watchlistedit-raw-titles'    => 'Naman:',
'watchlistedit-raw-done'      => 'Þīnu ƿæccȝetalu nīƿode.',

# Watchlist editing tools
'watchlisttools-view' => 'Ƿeorþliċe hƿearfas sēon',
'watchlisttools-edit' => 'Ƿæccȝetale sēon and ādihtan',
'watchlisttools-raw'  => 'Grēne ƿæccȝetale ādihtan',

# Special:Version
'version'              => 'Fadung',
'version-specialpages' => 'Syndriȝa sīdan',
'version-other'        => 'Ōðer',
'version-hooks'        => 'Anglas',
'version-hook-name'    => 'Angelnama',
'version-version'      => '(Fadung $1)',

# Special:FilePath
'filepath'        => 'Fīlpæþ',
'filepath-page'   => 'Fīl:',
'filepath-submit' => 'Gān',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Fīlnama:',
'fileduplicatesearch-submit'   => 'Sēċan',

# Special:SpecialPages
'specialpages'             => 'Syndriȝa sīdan',
'specialpages-group-other' => 'Ōðra syndriȝa sīdan',
'specialpages-group-users' => 'Brūcendas and riht',

# Special:BlankPage
'blankpage' => 'Blæċu sīde',

# Special:Tags
'tags-edit' => 'ādihtan',

# HTML forms
'htmlform-submit'              => 'Forþsendan',
'htmlform-reset'               => 'Hƿearfas undōn',
'htmlform-selectorother-other' => 'Ōðer',

);
