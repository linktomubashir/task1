<!-- Modal for Price History -->
<div class="modal fade" id="itemHistoryModal" tabindex="-1" aria-labelledby="itemHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemHistoryModalLabel">Price History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Old Price</th>
                            <th>New Price</th>
                            <th>Changed At</th>
                        </tr>
                    </thead>
                    <tbody id="price-history-body">
                        <tr>
                            <td colspan="3" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function itemHistory(itemId) {
        $('#price-history-body').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
        $('#itemHistoryModal').modal('show');

        $.ajax({
            url: `/api/price-history/${itemId}`,
            method: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    let rows = '';
                    data.forEach(item => {
                        rows += `
                            <tr>
                                <td>${item.old_price}</td>
                                <td>${item.new_price}</td>
                                <td>${new Date(item.changed_at).toLocaleString('en-GB')}</td>
                            </tr>
                        `;
                    });
                    $('#price-history-body').html(rows);
                } else {
                    $('#price-history-body').html('<tr><td colspan="3" class="text-center">No price history available.</td></tr>');
                }
            },
            error: function() {
                $('#price-history-body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load price history.</td></tr>');
            }
        });
    }
</script>
@endpush
