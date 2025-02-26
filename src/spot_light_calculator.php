// show calculator
add_shortcode("spot-light-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Spot Light Calculator</div>

    <form class="form-calculator" id="spot-light-calculator">

        <div class="form-group">
            <p class="form-title">Recessed Lights</p>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-1">Beam Angle</label>
            <input type="text" class="input-field" id="spot-light-input-1" name="angle" value="120">
            <span class="select-field">°</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-2">Lumens</label>
            <input type="text" class="input-field" id="spot-light-input-2" name="lumen" value="600">
            <span class="select-field">lm</span>
        </div>

        <div class="form-group">
            <p class="form-title">Illuminance</p>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-3">Lux</label>
            <input type="text" class="input-field" id="spot-light-input-3" name="lux" value="100">
            <span class="select-field">lx</span>
        </div>

        <div class="form-group">
            <p class="form-title">Room Dimensions</p>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-4">Height</label>
            <input type="text" class="input-field" id="spot-light-input-4" name="height" value="9">
            <span class="select-field">feet</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-5">Length</label>
            <input type="text" class="input-field" id="spot-light-input-5" name="length" value="24">
            <span class="select-field">feet</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="spot-light-input-6">Width</label>
            <input type="text" class="input-field" id="spot-light-input-6" name="width" value="16">
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
if (isset($_POST["action"]) && $_POST['action'] == "spot-light-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$angle  = floatval($_POST['angle']);
	$lumen  = floatval($_POST['lumen']);
	$lux    = floatval($_POST['lux']);
	$height = floatval($_POST['height']);
	$width  = floatval($_POST['width']);
	$length = floatval($_POST['length']);

	$roomArea = $width * $length;

	if ($angle > 0 && $angle < 180 && $lumen > 0 && $lumen <= 20000 && $lux > 0 && $lux <= 100000
		&& $height > 0 && $height <= 10 && $width > 0 && $width <= 200 && $length > 0 && $length <= 200) {

		$diameter = 2 * $height * tan(deg2rad($angle)/2);
		$spotArea = pi() * pow(($diameter/2), 2);
		$overlap = ($diameter/2) * 0.5;
		$distance = $diameter * 1.5;
		$spotLux = $lumen / $spotArea;
		$spotLux = $spotLux / pow(0.3048, 2);
		$luxFactor = $lux / $spotLux;
		$luxFactor = $luxFactor < 1 ? 1 : $luxFactor;
		$spots = $luxFactor * ($length / ($diameter - $overlap)) * ($width / ($diameter - $overlap));
		$spotsRounded = intval(round($spots));
		$result = sprintf('To illuminate an area of <strong>%.1f ft²</strong> you need <strong>%d spots</strong> with the specified properties.', $roomArea, $spotsRounded);
	}
	echo $result;
	exit;
}