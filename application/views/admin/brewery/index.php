<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper" style="margin-top: 55px;"> -->
	<section class="content">
		<div class="card">
			<div class="card-header">
				<div class="d-inline-block">
					<h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= $title ?></h3>
				</div>
				<div class="d-inline-block float-right">
					<a href="<?= base_url('admin/brewery/Brewery/add'); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_new_brewery') ?></a>
				</div>
			</div>

			<div class="card-body">
				<table id="example2" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th width="50"><?= trans('id') ?></th>
							<th><?= trans('brewery_name') ?></th>
							<th><?= trans('address') ?></th>
							<th><?= trans('contact_person') ?></th>
							<th width="200"><?= trans('mobile_no') ?></th>
							<th width="200"><?= trans('email') ?></th>
							<th width="200"><?= trans('action') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($records as $record): ?>
							<tr>
								<td><?php echo $record['id']; ?></td>
								<td><?php echo $record['brewery_name']; ?></td>
								<td><?php echo $record['address']; ?></td>
								<td><?php echo $record['contact_person_name']; ?></td>
								<td><?php echo $record['mail_id']; ?></td>
								<td><?php echo $record['mobile_no']; ?></td>
								<td>
									<?php if(!in_array($record['id'],array(0))): ?>
										<a href="<?php echo site_url("admin/brewery/edit/".$record['id']); ?>" class="btn btn-warning btn-xs mr5" >
											<i class="fa fa-edit"></i>
										</a>
										<a href="<?php echo site_url("admin/brewery/delete/".$record['id']); ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>
									<?php endif;?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>

	<script>
		$("body").on("change",".tgl_checkbox",function(){
			$.post('<?=base_url("admin/admin_roles/change_status")?>',
			{
				'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',	
				id : $(this).data('id'),
				status : $(this).is(':checked') == true ? 1:0
			},
			function(data){
				$.notify("Status Changed Successfully", "success");
			});
		});

	</script>