var password = document.getElementById('password');
var confirm_password = document.getElementById('confirm_password');
var message = document.getElementById('message');

var check = function () {
    if ( confirm_password.value == password.value ) {
        message.style.color = '#28a745';
        message.style.display = 'inline';
        message.innerHTML = 'Matching';
    } else {
        message.style.display = 'inline';
        message.style.color = '#dc3545';
        message.innerHTML = 'Not Matching';
    }
}

function verifyPassword() {
    var pw = password.value;
    var format = /(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/;
    var checkFormat = ()=>{
        return pw.match(format)? true : false;
    }
    //check empty password field
    if(pw == "" || pw.length < 8 || pw.length > 20 || checkFormat() == false) {
       document.getElementById("messagepw").innerHTML = "**Please match the requested format!";
       return false;
    }
   
  }