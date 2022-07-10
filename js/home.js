function show_pass(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

$(document).ready(function () {
    // $('#submit_signup').click(function (e) { 
    //     e.preventDefault();
    //     var f_name = $('#f_name').val();
    //     var l_name = $('#l_name').val();
    //     var dob = $('#dob').val();
    //     var user_name = $('#user_name').val();
    //     var email = $('#email').val();
    //     var mobile = $('#mobile').val();
    //     var password = $('#password').val();
    //     $.ajax({
    //         type: "post",
    //         url: "backend/signup.php",
    //         data: {'f_name':f_name,'l_name':l_name,'dob':dob,'user_name':user_name,'email':email,'mobile':mobile,'password':password},
    //         dataType: "json",
    //         success: function (response) {
    //             console.log(response);
    //         }
    //     });
    // });
});

