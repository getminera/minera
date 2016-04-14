'use strict';

/*********************
//
// Various functions
//
*********************/
/*jshint browser: true */
/*globals jQuery */
;(function (factory) {
    if (typeof exports === 'object') {
        factory(require('jquery'));
    } else if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else {
        factory(jQuery);
    }
}(function ($) {
    /**
     * Triggers the DOM changed event on the given element
     *
     * @private
     * @param   {Object}    element     the jQuery element that has been modified
     * @param   {String}    type        jQuery method used to trigger manipulation
     */
    function jQueryDOMChanged (element, type) {
        return $(element).trigger('DOMChanged', type);
    }
    /**
     * Wraps a given jQuery method and injects another function to be called
     *
     * @private
     * @param   {String}    method
     * @param   {Function}  caller
     */
    function jQueryHook (method, caller) {
        var definition = $.fn[method];
        if (definition) {
            $.fn[method] = function () {
                var args   = Array.prototype.slice.apply(arguments);
                var result = definition.apply(this, args);
                caller.apply(this, args);
                return result;
            };
        }
    }
    jQueryHook('prepend', function () {
        return jQueryDOMChanged(this, 'prepend');
    });
    jQueryHook('append', function () {
        return jQueryDOMChanged(this, 'append');
    });
    jQueryHook('before', function () {
        return jQueryDOMChanged($(this).parent(), 'before');
    });
    jQueryHook('after', function () {
        return jQueryDOMChanged($(this).parent(), 'after');
    });
    jQueryHook('html', function (value) {
        // Internally jQuery will set strings using innerHTML
        // otherwise will use append to insert new elements
        // Only trigger on string types to avoid doubled events
        if (typeof value === 'string') {
            return jQueryDOMChanged(this, 'html');
        }
    });
}));
