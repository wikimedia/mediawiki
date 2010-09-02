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

$linktrail = '/^([a-zäöüßåāđē]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Linke unerstrike:',
'tog-highlightbroken'         => 'Linke aw ai bestönjene side beklåme <a href="" class="new">biispel</a> (alternatiiwe: ås dideere<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Täkst ås blokseeting',
'tog-hideminor'               => 'Latje änringe fersteege',
'tog-hidepatrolled'           => 'Latje änringe fersteege',
'tog-newpageshidepatrolled'   => 'Kontroliirde side aw e list "Naie side" fersteege',
'tog-extendwatchlist'         => 'Ütwidede wåchelist tun wisen foon åle änringe',
'tog-usenewrc'                => 'Ütwidede deerstaling foon da "Leeste Änringe" (brükt JavaScript)',
'tog-numberheadings'          => 'Ouerschrafte automatisch numeriire',
'tog-showtoolbar'             => 'Beårbe-wärktjüch wise',
'tog-editondblclick'          => 'Side ma dööweltklik beårbe (brükt JavaScript)',
'tog-editsection'             => 'Ferbininge tun wisen foon änkelte oufsnaase',
'tog-editsectiononrightclick' => 'Änkelte stöögne ma ruchts kliken beårbe (brükt JavaScript)',
'tog-showtoc'                 => 'Wis en inhåltsferteeknis for side ma mäs ås trii ouerschrafte',
'tog-rememberpassword'        => 'Aw diheere komputer foon duur önjmälde (maksimool for {{PLURAL:$1|däi|deege}})',
'tog-watchcreations'          => 'Seelew måågede side automatisch bekiike',
'tog-watchdefault'            => 'Seelew änrede side automaatisch bekiike',
'tog-watchmoves'              => 'Seelew ferschääwene side automaatisch bekiike',
'tog-watchdeletion'           => 'Seelew wächhåålde side automatisch bekiike',
'tog-previewontop'            => 'Forbekiiken boowen dåt beårbingswaning wise',
'tog-previewonfirst'          => 'Bai dåt jarst beårben åltens dåt forbekiiken wise',
'tog-nocache'                 => 'Sidecache foon e browser deaktiwiire',
'tog-enotifwatchlistpages'    => 'Bai änringe önj bekiikede side E-maile siinje',
'tog-enotifusertalkpages'     => 'Bai änringe tu min brüker-diskusjoonssid E-Maile siinje',
'tog-enotifminoredits'        => 'Uk bai latje änringe tu bekiikede side E-maile siinje',
'tog-enotifrevealaddr'        => 'Min E-mail-adräs önj tising-E-maile wise',
'tog-shownumberswatching'     => 'Wis di tål foon wåchende brükere',
'tog-oldsig'                  => 'Forbekiik foon e aktuäle signatuur:',
'tog-fancysig'                => 'Signatuur behoonle ås wikitäkst',
'tog-externaleditor'          => 'Gewöönlik äksterne ediitor for färsjoonsunerschiise brüke (bloot for ekspärte, deer mönje spetsjäle önjstalinge aw di äine kompjuuter fornümen wårde)',
'tog-externaldiff'            => 'Gewöönlik äkstern program for färsjoonsunerschiise brüke (bloot for ekspärte, deer mönje spetsjäle önjstalinge aw di äine kompjuuter fornümen wårde)',
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
'sunday'        => 'saandi',
'monday'        => 'moundi',
'tuesday'       => 'täisdi',
'wednesday'     => 'weensdi',
'thursday'      => 'törsdi',
'friday'        => 'fraidi',
'saturday'      => 'saneene',
'sun'           => 'sd',
'mon'           => 'mo',
'tue'           => 'tä',
'wed'           => 'we',
'thu'           => 'tö',
'fri'           => 'fr',
'sat'           => 'se',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'marts',
'april'         => 'april',
'may_long'      => 'moi',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'august',
'september'     => 'septämber',
'october'       => 'oktoober',
'november'      => 'nowämber',
'december'      => 'detsämber',
'january-gen'   => 'januar',
'february-gen'  => 'februar',
'march-gen'     => 'marts',
'april-gen'     => 'april',
'may-gen'       => 'moi',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'august',
'september-gen' => 'septämber',
'october-gen'   => 'oktoober',
'november-gen'  => 'nowämber',
'december-gen'  => 'detsämber',
'jan'           => 'jan.',
'feb'           => 'feb.',
'mar'           => 'mar.',
'apr'           => 'apr.',
'may'           => 'moi',
'jun'           => 'jun.',
'jul'           => 'jul.',
'aug'           => 'aug.',
'sep'           => 'sep.',
'oct'           => 'okt.',
'nov'           => 'nov.',
'dec'           => 'det.',

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
'category-file-count'            => '↓ {{PLURAL:$2|Jüdeer kategorii önjthålt füliende dootäi:|{{PLURAL:$1|Füliende dootäi as iinj foon inåål $2 side önj jüdeer kategorii:|Deer wårde $1 foon inåål $2 dootäie önj jüdeer kategorii wised:}}}}',
'category-file-count-limited'    => 'Füliende {{PLURAL:$1|Dootäi as|$1 Dootäie san}} önj jüdeer kategorii önjthülen:',
'listingcontinuesabbrev'         => '(fortseeting)',
'index-category'                 => 'Indisiirde side',
'noindex-category'               => 'Ai indisiirde side',

'mainpagetext'      => "'''MediaWiki wörd ma erfolch instaliird.'''",
'mainpagedocfooter' => 'Heelp tu jü benjüting än konfigurasjoon foon e Wiki-software fanst dü önj dåt [http://meta.wikimedia.org/wiki/Help:Contents Benutzerhandbuch].


== Startheelpe ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Liste der Konfigurationsvariablen]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki-FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailingliste neuer MediaWiki-Versionen]',

'about'         => 'Ouer',
'article'       => 'Sid',
'newwindow'     => '(wårt önj en nai waning änäämmååged)',
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
'qbspecialpages' => ' Spetsjåålside',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Stuk haanetufoue',
'vector-action-delete'           => 'Sleeke',
'vector-action-move'             => 'Ferschüwe',
'vector-action-protect'          => 'Önj ferbading hüülje',
'vector-action-undelete'         => 'Wi mååge',
'vector-action-unprotect'        => 'Frijeewe',
'vector-simplesearch-preference' => 'Erwiderde säkforsliike aktiwiire (bloot vector)',
'vector-view-create'             => 'Mååge',
'vector-view-edit'               => 'Beårbe',
'vector-view-history'            => 'Dootäifärsjoone',
'vector-view-view'               => 'Lees',
'vector-view-viewsource'         => 'Kwältäkst önjkiike',
'actions'                        => 'Aksjoone',
'namespaces'                     => 'Noomerüme:',
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
'info_short'        => 'Informasjoon',
'printableversion'  => 'Prantfärsjoon',
'permalink'         => 'Permanänten link',
'print'             => 'Prante',
'edit'              => 'Beårbe',
'create'            => 'Mååge',
'editthispage'      => 'Sid beårbe',
'create-this-page'  => 'Sid mååge',
'delete'            => 'Strike',
'deletethispage'    => 'Jüdeer sid strike',
'undelete_short'    => '{{PLURAL:$1|1 färsjoon|$1 färsjoone}} widermååge',
'protect'           => 'Önj ferbading hüülje',
'protect_change'    => 'änre',
'protectthispage'   => 'Sid önj ferbading hüülje',
'unprotect'         => 'Frijeewe',
'unprotectthispage' => 'Ferbading aphääwe',
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
'projectpage'       => 'Brükersid wise',
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
'viewcount'         => 'Aw jüdeer sid as  {{PLURAL:$1|once|$1 times}} tugram.',
'protectedpage'     => 'Önj ferbading hülen sid',
'jumpto'            => 'Schaft tu:',
'jumptonavigation'  => 'Navigasjoon',
'jumptosearch'      => 'säk',
'view-pool-error'   => 'Önjschüliing, da särwere san nütutids ouerlååsted.
Tufoole brükere fersäke, jüdeer sid tu besäken.
Wees sü gödj än täiw hu minuute, iir dü dåt nuch iinjsen ferseechst.

