<script type="text/javascript">
$(document).ready(function () {
    
    initializeDataTable('#tbl_payrolls');
    var Selectors = {
        MODAL_REQUEST: '#modal_new_request',        
    };



    $("#btn-add-nomina").click(function(){
        var obj = document.querySelector(Selectors.MODAL_REQUEST);
        var modal = new window.bootstrap.Modal(obj);
        $("#id_form").text("0");
        modal.show();
    });

    $('#btn_save_abono').click(function () {
        SaveData();
    });

});


function initializeDataTable(id) {
    $(id).DataTable({
        "destroy": true,
        "info": false,
        "bPaginate": true,
        "responsive": true, 
        "order": [
            [0, "asc"]
        ],
        "lengthMenu": [
            [7, -1],
            [7, "Todo"]
        ],
        "language": {
            "zeroRecords": "NO HAY COINCIDENCIAS",
            "paginate": {
                "first": "Primera",
                "last": "Última ",
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "-",
            "search": "BUSCAR"
        },
        });

        $(id+"_length").hide();
        $(id+"_filter").hide();
}



function SaveData() {

    var FechaInicio     = $('#IdFechaInicio').val();
    var FechaFinal      = $('#IdFechaFinal').val();
    var inatec          = $('#payroll_inactec').val();
    var inss_patronal   = $('#payroll_inss_patronal').val();
    var payroll_type    = $('#payroll_type').val();
    var payroll_observation = $('#payroll_observation').val();


    $.ajax({
        url: "SavePayroll",
        data: {
            payroll_date_ini_       : FechaInicio,
            payroll_date_end_       : FechaFinal,
            payroll_inactec_        : inatec,
            payroll_inss_patronal_  : inss_patronal,
            payroll_type_           : payroll_type,
            payroll_observation_    : payroll_observation,
            _token  : "{{ csrf_token() }}" 
        },
        type: 'post',
        async: true,
        success: function(response) {
            Swal.fire({
                title: 'Guardado Correctamente' ,
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
                }
            })
        },
        error: function(response) {
            Swal.fire({
                title: 'No se ha podido guardar' ,
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
                }
            })
        }
    }).done(function(data) {
        //location.reload();
    });
}
function Remover(id){
    Swal.fire({
        title: '¿Estas Seguro de remover el registro  ?',
        text: "¡Se removera la informacion permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            $.ajax({
                url: "RemovePayroll",
                data: {
                    id_  : id.id_payrolls,
                    _token  : "{{ csrf_token() }}" 
                },
                type: 'post',
                async: true,
                success: function(response) {
                    if(response){
                        Swal.fire({
                            title: 'Registro Removido Correctamente ' ,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                                }
                            })
                        }
                    },
                error: function(response) {
                    //Swal.fire("Oops", "No se ha podido guardar!", "error");
                }
                }).done(function(data) {
                    //CargarDatos(nMes,annio);
                    location.reload();
                });
            },
        allowOutsideClick: () => !Swal.isLoading()
    });

}


</script>