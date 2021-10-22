# Datadog widget to link to Grafana

Takes specified input value from the config (which graph to draw), and fetches a graph of that name from Wikimedia's Grafana output by hitting their API with PHP.

A slightly updated version of https://github.com/stuartlangridge/dd-app-plain-html-grafana which dynamically queries the API for the Grafana instance to list its dashboards and panels so that one can be embedded. (At the moment it's embedded by embedding the iframe, but that's just a demonstration.) The important point is the dynamic nature of it; it queries Grafana in realtime.