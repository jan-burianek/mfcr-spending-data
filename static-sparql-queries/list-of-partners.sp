prefix obeu-attribute:   <http://data.openbudgets.eu/ontology/dsd/attribute/>
prefix obeu-currency:    <http://data.openbudgets.eu/resource/codelist/currency/>
prefix obeu-dimension:   <http://data.openbudgets.eu/ontology/dsd/dimension/>
prefix obeu-measure:     <http://data.openbudgets.eu/ontology/dsd/measure/>
prefix obeu-operation:   <http://data.openbudgets.eu/resource/codelist/operation-character/>
prefix qb:               <http://purl.org/linked-data/cube#>
prefix schema:           <http://schema.org/>
prefix xsd:              <http://www.w3.org/2001/XMLSchema#>
prefix obeu-dimension:   <http://data.openbudgets.eu/ontology/dsd/dimension/>
prefix obeu-operation:   <http://data.openbudgets.eu/resource/codelist/operation-character/>
prefix obeu-measure:     <http://data.openbudgets.eu/ontology/dsd/measure/>
prefix qb:               <http://purl.org/linked-data/cube#>
prefix mfcr-dimension:   <http://data.janburianek.com/ontology/dsd/mfcr-paid-invoices-2010-2014/dimension/>
prefix mfcr-attribute:   <http://data.janburianek.com/ontology/dsd/mfcr-paid-invoices-2010-2014/attribute/>
prefix dcterms:          <http://purl.org/dc/terms/>
prefix rov:              <http://www.w3.org/ns/regorg#>

SELECT ?name SUM(?a) AS ?amount

WHERE {

  [] obeu-measure:amount ?a ;
  obeu-dimension:partner ?partner ;
  mfcr-dimension:transaction-date ?date .
  ?partner schema:legalName ?name .

  FILTER ( ?date >= "<year-from>-01-01"^^xsd:date )
  FILTER ( ?date < "<year-to>-01-01"^^xsd:date )
}
GROUP BY ?name
ORDER BY DESC(?amount)