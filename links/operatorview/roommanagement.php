<?php include_once('../config.php'); include('../paginator.class.php'); include('addroom.php'); ?>
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
		<Strong><span>Room Management <p class="fas fa-restroom"></p> &nbsp;</span></strong>

	</div>
</br>
</br>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class="form-inline">
			<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
				 <input type="number" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Room Number">

				 <div class="col-lg-2">

				 </div>

				<a href="#addRoom" class="btn btn-primary" data-toggle="modal"><span>Add New Room</span></a>

		</div>
	</form>
		<hr>
		<?php
		if(isset($_REQUEST['tb2'])) {
			$condition		=	"";
			if(isset($_GET['tb2']) and $_GET['tb2']!="")
			{
				$condition		.=	" AND roomnumber LIKE'%".$_GET['tb2']."%'";

			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM room WHERE 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM room WHERE 1 ".$condition." ORDER BY roomnumber ASC ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM room");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM room ORDER BY roomnumber ASC ".$pages->limit."");
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
					<th>Location</th>
					<th>Room Number</th>
          			<th>Number Of Occupants</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($pages->items_total>0){
					$n  =   1;
					while($val  =   $result->fetch_assoc()){
						$idEditForRoom = $val['roomnumber'];
						$idEditForlocation = $val['location'];
				?>
				<tr>
					<?php $n++; ?>
					<td><?php echo $val['location']; ?></td>
					<td><?php echo $val['roomnumber']; ?></td>
          <td><?php echo $val['number']; ?></td>
          <td style="text-align:center;">
						<form method="post">

								<a href="#deleteRoom<?php echo $idEditForRoom;?>" name = "s1s" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit" onclick="showTableData(<?php echo $n ?>)">&#xE22B;</i></a>
						</form>
			  </td>
								<div id="deleteRoom<?php echo $idEditForRoom;?>" class="modal fade">
												<div class="modal-dialog">
													<div class="modal-content">
														<form method="post">
															<div class="modal-header">
																<h4 class="modal-title">Edit Room Record</h4>
																<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															</div>
															<div class="modal-body">
																<p>Add or remove number of occupants of room <?php echo $idEditForRoom;?> from Records? <?php echo $idEditForlocation;?> </p>
																	<input name="ForIDEdit" type = "hidden" class="form-control" value="<?php echo $idEditForRoom; ?>">
																	<input name="ForIDEditlocaiton" type = "hidden" class="form-control" value="<?php echo $idEditForlocation; ?>">
																<div class="form-group <?php echo (!empty($numberofoccupants_err1ForEdit)) ? 'has-error' : ''; ?>">
																	<input type="number"  min = "1" class="form-control" name="number_of_occupants" value="<?php echo $numberofoccupants_err1ForEdit; ?>" required>
																	<span style = "color:red;"class="help-block"><?php echo $numberofbeds_err1ForEdit; ?></span>
																</div>
																<p class="text-warning"><small>you cant add Zero to the occupants</small></p>
															</div>
															<div class="modal-footer">
																<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
																<input type="hidden" id="btnClickedValue" name="btnClickedValue" value="" />
																<input type="submit" name ="EditRoomRemove"class="btn btn-danger" value="Remove">
																<input type="submit" name ="EditRoomAdd"class="btn btn-success" value="Add">
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
					<td colspan="6" align="center"><strong>No Record(s) Found!</strong></td>
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
		<div id="addRoom" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
						<div class="modal-header">
							<h4 class="modal-title">Add Room</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body">
							<<div class="form-group">
																<label>Location</label>
																<?php $initial = "0"; ?>
																	<select id="select1" name="location" class="form-control">
																		<option>Please select A location</option>
																		<option value="Mandaluyong City">Mandaluyong City</option>
																		<option value="Makati City">Makati City</option>
																		<option value="Pasig City">Pasig City</option>
																	</select>
																</div>
							<div class="form-group <?php echo (!empty($numberofbeds_err)) ? 'has-error' : ''; ?>">
								<label>Room Number</label>
								<input type="number" class="form-control" name="roomnumbers" value="<?php echo $roomnumber; ?>" required>
								<span style = "color:red;"class="help-block"><?php echo $roomnumber_err; ?></span>
							</div>

						</div>
						<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">

							<input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-success" data-dismiss="static" name ="addR" value="Add">
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
									info.innerHTML = info.innerHTML + ' ' + objCells.item(0).innerHTML;
									document.getElementById("btnClickedValue").value = info.innerHTML;
									<?php
		             $haha = $_POST['btnClickedValue'];;
		          ?>
					        // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.

					    }
					</script>
					<script>function myFunction() {
   var x = document.createElement('DIALOG');
   var t = document.createTextNode('This is an open dialog window');
   x.setAttribute('open', 'open');
   x.appendChild(t);
   document.body.appendChild(x);
 }
 				</script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>
