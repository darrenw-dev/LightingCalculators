// show calculator
add_shortcode("power-consumption-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">Lamp Power Consumption Calculator</div>

    <form class="form-calculator" id="power-consumption-calculator">

        <div class="form-group">
        </div>

        <div class="form-group">
            <label class="control-label" for="power-consumption-input-1">Power</label>
            <input type="text" class="input-field" id="power-consumption-input-1" name="power" value="60">
            <span class="select-field">Watts</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="power-consumption-input-2">Burn Time</label>
            <input type="text" class="input-field" id="power-consumption-input-2" name="burn-time" value="6">
            <span class="select-field">h/Day</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="power-consumption-input-3">Quantity</label>
            <input type="text" class="input-field" id="power-consumption-input-3" name="quantity" value="1">
            <span class="select-field">Lamps</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="power-consumption-input-4">Electricity Rate</label>
            <input type="text" class="input-field" id="power-consumption-input-4" name="electricity-rate" value="0.15">
            <span class="select-field" style="padding-left: 0 !important;">$/kWh</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "power-consumption-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$power           = floatval($_POST['power']);
	$electricityRate = floatval($_POST['electricity-rate']);
	$burnTime        = floatval($_POST['burn-time']);
	$quantity        = floatval($_POST['quantity']);

	if ($power > 0 && $power < 1000 && $electricityRate > 0 && $electricityRate < 10 && $burnTime > 0 && $burnTime <= 24 && $quantity > 0 && $quantity < 1000) {

		$ledPowerUsagePerDay   = round($power/1000 * $burnTime * $quantity, 3);
		$ledPowerUsagePerMonth = round($power/1000 * $burnTime * $quantity * 365/12, 2);
		$ledPowerUsagePerYear  = round($power/1000 * $burnTime * $quantity * 365, 2);

		$ledEnergyCostsPerDay   = number_format($ledPowerUsagePerDay * $electricityRate, 2);
		$ledEnergyCostsPerMonth = number_format($ledPowerUsagePerMonth * $electricityRate, 2);
		$ledEnergyCostsPerYear  = number_format($ledPowerUsagePerYear * $electricityRate, 2);

		$result = '
<table class="wp-block-table is-style-stripes">
    <thead>
        <tr>
            <th style="text-align: center;">Time Period</th>
            <th style="text-align: center;">Power Consumption</th>
            <th style="text-align: center;">Energy Costs</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">Daily</td>
            <td style="text-align: center;">'.$ledPowerUsagePerDay.' kWh</td>
            <td style="text-align: center;">'.$ledEnergyCostsPerDay.' $</td>
        </tr>
        <tr>
            <td style="text-align: center;">Monthly</td>
            <td style="text-align: center;">'.$ledPowerUsagePerMonth.' kWh</td>
            <td style="text-align: center;">'.$ledEnergyCostsPerMonth.' $</td>
        </tr>
        <tr>
            <td style="text-align: center;">Annually</td>
            <td style="text-align: center;">'.$ledPowerUsagePerYear.' kWh</td>
            <td style="text-align: center;">'.$ledEnergyCostsPerYear.' $</td>
        </tr>
    </tbody>
</table>';
	}
	echo $result;
	exit;
}