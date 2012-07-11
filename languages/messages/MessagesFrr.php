<?php
/** Northern Frisian (Nordfriisk)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Maartenvdbent
 * @author Merlissimo
 * @author Murma174
 * @author Pyt
 */

$fallback = 'de';

$linkTrail = '/^([a-zäöüßåāđē]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Linke unerstrike:',
'tog-highlightbroken'         => 'Linke aw ai bestönjene side beklåme <a href="" class="new">biispel</a> (alternatiiwe: ås dideere<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Täkst ås blokseeting',
'tog-hideminor'               => 'Latje änringe fersteege',
'tog-hidepatrolled'           => 'Latje änringe fersteege',
'tog-newpageshidepatrolled'   => 'Kontroliirde side aw e list "Naie side" fersteege',
'tog-extendwatchlist'         => 'Ütwidede wåchelist tun wisen foon åle änringe',
'tog-usenewrc'                => "Ütwidede deerstaling foon da ''Leeste Änringe'' än aw da ''Eefterkiikliste'' (brükt JavaScript)",
'tog-numberheadings'          => 'Ouerschrafte automatisch numeriire',
'tog-showtoolbar'             => 'Beårbe-wärktjüch wise',
'tog-editondblclick'          => 'Side ma dööweltklik beårbe (brükt JavaScript)',
'tog-editsection'             => 'Ferbininge tun wisen foon änkelte oufsnaase',
'tog-editsectiononrightclick' => 'Änkelte stöögne ma ruchts kliken beårbe (brükt JavaScript)',
'tog-showtoc'                 => 'Wis en inhåltsferteeknis for side ma mäs ås trii ouerschrafte',
'tog-rememberpassword'        => 'Aw diheere komputer foon duur önjmälde (maksimool for $1 {{PLURAL:$1|däi|deege}})',
'tog-watchcreations'          => "Salew maaget sidjen an huuchlooset dateien leewen uun't uug behual",
'tog-watchdefault'            => "Salew feranert sidjen an dateien leewen uun't uug behual",
'tog-watchmoves'              => "Salew fersköwen sidjen an dateien leewen uun't uug behual",
'tog-watchdeletion'           => "Salew stregen sidjen an dateien leewen uun't uug behual",
'tog-minordefault'            => 'Äine änringe gewöönlik ås latj mårkiire',
'tog-previewontop'            => 'Forbekiiken boowen dåt beårbingswaning wise',
'tog-previewonfirst'          => 'Bai dåt jarst beårben åltens dåt forbekiiken wise',
'tog-nocache'                 => 'Sidecache foon e browser deaktiwiire',
'tog-enotifwatchlistpages'    => "Sjüür mi en E-Mail, wan sidjen of dateien feranert wurd, diar ik uun't uug behual wal",
'tog-enotifusertalkpages'     => 'Bai änringe tu min brüker-diskusjoonssid E-Maile siinje',
'tog-enotifminoredits'        => 'Sjüür mi uk bi letj feranrangen faan sidjen an dateien en E-Mail',
'tog-enotifrevealaddr'        => 'Min E-mail-adräs önj tising-E-maile wise',
'tog-shownumberswatching'     => 'Wis di tål foon wåchende brükere',
'tog-oldsig'                  => 'Aktuel signatuur:',
'tog-fancysig'                => 'Signatuur behoonle ås wikitäkst',
'tog-externaleditor'          => 'Gewöönlik äksterne ediitor for färsjoonsunerschiise brüke (bloot for ekspärte, deer mönje spetsjäle önjstalinge aw di äine kompjuuter fornümen wårde
[//www.mediawiki.org/wiki/Manual:External_editors Mör informatsjoone.])',
'tog-externaldiff'            => 'Gewöönlik äkstern program for färsjoonsunerschiise brüke (bloot for ekspärte, deer mönje spetsjäle önjstalinge aw di äine kompjuuter fornümen wårde[//www.mediawiki.org/wiki/Manual:External_editors Mör informatsjoone.])',
'tog-showjumplinks'           => '"Schafte tu"-ferbininge aktiwiire',
'tog-uselivepreview'          => 'Live-forbekiik ferwiinje (brükt JavaScript) (äksperimentäl)',
'tog-forceeditsummary'        => 'Woorschoue, wan bai dåt spiikern jü tuhuupefooting breecht',
'tog-watchlisthideown'        => 'Äine beårbinge önj e bekiiklist fersteege',
'tog-watchlisthidebots'       => 'Beårbinge döör bots önj e bekiiklist fersteege',
'tog-watchlisthideminor'      => 'Latje beårbinge önj e bekiiklist fersteege',
'tog-watchlisthideliu'        => 'Beårbinge foon önjmäldede brükere önj e bekiikliste fersteege',
'tog-watchlisthideanons'      => 'Beårbinge foon ai önjmäldede brükere önj e bekiikliste fersteege',
'tog-watchlisthidepatrolled'  => 'Eefterkiikede beårbinge önj e bekiiklist fersteege',
'tog-ccmeonemails'            => 'Siinje me kopiie foon e-maile, da ik tu oudere brükere siinje',
'tog-diffonly'                => 'Wis bai di fersjoonsferglik bloot da unerschiise, ai jü hiilj sid',
'tog-showhiddencats'          => 'Wis ferstäägene kategoriie',
'tog-norollbackdiff'          => 'Unerschiis eefter dåt tübäägseeten unerdrüke',

'underline-always'  => 'Åltens',
'underline-never'   => 'uler',
'underline-default' => 'oufhingi foon browser-önjstaling',

# Font style option in Special:Preferences
'editfont-style'     => 'Schraftfamiili for di takst onj dåt beårbingswaning:',
'editfont-default'   => 'oufhingi foon browser-önjstaling',
'editfont-monospace' => 'Schraft ma fååst tiikenbrååtj',
'editfont-sansserif' => 'Seriifen-lüüse grotäskschraft',
'editfont-serif'     => 'Schraft ma seriife',

# Dates
'sunday'        => 'Saandi',
'monday'        => 'Moundi',
'tuesday'       => 'Täisdi',
'wednesday'     => 'Weensdi',
'thursday'      => 'Törsdi',
'friday'        => 'Fraidi',
'saturday'      => 'Saneene',
'sun'           => 'Sd',
'mon'           => 'Mo',
'tue'           => 'Tä',
'wed'           => 'We',
'thu'           => 'Tö',
'fri'           => 'Fr',
'sat'           => 'Se',
'january'       => 'Januar',
'february'      => 'Feebruar',
'march'         => 'Marts',
'april'         => 'April',
'may_long'      => 'Moi',
'june'          => 'Juuni',
'july'          => 'Juuli',
'august'        => 'August',
'september'     => 'Septämber',
'october'       => 'Oktoober',
'november'      => 'Nowämber',
'december'      => 'Detsämber',
'january-gen'   => 'Januar',
'february-gen'  => 'Feebruar',
'march-gen'     => 'Marts',
'april-gen'     => 'April',
'may-gen'       => 'Moi',
'june-gen'      => 'Juuni',
'july-gen'      => 'Juuli',
'august-gen'    => 'August',
'september-gen' => 'Septämber',
'october-gen'   => 'Oktoober',
'november-gen'  => 'Nowämber',
'december-gen'  => 'Detsämber',
'jan'           => 'Jan.',
'feb'           => 'Feb.',
'mar'           => 'Mar.',
'apr'           => 'Apr.',
'may'           => 'Moi',
'jun'           => 'Jun.',
'jul'           => 'Jul.',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Now.',
'dec'           => 'Det.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorii|Kategoriie}}',
'category_header'                => 'Side önj e kategorii "$1"',
'subcategories'                  => 'Unerkategoriie',
'category-media-header'          => 'Meedia önj e kategorii "$1"',
'category-empty'                 => '"Jüdeer kategorii önjhüült nütutids niinj side unti meedie."',
'hidden-categories'              => '{{PLURAL:$1|Ferstäägen Kategorii|Ferstäägene Kategoriie}}',
'hidden-category-category'       => 'Ferstäägene kategoriie',
'category-subcat-count'          => '{{PLURAL:$2|Jüdeer kategorii önjthålt füliend unerkategorii:|{{PLURAL:$1|Füliend unerkategorii as iinj foon inåål $2 unerkategoriie önj jüdeer kategorii:|Deer wårde $1 foon inåål $2 unerkategoriie önj jüdeer kategorii wised:}}}}',
'category-subcat-count-limited'  => 'Jüdeer kategorii önjthålt füliende {{PLURAL:$1|unerkategorii|$1 unerkategoriie}}:',
'category-article-count'         => '{{PLURAL:$2|Jüdeer kategorii önjthålt füliende sid:|{{PLURAL:$1|Füliende sid as iinj foon inåål $2 side önj jüdeer kategorii:|Deer wårde $1 foon inåål $2 side önj jüdeer kategorii wised:}}}}',
'category-article-count-limited' => 'Füliende {{PLURAL:$1|sid as|$1 side san}} önj jüheer kategorii önjthülen:',
'category-file-count'            => '{{PLURAL:$2|Jüdeer kategorii önjthålt füliende dootäi:|{{PLURAL:$1|Füliende dootäi as iinj foon inåål $2 side önj jüdeer kategorii:|Deer wårde $1 foon inåål $2 dootäie önj jüdeer kategorii wised:}}}}',
'category-file-count-limited'    => 'Füliende {{PLURAL:$1|Dootäi as|$1 Dootäie san}} önj jüdeer kategorii önjthülen:',
'listingcontinuesabbrev'         => '(fortseeting)',
'index-category'                 => 'Indisiirde side',
'noindex-category'               => 'Ai indisiirde side',
'broken-file-category'           => 'Sid ma önjstöögne ferwisinge',

'about'         => 'Ouer',
'article'       => 'Sid',
'newwindow'     => '(wårt önj en nai waning ääm mååged)',
'cancel'        => 'Oufbreege',
'moredotdotdot' => 'Mör ...',
'mypage'        => 'Äine sid',
'mytalk'        => 'Äine diskusjoon',
'anontalk'      => 'Diskusjoonssid foon jüdeer IP',
'navigation'    => 'Navigasjoon',
'and'           => '&#32;än',

# Cologne Blue skin
'qbfind'         => 'Fine',
'qbbrowse'       => 'Bleese',
'qbedit'         => 'Änre',
'qbpageoptions'  => 'Jüdeer sid',
'qbpageinfo'     => 'Sidedoote',
'qbmyoptions'    => 'Min side',
'qbspecialpages' => 'Spetsjåålside',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Stuk haanetufoue',
'vector-action-delete'           => 'Strike',
'vector-action-move'             => 'Ferschüwe',
'vector-action-protect'          => 'Sääkere',
'vector-action-undelete'         => 'Wi mååge',
'vector-action-unprotect'        => 'Frijeewe unti späre',
'vector-simplesearch-preference' => 'Ütwidede säkforsliike aktiwiire (bloot vector)',
'vector-view-create'             => 'Mååge',
'vector-view-edit'               => 'Beårbe',
'vector-view-history'            => 'Dootäifärsjoone',
'vector-view-view'               => 'Lees',
'vector-view-viewsource'         => 'Kwältäkst önjkiike',
'actions'                        => 'Aksjoone',
'namespaces'                     => 'Noomerüme',
'variants'                       => 'Fariante',

'errorpagetitle'    => 'Fäägel',
'returnto'          => 'Tubääg tu jü side $1.',
'tagline'           => 'Üt {{SITENAME}}',
'help'              => 'Heelp',
'search'            => 'Säk',
'searchbutton'      => 'Säke',
'go'                => 'Ütfääre',
'searcharticle'     => 'Sid',
'history'           => 'Färsjoone',
'history_short'     => 'Färsjoone/autoore',
'updatedmarker'     => 'änred sunt man leest besäk',
'printableversion'  => 'Prantfärsjoon',
'permalink'         => 'Permanänten link',
'print'             => 'Prante',
'view'              => 'Lees',
'edit'              => 'Beårbe',
'create'            => 'Mååge',
'editthispage'      => 'Sid beårbe',
'create-this-page'  => 'Sid mååge',
'delete'            => 'Strike',
'deletethispage'    => 'Jüdeer sid strike',
'undelete_short'    => '{{PLURAL:$1|1 färsjoon|$1 färsjoone}} widermååge',
'viewdeleted_short' => '$1 {{PLURAL:$1|iinj sträägen färsjoon|$1 sträägene färsjoone}} önjkiike',
'protect'           => 'Sääkere',
'protect_change'    => 'änre',
'protectthispage'   => 'Sid önj ferbading hüülje',
'unprotect'         => 'Frijeewe unti späre',
'unprotectthispage' => 'Sääkering aphääwe',
'newpage'           => 'Nai sid',
'talkpage'          => 'Jüdeer sid diskutiire',
'talkpagelinktext'  => 'diskusjoon',
'specialpage'       => 'Spetsjåålsid',
'personaltools'     => 'Persöönlike räischupe',
'postcomment'       => 'Nai oufsnaas',
'articlepage'       => 'Inhåltsid wise',
'talk'              => 'Diskusjoon',
'views'             => 'Önjsichte',
'toolbox'           => 'Räischape',
'userpage'          => 'Brükersid wise',
'projectpage'       => 'Prujäktsid wise',
'imagepage'         => 'Dååtäisid wise',
'mediawikipage'     => 'Mäldingssid wise',
'templatepage'      => 'Forlåågesid wise',
'viewhelppage'      => 'Heelpsid wise',
'categorypage'      => 'Kategoriisid wise',
'viewtalkpage'      => 'Diskusjoon',
'otherlanguages'    => 'Önj oudere spräke',
'redirectedfrom'    => '(Widerliidjet foon $1)',
'redirectpagesub'   => 'Widerliidjing',
'lastmodifiedat'    => 'Jüdeer sid wörd tuleest aw $1 am jü klook $2 änred.',
'viewcount'         => 'Aw jüdeer sid as  {{PLURAL:$1|iinjsen|$1 tunge}} tugram wörden.',
'protectedpage'     => 'Sääkerd sid',
'jumpto'            => 'Schaft tu:',
'jumptonavigation'  => 'Navigasjoon',
'jumptosearch'      => 'säk',
'view-pool-error'   => 'Önjschüliing, da särwere san nütutids ouerlååsted.
Tufoole brükere fersäke, jüdeer sid tu besäken.
Wees sü gödj än täiw hu minuute, iir dü dåt nuch iinjsen ferseechst.

$1',
'pool-timeout'      => "Tidj uflepen bi't teewen üüb't sperang",
'pool-queuefull'    => 'Pool as auerläästet',
'pool-errorunknown' => 'Ünbekäänd feeler',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ouer {{SITENAME}}',
'aboutpage'            => 'Project:Ouer',
'copyright'            => 'Inhålt stoont tu rädj uner jü $1.',
'copyrightpage'        => '{{ns:project}}:Uurhiiwerruchte',
'currentevents'        => 'Aktuäle schaiinge',
'currentevents-url'    => 'Project:Aktuäle schaiinge',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Beårbingsheelp',
'edithelppage'         => 'Help:Beårbe',
'helppage'             => 'Help:Inhåltsfertiiknis',
'mainpage'             => 'Wikipedia:Hoodsid',
'mainpage-description' => 'Hoodsid',
'policy-url'           => 'Project:Ruchtliinje',
'portal'               => 'Gemiinschaps-portåål',
'portal-url'           => 'Project:Gemiinschaps-portåål',
'privacy'              => 'Dootenschuts',
'privacypage'          => 'Project:Dootenschuts',

'badaccess'        => 'Niinj tulingende ruchte',
'badaccess-group0' => 'Dü hääst ai jü nüsie beruchtiging for jüdeer aksjoon',
'badaccess-groups' => 'Jüdeer aksjoon as begränsed aw brükere, da tu {{PLURAL:$2|di grupe|åån foon da grupe}} „$1“ hiire.',

'versionrequired'     => 'Färsjoon $1 foon MediaWiki as nüsi.',
'versionrequiredtext' => 'Färsjoon $1 foon MediaWiki as nüsi, am jüdeer sid tu brüken.
Sii jü [[Special:Version|Färsjoonssid]]',

'ok'                      => 'OK',
'pagetitle'               => '$1 – {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle'        => '← $1',
'retrievedfrom'           => 'Foon „$1“',
'youhavenewmessages'      => 'Dü hääst $1 aw din diskusjoonssid ($2).',
'newmessageslink'         => 'naie tisinge',
'newmessagesdifflink'     => 'Leest änring',
'youhavenewmessagesmulti' => 'Dü hääst nai tisinge aw $1',
'editsection'             => 'Beårbe',
'editsection-brackets'    => '[$1]',
'editold'                 => 'Beårbe',
'viewsourceold'           => 'kwältakst wise',
'editlink'                => 'beårbe',
'viewsourcelink'          => 'kwältakst wise',
'editsectionhint'         => 'Säksjoon beårbe: $1',
'toc'                     => 'Inhåltsfertiiknis',
'showtoc'                 => 'Wise',
'hidetoc'                 => 'Ferbärje',
'collapsible-collapse'    => 'Tuupdoble',
'collapsible-expand'      => 'Ütjenööderdoble',
'thisisdeleted'           => '$1 önjkiike unti widermååge?',
'viewdeleted'             => '$1 wise?',
'restorelink'             => '$1 {{PLURAL:$1|sträägen Färsjoon|sträägene Färsjoone}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Üngülti feed-abonemänt-typ.',
'feed-unavailable'        => 'Deer stönje niinj feeds tu rädj.',
'site-rss-feed'           => 'RSS-feed for $1',
'site-atom-feed'          => 'Atom-feed for $1',
'page-rss-feed'           => 'RSS-feed for „$1“',
'page-atom-feed'          => 'Atom-feed for „$1“',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (sid ai deer)',
'sort-descending'         => 'Sortiare faan boowen tu onern',
'sort-ascending'          => 'Sortiare faan onern tu boowen',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sid',
'nstab-user'      => 'Brükersid',
'nstab-media'     => 'Meediesid',
'nstab-special'   => 'Spetsjåålsid',
'nstab-project'   => 'Prujäktsid',
'nstab-image'     => 'Dååtäi',
'nstab-mediawiki' => 'Berucht',
'nstab-template'  => 'Forlååge',
'nstab-help'      => 'Heelpsid',
'nstab-category'  => 'Kategorii',

# Main script and global functions
'nosuchaction'      => "Ai sü'n aksjoon",
'nosuchactiontext'  => 'Jü aksjoon spesifisiird döör di URL jült ai.
Dü koost di URL ferkiird tipt hääwe, unti dü hääst en ferkiirden link fülied.
Dåt koon uk en fäägel önjjeewe önj e software, jü {{SITENAME}} brúkt.',
'nosuchspecialpage' => "Ai sü'n spetsjäl sid",
'nospecialpagetext' => '<strong>Jü önjfrååged spetsjåålsid as ai deer.</strong>

Åle ferfäigboore spetsjåålside san önj e [[Special:SpecialPages|List foon da spetsjåålside]] tu finen.',

# General errors
'error'                => 'Fäägel',
'databaseerror'        => 'Dootebånkfäägel',
'dberrortext'          => 'Deer as en dootebånk-fäägel aptrin.
Di grün koon en programiirfäägel weese.
Jü leest dootebånk ouffrååg wus:
<blockquote><tt>$1</tt></blockquote>
üt jü funksjoon „<tt>$2</tt>“.
Jü dootebank mäldede di fäägel „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Dåt jäif en süntaksfäägel önj e dootebånk-ouffrååch.
Jü leest dootebånkouffrååch wus  „$1“ üt e funksjoon „<tt>$2</tt>“.
Jü dootebånk mälded jü fäägel: „<tt>$3: $4</tt>“.',
'laggedslavemode'      => "''''Woarschauing:''' Jü wised sid köö uner amstånde ai da leeste beåarbinge önjthüülje.",
'readonly'             => 'Dootebånk späred',
'enterlockreason'      => 'Wees swü gödj än jeew en grün önj, weeram jü dootebånk späred wårde schal än en schåting ouer e duur foon jü späre',
'readonlytext'         => 'Jü dootebånk as forluupi späred for naie önjdreege än änringe. Wees sü gödj än fersäk dåt lääser nuch iinjsen.

Grün foon e späre: $1',
'missing-article'      => 'Di täkst for „$1“ $2 wörd ai önj e dååtenbånk fünen.

Jü sid as möölikerwise tuninte mååged unti ferschääwen wörden.

Wan dåt ai di fål as, hääst dü eewäntuäl en fäägel önj e software fünen. Mäld dåt hål en  [[Special:ListUsers/sysop|administrator]] unner nååming foon jü URL.',
'missingarticle-rev'   => '(Färsjoonsnumer: $1)',
'missingarticle-diff'  => '(Ferschääl twasche färsjoone: $1, $2)',
'readonly_lag'         => 'Jü dootebånk wörd automaatisch for schriwtugraawe späred, deerma da ferdiiljde dootebånke (slaves) jam ma di hooddootebånksärwer (master) oufglike koone.',
'internalerror'        => 'Intärn fäägel',
'internalerror_info'   => 'Intärne fäägel: $1',
'fileappenderrorread'  => '"$1" köö wilert dåt baitufäigen ai leesen wårde.',
'fileappenderror'      => 'Köö ai "$1" tu "$2" önjhinge.',
'filecopyerror'        => 'Jü dootäi "$1" köö ai eefter "$2" kopiiird wårde.',
'filerenameerror'      => 'Jü dootäi "$1" köö ai eefter "$2" amnååmd wårde.',
'filedeleteerror'      => 'Jü dootäi "$1" köö ai straagen wårde.',
'directorycreateerror' => 'Dåt fertiiknis "$1" köö ai mååged wårde.',
'filenotfound'         => 'Köö dootäi "$1" ai fine.',
'fileexistserror'      => 'Ai möölik, eefter dootäi "$1" tu schriwen: dootäi bestoont ål',
'unexpected'           => 'Ünfermousene wjart: "$1"="$2".',
'formerror'            => 'Fäägel: köö jü form ai lääwere',
'badarticleerror'      => 'Jüdeer aksjoon koon ai aw jüdeer sid mååged wårde.',
'cannotdelete'         => 'Jü sid unti dootäi "$1" köö ai straagen wårde.
Dåt as flicht ål straagen foon huum ouders.',
'cannotdelete-title'   => 'Sidj „$1“ koon ei stregen wurd.',
'badtitle'             => 'Ferkiirde tiitel',
'badtitletext'         => 'Di tiitel foon jü anfrååged sid as üngülti, lääsi unti n üngültigen spräklink foon en ouder wiki.',
'perfcached'           => 'Jodiar dooten kem faan a cache an san ferlicht ei muar aktuel. Huuchstens {{PLURAL:$1|ian resultoot as|$1 resultooten san}} uun a cache.',
'perfcachedts'         => 'Jodiar dooten kem faan a cache, leetst tooch nei: $1. Huuchstens {{PLURAL:$4|ian resultoot as|$4 resultooten san}} uun a cache.',
'querypage-no-updates' => "'''Jü aktualisiiringsfunksjoon for jüdeer sid as nütutids deaktiwiird. Da doote wårde tujarst ai fernaierd.'''",
'wrong_wfQuery_params' => 'Ferkiirde parameetere for wfQuery()<br />
Funksjoon: $1<br />
Ouffrååch: $2',
'viewsource'           => 'Kwältäkst önjkiike',
'viewsource-title'     => 'Code faan sidj $1 uunluke',
'actionthrottled'      => 'Aksjoonstål limitiird',
'actionthrottledtext'  => 'Dü hääst jüdeer aksjoon tu oofding bane en kort tidrüm ütfjard. Wees sü gödj än täiw en påår minuute än fersäk dåt et dan foon naien.',
'protectedpagetext'    => 'Jüdeer sid as for dåt beårben späred.',
'viewsourcetext'       => 'Dü koost jü kwäle foon jüdeer sid bekiike än kopiire.',
'viewyourtext'         => "Dü könst di code faan '''din feranrang''' faan detdiar sidj uunluke an kopiare:",
'protectedinterface'   => 'Jüdeer sid önjthålt takst for ju brükerouerfläche foon e software än as späred, am masbrük tu ferhanern.',
'editinginterface'     => "'''Paase üüb:''' Üüb detdiar sidj stäänt tekst, diar faan't MediaWiki software brükt woort. Wan dü diar wat feranerst, feranerst dü di skak faan't Nuurdfresk Wikipedia.
Wan dü wat auersaat wel, maage det mä [//translatewiki.net/wiki/Main_Page?setlang=frr Translatewiki], det as det MediaWiki lokalisiarangsprojekt.",
'sqlhidden'            => 'SCL-ouffrååg ferstäägen',
'cascadeprotected'     => 'Jüdeer sid as for beårbing spärd. Jü as önj {{PLURAL:$1|e füliende sid|da füliende side}}
önjbünen, {{PLURAL:$1|jü|da}} madels e kaskaadespäropsjoon önj febading hüüljen {{PLURAL:$1|as|san}}:
$2',
'namespaceprotected'   => "Dü hääst niinj beruchtiging, jü sid önj di '''$1'''-noomerüm tu beårben.",
'customcssprotected'   => 'Dü mutst detheer CSS sidj ei bewerke, auer det hoker ööders hiart.',
'customjsprotected'    => 'Dü mutst detheer JavaScript sidj ei bewerke, auer det hoker ööders hiart.',
'ns-specialprotected'  => 'Spetsjåålside koone ai beårbed wårde.',
'titleprotected'       => 'En sid ma dideer noome koon ai önjläid wårde.
Jü späre wörd döör [[User:$1|$1]] ma grün "$2" inruchted.',

# Virus scanner
'virus-badscanner'     => "Hiinje konfigurasjoon: ünbekånde fiirusscanner: ''$1''",
'virus-scanfailed'     => 'scan fäägelsloin (code $1)',
'virus-unknownscanner' => 'Ünbekånde fiirusscanner:',

# Login and logout pages
'logouttext'                 => "'''Dü bast nü oufmälded.'''

Dü koost {{SITENAME}} nü anonüüm widerbrüke, unti de wider uner diseelew unti en oudern brükernoome [[Special:UserLogin|önjmälde]].
Påås aw, dåt hu side nuch wise koone, dåt dü önjmälded bast, sülung dü ai dan browsercache lääsimååged heest.",
'welcomecreation'            => '== Wäljkiimen, $1! ==

Din brükerkonto wörd inruchted.
Ferjees ai, din [[Special:Preferences|{{SITENAME}}-önjstalinge]] önjtupååsen.',
'yourname'                   => 'Brükernoome:',
'yourpassword'               => 'Pååsuurd:',
'yourpasswordagain'          => 'Schriw pååsuurd nuch iinjsen:',
'remembermypassword'         => 'Aw diheere komputer foon duur önjmälde (maksimool for {{PLURAL:$1|däi|deege}})',
'securelogin-stick-https'    => 'Eefter önjmälding ma HTTPS ferbünen bliwe',
'yourdomainname'             => 'Din domain:',
'externaldberror'            => 'Deer läit en fäägel bai jü äkstärn autentifisiiring for, unti dü möist din äkstärn brükerkonto äi aktualisiire.',
'login'                      => 'Önjmälde',
'nav-login-createaccount'    => 'Önjmälde',
'loginprompt'                => 'For jü önjmälding monje cookies aktiwiird weese.',
'userlogin'                  => 'Önjmälde / brükerkonte mååge',
'userloginnocreate'          => 'Önjmälde',
'logout'                     => 'Oufmälde',
'userlogout'                 => 'Oufmälde',
'notloggedin'                => 'Ai önjmälded',
'nologin'                    => "Dü hääst niinj brükerkonto? '''$1'''.",
'nologinlink'                => 'Nai brükerkonto inruchte',
'createaccount'              => 'Brükerkonto inruchte',
'gotaccount'                 => "Dü hääst ål en brükerkonto? '''$1'''.",
'gotaccountlink'             => 'Önjmälde',
'userlogin-resetlink'        => 'Heest dü din login dooten ferjiden?',
'createaccountmail'          => 'ouer E-mail',
'createaccountreason'        => 'Grün:',
'badretype'                  => 'Da biise pååsuurde stime ai oueriinj.',
'userexists'                 => 'Dideer brükernoome as ål ferjääwen.
Wees sü gödj en kiis en ouderen.',
'loginerror'                 => 'Fäägel bai önjmälding',
'createaccounterror'         => 'Brükerkonto köö ai mååged wårde: $1',
'nocookiesnew'               => 'Di benjütertugung wörd mååged, ouers dü bast ai önjmälded. {{SITENAME}} brükt for jüdeer funksjoon cookies.
Wees sü gödj än aktiwiir da än mäld de dan ma dan naien benjüternoome än dåt tuhiirend pååsuurd önj.',
'nocookieslogin'             => '{{SITENAME}} benjütet cookies tu e önjmälding foon da benjütere. Dü heest Cookis deaktiwiird.
Wees sü gödj än aktiwiir da än fersäk dåt wider.',
'nocookiesfornew'            => 'Det brükerkonto as ei iinracht wurden, auer wi ei witj, huar a dooten faan kem.
Üüb dan kompjuuter skel cookies aktiwiaret wees. Do rep detheer sidj noch ans nei ap.',
'noname'                     => 'Dü möist en gültigen brükernooem önjjeewe.',
'loginsuccesstitle'          => 'Önjmälding erfolchrik',
'loginsuccess'               => "'''Dü bast nü ås „$1“ bai {{SITENAME}} önjmälded.'''",
'nosuchuser'                 => 'Di brükernoome „$1“ bestoont ai.
Präiw jü schriwwise (grut-/latjschriwing beåchte) unti [[Special:UserLogin/signup|mäld de ås naie brüker önj]].',
'nosuchusershort'            => 'Deer as nåån brüker ma noome  "$1".
Präiw din ruchtschriwing.',
'nouserspecified'            => 'Dü schäät en brükernoome spesifisiire.',
'login-userblocked'          => 'Dideer brüker as spärd. Niinj ferloof tu önjmälding.',
'wrongpassword'              => 'Ferkiird pååsuurd önjjeewen.
Wees sü gödje än fersäk dåt nuch iinjsen.',
'wrongpasswordempty'         => 'Deer wörd niinj pååsuurd önjjääwen. Fersäk dåt foon naien.',
'passwordtooshort'           => 'Pååsuurde mönje tu t manst {{PLURAL:$1|1 tiiken|$1 tiikne}} lung weese.',
'password-name-match'        => 'Din pååsuurd mätj ferschääle foon dan brükernoome.',
'password-login-forbidden'   => 'Jüdeer brükernoome än paasuurd as ferbin.',
'mailmypassword'             => 'Nai pååsuurd tusiinje',
'passwordremindertitle'      => 'Nai tidwise pååsuurd for {{SITENAME}}',
'passwordremindertext'       => 'En brüker (woorskiinelk dü, faan IP adres $1) hää am en nei paaswurd för {{SITENAME}} ($4) fraaget.
En nei paaswurd för di brüker "$2" as maaget wurden an het nü "$3".

Wan dü det würelk so haa wel, do melde di nü uun an feranere det paaswurd. Det nei paaswurd täält för {{PLURAL:$5|ään dai|$5 daar}}.

Wan dü ei salew am en nei paaswurd fraaget heest, do säärst dü di am niks widjer komre. Do könst dü din ual paaswurd widjer brük.',
'noemail'                    => 'Deer as niinj E-mail-adräs bekånd for brüker "$1".',
'noemailcreate'              => 'Dü möist en gülti E-mail-adräs önjjeewe',
'passwordsent'               => 'En nai pååsuurd as sånd tu jü E-mail-adräs registriird for "$1".
Mälde wi önj eefter dü jü füngen heest.',
'blocked-mailpassword'       => 'Jü foon de ferwånde IP-adräs as for dåt änren foon side späred. Am en masbrük tu ferhanern, wórd jü möölikhäid tu dåt önjfråågen foon en nai pååsuurd uk späred.',
'eauthentsent'               => 'En bestääsiings-E-mail wörd önj jü önjjääwen adräs sånd.

Iir en E-mail foon oudere brükere ouer jü E-mail-funksjoon emfångd wårde koon, mötj jü adräs än har wörklike tuhiirihäid tu dåtheer brükerkonto jarst bestääsied wårde. Wees sü gödj än befülie da haanewisinge önj di bestääsiings-E-mail.',
'throttled-mailpassword'     => 'Deer wörd önj da leeste {{PLURAL:$1|stün|$1 stüne}} ål en nai pååsuurd önjfrååged. Am en misbrük foon jüdeer funksjoon tu ferhanren, koon bloot {{PLURAL:$1|iinjsen pro stün|åle $1 stüne}} en nai pååsuurd önjfrååged wårde.',
'mailerror'                  => 'Fäägel bai dåt siinjen foon e E-mail: $1',
'acct_creation_throttle_hit' => 'Besäkere foon j"heer Wiki, da din IP-adräse brüke, heewe önj e leeste däi {{PLURAL:$1|1 benutserkonto|$1 benutzerkonte}} mååged, wat jü maksimool tuleet tål önj jüdeer tidperioode as.

Besäkere, da iüheer IP-adräse brüke, koone tu jü tutids niinj benutserkonte mör mrstellen.',
'emailauthenticated'         => 'Din E-mail-adräs word di $2 am e klook $3 bestääsied.',
'emailnotauthenticated'      => 'Din E-mail-adräs as nuch ai bestääsied. Da füliende E-mail-funksjoone stönje jarst eefter erfolchrike bestääsiing tu ferfäiging.',
'noemailprefs'               => 'Jeew en E-mail-adräs önj da önjstalinge önj, deerma da füliende funksjoone tu ferfäiging stönje.',
'emailconfirmlink'           => 'E-mail-adräs bestääsie (autäntifisiire).',
'invalidemailaddress'        => 'Jü E-mail adräs wörd ai aksäptiird, ouerdåt jü en üngülti formoot (ewentuäl üngültie tiikne) tu heewen scheent.
Wees sü gödj än jeef en koräkt adräs önj unti mäág dåt fäalj lääsi.',
'cannotchangeemail'          => 'E-mail-adresen kön uun detheer wiki ei feranert wurd.',
'accountcreated'             => 'Benjüterkonto mååged',
'accountcreatedtext'         => 'Dåt benjüteraccount for $1 as mååged wörden.',
'createaccount-title'        => 'Måågen foon en benjüterkonto for {{SITENAME}}',
'createaccount-text'         => 'Deer wörd for de en benjüterkonto "$2" aw {{SITENAME}} ($4) mååged. Dåt automaatisch generiird pååsuurd for "$2" as "$3".
Dü schöist de nü önjmälde än dåt pååsuurd änre.

Fåls dåt benjüterkonto üt fersiinj önjläid wörd, koost dü jüdeer tising ignoriire.',
'usernamehasherror'          => 'Benjüternoome mötje niinj rütetiikne önjthüulje',
'login-throttled'            => 'Dü heest tu oofding fersoocht, di önjtumälden.
Wees sü gödj än täif, bit dü wider ferseechst.',
'login-abort-generic'        => 'Din önjmälding wus ei erfolchrik - Oufbräägen',
'loginlanguagelabel'         => 'Spräke: $1',
'suspicious-userlogout'      => 'Dan Oufmäldönjfrååge wörd ferwaigred, deer ja fermouslik foon en defäkte browser unti en cache-proxy sånd wörd.',

# E-mail sending
'php-mail-error-unknown' => 'Ünbekäänd feeler mä det funktsjuun mail() faan PHP.',
'user-mail-no-addy'      => 'Köö niinj e-mail schake suner e-mail-adres.',

# Change password dialog
'resetpass'                 => 'Pååsuurd änre',
'resetpass_announce'        => 'Önjmälding ma di ouer E-mail tusånde kode. Am e önjmälding ouftusliten, möist dü en nai pååsuurd kiise.',
'resetpass_header'          => 'Account pååsuurd änre',
'oldpassword'               => 'Üülj pååsuurd:',
'newpassword'               => 'Nai pååsuurd:',
'retypenew'                 => 'Tip nai pååsuurd nuch iinjsen:',
'resetpass_submit'          => 'Seet pååsuurd än mäld önj',
'resetpass_success'         => 'Din pååsuurd as ma resultoot änred!
Nü wårst dü önjmälded...',
'resetpass_forbidden'       => 'Pååsuurde koone ai änred wårde',
'resetpass-no-info'         => 'Dü möist önjmälded weese am ju sid diräkt tu tu gripen.',
'resetpass-submit-loggedin' => 'Pååsuurd änre',
'resetpass-submit-cancel'   => 'Oufbreege',
'resetpass-wrong-oldpass'   => 'Üngülti tämporäär unti antuäl pååsuurd.
Möölikerwise heest dü din pååsuurd ål ma erfolch änred heest unti en nai tämporäär pååsuurd beönjdräägen.',
'resetpass-temp-password'   => 'Tidwise pååsuurd:',

# Special:PasswordReset
'passwordreset'                    => 'Paasuurd tubääg seete',
'passwordreset-text'               => 'Fal detheer formulaar ütj, an do woort di en e-mail tusjüürd mä henwiiser tu din brükerkonto-uunmeldang.',
'passwordreset-legend'             => 'Paasuurd tubääg seete',
'passwordreset-disabled'           => 'Dü koost din paasuurd aw jüdeer wiki ai tubääg seete',
'passwordreset-pretext'            => '{{PLURAL:$1||Du ian faan jo dooten oner iin}}',
'passwordreset-username'           => 'Brükernoome:',
'passwordreset-domain'             => 'Domain:',
'passwordreset-capture'            => 'Wel dü det e-mail nooracht uunluke?',
'passwordreset-capture-help'       => 'Wan dü detheer kasje uunkrüsagst, woort det e-mail nooracht mä det nei paaswurd uunwiset an tu di brüker sjüürd.',
'passwordreset-email'              => 'E-mail adres:',
'passwordreset-emailtitle'         => 'Brükerkonto aw {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'Hoker mä det IP-Adres $1, woorskiinelk dü salew, wul hal brükerinformatsjuunen för {{SITENAME}} tusjüürd fu ($4). {{PLURAL:$3|Detdiar brükerkonto as|Jodiar brükerkontos san}} mä detdiar E-Mail-adres ferbünjen:

$2

{{PLURAL:$3|Detheer tidjwis paaswurd lääpt|Joheer tidjwis paaswurden luup}} efter {{PLURAL:$5|ään dai|$5 daar}} uf. 
Dü skulst di uunmelde an en nei paaswurd iinracht. Wan hoker ööders detheer uunfraag steld hää an dü din ual paaswurd käänst, do säärst dü niks widjer onernem. Melde di ianfach widjerhen mä din ual paaswurd uun.',
'passwordreset-emailtext-user'     => 'Di brüker $1 üüb {{SITENAME}} hää am brükerinformatsjuunen för {{SITENAME}} uunfraaget ($4). {{PLURAL:$3|Detdiar brükerkonto as|Jodiar brükerkontos san}} mä detdiar E-Mail-Adres ferbünjen:

$2

{{PLURAL:$3|Detheer tidjwis paaswurd lääpt|Joheer tidjwis paaswurden luup}} efter {{PLURAL:$5|ään dai|$5 daar}} uf. Dü skulst di uunmelde an en nei paaswurd iinracht. Wan hoker ööders detheer uunfraag steld hää of dü din ual paaswurd käänst, säärst dü niks widjer onernem. Melde di ianfach mä din ual paaswurd uun.',
'passwordreset-emailelement'       => 'Brükernoome: $1
Tidwis paasuurd: $2',
'passwordreset-emailsent'          => 'Diar as en E-Mail tu di onerwais.',
'passwordreset-emailsent-capture'  => 'Detdiar E-Mail, wat oner uunwiset woort, as tu di onerwais.',
'passwordreset-emailerror-capture' => 'Detdiar E-Mail, wat oner uunwiset woort, wiar tu di onerwais, oober küd ei ufsjüürd wurd: $1',

# Special:ChangeEmail
'changeemail'          => 'Feranre det E-Mail-adres',
'changeemail-header'   => 'Feranre det E-Mail-adres',
'changeemail-text'     => 'Fal detdiar formulaar hialandaal ütj, am din E-Mail-adres tu feranrin. Diarför skel dü din paaswurd uundu.',
'changeemail-no-info'  => 'Dü möist önjmälded weese am ju sid diräkt tu tu gripen.',
'changeemail-oldemail' => 'Aktuel e-mail adres',
'changeemail-newemail' => 'Nei e-mail adres',
'changeemail-none'     => '(niin)',
'changeemail-submit'   => 'E-mail adres feranre',
'changeemail-cancel'   => 'Ufbreeg',

# Edit page toolbar
'bold_sample'     => 'Fåten täkst',
'bold_tip'        => 'Fåten täkst',
'italic_sample'   => 'Kursiiwen täkst',
'italic_tip'      => 'Kursiiwen täkst',
'link_sample'     => 'Link-täkst',
'link_tip'        => 'Intärnen link',
'extlink_sample'  => 'http://www.example.com link-täkst',
'extlink_tip'     => 'Äkstärnen link (http:// beåchte)',
'headline_sample' => 'Schuchte 2 ouerschraft',
'headline_tip'    => 'Schuchte 2 ouerschraft',
'nowiki_sample'   => 'Ünformatiirden täkst heer önjfäige',
'nowiki_tip'      => 'Ünformatiirden täkst',
'image_tip'       => 'Dååtäilink',
'media_tip'       => 'Meediendååtäi-link',
'sig_tip'         => 'Din signatuur ma tidståmp',
'hr_tip'          => 'Horizontool liinje (spårsoom ferwiinje)',

# Edit pages
'summary'                          => 'Tuhuupefooting:',
'subject'                          => 'Bedrååwet:',
'minoredit'                        => 'Bloot kleenihäide wörden feränred',
'watchthis'                        => 'Kiike eefter jüdeer sid',
'savearticle'                      => 'Sidj seekre',
'preview'                          => 'Iarst ans luke',
'showpreview'                      => 'Iarst ans luke',
'showlivepreview'                  => 'Glik uunluke',
'showdiff'                         => 'Feranrangen wise',
'anoneditwarning'                  => "Dü beårbest jüdeer sid ünönjmälded. Wan dü spikerst, wård din aktuäle IP-adräs önj e fesjoonshistoori aptiikned än as deerma for åltens '''ålgemiin''' sichtboor.",
'anonpreviewwarning'               => "''Dü bast ai önjmälded. Bai t spiikern wårt din IP-adräs önj e fersjoonshistoori awtiikned.''",
'missingsummary'                   => "'''Haanewising:\"' Dü heest niinj tuhuupefooting önjjääwen.
Wan dü wider aw \"Sid spiikre\" klikst, wårt din änring suner tuhuupefooting ouernümen.",
'missingcommenttext'               => 'Jeew en tuhuupefooting önj.',
'missingcommentheader'             => "'''PÅÅS AW:''' dü heest niinj keer/ouerschraft önjjääwen.
Wan dü wider aw \"{{int:savearticle}}\" klakst, wårt din beårbing suner ouerschaft spiikerd.",
'summary-preview'                  => 'Forlök foon jü tuhuupfootingssid:',
'subject-preview'                  => 'Forkiik foon dåt subjäkt:',
'blockedtitle'                     => 'Brüker as späred',
'blockedtext'                      => "'''Dan brükernööm of IP adres as speret wurden.'''

Det as maaget wurden faan $1.
Di grünj as ''$2''.

* Began: $8
* Aanj: $6
* Bedraapt: $7

Dü könst $1 kontaktiare of uk en [[{{MediaWiki:Grouppage-sysop}}|administraator]] am det tu diskutiarin.

Dü könst ei det E-Mail-funktsjuun 'E-mail tu dideere brüker' brük, so loong dü nian E-Mail-adres uun din [[Special:Preferences|brükerkonto iinstelangen]] uunden heest of wan det E-Mail-funktsjuun för di speret wurden as.

Uugenblakelk as din IP addres $3, an det sper ID as #$5.
För arke uunfraag wurd aal jo informatsjuunen boowen brükt.",
'autoblockedtext'                  => "'''Din IP adres as speret wurden, auer det faan en öödern spereten brüker brükt wurden as.'''

Di grünj as:
: ''$2''.

* Began: $8
* Aanj: $6
* Bedraapt: $7

Dü könst $1 kontaktiare of uk en [[{{MediaWiki:Grouppage-sysop}}|administraator]] am det tu diskutiarin.

Dü könst ei det E-Mail-funktsjuun 'E-mail tu dideere brüker' brük, so loong dü nian E-Mail-adres uun din [[Special:Preferences|brükerkonto iinstelangen]] uunden heest of wan det E-Mail-funktsjuun för di speret wurden as.

Uugenblakelk as din IP addres $3, an det sper ID as #$5.
För arke uunfraag wurd aal jo informatsjuunen boowen brükt.",
'blockednoreason'                  => 'niinj grün önjjääwen',
'whitelistedittext'                => 'Dü möist de $1, am side beårbe tu koonen.',
'confirmedittext'                  => 'Dü möist din E-mail-adräs jarst bestääsie, iir dü beårbinge döörfääre koost. Mååg din årbe radi än bestääsie din E-mail önj da  [[Special:Preferences|önjstalinge]].',
'nosuchsectiontitle'               => 'Stuk ai fünen',
'nosuchsectiontext'                => 'Dü fersoochst en stuk tu änren, dåt dåt ai jeeft.
Dåt koon ferschääwen unti wächhååld weese, wilt dü jü sid bekiikedest.',
'loginreqtitle'                    => 'Önjmälden nüsi.',
'loginreqlink'                     => 'Önjmälde',
'loginreqpagetext'                 => 'Dü möist $1 am oudere side tu bekiiken.',
'accmailtitle'                     => 'Pååsuurd sånd.',
'accmailtext'                      => "En tufäli generiird pååsuurd for [[User talk:$1|$1]] wörd tu $2 fersånd.

Dåt pååsuurd for jüdeer nai benjüterkonto koon aw e spetsjoolsid ''[[Special:ChangePassword|Pååsuurd änre]]'' änred wårde.",
'newarticle'                       => '(Nai)',
'newarticletext'                   => "Dü bast en link tu en sid fülied, jü nuch ai bestoont.
Am jü sid tu måågen, dreeg dan täkst önj e unerstönjene box in (sii jü
[[{{MediaWiki:Helppage}}|heelpsid]] for mör informasjoon).
Bast üt fersiien heer, klik di '''tubääg'''-klänkfläche foon dan browser.",
'anontalkpagetext'                 => "----''Üüb detheer sidj könst dü en ünbekäänden brüker en nooracht du. Det lääpt auer sin IP adres. IP adresen kön faan flook brükern brükt wurd. Wan dü mä detheer nooracht niks began könst, do as det ferlicht för hoker ööders mend weesen. Dü säärst niks widjer onernem. Wan dü en aanj [[Special:UserLogin/signup|brükerkonto iinrachst]] of di [[Special:UserLogin|uunmeldest]], komt sowat ei weder föör.",
'noarticletext'                    => 'Jüdeer sid önjhålt uugenblaklik nuch nån täkst.
Dü koost dideere tiitel aw da ouder side [[Special:Search/{{PAGENAME}}|säke]],
<span class="plainlinks">önj da deertuhiirende [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} logböke säke] unti jüdeer sid [{{fullurl:{{FULLPAGENAME}}|action=edit}} beårbe]</span>.',
'noarticletext-nopermission'       => 'Jüdeer sid önjhålt uugenblaklik nuch nån täkst.
Dü koost dideere tiitel aw da oudre side [[Special:Search/{{PAGENAME}}|säke]],
unti<span class="plainlinks">önj da deertuhiirende [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} logböke säke] </span>.',
'userpage-userdoesnotexist'        => "Det brükerkonto ''$1'' as ei diar.
Wel dü detdiar sidj würelk maage/bewerke?",
'userpage-userdoesnotexist-view'   => 'Benjüterkonto "$1" bestoont ai.',
'blocked-notice-logextract'        => 'Dideer benjüter as tutids spärd.
For informasjoon füliet di leeste üttooch üt dåt benjüterspär-logbök:',
'clearyourcache'                   => "'''Beaachte:''' Maage di cache faan dan browser leesag, wan dü a feranrangen sä wel.
* '''Firefox / Safari:''' Hual ''Shift'' bi't aktualisiarin, of trak ''Strg an F5'' of ''Strg an R'' (''⌘an R'' üüb en Mac)
* '''Google Chrome:''' Trak ''Strg an Shift an R'' (''⌘an Shift an R'' üüb en Mac)
* '''Internet Explorer:''' Hual ''Strg'' bi't aktualisiarin, of trak ''Strg an F5''
* '''Konqueror:''' Trak ''Aktualisiarin'' of trak ''F5''
* '''Opera:''' ''Extras - Internetspuren löschen - Individuelle Auswahl - Den kompletten Cache löschen''",
'usercssyoucanpreview'             => "'''Tip:''' Brük di „{{int:showpreview}}“-knoop, am din nai CSS for dåt spiikern tu tästen.",
'userjsyoucanpreview'              => "'''Tip:''' Brük di „{{int:showpreview}}“-knoop, am din nai JavaScript for dåt spiikern tu tästen.",
'usercsspreview'                   => "'''Påås aw dåt dü bloot din brüker CSS forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'userjspreview'                    => "'''Påås aw dåt dü bloot din brüker JavaScript präiwest/forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'sitecsspreview'                   => "'''Påås aw dåt dü jüdeer CSS bloot forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'sitejspreview'                    => "'''Påås aw dåt dü jüdeer JavaScript code bloot forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'userinvalidcssjstitle'            => "''Woorschauing:''' Skin \"\$1\"jeeft dåt ai. Betånk, dåt brükerspetsiifische .css- än .js-side ma en latj bökstääw önjfånge mönje, ålsü biispelswise ''{{ns:user}}:Münsterkjarl/vector.css'' önj stää foon ''{{ns:user}}:Münsterkjarl/Vector.css''.",
'updated'                          => '(Änred)',
'note'                             => "'''Påås aw:'''",
'previewnote'                      => "'''Heer könst dü sä, hü det sidj wurd skal.'''
Det sidj as oober noch ei seekert!",
'previewconflict'                  => 'Dideer forbekiik jeeft di inhålt foon dåt boowerst takstfälj wider. Sü wårt jü sid ütsiinj, wan dü nü spiikerst.',
'session_fail_preview'             => "'''Din werk küd ei ufseekert wurd, diar as wat skiaf gingen.'''
Fersjük det man noch ans an trak do üüb ''Sid spiikre''.
Wan't do imer noch ei loket, [[Special:UserLogout|melde di uf]] an weder uun.",
'session_fail_preview_html'        => "'''Din werk küd ei seekert wurd. Diar as wat skiaf gingen.'''

''Uun {{SITENAME}} as HTML aktiwiaret, an diaram as JavaScript deaktiwiaret wurden.''

Fersjük det man noch ans an trak do üüb ''Sid spiikre''.
Wan't do imer noch ei loket, [[Special:UserLogout|melde di uf]] an weder uun.",
'token_suffix_mismatch'            => "'''Din werk küd ei ufseekert wurd, auer diar frääm tiaken uun san.'''

Det komt flooksis föör, wan Dan anonym Proxy-siinst ei rocht werket.",
'edit_form_incomplete'             => "'''Enkelt dialen faan det formulaar san ei rocht uunkimen.'''
Wees so gud an kontroliare ales noch ans.",
'editing'                          => 'Beårbe foon $1',
'editingsection'                   => 'Beårben foon $1 (oufsnaas)',
'editingcomment'                   => 'Beårben foon $1 (naien oufsnaas)',
'editconflict'                     => 'Beårbingskonflikt: $1',
'explainconflict'                  => "Hoker ööders hää detheer sidj feranert, üs dü jüst diarmä uun a gang wiarst.
Boowen könst dü di aktuel stant sä. Oner stun din fernanrangen.
Bluas wat '''boowen''' stäänt, woort seekert. Diaram kopiare din feranrangen boowen iin.
An do trak „{{int:savearticle}}“.",
'yourtext'                         => 'Din täkst',
'storedversion'                    => 'Spiikerd färsjoon',
'nonunicodebrowser'                => "'''Påås aw:''' Dan browser koon unicode-tiikne ai rucht ferårbe. Brük hål en oudern browser am side tu ferårben.",
'editingold'                       => "'''PÅÅS AW: Dü beårbest en üülj färsjoon foon jüdeer sid. \"
Wan dü spiikerst, wårde åle naiere färsjoone ouerschraawen.",
'yourdiff'                         => 'Ferschääle',
'copyrightwarning'                 => "''' Hål kopiir niinj webside, da ai din äine san, brük niinj uurhääwerruchtlik schütsede wärke suner ferloof foon di uurhääwer!'''<br />
Dü jeefst üs heerma dan tusååge, dåt dü di täkst '''seelew ferfooted''' hääst, dåt di täkst ålgemiingödj '''(public domain)''' as, unti dåt di '''uurhääwer''' sin '''tustiming''' jääwen heet. For di fål jüdeer täkst ål ouersweer ütdänj wörd, wis hål aw jü diskusjoonssid deeraw haane. <i>Beåcht hål, dåt åle {{SITENAME}}-tujeefte automaatisch uner jü „$2“ stönje (sii $1 for detaile). For di fål dü ai mååst, dåt diin årbe heer foon oudere feränred än språåt wårt, dan kröög ai aw „sid spikre“.</i>",
'copyrightwarning2'                => "Seenk diaram, dat det sidj {{SITENAME}} faan öödern bewerket, feranert of uk stregen wurd koon. Wan dü det ei wel, do skriiw heer niks iin! 

