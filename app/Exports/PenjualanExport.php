<?php

namespace App\Exports;

use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class PenjualanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithCustomStartCell,
    ShouldAutoSize
{
    protected Request $request;

    protected $totalSubtotal = 0;
    protected $totalDiscount = 0;
    protected $totalPenjualan = 0;
    protected $totalHpp = 0;
    protected $totalMargin = 0;
    protected $totalPembayaran = 0;
    protected $totalKembali = 0;

    /**
     * Cache data item berdasarkan nota
     */
    protected $itemsByNota = null;

    /**
     * Cache collection penjualan
     */
    protected $penjualanCollection = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Posisi awal header tabel
     */
    public function startCell(): string
    {
        return 'A10';
    }

    /**
     * Ambil data penjualan
     */
    public function collection()
    {
        // Gunakan cache supaya collection tidak query ulang
        if ($this->penjualanCollection !== null) {
            return $this->penjualanCollection;
        }

        $request = $this->request;

        $query = Penjualan::query();

        // ==========================
        // FILTER TANGGAL DARI
        // ==========================

        if ($request->filled('tanggal_dari')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_dari
            );
        }

        // ==========================
        // FILTER TANGGAL SAMPAI
        // ==========================

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_sampai
            );
        }

        // ==========================
        // FILTER CUSTOMER
        // ==========================

        if ($request->filled('customer')) {
            $query->where(
                'kd_pelanggan',
                $request->customer
            );
        }

        // ==========================
        // FILTER STATUS
        // ==========================

        if ($request->filled('status')) {
            $query->where(
                'status_pembayaran',
                $request->status
            );
        }

        // ==========================
        // FILTER KASIR
        // ==========================

        if ($request->filled('kasir')) {
            $query->where(
                'kd_user',
                $request->kasir
            );
        }

        $data = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        /*
         * ============================================================
         * AMBIL SEMUA ITEM SEKALIGUS
         * Relasi:
         *
         * penjualan.nota
         *        ↓
         * penjualan_post.nota
         *
         * Jadi tidak melakukan query item satu-per-satu.
         * ============================================================
         */

        $notas = $data
            ->pluck('nota')
            ->filter()
            ->unique()
            ->values();

        if ($notas->count() > 0) {
            $this->itemsByNota = PenjualanItem::query()
                ->whereIn('nota', $notas)
                ->get()
                ->groupBy('nota');
        } else {
            $this->itemsByNota = collect();
        }

        $this->penjualanCollection = $data;

        return $this->penjualanCollection;
    }

    /**
     * Header tabel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nota',
            'Pelanggan',
            'Subtotal',
            'Diskon',
            'Penjualan',
            'HPP',
            'Margin',
            'Bayar',
            'Kembali',
            'Status',
            'Kasir',
        ];
    }

    /**
     * Ambil HPP berdasarkan nota
     */
    protected function getHppByNota($nota): float
    {
        if (!$nota) {
            return 0;
        }

        $items = $this->itemsByNota[$nota] ?? collect();

        $hpp = 0;

        foreach ($items as $item) {

            $modal = (float) ($item->modal ?? 0);
            $jumlah = (float) ($item->jumlah ?? 0);

            $hpp += $modal * $jumlah;
        }

        return $hpp;
    }

    /**
     * Mapping data
     */
    public function map($row): array
    {
        // ==========================
        // NILAI DASAR
        // ==========================

        $subtotal = (float) ($row->subtotal ?? 0);

        $discount = (float) ($row->total_discount ?? 0);

        $penjualan = (float) ($row->belanja ?? 0);

        $bayar = (float) ($row->bayar ?? 0);

        $kembali = (float) ($row->kembali ?? 0);

        // ==========================
        // HITUNG HPP
        // ==========================

        $hpp = $this->getHppByNota($row->nota);

        // ==========================
        // HITUNG MARGIN
        // ==========================

        $margin = $penjualan - $hpp;

        // ==========================
        // TOTAL
        // ==========================

        $this->totalSubtotal += $subtotal;

        $this->totalDiscount += $discount;

        $this->totalPenjualan += $penjualan;

        $this->totalHpp += $hpp;

        $this->totalMargin += $margin;

        $this->totalPembayaran += $bayar;

        $this->totalKembali += $kembali;

        // ==========================
        // RETURN DATA
        // ==========================

        return [
            $row->tanggal,
            $row->nota,
            $row->pelanggan?->nm_pelanggan ?? '-',

            $subtotal,
            $discount,
            $penjualan,

            $hpp,
            $margin,

            $bayar,
            $kembali,

            $row->status_pembayaran ?? '-',
            $row->kasir?->nama ?? '-',
        ];
    }

    /**
     * Styling Excel
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $request = $this->request;

                // ==================================================
                // IDENTITAS TOKO
                // ==================================================

                $sheet->mergeCells('A1:L1');

                $sheet->setCellValue(
                    'A1',
                    env('NAMA_TOKO')
                );

                $sheet->mergeCells('A2:L2');

                $sheet->setCellValue(
                    'A2',
                    env('ALAMAT_TOKO1')
                );

                $sheet->mergeCells('A3:L3');

                $sheet->setCellValue(
                    'A3',
                    'DAFTAR PENJUALAN'
                );

                // ==================================================
                // FILTER
                // ==================================================

                $tanggalDari = 'Semua';

                if ($request->filled('tanggal_dari')) {

                    $tanggalDari = date(
                        'd-m-Y',
                        strtotime($request->tanggal_dari)
                    );
                }

                $tanggalSampai = 'Semua';

                if ($request->filled('tanggal_sampai')) {

                    $tanggalSampai = date(
                        'd-m-Y',
                        strtotime($request->tanggal_sampai)
                    );
                }

                $periode =
                    $tanggalDari .
                    ' s/d ' .
                    $tanggalSampai;

                // ==================================================
                // CUSTOMER
                // ==================================================

                $customer = '';

                if ($request->filled('customer')) {

                    $xpelanggan = Pelanggan::where(
                        'kd_pelanggan',
                        $request->customer
                    )->first();

                    $customer = $xpelanggan->nm_pelanggan ?? '';

                } else {

                    $customer = 'Semua Customer';
                }

                // ==================================================
                // STATUS
                // ==================================================

                $status = 'Semua';

                if ($request->filled('status')) {

                    $status = ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $request->status
                        )
                    );
                }

                // ==================================================
                // KASIR
                // ==================================================

                $kasir = '';

                if ($request->filled('kasir')) {

                    $xkasir = Pengguna::where(
                        'kd_pengguna',
                        $request->kasir
                    )->first();

                    $kasir = $xkasir->nama ?? '';

                } else {

                    $kasir = 'Semua Kasir';
                }

                // ==================================================
                // FILTER DISPLAY
                // ==================================================

                $sheet->mergeCells('A5:B5');

                $sheet->setCellValue(
                    'A5',
                    'Periode'
                );

                $sheet->mergeCells('C5:L5');

                $sheet->setCellValue(
                    'C5',
                    $periode
                );

                // --------------------------------------------------

                $sheet->mergeCells('A6:B6');

                $sheet->setCellValue(
                    'A6',
                    'Customer'
                );

                $sheet->mergeCells('C6:L6');

                $sheet->setCellValue(
                    'C6',
                    $customer
                );

                // --------------------------------------------------

                $sheet->mergeCells('A7:B7');

                $sheet->setCellValue(
                    'A7',
                    'Status Pembayaran'
                );

                $sheet->mergeCells('C7:L7');

                $sheet->setCellValue(
                    'C7',
                    $status
                );

                // --------------------------------------------------

                $sheet->mergeCells('A8:B8');

                $sheet->setCellValue(
                    'A8',
                    'Kasir'
                );

                $sheet->mergeCells('C8:L8');

                $sheet->setCellValue(
                    'C8',
                    $kasir
                );

                // ==================================================
                // STYLE JUDUL
                // ==================================================

                $sheet->getStyle('A1:L1')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                $sheet->getStyle('A2:L2')->applyFromArray([

                    'font' => [
                        'size' => 11,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                $sheet->getStyle('A3:L3')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 15,
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                // ==================================================
                // STYLE FILTER
                // ==================================================

                $sheet->getStyle('A5:L8')->applyFromArray([

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                        ],
                    ],

                    'alignment' => [
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                ]);

                $sheet->getStyle('A5:B8')->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],

                ]);

                // ==================================================
                // HEADER TABEL
                // ==================================================

                $headerRow = 10;

                $sheet->getStyle(
                    "A{$headerRow}:L{$headerRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'D9EAF7',
                        ],
                    ],

                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,

                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                        ],
                    ],

                ]);

                // ==================================================
                // JUMLAH DATA
                // ==================================================

                $data = $this->collection();

                $totalData = $data->count();

                $lastDataRow =
                    $headerRow +
                    $totalData;

                $totalRow =
                    $lastDataRow + 1;

                // ==================================================
                // BORDER DATA
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "A11:L{$lastDataRow}"
                    )->applyFromArray([

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,
                            ],
                        ],

                        'alignment' => [
                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],

                    ]);
                }

                // ==================================================
                // FORMAT RUPIAH
                //
                // D = Subtotal
                // E = Diskon
                // F = Penjualan
                // G = HPP
                // H = Margin
                // I = Bayar
                // J = Kembali
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "D11:J{$lastDataRow}"
                    )->getNumberFormat()
                        ->setFormatCode(
                            '"Rp" #,##0'
                        );
                }

                // ==================================================
                // TOTAL
                // ==================================================

                $sheet->setCellValue(
                    "C{$totalRow}",
                    'TOTAL'
                );

                // Subtotal
                $sheet->setCellValue(
                    "D{$totalRow}",
                    $this->totalSubtotal
                );

                // Diskon
                $sheet->setCellValue(
                    "E{$totalRow}",
                    $this->totalDiscount
                );

                // Penjualan
                $sheet->setCellValue(
                    "F{$totalRow}",
                    $this->totalPenjualan
                );

                // HPP
                $sheet->setCellValue(
                    "G{$totalRow}",
                    $this->totalHpp
                );

                // Margin
                $sheet->setCellValue(
                    "H{$totalRow}",
                    $this->totalMargin
                );

                // Bayar
                $sheet->setCellValue(
                    "I{$totalRow}",
                    $this->totalPembayaran
                );

                // Kembali
                $sheet->setCellValue(
                    "J{$totalRow}",
                    $this->totalKembali
                );

                // ==================================================
                // STYLE TOTAL
                // ==================================================

                $sheet->getStyle(
                    "C{$totalRow}:J{$totalRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => 'E9ECEF',
                        ],
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' =>
                                Border::BORDER_THIN,
                        ],
                    ],

                ]);

                // ==================================================
                // FORMAT RUPIAH TOTAL
                // ==================================================

                $sheet->getStyle(
                    "D{$totalRow}:J{$totalRow}"
                )->getNumberFormat()
                    ->setFormatCode(
                        '"Rp" #,##0'
                    );

                // ==================================================
                // AUTOFILTER
                // ==================================================

                if ($totalData > 0) {

                    $sheet->setAutoFilter(
                        "A10:L{$lastDataRow}"
                    );
                } else {

                    $sheet->setAutoFilter(
                        "A10:L10"
                    );
                }

                // ==================================================
                // FREEZE HEADER
                // ==================================================

                $sheet->freezePane('A11');

                // ==================================================
                // ALIGNMENT ANGKA
                // ==================================================

                if ($totalData > 0) {

                    $sheet->getStyle(
                        "D11:J{$lastDataRow}"
                    )->getAlignment()->setHorizontal(
                        Alignment::HORIZONTAL_RIGHT
                    );

                    $sheet->getStyle(
                        "A11:A{$lastDataRow}"
                    )->getAlignment()->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                    // Status
                    $sheet->getStyle(
                        "K11:K{$lastDataRow}"
                    )->getAlignment()->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );
                }

                // ==================================================
                // ROW HEIGHT
                // ==================================================

                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->getRowDimension(2)->setRowHeight(20);

                $sheet->getRowDimension(3)->setRowHeight(25);

                // ==================================================
                // PRINT SETTING
                // ==================================================

                $sheet->getPageSetup()->setOrientation(
                    PageSetup::ORIENTATION_LANDSCAPE
                );

                $sheet->getPageSetup()->setPaperSize(
                    PageSetup::PAPERSIZE_A4
                );

                $sheet->getPageSetup()->setFitToWidth(1);

                $sheet->getPageSetup()->setFitToHeight(0);

                $sheet->getPageSetup()->setHorizontalCentered(
                    true
                );

                // ==================================================
                // PRINT MARGIN
                // ==================================================

                $sheet->getPageMargins()->setTop(0.5);

                $sheet->getPageMargins()->setRight(0.3);

                $sheet->getPageMargins()->setBottom(0.5);

                $sheet->getPageMargins()->setLeft(0.3);

                // ==================================================
                // PRINT AREA
                // ==================================================

                // Jika ingin print area aktif, buka komentar:
                //
                // $sheet->getPageSetup()->setPrintArea(
                //     "A1:L{$totalRow}"
                // );
            },

        ];
    }
}