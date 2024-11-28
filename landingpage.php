<html>
<head>
	<title>page</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<div class="logo">
		<a href=""><img class="img" src="assets/uploads/05112024115404gusa.jpg"></a>
	</div>
<div class="nav">
 <table>
	<tr>
		<td><a href="#">Home</a></td>
		<td><a href="#">About</a></td>
		<td><a href="#">Contact</a></td>
        <td><a href="login.php">Sign in</a></td>
	</tr>

 </table>
 </div>
<div class="left">
		<h1>LOVE</h1><p>is all you need and lots of <h1>BROWNIES</h1></p>
</div>
<div class="right">
	<img src="brownie.jpg"/>
</div>
<a href="resident-register.php" class="btn">
	<button>Register</button>
</div> 
</body>
<style>
    *{
	margin: 0;
	padding: 0;
	
}

body{
	background-color: white;
}

.img{
    height: 70px;
    width: 70px;
    border-radius: 50%;
}

.logo{
	float: left;
	margin-top: 15px;
	margin-left: 100px;
	color: #8B4513;
	font-size: 25px;
	font-family: sans-serif;
}

.nav{
	float: right;
	margin-right: 10px;
}

.nav tr td{
	float: left;
	margin-right: 100px;
	margin-top: 30px;
	font-size: 20px;
	color: black;
	font-family: sans-serif;
}

.nav tr td a{
	text-decoration: none;
	color: black;
}

.left{
	margin-top: 250px;
	margin-left: 100px;
	float: left;
	position: absolute;
	font-size: 40px;
	color: white;
	font-family: sans-serif;
}
.left h1{
	color: #DAA520;
}

.left p{
    color: black;
}

.right img{
	height: 500px;
	width: 680px;
	float: right;
	display: flex;
	margin-top: 50px;
	margin-right: 50px;
	border-radius: 20px;
	box-shadow: 0px 5px 10px 5px rgba(0, 0, 0, 0.1);
}

.btn{
	position: absolute;
	margin-top: 500px;
	margin-left: -65px;
	
}
.btn button{
	font-family: sans-serif;
	height: 50px;
	width: 200px;
	border-radius: 30px;
	border: none;
	background-color: #32CD32;
	color: white;
	cursor: pointer;
}
</style>
</html>