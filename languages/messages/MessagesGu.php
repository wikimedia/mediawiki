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
 * @author Sushant savla
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
	'DoubleRedirects'           => array( 'દ્વિ_પુનઃમાર્ગદર્શન' ),
	'BrokenRedirects'           => array( 'ખંડિત_પુનઃમાર્ગદર્શન' ),
	'Disambiguations'           => array( 'અસંદિગ્ધતા' ),
	'Userlogin'                 => array( 'સભ્યપ્રવેશ' ),
	'Userlogout'                => array( 'સભ્યનિવેશ' ),
	'CreateAccount'             => array( 'ખાતું_ખોલો' ),
	'Preferences'               => array( 'પસંદ' ),
	'Watchlist'                 => array( 'ધ્યાનસૂચિ' ),
	'Recentchanges'             => array( 'તાજાફેરફારો' ),
	'Upload'                    => array( 'ચડાવો' ),
	'Listfiles'                 => array( 'યાદીફાઇલ', 'ફાઇલયાદી', 'ચિત્રયાદી' ),
	'Newimages'                 => array( 'નવીફાઇલો', 'નવાંચિત્રો' ),
	'Listusers'                 => array( 'યાદીસભ્યો', 'સભ્યયાદી' ),
	'Listgrouprights'           => array( 'યાદીસમુહઅધિકારો', 'સભ્યસમુહઅધિકારો' ),
	'Statistics'                => array( 'આંકડાકીયમાહિતી' ),
	'Randompage'                => array( 'યાદચ્છ', 'કોઈ_પણ_એક' ),
	'Lonelypages'               => array( 'એકાકીપાનાં', 'અનાથપાનાં' ),
	'Uncategorizedpages'        => array( 'અવર્ગિકૃત_પાનાં' ),
	'Uncategorizedcategories'   => array( 'અવર્ગિકૃત_શ્રેણીઓ' ),
	'Uncategorizedimages'       => array( 'અવર્ગિકૃત_ફાઇલો', 'અવર્ગિકૃત_ચિત્રો' ),
	'Uncategorizedtemplates'    => array( 'અવર્ગિકૃત_ઢાંચા' ),
	'Unusedcategories'          => array( 'વણવપરાયેલી_શ્રેણીઓ' ),
	'Unusedimages'              => array( 'વણવપરાયેલ_ફાઇલો', 'વણવપરાયેલ_ચિત્રો' ),
	'Wantedpages'               => array( 'જોઇતા_પાનાં', 'ત્રુટક_કડી' ),
	'Wantedcategories'          => array( 'જોઇતી_શ્રેણીઓ' ),
	'Wantedfiles'               => array( 'જોઇતી_ફાઇલો' ),
	'Wantedtemplates'           => array( 'જોઇતા_ઢાંચા' ),
	'Mostlinked'                => array( 'સૌથીવધુ_જોડાયેલાં_પાનાં', 'સૌથીવધુ_જોડાયેલ' ),
	'Mostlinkedcategories'      => array( 'સૌથીવધુજોડાયેલી_શ્રેણી', 'સૌથીવધુવપરાયેલી_શ્રેણીઓ' ),
	'Mostlinkedtemplates'       => array( 'સૌથીવધુ_જોડાયેલાં_ઢાંચા', 'સૌથી_વધુવપરાયેલાં_ઢાંચા' ),
	'Mostimages'                => array( 'સૌથી_વધુજોડાયેલી_ફાઇલો', 'મહત્તમ_ફાઇલો', 'મહત્તમ_ચિત્રો' ),
	'Mostcategories'            => array( 'મોટાભાગની_શ્રેણીઓ' ),
	'Mostrevisions'             => array( 'મહત્તમ_પુનરાવર્તન' ),
	'Fewestrevisions'           => array( 'લઘુત્તમ_પુનરાવર્તન' ),
	'Shortpages'                => array( 'ટુંકાપાનાં' ),
	'Longpages'                 => array( 'લાંબાપાના' ),
	'Newpages'                  => array( 'નવાપાનાં' ),
	'Ancientpages'              => array( 'પ્રાચીનપાનાં' ),
	'Deadendpages'              => array( 'મૃતાંતપાનાં' ),
	'Protectedpages'            => array( 'સંરક્ષિતપાનાં' ),
	'Protectedtitles'           => array( 'સંરક્ષિત_શિર્ષકો' ),
	'Allpages'                  => array( 'બધાંપાનાં' ),
	'Prefixindex'               => array( 'ઉપસર્ગ' ),
	'Specialpages'              => array( 'ખાસપાનાં' ),
	'Contributions'             => array( 'પ્રદાન' ),
	'Emailuser'                 => array( 'સભ્યનેઇમેલ' ),
	'Confirmemail'              => array( 'ઇમેઇલખાતરીકરો' ),
	'Whatlinkshere'             => array( 'અહિં_શું_જોડાય_છે?' ),
	'Recentchangeslinked'       => array( 'તાજેતરનાં_ફેરફારો', 'સંલગ્ન_ફેરફારો' ),
	'Movepage'                  => array( 'પાનુંખસેડો' ),
	'Booksources'               => array( 'પુસ્તકસ્રોત' ),
	'Categories'                => array( 'શ્રેણીઓ' ),
	'Export'                    => array( 'નિકાસ' ),
	'Version'                   => array( 'સંસ્કરણ' ),
	'Allmessages'               => array( 'બધાંસંદેશા' ),
	'Log'                       => array( 'લૉગ', 'લૉગ્સ' ),
	'Blockip'                   => array( 'પ્રતિબંધ', 'IP_પર_પ્રતિબંધ', 'સભ્યપર_પ્રતિબંધ' ),
	'Undelete'                  => array( 'પુનઃપ્રાપ્ત' ),
	'Import'                    => array( 'આયાત' ),
	'Userrights'                => array( 'સભ્યાધિકાર' ),
	'FileDuplicateSearch'       => array( 'ફાઇલપ્રતિકૃતિ_શોધ' ),
	'Unwatchedpages'            => array( 'વણજોયેલા_પાનાં' ),
	'Listredirects'             => array( 'પુનઃમાર્ગદર્શનયાદી' ),
	'Revisiondelete'            => array( 'રદકરેલું_સુધારો' ),
	'Unusedtemplates'           => array( 'વણવપરાયેલાં_ઢાંચા' ),
	'Randomredirect'            => array( 'યાદચ્છ_પુનઃમાર્ગદર્શન' ),
	'Mypage'                    => array( 'મારૂપાનું' ),
	'Mytalk'                    => array( 'મારીચર્ચા' ),
	'Mycontributions'           => array( 'મારૂપ્રદાન' ),
	'Listadmins'                => array( 'યાદીપ્રબંધક' ),
	'Listbots'                  => array( 'યાદીબૉટ' ),
	'Popularpages'              => array( 'લોકપ્રિયપાનાં' ),
	'Search'                    => array( 'શોધ' ),
	'Resetpass'                 => array( 'ગુપ્તસંજ્ઞા_બદલો', 'ગુપ્તસંજ્ઞા_પુન:_સ્થાપન' ),
	'Withoutinterwiki'          => array( 'આંતરવિકિવિહીન' ),
	'MergeHistory'              => array( 'વિલિનિકરણ_ઈતિહાસ' ),
	'Filepath'                  => array( 'ફાઇલપથ' ),
	'Invalidateemail'           => array( 'અમાન્ય_ઇ-મેઇલ' ),
	'Blankpage'                 => array( 'કોરૂં_પાનું' ),
	'LinkSearch'                => array( 'કડી_શોધ' ),
	'DeletedContributions'      => array( 'હટાવેલાં_યોગદાન' ),
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
'tog-newpageshidepatrolled'   => 'નવાં પાનાંની યાદીમાંથી દેખરેખ હેઠળનાં પાનાં છુપાવો',
'tog-extendwatchlist'         => 'ધ્યાનસૂચિને વિસ્તૃત કરો જેથી,ફક્ત તાજેતરનાજ નહીં, બધા આનુષાંગિક ફેરફારો જોઇ શકાય',
'tog-usenewrc'                => 'તાજેતરનાં વર્ધિત ફેરફારો (જાવાસ્ક્રીપ્ટ જરૂરી)',
'tog-numberheadings'          => 'મથાળાંઓને આપો-આપ ક્રમ (ઑટો નંબર) આપો',
'tog-showtoolbar'             => 'ફેરફારો માટેનો ટૂલબાર બતાવો (જાવા સ્ક્રિપ્ટ)',
'tog-editondblclick'          => 'ડબલ ક્લિક દ્વારા ફેરફાર કરો (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-editsection'             => 'વિભાગોમાં [ફેરફાર કરો] કડી દ્વારા વિભાગીય ફેરફાર લાગુ કરો.',
'tog-editsectiononrightclick' => 'વિભાગના મથાળાં ને રાઇટ ક્લિક દ્વારા ફેરફાર કરવાની રીત અપનાવો. (જાવાસ્ક્રિપ્ટ જરૂરી)',
'tog-showtoc'                 => 'અનુક્રમણિકા દર્શાવો (૩થી વધુ પેટા-મથાળા વાળા લેખો માટે)',
'tog-rememberpassword'        => 'આ કમ્પ્યૂટર પર મારી લોગ-ઇન વિગતો યાદ રાખો (મહત્તમ $1 {{PLURAL:$1|દિવસ|દિવસ}} માટે)',
'tog-watchcreations'          => 'મેં લખેલા નવા લેખો મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdefault'            => 'હું ફેરફાર કરૂં તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchmoves'              => 'હું જેનું નામ બદલું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdeletion'           => 'હું હટાવું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-minordefault'            => 'બધા નવા ફેરફારો નાના તરીકે માર્ક કરો.',
'tog-previewontop'            => 'એડીટ બોક્સ પહેલાં પ્રિવ્યુ બતાવો.',
'tog-previewonfirst'          => 'પ્રથમ ફેરફાર વખતે પ્રિવ્યુ બતાવો.',
'tog-nocache'                 => 'બ્રાઉઝરનું પેજ કેશિંગ અક્રિય કરો',
'tog-enotifwatchlistpages'    => 'મારી ધ્યાનસૂચિમાંનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifusertalkpages'     => 'મારી ચર્ચાનાં પાનામાં ફેરફાર થાય ત્યારે મને ઇ-મેલ મોકલો',
'tog-enotifminoredits'        => 'પાનામાં નાનાં ફેરફાર થાય ત્યારે પણ મને ઇ-મેલ મોકલો',
'tog-enotifrevealaddr'        => 'નોટીફીકેશનના ઇમેલમાં મારૂ ઇમેલ એડ્રેસ બતાવો',
'tog-shownumberswatching'     => 'ધ્યાનમાં રાખતા સભ્યોની સંખ્યા બતાવો',
'tog-oldsig'                  => 'વિદ્યમાન હસ્તાક્ષરનું પૂર્વદર્શન:',
'tog-fancysig'                => 'સ્વાચાલિત કડી વગરની (કાચી) સહી',
'tog-externaleditor'          => 'બીજું એડીટર વાપરો. (ફક્ત એકસપર્ટ માટે, તમારા કમ્પ્યુટરમાં સેટીંગ્સ બદલવા પડશે. [http://www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'Use external diff by default (for experts only, needs special settings on your computer. [http://www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks'           => "''આના પર જાવ'' કડીને સક્રીય કરો.",
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
'editfont-style'     => 'ક્ષેત્ર લિપિ શૈલીનું સંપાદન:',
'editfont-default'   => 'બ્રાઉઝરના સેટીંગ્સ પ્રમાણે',
'editfont-monospace' => 'Monospaced font',
'editfont-sansserif' => 'Sans-serif font',
'editfont-serif'     => 'Serif font',

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
'index-category'                 => 'અનુક્રમણિકા બનાવેલા પાનાં',
'noindex-category'               => 'અનુક્રમણિકા નહી બનાવેલા પાનાં',

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
'qbpageinfo'     => 'સંદર્ભ',
'qbmyoptions'    => 'મારાં પાનાં',
'qbspecialpages' => 'ખાસ પાનાં',
'faq'            => 'FAQ
વારંવાર પુછાતા પ્રશ્નો',
'faqpage'        => 'Project:વારંવાર પુછાતા પ્રશ્નો',

# Vector skin
'vector-action-addsection'       => 'નવી ચર્ચા',
'vector-action-delete'           => 'રદ કરો',
'vector-action-move'             => 'ખસેડો',
'vector-action-protect'          => 'સુરક્ષિત કરો',
'vector-action-undelete'         => 'રદ કરેલું પાછું વાળો',
'vector-action-unprotect'        => 'અસુરક્ષિત',
'vector-simplesearch-preference' => 'શોધ સંબંધી વિશિષ્ઠ સુઝાવના પર્યાયને સક્રીય  કરો (Vector skin only)',
'vector-view-create'             => 'બનાવો',
'vector-view-edit'               => 'ફેરફાર કરો',
'vector-view-history'            => 'ઈતિહાસ જુઓ',
'vector-view-view'               => 'વાંચો',
'vector-view-viewsource'         => 'સ્ત્રોત જુઓ',
'actions'                        => 'ક્રિયાઓ',
'namespaces'                     => 'નામાવકાશો',
'variants'                       => 'ભિન્ન રૂપો',

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
'view'              => 'જુઓ',
'edit'              => 'ફેરફાર કરો',
'create'            => 'બનાવો',
'editthispage'      => 'આ પાનામાં ફેરફાર કરો',
'create-this-page'  => 'આ પાનું બનાવો.',
'delete'            => 'રદ કરો',
'deletethispage'    => 'આ પાનું હટાવો',
'undelete_short'    => 'હટાવેલ {{PLURAL:$1|એક ફેરફાર|$1 ફેરફારો}} પરત લાવો.',
'viewdeleted_short' => '{{PLURAL:$1|ભૂંસી નાખેલો એક|ભૂંસી નાખેલા $1}} ફેરફાર જુઓ',
'protect'           => 'સુરક્ષિત કરો',
'protect_change'    => 'ફેરફાર કરો',
'protectthispage'   => 'આ પાનું સુરક્ષિત કરો.',
'unprotect'         => 'સુરક્ષા હટાવો',
'unprotectthispage' => 'આ પાનાની સુરક્ષા હટાવો.',
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
'lastmodifiedat'    => 'આ પાનામાં છેલ્લો ફેરફાર $1ના રોજ $2 વાગ્યે થયો.',
'viewcount'         => 'આ પાનું {{PLURAL:$1|એક|$1}} વખત જોવામાં આવ્યું છે.',
'protectedpage'     => 'સંરક્ષિત પાનું',
'jumpto'            => 'સીધા આના પર જાવ:',
'jumptonavigation'  => 'ભ્રમણ',
'jumptosearch'      => 'શોધો',
'view-pool-error'   => 'માફ કરશો, આ સમયે સર્વર અતિબોજા હેઠળ છે.

ઘણા બધા વપરાશકર્તાઓ આ પાનું જોવાની કોશિશ કરી રહ્યા છે.

આ પાનું ફરી જોતા પહેલાં કૃપયા થોડો સમય પ્રતિક્ષા કરો.

$1',
'pool-timeout'      => 'સમય સમાપ્ત -  સ્થગિતતા પ્રતિક્ષીત',
'pool-queuefull'    => '(Pool) કતાર પૂરી ભરેલી',
'pool-errorunknown' => 'અજ્ઞાત ત્રુટિ',

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
'collapsible-collapse'    => 'સંકેલો',
'collapsible-expand'      => 'વિસ્તારો',
'thisisdeleted'           => 'જુઓ અથવા મૂળરૂપે ફેરવો $1?',
'viewdeleted'             => '$1 જોવું છે?',
'restorelink'             => '{{PLURAL:$1|એક ભુસીનાખેલો ફેરફાર|$1 ભુસીનાખેલા ફેરફારો}}',
'feedlinks'               => 'ફીડ:',
'feed-invalid'            => 'અયોગ્ય સબસ્ક્રીપ્સન ફીડ પ્રકાર.',
'feed-unavailable'        => ' સંલગ્ન માહિતીની અપૂરાતિ મોજૂદ નથી',
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
'fileappenderrorread'  => 'ઉમેરો કરતાં "$1" વાંચી શકાયું નથી',
'fileappenderror'      => '"$1" ને "$2" શાથે જોડી શકાશે નહીં.',
'filecopyerror'        => '"$1" થી "$2"માં નકલ નાકામયાબ.',
'filerenameerror'      => '"$1" નું નામ બદલીને "$2" કરવામાં નાકામયાબ.',
'filedeleteerror'      => '"$1" ફાઇલ હટાવી ન શકાઇ.',
'directorycreateerror' => 'ડીરેક્ટરી "$1" ન બનાવી શકાઇ.',
'filenotfound'         => 'ફાઇલ "$1" ન મળી.',
'fileexistserror'      => 'ફાઇલ "$1"માં ન લખી શકાયું : ફાઇલ અસ્તિત્વ ધરાવે છે.',
'unexpected'           => 'અણધારી કિંમત: "$1"="$2".',
'formerror'            => 'ત્રુટિ: પત્રક રજૂ થયું નહીં',
'badarticleerror'      => 'આ ક્રિયા આ પાના ઉપર કરવી શક્ય નથી.',
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
'actionthrottled'      => 'અકાળે અટાકાવી દીધેલી ક્રિયા',
'actionthrottledtext'  => 'સ્પામ નિયંત્રણ તકેદારી રૂપે આ ક્રિયા અમુક મર્યાદામાં જ કરી શકો છો, અને તમે તે મર્યાદા વટાવી દીધી છે. કૃપા કરી થોડાક સમય પછી ફરી પ્રયત્ન કરો.',
'protectedpagetext'    => 'ફેરફારો થતાં રોકવા માટે આ પાનું સુરક્ષિત કરવામાં આવ્યું છે.',
'viewsourcetext'       => 'આપ આ પાનાનો મૂળ સ્ત્રોત નિહાળી શકો છો અને તેની નકલ (copy) પણ કરી શકો છો:',
'protectedinterface'   => 'આ પાનું સોફ્ટવેર માટે ઇન્ટરફેઇસ ટેક્સટ આપે છે, અને તેને દુરુપયોગ રોકવા માટે સ્થગિત કર્યું છે.',
'editinginterface'     => "'''ચેતવણી:''' તમે જે પાનામાં ફેરફાર કરી રહ્યા છો તે પાનું સોફ્ટવેર માટે ઇન્ટરફેસ ટેક્સટ પુરી પાડે છે.
અહીંનો બદલાવ બીજા સભ્યોના ઇન્ટરફેસનાં દેખાવ ઉપર અસરકર્તા બનશે.
ભાષાંતર કરવા માટે કૃપા કરી [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] -- મિડિયાવિકી લોકલાઇઝેશન પ્રકલ્પ વાપરો.",
'sqlhidden'            => '(છુપી SQL ક્વેરી)',
'namespaceprotected'   => "તમને '''$1''' નામાવકાશનાં પાનાંમાં ફેરફાર કરવાની પરવાનગી નથી.",
'customcssjsprotected' => 'તમને આ પાનું બદલવાની પરવાનગી નથી કારણકે આ પાનામાં બીજા સભ્યની પસંદગીના સેટીંગ્સ છે.',
'ns-specialprotected'  => 'ખાસ પાનાંમાં ફેરફાર ન થઇ શકે.',
'titleprotected'       => 'આ મથાળું (વિષય) [[User:$1|$1]] બનાવવા માટે સુરક્ષિત કરવામાં આવ્યો છે.
આ માટેનું કારણ છે-- "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "ખરાબ રૂપરેખા: અજાણ્યું વાઇરસ સ્કેનર: ''$1''",
'virus-scanfailed'     => 'સ્કેન અસફળ (code $1)',
'virus-unknownscanner' => 'અજાણ્યું એન્ટીવાઇરસ:',

# Login and logout pages
'logouttext'               => "'''તમે (લોગ આઉટ કરીને) બહાર નિકળી ચુક્યા છો.'''

તમે અનામી તરીકે {{SITENAME}} વાપરવાનું ચાલુ રાખી શકો છો, કે પછી તેના તે જ કે અલગ સભ્ય તરીકે [[Special:UserLogin|ફરી પ્રવેશ]] કરી શકો છો.
ધ્યાન રાખો કે જ્યાં સુધી તમે તમારા બ્રાઉઝરનો  કૅશ સાફ નહીં કરો ત્યાં સુધી કેટલાક પાનાં તમે પ્રવેશી ચુક્યા છો તેમ બતાવશે.",
'welcomecreation'          => '== તમારૂં સ્વાગત છે $1! ==
તમારૂં ખાતું બની ગયું છે.
તમારી [[Special:Preferences|{{SITENAME}} પસંદગી]] બદલવાનું ભૂલશો નહીં.',
'yourname'                 => 'સભ્ય નામ:',
'yourpassword'             => 'ગુપ્ત સંજ્ઞા:',
'yourpasswordagain'        => 'ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો',
'remembermypassword'       => 'આ કોમ્યૂટર પર મારી લૉગ ઇન વિગતો ધ્યાનમાં રાખો (વધુમાં વધુ $1 {{PLURAL:$1|દિવસ|દિવસ}} માટે)',
'securelogin-stick-https'  => 'લોગ-ઈન કર્યા પછી  HTTPS સાથે જોડાયેલા રહો.',
'yourdomainname'           => 'તમારૂં ડોમેઇન:',
'externaldberror'          => 'પ્રમાણભૂતતાની ત્રુટી આવી અથવા તમારૂ બહારનુ ખાતું અપડેટ કરવાનો અધિકાર તમને નથી.',
'login'                    => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'nav-login-createaccount'  => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'loginprompt'              => '{{SITENAME}}માં પ્રવેશ કરવા માટે તમારા બ્રાઉઝરમાં કુકીઝ એનેબલ કરેલી હોવી જોઇશે.',
'userlogin'                => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'userloginnocreate'        => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'logout'                   => 'બહાર નીકળો',
'userlogout'               => 'બહાર નીકળો/લૉગ આઉટ',
'notloggedin'              => 'પ્રવેશ કરેલ નથી',
'nologin'                  => "શું તમારૂં ખાતું નથી? તો નવું '''$1'''.",
'nologinlink'              => 'ખાતું ખોલો',
'createaccount'            => 'નવું ખાતું ખોલો',
'gotaccount'               => "પહેલેથી ખાતું ખોલેલું છે? '''$1'''.",
'gotaccountlink'           => 'પ્રવેશો (લૉગ ઇન કરો)',
'createaccountmail'        => 'ઇ-મેઇલ દ્વારા',
'createaccountreason'      => 'કારણ:',
'badretype'                => 'તમે દાખલ કરેલ ગુપ્તસંજ્ઞા મળતી આવતી નથી.',
'userexists'               => 'દાખલ કરેલું સભ્ય નામ વપરાશમાં છે.</br>
કૃપયા અન્ય નામ પસંદ કરો.',
'loginerror'               => 'પ્રવેશ ત્રુટિ',
'createaccounterror'       => 'ખાતું ખોલી શકાયું નથી: $1',
'nocookiesnew'             => 'તમારુ સભ્ય ખાતું બની ગયું છે પણ તમે પ્રવેશ (લોગ ઇન) કર્યો નથી.

{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.

કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'nocookieslogin'           => '{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.
તમે કુકીઝ બંધ કરી છે.
કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'noname'                   => 'તમે પ્રમાણભૂત સભ્યનામ જણાવેલ નથી.',
'loginsuccesstitle'        => 'પ્રવેશ સફળ',
'loginsuccess'             => "'''તમે હવે {{SITENAME}}માં \"\$1\" તરીકે પ્રવેશી ચુક્યા છો.'''",
'nosuchuser'               => '"$1" નામ ધરાવતો કોઇ સભ્ય અસ્તિત્વમાં નથી.

સભ્યનામો અક્ષરસંવેદી (કેસ સેન્સિટીવ) હોય છે.

કૃપા કરી સ્પેલીંગ/જોડણી ચકાસો અથવા [[Special:UserLogin/signup|નવું ખાતુ ખોલો]].',
'nosuchusershort'          => '"<nowiki>$1</nowiki>" નામનો કોઇ સભ્ય નથી, તમારી જોડણી તપાસો.',
'nouserspecified'          => 'તમારે સભ્ય નામ દર્શાવવાની જરૂર છે.',
'login-userblocked'        => 'આ યુઝર પ્રતિબંધિત છે. પ્રવેશ વર્જીત.',
'wrongpassword'            => 'તમે લખેલી ગુપ્ત સંજ્ઞા ખોટી છે.
ફરીથી પ્રયત્ન કરો.',
'wrongpasswordempty'       => 'તમે ગુપ્ત સંજ્ઞા લખવાનું ભુલી ગયા લાગો છો.
ફરીથી પ્રયત્ન કરો.',
'passwordtooshort'         => 'ગુપ્ત સંજ્ઞામાં ઓછામાં {{PLURAL:$1|ઓછો એક અક્ષર હોવો |ઓછા $1 અક્ષર હોવા}} જોઇએ.',
'password-name-match'      => 'તમારી ગુપ્તસંજ્ઞા તમારા સભ્યનામ કરતાં અલગ જ હોવી જોઇએ.',
'password-login-forbidden' => 'આ સભ્યનામ અને ગુપ્તસંજ્ઞા વાપરવા પર પ્રતિબંધ છે.',
'mailmypassword'           => 'પાસવર્ડ ઇ-મેલમાં મોકલો',
'passwordremindertitle'    => '{{SITENAME}} માટેની નવી કામચલાઉ ગુપ્ત સંજ્ઞા',
'passwordremindertext'     => 'કોઇકે (કદાચ તમે IP એડ્રેસ $1 પરથી) {{SITENAME}} ($4) માટે નવી ગુપ્ત સજ્ઞા (પાસવર્ડ) માટે વિનંતી કરેલ છે.
હંગામી ધોરણે સભ્ય "$2" માટે ગુપ્ત સંજ્ઞા બની છે અને તે "$3". જો તમે જ આ વિનંતી કરી હોય અને તમે ગુપ્ત સંજ્ઞા બદલવા માંગતા હો તો તમારે પ્રવેશ કરવો પડશે અને નવી ગુપ્ત સંજ્ઞા પસંદ કરવી પડશે. હંગામી ગુપ્ત સંજ્ઞાની અવધિ {{PLURAL:$5|એક દિવસ|$5 દિવસો}} છે ત્યાર બાદ તે કામ નહીં કરે.

જો બીજા કોઇએ આ વિનંતી કરી હોય અથવા તમને તમારી જુની ગુપ્ત સંજ્ઞા યાદ આવી ગઇ હોય અને તમે તે બદલવા ન માંગતા હો તો આ સંદેશ અવગણીને તમારી જુની ગુપ્ત સંજ્ઞા વાપરવાનું ચાલુ રાખો.',
'noemail'                  => 'સભ્ય "$1"નું કોઇ ઇ-મેલ સરનામું નોંધાયેલું નથી.',
'noemailcreate'            => 'વૈધ ઇ-મેલ આપશો',
'passwordsent'             => 'A new password has been sent to the e-mail address registered for "$1".
Please log in again after you receive it.
"$1" ની નવી ગુપ્તસંજ્ઞા (પાસવર્ડ) આપના ઇમેઇલ પર મોકલવામાં આવ્યો છે.
કૃપા કરી તે મળ્યા બાદ ફરી લોગ ઇન કરો.',
'blocked-mailpassword'     => 'Your IP address is blocked from editing, and so is not allowed to use the password recovery function to prevent abuse.
ફેરફાર કરવા માટે તમારું IP એડ્રેસ  સ્થગિત કરી દેવાયું છે તેથી દૂરુપયોગ ટાળવા માટે તમને ગુપ્તસંજ્ઞા રીકવરી કરવાની છૂટ નથી.',
'eauthentsent'             => 'પુષ્ટિ કરવા માટે તમે આપેલા સરનામાં પર ઇમેઇલ મોકલવામાં આવ્યો છે.
એ જ સરનામે બીજો ઇમેઇલ થતાં પહેલાં તમારે ઇમેઇલમાં લખેલી સૂચનાઓ પ્રમાણે કરવું પડશે જેથી એ પુષ્ટિ થઇ શકે કે આપેલું સરનામું તમારું છે.',
'throttled-mailpassword'   => 'ગુપ્ત સંજ્ઞા યાદ અપાવતી ઇમેઇલ છેલ્લા {{PLURAL:$1|કલાક|$1 કલાકમાં}} મોકલેલી છે.
દૂરુપયોગ રોકવા માટે, {{PLURAL:$1|કલાક|$1 કલાકમાં}} ફક્ત એક જ આવી મેઇલ કરવામાં આવે છે.',
'mailerror'                => 'મેઇલ મોકલવામાં ત્રુટિ: $1',
'emailauthenticated'       => 'તમારૂં ઇ-મેઇલ સરનામું $2 ના $3 સમયે પ્રમાણિત કરેલું છે.',
'emailnotauthenticated'    => 'તમારૂં ઇ-મેઇલ સરનામું હજુ સુધી પ્રમાણિત થયેલું નથી.

નિમ્નલિખિત વિશેષતાઓમાંથી કોઇ માટે ઇ-મેઇલ મોકલવામાં આવશે નહીં.',
'noemailprefs'             => "આ વિશેષતાઓ કાર્ય કરી શકે તે માટે 'તમારી પસંદ'માં ઇ-મેઇલ સરનામું દર્શાવો.",
'emailconfirmlink'         => 'તમારા ઇ-મેઇલ સરનામાની પુષ્ટિ કરો',
'accountcreated'           => 'ખાતું ખોલવામાં આવ્યું છે',
'accountcreatedtext'       => '$1 માટે સભ્ય ખાતુ બનાવ્યું.',
'createaccount-title'      => '{{SITENAME}} માટે ખાતુ બનાવ્યું',
'createaccount-text'       => 'કોઇકે {{SITENAME}} ($4) પર, નામ "$2", ગુપ્તસંજ્ઞા "$3", શાથે તમારા ઇ-મેઇલ એડ્રેસ માટે ખાતુ બનાવેલ છે.

તમે હવે પ્રવેશ કરી અને ગુપ્તસંજ્ઞા બદલી શકો છો.

જો આ ખાતુ ભુલથી બનેલું હોય તો,આ સંદેશને અવગણી શકો છો.',
'usernamehasherror'        => 'સભ્યનામમાં ગડબડિયા ચિહ્નો ન હોઈ શકે',
'login-throttled'          => 'તમે હાલમાં જ ઘણા પ્રવેશ પ્રયત્નો કર્યા.
કૃપા કરી ફરી પ્રયાસ પહેલાં થોડી રાહ જુઓ.',
'loginlanguagelabel'       => 'ભાષા: $1',

# E-mail sending
'php-mail-error-unknown' => 'PHPની મેલ() કામગીરીમાં અજ્ઞાત ત્રુટિ',

# JavaScript password checks
'password-strength'            => 'અંદાજીત ગુપ્તસંજ્ઞા ક્ષમતા: $1',
'password-strength-bad'        => 'ખરાબ',
'password-strength-mediocre'   => 'મધ્યમ',
'password-strength-acceptable' => 'સ્વીકાર્ય',
'password-strength-good'       => 'સારી',
'password-retype'              => 'ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો',
'password-retype-mismatch'     => 'ગુપ્તસંજ્ઞાઓ મેળ ખાતી નથી',

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
'resetpass-no-info'         => 'બારોબાર આ પાનું જોવા માટે પ્રવેશ કરવો આવશ્યક છે.',
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
'missingsummary'                   => "'''ચેતવણી:''' તમે ફેરફારનો સારંશ નથી આપ્યો.
જો તમે \"{{int:savearticle}}\"  પર ક્લીક કરશો તો તમરો ફેરફારા સારાઁશાવગરાસાચવવામાં આવશે",
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
મહેરબાની કરી [[Special:Preferences|મારી પસંદ]]માં જઇને તમારું ઇમેલ સરનામું આપો અને તેને પ્રમાણિત કરો.',
'nosuchsectiontitle'               => 'આવો વિભાગ મળ્યો નથી',
'nosuchsectiontext'                => 'તમે અસ્તિત્વ ન ધરાવtaa વિભાગમાં ફેરફાર કરવાની કોશિશ કરી છે.
શક્ય છે કે જ્યારે તમે પાનું જોતા હતા ત્યારે તેને દૂર કરવામાં કે ખસેડવામાં આવ્યો હોય.',
'loginreqtitle'                    => 'પ્રવેશ (લોગ ઇન) જરૂરી',
'loginreqlink'                     => 'લોગીન',
'loginreqpagetext'                 => 'બીજા પાનાં જોવા માટે જરૂરી છે કે તમે $1.',
'accmailtitle'                     => 'ગુપ્તસંજ્ઞા મોકલવામાં આવી છે.',
'newarticle'                       => '(નવીન)',
'newarticletext'                   => "આપ જે કડીને અનુસરીને અહીં પહોંચ્યા છો તે પાનું અસ્તિત્વમાં નથી.
<br />નવું પાનું બનાવવા માટે નીચે આપેલા ખાનામાં લખવાનું શરૂ કરો (વધુ માહિતિ માટે [[{{MediaWiki:Helppage}}|મદદ]] જુઓ).
<br />જો આપ ભુલમાં અહીં આવી ગયા હોવ તો, આપનાં બ્રાઉઝર નાં '''બેક''' બટન પર ક્લિક કરીને પાછા વળો.",
'noarticletext'                    => 'આ પાનામાં હાલમાં કોઇ માહિતિ નથી.
તમે  [[Special:Search/{{PAGENAME}}|આ શબ્દ]] ધરાવતાં અન્ય લેખો શોધી શકો છો, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} સંલગ્ન માહિતિ પત્રકોમાં શોધી શકો છો],
અથવા  [{{fullurl:{{FULLPAGENAME}}|action=edit}} આ પાનામાં ફેરફાર કરી] માહિતિ ઉમેરવાનું શરૂ કરી શકો છો</span>.',
'userpage-userdoesnotexist-view'   => 'સભ્યના ખાતાની નોંધણી નથી થઈ',
'blocked-notice-logextract'        => 'આ સભ્ય હાલમાં પ્રતિબંધિત છે.
તમારા નીરીક્ષણ માટે તાજેતરમાં પ્રતિબંધિત થયેલા સભ્યોની યાદિ આપી છે.',
'usercsspreview'                   => "'''યાદ રહે કે તમે તમારા સભ્ય CSS નું અવલોકન કરો છે.'''
'''હજી સીધું તે સચવાયું નથી!'''",
'userjspreview'                    => "'''યાદ રહે કે તમે તમારા સભ્ય JavaScript નું અવલોકન કરો છે.'''
'''હજી સીધું તે સચવાયું નથી!'''",
'sitecsspreview'                   => "'''યાદ રહે કે તમે તમારા સભ્ય CSS નું અવલોકન કરો છે.'''
'''હજી સીધું તે સચવાયું નથી!'''",
'sitejspreview'                    => "'''યાદ રહે કે તમે તમારા સભ્ય JavaScript નું અવલોકન કરો છે.'''
'''હજી સીધું તે સચવાયું નથી!'''",
'userinvalidcssjstitle'            => "'''ચેતવણી:''' કોઇ પણ \"\$1\" પટલ નથી.
સભ્ય રચિત .css અને  .js પાના બીજી અંગ્રેજી બારખડી શીર્ષક વાપરે છે, દા. ત. {{ns:user}}:Foo/vector.css નહીં કે {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(સંવર્ધીત)',
'note'                             => "'''નોંધ:'''",
'previewnote'                      => "'''આ ફક્ત પૂર્વાવલોકન છે;'''
ફેરફારો હજુ સાચવવામાં નથી આવ્યા!",
'previewconflict'                  => 'જો તમે આ પાનું સાચવશો તો આ પ્રિવ્યુમાં દેખાય છે તેવું સચવાશે.',
'editing'                          => '$1નો ફેરફાર કરી રહ્યા છે',
'editingsection'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'editingcomment'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'editconflict'                     => 'ફેરફારમાં વિસંગતતા: $1',
'yourtext'                         => 'તમારું લખાણ',
'storedversion'                    => 'રક્ષિત પુનરાવર્તન',
'yourdiff'                         => 'ભેદ',
'copyrightwarning'                 => "મહેરબાની કરીને એ વાતની નોંધ લેશો કે {{SITENAME}}માં કરેલું બધુંજ યોગદાન $2 હેઠળ પ્રકાશિત કરએલું માનવામાં આવે છે (વધુ માહિતિ માટે $1 જુઓ).
<br />જો આપ ના ચાહતા હોવ કે તમારા યોગદાનમાં અન્ય કોઇ વ્યક્તિ બેધડક પણે ફેરફાર કરે અને તેને પુનઃપ્રકાશિત કરે, તો અહીં યોગદાન કરશો નહી.
<br />સાથે સાથે તમે અમને એમ પણ ખાતરી આપી રહ્યા છો કે આ લખાણ તમે મૌલિક રીતે લખ્યું છે, અથવાતો પબ્લિક ડોમેઇન કે તેવા અન્ય મુક્ત સ્ત્રોતમાંથી લીધું છે.
<br />'''પરવાનગી વગર પ્રકાશનાધિકાર થી સુરક્ષિત (COPYRIGHTED) કાર્ય અહીં પ્રકાશિત ના કરશો!'''",
'templatesused'                    => 'આ પાનામાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'templatesusedpreview'             => 'આ પૂર્વાવલોકનમાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'template-protected'               => '(સુરક્ષિત)',
'template-semiprotected'           => '(અર્ધ સુરક્ષિત)',
'hiddencategories'                 => 'આ પાનું {{PLURAL:$1|૧ છુપી શ્રેણી|$1 છુપી શ્રેણીઓ}}નું સભ્ય છે:',
'nocreatetitle'                    => 'પાનું બનાવવૌં મર્યાદિત છે',
'nocreatetext'                     => '{{SITENAME}}માં નવું પાનુ બનાવવા ઉપર નિયંત્રણ આવી ગયું છે.
<br />આપ પાછા જઇને હયાત પાનામાં ફેરફાર કરી શકો છો, નહિતર [[Special:UserLogin|પ્રવેશ કરો કે નવું ખાતું ખોલો]].',
'nocreate-loggedin'                => 'તમને નવા પાના રચવાની પરવાનગી નથી.',
'sectioneditnotsupported-title'    => 'ખંડીય સંપાદન શક્ય નથી',
'sectioneditnotsupported-text'     => 'આ પાના પર ખંડીય સંપાદન શક્ય નથી',
'permissionserrors'                => 'પરવાનગીની ખામી',
'permissionserrorstext-withaction' => '$2 પરવાનગી તમને નીચેનાં {{PLURAL:$1|કારણ|કારણો}} સર નથી:',
'recreate-moveddeleted-warn'       => "'''ચેતવણી: તમે જે પાનું નવું બનાવવા જઇ રહ્યાં છો તે પહેલાં દૂર કરવામાં આવ્યું છે.'''

આ પાનું સંપાદિત કરતા પહેલાં ગંભીરતાપૂર્વક વિચારજો અને જો તમને લાગે કે આ પાનું ફરી વાર બનાવવું ઉચિત છે, તો જ અહીં ફેરફાર કરજો.
પાનું હટાવ્યાં પહેલાનાં બધા ફેરફારોની સૂચિ તમારી અનુકૂળતા માટે અહીં આપી છે:",
'log-fulllog'                      => 'પૂર્ણ લોગ જુઓ',
'edit-hook-aborted'                => 'ખૂંટા દ્વારા રદ્દ કરાયું.
કોઇ કારણ નથી અપાયું',
'edit-gone-missing'                => 'આ પાને અધ્યતન ન બનાવી શકાયું 
લાગે છે કોઇએ આ પાનું હટાવી દીધું છે',
'edit-conflict'                    => 'સંપાદન સંઘર્ષ.',
'edit-no-change'                   => 'તમારા ફેરફારો અવગણાયા, કેમકે અક્ષરકાયામાં કોઈ ફેરફારાના હતો',
'edit-already-exists'              => 'નવું પાનું બનાવી ન શકાયું
તે પહેલેથી હાજર છે.',

# Parser/template warnings
'expensive-parserfunction-category'       => 'ઘણા પદચ્છેદ સૂત્ર ધરાવતું પાનું',
'post-expand-template-inclusion-category' => 'માહિતી ચોકઠું ધરાવતા પાનાનું કદ વધુ છે',
'post-expand-template-argument-category'  => 'ઢાંચાના વિકલ્પો અધૂરા રહી ગયેલ હોય તેવા પાનાની યાદિ',
'parser-template-loop-warning'            => 'આવર્તી ઢાંચો મળ્યો : [[$1]]',
'language-converter-depth-warning'        => 'ભાષા રૂપાંતરણ ઊંડાઈ સીમા વટાવાઈ ($1)',

# "Undo" feature
'undo-failure' => 'વચ્ચે થયેલા અન્ય ફેરફાર થવાને કારણે આ ફેરફારો ઉલટાવી ન શકાયા',
'undo-norev'   => 'ફેરફાર સાચવે ન શકાયો કેમકે યા તો તે અસ્તિત્વમાં નથી અહ્તવાતો ભૂંસી નખાયા છે.',

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
'histlegend'             => "વિવિધ પસંદગી:સરખામણી માટે સુધારેલી આવૃતિઓના રેડિયો ખાનાઓ પસંદ કરો અને એન્ટર મારો અથવા નીચે આવેલું બટન દબાવો.<br />
સમજૂતી:'''({{int:cur}})''' = વર્તમાન અને સુધારેલી આવૃતિનો તફાવત, '''({{int:last}})''' = પૂર્વવર્તી ફેરફારનો તફાવત, '''{{int:minoreditletter}}''' = નાનો ફેરફાર.",
'history-fieldset-title' => 'ઇતિહાસ ઉખેળો',
'history-show-deleted'   => 'માત્ર હટાવાયેલા',
'histfirst'              => 'સૌથી જુનું',
'histlast'               => 'સૌથી નવું',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(ખાલી)',

# Revision feed
'history-feed-title'          => 'પુનરાવર્તન ઇતિહાસ',
'history-feed-description'    => 'વિકિમાં આ પાનાનાં પુનરાવર્તનનો ઇતિહાસ',
'history-feed-item-nocomment' => '$1, $2 સમયે',
'history-feed-empty'          => 'આ પાનું અસ્તિત્વમાં નથી.
શક્ય છે કે આ પાનું વિકિમાંથી દૂર કરવામાં આવ્યું હોય કે તેનું નામ બદલવામાં આવ્યું હોય.
સંલગ્ન નવા પાનાઓ માટે [[Special:Search|વિકિમાં શોધી જુઓ]].',

# Revision deletion
'rev-deleted-comment'         => '(ટિપ્પણી હટવાઈ)',
'rev-deleted-user'            => '(સભ્યનામ હટાવાયું)',
'rev-deleted-event'           => '(લોગ ક્રિયા હટાવાઈ)',
'rev-deleted-text-permission' => 'આ પુનરાવર્તન હટાવી દેવાયું છે
આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-suppressed-no-diff'      => "તમને ફરક નહીં દેખાય કેમકે કોઈ એક પુનરાવર્તન '''હટાવાયું છે'''.",
'rev-delundel'                => 'બતાવો/છુપાવો',
'rev-showdeleted'             => 'બતાવો',
'revisiondelete'              => 'પુનરાવર્તન રદ્દ કરો/પુનર્જીવીત કરો',
'revdelete-nooldid-title'     => 'અવૈધ લક્ષ્ય ફેરફાર',
'revdelete-nologtype-title'   => 'આવો કોઈ લોગા નથી ફરી પ્રયત્ન કરો',
'revdelete-nologid-title'     => 'લોગ પ્રવેશ અવૈદ્ય',
'revdelete-no-file'           => 'વર્ણવેલી ફાઈલ અસ્તિત્વમાં નથી',
'revdelete-show-file-submit'  => 'હા',
'revdelete-suppress-text'     => "બળ પૂર્વક છુપાવવું માત્ર આજ સંજોગોમાં કરી શકાશે:
* સંભવતઃ ભયાજનક માહિતી 
* અયોગ્ય નિજી માહિતી 
*: ''ઘરનું સરનામું અને ટેલિફોન નંબર, સામાજિક સુરક્ષા ક્ર્મ ઈ.''",
'revdelete-legend'            => 'દ્રશ્યતા સંબંધી પ્રતિબંધોને ગોઠવો',
'revdelete-hide-text'         => 'પુનરાવર્તન છુપાવો',
'revdelete-hide-image'        => 'ફાઇલની માહિતી છુપાવો',
'revdelete-hide-name'         => 'ક્રિયા અને લક્ષ્ય સંતાડો',
'revdelete-hide-comment'      => 'ફેરફાર સારાંશ છુપાવો',
'revdelete-hide-user'         => 'સંપાદકનું નામ /આઈ પી એડ્રેસ સંતાડો',
'revdelete-hide-restricted'   => 'પ્રબંધક કે અન્યો સૌની માહિતી છુપાવો',
'revdelete-radio-same'        => '(બદલશો નહીઁ)',
'revdelete-radio-set'         => 'હા',
'revdelete-radio-unset'       => 'ના',
'revdelete-suppress'          => 'પ્રબંધક કે અન્યો સૌની માહિતી છુપાવો',
'revdelete-unsuppress'        => 'સમા કરાયેલા પુનરાવર્તન પરનાપ્રતિબંધ હટાવો',
'revdelete-log'               => 'કારણ:',
'revdelete-submit'            => 'પસંદ કરેલા {{PLURAL:$1|ફેરફાર|ફેરફારો}} પર લગાડો',
'revdelete-logentry'          => '"[[$1]]" ના પુનરાવર્તનની બદયેલી દ્રશ્યતા',
'logdelete-logentry'          => '"[[$1]]"ની બદલાયેલી ઘટના દ્રશ્યતા',
'revdelete-success'           => 'પુનરવર્તન દ્રશ્યતાસફળતા પૂર્વક અદ્યતન બનાવાઈ',
'revdelete-failure'           => "'''પુનરાવર્તનની દ્રશ્યતા બદલીન શકાઈ:'''
$1",
'logdelete-success'           => "'''લોગની દ્રશ્યતા સફળતાપૂર્વક ગોઠવાઈ'''",
'logdelete-failure'           => "'''લોગની દ્રશ્યતા ગોઠવી ન શકાઈ :'''
$1",
'revdel-restore'              => 'દૃષ્ટિક્ષમતા બદલો',
'revdel-restore-deleted'      => 'હટાવેલા પુનરાવર્તનો',
'revdel-restore-visible'      => 'દ્રશ્ય પુનરાવર્તનો',
'pagehist'                    => 'પાનાનો ઇતિહાસ',
'deletedhist'                 => 'રદ કરેલનો ઇતિહાસ',
'revdelete-content'           => 'સામગ્રી',
'revdelete-summary'           => 'સંપાદનનો સંક્ષિપ્ત અહેવાલ',
'revdelete-uname'             => 'સભ્યનામ',
'revdelete-restricted'        => 'પ્રબઁધકોના ફેરફાર કરવા પર પ્રતિબંધ મુકાયો',
'revdelete-unrestricted'      => 'પ્રબંધકોના ફેરફાર કરવા પર પ્રતિબંધ હટાવાયો.',
'revdelete-hid'               => '$1 છુપાવો',
'revdelete-unhid'             => '$1 દર્શાવો',
'revdelete-log-message'       => '$1 માટે $2 {{PLURAL:$2|ફેરફાર|ફેરફારો }} દર્શાવો',
'revdelete-no-change'         => "'''ચેતવણી:'''  $2, $1 તારીખ ધરાવતી વસ્તુ માં માંગેલ દ્રશ્ય વિકલ્પો પહેલેથી છે",
'revdelete-reason-dropdown'   => '* હટાવવાના સામાન્ય કારણો 
** પ્રકાશન ભંગ 
** અયોગ્ય નિજી માહિતી 
** સંભવતઃ  ભયજનક  માહિતી',
'revdelete-otherreason'       => 'અન્ય/વધારાનું કારણ:',
'revdelete-reasonotherlist'   => 'અન્ય કારણ',
'revdelete-edit-reasonlist'   => 'ભુંસવાનું કારણ બદલો.',
'revdelete-offender'          => 'પુનરાવર્તનના મૂળ લેખક',

# Suppression log
'suppressionlog' => 'દાબ નોંધ',

# Revision move
'revisionmove'                 => '"$1" થી ફેરફાર હટાવો',
'revmove-legend'               => 'લક્ષ્ય પાનું અને સરાંશ પુન;સંયોજીત કરો.',
'revmove-submit'               => 'પુનરાવર્તનો પસંદ કરેલા પાના પર હટાવો',
'revisionmoveselectedversions' => 'પસંદ કરેલા ફેરફારો ખસેડો',
'revmove-reasonfield'          => 'કારણ:',
'revmove-titlefield'           => 'લક્ષ્ય પાનું',
'revmove-badparam-title'       => 'ખરાબ વિકલ્પો',
'revmove-norevisions-title'    => 'અવૈધ લક્ષ્ય ફેરફાર',
'revmove-nullmove-title'       => 'ખરાબ નામ',

# History merging
'mergehistory'                     => 'પાનાનાં ઇતિહાસોનું વિલીનીકરણ',
'mergehistory-box'                 => 'બે પાનાના ફેરફાર વિલિન કરો',
'mergehistory-from'                => 'સ્ત્રોત પાનું',
'mergehistory-into'                => 'લક્ષ્ય પાનું',
'mergehistory-list'                => 'વિલિનીકરણશીલ ફેરફારનો ઈતિહાસ',
'mergehistory-go'                  => 'વિલિનીકરણશીલ ફેરફારો બતવો',
'mergehistory-submit'              => 'ફેરફારો વિલિન કરો',
'mergehistory-empty'               => 'પુનરાવર્તન સાચવી ન શકાયા',
'mergehistory-fail'                => 'ઇતિહાસ પાના વિલિન ન કરી શકાયા, પાના અને સમય સંબંધી વિકલ્પો ચકાસો.',
'mergehistory-no-source'           => 'સ્ત્રોત પાનું $1 ઉપલબ્ધ નથી.',
'mergehistory-no-destination'      => 'લક્ષ્ય પાનું $1 અસ્તિત્વમાં નથી',
'mergehistory-invalid-source'      => 'સ્ત્રોત પાનું વૈધ શીર્ષક હોવું જ જોઈએ',
'mergehistory-invalid-destination' => 'લક્ષ્ય પાનું એક  વૈધ શીર્ષક હોવું જોઇએ',
'mergehistory-autocomment'         => ' [[:$1]] ને [[:$2]] માં વિલિન કર્યું',
'mergehistory-comment'             => '[[:$1]] ને [[:$2]]: $3  માં વિલિન કર્યું',
'mergehistory-same-destination'    => 'સ્ત્રોત અને લક્ષ્ય પાના એકાસમાન ના હોઈ શકે',
'mergehistory-reason'              => 'કારણ:',

# Merge log
'mergelog'           => 'લોગ વિલિન કરો',
'pagemerge-logentry' => '[[$1]] ને  [[$2]]માં વિલિન કરાયું ( $3 સુધી ના પુનરાવર્તનો)',
'revertmerge'        => 'છુટુ પાડો',
'mergelogpagetext'   => 'તાજેતરમાં એક બીજા સાથે વિલિન થયેલ ઇતિહાસ પાનાની યાદી',

# Diffs
'history-title'            => '"$1"ના ફેરફારોનો ઇતિહાસ',
'difference'               => '(પુનરાવર્તનો વચ્ચેનો તફાવત)',
'difference-multipage'     => '(પાનાઓ વચ્ચેનો ફેરફાર)',
'lineno'                   => 'લીટી $1:',
'compareselectedversions'  => 'પસંદ કરેલા સરખાવો',
'showhideselectedversions' => 'પસંદ કરેલા બતાવો / સંતાડો',
'editundo'                 => 'રદ કરો',
'diff-multi'               => '{{PLURAL:$2|એક સભ્યએ કરેલું|$2 સભ્યોએ કરેલા}} ({{PLURAL:$1|વચગાળાનું એક પુનરાવર્તન દર્શાવ્યં|વચગાળાનાં $1 પુનરાવર્તનો દર્શાવ્યાં}} નથી.)',

# Search results
'searchresults'                    => 'પરિણામોમાં શોધો',
'searchresults-title'              => 'પરિણામોમાં "$1" શોધો',
'searchresulttext'                 => '{{SITENAME}}માં કેવી રીતે શોધવું તેની વધુ માહિતિ માટે જુઓ: [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'તમે \'\'\'[[:$1]]\'\'\' માટે શોધ્યુ  ([[Special:Prefixindex/$1|"$1"થી શરૂ થતા બધા પાના]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"ની સાથે જોડાયેલા બધા પાના]])',
'searchsubtitleinvalid'            => "તમે '''$1''' શોધ્યું",
'toomanymatches'                   => 'શોધમાં ઘણાં બધાં પરિણામો મળ્યાં, કૃપા કરી નવો શબ્દ મૂકી શોધો.',
'titlematches'                     => 'પાનાનું શીર્ષક મળતું આવે છે',
'notitlematches'                   => 'આ શબ્દ સાથે કોઇ શિર્ષક મળતું આવતું નથી',
'textmatches'                      => 'પાનાના શબ્દો મળતાં આવે છે',
'notextmatches'                    => 'આ શબ્દ કોઈ પાનામાં મળ્યો નથી',
'prevn'                            => 'પહેલાનાં {{PLURAL:$1|$1}}',
'nextn'                            => 'પછીનાં {{PLURAL:$1|$1}}',
'prevn-title'                      => 'ગત  $1 {{PLURAL:$1|પરિણામ|પરિણામો}}',
'nextn-title'                      => 'આગલા  $1 {{PLURAL:$1|પરિણામ|પરિણામો}}',
'shown-title'                      => 'પ્રતિ પાને $1 {{PLURAL:$1|પરિણામ|પરિણામો}} બતાવો',
'viewprevnext'                     => 'જુઓ: ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'શોધ વિકલ્પો',
'searchmenu-exists'                => "''' આ વિકિ પર  \"[[:\$1]]\" નામે પાનું પહેલેથી અસ્તિત્વમાં છે.'''",
'searchmenu-new'                   => "'''આ વિકિ પર \"[[:\$1]]\" નામે પાનું બનાવો!'''",
'searchmenu-new-nocreate'          => '"$1" એ એક અવૈધ પાના શીર્ષક છે અને તે નહીં બનાવી શકાય્',
'searchhelp-url'                   => 'Help:સૂચિ',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|આ પૂર્વાક્ષર વાળા પાનાં જુઓ]]',
'searchprofile-articles'           => 'લેખનું પાનું',
'searchprofile-project'            => 'મદદ અને યોજના પાનું',
'searchprofile-images'             => 'દ્રશ્ય શ્રાવ્ય માધ્યમ',
'searchprofile-everything'         => 'દરેકમાં શોધો',
'searchprofile-advanced'           => 'સુધારિત',
'searchprofile-articles-tooltip'   => '$1 માં શોધો',
'searchprofile-project-tooltip'    => '$1 માં શોધો',
'searchprofile-images-tooltip'     => 'ફાઇલ શોધો',
'searchprofile-everything-tooltip' => 'બધે જ શોધો (ચર્ચાનાં પાના સહિત)',
'searchprofile-advanced-tooltip'   => 'સ્થાનીય નામસ્થળોમાં શોધો:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 શબ્દ|$2 શબ્દો}})',
'search-result-category-size'      => '{{PLURAL:$1|1 સભ્ય|$1 સભ્યો}} ({{PLURAL:$2|1 ઉપ શ્રેણી|$2 ઉપ શ્રેણીઓ}}, {{PLURAL:$3|1 ફાઇલ|$3 ફાઇલો}})',
'search-result-score'              => 'પ્રસ્તુતિ: $1%',
'search-redirect'                  => '(અન્યત્ર પ્રસ્થાન $1)',
'search-section'                   => '(વિભાગ $1)',
'search-suggest'                   => 'શું તમે $1 કહેવા માંગો છો?',
'search-interwiki-caption'         => 'બંધુ પ્રકલ્પ',
'search-interwiki-default'         => '$1 પરીણામો:',
'search-interwiki-more'            => '(વધુ)',
'search-mwsuggest-enabled'         => 'સુઝાવ સહિત',
'search-mwsuggest-disabled'        => 'સુઝાવ વિના',
'search-relatedarticle'            => 'શોધ સંબંધિત',
'mwsuggest-disable'                => 'AJAX સુઝાવો નિષ્ક્રીય કરો',
'searcheverything-enable'          => 'નામસ્થળોમાં શોધો:',
'searchrelated'                    => 'શોધ સંબંધિત',
'searchall'                        => 'બધા',
'showingresults'                   => " {{PLURAL:$1|'''1''' પરિણામ|'''$1''' પરિણામો}} સુધી #'''$2''' થી શરૂ  કરી",
'nonefound'                        => "'''નોંધ''':ફક્ત અમુકજ નામસ્થળોમાં આપોઆપ શોધાશે.
તમારા શબ્દને ''બધા:'' ઉમેરી શોધવાનો પ્રયત્ન કરો, જેથી બધી માહિતિમાં (જેમકે ચર્ચાના પાના, ઢાંચા, વિગેરે)માં શોધ થઈ શકે, અથવાતો ઇચ્છિત નામસ્થળ પસંદ કરી શોધો બટન દબાવો.",
'powersearch'                      => 'શોધો (વધુ પર્યાય સાથે)',
'powersearch-legend'               => 'વધુ પર્યાયો સાથે શોધો',
'powersearch-ns'                   => 'નામસ્થળોમાં શોધો:',
'powersearch-redir'                => 'અન્યત્ર વાળેલાં પાનાંની યાદી',
'powersearch-field'                => 'નાં માટે શોધો',
'powersearch-togglelabel'          => ' ચકાસો:',
'powersearch-toggleall'            => 'બધા',
'powersearch-togglenone'           => 'કાંઇ નહી',
'search-external'                  => 'બાહ્ય શોધ',
'searchdisabled'                   => "{{SITENAME}} ઉપર શોધ બંધ કરી દેવામાં આવી છે.
ત્યાં સુધી તમે ગુગલ દ્વારા શોધ કરી શકો.
'''નોંધઃ'''{{SITENAME}}નાં તેમના (ગુગલના) ઈન્ડેક્સ જુના હોઇ શકે.",

