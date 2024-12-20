<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped" id="sms-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>To</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($smsMessages as $sms)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sms->to }}</td>
                            <td>{{ Str::limit($sms->message, 50) }}...</td>
                            <td>{{ $sms->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info toggle-content" data-id="{{ $sms->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
    
                        <tr class="sms-content" id="content-{{ $sms->id }}" style="display: none;">
                            <td colspan="5">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>To:</strong> {{ $sms->to }}
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Message:</strong></p>
                                        <div>{{ $sms->message }}</div> 
                                        <p><strong>Sent At:</strong> {{ $sms->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.toggle-content').click(function() {
            let smsId = $(this).data('id');
            let row = $('#content-' + smsId);
            row.toggle();
        });
    });
</script>
