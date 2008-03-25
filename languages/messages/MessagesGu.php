<?php
/** Gujarati (ગુજરાતી)
 *
 * @addtogroup Language
 *
 * @author לערי ריינהארט
 * @author Aksi great
 * @author SPQRobin
 * @author Dsvyas
 */

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
'tog-underline'          => 'કડીઓની નીચે લીટી (અંડરલાઇન) ઉમેરો:',
'tog-highlightbroken'    => 'અપૂર્ણ કડીઓ<a href="" class="new">ને આ રીતે</a> (alternative: like this<a href="" class="internal">?</a>) લખો.',
'tog-hideminor'          => 'હાલમાં થયેલા ફેરફારમાં નાના ફેરફારો છુપાવો',
'tog-extendwatchlist'    => 'ધ્યાનસૂચિને વિસ્તૃત કરો જેથી બધા આનુષાંગિક ફેરફારો જોઇ શકાય',
'tog-numberheadings'     => 'મથાળાઓને આપો-આપ ક્રમ (ઑટો નંબર) આપો',
'tog-showtoolbar'        => 'ફેરફારો માટેનો ટૂલબાર બતાવો (જાવા સ્ક્રિપ્ટ)',
'tog-showtoc'            => 'અનુક્રમણિકા દર્શાવો (૩થી વધુ પેટા-મથાળા વાળા લેખો માટે)',
'tog-rememberpassword'   => 'આ કમ્પ્યૂટર પર મારી લોગ-ઇન વિગતો યાદ રાખો',
'tog-watchcreations'     => 'મેં લખેલા નવા લેખો મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdefault'       => 'હું ફેરફાર કરૂં તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchmoves'         => 'હું જેનું નામ બદલું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-watchdeletion'      => 'હું હટાવું તે પાના મારી ધ્યાનસૂચિમાં ઉમેરો',
'tog-forceeditsummary'   => "કોરો 'ફેરફાર સારાંશ' ઉમેરતા પહેલા મને ચેતવો",
'tog-watchlisthideown'   => "'મારી ધ્યાનસુચી'માં મે કરેલા ફેરફારો છુપાવો",
'tog-watchlisthideminor' => "'મારી ધ્યાનસુચી'માં નાનાં ફેરફારો છુપાવો",
'tog-ccmeonemails'       => 'મે અન્યોને મોકલેલા ઇ-મેઇલની નકલ મને મોકલો',
'tog-showhiddencats'     => 'છુપી શ્રેણીઓ દર્શાવો',

'underline-always' => 'હંમેશાં',
'underline-never'  => 'કદી નહિ',

'skinpreview' => '(ફેરફાર બતાવો)',

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
'categories'                  => 'શ્રેણીઓ',
'categoriespagetext'          => 'નીચેની શ્રેણીઓમાં પાના કે અન્ય સભ્યો છે.',
'pagecategories'              => '{{PLURAL:$1|શ્રેણી|શ્રેણીઓ}}',
'category_header'             => 'શ્રેણી "$1"માં પાના',
'subcategories'               => 'ઉપશ્રેણીઓ',
'category-media-header'       => 'શ્રેણી "$1"માં દ્રશ્ય કે શ્રાવ્ય સભ્યો',
'category-empty'              => "''આ શ્રેણીમાં હાલમાં કોઇ લેખ કે અન્ય સભ્ય નથી.''",
'hidden-categories'           => '{{PLURAL:$1|છુપી શ્રેણી|છુપી શ્રેણીઓ}}',
'hidden-category-category'    => 'છુપી શ્રેણીઓ', # Name of the category where hidden categories will be listed
'category-file-count'         => '{{PLURAL:$2|આ શ્રેણીમાં ફક્ત નીચે દર્શાવેલ દસ્તાવેજ છે.|આ શ્રેણીમાં કુલ $2 પૈકી નીચે દર્શાવેલ {{PLURAL:$1|દસ્તાવેજ|દસ્તાવેજો}} છે.}}',
'category-file-count-limited' => 'નીચે દર્શાવેલ {{PLURAL:$1|દસ્તાવેજ|દસ્તાવેજો}} પ્રસ્તુત શ્રેણીમાં છે.',
'listingcontinuesabbrev'      => 'ચાલુ..',

'about'         => 'વિષે',
'cancel'        => 'રદ કરો',
'moredotdotdot' => 'વધારે...',
'mypage'        => 'મારું પાનું',
'mytalk'        => 'મારી ચર્ચા',
'navigation'    => 'ભ્રમણ',
'and'           => 'અને',

