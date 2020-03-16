/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('select2/dist/js/select2');
require('chart.js/dist/Chart.min.js');
require('datatables.net');
require('datatables.net-bs4');
require('datatables.net-responsive');
require('datatables.net-responsive-bs4');
require('jquery-timepicker/jquery.timepicker');

import jsZip from 'jszip';
import 'datatables.net-buttons/js/buttons.colVis.min';
import 'datatables.net-buttons/js/dataTables.buttons.min';
import 'datatables.net-buttons/js/buttons.flash.min';
import 'datatables.net-buttons/js/buttons.print.min';
import 'datatables.net-buttons/js/buttons.html5.min';

window.JSZip = jsZip;

window.Vue = require('vue');
window.moment = require('moment');

import Vue from 'vue'
import VCalendar from 'v-calendar';
import Calendar from 'v-calendar/lib/components/calendar.umd'

// Use v-calendar & v-date-picker components
Vue.use(VCalendar);
Vue.component('vue-calendar', require('./components/vue-calendar.vue').default, Calendar);

const app = new Vue({
    el: '#app',
});





// This is the service worker with the combined offline experience (Offline page + Offline copy of pages)
// Add this below content to your HTML page, or add the js file to your page at the very top to register service worker
// Check compatibility for the browser we're running this in
if ("serviceWorker" in navigator) {
    if (navigator.serviceWorker.controller) {
        //console.log("[PWA Builder] active service worker found, no need to register");
    } else {
        // Register the service worker
        navigator.serviceWorker
            .register("pwabuilder-sw.js", {
                scope: "./"
            })
            .then(function (reg) {
                //console.log("[PWA Builder] Service worker has been registered for scope: " + reg.scope);
            });
    }
}


//Custom close bootstrap alerts
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 2000);


var options = {
    title: '&#x1F36A; Accept Cookies & Privacy Policy?',
    message: 'This website uses essential cookies to perform correctly. You must click the <strong>accept</strong> button to use this website. For more information please see our',
    delay: 600,
    expires: 356,
    link: '/cookie-policy',
    onAccept: function(){
        var myPreferences = $.fn.ihavecookies.cookie();
        console.log('The following preferences were saved...');
        console.log(myPreferences);
    },
    uncheckBoxes: true,
    acceptBtnLabel: 'Accept Cookies',
    moreInfoLabel: 'privacy policy',
    cookieTypesTitle: 'Select which cookies you want to accept',
    fixedCookieTypeLabel: 'Essential',
    fixedCookieTypeDesc: 'These are essential for the website to work correctly.'
};

$(document).ready(function() {
    $('body').ihavecookies(options);

    if ($.fn.ihavecookies.preference('marketing') === true) {
        console.log('This should run because marketing is accepted.');
    }

    $('#ihavecookiesBtn').on('click', function(){
        $('body').ihavecookies(options, 'reinit');
    });
});

$( document ).ready(function() {$('[data-toggle="tooltip"]').tooltip(); });

//Remember scroll position
/*document.addEventListener('DOMContentLoaded', function() {
    var sep = '\uE000'; // an unusual char: unicode 'Private Use, First'

    window.addEventListener('pagehide', function(e) {
        window.name += sep + window.pageXOffset + sep + window.pageYOffset;
    });

    if(window.name && window.name.indexOf(sep) > -1)
    {
        var parts = window.name.split(sep);
        if(parts.length >= 3)
        {
            window.name = parts[0];
            window.scrollTo(parseFloat(parts[parts.length - 2]), parseFloat(parts[parts.length - 1]));
        }
    }
});*/

window.addEventListener('scroll',function() {
    //When scroll change, you save it on localStorage.
    localStorage.setItem('scrollPosition',window.scrollY);
},false);


window.addEventListener('load',function() {
    if(localStorage.getItem('scrollPosition') !== null)
        window.scrollTo(0, localStorage.getItem('scrollPosition'));
},false);




$(document).ready(function(){
    $('.toast').show(500, function(){
        setTimeout(function(){ console.log('hey'); $('.toast').hide(500); }, 7000);
    });
    $('.close-toast').click(function(){
        $('.toast').hide(500);
    });
});

//Hide loader
$(document).ready(function() {
    $( "#load" ).hide();
});

//Hide/show calendar
$(document).ready(function() {
    $('#calendar_show, #calendar_close').on('click', function() {
        $('#calendar_box').fadeToggle(500);
    });
});


