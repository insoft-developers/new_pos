<?php

namespace App\Http\Controllers;

use App\Exports\PembayaranExport;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Piutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PembayaranController extends Controller
{


    public function table(Request $request)
    {
        if ($request->ajax()) {
            $query = Pembayaran::query();
            if ($request->tanggal_dari) {
                $query->whereDate(
                    'tanggal',
                    '>=',
                    $request->tanggal_dari
                );
            }

            if ($request->tanggal_sampai) {
                $query->whereDate(
                    'tanggal',
                    '<=',
                    $request->tanggal_sampai
                );
            }

            if ($request->customer) {
                $query->where(
                    'pelanggan',
                    $request->customer
                );
            }


            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal));
                })

                ->addColumn('kd_pelanggan', function ($row) {
                    return '<div style="white-space:normal;width:160px;">' . $row->customer?->nm_pelanggan ?? '' . '</div>';
                })
                ->addColumn('keterangan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . $row->keterangan . '</div>';
                })
                ->addColumn('nilai_nota', function ($row) {
                    return number_format($row->nilai_nota);
                })

                ->addColumn('pembayaran', function ($row) {
                    return '<div style="background:green;color:white;padding:2px 4px 2px 6px;border-radius:3px;"><strong>' . number_format($row->pembayaran) . '</strong></div>';
                })

                ->addColumn('sisa', function ($row) {
                    return number_format($row->sisa);
                })

                ->addColumn('kd_user', function ($row) {
                    return $row->kasir?->nama ?? '';
                })

                ->addColumn('action', function ($row) {
                    $button = '
                        <center>
                        <button onclick="printData(' . $row->id . ')"
                            class="btn btn-success btn-sm"
                            title="Print">
                            <i class="mdi mdi-cash"></i>
                        </button>

                        <button onclick="delete(' . $row->id . ')"
                            class="btn btn-danger btn-sm"
                            title="hapus">
                            <i class="mdi mdi-delete"></i>
                        </button>
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'kd_pelanggan', 'keterangan', 'pembayaran'])
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'pembayaran';
        $customer = Pelanggan::all();
        return view('pages.pembayaran.index', compact('view', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validated = $request->validate([
            'nota' => 'required',
            'pelanggan' => 'required',
            'nilai_nota' => 'required',
            'pembayaran' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            $lastNota = Pembayaran::orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNumber = $lastNota
                ? ((int) substr($lastNota->no_pembayaran, 5)) + 1
                : 1;

            $user = session('kd_pengguna');
            $kode = substr($user, -3);

            $nota = 'Y-' . $kode . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );


            $piutang = Piutang::where('nota', $input['nota'])->first();


            $p = new Pembayaran;
            $p->no_pembayaran = $nota;
            $p->nota = $input['nota'];
            $p->pelanggan = $input['pelanggan'];
            $p->nilai_nota = $piutang->sisa;
            $p->pembayaran = $input['pembayaran'];


            $sisa = (int)$piutang->sisa - (int)$input['pembayaran'];
            $p->sisa = $sisa;
            $p->tanggal = $input['tanggal'];
            $p->keterangan = $input['keterangan'] ?? '';
            $p->kd_user = session('kd_pengguna');
            $p->save();

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Pembayaran berhasil"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(new PembayaranExport($request), 'pembayaran-' . date('Y-m-d-His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Pembayaran::query();

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_dari
            );
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_sampai
            );
        }

        // Filter supplier
        if ($request->filled('customer')) {
            $query->where(
                'pelanggan',
                $request->customer
            );
        }

        $pembayaran = $query
            ->with([
                'customer',
                'kasir'
            ])
            ->orderBy('tanggal', 'desc')
            ->get();


        $pdf = Pdf::loadView(
            'pages.pembayaran.pdf',
            [
                'pembayaran' => $pembayaran,

                'tanggal_dari' =>
                $request->tanggal_dari,

                'tanggal_sampai' =>
                $request->tanggal_sampai,

                'customer' =>
                $request->customer,

            ]
        );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'pembayaran-' .
                date('Y-m-d-His') .
                '.pdf'
        );
    }
}
