<?php
include("config/dbconnect.php");
include("login_check.php");

	$oid = preg_replace('#[^0-9]#i','',$_GET['edt']);
	$oq = mysqli_query($con,"SELECT id,offer_type,title,description,start_date,end_date,discount,image FROM offer WHERE id='$oid'");
	$objs = mysqli_fetch_object($oq);
		$id = $objs->id;
		$offer_type = $objs->offer_type;
		$title = $objs->title;
		$description = $objs->description;
		$discount = $objs->discount;
		$start_date = $objs->start_date;
		$end_date = $objs->end_date;
		$image = $objs->image;
		
//Offer Edit Page
if(isset($_POST['submit'])){
$uploadOk = 0;
	$ip = $_SERVER["REMOTE_ADDR"];
	$type = trim($_POST["type"]);
	$title = trim($_POST["title"]);
	$description = trim(str_replace("'","~",$_POST["description"]));
	$discount = trim($_POST["discount"]);
	$start_date = trim($_POST["start_date"]);
	$end_date = trim($_POST["end_date"]);
	$fileName = rand()."_".$_FILES["fileToUpload"]["name"];
	$tmpName = $_FILES["fileToUpload"]["tmp_name"];
	$size = $_FILES["fileToUpload"]["size"];

	$target_dir = "images/offer/";
	$target_file = $target_dir . basename($fileName);

	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Check if file already exists
	if (file_exists($target_file)) {
		$uploadOk = 1;
	}
	// Check file size
	if ($size > 5000000000) {
		$uploadOk = 2;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		$uploadOk = 3;
	}
	
	// Check if $uploadOk is set to 0 by an error
	switch ($uploadOk) {		
    case 1:
        $msg = "Sorry, Image already exists. Plz Change Image Name.";
        break;
		
    case 2:
        $msg = "Sorry, your image is too large.";
        break;
		
    case 3:
       $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        break;
		
    default:
	// if everything is ok, try to upload file
        move_uploaded_file($tmpName, $target_file);
		}	
		$submit = mysqli_query($con,"UPDATE `offer` SET `offer_type`='$type', `title`='$title', `description`='$description', `discount`='$discount', `start_date`='$start_date', `end_date`='$end_date', `image`='$fileName', `ip`='$ip', `userid`='$loggedId', `datetime`=CURRENT_TIMESTAMP, `status`=1 WHERE id='$id'");
		
		mysqli_close($con);
		
		if($submit){
		$msg = "Successfully Save-:)";
		}else{
		$msg = "Save Error!!.";
		}
	}
?>
<!DOCTYPE html>
<head>
<title>Edit Offer Details || Tea Resort & Museum</title>
	<?php require("components/head.php");?>
	<style type="text/css">
		.logo{margin-top:0px !important; margin-left:0 !important; width:100%;}
	</style>
</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="home.php" class="logo">
        <img src="images/tea-resort-logo.jpg" class="img-responsive" alt="Tea Resort & Museum Logo">
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->
<div class="top-nav clearfix">
    <!--search & user info start-->
     <?php require("components/header.php");?>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <?php require("components/menu.php");?>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->

<!--main content start-->
<section id="main-content">
	<section class="wrapper">		
		<div class="row">		
			<div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Edit Offer Details
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
							<?php							
							if($msg){?>
							 <div class="alert alert-success" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="close">
										<span aria-hidden="true">&times;</span>
									</button>
									<span><strong><?php echo $msg; ?></strong></span>
							  </div>
							<?php } ?>
                               <form method="post" enctype="multipart/form-data">				   
							   
							   <div class="form-group">
                                    <label for="type">Offer Type</label>
                                    <input type="text" value="<?php echo $offer_type;?>" name="type" required="required" class="form-control" placeholder="Enter Offer Type">
                                </div>
								
                                <div class="form-group">
                                    <label for="Title">Offer Title</label>
                                    <input type="text" value="<?php echo $title;?>" name="title" required="required" class="form-control" placeholder="Enter title">
                                </div>
                                
								<div class="form-group">
									<label for="shortdescription">Short Description</label>
									<textarea name="description" class="form-control"><?php echo $description;?></textarea>
								</div>

								<div class="col-md-6 form-group">
									<label for="startdate">Start Date</label>
									<input type="date" value="<?php echo $start_date;?>" name="start_date" placeholder=".col-md-4" class="form-control">
								</div>

								<div class="col-md-6 form-group">
									<label for="enddate">End Date</label>
									<input type="date" value="<?php echo $end_date;?>" name="end_date" placeholder=".col-md-4" class="form-control">
								</div>
								
								<div class="col-md-6 form-group">
									<label for="sqm">Discount%</label>
									<input type="number" value="<?php echo $discount;?>" name="discount" placeholder=".col-md-4" class="form-control">
								</div>
								
                                <div class="form-group">
                                    <label for="AttachFile">Attach Offer Image</label>
                                   	<input type="file" name="fileToUpload" required="required" class="form-control" id="formGroupExampleInput">
                                </div>
								
                               <div class="alert alert-warning" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="close">
										<span aria-hidden="true">&times;</span>
									</button>
									<span>Image Max Size<strong>(778 X 558)</strong> AND Type <strong>(jpg,jpeg,png,gif)</strong>.</span>
							  </div>
                                 <div class="form-group">
									<input type="submit" name="submit" class="btn btn-outline-info" value="Edit" />
									<input type="reset" class="btn btn-outline-success" value="Reset" />
								  </div>
                            </form>
                            </div>
                        </div>
						<button type="button" class="btn btn-outline-primary btn-sm btn-right" onclick="goBack();">Go Back</button>
                    </section>

            </div>
		</div>
</section>
 <!-- footer -->
		  <?php require("components/footer.php");?>
  <!-- / footer -->
</section>
<!--main content end-->
</section>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/scripts.js"></script>
<script src="js/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery.scrollTo.js"></script>
<!-- morris JavaScript -->	
<script>
	$(document).ready(function() {
		//BOX BUTTON SHOW AND CLOSE
	   $('.small-graph-box').hover(function() {
		  $(this).find('.box-button').fadeIn('fast');
	   }, function() {
		  $(this).find('.box-button').fadeOut('fast');
	   });
	   $('.small-graph-box .box-close').click(function() {
		  $(this).closest('.small-graph-box').fadeOut(200);
		  return false;
	   });
	   
	});
	</script>
</body>
</html>