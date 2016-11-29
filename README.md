# GCAlerts

## Description
Drupal 8.x module to centralise and expose alerts for the Gold Coast region populated by external organisations and departments.
This is being developed as and educational task to understand Drupal and learn how to develop Drupal modules.

I cannot be responsible for the quality or reliability of this information and the methods used to retrieve this.
Any legal or licensing implications with retrieving this information is your responsibility.

Finally if there are improvements to be made, you are welcomed to contribute.

Ideally in the future this could be extended to a coordinate sources for a number of regions, however at this point in time I've contained the scope.

## Proof of concept
A quick proof of concept was developed in JavaScript to indicate the requirement for server-side caching.

## Information sources
### Suburbs
As a rudimendary region search, a list of suburbs can be used to filter alerts.
Suburbs are available via data.gov.au as Open Data 
https://data.gov.au/dataset/gold-coast-streets-and-suburbs/resource/ad3a8b32-c826-4bc4-ba0a-009d38257ea0

This source/method should probably be improved at a later date with a spatial query.

### Weather
Bureau of Meteorology (http://www.bom.gov.au) expose state-wide weather warnings. The page for QLD is http://www.bom.gov.au/qld/warnings/
An RSS feed is available for this: http://www.bom.gov.au/fwo/IDZ00056.warnings_qld.xml

### Traffic
QLD Traffic (https://qldtraffic.qld.gov.au) expose a list of incidents on their website

The datasource for their list is in geo json format which can be flattened if required
https://data.qldtraffic.qld.gov.au/events.geojson

### Power
Energex is the energy distributor on the Gold Coast. Their website lists
 * Planned power outages: https://www.energex.com.au/home/power-outages/planned-maintenance-outages
 * Emergency power outages: https://www.energex.com.au/home/power-outages/emergency-outages

Luckily these lists are popualted from a Funnelback search engine and can be consumed directly 
 * https://www.energex.com.au/fb-search/test-proxies/search.json-proxy/_nocache?sort=metaL&num_ranks=5000&collection=energex-maintenance-outages-v2&query=!null
 * https://www.energex.com.au/fb-search/test-proxies/search.json-proxy/_nocache?sort=metaL&num_ranks=500&collection=energex-outages-v2&query=!null

I've noticed a "test-proxies" in the url of both search strings which would be likely to change at a later date

### Fire
The QLD rural fire service have a map of bushfire incidents available at https://ruralfire.qld.gov.au/map/Pages/default.aspx

Current bushfire incidents are available on data.gov.au as Open Data 
https://data.qld.gov.au/dataset/queensland-fire-and-rescue-current-bushfire-incidents
