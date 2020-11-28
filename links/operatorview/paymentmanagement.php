<?php include_once('../config.php'); include('../paginator.class.php'); ?>
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
		<Strong><span>Payment Management <p class="fas fa-stamp"></p> &nbsp;</span></strong>

	</div>
</br>
</br>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class="form-inline">
			<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
				 <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Name of tenant">
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
				$condition		.=	" AND tenant.Tenant_Name LIKE'%".$_GET['tb1']."%'";
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM payment INNER JOIN tenant ON tenant.Tenant_Id=payment.Tenant_Id Where 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM payment INNER JOIN tenant ON tenant.Tenant_Id=payment.Tenant_Id Where 1 ".$condition." ORDER BY Tenant_Name ASC ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM payment INNER JOIN tenant ON tenant.Tenant_Id=payment.Tenant_Id");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM payment INNER JOIN tenant ON tenant.Tenant_Id=payment.Tenant_Id ORDER BY tenant.Tenant_Name ASC ".$pages->limit."");
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
					<th>Name</th>
         				<th>Dept Slip</th>
					<th>Date Started</th>
         				<th>Payment Status</th>
					<th>Total Due</th>
          				<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($pages->items_total>0){
					$n  =   1;
					while($val  =   $result->fetch_assoc()){
							$idEdit = $val['Payment_Id'];
				?>
				<tr>
					<td><?php echo $val['Payment_Id']; ?></td>
					<td><?php echo $val['Tenant_Name']; ?></td>
         				<td><?php echo $val['Dep_Slip']; ?></td>
					<td><?php echo $val['Date_Started']; ?></td>
          <td style="text-align:center;">
          <?php
          if($val['Payment_Status'] == 0){
            echo "NOT PAID <span class='fas fa-exclamation-circle'></span>";
          }else if($val['Payment_Status'] == 1){
            echo "PAID <span class='fas fa-check-circle'></span>";
          }
					else if($val['Payment_Status'] == 3){
            echo "PENDING <span class='fas fa-check-circle'></span>";
          }
          ?>
        </td>
					<td class="row_data"><?php echo $val['Total_Due']; ?></td>


						<td style="text-align:center;">
							<a class ="edit" href="#edit<?php echo $idEdit;?>" data-toggle="modal">
	              <i class="fas fa-eye" data-toggle="tooltip" title="More Information"></i></a>
						</td>



						<div id="edit<?php echo $idEdit; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
										<div class="modal-header">


											<?php echo "<span id='spanForID'></span>"; ?>


											<h4 class="modal-title">	<?php echo $val['Tenant_Name']; ?>'s payment information</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body">
											<div class="form-group">
												<label>Date Of transaction</label>
												<input value = "<?php echo $val['date_of_transaction']; ?>" class="form-control" type="date" name="dateStartedpayment1" min="1900-05-11" max="<?php $today11 = date('Y-m-d'); echo $today11 ?>" disabled>
											</div>
											<div class="form-group">
												<label>Date Started</label>
												<input value = "<?php echo $val['Date_Started']; ?>" class="form-control" type="date" name="dateStartedpayment1" min="1900-05-11" max="<?php $today11 = date('Y-m-d'); echo $today11 ?>" disabled>
											</div>
											<div class="form-group">
												<label>Rent</label>
												<input value = "<?php echo $val['Rent']; ?>" name = "rentPaymen1t" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Electric</label>
												<input value = "<?php echo $val['Electric']; ?>" name = "electricpayment1" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Others</label>
												<input value = "<?php echo $val['Others']; ?>" name = "OthersPayment1" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Late Fess</label>
												<input value = "<?php echo $val['Late_Fee']; ?>" name = "latefees1" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Excess</label>
												<input value = "<?php echo $val['Excess']; ?>" name = "excess1" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Total Due</label>
												<input value = "<?php echo $val['Total_Due']; ?>" name = "totalDueDate1" type = "number" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Cash</label>
												<input value = "<?php echo $val['Cash']; ?>" name = "cashpayment1" type = "number1" min = "1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Deposit Slip</label>
												<input value = "<?php echo $val['Dep_Slip']; ?>" name="DepositSlip1" class="form-control" disabled>
											</div>
											<div class="form-group">
												<label>Month Validity</label>
												<input value = "<?php echo $val['validtil']; ?>"class="form-control" type="date1" name="validitymonth1" min="<?php $today11 = date('Y-m-d'); echo $today11 ?>" disabled>
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



    <script> let cells = Array.prototype.slice.call(document.querySelectorAll(".row_data"));
    // Loop over the array
    cells.forEach(function(cell){
      // Convert cell data to a number, call .toLocaleString()
      // on that number and put result back into the cell
      cell.textContent = (+cell.textContent).toLocaleString('en-US', { style: 'currency', currency: 'PHP' });
    });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>
