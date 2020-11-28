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
		<Strong><span>Tenant information <p class="fas fa-person-booth"></p> &nbsp;</span></strong>

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
				$condition		.=	" AND Tenant_Name LIKE'%".$_GET['tb1']."%'";
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM tenant WHERE 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM tenant WHERE 1 ".$condition." ORDER BY Tenant_Name ASC ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM tenant");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM tenant ORDER BY Tenant_Name ASC ".$pages->limit."");
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
          <th>Room Number</th>
					<th>Contact</th>
					<th>Address</th>
					<th>Payment Status</th>
          <th>Actions</th>
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
				?>
				<tr>
					<?php $n++; ?>
					<td><?php echo $val['Tenant_Id']; ?></td>
					<td><?php echo mb_strtoupper($val['Tenant_Name']); ?></td>
          <td><?php echo mb_strtoupper($val['Room_Number']); ?></td>
					<td><?php echo mb_strtoupper($val['Tenant_Contact']); ?></td>
					<td><?php echo mb_strtoupper($val['Home_Address']); ?></td>
					<?php $Hello[$n-2] = $val['Tenant_Id']; ?>
					<td style="text-align:center;"><?php
					if($val['Payment_Status'] == 0){
						echo "NOT PAID <span class='fas fa-exclamation-circle'></span>";
					}else if($val['Payment_Status'] == 1){
						echo "PAID <span class='fas fa-check-circle'></span>";
					}else if($val['Payment_Status'] == 3)
					{
						echo "PENDING <span class='fas fa-exclamation-circle'></span>";
					}

					?></td>
					<td style="text-align:center;">
						<a class ="edit" href="#edit<?php echo $idEdit;?>" data-toggle="modal">
							<i class="fas fa-eye" data-toggle="tooltip" title="More Information"></i></a>
					<?php
						
						 ?>

					</td>
					<!--
					initial div element for adding tenant
					-->
							<div id="account<?php echo $idEdit;?>" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
											<div class="modal-header">
												<h4 class="modal-title">Create Account For <?php echo $TenantRESs['Tenant_Name'];?></h4>
													<input name="Tennnnnn" type = "hidden" class="form-control" value="<?php echo $TenantRESs['Tenant_Id']; ?>">
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
																<input name="editOldRoomNUmber" type = "hidden" class="form-control" value="<?php echo $TenantRES['Room_Number']; ?>">
																<input name="edittetenantID" type = "hidden" class="form-control" value="<?php echo $TenantRES['Tenant_Id']; ?>">
															<div class="form-group">
																<label>Room Number</label>
																<?php $initial = "0"; ?>
																<select id="select2" name="editroomnumber" class="form-control">
																	<option value="<?php echo $TenantRES['Room_Number']; ?>"><?php echo $TenantRES['Room_Number']; ?></option>
																	<?php
																		$Continentqry = $mysqli->query('SELECT DISTINCT Room_Number FROM room Where 1 AND Number_Of_Available_Beds >=1 ORDER BY Room_Number ASC ');
																		while($crow = $Continentqry->fetch_assoc()) {
																			$n = 0;
																			echo "<option value = '{$crow['Room_Number']}'";
																			if(isset($_REQUEST['editroomnumber']) and $_REQUEST['tb1']==$crow['Room_Number'])
																			echo ' selected="selected"';
																			echo ">{$crow['Room_Number']}</option>\n";
																			$n++;
																		}

																	?>
																</select>


															</div>
														<div>
															<div class="form-group">
																	<label>
																		Bed Type
																	 </label>
																		<select name = "editroomtype" class="form-control">
																			<option value="<?php echo $TenantRES['Room_Type']; ?>"><?php if($TenantRES['Room_Type'] == 1){echo "Upper Deck";}else{echo "Lower Deck";} ?></option>
																				<option value="1">Upper Deck</option>
																				<option value="2">Lower Deck</option>
																		</select>
															</div>
															<div class="form-group">
																<label>Full Name</label>
																<input id="inputField" onkeyup="validate();" type="text" name = "editfullname" class="form-control" value="<?php echo mb_strtoupper($TenantRES['Tenant_Name']); ?>" required>
															</div>

															<div class="form-group">
																<label>Birthdate</label>
																<input class="form-control" id ="txtDate" type="date" name="editbirthday" min="1900-05-11" value="<?php echo $TenantRES['Birthdate']; ?>">
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
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
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
