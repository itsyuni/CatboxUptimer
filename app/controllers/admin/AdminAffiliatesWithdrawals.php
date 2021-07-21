<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Language;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminAffiliatesWithdrawals extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_paid', 'user_id'], [], ['amount', 'datetime']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `affiliates_withdrawals` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/affiliates-withdrawals?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $affiliates_withdrawals = [];
        $affiliates_withdrawals_result = database()->query("
            SELECT
                `affiliates_withdrawals`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `affiliates_withdrawals`
            LEFT JOIN
                `users` ON `affiliates_withdrawals`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('affiliates_withdrawals')}
                {$filters->get_sql_order_by('affiliates_withdrawals')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $affiliates_withdrawals_result->fetch_object()) {
            $affiliates_withdrawals[] = $row;
        }

        /* Export handler */
        process_export_json($affiliates_withdrawals, 'include', ['id', 'user_id', 'amount', 'note', 'is_paid', 'datetime']);
        process_export_csv($affiliates_withdrawals, 'include', ['id', 'user_id', 'amount', 'note', 'is_paid', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/affiliates-withdrawals/affiliate_withdrawal_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Approve Modal */
        $view = new \Altum\Views\View('admin/affiliates-withdrawals/affiliate_withdrawal_approve_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'affiliates_withdrawals' => $affiliates_withdrawals,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\Views\View('admin/affiliates-withdrawals/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


    public function delete() {

        $affiliate_withdrawal_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('admin/affiliates-withdrawals');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the affiliate withdrawal */
            db()->where('affiliate_withdrawal_id', $affiliate_withdrawal_id)->delete('affiliates_withdrawals');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_affiliate_withdrawal_delete_modal->success_message);

        }

        redirect('admin/affiliates-withdrawals');
    }

    public function approve() {

        $affiliate_withdrawal_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('admin/affiliates-withdrawals');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            /* details about the affiliate withdrawal */
            $affiliate_withdrawal = db()->where('affiliate_withdrawal_id', $affiliate_withdrawal_id)->getOne('affiliates_withdrawals', ['user_id', 'affiliate_commissions_ids', 'amount']);

            $affiliate_withdrawal->affiliate_commissions_ids = json_decode($affiliate_withdrawal->affiliate_commissions_ids);

            /* Get the user that made the withdrawal request */
            $user = db()->where('user_id', $affiliate_withdrawal->user_id)->getOne('users', ['user_id', 'email', 'language']);

            /* Update commissions */
            db()->where('affiliate_commission_id', $affiliate_withdrawal->affiliate_commissions_ids, 'IN')->update('affiliates_commissions', ['is_withdrawn' => 1]);

            /* Update the affiliate withdrawal */
            db()->where('affiliate_withdrawal_id', $affiliate_withdrawal_id)->update('affiliates_withdrawals', ['is_paid' => 1]);

            /* Get the language for the user */
            $language = language($user->language);

            /* Prepare the email */
            $email_template = get_email_template(
                [],
                $language->global->emails->user_affiliate_withdrawal_approved->subject,
                [
                    '{{AMOUNT}}' => nr($affiliate_withdrawal->amount, 2),
                    '{{CURRENCY}}' => settings()->payment->currency,
                ],
                $language->global->emails->user_affiliate_withdrawal_approved->body
            );

            /* Send email notification */
            send_mail(
                $user->email,
                $email_template->subject,
                $email_template->body
            );

            /* Set a nice success message */
            Alerts::add_success(language()->admin_affiliate_withdrawal_approve_modal->success_message);

        }

        redirect('admin/affiliates-withdrawals');
    }
}
