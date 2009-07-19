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

package org.omtk.ogg {

	import flash.net.*;
	import flash.utils.ByteArray;
	
	public class OggPage {

		private var _version:int;
		private var _continued:Boolean;
		private var _bos:Boolean;
		private var _eos:Boolean;
		private var _absoluteGranulePosition:int;
		private var _streamSerialNumber:int;
		private var _pageSequenceNumber:int;
		private var _pageCheckSum:int;
		private var _segmentOffsets:Array;
		private var _segmentLengths:Array;
		private var _totalLength:int;
		private var _data:ByteArray;
			
		public function OggPage(stream:URLStream) {

			var capture:int = stream.readInt();
			if(capture != 0x5367674f) {
				throw new Error("Ogg page does not start with 'OggS': "+capture);
			}
		
			_version = stream.readByte()&0xff;
			var tmp:int = stream.readByte();
		
			_continued = (tmp&1)!=0;;
			_bos = (tmp&2)!=0;
			_eos = (tmp&4)!=0;
			
			// absoluteGranulePosition is really 64 bits
			_absoluteGranulePosition = stream.readUnsignedInt();
			stream.readUnsignedInt(); // last 32 bits of _absoluteGranulePosition
			
			_streamSerialNumber = stream.readUnsignedInt();
			_pageSequenceNumber = stream.readUnsignedInt();
			_pageCheckSum = stream.readUnsignedInt();
			
			//trace("_pageSequenceNumber: " + _pageSequenceNumber);
			
			var pageSegments:int = stream.readUnsignedByte();
			
			//stream.waitFor(pageSegments);
			
			_segmentOffsets = [];
			_segmentLengths = [];
			_data = new ByteArray();
			
			var totalLength:int;
			
			var i:int;
			var l:int;
			
			for( i= 0 ; i < pageSegments ; i++ ) {
				l = stream.readUnsignedByte();
				_segmentLengths.push( l );
				_segmentOffsets.push( totalLength );
				totalLength += l;
			}	
			
			stream.readBytes(_data, 0, totalLength);
		}
	
		public function get absoluteGranulePosition():int {
			return _absoluteGranulePosition;
		}

		public function get segmentOffsets():Array {
			return _segmentOffsets;
		}
		
		public function get segmentLengths():Array {
			return _segmentLengths;
		}
		
		public function get data():ByteArray {
			return _data;
		}

		public function get eos():Boolean {
			return _eos;
		}
		
		public function get bos():Boolean {
			return _bos;
		}
		
		public function get continued():Boolean {
			return _continued;
		}
		
	}

}