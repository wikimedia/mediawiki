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
	'Block'                     => array( 'પ્રતિબંધ', 'IP_પર_પ્રતિબંધ', 'સભ્યપર_પ્રતિબંધ' ),
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
'copyright'            => 'માહિતી $1 હેઠળ ઉપલબ્ધ છે.',
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
'dberrortext'          => 'માહિતીસંચ ને અપાયેલ શોધના સૂત્રમાં ચૂક છે.
આ સોફ્ટવેરમાં માં નાની  ત્રુટિ (bug) ને લીધે હોઇ શકે.
માહિતીસંચ પર કરાયેલ છેલ્લામાં છેલ્લી શોધ આ પ્રમાણે હતી:
<blockquote><tt>$1</tt></blockquote>
આ ફંકશન થકી  "<tt>$2</tt>".
માહિતીસંચે આપેલ ચૂકનું વિવરણ "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'માહિતીસંચ ને અપાયેલ શોધના સૂત્રમાં ચૂક છે.
માહિતીસંચ પર કરાયેલ છેલ્લામાં છેલ્લી શોધ આ પ્રમાણે હતી:
"$1"
આ ફંકશન થકી "$2".
માહિતીસંચે આપેલ ચૂકનું વિવરણ  "$3: $4"',
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
'readonly_lag'         => 'કનિષ્ઠ માહિતીસંચ સર્વરો મુખ્ય સર્વર સુધી પહોંચી ત્યા સુધી આ માહિતીસંચ પર સ્વયંચલિત રીતે તાળું મરાયું છે',
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
'wrong_wfQuery_params' => 'wfQuery() માટે અયોગ્ય વિકલ્પો<br />
Function: $1<br />
Query: $2',
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
'cascadeprotected'     => 'આ પાના પર ફેરફાર પ્રતિબંધિત છે કેમ કે આ પાનું  {{PLURAL:$1|એવું પાનું|એવા પાના}} માં શામિલ છે જેમાં અનુવર્તી (પગથિયામય)સંરક્ષણ સક્રીય છે :
$2',
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
'logouttext'                 => "'''તમે (લોગ આઉટ કરીને) બહાર નિકળી ચુક્યા છો.'''

તમે અનામી તરીકે {{SITENAME}} વાપરવાનું ચાલુ રાખી શકો છો, કે પછી તેના તે જ કે અલગ સભ્ય તરીકે [[Special:UserLogin|ફરી પ્રવેશ]] કરી શકો છો.
ધ્યાન રાખો કે જ્યાં સુધી તમે તમારા બ્રાઉઝરનો  કૅશ સાફ નહીં કરો ત્યાં સુધી કેટલાક પાનાં તમે પ્રવેશી ચુક્યા છો તેમ બતાવશે.",
'welcomecreation'            => '== તમારૂં સ્વાગત છે $1! ==
તમારૂં ખાતું બની ગયું છે.
તમારી [[Special:Preferences|{{SITENAME}} પસંદ]] બદલવાનું ભૂલશો નહીં.',
'yourname'                   => 'સભ્ય નામ:',
'yourpassword'               => 'ગુપ્ત સંજ્ઞા:',
'yourpasswordagain'          => 'ગુપ્ત સંજ્ઞા (પાસવર્ડ) ફરી લખો',
'remembermypassword'         => 'આ કોમ્યૂટર પર મારી લૉગ ઇન વિગતો ધ્યાનમાં રાખો (વધુમાં વધુ $1 {{PLURAL:$1|દિવસ|દિવસ}} માટે)',
'securelogin-stick-https'    => 'લોગ-ઈન કર્યા પછી  HTTPS સાથે જોડાયેલા રહો.',
'yourdomainname'             => 'તમારૂં ડોમેઇન:',
'externaldberror'            => 'પ્રમાણભૂતતાની ત્રુટી આવી અથવા તમારૂ બહારનુ ખાતું અપડેટ કરવાનો અધિકાર તમને નથી.',
'login'                      => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'nav-login-createaccount'    => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'loginprompt'                => '{{SITENAME}}માં પ્રવેશ કરવા માટે તમારા બ્રાઉઝરમાં કુકીઝ એનેબલ કરેલી હોવી જોઇશે.',
'userlogin'                  => 'પ્રવેશ કરો / નવું ખાતું ખોલો',
'userloginnocreate'          => 'પ્રવેશ કરો (લૉગ ઇન કરીને)',
'logout'                     => 'બહાર નીકળો',
'userlogout'                 => 'બહાર નીકળો/લૉગ આઉટ',
'notloggedin'                => 'પ્રવેશ કરેલ નથી',
'nologin'                    => "શું તમારૂં ખાતું નથી? તો નવું '''$1'''.",
'nologinlink'                => 'ખાતું ખોલો',
'createaccount'              => 'નવું ખાતું ખોલો',
'gotaccount'                 => "પહેલેથી ખાતું ખોલેલું છે? '''$1'''.",
'gotaccountlink'             => 'પ્રવેશો (લૉગ ઇન કરો)',
'createaccountmail'          => 'ઇ-મેઇલ દ્વારા',
'createaccountreason'        => 'કારણ:',
'badretype'                  => 'તમે દાખલ કરેલ ગુપ્તસંજ્ઞા મળતી આવતી નથી.',
'userexists'                 => 'દાખલ કરેલું સભ્ય નામ વપરાશમાં છે.</br>
કૃપયા અન્ય નામ પસંદ કરો.',
'loginerror'                 => 'પ્રવેશ ત્રુટિ',
'createaccounterror'         => 'ખાતું ખોલી શકાયું નથી: $1',
'nocookiesnew'               => 'તમારુ સભ્ય ખાતું બની ગયું છે પણ તમે પ્રવેશ (લોગ ઇન) કર્યો નથી.

{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.

કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'nocookieslogin'             => '{{SITENAME}} કુકીઝ સિવાય પ્રવેશ કરવા નહીં દે.
તમે કુકીઝ બંધ કરી છે.
કૃપા કરી કુકીઝ ચાલુ કરીને તમારા સભ્યનામ સાથે પ્રવેશ કરો.',
'nocookiesfornew'            => 'સભ્ય ખાતાની પુષ્ટિ નથી થઇ, અમે તેના સ્ત્રોતની પુષ્ટિ ન કરી શક્યા.
ખાત્રી કરો કે તમે કુકીઝ સક્રીય કરી છે, અને પાનું ફરીથી ચડાવો',
'noname'                     => 'તમે પ્રમાણભૂત સભ્યનામ જણાવેલ નથી.',
'loginsuccesstitle'          => 'પ્રવેશ સફળ',
'loginsuccess'               => "'''તમે હવે {{SITENAME}}માં \"\$1\" તરીકે પ્રવેશી ચુક્યા છો.'''",
'nosuchuser'                 => '"$1" નામ ધરાવતો કોઇ સભ્ય અસ્તિત્વમાં નથી.

સભ્યનામો અક્ષરસંવેદી (કેસ સેન્સિટીવ) હોય છે.

કૃપા કરી સ્પેલીંગ/જોડણી ચકાસો અથવા [[Special:UserLogin/signup|નવું ખાતુ ખોલો]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" નામનો કોઇ સભ્ય નથી, તમારી જોડણી તપાસો.',
'nouserspecified'            => 'તમારે સભ્ય નામ દર્શાવવાની જરૂર છે.',
'login-userblocked'          => 'આ યુઝર પ્રતિબંધિત છે. પ્રવેશ વર્જીત.',
'wrongpassword'              => 'તમે લખેલી ગુપ્ત સંજ્ઞા ખોટી છે.
ફરીથી પ્રયત્ન કરો.',
'wrongpasswordempty'         => 'તમે ગુપ્ત સંજ્ઞા લખવાનું ભુલી ગયા લાગો છો.
ફરીથી પ્રયત્ન કરો.',
'passwordtooshort'           => 'ગુપ્ત સંજ્ઞામાં ઓછામાં {{PLURAL:$1|ઓછો એક અક્ષર હોવો |ઓછા $1 અક્ષર હોવા}} જોઇએ.',
'password-name-match'        => 'તમારી ગુપ્તસંજ્ઞા તમારા સભ્યનામ કરતાં અલગ જ હોવી જોઇએ.',
'password-login-forbidden'   => 'આ સભ્યનામ અને ગુપ્તસંજ્ઞા વાપરવા પર પ્રતિબંધ છે.',
'mailmypassword'             => 'પાસવર્ડ ઇ-મેલમાં મોકલો',
'passwordremindertitle'      => '{{SITENAME}} માટેની નવી કામચલાઉ ગુપ્ત સંજ્ઞા',
'passwordremindertext'       => 'કોઇકે (કદાચ તમે IP એડ્રેસ $1 પરથી) {{SITENAME}} ($4) માટે નવી ગુપ્ત સજ્ઞા (પાસવર્ડ) માટે વિનંતી કરેલ છે.
હંગામી ધોરણે સભ્ય "$2" માટે ગુપ્ત સંજ્ઞા બની છે અને તે "$3". જો તમે જ આ વિનંતી કરી હોય અને તમે ગુપ્ત સંજ્ઞા બદલવા માંગતા હો તો તમારે પ્રવેશ કરવો પડશે અને નવી ગુપ્ત સંજ્ઞા પસંદ કરવી પડશે. હંગામી ગુપ્ત સંજ્ઞાની અવધિ {{PLURAL:$5|એક દિવસ|$5 દિવસો}} છે ત્યાર બાદ તે કામ નહીં કરે.

જો બીજા કોઇએ આ વિનંતી કરી હોય અથવા તમને તમારી જુની ગુપ્ત સંજ્ઞા યાદ આવી ગઇ હોય અને તમે તે બદલવા ન માંગતા હો તો આ સંદેશ અવગણીને તમારી જુની ગુપ્ત સંજ્ઞા વાપરવાનું ચાલુ રાખો.',
'noemail'                    => 'સભ્ય "$1"નું કોઇ ઇ-મેલ સરનામું નોંધાયેલું નથી.',
'noemailcreate'              => 'વૈધ ઇ-મેલ આપશો',
'passwordsent'               => 'A new password has been sent to the e-mail address registered for "$1".
Please log in again after you receive it.
"$1" ની નવી ગુપ્તસંજ્ઞા (પાસવર્ડ) આપના ઇમેઇલ પર મોકલવામાં આવ્યો છે.
કૃપા કરી તે મળ્યા બાદ ફરી લોગ ઇન કરો.',
'blocked-mailpassword'       => 'Your IP address is blocked from editing, and so is not allowed to use the password recovery function to prevent abuse.
ફેરફાર કરવા માટે તમારું IP એડ્રેસ  સ્થગિત કરી દેવાયું છે તેથી દૂરુપયોગ ટાળવા માટે તમને ગુપ્તસંજ્ઞા રીકવરી કરવાની છૂટ નથી.',
'eauthentsent'               => 'પુષ્ટિ કરવા માટે તમે આપેલા સરનામાં પર ઇમેઇલ મોકલવામાં આવ્યો છે.
એ જ સરનામે બીજો ઇમેઇલ થતાં પહેલાં તમારે ઇમેઇલમાં લખેલી સૂચનાઓ પ્રમાણે કરવું પડશે જેથી એ પુષ્ટિ થઇ શકે કે આપેલું સરનામું તમારું છે.',
'throttled-mailpassword'     => 'ગુપ્ત સંજ્ઞા યાદ અપાવતી ઇમેઇલ છેલ્લા {{PLURAL:$1|કલાક|$1 કલાકમાં}} મોકલેલી છે.
દૂરુપયોગ રોકવા માટે, {{PLURAL:$1|કલાક|$1 કલાકમાં}} ફક્ત એક જ આવી મેઇલ કરવામાં આવે છે.',
'mailerror'                  => 'મેઇલ મોકલવામાં ત્રુટિ: $1',
'acct_creation_throttle_hit' => 'આ વિકિના મુલાકાતીઓએ તમારું IP વાપરીને ગઈ કાલે {{PLURAL:$1|1 ખાતું |$1 ખાતા}} ખોલ્યાં છે,જે પ્રવાનગીની મહત્તમ સંખ્યા છે. આને પરિણામે મુલાકાતી આ ક્ષણેવધુ ખાતા નહીં ખોલી શકે.',
'emailauthenticated'         => 'તમારૂં ઇ-મેઇલ સરનામું $2 ના $3 સમયે પ્રમાણિત કરેલું છે.',
'emailnotauthenticated'      => 'તમારૂં ઇ-મેઇલ સરનામું હજુ સુધી પ્રમાણિત થયેલું નથી.

નિમ્નલિખિત વિશેષતાઓમાંથી કોઇ માટે ઇ-મેઇલ મોકલવામાં આવશે નહીં.',
'noemailprefs'               => "આ વિશેષતાઓ કાર્ય કરી શકે તે માટે 'તમારી પસંદ'માં ઇ-મેઇલ સરનામું દર્શાવો.",
'emailconfirmlink'           => 'તમારા ઇ-મેઇલ સરનામાની પુષ્ટિ કરો',
'invalidemailaddress'        => 'આ ઈ-મેલ અયોગ્ય માળખું ધરાવ્હે છે  માટે સ્વીકારી શકાશે નહીં
કૃપયા યોગ્ય માળખામાં ઇ-મેલ લખો',
'accountcreated'             => 'ખાતું ખોલવામાં આવ્યું છે',
'accountcreatedtext'         => '$1 માટે સભ્ય ખાતુ બનાવ્યું.',
'createaccount-title'        => '{{SITENAME}} માટે ખાતુ બનાવ્યું',
'createaccount-text'         => 'કોઇકે {{SITENAME}} ($4) પર, નામ "$2", ગુપ્તસંજ્ઞા "$3", શાથે તમારા ઇ-મેઇલ એડ્રેસ માટે ખાતુ બનાવેલ છે.

તમે હવે પ્રવેશ કરી અને ગુપ્તસંજ્ઞા બદલી શકો છો.

જો આ ખાતુ ભુલથી બનેલું હોય તો,આ સંદેશને અવગણી શકો છો.',
'usernamehasherror'          => 'સભ્યનામમાં ગડબડિયા ચિહ્નો ન હોઈ શકે',
'login-throttled'            => 'તમે હાલમાં જ ઘણા પ્રવેશ પ્રયત્નો કર્યા.
કૃપા કરી ફરી પ્રયાસ પહેલાં થોડી રાહ જુઓ.',
'loginlanguagelabel'         => 'ભાષા: $1',
'suspicious-userlogout'      => 'લોગ આઉટ કરવાની તમારી વિનંતિ પૂરી ન કરી શકાઇ. એમ લાગે છે કે તેને તૃટિ પામેલ બ્રાઉઝર કે પ્રોક્સી દ્વારા મોકલાઈ હતી.',

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
'resetpass-wrong-oldpass'   => 'અવૈધ હંગામે કે કાયમી ગુપ્તસંજ્ઞા.
કદાચ તમે પહેલેથી સફળતા પૂર્વક તમારી ગુપ્ત સંજ્ઞા બદલી દીધી હોય કે નવી ગુપ્ત સંંજ્ઞામાટે વિનંતિ કરી હોય',
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
'anonpreviewwarning'               => 'તમે સભ્યનામથી પ્રવેશ કર્યો નથી,આ પાનું ઈતિહાસમાંતમારા IP સરનામાના નામે  સાચવવામાં આવશે',
'missingsummary'                   => "'''ચેતવણી:''' તમે ફેરફારનો સારંશ નથી આપ્યો.
જો તમે \"{{int:savearticle}}\"  પર ક્લીક કરશો તો તમરો ફેરફારા સારાઁશાવગરાસાચવવામાં આવશે",
'missingcommenttext'               => 'કૃપા કરી નીચે ટીપ્પણી લખો.',
'missingcommentheader'             => "'''યાદ દેવડાવું:'''તમે તમારી ટિપ્પણી ને શીર્ષક/મથાળુ આપ્યું નથી. 
જો તમે  \"{{int:savearticle}}\" પર ફરીથી ક્લિક કરશો, તો તમારા ફેરરારો મથાળા વગર સચવાશે.",
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
'autoblockedtext'                  => 'તમારું  IP સરનામા પર સ્વયં ચાલિત રીતે રોક લગાવી દેવાઈ છે કેમકે તેને અન્ય સભ્ય દ્વારા વાપરવામાં આવ્યું હતું જેના પર  $1 દ્વારા 
 પહેલેથી રોક લગાવાઇ છે.
:\'\'$2\'\'

* રોકની શરુઆત : $8
* રોકની સમાપ્તિ તિથી : $6
* પ્રસ્તાવિત રોક સહન કરનાર : $7

તમે $1 અથવા  કોઇ અન્ય  [[{{MediaWiki:Grouppage-sysop}}|પ્રબંધક ]] નો સંપર્ક કરી રોકની ચર્ચા કરી શકો.

નોંધો કે તેમે "આ સભ્યને ઈ-મેલ કરો" વિકલ્પના વાપરી શકો સિવાયકે તમરી પાસે તમારા [[Special:Preferences|user preferences]] માં વૈધ ઈ-મેલ સરનામું હોય અનેતેવાપરી તમરા પર રોક લગાડવામાં આવી ન હોય.

તમારું હાલનું   IP સરનામું  $3 છે, અને રોકની  ID છે  #$5.
આ માહિતી તમારી પૂછપરછમાં જરૂર ઉમેરશો',
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
'accmailtext'                      => "[[User talk:$1|$1]] માટે રચેલ ગુપ્તસંજ્ઞા $2 ને મોકલાવી દેવાઇ છે 
આ નવા ખાતાનીગુપ્તસંજ્ઞા  ''[[Special:ChangePassword|change password]]'' વાપરીને પ્રવેશ કર્યા બાદ બદલી શકાશે.",
'newarticle'                       => '(નવીન)',
'newarticletext'                   => "આપ જે કડીને અનુસરીને અહીં પહોંચ્યા છો તે પાનું અસ્તિત્વમાં નથી.
<br />નવું પાનું બનાવવા માટે નીચે આપેલા ખાનામાં લખવાનું શરૂ કરો (વધુ માહિતિ માટે [[{{MediaWiki:Helppage}}|મદદ]] જુઓ).
<br />જો આપ ભુલમાં અહીં આવી ગયા હોવ તો, આપનાં બ્રાઉઝર નાં '''બેક''' બટન પર ક્લિક કરીને પાછા વળો.",
'anontalkpagetext'                 => "----''આ એક અજ્ઞાત સભ્યનું ચર્ચા પાનું છે જેણે યા તો પોતાનું ખાતું નથી ખોલ્યું યાતો તેને વાપરતો નથી.
આથી તેને ઓળખવા માટે અમારે સાંખ્યિક  IP સરનામાની મદદ લેવી પડી છે.
આવું IP સરનામુંઘણાંઅન્યસભ્યો પણ વાપરતા હોઇ શકે.
જો તમે અજ્ઞાત સભ્ય હોવ અને ઈચ્છતા હોવ કે અસંધિત ટિપ્પનીઓ તમારી તરફ વાળવામાં આવે છે, 
તો કૃપયા  [[Special:UserLogin/signup|create an account]] અથવા [[Special:UserLogin|log in]] નો ઉપયોગ કરશો  જેથી તમને કોઈ અજ્ઞાત સભ્ય સમજીને તમારી ટીકા ન કરાય.''",
'noarticletext'                    => 'આ પાનામાં હાલમાં કોઇ માહિતિ નથી.
તમે  [[Special:Search/{{PAGENAME}}|આ શબ્દ]] ધરાવતાં અન્ય લેખો શોધી શકો છો, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} સંલગ્ન માહિતિ પત્રકોમાં શોધી શકો છો],
અથવા  [{{fullurl:{{FULLPAGENAME}}|action=edit}} આ પાનામાં ફેરફાર કરી] માહિતિ ઉમેરવાનું શરૂ કરી શકો છો</span>.',
'noarticletext-nopermission'       => 'આ પાનામાં હાલમાં કોઇ માહિતિ નથી.
તમે  [[Special:Search/{{PAGENAME}}|આ શબ્દ]] ધરાવતાં અન્ય લેખો શોધી શકો છો, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} સંલગ્ન માહિતિ પત્રકોમાં શોધી શકો છો],
અથવા  [{{fullurl:{{FULLPAGENAME}}|action=edit}} આ પાનામાં ફેરફાર કરી] માહિતિ ઉમેરવાનું શરૂ કરી શકો છો</span>.',
'userpage-userdoesnotexist'        => 'સભ્ય ખાતું "$1"ની નોંધણીનથી થઈ.
શું તમે ખરેખર આ પાનાની રચના કે ફેરફાર કરવા માંગો છો',
'userpage-userdoesnotexist-view'   => 'સભ્યના ખાતા $1 ની નોંધણી નથી થઈ',
'blocked-notice-logextract'        => 'આ સભ્ય હાલમાં પ્રતિબંધિત છે.
તમારા નીરીક્ષણ માટે તાજેતરમાં પ્રતિબંધિત થયેલા સભ્યોની યાદિ આપી છે.',
'clearyourcache'                   => "'''નોંધ : સાચવ્યા પછી, તમારે તમારા બ્રાઉઝરની હંગામી યાદદાશ્તને અદ્યતન કરવી પડશે જેથી તમે સાચવેલા ફેરફાર જોઇ શકો.'''
'''મોઝીલા / ફાયરફોક્સ / સફારી:'''  ''Shift'' કળ દાબી રાખી  ''Reload'' પર ક્લીક કરો, અથવા યા તો   ''Ctrl-F5'' કળ દબાવો યાતો  ''Ctrl-R'' (''Command-R'' મેકીંટોશ પર);
'''કોંકરર : '''  ''Reload'' પર ક્લિક કરો અથવા ''F5'' કળ દબાવો;
'''ઑપેરા:''' clear the cache in ''Tools → Preferences'' માં જઈ હંગામી યાદદાશ્ત સાફ કરો;
'''ઈંટરનેટ એક્સપ્લોરર:'''  ''Refresh,'' પર ક્લિક કરવા સાથે ''Ctrl'' પણ દબાવી રાખો અથવા  ''Ctrl-F5'' કળ દબાવો.",
'usercssyoucanpreview'             => "'''ટીપ:''' તમારા નવા CSSને સાચવતા પહેલા  \"{{int:showpreview}}\" બટન વાપરી ચકાસણી કરો.",
'userjsyoucanpreview'              => "'''ટીપ:''' Use the તમારી નવી JavaScript ને સાચવતા પહેલા   \"{{int:showpreview}}\" બટન વાપરી ચકાસો.",
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
'session_fail_preview'             => "'''અફસોસ છે! સત્ર માહિતી ખોઇ દેવાને કારણે અમે તમારું કાર્ય સાચવી ન શક્યાં.'''
કૃપયા ફરી પ્રયત્ન કરો.
તેમ છતાં પણ સાચવી ન શકો તો [[Special:UserLogout|logging out]] કરી ફરી પ્રવેશ કરો.",
'session_fail_preview_html'        => "'''અફસોસ છે! સત્ર માહિતીનો નાશ થવાથી તમે કરેલ ફેરફાર અમે સાચવી ન શક્યાં .'''

''કેમકે  {{SITENAME}} માં કાચી HTML સક્રીય છે, JavaScript ના હુમલા સામેના રક્ષણ માટે ઝલક પર રોક લગાવી છે .''

'''જો આ ગંભીર પ્રયત્ન હતો તો ફરી પ્રયત્ન કરો .'''
અનેતમા છતાં  કમા  ના કરેતો  [[Special:UserLogout|logging out]] કરી ફરી લોગ ઇન કરો.",
'token_suffix_mismatch'            => "'''તમારા ફેરફારો કેમકે તમારા ગ્રાહકએ ફેરફાર નાકામાં વિરામચિન્હો અસ્ત વ્યસ્ત કરી દેતા.'''
આ પાનાને ખરાબ થતો અટકાવવા આ ફેરફાર રદ્દ કરાયો છે. 
જ્યારે તમે વેબ આધારિત  અજ્ઞાત પ્રોક્સી વાપરતા હોવ ત્યારે આવું બની શકે છે.",
'edit_form_incomplete'             => "'''ફેરફાર પત્રનો અમુક ભાગ સર્વર સુધી ન પહોંચ્યો; ખાત્રી કરો કે તમે કરેલા ફેરફાર બરાબર છે અને ફરી પ્રયત્ન કરો.'''",
'editing'                          => '$1નો ફેરફાર કરી રહ્યા છે',
'editingsection'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'editingcomment'                   => '$1 (પરિચ્છેદ)નો ફેરફાર કરી રહ્યા છો',
'editconflict'                     => 'ફેરફારમાં વિસંગતતા: $1',
'explainconflict'                  => "તમે ફેરફાર કરવાની શરૂઆત કરી તે દરમ્યાન અમુક કોઇકે પણ તેમાં ફેરફાર કર્યાં છે.
ઉપરનો અક્ષર દેહ અત્યરનો વિહરમાન સ્થિતિ બતાવે છે.
તમારા દ્વારા કરાયેલ ફેરફાર નીચેના ક્ષેત્રમાં દેખાય છે.
તમારે તમારા ફેરફારો વિહરમાન હયાત લેખમાં વિલિન કરવો પડશે. 
 જો તમે  \"{{int:savearticle}}\" આ બાન દબાવશો તો '''ફક્ત''' ઉપરનો લેખ સચવાશે.",
