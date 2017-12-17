<?php 
function create_wow_attributes_tables() {	
global $wpdb;
// $wow_pref = 'wow__';  $pref_avalue = 'attribute_value_';


$table_name = WOW_TABLE_ATTRIBUTE;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  status enum('valid','moderated','deleted','notused') collate utf8_unicode_ci NOT NULL default 'valid',
  entity_id int(10) unsigned NOT NULL default '0',
  is_visible_in_front enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  is_visible_in_front_listing enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  is_global enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_user_defined enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_required enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_visible_in_advanced_search enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_searchable enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_filterable enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_comparable enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_html_allowed_on_front enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_unique enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_filterable_in_search enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_used_for_sort_by enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_configurable enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_requiring_unit enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_recordable_in_cart_meta enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_used_in_admin_listing_column enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_used_in_quick_add_form enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_used_for_variation enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  is_used_in_variation enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  _display_informations_about_value enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  _need_verification enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  _unit_group_id int(10) default NULL,
  _default_unit int(10) default NULL,
  is_historisable enum('yes','no') collate utf8_unicode_ci default 'yes',
  is_intrinsic enum('yes','no') collate utf8_unicode_ci default 'no',
  data_type_to_use enum('custom','internal') collate utf8_unicode_ci NOT NULL default 'custom',
  use_ajax_for_filling_field enum('yes','no') collate utf8_unicode_ci default 'no',
  filter_position INT(10) NOT NULL DEFAULT '0' ,
  sorting_position INT(10) NOT NULL DEFAULT '0' ,
  data_type enum('datetime','decimal','integer','text','varchar') collate utf8_unicode_ci NOT NULL default 'varchar',
  backend_table varchar(255) collate utf8_unicode_ci default NULL, 
  backend_input enum('text', 'textarea', 'select', 'multiple-select', 'password', 'hidden', 'radio', 'checkbox', 'date', 'map') collate utf8_unicode_ci NOT NULL default 'text',
  frontend_label varchar(255) collate utf8_unicode_ci default NULL,
  frontend_label_2 varchar(255) collate utf8_unicode_ci default NULL,
  frontend_input enum('text', 'textarea', 'select', 'multiple-select', 'password', 'hidden','radio', 'checkbox') collate utf8_unicode_ci NOT NULL default 'text',
  frontend_verification enum('','username','email','postcode','country','state','phone') collate utf8_unicode_ci default NULL,
  code varchar(255) collate utf8_general_ci NOT NULL default '',
  note varchar(255) collate utf8_unicode_ci NOT NULL,
  default_value text collate utf8_unicode_ci,
  frontend_unit varchar(255) collate utf8_unicode_ci default NULL,  
  PRIMARY KEY	(id),
  UNIQUE KEY code (code),
  KEY status (status),
  KEY is_global (is_global),
  KEY is_user_defined (is_user_defined),
  KEY is_required (is_required),
  KEY is_visible_in_advanced_search (is_visible_in_advanced_search),
  KEY is_searchable (is_searchable),
  KEY is_filterable (is_filterable),
  KEY is_comparable (is_comparable),
  KEY is_html_allowed_on_front (is_html_allowed_on_front),
  KEY is_unique (is_unique),
  KEY is_filterable_in_search (is_filterable_in_search),
  KEY is_used_for_sort_by (is_used_for_sort_by),
  KEY is_configurable (is_configurable),
  KEY is_requiring_unit (is_requiring_unit),
  KEY is_recordable_in_cart_meta (is_recordable_in_cart_meta),
  KEY use_ajax_for_filling_field (use_ajax_for_filling_field),
  KEY data_type (data_type)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = WOW_TABLE_ATTR_OPTIONS;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
	id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
	status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
	attribute_id INT(10) UNSIGNED NOT NULL,
	position INT(10) UNSIGNED NOT NULL DEFAULT '1',	
	label VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
	color_code VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL default '',
	PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
}
/// value VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
 
$table_name = WOW_TABLE_ATTRIBUTE_SET;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	status ENUM('valid','moderated','deleted') NULL DEFAULT 'valid' ,
	default_set ENUM('yes','no') NULL DEFAULT 'no' ,
	position INT(10) NOT NULL DEFAULT '0' ,
	entity_id INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
	is_no_product INT(10) NOT NULL DEFAULT '0',
	set_post_type varchar(255) collate utf8_general_ci NOT NULL default '',
	name VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
	PRIMARY KEY (id) ,
	UNIQUE KEY set_post_type (set_post_type),
	KEY position (position) ,
	KEY status (status) ,
	KEY entity_id (entity_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}

