// show calculator
add_shortcode("lux-watt-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Lux Watt Calculator</div>

    <form class="form-calculator" id="lux-watt-calculator">

        <div class="form-group">
            <label class="control-label" for="lux-watt-input-1">Conversion</label>
            <div class="radio-group" id="lux-watt-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Lux to Watt</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Watt to Lux</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="lux-watt-input-2">Lux</label>
            <input type="text" class="input-field" id="lux-watt-input-2" name="lux">
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="lux-watt-input-3">Watts</label>
            <input type="text" class="input-field" id="lux-watt-input-3" name="watt">
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="lux-watt-input-4">Area</label>
            <input type="text" class="input-field" id="lux-watt-input-4" name="area" value="1">
            <span class="select-field">ft²</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "lux-watt-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$lux  = floatval($_POST['lux']);
	$watt = floatval($_POST['watt']);
	$area = floatval($_POST['area']);
	$k = 683;

	switch ($_POST['calctype'])
	{
		case '1':
			if ($lux > 0 && $lux <= 100000 && $area > 0 && $area <= 1000) {

				$watt = ($area * $lux) / $k;
				$watt = $watt * pow(0.3048, 2);
				$result = sprintf('<strong>%.0f lux</strong> for an area of <strong>%.1f ft²</strong> equals a radiation output of <strong>%.2f watts</strong>', $lux, $area, $watt);

			}
			break;

		case '2':
			if ($watt > 0 && $watt <= 20000 && $area > 0 && $area <= 1000) {

				$lux = ($k * $watt) / $area;
				$lux = $lux / pow(0.3048, 2);
				$result = sprintf('<strong>%.1f watts</strong> radiation output for an area of <strong>%.1f ft²</strong> equals an illuminance of <strong>%.0f lux</strong>', $watt, $area, $lux);
			}
			break;
	}
	echo $result;
	exit;
}