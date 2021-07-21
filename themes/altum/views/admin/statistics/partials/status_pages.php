<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-wifi fa-xs text-muted"></i> <?= language()->admin_statistics->status_pages->header ?></h2>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['status_pages']) ?></span> <?= language()->admin_statistics->status_pages->chart ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="status_pages"></canvas>
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
    let status_pages_chart = document.getElementById('status_pages').getContext('2d');
    color_gradient = status_pages_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, 'rgba(63, 136, 253, .1)');
    color_gradient.addColorStop(1, 'rgba(63, 136, 253, 0.025)');

    new Chart(status_pages_chart, {
        type: 'line',
        data: {
            labels: <?= $data->status_pages_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->admin_statistics->status_pages->chart) ?>,
                    data: <?= $data->status_pages_chart['status_pages'] ?? '[]' ?>,
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
