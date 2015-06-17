<?php
/**
 * @package WhatsApp Button
 * @version 1.0
 */
/*
 Plugin Name: WhatsApp Button
 Plugin URI: http://www.edc.org.kw
 Description: By using WhatsApp Button Plugin, you can add a share button for WhatsApp into posts/pages
 Version: 1.0
 Author: EDC Team
 Author URI: http://www.edc.org.kw
 License: It is Free -_-
*/

function whatsapp_button_plugin_install(){
	add_option( 'whatsapp_button_size', 3, '', 'yes' ); 
	add_option( 'whatsapp_button_allow', 1, '', 'yes' ); 
	add_option( 'whatsapp_button_place', 0, '', 'yes' ); 
    add_option( 'whatsapp_button_start_element', '<div style="margin:10px 0 10px 0;">', '', 'yes' ); 
    add_option( 'whatsapp_button_end_element', '</div>', '', 'yes' ); 
}
register_activation_hook(__FILE__,'whatsapp_button_plugin_install'); 

function whatsapp_button_plugin_scripts(){
     wp_register_script('whatsapp_button_plugin_scripts',plugin_dir_url( __FILE__ ).'js/whatsapp-button.js');
     wp_enqueue_script('whatsapp_button_plugin_scripts');
}
add_action('wp_enqueue_scripts','whatsapp_button_plugin_scripts'); 

function whatsapp_button_adminHeader() {
	echo "<style type=\"text/css\" media=\"screen\">\n";
	echo "#whatsapp_button { margin:0 0 20px 0; border:1px solid #cccccc; padding:5px; background-color:#fff; }\n";
	echo "#whatsapp_button input { padding:7px; margin:0 0 7px 0; }\n";
	do_action('whatsapp_button_css');
	echo "</style>\n";
}

add_action('admin_head','whatsapp_button_adminHeader');

function whatsapp_button_words($k=''){

if ( get_option( 'WPLANG' ) == 'ar'){
$word['title'] = 'جلب البيانات';
$word['start'] = 'بداية الكود, تستطيع استخدام العناصر h1,h2,div,p';
$word['end'] = 'نهاية العنصر';
$word['size'] = 'حجم الزر';
$word['small'] = 'صغير';
$word['medium'] = 'وسط';
$word['large'] = 'كبير';
$word['yes'] = 'نعم';
$word['no'] = 'لا';
$word['allow'] = 'السماح بإضافة الزر';
$word['top'] = 'أعلى الموضوع';
$word['bottom'] = 'أسفل الموضوع';
$word['place'] = 'مكان عرض الزر في الموضوع';
$word['update_options'] = 'تحديث';
}else{
$word['title'] = 'WhatsApp Button';
$word['start'] = 'Start element, you can using h1,h2,div,p';
$word['end'] = 'End element';
$word['size'] = 'button size';
$word['small'] = 'Small';
$word['medium'] = 'Medium';
$word['large'] = 'Large';
$word['yes'] = 'Yes';
$word['no'] = 'No';
$word['allow'] = 'Allow button';
$word['top'] = 'Top';
$word['bottom'] = 'Bottom';
$word['place'] = 'Button place';
$word['update_options'] = 'Update options';
}
return $word[$k];
}

function whatsapp_button(){
global $post;

if(get_option('whatsapp_button_allow') == 1){
$post_id = $post->ID;
$post_link = get_permalink($post_id);
$post_title = $post->post_title;
$post_excerpt = $post->post_excerpt;
$post_content = $post->post_content;

$start_element = stripslashes(get_option('whatsapp_button_start_element'));
$end_element = stripslashes(get_option('whatsapp_button_end_element'));
$size = get_option('whatsapp_button_size');

if($post_excerpt == ""){ $data_text = $post_title; }else{ $data_text = $post_excerpt; }

$code = '';
$code .= $start_element;
if($size == 1){
$code .= '<a href="whatsapp://send" data-text="'.strip_tags($data_text).'" data-href="" class="wa_btn wa_btn_s" style="display:none">Share</a>';
}elseif($size == 2){
$code .= '<a href="whatsapp://send" data-text="'.strip_tags($data_text).'" data-href="" class="wa_btn wa_btn_m" style="display:none">Share</a>';
}elseif($size == 3){
$code .= '<a href="whatsapp://send" data-text="'.strip_tags($data_text).'" data-href="" class="wa_btn wa_btn_l" style="display:none">Share</a>';
}
$code .= $end_element;
}else{
$code = '';
}
return $code;
}

