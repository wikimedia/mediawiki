"use strict";const n=require("vue");function u(r,t){if(r()){n.warn(t);return}const c=n.watch(r,e=>{e&&(n.warn(t),c())})}exports.useWarnOnce=u;