# Quickbar
'qbsettings'               => 'શીઘ્રપટ્ટ',
'qbsettings-none'          => 'કોઇપણ નહીં',
'qbsettings-fixedleft'     => 'અચળ ડાબે',
'qbsettings-fixedright'    => 'અચળ જમણે',
'qbsettings-floatingleft'  => 'ચલિત ડાબે',
'qbsettings-floatingright' => 'ચલિત જમણે',

# Preferences page
'preferences'                   => 'પસંદ',
'mypreferences'                 => 'મારી પસંદ',
'prefs-edits'                   => 'સંપાદનોની સંખ્યા',
'prefsnologin'                  => 'પ્રવેશ કરેલ નથી',
'changepassword'                => 'ગુપ્તસંજ્ઞા બદલો',
'prefs-skin'                    => 'ફલક',
'skin-preview'                  => 'ફેરફાર બતાવો',
'prefs-math'                    => 'ગણિત',
'datedefault'                   => 'મારી પસંદ',
'prefs-datetime'                => 'તારીખ અને સમય',
'prefs-personal'                => 'સભ્ય ઓળખ',
'prefs-rc'                      => 'તાજા ફેરફારો',
'prefs-watchlist'               => 'ધ્યાનસૂચિ',
'prefs-watchlist-days'          => 'ધ્યાનસૂચિમાઁ દર્શાવવના દિવસો',
'prefs-watchlist-days-max'      => 'મહત્તમ ૭ દિવસો',
'prefs-watchlist-edits'         => 'વિસ્તરીત ધ્યાનસૂચિ માં બતાવનારા ફેરફારોની સંખ્યા',
'prefs-watchlist-edits-max'     => 'મહત્તમ સંખ્યા : ૧૦૦૦',
'prefs-watchlist-token'         => 'ધ્યાનસૂચિ ચિઠ્ઠી',
'prefs-misc'                    => 'પરચૂરણ',
'prefs-resetpass'               => 'ગુપ્તસંજ્ઞા બદલો',
'prefs-email'                   => 'ઈ-મેલ સંબંધી વિકલ્પો',
'prefs-rendering'               => 'દેખાવ',
'saveprefs'                     => 'સાચવો',
'resetprefs'                    => 'બીન સાચવેલ ફેરફારો સાફ કરો',
'restoreprefs'                  => 'મૂળ વિકલ્પો ફરી ગોઠવો',
'prefs-editing'                 => 'ફેરફાર જારી છે',
'prefs-edit-boxsize'            => 'ફેરફાર ફલકનું માપ',
'rows'                          => 'પંક્તિઓ',
'columns'                       => 'સ્તંભ',
'searchresultshead'             => 'શોધો',
'resultsperpage'                => 'પ્રતિ પાના પર પરિણામો',
'contextlines'                  => 'પ્રતિ પરિણામ માં હરોળ',
'contextchars'                  => 'સંદર્ભ પ્રતિ હરોળ',
'stub-threshold-disabled'       => 'નિષ્ક્રીયાન્વીત',
'recentchangesdays'             => 'તાજા ફેરફારોમાં દેખાડવાના દિવસો',
'recentchangescount'            => 'સમાન્ય પણે ફલકમાં બતાવવાના ફેરફારોની સંખ્યા',
'prefs-help-recentchangescount' => 'આમાં તાજા ફેરફારો,  ઇતિહાસ અને લોગ શામિલ છે.',
'savedprefs'                    => 'તમારી પસંદગી સાચવી નથી શકાઇ',
'timezonelegend'                => 'સમય ક્ષેત્ર:',
'localtime'                     => 'સ્થાનીક સમય:',
'timezoneuseserverdefault'      => 'સર્વરના મૂળ વિકલ્પો ગોઠવો',
'timezoneuseoffset'             => 'અન્ય ( સમય ખંડ બતાવો)',
'timezoneoffset'                => 'સમય ખંડ',
'servertime'                    => 'સર્વર સમય:',
'guesstimezone'                 => 'બ્રાઉઝરમાંથી દાખલ કરો',
'timezoneregion-africa'         => 'આફ્રિકા',
'timezoneregion-america'        => 'અમેરિકા',
'timezoneregion-antarctica'     => 'એન્ટાર્કટિકા',
'timezoneregion-arctic'         => 'આર્કટિક',
'timezoneregion-asia'           => 'એશિયા',
'timezoneregion-atlantic'       => 'એટલાંટિક મહાસાગર',
'timezoneregion-australia'      => 'ઔસ્ટ્રેલિયા',
'timezoneregion-europe'         => 'યુરોપ',
'timezoneregion-indian'         => 'હિંદ મહાસાગર',
'timezoneregion-pacific'        => 'પ્રશાંત મહાસાગર',
'allowemail'                    => 'અન્ય સભ્યો તરફથી આવતા ઇ-મેલને પરવાનગી આપો',
'prefs-searchoptions'           => 'શોધ વિકલ્પો',
'prefs-namespaces'              => 'નામ અવકાશો',
'defaultns'                     => 'અન્યથા આ નામ અવકાશ માં શોધો',
'default'                       => 'મૂળ વિકલ્પ',
'prefs-files'                   => 'ફાઇલ',
'prefs-custom-css'              => 'ખાસ  CSS',
'prefs-custom-js'               => 'સભ્ય નિર્મિત JavaScript',
'prefs-common-css-js'           => 'બધા જ ફલક માટે સહીયારી CSS/JavaScript',
'prefs-emailconfirm-label'      => 'ઇ-મેલ પુષ્ટી',
'prefs-textboxsize'             => 'ફેરફાર ફલકનું માપ',
'youremail'                     => 'ઇ-મેઇલ:',
'username'                      => 'સભ્ય નામ:',
'uid'                           => 'સભ્ય નામ',
'prefs-memberingroups'          => '{{PLURAL:$1|સમુહ|સમુહો}}ના સભ્ય:',
'prefs-registration'            => 'નોંધણી સમય',
'yourrealname'                  => 'સાચું નામ:',
'yourlanguage'                  => 'ભાષા',
'yournick'                      => 'સહી:',
'badsig'                        => 'અવૈધ કાચી સહી
HTML નાકું ચકાસો',
'badsiglength'                  => 'તમારી સહી વધુ લાંબી છે.
તે $1 {{PLURAL:$1|અક્ષર|અક્ષરો}} કરતા વધુ લાંબી ન હોવી જોઇએ.',
'yourgender'                    => 'જાતિ:',
'gender-unknown'                => 'અનિર્દિષ્ટ',
'gender-male'                   => 'પુરુષ',
'gender-female'                 => 'સ્ત્રી',
'email'                         => 'ઇ-મેઇલ',
'prefs-help-realname'           => 'સાચું નામ મરજીયાત છે.
જો આપ સાચું નામ આપવાનું પસંદ કરશો, તો તેનો ઉપયોગ તમારા કરેલાં યોગદાનનું શ્રેય આપવા માટે થશે.',
'prefs-help-email'              => 'ઇમેલ સરનામુ વૈકલ્પિક છે, પરંતુ જો તમે ક્યારેક તમારી ગુપ્તસંજ્ઞા ભૂલી જાવ તો નવી ગુપ્તસંજ્ઞા મેળવવા માટે તે જરૂરી છે.',
'prefs-help-email-others'       => 'તમે તમારી ઓળખ છતી કર્યા સિવાય તમે અન્ય સભ્યો તમારો સંપર્ક તમારી ચર્ચાના પાના પર કરી શકો છો.',
'prefs-help-email-required'     => 'ઇ-મેઇલ સરનામુ જરૂરી.',
'prefs-info'                    => 'મૂળ માહિતી',
'prefs-i18n'                    => 'વૈશ્વીકરણ',
'prefs-signature'               => 'હસ્તાક્ષર',
'prefs-dateformat'              => 'તારીખ લખવાની શૈલિ',
'prefs-timeoffset'              => 'સમય ખંડ',
'prefs-advancedediting'         => 'અદ્યતન વિકલ્પો',
'prefs-advancedrc'              => 'અદ્યતન વિકલ્પો',
'prefs-advancedrendering'       => 'અદ્યતન વિકલ્પો',
'prefs-advancedsearchoptions'   => 'અદ્યતન વિકલ્પો',
'prefs-advancedwatchlist'       => 'અદ્યતન વિકલ્પો',
'prefs-displayrc'               => ' પ્રદર્શન વિકલ્પો',
'prefs-displaysearchoptions'    => ' પ્રદર્શન વિકલ્પો',
'prefs-displaywatchlist'        => ' પ્રદર્શન વિકલ્પો',
'prefs-diffs'                   => 'ફરક',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'ઈ-મેલ યોગ્ય લાગે છે.',
'email-address-validity-invalid' => 'પ્રમાણભૂત શૈલિમાં ઈ-મેલ એડ્રેસ લખો',

