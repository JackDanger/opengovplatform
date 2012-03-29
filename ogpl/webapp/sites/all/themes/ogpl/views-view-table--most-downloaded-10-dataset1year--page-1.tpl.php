<?php
/**
 * @file views-view-table.tpl.php
 * Template to display a view as a table.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $header: An array of header labels keyed by field id.
 * - $fields: An array of CSS IDs to use for each field id.
 * - $class: A class or classes to apply to the table, based on settings.
 * - $row_classes: An array of classes to apply to each row, indexed by row
 *   number. This matches the index in $rows.
 * - $rows: An array of row items. Each row is an array of content.
 *   $rows are keyed by row number, fields within rows are keyed by field ID.
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)) : ?>
    <caption><?php print $title; ?></caption>
  <?php endif; ?>

<div class="tableData">
<table class="header-width <?php print $class; ?> cBoth"<?php print $attributes; ?> width="100%" cellspacing="0" cellpadding="0" border="0" >
  <thead>
    <tr>
      <?php foreach ($header as $field => $label): ?>
         <?php
	   if($field=='rownumber')
		  {
		   print '<th class="views-field views-field-<?php print $fields[$field]; ?> ds-list-head-new-first" style="text-align:center; "><h3>';
		   print $label; 
		   }
		   else if($field=='field_ds_agency_short_name_value')
		   {
		    print '<th class="views-field views-field-<?php print $fields[$field]; ?> ds-list-head-new" style="text-align:center;"><h3>';
		    print $label;
		   	   
		   }
		   if($field==='title'||$field==='field_ds_sector_nid')
		   {print '<th class="views-field views-field-<?php print $fields[$field]; ?> ds-list-head-new"><h3>';
		    print $label;
		   }
		  if($field=='downloads') 
		  {  print '<th class="views-field views-field-<?php print $fields[$field]; ?> ds-list-head-new-last" style="text-align:center;"><h3>';
		    print $label; }
			?>
       </h3></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $count => $row): ?>
      <tr class="<?php print implode(' ', $row_classes[$count]); ?>">
        <?php foreach ($row as $field => $content): ?>
			
			 <?php if($field=='rownumber'||$field=='downloads'||$field=='field_ds_agency_short_name_value')
				 {
				   print '<td class="views-field views-field-<?php print $fields[$field]; ?> ds-list-item-new-center"  ><p>';
				  }
				 else
				  print '<td class="views-field views-field-<?php print $fields[$field]; ?> ds-list-item-new"><p>';?>
            <?php print $content; ?>
             </p></td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>