 @extends('master')
 @section('content')
     <div class="content-page">

         <div class="content">


             <!-- Start Content-->
             <div class="container-fluid">

                 <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                     <div class="flex-grow-1">
                         <h4 class="fs-18 fw-semibold m-0"></h4>
                     </div>

                     <div class="text-end">
                         <ol class="breadcrumb m-0 py-0">
                             <li class="breadcrumb-item"><a href="javascript: void(0);">Keuangan</a></li>
                             <li class="breadcrumb-item active">Piutang</li>
                         </ol>
                     </div>
                 </div>

                 <!-- Datatables  -->
                 <div class="row">
                     <div class="col-12">
                         <div class="card shadow-sm border-0">
                             <div
                                 class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                                 <div>
                                     <h5 class="mb-0 fw-bold">
                                         <i class="mdi mdi-cash-register text-primary"></i>
                                         Daftar Piutang
                                     </h5>
                                     <small class="text-muted">Daftar seluruh transaksi piutang baik yang sudah lunas atau belum</small>
                                 </div>

                                 <a href="{{ url('pembayaran') }}"><button type="button"
                                         class="btn btn-primary btn-sm rounded-pill btn-tambah">
                                         <i class="mdi mdi-cash"></i> Pembayaran
                                     </button></a>
                             </div>

                            
                             <div class="card-body">

                                 {{-- Filter --}}
                                 <div class="row g-2 mb-3">

                                     {{-- Tanggal Dari --}}
                                     <div class="col-md-2">
                                         <label class="form-label mb-1">
                                             Tanggal Dari
                                         </label>

                                         <input type="date" id="filter_tanggal_dari"
                                             class="form-control form-control-sm">
                                     </div>

                                     {{-- Tanggal Sampai --}}
                                     <div class="col-md-2">
                                         <label class="form-label mb-1">
                                             Tanggal Sampai
                                         </label>

                                         <input type="date" id="filter_tanggal_sampai"
                                             class="form-control form-control-sm">
                                     </div>

                                     {{-- Customer --}}
                                     <div class="col-md-2">
                                         <label class="form-label mb-1">
                                             Pelanggan
                                         </label>

                                         <select id="filter_customer" class="form-select form-select-sm">

                                             <option value="">Semua Pelanggan</option>

                                             @foreach ($customer ?? [] as $item)
                                                 <option value="{{ $item->kd_pelanggan }}">
                                                     {{ $item->nm_pelanggan }}
                                                 </option>
                                             @endforeach

                                         </select>
                                     </div>

                                     {{-- tempo --}}
                                     <div class="col-md-2">
                                         <label class="form-label mb-1">
                                             Tempo
                                         </label>

                                         <select id="filter_tempo" class="form-select form-select-sm">

                                             <option value="">Semua</option>
                                            <option value="jatuh-tempo">Jatuh Tempo</option>
                                            

                                         </select>
                                     </div>


                                     {{-- lunas --}}
                                     <div class="col-md-2">
                                         <label class="form-label mb-1">
                                             Status
                                         </label>

                                         <select id="filter_status" class="form-select form-select-sm">

                                            <option value="">Semua</option>
                                            <option value="outstanding">Belum Lunas</option>
                                            <option value="lunas">Lunas</option>
                                            

                                         </select>
                                     </div>

                                    

                                     

                                     {{-- Tombol Filter --}}
                                     <div class="col-md-2 d-flex align-items-end">

                                         <button type="button" id="btn-filter" class="btn btn-primary btn-sm me-1">

                                             <i class="mdi mdi-filter-outline"></i>
                                             Filter

                                         </button>

                                         <button type="button" id="btn-reset" class="btn btn-light btn-sm">

                                             <i class="mdi mdi-refresh"></i>

                                         </button>

                                     </div>

                                 </div>


                                 {{-- Export --}}
                                 <div class="d-flex justify-content-end mb-3">

                                     <button type="button" id="btn-export-excel" class="btn btn-success btn-sm me-2">

                                         <i class="mdi mdi-file-excel-outline"></i>
                                         Export Excel

                                     </button>

                                     <button type="button" id="btn-export-pdf" class="btn btn-danger btn-sm">

                                         <i class="mdi mdi-file-pdf-box"></i>
                                         Export PDF

                                     </button>

                                 </div>


                                 {{-- Table --}}
                                 <div class="table-responsive">

                                     <table id="list-table"
                                         class="table table-striped table-bordered table-hover table-sm mb-0 nowrap w-100">

                                         <thead class="table-light">

                                             <tr>

                                                 <th>Action</th>
                                                 <th>Tanggal</th>
                                                 <th>Nota</th>
                                                 <th>Pelanggan</th>
                                                 <th>Nilai</th>
                                                 <th>Bayar</th>
                                                 <th>Sisa</th>
                                                 <th>Tempo</th>
                                                 <th>Tgl Jatuh Tempo</th>
                                                 <th>Kasir</th>
                                                 <th>Keterangan</th>

                                             </tr>

                                         </thead>

                                         <tbody></tbody>

                                     </table>

                                 </div>

                             </div>
                            

                         </div>
                     </div>
                 </div>



             </div> <!-- container-fluid -->


         </div>
         @include('pages.piutang.modal_pembayaran')
     </div>
 @endsection

 @push('scripts')
     @include('pages.piutang.js')
 @endpush
