<?php

$grafana = "https://grafana.wikimedia.org/";
$action = $_GET["action"];

header("Content-Type: application/json");

if ($action == "list-dashboards") {
    $res = file_get_contents($grafana . "api/search?query=%");
    $obj = json_decode($res, true);
    $dashboards = [];
    foreach ($obj as $key => $value) {
        if ($value["type"] == "dash-db") {
            $dashboards[] = array(
                "title" => $value["title"],
                "url" => $value["url"],
                "uid" => $value["uid"]
            );
        }
    }
    echo json_encode($dashboards);
} else if ($action == "list-panels") {
    $dashboard = $_GET["uid"];
    if (!$dashboard || !preg_match('/^\d+$/', $dashboard)) {
        echo json_encode(array("error" => "bad uid"));
        die();
    }
    $res = file_get_contents($grafana . "api/dashboards/uid/" . $dashboard);
    $obj = json_decode($res, true);

    $dashurl = $obj["meta"]["url"];
    // make it -solo
    $parts = explode("/", $dashurl);
    $parts[1] .= "-solo";
    $ndashurl = implode("/", $parts);

    $panels = [];
    foreach ($obj["dashboard"]["rows"] as $key => $row) {
        foreach($row["panels"] as $rowidx => $panel) {
            $end = round(microtime(true) * 1000);
            $start = $end - (1000 * 60 * 60 * 24 * 5);
            $embed = $grafana . $ndashurl . "?from=" . 
                $start . "&to=" . $end . "&panelId=" . $panel["id"];
            $panels[] = array(
                "title" => $panel["title"],
                "id" => $panel["id"],
                "embed" => $embed
            );
        }
    }

    echo json_encode($panels);
} else {
    echo json_encode(array("error" => "bad action"));
}

?>