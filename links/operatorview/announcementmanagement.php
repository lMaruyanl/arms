<?php include_once('../config.php'); include('../paginator.class.php'); include('addannouncement.php'); ?>
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
		<Strong style="color:#980303 "><span>ANNOUNCEMENT <p class="fas fa-exclamation-triangle"></p> &nbsp;</span></strong>
</br></br>
	</div>
  <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class="form-inline">
			<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
				 <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-7" placeholder="Enter Hint from Announcement">

				 <div class="col-lg-1">

				 </div>

				<a href="#addRoom" class="btn btn-primary" data-toggle="modal"><span>Add Announcement</span></a>

		</div>
	</form>
		<hr>
		<?php
		if(isset($_REQUEST['tb1'])) {
			$condition		=	"";
			if(isset($_GET['tb1']) and $_GET['tb1']!="")
			{
				$condition		.=	" AND Announcement LIKE'%".$_GET['tb1']."%'";
			}

			//Main query
			$pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM announcement Where 1 ".$condition."");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM announcement Where 1 ".$condition." ORDER BY Date_Of_Announcement DESC ".$pages->limit."");
		}else {
      $pages = new Paginator;
			$pages->default_ipp = 10;
			$sql_forms = $mysqli->query("SELECT * FROM announcement ORDER BY A_ID DESC");
			$pages->items_total = $sql_forms->num_rows;
			$pages->mid_range = 9;
			$pages->paginate();

			$result	=	$mysqli->query("SELECT * FROM announcement ORDER BY A_ID DESC ".$pages->limit."");
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
          <th>Announcement</th>
          <th>Date Made</th>
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
          <?php $n++ ?>
					<td><?php echo $val['A_ID']; ?></td>
					<td><?php echo $val['Announcement']; ?></td>
          <td><?php echo $val['Date_Of_Announcement']; ?></td>

          <td style="text-align:center;">
            <form method="post">

                <a href="#deleteRoom" name = "s1s" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete" onclick="showTableData(<?php echo $n ?>)">&#xE872;</i></a>
            </form>
          </td>
				</tr>
				<?php
					}
				}else{?>
				<tr>
					<td colspan="8" align="center"><strong>No Announcement Made Yet!</strong>
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
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="modal-header">
              <h4 class="modal-title">Add Announcement</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Message</label>
                <textarea name = "MessageA" class="form-control"></textarea>
              </div>

            </div>
            <div class="modal-footer">
            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
              <input type="submit" onsubmit="setTimeout(function () { window.location.reload(); }, 10)" class="btn btn-danger" data-dismiss="static" name ="addR" value="Broadcast">
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>




    <div id="deleteRoom" class="modal fade">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="post">
                  <div class="modal-header">
                    <h4 class="modal-title">Delete Room Record</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to delete <span id="info"></span> from Records?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                  </div>
                  <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="hidden" id="btnClickedValue" name="btnClickedValue" value="" />
                    <input type="submit" name ="mainDe"class="btn btn-danger" value="Delete">
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
            <?php
           $haha = $_POST['btnClickedValue'];;
        ?>
            // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.

        }
    </script>

</body>
</html>
