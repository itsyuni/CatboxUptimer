<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <small>
            <ol class="custom-breadcrumbs">
                <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= language()->api_documentation->breadcrumb ?></li>
            </ol>
        </small>
    </nav>

    <div class="row mb-7">
        <div class="col-12 col-lg-7 mb-4 mb-lg-0">
            <h1 class="h4"><?= language()->api_documentation->header ?></h1>
            <p class="text-muted"><?= language()->api_documentation->subheader ?></p>
        </div>

        <div class="col-12 col-lg-4 offset-lg-1">

            <div class="mb-3">
                <a href="<?= url('account-api') ?>" target="_blank" class="btn btn-block btn-outline-primary"><?= language()->api_documentation->api_key ?></a>
            </div>

            <div class="form-group">
                <label for="base_url"><?= language()->api_documentation->base_url ?></label>
                <input type="text" id="base_url" value="<?= SITE_URL . 'api' ?>" class="form-control" readonly="readonly" />
            </div>

        </div>
    </div>

    <div class="mb-7">

        <div class="mb-4">
            <h2 class="h5"><?= language()->api_documentation->authentication->header ?></h2>
            <p class="text-muted"><?= language()->api_documentation->authentication->subheader ?></p>
        </div>

        <div class="form-group">
            <label><?= language()->api_documentation->example ?></label>
            <div class="card bg-gray-50 border-0">
                <div class="card-body">
                    curl --request GET \<br />
                    --url '<?= SITE_URL . 'api/' ?><span class="text-primary">{endpoint}</span>' \<br />
                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-7" />

    <div data-api="user">
        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->user->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link text-decoration-none" data-toggle="collapse" data-target="#user_read" aria-expanded="true" aria-controls="user_read">
                            <?= language()->api_documentation->user->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="user_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/user</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/user' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id":"1",
        "type":"users",
        "email":"example@example.com",
        "billing":{
            "type":"personal",
            "name":"John Doe",
            "address":"Lorem Ipsum",
            "city":"Dolor Sit",
            "county":"Amet",
            "zip":"5000",
            "country":"",
            "phone":"",
            "tax_id":""
        },
        "is_enabled":true,
        "plan_id":"custom",
        "plan_expiration_date":"2025-12-12 00:00:00",
        "plan_settings":{
            ...
        },
        "plan_trial_done":false,
        "language":"english",
        "timezone":"UTC",
        "country":null,
        "date":"2020-01-01 00:00:00",
        "last_activity":"2020-01-01 00:00:00",
        "total_logins":10
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="monitors">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->monitors->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#monitors_read_all" aria-expanded="true" aria-controls="monitors_read_all">
                            <?= language()->api_documentation->monitors->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="monitors_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/monitors/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/monitors/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "project_id": 0,
            "name": "Example",
            "type": "website",
            "target": "https://example.com/",
            "port": 0,
            "settings": {
                "check_interval_seconds": 3600,
                "timeout_seconds": 1,
                "request_method": "GET",
                "request_body": "",
                "request_basic_auth_username": "",
                "request_basic_auth_password": "",
                "request_headers": [],
                "response_status_code": 200,
                "response_body": "",
                "response_headers": []
            },
            "ping_servers_ids": [1],
            "is_ok": 1,
            "uptime": 95.5,
            "downtime": 4.5,
            "average_response_time": 500,
            "total_checks": 500,
            "total_ok_checks": 450,
            "total_not_ok_checks": 50,
            "last_check_datetime": "2021-03-25 08:27:07",
            "notifications": {
                "email_is_enabled": 0,
                "webhook":"",
                "slack":"",
                "twilio":""
            },
            "is_enabled": false,
            "datetime": "2021-02-12 21:54:29"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/monitors?&page=1",
        "last": "<?= SITE_URL ?>api/monitors?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/monitors?&page=1"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#monitors_read" aria-expanded="true" aria-controls="monitors_read">
                            <?= language()->api_documentation->monitors->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="monitors_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/monitors/</span><span class="text-primary">{monitor_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/monitors/<span class="text-primary">{monitor_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "project_id": 0,
        "name": "Example",
        "type": "website",
        "target": "https://example.com/",
        "port": 0,
        "settings": {
            "check_interval_seconds": 3600,
            "timeout_seconds": 1,
            "request_method": "GET",
            "request_body": "",
            "request_basic_auth_username": "",
            "request_basic_auth_password": "",
            "request_headers": [],
            "response_status_code": 200,
            "response_body": "",
            "response_headers": []
        },
        "ping_servers_ids": [1],
        "is_ok": 1,
        "uptime": 95.5,
        "downtime": 4.5,
        "average_response_time": 500,
        "total_checks": 500,
        "total_ok_checks": 450,
        "total_not_ok_checks": 50,
        "last_check_datetime": "2021-03-25 08:27:07",
        "notifications": {
            "email_is_enabled": 0,
            "webhook":"",
            "slack":"",
            "twilio":""
        },
        "is_enabled": false,
        "datetime": "2021-02-12 21:54:29"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="heartbeats">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->heartbeats->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#heartbeats_read_all" aria-expanded="true" aria-controls="heartbeats_read_all">
                            <?= language()->api_documentation->heartbeats->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="heartbeats_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/heartbeats/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/heartbeats/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "project_id": 0,
            "name": "Demo cron",
            "code": "12345678901112131415",
            "settings": {
            "run_interval": 1,
            "run_interval_type": "minutes",
            "run_interval_grace": 1,
            "run_interval_grace_type": "seconds"
            },
            "is_ok": 1,
            "uptime": 75.5,
            "downtime": 24.5,
            "total_runs": 50,
            "total_missed_runs": 15,
            "last_run_datetime": "2021-03-30 19:17:22",
            "notifications": {
                "email_is_enabled": 0,
                "webhook": "",
                "slack": "",
                "twilio": ""
            },
            "is_enabled": true,
            "datetime": "2021-03-09 11:30:20"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/heartbeats?&page=1",
        "last": "<?= SITE_URL ?>api/heartbeats?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/heartbeats?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#heartbeats_read" aria-expanded="true" aria-controls="heartbeats_read">
                            <?= language()->api_documentation->heartbeats->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="heartbeats_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/heartbeats/</span><span class="text-primary">{heartbeat_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/heartbeats/<span class="text-primary">{heartbeat_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "project_id": 0,
        "name": "Demo cron",
        "code": "12345678901112131415",
        "settings": {
        "run_interval": 1,
        "run_interval_type": "minutes",
        "run_interval_grace": 1,
        "run_interval_grace_type": "seconds"
        },
        "is_ok": 1,
        "uptime": 75.5,
        "downtime": 24.5,
        "total_runs": 50,
        "total_missed_runs": 15,
        "last_run_datetime": "2021-03-30 19:17:22",
        "notifications": {
            "email_is_enabled": 0,
            "webhook": "",
            "slack": "",
            "twilio": ""
        },
        "is_enabled": true,
        "datetime": "2021-03-09 11:30:20"
    }
}</pre>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="status_pages">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->status_pages->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#status_pages_read_all" aria-expanded="true" aria-controls="status_pages_read_all">
                            <?= language()->api_documentation->status_pages->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="status_pages_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/status-pages/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/status-pages/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "domain_id": 0,
            "monitors_ids": [1,2,3],
            "project_id": 0,
            "url": "example",
            "full_url": "<?= SITE_URL ?>s/example/",
            "name": "Example",
            "description": "This is just a simple description for the example status page 👋.",
            "socials": {
              "facebook": "example",
              "instagram": "example",
              "twitter": "example",
              "email": "",
              "website": "https://example.com/"
            },
            "logo_url": "",
            "favicon_url": ""
            "password": false,
            "timezone": "UTC",
            "custom_js": "",
            "custom_css": "",
            "pageviews": 50,
            "is_se_visible": true,
            "is_removed_branding": false,
            "is_enabled": true,
            "datetime": "2021-02-16 10:47:34"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/status-pages?&page=1",
        "last": "<?= SITE_URL ?>api/status-pages?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/status-pages?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#status_pages_read" aria-expanded="true" aria-controls="status_pages_read">
                            <?= language()->api_documentation->status_pages->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="status_pages_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/status-pages/</span><span class="text-primary">{status_page_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/status-pages/<span class="text-primary">{status_page_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "domain_id": 0,
        "monitors_ids": [1,2,3],
        "project_id": 0,
        "url": "example",
        "full_url": "<?= SITE_URL ?>s/example/",
        "name": "Example",
        "description": "This is just a simple description for the example status page 👋.",
        "socials": {
          "facebook": "example",
          "instagram": "example",
          "twitter": "example",
          "email": "",
          "website": "https://example.com/"
        },
        "logo_url": "",
        "favicon_url": ""
        "password": false,
        "timezone": "UTC",
        "custom_js": "",
        "custom_css": "",
        "pageviews": 50,
        "is_se_visible": true,
        "is_removed_branding": false,
        "is_enabled": true,
        "datetime": "2021-02-16 10:47:34"
    }
}</pre>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="statistics">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->statistics->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#statistics_read" aria-expanded="true" aria-controls="statistics_read">
                            <?= language()->api_documentation->statistics->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="statistics_read" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/statistics/</span><span class="text-primary">{status_page_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request GET \<br />
                                    --url '<?= SITE_URL ?>api/statistics/<span class="text-primary">{status_page_id}</span>?start_date=<span class="text-primary">2020-01-01</span>&end_date=<span class="text-primary">2021-01-01</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>start_date</td>
                                    <td><span class="badge badge-danger"><?= language()->api_documentation->required ?></span></td>
                                    <td><?= language()->api_documentation->statistics->start_date ?></td>
                                </tr>
                                <tr>
                                    <td>end_date</td>
                                    <td><span class="badge badge-danger"><?= language()->api_documentation->required ?></span></td>
                                    <td><?= language()->api_documentation->statistics->end_date ?></td>
                                </tr>
                                <tr>
                                    <td>type</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->statistics->type ?></td>
                                </tr>
                                <tr>
                                    <td>country_code</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->statistics->country_code ?></td>
                                </tr>
                                <tr>
                                    <td>utm_source</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->statistics->utm_source ?></td>
                                </tr>
                                <tr>
                                    <td>utm_medium</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td><?= language()->api_documentation->statistics->utm_medium ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                    <pre class="card-body">
{
    "data": [
        {
            "pageviews": 20,
            "visitors": 5,
            "formatted_date": "2021-01"
        },
        {
            "pageviews": 35,
            "visitors": 10,
            "formatted_date": "2021-02"
        },
        {
            "pageviews": 50,
            "visitors": 25,
            "formatted_date": "2021-03"
        }
    ]
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="projects">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->projects->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#projects_read_all" aria-expanded="true" aria-controls="projects_read_all">
                            <?= language()->api_documentation->projects->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="projects_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/projects/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/projects/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "name": "Development",
            "color": "#0e23cc",
            "last_datetime": "2021-03-14 21:22:37",
            "datetime": "2021-02-04 17:51:07"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/projects?&page=1",
        "last": "<?= SITE_URL ?>api/projects?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/projects?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#projects_read" aria-expanded="true" aria-controls="projects_read">
                            <?= language()->api_documentation->projects->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="projects_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/projects/</span><span class="text-primary">{project_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/projects/<span class="text-primary">{project_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "name": "Development",
        "color": "#0e23cc",
        "last_datetime": "2021-03-14 21:22:37",
        "datetime": "2021-02-04 17:51:07"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#projects_create" aria-expanded="true" aria-controls="projects_create">
                            <?= language()->api_documentation->projects->create_header ?>
                        </a>
                    </h3>
                </div>

                <div id="projects_create" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/projects</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>name</td>
                                    <td><span class="badge badge-danger"><?= language()->api_documentation->required ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>color</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request POST \<br />
                                    --url '<?= SITE_URL ?>api/projects' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                    --header 'Content-Type: multipart/form-data' \<br />
                                    --form 'name=<span class="text-primary">Production</span>' \<br />
                                    --form 'color=<span class="text-primary">#ffffff</span>' \<br />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                <pre class="card-body">
{
    "data": {
        "id": 1
    }
}</pre>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#projects_update" aria-expanded="true" aria-controls="projects_update">
                            <?= language()->api_documentation->projects->update_header ?>
                        </a>
                    </h3>
                </div>

                <div id="projects_update" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/projects/</span><span class="text-primary">{project_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>name</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>color</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request POST \<br />
                                    --url '<?= SITE_URL ?>api/projects/<span class="text-primary">{project_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                    --header 'Content-Type: multipart/form-data' \<br />
                                    --form 'name=<span class="text-primary">Production</span>' \<br />
                                    --form 'color=<span class="text-primary">#000000</span>' \<br />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                <pre class="card-body">
{
  "data": {
    "id": 1
  }
}</pre>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#projects_delete" aria-expanded="true" aria-controls="projects_delete">
                            <?= language()->api_documentation->projects->delete_header ?>
                        </a>
                    </h3>
                </div>

                <div id="projects_delete" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/projects/</span><span class="text-primary">{project_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request DELETE \<br />
                                    --url '<?= SITE_URL ?>api/projects/<span class="text-primary">{project_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="domains">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->domains->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_read_all" aria-expanded="true" aria-controls="domains_read_all">
                            <?= language()->api_documentation->domains->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="domains_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/domains/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/domains/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "scheme": "https://",
            "host": "example.com",
            "custom_index_url": "",
            "is_enabled": true,
            "last_datetime": null,
            "datetime": "2021-02-04 23:29:18"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/domains?&page=1",
        "last": "<?= SITE_URL ?>api/domains?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/domains?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_read" aria-expanded="true" aria-controls="domains_read">
                            <?= language()->api_documentation->domains->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="domains_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/domains/</span><span class="text-primary">{domain_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/domains/<span class="text-primary">{domain_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "scheme": "https://",
        "host": "example.com",
        "custom_index_url": "",
        "is_enabled": true,
        "last_datetime": null,
        "datetime": "2021-02-04 23:29:18"
    }
}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_create" aria-expanded="true" aria-controls="domains_create">
                            <?= language()->api_documentation->domains->create_header ?>
                        </a>
                    </h3>
                </div>

                <div id="domains_create" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/domains</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>host</td>
                                    <td><span class="badge badge-danger"><?= language()->api_documentation->required ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>custom_index_url</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>custom_not_found_url</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request POST \<br />
                                    --url '<?= SITE_URL ?>api/domains' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                    --header 'Content-Type: multipart/form-data' \<br />
                                    --form 'host=<span class="text-primary">domain.com</span>' \<br />
                                    --form 'custom_index_url=<span class="text-primary">https://mywebsite.com/</span>' \<br />
                                    --form 'custom_not_found_url=<span class="text-primary">https://mywebsite.com/404-page</span>'
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1
    }
}</pre>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_update" aria-expanded="true" aria-controls="domains_update">
                            <?= language()->api_documentation->domains->update_header ?>
                        </a>
                    </h3>
                </div>

                <div id="domains_update" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/domains/</span><span class="text-primary">{domain_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-custom-container mb-4">
                            <table class="table table-custom">
                                <thead>
                                <tr>
                                    <th><?= language()->api_documentation->parameters ?></th>
                                    <th><?= language()->api_documentation->details ?></th>
                                    <th><?= language()->api_documentation->description ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>host</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>custom_index_url</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>custom_not_found_url</td>
                                    <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                    <td>-</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request POST \<br />
                                    --url '<?= SITE_URL ?>api/domains/<span class="text-primary">{domain_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                    --header 'Content-Type: multipart/form-data' \<br />
                                    --form 'host=<span class="text-primary">domain.com</span>' \<br />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->response ?></label>
                            <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
  "data": {
    "id": 1
  }
}</pre>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_delete" aria-expanded="true" aria-controls="domains_delete">
                            <?= language()->api_documentation->domains->delete_header ?>
                        </a>
                    </h3>
                </div>

                <div id="domains_delete" class="collapse">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label><?= language()->api_documentation->endpoint ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/domains/</span><span class="text-primary">{domain_id}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><?= language()->api_documentation->example ?></label>
                            <div class="card bg-gray-100 border-0">
                                <div class="card-body">
                                    curl --request DELETE \<br />
                                    --url '<?= SITE_URL ?>api/domains/<span class="text-primary">{domain_id}</span>' \<br />
                                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="payments">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->payments->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#payments_read_all" aria-expanded="true" aria-controls="payments_read_all">
                            <?= language()->api_documentation->payments->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="payments_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/payments/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/payments/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "id": 1,
            "plan_id": 1,
            "processor": "stripe",
            "type": "one_time",
            "frequency": "monthly",
            "email": "example@example.com",
            "name": null,
            "total_amount": "4.99",
            "currency": "USD",
            "status": true,
            "date": "2021-03-25 15:08:58"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/payments?&page=1",
        "last": "<?= SITE_URL ?>api/payments?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/payments?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#payments_read" aria-expanded="true" aria-controls="payments_read">
                            <?= language()->api_documentation->payments->read_header ?>
                        </a>
                    </h3>
                </div>

                <div id="payments_read" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/payments/</span><span class="text-primary">{payment_id}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/payments/<span class="text-primary">{payment_id}</span>' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": {
        "id": 1,
        "plan_id": 1,
        "processor": "stripe",
        "type": "one_time",
        "frequency": "monthly",
        "email": "example@example.com",
        "name": null,
        "total_amount": "4.99",
        "currency": "USD",
        "status": true,
        "date": "2021-03-25 15:08:58"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <hr class="border-gray-100 my-5" />

    <div data-api="logs">

        <div class="mb-3">
            <h2 class="h5"><?= language()->api_documentation->users_logs->header ?></h2>
        </div>

        <div class="accordion">
            <div class="card">
                <div class="card-header bg-gray-50 p-3 position-relative">
                    <h3 class="h6 m-0">
                        <a href="#" class="stretched-link" data-toggle="collapse" data-target="#logs_read_all" aria-expanded="true" aria-controls="logs_read_all">
                            <?= language()->api_documentation->users_logs->read_all_header ?>
                        </a>
                    </h3>
                </div>

                <div id="logs_read_all" class="collapse">
                    <div class="card-body">

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->endpoint ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/logs/</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label><?= language()->api_documentation->example ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <div class="card-body">
                                            curl --request GET \<br />
                                            --url '<?= SITE_URL ?>api/logs/' \<br />
                                            --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-custom-container mb-4">
                                    <table class="table table-custom">
                                        <thead>
                                        <tr>
                                            <th><?= language()->api_documentation->parameters ?></th>
                                            <th><?= language()->api_documentation->details ?></th>
                                            <th><?= language()->api_documentation->description ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= language()->api_documentation->filters->page ?></td>
                                        </tr>
                                        <tr>
                                            <td>results_per_page</td>
                                            <td><span class="badge badge-info"><?= language()->api_documentation->optional ?></span></td>
                                            <td><?= sprintf(language()->api_documentation->filters->results_per_page, '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250]) . '</code>', 25) ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label><?= language()->api_documentation->response ?></label>
                                    <div class="card bg-gray-100 border-0">
                                        <pre class="card-body">
{
    "data": [
        {
            "type": "login.success",
            "ip": "127.0.0.1",
            "date": "2021-02-03 12:21:40"
        },
        {
            "type": "login.success",
            "ip": "127.0.0.1",
            "date": "2021-02-03 12:23:26"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/payments?&page=1",
        "last": "<?= SITE_URL ?>api/payments?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/payments?&page=1"
    }
}</pre>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
