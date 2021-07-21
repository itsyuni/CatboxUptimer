<?php defined('ALTUMCODE') || die() ?>

<p><?= sprintf($data->language->cron->monitor_email_report->p1, $data->row->name) ?></p>

<div>
    <table>
        <tbody>
            <tr>
                <th><?= $data->language->cron->monitor_email_report->datetime_range ?></th>
                <td>
                    <span class="text-muted">
                        <?= \Altum\Date::get($data->start_date, 5) . ' - ' . \Altum\Date::get($data->end_date, 5) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->monitor_email_report->uptime ?></th>
                <td>
                    <span class="text-muted">
                        <?= nr($data->monitor_logs_data['uptime'], 3) . '%' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->monitor_email_report->average_response_time ?></th>
                <td>
                    <span class="text-muted">
                        <?= display_response_time($data->monitor_logs_data['average_response_time']) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?= $data->language->cron->monitor_email_report->total_monitor_logs ?></th>
                <td>
                    <span class="text-muted">
                        <?= nr($data->total_monitor_logs) ?>
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
                            <a href="<?= url('monitor/' . $data->row->monitor_id) ?>">
                                <?= sprintf($data->language->cron->monitor_email_report->button, $data->row->name) ?>
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
    <small class="text-muted"><?= sprintf($data->language->cron->monitor_email_report->notice, '<a href="' . url('monitor-update/' . $data->row->monitor_id) . '">', '</a>') ?></small>
</p>
