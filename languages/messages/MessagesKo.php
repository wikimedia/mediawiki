<?php
/** Korean (한국어)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Albamhandae
 * @author Devunt
 * @author Ficell
 * @author Gapo
 * @author IRTC1015
 * @author ITurtle
 * @author Klutzy
 * @author Kwj2772
 * @author Pi.C.Noizecehx
 * @author PuzzletChung
 * @author TheAlpha for knowledge
 * @author ToePeu
 * @author Yknok29
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => '미디어',
	NS_SPECIAL          => '특수기능',
	NS_TALK             => '토론',
	NS_USER             => '사용자',
	NS_USER_TALK        => '사용자토론',
	NS_PROJECT_TALK     => '$1토론',
	NS_FILE             => '파일',
	NS_FILE_TALK        => '파일토론',
	NS_MEDIAWIKI        => '미디어위키',
	NS_MEDIAWIKI_TALK   => '미디어위키토론',
	NS_TEMPLATE         => '틀',
	NS_TEMPLATE_TALK    => '틀토론',
	NS_HELP             => '도움말',
	NS_HELP_TALK        => '도움말토론',
	NS_CATEGORY         => '분류',
	NS_CATEGORY_TALK    => '분류토론',
);

$namespaceAliases = array(
	'특'  => NS_SPECIAL,
	'MediaWiki토론' => NS_MEDIAWIKI_TALK,
	'그림' => NS_FILE,
	'파일토론' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( '이중넘겨주기' ),
	'BrokenRedirects'           => array( '끊긴넘겨주기' ),
	'Disambiguations'           => array( '동음이의', '동음이의문서' ),
	'Userlogin'                 => array( '로그인' ),
	'Userlogout'                => array( '로그아웃' ),
	'CreateAccount'             => array( '계정만들기', '가입' ),
	'Preferences'               => array( '환경설정' ),
	'Watchlist'                 => array( '주시문서목록', '주시목록' ),
	'Recentchanges'             => array( '최근바뀜' ),
	'Upload'                    => array( '파일올리기', '그림올리기' ),
	'Listfiles'                 => array( '파일', '그림', '파일목록', '그림목록' ),
	'Newimages'                 => array( '새파일', '새그림' ),
	'Listusers'                 => array( '사용자', '사용자목록' ),
	'Listgrouprights'           => array( '사용자권한', '권한목록' ),
	'Statistics'                => array( '통계' ),
	'Randompage'                => array( '임의문서' ),
	'Lonelypages'               => array( '외톨이문서' ),
	'Uncategorizedpages'        => array( '분류안된문서' ),
	'Uncategorizedcategories'   => array( '분류안된분류' ),
	'Uncategorizedimages'       => array( '분류안된파일', '분류안된그림' ),
	'Uncategorizedtemplates'    => array( '분류안된틀' ),
	'Unusedcategories'          => array( '안쓰는분류' ),
	'Unusedimages'              => array( '안쓰는파일', '안쓰는그림' ),
	'Wantedpages'               => array( '필요한문서' ),
	'Wantedcategories'          => array( '필요한분류' ),
	'Wantedfiles'               => array( '필요한파일', '필요한그림' ),
	'Wantedtemplates'           => array( '필요한틀' ),
	'Mostlinked'                => array( '많이링크된문서' ),
	'Mostlinkedcategories'      => array( '많이쓰는분류' ),
	'Mostlinkedtemplates'       => array( '많이쓰는틀' ),
	'Mostimages'                => array( '많이쓰는파일', '많이쓰는그림' ),
	'Mostcategories'            => array( '많이분류된문서' ),
	'Mostrevisions'             => array( '역사긴문서' ),
	'Fewestrevisions'           => array( '역사짧은문서' ),
	'Shortpages'                => array( '짧은문서' ),
	'Longpages'                 => array( '긴문서' ),
	'Newpages'                  => array( '새문서' ),
	'Ancientpages'              => array( '오래된문서' ),
	'Deadendpages'              => array( '막다른문서' ),
	'Protectedpages'            => array( '보호된문서' ),
	'Protectedtitles'           => array( '생성보호된문서' ),
	'Allpages'                  => array( '모든문서' ),
	'Prefixindex'               => array( '접두어찾기' ),
	'Ipblocklist'               => array( '차단된사용자', '차단목록' ),
	'Unblock'                   => array( '차단해제' ),
	'Specialpages'              => array( '특수문서', '특수기능' ),
	'Contributions'             => array( '기여', '기여목록', '사용자기여' ),
	'Emailuser'                 => array( '이메일보내기' ),
	'Confirmemail'              => array( '이메일인증' ),
	'Whatlinkshere'             => array( '가리키는문서', '링크하는문서' ),
	'Recentchangeslinked'       => array( '링크최근바뀜' ),
	'Movepage'                  => array( '이동', '문서이동' ),
	'Blockme'                   => array( '자가차단' ),
	'Booksources'               => array( '책찾기' ),
	'Categories'                => array( '분류' ),
	'Export'                    => array( '내보내기' ),
	'Version'                   => array( '버전' ),
	'Allmessages'               => array( '모든메시지' ),
	'Log'                       => array( '기록', '로그' ),
	'Blockip'                   => array( '차단' ),
	'Undelete'                  => array( '삭제취소', '삭제된문서' ),
	'Import'                    => array( '가져오기' ),
	'Lockdb'                    => array( 'DB잠금', 'DB잠그기' ),
	'Unlockdb'                  => array( 'DB잠금취소', 'DB잠금해제' ),
	'Userrights'                => array( '권한조정' ),
	'MIMEsearch'                => array( 'MIME찾기', 'MIME검색' ),
	'FileDuplicateSearch'       => array( '중복파일찾기' ),
	'Unwatchedpages'            => array( '주시안되는문서' ),
	'Listredirects'             => array( '넘겨주기', '넘겨주기목록' ),
	'Revisiondelete'            => array( '특정판삭제' ),
	'Unusedtemplates'           => array( '안쓰는틀' ),
	'Randomredirect'            => array( '임의넘겨주기' ),
	'Mypage'                    => array( '내사용자문서' ),
	'Mytalk'                    => array( '내사용자토론' ),
	'Mycontributions'           => array( '내기여', '내기여목록' ),
	'Listadmins'                => array( '관리자', '관리자목록' ),
	'Listbots'                  => array( '봇', '봇목록' ),
	'Popularpages'              => array( '인기있는문서' ),
	'Search'                    => array( '찾기', '검색' ),
	'Resetpass'                 => array( '비밀번호변경', '비밀번호바꾸기' ),
	'Withoutinterwiki'          => array( '인터위키없는문서' ),
	'MergeHistory'              => array( '역사합치기' ),
	'Filepath'                  => array( '파일경로', '그림경로' ),
	'Invalidateemail'           => array( '이메일인증취소', '이메일인증해제' ),
	'Blankpage'                 => array( '빈문서' ),
	'LinkSearch'                => array( '외부링크찾기', '외부링크검색' ),
	'DeletedContributions'      => array( '삭제된기여' ),
	'Tags'                      => array( '태그' ),
	'Activeusers'               => array( '활동중인사용자' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#넘겨주기', '#REDIRECT' ),
	'notoc'                 => array( '0', '__목차숨김__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__화랑숨김__', '__갤러리숨김__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__목차보임__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__목차__', '__TOC__' ),
	'noeditsection'         => array( '0', '__단락편집숨김__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__헤더숨김__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', '현재월', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', '현재월1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', '현재월이름', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', '현재월이름소유격', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', '현재월이름약자', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', '현재일', 'CURRENTDAY' ),
	'currentday2'           => array( '1', '현재일2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', '현재요일', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', '현재년', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', '현재시분', '현재시각', 'CURRENTTIME' ),
	'currenthour'           => array( '1', '현재시', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', '지역월', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', '지역월1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', '지역월이름', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', '지역월이름소유격', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', '지역월이름약자', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', '지역일', 'LOCALDAY' ),
	'localday2'             => array( '1', '지역일2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', '지역요일', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', '지역년', 'LOCALYEAR' ),
	'localtime'             => array( '1', '지역시분', '지역시각', 'LOCALTIME' ),
	'localhour'             => array( '1', '지역시', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', '모든문서수', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', '문서수', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', '파일수', '그림수', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', '사용자수', '계정수', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', '활동중인사용자수', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', '편집수', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', '조회수', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', '문서이름', 'PAGENAME' ),
	'pagenamee'             => array( '1', '문서이름E', 'PAGENAMEE' ),
	'namespace'             => array( '1', '이름공간', 'NAMESPACE' ),
	'namespacee'            => array( '1', '이름공간E', 'NAMESPACEE' ),
	'talkspace'             => array( '1', '토론이름공간', 'TALKSPACE' ),
	'talkspacee'            => array( '1', '토론이름공간E', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', '본문서이름공간', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', '본문서이름공간E', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', '전체문서이름', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', '전체문서이름E', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', '하위문서이름', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', '하위문서이름E', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', '상위문서이름', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', '상위문서이름E', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', '토론문서이름', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', '토론문서이름E', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', '본문서이름', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', '본문서이름E', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', '메시지:', 'MSG:' ),
	'subst'                 => array( '0', '풀기:', 'SUBST:' ),
	'safesubst'             => array( '0', '안전풀기:', 'SAFESUBST:' ),
	'img_thumbnail'         => array( '1', '섬네일', '썸네일', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', '섬네일=$1', '썸네일=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', '오른쪽', 'right' ),
	'img_left'              => array( '1', '왼쪽', 'left' ),
	'img_none'              => array( '1', '없음', 'none' ),
	'img_width'             => array( '1', '$1픽셀', '$1px' ),
	'img_center'            => array( '1', '가운데', 'center', 'centre' ),
	'img_framed'            => array( '1', '프레임', 'framed', 'enframed', 'frame' ),
	'img_page'              => array( '1', '문서=$1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', '테두리', 'border' ),
	'img_baseline'          => array( '1', '밑줄', 'baseline' ),
	'img_sub'               => array( '1', '윗첨자', 'sub' ),
	'img_top'               => array( '1', '위', 'top' ),
	'img_middle'            => array( '1', '중간', 'middle' ),
	'img_bottom'            => array( '1', '아래', 'bottom' ),
	'img_link'              => array( '1', '링크=$1', 'link=$1' ),
	'sitename'              => array( '1', '사이트이름', 'SITENAME' ),
	'ns'                    => array( '0', '이름:', '이름공간:', 'NS:' ),
	'nse'                   => array( '0', '이름E:', '이름공간E:', 'NSE:' ),
	'localurl'              => array( '0', '지역주소:', 'LOCALURL:' ),
	'localurle'             => array( '0', '지역주소E:', 'LOCALURLE:' ),
	'server'                => array( '0', '서버', 'SERVER' ),
	'servername'            => array( '0', '서버이름', 'SERVERNAME' ),
	'scriptpath'            => array( '0', '스크립트경로', 'SCRIPTPATH' ),
	'stylepath'             => array( '0', '스타일경로', 'STYLEPATH' ),
	'grammar'               => array( '0', '문법:', 'GRAMMAR:' ),
	'gender'                => array( '0', '성별:', 'GENDER:' ),
	'currentweek'           => array( '1', '현재주', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', '현재요일숫자', 'CURRENTDOW' ),
	'localweek'             => array( '1', '지역주', 'LOCALWEEK' ),
	'localdow'              => array( '1', '지역요일숫자', 'LOCALDOW' ),
	'revisionid'            => array( '1', '판번호', 'REVISIONID' ),
	'revisionday'           => array( '1', '판일', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', '판일2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', '판월', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', '판년', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', '판타임스탬프', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', '판사용자', 'REVISIONUSER' ),
	'plural'                => array( '0', '복수:', '복수형:', 'PLURAL:' ),
	'fullurl'               => array( '0', '전체주소:', 'FULLURL:' ),
	'fullurle'              => array( '0', '전체주소E:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', '첫소문자:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', '첫대문자:', 'UCFIRST:' ),
	'lc'                    => array( '0', '소문자:', 'LC:' ),
	'uc'                    => array( '0', '대문자:', 'UC:' ),
	'displaytitle'          => array( '1', '제목표시', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__새글쓰기__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__새글쓰기숨기기__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', '현재버전', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', '주소인코딩:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', '책갈피인코딩', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', '현재타임스탬프', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', '지역타임스탬프', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#언어:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', '기본언어', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', '이름공간문서수', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', '관리자수', 'NUMBEROFADMINS' ),
	'special'               => array( '0', '특수기능', 'special' ),
	'defaultsort'           => array( '1', '기본정렬:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', '파일경로:', '그림경로:', 'FILEPATH:' ),
	'tag'                   => array( '0', '태그', 'tag' ),
	'hiddencat'             => array( '1', '__숨은분류__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', '분류문서수', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', '문서크기', 'PAGESIZE' ),
	'numberingroup'         => array( '1', '권한별사용자수', '그룹별사용자수', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__넘겨주기고정__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', '보호수준', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', '날짜형식', 'formatdate', 'dateformat' ),
);

$bookstoreList = array(
	'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1',
	'inherit' => true,
);

$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'ko';
$dateFormats = array(
	'ko time'            => 'H:i',
	'ko date'            => 'Y년 M월 j일 (D)',
	'ko both'            => 'Y년 M월 j일 (D) H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => '링크에 밑줄치기:',
'tog-highlightbroken'         => '없는 문서로 연결된 링크를 <a href="" class="new">이렇게</a> 보이기 (선택하지 않으면 이렇게<a href="" class="internal">?</a> 보임)',
'tog-justify'                 => '문단 정렬하기',
'tog-hideminor'               => '사소한 편집을 최근 바뀜에서 숨기기',
'tog-hidepatrolled'           => '최근 바뀜에서 검토된 편집을 숨기기',
'tog-newpageshidepatrolled'   => '새 문서 목록에서 검토된 문서를 숨기기',
'tog-extendwatchlist'         => '주시문서 목록에서 최근 바뀜 대신 모든 편집 보기',
'tog-usenewrc'                => '향상된 최근 바뀜 (자바스크립트)',
'tog-numberheadings'          => '머릿글 번호 매기기',
'tog-showtoolbar'             => '편집창에 툴바 보이기 (자바스크립트)',
'tog-editondblclick'          => '더블클릭으로 문서 편집하기 (자바스크립트)',
'tog-editsection'             => '‘편집’ 링크로 부분을 편집하는 기능 켜기',
'tog-editsectiononrightclick' => '제목을 오른쪽 클릭해서 부분 편집하기 (자바스크립트)',
'tog-showtoc'                 => '문서의 차례 보여주기 (머릿글이 4개 이상인 경우)',
'tog-rememberpassword'        => '이 컴퓨터에서 로그인 상태를 저장하기 (최대 $1일)',
'tog-watchcreations'          => '내가 처음 만드는 문서를 주시문서 목록에 추가',
'tog-watchdefault'            => '내가 편집하는 문서를 주시문서 목록에 추가',
'tog-watchmoves'              => '내가 이동하는 문서를 주시문서 목록에 추가',
'tog-watchdeletion'           => '내가 삭제하는 문서를 주시문서 목록에 추가',
'tog-previewontop'            => '편집상자 앞에 미리보기 보이기',
'tog-previewonfirst'          => '처음 편집할 때 미리보기 보기',
'tog-nocache'                 => '문서 캐시 끄기',
'tog-enotifwatchlistpages'    => '주시문서가 바뀌면 이메일로 알림',
'tog-enotifusertalkpages'     => '내 토론 문서가 바뀌면 이메일로 알림',
'tog-enotifminoredits'        => '사소한 편집도 이메일로 알림',
'tog-enotifrevealaddr'        => '알림 메일에 내 이메일 주소를 밝히기',
'tog-shownumberswatching'     => '주시 사용자 수 보기',
'tog-oldsig'                  => '지금 서명 미리보기:',
'tog-fancysig'                => '서명을 위키텍스트로 취급 (자동으로 링크를 걸지 않음)',
'tog-externaleditor'          => '외부 편집기를 기본 편집기로 사용 (숙련자용. 컴퓨터에 특별한 설정이 필요)',
'tog-externaldiff'            => '외부 비교 도구를 기본 도구로 사용 (숙련자용. 컴퓨터에 특별한 설정이 필요)',
'tog-showjumplinks'           => '접근성을 위한 "이동" 링크 쓰기 (일부 스킨에서만 작동)',
'tog-uselivepreview'          => '실시간 미리 보기 사용하기 (자바스크립트, 실험적 기능)',
'tog-forceeditsummary'        => '편집 요약을 쓰지 않았을 때 알려주기',
'tog-watchlisthideown'        => '주시문서 목록에서 내 편집을 숨기기',
'tog-watchlisthidebots'       => '주시문서 목록에서 봇 편집을 숨기기',
'tog-watchlisthideminor'      => '주시문서 목록에서 사소한 편집을 숨기기',
'tog-watchlisthideliu'        => '주시문서 목록에서 로그인한 사용자의 편집을 숨기기',
'tog-watchlisthideanons'      => '주시문서 목록에서 비등록 사용자의 편집을 숨기기',
'tog-watchlisthidepatrolled'  => '주시문서 목록에서 검토된 편집을 숨기기',
'tog-ccmeonemails'            => '이메일을 보낼 때 내 이메일로 복사본을 보내기',
'tog-diffonly'                => '편집 차이를 비교할 때 문서 내용을 보지 않기',
'tog-showhiddencats'          => '숨은 분류 보기',
'tog-norollbackdiff'          => '되돌리기 후 차이를 보이지 않기',

'underline-always'  => '항상',
'underline-never'   => '치지 않음',
'underline-default' => '브라우저 설정을 따르기',

# Font style option in Special:Preferences
'editfont-style'     => '편집창의 글꼴:',
'editfont-default'   => '브라우저 설정을 따르기',
'editfont-monospace' => '고정폭 글꼴',
'editfont-sansserif' => '산세리프체',
'editfont-serif'     => '세리프체',

# Dates
'sunday'        => '일요일',
'monday'        => '월요일',
'tuesday'       => '화요일',
'wednesday'     => '수요일',
'thursday'      => '목요일',
'friday'        => '금요일',
'saturday'      => '토요일',
'sun'           => '일',
'mon'           => '월',
'tue'           => '화',
'wed'           => '수',
'thu'           => '목',
'fri'           => '금',
'sat'           => '토',
'january'       => '1월',
'february'      => '2월',
'march'         => '3월',
'april'         => '4월',
'may_long'      => '5월',
'june'          => '6월',
'july'          => '7월',
'august'        => '8월',
'september'     => '9월',
'october'       => '10월',
'november'      => '11월',
'december'      => '12월',
'january-gen'   => '1월',
'february-gen'  => '2월',
'march-gen'     => '3월',
'april-gen'     => '4월',
'may-gen'       => '5월',
'june-gen'      => '6월',
'july-gen'      => '7월',
'august-gen'    => '8월',
'september-gen' => '9월',
'october-gen'   => '10월',
'november-gen'  => '11월',
'december-gen'  => '12월',
'jan'           => '1',
'feb'           => '2',
'mar'           => '3',
'apr'           => '4',
'may'           => '5',
'jun'           => '6',
'jul'           => '7',
'aug'           => '8',
'sep'           => '9',
'oct'           => '10',
'nov'           => '11',
'dec'           => '12',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|분류}}',
'category_header'                => '‘$1’ 분류에 속하는 문서',
'subcategories'                  => '하위 분류',
'category-media-header'          => '‘$1’ 분류에 속하는 자료',
'category-empty'                 => '이 분류에 속하는 문서나 자료가 없습니다.',
'hidden-categories'              => '{{PLURAL:$1|숨은 분류}}',
'hidden-category-category'       => '숨은 분류',
'category-subcat-count'          => '다음은 이 분류에 속하는 하위 분류 {{PLURAL:$2|$1 개|$2 개 가운데 $1 개}} 입니다.',
'category-subcat-count-limited'  => '이 분류에 하위분류가 $1개 있습니다.',
'category-article-count'         => '다음은 이 분류에 속하는 문서 {{PLURAL:$2|$1 개|$2 개 가운데 $1 개}} 입니다.',
'category-article-count-limited' => '이 분류에 문서가 $1개 있습니다.',
'category-file-count'            => '다음은 이 분류에 속하는 파일 {{PLURAL:$2|$1 개|$2 개 가운데 $1 개}} 입니다.',
'category-file-count-limited'    => '이 분류에 파일이 $1개 있습니다.',
'listingcontinuesabbrev'         => ' (계속)',
'index-category'                 => '색인된 문서',
'noindex-category'               => '색인에서 제외되는 문서',

'mainpagetext'      => "'''미디어위키가 성공적으로 설치되었습니다.'''",
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/Help:Contents 이곳]에서 위키 프로그램에 대한 정보를 얻을 수 있습니다.

== 시작하기 ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings 설정하기]
* [http://www.mediawiki.org/wiki/Manual:FAQ 미디어위키 FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce 미디어위키 발표 메일링 리스트]',

'about'         => '소개',
'article'       => '문서 내용',
'newwindow'     => '(새 창으로 열림)',
'cancel'        => '취소',
'moredotdotdot' => '더 보기...',
'mypage'        => '내 사용자 문서',
'mytalk'        => '내 사용자 토론',
'anontalk'      => '익명 사용자 토론',
'navigation'    => '둘러보기',
'and'           => ',',

# Cologne Blue skin
'qbfind'         => '찾기',
'qbbrowse'       => '탐색',
'qbedit'         => '편집',
'qbpageoptions'  => '문서 기능',
'qbpageinfo'     => '문서 정보',
'qbmyoptions'    => '내 문서',
'qbspecialpages' => '특수 문서',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => '새 주제',
'vector-action-delete'           => '삭제',
'vector-action-move'             => '이동',
'vector-action-protect'          => '보호',
'vector-action-undelete'         => '되살리기',
'vector-action-unprotect'        => '보호 해제',
'vector-namespace-category'      => '분류',
'vector-namespace-help'          => '도움말',
'vector-namespace-image'         => '파일',
'vector-namespace-main'          => '문서',
'vector-namespace-media'         => '미디어 문서',
'vector-namespace-mediawiki'     => '메시지',
'vector-namespace-project'       => '프로젝트 문서',
'vector-namespace-special'       => '특수 문서',
'vector-namespace-talk'          => '토론',
'vector-namespace-template'      => '틀',
'vector-namespace-user'          => '사용자 문서',
'vector-simplesearch-preference' => '향상된 검색어 제안 사용하기 (벡터 스킨 전용)',
'vector-view-create'             => '생성',
'vector-view-edit'               => '편집',
'vector-view-history'            => '역사',
'vector-view-view'               => '읽기',
'vector-view-viewsource'         => '내용 보기',
'actions'                        => '행위',
'namespaces'                     => '이름공간',
'variants'                       => '변수',

'errorpagetitle'    => '오류',
'returnto'          => '$1(으)로 돌아갑니다.',
'tagline'           => '{{SITENAME}}',
'help'              => '도움말',
'search'            => '찾기',
'searchbutton'      => '찾기',
'go'                => '가기',
'searcharticle'     => '가기',
'history'           => '문서 역사',
'history_short'     => '역사',
'updatedmarker'     => '마지막으로 읽은 뒤 바뀌었음',
'info_short'        => '정보',
'printableversion'  => '인쇄용 문서',
'permalink'         => '고유링크',
'print'             => '인쇄',
'edit'              => '편집',
'create'            => '생성',
'editthispage'      => '이 문서 편집하기',
'create-this-page'  => '이 문서 만들기',
'delete'            => '삭제',
'deletethispage'    => '이 문서 삭제하기',
'undelete_short'    => '편집 $1개 되살리기',
'protect'           => '보호',
'protect_change'    => '보호 수준 변경',
'protectthispage'   => '이 문서 보호하기',
'unprotect'         => '보호 해제',
'unprotectthispage' => '이 문서 보호 해제하기',
'newpage'           => '새 문서',
'talkpage'          => '토론 문서',
'talkpagelinktext'  => '토론',
'specialpage'       => '특수 문서',
'personaltools'     => '개인 도구',
'postcomment'       => '새 주제',
'articlepage'       => '문서 보기',
'talk'              => '토론',
'views'             => '보기',
'toolbox'           => '도구모음',
'userpage'          => '사용자 문서 보기',
'projectpage'       => '프로젝트 문서 보기',
'imagepage'         => '자료 문서 보기',
'mediawikipage'     => '메시지 문서 보기',
'templatepage'      => '틀 문서 보기',
'viewhelppage'      => '도움말 문서 보기',
'categorypage'      => '분류 문서 보기',
'viewtalkpage'      => '토론 보기',
'otherlanguages'    => '다른 언어',
'redirectedfrom'    => '($1에서 넘어옴)',
'redirectpagesub'   => '넘겨주기 문서',
'lastmodifiedat'    => '이 문서는 $1 $2 에 마지막으로 바뀌었습니다.',
'viewcount'         => '이 문서는 $1번 읽혔습니다.',
'protectedpage'     => '보호된 문서',
'jumpto'            => '이동:',
'jumptonavigation'  => '둘러보기',
'jumptosearch'      => '찾기',
'view-pool-error'   => '서버가 과부하에 걸렸습니다.
너무 많은 사용자가 이 문서를 보려고 하고 있습니다.
이 문서를 다시 열기 전에 잠시만 기다려주세요.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} 소개',
'aboutpage'            => 'Project:소개',
'copyright'            => '모든 문서는 $1 라이선스를 따릅니다.',
'copyrightpage'        => '{{ns:project}}:저작권',
'currentevents'        => '요즘 화제',
'currentevents-url'    => 'Project:요즘 화제',
'disclaimers'          => '면책 조항',
'disclaimerpage'       => 'Project:면책 조항',
'edithelp'             => '편집 도움말',
'edithelppage'         => 'Help:편집하기',
'helppage'             => 'Help:목차',
'mainpage'             => '대문',
'mainpage-description' => '대문',
'policy-url'           => 'Project:정책',
'portal'               => '사용자 모임',
'portal-url'           => 'Project:사용자 모임',
'privacy'              => '개인정보 정책',
'privacypage'          => 'Project:개인정보 정책',

'badaccess'        => '권한 오류',
'badaccess-group0' => '요청한 동작을 실행할 권한이 없습니다.',
'badaccess-groups' => '요청한 동작은 {{PLURAL:$2|다음|다음 중 하나의}} 권한을 가진 사용자에게만 가능합니다: $1.',

'versionrequired'     => '미디어위키 $1 버전 필요',
'versionrequiredtext' => '이 문서를 사용하려면 $1 버전 미디어위키가 필요합니다. [[Special:Version|설치된 미디어위키 버전]]을 확인해주세요.',

'ok'                      => '확인',
'retrievedfrom'           => '원본 주소 ‘$1’',
'youhavenewmessages'      => '다른 사용자가 $1란에 글을 남겼습니다. ($2)',
'newmessageslink'         => '사용자 토론',
'newmessagesdifflink'     => '바뀐 내용 비교',
'youhavenewmessagesmulti' => '다른 사용자가 $1란에 글을 남겼습니다.',
'editsection'             => '편집',
'editold'                 => '편집',
'viewsourceold'           => '내용 보기',
'editlink'                => '편집',
'viewsourcelink'          => '내용 보기',
'editsectionhint'         => '부분 편집: $1',
'toc'                     => '목차',
'showtoc'                 => '보이기',
'hidetoc'                 => '숨기기',
'thisisdeleted'           => '$1을 보거나 되살리겠습니까?',
'viewdeleted'             => '$1을 보겠습니까?',
'restorelink'             => '삭제된 편집 $1 개',
'feedlinks'               => '피드:',
'feed-invalid'            => '잘못된 구독 피드 방식입니다.',
'feed-unavailable'        => '피드 서비스는 제공하지 않습니다',
'site-rss-feed'           => '$1 RSS 피드',
'site-atom-feed'          => '$1 Atom 피드',
'page-rss-feed'           => '‘$1’ RSS 피드',
'page-atom-feed'          => '"$1" Atom 피드',
'red-link-title'          => '$1 (없는 문서)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '문서',
'nstab-user'      => '사용자 문서',
'nstab-media'     => '자료',
'nstab-special'   => '특수 문서',
'nstab-project'   => '프로젝트 문서',
'nstab-image'     => '파일',
'nstab-mediawiki' => '메시지',
'nstab-template'  => '틀',
'nstab-help'      => '도움말',
'nstab-category'  => '분류',

# Main script and global functions
'nosuchaction'      => '해당하는 동작이 없습니다.',
'nosuchactiontext'  => 'URL로 요청한 동작이 잘못되었습니다.
당신은 URL을 잘못 입력하였거나, 잘못된 링크를 따라갔을 수 있습니다.
이것은 {{SITENAME}}의 버그일 수도 있습니다.',
'nosuchspecialpage' => '해당하는 특수기능이 없습니다.',
'nospecialpagetext' => '<strong>요청한 특수 문서가 존재하지 않습니다.</strong>

특수 문서의 목록은 [[Special:SpecialPages|여기]]에서 볼 수 있습니다.',

# General errors
'error'                => '오류',
'databaseerror'        => '데이터베이스 오류',
'dberrortext'          => '데이터베이스 쿼리 구문 오류가 발생했습니다.
소프트웨어의 버그가 있을 수 있습니다.
마지막으로 요청한 데이터베이스 쿼리는 "<tt>$2</tt>" 함수에서 쓰인
<blockquote><tt>$1</tt></blockquote>
입니다.
데이터베이스는 "<tt>$3: $4</tt>" 오류를 일으켰습니다.',
'dberrortextcl'        => '데이터베이스 쿼리 구문 오류가 발생했습니다.
마지막으로 요청한 데이터베이스 쿼리는 "$2" 함수에서 쓰인
"$1"
입니다. 
데이터베이스는 "$3: $4" 오류를 일으켰습니다.',
'laggedslavemode'      => "'''주의:''' 문서가 최근에 바뀐 내용이 아닐 수도 있습니다.",
'readonly'             => '데이터베이스 잠김',
'enterlockreason'      => '데이터베이스를 잠그는 이유와 예상되는 기간을 적어 주세요.',
'readonlytext'         => '데이터베이스가 잠겨 있어서 문서를 편집할 수 없습니다. 데이터베이스 관리가 끝난 후에는 정상으로 돌아올 것입니다.

관리자가 데이터베이스를 잠글 때 남긴 메시지는 다음과 같습니다: $1',
'missing-article'      => '데이터베이스에서 ‘$1’ 문서$2를 찾지 못했습니다.

삭제된 문서의 역사/비교 문서를 보려고 시도할 때 이러한 문제가 발생할 수 있습니다.

또는, 프로그램 버그가 발생했을 수도 있습니다. [[Special:ListUsers/sysop|관리자]]에게 오류가 나는 URL을 알려주세요.',
'missingarticle-rev'   => '(판번호: $1)',
'missingarticle-diff'  => '(차이: $1, $2)',
'readonly_lag'         => '슬레이브 데이터베이스가 마스터 서버의 자료를 갱신하는 중입니다. 데이터베이스가 자동으로 잠겨 있습니다.',
'internalerror'        => '내부 오류',
'internalerror_info'   => '내부 오류: $1',
'fileappenderrorread'  => '‘$1’ 파일을 읽을 수 없습니다. 해당 파일에 내용을 덧붙일 수 없습니다.',
'fileappenderror'      => '‘$1’ 파일을 ‘$2’에 덧붙일 수 없습니다.',
'filecopyerror'        => '‘$1’ 파일을 ‘$2’(으)로 복사할 수 없습니다.',
'filerenameerror'      => '‘$1’ 파일을 ‘$2’(으)로 옮길 수 없습니다.',
'filedeleteerror'      => '‘$1’ 파일을 삭제할 수 없습니다.',
'directorycreateerror' => '‘$1’ 디렉토리를 만들 수 없습니다.',
'filenotfound'         => '‘$1’ 파일을 찾을 수 없습니다.',
'fileexistserror'      => '"$1" 파일이 이미 있어 여기에 쓸 수 없습니다.',
'unexpected'           => '예상되지 않은 값: "$1"="$2"',
'formerror'            => '오류: 양식을 제출할 수 없습니다.',
'badarticleerror'      => '지금의 명령은 이 문서에서는 실행할 수 없습니다.',
'cannotdelete'         => '"$1" 문서나 파일을 삭제할 수 없습니다. 이미 삭제되었을 수도 있습니다.',
'badtitle'             => '잘못된 제목',
'badtitletext'         => '문서 제목이 잘못되었거나 비어있습니다.',
'perfcached'           => '다음 자료는 캐시된 것이므로 현재 상황을 반영하지 않을 수 있습니다.',
'perfcachedts'         => '다음 자료는 캐시된 것으로, $1에 마지막으로 갱신되었습니다.',
'querypage-no-updates' => '이 문서의 갱신이 현재 비활성화되어 있습니다. 자료가 잠시 갱신되지 않을 것입니다.',
'wrong_wfQuery_params' => 'wfQuery()에서 잘못된 매개변수 발생<br />함수: $1<br />쿼리: $2',
'viewsource'           => '내용 보기',
'viewsourcefor'        => '$1의 문서 내용',
'actionthrottled'      => '동작 중지',
'actionthrottledtext'  => '스팸을 막기 위해 짧은 시간 안에 이 작업을 너무 많이 하는 것을 막고 있습니다.
제한을 넘었으니 몇 분 뒤에 새로 시도하세요.',
'protectedpagetext'    => '이 문서는 편집할 수 없도록 보호되어 있습니다.',
'viewsourcetext'       => '문서의 원본을 보거나 복사할 수 있습니다:',
'protectedinterface'   => '이 문서는 소프트웨어 인터페이스에 쓰이는 문서로, 잠겨 있습니다.',
'editinginterface'     => "'''경고''': 소프트웨어에서 사용하는 메시지 문서를 고치고 있습니다. 이것은 모든 사용자에게 영향을 끼칩니다. 번역되지 않은 메시지를 번역하려는 경우에는, [http://translatewiki.net/wiki/Main_Page?setlang=ko translatewiki.net]에 참여하면 메시지 번역을 미디어위키에 직접 반영할 수 있습니다.",
'sqlhidden'            => '(SQL 쿼리 숨겨짐)',
'cascadeprotected'     => '이 문서는 연쇄보호가 걸린 {{PLURAL:$1|문서}}에 포함되어 있어 함께 보호됩니다. 연쇄 보호된 문서:
$2',
'namespaceprotected'   => "'''$1''' 네임스페이스를 편집할 수 있는 권한이 없습니다.",
'customcssjsprotected' => '이 문서는 다른 사용자의 개인 설정을 담고 있기 때문에, 편집할 권한이 없습니다.',
'ns-specialprotected'  => '{{ns:special}} 네임스페이스의 문서는 편집할 수 없습니다.',
'titleprotected'       => '[[User:$1|$1]] 사용자가 문서 생성을 금지했습니다.
이유는 다음과 같습니다. ‘$2’',

# Virus scanner
'virus-badscanner'     => "잘못된 설정: 알 수 없는 바이러스 검사기: ''$1''",
'virus-scanfailed'     => '검사 실패 (코드 $1)',
'virus-unknownscanner' => '알려지지 않은 백신:',

# Login and logout pages
'logouttext'                 => "'''{{SITENAME}}에서 로그아웃했습니다.'''

이대로 이름 없이 {{SITENAME}}을(를) 이용하거나, 방금 사용했던 계정이나 다른 계정으로 다시 [[Special:UserLogin|로그인]]해서 이용할 수 있습니다.
웹 브라우저의 캐시를 지우지 않으면 몇몇 문서에서 로그인이 되어 있는 것처럼 보일 수 있다는 점을 유의해 주세요.",
'welcomecreation'            => '== $1 님, 환영합니다! ==
계정이 만들어졌습니다.
[[Special:Preferences|{{SITENAME}} 사용자 환경 설정]]을 바꿀 수 있습니다.',
'yourname'                   => '계정 이름:',
'yourpassword'               => '비밀번호:',
'yourpasswordagain'          => '암호 재입력:',
'remembermypassword'         => '이 컴퓨터에서 로그인 상태를 저장하기 (최대 $1일)',
'yourdomainname'             => '도메인 이름',
'externaldberror'            => '외부 인증 데이터베이스에 오류가 있거나, 외부 계정을 갱신할 권한이 없습니다.',
'login'                      => '로그인',
'nav-login-createaccount'    => '로그인 / 계정 만들기',
'loginprompt'                => '{{SITENAME}}에 로그인하려면 쿠키를 사용할 수 있어야 합니다.',
'userlogin'                  => '로그인 / 계정 만들기',
'userloginnocreate'          => '로그인',
'logout'                     => '로그아웃',
'userlogout'                 => '로그아웃',
'notloggedin'                => '로그인하고 있지 않음',
'nologin'                    => '계정이 없나요? $1.',
'nologinlink'                => '계정을 만들 수 있습니다',
'createaccount'              => '계정 만들기',
'gotaccount'                 => "계정이 이미 있다면, '''$1'''.",
'gotaccountlink'             => '로그인하세요',
'createaccountmail'          => '이메일로 보내기',
'badretype'                  => '암호가 서로 다릅니다.',
'userexists'                 => '입력하신 사용자 이름이 이미 등록되어 있습니다.
다른 이름으로 만들어주십시오.',
'loginerror'                 => '로그인 오류',
'createaccounterror'         => '계정을 생성하지 못했습니다: $1',
'nocookiesnew'               => '사용자 계정을 만들었지만, 아직 로그인하지 않았습니다. {{SITENAME}}에서는 로그인 정보를 저장하기 위해 쿠키를 사용합니다. 지금 사용하는 웹 브라우저는 쿠키를 사용하지 않도록 설정되어 있습니다. 로그인하기 전에 웹 브라우저에서 쿠키를 사용하도록 설정해주세요.',
'nocookieslogin'             => '{{SITENAME}}에서는 로그인을 위해 쿠키를 사용합니다. 당신의 웹 브라우저에서 쿠키가 비활성되어 있습니다. 쿠키 사용을 활성화한 다음 로그인해 주세요.',
'noname'                     => '사용자 이름이 올바르지 않습니다.',
'loginsuccesstitle'          => '로그인 성공',
'loginsuccess'               => "'''{{SITENAME}}에 ‘$1’ 계정으로 로그인했습니다.'''",
'nosuchuser'                 => '"$1" 사용자가 존재하지 않습니다.
사용자 이름은 대소문자를 구별합니다. 철자가 맞는지 확인해주세요.
[[Special:UserLogin/signup|새 계정을 만들 수도 있습니다]].',
'nosuchusershort'            => '이름이 ‘<nowiki>$1</nowiki>’인 사용자는 없습니다. 철자가 맞는지 확인해 주세요.',
'nouserspecified'            => '사용자 이름을 입력하지 않았습니다.',
'login-userblocked'          => '이 사용자는 차단되었습니다. 로그인할 수 없습니다.',
'wrongpassword'              => '암호가 틀렸습니다. 다시 시도해 주세요.',
'wrongpasswordempty'         => '암호를 쓰지 않았습니다.
다시 시도해 주세요.',
'passwordtooshort'           => '암호는 적어도 $1자보다 길어야 합니다.',
'password-name-match'        => '암호는 반드시 사용자 이름과 달라야 합니다.',
'mailmypassword'             => '새 암호를 이메일로 보내기',
'passwordremindertitle'      => '{{SITENAME}}에서 보내는 새 임시 암호',
'passwordremindertext'       => 'IP 주소 $1에서 당신에게 {{SITENAME}} ($4)의 새 암호를 보내달라고 요청했습니다.
사용자 ‘$2’의 암호는 이제 ‘$3’입니다. 로그인한 다음 암호를 변경해 주세요.
당신의 임시 암호는 $5일 후에 만료됩니다.

만약 이 암호 요청을 다른 사람이 보낸 것이거나, 예전에 사용하던 암호를 기억해냈다면 이 메시지를 무시하고 예전 암호를 계속 사용할 수 있습니다.',
'noemail'                    => '사용자 ‘$1’에 등록된 이메일 주소가 없습니다.',
'noemailcreate'              => '바른 이메일 주소를 써야 합니다.',
'passwordsent'               => '‘$1’ 계정의 새로운 암호를 이메일로 보냈습니다. 암호를 받고 다시 로그인해 주세요.',
'blocked-mailpassword'       => '당신의 IP 주소는 편집이 차단되어 있습니다. 따라서 암호 되살리기 기능은 악용 방지를 위해 금지됩니다.',
'eauthentsent'               => '확인 이메일을 보냈습니다. 이메일 내용의 지시대로 계정 확인 절차를 실행해 주십시오.',
'throttled-mailpassword'     => '비밀번호 확인 이메일을 이미 최근 $1 시간 안에 보냈습니다. 악용을 방지하기 위해 비밀번호 확인 메일은 $1 시간마다 오직 하나씩만 보낼 수 있습니다.',
'mailerror'                  => '메일 보내기 오류: $1',
'acct_creation_throttle_hit' => '당신의 IP 주소를 이용한 방문자가 이전에 이미 계정을 $1개 생성하여, 계정 생성 한도를 초과하였습니다.
따라서 지금은 이 IP 주소로는 더 이상 계정을 만들 수 없습니다.',
'emailauthenticated'         => '당신의 이메일 주소는 $2 $3에 인증되었습니다.',
'emailnotauthenticated'      => '이메일 주소를 인증하지 않았습니다. 이메일 확인 절차를 거치지 않으면 다음 이메일 기능을 사용할 수 없습니다.',
'noemailprefs'               => '이 기능을 사용하기 위해서는 환경설정에서 이메일 주소를 설정해야 합니다.',
'emailconfirmlink'           => '이메일 주소 확인',
'invalidemailaddress'        => '이메일 주소의 형식이 잘못되어 인식할 수 없습니다.
정상적인 형식의 이메일을 입력하거나 칸을 비워 주세요.',
'accountcreated'             => '계정 만들어짐',
'accountcreatedtext'         => '‘$1’ 사용자 계정이 만들어졌습니다.',
'createaccount-title'        => '{{SITENAME}} 계정 만들기',
'createaccount-text'         => '{{SITENAME}}($4)에서 계정 이름 ‘$2’, 비밀번호 ‘$3’으로 계정을 만들었습니다. 로그인을 한 다음 암호를 바꾸어 주세요.

계정을 잘못 만들었다면 이 메시지는 무시해도 됩니다.',
'usernamehasherror'          => '계정 이름에는 해시 문자가 들어가서는 안 됩니다.',
'login-throttled'            => '로그인에 연속으로 실패하였습니다.
잠시 후에 다시 시도해주세요.',
'loginlanguagelabel'         => '언어: $1',
'suspicious-userlogout'      => '브라우저의 이상이 있거나 캐싱 프록시에서 로그아웃을 요청했기 때문에 로그아웃이 거부되었습니다.',

# Password reset dialog
'resetpass'                 => '비밀번호 바꾸기',
'resetpass_announce'        => '이메일로 받은 임시 암호로 로그인했습니다. 로그인을 마치려면 새 암호를 입력해 주세요:',
'resetpass_header'          => '비밀번호 변경',
'oldpassword'               => '예전 암호:',
'newpassword'               => '새 암호:',
'retypenew'                 => '새 암호 확인:',
'resetpass_submit'          => '암호를 변경하고 로그인하기',
'resetpass_success'         => '암호가 성공적으로 변경되었습니다! 이제 로그인해 주세요...',
'resetpass_forbidden'       => '비밀번호 변경 불가',
'resetpass-no-info'         => '이 특수 문서에 직접 접근하려면 반드시 로그인해야 합니다.',
'resetpass-submit-loggedin' => '비밀번호 변경',
'resetpass-submit-cancel'   => '취소',
'resetpass-wrong-oldpass'   => '비밀번호가 잘못되었거나 현재의 비밀번호와 같습니다.
당신은 이미 비밀번호를 성공적으로 변경하였거나 새 임시 비밀번호를 발급받았을 수 있습니다.',
'resetpass-temp-password'   => '임시 비밀번호:',

# Edit page toolbar
'bold_sample'     => '굵은 글씨',
'bold_tip'        => '굵은 글씨',
'italic_sample'   => '기울인 글씨',
'italic_tip'      => '기울인 글씨',
'link_sample'     => '링크 제목',
'link_tip'        => '내부 링크',
'extlink_sample'  => 'http://www.example.com 사이트 이름',
'extlink_tip'     => '외부 사이트 링크 (앞에 http://를 붙여야 합니다.)',
'headline_sample' => '제목',
'headline_tip'    => '2단계 문단 제목',
'math_sample'     => '여기에 수식을 쓰세요',
'math_tip'        => '수식(LaTeX)',
'nowiki_sample'   => '여기에 위키 문법을 사용하지 않을 글을 적어 주세요',
'nowiki_tip'      => '위키 문법 사용하지 않기',
'image_tip'       => '파일 넣기',
'media_tip'       => '파일 링크하기',
'sig_tip'         => '내 서명과 현재 시각',
'hr_tip'          => '가로줄(되도록 사용하지 말아 주세요)',

# Edit pages
'summary'                          => '편집 요약:',
'subject'                          => '주제/제목:',
'minoredit'                        => '사소한 편집',
'watchthis'                        => '이 문서 주시하기',
'savearticle'                      => '저장',
'preview'                          => '미리 보기',
'showpreview'                      => '미리 보기',
'showlivepreview'                  => '실시간 미리 보기',
'showdiff'                         => '차이 보기',
'anoneditwarning'                  => "'''주의''': 로그인하고 있지 않습니다. 당신의 IP 주소가 문서 역사에 남게 됩니다.",
'anonpreviewwarning'               => "'''당신은 지금 로그인하고 있지 않습니다. 문서를 저장하면 당신의 IP 주소가 문서 역사에 남게 됩니다.'''",
'missingsummary'                   => "'''알림:''' 편집 요약을 적지 않았습니다. 그대로 저장하면 편집 요약 없이 저장됩니다.",
'missingcommenttext'               => '아래에 내용을 채워 넣어 주세요.',
'missingcommentheader'             => "'''알림:''' 글의 제목을 입력하지 않았습니다.
다시 {{int:savearticle}} 버튼을 클릭하면 글이 제목 없이 저장됩니다.",
'summary-preview'                  => '편집 요약 미리 보기:',
'subject-preview'                  => '주제/제목 미리 보기:',
'blockedtitle'                     => '차단됨',
'blockedtext'                      => "'''당신의 계정 혹은 IP 주소가 차단되었습니다.'''

차단한 사람은 $1이고, 차단한 이유는 다음과 같습니다: $2

* 차단이 시작된 시간: $8
* 차단이 끝나는 시간: $6
* 차단된 사용자: $7

$1, 또는 [[{{MediaWiki:Grouppage-sysop}}|다른 관리자]]에게 차단에 대해 문의할 수 있습니다. [[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 '이메일 보내기' 기능을 사용할 수 있습니다. 또 이메일 보내기 기능이 차단되어 있으면 이메일을 보낼 수 없습니다.
지금 당신의 IP 주소는 $3이고, 차단 ID는 #$5입니다.
문의할 때에 이 정보를 같이 알려주세요.",
'autoblockedtext'                  => "당신의 IP 주소는 $1이 차단한 사용자가 사용했던 IP이기 때문에 자동으로 차단되었습니다. 차단된 이유는 다음과 같습니다:

:$2

* 차단이 시작된 시간: $8
* 차단이 끝나는 시간: $6
* 차단된 사용자: $7

$1, 또는 [[{{MediaWiki:Grouppage-sysop}}|다른 관리자]]에게 차단에 대해 문의할 수 있습니다.

[[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 '이메일 보내기' 기능을 사용할 수 있습니다. 또한 이메일 보내기 기능이 차단되어 있으면 이메일을 보낼 수 없습니다.

당신의 현재 IP 주소는 $3이고, 차단 ID는 #$5입니다.
문의할 때에 이 정보를 같이 알려주세요.",
'blockednoreason'                  => '이유를 입력하지 않음',
'blockedoriginalsource'            => "아래에 '''$1'''의 내용이 나와 있습니다:",
'blockededitsource'                => "아래에 '''$1'''에서의 '''당신의 편집'''이 나와 있습니다:",
'whitelistedittitle'               => '편집하려면 로그인 필요',
'whitelistedittext'                => '문서를 편집하려면 $1해야 합니다.',
'confirmedittext'                  => '문서를 고치려면 이메일 인증 절차가 필요합니다. [[Special:Preferences|사용자 환경 설정]]에서 이메일 주소를 입력하고 이메일 주소 인증을 해 주시기 바랍니다.',
'nosuchsectiontitle'               => '단락을 찾을 수 없음',
'nosuchsectiontext'                => '편집하려는 문단이 존재하지 않습니다.
당신이 이 문서를 보는 동안 문단이 이동되거나 삭제되었을 수 있습니다.',
'loginreqtitle'                    => '로그인 필요',
'loginreqlink'                     => '로그인',
'loginreqpagetext'                 => '다른 문서를 보기 위해서는 $1해야 합니다.',
'accmailtitle'                     => '암호를 보냈습니다.',
'accmailtext'                      => '[[User talk:$1|$1]] 사용자의 비밀번호가 임의로 만들어져 $2로 전송되었습니다.

새 비밀번호는 로그인한 후 [[Special:ChangePassword|변경]]할 수 있습니다.',
'newarticle'                       => '(새 문서)',
'newarticletext'                   => "이 문서는 아직 만들어지지 않았습니다. 새 문서를 만들려면 아래의 상자에 문서 내용을 입력하면 됩니다(자세한 내용은 [[{{MediaWiki:Helppage}}|도움말]]을 읽어 주세요).
만약 잘못 찾아온 문서라면, 웹 브라우저의 '''뒤로''' 버튼을 눌러 주세요.",
'anontalkpagetext'                 => '----
여기는 계정을 만들지 않았거나 사용하고 있지 않은 비등록 사용자를 위한 토론 문서입니다. 비등록 사용자를 구별하기 위해서는 숫자로 된 IP 주소를 사용해야만 합니다. IP 주소는 여러 사용자가 공유할 수 있습니다. 자신과 관계없는 의견이 자신에게 남겨져 있어 불쾌하다고 생각하는 비등록 사용자는 [[Special:UserLogin/signup|계정을 만들고]] [[Special:UserLogin|로그인 하여]] 나중에 다른 비등록 사용자에게 줄 혼란을 줄일 수 있습니다.',
'noarticletext'                    => '이 문서가 존재하지 않습니다.
이 문서와 제목이 비슷한 문서가 있는지 [[Special:Search/{{PAGENAME}}|검색]]하거나, 이 문서에 관련된 <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 기록]을 확인하거나, 문서를 직접 [{{fullurl:{{FULLPAGENAME}}|action=edit}} 편집]</span>할 수 있습니다.',
'noarticletext-nopermission'       => '‘{{FULLPAGENAME}}’ 문서가 존재하지 않습니다.
이 문서와 제목이 비슷한 문서가 있는지 [[Special:Search/{{PAGENAME}}|검색]]하거나, 이 문서에 관련된 <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 기록]을 확인할 수 있습니다.</span>',
'userpage-userdoesnotexist'        => '‘$1’ 계정은 등록되어 있지 않습니다. 이 문서를 만들거나 편집하려면 계정이 존재 하는지 확인해주세요.',
'userpage-userdoesnotexist-view'   => '"$1" 사용자 계정은 등록되지 않았습니다.',
'blocked-notice-logextract'        => '이 사용자는 현재 차단되어 있습니다.
해당 사용자의 최근 차단 기록을 참고하십시오:',
'clearyourcache'                   => "'''참고 - 설정을 저장한 후에 바뀐 점을 확인하기 위해서는 브라우저의 캐시를 갱신해야 합니다.'''

'''모질라 / 파이어폭스 / 사파리''': Shift 키를 누르면서 새로 고침을 클릭하거나, Ctrl-F5 또는 Ctrl-R 을 입력 (매킨토시에서는 Command-R); '''컨커러''': 새로고침을 클릭하거나 F5를 입력; '''오페라''': 도구→설정에서 캐시를 비움; '''인터넷 익스플로러''': Ctrl 키를 누르면서 새로 고침을 클릭하거나, Ctrl-F5를 입력.",
'usercssyoucanpreview'             => "'''안내''': CSS 문서를 저장하기 전에 ‘{{int:showpreview}}’ 기능을 통해 작동을 확인해주세요.",
'userjsyoucanpreview'              => "'''안내''': JS 문서를 저장하기 전에 ‘{{int:showpreview}}’ 기능을 통해 작동을 확인해주세요.",
'usercsspreview'                   => "'''이것은 사용자 CSS의 미리 보기이며, 아직 저장하지 않았다는 것을 주의해 주세요!'''",
'userjspreview'                    => "'''이것은 자바스크립트 미리 보기로, 아직 저장하지 않았다는 것을 주의해 주세요!'''",
'userinvalidcssjstitle'            => "'''경고''': ‘$1’ 스킨은 없습니다.
.css와 .js 문서의 제목은 {{ns:user}}:Foo/monobook.css 처럼 소문자로 써야 합니다. {{ns:user}}:Foo/Monobook.css 와 같이 대문자로 쓸 경우 작동하지 않습니다.",
'updated'                          => '(바뀜)',
'note'                             => "'''주의:'''",
'previewnote'                      => "'''지금 미리 보기로 보고 있는 내용은 아직 저장되지 않았습니다!'''",
'previewconflict'                  => '이 미리 보기는 저장할 때의 모습, 즉 위쪽 편집창의 문서를 반영합니다.',
'session_fail_preview'             => "'''세션 데이터가 없어져 편집을 저장하지 못했습니다.
다시 시도해도 되지 않으면 [[Special:UserLogout|로그아웃]]한 다음 다시 로그인해 보십시오.'''",
'session_fail_preview_html'        => "'''세션 데이터가 없어져 편집을 저장하지 못했습니다.'''

{{SITENAME}}에서 HTML 입력을 허용하기 때문에, 자바스크립트 공격을 막기 위해 미리 보기는 숨겨져 있습니다.

'''다시 시도해도 되지 않으면 [[Special:UserLogout|로그아웃]]한 다음 다시 로그인을 해 보십시오.'''",
'token_suffix_mismatch'            => "'''저장하려는 내용의 문장 부호가 망가져 있습니다. 문서 보호를 위해 해당 내용을 저장하지 않습니다. 버그가 있는 익명 프록시 서비스 등을 사용할 때 이런 문제가 발생할 수 있습니다.'''",
'editing'                          => '$1 편집하기',
'editingsection'                   => '$1 편집하기 (부분)',
'editingcomment'                   => '$1 편집하기 (덧붙이기)',
'editconflict'                     => '편집 충돌: $1',
'explainconflict'                  => "문서를 편집하는 도중에 누군가 이 문서를 고쳤습니다.
위쪽의 문서가 지금 바뀐 문서이고, 아래쪽의 문서가 당신이 편집한 문서입니다.
아래쪽의 내용을 위쪽에 적절히 합쳐 주시기 바랍니다.
\"저장\"을 누르면 '''위쪽의 편집 내역만''' 저장됩니다.",
'yourtext'                         => '당신의 편집',
'storedversion'                    => '현재 문서',
'nonunicodebrowser'                => "'''주의: 당신의 웹 브라우저가 유니코드를 완벽하게 지원하지 않습니다. 몇몇 문자가 16진수 코드로 나타날 수 있습니다.'''",
'editingold'                       => "'''경고:
지금 옛날 버전의 문서를 고치고 있습니다.
이것을 저장하면 최근에 편집된 부분이 사라질 수 있습니다.'''",
'yourdiff'                         => '차이',
'copyrightwarning'                 => "{{SITENAME}}에서의 모든 기여는 $2 라이선스로 배포된다는 점을 유의해 주세요 (자세한 내용에 대해서는 $1 문서를 읽어주세요). 만약 여기에 동의하지 않는다면 문서를 저장하지 말아 주세요.<br />
또 당신이 쓴 글은 직접 작성했거나 퍼블릭 도메인과 같은 자유 문서에서 가져왔다는 것을 보증해야 합니다.
'''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'copyrightwarning2'                => "{{SITENAME}}에서의 모든 기여는 다른 사용자가 편집, 수정, 삭제할 수 있다는 점을 유의해 주세요. 만약 여기에 동의하지 않는다면, 문서를 저장하지 말아 주세요.<br />
또 당신이 쓴 글은 직접 작성했거나, 퍼블릭 도메인과 같은 자유 문서에서 가져왔다는 것을 보증해야 합니다 (자세한 내용에 대해서는 $1 문서를 읽어 주세요).
'''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'longpagewarning'                  => "'''주의: 이 문서의 용량이 $1킬로바이트입니다. 몇몇 웹 브라우저에서는 32킬로바이트 이상의 문서를 편집할 때 문제가 발생할 수 있습니다. 만약의 경우를 대비하여, 문서를 여러 문단으로 나누어서 편집할 수 있습니다.'''",
'longpageerror'                    => "'''오류: 문서의 크기가 $1킬로바이트로서 가능한 최대 크기인 $2킬로바이트보다 큽니다. 저장할 수 없습니다.'''",
'readonlywarning'                  => "'''주의: 데이터베이스가 관리를 위해 잠겨 있습니다. 따라서 문서를 편집한 내용을 지금 저장할 수 없습니다.
편집 내용을 복사 붙여넣기 등을 사용하여 일단 다른 곳에 저장한 후, 나중에 다시 시도해 주세요.'''

잠근 관리자가 남긴 설명은 다음과 같습니다: $1",
'protectedpagewarning'             => "'''경고: 이 문서는 관리자만 편집할 수 있도록 보호되어 있습니다.'''
이 문서의 최근 기록을 참고하십시오:",
'semiprotectedpagewarning'         => "'''안내:''' 이 문서는 계정을 등록한 사용자만이 편집할 수 있도록 잠겨 있습니다.
이 문서의 최근 기록을 참고하십시오:",
'cascadeprotectedwarning'          => "'''주의''': 이 문서는 잠겨 있어 관리자만 편집할 수 있습니다. 연쇄적 보호가 걸린 다음 {{PLURAL:$1|문서}}에서 이 문서를 사용하고 있습니다:",
'titleprotectedwarning'            => "'''경고: 이 문서는 잠겨 있어, 문서를 생성하려면 [[Special:ListGroupRights|특정 권한]]이 필요합니다.'''
아래 문서의 최근 기록을 참고하십시오:",
'templatesused'                    => '이 문서에서 사용한 {{PLURAL:$1|틀}}:',
'templatesusedpreview'             => '이 미리 보기에서 사용하고 있는 {{PLURAL:$1|틀}}:',
'templatesusedsection'             => '이 문단에서 사용하고 있는 {{PLURAL:$1|틀}}:',
'template-protected'               => '(보호됨)',
'template-semiprotected'           => '(준보호됨)',
'hiddencategories'                 => '이 문서는 다음 숨은 분류 $1 개에 속해 있습니다:',
'edittools'                        => '<!-- 이 문서는 편집 창과 파일 올리기 창에 출력됩니다. -->',
'nocreatetitle'                    => '문서 생성 제한',
'nocreatetext'                     => '{{SITENAME}}에서 새로운 문서를 생성하는 것은 제한되어 있습니다. 이미 존재하는 다른 문서를 편집하거나, [[Special:UserLogin|로그인하거나 계정을 만들 수 있습니다]].',
'nocreate-loggedin'                => '새 문서를 만들 권한이 없습니다.',
'sectioneditnotsupported-title'    => '문단 편집 지원 안됨',
'sectioneditnotsupported-text'     => '이 문서에서는 문단 편집을 지원하지 않습니다.',
'permissionserrors'                => '권한 오류',
'permissionserrorstext'            => '해당 명령을 수행할 권한이 없습니다. 다음의 {{PLURAL:$1|이유}}를 확인해보세요:',
'permissionserrorstext-withaction' => '$2 권한이 없습니다. 다음의 {{PLURAL:$1|이유}}를 확인해주세요:',
'recreate-moveddeleted-warn'       => "'''주의: 삭제된 적이 있는 문서를 다시 만들고 있습니다.'''

이 문서를 계속 편집하는 것이 적합한 것인지 확인해주세요.
편의를 위해 삭제와 이동 기록을 다음과 같이 제공합니다:",
'moveddeleted-notice'              => '이 문서는 삭제되었습니다. 이 문서의 삭제/이동 기록은 다음과 같습니다.',
'log-fulllog'                      => '전체 기록 보기',
'edit-hook-aborted'                => '훅에 의해 편집이 중단되었습니다.
아무런 설명도 주어지지 않았습니다.',
'edit-gone-missing'                => '문서를 저장하지 못했습니다.
문서가 삭제된 것 같습니다.',
'edit-conflict'                    => '편집 충돌.',
'edit-no-change'                   => '문서에 아무런 변화가 없기 때문에, 당신의 편집은 무시되었습니다.',
'edit-already-exists'              => '새 문서를 만들 수 없습니다.
그 문서는 이미 존재합니다.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''경고:''' 이 문서는 너무 많은 파서 함수를 포함하고 있습니다.

$2개 보다 적게 써야 하지만 지금은 $1개를 쓰고 있습니다.",
'expensive-parserfunction-category'       => '느린 파서 함수 호출을 너무 많이 하는 문서',
'post-expand-template-inclusion-warning'  => '경고: 틀 포함 크기가 너무 큽니다.
일부 틀은 포함되지 않을 수 있습니다.',
'post-expand-template-inclusion-category' => '사용한 틀의 크기가 지나치게 큰 문서의 목록',
'post-expand-template-argument-warning'   => "'''경고:''' 이 문서는 전개 후 크기가 너무 큰 틀 변수가 하나 이상 포함되어 있습니다.
이 변수들은 생략되었습니다.",
'post-expand-template-argument-category'  => '생략된 틀 변수를 포함한 문서',
'parser-template-loop-warning'            => '재귀적인 틀이 발견되었습니다: [[$1]]',
'parser-template-recursion-depth-warning' => '틀 반복 횟수 제한을 초과함($1)',
'language-converter-depth-warning'        => '언어 변환기 실행 제한 초과($1)',

# "Undo" feature
'undo-success' => '이 편집을 되돌리려면 아래의 변경되는 사항을 확인한 후 저장해주세요.',
'undo-failure' => '중간의 다른 편집과 충돌하여 이 편집을 되돌릴 수 없습니다.',
'undo-norev'   => '문서가 없거나 삭제되었기 때문에, 편집을 되돌릴 수 없습니다.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|토론]]) 의 $1 판 편집을 되돌림',

# Account creation failure
'cantcreateaccounttitle' => '계정을 만들 수 없음',
'cantcreateaccount-text' => "현재 아이피 주소('''$1''')는 [[User:$3|$3]] 사용자에 의해 계정 생성이 차단된 상태입니다.

차단 이유는 다음과 같습니다: $2",

# History pages
'viewpagelogs'           => '이 문서의 기록 보기',
'nohistory'              => '이 문서는 편집 역사가 없습니다.',
'currentrev'             => '현재 판',
'currentrev-asof'        => '$1 현재 판',
'revisionasof'           => '$1 판',
'revision-info'          => '$2 사용자의 $1 버전',
'previousrevision'       => '← 이전 판',
'nextrevision'           => '다음 판 →',
'currentrevisionlink'    => '현재 판',
'cur'                    => '현재',
'next'                   => '다음',
'last'                   => '이전',
'page_first'             => '처음',
'page_last'              => '마지막',
'histlegend'             => "비교하려는 판을 선택한 다음 엔터를 누르거나 아래의 버튼을 누르세요.<br />
설명: '''({{int:cur}})''' = 현재 판과의 차이,
'''({{int:last}})''' = 바로 이전 판과의 차이, '''{{int:minoreditletter}}'''= 사소한 편집",
'history-fieldset-title' => '문서의 변경 내력 찾기',
'history-show-deleted'   => '삭제된 것만',
'histfirst'              => '처음',
'histlast'               => '마지막',
'historysize'            => '($1 바이트)',
'historyempty'           => '(비었음)',

# Revision feed
'history-feed-title'          => '편집 역사',
'history-feed-description'    => '이 문서의 편집 역사',
'history-feed-item-nocomment' => '$2에 대한 $1의 편집',
'history-feed-empty'          => '요청한 문서가 존재하지 않습니다.
해당 문서가 삭제되었거나, 문서 이름이 바뀌었을 수 있습니다.
[[Special:Search|검색 기능]]을 사용해 관련 문서를 찾아보세요.',

# Revision deletion
'rev-deleted-comment'         => '(편집 요약 삭제됨)',
'rev-deleted-user'            => '(계정 이름 삭제됨)',
'rev-deleted-event'           => '(로그 삭제됨)',
'rev-deleted-user-contribs'   => '[계정 이름/IP 주소 삭제됨 -  기여 목록에서 숨겨짐]',
'rev-deleted-text-permission' => "해당 편집이 문서 역사에서 '''삭제'''되었습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 볼 수 있습니다.",
'rev-deleted-text-unhide'     => "해당 편집이 문서 역사에서 '''삭제'''되었습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 볼 수 있습니다.
관리자 권한을 이용하여 [$1 해당 편집]을 볼 수도 있습니다.",
'rev-suppressed-text-unhide'  => "해당 편집이 문서 역사에서 '''숨겨져''' 있습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 숨김 기록]에서 볼 수 있습니다.
관리자 권한을 이용하여 [$1 해당 편집]을 볼 수도 있습니다.",
'rev-deleted-text-view'       => "이 문서의 편집은 역사에서 '''삭제'''되었습니다.
당신은 관리자로서 삭제된 편집을 볼 수 있습니다.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-suppressed-text-view'    => "이 문서의 편집은 역사에서 '''숨겨져''' 있습니다.
당신은 관리자로서 숨겨진 편집을 볼 수 있습니다.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 숨김 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-deleted-no-diff'         => "특정 판이 문서 역사에서 '''삭제'''되었기 때문에 비교할 수 없습니다.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-suppressed-no-diff'      => "두 판 중 일부가 '''삭제'''되었기 때문에 문서 편집 내용을 비교할 수 없습니다.",
'rev-deleted-unhide-diff'     => "이 비교에 사용된 판 가운데 하나가 '''삭제'''되었습니다.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에 자세한 내용이 있을 수 있습니다.
관리자로서 작업하고 싶다면 여전히 [$1 비교 보기]를 계속할 수 있습니다.",
'rev-suppressed-unhide-diff'  => "이 비교에 사용된 판 가운데 하나가 '''숨김'''되었습니다.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 숨김 기록]에 자세한 내용이 있을 수 있습니다.
관리자로 작업하고 싶다면 [$1 해당 편집]을 볼 수도 있습니다.",
'rev-deleted-diff-view'       => "비교 대상 중 어느 한 판이 '''삭제'''되었습니다.
관리자로서 삭제된 판과 다른 판의 비교를 할 수 있습니다; 자세한 것은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]을 참고하십시오.",
'rev-suppressed-diff-view'    => "비교하려는 판 중 일부가 '''숨겨져''' 있습니다.
관리자 권한이 있다면 편집 비교가 가능합니다. 자세한 내용은 [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 해당 숨김 기록]에서 볼 수 있습니다.",
'rev-delundel'                => '보이기/숨기기',
'rev-showdeleted'             => '표시',
'revisiondelete'              => '판 삭제/복구',
'revdelete-nooldid-title'     => '대상 판이 잘못되었습니다.',
'revdelete-nooldid-text'      => '이 기능을 수행할 특정 판을 제시하지 않았거나 해당 판이 없습니다. 또는 현재 판을 숨기려 하고 있을 수도 있습니다.',
'revdelete-nologtype-title'   => '기록의 종류가 제시되지 않았습니다.',
'revdelete-nologtype-text'    => '이 명령을 수행할 기록의 종류를 제시하지 않았습니다.',
'revdelete-nologid-title'     => '잘못된 기록',
'revdelete-nologid-text'      => '이 기능을 수행할 특정 기록을 제시하지 않았거나 제시한 기록이 존재하지 않습니다.',
'revdelete-no-file'           => '해당 파일이 존재하지 않습니다.',
'revdelete-show-file-confirm' => '정말 "<nowiki>$1</nowiki>" 파일의 삭제된 $2 $3 버전을 보시겠습니까?',
'revdelete-show-file-submit'  => '예',
'revdelete-selected'          => "'''[[:$1]]의 {{PLURAL:$2|선택된 판}}:'''",
'logdelete-selected'          => "{{PLURAL:$1}}'''선택한 로그:'''",
'revdelete-text'              => "'''삭제된 판과 기록은 문서 역사와 기록에 계속 나타나지만, 내용은 공개되지 않을 것입니다.'''
{{SITENAME}}의 다른 관리자는 다른 제한이 설정되어 있지 않는 한, 숨겨진 내용을 볼 수 있고, 같은 도구를 이용해 복구할 수 있습니다.",
'revdelete-confirm'           => '이 작업을 수행하는 것의 결과를 알고 있으며, [[{{MediaWiki:Policy-url}}|정책]]에 맞는 행동인지 확인해주세요.',
'revdelete-suppress-text'     => "숨기기는 '''다음 경우에만''' 사용되어야 합니다:
* 부적절한 개인 정보
*: 집 주소, 전화번호, 주민등록번호 등",
'revdelete-legend'            => '보이기 제한을 설정',
'revdelete-hide-text'         => '판의 내용을 숨기기',
'revdelete-hide-image'        => '파일을 숨기기',
'revdelete-hide-name'         => '로그 내용과 대상을 숨기기',
'revdelete-hide-comment'      => '편집 요약을 숨기기',
'revdelete-hide-user'         => '편집자의 계정 이름/IP를 숨기기',
'revdelete-hide-restricted'   => '관리자도 보지 못하게 숨기기',
'revdelete-radio-same'        => '(바꾸지 않음)',
'revdelete-radio-set'         => '예',
'revdelete-radio-unset'       => '아니오',
'revdelete-suppress'          => '문서 내용을 관리자에게도 보이지 않게 숨기기',
'revdelete-unsuppress'        => '복구된 판에 대한 제한을 해제',
'revdelete-log'               => '이유:',
'revdelete-submit'            => '선택한 {{PLURAL:$1|판}}에 적용',
'revdelete-logentry'          => '[[$1]]의 판의 보이기 설정을 변경함',
'logdelete-logentry'          => '[[$1]]의 로그 보이기 설정을 변경함',
'revdelete-success'           => "'''판의 보이기 설정이 성공적으로 변경되었습니다.'''",
'revdelete-failure'           => "'''특정 판 보기 설정을 바꾸지 못했습니다:'''
$1",
'logdelete-success'           => "'''로그의 보이기 설정이 성공적으로 변경되었습니다.'''",
'logdelete-failure'           => "'''로그 보이기 설정을 바꾸지 못했습니다:'''
$1",
'revdel-restore'              => '보이기 설정 변경',
'revdel-restore-deleted'      => '삭제된 판',
'revdel-restore-visible'      => '공개된 판',
'pagehist'                    => '문서 역사',
'deletedhist'                 => '삭제된 역사',
'revdelete-content'           => '내용',
'revdelete-summary'           => '편집 요약',
'revdelete-uname'             => '사용자 이름',
'revdelete-restricted'        => '관리자에게 제한을 적용함',
'revdelete-unrestricted'      => '관리자에 대한 제한을 해제함',
'revdelete-hid'               => '$1을 숨김',
'revdelete-unhid'             => '$1을 숨김 해제함',
'revdelete-log-message'       => '$2개의 판에 대해 $1',
'logdelete-log-message'       => '$2개의 로그에 대해 $1',
'revdelete-hide-current'      => '$1 $2 판을 숨기는 도중 오류 발생: 이 판은 현재 판입니다.
현재 판은 숨길 수 없습니다.',
'revdelete-show-no-access'    => '$1 $2 판을 보이는 데 오류 발생: 이 판은 "제한"으로 표시되어 있습니다.
여기에 접근할 수 없습니다.',
'revdelete-modify-no-access'  => '$1 $2 판을 고치는 데 오류 발생: 이 판은 "제한"으로 표시되어 있습니다.
여기에 접근할 수 없습니다.',
'revdelete-modify-missing'    => '판 ID $1을 수정하는 중 오류 발생: 데이터베이스에 존재하지 않습니다!',
'revdelete-no-change'         => "'''경고:''' $1 $2에 해당하는 항목은 이미 같은 보이기 설정이 설정되어 있습니다.",
'revdelete-concurrent-change' => '$1 $2에 수정된 항목을 갱신하면서 오류 발생: 이런 현상은 당신이 문서를 편집하고 있을 때 다른 사람이 문서를 편집했기 때문에 발생합니다.
관련 로그를 확인해 보세요.',
'revdelete-only-restricted'   => '$1 $2 버전 숨기기 오류: 다른 숨기기 설정을 같이 설정하지 않고 관리자가 보지 못하도록 특정 판을 숨길 수 없습니다.',
'revdelete-reason-dropdown'   => '*일반적인 삭제 이유
** 저작권 침해
** 부적절한 개인 정보',
'revdelete-otherreason'       => '다른 이유/부가적인 이유',
'revdelete-reasonotherlist'   => '다른 이유',
'revdelete-edit-reasonlist'   => '삭제 이유 편집',
'revdelete-offender'          => '판 작성자:',

# Suppression log
'suppressionlog'     => '숨기기 기록',
'suppressionlogtext' => '다음은 관리자로부터 숨겨진 내용에 대한 삭제, 차단 기록입니다.
현재 차단된 사용자 목록을 보시려면 [[Special:IPBlockList|차단된 사용자 목록]]을 참조하십시오.',

# Revision move
'moverevlogentry'              => '$1 문서의 편집 $3개를 $2 문서로 옮김',
'revisionmove'                 => '"$1" 문서에서 특정 판 옮기기',
'revmove-explain'              => '다음 판은 $1 문서에서 지정된 문서로 옮겨질 것입니다. 이동시킬 문서가 없다면 문서를 생성할 것이지만 그렇지 않다면 해당 판은 문서 역사에 합쳐질 것입니다.',
'revmove-legend'               => '옮길 문서와 이유',
'revmove-submit'               => '선택한 문서에 해당 편집을 옮기기',
'revisionmoveselectedversions' => '선택한 편집을 옮기기',
'revmove-reasonfield'          => '이유:',
'revmove-titlefield'           => '대상 문서:',
'revmove-badparam-title'       => '잘못된 변수',
'revmove-badparam'             => '당신의 요청이 잘못된 변수를 갖고 있거나 변수 내용이 부족합니다. "뒤로"를 클릭하여 다시 시도해 보십시오.',
'revmove-norevisions-title'    => '대상 판 선택이 잘못됨',
'revmove-norevisions'          => '당신은 이 기능을 수행할 대상 판을 설정하지 않았거나 존재하지 않는 판을 선택하였습니다.',
'revmove-nullmove-title'       => '제목이 잘못됨',
'revmove-nullmove'             => '옮기기 전 문서와 대상 문서의 제목이 같습니다. "뒤로"를 클릭하여 "$1"과 다른 문서 이름을 입력해주세요.',
'revmove-success-existing'     => '[[$2]] 문서의 편집 $1개가 [[$3]] 문서로 옮겨졌습니다.',
'revmove-success-created'      => '[[$2]] 문서의 편집 $1개가 [[$3]] 문서로 옮기면서 새 문서를 생성했습니다.',

# History merging
'mergehistory'                     => '문서 역사 합치기',
'mergehistory-header'              => '이 문서는 한 문서에서 다른 문서로 문서 역사를 합치게 할 것입니다.
이전 문서를 역사적 기록으로 계속 남겨둘 것인지 확인해주세요.',
'mergehistory-box'                 => '두 문서의 역사 합치기:',
'mergehistory-from'                => '원본 문서 이름:',
'mergehistory-into'                => '새 문서 이름:',
'mergehistory-list'                => '병합 가능한 문서 역사',
'mergehistory-merge'               => '[[:$1]] 문서의 다음 판이 [[:$2]] 문서로 병합될 수 있습니다.
병합하려는 판과 그 이전의 판을 선택하시려면 라디오 버튼을 이용해주세요.
둘러보기 링크를 이용하는 것은 이 문서를 초기화시킬 것입니다.',
'mergehistory-go'                  => '합칠 수 있는 편집 보기',
'mergehistory-submit'              => '문서 역사 합치기',
'mergehistory-empty'               => '합칠 수 있는 판이 없습니다.',
'mergehistory-success'             => '[[:$1]] 문서의 $3개의 판이 [[:$2]]에 성공적으로 합쳐졌습니다.',
'mergehistory-fail'                => '문서 역사 합치기 명령을 수행할 수 없습니다. 문서와 시간 변수를 다시 확인하십시오.',
'mergehistory-no-source'           => '원본인 $1 문서가 존재하지 않습니다.',
'mergehistory-no-destination'      => '대상인 $1 문서가 존재하지 않습니다.',
'mergehistory-invalid-source'      => '원본 문서 이름에는 반드시 유효한 제목을 입력해야 합니다.',
'mergehistory-invalid-destination' => '대상 문서 이름에는 반드시 유효한 제목을 입력해야 합니다.',
'mergehistory-autocomment'         => '[[:$1]] 문서를 [[:$2]] 문서로 합침',
'mergehistory-comment'             => '[[:$1]] 문서를 [[:$2]] 문서로 합침: $3',
'mergehistory-same-destination'    => '원본 문서 이름과 새 문서 이름은 달라야 합니다',
'mergehistory-reason'              => '이유:',

# Merge log
'mergelog'           => '합병 기록',
'pagemerge-logentry' => '[[$1]]을 [[$2]]에 병합 ($3판이 위로 옮겨짐)',
'revertmerge'        => '병합 해제',
'mergelogpagetext'   => '다음은 한 문서의 역사를 다른 문서의 역사와 합친 최근 기록입니다.',

# Diffs
'history-title'            => '‘$1’ 문서의 변경 내력',
'difference'               => '(버전 사이의 차이)',
'lineno'                   => '$1번째 줄:',
'compareselectedversions'  => '선택된 판들을 비교하기',
'showhideselectedversions' => '선택한 판을 보이기/숨기기',
'editundo'                 => '편집 취소',
'diff-multi'               => '(중간의 편집 $1 개 숨겨짐)',

# Search results
'searchresults'                    => '검색 결과',
'searchresults-title'              => '"$1"에 대한 검색 결과',
'searchresulttext'                 => '{{SITENAME}}의 찾기 기능에 대한 자세한 정보는 [[{{MediaWiki:Helppage}}|{{int:help}}]] 문서를 참고해주세요.',
'searchsubtitle'                   => "'''[[:$1]]''' 문서를 검색하고 있습니다. ([[Special:Prefixindex/$1|이름이 ‘$1’ 접두어로 시작하는 문서 목록]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|‘$1’ 문서를 가리키는 문서 목록]])",
'searchsubtitleinvalid'            => "검색 단어 '''$1'''",
'toomanymatches'                   => '일치하는 결과가 너무 많습니다. 다른 검색어를 입력해주세요.',
'titlematches'                     => '문서 제목 일치',
'notitlematches'                   => '해당하는 제목 없음',
'textmatches'                      => '문서 내용 일치',
'notextmatches'                    => '해당하는 문서 없음',
'prevn'                            => '이전 $1개',
'nextn'                            => '다음 $1개',
'prevn-title'                      => '이전 결과 $1개',
'nextn-title'                      => '다음 결과 $1개',
'shown-title'                      => '매 쪽마다 결과 $1 개씩 보이기',
'viewprevnext'                     => '보기: ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => '검색 설정',
'searchmenu-exists'                => "'''이 위키에 \"[[:\$1]]\"의 이름을 가진 문서가 있습니다.'''",
'searchmenu-new'                   => "'''이 위키에 \"[[:\$1]]\" 문서를 만드십시오!'''",
'searchhelp-url'                   => 'Help:목차',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|이 접두어로 시작하는 문서 찾기]]',
'searchprofile-articles'           => '일반 문서',
'searchprofile-project'            => '도움말 및 프로젝트 문서',
'searchprofile-images'             => '멀티미디어',
'searchprofile-everything'         => '모든 문서 검색',
'searchprofile-advanced'           => '고급 검색',
'searchprofile-articles-tooltip'   => '$1에서 찾기',
'searchprofile-project-tooltip'    => '$1에서 찾기',
'searchprofile-images-tooltip'     => '파일 찾기',
'searchprofile-everything-tooltip' => '토론 문서를 포함한 모든 문서 찾기',
'searchprofile-advanced-tooltip'   => '다음 설정된 이름공간에서 찾기',
'search-result-size'               => '$1 ($2 단어)',
'search-result-category-size'      => '문서 {{PLURAL:$1|1|$1}}개, 하위 분류 {{PLURAL:$2|1|$2}}개, 파일 {{PLURAL:$3|1|$3}}',
'search-result-score'              => '유사도: $1%',
'search-redirect'                  => '($1에서 넘어옴)',
'search-section'                   => '($1 문단)',
'search-suggest'                   => '$1 문서를 찾고 계신가요?',
'search-interwiki-caption'         => '자매 프로젝트',
'search-interwiki-default'         => '$1 결과:',
'search-interwiki-more'            => '(더 보기)',
'search-mwsuggest-enabled'         => '검색어 제안 있음',
'search-mwsuggest-disabled'        => '검색어 제안 없음',
'search-relatedarticle'            => '관련',
'mwsuggest-disable'                => 'AJAX 추천 기능 끄기',
'searcheverything-enable'          => '모든 이름공간에서 찾기',
'searchrelated'                    => '관련',
'searchall'                        => '모두',
'showingresults'                   => '<strong>$2</strong>번 부터의 <strong>결과 $1개</strong>입니다.',
'showingresultsnum'                => "'''$2'''번 부터의 '''결과 $3개''' 입니다.",
'showingresultsheader'             => "'''$4''' 검색어에 대하여 '''$3'''개의 결과 중 {{PLURAL:$5|'''$1'''개|'''$1 - $2'''번째}}를 보여 주고 있습니다.",
'nonefound'                        => "'''참고''': 몇개의 이름공간만 기본 검색 범위입니다. 토론이나 틀 등의 모든 자료를 검색하기 위해서는 접두어로 '''all:''' 어떤 이름공간을 위해서는 접두어로 그 이름공간을 쓸 수 있습니다.",
'search-nonefound'                 => '검색 결과가 없습니다.',
'powersearch'                      => '찾기',
'powersearch-legend'               => '고급 검색',
'powersearch-ns'                   => '다음의 이름공간에서 찾기:',
'powersearch-redir'                => '넘겨주기 목록',
'powersearch-field'                => '검색',
'powersearch-togglelabel'          => '확인:',
'powersearch-toggleall'            => '모두 선택',
'powersearch-togglenone'           => '모두 선택하지 않음',
'search-external'                  => '외부 검색',
'searchdisabled'                   => '{{SITENAME}} 검색 기능이 비활성화되어 있습니다. 기능이 작동하지 않는 동안에는 구글(Google)을 이용해 검색할 수 있습니다. 검색 엔진의 내용은 최근 것이 아닐 수 있다는 점을 주의해주세요.',

# Quickbar
'qbsettings'               => '빨리가기 맞춤',
'qbsettings-none'          => '없음',
'qbsettings-fixedleft'     => '왼쪽 고정',
'qbsettings-fixedright'    => '오른쪽 고정',
'qbsettings-floatingleft'  => '왼쪽 유동',
'qbsettings-floatingright' => '오른쪽 유동',

# Preferences page
'preferences'                   => '사용자 환경 설정',
'mypreferences'                 => '사용자 환경 설정',
'prefs-edits'                   => '편집 횟수:',
'prefsnologin'                  => '로그인하지 않음',
'prefsnologintext'              => '사용자 환경 설정을 바꾸려면 먼저 <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 로그인]</span>해야 합니다.',
'changepassword'                => '비밀번호 바꾸기',
'prefs-skin'                    => '스킨',
'skin-preview'                  => '미리 보기',
'prefs-math'                    => '수식',
'datedefault'                   => '기본값',
'prefs-datetime'                => '날짜와 시각',
'prefs-personal'                => '사용자 정보',
'prefs-rc'                      => '최근 바뀜',
'prefs-watchlist'               => '주시문서 목록',
'prefs-watchlist-days'          => '주시문서 목록에서 볼 날짜 수:',
'prefs-watchlist-days-max'      => '(최대 7일)',
'prefs-watchlist-edits'         => '주시문서 목록에서 볼 편집 수:',
'prefs-watchlist-edits-max'     => '(최대 1000개)',
'prefs-watchlist-token'         => '주시문서 토큰:',
'prefs-misc'                    => '기타',
'prefs-resetpass'               => '비밀번호 바꾸기',
'prefs-email'                   => '이메일 설정',
'prefs-rendering'               => '문서 보이기 설정',
'saveprefs'                     => '저장',
'resetprefs'                    => '저장하지 않은 설정 되돌리기',
'restoreprefs'                  => '모두 기본 설정으로 되돌리기',
'prefs-editing'                 => '편집상자',
'prefs-edit-boxsize'            => '편집 창의 크기',
'rows'                          => '줄 수:',
'columns'                       => '열 수:',
'searchresultshead'             => '찾기',
'resultsperpage'                => '쪽마다 보이는 결과 수:',
'contextlines'                  => '결과마다 보이는 줄 수:',
'contextchars'                  => '각 줄에 보이는 글 수:',
'stub-threshold'                => '링크를 <a href="#" class="stub">토막글</a> 형식으로 표시할 문서 크기 (바이트 수):',
'recentchangesdays'             => '최근 바뀜에 표시할 날짜 수:',
'recentchangesdays-max'         => '(최대 $1일)',
'recentchangescount'            => '기본으로 표시할 편집 수:',
'prefs-help-recentchangescount' => '이 설정은 최근 바뀜, 문서 역사와 기록에 적용됩니다.',
'prefs-help-watchlist-token'    => '아래에 비밀 값을 넣으면 주시문서에 대한 RSS 피드가 생성됩니다.
비밀 값을 알고 있는 사람이라면 누구나 피드를 읽을 수 있으므로 안전한 값을 입력해주세요.
임의로 만들어진 다음 값을 사용할 수도 있습니다: $1',
'savedprefs'                    => '설정을 저장했습니다.',
'timezonelegend'                => '시간대:',
'localtime'                     => '현지 시각:',
'timezoneuseserverdefault'      => '서버 기본값을 사용',
'timezoneuseoffset'             => '기타 (시차를 입력해주세요)',
'timezoneoffset'                => '시차¹:',
'servertime'                    => '서버 시각:',
'guesstimezone'                 => '웹 브라우저 설정에서 가져오기',
'timezoneregion-africa'         => '아프리카',
'timezoneregion-america'        => '아메리카',
'timezoneregion-antarctica'     => '남극',
'timezoneregion-arctic'         => '북극',
'timezoneregion-asia'           => '아시아',
'timezoneregion-atlantic'       => '대서양',
'timezoneregion-australia'      => '오스트레일리아',
'timezoneregion-europe'         => '유럽',
'timezoneregion-indian'         => '인도양',
'timezoneregion-pacific'        => '태평양',
'allowemail'                    => '다른 사용자에게서 이메일을 받음',
'prefs-searchoptions'           => '검색 설정',
'prefs-namespaces'              => '이름공간',
'defaultns'                     => '다음 이름공간에서 검색하기:',
'default'                       => '기본값',
'prefs-files'                   => '파일',
'prefs-custom-css'              => '사용자 CSS',
'prefs-custom-js'               => '사용자 자바스크립트',
'prefs-common-css-js'           => '모든 스킨에 적용되는 공통 CSS/자바스크립트:',
'prefs-reset-intro'             => '이 사이트의 기본 값으로 환경 설정을 되돌릴 수 있습니다.
복구할 수 없습니다.',
'prefs-emailconfirm-label'      => '이메일 인증:',
'prefs-textboxsize'             => '편집창의 크기',
'youremail'                     => '이메일:',
'username'                      => '계정 이름:',
'uid'                           => '사용자 ID:',
'prefs-memberingroups'          => '가지고 있는 {{PLURAL:$1|권한}} 목록:',
'prefs-registration'            => '등록 일시:',
'yourrealname'                  => '실명:',
'yourlanguage'                  => '언어:',
'yournick'                      => '서명:',
'prefs-help-signature'          => '토론 문서에 글을 남기실 때는 서명을 해 주세요. “<nowiki>~~~~</nowiki>”를 입력하시면 서명과 시간이 자동으로 입력됩니다.',
'badsig'                        => '서명이 잘못되었습니다. HTML 태그를 확인해주세요.',
'badsiglength'                  => '서명이 너무 깁니다.
서명은 $1자보다 짧아야 합니다.',
'yourgender'                    => '성별:',
'gender-unknown'                => '무응답',
'gender-male'                   => '남성',
'gender-female'                 => '여성',
'prefs-help-gender'             => '선택 사항: 소프트웨어에서 성별에 따른 언어 문제를 해결하기 위해 사용됩니다. 이 정보는 공개됩니다.',
'email'                         => '이메일',
'prefs-help-realname'           => '실명 기입은 자유입니다. 실명을 입력할 경우 문서 기여에 자신의 이름이 들어가게 됩니다.',
'prefs-help-email'              => '이메일 주소 입력은 선택 사항입니다. 이메일을 등록해 두면 비밀번호를 잊었을 때 다른 비밀번호를 받을 수 있으며, 이메일을 공개하지 않고도 다른 사용자에게 메일을 받을 수 있습니다.',
'prefs-help-email-required'     => '이메일 주소가 필요합니다.',
'prefs-info'                    => '기본 정보',
'prefs-i18n'                    => '언어 설정',
'prefs-signature'               => '서명',
'prefs-dateformat'              => '날짜 형식',
'prefs-timeoffset'              => '시차 설정',
'prefs-advancedediting'         => '고급 설정',
'prefs-advancedrc'              => '고급 설정',
'prefs-advancedrendering'       => '고급 설정',
'prefs-advancedsearchoptions'   => '고급 설정',
'prefs-advancedwatchlist'       => '고급 설정',
'prefs-displayrc'               => '보이기 설정',
'prefs-displaysearchoptions'    => '표시 설정',
'prefs-displaywatchlist'        => '표시 설정',
'prefs-diffs'                   => '차이',

# User rights
'userrights'                   => '사용자 권한 관리',
'userrights-lookup-user'       => '사용자 권한 관리',
'userrights-user-editname'     => '계정 이름:',
'editusergroup'                => '사용자 그룹 편집',
'editinguser'                  => "'''[[User:$1|$1]]'''([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) 사용자의 권한 변경",
'userrights-editusergroup'     => '사용자 그룹 편집',
'saveusergroups'               => '사용자 권한 저장',
'userrights-groupsmember'      => '현재 권한:',
'userrights-groupsmember-auto' => '자동으로 부여된 권한:',
'userrights-groups-help'       => '이 사용자의 권한을 바꿀 수 있습니다.
* 사용자는 체크 표시가 있는 권한을 갖습니다.
* 사용자는 체크 표시가 없는 권한을 갖지 않습니다.
* <nowiki>*</nowiki>표시는 권한을 주거나 거두는 것 중 하나만 할 수 있다는 뜻입니다.',
'userrights-reason'            => '이유:',
'userrights-no-interwiki'      => '다른 위키의 사용자 권한을 바꿀 권한이 없습니다.',
'userrights-nodatabase'        => '데이터베이스 $1이 존재하지 않거나 로컬에 있지 않습니다.',
'userrights-nologin'           => '사용자의 권한을 바꾸기 위해서는 반드시 관리자 계정으로 [[Special:UserLogin|로그인]]해야 합니다.',
'userrights-notallowed'        => '다른 사용자의 권한을 조정할 권한이 없습니다.',
'userrights-changeable-col'    => '바꿀 수 있는 권한',
'userrights-unchangeable-col'  => '바꿀 수 없는 권한',

# Groups
'group'               => '권한:',
'group-user'          => '사용자',
'group-autoconfirmed' => '자동 인증된 사용자',
'group-bot'           => '봇',
'group-sysop'         => '관리자',
'group-bureaucrat'    => '사무관',
'group-suppress'      => '오버사이트',
'group-all'           => '(모두)',

'group-user-member'          => '사용자',
'group-autoconfirmed-member' => '자동 인증된 사용자',
'group-bot-member'           => '봇',
'group-sysop-member'         => '관리자',
'group-bureaucrat-member'    => '사무관',
'group-suppress-member'      => '오버사이트',

'grouppage-user'          => '{{ns:project}}:일반 사용자',
'grouppage-autoconfirmed' => '{{ns:project}}:자동 인증된 사용자',
'grouppage-bot'           => '{{ns:project}}:봇',
'grouppage-sysop'         => '{{ns:project}}:관리자',
'grouppage-bureaucrat'    => '{{ns:project}}:사무관',
'grouppage-suppress'      => '{{ns:project}}:오버사이트',

# Rights
'right-read'                  => '문서 읽기',
'right-edit'                  => '문서 편집',
'right-createpage'            => '문서 생성 (토론 문서 제외)',
'right-createtalk'            => '토론 문서 생성',
'right-createaccount'         => '새 계정 만들기',
'right-minoredit'             => '사소한 편집 사용 가능',
'right-move'                  => '문서 이동',
'right-move-subpages'         => '문서와 하위 문서 이동하기',
'right-move-rootuserpages'    => '최상위 사용자 문서 이동',
'right-movefile'              => '파일을 옮기기',
'right-suppressredirect'      => '문서 이동할 때 옛날 이름으로 된 넘겨주기를 남기지 않기',
'right-upload'                => '파일 올리기',
'right-reupload'              => '이미 존재하는 파일을 다시 업로드',
'right-reupload-own'          => '자신이 이미 올린 파일 덮어쓰기',
'right-reupload-shared'       => '공용의 파일을 무시하고 로컬에서 파일 올리기',
'right-upload_by_url'         => 'URL 주소에서 파일 올리기',
'right-purge'                 => '확인 없이 문서의 캐시를 갱신',
'right-autoconfirmed'         => '준보호된 문서 편집',
'right-bot'                   => '봇의 편집으로 취급',
'right-nominornewtalk'        => '토론 문서를 새로 만들때 사소한 편집 사용 불가능',
'right-apihighlimits'         => 'API 상한 상승',
'right-writeapi'              => 'API 작성',
'right-delete'                => '문서 삭제',
'right-bigdelete'             => '문서 역사가 긴 문서를 삭제',
'right-deleterevision'        => '문서의 특정 판을 삭제 및 복구',
'right-deletedhistory'        => '삭제된 문서의 내용을 제외한 역사를 보기',
'right-deletedtext'           => '삭제된 문서의 내용과 편집상의 차이를 보기',
'right-browsearchive'         => '삭제된 문서 검색',
'right-undelete'              => '삭제된 문서 복구',
'right-suppressrevision'      => '관리자도 보지 못하도록 숨겨진 판의 확인 및 복구',
'right-suppressionlog'        => '숨겨진 기록을 보기',
'right-block'                 => '다른 사용자를 편집을 못하도록 차단',
'right-blockemail'            => '다른 사용자가 이메일을 보내지 못하도록 차단',
'right-hideuser'              => '사용자 이름을 차단하고 숨김',
'right-ipblock-exempt'        => 'IP 차단, 자동 차단, 광역 차단을 무시',
'right-proxyunbannable'       => '프록시 자동 차단을 우회',
'right-unblockself'           => '자기 자신을 차단 해제하기',
'right-protect'               => '보호 수준 변경 및 보호된 문서 편집',
'right-editprotected'         => '보호된 문서 편집 (연쇄적 보호 제외)',
'right-editinterface'         => '사용자 인터페이스를 편집',
'right-editusercssjs'         => '다른 사용자의 CSS와 JS 문서를 편집',
'right-editusercss'           => '다른 사용자의 CSS 문서를 편집',
'right-edituserjs'            => '다른 사용자의 JS 문서를 편집',
'right-rollback'              => '특정 문서를 편집한 마지막 사용자의 편집을 신속하게 되돌리기',
'right-markbotedits'          => '되돌리기를 봇의 편집으로 취급 가능',
'right-noratelimit'           => '편집이나 다른 행동 속도의 제한을 받지 않음',
'right-import'                => '다른 위키에서 문서 가져오기',
'right-importupload'          => '파일 업로드를 통해 문서 가져오기',
'right-patrol'                => '다른 사용자의 편집을 검토',
'right-autopatrol'            => '자신의 편집을 자동으로 검토',
'right-patrolmarks'           => '최근 바뀜에서 검토 표시를 보기',
'right-unwatchedpages'        => '주시되지 않은 문서의 목록을 보기',
'right-trackback'             => '트랙백 보내기',
'right-mergehistory'          => '문서의 역사를 합침',
'right-userrights'            => '모든 사용자의 권한 조정',
'right-userrights-interwiki'  => '다른 위키의 사용자 권한을 조정',
'right-siteadmin'             => '데이터베이스를 잠그거나 잠금 해제',
'right-reset-passwords'       => '다른 사용자의 비밀번호를 변경',
'right-override-export-depth' => '5단계로 링크된 문서를 포함하여 문서를 내보내기',
'right-sendemail'             => '다른 사용자에게 이메일 보내기',
'right-revisionmove'          => '특정 판 옮기기',
'right-selenium'              => '셀레늄 테스트 실행',

# User rights log
'rightslog'      => '사용자 권한 기록',
'rightslogtext'  => '사용자 권한 조정 기록입니다.',
'rightslogentry' => '$1의 권한을 $2에서 $3(으)로 변경',
'rightsnone'     => '(없음)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '이 문서를 읽기',
'action-edit'                 => '문서 편집',
'action-createpage'           => '문서 생성',
'action-createtalk'           => '토론 문서 생성하기',
'action-createaccount'        => '새 계정 만들기',
'action-minoredit'            => '이 편집을 사소한 편집으로 표시하기',
'action-move'                 => '이 문서 옮기기',
'action-move-subpages'        => '하위 문서를 함께 옮길',
'action-move-rootuserpages'   => '최상위 사용자 문서를 이동할',
'action-movefile'             => '이 파일을 옮길',
'action-upload'               => '이 파일을 올리기',
'action-reupload'             => '이미 존재하는 파일 덮어쓰기',
'action-reupload-shared'      => '공용 저장소의 파일을 무시하고 저장할',
'action-upload_by_url'        => 'URL 주소를 통해 이 파일을 올리기',
'action-writeapi'             => 'API를 작성할',
'action-delete'               => '이 문서 삭제하기',
'action-deleterevision'       => '이 판을 삭제',
'action-deletedhistory'       => '이 문서의 삭제된 기여의 역사 보기',
'action-browsearchive'        => '삭제된 문서 찾기',
'action-undelete'             => '이 문서를 복구하기',
'action-suppressrevision'     => '이 숨겨진 판을 검토하고 복구할',
'action-suppressionlog'       => '비공개 로그를 볼',
'action-block'                => '이 사용자를 편집하지 못하도록 차단',
'action-protect'              => '이 문서의 보호 설정을 변경하기',
'action-import'               => '다른 위키에서 이 문서를 가져오기',
'action-importupload'         => '파일 올리기를 통해 문서를 가져올',
'action-patrol'               => '다른 사용자의 편집을 검토된 것으로 표시하기',
'action-autopatrol'           => '자신의 편집을 검토된 것으로 표시할',
'action-unwatchedpages'       => '주시되지 않은 문서의 목록을 볼',
'action-trackback'            => '트랙백 보내기',
'action-mergehistory'         => '이 문서의 역사 합치기',
'action-userrights'           => '모든 사용자의 권한을 조정',
'action-userrights-interwiki' => '다른 위키의 사용자 권한을 조정',
'action-siteadmin'            => '데이터베이스를 잠그거나 잠금 해제하기',
'action-revisionmove'         => '특정 판을 이동할',

# Recent changes
'nchanges'                          => '$1개 바뀜',
'recentchanges'                     => '최근 바뀜',
'recentchanges-legend'              => '최근 바뀜 설정',
'recentchangestext'                 => '위키의 최근 바뀜 내역이 나와 있습니다.',
'recentchanges-feed-description'    => '위키의 최근 바뀜',
'recentchanges-label-legend'        => '범례: $1.',
'recentchanges-legend-newpage'      => '$1 - 새 문서',
'recentchanges-label-newpage'       => '새로운 문서',
'recentchanges-legend-minor'        => '$1 - 사소한 편집',
'recentchanges-label-minor'         => '사소한 편집',
'recentchanges-legend-bot'          => '$1 - 봇 편집',
'recentchanges-label-bot'           => '봇의 편집',
'recentchanges-legend-unpatrolled'  => '$1 - 검토되지 않은 편집',
'recentchanges-label-unpatrolled'   => '아직 검토되지 않은 편집',
'rcnote'                            => "다음은 $4 $5 까지의 '''$2'''일동안 바뀐 '''$1'''개의 문서입니다.",
'rcnotefrom'                        => "다음은 '''$2'''에서부터 바뀐 '''$1'''개의 문서입니다.",
'rclistfrom'                        => '$1 이래로 바뀐 문서',
'rcshowhideminor'                   => '사소한 편집을 $1',
'rcshowhidebots'                    => '봇을 $1',
'rcshowhideliu'                     => '등록 사용자를 $1',
'rcshowhideanons'                   => '익명 사용자를 $1',
'rcshowhidepatr'                    => '검토된 편집을 $1',
'rcshowhidemine'                    => '내 편집을 $1',
'rclinks'                           => '최근 $2일간의 $1개 변경 기록 보기<br />$3',
'diff'                              => '비교',
'hist'                              => '역사',
'hide'                              => '숨기기',
'show'                              => '보이기',
'minoreditletter'                   => '잔글',
'newpageletter'                     => '새글',
'boteditletter'                     => '봇',
'number_of_watching_users_pageview' => '[$1명이 주시하고 있음]',
'rc_categories'                     => '다음 분류로 제한 (‘|’로 구분)',
'rc_categories_any'                 => '모두',
'newsectionsummary'                 => '새 주제: /* $1 */',
'rc-enhanced-expand'                => '세부 기록 보기 (자바스크립트 필요)',
'rc-enhanced-hide'                  => '세부기록 숨기기',

