<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
History Transaksi Pembelian <strong><?= $username ?></strong>
<hr>
<div class="table-responsive">
    <!-- Table with stripped rows -->
    <table class="table datatable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID Pembelian</th>
                <th scope="col">Waktu Pembelian</th>
                <th scope="col">Total Bayar</th>
                <th scope="col">Alamat</th>
                <th scope="col">Status</th>
                <th scope="col">Bukti Pembayaran</th>
                <th scope="col">Detail & Upload</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($buy)) :
                foreach ($buy as $index => $item) :
            ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1 ?></th>
                        <td><?php echo $item['id'] ?></td>
                        <td><?php echo $item['created_at'] ?></td>
                        <td><?php echo number_to_currency($item['total_harga'], 'IDR') ?></td>
                        <td><?php echo $item['alamat'] ?></td>
                        <!-- ($item['status'] == "1") ? "Sudah Selesai" : "Belum Selesai" ?> --> 
                        <td><?php echo [ 
                        0 => 'Menunggu Pembayaran', 
                        1 => 'Sudah Dibayar', 
                        2 => 'Sedang Dikirim', 
                        3 => 'Sudah Selesai', 
                        4 => 'Dibatalkan' 
                        ][$item['status']] ?? 'Status Tidak Diketahui' ?> 
                        </td> 
                        <td>
                            <!-- Bukti Pembayaran -->
                            <?php if (!empty($item['bukti_pembayaran'])): ?>
                                <img src="<?= base_url('bukti/' . $item['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width:100px;max-height:100px;display:block;">
                            <?php else: ?>
                                <span class="text-muted">Belum ada bukti</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-success btn-sm px-2 py-1" style="font-size:0.9rem;" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $item['id'] ?>">
                                    Detail
                                </button>
                                <button type="button" class="btn btn-primary btn-sm px-2 py-1" style="font-size:0.9rem;" data-bs-toggle="modal" data-bs-target="#uploadBuktiModal-<?= $item['id'] ?>">
                                    Upload Bukti
                                </button>
                            </div>
                            <!-- Modal Upload Bukti per transaksi -->
                            <div class="modal fade" id="uploadBuktiModal-<?= $item['id'] ?>" tabindex="-1" aria-labelledby="uploadBuktiLabel-<?= $item['id'] ?>" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="uploadBuktiLabel-<?= $item['id'] ?>">Upload Bukti Pembayaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form action="<?= base_url('profile/upload_bukti/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                      <div class="mb-3">
                                        <label for="bukti_pembayaran_<?= $item['id'] ?>" class="form-label">Bukti Pembayaran (gambar)</label>
                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_<?= $item['id'] ?>" class="form-control" accept="image/*" required>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                      <button type="submit" class="btn btn-primary">Kirim</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Detail Modal Begin -->
                    <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Data</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php 
                                    if(!empty($product)){
                                        foreach ($product[$item['id']] as $index2 => $item2) : ?>
                                            <?php echo $index2 + 1 . ")" ?>
                                            <?php if ($item2['foto'] != '' and file_exists("img/" . $item2['foto'] . "")) : ?>
                                                <img src="<?php echo base_url() . "img/" . $item2['foto'] ?>" width="100px">
                                            <?php endif; ?>
                                            <strong><?= $item2['nama'] ?></strong>
                                            <?= number_to_currency($item2['harga'], 'IDR') ?>
                                            <br>
                                            <?= "(" . $item2['jumlah'] . " pcs)" ?><br>
                                            <?= number_to_currency($item2['subtotal_harga'], 'IDR') ?>
                                            <hr>
                                        <?php 
                                        endforeach; 
                                    }
                                    ?>
                                    Ongkir <?= number_to_currency($item['ongkir'], 'IDR') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Detail Modal End -->
            <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
    <!-- End Table with stripped rows -->
</div>
<?= $this->endSection() ?>