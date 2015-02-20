<?php

	require 'vendor/autoload.php';
	use Aws\Route53\Route53Client;

	$hostedZoneID = 'Z1X4EE7SP0H2J4';
	$domain = 'pi.davekernan.co.uk.';
	$ip = json_decode(file_get_contents("http://ip.paddez.com?json"));
	echo $ip->{"ip"}."\n";

	$client = Route53Client::factory(array(
	    'profile' => 'default'
	));
	
	if(strcmp($ip->{"ip"}, gethostbyname($domain)) != 0)
	{
		$result = $client->changeResourceRecordSets(array(
    		'HostedZoneId' => $hostedZoneID,
    		'ChangeBatch' => array(
        	'Changes' => array(
        	    array(
			'Action' => 'UPSERT',
                	'ResourceRecordSet' => array(
                		'Name' => $domain,
                		'Type' => 'A',
                		'TTL' => 120,
                		'ResourceRecords' => array(
                			array(
                				'Value' => $ip->{"ip"},
                			),
                		),
			),
            	    ),
    	    	),
		),
		));
	}

?>