# Recent changes linked
'recentchangeslinked'          => '가리키는 글의 바뀜',
'recentchangeslinked-feed'     => '가리키는 글의 바뀜',
'recentchangeslinked-toolbox'  => '가리키는 글의 바뀜',
'recentchangeslinked-title'    => '‘$1’ 문서에 관련된 문서 바뀜',
'recentchangeslinked-noresult' => '이 문서에서 링크하는 문서 중, 해당 기간에 바뀐 문서가 없습니다.',
'recentchangeslinked-summary'  => "여기를 가리키는 문서(분류일 경우 이 분류에 포함된 문서)에 대한 최근 바뀜이 나와 있습니다.
[[Special:Watchlist|주시하는 문서]]는 '''굵은''' 글씨로 나타납니다.",
'recentchangeslinked-page'     => '문서 이름:',
'recentchangeslinked-to'       => '여기를 가리키는 문서의 최근 바뀜',

# Upload
'upload'                      => '파일 올리기',
'uploadbtn'                   => '파일 올리기',
'reuploaddesc'                => '올리기를 취소하고 올리기 양식으로 돌아가기',
'upload-tryagain'             => '수정된 파일 설명을 저장',
'uploadnologin'               => '로그인하지 않음',
'uploadnologintext'           => '파일을 올리려면 [[Special:UserLogin|로그인]]해야 합니다.',
'upload_directory_missing'    => '파일 올리기용 디렉토리($1)가 없고 웹 서버가 생성하지 못했습니다.',
'upload_directory_read_only'  => '파일 저장 디렉토리($1)에 쓰기 권한이 없습니다.',
'uploaderror'                 => '올리기 오류',
'upload-recreate-warning'     => "'''경고: 이 파일로 된 이름이 삭제되었거나 옮겨졌습니다.'''

