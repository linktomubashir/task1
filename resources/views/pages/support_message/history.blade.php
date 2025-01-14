<div class="modal-body">
    <table class="table table-bordered table-striped" id="support-ticket-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Reply Message</th>
                <th>Replied At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $data)
                <tr>
                    <td>{{ $data->user->name }}</td>
                    <td data-toggle="tooltip" title="{{ $data->message }}">
                        {{ Str::limit($data->message, 50) }}
                    </td>
                    <td>{{ $data->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
