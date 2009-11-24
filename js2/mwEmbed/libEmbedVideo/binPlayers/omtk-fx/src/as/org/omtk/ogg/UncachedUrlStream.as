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
	import flash.utils.Endian;
	import flash.events.HTTPStatusEvent;
	import flash.events.ProgressEvent;
	
	public class UncachedUrlStream {
		
		private var source:URLStream;
	
		public function UncachedUrlStream(source: URLStream) {
			this.source = source;
		}
	
		public function get bytesAvailable():int {
			return source.bytesAvailable;
		}	
	
		public function addEventListener(type:String, listener:Function):void {
			source.addEventListener(type, listener);
		}
	
		public function getLogicalOggStream():LogicalOggStream {
			return new LogicalOggStream(this);
		}
	
		public function getNextOggPage():OggPage {
			return new OggPage(source);
		}
		
	}

}