이 문서의 최근 삭제 기록과 이동 기록을 참고하십시오:",
'uploadtext'                  => "파일을 올리기 위해서는 아래의 양식을 채워주세요.
[[Special:FileList|파일 목록]]에서 이전에 올라온 파일을 찾을 수 있습니다. [[Special:Log/upload|올리기 기록]]에는 파일이 올라온 기록이 남습니다. 삭제 기록은 [[Special:Log/delete|삭제 로그]]에서 볼 수 있습니다.

문서에 파일을 넣으려면 아래 방법 중 하나를 사용하세요.
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' 파일의 온전한 모양을 사용하고자 할 때.
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200픽셀|섬네일|왼쪽|설명]]</nowiki></tt>''' 파일의 넓이를 200픽셀로 하고 왼쪽 정렬하며 '설명' 이라는 주석을 파일 밑에 달 때.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' 파일을 직접 보여주지 않고 파일로 바로 링크할때.",
'upload-permitted'            => '허용하는 파일 확장자: $1',
'upload-preferred'            => '권장하는 파일 확장자: $1',
'upload-prohibited'           => '금지하는 파일 확장자: $1',
'uploadlog'                   => '올리기 기록',
'uploadlogpage'               => '올리기 기록',
'uploadlogpagetext'           => '최근 올라온 파일 목록입니다.
갤러리 형식으로 확인하고 싶으시다면 [[Special:NewFiles|새 파일 목록]]을 보세요.',
'filename'                    => '파일 이름',
'filedesc'                    => '파일의 설명',
'fileuploadsummary'           => '설명:',
'filereuploadsummary'         => '파일 변경에 대한 요약:',
'filestatus'                  => '저작권 상태:',
'filesource'                  => '출처:',
'uploadedfiles'               => '파일 올리기',
'ignorewarning'               => '경고를 무시하고 저장합니다',
'ignorewarnings'              => '모든 경고 무시하기',
'minlength1'                  => '파일 이름은 적어도 1글자 이상이어야 합니다.',
'illegalfilename'             => '파일 이름 "$1"에는 문서 제목으로 허용되지 않는 글자가 포함되어 있습니다. 이름을 바꾸어 다시 시도해 주세요.',
'badfilename'                 => '파일 이름이 ‘$1’(으)로 바뀌었습니다.',
'filetype-mime-mismatch'      => '파일 확장자와 MIME가 일치하지 않습니다.',
'filetype-badmime'            => '‘$1’ MIME을 가진 파일은 올릴 수 없습니다.',
'filetype-bad-ie-mime'        => '인터넷 익스플로러가 잠재적으로 위험한 파일 형식으로 판단되어 사용이 금지된 "$1"로 인식할 수 있기 때문에 이 파일을 올릴 수 없습니다.',
'filetype-unwanted-type'      => "'''\".\$1\"''' 확장자는 추천하지 않습니다. 추천하는 {{PLURAL:\$3|파일 확장자}}는 \$2입니다.",
'filetype-banned-type'        => "{{plural:$3}}‘'''.$1'''’ 형식의 파일은 올릴 수 없습니다. $2 형식만 사용할 수 있습니다.",
'filetype-missing'            => '파일에 확장자(‘.jpg’ 등)가 없습니다.',
'empty-file'                  => '당신이 올린 파일이 비어 있습니다.',
'file-too-large'              => '올리려는 파일이 너무 큽니다.',
'filename-tooshort'           => '파일 이름이 너무 짧습니다.',
'filetype-banned'             => '이러한 종류의 파일은 금지되어 있습니다.',
'verification-error'          => '이 파일은 파일 확인 절차를 통과하지 않았습니다.',
'hookaborted'                 => '당신이 수정하려고 한 것이 확장 기능 훅에 의해 중지되었습니다.',
'illegal-filename'            => '이 파일 이름은 허용되지 않습니다.',
'overwrite'                   => '기존 파일을 덮어쓰는 것은 허용되지 않습니다.',
'unknown-error'               => '알 수 없는 오류가 발생했습니다.',
'tmp-create-error'            => '임시 파일을 생성할 수 없습니다.',
'tmp-write-error'             => '임시 파일을 작성하는 데 오류가 발생했습니다.',
'large-file'                  => '파일 크기는 $1을 넘지 않는 것을 추천합니다. 이 파일의 크기는 $2입니다.',
'largefileserver'             => '이 파일의 크기가 서버에서 허용된 설정보다 큽니다.',
'emptyfile'                   => '올리려는 파일이 빈 파일입니다.
파일 이름을 잘못 입력했을 수도 있습니다.
올리려는 파일을 다시 한 번 확인해 주시기 바랍니다.',
'fileexists'                  => "같은 이름의 파일이 이미 있습니다.
파일을 바꾸고 싶지 않다면 '''<tt>[[:$1]]</tt>''' 파일을 확인해 주세요.
[[$1|thumb]]",
'filepageexists'              => "이 파일의 설명 문서가 '''<tt>[[:$1]]</tt>'''에 존재하지만, 이 이름을 가진 파일이 존재하지 않습니다.
당신이 입력한 설명은 설명 문서에 반영되지 않을 것입니다.
당신의 설명을 반영시키려면, 직접 편집하셔야 합니다.
[[$1|thumb]]",
'fileexists-extension'        => "비슷한 이름의 파일이 존재합니다: [[$2|thumb]]
* 올리려는 파일 이름: '''<tt>[[:$1]]</tt>'''
* 존재하는 파일 이름: '''<tt>[[:$2]]</tt>'''
다른 이름으로 시도해 주세요.",
'fileexists-thumbnail-yes'    => "이 파일은 원본 그림이 아닌, 다른 그림의 크기를 줄인 섬네일 파일인 것 같습니다. [[$1|thumb]]
'''<tt>[[:$1]]</tt>''' 파일을 확인해주세요.
해당 파일이 현재 올리려는 파일과 같다면, 더 작은 크기의 그림을 올릴 필요는 없습니다.",
'file-thumbnail-no'           => "파일 이름이 '''<tt>$1</tt>'''으로 시작합니다.
이 파일은 원본 그림이 아닌, 다른 그림의 크기를 줄인 섬네일 파일인 것 같습니다.
더 해상도가 좋은 파일이 있다면 그 파일을 올려주세요. 아니면 올리려는 파일 이름을 바꾸어 주세요.",
'fileexists-forbidden'        => '같은 이름의 파일이 이미 있고, 덮어쓸 수 없습니다.
그래도 파일을 올리시려면, 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '같은 이름의 파일이 이미 위키미디어 공용에 있습니다.
파일을 업로드하길 원하신다면 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => '{{PLURAL:$1}}현재 올리고 있는 파일이 아래 파일과 중복됩니다:',
'file-deleted-duplicate'      => '이 파일과 같은 파일 ([[$1]])이 이전에 삭제된 적이 있습니다. 파일을 다시 올리기 전에 문서의 삭제 기록을 확인해 주시기 바랍니다.',
'successfulupload'            => '올리기 성공',
'uploadwarning'               => '올리기 경고',
'uploadwarning-text'          => '아래의 파일 설명을 수정하고 다시 시도해 주세요.',
'savefile'                    => '파일 저장',
'uploadedimage'               => '‘[[$1]]’ 파일을 올렸습니다.',
'overwroteimage'              => '‘[[$1]]’ 파일의 새 버전을 올렸습니다.',
'uploaddisabled'              => '올리기 비활성화됨',
'copyuploaddisabled'          => 'URL로 파일 올리기가 비활성화되어 있습니다.',
'uploadfromurl-queued'        => '당신의 올리기 명령이 기록되었습니다.',
'uploaddisabledtext'          => '파일 올리기 기능이 비활성화되어 있습니다.',
'php-uploaddisabledtext'      => 'PHP 파일 올리기가 비활성화되었습니다. 파일 올리기 설정을 확인하십시오.',
'uploadscripted'              => '이 파일에는 HTML이나 다른 스크립트 코드가 포함되어 있어, 웹 브라우저에서 오류를 일으킬 수 있습니다.',
'uploadvirus'                 => '파일이 바이러스를 포함하고 있습니다! 상세 설명: $1',
'upload-source'               => '원본 파일',
'sourcefilename'              => '원본 파일 이름:',
'sourceurl'                   => '출처 URL:',
'destfilename'                => '파일의 새 이름:',
'upload-maxfilesize'          => '파일의 최대 크기: $1',
'upload-description'          => '파일의 설명',
'upload-options'              => '올리기 설정',
'watchthisupload'             => '이 파일 주시하기',
'filewasdeleted'              => '같은 이름을 가진 파일이 올라온 적이 있었고, 그 후에 삭제되었습니다. 올리기 전에 $1을 확인해 주시기 바랍니다.',
'upload-wasdeleted'           => "'''경고: 삭제된 적이 있는 파일을 다시 올리려 하고 있습니다.'''