Wan dü heer wat iinskrafst, do beest dü diarmä iinferstenen an seekerst tu, dat dü det '''salew skrewen''' heest of faan en steed auernimen heest, huar '''nian rochten''' üüb lei. (Luke bi $1, wan dü muar wed wel.)

'''Auerdreeg nään frääm teksten an bilen saner ferloof!'''",
'longpageerror'                    => "'''Error: Dan tekst as {{PLURAL:$1|ian kilobyte|$1 kilobytes}} lung, hi mut oober ei linger wees üs {{PLURAL:$2|ian kilobyte|$2 kilobytes}}.'''Hi koon ei ufspiikerd wurd.",
'readonlywarning'                  => "'''PÅÅS AW: Jü dootenbånk wörd for unerhult spärd, sü dåt din änringe tutids ai spiikerd wårde koone.
Wees sü gödj än sääkre di täkst lokool aw din kompjuuter än fersäk tu n lääsern tidpunkt, da änringe tu ouerdreegen.'''.

Grün for jü späre: $1",
'protectedpagewarning'             => "'''Paase üüb: Detdiar sidj as speret wurden. Bluas administratooren kön det bewerke.'''
Uun't logbuk stäänt muar diartu:",
'semiprotectedpagewarning'         => "'''Paase üüb: Detdiar sidj as dialwiis tu't bewerkin speret wurden. Bluas gudkäänd brükern kön det bewerke.'''
Uun't logbuk stäänt muar diartu:",
'cascadeprotectedwarning'          => "'''Paase üüb:''' Detdiar sidj koon bluas faan administratooren bewerket wurd. Hat as uun {{PLURAL:$1|detdiar ööder sidj|jodiar ööder sidjen}} iinbünjen, diar troch en kaskaaden-optsjuun seekert {{PLURAL:$1|as|san}}:",
'titleprotectedwarning'            => "'''Paase üüb: \"Detdiar sidj mä didiar nööm koon ei faan arken bewerket wurd. Bluas enkelt brükern mä [[Special:ListGroupRights|was brükerrochten]] kön detdiar sidj nei maage of bewerke.'''
Uun't logbuk stäänt muar diartu:",
'templatesused'                    => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon jüdeer sid ferwånd:',
'templatesusedpreview'             => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon diheere sideforlök ferwånd:',
'templatesusedsection'             => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon dideer oufsnaas ferwånd:',
'template-protected'               => '(seekert)',
'template-semiprotected'           => '(hualew-seekert)',
'hiddencategories'                 => 'Jüdeer sid as lasmoot foon {{PLURAL:$1|1 ferstäägen kategorii|$1 ferstäägene kategoriie}}:',
'nocreatetitle'                    => 'Det maagin faan nei sidjen as ei saner aanj mögelk.',
'nocreatetext'                     => 'Aw {{SITENAME}} wörd dåt måågen foon naie side begränsed.
Dü koost bestönjene side änre unti de [[Special:UserLogin|önjmälde unti mååg en account]].',
'nocreate-loggedin'                => 'Dü heest niinj beruchtiging, naie side tu måågen.',
'sectioneditnotsupported-title'    => 'Jü beårbing foon oufsnaase wårt ai unerstüted',
'sectioneditnotsupported-text'     => 'Jü beårbing foon oufsnaase wårt aw jüdeer beårbingssid ai stiped.',
'permissionserrors'                => 'Beruchtigingsfäägel',
'permissionserrorstext'            => 'Dü bast ai beruchted, jü aksjoon üttufäären. {{PLURAL:$1|grün|grüne}}:',
'permissionserrorstext-withaction' => 'Dü bast ai beruchtit, $2.
{{PLURAL:$1|grün|grüne}}:',
'recreate-moveddeleted-warn'       => "'''Paase üüb: Dü wel en artiikel maage, diar iar al ans stregen wurden as.'''
Auerlei di det gud, amdat dü niks ferkiard maagest.
Uun't logbuk stäänt muar diartu:",
'moveddeleted-notice'              => 'Jüheer sid wörd sleeked. Deer füliet en üttooch üt dåt sleek- än ferschüwingslogbök for jüheer sid.',
'log-fulllog'                      => 'Åle logbük-önjdrååge önjkiike',
'edit-hook-aborted'                => 'Jü beårbing wörd suner ferklååring döör en snaasstää oufbräägen.',
'edit-gone-missing'                => 'Jü sid köö ai aktualisiird wårde.
Jü wörd önjscheened sleeked.',
'edit-conflict'                    => 'Beårbingskonflikt.',
'edit-no-change'                   => 'Din beårbing wörd ignoriird, deer niinj änring an e täkst fornümen wörd.',
'edit-already-exists'              => 'Köö niinj nai sid mååge.
Dåt bestöö ål.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Woorschauing: Jüdeer sid önjthålt tu fool apteele foon widluftie parserfunksjoone.

