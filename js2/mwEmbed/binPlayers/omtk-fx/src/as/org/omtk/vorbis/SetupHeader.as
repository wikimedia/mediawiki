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

	import org.omtk.util.BitByteArray;

	public class SetupHeader {
	
		private var _codeBooks:Vector.<CodeBook>;
		private var _floors:Vector.<Floor>;
		private var _residues:Vector.<Residue>;
		private var _mappings:Vector.<Mapping>;
		private var _modes:Vector.<Mode>;
		
		public function SetupHeader(stream:VorbisStream, source:BitByteArray) {

			var i:int;
		
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
			source.readByte();
	
			var codeBookCount:uint = source.readUnsignedBitwiseInt(8)+1;
			_codeBooks = new Vector.<CodeBook>(codeBookCount);
			
			for(i = 0; i < codeBookCount; i++) {
				_codeBooks[i] = new CodeBook(source);
			}
			
			// read the time domain transformations,
			// these should all be 0
	
			var timeCount:int = source.readUnsignedBitwiseInt(6) + 1;
			for (i = 0; i < timeCount; i++) {
				if (source.readUnsignedBitwiseInt(16) != 0) {
					throw new Error(
							"Time domain transformation != 0");
				}
			}
			
			// read floor entries
	
			var floorCount:int = source.readUnsignedBitwiseInt(6) + 1;
			_floors = new Vector.<Floor>(floorCount);
	
			for (i = 0; i < floorCount; i++) {
				_floors[i] = Floor.createInstance(source, this);
			}

			var residueCount:int = source.readUnsignedBitwiseInt(6) + 1;
			_residues = new Vector.<Residue>(residueCount);

			for (i = 0; i < residueCount; i++) {
				_residues[i] = Residue.createInstance(source, this);
			}
			
			var mappingCount:int = source.readUnsignedBitwiseInt(6) + 1;
			_mappings = new Vector.<Mapping>(mappingCount);
	
			for (i = 0; i < mappingCount; i++) {
				_mappings[i] = Mapping.createInstance(stream, source, this);
			}
			
			var modeCount:int = source.readUnsignedBitwiseInt(6) + 1;
			_modes = new Vector.<Mode>(modeCount);
	
			for (i = 0; i < modeCount; i++) {
				_modes[i] = new Mode(source, this);
			}
	
			if (!source.readBit()) {
				throw new Error("The setup header framing bit is incorrect.");
			}
		}
	
		public function get codeBooks():Vector.<CodeBook> {
			return _codeBooks;
		}
	
		public function get floors():Vector.<Floor> {
			return _floors;
		}

		public function get mappings():Vector.<Mapping> {
			return _mappings;
		}
	
		public function get residues():Vector.<Residue> {
			return _residues;
		}

		public function get modes():Vector.<Mode> {
			return _modes;
		}
		
	}


}