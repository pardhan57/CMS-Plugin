<?php
/**
 * Initialize the plugin
 */
namespace include ;

/****
 * include required files
 *
 */
use include\admin\ShowTaxonomyID;
use include\admin\DashboardWidgets;
use include\api\BlockApiEnpoints;
use include\api\CustomPageCpt;
use include\api\FilterApiData;
use include\api\RestApiEndpoints;
use include\admin\AdminUsers;
use include\admin\CustomFieldSanitizer;
use include\api\GenerateToken;
use include\api\SecureApi;

// defined your class here
class Init
{

    private $block_rest_api_endpoints;
    private $rest_api_endpoints;
    private $filter_cpt_api_data;
    private $create_custom_page_endpoint;
    private $add_id_column;
    private $populate_id_column;
    private $remove_default_post_type;
    private $remove_default_post_type_menu_bar;
    private $dice_admin_theme_style;
    private $set_custom_admin_color_for_seo_editors;
    private $remove_default_dashboard_widgets;
    private $add_custom_cpt_widgets;
    private $custom_field_sanitizer;
    private $generate_token;
    private $register_rest_routes;


    /**
     * Initialize the required classes
     *
     * @return void
     */
    public function __construct()
    {
        $this->initialize_classes();
        $this->register_hooks();
    }

    /**
     * Initialize the required classes
     *
     * @return void
     */
    private function initialize_classes()
    {
        // $this->block_rest_api_endpoints = new BlockApiEnpoints();
        $this->rest_api_endpoints = new RestApiEndpoints();
        $this->filter_cpt_api_data = new FilterApiData();
        $this->create_custom_page_endpoint = new CustomPageCpt();
        $this->add_id_column = new ShowTaxonomyID();
        $this->populate_id_column = new ShowTaxonomyID();
        $this->remove_default_post_type= new AdminUsers();
        $this->remove_default_post_type_menu_bar = new AdminUsers();
        $this->dice_admin_theme_style = new AdminUsers();
        $this->set_custom_admin_color_for_seo_editors = new AdminUsers();
        $this->remove_default_dashboard_widgets = new DashboardWidgets();
        $this->add_custom_cpt_widgets = new DashboardWidgets();
        $this->custom_field_sanitizer = new CustomFieldSanitizer(); 

        // $this->generate_token = new GenerateToken();  // Initialize GenerateToken class
        $this->register_rest_routes = new GenerateToken();
        $this->verify_jwt_tokens = new SecureApi();


    }

    /**
     * Run all the necessary hooks and initializations.
     */
    public function run()
    {

    }

    /**
     * Register all the necessary hooks and initializations.
     *
     */
    private function register_hooks()
    {

        // var_dump("Tetsing");


        /**
         * Register REST API Endpoints
         */
        add_action('rest_api_init', array($this->rest_api_endpoints, 'register_endpoints'));

        /**
         * Register Custom Page Endpoints
         */

        add_action('rest_api_init', array($this->create_custom_page_endpoint, 'create_custom_page_endpoint'));


        add_action('rest_api_init', array($this->register_rest_routes, 'register_rest_routes') , 0, 3);

        add_filter('rest_authentication_errors', array($this->verify_jwt_tokens, 'verify_jwt_tokens'));

        // filter custom page routes

        add_filter('rest_url_prefix', array($this->create_custom_page_endpoint, 'change_rest_prefix'));

        // filter cpt routes
        // adding cpt data filter to clear the result response and removied the unnecessary fields from the response
        add_filter('rest_prepare_banner', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_block', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_bonus', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_user-bonus', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_jackpot', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_promotion', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_tournament', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);
        add_filter('rest_prepare_recent-winner', array($this->filter_cpt_api_data, 'filter_cpt_api_data'), 0, 3);

        //Show ID in Location Taxonomy
        add_action('manage_edit-location_columns', array($this->add_id_column, 'add_id_column'));

        // filter the data for admin section
        add_filter('manage_location_custom_column', array($this->populate_id_column, 'populate_id_column'), 10, 3);

        add_filter('manage_location_custom_column', array($this->populate_id_column, 'populate_id_column'), 10, 3);
        // Remove widgets in dashboard
        add_action('wp_dashboard_setup', array ($this->remove_default_dashboard_widgets, 'remove_default_dashboard_widgets'));
        //Add Widgets in dashboard
        add_action( 'wp_dashboard_setup', array( $this->add_custom_cpt_widgets, 'add_custom_cpt_widgets') );

        // Remove Page and Post from wp-admin
        add_action( 'admin_menu', array($this->remove_default_post_type, 'remove_default_post_type'));
        add_action( 'admin_bar_menu', array($this->remove_default_post_type_menu_bar, 'remove_default_post_type_menu_bar'), 999 );

        add_action('init', array($this->dice_admin_theme_style, 'dice_admin_theme_style'), 0 ,3);

        add_action('init', array($this->set_custom_admin_color_for_seo_editors, 'set_custom_admin_color_for_seo_editors'));

         // Register hooks for custom field sanitization
         add_action('save_post', array($this->custom_field_sanitizer, 'save_custom_repeater_fields'));
         add_filter('acf/update_value/type=wysiwyg', array($this->custom_field_sanitizer, 'sanitize_acf_wysiwyg_content'), 10, 3);

         // Register GenerateToken REST routes
        // $this->generate_token->register_rest_routes();
    }

    
}