Deer {{PLURAL:$2|mötj ai mör ås 1 apteel|mönje ai mör ås $1 apteele}} weese.',
'expensive-parserfunction-category'       => 'Side, da widluftie parserfunksjoone tu oofding apteele',
'post-expand-template-inclusion-warning'  => "'''Woorschauing:''' Jü grutelse foon da önjbünene forlååge as tu grut, hu forlååge koone ai önjbünen wårde.",
'post-expand-template-inclusion-category' => 'Side, önj da jü maksimoole grutelse foon önjbünene forlååge ouerschran as',
'post-expand-template-argument-warning'   => 'Påås aw: Jüdeer sid enthålt tumanst en argumänt önj en forlååge, dåt äkspandiird tu grut as. Dadeere argumänte wårde ignoriird.',
'post-expand-template-argument-category'  => 'Side, da ignoriirde forlååge-argumänte önjthüülje',
'parser-template-loop-warning'            => 'Forlåågesloif önjtdäkt: [[$1]]',
'parser-template-recursion-depth-warning' => 'Forloagerekursjoonsdiipgränse ouerschran ($1)',
'language-converter-depth-warning'        => 'Spräkekonwärsjoonsdiipdegränse ouerschren ($1)',

# "Undo" feature
'undo-success' => 'Detdiar feranrang koon turag nimen wurd. 
Luke oner, of dü det uk würelk du wel, an do seekre din feranrangen.',
'undo-failure' => 'Jü änring köö ai tunintemååged wårde, deer di bedrååwede oufsnaas intwasche feränred wörd.',
'undo-norev'   => 'Jü beårbing köö ai tunintemååged wårde, deer jü ai bestoont unti sleeked wörd.',
'undo-summary' => 'Änring $1 foon [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskusjoon]]) tunintemååged.',

