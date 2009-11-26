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

	import org.omtk.util.BitByteArray;	
	import flash.utils.Endian;

	public class LogicalOggStream {
	
		private var source:UncachedUrlStream;
		
		private var pageIndex:int;
		private var currentPage:OggPage;
		private var currentSegmentIndex:int;
		
		public function LogicalOggStream(source:UncachedUrlStream) {
			this.source = source;
		}
		
		public function getNextOggPacket(): OggPacket {
		
			var res:BitByteArray = new BitByteArray();

			var segmentLength:int = 0;

			if(currentPage == null) {
				currentPage = source.getNextOggPage();
			}

			do {
				if(currentSegmentIndex >= currentPage.segmentOffsets.length) {
            		currentSegmentIndex=0;

            		if(currentPage.eos) {
            			throw new EndOfOggStreamError("End of OGG stream");
            		}
            		
               		currentPage = source.getNextOggPage();
				}

				segmentLength = currentPage.segmentLengths[currentSegmentIndex];
				res.writeBytes(currentPage.data, currentPage.segmentOffsets[currentSegmentIndex], segmentLength);
				currentSegmentIndex++;
			}
			while(segmentLength==255);
			
			res.position = 0;
			
			var lastPacket: Boolean = currentPage.eos && currentSegmentIndex == currentPage.segmentOffsets.length;
			var lastGranulePosition: int = currentPage.absoluteGranulePosition;
			
			return new OggPacket(res, lastPacket, lastGranulePosition);
		}
	
	}
}