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

function passwordValidation(form) {
	if (form.password.value != "" 
		&& form.password.value == form.confirm_password.value) {
		// at least one number, one lowercase and one uppercase letter
		// at least six characters
		var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
		var validPassword = re.test(form.password.value);
		if (!validPassword) {
			return 1;
		}
		return 0;
	} else {
		return 2;
	}
}

function checkPasswordStrength(form) {
	var password = form.password.value;
	var validPassword = document.getElementById('validpw');

	if (passwordValidation(form) == 1) {
		validPassword.innerHTML = "Insecure password";
		validPassword.style.color = "#f66";
	} else if (passwordValidation(form) == 2) {
		validPassword.innerHTML = "The passwords does not match!";
		validPassword.style.color = "#f66";
	} else {
		validPassword.innerHTML = "Valid password!"
		validPassword.style.color = "#6f6";
	}
}

function checkEmail (form) {
	var email = form.email.value;
	var validEmail = document.getElementById('validEmail');

	if (email.indexOf("@") != -1 && email.indexOf(".") != -1) {
		validEmail.innerHTML = "Valid email!"
		validEmail.style.color = "#6f6";
	} else {
		validEmail.innerHTML = "Incorrect email!";
		validEmail.style.color = "#f66";
	}	
}

function checkEmailValid (form) {
	var email = form.email.value;
	var validEmail = document.getElementById('validEmail');

	if (email.indexOf("@") != -1 && email.indexOf(".") != -1) {
		return true;
	} else {
		return false;
	}	
}

function registerformhash(form) {
	if (checkPassword(form) && checkEmailValid(form)) {
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