<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <a href="#" data-toggle="dropdown" class="text-secondary dropdown-toggle dropdown-toggle-simple">
        <i class="fa fa-fw fa-ellipsis-v mr-1"></i>

        <div class="dropdown-menu dropdown-menu-right">
            <?php if(!$data->is_paid): ?>
                <a href="#" data-toggle="modal" data-target="#affiliate_withdrawal_approve_modal" data-affiliate-withdrawal-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-check mr-1"></i> <?= language()->admin_affiliates_withdrawals->table->action_pay_affiliate_withdrawal ?></a>
            <?php endif ?>

            <a href="#" data-toggle="modal" data-target="#affiliate_withdrawal_delete_modal" data-affiliate-withdrawal-id="<?= $data->id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
        </div>
    </a>
</div>
