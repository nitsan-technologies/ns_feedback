define([
    'jquery',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/NsFeedback/Main',
    'TYPO3/CMS/NsFeedback/Datatables',
    'TYPO3/CMS/Backend/Input/Clearable'
], function ($, Model) {
 
    $('.ns-ext-datatable').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ Records",
            "emptyTable": "No Records Available",
            "zeroRecords": "No matching Records found"
        },
    });

    $('.field-info-trigger').on('click', function(){
        $(this).parents('.form-group').find('.field-info-text').slideToggle();
    });
    $('[data-toggle="tooltip"]').tooltip();

    $('.dataTables_length select,\ .dataTables_filter input').addClass('form-control');

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        e.preventDefault();
        let url = $(this).attr('action');
        $.ajax({
            url:url,
            method:'post',
            data:$(this).serializeArray(),
            success:function(){
                window.location.reload();
            }
        })
    });

});
