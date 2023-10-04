
$("#chance_password").click(function(){
  Swal.fire({
    title: 'Cambiar contraseña',
    focusConfirm: false,
    html: `
      <input class="swal2-input" id="currentPassword" type="password" placeholder="Contraseña actual..." /><br />
      <input class="swal2-input" id="newPassword1" type="password" placeholder="Nueva contraseña..." /><br />
      <input class="swal2-input" id="newPassword2" type="password" placeholder="Confirme nueva contraseña..." />
    `,
    type: 'warning',
    showCancelButton: true,
    cancelButtonColor: 'grey',
    confirmButtonText: 'Actualizar!',
    allowOutsideClick: false,
    preConfirm: () => {
      const currentPassword = $("#currentPassword").val();
      const newPassword1 = $("#newPassword1").val();
      const newPassword2 = $("#newPassword2").val();

      if (newPassword1 !== newPassword2) {
        Swal.showValidationMessage('Contraseña no Coincide!');
      } else if (currentPassword && newPassword1 && newPassword2) {
        return {
          currentPassword: currentPassword,
          newPassword1: newPassword1,
          newPassword2: newPassword2
        };
      } else {
        Swal.showValidationMessage('Todos los campos Son Requeridos!');
      }
    }
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: 'updatePassword', 
        type: 'POST',
        data: {
          currentPassword: result.value.currentPassword,
          newPassword: result.value.newPassword1,
          _token  : $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.original.success) {
            Swal.fire('Contraseña Actualizada!', '', 'success');
          } else {
            Swal.fire('Contraseña no Actualizada', '', 'error');
          }
        },
        error: function() {
          Swal.fire('Error al Actualizar Contraseña', '', 'error');
        }
      });
    }
  });
});

function isValue(value, def, is_return) {
    if ( $.type(value) == 'null'
        || $.type(value) == 'undefined'
        || $.trim(value) == '(en blanco)'
        || $.trim(value) == ''
        || ($.type(value) == 'number' && !$.isNumeric(value))
        || ($.type(value) == 'array' && value.length == 0)
        || ($.type(value) == 'object' && $.isEmptyObject(value)) ) {
        return ($.type(def) != 'undefined') ? def : false;
    } else {
        return ($.type(is_return) == 'boolean' && is_return === true ? value : true);
    }
}