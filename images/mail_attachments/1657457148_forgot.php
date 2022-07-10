<?php include_once 'header.php'; ?>

<div class="container">
    <form class="grid" style="grid-template-columns: auto auto;" method="post" action="backend/forget.php">
        <div class="d-grid align-items-center">
            <!-- <figure class="forgot_logo">
                <img src="assets/logo.svg" alt="logo">
            </figure> -->
            <div class="forgot_section">
                <h2>Enter your registered E-mail</h2>
                <div class="forgot_content mt-2">
                    <input type="text" name="forgot_user_name" id="forgot_user_name" placeholder="abc@xyz.com" required><br>
                    <div class="d-flex justify-content-between mt-2">
                        <div>
                            Back to <a href="index.php">Login</a>
                        </div>
                        <div>
                            <button class="btn btn-outline-dark sign_in forgot_shadow" id="sign_in">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <figure>
                <img class="w-100" src="images/mail.png" alt="mail image">
            </figure>
        </div>
</form>
</div>

<?php include_once 'footer.php'; ?>