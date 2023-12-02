<?php 
$this->load->view('tema/Header', $title); 
$today = date('Y-m-d');
?>

<script src="<?= base_url('css_maruti/'); ?>js/jquery.min.js"></script> 
<script src="<?php echo base_url('css_maruti/'); ?>assets/ajax.js"></script>

<!-- ======================================================== conten ======================================================= -->
<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
	<div class="content-header">
		<div class="container">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Customer Cancel</h1>
				</div>
				<div class="col-sm-6">
					<button data-toggle="modal" data-target="#modal-summary" class="btn btn-success"><i class="fas fa-print"></i> Summary</button>
					<button data-toggle="modal" data-target="#filter_tgl" class="btn btn-success"><i class="fas fa-eye"></i> View</button>
				</div>
			</div>
		</div><br>
		<div class="container">
			<table class="table" id="example1">
				<thead>
					<tr>
						<th>No. </th>
						<th>Tanggal</th>
						<th>Nama Customer</th>
						<th>No. Telp</th>
						<th>Keterangan</th>
						<th width="5%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<form action="<?= base_url() ?>match/add_cancel" method="post">
							<td>#</td>
							<td>
								<input type="date" class="form-control" name="tgl" required="">
							</td>
							<td>
								<input type="text" class="form-control" name="nama" placeholder="Isi Nama" required="">
							</td>
							<td>
								<input type="number" class="form-control" name="telp" placeholder="Cth : 081212345678" required="">
							</td>
							<td>
								<input type="text" class="form-control" name="ket" placeholder="Keterangan" required="">
							</td>
							<td>
								<button type="submit" class="btn btn-primary">Simpan</button>
							</td>
						</form>
					</tr>
					<?php foreach ($cancel as $key => $value): ?>
						<tr>
							<td width="4%"><?= $key+1 ?></td>
							<td><?=  date('D, d-M-y', strtotime($value->tgl)) ?></td>
							<td width="18%"><?= $value->nama ?></td>
							<td width="20%">
							    <?php if (empty($value->telepon)): ?>
                                    -
                                    <?php else: ?>
                                    <?= $value->telepon ?>
                                <?php endif ?>
							    
							    </td>
							<td><?= $value->ket ?></td>
							<td width="12%">
								<a href="<?= base_url() ?>match/edit_c/<?= $value->id_cancel ?>" class="btn btn-warning"><i class="fa fa-edit"></i></a>
								<a href="<?= base_url() ?>match/del_cancel/<?= $value->id_cancel ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ?')"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<form action="<?= base_url('Match/summary_cancel'); ?>" method="post">
		<div class="modal fade" id="modal-summary">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background:#FFA07A;">
						<h4 class="modal-title">Export Summary</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<table>
								<tr>
									<td ><label for="">Nama Customer</label></td>
									<td>:</td>
									<td>
										<select name="nama" id="" class="form-control select2" required="">
											<option value="">- Pilih Customer -</option>
											<?php foreach ($summary as $key => $value): ?>
												<option value="<?= $value->nama ?>"><?= $value->nama ?></option>
											<?php endforeach ?>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<form action="<?= base_url('Match/cancel'); ?>" method="post">
					<div class="modal fade" id="filter_tgl">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header" style="background:#FFA07A;">
									<h4 class="modal-title">View Data</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<table>
											<tr>
												<td ><label for="">Tanggal</label></td>
												<td>:</td>
												<td> <input style="width: 350px;" class="form-control" type="input" value="<?= date("Y-m-d"); ?>" name="tanggal" id="picker"></td>
											</tr>
										</table>

										<input class="form-control" type="date" value="" id="tanggal1" name="tgl1" hidden>  
										<input class="form-control" type="date" value="" id="tanggal2" name="tgl2" hidden> 
									</div>
									<div class="modal-footer justify-content-between">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="submit" class="btn" style="background:#FFA07A;">Lanjutkan</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>


	<!-- ======================================================== conten ======================================================= -->




	<!-- ======================================================== conten ======================================================= -->

	<script>
		function autofill_anak(){
			var nm_kry = document.getElementById('nm_kry').value;
			$.ajax({
				url:"<?php echo base_url();?>Match/cari_anak",
				data:'&nm_kry='+nm_kry,
				success:function(data){
					var hasil = JSON.parse(data);  

					$.each(hasil, function(key,val){ 
						document.getElementById('id_kry').value=val.id_kry;
						document.getElementById('nm_kry').value=val.nm_kry;  
					});
				}
			});                   
		}

	</script>

	<?php $this->load->view('tema/Footer'); ?>