# Account creation failure
'cantcreateaccounttitle' => 'Benjüterkonto köö ai mååged wårde',
'cantcreateaccount-text' => "Dår måågen foon en brükerkonto foon jü IP-adräs '''($1)''' üt wörd döör [[User:$3|$3]] späred.

Grün foon jü späre: ''$2''",

# History pages
'viewpagelogs'           => 'Logböke for jüdeer sid wise',
'nohistory'              => 'Deer as niinj beårbingshistoori for jüdeer sid.',
'currentrev'             => 'Aktäle färsjoon.',
'currentrev-asof'        => 'Aktuäl färsjoon foon $2 am e klook $3',
'revisionasof'           => 'Färsjoon foon e klook $2, $3',
'revision-info'          => 'Färsjoon foon di $4 am e klook $5 foon $2',
'previousrevision'       => '← Näistålere färsjoon',
'nextrevision'           => 'Näistjunger färsjoon →',
'currentrevisionlink'    => 'Aktuäle färsjoon',
'cur'                    => 'aktuäl',
'next'                   => 'näist',
'last'                   => 'leest',
'page_first'             => 'Began',
'page_last'              => 'Iinje',
'histlegend'             => 'Tu wising foon da änringe iinjfåch da tu ferglikene Färsjoone ütwääle än di klänkfläche „{{int:compareselectedversions}}“ klikke.<br />
* (Aktuäl) = unerschiis tu e aktuäle färsjoon, (Leeste) = unerschiis tu e leeste färsjoon
* Klook/dootem = färsjoon tu jüdeer tid, brükernoome/IP-adräs foon di beårber, L = latje änring.',
'history-fieldset-title' => 'Säk önj e färsjoonshistoori',
'history-show-deleted'   => 'bloot straagene färsjoone',
'histfirst'              => 'Ålste',
'histlast'               => 'Naiste',
'historysize'            => '({{PLURAL:$1|1 Byte|$1 Bytes}})',
'historyempty'           => '(lääsi)',

# Revision feed
'history-feed-title'          => 'Färsjoonshistoori',
'history-feed-description'    => 'Färsjoonshistoori for jüdeer sid önj {{SITENAME}}',
'history-feed-item-nocomment' => '$1 bit $2',
'history-feed-empty'          => 'Jü önjfordied sid bestoont ai. Flicht wörd jü sleeked unti ferschääwen.  [[Special:Search|Döörsäk]] {{SITENAME}} aw pååsende naie side.',

