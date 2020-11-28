<?php session_start(); include_once('../config.php'); include('../paginator.class.php'); include('addreservation.php');?>
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
    <Strong><span>Reservation Status <p class="fas fa-stamp"></p> &nbsp;</span></strong>

  </div>
  </br></br>
  <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <div class="form-inline">
      <Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
         <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Name">

         <div class="col-lg-1">

         </div>

        <a href="#addRoom" class="btn btn-primary" data-toggle="modal"><span>Apply A Transient</span></a>

    </div>
  </form>
		<hr>
		<?php
		if(isset($_REQUEST['tb1'])) {
			$condition		=	"";
			if(isset($_GET['tb1']) and $_GET['tb1']!="")
			{
				$condition		.=	" AND tenant.Tenant_Name LIKE'%".$_GET['tb1']."%' And tenant.Tenant_Id = ".$_SESSION["dddddd"];
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM transient INNER JOIN tenant ON tenant.Tenant_Id=transient.Tenant_Id Where 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM transient INNER JOIN tenant ON tenant.Tenant_Id=transient.Tenant_Id Where 1 ".$condition." ORDER BY tenant.Tenant_Name ASC ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM transient INNER JOIN tenant ON tenant.Tenant_Id=transient.Tenant_Id where tenant.Tenant_Id =".$_SESSION["dddddd"]);
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM transient INNER JOIN tenant ON tenant.Tenant_Id=transient.Tenant_Id where transient.Tenant_Id=".$_SESSION["dddddd"]." ORDER BY tenant.Tenant_Name ASC ".$pages->limit."");
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
					<th>Duration</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if($pages->items_total>0){
					$n  =   1;
					while($val  =   $result->fetch_assoc()){
				?>
				<tr>
					<td><?php echo $val['Guest_Id']; ?></td>
					<td><?php echo mb_strtoupper($val['Guest_Name']); ?></td>
          <td><?php echo $val['Contact']; ?></td>
					<td><?php echo $val['Duration']; ?></td>
          <td style="text-align:center;">
          <?php
          if($val['Transient_Status'] == 0){
            echo "Not Approved <span class='fas fa-exclamation-circle'></span>";
          }else if($val['Transient_Status'] == 1){
            echo "Approved <span class='fas fa-check-circle'></span>";
          }else if($val['Transient_Status'] == 2){
            echo "Pending <span class='fas fa-exclamation-circle'></span>";
          }
          ?>
        </td>

				</tr>
				<?php
					}
				}else{?>
          <tr>
  					<td colspan="8" align="center"><strong>No Reservation Made Yet!</strong>
            <a href="#addRoom" class="btn btn-primary" style="background-color:transparent;border-left:none;border-right:none;border-top:none; border-bottom:none;" data-toggle="modal"><span class="fas fa-plus-circle" style="color:#2196F3"></span><span style="color:#2196F3;"> Create one</span></a>
          </td>
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
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <h4 class="modal-title">Add Transient</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label>Guest Full Name <span class="badge badge-info">Format("Letters Only")</span></label>
                <input name="guestname" class="form-control" pattern="[A-Za-z ]+" value="<?php echo $TenantRES['Guardian_Name']; ?>">
              </div>
              <div class="form-group">
                <label>Guest Phone Number <span class="badge badge-info">Format (xxx-xxx-xxxx)</span></label>
                  <input type="tel" name="guestcontact" pattern="^\d{3}-\d{3}-\d{4}$" class="form-control" value="<?php echo $TenantRES['Tenant_Contact']; ?>"required >
              </div>
              <div class="form-group">
                <label>Stay Duration <span class="badge badge-warning">in days</span></label>
                <input name = "guestduration" type = "number" min = "0" max = "366" class="form-control"  required>
              </div>
              <div class="form-group" align="center">
                <input type="hidden" name="size" value="1000000">
                  <label class="btn btn-default"  style="background-color:transparent;border-left:none;border-right:none;border-top:none; border-bottom:none;">
                      <span class="fas fa-upload" style="color:#2196F3"></span><span style="color:#2196F3;">Upload Image</span><input type="file" name="image" hidden required>
                  </label>
              </div>
            </div>
            <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
              <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="addR" value="Apply">
            </div>
          </form>
        </div>
      </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>
