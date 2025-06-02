@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rate Limit Details</h1>
        <a href="{{ route('admin.rate-limits.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rate-limits.show') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="endpoint">Endpoint</label>
                        <input type="text" class="form-control" id="endpoint" name="endpoint" 
                               value="{{ $endpoint }}" placeholder="e.g., /api/login">
                    </div>
                    <div class="col-md-3">
                        <label for="user_id">User ID</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" 
                               value="{{ $userId }}" placeholder="User ID">
                    </div>
                    <div class="col-md-3">
                        <label for="ip_address">IP Address</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address" 
                               value="{{ $ipAddress }}" placeholder="192.168.1.1">
                    </div>
                    <div class="col-md-2">
                        <label for="period">Period (hours)</label>
                        <select class="form-control" id="period" name="period">
                            <option value="1" {{ $period == 1 ? 'selected' : '' }}>1 Hour</option>
                            <option value="6" {{ $period == 6 ? 'selected' : '' }}>6 Hours</option>
                            <option value="24" {{ $period == 24 ? 'selected' : '' }}>24 Hours</option>
                            <option value="168" {{ $period == 168 ? 'selected' : '' }}>7 Days</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Rate Limit Records 
                <span class="text-muted">({{ $records->total() }} total)</span>
            </h6>
        </div>
        <div class="card-body">
            @if($records->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Endpoint</th>
                            <th>User ID</th>
                            <th>IP Address</th>
                            <th>Window Type</th>
                            <th>Attempts</th>
                            <th>Window Start</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr class="{{ $record->attempts > 60 ? 'table-warning' : '' }}">
                            <td>{{ $record->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <code>{{ $record->endpoint }}</code>
                            </td>
                            <td>
                                @if($record->user_id)
                                    <a href="{{ route('admin.rate-limits.show', ['user_id' => $record->user_id, 'period' => $period]) }}" 
                                       class="text-primary">{{ $record->user_id }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($record->ip_address)
                                    <a href="{{ route('admin.rate-limits.show', ['ip_address' => $record->ip_address, 'period' => $period]) }}" 
                                       class="text-primary">{{ $record->ip_address }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $record->window_type }}</span>
                            </td>
                            <td>
                                <strong class="{{ $record->attempts > 60 ? 'text-warning' : 'text-success' }}">
                                    {{ $record->attempts }}
                                </strong>
                            </td>
                            <td>{{ $record->window_start->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @if($record->attempts > 60)
                                    <span class="badge badge-warning">Rate Limited</span>
                                @else
                                    <span class="badge badge-success">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $records->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-search fa-3x mb-3"></i>
                <p>No rate limit records found for the specified criteria.</p>
                <p class="small">Try adjusting your filters or expanding the time period.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Export Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Export Options</h6>
        </div>
        <div class="card-body">
            <p>Export the current filtered data for further analysis.</p>
            <button class="btn btn-success" onclick="exportToCSV()">
                <i class="fas fa-download"></i> Export to CSV
            </button>
            <button class="btn btn-info" onclick="exportToJSON()">
                <i class="fas fa-download"></i> Export to JSON
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportToCSV() {
    const params = new URLSearchParams(window.location.search);
    params.set('format', 'csv');
    window.open('/admin/rate-limits/export?' + params.toString());
}

function exportToJSON() {
    const params = new URLSearchParams(window.location.search);
    params.set('format', 'json');
    window.open('/admin/rate-limits/export?' + params.toString());
}
</script>
@endpush
@endsection
