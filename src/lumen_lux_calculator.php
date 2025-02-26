// show calculator
add_shortcode("lumen-lux-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Lumen Lux Calculator</div>

    <form class="form-calculator" id="lumen-lux-calculator">

		<div class="form-group">
			<label class="control-label" for="lumen-lux-input-1">Conversion</label>
            <div class="radio-group" id="lumen-lux-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Lux in Lumen</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Lumen in Lux</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="lumen-lux-input-2">Lux</label>
            <input type="text" class="input-field" id="lumen-lux-input-2" name="lux">
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="lumen-lux-input-3">Lumens</label>
            <input type="text" class="input-field" id="lumen-lux-input-3" name="lumen">
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="lumen-lux-input-4">Beam Angle</label>
            <input type="text" class="input-field" id="lumen-lux-input-4" name="angle">
            <span class="select-field">°</span>
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="lumen-lux-input-5">Distance</label>
            <input type="text" class="input-field" id="lumen-lux-input-5" name="distance">
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
if (isset($_POST["action"]) && $_POST['action'] == "lumen-lux-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$lux      = floatval($_POST['lux']);
	$lumen    = floatval($_POST['lumen']);
	$angle    = floatval($_POST['angle']);
	$distance = floatval($_POST['distance']);
	$distance = $distance * 0.3048;
	
	switch ($_POST['calctype'])
	{
		case '1':
			if ($lux > 0 && $lux <= 100000 && $angle > 0 && $angle < 180 && $distance > 0 && $distance <= 100) {
				$radius = $distance * tan(deg2rad($angle)/2);
				$radius = $radius / 0.3048;
				$area = pi() * pow($radius, 2);
				$lumen = $area * $lux;
				$lumen = $lumen / 10.764;

				$result = sprintf('The luminous flux for an area of <strong>%.1f ft²</strong> is <strong>%.1f lumens</strong>', $area, $lumen);
			}
			break;

		case '2':
			if ($lumen > 0 && $lumen <= 20000 && $angle > 0 && $angle < 180 && $distance > 0 && $distance <= 100) {
				$radius = $distance * tan(deg2rad($angle)/2);
				$radius = $radius / 0.3048;
				$area = pi() * pow($radius, 2);
				$lux = $lumen / $area;
				$lux = $lux * 10.764;

				$result = sprintf('The illuminance for an area of <strong>%.1f ft²</strong> is <strong>%.1f lux</strong>', $area, $lux);
			}
			break;
	}
	echo $result;
	exit;
}