# User rights
'userrights'                   => 'સભ્ય હક્ક પ્રબંધન',
'userrights-lookup-user'       => 'સભ્ય સમુહો નું પ્રબંધન કરો',
'userrights-user-editname'     => 'સભ્યનામ દાખલ કરો:',
'editusergroup'                => 'સભ્ય સમુહો સંપાદીત કરો',
'userrights-editusergroup'     => 'સભ્ય સમુહો સંપાદીત કરો',
'saveusergroups'               => 'સભ્ય સમુહો સાચવો',
'userrights-groupsmember'      => 'સભ્યપદ:',
'userrights-groupsmember-auto' => 'નો અભિપ્રેત સભ્ય:',
'userrights-reason'            => 'કારણ:',
'userrights-no-interwiki'      => 'અન્ય વિકિ પર અન્ય સભ્યો ના અધિકારો માં પરિવર્તન કરવાની તમને પરવાનગી નથી',
'userrights-nodatabase'        => 'માહિતીસંચ $1 અસ્તિત્વમાં નથી કે તે સ્થાનીય નથી.',
'userrights-notallowed'        => 'તમારા ખાતને અન્ય સભ્યોને હક્કો પ્રદાન કરવાનો અધિકાર નથી',
'userrights-changeable-col'    => 'તમે બદલી શકો તેવા જૂથ',
'userrights-unchangeable-col'  => 'તમે બદલી ન શકો તેવા જૂથ',

