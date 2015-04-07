/*jslint browser: true, devel: true */
var attempt = 3;
function validate() {
	"use strict";
	var username = document.getElementById("username").value,
		password;
	if (username === "admin") {
		password = document.getElementById("password").value;
	}
	if (username === "admin" && password === "password") {
		alert("Login successfully");
		window.location = "frames.html";
		return false;
	} else {
		attempt = attempt - 1;
		alert("You have left " + attempt + " attempt;");
		if (attempt === 0) {
			document.getElementById("username").disabled = true;
			document.getElementById("password").disabled = true;
			document.getElementById("submit").disabled = true;
			return false;
		}
	}
}