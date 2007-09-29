<?php
/** Korean (한국어)
  *
  * @addtogroup Language
  */
$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => '특수기능',
	NS_MAIN           => '',
	NS_TALK           => '토론',
	NS_USER           => '사용자',
	NS_USER_TALK      => '사용자토론',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1토론',
	NS_IMAGE          => '그림',
	NS_IMAGE_TALK     => '그림토론',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki토론',
	NS_TEMPLATE       => '틀',
	NS_TEMPLATE_TALK  => '틀토론',
	NS_HELP           => '도움말',
	NS_HELP_TALK      => '도움말토론',
	NS_CATEGORY       => '분류',
	NS_CATEGORY_TALK  => '분류토론',
);

$skinNames = array(
	'standard' => '표준',
	'monobook' => '모노북',
	'myskin'   => '내 스킨',
);

$bookstoreList = array(
	'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1',
	'inherit' => true,
);

$datePreferences = false;
$defaultDateFormat = 'ko';
$dateFormats = array(
	'ko time' => 'H:i',
	'ko date' => 'Y년 M월 j일 (D)',
	'ko both' => 'Y년 M월 j일 (D) H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => '링크에 밑줄치기:',
'tog-highlightbroken'         => '없는 문서로 연결된 링크를 <a href="" class="new">이렇게</a> 보이기(선택하지 않으면 이렇게<a href="" class="internal">?</a> 보임)',
'tog-justify'                 => '문단 정렬하기',
'tog-hideminor'               => '사소한 편집을 최근 바뀜에서 숨기기',
'tog-extendwatchlist'         => '주시 문서를 모든 변경 목록에 적용하기',
'tog-usenewrc'                => '향상된 최근 바뀜 (자바스크립트)',
'tog-numberheadings'          => '머릿글 번호 매기기',
'tog-showtoolbar'             => '편집창에 툴바 보이기 (자바스크립트)',
'tog-editondblclick'          => '더블클릭으로 문서 편집하기 (자바스크립트)',
'tog-editsection'             => '"편집" 부분을 눌러 부분을 편집하기',
'tog-editsectiononrightclick' => '제목을 오른쪽 클릭해서 부분 편집하기 (자바스크립트)',
'tog-showtoc'                 => '문서의 차례 보여주기 (머릿글이 4개 이상인 경우)',
'tog-rememberpassword'        => '자동 로그인',
'tog-editwidth'               => '편집상자의 너비를 최대로 맞추기',
'tog-watchcreations'          => '내가 처음 만드는 문서를 주시 목록에 추가하기',
'tog-watchdefault'            => '내가 편집하는 문서를 주시문서 목록에 추가하기',
'tog-watchmoves'              => '내가 이동하는 문서를 주시문서 목록에 추가하기',
'tog-watchdeletion'           => '내가 삭제하는 문서를 주시문서 목록에 추가하기',
'tog-minordefault'            => '‘사소한 편집’을 항상 선택하기',
'tog-previewontop'            => '편집상자 앞에 미리 보기 화면을 보여주기',
'tog-previewonfirst'          => '처음 편집할 때 미리 보기 화면을 보여주기',
'tog-nocache'                 => '문서 캐시 끄기',
'tog-enotifwatchlistpages'    => '문서가 바뀌면 이메일을 보내기',
'tog-enotifusertalkpages'     => '내 토론 문서가 바뀌면 이메일을 보내기',
'tog-enotifminoredits'        => '사소한 편집에도 이메일을 보내기',
'tog-enotifrevealaddr'        => '알림 메일에 내 이메일 주소를 밝히기',
'tog-shownumberswatching'     => '주시 사용자 수를 보여주기',
'tog-fancysig'                => '서명에 링크를 걸지 않기',
'tog-externaleditor'          => '외부 입력기를 기본값으로 사용하기',
'tog-externaldiff'            => '외부 비교 툴을 기본값으로 사용하기',
'tog-uselivepreview'          => '실시간 미리 보기 사용하기 (자바스크립트) (실험적 기능)',
'tog-forceeditsummary'        => '편집 요약을 쓰지 않았을 때 알려주기',
'tog-watchlisthideown'        => '주시문서 목록에서 내 편집을 숨기기',
'tog-watchlisthidebots'       => '주시문서 목록에서 봇 편집을 숨기기',
'tog-watchlisthideminor'      => '주시문서 목록에서 사소한 편집을 숨기기',
'tog-ccmeonemails'            => '이메일을 보낼 때 내 이메일로 복사본을 보내기',
'tog-diffonly'                => '편집 차이를 비교할 때 문서 내용을 보여주지 않기',

'underline-always'  => '항상',
'underline-never'   => '치지 않음',
'underline-default' => '브라우저 설정을 따르기',

'skinpreview' => '(미리 보기)',

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

# Bits of text used by many pages
'categories'            => '분류',
'pagecategories'        => '분류',
'category_header'       => '‘$1’ 분류에 속해 있는 문서',
'subcategories'         => '하위 분류',
'category-media-header' => '‘$1’ 분류에 속해 있는 자료',
'category-empty'        => '이 분류에 속하는 문서나 자료가 없습니다.',

'mainpagetext'      => "<big>'''미디어위키가 성공적으로 설치되었습니다.'''</big>",
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/Help:Contents 이곳]에서 위키 프로그램에 대한 정보를 얻을 수 있습니다.

== 시작하기 ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings 설정하기]
* [http://www.mediawiki.org/wiki/Manual:FAQ 미디어위키 FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce 미디어위키 발표 메일링 리스트]',

'about'          => '소개',
'article'        => '문서 내용',
'newwindow'      => '(새 창으로 열림)',
'cancel'         => '취소',
'qbfind'         => '찾기',
'qbbrowse'       => '탐색',
'qbedit'         => '편집',
'qbpageoptions'  => '문서 기능',
'qbpageinfo'     => '문서 정보',
'qbmyoptions'    => '내 문서',
'qbspecialpages' => '특수 문서',
'moredotdotdot'  => '더 보기...',
'mypage'         => '내 사용자 문서',
'mytalk'         => '내 사용자 토론',
'anontalk'       => '익명 사용자 토론',
'navigation'     => '둘러보기',

# Metadata in edit box
'metadata_help' => '메타데이터([[Project:메타데이터]]에서 자세한 설명을 볼 수 있습니다):',

'errorpagetitle'    => '오류',
'returnto'          => '$1(으)로 돌아가기',
'tagline'           => '{{SITENAME}}',
'help'              => '도움말',
'search'            => '찾기',
'searchbutton'      => '찾기',
'go'                => '가기',
'searcharticle'     => '가기',
'history'           => '문서 역사',
'history_short'     => '역사',
'updatedmarker'     => '마지막으로 방문한 후 변경됨',
'info_short'        => '정보',
'printableversion'  => '인쇄용 문서',
'permalink'         => '고유링크',
'print'             => '인쇄',
'edit'              => '편집',
'editthispage'      => '이 문서 편집하기',
'delete'            => '삭제',
'deletethispage'    => '이 문서 삭제하기',
'undelete_short'    => '$1개의 편집 되살리기',
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
'postcomment'       => '의견 쓰기',
'articlepage'       => '문서 보기',
'talk'              => '토론',
'views'             => '보기',
'toolbox'           => '도구모음',
'userpage'          => '사용자 문서 보기',
'projectpage'       => '프로젝트 문서 보기',
'imagepage'         => '그림 문서 보기',
'mediawikipage'     => '메시지 문서 보기',
'templatepage'      => '틀 문서 보기',
'viewhelppage'      => '도움말 문서 보기',
'categorypage'      => '분류 문서 보기',
'viewtalkpage'      => '토론 보기',
'otherlanguages'    => '다른 언어',
'redirectedfrom'    => '($1에서 넘어옴)',
'redirectpagesub'   => '넘겨주기 문서',
'lastmodifiedat'    => '이 문서는 $2, $1에 마지막으로 바뀌었습니다.', # $1 date, $2 time
'viewcount'         => '이 문서는 총 $1번 읽혔습니다.',
'protectedpage'     => '보호된 문서',
'jumptosearch'      => '찾기',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} 소개',
'aboutpage'         => 'Project:소개',
'bugreports'        => '버그 신고',
'bugreportspage'    => 'Project:버그 신고',
'copyright'         => '문서는 $1 저작권 하에 있습니다.',
'copyrightpagename' => '{{SITENAME}} 저작권',
'copyrightpage'     => 'Project:저작권',
'currentevents'     => '요즘 화제',
'currentevents-url' => '요즘 화제',
'disclaimers'       => '면책 조항',
'disclaimerpage'    => 'Project:면책 조항',
'edithelp'          => '편집 도움말',
'edithelppage'      => 'Project:문서 편집 도움말',
'helppage'          => 'Help:목차',
'mainpage'          => '대문',
'policy-url'        => 'Project:정책',
'portal'            => '사용자 모임',
'portal-url'        => 'Project:사용자 모임',
'privacy'           => '개인정보 정책',
'privacypage'       => 'Project:개인정보 정책',
'sitesupport'       => '기부 안내',
'sitesupport-url'   => 'Project:사이트 지원',

'badaccess'        => '권한 오류',
'badaccess-group0' => '요청한 동작을 실행할 권한이 없습니다.',
'badaccess-group1' => '요청한 동작은 $1 권한을 가진 사용자에게만 가능합니다.',
'badaccess-group2' => '요청한 동작은 $1 중 하나의 권한을 가진 사용자에게만 가능합니다.',
'badaccess-groups' => '요청한 동작은 $1 중 하나의 권한을 가진 사용자에게만 가능합니다.',

'versionrequired'     => '미디어위키 $1 버전 필요',
'versionrequiredtext' => '이 문서를 보기 위해서는 미디어위키 $1 버전이 필요합니다. [[Special:Version|설치되어 있는 미디어위키의 버전]]을 확인해주세요.',

'ok'                      => '확인',
'retrievedfrom'           => '원본 주소 ‘$1’',
'youhavenewmessages'      => '$1 란에 누군가 글을 남겼습니다. ($2)',
'newmessageslink'         => '사용자 토론',
'newmessagesdifflink'     => '바뀐 내용 비교',
'youhavenewmessagesmulti' => '$1 란에 누군가 글을 남겼습니다.',
'editsection'             => '편집',
'editold'                 => '편집',
'editsectionhint'         => '부분 편집: $1',
'toc'                     => '목차',
'showtoc'                 => '보이기',
'hidetoc'                 => '숨기기',
'thisisdeleted'           => '$1을 보거나 되살리겠습니까?',
'viewdeleted'             => '$1을 보겠습니까?',
'restorelink'             => '$1개의 삭제된 편집',
'feedlinks'               => '피드:',
'feed-invalid'            => '잘못된 구독 피드 방식입니다.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => '문서',
'nstab-user'      => '사용자 문서',
'nstab-media'     => '자료',
'nstab-special'   => '특수 문서',
'nstab-project'   => '프로젝트 문서',
'nstab-image'     => '그림',
'nstab-mediawiki' => '메시지',
'nstab-template'  => '틀',
'nstab-help'      => '도움말',
'nstab-category'  => '분류',

# Main script and global functions
'nosuchaction'      => '해당하는 동작이 없습니다.',
'nosuchactiontext'  => 'URL로 요청한 동작을 위키에서 판별할 수 없습니다.',
'nosuchspecialpage' => '해당하는 특수기능이 없습니다.',
'nospecialpagetext' => '요청한 특수 문서가 존재하지 않습니다. 특수 문서의 목록은 [[Special:Specialpages|여기]]에서 볼 수 있습니다.',

# General errors
'error'                => '오류',
'databaseerror'        => '데이터베이스 오류',
'dberrortext'          => '데이터베이스 쿼리 구문 오류가 발생했습니다. 소프트웨어의 버그가 있을 수 있습니다. 마지막으로 요청한 데이터베이스 쿼리는 "<tt>$2</tt>" 함수에서 쓰인 <blockquote><tt>$1</tt></blockquote>입니다. MySQL은 "<tt>$3: $4</tt>" 오류를 냈습니다.',
'dberrortextcl'        => '데이터베이스 쿼리 구문 오류가 발생했습니다. 마지막으로 요청한 데이터베이스 쿼리는 "$2" 함수에서 쓰인 "$1"입니다. MySQL은 "$3: $4" 오류를 냈습니다.',
'noconnect'            => '죄송합니다. 위키의 기술적인 문제로 인해 데이터베이스 서버에 접근할 수 없습니다.<br />$1',
'nodb'                 => '$1 데이터베이스를 선택할 수 없습니다.',
'cachederror'          => '아래는 요청한 문서의 캐시 복사본으로, 최신이 아닐 수 있습니다.',
'laggedslavemode'      => '주의: 문서가 최근 것이 아닐 수 있습니다.',
'readonly'             => '데이터베이스 잠김',
'enterlockreason'      => '데이터베이스를 잠그는 이유와 예상되는 기간을 적어 주세요.',
'readonlytext'         => '현재 데이터베이스는 편집을 하지 못하도록 잠겨 있습니다. 데이터베이스 관리가 끝난 후에는 다시 정상으로 돌아올 것입니다.