'yourtext'                         => 'તમારું લખાણ',
'storedversion'                    => 'રક્ષિત પુનરાવર્તન',
'nonunicodebrowser'                => "'''ચેતવણી: તમારું બ્રાઉઝર યુનિકોડ ઉકેલવા સક્ષમ નથી.'''
અહીં તમે સુરક્ષીત રીતે ફેરફરો નહીં કરી શકો: ASCII સિવાયના અક્ષરો ફેરફાર ચોકઠામાં હેક્સાડેસિમલ સ્વરૂપે દેખાશે.",
'editingold'                       => "'''ચેતવણી: તમે કાલાતિત ફેરફારો ને બદલી રહ્યાં છો.'''
જો તમે તેને સાચવશો , વચમાં થયેલ ફેરફારો સાચવી ન શકાય.",
'yourdiff'                         => 'ભેદ',
'copyrightwarning'                 => "મહેરબાની કરીને એ વાતની નોંધ લેશો કે {{SITENAME}}માં કરેલું બધુંજ યોગદાન $2 હેઠળ પ્રકાશિત કરેલું માનવામાં આવે છે (વધુ માહિતિ માટે $1 જુઓ).
જો આપ ના ચાહતા હોવ કે તમારા યોગદાનમાં અન્ય કોઇ વ્યક્તિ બેધડક પણે ફેરફાર કરે અને તેને પુનઃપ્રકાશિત કરે, તો અહીં યોગદાન કરશો નહી.<br />
સાથે સાથે તમે અમને એમ પણ ખાતરી આપી રહ્યા છો કે આ લખાણ તમે મૌલિક રીતે લખ્યું છે, અથવાતો પબ્લિક ડોમેઇન કે તેવા અન્ય મુક્ત સ્ત્રોતમાંથી લીધું છે.
'''પરવાનગી વગર પ્રકાશનાધિકારથી સુરક્ષિત કાર્ય અહીં પ્રકાશિત ના કરશો!'''",
'copyrightwarning2'                => "મહેરબાની કરીને એ વાતની નોંધ લેશો કે {{SITENAME}}માં કરેલા બધાંજ યોગદાનમાં કોઇ પણ અન્ય યોગદાનકર્તા ફેરફાર કરી શકે છે, તેને બદલી શકે છે કે તેને દૂર પણ કરી શકે છે.
જો આપ ના ચાહતા હોવ કે તમારા યોગદાનમાં અન્ય કોઇ વ્યક્તિ બેધડક પણે ફેરફાર કરે તો અહીં યોગદાન કરશો નહી.<br />
સાથે સાથે તમે અમને એમ પણ ખાતરી આપી રહ્યા છો કે આ લખાણ તમે મૌલિક રીતે લખ્યું છે, અથવાતો પબ્લિક ડોમેઇન કે તેવા અન્ય મુક્ત સ્ત્રોતમાંથી લીધું છે (વધુ માહિતી માટે $1 જુઓ).
'''પરવાનગી વગર પ્રકાશનાધિકારથી સુરક્ષિત કાર્ય અહીં પ્રકાશિત ના કરશો!'''",
'longpageerror'                    => "'''ત્રુટિ: તમે લખેલ લેખ  $1 કિલોબાઇટ લાંબો છે, જે $2 કિલોબાઇટની મહત્તમ સીમા કરતાં વધુ છે.'''
તેને સાચવી ન શકાયા.",
'readonlywarning'                  => "'''ચેતવણી: માહિતીસંચ સમારકામ માટે બંધ કરાયો છે, તમે તમારા ફેરફારો હમણા સાચવી નહીં શકો.'''
તમે તમારું લેખ કોઇ ટેક્સ્ટ ફાઇલ માં સેવ કરી મૂકી દો અને માહિતી સંચ ખુલતા વિકિ પર સાચવી શકશો. 

જે પ્રબંધકે માહિતીસંચ બંધ કર્યો છે તેણે આ કારણ આપ્યું છે: $1",
'protectedpagewarning'             => "'''ચેતવણી : આ પાના પર સંરક્ષણ વિકલ્પ સક્રીય છે અને માત્ર પ્રબંધકો જ આમાં ફેરફાર કરી શકે.'''
તમારા સંદર્ભ માટે તાજેતરની લોગ યાદિ આપી છે:",
'semiprotectedpagewarning'         => "'''નોંધ : આ પાના પર સંરક્ષણ વિકલ્પ સક્રીય છે અને માત્ર પ્રબંધકો જ આમાં ફેરફાર કરી શકે.'''
તમારા સંદર્ભ માટે તાજેતરની લોગ યાદિ આપી છે:",
'cascadeprotectedwarning'          => "'''ચેતવણી:''' આ પાનું સંરક્ષીત છે.  પ્રબંધન અધિકાર ધરાવતા સભ્યોજ આમાં ફેરફાર કરી શકે છે. આ પાનાને નીચેના પગથિયામય સંરક્ષણ{{PLURAL:$1|page|pages}} દ્વારા સુરક્ષીત છે.",
'titleprotectedwarning'            => "'''ચેતવણી:  આ પાનું સંરક્ષીત છે  આની રચના માટે [[Special:ListGroupRights|specific rights]] ની જરૂર છે.'''
તે સંબંધી તાજેતરની ફેરફાર યાદિ તમારા સંદર્ભ માટે આપેલી છે:",
'templatesused'                    => 'આ પાનામાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'templatesusedpreview'             => 'આ પૂર્વાવલોકનમાં વપરાયેલ {{PLURAL:$1|ઢાંચો|ઢાંચાઓ}}:',
'templatesusedsection'             => 'આ ખંડ માં વપરાયેલા {{PLURAL:$1|ઢાંચો|ઢાંચા}} :',
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
'permissionserrorstext'            => 'તમને નીચેનાં {{PLURAL:$1|કારણ|કારણો}} સર  આ કાર્ય કરવાની પરવાનગીનથી.',
'permissionserrorstext-withaction' => '$2 પરવાનગી તમને નીચેનાં {{PLURAL:$1|કારણ|કારણો}} સર નથી:',
'recreate-moveddeleted-warn'       => "'''ચેતવણી: તમે જે પાનું નવું બનાવવા જઇ રહ્યાં છો તે પહેલાં દૂર કરવામાં આવ્યું છે.'''

આ પાનું સંપાદિત કરતા પહેલાં ગંભીરતાપૂર્વક વિચારજો અને જો તમને લાગે કે આ પાનું ફરી વાર બનાવવું ઉચિત છે, તો જ અહીં ફેરફાર કરજો.
પાનું હટાવ્યાં પહેલાનાં બધા ફેરફારોની સૂચિ તમારી અનુકૂળતા માટે અહીં આપી છે:",
'moveddeleted-notice'              => 'આ પાનું ભૂંસી દેવાયું છે.
તમારા સંદર્ભ માટે ભૂંસેલા અને હટાવેલા પાનાની સૂચિ આપેલી છે.',
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
'expensive-parserfunction-warning'        => "'''ચેતવણી:''' આ પાનું ખૂબ ખર્ચાળ પદચ્છેદ સૂત્ર ધરાવે છે.

તેમાં $2 કરતા ઓછાં{{PLURAL:$2|પૃછા|પૃછાઓ}}હોવા જોઇએ, અત્યારે તેમાં  {{PLURAL:$1|$1 પૃછા| $1 પૃછાઓ }} છે.",
'expensive-parserfunction-category'       => 'ઘણા પદચ્છેદ સૂત્ર ધરાવતું પાનું',
'post-expand-template-inclusion-warning'  => "'''ચેતવણી:''' ઢાંચાનું માપ ખૂબ મોટું છે.
અમુક ઢાંચા શામિલ નહીં કરાય.",
'post-expand-template-inclusion-category' => 'માહિતી ચોકઠું ધરાવતા પાનાનું કદ વધુ છે',
'post-expand-template-argument-warning'   => "'''ચેતવણી:''' આ પાનામાં ઢાંચાની એક વસ્તુ એવી છે કે જેનો વિસ્તાર ખૂબ લાંબો છે.
તેના વિકલ્પો હટાવી દેવાયા છે.",
'post-expand-template-argument-category'  => 'ઢાંચાના વિકલ્પો અધૂરા રહી ગયેલ હોય તેવા પાનાની યાદિ',
'parser-template-loop-warning'            => 'આવર્તી ઢાંચો મળ્યો : [[$1]]',
'parser-template-recursion-depth-warning' => 'ઢાંચાની આવર્તન સ્તરની સીમા પાર થઈ ($1) ગઈ',
'language-converter-depth-warning'        => 'ભાષા રૂપાંતરણ ઊંડાઈ સીમા વટાવાઈ ($1)',

# "Undo" feature
'undo-success' => 'આ ફેરફારોને ઉલટાવી શકાશે .
નીચે આપેલ સરકામણી સરખાવો અને ચકાસો શું તમે રાખવા માંગો છો અને ફેરફારો સાચવો.',
'undo-failure' => 'વચ્ચે થયેલા અન્ય ફેરફાર થવાને કારણે આ ફેરફારો ઉલટાવી ન શકાયા',
'undo-norev'   => 'ફેરફાર સાચવે ન શકાયો કેમકે યા તો તે અસ્તિત્વમાં નથી અહ્તવાતો ભૂંસી નખાયા છે.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|talk]])એ કરેલો ફેરફાર $1 પાછો વાળ્યો',

# Account creation failure
'cantcreateaccounttitle' => 'ખાતું ખોલી શકાય તેમ નથી',
'cantcreateaccount-text' => "આ IP સરનામા  ('''$1''') પર નવા ખાતાની રચના પર   [[User:$3|$3]] દ્વારા રોક લગાવાઇ છે.