$1',

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
'edithelppage'         => 'Help:beårbe',
'helppage'             => 'Help:Inhåltsfertiiknis',
'mainpage'             => 'Hoodsid',
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
'retrievedfrom'           => 'Foon „$1“',
'youhavenewmessages'      => 'Dü hääst $1 aw din diskusjoonssid ($2).',
'newmessageslink'         => 'naie tisinge',
'newmessagesdifflink'     => 'leest änring',
'youhavenewmessagesmulti' => 'Dü hääst nai tisinge aw $1',
'editsection'             => 'beårbe',
'editsection-brackets'    => '[$1]',
'editold'                 => 'beårbe',
'viewsourceold'           => 'kwältakst wise',
'editlink'                => 'beårbe',
'viewsourcelink'          => 'kwältakst wise',
'editsectionhint'         => 'säksjoon beårbe: $1',
'toc'                     => 'inhåltsfertiiknis',
'showtoc'                 => 'wise',
'hidetoc'                 => 'ferbärje',
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

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'sid',
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
'filedeleteerror'      => 'Jü dootái "$1" köö ai sleeked wårde.',
'directorycreateerror' => 'Dåt fertiiknis "$1" köö ai mååged wårde.',
'filenotfound'         => 'Köö dootäi "$1" ai fine.',
'fileexistserror'      => 'Ai önjstånde eefter dootäi "$1" tu schriwen: dootäi bestoont ål',
'unexpected'           => 'Ünfermousene wjart: "$1"="$2".',
'formerror'            => 'Fäägel: köö jü form ai lääwere',
'badarticleerror'      => 'Jüdeer aksjoon koon ai aw jüdeer sid mååged wårde.',
'cannotdelete'         => 'Jü sid unti dootäi "$1" köö ai sleeked wårde.
Dåt as flicht ål sleeked foon huum ouders.',
'badtitle'             => 'Ferkiirde tiitel',
'badtitletext'         => 'Di tiitel foon jü anfrååged sid as üngülti, lääsi unti n üngültigen spräklink foon en ouder wiki.',
'perfcached'           => 'Da füliende doote ståme üt di cache än san möölikerwise ai aktuäl.',
'perfcachedts'         => 'Daheere doote ståme üt di cache, leest aktualisiiring: $2, klook $3.',
'querypage-no-updates' => "'''Jü aktualisiiringsfunksjoon for jüdeer sid as nütutids deaktiwiird. Da doote wårde tujarst ai fernaierd.'''",
'wrong_wfQuery_params' => 'Ferkiirde parameetere for wfQuery()<br />
Funksjoon: $1<br />
Ouffrååch: $2',
'viewsource'           => 'Kwältäkst önjkiike',
'viewsourcefor'        => 'for $1',
'actionthrottled'      => 'Aksjoonstål limitiird',
'actionthrottledtext'  => 'Dü hääst jüdeer aksjoon tu oofding bane en kort tidrüm ütfjard. Wees sü gödj än täiw en påår minuute än fersäk dåt et dan foon naien.',
'protectedpagetext'    => 'Jüdeer sid as for dåt beårben späred.',
'viewsourcetext'       => 'Dü koost jü kwäle foon jüdeer sid bekiike än kopiire.',
'protectedinterface'   => 'Jüdeer sid önjthålt takst for ju brükerouerfläche foon e software än as späred, am masbrük tu ferhanern.',
'editinginterface'     => "'''Woorschauing:''' Jüdeer sid önjthålt foon jü MesiaWiki-software bënjüteden takst.
Änringe wirke jam aw jü brükerouerfläche üt.
For ouerseetinge tånk deeram, jü önj di  [http://translatewiki.net/wiki/Main_Page?setlang=frr Translatewiki], dåt MediaWiki lokalisiiringsprojekt, döörtufäären.",
'sqlhidden'            => 'SCL-ouffrååg ferstäägen',
'cascadeprotected'     => 'Jüdeer sid as for beårbing spärd. Jü as önj {{PLURAL:$1|e füliende sid|da füliende side}}
önjbünen, {{PLURAL:$1|jü|da}} madels e kaskaadespäropsjoon önj febading hüüljen {{PLURAL:$1|as|san}}:
$2',
'namespaceprotected'   => "Dü hääst niinj beruchtiging, jü sid önj di '''$1'''-noomerüm tu beårben.",
'customcssjsprotected' => 'Dü bast ai beruchtiged, jüdeer sid tu beårben, deer jü tu da persöönlike önjstalinge foon en ouderen brüker hiirt.',
'ns-specialprotected'  => 'Spetsjåålside koone ai beårbed wårde.',
'titleprotected'       => 'En sid ma dideer noome koon ai önjläid wårde.
Jü späre wörd döör [[User:$1|$1]] ma grün "$2" önjruchted.',

# Virus scanner
'virus-badscanner'     => "Hiinje konfigurasjoon: ünbekånde fiirusscanner: ''$1''",
'virus-scanfailed'     => 'scan fäägelsloin (code $1)',
'virus-unknownscanner' => 'Ünbekånde fiirusscanner:',

# Login and logout pages
'logouttext'                 => "'''Dü bast nü oufmäded.'''

Dü koost {{SITENAME}} nü anonüüm widerbrüke, unti de wider uner diseelew unti en oudern benjüternoome [[Special:UserLogin|önjmälde]].
Påås aw, dåt hu side nuch wide koone, dåt dü önjmälded bast, sülung dü ai dan browsercache lääsimååged heest.",
'welcomecreation'            => '== Wäljkiimen, $1! ==

Din brükerkonto wörd önjruchted.
Ferjeet ai, din [[Special:Preferences|{{SITENAME}}-önjstalinge]] önjtupååsen.',
'yourname'                   => 'Brükernoome:',
'yourpassword'               => 'Pååsuurd:',
'yourpasswordagain'          => 'Tip pååsuurd nuch iinjsen:',
'remembermypassword'         => 'Aw diheere komputer foon duur önjmälde (maksimool for {{PLURAL:$1|däi|deege}})',
'yourdomainname'             => 'Din domain:',
'externaldberror'            => 'Önjtwider deer läit en fäägel bai jü äkstärn autentifisiiring for, unti dü möist din äkstärn brükerkonto äi aktualisiire.',
'login'                      => 'Önjmälde',
'nav-login-createaccount'    => 'Önjmälde',
'loginprompt'                => 'For jü önjmälding monje cookies aktiwiird weese.',
'userlogin'                  => 'Önjmälde / brükerkonte mååge',
'userloginnocreate'          => 'Önjmälde',
'logout'                     => 'Oufmälde',
'userlogout'                 => 'Oufmälde',
'notloggedin'                => 'Ai önjmälded',
'nologin'                    => "Dü hääst ai en brükerkonto? '''$1'''.",
'nologinlink'                => 'Nai brükerkonto anleede',
'createaccount'              => 'Brükerkonto anleede',
'gotaccount'                 => "Dú hääst ål en brükerkonto? '''$1'''.",
'gotaccountlink'             => 'Önjmälde',
'createaccountmail'          => 'ouer E-mail',
'createaccountreason'        => 'Grün:',
'badretype'                  => 'Da biise pååsuurde stime ai oueriinj.',
'userexists'                 => 'Dideer brükernoome as ål ferjääwen. Wees sü gödj en ouderen tu kiisen.',
'loginerror'                 => 'Fäägel bai önjmälding',
'createaccounterror'         => 'Brükerkonto köö ai mååged wårde: $1',
'nocookiesnew'               => 'Di benjütertugung wörd mååged, ouers dü bast ai önjmälded. {{SITENAME}} brükt for jüdeer funksjoon cookies.
Wees sü gödj än aktiwiir da än mäld de dan ma dan naien benjüternoome än dåt tuhiirend pååsuurd önj.',
'nocookieslogin'             => '{{SITENAME}} benjütet cookies tu e önjmälding foon da benjütere. Dü heest Cookis deaktiwiird.
Wees sü gödj än aktiwiir da än fersäk dåt wider.',
'noname'                     => 'Dü möist en gültigen brükernooem önjjeewe.',
'loginsuccesstitle'          => 'Önjmälding erfolchrik',
'loginsuccess'               => "'''Dü bast nü ås „$1“ bai {{SITENAME}} önjmälded.'''",
'nosuchuser'                 => 'Di brükernoome „$1“ bestoont ai.
Präiw jü schriwwise (grut-/latjschriwing beåchte) unti [[Special:UserLogin/signup|mäld de ås naie brüker önj]].',
'nosuchusershort'            => 'Deer as nåån brüker ma noome  "<nowiki>$1</nowiki>".
Präiw din ruchtschriwing.',
'nouserspecified'            => 'Dü schäät en brükernoome spesifisiire.',
'login-userblocked'          => 'Dideer brüker as spärd. Niinj ferloof tu önjmälding.',
'wrongpassword'              => 'Ferkiird pååsuurd önjjeewen.
Wees sü gödje än fersäk dåt nuch iinjsen.',
'wrongpasswordempty'         => 'Deer wörd niinj pååsuurd önjjääwen. Fersäk dåt foon naien.',
'passwordtooshort'           => 'Pååsuurde mönje tu t manst {{PLURAL:$1|1 tiiken|$1 tiikne}} lung weese.',
'password-name-match'        => 'Din pååsuurd mätj ferschääle foon dan brükernoome.',
'mailmypassword'             => 'Nai pååsuurd tusiinje',
'passwordremindertitle'      => 'Nai tidwise pååsuurd for {{SITENAME}}',
'noemail'                    => 'Deer as niinj e-mail-adräs bekånd for brüker "$1".',
'noemailcreate'              => 'Dü möist en gülti E-mail-adräs önjjeewe',
'passwordsent'               => 'En nai pååsuurd as sånd tu jü E-mail-adräs registriird for "$1".
Mälde wi önj eefter dü jü füngen heest.',
'blocked-mailpassword'       => 'Jü foon de ferwånde IP-adräs as for dåt änren foon side späred. Am en masbrük tu ferhanern, wórd jü möölikhäid tu dåt önjfråågen foon en nai pååsuurd uk späred.',
'eauthentsent'               => 'En bestääsiings-E-mäil wörd önj jü önjjääwen adräs sånd.

Iir en E-mail foon oudere brükere ouer jü E-mail-funksjoon emfångd wårde koon, mötj jü adräs än har wörklike tuhiirihäid tu dåtheer brükerkonto jarst bestääsied wårde. Wees sü gödj än befülie da haanewisinge önj di bestääsiings-E-mail.',
'throttled-mailpassword'     => 'Deer wörd önj da leeste {{PLURAL:$1|stün|$1 stüne}} ål en nai pååsuurd önjfrååged. Am en misbrük foon jüdeer funksjoon tu ferhanren, koon bloot {{PLURAL:$1|iinjsen pro stün|åle $1 stüne}} en nai pååsuurd önjfrååged wårde.',
'mailerror'                  => 'Fäägel bai dåt siinjen foon e E-mail: $1',
'acct_creation_throttle_hit' => '↓ Besäkere foon j"heer Wiki, da din IP-adräse brüke, heewe önj e leeste däi {{PLURAL:$1|1 benutserkonto|$1 benutzerkonte}} mååged, wat jü maksimool tuleet tål önj jüdeer tidperioode as.

Besäkere, da iüheer IP-adräse brüke, koone tu jü tutids niinj benutserkonte mör mrstellen.',
'emailauthenticated'         => 'Din e-mail-adräs word di $2 am e klook $3 bestääsied.',
'emailnotauthenticated'      => 'Din E-mail-adräs as nuch ai bestääsied. Da füliende E-mail-funksjoone stönje jarst eefter erfolchrike bestääsiing tu ferfäiging.',
'noemailprefs'               => 'Jeew en E-mail-adräs önj da önjstalinge önj, deerma da füliende funksjoone tu ferfäiging stönje.',
'emailconfirmlink'           => 'E-mail-adräs bestääsie (autäntifisiire).',
'invalidemailaddress'        => 'Jü e-mail adräs wörd ai aksäptiird, ouerdåt jü en üngülti formoot (ewentuäl üngültie tiikne) tu heewen scheent.
Wees sü gödj än jeef en koräkt adräs önj unti mäág dåt fäalj lääsi.',
'accountcreated'             => 'Benjüterkonto mååged',
'accountcreatedtext'         => 'Dåt benjüteraccount for $1 as mååged wörden.',
'createaccount-title'        => 'Måågen foon en benjüterkonto for {{SITENAME}}',
'createaccount-text'         => 'Deer wörd for de en benjüterkonto "$2" aw {{SITENAME}} ($4) mååged. Dåt automaatisch generiird pååsuurd for "$2" as "$3".
Dü schöist de nü önjmälde än dåt pååsuurd änre.

Fåls dåt benjüterkonto üt fersiinj önjläid wörd, koost dü jüdeer tising ignoriire.',
'usernamehasherror'          => 'Benjüternoome mötje niinj rütetiikne önjthüulje',
'login-throttled'            => 'Dü heest tu oofding fersoocht, di önjtumälden.
Wees sü gödj än täif, bit dü wider ferseechst.',
'loginlanguagelabel'         => 'Spräke: $1',
'suspicious-userlogout'      => 'Dan Oufmäldönjfrååge wörd ferwaigred, deer ja fermouslik foon en defäkte browser unti en cache-proxy sånd wörd.',

# JavaScript password checks
'password-strength'            => 'Taksiird pååsuurdstarkhäid: $1',
'password-strength-bad'        => 'HIINJ',
'password-strength-mediocre'   => 'döörsnitlik',
'password-strength-acceptable' => 'önjnaamboor',
'password-strength-good'       => 'gödj',
'password-retype'              => 'Pååsuurd widerhååle',
'password-retype-mismatch'     => 'Pååsuurde kaame ai oueriinj',

# Password reset dialog
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
'math_sample'     => 'Formel heer önjfäige',
'math_tip'        => 'Matemaatisch formel (LaTex)',
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
'savearticle'                      => 'Sid spikre',
'preview'                          => 'Forlök',
'showpreview'                      => 'Forlök wise',
'showlivepreview'                  => 'Live-forkiik',
'showdiff'                         => 'Änringe wise',
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
'blockednoreason'                  => 'niinj grün önjjääwen',
'blockedoriginalsource'            => "Di kwältakst foon '''$1''' wårt heer wised:",
'blockededitsource'                => "Di takst foon '''din änringe''' bit '''$1'''wårt heer wised:",
'whitelistedittitle'               => 'Tu t beårben as dåt ferplächted, önjmälde tu weesen.',
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
'noarticletext'                    => 'Jüdeer sid önjhålt uugenblaklik nuch nån täkst.
Dü koost dideere tiitel aw da ouder side [[Special:Search/{{PAGENAME}}|säke]],
<span class="plainlinks">önj da deertuhiirende [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} logböke säke] unti jüdeer sid [{{fullurl:{{FULLPAGENAME}}|action=edit}} beårbe]</span>.',
'noarticletext-nopermission'       => 'Jüdeer sid önjhålt uugenblaklik nuch nån täkst.
Dü koost dideere tiitel aw da oudre side [[Special:Search/{{PAGENAME}}|säke]],
unti<span class="plainlinks">önj da deertuhiirende [{{fullurl:{{#special:Log}}|page={{FULLPAGENAMEE}}}} logböke säke] </span>.',
'userpage-userdoesnotexist'        => 'Dåt benjüterkonto "$1" as ai deer.
Wees sü gödj än präif, weer dü jüdeer sid wörklik mååge/beårbe wååt.',
'userpage-userdoesnotexist-view'   => 'Benjüterkonto "$1" bestoont ai.',
'blocked-notice-logextract'        => 'Dideer benjüter as tutids spärd.
For informasjoon füliet di leeste üttooch üt dåt benjüterspär-logbök:',
'usercssyoucanpreview'             => "'''Tip:''' Brük di „{{int:showpreview}}“-knoop, am din nai CSS for dåt spiikern tu tästen.",
'userjsyoucanpreview'              => "'''Tip:''' Brük di „{{int:showpreview}}“-knoop, am din nai JavaScript for dåt spiikern tu tästen.",
'usercsspreview'                   => "'''Påås aw dåt dü bloot din brüker CSS forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'userjspreview'                    => "'''Påås aw dåt dü bloot din brüker JavaScript präiwest/forbekiikest.'''
'''Dåt as nuch ai spiikerd!'''",
'userinvalidcssjstitle'            => "''Woorschauing:''' Skin \"\$1\"jeeft dåt ai. Betånk, dåt brükerspetsiifische .css- än .js-side ma en latj bökstääw önjfånge mönje, ålsü biispelswise ''{{ns:user}}:Münsterkjarl/monobook.css'' önj stää foon ''{{ns:user}}:Münsterkjarl/Monobook.css''.",
'updated'                          => '(Änred)',
'note'                             => "'''Påås aw:'''",
'previewnote'                      => "'''Dåtheer as bloot en forlök, jü sid wörd nuch ai spikred!'''",
'previewconflict'                  => 'Dideer forbekiik jeeft di inhålt foon dåt boowerst takstfälj wider. Sü wårt jü sid ütsiinj, wan dü nü spiikerst.',
'editing'                          => 'Beårbe foon $1',
'editingsection'                   => 'Beårben foon $1 (oufsnaas)',
'editingcomment'                   => 'Beårben foon $1 (naien oufsnaas)',
'editconflict'                     => 'Beårbingskonflikt: $1',
'yourtext'                         => 'Din täkst',
'storedversion'                    => 'Spiikerd färsjoon',
'nonunicodebrowser'                => "'''Påås aw:''' Dan browser koon unicode-tiikne ai rucht ferårbe. Brük hål en oudern browser am side tu ferårben.",
'editingold'                       => "'''PÅÅS AW: Dü beårbest en üülj färsjoon foon jüdeer sid. \"
Wan dü spiikerst, wårde åle naiere färsjoone ouerschraawen.",
'yourdiff'                         => 'Ferschääle',
'copyrightwarning'                 => "''' Hål kopiir niinj webside, da ai din äine san, brük niinj uurhääwerruchtlik schütsede wärke suner ferloof foon di uurhääwer!'''<br />
Dü jeefst üs heerma dan tusååge, dåt dü di täkst '''seelew ferfooted''' hääst, dåt di täkst ålgemiingödj '''(public domain)''' as, unti dåt di '''uurhääwer''' sin '''tustiming''' jääwen heet. For di fål jüdeer täkst ål ouersweer ütdänj wörd, wis hål aw jü diskusjoonssid deeraw haane. <i>Beåcht hål, dåt åle {{SITENAME}}-tujeefte automaatisch uner jü „$2“ stönje (sii $1 for detaile). For di fål dü ai mååst, dåt diin årbe heer foon oudere feränred än språåt wårt, dan kröög ai aw „sid spikre“.</i>",
'longpagewarning'                  => "'''Woorschauing:''' Jüheer sid as $1 KB grut; hu browsere köön probleeme heewe, side tu beårben, da gruter san as 32 KB.
 Ouerläi hål, weer en ouddiiling foon e sid önj latjere oufsnaase möölik as.",
'longpageerror'                    => "'''FÄÄGEL: Di täkst, di dü tu spiikren ferseechst, as $11 KB grut. Dåt as gruter ås dåt tuleet maksimum foon $2 KB - spiikren ai möölik.'''",
'readonlywarning'                  => "'''PÅÅS AW: Jü dootenbånk wörd for unerhult spärd, sü dåt din änringe tutids ai spiikerd wårde koone.
Wees sü gödj än sääkre di täkst lokool aw din kompjuuter än fersäk tu n lääsern tidpunkt, da änringe tu ouerdreegen.'''.

Grün for jü späre: $1",
'protectedpagewarning'             => "'''PÅÅS AW: Jüheer sid wörd spärd. Bloot benjütere ma adminstrasjoonsruchte koone jü sid beårbe.'''
For informasjoon füliet di aktuäle logbökönjdråch:",
'semiprotectedpagewarning'         => "'''PÅÅS AW: Jüheer sid wörd spärd. Bloot benjütere ma adminstrasjoonsruchte koone jü sid beårbe.'''
For informasjoon füliet di aktuäle logbökönjdråch:",
'cascadeprotectedwarning'          => "'''Woorschauing:''' Jüheer sid wörd sü önj ferbading hülen, dåt jü bloot döör benjütere ma administraator-ruchte beårbed wårde koon. Jü as önj {{PLURAL:$1|jü füliend sid|da füliende side}} önjbünen, da döör jü kaskaadespäropsjoon önj ferbading hülen {{PLURAL:$1|wårt|wårde}}:",
'titleprotectedwarning'            => "'''PÅÅS AW: \"Dåt måågen foon side wörd spärd. Bloot benjütere ma [[Special:ListGroupRights|spetsjäle ruchte]] koone da side mååge.'''
For informasjoon füliet jü leest logbök-önjdråch:",
'templatesused'                    => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon jüdeer sid ferwånd:',
'templatesusedpreview'             => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon diheere sideforlök ferwånd:',
'templatesusedsection'             => '{{PLURAL:$1|Jü füliend forlååg wårt|Da füliende forlååge wårde}} foon dideer oufsnaas ferwånd:',
'template-protected'               => '(önj ferbading hülen iinj schriwen)',
'template-semiprotected'           => '(schriwschütsed for ünönjmäldede än naie brükere)',
'hiddencategories'                 => 'Jüdeer sid as lasmoot foon {{PLURAL:$1|1 ferstäägen kategorii|$1 ferstäägene kategoriie}}:',
'nocreatetitle'                    => 'Dåt måågeb foon naie side as begränsed.',
'nocreatetext'                     => 'Aw {{SITENAME}} wörd dåt måågen foon naie side begränsed.
Dü koost bestönjene side änre unti de [[Special:UserLogin|önjmälde unti mååg en account]].',
'nocreate-loggedin'                => 'Dü heest niinj beruchtiging, naie side tu måågen.',
'sectioneditnotsupported-title'    => 'Jü beårbing foon oufsnaase wårt ai unerstüted',
'sectioneditnotsupported-text'     => 'Jü beårbing foon oufsnaase wårt aw jüdeer beårbingssid ai stiped.',
'permissionserrors'                => 'Beruchtigingsfäägel',
'permissionserrorstext'            => 'Dü bast ai beruchted, jü aksjoon üttufäären. {{PLURAL:$1|grün|grüne}}:',
'permissionserrorstext-withaction' => 'Dü bast ai beruchtit, $2.
{{PLURAL:$1|grün|grüne}}:',
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
'currentrev'             => 'Aktäle Färsjoon.',
'currentrev-asof'        => 'Aktuäl färsjoon foon $2 am e klook $3',
'revisionasof'           => 'Färsjoon foon e klook $2, $3',
'revision-info'          => 'Färsjoon foon di $4 am e klook $5 foon $2',
'previousrevision'       => '← Näistålere Färsjoon',
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
'history-show-deleted'   => 'bloot sleekede färsjoone',
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
'rev-deleted-comment'         => '(Beårbingskomäntoor wächnümen)',
'rev-deleted-user'            => '(Brükernoome wächhååld)',
'rev-deleted-event'           => '(Logbökaksjoon wächhååld)',
'rev-deleted-user-contribs'   => '[Benjüternoome unti IP-adräs wächhååld - beårbing üt baidråge ferstäägen]',
'rev-deleted-text-permission' => "Judeer Färsjoon wörd '''sleeked'''.
Näre önjgoowen tu di sleekforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].",
'rev-deleted-text-unhide'     => "Jüdeer färsjoon wörd '''sleeked'''.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].
Ås en administraator koost dü  nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-suppressed-text-unhide'  => " Jüdeer färsjoon wörd '''unerdrükt'''.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/suppress|page={{FULLPAGENAMEE}}}} sleek-logbök].
Ås en administraator koost dü  nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-deleted-text-view'       => "Jüdeer Färsjoon wörd '''sleeked'''.
Ås administraator koost dü da wider önjkiike.
Näre önjgoowen tu di sleekforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].",
'rev-suppressed-text-view'    => "Jüdeer färsjoon wörd '''unerdruked'''.
Administratoore koone da önjkiike; ainkelthäide stönje önj dåt [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unerdrukings-logbök].",
'rev-deleted-no-diff'         => "Dü koost jüdeer ferschääl ai bekiike, dan iinj foon da änringe wörd '''sleeked'''. Näre önjgoowen tu di sleekforgung ås uk en begrüning fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].",
'rev-suppressed-no-diff'      => "Dü koost jüdeer ferschääl ai bekiike, dan iinj foon da änringe wörd '''sleeked'''.",
'rev-deleted-unhide-diff'     => "Iinj foon da änringe doon jüdeer ferschääl wörd '''sleeked'''.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].
Ås en administraator koost dü nuch [$1 jüdeer färsjoon bekiike], wan dü wider gunge mååst.",
'rev-suppressed-unhide-diff'  => "Iinj foon da färsjoone foon dåtdeer ferschääl wörd '''unerdruked'''.
Ainkelthäide fant huum önj dåt [{{fullurl:{{#special:Log}}/delete|page={{FULLPAGENAMEE}}}} unerdruk-logbök].
Ås en administraator koost dü  nuch [$1 dåtdeer ferschääl bekiike], wan dü wider gunge mååst.",
'rev-deleted-diff-view'       => "En Färsjoon foon dåtdeer färsjoonsferschääl wörd '''sleeked'''.
Ås administraator koost dü dåt färsjoonsferschääl siinj.
Näre önjgoowen fant huum önj dåt [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sleek-logbök].",
'rev-suppressed-diff-view'    => "Iinj foon da färsjoone foon dåtdeer färsjoonsferschääl wörd '''unerdruked'''.
Administratoore koone dåtheer färsjoonsferschääl siinj; ainkelthäide stönje önj dåt [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} unerdrukings-logbök].",
'rev-delundel'                => 'wis/fersteeg',
'rev-showdeleted'             => 'wise',
'revisiondelete'              => 'Färsjoone sleeke/widermååge',
'revdelete-nooldid-title'     => 'Niinj färsjoon önjjääwen',
'revdelete-nologtype-title'   => 'Niinj logtyp önjjääwen',
'revdelete-nologtype-text'    => 'Deer wörd niinj logtüüp for jüheer aksjoon önjjääwen.',
'revdelete-nologid-title'     => 'Üngülti logönjdråch',
'revdelete-nologid-text'      => 'Deer wör niinj logtüüp ütkiisd unti di kiisde logtüüp bestoont ai.',
'revdelete-no-file'           => 'Jü önjjääwen dootäi bestoont ai.',
'revdelete-show-file-confirm' => 'Bast dü sääker, dåt de jü sleeked färsjoon foon e dootäi „<nowiki>$1</nowiki>“ foon e $2 am e klook $3 önjsiinj wäät?',
'revdelete-show-file-submit'  => 'Jåå',
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
'revdelete-suppress'          => 'Grün foon dåt sleeken uk for administratoore fersteege',
'revdelete-unsuppress'        => 'Gränse for wi måågede färsjoone wächnaame',
'revdelete-log'               => 'Begrüning:',
'revdelete-submit'            => 'Aw {{PLURAL:$1|kiisd färsjoon|kiisde färsjoone}} önjwiinje',
'revdelete-logentry'          => 'heet jü färsjoonsönjsicht foon "[[$1]]" änred',
'logdelete-logentry'          => 'heet jü sachtboorhäid foon "[[$1]]" änred',
'revdelete-success'           => "'''Jü färsjoonsönjsicht wörd aktualisiird.'''",
'revdelete-failure'           => "'''Jü färsjoonsönjsicht köö ai aktualisiird wårde:'''",
'logdelete-success'           => "'''Logbökönjsicht ma erfolch aktualisiird.'''",
'logdelete-failure'           => "'''Logböksachtboorhäid köö ai änred wårde:'''
$1",
'revdel-restore'              => 'sichtboorhäid änre',
'revdel-restore-deleted'      => 'sleekede färsjoone',
'revdel-restore-visible'      => 'sachtboore färsjoone',
'pagehist'                    => 'Färsjoonshistoori',
'deletedhist'                 => 'Sleekede färsjoone',
'revdelete-content'           => 'wat önj e side stoont',
'revdelete-summary'           => 'tuhuupefootings-komäntoor',
'revdelete-uname'             => 'brükernoome',
'revdelete-restricted'        => 'gränse jüle uk for administratoore',
'revdelete-unrestricted'      => 'gränse for administratoore wächnümen',
'revdelete-hid'               => 'fersteegen $1',
'revdelete-unhid'             => 'mååged $1 wi sachtboor',
'revdelete-log-message'       => '$1 for $2 {{PLURAL:$2|färsjoon|färsjoone}}',
'logdelete-log-message'       => '$1 for $2 {{PLURAL:$2|logbökönjdräch|logbökönjdreege}',
'revdelete-hide-current'      => 'Fäägel bai t fersteegen foon di önjdråch foon e klook $1, $2; ditheer as jü aktuäl färsjoon,
jü koon ai ferstäägen wårde.',
'revdelete-show-no-access'    => 'Fäägel bai t wisen foon di önjdråch foon $1, e klook $2: diheer önjdråch wörd ås "begränsed" markiird.
Dü heest deeraw nåån tugraawe.',
'revdelete-otherreason'       => 'Ouderen/tubaikaamenden grün:',
'revdelete-reasonotherlist'   => 'Ouderen grün',
'revdelete-edit-reasonlist'   => 'Sleekgrüne beårbe',

# Suppression log
'suppressionlog' => 'Oversight-logbök',

# History merging
'mergehistory'                  => 'Fersjoonshistoorie feriine',
'mergehistory-header'           => 'Ma jüdeer spetsjåålsid koost dü jü färsjoonshistoori foon en jurtkamstsid ma jü färsjoonshistoori foon en müüljsid feriine.
Stal sääker, dåt jü färsjoonshistoori foon en sid histoorisch koräkt as.',
'mergehistory-box'              => 'Rewisjoone foon tou side feriine:',
'mergehistory-from'             => 'Jurtkamstsid:',
'mergehistory-into'             => 'Müüljsid:',
'mergehistory-list'             => 'Färsjoone, da feriind wårde koone',
'mergehistory-go'               => 'Wis färsjoone da feriind wårde koone',
'mergehistory-submit'           => 'Feriinde färsjoone',
'mergehistory-empty'            => 'Niinj färsjoone koone fereiind wårde.',
'mergehistory-success'          => '$3 {{PLURAL:$3|färsjoon|färsjoone}} foon [[:$1]] ma erfolch feriind tu [[:$2]].',
'mergehistory-no-source'        => 'Jurtkamstsid "$1" as ai deer.',
'mergehistory-no-destination'   => 'Müüljsid „$1“ bestoont ai.',
'mergehistory-invalid-source'   => 'Jurtkamstsid mötj en gültige sidnoome heewe.',
'mergehistory-comment'          => '„[[:$1]]“ feriind eefter „[[:$2]]“: $3',
'mergehistory-same-destination' => 'Jurtkamst- än müüljsid mönje ai idäntisch weese',
'mergehistory-reason'           => 'Grün:',

# Merge log
'revertmerge'      => 'Feriining tuninte mååge',
'mergelogpagetext' => 'Dåtheer as dåt logbök foon da feriinde färsjoonshistoorie.',

# Diffs
'history-title'            => 'Färsjoonshistoori foon "$1"',
'difference'               => '(Ferschääl twasche Färsjoone)',
'lineno'                   => 'Ra $1:',
'compareselectedversions'  => 'Wäälde färsjoone ferglike',
'showhideselectedversions' => 'Wäälde färsjoone wise/fersteege',
'editundo'                 => 'tunintemååge',

# Search results
'searchresults'             => 'Säkjresultoote',
'searchresults-title'       => 'Säkjresultoote for „$1“',
'searchresulttext'          => 'For mör informasjoon tu jü säkj sii jü [[{{MediaWiki:Helppage}}|heelpsid]].',
'searchsubtitle'            => 'Din säkönjfrååg: „[[:$1|$1]]“ ([[Special:Prefixindex/$1|åle ma „$1“ beganende side]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|åle side, da eefter „$1“ ferlinke]])',
'searchsubtitleinvalid'     => 'Din säkönjfrååg: "$1".',
'titlematches'              => 'Oueriinjstiminge ma sidetiitle',
'notitlematches'            => 'Niinj oueriinjstiming ma sidetiitle',
'textmatches'               => 'Oueriinjstiminge ma inhålte',
'notextmatches'             => 'Niinj oueriinjstiming ma inhålte',
'prevn'                     => '{{PLURAL:$1|leesten|leeste $1}}',
'nextn'                     => '{{PLURAL:$1|näisten|näiste $1}}',
'prevn-title'               => 'Leeste $1 {{PLURAL:$1|resultoot|resultoote}}',
'nextn-title'               => 'Näiste $1 {{PLURAL:$1|resultoot|resultoote}}',
'shown-title'               => 'Wis $1 {{PLURAL:$1|resultoot|resultoote}} pro sid',
'viewprevnext'              => 'Wis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'         => "'''Deer as en sid nååmd \"[[:\$1]]\" önj jüdeer wiki'''",
'searchmenu-new'            => "'''Mååg jü sid „[[:$1|$1]]“ önj dideer Wiki.'''",
'search-result-size'        => '$1 ({{PLURAL:$2|1 uurd|$2 uurde}})',
'search-result-score'       => 'Relevans: $1 %',
'search-redirect'           => '(widerliidjing foon „$1“)',
'search-section'            => '(oufsnaas $1)',
'search-suggest'            => 'Miinjdst dü „$1“?',
'search-interwiki-caption'  => 'Süsterprujäkte',
'search-interwiki-default'  => '$1 resultoote:',
'search-interwiki-more'     => '(widere)',
'search-mwsuggest-enabled'  => 'ma forsliike',
'search-mwsuggest-disabled' => 'niinj forsliike',
'search-relatedarticle'     => 'früne',
'mwsuggest-disable'         => 'forsliike per Ajax deaktiviire',
'searcheverything-enable'   => 'Onj ål noomerüme säke',
'searchrelated'             => 'früne',
'searchall'                 => 'åle',
'showingresults'            => "Heer {{PLURAL:$1|as '''1''' resultoot|san '''$1''' resultoote}}, beganend ma numer '''$2.'''",
'showingresultsnum'         => "Heer {{PLURAL:$3|as '''1''' resultoot|san '''$3''' resultoote}}, beganend ma numer '''$2.'''",
'showingresultsheader'      => "{{PLURAL:$5|resultoot '''$1''' foon '''$3'''|resultoote '''$1-$2''' foon '''$3'''}}, for '''$4.'''",
'nonefound'                 => "'''Haanewising:''' Deer wårde ståndardmääsi man ainkelde noomerüme döörsoocht. Seet ''all:'' for din Säkbegrip, am åle side (inkl. diskusjoonside, forlååge, äsw.) tu döörsäken unti gesiilt di noome foon di tu döörsäkende noomerüm.",
'search-nonefound'          => 'For din säkanfrååg würden niinj resultoote fünen.',
'powersearch'               => 'ütwided säkj',
'powersearch-legend'        => 'ütwided säkj',
'powersearch-ns'            => 'Säkj önj noomerüme:',
'powersearch-redir'         => 'Widerliidjinge anwise',
'powersearch-field'         => 'Säk eefter:',
'powersearch-togglelabel'   => 'Wääl üt:',
'powersearch-toggleall'     => 'Åle',
'powersearch-togglenone'    => 'Niinj',
'search-external'           => 'Extern säkj',
'searchdisabled'            => 'Jü {{SITENAME}}-säkj as deaktiviird. Dü koost intwasche ma Google säke. Betånk, dåt di säkindäks for {{SITENAME}} ferüüljet weese koon.',

# Preferences page
'preferences'   => 'Önjstalinge',
'mypreferences' => 'Önjstalinge',

# Groups
'group-sysop' => 'Administratoore',

'grouppage-sysop' => '{{ns:project}}:Administratoore',

# User rights log
'rightslog' => 'Ruchte-logbök',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'jüdeer sid beårbe',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|änring|änringe}}',
'recentchanges'                  => 'Leeste änringe',
'recentchanges-legend'           => 'Wis-opsjoone',
'recentchanges-feed-description' => 'Ferfülie ma dåtheer feed da leeste änringe önj {{SITENAME}}.',
'rcnote'                         => "Wised {{PLURAL:$1|wård '''1''' änring|wårde da leeste '''$''' änringe}} {{PLURAL:$2|foon e leest däi|foon da leeste '''$2''' deege}}. Stånd: $4, am e klook $5.",
'rclistfrom'                     => 'Bloot änringe sunt $1 wise.',
'rcshowhideminor'                => 'Latje änringe $1',
'rcshowhidebots'                 => 'Bots $1',
'rcshowhideliu'                  => 'Önjmäldede brükere $1',
'rcshowhideanons'                => 'Anonymen brüker $1',
'rcshowhidemine'                 => 'Äine tujeefte $1',
'rclinks'                        => 'Wis da leeste $1 änringe foon da leeste $2 deege.<br />$3',
'diff'                           => 'ferschääl',
'hist'                           => 'Färsjoone',
'hide'                           => 'ütbläne',
'show'                           => 'önjbläne',
'minoreditletter'                => 'L',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Detaile wise (JavaScript as nüsi)',
'rc-enhanced-hide'               => 'Detaile fersteege',

# Recent changes linked
'recentchangeslinked'         => 'Änringe bai ferlinkte side',
'recentchangeslinked-title'   => 'Änringe bai side, da foon "$1" ferlinked san',
'recentchangeslinked-summary' => "Jüdeer speetsjoolsid listet da leeste änringe bai da ferlinked side ap (btw. bai kategoriie tu da lasmoote foon jüdeer kategorii). Side aw din [[Special:Watchlist|eefterkiikliste]] san '''fåt''' deerstald.",
'recentchangeslinked-page'    => 'Sid:',
'recentchangeslinked-to'      => 'Wis änringe aw side, da heerjurt ferlinke',

# Upload
'upload'                     => 'Huuchsiinje',
'uploadnologin'              => 'Ai önjmälded',
'uploadnologintext'          => 'Dü möist [[Special:UserLogin|önjmälded weese]], am dootäie huuchleese tu koonen.',
'upload_directory_missing'   => 'Dåt aplees-fertiiknis ($1) breecht än köö ai foon di wäbsärwer mååged wårde.',
'upload_directory_read_only' => 'Dåt aplees-fertiiknis ($1) koon ai foon e wäbsärver beschraawen wårde.',
'uploaderror'                => 'Aplees-fäägel',
'uploadlogpage'              => 'Dåtäi-logbök',
'uploadedimage'              => 'heet "[[$1]]" huuchsånd',

# File description page
'filehist'                  => 'Dååtäifärsjoone',
'filehist-help'             => 'Klik aw en tidpunkt, am jüdeer färsjoon önjiinjtunaamen.',
'filehist-current'          => 'aktuäl',
'filehist-datetime'         => 'Färsjoon foon e',
'filehist-thumb'            => 'Forlökbil',
'filehist-thumbtext'        => 'Forlökbil for Färsjoon foon $2, am e klook $3',
'filehist-user'             => 'brüker',
'filehist-dimensions'       => 'Mätje',
'filehist-comment'          => 'Komentoor',
'imagelinks'                => 'Dååtäiferwiinjinge',
'linkstoimage'              => '{{PLURAL:$1|Jü füliend sid ferwånt|Da füliende $1 side ferwiinje}} jüdeer dååtäi:',
'sharedupload'              => 'Jüdeer dååtäi ståmt üt $1 än mötj foon ouder prujäkte brükt wårde.',
'uploadnewversion-linktext' => 'En nai färsjoon foon jüdeer dåtäi huuchsiinje',

# Random page
'randompage' => 'Tufåli sid',

# Statistics
'statistics' => 'Statistiik',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|byte|bytes}}',
'nmembers'      => '{{PLURAL:$1|1 önjdraag|$1 önjdraage}}',
'prefixindex'   => 'Åle side (ma prefiks)',
'newpages'      => 'Naie side',
'move'          => 'Ferschüwe',
'movethispage'  => 'Sid ferschüwe',
'pager-newer-n' => '{{PLURAL:$1|näisten|näiste $1}}',
'pager-older-n' => '{{PLURAL:$1|åleren|ålere $1}}',