관리자가 데이터베이스를 잠근 이유는 다음과 같습니다: $1',
'missingarticle'       => '데이터베이스에서 "$1" 문서를 찾지 못했습니다.

보통 이것은 삭제된 문서의 비교/역사를 가리키는 링크일 것입니다.

만약 그런 경우가 아니라면, 프로그램에 버그가 있을 수도 있습니다. 관리자에게 이 URL을 보내 주세요.',
'readonly_lag'         => '슬레이브 서버에서 마스터 서버를 따라잡을 때까지 데이터베이스가 자동적으로 잠깁니다.',
'internalerror'        => '내부 오류',
'internalerror_info'   => '내부 오류: $1',
'filecopyerror'        => '‘$1’ 파일을 ‘$2’(으)로 복사할 수 없습니다.',
'filerenameerror'      => '‘$1’ 파일을 ‘$2’ 이름으로 바꿀 수 없습니다.',
'filedeleteerror'      => '‘$1’ 파일을 삭제할 수 없습니다.',
'directorycreateerror' => '‘$1’ 디렉토리를 만들 수 없습니다.',
'filenotfound'         => '‘$1’ 파일을 찾을 수 없습니다.',
'fileexistserror'      => '"$1" 파일에 쓸 수 없음: 파일이 존재함',
'unexpected'           => '예상되지 않은 값: "$1"="$2"',
'formerror'            => '오류: 양식을 제출할 수 없습니다.',
'badarticleerror'      => '지금의 명령은 이 문서에서는 실행할 수 없습니다.',
'cannotdelete'         => '문서나 파일을 삭제할 수 없습니다. 이미 삭제되었을 수도 있습니다.',
'badtitle'             => '잘못된 제목',
'badtitletext'         => '문서 제목이 잘못되었거나 비어있습니다.',
'perfdisabled'         => '이 기능을 쓰면 아무도 위키를 쓸 수 없을 정도로 데이터베이스가 느려지기 때문에 임시로 비활성화되었습니다.',
'perfcached'           => '다음 자료는 캐시된 것이므로 현재 상황을 반영하지 않을 수 있습니다.',
'perfcachedts'         => '다음 자료는 캐시된 것으로, $1에 마지막으로 갱신되었습니다.',
'querypage-no-updates' => '이 문서의 갱신이 현재 비활성화되어 있습니다. 자료가 잠시 갱신되지 않을 것입니다.',
'wrong_wfQuery_params' => 'wfQuery()에서 잘못된 매개변수 발생<br />함수: $1<br />쿼리: $2',
'viewsource'           => '내용 보기',
'viewsourcefor'        => '$1의 문서 내용',
'protectedpagetext'    => '이 문서는 문서 편집이 불가능하도록 보호되어 있습니다.',
'viewsourcetext'       => '문서의 원본을 보거나 복사할 수 있습니다:',
'protectedinterface'   => '이 문서는 소프트웨어 인터페이스에 쓰이는 문서로, 잠겨 있습니다.',
'editinginterface'     => "'''경고''': 소프트웨어에서 사용하는 메시지 문서를 고치고 있습니다. 이것은 모든 사용자에게 영향을 끼칩니다.",
'sqlhidden'            => '(SQL 쿼리 숨겨짐)',
'cascadeprotected'     => '다음의 문서에서 이 문서를 사용하고 있고 그 문서에 연쇄적 보호 설정이 걸려 있어, 이 문서도 편집에서 보호됩니다:
$2',
'namespaceprotected'   => "'''$1''' 네임스페이스를 편집할 수 있는 권한이 없습니다.",
'customcssjsprotected' => '이 문서는 다른 사용자의 개인 설정을 담고 있기 때문에, 편집할 권한이 없습니다.',
'ns-specialprotected'  => '{{ns:special}} 네임스페이스의 문서는 편집할 수 없습니다.',

# Login and logout pages
'logouttitle'                => '로그아웃',
'logouttext'                 => "'''{{SITENAME}}에서 로그아웃했습니다.''' 이대로 이름없이 {{SITENAME}}을(를) 이용하거나, 방금 사용했던 계정이나 다른 계정으로 다시 로그인해서 이용할 수 있습니다. 웹 브라우저의 캐시를 지우지 않으면 몇몇 문서에서 로그인이 되어 있는 것처럼 보일 수 있다는 점을 유의해 주세요.",
'welcomecreation'            => '== $1 님, 환영합니다! ==

계정이 만들어졌습니다. [[Special:Preferences|사용자 환경 설정]]에서 당신의 {{SITENAME}} 사용자 환경 설정을 바꿀 수 있습니다.',
'loginpagetitle'             => '로그인',
'yourname'                   => '이름',
'yourpassword'               => '암호',
'yourpasswordagain'          => '암호 확인',
'remembermypassword'         => '로그인 상태를 저장하기',
'yourdomainname'             => '도메인 이름',
'externaldberror'            => '외부 인증 데이터베이스에 오류가 있거나, 외부 계정을 갱신할 권한이 없습니다.',
'loginproblem'               => "'''로그인에 문제가 발생했습니다.'''<br />다시 시도해 보세요.",
'login'                      => '로그인',
'loginprompt'                => '{{SITENAME}}에 로그인하려면 쿠키를 사용할 수 있어야 합니다.',
'userlogin'                  => '로그인 / 계정 만들기',
'logout'                     => '로그아웃',
'userlogout'                 => '로그아웃',
'notloggedin'                => '로그인하고 있지 않음',
'nologin'                    => '계정이 없나요? $1.',
'nologinlink'                => '계정을 만들 수 있습니다',
'createaccount'              => '계정 만들기',
'gotaccount'                 => '계정이 이미 있나요? $1.',
'gotaccountlink'             => '로그인하세요',
'createaccountmail'          => '이메일로 보내기',
'badretype'                  => '암호가 서로 다릅니다.',
'userexists'                 => '사용자 이름이 이미 등록되어 있습니다. 다른 이름으로 만들어주십시오.',
'youremail'                  => '이메일 *',
'username'                   => '사용자 이름:',
'uid'                        => '사용자 ID:',
'yourrealname'               => '실명 *',
'yourlanguage'               => '언어:',
'yournick'                   => '닉네임:',
'badsig'                     => '서명이 잘못되었습니다. HTML 태그를 확인해주세요.',
'badsiglength'               => '서명이 너무 깁니다. 서명은 $1자보다 짧아야 합니다.',
'email'                      => '이메일',
'loginerror'                 => '로그인 오류',
'prefs-help-email'           => '* 이메일(선택): 당신의 메일 주소를 공개하지 않고 다른 사용자들과 이야기를 할 수 있습니다.',
'nocookiesnew'               => '사용자 계정이 생성되었지만 로그인하고 있지는 않습니다. {{SITENAME}}은 로그인을 위해 쿠키를 사용하고, 현재 당신의 웹 브라우저에서는 쿠키 사용이 비활성되어 있습니다. 쿠키 사용을 활성화한 다음 로그인해 주세요.',
'nocookieslogin'             => '{{SITENAME}}에서는 로그인을 위해 쿠키를 사용합니다. 당신의 웹 브라우저에서 쿠키가 비활성되어 있습니다. 쿠키 사용을 활성화한 다음 로그인해 주세요.',
'noname'                     => '사용자 이름이 올바르지 않습니다.',
'loginsuccesstitle'          => '로그인 성공',
'loginsuccess'               => "'''\"\$1\" 계정으로 {{SITENAME}}에 로그인했습니다.'''",
'nosuchuser'                 => '‘$1’ 사용자가 존재하지 않습니다. 철자가 맞는지 확인해주세요. 또는 새 계정을 만들 수 있습니다.',
'nosuchusershort'            => '‘$1’ 사용자가 존재하지 않습니다. 철자가 맞는지 확인해 주세요.',
'nouserspecified'            => '사용자 이름을 입력하지 않았습니다.',
'wrongpassword'              => '암호가 틀립니다. 다시 시도해 주세요.',
'wrongpasswordempty'         => '비밀번호가 비었습니다. 다시 시도해 주세요.',
'passwordtooshort'           => '암호가 너무 짧습니다. 암호 길이는 적어도 $1글자 이상이어야 합니다.',
'mailmypassword'             => '새 암호를 이메일로 보내기',
'passwordremindertitle'      => '{{SITENAME}}에서 보내는 새 암호',
'passwordremindertext'       => 'IP 주소 $1에게서 당신에게 {{SITENAME}}의 새 암호를 보내달라는 요청이 왔습니다. ($4)

사용자 "$2"의 암호는 이제 "$3"입니다. 로그인한 후에 암호를 변경해 주세요.

만약 다른 사람이 암호 요청을 했거나 예전 암호를 기억해냈다면 이 메시지를 무시하고 예전 암호를 계속 사용할 수 있습니다.',
'noemail'                    => '사용자 ‘$1’에 등록된 이메일 주소가 없습니다.',
'passwordsent'               => '‘$1’ 계정의 새로운 암호를 이메일로 보냈습니다. 암호를 받고 다시 로그인해 주세요.',
'blocked-mailpassword'       => '당신의 IP 주소는 편집에서 차단되어 있습니다. 따라서 암호 되살리기 기능은 악용 방지를 위해 금지됩니다.',
'eauthentsent'               => '확인 이메일을 보냈습니다. 이메일 내용의 지시대로 계정 확인 절차를 실행해 주십시오.',
'throttled-mailpassword'     => '비밀번호 확인 이메일을 이미 최근 $1시간 안에 보냈습니다. 악용을 방지하기 위해 확인 이메일은 $1시간당 한 개만을 보낼 수 있습니다.',
'mailerror'                  => '메일 보내기 오류: $1',
'acct_creation_throttle_hit' => '당신은 이미 $1개의 계정이 있습니다. 더 이상 만들 수 없습니다.',
'emailauthenticated'         => '당신의 이메일 주소는 $1에 인증되었습니다.',
'emailnotauthenticated'      => '당신의 이메일 주소가 아직 <strong>확인되지 않았습니다</strong>. 이메일 확인 절차를 거치지 않으면 다음 이메일 기능을 사용할 수 없습니다.',
'noemailprefs'               => '이 기능을 사용하기 위해서는 이메일 주소를 기입해야 합니다.',
'emailconfirmlink'           => '이메일 주소 확인',
'invalidemailaddress'        => '이메일 주소 입력이 잘못되었습니다. 정상적인 이메일을 입력하거나, 칸을 비워 주세요.',
'accountcreated'             => '계정 만들어짐',
'accountcreatedtext'         => '‘$1’ 사용자 계정이 만들어졌습니다.',
'loginlanguagelabel'         => '언어: $1',

# Password reset dialog
'resetpass'               => '계정 비밀번호 초기화',
'resetpass_announce'      => '이메일로 받은 임시 암호로 로그인했습니다. 로그인을 마치려면 새 암호를 입력해 주세요:',
'resetpass_header'        => '암호 재설정',
'resetpass_submit'        => '암호를 변경하고 로그인 하기',
'resetpass_success'       => '암호가 성공적으로 변경되었습니다! 로그인을 해 주세요.',
'resetpass_bad_temporary' => '잘못된 임시 암호. 암호를 이미 바꾸었거나 새로운 임시 암호를 받았을 수 있습니다.',
'resetpass_forbidden'     => '이 위키에서 암호 변경 불가',
'resetpass_missing'       => '입력값 없음.',

# Edit page toolbar
'bold_sample'     => '굵은 글씨',
'bold_tip'        => '굵은 글씨',
'italic_sample'   => '기울인 글씨',
'italic_tip'      => '기울인 글씨',
'link_sample'     => '링크 제목',
'link_tip'        => '내부 링크',
'extlink_sample'  => 'http://www.example.com 사이트 이름',
'extlink_tip'     => '외부 사이트 링크 (앞에 <nowiki>http://</nowiki>를 붙여야 합니다.)',
'headline_sample' => '제목',
'headline_tip'    => '두번째로 큰 문단 제목',
'math_sample'     => '여기에 수식을 쓰세요',
'math_tip'        => '수식 (LaTeX)',
'nowiki_sample'   => '여기에 위키 문법을 사용하지 않을 글을 적어 주세요',
'nowiki_tip'      => '위키 문법 무시하기',
'image_tip'       => '그림 추가하기',
'media_tip'       => '미디어 파일 링크',
'sig_tip'         => '내 서명과 현재 시각',
'hr_tip'          => '가로줄(되도록 사용하지 말아 주세요)',

