<?php

namespace TarValonNet;

use TarValonNet\RuleLibrary;

class PostChecker {

  private $ruleset = [];
  private $config = null;
	private $processor = null;

  public function __construct(mixed $ruleset, array $config) {
    $this->config = $config;
    $this->ruleset = $ruleset;
		$this->processor = new RuleLibrary($this->config);
	}

  public function check_post(array &$post, $last_author = '',) {
		$result = array('valid' => 'yes', 'reason' => '');

		foreach ($this->ruleset['rules'] as $rule) {
			$rule['last_author'] = $last_author;
			if ($this->config['debug'] >= 2) { fwrite(STDERR, "    DEBUG: Checking Rule ".$rule['name']."\n"); }
			// Default to this. This is the field in the post we are going to use
			
			$ret = $this->processor->apply_rule($post, $rule);
			if (is_null($ret)) {
				print "Rule not found: ".$rule['name']."\n";
			}
			return $ret;
		}

  }

	public function get_summary(array $posts) {
		$summary = array(
			'groups' => [],
			'authors' => []
		);
	
		foreach ($posts as $p) {
			if ($p['valid']) {      
				// Sum the groups
				if (isset($p['group'])) {
					$group = strtoupper($p['group']);
	
					if (!isset($summary['groups'][$group])) {
							$summary['groups'][$group] = 0;
					}    
					$summary['groups'][$group]++;
	
					// Sum the authors
					if (!isset($summary['authors'][$p['author']])) {
							$summary['authors'][$p['author']] = 0;
					}
					$summary['authors'][$p['author']]++;
				}
			}
		}
		return $summary;	
	}
}