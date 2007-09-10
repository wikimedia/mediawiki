/**
 * Obtained from http://homepage3.nifty.com/aokura/jscript/index.html
 * The webpage says, among other things:
 *    * ソースコードの全てあるいは一部を使用したことにより生じた損害に関しては一切責任を負いません。
 *    * ソースコードの使用、配布に制限はありません。ご自由にお使いください。
 *    * 動作チェックが不充分な場合もありますので、注意してください。
 * 
 * Which, loosely translated, means:
 *    * The author takes no responsibility for damage which occurs due to the use of this code.
 *    * There is no restriction on the use and distribution of the source code. Please use freely.
 *    * Please be careful, testing may have been insufficient.
 */


/**********************************************************************
 *
 *  Unicode ⇔ UTF-8
 *
 *  Copyright (c) 2005 AOK <soft@aokura.com>
 *
 **********************************************************************/

function _to_utf8(s) {
  var c, d = "";
  for (var i = 0; i < s.length; i++) {
    c = s.charCodeAt(i);
    if (c <= 0x7f) {
      d += s.charAt(i);
    } else if (c >= 0x80 && c <= 0x7ff) {
      d += String.fromCharCode(((c >> 6) & 0x1f) | 0xc0);
      d += String.fromCharCode((c & 0x3f) | 0x80);
    } else {
      d += String.fromCharCode((c >> 12) | 0xe0);
      d += String.fromCharCode(((c >> 6) & 0x3f) | 0x80);
      d += String.fromCharCode((c & 0x3f) | 0x80);
    }
  }
  return d;
}

function _from_utf8(s) {
  var c, d = "", flag = 0, tmp;
  for (var i = 0; i < s.length; i++) {
    c = s.charCodeAt(i);
    if (flag == 0) {
      if ((c & 0xe0) == 0xe0) {
        flag = 2;
        tmp = (c & 0x0f) << 12;
      } else if ((c & 0xc0) == 0xc0) {
        flag = 1;
        tmp = (c & 0x1f) << 6;
      } else if ((c & 0x80) == 0) {
        d += s.charAt(i);
      } else {
        flag = 0;
      }
    } else if (flag == 1) {
      flag = 0;
      d += String.fromCharCode(tmp | (c & 0x3f));
    } else if (flag == 2) {
      flag = 3;
      tmp |= (c & 0x3f) << 6;
    } else if (flag == 3) {
      flag = 0;
      d += String.fromCharCode(tmp | (c & 0x3f));
    } else {
      flag = 0;
    }
  }
  return d;
}

