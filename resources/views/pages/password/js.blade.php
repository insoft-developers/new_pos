<script>


// =====================================
// SHOW / HIDE PASSWORD
// =====================================

function togglePassword(id, button) {

    const input = document.getElementById(id);

    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('mdi-eye-outline');

        icon.classList.add('mdi-eye-off-outline');

    } else {

        input.type = 'password';

        icon.classList.remove('mdi-eye-off-outline');

        icon.classList.add('mdi-eye-outline');

    }

}


// =====================================
// SUBMIT
// =====================================

$("#form-password").submit(function(e) {

    e.preventDefault();

    const btn = $("#btn-password");

    const textAwal = btn.html();

    btn.prop("disabled", true);

    btn.html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Menyimpan...
    `);


    $.ajax({

        url: "{{ url('password_update') }}",

        type: "POST",

        data: $(this).serialize(),

        success: function(response) {

            if (response.success) {

                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: response.message || 'Password berhasil diubah.',

                    confirmButtonText: 'OK'

                }).then(() => {

                    $("#form-password")[0].reset();

                });

            } else {

                Swal.fire(
                    'Gagal!',
                    response.message || 'Password gagal diubah.',
                    'error'
                );

            }

        },

        error: function(xhr) {

            let message =
                'Terjadi kesalahan saat mengubah password.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message = xhr.responseJSON.message;

            }

            Swal.fire(
                'Gagal!',
                message,
                'error'
            );

        },

        complete: function() {

            btn.prop("disabled", false);

            btn.html(textAwal);

        }

    });

});

</script>