function whatsapp_button_content($content) {
	$more_content = whatsapp_button();
	if(get_option('whatsapp_button_place') == 1){
		$content = $more_content.$content;
	}else{
		$content = $content.$more_content;
	}
	return $content;
}
add_filter('the_content', 'whatsapp_button_content');

add_action( 'admin_menu', 'whatsapp_button_plugin_menu' );

function whatsapp_button_plugin_menu() {
	add_menu_page( ''.whatsapp_button_words('title').'', ''.whatsapp_button_words('title').'', 'manage_options', 'whatsapp-button-edit', 'whatsapp_button_options', ''.trailingslashit(plugins_url(null,__FILE__)).'/i/whatsapp.png' );
}

function whatsapp_button_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
	if ( get_option( 'whatsapp_button_size' ) !== false ) {
		update_option( 'whatsapp_button_size', intval($_POST['whatsapp_button_size']) );
		update_option( 'whatsapp_button_allow', intval($_POST['whatsapp_button_allow']) );
		update_option( 'whatsapp_button_place', intval($_POST['whatsapp_button_place']) );
		update_option( 'whatsapp_button_start_element', $_POST['whatsapp_button_start_element'] );
		update_option( 'whatsapp_button_end_element', $_POST['whatsapp_button_end_element'] );
	} else {
		add_option( 'whatsapp_button_size', 1, null );
		add_option( 'whatsapp_button_allow', 1, null );
		add_option( 'whatsapp_button_place', 0, null );
		add_option( 'whatsapp_button_start_element', '', null );
		add_option( 'whatsapp_button_end_element', '', null );
	}
}

$whatsapp_button_start_element = stripslashes(get_option('whatsapp_button_start_element'));
$whatsapp_button_end_element = stripslashes(get_option('whatsapp_button_end_element'));
?>
	<div id="whatsapp_button" class="submit">
			<div class="dbx-content">				
				<h2><?php echo whatsapp_button_words('title'); ?></h2>
				<br />
	
				<form name="sytform" action="" method="post">
					<input type="hidden" name="submitted" value="1" />

					<div>
						<input style="width:70%;" id="whatsapp_button_start_element" type="text" name="whatsapp_button_start_element" value="<?php echo htmlentities($whatsapp_button_start_element); ?>" />
						<label for="whatsapp_button_start_element"><?php echo whatsapp_button_words('start'); ?></label>
					</div>
					
					<div>
						<input style="width:70%;" id="whatsapp_button_end_element" type="text" name="whatsapp_button_end_element" value="<?php echo htmlentities($whatsapp_button_end_element); ?>" />
						<label for="whatsapp_button_end_element"><?php echo whatsapp_button_words('end'); ?></label>
					</div>
						
					<div>
						<select name="whatsapp_button_size" id="whatsapp_button_size">
						<option value="1"<?php echo ( get_option('whatsapp_button_size') == 1 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('small'); ?></option>
						<option value="2"<?php echo ( get_option('whatsapp_button_size') == 2 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('medium'); ?></option>
						<option value="3"<?php echo ( get_option('whatsapp_button_size') == 3 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('large'); ?></option>
						</select>
						<label for="whatsapp_button_size"><?php echo whatsapp_button_words('size'); ?></label>
					</div>
						
					<div>
						<select name="whatsapp_button_allow" id="whatsapp_button_allow">
						<option value="1"<?php echo ( get_option('whatsapp_button_allow') == 1 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('yes'); ?></option>
						<option value="0"<?php echo ( get_option('whatsapp_button_allow') == 0 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('no'); ?></option>
						</select>
						<label for="whatsapp_button_allow"><?php echo whatsapp_button_words('allow'); ?></label>
					</div>
						
					<div>
						<select name="whatsapp_button_place" id="whatsapp_button_place">
						<option value="1"<?php echo ( get_option('whatsapp_button_place') == 1 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('top'); ?></option>
						<option value="0"<?php echo ( get_option('whatsapp_button_place') == 0 ) ? ' selected="selected"' : ''; ?>><?php echo whatsapp_button_words('bottom'); ?></option>
						</select>
						<label for="whatsapp_button_place"><?php echo whatsapp_button_words('place'); ?></label>
					</div>
					
					<div style="padding: 1.5em 0;margin: 5px 0;">
						<input type="submit" name="Submit" value="<?php echo whatsapp_button_words('update_options'); ?>" />
					</div>
				</form>
			</div>   
						
		</div>
<?php
}
