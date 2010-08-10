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
 * @author Ashok modhvadia
 * @author Dineshjk
 * @author Dsvyas
 * @author RaviC
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

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'દ્વિ પુનઃમાર્ગદર્શન' ),
	'BrokenRedirects'           => array( 'ખંડિત પુનઃમાર્ગદર્શન' ),
	'Disambiguations'           => array( 'અસંદિગ્ધતા' ),
	'Userlogin'                 => array( 'સભ્યપ્રવેશ' ),
	'Userlogout'                => array( 'સભ્યનિવેશ' ),
	'CreateAccount'             => array( 'ખાતું ખોલો' ),
	'Preferences'               => array( 'પસંદ' ),
	'Watchlist'                 => array( 'ધ્યાનસૂચિ' ),
	'Recentchanges'             => array( 'તાજાફેરફારો' ),
	'Upload'                    => array( 'ચડાવો' ),
	'Listfiles'                 => array( 'યાદીફાઇલ', 'ફાઇલયાદી', 'ચિત્રયાદી' ),
	'Newimages'                 => array( 'નવીફાઇલો', 'નવાંચિત્રો' ),
	'Listusers'                 => array( 'યાદીસભ્યો', 'સભ્યયાદી' ),
	'Listgrouprights'           => array( 'યાદીસમુહઅધિકારો', 'સભ્યસમુહઅધિકારો' ),
	'Statistics'                => array( 'આંકડાકીયમાહિતી' ),
	'Randompage'                => array( 'યાદચ્છ', 'કોઈ પણ એક' ),
	'Lonelypages'               => array( 'એકાકીપાનાં', 'અનાથપાનાં' ),
	'Uncategorizedpages'        => array( 'અવર્ગિકૃત પાનાં' ),
	'Uncategorizedcategories'   => array( 'અવર્ગિકૃત શ્રેણીઓ' ),
	'Uncategorizedimages'       => array( 'અવર્ગિકૃત ફાઇલો', 'અવર્ગિકૃત ચિત્રો' ),
	'Uncategorizedtemplates'    => array( 'અવર્ગિકૃત ઢાંચા' ),
	'Unusedcategories'          => array( 'વણવપરાયેલી શ્રેણીઓ' ),
	'Unusedimages'              => array( 'વણવપરાયેલ ફાઇલો', 'વણવપરાયેલ ચિત્રો' ),
	'Wantedpages'               => array( 'જોઇતા પાનાં', 'ત્રુટક કડી' ),
	'Wantedcategories'          => array( 'જોઇતી શ્રેણીઓ' ),
	'Wantedfiles'               => array( 'જોઇતી ફાઇલો' ),
	'Wantedtemplates'           => array( 'જોઇતા ઢાંચા' ),
	'Mostlinked'                => array( 'સૌથીવધુ જોડાયેલાં પાનાં', 'સૌથીવધુ જોડાયેલ' ),
	'Mostlinkedcategories'      => array( 'સૌથીવધુજોડાયેલી શ્રેણી', 'સૌથીવધુવપરાયેલી શ્રેણીઓ' ),
	'Mostlinkedtemplates'       => array( 'સૌથીવધુ જોડાયેલાં ઢાંચા', 'સૌથી વધુવપરાયેલાં ઢાંચા' ),
	'Mostimages'                => array( 'સૌથી વધુજોડાયેલી ફાઇલો', 'મહત્તમ ફાઇલો', 'મહત્તમ ચિત્રો' ),
	'Mostcategories'            => array( 'મોટાભાગની શ્રેણીઓ' ),
	'Mostrevisions'             => array( 'મહત્તમ પુનરાવર્તન' ),
	'Fewestrevisions'           => array( 'લઘુત્તમ પુનરાવર્તન' ),
	'Shortpages'                => array( 'ટુંકાપાનાં' ),
	'Longpages'                 => array( 'લાંબાપાના' ),
	'Newpages'                  => array( 'નવાપાનાં' ),
	'Ancientpages'              => array( 'પ્રાચીનપાનાં' ),
	'Deadendpages'              => array( 'મૃતાંતપાનાં' ),
	'Protectedpages'            => array( 'સંરક્ષિતપાનાં' ),
	'Protectedtitles'           => array( 'સંરક્ષિત શિર્ષકો' ),
	'Allpages'                  => array( 'બધાંપાનાં' ),
	'Prefixindex'               => array( 'ઉપસર્ગ' ),
	'Specialpages'              => array( 'ખાસપાનાં' ),
	'Contributions'             => array( 'પ્રદાન' ),
	'Emailuser'                 => array( 'સભ્યનેઇમેલ' ),
	'Confirmemail'              => array( 'ઇમેઇલખાતરીકરો' ),
	'Whatlinkshere'             => array( 'અહિં શું જોડાય છે?' ),
	'Recentchangeslinked'       => array( 'તાજેતરનાં ફેરફારો', 'સંલગ્ન ફેરફારો' ),
	'Movepage'                  => array( 'પાનુંખસેડો' ),
	'Booksources'               => array( 'પુસ્તકસ્રોત' ),
	'Categories'                => array( 'શ્રેણીઓ' ),
	'Export'                    => array( 'નિકાસ' ),
	'Version'                   => array( 'સંસ્કરણ' ),
	'Allmessages'               => array( 'બધાંસંદેશા' ),
	'Log'                       => array( 'લૉગ', 'લૉગ્સ' ),
	'Blockip'                   => array( 'પ્રતિબંધ', 'IP પર પ્રતિબંધ', 'સભ્યપર પ્રતિબંધ' ),
	'Undelete'                  => array( 'પુનઃપ્રાપ્ત' ),
	'Import'                    => array( 'આયાત' ),
	'Userrights'                => array( 'સભ્યાધિકાર' ),
	'FileDuplicateSearch'       => array( 'ફાઇલપ્રતિકૃતિ શોધ' ),
	'Unwatchedpages'            => array( 'વણજોયેલા પાનાં' ),
	'Listredirects'             => array( 'પુનઃમાર્ગદર્શનયાદી' ),
	'Revisiondelete'            => array( 'રદકરેલું સુધારો' ),
	'Unusedtemplates'           => array( 'વણવપરાયેલાં ઢાંચા' ),
	'Randomredirect'            => array( 'યાદચ્છ પુનઃમાર્ગદર્શન' ),
	'Mypage'                    => array( 'મારૂપાનું' ),
	'Mytalk'                    => array( 'મારીચર્ચા' ),
	'Mycontributions'           => array( 'મારૂપ્રદાન' ),
	'Listadmins'                => array( 'યાદીપ્રબંધક' ),
	'Listbots'                  => array( 'યાદીબૉટ' ),
	'Popularpages'              => array( 'લોકપ્રિયપાનાં' ),
	'Search'                    => array( 'શોધ' ),
	'Resetpass'                 => array( 'ગુપ્તસંજ્ઞા બદલો', 'ગુપ્તસંજ્ઞા પુન: સ્થાપન' ),
	'Withoutinterwiki'          => array( 'આંતરવિકિવિહીન' ),
	'MergeHistory'              => array( 'વિલિનિકરણ ઈતિહાસ' ),
	'Filepath'                  => array( 'ફાઇલપથ' ),
	'Invalidateemail'           => array( 'અમાન્ય ઇ-મેઇલ' ),
	'Blankpage'                 => array( 'કોરૂં પાનું' ),
	'LinkSearch'                => array( 'કડી શોધ' ),
	'DeletedContributions'      => array( 'હટાવેલાં યોગદાન' ),
	'Tags'                      => array( 'ટેગ' ),
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
'tog-extendwatchlist'         => 'ધ્યાનસૂચિને વિસ્તૃત કરો જેથી,ફક્ત તાજેતરનાજ નહીં, બધા આનુષાંગિક ફેરફારો જોઇ શકાય',
'tog-usenewrc'                => 'તાજેતરનાં વર્ધિત ફેરફારો (જાવાસ્ક્રીપ્ટ જરૂરી)',
'tog-numberheadings'          => 'મથાળાંઓને આપો-આપ ક્રમ (ઑટો નંબર) આપો',
'tog-showtoolbar'             => 'ફેરફારો માટેનો ટૂલબાર બતાવો (જાવા સ્ક્રિપ્ટ)',
'tog-editondblclick'          => 'ડબલ ક્લિક દ્વારા ફેરફાર કરો (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-editsection'             => 'વિભાગોમાં [ફેરફાર કરો] કડી દ્વારા વિભાગીય ફેરફાર લાગુ કરો.',
'tog-editsectiononrightclick' => 'વિભાગના મથાળાં ને રાઇટ ક્લિક દ્વારા ફેરફાર કરવાની રીત અપનાવો. (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-showtoc'                 => 'અનુક્રમણિકા દર્શાવો (૩થી વધુ પેટા-મથાળા વાળા લેખો માટે)',
'tog-rememberpassword'        => 'આ કમ્પ્યૂટર પર મારી લોગ-ઇન વિગતો યાદ રાખો',
'tog-watchcreations'          => 'મેં લખેલા નવા લેખો મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdefault'            => 'હું ફેરફાર કરૂં તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchmoves'              => 'હું જેનું નામ બદલું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdeletion'           => 'હું હટાવું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-previewontop'            => 'એડીટ બોક્સ પહેલાં પ્રિવ્યુ બતાવો.',
'tog-previewonfirst'          => 'પ્રથમ ફેરફાર વખતે પ્રિવ્યુ બતાવો.',
'tog-nocache'                 => 'કેશ ન કરો.',
'tog-enotifwatchlistpages'    => 'મારી ધ્યાનસૂચિમાંનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifusertalkpages'     => 'મારી ચર્ચાનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifminoredits'        => 'પાનામાં નાનાં ફેરફાર થાય ત્યારે પણ મને ઇ-મેલ મોકલો',
'tog-enotifrevealaddr'        => 'નોટીફીકેશનના ઇમેલમાં મારૂ ઇમેલ એડ્રેસ બતાવો',
'tog-shownumberswatching'     => 'ધ્યાનમાં રાખતા સભ્યોની સંખ્યા બતાવો',
'tog-oldsig'                  => 'વિદ્યમાન હસ્તાક્ષરનું પૂર્વદર્શન:',
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

