"use strict";const o=require("vue");function p(e,u,r){return o.computed({get:()=>e.value,set:t=>u(r||"update:modelValue",t)})}exports.useModelWrapper=p;