# Book sources
'booksources'               => 'ISBN-säkj',
'booksources-search-legend' => 'Säk eefter betii-kwäle for böke',
'booksources-go'            => 'Säke',

# Special:Log
'log' => 'Logböke',

# Special:AllPages
'allpages'       => 'Åle side',
'alphaindexline' => '$1 bit $2',
'prevpage'       => 'Leest sid ($1)',
'allpagesfrom'   => 'Side wise sunt:',
'allpagesto'     => 'Side wise bit:',
'allarticles'    => 'Åle side',
'allpagessubmit' => 'Önjwiinje',

# Special:LinkSearch
'linksearch' => 'Weblink-säkj',

# Special:Log/newusers
'newuserlogpage'          => 'Nai-önjmäldings-logbök',
'newuserlog-create-entry' => 'Brüker wörd nai registriird',

# Special:ListGroupRights
'listgrouprights-members' => '(lasmoote-list)',

# E-mail user
'emailuser' => 'E-mail tu dideere brüker',

# Watchlist
'watchlist'         => 'Eefterkiikliste',
'mywatchlist'       => 'Eefterkiikliste',
'addedwatch'        => 'Tu eefterkiiksid tubaifäiged',
'addedwatchtext'    => 'Jü sid „[[:$1]]“ wörd tu din [[Special:Watchlist|eefterkiiklist]] tubaifäiged.