# Font style option in Special:Preferences
'editfont-style'   => 'ક્ષેત્ર લિપિ શૈલીનું સંપાદન:',
'editfont-default' => 'બ્રાઉઝરના સેટીંગ્સ પ્રમાણે',

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
'mainpagetext'      => "'''મિડીયાવિકિ સફળતાપૂર્વક ઇન્સટોલ થયું છે.'''",
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

# Vector skin
'vector-action-addsection'   => 'વિષય ઉમેરો',
'vector-action-delete'       => 'રદ કરો',
'vector-action-move'         => 'ખસેડો',
'vector-action-protect'      => 'સુરક્ષિત કરો',
'vector-action-undelete'     => 'રદ કરેલું પાછું વાળો',
'vector-action-unprotect'    => 'અસુરક્ષિત',
'vector-namespace-category'  => 'શ્રેણી',
'vector-namespace-help'      => 'મદદ માટેનું પાનું',
'vector-namespace-image'     => 'ફાઇલ',
'vector-namespace-main'      => 'પાનું',
'vector-namespace-media'     => 'માધ્યમ પાનું',
'vector-namespace-mediawiki' => 'સંદેશ',
'vector-namespace-project'   => 'યોજના પાનું',
'vector-namespace-special'   => 'ખાસ પાનું',
'vector-namespace-talk'      => 'ચર્ચા',
'vector-namespace-template'  => 'ઢાંચો',
'vector-namespace-user'      => 'સભ્યનું પાનું',
'vector-view-create'         => 'બનાવો',
'vector-view-edit'           => 'સંપાદન કરો',
'vector-view-history'        => 'ઈતિહાસ જુઓ',
'vector-view-view'           => 'વાંચો',
'vector-view-viewsource'     => 'સ્ત્રોત જુઓ',
'actions'                    => 'ક્રિયાઓ',
'namespaces'                 => 'નામાવકાશો',
'variants'                   => 'ભિન્ન રૂપો',

'errorpagetitle'    => 'ત્રુટિ',
'returnto'          => '$1 પર પાછા જાઓ.',
'tagline'           => '{{SITENAME}}થી',
'help'              => 'મદદ',
'search'            => 'શોધો',
'searchbutton'      => 'શોધો',
'go'                => 'જાઓ',
'searcharticle'     => 'જાવ',
'history'           => 'પાનાનો ઇતિહાસ',
'history_short'     => 'ઇતિહાસ',
'updatedmarker'     => 'મારી ગઇ મુલાકાત પછીના બદલાવ',
'info_short'        => 'માહિતી',
'printableversion'  => 'છાપવા માટેની આવૃત્તિ',
'permalink'         => 'સ્થાયી કડી',
'print'             => 'છાપો',
'edit'              => 'ફેરફાર કરો',
'create'            => 'બનાવો',
'editthispage'      => 'આ પાનામાં ફેરફાર કરો',
'create-this-page'  => 'આ પાનું બનાવો.',
'delete'            => 'હટાવો',
'deletethispage'    => 'આ પાનું હટાવો',
'undelete_short'    => 'હટાવેલ {{PLURAL:$1|એક ફેરફાર|$1 ફેરફારો}} પરત લાવો.',
'protect'           => 'સુરક્ષિત કરો',
'protect_change'    => 'ફેરફાર કરો',
'protectthispage'   => 'આ પાનું સુરક્ષિત કરો.',
'unprotect'         => 'સુરક્ષા હટાવો',
'unprotectthispage' => 'Unprotect this page
આ પાનાંની સુરક્ષા હટાવો.',
'newpage'           => 'નવું પાનું',
'talkpage'          => 'આ પાના વિષે ચર્ચા કરો',
'talkpagelinktext'  => 'ચર્ચા',
'specialpage'       => 'ખાસ પાનુ',
'personaltools'     => 'વ્યક્તિગત સાધનો',
'postcomment'       => 'નવો વિભાગ',
'articlepage'       => 'લેખનું પાનું જુઓ',
'talk'              => 'ચર્ચા',
'views'             => 'દેખાવ',
'toolbox'           => 'સાધન પેટી',
'userpage'          => 'સભ્યનું પાનું જુઓ',
'projectpage'       => 'પ્રકલ્પનું પાનું જુઓ',
'imagepage'         => 'ફાઇલનું પાનું જુઓ',
'mediawikipage'     => 'સંદેશનું પાનું જુઓ',
'templatepage'      => 'ઢાંચાનું પાનુ જુઓ',
'viewhelppage'      => 'મદદનું પાનું જુઓ',
'categorypage'      => 'શ્રેણીનું પાનુ જુઓ',
'viewtalkpage'      => 'ચર્ચા જુઓ',
'otherlanguages'    => 'બીજી ભાષાઓમાં',
'redirectedfrom'    => '($1 થી અહીં વાળેલું)',
'redirectpagesub'   => 'પાનું અન્યત્ર વાળો',
'lastmodifiedat'    => 'આ પાનાંમાં છેલ્લો ફેરફાર $1ના રોજ $2 વાગ્યે થયો.',
'viewcount'         => 'આ પાનાંને {{PLURAL:$1|એક|$1}} વખત જોવામાં આવ્યું છે.',
'protectedpage'     => 'સંરક્ષિત પાનું',
'jumpto'            => 'સીધા આના પર જાવ:',
'jumptonavigation'  => 'ભ્રમણ',
'jumptosearch'      => 'શોધો',
'view-pool-error'   => 'માફ કરશો, આ સમયે સર્વર અતિબોજા હેઠળ છે.

ઘણા બધા વપરાશકર્તાઓ આ પાનું જોવાની કોશિશ કરી રહ્યા છે.

આ પાનું ફરી જોતા પહેલાં કૃપયા થોડો સમય પ્રતિક્ષા કરો.

$1',

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
'policy-url'           => 'Project:નીતિ',
'portal'               => 'સમાજ મુખપૃષ્ઠ',
'portal-url'           => 'Project:સમાજ મુખપૃષ્ઠ',
'privacy'              => 'ગોપનિયતા નીતિ',
'privacypage'          => 'Project:ગોપનિયતા નીતિ',

'badaccess'        => 'પરવાનગીની ખામી',
'badaccess-group0' => 'તમને વિનંતી કરાયેલ ક્રિયાને ક્રિયાન્વિત કરવાની છૂટ નથી.',
'badaccess-groups' => 'તમે જે ક્રિયા કરવા માંગો છો તે {{PLURAL:$2|સમુહ|આ સમુહો પૈકીના કોઇ}} માટે મર્યાદિત છે: $1.',

'versionrequired'     => 'મીડીયાવિકિનું $1 સંસ્કરણ જરૂરી',
'versionrequiredtext' => 'આ પાનાના વપરાશ માટે મીડિયાવિકિનું $1 સંસ્કરણ જરૂરી.

જુઓ [[Special:Version|સંસ્કરણ પાનું]].',

'ok'                      => 'મંજૂર',
'retrievedfrom'           => '"$1"થી લીધેલું',
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
'page-atom-feed'          => '"$1" એટોમ ફીડ',
'red-link-title'          => '$1 (પાનું અસ્તિત્વમાં નથી)',

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
'nosuchaction'      => 'આવી કોઇ ક્રિયા નથી.',
'nosuchactiontext'  => 'આ URL દ્વારા દર્શાવેલી ક્રિયા અયોગ્ય છે.
તમે કદાચ ખોટો URL છાપ્યો હશે અથવા ખોટી કડીથી અહીં આવ્યા હશો.
તમે સોફ્ટવેરની આ ખામી {{SITENAME}} પર દર્શાવી શકો છો.',
'nosuchspecialpage' => 'એવું ખાસ પાનું નથી',
'nospecialpagetext' => '<strong>તમે અયોગ્ય ખાસ પાનું માંગ્યું છે. </strong>

