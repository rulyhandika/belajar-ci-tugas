<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h4>Laporan Pendapatan</h4>
<div class="d-flex justify-content-between align-items-center mb-3">
    <form class="d-flex align-items-center gap-2 mb-0" method="get" action="">
        <label class="mb-0">Periode:</label>
        <input type="date" name="start" value="<?= esc($start) ?>" class="form-control form-control-sm" style="width:150px;">
        <span class="mx-1">s/d</span>
        <input type="date" name="end" value="<?= esc($end) ?>" class="form-control form-control-sm" style="width:150px;">
        <button type="submit" class="btn btn-primary btn-sm ms-2">Terapkan</button>
    </form>
    <div>
        <a href="<?= base_url('laporan/pdf?start=' . esc($start) . '&end=' . esc($end)) ?>" class="btn btn-danger btn-sm me-2" target="_blank">Download PDF</a>
        <a href="<?= base_url('laporan/excel?start=' . esc($start) . '&end=' . esc($end)) ?>" class="btn btn-success btn-sm">Export Excel</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            if (!empty($pendapatan)): $no=1; foreach ($pendapatan as $row): 
                $total += $row['total_harga'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['id'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= number_to_currency($row['total_harga'], 'IDR') ?></td>
                <td><?php
                    $statusList = [
                        0 => 'Menunggu Pembayaran',
                        1 => 'Sudah Dibayar',
                        2 => 'Sedang Dikirim',
                        3 => 'Sudah Selesai',
                        4 => 'Dibatalkan'
                    ];
                    echo $statusList[$row['status']] ?? 'Tidak Diketahui';
                ?></td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total Harga</th>
                <th colspan="2"><?= number_to_currency($total, 'IDR') ?></th>
            </tr>
        </tfoot>
    </table>
</div>
<?= $this->endSection() ?>
