<script type="text/javascript">
    $(document).ready(function () {

        $('#btn_save').click(function() {

            var payroll_comision            = $('#payroll_comision').val();
            var payroll_dias_trabajados     = $('#payroll_dias_trabajados').val();
            var payroll_num_row             = $('#id_num_row').html();

            var data = {
                payroll_comision_            : payroll_comision,
                payroll_dias_trabajados_     : payroll_dias_trabajados,
                payroll_num_row_             : payroll_num_row,
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




    });

    function AddGastos(e) {
        var name                = isValue(e.employee_full_name,'N/D',true)
        var DiasTrabajados      = numeral(isValue(e.dias_trabajados,0,true)).format('0.00')
        var Comision            = numeral(isValue(e.comision,0,true)).format('0.00')



        $('#id_txt_nombre_empleado').html(name);
        $('#payroll_dias_trabajados').val(DiasTrabajados);
        $('#payroll_comision').val(Comision);


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