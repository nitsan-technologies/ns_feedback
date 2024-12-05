import "@nitsan/ns-feedback/Datatables.js";

    $('.ns-ext-datatable').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ Records",
            "emptyTable": "No Records Available",
            "zeroRecords": "No matching Records found"
        },
    });

   

    $('.dataTables_length select,\ .dataTables_filter input').addClass('form-control');

    $('#TypoScriptTemplateModuleController').on('submit',function(e){
        e.preventDefault();
        url = $(this).attr('action');
        $.ajax({
            url:url,
            method:'post',
            data:$(this).serializeArray(),
            success:function(){
                window.location.reload();
            }
        })
    });


