<script>
loadProfile();
function loadProfile()
{
    $.ajax({
        url: "{{ url('profile_data') }}",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $("#kd_pengguna").val(data.kd_pengguna);
            $("#nama").val(data.nama);
            $("#nm_pengguna").val(data.nm_pengguna);
            $("#alamat").val(data.alamat);
            $("#telepon").val(data.telepon);
            $("#level").val(data.level);
        }
    })
}

$("#form-profil").submit(function(e) {

    e.preventDefault();

    const btn = $("#btn-simpan");

    const textAwal = btn.html();

    btn.prop("disabled", true);

    btn.html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Menyimpan...
    `);


    $.ajax({

        url: "{{ url('profile_update') }}",

        type: "POST",

        data: $(this).serialize(),

        success: function(response) {

            if (response.success) {

                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: response.message || 'Profil berhasil diperbarui.',

                    confirmButtonText: 'OK'

                }).then(() => {

                    window.location.reload();

                });

            } else {

                Swal.fire(
                    'Gagal!',
                    response.message || 'Gagal menyimpan profil.',
                    'error'
                );

            }

        },

        error: function(xhr) {

            let message =
                'Terjadi kesalahan saat menyimpan profil.';

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