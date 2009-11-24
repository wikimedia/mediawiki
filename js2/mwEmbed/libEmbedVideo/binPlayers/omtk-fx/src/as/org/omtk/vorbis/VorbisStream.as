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

	import flash.events.SampleDataEvent;
	import flash.utils.ByteArray;
	import flash.utils.Endian;
	import org.omtk.util.*;
	import org.omtk.ogg.*;
	
	public class VorbisStream {
	
		private const IDENTIFICATION_HEADER:int = 1;
		private const COMMENT_HEADER:int = 3;
		private const SETUP_HEADER:int = 5;
		
		private var source:LogicalOggStream;
	
		private var _identificationHeader:IdentificationHeader;
		private var _commentHeader:CommentHeader;
		private var _setupHeader:SetupHeader;
		
		private var _lastAudioPacket:AudioPacket;
		
		private var _currentGranulePosition:int=0;
		private var packetCounter:int;
		
		private var _finished: Boolean = false;
		
		public var windows:Vector.<Vector.<Number>> = new Vector.<Vector.<Number>>(8);

		public function VorbisStream(source:LogicalOggStream) {

			this.source = source;

			for (var i:int = 0; i < 3; i++) {

				var data:BitByteArray = source.getNextOggPacket().data;
				var headerType:int = data.readUnsignedByte();

				switch(headerType) {
				case IDENTIFICATION_HEADER:
					_identificationHeader = new IdentificationHeader(data);
					break;
				case COMMENT_HEADER:
					_commentHeader = new CommentHeader(data);
					break;
				case SETUP_HEADER:
					_setupHeader = new SetupHeader(this, data);
					break;
				}
			}
			
		}
	
		public function get identificationHeader():IdentificationHeader {
			return _identificationHeader;
		}
		
		public function get commentHeader():CommentHeader {
			return _commentHeader;
		}
		
		public function get setupHeader():SetupHeader {
			return _setupHeader;
		}
	
		public function readPcm(data:ByteArray): int {
		
			var total:int;
			var i:int;
			
			if(_lastAudioPacket == null) {
				_lastAudioPacket = getNextAudioPacket();
			}

			total = 0;
			
			while(total < 2048 && !_finished) {
				try {
					var ap:AudioPacket = getNextAudioPacket();
					total += ap.readPcm(_lastAudioPacket, data);
		 			_lastAudioPacket = ap;
		 		}
		 		catch(e: EndOfOggStreamError) {
		 			// ok, stream finished
		 			_finished = true;
		 		}
			}
						
			return total;
		}

		private function getNextAudioPacket():AudioPacket {
			packetCounter++;
			var packet:OggPacket = source.getNextOggPacket();
			var res:AudioPacket = new AudioPacket(this, packet, _currentGranulePosition);
			if(_lastAudioPacket != null) {
				// don't count the first packet, since it doesn't contain any "real" samples
				_currentGranulePosition += res.numberOfSamples;
			}
			return res;
		}	
		
		public function get finished() : Boolean {
			return _finished;
		}
		
		public function get currentGranulePosition(): int {
			return _currentGranulePosition;
		}
	}
	
}