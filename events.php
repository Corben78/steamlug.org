<?php
$pageTitle = "Events";

require_once( "rbt_prs.php" );
require_once( "steameventparser.php" );

$parser = new SteamEventParser();

$month = gmstrftime("%m")-0;
$year  = gmstrftime("%Y");
$data  = $parser->genData("steamlug", $month, $year);
$data2 = $parser->genData("steamlug", ( $month >= 12 ? 1 : ( $month +1 ) ), ( $month >= 12 ? ( $year + 1 ) : $year ));
$data3 = $parser->genData("steamlug", ( $month <= 1 ? 12 : ( $month -1 ) ), ( $month <= 1 ? ( $year -1 ) : $year ));

$data['events'] = array_merge($data['events'], $data2['events']);
$data['pastevents'] = array_merge($data['pastevents'], $data3['pastevents']);

foreach ($data['events'] as $event)
	{
		if ($event['appid'] === 0)
		{
			continue;
		}
	$d = explode("-", $event['date']);
	$t = explode(":", $event['time']);

	break;
}

$dateString = "var target = Math.round( Date.UTC (" . $d[0] . ", " . $d[1] . " -1, " . $d[2] . ", " . $t[0] . ", " . $t[1] . ", 0, 0) / 1000);";
$extraJS = $dateString;
$externalJS = array('/scripts/events.js');

include_once( "includes/header.php" );
?>
		<header>
				<h1>SteamLUG Events</h1>
		</header>
		<section>
			<article id='nextevent'>
				<h1>Next Event</h1>
<?php
foreach ($data['events'] as $event)
{
	$event['img_header'] = "//steamcdn-a.akamaihd.net/steam/apps/" . $event["appid"] . "/header.jpg";
	if ($event['appid'] === 0)
	{
		continue;
	}

	$eventString = "\t\t\t\t<h2><a href='" . $event['url'] . "'>" .  $event['title'] . "</a></h2>";
	($event['appid'] !== 0 ? $eventString .= "\t\t\t\t\t<a href='" . $event['url'] . "'><img class = 'eventImage' src='" . $event['img_header'] . "' alt='" . $event['title'] . "'/></a>\n" : $eventString .= "\t\t\t\t\t<h1>?</h1>\n");
	$eventString .= "\t\t\t\t<h3 class = 'detailLink' ><a href = '" . $event['url'] . "'>Click for details</a></h3>\n";
	echo $eventString;
	break;
}
?>
				<div id='countdown'>
					<div>Days<br />
						<span id= 'd1' class = 'counterDigit'>0</span>
						<span id= 'd2' class = 'counterDigit'>0</span>
					</div>
					<div>Hours<br />
						<span id= 'h1' class = 'counterDigit'>0</span>
						<span id= 'h2' class = 'counterDigit'>0</span>
					</div>
					<div>Minutes<br />
						<span id= 'm1' class = 'counterDigit'>0</span>
						<span id= 'm2' class = 'counterDigit'>0</span>
					</div>
					<div>Seconds<br />
						<span id= 's1' class = 'counterDigit'>0</span>
						<span id= 's2' class = 'counterDigit'>0</span>
					</div>
				</div>
			</article>
		</section>
		<section>
		<article id = 'about' >
			<div class = 'shadow' >
				<h1>About</h1>
				<p>Welcome to the SteamLUG events page!</p>
				<p>Here you can find a list of upcoming group gaming events hosted by the SteamLUG community. A countdown timer is shown above for the next upcoming event. We also have a <a href = '/feed/events'>RSS feed</a> of event reminders available.</p>
				<p>All times are listed in UTC, and are subject to change.</p>
				<p>Click on an event title to post comments, find more information, and retrieve server passwords (for this, you will need to become a group member by clicking the Join Group button on the upper right of any of the event pages).</p>
				<p>If you'd like to know more about our community, visit the <a href = 'about' >About page</a>, or hop into our <a href = 'irc'>IRC channel</a> and say hi. If you'd like to get involved with organising SteamLUG events, please contact <a href = 'http://steamcommunity.com/id/swordfischer' >swordfischer</a>.</p>

				<h1>Mumble</h1>
				<p>We also run a <a href = 'http://mumble.sourceforge.net/' >Mumble</a> voice chat server which we use in place of in-game voice chat. You can learn more about it on our <a href = 'mumble' >Mumble page</a>.</p>
				<p>We encourage our users to use the Mumble server during the events, if there should be any important messages to be announced and to make team-based games easier to manage. You can just sit and listen.</p>
			</div>
		</article>
		<article id = 'main' >
		<div class = 'shadow' >
		
		<h1>Upcoming Events</h1>
		<ul class = 'eventList' >

