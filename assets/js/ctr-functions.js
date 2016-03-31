;var Checkinator = (function($){

    var $form = $(document.getElementById('check-in'));

    var init = function(){
        /** Add validation */
        jQuery.validator.addMethod("accept", function(value, element, param) {
            return value.match(new RegExp("." + param + "$"));
        });

        $form.validate({
            rules: {
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

            // Specify the validation error messages
            messages: {
                firstName: "Please enter a valid first name",
                lastName: "Please enter a valid last name"
            }
        });

        /** Redirect on success/error */
        var $success = $(document.getElementsByClassName('success-message')),
            $error = jQuery(document.getElementsByClassName('error-message'));

        if( $success.length > 0 || $error.length > 0 ){
            window.setTimeout(function(){
                window.location = window.location.href;
            }, 5000);
        }
    };

    return {
        init: init
    }

})(jQuery);

jQuery(document).ready(function(){
    Checkinator.init();
});