યોગ્ય ખાસ પાનાંની યાદી માટે ક્લિક કરો : [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'ત્રુટિ',
'databaseerror'        => 'ડેટાબેઝ ત્રુટિ',
'laggedslavemode'      => 'ચેતવણી: પાનું તાજેતરના ફેરફાર ધરાવતું નથી.',
'readonly'             => 'ડેટાબેઝ સ્થગિત',
'enterlockreason'      => 'સ્થગિતતા ક્યારે દુર કરાશે તેના અંદાજ શાથે,સ્થગિત કરવાનું કારણ આપો',
'readonlytext'         => 'નવી નોંધો અને ફેરફારો માટે ડેટાબેઝ હાલમાં સ્થગિત કરાયેલ છે,કદાચ નિયમિત ડેટાબેઝ સારસંભાળ માટે,તે પછી આ ફરી સામાન્ય થશે.

સ્થગિત કરનાર પ્રબંધકનો ખુલાસો: $1',
'missing-article'      => 'ડેટાબેઝને પાનાનાં જે શબ્દો ("$1" $2) મળવા જોઈતા હતા તે મળ્યા નથી.

આવું સામાન્ય રીતે ત્યારે બને જ્યારે તમે તફાવત કે ઈતિહાસની એવી જુની કડીને અનુસરીને અહીં આવ્યા હોવ કે જે પાનું હટાવી દીધું હોય.

જો તમને ખાતરી છે કે આવું નથી, તો તમારા ભાગે સોફ્ટવેરમાં રહેલી ત્રુટી આવી છે.
કૃપા કરી આ વાત, જે તે પાનાની પૂર્ણ યુ.આર.એલ. (URL) કડી સાથે, તમારા [[Special:ListUsers/sysop|પ્રબંધક]]ના ધ્યાન પર લાવો.',
'missingarticle-rev'   => '(પુનરાવર્તન#: $1)',
'missingarticle-diff'  => '(ભેદ: $1, $2)',
'internalerror'        => 'આંતરિક ત્રુટિ',
'internalerror_info'   => 'આંતરિક ત્રુટિ: $1',
'fileappenderror'      => '"$1" ને "$2" શાથે જોડી શકાશે નહીં.',
'filecopyerror'        => '"$1" થી "$2"માં નકલ નાકામયાબ.',
'filerenameerror'      => '"$1" નું નામ બદલીને "$2" કરવામાં નાકામયાબ.',
'filedeleteerror'      => '"$1" ફાઇલ હટાવી ન શકાઇ.',
'directorycreateerror' => 'ડીરેક્ટરી "$1" ન બનાવી શકાઇ.',
'filenotfound'         => 'ફાઇલ "$1" ન મળી.',
'fileexistserror'      => 'ફાઇલ "$1"માં ન લખી શકાયું : ફાઇલ અસ્તિત્વ ધરાવે છે.',
'unexpected'           => 'અણધારી કિંમત: "$1"="$2".',
'formerror'            => 'ત્રુટિ: પત્રક રજૂ થયું નહીં',
'badarticleerror'      => 'આ ક્રિયા આ પાનાં ઉપર કરવી શક્ય નથી.',
'cannotdelete'         => 'ફાઇલ કે પાનું "$1" હટાવી શકાયું નથી.
શક્ય છે કે અન્ય કોઈએ પહેલેથી હટાવી દીધું હોય.',
'badtitle'             => 'ખરાબ નામ',
'badtitletext'         => 'આપનું ઈચ્છિત શીર્ષક અમાન્ય છે, ખાલી છે, અથવાતો અયોગ્ય રીતે આંતર-ભાષિય કે આંતર-વિકિ સાથે જોડાયેલું શીર્ષક છે.
શક્ય છે કે તેમાં એક કે વધુ એવા અક્ષર કે ચિહ્નો છે કે જે પાનાનાં શીર્ષક માટે અવૈધ છે.',
'perfcached'           => 'નીચે દર્શાવેલી માહિતી જુના સંગ્રહમાંથી લીધેલી છે અને શક્ય છે કે તે હાલની પરિસ્થિતિમાં સચોટ ના હોય.',
'perfcachedts'         => 'નીચેની વિગતો જુના સંગ્રહમાથી છે અને તે છેલ્લે $1 સુધી અદ્યતન હતી.',
'querypage-no-updates' => 'આ પાનાની નવી આવૃત્તિઓ હાલમાં અક્રિય છે.
અહીંની વિગતો હાલમાં રિફ્રેશ કરવામાં નહી આવે.',
'viewsource'           => 'સ્ત્રોત જુઓ',
'viewsourcefor'        => '$1ને માટે',
'actionthrottled'      => 'નિયંત્રિત ક્રિયા',
'actionthrottledtext'  => 'સ્પામ નિયંત્રણ તકેદારી રૂપે આ ક્રિયા અમુક મર્યાદામાં જ કરી શકો છો, અને તમે તે મર્યાદા વટાવી દીધી છે. કૃપા કરી થોડાક સમય પછી ફરી પ્રયત્ન કરો.',
'protectedpagetext'    => 'સંપાદન અટકાવવા માટે આ પાનું સ્થગિત કરાયેલ છે.',
'viewsourcetext'       => 'આપ આ પાનાંનો મૂળ સ્ત્રોત નિહાળી શકો છો અને તેની નકલ (copy) પણ કરી શકો છો:',
'protectedinterface'   => 'આ પાનું સોફ્ટવેર માટે ઇન્ટરફેઇસ ટેક્સટ આપે છે, અને તેને દુરુપયોગ રોકવા માટે સ્થગિત કર્યું છે.',
'editinginterface'     => "'''ચેતવણી:''' તમે જે પાનાંમાં ફેરફાર કરી રહ્યા છો તે પાનું સોફ્ટવેર માટે ઇન્ટરફેઇસ ટેક્સટ પુરી પાડે છે.
અહીંનો બદલાવ બીજા સભ્યોના પાનાંનાં દેખાવ ઉપર અસરકર્તા બનશે.
ભાષાંતર કરવા માટે કૃપા કરી [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] -- મિડિયાવિકી લોકલાઇઝેશન પ્રકલ્પ-- વાપરો",
'sqlhidden'            => '(છુપી SQL ક્વેરી)',
'namespaceprotected'   => "તમને '''$1''' નામવિભાગનાં પાનાંમાં ફેરફાર કરવાની છૂટ નથી.",
'customcssjsprotected' => 'તમને આ પાનું બદલવાની છૂટ નથી કારણકે આ પાનાંમાં બીજા સભ્યની પસંદગીના સેટીંગ્સ છે.',
'ns-specialprotected'  => 'ખાસ પાનાંમાં ફેરફાર ન થઇ શકે.',
'titleprotected'       => 'આ મથાળું (વિષય) [[User:$1|$1]] બનાવવા માટે સુરક્ષિત કરવામાં આવ્યો છે.
આ માટેનું કારણ છે-- "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "ખરાબ રૂપરેખા: અજાણ્યું વાઇરસ સ્કેનર: ''$1''",
'virus-scanfailed'     => 'સ્કેન અસફળ (code $1)',
'virus-unknownscanner' => 'અજાણ્યું એન્ટીવાઇરસ:',

# Login and logout pages
'logouttext'              => "'''તમે (લોગ આઉટ કરીને) બહાર નિકળી ચુક્યા છો.'''

તમે અનામી તરીકે {{SITENAME}} વાપરવાનું ચાલુ રાખી શકો છો, કે પછી તેના તેજ કે અલગ સભ્ય તરીકે [[Special:UserLogin|ફરી પ્રવેશ]] કરી શકો છો.
ધ્યાન રાખો કે જ્યાં સુધી તમે તમારા બ્રાઉઝરનો  કૅશ સાફ નહીં કરો ત્યાં સુધી કેટલાક પાનાં તમે પ્રવેશી ચુક્યા છો તેમ બતાવશે.",
'welcomecreation'         => '== તમારૂં સ્વાગત છે $1! ==
તમારૂં ખાતું બની ગયું છે.
તમારી [[Special:Preferences|{{SITENAME}} પસંદગી]] બદલવાનું ભૂલશો નહીં.',
'yourname'                => 'સભ્ય નામ:',
'yourpassword'            => 'ગુપ્ત સંજ્ઞા:',
'yourpasswordagain'       => 'ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો',
'remembermypassword'      => 'આ કોમ્યૂટર પર મારી લૉગ ઇન વિગતો ધ્યાનમાં રાખો (વધુમાં વધુ $1 {{PLURAL:$1|દિવસ|દિવસ}} માટે)',
'yourdomainname'          => 'તમારૂં ડોમેઇન:',
'externaldberror'         => 'પ્રમાણભૂતતાની ત્રુટી આવી અથવા તમારૂ બહારનુ ખાતું અપડેટ કરવાનો અધિકાર તમને નથી.',
'login'                   => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'nav-login-createaccount' => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'loginprompt'             => '{{SITENAME}}માં પ્રવેશ કરવા માટે તમારા બ્રાઉઝરમાં કુકીઝ એનેબલ કરેલી હોવી જોઇશે.',
'userlogin'               => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'userloginnocreate'       => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'logout'                  => 'બહાર નીકળો',
'userlogout'              => 'બહાર નીકળો/લૉગ આઉટ',
'notloggedin'             => 'પ્રવેશ કરેલ નથી',
'nologin'                 => "શું તમારૂં ખાતું નથી? તો નવું '''$1'''.",
'nologinlink'             => 'ખાતું ખોલો',
'createaccount'           => 'નવું ખાતું ખોલો',
'gotaccount'              => "પહેલેથી ખાતું ખોલેલું છે? '''$1'''.",
'gotaccountlink'          => 'પ્રવેશો (લૉગ ઇન કરો)',
'createaccountmail'       => 'ઇ-મેઇલ દ્વારા',
'createaccountreason'     => 'કારણ:',
'badretype'               => 'તમે દાખલ કરેલ ગુપ્તસંજ્ઞા મળતી આવતી નથી.',
'userexists'              => 'દાખલ કરેલું સભ્ય નામ વપરાશમાં છે.</br>
કૃપયા અન્ય નામ પસંદ કરો.',
'loginerror'              => 'પ્રવેશ ત્રુટિ',
'nocookiesnew'            => 'તમારુ સભ્ય ખાતું બની ગયું છે પણ તમે પ્રવેશ (લોગ ઇન) કર્યો નથી.

{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.

કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'nocookieslogin'          => '{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.
તમે કુકીઝ બંધ કરી છે.
કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'noname'                  => 'તમે પ્રમાણભૂત સભ્યનામ જણાવેલ નથી.',
'loginsuccesstitle'       => 'પ્રવેશ સફળ',
'loginsuccess'            => "'''તમે હવે {{SITENAME}}માં \"\$1\" તરીકે પ્રવેશી ચુક્યા છો.'''",
'nosuchuser'              => '"$1" નામ ધરાવતો કોઇ સભ્ય અસ્તિત્વમાં નથી.

સભ્યનામો અક્ષરસંવેદી (કેસ સેન્સિટીવ) હોય છે.

કૃપા કરી સ્પેલીંગ/જોડણી ચકાસો અથવા [[Special:UserLogin/signup|નવું ખાતુ ખોલો]].',
'nosuchusershort'         => '"<nowiki>$1</nowiki>" નામનો કોઇ સભ્ય નથી, તમારી જોડણી તપાસો.',
'nouserspecified'         => 'તમારે સભ્ય નામ દર્શાવવાની જરૂર છે.',
'wrongpassword'           => 'તમે લખેલી ગુપ્ત સંજ્ઞા ખોટી છે.
ફરીથી પ્રયત્ન કરો.',
'wrongpasswordempty'      => 'તમે ગુપ્ત સંજ્ઞા લખવાનું ભુલી ગયા લાગો છો.
ફરીથી પ્રયત્ન કરો.',
'passwordtooshort'        => 'ગુપ્ત સંજ્ઞામાં ઓછામાં {{PLURAL:$1|ઓછો એક અક્ષર હોવો |ઓછા $1 અક્ષર હોવા}} જોઇએ.',
'password-name-match'     => 'તમારી ગુપ્તસંજ્ઞા તમારા સભ્યનામ કરતાં અલગ જ હોવી જોઇએ.',
'mailmypassword'          => 'પાસવર્ડ ઇ-મેલમાં મોકલો',
'passwordremindertitle'   => '{{SITENAME}} માટેની નવી કામચલાઉ ગુપ્ત સંજ્ઞા',
'passwordremindertext'    => 'કોઇકે (કદાચ તમે IP એડ્રેસ $1 પરથી) {{SITENAME}} ($4) માટે નવી ગુપ્ત સજ્ઞા (પાસવર્ડ) માટે વિનંતી કરેલ છે.
હંગામી ધોરણે સભ્ય "$2" માટે ગુપ્ત સંજ્ઞા બની છે અને તે "$3". જો તમે જ આ વિનંતી કરી હોય અને તમે ગુપ્ત સંજ્ઞા બદલવા માંગતા હો તો તમારે પ્રવેશ કરવો પડશે અને નવી ગુપ્ત સંજ્ઞા પસંદ કરવી પડશે. હંગામી ગુપ્ત સંજ્ઞાની અવધિ {{PLURAL:$5|એક દિવસ|$5 દિવસો}} છે ત્યાર બાદ તે કામ નહીં કરે.

જો બીજા કોઇએ આ વિનંતી કરી હોય અથવા તમને તમારી જુની ગુપ્ત સંજ્ઞા યાદ આવી ગઇ હોય અને તમે તે બદલવા ન માંગતા હો તો આ સંદેશ અવગણીને તમારી જુની ગુપ્ત સંજ્ઞા વાપરવાનું ચાલુ રાખો.',
'noemail'                 => 'સભ્ય "$1"નું કોઇ ઇ-મેલ સરનામું નોંધાયેલું નથી.',
'passwordsent'            => 'A new password has been sent to the e-mail address registered for "$1".
Please log in again after you receive it.
"$1" ની નવી ગુપ્તસંજ્ઞા (પાસવર્ડ) આપના ઇમેઇલ પર મોકલવામાં આવ્યો છે.
કૃપા કરી તે મળ્યા બાદ ફરી લોગ ઇન કરો.',
'blocked-mailpassword'    => 'Your IP address is blocked from editing, and so is not allowed to use the password recovery function to prevent abuse.
ફેરફાર કરવા માટે તમારું IP એડ્રેસ  સ્થગિત કરી દેવાયું છે તેથી દૂરુપયોગ ટાળવા માટે તમને ગુપ્તસંજ્ઞા રીકવરી કરવાની છૂટ નથી.',
'eauthentsent'            => 'પુષ્ટિ કરવા માટે તમે આપેલા સરનામાં પર ઇમેઇલ મોકલવામાં આવ્યો છે.
એ જ સરનામે બીજો ઇમેઇલ થતાં પહેલાં તમારે ઇમેઇલમાં લખેલી સૂચનાઓ પ્રમાણે કરવું પડશે જેથી એ પુષ્ટિ થઇ શકે કે આપેલું સરનામું તમારું છે.',
'throttled-mailpassword'  => 'ગુપ્ત સંજ્ઞા યાદ અપાવતી ઇમેઇલ છેલ્લા {{PLURAL:$1|કલાક|$1 કલાકમાં}} મોકલેલી છે.
દૂરુપયોગ રોકવા માટે, {{PLURAL:$1|કલાક|$1 કલાકમાં}} ફક્ત એક જ આવી મેઇલ કરવામાં આવે છે.',
'mailerror'               => 'મેઇલ મોકલવામાં ત્રુટિ: $1',
'emailauthenticated'      => 'તમારૂં ઇ-મેઇલ સરનામું $2 ના $3 સમયે પ્રમાણિત કરેલું છે.',
'emailnotauthenticated'   => 'તમારૂં ઇ-મેઇલ સરનામું હજુ સુધી પ્રમાણિત થયેલું નથી.

નિમ્નલિખિત વિશેષતાઓમાંથી કોઇ માટે ઇ-મેઇલ મોકલવામાં આવશે નહીં.',
'noemailprefs'            => "આ વિશેષતાઓ કાર્ય કરી શકે તે માટે 'તમારી પસંદ'માં ઇ-મેઇલ સરનામું દર્શાવો.",
'emailconfirmlink'        => 'તમારા ઇ-મેઇલ સરનામાની પુષ્ટિ કરો',
'accountcreated'          => 'ખાતું ખોલવામાં આવ્યું છે',
'accountcreatedtext'      => '$1 માટે સભ્ય ખાતુ બનાવ્યું.',
'createaccount-title'     => '{{SITENAME}} માટે ખાતુ બનાવ્યું',
'createaccount-text'      => 'કોઇકે {{SITENAME}} ($4) પર, નામ "$2", ગુપ્તસંજ્ઞા "$3", શાથે તમારા ઇ-મેઇલ એડ્રેસ માટે ખાતુ બનાવેલ છે.

તમે હવે પ્રવેશ કરી અને ગુપ્તસંજ્ઞા બદલી શકો છો.

જો આ ખાતુ ભુલથી બનેલું હોય તો,આ સંદેશને અવગણી શકો છો.',
'login-throttled'         => 'તમે હાલમાં જ ઘણા પ્રવેશ પ્રયત્નો કર્યા.
કૃપા કરી ફરી પ્રયાસ પહેલાં થોડી રાહ જુઓ.',
'loginlanguagelabel'      => 'ભાષા: $1',

# JavaScript password checks
'password-retype' => 'ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો',

# Password reset dialog
'resetpass'                 => 'ગુપ્તસંજ્ઞા બદલો',
'resetpass_announce'        => 'તમે હંગામી ઇમેઇલ કોડ સાથે લોગ ઇન કર્યું.
લોગીંગ પુરૂં કરવા માટે તમારે નવી ગુપ્ત સંજ્ઞા (પાસવર્ડ) આપવો પડશે:',
'resetpass_text'            => '<!-- અહીં ટેક્સટ ઉમેરો -->',
'resetpass_header'          => 'ખાતાની ગુપ્તસંજ્ઞા બદલો',
'oldpassword'               => 'જુની ગુપ્તસંજ્ઞા:',
'newpassword'               => 'નવી ગુપ્તસંજ્ઞા:',
'retypenew'                 => 'નવી ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો:',
'resetpass_submit'          => 'ગુપ્તસંજ્ઞા બદલીને પ્રવેશ કરો.',
'resetpass_success'         => 'તમારી ગુપ્તસંજ્ઞા સફળતાપૂર્વક બદલાઇ ગઇ! હવે તમે ...માં પ્રવેશ કરી શકો છો',
'resetpass_forbidden'       => 'ગુપ્તસંજ્ઞા બદલી શકાશે નહીં',
'resetpass-submit-loggedin' => 'ગુપ્તસંજ્ઞા બદલો',
'resetpass-submit-cancel'   => 'રદ કરો',
'resetpass-temp-password'   => 'કામચલાવ ગુપ્તસંજ્ઞા:',

# Edit page toolbar
'bold_sample'     => 'ઘાટા અક્ષર',
'bold_tip'        => 'ઘાટા અક્ષર',
'italic_sample'   => 'ત્રાંસા અક્ષર',
'italic_tip'      => 'ઇટાલિક (ત્રાંસુ) લખાણ',
'link_sample'     => 'કડીનું શીર્ષક',
'link_tip'        => 'આંતરિક કડી',
'extlink_sample'  => 'http://www.example.com કડીનું શીર્ષક',
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
'subject'                          => 'વિષય/શીર્ષક:',
'minoredit'                        => 'આ એક નાનો સુધારો છે.',
'watchthis'                        => 'આ પાનાને ધ્યાનમાં રાખો',
'savearticle'                      => 'સાચવો',
'preview'                          => 'પૂર્વાવલોકન',
'showpreview'                      => 'ઝલક',
'showlivepreview'                  => 'જીવંત પૂર્વાવલોકન',
'showdiff'                         => 'ફેરફારો',
'anoneditwarning'                  => "'''ચેતવણી:''' તમે તમારા સભ્ય નામથી પ્રવેશ કર્યો નથી.
આ પાનાનાં ઇતિહાસમાં તમારૂં આઇ.પી. (IP) એડ્રેસ નોંધવામાં આવશે.",
'missingcommenttext'               => 'કૃપા કરી નીચે ટીપ્પણી લખો.',
'summary-preview'                  => 'સારાંશ પૂર્વાવલોકન:',
'subject-preview'                  => 'વિષય/શિર્ષક પૂર્વાવલોકન:',
'blockedtitle'                     => 'સભ્ય પ્રતિબંધિત છે',
'blockedtext'                      => "'''આપનાં સભ્ય નામ અથવા આઇ.પી. એડ્રેસ પર પ્રતિબંધ મુકવામાં આવ્યો છે.'''

આ પ્રતિબંધ  $1એ મુક્યો છે.
જેને માટે કારણ આપવામાં આવ્યું છે કે, ''$2''.

* પ્રતિબંધ મુક્યા તારીખ: $8
* પ્રતિબંધ ઉઠાવવાની તારીખ: $6
* જેના ઉપર પ્રતિબંધ મુક્યો છે તે: $7

આપનાં પર મુકવામાં આવેલાં પ્રતિબંધ વિષે ચર્ચા કરવા માટે આપ $1નો કે અન્ય [[{{MediaWiki:Grouppage-sysop}}|પ્રબંધક]]નો સંપર્ક કરી શકો છો.
આપ 'સભ્યને ઇ-મેલ કરો' ની કડી વાપરી નહી શકો, પરંતુ જો આપનાં [[Special:Preferences|મારી પસંદ]]માં યોગ્ય ઇ-મેલ સરનામું વાપર્યું હશે અને તમારા તે ખાતું વાપરવા ઉપર પ્રતિબંધ નહી મુક્યો હોય તો તમે તે કડીનો ઉપયોગ કરી શકશો.
તમારૂં હાલનું આઇ.પી સરનામું છે $3, અને જેના પર પ્રતિબંધ મુકવામાં આવ્યો છે તે છે  #$5.
મહેરબાની કરીને કોઇ પણ પત્ર વ્યવહારમાં ઉપરની બધીજ માહિતીનો ઉલ્લેખ કરશો.",
'blockednoreason'                  => 'કોઇ કારણ દર્શાવવામાં આવ્યું નથી',
'blockedoriginalsource'            => "'''$1'''નો સ્રોત નીચે દર્શાવેલ છે:",
'blockededitsource'                => "'''$1''' માટે '''તમારા ફેરફારો''' નીચે દેખાય છે:",
'whitelistedittitle'               => 'ફેરફારો કરવા માટે લોગીન જરૂરી છે.',
'whitelistedittext'                => 'ફેરફાર કરવા માટે તમારે $1 કરવાનું છે.',
'confirmedittext'                  => 'પાનાંમાં ફેરફાર કરવા માટે તમારે તમારા ઇમેલની પુષ્ટિ કરવી પડશે.
મહેરબાની કરી [[Special:Preferences|મારી પસંદ]]માં જઇને તમારું ઇમેલ આપવું પડશે અને પ્રમાણિત કરવું પડશે.',
'nosuchsectiontitle'               => 'આવો કોઇ વિભાગ નથી',
'nosuchsectiontext'                => 'તમે અસ્તિત્વ ન ધરાવતો વિભાગ સંપાદિત કરવાની કોશિશ કરી.',
'loginreqtitle'                    => 'પ્રવેશ (લોગ ઇન) જરૂરી',
'loginreqlink'                     => 'લોગીન',
'loginreqpagetext'                 => 'બીજા પાનાં જોવા માટે તમારે $1 કરવું પડશે.',
'accmailtitle'                     => 'ગુપ્તસંજ્ઞા મોકલવામાં આવી છે.',
'newarticle'                       => '(નવિન)',
'newarticletext'                   => "આપ જે કડીને અનુસરીને અહીં પહોંચ્યા છો તે પાનું અસ્તિત્વમાં નથી.
<br />નવું પાનું બનાવવા માટે નીચે આપેલા ખાનામાં લખવાનું શરૂ કરો (વધુ માહિતિ માટે [[{{MediaWiki:Helppage}}|મદદ]] જુઓ).
<br />જો આપ ભુલમાં અહીં આવી ગયા હોવ તો, આપનાં બ્રાઉઝર નાં '''બેક''' બટન પર ક્લિક કરીને પાછા વળો.",
'noarticletext'                    => 'આ પાનામાં હાલમાં કોઇ માહિતિ નથી.
તમે  [[Special:Search/{{PAGENAME}}|આ શબ્દ]] ધરાવતાં અન્ય લેખો શોધી શકો છો, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} સંલગ્ન માહિતિ પત્રકોમાં શોધી શકો છો],
અથવા  [{{fullurl:{{FULLPAGENAME}}|action=edit}} આ પાનામાં ફેરફાર કરી] માહિતિ ઉમેરવાનું શરૂ કરી શકો છો</span>.',
'note'                             => "'''નોંધ:'''",
'previewnote'                      => "'''આ ફક્ત પૂર્વાવલોકન છે;'''
ફેરફારો હજુ સાચવવામાં નથી આવ્યા!",
'previewconflict'                  => 'જો તમે આ પાનું સાચવશો તો આ પ્રિવ્યુમાં દેખાય છે તેવું સચવાશે.',
'editing'                          => '$1નો ફેરફાર કરી રહ્યા છે',
'editingsection'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'editingcomment'                   => 'સંપાદન $1 (નવો વિભાગ )',
'editconflict'                     => 'સંપાદન સંઘર્ષ: $1',
'yourtext'                         => 'તમારું લખાણ',
'storedversion'                    => 'રક્ષિત પુનરાવર્તન',
'yourdiff'                         => 'ભેદ',
'copyrightwarning'                 => "મહેરબાની કરીને એ વાતની નોંધ લેશો કે {{SITENAME}}માં કરેલું બધુંજ યોગદાન $2 હેઠળ પ્રકાશિત કરએલું માનવામાં આવે છે (વધુ માહિતિ માટે $1 જુઓ).
<br />જો આપ ના ચાહતા હોવ કે તમારા યોગદાનમાં અન્ય કોઇ વ્યક્તિ બેધડક પણે ફેરફાર કરે અને તેને પુનઃપ્રકાશિત કરે, તો અહીં યોગદાન કરશો નહી.
<br />સાથે સાથે તમે અમને એમ પણ ખાતરી આપી રહ્યા છો કે આ લખાણ તમે મૌલિક રીતે લખ્યું છે, અથવાતો પબ્લિક ડોમેઇન કે તેવા અન્ય મુક્ત સ્ત્રોતમાંથી લીધું છે.
<br />'''પરવાનગી વગર પ્રકાશનાધિકાર થી સુરક્ષિત (COPYRIGHTED) કાર્ય અહીં પ્રકાશિત ના કરશો!'''",
'longpagewarning'                  => "'''ચેતવણી: આ પાનું $1 કિલોબાઇટ્સ લાંબુ છે;
કેટલાંક બ્રાઉઝરોમાં લગભગ ૩૨ કિલોબાઇટ્સ જેટલાં કે તેથી મોટાં પાનાઓમાં ફેરફાર કરવામાં મુશ્કેલી પડી શકે છે.
બને ત્યાં સુધી પાનાને નાનાં વિભાગોમાં વિભાજીત કરી નાંખો.'''",
'templatesused'                    => 'આ પાનામાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'templatesusedpreview'             => 'આ પૂર્વાવલોકનમાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'template-protected'               => '(સુરક્ષિત)',
'template-semiprotected'           => '(અર્ધ સુરક્ષિત)',
'hiddencategories'                 => 'આ પાનું {{PLURAL:$1|૧ છુપી શ્રેણી|$1 છુપી શ્રેણીઓ}}નું સભ્ય છે:',
'nocreatetext'                     => '{{SITENAME}}માં નવું પાનુ બનાવવા ઉપર નિયંત્રણ આવી ગયું છે.
<br />આપ પાછા જઇને હયાત પાનામાં ફેરફાર કરી શકો છો, નહિતર [[Special:UserLogin|પ્રવેશ કરો કે નવું ખાતું ખોલો]].',
'permissionserrorstext-withaction' => '$2 પરવાનગી તમને નીચેનાં {{PLURAL:$1|કારણ|કારણો}} સર નથી:',
'recreate-moveddeleted-warn'       => "'''ચેતવણી: તમે જે પાનું નવું બનાવવા જઇ રહ્યાં છો તે પહેલાં દૂર કરવામાં આવ્યું છે.'''

આ પાનું સંપાદિત કરતા પહેલાં ગંભીરતાપૂર્વક વિચારજો અને જો તમને લાગે કે આ પાનું ફરી વાર બનાવવું ઉચિત છે, તો જ અહીં ફેરફાર કરજો.
પાનું હટાવ્યાં પહેલાનાં બધા ફેરફારોની સૂચિ તમારી અનુકૂળતા માટે અહીં આપી છે:",
'edit-conflict'                    => 'સંપાદન સંઘર્ષ.',

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
'histlegend'             => "વિવિધ પસંદગી:સરખામણી માટે સુધારેલી આવૃતિઓના રેડિયોબોક્ષોને ચિહ્નિત કરો અને એન્ટર અથવા તળીયાનું બટન દબાવો.<br />
મુદ્રાલેખ:'''({{int:cur}})''' = વર્તમાન સુધારેલી આવૃતિઓનો તફાવત, '''({{int:last}})''' = પૂર્વવર્તી સુધારેલી આવૃતિઓનો તફાવત, '''{{int:minoreditletter}}''' = નાનું સંપાદન.",
'history-fieldset-title' => 'ઇતિહાસ ઉખેળો',
'histfirst'              => 'સૌથી જુનું',
'histlast'               => 'સૌથી નવું',
'historyempty'           => '(ખાલી)',

# Revision feed
'history-feed-item-nocomment' => '$1, $2 સમયે',

# Revision deletion
'rev-delundel'               => 'બતાવો/છુપાવો',
'revdelete-show-file-submit' => 'હા',
'revdelete-radio-set'        => 'હા',
'revdelete-radio-unset'      => 'ના',
'revdel-restore'             => 'વિઝિબિલિટિ બદલો',
'pagehist'                   => 'પાનાનો ઇતિહાસ',
'deletedhist'                => 'રદ કરેલનો ઇતિહાસ',
'revdelete-content'          => 'સામગ્રી',
'revdelete-summary'          => 'સંપાદનનો સંક્ષિપ્ત અહેવાલ',
'revdelete-uname'            => 'સભ્યનામ',
'revdelete-hid'              => 'છુપાવો $1',
'revdelete-unhid'            => 'દર્શાવો $1',
'revdelete-otherreason'      => 'અન્ય/વધારાનું કારણ:',
'revdelete-reasonotherlist'  => 'અન્ય કારણ',
'revdelete-edit-reasonlist'  => 'ભુંસવાનું કારણ બદલો.',

# Suppression log
'suppressionlog' => 'દાબ નોંધ',

# History merging
'mergehistory'      => 'પાનાનાં ઇતિહાસોનું વિલીનીકરણ',
'mergehistory-from' => 'સ્ત્રોત પાનું',

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
'notitlematches'            => 'આ શબ્દ સાથે કોઇ શિર્ષક મળતું આવતું નથી',
'notextmatches'             => 'આ શબ્દ કોઈ પાનાંમાં મળ્યો નથી',
'prevn'                     => 'પહેલાનાં {{PLURAL:$1|$1}}',
'nextn'                     => 'પછીનાં {{PLURAL:$1|$1}}',
'viewprevnext'              => 'જુઓ: ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:સૂચિ',
'search-result-size'        => '$1 ({{PLURAL:$2|1 શબ્દ|$2 શબ્દો}})',
'search-result-score'       => 'પ્રસ્તુતિ: $1%',
'search-redirect'           => '(અન્યત્ર પ્રસ્થાન $1)',
'search-section'            => '(વિભાગ $1)',
'search-suggest'            => 'શું તમે $1 કહેવા માંગો છો?',
'search-interwiki-caption'  => 'બંધુ પ્રકલ્પ',
'search-interwiki-default'  => '$1 પરીણામો:',
'search-interwiki-more'     => '(વધુ)',
'search-mwsuggest-enabled'  => 'સુઝાવ સહિત',
'search-mwsuggest-disabled' => 'સુઝાવ વિના',
'nonefound'                 => "'''નોંધ''':ફક્ત અમુકજ નામસ્થળોમાં આપોઆપ શોધાશે.
તમારા શબ્દને ''બધા:'' ઉમેરી શોધવાનો પ્રયત્ન કરો, જેથી બધી માહિતિમાં (જેમકે ચર્ચાના પાના, ઢાંચા, વિગેરે)માં શોધ થઈ શકે, અથવાતો ઇચ્છિત નામસ્થળ પસંદ કરી શોધો બટન દબાવો.",
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
'preferences'               => 'પસંદ',
'mypreferences'             => 'મારી પસંદ',
'skin-preview'              => 'ફેરફાર બતાવો',
'prefs-datetime'            => 'તારીખ અને સમય',
'prefs-watchlist'           => 'ધ્યાનસૂચિ',
'searchresultshead'         => 'શોધો',
'timezonelegend'            => 'સમય ક્ષેત્ર:',
'localtime'                 => 'સ્થાનીક સમય:',
'servertime'                => 'સર્વર સમય:',
'guesstimezone'             => 'બ્રાઉઝરમાંથી દાખલ કરો',
'timezoneregion-africa'     => 'આફ્રિકા',
'timezoneregion-america'    => 'અમેરિકા',
'timezoneregion-antarctica' => 'એન્ટાર્કટિકા',
'timezoneregion-arctic'     => 'આર્કટિક',
'timezoneregion-asia'       => 'એશિયા',
'timezoneregion-atlantic'   => 'એટલાંટિક મહાસાગર',
'timezoneregion-australia'  => 'ઔસ્ટ્રેલિયા',
'timezoneregion-europe'     => 'યુરોપ',
'timezoneregion-indian'     => 'હિંદ મહાસાગર',
'timezoneregion-pacific'    => 'પ્રશાંત મહાસાગર',
'prefs-searchoptions'       => 'શોધ વિકલ્પો',
'prefs-emailconfirm-label'  => 'ઇ-મેલ પુષ્ટી',
'youremail'                 => 'ઇ-મેઇલ:',
'username'                  => 'સભ્ય નામ:',
'prefs-memberingroups'      => '{{PLURAL:$1|સમુહ|સમુહો}}ના સભ્ય:',
'yourrealname'              => 'સાચું નામ:',
'yourlanguage'              => 'ભાષા',
'yournick'                  => 'સહી:',
'badsiglength'              => 'તમારી સહી વધુ લાંબી છે.
તે $1 {{PLURAL:$1|અક્ષર|અક્ષરો}} કરતા વધુ લાંબી ન હોવી જોઇએ.',
'yourgender'                => 'જાતિ:',
'gender-unknown'            => 'અનિર્દિષ્ટ',
'gender-male'               => 'પુરુષ',
'gender-female'             => 'સ્ત્રી',
'email'                     => 'ઇ-મેઇલ',
'prefs-help-realname'       => 'સાચું નામ મરજીયાત છે.
જો આપ સાચું નામ આપવાનું પસંદ કરશો, તો તેનો ઉપયોગ તમારા કરેલાં યોગદાનનું શ્રેય આપવા માટે થશે.',
'prefs-help-email'          => "ઇ-મેઇલ સરનામુ વૈકલ્પિક છે, પરંતુ જો તમે તમારી ગુપ્તસંજ્ઞા ભુલી ગયા હો તો એ દ્વારા તમને નવી ગુપ્તસંજ્ઞા ઇ-મેઇલ કરી શકાશે.
તમે એ પણ પસંદ કરી શકો કે, તમારી ઓળખ જાહેર થયા વગર, અન્ય લોકો તમારા 'મારા વિષે' કે 'મારી ચર્ચા'ના પાના પરથી તમારો સંપર્ક કરી શકે.",
'prefs-help-email-required' => 'ઇ-મેઇલ સરનામુ જરૂરી.',

# User rights
'userrights-user-editname' => 'સભ્યનામ દાખલ કરો:',
'editusergroup'            => 'સભ્ય સમુહો સંપાદીત કરો',

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
'nchanges'                       => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',
'recentchanges'                  => 'તાજા ફેરફારો',
'recentchanges-legend'           => 'હાલમાં થયેલા ફેરફારોના વિકલ્પ',
'recentchanges-feed-description' => 'આ ફીડ દ્વારા વિકિમાં થયેલા તાજા ફેરફારો પર ધ્યાન રાખો.',
'rcnote'                         => "નીચે $5, $4 સુધીમાં અને તે પહેલાનાં '''$2''' દિવસમાં {{PLURAL:$1| થયેલો '''1''' માત્ર ફેરફાર|થયેલાં છેલ્લા  '''$1''' ફેરફારો}} દર્શાવ્યાં છે .",
'rcnotefrom'                     => "નીચે '''$2'''થી થયેલાં '''$1''' ફેરફારો દર્શાવ્યાં છે.",
'rclistfrom'                     => '$1 બાદ થયેલા નવા ફેરફારો બતાવો',
'rcshowhideminor'                => 'નાના ફેરફારો $1',
'rcshowhidebots'                 => 'બૉટો $1',
'rcshowhideliu'                  => 'લૉગ ઇન થયેલાં સભ્યો $1',
'rcshowhideanons'                => 'અનામિ સભ્યો $1',
'rcshowhidemine'                 => 'મારા ફેરફારો $1',
'rclinks'                        => 'છેલ્લાં $2 દિવસમાં થયેલા છેલ્લાં $1 ફેરફારો દર્શાવો<br />$3',
'diff'                           => 'ભેદ',
'hist'                           => 'ઇતિહાસ',
'hide'                           => 'છુપાવો',
'show'                           => 'બતાવો',
'minoreditletter'                => 'નાનું',
'newpageletter'                  => 'નવું',
'boteditletter'                  => 'બૉટ',
'rc_categories_any'              => 'કોઇ પણ',
'rc-enhanced-expand'             => 'વિગતો બતાવો (જાવા સ્ક્રિપ્ટ જરૂરી છે)',
'rc-enhanced-hide'               => 'વિગતો છુપાવો',

# Recent changes linked
'recentchangeslinked'          => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-feed'     => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-toolbox'  => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-title'    => '"$1" ને લગતા ફેરફારો',
'recentchangeslinked-noresult' => 'સંકળાયેલાં પાનાંમાં સુચવેલા સમય દરમ્યાન કોઇ ફેરફાર થયાં નથી.',
'recentchangeslinked-summary'  => "આ એવા ફેરફારોની યાદી છે જે આ ચોક્કસ પાના (કે શ્રેણીનાં સભ્ય પાનાઓ) સાથે જોડાયેલા પાનાઓમાં તાજેતરમાં કરવામાં આવ્યા હોય.
<br />[[Special:Watchlist|તમારી ધ્યાનસૂચિમાં]] હોય તેવા પાનાં '''ઘાટા અક્ષર'''માં વર્ણવ્યાં છે",
'recentchangeslinked-page'     => 'પાનાંનું નામ:',
'recentchangeslinked-to'       => 'આને બદલે આપેલા પાનાં સાથે જોડાયેલા લેખોમાં થયેલા ફેરફારો શોધો',

# Upload
'upload'        => 'ફાઇલ ચડાવો',
'uploadbtn'     => 'ફાઇલ ચડાવો',
'uploadlogpage' => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'filesource'    => 'સ્ત્રોત:',
'uploadedimage' => '"[[$1]]" ચઢાવ્યું',

# Special:ListFiles
'listfiles' => 'ફાઇલોની યાદી',

# File description page
'file-anchor-link'          => 'ફાઇલ/દસ્તાવેજ',
'filehist'                  => 'ફાઇલનો ઇતિહાસ',
'filehist-help'             => 'તારિખ/સમય ઉપર ક્લિક કરવાથી તે સમયે ફાઇલ કેવી હતી તે જોવા મળશે',
'filehist-current'          => 'વર્તમાન',
'filehist-datetime'         => 'તારીખ/સમય',
'filehist-thumb'            => 'લઘુચિત્ર',
'filehist-thumbtext'        => '$1ના સંસ્કરણનું લઘુચિત્ર',
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
'duplicatesoffile'          => 'નીચે જણાવેલ {{PLURAL:$1|ફાઇલ|$1 ફાઇલો}} આ ફાઇલની નકલ છે. ([[Special:FileDuplicateSearch/$2|વધુ વિગતો]])',
'sharedupload'              => 'આ ફાઇલ $1માં આવેલી છે અને શક્ય છે કે તે અન્ય પરિયોજનાઓમાં પણ વપરાતી હોય.',
'sharedupload-desc-there'   => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
વધુ માહિતી માટે મહેરબાની કરીને [$2 ફાઇલનાં વર્ણનનુ પાનું] જુઓ.',
'sharedupload-desc-here'    => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
ત્યાંનાં મૂળ [$2 ફાઇલનાં વર્ણનનાં પાનાં] પર આપેલું વર્ણન નીચે દર્શાવેલું છે.',
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
'filedelete'                  => '$1ને ભૂસી નાંખો.',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'ફાઇલ ભુસી નાખો.',
'filedelete-intro'            => "તમે '''[[Media:$1|$1]]'' ફાઇલ અને તેની સાથે સંલગ્ન ઇતિહાસ ભુંસી રહ્યા છો.",
'filedelete-intro-old'        => "તમે '''[[Media:$1|$1]]'''નું આ [$4 $3, $2] વર્ઝન ભુસી રહ્યા છો.",
'filedelete-comment'          => 'કારણ:',
'filedelete-submit'           => 'ભુંસો',
'filedelete-success'          => "'''$1'''ને ભુસી નાંખવામાં આવ્યું છે.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''નું $3, $2ના રોજનું  સંસ્કરણ ભુંસી નાખ્યું છે.",
'filedelete-nofile'           => "'''$1'''નું અસ્તિત્વ નથી.",
'filedelete-nofile-old'       => "'''$1'''નું  આપે જણાવેલ ખાસિયતવાળું સંગ્રહિત સંસ્કરણ અસ્તિત્વમાં નથી.",
'filedelete-otherreason'      => 'બીજું/વધારાનું કારણ:',
'filedelete-reason-otherlist' => 'બીજું કારણ',
'filedelete-reason-dropdown'  => '*ભુંસવાના સામાન્ય કારણો
** કોપીરાઇટ ઉલંઘન
** ડુપ્લીકેટ ફાઇલ',
'filedelete-edit-reasonlist'  => 'ભુંસવાનું કારણ બદલો.',

