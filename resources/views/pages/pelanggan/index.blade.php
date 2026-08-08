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
                             <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                             <li class="breadcrumb-item active">Data Pelanggan</li>
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
                                         <i class="mdi mdi-human-female-female text-primary"></i>
                                         Data Pelanggan
                                     </h5>
                                     <small class="text-muted">Daftar seluruh pelanggan yang tersedia</small>
                                 </div>

                                 <button onclick="addData()" type="button"
                                     class="btn btn-primary btn-sm rounded-pill btn-tambah">
                                     <i class="mdi mdi-plus"></i> Tambah
                                 </button>
                             </div>

                             <div class="card-body">
                                 <div class="table-responsive">
                                     <table id="list-table"
                                         class="table table-striped table-bordered table-hover table-sm align-middle mb-0 nowrap w-100">

                                         <thead class="table-light">
                                             <tr>
                                                 <th width="70">Action</th>
                                                 <th>Kode</th>
                                                 <th>Nama Pelanggan</th>
                                                 <th>Alamat</th>
                                                 <th>Whatsapp</th>
                                                 
                                             </tr>
                                         </thead>

                                         <tbody>

                                         </tbody>

                                     </table>
                                 </div>


                             </div>
                         </div>
                     </div>
                 </div>



             </div> <!-- container-fluid -->


         </div>
         @include('pages.pelanggan.modal')
     </div>
 @endsection

 @push('scripts')
     @include('pages.pelanggan.js')
 @endpush
