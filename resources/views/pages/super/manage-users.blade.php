@extends('component.main')

@section('content')
    <div class="pagetitle mb-4">
        <h1>Manage Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('indexDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Manage Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">List Users</h5>
                            <a href="{{ route('super.create-user') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New User
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="usersTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Phone</th>
                                        <th>Created At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-medium">
                                                {{ $user->admin->nama ?? ($user->karyawan->nama ?? 'N/A') }}
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->roles === 'admin' ? 'success' : 'info' }}">
                                                    {{ ucfirst($user->roles) }}
                                                </span>
                                            </td>
                                            <td>{{ $user->admin->no_telp ?? ($user->karyawan->no_telp ?? 'N/A') }}</td>
                                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('super.edit-user', $user->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('super.delete-user', $user->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox display-4"></i>
                                                    <p class="mt-2">No users found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
@endpush
