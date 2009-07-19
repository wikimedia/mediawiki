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

	import flash.errors.IllegalOperationError;
	import flash.utils.Dictionary;
	import org.omtk.util.BitByteArray;
	
	public class Residue {

		private var _begin:int;
		private var _end:int;
		private var _partitionSize:int;
		private var _classifications:int;
		private var _classBook:int;
		private var _cascade:Vector.<int>;
		private var _books:Vector.<Vector.<int>>;
		
		private var _looks:Dictionary;

	
		public function Residue(source:BitByteArray, header:SetupHeader) {

			_begin = source.readUnsignedBitwiseInt(24);
			_end = source.readUnsignedBitwiseInt(24);
			_partitionSize = source.readUnsignedBitwiseInt(24) + 1;
			_classifications = source.readUnsignedBitwiseInt(6) + 1;
			_classBook = source.readUnsignedBitwiseInt(8);
	
			_cascade = new Vector.<int>(classifications);
	
			var acc:int = 0;
			var i:int;
			var j:int;
			
			for (i = 0; i < classifications; i++) {
				var highBits:int = 0;
				var lowBits:int = 0;
				
				lowBits = source.readUnsignedBitwiseInt(3);
				if (source.readBit()) {
					highBits = source.readUnsignedBitwiseInt(5);
				}
				_cascade[i] = (highBits << 3) | lowBits;
				acc += Util.icount(cascade[i]);
			}
	
			_books = new Vector.<Vector.<int>>(classifications);
	
			for (i = 0; i < classifications; i++) {
				books[i] = new Vector.<int>(8);
				for (j = 0; j < 8; j++) {
					if ((cascade[i] & (1 << j)) != 0) {
						books[i][j] = source.readUnsignedBitwiseInt(8);
						if (books[i][j] > header.codeBooks.length) {
							throw new Error(
									"Reference to invalid codebook entry in residue header.");
						}
					}
				}
			}
			
			_looks = new Dictionary();
		}
	
		public static function createInstance(source:BitByteArray, header:SetupHeader):Residue {
	
			var type:int = source.readUnsignedBitwiseInt(16);
			switch (type) {
			case 2:
				return new Residue2(source, header);
			default:
				throw new Error("Residue type " + type + " is not supported.");
			}
		}
		
		public function decodeResidue(
			vorbis:VorbisStream, source:BitByteArray, 
			mode:Mode, ch:int,
			doNotDecodeFlags:Vector.<Boolean>, vectors0:Vector.<Number>, vectors1:Vector.<Number>):void {
				
			throw new IllegalOperationError("not implemented");
		}
		
		
		public function getLook(stream:VorbisStream, key:Mode):Look {
			var look:Look = _looks[key];
			if (look == null) {
				look = new Look(stream, this, key);
				_looks[key] = look;
			}
			return new Look(stream, this, key);//look;
		}
	
	
		public function get begin():int {
			return _begin;
		}
		
		public function get end():int {
			return _end;
		}
		
		public function get partitionSize():int {
			return _partitionSize;
		}
		
		public function get classifications():int {
			return _classifications;
		}
		
		public function get classBook():int {
			return _classBook;
		}
		
		public function get cascade():Vector.<int> {
			return _cascade;
		}
		
		public function get books():Vector.<Vector.<int>> {
			return _books;
		}
			
	}
	
}