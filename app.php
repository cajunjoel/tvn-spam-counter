#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Cookie\CookieJar;
use TarValonNet\ThreadReader;
use TarValonNet\PostChecker;
use TarValonNet\DBHandler;


// ----------------------
// Read the config file
// ----------------------
$config_file = './config.json';
$rulename = '';

// ----------------------
// Process the config file and command line options
// ----------------------
$opts =  getopt('c::d::v::r:', array('config::','debug::','verbose::','ruleset:'));
if (isset($opts['c']) || isset($opts['config'])) {
	if (isset($opts['c'])) {
		$config_file = $opts['c'];	
	} elseif (isset($opts['config'])) {
		$config_file = $opts['config'];	
	}
}
$config = default_config();
if (file_exists($config_file)) {
	$config = json_decode(file_get_contents($config_file), true);
} else {
	usage("Config file not found.");
	die;
}

$config['debug'] = '';
if (isset($opts['d']) || isset($opts['debug'])) {
	if (isset($opts['d'])) {
		$config['debug'] = $opts['d'];
	} elseif (isset($opts['debug'])) {
		$config['debug'] = $opts['debug'];
	}
}
$config['verbose'] = (isset($opts['v']) || isset($opts['verbose']));

// Make sure we can find the rules
if (!file_exists("rules/".$config['rules'])) {
	usage('Rules file not found: '."rules/".$config['rules']);
	die;
}

// Make sure things exist
if (!file_exists($config['output_path'])) {
	mkdir($config['output_path'], 0777, true);
}
if (!file_exists($config['cache_path'])) {
	mkdir($config['cache_path'], 0777, true);
}

if (isset($opts['r']) || isset($opts['ruleset'])) {
	if (isset($opts['r'])) {
		$rulename = $opts['r'];	
	} elseif (isset($opts['ruleset'])) {
		$rulename = $opts['ruleset'];	
	}
}
if (!$rulename) {
	usage("No ruleset provided.");
	die;
}

// ----------------------
// Read the rules
// ----------------------
$all_rulesets = json_decode(file_get_contents("rules/".$config['rules']), true);
if (!isset($all_rulesets[$rulename])) {
    print "Config Ruleset '$rulename' not found.\n";
    usage();
    die;
}
$ruleset = $all_rulesets[$rulename];
print "Processing ".$ruleset['name']."...\n";


// ----------------------
// Prep the DB
// ----------------------
$db = new TarValonNet\DBHandler($config, $ruleset, $rulename);
$db->prepare_database($config['event_name']);


// ----------------------
// Get the URL
// ----------------------
$base_url = $ruleset['url'];
// Strip anchors from the URL
$base_url = preg_replace("/#.+$/", '', $base_url);
// Strip page numbers from the URL
if (preg_match("/\/page-\d+/", $base_url)) {
    $base_url = preg_replace("/\/page-\d+/", '', $base_url);
}

// ----------------------
// Use my login to TVN
// ----------------------
$jar = CookieJar::fromArray(
	[
		'xf_user' => $config['cookies']['xf_user'],
		'xf_tfa_trust' => $config['cookies']['xf_tfa_trust'],
		'xf_session' => $config['cookies']['xf_session'],
		'xf_csrf' => $config['cookies']['xf_csrf'],
	],
	$config['cookie_domain']
);

// ----------------------
// Read the thread
// ----------------------
$reader = new TarValonNet\ThreadReader($jar, $config);
$posts = $reader->read_thread($ruleset['url']);

// ----------------------
// Check the posts
// ----------------------
$checker = new TarValonNet\PostChecker($ruleset, $config);
$last_author = '';
for ($i = 0; $i < count($posts); $i++) {
	$checker->check_post($posts[$i], $last_author);
	$db->log_post($posts[$i]);
	// Remember who just posted
	$last_author = $posts[$i]['author'];
}

// ----------------------
// Save the Results
// ----------------------
$summary = $checker->get_summary($posts);
$matches = array();

preg_match("/^.+\/(.*?)$/", $ruleset['url'], $matches);
$csv_filename = $config['output_path'].'/'.$matches[1].'.csv';
$summ_filename = $config['output_path'].'/'.$matches[1].'-summary.csv';
save_spreadsheet($posts, $csv_filename);
save_summary($ruleset['name'], $summary, $summ_filename);

function save_summary($name, $summary, $filename) {
	$fh = fopen($filename, 'w');
	fwrite($fh, $name."\n");
	fwrite($fh, "GROUP\tCOUNT\n");
	foreach ($summary['groups'] as $name => $count) {
		fwrite($fh, $name."\t".$count."\n");
	}
	fwrite($fh, "\n");
	fwrite($fh, "NAME\tCOUNT\n");
	foreach ($summary['authors'] as $name => $count) {
		fwrite($fh, $name."\t".$count."\n");
	}
	fclose($fh);
}

function save_spreadsheet($posts, $filename) {
	$fh = fopen($filename, 'w');
	// Get all keys and unique-ify them
	$keys = [];
	foreach ($posts as $p) {
		unset($p['emoji']);
		unset($p['has_smilies']);
		foreach (array_keys($p) as $k) {
			$keys[] = $k;
		}
	}
	$keys = array_unique($keys);

	// Now we can save ALL the output
	fputcsv($fh, $keys);
	foreach ($posts as $p) {
		$arr = [];
		foreach ($keys as $k) {
			if ($k == 'emoji' || $k == 'has_smileys') {
				$arr[] = '';	
			} else {
				$arr[] = (isset($p[$k]) ? $p[$k] : '');
			}
		}
		// print_r($keys);
		// print_r($arr);
		fputcsv($fh, $arr);
	}
	fclose($fh);
}

function usage($message = '') {
	if ($message) {
		print "ERROR: $message\n";
	}
  print "USAGE: php app.php [options] -r NAME\n\n";
	print "Optional and Required arguments: \n";
	print "   -r|--ruleset NAME   Required. A name from the rules.json file.\n";
	print "   -v|--verbose        Optional. Print more output about what's\n";
	print "                       going on.\n";
	print "   -d|--debug N        Optional. Even more output. N may be 1 or 2\n";
	print "   -c|--config FILE    Optional. Use a different config file.\n";
	print "                       (defualt: config.json)\n";
}

function default_config() {
	return array( 
		'cache_lifetime' => 1800,
		'cache_path' => './cache',
		'output_path' => './output',
		'rules' => 'rules.json',
		'sleep' => 1
	);
}