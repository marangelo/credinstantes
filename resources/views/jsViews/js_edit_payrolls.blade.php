<script type="text/javascript">
    $(document).ready(function () {




    });

    function AddGastos(e) {
        $('#id_txt_nombre_empleado').html(e.employee.first_name + ' ' + e.employee.last_name);
        var obj = document.querySelector('#modal_add_gasto');
        var modal = new window.bootstrap.Modal(obj);
        modal.show();
    }
</script>