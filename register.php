<?php include_once 'header.php'; ?>

<div class="register" id="main">
    <div class="container">
        <form id="myform" method="post" enctype='multipart/form-data' v-on:submit.prevent="sign_up">
            <div class="register_wrapper mt-5 mb-5">
                <!-- <figure class="forgot_logo">
                    <img src="assets/logo.svg" alt="logo">
                </figure> -->
                <div class="register_body mt-3 p-3">
                    <h3>Create your account</h3>
                    <div class="grid image_section">
                        <div class="">
                            <div class="register_item1">
                                <input type="text" name="f_name" id="f_name" v-model="f_name" placeholder="Enter your First Name">
                                <div class="error" id="fname_err"></div>
                            </div>
                            <div class="register_item2">
                                <input type="text" name="l_name" id="l_name" v-model="l_name" placeholder="Enter your last name">
                                <div class="error" id="lname_err"></div>
                            </div>
                            <div class="register_item3">
                                <input type="text" name="user_name" id="user_name" v-model="user_name" placeholder="Select Username">
                                <div class="error" id="user_name_err"></div>
                            </div>
                        </div>
                        <div>
                            <div class="register_item4">
                                <figure>
                                    <img src="images/avatar.png" alt="profile image" id="profile_preview">
                                    <div>
                                        <label for="profile_pic" class="btn-link">Upload Picture</label>
                                        <input class="custom-file-input" type="file" id="profile_pic" accept="image/*" hidden>
                                        <div class="error" id="profile_err"></div>
                                    </div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="register_item5">
                            <input type="text" v-model="email" id="email" placeholder="Enter your email">
                        </div>
                        <div class="mail_sufix">@mailman.com</div>
                        <div class="error" id="email_err"></div>
                    </div>
                    <div class="register_item6">
                        <input type="text" v-model="recovery_email" id="recovery_email" placeholder="Enter your recovery email">
                        <div class="error" id="recovery_email_err"></div>
                    </div>
                    <div class="register_pass">
                        <div class="register_item7">
                            <input type="password" v-model="password" class="password" id="password" placeholder="Enter new password here">
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="right" title="Password Must Contain At Least 6 characters 1 uppercase,1 lowercase, 1 number and 1 special character "></i>
                            <div class="error" id="password_err"></div>

                        </div>
                        <div class="register_item8">
                            <input type="password" v-model="cpassword" class="cpassword" id="cpassword" placeholder="Confirm password">
                            <div class="error" id="cpassword_err"></div>
                        </div>
                    </div>
                    <div class="register_item9">
                        <input id="terms" type="checkbox"> I Agree to the <a href="">terms and conditions</a> of MailMan
                    </div>
                    <div class="register_item9">
                        <div class="updating_btn">
                            <button type="submit" class="btn btn-outline-dark sign_up register_shadow m-2" id="sign_up" disabled>Submit</button>
                        </div>
                        <button class="btn btn-outline-dark sign_in register_shadow" id="sign_in">Sign-in Instead</button>
                    </div>
                    <div id="signup_success" class="d-none">
                        <h4>Sign up successuflly.Please <a href="index.php">Login</a> here.</h4>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="js/vue.js"></script>

<script>
    var app = new Vue({
        el: '#main',
        data: function() {
            // get data from input form
            this.f_name = "",
                this.l_name = "",
                this.user_name = "",
                this.email = "",
                this.recovery_email = "",
                this.password = "",
                this.cpassword = ""
        },
        methods: {

            sign_up: function(event) {
                var profile_picture = document.getElementById('profile_pic')
                var formData = new FormData();
                formData.append('f_name', this.f_name);
                formData.append('l_name', this.l_name);
                formData.append('user_name', this.user_name);
                formData.append('email', this.email);
                formData.append('recovery_email', this.recovery_email);
                formData.append('password', this.password);
                formData.append('cpassword', this.cpassword);
                formData.append('profile_pic', profile_picture.files[0]);

                $.ajax({
                    url: "backend/signup.php",
                    data: (formData),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    type: 'POST',
                    beforeSend: function() {
                        var html = '<button class="btn btn-primary" type="button" disabled>' +
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...' +
                            '</button>';
                        $('.updating_btn').html(html);

                    },
                    success: function(response) {

                        // register successfully
                        if (response.signup == true) {
                            $("#signup_success").removeClass('d-none');

                            // setTimeout(function () {
                            //     window.location.replace('index.php');
                            // }, 2000);


                        } else {
                            if (response.fname.fname_error) {
                                $('#fname_err').html(response.fname.fname_error)
                            } else {
                                $('#fname_err').html('')
                            }

                            // last name
                            if (response.lname.lname_error) {
                                $('#lname_err').html(response.lname.lname_error)
                            } else {
                                $('#lname_err').html('')
                            }

                            // user name 
                            if (response.username.username_error) {
                                $('#user_name_err').html(response.username.username_error)
                            } else {
                                $('#user_name_err').html('')
                            }

                            // email id
                            if (response.email.email_error) {
                                $('#email_err').html(response.email.email_error)
                            } else {
                                $('#email_err').html('')
                            }

                            // recover email
                            if (response.recoveryemail.recoveryemail_error) {
                                $('#recovery_email_err').html(response.recoveryemail.recoveryemail_error)
                            } else {
                                $('#recovery_email_err').html('')
                            }

                            // password
                            if (response.password.message) {
                                $('#password_err').html(response.password.message)
                            } else {
                                $('#password_err').html('')
                            }
                            if (response.profile_img.response) {

                            } else {
                                $('#profile_err').html(response.profile_img.message)
                            }
                        }

                    },
                    complete: function(){
                        $('.updating_btn').html('<button type="submit" class="btn btn-outline-dark sign_up register_shadow m-2" id="sign_up" disabled>Submit</button>');
                    }
                });
            }
        },
        mounted() {

        },
    })

    // set image preview
    profile_pic.onchange = evt => {
        const [file] = profile_pic.files
        if (file) {
            profile_preview.src = URL.createObjectURL(file)
        }
    }


    // // register button enable disable
    $('#terms').attr('disabled', 'disabled');

    $('input').keyup(function() {
        var f_name = $('#f_name').val();
        var user_name = $('#user_name').val();
        var email = $('#email').val();
        var recovery_email = $('#recovery_email').val();
        var password = $('#password').val();
        var cpassword = $('#cpassword').val();
        if (f_name == '' || user_name == '' || email == '' || recovery_email == '' || password == '' || cpassword == '') {
            $('#terms').prop('checked', false);
            $('#terms').attr('disabled', 'disabled');
            $('#sign_up').attr('disabled', 'disabled');
            $('#terms').prop('title', 'Please fill above details.');
        } else {
            $('#terms').removeAttr('disabled');
            $('#terms').prop('title', '');
        }
    });
    // check terms and condition checked or not
    $('#terms').click(function() {
        if ($(this).prop('checked') == false) {
            $('#sign_up').attr('disabled', 'disabled');
        } else {
            $('#sign_up').removeAttr('disabled');
        }
    });
    $('#sign_in').click(function(e) {
        e.preventDefault();

        window.location.replace('index.php');
    });
</script>
<?php include_once 'footer.php'; ?>