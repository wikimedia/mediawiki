"use strict";const t=require("vue");function a(u){const n=t.ref("");return t.onMounted(()=>{let e=u.value;for(;e&&e.lang==="";)e=e.parentElement;n.value=e?e.lang:null}),n}module.exports=a;
