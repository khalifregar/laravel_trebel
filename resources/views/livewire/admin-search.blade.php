<div>
    @if (isSuperadmin())
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Created</h4>

                {{-- 🔍 Search Bar --}}
                <ul class="navbar-nav w-100">
                    <li class="nav-item w-100">
                        <div class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                            <input
                                type="text"
                                class="form-control"
                                wire:model.debounce.300ms="search"
                                placeholder="Search by username, email, or status (Active / Belum Login)">
                        </div>
                    </li>
                </ul>

                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th> Username </th>
                                <th> Email </th>
                                <th> Role </th>
                                <th> Created At </th>
                                <th> Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Jika ada admin baru dibuat (via session flash) --}}
                            @if (session('created_admin'))
                                @php $admin = session('created_admin'); @endphp
                                <tr>
                                    <td>
                                        <div class="form-check form-check-muted m-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input">
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="image" />
                                        <span class="pl-2">{{ $admin['username'] }}</span>
                                    </td>
                                    <td>{{ $admin['email'] ?? '-' }}</td>
                                    <td>Admin</td>
                                    <td>{{ \Carbon\Carbon::parse($admin['created_at'])->format('d M Y') }}</td>
                                    <td>
                                        <div class="badge badge-outline-success">Created</div>
                                    </td>
                                </tr>
                            @endif

                            {{-- Semua admin yang terfilter --}}
                            @forelse ($filteredAdmins as $admin)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-muted m-0">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input">
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{ asset('assets/images/faces/face2.jpg') }}" alt="image" />
                                        <span class="pl-2">{{ $admin->username }}</span>
                                    </td>
                                    <td>{{ $admin->email }}</td>
                                    <td>Admin</td>
                                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if ($admin->last_login_at)
                                            <div class="badge badge-success">Sudah Login</div>
                                        @else
                                            <div class="badge badge-outline-warning">Belum Login</div>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No admin found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