'tagline'          => '{{SITENAME}} થી',
'help'             => 'મદદ',
'search'           => 'શોધો',
'searchbutton'     => 'શોધો',
'go'               => 'જાઓ',
'searcharticle'    => 'જાઓ',
'history'          => 'પાનાનો ઇતિહાસ',
'history_short'    => 'ઇતિહાસ',
'info_short'       => 'માહિતી',
'edit'             => 'ફેરફાર કરો',
'editthispage'     => 'આ પાના માં ફેરફાર કરો',
'delete'           => 'હટાવો',
'deletethispage'   => 'આ પાનું હટાવો',
'protect'          => 'સુરક્ષિત કરો',
'newpage'          => 'નવું પાનું',
'talkpage'         => 'આ પાના વિષે ચર્ચા કરો',
'talkpagelinktext' => 'ચર્ચા',
'specialpage'      => 'ખાસ પાનુ',
'talk'             => 'ચર્ચા',
'toolbox'          => 'ઓજારની પેટી',
'viewtalkpage'     => 'ચર્ચા જુઓ',
'otherlanguages'   => 'બીજી ભાષાઓમાં',
'lastmodifiedat'   => 'આ પાનાંનો છેલ્લો ફેરફાર $1એ $2 વાગ્યે થયો.', # $1 date, $2 time
'jumptonavigation' => 'ભ્રમણ',
'jumptosearch'     => 'શોધો',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} વિષે',
'aboutpage'         => 'વિકિપીડિયા:વિષે',
'currentevents'     => 'વર્તમાન ઘટનાઓ',
'currentevents-url' => 'પરિયોજના:વર્તમાન ઘટનાઓ',
'edithelp'          => 'ફેરફારો માટે મદદ',
'mainpage'          => 'મુખપૃષ્ઠ',
'portal'            => 'સમાજ મુખપૃષ્ઠ',
'sitesupport'       => 'દાન',

'ok'                  => 'મંજૂર',
'retrievedfrom'       => '"$1" થી લીધેલું',
'newmessageslink'     => 'નુતન સંદેશ',
'newmessagesdifflink' => 'છેલ્લો ફેરફાર',
'editsection'         => 'ફેરફાર કરો',
'editold'             => 'ફેરફાર કરો',
'showtoc'             => 'બતાવો',
'hidetoc'             => 'છુપાવો',
'viewdeleted'         => '$1 તપાસવી છે?',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'લેખ',
'nstab-special'   => 'ખાસ',
'nstab-mediawiki' => 'સંદેશ',
'nstab-help'      => 'મદદનું પાનું',

# Main script and global functions
'nosuchspecialpage' => 'એવું ખાસ પાનું નથી',

# General errors
'badtitle'      => 'ખરાબ નામ',
'viewsourcefor' => '$1ને માટે',

# Login and logout pages
'userlogin'       => 'Log in / નવું ખાતું ખોલો',
'nologin'         => 'શું તમારૂં ખાતું નથી? $1.',
'nologinlink'     => 'ખાતું ખોલો',
'createaccount'   => 'નવું ખાતું ખોલો',
'yourrealname'    => 'સાચું નામ:',
'yourlanguage'    => 'ભાષા',
'nosuchusershort' => '"<nowiki>$1</nowiki>" નામનો કોઇ સભ્ય નથી, તમારી જોડણી તપાસો.',
'accountcreated'  => 'ખાતું ખોલવામાં આવ્યું છે',

# Edit pages
'summary'           => 'સારાંશ',
'minoredit'         => 'આ એક નાનો સુધારો છે.',
'watchthis'         => 'આ પાનાને ધ્યાનમાં રાખો',
'savearticle'       => 'કાર્ય સુરક્ષિત કરો',
'showpreview'       => 'ઝલક બતાવો',
'showdiff'          => 'ફેરફારો બતાવો',
'blockededitsource' => "'''$1''' માટે '''તમારા ફેરફારો''' નીચે દેખાય છે:",
'newarticle'        => '(નવિન)',
'editing'           => '$1નો ફેરફાર કરી રહ્યા છે',
'yourdiff'          => 'ભેદ',

# History pages
'nohistory'    => 'આ પાનાનાં ફેરફારનો ઇતિહાસ નથી.',
'cur'          => 'વર્તમાન',
'next'         => 'આગળ',
'last'         => 'છેલ્લું',
'page_first'   => 'પહેલું',
'page_last'    => 'છેલ્લું',
'histfirst'    => 'સૌથી જુનું',
'histlast'     => 'સૌથી નવું',
'historyempty' => '(ખાલી)',