$3 દ્વારા અપાયેલ કારણ છે ''$2''",

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
'rev-deleted-comment'         => '(ફેરફારોનો સારાંશ હટાવ્યો)',
'rev-deleted-user'            => '(સભ્યનામ હટાવાયું)',
'rev-deleted-event'           => '(લોગ ક્રિયા હટાવાઈ)',
'rev-deleted-user-contribs'   => '[સભ્યનામ કે IP સરનામું  હટાવી દેવાયું છે - યોગદાનામાં આ ફેરફાર અદ્રશ્ય છે]',
'rev-deleted-text-permission' => 'આ પુનરાવર્તન હટાવી દેવાયું છે
આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-deleted-text-unhide'     => 'આ ફરકમાંનો કોઇ  એક ફેરફાર હટાવી દેવાયો છે.
આની માહિતી અહીંથી [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] પરથી મળી જશે.
પ્રબંધક તરીકે તમે ઇચ્છોતો [$1 આ ફેરફારો ] જોઇ શકો છો.',
'rev-suppressed-text-unhide'  => 'આ ફરકમાંનો કોઇ  એક ફેરફાર સંતાડી દેવાયો છે.
આની માહિતી અહીંથી [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log]  પરથી મળી જશે.
પ્રબંધક તરીકે તમે ઇચ્છોતો [$1 આ ફેરફારો ] જોઇ શકો છો.',
'rev-deleted-text-view'       => 'આ પુનરાવર્તન હટાવી દેવાયું છે
પ્રબંધક તરીકે તમે આની લાહિતી મેળવી શકો છો. આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-suppressed-text-view'    => 'આ પુનરાવર્તન સંતાડી દેવાયું છે
પ્રબંધક તરીકે તમે આની લાહિતી મેળવી શકો છો. આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].',
'rev-deleted-no-diff'         => 'તમને આ ફરક નહીં દેખાય કેમકે કોઇ એક ફેરફાર હટાવી દેવાયો છે.
આની માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-suppressed-no-diff'      => "તમને ફરક નહીં દેખાય કેમકે કોઈ એક પુનરાવર્તન '''હટાવાયું છે'''.",
'rev-deleted-unhide-diff'     => 'આ ફરકમાંનો કોઇ  એક ફેરફાર હટાવી દેવાયો છે.
આની માહિતી અહીંથી [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] પરથી મળી જશે.
પ્રબંધક તરીકે તમે ઇચ્છોતો [$1 આ ફેરફારો ] જોઇ શકો છો.',
'rev-suppressed-unhide-diff'  => 'આ ફરકમાંનો કોઇ  એક ફેરફાર સંતાડી દેવાયો છે.
આની માહિતી અહીંથી [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log] પરથી મળી જશે.
પ્રબંધક તરીકે તમે ઇચ્છોતો [$1 આ ફેરફારો ] જોઇ શકો છો.',
'rev-deleted-diff-view'       => 'આ પુનરાવર્તન હટાવી દેવાયું છે
પ્રબંધક તરીકે તમે આની લાહિતી મેળવી શકો છો. આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log]..',
'rev-suppressed-diff-view'    => 'આ પુનરાવર્તન સંતાડી દેવાયું છે
પ્રબંધક તરીકે તમે આની લાહિતી મેળવી શકો છો. આની વિસ્તરીત માહિતી અહીં મળશે [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].',
'rev-delundel'                => 'બતાવો/છુપાવો',
'rev-showdeleted'             => 'બતાવો',
'revisiondelete'              => 'પુનરાવર્તન રદ્દ કરો/પુનર્જીવીત કરો',
'revdelete-nooldid-title'     => 'અવૈધ લક્ષ્ય ફેરફાર',
'revdelete-nooldid-text'      => 'આ ક્રિયા જેના પર  કરવાની છે તે લક્ષ્ય ફેરફાર તમે જણાવ્યો નથી અથવા એવો કોઇ ફેરફાર અસ્તિત્વમાં નથી અથવા તમે હાલનો ફેરફાર સંતાડવાનો પ્રયત્ન કરી રહ્યાં છો.',
'revdelete-nologtype-title'   => 'આવો કોઈ લોગા નથી ફરી પ્રયત્ન કરો',
'revdelete-nologtype-text'    => 'આ ક્રિયા શેના પર કરવાની છે તે લોગ નો પ્રકાર તમે જણાવ્યો નથી',
'revdelete-nologid-title'     => 'લોગ પ્રવેશ અવૈદ્ય',
'revdelete-nologid-text'      => 'આ ક્રિયા જેના પર કરવાની છે તે લક્ષ્ય ઘટના તમે જણાવી નથી અથવા તેવી કોઇ ઘટના નથી.',
'revdelete-no-file'           => 'વર્ણવેલી ફાઈલ અસ્તિત્વમાં નથી',
'revdelete-show-file-confirm' => 'શું તમને ખાત્રી છે કેતમે $2 તારીખ $3 વાગ્યા સુધીના "<nowiki>$1</nowiki>" ફાઇલ ના ફેરફાર જોવા માંગો છો?',
'revdelete-show-file-submit'  => 'હા',
'revdelete-selected'          => "''' [[:$1]] ના {{PLURAL:$2|પસંદ કરેલ ફેરફાર |પસંદ કરેલ ફેરફારો}}: '''",
'logdelete-selected'          => "'''{{PLURAL:$1|પસંદગી કરેલ લોગ ઘટના | પસંદગી કરેલ લોગ ઘટનાઓ }}:'''",
'revdelete-text'              => "''' રદ્દ કરાયેલ ફેરફારો અને ઘટનાઓ પાનાના ઈતિહાસ અને લોગમાં દેખાશે , પણ તેની અંદરની માહિતી જન સમુદાયથી અદ્રશ્ય રહેશે. '''
{{SITENAME}} પરના અન્ય પ્રબંધકો આ અદ્રશ્ય માહિતે જોઇ શકશે અને તેને પુનઃ જીવિત કરી શકશે સિવાય કે તેના પર વધારાની પાબંદી ન મુકાઇ હોય.",
'revdelete-confirm'           => 'કૃપયા પુષ્ટિ કરો કે તમે શું કરી રહ્યા છો તેની અને તેના પરિણામોની તમને જાણ છે અને તમે આ બધું  [[{{MediaWiki:Policy-url}}|the policy]] અ6તર્ગત કરી રહ્યાં છો.',
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
'logdelete-log-message'       => '$1 માટે $2 {{PLURAL:$2|ઘટના|ઘટનાઓ}}',
'revdelete-hide-current'      => '$2, $1 તરીખ ધરાવતી વસ્તુ સંતાડવામાં ત્રુટિ : આ હાલનો ફેરફાર છે.
આને છુપાવી નહીં શકાય.',
'revdelete-show-no-access'    => '$2, $1 તિથીની વસ્તુ બતાવવામાં ત્રુટિ : આને "પ્રતિબંધિત" અંકિત કરાઇ છે.
તમને ત્યાં પ્રવેશ નથી',
'revdelete-modify-no-access'  => '$2, $1 તિથીની વસ્તુ પર ફેરફાર કરવામાં ત્રુટિ : આ વસ્તુને "પ્રતિબંધિત" અંકિત કરાઇ છે.
તમને પ્રવેશ નથી.',
'revdelete-modify-missing'    => 'ID $1 નો ફેરફાર કરવામાં ત્રુટિ  : તે માહિતીસંચમાંથી ગાયબ છે',
'revdelete-no-change'         => "'''ચેતવણી:'''  $2, $1 તારીખ ધરાવતી વસ્તુ માં માંગેલ દ્રશ્ય વિકલ્પો પહેલેથી છે",
'revdelete-concurrent-change' => 'તારીખ $2, $1 વાગ્યે થયેલ ફેરફાર કરવામાં ત્રુટિ : તમે તેમાં ફેરફાર કરો છો ત્યારે કોઇ અન્ય વ્યક્તિ દ્વારા આનામાં ફેરફાર કરાયો છે.
કૃપયા લોગ તપાસો.',
'revdelete-only-restricted'   => '$2 તારીખ, $1 વાગ્યાની વસ્તુ સંતાડવામાં ત્રુટિ : અન્ય દ્રશ્ય વિકલ્પ પસંદ કર્યા સિવાય તમે પ્રતિબંધને પ્રબંધકથી દેખતા બચાવી ન શકો.',
'revdelete-reason-dropdown'   => '* હટાવવાનાં સામાન્ય કારણો 
** પ્રકાશનાધિકાર ભંગ
** અયોગ્ય અંગત માહિતી 
** સંભવતઃ બદનક્ષી કરનારું લખાણ',
'revdelete-otherreason'       => 'અન્ય/વધારાનું કારણ:',
'revdelete-reasonotherlist'   => 'અન્ય કારણ',
'revdelete-edit-reasonlist'   => 'ભુંસવાનું કારણ બદલો.',
'revdelete-offender'          => 'પુનરાવર્તનના મૂળ લેખક',

# Suppression log
'suppressionlog'     => 'દાબ નોંધ',
'suppressionlogtext' => 'નીચે પ્રબંધકોથી છુપાયેલ એવા હટાવ અને રોકની યાદિ આપેલી છે.
હાલમાં સક્રીય એવા  પ્રતિબંધ અને રોકની યાદિ અહીં  [[Special:IPBlockList|IP block list]]  આપેલ છે.',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|એક ફેરફાર|$3 ફેરફારો }} $1 થી $2 માં ખસેડ્યાં',
'revisionmove'                 => '"$1" થી ફેરફાર હટાવો',
'revmove-explain'              => 'નીચેના ફેરફારોને તમે $1 જણાવેલ લક્ષ્ય પાના પર હટાવાશે. જો લક્ષ્ય અસ્તિત્વ માં ન હોય  તો તેની રચના થશે. અન્યથા, ફેરફારોને પાનાના ઇતિહાસમાં વિલિન કરવામં આવશે.',
'revmove-legend'               => 'લક્ષ્ય પાનું અને સરાંશ પુન;સંયોજીત કરો.',
'revmove-submit'               => 'પુનરાવર્તનો પસંદ કરેલા પાના પર હટાવો',
'revisionmoveselectedversions' => 'પસંદ કરેલા ફેરફારો ખસેડો',
'revmove-reasonfield'          => 'કારણ:',
'revmove-titlefield'           => 'લક્ષ્ય પાનું',
'revmove-badparam-title'       => 'ખરાબ વિકલ્પો',
'revmove-badparam'             => 'તમારી વિનંતિમાં ગેરકાયદીસર અને અપૂરતા વિકલ્પો છે.
પાછલા પાના પર ફરી જાવ અને ફરી પ્રયત્ન કરો.',
'revmove-norevisions-title'    => 'અવૈધ લક્ષ્ય ફેરફાર',
'revmove-norevisions'          => 'આ ક્રિયા જેના પર કરવાની છે તે એક કે વધુ લક્ષિત ફેરફાર તમે જણાવ્યાં નથી અથવા એવા ફેરફારો અસ્તિત્વમાં નથી.',
'revmove-nullmove-title'       => 'ખરાબ નામ',
'revmove-nullmove'             => 'લક્ષ્ય પાનું અને સ્ત્રોત પાનું સમાન ન હોઇ શકે.
પાછલા પાને ફરી જાવ અને અન્ય "$1" માંથી અન્ય નામ પસંદ કરો.',
'revmove-success-existing'     => '[[$2]]  માંથી{{PLURAL:$1|ફેરફાર|$1 ફેરફારો }} પાના [[$3]] પર લઈ જવાયા.',
'revmove-success-created'      => '[[$2]] માંથી {{PLURAL:$1|ફેરફાર|$1 ફેરફારો }} પાના [[$3]] પર ખસેડાયા.',

# History merging
'mergehistory'                     => 'પાનાનાં ઇતિહાસોનું વિલીનીકરણ',
'mergehistory-header'              => 'આ પાનું તમને સ્ત્રોત પાનાનો ઈતિહાસ નવા પાનામાં વિલિન કરવા માં મદદ કરે છે.
એ વાતનું ધ્યાન રાખશો કે ઇતિહાસ પાનાની સળંગતા જળવાઇ રહે.',
'mergehistory-box'                 => 'બે પાનાના ફેરફાર વિલિન કરો',
'mergehistory-from'                => 'સ્ત્રોત પાનું',
'mergehistory-into'                => 'લક્ષ્ય પાનું',
'mergehistory-list'                => 'વિલિનીકરણશીલ ફેરફારનો ઈતિહાસ',
'mergehistory-merge'               => '[[:$1]] દ્વારા કરેલ ફેરફારો [[:$2]] માંવિલિન કરી શકાયા.
અમુક ખાસ સમય સુધીના ફેરફારો કરવા રેડિયો બટન પર ક્લિક કરો.
નોંધો કે આવાગમન કડીઓ વાપરતા આ સ્તંભ પુનઃ પૂર્વવત રૂપમાં આવે જશે',
'mergehistory-go'                  => 'વિલિનીકરણશીલ ફેરફારો બતવો',
'mergehistory-submit'              => 'ફેરફારો વિલિન કરો',
'mergehistory-empty'               => 'પુનરાવર્તન સાચવી ન શકાયા',
'mergehistory-success'             => '[[:$1]] ના $3 {{PLURAL:$3|ફેરફાર |ફેરફારો}} ને સફળતા પૂર્વક  [[:$2]] માં વિલિનાકરાયા.',
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
'diff-multi-manyusers'     => '{{PLURAL:$2|એક સભ્યએ કરેલું|$2 સભ્યોએ કરેલા}} ({{PLURAL:$1|વચગાળાનું એક પુનરાવર્તન દર્શાવ્યં|વચગાળાનાં $1 પુનરાવર્તનો દર્શાવ્યાં}} નથી.)',

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
'showingresultsnum'                => "#'''$2''' થી શરૂ કરી {{PLURAL:$3|'''1''' પરિણામresult|'''$3''' પરિણામો}} અહીં બતાવ્યાં છે",
'showingresultsheader'             => "'''$4''' માટે {{PLURAL:$5|પરિણામ  '''$1''' of '''$3'''|પરિણામો '''$1 - $2''' of '''$3'''}}",
'nonefound'                        => "'''નોંધ''':ફક્ત અમુકજ નામસ્થળોમાં આપોઆપ શોધાશે.
તમારા શબ્દને ''બધા:'' ઉમેરી શોધવાનો પ્રયત્ન કરો, જેથી બધી માહિતિમાં (જેમકે ચર્ચાના પાના, ઢાંચા, વિગેરે)માં શોધ થઈ શકે, અથવાતો ઇચ્છિત નામસ્થળ પસંદ કરી શોધો બટન દબાવો.",
'search-nonefound'                 => 'તમે આપેલી માહિતી ને મળતાં આવતાં કોઈ પરિણામો નથી',
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
'prefsnologintext'              => 'સભ્યના અધિકારો બદલવા તમે <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} logged in]</span> પ્રવેશ કરેલો હોવો જોઈએ',
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
'stub-threshold'                => '<a href="#" class="stub">stub link</a>નાફોર્મમેટિંગ માટે શરૂઆતિ પગથિયું  (બાઈટ્સ):',
'stub-threshold-disabled'       => 'નિષ્ક્રીયાન્વીત',
'recentchangesdays'             => 'તાજા ફેરફારોમાં દેખાડવાના દિવસો',
'recentchangesdays-max'         => 'મહત્તમ  $1 {{PLURAL:$1|દિવસ|દિવસો}}',
'recentchangescount'            => 'સમાન્ય પણે ફલકમાં બતાવવાના ફેરફારોની સંખ્યા',
'prefs-help-recentchangescount' => 'આમાં તાજા ફેરફારો,  ઇતિહાસ અને લોગ શામિલ છે.',
'prefs-help-watchlist-token'    => 'ગુપ્ત સંજ્ઞા આ ખાનામાં ભરતા તમારી ધ્યાનસૂચિ માટે RSS માહિતીનું નિર્માણ થશે.
જેની પાસે તમારી આ ગુપ્ત સંજ્ઞા હશે ત તમારી ધ્યાનસૂચિ વપરે શકશે. આથી સુરક્ષિત એવો શબ્દ પસંદ કરશો.
તમે વાપરી શકો તેવી અસંગત રચાયેલી રીતે સંજ્ઞા અહીં અપેલ છે : $1',
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
'prefs-reset-intro'             => 'તમી આ પાનાનો ઉપયોગ કરીને તમારા વિકલ્પો ની ગોઠવણી મૂળના વિકલ્પો સમાન ગોઠવી શકો છો.
આને ઉલટાવી ન શકાય.',
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
'prefs-help-signature'          => 'ચર્ચા પાના પરની ટિપ્પણી "<nowiki>~~~~</nowiki>" દ્વારા હસ્તાક્ષરીત હોવા જોઇએ તેમાં તમારા હસ્તાક્ષર અને સમય શામિલ થશે.',
'badsig'                        => 'અવૈધ કાચી સહી
HTML નાકું ચકાસો',
'badsiglength'                  => 'તમારી સહી વધુ લાંબી છે.
તે $1 {{PLURAL:$1|અક્ષર|અક્ષરો}} કરતા વધુ લાંબી ન હોવી જોઇએ.',
'yourgender'                    => 'જાતિ:',
'gender-unknown'                => 'અનિર્દિષ્ટ',
'gender-male'                   => 'પુરુષ',
'gender-female'                 => 'સ્ત્રી',
'prefs-help-gender'             => 'વૈકલ્પિક:  સોફ્ટવેર દ્વારા લિંગ  ઓળખ માટે વપરાયેલ .
આ માહિતી જન સમુદાય માટે જાહેર હશે.',
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
'editinguser'                  => "સભ્ય '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) ના અધિકારો બદલાઇ રહ્યા છે.",
'userrights-editusergroup'     => 'સભ્ય સમુહો સંપાદીત કરો',
'saveusergroups'               => 'સભ્ય સમુહો સાચવો',
'userrights-groupsmember'      => 'સભ્યપદ:',
'userrights-groupsmember-auto' => 'નો અભિપ્રેત સભ્ય:',
'userrights-groups-help'       => 'અ સ્ભ્ય જેનો સભ્ય છે તે સમ્હૂહને બદલી શકો છો:
* અંકિત કરેલું ખાનું બતાવે છે સભ્ય તેનો સમૂહમાં શામિલ છે.
* જો ખાનું અંકિત ન હોય તો સભ્ય તે સમૂહમાં શામિલ નથી.
* એક  *દર્શાવે છે તમે ઉમેર્યા પછી સમૂહ હટાવી ન શકો અથવા તેથી ઉલટું.',
'userrights-reason'            => 'કારણ:',
'userrights-no-interwiki'      => 'અન્ય વિકિ પર અન્ય સભ્યો ના અધિકારો માં પરિવર્તન કરવાની તમને પરવાનગી નથી',
'userrights-nodatabase'        => 'માહિતીસંચ $1 અસ્તિત્વમાં નથી કે તે સ્થાનીય નથી.',
'userrights-nologin'           => 'સભ્યના અધિકારો આપવા તમે પ્રબંધક તરીકે પ્રવેશ  [[Special:UserLogin|log in]] કરેલ હોવો જરૂરી છે.',
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
'right-suppressredirect'      => 'પાના હટાવતી વખતના સમયે સ્ત્રોત પાનામાંથી દિશા નિર્દેશ વર્જીત',
'right-upload'                => 'ફાઇલ ચડાવો',
'right-reupload'              => 'વિહરમાન ફાઇલ પર પુનર્લેખન કરો',
'right-reupload-own'          => 'સભ્ય દ્વારા જાતે ચઢાવેલી તાઇલ પર પુનર્લેખન કરો',
'right-reupload-shared'       => 'સર્વસામાન્ય સ્થાનીયા દ્રશ્યશ્રાવ્ય ફાઈલ સંચને અવગણી આગળ વધો.',
'right-upload_by_url'         => 'URL પરથી ફાઇલ ચઢાવો',
'right-purge'                 => 'પુષ્ટિકરણ ન થયેલ પાનાની સાઇટ સંબંધી માહિતી મીટાવો.',
'right-autoconfirmed'         => 'અર્ધ સંરક્ષિત પાના સંપાદિત કરો',
'right-bot'                   => 'આને સ્વયં ચાલિત પ્રિયા ગણો',
'right-nominornewtalk'        => 'ચર્ચાના પાનામાં લઘુ ફેરફારો તરીકે કરેલ ફેરફારની સૂચના ન મોકલાશે.',
'right-apihighlimits'         => 'પૂછતાછમાં  APની  ચઢિયાતી સીમા વાપરો',
'right-writeapi'              => 'લેખન API વાપરો',
'right-delete'                => 'પાનું હટાવો',
'right-bigdelete'             => 'લાંબા ઇતિહાસ વાળા પાના હટાવો',
'right-deleterevision'        => 'પાના પરના અમુક ખાસ સંપાદનો હટાવો કે પુનર્જીવીત કરો',
'right-deletedhistory'        => 'હટાવેલા ઇતિહાસ પ્રવેશ નોંધ જુઓ. સંલગ્ન અક્ષરદેહ વિના.',
'right-deletedtext'           => 'રદ્દ કરાયેલ લેખ અને રદ્દીકરણ વચ્ચેના ફેરફારો વાંચો',
'right-browsearchive'         => 'હટાવેલા પાનાની શોધ',
'right-undelete'              => 'હટાવેલ પાનું પુનર્જીવીત કરો',
'right-suppressrevision'      => 'પ્રબંધકોથી સંતાડાયેલ ફેરફારો નું અવલોકન અને પુનઃસ્થાપન કરો.',
'right-suppressionlog'        => 'નિજી લોગ જુઓ',
'right-block'                 => 'આ સભ્ય દ્વારા થનાર ફેરફાર પ્રતિબંધીત કરો',
'right-blockemail'            => 'સભ્યના ઇ-મેલ મોકલવા પર પ્રતિબંધ મૂકો',
'right-hideuser'              => 'સભ્યનામ પર પ્રતિબંધ મૂકો જેથી તે લોકોને ન દેખાય.',
'right-ipblock-exempt'        => 'IP દ્વારા, સ્વયં ચાલિત રીતે અને સમૂહ રોકને અવગનીને આગળ વધો',
'right-proxyunbannable'       => 'અવેજીના અવયંચાલિત ખંડોને ટાળો',
'right-unblockself'           => 'તેમને જાતે અપ્રતિબંધિત થવા દો',
'right-protect'               => 'સંરક્ષણ સ્તર બદલો અને સંરક્ષીત પાનામાં ફેરફાર કરો.',
'right-editprotected'         => 'સંરક્ષિત પાનામાં ફેરફાર કરો (પગથિયામય સુરક્ષા વગર)',
'right-editinterface'         => 'સભ્ય સંભાષણ પટલમાં ફેરફાર કરો',
'right-editusercssjs'         => 'અન્ય સભ્યોની CSS અને JavaScript ફાઇલમાં ફેરફાર કરો',
'right-editusercss'           => 'અન્ય સભ્યોની CSS ફાઇલમાં ફેરફાર કરો',
'right-edituserjs'            => 'અન્ય સભ્યોની JavaScript ફાઇલમાં ફેરફાર કરો',
'right-rollback'              => 'ચોક્કસ પાનામાં જે છેલ્લા સભ્યએ ફેરફારો કર્યાં હોય તેને ઝડપથી ઉલટાવો',
'right-markbotedits'          => 'ઉલટાવનારા અને બોટ ફેરફારો નોંધો',
'right-noratelimit'           => 'ઝડપ્ની સીમાને કારણે અસર ન થવે જોઇએ.',
'right-import'                => 'અન્ય વિકિમાંથી પાના આયાત કરો',
'right-importupload'          => 'ચઢાવેલી ફાઇલ પરથી આ પાનું આયાત કરો.',
'right-patrol'                => 'અન્યો ના ફેરફારો નીરીક્ષીત અંકિત કરો',
'right-autopatrol'            => 'પોતાના ફેરફારોને નીરીક્ષિત અંકિત કરો',
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
'rightslog'      => 'સભ્ય હક્ક માહિતિ પત્રક',
'rightslogtext'  => 'સભ્યના બદલાયેલ હક્કોની આ સંપાદન યાદિ છે .',
'rightslogentry' => '$2 થી $3 સુધી $1 માટે બદલાયેલું સમૂહ સભ્યપદ',
'rightsnone'     => '(કોઈ નહિ)',

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
'action-reupload-shared'      => 'સર્વ સામાન્ય ફાઈલ સંગ્રહ પર આ ફાઇલ અવગણી આગળ વધો',
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
'nchanges'                          => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}',
'recentchanges'                     => 'તાજા ફેરફારો',
'recentchanges-legend'              => 'હાલમાં થયેલા ફેરફારોના વિકલ્પ',
'recentchangestext'                 => 'આ પાના દ્વારા વિકિમાં થયેલા તાજા ફેરફારો પર ધ્યાન રાખો.',
'recentchanges-feed-description'    => 'આ ફીડ દ્વારા વિકિમાં થયેલા તાજા ફેરફારો પર ધ્યાન રાખો.',
'recentchanges-label-newpage'       => 'આ ફેરફાર દ્વારા નવું પાનું નિર્મિત થયું',
'recentchanges-label-minor'         => 'આ એક નાનો સુધારો છે.',
'recentchanges-label-bot'           => 'આ ફેરફાર બોટ દ્વારા કરાયો છે',
'recentchanges-label-unpatrolled'   => 'આ ફેરફાર હજી ચકાસાયો નથી',
'rcnote'                            => "નીચે $5, $4 સુધીમાં અને તે પહેલાનાં '''$2''' દિવસમાં {{PLURAL:$1| થયેલો '''1''' માત્ર ફેરફાર|થયેલાં છેલ્લા  '''$1''' ફેરફારો}} દર્શાવ્યાં છે .",
'rcnotefrom'                        => "નીચે '''$2'''થી થયેલાં '''$1''' ફેરફારો દર્શાવ્યાં છે.",
'rclistfrom'                        => '$1 બાદ થયેલા નવા ફેરફારો બતાવો',
'rcshowhideminor'                   => 'નાના ફેરફારો $1',
'rcshowhidebots'                    => 'બૉટો $1',
'rcshowhideliu'                     => 'લૉગ ઇન થયેલાં સભ્યો $1',
'rcshowhideanons'                   => 'અનામિ સભ્યો $1',
'rcshowhidepatr'                    => ' $1 ચોકીયાત ફેરફારો',
'rcshowhidemine'                    => 'મારા ફેરફારો $1',
'rclinks'                           => 'છેલ્લાં $2 દિવસમાં થયેલા છેલ્લાં $1 ફેરફારો દર્શાવો<br />$3',
'diff'                              => 'ભેદ',
'hist'                              => 'ઇતિહાસ',
'hide'                              => 'છુપાવો',
'show'                              => 'બતાવો',
'minoreditletter'                   => 'નાનું',
'newpageletter'                     => 'નવું',
'boteditletter'                     => 'બૉટ',
'number_of_watching_users_pageview' => '[$1 જોઇરહેલ  {{PLURAL:$1|સભ્ય|સભ્યો}}]',
'rc_categories'                     => 'શ્રેણીઓ સુધી મર્યાદિત ("|" થી જુદા પાડો)',
'rc_categories_any'                 => 'કોઇ પણ',
'newsectionsummary'                 => '/* $1 */ નવો વિભાગ',
'rc-enhanced-expand'                => 'વિગતો બતાવો (જાવા સ્ક્રિપ્ટ જરૂરી છે)',
'rc-enhanced-hide'                  => 'વિગતો છુપાવો',

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
'upload'                      => 'ફાઇલ ચડાવો',
'uploadbtn'                   => 'ફાઇલ ચડાવો',
'reuploaddesc'                => 'ફાઇલ ચઢાવવાનું રદ્દ કરો અને ફરી ફાઇલ ચઢાવવાના પાના પર ફરી જાવ',
'upload-tryagain'             => 'સુધારીત ફાઇલ વર્ણન ચડાવો',
'uploadnologin'               => 'પ્રવેશ કરેલ નથી',
'uploadnologintext'           => 'ફાઇલ ચઢાવવા માટે  [[Special:UserLogin|logged in]] પ્રવેશ કરેલો હોવો જોઇએ',
'upload_directory_missing'    => 'ફાઇલ ચડાવવાની ડાયરેક્ટરી ($1) ગાયબ છે  અને વેબસર્વર દ્વારા તેની રચના ન કરી શકાઇ.',
'upload_directory_read_only'  => 'ચઢાવેલી ડાયરેક્ટરી ($1) એ વેબ બ્રાઉઝર દ્વારા લેખન યોગ્ય નથી.',
'uploaderror'                 => 'ફાઇલ ચઢાવમાં ચૂક',
'upload-recreate-warning'     => "'''ચેતવણી: આ નામની એક ફાઇલ ભૂંસાડી કે હટાવી દેવાઇ છે. '''
તમારા સંદર્ભ માટે પાના ભૂંસાવવાનો અને હટાવવાની સૂચિ  અહીં આપેલ છે.:",
'uploadtext'                  => "ફાઈલ ચડાવવા માટે નીચેનું ફોર્મ વાપરો.
પહેલા ચડાવાયેલી ફાઇલ અહીં જુઓ કે શોધો  [[Special:FileList|list of uploaded files]], (પુનઃ) ચડાવેલી ફાઇલ પણ લોગમાં અહીં દર્શાવાઇ છે.  [[Special:Log/upload|upload log]], હટાવાયેલી ફાઇલોની યાદિ  [[Special:Log/delete|deletion log]].

કોઇ ફાઇલને પાનામાં ઉમેરવા માટે, નીચેની કોઇ એક કડી પર ક્લિક કરો:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' ફાઇલની પૂર્ણ આવૃત્તિ વાપરવા 
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' to use a ડાબા હાંસિયા નજીક ચોકઠામાં 200 પિક્સેલ પહોળી ફાઇલ   'alt text' સાથે વર્ણન સહીત ચડાવવા 
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' ફાઇલના દર્શન કર્યા સિવાય સીધી ફાઇલ જોડવા માટે",
'upload-permitted'            => 'રજામંદ ફાઈલ પ્રકારો: $1.',
'upload-preferred'            => 'ઈચ્છીત ફાઈલ પ્રકારો: $1.',
'upload-prohibited'           => 'પ્રતિબંધીત ફાઈલ પ્રકારો: $1.',
'uploadlog'                   => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'uploadlogpage'               => 'ચઢાવેલી ફાઇલોનું માહિતિ પત્રક',
'uploadlogpagetext'           => 'નીચે હાલમાં ચડાવેલી ફાઇલોની યાદી છે.
વધુ ફાઇલો દ્રશમાન કરવા [[Special:NewFiles|gallery of new files]] જુઓ',
'filename'                    => 'ફાઇલ નામ',
'filedesc'                    => 'સારાંશ:',
'fileuploadsummary'           => 'સારાંશ:',
'filereuploadsummary'         => 'ફાઈલ ફેરફારો',
'filestatus'                  => 'પ્રકાશનાધિકાર સ્થિતિ',
'filesource'                  => 'સ્ત્રોત:',
'uploadedfiles'               => 'ફાઇલ ચડાવો',
'ignorewarning'               => 'ચેતવણીને અવગણી ને પણ ફાઇલ સાચવો',
'ignorewarnings'              => 'કોઇ પણ ચેતવણી અવગણો',
'minlength1'                  => 'ફાઇલ નામની લંબાઇ કમ સે કમ એક અક્ષર જેટલી તો હોવી જ જોઇએ.',
'illegalfilename'             => 'ફાઇલનામ "$1" એવા ચિન્હો ધરાવે છે જેનો પ્રયોગ શીર્ષકોમાં વર્જિત  છે.
કૃપયા ફાઇઅલ નું નામ બદલીને ફરી ચઢાવો.',
'badfilename'                 => 'ફાઇલ નામ  "$1" નામે બદલાયું છે.',
'filetype-mime-mismatch'      => 'ફાઇલ પ્રકાર ".$1"  શોધાયેલ MIME પ્રકારની ફાઇલ ($2) સાથે મેળ ખાતો નથી.',
'filetype-badmime'            => 'MIME પ્રકારની "$1"  ફાઇલ ચડાવવાની પરવાનગી નથી',
'filetype-bad-ie-mime'        => 'આ ફાઈલ ચડાવી નહીં શકાય કેમ કે ઇંટરનેટ એક્સપ્લોરર  આને  "$1" તરીકે ઓળખશે, જેની પરવાનગી નથે અને આ ખતરનાક પ્રકારની ફાઇલ હોઇ શકે.',
'filetype-unwanted-type'      => "'''\".\$1\"''' એ એક બિન જરૂરી ફાઇલ પ્રકાર છે. 
ઇચ્છનીય  {{PLURAL:\$3|ફાઇલ પ્રકાર|ફાઇલ પ્રકારો}} \$2 છે.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|એ ફાઇલ પ્રકારને પ્રવાનગી નથી|એ ફાઇલ પ્રકારોને પ્રવાનગી નથી }}.
પરવાનગી મેળવેલ {{PLURAL:$3|ફાઇલ પ્રકાર છે|ફાઇલ પ્રકારો  છે}} $2.',
'filetype-missing'            => 'ફાઇલને કોઇ વિસ્તાર શબ્દ નથી (જેમકે ".jpg").',
'empty-file'                  => 'તમે ચડાવેલી ફાઈલ ખાલી છે',
'file-too-large'              => 'તમે ચડાવેલી ફાઈલ ખૂબ મોટી છે',
'filename-tooshort'           => 'ફાઇલ નામ ખૂબ ટૂંકું છે',
'filetype-banned'             => 'આ પ્રકારની ફાઈલ પ્રતિબંધિત છે.',
'verification-error'          => 'આ ફાઇલ એ ચકાસણી કસોટી પાર ન કરી',
'hookaborted'                 => 'તમે કરેલ ફેરફારો વિસ્તારીત ખૂંટા દ્વારા રોકી દેવાયા.',
'illegal-filename'            => 'ફાઈલા નામને પરવાનગી નથી',
'overwrite'                   => 'વિહરમાન ફાઇલ પર પુનર્લેખન કરવાની પરવાનગી નથી',
'unknown-error'               => 'અજ્ઞાત ચૂક થઈ',
'tmp-create-error'            => 'હંગામી ફાઇલ ન બનાવી શકાઇ',
'tmp-write-error'             => 'હંગામી ફાઇલ લખવામાં ખામી',
'large-file'                  => 'ફાઇલ $1 કરતાં મોટી ન હોય તે ઇચ્છનીય છે. 
આ ફાઇલનું કદ $2 છે.',
'largefileserver'             => 'સરવરે પરવાનગી આપેલ કદ કરતાં આ ફાઇલ મોટી  છે.',
'emptyfile'                   => 'તમે ચડાવેલી ફાઇલ ખાલી લાગે છે
કદાચ તે તેમાં રહેલ લાહિતીના પ્રકારને લીધે હશે.
કૃપયા ખાત્રી કરો શું તમે ખરેખર આ ફાઇલ ચડાવવા માંગો છો.',
'fileexists'                  => "આ નામે એક ફાઇલ પહેલેથી અસ્તિત્વમાં છે, કૃપયા  જો તમને ખાત્રી ન હોય તો અથવા બદલવા માંગતા હોય તો  '''<tt>[[:$1]]</tt>''' ચકાસો [[$1|thumb]]",
'filepageexists'              => "આ ફાઇલનું માહિતી પત્રક  '''<tt>[[:$1]]</tt>'''પર પહેલેથી અસ્તિત્વમાં છે, પણ તેનામે કોઇ ફાઇલ અસ્તિત્વમાં નથી.
તમે લખેલ સારાંશએ વર્ણનમાં નહીં દેખાય.
તમે જો ઇચ્છો કે તમારો સારાંશ આમાં દેખાય તો, તે માટે તમારે જાતે તેમાં ફેરેફાર કરવો પડશે. 
[[$1|thumb]]",
'fileexists-extension'        => "આ જ નામે ફાઇલ પહેલેથી અસ્તિત્વ માં છે: [[$2|thumb]]
* ચડાવાતી ફાઇલનું નામ છે: '''<tt>[[:$1]]</tt>'''
* ઓઅહેલેથી વિહરમાન ફાઇલનું નામ : '''<tt>[[:$2]]</tt>'''
કૃપયા અન્ય નામ આપો.",
'fileexists-thumbnail-yes'    => "આ ફાઇલ સંકોચેલી ફાઇલનું લઘુ ચિત્ર છે.
[[$1|thumb]]
કૃપયા ફાઇલ તપાસો  '''<tt>[[:$1]]</tt>'''.
જો તપાસેલી ફાઇલ તેની મૂળ પ્રતની આકૃતિ હોય તો વધારાની લઘુચિત્ર ચડાવવાની જરૂર નથી.",
'file-thumbnail-no'           => "ફાઇલનું નામ '''<tt>$1</tt>'''થી શરૂ થાય છે.
લાગે છે કે આ ઘટાડેલા કદનું ચિત્ર  ''(thumbnail)'' છે..
જો તમારી સાથે પૂર્ણ ઘનત્વ ધરાવતી ચિત્રની ફાઇલ હોય તો જ આ ફાઇલ ચડાવશો, અન્યથા ફાઇલનું નામ બદલશો.",
'fileexists-forbidden'        => 'આ નામની ફાઇલ પહેલેથી મોજુદ છે અને તેના ઉપર લેખન કરી શકાશે નહી.
તેમ છતાં પણ તમે ફાઇલ ચડાવવા માંગતા હોવ તો ફાઇલનું નામ બદલો અને નવા નામે ફરીથી ચડાવો.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'સર્વસામાન્ય ફાઇલ સંગ્રહમાં આ નામની ફાઇલ મોજુદ છે.
તેમ છતાં પણ તમે ફાઇલ ચડાવવા માંગતા હોવ તો ફાઇલનું નામ બદલો અને નવા નામે ફરીથી ચડાવો.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'આ ફાઇલ {{PLURAL:$1|ફાઇલ|ફાઇલો}} ની પ્રત છે.',
'file-deleted-duplicate'      => 'ફાઇલ ([[:$1]]) ને સમાન ફાઇલ પહેલાં ભૂંસાડી દેવાઇ છે.
આ ફાઇલને ચડાવત પહેલાં હટાવ ઇતિહાસ ચકાસી લો.',
'uploadwarning'               => 'ફાઇલ ચઢાવ ચેતવણી',
'uploadwarning-text'          => 'કૃપયા ફાઈલ સંબધી વર્ણન સુધારો અને ફરી પ્રયત્ન કરો',
'savefile'                    => 'સાચવો',
'uploadedimage'               => '"[[$1]]" ચઢાવ્યું',
'overwroteimage'              => ' "[[$1]]" ની નવી આવૃત્તિ ચઢાવો.',
'uploaddisabled'              => 'ફાઇલ ચઢાવ પ્રતિબંધિત',
'copyuploaddisabled'          => 'URL દ્વાર ફાઇલ ચઢાવ પ્રતિબંધિત',
'uploadfromurl-queued'        => 'તમારી ચઢાવેલી ફાઇલ કતારમાં ઉમેરાઇ છે.',
'uploaddisabledtext'          => 'ફાઇલ ચઢાવવું નિષ્ક્રીય બનાવ્યું છે',
'php-uploaddisabledtext'      => 'PHPમાં ફાઇલ અપલોડ નિષ્ક્રીય કરી દેવાયો છે.
ફાઇલ ચડાવવાના વિકલ્પ ફરી તપાસો.',
'uploadscripted'              => 'આ ફાઇલ HTML કે અન્ય સ્ક્રીપ્ટ કોડ ધરાવે છે જે વેબ બ્રાઉઝર દ્વારા ભૂલથી ઉપયોગમાં લઇ શકાય છે.',
'uploadvirus'                 => 'ફાઇલ વાયરસ સંક્ર્મિત છે
વિવરણ : $1',
'uploadjava'                  => 'આ ફાઇલ એ ZIP ફાઈલ છે જે Java .class ધરાવે છે.
Java ફાઇલ ચડાવવાની પરવાનગી નથી, કેમકે તેઓ સુરક્ષા તપાસને અવગણી નાખવાની ક્ષમતા ધરાવે છે.',
'upload-source'               => 'સ્ત્રોત ફાઇલ',
'sourcefilename'              => 'સ્ત્રોત ફાઇલ નામ',
'sourceurl'                   => 'સ્ત્રોત  URL:',
'destfilename'                => 'લક્ષ્ય ફાઇલ નામ',
'upload-maxfilesize'          => 'મહત્તમ ફાઈલ કદ : $1',
'upload-description'          => 'ફાઇલ માહિતી',
'upload-options'              => 'ચડાવ વિકલ્પ',
'watchthisupload'             => 'આ પાનાને ધ્યાનમાં રાખો',
'filewasdeleted'              => 'આ નામે ફાઇલ ચડાવાઇ હતી અને પછે થી ભૂંસાડી દેવાઇ હતી.
ફરીથી ફાઇલ ચડાવતા પહેલા $1 ચકાસો.',
'upload-wasdeleted'           => "'''ચેતવણી: તમે જે પાનું નવું બનાવવા જઇ રહ્યાં છો તે પહેલાં દૂર કરવામાં આવ્યું છે.'''

