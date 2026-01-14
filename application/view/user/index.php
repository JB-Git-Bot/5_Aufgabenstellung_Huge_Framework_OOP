<div class="container">
    <h1>UserController/showProfile</h1>

    <div class="box">
        <h2>Your profile</h2>
        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <div>Your username: <?= $this->user_name; ?></div>
        <div>Your email: <?= $this->user_email; ?></div>
        <div>Your avatar image:
            <?php if (Config::get('USE_GRAVATAR')) { ?>
                Your gravatar pic (on gravatar.com): <img src='<?= $this->user_gravatar_image_url; ?>' />
            <?php } else { ?>
                Your avatar pic (saved locally): <img src='<?= $this->user_avatar_file; ?>' />
            <?php } ?>
        </div>
        <div>Your account type is: <?= $this->user_account_type; ?></div>

        <?php if (!empty($_COOKIE['recaptcha_ok'])): ?>
            <div style="background:#d4edda;padding:10px;border:1px solid #aaa;margin-bottom:10px;">
                ✅ reCAPTCHA v3 verifiziert<br>
                Score: <?= htmlspecialchars($_COOKIE['recaptcha_score'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
                Action: <?= htmlspecialchars($_COOKIE['recaptcha_action'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</div>
