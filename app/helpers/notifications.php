<?php

function output_alert($type, $message, $icon = true, $dismissable = true) {

    switch($type) {
        case 'error':
            $alert_type = 'danger';
            $icon = $icon ? '<i class="fa fa-fw fa-times-circle text-' . $alert_type . ' mr-1"></i>' : null;
            break;

        case 'success':
            $alert_type = 'success';
            $icon = $icon ? '<i class="fa fa-fw fa-check-circle text-' . $alert_type . ' mr-1"></i>' : null;
            break;

        case 'info':
            $alert_type = 'info';
            $icon = $icon ? '<i class="fa fa-fw fa-info-circle text-' . $alert_type . ' mr-1"></i>' : null;
            break;
    }

    $dismiss_button = $dismissable ? '<button type="button" class="close" data-dismiss="alert"><i class="fa fa-fw fa-sm fa-times text-' . $alert_type . '"></i></button>' : null;

    return '
        <div class="alert alert-' . $alert_type . ' altum-animate altum-animate-fill-both altum-animate-fade-in">
            ' . $icon . '
            ' . $dismiss_button . '
            ' . $message . '
        </div>
    ';
}
