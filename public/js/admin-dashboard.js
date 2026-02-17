$(document).ready(function () {
    $('.action-btn').on('click', function () {
        const docId = $(this).data('id');
        const action = $(this).data('action');
        const row = $(`#doc-row-${docId}`);
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: `${action.charAt(0).toUpperCase() + action.slice(1)} Document?`,
            text: `Are you sure you want to ${action} this document?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${action} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/documents/${docId}/${action}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    dataType: 'json',
                    success: function (data) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Fade out row
                        row.fadeOut(500, function () {
                            $(this).remove();

                            // Update pending count
                            const badge = $('.badge.bg-light.text-primary');
                            const currentCount = parseInt(badge.text());
                            badge.text(`${currentCount - 1} Pending`);

                            // Check if no more pending documents
                            if ($('tbody tr').length === 0) {
                                $('.table-responsive').html('<p class="text-muted">No pending documents to review.</p>');
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while processing the request.'
                        });
                    }
                });
            }
        });
    });
});
