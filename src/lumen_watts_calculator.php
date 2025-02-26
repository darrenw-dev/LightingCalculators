// show calculator
add_shortcode("lumen-watts-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Lumen Watt Calculator</div>

    <form class="form-calculator" id="lumen-watts-calculator">

        <div class="form-group">
            <label class="control-label" for="lumen-watts-input-1">Conversion</label>
            <div class="radio-group" id="lumen-watts-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1"        >Lumens to Watts</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2" checked>Watts to Lumens</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="lumen-watts-input-2">Lumens</label>
            <input type="text" class="input-field" id="lumen-watts-input-2" name="lumen">
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="lumen-watts-input-3">Watts</label>
            <input type="text" class="input-field" id="lumen-watts-input-3" name="watts">
        </div>

        <div class="form-group">
            <label class="control-label" for="lumen-watts-input-4">Compare with</label>
            <div class="radio-group" id="lumen-watts-input-4">
                <div class="radio-element"><label><input type="radio" name="comptype" value="1" checked>Light bulb</label></div>
                <div class="radio-element"><label><input type="radio" name="comptype" value="2"        >Halogen lamp</label></div>
                <div class="radio-element"><label style="font-size: 15.5px;"><input type="radio" name="comptype" value="3"        >Energy-saving lamp</label></div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-button">
				'.wp_nonce_field('calc', '_wpnonce', false, false).'
                <button class="calc-button" disabled data-start="Calculate">Please enable JavaScript to use this tool</button>
            </div>
        </div>
    </form>
	
    <div class="calc-result" data-start="Please start calculation"></div>
    <div class="calc-notice">The tools in this website are provided "as is" without any warranty of any kind.</div>
</div>
';
});

// do calculation
if (isset($_POST["action"]) && $_POST['action'] == "lumen-watts-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';

	$lumen = floatval($_POST['lumen']);
	$watts = floatval($_POST['watts']);
	$inputVal = $_POST['calctype'] == '1' ? $lumen : $watts;

	// check input range
	if ($inputVal == 0) {
		echo 'Input field is empty';
		exit;
	}
	if ($inputVal < 0 || $inputVal > 20000) {
		echo 'Invalid range of value(s)';
		exit;
	}
	
	// watts/lumen pairs
	$bulb = array(
        25 => 230,
        40 => 430,
        60 => 730,
        75 => 970,
        100 => 1380
    );
	$halogen = array(
        25 => 300,
        40 => 500,
        60 => 900,
        75 => 1200,
        100 => 1700
    );
	$esl = array(
        5 => 180,
        7 => 290,
        9 => 400,
        11 => 530,
        14 => 730
    );
	
	function calculateLumenFromWatt($valueArray, $watt) {
        $lumen     = 0;
        $arrayKeys = array_keys($valueArray);
        $firstKey  = current($arrayKeys);
        $lastKey   = end($arrayKeys);

        if ($watt <= $firstKey) {
            $lumen = $valueArray[$firstKey] / $firstKey * $watt;
        } else if ($watt >= $lastKey) {
            $lumen = $valueArray[$lastKey] / $lastKey * $watt;
        } else {
            $lower = null;
            $upper = null;
            $i = 0;
            while($watt > $arrayKeys[$i]) {
                $lower = $arrayKeys[$i];
                $upper = $arrayKeys[$i+1];
                $i++;
            }
            $lumenDiff = $valueArray[$upper] - $valueArray[$lower];
            $wattDiff  = $upper - $lower;
            $part      = $watt - $lower;
            $lumen     = $lumenDiff / $wattDiff * $part + $valueArray[$lower];;
        }
        return round($lumen);
    }

	switch ($_POST['calctype'])
	{
		case '1':
			switch ($_POST['comptype'])
			{
				case '1':
					$result = calculateLumenFromWatt(array_flip($bulb), $inputVal);

					$result = sprintf('A lamp with <strong>%u lumens</strong> is as bright as a <strong>%u watts</strong> light bulb', $inputVal, $result);
					break;

				case '2':
					$result = calculateLumenFromWatt(array_flip($halogen), $inputVal);

					$result = sprintf('A lamp with <strong>%u lumens</strong> is as bright as a <strong>%u watts</strong> halogen lamp', $inputVal, $result);
					break;

				case '3':
					$result = calculateLumenFromWatt(array_flip($esl), $inputVal);

					$result = sprintf('A lamp with <strong>%u lumens</strong> is as bright as a <strong>%u watts</strong> energy-saving lamp', $inputVal, $result);
					break;
			}
			break;

		case '2':
			switch ($_POST['comptype'])
			{
				case '1':
					$result = calculateLumenFromWatt($bulb, $inputVal);

					$result = sprintf('A light bulb with <strong>%u watts</strong> has a luminous flux of <strong>%u lumens</strong>', $inputVal, $result);
					break;

				case '2':
					$result = calculateLumenFromWatt($halogen, $inputVal);

					$result = sprintf('A halogen lamp with <strong>%u watts</strong> has a luminous flux of <strong>%u lumens</strong>', $inputVal, $result);
					break;

				case '3':
					$result = calculateLumenFromWatt($esl, $inputVal);

					$result = sprintf('An energy-saving lamp with <strong>%u watts</strong> has a luminous flux of <strong>%u lumens</strong>', $inputVal, $result);
					break;
			}
			break;
	}
	
	echo $result;
	exit;
}