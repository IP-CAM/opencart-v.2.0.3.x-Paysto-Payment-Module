<?php

class ControllerExtensionPaymentPaysto extends Controller
{
    private $error = array();

    /** @var array Devafult servers */
    private $defaultServers = array(
        '95.213.209.218',
        '95.213.209.219',
        '95.213.209.220',
        '95.213.209.221',
        '95.213.209.222'
    );

    public function index()
    {
        $this->load->language('extension/payment/paysto');
        $this->document->setTitle = $this->language->get('heading_title');
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('paysto', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
        }
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_card'] = $this->language->get('text_card');

        $data['entry_x_login'] = $this->language->get('entry_x_login');
        $data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_useOnlyList'] = $this->language->get('entry_useOnlyList');
        $data['entry_serversList'] = $this->language->get('entry_serversList');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_tax'] = $this->language->get('entry_tax');
        $data['entry_log'] = $this->language->get('entry_log');
        $data['entry_class_tax'] = $this->language->get('entry_class_tax');
        $data['entry_text_tax'] = $this->language->get('entry_text_tax');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['tab_general'] = $this->language->get('tab_general');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['x_login'])) {
            $data['error_x_login'] = $this->error['x_login'];
        } else {
            $data['error_x_login'] = '';
        }

        if (isset($this->error['secret_key'])) {
            $data['error_secret_key'] = $this->error['secret_key'];
        } else {
            $data['error_secret_key'] = '';
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = '';
        }

        if (isset($this->error['useOnlyList'])) {
            $data['error_useOnlyList'] = $this->error['useOnlyList'];
        } else {
            $data['error_useOnlyList'] = '';
        }

        if (isset($this->error['serversList'])) {
            $data['error_serversList'] = $this->error['serversList'];
        } else {
            $data['error_serversList'] = '';
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/paysto', 'token=' . $this->session->data['token'], true),
        );

        $data['action'] = $this->url->link('extension/payment/paysto', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);


        if (isset($this->request->post['paysto_x_login'])) {
            $data['paysto_x_login'] = $this->request->post['paysto_x_login'];
        } else {
            $data['paysto_x_login'] = $this->config->get('paysto_x_login');
        }

        if (isset($this->request->post['paysto_secret_key'])) {
            $data['paysto_secret_key'] = $this->request->post['paysto_secret_key'];
        } else {
            $data['paysto_secret_key'] = $this->config->get('paysto_secret_key');
        }

        if (isset($this->request->post['paysto_description'])) {
            $data['paysto_description'] = $this->request->post['paysto_description'];
        } else {
            $data['paysto_description'] = $this->config->get('paysto_description');
        }

        if (isset($this->request->post['paysto_useOnlyList'])) {
            $data['paysto_useOnlyList'] = $this->request->post['paysto_useOnlyList'];
        } else {
            $data['paysto_useOnlyList'] = $this->config->get('paysto_useOnlyList');
        }

        if (isset($this->request->post['paysto_serversList'])) {
            $data['paysto_serversList'] = $this->request->post['paysto_serversList'];
        } else {
            if (!$this->config->get('paysto_serversList')) {
                $data['paysto_serversList'] = implode("\n", $this->defaultServers);
            } else {
                $data['paysto_serversList'] = $this->config->get('paysto_serversList');
            }
        }

        if (isset($this->request->post['paysto_order_status_id'])) {
            $data['paysto_order_status_id'] = $this->request->post['paysto_order_status_id'];
        } else {
            $data['paysto_order_status_id'] = $this->config->get('paysto_order_status_id');
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['paysto_geo_zone_id'])) {
            $data['paysto_geo_zone_id'] = $this->request->post['paysto_geo_zone_id'];
        } else {
            $data['paysto_geo_zone_id'] = $this->config->get('paysto_geo_zone_id');
        }

        if (isset($this->request->post['paysto_log'])) {
            $data['paysto_log'] = $this->request->post['paysto_log'];
        } else {
            $data['paysto_log'] = $this->config->get('paysto_log');
        }

        if (isset($this->request->post['paysto_classes'])) {
            $data['paysto_classes'] = $this->request->post['paysto_classes'];
        } elseif ($this->config->get('paysto_classes')) {
            $data['paysto_classes'] = $this->config->get('paysto_classes');
        } else {
            $data['paysto_classes'] = array(
                array(
                    'paysto_nalog' => 1,
                    'paysto_tax_rule' => 1
                )
            );
        }

        $data['tax_rules'] = array(
            array(
                'id' => 'Y',
                'name' => 'With VAT'
            ),
            array(
                'id' => 'N',
                'name' => 'Without VAT'
            )
        );

        $this->load->model('localisation/tax_class');
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();


        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['paysto_status'])) {
            $data['paysto_status'] = $this->request->post['paysto_status'];
        } else {
            $data['paysto_status'] = $this->config->get('paysto_status');
        }

        if (isset($this->request->post['paysto_sort_order'])) {
            $data['paysto_sort_order'] = $this->request->post['paysto_sort_order'];
        } else {
            $data['paysto_sort_order'] = $this->config->get('paysto_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/paysto.tpl', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/paysto')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['paysto_x_login']) {
            $this->error['x_login'] = $this->language->get('error_x_login');
        }

        if (!$this->request->post['paysto_secret_key']) {
            $this->error['secret_key'] = $this->language->get('error_secret_key');
        }

        if (!$this->request->post['paysto_description']) {
            $this->error['description'] = $this->language->get('error_description');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
