<?php
/**
 * Created by PhpStorm.
 * User: VHSoa
 * Date: 27/11/2017
 * Time: 09:28
 */

/*
 *
Plugin Name: Melhor Envio - Cotação
Plugin URI:  http://www.melhorenvio.com.br/
Description: Plugin que permite a cotação de fretes utilizando a API do Melhor Envio. Ainda é possível disponibilizar as informações da cotação de frete diretamente para o consumidor final.
Version:     2.0.0
Author:      Vítor Soares
Author URI:  https://vhsoares.github.io/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

if( !class_exists('WP_MelhorEnvio')):

    class WPMelhorEnvio
    {
        public function __construct()
        {

            include_once(ABSPATH.'wp-admin/includes/plugin.php');
            if( is_plugin_active('woocommerce/woocommerce.php')){
                add_action('plugins_loaded',array($this,'init'));
            }
        }

        public function init()
        {
            if(class_exists('WC_Integration')){
                //Incluindo a classe de integraçao
                include_once 'includes/wpmelhorenviointegration.php';
                //Registrando a integração
                add_filter('woocommerce_integrations',array($this, 'me_add_integration'));
                //Criando os links no Menu
                add_action("admin_menu", "me_addMenu");
                function me_addMenu(){
                    add_menu_page("Melhor Envio", "Melhor Envio", "administrator", "wpme_melhor-envio","pedidos", plugin_dir_url( __FILE__ )."data/mo.png");
                    add_submenu_page("wpme_melhor-envio","Melhor Envio - Pedidos", "Pedidos", "administrator", "wpme_melhor-envio-requests", "wpme_pedidos");
                    add_submenu_page("wpme_pedidos","Melhor Envio - Pedidos", "Pedidos", "administrator", "wpme_melhor-envio-request", "pedido");
                    add_submenu_page("wpme_pedidos","Melhor Envio - Relatório", "Relatório", "administrator", "wpme_melhor-envio-relato", "relatorio");
                    add_submenu_page("wpme_melhor-envio","Melhor Envio - Configurações do Plugin", "Configurações", "administrator", "wpme_melhor-envio-config", "wpme_config");
                    add_submenu_page("wpme_melhor-envio","Melhor Envio - Configurações da Conta", "Sua Conta Melhor Envio", "administrator", "wpme_melhor-envio-subscription", "wpme_cadastro");
                }
                function wpme_cadastro(){
                    include_once 'class/config.php';
                    include_once 'views/apikey.php';
                }
                function wpme_config(){
                    include_once 'class/config.php';
                    include_once 'views/address.php';
                }
                function wpme_pedidos(){
                    include_once 'class/orders.php';
                    include_once 'views/pedidos.php';
                }
                include_once 'class/shipping.php';
            }



            file_get_contents('php://input');
            include_once 'class/orders.php';
            add_action( 'wp_ajax_wpme_getCustomerTrackingAPI', 'wpme_getCustomerTrackingAPI' );
            add_action( 'wp_ajax_wpme_ticketAcquirementAPI', 'wpme_ticketAcquirementAPI' );
            add_action( 'wp_ajax_wpme_ticketPrintingAPI', 'wpme_ticketPrintingAPI' );
            add_action( 'wp_ajax_wpme_getCustomerCotacaoAPI', 'wpme_getCustomerCotacaoAPI' );

        }



        /**
         * Adiciona uma nova integração ao WooCommerce
         */
        public function me_add_integration(){

            $wpme_integrations[] = 'WPME_WPMelhorEnvioIntegration';
            return $wpme_integrations;
        }
    }


    $WPMelhorEnvioIntegration = new WPMelhorEnvio(__FILE__);

endif;