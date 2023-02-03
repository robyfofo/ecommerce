$(document).ready(function (e) {

    $('.deletewhishes').on('click',function (e) {
        e.stopPropagation();
        //e.stopImmediatePropagation();
        if(confirm(messages['Sei sicuro?'])) {
            return true;
        }
        return false;
        e.preventDefault();
    });

    $('.deleteproduct').on('click',function (e) {
        e.stopPropagation();
        //e.stopImmediatePropagation();
        if(confirm(messages['Sei sicuro?'])) {
            this.click;
            let id = $(this).attr("data-id");
            $.ajax({
                method: "POST",
                url: siteUrl+CoreRequestAction+'/delProAjax',
                data: { 'products_id': id },
                format: "json"
            })
            .done(function(data) {
                var obj = jQuery.parseJSON( data );              
                if (obj.result == 1) {
                    $('#wishes-tableID tr#'+id).remove();
                    alert( messages['prodotto cancellato'] );
                    if (obj.status == 'empty') {
                       $(window).attr('location',siteUrl+CoreRequestAction);
                    }
                }                          
            })
            .fail(function() {
                alert( "Error get request ajax delete product");
            });      
            return false;
        }
        return false;
        e.preventDefault();
    });

    
    $('.js-plus').on('click',function (e) {
        e.stopPropagation();
        this.click;
        let qty = 1;
        let id = $(this).attr("data-id");
        qty = $('#quantity'+id+'ID').val();
        if (qty > 0) {
            modifyQuantityProduct(qty,id);
        }
        e.preventDefault();
    });

    $('.js-minus').on('click',function (e) {
        e.stopPropagation();
        this.click;
        let qty = 1;
        let id = $(this).attr("data-id");
        qty = $('#quantity'+id+'ID').val();
        //console.log('sottrae quantita prodotto'+id+' '+qty);
        if (qty > 0) {
            modifyQuantityProduct(qty,id);
        }
        e.preventDefault();
    });

    function modifyQuantityProduct(quantity,products_id)
    {
        $.ajax({
            method: "POST",
            url: siteUrl+CoreRequestAction+'/modQtyProAjax',
            data: { 'quantity': quantity,'products_id': products_id },
            format: "json"
        })
        .done(function(data) {
            var obj = jQuery.parseJSON( data ); 
            if (obj.result == 1) {
                if (obj.price_total != '') {
                    $('#price_total'+products_id+'ID').html(obj.price_total);  
                }
            }
        })
        .fail(function() {
            alert( "Error get request ajax delete product");
        });      
    }
    

});