# Revision deletion
'rev-deleted-comment'         => '(Tuhuupefooting wächnümen)',
'rev-deleted-user'            => '(Brükernoome wächhååld)',
'rev-deleted-event'           => '(Logbökaksjoon wächhååld)',
'rev-deleted-user-contribs'   => '[Benjüternoome unti IP-adräs wächhååld - beårbing üt baidråge ferstäägen]',
'rev-deleted-text-permission' => "Judeer Färsjoon wörd '''straagen'''.
Näre önjgoowen tu di strikforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].",
'rev-deleted-text-unhide'     => "Jüdeer färsjoon as '''straagen''' wörden.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].
Dü koost nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-suppressed-text-unhide'  => "Jüdeer färsjoon as '''unerdrükt''' wörden.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} strik-logbök].
Dü koost nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-deleted-text-view'       => "Jüdeer Färsjoon as '''straagen''' wörden.
Dü koost da wider önjkiike. Näre önjgoowen tu di sleekforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].",
'rev-suppressed-text-view'    => "Jüdeer färsjoon as '''unerdrükd''' wörden.
Dü koost da önjkiike. Ainkelthäide stönje önj dåt [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unerdrukings-logbök].",
'rev-deleted-no-diff'         => "Dü koost jüdeer ferschääl ai bekiike, dan iinj foon da änringe wörd '''straagen'''. Näre önjgoowen tu di strikforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].",
'rev-suppressed-no-diff'      => "Dü koost jüdeer ferschääl ai bekiike, dan iinj foon da änringe wörd '''straagen'''.",
'rev-deleted-unhide-diff'     => "Iinj foon da änringe doon jüdeer ferschääl as '''straagen''' wörden.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].
Dü koost nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-suppressed-unhide-diff'  => "Iinj foon da färsjoone foon dåtdeer ferschääl wörd '''unerdrükd'''.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} unerdruk-logbök].
Dü koost nuch [$1 dåtdeer ferschääl bekiike], wan dü wider gunge mååst.",
'rev-deleted-diff-view'       => "En Färsjoon foon dåtdeer färsjoonsferschääl wörd '''straagen'''.
Dü koost dåt färsjoonsferschääl siinj.
Näre önjgoowen fant huum önj dåt [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} strik-logbök].",
'rev-suppressed-diff-view'    => "Iinj foon da färsjoone foon dåtdeer färsjoonsferschääl wörd '''unerdrükd'''.
Dü koost dåtheer färsjoonsferschääl siinj. Ainkelthäide stönje önj dåt [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unerdrukings-logbök].",
'rev-delundel'                => 'wis/fersteeg',
'rev-showdeleted'             => 'wise',
'revisiondelete'              => 'Färsjoone strike/wider mååge',
'revdelete-nooldid-title'     => 'Niinj färsjoon önjjääwen',
'revdelete-nooldid-text'      => 'Dü heest nian wersjuun för detheer aktsjuun uunden, of det wersjuun jaft at ei, of dü fersjükst, en aktuel wersjuun tu striken.',
'revdelete-nologtype-title'   => 'Niinj logtüüp önjjääwen',
'revdelete-nologtype-text'    => 'Deer wörd niinj logtüüp for jüheer aksjoon önjjääwen.',
'revdelete-nologid-title'     => 'Üngülti logönjdråch',
'revdelete-nologid-text'      => 'Deer wör niinj logtüüp ütkiisd unti di kiisde logtüüp bestoont ai.',
'revdelete-no-file'           => 'Jü önjjääwen dootäi bestoont ai.',
'revdelete-show-file-confirm' => 'Bast dü sääker, dåt de jü sleeked färsjoon foon e dootäi „<nowiki>$1</nowiki>“ foon e $2 am e klook $3 önjsiinj wäät?',
'revdelete-show-file-submit'  => 'Jåå',
'revdelete-selected'          => "'''{{PLURAL:$2|Ütjsoocht wersjuun|Ütjsoocht wersjuunen}} faan [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Ütjsoocht logbukiindrach|Ütjsoocht logbukiindracher}}:'''",
'revdelete-text'              => "'''Stregen wersjuunen an aktsjuunen bliiw uun det wersjuunshistoore an uun a logbuken, man det koon ei arken efterlees.'''

Ööder administratooren üüb {{SITENAME}} kön oober üüb det ferstäächt histoore tugrip an tu nuad en ual wersjuun weder iinstel.",
'revdelete-confirm'           => 'Ferseekre noch ans, dat dü det würelk du wel, dat dü witjst, wat dü dääst, an dat det mä a [[{{MediaWiki:Policy-url}}|bestemangen]] auerian stemet.',
'revdelete-suppress-text'     => "Det skul '''bluas''' onertrakt wurd bi:
* Persöönelk informatsjuunen, diar näämen wat uungung
*: ''Adresen, Tilefoonnumern, Ferseekerangsnumern an sowat''",
'revdelete-legend'            => 'Seeten foon da sachtboorhäids-gränse',
'revdelete-hide-text'         => 'Täkst foon e färsjoon fersteege',
'revdelete-hide-image'        => 'Fersteege wat önj e Dootäi stoont',
'revdelete-hide-name'         => 'Logbök-aksjoon fersteege',
'revdelete-hide-comment'      => 'Beårbingskomäntoor fersteege',
'revdelete-hide-user'         => 'Benjüternoome//IP-adräse foon e beårber fersteege',
'revdelete-hide-restricted'   => 'Doote uk for administratoore än oudere unerdrüke',
'revdelete-radio-same'        => '(ai änre)',
'revdelete-radio-set'         => 'Jåå',
'revdelete-radio-unset'       => 'Nåån',
'revdelete-suppress'          => 'Grün foon dåt striken uk for administratoore fersteege',
'revdelete-unsuppress'        => 'Gränse for wi måågede färsjoone wächnaame',
'revdelete-log'               => 'Begrüning:',
'revdelete-submit'            => 'Aw {{PLURAL:$1|kiisd färsjoon|kiisde färsjoone}} önjwiinje',
'revdelete-success'           => "'''Jü färsjoonsönjsicht wörd aktualisiird.'''",
'revdelete-failure'           => "'''Jü färsjoonsönjsicht köö ai aktualisiird wårde:'''
$1",
'logdelete-success'           => "'''Logbökönjsicht ma erfolch aktualisiird.'''",
'logdelete-failure'           => "'''Logböksachtboorhäid köö ai änred wårde:'''
$1",
'revdel-restore'              => 'sichtboorhäid änre',
'revdel-restore-deleted'      => 'sleekede färsjoone',
'revdel-restore-visible'      => 'sachtboore färsjoone',
'pagehist'                    => 'Färsjoonshistoori',
'deletedhist'                 => 'Straagene färsjoone',
'revdelete-hide-current'      => 'Fäägel bai t fersteegen foon di önjdråch foon e klook $1, $2; ditheer as jü aktuäl färsjoon,
jü koon ai ferstäägen wårde.',
'revdelete-show-no-access'    => 'Fäägel bai t wisen foon di önjdråch foon $1, e klook $2: diheer önjdråch wörd ås "begränsed" markiird.
Dü heest deeraw nåån tugraawe.',
'revdelete-modify-no-access'  => 'Bi\'t bewerkin di $1, am a klook $2 as wat skiaf gingen: Diheer iindrach as üs "hualew klaar" markiaret. Dü könst diar ei üüb tugrip.',
'revdelete-modify-missing'    => "Bi't bewerkin faan ID $1 as wat skiaf gingen: At waant uun a dootenbeenk!",
'revdelete-no-change'         => "'''Waarskau:''' Di iindrach faan di $1, am a klook $2 hää al jodiar iinstelangen.",
'revdelete-concurrent-change' => "Bi't bewerkin faan di iindrach di $1, am a klook $2 as wat skiaf gingen: At sjocht so ütj, üs wan hoker ööders det bewerket hää, iar dü det bewerke wulst. Luke iin uun a logbuken.",
'revdelete-only-restricted'   => "Bi't fersteegen faan di iindrach di $1, am a klook $2 as wat skiaf gingen: Dü könst di iindrach ei föör administratooren fersteeg, saner ööder iinstelangen tu feranrin.",
'revdelete-reason-dropdown'   => "*Grünjer för't striken san miast
** Copyright woort ei iinhäälen
** Persöönelk informatsjuunen, diar näämen wat uungung",
'revdelete-otherreason'       => 'Ouderen/tubaikaamenden grün:',
'revdelete-reasonotherlist'   => 'Ouderen grün',
'revdelete-edit-reasonlist'   => 'Strikgrüne beårbe',
'revdelete-offender'          => 'Autoor foon jüdeer färsjoon:',

# Suppression log
'suppressionlog'     => 'Oversight-logbök',
'suppressionlogtext' => 'Detheer as det logbuk faan oversighter aktsjuunen.
Luke bi [[Special:BlockList|List faan speret IP-adresen an brükernöömer]] för aktuel sperangen.',

# History merging
'mergehistory'                     => 'Fersjoonshistoorie feriine',
'mergehistory-header'              => 'Ma jüdeer spetsjåålsid koost dü jü färsjoonshistoori foon en jurtkamstsid ma jü färsjoonshistoori foon en müüljsid feriine.
Stal sääker, dåt jü färsjoonshistoori foon en sid histoorisch koräkt as.',
'mergehistory-box'                 => 'Rewisjoone foon tou side feriine:',
'mergehistory-from'                => 'Jurtkamstsid:',
'mergehistory-into'                => 'Müüljsid:',
'mergehistory-list'                => 'Färsjoone, da feriind wårde koone',
'mergehistory-merge'               => 'Jodiar wersjuunen faan „[[:$1]]“ kön efter „[[:$2]]“ auerdraanj wurd.
Markiare det wersjuun, wat üs leetst mä auerdraanj wurd skal.
A nawigatsjuun links saat ales weder turag üüb di ual stant.',
'mergehistory-go'                  => 'Wis färsjoone da feriind wårde koone',
'mergehistory-submit'              => 'Feriinde färsjoone',
'mergehistory-empty'               => 'Niinj färsjoone koone feriind wårde.',
'mergehistory-success'             => '$3 {{PLURAL:$3|färsjoon|färsjoone}} foon [[:$1]] ma erfolch feriind tu [[:$2]].',
'mergehistory-fail'                => 'Färsjoone koone ai feriind wårde, wees sü gödj än kontroliir jü sid än da tidönjgoowe.',
'mergehistory-no-source'           => 'Jurtkamstsid "$1" as ai deer.',
'mergehistory-no-destination'      => 'Müüljsid „$1“ bestoont ai.',
'mergehistory-invalid-source'      => 'Jurtkamstsid mötj en gülti sidnoome heewe.',
'mergehistory-invalid-destination' => 'Müüljsid mötj en gülti sidnoome weese.',
'mergehistory-autocomment'         => '„[[:$1]]“ feriind eefter „[[:$2]]“',
'mergehistory-comment'             => '„[[:$1]]“ feriind eefter „[[:$2]]“: $3',
'mergehistory-same-destination'    => 'Jurtkamst- än müüljsid mönje ai idäntisch weese',
'mergehistory-reason'              => 'Grün:',

# Merge log
'mergelog'           => 'Feriin-logbök',
'pagemerge-logentry' => 'feriind [[$1]] eefter [[$2]] (färsjoonen bit $3)',
'revertmerge'        => 'Feriining tuninte mååge',
'mergelogpagetext'   => 'Dåtheer as dåt logbök foon da feriinde färsjoonshistoorie.',

# Diffs
'history-title'            => '$1: Ferluup faan a wersjuunen',
'difference'               => '(Ferschääl twasche Färsjoone)',
'difference-multipage'     => '(Ferschääl twasche side)',
'lineno'                   => 'Ra $1:',
'compareselectedversions'  => 'Wäälde färsjoone ferglike',
'showhideselectedversions' => 'Wäälde färsjoone wise/fersteege',
'editundo'                 => 'tunintemååge',
'diff-multi'               => '({{PLURAL:$1|Ian wersjuun diartesken|$1 wersjuunen diartesken}} faan {{PLURAL:$2|ään brüker|$2 brükern}} {{PLURAL:$1|woort|wurd}} ei uunwiset)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Ian wersjuun diartesken|$1 wersjuunen diartesken}} faan muar üs $2 {{PLURAL:$2|brüker|brükern}} wurd ei uunwiset)',

# Search results
'searchresults'                    => 'Säkjresultoote',
'searchresults-title'              => 'Säkjresultoote for „$1“',
'searchresulttext'                 => 'For mör informasjoon tu jü säkj sii jü [[{{MediaWiki:Helppage}}|heelpsid]].',
'searchsubtitle'                   => 'Din säkönjfrååg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|åle ma „$1“ beganende side]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|åle side, da eefter „$1“ ferlinke]])',
'searchsubtitleinvalid'            => 'Din säkönjfrååg: "$1".',
'toomanymatches'                   => 'Diar kaam tuföl resultooten üüb din uunfraag. Fersjük det ööders.',
'titlematches'                     => 'Oueriinjstiminge ma sidetiitle',
'notitlematches'                   => 'Niinj oueriinjstiming ma sidetiitle',
'textmatches'                      => 'Oueriinjstiminge ma inhålte',
'notextmatches'                    => 'Niinj oueriinjstiming ma inhålte',
'prevn'                            => '{{PLURAL:$1|leesten|leeste $1}}',
'nextn'                            => '{{PLURAL:$1|näisten|näiste $1}}',
'prevn-title'                      => 'Leeste $1 {{PLURAL:$1|resultoot|resultoote}}',
'nextn-title'                      => 'Näiste $1 {{PLURAL:$1|resultoot|resultoote}}',
'shown-title'                      => 'Wis $1 {{PLURAL:$1|resultoot|resultoote}} pro sid',
'viewprevnext'                     => 'Wis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Säkmöölikhäide',
'searchmenu-exists'                => "'''Deer as en sid nååmd \"[[:\$1]]\" önj jüdeer Wiki'''",
'searchmenu-new'                   => "'''Mååg jü sid „[[:$1|$1]]“ önj jüdeer Wiki.'''",
'searchhelp-url'                   => 'Help:Inhåltsfertiiknis',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Wise aal jo sidjen, diar so began]]',
'searchprofile-articles'           => 'Artiikle',
'searchprofile-project'            => 'Heelp än Prujäktside',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Ales',
'searchprofile-advanced'           => 'Ütwided',
'searchprofile-articles-tooltip'   => 'Säk önj $1',
'searchprofile-project-tooltip'    => 'Säk önj $1',
'searchprofile-images-tooltip'     => 'Bilen sjük',
'searchprofile-everything-tooltip' => 'Sjük aueraal (uk diskusjuunssidjen)',
'searchprofile-advanced-tooltip'   => 'Onj mör noomerüme säke',
'search-result-size'               => '$1 ({{PLURAL:$2|1 uurd|$2 uurde}})',
'search-result-category-size'      => '{{PLURAL:$1|1 sidj|$1 sidjen}} ({{PLURAL:$2|1 onerkategorii|$2 onerkategoriin}}, {{PLURAL:$3|1 datei|$3 dateien}})',
'search-result-score'              => 'Relevans: $1 %',
'search-redirect'                  => '(widerliidjing foon „$1“)',
'search-section'                   => '(oufsnaas $1)',
'search-suggest'                   => 'Miinjdst dü „$1“?',
'search-interwiki-caption'         => 'Süsterprujäkte',
'search-interwiki-default'         => '$1 resultoote:',
'search-interwiki-more'            => '(widere)',
'search-mwsuggest-enabled'         => 'ma forsliike',
'search-mwsuggest-disabled'        => 'niinj forsliike',
'search-relatedarticle'            => 'früne',
'mwsuggest-disable'                => 'forsliike per Ajax deaktiviire',
'searcheverything-enable'          => 'Onj ål noomerüme säke',
'searchrelated'                    => 'früne',
'searchall'                        => 'åle',
'showingresults'                   => "Heer {{PLURAL:$1|as '''1''' resultoot|san '''$1''' resultoote}}, beganend ma numer '''$2.'''",
'showingresultsnum'                => "Heer {{PLURAL:$3|as '''1''' resultoot|san '''$3''' resultoote}}, beganend ma numer '''$2.'''",
'showingresultsheader'             => "{{PLURAL:$5|resultoot '''$1''' foon '''$3'''|resultoote '''$1-$2''' foon '''$3'''}}, for '''$4.'''",
'nonefound'                        => "'''Haanewising:''' Deer wårde ståndardmääsi man ainkelde noomerüme döörsoocht. Seet ''all:'' for din Säkbegrip, am åle side (inkl. diskusjoonside, forlååge, äsw.) tu döörsäken unti gesiilt di noome foon di tu döörsäkende noomerüm.",
'search-nonefound'                 => 'For din säkanfrååg würden niinj resultoote fünen.',
'powersearch'                      => 'ütwided säkj',
'powersearch-legend'               => 'ütwided säkj',
'powersearch-ns'                   => 'Säkj önj noomerüme:',
'powersearch-redir'                => 'Widerliidjinge anwise',
'powersearch-field'                => 'Säk eefter:',
'powersearch-togglelabel'          => 'Wääl üt:',
'powersearch-toggleall'            => 'Åle',
'powersearch-togglenone'           => 'Niinj',
'search-external'                  => 'Extern säkj',
'searchdisabled'                   => 'Jü {{SITENAME}}-säkj as deaktiviird. Dü koost intwasche ma Google säke. Betånk, dåt di säkindäks for {{SITENAME}} ferüüljet weese koon.',

# Quickbar
'qbsettings'                => 'Sidjenliist',
'qbsettings-none'           => 'Niinj',
'qbsettings-fixedleft'      => 'Lachts, fääst',
'qbsettings-fixedright'     => 'Rochts, fääst',
'qbsettings-floatingleft'   => 'Lachts, auergung',
'qbsettings-floatingright'  => 'Rochts, auergung',
'qbsettings-directionality' => 'Fääst, hinget faan di skriiwwai faan det spriak uf',

