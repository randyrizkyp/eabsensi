@extends('templates.main')

@section('content')
<br>
<div class="row justify-content-center">

    <div class="col-md-8">
        <h2 class="my-4 text-center text-dark">DAFTAR ADMIN KABUPATEN</h2>
        @if(session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="/register" class="btn btn-sm btn-primary mb-2">Tambah Admin</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>email</th>
                    <th>created_at</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengguna as $pg)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pg->name }}</td>
                    <td>{{ $pg->username }}</td>
                    <td>{{ $pg->email }}</td>
                    <td>{{ $pg->created_at }}</td>
                    <td><span class="badge bg-blue">edit</span><span class="badge bg-danger ml-2">hapus</span></td>

                </tr>

                @endforeach

            </tbody>
        </table>
    </div>

</div>
@endsection