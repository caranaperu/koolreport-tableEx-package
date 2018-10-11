<?php
/**
 * This file is the view of table widget
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

use \koolreport\core\Utility;

$tableCss = Utility::get($this->cssClass, "table");
$trClass = Utility::get($this->cssClass, "tr");
$tdClass = Utility::get($this->cssClass, "td");
$thClass = Utility::get($this->cssClass, "th");
$tfClass = Utility::get($this->cssClass, "tf");


?>
<div class="koolphp-table <?php echo $this->responsive ? "table-responsive" : ""; ?>" id="<?php echo $this->name; ?>">
    <table<?php echo ($tableCss) ? " class='table $tableCss'" : " class='table' border='1'"; ?>>
        <?php
        if ($this->showHeader) {
            ?>
            <thead>
            <?php
            foreach ($this->headers as $header) {
                ?>
                <tr>
                    <?php
                    foreach ($header as $hName => $hValue) {
                        ?>
                        <th<?php
                        foreach ($hValue as $k => $v) {
                            if ($k != "label") {
                                echo " $k='$v'";
                            }
                        }
                        ?>><?php echo Utility::get($hValue, "label", $hName); ?></th>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            <tr>
                <?php
                foreach ($showColumnKeys as $cKey) {
                    $label = Utility::get($meta["columns"][$cKey], "label", $cKey);
                    $cssStyle = Utility::get($meta["columns"][$cKey], "cssStyle", null);
                    $thStyle = is_string($cssStyle) ? $cssStyle : Utility::get($cssStyle, "th");
                    $class = "";
                    if ($thClass) {
                        $class = (gettype($thClass) == "string") ? $thClass : $thClass($cKey);
                    }
                    echo "<th".(($thStyle) ? " style='$thStyle'" : "").(($class != "") ? " class='$class'" : "").">$label</th>";
                }
                ?>
            </tr>
            </thead>
            <?php
        }
        ?>
        <?php
        if ($this->showFooter !== null) {
            ?>
            <tfoot <?php echo ($this->showFooter === "top") ? "style='display:table-row-group'" : ""; ?>>
            <tr>
                <?php
                foreach ($showColumnKeys as $cKey) {
                    $cssStyle = Utility::get($meta["columns"][$cKey], "cssStyle", null);
                    $tfStyle = is_string($cssStyle) ? $cssStyle : Utility::get($cssStyle, "tf");
                    ?>
                    <td <?php if ($tfClass) {
                        echo " class='".((gettype($tfClass) == "string") ? $tfClass : $tfClass($cKey))."'";
                    } ?> <?php echo ($tfStyle) ? "style='$tfStyle'" : ""; ?> >
                        <?php
                        $footerValue = isset($this->footer[$cKey]) ? $this->formatValue($this->footer[$cKey], $meta["columns"][$cKey]) : "";
                        $footerText = Utility::get($meta["columns"][$cKey], "footerText");
                        if ($footerText !== null) {
                            echo str_replace("@value", $footerValue, $footerText);
                        } else {
                            echo $footerValue;
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            </tfoot>
            <?php
        }
        ?>
        <tbody>
        <?php
        unset($cssStyle);
        foreach ($showColumnKeys as $cKey) {
            // PATCH : $cssStyle unn ecessary used as an array in original code..
            $cssStyle = Utility::get($meta["columns"][$cKey], "cssStyle", null);
            $tdStyle[$cKey] = is_string($cssStyle) ? $cssStyle : Utility::get($cssStyle, "td");
        }
        unset($cssStyle);

        $numFields = count($showColumnKeys);
        $numGroupColumns = count($groupColumns);

        $i = 0;
        $cnt=0;
        $this->dataStore->popStart();
        while ($row = $this->dataStore->pop()) {
            // Reinitialize vars
            $currLevel = 0;
            $lastValue = "";
            $rowout = "";
            $ptotal = "";
            $rowStyle = "";

            $trClassHtml = "";
            if ($trClass) {
                $trClassHtml = "class='".((gettype($trClass) == "string") ? $trClass : $trClass($row))."'";
            };

            for ($k = 0; $k < $numFields; $k++) {

                $cKey = $showColumnKeys[$k];

                $tdHtml="<td ";
                if ($tdClass) {
                    $tdHtml .= "class='".((gettype($tdClass) == "string") ? $tdClass : $trClass($row,$cKey))."'";
                }

                $tdTotalHtml = $tdHtml;
                if (isset($groupColumnsCss[$cKey])) {
                    $tdTotalHtml .= "style='$groupColumnsCss[$cKey]'";
                }

                $tdTotalTextHtml = $tdHtml;
                if (isset($groupTotalCss[$cKey])) {
                    $tdTotalTextHtml .= " style='$groupTotalCss[$cKey]'";
                }


                if ($tdStyle[$cKey]) {
                    $tdHtml .= " style='$tdStyle[$cKey]'";
                }
                $tdHtml .= " row-value='".(($cKey!=="#")?$row[$cKey]:($cnt+$meta["columns"][$cKey]["start"]))."'";


                // Formated field value
                $ffField = $this->formatValue(($cKey!=="#")?$row[$cKey]:(($cnt++)+$meta["columns"][$cKey]["start"]),$meta["columns"][$cKey],$row);


                // El nombre del campo esta en los grupos?
                if (isset($groupColumns) && in_array($cKey, $groupColumns)) {

                    if (strlen($lastValue) == 0) {
                        $fieldTest = $row[$cKey];
                    } else {
                        $fieldTest = $lastValue.'_'.$row[$cKey];
                    }

                    if (strlen($lastValue) < strlen($fieldTest)) {
                        $currLevel++;
                    } else {
                        $currLevel--;

                    }

                    // Cambio De Greupo
                    $lastValue = $fieldTest;
                    if (!isset($lastLevels[$currLevel]) || $lastLevels[$currLevel] != $lastValue) {

                        // Print the normal row if footer is at bottom
                        if ($footerStyle == "bottom") {
                            if ($groupStyle != "one_line" && $currLevel < $numGroupColumns) {
                                $rowout .= $tdHtml.">".$ffField."</td></tr>";
                                $rowout .= "<tr $trClassHtml lvl='$currLevel'>"; // New row

                                for ($j = 0; $j <= $k; $j++) {
                                    $rowout .= $tdHtml."></td>";
                                }

                            } else {
                                $rowout .= $tdHtml.">$ffField</td>";
                            }

                            // Prepare the total string to be printed
                            if (isset($levelsAgg) && count($levelsAgg) > 0) {
                                if (isset($lastLevels[$currLevel]) && isset($totals[$lastLevels[$currLevel]])) {
                                    $ptotal = $totals[$lastLevels[$currLevel]].$ptotal;
                                   unset($totals[$lastLevels[$currLevel]]);
                                }

                                if (isset($orderedAggFields[$cKey])) {

                                    // assembly the total row
                                    $totals[$lastValue] = "<tr $trClassHtml lvl='$currLevel'>"; // new row

                                    if ($k > 0) {
                                        $totals[$lastValue] .= $tdHtml." colspan='$k.'></td>";
                                    }

                                    if (isset($groupTotalText[$cKey])) {
                                        $totals[$lastValue] .= $tdTotalTextHtml.">".str_replace("@fname",$row[$cKey],$groupTotalText[$cKey])."</td>";
                                    } else {
                                        $totals[$lastValue] .= $tdTotalTextHtml.">Total $row[$cKey] =></td>";
                                    }

                                    // The totals value
                                    for ($j = $k + 1; $j < $numFields; $j++) {
                                        $tdAggTotalHtml = $tdTotalHtml;

                                        if (!isset($orderedAggFields[$cKey][$j])) {
                                            $totals[$lastValue] .= $tdHtml."></td>";
                                        } else {
                                            $totalField = $levelsAgg[$lastValue][$orderedAggFields[$cKey][$j]];
                                            //  Total cell has is own style? change it.
                                            // Had his onw format , change it
                                            if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]])) {
                                                if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["cssStyle"])) {
                                                    $tdAggTotalHtml = $tdHtml." style='".$aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["cssStyle"]."'";
                                                }

                                                if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["formatValue"])) {
                                                    // The formatValue function extract the formatValue key from the field options definition .
                                                    $totalField = $this->formatValue($levelsAgg[$lastValue][$orderedAggFields[$cKey][$j]],$aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]);
                                                }
                                            }
                                            $totals[$lastValue] .= $tdAggTotalHtml.">".$totalField."</td>";
                                        }
                                    }
                                    $totals[$lastValue] .= "</tr>";
                                }
                            }
                        } else {
                            //*********************************************
                            // Print the normal row if footer is at top
                            //*********************************************

                            // If is a group field and has a total , we process as normal group , otherwise
                            // as a standard field.
                            if (isset($levelsAgg[$lastValue])) {
                                if (isset($levelsAgg) && count($levelsAgg) > 0) {
                                    if ($k > 0) {
                                        // Se coloca el nivel para caso de expansion de datos por nivel
                                        $ptotal .= "<tr $trClassHtml lvl='$currLevel'>"; // new row
                                        //$ptotal .= "<tr $trClassHtml >"; // new row
                                        $ptotal .= $tdHtml." colspan='".($k)."'></td>";
                                    }
                                    $ptotal .= $tdTotalTextHtml.">$ffField</td>";

                                    // The totals value
                                    for ($j = $k + 1; $j < $numFields; $j++) {
                                        $tdAggTotalHtml = $tdTotalHtml;

                                        if (!isset($orderedAggFields[$cKey][$j])) {
                                            $ptotal .= $tdHtml."></td>";
                                        } else {
                                            $totalField = $levelsAgg[$lastValue][$orderedAggFields[$cKey][$j]];
                                            // is a total , need an specific style if  assigned
                                            //  Total cell has is own style? change it.
                                            // Had his own format , change it
                                            if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]])) {
                                                if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["cssStyle"])) {
                                                    $tdAggTotalHtml = $tdHtml." style='".$aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["cssStyle"]."'";
                                                }

                                                if (isset($aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]["formatValue"])) {
                                                    // The formatValue function extract the formatValue key from the field options definition .
                                                    $totalField = $this->formatValue($levelsAgg[$lastValue][$orderedAggFields[$cKey][$j]],$aggFieldOptions[$cKey][$orderedAggFields[$cKey][$j]]);
                                                }
                                            }
                                            $ptotal .= $tdAggTotalHtml.">".$totalField."</td>";
                                        }
                                    }

                                    $ptotal .= "</tr>";
                                    // Se coloca el nivel para caso de expansion de datos por nivel
                                    if (substr($ptotal, 0, 3) !== "<tr") {
                                        $ptotal = "<tr $trClassHtml lvl='$currLevel'>".$ptotal;
                                    }
                                    $rowout .= $tdHtml."></td>";
                                }
                            } else {
                                $rowout .= $tdHtml.">$ffField</td>";
                            }
                        }
                    } else {
                        $rowout .= $tdHtml."></td>";
                    }

                    $lastLevels[$currLevel] = $lastValue;

                } else {
                    $rowout .= $tdHtml.">$ffField</td>";
                }
            }

            if (strlen($ptotal)) {
                $ptotals = explode("<tr",$ptotal);
                foreach ($ptotals as $strtotal) {
                    if (strlen($strtotal) > 0) {
                        if ($this->paging) {
                            if ($i < $this->paging["pageIndex"] * $this->paging["pageSize"] || $i >= ($this->paging["pageIndex"] + 1) * $this->paging["pageSize"]) {
                                $rowStyle = "style='display:none;'";
                            }
                        }

                        if (strpos($strtotal, '<td') === 0) {
                            echo "<tr row-index='".($i++)."' $rowStyle $trClassHtml >" .$strtotal;
                        } else {
                            echo "<tr row-index='".($i++)."' $rowStyle " .$strtotal;
                        }
                    }
                }
                unset($ptotals);
            }

            if (strlen($rowout)) {
                $plines = explode("<tr",$rowout);
                foreach ($plines as $strline) {
                    if (strlen($strline) > 0) {
                        if ($this->paging) {
                            if ($i < $this->paging["pageIndex"] * $this->paging["pageSize"] || $i >= ($this->paging["pageIndex"] + 1) * $this->paging["pageSize"]) {
                                $rowStyle = "style='display:none;'";
                            }
                        }

                        if (strpos($strline, '<td') === 0) {
                            echo "<tr row-index='".($i++)."' $rowStyle $trClassHtml>" .$strline;
                        } else {
                            echo "<tr row-index='".($i++)."' $rowStyle " .$strline;
                        }
                    }
                }
                unset($plines);
                echo "</tr>";
            }

            ?>

            <?php
        }

        // Elk ultimo total aun no esta pintado y creeado , solo el ultimo
        // subtotal , por ende hay que completrarlo.
        // lastvalue contiene el ultimo grupo de proceso aun no pintado , por ende
        // a partir de alli generamos el total faltante.
        if ($footerStyle == "bottom") {
            if (count($totals) > 0) {
                $row = "";
                foreach ($totals as $strtotal) {
                    if (strlen($strtotal) > 0) {
                        if ($this->paging) {
                            if ($i < $this->paging["pageIndex"] * $this->paging["pageSize"] || $i >= ($this->paging["pageIndex"] + 1) * $this->paging["pageSize"]) {
                                $rowStyle = "style='display:none;'";
                            }
                        }

                        $row = str_replace("<tr","<tr row-index='".($i++)."' $rowStyle " ,$strtotal).$row;
                    }
                }
                if (strlen($row) > 0) {
                    echo $row;
                }
                unset($totals);
            }
        }

        if ($this->paging) {
            $this->paging["itemCount"]=$i;
            $this->paging["pageCount"]=ceil($this->paging["itemCount"]/$this->paging["pageSize"]);
        }

        ?>
        <?php
        if ($this->dataStore->countData() <= 0) {
            ?>
            <tr>
                <td colspan="<?php echo count($showColumnKeys); ?>"
                    align="center"><?php echo $this->translate("No data available in table"); ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php
    if ($this->paging) {
        ?>
        <div style='text-align:<?php echo $this->paging["align"]; ?>'>
            <nav></nav>
        </div>
        <?php
    }
    ?>
</div>
<script type="text/javascript">
    var <?php echo $this->name; ?> =
    new KoolPHPTable('<?php echo $this->name; ?>',<?php echo json_encode([
        "cKeys" => $showColumnKeys,
        "paging" => $this->paging
    ]); ?>);
    <?php
    if($this->clientEvents)
    {
    foreach($this->clientEvents as $eventName=>$function)
    {
    ?>
    <?php echo $this->name; ?>.on("<?php echo $eventName; ?>",<?php echo $function; ?>);
    <?php
    }
    }
    ?>
</script>