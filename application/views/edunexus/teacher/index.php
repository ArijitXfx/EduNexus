<head>
	<title>Teacher home page</title>
</head>
<body>
	<h1>Welcome <?php echo $name; ?></h1>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/teachermanager/showUploadPage'">Upload Material</button>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/teachermanager/showMyCourses'">My Courses</button> 
	<!-- <button>Discussion Forum</button> -->
	
</body>
