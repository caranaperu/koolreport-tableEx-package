# Introduction

`Tablex` is an enhanced widget based on the base Table widget, allowing total groups and differente formats.

# Installation

1. Unzip `futurelabs.zip`
2. Copy `futurelabs` folder into `koolreport\packages`
3. All done! Now you can use the package in your report.

# TableEx widget

`TableEx` is a widget that allows you to create standard table with group aggregates.
All you need to do are:

1. Setup same as standard Table.
2. Add groups if required. (See [example](#exampleid))
3. Run.

### Usage

```
  <?php
    TableEx::create([
        "dataStore" => $this->dataStore('Veritrade'),
        "showFooter" => "bottom",
        "columns" => [
            "MARCA",
            "MODELO" => [
                "footer" => "count",
                "footerText" => "Total: @value"
            ],
            "ANO" => ["cssStyle" => "text-align:right;color:blue;"],
            "CONTADOR" => [
                "footer" => "sum",
                "footerText" => "Total: @value",
                "cssStyle" => "text-align:right;color:blue;"
            ],
            "CC" => "#"
        ],
        "paging" => [
            "pageSize" => 40,
            "align" => "center",
            "pageIndex" => 0,
        ],
        "removeDuplicate" => [
            "fields" => [
                "MARCA" => [
                    "agg" => [
                        "sum" => [
                            "CONTADOR" => [
                                "formatValue" => "$ @value",
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ],
                            "SUMADOR"
                        ],
                        "max" => [
                            "ANO" => [
                                "formatValue" => function ($value) {
                                    return "[".$value."]";
                                },
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ]
                        ],
                        "dcount" => ["MODELO" => ["cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"]]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"
                ],,
                "MODELO" => [
                    "agg" => [
                        "avg" => ["CONTADOR"],
                        "count" => [
                            "ANO" => [
                                "formatValue" => function ($value) {
                                    return "<".$value.">";
                                },
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:yellow;"
                            ]
                        ]
                    ],
                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:papayawhip;",
                    "totalText" => "Total @fname :",
                    "totalCss" => "text-align:left;font-weight: bold;background-color:papayawhip;"
                ]
            ],
            "options" => [
                "showfooter" => "bottom",
                "style" => "one_line"
            ]
        ]
    ]);
    ?>
```

The only difference with the standard table is the removeDuplicate attribute , in the original Table widget definition is only allowed to define as:
```
"removeDuplicate" => ["MARCA","MODELO"]
```
The new widget allow group aggregates on columns.
All the other properties can be seen on the original Table Widget , the new ones are only applied to remodeDuplicates, here is the list:
### Properties

|name|type|default|description|
|---|---|---|---|
|`fields`|array|`*Required`| List of all fields that need and aggregate or can be grouped, each one need to be defined on the columns attribute of the Table. See the __fields_properties__ for more detail of settings on each one.|
|`options`|array|`*Optional`, footerStyle='bottom' and style not defined (multiple lines) , but if not aggregates defined on a field style='one_line'|See __option_properties__ for more details.|

### Options Properties

|name|type|default|description|
|---|---|---|---|
|`showfooter`|string|bottom|Define position of totals rows, 'top' or 'bottom' of group|
|`style`|string|if not aggregates on the fields default to one_line otherwise undefined|If showFooter is bottom defines is the group rows are on one line or multiple lines|

__Example__

```
<?php
TableEx::create(
    "removeDuplicate"=>[
        "fields=>[..],
        "options"=>[
            "showfooter"=>"top",
            "style"=>"one_line"
        ]
    ]
));
?>
```

### Fields Properties

|name|type|default|description|
|---|---|---|---|
|`agg`|array|`*Optional`|Define the operation needed sum,avg,count,dcount,max,min, see __agg_properties__|
|`cssStyle`|string|`*Optional`|CSS style for all elements for the total row , used for each aggregated column if not defined another specific for the colum|
|`totalText`|string|`*Optional`, "Total  :" by default|The text to put as total for the group field|
|`totalCss`|string|`*Optional`, by default take cssStyle if not defined|CSS style specific for the total colum of the field|

__Example__

```
<?php
TableEx::create(
    "removeDuplicate"=>[
        "fields=>[
            "BRAND"=>[
                "sum"=>[..],
                "avg"=>[..],
                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                "totalText" => "Total @fname :",
                "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"         
            ],
            "MODEL"
        ],
        "options"=>[
            "showfooter"=>"top",
            "style"=>"one_line"
        ]
    ]
));
?>
```
Here the field BRAND have specific definitions for css and total text.

### Agg Properties

for easy explanation , an example.

```
"removeDuplicate" => [
            "fields" => [
                "BRAND" => [
                    "agg" => [
                        "sum" => [
                            "COUNTER" => [
                                "formatValue" => "$ @value",
                                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ],
                            "ACCUMULATE"
                        ],
                        "max" => ["YEAR"],
                        "dcount" => ["MODEL"]
                    ]
                ],
                "MODEL" => [
                    "agg" => [
                        "avg" => ["COUNTER"],
                        "count" => ["YEAR"]
                        ]
                    ]
                ]
            ]
```

Here BRAND,MODEL,YEAR,COUNTER and ACCUMULATE all are fields of the Datastore and columns of the report.<br/>
For the field "BRAND" exists 3 aggregates definitions sum,max and dcount (distinct count), sum is applied to the fields COUNTER and ACCUMULATE<br/>
max is applied to the field YEAR and dcount to MODEL.<br/>
Also you can specify the formatValue (same as ther original Table Widget) and a css style for that aggregate cell.


### Full example: {#exampleid}
Veritrade is a company that provides information about the imports to a country for every product type , here i will use as example on CARS.<br/>

index.php

```
<?php
require_once "VeritradeAutos.php";

$veritradeAutos = new VeritradeAutos($params);
$veritradeAutos->run()->render();
```

VeritradeAutos.php

```
<?php
require_once "../../../../koolreport/autoload.php";

class VeritradeAutos extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "sqlserver"=>array(
                    'host' => '192.168.0.5',
                    'username' => 'auser',
                    'password' => 'apassword',
                    'dbname' => 'veritrade',
                    'class' => "\koolreport\datasources\SQLSRVDataSource"
                )
            )
        );
    }
    public function setup()
    {
        $this->src('sqlserver')
            ->query("SELECT BRAND,MODEL,YEAR,count(*) as COUNTER FROM veritrade where MARCA IS NOT NULL and MODELO IS NOT NULL group by BRAND,MODEL,YEAR order by BRAND,MODEL,YEAR")
            ->pipe($this->dataStore('Veritrade'));
    }
}
?>
```

Of course for work with groups , this can be done at datastore stage the table widgets are only for presentation.<br/>

VeritradeAutos.view.php (case 1)

```
<?php
use \koolreport\futurelabs\TableEx;

TableEx::create([
    "dataStore" => $this->dataStore('Veritrade'),
    "columns" => [
        "BRAND","MODEL","YEAR","COUNTER","CC" => "#"
    ],
    "removeDuplicate" => [
        "fields" => [
            "BRAND",
            "MODEL"
        ]
    ]
]);
?>
```


Here is the simple example , only do grouping no aggregates, also the options showfooter and style are useless.<br/>
The output:

<html>
<style>
.table {
    font-size: 11px;
}
.table > tbody > tr > td {
    line-height: normal;
    border-top: 0px;
    padding: 2px;
}
</style>
<body>
<script type='text/javascript' src='/koolreport/clients/jquery/jquery.min.js'></script>
<link type='text/css' rel='stylesheet' href='/koolreport/packages/futurelabs/table/table.css'></link>
<script type='text/javascript' src='/koolreport/packages/futurelabs/table/table.js'></script>
<div class="reppage-container">
<table class='table' border='1'>
                    <thead>
                        <tr>
                <th>BRAND</th><th>MODEL</th><th>YEAR</th><th>COUNTER</th><th>#</th>            </tr>
            </thead>
                            <tbody>
        <tr row-index='0'  ><td  row-value='AB MOTORS'>AB MOTORS</td><td  row-value='AB 150-HR'>AB 150-HR</td><td  row-value='2017'>2,017</td><td  row-value='8'>8</td><td  row-value='1'>1</td></tr>
            <tr row-index='1'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'></td><td  row-value='2018'>2,018</td><td  row-value='10'>10</td><td  row-value='2'>2</td></tr>
            <tr row-index='2'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-TR'>AB 150-TR</td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='3'>3</td></tr>
            <tr row-index='3'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 200-MT'>AB 200-MT</td><td  row-value='2018'>2,018</td><td  row-value='20'>20</td><td  row-value='4'>4</td></tr>
            <tr row-index='4'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'>AB250-HL</td><td  row-value='2017'>2,017</td><td  row-value='9'>9</td><td  row-value='5'>5</td></tr>
            <tr row-index='5'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'></td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='6'>6</td></tr>
            <tr row-index='6'  ><td  row-value='ACTIVA'>ACTIVA</td><td  row-value='AC300ZH-1'>AC300ZH-1</td><td  row-value='2017'>2,017</td><td  row-value='478'>478</td><td  row-value='7'>7</td></tr>
            <tr row-index='7'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'></td><td  row-value='2018'>2,018</td><td  row-value='199'>199</td><td  row-value='8'>8</td></tr>
            <tr row-index='8'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-2'>AC300ZH-2</td><td  row-value='2017'>2,017</td><td  row-value='30'>30</td><td  row-value='9'>9</td></tr>
            <tr row-index='9'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-3'>AC300ZH-3</td><td  row-value='2017'>2,017</td><td  row-value='18'>18</td><td  row-value='10'>10</td></tr>
            <tr row-index='10'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'>LK200ZH</td><td  row-value='2017'>2,017</td><td  row-value='55'>55</td><td  row-value='11'>11</td></tr>
            <tr row-index='11'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'></td><td  row-value='2018'>2,018</td><td  row-value='55'>55</td><td  row-value='12'>12</td></tr>
            <tr row-index='12'  ><td  row-value='ACTIVA'></td><td  row-value='LK250ZH'>LK250ZH</td><td  row-value='2017'>2,017</td><td  row-value='60'>60</td><td  row-value='13'>13</td></tr>
            <tr row-index='13'  ><td  row-value='ACURA'>ACURA</td><td  row-value='MDX'>MDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='14'>14</td></tr>
            <tr row-index='14'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2013'>2,013</td><td  row-value='1'>1</td><td  row-value='15'>15</td></tr>
            <tr row-index='15'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2014'>2,014</td><td  row-value='1'>1</td><td  row-value='16'>16</td></tr>
            <tr row-index='16'  ><td  row-value='ACURA'></td><td  row-value='RDX'>RDX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='17'>17</td></tr>
            <tr row-index='17'  ><td  row-value='ACURA'></td><td  row-value='RDX'></td><td  row-value='2015'>2,015</td><td  row-value='1'>1</td><td  row-value='18'>18</td></tr>
            <tr row-index='18'  ><td  row-value='ACURA'></td><td  row-value='RLX'>RLX</td><td  row-value='2017'>2,017</td><td  row-value='1'>1</td><td  row-value='19'>19</td></tr>
            <tr row-index='19'  ><td  row-value='ACURA'></td><td  row-value='RSX'>RSX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='20'>20</td></tr>
            <tr row-index='20'  ><td  row-value='ACURA'></td><td  row-value='TL'>TL</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='21'>21</td></tr>
            <tr row-index='21'  ><td  row-value='ACURA'></td><td  row-value='ZDX'>ZDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='22'>22</td></tr>
                            </tbody>
</table>
</div>
<script type="text/javascript">
    var ktableex5baefe9db21931 = new KoolPHPTable('ktableex5baefe9db21931',{"cKeys":["MARCA","MODELO","ANO","CONTADOR","#"],"paging":null});
</script>
</body>
</html>

Thats the standard output that can be done with the standard Table Widget.<br/><br/>
***
VeritradeAutos.view.php (case 2)

```
<?php
use \koolreport\futurelabs\TableEx;

TableEx::create([
    "dataStore" => $this->dataStore('Veritrade'),
    "columns" => [
        "BRAND","MODEL","YEAR","COUNTER","CC" => "#"
    ],
    "removeDuplicate" => [
        "fields" => [
            "BRAND" => [
                "agg" => [
                    "sum" => [
                        "COUNTER" => [
                            "formatValue" => "$ @value",
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                        ]
                    ],
                    "max" => [
                        "YEAR" => [
                            "formatValue" => function ($value) {
                                return "[".$value."]";
                            },
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                        ]
                    ],
                    "dcount" => [
                            "MODEL" => [
                                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ]
                    ]
                ],
                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                "totalText" => "Total @fname :",
                "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"
            ],
            "MODEL" => [
                "agg" => [
                    "avg" => ["COUNTER"],
                    "count" => [
                        "YEAR" => [
                            "formatValue" => function ($value) {
                                return "<".$value.">";
                            },
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:yellow;"
                        ]
                    ]
                ],
                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:papayawhip;",
                "totalText" => "Total @fname :",
                "totalCss" => "text-align:left;font-weight: bold;background-color:papayawhip;"
            ]
        ],
        "options" => [
            "showfooter" => "bottom",
            "style" => "one_line"
        ]
    ]
]);
?>
```


<html>
<style>
.table {
    font-size: 11px;
}
.table > tbody > tr > td {
    line-height: normal;
    border-top: 0px;
    padding: 2px;
}
</style>
<body>
<script type='text/javascript' src='/koolreport/clients/jquery/jquery.min.js'></script>
<link type='text/css' rel='stylesheet' href='/koolreport/packages/futurelabs/table/table.css'></link>
<script type='text/javascript' src='/koolreport/packages/futurelabs/table/table.js'></script>
<div class="reppage-container">
<div class="koolphp-table " id="ktableex5baf0f000f9281">
<table class='table' border='1'>
                    <thead>
                        <tr>
                <th>BRAND</th><th>MODEL</th><th>YEAR</th><th>COUNTER</th><th>#</th>            </tr>
            </thead>
<tbody>
        <tr row-index='0'  ><td  row-value='AB MOTORS'>AB MOTORS</td><td  row-value='AB 150-HR'>AB 150-HR</td><td  row-value='2017'>2,017</td><td  row-value='8'>8</td><td  row-value='1'>1</td></tr>
            <tr row-index='1'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'></td><td  row-value='2018'>2,018</td><td  row-value='10'>10</td><td  row-value='2'>2</td></tr>
            <tr row-index='2'    lvl='2'><td  row-value='AB 150-HR' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 150-HR :</td><td  row-value='AB 150-HR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>9</td><td  row-value='AB 150-HR'></td></tr><tr row-index='3'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-TR'>AB 150-TR</td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='3'>3</td></tr>
            <tr row-index='4'    lvl='2'><td  row-value='AB 150-TR' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 150-TR :</td><td  row-value='AB 150-TR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>15</td><td  row-value='AB 150-TR'></td></tr><tr row-index='5'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 200-MT'>AB 200-MT</td><td  row-value='2018'>2,018</td><td  row-value='20'>20</td><td  row-value='4'>4</td></tr>
            <tr row-index='6'    lvl='2'><td  row-value='AB 200-MT' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 200-MT :</td><td  row-value='AB 200-MT' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>20</td><td  row-value='AB 200-MT'></td></tr><tr row-index='7'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'>AB250-HL</td><td  row-value='2017'>2,017</td><td  row-value='9'>9</td><td  row-value='5'>5</td></tr>
            <tr row-index='8'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'></td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='6'>6</td></tr>
            <tr row-index='9'    lvl='2'><td  row-value='AB250-HL' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB250-HL :</td><td  row-value='AB250-HL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>12</td><td  row-value='AB250-HL'></td></tr><tr row-index='10'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total AB MOTORS :</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>4</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 77</td><td  row-value='AB MOTORS'></td></tr><tr row-index='11'  ><td  row-value='ACTIVA'>ACTIVA</td><td  row-value='AC300ZH-1'>AC300ZH-1</td><td  row-value='2017'>2,017</td><td  row-value='478'>478</td><td  row-value='7'>7</td></tr>
            <tr row-index='12'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'></td><td  row-value='2018'>2,018</td><td  row-value='199'>199</td><td  row-value='8'>8</td></tr>
            <tr row-index='13'    lvl='2'><td  row-value='AC300ZH-1' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-1 :</td><td  row-value='AC300ZH-1' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>338.5</td><td  row-value='AC300ZH-1'></td></tr><tr row-index='14'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-2'>AC300ZH-2</td><td  row-value='2017'>2,017</td><td  row-value='30'>30</td><td  row-value='9'>9</td></tr>
            <tr row-index='15'    lvl='2'><td  row-value='AC300ZH-2' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-2 :</td><td  row-value='AC300ZH-2' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>30</td><td  row-value='AC300ZH-2'></td></tr><tr row-index='16'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-3'>AC300ZH-3</td><td  row-value='2017'>2,017</td><td  row-value='18'>18</td><td  row-value='10'>10</td></tr>
            <tr row-index='17'    lvl='2'><td  row-value='AC300ZH-3' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-3 :</td><td  row-value='AC300ZH-3' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>18</td><td  row-value='AC300ZH-3'></td></tr><tr row-index='18'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'>LK200ZH</td><td  row-value='2017'>2,017</td><td  row-value='55'>55</td><td  row-value='11'>11</td></tr>
            <tr row-index='19'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'></td><td  row-value='2018'>2,018</td><td  row-value='55'>55</td><td  row-value='12'>12</td></tr>
            <tr row-index='20'    lvl='2'><td  row-value='LK200ZH' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total LK200ZH :</td><td  row-value='LK200ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>55</td><td  row-value='LK200ZH'></td></tr><tr row-index='21'  ><td  row-value='ACTIVA'></td><td  row-value='LK250ZH'>LK250ZH</td><td  row-value='2017'>2,017</td><td  row-value='60'>60</td><td  row-value='13'>13</td></tr>
            <tr row-index='22'    lvl='2'><td  row-value='LK250ZH' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total LK250ZH :</td><td  row-value='LK250ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>60</td><td  row-value='LK250ZH'></td></tr><tr row-index='23'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total ACTIVA :</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>5</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 895</td><td  row-value='ACTIVA'></td></tr><tr row-index='24'  ><td  row-value='ACURA'>ACURA</td><td  row-value='MDX'>MDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='14'>14</td></tr>
            <tr row-index='25'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2013'>2,013</td><td  row-value='1'>1</td><td  row-value='15'>15</td></tr>
            <tr row-index='26'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2014'>2,014</td><td  row-value='1'>1</td><td  row-value='16'>16</td></tr>
            <tr row-index='27'    lvl='2'><td  row-value='MDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total MDX :</td><td  row-value='MDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;3&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1.3333333333333</td><td  row-value='MDX'></td></tr><tr row-index='28'  ><td  row-value='ACURA'></td><td  row-value='RDX'>RDX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='17'>17</td></tr>
            <tr row-index='29'  ><td  row-value='ACURA'></td><td  row-value='RDX'></td><td  row-value='2015'>2,015</td><td  row-value='1'>1</td><td  row-value='18'>18</td></tr>
            <tr row-index='30'    lvl='2'><td  row-value='RDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RDX :</td><td  row-value='RDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RDX'></td></tr><tr row-index='31'  ><td  row-value='ACURA'></td><td  row-value='RLX'>RLX</td><td  row-value='2017'>2,017</td><td  row-value='1'>1</td><td  row-value='19'>19</td></tr>
            <tr row-index='32'    lvl='2'><td  row-value='RLX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RLX :</td><td  row-value='RLX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RLX'></td></tr><tr row-index='33'  ><td  row-value='ACURA'></td><td  row-value='RSX'>RSX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='20'>20</td></tr>
            <tr row-index='34'    lvl='2'><td  row-value='RSX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RSX :</td><td  row-value='RSX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RSX'></td></tr><tr row-index='35'  ><td  row-value='ACURA'></td><td  row-value='TL'>TL</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='21'>21</td></tr>
            <tr row-index='36'    lvl='2'><td  row-value='TL' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total TL :</td><td  row-value='TL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='TL'></td></tr><tr row-index='37'  ><td  row-value='ACURA'></td><td  row-value='ZDX'>ZDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='22'>22</td></tr>
            <tr row-index='39'    lvl='2'><td  row-value='ZDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total ZDX :</td><td  row-value='ZDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>2</td><td  row-value='ZDX'></td></tr><tr row-index='38'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total ACURA :</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>6</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2017]</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 11</td><td  row-value='ACURA'></td></tr>                
            </tbody>
</table>
</div>
<script type="text/javascript">
    var ktableex5baf0f000f9281 = new KoolPHPTable('ktableex5baf0f000f9281',{"cKeys":["BRAND","MODEL","YEAR","COUNTER","#"],"paging":null});
</script>
</div>
</body>
</html>


Here the total aggregates are at the bottom of each group , also is showing the format of each aggregate column and style.<br/>
If we change the option style to none

```
        "options" => [
            "showfooter" => "bottom"
        ]
```

The output change to:


<html>
<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css"/>
<style>
.table {
    font-size: 11px;
}
.table > tbody > tr > td {
    line-height: normal;
    border-top: 0px;
    padding: 2px;
}
</style>

<body>
<script type='text/javascript' src='/koolreport/clients/jquery/jquery.min.js'></script>
<link type='text/css' rel='stylesheet' href='/koolreport/packages/futurelabs/table/table.css'></link>
<script type='text/javascript' src='/koolreport/packages/futurelabs/table/table.js'></script>
<div class="reppage-container">
<div class="koolphp-table " id="ktableex5bafcde4493321">
<table class='table' border='1'>
                    <thead>
                        <tr>
                <th>BRAND</th><th>MODEL</th><th>YEAR</th><th>COUNTER</th><th>#</th>            </tr>
            </thead>
                            <tbody>
        <tr row-index='0'  ><td  row-value='AB MOTORS'>AB MOTORS</td></tr><tr row-index='1'    lvl='1'><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'>AB 150-HR</td><td  row-value='2017'>2,017</td><td  row-value='8'>8</td><td  row-value='1'>1</td></tr>
            <tr row-index='2'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'></td><td  row-value='2018'>2,018</td><td  row-value='10'>10</td><td  row-value='2'>2</td></tr>
            <tr row-index='3'    lvl='2'><td  row-value='AB 150-HR' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 150-HR :</td><td  row-value='AB 150-HR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>9</td><td  row-value='AB 150-HR'></td></tr><tr row-index='4'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-TR'>AB 150-TR</td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='3'>3</td></tr>
            <tr row-index='5'    lvl='2'><td  row-value='AB 150-TR' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 150-TR :</td><td  row-value='AB 150-TR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>15</td><td  row-value='AB 150-TR'></td></tr><tr row-index='6'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 200-MT'>AB 200-MT</td><td  row-value='2018'>2,018</td><td  row-value='20'>20</td><td  row-value='4'>4</td></tr>
            <tr row-index='7'    lvl='2'><td  row-value='AB 200-MT' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB 200-MT :</td><td  row-value='AB 200-MT' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>20</td><td  row-value='AB 200-MT'></td></tr><tr row-index='8'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'>AB250-HL</td><td  row-value='2017'>2,017</td><td  row-value='9'>9</td><td  row-value='5'>5</td></tr>
            <tr row-index='9'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'></td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='6'>6</td></tr>
            <tr row-index='10'    lvl='2'><td  row-value='AB250-HL' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AB250-HL :</td><td  row-value='AB250-HL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>12</td><td  row-value='AB250-HL'></td></tr><tr row-index='11'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total AB MOTORS :</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>4</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 77</td><td  row-value='AB MOTORS'></td></tr><tr row-index='12'  ><td  row-value='ACTIVA'>ACTIVA</td></tr><tr row-index='13'    lvl='1'><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'>AC300ZH-1</td><td  row-value='2017'>2,017</td><td  row-value='478'>478</td><td  row-value='7'>7</td></tr>
            <tr row-index='14'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'></td><td  row-value='2018'>2,018</td><td  row-value='199'>199</td><td  row-value='8'>8</td></tr>
            <tr row-index='15'    lvl='2'><td  row-value='AC300ZH-1' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-1 :</td><td  row-value='AC300ZH-1' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>338.5</td><td  row-value='AC300ZH-1'></td></tr><tr row-index='16'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-2'>AC300ZH-2</td><td  row-value='2017'>2,017</td><td  row-value='30'>30</td><td  row-value='9'>9</td></tr>
            <tr row-index='17'    lvl='2'><td  row-value='AC300ZH-2' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-2 :</td><td  row-value='AC300ZH-2' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>30</td><td  row-value='AC300ZH-2'></td></tr><tr row-index='18'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-3'>AC300ZH-3</td><td  row-value='2017'>2,017</td><td  row-value='18'>18</td><td  row-value='10'>10</td></tr>
            <tr row-index='19'    lvl='2'><td  row-value='AC300ZH-3' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total AC300ZH-3 :</td><td  row-value='AC300ZH-3' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>18</td><td  row-value='AC300ZH-3'></td></tr><tr row-index='20'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'>LK200ZH</td><td  row-value='2017'>2,017</td><td  row-value='55'>55</td><td  row-value='11'>11</td></tr>
            <tr row-index='21'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'></td><td  row-value='2018'>2,018</td><td  row-value='55'>55</td><td  row-value='12'>12</td></tr>
            <tr row-index='22'    lvl='2'><td  row-value='LK200ZH' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total LK200ZH :</td><td  row-value='LK200ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>55</td><td  row-value='LK200ZH'></td></tr><tr row-index='23'  ><td  row-value='ACTIVA'></td><td  row-value='LK250ZH'>LK250ZH</td><td  row-value='2017'>2,017</td><td  row-value='60'>60</td><td  row-value='13'>13</td></tr>
            <tr row-index='24'    lvl='2'><td  row-value='LK250ZH' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total LK250ZH :</td><td  row-value='LK250ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>60</td><td  row-value='LK250ZH'></td></tr><tr row-index='25'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total ACTIVA :</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>5</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 895</td><td  row-value='ACTIVA'></td></tr><tr row-index='26'  ><td  row-value='ACURA'>ACURA</td></tr><tr row-index='27'    lvl='1'><td  row-value='ACURA'></td><td  row-value='MDX'>MDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='14'>14</td></tr>
            <tr row-index='28'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2013'>2,013</td><td  row-value='1'>1</td><td  row-value='15'>15</td></tr>
            <tr row-index='29'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2014'>2,014</td><td  row-value='1'>1</td><td  row-value='16'>16</td></tr>
            <tr row-index='30'    lvl='2'><td  row-value='MDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total MDX :</td><td  row-value='MDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;3&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1.3333333333333</td><td  row-value='MDX'></td></tr><tr row-index='31'  ><td  row-value='ACURA'></td><td  row-value='RDX'>RDX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='17'>17</td></tr>
            <tr row-index='32'  ><td  row-value='ACURA'></td><td  row-value='RDX'></td><td  row-value='2015'>2,015</td><td  row-value='1'>1</td><td  row-value='18'>18</td></tr>
            <tr row-index='33'    lvl='2'><td  row-value='RDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RDX :</td><td  row-value='RDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RDX'></td></tr><tr row-index='34'  ><td  row-value='ACURA'></td><td  row-value='RLX'>RLX</td><td  row-value='2017'>2,017</td><td  row-value='1'>1</td><td  row-value='19'>19</td></tr>
            <tr row-index='35'    lvl='2'><td  row-value='RLX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RLX :</td><td  row-value='RLX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RLX'></td></tr><tr row-index='36'  ><td  row-value='ACURA'></td><td  row-value='RSX'>RSX</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='20'>20</td></tr>
            <tr row-index='37'    lvl='2'><td  row-value='RSX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total RSX :</td><td  row-value='RSX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RSX'></td></tr><tr row-index='38'  ><td  row-value='ACURA'></td><td  row-value='TL'>TL</td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='21'>21</td></tr>
            <tr row-index='39'    lvl='2'><td  row-value='TL' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total TL :</td><td  row-value='TL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='TL'></td></tr><tr row-index='40'  ><td  row-value='ACURA'></td><td  row-value='ZDX'>ZDX</td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='22'>22</td></tr>
            <tr row-index='42'    lvl='2'><td  row-value='ZDX' colspan='1.'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>Total ZDX :</td><td  row-value='ZDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>2</td><td  row-value='ZDX'></td></tr><tr row-index='41'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>Total ACURA :</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>6</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2017]</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 11</td><td  row-value='ACURA'></td></tr>                </tbody>
</table>
</div>
<script type="text/javascript">
    var ktableex5bafcde4493321 =
    new KoolPHPTable('ktableex5bafcde4493321',{"cKeys":["MARCA","MODELO","ANO","CONTADOR","#"],"paging":null});
    </script>
</div>
</body>
</html>


Finally , the other variant es when we put in options top , like:

```
        "options" => [
            "showfooter" => "top"
        ]
```

the output for this option will be like:


<html>
<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css"/>
<style>
.table {
    font-size: 11px;
}
.table > tbody > tr > td {
    line-height: normal;
    border-top: 0px;
    padding: 2px;
}
</style>

<body>
<script type='text/javascript' src='/koolreport/clients/jquery/jquery.min.js'></script>
<link type='text/css' rel='stylesheet' href='/koolreport/packages/futurelabs/table/table.css'></link>
<script type='text/javascript' src='/koolreport/packages/futurelabs/table/table.js'></script>
<div class="reppage-container">
<div class="koolphp-table " id="ktableex5bafd1415546a1">
<table class='table' border='1'>
                    <thead>
                        <tr>
                <th>MARCA</th><th>MODELO</th><th>ANO</th><th>CONTADOR</th><th>#</th>            </tr>
            </thead>
                            <tbody>
        <tr row-index='0'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>AB MOTORS</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>4</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='AB MOTORS' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 77</td><td  row-value='AB MOTORS'></td></tr><tr row-index='1'    lvl='2'><td  row-value='AB 150-HR' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AB 150-HR</td><td  row-value='AB 150-HR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>9</td><td  row-value='AB 150-HR'></td></tr><tr row-index='2'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'></td><td  row-value='2017'>2,017</td><td  row-value='8'>8</td><td  row-value='1'>1</td></tr>
            <tr row-index='3'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-HR'></td><td  row-value='2018'>2,018</td><td  row-value='10'>10</td><td  row-value='2'>2</td></tr>
            <tr row-index='4'    lvl='2'><td  row-value='AB 150-TR' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AB 150-TR</td><td  row-value='AB 150-TR' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>15</td><td  row-value='AB 150-TR'></td></tr><tr row-index='5'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 150-TR'></td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='3'>3</td></tr>
            <tr row-index='6'    lvl='2'><td  row-value='AB 200-MT' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AB 200-MT</td><td  row-value='AB 200-MT' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>20</td><td  row-value='AB 200-MT'></td></tr><tr row-index='7'  ><td  row-value='AB MOTORS'></td><td  row-value='AB 200-MT'></td><td  row-value='2018'>2,018</td><td  row-value='20'>20</td><td  row-value='4'>4</td></tr>
            <tr row-index='8'    lvl='2'><td  row-value='AB250-HL' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AB250-HL</td><td  row-value='AB250-HL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>12</td><td  row-value='AB250-HL'></td></tr><tr row-index='9'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'></td><td  row-value='2017'>2,017</td><td  row-value='9'>9</td><td  row-value='5'>5</td></tr>
            <tr row-index='10'  ><td  row-value='AB MOTORS'></td><td  row-value='AB250-HL'></td><td  row-value='2018'>2,018</td><td  row-value='15'>15</td><td  row-value='6'>6</td></tr>
            <tr row-index='11'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>ACTIVA</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>5</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2018]</td><td  row-value='ACTIVA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 895</td><td  row-value='ACTIVA'></td></tr><tr row-index='12'    lvl='2'><td  row-value='AC300ZH-1' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AC300ZH-1</td><td  row-value='AC300ZH-1' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>338.5</td><td  row-value='AC300ZH-1'></td></tr><tr row-index='13'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'></td><td  row-value='2017'>2,017</td><td  row-value='478'>478</td><td  row-value='7'>7</td></tr>
            <tr row-index='14'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-1'></td><td  row-value='2018'>2,018</td><td  row-value='199'>199</td><td  row-value='8'>8</td></tr>
            <tr row-index='15'    lvl='2'><td  row-value='AC300ZH-2' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AC300ZH-2</td><td  row-value='AC300ZH-2' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>30</td><td  row-value='AC300ZH-2'></td></tr><tr row-index='16'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-2'></td><td  row-value='2017'>2,017</td><td  row-value='30'>30</td><td  row-value='9'>9</td></tr>
            <tr row-index='17'    lvl='2'><td  row-value='AC300ZH-3' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>AC300ZH-3</td><td  row-value='AC300ZH-3' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>18</td><td  row-value='AC300ZH-3'></td></tr><tr row-index='18'  ><td  row-value='ACTIVA'></td><td  row-value='AC300ZH-3'></td><td  row-value='2017'>2,017</td><td  row-value='18'>18</td><td  row-value='10'>10</td></tr>
            <tr row-index='19'    lvl='2'><td  row-value='LK200ZH' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>LK200ZH</td><td  row-value='LK200ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>55</td><td  row-value='LK200ZH'></td></tr><tr row-index='20'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'></td><td  row-value='2017'>2,017</td><td  row-value='55'>55</td><td  row-value='11'>11</td></tr>
            <tr row-index='21'  ><td  row-value='ACTIVA'></td><td  row-value='LK200ZH'></td><td  row-value='2018'>2,018</td><td  row-value='55'>55</td><td  row-value='12'>12</td></tr>
            <tr row-index='22'    lvl='2'><td  row-value='LK250ZH' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>LK250ZH</td><td  row-value='LK250ZH' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>60</td><td  row-value='LK250ZH'></td></tr><tr row-index='23'  ><td  row-value='ACTIVA'></td><td  row-value='LK250ZH'></td><td  row-value='2017'>2,017</td><td  row-value='60'>60</td><td  row-value='13'>13</td></tr>
            <tr row-index='24'    lvl='1'><td  style='text-align:left;font-weight: bold;background-color:moccasin;'>ACURA</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>6</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>[2017]</td><td  row-value='ACURA' style='text-align:right;color:black;font-weight: bold;background-color:orange;'>$ 11</td><td  row-value='ACURA'></td></tr><tr row-index='25'    lvl='2'><td  row-value='MDX' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>MDX</td><td  row-value='MDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;3&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1.3333333333333</td><td  row-value='MDX'></td></tr><tr row-index='26'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='14'>14</td></tr>
            <tr row-index='27'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2013'>2,013</td><td  row-value='1'>1</td><td  row-value='15'>15</td></tr>
            <tr row-index='28'  ><td  row-value='ACURA'></td><td  row-value='MDX'></td><td  row-value='2014'>2,014</td><td  row-value='1'>1</td><td  row-value='16'>16</td></tr>
            <tr row-index='29'    lvl='2'><td  row-value='RDX' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>RDX</td><td  row-value='RDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;2&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RDX'></td></tr><tr row-index='30'  ><td  row-value='ACURA'></td><td  row-value='RDX'></td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='17'>17</td></tr>
            <tr row-index='31'  ><td  row-value='ACURA'></td><td  row-value='RDX'></td><td  row-value='2015'>2,015</td><td  row-value='1'>1</td><td  row-value='18'>18</td></tr>
            <tr row-index='32'    lvl='2'><td  row-value='RLX' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>RLX</td><td  row-value='RLX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RLX'></td></tr><tr row-index='33'  ><td  row-value='ACURA'></td><td  row-value='RLX'></td><td  row-value='2017'>2,017</td><td  row-value='1'>1</td><td  row-value='19'>19</td></tr>
            <tr row-index='34'    lvl='2'><td  row-value='RSX' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>RSX</td><td  row-value='RSX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='RSX'></td></tr><tr row-index='35'  ><td  row-value='ACURA'></td><td  row-value='RSX'></td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='20'>20</td></tr>
            <tr row-index='36'    lvl='2'><td  row-value='TL' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>TL</td><td  row-value='TL' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>1</td><td  row-value='TL'></td></tr><tr row-index='37'  ><td  row-value='ACURA'></td><td  row-value='TL'></td><td  row-value='2012'>2,012</td><td  row-value='1'>1</td><td  row-value='21'>21</td></tr>
            <tr row-index='38'    lvl='2'><td  row-value='ZDX' colspan='1'></td><td  style='text-align:left;font-weight: bold;background-color:papayawhip;'>ZDX</td><td  row-value='ZDX' style='text-align:right;color:black;font-weight: bold;background-color:yellow;'>&lt;1&gt;</td><td style='text-align:right;color:black;font-weight: bold;background-color:papayawhip;'>2</td><td  row-value='ZDX'></td></tr><tr row-index='39'  ><td  row-value='ACURA'></td><td  row-value='ZDX'></td><td  row-value='2012'>2,012</td><td  row-value='2'>2</td><td  row-value='22'>22</td></tr>
                            </tbody>