$table_name = WOW_TABLE_ATTRIBUTE_SET_SECTION;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	status ENUM('valid','moderated','deleted') NULL DEFAULT 'valid' ,
	default_group ENUM('yes','no') NULL DEFAULT 'no' ,
	attribute_set_id INT UNSIGNED NOT NULL DEFAULT '0' ,
	position INT NOT NULL DEFAULT '0' ,
	backend_display_type ENUM('fixed-tab','movable-tab') NULL DEFAULT 'fixed-tab' ,
	used_in_shop_type ENUM('presentation','sale') NULL DEFAULT 'sale' ,
	display_on_frontend ENUM('yes','no') NULL DEFAULT 'yes' ,
	code VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
	name VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL DEFAULT '' ,
	PRIMARY KEY (id) ,
	UNIQUE attribute_set_id_name_unique (attribute_set_id, code) ,
	KEY attribute_set_id_position_key (attribute_set_id, position) ,
	KEY attribute_set_id_index (attribute_set_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = WOW_TABLE_ATTRIBUTE_SECTION_DET;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
	id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
	status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
	entity_type_id INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
	attribute_set_id INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
	attribute_group_id INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
	attribute_id INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
	position INT(10) NOT NULL DEFAULT '0' ,
	PRIMARY KEY (id) ,
	KEY status (status),
	KEY attribute_set_id (attribute_set_id, position) ,
	KEY position (position) ,
	KEY attribute_id (attribute_id) ,
	KEY attribute_set_id_position (attribute_set_id) ,
	KEY attribute_group_id (attribute_group_id) ,
	KEY entity_type_id (entity_type_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = WOW_TABLE_WISHLIST;
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
	id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
	status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
	user_id INT(10) UNSIGNED NOT NULL ,
	label varchar(255) collate utf8_unicode_ci default NULL ,
	products varchar(255) collate utf8_unicode_ci ,	
	position INT(10) NOT NULL DEFAULT '0' ,
	PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}

/* 
$table_name = $wpdb->prefix . $wow_pref . "attributes_unit";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
  creation_date datetime default NULL,
  last_update_date datetime default NULL,
  group_id int(10) default NULL,
  is_default_of_group enum('yes','no') collate utf8_unicode_ci default 'no',
  unit char(25) collate utf8_unicode_ci NOT NULL,
  name char(50) collate utf8_unicode_ci NOT NULL,
  change_rate decimal(12,5),
  code_iso varchar(255) collate utf8_unicode_ci,
  PRIMARY KEY  (id),
  KEY status (status)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = $wpdb->prefix . $wow_pref . "attributes_unit_groups";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
  creation_date datetime default NULL,
  last_update_date datetime default NULL,
  name varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (id),
  KEY status (status)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "varchar";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  entity_type_id int(10) unsigned NOT NULL default '0',
  attribute_id int(10) unsigned NOT NULL default '0',
  entity_id int(10) unsigned NOT NULL default '0',
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value varchar(255) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "datetime";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  entity_type_id int(10) unsigned NOT NULL default '0',
  attribute_id int(10) unsigned NOT NULL default '0',
  entity_id int(10) unsigned NOT NULL default '0',
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value datetime default NULL,
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}


$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "decimal";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  entity_type_id int(10) unsigned NOT NULL,
  attribute_id int(10) unsigned NOT NULL,
  entity_id int(10) unsigned NOT NULL,
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value decimal(12,5) NOT NULL,
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}

$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "integer";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  entity_type_id int(10) unsigned NOT NULL default '0',
  attribute_id int(10) unsigned NOT NULL default '0',
  entity_id int(10) unsigned NOT NULL default '0',
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value int(10) NOT NULL,
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}

$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "text";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  entity_type_id int(10) unsigned NOT NULL default '0',
  attribute_id int(10) unsigned NOT NULL default '0',
  entity_id int(10) unsigned NOT NULL default '0',
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value longtext collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}

$table_name = $wpdb->prefix . $wow_pref . $pref_avalue . "_histo";
if($wpdb->get_var("show tables like '$table_name'") != $table_name) { 
$sql[$table_name] = "CREATE TABLE " . $table_name . " (
  value_id int(10) NOT NULL AUTO_INCREMENT,
  status enum('valid','moderated','deleted') collate utf8_unicode_ci NOT NULL default 'valid',
  creation_date datetime default NULL,
  last_update_date datetime default NULL,
  original_value_id int(10) unsigned NOT NULL default '0',
  entity_type_id int(10) unsigned NOT NULL default '0',
  attribute_id int(10) unsigned NOT NULL default '0',
  entity_id int(10) unsigned NOT NULL default '0',
  unit_id int(10) unsigned NOT NULL default '0',
	user_id bigint(20) unsigned NOT NULL default '1',
	creation_date_value datetime,
  language char(10) collate utf8_unicode_ci NOT NULL default 'en_US',
  value longtext collate utf8_unicode_ci NOT NULL,
  value_type char(70) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (value_id),
  KEY entity_id (entity_id),
  KEY attribute_id (attribute_id),
  KEY entity_type_id (entity_type_id),
  KEY unit_id (unit_id),
  KEY language (language),
  KEY status (status)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}
 */

// додати поля до таблиці term_taxonomy 
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_thumbnail` varchar(32) default '' AFTER `count`");
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_view` varchar(32) default '' AFTER `count`");
$wpdb->query("ALTER TABLE $wpdb->term_taxonomy ADD `term_order` bigint(20) default 99 AFTER `count`");


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);



}



?>