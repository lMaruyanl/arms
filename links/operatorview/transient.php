<?php include_once('../config.php'); include('../paginator.class.php');  include('approvetrans.php');?>
<!doctype html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec"  prefix="og: http://ogp.me/ns#" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>PHP pagination class with Bootstrap 4</title>

	<link rel="shortcut icon" href="https://demo.learncodeweb.com/favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style type="text/css">
	body{
		background-color:rgba(255, 255, 255, 0.0);
	}
		table.table td a:hover {
			color: #2196F3;
		}

	    table.table td a.edit {
	        color: #F44336;
	    }
	    table.table td i {
	        font-size: 19px;
	    }
		table.table .avatar {
			border-radius: 50%;
			vertical-align: middle;
			margin-right: 10px;
		}
	</style>
</head>

<body>


	<div class="container">

	</br>
	<div class="container" colspan="8" align="center">
    <Strong><span>Transient Application<p class="fas fa-stamp"></p> &nbsp;</span></strong>

  </div>
  </br></br>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class="form-inline">
			<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
				 <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Name of Tenant">
				 <div class="col-lg-2">

				 </div>
		</div>
	</form>
		<hr>
		<?php
		if(isset($_REQUEST['tb1'])) {
			$condition		=	"";
			if(isset($_GET['tb1']) and $_GET['tb1']!="")
			{
				$condition		.=	" AND Guest.Guest_Name LIKE'%".$_GET['tb1']."%'";
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM reservation  Where 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM reservation  Where 1 ".$condition." ORDER BY Guest.Guest_Name ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM reservation ORDER BY Guest_Name ");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM reservation ORDER BY Guest_Name ".$pages->limit."");
    }
		?>
		<div class="clearfix"></div>

		<div class="row marginTop">
			<div class="col-sm-12 paddingLeft pagerfwt">
				<?php if($pages->items_total > 0) { ?>
					<?php echo $pages->display_pages();?>
					<?php echo $pages->display_items_per_page();?>
					<?php echo $pages->display_jump_menu(); ?>
				<?php }?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>

		<table class="table table-bordered table-striped" id="empTable">
			<thead>
				<tr class="header">
				<th>ID</th>
					<th>Guess Name</th>
          <th>Contact</th>
					<th>Email</th>
          <th>Date</th>
					<th>Time</th>
          <th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($pages->items_total>0){
					$n  =   1;
					while($val  =   $result->fetch_assoc()){
				?>
				<tr>
					<?php $n++; ?>
					<td><?php echo $val['Guest_Id']; ?></td>
					<td><?php echo mb_strtoupper($val['Guest_Name']); ?></td>
         			<td><?php echo $val['Contact']; ?></td>
					<td><?php echo $val['guest_email']; ?></td>
          			<td><?php echo ($val['date']); ?></td>
		  			<td><?php echo ($val['time']); ?></td>
          <!--<td style="text-align:center;">
          <?php
        //if($val['Transient_Status'] == 0){
        //  echo "Not Approved <span class='fas fa-exclamation-circle'></span>";
        //}else if($val['Transient_Status'] == 1){
        //  echo "Approved <span class='fas fa-check-circle'></span>";
        // }else if($val['Transient_Status'] == 2){
        //    echo "Pending <span class='fas fa-exclamation-circle'></span>";
        //  }
          ?>
        </td>-->


          <td style="text-align:center;">

						<a class ="edit" href="#edit<?php echo $val['Guest_Id'];?>" data-toggle="modal"><i class="far fa-eye" data-toggle="tooltip" title="View ID"></i></a>
						<a class ="editstatus" href="#status_edit<?php echo $val['Guest_Id'];?>" data-toggle="modal"><i class="fas fa-file-signature" data-toggle="tooltip" title="View ID"></i></a>
						<?php
						if($val['Transient_Status'] == 2)
						{
							echo "<a href='#deleteTenant'";
							echo "name = 's1s' class='delete' data-toggle='modal'>";
							echo "<i class='fas fa-thumbs-up' data-toggle='tooltip' title='Approve' onclick='showTableData(";echo $n;echo")'></i></a>";
							echo "<span> </span>";
							echo "<a href='#dTenant'";
							echo "name = 's1s1' class='delete' data-toggle='modal'>";
							echo "<i class='fas fa-thumbs-down' style = 'color:red;'data-toggle='tooltip' title='Disapprove' onclick='showTableData(";echo $n;echo")'></i></a>";
						}
						else{

						}

						 ?>
            </td>
						<div id="edit<?php echo $val['Guest_Id'];?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
										<div class="modal-header">
											<h4 class="modal-title"><?php echo $val['Guest_Name']; ?>'s ID'</h4>
												<input name="Tennnnnn" type = "hidden" class="form-control" value="<?php echo $TenantRESs['Tenant_Id']; ?>">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body">
											<div class="thumbnail">

									        <img src="../userview/images/<?php echo $val['image'] ?>" alt="Image of Transient" style="width:100%">
									    </div>
										</div>
										<div class="modal-footer">
											<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
										</div>
									</form>
								</div>
							</div>
						</div>
				</tr>
				<div id="status_edit<?php echo $val['Guest_Id'];?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" action="addreservation.php">
										<div class="modal-header">
											<h4 class="modal-title"><?php echo $val['Guest_Name']; ?>'s Reservation Status</h4>
									</div>
											<div class="modal-body">
											<input name="editguestid" type = "hidden" class="form-control" value="<?php echo $val['Guest_Id']; ?>">
											<select id="select2" name="editstatus1" class="form-control">
															<option value="<?php echo $val['status']; ?>"><?php echo $val['status']; ?></option>
															<option value="Pending">Pending</option>
															<option value="Approved">Approved</option>
															<option value="Denied">Denied</option>
															
															</select>
											</div>
											<div class="modal-footer">
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
															<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" name="editstatus" value="Apply">
											</div>
														
										</div>
									</form>
								</div>
							</div>
						</div>
				<?php
					}
				}else{?>
				<tr>
					<td colspan="8" align="center"><strong>No Record(s) Found!</strong></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<div class="clearfix"></div>

		<div class="row marginTop">
			<div class="col-sm-12 paddingLeft pagerfwt">
				<?php if($pages->items_total > 0) { ?>
					<?php echo $pages->display_pages();?>
					<?php echo $pages->display_items_per_page();?>
					<?php echo $pages->display_jump_menu(); ?>
				<?php }?>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>

    </div>


		<div id="deleteTenant" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<form method="post">
									<div class="modal-header">
										<h4 class="modal-title">Approve Transient</h4>
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									</div>
									<div class="modal-body">
										<p>Are you sure you want to approve <span id="info"></span> as transient?</p>
										<p class="text-warning"><small>This action cannot be undone.</small></p>
									</div>
									<div class="modal-footer">
										<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
										<input type="hidden" id="btnClickedValue" name="btnClickedValue" value="" />

										<input type="submit" name ="mainDe"class="btn btn-danger" value="Approve">
									</div>
								</form>
							</div>
						</div>
			</div>
			<div id="dTenant" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post">
										<div class="modal-header">
											<h4 class="modal-title">Decline Transient</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body">
											<p>Are you sure you want to decline this transient?</p>
											<p class="text-warning"><small>This action cannot be undone.</small></p>
										</div>
										<div class="modal-footer">
											<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
											<input type="hidden" id="btnClickedValued" name="btnClickedValued" value="" />

											<input type="submit" name ="mainDe1"class="btn btn-danger" value="Decline">
										</div>
									</form>
								</div>
							</div>
				</div>

		<script>
							function showTableData(r) {
									document.getElementById('info').innerHTML = "";
									var myTab = document.getElementById('empTable');
									var objCells = myTab.rows.item(r-1).cells;
									info.innerHTML = info.innerHTML + ' ' + objCells.item(1).innerHTML;
									document.getElementById("btnClickedValue").value = objCells.item(0).innerHTML;
									document.getElementById("btnClickedValued").value = objCells.item(0).innerHTML;
									document.getElementById("TenantRoomnumber").value = objCells.item(2).innerHTML;
									<?php
								 $haha = $_POST['btnClickedValue'];
								 $paramRoomnumberTenant = $_POST['TenantRoomnumber'];
							?>
									// LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
							}



			</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>