# Groups
'group'               => 'સમુહ',
'group-user'          => 'સભ્ય',
'group-autoconfirmed' => 'સ્વયં ચલિત પરવાનગી મેળવેલ સભ્યો',
'group-bot'           => 'બૉટો',
'group-sysop'         => 'સાઇસૉપ/પ્રબંધકો',
'group-bureaucrat'    => 'રાજનૈતિકો',
'group-suppress'      => 'દુર્લક્ષ',
'group-all'           => '(બધા)',

'group-user-member'          => 'સભ્ય',
'group-autoconfirmed-member' => 'સ્વયં ચલિત પરવાનગી મેળવેલ સભ્ય',
'group-bot-member'           => 'બૉટ',
'group-sysop-member'         => 'સાઇસૉપ/પ્રબંધક',
'group-bureaucrat-member'    => 'રાજનૈતિક',
'group-suppress-member'      => 'દુર્લક્ષ',

'grouppage-user'          => '{{ns:project}}:સભ્યો',
'grouppage-autoconfirmed' => '{{ns:project}}:સ્વ્યંચલિત બહાલ  સભ્યો',
'grouppage-bot'           => '{{ns:project}}:બૉટો',
'grouppage-sysop'         => '{{ns:project}}:પ્રબંધકો',
'grouppage-bureaucrat'    => '{{ns:project}}: રાજનૈતિક',
'grouppage-suppress'      => '{{ns:project}}:દુર્લક્ષ',

# Rights
'right-read'                  => ' પાના વાંચો',
'right-edit'                  => 'પાના બદલો',
'right-createpage'            => 'પાના બનાવો ( જે ચર્ચા પાના ન હોય)',
'right-createtalk'            => 'ચર્ચાનું પાનું બનાવો',
'right-createaccount'         => 'નવા સભ્ય ખાતા ખોલો',
'right-minoredit'             => ' નાનો ફેરફાર તરીકે નોઁધો',
'right-move'                  => 'પાનું ખસેડો',
'right-move-subpages'         => 'પાનાઓને તેમના ઉપ પાના સાથે ખસેડો.',
'right-move-rootuserpages'    => 'મૂળ સભ્ય પાના હટાવો',
'right-movefile'              => 'ફાઈલો હટાવો',
'right-upload'                => 'ફાઇલ ચડાવો',
'right-reupload'              => 'વિહરમાન ફાઇલ પર પુનર્લેખન કરો',
'right-reupload-own'          => 'સભ્ય દ્વારા જાતે ચઢાવેલી તાઇલ પર પુનર્લેખન કરો',
'right-upload_by_url'         => 'URL પરથી ફાઇલ ચઢાવો',
'right-autoconfirmed'         => 'અર્ધ સંરક્ષિત પાના સંપાદિત કરો',
'right-bot'                   => 'આને સ્વયં ચાલિત પ્રિયા ગણો',
'right-apihighlimits'         => 'પૂછતાછમાં  APની  ચઢિયાતી સીમા વાપરો',
'right-writeapi'              => 'લેખન API વાપરો',
'right-delete'                => 'પાનું હટાવો',
'right-bigdelete'             => 'લાંબા ઇતિહાસ વાળા પાના હટાવો',
'right-deleterevision'        => 'પાના પરના અમુક ખાસ સંપાદનો હટાવો કે પુનર્જીવીત કરો',
'right-deletedhistory'        => 'હટાવેલા ઇતિહાસ પ્રવેશ નોંધ જુઓ. સંલગ્ન અક્ષરદેહ વિના.',
'right-deletedtext'           => 'રદ્દ કરાયેલ લેખ અને રદ્દીકરણ વચ્ચેના ફેરફારો વાંચો',
'right-browsearchive'         => 'હટાવેલા પાનાની શોધ',
'right-undelete'              => 'હટાવેલ પાનું પુનર્જીવીત કરો',
'right-suppressionlog'        => 'નિજી લોગ જુઓ',
'right-block'                 => 'આ સભ્ય દ્વારા થનાર ફેરફાર પ્રતિબંધીત કરો',
'right-blockemail'            => 'સભ્યના ઇ-મેલ મોકલવા પર પ્રતિબંધ મૂકો',
'right-hideuser'              => 'સભ્યનામ પર પ્રતિબંધ મૂકો જેથી તે લોકોને ન દેખાય.',
'right-proxyunbannable'       => 'અવેજીના અવયંચાલિત ખંડોને ટાળો',
'right-unblockself'           => 'તેમને જાતે અપ્રતિબંધિત થવા દો',
'right-protect'               => 'સંરક્ષણ સ્તર બદલો અને સંરક્ષીત પાના માં ફેરફાર કરો.',
'right-editprotected'         => 'સંરક્ષીત પાના માં ફેરફાર કરો (પગથિયામય સુરક્ષાના વિકલ્પ વગર)',
'right-editinterface'         => 'સભ્ય સંભાષણ પટલમાં ફેરફાર કરો',
'right-editusercssjs'         => 'અન્ય સભ્યની  CSS અને JavaScript ફાઇલ માં ફેરફાર કરો',
'right-editusercss'           => 'અન્ય સભ્યની CSS માં સંપાદિત કરો',
'right-edituserjs'            => 'અન્ય સભ્યની  JavaScript ફાઇલ માં ફેરફાર કરો',
'right-rollback'              => 'છેલ્લા સભ્ય દ્વારા કરેલા સંપાદનો ઝડપથી ઉલટાવો',
'right-markbotedits'          => 'ઉલટાવનારા અને બોટ ફેરફારો નોંધો',
'right-import'                => 'અન્ય વિકિમાંથી પાના આયાત કરો',
'right-importupload'          => 'ચઢાવેલી ફાઇલ પરથી આ પાનું આયાત કરો.',
'right-patrol'                => 'અન્યો ના ફેરફારો નીરીક્ષીત અંકિત કરો',
'right-patrolmarks'           => 'તાજા ફેરફારો અને ચોકીયાત નોંધ જુઓ',
'right-unwatchedpages'        => 'ન જોવાતા પાનાની યાદી જુઓ',
'right-trackback'             => 'ટ્રેકબેક જમા કરો',
'right-mergehistory'          => 'પાનાનો ઇતિહાસ વિલિન કરો',
'right-userrights'            => 'બધા સભ્યોના હક્કોમાં ફેરફાર કરો',
'right-userrights-interwiki'  => 'અન્ય વિકિ પરના સભ્યોના હક્કો સંપાદિત કરો.',
'right-siteadmin'             => 'માહિતી સંચયને ઉઘાડો અને વાસો.',
'right-reset-passwords'       => 'અન્ય સભ્યોની ગુપ્ત સંજ્ઞાઓ ફરી ગોઠવો',
'right-override-export-depth' => '૫ સ્તર સુધી જોડાયેલ પાના સહીત પાના નિકાસ કરો',
'right-sendemail'             => ' અન્ય સભ્યોને ઈ-મેલ મોકલો',
'right-revisionmove'          => 'પુનરાવર્તનો ખસેડો',
'right-disableaccount'        => 'ખાતું નિષ્ક્રીય બનાવો',

# User rights log
'rightslog'     => 'સભ્ય હક્ક માહિતિ પત્રક',
'rightslogtext' => 'સભ્યના બદલાયેલ હક્કોની આ સંપાદન યાદિ છે .',
'rightsnone'    => '(કોઈ નહિ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'આ પાનું વાંચો.',
'action-edit'                 => 'આ પાનામાં ફેરફાર કરવાની',
'action-createpage'           => 'નવો લેખ શરૂ કરો',
'action-createtalk'           => 'ચર્ચાનું પાનું બનાવો',
'action-createaccount'        => ' ખાતું ખોલો',
'action-minoredit'            => ' આ ફેરફારને એક નાના સુધારા તરીકે નોંધો',
'action-move'                 => 'આ પાનું ખસેડો',
'action-move-subpages'        => 'આ પાનું અને તેના ઉપપાના ખસેડો',
'action-move-rootuserpages'   => 'મૂળ સભ્ય પાના હટાવો',
'action-movefile'             => 'આ ફાઈલા ખસેડો',
'action-upload'               => 'આ ફાઈલ ચઢવો',
'action-reupload'             => 'વિહરમાન ફાઇલ પર પુનર્લેખન કરો',
'action-upload_by_url'        => 'URL પરથી આ ફાઇલ ચઢાવો',
'action-writeapi'             => 'લેખન API વાપરો',
'action-delete'               => 'આ પાનું હટાવો',
'action-deleterevision'       => 'આ પુનરાવર્તનારદ્દ કરો',
'action-deletedhistory'       => 'આ પાનાના રદ્દીકરણનો ઇતિહાસ બતાવો',
'action-browsearchive'        => 'હટાવેલા પાનાની શોધ',
'action-undelete'             => 'આ પાનું ફરી પુનર્જીવીત કરો',
'action-suppressrevision'     => 'સમીક્ષા કરી આ ગુપ્ત પુનરાવર્તન પુન-સ્થાપિત કરો',
'action-suppressionlog'       => 'આ નિજી લોગ જુઓ',
'action-block'                => 'આ સભ્ય દ્વારા થનાર ફેરફાર પ્રતિબંધીત કરો',
'action-protect'              => 'આ પાનાંનું પ્રતિબંધ સ્તર બદલો',
'action-import'               => 'અન્ય વિકિ પરથી આ પાનું આયાત કરો',
'action-importupload'         => 'ચઢાવેલી ફાઇલ પરથી આ પાનું આયાત કરો.',
'action-patrol'               => 'અન્યો ના ફેરફારો નીરીક્ષીત અંકિત કરો',
'action-autopatrol'           => 'તમે તમારા ફેરફારો નીરીક્ષિત અંકિત કરો',
'action-unwatchedpages'       => 'ન જોવાતા પાનાની યાદી જુઓ',
'action-trackback'            => 'ટ્રેકબેક જમા કરો',
'action-mergehistory'         => 'પાનાનો ઇતિહાસ વિલિન કરો',
'action-userrights'           => 'બધા સભ્યોના હક્કોમાં ફેરફાર કરો',
'action-userrights-interwiki' => 'અન્ય વિકિ પરના સભ્યોના હક્કો સંપાદિત કરો.',
'action-siteadmin'            => 'માહિતી સંચયને ઉઘાડો અને વાસો.',
'action-revisionmove'         => 'પુનરાવર્તનો ખસેડો',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',
'recentchanges'                   => 'તાજા ફેરફારો',
'recentchanges-legend'            => 'હાલમાં થયેલા ફેરફારોના વિકલ્પ',
'recentchanges-feed-description'  => 'આ ફીડ દ્વારા વિકિમાં થયેલા તાજા ફેરફારો પર ધ્યાન રાખો.',
'recentchanges-label-newpage'     => 'આ ફેરફાર દ્વારા નવું પાનું નિર્મિત થયું',
'recentchanges-label-minor'       => 'આ એક નાનો સુધારો છે.',
'recentchanges-label-bot'         => 'આ ફેરફાર બોટ દ્વારા કરાયો છે',
'recentchanges-label-unpatrolled' => 'આ ફેરફાર હજી ચકાસાયો નથી',
'rcnote'                          => "નીચે $5, $4 સુધીમાં અને તે પહેલાનાં '''$2''' દિવસમાં {{PLURAL:$1| થયેલો '''1''' માત્ર ફેરફાર|થયેલાં છેલ્લા  '''$1''' ફેરફારો}} દર્શાવ્યાં છે .",
'rcnotefrom'                      => "નીચે '''$2'''થી થયેલાં '''$1''' ફેરફારો દર્શાવ્યાં છે.",
'rclistfrom'                      => '$1 બાદ થયેલા નવા ફેરફારો બતાવો',
'rcshowhideminor'                 => 'નાના ફેરફારો $1',
'rcshowhidebots'                  => 'બૉટો $1',
'rcshowhideliu'                   => 'લૉગ ઇન થયેલાં સભ્યો $1',
'rcshowhideanons'                 => 'અનામિ સભ્યો $1',
'rcshowhidepatr'                  => ' $1 ચોકીયાત ફેરફારો',
'rcshowhidemine'                  => 'મારા ફેરફારો $1',
'rclinks'                         => 'છેલ્લાં $2 દિવસમાં થયેલા છેલ્લાં $1 ફેરફારો દર્શાવો<br />$3',
'diff'                            => 'ભેદ',
'hist'                            => 'ઇતિહાસ',
'hide'                            => 'છુપાવો',
'show'                            => 'બતાવો',
'minoreditletter'                 => 'નાનું',
'newpageletter'                   => 'નવું',
'boteditletter'                   => 'બૉટ',
'rc_categories_any'               => 'કોઇ પણ',
'newsectionsummary'               => '/* $1 */ નવો વિભાગ',
'rc-enhanced-expand'              => 'વિગતો બતાવો (જાવા સ્ક્રિપ્ટ જરૂરી છે)',
'rc-enhanced-hide'                => 'વિગતો છુપાવો',

# Recent changes linked
'recentchangeslinked'          => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-feed'     => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-toolbox'  => 'આની સાથે જોડાયેલા ફેરફાર',
'recentchangeslinked-title'    => '"$1" ને લગતા ફેરફારો',
'recentchangeslinked-noresult' => 'સંકળાયેલાં પાનાંમાં સુચવેલા સમય દરમ્યાન કોઇ ફેરફાર થયાં નથી.',
'recentchangeslinked-summary'  => "આ એવા ફેરફારોની યાદી છે જે આ ચોક્કસ પાના (કે શ્રેણીનાં સભ્ય પાનાઓ) સાથે જોડાયેલા પાનાઓમાં તાજેતરમાં કરવામાં આવ્યા હોય.
<br />[[Special:Watchlist|તમારી ધ્યાનસૂચિમાં]] હોય તેવા પાનાં '''ઘાટા અક્ષર'''માં વર્ણવ્યાં છે",
'recentchangeslinked-page'     => 'પાનાનું નામ:',
'recentchangeslinked-to'       => 'આને બદલે આપેલા પાનાં સાથે જોડાયેલા લેખોમાં થયેલા ફેરફારો શોધો',

# Upload
'upload'                => 'ફાઇલ ચડાવો',
'uploadbtn'             => 'ફાઇલ ચડાવો',
'reuploaddesc'          => 'ફાઇલ ચઢાવવાનું રદ્દ કરો અને ફરી ફાઇલ ચઢાવવાના પાના પર ફરી જાવ',
'upload-tryagain'       => 'સુધારીત ફાઇલ વર્ણન ચડાવો',
'uploadnologin'         => 'પ્રવેશ કરેલ નથી',
'uploadnologintext'     => 'ફાઇલ ચઢાવવા માટે  [[Special:UserLogin|logged in]] પ્રવેશ કરેલો હોવો જોઇએ',
'uploaderror'           => 'ફાઇલ ચઢાવમાં ચૂક',
'upload-permitted'      => 'રજામંદ ફાઈલ પ્રકારો: $1.',
'upload-preferred'      => 'ઈચ્છીત ફાઈલ પ્રકારો: $1.',
'upload-prohibited'     => 'પ્રતિબંધીત ફાઈલ પ્રકારો: $1.',
'uploadlog'             => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'uploadlogpage'         => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'filename'              => 'ફાઇલ નામ',
'filedesc'              => 'સારાંશ:',
'fileuploadsummary'     => 'સારાંશ:',
'filereuploadsummary'   => 'ફાઈલ ફેરફારો',
'filestatus'            => 'કોપી રાઇટ સ્થિતી',
'filesource'            => 'સ્ત્રોત:',
'uploadedfiles'         => 'ફાઇલ ચડાવો',
'ignorewarning'         => 'ચેતવણીને અવગણી ને પણ ફાઇલ સાચવો',
'ignorewarnings'        => 'કોઇ પણ ચેતવણી અવગણો',
'minlength1'            => 'ફાઇલ નામની લંબાઇ કમ સે કમ એક અક્ષર જેટલી તો હોવી જ જોઇએ.',
'badfilename'           => 'ફાઇલ નામ  "$1" નામે બદલાયું છે.',
'filetype-missing'      => 'ફાઇલને કોઇ વિસ્તાર શબ્દ નથી (જેમકે ".jpg").',
'empty-file'            => 'તમે ચડાવેલી ફાઈલ ખાલી છે',
'file-too-large'        => 'તમે ચડાવેલી ફાઈલ ખૂબ મોટી છે',
'filename-tooshort'     => 'ફાઇલ નામ ખૂબ ટૂંકું છે',
'filetype-banned'       => 'આ પ્રકારની ફાઈલ પ્રતિબંધિત છે.',
'verification-error'    => 'આ ફાઇલ એ ચકાસણી કસોટી પાર ન કરી',
'illegal-filename'      => 'ફાઈલા નામને પરવાનગી નથી',
'overwrite'             => 'વિહરમાન ફાઇલ પર પુનર્લેખન કરવાની પરવાનગી નથી',
'unknown-error'         => 'અજ્ઞાત ચૂક થઈ',
'tmp-create-error'      => 'હંગામી ફાઇલ ન બનાવી શકાઇ',
'tmp-write-error'       => 'હંગામી ફાઇલ લખવામાં ખામી',
'large-file'            => 'ફાઇલ $1 કરતાં મોટી ન હોય તે ઇચ્છનીય છે. 
આ ફાઇલનું કદ $2 છે.',
'largefileserver'       => 'સરવરે પરવાનગી આપેલ કદ કરતાં આ ફાઇલ મોટી  છે.',
'file-exists-duplicate' => 'આ ફાઇલ {{PLURAL:$1|ફાઇલ|ફાઇલો} ની પ્રત છે.',
'uploadwarning'         => 'ફાઇલ ચઢાવ ચેતવણી',
'uploadwarning-text'    => 'કૃપયા ફાઈલ સંબધી વર્ણન સુધારો અને ફરી પ્રયત્ન કરો',
'savefile'              => 'સાચવો',
'uploadedimage'         => '"[[$1]]" ચઢાવ્યું',
'overwroteimage'        => ' "[[$1]]" ની નવી આવૃત્તિ ચઢાવો.',
'uploaddisabled'        => 'ફાઇલ ચઢાવ પ્રતિબંધિત',
'copyuploaddisabled'    => 'URL દ્વાર ફાઇલ ચઢાવ પ્રતિબંધિત',
'uploadfromurl-queued'  => 'તમારી ચઢાવેલી ફાઇલ કતારમાં ઉમેરાઇ છે.',
'uploaddisabledtext'    => 'ફાઇલ ચઢાવવું નિષ્ક્રીય બનાવ્યું છે',
'uploadvirus'           => 'ફાઇલ વાયરસ સંક્ર્મિત છે
વિવરણ : $1',
'upload-source'         => 'સ્ત્રોત ફાઇલ',
'sourcefilename'        => 'સ્ત્રોત ફાઇલ નામ',
'sourceurl'             => 'સ્ત્રોત  URL:',
'destfilename'          => 'લક્ષ્ય ફાઇલ નામ',
'upload-maxfilesize'    => 'મહત્તમ ફાઈલ કદ : $1',
'upload-description'    => 'ફાઇલ માહિતી',
'upload-options'        => 'ચડાવ વિકલ્પ',
'watchthisupload'       => 'આ પાનાને ધ્યાનમાં રાખો',
'upload-success-subj'   => 'ફાઇલ ચડાવ સફળ',
'upload-success-msg'    => '[$2]થી તમારુઁ ફાઇલ ચડાવ સફળ રહ્યો. તે અહીઁ ઉપલબ્ધ છે. : [[:{{ns:file}}:$1]]',
'upload-failure-subj'   => 'ફાઇલ ચઢાવ મુશ્કેલી',
'upload-failure-msg'    => 'તમરા ફાઇલ ચડાવવામાં [$2]થી અડચણ થઇ:

$1',
'upload-warning-subj'   => 'ફાઇલ ચઢાવ ચેતવણી',