<?php
	foreach ($data['events'] as $event)
	{
		// skip if it's a special (non-game/non-app) event
		if ($event["appid"] === 0) {
			continue;
		}

		$eventString = "\t\t\t<li>\n";
		$eventString .= "\t\t\t\t<div class = 'eventWrap' >";
		$eventString .= "\t\t\t\t\t<div><img class = 'eventLogo' src = '" . $event['img_small'] . "' alt = '" . $event['title'] . "' ></div>\n";
		$eventString .= "\t\t\t\t\t<div class = 'eventCommentWrap'><a class = 'eventName' href = '" . $event['url'] . "' >" . $event['title'] . "</a><br>";
		$eventString .= ($event['comments'] > "0" ? "\t\t\t\t\t<a class = 'eventComments' href = '" . $event['url'] . "' >" . $event['comments'] . " " . ($event['comments'] == "1" ? "comment.." : "comments..") . "</a>\t" : "");
		$eventString .= "\t\t\t\t\t</div>";
		$eventString .= "\t\t\t\t\t<div><span class = 'eventDate' >" . $event['date'] . " " . $event['time'] . " " . $event['tz'] . "</span></div>\n";
		$eventString .= "\t\t\t\t</div>";
		/*$eventString .= "\t\t\t\t<a class = 'eventCal' href = '" . $event["url"] . "'>iCal</a></span>";*/
		$eventString .= "\t\t\t</li>\n";
		echo $eventString;
	}
?>
		</ul>
		</div>
		</article>
		<article id = 'history' >
		<div class = "shadow" >
		<h1>Past Events</h1>
		<ul class = 'eventList' >

<?php
	foreach ($data['pastevents'] as $event)
	{
		// skip if it's a special (non-game/non-app) event
		if ($event["appid"] === 0) {
			continue;
		}

		$eventString = "\t\t\t<li>\n";
		$eventString .= "\t\t\t\t<div class = 'eventWrap' >";
		$eventString .= "\t\t\t\t\t<div><img class = 'eventLogo' src = '" . $event['img_small'] . "' alt = '" . $event['title'] . "' ></div>\n";
		$eventString .= "\t\t\t\t\t<div class = 'eventCommentWrap'><a class = 'eventName' href = '" . $event['url'] . "' >" . $event['title'] . "</a><br>";
		$eventString .= ($event['comments'] > "0" ? "\t\t\t\t\t<a class = 'eventComments' href = '" . $event['url'] . "' >" . $event['comments'] . " " . ($event['comments'] == "1" ? "comment.." : "comments..") . "</a>\t" : "");
		$eventString .= "\t\t\t\t\t</div>";
		$eventString .= "\t\t\t\t\t<div><span class = 'eventDate' >" . $event['date'] . " " . $event['time'] . " " . $event['tz'] . "</span></div>\n";
		$eventString .= "\t\t\t\t</div>";
		$eventString .= "\t\t\t</li>\n";
		echo $eventString;
	}
?>
		</ul>
		</div>
		</article>
		</section>
<?php include_once("includes/footer.php"); ?>

