<script type="text/javascript">
    $(document).ready(function () {

        InitTable();

        $("#btn_process_payroll").click(function() {
            var IdPayroll           = $("#id_description").html();
            var neto_pagar_payroll  = $("#neto_pagar_payroll").html();            
            var Type_PayRoll        = $('#PayRoll_status').html();

            var data = {
                idPayroll_          : IdPayroll,
                neto_pagar_payroll_ : neto_pagar_payroll,
                Type_PayRoll_       : Type_PayRoll,
                _token      : "{{ csrf_token() }}"
            };

            $.ajax({
                url: '../ProcessPayroll',
                method: 'POST',
                data: data,
                success: function(response) {
                    Swal.fire({
                        title: 'Processaro Correctamente ' ,
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
                error: function(xhr, status, error) {
                    // Handle error response
                }
            });
        });

        $('#btn_save').click(function() {

        
            var comisiones          = $('#txt_comisiones').val();           
            var payroll_num_row     = $('#id_num_row').html();
            var PayRoll_type   = $('#PayRoll_type').html();

            
            var data = {
                comisiones_     : comisiones,
                payroll_num_row_             : payroll_num_row,
                PayRoll_type_   : PayRoll_type,
                _token  : "{{ csrf_token() }}" 
            };

            $.ajax({
                url: '../UpdatePayroll',
                method: 'POST',
                data: data,
                success: function(response) {
                    Swal.fire({
                        title: 'Correcto ' ,
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
                error: function(xhr, status, error) {
                    // Handle error response
                }
            });
            // Rest of your code here
        });

        $('#btn_export_payroll').click(function() {
            var idPayroll   = $("#id_description").html();            
            var TypePayRoll     = $("#PayRoll_type").html();
            
            window.location.href = "../ExportPayroll?" + $.param({ id_Payroll: idPayroll, TypePayRoll: TypePayRoll}); 
        });

        $('#id_txt_buscar').on('keyup', function() {   
            var vTableArticulos = $('#tbl_payroll_details').DataTable();     
            vTableArticulos.search(this.value).draw();
        });




    });

    function InitTable() {
        $("#tbl_payroll_details").DataTable({
            "lengthChange": false, 
            "destroy": true,
            "autoWidth": false,
            "info": false,
            "paging": false,
            order: [[0, 'desc']],
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
            }
        })

        $("#tbl_payroll_details_length").hide();
        $("#tbl_payroll_details_filter").hide();
    }

    function AddGastos(e) {
        var name                = isValue(e.employee_full_name,'N/D',true)
        var Comisiones      = numeral(isValue(e.comision,0,true)).format('0.00');



        $('#id_txt_nombre_empleado').html(name);
        $('#txt_comisiones').val(Comisiones);

        $('#id_num_row').html(e.id_payroll_details);

        var obj = document.querySelector('#modal_add_gasto');
        var modal = new window.bootstrap.Modal(obj);
        modal.show();
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>