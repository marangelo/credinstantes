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

        
            var payroll_dias_trabajados     = $('#payroll_dias_trabajados').val();
            var salario_mensual             = $('#salario_mensual').val();
            var payroll_num_row             = $('#id_num_row').html();
            var payroll_inss                = $('#payroll_inss').val();
            var payroll_ir                  = $('#payroll_ir').val();
            var id_PayRoll_status           = $('#PayRoll_status').html();
            var PayRoll_type                = $('#PayRoll_type').html();

            var data = {
                payroll_dias_trabajados_     : payroll_dias_trabajados,
                payroll_num_row_             : payroll_num_row,
                salario_mensual_             : salario_mensual,
                payroll_inss_                : payroll_inss,
                payroll_ir_                  : payroll_ir,
                id_PayRoll_status_           : id_PayRoll_status,
                PayRoll_type_                : PayRoll_type,
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
        var DiasTrabajados      = numeral(isValue(e.dias_trabajados,0,true)).format('0.00')
        var SalarioMensual      = numeral(isValue(e.salario_mensual,0,true)).format('0.00')
        
        var salarioQuincenal    = SalarioMensual / 2
        var inss                = numeral((salarioQuincenal * 0.07)).format('0.00')
        var ir                  = numeral((salarioQuincenal * 0.215)).format('0.00')

        console.log(ir,inss)



        $('#id_txt_nombre_empleado').html(name);
        $('#payroll_dias_trabajados').val(DiasTrabajados);
        $('#salario_mensual').val(SalarioMensual);
        $('#payroll_inss').val(inss);
        $('#payroll_ir').val(ir);

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