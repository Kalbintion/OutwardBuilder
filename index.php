<?php
include("./lib/polyfill.php");

$dataFiles = array(
	"skillTrees" => array( "file" => "./data/skillTrees.json"),
	"skills" => array("file" => "./data/skills.json")
);

function generateSkillTreeTabber($data) {
	$out = "";
	
	foreach($data as $k => $v) {
		if($v['img'] == "AUTO")
			$v['img'] = genImgSkillTree($v['name']);
		
		$out .= '<div tabindex="0" class="skill_trees--opt" data-target="'.genSafeName($v['name']).'"><div class="skill_trees--icon"><img src="'.$v['img'].'" /></div><div class="skill_trees--name">'.$v['name'].'</div></div>';
	}

	return $out;
}

function generateSkillTrees($data) {
	$out = "";
	
	foreach($data as $k => $v) {
		$safeK = genSafeName($k);
		$out .= '<div class="builder--tree" id="tree__'.$safeK.'"><div class="builder--tree--name">'.$k.'</div>';
		
		$skills = array();
		
		foreach($v as $skillIdx => $skillData) {
			$tier = $skillData['tier'];
			$slot = $skillData['slot'];
			$row = $skillData['row'];
			$name = $skillData['name'];
			$img = isset($skillData['img']) ? $skillData['img'] : genImgSkill($name);
			if($img == "AUTO")
				$img = genImgSkill($name);
			if(!str_contains($img, "."))
				$img = genImgSkill($img);
			
			$costs = $skillData['costs'];
			$costSilver = isset($costs['silver']) ? $costs['silver'] : 0;
			$costMedal = isset($costs['medal']) ? $costs['medal'] : 0;
			$costHealth = isset($costs['health']) ? $costs['health'] : 0;
			$costMana = isset($costs['mana']) ? $costs['mana'] : 0;
			$costStamina = isset($costs['stamina']) ? $costs['stamina'] : 0;
			$costBreakthrough = isset($costs['breakthrough']) ? $costs['breakthrough'] : 0;
			$costOther = isset($costs['other']) ? $costs['other'] : '';
			$cooldown = $skillData['cooldown'];
			$description = $skillData['description'];

			if(!isset($skills[$tier])) $skills[$tier] = array();
			if(!isset($skills[$tier][$row])) $skills[$tier][$row] = array();
			if(!isset($skills[$tier][$row][$slot])) $skills[$tier][$row][$slot] = "";

			$skills[$tier][$row][$slot] .= '<div tabindex="0" class="builder--skill" id="'.genSafeName($name). '"
					data-full-name="'.$name.'"
					data-cost-silver="'.$costSilver.'"
					data-cost-mana="'.$costMana.'"
					data-cost-stamina="'.$costStamina.'"
					data-cost-breakthrough="'.$costBreakthrough.'"
					data-cost-other="'.$costOther.'"
					data-cooldown="'.$cooldown.'"
					data-exclusive="'.
						(isset($skillData['exclusive']) ? implode(", ", $skillData['exclusive']) : '').'"
					data-requires="'.
						(isset($skillData['requires']) ? implode(", ", $skillData['requires']) : ''). '"';
					if(isset($skillData['bonuses'])) {
						forEach($skillData['bonuses'] as $k => $v) {
							$skills[$tier][$row][$slot] .= ' data-bonuses-' . $k . '="'.$v.'"';
						}
					}
			$skills[$tier][$row][$slot] .= '
					>
				<div class="builder--skill--img"><img src="' . $img . '"></div>
				<div class="builder--skill--card">
					<div class="card--name">
						<svg><text x="0" y="75%" textLength="250" lengthAdjust="spacingAndGlyphs">'.$name.'</text></svg>
					</div>
					<div class="card--costs">';
					if($costBreakthrough > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__breakthrough">'.$costBreakthrough.'<img class="icon icon--breakthrough" src="./img/Breakthrough.webp" /></div>';
					}
					if($costSilver > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__silver">'.$costSilver.'<img class="icon icon--silver" src="./img/Silver.webp" /></div>';
					}
					if($costHealth > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__health">'.$costHealth.'<img class="icon icon--health" src="./img/Health.webp" /></div>';
					}
					if($costMana > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__mana">'.$costMana.'<img class="icon icon--mana" src="./img/Mana.webp" /></div>';
					}
					if($costMedal > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__medal">'.$costMedal.'<img class="icon icon--medal" src="./img/Medal.webp" /></div>';
					}
					if($costStamina > 0) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost_stamina">'.$costStamina.'<img class="icon icon--stamina" src="./img/Stamina.webp" /></div>';
					}
					if($costOther !== "") {
						$skills[$tier][$row][$slot] .= '<div class="card--cost_other">'.$costOther.'</div>';
					}
					if($cooldown == -1) {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__cooldown passive">Passive</div>';
					} else {
						$skills[$tier][$row][$slot] .= '<div class="card--cost__cooldown">'.$cooldown.'s<img class="icon icon--cooldown" src="./img/Cooldown.webp" /></div>';
					}
			$skills[$tier][$row][$slot] .= '
					</div>
					<div class="card--description">'.$description.'</div>';
					if(isset($skillData['requires'])) {
						$skills[$tier][$row][$slot] .= '<div class="card--requires">Requires: ';
						$skills[$tier][$row][$slot] .= implode(", ", $skillData['requires']);
						$skills[$tier][$row][$slot] .= '</div>';
					}
					if(isset($skillData['exclusive'])) {
						$skills[$tier][$row][$slot] .= '<div class="card--requires">Exclusive With: ';
						$skills[$tier][$row][$slot] .= implode(", ", $skillData['exclusive']);
						$skills[$tier][$row][$slot] .= '</div>';
					}
					if(isset($skillData['quest'])) {
						$skills[$tier][$row][$slot] .= '<div class="card--requires">Quest: '.$skillData['quest'].'</div>';
					}
				
			$skills[$tier][$row][$slot] .= '
				</div>
			</div>';
		}
		
		$skills = array_reverse($skills, true);
		foreach($skills as $rowIdx => $iRow) {
			$out .= '<div class="builder--row ' . $rowIdx . '">';
			
			// $iRow = array_reverse($iRow, true);
			foreach($iRow as $slotIdx => $iSlot) {
				foreach($iSlot as $slotSpot => $slotData) {
					$out .= $slotData;
				}
			}
			
			$out .= '</div>';
		}
		
		$out .= '</div>';
	}
	
	return $out;
}

