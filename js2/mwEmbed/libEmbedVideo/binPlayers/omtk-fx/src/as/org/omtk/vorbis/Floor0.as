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
	import org.omtk.util.BitByteArray;
	
	public class Floor0 extends Floor {

		private var order:int;
		private var rate:int;
		private var barkMapSize:int;
		private var amplitudeBits:int;
		private var amplitudeOffset:int;
		
		private var bookList:Vector.<int>;
		
		public function Floor0(source:BitByteArray, header:SetupHeader) {

			order = source.readUnsignedBitwiseInt(8);
			rate = source.readUnsignedBitwiseInt(16);
			barkMapSize = source.readUnsignedBitwiseInt(16);
			amplitudeBits = source.readUnsignedBitwiseInt(6);
			amplitudeOffset = source.readUnsignedBitwiseInt(8);
	
			var bookCount:uint = source.readUnsignedBitwiseInt(4) + 1;
			bookList = new Vector.<int>(bookCount);
	
			var i:int;
	
			for (i = 0; i < bookList.length; i++) {
				bookList[i] = source.readUnsignedBitwiseInt(8);
				if (bookList[i] > header.codeBooks.length) {
					throw new Error("A floor0_book_list entry is higher than the code book count.");
				}
			}
		}

	}
}