이 파일을 올리는 것이 적절한지 다시 한번 생각해주십시오.
편의를 위해 삭제 기록을 제공합니다.",
'filename-bad-prefix'         => '올리려고 하는 파일 이름이 \'\'\'"$1"\'\'\'(으)로 시작합니다.
"$1"은(는) 디지털 사진기가 자동으로 붙이는 의미없는 이름입니다.
파일에 대해 알기 쉬운 이름을 골라주세요.',
'filename-prefix-blacklist'   => ' #<!-- 이 줄은 그대로 두십시오. --> <pre>
# 문법은 다음과 같습니다:
#   * "#"에서 그 줄의 끝까지는 코멘트입니다.
#   * 비어 있지 않은 줄은 디지털 카메라에서 자동적으로 부여하는 파일 접두어입니다.
CIMG # 카시오
DSC_ # 니콘
DSCF # 후지
DSCN # 니콘
DUW # 일부 휴대폰
IMG # 일반
JD # 제놉틱
MGP # 펜탁스
PICT # 기타
 #</pre> <!-- 이 줄은 그대로 두십시오. -->',
'upload-successful-msg'       => '당신이 올린 파일이 여기 있습니다: $1',
'upload-failure-subj'         => '올리기 실패',
'upload-failure-msg'          => '파일을 올리는 중 문제가 발생했습니다:

