$(document).ready(function() {
    $.ajax({
        type: "post",
        url: "backend/profile.php",
        data: "",
        dataType: "json",
        success: function(response) {

            console.log(response);
            $('#user_name').val(response.first_name + ' ' + response.last_name);
            $('#email').val(response.email);
            $('#backup_email').val(response.backup_mail);
            $('#username').val(response.user_name);
            if (!response.profile_pic) {
                $('#profile_imgage').attr('src', 'images/profile_pic/' + response.profile_pic);
            } else {
                $('#profile_imgage').attr('src', 'assets/avatar.png');
            }

        }
    });
});