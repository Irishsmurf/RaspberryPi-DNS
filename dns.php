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

	$result = $client->listResourceRecordSets(array(
		'HostedZoneId' => $hostedZoneID
		));

	$records = $result["ResourceRecordSets"];
	
	foreach($records as $record)
	{
		$name = $record["Name"];
		if(strcmp($name, $domain) == 0)
		{
			$value = $record["ResourceRecords"][0]["Value"];		
			break;
		}
	}

	if(strcmp($ip->{"ip"}, $value) != 0)
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