Lääsere änringe bai jüdeer sid än jü deertuhiirende diskusjoonssid wårde deer listed än
önj e ouersicht foon da [[Special:RecentChanges|leeste änringe]] önj fåtschraft deerstald.

Wan dü jü sid wider foon din eefterkiikliste wächhååle mååst, klik aw jüdeer sid aw „{{int:Unwatch}}“.',
'removedwatch'      => 'Foon jü eefterlöksid wächhååld',
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
'deletepage'            => 'Sid tunintemååge',
'confirmdeletetext'     => 'Dü bast deerbai, en sid ma åle tuhiirende ålere färsjoone tuninte tu måågen. Bestääsie hål deertu, dåt dü de foon da konsekwänse bewust bast, än dåt dü önj oueriinjstiming ma da [[{{MediaWiki:Policy-url}}|ruchtliinjen]] hoonelst.',
'actioncomplete'        => 'Aksjoon beånd',
'deletedtext'           => '„<nowiki>$1</nowiki>“ wörd tunintemååged. In e $2 fanst dü en list foon da tuleest tunintemåågede side.',
'deletedarticle'        => 'heet "[[$1]]" tunintemååged',
'dellogpage'            => 'Tunintemååg-Logbök',
'deletecomment'         => 'Grün:',
'deleteotherreason'     => 'Ouderen/tubaikaamenden grün:',
'deletereasonotherlist' => 'Ouderen grün',