'upload-proto-error'        => 'ખોટો શિષ્ટાચાર',
'upload-file-error'         => 'આંતરિક ત્રુટિ',
'upload-misc-error'         => 'અજ્ઞાત ફાઇલ ચડાવ ચૂક',
'upload-too-many-redirects' => 'URLમાં ઘણાં ઉપ નિર્દેશનો છે.',
'upload-unknown-size'       => 'અજ્ઞાત કદ',
'upload-http-error'         => ' HTTP ત્રુટિ : $1',

# Special:UploadStash
'uploadstash'          => 'ગુપ્ત ફાઈલ ચડાવો',
'uploadstash-clear'    => 'ગુપ્ત ફાઈલ સાફ કરો',
'uploadstash-nofiles'  => 'કોઈ ગુપ્ત ફાઈલ નથી',
'uploadstash-errclear' => 'ફાઇલ સાફ સફાઇ અસફળ રહી',
'uploadstash-refresh'  => 'અધ્યતન ફાઇલ યાદિ',

# img_auth script messages
'img-auth-accessdenied' => 'પ્રવેશ વર્જીત',
'img-auth-notindir'     => 'અર્જીત પથ ચડાવેલ ફાઈલની ડીરેક્ટરીમાં નથી',
'img-auth-badtitle'     => '"$1" માટે વૈધ શીર્ષક ન શોધી શકાયું',
'img-auth-nologinnWL'   => 'તમે પ્રવેશ કર્યો નથી અને અને : $1 ધવલ યાદિમાં નથી.',
'img-auth-nofile'       => ' ફાઇલ "$1" અસ્તિત્વમાં નથી',
'img-auth-streaming'    => 'ચિત્ર માહીતી  "$1" ઉતરી રહી છે.',
'img-auth-noread'       => '"$1" વાંચવાને સભ્યને પરવાનગી નથી.',

# HTTP errors
'http-invalid-url'      => 'અવૈધ URL: $1',
'http-invalid-scheme'   => '$1 પદ્ધતિના URLs અહીં ચાલતી નથી',
'http-request-error'    => 'અજ્ઞાત ત્રુટીને કારણે HTTP અરજી નિષ્ફળ',
'http-read-error'       => 'HTTP વાચન ત્રુટિ.',
'http-timed-out'        => ' HTTP અરજી કાલાતિત થઇ ગઇ.',
'http-curl-error'       => 'URL: $1 ખેંચી લાવવામાં ત્રુટિ',
'http-host-unreachable' => 'URL સુધી ન પહોંચી શકાયું.',
'http-bad-status'       => 'HTTP અરજી વખતે કોઈ અડચણ આવી : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'URL સુધી ન પહોંચી શકાયું.',
'upload-curl-error28' => 'સમય સમાપ્ત સંદેશ ચડાવો',

'license'            => 'પરવાના',
'license-header'     => 'પરવાના',
'nolicense'          => 'કોઇ વિકલ્પ પસંદ નથી કરાયો',
'license-nopreview'  => '(ઝલક મોજુદ નથી)',
'upload_source_url'  => ' (એક વૈધ , જાહેર URL)',
'upload_source_file' => '(તમારા કોમ્પ્યુટર પરની એક ફાઇલ)',

# Special:ListFiles
'listfiles_search_for'  => 'મિડિયા નામ શોધો:',
'imgfile'               => 'ફાઇલ',
'listfiles'             => 'ફાઇલોની યાદી',
'listfiles_thumb'       => 'લઘુચિત્ર',
'listfiles_date'        => 'તારીખ',
'listfiles_name'        => 'નામ',
'listfiles_user'        => 'સભ્ય',
'listfiles_size'        => 'માપ',
'listfiles_description' => 'વર્ણન',
'listfiles_count'       => 'આવૃત્તિ',

# File description page
'file-anchor-link'          => 'ફાઇલ/દસ્તાવેજ',
'filehist'                  => 'ફાઇલનો ઇતિહાસ',
'filehist-help'             => 'તારિખ/સમય ઉપર ક્લિક કરવાથી તે સમયે ફાઇલ કેવી હતી તે જોવા મળશે',
'filehist-deleteall'        => 'બધા ભૂસો',
'filehist-deleteone'        => 'હટાવો',
'filehist-revert'           => 'પૂર્વવત કરો',
'filehist-current'          => 'વર્તમાન',
'filehist-datetime'         => 'તારીખ/સમય',
'filehist-thumb'            => 'લઘુચિત્ર',
'filehist-thumbtext'        => '$1ના સંસ્કરણનું લઘુચિત્ર',
'filehist-nothumb'          => 'થમ્બનેઇલ નથી',
'filehist-user'             => 'સભ્ય',
'filehist-dimensions'       => 'પરિમાણ',
'filehist-filesize'         => 'ફાઇલનું કદ',
'filehist-comment'          => 'ટિપ્પણી',
'filehist-missing'          => 'ફાઇલ ગાયબ',
'imagelinks'                => 'ફાઇલની કડીઓ',
'linkstoimage'              => 'આ ફાઇલ સાથે {{PLURAL:$1|નીચેનું પાનું જોડાયેલું|$1 નીચેનાં પાનાઓ જોડાયેલાં}} છે',
'linkstoimage-more'         => '$1 કરતાં વધુ {{PLURAL:$1|પાનું|પાનાં}} આ ફાઇલ સાથે જોડાય છે.
નીચે જણાવેલ યાદી ફક્ત આ ફાઇલ સાથે જોડાયેલ {{PLURAL:$1|પ્રથમ પાનાની કડી|પ્રથમ $1 પાનાંની કડીઓ}} બતાવે છે.
અહીં [[Special:WhatLinksHere/$2|પુરી યાદી]]  મળશે.',
'nolinkstoimage'            => 'આ ફાઇલ સાથે કોઇ પાનાં જોડાયેલાં નથી.',
'morelinkstoimage'          => 'આ ફાઇલ સાથે જોડાયેલ [[Special:WhatLinksHere/$1|વધુ કડીઓ]] જુઓ.',
'redirectstofile'           => 'નીચે જણાવેલ {{PLURAL:$1|ફાઇલ|$1 ફાઇલો}} આ ફાઇલ પર વાળેલી છે:',
'duplicatesoffile'          => 'નીચે જણાવેલ {{PLURAL:$1|ફાઇલ|$1 ફાઇલો}} આ ફાઇલની નકલ છે. ([[Special:FileDuplicateSearch/$2|વધુ વિગતો]])',
'sharedupload'              => 'આ ફાઇલ $1માં આવેલી છે અને શક્ય છે કે તે અન્ય પરિયોજનાઓમાં પણ વપરાતી હોય.',
'sharedupload-desc-there'   => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
વધુ માહિતી માટે મહેરબાની કરીને [$2 ફાઇલનાં વર્ણનનુ પાનું] જુઓ.',
'sharedupload-desc-here'    => 'આ ફાઇલ $1નો ભાગ છે અને શક્ય છે કે અન્ય પ્રકલ્પોમાં પણ વપરાઇ હોય.
ત્યાંનાં મૂળ [$2 ફાઇલનાં વર્ણનનાં પાના] પર આપેલું વર્ણન નીચે દર્શાવેલું છે.',
'filepage-nofile'           => 'આ નામે કોઇ ફાઇલ અસ્તિત્વમાં નથી',
'filepage-nofile-link'      => 'આ નામે કોઇ ફાઇલ નથી પણ તમે [$1 ચડાવી] શકો છો.',
'uploadnewversion-linktext' => 'આ ફાઇલની નવી આવૃત્તિ ચઢાવો',
'shared-repo-from'          => '$1 થી',
'shared-repo'               => 'સાંઝો ભંડાર',

# File reversion
'filerevert'                => '$1 હતું તેવું કરો',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'ફાઇલ હતી તેવી કરો',
'filerevert-intro'          => "તમે '''[[Media:$1|$1]]''' ફાઇલ હતી તેવી મૂળ સ્થિતિ[$3, $2 વખતે હતું તેવું વર્ઝન $4]માં  લઇ જઇ રહ્યા છો.",
'filerevert-comment'        => 'કારણ:',
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
'mimetype'           => 'MIME પ્રકાર:',
'download'           => 'ડાઉનલોડ',

# Unwatched pages
'unwatchedpages' => 'ધ્યાનમાં ન રખાયેલ પાના.',

# List redirects
'listredirects' => 'અન્યત્ર વાળેલાં પાનાંઓની યાદી',

# Unused templates
'unusedtemplates'    => 'વણ વપરાયેલાં ઢાંચા',
'unusedtemplateswlh' => 'અન્ય કડીઓ',

# Random page
'randompage' => 'કોઈ પણ એક લેખ',

# Random redirect
'randomredirect' => 'દિશાહીન  નિર્દેશન',

# Statistics
'statistics'               => 'આંકડાકિય માહિતિ',
'statistics-header-pages'  => 'પાના સંબંધી આંકડાકીય માહિતી',
'statistics-header-edits'  => 'આંકડાકીય માહિતી બદલો',
'statistics-header-views'  => 'આંકડાકીય માહિતી જુઓ',
'statistics-header-users'  => 'સભ્ય સંબંધી આંકડાકીય માહિતી',
'statistics-header-hooks'  => 'અન્ય આંકડાકીય માહિતી',
'statistics-articles'      => 'લેખનું પાનું',
'statistics-pages'         => 'પાનાં',
'statistics-pages-desc'    => 'ચર્ચા પાના અને નીર્દેશીત પાના સહિત વિકિના બધા પાના',
'statistics-files'         => 'ચડાવેલ ફાઇલો',
'statistics-edits-average' => 'દર પાના પર સરાસરી ફેરફારો',
'statistics-views-total'   => 'સરવાળો',
'statistics-views-peredit' => 'પ્રતિ ફેરફાર ના દેખાવ',
'statistics-users'         => 'નોંધણી થયેલ [[Special:સભ્ય|સભ્યો]]',
'statistics-users-active'  => 'સક્રીય સભ્યો',
'statistics-mostpopular'   => 'સૌથી વધુ જોવાયેલા પાના',

'disambiguations'     => 'અસંદિગ્ધ શબ્દોવાળા પાના',
'disambiguationspage' => 'Template:અસંદિગ્ધ',

'doubleredirects'            => 'બનણું દિશાનિર્દેશિત',
'double-redirect-fixed-move' => '[[$1]] હટાવી દેવાયું છે.
હવે તે [[$2]] પરાનિર્દેશીત છે.',
'double-redirect-fixer'      => 'નિર્દેશન સમારનાર',

'brokenredirects'        => 'ત્રુટક નિર્દેશન',
'brokenredirectstext'    => 'આ નોર્દેશન કડી દર્શાવતા પાના અસ્તિત્વમાં નથી.',
'brokenredirects-edit'   => 'ફેરફાર કરો',
'brokenredirects-delete' => 'હટાવો',

'withoutinterwiki'         => 'અન્ય ભાષાઓની કડીઓ વગરનાં પાનાં',
'withoutinterwiki-summary' => 'આ પાનાઓ અન્ય ભાષા પરની કડી નથી બતાવતાં',
'withoutinterwiki-legend'  => 'પૂર્વર્ગ',
'withoutinterwiki-submit'  => 'બતાવો',

'fewestrevisions' => 'સૌથી ઓછાં ફેરફાર થયેલા પાનાં',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|બાઇટ|બાઇટ્સ}}',
'ncategories'             => '$1 {{PLURAL:$1|શ્રેણી|શ્રેણીઓ}}',
'nlinks'                  => '$1 {{PLURAL:$1|કડી|કડીઓ}}',
'nmembers'                => '$1 {{PLURAL:$1|સદસ્ય|સદસ્યો}}',
'nrevisions'              => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',
'nviews'                  => '$1 {{PLURAL:$1|દેખાવ|દેખાવો}}',
'nimagelinks'             => '$1 {{PLURAL:$1|પાના|પાનાઓ}} પર વપરાયો',
'ntransclusions'          => '$1 {{PLURAL:$1|પાના|પાનાઓ}} પર વપરાયો',
'specialpage-empty'       => 'આ પાનું ખાલી છે.',
'lonelypages'             => 'અનાથ પાના',
'uncategorizedpages'      => 'અવર્ગિકૃત પાનાં',
'uncategorizedcategories' => 'અવર્ગિકૃત શ્રેણીઓ',
'uncategorizedimages'     => 'અવર્ગિકૃત દસ્તાવેજો',
'uncategorizedtemplates'  => 'અવર્ગિકૃત ઢાંચાઓ',
'unusedcategories'        => 'વણ વપરાયેલી શ્રેણીઓ',
'unusedimages'            => 'વણ વપરાયેલાં દસ્તાવેજો',
'popularpages'            => 'પ્રખ્યાત પાના',
'wantedcategories'        => 'ઇચ્છિત શ્રેણીઓ',
'wantedpages'             => 'ઇચ્છિત પાનાં',
'wantedpages-badtitle'    => 'પરિણામ ગણમાં અવૈધ શીર્ષક: $1',
'wantedfiles'             => 'ઇચ્છિત ફાઈલો',
'wantedtemplates'         => 'જોઈતા ઢાંચા',
'mostlinked'              => 'સૌથી વધુ કડીઓ દ્વારા જોડાયેલ પાનું',
'mostlinkedcategories'    => 'સૌથી વધુ શ્રેણીઓ દ્વારા જોડાયેલ પાનું',
'mostlinkedtemplates'     => 'સૌથી વધુ ઢાંચાઓ  દ્વારા જોડાયેલ પાનું',
'mostcategories'          => 'સૌથી વધુ શ્રેણીઓ ધરાવતાં પાનાં',
'mostimages'              => 'સૌથી વધુ કડીઓ દ્વારા જોડાયેલી ફાઇલ',
'mostrevisions'           => 'સૌથી વધુ ફેરફાર થયેલા પાનાં',
'prefixindex'             => 'પૂર્વાક્ષર સૂચિ',
'shortpages'              => 'નાનાં પાનાં',
'longpages'               => 'લાંબા પાનાઓ',
'deadendpages'            => 'લેખ સમાપ્તિ પાના',
'protectedpages'          => 'સંરક્ષિત પાનાઓ',
'protectedpages-indef'    => 'ફક્ત અનિશ્ચિત સુરક્ષા ધરાવતા પાના',
'protectedpages-cascade'  => 'માત્ર પગથિયામય સુરક્ષા વાળા પગ',
'protectedpagesempty'     => 'આ વિકલ્પો દ્વારા કોઈ પાના સુરક્ષીત કરાયા નથી',
'protectedtitles'         => 'સંરક્ષીત શીર્ષકો',
'protectedtitlestext'     => 'આ શીર્ષકો રચના માટે આરક્ષીત છે',
'listusers'               => 'સભ્યોની યાદી',
'listusers-editsonly'     => 'માત્ર સંપાદન કરનારા સભ્યો બતાવો',
'listusers-creationsort'  => 'તારીખ અનુસાર ગોઠવો',
'usereditcount'           => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',
'usercreated'             => '$1 તારીખે $2 વાગ્યે નિર્મિત',
'newpages'                => 'નવા પાના',
'newpages-username'       => 'સભ્ય નામ:',
'ancientpages'            => 'સૌથી જૂનાં પાના',
'move'                    => 'નામ બદલો',
'movethispage'            => 'આ પાનું ખસેડો',
'unusedcategoriestext'    => 'નીચેના શ્રેણી પાના છે પણા કોઈ લેખ આનો ઉપયોગ કરતાં નથી',
'notargettitle'           => 'કોઇ લક્ષ્ય નથી',
'nopagetitle'             => 'આવું કોઇ લક્ષ્ય પાનું નથી',
'nopagetext'              => 'તમે લખેલ પાનું અસ્તિત્વમાં નથી',
'pager-newer-n'           => '{{PLURAL:$1|નવું 1|નવા $1}}',
'pager-older-n'           => '{{PLURAL:$1|જુનું 1|જુનાં $1}}',
'suppress'                => 'દુર્લક્ષ',

# Book sources
'booksources'               => 'પુસ્તક સ્ત્રોત',
'booksources-search-legend' => 'પુસ્તક સ્ત્રોત શોધો',
'booksources-isbn'          => 'આઇએસબીએન:',
'booksources-go'            => 'જાઓ',

# Special:Log
'specialloguserlabel'  => 'સભ્ય:',
'speciallogtitlelabel' => 'શિર્ષક:',
'log'                  => 'લૉગ',
'all-logs-page'        => 'બધાં જાહેર માહિતીપત્રકો',
'logempty'             => 'લોગમાં આને મળતી કોઇ વસ્તુ નથી',
'log-title-wildcard'   => 'આ શબ્દો દ્વારા શરૂ થનાર શીર્ષકો શોધો',

# Special:AllPages
'allpages'          => 'બધા પાના',
'alphaindexline'    => '$1 થી $2',
'nextpage'          => 'આગળનું પાનું ($1)',
'prevpage'          => 'પાછળનું પાનું ($1)',
'allpagesfrom'      => 'આનાથી શરૂ થતા પાના દર્શાવો:',
'allpagesto'        => 'આનાથી અંત થતા પાના દર્શાવો:',
'allarticles'       => 'બધા લેખ',
'allinnamespace'    => 'બધા પાના  ($1 નમાવકાશ)',
'allnotinnamespace' => 'બધા પાના  ($1 નમાવકાશમાંના હોય)',
'allpagesprev'      => 'પહેલાનું',
'allpagesnext'      => 'પછીનું',
'allpagessubmit'    => 'જાઓ',
'allpagesprefix'    => 'પૂર્વર્ગ ધરાવતા પાના શોધો',

# Special:Categories
'categories'                    => 'શ્રેણીઓ',
'categoriespagetext'            => 'નીચેની {{PLURAL:$1|શ્રેણી|શ્રેણીઓ}}માં પાના કે અન્ય સભ્યો છે.
[[Special:UnusedCategories|વણ વપરાયેલી શ્રેણીઓ]] અત્રે દર્શાવવામાં આવી નથી.
[[Special:WantedCategories|ઈચ્છિત શ્રેણીઓ]] પણ જોઈ જુઓ.',
'categoriesfrom'                => 'આનાથી શરૂ થતી શ્રેણી દર્શાવો:',
'special-categories-sort-count' => 'સંખ્યા આધારીત ચઢતા ક્રમમાં વર્ગીકરણ કરો',
'special-categories-sort-abc'   => 'મૂળાક્ષરો પ્રમાણે ગોઠવો',

# Special:DeletedContributions
'deletedcontributions'             => 'સભ્યનું યોગદાન ભૂંસી નાખો',
'deletedcontributions-title'       => 'સભ્યનું ભૂંસેલું યોગદાન',
'sp-deletedcontributions-contribs' => 'યોગદાન',

# Special:LinkSearch
'linksearch'      => 'બાહ્ય કડીઓ',
'linksearch-pat'  => 'શોધા આલેખ',
'linksearch-ns'   => 'નામાવકાશ:',
'linksearch-ok'   => 'શોધ',
'linksearch-line' => '$1 એ $2થી જોડાયેલ છે',

# Special:ListUsers
'listusersfrom'      => 'અહીંથી સભ્યો બતાવો',
'listusers-submit'   => 'બતાવો',
'listusers-noresult' => 'કોઇ સભ્ય ન મળ્યો',
'listusers-blocked'  => '(પ્રતિબંધિત)',

# Special:ActiveUsers
'activeusers'            => 'સક્રીયા સભ્ય છુપાવો',
'activeusers-from'       => 'આનાથી શરૂ થતા સભ્યો દર્શાવો:',
'activeusers-hidebots'   => 'બોટને સંતાડો',
'activeusers-hidesysops' => 'પ્રબંધકો છુપાવો',
'activeusers-noresult'   => 'કોઇ સક્રીય સભ્ય ન મળ્યો',

# Special:Log/newusers
'newuserlogpage'              => 'નવા બનેલા સભ્યોનો લૉગ',
'newuserlogpagetext'          => 'આ સભ્યોની રચનાનો લોગ છે.',
'newuserlog-byemail'          => 'ગુપ્ત સંજ્ઞા ઇ-મેલ દ્વારા મોકલાઇ છે.',
'newuserlog-create-entry'     => 'નવું ખાતું',
'newuserlog-create2-entry'    => 'નવું ખાતું $1 ખોલાયું',
'newuserlog-autocreate-entry' => 'સ્વયંચલિત રીતે ખુલેલા ખાતાં',

# Special:ListGroupRights
'listgrouprights'                      => 'સભ્ય જૂથ ના હક્કો',
'listgrouprights-group'                => 'જૂથ',
'listgrouprights-rights'               => 'હક્કો',
'listgrouprights-helppage'             => 'મદદ:સમૂહ હક્કો',
'listgrouprights-members'              => '(સભ્યોની યાદી)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|સમૂહ|સમૂહો}} ઉમેરો: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|સમૂહ|સમૂહો}} હટાવો: $1',
'listgrouprights-addgroup-all'         => 'બધા જૂથ ઉમેરો',
'listgrouprights-removegroup-all'      => 'બધા સમૂહો હટાવો',
'listgrouprights-addgroup-self'        => ' {{PLURAL:$2|સમૂહ|સમૂહો}}પોતાના ખાતામાં ઉમેરો: $1',
'listgrouprights-addgroup-self-all'    => 'દરેક જૂથને તેમના પોતાના ખાતા માં ઉમેરો',
'listgrouprights-removegroup-self-all' => 'બધા જૂથને તેમના પોતાના ખાતામાંથી હટાવો',