# Preferences page
'preferences'                   => 'Önjstalinge',
'mypreferences'                 => 'Önjstalinge',
'prefs-edits'                   => 'Taal faan feranrangen:',
'prefsnologin'                  => 'Ai önjmälded',
'prefsnologintext'              => 'Dü skel <span class="plainlinks">[{{fullurl:{{#special:UserLogin}}|returnto=$1}} uunmeldet]</span> wees, am din iinstelangen tu feranrin.',
'changepassword'                => 'Pååsuurd änre',
'prefs-skin'                    => 'Skak',
'skin-preview'                  => 'Forlök',
'datedefault'                   => 'Foor-önjstaling',
'prefs-beta'                    => 'Beta mögelkhaiden',
'prefs-datetime'                => 'Dai an klooktidj',
'prefs-labs'                    => 'Alpha mögelkhaiden',
'prefs-personal'                => 'Brüker dooten',
'prefs-rc'                      => 'Leeste änringe',
'prefs-watchlist'               => "Uun't uug behual",
'prefs-watchlist-days'          => "So föl daar uun't uug behual:",
'prefs-watchlist-days-max'      => 'Ei muar üs {{PLURAL:$1|ään dai|$1 daar}}',
'prefs-watchlist-edits'         => 'Ei muar feranrangen üs:',
'prefs-watchlist-edits-max'     => 'Ai mör as 1000',
'prefs-watchlist-token'         => "Token för uun't uug behualen:",
'prefs-misc'                    => 'Dit än dat',
'prefs-resetpass'               => 'Pååsuurd änre',
'prefs-changeemail'             => 'Feranre det E-Mail-adres',
'prefs-setemail'                => 'E-Mail-adres fäästlei:',
'prefs-email'                   => 'E-Mail iinstelangen',
'prefs-rendering'               => 'Skak',
'saveprefs'                     => 'Önjstalinge sääkere',
'resetprefs'                    => 'Wech diarmä',
'restoreprefs'                  => 'Normool iinstelangen weder haale',
'prefs-editing'                 => 'Bewerke',
'prefs-edit-boxsize'            => "Grate faan't wönang tu't bewerkin:",
'rows'                          => 'Räen:',
'columns'                       => 'Spleder:',
'searchresultshead'             => 'Sjük',
'resultsperpage'                => 'So fölsis komt det föör per sidj:',
'stub-threshold'                => 'Formatiarang faan links <a href="#" class="stub">för letj sidjen</a> (uun Byte):',
'stub-threshold-disabled'       => 'Ufsteld',
'recentchangesdays'             => 'Soföl daar skel a „leetst feranrangen“ uunwise:',
'recentchangesdays-max'         => 'Ei muar üs {{PLURAL:$1|ään dai|$1 daar}}',
'recentchangescount'            => 'Soföl feranrangen skel uunwiset wurd:',
'prefs-help-recentchangescount' => 'Det san a leetst feranrangen, wersjuunen an logbuken.',
'prefs-help-watchlist-token'    => "Wan dü detdiar fial mä en hiamelken code ütjfalst, woort en RSS-feed iinracht. Arken mä didiar code koon do sä, wat dü uun't uug behual wel. Diaram skul hi ei so ianfach wees, nem dach didiar: $1",
'savedprefs'                    => 'Din iinstelangen san seekert wurden.',
'timezonelegend'                => 'Tidjsoon:',
'localtime'                     => 'lokaal tid',
'timezoneuseserverdefault'      => 'Wiki standard tidj brük ($1)',
'timezoneuseoffset'             => 'Öödern (ferskeel uundu)',
'timezoneoffset'                => 'Ferskeel¹:',
'servertime'                    => 'Server klooktidj:',
'guesstimezone'                 => 'Faan a browser auernem',
'timezoneregion-africa'         => 'Afrikaa',
'timezoneregion-america'        => 'Ameerikaa',
'timezoneregion-antarctica'     => 'Antarktikaa',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Aasien',
'timezoneregion-atlantic'       => 'Atlantik',
'timezoneregion-australia'      => 'Austraalien',
'timezoneregion-europe'         => 'Euroopa',
'timezoneregion-indian'         => 'Indik',
'timezoneregion-pacific'        => 'Pasiifik',
'allowemail'                    => 'E-Mail foon oudere brükere tulätje',
'prefs-searchoptions'           => 'Säkmöölikhäide',
'prefs-namespaces'              => 'Noomerüme',
'defaultns'                     => 'Ouers säk önj jüheer noomerüme:',
'default'                       => 'Forinstaling',
'prefs-files'                   => 'Dååtäie',
'prefs-custom-css'              => 'Salew maaget CSS',
'prefs-custom-js'               => 'Salew maaget JavaScript',
'prefs-common-css-js'           => 'CSS / JavaScript för arke skak:',
'prefs-reset-intro'             => 'Üüb detdiar sidj könst dü weder a normool iinstelangen iinracht.
Do san jo ual iinstelangen wech.',
'prefs-emailconfirm-label'      => 'E-Mail gudkäänd:',
'prefs-textboxsize'             => "Grate faan't wönang tu bewerkin",
'youremail'                     => 'E-mail:',
'username'                      => 'Brükernoome:',
'uid'                           => 'Brüker ID:',
'prefs-memberingroups'          => 'Lasmoot faan {{PLURAL:$1|brükerskööl|brükersköölen}}:',
'prefs-registration'            => 'Uunmelde-tidj',
'yourrealname'                  => 'Rocht nööm:',
'yourlanguage'                  => 'Spräke:',
'yourvariant'                   => 'Spriak:',
'prefs-help-variant'            => 'Uun hün skriiwwiis skel a sidjen uunwiset wurd:',
'yournick'                      => 'Nai signatuur:',
'prefs-help-signature'          => 'Wees so gud an onerskriiw üüb diskusjuunssidjen mä „<nowiki>~~~~</nowiki>“. Diar komt do dan brükernööm an det klooktidj bi ütj.',
'badsig'                        => "Diar stemet wat ei mä't signatuur. Preewe at HTML.",
'badsiglength'                  => 'Din signatuur mut ei muar üs $1 {{PLURAL:$1|tiaken|tiakens}} haa.',
'yourgender'                    => 'Slach:',
'gender-unknown'                => 'Ei bekäänd',
'gender-male'                   => 'Maan',
'gender-female'                 => 'Wüf',
'prefs-help-gender'             => "Stäänt tu wool: Det woort brükt för't paasin uunspreegen faan maaner an wüfen. Arken koon det lees.",
'email'                         => 'E-mail',
'prefs-help-realname'           => 'Stäänt tu wool. Wan dü dan rochten nööm uundääst, koon hi mä din bewerkangen ferbünjen wurd.',
'prefs-help-email'              => 'Dü säärst din e-mail-adres ei uundu, oober do könst dü uk nian mädialangen fu, wan dü ans din paaswurd ferjiden heest.',
'prefs-help-email-others'       => 'Mä ööder brükern könst dü uk auer hör an din brükersidj kontakt apnem. Diarför woort din e-mail-adres ei brükt.',
'prefs-help-email-required'     => 'Du en rocht E-Mail-adres uun.',
'prefs-info'                    => 'Baasisdooten',
'prefs-i18n'                    => 'Spriak',
'prefs-signature'               => 'Signatuur',
'prefs-dateformat'              => "Formaat faan't dootem",
'prefs-timeoffset'              => 'Ferskeel faan a klooktidj',
'prefs-advancedediting'         => 'Ütwided möölikhäide',
'prefs-advancedrc'              => 'Ütwided möölikhäide',
'prefs-advancedrendering'       => 'Ütwided möölikhäide',
'prefs-advancedsearchoptions'   => 'Ütwided möölikhäide',
'prefs-advancedwatchlist'       => 'Ütwided möölikhäide',
'prefs-displayrc'               => "Mögelkhaiden för't uunwisin",
'prefs-displaysearchoptions'    => "Mögelkhaiden för't uunwisin",
'prefs-displaywatchlist'        => "Mögelkhaiden för't uunwisin",
'prefs-diffs'                   => 'Ferskeel',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Detdiar E-Mail-adres schocht gud ütj.',
'email-address-validity-invalid' => 'Du en echt E-Mail-adres uun.',

# User rights
'userrights'                   => 'Brükerrochten bewerke',
'userrights-lookup-user'       => 'Brükersköölen bewerke',
'userrights-user-editname'     => 'Brükernööm:',
'editusergroup'                => 'Brükersköölen bewerke',
'editinguser'                  => "Brükerrochten faan '''[[User:$1|$1]]''' $2 feranre",
'userrights-editusergroup'     => 'Lasmootskap tu brükersköölen bewerke',
'saveusergroups'               => 'Brükersköölen seekre',
'userrights-groupsmember'      => 'Lasmoot faan:',
'userrights-groupsmember-auto' => 'Faan salew lasmoot faan:',
'userrights-groups-help'       => 'Dü könst feranre, tu hün brükerskööl di brüker hiirt:
* En uunkrüsagt kasche ment, di brüker hiirt diartu.
* En ei uunkrüsagt kasche ment, di brüker hiart ei diartu.
* En * ment, dat dü det brükerrocht ei weder wechnem könst (of amkiard).',
'userrights-reason'            => 'Grünj:',
'userrights-no-interwiki'      => 'Dü heest ei det rocht, am brükerrochten uun ööder wikis tu feranrin.',
'userrights-nodatabase'        => 'Det dootenbeenk $1 jaft at ei, tumanst ei lokaal.',
'userrights-nologin'           => 'Dü mutst di mä en administraator-brükerkonto [[Special:UserLogin|uunmelde]], wan dü brükerrochten feranre wel.',
'userrights-notallowed'        => 'Dü heest ei det rocht, am brükerrochten tu feranrin.',
'userrights-changeable-col'    => 'Lasmootskapen, diar dü feranre könst',
'userrights-unchangeable-col'  => 'Lasmootskapen, diar dü ei feranre könst',

# Groups
'group'               => 'Skööl:',
'group-user'          => 'Brükern',
'group-autoconfirmed' => 'Registriaret brükern',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administratooren',
'group-bureaucrat'    => 'Bürokraaten',
'group-suppress'      => 'Oversighter',
'group-all'           => '(aaltumaal)',

'group-user-member'          => '{{GENDER:$1|brüker}}',
'group-autoconfirmed-member' => '{{GENDER:$1|registriaret brüker}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|administraator}}',
'group-bureaucrat-member'    => '{{GENDER:$1|bürokraat}}',
'group-suppress-member'      => '{{GENDER:$1|oversighter}}',

'grouppage-user'          => '{{ns:project}}:Brükern',
'grouppage-autoconfirmed' => '{{ns:project}}:Registriaret brükern',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administratooren',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokraaten',
'grouppage-suppress'      => '{{ns:project}}:Oversighter',

# Rights
'right-read'               => 'Sidjen lees',
'right-edit'               => 'Sidjen bewerke',
'right-createpage'         => 'Sidjen maage (saner diskusjuunssidjen)',
'right-createtalk'         => 'Diskusjuunssidjen maage',
'right-createaccount'      => 'Brükerkonto iinracht',
'right-minoredit'          => 'Feranrangen üs letj kääntiakne',
'right-move'               => 'Sidjen fersküüw',
'right-move-subpages'      => 'Sidjen mä onersidjen fersküüw',
'right-move-rootuserpages' => 'Hood-brükersidj fersküüw',
'right-movefile'           => 'Dateien fersküüw',
'right-suppressredirect'   => "Bi't fersküüwen nian widjerfeerang iinracht",
'right-upload'             => 'Dateien huuchsjüür',
'right-reupload'           => 'Dateien auerskriiw',
'right-reupload-own'       => 'En datei auerskriiw, diar dü salew huuchsjüürd heest',
'right-reupload-shared'    => 'En datei auerskriiw, diar uun en gemiansoom archiif leit',
'right-upload_by_url'      => 'Dateien faan en URL-adress huuchsjüür',
'right-purge'              => 'Sidjen-cache leesag maage saner efterfraagin',
'right-autoconfirmed'      => 'Hualew-seekert sidjen bewerke',
'right-bot'                => 'Automatisiaret bewerke',
'right-nominornewtalk'     => 'Letj feranrangen üüb diskusjuunssidjen wurd ei üs „nei noorachten“ uunwiset.',
'right-apihighlimits'      => 'Huuger taalen für API-uunfraagen brük',
'right-writeapi'           => 'Write-API brük',
'right-delete'             => 'Sidjen strik',
'right-bigdelete'          => 'Sidjen mä föl wersjuunen strik',
'right-deleterevision'     => 'Enkelt wersjuunen faan en sidj strik of turaghaale',
'right-browsearchive'      => 'Sjük stregen sidjen',
'right-sendemail'          => 'E-mails tu oudere brükere schake',

# User rights log
'rightslog'  => 'Ruchte-logbök',
'rightsnone' => '(-)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'jüdeer sid leese',
'action-edit'               => 'jüdeer sid beårbe',
'action-createpage'         => 'side mååge',
'action-createtalk'         => 'diskusjoonside mååge',
'action-createaccount'      => 'jüdeer brükerkonto mååge',
'action-minoredit'          => 'detdiar feranrang üs letj kääntiakne',
'action-move'               => 'jüdeer sid ferschüwe',
'action-move-subpages'      => 'jüdeer sid än unerside ferschüwe',
'action-move-rootuserpages' => 'hood-brükersidj fersküüw',
'action-movefile'           => 'jüdeer sid ferschüwe',
'action-delete'             => 'detdiar sidj strik',
'action-browsearchive'      => 'sjük stregen sidjen',
'action-undelete'           => 'detdiar sidj weder iinstel',
'action-sendemail'          => 'e-mails sjüür',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|änring|änringe}}',
'recentchanges'                   => 'Leeste änringe',
'recentchanges-legend'            => 'Wis-opsjoone',
'recentchangestext'               => "Üüb detdiar sidj könst dü a leetst feranerangen faan '''{{SITENAME}}''' ferfulge.",
'recentchanges-feed-description'  => 'Ferfülie ma dåtheer feed da leeste änringe önj {{SITENAME}}.',
'recentchanges-label-newpage'     => 'Nei sidj uunlaanj',
'recentchanges-label-minor'       => 'Letj feranrang',
'recentchanges-label-bot'         => 'Feranrang faan en bot',
'recentchanges-label-unpatrolled' => 'Detdiar feranrang as noch ei efterluket wurden',
'rcnote'                          => "Wised {{PLURAL:$1|wård '''1''' änring|wårde da leeste '''$''' änringe}} {{PLURAL:$2|foon e leest däi|foon da leeste '''$2''' deege}}. Stånd: $4, am e klook $5.",
'rcnotefrom'                      => "Diar wurd a feranrangen sant '''$2'''uunwiset (ei muar üs '''$1''' feranrangen).",
'rclistfrom'                      => 'Bloot änringe sunt $1 wise.',
'rcshowhideminor'                 => 'Latje änringe $1',
'rcshowhidebots'                  => 'Bots $1',
'rcshowhideliu'                   => 'Önjmäldede brükere $1',
'rcshowhideanons'                 => 'Anonymen brüker $1',
'rcshowhidepatr'                  => '$1 efterluket feranrangen',
'rcshowhidemine'                  => 'Äine tujeefte $1',
'rclinks'                         => 'Wis da leeste $1 änringe foon da leeste $2 deege.<br />$3',
'diff'                            => 'ferschääl',
'hist'                            => 'färsjoone',
'hide'                            => 'ütbläne',
'show'                            => 'önjbläne',
'minoreditletter'                 => 'L',
'newpageletter'                   => 'N',
'boteditletter'                   => 'B',
'rc-enhanced-expand'              => 'Detaile wise (JavaScript as nüsi)',
'rc-enhanced-hide'                => 'Detaile fersteege',
'rc-old-title'                    => 'tuiarst maaget üs „$1“',

# Recent changes linked
'recentchangeslinked'          => 'Feranrangen bi ferlinket sidjen',
'recentchangeslinked-feed'     => 'Feranrangen bi ferlinket sidjen',
'recentchangeslinked-toolbox'  => 'Feranrangen bi ferlinket sidjen',
'recentchangeslinked-title'    => 'Feranrangen bi sidjen, diar faan "$1" ferlinket san',
'recentchangeslinked-noresult' => 'Uun detdiar tidj san jo ferlinket sidjen ei feranert wurden.',
'recentchangeslinked-summary'  => "Jüdeer speetsjoolsid listet da leeste änringe bai da ferlinked side ap (btw. bai kategoriie tu da lasmoote foon jüdeer kategorii). Side aw din [[Special:Watchlist|eefterkiikliste]] san '''fåt''' deerstald.",
'recentchangeslinked-page'     => 'Sid:',
'recentchangeslinked-to'       => 'Wis änringe aw side, da heerjurt ferlinke',

# Upload
'upload'                     => 'Huuchschake',
'uploadbtn'                  => 'Datei huuchsjüür',
'uploadnologin'              => 'Ai önjmälded',
'uploadnologintext'          => 'Dü möist [[Special:UserLogin|önjmälded weese]], am dat dü dootäie huuchsiinje koost.',
'upload_directory_missing'   => 'Dåt aplees-fertiiknis ($1) breecht än köö ai foon di wäbsärwer mååged wårde.',
'upload_directory_read_only' => 'Dåt aplees-fertiiknis ($1) koon ai foon e wäbsärver beschraawen wårde.',
'uploaderror'                => 'Aplees-fäägel',
'uploadlog'                  => 'datei logbuk',
'uploadlogpage'              => 'Dåtäi-logbök',
'filedesc'                   => 'Beskriiwang',
'fileuploadsummary'          => 'Beskriiwang',
'filesource'                 => 'Kwel',
'uploadedfiles'              => 'Huuchsjüürd dateien',
'uploadedimage'              => 'heet "[[$1]]" huuchsånd',
'watchthisupload'            => 'Luke efter detdiar datei',

'upload-file-error' => 'Diar as wat skiaf gingen',

# File backend
'backend-fail-stream' => 'Det datei $1 küd ei auerdraanj wurd.',
'backend-fail-delete' => 'Det datei $1 küd ei stregen wurd.',
'backend-fail-store'  => 'Det datei $1 küd ei oner $2 seekert wurd.',
'backend-fail-copy'   => 'Det datei $1 küd ei efter $2 kopiaret wurd.',
'backend-fail-move'   => 'Det datei $1 küd ei efter $2 fersköwen wurd.',
'backend-fail-read'   => 'Det datei $1 küd ei leesen wurd.',
'backend-fail-create' => 'Det datei $1 küd ei seekert wurd.',

'license'        => 'Lisens:',
'license-header' => 'Lisens',

