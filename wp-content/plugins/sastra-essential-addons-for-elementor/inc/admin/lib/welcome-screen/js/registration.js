(function($){

    $(document).on('submit', '.tmpcoder-reg-tabs .reg-form', function(e){
        e.preventDefault();        
        //var formdata = new FormData(this);
        var parentRef = $(this);
        var formdata = $(this).serialize();
        /* for (const pair of formdata.entries()) {
            console.log(`${pair[0]}, ${pair[1]}`);
        } */

        $.ajax({
            type: 'POST',
            data: formdata,
            url: ajaxurl,
            processData:false,
            beforeSend(){
                parentRef.addClass('loading');
            },
            success: function( json ) {
              
              parentRef.removeClass('loading');

              if ( json.success ){
                if ( json.data.type == 'invalid' ){
                    
                     if ( parentRef.find('.tmpcoder-error').length > 0 ){
                        parentRef.find('.tmpcoder-error').remove();
                    }
                    parentRef.find('.reg-input-wrapper').after('<div class="tmpcoder-notice tmpcoder-error">'+ json.data.message + '</div>'); 
                    $("#tmpcoder-reg-tabs").load(location.href + " #tmpcoder-reg-tabs>*");
                }
                else if ( json.data.type == 'reset' ){
                    $("#tmpcoder-reg-tabs").load(location.href + " #tmpcoder-reg-tabs>*");
                    
                }else{
                    $("#tmpcoder-reg-tabs").load(location.href + " #tmpcoder-reg-tabs>*");
                }
              }else{
                if ( json.data ){
                    if ( json.data.message ){
                        parentRef.find('.reg-input-wrapper').after('<div class="tmpcoder-notice tmpcoder-error">'+ json.data.message + '</div>');
                    }
                }
              }
            },
            /**
             * Throw errors
             *
             * @param jqXHR
             * @param textStatus
             * @param errorThrown
             */
            error: function( jqXHR, textStatus, errorThrown ) {
              console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
            }
        });

    });

})(jQuery);