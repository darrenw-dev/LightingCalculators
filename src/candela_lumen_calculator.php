// show calculator
add_shortcode("candela-lumen-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Candela Lumen Calculator</div>

    <form class="form-calculator" id="candela-lumen-calculator">

		<div class="form-group">
            <label class="control-label" for="candela-lumen-input-1">Conversion</label>
            <div class="radio-group" id="candela-lumen-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1" checked>Candela to Lumen</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2"        >Lumen to Candela</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="candela-lumen-input-2">Candela</label>
            <input type="text" class="input-field" id="candela-lumen-input-2" name="candela">
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="candela-lumen-input-3">Lumens</label>
            <input type="text" class="input-field" id="candela-lumen-input-3" name="lumen">
        </div>

        <div class="form-group input-group option-1 option-2">
            <label class="control-label" for="candela-lumen-input-4">Beam Angle</label>
            <input type="text" class="input-field" id="candela-lumen-input-4" name="angle">
            <span class="select-field">°</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "candela-lumen-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$candela  = floatval($_POST['candela']);
	$lumen    = floatval($_POST['lumen']);
	$angle    = floatval($_POST['angle']);

	switch ($_POST['calctype'])
	{
		case '1':
			if ($candela > 0 && $candela <= 100000 && $angle > 0 && $angle < 180) {
				
				$sr = 2 * pi() * (1 - cos(deg2rad($angle / 2)));
				$lumen = $candela * $sr;

				$result = sprintf('The luminous flux is <strong>%.1f lumens</strong>', $lumen);
			}
			break;

		case '2':
			if ($lumen > 0 && $lumen <= 20000 && $angle > 0 && $angle < 180) {

				$sr = 2 * pi() * (1 - cos(deg2rad($angle / 2)));
				$candela = $lumen / $sr;

				$result = sprintf('The luminous intensity is <strong>%.1f candela</strong>', $candela);
			}
			break;
	}
	echo $result;
	exit;
}