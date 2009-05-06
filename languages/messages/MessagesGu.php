<?php
/** Gujarati (ગુજરાતી)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aksi great
 * @author Dineshjk
 * @author Dsvyas
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'દ્રશ્ય-શ્રાવ્ય_(મિડિયા)',
	NS_SPECIAL          => 'વિશેષ',
	NS_TALK             => 'ચર્ચા',
	NS_USER             => 'સભ્ય',
	NS_USER_TALK        => 'સભ્યની_ચર્ચા',
	NS_PROJECT_TALK     => '$1_ચર્ચા',
	NS_FILE             => 'ચિત્ર',
	NS_FILE_TALK        => 'ચિત્રની_ચર્ચા',
	NS_MEDIAWIKI        => 'મીડિયાવિકિ',
	NS_MEDIAWIKI_TALK   => 'મીડિયાવિકિ_ચર્ચા',
	NS_TEMPLATE         => 'ઢાંચો',
	NS_TEMPLATE_TALK    => 'ઢાંચાની_ચર્ચા',
	NS_HELP             => 'મદદ',
	NS_HELP_TALK        => 'મદદની_ચર્ચા',
	NS_CATEGORY         => 'શ્રેણી',
	NS_CATEGORY_TALK    => 'શ્રેણીની_ચર્ચા',
);

$digitTransformTable = array(
	'0' => '૦', # &#x0ae6;
	'1' => '૧', # &#x0ae7;
	'2' => '૨', # &#x0ae8;
	'3' => '૩', # &#x0ae9;
	'4' => '૪', # &#x0aea;
	'5' => '૫', # &#x0aeb;
	'6' => '૬', # &#x0aec;
	'7' => '૭', # &#x0aed;
	'8' => '૮', # &#x0aee;
	'9' => '૯', # &#x0aef;
);

$messages = array(
# User preference toggles
'tog-underline'               => 'કડીઓની નીચે લીટી (અંડરલાઇન) ઉમેરો:',
'tog-highlightbroken'         => 'અપૂર્ણ કડીઓ<a href="" class="new">ને આ રીતે</a> (alternative: like this<a href="" class="internal">?</a>) લખો.',
'tog-justify'                 => 'ફકરો લાઇનસર કરો',
'tog-hideminor'               => 'હાલમાં થયેલા ફેરફારમાં નાના ફેરફારો છુપાવો',
'tog-hidepatrolled'           => 'હાલના સલામતી માટે કરવામાં આવેલાં થયેલા ફેરફારો છુપાવો.',
'tog-newpageshidepatrolled'   => 'હાલમાં સુરક્ષા કાજે બનાવેલા નવાં પાનાંની યાદી છુપાવો',
'tog-extendwatchlist'         => 'ધ્યાનસૂચિને વિસ્તૃત કરો જેથી બધા આનુષાંગિક ફેરફારો જોઇ શકાય',
'tog-usenewrc'                => 'વર્ધિત ફેરફારો (જાવાસ્ક્રીપ્ટ જરૂરી)',
'tog-numberheadings'          => 'મથાળાંઓને આપો-આપ ક્રમ (ઑટો નંબર) આપો',
'tog-showtoolbar'             => 'ફેરફારો માટેનો ટૂલબાર બતાવો (જાવા સ્ક્રિપ્ટ)',
'tog-editondblclick'          => 'ડબલ ક્લિક દ્વારા ફેરફાર કરો (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-editsection'             => 'વિભાગોમાં [ફેરફાર કરો] કડી દ્વારા વિભાગીય ફેરફાર લાગુ કરો.',
'tog-editsectiononrightclick' => 'વિભાગના મથાળાં ને રાઇટ ક્લિક દ્વારા ફેરફાર કરવાની રીત અપનાવો. (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-showtoc'                 => 'અનુક્રમણિકા દર્શાવો (૩થી વધુ પેટા-મથાળા વાળા લેખો માટે)',
'tog-rememberpassword'        => 'આ કમ્પ્યૂટર પર મારી લોગ-ઇન વિગતો યાદ રાખો',
'tog-editwidth'               => 'ફેરફાર બોક્સની પુરી પહોળાઇ રાખો',
'tog-watchcreations'          => 'મેં લખેલા નવા લેખો મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdefault'            => 'હું ફેરફાર કરૂં તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchmoves'              => 'હું જેનું નામ બદલું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdeletion'           => 'હું હટાવું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-minordefault'            => 'બધા નવા ફેરફારો નાના તરીકે માર્ક કરો.',
'tog-previewontop'            => 'એડીટ બોક્સ પહેલાં પ્રીવ્યુ બતાવો.',
'tog-previewonfirst'          => 'પ્રથમ ફેરફાર વખતે પ્રિવ્યુ બતાવો.',
'tog-nocache'                 => 'કેશ ન કરો.',
'tog-enotifwatchlistpages'    => 'મારી ધ્યાનસૂચિમાંનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifusertalkpages'     => 'મારી ચર્ચાનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifminoredits'        => 'પાનામાં નાનાં ફેરફાર થાય ત્યારે પણ મને ઇ-મેલ મોકલો',
'tog-enotifrevealaddr'        => 'નોટીફીકેશનના ઇમેલમાં મારૂ ઇમેલ એડ્રેસ બતાવો',
'tog-shownumberswatching'     => 'ધ્યાનમાં રાખતા સભ્યોની સંખ્યા બતાવો',
'tog-fancysig'                => 'સ્વાચાલિત કડી વગરની (કાચી) સહી',
'tog-externaleditor'          => 'બીજું એડીટર વાપરો. (ફક્ત એકસપર્ટ માટે, તમારા કમ્પ્યુટરમાં સેટીંગ્સ બદલવા પડશે)',
'tog-externaldiff'            => 'ડીફોલ્ટ તરીકે એક્સટર્નલ ભેદ વાપરો (ફક્ત એક્ષપર્ટ માટે, તમારા કમ્પ્યુટરમાં સેટીંગ્સ બદલવા જરૂરી)',
'tog-uselivepreview'          => 'લાઇવ પ્રિવ્યુ જુઓ (જાવાસ્ક્રીપ્ટ જરૂરી) (પ્રાયોગીક)',
'tog-forceeditsummary'        => "કોરો 'ફેરફાર સારાંશ' ઉમેરતા પહેલા મને ચેતવો",
'tog-watchlisthideown'        => "'મારી ધ્યાનસુચી'માં મે કરેલા ફેરફારો છુપાવો",
'tog-watchlisthidebots'       => 'ધ્યાનસુચિમાં બોટ દ્વારા થયેલા ફેરફાર સંતાડો.',
'tog-watchlisthideminor'      => "'મારી ધ્યાનસુચી'માં નાનાં ફેરફારો છુપાવો",
'tog-watchlisthideliu'        => 'લોગ થયેલા સભ્ય દ્વારા કરવામાં આવેલ ફેરફાર ધ્યાનસુચીમાં છુપાવો.',
'tog-watchlisthideanons'      => 'અજાણ્યાસભ્ય દ્વારા થયેલ ફેરફાર મારી ધ્યાનસુચીમાં છુપાવો.',
'tog-watchlisthidepatrolled'  => 'સુરક્ષા કાજે કરવામાં આવેલ ફેરફાર મારી ધ્યાનસુચીમાં છુપાવો.',
'tog-nolangconversion'        => 'Fuzzy!! સામાન્ય તબદીલી રોકો.',
'tog-ccmeonemails'            => 'મે અન્યોને મોકલેલા ઇ-મેઇલની નકલ મને મોકલો',
'tog-diffonly'                => 'તફાવતની નીચે લેખ ન બતાવશો.',
'tog-showhiddencats'          => 'છુપી શ્રેણીઓ દર્શાવો',
'tog-noconvertlink'           => 'Disable link title conversion',
'tog-norollbackdiff'          => 'રોલબેક કર્યા પછીના તફાવતો છુપાવો',

'underline-always'  => 'હંમેશાં',
'underline-never'   => 'કદી નહિ',
'underline-default' => 'બ્રાઉઝરના સેટીંગ્સ પ્રમાણે',

# Dates
'sunday'        => 'રવિવાર',
'monday'        => 'સોમવાર',
'tuesday'       => 'મંગળવાર',
'wednesday'     => 'બુધવાર',
'thursday'      => 'ગુરૂવાર',
'friday'        => 'શુક્રવાર',
'saturday'      => 'શનિવાર',
'sun'           => 'રવિ',
'mon'           => 'સોમ',
'tue'           => 'મંગળ',
'wed'           => 'બુધ',
'thu'           => 'ગુરૂ',
'fri'           => 'શુક્ર',
'sat'           => 'શનિ',
'january'       => 'જાન્યુઆરી',
'february'      => 'ફેબ્રુઆરી',
'march'         => 'માર્ચ',
'april'         => 'એપ્રિલ',
'may_long'      => 'મે',
'june'          => 'જૂન',
'july'          => 'જુલાઇ',
'august'        => 'ઓગસ્ટ',
'september'     => 'સપ્ટેમ્બર',
'october'       => 'ઓકટોબર',
'november'      => 'નવેમ્બર',
'december'      => 'ડિસેમ્બર',
'january-gen'   => 'જાન્યુઆરી',
'february-gen'  => 'ફેબ્રુઆરી',
'march-gen'     => 'માર્ચ',
'april-gen'     => 'એપ્રિલ',
'may-gen'       => 'મે',
'june-gen'      => 'જૂન',
'july-gen'      => 'જુલાઇ',
'august-gen'    => 'ઓગસ્ટ',
'september-gen' => 'સપ્ટેમ્બર',
'october-gen'   => 'ઓકટોબર',
'november-gen'  => 'નવેમ્બર',
'december-gen'  => 'ડિસેમ્બર',
'jan'           => 'જાન્યુ',
'feb'           => 'ફેબ્રુ',
'mar'           => 'મા',
'apr'           => 'એપ્ર',
'may'           => 'મે',
'jun'           => 'જૂન',
'jul'           => 'જુલા',
'aug'           => 'ઓગ',
'sep'           => 'સપ્ટે',
'oct'           => 'ઓકટો',
'nov'           => 'નવે',
'dec'           => 'ડિસે',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|શ્રેણી|શ્રેણીઓ}}',
'category_header'                => 'શ્રેણી "$1"માં પાના',
'subcategories'                  => 'ઉપશ્રેણીઓ',
'category-media-header'          => 'શ્રેણી "$1"માં દ્રશ્ય કે શ્રાવ્ય સભ્યો',
'category-empty'                 => "''આ શ્રેણીમાં હાલમાં કોઇ લેખ કે અન્ય સભ્ય નથી.''",
'hidden-categories'              => '{{PLURAL:$1|છુપી શ્રેણી|છુપી શ્રેણીઓ}}',
'hidden-category-category'       => 'છુપી શ્રેણીઓ',
'category-subcat-count'          => '{{PLURAL:$2|આ શ્રેણીમાં ફક્ત નીચેની ઉપશ્રેણી છે.|આ શ્રેણીમાં કુલ  $2 પૈકીની નીચેની {{PLURAL:$1|ઉપશ્રેણી|$1 ઉપશ્રેણીઓ}} છે.}}',
'category-subcat-count-limited'  => 'આ શ્રેણીમાં નીચે મુજબની {{PLURAL:$1|ઉપશ્રેણી|$1 ઉપશ્રેણીઓ}} છે.',
'category-article-count'         => '{{PLURAL:$2|આ શ્રેણીમાં ફક્ત નીચેનું પાનું છે.|આ શ્રેણીમાં કુલ  $2 પૈકીનાં નીચેનાં {{PLURAL:$1|પાનું|$1 પાનાં}} છે.}}',
'category-article-count-limited' => 'નીચે જણાવેલ {{PLURAL:$1|પાનું|$1 પાનાં}} આ શ્રેણીમાં છે.',
'category-file-count'            => '{{PLURAL:$2|આ શ્રેણીમાં ફક્ત નીચે દર્શાવેલ દસ્તાવેજ છે.|આ શ્રેણીમાં કુલ $2 પૈકી નીચે દર્શાવેલ {{PLURAL:$1|દસ્તાવેજ|દસ્તાવેજો}} છે.}}',
'category-file-count-limited'    => 'નીચે દર્શાવેલ {{PLURAL:$1|દસ્તાવેજ|દસ્તાવેજો}} પ્રસ્તુત શ્રેણીમાં છે.',
'listingcontinuesabbrev'         => 'ચાલુ..',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "<big>'''મિડીયાવિકિ સફળતાપૂર્વક ઇન્સટોલ થયું છે.'''</big>",
'mainpagedocfooter' => 'વિકિ સોફ્ટવેર વાપરવાની માહીતિ માટે [http://meta.wikimedia.org/wiki/Help:Contents સભ્ય માર્ગદર્શિકા] જુઓ.

== શરૂઆતના તબક્કે ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings કોનફીગ્યુરેશન સેટીંગ્સની યાદી]
* [http://www.mediawiki.org/wiki/Manual:FAQ વારંવાર પુછાતા પ્રશ્નો]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce મિડીયાવિકિ રીલીઝ મેઇલીંગ લીસ્ટ]',

'about'         => 'વિષે',
'article'       => 'લેખનું પાનું',
'newwindow'     => '(નવા પાનામાં ખુલશે)',
'cancel'        => 'રદ કરો',
'moredotdotdot' => 'વધારે...',
'mypage'        => 'મારું પાનું',
'mytalk'        => 'મારી ચર્ચા',
'anontalk'      => 'આ IP માટેનું ચર્ચા પાનું',
'navigation'    => 'ભ્રમણ',
'and'           => '&#32;અને',

# Cologne Blue skin
'qbfind'         => 'શોધો',
'qbbrowse'       => 'બ્રાઉઝ',
'qbedit'         => 'ફેરફાર કરો',
'qbpageoptions'  => 'આ પાનું',
'qbpageinfo'     => 'પાનાંની જાણકારી',
'qbmyoptions'    => 'મારાં પાનાં',
'qbspecialpages' => 'ખાસ પાનાં',
'faq'            => 'FAQ
વારંવાર પુછાતા પ્રશ્નો',
'faqpage'        => 'Project:વારંવાર પુછાતા પ્રશ્નો',

# Metadata in edit box
'metadata_help' => 'મેટાડેટા:',

'errorpagetitle'   => 'ત્રુટિ',
'returnto'         => '$1 પર પાછા જાઓ.',
'tagline'          => '{{SITENAME}} થી',
'help'             => 'મદદ',
'search'           => 'શોધો',
'searchbutton'     => 'શોધો',
'go'               => 'જાઓ',
'searcharticle'    => 'જાઓ',
'history'          => 'પાનાનો ઇતિહાસ',
'history_short'    => 'ઇતિહાસ',
'info_short'       => 'માહિતી',
'printableversion' => 'છાપવા માટેની આવૃત્તિ',
'permalink'        => 'સ્થાયી કડી',
'edit'             => 'ફેરફાર કરો',
'create'           => 'બનાવો',
'editthispage'     => 'આ પાના માં ફેરફાર કરો',
'delete'           => 'હટાવો',
'deletethispage'   => 'આ પાનું હટાવો',
'protect'          => 'સુરક્ષિત કરો',
'protect_change'   => 'ફેરફાર કરો',
'newpage'          => 'નવું પાનું',
'talkpage'         => 'આ પાના વિષે ચર્ચા કરો',
'talkpagelinktext' => 'ચર્ચા',
'specialpage'      => 'ખાસ પાનુ',
'personaltools'    => 'વ્યક્તિગત સાધનો',
'talk'             => 'ચર્ચા',
'views'            => 'અવલોકનો',
'toolbox'          => 'સાધન પેટી',
'userpage'         => 'સભ્યનું પાનું જુઓ',
'viewtalkpage'     => 'ચર્ચા જુઓ',
'otherlanguages'   => 'બીજી ભાષાઓમાં',
'redirectedfrom'   => '($1 થી અહીં વાળેલું)',
'redirectpagesub'  => 'પાનું અન્યત્ર વાળો',
'lastmodifiedat'   => 'આ પાનાંમાં છેલ્લો ફેરફાર $1ના રોજ $2 વાગ્યે થયો.',
'jumpto'           => 'સીધા આના પર જાઓ:',
'jumptonavigation' => 'ભ્રમણ',
'jumptosearch'     => 'શોધો',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} વિષે',
'aboutpage'            => 'Project:વિષે',
'copyright'            => '$1 હેઠળ માહિતિ ઉપલબ્ધ છે.',
'copyrightpage'        => '{{ns:project}}:પ્રકાશનાધિકાર',
'currentevents'        => 'વર્તમાન ઘટનાઓ',
'currentevents-url'    => 'Project:વર્તમાન ઘટનાઓ',
'disclaimers'          => 'જાહેર ઇનકાર',
'disclaimerpage'       => 'Project:સામાન્ય જાહેર ઇનકાર',
'edithelp'             => 'ફેરફારો માટે મદદ',
'edithelppage'         => 'Help:ફેરફાર',
'helppage'             => 'Help:સૂચિ',
'mainpage'             => 'મુખપૃષ્ઠ',
'mainpage-description' => 'મુખપૃષ્ઠ',
'portal'               => 'સમાજ મુખપૃષ્ઠ',
'portal-url'           => 'Project:સમાજ મુખપૃષ્ઠ',
'privacy'              => 'ગોપનિયતા નીતિ',
'privacypage'          => 'Project:ગોપનિયતા નીતિ',

'badaccess' => 'પરવાનગીની ખામી',

'ok'                      => 'મંજૂર',
'retrievedfrom'           => '"$1" થી લીધેલું',
'youhavenewmessages'      => 'તમારા માટે $1 ($2).',
'newmessageslink'         => 'નૂતન સંદેશ',
'newmessagesdifflink'     => 'છેલ્લો ફેરફાર',
'youhavenewmessagesmulti' => '$1 ઉપર તમારા માટે નવો સંદેશ છે.',
'editsection'             => 'ફેરફાર કરો',
'editsection-brackets'    => '[$1]',
'editold'                 => 'ફેરફાર કરો',
'viewsourceold'           => 'સ્રોત જુઓ',
'editlink'                => 'ફેરફાર',
'viewsourcelink'          => 'સ્રોત જુઓ.',
'editsectionhint'         => 'ફેરફાર કરો - પરિચ્છેદ: $1',
'toc'                     => 'અનુક્રમણિકા',
'showtoc'                 => 'બતાવો',
'hidetoc'                 => 'છુપાવો',
'thisisdeleted'           => 'જુઓ અથવા મૂળરૂપે ફેરવો $1?',
'viewdeleted'             => '$1 જોવું છે?',
'restorelink'             => '{{PLURAL:$1|એક ભુસીનાખેલો ફેરફાર|$1 ભુસીનાખેલા ફેરફારો}}',
'feedlinks'               => 'ફીડ:',
'feed-invalid'            => 'અયોગ્ય સબસ્ક્રીપ્સન ફીડ પ્રકાર.',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'red-link-title'          => '$1 (પાનું અસ્તિત્વ ધરાવતું નથી)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'લેખ',
'nstab-user'      => 'મારા વિષે',
'nstab-media'     => 'મિડીયા પાનું',
'nstab-special'   => 'ખાસ પાનું',
'nstab-project'   => 'પરિયોજનાનું પાનું',
'nstab-image'     => 'ફાઇલ/દસ્તાવેજ',
'nstab-mediawiki' => 'સંદેશ',
'nstab-template'  => 'ઢાંચો',
'nstab-help'      => 'મદદનું પાનું',
'nstab-category'  => 'શ્રેણી',

# Main script and global functions
'nosuchspecialpage' => 'એવું ખાસ પાનું નથી',

# General errors
'missing-article'      => 'ડેટાબેઝને પનાંનાં જે શબ્દો ("$1" $2) મળવા જોઈતા હતા તે મળ્યા નથી.

આવું સામાન્ય રીતે ત્યારે બને જ્યારે તમે તફાવત કે ઈતિહાસની એવી જુની કડીને અનુસરીને અહિં આવ્યા હોવ કે જે પાનું હટાવી દીધું હોય.

જો તમને ખાતરી છે કે આવું નથી, તો તમારા ભાગે સોફ્ટવેરમાં રહેલી ત્રુટી આવી છે.
કૃપા કરી આ વાત, જે તે પાનાની પૂર્ણ યુ.આર.એલ. (URL) કડી સાથે, તમારા [[Special:ListUsers/sysop|પ્રબંધક]]ના ધ્યાન પર લાવો.',
'missingarticle-rev'   => '(પુનરાવર્તન#: $1)',
'badtitle'             => 'ખરાબ નામ',
'badtitletext'         => 'આપનું ઈચ્છિત શિર્ષક અમાન્ય છે, ખાલી છે, અથવાતો અયોગ્ય રીતે આંતર-ભાષિય કે આંતર-વિકિ સાથે જોડાયેલું શિર્ષક છે.
શક્ય છે કે તેમાં એક કે વધુ એવા અક્ષર કે ચિહ્નો છે કે જે પાનાનાં શિર્ષક માટે અવૈધ છે.',
'perfcached'           => 'નીચે દર્શાવેલી માહિતિ જુના સંગ્રહમાંથી લીધેલી છે અને શક્ય છે કે તે હાલની પરિસ્થિતિમાં સચોટ ના હોય.',
'perfcachedts'         => 'નીચેની વિગતો જુના સંગ્રહમાથી છે અને તે છેલ્લે $1 સુધી અદ્યતન હતી.',
'querypage-no-updates' => 'આ પાનાની નવી આવૃત્તિઓ હાલમાં અક્રિય છે.
અહીંની વિગતો હાલમાં રિફ્રેશ કરવામાં નહી આવે.',
'viewsource'           => 'સ્ત્રોત જુઓ',
'viewsourcefor'        => '$1ને માટે',
'viewsourcetext'       => 'આપ આ પાનાંનો મૂળ સ્ત્રોત નિહાળી શકો છો અને તેની નકલ (copy) પણ કરી શકો છો:',

# Login and logout pages
'yourname'                => 'સભ્ય નામ:',
'yourpassword'            => 'ગુપ્ત સંજ્ઞા:',
'remembermypassword'      => 'આ કોમ્યૂટર પર મારી લૉગ ઇન વિગતો ધ્યાનમાં રાખો',
'login'                   => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'nav-login-createaccount' => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'loginprompt'             => '{{SITENAME}}માં પ્રવેશ કરવા માટે તમારા બ્રાઉઝરમાં કુકીઝ એનેબલ કરેલી હોવી જોઇશે.',
'userlogin'               => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'logout'                  => 'બહાર નીકળો',
'userlogout'              => 'બહાર નીકળો/લૉગ આઉટ',
'nologin'                 => 'શું તમારૂં ખાતું નથી? તો નવું $1.',
'nologinlink'             => 'ખાતું ખોલો',
'createaccount'           => 'નવું ખાતું ખોલો',
'gotaccount'              => 'પહેલેથી ખાતું ખોલેલું છે? $1.',
'gotaccountlink'          => 'પ્રવેશો (લૉગ ઇન કરો)',
'loginsuccesstitle'       => 'પ્રવેશ સફળ',
'loginsuccess'            => "'''તમે હવે {{SITENAME}}માં \"\$1\" તરીકે પ્રવેશી ચુક્યા છો.'''",
'nosuchuser'              => '"$1" નામ ધરાવતો કોઇ સભ્ય અસ્તિત્વમાં નથી.
કૃપા કરી સ્પેલીંગ/જોડણી ચકાસો અથવા [[Special:UserLogin/signup|નવું ખાતુ ખોલો]].',
'nosuchusershort'         => '"<nowiki>$1</nowiki>" નામનો કોઇ સભ્ય નથી, તમારી જોડણી તપાસો.',
'nouserspecified'         => 'તમારે સભ્ય નામ દર્શાવવાની જરૂર છે.',
'wrongpassword'           => 'તમે લખેલી ગુપ્ત સંજ્ઞા ખોટી છે.
ફરીથી પ્રયત્ન કરો.',
'wrongpasswordempty'      => 'તમે ગુપ્ત સંજ્ઞા લખવાનું ભુલી ગયા લાગો છો.
ફરીથી પ્રયત્ન કરો.',
'passwordtooshort'        => 'તમે દાખલ કરેલી ગુપ્ત સંજ્ઞા ખુબજ ટુંકી છે અથવા અસ્વિકાર્ય છે.
તેમાં ઓછામાં {{PLURAL:$1|ઓછો એક અક્ષર હોવો |ઓછા $1 અક્ષર હોવા}} જોઇએ અને તેમાં તમારા સભ્ય નામનો સમાવેશ ના થવો જોઇએ.',
'mailmypassword'          => 'પાસવર્ડ ઇ-મેલમાં મોકલો',
'passwordremindertitle'   => '{{SITENAME}} માટેની નવી કામચલાઉ ગુપ્ત સંજ્ઞા',
'noemail'                 => 'સભ્ય "$1"નું કોઇ ઇ-મેલ સરનામું નોંધાયેલું નથી.',
'accountcreated'          => 'ખાતું ખોલવામાં આવ્યું છે',

# Password reset dialog
'retypenew' => 'નવી ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો:',

# Edit page toolbar
'bold_sample'     => 'ઘાટા અક્ષર',
'bold_tip'        => 'ઘાટા અક્ષર',
'italic_sample'   => 'ત્રાંસા અક્ષર',
'italic_tip'      => 'ઇટાલિક (ત્રાંસુ) લખાણ',
'link_sample'     => 'કડીનું શિર્ષક',
'link_tip'        => 'આંતરિક કડી',
'extlink_sample'  => 'http://www.example.com કડીનું શિર્ષક',
'extlink_tip'     => "બાહ્ય કડી (શરૂઆતામાં '''http://''' ઉમેરવાનું ભુલશો નહી)",
'headline_sample' => 'મથાળાનાં મોટા અક્ષર',
'headline_tip'    => 'બીજા ક્રમનું મથાળું',
'math_sample'     => 'સૂત્ર અહીં દાખલ કરો',
'math_tip'        => 'ગણિતિક સૂત્ર (LaTeX)',
'nowiki_sample'   => 'ફોર્મેટ કર્યા વગરનું લખાણ અહીં ઉમેરો',
'nowiki_tip'      => 'વિકિ ફોર્મેટીંગને અવગણો',
'image_tip'       => 'અંદર વણાયેલી (Embedded) ફાઇલ',
'media_tip'       => 'ફાઇલની કડી',
'sig_tip'         => 'તમારી સહી (સમય સાથે)',
'hr_tip'          => 'આડી લીટી (શક્ય તેટલો ઓછો ઉપયોગ કરો)',

# Edit pages
'summary'                          => 'સારાંશ:',
'subject'                          => 'વિષય/શિર્ષક:',
'minoredit'                        => 'આ એક નાનો સુધારો છે.',
'watchthis'                        => 'આ પાનાને ધ્યાનમાં રાખો',
'savearticle'                      => 'સાચવો',
'preview'                          => 'પૂર્વાવલોકન',
'showpreview'                      => 'ઝલક',
'showdiff'                         => 'ફેરફારો',
'anoneditwarning'                  => "'''ચેતવણી:''' તમે તમારા સભ્ય નામથી પ્રવેશ કર્યો નથી.
આ પાનાનાં ઇતિહાસમાં તમારૂં આઇ.પી. (IP) એડ્રેસ નોંધવામાં આવશે.",
'summary-preview'                  => 'સારાંશ પૂર્વાવલોકન:',
'blockedtext'                      => "<big>'''આપનાં સભ્ય નામ અથવા આઇ.પી. એડ્રેસ પર પ્રતિબંધ મુકવામાં આવ્યો છે.'''</big>

આ પ્રતિબંધ  $1એ મુક્યો છે.
જેને માટે કારણ આપવામાં આવ્યું છે કે, ''$2''.

* પ્રતિબંધ મુક્યા તારીખ: $8
* પ્રતિબંધ ઉઠાવવાની તારીખ: $6
* જેના ઉપર પ્રતિબંધ મુક્યો છે તે: $7

આપનાં પર મુકવામાં આવેલાં પ્રતિબંધ વિષે ચર્ચા કરવા માટે આપ $1નો કે અન્ય [[{{MediaWiki:Grouppage-sysop}}|પ્રબંધક]]નો સંપર્ક કરી શકો છો.
આપ 'સભ્યને ઇ-મેલ કરો' ની કડી વાપરી નહી શકો, પરંતુ જો આપનાં [[Special:Preferences|મારી પસંદ]]માં યોગ્ય ઇ-મેલ સરનામું વાપર્યું હશે અને તમારા તે ખાતું વાપરવા ઉપર પ્રતિબંધ નહી મુક્યો હોય તો તમે તે કડીનો ઉપયોગ કરી શકશો.
તમારૂં હાલનું આઇ.પી સરનામું છે $3, અને જેના પર પ્રતિબંધ મુકવામાં આવ્યો છે તે છે  #$5.
મહેરબાની કરીને કોઇ પણ પત્ર વ્યવહારમાં ઉપરની બધીજ માહિતિનો ઉલ્લેખ કરશો.",
'blockededitsource'                => "'''$1''' માટે '''તમારા ફેરફારો''' નીચે દેખાય છે:",
'newarticle'                       => '(નવિન)',
'newarticletext'                   => "આપ જે કડીને અનુસરીને અહીં પહોંચ્યા છો તે પાનું અસ્તિત્વમાં નથી.
<br />નવું પાનું બનાવવા માટે નીચે આપેલા ખાનામાં લખવાનું શરૂ કરો (વધુ માહિતિ માટે [[{{MediaWiki:Helppage}}|મદદ]] જુઓ).
<br />જો આપ ભુલમાં અહીં આવી ગયા હોવ તો, આપનાં બ્રાઉઝર નાં '''બેક''' બટન પર ક્લિક કરીને પાછા વળો.",
'noarticletext'                    => 'આ પાનામાં હાલમાં કોઇ માહિતિ નથી.
તમે  [[Special:Search/{{PAGENAME}}|આ શબ્દ]] ધરાવતાં અન્ય લેખો શોધી શકો છો, <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} સંલગ્ન માહિતિ પત્રકોમાં શોધી શકો છો],
અથવા  [{{fullurl:{{FULLPAGENAME}}|action=edit}} આ પાનામાં ફેરફાર કરી] માહિતિ ઉમેરવાનું શરૂ કરી શકો છો</span>.',
'previewnote'                      => "'''આ ફક્ત પૂર્વાવલોકન છે;
ફેરફ્ફરો હજુ સુરક્ષિત કરવામાં નથી આવ્યાં!'''",
'editing'                          => '$1નો ફેરફાર કરી રહ્યા છે',
'editingsection'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'yourdiff'                         => 'ભેદ',
'copyrightwarning'                 => "મહેરબાની કરીને એ વાતની નોંધ લેશો કે {{SITENAME}}માં કરેલું બધુંજ યોગદાન $2 હેઠળ પ્રકાશિત કરએલું માનવામાં આવે છે (વધુ માહિતિ માટે $1 જુઓ).
<br />જો આપ ના ચાહતા હોવ કે તમારા યોગદાનમાં અન્ય કોઇ વ્યક્તિ બેધડક પણે ફેરફાર કરે અને તેને પુનઃપ્રકાશિત કરે, તો અહીં યોગદાન કરશો નહી.
<br />સાથે સાથે તમે અમને એમ પણ ખાતરી આપી રહ્યા છો કે આ લખાણ તમે મૌલિક રીતે લખ્યું છે, અથવાતો પબ્લિક ડોમેઇન કે તેવા અન્ય મુક્ત સ્ત્રોતમાંથી લીધું છે.
<br />'''પરવાનગી વગર પ્રકાશનાધિકાર થી સુરક્ષિત (COPYRIGHTED) કાર્ય અહીં પ્રકાશિત ના કરશો!'''",
'longpagewarning'                  => "'''ચેતવણી: આ પાનું $1 કિલોબાઇટ્સ લાંબુ છે;
કેટલાંક બ્રાઉઝરોમાં લગભગ ૩૨ કિલોબાઇટ્સ જેટલાં કે તેથી મોટાં પાનાઓમાં ફેરફાર કરવામાં મુશ્કેલી પડી શકે છે.
બને ત્યાં સુધી પાનાને નાનાં વિભાગોમાં વિભાજીત કરી નાંખો.'''",
'templatesused'                    => 'આ પાનામાં વપરાયેલા ઢાંચાઓ:',
'templatesusedpreview'             => 'આ પૂર્વાવલોકનમાં વપરાયેલાં ઢાંચાઓ:',
'template-protected'               => '(સુરક્ષિત)',
'template-semiprotected'           => '(અર્ધ સુરક્ષિત)',
'hiddencategories'                 => 'આ પાનું {{PLURAL:$1|૧ છુપી શ્રેણી|$1 છુપી શ્રેણીઓ}}નું સભ્ય છે:',
'nocreatetext'                     => '{{SITENAME}}માં નવું પાનુ બનાવવા ઉપર નિયંત્રણ આવી ગયું છે.
<br />આપ પાછા જઇને હયાત પાનામાં ફેરફાર કરી શકો છો, નહિતર [[Special:UserLogin|પ્રવેશ કરો કે નવું ખાતું ખોલો]].',
'permissionserrorstext-withaction' => '$2 પરવાનગી તમને નીચેનાં {{PLURAL:$1|કારણ|કારણો}} સર નથી:',
'recreate-moveddeleted-warn'       => "'''ચેતવણી: તમે જે પાનું નવું બનાવવા જઇ રહ્યાં છો તે પહેલાં દૂર કરવામાં આવ્યું છે.'''

આગળ વધતાં બે વખત વિચારજો અને જો તમને લાગે કે આ પાનું ફરી વાર બનાવવું ઉચિત છે, તો જ અહીં ફેરફાર કરજો.
પાનું હટવ્યાં પહેલાનાં બધા ફેરફારોની સૂચિ તમારી સહુલીયત માટે અહીં આપી છે:",

# Account creation failure
'cantcreateaccounttitle' => 'ખાતું ખોલી શકાય તેમ નથી',

# History pages
'viewpagelogs'           => 'આ પાનાનાં લૉગ જુઓ',
'nohistory'              => 'આ પાનાનાં ફેરફારનો ઇતિહાસ નથી.',
'currentrev'             => 'હાલની આવૃત્તિ',
'currentrev-asof'        => '$1એ જોઈ શકાતી હાલની આવૃત્તિ',
'revisionasof'           => '$1 સુધીનાં પુનરાવર્તન',
'revision-info'          => '$2 દ્વારા $1 સુધીમાં કરવામાં આવેલાં ફેરફારો',
'previousrevision'       => '←જુના ફેરફારો',
'nextrevision'           => 'આ પછીનું પુનરાવર્તન→',
'currentrevisionlink'    => 'વર્તમાન આવૃત્તિ',
'cur'                    => 'વર્તમાન',
'next'                   => 'આગળ',
'last'                   => 'છેલ્લું',
'page_first'             => 'પહેલું',
'page_last'              => 'છેલ્લું',
'history-fieldset-title' => 'ઇતિહાસ ઉખેળો',
'histfirst'              => 'સૌથી જુનું',
'histlast'               => 'સૌથી નવું',
'historyempty'           => '(ખાલી)',

# Revision feed
'history-feed-item-nocomment' => '$1, $2 સમયે',

# Revision deletion
'rev-delundel'   => 'બતાવો/છુપાવો',
'revdel-restore' => 'વિઝિબિલિટિ બદલો',

# Merge log
'revertmerge' => 'છુટુ પાડો',

# Diffs
'history-title'           => '"$1" નાં ફેરફારોનો ઇતિહાસ',
'difference'              => '(પુનરાવર્તનો વચ્ચેનો તફાવત)',
'lineno'                  => 'લીટી $1:',
'compareselectedversions' => 'પસંદ કરેલા સરખાવો',
'editundo'                => 'રદ કરો',
'diff-multi'              => '({{PLURAL:$1|વચગાળાનું એક પુનરાવર્તન|વચગાળાનાં $1 પુનરાવર્તનો}} દર્શાવેલ નથી.)',

# Search results
'searchresults'             => 'પરિણામોમાં શોધો',
'searchresults-title'       => 'પરિણામોમાં "$1" શોધો',
'searchresulttext'          => '{{SITENAME}}માં કેવી રીતે શોધવું તેની વધુ માહિતિ માટે જુઓ: [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'તમે \'\'\'[[:$1]]\'\'\' માટે શોધ્યુ  ([[Special:Prefixindex/$1|"$1"થી શરૂ થતા બધા પાના]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"ની સાથે જોડાયેલા બધા પાના]])',
'searchsubtitleinvalid'     => "તમે '''$1''' શોધ્યું",
'noexactmatch'              => "'''\"\$1\" શિર્ષક વાળું કોઇ પાનું નથી.'''
<br />તમે [[:\$1|આ પાનું બનાવી શકો છો]].",
'noexactmatch-nocreate'     => "'''\"\$1\" શિર્ષક વાળું કોઈ પાનું નથી.'''",
'notitlematches'            => 'આ શબ્દ સાથે કોઇ શિર્ષક મળતું આવતું નથી',
'notextmatches'             => 'આ શબ્દ કોઈ પાનાંમાં મળ્યો નથી',
'prevn'                     => 'પાછળનાં $1',
'nextn'                     => 'આગળનાં $1',
'viewprevnext'              => 'જુઓ: ($1) ($2) ($3)',
'searchhelp-url'            => 'Help:સૂચિ',
'search-result-size'        => '$1 ({{બહુવચન:$2|1 શબ્દword|$2 શબ્દો}})',
'search-result-score'       => 'પ્રસ્તુતિ: $1%',
'search-redirect'           => '(અન્યત્ર પ્રસ્થાન $1)',
'search-section'            => '(વિભાગ $1)',
'search-suggest'            => 'શું તમે $1 કહેવા માંગો છો?',
'search-interwiki-caption'  => 'બંધુ પ્રકલ્પ',
'search-interwiki-default'  => '$1 પરીણામો:',
'search-interwiki-more'     => '(વધુ)',
'search-mwsuggest-enabled'  => 'સુઝાવ સહિત',
'search-mwsuggest-disabled' => 'સુઝાવ વિના',
'showingresultstotal'       => "{{PLURAL:$4|'''$3'''નું '''$1''' પરિણામ |'''$3'''નાં '''$1 - $2''' પરિણામો}} નીચે દર્શાવ્યાં છે",
'nonefound'                 => "'''નોંધ''':ફક્ત અમુકજ નામસ્થળોમાં આપોઆપ શોધાશે.
તમારા શબ્દને ''બધા:'' ઉમેરી શોધવાનો પ્રયત્ન કરો, જેથી બધી માહિતિમાં (જેમકે ચર્ચાના પાના, ઢાંચા, વિગેરે)માં શોધ થઈ શકે, અથવાતો ઇચ્છિત નામ્સ્થળ પસંદ કરી શોધો બટન દબાવો.",
'powersearch'               => 'શોધો (વધુ પર્યાય સાથે)',
'powersearch-legend'        => 'વધુ પર્યાયો સાથે શોધો',
'powersearch-ns'            => 'નામસ્થળોમાં શોધો:',
'powersearch-redir'         => 'અન્યત્ર વાળેલાં પાનાંની યાદી',
'powersearch-field'         => 'નાં માટે શોધો',
'search-external'           => 'બાહ્ય શોધ',
'searchdisabled'            => "{{SITENAME}} ઉપર શોધ બંધ કરી દેવામાં આવી છે.
ત્યાં સુધી તમે ગુગલ દ્વારા શોધ કરી શકો.
'''નોંધઃ'''{{SITENAME}}નાં તેમના (ગુગલના) ઈન્ડેક્સ જુના હોઇ શકે.",

# Quickbar
'qbsettings'      => 'શીઘ્રપટ્ટ',
'qbsettings-none' => 'કોઇપણ નહીં',

# Preferences page
'preferences'         => 'પસંદ',
'mypreferences'       => 'મારી પસંદ',
'skin-preview'        => 'ફેરફાર બતાવો',
'prefs-datetime'      => 'તારીખ અને સમય',
'prefs-watchlist'     => 'ધ્યાનસૂચિ',
'searchresultshead'   => 'શોધો',
'yourrealname'        => 'સાચું નામ:',
'yourlanguage'        => 'ભાષા',
'prefs-help-realname' => 'સાચું નામ મરજીયાત છે.
જો આપ સાચું નામ આપવાનું પસંદ કરશો, તો તેનો ઉપયોગ તમારા કરેલાં યોગદાનનું શ્રેય આપવા માટે થશે.',

# Groups
'group'       => 'સમુહ',
'group-bot'   => 'બૉટો',
'group-sysop' => 'સાઇસૉપ/પ્રબંધકો',
'group-all'   => '(બધા)',

'group-bot-member'   => 'બૉટ',
'group-sysop-member' => 'સાઇસૉપ/પ્રબંધક',

'grouppage-bot'   => '{{ns:project}}:બૉટો',
'grouppage-sysop' => '{{ns:project}}:પ્રબંધકો',

# User rights log
'rightslog'  => 'સભ્ય હક્ક માહિતિ પત્રક',
'rightsnone' => '(કોઈ નહિ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'આ પાનામાં ફેરફાર કરવાની',

# Recent changes
'nchanges'             => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',
'recentchanges'        => 'હાલ માં થયેલા ફેરફાર',
'recentchanges-legend' => 'હાલમાં થયેલા ફેરફારોના વિકલ્પ',
'rcnote'               => "નીચે $5, $4 સુધીમાં અને તે પહેલાનાં '''$2''' દિવસમાં {{PLURAL:$1| થયેલો '''1''' માત્ર ફેરફાર|થયેલાં છેલ્લા  '''$1''' ફેરફારો}} દર્શાવ્યાં છે .",
'rcnotefrom'           => "નીચે '''$2'''થી થયેલાં '''$1''' ફેરફારો દર્શાવ્યાં છે.",
'rclistfrom'           => '$1 બાદ થયેલા નવા ફેરફારો બતાવો',
'rcshowhideminor'      => 'નાના ફેરફારો $1',
'rcshowhidebots'       => 'બૉટો $1',
'rcshowhideliu'        => 'લૉગ ઇન થયેલાં સભ્યો $1',
'rcshowhideanons'      => 'અનામિ સભ્યો $1',
'rcshowhidemine'       => 'મારા ફેરફારો $1',
'rclinks'              => 'છેલ્લાં $2 દિવસમાં થયેલા છેલ્લાં $1 ફેરફારો દર્શાવો<br />$3',
'diff'                 => 'ભેદ',
'hist'                 => 'ઇતિહાસ',
'hide'                 => 'છુપાવો',
'show'                 => 'બતાવો',
'minoreditletter'      => 'નાનું',
'newpageletter'        => 'નવું',
'boteditletter'        => 'બૉટ',
'rc_categories_any'    => 'કોઇ પણ',
'rc-enhanced-expand'   => 'વિગતો બતાવો (જાવા સ્ક્રિપ્ટ જરૂરી છે)',
'rc-enhanced-hide'     => 'વિગતો છુપાવો',

# Recent changes linked
'recentchangeslinked'          => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-title'    => '"$1" ને લગતા ફેરફારો',
'recentchangeslinked-noresult' => 'સંકળાયેલાં પાનાંમાં સુચવેલા સમય દરમ્યાન કોઇ ફેરફાર થયાં નથી.',
'recentchangeslinked-summary'  => "આ એવા ફેરફારોની યાદી છે જે આ ચોક્કસ પાના (કે શ્રેણીનાં સભ્ય પાનાઓ) સાથે જોડાયેલા પાનાઓમાં તાજેતરમાં કરવામાં આવ્યા હોય.
<br />[[Special:Watchlist|તમારી ધ્યાનસૂચિમાં]] હોય તેવા પાનાં '''ઘાટા અક્ષર'''માં વર્ણવ્યાં છે",
'recentchangeslinked-page'     => 'પાનાંનું નામ:',
'recentchangeslinked-to'       => 'આને બદલે આપેલા પાનાં સાથે જોડાયેલા લેખોમાં થયેલા ફેરફારો શોધો',

# Upload
'upload'        => 'ફાઇલ ચડાવો',
'uploadbtn'     => 'ફાઇલ ચડાવો',
'reupload'      => 'ફરી ચડાવો',
'uploadlogpage' => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'filesource'    => 'સ્ત્રોત:',
'uploadedimage' => '"[[$1]]" ચઢાવ્યું',

# Special:ListFiles
'listfiles' => 'ફાઇલોની યાદી',

# File description page
'filehist'                  => 'ફાઇલનો ઇતિહાસ',
'filehist-help'             => 'તારિખ/સમય ઉપર ક્લિક કરવાથી તે સમયે ફાઇલ કેવી હતી તે જોવા મળશે',
'filehist-current'          => 'વર્તમાન',
'filehist-datetime'         => 'તારીખ/સમય',
'filehist-thumb'            => 'થમ્બનેઇલ',
'filehist-thumbtext'        => '$1મી વર્ઝન માટે થમ્બનેઇલ',
'filehist-nothumb'          => 'થમ્બનેઇલ નથી',
'filehist-user'             => 'સભ્ય',
'filehist-dimensions'       => 'પરિમાણ',
'filehist-filesize'         => 'ફાઇલનું કદ',
'filehist-comment'          => 'ટિપ્પણી',
'imagelinks'                => 'ફાઇલની કડીઓ',
'linkstoimage'              => 'આ ફાઇલ સાથે {{PLURAL:$1|નીચેનું પાનું જોડાયેલું|$1 નીચેનાં પાનાઓ જોડાયેલાં}} છે',
'linkstoimage-more'         => '$1 કરતાં વધુ {{PLURAL:$1|પાનું|પાનાંઓ}} આ ફાઇલ સાથે જોડાય છે.
નીચે જણાવેલ યાદી ફક્ત આ ફાઇલ સાથે જોડાયેલ {{PLURAL:$1|પ્રથમ પાનાંની કડી|પ્રથમ $1 પાનાંની કડીઓ}} બતાવે છે.  
અહીં [[Special:WhatLinksHere/$2|પુરી યાદી]]  મળશે.',
'nolinkstoimage'            => 'આ ફાઇલ સાથે કોઇ પાનાં જોડાયેલાં નથી.',
'morelinkstoimage'          => 'આ ફાઇલ સાથે જોડાયેલ [[Special:WhatLinksHere/$1|વધુ કડીઓ]] જુઓ.',
'redirectstofile'           => 'નીચે જણાવેલ {{PLURAL:$1|ફાઇલ|$1 ફાઇલો}} આ ફાઇલ પર વાળેલી છે:',
'sharedupload'              => 'આ ફાઇલ $1માં આવેલી છે અને શક્ય છે કે તે અન્ય પરિયોજનાઓમાં પણ વપરાતી હોય.',
'sharedupload-desc-there'   => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
વધુ માહિતી માટે મહેરબાની કરીને [$2 ફાઇલનાં વર્ણનનુ પાનું] જુઓ.',
'sharedupload-desc-here'    => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
ત્યાંનાં મૂળ [$2 ફાઇલનાં વર્ણનનાં પાનાં] પર આપેલું વર્ણન નીચે દર્શાવેલું છે.',
'noimage'                   => 'આ નામ વાળી કોઇ ફાઇલ અસ્તિત્વમાં નથી, તમે તેને $1 શકો છો.',
'noimage-linktext'          => 'ચઢાવી',
'uploadnewversion-linktext' => 'આ ફાઇલની નવી આવૃત્તિ ચઢાવો',
'shared-repo-from'          => '$1 થી',
'shared-repo'               => 'સાંઝો ભંડાર',

# File reversion
'filerevert'                => '$1 હતું તેવું કરો',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'ફાઇલ હતી તેવી કરો',
'filerevert-intro'          => "તમે '''[[Media:$1|$1]]''' ફાઇલ હતી તેવી મૂળ સ્થિતિ[$3, $2 વખતે હતું તેવું વર્ઝન $4]માં  લઇ જઇ રહ્યા છો.",
'filerevert-comment'        => 'ટીપ્પણી:',
'filerevert-defaultcomment' => '$2, $1 વખતે જે પરીસ્થિતિ હતી તે પરીસ્થિતિમાં ફેરવી દીધું.',
'filerevert-submit'         => 'હતુ તેવું પાછું કરો',
'filerevert-success'        => "'''[[Media:$1|$1]]''' ને  [$3, $2ના રોજ હતું તે વર્ઝન $4]માં પાછું લઇ જવામાં આવ્યું.",
'filerevert-badversion'     => 'તમે દર્શાવેલ સમય વખતની મૂળ ફાઇલ સ્થાનિક સ્વરુપે પ્રાપ્ય નથી.',

# File deletion
'filedelete'          => '$1ને ભૂસી નાંખો.',
'filedelete-backlink' => '← $1',
'filedelete-legend'   => 'ફાઇલ ભુસી નાખો.',

# List redirects
'listredirects' => 'અન્યત્ર વાળેલાં પાનાંઓની યાદી',

# Unused templates
'unusedtemplates' => 'વણ વપરાયેલાં ઢાંચા',

# Random page
'randompage' => 'કોઈ પણ એક લેખ',

# Statistics
'statistics' => 'આંકડાકિય માહિતિ',

'brokenredirects-edit'   => '(ફેરફાર કરો)',
'brokenredirects-delete' => '(હટાવો)',

'withoutinterwiki' => 'અન્ય ભાષાઓની કડીઓ વગરનાં પાનાં',

'fewestrevisions' => 'સૌથી ઓછાં ફેરફાર થયેલા પાનાં',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|બાઇટ|બાઇટ્સ}}',
'nlinks'                  => '$1 {{PLURAL:$1|કડી|કડીઓ}}',
'nmembers'                => '$1 {{PLURAL:$1|સદસ્ય|સદસ્યો}}',
'specialpage-empty'       => 'આ પાનું ખાલી છે.',
'lonelypages'             => 'અનાથ પાના',
'uncategorizedpages'      => 'અવર્ગિકૃત પાનાં',
'uncategorizedcategories' => 'અવર્ગિકૃત શ્રેણીઓ',
'uncategorizedimages'     => 'અવર્ગિકૃત દસ્તાવેજો',
'uncategorizedtemplates'  => 'અવર્ગિકૃત ઢાંચાઓ',
'unusedcategories'        => 'વણ વપરાયેલી શ્રેણીઓ',
'unusedimages'            => 'વણ વપરાયેલાં દસ્તાવેજો',
'wantedcategories'        => 'ઇચ્છિત શ્રેણીઓ',
'wantedpages'             => 'ઇચ્છિત પાનાં',
'mostcategories'          => 'સૌથી વધુ શ્રેણીઓ ધરાવતાં પાનાં',
'mostrevisions'           => 'સૌથી વધુ ફેરફાર થયેલા પાનાં',
'prefixindex'             => 'પૂર્વાક્ષર સૂચિ',
'shortpages'              => 'નાનાં પાનાં',
'longpages'               => 'લાંબા પાનાઓ',
'protectedpages'          => 'સંરક્ષિત પાનાઓ',
'listusers'               => 'સભ્યોની યાદી',
'newpages'                => 'નવા પાના',
'ancientpages'            => 'સૌથી જૂનાં પાના',
'move'                    => 'નામ બદલો',
'movethispage'            => 'આ પાનું ખસેડો',
'pager-newer-n'           => '{{PLURAL:$1|નવું 1|નવા $1}}',
'pager-older-n'           => '{{PLURAL:$1|જુનું 1|જુનાં $1}}',

# Book sources
'booksources'               => 'પુસ્તક સ્ત્રોત',
'booksources-search-legend' => 'પુસ્તક સ્ત્રોત શોધો',
'booksources-isbn'          => 'આઇએસબીએન:',
'booksources-go'            => 'જાઓ',

# Special:Log
'specialloguserlabel'  => 'સભ્ય:',
'speciallogtitlelabel' => 'શિર્ષક:',
'log'                  => 'લૉગ',
'all-logs-page'        => 'બધાં માહિતિ પત્રકો',

# Special:AllPages
'allpages'       => 'બધા પાના',
'alphaindexline' => '$1 થી $2',
'nextpage'       => 'આગળનું પાનું ($1)',
'prevpage'       => 'પાછળનું પાનું ($1)',
'allarticles'    => 'બધા લેખ',
'allpagesprev'   => 'પહેલાનું',
'allpagesnext'   => 'પછીનું',
'allpagessubmit' => 'જાઓ',

# Special:Categories
'categories'         => 'શ્રેણીઓ',
'categoriespagetext' => 'નીચેની શ્રેણીઓમાં પાના કે અન્ય સભ્યો છે.
[[Special:UnusedCategories|વણ વપરાયેલી શ્રેણીઓ]] અત્રે દર્શાવવામાં આવી નથી.
[[Special:WantedCategories|ઈચ્છિત શ્રેણીઓ]] પણ જોઈ જુઓ.',

# Special:LinkSearch
'linksearch'    => 'બાહ્ય કડીઓ',
'linksearch-ok' => 'શોધ',

# Special:ListUsers
'listusers-submit' => 'બતાવો',

# Special:Log/newusers
'newuserlogpage'          => 'નવા બનેલા સભ્યોનો લૉગ',
'newuserlog-create-entry' => 'નવું ખાતું',

# Special:ListGroupRights
'listgrouprights-members' => '(સભ્યોની યાદી)',

# E-mail user
'emailuser'    => 'સભ્યને ઇ-મેલ કરો',
'emailfrom'    => 'પ્રેષક:',
'emailto'      => 'પ્રતિ:',
'emailsubject' => 'વિષય:',
'emailmessage' => 'સંદેશો:',
'emailsend'    => 'મોકલો',

# Watchlist
'watchlist'         => 'મારી ધ્યાનસૂચી',
'mywatchlist'       => 'મારી ધ્યાનસૂચિ',
'watchlistfor'      => "('''$1'''ને માટે)",
'addedwatch'        => 'ધ્યાનસૂચિમાં ઉમેરવામાં આવ્યું છે',
'removedwatch'      => 'ધ્યાનસૂચિમાંથી કાઢી નાંખ્યું છે',
'removedwatchtext'  => '"[[:$1]]" શિર્ષક હેઠળનું પાનું [[Special:Watchlist|તમારી ધ્યાનસૂચિમાંથી]] કાઢી નાંખવામાં આવ્યું છે.',
'watch'             => 'ધ્યાન માં રાખો',
'watchthispage'     => 'આ પાનું ધ્યાનમાં રાખો',
'unwatch'           => 'ધ્યાનસૂચિમાંથી હટાવો',
'watchlist-details' => 'ચર્ચા વાળા પાના ન ગણતા {{PLURAL:$1|$1 પાનું|$1 પાનાં}} ધ્યાનસૂચીમાં છે.',
'watchlistcontains' => 'તમારી ધ્યાનસૂચીમાં $1 {{PLURAL:$1|પાનું|પાનાં}} છે.',
'wlshowlast'        => 'છેલ્લા $1 કલાક $2 દિવસ $3 બતાવો',
'watchlist-options' => 'ધ્યાનસૂચિના વિકલ્પો',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'નજર રાખી રહ્યાં છો...',
'unwatching' => 'નજર રાખવાની બંધ કરી છે...',

'enotif_newpagetext' => 'આ નવું પાનું છે.',
'changed'            => 'બદલાયેલું',

# Delete
'deletepage'            => 'પાનું હટાવો',
'confirm'               => 'ખાતરી કરો',
'exblank'               => 'પાનું ખાલી હતું',
'historywarning'        => 'ચેતવણી: જે પાનું તમે હટાવવા જઇ રહ્યાં છો તેનો ઇતિહાસ છે:',
'confirmdeletetext'     => 'આપ આ પાનું તેના ઇતિહાસ (બધાજ પૂર્વ  ફેરફારો) સાથે હટાવી રહ્યાં છો.
કૃપા કરી મંજૂરી આપો કે, આપ આમ કરવા ચાહો છો, આપ આના સરા-નરસા પરિણામોથી વાકેફ છો, અને આપ આ કૃત્ય [[{{MediaWiki:Policy-url}}|નીતિ]]ને અનુરૂપ જ કરી રહ્યાં છો.',
'actioncomplete'        => 'કામ પૂરું થઈ ગયું',
'deletedtext'           => '"<nowiki>$1</nowiki>" દૂર કરવામાં આવ્યું છે.
તાજેતરમાં દૂર કરેલા લેખોની વિગત માટે $2 જુઓ.',
'deletedarticle'        => 'હટાવવામાં આવેલા "[[$1]]"',
'dellogpage'            => 'હટાવેલાઓનું માહિતિ પત્રક (ડિલિશન લૉગ)',
'deletecomment'         => 'હટાવવા માટેનું કારણ:',
'deleteotherreason'     => 'અન્ય/વધારાનું કારણ:',
'deletereasonotherlist' => 'અન્ય કારણ',

# Rollback
'rollbacklink' => 'પાછું વાળો',

# Protect
'protectlogpage'              => 'સુરક્ષા માહિતિ પત્રક',
'protectedarticle'            => 'સુરક્ષિત "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"નું સુરક્ષાસ્તર બદલ્યું',
'prot_1movedto2'              => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'protectcomment'              => 'ટિપ્પણી:',
'protectexpiry'               => 'સમાપ્તિ:',
'protect_expiry_invalid'      => 'સમાપ્તિનો સમય માન્ય નથી.',
'protect_expiry_old'          => 'સમાપ્તિનો સમય ભૂતકાળમાં છે.',
'protect-default'             => 'બધા સભ્યોને પરવાનગી',
'protect-fallback'            => '"$1" પરવાનગી જરૂરી',
'protect-level-autoconfirmed' => 'નવા અને નહી નોંધાયેલા સભ્યો પર પ્રતિબંધ',
'protect-level-sysop'         => 'માત્ર પ્રબંધકો',
'protect-expiring'            => '$1 (UTC) એ સમાપ્ત થાય છે',
'protect-cascade'             => 'આ પાનાંમાં સમાવિષ્ટ પેટા પાનાં પણ સુરક્ષિત કરો (કૅસ્કેડીંગ સુરક્ષા)',
'protect-cantedit'            => 'આપ આ પાનાનાં સુરક્ષા સ્તરમાં ફેરફાર ના કરી શકો, કેમકે આપને અહિં ફેરફાર કરવાની પરવાનગી નથી.',
'protect-expiry-options'      => '૨ કલાક:2 hours,૧ દિવસ:1 day,૩ દિવસ:3 days,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
'restriction-type'            => 'પરવાનગી:',
'restriction-level'           => 'નિયંત્રણ સ્તર:',

# Restrictions (nouns)
'restriction-edit' => 'બદલો',

# Undelete
'undeletebtn'            => 'પાછું વાળો',
'undeletelink'           => 'જુઓ/પાછુ વાળો',
'undeletedarticle'       => '"[[$1]]" પુનઃસ્થાપિત કર્યું',
'undelete-search-submit' => 'શોધો',

# Namespace form on various pages
'namespace'      => 'નામસ્થળ:',
'invert'         => 'પસંદગી ઉલટાવો',
'blanknamespace' => '(મુખ્ય)',

# Contributions
'contributions'       => 'સભ્યનું યોગદાન',
'contributions-title' => 'સભ્ય $1નું યોગદાન',
'mycontris'           => 'મારૂં યોગદાન',
'contribsub2'         => '$1 માટે ($2)',
'uctop'               => '(છેક ઉપર)',
'month'               => ':મહિનાથી (અને પહેલાનાં)',
'year'                => ':વર્ષથી (અને પહેલાનાં)',

'sp-contributions-newbies'     => 'માત્ર નવા ખુલેલાં ખાતાઓનું યોગદાન બતાવો',
'sp-contributions-newbies-sub' => 'નવા ખાતાઓ માટે',
'sp-contributions-blocklog'    => 'પ્રતિબંધ સૂચિ',
'sp-contributions-talk'        => 'ચર્ચા',
'sp-contributions-search'      => 'યોગદાન શોધો',
'sp-contributions-username'    => 'IP સરનામું અથવા સભ્યનામ:',
'sp-contributions-submit'      => 'શોધો',

# What links here
'whatlinkshere'            => 'અહિયાં શું જોડાય છે',
'whatlinkshere-title'      => '"$1" સાથે જોડાયેલાં પાનાં',
'whatlinkshere-page'       => 'પાનું:',
'linkshere'                => "નીચેના પાનાઓ '''[[:$1]]''' સાથે જોડાય છે:",
'nolinkshere'              => "'''[[:$1]]'''ની સાથે કોઇ પાના જોડાતા નથી.",
'isredirect'               => 'પાનું અહીં વાળો',
'istemplate'               => 'સમાવેશ',
'isimage'                  => 'તસવીરની કડી',
'whatlinkshere-prev'       => '{{PLURAL:$1|પહેલાનું|પહેલાનાં $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|પછીનું|પછીનાં $1}}',
'whatlinkshere-links'      => '←  કડીઓ',
'whatlinkshere-hideredirs' => 'અન્યત્ર વાળેલાં પાનાં $1',
'whatlinkshere-hidelinks'  => 'કડીઓ $1',
'whatlinkshere-filters'    => 'ચાળણી',

# Block/unblock
'blockip'                  => 'સભ્ય પર પ્રતિબંધ મુકો',
'ipbreason'                => 'કારણ',
'ipbreasonotherlist'       => 'બીજું કારણ',
'ipbother'                 => 'અન્ય સમય',
'ipboptions'               => '૨ કલાક:2 hours,૧ દિવસ:1 day,૩ દિવસ:3 days,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
'ipbotheroption'           => 'બીજું',
'ipblocklist'              => 'પ્રતિબંધિત IP સરનામા અને સભ્યોની યાદી',
'ipblocklist-submit'       => 'શોધો',
'anononlyblock'            => 'માત્ર અનામી',
'blocklink'                => 'પ્રતિબંધ',
'unblocklink'              => 'પ્રતિબંધ હટાવો',
'change-blocklink'         => 'પ્રતિબંધમાં ફેરફાર કરો',
'contribslink'             => 'યોગદાન',
'blocklogpage'             => 'પ્રતિબંધ સૂચિ',
'blocklogentry'            => '[[$1]] પર પ્રતિબંધ $2 $3 સુધી મુકવામાં આવ્યો છે.',
'unblocklogentry'          => '$1 પરનો પ્રતિબંધ ઉઠાવ્યો',
'block-log-flags-nocreate' => 'ખાતું ખોલવા પર પ્રતિબંધ છે',

# Move page
'movearticle'             => 'આ પાનાનું નામ બદલો:',
'newtitle'                => 'આ નવું નામ આપો:',
'move-watch'              => 'આ પાનું ધ્યાનમાં રાખો',
'movepagebtn'             => 'પાનું ખસેડો',
'pagemovedsub'            => 'પાનું સફળતા પૂર્વક ખસેડવામાં આવ્યું છે',
'movepage-moved'          => '<big>\'\'\'"$1" નું નામ બદલીને "$2" કરવામાં આવ્યું છે\'\'\'</big>',
'articleexists'           => 'આ નામનું પાનું અસ્તિત્વમાં છે, અથવાતો તમે પસંદ કરેલું નામ અસ્વિકાર્ય છો.
કૃપા કરી અન્ય નામ પસંદ કરો.',
'movedto'                 => 'બદલ્યા પછીનું નામ',
'movetalk'                => 'સંલગ્ન ચર્ચાનું પાનું પણ ખસેડો',
'1movedto2'               => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'1movedto2_redir'         => 'નામ બદલતા [[$1]] ને [[$2]] બનાવ્યું',
'movelogpage'             => 'નામ ફેર માહિતિ પત્રક',
'movereason'              => 'કારણ',
'revertmove'              => 'પૂર્વવત',
'delete_and_move'         => 'હટાવો અને નામ બદલો',
'delete_and_move_confirm' => 'હા, આ પાનું હટાવો',

# Export
'export'        => 'પાનાઓની નિકાસ કરો/પાના અન્યત્ર મોકલો',
'export-addcat' => 'ઉમેરો',

# Namespace 8 related
'allmessages'        => 'તંત્ર સંદેશાઓ',
'allmessagesname'    => 'નામ',
'allmessagescurrent' => 'વર્તમાન દસ્તાવેજ',

# Thumbnails
'thumbnail-more'  => 'વિસ્તૃત કરો',
'thumbnail_error' => 'નાની છબી (થંબનેઇલ-thumbnail) બનાવવામાં ત્રુટિ: $1',

# Import log
'importlogpage' => 'આયાત માહિતિ પત્રક',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "તમારૂં પાનું (તમારૂં 'મારા વિષે')",
'tooltip-pt-mytalk'               => 'તમારૂં ચર્ચાનું પાનું',
'tooltip-pt-preferences'          => 'મારી પસંદ',
'tooltip-pt-watchlist'            => 'તમે દેખરેખ રાખી રહ્યાં હોવ તેવા પાનાઓની યાદી',
'tooltip-pt-mycontris'            => 'તમારા યોગદાનની યાદી',
'tooltip-pt-login'                => 'આપને લોગ ઇન કરવા ભલામણ કરવામાં આવે છે, જોકે તે આવશ્યક નથી',
'tooltip-pt-logout'               => 'બહાર નીકળો/લૉગ આઉટ કરો',
'tooltip-ca-talk'                 => 'અનુક્રમણિકાનાં પાના વિષે ચર્ચા',
'tooltip-ca-edit'                 => "આપ આ પાનામાં ફેરફાર કરી શકો છો, કાર્ય સુરક્ષિત કરતાં પહેલાં 'ફેરફાર બતાવો' બટન ઉપર ક્લિક કરીને જોઇ લેશો",
'tooltip-ca-addsection'           => 'ચર્ચાનો નવો મુદ્દો ઉમેરો.',
'tooltip-ca-viewsource'           => 'આ પાનુ સંરક્ષિત છે, તમે તેનો સ્ત્રોત જોઇ શકો છો',
'tooltip-ca-history'              => 'આ પાનાનાં અગાઉનાં ફેરફારો',
'tooltip-ca-protect'              => 'આ પાનું સુરક્ષિત કરો',
'tooltip-ca-delete'               => 'આ પાનું હટાવો',
'tooltip-ca-move'                 => 'આ પાનું ખસેડો',
'tooltip-ca-watch'                => 'આ પાનું તમારી ધ્યાનસૂચીમા ઉમેરો',
'tooltip-ca-unwatch'              => 'આ પાનું તમારી ધ્યાનસૂચીમાથી કાઢી નાખો',
'tooltip-search'                  => '{{SITENAME}} શોધો',
'tooltip-search-go'               => 'આ ચોક્કસ જોડણી વાળુ પાનુ જો અસ્તિત્વમાં હોય તો તેના પર જાવ',
'tooltip-search-fulltext'         => 'આ લખાણ વાળા પાનાઓ શોધો',
'tooltip-p-logo'                  => 'મુખપૃષ્ઠ',
'tooltip-n-mainpage'              => 'મુખપૃષ્ઠ પર જાઓ',
'tooltip-n-portal'                => 'પરિયોજના વિષે, આપ શું કરી શકો અને વસ્તુઓ ક્યાં શોધશો',
'tooltip-n-currentevents'         => 'પ્રસ્તુત ઘટનાની પૃષ્ઠભૂમિની માહિતિ શોધો',
'tooltip-n-recentchanges'         => 'વિકિમાં હાલમા થયેલા ફેરફારો ની સૂચિ.',
'tooltip-n-randompage'            => 'કોઇ પણ એક લેખ બતાવો',
'tooltip-n-help'                  => 'શોધવા માટેની જગ્યા.',
'tooltip-t-whatlinkshere'         => 'અહીં જોડાતા બધાં વિકિ પાનાઓની યાદી',
'tooltip-t-recentchangeslinked'   => 'આ પાના પરની કડીઓ વાળા લેખોમાં તાજેતરમાં થયેલા ફેરફારો',
'tooltip-t-contributions'         => 'આ સભ્યનાં યોગદાનોની યાદી જુઓ',
'tooltip-t-emailuser'             => 'આ સભ્યને ઇ-મેલ મોકલો',
'tooltip-t-upload'                => 'ફાઇલ ચડાવો',
'tooltip-t-specialpages'          => 'ખાસ પાનાંઓની સૂચિ',
'tooltip-t-print'                 => 'આ પાનાની છાપવા માટેની આવૃત્તિ',
'tooltip-t-permalink'             => 'પાનાનાં આ પુનરાવર્તનની સ્થાયી કડી',
'tooltip-ca-nstab-main'           => 'સૂચિ વાળું પાનુ જુઓ',
'tooltip-ca-nstab-user'           => 'સભ્યનું પાનું જુઓ',
'tooltip-ca-nstab-special'        => 'આ ખાસ પાનું છે, તમે તેમાં ફેરફાર ના કરી શકો',
'tooltip-ca-nstab-project'        => 'પરિયોજનાનું પાનું',
'tooltip-ca-nstab-image'          => 'ફાઇલ વિષેનું પાનું જુઓ',
'tooltip-ca-nstab-template'       => 'ઢાંચો જુઓ',
'tooltip-ca-nstab-help'           => 'મદદનું પાનું જુઓ',
'tooltip-ca-nstab-category'       => 'શ્રેણીઓનું પાનું જુઓ',
'tooltip-minoredit'               => 'આને નાનો ફેરફાર ગણો',
'tooltip-save'                    => 'તમે કરેલાં ફેરફારો સુરક્ષિત કરો',
'tooltip-preview'                 => 'તમે કરેલાં ફેરફારો જોવા મળશે, કૃપા કરી કાર્ય સુરક્ષિત કરતાં પહેલા આ જોઇ લો',
'tooltip-diff'                    => 'તમે માહિતિમાં કયા ફેરફારો કર્યા છે તે જોવા મળશે',
'tooltip-compareselectedversions' => 'અ પાનાનાં પસંદ કરેલા બે વૃત્તાંત વચ્ચેનાં ભેદ જુઓ.',
'tooltip-watch'                   => 'આ પાનાને તમારી ધ્યાનસૂચિમાં ઉમેરો',
'tooltip-rollback'                => '"પાછું વાળો" એક જ ક્લિકમાં છેલ્લા સભ્ય એ આ પાનાંમાં કરેલા બધા ફેરફારો પાછા વાળશે',
'tooltip-undo'                    => '"રદ કરો" આ ફેરફારને પાછો વાળશે અને ફેરફાર પછીનું પૂર્વાવલોકન ફોર્મ નવા પના તરિકે ખુલશે. 
તે તમને \'સારાંશ\'માં કારણ જણાવવા દેશે.',

# Info page
'infosubtitle' => 'પાના વિષે માહિતી',
'numedits'     => 'ફેરફારોની સંખ્યા (લેખ): $1',
'numtalkedits' => 'ફેરફારોની સંખ્યા (ચર્ચાનું પાનું): $1',

# Browsing diffs
'previousdiff' => '← પહેલાનો ફેરફાર',
'nextdiff'     => 'પછીનો ફેરફાર →',

# Media information
'file-info-size'       => '($1 × $2 પીક્સલ, ફાઇલનું કદ: $3, MIME પ્રકાર: $4)',
'file-nohires'         => '<small>આથી વધુ આવર્તન ઉપલબ્ધ નથી.</small>',
'svg-long-desc'        => '(SVG ફાઇલ, માત્ર $1 × $2 પીક્સલ, ફાઇલનું કદ: $3)',
'show-big-image'       => 'મહત્તમ આવર્તન',
'show-big-image-thumb' => '<small>આ પુર્વાવલોકનનું પરિમાણ: $1 × $2 પીક્સલ</small>',

# Special:NewFiles
'newimages' => 'નવી ફાઇલોની ઝાંખી',
'noimages'  => 'જોવા માટે કશું નથી.',
'ilsubmit'  => 'શોધો',
'bydate'    => 'તારીખ પ્રમાણે',

# Bad image list
'bad_image_list' => 'ફોર્મેટ નીચે મુજબ છે:

ફક્ત યાદીનાં નામો જ (* થી શરૂ થતી પંક્તિઓ) ધ્યાનમાં લેવામાં આવ્યાં છે.
પંક્તિમાં રહેલી પહેલી કડી ખરાબ ફાઇલને જોડતી હોવી જ જોઇએ.
તે જ પંક્તિમાં બાદમાં આવતી કડીઓ અપવાદ રૂપ ગણવામાં આવશે, જેમકે એવા લેખો કે જેમાં ફાઇલ વણી લેવામાં આવી (inline) હોય.',

# Metadata
'metadata'          => 'મૅટાડેટા',
'metadata-help'     => 'આ માધ્યમ સાથે વધુ માહિતિ સંકળાયેલી છે, જે સંભવતઃ માધ્યમ (ફાઇલ) બનાવવા માટે ઉપયોગમાં લેવાયેલા ડિજીટલ કેમેરા કે સ્કેનર દ્વારા ઉમેરવામાં આવી હશે.
<br />જો માધ્યમને તેના મુળ રૂપમાંથી ફેરફાર કરવામાં આવશે તો શક્ય છે કે અમુક માહિતિ પુરેપુરી હાલમાં છે તેવી રીતે ના જળવાઇ રહે.',
'metadata-expand'   => 'વિસ્તૃત કરેલી વિગતો બતાવો',
'metadata-collapse' => 'વિસ્તૃત કરેલી વિગતો છુપાવો',
'metadata-fields'   => 'આ સંદેશામાં સુચવેલી EXIF મૅટડેટા માહિતિ ચિત્રના પાનાનિ દ્રશ્ય આવૃત્તિમાં ઉમેરવામાં આવશે (જ્યારે મૅટડેટાનો કોઠો વિલિન થઇ જતો હશે ત્યારે).
>અન્ય આપોઆપ જ છુપાઇ જશે.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'  => 'પહોળાઈ',
'exif-imagelength' => 'ઊંચાઈ',
'exif-artist'      => 'કલાકાર',

'exif-unknowndate' => 'અજ્ઞાત તારીખ',

'exif-orientation-1' => 'સામાન્ય',

'exif-componentsconfiguration-0' => 'નથી',

'exif-meteringmode-0'   => 'અજાણ્યો',
'exif-meteringmode-255' => 'બીજું કઈ',

'exif-lightsource-0' => 'અજાણ્યો',

'exif-gaincontrol-0' => 'જરાપણ નહી',

'exif-saturation-0' => 'સામાન્ય',

'exif-sharpness-0' => 'સામાન્ય',

'exif-subjectdistancerange-0' => 'અજાણ્યો',

# External editor support
'edit-externally'      => 'બાહ્ય સોફ્ટવેર વાપરીને આ ફાઇલમાં ફેરફાર કરો',
'edit-externally-help' => '(વધુ માહિતિ માટે [http://www.mediawiki.org/wiki/Manual:External_editors સેટ-અપ સુચનાઓ] જુઓ)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'બધા',
'imagelistall'     => 'બધા',
'watchlistall2'    => 'બધા',
'namespacesall'    => 'બધા',
'monthsall'        => 'બધા',

# action=purge
'confirm_purge_button' => 'મંજૂર',

# Multipage image navigation
'imgmultipageprev' => '← પાછલું પાનું',
'imgmultipagenext' => 'આગલું પાનું →',
'imgmultigo'       => 'જાઓ!',

# Table pager
'table_pager_next'         => 'આગળનું પાનું',
'table_pager_prev'         => 'પાછળનું પાનું',
'table_pager_first'        => 'પહેલું પાનું',
'table_pager_last'         => 'છેલ્લૂં પાનું',
'table_pager_limit_submit' => 'જાઓ',

# Auto-summaries
'autosumm-new' => 'નવું પાનું : $1',

# Watchlist editing tools
'watchlisttools-view' => 'બંધબેસતાં ફેરફારો નિહાળો',
'watchlisttools-edit' => 'ધ્યાનસૂચી જુઓ અને બદલો',
'watchlisttools-raw'  => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',

# Special:Version
'version' => 'આવૃત્તિ',

# Special:SpecialPages
'specialpages' => 'ખાસ પાનાં',

);
