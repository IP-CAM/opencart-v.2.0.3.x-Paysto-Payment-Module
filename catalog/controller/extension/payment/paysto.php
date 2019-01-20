<?php

/**
 * Котроллер оплаты
 * Class ControllerExtensionPaymentPaymaster
 *
 */
class ControllerExtensionPaymentPaysto extends Controller
{
    const STATUS_TAX_OFF = 'N';
    const MAX_POS_IN_CHECK = 100;
    const BEGIN_POS_IN_CHECK = 0;


    public function __construct($arg)
    {
        parent::__construct($arg);
        // Set payment servers from Paysto
        $this->PaystoServers = preg_split('/\r\n|[\r\n]/', $this->config->get('paysto_serversList'));
    }

    /**
     * Generation payment form
     *
     * @return mixed
     */
    public function index()
    {
        // Set null value for products list
        $x_line_item = '';
        //Set pos for 0
        $pos = 0;

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['button_back'] = $this->language->get('button_back');
        $data['action'] = 'https://paysto.com/ru/pay/AuthorizeNet';

        $this->load->language('extension/payment/paysto');
        $this->load->model('account/order');


        $data['pos'] = self::BEGIN_POS_IN_CHECK;
        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $amount = number_format($order['total'], 2, ".", "");
        $currency = strtoupper($order['currency_code']);
        $order_id = $this->session->data['order_id'];
        $now = time();

        $data['x_login'] = $this->config->get('paysto_x_login');
        $data['x_email'] = $order['email'];
        $data['x_fp_sequence'] = $order_id;
        $data['x_invoice_num'] = $order_id;
        $data['x_amount'] = $amount;
        $data['x_currency_code'] = $currency;
        $data['x_fp_timestamp'] = $now;
        $data['x_description'] = $this->config->get('paysto_description') . ' ' . $order_id;
        $data['x_fp_hash'] = $this->get_x_fp_hash($this->config->get('paysto_x_login'), $order_id,
            $now, $amount, $currency);
        $data['x_relay_response'] = 'TRUE';
        $data['x_relay_url'] = $this->url->link('extension/payment/paysto/callback', false);

        $order_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);

        //product
        if ($order_products) {
            foreach ($order_products as $pos => $order_product) {
                $lineArr = array();
                $lineArr[] = '№' . $pos;
                $lineArr[] = substr($order_product['model'], 0, 30);
                $lineArr[] = substr($order_product['name'], 0, 254);
                $lineArr[] = substr($order_product['quantity'], 0, 254);
                $lineArr[] = number_format($order_product['price'], 2, '.',
                    '');
                $lineArr[] = $this->config->get('tax_status') ? $this->getTax($order_product['product_id']) : self::STATUS_TAX_OFF;
                $x_line_item .= implode('<|>', $lineArr) . "0<|>\n";
            }
        }

        $order_totals = $this->model_account_order->getOrderTotals($this->session->data['order_id']);

        $services = [
            'shipping',
            'tax',
            'low_order_fee',
            'coupon'
        ];

        //service
        $pos++;

        foreach ($order_totals as $service) {
            if (in_array($service['code'], $services) && ($service['value'] > 0)) {
                $lineArr = array();
                $lineArr[] = '№' . $pos;
                $lineArr[] = substr($service['code'], 0, 30);
                $lineArr[] = substr($service['title'], 0, 254);
                $lineArr[] = '1';
                $lineArr[] = number_format($service['value'], 2, '.',
                    '');
                $lineArr[] = self::STATUS_TAX_OFF;
                $x_line_item .= implode('<|>', $lineArr) . "0<|>\n";
                $pos++;
            }
        }

        $data['x_line_item'] = $x_line_item;

        if ($pos > self::MAX_POS_IN_CHECK + 1) {
            $data['error_warning'] = $this->language->get('error_max_pos');
        }


