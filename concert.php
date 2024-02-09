<?php
/*
Plugin Name: Plugin Woo Commerce Tickets
Description: Ajout des champs sur la création de produits
Version: 1.0
 */





$active_plugins = get_option('active_plugins');
if (in_array('woocommerce/woocommerce.php', $active_plugins)
    && in_array('advanced-custom-fields/acf.php', $active_plugins)) {
    add_action('acf/init', 'my_acf_init');
    add_action("save_post","event_save_form");
    add_action('init', 'infos_init_shortcode');
    add_action('init', 'private_init_shortcode');
    add_action('init', 'Plugin_FrontBack_enqueue_styles');
    add_action('init', 'Plugin_FrontBack_enqueue_scripts');



    function Plugin_FrontBack_enqueue_styles()
    {
        wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
    }



    function Plugin_FrontBack_enqueue_scripts()
    {
        wp_enqueue_script('script', plugins_url('js/script.js',
            __FILE__), array('jquery'), '', true);
    }


    function my_acf_init()
    {
        if (function_exists('acf_add_local_field_group')) {

            acf_add_local_field_group(array(
                    'key' => 'infos',
                    'title' => 'Infos',
                    'fields' => array(
                        array(
                            'key' => 'event_date',
                            'label' => 'Date de l\'événement',
                            'name' => 'event_date',
                            'type' => 'date_picker',
                            'instructions' => 'Saisissez votre date ici.',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'event_time',
                            'label' => 'Horaire',
                            'name' => 'event_time',
                            'type' => 'time_picker',
                            'instructions' => 'Saisissez votre heure ici.',
                            'required' => 1,

                        )
                    ,
                        array(
                            'key' => 'event_description',
                            'label' => 'Description',
                            'name' => 'event_description',
                            'type' => 'text',
                            'instructions' => 'Saisissez votre texte ici.',
                            'required' => 1,

                        )
                    ,
                        array(
                            'key' => 'event_adress',
                            'label' => 'Adresse',
                            'name' => 'event_adress',
                            'type' => 'text',
                            'instructions' => 'Saisissez votre adresse ici.',
                            'required' => 1,

                        )
                    ),
                    'location' => array(
                        array(
                            array(
                                'param' => 'post_type',
                                'operator' => '==',
                                'value' => 'product',
                            ),
                        ),
                    ),
                )
            );
            function event_save_form($post_id)
            {
                if (isset($_POST['event_date'])) {
                    update_post_meta(
                        $post_id,
                        'event_date',
                        $_POST['event_date']
                    );
                }
                if (isset($_POST['event_time'])) {
                    update_post_meta(
                        $post_id,
                        'event_time',
                        $_POST['event_time']
                    );
                }
                if (isset($_POST['event_description'])) {
                    update_post_meta(
                        $post_id,
                        'event_description',
                        $_POST['event_description']
                    );
                }
                if (isset($_POST['event_adress'])) {
                    update_post_meta(
                        $post_id,
                        'event_adress',
                        $_POST['event_adress']
                    );
                }


            }
        }

    }

    function infos_init_shortcode()
    {
        add_shortcode('infos', 'infos_do_shortcode');

    }




    function infos_do_shortcode($attrs)
    {
        $id = get_the_ID();
        $date_event = get_post_meta(
            $id,
            'event_date',
            true
        );
        $date = date('m-d-Y', strtotime($date_event));

        $time_event = get_post_meta(
            $id,
            'event_time',
            true
        );

        $time = date('H:i:s', strtotime($time_event));


        $description = get_post_meta(
            $id,
            'event_description',
            true
        );
        $exact_date = $date.' '.$time;
        $display_date = date('d F Y', strtotime($date));


        $end_time = strtotime("".$date." ".$time.""); // Countdown end time
        $current_time = time(); // Current timestamp
        $time_left = $end_time - $current_time; // Time remaining in seconds

        $days = floor($time_left / 86400); // 86400 seconds in a day
        $time_left = $time_left % 86400;

        $hours = floor($time_left / 3600); // 3600 seconds in an hour
        $time_left = $time_left % 3600;

        $minutes = floor($time_left / 60); // 60 seconds in a minute
        $seconds = $time_left % 60;



        $output = (
            '<div id="event_'.$id.'"  class="event-details" data-event-date="' . esc_attr($exact_date) . '">
                <p>Date de l\'événement: ' . $display_date . '</p> <br>
                <p>Heure de l\'événement: ' . $time . '</p> <br>
                <p>Description de l\'événement: ' . $description . '</p><br>
                <p id="countdown"></p>
            </div> ');
        return $output;
    }

    function private_init_shortcode()
    {
        add_shortcode('private', 'private_do_shortcode');

    }


    function private_do_shortcode($attrs){
        $id = get_the_ID();
        $content =  ('<p id="secret_adress">Vous devez acheter une place pour voir l\'adresse.</p>');
        $current_user= wp_get_current_user();
        $customer_email = $current_user->user_email;



        if (is_user_logged_in()) {
            if (wc_customer_bought_product($customer_email, $current_user->ID, $id)) {
                $address = get_post_meta(
                    $id,
                    'event_adress',
                    true
                );
                $content =  ('<p id="secret_adress">Adresse: ' . $address . '</p>');
                return $content;
            }
        }

        return $content;
    }
}

else{
    if(!in_array('woocommerce/woocommerce.php', $active_plugins)){
        echo( '<p style="text-align: center; color:red; font-weight: bolder">Installez le plugin Woocommerce</p>');
    }
    if (!in_array('advanced-custom-fields/acf.php', $active_plugins)){
        echo( '<p style="text-align: center; color:red; font-weight: bolder">Installez le plugin ACF</p>');
    }
}


