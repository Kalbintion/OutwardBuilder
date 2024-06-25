<?php
include("./lib/polyfill.php");
include("./lib/generators.php");

$dataFiles = array(
	"skillTrees" => array( "file" => "./data/skillTrees.json"),
	"skills" => array("file" => "./data/skills.json"),
	"gear" => array("file" => "./data/gear.json")
);

function formatDescription($desc) {
	return str_replace(array("\n"), array("<br />"), $desc);
}
?>
<html>
<head>
	<title>Outward - Class Builder</title>
	<link rel="stylesheet" href="css/main.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<script src="./js/jquery-3.7.1.min.js"></script>
	<script src="./js/phpjs.js"></script>
	<script src="./js/main.js"></script>
</head>
<body>
	<div class="section builder">
		<div class="builder--tabber">
			<div class="builder--skill_trees">
				<div tabindex="0" class="skill_trees--opt" data-target="gear">
					<div class="skill_trees--icon">
						<img src="./img/skillTree/gear.webp">
					</div>
					<div class="skill_trees--name">Gear</div>
				</div>
				<?PHP echo generateSkillTreeTabber(json_decode(file_get_contents($dataFiles['skillTrees']['file']), true)); ?>
			</div>
		</div>
		<div class="builder--gear" id="tree__gear">
			<div class="builder--tree--name col-all">Gear</div>
			<div class="builder--tree--gear">
				<div class="gear--left-rail">
					<div class="gear--slot gear--primary" data-target="primary">
						<img src="./img/slots/weapon.png">
					</div>
					<div class="gear--slot gear--offhand" data-target="offhand">
						<img src="./img/slots/offhand.png">
					</div>
					<div class="gear--slot gear--ammo" data-target="ammo">
						<img src="./img/slots/Ammo.png">
					</div>
				</div>
				<div class="gear--right-rail">
					<div class="gear--slot gear--head" data-target="head">
						<img src="./img/slots/helm.png">
					</div>
					<div class="gear--slot gear--chest" data-target="chest">
						<img src="./img/slots/armor.png">
					</div>
					<div class="gear--slot gear--back" data-target="back">
						<img src="./img/slots/bag.png">
					</div>
					<div class="gear--slot gear--boots" data-target="boots">
						<img src="./img/slots/boots.png">
					</div>
				</div>
			</div>
			<div class="gear--selectors">
				<?PHP echo generateGearListings(json_decode(file_get_contents($dataFiles['gear']['file']), true)); ?>
			</div>
			<div class="builder--quick-slots">
				<div class="quick-slots slot-1"></div>
				<div class="quick-slots slot-2"></div>
				<div class="quick-slots slot-3"></div>
				<div class="quick-slots slot-4"></div>
				<div class="quick-slots slot-5"></div>
				<div class="quick-slots slot-6"></div>
				<div class="quick-slots slot-7"></div>
				<div class="quick-slots slot-8"></div>
			</div>
		</div>
		<?PHP echo generateSkillTrees(json_decode(file_get_contents($dataFiles['skills']['file']), true)); ?>
	</div>
	<div class="section totals">
		<div class="totals--header">Build Data</div>
		<div class="totals--skills">
			<div class="total-skills__total">Total: <span id="total-skills__total">0</span></div>
			<div class="total-skills__passive">Passive Skills: <span id="total-skills__passive">0</span></div>
			<div class="total-skills__active">Active Skills: <span id="total-skills__active">0</span></div>
		</div>
		<div class="totals--costs">
			<div class="costs__breakthrough">Breakthrough: <span id="costs__breakthrough">0</span><img class="icon icon--breakthrough" src="./img/statIcons/Breakthrough.webp" /></div>
			<div class="costs__silver">Silver: <span id="costs__silver">0</span><img class="icon icon--silver" src="./img/statIcons/Silver.webp" /></div>
			<div class="costs__medal">Medal: <span id="costs__medal">0</span><img class="icon icon--medal" src="./img/statIcons/Medal.webp" /></div>
			<div class="costs__other">Other Costs: <span id="costs__other">None</span></div>
		</div>
		<div class="totals--skill_bonuses">
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Vitals</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__health">Health: <span id="skill-bonuses__health">0</span><img class="icon icon--health" src="./img/statIcons/Health.webp" /></div>
					<div class="skill-bonuses skill-bonuses__stamina">Stamina: <span id="skill-bonuses__stamina">0</span><img class="icon icon--stamina" src="./img/statIcons/Stamina.webp" /></div>
					<div class="skill-bonuses skill-bonuses__mana">Mana: <span id="skill-bonuses__mana">0</span><img class="icon icon--mana" src="./img/statIcons/Mana.webp" /></div>
					<div class="skill-bonuses skill-bonuses__mana_regen">Mana Regen: <span id="skill-bonuses__mana_regen">0</span><img class="icon icon--mana" src="./img/statIcons/Mana.webp" /> / s</div>
				</div>
			</div>
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Needs</div>
				</div>
				<div class="totals--row-content">
					<!-- <div class="skill-bonuses skill-bonuses__food">Food: <span id="skill-bonuses__food">N/A</span></div>
					<div class="skill-bonuses skill-bonuses__drink">Drink: <span id="skill-bonuses__drink">N/A</span></div>
					<div class="skill-bonuses skill-bonuses__sleep">Sleep: <span id="skill-bonuses__sleep">N/A</span></div> -->
					<div class="skill-bonuses skill-bonuses__hot">Hot: <span id="skill-bonuses__hot">0</span></div>
					<div class="skill-bonuses skill-bonuses__cold">Cold: <span id="skill-bonuses__cold">0</span></div>
				</div>
			</div>
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Basic</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__speed">Move Speed: <span id="skill-bonuses__speed">0</span></div>
					<div class="skill-bonuses skill-bonuses__stam_cost">Stamina Cost: <span id="skill-bonuses__stam_cost">0</span></div>
					<div class="skill-bonuses skill-bonuses__mana_cost">Mana Cost: <span id="skill-bonuses__mana_cost">0</span></div>
					<div class="skill-bonuses skill-bonuses__pouch">Pouch Bonus: <span id="skill-bonuses__pouch">0</span></div>
					<div class="skill-bonuses skill-bonuses__stealth">Stealth: <span id="skill-bonuses__stealth">0</span></div>
					<div class="skill-bonuses skill-bonuses__cdr">Cooldown Reduction: <span id="skill-bonuses__cdr">0</span></div>
				</div>
			</div>
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Combat</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__atk_spd">Attack Speed <span id="skill-bonuses__atk_spd">0</span></div>
					<div class="skill-bonuses skill-bonuses__physical_dmg">Physical <span id="skill-bonuses__physical_dmg">0</span></div>
					<div class="skill-bonuses skill-bonuses__ethereal_dmg">Ethereal <span id="skill-bonuses__ethereal_dmg">0</span></div>
					<div class="skill-bonuses skill-bonuses__decay_dmg">Decay <span id="skill-bonuses__decay_dmg">0</span></div>
					<div class="skill-bonuses skill-bonuses__lightning_dmg">Lightning <span id="skill-bonuses__lightning_dmg">0</span></div>
					<div class="skill-bonuses skill-bonuses__frost_dmg">Frost <span id="skill-bonuses__frost_dmg">0</span></div>
					<div class="skill-bonuses skill-bonuses__fire_dmg">Fire <span id="skill-bonuses__fire_dmg">0</span></div>
				</div>
			</div>
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Resists</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__impact">Impact <span id="skill-bonuses__impact">0</span></div>
					<div class="skill-bonuses skill-bonuses__physical">Physical <span id="skill-bonuses__physical">0</span></div>
					<div class="skill-bonuses skill-bonuses__ethereal">Ethereal <span id="skill-bonuses__ethereal">0</span></div>
					<div class="skill-bonuses skill-bonuses__decay">Decay <span id="skill-bonuses__decay">0</span></div>
					<div class="skill-bonuses skill-bonuses__lightning">Lightning <span id="skill-bonuses__lightning">0</span></div>
					<div class="skill-bonuses skill-bonuses__frost">Frost <span id="skill-bonuses__frost">0</span></div>
					<div class="skill-bonuses skill-bonuses__fire">Fire <span id="skill-bonuses__fire">0</span></div>
					<div class="skill-bonuses skill-bonuses__corrupt">Corruption <span id="skill-bonuses__corrupt">0</span></div>
					<div class="skill-bonuses skill-bonuses__status_resist">Status Resistance <span id="skill-bonuses__status_resist">0</span></div>
				</div>
			</div>
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Defensive</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__protection">Protection <span id="skill-bonuses__protection">0</span></div>
					<div class="skill-bonuses skill-bonuses__barrier">Barrier <span id="skill-bonuses__barrier">0</span></div>
				</div>
			</div>
		</div>
	</div>
	<div style="display: none;" class="section context skill">
		<div class="context--header">Assign To Slot..</div>
		<div class="context--content">
			<div class="context--target" data-tar="slot-1">Slot 1</div>
			<div class="context--target" data-tar="slot-2">Slot 2</div>
			<div class="context--target" data-tar="slot-3">Slot 3</div>
			<div class="context--target" data-tar="slot-4">Slot 4</div>
			<div class="context--target" data-tar="slot-5">Slot 5</div>
			<div class="context--target" data-tar="slot-6">Slot 6</div>
			<div class="context--target" data-tar="slot-7">Slot 7</div>
			<div class="context--target" data-tar="slot-8">Slot 8</div>
		</div>
	</div>
	<div style="display: none;" class="section context item">
		<div class="context--header">Assign To Slot..</div>
		<div class="context--content">
			<div class="context--target" data-tar="slot-1">Slot 1</div>
			<div class="context--target" data-tar="slot-2">Slot 2</div>
			<div class="context--target" data-tar="slot-3">Slot 3</div>
			<div class="context--target" data-tar="slot-4">Slot 4</div>
			<div class="context--target" data-tar="slot-5">Slot 5</div>
			<div class="context--target" data-tar="slot-6">Slot 6</div>
			<div class="context--target" data-tar="slot-7">Slot 7</div>
			<div class="context--target" data-tar="slot-8">Slot 8</div>
		</div>
	</div>
	<div class="section tools">
		<div tabindex="0" role="button" class="tools-btn tools--clear" title="Resets current build to be empty.">Clear Build</div>
		<div tabindex="0" role="button" class="tools-btn tools--toggle-active" title="Toggles current tree to be all selected or unselected.">Toggle Active Tree</div>
		<div tabindex="0" role="button" class="tools-btn tools--toggle-zero-stats active" title="Toggles being able to see build stats that are 0.">Toggle Zero Visibility</div>
	</div>
	<div class="section footer"></div>
</body>
</html>