# MIME search
'mimesearch'         => 'MIME શોધ',
'mimesearch-summary' => 'આ પાનાનો ઉપયોગ MIME-પ્રકાર અનુસાર ફીલ્ટર કરવા માટે ઉપયોગ થાય છે.  ફાThis page enables the filtering of files for its MIME-type.
ઇનપુટ: પ્રકાર, e.g. <tt>image/jpeg</tt>.',
'download'           => 'ડાઉનલોડ',

# Unwatched pages
'unwatchedpages' => 'ધ્યાનમાં ન રખાયેલ પાના.',

# List redirects
'listredirects' => 'અન્યત્ર વાળેલાં પાનાંઓની યાદી',

# Unused templates
'unusedtemplates' => 'વણ વપરાયેલાં ઢાંચા',

# Random page
'randompage' => 'કોઈ પણ એક લેખ',

# Statistics
'statistics' => 'આંકડાકિય માહિતિ',

'brokenredirects-edit'   => 'ફેરફાર કરો',
'brokenredirects-delete' => 'હટાવો',

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
'newpages-username'       => 'સભ્ય નામ:',
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
'allpagesfrom'   => 'આનાથી શરૂ થતા પાના દર્શાવો:',
'allpagesto'     => 'આનાથી અંત થતા પાના દર્શાવો:',
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
'addedwatch'        => 'ધ્યાનસૂચિમાં ઉમેરવામાં આવ્યું છે',
'addedwatchtext'    => 'પાનું "[[:$1]]" તમારી [[Special:Watchlist|ધ્યાનસૂચિ]]માં ઉમેરાઈ ગયું છે.
ભવિષ્યમાં આ પાનાં અને તેનાં સંલગ્ન ચર્ચાનાં પાનાંમાં થનારા ફેરફારોની યાદી ત્યાં આપવામાં આવશે અને આ પાનું [[Special:RecentChanges|તાજેતરમાં થયેલા ફેરફારોની યાદી]]માં ઘાટા અક્ષરે જોવા મળશે, જેથી આપ સહેલાઇથી તેને અલગ તારવી શકો.',
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
'deletecomment'         => 'કારણ:',
'deleteotherreason'     => 'અન્ય/વધારાનું કારણ:',
'deletereasonotherlist' => 'અન્ય કારણ',

