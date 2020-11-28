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
		<div style="text-align:center;" class="container form-inline">
			<Strong><span>Search <p class="fas fa-search"></p> &nbsp;</span></strong>
				 <input type="text" name="tb1" onchange="submit()" class="form-control col-lg-8" placeholder="Enter Hint from Announcement">
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

          <th>Announcement</th>
          <th>Date Made</th>

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
					<td><?php echo $val['Announcement']; ?></td>
          <td><?php echo $val['Date_Of_Announcement']; ?></td>
				</tr>
				<?php
					}
				}else{?>
				<tr>
					<td colspan="8" align="center"><strong>No Announcement Made Yet!</strong>
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>


</body>
</html>
