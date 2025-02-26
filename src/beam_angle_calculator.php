// show calculator
add_shortcode("beam-angle-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Beam Angle Calculator</div>

    <form class="form-calculator" id="beam-angle-calculator">

        <div class="form-group">
            <label class="control-label" for="beam-angle-input-1">Conversion</label>
            <div class="radio-group" id="beam-angle-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Beam Angle</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Diameter</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="3"        >Distance</label></div>
            </div>
        </div>

        <div class="form-group input-group option-2 option-3">
            <label class="control-label" for="beam-angle-input-2">Beam Angle</label>
            <input type="text" class="input-field" id="beam-angle-input-2" name="angle">
            <span class="select-field">°</span>
        </div>

        <div class="form-group input-group option-1 option-3">
            <label class="control-label" for="beam-angle-input-3">Diameter</label>
            <input type="text" class="input-field" id="beam-angle-input-3" name="diameter">
            <span class="select-field">feet</span>
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="beam-angle-input-4">Distance</label>
            <input type="text" class="input-field" id="beam-angle-input-4" name="distance">
            <span class="select-field">feet</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "beam-angle-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$angle    = floatval($_POST['angle']);
	$diameter = floatval($_POST['diameter']);
	$distance = floatval($_POST['distance']);
	$diameter = $diameter * 0.3048;
	$distance = $distance * 0.3048;

	switch ($_POST['calctype'])
	{
		case '1':
			if ($diameter > 0 && $diameter <= 100 && $distance > 0 && $distance <= 100) {
				$angle = rad2deg(2 * (atan($diameter / (2 * $distance))));

				$result = sprintf('The beam angle is <strong>%.1f °</strong>', $angle);
			}
			break;

		case '2':
			if ($angle > 0 && $angle < 180 && $distance > 0 && $distance <= 100) {
				$diameter = 2 * $distance * tan(deg2rad($angle)/2);
				$diameter = $diameter / 0.3048;

				$result = sprintf('The spot light diameter is <strong>%.1f feet</strong>', $diameter);
			}
			break;

		case '3':
			if ($angle > 0 && $angle < 180 && $diameter > 0 && $diameter <= 100) {
				$distance = $diameter / (2 * tan(deg2rad($angle)/2));
				$distance = $distance / 0.3048;

				$result = sprintf('The distance is <strong>%.1f feet</strong>', $distance);
			}
			break;
	}
	echo $result;
	exit;
}