આ પાનું સંપાદિત કરતા પહેલાં ગંભીરતાપૂર્વક વિચારજો અને જો તમને લાગે કે આ પાનું ફરી વાર બનાવવું ઉચિત છે, તો જ અહીં ફેરફાર કરજો.
પાનું હટાવ્યાં પહેલાનાં બધા ફેરફારોની સૂચિ તમારી અનુકૂળતા માટે અહીં આપી છે:",
'filename-bad-prefix'         => "તમે ચડાવેલી ફાઇલનું નામ '''\"\$1\"''' થી શરૂ થાય છે, જેમાં ફાઇલની માહિતી વિષે વર્ણન નથી. લાગે ચે તે ડિજીટલ કેમેરા દ્વારા સામાન્ય રીતે સ્વયંચાલિત રીતે અપાતું છબીનું નામ છે.
કૃપયા તમે ચડાવેલી ફાઇલ માટે એક વર્ણનાત્મક નામ આપો.",
'upload-success-subj'         => 'ફાઇલ ચડાવ સફળ',
'upload-success-msg'          => '[$2]થી તમારુઁ ફાઇલ ચડાવ સફળ રહ્યો. તે અહીઁ ઉપલબ્ધ છે. : [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'ફાઇલ ચઢાવ મુશ્કેલી',
'upload-failure-msg'          => 'તમરા ફાઇલ ચડાવવામાં [$2]થી અડચણ થઇ:

$1',
'upload-warning-subj'         => 'ફાઇલ ચઢાવ ચેતવણી',
'upload-warning-msg'          => '[$2]થી કરાયેલ તમારા ચડાવા માં તકલીફ હતી. તમે [[Special:Upload/stash/$1|upload form]]  પર ફરી જઇ આ ત્રુટિ હટાવી ફરી ફાઇલ ચડાવો',

'upload-proto-error'        => 'ખોટો શિષ્ટાચાર',
'upload-proto-error-text'   => 'દુરના ફાઇલ ચઢાવના  URL ની શરૂઆત <code>http://</code> કે <code>ftp://</code>થી થવી જોઇએ.',
'upload-file-error'         => 'આંતરિક ત્રુટિ',
'upload-file-error-text'    => 'સર્વર પર હંગામી ફાઇલ રચતા કોઇ આંતરીક ત્રુટિ નિર્માણ થઇ.
કૃપયા [[Special:ListUsers/sysop|administrator]]નો સંપર્ક કરો.',
'upload-misc-error'         => 'અજ્ઞાત ફાઇલ ચડાવ ચૂક',
'upload-misc-error-text'    => 'ફાઈલ ચડાવતી વખતે કાંઇક ત્રુટિ રહી ગઇ.
ખાત્રી કરો કે URL વૈધ છે અને સક્રીય છે અને ફરી પ્રયત્ન કરો.
તેમ છતાં પણ  પ્રોબ્લેમ રહે તો અહીં સંપર્ક કરશો [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'URLમાં ઘણાં ઉપ નિર્દેશનો છે.',
'upload-unknown-size'       => 'અજ્ઞાત કદ',
'upload-http-error'         => ' HTTP ત્રુટિ : $1',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIP તપાસ કરતી વખતે ત્રુટિ નિર્માણ થઈ',
'zip-wrong-format'    => 'જણાવેલી ફાઇલ ZIP ફાઇલ નથી.',
'zip-bad'             => 'આ ફાઇલોઅ યા તો ખરાબ છે અથવા તો ન વાંચી શકાય એવી ZIP ફાઇલ છે.
આની સંતોષકારક સુરક્ષા તપાસ ન થઈ શકી.',
'zip-unsupported'     => 'આ ZIP ફાઇલ એવા આયામો વાપરે છે જેને મિડિયા વિકિ નથી સમજતું
સુરક્ષા સંબંધી તપાસ સંતોષ પૂર્વક નહીં થાય.',

# Special:UploadStash
'uploadstash'          => 'ગુપ્ત ફાઈલ ચડાવો',
'uploadstash-summary'  => 'આ પાનું તે ફાઇલો સુધી પહોંચાડે છે જે ફાઇલો ચડાવાઇ/ચડાવાઇ રહી છે પણ વિકિ પર પ્રસિદ્ધ થવાની બાકી છે. આ ફાઇલ તેને ચડાવનાર સિવાય અન્ય કોઇ સભ્યને દ્રશ્યમાન નથી.',
'uploadstash-clear'    => 'ગુપ્ત ફાઈલ સાફ કરો',
'uploadstash-nofiles'  => 'કોઈ ગુપ્ત ફાઈલ નથી',
'uploadstash-badtoken' => 'તે કાર્ય કરવું નિષ્ફળ. કદાચ તમારા ફેરફાર હક્કો કાલાતિત થયાં છે. ફરી પ્રયત્ન કરો.',
'uploadstash-errclear' => 'ફાઇલ સાફ સફાઇ અસફળ રહી',
'uploadstash-refresh'  => 'અધ્યતન ફાઇલ યાદિ',

# img_auth script messages
'img-auth-accessdenied' => 'પ્રવેશ વર્જીત',
'img-auth-nopathinfo'   => 'પથ માહિતી ગાયબ.
આ માહિતી પસાર કરવા તમારું સર્વર સજ્જ નથી.
તે કદાચ CGI-આધારિત હોય અને img_authને આધાર ન આપે.
જુઓ http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'અર્જીત પથ ચડાવેલ ફાઈલની ડીરેક્ટરીમાં નથી',
'img-auth-badtitle'     => '"$1" માટે વૈધ શીર્ષક ન શોધી શકાયું',
'img-auth-nologinnWL'   => 'તમે પ્રવેશ કર્યો નથી અને અને : $1 ધવલ યાદિમાં નથી.',
'img-auth-nofile'       => ' ફાઇલ "$1" અસ્તિત્વમાં નથી',
'img-auth-isdir'        => 'તમે ડયરેક્ટરી "$1" ને પહોંચવાનો પ્રયાસ કરો છો.
માત્ર ફાઇલ સુધે પહોંચવાની પરવાનગી છે.',
'img-auth-streaming'    => 'ચિત્ર માહીતી  "$1" ઉતરી રહી છે.',
'img-auth-public'       => ' img_auth.php દ્વારા કાર્યાન્વીત થતી ક્રિયા નીજી વિકિ માંથી ફાઇલ કાઢવા વપરાય છે. 
આ વિકિ ની ગોઠવણ જન વિકી તરીકે થયેલી છે.
મહત્તમ સુરક્ષા માટે, img_auth.php ને નિષ્ક્રિય કરાઇ છે.',
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
'upload-curl-error6'       => 'URL સુધી ન પહોંચી શકાયું.',
'upload-curl-error6-text'  => 'અપાયેલ URL સુધી પહોંચી ન શકાયું 
કૃપયા ફરી  URL ચકાસો અને ખાત્રી કરો કે સાઇટ સક્રિય છે.',
'upload-curl-error28'      => 'સમય સમાપ્ત સંદેશ ચડાવો',
'upload-curl-error28-text' => 'આ વેબ સાઇટે પ્રત્યુત્ત આપવામાં ઘણો લંબો સમય લે છે.
કૃપયા ખાત્રી કરો કે સાઇટ સક્રીય છે કે. થોસો સમ્ય રાહ જોઇ ફરી પ્રતિક્ષા કરો.
અથવા તમે અમુક ઓછા વ્યસ્ત સમયે પ્રયત્ન કરશો.',

'license'            => 'પરવાના',
'license-header'     => 'પરવાના',
'nolicense'          => 'કોઇ વિકલ્પ પસંદ નથી કરાયો',
'license-nopreview'  => '(ઝલક મોજુદ નથી)',
'upload_source_url'  => ' (એક વૈધ , જાહેર URL)',
'upload_source_file' => '(તમારા કોમ્પ્યુટર પરની એક ફાઇલ)',

# Special:ListFiles
'listfiles-summary'     => 'આ ખાસ પાનું બધી ચડાવેલે ફાઇલ બતાવે છે.
સૌથી છેલ્લે ચડાવેલ ફાઇલ સૌથી ઉપર બતાવી છે. 
સ્તંભના શીર્ષક પર ક્લીક કરતા અનુક્રમ બદલાશે .',
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
'filedelete-reason-dropdown'  => '* હટાવવાનાં સામાન્ય કારણો
** પ્રકાશનાધિકાર ભંગ
** ડુપ્લીકેટ ફાઇલ',
'filedelete-edit-reasonlist'  => 'ભુંસવાનું કારણ બદલો.',
'filedelete-maintenance'      => 'સાર સંભાળ કાર્ય જારી હોઇ ફાઇલો નું હટાવવું અને પુનઃ સ્થાપન થોડા સમય માટે  બંધ  છે',

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
'unusedtemplates'     => 'વણ વપરાયેલાં ઢાંચા',
'unusedtemplatestext' => 'આ પાનું નામ સ્થળ {{ns:template}} માં આવેલ અને અન્ય પાનામાં ન સમાવિષ્ટ પાના ની યાદિ બતાવે છે.
ઢાંચાને હટાવતા પહેલા તેમાં વપરાયેલી અન્ય પાનાને જોડતી કડીઓ તપાસી લેશો.',
'unusedtemplateswlh'  => 'અન્ય કડીઓ',

# Random page
'randompage'         => 'કોઈ પણ એક લેખ',
'randompage-nopages' => 'આ {{PLURAL:$2|નામસ્થળ|નામસ્થળો}}માં કોઇ પાના નથી: $1.',

# Random redirect
'randomredirect'         => 'દિશાહીન  નિર્દેશન',
'randomredirect-nopages' => 'નામ સ્થળ "$1" માં કોઇ દિશા નિર્દેશન નથી',

# Statistics
'statistics'                   => 'આંકડાકિય માહિતિ',
'statistics-header-pages'      => 'પાના સંબંધી આંકડાકીય માહિતી',
'statistics-header-edits'      => 'આંકડાકીય માહિતી બદલો',
'statistics-header-views'      => 'આંકડાકીય માહિતી જુઓ',
'statistics-header-users'      => 'સભ્ય સંબંધી આંકડાકીય માહિતી',
'statistics-header-hooks'      => 'અન્ય આંકડાકીય માહિતી',
'statistics-articles'          => 'લેખનું પાનું',
'statistics-pages'             => 'પાનાં',
'statistics-pages-desc'        => 'ચર્ચા પાના અને નીર્દેશીત પાના સહિત વિકિના બધા પાના',
'statistics-files'             => 'ચડાવેલ ફાઇલો',
'statistics-edits'             => '{{SITENAME}} શરૂ થયા પછી ફેરફાર થયેલ પાના',
'statistics-edits-average'     => 'દર પાના પર સરાસરી ફેરફારો',
'statistics-views-total'       => 'સરવાળો',
'statistics-views-total-desc'  => 'અવિહરમાન પાના અને ખાસ પાના આમા શામિલ નથી.',
'statistics-views-peredit'     => 'પ્રતિ ફેરફાર ના દેખાવ',
'statistics-users'             => 'નોંધણી થયેલ  [[Special:ListUsers|users]]',
'statistics-users-active'      => 'સક્રીય સભ્યો',
'statistics-users-active-desc' => 'સભ્ય કે જેમણે છેલ્લા {{PLURAL:$1|દિવસ|$1 દિવસો}}માં ફેરફારો કર્યાં છે',
'statistics-mostpopular'       => 'સૌથી વધુ જોવાયેલા પાના',

'disambiguations'      => 'અસંદિગ્ધ શબ્દોવાળા પાના',
'disambiguationspage'  => 'Template:અસંદિગ્ધ',
'disambiguations-text' => "નીચેના પાના  '''સંદિગ્ધ વાક્યો વાળા પાના''' સાથે કડી દ્વારા જોડાયેલા છે.
તેના કરતા તેને યોગ્ય તે વિષ્ય સાથે જોડાયેલા હોવા જોઇએ.<br />
આ પાનાને સંદિગ્ધ  વાક્યો વાળા પાના ત્યારે કહી શકાય જ્યારે તે [[MediaWiki:Disambiguationspage]] નામના ઢાંચા સાથે જોડાયેલા હોય.",

'doubleredirects'                   => 'બનણું દિશાનિર્દેશિત',
'doubleredirectstext'               => 'આ પાનું દિશા નિર્દેશિત પાના પર થયેલા દિશા નિર્દેશિત પાનાની યાદિ બતાવે છે.
દરેક લિટીમાં પાના પ્રથમ અને દ્વીતીય દિશા નિર્દેશન ક્ડી બતાવે છે, તે સિવાય દ્વીતીય દિશા નિર્દેશનનું લક્ષ્ય પણ બતાવે છે કે મોટે ભાગે મૂળ પાનું હોઇ શકે છેૢ જેના પર પ્રથમ દિશા નિર્દેશન લક્ષિત છે. <del>Crossed out</del> લિટીઓ  નો ઉત્તર મેળવાયો છે.',
'double-redirect-fixed-move'        => '[[$1]] હટાવી દેવાયું છે.
હવે તે [[$2]] પરાનિર્દેશીત છે.',
'double-redirect-fixed-maintenance' => '[[$1]] થી [[$2]] સુધી બમણા દિશાનિર્દેશન સમાકરાયા.',
'double-redirect-fixer'             => 'નિર્દેશન સમારનાર',

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
'lonelypagestext'         => 'નીચે બતાવેલા પાના {{SITENAME}} પર કે થી કડી દ્વારા જોડાયેલ નથી',
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
'deadendpagestext'        => 'નીચેના પાના {{SITENAME}}ના અન્ય પાનાને કડીઓ દ્વારા નથી જોડતાં.',
'protectedpages'          => 'સંરક્ષિત પાનાઓ',
'protectedpages-indef'    => 'ફક્ત અનિશ્ચિત સુરક્ષા ધરાવતા પાના',
'protectedpages-cascade'  => 'માત્ર પગથિયામય સુરક્ષા વાળા પગ',
'protectedpagestext'      => 'નીચેના પાના કોઈ ફેરફાર કે હટાવવા થી સુરકક્ષીત કરાયા છે',
'protectedpagesempty'     => 'આ વિકલ્પો દ્વારા કોઈ પાના સુરક્ષીત કરાયા નથી',
'protectedtitles'         => 'સંરક્ષીત શીર્ષકો',
'protectedtitlestext'     => 'આ શીર્ષકો રચના માટે આરક્ષીત છે',
'protectedtitlesempty'    => 'આ પરિબળો દ્વારા કોઇ પણ શીર્ષકો સચવાયા નથી.',
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
'unusedimagestext'        => 'નીચેની ફાઇલો અસ્તિત્વમાં તો છે પણ કોઇ પાના પર તેનો ઉપયોગ થયેલ નથી.
ક્ર્પયા નોંધ લેખો કે આ ફાઇલને અન્ય વેબસાઇટો સીધા URL ના માધ્યમ દ્વારા વાપરી શકે છે અને કોઇ પણ ઉપયોગ સિવાય ઘણી ફાઇલો અહીં યાદિમાં હોઇ શકે.',
'unusedcategoriestext'    => 'નીચેના શ્રેણી પાના છે પણા કોઈ લેખ આનો ઉપયોગ કરતાં નથી',
'notargettitle'           => 'કોઇ લક્ષ્ય નથી',
'notargettext'            => 'આ ક્રિયા જેના પર કરવાની છે તે સભ્ય કે પાનાની માહિતી તમે પૂરી પાડી નથી',
'nopagetitle'             => 'આવું કોઇ લક્ષ્ય પાનું નથી',
'nopagetext'              => 'તમે લખેલ પાનું અસ્તિત્વમાં નથી',
'pager-newer-n'           => '{{PLURAL:$1|નવું 1|નવા $1}}',
'pager-older-n'           => '{{PLURAL:$1|જુનું 1|જુનાં $1}}',
'suppress'                => 'દુર્લક્ષ',
'querypage-disabled'      => 'કાર્ય બજવણી ના કારણે આ ખાસ પાનું નિસ્ક્રીય કરાયું છે.',

# Book sources
'booksources'               => 'પુસ્તક સ્ત્રોત',
'booksources-search-legend' => 'પુસ્તક સ્ત્રોત શોધો',
'booksources-isbn'          => 'આઇએસબીએન:',
'booksources-go'            => 'જાઓ',
'booksources-text'          => 'નીચે દર્શાવેલ યાદી એ કડીઓ બતાવે છે જેઓ નવા અને જૂના પુસ્તકો  વેચે છે , અને તમે માંગેલ વસ્તુ સંબંધિ વધુ મહિતી પણ ધરાવી શકે છે.',
'booksources-invalid-isbn'  => 'આપેલ ISBN વૈધ નથી લાગતો. મૂળ સ્ત્રોત થે ચકાસીને ભૂલ શોધી ખરી માહિતી આપો.',

# Special:Log
'specialloguserlabel'  => 'સભ્ય:',
'speciallogtitlelabel' => 'શિર્ષક:',
'log'                  => 'લૉગ',
'all-logs-page'        => 'બધાં જાહેર માહિતીપત્રકો',
'alllogstext'          => '{{SITENAME}} ના લોગનો સંયુક્ત વર્ણન.
તમે લોગનો પ્રકાર,સભ્ય નામ અથવા અસરગ્રસ્ત પાના આદિ પસંદ કરી તમારી યાદિ ટૂંકાવી શકો.',
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
'allpagesbadtitle'  => 'આપનું ઈચ્છિત શીર્ષક અમાન્ય છે, ખાલી છે, અથવાતો અયોગ્ય રીતે આંતર-ભાષિય કે આંતર-વિકિ સાથે જોડાયેલું શીર્ષક છે.
શક્ય છે કે તેમાં એક કે વધુ એવા અક્ષર કે ચિહ્નો છે કે જે પાનાનાં શીર્ષક માટે અવૈધ છે.',
'allpages-bad-ns'   => '{{SITENAME}} ને નામસ્થળ  "$1" નથી.',

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
'linksearch'       => 'બાહ્ય કડીઓ',
'linksearch-pat'   => 'શોધા આલેખ',
'linksearch-ns'    => 'નામાવકાશ:',
'linksearch-ok'    => 'શોધ',
'linksearch-text'  => '"*.wikipedia.org" જેવા વાઈલ્ડાકાર્ડ અહીં વાપરી શકાશે.
માન્ય પ્રોટોકોલ : <tt>$1</tt>',
'linksearch-line'  => '$1 એ $2થી જોડાયેલ છે',
'linksearch-error' => 'યજમાન નામની શરૂઆતમાં જ વાઈલ્ડકાર્ડ પ્રકટ થશે',

# Special:ListUsers
'listusersfrom'      => 'અહીંથી સભ્યો બતાવો',
'listusers-submit'   => 'બતાવો',
'listusers-noresult' => 'કોઇ સભ્ય ન મળ્યો',
'listusers-blocked'  => '(પ્રતિબંધિત)',

# Special:ActiveUsers
'activeusers'            => 'સક્રીયા સભ્ય છુપાવો',
'activeusers-intro'      => 'છેલ્લા  $1 {{PLURAL:$1|દિવસ|દિવસો}}માં જે સભ્યોએ કોઇક યોગદાન કર્યું હોય તેમની યાદિ.',
'activeusers-count'      => '$1 {{PLURAL:$1|ફેરફાર|ફેરફારો}} છેલ્લા {{PLURAL:$3|દિવસ |$3 દિવસો }}માં',
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
'listgrouprights-summary'              => 'નીચે આ વિકિ પર પરિભાષિત સભ્ય જૂથની યાદિ આપી છે અને તેમના હક્કો પણ આપેલા છે.
નીજી હક્કો માટે [[{{MediaWiki:Listgrouprights-helppage}}|additional information]] માહિતી.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">અધિકાર અપાયા</span>
* <span class="listgrouprights-revoked">અધિકાર હટાવાયા</span>',
'listgrouprights-group'                => 'જૂથ',
'listgrouprights-rights'               => 'હક્કો',
'listgrouprights-helppage'             => 'Help:સમૂહ હક્કો',
'listgrouprights-members'              => '(સભ્યોની યાદી)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|સમૂહ|સમૂહો}} ઉમેરો: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|સમૂહ|સમૂહો}} હટાવો: $1',
'listgrouprights-addgroup-all'         => 'બધા જૂથ ઉમેરો',
'listgrouprights-removegroup-all'      => 'બધા સમૂહો હટાવો',
'listgrouprights-addgroup-self'        => ' {{PLURAL:$2|સમૂહ|સમૂહો}}પોતાના ખાતામાં ઉમેરો: $1',
'listgrouprights-removegroup-self'     => 'તેમના પોતાના ખાતમાંથી  {{PLURAL:$2|group|groups}}  હટાવો : $1',
'listgrouprights-addgroup-self-all'    => 'દરેક જૂથને તેમના પોતાના ખાતા માં ઉમેરો',
'listgrouprights-removegroup-self-all' => 'બધા જૂથને તેમના પોતાના ખાતામાંથી હટાવો',

# E-mail user
'mailnologin'          => 'મેળવનારનું સરનામું નથી',
'mailnologintext'      => 'અન્ય સભ્યને ઇ-મેલ મોકલવા માટે તમે [[Special:UserLogin|logged in]] પ્રવેશ કરેલ હોવો જોઈએ અને તમારા[[Special:Preferences|preferences]] વિકલ્પોમાં તમારા ઈ-મેલ સરનામાની પુષ્ટિ થયેલી હોવી જોઈએ',
'emailuser'            => 'સભ્યને ઇ-મેલ કરો',
'emailpage'            => 'ઈ-મેલ સભ્ય',
'emailpagetext'        => 'તમે નીચે દર્શાવેલ ફોર્મ વાપરી ઇ-મેલ મોકલી શકો છો.
તમે તમારી માહિતીમાં [[Special:Preferences|your user preferences]] જે ઇ-મેલ લખ્યો હશે  તે દ્વારા ના નામ હેઠળ દેખાશે, જેથી ઇ-મેલ મેળવનાર તમને સંદેશાનો જવાબ આપી શકશે.',
'usermailererror'      => 'મેલ વસ્તુ પાઠવવામાં ત્રુટિ',
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
'watchlistanontext'    => 'તમારી ધ્યાનસૂચિની વસ્તુઓ જોવા અને ફેરફાર કરવા $1 કરો',
'watchnologin'         => 'પ્રવેશ કરેલ નથી',
'watchnologintext'     => 'ધ્યાનસૂચિમાં ફેરફારાકરવા તમે પ્રવેશ [[Special:UserLogin|logged in]] કરેલો હોવો જરૂરી છે.',
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
'watchnochange'        => 'તમારા દ્વારા નિરીક્ષિત પાના આપેલ સમય ગાળામાં ફેરફારીત થયા',
'watchlist-details'    => 'ચર્ચાનાં પાનાં ન ગણતા {{PLURAL:$1|$1 પાનું|$1 પાનાં}} ધ્યાનસૂચીમાં છે.',
'wlheader-enotif'      => '*ઈ-મેલા સૂચના પદ્ધતિ સક્રીય કરાઈ',
'wlheader-showupdated' => "*તમારી મુલાકાત લીધા પછી બદલાયેલા પાના  '''ઘાટા''' અક્ષરો વડે દર્શાવ્યાં છે",
'watchmethod-recent'   => 'હાલમાં ફેરફાર થયેલ પાનાની ચકાસણી જારી',
'watchmethod-list'     => 'હાલમાં થયેલ ફેરફાર માટે નીરીક્ષીત પાના તપાસાય છે',
'watchlistcontains'    => 'તમારી ધ્યાનસૂચીમાં $1 {{PLURAL:$1|પાનું|પાનાં}} છે.',
'iteminvalidname'      => "વસ્તુ '$1' સાથે ગડબડ, અવૈધ નામ...",
'wlnote'               => "છેલ્લાં {{PLURAL:$2|કલાક|'''$2''' કલાકો}} માં થયેલા {{PLURAL:$1|ફેરફાર| '''$1''' ફેરફારો }} .",
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
'enotif_subject'               => '{{SITENAME}} નું પાનું $PAGETITLE $PAGEEDITOR દ્વારા $CHANGEDORCREATED',
'enotif_lastvisited'           => 'તમારા છેલ્લા ફેરફાર પછી થયેલા ફેરફાર $1 જુઓ',
'enotif_lastdiff'              => 'આ ફેરફાર જોવા $1 જુઓ',
'enotif_anon_editor'           => 'અજ્ઞાત સભ્ય $1',
'enotif_body'                  => '↓ પ્રિય $WATCHINGUSERNAME,


 {{SITENAME}}નું પાનું  $PAGETITLE $PAGEEDITDATE ના દિવસે by $PAGEEDITOR દ્વારા $CHANGEDORCREATED છે , તાજા પુનરાવર્તન માટે $PAGETITLE_URL જુઓ.

$NEWPAGE

સભ્યનો સારાંશ: $PAGESUMMARY $PAGEMINOREDIT

તંત્રી નો સંપર્ક :
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

આ પાનામાં ભવિષ્યમાં થનાર કોઇ પણ ફેરફારની સૂચના તમને નહીં મળે  સિવાય કે તમે તે પાનાની મુલાકાત લો.
તમે તમારી ધ્યાન સૂચિમાં તમે જોયેલા પાના સંબંધી સૂચનાને લાગતા વિલપોમાં ફેરફાર કરી શકો છો.

             તમારો મિત્ર {{SITENAME}} સૂચના પ્રક્રિયા   
 
--
તમારી ધ્યાનસૂચિ ના  વિક્લ્પ ગોઠવણ માટે  આ જુઓ {{fullurl:{{#special:Watchlist}}/edit}}

ધ્યાનસૂચિમાંથી વિષ્ય હટાવવા , આ જુઓ  
$UNWATCHURL

મંતવ્યો અને આગળની મદદ માટે 
{{fullurl:{{MediaWiki:Helppage}}}}',

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
'deletereason-dropdown'  => '* હટાવવાનાં સામાન્ય કારણો 
** લેખકની વિનંતી
** પ્રકાશનાધિકાર ભંગ 
** ભાંગફોડીયા પ્રવૃત્તિ',
'delete-edit-reasonlist' => 'ભુંસવાનું કારણ બદલો.',
'delete-toobig'          => 'આ પાનાના ફેરફારોનો ઇતિહાસ ખૂબ લાંબો છે , $1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}થી પણ વધારે.
{{SITENAME}}ને અક્સ્માતે ખોરવાતું અટકાવવા આવા પાનાને હટાવવા પર પ્રતિબંધ છે.',
'delete-warning-toobig'  => 'આ પાનાનો ઇતિહાસ ઘણો લાંબો છે લગભગ  $1 {{PLURAL:$1|ફેરફાર|ફેરફારો}}.
તેને ભૂંસતા {{SITENAME}}ના માહિતીસંચને લાગતા કામકાજ પર અસર થૈ શકે છે;
સંભાળ પૂર્વક આગળ વધો.


Deleting it may disrupt database operations of {{SITENAME}};',

# Rollback
'rollback'          => 'ફેરફારો ઉલટાવો',
'rollback_short'    => 'ઉલટાવો',
'rollbacklink'      => 'પાછું વાળો',
'rollbackfailed'    => 'ઉલટાવવું નિષ્ફળ',
'cantrollback'      => 'આ ફેરફારો ઉલટાવી નહી શકાય
છેલ્લો ફેરફાર આ પાના ના રચયિતા દ્વારા જ થયો હતો',
'alreadyrolled'     => '[[User:$2|$2]] ([[User talk:$2|talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) દ્વારા થયેલ[[:$1]]ના  ફેરફારો ઉલટાવી ન શકાયા;
કોઇક અન્ય સભ્યએ આ પાનાપર ફેરફાર કરી દીધા છે.

આ પાના પર ના છેલ્લા ફેરફારો [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) દ્વારા કરવામાં આવ્યાં હતાં.',
'editcomment'       => "ફેરફાર સારાંશ હતી: \"''\$1''\".",
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|talk]])દ્વારા ફેરફરોને  [[User:$1|$1]] દ્વારા કરેલા છેલ્લા સુધારા સુધી ઉલટાવાયા.',
'revertpage-nouser' => ' (સભ્ય નામ હટાવ્યું) દ્વારા થયેલ ફેરફારને  [[User:$1|$1]]ના દ્વારા થયેલ છેલ્લા પુનરાવર્તન પર પાછા લઇ જવાયા',
'rollback-success'  => '$1 દ્વારા થયેલા ફેરફારો ઉલટાવાયા
તેને $2 દ્વારા થયેલ સંપાદન સુધી લઇ જવાયું',

# Edit tokens
'sessionfailure-title' => 'સત્ર નિષ્ફળ',
'sessionfailure'       => 'તમારા પ્રવેશ સત્રમાં કઈંક ત્રુટિ છે ;
સત્ર અપહરણ ની શક્યતા ન રહે તેમાટે આ સત્ર રદ્દ્ કરવામાં આવે છે.
પાછળ જોયેલા પાને પાછા જાઓ , અને ફરી પ્રયત્ન કરો .',

# Protect
'protectlogpage'              => 'સુરક્ષા માહિતિ પત્રક',
'protectlogtext'              => 'પાનાના સંરક્ષણ લગાવવાના અને હટાવવાની યાદિ આપી છે 
હાલમાં સક્રીય પાના સુરક્ષાની યાદિ [[Special:ProtectedPages|protected pages list]] આ પ્રમાણે છે',
'protectedarticle'            => 'સુરક્ષિત "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]"નું સુરક્ષાસ્તર બદલ્યું',
'unprotectedarticle'          => 'અસુરક્ષિત "[[$1]]"',
'movedarticleprotection'      => 'સંરક્ષણ વિકલ્પ  "[[$2]]" થી "[[$1]]" પર હટાવયા.',
'protect-title'               => '"$1"નું સુરક્ષાસ્તર બદલ્યું',
'prot_1movedto2'              => '[[$1]]નું નામ બદલીને [[$2]] કરવામાં આવ્યું છે.',
'protect-legend'              => 'સઁરક્ષણને બહાલી આપો',
'protectcomment'              => 'કારણ:',
'protectexpiry'               => 'સમાપ્તિ:',
'protect_expiry_invalid'      => 'સમાપ્તિનો સમય માન્ય નથી.',
'protect_expiry_old'          => 'સમાપ્તિનો સમય ભૂતકાળમાં છે.',
'protect-unchain-permissions' => 'આગળના સંરક્ષણ પ્રતિબંધ વિકલ્પ મુક્ત કરો',
'protect-text'                => "અહિં તમે પાના '''<nowiki>$1</nowiki>'''નું સુરક્ષા સ્તર જોઈ શકો છો અને તેમાં ફેરફાર પણ કરી શકશો.",
'protect-locked-blocked'      => "જ્યારે પાના પર રોક લગાવેલી હોય ત્યારે તેનું સુરક્ષા સ્તર બદલાવી ન શકાય.
'''$1''' પાનાની હાલની સુરક્ષા ગોઠવણી આપેલી છે",
'protect-locked-dblock'       => 'માહિતી સંચ બંધ હોવાથી સંરક્ષણ સ્તર બદલી નહીં શકાય્
આ પાનાના હાલના સુરક્ષા સ્તર : $1',
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
'protect-expiry-options'      => '૧ કલાક:1 hour,૧ દિવસ:1 day,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
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
'undelete'                     => 'ભૂંસાડેલા પાના બતાવો',
'undeletepage'                 => 'હટાવેલ પાના જુઓ અને પુંર્જીવીત કરો',
'undeletepagetitle'            => "'''નીચે [[:$1|$1]] ના ભૂંસાડેલ સંપાદનો છે.'''.",
'viewdeletedpage'              => 'ભૂંસાડેલા પાના બતાવો',
'undeletepagetext'             => 'નીચેના {{PLURAL:$1|પાનું હટાવી દેવાયું છે |$1 પાના હટાવી દેવાયા છે}} તે હજી પ્રાચીન દસ્તવેજમાં છે અને પુનઃસ્થાપિત કરી શકાશે.
પ્રાચીન દસ્તાવેજ કાલાંતરે સાફ કરી શકાય છે.',
'undelete-fieldset-title'      => 'સંપાદનો પાછા લાવો',
'undeleteextrahelp'            => "આ પાનાનો આખો ઇતિહાસ પુનઃસ્થાપિત કરવા , બધા ખાનાને બિન-અંકિત રાખો  અને અહીં  '''''{{int:undeletebtn}}'''''  ક્લિક કરો.
To perform a selective restoration, check the boxes corresponding to the revisions to be restored, and click '''''{{int:undeletebtn}}'''''.
Clicking '''''{{int:undeletereset}}''''' will clear the comment field and all checkboxes.",
'undeleterevisions'            => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}} દસ્તાવેજીત કરાયા',
'undeletehistory'              => 'જો તમે આપાનું પુનઃસ્થાપિત કરશો તો બધાં ફેરફારો પણ ઈતિહાસમાં પુનઃસ્થાપિત થશે.
તે પાનું હટાવાયા પછી આજ નામે જો કોઈ અન્ય પાનું બનાવાયું હશે તો પુનઃ સ્થપનના ફેર્ફાર પ્રાચીન ઇતિહાસમાં દેખાશે.',
'undeleterevdel'               => 'જો ફાઇલનું ટોચના પાનું કે  પુનરાવર્તન ભૂંસાઇ જાય તેમ હોય તો બિનરદ્દીકરણ કરવાની ક્રિયા નહીં કરવામાં આવે.
આવા મામલામાં તમારે સૌથી અદ્યતન ફેરફારોને છતાં કરવાઅ જોઇએ.',
'undeletehistorynoadmin'       => 'આ પાનું ભૂંસાડી દેવાયું છે.
ભૂંસાડીદેવાનું કારણ નીચે આપેલ સારાંશામાં આપેલ છે.
આ ભૂંસાડેલ લેખન પ્રબંધકો પાસે મળશે.',
'undelete-revision'            => '$1 ( $4 તારીખે , $5 વાગ્યા)  સુધી કરેલા ફેરફારો  $3 દ્વારા હટાવાયા:',
'undeleterevision-missing'     => 'અવૈધ કે ગાયબ પુનરાવર્તન.
તમારી કડી ખરાબ હશે અથવા ઇતિહાસમાંથી તે માહિતી હટાવાઇ હશે.',
'undelete-nodiff'              => 'કોઇ પ્રાચીન સંપદનો નથી મળ્યાં',
'undeletebtn'                  => 'પાછું વાળો',
'undeletelink'                 => 'જુઓ/પાછુ વાળો',
'undeleteviewlink'             => 'જુઓ',
'undeletereset'                => 'ફરી ગોઠવો',
'undeleteinvert'               => 'પસંદગી ઉલટાવો',
'undeletecomment'              => 'કારણ:',
'undeletedarticle'             => '"[[$1]]" પુનઃસ્થાપિત કર્યું',
'undeletedrevisions'           => '{{PLURAL:$1|૧ સંપાદન|$1 સંપાદનો}} પુન સ્થાપિત કરાયા',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 ફેરફાર|$1 ફેરફારો}} અને {{PLURAL:$2|1 ફાઈલા|$2 ફાઈલો}} પુનઃસ્થાપિત',
'undeletedfiles'               => '{{PLURAL:$1|1 ફાઇલ|$1 ફાઇલો}} પુનઃસ્થાપિત',
'cannotundelete'               => 'પુનઃજીવિત કરવાનું કાર્ય અસફળ 
કોઇકે  આ પાનાને પહેલેથી પુનઃજીવિત કર્યું હોઇ શકે.',
'undeletedpage'                => "'''$1 પુનઃસ્થાપિત કરાયા'''