# File description page
'file-anchor-link'          => 'Datei',
'filehist'                  => 'Dååtäifärsjoone',
'filehist-help'             => 'Klik aw en tidpunkt, am jüdeer färsjoon önjiinjtunaamen.',
'filehist-deleteall'        => 'åle färsjoone strike',
'filehist-deleteone'        => 'jüdeer färsjoon strike',
'filehist-revert'           => 'tubääg seete',
'filehist-current'          => 'aktuäl',
'filehist-datetime'         => 'Färsjoon foon e',
'filehist-thumb'            => 'Forlökbil',
'filehist-thumbtext'        => 'Forlökbil for Färsjoon foon $2, am e klook $3',
'filehist-nothumb'          => 'Niinj forlökbil deer',
'filehist-user'             => 'brüker',
'filehist-dimensions'       => 'Mätje',
'filehist-comment'          => 'Komentoor',
'imagelinks'                => 'Hü det datei brükt woort',
'linkstoimage'              => '{{PLURAL:$1|Jü füliend sid ferwånt|Da füliende $1 side ferwiinje}} jüdeer dååtäi:',
'nolinkstoimage'            => 'Nään artiikel brükt detheer datei',
'sharedupload'              => 'Jüdeer dååtäi ståmt üt $1 än mötj foon ouder prujäkte brükt wårde.',
'sharedupload-desc-here'    => "Detheer bil as faan $1 an koon faan ööder projekten brükt wurd. 
Det beskriiwang faan't [$2 beskriiwangssidj] woort oner uunwiset.",
'uploadnewversion-linktext' => 'En nai färsjoon foon jüdeer dåtäi huuchsiinje',
'shared-repo-from'          => 'foon $1',

# Random page
'randompage' => 'Tufali sid',

# Statistics
'statistics' => 'Statistik',

'disambiguationspage' => 'Template:Muardüüdag artiikel',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'       => '{{PLURAL:$1|kategorii|kategoriie}}',
'nlinks'            => '$1 {{PLURAL:$1|link|links}}',
'nmembers'          => '{{PLURAL:$1|1 önjdraag|$1 önjdraage}}',
'prefixindex'       => 'Åle side (ma prefiks)',
'usercreated'       => '{{GENDER:$3|Maaget}} di $1 am a klook $2',
'newpages'          => 'Naie side',
'newpages-username' => 'Brükernoome:',
'move'              => 'Ferschüwe',
'movethispage'      => 'Sid ferschüwe',
'pager-newer-n'     => '{{PLURAL:$1|näisten|näiste $1}}',
'pager-older-n'     => '{{PLURAL:$1|åleren|ålere $1}}',
'suppress'          => 'Oversight',

# Book sources
'booksources'               => 'ISBN-säkj',
'booksources-search-legend' => 'Säk eefter betii-kwäle for böke',
'booksources-go'            => 'Säke',

# Special:Log
'specialloguserlabel' => 'Brüker:',
'log'                 => 'Logböke',

# Special:AllPages
'allpages'       => 'Åle side',
'alphaindexline' => '$1 bit $2',
'nextpage'       => 'Näist sid ($1)',
'prevpage'       => 'Leest sid ($1)',
'allpagesfrom'   => 'Side wise sunt:',
'allpagesto'     => 'Side wise bit:',
'allarticles'    => 'Åle side',
'allpagesprev'   => 'Leest',
'allpagesnext'   => 'Näist',
'allpagessubmit' => 'Önjwiinje',

# Special:Categories
'categories' => 'Kategoriin',

# Special:LinkSearch
'linksearch'      => "Sjük efter ferwisangen uun't näät",
'linksearch-ns'   => 'Noomerüm:',
'linksearch-ok'   => 'Säk',
'linksearch-line' => '$2 ferwiset üüb $1',

# Special:Log/newusers
'newuserlogpage' => 'Nai-önjmäldings-logbök',

# Special:ListGroupRights
'listgrouprights-members' => '(lasmoote-list)',

# E-mail user
'emailuser' => 'E-mail tu dideere brüker',

# Watchlist
'watchlist'         => 'Eefterkiikliste',
'mywatchlist'       => 'Eefterkiikliste',
'watchlistfor2'     => 'Foon $1 $2',
'addedwatchtext'    => 'Jü sid „[[:$1]]“ wörd tu din [[Special:Watchlist|eefterkiiklist]] tubaifäiged.

Lääsere änringe bai jüdeer sid än jü deertuhiirende diskusjoonssid wårde deer listed än
önj e ouersicht foon da [[Special:RecentChanges|leeste änringe]] önj fåtschraft deerstald.

Wan dü jü sid wider foon din eefterkiikliste wächhååle mååst, klik aw jüdeer sid aw „{{int:Unwatch}}“.',
'removedwatchtext'  => 'Jü sid „[[:$1]]“ wörd foon din [[Special:Watchlist|eefterkiiklist]] wächhååld.',
'watch'             => 'Kiike eefter',
'watchthispage'     => 'Side eefterkiike',
'unwatch'           => 'ai mör eefter kiike',
'watchlist-details' => 'Dü kiikst eefter {{PLURAL:$1|1 sid|$1 side}}.',
'wlshowlast'        => 'Wis da änringe foon da leeste $1 stüne, $2 deege unti $3.',
'watchlist-options' => 'Wis-opsjoone',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Eefter kiike...',
'unwatching' => 'Ai eefter kiike...',

# Delete
'deletepage'             => 'Sid tunintemååge',
'exblank'                => 'sidj wiar leesag',
'delete-confirm'         => 'Strik "$1"',
'delete-legend'          => 'Strike',
'historywarning'         => "'''Paase üüb:''' Det sidj, wat dü strik wel, hää amanbi $1 {{PLURAL:$1|wersjuun|wersjuunen}}:",
'confirmdeletetext'      => 'Dü bast deerbai, en sid ma åle tuhiirende ålere färsjoone tuninte tu måågen. Bestääsie hål deertu, dåt dü de foon da konsekwänse bewust bast, än dåt dü önj oueriinjstiming ma da [[{{MediaWiki:Policy-url}}|ruchtliinjen]] hoonelst.',
'actioncomplete'         => 'Aksjoon beånd',
'actionfailed'           => 'Diar ging wat skiaf',
'deletedtext'            => '„$1“ wörd tunintemååged. In e $2 fanst dü en list foon da tuleest tunintemåågede side.',
'dellogpage'             => 'Tunintemååg-Logbök',
'dellogpagetext'         => 'Diar stun a leetst stregen sidjen an datein.',
'deletionlog'            => "logbuk faan't striken",
'reverted'               => 'Tu en ual wersjuun turagsaat',
'deletecomment'          => 'Grün:',
'deleteotherreason'      => 'Ouderen/tubaikaamenden grün:',
'deletereasonotherlist'  => 'Ouderen grün',
'deletereason-dropdown'  => "*Algemian grünjer för't striken
** Di skriiwer wul det so
** Copyright as ei beaachtet
** Wandaalen onerwais",
'delete-edit-reasonlist' => "Grünjer för't striken bewerke",
'delete-toobig'          => 'Detdiar sidj hää muar üs $1 {{PLURAL:$1|wersjuun|wersjuunen}} . Sok sidjen kön ei so gau stregen wurd, ööders san a servers plaat.',
'delete-warning-toobig'  => "Detdiar sidj hää muar üs $1 {{PLURAL:$1|wersjuun|wersjuunen}} . Det striken koon komer maage bi't dootenbeenk.",

# Rollback
'rollbacklink' => 'tubäägseete',

# Protect
'protectlogpage'              => 'Sideschütse-logbök',
'protectedarticle'            => 'schütsed „[[$1]]“',
'modifiedarticleprotection'   => 'änred e schüts for "[[$1]]"',
'prot_1movedto2'              => 'hää „[[$1]]“ efter „[[$2]]“ fersköwen',
'protectcomment'              => 'Grün:',
'protectexpiry'               => 'Spärduur:',
'protect_expiry_invalid'      => 'Jü önjjääwen duur as üngülti.',
'protect_expiry_old'          => 'Jü spärtid lait in jü jütid.',
'protect-text'                => 'Heer koost dü e schütsstatus for jü sid "$1" önjkiike än änre.',
'protect-locked-access'       => "Din brükerkonto ferfäiget ai ouer da nüsie ruchte tu jü änring foon e sideschüts. Heer san da aktuäle sideschütsönjstalinge fon jü sid '''„$1“:'''",
'protect-cascadeon'           => 'Jüdeer sid as nütutids diilj foon e kaskaadenspäre. Jü as önj {{PLURAL:$1|jü füliende sid|da füliende side}} önjbünen, huk döör jü kaskaadenspäropsjoon schütsed {{PLURAL:$1|as|san}}. Di sideschütsstatus koon for jüdeer sid änred wårde, dåtdeer heet ouers nån influs aw jü kaskaadenspäre:',
'protect-default'             => 'Åle brükere',
'protect-fallback'            => 'Jü "$1"-beruchtiging as nüsi.',
'protect-level-autoconfirmed' => 'Späring for naie än ai registriirde brükere',
'protect-level-sysop'         => 'Bloot administratoore',
'protect-summary-cascade'     => 'kaskadiirend',
'protect-expiring'            => 'bit $2, am e klook $3 (UTC)',
'protect-cascade'             => 'Kaskadiirende späre - åle önj jüdeer sid önjbünene forlååge wårde uk spärd.',
'protect-cantedit'            => 'Dü koost jü späre foon jüheer sid ai änre, deer dü niinj beruchtiging tu beårben foon jü sid hääst.',
'restriction-type'            => 'Schütsstatus',
'restriction-level'           => 'Schütshöögde',
'minimum-size'                => 'Minimaal grate:',
'maximum-size'                => 'Maksimaal grate:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Bewerke',
'restriction-move'   => 'Fersküüw',
'restriction-create' => 'Maage',
'restriction-upload' => 'Huuchsjüür',

# Restriction levels
'restriction-level-sysop'         => 'seekert (bluas för administratooren)',
'restriction-level-autoconfirmed' => 'hualew-seekert (bluas för gudkäänd brükern)',
'restriction-level-all'           => 'aaltumaal',

# Undelete
'undelete'                   => 'Stregen sidjen uunwise',
'undeletepage'               => 'Stregen sidjen uunwise an weder iinstel',
'undeletepagetitle'          => "'''Detdiar wiset a stregen wersjuunen faan [[:$1|$1]]'''.",
'viewdeletedpage'            => 'Stregen sidjen uunwise',
'undeletepagetext'           => "{{PLURAL:$1|Detdiar sidj as stregen wurden, oober koon|Jodiar $1 sidjen san stregen wurden, oober kön}} faan administratooren weder iinsteld wurd, wan jo noch uun't archiif san.",
'undelete-fieldset-title'    => 'Weder iinstel',
'undeleteextrahelp'          => '* Am det sidj mä aal jo wersjuunen weder iintustelen, sjük nian enkelt wersjuun ütj, du en grünj uun an trak do üüb „{{int:undeletebtn}}“.*
* Am en was wersjuun weder iintustelen, sjük det wersjuun ütj, du en grünj uun an trak do üüb „{{int:undeletebtn}}“.',
'undeleterevisions'          => '{{PLURAL:$1|1 wersjuun|$1 wersjuunen}} archiwiaret',
'undeletehistory'            => 'Wan dü detdiar sidj weder iinstelst, wurd uk jo ual wersjuunen weder iinsteld. 
Wan sant det striken en nei sidj mä di salew nööm iinsteld wurden as, wurd jo ual wersjuunen bi det nei sidj mä iinwerket.',
'undeleterevdel'             => 'Det weder iinstelen woort ei maaget, wan det leetst wersjuun ferstäächt as.
Wan det so as, skal det leetst wersjuun iarst weder üüb normool steld wurd.',
'undeletehistorynoadmin'     => 'Detdiar sidj as stregen wurden.
Oner könst dü sä, hoker det maaget hää an huaram.
Di tekst faan det stregen sidj fu bluas administratooren uunwiset.',
'undelete-revision'          => 'Stregen wersjuun faan $1 (di $4 am a klook $5 ), $3:',
'undeleterevision-missing'   => 'Mä detdiar wersjuun stemet wat ei. Ferlicht as di link ferkiard of det wersjuun as ei muar diar.',
'undelete-nodiff'            => 'Nian föörgunger wersjuun diar.',
'undeletebtn'                => 'Weder iinstel',
'undeletelink'               => 'wise/widermååge',
'undeleteviewlink'           => 'Uunluke',
'undeletereset'              => 'Turag saat',
'undeleteinvert'             => 'Ütjwool amdrei',
'undeletecomment'            => 'Grünj:',
'undeletedrevisions'         => '{{PLURAL:$1|1 wersjuun|$1 wersjuunen}} weder iinsteld',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 wersjuun|$1 wersjuunen}} an {{PLURAL:$2|1 datei|$2 datein}} weder iinsteld',
'undeletedfiles'             => '{{PLURAL:$1|1 datei|$1 datein }} weder iinsteld',
'cannotundelete'             => 'Weder iinstelen hää ei loket; ferlicht hää hoker ööders det al weder iinsteld.',
'undeletedpage'              => "'''„$1“''' as weder iinsteld wurden.
Uun't [[Special:Log/delete|logbuk faan stregen sidjen]] stun a stregen an weder iinsteld sidjen.",
'undelete-header'            => "Luke uun't [[Special:Log/delete|logbuk för stregen sidjen]] efter stregen sidjen faan a leetst tidj.",
'undelete-search-title'      => 'Sjük stregen sidjen',
'undelete-search-box'        => 'Sjük stregen sidjen',
'undelete-search-prefix'     => 'Sjük sidjen, diar began mä:',
'undelete-search-submit'     => 'Sjük',
'undelete-no-results'        => "Uun't archiif wiar nian paasen sidjen.",
'undelete-filename-mismatch' => 'Det wersjuun faan $1 koon ei weder iinsteld wurd. Di dateinööm paaset ei.',
'undelete-bad-store-key'     => "Det dateiwersjuun faan $1 koon ei weder iinsteld wurd. Det datei wiar al föör't striken ei muar diar.",
'undelete-error'             => "Bi't weder iinstelen faan det sidj as wat skiaf gingen.",
'undelete-error-short'       => "Bi't weder iinstelen faan det datei $1 as wat skiaf gingen.",
'undelete-error-long'        => "Bi't weder iinstelen faan en datei as wat skiaf gingen:

$1",
'undelete-show-file-confirm' => 'Wel dü würelk det stregen wersjuun faan det datei „<nowiki>$1</nowiki>“ faan di $2, am a klook $3 uunluke?',
'undelete-show-file-submit'  => 'Ja',

# Namespace form on various pages
'namespace'      => 'Noomerüm:',
'invert'         => 'Ütwool amkiire',
'blanknamespace' => '(Side)',

# Contributions
'contributions'       => 'Brükertujeefte',
'contributions-title' => 'Brükertujeefte foon "$1"',
'mycontris'           => 'Äine tujeefte',
'contribsub2'         => 'For $1 ($2)',
'uctop'               => '(aktuäl)',
'month'               => 'än moune:',
'year'                => 'bit iir:',

'sp-contributions-newbies'  => 'Wis bloot tujeefte foon naie brükere',
'sp-contributions-blocklog' => 'Spär-logbök',
'sp-contributions-uploads'  => 'Huuchsjüürd bilen',
'sp-contributions-logs'     => 'logbuken',
'sp-contributions-talk'     => 'diskusjuun',
'sp-contributions-search'   => 'Säkj eefter brükertujeefte',
'sp-contributions-username' => 'IP-adräs unti brükernoome',
'sp-contributions-toponly'  => 'Bluas aktuel wersjuunen wise',
'sp-contributions-submit'   => 'Säike',

# What links here
'whatlinkshere'            => 'Links aw jüdeer sid',
'whatlinkshere-title'      => 'Side, da aw "$1" ferlinke',
'whatlinkshere-page'       => 'sid:',
'linkshere'                => "Da füliende side ferlinke aw '''„[[:$1]]“''':",
'nolinkshere'              => 'Nian sidj ferwiset üüb [[:$1]]',
'isredirect'               => 'widerliidjingssid',
'istemplate'               => 'forlåågeninbining',
'isimage'                  => 'Dåtäilink',
'whatlinkshere-prev'       => '{{PLURAL:$1|leesten|leeste $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|näisten|näiste $1}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => 'Widerliidjinge $1',
'whatlinkshere-hidetrans'  => 'Forlåågenönjbininge $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-hideimages' => '$1 dåtäilinks',
'whatlinkshere-filters'    => 'Filtere',

