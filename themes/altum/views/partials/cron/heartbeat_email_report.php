<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf($data->language->cron->heartbeat_email_report->p1, $data->row->name) ?></p>

<div>
    <table>
        <tbody>
            <tr>
                <th><?= $data->language->cron->heartbeat_email_report->datetime_range ?></th>
                <td>
                    <span class="text-muted">
                        <?= \Altum\Date::get($data->start_date, 5) . ' - ' . \Altum\Date::get($data->end_date, 5) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->heartbeat_email_report->uptime ?></th>
                <td>
                    <span class="text-muted">
                        <?= nr($data->heartbeat_logs_data['uptime'], 3) . '%' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->heartbeat_email_report->total_runs ?></th>
                <td>
                    <span class="text-muted">
                        <?= nr($data->heartbeat_logs_data['total_runs']) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->heartbeat_email_report->total_missed_runs ?></th>
                <td>
                    <span class="text-muted">
                        <?= nr($data->heartbeat_logs_data['total_missed_runs']) ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 30px">
    <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
        <tbody>
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td>
                            <a href="<?= url('heartbeat/' . $data->row->heartbeat_id) ?>">
                                <?= sprintf($data->language->cron->heartbeat_email_report->button, $data->row->name) ?>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<p>
    <small class="text-muted"><?= sprintf($data->language->cron->heartbeat_email_report->notice, '<a href="' . url('heartbeat-update/' . $data->row->heartbeat_id) . '">', '</a>') ?></small>
</p>
