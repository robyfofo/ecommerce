/* admin/faq/listItems.js v.4.5.1. 20/11/2018 */

$(document).ready(function () {
    manageTableCheckList();
    doTableCheckListActionsID()
});

function manageTableCheckList() {
    $('.tableCheckList').each(function (element) {
        //console.log( index + ": " + $( this ).text() );
        // console.log( element + ": " + $( this ).text() );
        $(this).change(function () {
            let id = $(this).attr('id');
            let dataid = $(this).attr('data-id');
            console.log('cambiato ' + id + ' -> ' + dataid);
            if ($('#' + id).is(":checked")) {
                //console.log('imoposta '+'#' + id+' come uncheked');
                $.ajax({
                    url: siteUrl + "ajax/upgradePhpMultiSessionVar.php",
                    method: "POST",
                    data: {
                        'keysession': 'brands',
                        'keylevel': 'checklist',
                        'value': dataid,
                        'action': 'addvalueinarray'
                    }
                }).done(function () {
                });
            } else {
                //console.log('imoposta '+'#' + id+' come cheked');
                $.ajax({
                    url: siteUrl + "ajax/upgradePhpMultiSessionVar.php",
                    method: "POST",
                    data: {
                        'keysession': 'brands',
                        'keylevel': 'checklist',
                        'value': dataid,
                        'action': 'removevalueinarray'
                    }
                }).done(function () {
                });
            }
        });

    });

}

function doTableCheckListActionsID() {
    $('form #tableCheckListActionsButtonID').click(function (e) {
        //e.preventDefault();
        //console.log('click');
        let action = $('#tableCheckListActionsID').val();
        console.log(action);
        if (action != '') {
            let form = $(this.form);
            let url = form.attr('action');
            url = url + '/' + action;
            
            //console.log('prosegui azione');
            bootbox.confirm(messages['Sei sicuro?'], function (confirmed) {
                if (confirmed) {
                    
                   
                    console.log('invia a. '+url);
                    form.attr('action', url).submit();
                }
            });
        }
        return false;
    })


}