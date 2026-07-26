<?php $this->layout('layouts/main'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4"><?= __('messages.login') ?></h2>
                    
                    <form method="POST" action="/login">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label"><?= __('messages.email') ?></label>
                            <input type="email" class="form-control <?= session_has('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= old('email') ?>" required>
                            <?php if (session_has('errors.email')): ?>
                                <div class="invalid-feedback"><?= session_get('errors.email') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label"><?= __('messages.password') ?></label>
                            <input type="password" class="form-control <?= session_has('errors.password') ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" required>
                            <?php if (session_has('errors.password')): ?>
                                <div class="invalid-feedback"><?= session_get('errors.password') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember"><?= __('messages.remember_me') ?></label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> <?= __('messages.login') ?>
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="/forgot-password" class="text-decoration-none"><?= __('messages.forgot_password') ?></a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p><?= __('messages.dont_have_account') ?> <a href="/register"><?= __('messages.register') ?></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
