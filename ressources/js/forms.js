function checkPassword(form) {
	var result = passwordValidation(form);
	if (result == 0) {
		return true;
	} else if (result == 1)  {
			alert("The password does not match the requirements!");
			form.password.value = "";
			form.confirm_password.value = "";
			return false;
	} else if (result == 2)  {
		alert("The both passwords are not identical!");
		form.password.value = "";
		form.confirm_password.value = "";
		return false;
	}
}

function passwordCkeck(password, confirm_password) {
	if (password != "" 
		&& password == confirm_password) {
		// at least one number, one lowercase and one uppercase letter
		// at least eight characters
		var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
		var validPassword = re.test(password);
		if (!validPassword) {
			return 1;
		}
		return 0;
	} else {
		return 2;
	}
}

function passwordValidation(form) {
	return passwordCkeck(form.password.value, form.confirm_password.value);
}

function checkPasswordStrength(form) {
	var password = form.password.value;
	var validPassword = document.getElementById('validpw');

	if (passwordValidation(form) == 1) {
		validPassword.innerHTML = "Insecure password";
		validPassword.setAttribute("class", "error");
	} else if (passwordValidation(form) == 2) {
		validPassword.innerHTML = "The passwords does not match!";
		validPassword.setAttribute("class", "error");
	} else {
		validPassword.innerHTML = "Valid password!";
		validPassword.setAttribute("class", "success");
	}
}

function checkEmail (form) {
	var validEmail = document.getElementById('validEmail');

	if (checkEmailValid(form)) {
		validEmail.innerHTML = "Valid email!"
		validEmail.setAttribute("class", "success");
	} else {
		validEmail.innerHTML = "Incorrect email!";
		validEmail.setAttribute("class", "error");
	}	
}

function checkEmailValid (form) {
	var email = form.email.value;

	if (email.indexOf("@") != -1 && email.indexOf(".") != -1) {
		return true;
	} else {
		return false;
	}	
}

function checkUsername(form) {
	var username = form.username.value;
	var valid = checkValidUsername(username);
	if (valid) {
		document.getElementById('validName').innerHTML = "";
	} else {
		form.username.value = "";
		document.getElementById('validName').innerHTML = 
		'<div style="color: #f66">Only A-Z, a-z, 0-9<br>and -_ are allowed</div>';	
	}
}

function checkValidUsername(username) {
	var regex = /([\w|-]+)/gi;
	var valid = regex.exec(username);
	if (username.length == 0) {
		return false;
	}
	if (valid[0].length == username.length) {
		return true;
	} else {
		return false;
	}
}

function registerformhash(form) {
	if ((passwordValidation(form) == 0) && checkEmailValid(form) && checkValidUsername(form.username.value)) {
		// Create a new element input, this will be out hashed passw ord field.
		var p = document.createElement("input");
		// Add the new element to our form.
		form.appendChild(p);
		p.name = "p";
		p.type = "hidden"
		p.value = hex_sha512(form.password.value);
		// Make sure the plaintext password doesn't get sent.
		form.password.value = "";
		form.confirm_password.value = "";
		// Finally submit the form.
		form.submit();
	}
}

function formhash(form) {
   // Create a new element input, this will be out hashed password field.
   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(form.password.value);
   // Make sure the plaintext password doesn't get sent.
   form.password.value = "";
   // Finally submit the form.
   form.submit();
}