$1',

'upload-proto-error'        => '잘못된 프로토콜',
'upload-proto-error-text'   => '파일을 URL로 올리려면 <code>http://</code>이나 <code>ftp://</code>로 시작해야 합니다.',
'upload-file-error'         => '내부 오류',
'upload-file-error-text'    => '서버에 임시 파일을 만드는 과정에서 내부 오류가 발생했습니다. [[Special:ListUsers/sysop|관리자]]에게 연락해주세요.',
'upload-misc-error'         => '알 수 없는 파일 올리기 오류',
'upload-misc-error-text'    => '파일을 올리는 중 알 수 없는 오류가 발생했습니다. URL이 올바르고 접근 가능한지를 확인하고 다시 시도해주세요. 문제가 계속되면 [[Special:ListUsers/sysop|관리자]]에게 연락해주세요.',
'upload-too-many-redirects' => 'URL이 너무 많은 넘겨주기에 연결되어 있습니다.',
'upload-unknown-size'       => '크기를 알 수 없음',
'upload-http-error'         => 'HTTP 오류 발생: $1',

# img_auth script messages
'img-auth-accessdenied' => '접근 거부됨',
'img-auth-nopathinfo'   => 'PATH_INFO 가 빠졌습니다.
서버에 이 정보가 설정되어 있지 않습니다.
CGI 기반이거나 img_auth 를 지원하지 않을 수 있습니다.
http://www.mediawiki.org/wiki/Manual:Image_Authorization 를 참고하세요.',
'img-auth-notindir'     => '요청한 경로가 설정한 업로드 디렉토리에 없습니다.',
'img-auth-badtitle'     => '"$1"에서 바른 제목을 만들 수 없습니다.',
'img-auth-nologinnWL'   => '당신은 로그인하지 않았으며 "$1" 파일은 화이트리스트에 존재하지 않습니다.',
'img-auth-nofile'       => '"$1" 파일이 없습니다.',
'img-auth-isdir'        => '"$1" 디렉토리에 접근을 시도했습니다.
파일에만 접근할 수 있습니다.',
'img-auth-streaming'    => '‘$1’ 파일을 전송하는 중입니다.',
'img-auth-public'       => 'img_auth.php는 개인 위키 파일을 외부 사이트로 전송하는 기능입니다. 이 기능은 기본적으로 공개적인 위키에서 사용하도록 설계되어 있으며, 보안적인 문제로 기본적으로 img_auth.php 기능은 비활성화되어 있습니다.',
'img-auth-noread'       => '‘$1’ 파일을 볼 권한이 없습니다.',

# HTTP errors
'http-invalid-url'      => '잘못된 URL: $1',
'http-invalid-scheme'   => '‘$1’로 시작하는 URL은 지원되지 않습니다.',
'http-request-error'    => '알 수 없는 오류로 HTTP 요청에 실패했습니다.',
'http-read-error'       => 'HTTP 읽기 오류.',
'http-timed-out'        => 'HTTP 요청 시간 초과.',
'http-curl-error'       => 'URL 열기 오류: $1',
'http-host-unreachable' => 'URL에 접근하지 못했습니다.',
'http-bad-status'       => 'HTTP 요청 중 오류 발생: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL 접근 불가',
'upload-curl-error6-text'  => 'URL에 접근할 수 없습니다.
URL이 맞고 해당 웹사이트가 작동하는지 확인해주세요.',
'upload-curl-error28'      => '업로드 시간 초과',
'upload-curl-error28-text' => '사이트에서 응답하는 시간이 너무 깁니다. 사이트 접속이 가능한지 확인한 다음 다시 시도해주세요. 해당 사이트에 접속이 많을 경우, 접속이 원활한 시간대에 시도해주세요.',

'license'            => '라이선스:',
'license-header'     => '라이선스',
'nolicense'          => '선택하지 않음',
'license-nopreview'  => '(미리보기 불가능)',
'upload_source_url'  => ' (유효하고, 모든 사람이 접근 가능한 URL)',
'upload_source_file' => ' (당신의 컴퓨터에 있는 파일)',

# Special:ListFiles
'listfiles-summary'     => '이 위키에 올라와 있는 모든 파일이 나열되어 있습니다.
기본적으로 가장 최근에 올라온 파일이 가장 위에 올라와 있습니다.
다른 방식으로 정렬하려면 기준 열의 머리칸을 눌러주세요.',
'listfiles_search_for'  => '다음 이름을 가진 미디어 찾기:',
'imgfile'               => '파일',
'listfiles'             => '파일 목록',
'listfiles_date'        => '날짜',
'listfiles_name'        => '이름',
'listfiles_user'        => '사용자',
'listfiles_size'        => '크기',
'listfiles_description' => '설명',
'listfiles_count'       => '버전',

# File description page
'file-anchor-link'                  => '파일',
'filehist'                          => '파일 역사',
'filehist-help'                     => '날짜/시간 링크를 클릭하면 해당 시간의 파일을 볼 수 있습니다.',
'filehist-deleteall'                => '모두 삭제',
'filehist-deleteone'                => '지우기',
'filehist-revert'                   => '되돌리기',
'filehist-current'                  => '현재',
'filehist-datetime'                 => '날짜/시간',
'filehist-thumb'                    => '섬네일',
'filehist-thumbtext'                => '$1 버전의 파일',
'filehist-nothumb'                  => '섬네일 없음',
'filehist-user'                     => '사용자',
'filehist-dimensions'               => '크기',
'filehist-filesize'                 => '파일 크기',
'filehist-comment'                  => '내용',
'filehist-missing'                  => '파일을 찾을 수 없음',
'imagelinks'                        => '파일 링크',
'linkstoimage'                      => '다음 $1개의 문서가 이 파일을 사용하고 있습니다:',
'linkstoimage-more'                 => '$1개 이상의 문서가 이 파일을 가리키고 있습니다.
다음 목록은 이 파일을 가리키는 처음 $1개 문서만 보여주고 있습니다. 
이 파일을 가리키는 모든 문서를 보려면 [[Special:WhatLinksHere/$2|여기]]를 참고해 주십시오.',
'nolinkstoimage'                    => '이 파일을 사용하는 문서가 없습니다.',
'morelinkstoimage'                  => '이 파일이 쓰이고 있는 문서 목록 [[Special:WhatLinksHere/$1|더 보기]].',
'redirectstofile'                   => '다음 파일 $1개가 이 파일로 넘겨주고 있습니다:',
'duplicatesoffile'                  => '다음 파일 $1개가 이 파일과 중복됩니다 ([[Special:FileDuplicateSearch/$2|자세한 정보]]):',
'sharedupload'                      => '이 파일은 $1으로부터 왔고, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.',
'sharedupload-desc-there'           => '이 파일은 $1(으)로부터 왔고, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다. [$2 해당 파일 문서]에서 자세한 정보를 확인해주세요.',
'sharedupload-desc-here'            => '이 파일은 $1(으)로부터 왔고, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.
[$2 해당 파일]에 대한 설명이 아래에 나와 있습니다.',
'filepage-nofile'                   => '해당 이름으로 된 파일이 없습니다.',
'filepage-nofile-link'              => '해당 이름으로 된 파일이 없습니다. [$1 파일을 올릴 수] 있습니다.',
'uploadnewversion-linktext'         => '이 파일의 새로운 버전을 올리기',
'shared-repo-from'                  => '($1)',
'shared-repo'                       => '공용 저장소',
'shared-repo-name-wikimediacommons' => '위키미디어 공용',

# File reversion
'filerevert'                => '$1 되돌리기',
'filerevert-legend'         => '파일 되돌리기',
'filerevert-intro'          => "'''[[Media:$1|$1]]''' 파일을 [$4 $2 $3 버전]으로 되돌립니다.",
'filerevert-comment'        => '이유:',
'filerevert-defaultcomment' => '$1 $2 버전으로 되돌림',
'filerevert-submit'         => '되돌리기',
'filerevert-success'        => "'''[[Media:$1|$1]]''' 파일을 [$4 $2 $3 버전]으로 되돌렸습니다.",
'filerevert-badversion'     => '주어진 타임스탬프를 가진 파일의 로컬 버전이 없습니다.',

# File deletion
'filedelete'                  => '$1 삭제하기',
'filedelete-legend'           => '파일 삭제하기',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' 파일과 모든 역사를 삭제합니다.",
'filedelete-intro-old'        => "'''[[Media:$1|$1]]''' 파일의 [$4 $2 $3] 버전을 삭제합니다.",
'filedelete-comment'          => '이유:',
'filedelete-submit'           => '삭제',
'filedelete-success'          => "'''$1''' 파일을 삭제했습니다.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' 파일의 $2 $3 버전을 삭제했습니다.",
'filedelete-nofile'           => "'''$1''' 파일이 존재하지 않습니다.",
'filedelete-nofile-old'       => "해당 조건에 맞는 과거 '''$1''' 파일이 존재하지 않습니다.",
'filedelete-otherreason'      => '다른 이유/추가적인 이유:',
'filedelete-reason-otherlist' => '다른 이유',
'filedelete-reason-dropdown'  => '*일반적인 삭제 이유
** 저작권 침해
** 중복된 파일',
'filedelete-edit-reasonlist'  => '삭제 이유 편집',
'filedelete-maintenance'      => '점검 중에는 임시적으로 삭제와 복구를 할 수 없습니다.',

# MIME search
'mimesearch'         => 'MIME 검색',
'mimesearch-summary' => 'MIME 타입에 해당하는 파일을 검색합니다. MIME 값을 <tt>image/jpeg</tt> 형태로 입력해주세요.',
'mimetype'           => 'MIME 종류:',
'download'           => '다운로드',

# Unwatched pages
'unwatchedpages' => '주시되지 않는 문서 목록',

# List redirects
'listredirects' => '넘겨주기 문서 목록',

# Unused templates
'unusedtemplates'     => '사용하지 않는 틀 목록',
'unusedtemplatestext' => '다른 문서에서 사용하지 않는 {{ns:template}} 이름공간 문서의 목록입니다.

삭제하기 전에 사용 여부를 다시 확인해 주세요.',
'unusedtemplateswlh'  => '다른 링크',

# Random page
'randompage'         => '임의 문서로',
'randompage-nopages' => '{{PLURAL:$2|다음}} 이름공간에는 문서가 없습니다: $1',

# Random redirect
'randomredirect'         => '임의 넘겨주기 문서로',
'randomredirect-nopages' => '"$1" 이름공간에서 해당하는 넘겨주기 문서가 없습니다.',

# Statistics
'statistics'                   => '통계',
'statistics-header-pages'      => '문서 통계',
'statistics-header-edits'      => '편집 통계',
'statistics-header-views'      => '방문 통계',
'statistics-header-users'      => '사용자 통계',
'statistics-header-hooks'      => '기타 통계',
'statistics-articles'          => '일반 문서',
'statistics-pages'             => '전체 문서',
'statistics-pages-desc'        => '토론 문서, 넘겨주기 문서 등을 포함.',
'statistics-files'             => '올려져 있는 파일',
'statistics-edits'             => '{{SITENAME}} 설치 후 문서의 전체 편집 횟수',
'statistics-edits-average'     => '문서당 평균 편집 횟수',
'statistics-views-total'       => '총 방문 수',
'statistics-views-peredit'     => '편집당 방문 횟수',
'statistics-users'             => '등록된 [[Special:ListUsers|사용자]]',
'statistics-users-active'      => '활동 중인 사용자',
'statistics-users-active-desc' => '최근 $1일 동안 활동한 사용자',
'statistics-mostpopular'       => '가장 많이 읽힌 문서',

'disambiguations'      => '동음이의 문서 목록',
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "다음의 문서들은 '''동음이의 문서'''를 가리키고 있습니다.
그 링크를 다른 적절한 문서로 연결해 주어야 합니다.<br />
[[MediaWiki:Disambiguationspage]]에서 링크된 틀을 사용하는 문서를 동음이의 문서로 간주합니다.",

'doubleredirects'            => '이중 넘겨주기 목록',
'doubleredirectstext'        => '이 문서는 다른 넘겨주기 문서로 넘겨주고 있는 문서의 목록입니다.
매 줄에는 첫 번째 문서와 두 번째 문서의 링크가 있습니다. 그리고 보통 첫 번째 문서가 넘겨주어야 할 "실제" 문서인 두 번째 넘겨주기의 대상이 있습니다.
<s>취소선이 그인</s> 부분은 이미 해결되었습니다.',
'double-redirect-fixed-move' => '[[$1]] 문서를 옮겼습니다. 이 문서는 이제 [[$2]] 문서로 넘겨줍니다.',
'double-redirect-fixer'      => '넘겨주기 수리꾼',

'brokenredirects'        => '끊긴 넘겨주기 목록',
'brokenredirectstext'    => '존재하지 않는 문서로 넘겨주기가 되어 있는 문서의 목록입니다:',
'brokenredirects-edit'   => '편집',
'brokenredirects-delete' => '삭제',

'withoutinterwiki'         => '언어 인터위키 링크가 없는 문서 목록',
'withoutinterwiki-summary' => '다른 언어로의 연결이 없는 문서의 목록입니다:',
'withoutinterwiki-legend'  => '접두어',
'withoutinterwiki-submit'  => '보기',

'fewestrevisions' => '편집 역사가 짧은 문서 목록',

# Miscellaneous special pages
'nbytes'                  => '$1 바이트',
'ncategories'             => '분류 $1개',
'nlinks'                  => '링크 $1개',
'nmembers'                => '문서 $1개',
'nrevisions'              => '편집 $1개',
'nviews'                  => '$1 번 읽음',
'specialpage-empty'       => '명령에 대한 결과가 없습니다.',
'lonelypages'             => '외톨이 문서 목록',
'lonelypagestext'         => '{{SITENAME}}에서 다른 모든 문서에서 링크되거나 틀로 포함되지 않은 문서의 목록입니다.',
'uncategorizedpages'      => '분류되지 않은 문서 목록',
'uncategorizedcategories' => '분류되지 않은 분류 목록',
'uncategorizedimages'     => '분류되지 않은 파일 목록',
'uncategorizedtemplates'  => '분류되지 않은 틀 목록',
'unusedcategories'        => '사용하지 않는 분류 목록',
'unusedimages'            => '사용하지 않는 파일 목록',
'popularpages'            => '인기있는 문서 목록',
'wantedcategories'        => '필요한 분류 목록',
'wantedpages'             => '필요한 문서 목록',
'wantedpages-badtitle'    => '문서 제목이 잘못되었습니다: $1',
'wantedfiles'             => '필요한 파일 목록',
'wantedtemplates'         => '필요한 틀 목록',
'mostlinked'              => '가장 많이 연결된 문서 목록',
'mostlinkedcategories'    => '가장 많이 연결된 분류 목록',
'mostlinkedtemplates'     => '가장 많이 사용된 틀 목록',
'mostcategories'          => '가장 많이 분류된 문서 목록',
'mostimages'              => '가장 많이 사용된 파일 목록',
'mostrevisions'           => '가장 많이 편집된 문서 목록',
'prefixindex'             => '접두어에 따른 문서 목록',
'shortpages'              => '짧은 문서 목록',
'longpages'               => '긴 문서 목록',
'deadendpages'            => '막다른 문서 목록',
'deadendpagestext'        => '{{SITENAME}} 내의 다른 문서로 나가는 링크가 없는 문서의 목록입니다.',
'protectedpages'          => '보호된 문서 목록',
'protectedpages-indef'    => '오른쪽 조건에 맞는 보호만 보기',
'protectedpages-cascade'  => '연쇄적 보호만 보기',
'protectedpagestext'      => '다음의 문서는 이동/편집이 불가능하도록 보호되어 있습니다.',
'protectedpagesempty'     => '보호되어 있는 문서가 없습니다.',
'protectedtitles'         => '생성 보호된 표제어 목록',
'protectedtitlestext'     => '다음 표제어들은 생성이 금지되어 있습니다.',
'protectedtitlesempty'    => '해당 조건에 맞는 생성 금지 표제어가 없습니다.',
'listusers'               => '사용자 목록',
'listusers-editsonly'     => '기여가 있는 사용자만 보기',
'listusers-creationsort'  => '계정 등록일 순으로 정렬',
'usereditcount'           => '편집 $1개',
'usercreated'             => '$1 $2에 계정 생성',
'newpages'                => '새 문서 목록',
'newpages-username'       => '이름:',
'ancientpages'            => '오래된 문서 목록',
'move'                    => '이동',
'movethispage'            => '문서 이동하기',
'unusedimagestext'        => '다음은 어떤 문서도 사용하지 않는 파일의 목록입니다.
다른 사이트에서 URL 접근을 통해 파일을 사용할 수 있기 때문에, 아래 목록에 있는 파일도 실제로 사용 중일 가능성이 있다는 점을 주의해주세요.',
'unusedcategoriestext'    => '사용하지 않는 분류 문서들의 목록입니다.',
'notargettitle'           => '해당하는 문서 없음',
'notargettext'            => '기능을 수행할 대상 문서나 사용자를 지정하지 않았습니다.',
'nopagetitle'             => '해당 문서 없음',
'nopagetext'              => '당신이 찾는 문서는 존재하지 않습니다.',
'pager-newer-n'           => '이전 $1개',
'pager-older-n'           => '다음 $1개',
'suppress'                => '오버사이트',

# Book sources
'booksources'               => '책 찾기',
'booksources-search-legend' => '책 찾기',
'booksources-go'            => '찾기',
'booksources-text'          => '아래의 목록은 새 책이나 중고 책을 판매하는 외부 사이트로, 원하는 책의 정보를 얻을 수 있습니다:',
'booksources-invalid-isbn'  => '입력한 ISBN이 잘못된 것으로 보입니다; 원본과 대조해 보십시오.',

# Special:Log
'specialloguserlabel'  => '이름:',
'speciallogtitlelabel' => '제목:',
'log'                  => '로그 목록',
'all-logs-page'        => '모든 공개 기록',
'alllogstext'          => '{{SITENAME}}에서의 기록이 모두 나와 있습니다.
로그 종류, 계정 이름, 문서 이름을 선택해서 볼 수 있습니다. 검색시에는 대소문자를 구별합니다.',
'logempty'             => '일치하는 항목이 없습니다.',
'log-title-wildcard'   => '다음 글로 시작하는 제목 검색',

# Special:AllPages
'allpages'          => '모든 문서 목록',
'alphaindexline'    => '$1에서 $2까지',
'nextpage'          => '다음 문서 ($1)',
'prevpage'          => '이전 문서 ($1)',
'allpagesfrom'      => '다음으로 시작하는 문서 보기:',
'allpagesto'        => '다음으로 끝나는 문서 보기:',
'allarticles'       => '모든 문서',
'allinnamespace'    => '$1 이름공간의 모든 문서',
'allnotinnamespace' => '$1 이름공간을 제외한 모든 문서',
'allpagesprev'      => '이전',
'allpagesnext'      => '다음',
'allpagessubmit'    => '표시',
'allpagesprefix'    => '다음으로 시작하는 문서 보기:',
'allpagesbadtitle'  => '문서 제목이 잘못되었거나 다른 사이트로 연결되는 인터위키를 가지고 있습니다. 문서 제목에 사용할 수 없는 문자를 사용했을 수 있습니다.',
'allpages-bad-ns'   => '{{SITENAME}}에서는 ‘$1’ 이름공간을 사용하지 않습니다.',

# Special:Categories
'categories'                    => '분류',
'categoriespagetext'            => '{{PLURAL:$1}}문서나 자료를 담고 있는 분류 목록입니다.
[[Special:UnusedCategories|사용되지 않는 분류]]는 여기에 보이지 않습니다.
[[Special:WantedCategories|필요한 분류]]도 참고하세요.',
'categoriesfrom'                => '다음으로 시작하는 분류들을 보여주기:',
'special-categories-sort-count' => '항목 갯수 순으로 정렬',
'special-categories-sort-abc'   => '알파벳순으로 정렬',

# Special:DeletedContributions
'deletedcontributions'             => '삭제된 기여 목록',
'deletedcontributions-title'       => '삭제된 기여 목록',
'sp-deletedcontributions-contribs' => '기여',

# Special:LinkSearch
'linksearch'       => '웹 링크 검색',
'linksearch-pat'   => '검색 패턴:',
'linksearch-ns'    => '이름공간:',
'linksearch-ok'    => '검색',
'linksearch-text'  => '"*.wikipedia.org"와 같이 와일드카드를 사용할 수 있습니다.<br />
지원하는 프로토콜 목록: <tt>$1</tt>',
'linksearch-line'  => '$2에서 $1 을 링크하고 있습니다.',
'linksearch-error' => '와일드카드는 주소의 처음 부분에만 사용될 수 있습니다.',

# Special:ListUsers
'listusersfrom'      => '다음으로 시작하는 사용자 보기:',
'listusers-submit'   => '보기',
'listusers-noresult' => '해당 사용자가 없습니다.',
'listusers-blocked'  => '(차단됨)',

# Special:ActiveUsers
'activeusers'            => '활동 중인 사용자 목록',
'activeusers-intro'      => '다음은 최근 $1일 동안 활동한 사용자의 목록입니다.',
'activeusers-count'      => '최근 $3일 사이의 편집 $1개',
'activeusers-from'       => '다음으로 시작하는 사용자를 보기:',
'activeusers-hidebots'   => '봇을 숨기기',
'activeusers-hidesysops' => '관리자를 숨기기',
'activeusers-noresult'   => '사용자가 없습니다.',

# Special:Log/newusers
'newuserlogpage'              => '사용자 등록 기록',
'newuserlogpagetext'          => '사용자 등록 기록입니다.',
'newuserlog-byemail'          => '이메일로 보낸 암호',
'newuserlog-create-entry'     => '새 사용자',
'newuserlog-create2-entry'    => '$1 계정을 새로 만듦',
'newuserlog-autocreate-entry' => '계정이 자동으로 만들어졌습니다.',

# Special:ListGroupRights
'listgrouprights'                      => '사용자 권한 목록',
'listgrouprights-summary'              => '다음은 이 위키에서 설정된 사용자 권한 그룹의 목록입니다.
각각의 권한에 대해서는 [[{{MediaWiki:Listgrouprights-helppage}}|이곳]]을 참조하십시오.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">부여된 권한</span>
* <span class="listgrouprights-revoked">해제된 권한</span>',
'listgrouprights-group'                => '그룹',
'listgrouprights-rights'               => '권한',
'listgrouprights-helppage'             => 'Help:사용자 권한 그룹',
'listgrouprights-members'              => '(사용자 목록)',
'listgrouprights-addgroup'             => '{{PLURAL:$2}}권한 부여: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2}}권한 회수: $1',
'listgrouprights-addgroup-all'         => '모든 권한을 부여',
'listgrouprights-removegroup-all'      => '모든 권한을 회수',
'listgrouprights-addgroup-self'        => '자신에게 다음 {{PLURAL:$2|권한|권한}}을 부여: $1',
'listgrouprights-removegroup-self'     => '자신에게서 다음 {{PLURAL:$2|권한|권한}}을 해제: $1',
'listgrouprights-addgroup-self-all'    => '자신에게 모든 권한을 부여',
'listgrouprights-removegroup-self-all' => '자신의 계정에서 모든 권한을 해제',

# E-mail user
'mailnologin'          => '보낼 이메일 주소가 없음',
'mailnologintext'      => '다른 사용자에게 이메일을 보내려면, [[Special:UserLogin|로그인]]한 다음 [[Special:Preferences|사용자 환경 설정]]에서 자신의 이메일 주소를 저장해야 합니다.',
'emailuser'            => '이메일 보내기',
'emailpage'            => '사용자에게 이메일 보내기',
'emailpagetext'        => '이 사용자가 환경 설정에 올바른 이메일 주소를 적었다면, 아래 양식을 통해 이메일을 보낼 수 있습니다.
이메일을 받은 사용자가 바로 답장할 수 있도록 하기 위해 당신이 [[Special:Preferences|사용자 환경 설정]]에 적은 이메일 주소가 "발신자" 정보에 들어갑니다. 따라서 수신자가 당신에게 직접 답장을 보낼 수 있습니다.',
'usermailererror'      => '메일 객체에서 오류 발생:',
'defemailsubject'      => '{{SITENAME}} 이메일',
'usermaildisabled'     => '사용자 이메일 비활성화됨',
'usermaildisabledtext' => '당신은 이 위키에서 다른 사용자에게 메일을 보낼 수 없습니다',
'noemailtitle'         => '이메일 주소 없음',
'noemailtext'          => '이 사용자는 올바른 이메일 주소를 입력하지 않았습니다.',
'nowikiemailtitle'     => '이메일이 허용되지 않음',
'nowikiemailtext'      => '이 사용자는 다른 사용자로부터의 이메일을 받지 않도록 설정하였습니다.',
'email-legend'         => '{{SITENAME}}의 다른 사용자에게 이메일을 보내기',
'emailfrom'            => '이메일 발신자:',
'emailto'              => '수신자:',
'emailsubject'         => '제목:',
'emailmessage'         => '내용:',
'emailsend'            => '보내기',
'emailccme'            => '사본을 내 이메일로도 보내기',
'emailccsubject'       => '$1에게 보낸 메일 사본: $2',
'emailsent'            => '이메일 보냄',
'emailsenttext'        => '이메일을 보냈습니다.',
'emailuserfooter'      => '이 이메일은 {{SITENAME}}의 $1 사용자가 $2 사용자에게 "이메일 보내기" 기능을 통해 전송되었습니다.',

# User Messenger
'usermessage-summary' => '시스템 메시지 남기기',
'usermessage-editor'  => '시스템 메신저',

# Watchlist
'watchlist'            => '주시문서 목록',
'mywatchlist'          => '내 주시문서 목록',
'watchlistfor'         => "('''$1'''의 목록)",
'nowatchlist'          => '주시하는 문서가 아직 없습니다.',
'watchlistanontext'    => '주시문서 목록을 보거나 고치려면 $1 하세요.',
'watchnologin'         => '로그인하지 않음',
'watchnologintext'     => '주시문서 목록을 고치려면 [[Special:UserLogin|로그인]]해야 합니다.',
'addedwatch'           => '주시문서 목록에 추가',
'addedwatchtext'       => "\"[[:\$1]]\" 문서를 [[Special:Watchlist|주시문서 목록]]에 추가했습니다.
앞으로 이 문서나 토론 문서가 바뀌면 [[Special:RecentChanges|최근 바뀜]]에서 알아보기 쉽게 '''굵은 글씨'''로 보일 것입니다.",
'removedwatch'         => '주시문서 목록에서 제거',
'removedwatchtext'     => '"[[:$1]]" 문서를 [[Special:Watchlist|주시문서 목록]]에서 뺐습니다.',
'watch'                => '주시',
'watchthispage'        => '주시하기',
'unwatch'              => '주시 해제',
'unwatchthispage'      => '주시 해제하기',
'notanarticle'         => '문서가 아님',
'notvisiblerev'        => '이 판은 삭제되었습니다.',
'watchnochange'        => '주어진 기간 중에 바뀐 주시문서가 없습니다.',
'watchlist-details'    => '토론을 제외하고 문서 $1개를 주시하고 있습니다.',
'wlheader-enotif'      => '* 이메일 알림 기능이 활성화되었습니다.',
'wlheader-showupdated' => "* 마지막으로 방문한 이후에 바뀐 문서들은 '''굵은 글씨'''로 표시됩니다.",
'watchmethod-recent'   => '주시된 문서를 확인하고자 최근 편집을 확인',
'watchmethod-list'     => '최근 편집을 확인하고자 주시된 문서 확인',
'watchlistcontains'    => '문서 $1개를 주시하고 있습니다.',
'iteminvalidname'      => '"$1" 항목에 문제가 발생했습니다. 이름이 잘못되었습니다...',
'wlnote'               => "다음은 최근 '''$2'''시간 동안 바뀐 문서 '''$1'''개 입니다.",
'wlshowlast'           => '최근 $1 시간 $2 일 또는 $3 동안에 바뀐 문서',
'watchlist-options'    => '주시문서 목록 설정',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '주시목록에 추가 중...',
'unwatching' => '주시 해제하는 중...',

