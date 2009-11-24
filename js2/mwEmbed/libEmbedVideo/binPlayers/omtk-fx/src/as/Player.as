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

package {

	import org.omtk.vorbis.VorbisSound;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageQuality;
	import flash.display.StageScaleMode;
	import flash.net.*;
	import flash.events.Event;
	import flash.text.TextField;
	import flash.utils.ByteArray;
	import flash.utils.Endian;
	import flash.utils.getTimer;
	import flash.utils.setTimeout;
	import flash.utils.setInterval;
	import flash.external.ExternalInterface;
	
	[ SWF( backgroundColor = '#ffffff', width = '1', height = '1' ) ]
	
	public class Player extends Sprite {
	
		private var sound: VorbisSound;
		private var initialized: Boolean = false;
		
		public function Player() {
			if(stage != null) {
				stage.frameRate = 20;
			}
			
			setTimeout(registerJSCallbacks, 100);
			initialized = true;
		}

		private function registerJSCallbacks(): void {
			if (ExternalInterface.available) {
				ExternalInterface.addCallback("play", playJS);
				ExternalInterface.addCallback("getMetaData", getMetaDataJS);
				ExternalInterface.addCallback("getPosition", getPositionJS);		
			}
		}

		public function playJS(url: String): void {
			if(sound != null) {
				sound.stop();
			}
			sound = new VorbisSound(new URLRequest(url));
			sound.addEventListener(Event.COMPLETE, complete);	
			sound.addEventListener(VorbisSound.METADATA_UPDATE, metadataUpdate);
			sound.play();
		}

		public function getMetaDataJS(key: String): String {
			if(sound == null) {
				return null;
			}
			else {
				return sound.getMetaData(key);
			}
		}
		
		public function getPositionJS(): int {
			if(sound == null) {
				return -1;
			}
			else {
				return sound.position;
			}
		}
		
		private function complete(event: Event):void {
			trace("complete");
			if(ExternalInterface.available) {
				ExternalInterface.call("OMTK_P_complete");
			}
		}

		private function metadataUpdate(event: Event):void {
			trace("metadata update: " + sound.getMetaData("TITLE"));
			if(ExternalInterface.available) {
				ExternalInterface.call("OMTK_P_metadataUpdate");
			}
		}

	}
	
}