# Rollback
'rollbacklink' => 'પાછું વાળો',

# Protect
'protectlogpage'              => 'સુરક્ષા માહિતિ પત્રક',
'protectedarticle'            => 'સુરક્ષિત "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"નું સુરક્ષાસ્તર બદલ્યું',
'prot_1movedto2'              => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'protectcomment'              => 'કારણ:',
'protectexpiry'               => 'સમાપ્તિ:',
'protect_expiry_invalid'      => 'સમાપ્તિનો સમય માન્ય નથી.',
'protect_expiry_old'          => 'સમાપ્તિનો સમય ભૂતકાળમાં છે.',
'protect-text'                => "અહિં તમે પાનાં '''<nowiki>$1</nowiki>'''નું સુરક્ષા સ્તર જોઈ શકો છો અને તેમાં ફેરફાર પણ કરી શકશો.",
'protect-locked-access'       => "તમને પાનાંની સુરક્ષાનાં સ્તરમાં ફેરફાર કરવાની પરવાનગી નથી.
પાનાં '''$1'''નું હાલનું સેટીંગ અહિં જોઈ શકો છો:",
'protect-cascadeon'           => 'આ પાનું હાલમાં સંરક્ષિત છે કારણકે તે {{PLURAL:$1|પાનું,|પાનાઓ,}} જેમાં ધોધાકાર સંરક્ષણ ચાલુ છે, તેમાં છે.

