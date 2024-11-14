<?php
/**
 * Filter the required data from CPT
 */

namespace include\api;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class FilterApiData {

    public function __construct()
    {
       
    }

    //Prepare REST data fields for Banner & Block post types
    public function filter_cpt_api_data ($data, $post, $context) {
            
        if (is_wp_error ($data)) { return $data; } else {
            
            $data_r = array();
            $data_r['id'] = $data->data['id'];
            $data_r['title'] = $data->data['title']['rendered'];
            $data_r['slug'] = $data->data['slug'];
            $data_r['created'] = $data->data['date'];
            $data_r['modified'] = $data->data['modified'];
            $data_r['data'] = get_fields();
            
            // Check if 'locations' field exists before assigning it
            if (isset($data->data['locations'])) {
                $data_r['locations'] = $data->data['locations'];
            } else {
                $data_r['locations'] = null; 
            }
            
            
            //Nullable type fix
            $nullable_fields = array('image','image_medium', 'image_mobile');
            
            foreach($nullable_fields as $key) {
                
                if(isset($data_r['data'][$key]) && $data_r['data'][$key] == false) { $data_r['data'][$key] = NULL; }
                
            }
            
            return($data_r);
        }
    }

}