'enotif_mailer'                => '{{SITENAME}} 자동 알림 메일',
'enotif_reset'                 => '모든 문서를 방문한 것으로 표시하기',
'enotif_newpagetext'           => '이것은 새 문서입니다.',
'enotif_impersonal_salutation' => '{{SITENAME}} 사용자',
'changed'                      => '바꾸',
'created'                      => '만들',
'enotif_subject'               => '{{SITENAME}}에서 $PAGEEDITOR 사용자가 $PAGETITLE 문서를 $CHANGEDORCREATED었습니다.',
'enotif_lastvisited'           => '마지막으로 방문한 뒤 생긴 모든 변경 사항을 보려면 $1을(를) 보십시오.',
'enotif_lastdiff'              => '이 바뀐 내용을 보려면 $1을 보십시오.',
'enotif_anon_editor'           => '익명 사용자 $1',
'enotif_body'                  => '$WATCHINGUSERNAME님,

{{SITENAME}}의 $PAGETITLE 문서를 $PAGEEDITDATE에 $PAGEEDITOR님이 $CHANGEDORCREATED었습니다. 현재의 문서는 $PAGETITLE_URL 에서 볼 수 있습니다.

$NEWPAGE

편집 요약: $PAGESUMMARY $PAGEMINOREDIT

다음을 통해 편집자와 대화를 할 수 있습니다:
이메일: $PAGEEDITOR_EMAIL
위키: $PAGEEDITOR_WIKI

이 문서를 열기 전에는 다른 알림 이메일을 더 이상 보내지 않습니다.
모든 주시 문서의 알림 딱지를 초기화할 수도 있습니다.

             {{SITENAME}} 알림 시스템

