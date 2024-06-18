<title>Hypnotherapy</title>
@extends('layouts.main')

@section('content')
    <!-- Display Errors -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif

    <!-- Display Success Message -->
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        <h1>Hypnotherapy - Medicine Inventory</h1>
        <br>

        <!-- Add Medicine Button -->
        <a href="/obat-form" class="btn btn-success">
            <i class="fas fa-plus text-white"></i>
            <i class="fas fa-medkit text-white"></i> Add Medicine
        </a>

        <br /><br />

        <!-- Medicine Table -->
        <div class="table-responsive">
            <table class="table table-flush" id="products-list">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tools</th>
                        <th>Kode Obat</th>
                        <th>Stok</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Dosis</th>
                        <th>Harga</th>
                        <th>Tanggal Buat</th>
                        <th>Tanggal Expired</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obat as $index => $medicine)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <!-- Action Buttons -->
                            <td class="text-sm">
                                <!-- Edit Medicine -->
                                <a href="{{ route('obat.edit', $medicine->id) }}" class="btn btn-warning" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pen text-white"></i>
                                </a>

                                <!-- Edit Stock -->
                                <a href="/edit-stok/{{ $medicine->id }}" class="btn btn-primary" data-bs-toggle="tooltip" title="Edit Stock">
                                    <i class="fas fa-cube text-white"></i>
                                </a>

                                <!-- Delete Medicine -->
                                <form action="{{ route('obat.destroy', $medicine->id) }}" method="POST" style="display:inline-block;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete this medicine?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                            <!-- Medicine Details -->
                            <td>{{ $medicine->kodeobat ?: 'Kode Belum data' }}</td>
                            <td>{{ $medicine->stok ?: 'Stok Kosong' }}</td>
                            <td>{{ $medicine->nama }}</td>
                            <td>{{ $medicine->jenis->jenisobat ?: 'Jenis Belum ada' }}</td>
                            <td>
                                @php
                                    $expiredDate = date('Y/m/d', strtotime($medicine->expired));
                                    $currentDate = date('Y/m/d');
                                @endphp

                                @if ($currentDate < $expiredDate)
                                    <span class="text-success">Bagus</span>
                                @else
                                    <span class="text-danger">Expired</span>
                                @endif
                            </td>
                            <td>{{ $medicine->dosis ?: 'Dosis Belum ada' }}</td>
                            <td>{{ "Rp " . number_format($medicine->harga, 2, ',', '.') }}</td>
                            <td>{{ $medicine->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $medicine->expired ? date("d/m/Y", strtotime($medicine->expired)) : 'Expired belum di Set' }}</td>
                            <td>
                                @if ($medicine->photo)
                                    <img src="/image/{{ $medicine->photo }}" alt="Medicine Photo" width="100%">
                                @else
                                    Gambar Belum ada
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#products-list').DataTable({
                    dom: 'lBfrtip',
                    lengthMenu: [
                        [50, 100, 1000, -1],
                        ['50', '100', '1000', 'All']
                    ],
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'Excel',
                            messageTop: 'Data Obat dicetak per Tanggal ' + '{{ \Carbon\Carbon::now()->format("d-M-Y") }}'
                        },
                        {
                            extend: 'copy',
                            text: 'Copy Isi',
                            messageTop: 'Data Obat dicetak per Tanggal ' + '{{ \Carbon\Carbon::now()->format("d-M-Y") }}'
                        }
                    ],
                    language: {
                        searchPlaceholder: "Cari nama obat",
                        zeroRecords: "Tidak ditemukan data yang sesuai",
                        emptyTable: "Tidak terdapat data di tabel"
                    }
                });
            });
        </script>
    @endpush
@endsection
