/**
 * metavid: mv_flashEmbed builds off of flowplayer api (included first in this file) 
 */
 
 /**
 * flowplayer.js 3.0.0-rc5. The Flowplayer API.
 * 
 * This file is part of Flowplayer, http://flowplayer.org
 *
 * Author: Tero Piirainen, <support@flowplayer.org>
 * Copyright (c) 2008 Flowplayer Ltd
 *
 * Released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Version: 3.0.0-rc5 - Thu Nov 20 2008 22:09:49 GMT-0000 (GMT+00:00)
 */
(function() {
 
/* 
	FEATURES 
	--------
	- handling multiple instances 
	- Flowplayer programming API 
	- Flowplayer event model	
	- player loading / unloading
	- $f() function
	- jQuery support
*/ 
 

/*jslint glovar: true, browser: true */
/*global flowplayer, $f */

// {{{ private utility methods
	
	function log(args) {
		
		// write into opera console
		if (typeof opera == 'object') {
			opera.postError("$f.fireEvent: " + args.join(" | "));	

			
		} else if (typeof console == 'object') {
			console.log("$f.fireEvent", [].slice.call(args));	
		}
	}

		
	// thanks: http://keithdevens.com/weblog/archive/2007/Jun/07/javascript.clone
	function clone(obj) {	
		if (!obj || typeof obj != 'object') { return obj; }		
		var temp = new obj.constructor();	
		for (var key in obj) {	
			if (obj.hasOwnProperty(key)) {
				temp[key] = clone(obj[key]);
			}
		}		
		return temp;
	}

	// stripped from jQuery, thanks John Resig 
	function each(obj, fn) {
		if (!obj) { return; }
		
		var name, i = 0, length = obj.length;
	
		// object
		if (length === undefined) {
			for (name in obj) {
				if (fn.call(obj[name], name, obj[name]) === false) { break; }
			}
			
		// array
		} else {
			for (var value = obj[0];
				i < length && fn.call( value, i, value ) !== false; value = obj[++i]) {				
			}
		}
	
		return obj;
	}

	
	// convenience
	function el(id) {
		return document.getElementById(id);	 
	}	

	
	// used extensively. a very simple implementation. 
	function extend(to, from, skipFuncs) {
		if (to && from) {			
			each(from, function(name, value) {
				if (!skipFuncs || typeof value != 'function') {
					to[name] = value;		
				}
			});
		}
	}
	
	// var arr = select("elem.className"); 
	function select(query) {
		var index = query.indexOf("."); 
		if (index != -1) {
			var tag = query.substring(0, index) || "*";
			var klass = query.substring(index + 1, query.length);
			var els = [];
			each(document.getElementsByTagName(tag), function() {
				if (this.className && this.className.indexOf(klass) != -1) {
					els.push(this);		
				}
			});
			return els;
		}
	}
	
	// fix event inconsistencies across browsers
	function stopEvent(e) {
		e = e || window.event;
		
		if (e.preventDefault) {
			e.stopPropagation();
			e.preventDefault();
			
		} else {
			e.returnValue = false;	
			e.cancelBubble = true;
		} 
		return false;
	}

	// push an event listener into existing array of listeners
	function bind(to, evt, fn) {
		to[evt] = to[evt] || [];
		to[evt].push(fn);		
	}
	
	
	// generates an unique id
   function makeId() {
	  return "_" + ("" + Math.random()).substring(2, 10);   
   }
	
//}}}	
	

// {{{ Clip

	var Clip = function(json, index, player) {
		
		// private variables
		var self = this;
		var cuepoints = {};
		var listeners = {}; 
		this.index = index;
		
		// instance variables
		if (typeof json == 'string') {
			json = {url:json};	
		}
	
		extend(this, json, true);	
		
		// event handling 
		each(("Begin*,Start,Pause*,Resume*,Seek*,Stop*,Finish*,LastSecond,Update,BufferFull,BufferEmpty,BufferStop").split(","),
			function() {
			
			var evt = "on" + this;
				
			// before event
			if (evt.indexOf("*") != -1) {
				evt = evt.substring(0, evt.length -1); 
				var before = "onBefore" + evt.substring(2); 
				
				self[before] = function(fn) {
					bind(listeners, before, fn);
					return self;
				};				
			}  
			
			self[evt] = function(fn) {
				bind(listeners, evt, fn);
				return self;
			};
			
			
			// set common clip event listeners to player level
			if (index == -1) {
				if (self[before]) {
					player[before] = self[before];		
				}				
				if (self[evt])  {
					player[evt] = self[evt];		
				}
			}
			
		});			  
		
		extend(this, {
			
			 
			onCuepoint: function(points, fn) {
				
				// embedded cuepoints
				if (arguments.length == 1) {
					cuepoints.embedded = [null, points];
					return self;
				}
				
				if (typeof points == 'number') {
					points = [points];	
				}
				
				var fnId = makeId();  
				cuepoints[fnId] = [points, fn]; 
				
				if (player.isLoaded()) {
					player._api().fp_addCuepoints(points, index, fnId);	
				}  
				
				return self;
			},
			
			update: function(json) {
				extend(self, json);

				if (player.isLoaded()) {
					player._api().fp_updateClip(json, index);	
				}
				var conf = player.getConfig(); 
				var clip = (index == -1) ? conf.clip : conf.playlist[index];
				extend(clip, json, true);
			},
			
			
			// internal event for performing clip tasks. should be made private someday
			_fireEvent: function(evt, arg1, arg2, target) {				 
				
				if (evt == 'onLoad') { 
					each(cuepoints, function(key, val) {
						player._api().fp_addCuepoints(val[0], index, key);		 
					}); 
					return false;
				}					
				
				// target clip we are working against
				if (index != -1) {
					target = self;	
				}
				
				if (evt == 'onCuepoint') {
					var fn = cuepoints[arg1];
					if (fn) {
						return fn[1].call(player, target, arg2);
					}
				}  
	
				if (evt == 'onStart' || evt == 'onUpdate') {
					
					extend(target, arg1);					
					
					if (!target.duration) {
						target.duration = arg1.metaData.duration;	 
					} else {
						target.fullDuration = arg1.metaData.duration;	
					}					  
				}  
				
				var ret = true;
				each(listeners[evt], function() {
					ret = this.call(player, target, arg1);		
				}); 
				return ret;				
			}			
			
		});
		
		
		// get cuepoints from config
		if (json.onCuepoint) {
			self.onCuepoint.apply(self, json.onCuepoint);
			delete json.onCuepoint;
		} 
		
		// get other events
		each(json, function(key, val) {
			if (typeof val == 'function') {
				bind(listeners, key, val);
				delete json[key];	
			}
		});

		
		// setup common clip event callbacks for Player object too (shortcuts)
		if (index == -1) {
			player.onCuepoint = this.onCuepoint;	
		}
	
	};

//}}}


// {{{ Plugin
		
	var Plugin = function(name, json, player, fn) {
	
		var listeners = {};
		var self = this;   
		var hasMethods = false;
	
		if (fn) {
			extend(listeners, fn);	
		}   
		
		// custom callback functions in configuration
		each(json, function(key, val) {
			if (typeof val == 'function') {
				listeners[key] = val;
				delete json[key];	
			}
		});  
		
		// core plugin methods		
		extend(this, {
  
			animate: function(props, speed, fn) { 
				if (!props) {
					return self;	
				}
				
				if (typeof speed == 'function') { 
					fn = speed; 
					speed = 500;
				}
				
				if (typeof props == 'string') {
					var key = props;
					props = {};
					props[key] = speed;
					speed = 500; 
				}
				
				if (fn) {
					var fnId = makeId();
					listeners[fnId] = fn;
				}
		
				if (speed === undefined) { speed = 500; }
				json = player._api().fp_animate(name, props, speed, fnId);	
				return self;
			},
			
			css: function(props, val) {
				if (val !== undefined) {
					var css = {};
					css[props] = val;
					props = css;					
				}
				try{
					json = player._api().fp_css(name, props);
					extend(self, json);
					return self;
				}catch(e){
					js_log('flow player could not set css: ' + json);
				}
			},
			
			show: function() {
				this.display = 'block';
				player._api().fp_showPlugin(name);
				return self;
			},
			
			hide: function() {
				this.display = 'none';
				player._api().fp_hidePlugin(name);
				return self;
			},
			
			toggle: function() {
				this.display = player._api().fp_togglePlugin(name);
				return self;
			},			
			
			fadeTo: function(o, speed, fn) {
				
				if (typeof speed == 'function') { 
					fn = speed; 
					speed = 500;
				}
				
				if (fn) {
					var fnId = makeId();
					listeners[fnId] = fn;
				}				
				this.display = player._api().fp_fadeTo(name, o, speed, fnId);
				this.opacity = o;
				return self;
			},
			
			fadeIn: function(speed, fn) { 
				return self.fadeTo(1, speed, fn);				
			},
	
			fadeOut: function(speed, fn) {
				return self.fadeTo(0, speed, fn);	
			},
			
			getName: function() {
				return name;	
			},
			
			
			// internal method not meant to be used by clients
		 _fireEvent: function(evt, arg) {

				
			// update plugins properties & methods
			if (evt == 'onUpdate') {
			   var json = arg || player._api().fp_getPlugin(name); 
					if (!json) { return;	}					
					
			   extend(self, json);
			   delete self.methods;
					
			   if (!hasMethods) {
				  each(json.methods, function() {
					 var method = "" + this;	   
							
					 self[method] = function() {
						var a = [].slice.call(arguments);
						var ret = player._api().fp_invoke(name, method, a); 
						return ret == 'undefined' ? self : ret;
					 };
				  });
				  hasMethods = true;		 
			   }
			}
			
			// plugin callbacks
			var fn = listeners[evt];

				if (fn) {
					
					fn.call(self, arg);
					
					// "one-shot" callback
					if (evt.substring(0, 1) == "_") {
						delete listeners[evt];  
					} 
			}		 
		 }					 
			
		});

	};


//}}}


function Player(wrapper, params, conf) {   
		
	// private variables (+ arguments)
	var 
		self = this, 
		api = null, 
		html, 
		commonClip, 
		playlist = [], 
		plugins = {},
		listeners = {},
		playerId,
		apiId,
		activeIndex,
		swfHeight,
		wrapperHeight;	

  
// {{{ public methods 
	
	extend(self, {
			
		id: function() {
			return playerId;	
		}, 
		
		isLoaded: function() {
			return (api !== null);	
		},
		
		getParent: function() {
			return wrapper;	
		},
		
		hide: function(all) {
			if (all) { wrapper.style.height = "0px"; }
			if (api) { api.style.height = "0px"; } 
			return self;
		},

		show: function() {
			wrapper.style.height = wrapperHeight + "px";
			if (api) { api.style.height = swfHeight + "px"; }
			return self;
		}, 
					
		isHidden: function() {
			return api && parseInt(api.style.height, 10) === 0;
		},
		
		
		load: function(fn) { 
			
			if (!api && self._fireEvent("onBeforeLoad") !== false) {
				
				// unload all instances
				each(players, function()  {
					this.unload();		
				});
				
				html = wrapper.innerHTML; 
				flashembed(wrapper, params, {config: conf});
				
				// function argument
				if (fn) {
					fn.cached = true;
					bind(listeners, "onLoad", fn);	
				}
			}
			
			return self;	
		},
		
		unload: function() {  
			
		 if (api && html.replace(/\s/g, '') !== '' && !api.fp_isFullscreen() && 
			self._fireEvent("onBeforeUnload") !== false) { 
				api.fp_close();
				wrapper.innerHTML = html; 
				self._fireEvent("onUnload");
				api = null;
			}
			
			return self;
		},

		getClip: function(index) {
			if (index === undefined) {
				index = activeIndex;	
			}
			return playlist[index];
		},
		
		
		getCommonClip: function() {
			return commonClip;	
		},		
		
		getPlaylist: function() {
			return playlist; 
		},
		
	  getPlugin: function(name) {  
		 var plugin = plugins[name];
		 
			// create plugin if nessessary
		 if (!plugin && self.isLoaded()) {
				var json = self._api().fp_getPlugin(name);
				if (json) {
					plugin = new Plugin(name, json, self);
					plugins[name] = plugin;						  
				} 
		 }		
		 return plugin; 
	  },
		
		getScreen: function() { 
			return self.getPlugin("screen");
		}, 
		
		getControls: function() { 
			return self.getPlugin("controls");
		}, 

		getConfig: function() { 
			return clone(conf);
		},
		
		getFlashParams: function() { 
			return params;
		},		
		
		loadPlugin: function(name, url, props, fn) { 

			// properties not supplied			
			if (typeof props == 'function') { 
				fn = props; 
				props = {};
			} 
			
			// if fn not given, make a fake id so that plugin's onUpdate get's fired
			var fnId = fn ? makeId() : "_"; 
			self._api().fp_loadPlugin(name, url, props, fnId); 
			
			// create new plugin
			var arg = {};
			arg[fnId] = fn;
			var p = new Plugin(name, null, self, arg);
			plugins[name] = p;
			return p;			
		},
		
		
		getState: function() {
			return api ? api.fp_getState() : -1;
		},
		
		// "lazy" play
		play: function(clip) {
			
			function play() {
				if (clip !== undefined) {
					self._api().fp_play(clip);
				} else {
					if(typeof self._api().fp_play == 'function')
						self._api().fp_play();	
				}
			}
			
			if (api) {
				play();
				
			} else {
				self.load(function() { 
					play();
				});
			}
			
			return self;
		},
		
		getVersion: function() {
			var js = "flowplayer.js 3.0.0-rc5";
			if (api) {
				var ver = api.fp_getVersion();
				ver.push(js);
				return ver;
			}
			return js; 
		},
		
		_api: function() {
			if (!api) {
				throw "Flowplayer " +self.id()+ " not loaded. Try moving your call to player's onLoad event";
			}
			return api;				
		},
		
		_dump: function() {
			console.log(listeners);
		}
		
	}); 
	
	
	// event handlers
	each(("Click*,Load*,Unload*,Keypress*,Volume*,Mute*,Unmute*,PlaylistReplace,Fullscreen*,FullscreenExit,Error").split(","),
		function() {		 
			var name = "on" + this;
			
			// before event
			if (name.indexOf("*") != -1) {
				name = name.substring(0, name.length -1); 
				var name2 = "onBefore" + name.substring(2);
				self[name2] = function(fn) {
					bind(listeners, name2, fn);	
					return self;
				};						
			}
			
			// normal event
			self[name] = function(fn) {
				bind(listeners, name, fn);	
				return self;
			};			 
		}
	); 
	
	
	// core API methods
	each(("pause,resume,mute,unmute,stop,toggle,seek,getStatus,getVolume,setVolume,getTime,isPaused,isPlaying,startBuffering,stopBuffering,isFullscreen,reset").split(","),		
		function() {		 
			var name = this;
			
			self[name] = function(arg) {
				if (!api) { return self; }
				try{
					var ret = (arg === undefined) ? api["fp_" + name]() : api["fp_" + name](arg);
					return ret == 'undefined' ? self : ret;
				}catch (e){
					js_log('flowplayer could not access fp_ '+ name);
				}
			};			 
		}
	);		 
	
//}}}


// {{{ public method: _fireEvent
		
	self._fireEvent = function(evt, arg0, arg1, arg2) {		
				
		if (conf.debug) {
			log(arguments);		
		}				
		
		// internal onLoad
		if (evt == 'onLoad' && !api) {  
			
			api = api || el(apiId); 
			swfHeight = api.clientHeight;
			
			each(playlist, function() {
				this._fireEvent("onLoad");		
			});
			
			each(plugins, function(name, p) {
				p._fireEvent("onUpdate");		
			});
			
			
			commonClip._fireEvent("onLoad");  
		}
		
	  if (evt == 'onContextMenu') {
		 each(conf.contextMenu[arg0], function(key, fn)  {
			fn.call(self);
		 });
		 return;
	  }

		if (evt == 'onPluginEvent') {
			var name = arg0.name || arg0;
			var p = plugins[name];
			if (p) {
				if (arg0.name) {
					p._fireEvent("onUpdate", arg0);		
				}
				p._fireEvent(arg1);		
			}
			return;
		}		

		// onPlaylistReplace
		if (evt == 'onPlaylistReplace') {
			playlist = [];
			var index = 0;
			each(arg0, function() {
				playlist.push(new Clip(this, index++));
			});		
		}
		
		var ret = true;
		
		// clip event
		if (arg0 === 0 || (arg0 && arg0 >= 0)) {
			
			activeIndex = arg0;
			var clip = playlist[arg0];			
			
			if (clip) {
				ret = clip._fireEvent(evt, arg1, arg2);	
			} 
			
			if (!clip || ret !== false) {
				
				// clip argument is given for common clip, because it behaves as the target
				ret = commonClip._fireEvent(evt, arg1, arg2, clip);	
			}  
		} 
		
		// player event	
		var i = 0;
		each(listeners[evt], function() {
			ret = this.call(self, arg0);		
			
			// remove cached entry
			if (this.cached) {
				listeners[evt].splice(i, 1);	
			}
			
			// break loop
			if (ret === false) { return false;	 }
			i++;
			
		}); 

		return ret;
	};

//}}}
 

// {{{ init
	
   function init() {
		
		if ($f(wrapper)) {
			return null;	
		}		
		
		wrapperHeight = parseInt(wrapper.style.height) || wrapper.clientHeight;
		
		// register this player into global array of instances
		players.push(self);  
		
		
		// flashembed parameters
		if (typeof params == 'string') {
			params = {src: params};	
		}	
		
		// playerId	
		playerId = wrapper.id || "fp" + makeId();
		apiId = params.id || playerId + "_api";		 
		params.id = apiId;
		conf.playerId = playerId;
		
		
		// plain url is given as config
		if (typeof conf == 'string') {
			conf = {clip:{url:conf}};	
		} 
		
		// common clip is always there
		conf.clip = conf.clip || {};
		commonClip = new Clip(conf.clip, -1, self);  
		
		
		// wrapper href as playlist
		if (wrapper.getAttribute("href")) { 
			conf.playlist = [{url:wrapper.getAttribute("href", 2)}];			
		} 
		
		// playlist
		conf.playlist = conf.playlist || [conf.clip]; 
		
		var index = 0;
		each(conf.playlist, function() {

			var clip = this;
			
			// clip is an array, we don't allow that
			if (typeof clip == 'object' && clip.length)  {
				clip = "" + clip;	
			}
			
			if (!clip.url && typeof clip == 'string') {				
				clip = {url: clip};				
			} 
			
			// populate common clip properties to each clip
			extend(clip, conf.clip, true);		
			
			// modify configuration playlist
			conf.playlist[index] = clip;			
			
			// populate playlist array
			clip = new Clip(clip, index, self);
			playlist.push(clip);						
			index++;			
		});
			
		
		// event listeners
		each(conf, function(key, val) {
			if (typeof val == 'function') {
				bind(listeners, key, val);
				delete conf[key];	
			}
		});		 
		
		
		// plugins
		each(conf.plugins, function(name, val) {
			if (val) {
				plugins[name] = new Plugin(name, val, self);
			}
		});
		
		
		// setup controlbar plugin if not explicitly defined
		if (!conf.plugins || conf.plugins.controls === undefined) {
			plugins.controls = new Plugin("controls", null, self);	
		} 
		
		// Flowplayer uses black background by default
		params.bgcolor = params.bgcolor || "#000000";
		
		
		// setup default settings for express install
		params.version = params.version || [9,0];		
		params.expressInstall = 'http://www.flowplayer.org/swf/expressinstall.swf';
		
		
		// click function
		function doClick(e) {
			if (self._fireEvent("onBeforeClick") !== false) {
				self.load();		
			} 
			return stopEvent(e);					
		}
		
		// defer loading upon click
		html = wrapper.innerHTML;
		if (html.replace(/\s/g, '') !== '') {	 
			
			if (wrapper.addEventListener) {
				wrapper.addEventListener("click", doClick, false);	
				
			} else if (wrapper.attachEvent) {
				wrapper.attachEvent("onclick", doClick);	
			}
			
		// player is loaded upon page load 
		} else {
			
			// prevent default action from wrapper (safari problem) loaded
			if (wrapper.addEventListener) {
				wrapper.addEventListener("click", stopEvent, false);	
			}
			
			// load player
			self.load();
		}
		
	}

	// possibly defer initialization until DOM get's loaded
	if (typeof wrapper == 'string') { 
		flashembed.domReady(function() {
			var node = el(wrapper); 
			
			if (!node) {
				throw "Flowplayer cannot access element: " + wrapper;	
			} else {
				wrapper = node; 
				init();					
			} 
		});
		
	// we have a DOM element so page is already loaded
	} else {		
		init();
	}
	
	
//}}}


}


// {{{ flowplayer() & statics 

// container for player instances
var players = [];


// this object is returned when multiple player's are requested 
function Iterator(arr) {
	
	this.length = arr.length;
	
	this.each = function(fn)  {
		each(arr, fn);	
	};
	
	this.size = function() {
		return arr.length;	
	};	
}

// these two variables are the only global variables
window.flowplayer = window.$f = function() {
	
	var instance = null;
	var arg = arguments[0];	
	
	
	// $f()
	if (!arguments.length) {
		each(players, function() {
			if (this.isLoaded())  {
				instance = this;	
				return false;
			}
		});
		
		return instance || players[0];
	} 
	
	if (arguments.length == 1) {
		
		// $f(index);
		if (typeof arg == 'number') { 
			return players[arg];	
	
			
		// $f(wrapper || 'containerId' || '*');
		} else {
			
			// $f("*");
			if (arg == '*') {
				return new Iterator(players);	
			}
			
			// $f(wrapper || 'containerId');
			each(players, function() {
				if (this.id() == arg.id || this.id() == arg || this.getParent() == arg)  {
					instance = this;	
					return false;
				}
			});
			
			return instance;					
		}
	}			 

	// instance builder 
	if (arguments.length > 1) {		

		var swf = arguments[1];
		var conf = (arguments.length == 3) ? arguments[2] : {};
						
		if (typeof arg == 'string') {
			
			// select arg by classname
			if (arg.indexOf(".") != -1) {
				var instances = [];
				
				each(select(arg), function() { 
					instances.push(new Player(this, clone(swf), clone(conf)));		 
				});	
				
				return new Iterator(instances);
				
			// select node by id
			} else {		
				var node = el(arg);
				return new Player(node !== null ? node : arg, swf, conf);	  
			} 
			
			
		// arg is a DOM element
		} else if (arg) {
			return new Player(arg, swf, conf);						
		}
		
	} 
	
	return null; 
};
	
extend(window.$f, {

	// called by Flash External Interface		 
	fireEvent: function(id, evt, a0, a1, a2) {		
		var p = $f(id);		
		return p ? p._fireEvent(evt, a0, a1, a2) : null;
	},
	
	
	// create plugins by modifying Player's prototype
	addPlugin: function(name, fn) {
		Player.prototype[name] = fn;
		return $f;
	},
	
	// utility methods for plugin developers
	each: each,
	
	extend: extend
	
});
	
//}}}


//{{{ jQuery support

if (typeof jQuery == 'function') {
	
	jQuery.prototype.flowplayer = function(params, conf) {  
		
		// select instances
		if (!arguments.length || typeof arguments[0] == 'number') {
			var arr = [];
			this.each(function()  {
				var p = $f(this);
				if (p) {
					arr.push(p);	
				}
			});
			return arguments.length ? arr[arguments[0]] : new Iterator(arr);
		}
		
		// create flowplayer instances
		return this.each(function() { 
			$f(this, clone(params), conf ? clone(conf) : {});	
		}); 
		
	};
	
}

//}}}


})();
/** 
 * flashembed 0.34. Adobe Flash embedding script
 * 
 * http://flowplayer.org/tools/flash-embed.html
 *
 * Copyright (c) 2008 Tero Piirainen (support@flowplayer.org)
 *
 * Released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * >> Basically you can do anything you want but leave this header as is <<
 *
 * first version 0.01 - 03/11/2008 
 * version 0.34 - Tue Nov 11 2008 09:09:52 GMT-0000 (GMT+00:00)
 */