        return $this->load->view('extension/payment/paysto', $data);
    }


    /**
     * Payment fail perform action
     *
     * @return bool
     */
    public function fail()
    {
        $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        return true;
    }

    /**
     * Payment success perform action
     *
     * @return bool
     */
    public function success()
    {

        $order_id = $this->request->post["x_invoice_num"];
        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($order_id);

        if ((int)$order["order_status_id"] == (int)$this->config->get('paysto_order_status_id')) {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paysto_order_status_id'),
                'Paysto', true);
            $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
            return true;
        }

        return $this->fail();
    }


    /**
     * Main calback function
     */
    public function callback()
    {

        if (!isset($this->request->post)) {
            exit();
        }

        $order_id = $this->request->post["x_invoice_num"];
        $x_trans_id = $this->request->post["x_trans_id"];
        $x_response_code = $this->request->post["x_response_code"];
        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($order_id);
        $amount = number_format($order['total'], 2, '.', '');
        $x_login = $this->config->get('paysto_x_login');

        if ($order['order_status_id'] == $this->config->get('paysto_order_status_id')) {
            try {
                $this->session->data['paysto_pay'] = "success";
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paysto_order_status_id'),
                    'Paysto', true);
                $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
                return true;
            } catch (\Exception $exception) {

            }
        }

        $HTTP_X_FORWARDED_FOR = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '127.0.0.1';
        $HTTP_CF_CONNECTING_IP = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : '127.0.0.1';
        $HTTP_X_REAL_IP = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '127.0.0.1';
        $REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $GEOIP_ADDR = isset($_SERVER['GEOIP_ADDR']) ? $_SERVER['GEOIP_ADDR'] : '127.0.0.1';

        if ($this->config->get('paysto_useOnlyList') &&
            ((!in_array($HTTP_X_FORWARDED_FOR, $this->PaystoServers)) &&
                (!in_array($HTTP_CF_CONNECTING_IP, $this->PaystoServers)) &&
                (!in_array($HTTP_X_REAL_IP, $this->PaystoServers)) &&
                (!in_array($REMOTE_ADDR, $this->PaystoServers)) &&
                (!in_array($GEOIP_ADDR, $this->PaystoServers)))) {
            if ($this->session->data['paysto_pay'] = 'success') {
                $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
                return true;
            } else {
                try {
                    session_destroy();
                } catch (\Exception $exception) {
                    $this->log->write($exception->getMessage());
                }
            }
        }

        if (isset($this->request->post['x_MD5_Hash'])) {
            $x_MD5_Hash = $this->request->post['x_MD5_Hash'];
            if ($x_MD5_Hash == $this->get_x_MD5_Hash($x_login, $x_trans_id, $amount) && $x_response_code == 1) {
                if ($order['order_status_id'] == 0) {
                    try {
                        $this->model_checkout_order->addOrderHistory($order_id,
                            $this->config->get('paysto_order_status_id'), 'Order was paid in Paysto payment gateway');
                    } catch
                    (\Exception $exception) {
                        $this->log->write($exception->getMessage());
                        exit();
                    }
                    exit();
                }
                if ($order['order_status_id'] != $this->config->get('paysto_order_status_id') || $x_response_code != 1) {
                    try {
                        $this->model_checkout_order->addOrderHistory($order_id,
                            $this->config->get('paysto_order_status_id'), 'Paysto', true);
                    } catch (\Exception $exception) {
                        $this->log->write($exception->getMessage());
                        exit();
                    }
                    exit();
                }
            } else {
                $this->log->write("Paysto sign is not correct or other error happen!");
            }
        }

    }


    /**
     * Return hash md5 HMAC
     *
     * @param $x_login
     * @param $x_fp_sequence
     * @param $x_fp_timestamp
     * @param $x_amount
     * @param $x_currency_code
     * @return false|string
     */
    private function get_x_fp_hash($x_login, $x_fp_sequence, $x_fp_timestamp, $x_amount, $x_currency_code)
    {
        $arr = [$x_login, $x_fp_sequence, $x_fp_timestamp, $x_amount, $x_currency_code];
        $str = implode('^', $arr);
        return hash_hmac('md5', $str, $this->config->get('paysto_secret_key'));
    }


    /**
     * Return sign with MD5 algoritm
     *
     * @param $x_login
     * @param $x_trans_id
     * @param $x_amount
     * @return string
     */
    private function get_x_MD5_Hash($x_login, $x_trans_id, $x_amount)
    {
        return md5($this->config->get('paysto_secret_key') . $x_login . $x_trans_id . $x_amount);
    }


    /**
     * Logger
     *
     * @param $method
     * @param array $data
     * @param string $text
     * @return bool
     */
    protected function createLog($method, $data = [], $text = '')
    {
        if ($this->config->get('paysto_log')) {
            $this->log->write('---------PAYSTO START LOG---------');
            $this->log->write('---Callback method: ' . $method . '---');
            $this->log->write('---Description: ' . $text . '---');
            $this->log->write($data);
            $this->log->write('---------PAYSTO END LOG----------');
        }
        return true;
    }

    /**
     * Get tax rate
     *
     * @param $product_id
     * @return mixed
     */
    protected function getTax($product_id)
    {
        $this->load->model('catalog/product');
        $product_info = $this->model_catalog_product->getProduct($product_id);
        $tax_rule_id = 3;

        foreach ($this->config->get('paysto_classes') as $i => $tax_rule) {
            if ($tax_rule['paysto_nalog'] == $product_info['tax_class_id']) {
                $tax_rule_id = $tax_rule['paysto_tax_rule'];
            }
        }

        $tax_rules = [
            [
                'id' => 0,
                'name' => 'Without VAT'
            ],
            [
                'id' => 1,
                'name' => 'With VAT'
            ]
        ];

        return $tax_rules[$tax_rule_id]['name'];

    }

}
