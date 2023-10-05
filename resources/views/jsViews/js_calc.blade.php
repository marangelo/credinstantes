<script type="text/javascript">

    $.ajax({
        url: "CalcularEstados",
        type: 'get',
        async: true,
        success: function(response) {

            if(response){
                Swal.fire({
                    title: 'Registro Actualizado Correctamente ' ,
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
            
        }
    }).done(function(data) {

    });


</script>