# E-mail user
'mailnologin'          => 'મેળવનારનું સરનામું નથી',
'emailuser'            => 'સભ્યને ઇ-મેલ કરો',
'emailpage'            => 'ઈ-મેલ સભ્ય',
'defemailsubject'      => '{{SITENAME}} ઈ-મેલ',
'usermaildisabled'     => 'સભ્યનો ઈ-મેલ નિષ્ક્રિય કરાયો',
'usermaildisabledtext' => 'તમે આ વિકિ પર અન્ય સભ્યોને ઇ-મેલ મોકલી ન શકો',
'noemailtitle'         => 'ઈ-મેલ એડ્રેસ નથી',
'noemailtext'          => 'આ સભ્યએ  વૈધ ઇ-મેલ સરનામું નથી આપ્યું.',
'nowikiemailtitle'     => 'કોઇ પણ ઇ મેલની રજા નથી',
'nowikiemailtext'      => 'અન્ય સભ્યો ઇ-મેલ ન મોકલે તેવી આ સભ્યની પસંદગી છે.',
'email-legend'         => 'અન્ય {{SITENAME}} સભ્ય નેઈ-મેલ મોકલો',
'emailfrom'            => 'પ્રેષક:',
'emailto'              => 'પ્રતિ:',
'emailsubject'         => 'વિષય:',
'emailmessage'         => 'સંદેશો:',
'emailsend'            => 'મોકલો',
'emailccme'            => 'મારા ઈ-મેલની પ્રત મને મોકલો',
'emailccsubject'       => 'તમારો સંદેશની પ્રત  $1: $2 માઁ',
'emailsent'            => 'ઈ-મેલ મોકલી દેવાયો',
'emailsenttext'        => 'તમારો ઈ-મેલ મોકલી દેવાયો છે',
'emailuserfooter'      => 'આ ઈ-મેલ $1 દ્વારા $2ને  "E-mail user" સૂત્ર  {{SITENAME}} પર મોકલાવાયું છે.',

# User Messenger
'usermessage-summary' => 'પ્રણાલી સંદેશા મૂકાયો',
'usermessage-editor'  => 'તંત્ર સંદેશાઓ',

# Watchlist
'watchlist'            => 'મારી ધ્યાનસૂચી',
'mywatchlist'          => 'મારી ધ્યાનસૂચિ',
'watchlistfor2'        => 'ધ્યાન સૂચિ $1 $2',
'nowatchlist'          => 'તમારી ધ્યાન સૂચિ ખાલી છે',
'watchnologin'         => 'પ્રવેશ કરેલ નથી',
'addedwatch'           => 'ધ્યાનસૂચિમાં ઉમેરવામાં આવ્યું છે',
'addedwatchtext'       => 'પાનું "[[:$1]]" તમારી [[Special:Watchlist|ધ્યાનસૂચિ]]માં ઉમેરાઈ ગયું છે.
ભવિષ્યમાં આ પાના અને તેનાં સંલગ્ન ચર્ચાનાં પાનામાં થનારા ફેરફારોની યાદી ત્યાં આપવામાં આવશે અને આ પાનું [[Special:RecentChanges|તાજેતરમાં થયેલા ફેરફારોની યાદી]]માં ઘાટા અક્ષરે જોવા મળશે, જેથી આપ સહેલાઇથી તેને અલગ તારવી શકો.',
'removedwatch'         => 'ધ્યાનસૂચિમાંથી કાઢી નાંખ્યું છે',
'removedwatchtext'     => '"[[:$1]]" શિર્ષક હેઠળનું પાનું [[Special:Watchlist|તમારી ધ્યાનસૂચિમાંથી]] કાઢી નાંખવામાં આવ્યું છે.',
'watch'                => 'ધ્યાન માં રાખો',
'watchthispage'        => 'આ પાનું ધ્યાનમાં રાખો',
'unwatch'              => 'ધ્યાનસૂચિમાંથી હટાવો',
'unwatchthispage'      => 'નીરીક્ષણ બંધ કરો',
'notanarticle'         => 'માહિતી વિનાનું પાનું',
'notvisiblerev'        => 'અન્ય સભ્ય દ્વારા થયેલું સંપાદન ભૂંસી નખાયું છે.',
'watchlist-details'    => 'ચર્ચાનાં પાનાં ન ગણતા {{PLURAL:$1|$1 પાનું|$1 પાનાં}} ધ્યાનસૂચીમાં છે.',
'wlheader-enotif'      => '*ઈ-મેલા સૂચના પદ્ધતિ સક્રીય કરાઈ',
'wlheader-showupdated' => "*તમારી મુલાકાત લીધા પછી બદલાયેલા પાના  '''ઘાટા''' અક્ષરો વડે દર્શાવ્યાં છે",
'watchmethod-list'     => 'હાલમાં થયેલ ફેરફાર માટે નીરીક્ષીત પાના તપાસાય છે',
'watchlistcontains'    => 'તમારી ધ્યાનસૂચીમાં $1 {{PLURAL:$1|પાનું|પાનાં}} છે.',
'wlshowlast'           => 'છેલ્લા $1 કલાક $2 દિવસ $3 બતાવો',
'watchlist-options'    => 'ધ્યાનસૂચિના વિકલ્પો',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'નજર રાખી રહ્યાં છો...',
'unwatching' => 'નજર રાખવાની બંધ કરી છે...',

'enotif_mailer'                => '{{SITENAME}} સૂચના ઈ-મેલ પાઠક',
'enotif_reset'                 => 'બધા પાનાને મુલાકાત લેવાયેલા અંકિત કરો',
'enotif_newpagetext'           => 'આ નવું પાનું છે.',
'enotif_impersonal_salutation' => '{{SITENAME}} સભ્ય',
'changed'                      => 'બદલાયેલું',
'created'                      => 'બનાવ્યું',
'enotif_lastvisited'           => 'તમારા છેલ્લા ફેરફાર પછી થયેલા ફેરફાર $1 જુઓ',
'enotif_lastdiff'              => 'આ ફેરફાર જોવા $1 જુઓ',
'enotif_anon_editor'           => 'અજ્ઞાત સભ્ય $1',

# Delete
'deletepage'             => 'પાનું હટાવો',
'confirm'                => 'ખાતરી કરો',
'excontent'              => 'આટલી જ માહિતી હતી: "$1"',
'excontentauthor'        => 'માત્ર આજ માહિતી હતી : "$1" (અને યોગદાન કરનાર માત્ર "[[Special:Contributions/$2|$2]]" જ હતા)',
'exbeforeblank'          => 'આ પાનું ભૂંસતા પહેલા તેમાં આ શબ્દો હતાં: $1',
'exblank'                => 'પાનું ખાલી હતું',
'delete-confirm'         => '$1ને ભૂસી નાંખો.',
'delete-legend'          => 'રદ કરો',
'historywarning'         => "'''ચેતવણી:''' જે પાનું તમે હટાવવા જઇ રહ્યાં છો તેને આશરે $1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}નો ઇતિહાસ છે:",
'confirmdeletetext'      => 'આપ આ પાનું તેના ઇતિહાસ (બધાજ પૂર્વ  ફેરફારો) સાથે હટાવી રહ્યાં છો.
કૃપા કરી મંજૂરી આપો કે, આપ આમ કરવા ચાહો છો, આપ આના સરા-નરસા પરિણામોથી વાકેફ છો, અને આપ આ કૃત્ય [[{{MediaWiki:Policy-url}}|નીતિ]]ને અનુરૂપ જ કરી રહ્યાં છો.',
'actioncomplete'         => 'કામ પૂરું થઈ ગયું',
'actionfailed'           => 'કાર્ય અસફળ',
'deletedtext'            => '"<nowiki>$1</nowiki>" દૂર કરવામાં આવ્યું છે.
તાજેતરમાં દૂર કરેલા લેખોની વિગત માટે $2 જુઓ.',
'deletedarticle'         => 'હટાવવામાં આવેલા "[[$1]]"',
'suppressedarticle'      => 'છુપાવેલા "[[$1]]"',
'dellogpage'             => 'હટાવેલાઓનું માહિતિ પત્રક (ડિલિશન લૉગ)',
'dellogpagetext'         => 'હાલમાં હટાવેલ પાનાની યાદિ',
'deletionlog'            => 'હટાવેલાઓનું માહિતિ પત્રક (ડિલિશન લૉગ)',
'reverted'               => 'આગળના ઉલટાવેલા સંપાદનો',
'deletecomment'          => 'કારણ:',
'deleteotherreason'      => 'અન્ય/વધારાનું કારણ:',
'deletereasonotherlist'  => 'અન્ય કારણ',
'deletereason-dropdown'  => '*સામાન્ય કારણો 
** લેખકની વિનંતિ 
** પ્રકાશન અધિકાર ભંગ 
** ભાંગફોડીયા પ્રવૃત્તિ',
'delete-edit-reasonlist' => 'ભુંસવાનું કારણ બદલો.',
'delete-toobig'          => 'આ પાનાના ફેરફારોનો ઇતિહાસ ખૂબ લાંબો છે , $1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}થી પણ વધારે.
{{SITENAME}}ને અક્સ્માતે ખોરવાતું અટકાવવા આવા પાનાને હટાવવા પર પ્રતિબંધ છે.',

# Rollback
'rollback'          => 'ફેરફારો ઉલટાવો',
'rollback_short'    => 'ઉલટાવો',
'rollbacklink'      => 'પાછું વાળો',
'rollbackfailed'    => 'ઉલટાવવું નિષ્ફળ',
'cantrollback'      => 'આ ફેરફારો ઉલટાવી નહી શકાય
છેલ્લો ફેરફાર આ પાના ના રચયિતા દ્વારા જ થયો હતો',
'editcomment'       => "ફેરફાર સારાંશ હતી: \"''\$1''\".",
'revertpage-nouser' => ' (સભ્ય નામ હટાવ્યું) દ્વારા થયેલ ફેરફારને  [[User:$1|$1]]ના દ્વારા થયેલ છેલ્લા પુનરાવર્તન પર પાછા લઇ જવાયા',
'rollback-success'  => '$1 દ્વારા થયેલા ફેરફારો ઉલટાવાયા
તેને $2 દ્વારા થયેલ સંપાદન સુધી લઇ જવાયું',

# Edit tokens
'sessionfailure-title' => 'સત્ર નિષ્ફળ',

# Protect
'protectlogpage'              => 'સુરક્ષા માહિતિ પત્રક',
'protectedarticle'            => 'સુરક્ષિત "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"નું સુરક્ષાસ્તર બદલ્યું',
'unprotectedarticle'          => 'અસુરક્ષિત "[[$1]]"',
'protect-title'               => '"$1"નું સુરક્ષાસ્તર બદલ્યું',
'prot_1movedto2'              => '[[$1]]નું નામ બદલીને [[$2]] કરવામાં આવ્યું છે.',
'protect-legend'              => 'સઁરક્ષણને બહાલી આપો',
'protectcomment'              => 'કારણ:',
'protectexpiry'               => 'સમાપ્તિ:',
'protect_expiry_invalid'      => 'સમાપ્તિનો સમય માન્ય નથી.',
'protect_expiry_old'          => 'સમાપ્તિનો સમય ભૂતકાળમાં છે.',
'protect-unchain-permissions' => 'આગળના સંરક્ષણ પ્રતિબંધ વિકલ્પ મુક્ત કરો',
'protect-text'                => "અહિં તમે પાના '''<nowiki>$1</nowiki>'''નું સુરક્ષા સ્તર જોઈ શકો છો અને તેમાં ફેરફાર પણ કરી શકશો.",
'protect-locked-access'       => "તમને પાનાની સુરક્ષાનાં સ્તરમાં ફેરફાર કરવાની પરવાનગી નથી.
પાનાં '''$1'''નું હાલનું સેટીંગ અહિં જોઈ શકો છો:",
'protect-cascadeon'           => 'આ પાનું હાલમાં સંરક્ષિત છે કારણકે તે {{PLURAL:$1|પાનું,|પાનાઓ,}} જેમાં ધોધાકાર સંરક્ષણ ચાલુ છે, તેમાં છે.

તમે આ પાનાઓનું સંરક્ષણ સ્તર બદલી શકો છો, પરંતુ તેની અસર ધોધાકાર સંરક્ષણ પર પડવી જોઇએ નહીં.',
'protect-default'             => 'બધા સભ્યોને પરવાનગી',
'protect-fallback'            => '"$1" પરવાનગી જરૂરી',
'protect-level-autoconfirmed' => 'નવા અને નહી નોંધાયેલા સભ્યો પર પ્રતિબંધ',
'protect-level-sysop'         => 'માત્ર પ્રબંધકો',
'protect-summary-cascade'     => 'ધોધાકાર',
'protect-expiring'            => '$1 (UTC) એ સમાપ્ત થાય છે',
'protect-expiry-indefinite'   => 'અનિશ્ચિત',
'protect-cascade'             => 'આ પાનામાં સમાવિષ્ટ પેટા પાનાં પણ સુરક્ષિત કરો (કૅસ્કેડીંગ સુરક્ષા)',
'protect-cantedit'            => 'આપ આ પાનાનાં સુરક્ષા સ્તરમાં ફેરફાર ના કરી શકો, કેમકે આપને અહિં ફેરફાર કરવાની પરવાનગી નથી.',
'protect-othertime'           => 'અન્ય સમય',
'protect-othertime-op'        => 'અન્ય સમય',
'protect-existing-expiry'     => 'વિહરમાન કાલાતિત સમય : $3, $2',
'protect-otherreason'         => 'અન્ય/વધારાનું કારણ:',
'protect-otherreason-op'      => 'અન્ય કારણ',
'protect-dropdown'            => '* સામાન્ય સંરક્ષણ કરણો 
** આત્યંતિક ભાંગફોડિયા પ્રવૃત્તિ 
** વધારે પડતી સ્પેમિંગ
** અ-ફળદાયી ફેરફાર ચેતવણી
** અત્યંત મુલાકાત લેવાતું પાનું',
'protect-edit-reasonlist'     => 'ભુંસવાનું કારણ બદલો.',
'protect-expiry-options'      => '૨ કલાક:2 hours,૧ દિવસ:1 day,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
'restriction-type'            => 'પરવાનગી:',
'restriction-level'           => 'નિયંત્રણ સ્તર:',
'minimum-size'                => 'લઘુત્તમ કદ',
'maximum-size'                => 'મહત્તમ કદ',
'pagesize'                    => '(બાઇટ્સ)',

# Restrictions (nouns)
'restriction-edit'   => 'બદલો',
'restriction-move'   => 'ખસેડો',
'restriction-create' => 'બનાવો',
'restriction-upload' => 'ફાઇલ ચઢાવો',

# Restriction levels
'restriction-level-sysop'         => 'સંપૂર્ણા સંરક્ષીત',
'restriction-level-autoconfirmed' => 'અર્ધ સંરક્ષીત',
'restriction-level-all'           => 'કોઈ પણ સ્તર',

# Undelete
'undelete'                   => 'ભૂંસાડેલા પાના બતાવો',
'undeletepage'               => 'હટાવેલ પાના જુઓ અને પુંર્જીવીત કરો',
'undeletepagetitle'          => "'''નીચે [[:$1|$1]] ના ભૂંસાડેલ સંપાદનો છે.'''.",
'viewdeletedpage'            => 'ભૂંસાડેલા પાના બતાવો',
'undelete-fieldset-title'    => 'સંપાદનો પાછા લાવો',
'undeleterevisions'          => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}} દસ્તાવેજીત કરાયા',
'undelete-revision'          => '$1 ( $4 તારીખે , $5 વાગ્યા)  સુધી કરેલા ફેરફારો  $3 દ્વારા હટાવાયા:',
'undelete-nodiff'            => 'કોઇ પ્રાચીન સંપદનો નથી મળ્યાં',
'undeletebtn'                => 'પાછું વાળો',
'undeletelink'               => 'જુઓ/પાછુ વાળો',
'undeleteviewlink'           => 'જુઓ',
'undeletereset'              => 'ફરી ગોઠવો',
'undeleteinvert'             => 'પસંદગી ઉલટાવો',
'undeletecomment'            => 'કારણ:',
'undeletedarticle'           => '"[[$1]]" પુનઃસ્થાપિત કર્યું',
'undeletedrevisions'         => '{{PLURAL:$1|૧ સંપાદન|$1 સંપાદનો}} પુન સ્થાપિત કરાયા',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 ફેરફાર|$1 ફેરફારો}} અને {{PLURAL:$2|1 ફાઈલા|$2 ફાઈલો}} પુનઃસ્થાપિત',
'undeletedfiles'             => '{{PLURAL:$1|1 ફાઇલ|$1 ફાઇલો}} પુનઃસ્થાપિત',
'cannotundelete'             => 'પુનઃજીવિત કરવાનું કાર્ય અસફળ 
કોઇકે  આ પાનાને પહેલેથી પુનઃજીવિત કર્યું હોઇ શકે.',
'undeletedpage'              => "'''$1 પુનઃસ્થાપિત કરાયા'''

તાજેતરમાં હટાવેલા કે પુનઃસ્થાપિત થયેલા ફેરફારની નોંધ નો સંદર્ભ અહીં ઉપ્લબ્ધ [[Special:Log/delete|deletion log]].",
'undelete-header'            => 'હાલમાં હટાવેલ પાનાનો  [[Special:Log/delete|the deletion log]]  જુઓ',
'undelete-search-box'        => 'હટાવેલા પાનાની શોધ',
'undelete-search-prefix'     => 'આનાથી શરૂ થતા પાના બતાવો.',
'undelete-search-submit'     => 'શોધો',
'undelete-no-results'        => 'રદ્દીકરણ ઈતિહાસમાં આને  મળતા પાના ન મળ્યાં',
'undelete-cleanup-error'     => 'વણ વપરાયેલી પ્રાચીન  ફાઇલને ભૂંસવામાં ત્રુટિ "$1".',
'undelete-error-short'       => 'આ ફાઇલ પુનર્જીવીત કરવામાં તકલીફ : $1',
'undelete-error-long'        => '$1 આ ફાઈલ ભૂંસતી વખતે ચૂક થઈ',
'undelete-show-file-confirm' => 'શું તમને ખાત્રી છે કેતમે $2 તારીખ $3 વાગ્યા સુધીના "<nowiki>$1</nowiki>" ફાઇલ ના ફેરફાર જોવા માંગો છો?',
'undelete-show-file-submit'  => 'હા',

# Namespace form on various pages
'namespace'      => 'નામસ્થળ:',
'invert'         => 'પસંદગી ઉલટાવો',
'blanknamespace' => '(મુખ્ય)',

# Contributions
'contributions'       => 'સભ્યનું યોગદાન',
'contributions-title' => 'સભ્ય $1નું યોગદાન',
'mycontris'           => 'મારૂં યોગદાન',
'contribsub2'         => '$1 માટે ($2)',
'nocontribs'          => 'આ પરિમાણને મળતી પરિણામ નથી મળ્યાં',
'uctop'               => '(છેક ઉપર)',
'month'               => ':મહિનાથી (અને પહેલાનાં)',
'year'                => ':વર્ષથી (અને પહેલાનાં)',

'sp-contributions-newbies'        => 'માત્ર નવા ખુલેલાં ખાતાઓનું યોગદાન બતાવો',
'sp-contributions-newbies-sub'    => 'નવા ખાતાઓ માટે',
'sp-contributions-newbies-title'  => 'નવા ખાતાના સભ્યોનું યોગદાન',
'sp-contributions-blocklog'       => 'પ્રતિબંધ સૂચિ',
'sp-contributions-deleted'        => 'સભ્યનું ભૂંસેલું યોગદાન',
'sp-contributions-uploads'        => 'ખાસ યોગદાન / ચડાવેલ ફાઇલ',
'sp-contributions-logs'           => 'લૉગ',
'sp-contributions-talk'           => 'યોગદાનકર્તાની ચર્ચા',
'sp-contributions-userrights'     => 'સભ્ય હક્ક પ્રબંધન',
'sp-contributions-blocked-notice' => 'આ સભ્ય પ્રતિબંધિત છે
તમારા સંદર્ભ માટે પ્રતિબંધિત સભ્યોની યાદિ આપી છે',
'sp-contributions-search'         => 'યોગદાન શોધો',
'sp-contributions-username'       => 'IP સરનામું અથવા સભ્યનામ:',
'sp-contributions-toponly'        => 'તાજેતરમાં થયેલા ફેરફારો જબતાવો',
'sp-contributions-submit'         => 'શોધો',

# What links here
'whatlinkshere'            => 'અહિયાં શું જોડાય છે',
'whatlinkshere-title'      => '"$1" સાથે જોડાયેલાં પાનાં',
'whatlinkshere-page'       => 'પાનું:',
'linkshere'                => "નીચેના પાનાઓ '''[[:$1]]''' સાથે જોડાય છે:",
'nolinkshere'              => "'''[[:$1]]'''ની સાથે કોઇ પાના જોડાતા નથી.",
'nolinkshere-ns'           => "પસંદ કરેલ નામ સ્થળમાં કોઇ પાના '''[[:$1]]'''  સાથે જોડાયેલ નથી.",
'isredirect'               => 'પાનું અહીં વાળો',
'istemplate'               => 'સમાવેશ',
'isimage'                  => 'તસવીરની કડી',
'whatlinkshere-prev'       => '{{PLURAL:$1|પહેલાનું|પહેલાનાં $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|પછીનું|પછીનાં $1}}',
'whatlinkshere-links'      => '←  કડીઓ',
'whatlinkshere-hideredirs' => 'અન્યત્ર વાળેલાં પાનાં $1',
'whatlinkshere-hidetrans'  => '$1 આરપાર સમાવેશનો',
'whatlinkshere-hidelinks'  => 'કડીઓ $1',
'whatlinkshere-hideimages' => '$1 ચિત્ર કડીઓ',
'whatlinkshere-filters'    => 'ચાળણી',

