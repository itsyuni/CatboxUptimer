<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf($data->language->cron->monitor_ssl_expiration_notice->p1, $data->row->name, $data->row->target, $data->ssl_expires_in_days) ?></p>

<p>
    <small class="text-muted"><?= sprintf($data->language->cron->monitor_ssl_expiration_notice->notice, '<a href="' . url('monitor-update/' . $data->row->monitor_id) . '">', '</a>') ?></small>
</p>