# Rollback
'rollbacklink' => 'tubäägseete',

# Protect
'protectlogpage'              => 'Sideschütse-logbök',
'protectedarticle'            => 'schütsed „[[$1]]“',
'modifiedarticleprotection'   => 'änred e schüts for "[[$1]]"',
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

# Undelete
'undeletelink'     => 'wise/widermååge',
'undeletedarticle' => 'heet "[[$1]]" widermååged',

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
'sp-contributions-search'   => 'Säkj eefter brükertujeefte',
'sp-contributions-username' => 'IP-adräs unti brükernoome',
'sp-contributions-submit'   => 'Säike',

# What links here
'whatlinkshere'            => 'Links aw jüdeer sid',
'whatlinkshere-title'      => 'Side, da aw "$1" ferlinke',
'whatlinkshere-page'       => 'sid:',
'linkshere'                => "Da füliende side ferlinke aw '''„[[:$1]]“''':",
'isredirect'               => 'widerliidjingssid',
'istemplate'               => 'Forlåågeninbining',
'isimage'                  => 'dåtäilink',
'whatlinkshere-prev'       => '{{PLURAL:$1|leesten|leeste $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|näisten|näiste $1}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => 'Widerliidjinge $1',
'whatlinkshere-hidetrans'  => 'Forlåågenönjbininge $1',
'whatlinkshere-hidelinks'  => 'Links $1',
'whatlinkshere-filters'    => 'Sääwe',