# Diffs
'lineno'   => 'લીટી $1:',
'editundo' => 'રદ કરો',

# Search results
'searchresults' => 'પરિણામોમાં શોધો',
'prevn'         => 'પાછળનાં $1',
'nextn'         => 'આગળનાં $1',
'viewprevnext'  => 'જુઓ: ($1) ($2) ($3)',
'powersearch'   => 'શોધો (વધુ પર્યાય સાથે)',

# Preferences page
'mypreferences'     => 'મારી પસંદ',
'datetime'          => 'તારીખ અને સમય',
'prefs-watchlist'   => 'ધ્યાનસૂચી',
'searchresultshead' => 'શોધો',

# Groups
'group'     => 'સમુહ',
'group-all' => '(બધા)',

# User rights log
'rightsnone' => '(કોઈ નહિ)',

# Recent changes
'recentchanges'   => 'હાલ માં થયેલા ફેરફાર',
'rclistfrom'      => '$1 બાદ થયેલા નવા ફેરફારો બતાવો',
'rcshowhideminor' => 'નાના ફેરફારો $1',
'rcshowhidebots'  => 'બૉટો $1',
'rcshowhidemine'  => 'મારા ફેરફારો $1',
'rclinks'         => 'છેલ્લાં $2 દિવસમાં થયેલા છેલ્લાં $1 ફેરફારો દર્શાવો<br />$3',
'diff'            => 'ભેદ',
'hist'            => 'ઇતિહાસ',
'hide'            => 'છુપાવો',
'show'            => 'બતાવો',
'minoreditletter' => 'નાનું',
'newpageletter'   => 'નવું',
'boteditletter'   => 'બૉટ',

# Recent changes linked
'recentchangeslinked' => 'આ પાના સાથે જોડાયેલા ફેરફાર',

# Upload
'upload' => 'ફાઇલ ચડાવો',

# Random page
'randompage' => 'કોઈ પણ એક લેખ',

'brokenredirects-edit'   => '(ફેરફાર કરો)',
'brokenredirects-delete' => '(હટાવો)',

# Miscellaneous special pages
'nmembers'          => '$1 {{PLURAL:$1|સદસ્ય|સદસ્યો}}',
'specialpage-empty' => 'આ પાનું ખાલી છે.',
'lonelypages'       => 'અનાથ પાના',
'shortpages'        => 'નાનાં પાનાં',
'specialpages'      => 'ખાસ પાનાં',
'newpages'          => 'નવા પાના',
'ancientpages'      => 'સૌથી જૂનાં પાના',
'move'              => 'નામ બદલો',

# Book sources
'booksources-isbn' => 'આઇએસબીએન:',
'booksources-go'   => 'જાઓ',

# Special:Log
'log-search-submit' => 'શોધો',

# Special:Allpages
'allpages'       => 'બધા પાના',
'alphaindexline' => '$1 થી $2',
'nextpage'       => 'આગળનું પાનું ($1)',
'prevpage'       => 'પાછળનું પાનું ($1)',
'allarticles'    => 'બધા લેખ',
'allpagesprev'   => 'પહેલાનું',
'allpagesnext'   => 'પછીનું',
'allpagessubmit' => 'જાઓ',

# Special:Listusers
'listusers-submit' => 'બતાવો',

# E-mail user
'emailfrom'    => 'મોકલનાર',
'emailto'      => 'લેનાર',
'emailsubject' => 'વિષય',
'emailmessage' => 'સંદેશ',
'emailsend'    => 'મોકલો',

# Watchlist
'watchlist'            => 'મારી ધ્યાનસૂચી',
'mywatchlist'          => 'મારી ધ્યાનસૂચી',
'watchlistfor'         => "('''$1'''ને માટે)",
'watch'                => 'ધ્યાન માં રાખો',
'watchlist-details'    => 'ચર્ચા વાળા પાના ન ગણતા {{PLURAL:$1|$1 પાનું|$1 પાનાં}} ધ્યાનસૂચી મા છે.',
'watchlistcontains'    => 'તમારી ધ્યાનસૂચીમાં $1 {{PLURAL:$1|પાનું|પાનાં}} છે.',
'watchlist-hide-bots'  => 'બૉટના ફેરફાર સંતાડો',
'watchlist-hide-own'   => 'મારા ફેરફાર સંતાડો',
'watchlist-hide-minor' => 'નાના ફેરફાર સંતાડો',

