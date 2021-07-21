<?php

namespace Altum\Routing;

use Altum\Database\Database;
use Altum\Language;

class Router {
    public static $params = [];
    public static $original_request = '';
    public static $language_code = '';
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'wrapper' => 'wrapper',
        'no_authentication_check' => false,

        /* Should we see a view for the controller? */
        'has_view' => true,

        /* If set on yes, ads wont show on these pages at all */
        'no_ads' => false,

        /* Authentication guard check (potential values: null, 'guest', 'user', 'admin') */
        'authentication' => null
    ];
    public static $method = 'index';
    public static $data = [];

    public static $routes = [
        's' => [
            'status-page' => [
                'controller' => 'StatusPage',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],
            'monitor' => [
                'controller' => 'Monitor',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],
        ],

        '' => [
            'index' => [
                'controller' => 'Index'
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'affiliate' => [
                'controller' => 'Affiliate'
            ],

            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'api-documentation' => [
                'controller' => 'ApiDocumentation',
                'settings' => [
                    'no_ads'    => true
                ]
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'notfound' => [
                'controller' => 'NotFound'
            ],

            /* Logged in */
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'monitors' => [
                'controller' => 'Monitors',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'monitor' => [
                'controller' => 'Monitor',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'monitor-logs' => [
                'controller' => 'MonitorLogs',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'monitor-create' => [
                'controller' => 'MonitorCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'monitor-update' => [
                'controller' => 'MonitorUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-pages' => [
                'controller' => 'StatusPages',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page' => [
                'controller' => 'StatusPage',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page-update' => [
                'controller' => 'StatusPageUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page-create' => [
                'controller' => 'StatusPageCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page-statistics' => [
                'controller' => 'StatusPageStatistics',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page-qr' => [
                'controller' => 'StatusPageQr',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'status-page-redirect' => [
                'controller' => 'StatusPageRedirect',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heartbeats' => [
                'controller' => 'Heartbeats',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heartbeat' => [
                'controller' => 'Heartbeat',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heartbeat-update' => [
                'controller' => 'HeartbeatUpdate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heartbeat-create' => [
                'controller' => 'HeartbeatCreate',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-plan' => [
                'controller' => 'AccountPlan',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-api' => [
                'controller' => 'AccountApi',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-delete' => [
                'controller' => 'AccountDelete',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'referrals' => [
                'controller' => 'Referrals',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'refer' => [
                'controller' => 'Refer',
                'settings' => [
                    'has_view' => false
                ]
            ],

            'domains' => [
                'controller' => 'Domains',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'domain-create' => [
                'controller' => 'DomainCreate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'domain-update' => [
                'controller' => 'DomainUpdate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'projects' => [
                'controller' => 'Projects',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'project-create' => [
                'controller' => 'ProjectCreate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'project-update' => [
                'controller' => 'ProjectUpdate',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                    'no_ads' => true
                ]
            ],

            'plan' => [
                'controller' => 'Plan',
                'settings' => [
                    'no_ads'    => true
                ]
            ],

            'pay' => [
                'controller' => 'Pay',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'pay-billing' => [
                'controller' => 'PayBilling',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads' => true
                ]
            ],

            'pay-thank-you' => [
                'controller' => 'PayThankYou',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],


            /* Webhooks */
            'webhook-heartbeat' => [
                'controller' => 'WebhookHeartbeat',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'webhook-paypal' => [
                'controller' => 'WebhookPaypal',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            /* Others */
            'sitemap' => [
                'controller' => 'Sitemap',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'cron' => [
                'controller' => 'Cron',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

        ],

        'api' => [
            'monitors' => [
                'controller' => 'ApiMonitors',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'heartbeats' => [
                'controller' => 'ApiHeartbeats',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'status-pages' => [
                'controller' => 'ApiStatusPages',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'statistics' => [
                'controller' => 'ApiStatistics',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'projects' => [
                'controller' => 'ApiProjects',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'user' => [
                'controller' => 'ApiUser',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'payments' => [
                'controller' => 'ApiPayments',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'logs' => [
                'controller' => 'ApiLogs',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
        ],

        /* Admin Panel */
        /* Authentication is set by default to 'admin' */
        'admin' => [
            'index' => [
                'controller' => 'AdminIndex',
            ],

            'users' => [
                'controller' => 'AdminUsers',
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate',
            ],

            'user-view' => [
                'controller' => 'AdminUserView',
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate',
            ],

            'status-pages' => [
                'controller' => 'AdminStatusPages',
            ],

            'monitors' => [
                'controller' => 'AdminMonitors',
            ],

            'heartbeats' => [
                'controller' => 'AdminHeartbeats',
            ],

            'projects' => [
                'controller' => 'AdminProjects',
            ],

            'domains' => [
                'controller' => 'AdminDomains',
            ],

            'domain-create' => [
                'controller' => 'AdminDomainCreate',
            ],

            'domain-update' => [
                'controller' => 'AdminDomainUpdate',
            ],

            'ping-servers' => [
                'controller' => 'AdminPingServers',
            ],

            'ping-server-create' => [
                'controller' => 'AdminPingServerCreate',
            ],

            'ping-server-update' => [
                'controller' => 'AdminPingServerUpdate',
            ],

            'pages-categories' => [
                'controller' => 'AdminPagesCategories',
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate',
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate',
            ],

            'pages' => [
                'controller' => 'AdminPages',
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate',
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate',
            ],


            'plans' => [
                'controller' => 'AdminPlans',
            ],

            'plan-create' => [
                'controller' => 'AdminPlanCreate',
            ],

            'plan-update' => [
                'controller' => 'AdminPlanUpdate',
            ],


            'codes' => [
                'controller' => 'AdminCodes',
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate',
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate',
            ],


            'taxes' => [
                'controller' => 'AdminTaxes',
            ],

            'tax-create' => [
                'controller' => 'AdminTaxCreate',
            ],

            'tax-update' => [
                'controller' => 'AdminTaxUpdate',
            ],


            'affiliates-withdrawals' => [
                'controller' => 'AdminAffiliatesWithdrawals',
            ],


            'payments' => [
                'controller' => 'AdminPayments',
            ],

            'statistics' => [
                'controller' => 'AdminStatistics',
            ],

            'plugins' => [
                'controller' => 'AdminPlugins',
            ],

            'settings' => [
                'controller' => 'AdminSettings',
            ],

            'api-documentation' => [
                'controller' => 'AdminApiDocumentation',
            ],
        ],

        'admin-api' => [
            'users' => [
                'controller' => 'AdminApiUsers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'plans' => [
                'controller' => 'AdminApiPlans',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
        ],
    ];


    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', filter_var(rtrim($_GET['altum'], '/'), FILTER_SANITIZE_URL));
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_language() {

        /* Check for potential language set in the first parameter */
        if(!empty(self::$params[0]) && array_key_exists(self::$params[0], Language::$languages)) {

            /* Set the language */
            $language_code = filter_var(self::$params[0], FILTER_SANITIZE_STRING);
            Language::set_by_code($language_code);
            self::$language_code = $language_code;

            /* Unset the parameter so that it wont be used further */
            unset(self::$params[0]);
            self::$params = array_values(self::$params);

        }

    }

    public static function parse_controller() {

        self::$original_request = filter_var(implode('/', self::$params), FILTER_SANITIZE_STRING);

        /* Check if the current link accessed is actually the original url or not (multi domain use) */
        $original_url_host = parse_url(url())['host'];
        $request_url_host = Database::clean_string($_SERVER['HTTP_HOST']);

        if($original_url_host != $request_url_host) {

            /* Make sure the custom domain is attached */
            $domain = (new \Altum\Models\Domain())->get_domain_by_host($request_url_host);;

            if($domain && $domain->is_enabled) {
                self::$path = 's';

                /* Set some route data */
                self::$data['domain'] = $domain;

            }

        }

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if(in_array(self::$params[0], ['admin', 's', 'admin-api', 'api'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        /* Check for potential Store link */
        if(self::$path == 's') {

            /* Status page */
            self::$controller_key = 'status-page';
            self::$controller = 'StatusPage';

            if(isset(self::$params[0], self::$params[1])) {

                /* Monitor */
                self::$controller_key = 'monitor';
                self::$controller = 'Monitor';

            }

        }

        else if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Not found controller */
                self::$path = '';
                self::$controller_key = 'notfound';

            }

        }

        /* Save the current controller */
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Admin path authentication force check */
        if(self::$path == 'admin' && !isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$routes[self::$path][self::$controller_key]['settings'] = ['authentication' => 'admin'];
        }

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_ame, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_ame . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_ame;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Make sure to check the class method if set in the url */
        if(isset(self::get_params()[0]) && method_exists($controller, self::get_params()[0])) {

            /* Make sure the method is not private */
            $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
            if($reflection->isPublic()) {
                $method = self::get_params()[0];

                unset(self::$params[0]);
            }

        }

        return $method;

    }

}
