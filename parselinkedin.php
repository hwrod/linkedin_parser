<?	
	# Parse a bunch of files available in files/
	for ($i = 1; $i <= 1000; $i++) {
		$file = file_get_contents("files/file ($i).htm");
		if (!$file) continue;
		
		preg_match('@<span class="full-name">(.*?)\s*</h1>@ism', $file, $name);
		preg_match('@class="headline-title title"(.*?)\s*</p>@ism', $file, $job);
		preg_match('@<span class="org summary">(.*?)\s*</span>@ism', $file, $company);
		preg_match('@<a class="company-profile-public" href="(.*?)"@ism', $file, $companylink);
		preg_match('@<abbr class="dtstart"(.*?)</abbr>@ism', $file, $start);
		preg_match('@<abbr class="dtstamp"(.*?)</abbr>@ism', $file, $stop);
		preg_match('@<span class="duration">(.*?)(?:<span class="location">|</p>)@ism', $file, $duration);
		preg_match('@id="profile-experience"(.*?)(?:profile-skills|profile-education|profile-additional|profile-contact|view-full-profile)@ism', $file, $experience);
		preg_match_all('@(?:<div class="position\s*first experience vevent vcard summary-current"|<div class="position\s*experience vevent vcard summary-past")(.*?)</div>\s*</div>\s*<div>@ism', $experience[1], $xps);

		if (!$company[1]) continue;
		echo "<b>".strip($name[1])."</b><br>";

		foreach ($xps[1] as $xp) {
			preg_match('@<span class="org summary">(.*?)</span>@ism', $xp, $company);
			preg_match('@<a class="company-profile-public" href="(.*?)"@ism', $xp, $companylink);
			preg_match('@<span class="title">(.*?)</span>@ism', $xp, $job);
			preg_match('@<abbr class="dtstart"(.*?)</abbr>@ism', $xp, $start);
			preg_match('@<abbr (?:class="dtstamp"|class="dtend")(.*?)</abbr>@ism', $xp, $stop);
			preg_match('@<span class="duration">(.*?)(?:<span class="location">|</p>)@ism', $xp, $duration);

			echo "\tCompany\t".strip($company[1])."\n";
			echo "\tTitle\t".preg_replace('@ at .*@', '', strip($job[1]))."\n";
			echo "\tCompany ID\t".strip($companylink[1])."\n";
			echo "\tStart\t".strip($start[1])."\n";
			echo "\tStop\t".strip($stop[1])."\n";
			echo "\tDuration\t".preg_replace('@\)|\(@', '', strip($duration[1]))."\n\n";
		}
	}
	
	function strip($txt) { return trim(strip_tags(preg_replace('@(^.*?>)|/company/|\?trk=.*@', '', $txt))); }
?>