function genImgSkillTree($name) {
	return "./img/skillTree/" . genSafeName($name) . ".webp";
}

function genImgSkill($name) {
	return "./img/skills/" . genSafeName($name, false) . ".webp";
}

function genSafeName($name, $lower = true) {
	if($lower) $name = strtolower($name);
	return str_replace(array(" "), "_", $name);
}

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
				<?PHP echo generateSkillTreeTabber(json_decode(file_get_contents($dataFiles['skillTrees']['file']), true)); ?>
			</div>
			<?PHP echo generateSkillTrees(json_decode(file_get_contents($dataFiles['skills']['file']), true)); ?>
		</div>
	</div>
	<div class="section totals">
		<div class="totals--header">Build Data</div>
		<div class="totals--skills">
			<div class="total-skills__total">Total: <span id="total-skills__total">0</span></div>
			<div class="total-skills__passive">Passive Skills: <span id="total-skills__passive">0</span></div>
			<div class="total-skills__active">Active Skills: <span id="total-skills__active">0</span></div>
		</div>
		<div class="totals--costs">
			<div class="costs__breakthrough">Breakthrough: <span id="costs__breakthrough">0</span><img class="icon icon--breakthrough" src="./img/Breakthrough.webp" /></div>
			<div class="costs__silver">Silver: <span id="costs__silver">0</span><img class="icon icon--silver" src="./img/Silver.webp" /></div>
			<div class="costs__medal">Medal: <span id="costs__medal">0</span><img class="icon icon--medal" src="./img/Medal.webp" /></div>
			<div class="costs__other">Other Costs: <span id="costs__other">None</span></div>
		</div>
		<div class="totals--skill_bonuses">
			<div class="totals--group--row">
				<div class="totals--row-header">
					<div class="row--header">Vitals</div>
				</div>
				<div class="totals--row-content">
					<div class="skill-bonuses skill-bonuses__health">Health: <span id="skill-bonuses__health">0</span><img class="icon icon--health" src="./img/Health.webp" /></div>
					<div class="skill-bonuses skill-bonuses__stamina">Stamina: <span id="skill-bonuses__stamina">0</span><img class="icon icon--stamina" src="./img/Stamina.webp" /></div>
					<div class="skill-bonuses skill-bonuses__mana">Mana: <span id="skill-bonuses__mana">0</span><img class="icon icon--mana" src="./img/Mana.webp" /></div>
					<div class="skill-bonuses skill-bonuses__mana_regen">Mana Regen: <span id="skill-bonuses__mana_regen">0</span><img class="icon icon--mana" src="./img/Mana.webp" /> / s</div>
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
	<div class="section tools">
		<div tabindex="0" role="button" class="tools-btn tools--clear" title="Resets current build to be empty.">Clear Build</div>
		<div tabindex="0" role="button" class="tools-btn tools--toggle-active" title="Toggles current tree to be all selected or unselected.">Toggle Active Tree</div>
		<div tabindex="0" role="button" class="tools-btn tools--toggle-zero-stats active" title="Toggles being able to see build stats that are 0.">Toggle Zero Visibility</div>
	</div>
	<div class="section footer"></div>
</body>
</html>