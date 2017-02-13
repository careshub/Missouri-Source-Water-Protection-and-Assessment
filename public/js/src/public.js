(function ( $ ) {
	"use strict";

		// Bind the chosen.js library to the inputs.
		$( '.chosen-select' ).chosen({
			search_contains: true,
			display_disabled_options: false,
			allow_single_deselect: true
		});

		// Apply filters at page load.
		apply_filters();

		// When a filter is updated, limit the options in the PWS dropdown.
		$('.filter').on('change', function(event, params) {
			apply_filters();
		});

		function apply_filters() {
			var filter = '',
				statuses = '',
				options = $( "#sysid option" ),
				enabled = 0,
				current_selection = $("#sysid").chosen().val(),
				total_systems_count = $("#total_systems_count").val();


			// Enable all options - clean the slate.
			options.attr( "disabled", false );

			/*
			 * Most of the filters are chosen-selects.
			 * We can string these together in this style to exclude those that
			 * don't match all selected properties:
			 * .not( [data-county~="Boone"][data-huc~="12345678"] )
			 */
			$( ".filter.chosen-select" ).each( function( index ) {
				var key   = $( this ).attr( "id" ),
					value = $( '#' + key ).chosen().val();
				// Build the whole word filter using ~= selector
				if ( value.length ) {
					filter += '[data-' + key + '~="' + value + '"]';
				}
			});

			// Disable those without the selected HUC and County.
			if ( filter.length ) {
				options.not( filter ).attr( "disabled", true );
			}

			/*
			 * The "status" filters are checkboxes and work differently.
			 * Logic is "any"--if "active" and "emergency" are checked, include
			 * PWSs that include sources with either or both statuses.
			 * Exclude those that don't have at least one of the chosen statuses:
			 * .not( [data-status~="Active"], [data-status~="Proposed"] )
			 */
			$( "#status input:checked" ).each( function( index ) {
				if ( index > 0 ) {
					statuses += ", ";
				}
				statuses += '[data-status~="' + $( this ).val() + '"]';
			});

			// Disable the options that don't have any of the selected statuses.
			if ( statuses.length ) {
				options.not( statuses ).attr( "disabled", true );
			}

			// Update the number of listed PWSs indicator.
			enabled = $( "#sysid option:enabled" ).length;
			if ( total_systems_count <= enabled ) {
				$("#result-count").fadeTo('fast', 0.5).text( "Listing all systems." ).fadeTo('slow', 1.0);
			} else if ( 1 == enabled ) {
				$("#result-count").fadeTo('fast', 0.5).text( "Filtered to list 1 system." ).fadeTo('slow', 1.0);
			} else {
				$("#result-count").fadeTo('fast', 0.5).text( "Filtered to list " + enabled + " systems." ).fadeTo('slow', 1.0);
			}

			// If the currently selected value is not an enabled option, clear it.
			if ( $( '#sysid option[value="' + current_selection + '"]' ).is( ":disabled" ) ) {
				$("#sysid").val("");
			}

			// Refresh the PWS list to take the new "enabled" options into account.
			$("#sysid").trigger("chosen:updated");
		}

}(jQuery));