# Block/unblock
'blockip'                         => 'IP-adräs/brüker späre',
'ipboptions'                      => '2 stüne:2 hours,1 däi:1 day,3 deege:3 days,1 wääg:1 week,2 wääge:2 weeks,1 moune:1 month,3 moune:3 months,6 moune:6 months,1 iir:1 year,suner iinje:infinite',
'ipblocklist'                     => 'Spärd IP-adräse än brükernoome',
'expiringblock'                   => 'iinjet aw e $1 am e klook $2',
'blocklink'                       => 'späre',
'unblocklink'                     => 'frijeewe',
'change-blocklink'                => 'Spär änre',
'contribslink'                    => 'tujeefte',
'autoblocker'                     => 'Automatische spär, deer dü en gemiinsoom IP-adräs ma [[User:$1|brüker:$1]] brükst. Grün foon brükerspär: „$2“.',
'blocklogpage'                    => 'Brükerspär-logbök',
'blocklogentry'                   => 'spärd „[[$1]]“ for di tidrüm: $2 $3',
'reblock-logentry'                => 'änerd jü spär for „[[$1]]“ for di tidrüm: $2 $3',
'blocklogtext'                    => 'Dåtdeer as dåt logbök ouer späringe än önjtspäringe foon brükere än IP-adräse.
Automatisch spärd IP-adräse wårde ai footed.
Sii jü [[Special:IPBlockList|list foon da spärd IP-adräse än brükernoome]] for ål da aktive späre.',
'unblocklogentry'                 => 'heet jü späre foon „$1“ aphääwd',
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
'movepagetext'     => "Ma dideere formulaar koost de en sid ambenååme (masamt åle färsjoone).
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
'movepagetalktext' => "Jü deertu hiirende diskusjoonssid wård, süwid deer, maferschääwen, '''unti dåt moost weese:'''
*Deer bestoont ål en diskusjoonssid ma dideere noome, unti
*dü wäälst jü uner stönjene opsjoon ouf.