તાજેતરમાં હટાવેલા કે પુનઃસ્થાપિત થયેલા ફેરફારની નોંધ નો સંદર્ભ અહીં ઉપ્લબ્ધ [[Special:Log/delete|deletion log]].",
'undelete-header'              => 'હાલમાં હટાવેલ પાનાનો  [[Special:Log/delete|the deletion log]]  જુઓ',
'undelete-search-box'          => 'હટાવેલા પાનાની શોધ',
'undelete-search-prefix'       => 'આનાથી શરૂ થતા પાના બતાવો.',
'undelete-search-submit'       => 'શોધો',
'undelete-no-results'          => 'રદ્દીકરણ ઈતિહાસમાં આને  મળતા પાના ન મળ્યાં',
'undelete-filename-mismatch'   => 'સમય છાપ $1 સાથેની ફાઈલના પુનરાવર્તન  પુનઃસ્થાપનાન કરી શકાયા.',
'undelete-bad-store-key'       => 'સમયચિન્હ $1 સાથેના ફાઈલ પુનરાવર્તનોને ભૂંસાડી નહીં શકાય : ભૂંસાડાય તે પહેલા ફાઇલ ગાયબ છે',
'undelete-cleanup-error'       => 'વણ વપરાયેલી પ્રાચીન  ફાઇલને ભૂંસવામાં ત્રુટિ "$1".',
'undelete-missing-filearchive' => 'ફાઇલ સંગ્રહ ID $1 પુનઃ સ્થાપિત ન કરી શકાયું કેમકે તે માહિતી સંચમાં મોજુદ નથી.
તે પહેલેથી પુનઃસ્થાપિત  કરાયું હશે.',
'undelete-error-short'         => 'આ ફાઇલ પુનર્જીવીત કરવામાં તકલીફ : $1',
'undelete-error-long'          => '$1 આ ફાઈલ ભૂંસતી વખતે ચૂક થઈ',
'undelete-show-file-confirm'   => 'શું તમને ખાત્રી છે કેતમે $2 તારીખ $3 વાગ્યા સુધીના "<nowiki>$1</nowiki>" ફાઇલ ના ફેરફાર જોવા માંગો છો?',
'undelete-show-file-submit'    => 'હા',

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

'sp-contributions-newbies'             => 'માત્ર નવા ખુલેલાં ખાતાઓનું યોગદાન બતાવો',
'sp-contributions-newbies-sub'         => 'નવા ખાતાઓ માટે',
'sp-contributions-newbies-title'       => 'નવા ખાતાના સભ્યોનું યોગદાન',
'sp-contributions-blocklog'            => 'પ્રતિબંધ સૂચિ',
'sp-contributions-deleted'             => 'સભ્યનું ભૂંસેલું યોગદાન',
'sp-contributions-uploads'             => 'ખાસ યોગદાન / ચડાવેલ ફાઇલ',
'sp-contributions-logs'                => 'લૉગ',
'sp-contributions-talk'                => 'યોગદાનકર્તાની ચર્ચા',
'sp-contributions-userrights'          => 'સભ્ય હક્ક પ્રબંધન',
'sp-contributions-blocked-notice'      => 'આ સભ્ય પ્રતિબંધિત છે
તમારા સંદર્ભ માટે પ્રતિબંધિત સભ્યોની યાદિ આપી છે',
'sp-contributions-blocked-notice-anon' => 'આ  IP સરનામું  હમણા પ્રતિબંધિત છે
તમરા સંદર્ભ માટે તાજી પ્રતિબંધ યાદિ આપી છે.',
'sp-contributions-search'              => 'યોગદાન શોધો',
'sp-contributions-username'            => 'IP સરનામું અથવા સભ્યનામ:',
'sp-contributions-toponly'             => 'તાજેતરમાં થયેલા ફેરફારો જબતાવો',
'sp-contributions-submit'              => 'શોધો',

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
'blockiptext'                     => 'કોઇ ચોક્કસ IP સરનામું કે સભ્ય  ના  લેખન યોગદાન પર પ્રતિબંધ મુકવા નીચેનું ફોર્મ વાપરો.
તેનો ઉપયોગ માત્ર ભાંગફોડિયા પ્રવૃત્તિઓને રોકવા અને  [[{{MediaWiki:Policy-url}}|policy]] અનુસાર જ હોવી જોઇએ.
કારણનું ખાનું અવશ્ય ફરશો (દા.ત. અમુક ભાંગફોડ કરાયેલ પાનું)',
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
'ipbcreateaccount'                => 'ખાતા ખોલવા પર પ્રતિબંધ',
'ipbemailban'                     => 'સભ્યના ઇ-મેલ મોકલવા પર પ્રતિબંધ મૂકો',
'ipbenableautoblock'              => 'આ સભ્ય દ્વારા વપરાયેલ  IP સરનામા પર અને તત-પશ્ચ્યાત વપરાયેલ IP સરનામા ને સ્વયંચાલિત રીતે રોક લગાવો',
'ipbsubmit'                       => 'આ સભ્ય પર પ્રતિબંધ મૂકો',
'ipbother'                        => 'અન્ય સમય',
'ipboptions'                      => '૨ કલાક:2 hours,૧ દિવસ:1 day,૩ દિવસ:3 days,૧ સપ્તાહ:1 week,૨ સપ્તાહ:2 weeks,૧ માસ:1 month,૩ માસ:3 months,૬ માસ:6 months,૧ વર્ષ:1 year,અમર્યાદ:infinite',
'ipbotheroption'                  => 'બીજું',
'ipbotherreason'                  => 'અન્ય/વધારાનું કારણ:',
'ipbhidename'                     => 'ફેરફારો અને યાદિમાંથી સભ્ય નામ છુપાવો',
'ipbwatchuser'                    => 'આ સભ્યના સભ્ય અને ચર્ચા પાના જુઓ',
'ipb-change-block'                => 'આ પ્રણાલી સાથે સભ્યને  ફરી પ્રતિબંધિત કરો',
'badipaddress'                    => 'અવૈધ IP સરનામું',
'blockipsuccesssub'               => 'સફળ પ્રતિબંધ મુકાયો',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] પરા રોક લગાવાઈ છે<br />
રોક લગાવેલ સભ્યોની યાદિ [[Special:IPBlockList|IP block list]].',
'ipb-edit-dropdown'               => 'પ્રતિબંધ કારણોમાં ફેરફાર કરો',
'ipb-unblock-addr'                => '$1 પરનો પ્રતિબંધ ઉઠાવો',
'ipb-unblock'                     => 'સભ્યનામ કે  IP સરનામું અપ્રતિબંધિત કરો.',
'ipb-blocklist'                   => 'વિહરમાન પ્રતિબંધો જુઓ',
'ipb-blocklist-contribs'          => 'સભ્ય $1નું યોગદાન',
'unblockip'                       => 'સભ્ય પરનો પ્રતિબંધ હટાવો',
'unblockiptext'                   => 'પહેલા રોક લગાડેલ IP સરનામા કે સભ્ય ના પ્રવેશને પુનઃસ્થાપિત કરવા નીચેનું ફોર્મેટ વાપરો.',
'ipusubmit'                       => 'આપ્રતિબંધન હટાવો',
'unblocked'                       => '[[User:$1|$1]] પ્રતિબંધિત કરાયા',
'unblocked-id'                    => ' $1 નો પ્રતિબંધ હટાવાયો',
'ipblocklist'                     => 'પ્રતિબંધિત IP સરનામા અને સભ્યોની યાદી',
'ipblocklist-legend'              => 'પ્રતિબંધિત સભ્ય શોધો',
'ipblocklist-submit'              => 'શોધો',
'ipblocklist-localblock'          => 'સ્થાનીય પ્રતિબંધ',
'ipblocklist-otherblocks'         => 'અન્ય {{PLURAL:$1|પ્રતિબંધન|પ્રતિબંધનો}}',
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
'autoblocker'                     => 'તમારા પર સ્વયંચાલિત રીતે રોક લગાવાઇ છે કેમકે તમારો IP હાલમાં "[[User:$1|$1]]" સભ્ય દ્વારા વપરાયો છે.
તેનું કારણ આ છે : "$2"',
'blocklogpage'                    => 'પ્રતિબંધ સૂચિ',
'blocklog-showlog'                => 'આ સભ્ય પર પહેલા રોક લગાવાઈ છે.
રોકા લગાવાયેલા સભ્યોની યાદિ આ મુજબ છે',
'blocklog-showsuppresslog'        => 'આ સભ્ય પર પહેલા રોક કે પ્રતિબંધ લગાવાયો છે.
રોક લગાવાયેલા સભ્યોની યાદિ આ મુજબ છે',
'blocklogentry'                   => '[[$1]] પર પ્રતિબંધ $2 $3 સુધી મુકવામાં આવ્યો છે.',
'reblock-logentry'                => '[[$1]] ની પ્રતિબંધ વિકલ્પો બદલ્યા જે નો કલાતિત સમય છે   $2 $3',
'blocklogtext'                    => 'આ સભ્યો પર રોક લગાવવા અને હટાવવા સંબંધિત યાદિ છે.
સ્વયંચાલિત રીતે રોક લગાવાયેલ IP સરનામાની યાદિ અહીં નથી આપી.
હાલમાં પ્રવર્તમાન રોક ની યાદિ અહીં જુઓ [[Special:IPBlockList|IP block list]].',
'unblocklogentry'                 => '$1 પરનો પ્રતિબંધ ઉઠાવ્યો',
'block-log-flags-anononly'        => 'માત્ર અજ્ઞાત સભ્ય',
'block-log-flags-nocreate'        => 'ખાતું ખોલવા પર પ્રતિબંધ છે',
'block-log-flags-noautoblock'     => 'સ્વયંચાલિત પ્રતિબંધ ક્રિયા નિષ્ક્રીય કરાઈ',
'block-log-flags-noemail'         => 'ઇ-મેલ પ્ર પ્રતિબંધ મુકાયો',
'block-log-flags-nousertalk'      => 'તમે પોતાનું ચર્ચા પાનામાં ફેરફાર ન કરી શકો.',
'block-log-flags-angry-autoblock' => 'સુધારિત સ્વયંચાલિત પ્રણાલી સક્રીય કરાઇ',
'block-log-flags-hiddenname'      => 'સભ્યનું નામ છુપાવો',
'range_block_disabled'            => 'પ્રબંધકના સમૂહીક રોક લગાડવાનો અધિકાર નીષ્ક્રિય',
'ipb_expiry_invalid'              => 'સમાપ્તિનો સમય માન્ય નથી.',
'ipb_expiry_temp'                 => 'સંતાડેલા સભ્યનામ પ્રતિબંધનો કાયમી જ હોવા જોઇએ.',
'ipb_hide_invalid'                => 'આ પાનાને છુપાવવો સંભવ નથી તેમાં ઘણા બધાં ફેરફારો છે',
'ipb_already_blocked'             => ' "$1" પહેલેથી પ્રતિબંધિત છે',
'ipb-needreblock'                 => '== પહેલેથી પ્રતિબંધિત  ==
$1 પહેલેથી પ્રતિબંધિત છે.
તમારે આ સેટીંગ બદલવી છે?',
'ipb-otherblocks-header'          => 'અન્ય {{PLURAL:$1|પ્રતિબંધન|પ્રતિબંધનો}}',
'ipb_cant_unblock'                => 'તૃટિ પ્રતિબંધિત ID $1 ન મળ્યો.
તેપહેલેથી અપ્રતિબંધિત કરાયું હોઇ શકે',
'ipb_blocked_as_range'            => 'ત્રુટિ: IP સરનામું $1 પર  સીધી રોક નથી લગાવાઇ આથી તેના પર રોક ન હટાવી શકાય.
જો કે આને સામૂહિક $2 રોક લગાવાઇ હોવાથી તે સમૂ હની રોક હટાવી શકાશે.',
'ip_range_invalid'                => 'અવૈધ IP શ્રેણી',
'ip_range_toolarge'               => '$1થી મોટા સમૂહ રોકની પરવાનગિ નથી',
'blockme'                         => 'મને પ્રતિબંધિત કરો',
'proxyblocker'                    => 'અવેજી (પ્રોક્સી) રોક લગાડનાર',
'proxyblocker-disabled'           => 'આ સૂત્ર (ફંકશન) નિષ્ક્રીય કરાયો',
'proxyblockreason'                => 'તમારા IP સરનામા પરા રોક લગાડાઈ છે કેમકે તેએક ખુલ્લી પ્રોક્સી છે.
કૃપયા તમારા ઇંટરનેટ સેવા પ્રદાતા કે તકનીકી સહાય વિભાગનો સંપર્ક કરી જણાવો કે આ એક ભયંકર સુરક્ષા મામલો છે.',
'proxyblocksuccess'               => 'સંપન્ન',
'sorbsreason'                     => '{{SITENAME}} દ્વારા વપરાયેલા DNSBL માં તમારું IP સરનામું એક ખુલ્લી પ્રોક્સી તરીકે નોંધાયું છે.',
'sorbs_create_account_reason'     => '{{SITENAME}} માં વપરાતા DNSBL દ્વારા તમારા IP  સરનામાને ખુલી પ્રોક્સી જણાવાઇ છે.
તમે ખાતાની રચના નહીં કરી શકો.',
'cant-block-while-blocked'        => 'જ્યારે તમે પોતે પ્રતિબંધિત હોવ ત્યારે અન્ય સભ્યોને પ્રતિબંધિત ન કરી શકો',
'cant-see-hidden-user'            => 'તમે જે સભ્ય પર રોક લગાવવા પ્રયત્ન કરો છો તે ના પર પહેલેથી રોક લગાવાયેલી છે.
તમને સભ્ય છુપાવો / બતાવોના અધિકારો ન હોવાથી, તમે  સભ્ય પર રોક લગાવેલ તે નોંધ જોઇ નથી શકતા.',
'ipbblocked'                      => 'તમે અન્ય સભ્યોને પ્રતિબંધિત ન કરી શકો, તમે પોતે પ્રતિબંધિત છો.',
'ipbnounblockself'                => 'તમે પોતાને અપ્રતિબંધિત ન કરી શકો',

