@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rate Limiting Dashboard</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.rate-limits.index', ['period' => 1]) }}" 
               class="btn btn-outline-primary {{ $period == 1 ? 'active' : '' }}">1H</a>
            <a href="{{ route('admin.rate-limits.index', ['period' => 6]) }}" 
               class="btn btn-outline-primary {{ $period == 6 ? 'active' : '' }}">6H</a>
            <a href="{{ route('admin.rate-limits.index', ['period' => 24]) }}" 
               class="btn btn-outline-primary {{ $period == 24 ? 'active' : '' }}">24H</a>
            <a href="{{ route('admin.rate-limits.index', ['period' => 168]) }}" 
               class="btn btn-outline-primary {{ $period == 168 ? 'active' : '' }}">7D</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_requests']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rate Limited
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['rate_limited_requests']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Unique Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['unique_users']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Unique IPs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['unique_ips']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Request Volume Over Time</h6>
                </div>
                <div class="card-body">
                    <canvas id="requestChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Top Endpoints -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Endpoints by Volume</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Total Requests</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topEndpoints as $endpoint)
                                <tr>
                                    <td>{{ $endpoint->endpoint }}</td>
                                    <td>{{ number_format($endpoint->total_attempts) }}</td>
                                    <td>
                                        <a href="{{ route('admin.rate-limits.show', ['endpoint' => $endpoint->endpoint, 'period' => $period]) }}" 
                                           class="btn btn-sm btn-outline-primary">View Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rate Limited Endpoints -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Rate Limited Endpoints</h6>
                </div>
                <div class="card-body">
                    @if($rateLimitedEndpoints->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Endpoint</th>
                                    <th>Violations</th>
                                    <th>Max Attempts</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rateLimitedEndpoints as $endpoint)
                                <tr>
                                    <td>{{ $endpoint->endpoint }}</td>
                                    <td>{{ number_format($endpoint->violations) }}</td>
                                    <td>{{ number_format($endpoint->max_attempts) }}</td>
                                    <td>
                                        <a href="{{ route('admin.rate-limits.show', ['endpoint' => $endpoint->endpoint, 'period' => $period]) }}" 
                                           class="btn btn-sm btn-outline-warning">View Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>No rate limiting violations in the selected period.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cleanup Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Database Cleanup</h6>
                </div>
                <div class="card-body">
                    <p>Clean up old rate limit records to improve performance.</p>
                    <button class="btn btn-danger" onclick="cleanupOldRecords()">
                        <i class="fas fa-trash"></i> Cleanup Old Records (7+ days)
                    </button>
                    <div id="cleanup-result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js configuration
const ctx = document.getElementById('requestChart').getContext('2d');
const timeSeriesData = @json($timeSeriesData);

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: timeSeriesData.map(item => item.hour),
        datasets: [{
            label: 'Total Requests',
            data: timeSeriesData.map(item => item.total_requests),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }, {
            label: 'Rate Limit Violations',
            data: timeSeriesData.map(item => item.violations),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Cleanup function
function cleanupOldRecords() {
    if (!confirm('Are you sure you want to delete old rate limit records? This action cannot be undone.')) {
        return;
    }
    
    fetch('/admin/rate-limits/cleanup?confirm=true', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('cleanup-result').innerHTML = 
            `<div class="alert alert-success">${data.message}</div>`;
    })
    .catch(error => {
        document.getElementById('cleanup-result').innerHTML = 
            `<div class="alert alert-danger">Error: ${error.message}</div>`;
    });
}

// Auto-refresh every 30 seconds
setInterval(() => {
    window.location.reload();
}, 30000);
</script>
@endpush
@endsection
