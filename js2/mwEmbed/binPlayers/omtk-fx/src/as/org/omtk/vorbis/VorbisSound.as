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

	import flash.net.URLRequest;
	import flash.net.URLStream;
	import flash.utils.ByteArray;
	import flash.utils.Endian;
	import flash.events.Event;
	import flash.events.ProgressEvent;
	import flash.events.SampleDataEvent;
	
	import flash.external.ExternalInterface;

	import flash.media.Sound;
	
	import org.omtk.ogg.UncachedUrlStream;
	import org.omtk.ogg.EndOfOggStreamError;
	import flash.utils.setTimeout;
	
	public class VorbisSound extends Sound {
	
		public static var METADATA_UPDATE: String = "metadata_update";
	
		private var urlStream: URLStream;
		
		private var oggStream:UncachedUrlStream;
		private var vorbisStream:VorbisStream;

		private var bytesAvailable:int;

		private var initialized:Boolean = false;
		private var playing:Boolean = false;
		
		private var fill1:Boolean = true;
		private var fill2:Boolean = true;
		
		private var stopped: Boolean = false;
		private var completeEventDispatched: Boolean = false;
		
		public function VorbisSound(url: URLRequest ) {
			urlStream = new URLStream();
			urlStream.endian = Endian.LITTLE_ENDIAN;
			urlStream.load(url);

			Mdct.initialize();

			oggStream = new UncachedUrlStream(urlStream);
			oggStream.addEventListener('progress', progress);
			bytesAvailable = oggStream.bytesAvailable;
			addEventListener("sampleData", sampleGenerator);
		}
		
		private function initialize():void {
			vorbisStream = new VorbisStream(oggStream.getLogicalOggStream());
			setTimeout(dispatchEvent, 100, new Event(METADATA_UPDATE));
			initialized = true;
		}
		
		private var samplesPlayed: int = 0;
		
		private function sampleGenerator(event:SampleDataEvent):void {
			
			if(stopped) {
				return;
			}
			
			if(Mdct.initialized && !initialized && bytesAvailable > 64*1024) {
				initialize();
			}
			
			if(initialized && bytesAvailable > 16*1024 && !vorbisStream.finished) {
				var cnt: int;
				cnt = vorbisStream.readPcm(event.data);
				samplesPlayed += cnt;
				if(cnt < 2048 && oggStream.bytesAvailable > 0) {
					vorbisStream = new VorbisStream(oggStream.getLogicalOggStream());
					setTimeout(dispatchEvent, 100, new Event(METADATA_UPDATE));
					samplesPlayed += vorbisStream.readPcm(event.data);
				}
			}
			else if(vorbisStream == null || !vorbisStream.finished) {
			    for(var c:int=0; c<2048; c++) {
			    	event.data.writeFloat(0);
			    	event.data.writeFloat(0);
			    }
			}
			
			if(initialized && vorbisStream.finished && oggStream.bytesAvailable == 0 && !completeEventDispatched) {
				dispatchEvent(new Event(Event.COMPLETE));
				completeEventDispatched = true;
			}
			
			//trace("samples played: " + samplesPlayed + "/" + oggStream.bytesAvailable);
			
		}

		public function stop(): void {
			stopped = true;
		}

		public function progress(event:ProgressEvent):void {
			bytesAvailable = oggStream.bytesAvailable;
		}

		public function get position(): int {
			return samplesPlayed * 1000 / 44100;
		}

		public function getMetaData(key: String):String {
			if(vorbisStream != null) {
				return vorbisStream.commentHeader.comments[key];
			}
			else {
				return null;
			}
		}
	}
	
}