# Edit pages
'summary'                   => '편집 요약',
'subject'                   => '주제/제목',
'minoredit'                 => '사소한 편집',
'watchthis'                 => '이 문서 주시하기',
'savearticle'               => '저장',
'preview'                   => '미리 보기',
'showpreview'               => '미리 보기',
'showlivepreview'           => '실시간 미리 보기',
'showdiff'                  => '차이 보기',
'anoneditwarning'           => "'''주의''': 로그인하고 있지 않습니다. 당신의 IP 주소가 문서 역사에 남게 됩니다.",
'missingsummary'            => "'''알림:''' 편집 요약을 적지 않았습니다. 그대로 저장하면 편집 요약 없이 저장됩니다.",
'summary-preview'           => '편집 요약 미리 보기',
'subject-preview'           => '주제/제목 미리 보기',
'blockedtitle'              => '차단됨',
'blockedtext'               => '<big>\'\'\'당신의 계정 혹은 IP 주소가 차단되었습니다.\'\'\'</big>

계정을 차단한 사람은 $1이고, 차단한 이유는 다음과 같습니다: "$2"

* 차단이 시작된 시간: $8
* 차단이 만료되는 시간: $6
* 차단된 사용자: $7

$1, 또는 [[{{MediaWiki:grouppage-sysop}}|다른 관리자]]에게 차단에 대해 연락할 수 있습니다. [[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 \'이메일 보내기\' 기능을 사용할 수 있습니다. 당신의 현재 IP 주소는 $3이고, 차단 ID는 #$5입니다. 이메일을 보낼 때에 이 주소를 같이 알려주세요.',
'autoblockedtext'           => "당신의 IP 주소는 $1이 차단한 사용자가 사용했던 IP이기 때문에, 자동으로 차단되었습니다. 차단된 이유는 다음과 같습니다:

:$2

* 차단이 시작된 시간: $8
* 차단이 만료되는 시간: $6

$1, 또는 [[{{MediaWiki:grouppage-sysop}}|다른 관리자]]에게 차단에 대해 연락할 수 있습니다.

[[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 '이메일 보내기' 기능을 사용할 수 있습니다. 또한 이메일 보내기 기능이 차단되어 있으면 이메일을 보낼 수 없습니다.

당신의 현재 IP 주소는 $3이고, 차단 ID는 #$5입니다. 이메일을 보낼 때에 이 주소를 같이 알려주세요.",
'blockedoriginalsource'     => "아래에 '''$1'''의 내용이 나와 있습니다:",
'blockededitsource'         => "아래에 '''$1'''에서의 '''당신의 편집'''이 나와 있습니다:",
'whitelistedittitle'        => '편집하려면 로그인 필요',
'whitelistedittext'         => '문서를 편집하려면 $1해야 합니다.',
'whitelistreadtitle'        => '문서를 보려면 로그인 필요',
'whitelistreadtext'         => '문서를 읽기 위해서는 [[Special:Userlogin|로그인]]해야 합니다.',
'whitelistacctitle'         => '계정을 만들도록 허용되어 있지 않습니다.',
'confirmedittitle'          => '편집하려면 이메일 인증 필요',
'confirmedittext'           => '문서를 고치려면 이메일 인증 절차가 필요합니다. [[Special:Preferences|사용자 환경 설정]]에서 이메일 주소를 입력하고 이메일 주소 인증을 해 주시기 바랍니다.',
'nosuchsectiontitle'        => '해당 부분 없음',
'loginreqtitle'             => '로그인 필요',
'loginreqlink'              => '로그인',
'loginreqpagetext'          => '다른 문서를 보기 위해서는 $1해야 합니다.',
'accmailtitle'              => '암호를 보냈습니다.',
'accmailtext'               => '‘$1’의 암호를 $2로 보냈습니다.',
'newarticle'                => '(새 문서)',
'newarticletext'            => "이 문서는 아직 만들어지지 않았습니다. 문서를 만들기 위해서는 아래의 상자에 내용을 입력하면 됩니다. (자세한 내용은 [[{{MediaWiki:helppage}}|도움말 문서]]를 읽어 주시기 바랍니다.) 만약 잘못 찾아온 문서라면 브라우저의 '''뒤로''' 버튼을 눌러 주세요.",
'anontalkpagetext'          => '----
여기는 계정에 로그인하지 않은 익명 사용자를 위한 토론 문서입니다. 익명 사용자의 사용자 이름은 IP 주소로 나오기 때문에, 한 IP 주소를 여러 명이 같이 쓰거나 유동 IP를 사용하는 경우 엉뚱한 사람에게 의견이 전달될 수 있습니다. 이러한 문제를 피하려면 [[Special:Userlogin|계정을 만들거나 로그인해 주시기 바랍니다]].',
'noarticletext'             => '현재 문서는 비어 있습니다. 이 제목으로 [[Special:Search/{{PAGENAME}}|검색]]하거나 문서를 [{{fullurl:{{FULLPAGENAME}}|action=edit}} 편집]할 수 있습니다.',
'clearyourcache'            => "'''참고''': 설정을 저장한 후에 바뀐 점을 확인하기 위해서는 브라우저의 캐시를 갱신해야 합니다. '''모질라 / 파이어폭스 / 사파리''': ‘시프트’ 키를 누르면서 ‘새로 고침’을 클릭하거나, ''Ctrl-F5''를 입력; '''컨커러''': 단순히 '새로고침'을 클릭하거나 ''F5''를 입력; '''오페라''' 사용자는 ‘도구→설정’에서 캐시를 완전히 비워야 합니다.",
'usercssjsyoucanpreview'    => "'''안내''': CSS/JS 문서를 저장하기 전에 ‘미리 보기’ 기능을 통해 작동을 확인해주세요.",
'usercsspreview'            => "'''이것은 CSS 미리 보기로, 아직 저장하지 않았다는 것을 주의해 주세요!'''",
'userjspreview'             => "'''이것은 자바스크립트 미리 보기로, 아직 저장하지 않았다는 것을 주의해 주세요!'''",
'userinvalidcssjstitle'     => "'''경고''': ‘$1’ 스킨이 존재하지 않습니다. css와 js 문서의 제목은 {{ns:user}}:홍길동/monobook.css처럼 소문자로 씁니다.",
'updated'                   => '(바뀜)',
'note'                      => "'''주의''':",
'previewnote'               => "'''지금 미리 보기로 보고 있는 내용은 아직 저장되지 않았습니다!'''",
'previewconflict'           => '이 미리 보기는 저장할 때의 모습, 즉 위쪽 편집창의 문서를 반영합니다.',
'session_fail_preview'      => "'''죄송합니다. 세션 데이터가 없어져 편집을 저장하지 못했습니다. 다시 시도해도 되지 않으면 로그아웃한 다음 다시 로그인해 보십시오.'''",
'session_fail_preview_html' => "'''죄송합니다. 세션 데이터가 없어져 편집을 저장하지 못했습니다.'''

문서에 HTML 태그가 사용되었기 때문에, 자바스크립트 공격을 막기 위해 미리 보기는 숨겨져 있습니다.

'''다시 시도해도 되지 않으면 로그아웃한 다음 다시 로그인해 보십시오.'''",
'editing'                   => '$1 편집하기',
'editinguser'               => "'''$1''' 사용자 편집하기",
'editingsection'            => '$1 편집하기 (부분)',
'editingcomment'            => '$1 편집하기 (덧붙이기)',
'editconflict'              => '편집 충돌: $1',
'explainconflict'           => "문서를 편집하는 도중에 누군가가 이 문서를 바꾸었습니다. 위쪽에 있는 문서가 현재 바뀐 문서이고, 아래쪽의 문서가 당신이 편집한 문서입니다. 아래쪽의 내용을 위쪽에 적절히 합쳐 주시기 바랍니다. '''위쪽의 편집 내역만이''' 저장됩니다.",
'yourtext'                  => '당신의 편집',
'storedversion'             => '현재 문서',
'nonunicodebrowser'         => "'''주의: 당신의 웹 브라우저가 유니코드를 완벽하게 지원하지 않습니다. 몇몇 문자가 16진수 코드로 나타날 수 있습니다.'''",
'editingold'                => "'''경고''': 지금 옛날 버전의 문서를 고치고 있습니다. 이것을 저장하면 최근에 편집된 부분이 사라질 수 있습니다.",
'yourdiff'                  => '차이',
'copyrightwarning'          => "{{SITENAME}}의 모든 기여는 $2 라이선스에 따라 배포된다는 점을 유의해 주시기 바랍니다. ($1에서 자세한 사항을 읽어 주세요.) 만약 당신이 이에 대해 찬성하지 않는다면, 여기에 편집 내역을 저장하지 말아 주세요. 또한 당신의 기여는 직접 작성했거나, 또는 퍼블릭 도메인과 같은 자유 문서에서 가져온 것을 보증해야 합니다. '''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'copyrightwarning2'         => "{{SITENAME}}의 모든 기여는 $2 라이선스에 따라 배포된다는 점을 유의해 주시기 바랍니다. 만약 당신이 이에 대해 찬성하지 않는다면, 여기에 편집 내역을 저장하지 말아 주세요.<br />또한 당신의 기여는 직접 작성했거나, 또는 퍼블릭 도메인과 같은 자유 문서에서 가져온 것을 보증해야 합니다. ($1에서 자세한 사항을 읽어 주세요.) '''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'longpagewarning'           => "'''주의: 이 문서의 용량이 $1킬로바이트입니다. 몇몇 웹 브라우저에서는 32킬로바이트 이상의 문서를 편집할 때 문제가 발생할 수 있습니다. 만약의 경우를 대비하여, 문서를 여러 문단으로 나누어서 편집할 수 있습니다.'''",
'longpageerror'             => "'''오류: 문서의 크기가 $1킬로바이트로, 최대 가능한 크기인 $2킬로바이트보다 큽니다. 저장할 수 없습니다.'''",
'readonlywarning'           => "'''주의: 데이터베이스가 관리를 위해 잠겨 있습니다. 따라서 문서를 편집한 내용을 지금 저장할 없습니다. 편집 내용을 다른 곳에 저장한 후, 나중에 다시 시도해 주세요.'''",
'protectedpagewarning'      => "'''주의: 이 문서는 관리자만 편집할 수 있도록 보호되어 있습니다. [[Project:문서 보호 정책|문서 보호 정책]]을 참고하십시오.'''",
'semiprotectedpagewarning'  => "'''주의:''' 이 문서는 잠겨 있습니다. 등록된 사용자만이 편집할 수 있습니다.",
'cascadeprotectedwarning'   => '<strong>주의: 다음의 문서에서 이 문서를 사용하고 있고 그 문서에 연쇄적 보호 설정이 걸려 있어, 이 문서도 편집에서 보호되어 관리자만이 편집할 수 있습니다</strong>:',
'templatesused'             => '이 문서에서 사용한 틀:',
'templatesusedpreview'      => '이 미리 보기에서 사용한 틀:',
'templatesusedsection'      => '이 부분에서 사용한 틀:',
'template-protected'        => '(보호됨)',
'template-semiprotected'    => '(준보호됨)',
'edittools'                 => '<!-- 이 문서는 편집 창과 파일 올리기 창에 출력됩니다. -->',
'nocreatetitle'             => '문서 생성 제한',
'nocreatetext'              => '이 사이트에서는 새로운 문서를 생성하는 것에 제한을 두고 있습니다. 이미 존재하는 문서를 편집하거나, [[Special:Userlogin|로그인하거나 계정을 만들 수 있습니다]].',
'nocreate-loggedin'         => '새 문서를 만들 권한이 없습니다.',
'permissionserrors'         => '권한 오류',
'permissionserrorstext'     => '해당 명령을 수행할 권한이 없습니다. 다음의 이유를 확인해보세요:',

# "Undo" feature
'undo-failure' => '중간의 다른 편집과 충돌하여 이 편집을 되돌릴 수 없습니다.',
'undo-summary' => '[[Special:Contributions/$2|$2]]([[User talk:$2|토론]])의 $1판 편집을 되돌림',

# Account creation failure
'cantcreateaccounttitle' => '계정을 만들 수 없음',

# History pages
'revhistory'          => '문서 역사',
'viewpagelogs'        => '이 문서의 기록 보기',
'nohistory'           => '이 문서는 편집 역사가 없습니다.',
'revnotfound'         => '버전 없음',
'revnotfoundtext'     => '문서의 해당 버전을 찾지 못했습니다. 접속할 때 사용한 URL을 확인해 주세요.',
'loadhist'            => '문서 역사 불러오는 중',
'currentrev'          => '현재 버전',
'revisionasof'        => '$1 버전',
'revision-info'       => '$2 사용자의 $1 버전',
'previousrevision'    => '←이전 버전',
'nextrevision'        => '다음 버전→',
'currentrevisionlink' => '현재 문서',
'cur'                 => '현재',
'next'                => '다음',
'last'                => '이전',
'orig'                => '처음',
'page_first'          => '처음',
'page_last'           => '마지막',
'histlegend'          => '비교하려는 버전들을 선택한 다음 버튼을 누르세요.<br />설명: (현재) = 현재 버전과의 차이, (이전) = 바로 이전 버전과의 차이, 잔글 = 사소한 편집',
'deletedrev'          => '[삭제됨]',
'histfirst'           => '처음',
'histlast'            => '마지막',
'historysize'         => '($1 바이트)',
'historyempty'        => '(비었음)',

# Revision feed
'history-feed-title'       => '편집 역사',
'history-feed-description' => '이 문서의 편집 역사',
'history-feed-empty'       => '요청한 문서가 존재하지 않습니다.
해당 문서가 삭제되었거나, 문서 이름이 바뀌었을 수 있습니다.
[[Special:Search|위키 검색 기능]]을 이용해 관련 문서를 찾아보세요.',

# Revision deletion
'rev-delundel'   => '보이기/숨기기',
'revisiondelete' => '버전 삭제/복구',

# Diffs
'difference'                => '(버전 사이의 차이)',
'loadingrev'                => '버전 간의 차이를 받고 있습니다.',
'lineno'                    => '$1번째 줄:',
'editcurrent'               => '현재 버전의 문서를 편집합니다',
'selectnewerversionfordiff' => '비교할 최근 버전을 선택해 주세요.',
'selectolderversionfordiff' => '비교할 과거 버전을 선택해 주세요.',
'compareselectedversions'   => '선택된 버전들을 비교하기',
'editundo'                  => '편집 취소',
'diff-multi'                => '(중간 $1개의 편집이 숨겨짐)',

# Search results
'searchresults'         => '검색 결과',
'searchresulttext'      => '{{SITENAME}} 찾기 기능에 대한 자세한 정보는 [[Project:찾기|{{SITENAME}} 찾기]]를 보세요.',
'searchsubtitle'        => "열쇠말 '''[[:$1]]'''",
'searchsubtitleinvalid' => "열쇠말 '$1'",
'noexactmatch'          => "'''$1 문서가 없습니다.''' 문서를 [[:$1|만들 수]] 있습니다.",
'titlematches'          => '문서 제목 일치',
'notitlematches'        => '해당하는 제목 없음',
'textmatches'           => '문서 내용 일치',
'notextmatches'         => '해당하는 문서 없음',
'prevn'                 => '이전 $1개',
'nextn'                 => '다음 $1개',
'viewprevnext'          => '보기: ($1) ($2) ($3).',
'showingresults'        => '<strong>$2</strong>번 부터 <strong>$1</strong>개의 결과입니다.',
'showingresultsnum'     => "'''$2'''번 부터 '''$3'''개의 결과입니다.",
'nonefound'             => "'''참고''': \"have\"와 \"from\"과 같은 일반적인 단어는 검색에 포함되지 않고, 이런 단어를 포함한 경우 검색이 효과적이지 못할 수 있습니다. 또는 여러 단어를 동시에 검색한 경우에도 효과적인 검색이 되지 않습니다(검색하려는 단어가 모두 들어 있는 문서만이 결과에 나타납니다).",
'powersearch'           => '찾기',
'powersearchtext'       => '다음의 네임스페이스에서 찾기:<br />$1<br />$2 넘겨주기 표시<br />$3를 $9',
'searchdisabled'        => '{{SITENAME}} 검색 기능이 비활성화되어 있습니다. 기능이 작동하지 않는 동안에는 Google을 이용해 검색할 수 있습니다. 검색 엔진의 내용은 최근 것이 아닐 수 있다는 점을 주의해주세요.',

# Preferences page
'preferences'              => '사용자 환경 설정',
'mypreferences'            => '사용자 환경 설정',
'prefs-edits'              => '편집 횟수:',
'prefsnologin'             => '로그인하지 않음',
'prefsnologintext'         => '사용자 환경 설정을 바꾸려면 먼저 [[Special:Userlogin|로그인]]해야 합니다.',
'prefsreset'               => '사용자 환경 설정을 기본값으로 되돌렸습니다.',
'qbsettings'               => '빨리가기 맞춤',
'qbsettings-none'          => '없음',
'qbsettings-fixedleft'     => '왼쪽',
'qbsettings-fixedright'    => '오른쪽',
'qbsettings-floatingleft'  => '왼쪽 고정',
'qbsettings-floatingright' => '오른쪽 고정',
'changepassword'           => '암호 바꾸기',
'skin'                     => '스킨',
'math'                     => '수식',
'dateformat'               => '날짜 형식',
'datedefault'              => '기본값',
'datetime'                 => '날짜와 시각',
'math_failure'             => '해석 실패',
'math_unknown_error'       => '알 수 없는 오류',
'math_unknown_function'    => '알 수 없는 함수',
'math_lexing_error'        => '어휘 오류',
'math_syntax_error'        => '구문 오류',
'math_image_error'         => 'PNG 변환 실패 - latex, dvips, gs가 올바르게 설치되어 있는지 확인해 주세요.',
'math_bad_tmpdir'          => '수식을 임시 폴더에 저장하거나 폴더를 만들 수 없습니다.',
'math_bad_output'          => '수식을 출력 폴더에 저장하거나 폴더를 만들 수 없습니다.',
'math_notexvc'             => '실행할 수 있는 texvc이 없습니다. 설정을 위해 math/README를 읽어 주세요.',
'prefs-personal'           => '사용자 정보',
'prefs-rc'                 => '최근 바뀜',
'prefs-watchlist'          => '주시문서 목록',
'prefs-watchlist-days'     => '주시문서 목록에 보이는 날짜 수:',
'prefs-watchlist-edits'    => '주시문서 목록에 보이는 편집 갯수:',
'prefs-misc'               => '기타',
'saveprefs'                => '저장',
'resetprefs'               => '기본 설정으로',
'oldpassword'              => '현재 암호',
'newpassword'              => '새 암호',
'retypenew'                => '새 암호 확인',
'textboxsize'              => '편집상자 크기',
'rows'                     => '줄 수:',
'columns'                  => '열:',
'searchresultshead'        => '찾기',
'resultsperpage'           => '쪽마다 보이는 결과 수:',
'contextlines'             => '결과마다 보이는 줄 수:',
'contextchars'             => '각 줄에 보이는 글 수:',
'stub-threshold'           => '<a href="#" class="stub">토막글 링크</a>를 표시할 임계값:',
'recentchangesdays'        => '최근 바뀜에 표시할 날짜 수:',
'recentchangescount'       => '최근 바뀜에 표시할 항목 수:',
'savedprefs'               => '설정을 저장했습니다.',
'timezonelegend'           => '시간대',
'timezonetext'             => '현지 시각과 서버 시각(UTC) 사이의 시차를 써 주세요.',
'localtime'                => '현지 시각',
'timezoneoffset'           => '시차¹',
'servertime'               => '서버 시각',
'guesstimezone'            => '웹 브라우저 설정에서 가져오기',
'allowemail'               => '다른 사용자로부터의 이메일 허용',
'defaultns'                => '기본으로 다음의 네임스페이스에서 찾기:',
'default'                  => '기본값',
'files'                    => '파일',

# User rights
'userrights-lookup-user'     => '사용자 권한 관리',
'userrights-user-editname'   => '사용자 이름:',
'editusergroup'              => '사용자 그룹 편집',
'userrights-editusergroup'   => '사용자 그룹 편집',
'saveusergroups'             => '사용자 권한 저장',
'userrights-groupsmember'    => '현재 권한:',
'userrights-groupsavailable' => '가능한 권한:',
'userrights-groupshelp'      => '현재 권한에서 제거하려는 권한이나, 가능한 권한에서 추가하려는 권한을 선택해 주세요. 선택하지 않은 권한은 변경되지 않습니다. CTRL을 누른 채 클릭하면 선택을 해제할 수 있습니다.',

# Groups
'group'            => '권한:',
'group-bot'        => '봇',
'group-sysop'      => '관리자',
'group-bureaucrat' => '뷰로크랫',
'group-all'        => '(모두)',

'group-bot-member'        => '봇',
'group-sysop-member'      => '관리자',
'group-bureaucrat-member' => '뷰로크랫',

'grouppage-bot'        => 'Project:봇',
'grouppage-sysop'      => 'Project:관리자',
'grouppage-bureaucrat' => 'Project:뷰로크랫',

# User rights log
'rightslog'      => '사용자 권한 기록',
'rightslogtext'  => '사용자 권한 조정 기록입니다.',
'rightslogentry' => '$1의 권한을 $2에서 $3으로 변경',
'rightsnone'     => '(없음)',

# Recent changes
'nchanges'                          => '$1개 바뀜',
'recentchanges'                     => '최근 바뀜',
'recentchangestext'                 => '위키의 최근 바뀜 내역이 나와 있습니다.',
'rcnote'                            => '다음은 $3까지 <strong>$2</strong>일간 바뀐 <strong>$1</strong>개의 문서입니다.',
'rcnotefrom'                        => '다음은 <strong>$2</strong>에서부터 바뀐 <strong>$1</strong>개의 문서입니다.',
'rclistfrom'                        => '$1 이래로 바뀐 문서',
'rcshowhideminor'                   => '사소한 편집을 $1',
'rcshowhidebots'                    => '봇을 $1',
'rcshowhideliu'                     => '로그인한 사용자를 $1',
'rcshowhideanons'                   => '익명 사용자를 $1',
'rcshowhidepatr'                    => '검토된 편집을 $1',
'rcshowhidemine'                    => '내 편집을 $1',
'rclinks'                           => '최근 $2일 동안에 바뀐 $1개의 문서를 봅니다.<br />$3',
'diff'                              => '차이',
'hist'                              => '역사',
'hide'                              => '숨기기',
'show'                              => '보이기',
'minoreditletter'                   => '잔글',
'newpageletter'                     => '새글',
'number_of_watching_users_pageview' => '[$1 명이 주시하고 있음]',
'rc_categories'                     => '다음 분류로 제한 (‘|’로 구분)',
'rc_categories_any'                 => '모두',
'newsectionsummary'                 => '새 주제: /* $1 */',

# Recent changes linked
'recentchangeslinked' => '가리키는 글의 바뀜',

# Upload
'upload'                      => '파일 올리기',
'uploadbtn'                   => '파일 올리기',
'reupload'                    => '다시 올리기',
'reuploaddesc'                => '올리기 양식으로 돌아가기',
'uploadnologin'               => '로그인하지 않음',
'uploadnologintext'           => '파일을 올리려면 [[Special:Userlogin|로그인]]해야 합니다.',
'upload_directory_read_only'  => '파일 저장 디렉토리($1)에 쓰기 권한이 없습니다.',
'uploaderror'                 => '올리기 오류',
'uploadtext'                  => "파일을 올리기 위해서는 아래의 양식을 채워주세요. 또는 예전에 올라온 그림을 찾으려면 [[Special:Imagelist|파일 목록]]을 사용할 수 있습니다. [[Special:Log/upload|올리기 기록]]에서 파일이 올라온 기록과 삭제된 기록을 볼 수 있습니다.

문서에 그림을 집어넣으려면 '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''', '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|대체 설명]]</nowiki>'''과 같이 사용합니다. 또는 파일에 직접 링크하려면 '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>'''와 같이 씁니다.",
'uploadlog'                   => '올리기 기록',
'uploadlogpage'               => '올리기 기록',
'uploadlogpagetext'           => '최근 올라온 그림 목록입니다.',
'filename'                    => '파일이름',
'filedesc'                    => '파일의 설명',
'fileuploadsummary'           => '설명:',
'filestatus'                  => '저작권 상태',
'filesource'                  => '출처',
'uploadedfiles'               => '파일 올리기',
'ignorewarning'               => '경고를 무시하고 저장합니다.',
'ignorewarnings'              => '모든 경고 무시하기',
'minlength1'                  => '파일 이름은 적어도 1글자 이상이어야 합니다.',
'illegalfilename'             => '파일명 "$1"에는 문서 제목으로 허용되지 않는 글자가 포함되어 있습니다. 이름을 바꾸어 다시 시도해 주세요.',
'badfilename'                 => '파일 이름이 ‘$1’(으)로 바뀌었습니다.',
'filetype-badmime'            => '‘$1’ MIME을 가진 파일은 올릴 수 없습니다.',
'filetype-badtype'            => "'''.$1'''은 허용되지 않은 파일 확장자입니다.
:가능한 파일 확장자 목록: $2",
'filetype-missing'            => "파일의 확장자('.jpg' 등)가 없습니다.",
'large-file'                  => '파일 크기는 $1을 넘지 않는 것을 추천합니다. 이 파일의 크기는 $2입니다.',
'largefileserver'             => '이 파일의 크기가 서버에서 허용된 설정보다 큽니다.',
'emptyfile'                   => '당신이 올린 파일이 빈 파일입니다. 파일명을 잘못 입력했을 수도 있습니다. 다시 한 번 확인해 주시기 바랍니다.',
'fileexists'                  => '같은 이름의 파일이 이미 있습니다. 파일을 바꾸고 싶지 않다면 $1을 확인해 주시기 바랍니다.',
'fileexists-extension'        => '비슷한 이름의 파일이 존재합니다:<br />
올리려는 파일 이름: <strong><tt>$1</tt></strong><br />
존재하는 파일 이름: <strong><tt>$2</tt></strong><br />
다른 이름으로 시도해 주세요.',
'fileexists-thumb'            => "'''<center>존재하는 그림</center>'''",
'fileexists-forbidden'        => '같은 이름의 파일이 이미 있습니다. 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '같은 이름의 파일이 이미 공용 파일 저장소에 있습니다. 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => '올리기 성공',
'uploadwarning'               => '올리기 경고',
'savefile'                    => '파일 저장',
'uploadedimage'               => '‘[[$1]]’을(를) 올렸습니다.',
'overwroteimage'              => '‘[[$1]]’ 파일의 새 버전을 올렸습니다.',
'uploaddisabled'              => '올리기 비활성화됨',
'uploaddisabledtext'          => '이 위키에서는 파일 올리기 기능이 비활성화되어 있습니다.',
'uploadscripted'              => '이 파일에는 HTML이나 다른 스크립트 코드가 포함되어 있어, 웹 브라우저에서 오류를 일으킬 수 있습니다.',
'uploadcorrupt'               => '이 파일은 잘못된 형식을 가지고 있습니다. 파일을 확인하고 다시 시도해 주세요.',
'uploadvirus'                 => '파일이 바이러스를 포함하고 있습니다! 상세 설명: $1',
'sourcefilename'              => '원본 파일 이름',
'destfilename'                => '파일의 새 이름',
'watchthisupload'             => '이 문서 주시하기',
'filewasdeleted'              => '같은 이름을 가진 파일이 올라온 적이 있었고, 그 후에 삭제되었습니다. 올리기 전에 $1을 확인해 주시기 바랍니다.',

'upload-proto-error'      => '잘못된 프로토콜',
'upload-proto-error-text' => '파일을 URL로 올리려면 <code>http://</code>이나 <code>ftp://</code>로 시작해야 합니다.',
'upload-file-error'       => '내부 오류',
'upload-file-error-text'  => '서버에 임시 파일을 만드는 과정에서 내부 오류가 발생했습니다. 시스템 관리자에게 연락해주세요.',
'upload-misc-error'       => 'Unknown upload error
알 수 없는 파일 올리기 오류',
'upload-misc-error-text'  => '파일을 올리는 중 알 수 없는 오류가 발생했습니다. URL이 올바르고 접근 가능한지를 확인하고 다시 시도해주세요. 문제가 계속되면 시스템 관리자에게 연락해주세요.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL 접근 불가',

'license'   => '라이센스',
'nolicense' => '선택하지 않음',

# Image list
'imagelist'                 => '그림 목록',
'imagelisttext'             => '$1개의 파일이 $2 순으로 정렬되어 있습니다.',
'getimagelist'              => '그림 목록 가져오기',
'ilsubmit'                  => '찾기',
'showlast'                  => '최근의 $1개 파일이 $2 순으로 정렬되어 있습니다.',
'byname'                    => '이름',
'bydate'                    => '날짜',
'bysize'                    => '크기',
'imgdelete'                 => '삭제',
'imgdesc'                   => '설명',
'imgfile'                   => '파일',
'filehist'                  => '파일 역사',
'imagelinks'                => '그림 링크',
'linkstoimage'              => '다음 문서들이 이 그림을 사용하고 있습니다:',
'nolinkstoimage'            => '이 그림을 사용하는 문서가 없습니다.',
'sharedupload'              => '이 파일은 공용 저장소에 있습니다. 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.',
'shareduploadwiki'          => '$1에서 더 자세한 정보를 얻을 수 있습니다.',
'shareduploadwiki-linktext' => '파일 설명 문서',
'noimage'                   => '파일이 없습니다. $1할 수 있습니다.',
'noimage-linktext'          => '업로드',
'uploadnewversion-linktext' => '이 파일의 새로운 버전을 올리기',
'imagelist_date'            => '날짜',
'imagelist_name'            => '이름',
'imagelist_user'            => '사용자',
'imagelist_size'            => '크기',
'imagelist_description'     => '설명',

# MIME search
'mimesearch' => 'MIME 검색',
'mimetype'   => 'MIME 종류:',
'download'   => '다운로드',

# Unwatched pages
'unwatchedpages' => '주시되지 않는 문서 목록',

# List redirects
'listredirects' => '넘겨주기 문서 목록',

# Unused templates
'unusedtemplates'     => '사용하지 않는 틀 목록',
'unusedtemplatestext' => '다른 문서에 사용되지 않는 틀을 모아 놓았습니다. 삭제하기 전에 쓰이지 않는지를 다시 한번 확인해 주세요.',
'unusedtemplateswlh'  => '다른 링크',

# Random redirect
'randomredirect'         => '임의 넘겨주기 문서로',
'randomredirect-nopages' => '이 네임스페이스에서 해당하는 넘겨주기 문서가 없습니다.',

# Statistics
'statistics'             => '통계',
'sitestats'              => '{{SITENAME}} 통계',
'userstats'              => '사용자 통계',
'sitestatstext'          => "현재 데이터베이스에는 '''\$1'''개의 문서가 있습니다. 이것은 토론 문서, {{SITENAME}} 문서, \"토막글\" 문서, 넘겨주기 문서 등을 포함하고 있습니다. 이것들을 제외하면 '''\$2'''개의 문서가 있습니다.

'''\$8'''개의 파일이 올라와 있습니다.

위키가 설치된 후 문서는 전체 '''\$3'''번 읽혔고, '''\$4'''번 편집되었습니다. 문서당 평균 편집 횟수는 '''\$5'''번이고, 한번 편집할 때마다 평균 '''\$6'''번 문서를 보았습니다.

[http://meta.wikimedia.org/wiki/Help:Job_queue job queue]의 길이는 '''\$7'''입니다.",
'userstatstext'          => "'''$1'''명의 [[Special:Listusers|사용자]]가 등록되어 있고, 이 중 $5 권한을 가진 사용자 수는 '''$2'''명('''$4%''')입니다.",
'statistics-mostpopular' => '가장 많이 읽힌 문서',

'disambiguations'      => '동음이의 문서 목록',
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "다음의 문서들은 '''동음이의 문서'''를 가리키고 있습니다. 그 링크를 다른 적절한 문서로 연결해 주어야 합니다.<br />[[MediaWiki:disambiguationspage]]에서 링크된 틀을 사용하는 문서를 동음이의 문서로 간주합니다.",

'doubleredirects'     => '이중 넘겨주기 목록',
'doubleredirectstext' => '각 열의 첫번째 문서는 두번째 문서로, 두번째 문서는 세번째 문서로 연결됩니다. 첫번째 문서를 마지막 문서로 연결해 주어야 합니다.',

'brokenredirects'        => '끊긴 넘겨주기 목록',
'brokenredirectstext'    => '존재하지 않는 문서로 넘겨주기가 되어 있는 문서의 목록입니다:',
'brokenredirects-edit'   => '(편집)',
'brokenredirects-delete' => '(삭제)',

'withoutinterwiki'        => '언어 인터위키 링크가 없는 문서 목록',
'withoutinterwiki-header' => '다른 언어로의 연결이 없는 문서의 목록입니다:',

'fewestrevisions' => '편집 역사가 짧은 문서 목록',

# Miscellaneous special pages
'nbytes'                  => '$1 바이트',
'ncategories'             => '$1개의 분류',
'nlinks'                  => '$1개의 링크',
'nmembers'                => '$1개의 항목',
'nrevisions'              => '$1개의 판',
'nviews'                  => '$1번 읽음',
'specialpage-empty'       => '명령에 대한 결과가 없습니다.',
'lonelypages'             => '외톨이 문서 목록',
'lonelypagestext'         => '다른 문서에서 연결하지 않는 문서의 목록입니다.',
'uncategorizedpages'      => '분류되지 않은 문서 목록',
'uncategorizedcategories' => '분류되지 않은 분류 목록',
'uncategorizedimages'     => '분류되지 않은 그림 목록',
'uncategorizedtemplates'  => '분류되지 않은 틀 목록',
'unusedcategories'        => '사용하지 않는 분류 목록',
'unusedimages'            => '사용하지 않는 그림 목록',
'popularpages'            => '인기있는 문서 목록',
'wantedcategories'        => '필요한 분류 목록',
'wantedpages'             => '필요한 문서 목록',
'mostlinked'              => '가장 많이 연결된 문서 목록',
'mostlinkedcategories'    => '가장 많이 연결된 분류 목록',
'mostcategories'          => '가장 많이 분류된 문서 목록',
'mostimages'              => '가장 많이 연결된 그림 목록',
'mostrevisions'           => '가장 많이 편집된 문서 목록',
'allpages'                => '모든 문서 목록',
'prefixindex'             => '접두어 목록',
'randompage'              => '임의 문서로',
'randompage-nopages'      => '이 네임스페이스에는 문서가 없습니다.',
'shortpages'              => '짧은 문서 목록',
'longpages'               => '긴 문서 목록',
'deadendpages'            => '막다른 문서 목록',
'deadendpagestext'        => '다른 문서로 연결하지 않는 문서의 목록입니다.',
'protectedpages'          => '보호된 문서 목록',
'protectedpagestext'      => '다음의 문서는 이동/편집이 불가능하도록 보호되어 있습니다.',
'protectedpagesempty'     => '보호되어 있는 문서가 없습니다.',
'listusers'               => '사용자 목록',
'specialpages'            => '특수 문서 목록',
'spheading'               => '일반 특수 문서',
'restrictedpheading'      => '제한된 특수 문서',
'rclsub'                  => '(‘$1’에서 링크된 문서들)',
'newpages'                => '새 문서 목록',
'newpages-username'       => '이름:',
'ancientpages'            => '오래된 문서 목록',
'intl'                    => '인터위키',
'move'                    => '이동',
'movethispage'            => '문서 이동하기',
'unusedimagestext'        => '<p>다른 사이트에서 그림의 URL을 사용하고 있을 가능성이 있고, 따라서 이 목록에 있는 그림도 사용하고 있을 수 있습니다.</p>',
'unusedcategoriestext'    => '사용하지 않는 분류 문서들의 목록입니다.',

# Book sources
'booksources'               => '책 찾기',
'booksources-search-legend' => '책 찾기',
'booksources-go'            => '찾기',
'booksources-text'          => '아래의 목록은 새 책이나 헌 책을 판매하는 외부 사이트로, 원하는 책의 정보를 얻을 수 있습니다:',

'categoriespagetext' => '위키에 존재하는 분류의 목록입니다.',
'data'               => '자료',
'userrights'         => '사용자 권한 관리',
'groups'             => '사용자 권한 목록',
'alphaindexline'     => '$1에서 $2까지',
'version'            => '버전',

# Special:Log
'specialloguserlabel'  => '이름:',
'speciallogtitlelabel' => '제목:',
'log'                  => '로그 목록',
'all-logs-page'        => '모든 기록',
'alllogstext'          => '파일 올리기, 문서 삭제, 보호, 사용자 차단, 관리자 기록이 모두 나와 있습니다. 원하는 기록을 선택해서 볼 수 있습니다.',
'logempty'             => '일치하는 항목이 없습니다.',

# Special:Allpages
'nextpage'          => '다음 문서 ($1)',
'prevpage'          => '이전 문서 ($1)',
'allpagesfrom'      => '다음으로 시작하는 문서들을 보여주기:',
'allarticles'       => '모든 문서',
'allinnamespace'    => '$1 네임스페이스의 모든 문서',
'allnotinnamespace' => '$1 네임스페이스를 제외한 모든 문서 목록',
'allpagesprev'      => '이전',
'allpagesnext'      => '다음',
'allpagessubmit'    => '표시',
'allpagesprefix'    => '다음 접두어로 시작하는 문서 목록:',
'allpagesbadtitle'  => '문서 제목이 잘못되었거나 다른 사이트로 연결되는 인터위키를 가지고 있습니다. 문서 제목에 사용할 수 없는 문자를 사용했을 수 있습니다.',
'allpages-bad-ns'   => '{{SITENAME}}에는 ‘$1’ 네임스페이스를 사용하지 않습니다.',

# Special:Listusers
'listusersfrom'      => '다음으로 시작하는 사용자 보이기:',
'listusers-submit'   => '보이기',
'listusers-noresult' => '해당 사용자가 없습니다.',

# E-mail user
'mailnologin'     => '보낼 이메일 주소가 없음',
'mailnologintext' => '다른 사용자에게 이메일을 보내려면 {{SITENAME}}에 [[Special:Userlogin|로그인]]한 상태에서 [[Special:Preferences|사용자 환경 설정]]에 자신의 이메일 주소를 저장해야 합니다.',
'emailuser'       => '이 사용자에게 이메일 보내기',
'emailpage'       => '사용자에게 이메일 보내기',
'emailpagetext'   => '이 사용자가 환경설정에 올바른 이메일 주소를 적었다면, 아래 양식을 통해 이메일을 보낼 수 있습니다. 받는이가 바로 답장할 수 있도록, 당신의 설정에 적힌 주소가 ‘보낸이’ 주소에 들어갑니다.',
'usermailererror' => '메일 객체에서 오류 발생:',
'defemailsubject' => '{{SITENAME}} 이메일',
'noemailtitle'    => '이메일 주소 없음',
'noemailtext'     => '이 사용자는 올바른 이메일 주소를 입력하지 않았거나, 이메일을 받지 않도록 설정해 놓았습니다.',
'emailfrom'       => '이메일 발신자',
'emailto'         => '수신자',
'emailsubject'    => '제목',
'emailmessage'    => '내용',
'emailsend'       => '보내기',
'emailccme'       => '메일 사본을 내 이메일로 보내기',
'emailccsubject'  => '$1에게 보낸 메일 사본: $2',
'emailsent'       => '이메일 보냄',
'emailsenttext'   => '이메일을 보냈습니다.',

# Watchlist
'watchlist'            => '주시문서 목록',
'mywatchlist'          => '내 주시문서 목록',
'watchlistfor'         => "('''$1'''의 목록)",
'nowatchlist'          => '주시하는 문서가 아직 없습니다.',
'watchlistanontext'    => '주시문서 목록의 항목들을 보거나 편집하려면 $1을(를) 보세요.',
'watchnologin'         => '로그인하지 않음',
'watchnologintext'     => '[[Special:Userlogin|로그인]]을 해야만 주시문서 목록을 볼 수 있습니다.',
'addedwatch'           => '주시문서 목록에 추가',
'addedwatchtext'       => "‘[[:$1]]’ 문서가 주시문서 목록에 추가되었습니다. 앞으로 이 문서나 토론 문서가 변경되면 [[Special:Recentchanges|최근 바뀜]]에서 변경점들이 '''굵은 글씨'''로 나타날 것입니다. 더 이상 주시하지 않으려면 ‘주시 해제’를 누르면 됩니다.",
'removedwatch'         => '주시문서 목록에서 제거',
'removedwatchtext'     => '‘[[:$1]]’ 문서를 주시문서 목록에서 제거했습니다.',
'watch'                => '주시',
'watchthispage'        => '주시하기',
'unwatch'              => '주시 해제',
'unwatchthispage'      => '주시 해제하기',
'notanarticle'         => '문서가 아님',
'watchnochange'        => '주어진 기간 중에 바뀐 주시문서가 없습니다.',
'watchlist-details'    => '$1개(토론 제외)의 문서를 주시하고 있습니다.',
'wlheader-enotif'      => '* 이메일 알림 기능이 활성화되었습니다.',
'wlheader-showupdated' => "* 마지막으로 방문한 이후에 바뀐 문서들은 '''굵은 글씨'''로 표시됩니다.",
'watchlistcontains'    => '$1개의 문서를 주시하고 있습니다.',
'iteminvalidname'      => '"$1" 항목에 문제가 발생했습니다. 이름이 잘못되었습니다...',
'wlnote'               => "다음은 최근 '''$2'''시간 동안에 바뀐 $1개의 문서입니다.",
'wlshowlast'           => '$3 최근 $1 시간 $2 일 동안에 바뀐 문서',
'watchlist-show-bots'  => '봇의 편집을 보이기',
'watchlist-hide-bots'  => '봇의 편집을 숨기기',
'watchlist-show-own'   => '나의 편집을 보이기',
'watchlist-hide-own'   => '나의 편집을 숨기기',
'watchlist-show-minor' => '사소한 편집을 보이기',
'watchlist-hide-minor' => '사소한 편집을 숨기기',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => '주시하는 중...',
'unwatching' => '주시 해제하는 중...',

'enotif_mailer'                => '{{SITENAME}} 자동 알림 메일',
'enotif_reset'                 => '모든 문서를 방문한 것으로 표시하기',
'enotif_newpagetext'           => '이것은 새 문서입니다.',
'enotif_impersonal_salutation' => '{{SITENAME}} 사용자',
'changed'                      => '바뀜',
'created'                      => '만들어짐',
'enotif_subject'               => '{{SITENAME}}의 문서 $PAGETITLE이 $PAGEEDITOR에 의해 $CHANGEDORCREATED되었습니다',
'enotif_lastvisited'           => '당신의 마지막 방문 이후의 모든 변경사항을 보려면 $1을(를) 보십시오.',
'enotif_anon_editor'           => '익명 사용자 $1',
'enotif_body'                  => '$WATCHINGUSERNAME에게,

{{SITENAME}}의 문서 $PAGETITLE이(가) $PAGEEDITOR에 의해 $CHANGEDORCREATED되었습니다. 현재의 문서는 $PAGETITLE_URL에서 볼 수 있습니다.

$NEWPAGE

편집 요약: $PAGESUMMARY $PAGEMINOREDIT

다음을 통해 편집자와 대화를 할 수 있습니다:
이메일: $PAGEEDITOR_EMAIL
위키: $PAGEEDITOR_WIKI

이 문서를 방문하기 전까지는 알림 이메일은 발송되지 않습니다.

             {{SITENAME}} 알림 시스템

--
주시문서 설정을 바꾸려면 다음을 사용하세요:
{{fullurl:{{ns:special}}:Watchlist/edit}}

도움말:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => '문서 삭제하기',
'confirm'                     => '확인',
'excontent'                   => '내용: ‘$1’',
'excontentauthor'             => '내용: ‘$1’ (그리고 편집한 사람은 ‘$2’뿐)',
'exbeforeblank'               => '비우기 전의 내용: ‘$1’',
'exblank'                     => '빈 문서',
'confirmdelete'               => '삭제 확인',
'deletesub'                   => '($1 삭제)',
'historywarning'              => '주의: 현재 삭제하려는 문서에는 문서 역사가 있습니다:',
'confirmdeletetext'           => '문서나 그림, 그리고 이 문서의 역사를 삭제하려고 합니다. 삭제하기 전에 다시 한번 문서 역사를 확인해 주시기 바랍니다.',
'actioncomplete'              => '명령완료',
'deletedtext'                 => '$1 문서가 삭제되었습니다. $2에서 최근의 삭제 기록을 볼 수 있습니다.',
'deletedarticle'              => '[[$1]] 삭제됨',
'dellogpage'                  => '삭제 기록',
'dellogpagetext'              => '아래의 목록은 최근에 삭제된 문서들입니다.',
'deletionlog'                 => '삭제 기록',
'reverted'                    => '이전 버전으로 되돌렸습니다.',
'deletecomment'               => '삭제 이유',
'rollback'                    => '편집 되돌리기',
'rollback_short'              => '되돌리기',
'rollbacklink'                => '되돌리기',
'rollbackfailed'              => '되돌리기 실패',
'cantrollback'                => '편집을 되돌릴 수 없습니다. 문서를 편집한 사용자가 한명뿐입니다.',
'alreadyrolled'               => '[[$1]]에서 [[User:$2|$2]]([[User talk:$2|토론]])의 편집을 되돌릴 수 없습니다. 누군가가 문서를 고치거나 되돌렸습니다.

마지막으로 문서를 편집한 사람은[[User:$3|$3]]([[User talk:$3|토론]])입니다.',
'editcomment'                 => "편집 요약: ''$1''", # only shown if there is an edit comment
'revertpage'                  => '[[Special:Contributions/$2|$2]]([[User talk:$2|토론]])의 편집을 [[Special:Contributions/$1|$1]]의 버전으로 되돌림',
'rollback-success'            => '$1의 편집을 $2의 마지막 버전으로 되돌렸습니다.',
'sessionfailure'              => '로그인 세션에 문제가 발생한 것 같습니다. 세션 하이재킹을 막기 위해 동작이 취소되었습니다. 브라우저의 "뒤로" 버튼을 누르고 문서를 새로고침한 후에 다시 시도해 주세요.',
'protectlogpage'              => '문서 보호 기록',
'protectlogtext'              => '아래의 목록은 문서 보호와 보호 해제 기록입니다.',
'protectedarticle'            => '‘[[$1]]’ 문서가 보호됨',
'modifiedarticleprotection'   => '‘[[$1]]’ 문서의 보호 설정이 변경됨',
'unprotectedarticle'          => '"[[$1]]" 문서가 보호 해제되었음',
'protectsub'                  => '("$1" 보호하기)',
'confirmprotect'              => '보호 확인',
'protectcomment'              => '보호 이유',
'unprotectsub'                => '("$1" 보호 해제하기)',
'protect-unchain'             => '이동 권한을 수동으로 조정',
'protect-text'                => "'''$1''' 문서의 보호 수준을 보거나 변경할 수 있습니다.",
'protect-cascadeon'           => '이 문서는 다음의 틀에서 사용하고 있고 그 틀에 연쇄적 보호가 걸려 있어 이 문서도 자동으로 보호됩니다. 이 문서의 보호 설정을 바꾸어도 연쇄적 보호에 영향을 받지 않습니다.',
'protect-default'             => '(기본값)',
'protect-fallback'            => '‘$1’ 권한 필요',
'protect-level-autoconfirmed' => '등록된 사용자만 가능',
'protect-level-sysop'         => '관리자만 가능',
'protect-summary-cascade'     => '연쇄적',
'protect-cascade'             => '연쇄적 보호 - 이 문서에서 사용되는 다른 문서를 함께 보호합니다.',
'pagesize'                    => '(바이트)',

# Restrictions (nouns)
'restriction-edit' => '편집',
'restriction-move' => '이동',

# Restriction levels
'restriction-level-sysop'         => '보호됨',
'restriction-level-autoconfirmed' => '준보호됨',

# Undelete
'undelete'                 => '삭제된 문서 보기',
'undeletepage'             => '삭제된 문서를 보거나 되살리기',
'viewdeletedpage'          => '삭제된 문서 보기',
'undeletepagetext'         => '다음의 문서는 삭제되었지만 보관되어 있고, 되살릴 수 있습니다. 보관된 문서들은 주기적으로 삭제될 것입니다.',
'undeleteextrahelp'        => "문서 역사 전체를 복구하려면 모든 체크박스를 선택 해제한 뒤 '''복구'''를 누르세요.
특정한 버전만을 복구하려면 복구하려는 버전들을 선택한 뒤 '''복구'''를 누르세요. '''초기화'''를 누르면 모든 선택이 취소됩니다.",
'undeleterevisions'        => '$1개의 버전 보관중',
'undeletehistory'          => '문서를 되살리면 모든 역사가 같이 복구됩니다. 문서가 삭제된 후에 같은 이름의 문서가 만들어졌다면, 복구되는 버전들은 역사의 과거 부분에 나타날 것입니다.',
'undeletehistorynoadmin'   => '이 문서는 삭제되어 있습니다. 삭제된 이유와 삭제되기 전에 이 문서를 편집한 사용자들이 아래에 나와 있습니다. 삭제된 문서를 보려면 관리자 권한이 필요합니다.',
'undeletebtn'              => '복구',
'undeletereset'            => '초기화',
'undeletedarticle'         => '"[[$1]]" 복구됨',
'undeletedrevisions'       => '$1개의 버전이 복구되었습니다.',
'undeletedrevisions-files' => '$1개의 버전과 $2개의 파일이 복구되었습니다.',
'undeletedfiles'           => '$1개의 파일이 복구되었습니다.',
'cannotundelete'           => '복구에 실패했습니다. 다른 누군가가 이미 복구했을 수도 있습니다.',
'undeletedpage'            => "<big>'''$1이(가) 복구되었습니다.'''</big>

[[Special:Log/delete|삭제 기록]]에서 최근의 삭제/복구 기록을 볼 수 있습니다.",
'undelete-header'          => '최근에 삭제된 문서 기록은 [[Special:Log/delete|여기]]에서 볼 수 있습니다.',
'undelete-search-box'      => '삭제된 문서 찾기',
'undelete-search-prefix'   => '다음으로 시작하는 문서 보이기:',

# Namespace form on various pages
'namespace'      => '네임스페이스:',
'invert'         => '선택 반전',
'blanknamespace' => '(일반)',

# Contributions
'contributions' => '사용자 기여',
'mycontris'     => '내 기여 목록',
'contribsub2'   => '$1($2)의 기여',
'nocontribs'    => '이 사용자는 어디에도 기여하지 않았습니다.',
'ucnote'        => "이 사용자가 '''$2'''일 동안에 바꾼 '''$1'''개의 목록입니다.",
'uclinks'       => '최근 $1개 보기; 최근 $2일 보기',
'uctop'         => ' (최신)',

'sp-contributions-newest'   => '마지막',
'sp-contributions-oldest'   => '처음',
'sp-contributions-newer'    => '이전 $1개',
'sp-contributions-older'    => '다음 $1개',
'sp-contributions-blocklog' => '차단 기록',
'sp-contributions-username' => 'IP 주소 혹은 사용자 이름:',

'sp-newimages-showfrom' => '$1부터 올라온 그림 목록 보기',

# What links here
'whatlinkshere'       => '여기를 가리키는 글',
'whatlinkshere-title' => '$1 문서를 가리키는 문서 목록',
'notargettitle'       => '해당하는 문서 없음',
'notargettext'        => '기능을 수행할 목표 문서나 목표 사용자를 지정하지 않았습니다.',
'linklistsub'         => '(링크 목록)',
'linkshere'           => "다음의 문서들이 '''[[:$1]]''' 문서를 가리키고 있습니다:",
'nolinkshere'         => "'''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'nolinkshere-ns'      => "해당 네임스페이스에서 '''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'isredirect'          => '넘겨주기 문서',
'istemplate'          => '포함',
'whatlinkshere-prev'  => '{{PLURAL:$1|이전|이전 $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|다음|다음 $1}}',
'whatlinkshere-links' => '← 가리키는 문서 목록',

# Block/unblock
'blockip'                     => '사용자 차단',
'blockiptext'                 => '차단할 IP 주소나 사용자 이름을 아래에 적어 주세요. 차단은 반드시 반달행위를 막기 위해서, 혹은 {{SITENAME}} 정책에 의해서만 이루어져야 합니다. 차단 이유도 같이 적어 주시기 바랍니다.',
'ipaddress'                   => 'IP 주소',
'ipadressorusername'          => 'IP 주소 또는 사용자 이름',
'ipbexpiry'                   => '기간',
'ipbreason'                   => '이유',
'ipbreasonotherlist'          => '다른 이유',
'ipbanononly'                 => '익명 사용자만 차단',
'ipbcreateaccount'            => '계정 생성을 막기',
'ipbemailban'                 => '이메일 보내기 기능을 막기',
'ipbenableautoblock'          => '이 사용자가 최근에 사용했거나 앞으로 사용하는 IP를 자동으로 막기',
'ipbsubmit'                   => '이 사용자를 차단하기',
'ipbother'                    => '지정 기간',
'ipboptions'                  => '2시간:2 hours,1일:1 day,3일:3 days,1주일:1 week,2주일:2 weeks,1개월:1 month,3개월:3 months,6개월:6 months,1년:1 year,무기한:infinite',
'ipbotheroption'              => '수동으로 지정',
'badipaddress'                => '잘못된 IP 주소',
'blockipsuccesssub'           => '차단 완료',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] 사용자가 차단되었습니다. 차단된 사용자 목록은 [[Special:Ipblocklist|여기]]에서 볼 수 있습니다.',
'ipb-unblock-addr'            => '$1 차단 해제하기',
'ipb-unblock'                 => '사용자/IP 주소 차단 해제하기',
'ipb-blocklist-addr'          => '$1의 현재 차단 기록 보기',
'ipb-blocklist'               => '현재 차단 기록 보기',
'unblockip'                   => '사용자 차단 해제',
'unblockiptext'               => '아래의 양식에 차단 해제하려는 IP 주소나 사용자 이름을 입력하세요.',
'ipusubmit'                   => '차단 해제',
'unblocked'                   => '[[User:$1|$1]] 사용자 차단 해제됨',
'unblocked-id'                => '차단 $1 해제됨',
'ipblocklist'                 => '현재 차단 중인 IP 주소와 사용자 이름 목록',
'blocklistline'               => '$1, $2 사용자는 $3을 차단함 ($4)',
'infiniteblock'               => '무기한',
'expiringblock'               => '$1에 해제',
'noautoblockblock'            => '자동 차단 비활성화됨',
'createaccountblock'          => '계정 생성 금지됨',
'blocklink'                   => '차단',
'unblocklink'                 => '차단 해제',
'contribslink'                => '기여',
'autoblocker'                 => "당신의 IP 주소는 최근 ‘[[User:$1|$1]]’이(가) 사용하였기 때문에 자동으로 차단되었습니다. $1의 차단 이유는 다음과 같습니다: '''$2'''",
'blocklogpage'                => '차단 기록',
'blocklogentry'               => '[[$1]] 사용자를 $2 $3 차단함',
'blocklogtext'                => '이 목록은 사용자 차단/차단 해제 기록입니다. 자동으로 차단된 IP 주소는 여기에 나오지 않습니다. [[Special:Ipblocklist|여기]]에서 현재 차단된 사용자 목록을 볼 수 있습니다.',
'unblocklogentry'             => '$1을 차단 해제했습니다.',
'block-log-flags-anononly'    => 'IP 접속만 차단하기',
'range_block_disabled'        => 'IP 범위 차단 기능이 비활성화되어 있습니다.',
'ipb_expiry_invalid'          => '차단 기간이 잘못되었습니다.',
'ipb_already_blocked'         => '$1 사용자는 이미 차단됨',
'ip_range_invalid'            => 'IP 범위가 잘못되었습니다.',
'proxyblocker'                => '프록시 차단',
'ipb_cant_unblock'            => '오류: 차단 ID $1이(가) 존재하지 않습니다. 이미 차단 해제되었을 수 있습니다.',
'proxyblockreason'            => '당신의 IP 주소는 공개 프록시로 밝혀져 자동으로 차단됩니다. 만약 인터넷 사용에 문제가 있다면 인터넷 서비스 공급자에게 문의해주세요.',
'proxyblocksuccess'           => '완료.',
'sorbsreason'                 => '당신의 IP 주소는 DNSBL의 공개 프록시 목록에 들어있습니다.',
'sorbs_create_account_reason' => '당신의 IP 주소는 DNSBL의 공개 프록시 목록에 들어있습니다. 계정을 만들 수 없습니다.',

# Developer tools
'lockdb'              => '데이터베이스 잠그기',
'unlockdb'            => '데이터베이스 잠금 해제',
'lockdbtext'          => '데이터베이스를 잠그면 모든 사용자의 편집 권한, 환경 설정 변경 권한, 주시문서 편집 권한 등의 모든 기능이 정지됩니다. 정말로 잠가야 하는지를 다시 한번 확인해주세요.',
'unlockdbtext'        => '데이터베이스를 잠금 해제하면 모든 사용자의 편집 권한, 환경 설정 변경 권한, 주시문서 편집 권한 등의 모든 기능이 복구됩니다. 정말로 잠금을 해제하려는지를 다시 한번 확인해주세요.',
'lockconfirm'         => '네, 데이터베이스를 잠급니다.',
'unlockconfirm'       => '네, 데이터베이스를 잠금 해제합니다.',
'lockbtn'             => '데이터베이스 잠그기',
'unlockbtn'           => '데이터베이스 잠금 해제',
'locknoconfirm'       => '확인 체크박스를 선택하지 않았습니다.',
'lockdbsuccesssub'    => '데이터베이스 잠김',
'unlockdbsuccesssub'  => '데이터베이스 잠금 해제됨',
'lockdbsuccesstext'   => '데이터베이스가 잠겼습니다.<br />관리가 끝나면 잠금을 해제하는 것을 잊지 말아 주세요.',
'unlockdbsuccesstext' => '데이터베이스 잠금 상태가 해제되었습니다.',
'lockfilenotwritable' => '데이터베이스 잠금 파일에 쓰기 권한이 없습니다. 데이터베이스를 잠그거나 잠금 해제하려면, 웹 서버에서 이 파일의 쓰기 권한을 설정해야 합니다.',
'databasenotlocked'   => '데이터베이스가 잠겨 있지 않습니다.',

# Move page
'movepage'                => '문서 이동하기',
'movepagetext'            => "아래 틀을 채워 문서의 이름을 바꿀 수 있습니다. 문서의 역사도 모두 새 문서로 옮겨집니다. 기존의 문서는 새 문서로 넘겨주는 문서가 됩니다. 기존 문서에서의 링크는 바뀌지 않습니다. 이중 넘겨주기와 끊긴 넘겨주기에 주의해주세요.

만약 문서의 새 이름으로 된 문서가 존재할 때, 이 문서가 비었거나 넘겨주기 문서이고 문서 역사가 없을 때에만 이동합니다. 그렇지 않을 경우에는 이동하지 '''않습니다'''. 이것은 실수로 이동한 문서를 되돌릴 수는 있지만, 이미 존재하는 문서 위에 덮어씌울 수는 없다는 것을 의미합니다.

'''주의!''' 자주 사용하는 문서를 이동하면 위험한 결과를 가져올 수 있습니다. 이동하기 전에, 이 문서를 이동해도 문제가 없다는 것을 확인해주세요.",
'movepagetalktext'        => "딸린 토론 문서도 자동으로 이동합니다. 다음의 경우는 '''이동하지 않습니다''':
* 이동할 이름으로 된 문서가 이미 있는 경우
* 아래의 선택을 해제하는 경우
이 경우에는 문서를 직접 이동하거나 두 문서를 합쳐야 합니다.",
'movearticle'             => '문서 이동하기',
'movenologin'             => '로그인하지 않음',
'movenologintext'         => '[[Special:Userlogin|로그인]]해야만 문서를 이동할 수 있습니다.',
'movenotallowed'          => '문서를 이동할 권한이 없습니다.',
'newtitle'                => '새 문서 이름',
'move-watch'              => '이 문서 주시하기',
'movepagebtn'             => '이동',
'pagemovedsub'            => '문서 이동함',
'movepage-moved'          => "<big>'''‘$1’ 문서를 ‘$2’(으)로 이동함 '''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => '문서가 이미 존재하거나, 문서 이름이 올바르지 않습니다. 다른 제목으로 시도해주세요.',
'talkexists'              => "'''문서는 이동되었습니다. 하지만 딸린 토론 문서의 새 이름으로 된 문서가 이미 존재해서, 토론 문서는 이동하지 않았습니다. 직접 문서를 합쳐 주세요.'''",
'movedto'                 => '새 이름',
'movetalk'                => '딸린 토론도 함께 이동합니다.',
'talkpagemoved'           => '딸린 토론도 이동했습니다.',
'talkpagenotmoved'        => "딸린 토론은 이동하지 '''않았습니다'''.",
'1movedto2'               => '[[$1]]을(를) [[$2]](으)로 옮김',
'1movedto2_redir'         => '[[$1]]을(를) [[$2]](으)로 옮기면서 넘겨주기를 덮어 씀',
'movelogpage'             => '이동 기록',
'movelogpagetext'         => '아래는 옮겨진 문서의 목록입니다.',
'movereason'              => '이유',
'revertmove'              => '되돌리기',
'delete_and_move'         => '삭제하고 이동',
'delete_and_move_text'    => '== 삭제 필요 ==

이동하려는 제목으로 된 문서 [[$1]]이(가) 이미 존재합니다. 삭제하고 이동할까요?',
'delete_and_move_confirm' => '네. 문서를 삭제합니다',
'delete_and_move_reason'  => '문서를 이동하기 위해 삭제함',
'selfmove'                => '이동하려는 제목이 원래 제목과 같습니다. 이동할 수 없습니다.',
'immobile_namespace'      => '특수한 네임스페이스로는 이동할 수 없습니다.',

# Export
'export'          => '문서 내보내기',
'exporttext'      => '문서와 편집 역사들을 XML 형식으로 만들 수 있습니다. 이렇게 만들어지는 파일은 다른 미디어위키에서 Special:Import page를 통해 가져올 수 있습니다.

문서를 내보내기 위해서는, 아래의 상자에 문서의 제목들을 한 줄에 한 제목씩 쓰면 됩니다. 그리고 현재의 버전만을 내보낼지, 또는 전체 역사, 최근의 내역 등을 내보낼지 선택해 주시기 바랍니다.

특정 문서를 내보내려면, 예를 들어 {{Mediawiki:Mainpage}} 문서를 내보내려면 [[Special:Export/{{Mediawiki:Mainpage}}]] 문서를 사용할 수도 있습니다.',
'exportcuronly'   => '현재 버전만 포함하고, 전체 역사는 포함하지 않음',
'exportnohistory' => "----
'''주의:''' 전체 문서 역사를 내보내는 기능은 성능 문제로 인해 비활성되어 있습니다.",
'export-submit'   => '내보내기',

# Namespace 8 related
'allmessages'               => '시스템 메시지 목록',
'allmessagesname'           => '이름',
'allmessagesdefault'        => '기본 내용',
'allmessagescurrent'        => '현재 내용',
'allmessagestext'           => 'MediaWiki 네임스페이스에 있는 모든 시스템 메시지의 목록입니다.',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages'''가 해제되어 있어서 '''Special:Allmessages'''를 사용할 수 없습니다.",
'allmessagesfilter'         => '다음 메시지만 보이기:',
'allmessagesmodified'       => '변경된 것만 보여주기',

# Thumbnails
'thumbnail-more'  => '실제 크기로',
'missingimage'    => "'''사라진 그림'''<br />''$1''",
'filemissing'     => '파일 사라짐',
'thumbnail_error' => '섬네일을 만드는 중 오류 발생: $1',

# Special:Import
'import'            => '문서 가져오기',
'importuploaderror' => '파일 올리기를 실패했습니다. 파일의 크기가 허용치를 넘었을 수 있습니다.',

# Import log
'importlogpage'     => '파일 올리기 기록',
'importlogpagetext' => '다른 위키에서 가져온 문서 기록입니다.',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '내 사용자 문서',
'tooltip-pt-anonuserpage'         => '현재 사용하는 IP의 사용자 문서',
'tooltip-pt-mytalk'               => '내 토론 문서',
'tooltip-pt-anontalk'             => '현재 사용하는 IP를 위한 사용자 토론 문서',
'tooltip-pt-preferences'          => '사용자 환경 설정',
'tooltip-pt-watchlist'            => '주시문서 목록',
'tooltip-pt-mycontris'            => '내가 편집한 글',
'tooltip-pt-anonlogin'            => '꼭 필요한 것은 아니지만, 로그인을 하면 편리한 점이 많습니다.',
'tooltip-pt-logout'               => '로그아웃',
'tooltip-ca-talk'                 => '문서의 내용에 대한 토론 문서',
'tooltip-ca-edit'                 => '문서를 편집할 수 있습니다. 저장하기 전에 미리보기를 해 주세요.',
'tooltip-ca-addsection'           => '내용 추가하기',
'tooltip-ca-viewsource'           => '문서가 잠겨 있습니다. 문서의 소스만 볼 수 있습니다.',
'tooltip-ca-history'              => '문서의 과거 버전들',
'tooltip-ca-protect'              => '문서 보호하기',
'tooltip-ca-delete'               => '문서 삭제하기',
'tooltip-ca-undelete'             => '삭제된 문서 복구하기',
'tooltip-ca-move'                 => '문서 이동하기',
'tooltip-ca-watch'                => '이 문서를 주시문서 목록에 추가합니다.',
'tooltip-ca-unwatch'              => '이 문서를 주시문서 목록에서 제거합니다.',
'tooltip-search'                  => '{{SITENAME}} 찾기',
'tooltip-p-logo'                  => '대문',
'tooltip-n-mainpage'              => '대문으로',
'tooltip-n-portal'                => '이 프로젝트에 대해',
'tooltip-n-currentevents'         => '최근의 소식을 봅니다.',
'tooltip-n-recentchanges'         => '이 위키에서 최근 바뀐 점의 목록입니다.',
'tooltip-n-randompage'            => '임의 문서로 갑니다.',
'tooltip-n-help'                  => '도움말',
'tooltip-n-sitesupport'           => '지원을 기다립니다.',
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
'tooltip-ca-nstab-image'          => '그림 문서 내용을 봅니다.',
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

# Stylesheets
'common.css'   => '/** 이 CSS 설정은 모든 스킨에 적용됩니다 */',
'monobook.css' => '/* 이 CSS 설정은 모든 모노북 스킨에 적용됩니다 */',

# Scripts
'common.js'   => '/* 이 자바스크립트 설정은 모든 문서, 모든 사용자에게 적용됩니다. */',
'monobook.js' => '/* 현재는 사용하지 않습니다. 대신 [[MediaWiki:common.js]]를 사용해주세요. */',

# Metadata
'nodublincore'      => '더블린 코어 RDF 메타데이터 기능은 비활성되어 있습니다.',
'nocreativecommons' => '크리에이티브 커먼즈 RDF 메타데이터 기능은 비활성되어 있습니다.',

# Attribution
'anonymous'        => '{{SITENAME}}의 익명 사용자',
'siteuser'         => '{{SITENAME}} 사용자 $1',
'lastmodifiedatby' => '이 문서는 $3에 의해 $2, $1에 마지막으로 바뀌었습니다.', # $1 date, $2 time, $3 user
'and'              => '그리고',
'othercontribs'    => '$1의 작업을 바탕으로 함.',
'others'           => '기타',
'siteusers'        => '{{SITENAME}} 사용자 $1',
'creditspage'      => '문서 기여자들',
'nocredits'        => '이 문서에서는 기여자 정보가 없습니다.',

# Spam protection
'spamprotectiontitle'    => '스팸 방지 필터',
'spamprotectiontext'     => '스팸 필터가 문서 저장을 막았습니다. 다른 사이트로 연결하는 링크 중에 문제가 되는 사이트가 있을 것입니다.',
'spamprotectionmatch'    => '문제가 되는 부분은 다음과 같습니다: $1',
'subcategorycount'       => '이 분류에 $1개의 하위 분류가 있습니다.',
'categoryarticlecount'   => '이 분류에 $1개의 문서가 있습니다.',
'category-media-count'   => '이 분류에 $1개의 자료가 있습니다.',
'listingcontinuesabbrev' => ' (계속)',
'spambot_username'       => 'MediaWiki 스팸 제거',
'spam_reverting'         => '$1을 포함하지 않는 최신 버전으로 되돌림',
'spam_blanking'          => '모든 버전에 $1 링크를 포함하고 있어 문서를 비움',

# Info page
'infosubtitle'   => '문서 정보',
'numedits'       => '편집 횟수(문서): $1',
'numtalkedits'   => '편집 횟수(토론 문서): $1',
'numwatchers'    => '주시하는 사용자 수: $1',
'numauthors'     => '기여한 사용자 수(문서): $1',
'numtalkauthors' => '기여한 사용자 수(토론 문서): $1',

# Math options
'mw_math_png'    => '항상 PNG로 표시',
'mw_math_simple' => '아주 간단한 것은 HTML로, 나머지는 PNG로',
'mw_math_html'   => '가능한 한 HTML로, 나머지는 PNG로',
'mw_math_source' => 'TeX로 남겨둠 (텍스트 브라우저용)',
'mw_math_modern' => '최신 웹 브라우저인 경우 추천',
'mw_math_mathml' => '가능하면 MathML로 (시험판)',

# Patrolling
'markaspatrolleddiff'                 => '검토된 것으로 표시',
'markaspatrolledtext'                 => '이 문서를 검토된 것으로 표시',
'markedaspatrolled'                   => '검토된 것으로 표시',
'markedaspatrolledtext'               => '선택한 버전이 검토된 것으로 표시되었습니다.',
'rcpatroldisabled'                    => '최근 바뀜 검토 기능 비활성화됨',
'rcpatroldisabledtext'                => '최근 바뀜 검토 기능은 현재 비활성화되어 있습니다.',
'markedaspatrollederror'              => '검토된 것으로 표시할 수 없습니다.',
'markedaspatrollederrortext'          => '검토된 것으로 표시할 버전을 지정해야 합니다.',
'markedaspatrollederror-noautopatrol' => '자신의 편집을 스스로 검토된 것으로 표시하는 것은 허용되지 않습니다.',

# Patrol log
'patrol-log-page' => '검토 기록',

# Image deletion
'deletedrevision'       => '예전 버전 $1이(가) 삭제되었습니다.',
'filedeleteerror-short' => '파일 삭제 오류: $1',

# Browsing diffs
'previousdiff' => '← 이전 비교',
'nextdiff'     => '다음 비교 →',

# Media information
'mediawarning'    => "'''경고''': 이 파일에는 시스템을 위험하게 만드는 악성 코드가 들어있을 수 있습니다.<br />",
'imagemaxsize'    => '그림 설명 문서에 그림 크기를 다음으로 제한:',
'thumbsize'       => '섬네일 크기:',
'widthheightpage' => '$1×$2, $3페이지',
'file-info'       => '(파일 크기: $1, MIME 종류: $2)',
'file-info-size'  => '($1 × $2 픽셀, 파일 크기: $3, MIME 종류: $4)',

# Special:Newimages
'newimages'    => '새 그림 파일 목록',
'showhidebots' => '(봇을 $1)',
'noimages'     => '그림이 없습니다.',

# Metadata
'metadata'          => '메타데이터',
'metadata-help'     => '이 파일은 카메라/스캐너 정보와 같은 부가적인 정보를 담고 있습니다. 파일을 변경한다면 몇몇 세부 사항은 변경된 그림에 적용되지 않을 수 있습니다.',
'metadata-expand'   => '자세한 정보 보이기',
'metadata-collapse' => '자세한 정보 숨기기',

# External editor support
'edit-externally'      => '이 파일을 외부 프로그램을 사용해서 편집하기',
'edit-externally-help' => '[http://meta.wikimedia.org/wiki/Help:External_editors 여기]에서 외부 편집기에 대한 정보를 얻을 수 있습니다.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '모두',
'imagelistall'     => '모두',
'watchlistall2'    => '모두',
'namespacesall'    => '모두',

# E-mail address confirmation
'confirmemail'            => '이메일 주소 확인',
'confirmemail_text'       => '이 위키에서는 이메일과 관련된 기능을 사용하기 전에, 이메일 인증을 받아야 합니다. 아래의 버튼을 눌러 인증 메일을 보내세요. 메일에는 인증 코드를 포함한 링크가 들어 있을 것입니다. 링크를 브라우저에서 열면 인증이 됩니다.',
'confirmemail_send'       => '인증 코드를 메일로 보내기',
'confirmemail_sent'       => '인증 이메일을 보냈습니다.',
'confirmemail_sendfailed' => '인증 이메일을 보낼 수 없습니다. 잘못된 주소인지 확인해 주십시오.

메일 서버로부터의 응답: $1',
'confirmemail_invalid'    => '인증 코드가 올바르지 않습니다. 코드가 소멸되었을 수도 있습니다.',
'confirmemail_needlogin'  => '이메일 주소를 인증하려면 $1이 필요합니다.',
'confirmemail_success'    => '당신의 이메일 주소가 인증되었습니다. 이제 로그인해서 위키를 사용하세요.',
'confirmemail_loggedin'   => '당신의 이메일 주소는 이제 인증되었습니다.',
'confirmemail_error'      => '당신의 인증을 저장하는 도중 오류가 발생했습니다.',
'confirmemail_subject'    => '{{SITENAME}} 이메일 주소 인증',
'confirmemail_body'       => '$1 아이피 주소를 사용하는 사용자가 "$2" 계정의 이메일 인증 신청을 했습니다.

만약 이 계정이 당신의 계정이고 이메일 인증을 하려면, 다음 주소를 열어 주세요:

$3

만약 당신의 계정이 아니라면, 문서를 열지 마세요. 인증 코드는 $4에 만료됩니다.',

# Scary transclusion
'scarytranscludedisabled' => '[인터위키가 비활성되어 있습니다]',
'scarytranscludefailed'   => '[$1 틀을 불러오는 데에 실패했습니다]',
'scarytranscludetoolong'  => '[URL이 너무 깁니다]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">이 문서에 달린 트랙백:<br />$1</div>',
'trackbackremove'   => ' ([$1 삭제])',
'trackbacklink'     => '트랙백',
'trackbackdeleteok' => '트랙백이 삭제되었습니다.',

# Delete conflict
'deletedwhileediting' => '주의: 당신이 이 문서를 편집하던 중에 이 문서가 삭제되었습니다.',
'confirmrecreate'     => '[[User:$1|$1]]([[User talk:$1|토론]]) 사용자가 당신의 편집 도중 문서를 지웠습니다. 삭제 이유는 다음과 같습니다:
: $2
문서를 다시 되살릴 필요가 있는지를 확인해주세요.',
'recreate'            => '새로 만들기',

# HTML dump
'redirectingto' => '[[$1]]로 넘어가는 중...',

# action=purge
'confirm_purge'        => '문서의 캐시를 지울까요?

$1',
'confirm_purge_button' => '확인',

# AJAX search
'searchcontaining' => '"$1"이 포함된 글을 검색합니다.',
'searchnamed'      => '"$1" 이름을 가진 문서를 검색합니다.',
'articletitles'    => "''$1''로 시작하는 문서들",
'hideresults'      => '결과 숨기기',

# Multipage image navigation
'imgmultipageprev' => '← 이전 문서',
'imgmultipagenext' => '다음 문서 →',

# Table pager
'table_pager_next'         => '다음 문서',
'table_pager_prev'         => '이전 문서',
'table_pager_first'        => '처음 문서',
'table_pager_last'         => '마지막 문서',
'table_pager_limit'        => '문서당 $1개 항목 보이기',
'table_pager_limit_submit' => '확인',
'table_pager_empty'        => '결과 없음',

# Auto-summaries
'autosumm-blank'   => '문서의 모든 내용을 삭제',
'autosumm-replace' => '문서 내용을 ‘$1’으로 교체',
'autoredircomment' => '[[$1]](으)로 넘겨주기',
'autosumm-new'     => '새 문서: $1',

);
