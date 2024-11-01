<?php
/*
Plugin Name: Shdlr Integrate
Plugin URI: http://www.shdlr.com/
Description: A plugin to integrate 'shdlr.com' conference schedule in your wordpress site
Version: 1.0
Author: Aditya Jain
Author URI: http://shdlr.com/blog/2013/10/17/wordpress-plugin-shdlr-integrate/
*/

/**
 * Copyright (c) `2013` shdlr.com. 
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

class ShdlrIntegrate {

	protected $options;

	protected $processing;

	protected $public_msg;

	protected $url;

	public function __construct(){

		add_action('admin_menu', array($this, 'admin_menu'));

        add_action('admin_enqueue_scripts', array($this, 'admin_enqueues')); // Plugin hook for adding CSS and JS files required for this plugin

        // Admin init
        add_action('admin_init', array($this, 'admin_init'));

        add_action('wp_enqueue_scripts', array($this, 'shdlr_scripts'));

        add_action('wp_ajax_validate_conf', array($this, 'validate_conf')); // Hook to implement AJAX request

        add_shortcode('shdlr', array($this, 'process_shortcode'));

        $this->options = get_option( 'shdlr_integrate');

        $this->public_message = 'No content to be displayed';

        $this->url = 'shdlr.com';
	}

	public function admin_menu(){
		add_options_page('Shdlr Integrate Settings','Shdlr Integrate', 'manage_options', 'shdlr_integrate', array($this, 'admin_interface'));
	}

	public function admin_enqueues($hook){
		if($hook != 'settings_page_shdlr_integrate') return;
		wp_enqueue_script('jQuery');
		wp_enqueue_script( 'admin', plugins_url( '/js/admin.js', __FILE__ ));
		wp_enqueue_style( 'shdlr_admin_css', plugins_url( '/css/shdlr_admin.css', __FILE__ ));
		wp_enqueue_style( 'ad_css', plugins_url( '/css/ad_style.css', __FILE__ ));
	}

	public function admin_init(){
	}

	public function validate_conf(){
		update_option('shdlr_integrate', array('token' => $_POST['id']));
		exit;
	}

	public function admin_interface(){
		?>
		<div>
        <div style="margin-right:260px;">
            <div style='float:left; width: 100%'>
		<div class="wrap">
			<div id="message" class="updated fade" style="display:none"></div>
			<div id="icon-options-general" class="icon32"><br></div>
            <h2><?php printf(__('Shdlr Integrate - By %s', 'shdlr_integrate'), '<a href="http://www.shdlr.com" target="_blank">shdlr</a>'); ?></h2>
                <form action="" method="post">
                	</br><b>Token :</b>
                	<input type='text' id='token' name='token' value="<?php echo $this->options['token']; ?>" style='width:300px;'/>
                	<input type='button' id='validate' class='button button-primary' value='Save' />
                	
                	<div id='conf_detail' style='display:none;'></br>
                		<h2>Conference : <b id='conf_name'></b></h2>
                		
                		
                		<div id='conf_list'>
                			
                			
                			<b>Schedule type : </b>
                			<select id='conf_type'>
                				<option value='0'>Grid Inline</option>
                				<option value='1'>Grid Pop-up</option>
                				<option value='2'>List Inline</option>
                				<option value='3'>List Pop-up</option>
                				<option value='4'>Simple Grid Inline</option>
                				<option value='5'>Simple Grid Pop-up</option>
                				<option value='6'>Talks Inline</option>
                				<option value='7'>Talks Pop-up</option>
                			</select>&nbsp;&nbsp;
                			<h3>Shortcode : <code id='shdlr_shortcode'></code></h3>
                			<p class='description'>Choose options for your schedule and paste the shortcode  above in a page to publish your conference schedule.</br>We recommend you use a full width template for best view.</br></br>Tip : To try integration of demo conference schedule use token <b>demo-token</b> </p>
                			
                		</div>
                	</div>
                	<div id='preloader' style='display:none;'>
                		<img src="<?php echo plugins_url( 'img/preloader.GIF', __FILE__ ) ?>" style="margin: 50px 150px;;">
                	</div>
                </form>
        </div>
   </div>
        <div class="codecanyon_plugin_advertisement">
            <div>
                <div class="codecanyon_plugin_logo">
                	<a href="http://shdlr.com" target="__blank">
                    	<img align="middle" src=" <?php echo plugins_url( 'img/shdlr_logo.png', __FILE__  ); ?>" >
                	</a>
                </div>
            </div>
            <div class="codecanyon_plugin_features">
                <ul>
                    <li>Create a conference schedule at <a href="http://shdlr.com" target="__blank">shdlr.com</a></li> 
                    <li>Get your token from</br><b>Shdlr Admin Panel > Customize Website > Embed > WordPress plugin</b></li>
                    <li>Use token to integerate your conference schedule</li>
                    <li>Incase you need futher assistance feel free to contact us</li>
                </ul>
            </div>
            <div class="codecanyon_plugin_buy_now">
                <a href="mailto:support@shdlr.com" target=""><input type="button" value="Contact Us" class="button-primary"/></a>
            </div>
        </div>
        </div>
    </div>
<?php
	}

	public function sanitize_options(){

	}

	public function options_section(){

	}

	public function process_shortcode($atts){
		wp_enqueue_style( 'shdlr_view', plugins_url( '/css/view.css', __FILE__ ));

		if(empty($this->options['token'])){
			if(current_user_can('manage_options')){
				echo "<div class='shdlr_info'><a href=".admin_url( $path = 'options-general.php?page=shdlr_integrate', $scheme = 'admin' ).">Please enter a valid token</a></div>";
				return;
			} else {
				echo "<div class='shdlr_info'>".$this->public_message."</div>";
				return;
			}
			
		} elseif($this->options['token'] == 'demo-token' && $atts['conf_id'] == 'demo'){
			$request_data = (object) array('success' => true, 'data' => '1');
		} else {
			if(empty($atts['conf_id'])){
				if(current_user_can('edit_post')){
					edit_post_link( 'Please enter a valid conference id', '<div class="shdlr_info">', '</div>');
					return;
				} else {
					echo "<div class='shdlr_info'>".$this->public_message."</div>";
					return;
				}
			} else {
				$request_url = "http://".$atts['conf_id'].".".$this->url."/conferences/request_check/".$this->options['token'];
				$request_data = json_decode(wp_remote_retrieve_body(wp_remote_request(  $request_url, array('timeout' => 50))));
			}
		}

		if(!$request_data->success){
			if(current_user_can('manage_options')){
				if(!$request_data->message){
					echo '<div class="shdlr_info">Unable to fetch data from shdlr.com</div>';
				}else{
					echo '<div class="shdlr_info">'.$request_data->message.'</div>';
				}
			} else {
				echo '<div class="shdlr_info">'.$this->public_message.'</div>';
			}
			return;
		} else {
			if(wp_is_mobile()){
				echo "<div class='shdlr_info'><a href='http://".$atts['conf_id'].".".$this->url."'>Click for mobile view</a></div>";
				return;
			}

			if(!empty($request_data->data)){

					switch ($atts['type']) {
						case 'grid_popup':
							$type = 'popup';
							$div = '<div id="shdlr-grid-widget-link"><a href="#">View Conference Schedule</a></div>';
							$var = 'grid_popup';
							break;
						case 'simple_popup':
							$type = 'popup';
							$div = '<div id="shdlr-grid-simple-widget-link"><a href="#">View Conference Schedule</a></div>';
							$var = 'grid_simple_popup';
							break;
						case 'list_popup':
							$type = 'popup';
							$div = '<div id="shdlr-list-widget-link"><a href="#">View Conference Schedule</a></div>';
							$var = 'list_popup';
							break;
						case 'talks_popup':
							$type = 'popup';
							$div = '<div id="shdlr-talkslist-widget-link"><a href="#">View Conference Talks</a></div>';
							$var = 'talkslist_popup';
							break;
						case 'simple':
							$type = 'inline';
							$div = '<div id="shdlr-integrate" data-conftype="grid_simple" data-confid="'.$request_data->data.'"></div>';
							break;
						case 'list':
							$type = 'inline';
							$div = '<div id="shdlr-integrate" data-conftype="list_inline" data-confid="'.$request_data->data.'"></div>';
							break;
						case 'talks':
							$type = 'inline';
							$div = '<div id="shdlr-integrate" data-conftype="talkslist_inline" data-confid="'.$request_data->data.'"></div>';
							break;
						default:
							$type = 'inline';
							$div = '<div id="shdlr-integrate" data-conftype="grid_inline" data-confid="'.$request_data->data.'"></div>';
							break;
					}

					if($type == 'popup'){
						wp_enqueue_script('shdlr_integrate', 'http://'.$this->url.'/conferences/widget_js/'.$request_data->data.'/'.$var);
					} else {
						wp_enqueue_script('shdlr_integrate', 'http://'.$this->url.'/embed/lib.js');
					}

					echo $div;

			}
		}
	}

	public function shdlr_scripts(){
		
		wp_enqueue_script( 'jquery' );
	}

}

new ShdlrIntegrate();

?>