# Developer tools
'lockdb'              => 'માહિતીસંચય તાળું વાસો',
'unlockdb'            => 'માહિતીસંચય પરનું તાળું ખોલો',
'lockdbtext'          => 'માહિતીસંચને તાળું લગાડતા અન્ય સભ્યની ફેરફાર કરવાની શક્તિ, વિકલ્પો બદલવાની શક્તિ , ધ્યાન સૂચિ બદલવાની શક્તિ અને માહિતી સંચ બદલવાની જરૂરિયાત ધરાવતી ક્રિયાઓ  પર  અસર પડશે.
કૃપયા પુષ્ટિ કરો કે તમે આમ જ કરવા ઇચ્છો છો અને તમારું સમારકામ કાર્ય સંપન્ન થતા તમે માહિતી સંચને ખુલ્લો કરશો.',
'unlockdbtext'        => 'માહિતીસંચને ઉઘાડતા સર્વ સભ્યોનો ફેરફાર કરવાનો અધિકાર સ્થાપિત થશે. તેમના વિકલ્પો બદલાશે, તેમની ધ્યાનસૂચિ બદલશે , અને માહિતિ સંચમાં જોઇતા અન્ય વિકલ્પો બદલશે. કૃપયા પુષ્ટિ કરો કે તેમે આમ જ કરવા માંગો છો.',
'lockconfirm'         => 'હા મારે માહિતી સંચય પર તાળું  લગાવવું છે',
'unlockconfirm'       => 'હા મારે માહિતી સંચય ખોલવું છે',
'lockbtn'             => 'માહિતીસંચય પર તાળું વાસો',
'unlockbtn'           => 'માહિતીસંચય પરનું તાળું ખોલો',
'locknoconfirm'       => 'તમે પુષ્ટિ ખાનાને અંકિત નથી કર્યો',
'lockdbsuccesssub'    => 'માહેતીસંચ પરનું તાળું સફળતા પૂર્વક લગાવાયું',
'unlockdbsuccesssub'  => 'માહિતીસંચ પરનું તાળું હટાવાયું',
'lockdbsuccesstext'   => 'માહિતીસંચ પરા રોક લગાવી દેવાઈ.<br />
તમારું સમારાકામ પત્યા પછી તેને [[Special:UnlockDB|remove the lock]] કરવાનું ભૂલશો નહીં',
'unlockdbsuccesstext' => 'આ માહિતી સંચ મુક્ત કરી દેવાયો',
'lockfilenotwritable' => 'માહિતીસંચ તાળુ ફાઇલ લેખનક્ષમ નથી.
માહિતીસંચને તાળું લગાડવા કે ખોલવા વેબ સર્વર દ્વારા તે લેખનક્ષમ હોવું જરૂરી છે.',
'databasenotlocked'   => 'માહિતીસંચ પરા તાળું નથી લગાવી શકાયું',

# Move page
'move-page'                    => '$1 ખસેડો',
'move-page-legend'             => 'પાનું ખસેડો',
'movepagetext'                 => "નીચેનું ફોર્મ વાપરવાથી આ પાનાનું નામ બદલાઇ જશે અને તેમાં રહેલી બધી મહિતિ નવા નામે બનેલાં પાનામાં ખસેડાઇ જશે.
જુનું પાનું, નવા બનેલા પાના તરફ વાળતું થશે.
તમે આવા અન્યત્ર વાળેલાં પનાઓને આપોઆપ જ તેના મુળ શીર્ષક સાથે જોડી શકશો.
જો તમે તેમ કરવા ના ઇચ્છતા હોવ તો, [[Special:DoubleRedirects|બેવડા]] અથવા [[Special:BrokenRedirects|ત્રુટક કડી વાળા]] અન્યત્ર વાળેલા પાનાઓની યાદી ચકાસીને ખાતરી કરી લેશો.
કડી જે પાના પર લઈ જવી જોઈએ તે જ પાના સાથે જોડે તેની ખાતરી કરી લેવી તે તમારી જવાબદારી છે.

એ વાતની નોંધ લેશો કે, જો તમે પસંદ કરેલા નવા નામ વાળું પાનું અસ્તિત્વમાં હશે તો જુનું પાનું '''નહી ખસે''', સિવાયકે તે પાનું ખાલી હોય અથવા તે પણ અન્યત્ર વાળતું પાનું હોય અને તેનો કોઈ ઇતિહાસ ના હોય.
આનો અર્થ એમ થાય છે કે જો તમે કોઈ તબક્કે ભુલ કરશો તો જે પાનાનું નામ બદલવાનો પ્રયત્ન કરતા હોવ તેને તમે ફરી પાછા જુના નામ પર જ પાછું વાળી શકશો, અને બીજું કે પહેલેથી બનેલા પાનાનું નામ તમે નામફેર કરવા માટે ના વાપરી શકો.

'''ચેતવણી!'''
લોકપ્રિય પાનાં સાથે આવું કરવું બિનઅપેક્ષિત અને જોરદાર પરિણામકારક નિવડી શકે છે;
આગળ વધતાં પહેલાં આની અસરોનો પુરે પુરો તાગ મેળવી લેવો આવશ્યક છે.",
'movepagetext-noredirectfixer' => "નીચેનું ફોર્મ વાપરવાથી આ પાનાનું નામ બદલાઇ જશે અને તેમાં રહેલી બધી મહિતિ નવા નામે બનેલાં પાનામાં ખસેડાઇ જશે.
જુનું પાનું, નવા બનેલા પાના તરફ વાળતું થશે.
તમે આવા અન્યત્ર વાળેલાં પનાઓને આપોઆપ જ તેના મુળ શીર્ષક સાથે જોડી શકશો.
જો તમે તેમ કરવા ના ઇચ્છતા હોવ તો, [[Special:DoubleRedirects|બેવડા]] અથવા [[Special:BrokenRedirects|ત્રુટક કડી વાળા]] અન્યત્ર વાળેલા પાનાઓની યાદી ચકાસીને ખાતરી કરી લેશો.
કડી જે પાના પર લઈ જવી જોઈએ તે જ પાના સાથે જોડે તેની ખાતરી કરી લેવી તે તમારી જવાબદારી છે.

એ વાતની નોંધ લેશો કે, જો તમે પસંદ કરેલા નવા નામ વાળું પાનું અસ્તિત્વમાં હશે તો જુનું પાનું '''નહી ખસે''', સિવાયકે તે પાનું ખાલી હોય અથવા તે પણ અન્યત્ર વાળતું પાનું હોય અને તેનો કોઈ ઇતિહાસ ના હોય.
આનો અર્થ એમ થાય છે કે જો તમે કોઈ તબક્કે ભુલ કરશો તો જે પાનાનું નામ બદલવાનો પ્રયત્ન કરતા હોવ તેને તમે ફરી પાછા જુના નામ પર જ પાછું વાળી શકશો, અને બીજું કે પહેલેથી બનેલા પાનાનું નામ તમે નામફેર કરવા માટે ના વાપરી શકો.

'''ચેતવણી!'''
લોકપ્રિય પાનાં સાથે આવું કરવું બિનઅપેક્ષિત અને જોરદાર પરિણામકારક નિવડી શકે છે;
આગળ વધતાં પહેલાં આની અસરોનો પુરે પુરો તાગ મેળવી લેવો આવશ્યક છે.",
'movepagetalktext'             => "આની સાથે સાથે તેનું સંલગ્ન ચર્ચાનું પાનું પણ ખસેડવામાં આવશે, '''સિવાયકે:'''
*નવા નામ વાળું ચર્ચાનું પાનું અસ્તિત્વમાં હોય અને તેમાં લખાણ હોય, અથવા
*નીચેનાં ખાનામાંથી ખરાની નિશાની તમે દૂર કરી હોય.