તમે આ પાનાઓનું સંરક્ષણ સ્તર બદલી શકો છો, પરંતુ તેની અસર ધોધાકાર સંરક્ષણ પર પડવી જોઇએ નહીં.',
'protect-default'             => 'બધા સભ્યોને પરવાનગી',
'protect-fallback'            => '"$1" પરવાનગી જરૂરી',
'protect-level-autoconfirmed' => 'નવા અને નહી નોંધાયેલા સભ્યો પર પ્રતિબંધ',
'protect-level-sysop'         => 'માત્ર પ્રબંધકો',
'protect-summary-cascade'     => 'ધોધાકાર',
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
'sp-contributions-talk'        => 'યોગદાનકર્તાની ચર્ચા',
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
'whatlinkshere-hidetrans'  => '$1 આરપાર સમાવેશનો',
'whatlinkshere-hidelinks'  => 'કડીઓ $1',
'whatlinkshere-filters'    => 'ચાળણી',

# Block/unblock
'blockip'                  => 'સભ્ય પર પ્રતિબંધ મુકો',
'ipbreason'                => 'કારણ:',
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
'movepagetext'            => "નીચેનું ફોર્મ વાપરવાથી આ પાનાનું નામ બદલાઇ જશે અને તેમાં રહેલી બધી મહિતિ નવા નામે બનેલાં પાનાંમાં ખસેડાઇ જશે.
જુનું પાનું, નવા બનેલા પાના તરફ વાળતું થશે.
તમે આવા અન્યત્ર વાળેલાં પનાઓને આપોઆપ જ તેના મુળ શિર્ષક સાથે જોડી શકશો.
જો તમે તેમ કરવા ના ઇચ્છતા હોવ તો, [[Special:DoubleRedirects|બેવડા]] અથવા [[Special:BrokenRedirects|ત્રુટક કડી વાળા]] અન્યત્ર વાળેલા પાનાઓની યાદી ચકાસીને ખાતરી કરી લેશો.
કડી જે પાના પર લઈ જવી જોઈએ તે જ પાના સાથે જોડે તેની ખાતરી કરી લેવી તે તમારી જવાબદારી છે.

