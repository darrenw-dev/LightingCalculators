// show calculator
add_shortcode("illuminance-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Illuminance Calculator</div>

    <form class="form-calculator" id="illuminance-calculator">

		<div class="form-group">
            <p class="form-title">Illuminants</p>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="illuminance-input-1">Beam Angle</label>
            <input type="text" class="input-field" id="illuminance-input-1" name="angle" value="120">
            <span class="select-field">°</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="illuminance-input-2">Lumens</label>
            <input type="text" class="input-field" id="illuminance-input-2" name="lumen" value="600">
            <span class="select-field">lm</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="illuminance-input-3">Quantity</label>
            <input type="text" class="input-field" id="illuminance-input-3" name="quantity" value="9">
            <span class="select-field">Lamps</span>
        </div>

        <div class="form-group">
            <p class="form-title">Room Dimensions</p>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="illuminance-input-4">Height</label>
            <input type="text" class="input-field" id="illuminance-input-4" name="height" value="9">
            <span class="select-field">feet</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="illuminance-input-5">Length</label>
            <input type="text" class="input-field" id="illuminance-input-5" name="length" value="24">
            <span class="select-field">feet</span>
        </div>

        <div class="form-group input-group">
            <label class="control-label" for="illuminance-input-6">Width</label>
            <input type="text" class="input-field" id="illuminance-input-6" name="width" value="16">
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
if (isset($_POST["action"]) && $_POST['action'] == "illuminance-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$angle    = floatval($_POST['angle']);
	$lumen    = floatval($_POST['lumen']);
	$quantity = floatval($_POST['quantity']);
	$height   = floatval($_POST['height']);
	$width    = floatval($_POST['width']);
	$length   = floatval($_POST['length']);
	$roomArea = $width * $length;

	if ($angle > 0 && $angle < 180 && $lumen > 0 && $lumen <= 20000 && $quantity > 0 && $quantity <= 1000
		&& $height > 0 && $height <= 10 && $width > 0 && $width <= 200 && $length > 0 && $length <= 200) {

		$diameter = 2 * $height * tan(deg2rad($angle)/2);
		$spotArea = pi() * pow(($diameter/2), 2);
		$spotAreaSum = $spotArea * $quantity;
		$areaFactor = $spotAreaSum / $roomArea;
		$lumenSum = $lumen * $quantity;
		$lux = $lumenSum / $roomArea;
		$lux = $lux / pow(0.3048, 2);

		$result = sprintf('The illuminance on the area of <strong>%.1f ft²</strong> is <strong>%d lux</strong>', $roomArea, $lux);
		if ($areaFactor < 1)
			$result .= '<br><strong>Imbalanced illumination:</strong> the size of the light circles is smaller than the room size';
	}	
	echo $result;
	exit;
}