--
주시문서 설정을 바꾸려면 다음을 사용하세요:
{{fullurl:{{#special:Watchlist}}/edit}}

주시문서에서 이 문서를 지우려면 이곳을 방문해주세요:
$UNWATCHURL

도움을 얻거나 피드백 하기:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => '문서 삭제하기',
'confirm'                => '확인',
'excontent'              => '내용: ‘$1’',
'excontentauthor'        => "내용: ‘$1’ (유일한 편집자는 '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => '비우기 전의 내용: ‘$1’',
'exblank'                => '빈 문서',
'delete-confirm'         => '‘$1’ 삭제',
'delete-legend'          => '삭제',
'historywarning'         => "'''주의''': 삭제하려는 문서에 약 $1개의 과거 편집 내역이 있습니다:",
'confirmdeletetext'      => '문서와 문서 역사를 삭제하려고 합니다. 삭제하려는 문서가 맞는지, 이 문서를 삭제하는 것이 [[{{MediaWiki:Policy-url}}|정책]]에 맞는 행동인지를 확인해 주세요.',
'actioncomplete'         => '명령 완료',
'actionfailed'           => '명령 실패',
'deletedtext'            => '‘<nowiki>$1</nowiki>’ 문서를 삭제했습니다. 최근 삭제 기록은 $2에 있습니다.',
'deletedarticle'         => '사용자가 [[$1]] 문서를 삭제하였습니다.',
'suppressedarticle'      => '"[[$1]]"를 숨김',
'dellogpage'             => '삭제 기록',
'dellogpagetext'         => '아래의 목록은 최근에 삭제된 문서들입니다.',
'deletionlog'            => '삭제 기록',
'reverted'               => '이전 버전으로 되돌렸습니다.',
'deletecomment'          => '이유:',
'deleteotherreason'      => '다른 이유/추가적인 이유:',
'deletereasonotherlist'  => '다른 이유',
'deletereason-dropdown'  => '*일반적인 삭제 이유
** 작성자의 요청
** 저작권 침해
** 잘못된 문서',
'delete-edit-reasonlist' => '삭제 이유 편집',
'delete-toobig'          => '이 문서에는 편집 역사가 $1개 있습니다. 편집 역사가 긴 문서를 삭제하면 {{SITENAME}}에 큰 혼란을 줄 수 있기 때문에 삭제할 수 없습니다.',
'delete-warning-toobig'  => '이 문서에는 편집 역사가 $1개 있습니다.
편집 역사가 긴 문서를 삭제하면 {{SITENAME}} 데이터베이스 동작에 큰 영향을 줄 수 있습니다.
주의해 주세요.',

# Rollback
'rollback'          => '편집 되돌리기',
'rollback_short'    => '되돌리기',
'rollbacklink'      => '되돌리기',
'rollbackfailed'    => '되돌리기 실패',
'cantrollback'      => '편집을 되돌릴 수 없습니다. 문서를 편집한 사용자가 한명뿐입니다.',
'alreadyrolled'     => '[[:$1]]에서 [[User:$2|$2]] ([[User talk:$2|토론]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) 의 편집을 되돌릴 수 없습니다; 누군가가 문서를 고치거나 되돌렸습니다.

마지막으로 문서를 편집한 사람은 [[User:$3|$3]] ([[User talk:$3|토론]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) 입니다.',
'editcomment'       => "편집 요약: ''$1''",
'revertpage'        => '[[Special:Contributions/$2|$2]]([[User talk:$2|토론]])의 편집을 [[User:$1|$1]]의 마지막 버전으로 되돌림',
'revertpage-nouser' => '(계정 이름 삭제됨)의 편집을 [[User:$1|$1]]의 마지막 편집으로 되돌림',
'rollback-success'  => '$1의 편집을 $2의 마지막 버전으로 되돌렸습니다.',

# Edit tokens
'sessionfailure-title' => '세션 손실',
'sessionfailure'       => '로그인 세션에 문제가 발생한 것 같습니다. 세션 하이재킹을 막기 위해 동작이 취소되었습니다. 브라우저의 "뒤로" 버튼을 누르고 문서를 새로고침한 후에 다시 시도해 주세요.',

# Protect
'protectlogpage'              => '문서 보호 기록',
'protectlogtext'              => '아래의 목록은 문서 보호와 보호 해제 기록입니다.',
'protectedarticle'            => '‘[[$1]]’ 문서를 보호함',
'modifiedarticleprotection'   => '‘[[$1]]’ 문서의 보호 설정을 변경함',
'unprotectedarticle'          => '‘[[$1]]’ 문서를 보호 해제함',
'movedarticleprotection'      => '문서의 보호 설정을 "[[$2]]"에서 "[[$1]]"으로 옮김',
'protect-title'               => '"$1" 보호하기',
'prot_1movedto2'              => '[[$1]]을(를) [[$2]](으)로 옮김',
'protect-legend'              => '보호 확인',
'protectcomment'              => '이유:',
'protectexpiry'               => '보호 기간:',
'protect_expiry_invalid'      => '보호 기간이 잘못되었습니다.',
'protect_expiry_old'          => '기한을 과거로 입력했습니다.',
'protect-unchain-permissions' => '다른 보호 설정을 수동으로 설정하기',
'protect-text'                => "'''<nowiki>$1</nowiki>''' 문서의 보호 수준을 보거나 변경할 수 있습니다.",
'protect-locked-blocked'      => "차단된 동안에는 보호 설정을 바꿀 수 없습니다. '''$1''' 문서의 보호 설정은 다음과 같습니다:",
'protect-locked-dblock'       => "데이터베이스가 잠겨 문서 보호 설정을 바꿀 수 없습니다. '''$1''' 문서의 현재 설정은 다음과 같습니다:",
'protect-locked-access'       => "문서 보호 수준을 변경할 권한이 없습니다. '''$1''' 문서의 권한은 다음과 같습니다.",
'protect-cascadeon'           => '다음 {{PLURAL:$1|문서}}에 연쇄적 보호가 작동하고 있어 그 문서에 속한 이 문서도 현재 보호됩니다.
사용자께서는 이 문서의 보호 설정을 바꾸실 수 있지만, 연쇄적 보호에는 영향을 주지 않습니다.',
'protect-default'             => '모든 사용자에게 허용',
'protect-fallback'            => '‘$1’ 권한 필요',
'protect-level-autoconfirmed' => '등록된 사용자만 가능',
'protect-level-sysop'         => '관리자만 가능',
'protect-summary-cascade'     => '연쇄적',
'protect-expiring'            => '$1 (UTC)에 만료',
'protect-expiry-indefinite'   => '무기한',
'protect-cascade'             => '연쇄적 보호 - 이 문서에서 사용되는 다른 문서를 함께 보호합니다.',
'protect-cantedit'            => '이 문서의 보호 설정을 바꿀 권한이 없습니다.',
'protect-othertime'           => '다른 기간:',
'protect-othertime-op'        => '다른 기간',
'protect-existing-expiry'     => '현재 만료 기간: $2 $3',
'protect-otherreason'         => '다른 이유/추가적인 이유:',
'protect-otherreason-op'      => '다른 이유',
'protect-dropdown'            => '*일반적인 보호 이유
** 빈번한 훼손 행위
** 빈번한 광고 행위
** 비생산적인 편집 분쟁
** 방문이 많은 문서',
'protect-edit-reasonlist'     => '보호 이유 편집하기',
'protect-expiry-options'      => '1시간:1 hour,1일:1 day,1주일:1 week,2주일:2 weeks,1개월:1 month,3개월:3 months,6개월:6 months,1년:1 year,무기한:infinite',
'restriction-type'            => '권한:',
'restriction-level'           => '보호 수준:',
'minimum-size'                => '최소 크기',
'maximum-size'                => '최대 크기:',
'pagesize'                    => '(바이트)',

# Restrictions (nouns)
'restriction-edit'   => '편집',
'restriction-move'   => '이동',
'restriction-create' => '만들기',
'restriction-upload' => '올리기',

# Restriction levels
'restriction-level-sysop'         => '보호됨',
'restriction-level-autoconfirmed' => '준보호됨',
'restriction-level-all'           => '모두',

# Undelete
'undelete'                     => '삭제된 문서 보기',
'undeletepage'                 => '삭제된 문서를 보거나 되살리기',
'undeletepagetitle'            => "'''아래는 [[:$1|$1]]의 삭제된 판입니다.'''.",
'viewdeletedpage'              => '삭제된 문서 보기',
'undeletepagetext'             => '다음 {{PLURAL:$1|문서는|문서 $1개는}} 삭제되었지만 아직 보관되어 있고 되살릴 수 있습니다.
보관된 문서는 주기적으로 삭제될 것입니다.',
'undelete-fieldset-title'      => '문서 복구',
'undeleteextrahelp'            => "문서 역사 전체를 복구하려면 모든 체크박스의 선택을 풀고 '''복구'''를 누르세요.
특정한 버전만 복구하려면 복구하려는 버전을 선택하고 '''복구'''를 누르세요.
'''초기화'''를 누르면 모든 선택이 취소됩니다.",
'undeleterevisions'            => '판 $1개 보관중',
'undeletehistory'              => '문서를 되살리면 모든 역사가 같이 복구됩니다.
문서가 삭제된 뒤 같은 이름의 문서가 만들어졌다면, 복구되는 역사는 지금 역사의 과거 부분에 나타날 것입니다.',
'undeleterevdel'               => '복구하려는 문서의 최신판이 삭제되어 있는 경우 문서를 복구시킬 수 없습니다. 이러한 경우, 삭제된 최신판 문서의 체크박스를 선택 해제하거나 숨김을 해제해야 합니다.',
'undeletehistorynoadmin'       => '이 문서는 삭제되었습니다.
삭제된 이유와 삭제되기 전에 이 문서를 편집한 사용자들이 아래에 나와 있습니다.
삭제된 문서의 내용을 보려면 관리자 권한이 필요합니다.',
'undelete-revision'            => '삭제된 $1 문서의 $4 $5 버전 (기여자 $3):',
'undeleterevision-missing'     => '해당 판이 잘못되었거나 존재하지 않습니다.
잘못된 링크를 따라왔거나, 특정 판이 이미 복구되거나 데이터베이스에서 제거되었을 수도 있습니다.',
'undelete-nodiff'              => '이전의 판이 없습니다.',
'undeletebtn'                  => '복구',
'undeletelink'                 => '보기/되살리기',
'undeleteviewlink'             => '보기',
'undeletereset'                => '초기화',
'undeleteinvert'               => '선택 반전',
'undeletecomment'              => '이유:',
'undeletedarticle'             => '"[[$1]]" 문서를 복구하였습니다.',
'undeletedrevisions'           => '판 $1개를 복구했습니다',
'undeletedrevisions-files'     => '판 $1개와 파일 $2개를 복구했습니다.',
'undeletedfiles'               => '파일 $1개를 복구했습니다',
'cannotundelete'               => '복구에 실패했습니다.
다른 사용자가 이미 복구했을 수도 있습니다.',
'undeletedpage'                => "'''$1 문서가 복구되었습니다.'''

[[Special:Log/delete|삭제 기록]]에서 최근의 삭제/복구 기록을 볼 수 있습니다.",
'undelete-header'              => '최근에 삭제한 문서에 대한 기록은 [[Special:Log/delete|여기]]에서 볼 수 있습니다.',
'undelete-search-box'          => '삭제된 문서 찾기',
'undelete-search-prefix'       => '다음으로 시작하는 문서 보기:',
'undelete-search-submit'       => '찾기',
'undelete-no-results'          => '삭제된 문서 보존 자료에서 입력한 값에 맞는 문서가 없습니다.',
'undelete-filename-mismatch'   => '타임스탬프가 $1인 파일의 버전을 복구할 수 없습니다: 파일 이름이 일치하지 않습니다.',
'undelete-bad-store-key'       => '타임스탬프가 $1인 파일의 버전을 복구할 수 없습니다: 파일이 삭제되기 전에 사라졌습니다.',
'undelete-cleanup-error'       => '사용되지 않는 보존된 파일 "$1"을 삭제하는 데 오류가 발생했습니다.',
'undelete-missing-filearchive' => '데이터베이스에 존재하지 않기 때문에 파일 보존 ID가 $1인 파일을 복구할 수 없습니다.
이미 복구되었을 수 있습니다.',
'undelete-error-short'         => '파일 복구 오류: $1',
'undelete-error-long'          => '파일을 복구하는 중 오류 발생:

$1',
'undelete-show-file-confirm'   => '정말 "<nowiki>$1</nowiki>" 파일의 삭제된 $2 $3 버전을 보시겠습니까?',
'undelete-show-file-submit'    => '예',

# Namespace form on various pages
'namespace'      => '이름공간:',
'invert'         => '선택 반전',
'blanknamespace' => '(일반)',

# Contributions
'contributions'       => '사용자 기여',
'contributions-title' => '$1 사용자의 기여 목록',
'mycontris'           => '내 기여 목록',
'contribsub2'         => '$1($2)의 기여',
'nocontribs'          => '이 사용자는 아무 것도 기여하지 않았습니다.',
'uctop'               => '(최신)',
'month'               => '월:',
'year'                => '연도:',

'sp-contributions-newbies'             => '새 사용자의 기여만 보기',
'sp-contributions-newbies-sub'         => '새 사용자들의 기여',
'sp-contributions-newbies-title'       => '새 사용자의 기여',
'sp-contributions-blocklog'            => '차단 기록',
'sp-contributions-deleted'             => '삭제된 기여 목록',
'sp-contributions-logs'                => '기록',
'sp-contributions-talk'                => '토론',
'sp-contributions-userrights'          => '사용자 권한 관리',
'sp-contributions-blocked-notice'      => '이 사용자는 현재 차단되어 있습니다. 해당 사용자의 차단 기록은 다음과 같습니다.',
'sp-contributions-blocked-notice-anon' => '이 IP 주소는 현재 차단되어 있습니다.
아래의 최근 차단 기록을 참고하십시오.',
'sp-contributions-search'              => '기여 검색',
'sp-contributions-username'            => 'IP 주소 혹은 계정 이름:',
'sp-contributions-toponly'             => '최신판만 보기',
'sp-contributions-submit'              => '찾기',

# What links here
'whatlinkshere'            => '여기를 가리키는 문서',
'whatlinkshere-title'      => '"$1" 문서를 가리키는 문서 목록',
'whatlinkshere-page'       => '문서:',
'linkshere'                => "다음의 문서가 '''[[:$1]]''' 문서를 가리키고 있습니다:",
'nolinkshere'              => "'''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'nolinkshere-ns'           => "선택한 이름공간에는 '''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'isredirect'               => '넘겨주기 문서',
'istemplate'               => '포함',
'isimage'                  => '그림 포함',
'whatlinkshere-prev'       => '{{PLURAL:$1|이전|이전 $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|다음|다음 $1}}',
'whatlinkshere-links'      => '← 가리키는 문서 목록',
'whatlinkshere-hideredirs' => '넘겨주기를 $1',
'whatlinkshere-hidetrans'  => '틀을 $1',
'whatlinkshere-hidelinks'  => '링크를 $1',
'whatlinkshere-hideimages' => '그림 포함을 $1',
'whatlinkshere-filters'    => '필터',

# Block/unblock
'blockip'                         => '사용자 차단',
'blockip-title'                   => '특정 사용자를 차단하기',
'blockip-legend'                  => '사용자 차단',
'blockiptext'                     => '차단할 IP 주소나 사용자 이름을 아래에 적어 주세요.
차단은 문서 훼손을 막기 위해, [[{{MediaWiki:Policy-url}}|정책]]에 의해서만 이루어져야 합니다.
차단 이유를 같이 적어주세요(예: 특정 문서 훼손).',
'ipaddress'                       => 'IP 주소:',
'ipadressorusername'              => 'IP 주소 또는 계정 이름:',
'ipbexpiry'                       => '기간:',
'ipbreason'                       => '이유:',
'ipbreasonotherlist'              => '다른 이유',
'ipbreason-dropdown'              => '*일반적인 차단 이유
** 거짓 정보 추가
** 문서 내용을 지움
** 문서에 광고성 링크를 만듦
** 장난 편집
** 협박성 행동
** 다중 계정 악용
** 부적절한 계정 이름',
'ipbanononly'                     => '익명 사용자만 차단하기',
'ipbcreateaccount'                => '계정 생성을 막기',
'ipbemailban'                     => '이메일을 보내지 못하도록 막기',
'ipbenableautoblock'              => '이 사용자가 최근에 사용했거나 앞으로 사용하는 IP를 자동으로 막기',
'ipbsubmit'                       => '사용자 차단',
'ipbother'                        => '다른 기간:',
'ipboptions'                      => '2시간:2 hours,1일:1 day,3일:3 days,1주일:1 week,2주일:2 weeks,1개월:1 month,3개월:3 months,6개월:6 months,1년:1 year,무기한:infinite',
'ipbotheroption'                  => '수동으로 지정',
'ipbotherreason'                  => '다른 이유/추가적인 이유:',
'ipbhidename'                     => '사용자 이름을 편집 역사에서 숨기기',
'ipbwatchuser'                    => '이 사용자 문서와 사용자토론 문서를 주시하기',
'ipballowusertalk'                => '차단된 중에도 자신의 토론 문서는 편집할 수 있게 허용',
'ipb-change-block'                => '이 설정으로 이 사용자를 다시 차단합니다',
'badipaddress'                    => '잘못된 IP 주소',
'blockipsuccesssub'               => '차단 완료',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] 사용자가 차단되었습니다. 차단된 사용자 목록은 [[Special:IPBlockList|여기]]에서 볼 수 있습니다.',
'ipb-edit-dropdown'               => '차단 이유 목록 편집하기',
'ipb-unblock-addr'                => '$1 차단 해제하기',
'ipb-unblock'                     => '사용자/IP 주소 차단 해제하기',
'ipb-blocklist-addr'              => '$1의 현재 차단 기록 보기',
'ipb-blocklist'                   => '현재 차단 기록 보기',
'ipb-blocklist-contribs'          => '$1의 기여',
'unblockip'                       => '사용자 차단 해제',
'unblockiptext'                   => '아래의 양식에 차단 해제하려는 IP 주소나 사용자 이름을 입력하세요.',
'ipusubmit'                       => '차단 해제',
'unblocked'                       => '[[User:$1|$1]] 사용자 차단 해제됨',
'unblocked-id'                    => '차단 $1 해제됨',
'ipblocklist'                     => '차단 중인 IP 주소와 계정 이름',
'ipblocklist-legend'              => '차단 중인 사용자 찾기',
'ipblocklist-username'            => '계정 이름/IP 주소:',
'ipblocklist-sh-userblocks'       => '계정 차단을 $1',
'ipblocklist-sh-tempblocks'       => '임시 차단을 $1',
'ipblocklist-sh-addressblocks'    => '단일 IP 차단을 $1',
'ipblocklist-submit'              => '찾기',
'ipblocklist-localblock'          => '로컬 차단',
'ipblocklist-otherblocks'         => '다른 {{PLURAL:$1|}}차단 기록',
'blocklistline'                   => '$1에 $2 사용자는 $3을 차단함 ($4)',
'infiniteblock'                   => '무기한',
'expiringblock'                   => '$1 $2에 해제',
'anononlyblock'                   => '익명 사용자만',
'noautoblockblock'                => '자동 차단 비활성화됨',
'createaccountblock'              => '계정 생성 금지됨',
'emailblock'                      => '이메일 차단됨',
'blocklist-nousertalk'            => '자신의 토론 문서 편집 불가',
'ipblocklist-empty'               => '차단된 사용자가 없습니다.',
'ipblocklist-no-results'          => '당신이 입력한 IP 주소나 사용자는 차단되지 않았습니다.',
'blocklink'                       => '차단',
'unblocklink'                     => '차단 해제',
'change-blocklink'                => '차단 설정 변경',
'contribslink'                    => '기여',
'autoblocker'                     => '당신의 IP 주소는 최근에 "[[User:$1|$1]]" 사용자가 사용하였기 때문에 자동으로 차단되었습니다.
$1 사용자가 차단된 이유는 다음과 같습니다: "$2"',
'blocklogpage'                    => '차단 기록',
'blocklog-showlog'                => '이 사용자는 과거에 차단된 기록이 있습니다. 해당 사용자의 차단 기록은 다음과 같습니다.',
'blocklog-showsuppresslog'        => '이 사용자는 과거에 차단된 적이 있으며, 그 기록이 숨겨져 있습니다. 해당 사용자의 차단 기록은 다음과 같습니다.',
'blocklogentry'                   => '[[$1]] 사용자를 $2 차단함 $3',
'reblock-logentry'                => '[[$1]] 사용자의 차단 기간을 $2(으)로 변경 $3',
'blocklogtext'                    => '이 목록은 사용자 차단/차단 해제 기록입니다. 자동으로 차단된 IP 주소는 여기에 나오지 않습니다. [[Special:IPBlockList|여기]]에서 현재 차단된 사용자 목록을 볼 수 있습니다.',
'unblocklogentry'                 => '$1을 차단 해제했습니다.',
'block-log-flags-anononly'        => 'IP만 막음',
'block-log-flags-nocreate'        => '계정 생성 막음',
'block-log-flags-noautoblock'     => '자동 차단 비활성화됨',
'block-log-flags-noemail'         => '이메일 막음',
'block-log-flags-nousertalk'      => '자신의 토론 문서 편집 불가',
'block-log-flags-angry-autoblock' => '향상된 자동 차단 활성화됨',
'block-log-flags-hiddenname'      => '계정 이름 숨겨짐',
'range_block_disabled'            => 'IP 범위 차단 기능이 비활성화되어 있습니다.',
'ipb_expiry_invalid'              => '차단 기간이 잘못되었습니다.',
'ipb_expiry_temp'                 => '사용자 이름을 숨기는 차단은 반드시 무기한이어야 합니다.',
'ipb_hide_invalid'                => '해당 계정은 막을 수 없습니다. 기여량이 너무 많습니다.',
'ipb_already_blocked'             => '"$1" 사용자는 이미 차단됨',
'ipb-needreblock'                 => '== 이미 차단되어 있습니다 ==
$1 사용자는 이미 차단되었습니다. 차단 설정을 바꾸시겠습니까?',
'ipb-otherblocks-header'          => '다른 {{PLURAL:$1|}}차단 기록',
'ipb_cant_unblock'                => '오류: 차단 ID $1이(가) 존재하지 않습니다.
이미 차단 해제되었을 수 있습니다.',
'ipb_blocked_as_range'            => '오류: IP 주소 $1은 직접 차단되지 않았기 때문에 차단 해제할 수 없습니다.
하지만 $2로 광역 차단되었기 때문에, 광역 차단 해제로 차단을 해제할 수 있습니다.',
'ip_range_invalid'                => 'IP 범위가 잘못되었습니다.',
'ip_range_toolarge'               => '/$1보다 넓은 범위의 광역 차단을 할 수 없습니다.',
'blockme'                         => '자가 차단',
'proxyblocker'                    => '프록시 차단',
'proxyblocker-disabled'           => '이 기능은 비활성되어 있습니다.',
'proxyblockreason'                => '당신의 IP 주소는 공개 프록시로 밝혀져 자동으로 차단됩니다. 만약 인터넷 사용에 문제가 있다면 인터넷 서비스 공급자에게 문의해주세요.',
'proxyblocksuccess'               => '완료.',
'sorbsreason'                     => '당신의 IP 주소는 {{SITENAME}}에서 사용하는 DNSBL 공개 프록시 목록에 들어 있습니다.',
'sorbs_create_account_reason'     => '당신의 IP 주소는 {{SITENAME}}에서 사용하는 DNSBL 공개 프록시 목록에 들어 있습니다. 계정을 만들 수 없습니다.',
'cant-block-while-blocked'        => '당신이 차단되어 있는 동안에는 다른 사용자를 차단할 수 없습니다.',
'cant-see-hidden-user'            => '당신이 차단하려 하는 사용자는 이미 차단되었고 계정 숨김 처리되었습니다. 당신이 계정 숨기기 권한을 갖고 있지 않기 때문에, 이 사용자의 차단 기록을 보거나 차단 설정을 변경할 수 없습니다.',
'ipbblocked'                      => '당신은 차단되어 있기 때문에 다른 사용자를 차단하거나 차단을 해제할 수 없습니다.',
'ipbnounblockself'                => '당신은 자기 스스로를 차단 해제할 수 없습니다.',

# Developer tools
'lockdb'              => '데이터베이스 잠그기',
'unlockdb'            => '데이터베이스 잠금 해제',
'lockdbtext'          => '데이터베이스를 잠그면 모든 사용자의 편집, 환경 설정 변경, 주시문서 편집 등 데이터베이스를 요구하는 모든 기능이 정지됩니다.
정말로 잠가야 하는지를 다시 한번 확인해주세요. 관리 작업이 끝난 뒤에는 데이터베이스 잠금을 풀어야 합니다.',
'unlockdbtext'        => '데이터베이스를 잠금 해제하면 모든 사용자의 편집, 환경 설정 변경, 주시문서 편집 등 데이터베이스를 요구하는 모든 기능이 복구됩니다.
정말로 잠금을 해제하려는지를 다시 한번 확인해주세요.',
'lockconfirm'         => '네, 데이터베이스를 잠급니다.',
'unlockconfirm'       => '네, 데이터베이스를 잠금 해제합니다.',
'lockbtn'             => '데이터베이스 잠그기',
'unlockbtn'           => '데이터베이스 잠금 해제',
'locknoconfirm'       => '확인 체크박스를 선택하지 않았습니다.',
'lockdbsuccesssub'    => '데이터베이스 잠김',
'unlockdbsuccesssub'  => '데이터베이스 잠금 해제됨',
'lockdbsuccesstext'   => '데이터베이스가 잠겼습니다.<br />
관리가 끝나면 잊지 말고 [[Special:UnlockDB|잠금을 풀어]] 주세요.',
'unlockdbsuccesstext' => '데이터베이스 잠금 상태가 해제되었습니다.',
'lockfilenotwritable' => '데이터베이스 잠금 파일에 쓰기 권한이 없습니다. 데이터베이스를 잠그거나 잠금 해제하려면, 웹 서버에서 이 파일의 쓰기 권한을 설정해야 합니다.',
'databasenotlocked'   => '데이터베이스가 잠겨 있지 않습니다.',

# Move page
'move-page'                    => '이동 $1',
'move-page-legend'             => '문서 이동하기',
'movepagetext'                 => "아래 양식을 채워 문서의 이름을 바꾸고 모든 역사를 새 이름으로 옮길 수 있습니다.
기존의 문서는 새 문서로 넘겨주는 문서가 됩니다.
원래 이름을 가리키는 넘겨주기를 자동으로 갱신할 수 있습니다.
만약 이 설정을 선택하지 않았다면 [[Special:DoubleRedirects|이중 넘겨주기]]와 [[Special:BrokenRedirects|끊긴 넘겨주기]]를 확인해주세요.
당신은 링크가 의도한 문서를 가리키게 할 책임이 있습니다.

만약 문서의 새 이름으로 된 문서가 존재할 때, 그 문서가 비었거나 넘겨주기 문서이고 문서 역사가 없을 때에만 이동합니다. 그렇지 않을 경우에는 이동하지 '''않습니다'''.
이것은 실수로 이동한 문서를 되돌릴 수는 있지만, 이미 존재하는 문서 위에 덮어씌울 수는 없다는 것을 의미합니다.

'''주의!'''
자주 사용하는 문서를 이동하면 위험한 결과를 가져올 수 있습니다.
이동하기 전에, 이 문서를 이동해도 문제가 없다는 것을 확인해주세요.",
'movepagetalktext'             => "딸린 토론 문서도 자동으로 이동합니다. 하지만 다음의 경우는 '''이동하지 않습니다''':
* 이동할 이름으로 된 문서가 이미 있는 경우
* 아래의 선택을 해제하는 경우

이 경우에는 문서를 직접 이동하거나 두 문서를 합쳐야 합니다.",
'movearticle'                  => '문서 이동하기',
'moveuserpage-warning'         => "'''경고:''' 당신은 사용자 문서를 옮기려 하고 있습니다. 사용자 문서만 이동되며 계정 이름이 바뀌지 않는다는 점을 명심해주시기 바랍니다.",
'movenologin'                  => '로그인하지 않음',
'movenologintext'              => '[[Special:UserLogin|로그인]]해야만 문서를 이동할 수 있습니다.',
'movenotallowed'               => '문서를 이동할 권한이 없습니다.',
'movenotallowedfile'           => '파일을 옮길 권한이 없습니다.',
'cant-move-user-page'          => '사용자 문서를 옮길 권한이 없습니다 (하위 문서는 제외).',
'cant-move-to-user-page'       => '문서를 사용자 문서로 옮길 권한이 없습니다 (하위 문서는 제외).',
'newtitle'                     => '새 문서 이름',
'move-watch'                   => '이 문서 주시하기',
'movepagebtn'                  => '이동',
'pagemovedsub'                 => '문서 이동함',
'movepage-moved'               => '\'\'\'"$1" 문서를 "$2" 문서로 이동했습니다.\'\'\'',
'movepage-moved-redirect'      => '넘겨주기 문서를 만들었습니다.',
'movepage-moved-noredirect'    => '넘겨주기 문서를 남기지 않았습니다.',
'articleexists'                => '문서가 이미 존재하거나 이름이 올바르지 않습니다.
다른 제목으로 시도해주세요.',
'cantmove-titleprotected'      => '새로운 제목으로 문서를 만드는 것이 금지되어 있어 문서를 이동할 수 없습니다.',
'talkexists'                   => "'''문서는 이동되었습니다. 하지만 딸린 토론 문서의 새 이름으로 된 문서가 이미 있기 때문에 토론 문서는 옮기지 못했습니다. 직접 문서를 합쳐 주세요.'''",
'movedto'                      => '새 이름',
'movetalk'                     => '딸린 토론도 함께 이동합니다.',
'move-subpages'                => '하위 문서도 함께 ($1개 이하) 이동합니다.',
'move-talk-subpages'           => '토론 문서의 하위 문서도 ($1개까지) 함께 이동합니다.',
'movepage-page-exists'         => '이동할 수 없습니다. ‘$1’ 문서가 이미 존재합니다.',
'movepage-page-moved'          => '$1 문서를 $2 (으)로 옮겼습니다',
'movepage-page-unmoved'        => '$1 문서를 $2 (으)로 옮길 수 없습니다.',
'movepage-max-pages'           => '문서를 최대 $1개 옮겼고 나머지는 자동으로 옮기지 않습니다.',
'1movedto2'                    => '[[$1]] 문서를 [[$2]](으)로 옮김',
'1movedto2_redir'              => '[[$1]] 문서를 [[$2]](으)로 옮기면서 넘겨주기를 덮어 씀',
'move-redirect-suppressed'     => '넘겨주기가 생성되지 않음',
'movelogpage'                  => '이동 기록',
'movelogpagetext'              => '아래는 옮겨진 문서의 목록입니다.',
'movesubpage'                  => '{{PLURAL:$1}}하위 문서',
'movesubpagetext'              => '이 문서에는 다음 $1개의 하위 문서가 있습니다.',
'movenosubpage'                => '이 문서에는 하위 문서가 존재하지 않습니다.',
'movereason'                   => '이유:',
'revertmove'                   => '되돌리기',
'delete_and_move'              => '삭제하고 이동',
'delete_and_move_text'         => '== 삭제 필요 ==

이동하려는 제목으로 된 [[:$1]] 문서가 이미 존재합니다.
삭제하고 이동할까요?',
'delete_and_move_confirm'      => '네. 문서를 삭제합니다',
'delete_and_move_reason'       => '문서를 이동하기 위해 삭제함',
'selfmove'                     => '이동하려는 제목이 원래 제목과 같습니다. 이동할 수 없습니다.',
'immobile-source-namespace'    => '"$1" 이름공간에 속한 문서는 이동시킬 수 없습니다.',
'immobile-target-namespace'    => '"$1" 이름공간에 속한 문서는 이동시킬 수 없습니다.',
'immobile-target-namespace-iw' => '인터위키 링크를 넘어 문서를 이동할 수 없습니다.',
'immobile-source-page'         => '이 문서는 이동할 수 없습니다.',
'immobile-target-page'         => '새 이름으로 옮길 수 없습니다.',
'imagenocrossnamespace'        => '파일을 파일이 아닌 이름공간으로 옮길 수 없습니다.',
'imagetypemismatch'            => '새 파일의 확장자가 원래의 확장자와 일치하지 않습니다.',
'imageinvalidfilename'         => '새 파일 이름이 잘못되었습니다.',
'fix-double-redirects'         => '기존 이름을 가리키는 넘겨주기를 갱신',
'move-leave-redirect'          => '이동 후 넘겨주기를 남기기',
'protectedpagemovewarning'     => "'''경고:''' 이 문서는 관리자만이 이동할 수 있도록 잠겨 있습니다.
최근의 기록을 참조용으로 제공합니다:",
'semiprotectedpagemovewarning' => "'''알림:''' 이 문서는 등록된 사용자만이 이동할 수 있도록 잠겨 있습니다.
최근 기록 내용이 참조용으로 제공됩니다:",
'move-over-sharedrepo'         => '== 파일이 존재합니다 ==
[[:$1]] 파일이 공용 저장소에 있습니다. 이 이름으로 파일을 옮기면 공용의 파일을 덮어쓰게 될 것입니다.',
'file-exists-sharedrepo'       => '당신이 선택한 파일 이름은 공용 저장소에서 사용 중입니다.
다른 이름을 선택해주세요.',

# Export
'export'            => '문서 내보내기',
'exporttext'        => '특정 문서와 그 문서의 편집 역사를 XML 파일로 만들 수 있습니다. 이렇게 만들어진 파일은 다른 미디어위키에서 [[Special:Import|문서 가져오기]] 기능을 통해 가져갈 수 있습니다.

문서를 내보내려면, 내보내려는 문서 제목을 한 줄에 하나씩 입력해주세요. 그리고 문서의 전체 역사가 필요한지, 혹은 현재 버전만이 필요한지를 선택해 주세요.

특정 문서를 내보내려면, 예를 들어 ‘[[{{MediaWiki:Mainpage}}]]’ 문서를 내보내려면 [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] 링크를 사용할 수도 있습니다.',
'exportcuronly'     => '현재 버전만 포함하고, 전체 역사는 포함하지 않음',
'exportnohistory'   => "----
'''주의:''' 전체 문서 역사를 내보내는 기능은 성능 문제로 인해 비활성되어 있습니다.",
'export-submit'     => '내보내기',
'export-addcattext' => '분류에 있는 문서 추가:',
'export-addcat'     => '추가',
'export-addnstext'  => '다음 이름공간을 가진 문서를 추가:',
'export-addns'      => '추가',
'export-download'   => '파일로 저장',
'export-templates'  => '틀 포함하기',
'export-pagelinks'  => '다음 단계로 링크된 문서를 포함:',

# Namespace 8 related
'allmessages'                   => '시스템 메시지 목록',
'allmessagesname'               => '이름',
'allmessagesdefault'            => '기본 내용',
'allmessagescurrent'            => '현재 내용',
'allmessagestext'               => '미디어위키 이름공간에 있는 모든 시스템 메시지의 목록입니다. 미디어위키의 번역 작업에 관심이 있으면 [http://www.mediawiki.org/wiki/Localisation 미디어위키 지역화]나 [http://translatewiki.net translatewiki.net]에 참가해주세요.',
'allmessagesnotsupportedDB'     => "'''\$wgUseDatabaseMessages'''가 해제되어 있어서 이 문서는 쓸 수 없습니다.",
'allmessages-filter-legend'     => '필터',
'allmessages-filter'            => '수정 상태로 거르기:',
'allmessages-filter-unmodified' => '수정되지 않음',
'allmessages-filter-all'        => '모두',
'allmessages-filter-modified'   => '수정됨',
'allmessages-prefix'            => '접두어로 거르기:',
'allmessages-language'          => '언어:',
'allmessages-filter-submit'     => '실행',

# Thumbnails
'thumbnail-more'           => '실제 크기로',
'filemissing'              => '파일 사라짐',
'thumbnail_error'          => '섬네일을 만드는 중 오류 발생: $1',
'djvu_page_error'          => 'DjVu 페이지 범위 벗어남',
'djvu_no_xml'              => 'DjVu 파일의 XML 정보를 읽을 수 없음',
'thumbnail_invalid_params' => '섬네일 매개변수가 잘못되었습니다.',
'thumbnail_dest_directory' => '새 목적 디렉토리를 생성할 수 없습니다.',
'thumbnail_image-type'     => '해당 파일 형식은 지원하지 않습니다',
'thumbnail_gd-library'     => 'GD 라이브러리 설정이 잘못되었습니다. $1 함수를 찾을 수 없습니다.',
'thumbnail_image-missing'  => '파일을 찾을 수 없습니다: $1',

# Special:Import
'import'                     => '문서 가져오기',
'importinterwiki'            => '다른 위키에서 문서 가져오기',
'import-interwiki-text'      => '문서를 가져올 위키를 선택하고, 문서 제목을 입력해주세요.
편집 날짜와 편집자의 이름이 보존될 것입니다.
모든 가져오기는 [[Special:Log/import|가져오기 기록]]에 기록될 것입니다.',
'import-interwiki-source'    => '원본 위키/문서:',
'import-interwiki-history'   => '이 문서의 모든 역사를 가져오기',
'import-interwiki-templates' => '모든 틀을 포함하기',
'import-interwiki-submit'    => '가져오기',
'import-interwiki-namespace' => '새 이름공간:',
'import-upload-filename'     => '파일 이름:',
'import-comment'             => '이유:',
'importtext'                 => '원본 위키에서 [[Special:Export|내보내기]] 기능을 사용해 파일을 내려받아서 여기에 올려주세요.',
'importstart'                => '문서를 가져오는 중...',
'import-revision-count'      => '판 $1개',
'importnopages'              => '가져올 문서가 없습니다.',
'imported-log-entries'       => '로그 항목 $1개를 가져왔습니다.',
'importfailed'               => '가져오기 실패: <nowiki>$1</nowiki>',
'importunknownsource'        => '알 수 없는 가져오기 자료 유형',
'importcantopen'             => '파일을 열 수 없습니다.',
'importbadinterwiki'         => '인터위키 링크가 잘못되었습니다.',
'importnotext'               => '내용이 없습니다.',
'importsuccess'              => '가져오기 완료!',
'importhistoryconflict'      => '문서 역사가 충돌하는 판이 있습니다. (이전에 이 문서를 가져온 적이 있을 수도 있습니다)',
'importnosources'            => '문서를 가져올 출처가 정의되지 않았고, 문서 역사 올리기가 비활성화되었습니다.',
'importnofile'               => '가져오기용 파일이 업로드되지 않았습니다.',
'importuploaderrorsize'      => '파일 올리기를 통한 가져오기에 실패했습니다.
파일이 허용된 크기 제한보다 큽니다.',
'importuploaderrorpartial'   => '가져오기 파일을 올리는 데 실패하였습니다.
파일이 부분적으로만 업로드되었습니다.',
'importuploaderrortemp'      => '가져오기 파일을 올리는 데 실패했습니다.
임시 폴더가 존재하지 않습니다.',
'import-parse-failure'       => 'XML 문서 분석 실패',
'import-noarticle'           => '가져올 문서가 없습니다!',
'import-nonewrevisions'      => '이전에 이미 모든 판을 가져왔습니다.',
'xml-error-string'           => '$3단 $2줄 (바이트 $4)에서 $1: $5',
'import-upload'              => 'XML 데이터 올리기',
'import-token-mismatch'      => '세션 데이터가 손실되었습니다. 다시 시도해주세요.',
'import-invalid-interwiki'   => '해당 위키에서 문서를 가져올 수 없습니다.',

# Import log
'importlogpage'                    => '가져오기 기록',
'importlogpagetext'                => '다른 위키에서 가져온 문서 기록입니다.',
'import-logentry-upload'           => ' 사용자가 파일 올리기를 통해 [[$1]] 문서를 가져왔습니다.',
'import-logentry-upload-detail'    => '판 $1개',
'import-logentry-interwiki'        => '$1 문서를 다른 위키에서 가져왔습니다.',
'import-logentry-interwiki-detail' => '$2에서 판 $1개를 가져옴',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '내 사용자 문서',
'tooltip-pt-anonuserpage'         => '현재 사용하는 IP의 사용자 문서',
'tooltip-pt-mytalk'               => '내 토론 문서',
'tooltip-pt-anontalk'             => '현재 사용하는 IP를 위한 사용자 토론 문서',
'tooltip-pt-preferences'          => '사용자 환경 설정',
'tooltip-pt-watchlist'            => '주시문서 목록',
'tooltip-pt-mycontris'            => '내가 편집한 글',
'tooltip-pt-login'                => '꼭 로그인해야 하는 것은 아니지만, 로그인을 권장합니다.',
'tooltip-pt-anonlogin'            => '꼭 필요한 것은 아니지만, 로그인을 하면 편리한 점이 많습니다.',
'tooltip-pt-logout'               => '로그아웃',
'tooltip-ca-talk'                 => '문서의 내용에 대한 토론 문서',
'tooltip-ca-edit'                 => '문서를 편집할 수 있습니다. 저장하기 전에 미리보기를 해 주세요.',
'tooltip-ca-addsection'           => '내용 추가하기',
'tooltip-ca-viewsource'           => '문서가 잠겨 있습니다. 문서의 소스만 볼 수 있습니다.',
'tooltip-ca-history'              => '문서의 과거 버전들',
'tooltip-ca-protect'              => '문서 보호하기',
'tooltip-ca-unprotect'            => '이 문서 보호 헤제하기',
'tooltip-ca-delete'               => '문서 삭제하기',
'tooltip-ca-undelete'             => '삭제된 문서 복구하기',
'tooltip-ca-move'                 => '문서 이동하기',
'tooltip-ca-watch'                => '이 문서를 주시문서 목록에 추가합니다.',
'tooltip-ca-unwatch'              => '이 문서를 주시문서 목록에서 제거합니다.',
'tooltip-search'                  => '{{SITENAME}} 찾기',
'tooltip-search-go'               => '이 이름의 문서가 존재하면 그 문서로 바로가기',
'tooltip-search-fulltext'         => '이 문자열이 포함된 문서 찾기',
'tooltip-p-logo'                  => '대문',
'tooltip-n-mainpage'              => '대문으로',
'tooltip-n-mainpage-description'  => '대문으로',
'tooltip-n-portal'                => '이 프로젝트에 대해',
'tooltip-n-currentevents'         => '최근의 소식을 봅니다.',
'tooltip-n-recentchanges'         => '이 위키에서 최근 바뀐 점의 목록입니다.',
'tooltip-n-randompage'            => '임의 문서로 갑니다.',
'tooltip-n-help'                  => '도움말',
'tooltip-t-whatlinkshere'         => '여기로 연결된 모든 문서의 목록',
'tooltip-t-recentchangeslinked'   => '여기로 연결된 모든 문서의 변경 내역',
'tooltip-feed-rss'                => '이 문서의 RSS 피드입니다.',
'tooltip-feed-atom'               => '이 문서의 Atom 피드입니다.',
'tooltip-t-contributions'         => '이 사용자의 기여 목록을 봅니다.',
'tooltip-t-emailuser'             => '이 사용자에게 이메일을 보냅니다.',
'tooltip-t-upload'                => '파일을 올립니다.',
'tooltip-t-specialpages'          => '모든 특수 문서의 목록입니다.',
'tooltip-t-print'                 => '이 문서의 인쇄용 버전',
'tooltip-t-permalink'             => '문서의 현재 버전에 대한 고유링크',
'tooltip-ca-nstab-main'           => '문서 내용을 봅니다.',
'tooltip-ca-nstab-user'           => '사용자 문서 내용을 봅니다.',
'tooltip-ca-nstab-media'          => '미디어 문서 내용을 봅니다.',
'tooltip-ca-nstab-special'        => '이것은 특수 문서로, 편집할 수 없습니다.',
'tooltip-ca-nstab-project'        => '프로젝트 문서 내용을 봅니다.',
'tooltip-ca-nstab-image'          => '파일 문서 내용을 봅니다.',
'tooltip-ca-nstab-mediawiki'      => '시스템 메시지 내용을 봅니다.',
'tooltip-ca-nstab-template'       => '틀 문서 내용을 봅니다.',
'tooltip-ca-nstab-help'           => '도움말 문서 내용을 봅니다.',
'tooltip-ca-nstab-category'       => '분류 문서 내용을 봅니다.',
'tooltip-minoredit'               => '사소한 편집으로 표시하기',
'tooltip-save'                    => '편집 내용을 저장하기',
'tooltip-preview'                 => '편집 미리 보기. 저장하기 전에 꼭 미리 보기를 해 주세요.',
'tooltip-diff'                    => '원래의 문서와 현재 편집하는 문서를 비교하기',
'tooltip-compareselectedversions' => '이 문서에서 선택한 두 버전간의 차이를 비교',
'tooltip-watch'                   => '이 문서를 주시문서 목록에 추가',
'tooltip-recreate'                => '문서를 편집하는 중 삭제되어도 새로 만들기',
'tooltip-upload'                  => '파일 올리기 시작',
'tooltip-rollback'                => '"되돌리기" 기능을 사용하면 이 문서에 대한 마지막 기여자의 편집을 모두 되돌릴 수 있습니다.',
'tooltip-undo'                    => '"편집 취소" 기능을 사용하면 이 편집이 되돌려지고, 차이보기 기능이 미리보기 형식으로 나타납니다. 
편집 요약에 이 편집을 왜 되돌리는지에 대한 이유를 쓸 수 있습니다.',
'tooltip-preferences-save'        => '환경 설정 저장하기',
'tooltip-summary'                 => '짧은 편집 요약을 적어주세요',

# Stylesheets
'common.css'   => '/** 이 CSS 설정은 모든 스킨에 적용됩니다 */',
'monobook.css' => '/* 이 CSS 설정은 모든 모노북 스킨에 적용됩니다 */',
'modern.css'   => '/* 이 CSS 설정은 모든 모던 스킨에 적용됩니다 */',
'vector.css'   => '/* 이 CSS 설정은 모든 벡터 스킨에 적용됩니다 */',

# Scripts
'common.js'   => '/* 이 자바스크립트 설정은 모든 문서, 모든 사용자에게 적용됩니다. */',
'monobook.js' => '/* 이 자바스크립트 설정은 모노북 스킨을 사용하는 사용자에게 적용됩니다. */',

# Metadata
'nodublincore'      => '더블린 코어 RDF 메타데이터 기능은 비활성되어 있습니다.',
'nocreativecommons' => '크리에이티브 커먼즈 RDF 메타데이터 기능은 비활성되어 있습니다.',
'notacceptable'     => '클라이언트에서 인식 가능한 출력 포맷이 없습니다.',

# Attribution
'anonymous'        => '{{PLURAL:$1}}{{SITENAME}} 익명 사용자',
'siteuser'         => '{{SITENAME}} 사용자 $1',
'anonuser'         => '{{SITENAME}} 익명 사용자 $1',
'lastmodifiedatby' => '이 문서는 $3 사용자가 $1 $2에 마지막으로 바꾸었습니다.',
'othercontribs'    => '$1의 작업을 바탕으로 함.',
'others'           => '기타',
'siteusers'        => '{{PLURAL:$2}}{{SITENAME}} 사용자 $1',
'anonusers'        => '{{PLURAL:$2}}{{SITENAME}} 익명 사용자 $1',
'creditspage'      => '문서 기여자들',
'nocredits'        => '이 문서에서는 기여자 정보가 없습니다.',

# Spam protection
'spamprotectiontitle' => '스팸 방지 필터',
'spamprotectiontext'  => '스팸 필터가 문서 저장을 막았습니다.
외부 사이트로 연결하는 링크 중에 블랙리스트에 포함된 사이트가 있을 것입니다.',
'spamprotectionmatch' => '문제가 되는 부분은 다음과 같습니다: $1',
'spambot_username'    => 'MediaWiki 스팸 제거',
'spam_reverting'      => '$1을 포함하지 않는 최신 버전으로 되돌림',
'spam_blanking'       => '모든 버전에 $1 링크를 포함하고 있어 문서를 비움',

# Info page
'infosubtitle'   => '문서 정보',
'numedits'       => '편집 횟수(문서): $1',
'numtalkedits'   => '편집 횟수(토론 문서): $1',
'numwatchers'    => '주시하는 사용자 수: $1',
'numauthors'     => '기여한 사용자 수(문서): $1',
'numtalkauthors' => '기여한 사용자 수(토론 문서): $1',

# Skin names
'skinname-standard'    => '클래식',
'skinname-nostalgia'   => '노스탤지아',
'skinname-cologneblue' => '쾰른 블루',
'skinname-monobook'    => '모노북',
'skinname-myskin'      => '마이스킨',
'skinname-chick'       => '치크',
'skinname-simple'      => '심플',
'skinname-modern'      => '모던',
'skinname-vector'      => '벡터',

# Math options
'mw_math_png'    => '항상 PNG로 표시',
'mw_math_simple' => '아주 간단한 것은 HTML로, 나머지는 PNG로',
'mw_math_html'   => '가능한 한 HTML로, 나머지는 PNG로',
'mw_math_source' => 'TeX로 남겨둠 (텍스트 브라우저용)',
'mw_math_modern' => '최신 브라우저 사용자에게 권장',
'mw_math_mathml' => '가능하면 MathML로 (실험적인 기능)',

# Math errors
'math_failure'          => '해석 실패',
'math_unknown_error'    => '알 수 없는 오류',
'math_unknown_function' => '알 수 없는 함수',
'math_lexing_error'     => '어휘 오류',
'math_syntax_error'     => '구문 오류',
'math_image_error'      => 'PNG 변환 실패 - latex, dvips, gs, convert가 올바르게 설치되어 있는지 확인해 주세요.',
'math_bad_tmpdir'       => '수식을 임시 폴더에 저장하거나 폴더를 만들 수 없습니다.',
'math_bad_output'       => '수식을 출력 폴더에 저장하거나 폴더를 만들 수 없습니다.',
'math_notexvc'          => '실행할 수 있는 texvc이 없습니다. 설정을 위해 math/README를 읽어 주세요.',

# Patrolling
'markaspatrolleddiff'                 => '검토한 문서로 표시',
'markaspatrolledtext'                 => '이 문서를 검토한 것으로 표시',
'markedaspatrolled'                   => '검토한 문서로 표시',
'markedaspatrolledtext'               => '[[:$1]] 문서의 선택한 판을 검토한 것으로 표시하였습니다.',
'rcpatroldisabled'                    => '최근 바뀜 검토 기능 비활성화됨',
'rcpatroldisabledtext'                => '최근 바뀜 검토 기능은 현재 비활성화되어 있습니다.',
'markedaspatrollederror'              => '검토한 것으로 표시할 수 없습니다.',
'markedaspatrollederrortext'          => '검토한 것으로 표시할 판을 지정해야 합니다.',
'markedaspatrollederror-noautopatrol' => '자신의 편집은 스스로 검토할 수 없습니다.',

# Patrol log
'patrol-log-page'      => '검토 기록',
'patrol-log-header'    => '이 기록은 검토된 문서에 대한 기록입니다.',
'patrol-log-line'      => '$2 문서의 $1을 검토함 $3',
'patrol-log-auto'      => '(자동)',
'patrol-log-diff'      => '$1판',
'log-show-hide-patrol' => '검토 기록을 $1',

# Image deletion
'deletedrevision'                 => '예전 버전 $1이(가) 삭제되었습니다.',
'filedeleteerror-short'           => '파일 삭제 오류: $1',
'filedeleteerror-long'            => '파일을 삭제하는 도중 오류가 발생했습니다:

$1',
'filedelete-missing'              => '"$1" 파일을 삭제할 수 없습니다. 없는 파일입니다.',
'filedelete-old-unregistered'     => '입력한 파일의 "$1" 버전이 데이터베이스에 존재하지 않습니다.',
'filedelete-current-unregistered' => '"$1" 이라는 이름을 가진 파일이 데이터베이스에 존재하지 않습니다.',
'filedelete-archive-read-only'    => '웹 서버의 파일 저장 위치 ‘$1’에 쓰기 권한이 없습니다.',

# Browsing diffs
'previousdiff' => '← 이전 편집',
'nextdiff'     => '다음 편집 →',

# Media information
'mediawarning'         => "'''경고''': 이 파일에 악성 코드가 포함되어 있을 수 있습니다. 파일을 실행하면 컴퓨터에 문제가 생길 가능성이 있습니다.<hr />",
'imagemaxsize'         => "그림 최대 크기:<br />''(파일 문서에 적용되는 기능)''",
'thumbsize'            => '섬네일 크기:',
'widthheightpage'      => '$1×$2, $3페이지',
'file-info'            => '(파일 크기: $1, MIME 종류: $2)',
'file-info-size'       => '($1 × $2 픽셀, 파일 크기: $3, MIME 종류: $4)',
'file-nohires'         => '<small>최대 해상도입니다.</small>',
'svg-long-desc'        => '(SVG 파일, 실제 크기 $1 × $2 픽셀, 파일 크기 $3)',
'show-big-image'       => '최대 해상도',
'show-big-image-thumb' => '<small>미리보기 크기: $1 × $2 픽셀</small>',
'file-info-gif-looped' => '반복됨',
'file-info-gif-frames' => '$1 프레임',
'file-info-png-looped' => '반복됨',
'file-info-png-repeat' => '$1번 재생됨',
'file-info-png-frames' => '$1 프레임',

# Special:NewFiles
'newimages'             => '새 파일 목록',
'imagelisttext'         => "파일 '''$1'''개를 $2 순으로 정렬한 목록입니다.",
'newimages-summary'     => '이 특수 문서는 최근에 올라온 파일을 나열하고 있습니다.',
'newimages-legend'      => '필터',
'newimages-label'       => '파일 이름 (또는 그 일부분):',
'showhidebots'          => '(봇을 $1)',
'noimages'              => '그림이 없습니다.',
'ilsubmit'              => '찾기',
'bydate'                => '날짜',
'sp-newimages-showfrom' => '$1 $2부터 올라온 파일 목록 보기',

# Bad image list
'bad_image_list' => '형식은 아래와 같습니다.

"*"로 시작하는 목록의 내용만 적용됩니다.
매 줄의 첫번째 링크는 부적절한 파일을 가리켜야 합니다.
같은 줄에 따라오는 모든 링크는 예외로 봅니다. (예: 파일이 사용되어야 하는 문서)',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '간체',
'variantname-zh-hant' => '번체',

# Metadata
'metadata'          => '메타데이터',
'metadata-help'     => '이 파일은 카메라/스캐너에서 기록한 부가 정보를 가지고 있습니다. 프로그램에서 파일을 편집할 경우, 새로 저장한 그림 파일에 일부 부가 정보가 빠질 수 있습니다.',
'metadata-expand'   => '자세한 정보 보이기',
'metadata-collapse' => '자세한 정보 숨기기',
'metadata-fields'   => 'EXIF 메타데이터 표가 접혀 있을 때, 다음의 속성값만이 기본적으로 보이게 됩니다. 나머지 값은 자동적으로 숨겨집니다.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => '너비',
'exif-imagelength'                 => '높이',
'exif-bitspersample'               => '픽셀당 비트 수',
'exif-compression'                 => '압축 방식',
'exif-photometricinterpretation'   => '픽셀 배열',
'exif-orientation'                 => '방향',
'exif-samplesperpixel'             => '화소 수',
'exif-planarconfiguration'         => '데이터 정렬 방식',
'exif-ycbcrsubsampling'            => 'Y와 C의 축소 비율',
'exif-ycbcrpositioning'            => 'Y와 C 위치',
'exif-xresolution'                 => '수평 해상도',
'exif-yresolution'                 => '수직 해상도',
'exif-resolutionunit'              => 'X, Y방향 해상도 단위',
'exif-stripoffsets'                => '이미지 데이터 위치',
'exif-rowsperstrip'                => '스트립당 줄의 수',
'exif-stripbytecounts'             => '압축된 스트립당 바이트 수',
'exif-jpeginterchangeformat'       => 'JPEG SOI와의 차이',
'exif-jpeginterchangeformatlength' => 'JPEG 데이터 바이트 수',
'exif-transferfunction'            => '광학 전달 함수',
'exif-whitepoint'                  => '화이트 포인트 색도',
'exif-primarychromaticities'       => '색도의 우선 색',
'exif-ycbcrcoefficients'           => '색 공간 변환 표 계수',
'exif-referenceblackwhite'         => '흑백 값에 대한 정보',
'exif-datetime'                    => '파일이 변경된 날짜와 시간',
'exif-imagedescription'            => '그림 제목',
'exif-make'                        => '카메라 제조사',
'exif-model'                       => '카메라 모델',
'exif-software'                    => '사용된 소프트웨어',
'exif-artist'                      => '저작자',
'exif-copyright'                   => '저작권자',
'exif-exifversion'                 => 'Exif 버전',
'exif-flashpixversion'             => '지원하는 플래시픽스 버전',
'exif-colorspace'                  => '색 공간',
'exif-componentsconfiguration'     => '각 구성 요소의 의미',
'exif-compressedbitsperpixel'      => '이미지 압축 방식',
'exif-pixelydimension'             => '유효한 그림 너비',
'exif-pixelxdimension'             => '유효한 그림 높이',
'exif-makernote'                   => '제조사 주',
'exif-usercomment'                 => '사용자 주',
'exif-relatedsoundfile'            => '관련된 오디오 파일',
'exif-datetimeoriginal'            => '날짜와 시간',
'exif-datetimedigitized'           => '디지털화된 날짜와 시간',
'exif-subsectime'                  => '파일이 변경된 날짜와 시간 (초 단위 미만)',
'exif-subsectimeoriginal'          => '파일 생성시 날짜와 시간 (초단위 미만)',
'exif-subsectimedigitized'         => '디지털화된 날짜와 시간 (초단위 미만)',
'exif-exposuretime'                => '노출 시간',
'exif-exposuretime-format'         => '$1초 ($2)',
'exif-fnumber'                     => 'F 번호',
'exif-exposureprogram'             => '노출 프로그램',
'exif-spectralsensitivity'         => '스펙트럼 감도',
'exif-isospeedratings'             => 'ISO 속도',
'exif-oecf'                        => '광전자 변환 함수',
'exif-shutterspeedvalue'           => '셔터 속도',
'exif-aperturevalue'               => '조리개',
'exif-brightnessvalue'             => '밝기',
'exif-exposurebiasvalue'           => '노출 보정값',
'exif-maxaperturevalue'            => '최대 조리개 값 (최소 F 값)',
'exif-subjectdistance'             => '대상과의 거리',
'exif-meteringmode'                => '측광 방식',
'exif-lightsource'                 => '광원',
'exif-flash'                       => '플래시',
'exif-focallength'                 => '렌즈 초점 거리',
'exif-subjectarea'                 => '대상 위치',
'exif-flashenergy'                 => '플래시 광량',
'exif-spatialfrequencyresponse'    => '공간 주파수 반응',
'exif-focalplanexresolution'       => '초점면 X방향 해상도',
'exif-focalplaneyresolution'       => '초점면 Y방향 해상도',
'exif-focalplaneresolutionunit'    => '초점면 해상도 단위',
'exif-subjectlocation'             => '대상 위치',
'exif-exposureindex'               => '노출 지수',
'exif-sensingmethod'               => '감지 방식',
'exif-filesource'                  => '파일 출처',
'exif-scenetype'                   => '촬영 모드',
'exif-cfapattern'                  => 'CFA 패턴',
'exif-customrendered'              => '이미지 처리 방식',
'exif-exposuremode'                => '노출 방식',
'exif-whitebalance'                => '화이트 밸런스',
'exif-digitalzoomratio'            => '디지털 줌 비율',
'exif-focallengthin35mmfilm'       => '35 mm 필름에서의 초점 거리',
'exif-scenecapturetype'            => '장면 포착 방식',
'exif-gaincontrol'                 => '장면 제어',
'exif-contrast'                    => '대비',
'exif-saturation'                  => '채도',
'exif-sharpness'                   => '선명도',
'exif-devicesettingdescription'    => '장치 설정에 대한 설명',
'exif-subjectdistancerange'        => '대상과의 거리 범위',
'exif-imageuniqueid'               => '이미지 고유 ID',
'exif-gpsversionid'                => 'GPS 태그 버전',
'exif-gpslatituderef'              => '북위 또는 남위',
'exif-gpslatitude'                 => '위도',
'exif-gpslongituderef'             => '동경 또는 서경',
'exif-gpslongitude'                => '경도',
'exif-gpsaltituderef'              => '고도 정보',
'exif-gpsaltitude'                 => '고도',
'exif-gpstimestamp'                => 'GPS 시간 (원자 시계)',
'exif-gpssatellites'               => '측정에 사용된 위성',
'exif-gpsstatus'                   => '수신기 상태',
'exif-gpsmeasuremode'              => '측정 방식',
'exif-gpsdop'                      => '측정 정확도',
'exif-gpsspeedref'                 => '속도 단위',
'exif-gpsspeed'                    => 'GPS 수신기 속도',
'exif-gpstrackref'                 => '이동 방향에 대한 정보',
'exif-gpstrack'                    => '이동 방향',
'exif-gpsimgdirectionref'          => '이미지 방향에 대한 정보',
'exif-gpsimgdirection'             => '이미지 방향',
'exif-gpsmapdatum'                 => '측지 조사 데이처 사용',
'exif-gpsdestlatituderef'          => '목적지의 위도 정보',
'exif-gpsdestlatitude'             => '목적지의 위도',
'exif-gpsdestlongituderef'         => '목적지의 경도 정보',
'exif-gpsdestlongitude'            => '목적지의 경도',
'exif-gpsdestbearingref'           => '목적지의 방향에 대한 정보',
'exif-gpsdestbearing'              => '목적지의 방향',
'exif-gpsdestdistanceref'          => '목적지까지의 거리 정보',
'exif-gpsdestdistance'             => '목적지와의 거리',
'exif-gpsprocessingmethod'         => 'GPS 처리 방식의 이름',
'exif-gpsareainformation'          => 'GPS 구역 이름',
'exif-gpsdatestamp'                => 'GPS 날짜',
'exif-gpsdifferential'             => 'GPS 차이 보정',

# EXIF attributes
'exif-compression-1' => '압축되지 않음',

'exif-unknowndate' => '날짜를 알 수 없음',

'exif-orientation-1' => '일반',
'exif-orientation-2' => '수평으로 뒤집음',
'exif-orientation-3' => '180° 회전됨',
'exif-orientation-4' => '수직으로 뒤집음',
'exif-orientation-5' => '시계 반대 방향으로 90° 회전하고 수직으로 뒤집음',
'exif-orientation-6' => '시계 방향으로 90° 회전함',
'exif-orientation-7' => '시계 방향으로 90° 회전하고 수직으로 뒤집음',
'exif-orientation-8' => '시계 반대 방향으로 90° 회전됨',

'exif-planarconfiguration-1' => '덩어리 형식',
'exif-planarconfiguration-2' => '평면형',

'exif-componentsconfiguration-0' => '존재하지 않음',

'exif-exposureprogram-0' => '정의되지 않음',
'exif-exposureprogram-1' => '수동',
'exif-exposureprogram-2' => '일반 프로그램',
'exif-exposureprogram-3' => '조리개 우선',
'exif-exposureprogram-4' => '셔터 우선',
'exif-exposureprogram-5' => '크리에이티브 프로그램 (깊이 부분 우선)',
'exif-exposureprogram-6' => '액션 프로그램 (빠른 셔터 속도에 치중)',
'exif-exposureprogram-7' => '인물 사진 모드 (배경을 초점 밖으로 하여 대상을 강조)',
'exif-exposureprogram-8' => '풍경 모드 (초점이 배경인 풍경 사진용)',

'exif-subjectdistance-value' => '$1 미터',

'exif-meteringmode-0'   => '알 수 없음',
'exif-meteringmode-1'   => '평균 측광',
'exif-meteringmode-2'   => '중앙 중점 평균 측광',
'exif-meteringmode-3'   => '스팟 측광',
'exif-meteringmode-4'   => '멀티스팟 측광',
'exif-meteringmode-5'   => '평가 측광',
'exif-meteringmode-6'   => '부분',
'exif-meteringmode-255' => '기타',

'exif-lightsource-0'   => '알 수 없음',
'exif-lightsource-1'   => '태양광',
'exif-lightsource-2'   => '형광등',
'exif-lightsource-3'   => '텅스텐 (백열광)',
'exif-lightsource-4'   => '플래시',
'exif-lightsource-9'   => '맑은 날씨',
'exif-lightsource-10'  => '흐린 날씨',
'exif-lightsource-11'  => '그늘',
'exif-lightsource-12'  => '주광색 형광등 (D 5700 – 7100K)',
'exif-lightsource-13'  => '주백색 형광등 (N 4600 – 5400K)',
'exif-lightsource-14'  => '냉백색 형광등 (W 3900 – 4500K)',
'exif-lightsource-15'  => '백색 형광등 (WW 3200 – 3700K)',
'exif-lightsource-17'  => '표준 A',
'exif-lightsource-18'  => '표준 B',
'exif-lightsource-19'  => '표준 C',
'exif-lightsource-24'  => 'ISO 스튜디오 백열광',
'exif-lightsource-255' => '다른 광원',

# Flash modes
'exif-flash-fired-0'    => '플래시가 터지지 않음',
'exif-flash-fired-1'    => '플래시 터짐',
'exif-flash-return-0'   => '플래시 반사광 감지 기능을 사용하지 않음',
'exif-flash-return-2'   => '플래시 반사광이 감지되지 않음',
'exif-flash-return-3'   => '플래시 반사광이 감지됨',
'exif-flash-mode-1'     => '플래시 강제',
'exif-flash-mode-2'     => '플래시 억제',
'exif-flash-mode-3'     => '자동 모드',
'exif-flash-function-1' => '플래시 기능 없음',
'exif-flash-redeye-1'   => '적목 방지 모드',

'exif-focalplaneresolutionunit-2' => '인치',

'exif-sensingmethod-1' => '정의되지 않음',
'exif-sensingmethod-2' => '1칩 색 공간 센서',
'exif-sensingmethod-3' => '2칩 색 공간 센서',
'exif-sensingmethod-4' => '3칩 색 공간 센서',
'exif-sensingmethod-5' => '순차적 색 공간 센서',
'exif-sensingmethod-7' => '3선 센서',
'exif-sensingmethod-8' => '순차적 색 공간 선형 센서',

'exif-scenetype-1' => '직접 촬영된 이미지',

'exif-customrendered-0' => '일반',
'exif-customrendered-1' => '사용자 정의',

'exif-exposuremode-0' => '자동 노출',
'exif-exposuremode-1' => '수동 노출',
'exif-exposuremode-2' => '자동 노출 브래킷',

'exif-whitebalance-0' => '자동 화이트 밸런스',
'exif-whitebalance-1' => '수동 화이트 밸런스',

'exif-scenecapturetype-0' => '표준',
'exif-scenecapturetype-1' => '풍경',
'exif-scenecapturetype-2' => '인물 사진',
'exif-scenecapturetype-3' => '야경 사진',

'exif-gaincontrol-0' => '없음',
'exif-gaincontrol-1' => '약하게 증가',
'exif-gaincontrol-2' => '강하게 증가',
'exif-gaincontrol-3' => '약하게 감소',
'exif-gaincontrol-4' => '강하게 감소',

'exif-contrast-0' => '보통',
'exif-contrast-1' => '부드러움',
'exif-contrast-2' => '강함',

'exif-saturation-0' => '보통',
'exif-saturation-1' => '저채도',
'exif-saturation-2' => '고채도',

'exif-sharpness-0' => '보통',
'exif-sharpness-1' => '부드러움',
'exif-sharpness-2' => '강함',

'exif-subjectdistancerange-0' => '알 수 없음',
'exif-subjectdistancerange-1' => '접사',
'exif-subjectdistancerange-2' => '근거리',
'exif-subjectdistancerange-3' => '원거리',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '북위',
'exif-gpslatitude-s' => '남위',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '동경',
'exif-gpslongitude-w' => '서경',

'exif-gpsstatus-a' => '측정 중',
'exif-gpsstatus-v' => '인터랙티브 측정',

'exif-gpsmeasuremode-2' => '2차원 측정',
'exif-gpsmeasuremode-3' => '3차원 측정',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '킬로미터 매 시간',
'exif-gpsspeed-m' => '마일 매 시간',
'exif-gpsspeed-n' => '노트',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '실제 방위',
'exif-gpsdirection-m' => '자기 방위',

# External editor support
'edit-externally'      => '이 파일을 외부 프로그램을 사용해서 편집하기',
'edit-externally-help' => '(자세한 정보는 [http://www.mediawiki.org/wiki/Manual:External_editors 설치 방법]을 참고하십시오)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '모두',
'imagelistall'     => '모두',
'watchlistall2'    => '모든 기간',
'namespacesall'    => '모두',
'monthsall'        => '모든 달',
'limitall'         => '모두',

# E-mail address confirmation
'confirmemail'              => '이메일 주소 확인',
'confirmemail_noemail'      => '[[Special:Preferences|환경 설정]]에 이메일을 설정하지 않았습니다.',
'confirmemail_text'         => '{{SITENAME}}에서는 이메일 기능을 사용하기 전에 이메일 인증을 받아야 합니다.
아래의 버튼을 누르면 인증 메일을 보냅니다.
메일에는 인증 코드가 들어있는 링크가 있습니다.
그 링크를 웹 브라우저로 열면 인증이 완료됩니다.',
'confirmemail_pending'      => '이미 확인 이메일을 보냈습니다. 계정을 최근에 만들었다면 이메일을 보내는 데에 몇 분이 걸릴 수 있으므로 잠시 후에 다시 확인해 주세요.',
'confirmemail_send'         => '인증 코드를 메일로 보내기',
'confirmemail_sent'         => '인증 이메일을 보냈습니다.',
'confirmemail_oncreate'     => '확인 이메일을 보냈습니다. 이 확인 과정은 로그인하는 데에 필요하지는 않지만, 위키 프로그램에서 제공하는 이메일 기능을 사용하기 위해서 필요합니다.',
'confirmemail_sendfailed'   => '{{SITENAME}}에서 인증 이메일을 보낼 수 없습니다.
이메일 주소를 잘못 입력했는지 확인해주세요.

메일 서버로부터의 응답: $1',
'confirmemail_invalid'      => '인증 코드가 올바르지 않습니다. 인증 코드가 만료되었을 수도 있습니다.',
'confirmemail_needlogin'    => '이메일 주소를 인증하려면 $1이 필요합니다.',
'confirmemail_success'      => '이메일 주소가 인증되었습니다. 이제 로그인해서 위키를 사용하세요.',
'confirmemail_loggedin'     => '이메일 주소가 인증되었습니다.',
'confirmemail_error'        => '당신의 인증을 저장하는 도중 오류가 발생했습니다.',
'confirmemail_subject'      => '{{SITENAME}} 이메일 주소 인증',
'confirmemail_body'         => '$1 아이피 주소를 사용하는 사용자가 {{SITENAME}}의 ‘$2’ 계정에 이메일 인증 신청을 했습니다.

이 계정이 당신의 계정이라면, 아래 주소를 열어서 이메일 인증을 해 주세요.

$3

당신의 계정이 아니라면, 이메일 인증 신청을 취소하기 위해 아래의 주소를 열어주세요.

$5

인증 코드는 $4에 만료됩니다.',
'confirmemail_body_changed' => '$1 IP 주소를 사용하는 사용자가 {{SITENAME}}의 "$2" 계정의 이메일 주소를 바꾸었습니다.

이 계정이 당신의 계정인지 확인하고 {{SITENAME}}의 이메일 기능을 활성화하려면 아래 링크를 클릭하여 이메일 인증을 해 주세요.

$3

이 계정이 당신의 것이 아니라면 다음 링크를 열어 이메일 주소 변경을 취소하십시오.

$5

인증 코드는 $4에 만료됩니다.',
'confirmemail_invalidated'  => '이메일 확인이 취소됨',
'invalidateemail'           => '이메일 확인 취소',

# Scary transclusion
'scarytranscludedisabled' => '[인터위키가 비활성되어 있습니다]',
'scarytranscludefailed'   => '[$1 틀을 불러오는 데에 실패했습니다]',
'scarytranscludetoolong'  => '[URL이 너무 깁니다]',

# Trackbacks
'trackbackbox'      => '이 문서에 달린 트랙백:<br />
$1',
'trackbackremove'   => '([$1 삭제])',
'trackbacklink'     => '트랙백',
'trackbackdeleteok' => '트랙백을 삭제했습니다.',

# Delete conflict
'deletedwhileediting' => "'''주의''': 당신이 이 문서를 편집하던 중에 이 문서가 삭제되었습니다.",
'confirmrecreate'     => '[[User:$1|$1]]([[User talk:$1|토론]]) 사용자가 당신의 편집 도중 문서를 지웠습니다. 삭제 이유는 다음과 같습니다:
: $2
문서를 다시 되살릴 필요가 있는지를 확인해주세요.',
'recreate'            => '새로 만들기',

# action=purge
'confirm_purge_button' => '확인',
'confirm-purge-top'    => '문서의 캐시를 지울까요?',
'confirm-purge-bottom' => '문서를 새로고침하는 것은 캐시를 갱신하고 가장 최근의 버전이 나타나게 할 것입니다.',

# Multipage image navigation
'imgmultipageprev' => '← 이전 페이지',
'imgmultipagenext' => '다음 페이지 →',
'imgmultigo'       => '이동',
'imgmultigoto'     => '$1 페이지로 가기',

# Table pager
'ascending_abbrev'         => '오름차순',
'descending_abbrev'        => '내림차순',
'table_pager_next'         => '다음 문서',
'table_pager_prev'         => '이전 문서',
'table_pager_first'        => '처음 문서',
'table_pager_last'         => '마지막 문서',
'table_pager_limit'        => '문서당 $1개 항목 보이기',
'table_pager_limit_label'  => '페이지당 항목 수:',
'table_pager_limit_submit' => '확인',
'table_pager_empty'        => '결과 없음',

# Auto-summaries
'autosumm-blank'   => '문서를 비움',
'autosumm-replace' => '문서 내용을 ‘$1’으로 교체',
'autoredircomment' => '[[$1]] 문서로 넘겨주기',
'autosumm-new'     => '새 문서: $1',

# Live preview
'livepreview-loading' => '불러오는 중...',
'livepreview-ready'   => '불러 오는 중… 준비!',
'livepreview-failed'  => '실시간 미리 보기 실패! 일반 미리 보기를 이용해주세요.',
'livepreview-error'   => '연결에 실패하였습니다: $1 "$2"
일반 미리보기를 이용하십시오.',

# Friendlier slave lag warnings
'lag-warn-normal' => '최근 $1초 안에 바뀐 문서는 이 목록에서 빠졌을 수 있습니다.',
'lag-warn-high'   => '데이터베이스 서버의 과도한 부하 때문에 최근 $1초 안에 변경된 문서 목록은 표시되지 않을 수 있습니다.',

# Watchlist editor
'watchlistedit-numitems'       => '토론 문서를 제외하고 문서 $1개를 주시하고 있습니다.',
'watchlistedit-noitems'        => '주시문서 목록이 비어 있습니다.',
'watchlistedit-normal-title'   => '주시문서 목록 편집하기',
'watchlistedit-normal-legend'  => '목록에서 문서 제거하기',
'watchlistedit-normal-explain' => "주시문서 목록에 있는 문서의 제목이 아래에 나열되어 있습니다.
주시문서 목록에서 제거하려는 문서가 있으면, 각 항목의 체크박스를 선택한 다음 '{{int:Watchlistedit-normal-submit}}'를 클릭해주세요.
또는 [[Special:Watchlist/raw|목록을 직접 편집]]할 수도 있습니다.",
'watchlistedit-normal-submit'  => '항목 삭제',
'watchlistedit-normal-done'    => '다음 $1 항목을 주시하지 않습니다:',
'watchlistedit-raw-title'      => '주시문서 목록 직접 편집하기',
'watchlistedit-raw-legend'     => '주시문서 목록 직접 편집하기',
'watchlistedit-raw-explain'    => "주시문서 목록의 각 항목이 나와 있습니다. 필요한 항목을 직접 추가하거나 제거할 수 있습니다.
각 줄마다 하나의 제목을 쓰고, 수정을 마쳤다면 '{{int:Watchlistedit-raw-submit}}'을 누르면 됩니다.
또는 [[Special:Watchlist/edit|일반적인 편집기]]를 쓸 수도 있습니다.",
'watchlistedit-raw-titles'     => '목록:',
'watchlistedit-raw-submit'     => '주시문서 목록 갱신',
'watchlistedit-raw-done'       => '주시문서 목록을 갱신했습니다.',
'watchlistedit-raw-added'      => '문서 $1개를 추가했습니다:',
'watchlistedit-raw-removed'    => '문서 $1개를 제거했습니다:',

# Watchlist editing tools
'watchlisttools-view' => '주시문서 최근 바뀜',
'watchlisttools-edit' => '주시문서 목록 보기/편집하기',
'watchlisttools-raw'  => '주시문서 목록 직접 편집하기',

# Core parser functions
'unknown_extension_tag' => '알 수 없는 확장 기능 태그 "$1"',
'duplicate-defaultsort' => '\'\'\'경고:\'\'\' 기본 정렬 키 "$2"가 이전의 기본 정렬 키 "$1"를 덮어쓰고 있습니다.',

# Special:Version
'version'                          => '버전',
'version-extensions'               => '설치된 확장 기능',
'version-specialpages'             => '특수 문서',
'version-parserhooks'              => '파서 훅',
'version-variables'                => '변수',
'version-other'                    => '기타',
'version-mediahandlers'            => '미디어 핸들러',
'version-hooks'                    => '훅',
'version-extension-functions'      => '확장 함수',
'version-parser-extensiontags'     => '파서 확장 태그',
'version-parser-function-hooks'    => '파서 기능 훅',
'version-skin-extension-functions' => '스킨 확장 기능',
'version-hook-name'                => '훅 이름',
'version-hook-subscribedby'        => '훅이 사용된 위치',
'version-version'                  => '(버전 $1)',
'version-license'                  => '라이센스',
'version-software'                 => '설치된 프로그램',
'version-software-product'         => '제품',
'version-software-version'         => '버전',

# Special:FilePath
'filepath'         => '파일 경로',
'filepath-page'    => '파일:',
'filepath-submit'  => '가기',
'filepath-summary' => '파일의 실제 URL 주소를 엽니다.
그림 파일일 경우 원본 해상도의 파일이 열립니다. 다른 종류의 파일일 경우 그 파일의 종류에 맞는 프로그램이 실행됩니다.

"{{ns:file}}:" 접두어를 뺀 파일 이름을 입력해주세요.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => '중복된 파일 찾기',
'fileduplicatesearch-summary'  => '파일 해시값을 이용해 중복 파일을 찾습니다.

"{{ns:file}}:" 접두어를 뺀 파일 이름을 넣어주세요.',
'fileduplicatesearch-legend'   => '중복 찾기',
'fileduplicatesearch-filename' => '파일 이름:',
'fileduplicatesearch-submit'   => '찾기',
'fileduplicatesearch-info'     => '$1 × $2 픽셀<br />파일 크기: $3<br />MIME 유형: $4',
'fileduplicatesearch-result-1' => '‘$1’ 파일과 중복된 파일이 없습니다.',
'fileduplicatesearch-result-n' => '"$1"파일은 중복 파일이 $2개 있습니다.',

# Special:SpecialPages
'specialpages'                   => '특수 문서 목록',
'specialpages-note'              => '----
* 일반 특수 문서.
* <strong class="mw-specialpagerestricted">제한된 특수 문서.</strong>',
'specialpages-group-maintenance' => '관리용 목록',
'specialpages-group-other'       => '다른 특수문서',
'specialpages-group-login'       => '로그인 / 등록',
'specialpages-group-changes'     => '최근 바뀜과 기록',
'specialpages-group-media'       => '파일 관리',
'specialpages-group-users'       => '사용자와 권한',
'specialpages-group-highuse'     => '많이 쓰이는 문서 목록',
'specialpages-group-pages'       => '문서 목록',
'specialpages-group-pagetools'   => '문서 도구',
'specialpages-group-wiki'        => '위키 정보와 도구',
'specialpages-group-redirects'   => '넘겨주기 특수 문서',
'specialpages-group-spam'        => '스팸 처리 도구',

# Special:BlankPage
'blankpage'              => '빈 문서',
'intentionallyblankpage' => '일부러 비워 둔 문서입니다.',

# External image whitelist
'external_image_whitelist' => ' #이 줄은 그대로 두십시오<pre>
#정규 표현식(// 사이에 있는 부분)을 아래에 써 주십시오.
#이 목록은 외부 이미지의 URL과 대조될 것입니다.
#이 목록과 일치하는 것은 그림이 직접 보여지지만, 그렇지 않은 경우 그림을 가리키는 링크만 보이게 될 것입니다.
# "#"으로 시작하는 줄은 주석으로 간주됩니다.
#이 목록은 대소문자를 구별하지 않습니다.

#모든 정규 표현식은 이 줄 위에 넣어 주십시오. 그리고 이 줄은 그대로 두십시오.</pre>',

# Special:Tags
'tags'                    => '유효한 편집에 대한 태그',
'tag-filter'              => '[[Special:Tags|태그]] 필터:',
'tag-filter-submit'       => '필터',
'tags-title'              => '태그',
'tags-intro'              => '이 페이지는 소프트웨어에서 편집에 대해 표시하는 태그와 그 의미를 설명하는 목록입니다.',
'tags-tag'                => '태그 이름',
'tags-display-header'     => '변경 목록의 모양',
'tags-description-header' => '태그에 대한 설명',
'tags-hitcount-header'    => '태그된 바뀜',
'tags-edit'               => '편집',
'tags-hitcount'           => '$1개 바뀜',

# Special:ComparePages
'comparepages'     => '문서 비교',
'compare-selector' => '문서의 특정판 비교',
'compare-page1'    => '첫 번째 문서',
'compare-page2'    => '두 번째 문서',
'compare-rev1'     => '첫 번째 판',
'compare-rev2'     => '두 번째 판',
'compare-submit'   => '비교하기',

# Database error messages
'dberr-header'      => '이 위키에 문제가 있습니다.',
'dberr-problems'    => '죄송합니다. 이 사이트는 기술적인 문제가 있습니다.',
'dberr-again'       => '잠시 후에 다시 시도해주세요.',
'dberr-info'        => '(데이터베이스에 접속할 수 없습니다: $1)',
'dberr-usegoogle'   => '그 동안 구글을 통해 검색할 수도 있습니다.',
'dberr-outofdate'   => '참고로, 구글의 내용 개요는 오래된 것일 수도 있습니다.',
'dberr-cachederror' => '다음은 요청한 문서의 캐시된 복사본이며, 최신이 아닐 수도 있습니다.',

# HTML forms
'htmlform-invalid-input'       => '당신이 입력한 값에 문제가 있습니다.',
'htmlform-select-badoption'    => '당신이 입력한 값은 올바른 설정이 아닙니다.',
'htmlform-int-invalid'         => '당신이 입력한 값은 정수가 아닙니다.',
'htmlform-float-invalid'       => '입력한 값이 수가 아닙니다.',
'htmlform-int-toolow'          => '당신이 입력한 값은 최소값 $1 미만입니다.',
'htmlform-int-toohigh'         => '당신이 입력한 값은 최대값 $1 이상입니다.',
'htmlform-required'            => '이 값은 필수 항목입니다',
'htmlform-submit'              => '저장',
'htmlform-reset'               => '변경한 것을 되돌리기',
'htmlform-selectorother-other' => '기타',

);