એ વાતની નોંધ લેશો કે, જો તમે પસંદ કરેલા નવા નામ વાળું પાનું અસ્તિત્વમાં હશે તો જુનું પાનું '''નહી ખસે''', સિવાયકે તે પાનું ખાલી હોય અથવા તે પણ અન્યત્ર વાળતું પાનું હોય અને તેનો કોઈ ઇતિહાસ ના હોય.
આનો અર્થ એમ થાય છે કે જો તમે કોઈ તબક્કે ભુલ કરશો તો જે પાનાનું નામ બદલવાનો પ્રયત્ન કરતા હોવ તેને તમે ફરી પાછા જુના નામ પર જ પાછું વાળી શકશો, અને બીજું કે પહેલેથી બનેલા પાનાનું નામ તમે નામ ફેર કરવા માટે ના વાપરી શકો.

'''ચેતવણી!'''
લોકપ્રિય પાનાં સાથે આવું કરવું બિનઅપેક્ષિત અને નાટકિય નિવડી શકે છે;
આગળ વધતાં પહેલાં આની અસરોનો પુરે પુરો તાગ મેળવી લેવો આવશ્યક છે.",
'movepagetalktext'        => "આની સાથે સાથે તેનું સંલગ્ન ચર્ચાનું પાનું પણ ખસેડવામાં આવશે, '''સિવાયકે:'''
*નવા નામ વાળું ચર્ચાનું પાનું અસ્તિત્વમાં હોય અને તેમાં લખાણ હોય, અથવા
*નીચેનાં ખાનામાંથી ખરાની નિશાની તમે દૂર કરી હોય.

