/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function changepassword(){
    currentpassword = document.getElementById("currentpassword").value;
    newpassword = document.getElementById("newpassword").value;
    confirmpassword = document.getElementById("confirmpassword").value;
    
    if(currentpassword == ''){
        alert('Password enter current password');
        return false;
    }
    if(newpassword !== confirmpassword){
        alert('Confirm password mismatch');
        return false;
    }
    if(newpassword.length < 6){
        alert('Password must be atleast 6 character');
        return false;
    }
}