</table>
</div>
<script type="text/javascript">
    var ktableex5bafd1415546a1 =
    new KoolPHPTable('ktableex5bafd1415546a1',{"cKeys":["MARCA","MODELO","ANO","CONTADOR","#"],"paging":null});
    </script>
</div>
</body>
</html>


## What you cant do
Never use the same aggregate column for the same field in more than one aggregate definition.<br/>
For example , this will not work:<br/>

```

   "removeDuplicate" => [
        "fields" => [
            "BRAND" => [
                "agg" => [
                    "sum" => [
                        "COUNTER" => [
                            "formatValue" => "$ @value",
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                        ]
                    ],
                    "max" => [
                        "COUNTER" => [
                            "formatValue" => function ($value) {
                                return "[".$value."]";
                            },
                            "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                        ]
                    ],
                    "dcount" => [
                            "MODEL" => [
                                    "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:orange;"
                            ]
                    ]
                ],
                "cssStyle" => "text-align:right;color:black;font-weight: bold;background-color:moccasin;",
                "totalText" => "Total @fname :",
                "totalCss" => "text-align:left;font-weight: bold;background-color:moccasin;"
            ]
        ]
```

Here , sum and max use COUNTER for the same BRAND field , this is not allowed , the reaason is obvious , we can put 2 aggregates on the same column report.


## Support

you may send email to us at __aranape@gmail.com__.
