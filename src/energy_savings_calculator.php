// show calculator
add_shortcode("energy-savings-calculator", function() {
	return '
<div class="calc-box">
    <div class="calc-box-title">LED Power Savings Calculator</div>

    <form class="form-calculator" id="energy-savings-calculator">

		<div class="form-group">
            <p class="form-title">New LED Lights</p>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-1">Power</label>
            <input type="text" class="input-field" id="energy-savings-input-1" name="led-power" value="6">
            <span class="select-field">Watts</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-2">Purchase Price</label>
            <input type="text" class="input-field" id="energy-savings-input-2" name="led-costs" value="8.99">
            <span class="select-field">$</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-3">Lifespan</label>
            <input type="text" class="input-field" id="energy-savings-input-3" name="led-lifetime" value="25000">
            <span class="select-field">Hours</span>
        </div>

        <div class="form-group">
            <p class="form-title">Old Existing Lights</p>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-4">Power</label>
            <input type="text" class="input-field" id="energy-savings-input-4" name="old-power" value="60">
            <span class="select-field">Watts</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-5">Purchase Price</label>
            <input type="text" class="input-field" id="energy-savings-input-5" name="old-costs" value="1.00">
            <span class="select-field">$</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-6">Lifespan</label>
            <input type="text" class="input-field" id="energy-savings-input-6" name="old-lifetime" value="1000">
            <span class="select-field">Hours</span>
        </div>

        <div class="form-group">
            <p class="form-title">General Information</p>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-7">Electricity Rate</label>
            <input type="text" class="input-field" id="energy-savings-input-7" name="electricity-rate" value="0.15">
            <span class="select-field" style="padding-left: 0 !important;">$/kWh</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-8">Burn Time</label>
            <input type="text" class="input-field" id="energy-savings-input-8" name="burn-time" value="6">
            <span class="select-field">h/Day</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="energy-savings-input-9">Quantity</label>
            <input type="text" class="input-field" id="energy-savings-input-9" name="quantity" value="1">
            <span class="select-field">Lamps</span>
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
if (isset($_POST["action"]) && $_POST['action'] == "energy-savings-calculator") {
	if (false === wp_verify_nonce($_POST["_wpnonce"], "calc")) {
		echo "Session expired, please reload the page";
		exit;
	}
	$result = 'Please check input values';
	
	$ledPower    = floatval($_POST['led-power']);
	$ledCosts    = floatval($_POST['led-costs']);
	$ledLifetime = floatval($_POST['led-lifetime']);
	$oldPower    = floatval($_POST['old-power']);
	$oldCosts    = floatval($_POST['old-costs']);
	$oldLifetime = floatval($_POST['old-lifetime']);
	$electricityRate = floatval($_POST['electricity-rate']);
	$burnTime        = floatval($_POST['burn-time']);
	$quantity        = floatval($_POST['quantity']);

	if ($ledPower > 0 && $ledPower < 1000 && $ledCosts > 0 && $ledCosts < 100 && $ledLifetime > 0 && $ledLifetime < 1000000
		&& $oldPower > 0 && $oldPower < 10000 && $oldCosts > 0 && $oldCosts < 100 && $oldLifetime > 0 && $oldLifetime < 100000
		&& $electricityRate > 0 && $electricityRate < 10 && $burnTime > 0 && $burnTime <= 24 && $quantity > 0 && $quantity < 1000) {

		$oldPowerUsagePerDay = round($oldPower/1000 * $burnTime * $quantity, 3);
		$ledPowerUsagePerDay = round($ledPower/1000 * $burnTime * $quantity, 3);
		$oldPowerUsagePerYear = round($oldPower/1000 * $burnTime * $quantity * 365, 2);
		$ledPowerUsagePerYear = round($ledPower/1000 * $burnTime * $quantity * 365, 2);
		$oldEnergyCostsPerDay = number_format($oldPowerUsagePerDay * $electricityRate, 2);
		$ledEnergyCostsPerDay = number_format($ledPowerUsagePerDay * $electricityRate, 2);
		$oldEnergyCostsPerYear = number_format($oldPowerUsagePerYear * $electricityRate, 2);
		$ledEnergyCostsPerYear = number_format($ledPowerUsagePerYear * $electricityRate, 2);

		$energySavingsPerYear = ($oldPowerUsagePerYear - $ledPowerUsagePerYear) * $electricityRate;
		$oldLifetimeInYears = $oldLifetime / ($burnTime * 365);
		$ledLifetimeInYears = $ledLifetime / ($burnTime * 365);
		$ledLifetimeEnergySavings = $energySavingsPerYear * $ledLifetimeInYears;
		$oldLifetimeCosts = $ledLifetimeInYears * $oldCosts * $quantity;
		$ledLifetimeCosts = $ledCosts * $quantity;


		$result = '
<table class="wp-block-table is-style-stripes">
    <thead>
        <tr>
            <th style="text-align: center;"></th>
            <th style="text-align: center;">Old Existing Lights</th>
            <th style="text-align: center;">New LED Lights</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align: center;">Daily Power Consumption</td>
            <td style="text-align: center;">'.$oldPowerUsagePerDay.' kWh</td>
            <td style="text-align: center;">'.$ledPowerUsagePerDay.' kWh</td>
        </tr>
        <tr>
            <td style="text-align: center;">Annual Power Consumption</td>
            <td style="text-align: center;">'.$oldPowerUsagePerYear.' kWh</td>
            <td style="text-align: center;">'.$ledPowerUsagePerYear.' kWh</td>
        </tr>
        <tr>
            <td style="text-align: center;">Daily Energy Costs</td>
            <td style="text-align: center;">'.$oldEnergyCostsPerDay.' $</td>
            <td style="text-align: center;">'.$ledEnergyCostsPerDay.' $</td>
        </tr>
        <tr>
            <td style="text-align: center;">Annual Energy Costs</td>
            <td style="text-align: center;">'.$oldEnergyCostsPerYear.' $</td>
            <td style="text-align: center;">'.$ledEnergyCostsPerYear.' $</td>
        </tr>
    </tbody>
</table>';

		$result .= '</br>';
		$result .= sprintf('The annual energy cost savings are <strong>%.2f $</strong>.', $energySavingsPerYear);
		$result .= '</br></br>';
		$result .= sprintf('With a daily burn time of <strong>%.1f hours</strong> the LED lamp has a lifespan of <strong>%.1f years</strong> in comparison to <strong>%.1f years</strong> of the old lamp.', $burnTime, $ledLifetimeInYears, $oldLifetimeInYears);
		$result .= '</br></br>';
		$result .= sprintf('The energy cost savings within the complete lifespan are <strong>%.2f $</strong>.', $ledLifetimeEnergySavings);
		$result .= '</br></br>';
		$result .= sprintf('Within this time there have been purchase costs of <strong>%.2f $</strong> for LED lamps in comprison to <strong>%.2f&nbsp;$</strong> for the old lamps.', $ledLifetimeCosts, $oldLifetimeCosts);
	}
	echo $result;
	exit;
}