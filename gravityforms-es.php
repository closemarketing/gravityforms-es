<?php
/*
Plugin Name: Gravity Forms (Spanish)
Plugin URI: http://www.closemarketing.es/servicios/wordpress-plugins/gravity-forms-es/
Description: Extends the Gravity Forms plugin and add-ons with the Spanish language

Version: 2.1
Requires at least: 4.0

Author: Closemarketing
Author URI: http://www.closemarketing.es/

Text Domain: gravityforms-es
Domain Path: /languages/

License: GPL
*/

class GravityFormsESPlugin {
	/**
	 * The plugin file
	 *
	 * @var string
	 */
	private $file;

	////////////////////////////////////////////////////////////

	/**
	 * The current langauge
	 *
	 * @var string
	 */
	private $language;

	/**
	 * Flag for the dutch langauge, true if current langauge is dutch, false otherwise
	 *
	 * @var boolean
	 */
	private $is_spanish;

	////////////////////////////////////////////////////////////

	/**
	 * Construct and intialize
	 */
	public function __construct( $file ) {
		$this->file = $file;
		// Priority is set to 8, beceasu the Signature Add-On is using priority 9
		add_action( 'init', array( $this, 'init' ), 8 );

		add_filter( 'gform_currencies',             array( $this, 'gform_currencies' ) );
		add_filter( 'gform_address_types',          array( $this, 'gform_address_types' ) );
		add_filter( 'gform_address_display_format', array( $this, 'gform_address_display_format' ) );

		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );

		/*
		 * @since Gravity Forms v1.6.12
		 *
		 * Gravity Forms don't execute the load_plugin_textdomain() in the 'init'
		 * action, therefor we have to make sure this plugin will load first
		 *
		 * @see http://stv.whtly.com/2011/09/03/forcing-a-wordpress-plugin-to-be-loaded-before-all-other-plugins/
		 */
		add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Activated plugin
	 */
	public function activated_plugin() {
		$path = str_replace( WP_PLUGIN_DIR . '/', '', $this->file );

		if ( $plugins = get_option( 'active_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );

				update_option( 'active_plugins', $plugins );
			}
		}

		if ( $plugins = get_site_option( 'active_sitewide_plugins' ) ) {
			if ( $key = array_search( $path, $plugins ) ) {
				array_splice( $plugins, $key, 1 );
				array_unshift( $plugins, $path );

				update_site_option( 'active_sitewide_plugins', $plugins );
			}
		}
	}

	////////////////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {
		$rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Determine language
		if ( $this->language == null ) {
			$this->language = get_option( 'WPLANG', WPLANG );
			$this->is_spanish = ( $this->language == 'es' || $this->language == 'es_ES' );
		}

		//Load Gravity Forms Strings to Wordpress translate ORG
		require( 'languages/gravityforms.php' );

