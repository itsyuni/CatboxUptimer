<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-chart-pie fa-xs text-muted"></i> <?= language()->admin_statistics->heartbeats_logs->header ?></h2>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['heartbeats_logs']) ?></span> <?= language()->admin_statistics->heartbeats_logs->chart ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="heartbeats_logs"></canvas>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let heartbeats_logs_chart = document.getElementById('heartbeats_logs').getContext('2d');
    color_gradient = heartbeats_logs_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(heartbeats_logs_chart, {
        type: 'line',
        data: {
            labels: <?= $data->heartbeats_logs_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->admin_statistics->heartbeats_logs->chart) ?>,
                    data: <?= $data->heartbeats_logs_chart['heartbeats_logs'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
