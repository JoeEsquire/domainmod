<?php
// /_includes/system/update-database.inc.php
// 
// DomainMOD is an open source application written in PHP & MySQL used to track and manage your web resources.
// Copyright (C) 2010 Greg Chetcuti
// 
// DomainMOD is free software; you can redistribute it and/or modify it under the terms of the GNU General
// Public License as published by the Free Software Foundation; either version 2 of the License, or (at your
// option) any later version.
// 
// DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
// implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
// for more details.
// 
// You should have received a copy of the GNU General Public License along with DomainMOD. If not, please see
// http://www.gnu.org/licenses/
?>
<?php
$direct = $_GET['direct'];
$current_timestamp = date("Y-m-d H:i:s");

if ($direct == "1") { 

	include("../start-session.inc.php");
	include("../config.inc.php");
	include("../database.inc.php");
	include("../software.inc.php");
	include("../auth/auth-check.inc.php");

}

$sql = "SELECT db_version
		FROM settings";
$result = mysql_query($sql,$connection) or die(mysql_error());

while ($row = mysql_fetch_object($result)) {
	$current_db_version = $row->db_version;
}

if ($current_db_version < $most_recent_db_version) {

	// upgrade database from 1.1 to 1.2
	if ($current_db_version == 1.1) {

		$sql = "ALTER TABLE `ssl_certs`  
				ADD `ip` VARCHAR(50) NOT NULL AFTER `name`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.2', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.2;
		
	}

	// upgrade database from 1.2 to 1.3
	if ($current_db_version == 1.2) {

		$sql = "CREATE TABLE IF NOT EXISTS `ip_addresses` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(255) NOT NULL,
				`ip` varchar(255) NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.3', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.3;
		
	}

	// upgrade database from 1.3 to 1.4
	if ($current_db_version == 1.3) {

		$sql = "ALTER TABLE `ip_addresses` 
				ADD `notes` longtext NOT NULL AFTER `ip`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.4', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.4;
		
	}

	// upgrade database from 1.4 to 1.5
	if ($current_db_version == 1.4) {

		$sql = "ALTER TABLE `domains`  
				ADD `ip_id` int(10) NOT NULL default '0' AFTER `dns_id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.5', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.5;
		
	}

	// upgrade database from 1.5 to 1.6
	if ($current_db_version == 1.5) {

		$sql = "ALTER TABLE `domains` 
				CHANGE `ip_id` `ip_id` INT(10) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE `domains` 
				SET ip_id = '1',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "TRUNCATE `ip_addresses`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO `ip_addresses` 
				(`id`, `name`, `ip`, `insert_time`) VALUES 
				('1', '[no ip address]', '-', '" . mysql_real_escape_string($current_timestamp) . "')";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.6', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.6;
		
	}

	// upgrade database from 1.6 to 1.7
	if ($current_db_version == 1.6) {

		$sql = "ALTER TABLE `ssl_certs` 
				DROP `ip`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.7', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.7;
		
	}

	// upgrade database from 1.7 to 1.8
	if ($current_db_version == 1.7) {

		$sql = "ALTER TABLE `ip_addresses`  
				ADD `test_data` int(1) NOT NULL default '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.8', 
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.8;
		
	}

	// upgrade database from 1.8 to 1.9
	if ($current_db_version == 1.8) {

		$sql = "ALTER TABLE `settings`  
				ADD `email_address` VARCHAR(255) NOT NULL AFTER `db_version`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.9', 
					email_address = 'code@aysmedia.com',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.9;
		
	}

	// upgrade database from 1.9 to 1.91
	if ($current_db_version == 1.9) {

		$sql = "ALTER TABLE `ip_addresses` 
				ADD `rdns` VARCHAR(255) NOT NULL DEFAULT '-' AFTER `ip`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.91',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.91;
		
	}

	// upgrade database from 1.91 to 1.92
	if ($current_db_version == 1.91) {

		$sql = "ALTER TABLE `settings` 
				ADD `type` VARCHAR(50) NOT NULL AFTER `id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings 
				SET type =  'system',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "UPDATE settings
				SET db_version = '1.92',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'
				WHERE type = 'system'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.92;
		
	}

	// upgrade database from 1.92 to 1.93
	if ($current_db_version == 1.92) {

		$sql = "ALTER TABLE `settings` 
				DROP `type`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.93',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.93;
		
	}

	// upgrade database from 1.93 to 1.94
	if ($current_db_version == 1.93) {

		$sql = "ALTER TABLE `settings` 
				ADD `number_of_domains` INT(5) NOT NULL DEFAULT '50' AFTER `email_address`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				ADD `number_of_ssl_certs` INT(5) NOT NULL DEFAULT '50' AFTER `number_of_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.94',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.94;
		
	}

	// upgrade database from 1.94 to 1.95
	if ($current_db_version == 1.94) {

		$sql = "ALTER TABLE `currencies` 
				DROP `default_currency`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				ADD `default_currency` VARCHAR(5) NOT NULL DEFAULT 'CAD' AFTER `email_address`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.95',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.95;
		
	}

	// upgrade database from 1.95 to 1.96
	if ($current_db_version == 1.95) {

		$sql = "ALTER TABLE `currencies` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.96',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.96;
		
	}

	// upgrade database from 1.96 to 1.97
	if ($current_db_version == 1.96) {

		$sql = "CREATE TABLE IF NOT EXISTS `owners` ( 
					`id` int(5) NOT NULL auto_increment,
					`name` varchar(255) NOT NULL,
					`notes` longtext NOT NULL,
					`active` int(1) NOT NULL default '1',
					`test_data` int(1) NOT NULL default '0',
					`insert_time` datetime NOT NULL,
					`update_time` datetime NOT NULL,
					PRIMARY KEY  (`id`),
					KEY `name` (`name`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO owners 
					(id, name, notes, active, test_data, insert_time, update_time) 
					SELECT id, name, notes, active, test_data, insert_time, update_time FROM companies ORDER BY id;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "DROP TABLE `companies`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` CHANGE `company_id` `owner_id` INT(5) NOT NULL;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrar_accounts` CHANGE `company_id` `owner_id` INT(5) NOT NULL;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_accounts` CHANGE `company_id` `owner_id` INT(5) NOT NULL;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs` CHANGE `company_id` `owner_id` INT(5) NOT NULL;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.97',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.97;
		
	}

	// upgrade database from 1.97 to 1.98
	if ($current_db_version == 1.97) {

		$sql = "INSERT INTO `categories` 
					(`name`, `owner`, `insert_time`) VALUES 
					('[no category]', '[no stakeholder]', '" . mysql_real_escape_string($current_timestamp) . "');";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM categories
				WHERE default_category = '1';";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			$sql_update = "UPDATE categories
						   SET default_category = '1',
						   	   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
						   WHERE name = '[no category]'";
			$result_update = mysql_query($sql_update,$connection) or die(mysql_error());
		}

		$sql = "ALTER TABLE `dns` 
					ADD `default_dns` INT(1) NOT NULL DEFAULT '0' AFTER `number_of_servers`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO `dns` 
					(`name`, `dns1`, `dns2`, `number_of_servers`, `insert_time`) VALUES 
					('[no dns]', 'ns1.no-dns.com', 'ns2.no-dns.com', '2', '" . mysql_real_escape_string($current_timestamp) . "');";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM dns
				WHERE default_dns = '1';";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			$sql_update = "UPDATE dns
						   SET default_dns = '1',
						   	   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
						   WHERE name = '[no dns]'";
			$result_update = mysql_query($sql_update,$connection) or die(mysql_error());
		}

		$sql = "ALTER TABLE `owners`  
					ADD `default_owner` INT(1) NOT NULL DEFAULT '0' AFTER `notes`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO `owners` 
					(`name`, `insert_time`) VALUES 
					('[no owner]', '" . mysql_real_escape_string($current_timestamp) . "');";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM owners
				WHERE default_owner = '1';";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			$sql_update = "UPDATE owners
						   SET default_owner = '1',
						   	   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
						   WHERE name = '[no owner]'";
			$result_update = mysql_query($sql_update,$connection) or die(mysql_error());
		}

		$sql = "ALTER TABLE `ip_addresses` 
					ADD `default_ip_address` INT(1) NOT NULL DEFAULT '0' AFTER `notes`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM ip_addresses
				WHERE default_ip_address = '1';";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		if (mysql_num_rows($result) == 0) {
			$sql_update = "UPDATE ip_addresses
						   SET default_ip_address = '1',
						   	   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
						   WHERE name = '[no ip address]'";
			$result_update = mysql_query($sql_update,$connection) or die(mysql_error());
		}

		$sql = "UPDATE settings
				SET db_version = '1.98',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.98;
		
	}

	// upgrade database from 1.98 to 1.99
	if ($current_db_version == 1.98) {

		$sql = "ALTER TABLE `categories` 
					CHANGE `owner` `stakeholder` VARCHAR(255) NOT NULL;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE `categories`
					SET `stakeholder` = '[no stakeholder]',
						`update_time` = '" . mysql_real_escape_string($current_timestamp) . "'
				WHERE `stakeholder` = '[no category owner]';";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '1.99',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 1.99;
		
	}

	// upgrade database from 1.99 to 2.0001
	if ($current_db_version == 1.99) {

		$sql = "ALTER TABLE `currencies` 
					ADD `default_currency` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT default_currency
				FROM settings";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		while ($row = mysql_fetch_object($result)) {
			$default_currency = $row->default_currency;
		}
		
		$sql = "UPDATE currencies
				SET default_currency = '0',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE currencies
				SET default_currency = '1',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'
				WHERE currency = '" . mysql_real_escape_string($default_currency) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				DROP `default_currency`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0001',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0001;
		
	}

	// upgrade database from 2.0001 to 2.0002
	if ($current_db_version == 2.0001) {

		$sql = "ALTER TABLE `ssl_cert_functions` 
					ADD `default_function` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_cert_types` 
					ADD `default_type` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE ssl_cert_functions
				SET default_function = '1',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'
				WHERE function = 'Web Server SSL/TLS Certificate'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE ssl_cert_types
				SET default_type = '1',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'
				WHERE type = 'Wildcard'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0002',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0002;

	}

	// upgrade database from 2.0002 to 2.0003
	if ($current_db_version == 2.0002) {

		$sql = "DROP TABLE `ssl_cert_types`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs` 
				DROP `type_id`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_fees` 
				DROP `type_id`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0003',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0003;

	}

	// upgrade database from 2.0003 to 2.0004
	if ($current_db_version == 2.0003) {

		$sql = "CREATE TABLE IF NOT EXISTS `ssl_cert_types` ( 
					`id` int(10) NOT NULL auto_increment,
					`type` varchar(255) NOT NULL,
					`notes` longtext NOT NULL,
					`default_type` int(1) NOT NULL default '0',
					`active` int(1) NOT NULL default '1',
					`insert_time` datetime NOT NULL,
					`update_time` datetime NOT NULL,
					PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO ssl_cert_types 
					(id, type, notes, default_type, active, insert_time, update_time) 
					SELECT id, function, notes, default_function, active, insert_time, update_time FROM ssl_cert_functions ORDER BY id;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "DROP TABLE `ssl_cert_functions`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs` 
					CHANGE `function_id` `type_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_fees` 
					CHANGE `function_id` `type_id` INT(5) NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0004',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0004;

	}

	// upgrade database from 2.0004 to 2.0005
	if ($current_db_version == 2.0004) {

		$sql = "ALTER TABLE `ssl_cert_types`  
					ADD `test_data` int(1) NOT NULL default '0' AFTER `active`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0005',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0005;

	}

	// upgrade database from 2.0005 to 2.0006
	if ($current_db_version == 2.0005) {

		$sql = "ALTER TABLE `ip_addresses` 
					ADD `active` INT(1) NOT NULL DEFAULT '1' AFTER `default_ip_address`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
					CHANGE `active` `active` INT(2) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0006',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0006;

	}

	// upgrade database from 2.0006 to 2.0007
	if ($current_db_version == 2.0006) {

		$sql = "ALTER TABLE `registrars` 
					ADD `default_registrar` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrar_accounts` 
					ADD `default_account` INT(1) NOT NULL DEFAULT '0' AFTER `reseller`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_providers` 
					ADD `default_provider` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_accounts` 
					ADD `default_account` INT(1) NOT NULL DEFAULT '0' AFTER `reseller`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0007',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0007;

	}

	// upgrade database from 2.0007 to 2.0008
	if ($current_db_version == 2.0007) {

		$sql = "ALTER TABLE `owners` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrars` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_providers` 
					CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0008',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0008;

	}

	// upgrade database from 2.0008 to 2.0009
	if ($current_db_version == 2.0008) {

		$sql = "ALTER TABLE `currencies`  
				ADD `test_data` int(1) NOT NULL default '0' AFTER `active`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0009',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0009;

	}

	// upgrade database from 2.0009 to 2.0010
	if ($current_db_version == 2.0009) {

		$sql = "CREATE TABLE IF NOT EXISTS `user_settings` (
					`id` int(10) NOT NULL auto_increment,
					`user_id` int(10) NOT NULL,
					`number_of_domains` int(5) NOT NULL default '50',
					`number_of_ssl_certs` int(5) NOT NULL default '50',
					`display_domain_owner` int(1) NOT NULL default '0',
					`display_domain_registrar` int(1) NOT NULL default '0',
					`display_domain_account` int(1) NOT NULL default '1',
					`display_domain_expiry_date` int(1) NOT NULL default '1',
					`display_domain_category` int(1) NOT NULL default '1',
					`display_domain_dns` int(1) NOT NULL default '0',
					`display_domain_ip` int(1) NOT NULL default '0',
					`display_domain_tld` int(1) NOT NULL default '0',
					`display_ssl_owner` int(1) NOT NULL default '0',
					`display_ssl_provider` int(1) NOT NULL default '0',
					`display_ssl_account` int(1) NOT NULL default '0',
					`display_ssl_domain` int(1) NOT NULL default '0',
					`display_ssl_type` int(1) NOT NULL default '0',
					`display_ssl_expiry_date` int(1) NOT NULL default '0',
					`insert_time` datetime NOT NULL,
					`update_time` datetime NOT NULL,
					PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		while ($row = mysql_fetch_object($result)) {
			$sql_temp = "INSERT INTO user_settings
						 (user_id, insert_time) VALUES 
						 ('" . mysql_real_escape_string($row->id) . "', '" . mysql_real_escape_string($current_timestamp) . "');";
			$result_temp = mysql_query($sql_temp,$connection) or die(mysql_error());
		}

		$sql = "UPDATE settings
				SET db_version = '2.001',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.001;

	}

	// upgrade database from 2.0010 to 2.0011
	if ($current_db_version == 2.001) {

		$sql = "ALTER TABLE `settings` 
					DROP `number_of_domains`, 
					DROP `number_of_ssl_certs`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0011',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0011;

	}

	// upgrade database from 2.0011 to 2.0012
	if ($current_db_version == 2.0011) {

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `display_domain_account` `display_domain_account` INT(1) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0012',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0012;

	}

	// upgrade database from 2.0012 to 2.0013
	if ($current_db_version == 2.0012) {

		$sql = "ALTER TABLE `categories` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `currencies` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `dns` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `fees` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ip_addresses` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `owners` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrars` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrar_accounts` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `segments` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `segments` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_accounts` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_cert_types` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_fees` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_providers` 
				DROP `test_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0013',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0013;

	}

	// upgrade database from 2.0013 to 2.0014
	if ($current_db_version == 2.0013) {

		$sql = "CREATE TABLE IF NOT EXISTS `segment_data` (
				`id` int(10) NOT NULL auto_increment,
				`segment_id` int(10) NOT NULL,
				`domain` varchar(255) NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0014',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0014;

	}

	// upgrade database from 2.0014 to 2.0015
	if ($current_db_version == 2.0014) {

		$sql = "ALTER TABLE `user_settings` 
					ADD `display_domain_fee` INT(1) NOT NULL DEFAULT '0' AFTER `display_domain_tld`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
					ADD `display_ssl_fee` INT(1) NOT NULL DEFAULT '0' AFTER `display_ssl_expiry_date`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0015',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0015;

	}

	// upgrade database from 2.0015 to 2.0016
	if ($current_db_version == 2.0015) {

		$sql = "ALTER TABLE `segment_data` 
					ADD `active` INT(1) NOT NULL DEFAULT '0' AFTER `domain`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `segment_data` 
					ADD `inactive` INT(1) NOT NULL DEFAULT '0' AFTER `active`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `segment_data` 
					ADD `missing` INT(1) NOT NULL DEFAULT '0' AFTER `inactive`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0016',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0016;

	}

	// upgrade database from 2.0016 to 2.0017
	if ($current_db_version == 2.0016) {

		$sql = "ALTER TABLE `segment_data` 
					ADD `filtered` INT(1) NOT NULL DEFAULT '0' AFTER `missing`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0017',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0017;

	}

	// upgrade database from 2.0017 to 2.0018
	if ($current_db_version == 2.0017) {

		$sql = "ALTER TABLE `ssl_certs` 
					CHANGE `domain_id` `domain_id` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0018',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0018;

	}

	// upgrade database from 2.0018 to 2.0019
	if ($current_db_version == 2.0018) {

		$sql = "ALTER TABLE `ssl_certs` 
					CHANGE `domain_id` `domain_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0019',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0019;

	}

	// upgrade database from 2.0019 to 2.0020
	if ($current_db_version == 2.0019) {

		$sql = "ALTER TABLE `user_settings`  
					ADD `expiration_emails` INT(1) NOT NULL DEFAULT '1' AFTER `user_id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0020',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0020;

	}

	// upgrade database from 2.0020 to 2.0021
	if ($current_db_version == 2.002) {

		$sql = "ALTER TABLE `settings` 
					ADD `full_url` VARCHAR(100) NOT NULL DEFAULT 'http://' AFTER `id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$full_url = substr($_SERVER["HTTP_REFERER"], 0, -1);
		
		$sql = "UPDATE settings
				SET full_url = '" . $full_url . "'";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0021',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0021;

	}

	// upgrade database from 2.0021 to 2.0022
	if ($current_db_version == 2.0021) {

		$sql = "ALTER TABLE `settings`  
					ADD `timezone` VARCHAR(10) NOT NULL DEFAULT 'Canada/Pacific' AFTER `email_address`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0022',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0022;

	}

	// upgrade database from 2.0022 to 2.0023
	if ($current_db_version == 2.0022) {

		$sql = "CREATE TABLE IF NOT EXISTS `timezones` (
				`id` int(5) NOT NULL auto_increment,
				`timezone` varchar(50) NOT NULL,
				`insert_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO `timezones` 
				(`timezone`, `insert_time`) VALUES 
				('Africa/Abidjan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Accra', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Addis_Ababa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Algiers', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Asmara', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Asmera', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Bamako', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Bangui', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Banjul', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Bissau', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Blantyre', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Brazzaville', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Bujumbura', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Cairo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Casablanca', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Ceuta', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Conakry', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Dakar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Dar_es_Salaam', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Djibouti', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Douala', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/El_Aaiun', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Freetown', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Gaborone', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Harare', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Johannesburg', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Juba', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Kampala', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Khartoum', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Kigali', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Kinshasa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Lagos', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Libreville', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Lome', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Luanda', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Lubumbashi', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Lusaka', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Malabo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Maputo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Maseru', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Mbabane', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Mogadishu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Monrovia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Nairobi', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Ndjamena', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Niamey', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Nouakchott', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Ouagadougou', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Porto-Novo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Sao_Tome', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Timbuktu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Tripoli', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Tunis', '" . mysql_real_escape_string($current_timestamp) . "'), ('Africa/Windhoek', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Adak', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Anchorage', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Anguilla', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Antigua', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Araguaina', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Buenos_Aires', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Catamarca', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/ComodRivadavia', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Cordoba', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Jujuy', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/La_Rioja', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Mendoza', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Rio_Gallegos', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Salta', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/San_Juan', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/San_Luis', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Tucuman', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Argentina/Ushuaia', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Aruba', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Asuncion', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Atikokan', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Atka', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Bahia', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Bahia_Banderas', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Barbados', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Belem', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Belize', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Blanc-Sablon', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Boa_Vista', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Bogota', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Boise', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Buenos_Aires', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cambridge_Bay', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Campo_Grande', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cancun', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Caracas', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Catamarca', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cayenne', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cayman', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Chicago', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Chihuahua', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Coral_Harbour', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cordoba', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Costa_Rica', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Creston', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Cuiaba', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Curacao', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Danmarkshavn', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Dawson', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Dawson_Creek', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Denver', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Detroit', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Dominica', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Edmonton', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Eirunepe', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/El_Salvador', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Ensenada', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Fort_Wayne', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Fortaleza', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Glace_Bay', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Godthab', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Goose_Bay', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Grand_Turk', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Grenada', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Guadeloupe', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Guatemala', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Guayaquil', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Guyana', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Halifax', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Havana', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Hermosillo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Indianapolis', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Knox', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Marengo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Petersburg', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Tell_City', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Vevay', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Vincennes', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indiana/Winamac', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Indianapolis', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Inuvik', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Iqaluit', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Jamaica', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Jujuy', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Juneau', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Kentucky/Louisville', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Kentucky/Monticello', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Knox_IN', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Kralendijk', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/La_Paz', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Lima', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Los_Angeles', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Louisville', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Lower_Princes', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Maceio', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Managua', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Manaus', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Marigot', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Martinique', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Matamoros', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Mazatlan', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Mendoza', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Menominee', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Merida', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Metlakatla', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Mexico_City', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Miquelon', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Moncton', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Monterrey', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Montevideo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Montreal', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Montserrat', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Nassau', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/New_York', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Nipigon', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Nome', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Noronha', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/North_Dakota/Beulah', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/North_Dakota/Center', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/North_Dakota/New_Salem', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Ojinaga', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Panama', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Pangnirtung', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Paramaribo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Phoenix', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Port-au-Prince', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Port_of_Spain', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Porto_Acre', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Porto_Velho', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Puerto_Rico', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Rainy_River', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Rankin_Inlet', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Recife', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Regina', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Resolute', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Rio_Branco', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Rosario', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Santa_Isabel', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Santarem', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Santiago', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Santo_Domingo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Sao_Paulo', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Scoresbysund', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Shiprock', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Sitka', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Barthelemy', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Johns', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Kitts', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Lucia', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Thomas', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/St_Vincent', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Swift_Current', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Tegucigalpa', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Thule', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Thunder_Bay', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Tijuana', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Toronto', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Tortola', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Vancouver', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Virgin', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Whitehorse', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Winnipeg', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Yakutat', '" . mysql_real_escape_string($current_timestamp) . "'), ('America/Yellowknife', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Casey', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Davis', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/DumontDUrville', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Macquarie', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Mawson', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/McMurdo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Palmer', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Rothera', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/South_Pole', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Syowa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Antarctica/Vostok', '" . mysql_real_escape_string($current_timestamp) . "'), ('Arctic/Longyearbyen', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Aden', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Almaty', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Amman', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Anadyr', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Aqtau', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Aqtobe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ashgabat', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ashkhabad', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Baghdad', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Bahrain', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Baku', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Bangkok', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Beirut', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Bishkek', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Brunei', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Calcutta', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Choibalsan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Chongqing', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Chungking', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Colombo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Dacca', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Damascus', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Dhaka', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Dili', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Dubai', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Dushanbe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Gaza', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Harbin', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Hebron', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ho_Chi_Minh', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Hong_Kong', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Hovd', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Irkutsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Istanbul', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Jakarta', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Jayapura', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Jerusalem', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kabul', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kamchatka', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Karachi', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kashgar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kathmandu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Katmandu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Khandyga', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kolkata', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Krasnoyarsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kuala_Lumpur', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kuching', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Kuwait', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Macao', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Macau', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Magadan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Makassar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Manila', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Muscat', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Nicosia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Novokuznetsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Novosibirsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Omsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Oral', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Phnom_Penh', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Pontianak', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Pyongyang', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Qatar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Qyzylorda', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Rangoon', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Riyadh', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Saigon', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Sakhalin', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Samarkand', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Seoul', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Shanghai', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Singapore', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Taipei', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Tashkent', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Tbilisi', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Tehran', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Tel_Aviv', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Thimbu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Thimphu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Tokyo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ujung_Pandang', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ulaanbaatar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ulan_Bator', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Urumqi', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Ust-Nera', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Vientiane', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Vladivostok', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Yakutsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Yekaterinburg', '" . mysql_real_escape_string($current_timestamp) . "'), ('Asia/Yerevan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Azores', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Bermuda', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Canary', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Cape_Verde', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Faeroe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Faroe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Jan_Mayen', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Madeira', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Reykjavik', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/South_Georgia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/St_Helena', '" . mysql_real_escape_string($current_timestamp) . "'), ('Atlantic/Stanley', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/ACT', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Adelaide', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Brisbane', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Broken_Hill', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Canberra', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Currie', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Darwin', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Eucla', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Hobart', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/LHI', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Lindeman', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Lord_Howe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Melbourne', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/North', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/NSW', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Perth', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Queensland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/South', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Sydney', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Tasmania', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Victoria', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/West', '" . mysql_real_escape_string($current_timestamp) . "'), ('Australia/Yancowinna', '" . mysql_real_escape_string($current_timestamp) . "'), ('Brazil/Acre', '" . mysql_real_escape_string($current_timestamp) . "'), ('Brazil/DeNoronha', '" . mysql_real_escape_string($current_timestamp) . "'), ('Brazil/East', '" . mysql_real_escape_string($current_timestamp) . "'), ('Brazil/West', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Atlantic', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Central', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/East-Saskatchewan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Eastern', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Mountain', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Newfoundland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Pacific', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Saskatchewan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Canada/Yukon', '" . mysql_real_escape_string($current_timestamp) . "'), ('Chile/Continental', '" . mysql_real_escape_string($current_timestamp) . "'), ('Chile/EasterIsland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Cuba', '" . mysql_real_escape_string($current_timestamp) . "'), ('Egypt', '" . mysql_real_escape_string($current_timestamp) . "'), ('Eire', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Amsterdam', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Andorra', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Athens', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Belfast', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Belgrade', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Berlin', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Bratislava', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Brussels', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Bucharest', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Budapest', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Busingen', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Chisinau', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Copenhagen', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Dublin', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Gibraltar', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Guernsey', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Helsinki', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Isle_of_Man', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Istanbul', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Jersey', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Kaliningrad', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Kiev', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Lisbon', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Ljubljana', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/London', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Luxembourg', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Madrid', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Malta', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Mariehamn', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Minsk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Monaco', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Moscow', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Nicosia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Oslo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Paris', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Podgorica', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Prague', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Riga', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Rome', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Samara', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/San_Marino', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Sarajevo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Simferopol', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Skopje', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Sofia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Stockholm', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Tallinn', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Tirane', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Tiraspol', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Uzhgorod', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Vaduz', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Vatican', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Vienna', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Vilnius', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Volgograd', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Warsaw', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Zagreb', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Zaporozhye', '" . mysql_real_escape_string($current_timestamp) . "'), ('Europe/Zurich', '" . mysql_real_escape_string($current_timestamp) . "'), ('Greenwich', '" . mysql_real_escape_string($current_timestamp) . "'), ('Hongkong', '" . mysql_real_escape_string($current_timestamp) . "'), ('Iceland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Antananarivo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Chagos', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Christmas', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Cocos', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Comoro', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Kerguelen', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Mahe', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Maldives', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Mauritius', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Mayotte', '" . mysql_real_escape_string($current_timestamp) . "'), ('Indian/Reunion', '" . mysql_real_escape_string($current_timestamp) . "'), ('Iran', '" . mysql_real_escape_string($current_timestamp) . "'), ('Israel', '" . mysql_real_escape_string($current_timestamp) . "'), ('Jamaica', '" . mysql_real_escape_string($current_timestamp) . "'), ('Japan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Kwajalein', '" . mysql_real_escape_string($current_timestamp) . "'), ('Libya', '" . mysql_real_escape_string($current_timestamp) . "'), ('Mexico/BajaNorte', '" . mysql_real_escape_string($current_timestamp) . "'), ('Mexico/BajaSur', '" . mysql_real_escape_string($current_timestamp) . "'), ('Mexico/General', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Apia', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Auckland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Chatham', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Chuuk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Easter', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Efate', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Enderbury', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Fakaofo', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Fiji', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Funafuti', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Galapagos', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Gambier', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Guadalcanal', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Guam', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Honolulu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Johnston', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Kiritimati', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Kosrae', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Kwajalein', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Majuro', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Marquesas', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Midway', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Nauru', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Niue', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Norfolk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Noumea', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Pago_Pago', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Palau', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Pitcairn', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Pohnpei', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Ponape', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Port_Moresby', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Rarotonga', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Saipan', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Samoa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Tahiti', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Tarawa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Tongatapu', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Truk', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Wake', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Wallis', '" . mysql_real_escape_string($current_timestamp) . "'), ('Pacific/Yap', '" . mysql_real_escape_string($current_timestamp) . "'), ('Poland', '" . mysql_real_escape_string($current_timestamp) . "'), ('Portugal', '" . mysql_real_escape_string($current_timestamp) . "'), ('Singapore', '" . mysql_real_escape_string($current_timestamp) . "'), ('Turkey', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Alaska', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Aleutian', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Arizona', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Central', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/East-Indiana', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Eastern', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Hawaii', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Indiana-Starke', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Michigan', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Mountain', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Pacific', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Pacific-New', '" . mysql_real_escape_string($current_timestamp) . "'), ('US/Samoa', '" . mysql_real_escape_string($current_timestamp) . "'), ('Zulu', '" . mysql_real_escape_string($current_timestamp) . "');";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0023',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0023;

	}

	// upgrade database from 2.0023 to 2.0024
	if ($current_db_version == 2.0023) {

		$sql = "ALTER TABLE `settings` 
					CHANGE `timezone` `timezone` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Canada/Pacific'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0024',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0024;

	}

	// upgrade database from 2.0024 to 2.0025
	if ($current_db_version == 2.0024) {

		$sql = "CREATE TABLE IF NOT EXISTS `hosting` ( 
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(255) NOT NULL,
				`notes` longtext NOT NULL,
				`default_host` int(1) NOT NULL default '0',
				`active` int(1) NOT NULL default '1',
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO `hosting` 
					(`name`, `default_host`, `insert_time`) VALUES 
					('[no hosting]', 1, '" . mysql_real_escape_string($current_timestamp) . "');";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains`  
					ADD `hosting_id` int(10) NOT NULL default '1' AFTER `ip_id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM hosting
				WHERE name = '[no hosting]'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		while ($row = mysql_fetch_object($result)) {
			$temp_hosting_id = $row->id;
		}
		
		$sql = "UPDATE domains
				SET hosting_id = '" . mysql_real_escape_string($temp_hosting_id) . "',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
					CHANGE `owner_id` `owner_id` INT(5) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
					CHANGE `registrar_id` `registrar_id` INT(5) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
					CHANGE `account_id` `account_id` INT(5) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `domains` 
					CHANGE `dns_id` `dns_id` INT(5) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0025',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0025;

	}

	// upgrade database from 2.0025 to 2.0026
	if ($current_db_version == 2.0025) {

		$sql = "ALTER TABLE `user_settings` 
					ADD `display_domain_host` INT(1) NOT NULL DEFAULT '0' AFTER `display_domain_dns`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0026',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0026;

	}

	// upgrade database from 2.0026 to 2.0027
	if ($current_db_version == 2.0026) {

		$sql = "ALTER TABLE `registrar_accounts`  
					ADD `password` VARCHAR(100) NOT NULL AFTER `username`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0027',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0027;

	}

	// upgrade database from 2.0027 to 2.0028
	if ($current_db_version == 2.0027) {

		$sql = "ALTER TABLE `ssl_accounts`  
					ADD `password` VARCHAR(100) NOT NULL AFTER `username`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0028',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0028;

	}

	// upgrade database from 2.0028 to 2.0029
	if ($current_db_version == 2.0028) {

		$sql = "ALTER TABLE `dns`  
					ADD `ip1` VARCHAR(255) NOT NULL AFTER `dns10`,  
					ADD `ip2` VARCHAR(255) NOT NULL AFTER `ip1`,  
					ADD `ip3` VARCHAR(255) NOT NULL AFTER `ip2`,  
					ADD `ip4` VARCHAR(255) NOT NULL AFTER `ip3`,  
					ADD `ip5` VARCHAR(255) NOT NULL AFTER `ip4`,  
					ADD `ip6` VARCHAR(255) NOT NULL AFTER `ip5`,  
					ADD `ip7` VARCHAR(255) NOT NULL AFTER `ip6`,  
					ADD `ip8` VARCHAR(255) NOT NULL AFTER `ip7`,  
					ADD `ip9` VARCHAR(255) NOT NULL AFTER `ip8`,  
					ADD `ip10` VARCHAR(255) NOT NULL AFTER `ip9`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings`  
					ADD `expiration_email_days` INT(3) NOT NULL DEFAULT '60' AFTER `timezone`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0029',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0029;

	}

	// upgrade database from 2.0029 to 2.003
	if ($current_db_version == 2.0029) {

		$sql = "ALTER TABLE `domains`  
					ADD `notes_fixed_temp` INT(1) NOT NULL DEFAULT '0' AFTER `notes`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id, status, status_notes, notes
				FROM domains";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		while ($row = mysql_fetch_object($result)) {

			if ($row->status != "" || $row->status_notes != "" || $row->notes != "") {

				$full_status = "";
				$full_status_notes = "";
				$new_notes = "";
				
				if ($row->status != "") {
		
					$full_status .= "--------------------\r\n";
					$full_status .= "OLD STATUS - INSERTED " . mysql_real_escape_string($current_timestamp) . "\r\n";
					$full_status .= "The Status field was removed because it was redundant.\r\n";
					$full_status .= "--------------------\r\n";
					$full_status .= $row->status . "\r\n";
					$full_status .= "--------------------";
		
				} else {
					
					$full_status = "";
					
				}
		
				if ($row->status_notes != "") {
		
					$full_status_notes .= "--------------------\r\n";
					$full_status_notes .= "OLD STATUS NOTES - INSERTED " . mysql_real_escape_string($current_timestamp) . "\r\n";
					$full_status_notes .= "The Status Notes field was removed because it was redundant.\r\n";
					$full_status_notes .= "--------------------\r\n";
					$full_status_notes .= $row->status_notes . "\r\n";
					$full_status_notes .= "--------------------";
		
				} else {
					
					$full_status_notes = "";
					
				}
				
				if ($row->notes != "") {
					
					if ($full_status != "" && $full_status_notes != "") {
						
						$new_notes = $full_status . "\r\n\r\n" . $full_status_notes . "\r\n\r\n" . $row->notes;
	
					} elseif ($full_status != "" && $full_status_notes == "") {
						
						$new_notes = $full_status . "\r\n\r\n" . $row->notes;
	
					} elseif ($full_status == "" && $full_status_notes != "") {
						
						$new_notes = $full_status_notes . "\r\n\r\n" . $row->notes;
	
					} elseif ($full_status == "" && $full_status_notes == "") {
						
						$new_notes = $row->notes;
	
					}
					
				} elseif ($row->notes == "") {
	
					if ($full_status != "" && $full_status_notes != "") {
						
						$new_notes = $full_status . "\r\n\r\n" . $full_status_notes;
	
					} elseif ($full_status != "" && $full_status_notes == "") {
						
						$new_notes = $full_status;
	
					} elseif ($full_status == "" && $full_status_notes != "") {
						
						$new_notes = $full_status_notes;
	
					}
	
				}
				
				$sql_update = "UPDATE domains
							   SET notes = '" . trim(mysql_real_escape_string($new_notes)) . "',
							   	   notes_fixed_temp = '1',
								   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
							   WHERE id = '" . mysql_real_escape_string($row->id) . "'";
				$result_update = mysql_query($sql_update,$connection) or die(mysql_error());

			} else {

				$sql_update = "UPDATE domains
							   SET notes_fixed_temp = '1',
								   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
							   WHERE id = '" . mysql_real_escape_string($row->id) . "'";
				$result_update = mysql_query($sql_update,$connection) or die(mysql_error());
			}

		}
		
		$sql = "SELECT *
				FROM domains
				WHERE notes_fixed_temp = '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		if (mysql_num_rows($result) > 0) {
			
			echo "DATABASE UPDATE v2.003 FAILED: PLEASE CONTACT YOUR " . strtoupper($software_title) . " ADMINISTRATOR IMMEDIATELY";
			exit;
			
		} else {

			$sql = "ALTER TABLE `domains` 
						DROP `status`, 
						DROP `status_notes`,
						DROP `notes_fixed_temp`";
			$result = mysql_query($sql,$connection) or die(mysql_error());

		}
		
		$sql = "UPDATE settings
				SET db_version = '2.003',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.003;

	}

	// upgrade database from 2.003 to 2.0031
	if ($current_db_version == 2.003) {

		$sql = "ALTER TABLE `categories` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `currencies` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `dns` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `hosting` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ip_addresses` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `owners` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `registrars` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `registrar_accounts` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `segments` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_accounts` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_cert_types` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_providers` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_providers` 
					DROP `active`;";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0031',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0031;

	}

	// upgrade database from 2.0031 to 2.0032
	if ($current_db_version == 2.0031) {

		$sql = "ALTER TABLE `fees` 
				ADD `transfer_fee` FLOAT NOT NULL AFTER `renewal_fee`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_fees` 
				ADD `transfer_fee` FLOAT NOT NULL AFTER `renewal_fee`";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0032',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0032;

	}

	// upgrade database from 2.0032 to 2.0033
	if ($current_db_version == 2.0032) {

		$sql = "ALTER TABLE `ssl_fees` 
				DROP `transfer_fee`;";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0033',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0033;

	}

	// upgrade database from 2.0033 to 2.0034
	if ($current_db_version == 2.0033) {

		$sql = "ALTER TABLE `domains` 
				CHANGE `owner_id` `owner_id` INT(10) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `domains` 
				CHANGE `registrar_id` `registrar_id` INT(10) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `domains` 
				CHANGE `account_id` `account_id` INT(10) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `domains` 
				CHANGE `dns_id` `dns_id` INT(10) NOT NULL DEFAULT '1'";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `fees` 
				CHANGE `registrar_id` `registrar_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `registrar_accounts` 
				CHANGE `owner_id` `owner_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_accounts` 
				CHANGE `owner_id` `owner_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_certs` 
				CHANGE `owner_id` `owner_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_certs` 
				CHANGE `ssl_provider_id` `ssl_provider_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_certs` 
				CHANGE `account_id` `account_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_fees` 
				CHANGE `ssl_provider_id` `ssl_provider_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `ssl_fees` 
				CHANGE `type_id` `type_id` INT(10) NOT NULL";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0034',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0034;

	}

	// upgrade database from 2.0034 to 2.0035
	if ($current_db_version == 2.0034) {

		$sql = "ALTER DATABASE " . $dbname . " 
				CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE categories CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE currencies CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE dns CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE domains CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE fees CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE hosting CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ip_addresses CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE owners CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE registrars CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE registrar_accounts CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE segments CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE segment_data CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE settings CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_accounts CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_certs CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_cert_types CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_fees CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_providers CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE timezones CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE users CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE user_settings CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE categories CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE currencies CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE dns CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE domains CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE fees CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE hosting CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ip_addresses CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE owners CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE registrars CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE registrar_accounts CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE segments CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE segment_data CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE settings CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_accounts CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_certs CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_cert_types CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_fees CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE ssl_providers CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE timezones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE users CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE user_settings CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0035',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0035;

	}

	// upgrade database from 2.0035 to 2.0036
	if ($current_db_version == 2.0035) {

		$sql = "DROP TABLE `currency_data`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER DATABASE " . $dbname . " 
				CHARACTER SET utf8 
				DEFAULT CHARACTER SET utf8 
				COLLATE utf8_unicode_ci
				DEFAULT COLLATE utf8_unicode_ci;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `currencies`  
				ADD `symbol` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `conversion`,  
				ADD `symbol_order` INT(1) NOT NULL DEFAULT '0' AFTER `symbol`,  
				ADD `symbol_space` INT(1) NOT NULL DEFAULT '0' AFTER `symbol_order`,
				ADD `newly_inserted` INT(1) NOT NULL DEFAULT '1' AFTER `symbol_space`";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE currencies
				SET newly_inserted = '0',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_currency` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `email_address`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_currency` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `user_id`";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET default_currency = '" . $_SESSION['default_currency'] . "',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE user_settings
				SET default_currency = '" . $_SESSION['default_currency'] . "',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection);

		$sql = "INSERT INTO currencies
				(name, currency, symbol, insert_time) VALUES 
				('Albania Lek', 'ALL', 'Lek', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Afghanistan Afghani', 'AFN', '؋', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Argentina Peso', 'ARS', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Aruba Guilder', 'AWG', 'ƒ', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Australia Dollar', 'AUD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Azerbaijan New Manat', 'AZN', '" . 'ман' . "', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bahamas Dollar', 'BSD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Barbados Dollar', 'BBD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Belarus Ruble', 'BYR', 'p.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Belize Dollar', 'BZD', 'BZ$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bermuda Dollar', 'BMD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bolivia Boliviano', 'BOB', '\$b', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bosnia and Herzegovina Convertible Marka', 'BAM', 'KM', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Botswana Pula', 'BWP', 'P', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bulgaria Lev', 'BGN', 'лв', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Brazil Real', 'BRL', 'R$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Brunei Darussalam Dollar', 'BND', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Cambodia Riel', 'KHR', '៛', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Canada Dollar', 'CAD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Cayman Islands Dollar', 'KYD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Chile Peso', 'CLP', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('China Yuan Renminbi', 'CNY', '¥', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Colombia Peso', 'COP', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Costa Rica Colon', 'CRC', '₡', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Croatia Kuna', 'HRK', 'kn', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Cuba Peso', 'CUP', '₱', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Czech Republic Koruna', 'CZK', 'Kč', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Denmark Krone', 'DKK', 'kr', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Dominican Republic Peso', 'DOP', 'RD$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('East Caribbean Dollar', 'XCD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Egypt Pound', 'EGP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('El Salvador Colon', 'SVC', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Estonia Kroon', 'EEK', 'kr', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Euro Member Countries', 'EUR', '€', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Falkland Islands (Malvinas) Pound', 'FKP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Fiji Dollar', 'FJD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ghana Cedis', 'GHC', '¢', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Gibraltar Pound', 'GIP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Guatemala Quetzal', 'GTQ', 'Q', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Guernsey Pound', 'GGP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Guyana Dollar', 'GYD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Honduras Lempira', 'HNL', 'L', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Hong Kong Dollar', 'HKD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Hungary Forint', 'HUF', 'Ft', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Iceland Krona', 'ISK', 'kr', '" . mysql_real_escape_string($current_timestamp) . "'),
				('India Rupee', 'INR', 'Rs', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Indonesia Rupiah', 'IDR', 'Rp', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Iran Rial', 'IRR', '﷼', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Isle of Man Pound', 'IMP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Israel Shekel', 'ILS', '₪', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Jamaica Dollar', 'JMD', 'J$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Japan Yen', 'JPY', '¥', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Jersey Pound', 'JEP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Kazakhstan Tenge', 'KZT', 'лв', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Korea (North) Won', 'KPW', '₩', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Korea (South) Won', 'KRW', '₩', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Kyrgyzstan Som', 'KGS', 'лв', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Laos Kip', 'LAK', '₭', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Latvia Lat', 'LVL', 'Ls', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Lebanon Pound', 'LBP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Liberia Dollar', 'LRD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Lithuania Litas', 'LTL', 'Lt', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Macedonia Denar', 'MKD', 'ден', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Malaysia Ringgit', 'RM', 'RM', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Mauritius Rupee', 'MUR', '₨', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Mexico Peso', 'MXN', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Mongolia Tughrik', 'MNT', '₮', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Mozambique Metical', 'MZN', 'MT', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Namibia Dollar', 'NAD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Nepal Rupee', 'NPR', '₨', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Netherlands Antilles Guilder', 'ANG', 'ƒ', '" . mysql_real_escape_string($current_timestamp) . "'),
				('New Zealand Dollar', 'NZD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Nicaragua Cordoba', 'NIO', 'C$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Nigeria Naira', 'NGN', '₦', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Norway Krone', 'NOK', 'kr', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Oman Rial', 'OMR', '﷼', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Pakistan Rupee', 'PKR', '₨', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Panama Balboa', 'PAB', 'B/.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Paraguay Guarani', 'PYG', 'Gs', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Peru Nuevo Sol', 'PEN', 'S/.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Philippines Peso', 'PHP', '₱', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Poland Zloty', 'PLN', 'zł', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Qatar Riyal', 'QAR', '﷼', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Romania New Leu', 'RON', 'lei', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Russia Ruble', 'RUB', 'руб', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Saint Helena Pound', 'SHP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Saudi Arabia Riyal', 'SAR', '﷼', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Serbia Dinar', 'RSD', 'Дин.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Seychelles Rupee', 'SCR', '₨', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Singapore Dollar', 'SGD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Solomon Islands Dollar', 'SBD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Somalia Shilling', 'SOS', 'S', '" . mysql_real_escape_string($current_timestamp) . "'),
				('South Africa Rand', 'ZAR', 'R', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Sri Lanka Rupee', 'LKR', '₨', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Sweden Krona', 'SEK', 'kr', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Switzerland Franc', 'CHF', 'CHF', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Suriname Dollar', 'SRD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Syria Pound', 'SYP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Taiwan New Dollar', 'TWD', 'NT$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Thailand Baht', 'THB', '฿', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Trinidad and Tobago Dollar', 'TTD', 'TT$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Turkey Lira', 'TRY', '₤', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Tuvalu Dollar', 'TVD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ukraine Hryvna', 'UAH', '₴', '" . mysql_real_escape_string($current_timestamp) . "'),
				('United Kingdom Pound', 'GBP', '£', '" . mysql_real_escape_string($current_timestamp) . "'),
				('United States Dollar', 'USD', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Uruguay Peso', 'UYU', '\$U', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Uzbekistan Som', 'UZS', 'лв', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Venezuela Bolivar', 'VEF', 'Bs', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Viet Nam Dong', 'VND', '₫', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Yemen Rial', 'YER', '﷼', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Zimbabwe Dollar', 'ZWD', 'Z$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Emirati Dirham', 'AED', 'د.إ', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Malaysian Ringgit', 'MYR', 'RM', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Kuwaiti Dinar', 'KWD', 'ك', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Moroccan Dirham', 'MAD', 'م.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Iraqi Dinar', 'IQD', 'د.ع', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bangladeshi Taka', 'BDT', 'Tk', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bahraini Dinar', 'BHD', 'BD', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Kenyan Shilling', 'KES', 'KSh', '" . mysql_real_escape_string($current_timestamp) . "'),
				('CFA Franc', 'XOF', 'CFA', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Jordanian Dinar', 'JOD', 'JD', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Tunisian Dinar', 'TND', 'د.ت', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ghanaian Cedi', 'GHS', 'GH¢', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Central African CFA Franc BEAC', 'XAF', 'FCFA', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Algerian Dinar', 'DZD', 'دج', '" . mysql_real_escape_string($current_timestamp) . "'),
				('CFP Franc', 'XPF', 'F', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ugandan Shilling', 'UGX', 'USh', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Tanzanian Shilling', 'TZS', 'TZS', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ethiopian Birr', 'ETB', 'Br', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Georgian Lari', 'GEL', 'GEL', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Cuban Convertible Peso', 'CUC', 'CUC$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Burmese Kyat', 'MMK', 'K', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Libyan Dinar', 'LYD', 'LD', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Zambian Kwacha', 'ZMK', 'ZK', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Zambian Kwacha', 'ZMW', 'ZK', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Macau Pataca', 'MOP', 'MOP$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Armenian Dram', 'AMD', 'AMD', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Angolan Kwanza', 'AOA', 'Kz', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Papua New Guinean Kina', 'PGK', 'K', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Malagasy Ariary', 'MGA', 'Ar', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Ni-Vanuatu Vatu', 'VUV', 'VT', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Sudanese Pound', 'SDG', 'SDG', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Malawian Kwacha', 'MWK', 'MK', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Rwandan Franc', 'RWF', 'FRw', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Gambian Dalasi', 'GMD', 'D', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Maldivian Rufiyaa', 'MVR', 'Rf', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Congolese Franc', 'CDF', 'FC', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Djiboutian Franc', 'DJF', 'Fdj', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Haitian Gourde', 'HTG', 'G', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Samoan Tala', 'WST', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Guinean Franc', 'GNF', 'FG', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Cape Verdean Escudo', 'CVE', '$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Tongan Pa\'anga', 'TOP', 'T$', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Moldovan Leu', 'MDL', 'MDL', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Sierra Leonean Leone', 'SLL', 'Le', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Burundian Franc', 'BIF', 'FBu', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Mauritanian Ouguiya', 'MRO', 'UM', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Bhutanese Ngultrum', 'BTN', 'Nu.', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Swazi Lilangeni', 'SZL', 'SZL', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Tajikistani Somoni', 'TJS', 'TJS', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Turkmenistani Manat', 'TMT', 'm', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Basotho Loti', 'LSL', 'LSL', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Comoran Franc', 'KMF', 'CF', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Sao Tomean Dobra', 'STD', 'STD', '" . mysql_real_escape_string($current_timestamp) . "'),
				('Seborgan Luigino', 'SPL', 'SPL', '" . mysql_real_escape_string($current_timestamp) . "')";
		$result = mysql_query($sql,$connection);
		
		$sql = "SELECT id, currency
				FROM currencies
				WHERE newly_inserted = '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		while ($row = mysql_fetch_object($result)) {
			
			$sql_find_new = "SELECT id, symbol
							 FROM currencies
							 WHERE newly_inserted = '1'
							   AND currency = '" . $row->currency . "'";
			$result_find_new = mysql_query($sql_find_new,$connection);
			$total_results = mysql_num_rows($result_find_new);
			
			while ($row_find_new = mysql_fetch_object($result_find_new)) {
			
				if ($total_results > 0) {
					
					$sql_update_old = "UPDATE currencies
									   SET symbol = '" . $row_find_new->symbol . "'
									   WHERE id = '" . $row->id . "'";
					$result_update_old = mysql_query($sql_update_old,$connection);

					$sql_delete_new = "DELETE FROM currencies
									   WHERE id = '" . $row_find_new->id . "'";
					$result_delete_new = mysql_query($sql_delete_new,$connection);

				}
				
			}

		}

		$sql = "ALTER TABLE `currencies` 
				DROP `newly_inserted`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0036',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0036;

	}

	// upgrade database from 2.0036 to 2.0037
	if ($current_db_version == 2.0036) {
		
		$sql = "SELECT currency
				FROM currencies
				WHERE default_currency = '1'";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) { $temp_currency = $row->currency; }
		
		$sql = "UPDATE settings
				SET default_currency = '" . $temp_currency . "'";
		$result = mysql_query($sql,$connection);
		
		$_SESSION['default_currency'] = $temp_currency;

		$sql = "SELECT name, symbol, symbol_order, symbol_space
				FROM currencies
				WHERE currency = '" . $_SESSION['default_currency'] . "'";
		$result = mysql_query($sql,$connection);
	
		while ($row = mysql_fetch_object($result)) {
			$_SESSION['default_currency_name'] = $row->name;
			$_SESSION['default_currency_symbol'] = $row->symbol;
			$_SESSION['default_currency_symbol_order'] = $row->symbol_order;
			$_SESSION['default_currency_symbol_space'] = $row->symbol_space;
		}

		$sql = "ALTER TABLE `currencies` 
				DROP `default_currency`;";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `user_settings` 
				DROP `default_currency`;";
		$result = mysql_query($sql,$connection);
		
		$sql = "UPDATE settings
				SET db_version = '2.0037',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0037;

	}

	// upgrade database from 2.0037 to 2.0038
	if ($current_db_version == 2.0037) {

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_currency` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL after user_id";
		$result = mysql_query($sql,$connection);
		
		$sql = "SELECT default_currency
				FROM settings";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			$temp_default_currency = $row->default_currency;
			$_SESSION['default_currency'] = $row->default_currency;
		}
		
		$sql = "SELECT name, symbol, symbol_order, symbol_space
				FROM currencies
				WHERE currency = '" . $_SESSION['default_currency'] . "'";
		$result = mysql_query($sql,$connection);
		
		while ($row = mysql_fetch_object($result)) {

			$_SESSION['default_currency_name'] = $row->name; 
			$_SESSION['default_currency_symbol'] = $row->symbol; 
			$_SESSION['default_currency_symbol_order'] = $row->symbol_order; 
			$_SESSION['default_currency_symbol_space'] = $row->symbol_space; 

		}

		$sql = "UPDATE user_settings
				SET default_currency = '" . $temp_default_currency . "'";
		$result = mysql_query($sql,$connection);

		$sql = "CREATE TABLE IF NOT EXISTS `currency_conversions` (
				`id` int(10) NOT NULL auto_increment,
				`currency_id` int(10) NOT NULL,
				`user_id` int(10) NOT NULL,
				`conversion` float NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection);
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);
			
		while ($row = mysql_fetch_object($result)) {
			
			$sql_conversion = "SELECT id, conversion
							   FROM currencies
							   WHERE conversion != '0'";
			$result_conversion = mysql_query($sql_conversion,$connection);
			
			while ($row_conversion = mysql_fetch_object($result_conversion)) {
				
				$sql_insert = "INSERT INTO currency_conversions
							   (currency_id, user_id, conversion, insert_time, update_time) VALUES 
							   ('" . $row_conversion->id . "', '" . $row->id . "', '" . $row_conversion->conversion . "', '" . mysql_real_escape_string($current_timestamp) . "', '" . mysql_real_escape_string($current_timestamp) . "')";
				$result_insert = mysql_query($sql_insert,$connection);
				
			}
			
		}

		$sql = "ALTER TABLE `currencies` 
				DROP `conversion`;";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET db_version = '2.0038',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0038;

	}

	// upgrade database from 2.0038 to 2.0039
	if ($current_db_version == 2.0038) {


		$sql = "ALTER TABLE `ssl_certs`  
				ADD `ip_id` int(10) NOT NULL AFTER `type_id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs`  
				ADD `cat_id` int(10) NOT NULL AFTER `ip_id`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id, cat_id, ip_id
				FROM domains";
		$result = mysql_query($sql,$connection);
		
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE ssl_certs
						   SET cat_id = '" . $row->cat_id . "',
						   	   ip_id = '" . $row->ip_id . "',
							   update_time = '" . mysql_real_escape_string($current_timestamp) . "'
						   WHERE domain_id = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);
			
		}

		$sql = "ALTER TABLE `user_settings`  
				ADD `display_ssl_ip` int(1) NOT NULL default '0' AFTER `display_ssl_expiry_date`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `display_ssl_category` int(1) NOT NULL default '0' AFTER `display_ssl_ip`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0039',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0039;

	}

	// upgrade database from 2.0039 to 2.004
	if ($current_db_version == 2.0039) {

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_category` INT(10) NOT NULL default '1' AFTER `default_currency`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_category` INT(10) NOT NULL default '1' AFTER `default_currency`";
		$result = mysql_query($sql,$connection);
		
		$sql = "SELECT id
				FROM categories
				WHERE default_category = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_category = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_category = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);
			
			$_SESSION['default_category'] = $row->id;
			$_SESSION['system_default_category'] = $row->id;

		}

		$sql = "ALTER TABLE `categories` 
				DROP `default_category`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_dns` INT(10) NOT NULL default '1' AFTER `default_category`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_dns` INT(10) NOT NULL default '1' AFTER `default_category`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM dns
				WHERE default_dns = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_dns = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_dns = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_dns'] = $row->id;
			$_SESSION['system_default_dns'] = $row->id;

		}

		$sql = "ALTER TABLE `dns` 
				DROP `default_dns`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_host` INT(10) NOT NULL default '1' AFTER `default_dns`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_host` INT(10) NOT NULL default '1' AFTER `default_dns`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM hosting
				WHERE default_host = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_host = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_host = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_host'] = $row->id;
			$_SESSION['system_default_host'] = $row->id;

		}

		$sql = "ALTER TABLE `hosting` 
				DROP `default_host`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_ip_address` INT(10) NOT NULL default '1' AFTER `default_host`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_ip_address` INT(10) NOT NULL default '1' AFTER `default_host`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM ip_addresses
				WHERE default_ip_address = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_ip_address = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_ip_address = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_ip_address'] = $row->id;
			$_SESSION['system_default_ip_address'] = $row->id;
			
		}

		$sql = "ALTER TABLE `ip_addresses` 
				DROP `default_ip_address`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_owner` INT(10) NOT NULL default '1' AFTER `default_ip_address`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_owner` INT(10) NOT NULL default '1' AFTER `default_ip_address`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM owners
				WHERE default_owner = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_owner = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_owner = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_owner'] = $row->id;
			$_SESSION['system_default_owner'] = $row->id;
			
		}

		$sql = "ALTER TABLE `owners` 
				DROP `default_owner`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_registrar` INT(10) NOT NULL default '1' AFTER `default_owner`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_registrar` INT(10) NOT NULL default '1' AFTER `default_owner`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM registrars
				WHERE default_registrar = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_registrar = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_registrar = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_registrar'] = $row->id;
			$_SESSION['system_default_registrar'] = $row->id;

		}

		$sql = "ALTER TABLE `registrars` 
				DROP `default_registrar`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_registrar_account` INT(10) NOT NULL default '1' AFTER `default_registrar`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_registrar_account` INT(10) NOT NULL default '1' AFTER `default_registrar`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM registrar_accounts
				WHERE default_account = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_registrar_account = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_registrar_account = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_registrar_account'] = $row->id;
			$_SESSION['system_default_registrar_account'] = $row->id;

		}

		$sql = "ALTER TABLE `registrar_accounts` 
				DROP `default_account`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_ssl_provider_account` INT(10) NOT NULL default '1' AFTER `default_registrar_account`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_ssl_provider_account` INT(10) NOT NULL default '1' AFTER `default_registrar_account`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM ssl_accounts
				WHERE default_account = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_ssl_provider_account = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_ssl_provider_account = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_ssl_provider_account'] = $row->id;
			$_SESSION['system_default_ssl_provider_account'] = $row->id;

		}

		$sql = "ALTER TABLE `ssl_accounts` 
				DROP `default_account`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_ssl_type` INT(10) NOT NULL default '1' AFTER `default_ssl_provider_account`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_ssl_type` INT(10) NOT NULL default '1' AFTER `default_ssl_provider_account`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM ssl_cert_types
				WHERE default_type = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_ssl_type = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_ssl_type = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_ssl_type'] = $row->id;
			$_SESSION['system_default_ssl_type'] = $row->id;

		}

		$sql = "ALTER TABLE `ssl_cert_types` 
				DROP `default_type`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_ssl_provider` INT(10) NOT NULL default '1' AFTER `default_ssl_type`";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings`  
				ADD `default_ssl_provider` INT(10) NOT NULL default '1' AFTER `default_ssl_type`";
		$result = mysql_query($sql,$connection);

		$sql = "SELECT id
				FROM ssl_providers
				WHERE default_provider = '1'
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) {
			
			$sql_update = "UPDATE user_settings
						   SET default_ssl_provider = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$sql_update = "UPDATE settings
						   SET default_ssl_provider = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection);

			$_SESSION['default_ssl_provider'] = $row->id;
			$_SESSION['system_default_ssl_provider'] = $row->id;

		}

		$sql = "ALTER TABLE `ssl_providers` 
				DROP `default_provider`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings`  
				ADD `default_timezone` VARCHAR(50) NOT NULL DEFAULT 'Canada/Pacific' AFTER `default_currency`";
		$result = mysql_query($sql,$connection);

		$sql = "UPDATE settings
				SET default_timezone = timezone";
		$result = mysql_query($sql,$connection);

		$sql = "ALTER TABLE `settings` 
				DROP `timezone`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings`  
				ADD `default_timezone` VARCHAR(50) NOT NULL DEFAULT 'Canada/Pacific' AFTER `default_currency`";
		$result = mysql_query($sql,$connection);
		
		$sql = "SELECT default_timezone
				FROM settings
				ORDER BY id desc
				LIMIT 1";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) { $temp_default_system_timezone = $row->default_timezone; }
		
		$sql = "UPDATE user_settings
				SET default_timezone = '" . $temp_default_system_timezone . "'";
		$result = mysql_query($sql,$connection);

		$_SESSION['default_timezone'] = $temp_default_system_timezone;
		$_SESSION['system_default_timezone'] = $temp_default_system_timezone;

		$sql = "ALTER TABLE `settings` 
				DROP `default_currency`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				DROP `default_timezone`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.004',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.004;

	}

	// upgrade database from 2.004 to 2.0041
	if ($current_db_version == 2.004) {

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_category` `default_category_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_ip_address` `default_ip_address_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_owner` `default_owner_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				ADD `default_category_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_category_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "ALTER TABLE `user_settings` 
				ADD `default_ip_address_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_ip_address_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				ADD `default_owner_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_owner_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id, default_category_domains, default_ip_address_domains, default_owner_domains
				FROM user_settings";
		$result = mysql_query($sql,$connection);
		
		while ($row = mysql_fetch_object($result)) {

			$sql_update = "UPDATE user_settings
						   SET default_category_ssl = '" . $row->default_category_domains . "',
						   	   default_ip_address_ssl = '" . $row->default_ip_address_domains . "',
							   default_owner_ssl = '" . $row->default_owner_domains . "'
						   WHERE id = '" . $row->id . "'";
			$result_update = mysql_query($sql_update,$connection) or die(mysql_error());

		}

		$sql = "SELECT default_category_domains, default_ip_address_domains, default_owner_domains
				FROM user_settings
				WHERE user_id = '" . $_SESSION['user_id'] . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		while ($row = mysql_fetch_object($result)) {

			$default_category_domains = $row->default_category_domains;
			$default_ip_address_domains = $row->default_ip_address_domains;
			$default_owner_domains = $row->default_owner_domains;

		}

		$_SESSION['default_category_domains'] = $default_category_domains;
		$_SESSION['default_category_ssl'] = $default_category_domains;
		$_SESSION['default_ip_address_domains'] = $default_ip_address_domains;
		$_SESSION['default_ip_address_ssl'] = $default_ip_address_domains;
		$_SESSION['default_owner_domains'] = $default_owner_domains;
		$_SESSION['default_owner_ssl'] = $default_owner_domains;

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_category` `default_category_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "ALTER TABLE `settings` 
				CHANGE `default_ip_address` `default_ip_address_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_owner` `default_owner_domains` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				ADD `default_category_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_category_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "ALTER TABLE `settings` 
				ADD `default_ip_address_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_ip_address_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				ADD `default_owner_ssl` INT(10) NOT NULL DEFAULT '0' AFTER `default_owner_domains`";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT default_category_domains, default_ip_address_domains, default_owner_domains
				FROM settings";
		$result = mysql_query($sql,$connection);
		
		while ($row = mysql_fetch_object($result)) {

			$default_category_domains = $row->default_category_domains;
			$default_ip_address_domains = $row->default_ip_address_domains;
			$default_owner_domains = $row->default_owner_domains;

		}

		$sql = "UPDATE settings
				SET default_category_ssl = '" . $default_category_domains . "',
					default_ip_address_ssl = '" . $default_ip_address_domains . "',
					default_owner_ssl = '" . $default_owner_domains . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$_SESSION['system_default_category_domains'] = $default_category_domains;
		$_SESSION['system_default_category_ssl'] = $default_category_domains;
		$_SESSION['system_default_ip_address_domains'] = $default_ip_address_domains;
		$_SESSION['system_default_ip_address_ssl'] = $default_ip_address_domains;
		$_SESSION['system_default_owner_domains'] = $default_owner_domains;
		$_SESSION['system_default_owner_ssl'] = $default_owner_domains;

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_dns` `default_dns` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_host` `default_host` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_registrar` `default_registrar` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_registrar_account` `default_registrar_account` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_ssl_provider_account` `default_ssl_provider_account` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_ssl_type` `default_ssl_type` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `default_ssl_provider` `default_ssl_provider` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_dns` `default_dns` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_host` `default_host` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_registrar` `default_registrar` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_registrar_account` `default_registrar_account` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_ssl_provider_account` `default_ssl_provider_account` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_ssl_type` `default_ssl_type` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `user_settings` 
				CHANGE `default_ssl_provider` `default_ssl_provider` INT(10) NOT NULL DEFAULT '0'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0041',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0041;

	}

	// upgrade database from 2.0041 to 2.0042
	if ($current_db_version == 2.0041) {

		// This section was made redunant by DB update v2.005
		/*
		$sql = "CREATE TABLE IF NOT EXISTS `updates` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`update` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "INSERT INTO updates
				(name, `update`, insert_time, update_time) VALUES 
				('Domain Manager now contains a Software Updates section!', 'After upgrading Domain Manager I\'m sure it would be nice to know what new features have been added, as well as any important changes to the software that you should know about, so I\'ve added a Software Updates section that chronicles the most important and most useful new features. Now after an upgrade you can simply visit the Software Updates section and view a list of the updates since your previous version.', '2013-05-04 00:00:00', '2013-05-04 00:00:00'),
				('Overhaul of Domain Manager Settings Complete!', 'Over the past few months the Domain Manager settings have been undergoing a complete overhaul. The changes include but are not limited to making currency conversions user-based instead of system-based, updating all Domain & SSL default settings to be user-based instead of system-based, separating out Category, IP Address and Owner settings so that Domains & SSLs have thier own options instead of sharing them, adding support for saving passwords for Domain Registrar & SSL Provider accounts, removing the redundant Status and Status Notes fields from the Domains section, and so on.<BR><BR>I\'m constantly trying to improve the software and make it more user-friendly, so if you have any suggestions or feedback feel free to drop me a line at <a class=\"invisiblelink\" href=\"mailto:greg@chetcuti.com\">greg@chetcuti.com</a>.', '2013-05-02 00:00:00', '2013-05-02 00:00:00'),
				('Currencies have been updated to be user-based instead of system-based', 'Now that Currencies have been re-worked to be user-based, every user in the system can set their own default currency, and this currency will be used for them throughout the system. Every setting, webpage, and report in the Domain Manager system will automatically be converted to display monetary values using the user\'s default currency.', '2013-04-29 00:00:00', '2013-04-29 00:00:00'),
				('Domain Manager has been converted to UTF-8', 'The entire Domain Manager system has been converted to use the UTF-8 character set in order to allow for support of non-ASCII characters, such as the characters found in some IDNs (Internationalized Domain Names).', '2013-04-27 00:00:01', '2013-04-27 00:00:01'),
				('Cron job added for automating currency conversions at regular intervals', 'Never worry about having outdated exchange rates again! Domain Manager now includes a cron job that automates currency conversions. This means you can have the cron job set to run overnight, and when you go to use the Domain Manager software in the morning your currency conversions will already be completely up-to-date.<BR><BR>If you would like to use this feature, just let your system administrator know so that they can schedule the cron job to run.', '2013-04-27 00:00:00', '2013-04-27 00:00:00'),
				('Reporting section added', 'Domain Manager now includes a handful of reports that can give you valuable insight into your data, and I\'m always on the lookout for more reports that can be added. If you have any new report ideas, or any suggestions for the current reports, feel free to drop me a line at <a class=\"invisiblelink\" href=\"mailto:greg@chetcuti.com\">greg@chetcuti.com</a>.', '2013-04-25 00:00:00', '2013-04-25 00:00:00'),
				('Added a \'view full notes\' feature to the Domain and SSL Cert edit pages', 'When editing a Domain or SSL certificate, if you want to view the notes but scrolling through the text box just isn\'t your thing, you can now click on a link to view the full notes on a separate page, making them much easier to read.', '2013-04-24 00:00:00', '2013-04-24 00:00:00'),
				('Remove the (redundant) Domain Status and Status Notes fields', 'Although the Domain Status & Status Notes fields were removed because they were redundant, if you had data stored in either of these fields it would have been appended to the primary Notes field when your Domain Manager database was upgraded. So don\'t worry, dropping these two fields didn\'t cause you to lose any data.', '2013-04-20 00:00:00', '2013-04-20 00:00:00'),
				('Update the expiration email so that the System Adminstrator can set the number of days in the future to display in the email', 'Previously when the daily expiration emails were sent out to users they would automatically include the next 60 days of expirations, but this has now been converted to a system setting so that your system administrator can now specify the number of days to include in the email.', '2013-04-19 00:00:01', '2013-04-19 00:00:01'),
				('A password field has now been added to Registrar & SSL Provider accounts so that passwords can be managed through Domain Manager', '', '2013-04-19 00:00:00', '2013-04-19 00:00:00'),
				('A new \'Web Hosting\' section has been added to the UI so that you can now keep track of your web hosting providers within Domain Manager', '', '2013-04-17 00:00:00', '2013-04-17 00:00:00'),
				('Cron job added for sending an email to users about upcoming Domain and SSL Certificate renewals', 'A cron job has now been added to send a daily email to users letting them know about upcoming domain and SSL expirations, and users can subscribe and unsubscribe from this email through their Control Panel.<BR><BR>If you would like to use this feature, just let your system administrator know so that they can schedule the cron job to run.', '2013-04-15 00:00:00', '2013-04-15 00:00:00'),
				('A logo has now been added to the Domain Manager software in order to pretty things up a little bit', '', '2013-04-10 00:00:00', '2013-04-10 00:00:00'),
				('The Domain & SSL search pages have been updated to allow for the exporting of results', '', '2013-04-07 00:00:00', '2013-04-07 00:00:00'),
				('Update the Segments UI to give the user a lot more information and flexibility', 'Now when filtering your domains using a segment, Domain Manager will tell you which domains in the segment are stored in your Domain Manager (indicating whether or not the domain is active or inactive), as well as which domains don\'t match, and lastly it will tell you which domains matched but were filtered out based on your other search criteria. Each of the resulting lists can be easily viewed and exported for your convenience.<BR><BR>It took quite a bit of work to get this feature implemented, but the segment filtering just felt incomplete without it. It was still a very useful feature, but now it\'s incredibly powerful, and I hope to add on the functionality in the future.', '2013-04-07 00:00:00', '2013-04-07 00:00:00'),
				('Test Data System removed, Demo launched', 'In order to focus on the development of the actual Domain Manager software, I\'ve decided to remove the Test Data System entirely. Although this system allowed users to easily generate some test data and get a feel for the software, it complicated the development process and added unecessary overhead to the software as a whole. Most importantly, it took me away from adding other, more useful features to the core software.<BR><BR>Now instead of testing the software by installing it and generating the test data, you can simply visit <a class=\"invisiblelink\" target=\"_blank\" href=\"http://demos.aysmedia.com/domainmanager/\">http://demos.aysmedia.com/domainmanager/</a> to take Domain Manager for a test drive.', '2013-04-06 00:00:00', '2013-04-06 00:00:00'),
				('A new \'IP Address\' section has been added to the UI so that you can keep track of all your IP Addresses within Domain Manager', '', '2013-03-26 00:00:00', '2013-03-26 00:00:00'),
				('Support has been added for automatic currency updates!', 'Thanks to Yahoo! Finance\'s free API, I\'m happy to announce that currency conversions have been completely automated. Now instead of having to manually update the conversions one-by-one on a regular basis to ensure proper financial reporting, all you have to do is make sure your default currency is set and your conversion rates will be updated automatically and seemlessly in the background while you use the software.<BR><BR>To say that this feature pleases me would be a huge understatement. I personally use the Domain Manager software on a daily basis, and updating the currency conversions manually was always such a boring, tedious task, and I\'m happy that nobody will ever have to go through that process ever again. If I could give Yahoo! Finance a big hug, I would.', '2013-03-20 00:00:01', '2013-03-20 00:00:01'),
				('Domain Manager now contains a Software Updates section!', '<em>[This feature was implemented on 2013-05-04, but it seemed appropriate that the very first post in the Software Updates section be information about the new section itself, so the post was duplicated and backdated]</em><BR><BR>After upgrading Domain Manager I\'m sure it would be nice to know what new features have been added, as well as any important changes to the software that you should know about, so I\'ve added a Software Updates section that chronicles the most important and most useful new features. Now after an upgrade you can simply visit the Software Updates section and view a list of the updates since your previous version.', '2013-03-20 00:00:00', '2013-03-20 00:00:00')";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "CREATE TABLE IF NOT EXISTS update_data (
				`id` int(10) NOT NULL auto_increment,
				`user_id` int(10) NOT NULL,
				`update_id` int(10) NOT NULL,
				`insert_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);

		while ($row = mysql_fetch_object($result)) {

			$sql_updates = "SELECT id
							FROM `updates`";
			$result_updates = mysql_query($sql_updates,$connection);

			while ($row_updates = mysql_fetch_object($result_updates)) {

				$sql_insert = "INSERT INTO 
							   update_data
							   (user_id, update_id, insert_time) VALUES 
							   ('" . $row->id . "', '" . $row_updates->id . "', '" . mysql_real_escape_string($current_timestamp) . "')";
				$result_insert = mysql_query($sql_insert,$connection);

			}

		}

		$_SESSION['are_there_updates'] = "1";

		$sql = "UPDATE settings
				SET db_version = '2.0042',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		*/

		$current_db_version = 2.0042;

	}

	// upgrade database from 2.0042 to 2.0043
	if ($current_db_version == 2.0042) {

		$sql = "ALTER TABLE `segments` 
				CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0043',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0043;

	}

	// upgrade database from 2.0043 to 2.0044
	if ($current_db_version == 2.0043) {

		$sql = "ALTER TABLE `owners` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `categories` 
				CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `categories` 
				CHANGE `stakeholder` `stakeholder` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `hosting` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ip_addresses` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ip_addresses` 
				CHANGE `ip` `ip` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ip_addresses` 
				CHANGE `rdns` `rdns` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrar_accounts` 
				CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrar_accounts` 
				CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrars` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `registrars` 
				CHANGE `url` `url` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_accounts` 
				CHANGE `username` `username` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_accounts` 
				CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_providers` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_providers` 
				CHANGE `url` `url` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_cert_types` 
				CHANGE `type` `type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `users` 
				CHANGE `username` `username` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `users` 
				CHANGE `email_address` `email_address` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `settings` 
				CHANGE `email_address` `email_address` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "ALTER TABLE `ssl_certs` 
				CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0044',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0044;

	}

	// upgrade database from 2.0044 to 2.0045
	if ($current_db_version == 2.0044) {

		$sql = "ALTER TABLE `segments` 
				CHANGE `name` `name` VARCHAR(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0045',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0045;

	}

	// upgrade database from 2.0045 to 2.0046
	if ($current_db_version == 2.0045) {

		// This section was made redunant by DB update v2.005
		/*
		$sql = "INSERT INTO updates
				(name, `update`, insert_time, update_time) VALUES 
				('An Export option has been added to all Asset pages', '', '2013-05-06 00:00:00', '2013-05-06 00:00:00')";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);

		while ($row = mysql_fetch_object($result)) {

			$sql_updates = "SELECT id
							FROM `updates`
							WHERE name = 'An Export option has been added to all Asset pages'
							  AND insert_time = '2013-05-06 00:00:00'";
			$result_updates = mysql_query($sql_updates,$connection);

			while ($row_updates = mysql_fetch_object($result_updates)) {

				$sql_insert = "INSERT INTO 
							   update_data
							   (user_id, update_id, insert_time) VALUES 
							   ('" . $row->id . "', '" . $row_updates->id . "', '" . mysql_real_escape_string($current_timestamp) . "')";
				$result_insert = mysql_query($sql_insert,$connection);

			}

		}
		
		$_SESSION['are_there_updates'] = "1";

		$sql = "UPDATE settings
				SET db_version = '2.0046',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		*/
		
		$current_db_version = 2.0046;

	}

	// upgrade database from 2.0046 to 2.0047
	if ($current_db_version == 2.0046) {

		$sql = "ALTER TABLE `hosting`  
				ADD `url` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL after name";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "UPDATE settings
				SET db_version = '2.0047',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$current_db_version = 2.0047;

	}

	// upgrade database from 2.0047 to 2.0048
	if ($current_db_version == 2.0047) {

		$sql = "CREATE TABLE IF NOT EXISTS `custom_field_types` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "INSERT INTO custom_field_types
				(id, name, insert_time) VALUES 
				(1, 'Check Box', '" . mysql_real_escape_string($current_timestamp) . "'),
				(2, 'Text', '" . mysql_real_escape_string($current_timestamp) . "'),
				(3, 'Text Area', '" . mysql_real_escape_string($current_timestamp) . "')";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "CREATE TABLE IF NOT EXISTS `domain_fields` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(75) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`field_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`type_id` int(10) NOT NULL,
				`description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`notes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "CREATE TABLE IF NOT EXISTS `domain_field_data` (
				`id` int(10) NOT NULL auto_increment,
				`domain_id` int(10) NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM domains";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		while ($row = mysql_fetch_object($result)) {
			
			$full_id_string .= "('" . $row->id . "', '" . mysql_real_escape_string($current_timestamp) . "'), ";
			
		}

		$full_id_string_formatted = substr($full_id_string, 0, -2);
		
		$sql = "INSERT INTO domain_field_data
				(domain_id, insert_time) VALUES 
				" . $full_id_string_formatted . "";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$full_id_string = "";
		$full_id_string_formatted = "";

		$sql = "CREATE TABLE IF NOT EXISTS `ssl_cert_fields` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(75) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`field_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`type_id` int(10) NOT NULL,
				`description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`notes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "CREATE TABLE IF NOT EXISTS `ssl_cert_field_data` (
				`id` int(10) NOT NULL auto_increment,
				`ssl_id` int(10) NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM ssl_certs";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		while ($row = mysql_fetch_object($result)) {
			
			$full_id_string .= "('" . $row->id . "', '" . mysql_real_escape_string($current_timestamp) . "'), ";
			
		}

		$full_id_string_formatted = substr($full_id_string, 0, -2);
		
		$sql = "INSERT INTO ssl_cert_field_data
				(ssl_id, insert_time) VALUES 
				" . $full_id_string_formatted . "";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		// This section was made redunant by DB update v2.005
		/*
		$sql = "INSERT INTO updates
				(name, `update`, insert_time, update_time) VALUES 
				('You can now create Custom Domain & SSL Fields!', 'In an effort to allow users more flexibility, as well as track as much data as possible, I\'ve implemented Custom Domain & SSL Fields. Now if there\'s information you want to track for a domain or SSL certificate but the field doesn\'t exist in Domain Manager, you can just add it yourself!<BR><BR>For example, if you wanted to keep track of which domains are currenty setup in Google Analytics, you could create a new Google Analytics check box field and start tracking this information for each of your domains. Or if you were working in a corporate environment and wanted to keep a record of who purchased each of your SSL certificates, you could create a Purchaser Name text field and keep track of this information for every one of your SSL certificates. Combine custom fields with the ability to update them with the Bulk Updater, and the sky\'s the limit in regards to what data you can easily track! (the Bulk Updater currently only supports domains, not SSL certificates)<BR><BR>And when you export your domain & SSL data, the information contained in your custom fields will automatically be included in the exported data.', '2013-05-25 17:00:00', '2013-05-25 17:00:00')";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$sql = "SELECT id
				FROM `updates`
				WHERE name = 'You can now create Custom Domain & SSL Fields!'
				  AND insert_time = '2013-05-25 17:00:00'";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) { $temp_update_id = $row->id; }
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);

		while ($row = mysql_fetch_object($result)) {

			$sql_insert = "INSERT INTO 
						   update_data
						   (user_id, update_id, insert_time) VALUES 
						   ('" . $row->id . "', '" . $temp_update_id . "', '" . mysql_real_escape_string($current_timestamp) . "')";
			$result_insert = mysql_query($sql_insert,$connection);

		}

		$_SESSION['are_there_updates'] = "1";

		$sql = "UPDATE settings
				SET db_version = '2.0048',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		*/

		$current_db_version = 2.0048;

	}

	// upgrade database from 2.0048 to 2.0049
	if ($current_db_version == 2.0048) {

		$sql = "CREATE TABLE IF NOT EXISTS `dw_servers` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`host` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`protocol` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`port` int(5) NOT NULL,
				`username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`hash` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`notes` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`dw_accounts` int(10) NOT NULL,
				`dw_dns_zones` int(10) NOT NULL,
				`dw_dns_records` int(10) NOT NULL,
				`build_status` int(1) NOT NULL default '0',
				`build_start_time` datetime NOT NULL,
				`build_end_time` datetime NOT NULL,
				`build_time` int(10) NOT NULL default '0',
				`has_ever_been_built` int(1) NOT NULL default '0',
				`build_status_overall` int(1) NOT NULL default '0',
				`build_start_time_overall` datetime NOT NULL,
				`build_end_time_overall` datetime NOT NULL,
				`build_time_overall` int(10) NOT NULL default '0',
				`has_ever_been_built_overall` int(1) NOT NULL default '0',
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		// This section was made redunant by DB update v2.005
		/*
		$sql = "INSERT INTO updates
				(name, `update`, insert_time, update_time) VALUES 
				('Domain Manager now includes a Data Warehouse for importing data', 'Domain Manager now has a data warehouse framework built right into it, which allows you to import the data stored on your web servers. Currently the only web servers that are supported are ones that run WHM/cPanel, but I also intend on adding support for Plesk and other systems once I’ve ironed out all the kinks in the framework.<BR><BR>The data warehouse is used for informational purposes only, and you will see its data referenced throughout Domain Manager where applicable. For example, if a domain you’re editing has information stored in your data warehouse, the system will automatically match them up and display the additional information for you, giving you even more insight into your data. You can also view, export, and run reports on the information in your data warehouse.<BR><BR>The following WHM data is currently supported, but my end goal is to have every piece of WHM information that can be retrieved via the API stored in the data warehouse.<BR><BR><strong>ACCOUNTS</strong><BR>Domain, IP Address, Owner, User, Contact Email, Plan, Theme, Shell, Partition, Disk Limit, Disk Usage, Max Addons, Max FTP Accounts, Max Email Lists, Max Parked Domains, Max POP Accounts, Max SQL Accounts, Max Subdomains, Creation Date, Suspend Status, Suspend Reason, Suspend Time, Max Email Per Hour, Failed Email % Before Defer, Min Failed Email # Before Defer<BR><BR><strong>DNS ZONES</strong><BR>Zone File Name, Original/Primary Source of Zone Data, Admin Email, Serial #, Refresh, Retry, Expiry, Minimum TTL, Authoritative Name Server<BR><BR><strong>DNS RECORDS</strong><BR>TTL, Class, Type, IP Address, CNAME, Mail Server, Mail Server Priority, TXT Data, Line # of Zone, # of Lines, RAW Data<BR><BR><font class=\"default_highlight\">NOTE:</font> Importing your server into the data warehouse will not modify any of your Domain Manager data.', '2013-06-01 1:00:00', '2013-06-01 1:00:00')";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM `updates`
				WHERE name = 'Domain Manager now includes a Data Warehouse for importing data'
				  AND insert_time = '2013-06-01 1:00:00'";
		$result = mysql_query($sql,$connection);
		while ($row = mysql_fetch_object($result)) { $temp_update_id = $row->id; }
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);
		
		while ($row = mysql_fetch_object($result)) {
		
			$sql_insert = "INSERT INTO 
						   update_data
						   (user_id, update_id, insert_time) VALUES 
						   ('" . $row->id . "', '" . $temp_update_id . "', '" . mysql_real_escape_string($current_timestamp) . "')";
			$result_insert = mysql_query($sql_insert,$connection);
		
		}
		
		$_SESSION['are_there_updates'] = "1";
		*/

		$sql = "UPDATE settings
				SET db_version = '2.0049',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$current_db_version = 2.0049;

	}

	// upgrade database from 2.0049 to 2.005
	if ($current_db_version == 2.0049) {

		$sql = "DROP TABLE IF EXISTS `updates`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
	
		$sql = "DROP TABLE IF EXISTS `update_data`;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
	
		$sql = "CREATE TABLE IF NOT EXISTS `updates` (
				`id` int(10) NOT NULL auto_increment,
				`name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`update` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				`insert_time` datetime NOT NULL,
				`update_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
	
		$sql = "INSERT INTO updates
				(name, `update`, insert_time, update_time) VALUES 
				('" . $software_title . " now contains a Software Updates section!', '<em>[This feature was implemented on 2013-05-04, but it seemed appropriate that the very first post in the Software Updates section be information about the new section itself, so the post was duplicated and backdated]</em><BR><BR>After upgrading " . $software_title . " I\'m sure it would be nice to know what new features have been added, as well as any important changes to the software that you should know about, so I\'ve added a Software Updates section that chronicles the most important and most useful new features. Now after an upgrade you can simply visit the Software Updates section and view a list of the updates since your previous version.', '2013-03-20 00:00:00', '2013-03-20 00:00:00'),
				('Support has been added for automatic currency updates!', 'Thanks to Yahoo! Finance\'s free API, I\'m happy to announce that currency conversions have been completely automated. Now instead of having to manually update the conversions one-by-one on a regular basis to ensure proper financial reporting, all you have to do is make sure your default currency is set and your conversion rates will be updated automatically and seemlessly in the background while you use the software.<BR><BR>To say that this feature pleases me would be a huge understatement. I personally use " . $software_title . " on a daily basis, and updating the currency conversions manually was always such a boring, tedious task, and I\'m happy that nobody will ever have to go through that process ever again. If I could give Yahoo! Finance a big hug, I would.', '2013-03-20 00:00:01', '2013-03-20 00:00:01'),
				('A new \'IP Address\' section has been added to the UI so that you can keep track of all your IP Addresses within " . $software_title . "', '', '2013-03-26 00:00:00', '2013-03-26 00:00:00'),
				('Test Data System removed, Demo launched', 'In order to focus on the development of the actual " . $software_title . " software, I\'ve decided to remove the Test Data System entirely. Although this system allowed users to easily generate some test data and get a feel for the software, it complicated the development process and added unecessary overhead to the software as a whole. Most importantly, it took me away from adding other, more useful features to the core software.<BR><BR>Now instead of testing the software by installing it and generating the test data, you can simply visit <a class=\"invisiblelink\" target=\"_blank\" href=\"http://demo.domainmod.org\">http://demo.domainmod.org</a> to take " . $software_title . " for a test drive.', '2013-04-06 00:00:00', '2013-04-06 00:00:00'),
				('Update the Segments UI to give the user a lot more information and flexibility', 'Now when filtering your domains using a segment, " . $software_title . " will tell you which domains in the segment are stored in " . $software_title . " (indicating whether or not the domain is active or inactive), as well as which domains don\'t match, and lastly it will tell you which domains matched but were filtered out based on your other search criteria. Each of the resulting lists can be easily viewed and exported for your convenience.<BR><BR>It took quite a bit of work to get this feature implemented, but the segment filtering just felt incomplete without it. It was still a very useful feature, but now it\'s incredibly powerful, and I hope to add on the functionality in the future.', '2013-04-07 00:00:00', '2013-04-07 00:00:00'),
				('The Domain & SSL search pages have been updated to allow for the exporting of results', '', '2013-04-07 00:00:00', '2013-04-07 00:00:00'),
				('A logo has now been added to " . $software_title . " in order to pretty things up a little bit', '', '2013-04-10 00:00:00', '2013-04-10 00:00:00'),
				('Cron job added for sending an email to users about upcoming Domain and SSL Certificate renewals', 'A cron job has now been added to send a daily email to users letting them know about upcoming domain and SSL expirations, and users can subscribe and unsubscribe from this email through their Control Panel.<BR><BR>If you would like to use this feature, just let your system administrator know so that they can schedule the cron job to run.', '2013-04-15 00:00:00', '2013-04-15 00:00:00'),
				('A new \'Web Hosting\' section has been added to the UI so that you can now keep track of your web hosting providers within " . $software_title . "', '', '2013-04-17 00:00:00', '2013-04-17 00:00:00'),
				('A password field has now been added to Registrar & SSL Provider accounts so that passwords can be managed through " . $software_title . "', '', '2013-04-19 00:00:00', '2013-04-19 00:00:00'),
				('Update the expiration email so that the System Adminstrator can set the number of days in the future to display in the email', 'Previously when the daily expiration emails were sent out to users they would automatically include the next 60 days of expirations, but this has now been converted to a system setting so that your system administrator can now specify the number of days to include in the email.', '2013-04-19 00:00:01', '2013-04-19 00:00:01'),
				('Remove the (redundant) Domain Status and Status Notes fields', 'Although the Domain Status & Status Notes fields were removed because they were redundant, if you had data stored in either of these fields it would have been appended to the primary Notes field when your database was upgraded. So don\'t worry, dropping these two fields didn\'t cause you to lose any data.', '2013-04-20 00:00:00', '2013-04-20 00:00:00'),
				('Added a \'view full notes\' feature to the Domain and SSL Cert edit pages', 'When editing a Domain or SSL certificate, if you want to view the notes but scrolling through the text box just isn\'t your thing, you can now click on a link to view the full notes on a separate page, making them much easier to read.', '2013-04-24 00:00:00', '2013-04-24 00:00:00'),
				('Reporting section added', '" . $software_title . " now includes a handful of reports that can give you valuable insight into your data, and I\'m always on the lookout for more reports that can be added. If you have any new report ideas, or any suggestions for the current reports, feel free to drop me a line at <a class=\"invisiblelink\" href=\"mailto:greg@chetcuti.com\">greg@chetcuti.com</a>.', '2013-04-25 00:00:00', '2013-04-25 00:00:00'),
				('Cron job added for automating currency conversions at regular intervals', 'Never worry about having outdated exchange rates again! " . $software_title . " now includes a cron job that automates currency conversions. This means you can have the cron job set to run overnight, and when you go to use " . $software_title . " in the morning your currency conversions will already be completely up-to-date.<BR><BR>If you would like to use this feature, just let your system administrator know so that they can schedule the cron job to run.', '2013-04-27 00:00:00', '2013-04-27 00:00:00'),
				('" . $software_title . " has been converted to UTF-8', 'The entire " . $software_title . " system has been converted to use the UTF-8 character set in order to allow for support of non-ASCII characters, such as the characters found in some IDNs (Internationalized Domain Names).', '2013-04-27 00:00:01', '2013-04-27 00:00:01'),
				('Currencies have been updated to be user-based instead of system-based', 'Now that Currencies have been re-worked to be user-based, every user in the system can set their own default currency, and this currency will be used for them throughout the system. Every setting, webpage, and report in the " . $software_title . " system will automatically be converted to display monetary values using the user\'s default currency.', '2013-04-29 00:00:00', '2013-04-29 00:00:00'),
				('Overhaul of " . $software_title . " Settings Complete!', 'Over the past few months the " . $software_title . " settings have been undergoing a complete overhaul. The changes include but are not limited to making currency conversions user-based instead of system-based, updating all Domain & SSL default settings to be user-based instead of system-based, separating out Category, IP Address and Owner settings so that Domains & SSLs have thier own options instead of sharing them, adding support for saving passwords for Domain Registrar & SSL Provider accounts, removing the redundant Status and Status Notes fields from the Domains section, and so on.<BR><BR>I\'m constantly trying to improve the software and make it more user-friendly, so if you have any suggestions or feedback feel free to drop me a line at <a class=\"invisiblelink\" href=\"mailto:greg@chetcuti.com\">greg@chetcuti.com</a>.', '2013-05-02 00:00:00', '2013-05-02 00:00:00'),
				('" . $software_title . " now contains a Software Updates section!', 'After upgrading " . $software_title . " I\'m sure it would be nice to know what new features have been added, as well as any important changes to the software that you should know about, so I\'ve added a Software Updates section that chronicles the most important and most useful new features. Now after an upgrade you can simply visit the Software Updates section and view a list of the updates since your previous version.', '2013-05-04 00:00:00', '2013-05-04 00:00:00'),
				('An Export option has been added to all Asset pages', '', '2013-05-06 00:00:00', '2013-05-06 00:00:00'),
				('You can now create Custom Domain & SSL Fields!', 'In an effort to allow users more flexibility, as well as track as much data as possible, I\'ve implemented Custom Domain & SSL Fields. Now if there\'s information you want to track for a domain or SSL certificate but the field doesn\'t exist in " . $software_title . ", you can just add it yourself!<BR><BR>For example, if you wanted to keep track of which domains are currenty setup in Google Analytics, you could create a new Google Analytics check box field and start tracking this information for each of your domains. Or if you were working in a corporate environment and wanted to keep a record of who purchased each of your SSL certificates, you could create a Purchaser Name text field and keep track of this information for every one of your SSL certificates. Combine custom fields with the ability to update them with the Bulk Updater, and the sky\'s the limit in regards to what data you can easily track! (the Bulk Updater currently only supports domains, not SSL certificates)<BR><BR>And when you export your domain & SSL data, the information contained in your custom fields will automatically be included in the exported data.', '2013-05-25 17:00:00', '2013-05-25 17:00:00'),
				('" . $software_title . " now includes a Data Warehouse for importing data', '" . $software_title . " now has a data warehouse framework built right into it, which allows you to import the data stored on your web servers. Currently the only web servers that are supported are ones that run WHM/cPanel, but I also intend on adding support for Plesk and other systems once I’ve ironed out all the kinks in the framework.<BR><BR>The data warehouse is used for informational purposes only, and you will see its data referenced throughout " . $software_title . " where applicable. For example, if a domain you’re editing has information stored in your data warehouse, the system will automatically match them up and display the additional information for you, giving you even more insight into your data. You can also view, export, and run reports on the information in your data warehouse.<BR><BR>The following WHM data is currently supported, but my end goal is to have every piece of WHM information that can be retrieved via the API stored in the data warehouse.<BR><BR><strong>ACCOUNTS</strong><BR>Domain, IP Address, Owner, User, Contact Email, Plan, Theme, Shell, Partition, Disk Limit, Disk Usage, Max Addons, Max FTP Accounts, Max Email Lists, Max Parked Domains, Max POP Accounts, Max SQL Accounts, Max Subdomains, Creation Date, Suspend Status, Suspend Reason, Suspend Time, Max Email Per Hour, Failed Email % Before Defer, Min Failed Email # Before Defer<BR><BR><strong>DNS ZONES</strong><BR>Zone File Name, Original/Primary Source of Zone Data, Admin Email, Serial #, Refresh, Retry, Expiry, Minimum TTL, Authoritative Name Server<BR><BR><strong>DNS RECORDS</strong><BR>TTL, Class, Type, IP Address, CNAME, Mail Server, Mail Server Priority, TXT Data, Line # of Zone, # of Lines, RAW Data<BR><BR><font class=\"default_highlight\">NOTE:</font> Importing your server into the data warehouse will not modify any of your " . $software_title . " data.', '2013-06-01 1:00:00', '2013-06-01 1:00:00')";
		$result = mysql_query($sql,$connection) or die(mysql_error());
	
		$sql = "CREATE TABLE IF NOT EXISTS update_data (
				`id` int(10) NOT NULL auto_increment,
				`user_id` int(10) NOT NULL,
				`update_id` int(10) NOT NULL,
				`insert_time` datetime NOT NULL,
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql,$connection) or die(mysql_error());
		
		$sql = "SELECT id
				FROM users";
		$result = mysql_query($sql,$connection);
	
		while ($row = mysql_fetch_object($result)) {
	
			$sql_updates = "SELECT id
							FROM `updates`";
			$result_updates = mysql_query($sql_updates,$connection);
	
			while ($row_updates = mysql_fetch_object($result_updates)) {
	
				$sql_insert = "INSERT INTO 
							   update_data
							   (user_id, update_id, insert_time) VALUES 
							   ('" . $row->id . "', '" . $row_updates->id . "', '" . $current_timestamp . "')";
				$result_insert = mysql_query($sql_insert,$connection);
	
			}
	
		}

		$_SESSION['are_there_updates'] = "1";

		$sql = "UPDATE settings
				SET db_version = '2.005',
					update_time = '" . mysql_real_escape_string($current_timestamp) . "'";
		$result = mysql_query($sql,$connection) or die(mysql_error());

		$current_db_version = 2.005;

	}

	if ($direct == "1") {
	
		$_SESSION['result_message'] .= "Your Database Has Been Updated<BR>";
			
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		
	} else {
			
		$_SESSION['result_message'] .= "Your Database Has Been Updated<BR>";
		
	}

} else {

	if ($direct == "1") {
	
		$_SESSION['result_message'] .= "Your Database is already up-to-date<BR>";
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	
	} else {
		
		$_SESSION['result_message'] .= "Your Database is already up-to-date<BR>";
	
	}
	
}
?>
