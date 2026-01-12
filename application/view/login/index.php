<script src="https://www.google.com/recaptcha/api.js?render=6LeE_UcsAAAAAGXOr2mKj7jX_YtS0Gf28umNVlgM"></script>

<div class="container">
    <?php $this->renderFeedbackMessages(); ?>

    <div class="login-page-box">
        <div class="table-wrapper">

            <div class="login-box">
                <h2>Login here</h2>
                <form action="<?php echo Config::get('URL'); ?>login/login" method="post">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                    <input type="text" name="user_name" placeholder="Username or email" required />
                    <input type="password" name="user_password" placeholder="Password" required />

                    <label for="set_remember_me_cookie" class="remember-me-label">
                        <input type="checkbox" name="set_remember_me_cookie" class="remember-me-checkbox" />
                        Remember me for 2 weeks
                    </label>

                    <?php if (!empty($this->redirect)) { ?>
                        <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
                    <?php } ?>

                    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
                    <input type="submit" class="login-submit-button" value="Log in"/>
                </form>

                <div class="link-forgot-my-password">
                    <a href="<?php echo Config::get('URL'); ?>login/requestPasswordReset">I forgot my password</a>
                </div>
            </div>

            <div class="register-box">
                <h2>No account yet ?</h2>
                <a href="<?php echo Config::get('URL'); ?>register/index">Register</a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('.login-box form');
  if (!form) return;

  let recaptchaDone = false;

  form.addEventListener('submit', function (e) {
    if (recaptchaDone) return;
    e.preventDefault();

    grecaptcha.ready(function () {
      grecaptcha.execute('6LeE_UcsAAAAAGXOr2mKj7jX_YtS0Gf28umNVlgM', { action: 'login' })
        .then(function (token) {
          document.getElementById('g-recaptcha-response').value = token;
          recaptchaDone = true;
          form.submit();
        })
        .catch(function () {
          alert('reCAPTCHA konnte nicht geladen werden. Bitte neu laden.');
        });
    });
  });
});
</script>