# Block/unblock
'blockip'                         => 'IP-adräs/brüker späre',
'ipboptions'                      => '2 stüne:2 hours,1 däi:1 day,3 deege:3 days,1 wääg:1 week,2 wääge:2 weeks,1 moune:1 month,3 moune:3 months,6 moune:6 months,1 iir:1 year,suner iinje:infinite',
'unblocked-id'                    => 'Sperang $1 as apheewen',
'blocklist'                       => 'Spärd brükere',
'ipblocklist'                     => 'Spärd brükere',
'ipblocklist-legend'              => 'Spärd brükere fine',
'expiringblock'                   => 'iinjet aw e $1 am e klook $2',
'blocklink'                       => 'späre',
'unblocklink'                     => 'frijeewe',
'change-blocklink'                => 'Späring änre',
'contribslink'                    => 'tujeefte',
'emaillink'                       => 'e-mail schake',
'autoblocker'                     => 'Automatische spär, deer dü en gemiinsoom IP-adräs ma [[User:$1|brüker:$1]] brükst. Grün foon brükerspär: „$2“.',
'blocklogpage'                    => 'Brükerspär-logbök',
'blocklogentry'                   => 'spärd „[[$1]]“ for di tidrüm: $2 $3',
'reblock-logentry'                => 'änerd jü spär for „[[$1]]“ for di tidrüm: $2 $3',
'blocklogtext'                    => "Detdiar as det logbuk auer sperangen an apheewen sperangen faan brükernöömer an IP-adresen.
Automaatisk sperd IP-adresen wurd ei uunwiset.
Luke bi't [[Special:BlockList|sperlist]] för aal jo aktuel speren.",
'unblocklogentry'                 => 'heet jü späre foon „$1“ aphääwen',
'block-log-flags-anononly'        => 'bloot anonyme',
'block-log-flags-nocreate'        => 'Måågen foon brükerkonte spärd',
'block-log-flags-noautoblock'     => 'autoblock deaktiviird',
'block-log-flags-noemail'         => 'e-mail-fersiinjing spärd',
'block-log-flags-nousertalk'      => 'mötj äine diskusjoonssid ai beårbe',
'block-log-flags-angry-autoblock' => 'ütbrååt autoblock aktiviird',
'block-log-flags-hiddenname'      => 'brükernoome ferstäägen',
'range_block_disabled'            => 'Jü möölikhäid, hiilj adräsrüme tu spären, as ai aktiviird.',
'ipb_expiry_invalid'              => 'Jü önjjääwen duur as üngülti.',
'ipb_expiry_temp'                 => 'Ferstäägen brükernoome-späre schan pärmanänt weese.',
'ipb_hide_invalid'                => 'Ditheer konto koon ai unerdrükd wårde, deer dåt tufoole beårbinge apwist.',

# Move page
'move-page'             => 'Ferschüw $1',
'move-page-legend'      => 'Sid ferschüwe',
'movepagetext'          => "Ma dideere formulaar koost de en sid ambenååme (masamt åle färsjoone).
Di üülje tiitel wårt tu di naie widerliidje.
Dü koost widerliidjinge, da ap e originooltiitel ferlinke, automatisch korrigiire lätje.
For di fål dåt dü dåt ai dääst, präiw aw [[Special:DoubleRedirects|dööwelte]] unti [[Special:BrokenRedirects|önjstööge widerliidjinge]].
Dü bast deerfor feroontuurdlik, dåt links fortönj ap dåt koräkt muul wise.

Jü sid wårt '''ai''' ferschääwen, wan dåt ål en sid ma di seelew noome jeeft,
süwid jüdeer ai lääsi unti en widerliidjing suner färsjoonshistoori as. Dåtdeer bedjüset,
dåt dü jü sid tubääg ferschüwe koost, wan dü en fäägel mååged heest. Dü koost
deeriinj niinj sid ouerschriwe.

'''Woorschouing!'''
Jü ferschüwing koon widlingende än ünfermousene fülie for beliifte side heewe.
Dü schöist deerfor da konsekwänse ferstönjen heewe, iir dü baiblafst.",
'movepagetalktext'      => "Jü deertu hiirende diskusjoonssid wård, süwid deer, maferschääwen, '''unti dåt moost weese:'''
*Deer bestoont ål en diskusjoonssid ma dideere noome, unti
*dü wäälst jü uner stönjene opsjoon ouf.

Önj dadeere fåle möist dü, wan wansched, di önjhålt foon jü sid foon hönj ferschüwe unti tuhuupefääre.

Hål di '''naie''' tiitel uner '''muul''' önjdreege, deeruner jü ambenååming hål '''begrüne.'''",
'movearticle'           => 'Sid ferschüwe:',
'newtitle'              => 'Müülj:',
'move-watch'            => 'Lök eefter jüdeer sid',
'movepagebtn'           => 'Sid ferschüwe',
'pagemovedsub'          => 'Ferschüwing luket',
'movepage-moved'        => "'''Jü sid „$1“ wörd eefter „$2“ ferschääwen.'''",
'articleexists'         => 'Uner dideere noome bestoont ål en sid. Wääl hål en nai noome.',
'talkexists'            => 'Jü sid seelew wörd erfolchrik ferschääwen, ouers jü deertu hiirende diskusjoonssid ai, deer ål iinj ma di nai tiitel bestoont. Glik hål da önjhålte foon hönj ouf.',
'movedto'               => 'ferschääwen eefter',
'movetalk'              => 'Jü diskusjoonssid maferschüwe, wan möölik',
'movepage-page-moved'   => 'Det sidj $1 as efter $2 fersköwen wurden.',
'movepage-page-unmoved' => 'Det sidj $1 küd ei efter $2 fersköwen wurd.',
'movepage-max-pages'    => 'Diar kön ei muar üs {{PLURAL:$1|sidj|sidjen}} fersköwen wurd. Muar sidjen kön ei automaatisk fersköwen wurd.',
'movelogpage'           => 'Ferschüwingslogbök',
'movereason'            => 'Begrüning:',
'revertmove'            => 'tubääg ferschüwe',

# Export
'export' => 'Side äksportiire',

# Namespace 8 related
'allmessagesname'    => 'Nööm',
'allmessagesdefault' => 'Standard tekst',

# Thumbnails
'thumbnail-more'  => 'fergrutre',
'thumbnail_error' => "Bi't maagin faan't sümnaielbil ging wat skiaf: $1",

# Special:Import
'import'                  => 'Side importiire',
'importinterwiki'         => 'Transwiki import',
'import-interwiki-submit' => 'Import',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Din brükersid',
'tooltip-pt-mytalk'               => 'Din diskusjoonssid',
'tooltip-pt-preferences'          => 'Äine önjstalinge',
'tooltip-pt-watchlist'            => 'List foon eefterkiikede side',
'tooltip-pt-mycontris'            => 'List foon din tujeefte',
'tooltip-pt-login'                => 'Ham önjmälde wårt wälj hål sänj, ouers as niinj plächt.',
'tooltip-pt-anonlogin'            => 'Ham önjmälde wårt wälj hål sänj, ouers as niinj plächt.',
'tooltip-pt-logout'               => 'Oufmälde',
'tooltip-ca-talk'                 => 'Diskusjoon ouer jü sidinhålt',
'tooltip-ca-edit'                 => 'Sid beårbe. Hål for dåt spikern jü forlökfunksjoon brüke.',
'tooltip-ca-addsection'           => 'Nai oufsnaas begane',
'tooltip-ca-viewsource'           => 'Jüdeer sid wårt uner ferbading hülen. Di kwältäkst koon önjkiiked wårde.',
'tooltip-ca-history'              => 'Iire färsjoone foon jüdeer sid',
'tooltip-ca-protect'              => 'Jüdeer sid schütse',
'tooltip-ca-unprotect'            => 'Seekerang feranere',
'tooltip-ca-delete'               => 'Jüdeer sid tunintemååge',
'tooltip-ca-move'                 => 'Jüdeer sid ferschüwe',
'tooltip-ca-watch'                => 'Jüdeer sid tu jü persöönlike eefterkiiksid baitufäige',
'tooltip-ca-unwatch'              => 'Jüdeer sid foon jü persöönlike eefterkiikliste wächnaame',
'tooltip-search'                  => '{{SITENAME}} döörsäke',
'tooltip-search-go'               => 'Gung matiinjs tu jü sid, jü äksakt di injääwen noome önjtspreecht.',
'tooltip-search-fulltext'         => 'Säk eefter side, da dideere täkst öjnthüülje',
'tooltip-p-logo'                  => 'Besäk jü hoodsid',
'tooltip-n-mainpage'              => 'Hoodsid wise',
'tooltip-n-mainpage-description'  => 'Hoodsid besäke',
'tooltip-n-portal'                => 'Ouer dåt portåål, wat dü düünj koost, weer wat tu finen as',
'tooltip-n-currentevents'         => 'Äädergrüninformasjoone tu aktuäle schaiinge',
'tooltip-n-recentchanges'         => 'List foon da leeste änringe önj {{SITENAME}}',
'tooltip-n-randompage'            => 'Tufali sid',
'tooltip-n-help'                  => 'Heelpsid wise',
'tooltip-t-whatlinkshere'         => 'List foon ål da side, da heer jurt wise',
'tooltip-t-recentchangeslinked'   => 'Leest änringen bai side, da foon heer ferlinkd san',
'tooltip-feed-rss'                => 'RSS-feed for jüdeer sid',
'tooltip-feed-atom'               => 'Atom-feed for jüdeer sid',
'tooltip-t-contributions'         => 'List foon tujeefte foon dideere brüker önjkiike',
'tooltip-t-emailuser'             => 'En e-mail tu dideere brüker siinje',
'tooltip-t-upload'                => 'Bile huuchschake',
'tooltip-t-specialpages'          => 'List foon ål da spesjåålside',
'tooltip-t-print'                 => 'Prantönjsacht foon jüdeer sid',
'tooltip-t-permalink'             => 'Wååri link tu jüdeer sidfärsjoon',
'tooltip-ca-nstab-main'           => 'Sidinhålt wise',
'tooltip-ca-nstab-user'           => 'Brükersid wise',
'tooltip-ca-nstab-special'        => 'Jüdeer sid as en spetsjåålsid. Jü koon ai beåarbed wårde.',
'tooltip-ca-nstab-project'        => 'Portoolsid wise',
'tooltip-ca-nstab-image'          => 'Dååtäisid wise',
'tooltip-ca-nstab-template'       => 'Forlååge wise',
'tooltip-ca-nstab-help'           => 'Heelpsid wise',
'tooltip-ca-nstab-category'       => 'Kategoriisid wise',
'tooltip-minoredit'               => 'Jüdeer änring as latj markiire.',
'tooltip-save'                    => 'Feranerangen seekre',
'tooltip-preview'                 => 'Forlök foon da änringe bai jüdeer sid. Hål for dåt spikern brüke!',
'tooltip-diff'                    => 'Änringe bai di täkst wise',
'tooltip-compareselectedversions' => 'Ferschääl twasche tou ütwäälde färsjoone foon jüdeer sid wise.',
'tooltip-watch'                   => 'Fäig jüdeer sid foon din eefterkiikliste tubai',
'tooltip-rollback'                => 'Mååget åle leeste änringe foon jü sid, da foon di lik brüker fornümen wörden san, döör iinj klik tuninte.',
'tooltip-undo'                    => 'Mååget bloot jüdeer iinje änring tuninte än wist dåt resultoot önj e forlöksid önj, deerma önj e tukuupefootingssid en begrüning önjjääwen wårde koon.',
'tooltip-summary'                 => 'Faade det kurt tuup',

# Browsing diffs
'previousdiff' => '← Tu di leest färsjoonsferschääl',
'nextdiff'     => 'Tu di näist färsjoonsferschääl →',

# Media information
'file-info-size' => '$1 × $2 pixele, dååtäigrutelse: $3, MIME-typ: $4',
'file-nohires'   => 'Niinj huuger apliising as deer.',
'svg-long-desc'  => 'SVG-dåtäi, basisgrutelse: $1 × $2 pixel, dåtäigrutelse: $3',
'show-big-image' => 'Färsjon önj huuger apliising',

# Bad image list
'bad_image_list' => 'Formååt:

Bloot rae, da ma en * begane, wårde ütwjarted. As jarste eefter dåt * mötj en link aw en ai wansched dååtäi stönje.
Deeraw föliende sidelinke önj dåtseelwi ra definiire ütnååme, önj di kontäkst weerfoon jü dååtäi duch tu schüns kaame mötj.',

# Metadata
'metadata'          => 'Metadååte',
'metadata-help'     => 'Jüdeer dåtäi önjthålt widere informasjoon, jü önj e räigel foon jü digitoolamera unti di ferwånd scanner ståme. Döör eefterdräägen beårbing foon jü originooldåtäi koone hu detaile feränret wörden weese.',
'metadata-expand'   => 'Ütbriidede detaile wise',
'metadata-collapse' => 'Ütbriidede detaile fersteege',
'metadata-fields'   => 'Da füliende fälje foon da EXIF-metadååte, da önj dideere MediaWiki-systeemtäkst önjjääwen san, wårde aw bilbeschriwingsside ma inklapede metadååtetabäle wist. Widere wårde standardmääsi ai wised.
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

# External editor support
'edit-externally'      => 'Jüdeer dåtäi ma en äkstärn prugram beårbe',
'edit-externally-help' => '(Sii da [//www.mediawiki.org/wiki/Manual:External_editors Installationsanweisungen] for widere Informasjoon)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'åle',
'namespacesall' => 'åle',
'monthsall'     => 'åle',
'limitall'      => 'åle',

# Table pager
'ascending_abbrev'  => 'ap',
'descending_abbrev' => 'deel',

# Auto-summaries
'autosumm-blank'   => 'Det sidj as leesag maaget wurden.',
'autosumm-replace' => 'Di tekst as ütjbütjet wurden mä "$1"',
'autoredircomment' => 'Sidj tu [[$1]] widjerfeerd',
'autosumm-new'     => 'Det sidj as nei uunlaanj wurden: "$1"',

# Watchlist editing tools
'watchlisttools-view' => 'Eefterkiiklist: änringe',
'watchlisttools-edit' => 'normåål beårbe',
'watchlisttools-raw'  => 'Listeformoot beårbe (import/äksport)',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Paase üüb:\'\'\' Di sortiarkai "$2" auerskraft di ual sortiarkai "$1"',

# Special:Version
'version-software'         => 'Instaliird software',
'version-software-product' => 'Produkt',
'version-software-version' => 'Färsjoon',

# Special:SpecialPages
'specialpages' => 'Spetsjåålside',

# External image whitelist
'external_image_whitelist' => " #Feranere detheer rä ei<pre>
#Dialen faan reguleer ütjdrüker (tesken a tiakens //) kön oner iinden wurd.
#Jo wurd do mä URLs faan ekstern bilen ferglikt.
#Huar't auerianstemet, woort det bil uunwiset, ööders bluas en ferwis üüb det bil.
#Räen mä en # bi a began san komentaaren.
#Grat- an letjskriiwang woort ei onerskääst.

#Skriiw dialen faan reguleer ütjdrüker auer detheer rä. Feranere detheer rä ei</pre>",

# Special:Tags
'tag-filter' => '[[Special:Tags|Kääntiaken]] filter:',

# HTML forms
'htmlform-selectorother-other' => 'Oudere',

# New logging system
'logentry-delete-delete'              => '$1 hää det sidj $3 stregen',
'logentry-delete-restore'             => '$1 hää det sidj $3 weder iinsteld',
'logentry-delete-event'               => '$1 hää det uunlukin feranert faan {{PLURAL:$5|en logbuk iindrach|$5 logbuk iindracher}} üüb $3: $4',
'logentry-delete-revision'            => '$1 hää det uunlukin feranert faan {{PLURAL:$5|ian wersjuun|$5 wersjuunen}} faan det sidj $3: $4',
'logentry-delete-event-legacy'        => '$1 hää det uunlukin feranert faan logbuk iindracher üüb $3',
'logentry-delete-revision-legacy'     => '$1 hää det uunlukin feranert faan wersjuunen faan det sidj $3',
'logentry-suppress-delete'            => '$1 hää det sidj $3 wechtrakt',
'logentry-suppress-event'             => '$1 hää stalswigin det uunlukin feranert faan {{PLURAL:$5|en logbuk iindrach|$5 logbuk iindracher}} üüb $3: $4',
'logentry-suppress-revision'          => '$1 hää stalswigin det uunlukin feranert faan {{PLURAL:$5|ian wersjuun|$5 wersjuunen}} faan det sidj $3: $4',
'logentry-suppress-event-legacy'      => '$1 hää stalswigin det uunlukin fernanert faan logbuk iindracher üüb $3',
'logentry-suppress-revision-legacy'   => '$1 hää stalswigin det uunlukin feranert faan wersjuunen faan det sidj $3',
'revdelete-uname-hid'                 => 'brükernoome ferstäägen',
'revdelete-uname-unhid'               => 'brükernoome frijääwen',
'revdelete-restricted'                => 'gränse jüle uk for administratoore',
'revdelete-unrestricted'              => 'gränse for administratoore wächnümen',
'logentry-move-move'                  => '$1 hää det sidj $3 efter $4 fersköwen.',
'logentry-move-move-noredirect'       => '$1 hää det sidj $3 efter $4 saner widjerfeerang fersköwen.',
'logentry-move-move_redir'            => '$1 hää det sidj $3 efter $4 fersköwen an diarbi en widjerfeerang auerskrewen.',
'logentry-move-move_redir-noredirect' => '$1 hää det sidj $3 efter $4 fersköwen an diarbi en widjerfeerang auerskrewen saner salew en widjerfeerang uuntuleien.',

);
