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
	
	public class Look {
	
		private var _map:int;
		private var _parts:int;
		private var _stages:int;
		private var _fullbooks:Vector.<CodeBook>;
		private var _phrasebook:CodeBook;
		private var _partbooks:Vector.<Vector.<int>>;
		private var _partvals:int;
		private var _decodemap:Vector.<Vector.<int>>;
		private var _postbits:int;
		private var _phrasebits:int;
		private var _frames:int;

		public function Look(source:VorbisStream, residue:Residue, mode:Mode) {
		
			var i:int;
			var j:int;
			var k:int;
			
			var dim:int = 0;
			var acc:int = 0;
			var maxstage:int = 0;

			_map = mode.mapping;
			
			_parts = residue.classifications;
			_fullbooks = source.setupHeader.codeBooks;
			_phrasebook = _fullbooks[residue.classBook];
			dim = _phrasebook.dimensions;

			_partbooks = new Vector.<Vector.<int>>(_parts);

			for (j = 0; j < _parts; j++) {
				var s:int = Util.ilog(residue.cascade[j]);
				if (s != 0) {
					if (s > maxstage) {
						maxstage = s;
					}
					_partbooks[j] = new Vector.<int>(s);
					for (k = 0; k < s; k++) {
						if ((residue.cascade[j] & (1 << k)) != 0) {
							_partbooks[j][k] = residue.books[j][k];
						}
					}
				}
			}

			_partvals = Math.round(Math.pow(_parts, dim));
			_stages = maxstage;

			_decodemap = new Vector.<Vector.<int>>(_partvals, true);

			for (j = 0; j < _partvals; j++) {
				var val:int = j;
				var mult:int = _partvals / _parts;
				_decodemap[j] = new Vector.<int>(dim);

				for (k = 0; k < dim; k++) {
					var deco:int = val / mult;
					val -= deco * mult;
					mult /= _parts;
					_decodemap[j][k] = deco;
				}
			}
		}

		public function get map():int {
			return _map;
		}
		
		public function get parts():int {
			return _parts;
		}
		
		public function get stages():int {
			return _stages;
		}
		
		public function get fullbooks():Vector.<CodeBook> {
			return _fullbooks;
		}
		
		public function get phrasebook():CodeBook {
			return _phrasebook;
		}
		
		public function get partbooks():Vector.<Vector.<int>> {
			return _partbooks;
		}
		
		public function get partvals():int {
			return _partvals;
		}
		
		public function get decodemap():Vector.<Vector.<int>> {
			return _decodemap;
		}
		
		public function get postbits():int {
			return _postbits;
		}
			
		public function get phrasebits():int {
			return _phrasebits;
		}
		
		public function get frames():int {
			return _frames;
		}
			
	}
	
}