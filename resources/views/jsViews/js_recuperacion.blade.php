<script type="text/javascript">
    $(document).ready(function () {

        $('#dt-ini,#dt-end').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        Init()

       

    })

    function Init() {

        var dtEnd   = $("#dtEnd").val();    
        var dtIni   = $("#dtIni").val(); 

        dtEnd      = isValue(dtEnd,'N/D',true) 
        dtIni      = isValue(dtIni,'N/D',true) 

        const dt_Ini = moment(dtIni, 'DD/MM/YYYY');
        const dt_End = moment(dtEnd, 'DD/MM/YYYY');

        var lbl_titulo_reporte = 'Del ' + dt_Ini.format('ddd, MMM DD, YYYY') + ' Al ' + dt_End.format('ddd, MMM DD, YYYY');
        dt_Ini_ = dt_Ini.format('YYYY-MM-DD');
        dt_End_ = dt_End.format('YYYY-MM-DD');
        $("#lbl_titulo_reporte").text(lbl_titulo_reporte)


        $.ajax({
            url: "CalcRecuperacion",
            type: 'post',
            data: {
                dtIni   : dt_Ini_,
                dtEnd   : dt_End_,
                _token  : "{{ csrf_token() }}" 
            },
            async: true,
            success: function(response) {
                if(response){
                    
                    $("#IdCalcRecuperacion").text(numeral(isValue(response,0,true)).format('0,00.00'))

                }
            },
            error: function(response) {
                Swal.fire("Oops", "No se ha podido guardar!", "error");
            }
        }).done(function(data) {
            //location.reload();
        });








    }

</script>