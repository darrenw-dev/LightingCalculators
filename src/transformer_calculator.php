// show calculator
add_shortcode("transformer-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">LED Power Supply Calculator</div>

    <form class="form-calculator" id="transformer-calculator">

        <div class="form-group">
            <label class="control-label" for="transformer-input-1">Input</label>
            <div class="radio-group" id="transformer-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Voltage/Current</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Power</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="transformer-input-2">Voltage</label>
            <input type="text" class="input-field" id="transformer-input-2" name="voltage" value="12">
            <span class="select-field">V</span>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="transformer-input-3">Current</label>
            <input type="text" class="input-field" id="transformer-input-3" name="current">
            <span class="select-field">A</span>
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="transformer-input-4">Power</label>
            <input type="text" class="input-field" id="transformer-input-4" name="power">
            <span class="select-field">W</span>
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="transformer-input-5">Reserve</label>
            <input type="text" class="input-field" id="transformer-input-5" name="reserve" value="20">
            <span class="select-field">%</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "transformer-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$voltage = floatval($_POST['voltage']);
	$current = floatval($_POST['current']);
	$power   = floatval($_POST['power']);
	$reserve = floatval($_POST['reserve']);

	switch ($_POST['calctype'])
	{
		case '1':
			if ($voltage > 0 && $voltage <= 48 && $current > 0 && $current <= 100 && $reserve > 0 && $reserve <= 100) {
				$resultPower = $voltage * $current * (1 + $reserve/100);
				$result = sprintf('The LED power supply should have an output power of <strong>%.1f watts</strong>', $resultPower);
			}
			break;

		case '2':
			if ($power > 0 && $power <= 10000 && $reserve > 0 && $reserve <= 100) {
				$resultPower = $power * (1 + $reserve/100);
				$result = sprintf('The LED power supply should have an output power of <strong>%.1f watts</strong>', $resultPower);
			}
			break;
	}
	echo $result;
	exit;
}