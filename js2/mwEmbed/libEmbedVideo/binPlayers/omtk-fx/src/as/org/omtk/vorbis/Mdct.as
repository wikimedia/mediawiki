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

	import flash.events.*;
	import flash.system.*;
	import flash.display.*;
	import flash.net.*;
	import flash.utils.getTimer;
	
	/*
	 * Wrapper for the haXe compiled class org.omtk.vorbis.MdctHX
	 */
	public class Mdct {
	
		public static var initialized : Boolean = false;
		private static var hxClass : Class;
		
		public static function initialize() : void {
			var ldr:Loader = new Loader();
			var swfUrl:String = "hxmdct.swf";
			var req:URLRequest = new URLRequest(swfUrl);
			var ldrContext:LoaderContext = 
			new LoaderContext(false, ApplicationDomain.currentDomain);
			ldr.load(req, ldrContext);
			ldr.contentLoaderInfo.addEventListener(Event.COMPLETE, swfLoaded);
			
			function swfLoaded(e:Event):void {
				hxClass = ApplicationDomain.currentDomain.getDefinition("org.omtk.vorbis.MdctHX") as Class;
				initialized = true;
			}		
		}

		private var delegate : Object;
		
		public function Mdct(n:int) { 
			delegate = new hxClass(n);
		}
		
		public function imdct(frq:Vector.<Number>, window:Vector.<Number>, pcm:Vector.<Number>):void {
			delegate.imdct(frq, window, pcm);
		}

	}
		
}