# Block/unblock
'blockip'                         => 'સભ્ય પર પ્રતિબંધ મુકો',
'blockip-title'                   => 'સભ્ય પર પ્રતિબંધ મુકો',
'blockip-legend'                  => 'સભ્ય પર પ્રતિબંધ મુકો',
'ipaddress'                       => 'IP સરનામું:',
'ipadressorusername'              => 'IP સરનામું અથવા સભ્યનામ:',
'ipbexpiry'                       => 'કાલાતિત',
'ipbreason'                       => 'કારણ:',
'ipbreasonotherlist'              => 'બીજું કારણ',
'ipbreason-dropdown'              => '*સામાન્ય પ્રતિબંધ કારણો
** ખોટી  માહિતી ઉમેરાઇ  
** પાના માંથી માહિતી ભૂંસી નાખવી 
** અન્ય માહિતી સાથે બાહ્ય કડીઓ જોડાઇ છે 
** પાનામાં મૂર્ખામીભરી અર્થહીન માહિતી ઉમેરવીInserting nonsense/gibberish into pages
**  ત્રાસદાયક વર્તન 
** ઘણા ખાતાઓનું  સાથે શોષણ 
** અસ્વીકાર્ય સભ્ય નામ',
'ipbanononly'                     => 'માત્ર બેનામી સભ્ય્ને છુપાવો',
'ipbcreateaccount'                => 'ખાતા ખોલવા પર પ્રતિબંધ',
'ipbemailban'                     => 'સભ્યના ઇ-મેલ મોકલવા પર પ્રતિબંધ મૂકો',
'ipbsubmit'                       => 'આ સભ્ય પર પ્રતિબંધ મૂકો',
'ipbother'                        => 'અન્ય સમય',
'ipboptions'                      => '૨ કલાક:2 hours,૧ દિવસ:1 day,૩ દિવસ:3 days,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
'ipbotheroption'                  => 'બીજું',
'ipbotherreason'                  => 'અન્ય/વધારાનું કારણ:',
'ipbhidename'                     => 'ફેરફારો અને યાદિમાંથી સભ્ય નામ છુપાવો',
'ipbwatchuser'                    => 'આ સભ્યના સભ્ય અને ચર્ચા પાના જુઓ',
'ipballowusertalk'                => 'આ સભ્ય પ્રતિબંધિત હોય ત્યારે પણ ફેરફારા કરવાની રજા  આપો',
'ipb-change-block'                => 'આ પ્રણાલી સાથે સભ્યને  ફરી પ્રતિબંધિત કરો',
'badipaddress'                    => 'અવૈધ IP સરનામું',
'blockipsuccesssub'               => 'સફળ પ્રતિબંધ મુકાયો',
'ipb-edit-dropdown'               => 'પ્રતિબંધ કારણોમાં ફેરફાર કરો',
'ipb-unblock-addr'                => '$1 પરનો પ્રતિબંધ ઉઠાવો',
'ipb-unblock'                     => 'સભ્યનામ કે  IP સરનામું અપ્રતિબંધિત કરો.',
'ipb-blocklist'                   => 'વિહરમાન પ્રતિબંધો જુઓ',
'ipb-blocklist-contribs'          => 'સભ્ય $1નું યોગદાન',
'unblockip'                       => 'સભ્ય પરનો પ્રતિબંધ હટાવો',
'ipusubmit'                       => 'આપ્રતિબંધન હટાવો',
'unblocked'                       => '[[User:$1|$1]] પ્રતિબંધિત કરાયા',
'unblocked-id'                    => ' $1 નો પ્રતિબંધ હટાવાયો',
'ipblocklist'                     => 'પ્રતિબંધિત IP સરનામા અને સભ્યોની યાદી',
'ipblocklist-legend'              => 'પ્રતિબંધિત સભ્ય શોધો',
'ipblocklist-username'            => 'સભ્યનામ કે IP સરનામું:',
'ipblocklist-sh-userblocks'       => '$1 ખાતા પર પ્રતિબંધ',
'ipblocklist-sh-tempblocks'       => '$1 હંગામી પ્રતિબંધની યાદિ',
'ipblocklist-sh-addressblocks'    => ' $1 એકામાત્ર IP પ્રતિબંધન',
'ipblocklist-submit'              => 'શોધો',
'ipblocklist-localblock'          => 'સ્થાનીય પ્રતિબંધ',
'ipblocklist-otherblocks'         => 'અન્ય {{PLURAL:$1|પ્રતિબંધન|પ્રતિબંધનો}}',
'blocklistline'                   => '$1, $2 પ્રતિબંધ $3 ($4)',
'infiniteblock'                   => 'અનિશ્ચિત',
'expiringblock'                   => '$1 તારીખે  $2 વાગ્યે કાલાતીત થયું',
'anononlyblock'                   => 'માત્ર અનામી',
'noautoblockblock'                => 'સ્વયંચાલિત પ્રતિબંધ ક્રિયા નિષ્ક્રીય કરાઈ',
'createaccountblock'              => 'ખાતું ખોલવા પર પ્રતિબંધ છે',
'emailblock'                      => 'ઇ-મેલ પર પ્રતિબંધ મુકાયો',
'blocklist-nousertalk'            => 'તમે પોતાનું ચર્ચા પાનામાં ફેરફાર ન કરી શકો.',
'ipblocklist-empty'               => 'પ્રતિબંધીત યાદિ ખાલી છે.',
'ipblocklist-no-results'          => 'અર્જીત IP સરનામું કે સભ્યનામ પ્રતિબંધિત નથી',
'blocklink'                       => 'પ્રતિબંધ',
'unblocklink'                     => 'પ્રતિબંધ હટાવો',
'change-blocklink'                => 'પ્રતિબંધમાં ફેરફાર કરો',
'contribslink'                    => 'યોગદાન',
'blocklogpage'                    => 'પ્રતિબંધ સૂચિ',
'blocklogentry'                   => '[[$1]] પર પ્રતિબંધ $2 $3 સુધી મુકવામાં આવ્યો છે.',
'unblocklogentry'                 => '$1 પરનો પ્રતિબંધ ઉઠાવ્યો',
'block-log-flags-anononly'        => 'માત્ર અજ્ઞાત સભ્ય',
'block-log-flags-nocreate'        => 'ખાતું ખોલવા પર પ્રતિબંધ છે',
'block-log-flags-noautoblock'     => 'સ્વયંચાલિત પ્રતિબંધ ક્રિયા નિષ્ક્રીય કરાઈ',
'block-log-flags-noemail'         => 'ઇ-મેલ પ્ર પ્રતિબંધ મુકાયો',
'block-log-flags-nousertalk'      => 'તમે પોતાનું ચર્ચા પાનામાં ફેરફાર ન કરી શકો.',
'block-log-flags-angry-autoblock' => 'સુધારિત સ્વયંચાલિત પ્રણાલી સક્રીય કરાઇ',
'block-log-flags-hiddenname'      => 'સભ્યનું નામ છુપાવો',
'ipb_expiry_invalid'              => 'સમાપ્તિનો સમય માન્ય નથી.',
'ipb_expiry_temp'                 => 'સંતાડેલા સભ્યનામ પ્રતિબંધનો કાયમી જ હોવા જોઇએ.',
'ipb_already_blocked'             => ' "$1" પહેલેથી પ્રતિબંધિત છે',
'ipb-needreblock'                 => '== પહેલેથી પ્રતિબંધિત  ==
$1 પહેલેથી પ્રતિબંધિત છે.
તમારે આ સેટીંગ બદલવી છે?',
'ipb-otherblocks-header'          => 'અન્ય {{PLURAL:$1|પ્રતિબંધન|પ્રતિબંધનો}}',
'ipb_cant_unblock'                => 'તૃટિ પ્રતિબંધિત ID $1 ન મળ્યો.
તેપહેલેથી અપ્રતિબંધિત કરાયું હોઇ શકે',
'ip_range_invalid'                => 'અવૈધ IP શ્રેણી',
'blockme'                         => 'મને પ્રતિબંધિત કરો',
'proxyblocker-disabled'           => 'આ સૂત્ર (ફંકશન) નિષ્ક્રીય કરાયો',
'proxyblocksuccess'               => 'સંપન્ન',
'cant-block-while-blocked'        => 'જ્યારે તમે પોતે પ્રતિબંધિત હોવ ત્યારે અન્ય સભ્યોને પ્રતિબંધિત ન કરી શકો',
'ipbblocked'                      => 'તમે અન્ય સભ્યોને પ્રતિબંધિત ન કરી શકો, તમે પોતે પ્રતિબંધિત છો.',
'ipbnounblockself'                => 'તમે પોતાને અપ્રતિબંધિત ન કરી શકો',

# Developer tools
'lockdb'              => 'માહિતીસંચય તાળું વાસો',
'unlockdb'            => 'માહિતીસંચય પરનું તાળું ખોલો',
'lockconfirm'         => 'હા મારે માહિતી સંચય પર તાળું  લગાવવું છે',
'unlockconfirm'       => 'હા મારે માહિતી સંચય ખોલવું છે',
'lockbtn'             => 'માહિતીસંચય પર તાળું વાસો',
'unlockbtn'           => 'માહિતીસંચય પરનું તાળું ખોલો',
'locknoconfirm'       => 'તમે પુષ્ટિ ખાનાને અંકિત નથી કર્યો',
'lockdbsuccesssub'    => 'માહેતીસંચ પરનું તાળું સફળતા પૂર્વક લગાવાયું',
'unlockdbsuccesssub'  => 'માહિતીસંચ પરનું તાળું હટાવાયું',
'unlockdbsuccesstext' => 'આ માહિતી સંચ મુક્ત કરી દેવાયો',
'databasenotlocked'   => 'માહિતીસંચ પરા તાળું નથી લગાવી શકાયું',

# Move page
'move-page'                 => '$1 ખસેડો',
'move-page-legend'          => 'પાનું ખસેડો',
'movepagetext'              => "નીચેનું ફોર્મ વાપરવાથી આ પાનાનું નામ બદલાઇ જશે અને તેમાં રહેલી બધી મહિતિ નવા નામે બનેલાં પાનામાં ખસેડાઇ જશે.
જુનું પાનું, નવા બનેલા પાના તરફ વાળતું થશે.
તમે આવા અન્યત્ર વાળેલાં પનાઓને આપોઆપ જ તેના મુળ શીર્ષક સાથે જોડી શકશો.
જો તમે તેમ કરવા ના ઇચ્છતા હોવ તો, [[Special:DoubleRedirects|બેવડા]] અથવા [[Special:BrokenRedirects|ત્રુટક કડી વાળા]] અન્યત્ર વાળેલા પાનાઓની યાદી ચકાસીને ખાતરી કરી લેશો.
કડી જે પાના પર લઈ જવી જોઈએ તે જ પાના સાથે જોડે તેની ખાતરી કરી લેવી તે તમારી જવાબદારી છે.

એ વાતની નોંધ લેશો કે, જો તમે પસંદ કરેલા નવા નામ વાળું પાનું અસ્તિત્વમાં હશે તો જુનું પાનું '''નહી ખસે''', સિવાયકે તે પાનું ખાલી હોય અથવા તે પણ અન્યત્ર વાળતું પાનું હોય અને તેનો કોઈ ઇતિહાસ ના હોય.
આનો અર્થ એમ થાય છે કે જો તમે કોઈ તબક્કે ભુલ કરશો તો જે પાનાનું નામ બદલવાનો પ્રયત્ન કરતા હોવ તેને તમે ફરી પાછા જુના નામ પર જ પાછું વાળી શકશો, અને બીજું કે પહેલેથી બનેલા પાનાનું નામ તમે નામફેર કરવા માટે ના વાપરી શકો.

'''ચેતવણી!'''
લોકપ્રિય પાનાં સાથે આવું કરવું બિનઅપેક્ષિત અને જોરદાર પરિણામકારક નિવડી શકે છે;
આગળ વધતાં પહેલાં આની અસરોનો પુરે પુરો તાગ મેળવી લેવો આવશ્યક છે.",
'movepagetalktext'          => "આની સાથે સાથે તેનું સંલગ્ન ચર્ચાનું પાનું પણ ખસેડવામાં આવશે, '''સિવાયકે:'''
*નવા નામ વાળું ચર્ચાનું પાનું અસ્તિત્વમાં હોય અને તેમાં લખાણ હોય, અથવા
*નીચેનાં ખાનામાંથી ખરાની નિશાની તમે દૂર કરી હોય.