		$arraydomains = array(
			array( // Gravity Forms Aweber.
				'plugin_name'     => 'aweber',
				'dir_plugin_name' => 'gravityformsaweber',
				'language_domain' => 'gravityformsaweber',
			),
			array( // Gravity Forms Coupons.
				'plugin_name'     => 'coupons',
				'dir_plugin_name' => 'gravityformscoupons',
				'language_domain' => 'gravityformscoupons',
			),
			array( // Gravity Forms Campaign Monitor.
				'plugin_name'     => 'campaignmonitor',
				'dir_plugin_name' => 'gravityformscampaignmonitor',
				'language_domain' => 'gravityformscampaignmonitor',
			),
			array( // Gravity Forms FreshBooks.
				'plugin_name'     => 'freshbooks',
				'dir_plugin_name' => 'gravityformsfreshbooks',
				'language_domain' => 'gravityformsfreshbooks',
			),
			array( // Gravity Forms MailChimp.
				'plugin_name'     => 'mailchimp',
				'dir_plugin_name' => 'gravityformsmailchimp',
				'language_domain' => 'gravityformsmailchimp',
			),
			array( // Gravity Forms Partial Entries.
				'plugin_name'     => 'partialentries',
				'dir_plugin_name' => 'gravityformspartialentries',
				'language_domain' => 'gravityformspartialentries',
			),
			array( // Gravity Forms Paypal.
				'plugin_name'     => 'paypal',
				'dir_plugin_name' => 'gravityformspaypal',
				'language_domain' => 'gravityformspaypal',
			),
			array( // Gravity Forms PayPal Payments PRO.
				'plugin_name'     => 'paypalpaymentspro',
				'dir_plugin_name' => 'gravityformspaypalpaymentspro',
				'language_domain' => 'gravityformspaypalpaymentspro',
			),
			array( // Gravity Forms Polls.
				'plugin_name'     => 'polls',
				'dir_plugin_name' => 'gravityformspolls',
				'language_domain' => 'gravityformspolls',
			),
			array( // Gravity Forms Quiz.
				'plugin_name'     => 'quiz',
				'dir_plugin_name' => 'gravityformsquiz',
				'language_domain' => 'gravityformsquiz',
			),
			array( // Gravity Forms Signature.
				'plugin_name'     => 'signature',
				'dir_plugin_name' => 'gravityformssignature',
				'language_domain' => 'gravityformssignature',
			),
			array( // Gravity Forms Slack.
				'plugin_name'     => 'slack',
				'dir_plugin_name' => 'gravityformsslack',
				'language_domain' => 'gravityformsslack',
			),
			array( // Gravity Forms Survey.
				'plugin_name'     => 'survey',
				'dir_plugin_name' => 'gravityformssurvey',
				'language_domain' => 'gravityformssurvey',
			),
			array( // Gravity Forms User Registration.
				'plugin_name'     => 'userregistration',
				'dir_plugin_name' => 'gravityformsuserregistration',
				'language_domain' => 'gravityformsuserregistration',
			),
			array( // Gravity Forms Zapier.
				'plugin_name'     => 'zapier',
				'dir_plugin_name' => 'gravityformszapier',
				'language_domain' => 'gravityformszapier',
			),
		);

		foreach ( $arraydomains as $domain ) {
			// Detects if plugin is active. Then its "textdomain" is loaded.
			if ( isset( $domain['dir_plugin_name'] ) ) {
				$directory_plugin = $domain['dir_plugin_name'];
			} else {
				$directory_plugin = $domain['plugin_name'];
			}
			$plugin = $directory_plugin . '/' . $domain['plugin_name'] . '.php';

			if ( is_plugin_active( $plugin ) ) {
				load_plugin_textdomain( $domain['language_domain'], false, $rel_path );
			}
		}