Önj dadeere fåle möist dü, wan wansched, di önjhålt foon jü sid foon hönj ferschüwe unti tuhuupefääre.

Hål di '''naie''' tiitel uner '''muul''' önjdreege, deeruner jü ambenååming hål '''begrüne.'''",
'movearticle'      => 'Sid ferschüwe:',
'newtitle'         => 'Muul:',
'move-watch'       => 'Lök eefter jüdeer sid',
'movepagebtn'      => 'Sid ferschüwe',
'pagemovedsub'     => 'Ferschüwing erfolchrik',
'movepage-moved'   => "'''Jü sid „$1“ wörd eefter „$2“ ferschääwen.'''",
'articleexists'    => 'Uner dideere noome bestoont ål en sid. Wääl hål en nai noome.',
'talkexists'       => 'Jü sid seelew wörd erfolchrik ferschääwen, ouers jü deertu hiirende diskusjoonssid ai, deer ål iinj ma di nai tiitel bestoont. Glik hål da önjhålte foon hönj ouf.',
'movedto'          => 'ferschääwen eefter',
'movetalk'         => 'Jü diskusjoonssid maferschüwe, wan möölik',
'1movedto2'        => 'heet „[[$1]]“ eefter „[[$2]]“ ferschääwen',
'1movedto2_redir'  => 'heet „[[$1]]“ eefter „[[$2]]“ ferschääwen än deerbai en widerliidjing ouerschraawen',
'movelogpage'      => 'Ferschüwingslogbök',
'movereason'       => 'Begrüning:',
'revertmove'       => 'tubääg ferschüwe',