આ સંજોગોમાં, જો તમે ચાહતા હોવ તો તમારે અહિંનું લખાણ જાતે નવા પાના પર ખસેડવું પડશે.",
'movearticle'             => 'આ પાનાનું નામ બદલો:',
'newtitle'                => 'આ નવું નામ આપો:',
'move-watch'              => 'આ પાનું ધ્યાનમાં રાખો',
'movepagebtn'             => 'પાનું ખસેડો',
'pagemovedsub'            => 'પાનું સફળતા પૂર્વક ખસેડવામાં આવ્યું છે',
'movepage-moved'          => '\'\'\'"$1" નું નામ બદલીને "$2" કરવામાં આવ્યું છે\'\'\'',
'articleexists'           => 'આ નામનું પાનું અસ્તિત્વમાં છે, અથવાતો તમે પસંદ કરેલું નામ અસ્વિકાર્ય છો.
કૃપા કરી અન્ય નામ પસંદ કરો.',
'talkexists'              => "'''મુખ્ય પાનું સફળતાપૂર્વક ખસેડવામાં આવ્યું છે, પરંતુ તેનું ચર્ચાનું પાનું ખસેડી શકાયું નથી, કેમકે નવા શિર્ષક હેઠળ તે પાનું પહેલેથી અસ્તિત્વમાં છે.
કૃપા કરી જાતે તેને નવાં નામ વાળાં પાનાંમાં વિલિન કરો.'''",
'movedto'                 => 'બદલ્યા પછીનું નામ',
'movetalk'                => 'સંલગ્ન ચર્ચાનું પાનું પણ ખસેડો',
'1movedto2'               => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'1movedto2_redir'         => 'નામ બદલતા [[$1]] ને [[$2]] બનાવ્યું',
'movelogpage'             => 'નામ ફેર માહિતિ પત્રક',
'movereason'              => 'કારણ:',
'revertmove'              => 'પૂર્વવત',
'delete_and_move'         => 'હટાવો અને નામ બદલો',
'delete_and_move_confirm' => 'હા, આ પાનું હટાવો',

# Export
'export'        => 'પાનાઓની નિકાસ કરો/પાના અન્યત્ર મોકલો',
'export-addcat' => 'ઉમેરો',

# Namespace 8 related
'allmessages'          => 'તંત્ર સંદેશાઓ',
'allmessagesname'      => 'નામ',
'allmessagescurrent'   => 'વર્તમાન દસ્તાવેજ',
'allmessages-language' => 'ભાષા:',

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
'tooltip-ca-edit'                 => "આપ આ પાનામાં ફેરફાર કરી શકો છો, કાર્ય સુરક્ષિત કરતાં પહેલાં 'ઝલક' બટન ઉપર ક્લિક કરીને જોઇ લેશો",
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
'tooltip-n-mainpage-description'  => 'મુખ્ય પાના પર જાઓ',
'tooltip-n-portal'                => 'પરિયોજના વિષે, આપ શું કરી શકો અને વસ્તુઓ ક્યાં શોધશો',
'tooltip-n-currentevents'         => 'પ્રસ્તુત ઘટનાની પૃષ્ઠભૂમિની માહિતિ શોધો',
'tooltip-n-recentchanges'         => 'વિકિમાં હાલમા થયેલા ફેરફારોની સૂચિ.',
'tooltip-n-randompage'            => 'કોઇ પણ એક લેખ બતાવો',
'tooltip-n-help'                  => 'શોધવા માટેની જગ્યા.',
'tooltip-t-whatlinkshere'         => 'અહીં જોડાતા બધાં વિકિ પાનાઓની યાદી',
'tooltip-t-recentchangeslinked'   => 'આ પાના પરની કડીઓ વાળા લેખોમાં તાજેતરમાં થયેલા ફેરફારો',
'tooltip-feed-rss'                => 'આ પાના માટે આર.એસ.એસ. ફીડ',
'tooltip-feed-atom'               => 'આ પાના માટે એટોમ ફીડ',
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
'tooltip-undo'                    => '"રદ કરો" આ ફેરફારને પાછો વાળશે અને ફેરફાર પછીનું પૂર્વાવલોકન ફોર્મ નવા પાના તરીકે ખુલશે.
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
* isospeedratings
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
