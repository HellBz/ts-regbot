<?PHP
require("lib/ts3admin.class.php");
include 'config.php';

function channelStatus($channel_id)
{
	global $tsAdmin;
	global $config;
	
	$channelList = $tsAdmin->channelList();	
	foreach($channelList['data'] as $channelListTemp)
	{
		if($channelListTemp['cid'] == $channel_id)
		{
			if($channelListTemp['total_clients'] > 0)
			{
				return true;
			}			
		}
	}
}

function usersOnChannel($channel_id)
{
	global $tsAdmin;
	global $config;
	
	$usersTable = Array();
	$clientList = $tsAdmin->clientList('-groups -voice -away -times');
	
	foreach($clientList['data'] as $clientListTemp)
	{
		if($clientListTemp['cid'] == $channel_id)
		{			
				$table['clientDatabaseId'] = $clientListTemp['client_database_id'];
				$table['clientCurrentId'] = $clientListTemp['clid'];
				$table['clientServerGroups'] = $clientListTemp['client_servergroups'];
				$table['clientCreted'] = $clientListTemp['client_created'];
							
			$usersTable[] = $table;
		}
	}
	return $usersTable;
}


$tsAdmin = new ts3admin($config['ip'], $config['query_port']);

if($tsAdmin->getElement('success', $tsAdmin->connect()))
{
	$tsAdmin->login($config['login'], $config['password']);	
	$tsAdmin->selectServer($config['port']);	
	$tsAdmin->setName($config['name']);
	
	$whoami = $tsAdmin->getElement('data', $tsAdmin->whoAmI());
    $tsAdmin->clientMove($whoami['client_id'],$config['bot_move']);
	
	
		
while(1) {
	
	$channelStatusMan = channelStatus($config['channel_id_male']);
	$channelStatusLady = channelStatus($config['channel_id_female']);
	
	$reg_groups = array($config['rank_id_male'],$config['rank_id_female']);
	
		if($channelStatusMan == true)
		{			
				$ManOnChannel = usersOnChannel($config['channel_id_male']);				
				foreach($ManOnChannel as $usersOnChannelTemp)
				{
					$man_groups = explode(',',$usersOnChannelTemp['clientServerGroups']);					 
					$tsAdmin->serverGroupAddClient($config['rank_id_male'],$usersOnChannelTemp['clientDatabaseId']);
					$tsAdmin->clientPoke($usersOnChannelTemp['clientCurrentId'], $config['rang_add']);
					$tsAdmin->clientKick($usersOnChannelTemp['clientCurrentId'], $kickMode = "channel", $kickmsg = "");	
				}
		}
		
		if($channelStatusLady == true)
		{			
				$LadyOnChannel = usersOnChannel($config['channel_id_female']);				
				foreach($LadyOnChannel as $usersOnChannelTemp)
				{	
					$lady_groups = explode(',',$usersOnChannelTemp['clientServerGroups']);
					$tsAdmin->serverGroupAddClient($config['rank_id_female'],$usersOnChannelTemp['clientDatabaseId']);
					$tsAdmin->clientPoke($usersOnChannelTemp['clientCurrentId'], $config['rang_add']);					
					$tsAdmin->clientKick($usersOnChannelTemp['clientCurrentId'], $kickMode = "channel", $kickmsg = "");										
				}		
		}
	
sleep(5);
}
	
}else{
	echo "Connetcion Problem";
}
?>