<?php

namespace App\Http\Controllers;

use App\Exports\PiutangExport;
use App\Models\Pelanggan;
use App\Models\Piutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PiutangController extends Controller
{


    public function table(Request $request)
    {
        if ($request->ajax()) {
            $query = Piutang::query();
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
                    'kd_pelanggan',
                    $request->customer
                );
            }

            if ($request->tempo) {
                $query->where('sisa', '>', 0)
                    ->whereDate('jatuh_tempo', '<=', now()->toDateString());
            }


            if ($request->status) {
                if ($request->status == 'outstanding') {
                    $query->where('sisa', '>', 0);
                } else if ($request->status == 'lunas') {
                    $query->where('sisa', '<=', 0);
                }
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal));
                })
                ->addColumn('jatuh_tempo', function ($row) {
                    return date('d-m-Y', strtotime($row->jatuh_tempo));
                })
                ->addColumn('kd_pelanggan', function ($row) {
                    return '<div style="white-space:normal;width:160px;">' . $row->customer?->nm_pelanggan ?? '' . '</div>';
                })
                ->addColumn('keterangan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . $row->keterangan . '</div>';
                })
                ->addColumn('belanja', function ($row) {
                    return number_format($row->belanja);
                })

                ->addColumn('bayar', function ($row) {
                    return number_format($row->bayar);
                })

                ->addColumn('sisa', function ($row) {
                    return '<div class="bg-danger" style="color:white;padding:2px 4px 2px 6px;border-radius:3px;"><strong>'.number_format($row->sisa).'</strong></div>';
                })

                ->addColumn('kd_user', function ($row) {
                    return $row->kasir?->nama ?? '';
                })

                ->addColumn('action', function ($row) {
                    $button = '
                        <center>
                        <button onclick="pembayaran('.$row->id.')"
                            class="btn btn-warning btn-sm"
                            title="Bayar">
                            <i class="mdi mdi-cash"></i>
                        </button>

                       
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'kd_pelanggan', 'keterangan', 'sisa'])
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'piutang';
        $customer = Pelanggan::all();
        return view('pages.piutang.index', compact('view', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        return Excel::download(new PiutangExport($request), 'piutang-' . date('Y-m-d-His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Piutang::query();

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
                'kd_pelanggan',
                $request->customer
            );
        }


        // Filter kasir
        if ($request->filled('tempo')) {

            $query->where('sisa', '>', 0)
                ->whereDate('jatuh_tempo', '<=', now()->toDateString());
        }

        if ($request->filled('status')) {

            if ($request->status == 'outstanding') {
                $query->where('sisa', '>', 0);
            } else if ($request->status == 'lunas') {
                $query->where('sisa', '<=', 0);
            }
        }

        $piutang = $query
            ->with([
                'customer',
                'kasir'
            ])
            ->orderBy('tanggal', 'desc')
            ->get();


        $pdf = Pdf::loadView(
            'pages.piutang.pdf',
            [
                'piutang' => $piutang,

                'tanggal_dari' =>
                $request->tanggal_dari,

                'tanggal_sampai' =>
                $request->tanggal_sampai,

                'customer_nama' =>
                $request->customer,

                'tempo' =>
                $request->tempo,

                'status' =>
                $request->status,
            ]
        );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'piutang-' .
                date('Y-m-d-His') .
                '.pdf'
        );
    }

    public function piutangList($id)
    {
        $data = Piutang::find($id);

        $row['id'] = $data->id;
        $row['nota'] = $data->nota;
        $row['kd_pelanggan'] = $data->kd_pelanggan;
        $row['nm_pelanggan'] = $data->customer?->nm_pelanggan ?? '';
        $row['keterangan'] = $data->keterangan;
        $row['tanggal'] = date('d-m-Y', strtotime($data->tanggal));
        $row['belanja'] = $data->belanja;
        $row['bayar'] = $data->bayar;
        $row['sisa'] = $data->sisa;
        $row['kd_user'] = $data->kd_user;
        $row['tempo_hari'] = $data->tempo;
        $row['jatuh_tempo'] = $data->jatuh_tempo;

        return $row;
    }
}
