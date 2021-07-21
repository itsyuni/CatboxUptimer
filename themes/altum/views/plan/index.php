<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= language()->plan->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <?php if(\Altum\Middlewares\Authentication::check() && $this->user->plan_is_expired && $this->user->plan_id != 'free'): ?>
        <div class="alert alert-info" role="alert">
            <?= language()->global->info_message->user_plan_is_expired ?>
        </div>
    <?php endif ?>

    <?php if($data->type == 'new'): ?>

        <h1 class="h3"><?= language()->plan->header_new ?></h1>
        <span class="text-muted"><?= language()->plan->subheader_new ?></span>

    <?php elseif($data->type == 'renew'): ?>

        <h1 class="h3"><?= language()->plan->header_renew ?></h1>
        <span class="text-muted"><?= language()->plan->subheader_renew ?></span>

    <?php elseif($data->type == 'upgrade'): ?>

        <h1 class="h3"><?= language()->plan->header_upgrade ?></h1>
        <span class="text-muted"><?= language()->plan->subheader_upgrade ?></span>

    <?php endif ?>

    <div class="mt-5">
        <?= $this->views['plans'] ?>
    </div>

    <div class="mt-8">
        <h1 class="h3"><?= language()->plan->why->header ?></h1>
        <span class="text-muted"><?= language()->plan->why->subheader ?></span>

        <div class="mt-5 row">
            <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                <div class="card bg-gray-50 border-0">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <span class="h5"><?= language()->plan->why->one->header ?></span>
                            <span class="text-muted"><?= language()->plan->why->one->subheader ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                <div class="card bg-gray-50 border-0">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <span class="h5"><?= language()->plan->why->two->header ?></span>
                            <span class="text-muted"><?= language()->plan->why->two->subheader ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                <div class="card bg-gray-50 border-0">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <span class="h5"><?= language()->plan->why->three->header ?></span>
                            <span class="text-muted"><?= language()->plan->why->three->subheader ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h1 class="h3"><?= language()->plan->faq->header ?></h1>
        <span class="text-muted"><?= language()->plan->faq->subheader ?></span>

        <div class="mt-5">
            <h2 class="h5"><?= language()->plan->faq->one->header ?></h2>
            <p class="text-muted"><?= language()->plan->faq->one->text ?></p>
        </div>

        <div class="mt-5">
            <h2 class="h5"><?= language()->plan->faq->two->header ?></h2>
            <p class="text-muted"><?= language()->plan->faq->two->text ?></p>
        </div>

        <div class="mt-5">
            <h2 class="h5"><?= language()->plan->faq->three->header ?></h2>
            <p class="text-muted"><?= language()->plan->faq->three->text ?></p>
        </div>

        <div class="mt-5">
            <h2 class="h5"><?= language()->plan->faq->four->header ?></h2>
            <p class="text-muted"><?= language()->plan->faq->four->text ?></p>
        </div>
    </div>
</div>
