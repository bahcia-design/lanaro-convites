<?php
class ControllerExtensionShippingCorreios extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('extension/shipping/correios');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_correios', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['required'])) {
			$data['error_warning'] = $this->error['required'];
		} else {
			$data['error_warning'] = '';
		}		

		$data['breadcrumbs'] = array();
   		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
   		);
		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
   		);
		
   		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/correios', 'user_token=' . $this->session->data['user_token'], true)
   		);
		
   		$data['action'] = $this->url->link('extension/shipping/correios', 'user_token=' . $this->session->data['user_token'], true);
		
   		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_correios_servicos'])) {
			$data['shipping_correios_servicos'] = $this->request->post['shipping_correios_servicos'];
		} else {
			$data['shipping_correios_servicos'] = $this->config->get('shipping_correios_servicos');
		}
		
		if(empty($data['shipping_correios_servicos'])) {
			$data['shipping_correios_servicos'] = array();
		}		

		if (isset($this->request->post['shipping_correios_status'])) {
			$data['shipping_correios_status'] = $this->request->post['shipping_correios_status'];
		} else {
			$data['shipping_correios_status'] = $this->config->get('shipping_correios_status');
		}
		
		if (isset($this->request->post['shipping_correios_tax_class_id'])) {
			$data['shipping_correios_tax_class_id'] = $this->request->post['shipping_correios_tax_class_id'];
		} else {
			$data['shipping_correios_tax_class_id'] = $this->config->get('shipping_correios_tax_class_id');
		}
		
		if (isset($this->request->post['shipping_correios_geo_zone_id'])) {
			$data['shipping_correios_geo_zone_id'] = $this->request->post['shipping_correios_geo_zone_id'];
		} else {
			$data['shipping_correios_geo_zone_id'] = $this->config->get('shipping_correios_geo_zone_id');
		}
		
		if (isset($this->request->post['shipping_correios_sort_order'])) {
			$data['shipping_correios_sort_order'] = $this->request->post['shipping_correios_sort_order'];
		} else {
			$data['shipping_correios_sort_order'] = $this->config->get('shipping_correios_sort_order');
		}
		
		$this->load->model('localisation/tax_class');
		
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/shipping/correios', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/correios')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!empty($this->request->post['shipping_correios_servicos'])) {
			foreach ($this->request->post['shipping_correios_servicos'] as $servico) {
				if ((utf8_strlen($servico['codigo']) == 0) || (utf8_strlen($servico['nome']) == 0) || (utf8_strlen($servico['postcode']) == 0) || (utf8_strlen($servico['max_declarado']) == 0) || (utf8_strlen($servico['min_declarado']) == 0) || (utf8_strlen($servico['max_soma_lados']) == 0) || (utf8_strlen($servico['min_soma_lados']) == 0) || (utf8_strlen($servico['max_lado']) == 0) || (utf8_strlen($servico['max_peso']) == 0)) {
					$this->error['required'] = $this->language->get('error_required');
					break;
				}
			}
		}		

		return !$this->error;
	}
	
	public function install() {
	    $this->uninstall();
		
		$serviços = '[{"codigo":"04014","nome":"SEDEX","a_cobrar":"0","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"10000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"30","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"0","total":"","prazo_adicional":"","adicional":""},{"codigo":"04065","nome":"SEDEX a Cobrar","a_cobrar":"1","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"10000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"30","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"1","total":"","prazo_adicional":"","adicional":""},{"codigo":"40215","nome":"SEDEX 10","a_cobrar":"0","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"10000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"10","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"0","total":"","prazo_adicional":"","adicional":""},{"codigo":"40169","nome":"SEDEX 12","a_cobrar":"0","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"10000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"10","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"0","total":"","prazo_adicional":"","adicional":""},{"codigo":"40290","nome":"SEDEX Hoje","a_cobrar":"0","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"10000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"10","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"0","total":"","prazo_adicional":"","adicional":""},{"codigo":"04510","nome":"PAC","a_cobrar":"0","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"3000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"30","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"0","total":"","prazo_adicional":"","adicional":""},{"codigo":"04707","nome":"PAC a Cobrar","a_cobrar":"1","postcode":"","contrato_codigo":"","contrato_senha":"","max_declarado":"3000","min_declarado":"19","max_soma_lados":"200","min_soma_lados":"29","max_lado":"105","max_peso":"30","mao_propria":"0","aviso_recebimento":"0","declarar_valor":"1","total":"","prazo_adicional":"","adicional":""}]';
	   
        $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET store_id = '0', `code` = 'shipping_correios', `key` =  'shipping_correios_servicos', `value` = '". $serviços . "', `serialized` = '1'");
	}

	public function uninstall() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'shipping_correios'");
	}		
}
?>
