function validateForm() {
    document.getElementById("error_password_first").innerHTML = "";
    document.getElementById("error_password_second").innerHTML = "";

    var password_first = document.forms["user"]["user_password_first"].value;
    var password_second = document.forms["user"]["user_password_second"].value;

    if (password_first == "") {
        document.getElementById("error_password_first").innerHTML = "Please enter a password";
        return false;
    }
    if (password_second == "") {
        document.getElementById("error_password_second").innerHTML = "Please enter a password";
        return false;
    }
}