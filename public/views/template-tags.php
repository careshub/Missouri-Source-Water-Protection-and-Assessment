<?php
/**
 * Generate the public-facing pieces of the plugin.
 *
 * Community Commons MoCWP
 *
 * @package   Community_Commons_MoCWP
 * @author    dcavins
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2017 Community Commons
 */

/**
 * Generate the water system search form .
 *
 * @since   1.0.0
 *
 * @return  html Form.
 */
function mocwp_form() {
	// Get remote data
	$data = array();
	$query = 'SELECT pwssid, pwssname, totcounties, tothuc8s, totstatuses FROM Cares.Cares.VA_PUB_SYS_SUMMARY';
	$response = wp_remote_post(
		'http://services.communitycommons.org/api-report/v1/select',
		array(
			'body' => array(
				'sql_query' => $query,
			)
		)
	);

	if ( ! is_wp_error( $response ) ) {
		$data = json_decode( $response['body'] );
	}

	$systems = array();
	foreach ( $data as $system ) {
		$row = array();
		foreach ( $system as $property ) {
			// Convert comma-separated lists to space delimited lists
			// for compatibility wit h the ~= jQuery selector.
			$property->field_value = str_replace( ',', ' ', $property->field_value );
			$row[$property->field_name] = $property->field_value;
		}
		$systems[] = $row;
	}
	$system_count = count( $systems );

	$sys_options = '';
	foreach ( $systems as $sys ) {
		$sys_options .= '<option value="' . $sys['pwssid'] . '" data-county="' . $sys['totcounties'] . '" data-huc="' . $sys['tothuc8s'] . '" data-status="' . $sys['totstatuses'] . '">' . $sys['pwssname'] . ' &ndash; ' . $sys['pwssid'] . '</option>';
	}

	$counties = mocwp_get_counties();
	foreach ( $counties as $county ) {
		$label = $county;
		// Add "county" unless it's St Louis City.
		if ( 'St. Louis City' != $county ) {
			$label = $county . ' County';
		}
		$county_options .= '<option value="' . $county . '">' . $label . '</option>';
	}

	$hucs = mocwp_get_hucs();
	foreach ( $hucs as $huc_id => $huc_name ) {
		$huc_options .= '<option value="' . $huc_id . '">' . $huc_id . ' &ndash; ' . $huc_name . '</option>';
	}

?>
<form id="mocwp" action="https://maps.communitycommons.org/report/va" method="get">
	<div class="content-row clear">
		<h4>Choose a Public Water System by Name or ID</h4>
		<select name="sysid" id="sysid" class="chosen-select" data-placeholder="Search by PWS system ID or name." style="width:100%">
			<!-- Include an empty option for chosen.js support-->
			<option></option>
			<?php echo $sys_options; ?>
		</select>
		<p style="margin-top:.4em;"><em><span id="result-count">Filtered to list <?php echo $system_count; ?> systems</span></em></p>

	</div>

	<div class="content-row clear">
		<h4>Or, Filter Public Water Systems by Other Properties&ensp;<em>optional</em></h4>

		<div class="third-block compact">
			<label for="county"><strong>Filter by County</strong></label>
			<p><select id="county" class="chosen-select filter" data-placeholder="Select a county." style="width:100%">
				<!-- Include an empty option for chosen.js support-->
				<option></option>
				<?php echo $county_options; ?>
			</select></p>
		</div>

		<div class="third-block compact">
			<label for="huc"><strong>Filter by Hydrologic Unit Code (HUC)</strong></label>
			<p><select id="huc" class="chosen-select filter" data-placeholder="Select an 8-digit HUC." style="width:100%">
				<!-- Include an empty option for chosen.js support-->
				<option></option>
				<?php echo $huc_options; ?>
			</select></p>
		</div>

		<div class="third-block compact">
			<fieldset id="status">
				<legend><strong>Include Water Source Statuses</strong></legend>
				<input type="checkbox" id="include-status-active" class="filter" name="status" value="Active" checked="checked"> <label for="include-status-active">Active</label> <br />
				<input type="checkbox" id="include-status-emergency" class="filter" name="status" value="Emergency" checked="checked"> <label for="include-status-emergency">Emergency</label> <br />
				<input type="checkbox" id="include-status-proposed" class="filter" name="status" value="Proposed"> <label for="include-status-proposed">Proposed</label> <br />
			</fieldset>
		</div>
	</div>

	<hr />

	<div class="content-row clear" style="margin-bottom: 1em;">
		<fieldset id="maptype">
			<legend><strong>Jump to Sheet</strong></legend>
			<input type="radio" id="jump-to-ve" name="maptype" value="ve" checked="checked"> <label for="jump-to-ve">System Overview Map (aerial)</label> <br />
			<input type="radio" id="jump-to-topo" name="maptype" value="topo"> <label for="jump-to-topo">System Overview Map (topo)</label> <br />
			<input type="radio" id="jump-to-wive" name="maptype" value="wive"> <label for="jump-to-wive">Well/Intake Map (aerial)</label> <br />
			<input type="radio" id="jump-to-witopo" name="maptype" value="witopo"> <label for="jump-to-witopo">Well/Intake Map (topo)</label> <br />
			<input type="radio" id="jump-to-dsWellData" name="maptype" value="dsWellData"> <label for="jump-to-dsWellData">Well/Intake Data Sheet</label> <br />
			<input type="radio" id="jump-to-dsContDataSF" name="maptype" value="dsContDataSF"> <label for="jump-to-dsContDataSF">State/Federal Contaminant Report</label> <br />
			<input type="radio" id="jump-to-dsContData2" name="maptype" value="dsContData2"> <label for="jump-to-dsContData2">SWIP Contaminant Report</label> <br />
			<input type="radio" id="jump-to-dsContSumData" name="maptype" value="dsContSumData"> <label for="jump-to-dsContSumData">Contaminant Summary Report</label> <br />
			<input type="radio" id="jump-to-dsSusDet" name="maptype" value="dsSusDet"> <label for="jump-to-dsSusDet">Susceptibility Determination</label>
		</fieldset>
	</div>

	<input type="hidden" id="total_systems_count" value="<?php echo $system_count; ?>">

	<p><button type="submit">Open Report</button></p>
</form>

<hr />
<?php
}