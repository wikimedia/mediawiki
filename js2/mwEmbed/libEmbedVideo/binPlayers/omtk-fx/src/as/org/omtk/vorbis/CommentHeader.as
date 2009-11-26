/*

Copyright 2008 Tor-Einar Jarnbjo

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/

package org.omtk.vorbis {

	import flash.utils.ByteArray;
	import flash.utils.Endian;
	import flash.utils.Dictionary;
	import org.omtk.ogg.*;

	public class CommentHeader {
	
		private var _vendor:String;
		private var _comments:Dictionary = new Dictionary();
	
		public function CommentHeader(source:ByteArray)
		{
			init( source );
		}
		
		private function init( source: ByteArray ): void
		{
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();

			_vendor = readUtf8String(source);
			
			var ucLength:int = source.readUnsignedInt();
			
			for(var i:int = 0; i < ucLength; i++) {
				var comment:String = readUtf8String(source);
				var ix:int = comment.indexOf('=');
				var key:String = comment.substring(0, ix);
				var value:String = comment.substring(ix+1);
				_comments[key.toUpperCase()]=value;
			}
		}	
	
		private function readUtf8String(source:ByteArray):String {
			var length:uint = source.readUnsignedInt();
			return source.readUTFBytes(length);
		}
	
		public function get vendor():String {
			return _vendor;
		}
		
		public function get comments():Dictionary {
			return _comments;
		}
	
		public function get artist():String {
			return _comments["ARTIST"];
		}
		
		public function get title():String {
			return _comments["TITLE"];
		}
	
	}

}