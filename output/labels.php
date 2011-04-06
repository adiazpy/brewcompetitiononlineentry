<?php
require('output.bootstrap.php');
require(INCLUDES.'functions.inc.php');
require(INCLUDES.'url_variables.inc.php');
require(DB.'common.db.php');
include(DB.'admin_common.db.php');
require(INCLUDES.'version.inc.php');
require(INCLUDES.'scrubber.inc.php');
require(CLASSES.'fpdf/pdf_label.php');

if (($go == "entries") && ($action == "bottle")) {
	$query_log = "SELECT * FROM brewing WHERE brewPaid='Y' AND brewReceived='Y'";
	if ($filter != "default") $query_log .= sprintf(" AND brewCategorySort='%s'",$filter);
	$query_log .= " ORDER BY brewCategorySort,brewSubCategory,id ASC";
	$log = mysql_query($query_log, $brewing) or die(mysql_error());
	$row_log = mysql_fetch_assoc($log);
	$totalRows_log = mysql_num_rows($log);

	$filename = str_replace(" ","_",$row_contest_info['contestName'])."_Bottle_Labels";
	if ($filter != "default") $filename .= "_Category_".$filter;
	$filename .= ".pdf";
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();

	// Print labels
	do {
		
		if ($row_log['id'] < 10) $entry_no = "00".$row_log['id'];
		elseif (($row_log['id'] >= 10) && ($row_log['id'] < 100)) $entry_no = "0".$row_log['id'];
		else $entry_no = $row_log['id'];																						  
		
		$text = sprintf("\n%s (%s)   %s (%s)   %s (%s)\n\n\n%s (%s)   %s (%s)   %s (%s)",
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'], 
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory'],
		$entry_no, $row_log['brewCategory'].$row_log['brewSubCategory']
		);
		
		$pdf->Add_Label($text);
		
	} while ($row_log = mysql_fetch_assoc($log));

	$pdf->Output($filename,D);
}

if (($go == "participants") && ($action == "judging_labels")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	
	$query_brewer = "SELECT id,brewerFirstName,brewerLastName,brewerJudgeRank,brewerJudgeID,brewerEmail FROM brewer WHERE id = '$id'";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	$totalRows_brewer = mysql_num_rows($brewer);
	
	$filename .= $row_brewer['brewerFirstName']."_".$row_brewer['brewerLastName']."_Judging_Labels.pdf";
	
	$rank = bjcp_rank($row_brewer['brewerJudgeRank'],2);
	$j = preg_replace('/[a-zA-Z]/','',$row_brewer['brewerJudgeID']);
	//$j = ltrim($row_brewer['brewerJudgeID'],'/[a-z][A-Z]/');
	if ($j > 0) $judge_id = "- ".$row_brewer['brewerJudgeID'];
	else $judge_id = "";
	for($i=0; $i<30; $i++) {
		
		$text = sprintf("\n%s\n%s %s\n%s",
		$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'], 
		$rank,
		$judge_id,
		$row_brewer['brewerEmail']
		);
		
		$pdf->Add_Label($text);
	}
	
	$pdf->Output($filename,D);
}

if (($go == "participants") && ($action == "address_labels")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	
	$query_brewer = "SELECT * FROM brewer ORDER BY brewerLastName ASC";
	$brewer = mysql_query($query_brewer, $brewing) or die(mysql_error());
	$row_brewer = mysql_fetch_assoc($brewer);
	
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_Participant_Address_Labels.pdf";
	
	do {
		if (total_paid_received("default",$row_brewer['id']) > 0) {
			$text = sprintf("\n%s\n%s\n%s, %s %s",
			$row_brewer['brewerFirstName']." ".$row_brewer['brewerLastName'], 
			$row_brewer['brewerAddress'],
			$row_brewer['brewerCity'],
			$row_brewer['brewerState'],
			$row_brewer['brewerZip']
			);
		
			$pdf->Add_Label($text);
		}
	} while ($row_brewer = mysql_fetch_assoc($brewer));
	
	$pdf->Output($filename,D);
}

if (($go == "judging_scores") && ($action == "awards")) {
	$pdf = new PDF_Label('5160'); 
	$pdf->AddPage();
	
	$filename .= str_replace(" ","_",$row_contest_info['contestName'])."_Award_Labels.pdf";
	
	$query_tables = "SELECT * FROM judging_tables ORDER BY tableNumber";
	$tables = mysql_query($query_tables, $brewing) or die(mysql_error());
	$row_tables = mysql_fetch_assoc($tables);
	$totalRows_tables = mysql_num_rows($tables);
	
	$query_bos = "SELECT * FROM judging_scores_bos ORDER BY scoreType,scorePlace ASC";
	$bos = mysql_query($query_bos, $brewing) or die(mysql_error());
	$row_bos = mysql_fetch_assoc($bos);
	$totalRows_bos = mysql_num_rows($bos);
	
	do {
			
			$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM brewing WHERE id='%s'", $row_bos['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			if ($row_bos['scorePlace'] != "") { 
				$text = sprintf("\n%s\n%s\n%s\n%s",
				display_place($row_bos['scorePlace'])." - BEST IN SHOW",
				style_type($row_bos['scoreType'],"3","default"),
				$row_entries['brewBrewerFirstName']." ".$row_entries['brewBrewerLastName'], 
				strtr($row_entries['brewName'],$html_remove)." - ".$row_entries['brewStyle']
				);
				$pdf->Add_Label($text);
			}
			
		} while ($row_bos = mysql_fetch_assoc($bos));
	
	do {
	
	$query_scores = sprintf("SELECT * FROM %s WHERE scoreTable='%s'", "judging_scores", $row_tables['id']);
	$query_scores .= " AND (scorePlace='1' OR scorePlace='2' OR scorePlace='3' OR scorePlace='4' OR scorePlace='5') ORDER BY scorePlace ASC";
	$scores = mysql_query($query_scores, $brewing) or die(mysql_error());
	$row_scores = mysql_fetch_assoc($scores);
	$totalRows_scores = mysql_num_rows($scores);
	
		do {
			$query_entries = sprintf("SELECT id,brewBrewerFirstName,brewBrewerLastName,brewName,brewStyle,brewCategory,brewSubCategory FROM brewing WHERE id='%s'", $row_scores['eid']);
			$entries = mysql_query($query_entries, $brewing) or die(mysql_error());
			$row_entries = mysql_fetch_assoc($entries);
			
			$text = sprintf("\n%s%s\n%s\n%s",
			display_place($row_scores['scorePlace'])." - ",
			$row_tables['tableName'],
			$row_entries['brewBrewerFirstName']." ".$row_entries['brewBrewerLastName'], 
			strtr($row_entries['brewName'],$html_remove)
			);
			$pdf->Add_Label($text);
			
		} while ($row_scores = mysql_fetch_assoc($scores));
		
	} while ($row_tables = mysql_fetch_assoc($tables));
	
	$pdf->Output($filename,D);
}
?>