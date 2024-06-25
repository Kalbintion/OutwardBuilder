<?php
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
		$out .= '<div class="builder--tree" id="tree__'.$safeK.'"><div class="builder--tree--name col-all">'.$k.'</div>';
		
		$skills = array();
		
		foreach($v as $skillIdx => $skillData) {
			// $tier = $skillData['tier']; // ELIMINATED FROM DATA
			$slot = $skillData['slot'];
			$row = $skillData['row'];
			$name = $skillData['name'];
			$img = isset($skillData['img']) ? $skillData['img'] : genImgSkill($name);
			$imgIcon = isset($skillData['icon']) ? $skillData['icon'] : genImgSkillIcon($name);
			if($img == "AUTO")
				$img = genImgSkill($name);
			if($imgIcon == "AUTO")
				$imgIcon = genImgSkill($name);
			
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

			if(!isset($skills[$row])) $skills[$row] = array();
			if(!isset($skills[$row][$slot])) $skills[$row][$slot] = "";

			$skills[$row][$slot] .= '<div tabindex="0" class="builder--skill" id="'.genSafeName($name). '"
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
							$skills[$row][$slot] .= ' data-bonuses-' . $k . '="'.$v.'"';
						}
					}
			$skills[$row][$slot] .= '
					>
				<div class="builder--skill--img"><img src="' . $imgIcon . '"></div>
				<div class="builder--skill--card">
					<div class="card-img">
						<img src="'.$img.'">
					</div>
					<div class="card--name">
						<svg><text x="0" y="75%" textLength="250" lengthAdjust="spacingAndGlyphs">'.$name.'</text></svg>
					</div>
					<div class="card--costs">';
					if($costBreakthrough > 0) {
						$skills[$row][$slot] .= '<div class="card--cost__breakthrough">'.$costBreakthrough.'<img class="icon icon--breakthrough" src="./img/statIcons/Breakthrough.webp" /></div>';
					}
					if($costSilver > 0) {
						$skills[$row][$slot] .= '<div class="card--cost__silver">'.$costSilver.'<img class="icon icon--silver" src="./img/statIcons/Silver.webp" /></div>';
					}
					if($costHealth > 0) {
						$skills[$row][$slot] .= '<div class="card--cost__health">'.$costHealth.'<img class="icon icon--health" src="./img/statIcons/Health.webp" /></div>';
					}
					if($costMana > 0) {
						$skills[$row][$slot] .= '<div class="card--cost__mana">'.$costMana.'<img class="icon icon--mana" src="./img/statIcons/Mana.webp" /></div>';
					}
					if($costMedal > 0) {
						$skills[$row][$slot] .= '<div class="card--cost__medal">'.$costMedal.'<img class="icon icon--medal" src="./img/statIcons/Medal.webp" /></div>';
					}
					if($costStamina > 0) {
						$skills[$row][$slot] .= '<div class="card--cost_stamina">'.$costStamina.'<img class="icon icon--stamina" src="./img/statIcons/Stamina.webp" /></div>';
					}
					if($costOther !== "") {
						$skills[$row][$slot] .= '<div class="card--cost_other">'.$costOther.'</div>';
					}
					if($cooldown == -1) {
						$skills[$row][$slot] .= '<div class="card--cost__cooldown passive">Passive</div>';
					} else {
						$skills[$row][$slot] .= '<div class="card--cost__cooldown">'.$cooldown.'s<img class="icon icon--cooldown" src="./img/statIcons/Cooldown.webp" /></div>';
					}
			$skills[$row][$slot] .= '
					</div>
					<div class="card--description">'.$description.'</div>';
					if(isset($skillData['requires'])) {
						$skills[$row][$slot] .= '<div class="card--requires">Requires: ';
						$skills[$row][$slot] .= implode(", ", $skillData['requires']);
						$skills[$row][$slot] .= '</div>';
					}
					if(isset($skillData['exclusive'])) {
						$skills[$row][$slot] .= '<div class="card--requires">Exclusive With: ';
						$skills[$row][$slot] .= implode(", ", $skillData['exclusive']);
						$skills[$row][$slot] .= '</div>';
					}
					if(isset($skillData['quest'])) {
						$skills[$row][$slot] .= '<div class="card--requires">Quest: '.$skillData['quest'].'</div>';
					}
				
			$skills[$row][$slot] .= '
				</div>
			</div>';
		}
		// echo "<pre>".htmlspecialchars(print_r($skills, true))."</pre>";
		
		$skills = array_reverse($skills, true);
		foreach($skills as $rowIdx => $iRow) {
			$out .= '<div class="builder--row rail-'.(8 - ($rowIdx + 1) ).'">';
			
			// $iRow = array_reverse($iRow, true);
			foreach($iRow as $slotIdx => $iSlot) {
				$out .= "<div class=\"skill--group col-".$slotIdx."\" data-slotIDX='".$slotIdx."'>";
				$out .= $iSlot;
				$out .= "</div>";
			}
			
			$out .= '</div>';
		}
		
		$out .= '</div>';
	}
	
	
	return $out;
}

function generateGearListings($data) {
	$out = "";
	
	foreach($data as $slotName => $slotData) {
		$out .= '<div style="display: none;" class="gear--group" id="gear__'.genSafeName($slotName).'">';
		foreach($slotData as $idx => $itemInfo) {
			$out .= '<div class="gear--info" data-id="'.$itemInfo['id'].'" data-name="'.$itemInfo['name'].'">';
			
			if(!isset($itemInfo['img']) || $itemInfo['img'] == "AUTO")
				$itemInfo['img'] = genImgItem($itemInfo['id'], $itemInfo['name']);
			
			$out .= '<div class="gear--image"><img src="'.$itemInfo['img'].'"><div class="gear--name">'.$itemInfo['name'].'</div></div>';
			$out .= '';
			
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

function genImgSkillIcon($name) {
	return "./img/skillIcons/" . genSafeName($name, false) . ".png";
}

function genImgItem($id, $name) {
	$path = "./img/gear/".$id."_".str_replace(" ", "", $name)."_v_icn.png";
	if(!file_exists($path)) {
		$matches = glob("./img/gear/".$id."_*.png");
		if(count($matches) == 1)
			$path = $matches[0];
	}
	return $path;
}

function genSafeName($name, $lower = true) {
	if($lower) $name = strtolower($name);
	return str_replace(array(" ", ":"), array("_", ""), $name);
}
?>