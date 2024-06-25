window.appVersion = "v2";
window.buildUpdaters = {1: updateBuildURLV1, 2: updateBuildURLV2};
window.buildLoaders = {1: loadBuildV1, 2: loadBuildV2};
window.hideZeroStats = true;

function switchActiveSkillTree(name) {
	$(".section.builder > div.active").removeClass("active");
	
	let treeName = $(name).data("target");
	$("div#tree__" + treeName).addClass("active");
}

function switchActiveTabber(newTabber) {
	$(".skill_trees--opt.active").removeClass("active");
	$(newTabber).addClass("active");
}

function switchActiveGear(newTabber) {
	$(".gear--group").hide();
	let tar = $(newTabber).data("target");
	let tarEle = $("#gear__"+tar);
	let isAlreadyShown = (tarEle.css('display') !== "none");
	console.log(isAlreadyShown);
	if(isAlreadyShown)
		tarEle.hide();
	else
		tarEle.show();
}

function updateSkillStats() {
	var selectedSkills = $("div.builder--skill.selected");
	
	var totalPassives = 0;
	var totalActives = 0;
	var totalSilver = 0;
	var totalBreakthrough = 0;
	var totalOther = [];
	
	var bonusNames = ["hot", "cold", "health", "stamina", "mana", "speed", "stam_cost", "mana_cost", "mana_regen", "pouch", "stealth", "cdr", "atk_spd", "physical_dmg", "ethereal_dmg", "decay_dmg", "lightning_dmg", "frost_dmg", "fire_dmg", "impact", "physical", "ethereal", "decay", "lightning", "frost", "fire", "corrupt", "status_resist", "protection", "barrier"];
	var bonusValues = {};
	
	bonusNames.forEach((v,k) => {
		// Initialize bonus values
		bonusValues[v] = 0;
	});
	
	var bonusHealth = 0;
	var bonusStamina = 0;
	var bonusMana = 0;
	
	var bonusHot = 0;
	var bonusCold = 0;
	
	
	selectedSkills.each((k, v) => {
		let s = $(v);
		if(s.data('cooldown') == -1) {
			totalPassives++;
		} else {
			totalActives++;
		}
		
		if(s.data('cost-silver')) totalSilver += s.data('cost-silver');
		if(s.data('cost-breakthrough')) totalBreakthrough += s.data('cost-breakthrough');
		if(s.data('cost-other')) {
			let others = s.data('cost-other').split(", ");
			others.forEach((req,key) => {
				totalOther.push(req);
			});
		}
		
		bonusNames.forEach((v,k) => {
			if(s.data('bonuses-' + v)) bonusValues[v] += s.data('bonuses-' + v);
		});
	});
	
	if(totalOther.length == 0) totalOther.push("None");
	
	$("#total-skills__total").text(selectedSkills.length);
	$("#total-skills__active").text(totalActives);
	$("#total-skills__passive").text(totalPassives);
	$("#costs__breakthrough").text(totalBreakthrough);
	$("#costs__silver").text(totalSilver);
	$("#costs__other").text(totalOther.join(", "));
	
	// Setup text values for bonuses
	bonusNames.forEach((v,k) => {
		$("#skill-bonuses__" + v).text(bonusValues[v]);
		$("#skill-bonuses__" + v).parent().removeClass("hidden");
		
		if(bonusValues[v] === 0 && window.hideZeroStats)
			$("#skill-bonuses__" + v).parent().addClass("hidden");
	});
}

function updateBuildURL() {
	let buildVersionNumber = window.appVersion.replaceAll("v", "");
	window.buildUpdaters[buildVersionNumber]();
}

function updateBuildURLV1() {
	var out = "";
	$("div.builder--skill").each((k, v) => {
		var s = $(v);
		if(s.hasClass("selected")) out += '1'
		else out += '0';
	});
	
	document.location.hash = "v1_" + out;
}

function updateBuildURLV2() {
	var buildData = "";
	$("div.builder--skill").each((k, v) => {
		var s = $(v);
		if(s.hasClass("selected")) buildData += '1'
		else buildData += '0';
	});
	
	buildData = buildData + "0".repeat(buildData.length % 32);
	
	let buildOut = "";
	let buildLength = buildData.length;
	for(var i = 0; i < buildLength; i+=32) {
		let buildPiece = buildData.substring(i,i+32);
		buildOut += parseInt(buildPiece, 2) + "-";
	}
	
	buildOut = buildOut.substring(0, buildOut.length - 1);
	
	document.location.hash = "v2_" + buildOut;
}

function loadBuildURL() {
	let skillHash = location.hash.replaceAll("#", "");
	if(skillHash == "")
		return;
	
	let buildVersion = skillHash.split("_")[0];
	let buildVersionNumber = buildVersion.replaceAll("v", "");
	let skillData = skillHash.split("_")[1];
	console.log("Loading", skillHash, skillHash.length);
	
	window.buildLoaders[buildVersionNumber](skillHash, skillData);
}

function loadBuildV2(skillHash, skillData) {
	let buildVersion = skillHash.split("_")[0];
	
	if(buildVersion !== "v2") {
		console.error("Could not load build! Build version not supported.");
		return;
	}

	// Unpack Ints
	let buildPieces = skillData.split("-");
	let buildData = "";
	skillData.split("-").forEach((v, k) => {
		let int2bin = (v >> 0).toString(2);
		buildData += int2bin.padStart(32, "0");
	});
	
	if(buildData.length % 32 !== 0) {
		console.error("Could not load build! Data length not expected, got " + buildData.length);
		return;
	}
	
	if(buildData.length < 144) {
		console.error("Could not load build! Data length not exxpected, got " + buildData.length);
		return;
	}
	
	// Trim excess data padding
	buildData = buildData.substring(0, 144);
	
	console.log("v1 Build Data: " + buildData);

	
	// We're good to load
	$(".builder--skill").each((k, v) => {
		if(buildData.charAt(k) == "1")
			$(v).trigger("click");
	});
}

