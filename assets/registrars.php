<?php
// /assets/registrars.php
// 
// Domain Manager - A web-based application written in PHP & MySQL used to manage a collection of domain names.
// Copyright (C) 2010 Greg Chetcuti
// 
// Domain Manager is free software; you can redistribute it and/or modify it under the terms of the GNU General
// Public License as published by the Free Software Foundation; either version 2 of the License, or (at your
// option) any later version.
// 
// Domain Manager is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
// implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
// for more details.
// 
// You should have received a copy of the GNU General Public License along with Domain Manager. If not, please 
// see http://www.gnu.org/licenses/
?>
<?php
include("../_includes/start-session.inc.php");
include("../_includes/config.inc.php");
include("../_includes/database.inc.php");
include("../_includes/software.inc.php");
include("../_includes/auth/auth-check.inc.php");

$page_title = "Domain Registrars";
$software_section = "registrars";
?>
<?php include("../_includes/doctype.inc.php"); ?>
<html>
<head>
<title><?=$software_title?> :: <?=$page_title?></title>
<?php include("../_includes/head-tags.inc.php"); ?>
</head>
<body>
<?php include("../_includes/header.inc.php"); ?>
<?php
$sql = "SELECT r.id AS rid, r.name AS rname, r.default_registrar, r.url
		FROM registrars AS r, domains AS d
		WHERE r.id = d.registrar_id
		  AND d.domain NOT IN ('0', '10')
		  AND (SELECT count(*) FROM domains WHERE registrar_id = r.id AND active NOT IN ('0','10')) > 0
		GROUP BY r.name
		ORDER BY r.name asc";
$result = mysql_query($sql,$connection) or die(mysql_error());
?>
Below is a list of all the Domain Registrars that are stored in your <?=$software_title?>.<BR>
<?php if (mysql_num_rows($result) > 0) { ?>
<?php $has_active = "1"; ?>
    <table class="main_table">
    <tr class="main_table_row_heading_active">
        <td class="main_table_cell_heading_active">
            <font class="main_table_heading">Active Registrars (<?=mysql_num_rows($result)?>)</font>
        </td>
        <td class="main_table_cell_heading_active">
            <font class="main_table_heading">Accounts</font>
        </td>
        <td class="main_table_cell_heading_active">
            <font class="main_table_heading">Domains</font>
        </td>
    </tr>
    <?php 
    
    while ($row = mysql_fetch_object($result)) {

	    $new_rid = $row->rid;
    
        if ($current_rid != $new_rid) {
			$exclude_registrar_string_raw .= "'$row->rid', ";
		} ?>
    
        <tr class="main_table_row_active">
            <td class="main_table_cell_active">
                <a class="invisiblelink" href="edit/registrar.php?rid=<?=$row->rid?>"><?=$row->rname?></a><?php if ($row->default_registrar == "1") echo "<a title=\"Default Registrar\"><font class=\"default_highlight\">*</font></a>"; ?>&nbsp;[<a class="invisiblelink" target="_blank" href="<?=$row->url?>">v</a>]
            </td>
            <td class="main_table_cell_active">
                <?php
                $sql_total_count = "SELECT count(*) AS total_count
									FROM registrar_accounts
									WHERE registrar_id = '$row->rid'";
                $result_total_count = mysql_query($sql_total_count,$connection);
        
                while ($row_total_count = mysql_fetch_object($result_total_count)) { 
                    $total_accounts = $row_total_count->total_count;
                }
                
                    if ($total_accounts >= 1) { ?>
            
                        <a class="nobold" href="registrar-accounts.php?rid=<?=$row->rid?>"><?=number_format($total_accounts)?></a>
                        <?php 
            
                    } else { ?>
            
                        <?=number_format($total_accounts)?>
                        <?php
                    } ?>
        
            </td>
            <td class="main_table_cell_active">
                <?php
                $sql3 = "SELECT count(*) AS total_count
                         FROM domains
                         WHERE active NOT IN ('0', '10')
                           AND registrar_id = '$row->rid'";
                $result3 = mysql_query($sql3,$connection);
        
                while ($row3 = mysql_fetch_object($result3)) { 
                    $total_domains = $row3->total_count;
                }		
        
                    if ($total_accounts >= 1) { ?>
            
                        <a class="nobold" href="../domains.php?rid=<?=$row->rid?>"><?=number_format($total_domains)?></a>
                        <?php 
            
                    } else { ?>
            
                        <?=number_format($total_domains)?>
                        <?php 
                    
                    } ?>
        
            </td>
        </tr>
        <?php 
		$current_rid = $row->rid;

	} ?>
	<?php

} ?>

