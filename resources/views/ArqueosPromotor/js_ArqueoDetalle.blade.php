<script type="text/javascript">
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    $(document).ready(function () {
        var IdArqueo = $("#id_moneda").text();
        let typingTimer;

        

        var ColumnasTable = [
                { "data": "cliente", "title": "CLIENTES DESEMBOLSADOS" },
                { "data": "monto_credito", "title": "MONTO DESEMBOLSADO C$." , render: $.fn.dataTable.render.number( ',', '.', 2  ) },                
                { "data": "comprobante", "title": "N DE COMPROBANTE" },
                { "data": "ruta", "title": " RUTA" }
            ]
    
        getDetalles(IdArqueo).then(function(res) {

            InitDataTable(
                ColumnasTable,
                res.Detalles
            );

            UpdateUI(res.InfoArqueo[0]);
            
        });

        $("#txt_entregado").on("keyup", function(event) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                updateSobrante();
            }, 250);
        });

        $("#bt_save_arqueo").click(function(){      
            SaveForm();
        })

        
    })

    

    async function getDetalles(Arqueo) {
        try {
            
            const response = await fetch("../TableDetalles", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    Arqueo     : Arqueo
                })
            });

            const result = await response.json();

            return result;
            

        } catch (error) {
            console.error(error);
        }
    }

    function SaveForm() {

        var IdArqueo = $("#id_moneda").text();

        var dtIni               = $("#dtIni").val();
        var txt_entregado       = $("#txt_entregado").val();
        var txt_desembolsado    = $("#txt_desembolsado").val();
        var txt_sobrante        = $('#id_sobrante').val();
        var txt_consiliado      = $("#txt_consiliado").val();
        var txt_commit          = $("#id_commit").val();

        dtIni_               = moment(dtIni, 'YYYY-MM-DD');
        txt_entregado_       = numeral(isValue(txt_entregado,0,true)).format('0.00')
        txt_desembolsado_    = numeral(isValue(txt_desembolsado,0,true)).format('0.00')
        txt_sobrante_        = numeral(isValue(txt_sobrante,0,true)).format('0.00')
        txt_consiliado_      = numeral(isValue(txt_consiliado,0,true)).format('0.00')

        $.ajax({
            url: "../UpdateArqueoPromotor",
            data: {
                Arqueo      : IdArqueo,
                Fecha       : dtIni_.format('YYYY-MM-DD'),
                Entregado   : txt_entregado_,
                Desembolso  : txt_desembolsado_,
                Sobrante    : txt_sobrante_,
                Consolido   : txt_consiliado_,
                Commit      : txt_commit,
                _token      : "{{ csrf_token() }}" 
            },
            type: 'post',
            async: true,
            success: function(res) {
                Toast.fire({
                    icon: 'success',
                    title: res.original.message
                });
            },
            error: function(response) {
                swal("Oops", "No se ha podido guardar!", "error");
            }
        }).done(function(data) {
        });
        
    }


    function updateSobrante() {
        var Desembolsado = parseFloat($("#txt_desembolsado").val());
        var Entregado = parseFloat($("#txt_entregado").val());
        
        var Sobrante = Entregado > Desembolsado ? Entregado - Desembolsado : 0;
        var Consiliado = Desembolsado + Sobrante;
        
        $("#id_sobrante").val(Sobrante.toFixed(2));
        $("#txt_consiliado").val(Consiliado.toFixed(2));
    }

    function UpdateUI(infoArqueo) {

        $("#dtIni").val(infoArqueo.fecha);
        $("#txt_entregado").val(infoArqueo.entregado);
        $("#txt_desembolsado").val(infoArqueo.desembolsado);
        $("#id_sobrante").val(infoArqueo.sobrante);
        $("#txt_consiliado").val(infoArqueo.consolidado);
        $("#id_commit").val(infoArqueo.comentario);
        
    }

    function InitDataTable(columns, data = []) {

        // destruir si existe
        if ($.fn.DataTable.isDataTable('#tbl_creditos_arqueo')) {
            $('#tbl_creditos_arqueo').DataTable().destroy();
        }

        // reconstruir thead
        let thead = "<thead><tr>";
        columns.forEach(col => {
            thead += `<th>${col.title || ''}</th>`;
        });

        thead += "</tr></thead>";
        $("#tbl_creditos_arqueo").html(thead + "<tbody></tbody>");

        $("#tbl_creditos_arqueo").DataTable({
            destroy: true,
            data: data,
            columns: columns,
            autoWidth: false,
            lengthChange: false,
            info: false,
            paging: false,
            searching: false,
            ordering: columns.length > 0, // evita error si no hay columnas
            order: columns.length > 0 ? [[0, 'desc']] : [],
            language: {
                zeroRecords: "NO HAY COINCIDENCIAS",
                emptyTable: "NO HAY COINCIDENCIAS",
                paginate: {
                    first: "Primera",
                    last: "Última",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
            
        });
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>