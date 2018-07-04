<head>
	<title>NGO home page</title>
</head>
<body>
	<h1>Welcome <?php echo $name; ?></h1>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/manageUsers'">Manage Users</button>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/manageCourses'">Manage Course</button>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/verifyMaterials'">Verify Material</button>
	<button onclick="location.href ='<?php echo site_url()?>/edunexus/ngomanager/verifyTeachers'">Verify Teacher</button>
</body>
