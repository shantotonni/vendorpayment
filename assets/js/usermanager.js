function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


function loaddata(userid){
    $.ajax({
        url: base_url + "usermanager/userdata",
        type: "post",
        data: "userid=" + userid,
        success: function (response) {
            data = JSON.parse(response);
            objSubmit = document.getElementById("submit");
            objErrormsg = document.getElementById("errormsg");
            if(data.length){
                objSubmit.disabled = true;
                objErrormsg.innerHTML = "User id already exists.";
            }else{
                objSubmit.disabled = false;
                objErrormsg.innerHTML = "";
            }
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
}

function validateform(){
    password = document.getElementById("password").value;
    confirmpassword = document.getElementById("confirmpassword").value;
    if(password !== confirmpassword){
        alert('Password mismatch');
        return false;
    }
}