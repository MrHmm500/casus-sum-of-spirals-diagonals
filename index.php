<?php
$jsonTestCases = file_get_contents('testcases.json');
$testCases = json_decode($jsonTestCases);
foreach($testCases as $caseName => $caseData) {
    echo "-----------------------------------<br />";
    echo $caseName . ' wordt getest<br />';
    echo 'expected output: <br />';
    echo str_replace("\n", '<br />', str_replace(' ', '&nbsp;', $caseData->expectedOutput)) . '<br /><br />';

    $input = $caseData->input;

    echo "-----------------------------------<br />";
    echo "actual output:<br />";

    $spiral = createSpiral($input);
    $spiral = fillSpiral($spiral);
    echo calculateCross($spiral, $input) . '<br />';
}

function createSpiral($size): array {
    $spiral = [];
    for ($x = 0; $x < $size; $x++) {
        $spiral[$x] = [];
        for ($y = 0; $y < $size; $y++) {
            $spiral[$x][$y] = 0;
        }
    }
    return $spiral;
}

function fillSpiral(array $spiral): array {
    $filled = false;
    $x = 0;
    $y = 0;
    $direction = 'r';
    $i = 1;
    $spiral[0][0] = 1;

    // todo: refactor, dit is niet schaalbaar
    while ($filled == false) {
        $i++;
        $nextDirection = null;
        if ($direction == 'u') {
            $y--;
            if(!isset($spiral[$y - 1][$x]) || $spiral[$y-1][$x] !== 0) {
                $nextDirection = 'r';
            }
            if ((isset($spiral[$y-1][$x]) === false || $spiral[$y-1][$x] !== 0) && !isset($spiral[$y][$x + 1]) || $spiral[$y][$x+1] !== 0) {
                $filled = true;
            }
        }
        if ($direction == 'r') {
            $x++;
            if(!isset($spiral[$y][$x + 1]) || $spiral[$y][$x+1] !== 0) {
                $nextDirection = 'd';
            }
            if ((!isset($spiral[$y][$x + 1]) || $spiral[$y][$x+1] !== 0) && (!isset($spiral[$y + 1][$x]) || $spiral[$y+1][$x] !== 0)) {
                $filled = true;
            }
        }
        if ($direction == 'd') {
            $y++;
            if(!isset($spiral[$y + 1][$x]) || $spiral[$y+1][$x] !== 0) {
                $nextDirection = 'l';
            }
            if ((isset($spiral[$y+1][$x]) === false || $spiral[$y+1][$x] !== 0) && !isset($spiral[$y][$x - 1]) || $spiral[$y][$x-1] !== 0) {
                $filled = true;
            }
        }
        if ($direction == 'l') {
            $x--;
            if(!isset($spiral[$y][$x - 1]) || $spiral[$y][$x-1] !== 0) {
                $nextDirection = 'u';
            }
            if ((isset($spiral[$y][$x-1]) === false || $spiral[$y][$x-1] !== 0) && !isset($spiral[$y - 1][$x]) || $spiral[$y-1][$x] !== 0) {
                $filled = true;
            }
        }
        $spiral[$y][$x] = $i;
        if($nextDirection !== null) {
            $direction = $nextDirection;
        }
    }

    return $spiral;
}

function calculateCross($spiral, $maxSize) {
    $alreadyAdded = [];
    $sum = 0;
    for($x = 0; $x < $maxSize; $x++) {
        $alreadyAdded[$x] = [];
        $alreadyAdded[$x][$x] = true;
        $sum += $spiral[$x][$x];
    }
    $y = $maxSize -1;
    for($x = 0; $x < $maxSize; $x++) {
        $sum += isset($alreadyAdded[$x][$y]) ? 0 : $spiral[$x][$y];
        $y--;
    }
    return $sum;
}