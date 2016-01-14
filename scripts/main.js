var $ = jQuery;
var $window = $(window);
var $body;

$(function () { $body = $(document.body); });
$(function () { $body.addClass('dom-loaded'); });
$window.load(function () { $body.addClass('images-loaded'); });

var app = angular.module('app', []);



/* MAIN CONTROLLER */

app.controller('Main', function ($scope) {

});



/* OTHER */

