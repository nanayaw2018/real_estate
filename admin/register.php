<?php
// Creating the register form 
require_once("../core/init.php");
include("includes/header.php");


// Arrays of the months from January to December
$months = array("January","February","March","April","May","June","July","August","September","October","November","December");

// Arrays of years, from 1900 to 2018
$years = array('1900'=>"1900", '1901'=>"1901", '1902'=>"1902", '1903'=>"1903", '1904'=>"1904", '1905'=>"1905", '1906'=>"1906", '1907'=>"1907", '1908'=>"1908", '1909'=>"1909", '1910'=>"1910", '1911'=>"1911", '1912'=>"1912", '1913'=>"1913", '1914'=>"1914", '1915'=>"1915", '1916'=>"1916", '1917'=>"1917", '1918'=>"1918", '1919'=>"1919", '1920'=>"1920", '1921'=>"1921", '1922'=>"1922", '1923'=>"1923", '1924'=>"1924", '1925'=>"1925", '1926'=>"1926", '1927'=>"1927", '1928'=>"1928", '1929'=>"1929", '1930'=>"1930", '1931'=>"1931", '1932'=>"1932", '1933'=>"1933", '1934'=>"1934", '1935'=>"1935", '1936'=>"1936", '1937'=>"1937", '1938'=>"1938", '1939'=>"1939", '1940'=>"1940", '1941'=>"1941", '1942'=>"1942", '1943'=>"1943", '1944'=>"1944", '1945'=>"1945", '1946'=>"1946", '1947'=>"1947", '1948'=>"1948", '1949'=>"1949", '1950'=>"1950", '1951'=>"1951", '1952'=>"1952", '1953'=>"1953", '1954'=>"1954", '1955'=>"1955", '1956'=>"1956", '1957'=>"1957", '1958'=>"1958", '1959'=>"1959", '1960'=>"1960", '1961'=>"1961", '1962'=>"1962", '1963'=>"1963", '1964'=>"1964", '1965'=>"1965", '1966'=>"1966", '1967'=>"1967", '1968'=>"1968", '1969'=>"1969", '1970'=>"1970", '1971'=>"1971", '1972'=>"1972", '1973'=>"1973", '1974'=>"1974", '1975'=>"1975", '1976'=>"1976", '1977'=>"1977", '1978'=>"1978", '1979'=>"1979", '1980'=>"1980", '1981'=>"1981", '1982'=>"1982", '1983'=>"1983", '1984'=>"1984", '1985'=>"1985", '1986'=>"1986", '1987'=>"1987", '1988'=>"1988", '1989'=>"1989", '1990'=>"1990", '1991'=>"1991", '1992'=>"1992", '1993'=>"1993", '1994'=>"1994", '1995'=>"1995", '1996'=>"1996", '1997'=>"1997", '1998'=>"1998", '1999'=>"1999", '2000'=>"2000", '2001'=>"2001", '2002'=>"2002", '2003'=>"2003", '2004'=>"2004", '2005'=>"2005", '2006'=>"2006", '2007'=>"2007", '2008'=>"2008", '2009'=>"2009", '2010'=>"2010", '2011'=>"2011", '2012'=>"2012", '2013'=>"2013", '2014'=>"2014", '2015'=>"2015", '2016'=>"2016", '2017'=>"2017", '2018'=>"2018");

// Assigning inputs from the form to vairable using ternary operators
$firstname = ((isset($_POST['firstname']) && !empty($_POST['firstname']))?$_POST['firstname']:'');
$lastname = ((isset($_POST['lastname']) && !empty($_POST['lastname']))?$_POST['lastname']:'');
$year_of_birth = ((isset($_POST['year_of_birth']) && !empty($_POST['year_of_birth']))?$_POST['year_of_birth']:'');
$month_of_birth = ((isset($_POST['month_of_birth']) && !empty($_POST['month_of_birth']))?$_POST['month_of_birth']:'');
$day_of_birth = ((isset($_POST['day_of_birth']) && !empty($_POST['day_of_birth']))?$_POST['day_of_birth']:'');
$email = ((isset($_POST['email']) && !empty($_POST['email']))?$_POST['email']:'');
$organization = ((isset($_POST['organization']) && !empty($_POST['organization']))?$_POST['organization']:'');
$password = ((isset($_POST['password']) && !empty($_POST['password']))?$_POST['password']:'');
$confirm = ((isset($_POST['confirm']) && !empty($_POST['confirm']))?$_POST['confirm']:'');
$phone = ((isset($_POST['phone']) && !empty($_POST['phone']))?$_POST['phone']:'');
$date_of_birth = $year_of_birth."-".$month_of_birth."-".$day_of_birth;

// Empty error array, to keep track of all errors that are raised during form submission
$errors = array();


