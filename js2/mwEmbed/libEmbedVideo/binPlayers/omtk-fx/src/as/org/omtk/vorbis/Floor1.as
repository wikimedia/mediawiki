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
	import org.omtk.util.*;
	
	public class Floor1 extends Floor {

		private var partitionClassList:Vector.<int>;
		private var maximumClass:int;
		private var multiplier:int;
		private var rangeBits:int;
		private var classDimensions:Vector.<int>;
		private var classSubclasses:Vector.<int>;
		private var classMasterbooks:Vector.<int>;
		private var subclassBooks:Vector.<Vector.<int>>;
		private var xList:Vector.<int>;
		private var yList:Vector.<int>;
		private var lowNeighbours:Vector.<int>;
		private var highNeighbours:Vector.<int>;
		private static var RANGES:Vector.<int> = Vector.<int>([256, 128, 86, 64]);

		private var xList2:Vector.<int>;
		private var step2Flags:Vector.<Boolean>;
		
		public function Floor1(source:BitByteArray = null, header:SetupHeader = null) {

			if(source==null && header==null) {
				return;
			}
		
			var i:int;
			var j:int;
			
			maximumClass = -1;
			var partitions:int = source.readUnsignedBitwiseInt(5);
			partitionClassList = new Vector.<int>(partitions);
	
			for (i = 0; i < partitionClassList.length; i++) {
				partitionClassList[i] = source.readUnsignedBitwiseInt(4);
				if (partitionClassList[i] > maximumClass) {
					maximumClass = partitionClassList[i];
				}
			}
	
			classDimensions = new Vector.<int>(maximumClass + 1);
			classSubclasses = new Vector.<int>(maximumClass + 1);
			classMasterbooks = new Vector.<int>(maximumClass + 1);
			subclassBooks = new Vector.<Vector.<int>>(maximumClass + 1);
	
			var xListLength:int = 2;
	
			for (i = 0; i <= maximumClass; i++) {
				classDimensions[i] = source.readUnsignedBitwiseInt(3) + 1;
				xListLength += classDimensions[i];
				classSubclasses[i] = source.readUnsignedBitwiseInt(2);
	
				if (classDimensions[i] > header.codeBooks.length || classSubclasses[i] > header.codeBooks.length) {
					throw new Error("There is a class dimension or class subclasses entry higher than the number of codebooks in the setup header.");
				}
				if (classSubclasses[i] != 0) {
					classMasterbooks[i] = source.readUnsignedBitwiseInt(8);
				}
				subclassBooks[i] = new Vector.<int>(1 << classSubclasses[i]);
				for (j = 0; j < subclassBooks[i].length; j++) {
					subclassBooks[i][j] = source.readUnsignedBitwiseInt(8) - 1;
				}
			}
	
			multiplier = source.readUnsignedBitwiseInt(2) + 1;
			rangeBits = source.readUnsignedBitwiseInt(4);
	
			var floorValues:int = 0;
	
			xList = Vector.<int>([0, 1<<rangeBits]);
			
			for (i = 0; i < partitions; i++) {
				for (j = 0; j < classDimensions[partitionClassList[i]]; j++) {
					xList.push(source.readUnsignedBitwiseInt(rangeBits));
				}
			}
	
			lowNeighbours = new Vector.<int>(xList.length);
			highNeighbours = new Vector.<int>(xList.length);
	
			for (i = 0; i < xList.length; i++) {
				lowNeighbours[i] = Util.lowNeighbour(xList, i);
				highNeighbours[i] = Util.highNeighbour(xList, i);
			}
			
			xList2 = new Vector.<int>(xList.length, true);
			step2Flags = new Vector.<Boolean>(xList.length, true);
		}

		public override function get type():int {
			return 1;
		}

		public override function decodeFloor(vorbis:VorbisStream, source:BitByteArray):Floor1 {

			var i:int;
			var j:int;
			var offset:int;

			if (!source.readBit()) {
				return null;
			}
	
			var clone:Floor1 = clone();
	
			clone.yList = new Vector.<int>(xList.length);
	
			var range:int = RANGES[multiplier - 1];
	
			clone.yList[0] = source.readUnsignedBitwiseInt(Util.ilog(range - 1));
			clone.yList[1] = source.readUnsignedBitwiseInt(Util.ilog(range - 1));
	
			offset = 2;
	
			for (i = 0; i < partitionClassList.length; i++) {
				var cls:int = partitionClassList[i];
				var cdim:int = classDimensions[cls];
				var cbits:int = classSubclasses[cls];
				var csub:int = (1 << cbits) - 1;
				var cval:int = 0;
				if (cbits > 0) {
					cval = source.readUnsignedHuffmanInt(vorbis.setupHeader.codeBooks[classMasterbooks[cls]].huffmanRoot);
				}

				for (j = 0; j < cdim; j++) {
					var book:int = subclassBooks[cls][cval & csub];
					cval >>>= cbits;
					if (book >= 0) {
						clone.yList[j + offset] = source.readUnsignedHuffmanInt(vorbis.setupHeader.codeBooks[book].huffmanRoot);
					} else {
						clone.yList[j + offset] = 0;
					}
				}
				offset += cdim;
			}
		
			return clone;
		}
	
		public override function computeFloor(vector:Vector.<Number>):void {

			var i:int;
			var j:int;

			var n:int = vector.length;
			var values:int = xList.length;
			//var step2Flags:Vector.<Boolean> = new Vector.<Boolean>(values);

			var range:int = RANGES[multiplier - 1];
	
			for (i = 2; i < values; i++) {

				var lowNeighbourOffset:int = lowNeighbours[i];
				var highNeighbourOffset:int = highNeighbours[i];
				
				var predicted:int = Util.renderPoint(
					xList[lowNeighbourOffset], xList[highNeighbourOffset], 
					yList[lowNeighbourOffset], yList[highNeighbourOffset], xList[i]);
				
				var val:int = yList[i];
				var highRoom:int = range - predicted;
				var lowRoom:int = predicted;
				var room:int = highRoom < lowRoom ? highRoom * 2 : lowRoom * 2;
				
				if (val != 0) {
					step2Flags[lowNeighbourOffset] = true;
					step2Flags[highNeighbourOffset] = true;
					step2Flags[i] = true;
					if (val >= room) {
						yList[i] = highRoom > lowRoom ? val - lowRoom + predicted
								: -val + highRoom + predicted - 1;
					} else {
						yList[i] = (val & 1) == 1 ? predicted - ((val + 1) >> 1)
								: predicted + (val >> 1);
					}
				} else {
					step2Flags[i] = false;
					yList[i] = predicted;
				}
			}
	
			for(i=0; i<xList.length; i++) {
				xList2[i] = xList[i];
			}

			sort(xList2, yList, step2Flags);
	
			var hx:int = 0;
			var hy:int = 0;
			var lx:int = 0;
			var ly:int = yList[0] * multiplier;
	
			for (i = 1; i < values; i++) {
				if (step2Flags[i]) {
					hy = yList[i] * multiplier;
					hx = xList2[i];
					Util.renderLine(lx, ly, hx, hy, vector);
					lx = hx;
					ly = hy;
				}
			}
	
			var r:Number = DB_STATIC_TABLE[hy];

			while(hx < n/2) {
				vector[hx++] = r;
			}

		}

		public function clone():Floor1 {
			var clone:Floor1 = new Floor1();
			clone.classDimensions = classDimensions;
			clone.classMasterbooks = classMasterbooks;
			clone.classSubclasses = classSubclasses;
			clone.maximumClass = maximumClass;
			clone.multiplier = multiplier;
			clone.partitionClassList = partitionClassList;
			clone.rangeBits = rangeBits;
			clone.subclassBooks = subclassBooks;
			clone.xList = xList;
			clone.yList = yList;
			clone.lowNeighbours = lowNeighbours;
			clone.highNeighbours = highNeighbours;
			clone.xList2 = xList2;
			clone.step2Flags = step2Flags;
			return clone;
		}
		
		private function sort(x:Vector.<int>, y:Vector.<int>, b:Vector.<Boolean>):void {
			var off:int = 0;
			var len:int = x.length;
			var lim:int = len + off;
			var itmp:int;
			var btmp:Boolean;
			var i:int;
			var j:int;
			// Insertion sort on smallest arrays
			for (i = off; i < lim; i++) {
				for (j = i; j > off && x[j - 1] > x[j]; j--) {
					itmp = x[j];
					x[j] = x[j - 1];
					x[j - 1] = itmp;
					itmp = y[j];
					y[j] = y[j - 1];
					y[j - 1] = itmp;
					btmp = b[j];
					b[j] = b[j - 1];
					b[j - 1] = btmp;
				}
			}
		}

	}
	
}