$(document).ready(function (e) {

    $('.deleteproduct').on('click',function (e) {
        e.stopPropagation();
        //e.stopImmediatePropagation();
        if(confirm(messages['Sei sicuro?'])) {
            this.click;
            let id = $(this).attr("data-id");
            //console.log('cancella prodotto'+id);
            $(window).attr('location',siteUrl+CoreRequestAction+'/delPro/'+id);
        }
        else
        {
        }       
        e.preventDefault();
    });

    $('.js-plus').on('click',function (e) {
        e.stopPropagation();
        this.click;
        let qty = 1;
        let id = $(this).attr("data-id");
        qty = $('#quantity'+id+'ID').val();
        //console.log('modifica quantita prodotto'+id+' '+qty);
        $(window).attr('location',siteUrl+CoreRequestAction+'/modQtyPro/'+id+'/'+qty);
        e.preventDefault();
    });

    $('.js-minus').on('click',function (e) {
        e.stopPropagation();
        this.click;
        let qty = 1;
        let id = $(this).attr("data-id");
        qty = $('#quantity'+id+'ID').val();
        //console.log('modifica quantita prodotto'+id+' '+qty);
        if (qty > 0) {
            $(window).attr('location',siteUrl+CoreRequestAction+'/modQtyPro/'+id+'/'+qty);
        }
        e.preventDefault();
    });

    $('#goToStep1ID').on('click',function (e) {
        e.stopPropagation();

        $(window).attr('location',siteUrl+'carts1');
        e.preventDefault();
    });


});