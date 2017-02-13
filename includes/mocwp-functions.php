<?php
/**
 * Utility functions for the plugin.
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
 * Fetch the group id of the MoCWP group for the current environment.
 *
 * @since   1.0.0
 *
 * @return  int The group ID
 */
function mocwp_get_group_id(){
	$location = get_site_url();
	switch ( $location ) {
		case 'http://commonsdev.local':
			$group_id = 23;
			break;
		case 'https://dev.communitycommons.org':
		case 'https://staging.communitycommons.org':
		case 'https://www.communitycommons.org':
		case 'https://abydos.cares.missouri.edu':
			$group_id = 703;
			break;
		default:
			$group_id = 0;
			break;
	}
	return apply_filters( 'mocwp_get_group_id', $group_id );
}

/**
 * Is this the group?.
 *
 * @since   1.0.0
 *
 * @param   int $group_id Optional. Group ID to check.
 *          Defaults to current group.
 * @return  bool
 */
function mocwp_is_mocwp_group( $group_id = 0 ) {
	if ( empty( $group_id ) ){
		$group_id = bp_get_current_group_id();
	}
	$setting = ( mocwp_get_group_id() == $group_id ) ? true : false;

	return apply_filters( 'mocwp_is_mocwp_group', $setting );
}

/**
 * Get base url for the group.
 *
 * @since   1.0.0
 *
 * @return  string url
 */
function mocwp_get_group_permalink() {
	$group_id = sa_get_group_id();
	$permalink = bp_get_group_permalink( groups_get_group( array( 'group_id' => $group_id ) ) );

	return apply_filters( 'mocwp_get_group_permalink', $permalink, $group_id);
}

/**
 * Is the current or specified user a member of the hub?
 *
 * @since   1.0.0
 * @param   int $user_id
 *
 * @return  bool
 */
function mocwp_is_current_user_a_member( $user_id = 0 ) {
	$is_member = false;

	if ( empty( $user_id )  ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		$is_member = (bool) groups_is_user_member( $user_id, mocwp_get_group_id() );
	}

	return $is_member;
}

/**
 * Get an array of the counties of Missouri.
 *
 * @since   1.0.0
 *
 * @return  array
 */
function mocwp_get_counties() {
	return array( 'Adair', 'Andrew', 'Atchison', 'Audrain', 'Barry', 'Barton', 'Bates', 'Benton', 'Bollinger', 'Boone', 'Buchanan', 'Butler', 'Caldwell', 'Callaway', 'Camden', 'Cape Girardeau', 'Carroll', 'Carter', 'Cass', 'Cedar', 'Chariton', 'Christian', 'Clark', 'Clay', 'Clinton', 'Cole', 'Cooper', 'Crawford', 'Dade', 'Dallas', 'Daviess', 'DeKalb', 'Dent', 'Douglas', 'Dunklin', 'Franklin', 'Gasconade', 'Gentry', 'Greene', 'Grundy', 'Harrison', 'Henry', 'Hickory', 'Holt', 'Howard', 'Howell', 'Iron', 'Jackson', 'Jasper', 'Jefferson', 'Johnson', 'Knox', 'Laclede', 'Lafayette', 'Lawrence', 'Lewis', 'Lincoln', 'Linn', 'Livingston', 'McDonald', 'Macon', 'Madison', 'Maries', 'Marion', 'Mercer', 'Miller', 'Mississippi', 'Moniteau', 'Monroe', 'Montgomery', 'Morgan', 'New Madrid', 'Newton', 'Nodaway', 'Oregon', 'Osage', 'Ozark', 'Pemiscot', 'Perry', 'Pettis', 'Phelps', 'Pike', 'Platte', 'Polk', 'Pulaski', 'Putnam', 'Ralls', 'Randolph', 'Ray', 'Reynolds', 'Ripley', 'St. Charles', 'St. Clair', 'Ste. Genevieve', 'St. Francois', 'St. Louis', 'St. Louis City', 'Saline', 'Schuyler', 'Scotland', 'Scott', 'Shannon', 'Shelby', 'Stoddard', 'Stone', 'Sullivan', 'Taney', 'Texas', 'Vernon', 'Warren', 'Washington', 'Wayne', 'Webster', 'Worth', 'Wright' );
}

/**
 * Get an associative array of the 8-digit Hydrologic Unit Codes of Missouri.
 *
 * @since   1.0.0
 *
 * @return  array
 */
function mocwp_get_hucs() {
	return array( "07110001" => "Bear-Wyaconda", "11010001" => "Beaver Reservoir", "10290202" => "Big Piney", "07140104" => "Big", "10300104" => "Blackwater", "07140103" => "Bourbeuse", "11010003" => "Bull Shoals Lake", "08020302" => "Cache", "07140101" => "Cahokia-Joachim", "07110008" => "Cuivre", "11010008" => "Current", "11010011" => "Eleven Point", "11070208" => "Elk", "10290105" => "Harry S. Truman Reservoir", "10240011" => "Independence-Sugar", "11010002" => "James", "10240001" => "Keg-Weeping Water", "11070206" => "Lake O' The Cherokees", "10290109" => "Lake Of The Ozarks", "10300103" => "Lamine", "10280203" => "Little Chariton", "10290103" => "Little Osage", "08020204" => "Little River Ditches", "11010009" => "Lower Black", "10280202" => "Lower Chariton", "07100009" => "Lower Des Moines", "10290203" => "Lower Gasconade", "10280103" => "Lower Grand", "10270104" => "Lower Kansas", "10290102" => "Lower Marais Des Cygnes", "08010100" => "Lower Mississippi-Memphis", "10300200" => "Lower Missouri", "10300101" => "Lower Missouri-Crooked", "10300102" => "Lower Missouri-Moreau", "05140206" => "Lower Ohio", "10290111" => "Lower Osage", "08020203" => "Lower St. Francis", "10290104" => "Marmaton", "07140102" => "Meramec", "08020201" => "New Madrid-St. Johns", "10290110" => "Niangua", "10240004" => "Nishnabotna", "10240010" => "Nodaway", "07110002" => "North Fabius", "07110005" => "North Fork Salt", "11010006" => "North Fork White", "10240013" => "One Hundred And Two", "07110009" => "Peruque-Piasa", "10240012" => "Platte", "10290107" => "Pomme De Terre", "10290106" => "Sac", "07110007" => "Salt", "07110003" => "South Fabius", "07110006" => "South Fork Salt", "10290108" => "South Grand", "11010010" => "Spring", "11070207" => "Spring", "10240005" => "Tarkio-Wolf", "07110004" => "The Sny", "10280102" => "Thompson", "11010007" => "Upper Black", "10280201" => "Upper Chariton", "10290201" => "Upper Gasconade", "10280101" => "Upper Grand", "07140105" => "Upper Mississippi-Cape Girardeau", "08020202" => "Upper St. Francis", "07140107" => "Whitewater" );
}