<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
        <i class="fa fa-fw fa-ellipsis-v mr-1"></i>

        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="admin/plan-update/<?= $data->id ?>"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= language()->global->edit ?></a>

            <?php if(is_numeric($data->id)): ?>
            <a href="#" data-toggle="modal" data-target="#plan_delete_modal" data-plan-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
            <?php endif ?>

        </div>
    </a>
</div>
