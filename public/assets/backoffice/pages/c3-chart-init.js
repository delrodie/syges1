/*
 Template Name: Aplomb - Bootstrap 4 Admin Dashboard
 Author: Mannatthemes
 Website: www.mannatthemes.com
 File: C3 Chart init js
 */
!function(e){
    "use strict";
    var a=function(){};
    a.prototype.init=function(){
        c3.generate({
            bindto:"#chart",
            data:{
                columns:[
                    ["Desktop",150,80,70,152,250,95],
                    ["Mobile",200,130,90,240,130,220],
                    ["Tablet",300,200,160,400,250,250]
                ],
                type:"bar",
                colors:{
                    Desktop:"#cdcdcd",
                    Mobile:"#69a09d",
                    Tablet:"#436797"
                }
            }
        })},
        e.ChartC3=new a,e.ChartC3.Constructor=a}(window.jQuery),function(e){"use strict";window.jQuery.ChartC3.init()}();