'enotif_newpagetext' => 'આ નવું પાનું છે.',
'changed'            => 'બદલાયેલું',

# Delete/protect/revert
'deletepage'     => 'પાનું હટાવો',
'confirm'        => 'ખાતરી કરો',
'exblank'        => 'પાનું ખાલી હતું',
'actioncomplete' => 'કામ પૂરું થઈ ગયું',

# Restrictions (nouns)
'restriction-edit' => 'બદલો',

# Undelete
'undelete-search-submit' => 'શોધો',

# Namespace form on various pages
'blanknamespace' => '(મુખ્ય)',

# Contributions
'mycontris' => 'મારું યોગદાન',

'sp-contributions-submit' => 'શોધો',

# What links here
'whatlinkshere' => 'અહિયાં શું જોડાય છે',
'linklistsub'   => '(કડીઓની સૂચી)',

# Block/unblock
'ipbreason'          => 'કારણ',
'ipbreasonotherlist' => 'બીજું કારણ',
'ipbother'           => 'અન્ય સમય',
'ipbotheroption'     => 'બીજું',
'ipblocklist-submit' => 'શોધો',
'anononlyblock'      => 'માત્ર અનામી',
'contribslink'       => 'યોગદાન',

# Move page
'1movedto2'               => '[[$1]] નું નામ બદલી ને [[$2]] કરવામાં આવ્યું છે.',
'movereason'              => 'કારણ',
'delete_and_move'         => 'હટાવો અને નામ બદલો',
'delete_and_move_confirm' => 'હા, આ પાનું હટાવો',

# Export
'export-addcat' => 'ઉમેરો',

# Namespace 8 related
'allmessagesname'    => 'નામ',
'allmessagescurrent' => 'વર્તમાન દસ્તાવેજ',

# Tooltip help for the actions
'tooltip-pt-mytalk'       => 'મારી ચર્ચાનું પાનું',
'tooltip-pt-preferences'  => 'મારી પસંદ',
'tooltip-ca-protect'      => 'આ પાનું સુરક્ષિત કરો',
'tooltip-ca-delete'       => 'આ પાનું હટાવો',
'tooltip-ca-watch'        => 'આ પાનું તમારી ધ્યાનસૂચીમા ઉમેરો',
'tooltip-ca-unwatch'      => 'આ પાનું તમારી ધ્યાનસૂચીમાથી કાઢી નાખો',
'tooltip-search'          => '{{SITENAME}} શોધો',
'tooltip-p-logo'          => 'મુખપૃષ્ઠ',
'tooltip-n-mainpage'      => 'મુખપૃષ્ઠ પર જાઓ',
'tooltip-n-recentchanges' => 'વિકિમાં હાલમા થયેલા ફેરફારો ની સૂચિ.',
'tooltip-n-sitesupport'   => 'અમારું સમર્થન કરો',
'tooltip-t-specialpages'  => 'ખાસ પાનાંઓની સૂચિ',

# Info page
'infosubtitle' => 'પાના વિષે માહિતી',
'numedits'     => 'ફેરફારોની સંખ્યા (લેખ): $1',
'numtalkedits' => 'ફેરફારોની સંખ્યા (ચર્ચાનું પાનું): $1',

# Special:Newimages
'noimages' => 'જોવા માટે કશું નથી.',
'ilsubmit' => 'શોધો',
'bydate'   => 'તારીખ પ્રમાણે',

# EXIF tags
'exif-imagewidth'  => 'પહોળાઈ',
'exif-imagelength' => 'ઊંચાઈ',
'exif-artist'      => 'કલાકાર',

'exif-unknowndate' => 'અજ્ઞાત તારીખ',

'exif-orientation-1' => 'સામાન્ય', # 0th row: top; 0th column: left

'exif-componentsconfiguration-0' => 'નથી',

'exif-meteringmode-0'   => 'અજાણ્યો',
'exif-meteringmode-255' => 'બીજું કઈ',

'exif-lightsource-0' => 'અજાણ્યો',

'exif-gaincontrol-0' => 'નથી',

'exif-saturation-0' => 'સામાન્ય',

'exif-sharpness-0' => 'સામાન્ય',

'exif-subjectdistancerange-0' => 'અજાણ્યો',

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
'watchlisttools-edit' => 'ધ્યાનસૂચી જુઓ અને બદલો',

);