// Check if the register button is been clicked.
if(isset($_POST['register'])){
	if(empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm']) || empty($_POST['organization']) || empty($_POST['day_of_birth']) || empty($_POST['month_of_birth']) || empty($_POST['year_of_birth']) || empty($_POST['phone'])){
		$errors[] = "All fields are required";
	}else{
		// Checking for a valid email address, the FILTER_VALIDATE_EMAIL is an inbuilt function for validating emails.
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$errors[] = "Please enter a valid email"; // If the email is not valid, add error information to the error array
		}

		if($confirm != $password){
			$errors[] = "Passwords do not match"; // if password do not match add error information to the error array
		}
	}
	//checking for errors
	if(!empty($errors)){
		$error = display_errors($errors);
	}else{

		//insert details into database if there exist no errors.
		$full_name = $firstname." ".$lastname; // Creating a full name by concatenating the first and last name.
		$hashed_password = password_hash($password, PASSWORD_DEFAULT); // Using password_hash function(inbuilt) to encrypt password before insertion into the database
		$_SESSION['success'] = "<ul class='bg-success'><li class='text-default text-center'>Account successfully created!</li></ul>";
		$db->query("INSERT INTO users(full_name, email, date_of_birth, organization, password, permissions, phone) VALUES('$full_name', '$email', '$date_of_birth', '$organization', '$hashed_password', 'guest', '$phone')");
		header("location: index.php");
	}

}

?>	
	<!-- Html Code for Registeration Form begins here -->
	<div class="container">
		<h3 class="text-center text-primary">Join Us Today!</h3>
			<div class="center-edit">
			<hr>
			<?=((!empty($error))?$error:'');?>
			<form action="register.php" method="post">
				<div class="form-group col-md-6">
					<label for="firstname">First Name *:</label>
					<input type="text" class="form-control" name="firstname" id="firstname" value="<?=((!empty($errors))?$firstname:'');?>" placeholder="Enter first name">
				</div>
				<div class="form-group col-md-6">
					<label for="lastname">Last Name *:</label>
					<input type="text" class="form-control" name="lastname" id="lastname" value="<?=((!empty($errors))?$lastname:'');?>" placeholder="Enter last name">
				</div>
				<div class="form-group col-md-12">
					<label for="date_of_birth">Date Of Birth *:</label>
				</div>
				<div class="form-group col-md-4">
					<select class="form-control" id="day_of_birth" name="day_of_birth">
						<option value="">--Select Day--</option>
						<?php for($i=1; $i<32; $i++): ?>
						<option value="<?=$i;?>" <?=((!empty($errors) && $day_of_birth == $i)?'selected':'');?> ><?=$i;?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="form-group col-md-4">
					<select class="form-control" id="month_of_birth" name="month_of_birth">
						<option value="">--Select Month--</option>
						<?php for($x=1; $x<13; $x++): ?>
						<option value="<?=$x?>"  <?=((!empty($errors) && $month_of_birth == $months[$x-1])?'selected':'');?> ><?=$months[$x-1]?></option>
						<?php endfor; ?>
					</select>
				</div>
				<div class="form-group col-md-4">
					<select class="form-control" id="year_of_birth" name="year_of_birth">
						<option value="">--Select Year--</option>
						<?php foreach ($years as $key => $value): ?>
						<option value="<?=$value;?>"<?=((!empty($errors) && $year_of_birth == $value)?'selected':'');?>><?=$key;?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label for="email">Email *:</label>
					<input type="text" class="form-control" name="email" id="email" value="<?=((!empty($errors))?$email:'');?>" placeholder="Enter email">
				</div>
				<div class="form-group col-md-12">
					<label for="organization">Organization *:</label>
					<input type="text" class="form-control" name="organization" id="organization" value="<?=((!empty($errors))?$organization:'');?>" placeholder="Enter organization">
				</div>
				<div class="form-group col-md-12">
					<label for="phone">Phone *:</label>
					<input type="text" class="form-control" name="phone" id="phone" value="<?=((!empty($errors))?$phone:'');?>">
				</div>
				<div class="form-group col-md-6">
					<label for="password">Password *:</label>
					<input type="password" class="form-control" name="password" id="password" value="<?=((!empty($errors))?$password:'');?>">
				</div>
				<div class="form-group col-md-6">
					<label for="confirm">Confirm Password *:</label>
					<input type="password" class="form-control" name="confirm" id="confirm" value="<?=((!empty($errors))?$confirm:'');?>">
				</div>
				<div class="form-group col-md-12">
					<input class="btn btn-success" type="submit" name="register" value="Register">
					<p>Already have an account? <a href="login.php">Sign in</a> </p>
				</div>
			</form>
			</div>
	</div>
	<!-- End of Registration Form -->
</div>
</body>
</html>