આ સંજોગોમાં, જો તમે ચાહતા હોવ તો તમારે અહિંનું લખાણ જાતે નવા પાના પર ખસેડવું પડશે.",
'movearticle'               => 'આ પાનાનું નામ બદલો:',
'movenologin'               => 'પ્રવેશ કરેલ નથી',
'movenologintext'           => 'કોઇ પાનું હટાવવા માટે તેમે નોંધણી કૃત સભ્ય અને [[Special:UserLogin|logged in]]  હોવા જોઇએ',
'movenotallowed'            => 'તમને નવા પાના ખસેડવાની પરવાનગી નથી.',
'movenotallowedfile'        => 'તમને ફાઈલ ખસેડવાની પરવાનગી નથી.',
'cant-move-user-page'       => 'તમને સભ્ય પાના હટાવવાની પરવાનગી નથી (ઉપપાના સિવાય).',
'newtitle'                  => 'આ નવું નામ આપો:',
'move-watch'                => 'આ પાનું ધ્યાનમાં રાખો',
'movepagebtn'               => 'પાનું ખસેડો',
'pagemovedsub'              => 'પાનું સફળતા પૂર્વક ખસેડવામાં આવ્યું છે',
'movepage-moved'            => '\'\'\'"$1" નું નામ બદલીને "$2" કરવામાં આવ્યું છે\'\'\'',
'movepage-moved-redirect'   => 'દિશાનિર્દેશના રચાયું',
'movepage-moved-noredirect' => 'દિશા નિર્દેશનોની રચના ને સંતાડી દેવાઇ છે',
'articleexists'             => 'આ નામનું પાનું અસ્તિત્વમાં છે, અથવાતો તમે પસંદ કરેલું નામ અસ્વિકાર્ય છો.
કૃપા કરી અન્ય નામ પસંદ કરો.',
'talkexists'                => "'''મુખ્ય પાનું સફળતાપૂર્વક ખસેડવામાં આવ્યું છે, પરંતુ તેનું ચર્ચાનું પાનું ખસેડી શકાયું નથી, કેમકે નવા શિર્ષક હેઠળ તે પાનું પહેલેથી અસ્તિત્વમાં છે.
કૃપા કરી જાતે તેને નવાં નામ વાળાં પાનાંમાં વિલિન કરો.'''",
'movedto'                   => 'બદલ્યા પછીનું નામ',
'movetalk'                  => 'સંલગ્ન ચર્ચાનું પાનું પણ ખસેડો',
'move-subpages'             => '($1 સુધી) ઉપ-પાના હટાવાયા',
'movepage-page-moved'       => 'પાના $1 ને $2 પર ખસેડાયું',
'movepage-page-unmoved'     => 'પાના $1ને $2 પર ન લઈ જઈ શકાયું',
'1movedto2'                 => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'1movedto2_redir'           => 'નામ બદલતા [[$1]] ને [[$2]] બનાવ્યું',
'move-redirect-suppressed'  => 'દિશા નિર્દેશનો છુપાડાયા',
'movelogpage'               => 'નામ ફેર માહિતિ પત્રક',
'movelogpagetext'           => 'બધા હટાવેલ પાનાની માહિતી',
'movesubpage'               => '{{PLURAL:$1|ઉપપાનું|ઉપપાના}}',
'movesubpagetext'           => 'આ પાનાના $1 {{PLURAL:$1|ઉપપાના |ઉપપાનાઓ }} છે',
'movenosubpage'             => 'આ પાનાના કોઈ ઉપ-પાના નથી',
'movereason'                => 'કારણ:',
'revertmove'                => 'પૂર્વવત',
'delete_and_move'           => 'હટાવો અને નામ બદલો',
'delete_and_move_confirm'   => 'હા, આ પાનું હટાવો',
'delete_and_move_reason'    => 'હટાવવાનું કામ આગળ વધાવવા ભૂંસી દેવાયુ',
'immobile-source-namespace' => '"$1" નામાસ્થળમાં પાના ન ખસેડી શાકાયા',
'immobile-target-namespace' => '"$1" નામાસ્થળમાં પાના ન ખસેડી શાકાયા',
'immobile-source-page'      => 'આ પાનું ખસેડી નહીં શકાય',
'immobile-target-page'      => 'તે લક્ષ્ય શીર્ષક પર ન ખસેડી શકાયા.',
'imagetypemismatch'         => 'નવો ફાઈલ વિસ્તાર શબ્દ તેના પ્રકારાને નથી મળતો',
'imageinvalidfilename'      => 'લક્ષ્ય ફાઈલ અવૈધ છે',
'move-leave-redirect'       => 'પાછળ દિશા સૂચન છોડો',

# Export
'export'            => 'પાનાઓની નિકાસ કરો/પાના અન્યત્ર મોકલો',
'exportcuronly'     => 'માત્ર હાલના ફેરફારો જુઓ , પૂર્ણ ઇતિહાસ નહી.',
'export-submit'     => 'નિકાસ',
'export-addcattext' => 'આ શ્રેણીમાંથી પાના ઉમેરો',
'export-addcat'     => 'ઉમેરો',
'export-addnstext'  => 'નામસ્થળથી પાના ઉમેરો:',
'export-addns'      => 'ઉમેરો',
'export-download'   => 'સાચવો',

# Namespace 8 related
'allmessages'                   => 'તંત્ર સંદેશાઓ',
'allmessagesname'               => 'નામ',
'allmessagesdefault'            => 'મૂળ સંદેશ',
'allmessagescurrent'            => 'વર્તમાન દસ્તાવેજ',
'allmessages-filter-legend'     => 'ચાળણી',
'allmessages-filter-unmodified' => 'અસંપાદિત',
'allmessages-filter-all'        => 'બધા',
'allmessages-filter-modified'   => 'સુધારીત',
'allmessages-prefix'            => 'પૂર્વર્ગ દ્વારા ચાળો',
'allmessages-language'          => 'ભાષા:',
'allmessages-filter-submit'     => 'કરો',

# Thumbnails
'thumbnail-more'           => 'વિસ્તૃત કરો',
'filemissing'              => 'ફાઇલ ગાયબ',
'thumbnail_error'          => 'નાની છબી (થંબનેઇલ-thumbnail) બનાવવામાં ત્રુટિ: $1',
'djvu_page_error'          => 'DjVu પાનું સીમાની બહાર',
'djvu_no_xml'              => 'DjVu ફાઇલ માટે XML લાવવા અસમર્થ',
'thumbnail_invalid_params' => 'અંગુલિ નિર્દેશકના નિર્દેશકો અવૈધ',
'thumbnail_dest_directory' => 'લક્ષ્ય ડીરેક્ટરી રચવા અસમર્થ',
'thumbnail_image-type'     => 'ચિત્રનો આ પ્રકાર અમાન્ય',
'thumbnail_image-missing'  => 'આ ફાઇલ ગુમશુદા : $1',

# Special:Import
'import'                     => 'પાના આયાત કરો',
'importinterwiki'            => 'ટ્રાંસ વિકિ આયાત',
'import-interwiki-source'    => 'સ્ત્રોત વિકિ/પાનું',
'import-interwiki-history'   => 'આ પાના બધા ઐતિહાસીક ફેરફારોની નકલ કરો',
'import-interwiki-templates' => 'બધા ઢાંચા શામિલ કરો',
'import-interwiki-submit'    => 'આયાત કરો',
'import-interwiki-namespace' => 'લક્ષ્ય નામ સ્થળ',
'import-upload-filename'     => 'ફાઇલ નામ',
'import-comment'             => 'ટિપ્પણી:',
'importstart'                => 'આયાત કામ જારી છે....',
'import-revision-count'      => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',
'importnopages'              => 'આયાત કરવા માટે કોઇ પાનું નથી!',
'imported-log-entries'       => 'આયાતી $1 {{PLURAL:$1|log entry|log entries}}.',
'importfailed'               => 'આયાત નિષ્ફળ: <nowiki>$1</nowiki>',
'importunknownsource'        => 'અજ્ઞાત આયાતી સ્ત્રોત પ્રકાર',
'importcantopen'             => 'આયાતી ફાઈલ નાખોલી શકાઈ',
'importbadinterwiki'         => 'ખરાબ આંતરીકા વિકિ કડી',
'importnotext'               => 'ખાલી કે શબ્દ વિહીન',
'importsuccess'              => 'આયાત સંપૂર્ણ',
'importnofile'               => 'કોઇ પણ આયાતી ફાઇલ ન ચડાવી શકાઇ',
'import-noarticle'           => 'આયાત કરવા માટે કોઇ પાનું નથી!',
'import-nonewrevisions'      => 'બધા ફેરફરો પહેલા આયાત કરાયા છે.',
'import-upload'              => 'XML માહિતી ચઢાવો',
'import-token-mismatch'      => 'સત્ર સમાપ્ત
ફરી પ્રયત્ન કરો',
'import-invalid-interwiki'   => 'દર્શાવેલ વિકિ પરથી આયાત નહીં કરી શકાય',

# Import log
'importlogpage'                    => 'આયાત માહિતિ પત્રક',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',
'import-logentry-interwiki-detail' => '$2 થી $1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',

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
'tooltip-ca-unprotect'            => 'આ પાનાની સુરક્ષા હટાવો.',
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
'tooltip-t-specialpages'          => 'બધા ખાસ પાનાઓની સૂચિ',
'tooltip-t-print'                 => 'આ પાનાની છાપવા માટેની આવૃત્તિ',
'tooltip-t-permalink'             => 'પાનાનાં આ પુનરાવર્તનની સ્થાયી કડી',
'tooltip-ca-nstab-main'           => 'સૂચિ વાળું પાનુ જુઓ',
'tooltip-ca-nstab-user'           => 'સભ્યનું પાનું જુઓ',
'tooltip-ca-nstab-media'          => 'મિડિયાનું પાનું જુઓ',
'tooltip-ca-nstab-special'        => 'આ ખાસ પાનું છે, તમે તેમાં ફેરફાર ના કરી શકો',
'tooltip-ca-nstab-project'        => 'પરિયોજનાનું પાનું',
'tooltip-ca-nstab-image'          => 'ફાઇલ વિષેનું પાનું જુઓ',
'tooltip-ca-nstab-mediawiki'      => 'તંત્ર સંદેશ જુઓ',
'tooltip-ca-nstab-template'       => 'ઢાંચો જુઓ',
'tooltip-ca-nstab-help'           => 'મદદનું પાનું જુઓ',
'tooltip-ca-nstab-category'       => 'શ્રેણીઓનું પાનું જુઓ',
'tooltip-minoredit'               => 'આને નાનો ફેરફાર ગણો',
'tooltip-save'                    => 'તમે કરેલાં ફેરફારો સુરક્ષિત કરો',
'tooltip-preview'                 => 'તમે કરેલાં ફેરફારો જોવા મળશે, કૃપા કરી કાર્ય સુરક્ષિત કરતાં પહેલા આ જોઇ લો',
'tooltip-diff'                    => 'તમે માહિતિમાં કયા ફેરફારો કર્યા છે તે જોવા મળશે',
'tooltip-compareselectedversions' => 'અ પાનાનાં પસંદ કરેલા બે વૃત્તાંત વચ્ચેનાં ભેદ જુઓ.',
'tooltip-watch'                   => 'આ પાનાને તમારી ધ્યાનસૂચિમાં ઉમેરો',
'tooltip-upload'                  => 'ફાઇલ ચડાવવાનું શરૂ કરો',
'tooltip-rollback'                => '"પાછું વાળો" એક જ ક્લિકમાં છેલ્લા સભ્યએ આ પાનામાં કરેલા બધા ફેરફારો પાછા વાળશે',
'tooltip-undo'                    => '"રદ કરો" આ ફેરફારને પાછો વાળશે અને ફેરફાર પછીનું પૂર્વાવલોકન ફોર્મ નવા પાના તરીકે ખુલશે.
તે તમને \'સારાંશ\'માં કારણ જણાવવા દેશે.',
'tooltip-preferences-save'        => 'પસંદ સાચવો',
'tooltip-summary'                 => 'ટૂંક સારાંશ ઉમેરો',

# Attribution
'siteuser'      => '{{SITENAME}} સભ્ય $1',
'anonuser'      => '{{SITENAME}} અજ્ઞાત સભ્ય  $1',
'othercontribs' => '$1 ના કાર્ય પર આધારિત',
'others'        => 'અન્યો',
'creditspage'   => 'પાનાના ઋણ સ્વીકાર',

# Info page
'infosubtitle' => 'પાના વિષે માહિતી',
'numedits'     => 'ફેરફારોની સંખ્યા (લેખ): $1',
'numtalkedits' => 'ફેરફારોની સંખ્યા (ચર્ચાનું પાનું): $1',
'numwatchers'  => 'નીરીક્ષકોની સંખ્યા : $1',

# Math errors
'math_failure'          => 'પદચ્છેદ અસફળ',
'math_unknown_error'    => 'અજ્ઞાત ત્રુટિ',
'math_unknown_function' => 'અજ્ઞાત ફંક્શન',

# Patrolling
'markaspatrolledtext'                 => 'આ પાનાને નીરીક્ષિત અંકિત કરો',
'markedaspatrolled'                   => 'નીરીક્ષિત અંકિત કરો',
'rcpatroldisabled'                    => 'હાલના ફેરફારોનું નિરીક્ષણ પ્રતિબંધિત',
'markedaspatrollederror'              => 'આને ચકાસિત અંકિત નથી કરાયું',
'markedaspatrollederror-noautopatrol' => 'તમે તમારા પોતાના ફેરફારોને નીરીક્ષિત અંકિત ન કરી શકો',

# Patrol log
'patrol-log-page'      => 'ચકાસણી લોગ',
'patrol-log-line'      => '$1 માંના $2 ને નીરીક્ષિત  અંકિત કરો  $3',
'patrol-log-auto'      => '(સ્વયંચાલિત)',
'patrol-log-diff'      => 'સુધારો: $1',
'log-show-hide-patrol' => '$1 ચકાસણી લોગ',

# Image deletion
'deletedrevision'       => 'જુના સુધારા ભૂસો $1',
'filedeleteerror-short' => 'ફાઇલ : $1 ભૂંસવામાં તૃટિ',
'filedeleteerror-long'  => '$1 આ ફાઈલ ભૂંસતી વખતે ચૂક થઈ',

# Browsing diffs
'previousdiff' => '← પહેલાનો ફેરફાર',
'nextdiff'     => 'પછીનો ફેરફાર →',

# Media information
'thumbsize'            => 'લઘુચિત્ર કદ',
'file-info-size'       => '$1 × $2 પીક્સલ, ફાઇલનું કદ: $3, MIME પ્રકાર: $4',
'file-nohires'         => '<small>આથી વધુ આવર્તન ઉપલબ્ધ નથી.</small>',
'svg-long-desc'        => 'SVG ફાઇલ, માત્ર $1 × $2 પીક્સલ, ફાઇલનું કદ: $3',
'show-big-image'       => 'મહત્તમ આવર્તન',
'show-big-image-thumb' => '<small>આ પુર્વાવલોકનનું પરિમાણ: $1 × $2 પીક્સલ</small>',
'file-info-gif-looped' => 'આવર્તન  (લુપ)',
'file-info-gif-frames' => ' $1 {{PLURAL:$1|છબી|છબીઓ}}',
'file-info-png-looped' => 'આવર્તન',
'file-info-png-repeat' => '$1 {{PLURAL:$1|વખત|વખત}} કરાયું',
'file-info-png-frames' => '$1 {{PLURAL:$1|છ્બી|છબીઓ}}',

# Special:NewFiles
'newimages'             => 'નવી ફાઇલોની ઝાંખી',
'newimages-legend'      => 'ચાળણી',
'newimages-label'       => 'ફાઈલનામ (કે તેનો ભાગ)',
'noimages'              => 'જોવા માટે કશું નથી.',
'ilsubmit'              => 'શોધો',
'bydate'                => 'તારીખ પ્રમાણે',
'sp-newimages-showfrom' => '$2, $1 થી શરૂ થતી ફાઇલો બતાવો',

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
'exif-imagewidth'                => 'પહોળાઈ',
'exif-imagelength'               => 'ઊંચાઈ',
'exif-compression'               => 'સંકોચન પ્રણાલી',
'exif-photometricinterpretation' => 'Pixel સંરચના',
'exif-orientation'               => 'દિશામાન્',
'exif-planarconfiguration'       => 'માહિતી ગોઠવણ',
'exif-transferfunction'          => 'સ્થળાંતર કાર્ય',
'exif-datetime'                  => 'ફાઇલ સુધારાની તારીખ અને સમય',
'exif-imagedescription'          => 'ચિત્ર શીર્ષક',
'exif-model'                     => 'કેમેરાનું મોડેલ',
'exif-artist'                    => 'કલાકાર',
'exif-exifversion'               => 'Exif આવૃત્તિ',
'exif-colorspace'                => 'રંગ માટે જગ્યા',
'exif-pixelydimension'           => 'ચિત્ર માટે વૈધ પહોળાઇ',
'exif-pixelxdimension'           => 'ચિત્રની વૈધ ઊઁચાઈ',
'exif-usercomment'               => 'સભ્યની ટિપ્પણી',
'exif-relatedsoundfile'          => 'સંબંધિત શ્રાવ્ય ફાઈલો',
'exif-datetimeoriginal'          => 'નિર્મિતીનો સમય અને તારીખ',
'exif-exposuretime'              => 'પ્રકાશાગમ સમય',
'exif-exposuretime-format'       => ' $1 સેકંડ ($2)',
'exif-fnumber'                   => 'F ક્રમ',
'exif-aperturevalue'             => 'છીદ્ર માપ',
'exif-brightnessvalue'           => 'તેજસ્વીતા',
'exif-subjectdistance'           => 'વસ્તુનું અંતર',
'exif-lightsource'               => 'પ્રકાશા સ્ત્રોત',
'exif-flash'                     => 'જબકારો (ફ્લેશ)',
'exif-subjectlocation'           => 'વસ્તુનું સ્થાન',
'exif-filesource'                => 'ફાઇલ સ્ત્રોત',
'exif-whitebalance'              => 'ધવલ સમતોલન',
'exif-digitalzoomratio'          => 'ડીજીટલ ઝુમ પ્રમાણ',
'exif-gaincontrol'               => 'દ્રશ્ય નિયંત્રણ',
'exif-contrast'                  => 'રંગછટા',
'exif-saturation'                => 'સંતૃપ્તતા',
'exif-sharpness'                 => 'તીવ્રતા',
'exif-gpslatitude'               => 'અક્ષાંસ',
'exif-gpslongitude'              => 'રેખાંશ',
'exif-gpssatellites'             => 'માપન માટે વપરાયેલ ઉપગ્રહ',
'exif-gpsstatus'                 => 'ગ્રાહકની સ્થિતિ',
'exif-gpsdop'                    => 'માપન ચોકસાઈ',
'exif-gpsspeedref'               => 'ઝડપનું એકમ',
'exif-gpsimgdirection'           => 'ચિત્રની દિશા',
'exif-gpsmapdatum'               => 'ભૂમાપન સર્વેક્ષણ માહિતી વપરાઇ',
'exif-gpsdestlatitude'           => 'અક્ષાંસ સ્થળ',
'exif-gpsdestlongitude'          => 'રેખાંશ સ્થળ',

'exif-unknowndate' => 'અજ્ઞાત તારીખ',

'exif-orientation-1' => 'સામાન્ય',
'exif-orientation-3' => '૧૮૦° ફેરવો',
'exif-orientation-6' => 'ઘડિયાળની દિશામાં ૯૦° ફેરવો',
'exif-orientation-7' => ' ઘડિયાળની દિશામાં ૯૦° ફેરવો અને ઊભી દિશામાં પ્રતિબિંબિત કરો',
'exif-orientation-8' => '૯૦° ઉલટ ઘડિયાળ દિશામાં ફેરવો',

'exif-componentsconfiguration-0' => 'નથી',

'exif-exposureprogram-1' => 'માનવ ચાલિત',
'exif-exposureprogram-2' => 'સામાન્ય પ્રણાલી',
'exif-exposureprogram-4' => 'શટર અગ્રતા',

'exif-subjectdistance-value' => '$1 મીટર',

'exif-meteringmode-0'   => 'અજાણ્યો',
'exif-meteringmode-1'   => 'સરાસરી',
'exif-meteringmode-5'   => 'ભાત',
'exif-meteringmode-6'   => 'આશિંક',
'exif-meteringmode-255' => 'બીજું કઈ',

'exif-lightsource-0'   => 'અજાણ્યો',
'exif-lightsource-1'   => 'દિવસ પ્રકાશ',
'exif-lightsource-4'   => 'જબકારો (ફ્લેશ)',
'exif-lightsource-9'   => 'સામાન્ય વાતાવરણ',
'exif-lightsource-10'  => 'વાદળીયું વાતાવરણ',
'exif-lightsource-11'  => 'છાયા',
'exif-lightsource-17'  => 'પ્રમાણભૂત પ્રકાશ A',
'exif-lightsource-18'  => 'પ્રમાણભૂત પ્રકાશ B',
'exif-lightsource-19'  => 'પ્રમાણભૂત પ્રકાશ C',
'exif-lightsource-255' => 'પ્રકાશના અન્ય સ્ત્રોત',

# Flash modes
'exif-flash-fired-0' => 'પ્રકાશ ઝબકારો ન થયો',
'exif-flash-fired-1' => 'ઝબકારો કરાયો',

'exif-focalplaneresolutionunit-2' => 'ઈંચ',

'exif-scenecapturetype-3' => 'રાત્રી દર્શન',

'exif-gaincontrol-0' => 'જરાપણ નહી',

'exif-contrast-0' => 'સામાન્ય',
'exif-contrast-1' => 'Soft',
'exif-contrast-2' => 'તીવ્ર',

'exif-saturation-0' => 'સામાન્ય',
'exif-saturation-1' => 'અલ્પ સંતૃપ્તિ',
'exif-saturation-2' => 'અધિક સંતૃપ્તિ',

'exif-sharpness-0' => 'સામાન્ય',
'exif-sharpness-1' => 'સૌમ્ય',
'exif-sharpness-2' => 'તીવ્ર',

'exif-subjectdistancerange-0' => 'અજાણ્યો',
'exif-subjectdistancerange-2' => 'નજીક દર્શન',
'exif-subjectdistancerange-3' => 'દૂરનું દ્રશ્ય',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ઉત્તર અક્ષાંસ',
'exif-gpslatitude-s' => 'દક્ષિણ અક્ષાંસ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'પૂર્વ રેખાંશ',
'exif-gpslongitude-w' => 'પશ્ચિમ રેખાંશ',

'exif-gpsstatus-a' => 'માપન કાર્ય જારી',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'કિમી પ્રતિ કલાક',
'exif-gpsspeed-m' => 'માઇલ પ્રતિ કલાક',
'exif-gpsspeed-n' => 'નોટ્સ્',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'વાસ્તવિક દિશા',
'exif-gpsdirection-m' => 'ચુંબકીય દિશા',

# External editor support
'edit-externally'      => 'બાહ્ય સોફ્ટવેર વાપરીને આ ફાઇલમાં ફેરફાર કરો',
'edit-externally-help' => '(વધુ માહિતિ માટે [http://www.mediawiki.org/wiki/Manual:External_editors સેટ-અપ સુચનાઓ] જુઓ)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'બધા',
'imagelistall'     => 'બધા',
'watchlistall2'    => 'બધા',
'namespacesall'    => 'બધા',
'monthsall'        => 'બધા',
'limitall'         => 'બધા',

# E-mail address confirmation
'confirmemail'             => 'તમારા ઇ-મેઇલ સરનામાની પુષ્ટિ કરો',
'confirmemail_send'        => 'ઈ-મેલ બહાલી સંકેત ઇ-મેલમાં મોકલો',
'confirmemail_sent'        => 'બહાલીનો ઇ-મેલ મોકલી દેવાયો છે',
'confirmemail_invalid'     => 'અવૈધ બહાલી સંકેત
સંકેત કાલાતિત થયું હોય',
'confirmemail_needlogin'   => 'તમારે ઈ-મેલ ને બહાલી આપવા $1 કરવું પડશે',
'confirmemail_loggedin'    => 'તમારા ઇ-મેલ સરનામાની પુષ્ટિ કરાઇ છે.',
'confirmemail_error'       => 'પુષ્ટિ સાચવતા કોઈ ત્રુટિ રહી ગઈ',
'confirmemail_subject'     => '{{SITENAME}} ઈ-મેલ સરનામાની પુષ્ટિ',
'confirmemail_invalidated' => 'ઇ-મેલ સરનામાનું બહાલીકરણ રદ્દ',
'invalidateemail'          => 'ઇ-મેલ બહાલી રદ્દ કરો',

# Scary transclusion
'scarytranscludetoolong' => '[URL ઘણો લાંબો છે]',

# Trackbacks
'trackbackremove' => '([$1 ભૂંસો])',
'trackbacklink'   => 'ટ્રેકબેક',

# Delete conflict
'recreate' => 'પુનર્નિર્માણ કરો',

# action=purge
'confirm_purge_button' => 'મંજૂર',

# Multipage image navigation
'imgmultipageprev' => '← પાછલું પાનું',
'imgmultipagenext' => 'આગલું પાનું →',
'imgmultigo'       => 'જાઓ!',
'imgmultigoto'     => 'પાના  $1 પર જાવ',

# Table pager
'ascending_abbrev'         => 'ચડતો ક્ર્મ',
'descending_abbrev'        => 'ઉતરતો ક્ર્મ',
'table_pager_next'         => 'આગળનું પાનું',
'table_pager_prev'         => 'પાછળનું પાનું',
'table_pager_first'        => 'પહેલું પાનું',
'table_pager_last'         => 'છેલ્લૂં પાનું',
'table_pager_limit'        => 'પ્રતિ પાને $1 વસ્તુ બતાવો',
'table_pager_limit_submit' => 'જાઓ',
'table_pager_empty'        => 'કોઇ પરિણામ નથી',

# Auto-summaries
'autosumm-blank' => 'પાનું ખાલી કરી દેવાયું',
'autosumm-new'   => '$1થી શરૂ થતું નવું પાનું બાનવ્યું',

# Live preview
'livepreview-failed' => 'સજીવ ઝલક અસફળ
સામાન્ય ઝલક જુઓ',
'livepreview-error'  => ' $1 "$2" નો સંપર્ક અસફળ
સામાન્ય ઝલક જુઓ',

# Watchlist editor
'watchlistedit-normal-title'  => 'ધ્યાનસૂચિ માં ફેરફાર કરો',
'watchlistedit-normal-legend' => 'ધ્યાનસૂચિમાંથી આશીર્ષકો કાઢી નાખો',
'watchlistedit-normal-submit' => 'શીર્ષકો હટાવો',
'watchlistedit-raw-title'     => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',
'watchlistedit-raw-legend'    => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',
'watchlistedit-raw-titles'    => 'શિર્ષક:',
'watchlistedit-raw-submit'    => 'ધ્યાનસૂચિ અધ્યતન બનાવો',

# Watchlist editing tools
'watchlisttools-view' => 'બંધબેસતાં ફેરફારો નિહાળો',
'watchlisttools-edit' => 'ધ્યાનસૂચી જુઓ અને બદલો',
'watchlisttools-raw'  => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',

# Special:Version
'version'                  => 'આવૃત્તિ',
'version-specialpages'     => 'ખાસ પાના',
'version-parserhooks'      => 'પદચ્છેદ ખૂંટો',
'version-variables'        => 'સહગુણકો',
'version-other'            => 'અન્ય',
'version-hooks'            => 'ખૂંટા',
'version-hook-name'        => 'ખૂંટાનું નામ્',
'version-version'          => '(આવૃત્તિ $1)',
'version-license'          => 'પરવાનો',
'version-poweredby-others' => 'અન્યો',
'version-software-product' => 'ઉત્પાદ',
'version-software-version' => 'આવૃત્તિ',

# Special:FilePath
'filepath'        => 'ફાઈલ પથ',
'filepath-page'   => 'ફાઇલ:',
'filepath-submit' => 'કરો',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'નકલ ફાઇલ શોધો',
'fileduplicatesearch-legend'   => 'નકલ શોધો',
'fileduplicatesearch-filename' => 'ફાઇલ નામ',
'fileduplicatesearch-submit'   => 'શોધ',

# Special:SpecialPages
'specialpages'                   => 'ખાસ પાનાં',
'specialpages-group-maintenance' => 'સમારકામ અહેવાલ',
'specialpages-group-other'       => 'અન્ય ખાસ પાનાઓ',
'specialpages-group-login'       => 'પ્રવેશ / નોંધણી કરો',
'specialpages-group-changes'     => 'હાલના ફેરફારો અને લોગ',
'specialpages-group-media'       => 'મિડિયા રિપોર્ટ અને ચડાવેલી ફાઇલ',
'specialpages-group-users'       => 'સભ્યો અને અધિકારો',
'specialpages-group-highuse'     => 'વધુ વપરાશ ધરાવતા પાના',
'specialpages-group-pages'       => 'પાનાની યાદીઓ',
'specialpages-group-pagetools'   => 'પાના સાધનો',
'specialpages-group-wiki'        => 'વિકિ માહિતીસંચ અને સાધનો',
'specialpages-group-spam'        => 'સ્પેમ સાધનો',

# Special:BlankPage
'blankpage'              => 'કોરું પાનું',
'intentionallyblankpage' => 'આપાનું જાણે કરીને કોરું રખાયું છે',

# Special:Tags
'tag-filter'              => '[[Special:Tags|Tag]] ચાળણી',
'tag-filter-submit'       => 'ચાળણી',
'tags-title'              => 'નાકા',
'tags-tag'                => 'નાકાનું નામ',
'tags-display-header'     => 'ફેરફારની યાદિમાં અવતરણ',
'tags-description-header' => 'અર્થનું પૂર્ણ વિવરણ',
'tags-hitcount-header'    => 'અંકિત ફેરફાર',
'tags-edit'               => 'ફેરફાર કરો',
'tags-hitcount'           => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',

# Special:ComparePages
'comparepages'     => 'પાનાં સરખાવો',
'compare-selector' => 'પાનાનાં પુનરાવર્તન સરખાવો',
'compare-page1'    => 'પાનું ૧',
'compare-page2'    => 'પાનું ૨',
'compare-rev1'     => 'પુનરાવર્તન ૧',
'compare-rev2'     => 'પુનરાવર્તન ૨',
'compare-submit'   => 'સરખાવો',

# Database error messages
'dberr-header'    => 'આ વિકિમાં તકલીફ છે',
'dberr-problems'  => 'દિલગીરી!
આ સાઇટ તકનિકી અડચણ અનુભવી રહી છે.',
'dberr-again'     => 'થોડી વાર રાહ જોઈને ફરી પેજ લોડ કરવાનો પ્રયત્ન કરો.',
'dberr-info'      => '(માહિતી સંચય સર્વર : $1નો સંપર્ક નથી કરી શકાયો)',
'dberr-usegoogle' => 'તેસમયા દરમ્યાન તમે ગુગલ દ્વારા શોધી શકો',

# HTML forms
'htmlform-invalid-input'       => 'તમારા અમુક ઉમેરા માંઅમુક તકલીફ છે',
'htmlform-select-badoption'    => 'તમે બતાવેલ વિકલ્પ અવૈધ છે',
'htmlform-int-invalid'         => 'તમે લખેલ વિકલ્પ અંક નથી',
'htmlform-float-invalid'       => 'તમે લખેલ વિકલ્પ અંક નથી',
'htmlform-int-toolow'          => '$1 આ કિંમત આ ન્યૂનતમ કિંમત છે',
'htmlform-required'            => 'આ કિઁમત જોઈએ છે',
'htmlform-submit'              => 'જમા કરો',
'htmlform-reset'               => 'ફેરફાર ઉલટાવો',
'htmlform-selectorother-other' => 'અન્ય',

# SQLite database support
'sqlite-has-fts' => '$1 પૂર્ણ શબ્દ શોધ સહીત',
'sqlite-no-fts'  => '$1 પૂર્ણ શબ્દ  શોધ વિકલ્પ વગર',

# Special:DisableAccount
'disableaccount'             => 'સભ્યના ખાતા પરપ્રતિબંધ મૂકો',
'disableaccount-user'        => 'સભ્ય નામ:',
'disableaccount-reason'      => 'કારણ:',
'disableaccount-mustconfirm' => 'તમારે પુષ્ટિ કરવી પડશે કે તમે ખાતાને નિષ્ક્રીય કરવા માંગો છો.',
'disableaccount-nosuchuser'  => 'સભ્ય ખાતું "$1" અસ્તિત્વમાં નથી',
'disableaccount-success'     => 'સભ્ય ખાતું "$1" કાયમી ધોરણે પ્રતિબંધીત કરાયું',
'disableaccount-logentry'    => ' સભ્ય ખાતું [[$1]] કાયમી ધોરણે નિષ્ક્રીય બનાવાયું',

);
