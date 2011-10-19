<?php
/** Sango (Sängö)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ice201 (on sg.wikipedia.org)
 * @author Mdkidiri
 */

$fallback = 'fr';

$messages = array(
# User preference toggles
'tog-underline'               => 'Gbënëngö-gbê',
'tog-highlightbroken'         => 'Funda fängö gbê <a href="" class="new">like this</a> (wala ngâ: töngana sô : s<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Lôngbi yângâ tî âsurä',
'tog-hideminor'               => 'Hônde âkêtê lönzïngö-sû sô asï fadê na yâ tî gbâsû',
'tog-hidepatrolled'           => 'Hônde âlönzïngö-sû sô asï  na hönngö na yâ tî gbâsû',
'tog-newpageshidepatrolled'   => 'Na pöpö tî âlêmbëtï nî, hônde âla sô a yeke bâa ndo daä.',
'tog-extendwatchlist'         => 'Gbara molongö tî bängö-pekô sï atanda gï âfinî âsänzëmä pëpe me âsänzëmä kûê kûê.',
'tog-usenewrc'                => 'Sâra kua na âfinî sänzëmä sô alônzi ângbêre nî (ahûnda Javascript)',
'tog-numberheadings'          => 'Sû nömörö bîakü bîakü na terê tî âlisurä',
'tog-showtoolbar'             => 'Fa moyarâka tî sepesû (ahûnda Javascript)',
'tog-editondblclick'          => 'Pîka kpêkê ûse tî sepe lêmbëtï (ahûnda Javascript)',
'tog-editsection'             => 'Zîngo sëpengö surä na gbê "[Sepe]"',
'tog-editsectiononrightclick' => 'Pîka kötï kpêkê na ndö tî lisurä sï mo sepe nî (ahûnda Javascript)',
'tog-showtoc'                 => 'Fa molongö tî münä (tî âlêmbëtï sô ayeke na surä otâ)',
'tog-rememberpassword'        => 'Da bê na sênyîmbâ tî mbï (asï {{PLURAL:$1|längö|längö}}) $1',
'tog-watchcreations'          => 'Âlêmbëtï sô mbï sâra, zîa nî kûê na yâ tî molongö tî mbï tî bängö-pekô.',
'tog-watchdefault'            => 'Âlêmbëtï sô mbï sepe, zîa nî kûê na yâ tî molongö tî mbï tî bängö-pekô.',
'tog-watchmoves'              => 'Âlêmbëtï sô mbï sanzêe ïrï nî, zîa nî kûê na yâ tî molongö tî mbï tî bängö-pekô.',
'tog-watchdeletion'           => 'Âlêmbëtï sô mbï woza nî, zîa nî kûê na yâ tî molongö tî mbï tî bängö-pekô.',
'tog-minordefault'            => 'Tî sêtîa nî, sûngi âsepesû kûê töngana kêtê sepesû',
'tog-previewontop'            => 'Tanda piabängö-nî na ndöbê tî zuka tî sepesû',
'tog-shownumberswatching'     => 'Fa wüngö tî ânyîmbâ sô ayeke bâa lêmbëtï sô',
'tog-fancysig'                => 'Mû kekere nî töngana sêngê gbâsû tî Wïkï (sân taâ gbê)',

# Dates
'january'   => 'Nyenye',
'february'  => 'Fulundïgi',
'march'     => 'Mbängü',
'april'     => 'Ngubë',
'may_long'  => 'Bêläwü',
'june'      => 'Föndo',
'july'      => 'Lengua',
'august'    => 'Kükürü',
'september' => 'Mvuka',
'october'   => 'Ngberere',
'november'  => 'Nabändüru',
'december'  => 'Kakawuka',
'jan'       => 'Nye',
'feb'       => 'Ful',
'mar'       => 'Mba',
'apr'       => 'Ngu',
'may'       => 'Bêl',
'jun'       => 'Fön',
'jul'       => 'Len',
'aug'       => 'Kük',
'sep'       => 'Mvu',
'oct'       => 'Ngb',
'nov'       => 'Nab',
'dec'       => 'Kak',

'mytalk'     => 'Lisoro tî mbï',
'navigation' => 'Simba',

# Vector skin
'vector-view-view' => 'Dîko',
'actions'          => 'Kua',
'namespaces'       => 'Pöpö tî âïrï',
'variants'         => 'Âmbênî marä nî',

'tagline'          => 'Alöndö na  {{SITENAME}}',
'help'             => 'Za mbï',
'search'           => 'Gi',
'searchbutton'     => 'Gi',
'history'          => 'Mbai tî lêmbëtï nî',
'history_short'    => 'mbai',
'printableversion' => 'Mbâlê tî pete na sasango',
'permalink'        => 'Kpengü gbê tî lêmbëtï nî',
'edit'             => 'Sepe',
'editthispage'     => 'Fa na mbi sô lêmbëtï',
'talkpagelinktext' => 'tene tënë',
'personaltools'    => 'Âyêkua tî wanî',
'talk'             => 'Lisoro',
'views'            => 'Tändä',
'toolbox'          => 'Gbâyêkua',
'otherlanguages'   => 'Na mbênî âyângâ',
'jumpto'           => 'Gue na:',
'jumptonavigation' => 'Simba',
'jumptosearch'     => 'Gi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Na ndö tî {{SITENAME}}',
'aboutpage'            => 'Project:Na ndö tî...',
'currentevents'        => 'Âsango',
'currentevents-url'    => 'Project:Âsango',
'disclaimers'          => 'Zïngö-lê',
'disclaimerpage'       => 'Project:Zïngö-lê',
'helppage'             => 'Help:Münä',
'mainpage'             => 'Gä nzönî',
'mainpage-description' => 'Gä nzönî',
'portal'               => 'Âsëwä',
'portal-url'           => 'Project:Gä nzönî',
'privacy'              => 'Ndarä tî bätängö vivîi tënë',
'privacypage'          => 'Project:Vivîi tënë',

'ok'              => 'Nî laâ!',
'retrievedfrom'   => 'Awara na yâ tî "$1"',
'editsection'     => 'Sepe',
'editold'         => 'sepe',
'editsectionhint' => 'Sepe surä nî $1',
'site-atom-feed'  => 'süängö Atom tî $1',
'red-link-title'  => '$1 (lêmbëtï sô ayeke daä äpe)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'Lêmbëtï',
'nstab-user'    => 'Lêmbëtï tî Nyîmbâ',
'nstab-special' => 'Lêmbëtï ndê',

# Login and logout pages
'yourpassword'            => 'Pafungûla',
'nav-login-createaccount' => 'Linda wala Zî kônde',
'userlogin'               => 'Linda wala  Zî kônde',
'logout'                  => 'Sïgî',
'userlogout'              => 'Sigî',

# Edit pages
'watchthis'       => 'Bâa pekô tî lêmbëtï sô',
'anoneditwarning' => "'''Ânge:''' Mô de mo linda äpëe. Fade a sû lindosînga IP tî mo na mbai tî lêmbëtï sô.",
'editing'         => 'Sëpëngö $1',
'editingsection'  => 'Sëpëngö $1 (surä)',

# Diffs
'editundo' => 'woza',

# Search results
'searchresults'       => 'Pendâkua tî gïngö-ndo nî',
'searchresults-title' => 'Pendâkua tî gïngö-ndo tëtî "$1"',
'shown-title'         => 'Fa $1 {{PLURAL:$1|pendâkua |âpendâkua}} nî alîngbi na lêmbëtï ôko ôko.',
'search-result-size'  => '$1 ({{PLURAL:$2|1 mbupa|$2 âmbupa}})',

# Preferences page
'mypreferences' => 'tandä tî mbï',
'prefs-rc'      => 'Tanga ti yé so a gbion ya ni',
'yournick'      => 'Nyîmbâ:',

# User rights
'editinguser' => "Mo fa na mbi nyîmbâ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",

# Recent changes
'recentchanges' => 'Ndângbâ sänzëmä',

# Recent changes linked
'recentchangeslinked-toolbox' => 'Bängö pekô tî âgbegbêe lêmbëtï',

# Upload
'upload'          => 'Yâlamû mbênî kuru',
'watchthisupload' => 'bâa lo ânde sô lêmbëtï',

# Random page
'randompage' => 'Lêmbëtï waâwa',

'brokenredirects-edit' => 'fa na mbi',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|limbe|âlimbe}}',

