<?= $this->extend('layout') ?> 
<?= $this->section('content') ?> 
Daftar Transaksi 
<hr> 
<?= session()->getFlashdata('success') ?> 
<?= session()->getFlashdata('error') ?> 
<div class="table-responsive"> 
    <!-- Table with stripped rows --> 
    <table class="table datatable"> 
        <thead> 
            <tr> 
                <th scope="col">#</th> 
                <th scope="col">ID</th> 
                <th scope="col">UserName</th> 
                <th scope="col">Total Harga</th> 
                <th scope="col">Alamat</th> 
                <th scope="col">Ongkir</th> 
                <th scope="col">Status</th> 
                <th scope="col">Bukti Pembayaran</th>
                <th scope="col">Ubah Status</th> 
            </tr> 
        </thead> 
        <tbody> 
            <?php 
            if (!empty($transactions)) : 
                foreach ($transactions as $index => $item) : 
            ?> 
                    <tr> 
                        <th scope="row"><?php echo $index + 1 ?></th> 
                        <td><?php echo $item['id'] ?></td> 
                        <td><?php echo $item['username'] ?></td> 
                        <td><?php echo number_to_currency($item['total_harga'], 
'IDR') ?></td> 
                        <td><?php echo $item['alamat'] ?></td> 
                        <td><?php echo $item['ongkir'] ?></td> 
                        <td><?php echo [ 
                        0 => 'Menunggu Pembayaran', 
                        1 => 'Sudah Dibayar', 
                        2 => 'Sedang Dikirim', 
                        3 => 'Sudah Selesai', 
                        4 => 'Dibatalkan' 
                    ][$item['status']] ?? 'Status Tidak Diketahui' ?></td> 
                        <td>
                            <?php if (!empty($item['bukti_pembayaran'])): ?>
                                <img src="<?= base_url('bukti/' . $item['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width:100px;max-height:100px;display:block;">
                            <?php else: ?>
                                <span class="text-muted">Belum ada bukti</span>
                            <?php endif; ?>
                        </td>
                    <td>
            <?php if (session()->get('role') === 'admin') : ?>
                <form class="d-flex align-items-center gap-2 mb-0" action="<?= base_url('penjualan/updateStatus/' . $item['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <select name="status" class="form-select" style="min-width:140px;max-width:180px;">
                        <option value="0" <?= $item['status'] == '0' ? 'selected' : '' ?>>Pending</option>
                        <option value="1" <?= $item['status'] == '1' ? 'selected' : '' ?>>Paid</option>
                        <option value="2" <?= $item['status'] == '2' ? 'selected' : '' ?>>Shipped</option>
                        <option value="3" <?= $item['status'] == '3' ? 'selected' : '' ?>>Completed</option>
                        <option value="4" <?= $item['status'] == '4' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-success btn-sm px-2 py-1" title="Update Status">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                </form>
            <?php else : ?>
                <span class="text-muted">-</span>
            <?php endif; ?>
        </td>
    </tr> 
    <?php endforeach;   
            endif; 
            ?> 
        </tbody> 
    </table> 
    <!-- End Table with stripped rows --> 
</div> 
<?= $this->endSection() ?>