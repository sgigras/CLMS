<!DOCTYPE html>
<html>
<!-- <head>
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>asset/bootstrap.min.css" />
	<script src="<?php echo base_url(); ?>asset/jquery.min.js"></script>
</head> -->
<style>
	.main-card{
		padding:20px;
	}
/*.input-group.custom-file {
    display: flex;
    align-items: center;
}*/


/*.input-group-append{

display: flex;
}*/
div {
	display: block;
}
/*.input-group-append.btn {
    position: relative;
     z-index: 2; 
}
	/* .inside-card{
		display:inline;
	} */*/
</style>
<body>
	
	<div class="container">
		<br />
		<div class='card main-card' >
			<h4>Import Excel Data</h4>
			<br>
			<!-- <form method="post" id="import_form" enctype="multipart/form-data"> -->
				<?php echo form_open(base_url('admin/Import_excel/import'),  array("id" => "import_form", "class" => "form-horizontal"));?>
				

				<div class="row" style="margin:20px;">
					<div class="col-12 col-md-12">
						<div class="row">
						<div class="col-6 col-md-6">
							<div class="form-group">
								<label class="control-label">Select file</label>
								<div class="input-group">
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="file" accept=".xls, .xlsx"/ name="file">
										<label class="custom-file-label" for="exampleInputFile" id="file_name">Choose file</label>

										<!-- <span id="file_name">Select Excel File</span> -->
									</div>
									<div class="input-group-append">

										<button type="button" class="btn  dropdown-toggle" data-toggle="dropdown"  aria-expanded="false">
											<span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(68px, 38px, 0px); top: 0px; left: 0px;" id="template_menu" >

											<option class="dropdown-item" id="template1" value="bsf_tepmlate1">BSF HRMS DATA</option>
											


										</div>
									</div>
								</div>


							</div>
							

						</div>
						<div class="col-6 col-md-6" style="margin-top: 30px;">
							<center>
								<input type="submit" name="import" value="Import" class="btn" style='background-color:#dc3545;color:white;width:120px;height:40px;border-radius:20px;'/>
							</center>
								
									
										
									
							
						</div>
					</div>
						
						</div>
						

				
					<div style="overflow-x:auto;">
						<br>


						<div id="template" style="display:flex;" >
							<table class="table-responsive">
								<thead style="width: 15%; background-color: skyblue; border-radius: 2px; overflow-x:auto; " >
									<tr>
										<th style="width: 10%;">IRLA NO</th>
										<th style="width: 10%;">NAME</th>
										<th style="width: 10%;">MOBILE NO</th>
										<th style="width: 10%;">DATE OF BIRTH</th>
										<th style="width: 10%;">RANK</th>
										<th style="width: 10%;">PRESENT APPOINTMENT</th>
										<th style="width: 10%;">STATUS</th>
										<th style="width: 10%;">LOCATION</th>
										<th style="width: 10%;">DISRICT</th>
										<th style="width: 10%;">STATE</th>
										<th style="width: 10%;">EMAIL ID</th>
										<th style="width: 10%;">POSTING UNIT</th>
										<th style="width: 10%;">FRONTIER</th>
									</tr>
								</thead>
								<tbody >
									<tr>
										<td>12345678</td>
										<td>SUJIT</td>
										<td>9876543210</td>
										<td>1998-12-11</td>
										<td>AC</td>
										<td>DC</td>
										<td>SERVING</td>
										<td>DELHI</td>
										<td>NEW DELHI</td>
										<td>DELHI</td>
										<td>bsf@gmail.com</td>
										<td>bsf-22</td>
										<td>JAMMU</td>
									</tr>
									<tr>
										<td>12345678</td>
										<td>SUJIT</td>
										<td>9876543210</td>
										<td>1998-12-11</td>
										<td>AC</td>
										<td>DC</td>
										<td>SERVING</td>
										<td>DELHI</td>
										<td>NEW DELHI</td>
										<td>DELHI</td>
										<td>bsf@gmail.com</td>
										<td>bsf-22</td>
										<td>JAMMU</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<?php echo form_close();?>
					<!-- </form> -->

				

			</div>
		</div>
	</div>
		</body>
		<script>
			var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
			var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
			var baseurl = "<?php echo base_url(); ?>";
		</script>
		</html>
		<script src="<?php echo base_url(); ?>assets/js/module/import_excel.js"></script>
