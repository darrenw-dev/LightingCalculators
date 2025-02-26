// show calculator
add_shortcode("aquarium-lighting-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Aquarium Lighting Calculator</div>

    <form class="form-calculator" id="aquarium-lighting-calculator">

        <div class="form-group">
            <label class="control-label" for="aquarium-lighting-input-1">Aquarium</label>
            <div class="radio-group" id="aquarium-lighting-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Dimensions</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Volume</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="aquarium-lighting-input-2">Length</label>
            <input type="text" class="input-field" id="aquarium-lighting-input-2" name="length" value="40">
            <span class="select-field">in</span>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="aquarium-lighting-input-3">Width</label>
            <input type="text" class="input-field" id="aquarium-lighting-input-3" name="width" value="15">
            <span class="select-field">in</span>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="aquarium-lighting-input-4">Height</label>
            <input type="text" class="input-field" id="aquarium-lighting-input-4" name="height" value="15">
            <span class="select-field">in</span>
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="aquarium-lighting-input-5">Liters</label>
            <input type="text" class="input-field" id="aquarium-lighting-input-5" name="liter" value="160">
        </div>

        <div class="form-group">
            <p class="form-title">Lighting Demand</p>
        </div>

        <div class="form-group">
            <label class="control-label" for="aquarium-lighting-input-6">Lumens / Liter</label>
            <input type="text" class="input-field" id="aquarium-lighting-input-6" name="demand" value="30">
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
if (isset($_POST["action"]) && $_POST['action'] == "aquarium-lighting-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$height = floatval($_POST['height']);
	$width  = floatval($_POST['width']);
	$length = floatval($_POST['length']);
	$liter  = floatval($_POST['liter']);
	$demand = floatval($_POST['demand']);

	switch ($_POST['calctype'])
	{
		case '1':
			if ($height > 0 && $height <= 10000 && $width > 0 && $width <= 10000 && $length > 0 && $length <= 10000 && $demand > 0 && $demand <= 1000) {

				$volume = $height * $width * $length / 1000;
				$volume = $volume / pow(0.3937008, 3);
				$lumen = $volume * $demand;

				$result = sprintf('The total light demand for a <strong>%.0f liter</strong> aquarium is <strong>%.0f lumen</strong>', $volume, $lumen);
			}
			break;

		case '2':
			if ($liter > 0 && $liter <= 10000 && $demand > 0 && $demand <= 1000) {

				$volume = $liter;
				$lumen = $volume * $demand;

				$result = sprintf('The total light demand for a <strong>%.0f liter</strong> aquarium is <strong>%.0f lumen</strong>', $volume, $lumen);
			}
			break;
	}
	echo $result;
	exit;
}