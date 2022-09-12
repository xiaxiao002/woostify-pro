<?php
/**
 * Woostify Ajax Product Search Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Index_Table' ) ) :

	/**
	 * Woostify Ajax Product Search Class
	 */
	class Woostify_Index_Table {

		const DB_VERSION        = '1.2';
		const DB_VERSION_OPTION = 'woostify_db_table';
		const DB_NAME           = 'woostify_product_index';
		const DB_TAX_NAME       = 'woostify_tax_index';
		const DB_SKU_INDEX      = 'woostify_sku_index';
		const DB_CATEGORIES     = 'woostify_category_index';
		const DB_TAGS           = 'woostify_tag_index';
		const DB_ATTRIBUTE      = 'woostify_attribute_index';

		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Total Product
		 *
		 * @var total_product
		 */
		public $total_product;

		/**
		 * Last Update
		 *
		 * @var update_time
		 */
		public $update_time;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'maybe_install' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( $this, 'install_data' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( $this, 'create_table_tax' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( $this, 'create_table' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( $this, 'sku_table' ) );
		}

		/**
		 * Install DB.
		 */
		public function maybe_install() {
			if ( get_site_option( self::DB_VERSION_OPTION ) != self::DB_VERSION ) { // phpcs:ignore
				$this->create_table();
				$this->create_table_tax();
				$this->table_sku();
				$this->create_table_category();
				$this->create_table_tag();
				$this->create_table_attribute();
			}
		}

		/**
		 * Create stoplist table.
		 */
		public function custom_stop_word() {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_name      = $wpdb->prefix . 'woostify_stopwords';
			$sql             = "CREATE TABLE $table_name(value VARCHAR(30)) ENGINE = INNODB"; //phpcs:ignore
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			$database = $wpdb->dbname;
			$path     = $wpdb->dbname . '/' . $table_name;

		}

		/**
		 * Create table product index.
		 */
		public function create_table() {

			global $wpdb;

			$table_name      = $wpdb->prefix . self::DB_NAME;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) UNSIGNED NOT NULL,
				name            TEXT NOT NULL,
				description     LONGTEXT NOT NULL,
				short_description LONGTEXT NOT NULL,
				sku             VARCHAR(100) NOT NULL,
				sku_variations  TEXT NOT NULL,
				attributes      LONGTEXT NOT NULL,
				meta            LONGTEXT NOT NULL,
				image           TEXT NOT NULL,
				url             TEXT NOT NULL,
				html_price      TEXT NOT NULL,
				price           DECIMAL(10,2) NOT NULL DEFAULT '0',
				max_price       DECIMAL(10,2) NOT NULL DEFAULT '0',
				average_rating  DECIMAL(3,2) NOT NULL DEFAULT '0',
				review_count    SMALLINT(5) NOT NULL DEFAULT '0',
				total_sales     SMALLINT(5) NOT NULL DEFAULT '0',
				lang            VARCHAR(5) NOT NULL,
				type            VARCHAR(255) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				status          VARCHAR(255) NOT NULL DEFAULT 'enable',
				transition      VARCHAR(255) NOT NULL DEFAULT 'publish',
				PRIMARY KEY    (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );
			$wpdb->set_sql_mode( array( 'ALLOW_INVALID_DATES' ) );
			$this->custom_stop_word();
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (name)" ); //phpcs:ignore
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (description)" ); //phpcs:ignore
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (short_description)" ); //phpcs:ignore
		}


		/**
		 * Create table product index.
		 */
		public function create_table_category() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_CATEGORIES;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) UNSIGNED NOT NULL,
				name            TEXT NOT NULL,
				url             TEXT NOT NULL,
				lang            VARCHAR(5) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY    (id)
			) $charset_collate;";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );
			$wpdb->set_sql_mode( array( 'ALLOW_INVALID_DATES' ) );
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (name)" ); //phpcs:ignore
		}

		/**
		 * Create table tags index.
		 */
		public function create_table_tag() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_TAGS;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) UNSIGNED NOT NULL,
				name            TEXT NOT NULL,
				url             TEXT NOT NULL,
				lang            VARCHAR(5) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY    (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );
			$wpdb->set_sql_mode( array( 'ALLOW_INVALID_DATES' ) );
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (name)" ); //phpcs:ignore
		}

		/**
		 * Create table tags index.
		 */
		public function create_table_attribute() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_ATTRIBUTE;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) UNSIGNED NOT NULL,
				name            TEXT NOT NULL,
				group_name           VARCHAR(255) NOT NULL,
				url             TEXT NOT NULL,
				lang            VARCHAR(5) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY    (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );
			$wpdb->set_sql_mode( array( 'ALLOW_INVALID_DATES' ) );
			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (name)" ); //phpcs:ignore
		}

		/**
		 * Create table tax.
		 */
		public function create_table_tax() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_TAX_NAME;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) NOT NULL AUTO_INCREMENT,
				tax_id            BIGINT(20) NOT NULL,
				product_id     BIGINT(20) NOT NULL,
				lang            VARCHAR(5) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY    (id)

			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );
		}

		/**
		 * Index data.
		 */
		public function install_data() {
			global $wpdb;
			$products      = self::get_total_product();
			$list_products = array_chunk( $products, 2000 );
			$table_name    = $wpdb->prefix . self::DB_NAME;
			$table_tax     = $wpdb->prefix . self::DB_TAX_NAME;
			$table_sku     = $wpdb->prefix . self::DB_SKU_INDEX;
			$this->create_category();
			$this->create_tags();
			$this->create_attribute();
			foreach ( $list_products[0] as $post ) {
				$this->create_product( $post, $table_name, $table_tax, $table_sku );
			}

		}

		/**
		 * Create Category.
		 */
		public function create_category() {
			global $wpdb;
			$categories     = $this->get_all_categories();
			$table_category = $wpdb->prefix . self::DB_CATEGORIES;
			$lang           = get_locale();
			if ( defined( 'ICL_SITEPRESS_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang = $this->get_lang_by_local( $lang );
			}

			foreach ( $categories as $category ) {
				$wpdb->insert( //phpcs:ignore
					$table_category,
					array(
						'id'           => $category->term_id,
						'name'         => $category->name,
						'url'          => get_term_link( $category->term_id, 'product_cat' ),
						'lang'         => $lang,
						'created_date' => current_time( 'mysql' ),
					)
				);
			}
		}

		/**
		 * Create Tags.
		 */
		public function create_tags() {
			global $wpdb;
			$tags      = $this->get_all_tags();
			$table_tag = $wpdb->prefix . self::DB_TAGS;
			$lang      = get_locale();
			if ( defined( 'ICL_SITEPRESS_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang = $this->get_lang_by_local( $lang );
			}
			foreach ( $tags as $tag ) {
				$wpdb->insert( //phpcs:ignore
					$table_tag,
					array(
						'id'           => $tag->term_id,
						'name'         => $tag->name,
						'url'          => get_term_link( $tag->term_id, 'product_tag' ),
						'lang'         => $lang,
						'created_date' => current_time( 'mysql' ),
					)
				);
			}
		}

		/**
		 * Create Atrribute.
		 */
		public function create_attribute() {
			global $wpdb;
			$attributes_tax = wc_get_attribute_taxonomy_labels();
			$table_attr     = $wpdb->prefix . self::DB_ATTRIBUTE;
			$lang           = get_locale();
			if ( defined( 'ICL_SITEPRESS_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
				$lang = $this->get_lang_by_local( $lang );
			}
			foreach ( $attributes_tax as $tax => $label ) {
				$attr_terms = get_terms( 'pa_' . $tax );
				foreach ( $attr_terms as $term ) {
					$wpdb->insert( //phpcs:ignore
						$table_attr,
						array(
							'id'           => $term->term_id,
							'name'         => $term->name,
							'group_name'   => $label,
							'url'          => get_term_link( $term->term_id, 'pa_' . $tax ),
							'lang'         => $lang,
							'created_date' => current_time( 'mysql' ),
						)
					);
				}
			}
		}

		/**
		 * Create Product.
		 *
		 * @param (int)    $post | Post.
		 * @param (string) $table_name | Table Product Index.
		 * @param (string) $table_tax | Table Tax Index.
		 * @param (string) $table_sku | Table Product Index.
		 */
		public function create_product( $post, $table_name, $table_tax, $table_sku ) {
			global $wpdb;
			global $woocommerce_wpml;
			$post_id = $post->ID;
			try {
				$product       = new \WC_Product( $post_id );
				$list_term     = array();
				$terms         = $product->get_category_ids();
				$max_price     = 0;
				$price         = self::get_price_default( $post_id );
				$list_currency = false;
				$lang          = get_locale();
				$status        = 'enable';
				$type          = $product->get_type();
				if ( 'catalog' == $product->get_catalog_visibility() || 'hidden' == $product->get_catalog_visibility() ) { //phpcs:ignore
					$status = 'disable';
				}
				if ( defined( 'ICL_SITEPRESS_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
					$lang = $post->language_code;
				}

				$tags = wp_get_post_terms( $post_id, 'product_tag' );

				if ( count( $tags ) > 0 ) {
					foreach ( $tags as $term ) {
						$term_id = $term->term_id; // Product tag Id.
						$wpdb->insert( //phpcs:ignore
							$table_tax,
							array(
								'tax_id'       => $term_id,
								'product_id'   => $post_id,
								'lang'         => $lang,
								'created_date' => current_time( 'mysql' ),
							)
						);
					}
				}

				foreach ( $terms as $term ) {
					$list_term[] = $term;
					$parentcats  = get_ancestors( $term, 'product_cat' );
					if ( ! empty( $parentcats ) ) {
						foreach ( $parentcats as $cat ) {
							$wpdb->insert( //phpcs:ignore
								$table_tax,
								array(
									'tax_id'       => $cat,
									'product_id'   => $post_id,
									'lang'         => $lang,
									'created_date' => current_time( 'mysql' ),
								)
							);
						}
					}
					$wpdb->insert( //phpcs:ignore
						$table_tax,
						array(
							'tax_id'       => $term,
							'product_id'   => $post_id,
							'lang'         => $lang,
							'created_date' => current_time( 'mysql' ),
						)
					);
				}

				$image     = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'thumbnail' );
				$image_src = $image ? $image[0] : wc_placeholder_img_src();
				$sku_array = array();
				if ( 'variable' == $type ) { //phpcs:ignore
					$max_price = $product->get_variation_regular_price( 'max' );
					$price     = self::get_price_variable_min( $post_id );
					foreach ( $product->get_visible_children( false ) as $child_id ) {
						$variation = wc_get_product( $child_id );
						if ( $variation && $variation->get_sku() ) {
							$sku_array[] = $variation->get_sku();
							$wpdb->insert( //phpcs:ignore
								$table_sku,
								array(
									'sku'          => $variation->get_sku(),
									'product_id'   => $post_id,
									'lang'         => $lang,
									'created_date' => current_time( 'mysql' ),
								)
							);
						}
					}
				}

				// Check WCML.
				if ( defined( 'ICL_SITEPRESS_VERSION' ) && defined( 'ICL_LANGUAGE_CODE' ) && $woocommerce_wpml && $woocommerce_wpml->multi_currency ) {
					$list_currency = $this->get_list_currency();

					if ( ! $this->check_lang( $lang ) && $list_currency[$lang]['rate'] != 0 ) { //phpcs:ignore
						$price = round( $price * $list_currency[$lang]['rate'], wc_get_price_decimals() ); //phpcs:ignore
						$max_price = round( $max_price * $list_currency[$lang]['rate'], wc_get_price_decimals() ); //phpcs:ignore
					}
				}

				$list_sku = implode( ',', $sku_array );
				$wpdb->insert( //phpcs:ignore
					$table_name,
					array(
						'id'                => $post_id,
						'name'              => get_the_title( $post_id ),
						'description'       => $product->get_description(),
						'short_description' => $product->get_short_description(),
						'sku'               => $product->get_sku(),
						'sku_variations'    => $list_sku,
						'image'             => $image_src,
						'url'               => get_permalink( $post_id ),
						'html_price'        => $product->get_price_html(),
						'price'             => $price,
						'max_price'         => $max_price,
						'type'              => $product->get_type(),
						'average_rating'    => $product->get_average_rating(),
						'review_count'      => $product->get_review_count(),
						'total_sales'       => $product->get_total_sales(),
						'lang'              => $lang,
						'status'            => $status,
						'transition'        => $product->get_status(),
						'created_date'      => current_time( 'mysql' ),
					)
				);
			} catch ( Exception $e ) {
				print_r( $e->getTraceAsString() ); //phpcs:ignore
			}
		}

		/**
		 * Get all category.
		 */
		public function get_all_categories() {
			$args = array(
				'taxonomy'     => 'product_cat',
				'hierarchical' => 1,
				'hide_empty'   => 0,
				'orderby'      => 'id',
				'order'        => 'ASC',
			);

			return get_categories( $args );
		}

		/**
		 * Get all category.
		 */
		public function get_all_tags() {
			$args = array(
				'taxonomy'     => 'product_tag',
				'hierarchical' => 1,
				'hide_empty'   => 0,
				'orderby'      => 'id',
				'order'        => 'ASC',
			);

			return get_terms( $args );
		}

		/**
		 * Create table sku.
		 */
		public function sku_table() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_SKU_INDEX;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = "CREATE TABLE $table_name (
				id         BIGINT(20) NOT NULL AUTO_INCREMENT,
				product_id     TEXT NOT NULL,
				sku             VARCHAR(100) NOT NULL,
				lang            VARCHAR(5) NOT NULL,
				created_date    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY    (id)
			) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

			add_option( self::DB_VERSION_OPTION, self::DB_VERSION );

			$wpdb->query( "ALTER TABLE $table_name ADD FULLTEXT (sku)" ); //phpcs:ignore
		}

		/**
		 * Get Total product index.
		 */
		public function get_total_product() {
			global $wpdb;

			$tb_posts = $wpdb->prefix . 'posts';
			$sql      = "SELECT * FROM $tb_posts as p";
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				$sql .= " JOIN {$wpdb->prefix}icl_translations as t ON p.ID = t.element_id";
			}

			$sql .= " WHERE p.post_type='product'";
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				$sql .= " AND t.element_type='post_product'";
			}

			$product = $wpdb->get_results( $sql ); //phpcs:ignore

			return $product;
		}

		/**
		 * Get number Total product index.
		 */
		public function total_product() {
			global $wpdb;
			$table_name = $wpdb->prefix . self::DB_NAME;
			if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) { //phpcs:ignore
				$sql  = "SELECT COUNT(id) FROM $table_name"; //phpcs:ignore
				$count = $wpdb->get_var( $sql ); //phpcs:ignore

				return $count;
			}
		}

		/**
		 * Last index time.
		 */
		public function last_index() {
			global $wpdb;
			$table_name      = $wpdb->prefix . self::DB_NAME;
			$charset_collate = $wpdb->get_charset_collate();
			if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) ) { //phpcs:ignore
				$sql  = "SELECT MAX(created_date) FROM $table_name"; //phpcs:ignore
				$time = $wpdb->get_var( $sql ); //phpcs:ignore

				return $time;
			}
		}

		/**
		 * Get price default when use dynamic.
		 *
		 * @param (int) $product_id | product id.
		 */
		public function get_price_default( $product_id ) {
			global $wpdb;
			$sql   = "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = $product_id AND meta_key ='_regular_price'"; //phpcs:ignore
			$price = $wpdb->get_results( $sql ); //phpcs:ignore

			if ( ! empty( $price ) ) {
				return $price[0]->meta_value;
			}
			return 0;
		}

		/**
		 * Get price default when use dynamic.
		 *
		 * @param (int) $product_id | product id.
		 */
		public function get_price_variable_max( $product_id ) {
			global $wpdb;
			$sql   = "SELECT MAX( tm.meta_value ) FROM {$wpdb->prefix}posts as tp LEFT JOIN {$wpdb->prefix}postmeta as tm ON tp.ID = tm.post_id WHERE tp.post_type = 'product_variation' AND tp.post_parent = $product_id AND tm.meta_key = '_regular_price' "; //phpcs:ignore
			$price = $wpdb->get_var( $sql ); //phpcs:ignore

			return $price;
		}

		/**
		 * Get price default when use dynamic.
		 *
		 * @param (int) $product_id | product id.
		 */
		public function get_price_variable_min( $product_id ) {
			global $wpdb;
			$sql   = "SELECT MIN( tm.meta_value ) FROM {$wpdb->prefix}posts as tp LEFT JOIN {$wpdb->prefix}postmeta as tm ON tp.ID = tm.post_id WHERE tp.post_type = 'product_variation' AND tp.post_parent = $product_id AND tm.meta_key = '_regular_price' "; //phpcs:ignore
			$price = $wpdb->get_var( $sql ); //phpcs:ignore

			if ( $price ) {
				return $price;
			}
			return 0;
		}


		/**
		 * Get get currencies.
		 */
		public function get_currencies() {
			global $woocommerce_wpml;
			if ( $woocommerce_wpml && $woocommerce_wpml->multi_currency ) {
				return $woocommerce_wpml->multi_currency->get_currencies( 'include_default = true' );
			}
			return false;
		}

		/**
		 * Get get list currencies.
		 */
		public function get_list_currency() {
			$currencies    = $this->get_currencies();
			$list_currency = array();
			if ( $currencies ) {

				foreach ( $currencies as $currency => $data ) {
					$code = '';
					$lang = $data['languages'];
					foreach ( $lang as $key => $value ) {
						if ( $value ) {
							$code = $key;
						}
					}
					$list_currency[ $code ] = array(
						'currency' => $currency,
						'rate'     => $data['rate'],
					);
				}
			}

			return $list_currency;
		}


		/**
		 * Get get lang active.
		 */
		public function get_lang_active() {
			global $wpdb;
			$sql   = "SELECT * FROM {$wpdb->prefix}icl_languages WHERE active = '1' "; //phpcs:ignore
			$lang = $wpdb->get_results( $sql ); //phpcs:ignore

			if ( $lang ) {
				return $lang[0];
			}

			return false;
		}

		/**
		 * Check list currencies.
		 *
		 * @param (int) $lang | languare.
		 */
		public function check_lang( $lang ) {
			$lang_active = $this->get_lang_active();

			if ( $lang_active->code == $lang ) { //phpcs:ignore
				return true;
			}

			return false;
		}

		/**
		 * Get lang by local code.
		 *
		 * @param (string) $local_code | languare.
		 */
		public function get_lang_by_local( $local_code ) {
			global $wpdb;
			$sql   = "SELECT * FROM {$wpdb->prefix}icl_languages WHERE default_locale = '$local_code' "; //phpcs:ignore
			$lang = $wpdb->get_results( $sql ); //phpcs:ignore

			if ( $lang ) {
				return $lang[0]->code;
			}

			return false;
		}

		/**
		 * Init.
		 */
		public function init() {
			register_activation_hook( WOOSTIFY_PRO_FILE, array( 'Woostify_Index_Table', 'install_data' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( 'Woostify_Index_Table', 'create_table' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( 'Woostify_Index_Table', 'create_table_tax' ) );
			register_activation_hook( WOOSTIFY_PRO_FILE, array( 'Woostify_Index_Table', 'sku_table' ) );
			add_action( 'plugins_loaded', array( 'Woostify_Index_Table', 'maybe_install' ) );
		}
	}

	Woostify_Index_Table::get_instance();

endif;