function loadBuildV1(skillHash, skillData) {
	let skillCount = $(".builder--skill").length;
	let buildVersion = skillHash.split("_")[0];
	
	if(buildVersion !== "v1") {
		console.error("Could not load build! Build version not supported.");
		return;
	}
	
	if(skillData.length > skillCount) {
		console.error("Could not load build! Data mismatch.");
		return;
	}
	
	if(skillData.match(/^[0-1]+$/) == null) {
		console.error("Could not load build! Bad data.");
		return;
	}
	
	// We're good to load
	$(".builder--skill").each((k, v) => {
		if(skillData.charAt(k) == "1")
			$(v).trigger("click");
	});
}

function genSafeName(name, lower = true) {
	if(lower) name = name.toLowerCase();
	name = name.str_replace([" ", "'"], ["_", "\\'"]);
	
	return name;
}

function initSkillRequired() {
	var skillRequires = $("div.builder--skill[data-requires!='']");
	skillRequires.each((k, v) => {
		let skillNames = $(v).data('requires').split(', ');
		skillNames.forEach((v2, k2) => {
			v2 = genSafeName(v2);
			let skillReqed = $("#" + v2);
			if(!$(skillReqed).hasClass("selected")) {
				$(v).addClass("requires");
			} else {
				$(v).removeClass("requires");
			}
		});
	});
}

function cascadeRequireCheck(from, prevSelected) {
	let requires = $("div.builder--skill[data-requires*=\"" + from + "\"]");
	requires.each((k,v) => {
		// Remove selection/require tags
		let curSelected = $(v).hasClass("selected");
		if($(v).hasClass("requires") && prevSelected) {
			$(v).removeClass("requires");
		} else {
			$(v).addClass("requires");
			$(v).removeClass("selected");
		}
		
		let itemFullName = $(v).data("full-name");
		cascadeRequireCheck(itemFullName, curSelected);
	});
}

function cascadeExclusivesCheck(from, prevSelected) {
	if(from !== "") {
		let exclusives = from.split(", ");
		exclusives.forEach((v,i) => {
			if(prevSelected) {
				$("#"+genSafeName(v)).addClass("locked");
				$("#"+genSafeName(v)).removeClass("requires");
			} else {
				$("#"+genSafeName(v)).removeClass("locked");
				$("#"+genSafeName(v)).removeClass("requires");
			}
		});
	}
}

function mouseX(evt) {
	if(evt.pageX) {
		return evt.pageX;
	} else if(evt.clientX) {
		return evt.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
	} else {
		return null;
	}
}

function mouseY(evt) {
	if(evt.pageY) {
		return evt.pageY;
	} else if(evt.clientY) {
		return evt.clientY + (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
	} else {
		return null;
	}
}

$(function() {
	// Handlers
	$(".tools--clear").click(function() {
		$(".builder--skill.selected").each((k, v) => {
			$(v).click();
		});
	});
	$(".tools--toggle-active").click(function() {
		let nOn = $("div.builder--tree.active div.builder--skill.selected").length;
		let nOff = $("div.builder--tree.active div.builder--skill").length - nOn;

		$("div.builder--tree.active div.builder--skill").each((k, v) => {
			if(nOn > nOff && $(v).hasClass("selected"))
				$(v).click();
			else if(nOn < nOff && !$(v).hasClass("selected"))
				$(v).click();
		});
	});
	$(".tools--toggle-zero-stats").click(function() {
		window.hideZeroStats = $(this).toggleClass("active").hasClass("active");
		updateSkillStats();
	});
	$(".skill_trees--opt").click(function() { switchActiveTabber(this); switchActiveSkillTree(this); })
	$(".builder--skill").click(function() {
		if($(this).hasClass("locked") || $(this).hasClass("requires"))
			return;
		
		$(this).toggleClass("selected");
		
		let fullName = $(this).data("full-name");
		let isSelected = $(this).hasClass("selected");
		cascadeRequireCheck(fullName, isSelected);
		cascadeExclusivesCheck($(this).data("exclusive"), isSelected);
		
		updateSkillStats();
		updateBuildURL();
	});
	$(".gear--slot").click(function() { switchActiveGear(this); });
	$(".builder--skill:not([data-cooldown='-1']").on("contextmenu", function() {
		let rMenu = $(".section.context.skill");
		rMenu.css("top", mouseY(event) + "px");
		rMenu.css("left",mouseX(event) + "px");
		rMenu.data("name", $(this).data("name"));
		$(".section.context.skill").show();
		window.event.returnValue = false;
	});
	$(".gear--info").on("contextmenu", function() {
		let rMenu = $(".section.context.item");
		rMenu.css("top", mouseY(event) + "px");
		rMenu.css("left", mouseX(event) + "px");
		rMenu.data("id", $(this).data("id"));
		$(".section.context.item").show();
		window.event.returnValue = false;
	});
	$(".context--target").click(function() {
		
	});
	
	// Context menu hiding
	$(document).bind("click", function() {
		$(".section.context").hide();
	});
	
	
	// Initialize Default Page Setup
	let firstTree = $(".skill_trees--opt").eq(0);
	
	switchActiveTabber(firstTree);
	switchActiveSkillTree(firstTree);
	
	initSkillRequired();
	updateSkillStats();
	
	// Build load handler
	window.onhashchange = loadBuildURL();
});