<?


/*  Copyright 2009  Waseem Senjer  (email : waseem.senjer@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/*
Plugin Name: إضافة درر الكلام
Plugin URI: http://dorar.shamekh.ws
Description: لعرض حكم وأمثال وأقوال مشهورة في مدونتك من خلال موقع درر الكلام
Author: Waseem Senjer وسيم سنجر
Version: 2.0 
Author URI: http://www.shamekh.ws
*/




function dorar_elkalam()
{
$options = (array) get_option('widget_dorar_elkalam');
$items=get_feed($options['number']);





  if ($options['site']){
  $site= "<a href=\"http://dorar.shamekh.ws\" target=\"_blank\">درر الكلام</a>";
  } else {
  $site="";

  }
  
  
   if (empty($items)) {
   // when items is NULL or fail to fetch the rss
	echo "<ul>";
	echo '<li>من جد وجد ومن زرع حصد ومن سار على الدرب وصل</li>';
	echo $site;
	echo "</ul>"; 
 
   
   }
			else{
				// echo the results
				foreach ( $items as $item ) : 
						echo "<ul>";
						echo "<li>".$item['description']."</li>";
						
						echo "</ul>"; 

				 endforeach; 
				 echo $site;
			}
}





////the widget function

function widget_dorar_elkalam($args) {
  extract($args);
  $defaults = array('title'=>'حكم وأقوال' , 'site'=>'unchecked' , 'number'=>1);
  $options = (array) get_option('widget_dorar_elkalam');
  
  
  echo $before_widget;
  echo $before_title;
 
  if ($options['title']!="") {
  echo $options['title'];
  } else { echo $defaults['title']; }
  
  echo $after_title;
  dorar_elkalam();
  echo $after_widget;
}
/////////////////////////////////////////////////



//////////////////////////////////////////////////
function dorar_elkalam_init()
{
  register_sidebar_widget(__('حكم وأقوال'), 'widget_dorar_elkalam'); 
  register_widget_control('حكم وأقوال', 'dorar_elkalam_control');  
}
add_action("plugins_loaded", "dorar_elkalam_init");

////////////////////////////////////////////////////
function get_feed($number){
		
 // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/rss.php');
$rss = @fetch_rss('http://feeds.feedburner.com/dorar-elkalam'); // V 1.5

$items = @array_slice($rss->items, 0, $number,false);
return $items;

}
//////////////////////////////////////////////////////
// CONTROL
function dorar_elkalam_control () {
		$options = $newoptions = get_option('widget_dorar_elkalam');
		if ( $_POST['dorar-submit'] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['dorar-title']));
			$newoptions['number'] = strip_tags(stripslashes($_POST['dorar-number'])); // v 2.0
			$newoptions['site'] = strip_tags(stripslashes(isset($_POST['dorar-site'])));
			
			
			
		}
		// if the options are new , swap between the old and the new options .
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_dorar_elkalam', $options);
		}
?>
				<p style="text-align: right">
					<label for="dorar-title" ><?php _e('اسم المربع:', 'widgets'); ?> <input type="text" id="dorar-title" name="dorar-title" value="<?php echo $options['title']; ?>" /></label>
				</p>
				<p><label for="dorar-site"><input class="checkbox" id="dorar-site" name="dorar-site" type="checkbox"   <?php if ($options['site']) {echo ' checked="checked"';}  ?> > ظهور رابط موقع درر الكلام؟</label></p>
				<p style="text-align: right">
									<label for="dorar-number" ><?php _e('عدد المقولات:', 'widgets'); ?> </label>
<select name="dorar-number"  id="dorar-number" >

<option value="1" <? if($options['number']==1) { echo "selected=\'selected\'"; }  ?>>1</option>
<option value="2" <? if($options['number']==2) { echo "selected=\'selected\'"; }  ?>>2</option>
<option value="3" <? if($options['number']==3) { echo "selected=\'selected\'"; }  ?>>3</option>
<option value="4" <? if($options['number']==4) { echo "selected=\'selected\'"; }  ?>>4</option>
<option value="5" <? if($options['number']==5) { echo "selected=\'selected\'"; }  ?>>5</option>

		</select>

					<input type="hidden" name="dorar-submit" id="feeds-submit" value="1" />
				</p>				
<?php
	}


	// Uninstallation
register_deactivation_hook( __FILE__, 'dorar_elkalam_unistall' );
	
	// Uniinstallation
function dorar_elkalam_unistall () {
	delete_option('widget_dorar_elkalam');
		delete_option('dorar_elkalam_control');
	
}
?>