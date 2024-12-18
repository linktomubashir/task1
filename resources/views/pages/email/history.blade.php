
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped" id="item-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>To</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emails as $email)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $email->to }}</td>
                            <td>{{ $email->subject }}</td>
                            <td>{{ $email->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info toggle-content" data-id="{{ $email->id }}"> <i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
    
                        <tr class="email-content" id="content-{{ $email->id }}" style="display: none;">
                            <td colspan="5">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Subject:</strong> {{ $email->subject }}
                                    </div>
                                    <div class="card-body">
                                        <p><strong>To:</strong> {{ $email->to }}</p>
                                        <p><strong>Message:</strong></p>
                                        <div>{{ $email->message }}</div> 
                                        
                                        {{-- @if ($email->attachment)
                                            <p><strong>Attachment:</strong> 
                                                <a href="{{ asset('storage/' . $email->attachment) }}" target="_blank">View Attachment</a>
                                            </p>
                                        @endif --}}
    
                                        <p><strong>Sent At:</strong> {{ $email->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <script>
            $(document).ready(function() {
                $('.toggle-content').click(function() {
                    let emailId = $(this).data('id'); 
                    let row = $('#content-' + emailId); 
                    row.toggle();
                });
            });
        </script>