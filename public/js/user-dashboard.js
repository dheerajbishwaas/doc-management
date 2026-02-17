$(document).ready(function () {
    $('.delete-form').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);

        Swal.fire({
            title: 'Delete Document?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form[0].submit();
            }
        });
    });
});