આ સંજોગોમાં, જો તમે ચાહતા હોવ તો તમારે અહિંનું લખાણ જાતે નવા પાના પર ખસેડવું પડશે.",
'movearticle'                  => 'આ પાનાનું નામ બદલો:',
'moveuserpage-warning'         => "'''ચેતવણી :''' તમે સભ્યનું પાનું હટાવી રહ્યાં છો.  કૃપયા યાદરાખશો કે માત્ર પાનું જ હટાવીશકાશે અને સભ્ય્ને નૂતન નામ નહીં અપાય.",
'movenologin'                  => 'પ્રવેશ કરેલ નથી',
'movenologintext'              => 'કોઇ પાનું હટાવવા માટે તેમે નોંધણી કૃત સભ્ય અને [[Special:UserLogin|logged in]]  હોવા જોઇએ',
'movenotallowed'               => 'તમને નવા પાના ખસેડવાની પરવાનગી નથી.',
'movenotallowedfile'           => 'તમને ફાઈલ ખસેડવાની પરવાનગી નથી.',
'cant-move-user-page'          => 'તમને સભ્ય પાના હટાવવાની પરવાનગી નથી (ઉપપાના સિવાય).',
'cant-move-to-user-page'       => 'તમને   કોઇ પાનાને સભ્ય પાનામાં ખસેડવાની પ્રવાનગી નથી (સિવાય કે સભ્ય ઉપપાના)',
'newtitle'                     => 'આ નવું નામ આપો:',
'move-watch'                   => 'આ પાનું ધ્યાનમાં રાખો',
'movepagebtn'                  => 'પાનું ખસેડો',
'pagemovedsub'                 => 'પાનું સફળતા પૂર્વક ખસેડવામાં આવ્યું છે',
'movepage-moved'               => '\'\'\'"$1" નું નામ બદલીને "$2" કરવામાં આવ્યું છે\'\'\'',
'movepage-moved-redirect'      => 'દિશાનિર્દેશના રચાયું',
'movepage-moved-noredirect'    => 'દિશા નિર્દેશનોની રચના ને સંતાડી દેવાઇ છે',
'articleexists'                => 'આ નામનું પાનું અસ્તિત્વમાં છે, અથવાતો તમે પસંદ કરેલું નામ અસ્વિકાર્ય છો.
કૃપા કરી અન્ય નામ પસંદ કરો.',
'cantmove-titleprotected'      => 'આ સ્થાને તમે પાનું નહીં હટાવી શકો કેમ કે નવું શીર્ષક રચના કરવા પહેલેથી આરક્ષીત છે',
'talkexists'                   => "'''મુખ્ય પાનું સફળતાપૂર્વક ખસેડવામાં આવ્યું છે, પરંતુ તેનું ચર્ચાનું પાનું ખસેડી શકાયું નથી, કેમકે નવા શિર્ષક હેઠળ તે પાનું પહેલેથી અસ્તિત્વમાં છે.
કૃપા કરી જાતે તેને નવાં નામ વાળાં પાનાંમાં વિલિન કરો.'''",
'movedto'                      => 'બદલ્યા પછીનું નામ',
'movetalk'                     => 'સંલગ્ન ચર્ચાનું પાનું પણ ખસેડો',
'move-subpages'                => '($1 સુધી) ઉપ-પાના હટાવાયા',
'move-talk-subpages'           => 'ઉપપાનને ચર્ચાના પાના પર ખસેડો ( $1 સુધે)',
'movepage-page-exists'         => 'પાનું  $1 પહેલેથી અસ્તિત્વમાં છે તેના પર સ્વયં ચલિત રીતે નવું લેખન ન થાય.',
'movepage-page-moved'          => 'પાના $1 ને $2 પર ખસેડાયું',
'movepage-page-unmoved'        => 'પાના $1ને $2 પર ન લઈ જઈ શકાયું',
'movepage-max-pages'           => 'મહત્તમ એવા $1 {{PLURAL:$1|પાનું|પાનાઓ}} કાઢી નખાયા છે. સ્વયંચાલિત રીત આનાથી વધુ પાના કાઢી શકાશે નહીં.',
'1movedto2'                    => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'1movedto2_redir'              => 'નામ બદલતા [[$1]] ને [[$2]] બનાવ્યું',
'move-redirect-suppressed'     => 'દિશા નિર્દેશનો છુપાડાયા',
'movelogpage'                  => 'નામ ફેર માહિતિ પત્રક',
'movelogpagetext'              => 'બધા હટાવેલ પાનાની માહિતી',
'movesubpage'                  => '{{PLURAL:$1|ઉપપાનું|ઉપપાના}}',
'movesubpagetext'              => 'આ પાનાના $1 {{PLURAL:$1|ઉપપાના |ઉપપાનાઓ }} છે',
'movenosubpage'                => 'આ પાનાના કોઈ ઉપ-પાના નથી',
'movereason'                   => 'કારણ:',
'revertmove'                   => 'પૂર્વવત',
'delete_and_move'              => 'હટાવો અને નામ બદલો',
'delete_and_move_text'         => '== પાનું દૂર કરવાની જરૂર છે  ==
લક્ષ્ય પાનું  "[[:$1]]" પહેલેથી અસ્તિત્વમાં છે.
શું તમે આને હટાવીને સ્થળાંતર કરવાનો માર્ગ મોકળો કરવા માંગો છો?',
'delete_and_move_confirm'      => 'હા, આ પાનું હટાવો',
'delete_and_move_reason'       => 'હટાવવાનું કામ આગળ વધાવવા ભૂંસી દેવાયુ',
'selfmove'                     => 'સ્ત્રોત ને લક્ષ્ય શીર્ષકો સમાન છે.
પાના ને તેવા જ નામ ધરાવતા પાના પર પુનઃ સ્થાપન નહીં કરી શકાય.',
'immobile-source-namespace'    => '"$1" નામાસ્થળમાં પાના ન ખસેડી શાકાયા',
'immobile-target-namespace'    => '"$1" નામાસ્થળમાં પાના ન ખસેડી શાકાયા',
'immobile-target-namespace-iw' => 'પાનું હટાવવા આંતરવિકિ કડી એ કોઇ વૈધ શીર્ષક ન હોઇ શકે.',
'immobile-source-page'         => 'આ પાનું ખસેડી નહીં શકાય',
'immobile-target-page'         => 'તે લક્ષ્ય શીર્ષક પર ન ખસેડી શકાયા.',
'imagenocrossnamespace'        => 'ફાઇલ ને બિન-ફાઇલ નામસ્થળમાં ન ખસેડી શકાય',
'nonfile-cannot-move-to-file'  => 'ફાઇલ ન હોય તેમને ફાઇલ નામ સ્થળમાં ન ખસેડી શકાય',
'imagetypemismatch'            => 'નવો ફાઈલ વિસ્તાર શબ્દ તેના પ્રકારાને નથી મળતો',
'imageinvalidfilename'         => 'લક્ષ્ય ફાઈલ અવૈધ છે',
'fix-double-redirects'         => 'મૂળ શીર્ષક તરફ  નિર્દેશન કરતા  દિશા નિર્દેશકો અધ્યતન કરો',
'move-leave-redirect'          => 'પાછળ દિશા સૂચન છોડો',
'protectedpagemovewarning'     => "'''નોંધ : આ પાના પર સંરક્ષણ વિકલ્પ સક્રીય છે અને માત્ર પ્રબંધકો જ આમાં ફેરફાર કરી શકે.'''
તમારા સંદર્ભ માટે તાજેતરની લોગ યાદિ આપી છે:",
'semiprotectedpagemovewarning' => "'''નોંધ : આ પાના પર સંરક્ષણ વિકલ્પ સક્રીય છે અને માત્ર પ્રબંધકો જ આમાં ફેરફાર કરી શકે.'''
તમારા સંદર્ભ માટે તાજેતરની લોગ યાદિ આપી છે:",
'move-over-sharedrepo'         => '== ફાઇલ અસ્તિત્વ ધારાવે છે ==
સર્વસામાન્ય ફાઇલ સંગ્રહમાં [[:$1]] પહેલેથી મોજૂદ છે.  આ સ્થળે કોઇ અન્ય ફાઇલ હટાવતા વિહરમાન ફાઇલની માહિતી પર આ ફાઇલ લખાશે.',
'file-exists-sharedrepo'       => 'પસંદ કરેલ ફાઇલ ના નામે અન્ય ફાઇલ પહેલેથી સર્વ સામાન્ય ફાઇલ સંગ્રહમાં મોજૂદ છે/
કૃપયા અન્ય નામ પસંદ કરો.',

# Export
'export'            => 'પાનાઓની નિકાસ કરો/પાના અન્યત્ર મોકલો',
'exporttext'        => 'તમે કોઇ એક  પાનાનું લેખન અને ઇતિહાસ નિકાસ કરી શકો અથવા કોઇ XML પાનામાં વેંટાળી શકો.
મિડીયા વિકિ ના [[Special:Import|import page]]  નો ઉપયોગ કરી  આ પાનાને અન્ય વિકિમાં ખસેડી શકો.

પાનાની નિકાસ કરવા, નીચેના ચોકઠામાં તેના શીર્ષકો લખો, એક લિટીમાં એક શીર્ષક, અનેપસંદ કરો કે તમારે પ્રાચીન ફેરફારો સાથે ઇતિહાસના પાના સાથે નવા ફેરફારો અને હાલના ફેરફારની માહિતી લઇ જવી છે.

પાછળના વિકલ્પ પસંદ કરવા તમે આ કડી વાપરી શકો [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] આ પાના માટે   "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'માત્ર હાલના ફેરફારો જુઓ , પૂર્ણ ઇતિહાસ નહી.',
'exportnohistory'   => "----
'''નોંધ:''' કાર્યક્ષમતાને લાગતા કારણોને લીધે આ રૂપમાં ઇતિહાસ પાનાની નિકાસ ને નિષ્ક્રીય કરાઇ છે.",
'export-submit'     => 'નિકાસ',
'export-addcattext' => 'આ શ્રેણીમાંથી પાના ઉમેરો',
'export-addcat'     => 'ઉમેરો',
'export-addnstext'  => 'નામસ્થળથી પાના ઉમેરો:',
'export-addns'      => 'ઉમેરો',
'export-download'   => 'સાચવો',
'export-templates'  => 'ઢાંચાઓ શામિલ કરો',
'export-pagelinks'  => 'કડી દ્વારા જોડાયેલા પાના આટાલા સ્તર સુધી જોડો :',

# Namespace 8 related
'allmessages'                   => 'તંત્ર સંદેશાઓ',
'allmessagesname'               => 'નામ',
'allmessagesdefault'            => 'મૂળ સંદેશ',
'allmessagescurrent'            => 'વર્તમાન દસ્તાવેજ',
'allmessagestext'               => 'આ મિડિયાવિકિ નામ સ્થળમાં આવેલ પ્રણાલીજનિત સંદેશાની યાદુઇ આ મુજબ છે.
કૃપયા [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] અને [http://translatewiki.net translatewiki.net]ની મુલાકાત લો જો ત મિડિયાયાવિકિના સ્થાનિય કરણમાં મદદરૂપ થવા ઇચ્છતા હોવ.',
'allmessagesnotsupportedDB'     => "આ પાનું ન વાપરી શકાશે કેમકે '''\$wgUseDatabaseMessages'''  નિષ્ક્રીય કરાયું છે",
'allmessages-filter-legend'     => 'ચાળણી',
'allmessages-filter'            => 'સ્થાનીયકરણ સ્થિતિ દ્વારા ચાળો',
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
'thumbnail_gd-library'     => 'અપૂર્ણ GD પુસ્તકાલય ગોઠવણા: કાર્યસૂત્ર $1 ગાયબ',
'thumbnail_image-missing'  => 'આ ફાઇલ ગુમશુદા : $1',

# Special:Import
'import'                     => 'પાના આયાત કરો',
'importinterwiki'            => 'ટ્રાંસ વિકિ આયાત',
'import-interwiki-text'      => 'આયાત કરવાના વિકિ અને પાનાનું શીર્ષક પસંદ કરો.
ફેરફરની તારીખ અને લેખકો વિષે ની માહિતી સચવાશે.
આંતર વિકિ આયાત આદિ [[Special:Log/import|import log]] માં અંકિત થાય છે.',
'import-interwiki-source'    => 'સ્ત્રોત વિકિ/પાનું',
'import-interwiki-history'   => 'આ પાના બધા ઐતિહાસીક ફેરફારોની નકલ કરો',
'import-interwiki-templates' => 'બધા ઢાંચા શામિલ કરો',
'import-interwiki-submit'    => 'આયાત કરો',
'import-interwiki-namespace' => 'લક્ષ્ય નામ સ્થળ',
'import-upload-filename'     => 'ફાઇલ નામ',
'import-comment'             => 'ટિપ્પણી:',
'importtext'                 => 'કૃપયા [[Special:Export|export utility]] વાપરી વિકિ સ્ત્રોત પરથી ફાઇલ નિકાસ કરો.
તેને તમારા સંગણક પર સાચવો અને અહીં ચડાવો.',
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
'importhistoryconflict'      => 'એક બીજાથી વિસંગત ફેરફારો અસ્તિત્વ ધરાવે છે ( કદાક આ પાનું પહેલાં આયાત કરાયું હોય)',
'importnosources'            => 'કોઇ પણ આંતર વિકિ સ્ત્રોત જણાવાયા નથી અને સીધા ઇતિહાસ ફાઇલ ચડાવવા પર રોક લાગેલી છે.',
'importnofile'               => 'કોઇ પણ આયાતી ફાઇલ ન ચડાવી શકાઇ',
'importuploaderrorsize'      => 'આયાતી ફાઇલ ચડાવવાનું અસફળ
મંજૂર કદ કરતા આ ફાઈલાનું કદ મોટું છે.',
'importuploaderrorpartial'   => 'આયાતી ફાઇલ ચડાઅવવામાં અસફળ 
આ ફાઇલ માત્ર આંશિક રૂપે ચડાવાઇ',
'importuploaderrortemp'      => 'આયાતી ફાઈલ ચઢાવવું અસફળ.
હંગામી ફોલ્ડરા ગાયબ છે.',
'import-parse-failure'       => 'XML આયાત પદચ્છેદ અસફળ',
'import-noarticle'           => 'આયાત કરવા માટે કોઇ પાનું નથી!',
'import-nonewrevisions'      => 'બધા ફેરફરો પહેલા આયાત કરાયા છે.',
'xml-error-string'           => '$1  લીટી ક્ર્માંક $2, સ્તંભ  $3 (બાઇટ  $4): $5',
'import-upload'              => 'XML માહિતી ચઢાવો',
'import-token-mismatch'      => 'સત્ર સમાપ્ત
ફરી પ્રયત્ન કરો',
'import-invalid-interwiki'   => 'દર્શાવેલ વિકિ પરથી આયાત નહીં કરી શકાય',

# Import log
'importlogpage'                    => 'આયાત માહિતિ પત્રક',
'importlogpagetext'                => 'અન્ય  વિકિમાંથી ઈતિહાસ સહિત પાનાની પ્રબંધકીય આયાત',
'import-logentry-upload'           => 'ફાઇલ ચઢાવનાર દ્વારા [[$1]] આયાત કરાઇ',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',
'import-logentry-interwiki'        => 'આંતરવિકિ  $1',
'import-logentry-interwiki-detail' => '$2 થી $1 {{PLURAL:$1|પુનરાવર્તન|પુનરાવર્તનો}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "તમારૂં પાનું (તમારૂં 'મારા વિષે')",
'tooltip-pt-anonuserpage'         => 'IP સરનામું માટેના સભ્ય પાનામાં તમે ફેરફાર કરી રહ્યાં છો.',
'tooltip-pt-mytalk'               => 'તમારૂં ચર્ચાનું પાનું',
'tooltip-pt-anontalk'             => 'આ IP સરનામા દ્વારા થયેલ ફેરફારની ચર્ચા',
'tooltip-pt-preferences'          => 'મારી પસંદ',
'tooltip-pt-watchlist'            => 'તમે દેખરેખ રાખી રહ્યાં હોવ તેવા પાનાઓની યાદી',
'tooltip-pt-mycontris'            => 'તમારા યોગદાનની યાદી',
'tooltip-pt-login'                => 'આપને લોગ ઇન કરવા ભલામણ કરવામાં આવે છે, જોકે તે આવશ્યક નથી',
'tooltip-pt-anonlogin'            => 'આપને લોગ ઇન કરવા ભલામણ કરવામાં આવે છે, જોકે તે આવશ્યક નથી',
'tooltip-pt-logout'               => 'બહાર નીકળો/લૉગ આઉટ કરો',
'tooltip-ca-talk'                 => 'અનુક્રમણિકાનાં પાના વિષે ચર્ચા',
'tooltip-ca-edit'                 => "આપ આ પાનામાં ફેરફાર કરી શકો છો, કાર્ય સુરક્ષિત કરતાં પહેલાં 'ઝલક' બટન ઉપર ક્લિક કરીને જોઇ લેશો",
'tooltip-ca-addsection'           => 'ચર્ચાનો નવો મુદ્દો ઉમેરો.',
'tooltip-ca-viewsource'           => 'આ પાનુ સંરક્ષિત છે, તમે તેનો સ્ત્રોત જોઇ શકો છો',
'tooltip-ca-history'              => 'આ પાનાનાં અગાઉનાં ફેરફારો',
'tooltip-ca-protect'              => 'આ પાનું સુરક્ષિત કરો',
'tooltip-ca-unprotect'            => 'આ પાનાની સુરક્ષા હટાવો.',
'tooltip-ca-delete'               => 'આ પાનું હટાવો',
'tooltip-ca-undelete'             => 'આ પાનું હટાવાયું તે પહેલાના ફેરફારો હટાવો.',
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
'tooltip-recreate'                => 'આપાનું ફરીથી રચો ભલેને તે પહેલાં ભૂસાડી દેવાયું હોય',
'tooltip-upload'                  => 'ફાઇલ ચડાવવાનું શરૂ કરો',
'tooltip-rollback'                => '"પાછું વાળો" એક જ ક્લિકમાં છેલ્લા સભ્યએ આ પાનામાં કરેલા બધા ફેરફારો પાછા વાળશે',
'tooltip-undo'                    => '"રદ કરો" આ ફેરફારને પાછો વાળશે અને ફેરફાર પછીનું પૂર્વાવલોકન ફોર્મ નવા પાના તરીકે ખુલશે.
તે તમને \'સારાંશ\'માં કારણ જણાવવા દેશે.',
'tooltip-preferences-save'        => 'પસંદ સાચવો',
'tooltip-summary'                 => 'ટૂંક સારાંશ ઉમેરો',

# Metadata
'nodublincore'      => 'સર્વર માટે Dublin Core RDF metadata નિષ્ક્રીય કરાયો.',
'nocreativecommons' => 'આ સર્વર માટે કલાકૃતિ Commons RDF metadata નિષ્ક્રીય કરાયો.',
'notacceptable'     => 'તમારા ગ્રાહક સમજી શકે તે રીતે વિકિ માહિતી નહીં આપી શકે',

# Attribution
'anonymous'        => '{{SITENAME}} ના અ‍જ્ઞાત {{PLURAL:$1|સભ્ય|સભ્યો}}',
'siteuser'         => '{{SITENAME}} સભ્ય $1',
'anonuser'         => '{{SITENAME}} અજ્ઞાત સભ્ય  $1',
'lastmodifiedatby' => 'આ પાનામાં છેલ્લે  $3 દ્વારા $1 તારીખે $2 વાગ્યે ફેરફાર થયો',
'othercontribs'    => '$1 ના કાર્ય પર આધારિત',
'others'           => 'અન્યો',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|સભ્ય|સભ્યો}} $1',
'anonusers'        => '{{SITENAME}} અજ્ઞાત {{PLURAL:$2|સભ્ય|સભ્યો}} $1',
'creditspage'      => 'પાનાના ઋણ સ્વીકાર',
'nocredits'        => 'આ પાના માટે કોઇ પણ ઋણસ્વીકાર મોજુદ નથી',

# Spam protection
'spamprotectiontitle' => 'સ્પામ સંરક્ષણ ચાળણી',
'spamprotectiontext'  => 'તમે જે લેખન સાચવવા માંગો છો તેના પર સ્પેમ ચાળણી દ્વારા રોક લગાવાઇ છે.
પ્રતિબંધિત બાહ્ય સાઇટ પર રોક લગાવતી કડી દ્વારા આ કાર્ય થયેલું લાગે છે.',
'spamprotectionmatch' => 'આ શબ્દો એ સ્પેમ સંરક્ષણ ચાળણીને સક્રીય કરી : $1',
'spambot_username'    => 'મિડિયાવિકી સ્પેમ સફાઇ',
'spam_reverting'      => ' $1 પર કડી ન ધરાવતા છેલ્લા ફેરેફાર પર પુનઃ સ્થાપન કરાય છે',
'spam_blanking'       => 'બધા ફેરફારોમાં  $1 પર કડી હતી, આને હટાવી દેવામાં આવે છે',

# Info page
'infosubtitle'   => 'પાના વિષે માહિતી',
'numedits'       => 'ફેરફારોની સંખ્યા (લેખ): $1',
'numtalkedits'   => 'ફેરફારોની સંખ્યા (ચર્ચાનું પાનું): $1',
'numwatchers'    => 'નીરીક્ષકોની સંખ્યા : $1',
'numauthors'     => 'લેખકોની સંખ્યા (પાનું): $1',
'numtalkauthors' => 'લેખકોની સંખ્યા (ચર્ચા પાનું): $1',

# Math options
'mw_math_png'    => 'PNGને હમેંશા પરત કરો',
'mw_math_simple' => 'જો સરળ હોય તો  HTML અન્યથા PNG',
'mw_math_html'   => 'જો સરળ હોય તો  HTML અન્યથા PNG',
'mw_math_source' => 'આને શબ્દ રહેવા દો (ટેક્સ્ટ બ્રાઉઝર)',
'mw_math_modern' => 'અત્યાધુનિક બ્રાઉઝરો માટે ભલામણ યોગ્ય',
'mw_math_mathml' => ' MathML શક્ય હોય તો  (પ્રયોગાત્મક)',

# Math errors
'math_failure'          => 'પદચ્છેદ અસફળ',
'math_unknown_error'    => 'અજ્ઞાત ત્રુટિ',
'math_unknown_function' => 'અજ્ઞાત ફંક્શન',
'math_lexing_error'     => 'લેખન ક્ર્મ / વિધી ત્રુટિ',
'math_syntax_error'     => 'સૂત્ર લેખન ત્રુટિ',
'math_image_error'      => 'PNG રૂપાંતરણ નિષ્ફળ;  latex અને dvipng (or dvips + gs + convert) બરાબર ચડાવાયું છે કે કેમ તે ચકાસો',
'math_bad_tmpdir'       => 'હંગામી ગણિત ડીરેક્ટરી લખી કે રચી નહીં શકાય.',
'math_bad_output'       => 'ઉત્તરીય ગણિત ડીરેક્ટરી લખી કે રચી નહીં શકાય.',
'math_notexvc'          => 'ચલાવી શકાય તેવી texvc ગાયબ ; આને ચડાવવા math/README  જુઓ.',

# Patrolling
'markaspatrolleddiff'                 => 'નિરીક્ષીત અંકિત કરો',
'markaspatrolledtext'                 => 'આ પાનાને નીરીક્ષિત અંકિત કરો',
'markedaspatrolled'                   => 'નીરીક્ષિત અંકિત કરો',
'markedaspatrolledtext'               => '[[:$1]] ના પસંદગી કૃત ફેરફારો નીરિક્ષીત અંકિત કરાયા છે',
'rcpatroldisabled'                    => 'હાલના ફેરફારોનું નિરીક્ષણ પ્રતિબંધિત',
'rcpatroldisabledtext'                => 'હાલમાં થયેલ ફેરફરની ચકાસણીનો વિકલ્પ હાલમાં નિષ્ક્રીય છે.',
'markedaspatrollederror'              => 'આને ચકાસિત અંકિત નથી કરાયું',
'markedaspatrollederrortext'          => 'તમરી કોઇ ફેરફારને નીરીક્ષિત અંકિત કરવા તે પસંદ કરેલ હોવા જોઇએ.',
'markedaspatrollederror-noautopatrol' => 'તમે તમારા પોતાના ફેરફારોને નીરીક્ષિત અંકિત ન કરી શકો',

# Patrol log
'patrol-log-page'      => 'ચકાસણી લોગ',
'patrol-log-header'    => 'આ નીરીક્ષિત ફેરફાઓનો લોગ છે',
'patrol-log-line'      => '$1 માંના $2 ને નીરીક્ષિત  અંકિત કરો  $3',
'patrol-log-auto'      => '(સ્વયંચાલિત)',
'patrol-log-diff'      => 'સુધારો: $1',
'log-show-hide-patrol' => '$1 ચકાસણી લોગ',

# Image deletion
'deletedrevision'                 => 'જુના સુધારા ભૂસો $1',
'filedeleteerror-short'           => 'ફાઇલ : $1 ભૂંસવામાં તૃટિ',
'filedeleteerror-long'            => '$1 આ ફાઈલ ભૂંસતી વખતે ચૂક થઈ',
'filedelete-missing'              => 'ફાઇલ  "$1" ને ભૂંસાડી નહીં શકાય કેમકે તે અસ્તિત્વમાં નથી.',
'filedelete-old-unregistered'     => 'જણાવેલ ફાઇલ ફેરફાર "$1" માહિતીસંચમાં નથી',
'filedelete-current-unregistered' => 'જણાવેલ ફાઇલ  "$1" માહિતીસંચમાં નથી',
'filedelete-archive-read-only'    => 'ઐતિહાસિક ડાયરેક્ટરી ($1) એ વેબ બ્રાઉઝર દ્વારા લેખન યોગ્ય નથી.',

# Browsing diffs
'previousdiff' => '← પહેલાનો ફેરફાર',
'nextdiff'     => 'પછીનો ફેરફાર →',

# Media information
'mediawarning'         => "'''ચેતવણી''': આ ફાઇલમાં દુષ્ટ સોચનાઓ હોઇ શકે.
તેને ચલવતા, તમારા સંગણકને ભય છે.",
'imagemaxsize'         => "ચિત્રના કદની મર્યાદા:<br />''(for file description pages)''",
'thumbsize'            => 'લઘુચિત્ર કદ',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|પાનું|પાના}}',
'file-info'            => 'ફાઇલ કદ : $1, MIME પ્રકાર: $2',
'file-info-size'       => '$1 × $2 પીક્સલ, ફાઇલનું કદ: $3, MIME પ્રકાર: $4',
'file-nohires'         => '<small>આથી વધુ આવર્તન ઉપલબ્ધ નથી.</small>',
'svg-long-desc'        => 'SVG ફાઇલ, માત્ર $1 × $2 પીક્સલ, ફાઇલનું કદ: $3',
'show-big-image'       => 'મહત્તમ આવર્તન',
'file-info-gif-looped' => 'આવર્તન  (લુપ)',
'file-info-gif-frames' => ' $1 {{PLURAL:$1|છબી|છબીઓ}}',
'file-info-png-looped' => 'આવર્તન',
'file-info-png-repeat' => '$1 {{PLURAL:$1|વખત|વખત}} કરાયું',
'file-info-png-frames' => '$1 {{PLURAL:$1|છ્બી|છબીઓ}}',

# Special:NewFiles
'newimages'             => 'નવી ફાઇલોની ઝાંખી',
'imagelisttext'         => "નીચે '''$1''' ની યાદિ આપી છે  {{PLURAL:$1|ફાઇલ|ફાઇલો}}  $2માં સંગ્રહીત.",
'newimages-summary'     => 'આ ખાસ પાનું છેવટની  ચડાવેલા વફાઈલા બતાવે છે',
'newimages-legend'      => 'ચાળણી',
'newimages-label'       => 'ફાઈલનામ (કે તેનો ભાગ)',
'showhidebots'          => '($1 બોટ)',
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
'exif-imagewidth'                  => 'પહોળાઈ',
'exif-imagelength'                 => 'ઊંચાઈ',
'exif-bitspersample'               => 'બીટ્સ પ્રતિ ભાગ',
'exif-compression'                 => 'સંકોચન પ્રણાલી',
'exif-photometricinterpretation'   => 'Pixel સંરચના',
'exif-orientation'                 => 'દિશામાન્',
'exif-samplesperpixel'             => 'ભાગની સંખ્યા',
'exif-planarconfiguration'         => 'માહિતી ગોઠવણ',
'exif-ycbcrsubsampling'            => 'Y થી C નો સબસેમ્પલીંગ ગુણોત્તર',
'exif-ycbcrpositioning'            => 'Y અને C સ્થાનાંતરણ',
'exif-xresolution'                 => 'આડું ઘનત્વ',
'exif-yresolution'                 => 'ઊભું ઘનત્વ',
'exif-resolutionunit'              => 'X અને  Y અક્ષના ચિત્ર ઘનત્વનો (રેસોલ્યૂશન) એકમ',
'exif-stripoffsets'                => 'ચિત્ર સંબંધિત માહિતીનું સ્થાન',
'exif-rowsperstrip'                => 'દરેક પટ્ટીમાં હરોળની સંખ્યા',
'exif-stripbytecounts'             => 'સંકુચિત પટ્ટીમાં બાઈટની સંખ્યા',
'exif-jpeginterchangeformat'       => 'JPEG SOI નો ફણગો',
'exif-jpeginterchangeformatlength' => 'JPEG માહિતીની બાઇટ્સ',
'exif-transferfunction'            => 'સ્થળાંતર કાર્ય',
'exif-whitepoint'                  => 'ધવલ બિંદુ રંગ તીવ્રતા',
'exif-primarychromaticities'       => 'મૂળ રંગોની રંગછટા',
'exif-ycbcrcoefficients'           => 'રંગ અવકાશ પરિવર્તન ગર્ભ અચળાંક',
'exif-referenceblackwhite'         => 'શ્યામ અને ધવલ સંદર્ભ કિંમતોની જોડી',
'exif-datetime'                    => 'ફાઇલ સુધારાની તારીખ અને સમય',
'exif-imagedescription'            => 'ચિત્ર શીર્ષક',
'exif-make'                        => 'કેમેરા ઉત્પાદક',
'exif-model'                       => 'કેમેરાનું મોડેલ',
'exif-software'                    => 'વપરાયેલ સોફ્ટવેર',
'exif-artist'                      => 'કલાકાર',
'exif-copyright'                   => 'પ્રકાશનાધિકાર ધારક',
'exif-exifversion'                 => 'Exif આવૃત્તિ',
'exif-flashpixversion'             => 'આધારીત Flashpix  આવૃત્તિ',
'exif-colorspace'                  => 'રંગ માટે જગ્યા',
'exif-componentsconfiguration'     => 'દરેક ભાગનો અર્થ',
'exif-compressedbitsperpixel'      => 'ચિત્ર સરખામણી મોડ',
'exif-pixelydimension'             => 'ચિત્ર માટે વૈધ પહોળાઇ',
'exif-pixelxdimension'             => 'ચિત્રની વૈધ ઊઁચાઈ',
'exif-makernote'                   => 'ઉત્પાદકની નોંધ',
'exif-usercomment'                 => 'સભ્યની ટિપ્પણી',
'exif-relatedsoundfile'            => 'સંબંધિત શ્રાવ્ય ફાઈલો',
'exif-datetimeoriginal'            => 'નિર્મિતીનો સમય અને તારીખ',
'exif-datetimedigitized'           => 'અંકલેખિત કરવાનો સમય',
'exif-subsectime'                  => 'તારીખ સમય સબસેકંડ્માં',
'exif-subsectimeoriginal'          => 'અંકલેખિત કરવાનો સમય સબસેકંડમાં',
'exif-subsectimedigitized'         => 'અંકલેખિત કરવાનો સમય સબસેકંડમાં',
'exif-exposuretime'                => 'પ્રકાશાગમ સમય',
'exif-exposuretime-format'         => ' $1 સેકંડ ($2)',
'exif-fnumber'                     => 'F ક્રમ',
'exif-exposureprogram'             => 'પ્રકાશાગમ પ્રોગ્રામ',
'exif-spectralsensitivity'         => 'રંગપટલ સંવેદના',
'exif-isospeedratings'             => 'ISO ઝડપ ક્ર્માંક',
'exif-oecf'                        => 'દ્રસ્ટિ ઇલેક્ટ્રોનીક રૂપાંતરણ અચળાંક',
'exif-shutterspeedvalue'           => 'દ્વારની ઝડપ',
'exif-aperturevalue'               => 'છીદ્ર માપ',
'exif-brightnessvalue'             => 'તેજસ્વીતા',
'exif-exposurebiasvalue'           => 'પ્રકાશાગમ ફરક્',
'exif-maxaperturevalue'            => 'મહત્તમ ભૂમિ છીદ્ર',
'exif-subjectdistance'             => 'વસ્તુનું અંતર',
'exif-meteringmode'                => 'મીટરીંગ ઢબ',
'exif-lightsource'                 => 'પ્રકાશા સ્ત્રોત',
'exif-flash'                       => 'જબકારો (ફ્લેશ)',
'exif-focallength'                 => 'કાંચનું કેન્દ્રીય લંબાઇ (ફોકલ લેંથ)',
'exif-subjectarea'                 => 'વિષ્યવસ્તુ  ક્ષેત્ર',
'exif-flashenergy'                 => 'ઝબકારાની શક્તિ',
'exif-spatialfrequencyresponse'    => 'અવકાશી કંપન પ્રત્યુત્તર',
'exif-focalplanexresolution'       => 'કેન્દ્રીત X ફલક નું ઘનત્વ',
'exif-focalplaneyresolution'       => 'કેન્દ્રીત Y ફલક નું ઘનત્વ',
'exif-focalplaneresolutionunit'    => 'કેંન્દ્રીત ફલક ઘનત્વનો એકમ',
'exif-subjectlocation'             => 'વસ્તુનું સ્થાન',
'exif-exposureindex'               => 'પ્રકાશાગમ અનુક્ર્મ',
'exif-sensingmethod'               => 'સંવેદનાની રીત',
'exif-filesource'                  => 'ફાઇલ સ્ત્રોત',
'exif-scenetype'                   => 'દ્રશ્ય પ્રકાર',
'exif-cfapattern'                  => 'CFA ભાત',
'exif-customrendered'              => 'સ્થાનીય ચિત્ર પ્રક્રિયા',
'exif-exposuremode'                => 'પ્રકાશાગમ પ્રકાર',
'exif-whitebalance'                => 'ધવલ સમતોલન',
'exif-digitalzoomratio'            => 'ડીજીટલ ઝુમ પ્રમાણ',
'exif-focallengthin35mmfilm'       => '35 mm ફીલ્મનું કેંદ્રીય અંતર',
'exif-scenecapturetype'            => 'દ્રશ્ય ગ્રહણ પ્રકાર',
'exif-gaincontrol'                 => 'દ્રશ્ય નિયંત્રણ',
'exif-contrast'                    => 'રંગછટા',
'exif-saturation'                  => 'સંતૃપ્તતા',
'exif-sharpness'                   => 'તીવ્રતા',
'exif-devicesettingdescription'    => 'યંત્રના વિકલ્પ ગોઠવણનું વિવરણ',
'exif-subjectdistancerange'        => 'વિષયાવસ્તુ અંતર મર્યાદા',
'exif-imageuniqueid'               => 'અનન્ય ચિત્ર ID',
'exif-gpsversionid'                => 'GPS ચિન્હ સંસ્કરણ',
'exif-gpslatituderef'              => 'ઉત્તર કે દક્ષીણ અક્ષાંસ',
'exif-gpslatitude'                 => 'અક્ષાંસ',
'exif-gpslongituderef'             => 'પૂર્વ પશ્ચિમ રેખાંશ',
'exif-gpslongitude'                => 'રેખાંશ',
'exif-gpsaltituderef'              => 'ઊંચાઈ સંદર્ભ',
'exif-gpsaltitude'                 => 'ઊંચાઈ',
'exif-gpstimestamp'                => 'GPS સમય (આણ્વીક ઘડિયાળ)',
'exif-gpssatellites'               => 'માપન માટે વપરાયેલ ઉપગ્રહ',
'exif-gpsstatus'                   => 'ગ્રાહકની સ્થિતિ',
'exif-gpsmeasuremode'              => 'માપનની ઢબ',
'exif-gpsdop'                      => 'માપન ચોકસાઈ',
'exif-gpsspeedref'                 => 'ઝડપનું એકમ',
'exif-gpsspeed'                    => 'GPS ગ્રાહક ઝડપ',
'exif-gpstrackref'                 => 'દિશા કે ચલનનો સંદર્ભ',
'exif-gpstrack'                    => 'હલનચલનની દિશા',
'exif-gpsimgdirectionref'          => 'ચિત્રની દિશાનો સંદર્ભ',
'exif-gpsimgdirection'             => 'ચિત્રની દિશા',
'exif-gpsmapdatum'                 => 'ભૂમાપન સર્વેક્ષણ માહિતી વપરાઇ',
'exif-gpsdestlatituderef'          => 'સ્થાનનો અક્ષાંસ સંદર્ભ',
'exif-gpsdestlatitude'             => 'અક્ષાંસ સ્થળ',
'exif-gpsdestlongituderef'         => 'સ્થાનનો રેખાંશ સંદર્ભ',
'exif-gpsdestlongitude'            => 'રેખાંશ સ્થળ',
'exif-gpsdestbearingref'           => 'સ્થાનનો દિશા સંદર્ભ',
'exif-gpsdestbearing'              => 'સ્થાનની દિશા સ્થિતિ',
'exif-gpsdestdistanceref'          => 'સ્થાન સુધીના અંતરનો સંદર્ભ',
'exif-gpsdestdistance'             => 'સ્થળનું અંતર',
'exif-gpsprocessingmethod'         => ' GPS ગણન પ્રક્રિયાનું નામ',
'exif-gpsareainformation'          => 'GPS ક્ષેત્રનું નામ',
'exif-gpsdatestamp'                => 'GPS તારીખ',
'exif-gpsdifferential'             => 'GPS ફેરફારનો સુધારો',
'exif-objectname'                  => 'લઘુ શીર્ષક',

# EXIF attributes
'exif-compression-1' => 'અસંકોચિત',

'exif-unknowndate' => 'અજ્ઞાત તારીખ',

'exif-orientation-1' => 'સામાન્ય',
'exif-orientation-2' => 'ક્ષિતીજા સમાંતર પલટાવો',
'exif-orientation-3' => '૧૮૦° ફેરવો',
'exif-orientation-4' => 'ઊભી દિશામાં પ્રતિબિંબિત કરો.',
'exif-orientation-5' => ' ઘડિયાળની વિરુદ્ધ દિશામાં ૯૦° ફેરવો અને ઊભી દિશામાં પ્રતિબિંબિત કરો',
'exif-orientation-6' => 'ઘડિયાળની દિશામાં ૯૦° ફેરવો',
'exif-orientation-7' => ' ઘડિયાળની દિશામાં ૯૦° ફેરવો અને ઊભી દિશામાં પ્રતિબિંબિત કરો',
'exif-orientation-8' => '૯૦° ઉલટ ઘડિયાળ દિશામાં ફેરવો',

'exif-planarconfiguration-1' => 'ખરબચડું',
'exif-planarconfiguration-2' => 'સપાટ',

'exif-componentsconfiguration-0' => 'નથી',

'exif-exposureprogram-0' => 'અવ્યાખ્યાયિત',
'exif-exposureprogram-1' => 'માનવ ચાલિત',
'exif-exposureprogram-2' => 'સામાન્ય પ્રણાલી',
'exif-exposureprogram-3' => 'છીદ્ર અગ્રતા',
'exif-exposureprogram-4' => 'શટર અગ્રતા',
'exif-exposureprogram-5' => 'રચનાત્મક પ્રોગ્રામ ( દ્રશ્યની ઊંડાઇ તરફ પૂર્વગ્રાહી)',
'exif-exposureprogram-6' => 'સક્રિય પ્રોગ્રામ ( ઝડપી શટર ગતિ પ્રત્યે પક્ષપાતી)',
'exif-exposureprogram-7' => 'વ્યક્તિ વિશેષ ચિત્રિકરણ ઢબ ( નજીકના કે ચહેરા પર લક્ષ્ય કેંદ્રીત અને પાછળનો દેખાવ લક્ષ્યથી બહાર)',
'exif-exposureprogram-8' => 'દેખાવ ચિત્રિકરણ ઢબ  (આજુબાજુ અને પાછળ ના પ્રાકૃતિક દેખાવ પર લક્ષ્ય કેન્દ્રીત)',

'exif-subjectdistance-value' => '$1 મીટર',

'exif-meteringmode-0'   => 'અજાણ્યો',
'exif-meteringmode-1'   => 'સરાસરી',
'exif-meteringmode-2'   => 'કેન્દ્રીય સરાસરી સમતોલન',
'exif-meteringmode-3'   => 'બિંદુ',
'exif-meteringmode-4'   => 'બિંદુઓ',
'exif-meteringmode-5'   => 'ભાત',
'exif-meteringmode-6'   => 'આશિંક',
'exif-meteringmode-255' => 'બીજું કઈ',

'exif-lightsource-0'   => 'અજાણ્યો',
'exif-lightsource-1'   => 'દિવસ પ્રકાશ',
'exif-lightsource-2'   => 'ફ્લોરોસ્કેન્ટ રંગો',
'exif-lightsource-3'   => 'ટંગસ્ટાન  (બલ્બ પ્રકાશ)',
'exif-lightsource-4'   => 'જબકારો (ફ્લેશ)',
'exif-lightsource-9'   => 'સામાન્ય વાતાવરણ',
'exif-lightsource-10'  => 'વાદળીયું વાતાવરણ',
'exif-lightsource-11'  => 'છાયા',
'exif-lightsource-12'  => 'દિવસ પ્રકાશ ટ્યૂબ લાઇટ (D 5700 – 7100K)',
'exif-lightsource-13'  => 'દિન ધવલ ફ્લોરોસ્કેન્ટ (N 4600 – 5400K)',
'exif-lightsource-14'  => 'શીત ધવલ ફ્લોરોસ્કેન્ટ (W 3900 – 4500K)',
'exif-lightsource-15'  => 'ધવલ ફ્લોરોસ્કેન્ટ (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'પ્રમાણભૂત પ્રકાશ A',
'exif-lightsource-18'  => 'પ્રમાણભૂત પ્રકાશ B',
'exif-lightsource-19'  => 'પ્રમાણભૂત પ્રકાશ C',
'exif-lightsource-24'  => 'ISO સ્ટુડીયો ટંગસ્ટન',
'exif-lightsource-255' => 'પ્રકાશના અન્ય સ્ત્રોત',

# Flash modes
'exif-flash-fired-0'    => 'પ્રકાશ ઝબકારો ન થયો',
'exif-flash-fired-1'    => 'ઝબકારો કરાયો',
'exif-flash-return-0'   => 'પરતીનો સ્ટ્રોબ શોધ કાર્ય',
'exif-flash-return-2'   => 'પરતીનો સ્ટ્રોબ પ્રકાશ ન મેળવાયો.',
'exif-flash-return-3'   => 'પરતીનો સ્ટ્રોબ પ્રકાશ જ્ઞાત થયો .',
'exif-flash-mode-1'     => 'ફરજિયાત  ઝાકારો રાખો',
'exif-flash-mode-2'     => 'ફરજીયાત ઝબકારો બંધ રાખો',
'exif-flash-mode-3'     => 'સ્વયંચાલિત ઢબ',
'exif-flash-function-1' => 'ઝબકારો નહીં',
'exif-flash-redeye-1'   => 'રાતી આંખના ઘટાડાની ઢબ',

'exif-focalplaneresolutionunit-2' => 'ઈંચ',

'exif-sensingmethod-1' => 'અવ્યાખ્યાયિત',
'exif-sensingmethod-2' => 'એક-ચીપ વાળો રંગ ક્ષેત્રનો સંવેદક',
'exif-sensingmethod-3' => 'બે-ચીપ વાળો રંગ ક્ષેત્રનો સંવેદક',
'exif-sensingmethod-4' => 'ત્રણ-ચીપ વાળો રંગ ક્ષેત્રનો સંવેદક',
'exif-sensingmethod-5' => 'અનુક્રમિત રંગ ક્ષેત્ર સંવેદક',
'exif-sensingmethod-7' => 'ત્રિરેખીક સંવેદક',
'exif-sensingmethod-8' => 'અનુક્રમિત રંગ ક્ષેત્ર સંવેદક',

'exif-scenetype-1' => 'અસલ ફોટો ધરાવતું ચિત્ર',

'exif-customrendered-0' => 'સામાન્ય પ્રક્રિયા',
'exif-customrendered-1' => 'પસંદગી પ્રક્રિયા',

'exif-exposuremode-0' => 'સ્વયંચાલિત પ્રકાશાભિમુખતા',
'exif-exposuremode-1' => 'માનવ ચાલિત પ્રકાશાભિમુખતા',
'exif-exposuremode-2' => 'સ્વયંચાલિત બ્રેકેટ પ્રણાલી (અન્ય પ્રકાશ સંવેદના સ્તરે)',

'exif-whitebalance-0' => 'સ્વયંચાલિત ધવલ સમતોલન',
'exif-whitebalance-1' => 'માનવાચાલિત ધવલ સમતોલન',

'exif-scenecapturetype-0' => 'પ્રમાણભૂત',
'exif-scenecapturetype-1' => 'પ્રાકૃતિક દ્રશ્ય',
'exif-scenecapturetype-2' => 'શિલ્પ',
'exif-scenecapturetype-3' => 'રાત્રી દર્શન',

'exif-gaincontrol-0' => 'જરાપણ નહી',
'exif-gaincontrol-1' => 'લઘુત્તમ પ્રકાશ ગ્રહણ વધારો',
'exif-gaincontrol-2' => 'મહત્તમ પ્રકાશ ગ્રહણ વધારો',
'exif-gaincontrol-3' => 'લઘુત્તમ પ્રકાશ ગ્રહણ ઘટાડો',
'exif-gaincontrol-4' => 'મહત્તમ પ્રકાશ ગ્રહણ ઘટાડો',

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
'exif-subjectdistancerange-1' => 'કાર્યસૂચના- મેક્રો',
'exif-subjectdistancerange-2' => 'નજીક દર્શન',
'exif-subjectdistancerange-3' => 'દૂરનું દ્રશ્ય',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ઉત્તર અક્ષાંસ',
'exif-gpslatitude-s' => 'દક્ષિણ અક્ષાંસ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'પૂર્વ રેખાંશ',
'exif-gpslongitude-w' => 'પશ્ચિમ રેખાંશ',

'exif-gpsstatus-a' => 'માપન કાર્ય જારી',
'exif-gpsstatus-v' => 'માપન એકમ બદલની ક્ષમતા',

'exif-gpsmeasuremode-2' => 'દ્વી-પરિમાણીત માપન',
'exif-gpsmeasuremode-3' => 'ત્રિ-પરિમાણીત માપન',

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
'confirmemail'              => 'તમારા ઇ-મેઇલ સરનામાની પુષ્ટિ કરો',
'confirmemail_noemail'      => 'તમારા [[Special:Preferences|user preferences]] માં વૈધ ઈ-મેલ સરનામું નથી.',
'confirmemail_text'         => '{{SITENAME}} માં તમારા ઇ-મેલ સરનામાની પુષ્ટિ થયેલી હોવી જરૂરી છે.
નીચેના બટન પર  ક્લિક કરો અને પુષ્ટિ કરનાર ઇ-મેલ પાઠવો.
આ મેલમાં કોડ ધરાવતી એક કડી પણ મોકલાવાશે;
ઈ-મેલની પુષ્ટિ કરવા તે કડી તમારા બ્રાઉઝરમાં સક્રીય કરો.',
'confirmemail_pending'      => 'પુષ્ટિ કરતું કોડ તમને ઇ-મેલ કરી દેવાયો છે;
તમે જો હાલમાં તમારું ખાતું ખોલ્યું હોય, તો તમે અમુક ક્ષણો પ્રતિક્ષા કરી અને નવા કોડની વિનંતી કરવાનું ટાળી શકો.',
'confirmemail_send'         => 'ઈ-મેલ બહાલી સંકેત ઇ-મેલમાં મોકલો',
'confirmemail_sent'         => 'બહાલીનો ઇ-મેલ મોકલી દેવાયો છે',
'confirmemail_oncreate'     => 'પુષ્ટિકોડ તમને ઇમેલ દ્વારા મોકલવામાં આવ્યો છે. 
પ્રવેશ કરવા માટ તમને આ કોડની જરૂર નથી.  પણ ઇ-મેલ આધારિત વિકિ સેવાઓ વાપરવા આની જરૂર છે.',
'confirmemail_sendfailed'   => '{{SITENAME}} તમારો પુષ્ટિ કરનાર ઇ-મેલ નહીં મોકલી શકી.
કૃપયા તમારો ઇ-મેલ તપાસી અવૈધ અક્ષરો હોય તો તપાસો.
મેલની પહોંચ પાછી ફરી: $1',
'confirmemail_invalid'      => 'અવૈધ બહાલી સંકેત
સંકેત કાલાતિત થયું હોય',
'confirmemail_needlogin'    => 'તમારે ઈ-મેલ ને બહાલી આપવા $1 કરવું પડશે',
'confirmemail_success'      => 'તમારા ઈ-મેલની પુષ્ટિ થઈ ગઈ છે.
તમે હવે પ્રવેશ [[Special:UserLogin|log in]] કરી વિકિનો આનંદ ઉઠાવી શકો.',
'confirmemail_loggedin'     => 'તમારા ઇ-મેલ સરનામાની પુષ્ટિ કરાઇ છે.',
'confirmemail_error'        => 'પુષ્ટિ સાચવતા કોઈ ત્રુટિ રહી ગઈ',
'confirmemail_subject'      => '{{SITENAME}} ઈ-મેલ સરનામાની પુષ્ટિ',
'confirmemail_body'         => 'કોઇકે, કદાચ તમે પોતે જ , IP સરનામા  $1 પરથી,
 "$2" ખાતાનું ઇ-મેલ સરનામું બદલ્યું  {{SITENAME}} પર છે.

એ વાતની પુષ્ટિ કરવા માટે કે આ એ-મેલ ખાતું તમારું જ છે અને {{SITENAME}}ના ઇ-મેલ સંબંધિત વિકલ્પો સક્રીય કરવા માટે અપેલ કડીને તમારા બ્રાઉઝરમાં સક્રીય કરો. :

$3

જો ખાતું તમારું ના હોય તો,આકડીનેઅનુસરીઈ-મેલાખાતાનીપુષ્ટિનીનોંધણીને રદ્દ કરો:

$5

આ પુષ્ટિ કોડ  $4 ના કાલાતિત થશે.',
'confirmemail_body_changed' => 'કોઇકે, કદાચ તમે પોતે જ , IP સરનામા  $1 પરથી,
 "$2" ખાતાનું ઇ-મેલ સરનામું બદલ્યું  {{SITENAME}} પર છે.

એ વાતની પુષ્ટિ કરવા માટે કે આ એ-મેલ ખાતું તમારું જ છે અને {{SITENAME}}ના ઇ-મેલ સંબંધિત વિકલ્પો સક્રીય કરવા માટે અપ્ેલ કડીને તમારા બ્રાઉઝરમાં સક્રીય કરો. :

$3

જો ખાતું તમારું ના હોય તો,આકડીનેઅનુસરીઈ-મેલાખાતાનીપુષ્ટિનીનોંધણીને રદ્દ કરો:

$5

આ પુષ્ટિ કોડા  $4 ના કાલાતિત થશે.',
'confirmemail_body_set'     => 'કોઇકે, કદાચ તમે પોતે જ , IP સરનામા  $1 પરથી,
 "$2" ખાતાનું ઇ-મેલ સરનામું બદલ્યું  {{SITENAME}} પર છે.

એ વાતની પુષ્ટિ કરવા માટે કે આ એ-મેલ ખાતું તમારું જ છે અને {{SITENAME}}ના ઇ-મેલ સંબંધિત વિકલ્પો સક્રીય કરવા માટે અપેલ કડીને તમારા બ્રાઉઝરમાં સક્રીય કરો. :

$3

જો ખાતું તમારું ના હોય તો,આકડીનેઅનુસરીઈ-મેલાખાતાનીપુષ્ટિનીનોંધણીને રદ્દ કરો:

$5

આ પુષ્ટિ કોડ  $4 ના કાલાતિત થશે.',
'confirmemail_invalidated'  => 'ઇ-મેલ સરનામાનું બહાલીકરણ રદ્દ',
'invalidateemail'           => 'ઇ-મેલ બહાલી રદ્દ કરો',

# Scary transclusion
'scarytranscludedisabled' => 'આંતરવિકિ આંશિક સંદર્ભીકરણ નિષ્ક્રીય',
'scarytranscludefailed'   => '[$1 માટે ઢાંચો ખેંચી લાવવામાં અસફળ ]',
'scarytranscludetoolong'  => '[URL ઘણો લાંબો છે]',

# Trackbacks
'trackbackbox'      => 'આ પાનાના ટ્રેક બેક:<br />
$1',
'trackbackremove'   => '([$1 ભૂંસો])',
'trackbacklink'     => 'ટ્રેકબેક',
'trackbackdeleteok' => 'ટ્રેકબેક સફળતા પૂર્વક હટાવાયા.',

# Delete conflict
'deletedwhileediting' => "'''ચેતવણી''': તમે ફેરફાર  કર્યા પછી આ પાનું હટાવી દેવાયું !",
'confirmrecreate'     => "સભ્ય [[User:$1|$1]] ([[User talk:$1|talk]]) એતમે ફેરફાર કરો તે પહેલાં આ પાનું હટાવી દીધું તેનું કારણ:
: ''$2''
કૃપયા ખાત્રી કરો કે તમે આ પાનું ખરેખર રચવા માંગો છે.",
'recreate'            => 'પુનર્નિર્માણ કરો',

# action=purge
'confirm_purge_button' => 'મંજૂર',
'confirm-purge-top'    => 'આ પાનાનો સ્મૃતિ ભંડાર ખાલી કરું?',
'confirm-purge-bottom' => 'પાનાની પૂર્ણ સફાઇ તેનીએ માહિતી અને સંગ્રહની હંગામી માહિતી પણ ભૂંસી દઇ ફક્ત નવીનત્તમ માહિતી રાખશે.',

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
'table_pager_limit_label'  => 'વસ્તિઓ પ્રતિ પાને',
'table_pager_limit_submit' => 'જાઓ',
'table_pager_empty'        => 'કોઇ પરિણામ નથી',

# Auto-summaries
'autosumm-blank'   => 'પાનું ખાલી કરી દેવાયું',
'autosumm-replace' => 'માહિતીને "$1" થી બદલી',
'autoredircomment' => '[[$1]] પર દિશાનિર્દેશિત',
'autosumm-new'     => '$1થી શરૂ થતું નવું પાનું બાનવ્યું',

# Live preview
'livepreview-loading' => 'લવાઇ રહ્યું છે...',
'livepreview-ready'   => 'લવાઇ રહ્યું છે .... તૈયાર!',
'livepreview-failed'  => 'સજીવ ઝલક અસફળ
સામાન્ય ઝલક જુઓ',
'livepreview-error'   => ' $1 "$2" નો સંપર્ક અસફળ
સામાન્ય ઝલક જુઓ',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|સેકંડ|સેકંડો}} પછી થયેલ  નવા સુધારા કદાચ ન બતાવી શકાય.',
'lag-warn-high'   => 'માહિતીસંચ સર્વરના લાંબાગાળાને કારણે $1 {{PLURAL:$1|સેકંડ|સેકંડો}} પછી કરેલા ફેરફાર જોઇ શકાશે નહીં',

# Watchlist editor
'watchlistedit-numitems'       => 'તમારી ધ્યાન સૂચિ {{PLURAL:$1|1 શીર્ષક |$1 શીર્ષકો}} ધરાવે છે, ચર્ચાના પાના સિવાય.',
'watchlistedit-noitems'        => 'તમારી ધ્યાન સૂચિમાં કોઇ શીર્ષક નથી.',
'watchlistedit-normal-title'   => 'ધ્યાનસૂચિ માં ફેરફાર કરો',
'watchlistedit-normal-legend'  => 'ધ્યાનસૂચિમાંથી આશીર્ષકો કાઢી નાખો',
'watchlistedit-normal-explain' => 'તમારી ધ્યાનસૂચિના શીર્ષકો અહીં બતાવ્યાં છે.
કોઇ શીર્ષક હતાવવા માટે , તેની બાજું નું ખાનું અંકિત કરો , અને "{{int:Watchlistedit-normal-submit}}"ના પર ક્લિક કરો.
તમે [[Special:EditWatchlist/raw|edit the raw list]]માં  બદલાવ પણ કરી શકો.',
'watchlistedit-normal-submit'  => 'શીર્ષકો હટાવો',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 શીર્ષક |$1 શીર્ષકો }} ને તમારી ધ્યાનસૂચિમાંથી હટાવાયા:',
'watchlistedit-raw-title'      => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',
'watchlistedit-raw-legend'     => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',
'watchlistedit-raw-explain'    => 'તમારી ધ્યાનસૂચિના શીર્ષકો અહીં બતાવ્યાં છે , તેમાં વસ્તુ ઓ ઉમેરીએ અને હટાવીને તેમાં ફેરફાર કરી શકાય છે;
એક શીર્ષક પ્રતિ લિટી.
જ્યારે કાર્ય સમાપ્ત થાય ત્યારે, અહીં ક્લિક કરો "{{int:Watchlistedit-raw-submit}}".
તેમે  [[Special:EditWatchlist|use the standard editor]] પણ કરી શકો.',
'watchlistedit-raw-titles'     => 'શિર્ષક:',
'watchlistedit-raw-submit'     => 'ધ્યાનસૂચિ અધ્યતન બનાવો',
'watchlistedit-raw-done'       => 'તમારી ધ્યાના સૂચિ અધ્યતન કરાઈ.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 શીર્ષક |$1 શીર્ષકો}} ઉમેરાયા :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 શીર્ષક |$1 શીર્ષકો  }} હટાવાયા :',

# Watchlist editing tools
'watchlisttools-view' => 'બંધબેસતાં ફેરફારો નિહાળો',
'watchlisttools-edit' => 'ધ્યાનસૂચી જુઓ અને બદલો',
'watchlisttools-raw'  => 'કાચી ધ્યાનસૂચિમાં ફેરફાર કરો',

# Core parser functions
'unknown_extension_tag' => 'અજ્ઞાત વિસ્તારક શબ્દ "$1"',
'duplicate-defaultsort' => '\'\'\'ચેતવણી:\'\'\'  કી "$2" આગળનામૂળે પ્રસ્થાપિત ક્રમિકાવર્ગીકરણ કી "$1"નું સ્થાન લઈ લેશે..',

# Special:Version
'version'                          => 'આવૃત્તિ',
'version-extensions'               => 'પ્રસ્થાપિત વિસ્તારકો',
'version-specialpages'             => 'ખાસ પાનાં',
'version-parserhooks'              => 'પદચ્છેદ ખૂંટો',
'version-variables'                => 'સહગુણકો',
'version-antispam'                 => 'સ્પેમ સંરક્ષણ',
'version-skins'                    => 'ફલક',
'version-other'                    => 'અન્ય',
'version-mediahandlers'            => 'દ્રશ્યશ્રાવ્ય માધ્યમના ધારક',
'version-hooks'                    => 'ખૂંટા',
'version-extension-functions'      => 'વિસ્તારક કાર્ય',
'version-parser-extensiontags'     => 'પદચ્ચેદ વિસ્તારક નાકા',
'version-parser-function-hooks'    => 'પદચ્છેદ કાર્ય ખૂંટા',
'version-skin-extension-functions' => 'ફલક વિસ્તારક કાર્ય',
'version-hook-name'                => 'ખૂંટાનું નામ્',
'version-hook-subscribedby'        => 'દ્વ્રારા લાભાન્વીત',
'version-version'                  => '(આવૃત્તિ $1)',
'version-license'                  => 'પરવાનો',
'version-poweredby-credits'        => "આ વિકિ  '''[http://www.mediawiki.org/ MediaWiki]''' દ્વારા ચાલે છે, પ્રકાશનાધિકાર © 2001-$1 $2.",
'version-poweredby-others'         => 'અન્યો',
'version-license-info'             => 'મિડિયાવિકિ એક મુક્ત સોફ્ટવેર છે. તમે તેનું પુનઃવિતરણ કરી શકો છો અને/અથવા તેને the Free Software Foundation દ્વારા પ્રકાશિત  GNU General Public License હેઠળ તેના સંસ્કરણ 2 ને કે તે પછીના સંસ્કરણ   મઠારી શકો છો . 

મિડિયા વિકિ ને તે આશાથી વિતરીત કરાયું છે કે તે લોકોને ઉપયોગિ થશે કોઇ વોરેંટી વિના અથવા કોઇ કાર્ય સંબધી વેચાણકે તેની યોગ્યતા બદ્દલ ખાત્રી સિવાય. વધારે  માહિતે માટે GNU General Public Licens જુઓ.

આ પ્રોગ્રામ સાથે તમને  [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License]ની કૉપી મળી હશે. જો ન મલી હોય તો અહીં લખશો the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA કે  [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html ઓનલાઇન વાંચો ].',
'version-software'                 => 'બેસાડેલા સોફ્ટવેર',
'version-software-product'         => 'ઉત્પાદ',
'version-software-version'         => 'આવૃત્તિ',

# Special:FilePath
'filepath'         => 'ફાઈલ પથ',
'filepath-page'    => 'ફાઇલ:',
'filepath-submit'  => 'કરો',
'filepath-summary' => 'આ ખાસ પાનું કોઇ ફાઇલનો સંપૂર્ણ પથ બતાવે છે.
ચિત્રોને તેમના પૂર્ણ ઘનત્વ સાથે બતાવાય છે, અન્ય ફાઈલો તેમને તેમના સંલગ્ન પ્રોગ્રામ દ્વારા સીધી ખોલવામાં આવશે.
ફાઈલ નામને તેમના "{{ns:file}}:" પૂર્વર્ગ વિના લખો.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'નકલ ફાઇલ શોધો',
'fileduplicatesearch-summary'   => 'હેશ કિંમત પર આધારિત આબેહૂબ ફાઇલો શોધો.',
'fileduplicatesearch-legend'    => 'નકલ શોધો',
'fileduplicatesearch-filename'  => 'ફાઇલ નામ',
'fileduplicatesearch-submit'    => 'શોધ',
'fileduplicatesearch-info'      => '$1 × $2 પીક્સલ, ફાઇલનું કદ: $3, MIME પ્રકાર: $4',
'fileduplicatesearch-result-1'  => 'ફાઇલ "$1" ની  સમાન કોઇ નકલ નથી.',
'fileduplicatesearch-result-n'  => '"$1" ફાઇલની તેની સમાન {{PLURAL:$2|1 નકલ |$2 નકલો }} છે.',
'fileduplicatesearch-noresults' => ' "$1" નામ ધરાવતી કોઇ ફાઇલ ન મળી',

# Special:SpecialPages
'specialpages'                   => 'ખાસ પાનાં',
'specialpages-note'              => '----
* નિયમિત ખાસ પાનાં.
* <span class="mw-specialpagerestricted">સીમિત ખાસ પાનાં.</span>
* <span class="mw-specialpagecached">માત્ર-સંગ્રહિત ખાસ પાનાં.</span>',
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
'specialpages-group-redirects'   => 'ખાસ પાના પરના સમૂહ દિશાનિર્દેશન',
'specialpages-group-spam'        => 'સ્પેમ સાધનો',

# Special:BlankPage
'blankpage'              => 'કોરું પાનું',
'intentionallyblankpage' => 'આપાનું જાણે કરીને કોરું રખાયું છે',

# External image whitelist
'external_image_whitelist' => ' #આ લીટી જેમ છે તેમ જ રહેવા દો. <pre>
#પ્રાયઃ મુકાતા ખંડન કરનારા ચિન્હો મુકો (જેમ કે // વચ્ચે મુકાતા ભાગ) 
#આને બહારથી આવતી ચિત્રોના URLs સાથે સરાખાવાશે 
#જે મેળ ખાશે તેને ચિત્ર તરીકે બતાવાશે , અન્યથા માત્ર તે ચિત્ર પર દોરી જતી કડી બતાવાશે
#લીટીઓ કે જે  # થી શરૂ થશે તેને નોંધ માનવામાં આવશે.
#આ બધી અક્ષર અને માત્રાઅ સંવેદન હશે. 

#દરેક regex ટુકડાઓ આ લીટીની ઉપર મુકો. અને આ લીટીને જેમ છે તેમ મુકો </pre>',

# Special:Tags
'tags'                    => 'વૈધ ફેરફાર પાના',
'tag-filter'              => '[[Special:Tags|Tag]] ચાળણી',
'tag-filter-submit'       => 'ચાળણી',
'tags-title'              => 'નાકા',
'tags-intro'              => 'આ પાના પર સોફ્ટવેર દ્વારા અંકિત ફેરફારના ચિન્હો અને તેમનો અર્થ બતાવે છે.',
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
'dberr-header'      => 'આ વિકિમાં તકલીફ છે',
'dberr-problems'    => 'દિલગીરી!
આ સાઇટ તકનિકી અડચણ અનુભવી રહી છે.',
'dberr-again'       => 'થોડી વાર રાહ જોઈને ફરી પેજ લોડ કરવાનો પ્રયત્ન કરો.',
'dberr-info'        => '(માહિતી સંચય સર્વર : $1નો સંપર્ક નથી કરી શકાયો)',
'dberr-usegoogle'   => 'તેસમયા દરમ્યાન તમે ગુગલ દ્વારા શોધી શકો',
'dberr-outofdate'   => 'આપણી માહિતી સંબંધી તેમની સૂચિ કાલાતિત હોઇ શકે.',
'dberr-cachederror' => 'વિનંતિ કરેલ પાનાની આ એક સંગ્રહીત પ્રત માત્ર છે અને તે અધ્યતન ન પણ હોય.',

# HTML forms
'htmlform-invalid-input'       => 'તમારા અમુક ઉમેરા માંઅમુક તકલીફ છે',
'htmlform-select-badoption'    => 'તમે બતાવેલ વિકલ્પ અવૈધ છે',
'htmlform-int-invalid'         => 'તમે લખેલ વિકલ્પ અંક નથી',
'htmlform-float-invalid'       => 'તમે લખેલ વિકલ્પ અંક નથી',
'htmlform-int-toolow'          => '$1 આ કિંમત આ ન્યૂનતમ કિંમત છે',
'htmlform-int-toohigh'         => 'તમે પુરી પાડેલ માહિતી મહત્તમ $1થી વધુ છે',
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
'disableaccount-confirm'     => "આ ખાતું નિષ્ક્રિય બનાવો.
સભ્ય ફરી પ્રવેશ નહીં કરી શકે, તેમની ગુપ્ત સંજ્ઞા પુનઃ ગઠન કરો અથવા ઇ-મેલ સૂચના મેળવો.
જો ત સભ્યએ આ સમયે ક્યાંયથી પણ પ્રવેશ કર્યો હશે તો તેઓ તાત્કાલીક લોગ આઉટ  થઇ જશે. 
''યાદ રાખો કે નિષ્ક્રીય કરેલા ખાતાને પ્રબંધક સિવાય કોઇ શક્રીય કરી શકશે નહીં.''",
'disableaccount-mustconfirm' => 'તમારે પુષ્ટિ કરવી પડશે કે તમે ખાતાને નિષ્ક્રીય કરવા માંગો છો.',
'disableaccount-nosuchuser'  => 'સભ્ય ખાતું "$1" અસ્તિત્વમાં નથી',
'disableaccount-success'     => 'સભ્ય ખાતું "$1" કાયમી ધોરણે પ્રતિબંધીત કરાયું',
'disableaccount-logentry'    => ' સભ્ય ખાતું [[$1]] કાયમી ધોરણે નિષ્ક્રીય બનાવાયું',

);
