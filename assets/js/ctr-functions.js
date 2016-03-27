;var Checkinator = (function($){

    var init = function(){
        /** Theme functions here */
        console.log('loaded');
    };

    return {
        init: init
    }

})(jQuery);

jQuery(document).ready(function(){
    Checkinator.init();
});