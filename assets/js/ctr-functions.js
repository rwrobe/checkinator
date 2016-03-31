;var Checkinator = (function($){

    var form = document.getElementById('check-in'),
        $form = $(form);

    var init = function(){
        form_init();
        /** Add validation */
        $(window).load(function(){
            validator();
        });
    };

    var form_init = function(){

        /** Redirect on success/error */
        var $success = $(document.getElementsByClassName('ctr-success-message')),
            $error = jQuery(document.getElementsByClassName('ctr-error-message'));

        if( $success.length > 0 || $error.length > 0 ){
            window.setTimeout(function(){
                window.location = window.location.href;
            }, 3000);
        }
    };

    var validator = function(){
        jQuery.validator.addMethod("accept", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
        });

        $('#check-in').validate({
            rules: {
                firstName: "required",
                lastName: "required",

                firstName: {
                    required: true,
                    minlength: 2,
                    maxlength: 20,
                    accept: "[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$"
                },
                lastName: {
                    required: true,
                    minlength: 2,
                    maxlength: 20,
                    accept: "[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$"
                }
            },
        });
    };

    return {
        init: init
    }

})(jQuery);

jQuery(document).ready(function(){
    Checkinator.init();
});