(function() { 
 
//{{{ utility functions 
		
var jQ = typeof jQuery == 'function';


// from "Pro JavaScript techniques" by John Resig
function isDomReady() {
	
	if (domReady.done)  { return false; }
	
	var d = document;
	if (d && d.getElementsByTagName && d.getElementById && d.body) {
		clearInterval(domReady.timer);
		domReady.timer = null;
		
		for (var i = 0; i < domReady.ready.length; i++) {
			domReady.ready[i].call();	
		}
		
		domReady.ready = null;
		domReady.done = true;
	} 
}

// if jQuery is present, use it's more effective domReady method
var domReady = jQ ? jQuery : function(f) {
	
	if (domReady.done) {
		return f();	
	}
	
	if (domReady.timer) {
		domReady.ready.push(f);	
		
	} else {
		domReady.ready = [f];
		domReady.timer = setInterval(isDomReady, 13);
	} 
};	


// override extend params function 
function extend(to, from) {
	if (from) {
		for (key in from) {
			if (from.hasOwnProperty(key)) {
				to[key] = from[key];
			}
		}
	}
	
	return to;
}	


function concatVars(vars) {		
	var out = "";
	
	for (var key in vars) { 
		if (vars[key]) {
			out += [key] + '=' + asString(vars[key]) + '&';
		}
	}			
	return out.substring(0, out.length -1);				
}  



// JSON.asString() function
function asString(obj) {

	switch (typeOf(obj)){
		case 'string':
			obj = obj.replace(new RegExp('(["\\\\])', 'g'), '\\$1');
			
			// flash does not handle %- characters well. transforms "50%" to "50pct" (a dirty hack, I admit)
			obj = obj.replace(/^\s?(\d+)%/, "$1pct");
			return '"' +obj+ '"';
			
		case 'array':
			return '['+ map(obj, function(el) {
				return asString(el);
			}).join(',') +']'; 
			
		case 'function':
			return '"function()"';
			
		case 'object':
			var str = [];
			for (var prop in obj) {
				if (obj.hasOwnProperty(prop)) {
					str.push('"'+prop+'":'+ asString(obj[prop]));
				}
			}
			return '{'+str.join(',')+'}';
	}
	
	// replace ' --> "  and remove spaces
	return String(obj).replace(/\s/g, " ").replace(/\'/g, "\"");
}


// private functions
function typeOf(obj) {
	if (obj === null || obj === undefined) { return false; }
	var type = typeof obj;
	return (type == 'object' && obj.push) ? 'array' : type;
}


// version 9 bugfix: (http://blog.deconcept.com/2006/07/28/swfobject-143-released/)
if (window.attachEvent) {
	window.attachEvent("onbeforeunload", function() {
		__flash_unloadHandler = function() {};
		__flash_savedUnloadHandler = function() {};
	});
}

function map(arr, func) {
	var newArr = []; 
	for (var i in arr) {
		if (arr.hasOwnProperty(i)) {
			newArr[i] = func(arr[i]);
		}
	}
	return newArr;
}
	
function getEmbedCode(p, c) {
	var html = '<embed type="application/x-shockwave-flash" ';

	if (p.id) { extend(p, {name:p.id}); }
	
	for (var key in p) { 
		if (p[key] !== null) { 
			html += key + '="' +p[key]+ '"\n\t';
		}
	}

	if (c) {
		 html += 'flashvars=\'' + concatVars(c) + '\'';
	}
	
	// thanks Tom Price (07/17/2008)
	html += '/>';
	
	return html;
}

function getObjectCode(p, c, embeddable) {
	
	var html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
	html += 'width="' + p.width + '" height="' + p.height + '"'; 
	
	// force id for IE. otherwise embedded Flash object cannot be returned
	if (!p.id && document.all) {
		p.id = "_" + ("" + Math.random()).substring(5);
	} 
	
	if (p.id) {
		html += ' id="' + p.id + '"';
	}
	
	html += '>';  
	
	// sometimes ie fails to load flash if it's on cache
	if (document.all) {
		p.src += ((p.src.indexOf("?") != -1 ? "&" : "?") + Math.random());		
	} 
	
	html += '\n\t<param name="movie" value="'+ p.src +'" />';

	var e = extend({}, p);
	e.id = e.width = e.height = e.src = null;
	
	for (var k in e) {
		if (e[k] !== null) {
			html += '\n\t<param name="'+ k +'" value="'+ e[k] +'" />';
		}
	}
	
	if (c) {
		html += '\n\t<param name="flashvars" value=\'' + concatVars(c) + '\' />';
	}
	
	if (embeddable) {
		html += getEmbedCode(p, c);	
	}
	 
	html += "</object>";
	
	return html;
}

function getFullHTML(p, c) {
	return getObjectCode(p, c, true);	
}

function getHTML(p, c) { 
	var isNav = navigator.plugins && navigator.mimeTypes && navigator.mimeTypes.length; 
	return (isNav) ? getEmbedCode(p, c) : getObjectCode(p, c);
}

//}}}

	
window.flashembed = function(root, userParams, flashvars) {	
	
	
//{{{ construction
		
	// setup params
	var params = {
		
		// very common params
		src: '#',
		width: '100%',
		height: '100%',		
		
		// flashembed specific options
		version:null,
		onFail:null,
		expressInstall:null,  
		debug: false,
		
		// flashembed defaults
		// bgcolor: 'transparent',
		allowfullscreen: true,
		allowscriptaccess: 'always',
		quality: 'high',
		type: 'application/x-shockwave-flash',
		pluginspage: 'http://www.adobe.com/go/getflashplayer'
	};
	
	
	if (typeof userParams == 'string') {
		userParams = {src: userParams};	
	}
	
	extend(params, userParams);			 
		
	var version = flashembed.getVersion(); 
	var required = params.version; 
	var express = params.expressInstall;		 
	var debug = params.debug;

	
	if (typeof root == 'string') {
		var el = document.getElementById(root);
		if (el) {
			root = el;	
		} else {
			domReady(function() {
				flashembed(root, userParams, flashvars);
			});
			return;		 
		} 
	}
	
	if (!root) { return; }

	
	// is supported 
	if (!required || flashembed.isSupported(required)) {
		params.onFail = params.version = params.expressInstall = params.debug = null;
		
		// root.innerHTML may cause broplems: http://domscripting.com/blog/display/99
		// thanks to: Ryan Rud
		// var tmp = document.createElement("extradiv");
		// tmp.innerHTML = getHTML();
		// root.appendChild(tmp);
		
		root.innerHTML = getHTML(params, flashvars);
		
		// return our API			
		return root.firstChild;
		
	// custom fail event
	} else if (params.onFail) {
		var ret = params.onFail.call(params, flashembed.getVersion(), flashvars);
		if (ret === true) { root.innerHTML = ret; }		
		

	// express install
	} else if (required && express && flashembed.isSupported([6,65])) {
		
		extend(params, {src: express});
		
		flashvars = {
			MMredirectURL: location.href,
			MMplayerType: 'PlugIn',
			MMdoctitle: document.title
		};
		
		root.innerHTML = getHTML(params, flashvars);	
		
	// not supported
	} else {

		// minor bug fixed here 08.04.2008 (thanks JRodman)
		
		if (root.innerHTML.replace(/\s/g, '') !== '') {
			// custom content was supplied
		
		} else {
			root.innerHTML = 
				"<h2>Flash version " + required + " or greater is required</h2>" + 
				"<h3>" + 
					(version[0] > 0 ? "Your version is " + version : "You have no flash plugin installed") +
				"</h3>" + 
				"<p>Download latest version from <a href='" + params.pluginspage + "'>here</a></p>";
		}
	}

	return root;
	
//}}}
	
	
};


//{{{ static methods

extend(window.flashembed, {

	// returns arr[major, fix]
	getVersion: function() {
	
		var version = [0, 0];
		
		if (navigator.plugins && typeof navigator.plugins["Shockwave Flash"] == "object") {
			var _d = navigator.plugins["Shockwave Flash"].description;
			if (typeof _d != "undefined") {
				_d = _d.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
				var _m = parseInt(_d.replace(/^(.*)\..*$/, "$1"), 10);
				var _r = /r/.test(_d) ? parseInt(_d.replace(/^.*r(.*)$/, "$1"), 10) : 0;
				version = [_m, _r];
			}
			
		} else if (window.ActiveXObject) {
			
			try { // avoid fp 6 crashes
				var _a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
				
			} catch(e) {
				try { 
					_a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
					version = [6, 0];
					_a.AllowScriptAccess = "always"; // throws if fp < 6.47 
					
				} catch(ee) {
					if (version[0] == 6) { return; }
				}
				try {
					_a = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
				} catch(eee) {
				
				}
				
			}
			
			if (typeof _a == "object") {
				_d = _a.GetVariable("$version"); // bugs in fp 6.21 / 6.23
				if (typeof _d != "undefined") {
					_d = _d.replace(/^\S+\s+(.*)$/, "$1").split(",");
					version = [parseInt(_d[0], 10), parseInt(_d[2], 10)];
				}
			}
		} 
		
		return version;
	},
	
	isSupported: function(version) {
		var now = flashembed.getVersion();
		var ret = (now[0] > version[0]) || (now[0] == version[0] && now[1] >= version[1]);			
		return ret;
	},
	
	domReady: domReady,
	
	// returns a String representation from JSON object 
	asString: asString,
	
	getHTML: getHTML,
	
	getFullHTML: getFullHTML
	
});

//}}}


// setup jquery support
if (jQ) {
	
	jQuery.prototype.flashembed = function(params, flashvars) { 
		return this.each(function() { 
			flashembed(this, params, flashvars);
		});
	};

}

})();

/************************************************
********* mv_embed extension to flowplayer.js ***
************************************************/	 
var flowplayerEmbed = {
	instanceOf:'flowplayerEmbed',
	monitorTimerId : 0,
	old_pid:0,
	didSeekJump:false,
	startedTimedPlayback:false,		
	didDateStartTimeRestore:false,
	supports: {
		'play_head' : true, 
		'pause' : true,
		'stop' : true, 
		'time_display' : true, 
		'volume_control' : true
	},
	getEmbedHTML: function (){
		setTimeout('document.getElementById(\''+this.id+'\').postEmbedJS()', 150);
		return this.wrapEmebedContainer( this.getEmbedObj() );
	},
	getEmbedObj:function(){	
		//give the embed element a unique pid (work around for flowplayer persistence)
		if( this.old_pid!=0 ){
			this.pid = this.pid +'_'+ this.old_pid;
		}				
		return '<a  '+
					'href="'+ this.getSrc() + '" '+  
					'style="display:block;width:' + parseInt(this.width) + 'px;height:' + parseInt(this.height) + 'px" '+  
					'id="'+this.pid+'">'+ 
				'</a>';
	},
	postEmbedJS: function()
	{   
		var _this = this;
		js_log('embedFlow: uri:'+ this.getSrc() + "\n"+ mv_embed_path + 'binPlayers/flowplayer/flowplayer-3.0.1.swf' ) ;
		var flowConfig = { 
			clip: { 
				url: this.getSrc(),				  
				// when this is false playback does not start until play button is pressed 
				autoPlay: true
			},
			plugins: { 
				controls: { 
				   all: false, 
				   fullscreen: true,
				   backgroundColor: 'transparent',
				   backgroundGradient: 'none',
				   autoHide:'always',
				   top:'95%',
				   right:'0px'
				}				 
			},
			screen: {
				opacity : '1.0'
			}			
		};
		
		//if in preview mode set grey and lower volume until "ready"
		if( this.preview_mode ){
			flowConfig.screen.opacity = 0.2;				 
		}					
		
		$f(this.pid,  mv_embed_path + 'binPlayers/flowplayer/flowplayer-3.0.1.swf', flowConfig);				  
		//get the this.fla value:		 
		this.getFLA();		
		//set up bindings (for when interacting with the swf causes action:  
		this.fla.onPause(function(){																
			_this.parent_pause();   //update the interface			 
		})
		this.fla.onResume( function(){
			_this.parent_play();	//update the interface	
		});				
		   
		//start monitor: 
		this.monitor();  
		this.old_pid++;
	},   
	/* js hooks/controls */
	play: function(){				
		this.getFLA();		
		//update play/pause button etc
		this.parent_play();				
		if( this.fla ){			
			this.fla.play();			
			
			//on a resume make sure volume and opacity are correct 
			this.restorePlayer();						 
			setTimeout('$j(\'#'+this.id+'\').get(0).monitor()', 250);
		}
	},
	//@@todo support mute
	toggleMute: function(){
		this.parent_toggleMute();
		this.getFLA();
		if(this.fla){
			if(this.muted){
				
			}else{
				
			}
		}
	},
	//@@ Suport UpDateVolumen 
	updateVolumen:function(perc){
		this.getFLA();		
		if(this.fla)this.fla.setVolume(perc*100);
			    
    },	
    //@@ Get Volumen
  	getVolumen:function(){
		this.getFLA();		
		if(this.fla)
			return this.fla.getVolume() / 100;			   
    },	
	fullscreen:function(){
		if(this.fla){
			this.fla.fullscreen();
		}else{
			js_log('must be playing before you can go fullscreen');
		}
	},
	pause : function()
	{
		this.getFLA();
		if(!this.thumbnail_disp){
			this.parent_pause();
			if(this.fla){		
				js_log("Flash:Pause: " + this.fla.isPaused() );
				if( this.fla['pause'] ){
					if( ! this.fla.isPaused() ){
						js_log('calling plugin pause');				
						this.fla.pause();  
						
						//restore volume and opacity 
						this.restorePlayer();		   
					}
				}
			}
		}
	},
	monitor : function()
	{			
		var _this = this;
		//date time
		if( !this.dateStartTime ){
			var d = new Date();
			this.dateStartTime = d.getTime();
			
		}else{
			var d = new Date();						 
			if( !this.didDateStartTimeRestore  && this.preview_mode)
				this.fla.setVolume(0);
				
			if( (d.getTime() - this.dateStartTime) > 6000  && !this.didDateStartTimeRestore){							 
				this.restorePlayer(); 
			}
		}					  
					  
		var flash_state = this.fla.getStatus();
		//update the duration from the clip if its zero or not set: 
		if( !this.duration || this.duration==0 ){
			if( this.fla.getClip() ){
				this.duration = this.fla.getClip().fullDuration;				
				js_log('set duration via clip value: ' + this.getDuration() );
			}
		}
		//update the duration ntp values: 
		this.getDuration();		

		if( typeof flash_state == 'undefined' ){
			 var flash_state = {
				 "time" : this.fla.getTime()
			 };				 
			//we are not getting buffered data restore volume and opacity
			this.restorePlayer();						   
		}else{
			//simplification of buffer state ... should move to support returning time rages like:
			//http://www.whatwg.org/specs/web-apps/current-work/#normalized-timeranges-object			
			this.bufferedPercent = flash_state.bufferEnd / this.getDuration();									
		}			   
		//set the current Time (based on timeFormat)		
		if( this.supportsURLTimeEncoding() ){
			this.currentTime = flash_state.time;			  
			//js_log('set buffer: ' + flash_state.bufferEnd + ' at time: ' + flash_state.time +' of total dur: ' + this.getDuration()); 
		}else{
			this.currentTime = flash_state.time + this.start_offset;						
			//stop buffering if greater than the duration: 
			if( flash_state.bufferEnd > this.getDuration() + 5 ){
				//js_log('should stop buffering (does not seem to work)' + flash_state.bufferEnd + ' > dur: ' + this.getDuration() );
				this.fla.stopBuffering();
			} 
		}				  
		
		if(this.currentTime > npt2seconds(this.start_ntp) && !this.startedTimedPlayback){
			var fail = false;
			try
			{
				this.restorePlayer();				
			}
			catch(err)
			{
				js_log('failed to set values');
				fail = true;
			}
			if(!fail)
				this.startedTimedPlayback=true;										 
		}
		
		/* to support local seeks */
		if(this.currentTime > 1 && this.seek_time_sec != 0 && !this.supportsURLTimeEncoding() )
		{
			js_log('flashEmbed: _local_ Seeking to ' + this.seek_time_sec);
			this.fla.seek( this.seek_time_sec );
			this.seek_time_sec = 0;
		}						
		
		//checks to see if we reached the end of playback:				
		if(this.duration && this.startedTimedPlayback && 
			(	 this.currentTime > (npt2seconds(this.end_ntp)+2) 
				|| 
				( this.currentTime > (npt2seconds(this.end_ntp)-1) 
					&& this.prevTime == this.currentTime) )
			){							
			js_log('prbally reached end of stream: '+ seconds2npt( this.currentTime) );
			this.onClipDone();				  
		}		
		
		//update the status and check timmer via universal parent monitor
		this.parent_monitor();
		
		
		this.prevTime = this.currentTime;	
		//js_log('cur perc loaded: ' + this.fla.getPercentLoaded() +' cur time : ' + (this.currentTime - npt2seconds(this.start_ntp)) +' / ' +(npt2seconds(this.end_ntp)-npt2seconds(this.start_ntp)));
	},
	restorePlayer:function(){		
		if(!this.fla)
			this.getFLA();
		if(this.fla){
			js_log('f:do restorePlayer');
			this.fla.setVolume(90); 
			$f().getPlugin('screen').css({'opacity':'1.0'} );
			//set the fallback date restore flag to true:
			this.didDateStartTimeRestore=true;				  
		}		
	},
	// get the embed fla object 
	getFLA : function (){
		this.fla = $f(this.pid);		   
	},
	stop : function(){	
		js_log('f:flashEmbed:stop');		
		this.startedTimedPlayback=false;	
		if (this.monitorTimerId != 0 )
		{
			clearInterval(this.monitorTimerId);
			this.monitorTimerId = 0;
		}
		if(this.fla)
			this.fla.unload();
		this.parent_stop();
	},
	onStop: function(){
		js_log('f:onStop');
		//stop updates: 
		if( this.monitorTimerId != 0 )
		{
			clearInterval(this.monitorTimerId);
			this.monitorTimerId = 0;
		}
	},
	onClipDone: function(){			
		js_log('f:flash:onClipDone');		
		if( ! this.startedTimedPlayback){
			js_log('clip done before timed playback started .. not good. (ignoring) ');			
			//keep monitoring: 
			this.monitor();
		}else{
			js_log('clip done and '+ this.startedTimedPlayback);
			//stop the clip if its not stopped already:
			this.stop();
			this.setStatus("Clip Done...");
			//run the onClip done action: 
			this.parent_onClipDone();
		}
	}
};