<?php
$exclude_registrar_string = substr($exclude_registrar_string_raw, 0, -2); 

if ($exclude_registrar_string == "") {

	$sql = "SELECT r.id AS rid, r.name AS rname, r.default_registrar, r.url
			FROM registrars AS r
			WHERE r.id
			GROUP BY r.name
			ORDER BY r.name asc";

} else {
	
	$sql = "SELECT r.id AS rid, r.name AS rname, r.default_registrar, r.url
			FROM registrars AS r
			WHERE r.id
			  AND r.id NOT IN ($exclude_registrar_string)
			GROUP BY r.name
			ORDER BY r.name asc";

}
$result = mysql_query($sql,$connection) or die(mysql_error());
?>
<?php if (mysql_num_rows($result) > 0) { 
$has_inactive = "1";
if ($has_active == "1") echo "<BR>";
if ($has_active != "1" && $has_inactive == "1") echo "<table class=\"main_table\">";
?>
    <tr class="main_table_row_heading_inactive">
        <td class="main_table_cell_heading_inactive">
            <font class="main_table_heading">Inactive Registrars (<?=mysql_num_rows($result)?>)</font>
        </td>
        <td class="main_table_cell_heading_inactive">
            <font class="main_table_heading">Accounts</font>
        </td>
        <td class="main_table_cell_heading_inactive">&nbsp;
        	
        </td>
    </tr>
    <?php 
    
    while ($row = mysql_fetch_object($result)) { ?>
    
        <tr class="main_table_row_inactive">
            <td class="main_table_cell_inactive">
                <a class="invisiblelink" href="edit/registrar.php?rid=<?=$row->rid?>"><?=$row->rname?></a><?php if ($row->default_registrar == "1") echo "<a title=\"Default Registrar\"><font class=\"default_highlight\">*</font></a>"; ?>&nbsp;[<a class="invisiblelink" target="_blank" href="<?=$row->url?>">v</a>]
            </td>
            <td class="main_table_cell_inactive">
                <?php
                $sql_total_count = "SELECT count(*) AS total_count
									FROM registrar_accounts
									WHERE registrar_id = '$row->rid'";
                $result_total_count = mysql_query($sql_total_count,$connection);
        
                while ($row_total_count = mysql_fetch_object($result_total_count)) { 
                    $total_accounts = $row_total_count->total_count;
                }
                
                    if ($total_accounts >= 1) { ?>
            
                        <a class="nobold" href="registrar-accounts.php?rid=<?=$row->rid?>"><?=number_format($total_accounts)?></a>
                        <?php 
            
                    } else { ?>
            
                        <?=number_format($total_accounts)?>
                        <?php
                    } ?>
        
            </td>
            <td class="main_table_cell_inactive">&nbsp;
				
            </td>
        </tr>
        <?php 

	} ?>
	<?php

} ?>
<?php
if ($has_active == "1" || $has_inactive == "1") echo "</table>";
?>
<?php if ($has_active || $has_inactive) { ?>
		<BR><font class="default_highlight">*</font> = Default Registrar
<?php } ?>
<?php if (!$has_active && !$has_inactive) { ?>
		<BR>You don't currently have any Domain Registrars. <a href="add/registrar.php">Click here to add one</a>.
<?php } ?>
<?php include("../_includes/footer.inc.php"); ?>
</body>
</html>