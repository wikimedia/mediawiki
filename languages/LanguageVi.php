<?php
/**
  * Based on Language.php 1.645
  * @package MediaWiki
  * @subpackage Language
  * Compatible to MediaWiki 1.5
  * Initial translation by Trần Thế Trung and Nguyễn Thanh Quang
  * Last update 28 August 2005 (UTC)
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesVi = array(
	NS_MEDIA			=> 'Phương_tiện',
	NS_SPECIAL			=> 'Đặc_biệt',
	NS_MAIN				=> '',
	NS_TALK				=> 'Thảo_luận',
	NS_USER				=> 'Thành_viên',
	NS_USER_TALK		=> 'Thảo_luận_Thành_viên',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Thảo_luận_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Hình',
	NS_IMAGE_TALK		=> 'Thảo_luận_Hình',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Thảo_luận_MediaWiki',
	NS_TEMPLATE			=> 'Tiêu_bản',
	NS_TEMPLATE_TALK	=> 'Thảo_luận_Tiêu_bản',
	NS_HELP				=> 'Trợ_giúp',
	NS_HELP_TALK		=> 'Thảo_luận_Trợ_giúp',
	NS_CATEGORY			=> 'Thể_loại',
	NS_CATEGORY_TALK	=> 'Thảo_luận_Thể_loại'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsVi = array(
	'Không', 'Trái', 'Phải', 'Nổi bên trái'
);

/* private */ $wgSkinNamesVi = array(
	'standard'		=> 'Cổ điển',
	'nostalgia'		=> 'Vọng cổ',
	'myskin'		=> 'Cá nhân'
) + $wgSkinNamesEn;

 /* private */ $wgMagicWordsVi = array(
	MAG_REDIRECT             => array( 0,    '#redirect' , '#đổi'             ),
	MAG_NOTOC                => array( 0,    '__NOTOC__' , '__KHÔNGMỤCMỤC__'             ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__', '__LUÔNMỤCLỤC__'        ),
	MAG_TOC                  => array( 0,    '__TOC__' , '__MỤCLỤC__'               ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__', '__KHÔNGSỬAMỤC__'      ),
	MAG_START                => array( 0,    '__START__' , '__BẮTĐẦU__'             ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH' , 'THÁNGNÀY'          ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'  , 'TÊNTHÁNGNÀY'     ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN' , 'TÊNDÀITHÁNGNÀY'   ),
	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV'  , 'TÊNNGẮNTHÁNGNÀY'  ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'       , 'NGÀYNÀY'     ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'   , 'TÊNNGÀYNÀY'      ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'    , 'NĂMNÀY'        ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'     , 'GIỜNÀY'       ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'  , 'SỐBÀI'     ),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES'   , 'SỐTẬPTIN'       ),
	MAG_PAGENAME             => array( 1,    'PAGENAME'      , 'TÊNTRANG'        ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE'   , 'TÊNTRANG2'           ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'   , 'KHÔNGGIANTÊN'           ),
	MAG_MSG                  => array( 0,    'MSG:'     , 'NHẮN:'              ),
	MAG_SUBST                => array( 0,    'SUBST:'   ,  'THẾ:'            ),
	MAG_MSGNW                => array( 0,    'MSGNW:'    ,  'NHẮNMỚI:'             ),
	MAG_END                  => array( 0,    '__END__'    , '__KẾT__'            ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb' , 'nhỏ'    ),
	MAG_IMG_RIGHT            => array( 1,    'right' , 'phải'                 ),
	MAG_IMG_LEFT             => array( 1,    'left'  , 'trái'                ),
	MAG_IMG_NONE             => array( 1,    'none'  , 'không'                 ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'                   ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre' , 'giữa'      ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' , 'khung'),
	MAG_INT                  => array( 0,    'INT:'                   ),
	MAG_SITENAME             => array( 1,    'SITENAME'  , 'TÊNMẠNG'             ),
	MAG_NS                   => array( 0,    'NS:'                    ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'              ),
	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'    , 'MÁYCHỦ'             ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME' , 'TÊNMÁYCHỦ'            ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH'  , ''           ),
	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'   , 'NGỮPHÁP'            ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__',
'__NOTC__', '__KHÔNGCHUYỂNTÊN__'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__',
'__NOCC__', '__KHÔNGCHUYỂNNỘIDUNG__'),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK' , 'TUẦNNÀY'           ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1,    'REVISIONID'  , 'SỐBẢN'           ),
 );

/* private */ $wgDateFormatsVi = array(
    MW_DATE_DEFAULT => 'Không lựa chọn',
    1 => '16:12, tháng 1 ngày 15 năm 2001',
    2 => '16:12, ngày 15 tháng 1 năm 2001',
    3 => '16:12, năm 2001 tháng 1 ngày 15',
    4 => '',
    MW_DATE_ISO => '2001-01-15 16:12:34'
);
global $wgRightsText;

if (!$wgCachedMessageArrays) {
	require_once('MessagesVi.php');
}


class LanguageVi extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListVi ;
		return $wgBookstoreListVi ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesVi;
		return $wgNamespaceNamesVi;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsVi;
		return $wgQuickbarSettingsVi;
	}

	function getSkinNames() {
		global $wgSkinNamesVi;
		return $wgSkinNamesVi;
	}

	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$datePreference = $this->dateFormat( $format );

		$month = $this->formatMonth( substr( $ts, 4, 2 ), $datePreference );
		$day = $this->formatDay( substr( $ts, 6, 2 ), $datePreference );
		$year = $this->formatNum( substr( $ts, 0, 4 ), true );

		switch( $datePreference ) {
			case 3:
			case 4: return "$day/$month/$year";
			case MW_DATE_ISO: return substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .substr($ts, 6, 2);
			default: return "$day $month năm $year";
		}
	}

	function timeSeparator( $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '4': return 'h';
				default:  return ':';
			}
	}

	function timeDateSeparator( $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1':
				case '2': return ', ';
				default:  return ' ';
			}
	}

	function formatMonth( $month, $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1': return 'tháng ' . ( 0 + $month );
				case '2': return 'tháng ' . $this->getSpecialMonthName( $month );
				default:  return 0 + $month;
			}
	}

	function formatDay( $day, $format ) {
		$datePreference = $this->dateFormat($format);
			switch ( $datePreference ) {
				case '0':
				case '1':
				case '2': return 'ngày ' . (0 + $day);
				default:  return 0 + $day;
			}
	}

	function getSpecialMonthName( $key ) {
		$names = 'Một, Hai, Ba, Tư, Năm, Sáu, Bảy, Tám, Chín, Mười, Mười một, Mười hai';
		$names = explode(', ', $names);
		return $names[$key-1];
	}

	function getDateFormats() {
		global $wgDateFormatsVi;
		return $wgDateFormatsVi;
	}

	function &getMagicWords() {
		global $wgMagicWordsVi;
		return $wgMagicWordsVi;
	}

	function separatorTransformTable() {
		return array(',' => '.', '.' => ',' );
	}

	function getMessage( $key ) {
		global $wgAllMessagesVi;
		if( isset( $wgAllMessagesVi[$key] ) ) {
			return $wgAllMessagesVi[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
