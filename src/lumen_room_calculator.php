// show calculator
add_shortcode("lumen-room-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Lumen per Room Calculator</div>

    <form class="form-calculator" id="lumen-room-calculator">

        <div class="form-group">
            <label class="control-label" for="lumen-room-input-1">Room</label>
            <div class="radio-group" id="lumen-room-input-1">
                <div class="radio-element"><label><input type="radio" name="calctype" value="1"        >Hall & Stairs</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="2" checked>Living Room</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="3"        >Bath & Kitchen</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="4"        >Bedroom</label></div>
                <div class="radio-element"><label><input type="radio" name="calctype" value="5"        >Home Office</label></div>
            </div>
        </div>

        <div class="form-group input-group option-1">
            <label class="control-label" for="lumen-room-input-2">Lumens</label>
            <input type="text" class="input-field" id="lumen-room-input-2" name="lumen[corridor]" value="12">
            <span class="select-field">per ft²</span>
        </div>

        <div class="form-group input-group option-2">
            <label class="control-label" for="lumen-room-input-3">Lumens</label>
            <input type="text" class="input-field" id="lumen-room-input-3" name="lumen[living]" value="12">
            <span class="select-field">per ft²</span>
        </div>

        <div class="form-group input-group option-3">
            <label class="control-label" for="lumen-room-input-4">Lumens</label>
            <input type="text" class="input-field" id="lumen-room-input-4" name="lumen[bathroom]" value="30">
            <span class="select-field">per ft²</span>
        </div>

        <div class="form-group input-group option-4">
            <label class="control-label" for="lumen-room-input-5">Lumens</label>
            <input type="text" class="input-field" id="lumen-room-input-5" name="lumen[bedroom]" value="12">
            <span class="select-field">per ft²</span>
        </div>

        <div class="form-group input-group option-5">
            <label class="control-label" for="lumen-room-input-6">Lumens</label>
            <input type="text" class="input-field" id="lumen-room-input-6" name="lumen[office]" value="30">
            <span class="select-field">per ft²</span>
        </div>

        <div class="form-group input-group option-1 option-2 option-3 option-4 option-5">
            <label class="control-label" for="lumen-room-input-7">Room Size</label>
            <input type="text" class="input-field" id="lumen-room-input-7" name="area">
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
if (isset($_POST["action"]) && $_POST['action'] == "lumen-room-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$area = floatval($_POST['area']);
	$calcTypes = array(
        1 => 'corridor',
        2 => 'living',
        3 => 'bathroom',
        4 => 'bedroom',
        5 => 'office',
    );
	$calcType = $calcTypes[$_POST['calctype']];

	if (isset($_POST['lumen']) && is_array($_POST['lumen']) && array_key_exists($calcType, $_POST['lumen'])) {
		$lumen = intval($_POST['lumen'][$calcType]);
	} else {
		$lumen = 0;
	}

	if ($area > 0 && $area <= 200 && $lumen > 0 && $lumen <= 20000) {
		$lumenRoom = $area * $lumen;

		$result = sprintf('A <strong>%.1f ft²</strong> room with <strong>%d lm/ft²</strong> needs a total luminous flux of <strong>%d lumens</strong>', $area, $lumen, $lumenRoom);

	}
	echo $result;
	exit;
}