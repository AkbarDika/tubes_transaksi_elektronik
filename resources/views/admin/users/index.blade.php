@extends('layouts.admin')

@section('content')

<div class="page-title">
    <i class="bi bi-people"></i>
    <h2>Kelola User</h2>
</div>

<div class="admin-card">

    <button class="btn btn-primary btn-sm mb-3"
        data-bs-toggle="modal"
        data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle"></i> Tambah User
    </button>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>No HP</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->role_id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->no_hp }}</td>
                <td>
                    <button class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#edit{{ $u->id }}">
                        Edit
                    </button>

                    <form action="{{ route('user.destroy',$u->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus user ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>

            {{-- MODAL EDIT --}}
            <div class="modal fade" id="edit{{ $u->id }}">
                <div class="modal-dialog">
                    <form method="POST"
                        action="{{ route('user.update',$u->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>Edit User</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="text" name="name"
                                    class="form-control mb-2"
                                    value="{{ $u->name }}">

                                <input type="text" name="username"
                                    class="form-control mb-2"
                                    value="{{ $u->username }}">

                                <input type="email" name="email"
                                    class="form-control mb-2"
                                    value="{{ $u->email }}">

                                <input type="password" name="password"
                                    class="form-control mb-2"
                                    placeholder="Password baru (opsional)">

                                <textarea name="alamat"
                                    class="form-control mb-2">{{ $u->alamat }}</textarea>

                                <input type="text" name="no_hp"
                                    class="form-control mb-2"
                                    value="{{ $u->no_hp }}">

                                <select name="role_id" class="form-control">
                                    <option value="1" {{ $u->role_id==1?'selected':'' }}>Admin</option>
                                    <option value="3" {{ $u->role_id==3?'selected':'' }}>User</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        </tbody>
    </table>
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('user.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah User</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" name="name"
                        class="form-control mb-2" placeholder="Nama">

                    <input type="text" name="username"
                        class="form-control mb-2" placeholder="Username">

                    <input type="email" name="email"
                        class="form-control mb-2" placeholder="Email">

                    <input type="password" name="password"
                        class="form-control mb-2" placeholder="Password">

                    <textarea name="alamat"
                        class="form-control mb-2"
                        placeholder="Alamat"></textarea>

                    <input type="text" name="no_hp"
                        class="form-control mb-2"
                        placeholder="No HP">

                    <select name="role_id" class="form-control">
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                        <option value="4">Petugas</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
