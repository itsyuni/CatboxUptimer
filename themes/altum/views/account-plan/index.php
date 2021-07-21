<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['app_sub_menu'] ?>

    <div class="">
        <div class="row mb-3">
            <div class="col-12 col-xl">
                <h1 class="h4"><?= $this->user->plan->name ?></h1>
                <?php if($this->user->plan_id != 'free' && (new \DateTime($this->user->plan_expiration_date)) < (new \DateTime())->modify('+5 years')): ?>
                    <p class="text-muted">
                        <?= sprintf(
                            $this->user->payment_subscription_id ? language()->account_plan->plan->renews : language()->account_plan->plan->expires,
                            '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>'
                        ) ?>
                    </p>
                <?php endif ?>
            </div>

            <?php if(settings()->payment->is_enabled): ?>
                <div class="col-12 col-xl-auto">
                    <?php if($this->user->plan_id == 'free'): ?>
                        <a href="<?= url('plan/upgrade') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-arrow-up"></i> <?= language()->account->plan->upgrade_plan ?></a>
                    <?php elseif($this->user->plan_id == 'trial'): ?>
                        <a href="<?= url('plan/renew') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-sync-alt"></i> <?= language()->account->plan->renew_plan ?></a>
                    <?php else: ?>
                        <a href="<?= url('plan/renew') ?>" class="btn btn-outline-primary"><i class="fa fa-fw fa-sm fa-sync-alt"></i> <?= language()->account->plan->renew_plan ?></a>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>

        <div class="card">
            <div class="card-body">

                <?= (new \Altum\Views\View('partials/plan_features'))->run(['plan_settings' => $this->user->plan_settings]) ?>

            </div>
        </div>
    </div>

    <?php if($this->user->plan_id != 'free' && $this->user->payment_subscription_id): ?>
        <hr class="border-gray-50 my-4" />

        <div class="">
            <h1 class="h4"><?= language()->account_plan->cancel->header ?></h1>
            <p class="text-muted"><?= language()->account_plan->cancel->subheader ?></p>

            <a href="<?= url('account/cancelsubscription' . \Altum\Middlewares\Csrf::get_url_query()) ?>" class="btn btn-block btn-outline-secondary" data-confirm="<?= language()->account_plan->cancel->confirm_message ?>"><?= language()->account_plan->cancel->cancel ?></a>
        </div>
    <?php endif ?>

    <?php if(settings()->payment->is_enabled && settings()->payment->codes_is_enabled): ?>
        <hr class="border-gray-50 my-4" />

        <div class="">

            <h2 class="h4"><?= language()->account_plan->code->header ?></h2>
            <p class="text-muted"><?= language()->account_plan->code->subheader ?></p>

            <div class="card">
                <div class="card-body">

                    <form id="code_form" action="<?= url('account-plan/redeem_code') ?>" method="post" role="form">
                        <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

                        <div class="form-group">
                            <label for="code"><i class="fa fa-fw fa-sm fa-tags text-muted mr-1"></i> <?= language()->account_plan->code->input ?></label>
                            <input id="code" type="text" name="code" class="form-control <?= \Altum\Alerts::has_field_errors('code') ? 'is-invalid' : null ?>" />
                            <?= \Altum\Alerts::output_field_error('code') ?>
                            <div class="mt-2"><span id="code_help" class="text-muted"></span></div>
                        </div>

                        <button id="code_submit" type="submit" name="submit" class="btn btn-primary d-none"><?= language()->account_plan->code->submit ?></button>
                    </form>

                </div>
            </div>

        </div>

    <?php ob_start() ?>
        <script>
            /* Disable form submission for code form on empty submissions */
            document.querySelector('#code_form').addEventListener('submit', event => {
                let code = document.querySelector('input[name="code"]').value;

                if(code.trim() == '') {
                    event.preventDefault();
                }
            })
            /* Function to check the discount code */
            let check_code = () => {
                let code = document.querySelector('input[name="code"]').value;
                let is_valid = false;

                if(code.trim() == '') {
                    document.querySelector('input[name="code"]').classList.remove('is-invalid');
                    document.querySelector('input[name="code"]').classList.remove('is-valid');
                    document.querySelector('#code_submit').classList.add('d-none');
                    return;
                }

                fetch(`${url}account-plan/code`, {
                    method: 'POST',
                    body: JSON.stringify({
                        code, global_token
                    }),
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8'
                    }
                })
                    .then(response => {
                        return response.ok ? response.json() : Promise.reject(response);
                    })
                    .then(data => {

                        if(data.status == 'success') {
                            is_valid = true;
                        }

                        document.querySelector('#code_help').innerHTML = data.message;

                        if(is_valid) {
                            document.querySelector('input[name="code"]').classList.add('is-valid');
                            document.querySelector('input[name="code"]').classList.remove('is-invalid');
                            document.querySelector('#code_submit').classList.remove('d-none');
                        } else {
                            document.querySelector('input[name="code"]').classList.add('is-invalid');
                            document.querySelector('input[name="code"]').classList.remove('is-valid');
                            document.querySelector('#code_submit').classList.add('d-none');
                        }

                    })
                    .catch(error => {
                        /* :) */
                    });

            };

            /* Writing hanlder on the input */
            let timer = null;
            let timer_function = () => {
                clearTimeout(timer);

                timer = setTimeout(() => {
                    check_code();
                }, 500);
            }

            document.querySelector('input[name="code"]').addEventListener('change', timer_function);
            document.querySelector('input[name="code"]').addEventListener('paste', timer_function);
            document.querySelector('input[name="code"]').addEventListener('keyup', timer_function);

            /* Autofill code field on header query */
            let current_url = new URL(window.location.href);

            if(current_url.searchParams.get('code')) {
                document.querySelector('input[name="code"]').value = current_url.searchParams.get('code');
                check_code();
            }
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
    <?php endif ?>

</div>

