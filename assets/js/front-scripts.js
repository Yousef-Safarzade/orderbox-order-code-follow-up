jQuery(document).ready(function(){

    handleUploadPaymentDocumentFormSubmit();
    handleFileSelectedEvent();
    handleUploadPaymentDocumentFormReset();
    handleUploadPaymentDocumentFormAcceptButtonClick();

});


function handleUploadPaymentDocumentFormAcceptButtonClick(){

    if( $("#orderboxPaymentUploadForm").length < 1){

        return;

    }

    $("#orderboxPaymentUploadForm #acceptButton").on("click",function (e){

        e.preventDefault();

        const fileURL = $("#paymentDocumentURL").val();

        if( typeof (fileURL) == 'undefined' || fileURL == '' ){

            alert(oofuAjaxData.messages.general_error);

            return;

        }

        let formData = new FormData();

        formData.append("action", "accept_payment_document");

        formData.append("postID", oofuAjaxData.postID);

        formData.append("paymentDocument", fileURL);


        $.ajax({

            url: oofuAjaxData.ajax_url,

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                alert(oofuAjaxData.messages.document_accepted_success);

                window.location.reload();

            },

            error: function (xhr) {

                alert(oofuAjaxData.messages.document_accepted_failed);


            },

        });

    })







}


function handleUploadPaymentDocumentFormReset(){

    if( $("#orderboxPaymentUploadForm").length < 1){

        return;

    }


    $("#orderboxPaymentUploadForm").on("reset",function(e){

        $(".paymentDocumentFileName").html('');

        $("#orderboxPaymentUploadForm input[type='button']").prop('disabled', true);

    })

}


function handleFileSelectedEvent(){

    if( $("#orderboxPaymentUploadForm").length < 1){

        return;

    }

    $("#orderboxPaymentUploadForm #paymentDocument").on("change",function(e){


        const fileName = $("#orderboxPaymentUploadForm #paymentDocument")[0].files[0].name;


        $(".paymentDocumentFileName").html(fileName);


    })


}

function handleUploadPaymentDocumentFormSubmit(){


    if( $("#orderboxPaymentUploadForm").length < 1){

        return;

    }

    $("#orderboxPaymentUploadForm").on("submit",function(e){

        e.preventDefault();

        let file_data = $('#paymentDocument')[0].files;

        if ( file_data.length < 1 ){

            alert(oofuAjaxData.messages.general_error);

            return;

        }


        $("#orderboxPaymentUploadForm input[type='button']").prop('disabled', true);

        let formData = new FormData();

        formData.append("action", "handle_upload_payment_document");

        formData.append("security", oofuAjaxData.nonce);

        formData.append("postID", oofuAjaxData.postID);

        formData.append("paymentDocument", file_data[0]);


        $.ajax({

            url: oofuAjaxData.ajax_url,

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                if( typeof(response.data) != 'undefined' && response.data.length > 0 ){

                    alert(oofuAjaxData.messages.document_uploaded_success);

                    let images = response.data;

                    let htmlContent = '';

                    for(let i=0 ; i < images.length; i++){

                        htmlContent = htmlContent + "<a href='" + images[i]['url'] + "' data-lightbox='" + images[i]['url'] + "' class='payment-document-preview' target='_blank'>پیش نمایش تصویر</a>";

                        $("#paymentDocumentURL").val(images[i]['url']);

                    }

                    $(".orderbox-payment-document-preview").html(htmlContent);

                    $("#orderboxPaymentUploadForm input[type='button']").prop('disabled',false);


                }

            },

            error: function (xhr) {

                alert(oofuAjaxData.messages.document_uploaded_failed);

            },

        });


    });

}