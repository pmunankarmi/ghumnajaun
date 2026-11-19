<?php
/** Automatically installs theme releases produced by pushes to main. */
defined('ABSPATH') || exit;
add_filter('cron_schedules',function($s){$s['gj_five_minutes']=['interval'=>300,'display'=>'Every five minutes'];return $s;});
add_action('init',function(){if(!wp_next_scheduled('gj_github_update'))wp_schedule_event(time()+60,'gj_five_minutes','gj_github_update');});
add_action('switch_theme',function(){wp_clear_scheduled_hook('gj_github_update');});
function gj_release(){
 $cached=get_transient('gj_github_release');if($cached!==false)return $cached;
 $r=wp_remote_get('https://github.com/pmunankarmi/ghumnajaun/releases/latest/download/ghumnajaun-update.json',['timeout'=>20,'headers'=>['Accept'=>'application/json','User-Agent'=>'GhumnaJaun-WordPress']]);
 if(is_wp_error($r)||wp_remote_retrieve_response_code($r)!==200){update_option('gj_update_status',['time'=>time(),'message'=>'GitHub release unavailable; current theme remains active.'],false);set_transient('gj_github_release',[],300);return [];}
 $data=json_decode(wp_remote_retrieve_body($r),true);$version=$data['version']??'';$url=$data['package']??'';
 if(!preg_match('/^\d+\.\d+\.\d+$/',$version)||$url!=='https://github.com/pmunankarmi/ghumnajaun/releases/download/v'.$version.'/ghumnajaun.zip')return [];
 $release=['theme'=>'ghumnajaun','new_version'=>$version,'url'=>'https://github.com/pmunankarmi/ghumnajaun','package'=>$url,'requires'=>'6.5','requires_php'=>'8.0'];set_transient('gj_github_release',$release,300);return $release;
 return [];
}
add_filter('pre_set_site_transient_update_themes',function($transient){if(!is_object($transient))$transient=new stdClass();$r=gj_release();if($r&&version_compare($r['new_version'],wp_get_theme('ghumnajaun')->get('Version'),'>'))$transient->response['ghumnajaun']=$r;return $transient;});
add_filter('auto_update_theme',function($enabled,$item){return ($item->theme??'')==='ghumnajaun'?true:$enabled;},10,2);
add_action('gj_github_update',function(){
 if(get_stylesheet()!=='ghumnajaun'||get_transient('gj_update_lock'))return;
 set_transient('gj_update_lock',1,10*MINUTE_IN_SECONDS);
 try{
  $release=gj_release();if(!$release||!version_compare($release['new_version'],wp_get_theme()->get('Version'),'>'))return;
  require_once ABSPATH.'wp-admin/includes/admin.php';require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
  $transient=get_site_transient('update_themes');if(!is_object($transient))$transient=new stdClass();$transient->response['ghumnajaun']=$release;set_site_transient('update_themes',$transient);
  // The owner explicitly requested managed GitHub theme updates. A Git checkout
  // elsewhere in an ancestor (for example the user's home directory) must not
  // disable this one theme's updater. Scope the exception to this call only.
  $managed_release=function($checkout,$context){return untrailingslashit($context)===untrailingslashit(get_theme_root('ghumnajaun'))?false:$checkout;};
  add_filter('automatic_updates_is_vcs_checkout',$managed_release,10,2);
  try{$updater=new WP_Automatic_Updater();$result=$updater->update('theme',(object)$release);}finally{remove_filter('automatic_updates_is_vcs_checkout',$managed_release,10);}
  wp_clean_themes_cache();$installed=wp_get_theme('ghumnajaun')->get('Version');
  $message=version_compare($installed,$release['new_version'],'>=')?'Theme updated automatically to '.$installed.'.':(is_wp_error($result)?$result->get_error_message():'Update deferred. Installed version: '.$installed.'.');
  update_option('gj_update_status',['time'=>time(),'message'=>$message],false);
 }finally{delete_transient('gj_update_lock');}
});
add_action('admin_footer',function(){if(!current_user_can('update_themes'))return; ?>
<script>setInterval(()=>fetch(ajaxurl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=gj_update_tick&_ajax_nonce=<?php echo esc_js(wp_create_nonce('gj_update_tick')); ?>'}),300000);</script>
<?php });
add_action('wp_ajax_gj_update_tick',function(){check_ajax_referer('gj_update_tick');if(!current_user_can('update_themes'))wp_send_json_error(null,403);do_action('gj_github_update');wp_send_json_success();});
add_action('admin_notices',function(){if(!current_user_can('update_themes'))return;$status=get_option('gj_update_status');echo '<div class="notice notice-info"><p>Ghumna Jaun '.esc_html(wp_get_theme()->get('Version')).' · Automatic GitHub updates enabled (main branch releases, approximately every five minutes while the site is running).';if($status)echo ' '.esc_html($status['message']);echo '</p></div>';});
