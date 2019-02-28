<?php

	require_once('api.class.php');

	$list_id = 365320;     // can be obtained using searchLists

	$returned_fields = array('id', 'Email',"Phone", "Name", "Address", "State", "Postcode", "Country", 'Time of visit'
	, 'Are you in the', 'Are you', 'Car Parking', 'Thoroughness of security inspection', 'Courtesy & helpfulness of airport staff', 'Cleanliness of washrooms/ toilets', 'Shopping facilities','Cleanliness of airport terminal',
	 'Comfort of waiting / gate areas','Comments', 'Tick here if you would like a reply', 'Restaurant / eating facilities', 'Overall airport satisfaction', 'Kerbside Pickup/Drop Off', 'Date of your visit');
 
	$url = 'https://www.vision6.com.au/api/jsonrpcserver?version=3.3';
	$api_key = 'cannot disclose for obvious reasons';

	// request contact data

	$api = new Api($url, $api_key);
	$contacts = $api->invokeMethod('searchContacts', $list_id, array(), 9999, 0, '', '', $returned_fields);

	$myfile = fopen("vision6.csv", "w") or die("Unable to open file!");

	$headers = array("ID","Email", "Phone", "Name", "Address", "State", "Postcode", "Country","Time of visit", "Terminal" ,"Travel Status", "Car Parking", "Security Inspection", "Staff Courtesy",
	"Bathroom Quality", "Shopping Facilities", "Terminal Cleanliness", "Waiting Area Comfort", "Comments", "Reply?", "Restaurant/Eating Facilities", "Overall", "Kerbside", "Date of Visit");

	fputcsv($myfile, $headers);

	$count = 0;

	// loop over result

	foreach ($contacts as $contact_details) {

			$travel_status = str_replace(',', ' and ', $contact_details['Are you']);
			$terminal = str_replace(',', ' and ', $contact_details['Are you in the']);
			$time_of_visit = str_replace(',', ' and ', $contact_details['Time of visit']);
			$comments = '"'.$contact_details['Comments'].'"';
			$comments = str_replace(',', ' ' , $contact_details["Comments"]);
			$reformat_comments = str_replace(array("\r\n", "\n\r", "\n", "\r"), ' ', $comments);
			$reformat_date_of_visit = str_replace('0000-00-00 00:00:00', '', $contact_details['Date of your visit']);

			if ($terminal == "Select...") {
				$terminal = "";
			}

			$data = array($contact_details['id'], $contact_details['Email'], $contact_details['Phone'], $contact_details['Name'], $contact_details['Address'], $contact_details['State'], $contact_details['Postcode'], $contact_details['Country'], $time_of_visit , $terminal, $travel_status, $contact_details['Car Parking'], $contact_details['Thoroughness of security inspection'], $contact_details['Courtesy & helpfulness of airport staff'] , $contact_details['Cleanliness of washrooms/ toilets'], $contact_details['Shopping facilities'], $contact_details['Cleanliness of airport terminal'],
			$contact_details['Comfort of waiting / gate areas'], $reformat_comments, $contact_details['Tick here if you would like a reply'], $contact_details['Restaurant / eating facilities'], $contact_details['Overall airport satisfaction'], $contact_details['Kerbside Pickup/Drop Off'], $reformat_date_of_visit);

			fputcsv($myfile, $data);

			$count++;
		}

	print nl2br($count . " records read" . "\n");

	print nl2br("\n");

	print("----Load Complete----" );

	fclose($myfile);

	echo "<script>window.close();</script>";
?>
