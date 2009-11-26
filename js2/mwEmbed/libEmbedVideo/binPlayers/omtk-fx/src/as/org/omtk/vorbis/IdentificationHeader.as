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
	
	public class IdentificationHeader {
	
		private var _version:uint;
		private var _channels:uint;
		private var _sampleRate:uint;
		private var _bitrateMaximum:int;
		private var _bitrateMinimum:int;
		private var _bitrateNominal:int;
		private var _blockSize0:uint;
		private var _blockSize1:uint;
	
		private var _mdct:Vector.<Mdct>;

		public function IdentificationHeader(source:ByteArray) {

			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
	
			_version = source.readUnsignedInt();
			_channels = source.readUnsignedByte();
			_sampleRate = source.readUnsignedInt();
			_bitrateMaximum = source.readUnsignedInt();
			_bitrateNominal = source.readUnsignedInt();
			_bitrateMinimum = source.readUnsignedInt();
			var bs:int = source.readUnsignedByte();
			_blockSize0 = 1<<(bs&0xf);
			_blockSize1 = 1<<(bs>>4);
		
			_mdct = new Vector.<Mdct>(2, false);
			_mdct[0] = new Mdct(_blockSize0);
			_mdct[1] = new Mdct(_blockSize1);
		}
		
		public function get channels():uint {
			return _channels;
		}
		
		public function get sampleRate():uint {
			return _sampleRate;
		}
		
		public function get blockSize0():uint {
			return _blockSize0;
		}
		
		public function get blockSize1():uint {
			return _blockSize1;
		}
		
		public function get mdct0():Mdct {
			return _mdct[0];
		}
	
		public function get mdct1():Mdct {
			return _mdct[1];
		}

	}


}