# Watchlist
'watchlist'     => 'Molongö tî bängö pekô',
'watch'         => 'bâa pekô nî',
'watchthispage' => 'bâa lo ânde sô lêmbëtï',

# Namespace form on various pages
'blanknamespace' => '(Tö)',

# Contributions
'mycontris' => 'Kua tî mbï',

# What links here
'whatlinkshere' => 'Gbegbêe lêmbëtï',

# Block/unblock
'contribslink' => 'âkua tî ânyîmbâ',

# Move page
'move-watch' => 'bâa lo ânde sô lêmbëtï',

# Tooltip help for the actions
'tooltip-pt-login'               => 'Ayeke nzönî mo linda, atâa sô a kambaga mo äpe.',
'tooltip-pt-logout'              => 'sïgî',
'tooltip-ca-talk'                => 'Lisoro na ndö tî münä tî lêmbëtï sô',
'tooltip-ca-edit'                => 'Mo lîngbi tî sepe lêmbëtï sô. Nzönî mo pete kûtu piabâa tîtene mo pia mo bâa nî awe sï mo sûnga nî.',
'tooltip-ca-history'             => 'Ângbêre mbâlê tî lêmbëtï sô (na ïrï tî âwasüngö-nî)',
'tooltip-search'                 => 'Gi {{SITENAME}}',
'tooltip-search-fulltext'        => 'Gi âlêmbëtï sô ayeke na gbâsû sô',
'tooltip-p-logo'                 => 'Gue na Tölêmbëtï',
'tooltip-n-mainpage'             => 'Gue na tölêmbëtï Gä nzönî.',
'tooltip-n-mainpage-description' => 'Gue na tölêmbëtï Gä nzönî.',
'tooltip-n-portal'               => 'Tënë tî pialö nî',
'tooltip-n-currentevents'        => 'Wara sango tî âpäsëmä tî fafadësô na gündâ nî.',
'tooltip-n-recentchanges'        => 'Molongö tî ândângbâ sänzëma tî yâ tî Wïkï nî',
'tooltip-n-randompage'           => 'Tanda mbênî lêmbëtï waâa',
'tooltip-n-help'                 => 'Za mbï',
'tooltip-t-whatlinkshere'        => 'Molongö tî âlêmbëtï sô kûê agbë gbê na lo sô.',
'tooltip-t-recentchangeslinked'  => 'Molongö tî ândângbâ sänzëmä tî âlêmbëtï sô agbë gbê na lo-sô.',
'tooltip-t-upload'               => 'To kuru na wavungä',
'tooltip-t-specialpages'         => 'Molongö tî âlêmbëtï sô kûê ayeke ndê',
'tooltip-t-print'                => 'Mbâlê tî lêmbëtï sô tî pete na sasango',
'tooltip-t-permalink'            => 'Kpengü gbê na mbâlê tî lêmbëtï nî sô',
'tooltip-ca-nstab-main'          => 'Bâa lêmbëtï tî münä nî',

# Attribution
'others' => 'mbênî',

# Bad image list
'bad_image_list' => 'Funda nî ayeke töngasô:
Gï âkâmba tî molongö (sô atö ndâ nî na *) laâ a yeke bâa lêgë nî. Kôzo gbê tî yâ tî kâmba ôko ôko adu tî gbùe na mbênî kpäkë kuru. Âtanga tî âgbê sô na yâ tî ôko kâmba nî sô, a bâa nî töngana yê ndê, sô ^tî tene, töngana âlêmbëtï sô kuru nî alîngbi tî tua daä na gbegbê nî.',

# Special:SpecialPages
'specialpages' => 'Âlêmbëtï sô ayeke ndê',

);