# Export
'export' => 'Side äksportiire',

# Thumbnails
'thumbnail-more' => 'fergrutre',

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
'tooltip-n-randompage'            => 'Tufäli sid',
'tooltip-n-help'                  => 'Heelpsid wise',
'tooltip-t-whatlinkshere'         => 'List foon ål da side, da heer jurt wise',
'tooltip-t-recentchangeslinked'   => 'Leest änringen bai side, da foon heer ferlinkd san',
'tooltip-feed-rss'                => 'RSS-feed for jüdeer sid',
'tooltip-feed-atom'               => 'Atom-feed for jüdeer sid',
'tooltip-t-contributions'         => 'List foon tujeefte foon dideere brüker önjkiike',
'tooltip-t-emailuser'             => 'En e-mail tu dideere brüker siinje',
'tooltip-t-upload'                => 'Dåtäie huuchsiinje',
'tooltip-t-specialpages'          => 'List foon ål da spesjåålside',
'tooltip-t-print'                 => 'Prantönjsacht foon jüdeer sid',
'tooltip-t-permalink'             => 'Wååri link tu jüdeer sidfärsjoon',
'tooltip-ca-nstab-main'           => 'Sidinhålt wise',
'tooltip-ca-nstab-user'           => 'Brükersid wise',
'tooltip-ca-nstab-special'        => 'Jüdeer sid as en spetsjåålsid. Jü koon ai beåarbed wårde.',
'tooltip-ca-nstab-project'        => 'Portoolsid wise',
'tooltip-ca-nstab-image'          => 'Dååtäisid wise',
'tooltip-ca-nstab-template'       => 'Forlååge wise',
'tooltip-ca-nstab-category'       => 'Kategoriisid wise',
'tooltip-minoredit'               => 'Jüdeer änring as latj markiire.',
'tooltip-save'                    => 'Änringe spikre',
'tooltip-preview'                 => 'Forlök foon da änringe bai jüdeer sid. Hål for dåt spikern brüke!',
'tooltip-diff'                    => 'Änringe bai di täkst wise',
'tooltip-compareselectedversions' => 'Ferschääl twasche tou ütwäälde färsjoone foon jüdeer sid wise.',
'tooltip-watch'                   => 'Fäig jüdeer sid foon din eefterkiikliste tubai',
'tooltip-rollback'                => 'Mååget åle leeste änringe foon jü sid, da foon di lik brüker fornümen wörden san, döör iinj klik tuninte.',
'tooltip-undo'                    => 'Mååget bloot jüdeer iinje änring tuninte än wist dåt resultoot önj e forlöksid önj, deerma önj e tukuupefootingssid en begrüning önjjääwen wårde koon.',

# Browsing diffs
'previousdiff' => '← Tu di leest färsjoonsferschääl',
'nextdiff'     => 'Tu di näist färsjoons-unerschiis →',

# Media information
'file-info-size'       => '($1 × $2 pixele, dååtäigrutelse: $3, MIME-typ: $4)',
'file-nohires'         => '<small>Niinj huuger apliising as deer.</small>',
'svg-long-desc'        => '(SVG-dåtäi, basisgrutelse: $1 × $2 pixel, dåtäigrutelse: $3)',
'show-big-image'       => 'Färsjon önj huuger apliising',
'show-big-image-thumb' => '<small>Grutelse foon jü forlök: $1 × $2 pixele</small>',

# Bad image list
'bad_image_list' => 'Formååt:

Bloot rae, da ma en * begane, wårde ütwjarted. As jarste eefter dåt * mötj en link aw en ai wansched dååtäi stönje.
Deeraw föliende sidelinke önj dåtseelwi ra definiire ütnååme, önj di kontäkst weerfoon jü dååtäi duch tu schüns kaame mötj.',

# Metadata
'metadata'          => 'Metadååte',
'metadata-help'     => 'Jüdeer dåtäi önjthålt widere informasjoon, jü önj e räigel foon jü digitoolamera unti di ferwånd scanner ståme. Döör eefterdräägen beårbing foon jü originooldåtäi koone hu detaile feränret wörden weese.',
'metadata-expand'   => 'Ütbriidede detaile wise',
'metadata-collapse' => 'Ütbriidede detaile fersteege',
'metadata-fields'   => 'Da füliende fälje foon da EXIF-metadååte önju dideere MediaWiki-systeemtäkst wårde aw bilbeschriwingsside wist; widere standardmääsi „inklapede“ detaile koone wised wårde.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Jüdeer dåtäi ma en äkstärn prugram beårbe',
'edit-externally-help' => '(Sii da [http://www.mediawiki.org/wiki/Manual:External_editors Installationsanweisungen] for widere Informasjoon)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'åle',
'namespacesall' => 'åle',
'monthsall'     => 'åle',

# Auto-summaries
'autosumm-blank'   => 'Det sidj wurd leesagd.',
'autosumm-replace' => 'Di iinhual wurd ütjbütjet mä "$1"',
'autoredircomment' => 'Sidj tu [[$1]] widjerfeerd',
'autosumm-new'     => 'Det sidj wurd nei uunlaanj: "$1"',

# Watchlist editing tools
'watchlisttools-view' => 'Eefterkiiklist: änringe',
'watchlisttools-edit' => 'normåål beårbe',
'watchlisttools-raw'  => 'Listeformoot beårbe (import/äksport)',

# Special:SpecialPages
'specialpages' => 'Spetsjåålside',

);
