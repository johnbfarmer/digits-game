<?php

if ($argc !== 3) {
	die('please specify a comma-separated list of numbers to use follwed by a target, like 1,2,3 7');
}

$set = explode(',',$argv[1]);
$target = 1*$argv[2];

foreach ($set as $elt) {
	if (!is_numeric($elt)) {
		die($elt . ' is not an int');
	}
}

if (!is_numeric($target)) {
	die($target . ' is not an int');
}

$set = array_map('timesOne', $set);

branch($set, [], $target);

echo "done\n";

function branch($set, $ops, $target) {
	if (in_array($target, $set)) {
		return displaySolution($set, $ops, $target);
	}
	$ct = count($set);
	if ($ct === 1) {
		return false;
	}

	$i = 0;
	while ($i <= $ct - 2) {
		$j = $i + 1;
		while ($j <= $ct - 1) {
			$a = [];
			for ($k=0; $k<$ct; $k++) {
				if ($k === $i || $k === $j) {
					continue;
				}
				$a[] = $set[$k];
			}
			$x = $set[$i];
			$y = $set[$j];
			foreach(['+','-','*','/'] as $op) {
				getNewSetsAndRecurse(min($x,$y), max($x, $y), $a, $ops, $target, $op);
			}
			$j++;
		}
		$i++;
	}
}

function getNewSetsAndRecurse($x, $y, $set, $ops, $target, $op) {
	switch ($op) {
		case '+':
			$set[] = $x + $y;
			$ops[] = "$x + $y = ". ($x+$y);
			return branch($set, $ops, $target);
		case '-':
			$set[] = $y - $x;
			$ops[] = "$y - $x = ". ($y-$x);
			return branch($set, $ops, $target);
		case '*':
			$set[] = $x * $y;
			$ops[] = "$x x $y = ". ($x*$y);
			return branch($set, $ops, $target);
		default:
			if (!$x || !$y) {
				return false;
			}
			if (!($y % $x)) {
				$set[] = $y / $x;
				$ops[] = "$y ".chr(247)." $x = ". ($y/$x);
				return branch($set, $ops, $target);
			}
			return false;
	}
}

function displaySolution($set, $ops, $target) {
	foreach ($ops as $op) {
		print $op."\n";
	}
	print "\n";
}

function timesOne($x) {
	return 1 * $x;
}