		// Load plugin text domain - Translate WordPress ORG
		$domain = 'gravityforms-es';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( 'gravityforms', trailingslashit( WP_LANG_DIR ) . 'plugins/'. $domain . '-' . $locale . '.mo' );
	}

	////////////////////////////////////////////////////////////

	/**
	 * Gravity Forms translate datepicker
	 */
	public function wp_print_scripts() {
		if ( $this->is_spanish ) {
			/**
			 * gforms_ui_datepicker » @since ?
			 * gforms_datepicker » @since Gravity Forms 1.7.5
			 */
			foreach ( array( 'gforms_ui_datepicker', 'gforms_datepicker' ) as $script_datepicker ) {
				if ( wp_script_is( $script_datepicker ) ) {
					// @see http://code.google.com/p/jquery-ui/source/browse/trunk/ui/i18n/jquery.ui.datepicker-nl.js
					// @see https://github.com/jquery/jquery-ui/blob/master/ui/i18n/jquery.ui.datepicker-nl.js
					$src = plugins_url( 'js/jquery.ui.datepicker-es.js', $this->file );

					wp_enqueue_script( 'gforms_ui_datepicker_es', $src, array( $script_datepicker ), false, true );
				}
			}
		}
	}

	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////

	/**
	 * Update currency
	 *
	 * @param array $currencies
	 */
	public function gform_currencies( $currencies ) {
		$currencies['EUR'] = array(
			'name'               => __( 'Euro', 'gfes' ),
			'symbol_left'        => '€',
			'symbol_right'       => '',
			'symbol_padding'     => ' ',
			'thousand_separator' => '.',
			'decimal_separator'  => ',',
			'decimals'           => 2
		);

		return $currencies;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Address types
	 *
	 * @param array $address_types
	 */
	public function gform_address_types( $address_types ) {
		// @see http://www.gravityhelp.com/forums/topic/add-custom-field-to-address-field-set
		$address_types['es'] = array(
			'label'       => __( 'Spain', 'gfes' ),
			'country'     => __( 'Spain', 'gfes' ),
			'zip_label'   => __( 'Postal Code', 'gfes' ),
			'state_label' => __( 'Province', 'gfes' ),
			'states'      => array_merge( array( '' ), self::get_spanish_provinces() )
		);

		return $address_types;
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get list of Spanish provinces
	 *
	 * @return array
	 */
	public static function get_spanish_provinces() {
		return array(
			__( 'Albacete', 'gfes' ),
			__( 'Alicante', 'gfes' ),
			__( 'Almería', 'gfes' ),
			__( 'Asturias', 'gfes' ),
			__( 'Ávila', 'gfes' ),
			__( 'Badajoz', 'gfes' ),
			__( 'Barcelona', 'gfes' ),
			__( 'Burgos', 'gfes' ),
			__( 'Cáceres', 'gfes' ),
			__( 'Cádiz', 'gfes' ),
			__( 'Cantabria', 'gfes' ),
			__( 'Castellón', 'gfes' ),
			__( 'Ceuta', 'gfes' ),
			__( 'Ciudad Real', 'gfes' ),
			__( 'Córdoba', 'gfes' ),
			__( 'Coruña (La)', 'gfes' ),
			__( 'Cuenca', 'gfes' ),
			__( 'Girona', 'gfes' ),
			__( 'Granada', 'gfes' ),
			__( 'Guadalajara', 'gfes' ),
			__( 'Guipuzcoa', 'gfes' ),
			__( 'Huelva', 'gfes' ),
			__( 'Huesca', 'gfes' ),
			__( 'Islas Baleares', 'gfes' ),
			__( 'Jaén', 'gfes' ),
			__( 'León', 'gfes' ),
			__( 'Lleida', 'gfes' ),
			__( 'Lugo', 'gfes' ),
			__( 'Madrid', 'gfes' ),
			__( 'Málaga', 'gfes' ),
			__( 'Melilla', 'gfes' ),
			__( 'Murcia', 'gfes' ),
			__( 'Navarra', 'gfes' ),
			__( 'Orense', 'gfes' ),
			__( 'Palencia', 'gfes' ),
			__( 'Palmas (Las)', 'gfes' ),
			__( 'Pontevedra', 'gfes' ),
			__( 'provincia', 'gfes' ),
			__( 'Rioja (La)', 'gfes' ),
			__( 'Salamanca', 'gfes' ),
			__( 'Santa Cruz de Tenerife', 'gfes' ),
			__( 'Segovia', 'gfes' ),
			__( 'Sevilla', 'gfes' ),
			__( 'Soria', 'gfes' ),
			__( 'Tarragona', 'gfes' ),
			__( 'Teruel', 'gfes' ),
			__( 'Toledo', 'gfes' ),
			__( 'Valencia', 'gfes' ),
			__( 'Valladolid', 'gfes' ),
			__( 'Vizcaya', 'gfes' ),
			__( 'Zamora', 'gfes' ),
			__( 'Zaragoza', 'gfes' )
		);
	}

	////////////////////////////////////////////////////////////

	/**
	 * Address display format
	 *
	 * @see http://www.gravityhelp.com/documentation/page/Gform_address_display_format
	 * @param array $address_types
	 */
	public function gform_address_display_format( $format ) {
		if ( $this->is_spanish ) {
			return 'zip_before_city';
		}

		return $format;
	}
}

global $gravityforms_es_plugin;

$gravityforms_es_plugin = new GravityFormsESPlugin( __FILE__ );
