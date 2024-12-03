@extends('layouts')

@section('content')
    <h2>Tambah Transaksi</h2>
    <div class="card">
        <div class="card-header bg-white">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">Kembali</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="d-flex flex-column gap-4 mb-4">
                    <div class="form-group">
                        <label>Tanggal Pembelian</label>
                        <input type="date" class="form-control" name="tanggal_pembelian" value="{{ old('tanggal_pembelian') }}" required>
                    </div>
                </div>
                <h6>Produk yang dibeli</h6>
                <div class="accordion mb-4" id="accordionItem">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $i === 1 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#item{{ $i }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}">
                                    Item #{{ $i }}
                                </button>
                            </h2>
                            <div id="item{{ $i }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" data-bs-parent="#accordionItem">
                                <div class="accordion-body">
                                    <div class="d-flex flex-column gap-4 mb-4">
                                        <div class="form-group">
                                            <label>Nama Produk</label>
                                            <input type="text" class="form-control" name="nama_produk{{ $i }}" value="{{ old('nama_produk' . $i) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" name="harga_satuan{{ $i }}" value="{{ old('harga_satuan' . $i) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input type="number" class="form-control" name="jumlah{{ $i }}" value="{{ old('jumlah' . $i) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Subtotal</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control" name="subtotal{{ $i }}" value="{{ old('subtotal' . $i) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="form-group">
                    <label>Harga Total</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="total_harga" value="{{ old('total_harga') }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label>Bayar</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="bayar" value="{{ old('bayar') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Kembalian</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" name="kembalian" value="{{ old('kembalian') }}" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

{{-- customjs --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let subtotals = {
            subtotal1: 0,
            subtotal2: 0,
            subtotal3: 0
        };

        function calculateSubtotal(item) {
            const hargaSatuan = parseInt(document.querySelector(`input[name="harga_satuan${item}"]`).value) || 0;
            const jumlah = parseInt(document.querySelector(`input[name="jumlah${item}"]`).value) || 0;
            subtotals[`subtotal${item}`] = hargaSatuan * jumlah;
            document.querySelector(`input[name="subtotal${item}"]`).value = subtotals[`subtotal${item}`];
            document.querySelector(`input[name="total_harga"]`).value = Object.values(subtotals).reduce((acc, curr) => acc + curr, 0);
        }

        [1, 2, 3].forEach(item => {
            document.querySelectorAll(`input[name="harga_satuan${item}"], input[name="jumlah${item}"]`).forEach(input => {
                input.addEventListener('input', () => calculateSubtotal(item));
            });
        });

        document.querySelector('input[name="bayar"]').addEventListener('input', () => {
            const totalHarga = parseInt(document.querySelector('input[name="total_harga"]').value) || 0;
            const bayar = parseInt(document.querySelector('input[name="bayar"]').value) || 0;
            const kembalian = bayar - totalHarga;
            document.querySelector('input[name="kembalian"]').value = kembalian >= 0 ? kembalian : 0;
        });
    });
</script>
@endsection
