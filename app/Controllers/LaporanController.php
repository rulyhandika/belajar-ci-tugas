<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class LaporanController extends BaseController
{
    public function index()
    {
        helper('number');
        $model = new TransactionModel();
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $builder = $model->where('status', 3); // hanya status Selesai
        if ($start && $end) {
            $builder = $builder->where('DATE(created_at) >=', $start)
                               ->where('DATE(created_at) <=', $end);
        }
        $pendapatan = $builder->findAll();
        return view('v_laporan', [
            'pendapatan' => $pendapatan,
            'start' => $start,
            'end' => $end
        ]);
    }

    public function pdf()
    {
        helper('number');
        $model = new TransactionModel();
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $builder = $model->where('status', 3);
        if ($start && $end) {
            $builder = $builder->where('DATE(created_at) >=', $start)
                               ->where('DATE(created_at) <=', $end);
        }
        $pendapatan = $builder->findAll();
        $html = '<h4 style="text-align:center;">Laporan Pendapatan</h4>';
        $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
        $html .= '<thead><tr><th>No</th><th>ID Transaksi</th><th>Tanggal</th><th>User</th><th>Total Harga</th><th>Status</th></tr></thead><tbody>';
        $total = 0;
        $statusList = [0=>'Menunggu Pembayaran',1=>'Sudah Dibayar',2=>'Sedang Dikirim',3=>'Sudah Selesai',4=>'Dibatalkan'];
        $no = 1;
        foreach ($pendapatan as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $no++ . '</td>';
            $html .= '<td>' . $row['id'] . '</td>';
            $html .= '<td>' . $row['created_at'] . '</td>';
            $html .= '<td>' . $row['username'] . '</td>';
            $html .= '<td>' . number_to_currency($row['total_harga'], 'IDR') . '</td>';
            $html .= '<td>' . ($statusList[$row['status']] ?? '-') . '</td>';
            $html .= '</tr>';
            $total += $row['total_harga'];
        }
        $html .= '</tbody>';
        $html .= '<tfoot><tr><th colspan="4" style="text-align:right;">Total Harga</th><th colspan="2">' . number_to_currency($total, 'IDR') . '</th></tr></tfoot>';
        $html .= '</table>';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_pendapatan.pdf', ['Attachment' => 0]);
        exit();
    }

    public function excel()
    {
        helper('number');
        $model = new TransactionModel();
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');
        $builder = $model->where('status', 3);
        if ($start && $end) {
            $builder = $builder->where('DATE(created_at) >=', $start)
                               ->where('DATE(created_at) <=', $end);
        }
        $pendapatan = $builder->findAll();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_pendapatan.xls"');
        echo '<table border="1">';
        echo '<thead><tr><th>No</th><th>ID Transaksi</th><th>Tanggal</th><th>User</th><th>Total Harga</th><th>Status</th></tr></thead><tbody>';
        $total = 0;
        $statusList = [0=>'Menunggu Pembayaran',1=>'Sudah Dibayar',2=>'Sedang Dikirim',3=>'Sudah Selesai',4=>'Dibatalkan'];
        $no = 1;
        foreach ($pendapatan as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['created_at'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['total_harga'] . '</td>';
            echo '<td>' . ($statusList[$row['status']] ?? '-') . '</td>';
            echo '</tr>';
            $total += $row['total_harga'];
        }
        echo '</tbody>';
        echo '<tfoot><tr><th colspan="4" style="text-align:right;">Total Harga</th><th colspan="2">' . $total . '</th></tr></tfoot>';
        echo '</table>';
        exit();
    }
}
