<?php include_once('../config.php'); include('../paginator.class.php'); include('addtenant.php'); include('addaccount.php'); ?>
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
        color: #FFC107;
    }
	    table.table td a.delete{
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
		input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
	-webkit-appearance: none;
	margin: 0;
}

/* Firefox */
input[type=number] {
	-moz-appearance: textfield;
}
	</style>
</head>

<body>


	<div class="container">

	</br>
	<div class="container" colspan="8" align="center">
		<Strong><span>Tenant Management <p class="fas fa-person-booth"></p> &nbsp;</span></strong>

	</div>
</br>
</br>
				<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<div class="form-inline">
						<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
							 <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Name of tenant">
							 <div class="col-lg-2">

							 </div>

							<a href="#addTenant" class="btn btn-primary" data-toggle="modal"><span>Add New Tenant</span></a>

					</div>
				</form>
		<hr>
		<?php
		if(isset($_REQUEST['tb1'])) {
			$condition		=	"";
			if(isset($_GET['tb1']) and $_GET['tb1']!="")
			{
				$condition		.=	" AND Tenant_Name LIKE'%".$_GET['tb1']."%'";
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM tenant WHERE 1 ".$condition." AND status ='Archived' AND haveAcc ='0'");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM tenant WHERE 1 ".$condition.",  ORDER BY Tenant_Name ASC ".$pages->limit." AND status ='Archived' AND haveAcc ='0'");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM tenant");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM tenant where status ='Archived' AND haveAcc ='0' ORDER BY Tenant_Name ASC ".$pages->limit."");
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
					<th>Contact</th>
					<th>Address</th>
          <th style="text-align:center;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($pages->items_total>0){
					$n=1;
					$s=0;
					$Hello = array($pages->items_total);

					while($val = $result->fetch_assoc()){
						$idEdit = $val['Tenant_Id'];
						$tenantName = $val['Tenant_Name'];
				?>
				<tr>
					<?php $n++; ?>
					<td><?php echo $val['Tenant_Id']; ?></td>
					<td><?php echo $val['Tenant_Name']; ?></td>
					<td><?php echo $val['Tenant_Contact']; ?></td>
					<td><?php echo $val['Home_Address']; ?></td>
					<?php $Hello[$n-2] = $val['Tenant_Id']; ?>

          <td style="text-align:center;">
						<a class ="edit" href="#edit<?php echo $idEdit;?>" data-toggle="modal">
              <i class="fas fa-eye" data-toggle="tooltip" title="More Information"></i></a>
					<?php
						if($val['haveAcc'] == 0)
						{
								echo '<a class ="account" ';
								echo "href='#account"; echo $idEdit;
								echo "'data-toggle='modal'>";
								echo "<i class='fas fa-file-signature' data-toggle='tooltip' title='Add Account'></i></a>";

						}
						else{

						}
						 ?>
				  <a href="#paymentT<?php echo $idEdit;?>" name = "tenantPayment" class="payment" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" title="Add payment transaction" ></i></a>

					<a href="#deleteTenant<?php echo $idEdit;?>" name = "s1s" class="delete" data-toggle="modal"><i class="	fas fa-trash" data-toggle="tooltip" title="Delete" onclick="showTableData(<?php echo $n; ?>);"></i></a>
					</td>

					<div id="deleteTenant<?php echo $idEdit;?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form method="post">
												<div class="modal-header">
													<h4 class="modal-title">Delete Tenant Record</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
													<p>Are you sure you want to delete <?php echo $val['Tenant_Name'];?>'s Records?</p>
													<p class="text-warning"><small>This action cannot be undone.</small></p>
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
													<input type="hidden" id="btnClickedValue" name="btnClickedValue" value="<?php echo $val['Tenant_Id']; ?>" />
														<input type="hidden" id="TenantRoomnumber" name="TenantRoomnumber" value="<?php echo $val['roomnumber']; ?>" />
														<input type="hidden" id="Tenantlocation" name="Tenantlocation" value="<?php echo $val['location']; ?>" />
													<input type="submit" name ="mainDe"class="btn btn-danger" value="Delete">
												</div>
											</form>
										</div>
									</div>
						</div>
					<!--
					initial div element for adding tenant
					-->
							<div id="account<?php echo $idEdit;?>" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
											<div class="modal-header">
												<h4 class="modal-title">Create Account For <?php echo $val['Tenant_Name'];?></h4>
													<input name="Tennnnnn" type = "hidden" class="form-control" value="<?php echo $val['Tenant_Id']; ?>">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label>Username <span class="badge badge-danger">Space and special characters not allowed, Minimum of 6 characters</span></label>
													<input name="TenantUsername" class="form-control" pattern="[A-Za-z0-9]+.{5,}" required>
												</div>
												<div class="form-group">
													<label>Password <span class="badge badge-danger">No Spaces allowed, Minimum of 6 characters</span></label>
													<input name="TenantPassword" class="form-control" pattern="[^ ]+.{5,}" required>
												</div>
											</div>
											<div class="modal-footer">
												<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
												<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" name="addaccountid" value="Add">
											</div>
										</form>
									</div>
								</div>
							</div>
					<!--
					end div for adding tenant
					-->
					<!--
					initial div element for adding tenant
					-->




							<div id="paymentT<?php echo $idEdit;?>" class="modal fade">

			.0					<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
											<div class="modal-header">
												<h4 class="modal-title">Make Payment transaction for <?php echo $val['Tenant_Name'];?> on this date?</h4>
													<input name="PaymentTenantID" type = "hidden" class="form-control" value="<?php echo $val['Tenant_Id']; ?>">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											<div class="modal-body">


												<div class="form-group">
													<label>Date Started <span class="badge badge-danger">Date the tenant started occupying a room</span></label>
													<input class="form-control" type="date" name="dateStartedpayment" min="1900-05-11" max="<?php $today11 = date('Y-m-d'); echo $today11 ?>">
												</div>
												<div class="form-group">
													<label>Rent <span class="badge badge-danger">Amount of rent to be paid</span></label>
													<input name = "rentPayment" type = "number" min = "1" class="form-control price">
												</div>
												<div class="form-group">
													<label>Electric <span class="badge badge-danger">Amount of electric bill/span></label>
													<input name = "electricpayment" type = "number" min = "1" class="form-control price">
												</div>
												<div class="form-group">
													<label>Others <span class="badge badge-danger">Other miscellaneous fees</span></label>
													<input name = "OthersPayment" type = "number" min = "1" class="form-control price">
												</div>
												<div class="form-group">
													<label>Late Fess <span class="badge badge-danger">fees for paying late</span></label>
													<input name = "latefees" type = "number" min = "1" class="form-control price">
												</div>
												<div class="form-group">
													<label>Excess <span class="badge badge-danger">Excess fees</span></label>
													<input name = "excess" type = "number" min = "1" class="form-control price">
												</div>
												<div class="form-group">
													<label>Total Due</label>
													<input name = "totalDueDate" type = "number" id="totalPrice" min = "1" class="form-control">
												</div>
												<div class="form-group">
													<label>Cash</label>
													<input name = "cashpayment" type = "number" min = "1" class="form-control">
												</div>
												<div class="form-group">
													<label>Deposit Slip <span class="badge badge-danger">Reference number on the deposit slip</span></label>
													<input name="DepositSlip" class="form-control">
												</div>
												<div class="form-group">
													<label>Month Validity <span class="badge badge-danger">Payment will be valid until</span></label>
													<input class="form-control" type="date" name="validitymonth" min="<?php $today11 = date('Y-m-d'); echo $today11 ?>">
												</div>
												<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
												<script>
												// we used jQuery 'keyup' to trigger the computation as the user type
												$('.price').keyup(function () {

												    // initialize the sum (total price) to zero
												    var sum = 0;

												    // we use jQuery each() to loop through all the textbox with 'price' class
												    // and compute the sum for each loop
												    $('.price').each(function() {
												        sum += Number($(this).val());
												    });

												    // set the computed value to 'totalPrice' textbox
												    $('#totalPrice').val(sum);

												});
												</script>


											</div>
											<div class="modal-footer">
												<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
												<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" name="paymentTransaction" value="Make Transaction">
											</div>
										</form>
									</div>
								</div>
							</div>








										<!--
										initial div element for Editing tenant
										-->
										<div id="edit<?php echo $idEdit; ?>" class="modal fade">
											<div class="modal-dialog">
												<div class="modal-content">
													<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
														<div class="modal-header">


															<?php echo "<span id='spanForID'></span>"; ?>


															<?php

															$sqled = "SELECT * from tenant where Tenant_Id =?";
															if($stmt = $mysqli->prepare($sqled)){
																$stmt->bind_param("s", $paramTen);
																$paramTen = $idEdit;
																	$stmt->execute();
																	$resulthere = $stmt->get_result();
																	 $TenantRES = $resulthere->fetch_assoc();

																	}
																	$stmt->close(); ?>

															<h4 class="modal-title">	<?php echo $TenantRES['Tenant_Name']; ?>'s information</h4>
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														</div>
														<div class="modal-body">
																<input name="editOldlocation" type = "hidden" class="form-control" value="<?php echo $TenantRES['location']; ?>">
																<input name="editOldRoomNUmber" type = "hidden" class="form-control" value="<?php echo $TenantRES['roomnumber']; ?>">
																<input name="edittetenantID" type = "hidden" class="form-control" value="<?php echo $TenantRES['Tenant_Id']; ?>">
																<div class="form-group">
																<label>Location</label>
																<?php $initial = "0"; ?>
																	<select id="select2" name="editlocation" class="form-control">
																	<option value="<?php echo $TenantRES['location']; ?>"><?php echo $TenantRES['location']; ?></option>
																		<option value="Mandaluyong City">Mandaluyong City</option>
																		<option value="Makati City">Makati City</option>
																		<option value="Pasig City">Pasig City</option>
																	</select>
																</div>
															<div class="form-group">
																	<label>Room Number</label>
																	<input id="roomnumber" onkeyup="validate();" type="number" value="<?php echo $TenantRES['roomnumber'];?>" name = "editroomnumber" class="form-control" required>
															</div>
															<div class="form-group">
																	<label>Number of Occupants</label>
																	<input id="editoccupants" onkeyup="validate();" min="1" type="number" value="<?php echo $TenantRES['occupants'];?>" name = "editoccupants" class="form-control" required>
															</div>
															<div>
															<div class="form-group">
															</div>
															<div class="form-group">
																<label>Full Name</label>
																<input id="inputField" onkeyup="validate();" type="text" name = "editfullname" class="form-control" value="<?php echo mb_strtoupper($TenantRES['Tenant_Name']); ?>" required>
															</div>

															<div class="form-group">
																<label>Birthdate</label>
																<input class="form-control" id ="txtDate" type="date" name="editbirthday" max="2015-01-01" value="<?php echo $TenantRES['Birthdate']; ?>">
															</div>
															<div class="form-group">
																<label>Home Address</label>
																<textarea name = "edithomeaddress" class="form-control"><?php echo $TenantRES['Home_Address']; ?></textarea>
															</div>
															<div class="form-group">
																<label>Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
																	<input type="tel" name="editphonenumber" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" value="<?php echo $TenantRES['Tenant_Contact']; ?>"required >
															</div>
															<div class="form-group">
																<label>Guardian Full Name <span class="badge badge-info">Format("Letters Only")</span></label>
																<input name="editguardianFullname" class="form-control" pattern="[A-Za-z ]+" value="<?php echo $TenantRES['Guardian_Name']; ?>">
															</div>
															<div class="form-group">
																<label>Guardian Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
																	<input type="tel" name="editguardianphonenumber" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" value = "<?php echo $TenantRES['Guardian_Contact']; ?>"required >
															</div>
															<div class="form-group">
																<label>Address of present apartment</label>
																<textarea name = "editaddressofpresent" class="form-control"><?php echo $TenantRES['Address_Of_Present_Apartment']; ?></textarea>
															</div>
															<div class="form-group">
																<label>Reason For Leaving</label>
																<textarea name = "editreasonforleaving" class="form-control"><?php echo $TenantRES['Reason_For_Leaving']; ?></textarea>
															</div>
															<div class="form-group">
																<label>Years of stay in present apartment</label>
																<input name = "edityearsofstayinpresent" type = "number" min = "0" max = "99" class="form-control" value="<?php echo $TenantRES['Years_Of_Stay_In_Present_Apartment']; ?>">
															</div>
															<div class="form-group">
																<label>Name of school or work</label>
																<textarea name = "editnameofschoolwork" class="form-control" ><?php echo $TenantRES['Name_Of_Schoolwork']; ?></textarea>
															</div>
															<div class="form-group">
																<label>Address of school or work</label>
																<textarea name = "editaddressofschoolwork" class="form-control"><?php echo $TenantRES['Address_Of_Present_Apartment']; ?></textarea>
															</div>
															<div class="form-group">
																<label>Position in Company <span class="badge badge-info">Format("Letters Only")</span></label>
																<input name="editpositionincompany" class="form-control" pattern="[A-Za-z ]+" value="<?php echo $TenantRES['Position_In_Company']; ?>">
															</div>
															<div class="form-group">
																<label>Name Of immediate Supervisor <span class="badge badge-info">Format("Letters Only")</span></label>
																<input name="editNameimmediatesupervisor" class="form-control" pattern="[A-Za-z ]+" value="<?php echo $TenantRES['Name_Of_Immediate_Supervisor']; ?>">
															</div>
															<div class="form-group">
																<label>Number of years in present position</label>
																<input name = "edityearsinpresentposition" type = "number" min = "0" max = "99" class="form-control" value="<?php echo $TenantRES['Number_Of_years_In_present_Position']; ?>">
															</div>
														</div>
														</div>
														<div class="modal-footer">
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
															<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" name="editTen" value="Apply">
														</div>
													</form>
												</div>
											</div>
										</div>
										<!--
										end div for Editing tenant
										-->
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



		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

<!--
initial div element for adding tenant
-->
		<div id="addTenant" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="modal-header">
							<h4 class="modal-title">Add Tenant</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>Location</label>
								<?php $initial = "0"; ?>
								<select id="select1" name="location" class="form-control">
									<option>Please select A location</option>
									<!--<?php
										//$Continentqry = $mysqli->query('SELECT DISTINCT Room_Number FROM room Where 1 AND Number_Of_Available_Beds >=1 ORDER BY Room_Number ASC ');
										//while($crow = $Continentqry->fetch_assoc()) {
										//	$n = 0;
										//	echo "<option value = '{$crow['Room_Number']}'";
										//	if(isset($_REQUEST['roomnumber']) and $_REQUEST['tb1']==$crow['Room_Number'])
										//	echo ' selected="selected"';
										//	echo ">{$crow['Room_Number']}</option>\n";
										//	$n++;
										//}

									?>-->
									<option value="Mandaluyong City">Mandaluyong City</option>
									<option value="Makati City">Makati City</option>
									<option value="Pasig City">Pasig City</option>
								</select>
								<script type="text/javascript">
								    $(function () {
								        $("#select1").change(function () {
								            var selectedText = $(this).find("option:selected").text();
								            var selectedValue = $(this).val();
														var x = document.getElementById("myDIV");
								            if(selectedText == "Please select Room number")
														{
															    x.style.display = "none";
														}else {
															 x.style.display = "block";
														}
								        });
								    });
								</script>


							</div>
						<div id="myDIV" style="display:none">
							<div class="form-group">
									<label>Room Number</label>
									<input id="roomnumber" onkeyup="validate();" type="number" name = "roomnumber" class="form-control" required>
							</div>
							<div class="form-group">
									<label>Number of Occupants</label>
									<input id="occupants" onkeyup="validate();" type="number" name = "occupants" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Full Name</label>
								<input id="inputField" onkeyup="validate();" type="text" name = "fullname" class="form-control" required>
							</div>
							<div class="form-group">
								<label>Birthdate</label>
								<input class="form-control" id ="txtDate" type="date" name="birthday" min="1900-05-11" max="2015-01-01">
							</div>
							<div class="form-group">
								<label>Home Address</label>
								<textarea name = "homeaddress" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
								  <input type="tel" name="phonenumber" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" required >
							</div>
							<div class="form-group">
								<label>Guardian Full Name <span class="badge badge-info">Format("Letters Only")</span></label>
								<input name="guardianFullname" class="form-control" pattern="[A-Za-z ]+">
							</div>
							<div class="form-group">
								<label>Guardian Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
								  <input type="tel" name="guardianphonenumber" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" required >
							</div>
							<div class="form-group">
								<label>Address of present apartment</label>
								<textarea name = "addressofpresent" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>Reason For Leaving</label>
								<textarea name = "reasonforleaving" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>Years of stay in present apartment</label>
								<input name = "yearsofstayinpresent" type = "number" min = "1" max = "99" class="form-control">
							</div>
							<div class="form-group">
								<label>Name of school or work</label>
								<textarea name = "nameofschoolwork" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>Address of school or work</label>
								<textarea name = "addressofschoolwork" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>Position in Company <span class="badge badge-info">Format("Letters Only")</span></label>
								<input name="positionincompany" class="form-control" pattern="[A-Za-z ]+">
							</div>
							<div class="form-group">
								<label>Name Of immediate Supervisor <span class="badge badge-info">Format("Letters Only")</span></label>
								<input name="Nameimmediatesupervisor" class="form-control" pattern="[A-Za-z ]+">
							</div>
							<div class="form-group">
								<label>Number of years in present position</label>
								<input name = "yearsinpresentposition" type = "number" min = "1" max = "99" class="form-control">
							</div>
						</div>
						</div>
						<div class="modal-footer">
							<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
							<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" name="addTen" value="Add">
						</div>
					</form>
				</div>
			</div>
		</div>
<!--
end div for adding tenant
-->








		<script>
					    function showTableData(r) {
					        document.getElementById('info').innerHTML = "";
					        var myTab = document.getElementById('empTable');
									var objCells = myTab.rows.item(r-1).cells;
									info.innerHTML = info.innerHTML + ' ' + objCells.item(1).innerHTML;
									document.getElementById("btnClickedValue").value = objCells.item(0).innerHTML;
									document.getElementById("TenantRoomnumber").value = objCells.item(2).innerHTML;
									<?php
		             $haha = $_POST['btnClickedValue'];
								 $paramRoomnumberTenant = $_POST['TenantRoomnumber'];
		          ?>
					        // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
					    }



			</script>


		<script>
		$(function(){
		    var dtToday = new Date();

		    var month = dtToday.getMonth() + 1;
		    var day = dtToday.getDate();
		    var year = dtToday.getFullYear();

		    if(month < 10)
		        month = '0' + month.toString();
		    if(day < 10)
		        day = '0' + day.toString();

		    var maxDate = year + '-' + month + '-' + day;
		    $('#txtDate').attr('max', maxDate);
		});
		$("#inputField").keyup(function(e) {
		  // Our regex
		  // a-z => allow all lowercase alphabets
		  // A-Z => allow all uppercase alphabets
		  // 0-9 => allow all numbers
		  // @ => allow @ symbol
		  var regex = /^[a-zA-Z ]+$/;
		  // This is will test the value against the regex
		  // Will return True if regex satisfied
		  if (regex.test(this.value) !== true)
		  //alert if not true
		  //alert("Invalid Input");
		  // You can replace the invalid characters by:
		    this.value = this.value.replace(/[^a-zA-Z ]+/, '');
		});

		</script>
</body>
</html>
