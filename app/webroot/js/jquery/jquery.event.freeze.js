$(function(){

    //ini plugin

    jQuery.event.freezeEvents = function(elem) {

        if (typeof(jQuery._funcFreeze)=="undefined")
                jQuery._funcFreeze = [];

        if (typeof(jQuery._funcNull)=="undefined")
                jQuery._funcNull = function(){ };

        // don't do events on text and comment nodes
        if ( elem.nodeType == 3 || elem.nodeType == 8 )
                return;

        var events = jQuery.data(elem, "events"), ret, index;

        if ( events ) {

                for ( var type in events )
                {
                        if ( events[type] ) {

                                var namespaces = type.split(".");
                                type = namespaces.shift();
                                var namespace = RegExp("(^|\\.)" + namespaces.slice().sort().join(".*\\.") + "(\\.|$)");

                                for ( var handle in events[type] )
                                        if ( namespace.test(events[type][handle].type) ){
                                                if (events[type][handle] != jQuery._funcNull){
                                                        jQuery._funcFreeze["events_freeze_" + handle] = events[type][handle];
                                                        events[type][handle] = jQuery._funcNull;
                                                }
                                        }
                        }

                }
        }
    }

    jQuery.event.unFreezeEvents = function(elem) {

        // don't do events on text and comment nodes
        if ( elem.nodeType == 3 || elem.nodeType == 8 )
                return;

        var events = jQuery.data(elem, "events"), ret, index;

        if ( events ) {

                for ( var type in events )
                {
                        if ( events[type] ) {

                                var namespaces = type.split(".");
                                type = namespaces.shift();

                                for ( var handle in events[type] )
                                        if (events[type][handle]==jQuery._funcNull)
                                                events[type][handle] = jQuery._funcFreeze["events_freeze_" + handle];

                        }
                }
        }
    }

    jQuery.fn.freezeEvents = function() {

        return this.each(function(){
                jQuery.event.freezeEvents(this);
        });

    };

    jQuery.fn.unFreezeEvents = function() {

        return this.each(function(){
                jQuery.event.unFreezeEvents(this);
        });

    };

});
