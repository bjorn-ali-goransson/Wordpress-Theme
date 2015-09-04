var $ = jQuery;
var app = angular.module('app', []);



/* MAIN CONTROLLER */

app.controller('Main', function ($scope) {

});



/* NG SCOPE */

app.directive('ngScope', function () {
  return {
    scope: true
  };
});



/* HTTP MODEL */

app.directive('httpModel', function ($http) {
    return {
        controller: $.noop,
        link: function (scope, element, attributes, controller) {
            if (!attributes.propertyName) {
                var propertyName = attributes.httpModel.substr(attributes.httpModel.lastIndexOf('/') + 1);

                if (propertyName.indexOf('?') != -1) {
                    propertyName = propertyName.substr(0, propertyName.indexOf('?'));
                }

                attributes.propertyName = attributes.$normalize(propertyName);
            }

            var hasLoaded = false;
            var undefined = $.noop();

            controller.reload = function () {
                //if (button) {
                //    button.attr('href', attributes.httpModel);
                //}

                hasLoaded = true;

                return $http({
                    url: attributes.httpModel,
                    method: 'GET'
                })
                .success(function (data) {
                    if (data == 'null') {
                        data = null;
                    }
                    if (data == 'true') {
                        data = true;
                    }
                    if (data == 'false') {
                        data = false;
                    }

                    scope[attributes.propertyName] = data;

                    element.trigger('reload', [data]);
                });
            };

            controller.reload();

            scope.httpModelReload = controller.reload;

            if (attributes.watch) {
                scope.$watch(attributes.watch, function (newValue, oldValue) {
                    if (hasLoaded && newValue === oldValue) {
                        return;
                    }

                    controller.reload();
                });
            }
        }
    };
});



/* ENCODE URI COMPONENT FILTER */

app.filter('encodeUriComponent', function () {
  return function (value) {
    return encodeURIComponent(value);
  };
});



/* NG CLICK PREVENT DEFAULT */

app.directive('ngClick', function () {
    return {
        link: function (scope, element, attributes) {
            element.click(function (event) {
                event.preventDefault();
                event.stopPropagation();
            });
        }
    };
});



/* ERROR HANDLER */

app.factory('errorHandler', function ($q) {
    return {
        responseError: function (response) {
            if (response.status == 401) {
                if (typeof window.loginChoice == 'undefined') {
                    window.loginChoice = confirm('You have been logged out - choose OK to login again.');
                }
                if (window.loginChoice) {
                    location.href = "/Auth/Login?return-url=" + encodeURIComponent(location.href);
                }
            }
            if (response.status == 500) {
                var message;

                if (response && response.data && response.data.ExceptionMessage) {
                    message = response.data.ExceptionMessage;
                } else {
                    message = 'Internal server error';
                }
                
                if (response && response.data && response.data.ExceptionType && response.data.ExceptionType != 'System.Exception') {
                    message += " (" + response.data.ExceptionType + ")";
                }

                alert(message);
            }
            if (response.status === 0) {
                //alert('Server did not respond');
            }

            return $q.reject(response);
        }
    };
});
app.config(function ($httpProvider) {
    $httpProvider.interceptors.push('errorHandler');
});



/* LOADING INDICATOR */

app.factory('loadingIndicator', function ($q) {
    var depth = 0;
    var oldTitle = document.title;

    function setLoading(value) {
        if (value) {
            depth++;
        } else {
            depth--;
        }

        if (depth > 0) {
            document.title = 'Loading ...';
        } else {
            document.title = oldTitle;
        }
    }

    return {
        request: function (config) {
            setLoading(true);
            return config;
        },
        response: function (response) {
            setLoading(false);
            return response;
        },
        responseError: function (response) {
            setLoading(false);
            return $q.reject(response);
        }
    };
});
app.config(function ($httpProvider) {
    $httpProvider.interceptors.push('loadingIndicator');
});



/* OTHER */

